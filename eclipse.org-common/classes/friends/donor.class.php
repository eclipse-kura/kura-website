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
require_once("/home/data/httpd/eclipse-php-classes/system/ldapconnection.class.php");
require_once("friend.class.php");
require_once("contribution.class.php");

/**
 * This class represent an donor. It holds the values from
 * the friends_process table or from IPN payload.
 *
 * @author chrisguindon
 */
class Donor {

  /**
   * Contributions() object
   */
  public $Contribution = NULL;

 /**
  * Email address of donor
  */
  public $donor_email = "";

  /**
   * First name of donor
   */
  public $donor_first_name = "";

  /**
   * Last name of donor
   */
  public $donor_last_name = "";

  /**
   * Paypal e-mail address
   */
  public $donor_paypal_email = "";

  /**
   * LDAP uid of donor
   */
  public $donor_uid = "";

  /**
   * LDAP UID
   * @var unknown
   */
  public $donor_ldap_uid = "";

  /**
   * LDAP First name
   * @var unknown
   */
  public $donor_ldap_first_name = "";

  /**
   * LDAP Last name
   * @var unknown
   */
  public $donor_ldap_last_name = "";

  /**
   * Friend() object
   */
  public $Friend = NULL;

  public function __construct($test_mode)  {
    $Session = New Session();
    $Friend = $Session->getFriend();
    $fid = $Friend->getFriendID();
    $this->Friend = new Friend($test_mode);
    $this->Contribution = new Contribution($test_mode);
    if ($fid != 0 && !empty($fid)) {
      $this->Friend = $Friend;
      $this->Contribution->setFriendID($fid);
      $this->set_donor_email($Friend->getEmail());
      $this->set_donor_first_name($Friend->getFirstName());
      $this->set_donor_last_name($Friend->getLastName());
      $this->set_donor_uid($Friend->getLDAPUID());

      // If there is a valid eclipse id, the first and last name
      // will be from LDAP.
      $this->Friend->setFirstName($this->get_donor_first_name());
      $this->Friend->setLastName($this->get_donor_last_name());
    }
  }

  /**
   * Helper function to select active e-mail if possible
   * @param unknown $Friend
   */
  public function get_active_email() {
    if (!empty($this->donor_email)) {
      return $this->donor_email;
    }

    if (!empty($this->donor_paypal_email)) {
      return $this->donor_paypal_email;
    }

    $friend_email = $this->Friend->getEmail();
    if (!empty($friend_email)) {
      $this->set_donor_email($friend_email);
      return $this->donor_paypal_email;
    }

    return '';
  }

  /**
   * Set donor email
   */
  public function get_donor_email() {
    return $this->donor_email;
  }


 /**
   * Get donor first name
   */
  public function get_donor_first_name() {
    return ucwords(strtolower($this->donor_first_name));
  }

  /**
   * Get the full name of the donor
   */
  public function get_donor_full_name() {
    if ($this->donor_first_name != '' || $this->donor_last_name != "") {
      return $this->get_donor_first_name(). ' ' . $this->get_donor_last_name();
    }
    return '';
  }

  /**
   * Get donor last name
   */
  public function get_donor_last_name() {
    return ucwords(strtolower($this->donor_last_name));
  }

  /**
   * Get paypal e-mail address
   */
  public function get_donor_paypal_email(){
    return $this->donor_paypal_email;
  }

  /**
   * Get donor LDAP uid
   * @return string
   */
  public function get_donor_uid() {
    $this->set_donor_uid();
    return $this->donor_uid;
  }

  function get_friend_id_from_uid() {
    $fid = $this->Friend->selectFriendID('uid', $this->get_donor_uid());
    $this->Friend->setFriendID($fid);
    $this->Friend->setFirstName($this->get_donor_first_name());
    $this->Friend->setLastName($this->get_donor_last_name());
    $this->Friend->setLDAPUID($this->get_donor_uid());
    $bugzilla_id = $this->Friend->getBugzillaIDFromEmail($this->get_active_email());
    $this->Friend->setBugzillaID($bugzilla_id);
    $this->Contribution->setFriendID($fid);
    return $fid;
  }

