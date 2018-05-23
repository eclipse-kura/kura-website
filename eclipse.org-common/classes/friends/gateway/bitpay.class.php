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
 * Class for processing bitpay donations
 *
 * @author chrisguindon
 */
class BitPay extends PaymentGateway {

  /**
   * Bitpay configurations
   *
   * @var array
   */
  private $bitpay_options = array();

  /**
   * Bitpay donation button data
   *
   * @var string
   */
  private $bitpay_data = "";

  /**
   * Bitpay API version
   *
   * @var string
   */
  private $bitpay_api_version = "";

  /**
   * Bitpay invoice response
   * @var array
   */
  private $bitpay_invoice = NULL;

  public function BitPay() {
   parent::__construct();

   // Overide debug settings on production
   if (!$this->_get_debug_mode()) {
     $this->_set_gateway_url('https://bitpay.com/checkout');
     $this->_set_gateway_email('donate@eclipse.org');
     $this->_set_bitpay_data('0aFzNGoToD0/elGC/2XEk0tokqlR/1w4f7ld6jMcYQ+/rt4oezJ7XvP5BWx0WmbDfgzQ0TFoBXp9fJ08yGxe9dz9EehQbo8t/kQvbWzhGDS0NZ1HUUOMQQktbomnBeW1Kgp46LS5c2EidjP+pG0vtQwzGJBcJIwZlHHJ1Ep8g5O2NAJrp8Fc9af2Gbfja7PTTwkO+KFOK78RwJ7rowxxCruqbyzQYk70I8e/89PoDlUjzRsI0xXyDE3Ssma8JxAeKOVoRw9Q+cIaLsLFKqwbqzZazE2tKQAo5GDmpz0uUWVB0sSwVQrCmO90uhQTq8zYrn8LUzAAofVnrteoVz3yKbqwV4TWXic+z6SN7UVroijSuNm22eUJXqZfgx4d9+ALtxits48RMpiPi0hZwUGBpw==');
   }

   $this->Donation->set_donation_currency('BTC');
   $this->_set_gateway_type('bitpay');
   $this->_set_gateway_notify_url('https://'. $this->_get_prefix_domain() . '/donate/web-api/bitpay.php');

   $this->_set_bitpay_api_version('1.9');
   $this->_set_bitpay_options();
  }

