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
require_once(realpath(dirname(__FILE__) . "/../paymentGateway.class.php"));

/**
 * Class for processing paypal donations
 *
 * @author chrisguindon
 */
class Paypal extends PaymentGateway {

  /**
   * Paypal PDT values
   *
   * @var array
   */
  private $paypal_pdt_values = array();

  /**
   * Paypal IPN values
   *
   * @var array
   */
  private $paypal_ipn_values = array();

  /**
   * Paypal tran
   */
  private $paypal_txn_id = "";

  public function Paypal() {
    parent::__construct();
    if (!$this->_get_debug_mode()) {
      $this->_set_gateway_url('https://www.paypal.com/cgi-bin/webscr');
      $this->_set_gateway_email('donate@eclipse.org');
    }
    $this->Donation->set_donation_currency('USD');
    $this->_set_gateway_type('paypal');
    $this->_set_gateway_notify_url('https://'. $this->_get_prefix_domain() . '/donate/web-api/paypal.php');
  }

  /**
   * Implement _extend_email_ipn_post()
   *
   * @see Payment::_extend_email_ipn_post()
   */
  protected function _extend_email_ipn_post() {
    print '-------Validation---------' . PHP_EOL;
    print $this->_get_gateway_response() . PHP_EOL;
    print '-------txn_id---------' . PHP_EOL;
    print $this->App->getHTTPParameter('txn_id') . PHP_EOL;
    print '-------Identity Token---------' . PHP_EOL;
    print $this->_get_gateway_auth_token() . PHP_EOL;
  }

  /**
   * Implement _extend_set_debug_mode()
   *
   * @see Payment::_extend_set_debug_mode()
   */
  protected function _extend_set_debug_mode() {
    $this->_set_gateway_url('https://www.sandbox.paypal.com/cgi-bin/webscr');
    $this->_set_gateway_email('business@eclipse.org');
  }

  /**
   * Implement _set_gateway_auth_token()
   *
   * @see PaymentGateway::_set_gateway_auth_token()
   */
  protected function _set_gateway_auth_token() {
    require_once("/home/data/httpd/eclipse-php-classes/system/authcode.php");
    $this->gateway_auth_token = $payment_gateway_keys['paypal']['production'];
    if ($this->_get_debug_mode()){
      $this->gateway_auth_token = $payment_gateway_keys['paypal']['staging'];
    }
  }

