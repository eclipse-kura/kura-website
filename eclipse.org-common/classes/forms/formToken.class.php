<?php
/*******************************************************************************
* Copyright (c) 2016 Eclipse Foundation and others.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the Eclipse Public License v1.0
* which accompanies this distribution, and is available at
* http://www.eclipse.org/legal/epl-v10.html
*
* Contributors:
*    Christopher Guindon (Eclipse Foundation) - Refactoring for usability and USS
*******************************************************************************/

/**
 * Simple form token calss
 *
 * @author chrisguindon
 */
class FormToken {
  function __construct(App $App = NULL) {
    session_start();
    $this->getToken();
  }

  /**
   * Generate a token
   */
  private function _generateToken() {
    if (function_exists('mcrypt_create_iv')) {
      $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    }
    else {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
  }

  /**
   * Get token
   *
   * Generate a token if it does not exist
   *
   * @return unknown
   */
  public function getToken(){
    if (empty($_SESSION['token'])) {
      $this->_generateToken();
    }
    return $_SESSION['token'];
  }

  /**
   * Verify token
   *
   * @param string $token
   * @return boolean
   */
  public function verifyToken($token = "") {
    $return = FALSE;
    if (!empty($token) && $this->_hash_equals($_SESSION['token'], $token)) {
      $return = TRUE;
    }
     $this->_generateToken();
    return $return;
  }

  /**
   * hash_equals for previous version of php 5.6
   *
   * @param unknown $str1
   * @param unknown $str2
   * @return boolean
   */
  private function _hash_equals($str1, $str2) {
    if(strlen($str1) != strlen($str2)) {
      return FALSE;
    }

    $res = $str1 ^ $str2;
    $ret = 0;
    for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
    return !$ret;
  }
}