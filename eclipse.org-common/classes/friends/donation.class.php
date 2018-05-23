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
require_once(realpath(dirname(__FILE__) . "/../../system/session.class.php"));
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));
require_once(realpath(dirname(__FILE__) . "/../../system/cookies.class.php"));
require_once("donor.class.php");
require_once("donationEmails.class.php");

/**
 * Store information regarding a donation
 *
 * @author chrisguindon
 */
class Donation {

  /**
   * Eclipse $App()
   */
  private $App = NULL;

  /**
   * Cookies
   */
  private $Cookies = NULL;

  /**
   * Donation amount.
   */
  public $donation_amount = 0;

 /**
  * Donation worthy of Eclipse benefit.
  *
  * @var unknown
  */
  public $donation_benefit = 0;

  /**
   * Donation level / Benefit group
   */
  public $donation_benefit_group = '';

  /**
   * Currency used in donation.
   */
  public $donation_currency = "";


  /**
   * Visibility setting of donation
   */
  public $donation_is_anonymous = 1;

  /**
   * Comment left by the donor for this donation
   */
  public $donation_message = "";

  /**
   * Get random internal invoice id
   *
   * We use this value to fetch the value submitted by the
   * user before they made the donation.
   *
   * @var unknown
   */
  public $donation_random_invoice_id = '';

  /**
   * Payment status of a donation
   *
   * @var unknown
   */
  public $donation_status = "";

  /**
   * If this is a subscription donation or not.
   *
   * @var unknown
   */
  public $donation_subscription = 0;

  /**
   * Gateway Transaction id
   */
  public $donation_txn_id = '';

  /**
   * Donor object
   */
  public $Donor = NULL;

  public $table_prefix = FALSE;

  public function __construct($test_mode = FALSE)  {
    if ($test_mode === TRUE){
      $this->table_prefix = 'testing_';
    }
    $this->Donor = new Donor($test_mode);
    $this->App = new App();
    $this->Cookies = new Cookies();
  }