  /**
   * Implement _set_gateway_redirect()
   *
   * @see PaymentGateway::_set_gateway_redirect()
   */
  protected function _set_gateway_redirect($url = NULL) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      return $this->gateway_redirect = $url;
    }
    $query = array();
    $query['notify_url'] = $this->_get_gateway_notify_url();
    $query['business'] = $this->_get_gateway_email();
    $query['email'] = $this->Donation->Donor->get_donor_email();
    if ($this->Donation->get_donation_subscription()) {
      $query['item_name'] = 'Recurring Donation';
      $period = $this->App->getHTTPParameter('subscription', 'POST');
      $valid_period = array('M', 'Y', 'D', 'W');
      if (empty($period) || !in_array($period, $valid_period)) {
        $period = 'M';
      }

      $query['t3'] = strtoupper($period);
      $query['p3'] = '1';
      $query['src'] = '1';
      $query['srt'] = '0';
      $query['no_note'] = '1';
      $query['a3'] = $this->Donation->get_donation_amount();
      //$query['amount'] = $this->Donation->get_donation_amount();
          $query['cmd'] = '_xclick-subscriptions';
    }
    else{
      $query['item_name'] = 'Donation';
      $query['amount'] = $this->Donation->get_donation_amount();
      $query['cmd'] = ' _donations';
    }

    $query['no_shipping'] = '1';
    $query['currency_code'] = $this->Donation->get_donation_currency();
    $query['lc'] = 'US';
    $query['custom'] = $this->Donation->get_donation_random_invoice_id();
    $query['return'] = $this->_get_gateway_return_url();


    // Prepare query string
    $query_string = http_build_query($query);
    $url = $this->_get_gateway_url() . '?' . $query_string;
    return $this->gateway_redirect = $url;
  }

  /**
   * Get PDT response values
   */
  public function get_paypal_pdt_values() {
    return $this->paypal_pdt_values;
  }

  /**
   * Get IPN response values
   */
  public function get_paypal_ipn_values() {
    return $this->paypal_ipn_values;
  }

  /**
   * Confirm IPN with paypal before processing the donation
   *
   * @param array $ipn_values
   */
  public function paypal_confirm_ipn() {
    // Validate the IPN response. If this is FALSE,
    // it's quite probable that someone is trying to
    // post fake data to the IPN script.

    //@todo: remove this, we shouldnt need to pass ipn values.
    // It should always be get_paypal_ipn_values().
    if ($this->_paypal_confirm_ipn()) {
      $values = $this->get_paypal_ipn_values();
      // Verify if the transaction is final and we've received
      // the funds.
      // @todo: Support different payment_status like "pending" and "refund".
      if (strtolower($values['payment_status']) == 'completed'){
        // Update Donor() with the info the user sent us before a donation
        $update = FALSE;


        if (!empty($values['txn_id'])) {
          // Verify if the transaction already exist.
          $this->Donation->set_donation_txn_id($values['txn_id']);
          $this->Donation->Donor->set_donor_contribution_with_txn_id($this->Donation->get_donation_txn_id());
        }

        if (!empty($values['first_name'])) {
          $this->Donation->Donor->set_donor_first_name($values['first_name']);
        }

        if (!empty($values['last_name'])) {
          $this->Donation->Donor->set_donor_last_name($values['last_name']);
        }

        if (!empty($values['custom'])) {
          $this->Donation->set_donation_random_invoice_id($values['custom']);
          $update = $this->Donation->update_donor_from_process_table();
        }

        if (!empty($values['payer_email'])) {
          $this->Donation->Donor->set_donor_paypal_email($values['payer_email']);
        }

        if (!empty($values['mc_gross'])){
          $this->Donation->set_donation_amount($values['mc_gross']);
        }

        if (!empty($values['payment_status'])){
          $this->Donation->set_donation_status($values['payment_status']);
        }

        // If this is a new record, set the redirect url to the IPN
        // script it will create a new record.
        //
        // I am assuming this would happend with a paypal
        // donation without the custom field with the id_unique for
        // the friends_process database table.
        // This might happend also with a bitpay donation.
        if ($update === FALSE) {
          $this->_set_gateway_redirect($this->_get_gateway_notify_url());
        }

        $this->update_friends_process_table($update);
        // Update friends_process table.
        $this->Donation->update_donation_from_ipn($update);
      }
    }
    $this->_email_ipn_post();
  }

  /**
   * Confirm with paypal if this is a valid IPN request.
   *
   */
  protected function _paypal_confirm_ipn() {
    $this->error_logger(date('[Y-m-d H:i e] '). "Requesting IPN transaction information" . PHP_EOL);
       // STEP 1: read POST data
    // Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
    // Instead, read raw POST data from the input stream.
    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
      $keyval = explode ('=', $keyval);
      if (count($keyval) == 2) {
         $myPost[$keyval[0]] = urldecode($keyval[1]);
      }
    }
    // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
    $req = 'cmd=_notify-validate';
    if(function_exists('get_magic_quotes_gpc')) {
       $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
       if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
            $value = urlencode(stripslashes($value));
       } else {
            $value = urlencode($value);
       }
       $req .= "&$key=$value";
    }
    $res = $this->_paypal_curl_request($this->gateway_url, $req);
    $this->_set_gateway_response($res);
    $lines = explode("\n", $res);
    if (strcmp ($res, "VERIFIED") == 0) {
       $this->paypal_ipn_values = $_POST;
       return TRUE;
     }
     return FALSE;
  }

  /**
   * Confirm with paypal if this is a valid PDT request
   */
  public function paypal_confirm_pdt() {
    $tx_token = $this->App->getHTTPParameter('tx', 'GET');
    if (empty($tx_token)) {
      return FALSE;
    }
    $auth_token = $this->_get_gateway_auth_token();
    $req = 'cmd=_notify-synch';
    $req .= "&tx=$tx_token&at=$auth_token";
    $this->gateway_response = $this->_paypal_curl_request($this->gateway_url, $req);

    if(!$this->gateway_response){
      $this->set_client_message('HTTP ERROR: Unable to connect to paypal.com.', 'danger');
    }
    else{
      // parse the data
      $lines = explode("\n", $this->gateway_response);
      $keyarray = array();
      if (strcmp ($lines[0], "SUCCESS") == 0) {
        for ($i=1; $i<count($lines);$i++) {
          if (strpos($lines[$i], '=') !== false) {
            list($key,$val) = explode("=", $lines[$i]);
            $keyarray[urldecode($key)] = urldecode($val);
          }
        }
        $this->paypal_pdt_values = $keyarray;
        if (!empty($keyarray['txn_id'])) {
          // Verify if the transaction already exist.
          $this->Donation->set_donation_txn_id($keyarray['txn_id']);
          $this->Donation->Donor->set_donor_contribution_with_txn_id($this->Donation->get_donation_txn_id());
        }

        if (!empty($keyarray['custom'])) {
          $this->Donation->set_donation_random_invoice_id($keyarray['custom']);
          $update = $this->Donation->update_donor_from_process_table();
        }

        $this->_set_paypal_successful_pdt_message();
        return TRUE;
      }
      else if (strcmp ($lines[0], "FAIL") == 0) {
        $this->set_client_message('ERROR: Payment Data Transfer (PDT) request FAILED.', 'danger');
      }
    }
    return FALSE;
  }

  /**
   * Curl request to paypal
   *
   * @param string $url
   * @param string $req
   */
  protected function _paypal_curl_request($url, $req) {
    $ch = curl_init($url);
    if ($ch == FALSE) {
      $this->error_logger(date('[Y-m-d H:i e] ') . 'Error while initializing CURL ' . PHP_EOL);
      return FALSE;
    }

    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    $this->_get_curl_options($ch);

    $res = curl_exec($ch);
    $this->_set_gateway_response($res);
    if (curl_errno($ch) != 0) { // cURL error
      $this->error_logger(date('[Y-m-d H:i e] ') . "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL);
      curl_close($ch);
      return FALSE;
    }
    else {
      $this->error_logger(date('[Y-m-d H:i e] ') . "HTTP response of validation request: $res" . PHP_EOL);
      curl_close($ch);
      return $res;
    }
  }

  /**
   * Set a thank you message when the user return from paypal
   */
  private function _set_paypal_successful_pdt_message() {
    $message = "";
    $pdt = $this->get_paypal_pdt_values();
    $message = '<strong>Thank you for your donation ' . $this->Donation->Donor->get_donor_first_name() . ' ' . $this->Donation->Donor->get_donor_last_name() . '!</strong><br/><br/>
    Your transaction has been completed. A receipt for your donation has been sent to your email.
    You may also see the transaction details by logging into your account at
    <a href="https://www.paypal.com" target="_blank">www.paypal.com</a>.';

    // Show this message only if the payment status is one of these values.
    $status = array('completed', 'pending', 'processed');
    if (!empty($message) && in_array(strtolower($pdt['payment_status']), $status)) {
      $this->set_client_message($message, 'success');
      setcookie("thankyou_page[eclipse_donation]", TRUE, time() + (3600 * 24 * 360 * 10), '/', $this->_get_prefix_cookie());
    }
  }
}
