<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/

require_once ("recaptchaMailHide.class.php");

define("RECAPTCHA_API_SERVER", "www.google.com/recaptcha/api");

define("RECAPTCHA_VERIFY_SERVER", "www.google.com");

class ReCaptcha extends ReCaptchaMailHide {

  private $pubkey = "";

  private $privkey = "";

  private $challenge = NULL;

  private $response = NULL;

  private $use_ssl = TRUE;

  private $server = "";

  private $remoteip = "";

  private $is_valid = FALSE;

  private $error = "";

  function __construct($ssl = TRUE) {
    parent::__construct();
    $this->pubkey = RECAPTCHA_PUBKEY;
    $this->privkey = RECAPTCHA_PRIVKEY;

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    $this->remoteip = $ip;
    $this->_use_ssl($ssl);
  }

  /**
   * Gets error from reCAPTCHA.
   *
   * @return string
   */
  public function  get_error() {
    return $this->error;
  }

  /**
   * Gets the challenge HTML (javascript and non-javascript version).
   *
   * This is called from the browser, and the resulting reCAPTCHA HTML widget
   * is embedded within the HTML form it was called from.
   *
   * @return string - The HTML to be embedded in the user's form.
   */
  public function get_html() {
    if ($this->pubkey == NULL || $this->pubkey == '') {
      die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
    }

    $errorpart = "";
    if ($this->error) {
      $errorpart = "&amp;error=" . $this->error;
    }

    return '<script type="text/javascript" src="'. $this->server . '/challenge?k=' . $this->pubkey . $errorpart . '"></script>

    <noscript>
        <iframe src="'. $this->server . '/noscript?k=' . $this->pubkey . $errorpart . '" height="300" width="500" frameborder="0"></iframe><br/>
        <textarea name="challenge_field" rows="3" cols="40"></textarea>
        <input type="hidden" name="response_field" value="manual_challenge"/>
    </noscript>';
  }

  /**
   * Verify if the captcha anwser was correct.
   *
   * @return bool
   */
  public function validate() {
    $this->verify_answer();
    return $this->is_valid;
  }

  /**
    * Calls an HTTP POST function to verify if the user's guess was correct
    *
    * @param string $challenge
    * @param string $response
    * @param array $extra_params an array of extra variables to post to the server
    *
    * @return bool
    */
  public function verify_answer($challenge = NULL, $response = NULL, $extra_params = array()) {
    if ($this->privkey == null || $this->privkey == '') {
      die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
    }

    if ($this->remoteip == null || $this->remoteip == '') {
      die ("For security reasons, you must pass the remote ip to reCAPTCHA");
    }

    //discard spam submissions
    if (!$this->_verify_spam($challenge, $response)) {
      $this->is_valid = FALSE;
      $this->error = 'incorrect-captcha-sol';
      return FALSE;
    }

    $path = array_merge(array(
      'privatekey' => $this->privkey,
      'remoteip' => $this->remoteip,
      'challenge' => $this->challenge,
      'response' => $this->response
    ), $extra_params);

    $response = $this->_http_post(RECAPTCHA_VERIFY_SERVER, "/recaptcha/api/verify", $path);

    $answers = explode("\n", $response[0]);

    if (trim($answers[0]) == 'true') {
      $this->is_valid = TRUE;
      return TRUE;
    }
    else {
      $this->is_valid = FALSE;
      $this->error = $answers[1];
    }

    return FALSE;
  }

  /**
   * Submits an HTTP POST to a reCAPTCHA server
   *
   * @param string $host
   * @param string $path
   * @param array $data
   *
   * @return array response
   */
  private function _http_post($host, $path, $data) {
    $add_headers = array(
      "Host: $host",
    );

    $cu = curl_init('https://' . $host . $path);
    curl_setopt($cu, CURLOPT_POST, TRUE);
    curl_setopt($cu, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($cu, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($cu, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($cu, CURLOPT_USERAGENT, 'reCAPTCHA/PHP');
    curl_setopt($cu, CURLOPT_POSTFIELDS, $data);
    curl_setopt($cu, CURLOPT_HEADER, FALSE);
    curl_setopt($cu, CURLOPT_HTTPHEADER, $add_headers);
    curl_setopt($cu, CURLOPT_CONNECTTIMEOUT, 30);

    curl_setopt($cu, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($cu, CURLOPT_SSL_VERIFYHOST, 2);

    // Need to use eclipse.org proxy
    curl_setopt($cu, CURLOPT_HTTPPROXYTUNNEL, TRUE);
    curl_setopt($cu, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    curl_setopt($cu, CURLOPT_PROXY, 'proxy.eclipse.org:9899');

    $response = curl_exec($cu);
    if ($response === FALSE) {
      die('Error connecting to ' . $host . '.');
    }

    $response = explode("\r\n\r\n", $response, 2);

    return $response;
}


  /**
   * Encodes the given data into a query string format
   *
   * @param $data - array of string elements to be encoded
   *
   * @return string - encoded request
   */
  private function _qsencode($data) {
    $req = "";
    foreach ($data as $key => $value ) {
      $req .= $key . '=' . urlencode(stripslashes($value)) . '&';
    }

    // Cut the last '&'
    $req = substr($req,0,strlen($req)-1);
    return $req;
  }

  /**
   * Verify if the request is spam before posting to reCAPTCHA.
   *
   * @param string $challenge
   * @param string $response
   *
   * @return boolean
   */
  private function _verify_spam($challenge = NULL, $response = NULL) {

    $this->challenge = $challenge;
    $this->response = $response;

    if ((is_null($this->challenge) || empty($this->challenge)) && isset($_POST["recaptcha_response_field"])) {
      $this->challenge = $_POST["recaptcha_challenge_field"];
    }

    if ((is_null($this->response) || empty($this->response)) && isset($_POST["recaptcha_response_field"])) {
      $this->response = $_POST["recaptcha_response_field"];
    }

    if ($this->challenge == null || strlen($this->challenge) == 0 || $this->response == null || strlen($this->response) == 0) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Set protocol for loading reCAPTCHA
   *
   * @param string $use_ssl
   *
   * @return boolean
   */
  private function _use_ssl($use_ssl = TRUE) {
    $protocol =  "http://";
    if (is_bool($use_ssl)) {
      $this->use_ssl = $use_ssl;
    }

    if ($this->use_ssl) {
      $protocol =  "https://";
    }

    $this->server = $protocol . RECAPTCHA_API_SERVER;
    return $this->use_ssl;
  }
}