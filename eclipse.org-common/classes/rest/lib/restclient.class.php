<?php
/*******************************************************************************
* Copyright (c) 2013, 2014, 2015, 2016 Eclipse Foundation and others.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the Eclipse Public License v1.0
* which accompanies this distribution, and is available at
* http://www.eclipse.org/legal/epl-v10.html
*
* Contributors:
*    Zak James (zak.james@gmail.com) - Initial implementation
*    Denis Roy (Eclipse Foundation)
*    Christopher Guindon (Eclipse Foundation) - Refactoring for usability and USS
*******************************************************************************/
require_once(realpath(dirname(__FILE__) . '/../../../system/eclipseenv.class.php'));

/**
 * RestClient class
 *
 * @author chrisguindon
 */
class RestClient extends EclipseEnv {

  /**
   * Base url for api requests.
   * @var string
   */
  protected $base_url = "";

  /**
   * List of headers for subsequent request.
   * @var array
   */
  protected $header = array();

  /**
   * List of cookies for subsequent request.
   * @var array
   */
  protected $cookie = array();

  /**
   * Proxy value for curl requests
   *
   * @var string
   */
  protected $proxy = '';

  /**
   * Result of the last request made
   *
   * @var unknown
   */
  protected $result = NULL;

  /**
   * Oauth2 access_token
   *
   * @var string
   */
  protected $access_token = "";

  function __construct(App $App = NULL) {
    parent::__construct($App);
    // Default headers
    $default_headers = array(
      'Content-Type' => 'application/json',
      'Accept' =>'application/json',
      'User-Agent' => 'eclipse/foundation'
    );
    $this->setHeader($default_headers);
  }

  /**
   * Execute a GET request.
   *
   * @param string $url
   *
   * @return Response $data
   */
  public function get($url) {
    return $this->curl_exec($url);
  }

  /**
   * Execute a POST request.
   *
   * @param string $url
   * @param string $data
   *
   * @return Response $data
   */
  public function post($url, $data = NULL) {
    $options[CURLOPT_POST] = TRUE;
    $options[CURLOPT_POSTFIELDS] = $data;

    return $this->curl_exec($url, $options);

  }

  /**
   * Execute a PUT request.
   *
   * @param string $url
   * @param string $data
   *
   * @return Response $data
   */
  public function put($url, $data = NULL) {
    $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
    $options[CURLOPT_POSTFIELDS] = $data;

    return $this->curl_exec($url, $options);
  }

  /**
   * Execute a DELETE request.
   *
   * @param string $url
   * @param string $data
   *
   * @return Response $data
   */
  public function delete($url) {
    $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
    return $this->curl_exec($url, $options);
  }

  /**
   * Execute a PATCH request.
   *
   * @param string $url
   * @param string $data
   *
   * @return Response $data
   */
  public function patch($url, $data) {
    $options[CURLOPT_CUSTOMREQUEST] = 'PATCH';
    $options[CURLOPT_POSTFIELDS] = $data;
    return $this->curl_exec($url, $options);
  }

  /**
   * Get $base_url
   *
   * @return string $base_url
   */
  public function getBaseUrl() {
    return $this->base_url;
  }