  /**
   * Get donor email
   * @param unknown $_email
   */
  public function set_donor_email($email = "") {
    if (empty($email) && empty($this->donor_email)) {
      // Handle Logged in User Session
      $Session = new Session();
      $Friend = $Session->getFriend();
      $email = $Friend->getEmail();
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->donor_email = strtolower($email);
      $this->set_donor_uid();
    }
  }

  /**
   * Set donor first name
   *
   * @param unknown $name
   */
  public function set_donor_first_name($name) {
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $name = ucwords(strtolower($name));
    if ($name != "") {
      $this->donor_first_name = $name;
    }

    // Always use LDAP first name if possible
    if (!empty($this->donor_ldap_first_name)) {
      $this->donor_first_name = $this->donor_ldap_first_name;
    }
  }

  /**
   * Set donor last name
   *
   * @param unknown $name
   */
  public function set_donor_last_name($name) {
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $name = ucwords(strtolower($name));
    if ($name != "") {
      $this->donor_last_name = $name;
    }
    // Always use LDAP first name if possible
    if (!empty($this->donor_ldap_last_name)) {
      $this->donor_last_name = $this->donor_ldap_last_name;
    }
  }

  /**
   * Set paypal e-mail address
   */
  public function set_donor_paypal_email($email) {
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $email = strtolower($email);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $this->donor_paypal_email = $email;
      $this->set_donor_uid();
    }
    if (empty($this->donor_email)) {
      $this->set_donor_email($email);
    }
  }

  /**
   * Set donor LDAP uid
   */
  public function set_donor_uid($uid = '') {
    $uid = filter_var($uid, FILTER_SANITIZE_STRING);
    $active_email =  $this->get_active_email();
    if (!empty($uid)){
      $this->_set_donor_uid($uid);
    }

    // if uid is empty, let's try to find one.
    if (empty($this->donor_uid) && !empty($active_email)) {
      $uid = $this->get_LDAP_uid_from_email($active_email);
      $this->_set_donor_uid($uid);
    }

    if (empty($this->donor_uid) && $this->Friend->getLDAPUID() != "") {
       $this->_set_donor_uid($this->Friend->getLDAPUID());
    }
  }

  /**
   * Set donor uid if it exists in LDAP
   *
   * This function will also override the first and last name
   * of the donor with information from ldap if a uid exist in LDAP.
   *
   * @param string $uid
   */
  public function _set_donor_uid($uid = "") {
    $LDAP = new LDAPConnection();
    if ($dn = $LDAP->getDNFromUID($uid)) {
      if ($this->donor_uid != $uid) {
        $this->donor_uid = $uid;
      }
      else {
       return FALSE;
      }
    }
    else {
      return FALSE;
    }

    $this->donor_ldap_uid = $uid;
    $this->donor_ldap_first_name = $LDAP->getLDAPAttribute($dn, 'givenName');
    $this->donor_ldap_last_name = $LDAP->getLDAPAttribute($dn, 'sn');
    $this->set_donor_first_name($this->donor_ldap_first_name);
    $this->set_donor_last_name($this->donor_ldap_last_name);
    $this->get_friend_id_from_uid();

    return TRUE;
  }

  /**
   * Shortcut for setting the donation transaction id
   *
   * For paypal, this is the txn_id but for bitpay its the invoice id.
   *
   * @param string $txn_id
   */
  public function set_donor_contribution_with_txn_id($txn_id = "") {
    $this->Contribution->selectContributionWithTransaction($txn_id);
  }

  /**
   * Helper function for getting the LDAP uid from e-mail
   *
   * @param unknown $_email
   */
  public function get_LDAP_uid_from_email($_email) {
    if (!filter_var($_email, FILTER_VALIDATE_EMAIL)) {
      return '';
    }
    $LDAP = new LDAPConnection();
    if ($response = $LDAP->getUIDFromMail($_email)) {
      return $response;
    }
    return '';
  }
}