  /**
   * Confirm donation from bitpay
   */
  private function _bitpay_confirm_ipn(){
    $invoice = $this->_get_bitpay_invoice();
    //@todo: Confirm this is the right way to validate an IPN response
    if (isset($invoice['status']) && $invoice['status'] == 'confirmed') {
      if (isset($invoice['url']) && !empty($invoice['url'])) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Confirm and process IPN request
   */
  function bitpay_confirm_ipn() {
    $this->_set_bitpay_invoice($this->bitpay_verify_notification());
    // Validate the IPN response. If this is FALSE,
    // it's quite probable that someone is trying to
    // post fake data to the IPN script.

    if ($this->_bitpay_confirm_ipn()) {
      $values = $this->_get_bitpay_invoice();
      $gateway_response = $this->_get_gateway_response();
      if (!empty($gateway_response['posData'])){
        $values['posData'] = $gateway_response['posData'];
      }

      // Confirm the transaction
      // @todo: Support different payment_status like "pending" and "refund".
      if (strtolower($values['status']) == 'confirmed'){
        // Update Donor() with the info the user sent us before a donation
        $update = FALSE;

        if (!empty($values['id'])) {
          // Verify if the transaction already exist.
          $this->Donation->set_donation_txn_id($values['id']);
          $this->Donation->Donor->set_donor_contribution_with_txn_id($this->Donation->get_donation_txn_id());
        }

        if (!empty($values['posData'])) {
          $this->Donation->set_donation_random_invoice_id($values['posData']);
          $update = $this->Donation->update_donor_from_process_table();
        }

        if (!empty($values['buyerFields']['buyerEmail'])) {
          $this->Donation->Donor->set_donor_paypal_email($values['buyerFields']['buyerEmail']);
        }

        if (!empty($values['price'])){
          $this->Donation->set_donation_amount($values['price']);
        }

        // Make sure the donation currency is set to USD
        // If not, someone modified the currency before submitting the form
        // We wont apply any benefits for this type of scenario
        if (!empty($values['currency']) && $values['currency'] != 'USD'){
          $invalid == TRUE;
        }

        if (!empty($values['status'])){
          $this->Donation->set_donation_status($values['status']);
        }

        // If this is a new record, set the redirect url to the IPN
        //
        // I am assuming this would happend with a paypal
        // donation without the custom field for
        // the friends_process database table.
        // This might happend with a bitpay donation.
        if ($update === FALSE) {
          $this->_set_gateway_redirect($this->_get_gateway_notify_url());
        }

        //Update donation if this is a valid transaction
        if (!isset($invalid)) {
          $this->update_friends_process_table($update);
          // Update the friends_process table.
          $this->Donation->update_donation_from_ipn($update);
        }
      }
    }
    $this->_email_ipn_post();
  }

  /**
   * Implement _extend_email_ipn_post()
   *
   * @see Payment::_extend_email_ipn_post()
   */
  protected function _extend_email_ipn_post() {
    print '-------Validation---------' . PHP_EOL;
    print $this->_get_gateway_response() . PHP_EOL;
    print '-------Invoice---------' . PHP_EOL;
    print_r($this->bitpay_invoice) . PHP_EOL;
    print '-------Identity Token---------' . PHP_EOL;
    print $this->_get_gateway_auth_token() . PHP_EOL;
  }

  /**
   * Implement _extend_set_debug_mode()
   *
   * @see Payment::_extend_set_debug_mode()
   */
  protected function _extend_set_debug_mode() {
    $this->_set_gateway_url('https://test.bitpay.com/checkout');
    $this->_set_bitpay_data('osjDxpBZPpOhQroIZfr6TT9xBh0V/WW9u+mGzbL6MDEkFQaCcQIdAqTfJsiKOtR02dYcKjGQbTkLVAZvVO7Ejp9T2ptc1PxevbI3FhmNNFffYL324/osufoFYvlOWRYX4EszvABx4x0cPrTMCrV23o4+u9USgA4xBRdprH94h2ZtnfJ6TYeY+RIGoDmeZhYgID2jESLiEISCFOmvXMZarYrB6V+9LRQcrMNTVmQYUbonL68L058l8eA2RRuAWFiT/mWXLrC/YkbMsF8+eMAeh+KlJ1sStUSHbWAmegQuwLM/1oddkK9+pFaT9ET0rkQPRa+cvtgCvsuYgTk/m2ueIAHWdaOz3TtNOuF3B743FsShQGLzhgzRA6CruYQZ+P/XNx1qz0cUfE4b+ayw/ZgqIa2YNfGxESp304u6F0hXvdw=');
  }

  /**
   * Implement _set_gateway_auth_token()
   *
   * @see PaymentGateway::_set_gateway_auth_token()
   */
  protected function _set_gateway_auth_token() {
    require_once("/home/data/httpd/eclipse-php-classes/system/authcode.php");
    $this->gateway_auth_token = $payment_gateway_keys['bitpay']['production'];
    if ($this->_get_debug_mode()){
      $this->gateway_auth_token = $payment_gateway_keys['bitpay']['staging'];
    }
  }

  /**
   * Set bitpay API version
   *
   * @param string $version
   */
  protected function _set_bitpay_api_version($version = '') {
    $this->bitpay_api_version = $version;
  }

  /**
   * Get bitpay API verision
   */
  protected function _get_bitpay_api_version() {
    return $this->bitpay_api_version;
  }

  /**
   * Get bitpay invoice
   */
  protected function _get_bitpay_invoice() {
    return $this->bitpay_invoice;
  }

  /**
   * Get bitpay data for donation form
   */
  protected function _get_bitpay_data() {
    return $this->bitpay_data;
  }

  /**
   * Get bitpay default options
   */
  protected function _get_bitpay_options(){
    return $this->bitpay_options;
  }

  /**
   * Set bitpay data
   */
  protected function _set_bitpay_data($data = ''){
    $this->bitpay_data = $data;
  }

  /**
   * Set bitpay default OPTIONS
   */
  protected function _set_bitpay_options(){
    /*
     * Optional - url where the customer should be directed to after paying for the order
     * example: $bitpay_NotificationUrl = 'http://www.example.com/confirmation.php';
     */
    $this->bitpay_options['redirectURL'] = $this->_get_gateway_return_url();

    /*
     * Boolean value.  Whether to verify POS data by hashing above api key.  If set to false, you should
     * have some way of verifying that callback data comes from bitpay.com
     * Note: this option can only be changed here.  It cannot be set dynamically.
     */
    $this->bitpay_options['verifyPos'] = FALSE;

    /*
     * REQUIRED!  This is the currency used for the price setting.  A list of other pricing
     * currencies supported is found at bitpay.com
     */
    $this->bitpay_options['currency'] = $this->Donation->get_donation_currency();

    /*
     * Boolean value.  Indicates whether anything is to be shipped with
     * the order (if false, the buyer will be informed that nothing is
     * to be shipped)
     */
    $this->bitpay_options['physical'] = FALSE;

    /*
     * If set to false, then notificaitions are only
     * sent when an invoice is confirmed (according the the
     * transactionSpeed setting). If set to true, then a notification
     * will be sent on every status change
     */
    $this->bitpay_options['fullNotifications'] = TRUE;

    /*
     * REQUIRED! Transaction speed: low/medium/high.  See API docs for more details.
    */
    $this->bitpay_options['transactionSpeed'] = 'low';

    /*
     * Boolean value. Change to 'true' if you would like automatic logging of errors.
     * Otherwise you will have to call the error_logger function manually to log any information.
     */
    $this->bitpay_options['useLogging'] = TRUE;

    /*
     * Boolean value. Change to 'true' if you want to use the testnet development environment at
     * test.bitpay.com. See: http://blog.bitpay.com/2014/05/13/introducing-the-bitpay-test-environment.html
     * for more information on using testnet.
     */
    $this->bitpay_options['testnet'] = $this->_get_debug_mode();

    /*
     * Optional - email where you want invoice update notifications sent
     */
    $this->bitpay_options['notificationEmail'] = $this->_get_gateway_email();

    /*
     * Optional - url where bit-pay server should send payment notification updates.  See API doc for more details.
     * Example: $bitpay_NotificationUrl = 'http://www.example.com/callback.php';
     */
    $this->bitpay_options['notificationURL'] = $this->_get_gateway_notify_url();
  }

  protected function _set_bitpay_invoice($invoice) {
    if (is_array($invoice)) {
      $this->bitpay_invoice = $invoice;
    }
  }
  /**
   * Returns the correct API service endpoint hostname depending on whether the
   * production or test environment is selected.
   *
   * @param none
   * @return string $host
   */
  function bitpay_host() {
    /*
     * Safety check in case an older version of the option file is being used or the test option is
     * empty or not set at all.  Defaults to the live site.
     */
    if (!isset($this->bitpay_options['testnet']) || trim($this->bitpay_options['testnet']) == '' || is_null($this->bitpay_options['testnet']) || empty($this->bitpay_options['testnet']))
      $this->bitpay_options['testnet'] == false;

    if ($this->bitpay_options['testnet'] == true)
      return 'test.bitpay.com';

    return 'bitpay.com';
  }

  /**
   * Handles post/get to BitPay via curl.
   *
   * @param string $url, boolean $post
   * @return mixed $response
   * @throws Exception $e
   */
  function bitpay_curl($url, $post = false) {

    $apiKey = $this->_get_gateway_auth_token();

    /*
     * Container for our curl response or any error messages to return
     * to the calling function.
     */
    $response = null;


    if ((isset($url) && trim($url) != '') && (isset($apiKey) && trim($apiKey) != '')) {

      try {
        $curl = curl_init();

        if (!$curl)
          return 'Error in bitpay_curl(): Could not initialize a cURL handle!';

        $content_length = 0;

        if ($post) {
          curl_setopt($curl, CURLOPT_POST, 1);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

          $content_length = strlen($post);
        }

        $uname = base64_encode($apiKey);

        if ($uname) {
          $header = array(
                    'Content-Type: application/json',
                    'Content-Length: ' . $content_length,
                    'Authorization: Basic ' . $uname,
                    'X-BitPay-Plugin-Info: phplib' . $this->_get_bitpay_api_version(),
                    );

          /*
           * If you are having SSL certificate errors due to an outdated CA cert on your webserver
           * ask your webhosting provider to update your webserver.  The curl SSL checks are used
           * to ensure you are actually communicating with the real BitPay network.
           */
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_PORT, 443);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
          $this->_get_curl_options($curl);

          /*
           * Returns the error message or '' (the empty string) if no error occurred.
           */
          $responseString = curl_exec($curl);
          /*
           * For a complete list and description of all curl error codes
           * see: http://curl.haxx.se/libcurl/c/libcurl-errors.html
           */
          $curl_error_number = curl_errno($curl);

          if ($responseString === false || $curl_error_number != 0) {
            if(function_exists('curl_strerror'))
              $curl_error_description = curl_strerror($curl_error_number);
             else
               $curl_error_description = $this->bitpay_curl_Strerror($curl_error_number);

            $response = array('error' => curl_error($curl), 'error_code' => $curl_error_number, 'error_code_description' => $curl_error_description);

            if ($this->bitpay_options['useLogging'])
              $this->error_logger('Error in bitpay_curl(): ' . $response);

          } else {

            if (function_exists('json_decode'))
              $response = json_decode($responseString, true);
            else
              $response = $this->bitpay_JSON_decode($responseString);

            if (!$response) {
              $response = array('error' => 'invalid json');

              if ($this->bitpay_options['useLogging'])
                $this->error_logger('Error in bitpay_curl(): Invalid JSON.');
            }

          }
          curl_close($curl);
          $this->_set_gateway_response($response);
          return $response;

        } else {

          curl_close($curl);

          if ($this->bitpay_options['useLogging'])
            $this->error_logger('Error in bitpay_curl(): Invalid data found in apiKey value passed to bitpay_curl. (Failed: base64_encode(apikey))');

          return array('error' => 'Invalid data found in apiKey value passed to bitpay_curl. (Failed: base64_encode(apikey))');
        }

      } catch (Exception $e) {

        /*
         * It's possible that an error could occur before curl is initialized.  In that case
         * it is safe to suppress the warning message from calling curl_close without an
         * initialized curl session.
         */
        @curl_close($curl);

        if ($this->bitpay_options['useLogging'])
          $this->error_logger('Error in bitpay_curl(): ' . $e->getMessage());

        return array('error' => $e->getMessage());
      }

    } else {

      /*
       * Invalid URL or API Key parameter specified
       */
      if ($this->bitpay_options['useLogging'])
        $this->error_logger('Error in bitpay_curl(): You must supply non-empty url and apiKey parameters.');

      return array('error' => 'You must supply non-empty url and apiKey parameters to bitpay_curl().');
    }

  }

  /**
   * Creates BitPay invoice via bitpay_curl.  More information regarding the various options are explained
   * below.  For the official API documentation, see: https://bitpay.com/downloads/bitpayApi.pdf
   *
   * @param string $orderId, string $price, string $posData, array $options
   * @return array $response
   * @throws Exception $e
   */
  function bitpay_create_invoice($orderId, $price, $posData = '', $options = array()) {

    /*
     * $orderId: Used to display an orderID to the buyer. In the account summary view, this value is used to
     * identify a ledger entry if present. Maximum length is 100 characters.
     *
     * $price: by default, $price is expressed in the currency you set in bitpay__options.php.  The currency can be
     * changed in $options.
     *
     * $posData: this field is included in status updates or requests to get an invoice.  It is intended to be used by
     * the merchant to uniquely identify an order associated with an invoice in their system.  Aside from that, BitPay does
     * not use the data in this field.  The data in this field can be anything that is meaningful to the merchant.
     * Maximum length is 100 characters.
     *
     * Note:  Using the posData hash option will APPEND the hash to the posData field and could push you over the 100
     *        character limit.
     *
     * $options keys can include any of:
     *  'itemDesc', 'itemCode', 'notificationEmail', 'notificationURL', 'redirectURL',
     *  'currency', 'physical', 'fullNotifications', 'transactionSpeed', 'buyerName',
     *  'buyerAddress1', 'buyerAddress2', 'buyerCity', 'buyerState', 'buyerZip', 'buyerEmail', 'buyerPhone'
     *
     * If a given option is not provided here, the value of that option will default to what is found in bitpay__options.php
     * (see api documentation for information on these options).
     */

    if (!isset($orderId) || is_null($orderId) || trim($orderId) == '' || empty($orderId))
      return 'Error in bitpay_create_invoice(): No orderId supplied to function. Usage: bitpay_create_invoice($orderId, $price, $posData, $options)';

    if (!isset($price) || is_null($price) || trim($price) == '' || empty($price))
      return 'Error in bitpay_create_invoice(): No price supplied to function.  Usage: bitpay_create_invoice($orderId, $price, $posData, $options)';

    try {
      $options = array_merge($this->bitpay_options, $options);
      $pos = array('posData' => $posData);

      if ($this->bitpay_options['verifyPos'])
        $pos['hash'] = $this->bitpay_hash(serialize($posData), $this->_get_gateway_auth_token());

      if (function_exists('json_encode'))
        $options['posData'] = json_encode($pos);
      else
        $options['posData'] = $this->bitpay_JSON_encode($pos);

      if (strlen($options['posData']) > 100)
        return array('error' => 'The posData exceeds the 100 character limit. Are you using the posData hash? The hash is APPENDED to the posData string and can cause overflow.');

      $options['orderID'] = $orderId;
      $options['price'] = $price;

      $postOptions = array('orderID', 'itemDesc', 'itemCode', 'notificationEmail', 'notificationURL', 'redirectURL',
                           'posData', 'price', 'currency', 'physical', 'fullNotifications', 'transactionSpeed', 'buyerName',
                           'buyerAddress1', 'buyerAddress2', 'buyerCity', 'buyerState', 'buyerZip', 'buyerEmail', 'buyerPhone');

      foreach($postOptions as $o) {
        if (array_key_exists($o, $options))
          $post[$o] = $options[$o];
      }

      if (function_exists('json_encode'))
        $post = json_encode($post);
      else
        $post = $this->bitpay_JSON_encode($post);

      $response = $this->bitpay_curl('https://' . $this->bitpay_host() . '/api/invoice/', $this->_get_gateway_auth_token(), $post);

      return $response;

    } catch (Exception $e) {
      if ($this->bitpay_options['useLogging'])
        $this->error_logger('Error in bitpay_create_invoice(): ' . $e->getMessage());

      return array('error' => $e->getMessage());
    }
  }

  /**
   * Call from your notification handler to convert $_POST data to an object containing invoice data
   *
   * @return mixed $json
   * @throws Exception $e
   */
  function bitpay_verify_notification() {
    $apiKey = $this->_get_gateway_auth_token();

    try {

      /*
       * There's a PHP quirk when reading a pure JSON POST response.  The $_POST global will be empty
       * so you must read the raw POST input using file_get_contents().  For more information, see:
       * https://support.bitpay.com/hc/en-us/articles/202596678-Blank-IPN-post-response-from-BitPay-when-using-PHP
       */
      $post = file_get_contents("php://input");

      if (!$post)
        return 'Error in bpVerifyNotification(): No POST data returned.';

      if (function_exists('json_decode'))
        $json = json_decode($post, true);
      else
        $json = bpJSONdecode($post);

      if (is_string($json) || (is_array($json) && array_key_exists('error', $json)))
        return $json;

      if (!array_key_exists('posData', $json))
        return 'Error in bitpay_verify_notification(): No posData found.';

      if (function_exists('json_decode'))
        $posData = json_decode($json['posData'], true);
      else
        $posData = $this->bitpay_JSON_decode($json['posData']);

      if ($this->bitpay_options['verifyPos'] and $posData['hash'] != $this->bitpay_hash(serialize($posData['posData'])))
        return 'Error in bitpay_verify_notification(): Authentication failed (bad hash)';

      $json['posData'] = $posData['posData'];

      if (!array_key_exists('id', $json))
      {
          return 'Cannot find invoice ID';
      }
      return $this->bitpay_get_invoice($json['id']);

    } catch (Exception $e) {
      if ($this->bitpay_options['useLogging'])
        $this->error_logger('Error in bitpay_verify_notification(): ' . $e->getMessage());

      return array('error' => $e->getMessage());
    }
  }

  /**
   * Retrieves an invoice from BitPay.
   *
   * @param string $invoiceId
   * @return mixed $json
   * @throws Exception $e
   */
  function bitpay_get_invoice($invoiceId) {
    $apiKey = $this->_get_gateway_auth_token();
    if (!isset($invoiceId) || is_null($invoiceId) || trim($invoiceId) == '' || empty($invoiceId))
      return 'Error in bitpay_get_invoice(): No invoiceId supplied to function. Usage: bitpay_get_invoice($invoiceId)';

    try {
      $response = $this->bitpay_curl('https://' . $this->bitpay_host() . '/api/invoice/' . $invoiceId);

      if (is_string($response) || (is_array($response) && array_key_exists('error', $response)))
        return $response;

      if (function_exists('json_decode'))
        $response['posData'] = json_decode($response['posData'], true);
      else
        $response['posData'] = $this->bitpay_JSON_decode($response['posData']);

      $response['posData'] = $response['posData']['posData'];
      return $response;

    } catch (Exception $e) {
      if ($this->bitpay_options['useLogging'])
        $this->error_logger('Error in bitpay_get_invoice(): ' . $e->getMessage());

      return 'Error in bitpay_get_invoice(): ' . $e->getMessage();
    }
  }

  /**
   * Generates a base64 encoded keyed hash using the HMAC method. For more
   * information, see: http://www.php.net/manual/en/function.hash-hmac.php
   *
   * @param string $data, string $key
   * @return string $hmac
   * @throws Exception $e
   */
  function bitpay_hash($data, $key) {


    if (!isset($key) || is_null($key) || trim($key) == '' || empty($key))
      return 'Error in bitpay_hash(): No key supplied to function. Usage: bitpay_hash($data, $key)';

    if (!isset($data) || is_null($data) || trim($data) == '' || empty($data))
      return 'Error in bitpay_hash(): No data supplied to function. Usage: bitpay_hash($data, $key)';

    try {
      $hmac = base64_encode(hash_hmac('sha256', $data, $key, TRUE));

      return strtr($hmac, array('+' => '-', '/' => '_', '=' => ''));

    } catch (Exception $e) {
      if ($this->bitpay_options['useLogging'])
        $this->error_logger('Error in bitpay_hash(): ' . $e->getMessage());

      return 'Error in bitpay_hash(): ' . $e->getMessage();
    }
  }

  /**
   * Decodes JSON response and returns an associative array.
   *
   * @param string $response
   * @return array $arrResponse
   * @throws Exception $e
   */
  function bitpay_decode_response($response) {


    try {

      if (!is_string($response) || is_null($response) || trim($response) == '' || empty($response))
        return 'Error in bitpay_decode_response(): Missing response string parameter. Usage: bitpay_decode_response($response)';

      if (function_exists('json_decode'))
        return json_decode($response, true);
      else
        return $this->bitpay_JSON_decode($response);

    } catch (Exception $e) {
      if ($this->bitpay_options['useLogging'])
        $this->error_logger('Error in bitpay_decode_response(): ' . $e->getMessage());

      return 'Error in bitpay_decode_response(): ' . $e->getMessage();
    }
  }

  /**
   * Retrieves a list of all supported currencies and returns an associative array.
   *
   * @param none
   * @return array $currencies
   * @throws Exception $e
   */
  function bitpay_currency_list() {


    $currencies = array();
    $rate_url = 'https://' . $this->bitpay_host() . '/api/rates';

    try {

      if (function_exists('json_decode'))
        $clist = json_decode(file_get_contents($rate_url),true);
      else
        $clist = $this->bitpay_JSON_decode(file_get_contents($rate_url));

      foreach($clist as $key => $value)
        $currencies[$value['code']] = $value['name'];

      return $currencies;

    } catch (Exception $e) {
      if ($this->bitpay_options['useLogging'])
        $this->error_logger('Error in bitpay_currency_list(): ' . $e->getMessage());

      return 'Error in bitpay_currency_list(): ' . $e->getMessage();
    }
  }

  /**
   * Retrieves the current rate based on $code. The default code us USD, so calling the
   * function without a parameter will return the current BTC/USD price.
   *
   * @param string $code
   * @return string $rate
   * @throws Exception $e
   */
  function bitpay_get_rate($code = 'USD') {
    $rate = '';
    $clist = '';
    $rate_url = 'https://' . $this->bitpay_host() . '/api/rates';

    try {

      if (function_exists('json_decode'))
        $clist = json_decode(file_get_contents($rate_url), true);
      else
        $clist = $this->bitpay_JSON_decode(file_get_contents($rate_url));

      foreach($clist as $key => $value) {
        if ($value['code'] == $code)
          $rate = number_format($value['rate'], 2, '.', '');
      }

      return $rate;

    } catch (Exception $e) {
      if ($this->bitpay_options['useLogging'])
        $this->error_logger('Error in bitpay_get_rate(): ' . $e->getMessage());

      return 'Error in bitpay_get_rate(): ' . $e->getMessage();
    }
  }

  /**
   * Fallback JSON decoding function in the event you do not have the PHP JSON extension installed and
   * cannot install it.  This function takes an encoded string and returns an associative array.
   *
   * @param string $jsondata
   * @return array $jsonarray
   */
  function bitpay_JSON_decode($jsondata) {
    $jsondata = trim(stripcslashes(str_ireplace('"', '', str_ireplace('\'', '', $jsondata))));
    $jsonarray = array();
    $level = 0;

    if (!is_string($jsondata) || is_null($jsondata) || trim($jsondata) == '' || empty($jsondata))
      return false;

    if ($jsondata[0] == '[')
      $jsondata = trim(substr($jsondata, 1, strlen($jsondata)));

    if ($jsondata[0] == '{')
      $jsondata = trim(substr($jsondata, 1, strlen($jsondata)));

    if (substr($jsondata, strlen($jsondata) - 1, 1) == ']')
      $jsondata = trim(substr($jsondata, 0, strlen($jsondata) - 1));

    if (substr($jsondata, strlen($jsondata) - 1, 1) == '}')
      $jsondata = trim(substr($jsondata, 0, strlen($jsondata) - 1));

    $break = false;

    while(!$break) {
      if (stripos($jsondata,"\t") !== false)
        $jsondata = str_ireplace("\t", ' ', $jsondata);

      if (stripos($jsondata,"\r") !== false)
        $jsondata = str_ireplace("\r", '', $jsondata);

      if (stripos($jsondata,"\n") !== false)
        $jsondata = str_ireplace("\n", '', $jsondata);

      if (stripos($jsondata,' ') !== false)
        $jsondata = str_ireplace(' ', ' ', $jsondata);
      else
        $break = true;
    }

    $level = 0;
    $x = 0;
    $array = false;
    $object = false;

    while($x < strlen($jsondata)) {
      $var = '';
      $val = '';

      while($x < strlen($jsondata) && $jsondata[$x] == ' ')
        $x++;

      switch($jsondata[$x]) {
        case '[':
          $level++;
          break;
        case '{':
          $level++;
          break;
      }

      if ($level <= 0) {
        while($x < strlen($jsondata) && $jsondata[$x] != ':') {
          if ($jsondata[$x] != ' ') $var .= $jsondata[$x];
          $x++;
        }

        $var = trim(stripcslashes(str_ireplace('"', '', $var)));

        while($x < strlen($jsondata) && ($jsondata[$x] == ' ' || $jsondata[$x] == ':'))
          $x++;

        switch($jsondata[$x]) {
          case '[':
            $level++;
            break;
          case '{':
           $level++;
           break;
        }
     }

      if ($level > 0) {

        while($x< strlen($jsondata) && $level > 0) {
          $val .= $jsondata[$x];
          $x++;

          switch($jsondata[$x]) {
            case '[':
              $level++;
              break;
            case '{':
              $level++;
              break;
            case ']':
              $level--;
              break;
            case '}':
              $level--;
              break;
          }
        }

        if ($jsondata[$x] == ']' || $jsondata[$x] == '}')
          $val .= $jsondata[$x];

        $val = trim(stripcslashes(str_ireplace('"', '', $val)));

        while($x < strlen($jsondata) && ($jsondata[$x] == ' ' || $jsondata[$x] == ',' || $jsondata[$x] == ']' || $jsondata[$x] == '}'))
          $x++;

      } else {

        while($x < strlen($jsondata) && $jsondata[$x] != ',') {
          $val .= $jsondata[$x];
          $x++;
        }

        $val = trim(stripcslashes(str_ireplace('"', '', $val)));

        while($x < strlen($jsondata) && ($jsondata[$x] == ' ' || $jsondata[$x] == ','))
          $x++;
      }

      $jsonarray[$var] = $val;

      if ($level < 0) $level = 0;
    }

    return $jsonarray;

  }

  /**
   * Fallback JSON encoding function in the event you do not have the PHP JSON extension installed and
   * cannot install it.  This function takes data in various forms and returns a JSON encoded string.
   *
   * @param mixed $data
   * @return string $jsondata
   */
  function bitpay_JSON_encode($data) {
    if (is_array($data)) {
      $jsondata = '{';

      foreach($data as $key => $value) {
        $jsondata .= '"' . $key . '": ';

        if (is_array($value))
          $jsondata .= $this->bitpay_JSON_encode($value) . ', ';

        if (is_numeric($value))
          $jsondata .= $value . ', ';

        if (is_string($value))
          $jsondata .= '"' . $value . '", ';

        if (is_bool($value)) {
          if ($value)
            $jsondata .= 'true, ';
          else
            $jsondata .= 'false, ';
        }

        if (is_null($value))
          $jsondata .= 'null, ';
      }

      $jsondata = substr($jsondata, 0, strlen($jsondata) - 2);
      $jsondata .= '}';

    } else {
      $jsondata = '{"' . $data . '"}';
    }

    return $jsondata;
  }

  /**
   * Fallback cURL error string function in the event you do not have PHP >= 5.5.0 installed.  These cURL
   * error codes and descriptions were retrieved from the official libcurl error documentation.  See:
   * http://curl.haxx.se/libcurl/c/libcurl-errors.html
   *
   * @param integer $errorno
   * @return string $error_description
   */
  function bitpay_curl_Strerror($errorno) {
    $error_description = '';

    if (!is_int($errorno) || is_null($errorno) || empty($errorno))
      return 'Error in bitpay_curl_Strerror(): No error number integer passed to function. Usage: bitpay_curl_Strerror($errorno)';

    switch($errorno) {
      case 0:
        /*
         * CURLE_OK (0)
         */
        $error_description = 'CURLE_OK: All fine. Proceed as usual.';
        break;
      case 1:
        /*
         * CURLE_UNSUPPORTED_PROTOCOL (1)
         */
        $error_description = 'CURLE_UNSUPPORTED_PROTOCOL: The URL you passed to libcurl used a protocol that this libcurl does not support. The support might be a compile-time option that you didn\'t use, it can be a misspelled protocol string or just a protocol libcurl has no code for.';
        break;
      case 2:
        /*
         * CURLE_FAILED_INIT (2)
         */
        $error_description = 'CURLE_FAILED_INIT: Very early initialization code failed. This is likely to be an internal error or problem, or a resource problem where something fundamental couldn\'t get done at init time.';
        break;
      case 3:
        /*
         * CURLE_URL_MALFORMAT (3)
         */
        $error_description = 'CURLE_URL_MALFORMAT: The URL was not properly formatted.';
        break;
      case 4:
        /*
         * CURLE_NOT_BUILT_IN (4)
         */
        $error_description = 'CURLE_NOT_BUILT_IN: A requested feature, protocol or option was not found built-in in this libcurl due to a build-time decision. This means that a feature or option was not enabled or explicitly disabled when libcurl was built and in order to get it to function you have to get a rebuilt libcurl.';
        break;
      case 5:
        /*
         * CURLE_COULDNT_RESOLVE_PROXY (5)
         */
        $error_description = 'CURLE_COULDNT_RESOLVE_PROXY: Couldn\'t resolve proxy. The given proxy host could not be resolved.';
        break;
      case 6:
        /*
         * CURLE_COULDNT_RESOLVE_HOST (6)
         */
        $error_description = 'CURLE_COULDNT_RESOLVE_HOST: Couldn\'t resolve host. The given remote host was not resolved.';
        break;
      case 7:
        /*
         * CURLE_COULDNT_CONNECT (7)
         */
        $error_description = 'CURLE_COULDNT_CONNECT: Failed to connect() to host or proxy.';
        break;
      case 8:
        /*
         * CURLE_FTP_WEIRD_SERVER_REPLY (8)
         */
        $error_description = 'CURLE_FTP_WEIRD_SERVER_REPLY: After connecting to a FTP server, libcurl expects to get a certain reply back. This error code implies that it got a strange or bad reply. The given remote server is probably not an OK FTP server.';
        break;
      case 9:
        /*
         * CURLE_REMOTE_ACCESS_DENIED (9)
         */
        $error_description = 'CURLE_REMOTE_ACCESS_DENIED: We were denied access to the resource given in the URL. For FTP, this occurs while trying to change to the remote directory.';
        break;
      case 10:
        /*
         * CURLE_FTP_ACCEPT_FAILED (10)
         */
        $error_description = 'CURLE_FTP_ACCEPT_FAILED: While waiting for the server to connect back when an active FTP session is used, an error code was sent over the control connection or similar.';
        break;
      case 11:
        /*
         * CURLE_FTP_WEIRD_PASS_REPLY (11)
         */
        $error_description = 'CURLE_FTP_WEIRD_PASS_REPLY: After having sent the FTP password to the server, libcurl expects a proper reply. This error code indicates that an unexpected code was returned.';
        break;
      case 12:
        /*
         * CURLE_FTP_ACCEPT_TIMEOUT (12)
         */
        $error_description = 'CURLE_FTP_ACCEPT_TIMEOUT: During an active FTP session while waiting for the server to connect, the CURLOPT_ACCEPTTIMOUT_MS(3) (or the internal default) timeout expired.';
        break;
      case 13:
        /*
         * CURLE_FTP_WEIRD_PASV_REPLY (13)
         */
        $error_description = 'CURLE_FTP_WEIRD_PASV_REPLY: libcurl failed to get a sensible result back from the server as a response to either a PASV or a EPSV command. The server is flawed.';
        break;
      case 14:
        /*
         * CURLE_FTP_WEIRD_227_FORMAT (14)
         */
        $error_description = 'CURLE_FTP_WEIRD_227_FORMAT: FTP servers return a 227-line as a response to a PASV command. If libcurl fails to parse that line, this return code is passed back.';
        break;
      case 15:
        /*
         * CURLE_FTP_CANT_GET_HOST (15)
         */
        $error_description = 'CURLE_FTP_CANT_GET_HOST: An internal failure to lookup the host used for the new connection.';
        break;
      case 17:
        /*
         * CURLE_FTP_COULDNT_SET_TYPE (17)
         */
        $error_description = 'CURLE_FTP_COULDNT_SET_TYPE: Received an error when trying to set the transfer mode to binary or ASCII.';
        break;
      case 18:
        /*
         * CURLE_PARTIAL_FILE (18)
         */
        $error_description = 'CURLE_PARTIAL_FILE: A file transfer was shorter or larger than expected. This happens when the server first reports an expected transfer size, and then delivers data that doesn\'t match the previously given size.';
        break;
      case 19:
        /*
         * CURLE_FTP_COULDNT_RETR_FILE (19)
         */
        $error_description = 'CURLE_FTP_COULDNT_RETR_FILE: This was either a weird reply to a \'RETR\' command or a zero byte transfer complete.';
        break;
      case 21:
        /*
         * CURLE_QUOTE_ERROR (21)
         */
        $error_description = 'CURLE_QUOTE_ERROR: When sending custom "QUOTE" commands to the remote server, one of the commands returned an error code that was 400 or higher (for FTP) or otherwise indicated unsuccessful completion of the command.';
        break;
      case 22:
        /*
         * CURLE_HTTP_RETURNED_ERROR (22)
         */
        $error_description = 'CURLE_HTTP_RETURNED_ERROR: This is returned if CURLOPT_FAILONERROR is set TRUE and the HTTP server returns an error code that is >= 400.';
        break;
      case 23:
        /*
         * CURLE_WRITE_ERROR (23)
         */
        $error_description = 'CURLE_WRITE_ERROR: An error occurred when writing received data to a local file, or an error was returned to libcurl from a write callback.';
        break;
      case 25:
        /*
         * CURLE_UPLOAD_FAILED (25)
         */
        $error_description = 'CURLE_UPLOAD_FAILED: Failed starting the upload. For FTP, the server typically denied the STOR command. The error buffer usually contains the server\'s explanation for this.';
        break;
      case 26:
        /*
         * CURLE_READ_ERROR (26)
         */
        $error_description = 'CURLE_READ_ERROR: There was a problem reading a local file or an error returned by the read callback.';
        break;
      case 27:
        /*
         * CURLE_OUT_OF_MEMORY (27)
         */
        $error_description = 'CURLE_OUT_OF_MEMORY: A memory allocation request failed. This is serious badness and things are severely screwed up if this ever occurs.';
        break;
      case 28:
        /*
         * CURLE_OPERATION_TIMEDOUT (28)
         */
        $error_description = 'CURLE_OPERATION_TIMEDOUT: Operation timeout. The specified time-out period was reached according to the conditions.';
        break;
      case 30:
        /*
         * CURLE_FTP_PORT_FAILED (30)
         */
        $error_description = 'CURLE_FTP_PORT_FAILED: The FTP PORT command returned error. This mostly happens when you haven\'t specified a good enough address for libcurl to use. See CURLOPT_FTPPORT.';
        break;
      case 31:
        /*
         * CURLE_FTP_COULDNT_USE_REST (31)
         */
        $error_description = 'CURLE_FTP_COULDNT_USE_REST: The FTP REST command returned error. This should never happen if the server is sane.';
        break;
      case 33:
        /*
         * CURLE_RANGE_ERROR (33)
         */
        $error_description = 'CURLE_RANGE_ERROR: The server does not support or accept range requests.';
        break;
      case 34:
        /*
         * CURLE_HTTP_POST_ERROR (34)
         */
        $error_description = 'CURLE_HTTP_POST_ERROR: This is an odd error that mainly occurs due to internal confusion.';
        break;
      case 35:
        /*
         * CURLE_SSL_CONNECT_ERROR (35)
         */
        $error_description = 'CURLE_SSL_CONNECT_ERROR: A problem occurred somewhere in the SSL/TLS handshake. You really want the error buffer and read the message there as it pinpoints the problem slightly more. Could be certificates (file formats, paths, permissions), passwords, and others.';
        break;
      case 36:
        /*
         * CURLE_BAD_DOWNLOAD_RESUME (36)
         */
        $error_description = 'CURLE_BAD_DOWNLOAD_RESUME: The download could not be resumed because the specified offset was out of the file boundary.';
        break;
      case 37:
        /*
         * CURLE_FILE_COULDNT_READ_FILE (37)
         */
        $error_description = 'CURLE_FILE_COULDNT_READ_FILE: A file given with FILE:// couldn\'t be opened. Most likely because the file path doesn\'t identify an existing file. Did you check file permissions?';
        break;
      case 38:
        /*
         * CURLE_LDAP_CANNOT_BIND (38)
         */
        $error_description = 'CURLE_LDAP_CANNOT_BIND: LDAP cannot bind. LDAP bind operation failed.';
        break;
      case 39:
        /*
         * CURLE_LDAP_SEARCH_FAILED (39)
         */
        $error_description = 'CURLE_LDAP_SEARCH_FAILED: LDAP search failed.';
        break;
      case 41:
        /*
         * CURLE_FUNCTION_NOT_FOUND (41)
         */
        $error_description = 'CURLE_FUNCTION_NOT_FOUND: Function not found. A required zlib function was not found.';
        break;
      case 42:
        /*
         * CURLE_ABORTED_BY_CALLBACK (42)
         */
        $error_description = 'CURLE_ABORTED_BY_CALLBACK: Aborted by callback. A callback returned "abort" to libcurl.';
        break;
      case 43:
        /*
         * CURLE_BAD_FUNCTION_ARGUMENT (43)
         */
        $error_description = 'CURLE_BAD_FUNCTION_ARGUMENT: Internal error. A function was called with a bad parameter.';
        break;
      case 45:
        /*
         * CURLE_INTERFACE_FAILED (45)
         */
        $error_description = 'CURLE_INTERFACE_FAILED: Interface error. A specified outgoing interface could not be used. Set which interface to use for outgoing connections\' source IP address with CURLOPT_INTERFACE.';
        break;
      case 47:
        /*
         * CURLE_TOO_MANY_REDIRECTS (47)
         */
        $error_description = 'CURLE_TOO_MANY_REDIRECTS: Too many redirects. When following redirects, libcurl hit the maximum amount. Set your limit with CURLOPT_MAXREDIRS.';
        break;
      case 48:
        /*
         * CURLE_UNKNOWN_OPTION (48)
         */
        $error_description = 'CURLE_UNKNOWN_OPTION: An option passed to libcurl is not recognized/known. Refer to the appropriate documentation. This is most likely a problem in the program that uses libcurl. The error buffer might contain more specific information about which exact option it concerns.';
        break;
      case 49:
        /*
         * CURLE_TELNET_OPTION_SYNTAX (49)
         */
        $error_description = 'CURLE_TELNET_OPTION_SYNTAX: A telnet option string was Illegally formatted.';
        break;
      case 51:
        /*
         * CURLE_PEER_FAILED_VERIFICATION (51)
         */
        $error_description = 'CURLE_PEER_FAILED_VERIFICATION: The remote server\'s SSL certificate or SSH md5 fingerprint was deemed not OK.';
        break;
      case 52:
        /*
         * CURLE_GOT_NOTHING (52)
         */
        $error_description = 'CURLE_GOT_NOTHING: Nothing was returned from the server, and under the circumstances, getting nothing is considered an error.';
        break;
      case 53:
        /*
         * CURLE_SSL_ENGINE_NOTFOUND (53)
         */
        $error_description = 'CURLE_SSL_ENGINE_NOTFOUND: The specified crypto engine wasn\'t found.';
        break;
      case 54:
        /*
         * CURLE_SSL_ENGINE_SETFAILED (54)
         */
        $error_description = 'CURLE_SSL_ENGINE_SETFAILED: Failed setting the selected SSL crypto engine as default!';
        break;
      case 55:
        /*
         * CURLE_SEND_ERROR (55)
         */
        $error_description = 'CURLE_SEND_ERROR: Failed sending network data.';
        break;
      case 56:
        /*
         * CURLE_RECV_ERROR (56)
         */
        $error_description = 'CURLE_RECV_ERROR: Failure with receiving network data.';
        break;
      case 58:
        /*
         * CURLE_SSL_CERTPROBLEM (58)
         */
        $error_description = 'CURLE_SSL_CERTPROBLEM: Problem with the local client certificate.';
        break;
      case 59:
        /*
         * CURLE_SSL_CIPHER (59)
         */
        $error_description = 'CURLE_SSL_CIPHER: Couldn\'t use specified cipher.';
        break;
      case 60:
        /*
         * CURLE_SSL_CACERT (60)
         */
        $error_description = 'CURLE_SSL_CACERT: Peer certificate cannot be authenticated with known CA certificates.';
        break;
      case 61:
        /*
         * CURLE_BAD_CONTENT_ENCODING (61)
         */
        $error_description = 'CURLE_BAD_CONTENT_ENCODING: Unrecognized transfer encoding.';
        break;
      case 62:
        /*
         * CURLE_LDAP_INVALID_URL (62)
         */
        $error_description = 'CURLE_LDAP_INVALID_URL: Invalid LDAP URL.';
        break;
      case 63:
        /*
         * CURLE_FILESIZE_EXCEEDED (63)
         */
        $error_description = 'CURLE_FILESIZE_EXCEEDED: Maximum file size exceeded.';
        break;
      case 64:
        /*
         * CURLE_USE_SSL_FAILED (64)
         */
        $error_description = 'CURLE_USE_SSL_FAILED: Requested FTP SSL level failed.';
        break;
      case 65:
        /*
         * CURLE_SEND_FAIL_REWIND (65)
         */
        $error_description = 'CURLE_SEND_FAIL_REWIND: When doing a send operation curl had to rewind the data to retransmit, but the rewinding operation failed.';
        break;
      case 66:
        /*
         * CURLE_SSL_ENGINE_INITFAILED (66)
         */
        $error_description = 'CURLE_SSL_ENGINE_INITFAILED: Initiating the SSL Engine failed.';
        break;
      case 67:
        /*
         * CURLE_LOGIN_DENIED (67)
         */
        $error_description = 'CURLE_LOGIN_DENIED: The remote server denied curl to login (Added in 7.13.1)';
        break;
      case 68:
        /*
         * CURLE_TFTP_NOTFOUND (68)
         */
        $error_description = 'CURLE_TFTP_NOTFOUND: File not found on TFTP server.';
        break;
      case 69:
        /*
         * CURLE_TFTP_PERM (69)
         */
        $error_description = 'CURLE_TFTP_PERM: Permission problem on TFTP server.';
        break;
      case 70:
        /*
         * CURLE_REMOTE_DISK_FULL (70)
         */
        $error_description = 'CURLE_REMOTE_DISK_FULL: Out of disk space on the server.';
        break;
      case 71:
        /*
         * CURLE_TFTP_ILLEGAL (71)
         */
        $error_description = 'CURLE_TFTP_ILLEGAL: Illegal TFTP operation.';
        break;
      case 72:
        /*
         * CURLE_TFTP_UNKNOWNID (72)
         */
        $error_description = 'CURLE_TFTP_UNKNOWNID: Unknown TFTP transfer ID.';
        break;
      case 73:
        /*
         * CURLE_REMOTE_FILE_EXISTS (73)
         */
        $error_description = 'CURLE_REMOTE_FILE_EXISTS: File already exists and will not be overwritten.';
        break;
      case 74:
        /*
         * CURLE_TFTP_NOSUCHUSER (74)
         */
        $error_description = 'CURLE_TFTP_NOSUCHUSER: This error should never be returned by a properly functioning TFTP server.';
        break;
      case 75:
        /*
         * CURLE_CONV_FAILED (75)
         */
        $error_description = 'CURLE_CONV_FAILED: Character conversion failed.';
        break;
      case 76:
        /*
         * CURLE_CONV_REQD (76)
         */
        $error_description = 'CURLE_CONV_REQD: Caller must register conversion callbacks.';
        break;
      case 77:
        /*
         * CURLE_SSL_CACERT_BADFILE (77)
         */
        $error_description = 'CURLE_SSL_CACERT_BADFILE: Problem with reading the SSL CA cert (path? access rights?)';
        break;
      case 78:
        /*
         * CURLE_REMOTE_FILE_NOT_FOUND (78)
         */
        $error_description = 'CURLE_REMOTE_FILE_NOT_FOUND: The resource referenced in the URL does not exist.';
        break;
      case 79:
        /*
         * CURLE_SSH (79)
         */
        $error_description = 'CURLE_SSH: An unspecified error occurred during the SSH session.';
        break;
      case 80:
        /*
         * CURLE_SSL_SHUTDOWN_FAILED (80)
         */
        $error_description = 'CURLE_SSL_SHUTDOWN_FAILED: Failed to shut down the SSL connection.';
        break;
      case 81:
        /*
         * CURLE_AGAIN (81)
         */
        $error_description = 'CURLE_AGAIN: Socket is not ready for send/recv wait till it\'s ready and try again. This return code is only returned from curl_easy_recv and curl_easy_send (Added in 7.18.2)';
        break;
      case 82:
        /*
         * CURLE_SSL_CRL_BADFILE (82)
         */
        $error_description = 'CURLE_SSL_CRL_BADFILE: Failed to load CRL file (Added in 7.19.0)';
        break;
      case 83:
        /*
         * CURLE_SSL_ISSUER_ERROR (83)
         */
        $error_description = 'CURLE_SSL_ISSUER_ERROR: Issuer check failed (Added in 7.19.0)';
        break;
      case 84:
        /*
         * CURLE_FTP_PRET_FAILED (84)
         */
        $error_description = 'CURLE_FTP_PRET_FAILED: The FTP server does not understand the PRET command at all or does not support the given argument. Be careful when using CURLOPT_CUSTOMREQUEST, a custom LIST command will be sent with PRET CMD before PASV as well. (Added in 7.20.0)';
        break;
      case 85:
        /*
         * CURLE_RTSP_CSEQ_ERROR (85)
         */
        $error_description = 'CURLE_RTSP_CSEQ_ERROR: Mismatch of RTSP CSeq numbers.';
        break;
      case 86:
        /*
         * CURLE_RTSP_SESSION_ERROR (86)
         */
        $error_description = 'CURLE_RTSP_SESSION_ERROR: Mismatch of RTSP Session Identifiers.';
        break;
      case 87:
        /*
         * CURLE_FTP_BAD_FILE_LIST (87)
         */
        $error_description = 'CURLE_FTP_BAD_FILE_LIST: Unable to parse FTP file list (during FTP wildcard downloading).';
        break;
      case 88:
        /*
         * CURLE_CHUNK_FAILED (88)
         */
        $error_description = 'CURLE_CHUNK_FAILED: Chunk callback reported error.';
        break;
      case 89:
        /*
         * CURLE_NO_CONNECTION_AVAILABLE (89) - Added for completeness.
         */
        $error_description = 'CURLE_NO_CONNECTION_AVAILABLE: (For internal use only, will never be returned by libcurl) No connection available, the session will be queued. (added in 7.30.0)';
        break;
      default:
        $error_description = 'UNKNOWN CURL ERROR NUMBER: This error code is not mapped to any known error.  Possibly a system error?';
        break;
    }

    return $error_description;
  }

}