  /**
   * Validate if the user is passing the right info
   * for linking a donation with an eclipse account
   *
   * @return boolean
   */
  public function link_donation() {
    $txn_id = $this->App->getHTTPParameter('tid', 'get');
    $invoice_id = $this->App->getHTTPParameter('iid', 'get');

    if (!empty($txn_id) && !empty($invoice_id)){
      $this->set_donation_txn_id($txn_id);
      $this->set_donation_random_invoice_id($invoice_id);
      $this->Donor->set_donor_contribution_with_txn_id($this->get_donation_txn_id());
      $cid = $this->Donor->Contribution->getContributionID();
      if (empty($cid)){
        return FALSE;
      }

      if ($this->update_donor_from_process_table()) {
        $this->Donor->Friend->selectFriend($this->Donor->Contribution->getFriendID());
        $this->set_donation_amount($this->Donor->Contribution->getAmount());
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Try to update a donation with an eclipse account
   *
   * @return boolean/string
   */
  public function update_donation() {

    $uid = $this->Donor->get_donor_uid();
    $cfid = $this->Donor->Contribution->getFriendID();
    $fid = $this->Donor->Friend->getFriendID();
    if (!empty($uid) && $cfid == $fid) {
      return 'link_already_done';
    }
    $stage = $this->App->getHTTPParameter('form-stage', 'post');

    // Let's try to link automaticaly, if the user is logged in
    if (empty($stage)){
      $Session = new Session();
      $Friend = $Session->getFriend();
      $email = $Friend->getEmail();
      $this->Donor->set_donor_email($email);
      $uid = $this->Donor->get_donor_uid();
      if (!empty($uid)) {
        $this->get_or_create_friend();
        $this->Donor->Contribution->setFriendID($this->Donor->Friend->getFriendID());
        $this->Donor->Contribution->updateContribution();
        $DonationEmails = new DonationEmails($this);
        $DonationEmails->send_email();
        return 'updated';
      }
    }

    if ($stage == 'update') {
      $this->Donor->set_donor_email($this->App->getHTTPParameter('email', 'post'));
      $this->set_donation_message($this->App->getHTTPParameter('message', 'post'));
      $this->set_donation_is_anonymous($this->App->getHTTPParameter('is_anonymous', 'post'));
      $uid = $this->Donor->get_donor_uid();
      if (!empty($uid)) {
        $this->get_or_create_friend();
        $this->Donor->Contribution->setMessage($this->get_donation_message());
        $this->Donor->Contribution->setFriendID($this->Donor->Friend->getFriendID());
        $this->Donor->Contribution->updateContribution();
        $DonationEmails = new DonationEmails($this);
        $DonationEmails->send_email();
        return 'updated';
      }
      return 'invalid_eclipse_id';
    }
    return FALSE;
  }

  /**
   * Get human friendly string of $is_anonymous
   *
   * @return string
   */
  public function get_donation_is_anonymous_string() {
    if (!$this->donation_is_anonymous) {
      return 'Public';
    }
    return 'Private';
  }

  /**
   * Get donation amount
   *
   * @return number
   */
  public function get_donation_amount() {
    $this->_set_donation_benefit_level();
    return $this->donation_amount;
  }

  /**
   * Get donation_benefit value
   */
  public function get_donation_benefit(){
   return (int)$this->donation_benefit;
  }

  /**
   * Get donation_benefit group/level
   */
  public function get_donation_benefit_level() {
    $this->_set_donation_benefit_level();
    return $this->donation_benefit_group;
  }

  /**
   * Set donation benefit level
   *
   * This should only be called when we sent the donation
   * amount.
   */
  private function _set_donation_benefit_level() {
    $amount = $this->donation_amount;
    $currency = $this->get_donation_currency();

    // Access to the Friends of Eclipse mirrors.
    if ($amount >= 35) {
      $this->set_donation_benefit(1);
    }

    // Minimum donation of 250USD or 0.70 BTC
    if ($amount >= 250) {
      $this->donation_benefit_group = 'webmaster_idol';
    }
    // Minumum donation of 100USD or 0.25 BTC
    elseif ($amount >= 50) {
      $this->donation_benefit_group = 'best_friend';
    }
    // Minimum donation of 35USD or 0.15 BTC
    elseif ($amount >= 35) {
      $this->donation_benefit_group = 'friend';
    }
    else{
      $this->donation_benefit_group = 'donor';
    }
  }

  /**
   * Get donation currency type
   */
  public function get_donation_currency() {
    if (empty($this->donation_currency)) {
      $this->donation_currency = 'USD';
    }
    return $this->donation_currency;
  }

  /**
   * Get $is_anonymous value
   *
   * @return number
   */
  public function get_donation_is_anonymous(){
    return (int)$this->donation_is_anonymous;
  }

  /**
   * Get donation message
   *
   * @return string
   */
  public function get_donation_message() {
    return $this->donation_message;
  }

  /**
   * Get donation random invoice id
   *
   * @param number $length
   * @return Ambigous <string, unknown>
   */
  public function get_donation_random_invoice_id() {
    if (empty($this->donation_random_invoice_id)) {
      $this->set_donation_random_invoice_id();
    }
    return $this->donation_random_invoice_id;
  }

  /**
   * Get donation status
   *
   * @return unknown
   */
  public function get_donation_status() {
    if (empty($this->donation_status)){
      $this->set_donation_status('initial');
    }
    return strtoupper($this->donation_status);
  }

  /**
   * Get donation_subscription value
   *
   * @return unknown
   */
  public function get_donation_subscription(){
    return (int)$this->donation_subscription;
  }


  /**
   * Get id for transaction
   * @param string $txn_id
   */
  public function get_donation_txn_id() {
    return $this->donation_txn_id;
  }

 public function get_or_create_friend() {
    $update = FALSE;
    $active_email = $this->Donor->get_active_email();
    $this->Donor->Friend->setEmail($active_email);
    $this->Donor->get_friend_id_from_uid();
    $this->Donor->Friend->setIsAnonymous($this->get_donation_is_anonymous());
    $this->Donor->Friend->setIsBenefit($this->get_donation_benefit());
    $new_friend_id = $this->Donor->Friend->insertUpdateFriend();
    $this->Donor->Friend->setFriendID($new_friend_id);
    $this->Donor->Contribution->setFriendID($new_friend_id);
  }

  /**
   * Set donation amount
   *
   * @param string $donation_amount
   */
  public function set_donation_amount($donation_amount = 0) {

    //Make sure the amount is a number and it's not 0
    if ($donation_amount == "0" or $donation_amount == "" or is_nan($donation_amount)) {
      $donation_amount = 35.00;
    }

    // Format the amount
    $donation_amount = number_format($donation_amount, 2);

    $this->donation_amount = $donation_amount;
    $this->_set_donation_benefit_level();
  }

 /**
   * Set donation_benefit value
   */
  public function set_donation_benefit($value) {
    if ($value === 1 || $value === '1' || $value === TRUE) {
      return $this->donation_benefit = 1;
    }
  }

  /**
   * Set donation currency type
   */
  public function set_donation_currency($currency = '') {
    $valid_currency = array('USD', 'BTC');
    $valid_type = array('PAYPAL', 'BITPAY');
    $currency = strtoupper($currency);
    if (in_array($currency, $valid_currency)) {
      $this->donation_currency = $currency;
    }
    // We might be passing $paymentGateway->gateway_type and
    // we know that we only accept USD for paypal and BTC from bitcoin.
    elseif (in_array($currency, $valid_type)) {
      if ($currency == 'PAYPAL') {
         $this->donation_currency = 'USD';
      }
      // @todo: verify if will accept more currencies with bitpay.
      elseif ($currency == 'BITPAY') {
        $this->donation_currency = 'BTC';
      }
    }
  }

  /**
   * Set $is_anonymous value
   *
   * @param unknown $value
   */
  public function set_donation_is_anonymous($value) {
    $this->donation_is_anonymous = 1;
    if ($value != "is_anonymous" && $value != 1) {
      $this->donation_is_anonymous = 0;
    }
  }

  /**
   * Set donation message
   *
   * @param unknown $message
   */
  public function set_donation_message($message) {
    $message = filter_var($message, FILTER_SANITIZE_STRING);
    $this->donation_message = strip_tags($message);
  }

  /**
   * Set donation random invoice id
   */
  public function set_donation_random_invoice_id($key = '') {
    $key = filter_var($key, FILTER_SANITIZE_STRING);
    if (empty($key)) {
      $length = 30;
      $keys = array_merge(range(0, 9), range('a', 'z'));

      for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
      }
    }
    $this->donation_random_invoice_id = $key;
  }

  /**
   * Set donation status
   *
   * @param unknown $status
   */
  public function set_donation_status($status) {
    $status = strtolower($status);
    $available_status = array(
      'completed', // paypal payment_status
      'no_eclipse_id', // Completed but addionnal steps are required to link donation with eclipse_id.
      'new_donation_form', // This was created by the process.php script.
      'confirmed' // bitpay payment status
    );

    if (in_array($status, $available_status)) {
      $this->donation_status = strtoupper($status);
    }
  }

  /**
   * Set donation subscription value
   * @param string $donation_subscription
   */
  public function set_donation_subscription($donation_subscription = NULL) {
    $this->donation_subscription = 0;
    if ($donation_subscription) {
      $this->donation_subscription = 1;
    }
  }

  /**
   * Set id for transaction
   * @param string $txn_id
   */
  public function set_donation_txn_id($txn_id = "") {
    $txn_id = filter_var($txn_id, FILTER_SANITIZE_STRING);
    $this->donation_txn_id = $txn_id;
  }

  /**
   * Update donor() & donation() based off the info from the
   * friends_process table.
   *
   * @return bool
   */
  public function update_donor_from_process_table() {
    $unique_id = $this->get_donation_random_invoice_id();
    $sql = 'SELECT /* USE MASTER */ * FROM ' . $this->table_prefix . 'friends_process WHERE id_unique = ';
    $sql .= $this->App->returnQuotedString($this->App->sqlSanitize($unique_id));
    $sql .= ' LIMIT 1';
    $rs = $this->App->eclipse_sql($sql);
    $process = mysql_fetch_assoc($rs);

    // We found a match in the friends_process table :)
    if (!empty($process)) {
      $this->Donor->set_donor_first_name($process['first_name']);
      $this->Donor->set_donor_last_name($process['last_name']);
      $this->set_donation_message($process['message']);
      $this->set_donation_subscription($process['subscription']);
      $this->set_donation_is_anonymous($process['is_anonymous']);
      $this->Donor->set_donor_email($process['email']);
      $this->Donor->set_donor_paypal_email($process['email_paypal']);
      $this->Donor->set_donor_uid($process['uid']);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Update/create Contribution from the IPN script
   *
   * @param string $update
   */
  public function update_donation_from_ipn($update = TRUE) {
    $this->get_or_create_friend();
    if ($this->Donor->Contribution->getContributionID() == "") {
      // Contribution Doesn't Already Exist
      $this->Donor->Contribution->setAmount($this->get_donation_amount());
      $this->Donor->Contribution->setMessage($this->get_donation_message());
      $this->Donor->Contribution->setTransactionID($this->get_donation_txn_id());
      $this->Donor->Contribution->setProcessId($this->get_donation_random_invoice_id());
      $this->Donor->Contribution->setCurrency($this->get_donation_currency());
      $this->Donor->Contribution->setFriendID($this->Donor->Friend->getFriendID());
      $this->Donor->Contribution->insertContribution();
    }
    else{
     // Update transaction. This should not append...
      //$this->Donor->Contribution->setProcessId($this->get_donation_random_invoice_id());
      $this->Donor->Contribution->setFriendID($this->Donor->Friend->getFriendID());
      $this->Donor->Contribution->updateContribution();
    }

    // Send out Emails
    $DonationEmails = new DonationEmails($this);
    $content = $DonationEmails->send_email();
  }

  /**
   * Output the HTML of the Banner Ad
   *
   * return string
   */
  public function outputBannerAd() {

    // Don't display the banner if the date is greater than Sept 30, 2016
    if(date("Y/m/d") > "2016/09/30") {
      return FALSE;
    }

    $banner_cookie = $this->getBannerCookies();

    // Check if the banner should NOT be visible
    // By default the visible value is 1
    // The banner should not be visible if the value is anything but 1
    if (!isset($banner_cookie['donation_banner']['value']['visible']) || $banner_cookie['donation_banner']['value']['visible'] !== 1) {
      return FALSE;
    }

    // Don't print the banner if the user is on the donate page or sub pages
    if (strpos($this->App->getCurrentURL(), '/donate/') !== FALSE) {
      return FALSE;
    }

    // Set the default content if the cookie is empty
    if (!isset($banner_cookie['donation_banner']['value']['template'])) {
      $banner_cookie['donation_banner']['value']['template'] = 1;
    }

    // Set the correct content for the template
    switch ($banner_cookie['donation_banner']['value']['template']) {
      case 1:
        $banner_content['title']    = '<h2>September Friend Campaign</h2>';
        $banner_content['text']     = '<p>Friends of Eclipse,</p>
                                       <p>Eclipse is an open source community that benefits millions of developers around
                                       the world each and every day! During the month of September, we are asking you
                                       to give back to our wonderful open source community. All donations will be used
                                       to improve Eclipse technology. Your contribution counts!</p>';
        $banner_content['thankyou'] = '<p>We thank you for this gesture, and for giving back to our community. <i class="fa fa-heart"></i></p>';
        $banner_content['campaign'] = "PROMO_DONATE_BANNER_1";
        break;
      case 2:
        $banner_content['title']    = '<h2>September Friend Campaign</h2>';
        $banner_content['text']     = '<p>Calling all Eclipse community members,</p>
                                       <p>This month we are asking you to support the future of the Eclipse community.
                                       Show your support for Eclipse by donating today. All donations will be used to
                                       improve the Eclipse platform.</p>';
        $banner_content['thankyou'] = '<p>Thank you for giving back! <i class="fa fa-heart"></i></p>';
        $banner_content['campaign'] = "PROMO_DONATE_BANNER_2";
        break;
      case 3:
        $banner_content['title']    = '<h2>September Friend Campaign</h2>';
        $banner_content['text']     = '<p>Friends of Eclipse,</p>
                                       <p>Eclipse is a free IDE used by millions of developers every day. If each
                                       developer donates the price of a coffee or beer, we can fund some amazing
                                       improvements to Eclipse. Please donate today.</p>';
        $banner_content['thankyou'] = '<p>Thank you for giving back! <i class="fa fa-heart"></i></p>';
        $banner_content['campaign'] = "PROMO_DONATE_BANNER_3";
        break;
      case 4:
        $banner_content['title']    = '<h2>September Friend Campaign</h2>';
        $banner_content['text']     = '<p>Eclipse Users,</p>
                                       <p>For 15 years, Eclipse has been providing great development tools and they are
                                       free! Today we need your support to fund the ongoing development of the Eclipse
                                       platform. Please donate and support Eclipse.</p>';
        $banner_content['thankyou'] = '<p>Thank you for giving back! <i class="fa fa-heart"></i></p>';
        $banner_content['campaign'] = "PROMO_DONATE_BANNER_4";
        break;
    }

    // Print the output if the user is a commiter
    ob_start();
    include("tpl/donate_ad.tpl.php");
    return ob_get_clean();
  }

  /**
   * Get the Banner Ad cookies
   *
   * return array
   */
  public function getBannerCookies() {

    // json decoded cookie
    $cookies = $this->Cookies->getCookie();

    // Default value of the reset cookie
    $reset_cookies = FALSE;

    // If the current date is greater or equal to sept 15
    if (date("Y/m/d") >= "2016/09/15") {

      // By default we don't want to assume
      // we need to reset the cookie yet
      $reset_cookies = TRUE;

      // If the reset cookie has been set and the value is 1
      // this means that we already have resetted the cookie
      // and don't need to do it again
      if (isset($cookies['donation_banner']['value']['reset']) && $cookies['donation_banner']['value']['reset'] == 1) {
        $reset_cookies = FALSE;
      }
    }

    // Check if the cookie is not empty
    // And if the cookie is not reseting
    if (!empty($cookies) && !$reset_cookies) {

      $template = $cookies['donation_banner']['value']['template'];
      $banner_expiration = $cookies['donation_banner']['value']['banner_expiration'];

      // Check if the expiration data has been set AND has expired
      // We don't need to go through the loop if the user is seeing the template #4
      if (isset($banner_expiration) && time() > $banner_expiration && $template !== 4) {

        for ($t = 1; $t <= 3; $t++) {
          // Check what template is currently being displayed
          if ($cookies['donation_banner']['value']['template'] == $t) {

            // Update the cookie with the next template
            // for another 24 hours
            return $this->_setCookieData($t + 1);
          }
        }

      }
      return $cookies;
    }

    // If the cookie is not set,
    // set and return the default values
    return $this->_setCookieData(1, 1, '', '', $reset_cookies);
  }

  /**
   * Set the cookie data
   *
   * @param string $name - Name of the cookie item that will be stored
   * @param int $template - Template number
   * @param string $banner_expiration - Expiration of the item
   * @param int $visible
   * @param string $expiration
   * @param bool $reset
   *
   * @return array
   */
  private function _setCookieData($template = NULL, $visible = 1, $banner_expiration = "", $expiration = "", $reset = NULL) {

    if (is_null($template) || !is_numeric($visible)) {
      return FALSE;
    }

    // If the cookie is reseting,
    // return its state to 0
    // so it doesn't reset a second time
    $reset_value = 0;
    if ($reset) {
      $reset_value = 1;
    }

    // Set the default banner expiration date if empty
    if (empty($banner_expiration)) {
      $banner_expiration = strtotime('+1 day');
    }

    // Set the default expiration date if empty
    if (empty($expiration)) {
      $expiration = strtotime('+1 month');
    }

    $data = array(
      'value' => array(
        'template' => $template,
        'banner_expiration' => $banner_expiration,
        'visible' => $visible,
        'reset' => $reset_value,
      ),
      'expiration' => $expiration,
    );
    $this->Cookies->setCookies('donation_banner',$data['value'], $data['expiration']);
    return array('donation_banner' => $data);
  }
}
