<?php
/**
 * *****************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Eric Poirier (Eclipse Foundation)- initial API and implementation
 * *****************************************************************************
 */

Class Cookies {

  private $cookie_name = "eclipse_settings";

  private $cookie_domain = "";

  /**
   * Set the Banner Ad Cookies
   *
   * @param string $key - Key/Name of the setting
   *
   * @param null $value - array or string of value
   *
   * @param null $expiration - expiration date in timestamp
   */
  public function setCookies($name, $value = NULL, $expiration = NULL) {

    // Return here is no name have been specified
    if (empty($name)) {
      return FALSE;
    }

    // Make sure the expiration is a number
    if (!is_numeric($expiration)) {
      $expiration = strtotime("+365 day");
    }

    // Json decoded cookies
    $cookie_values = $this->getCookie();

    // Update or add new $key
    $cookie_values[$name]['value'] = $value;
    $cookie_values[$name]['expiration'] = $expiration;

    // Get the cookie name
    $cookie_name = $this->getCookieName();

    // Set the path of the cookie to root
    // since the cookie should be available
    // everywhere on the site.
    $cookie_path = "/";

    // Set the cookie with the appropriate cookie name, values and path
    // Here we set the expiry date of the global cookie to a year
    setcookie($cookie_name,json_encode($cookie_values, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), strtotime("+365 day"), $cookie_path);
  }

  /**
   * Get the Banner Ad cookies
   *
   * @return array
   */
  public function getCookie() {

    $cookie = array();

    // Get the cookie name
    $cookie_name = $this->getCookieName();

    // If the cookie is set in the browser
    if (isset($_COOKIE[$cookie_name])) {

      // decode it and make sure it's an array
      // by specifying TRUE as a parameter
      $cookie = json_decode($_COOKIE[$cookie_name], TRUE);
    }

    return $cookie;
  }

  /**
   * Get a specific cookie based on a key
   *
   * @param string $key
   *
   * @return array
   */
  public function getNameValue($name = "") {
    if (empty($name)) {
      return array();
    }

    // Get the cookie
    $cookie = $this->getCookie();

    if (!isset($cookie[$name])) {
      return array();
    }

    return $cookie[$name];
  }

  /**
   * Get a Cookie Name
   *
   * @return string
   */
  public function getCookieName() {
    if (empty($this->cookie_name)) {
      $this->setCookieName();
    }
    return $this->cookie_name;
  }

  /**
   * Set a Cookie name
   *
   * @param string $name
   */
  public function setCookieName($name = "") {
    if (!empty($name)) {
      $this->cookie_name = $name;
    }
  }
}