  /**
   * Set $base_url
   * @param string $url
   *
   * @return string $base_url
   */
  public function setBaseUrl($url) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
      $this->base_url = $url;
    }
    return $this->base_url;
  }

  /**
   * Shortcut for the decoded value of the request body
   *
   * The server must return data in JSON.
   */
  public function getRequestBody($json = TRUE) {
    $return = new stdClass();
    if (isset($this->result->body) && !empty($this->result->body)) {
      $return = $this->result->body;
      if ($json) {
        $return = json_decode(stripslashes($this->result->body));
      }
    }
    return $return;
  }

  /**
   * Get $cookie
   * Convert $cookie array into a string
   *
   * @return string $cookie
   */
  public function getCookie() {
    return implode('; ', $this->cookie);
  }

  /**
   * Set $cookie
   * @param array $cookies
   *
   * @return bool
   */
  public function setCookie($cookies = array()) {
    if (!is_array($cookies)) {
      return FALSE;
    }
    foreach ($cookies as $key => $value) {
      $this->cookie[$key] = $value;
    }
    return TRUE;
  }

  /**
   * Get $header
   * Remove array key from $header
   *
   * @return array $header
   */
  public function getHeader() {
    return array_values($this->header);
  }

  /**
   * Set $header
   * @param array $headers
   *
   * @return bool
   */
  public function setHeader($headers = array()) {
    if (!is_array($headers)) {
      return FALSE;
    }
    foreach ($headers as $key => $value) {
      $this->header[$key] = $key . ': '. $value;
    }
    return TRUE;
  }

  /**
   * Get access_token
   *
   * @return string
   */
  public function getAccessToken() {
    return $this->access_token;
  }

  /**
   * Get access_token
   *
   * @param string $token
   */
  public function setAccessToken($token = "") {
    $this->access_token = $token;
    $_SESSION['access_token']['uss'] = $token;
  }

  /**
   * Unset $header
   * @param string $header
   *
   * @return bool
   */
  public function unsetHeader($header = "") {
    if (isset($this->header[$header])) {
      unset($this->header[$header]);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Execute a CURL request
   *
   * @param string $path
   * @param array $options
   *
   * @return Response $return
   */
  protected function curl_exec($path = '', $options = array()) {
    $this->result = new stdClass();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    //proxy configuration
    //curl_setopt($ch, CURLOPT_PROXY, $this->_get_proxy());
    //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
    if ($this->getEnvShortName() === 'local') {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_PROXY, '');
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    $options[CURLOPT_URL] = $this->base_url . '/' . $path;

    // Use access token if set
    if ($token = $this->getAccessToken()) {
      $this->setHeader(array('Authorization' => 'Bearer ' . $token));
    }

    $headers = $this->getHeader();
    $cookies = $this->getCookie();
    if (!empty($cookies)) {
      $headers[] = 'Cookie: ' . $cookies;
    }
    $options[CURLOPT_HTTPHEADER] = $headers;
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    if (!$result) {
      $this->result->error = curl_error($ch);
      $this->result->code = 9990;
      $this->result->request_error = $result;
      curl_close($ch);
      return $this->result;
    }
    $this->result->options = $options;
    $this->result->request = curl_getinfo($ch);
    $this->result->body = substr($result, $this->result->request['header_size']);
    curl_close($ch);

    $this->result->headers = array();
    $header_text = substr($result, 0, strpos($result, "\r\n\r\n"));
    foreach (explode("\r\n", $header_text) as $i => $line) {
      if ($i === 0) {
        $this->result->headers['http_code'] = $line;
      }
      else {
        list ($key, $value) = explode(': ', $line);
        $this->result->headers[$key] = $value;
      }
    }

    $response_array = explode(' ', trim($this->result->headers['http_code']), 3);

    $this->result->status_message = '';
    $this->result->protocol = $response_array[0];
    $this->result->code = $this->result->request['http_code'];
    if (isset($response_array[2])) {
      $this->result->status_message = $response_array[2];
    }
    return $this->result;
  }

  /**
   * Get 'Link' header
   *
   * @param string $link
   *
   * @return bool/array $links
   */
  protected function _getHeaderLink($link = '') {
    if (strlen($link) == 0) {
      return FALSE;
    }

    $parts = explode(',', $link);
    $links = array();
    foreach($parts as $p) {
      $section = explode(';', htmlspecialchars_decode($p));
      if (count($section) != 2) {
        return FALSE;
      }
      $url = $this->_removeBaseUrlFromUrl(trim(preg_replace("/<(.*)>/", '$1', $section[0])));
      $key = trim(preg_replace("/rel=\"(.*)\"/", '$1', $section[1]));
      $links[$key] = $url;
    }
    return $links;
  }

  /**
   * Get next page from pagination
   *
   * @param  Response $data
   *
   * @return Response $return
   */
  public function _getNextPage($data) {
    if ($data && isset($data->headers['Link']) && !isset($data->error) && !empty($data->body)) {
      $pages = $this->_getHeaderLink($data->headers['Link']);
      if (($pages['self'] !== $pages['last']) && !empty($pages['next'])){
        if ($data = $this->get($pages['next'])) {
          return $data;
        }
      }
    }
    return FALSE;
  }

  /**
   * Remove $base_url from $url
   * @param string $url
   *
   * @return string $url
   */
  protected function _removeBaseUrlFromUrl($url = '') {
    return str_replace($this->getBaseUrl() . '/', '', $url);
  }

  /**
   * Get proxy value
   *
   * @return string
   */
  private function _get_proxy() {
    if (empty($this->proxy)) {
      $this->_set_proxy();
    }
    return $this->proxy;
  }

  /**
   * Set eclipse proxy for staging/production
   */
  private function _set_proxy(){
    if ($this->_get_prefix_domain() === 'www.eclipse.org') {
      $this->proxy = 'proxy.eclipse.org:9899';
    }
  }
}