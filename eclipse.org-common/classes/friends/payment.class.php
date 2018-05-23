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
require_once(realpath(dirname(__FILE__) . '/../../system/eclipseenv.class.php'));

/**
 * Base class for PaymentGateway()
 *
 * @author chrisguindon
 */
class Payment extends EclipseEnv {

  /**
   * List of message to show to the end user
   *
   * @var array
   */
  protected $client_message = '';

  /**
   * Proxy value for curl requests
   *
   * @var string
   */
  protected $proxy = '';

  /**
   * Constructor
   */
  public function __construct() {
     parent::__construct();
  }

  /**
   * Writes $contents to the system logger which is usually the webserver's error log
   * but can be changed depending on your development requirements.
   *
   * @param mixed $contents
   * @return boolean
   * @throws Exception $e
   */
  public function error_logger($contents) {
    if (!$this->_get_debug_mode()){
      return FALSE;
    }
    if (!isset($contents) || trim($contents) == '' || is_null($contents) || empty($contents))
      return 'Error in error_logger(): Nothing to log was supplied. Usage: error_logger($contents)';

    try {

      /*
       * System Error Logging: bool error_log ( string $message )
       * $message is sent to PHP's system logger, using the Operating System's system
       * logging mechanism or a file, depending on what the error_log configuration
       * directive is set to. This is the default option.
       * See: http://www.php.net/manual/en/function.error-log.php
       */
      if (isset($contents)) {
        if (is_resource($contents)) {
          return error_log(serialize($contents));
        }
        else {
          return error_log(var_dump($contents));
        }
      }
      else {
        return false;
      }

    } catch (Exception $e) {
      echo 'Error in error_logger(): ' . $e->getMessage();
    }
  }

  /**
   * Get HTML for messages to the client
   *
   * @return string
   */
  public function get_client_message(){
    $html = "";
    foreach ($this->client_message as $type => $msgs) {
      foreach ($msgs as $m) {
        $html .= '<div class="alert alert-' . $type . '" role="alert">' . $m . '</div>';
      }
    }
    return $html;
  }

  /**
   * Set a client message
   *
   * @param string $message
   * @param string $type
   *  This should be either danger, warning, info or success
   */
  public function set_client_message($message = '', $type = 'success') {
    $alert_type = array('success', 'warning', 'danger', 'info');
    if (!in_array($type, $alert_type)) {
      $type = 'warning';
    }
    $this->client_message[$type][] = $message;
  }

  /**
   * Get HTML for proccess page
   *
   * This is based of the class name that is calling this function
   *
   * @return string
   */
  public function get_process_html(){
    ob_start();
    include('tpl/'. strtolower(get_class($this)) . '-process.tpl.php');
    return ob_get_clean();
  }

  /**
   * Email IPN results to webdev@eclipse.org
   */
  protected function _email_ipn_post() {
    // Bug 2442 - Stop sending IPN email notifications to webdev
    return TRUE;

    ob_start();
    print '-------$_POST---------' . PHP_EOL;
    print_r($_POST) . PHP_EOL;
    print '-------$_SERVER---------' . PHP_EOL;
    print_r($_SERVER) . PHP_EOL;
    $this->_extend_email_ipn_post();
    $message = ob_get_clean();
    mail('webdev@eclipse.org', 'Eclipse Donation IPN: This a debug message', $message);
  }

  /**
   * Set default curl option for all curl request
   */
  protected function _get_curl_options(&$curl) {
    curl_setopt($curl, CURLOPT_FORBID_REUSE, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    // CONFIG: Optional proxy configuration
    curl_setopt($curl, CURLOPT_PROXY, $this->_get_proxy());
    curl_setopt($curl, CURLOPT_HTTPPROXYTUNNEL, 1);
    if ($this->getEnvShortName() === 'local') {
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curl, CURLOPT_PROXY, '');
    }
  }

  /**
   * Get proxy value
   *
   * @return string
   */
  protected function _get_proxy() {
    if (empty($this->proxy)) {
      $this->_set_proxy();
    }
    return $this->proxy;
  }

  /**
   * Allow classes to extend the ipn post e-mail
   * @return string
   */
  protected function _extend_email_ipn_post() {
    return '';
  }

  /**
   * Log donation status in the EvtLog.
   *
   * @param unknown $action
   */
  protected function _log_database($action) {
    // Database Logging
    $EvtLog = new EvtLog();
    $EvtLog->setLogTable("__paypal.class");
    if ($this->transaction) {
      $EvtLog->setPK1("$this->donation_transaction_id,$this->donation_amount,$this->donation_status");
    }
    else {
     $EvtLog->setPK1("Unknown");
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $EvtLog->setPK2($ip);
    $EvtLog->setLogAction($action);
    if ($this->donor_email) {
      $EvtLog->insertModLog($this->donor_email);
    }
    else {
     $EvtLog->insertModLog("Unknown");
    }
  }

  /**
   * Append class specific functionnality to _set_debug_mode()
   */
  protected function _extend_set_debug_mode() {
    return TRUE;
  }

  /**
   * Enable/disable debug/sandbox mode
   */
  protected function _set_debug_mode($debug_mode = FALSE){
    $this->debug_mode = $debug_mode;
    if ($this->_get_debug_mode()) {
      $this->set_client_message('Debug, logging and Sandbox mode is enabled.', 'warning');
      $this->_extend_set_debug_mode();
    }
  }

  /**
   * Set eclipse proxy for staging/production
   */
  protected function _set_proxy(){
    if ($this->_get_prefix_domain() === 'www.eclipse.org') {
      $this->proxy = 'proxy.eclipse.org:9899';
    }
  }

  /**
   * Helper SQL function to Insert/Update
   *
   * @param unknown $table
   * @param unknown $fields
   * @param unknown $possible_null_field
   * @return string
   */
  protected function _sql_on_duplicate_update($table, $fields = array(), $possible_null_field = array()) {
    if ($this->_get_debug_mode()) {
      $table = 'testing_' . $table;
    }
    $sql = "INSERT INTO " . $table . " (";
    $columns = array();
    $values = array();
    foreach ($fields as $key => $value) {
      if (!empty($value)) {
        $columns[] = $key;
        $values[] = '"' . $this->App->sqlSanitize($value) . '"';
      }
      else if(in_array($key, $possible_null_field)) {
        $columns[] = $key;
        $values[] = 'NULL';
      }
    }

    $sql .= implode(',', $columns);
    $sql .= ') VALUES (';
    $sql .= implode(',', $values);
    $sql .= ")  ON DUPLICATE KEY UPDATE";
    foreach ($columns as $key => $value){
      $sql .= ' ' .$value . '=' . $values[$key] . ',';
    }
    $sql = rtrim($sql, ',');
    return $sql;
  }
}

require_once("gateway/paypal.class.php");
require_once("gateway/bitpay.class.php");