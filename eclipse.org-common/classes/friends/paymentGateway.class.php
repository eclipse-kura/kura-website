<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

require_once("payment.class.php");
require_once("donation.class.php");
require_once("donationEmails.class.php");

/**
 * Base class for gateway class
 *
 * We currently have two supported gateway classes.
 * They are paypal or bitpay.
 *
 * @author chrisguindon
 *
 */
class PaymentGateway extends Payment {

 /**
  * Donation object
  */
  public $Donation = NULL;

 /**
  * Getway API key
  */
  protected $gateway_auth_token = '';

  /**
   * Gateway business e-mail address
   */
  protected $gateway_email = '';

  /**
   * Location of the gateway IPN script on our servers
   *
   * @var unknown
   */
  protected $gateway_notify_url = '';

  /**
   * Location of the donation preprocess form for credit cards.
   *
   * This is the script that will redirect the user
   * to the proper donation based off certain criteria.
   *
   * @var unknown
   */
  protected $gateway_credit_process_url = '';

    /**
   * Location of the donation preprocess form.
   *
   * This is the script that will redirect the user
   * to the proper donation based off certain criteria.
   *
   * @var unknown
   */
  protected $gateway_process_url = '';

  /**
   * Redirect url for process script.
   *
   * If possible, this url can be used to
   * automaticly redirect the user to the
   * payment gateway from the process script.
   *
   * @var unknown
   */
  protected $gateway_redirect = '';

  /**
   * Curl request response
   */
  protected $gateway_response = '';

  /**
   * Page where the user should when returning from payment gateway
   */
  protected $gateway_return_url = '';

  /**
   * Type of gateway
   *
   * This should probably set to bitpay or paypal
   * for now.
   */
  protected $gateway_type = "";

  /**
   * Payment gateway URL
   */
  protected $gateway_url = '';

  public function __construct()  {
    parent::__construct();
    $this->Donation = new Donation($this->_get_debug_mode());
    $domain = $this->getEclipseEnv();
    $this->_set_gateway_process_url('https://'. $this->_get_prefix_domain() . '/donate/process.php');
    $this->_set_gateway_return_url('https://'. $this->_get_prefix_domain() . '/donate/credit.php');
    $this->_set_gateway_credit_process_url('https://'. $domain['accounts'] . '/donate/process');
  }

  /**
   * Get gateway process url
   *
   * @return unknown
   */
  public function get_gateway_process_url() {
    return $this->gateway_process_url;
  }

  /**
   * Get gateway process url
   *
   * @return unknown
   */
  public function get_gateway_credit_process_url() {
    return $this->gateway_credit_process_url;
  }
  /**
   * Get gateway redirect value
   *
   * @return unknown
   */
  public function get_gateway_redirect() {
    if (empty($this->gateway_redirect)) {
      $this->_set_gateway_redirect();
    }
    return $this->gateway_redirect;
  }

  /**
   * Set a client message for returning bitpay donation
   */
  public function get_gateway_bitpay_thank_you() {
    $bitpay_return = $this->App->getHTTPParameter('bitpay_return', 'get');
    if ($bitpay_return == 'donation') {
      setcookie("thankyou_page[eclipse_donation]", TRUE, time() + (3600 * 24 * 360 * 10), '/', $this->_get_prefix_cookie());
      $this->set_client_message( '<strong>Thank you for your donation!</strong>', 'success');
    }
  }

  /**
   * Store form values from donation form or process script
   */
  public function update_friends_process_table($update = FALSE) {
    $fields = array(
      'id_unique' => $this->Donation->get_donation_random_invoice_id(),
      'uid' => $this->Donation->Donor->get_donor_uid(),
      'type' => $this->_get_gateway_type(),
      'domain' => $this->_get_prefix_domain(),
      'first_name' => $this->Donation->Donor->get_donor_first_name(),
      'last_name' => $this->Donation->Donor->get_donor_last_name(),
      'email' => $this->Donation->Donor->get_donor_email(),
      'amount' => $this->Donation->get_donation_amount(),
      'message' => $this->Donation->get_donation_message(),
      'subscription' => $this->Donation->get_donation_subscription(),
      'is_anonymous' => $this->Donation->get_donation_is_anonymous(),
      'redirect_url' => $this->get_gateway_redirect(),
      'status' => $this->Donation->get_donation_status(),
      'email_paypal' => $this->Donation->Donor->get_donor_paypal_email()
    );

    if ($update) {
      // On ipn update, we won't update the redirect_url
      unset($fields['redirect_url']);
    }

    $possible_null_field = array(
      'uid',
      'first_name',
      'last_name',
      'message',
      'email',
      'email_paypal',
      'amount',
      'domain'
    );

    $sql = $this->_sql_on_duplicate_update('friends_process', $fields, $possible_null_field);
    $this->App->eclipse_sql($sql);
  }

