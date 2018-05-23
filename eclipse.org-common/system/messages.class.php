<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

class Messages {

  function __construct() {
    if (session_id() == '') {
      session_start();
    }

    if (empty($_SESSION['eclipse_system_messages']) || !is_array($_SESSION['eclipse_system_messages'])) {
      $_SESSION['eclipse_system_messages'] = array();
    }
  }

  /**
   * This function sets the message
   * @param $name - string containing the name of the message
   * @param $msg  - string containing the message itself
   * @param $type - string containing the type of the message
   * */
  public function setMessages($name = "", $msg = "", $type = "") {
    $allowed_type = array(
      'success',
      'info',
      'warning',
      'danger'
    );
    if (in_array($type, $allowed_type) && !empty($msg) && !empty($name)) {
      $_SESSION['eclipse_system_messages'][$name][$type][] = $msg;
    }
  }

  /**
   * Convert message type to valid drupal message type
   *
   * drupal_set_message only supports 'status',
   * 'warning' and 'error'. This translate a status
   * from this class to a drupal status.
   *
   * @param string $type
   */
  public function translateToDrupalStatus($type = ""){
    $type = strtolower($type);
    switch($type) {
      case 'success':
        return 'status';
      case 'info':
      case 'warning':
        return 'warning';
      case 'danger':
        return 'error';
    }
    return 'status';
  }

  /**
   * This function returns the Messages
   *
   * Messages are removed from $_SESSION['eclipse_system_messages']
   * after calling this function.
   *
   * @param $msg - array containing the names, types and content of each messages
   * @return string
   * */
  public function getMessages() {
    $messages = $_SESSION['eclipse_system_messages'];
    $_SESSION['eclipse_system_messages'] = array();
    $return = "";
    if (!empty($messages)) {
      foreach ($messages as $type) {
        foreach ($type as $key => $value) {
          $list = '<ul>';
          if (count($value) == 1) {
            if ($key == 'danger'){
              $org_value = $value[0];
              $value[0] = '<p><strong>' . $org_value . '</strong></p>';
            }
            $return .= $this->_getMessageContainer($value[0], $key);
            continue;
          }
          foreach ($value as $msg) {
            $list .= '<li><strong>' . $msg . '</strong></li>';
          }
          $list .= '</ul>';
          $return .= $this->_getMessageContainer($list, $key);
        }
      }
    }
    return $return;
  }

  /**
   * Get system message array
   *
   * Messages are removed from $_SESSION['eclipse_system_messages']
   * after calling this function.
   *
   * @return array
   */
  function getMessagesArray(){
    $messages = $_SESSION['eclipse_system_messages'];
    $_SESSION['eclipse_system_messages'] = array();
    $return  = array();
    if (!empty($messages)) {
      foreach ($messages as $type) {
        foreach ($type as $key => $value) {
          foreach ($value as $msg) {
            $return[$key][] = $msg;
          }
        }
      }
    }
    return $return;
  }

  /**
   * This function returns a DIV tag containing the $message with the proper CSS class
   * @param $message - String containing the message
   * @param $type    - String containing the message type
   *                   Accepted types: success, info, warning, danger
   * @return string
   * */
  private function _getMessageContainer($message = '', $type = 'success') {
    $class = "stay-visible alert alert-" . $type;
    return '<div class="' . $class . '" role="alert">' . $message . '</div>';
  }
}