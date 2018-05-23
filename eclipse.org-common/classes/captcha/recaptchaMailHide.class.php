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

require_once("/home/data/httpd/eclipse-php-classes/system/captchacode.php");

class ReCaptchaMailHide {

  private $pubkey = "";

  private $privkey = "";

  function __construct() {
    $this->pubkey = RECAPTCHA_MAILHIDE_PUBKEY;
    $this->privkey = RECAPTCHA_MAILHIDE_PRIVKEY;
  }

  /**
   * Gets html to display an email address given a public an private key.
   * to get a key, go to:
   *
   * http://www.google.com/recaptcha/mailhide/apikey
   *
   * @param string $email
   * @return string
   */
  public function get_mailhide_html($email) {
    $emailparts = $this->_mailhide_email_parts($email);
    $url = $this->get_mailhide_url($email);

    return htmlentities($emailparts[0]) . "<a href='" . htmlentities($url) .
      "' onclick=\"window.open('" . htmlentities($url) . "', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;\" title=\"Reveal this e-mail address\">...</a>@" . htmlentities($emailparts[1]);
  }

  /**
   * Gets the reCAPTCHA Mailhide url for a given email
   *
   * @param string $email
   * @return string url
   */
  public function get_mailhide_url($email = "") {
    if ($this->pubkey == '' || $this->pubkey == null || $this->privkey == "" || $this->privkey == null) {
      die ("To use reCAPTCHA Mailhide, you have to sign up for a public and private key, " .
           "you can do so at <a href='http://www.google.com/recaptcha/mailhide/apikey'>http://www.google.com/recaptcha/mailhide/apikey</a>");
    }

    $ky = pack('H*', $this->privkey);
    $cryptmail = $this->_aes_encrypt($email, $ky);

    return "http://www.google.com/recaptcha/mailhide/d?k=" . $this->pubkey . "&c=" . $this->_mailhide_urlbase64($cryptmail);
  }

  /**
   * Prepare value for encryption.
   *
   * @param string $val
   * @return string
   */
  protected function _aes_pad($val = "") {
    $block_size = 16;
    $numpad = $block_size - (strlen ($val) % $block_size);
    return str_pad($val, strlen ($val) + $numpad, chr($numpad));
  }

  /**
   * Encrypt value with mcrypt_encrypt().
   *
   * @param string $val
   * @param string $ky
   *
   * @return string
   */
  private function _aes_encrypt($val = "", $ky = "") {
    if (!function_exists ("mcrypt_encrypt")) {
      die ("To use reCAPTCHA Mailhide, you need to have the mcrypt php module installed.");
    }
    $mode = MCRYPT_MODE_CBC;
    $enc = MCRYPT_RIJNDAEL_128;
    $val = $this->_aes_pad($val);

    return mcrypt_encrypt($enc, $ky, $val, $mode, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
  }

  /**
   * base64_encode mcrypt_encrypt value.
   *
   * @param string $x
   *
   * @return string
   */
  private function _mailhide_urlbase64($x = "") {
    return strtr(base64_encode($x), '+/', '-_');
  }

  /**
   * Gets the parts of the email to expose to the user.
   *
   * eg, given johndoe@example,com return ["john", "example.com"].
   * the email is then displayed as john...@example.com
   *
   * @param string $email
   *
   * @return array
   */
  private function _mailhide_email_parts($email) {
    $arr = preg_split("/@/", $email);

    if (strlen ($arr[0]) <= 4) {
      $arr[0] = substr($arr[0], 0, 1);
    } else if (strlen($arr[0]) <= 6) {
      $arr[0] = substr($arr[0], 0, 3);
    } else {
      $arr[0] = substr($arr[0], 0, 4);
    }

    return $arr;
  }
}