  public function set_posted_donation_values() {
    $this->Donation->Donor->set_donor_email($this->App->getHTTPParameter('email'));
    $this->Donation->Donor->set_donor_first_name($this->App->getHTTPParameter('first_name'));
    $this->Donation->Donor->set_donor_last_name($this->App->getHTTPParameter('last_name'));
    $this->Donation->set_donation_amount($this->App->getHTTPParameter('amount'));
    $this->Donation->set_donation_message($this->App->getHTTPParameter('message'));
    $this->Donation->set_donation_subscription($this->App->getHTTPParameter('subscription'));
    $this->Donation->set_donation_is_anonymous($this->App->getHTTPParameter('is_anonymous'));
    $this->Donation->set_donation_status('new_donation_form');
    $this->_set_gateway_redirect();
    $this->update_friends_process_table();
  }

  /**
   * Get gateway auth token.
   */
  protected function _get_gateway_auth_token(){
    if (empty($this->gateway_auth_token)) {
      $this->_set_gateway_auth_token();
    }
    return $this->gateway_auth_token;
  }

  /**
   * Get gateway business e-mail account
   */
  protected function _get_gateway_email() {
    return $this->gateway_email;
  }

  /**
   * Get gateway notify url
   *
   * @return string
   */
  protected function _get_gateway_notify_url() {
    return $this->gateway_notify_url;
  }

  /**
   * Get return URL
   *
   * @return unknown
   */
  protected function _get_gateway_return_url() {
    return $this->gateway_return_url;
  }

  /**
   * Get gateway redirect URL
   */
  protected function _get_gateway_redirect(){
    return $this->gateway_redirect;
  }

  /**
   * Get curl request response
   */
  protected function _get_gateway_response(){
    return $this->gateway_response;
  }

  /**
   * Get gateway type
   */
  protected function _get_gateway_type() {
   return $this->gateway_type;
  }

  /**
   * Get gateway URL
   *
   * @return unknown
   */
  protected function _get_gateway_url() {
    return $this->gateway_url;
  }

  /**
   * Set gateway auth token
   * @return string
   */
  protected function _set_gateway_auth_token($token = ""){
    $this->gateway_auth_token = $token;
  }

  /**
   * Set gateway business e-mail account
   *
   * @param unknown $email
   */
  protected function _set_gateway_email($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->gateway_email = $email;
    }
  }

  /**
   * Set gateway notify url
   *
   * @param string $url
   */
  protected function _set_gateway_notify_url($url = '') {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      $this->gateway_notify_url = $url;
    }
  }

  /**
   * Set gateway process url
   *
   * @param string $url
   */
 protected function _set_gateway_process_url($url = '') {
   if (filter_var($url, FILTER_VALIDATE_URL)) {
      $this->gateway_process_url = $url;
    }
  }

  /**
   * Set gateway process url
   *
   * @param string $url
   */
 protected function _set_gateway_credit_process_url($url = '') {
   if (filter_var($url, FILTER_VALIDATE_URL)) {
      $this->gateway_credit_process_url = $url;
    }
  }

  /**
   * Set gateway redirect url
   *
   * @param string $url
   */
  protected function _set_gateway_redirect($url = NULL) {
    $this->gateway_redirect = $this->_get_gateway_url();
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      $this->gateway_redirect = $url;
    }
  }

  /**
   * Set curl request response
   */
  protected function _set_gateway_response($res = ''){
    $this->gateway_response = $res;
  }

  /**
   * Set return URL
   *
   * @param string $url
   */
  protected function _set_gateway_return_url($url = NULL) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      $this->gateway_return_url = $url;
    }
  }

  /**
   * Set gateway type
   *
   * @param string $gateway_type
   */
  protected function _set_gateway_type($gateway_type = NULL){
    $haystack = array('bitpay', 'paypal');
    if (in_array($gateway_type, $haystack)) {
      $this->gateway_type = $gateway_type;
      $this->Donation->set_donation_currency($gateway_type);
    }
  }

  /**
   * Set gateway URL
   *
   * @param string $url
   */
  protected function _set_gateway_url($url = NULL) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      $this->gateway_url = $url;
    }
  }
}
