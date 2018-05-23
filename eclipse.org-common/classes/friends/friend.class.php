<?php
/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

class Friend {

  private $friend_id  = 0;

  private $bugzilla_id = 0;

  private $first_name = "";

  private $last_name = "";

  private $date_joined = NULL;

  private $benefit_expires = NULL;

  private $is_anonymous = 0;

  private $is_benefit = 0;

  private $uid = NULL;

  private $email = "";

  ## FORMAT: ::XX::  where XX is a Foundation role (CM, PL, PM, etc)
  private $roles = "";

  ## Concatenate for multiples: ::CM::::PL::::PM::
  private $dn = "";

  private $table_prefix = '';

  public function __construct($testmode = FALSE) {
    if ($testmode === TRUE){
      $this->table_prefix = 'testing_';
    }
  }

  function getFriendID() {
    return $this->friend_id;
  }

  function getBugzillaID() {
    return $this->bugzilla_id;
  }

  function getFirstName() {
    return $this->first_name;
  }

  function getLastName() {
    return $this->last_name;
  }

  function getDateJoined() {
    return $this->date_joined;
  }

  function getIsAnonymous() {
    return $this->is_anonymous;
  }

  function getIsBenefit() {
    return $this->is_benefit;
  }

  /**
   * Get Friend LDAP UID.
   *
   * @return string
   */
  function getLDAPUID() {
    // This might be empty if the user
    // never made a donation and he his
    // missing from the friends table.
    if (empty($this->uid)) {
      $this->getUID();
    }
    return $this->uid;
  }

  function getEmail() {
    return $this->email;
  }

  function getBenefitExpires() {
    return $this->benefit_expires;
  }

  private function getRoles() {

    if ( $this->roles === "" ){
      $App= new App();
      # Get user roles
      # Committer
      $sql = "SELECT /* friend.class.php authenticate */ COUNT(1) AS RecordCount FROM PeopleProjects AS PRJ
      INNER JOIN People AS P ON P.PersonID = PRJ.PersonID
      WHERE P.EMail = '$this->email' AND PRJ.Relation = 'CM'
      AND (LEFT(PRJ.InactiveDate,10) = '0000-00-00' OR PRJ.InactiveDate IS NULL OR PRJ.InactiveDate > NOW())";

        $result = $App->foundation_sql($sql);
      if($result && mysql_num_rows($result) > 0) {
        $myrow = mysql_fetch_assoc($result);
        if($myrow['RecordCount'] > 0) {
        $this->roles .= "::CM::";
      }
      }
    }
    return $this->roles;
  }

  function getDn() {
    return $this->dn;
  }

  function setFriendID($_friend_id) {
    $this->friend_id = $_friend_id;
  }

  function setBugzillaID($_bugzilla_id) {
    if (ctype_digit($_bugzilla_id)) {
      $this->bugzilla_id = $_bugzilla_id;
    }
  }

  function setFirstName($_first_name) {
    $this->first_name = $_first_name;
  }

  function setLastName($_last_name) {
    $this->last_name = $_last_name;
  }

  function setDateJoined($_date_joined) {
    $this->date_joined = $_date_joined;
  }

  function setIsAnonymous($_is_anonymous) {
    $this->is_anonymous = $_is_anonymous;
  }

  function setIsBenefit($_is_benefit) {
    $this->is_benefit = $_is_benefit;
  }

  function setLDAPUID($_uid) {
    $this->uid = $_uid;
  }

  function setEmail($_email) {
    $this->email = $_email;
  }

  function setBenefitExpires($_benefit_expires) {
    $this->benefit_expires = $_benefit_expires;
  }

  private function setRoles($_roles) {
    $this->roles = $_roles;
  }

  function setDn($_dn) {
    $this->dn = $_dn;
  }

  /**
   * Get LDAP UID
   *
   * This was deprecated until it was discovered
   * that a user might not have an entry in the
   * friends database.
   *
   * @return string
   */
  function getUID() {
    if ($this->dn != "") {
      if (preg_match('/uid=(.*),ou=/', $this->dn, $matches)) {
        $this->setLDAPUID($matches[1]);
        return $matches[1];
      }
    }
    return FALSE;
  }

  /**
   * getIsCommitter() - return committer status
   * @see authenticate()
   * @return bool user is a committer
   */
  function getIsCommitter() {
    $rValue = false;
    if(preg_match('/ou=people,/i', $this->getDn())) {
      if(strlen($this->roles) == 0) {
        $this->setRoles("::CM::");
      }
      $rValue = true;
    }
    return $rValue;
  }

  /**
   * Returns a list of Friend objects that have donated more than $100
   * @param $offset int The result list offset
   * @param $num int The number of results to retrieve
   * @param $get_anonymous boolean Whether or not to return anonymous friends
   */
  function getBestFriends($offset=0, $num=99999, $get_anonymous=TRUE) {
    $bestFriends = array();
    $App = new App();
    $count = $offset + $num;
    $sql = "SELECT f.friend_id, f.is_anonymous, fc.amount
            FROM " . $this->table_prefix . "friends as f
            INNER JOIN " . $this->table_prefix . "friends_contributions fc
            ON (fc.friend_id = f.friend_id AND date_expired > DATE_SUB(NOW(), INTERVAL 1 YEAR) AND fc.amount >= 100) ";
    if (!$get_anonymous) $sql .= "AND f.is_anonymous = 0 ";
    $sql .= "LIMIT $offset,$count";
    $result = $App->eclipse_sql($sql);
    while ($myrow = mysql_fetch_assoc($result)) {
        $newFriend = new Friend();
        $newFriend->selectFriend($myrow['friend_id']);
        $bestFriends[] = $newFriend;
    }
    return $bestFriends;
  }

  function insertUpdateFriend() {
    $retVal = 0;

    $App = new App();
    #$ModLog = new ModLog();
    #$ModLog->setLogTable("Person");
    #$ModLog->setPK1($this->getPersonID());

    if ($this->date_joined == NULL)
      $default_date_joined = "NOW()";
    else
      $default_date_joined = $App->returnQuotedString($this->date_joined);

    if($this->selectFriendID("friend_id", $this->getFriendID())) {
      # update
      $sql = "UPDATE " . $this->table_prefix . "friends SET
            bugzilla_id = " . $App->returnQuotedString($App->sqlSanitize($this->getBugzillaID())) . ",
            first_name = " . $App->returnQuotedString($App->sqlSanitize($this->getFirstName())) . ",
            last_name = " . $App->returnQuotedString($App->sqlSanitize($this->getLastName())) . ",
            date_joined = " . $default_date_joined . ",
            is_anonymous = " . $App->returnQuotedString($App->sqlSanitize($this->getIsAnonymous())) . ",
            is_benefit = " . $App->returnQuotedString($App->sqlSanitize($this->getIsBenefit())) . ",
            uid = " . $App->returnQuotedString($App->sqlSanitize($this->getLDAPUID())) . "
          WHERE
            friend_id = " . $App->sqlSanitize($this->getFriendID());

        $App->eclipse_sql($sql);
        $retVal = $this->friend_id;
    }
    else {
      # insert
      $sql = "INSERT INTO " . $this->table_prefix . "friends (
            bugzilla_id,
            first_name,
            last_name,
            date_joined,
            is_anonymous,
            is_benefit,
            uid)
          VALUES (
            " . $App->returnQuotedString($this->getBugzillaID()) . ",
            " . $App->returnQuotedString($this->getFirstName()) . ",
            " . $App->returnQuotedString($this->getLastName()) . ",
            " . $default_date_joined . ",
            " . $App->returnQuotedString($this->getIsAnonymous()) . ",
            " . $App->returnQuotedString($this->getIsBenefit()) . ",
            " . $App->returnQuotedString($this->getLDAPUID()) . ")";
      $App->eclipse_sql($sql);
      $retVal = mysql_insert_id();
      $this->setFriendID($retVal);
    }
    return $retVal;
  }

  function selectFriend($_friend_id) {
    if($_friend_id != "") {
      $App = new App();
      $_friend_id = $App->sqlSanitize($_friend_id);

      $sql = "SELECT /* USE MASTER */ f.friend_id, f.bugzilla_id, f.first_name,
                    f.last_name, f.date_joined, f.is_anonymous, f.is_benefit, f.uid,
          fc_temp.date_expired
          FROM " . $this->table_prefix . "friends as f
          LEFT JOIN (SELECT friend_id, MAX(date_expired) AS date_expired FROM " . $this->table_prefix . "friends_contributions GROUP BY friend_id) fc_temp
            ON fc_temp.friend_id = f.friend_id
          WHERE f.friend_id = " . $App->returnQuotedString($_friend_id);
      $result = $App->eclipse_sql($sql);

      if ($myrow = mysql_fetch_array($result))  {
        $this->setFriendID    ($myrow["friend_id"]);
        $this->setBugzillaID  ($myrow["bugzilla_id"]);
        $this->setFirstName    ($myrow["first_name"]);
        $this->setLastName    ($myrow["last_name"]);
        $this->setDateJoined  ($myrow["date_joined"]);
        $this->setIsAnonymous  ($myrow["is_anonymous"]);
        $this->setIsBenefit    ($myrow["is_benefit"]);
        $this->setLDAPUID           ($myrow["uid"]);
        $this->setBenefitExpires($myrow["date_expired"]);
        $this->getRoles();
        return TRUE;
      }
    }
    return FALSE;
  }

  function selectFriendID($_fieldname, $_searchfor) {
    $retVal = 0;

    if( ($_fieldname != "") && ($_searchfor != "")) {
      $App = new App();
      $_fieldname = $App->sqlSanitize($_fieldname, null);
      $_searchfor = $App->sqlSanitize($_searchfor, null);

      $sql = "SELECT /* USE MASTER */ friend_id
          FROM " . $this->table_prefix . "friends
          WHERE $_fieldname = " . $App->returnQuotedString($_searchfor);

      $result = $App->eclipse_sql($sql);
      if ($result){
        $myrow = mysql_fetch_array($result);
        $retVal = $myrow['friend_id'];
      }
    }
    return $retVal;
  }

  function getBugzillaIDFromEmail($_email, $use_master=false) {
    $result = 0;
    if($_email != "") {
      $App = new App();
      $_email = $App->sqlSanitize($_email);
                if ($use_master) {
          $sql = "SELECT /* USE MASTER */ userid FROM profiles WHERE login_name = " . $App->returnQuotedString($_email);
                } else {
          $sql = "SELECT userid FROM profiles WHERE login_name = " . $App->returnQuotedString($_email);
                }
      $result = $App->bugzilla_sql($sql);
      $myrow = mysql_fetch_array($result);
      $result = $myrow['userid'];
    }
    return $result;
  }

  /**
   * authenticate() - Authenticate user using bugzilla credentials
   *
   * @author droy
   * @param string Email address
   * @param string password
   * @return boolean - auth was successful or not
   * @since 2007-11-20
   * @deprecated Use site_login instead, which uses LDAP for everyone
   *
   * 2009-08-27: Added code for crypt/sha-256 passes
   *
   */
  function authenticate($email, $password) {

    $rValue = false;

    $validPaths = array(
      "/home/data/httpd/dev.eclipse.org/html/site_login/"
    );
    $App = new App();
    if($email != "" && $password != "" && ($App->isValidCaller($validPaths) || $App->devmode)) {

      //check if magic quotes is 'off'. If it's on then the sanitizer will extra escape
      //the adress which results in valid accounts being rejected.
      if(!get_magic_quotes_gpc()) {
        $email          = $App->sqlSanitize($email, null);
      }
      else {
        $password = stripslashes($password);  # 359128 - password didn't work with \
      }

      $sql = "SELECT userid, login_name,
            LEFT(realname, @loc:=LENGTH(realname) - LOCATE(' ', REVERSE(realname))) AS first_name,
            SUBSTR(realname, @loc+2) AS last_name,
            cryptpassword
        FROM profiles WHERE login_name = '$email' AND disabledtext = ''";
      $result = $App->bugzilla_sql($sql);

      if($result && mysql_num_rows($result) > 0) {
        $myrow         = mysql_fetch_assoc($result);
        $db_cryptpassword   = $myrow['cryptpassword'];
        $pw         = "abc12345";  // never allow db == pw by default

        # check password
        if(preg_match("/{([^}]+)}$/", $db_cryptpassword, $matches)) {
          $hash = $matches[0];
          $salt = substr($db_cryptpassword,0,8);
          if(function_exists('mhash')) {
            $pw = $salt . str_replace("=", "", base64_encode(mhash(MHASH_SHA256,$password . $salt))) . $hash;
          }
          else {
            $pw = $salt . str_replace("=", "", base64_encode(hash("sha256",$password . $salt, true))) . $hash;
          }
        }
        else {
          $pw = crypt($password, $db_cryptpassword);
        }

        if($db_cryptpassword == $pw) {
            $rValue = true;

          $this->setBugzillaID($myrow['userid']);
          $this->setEmail($myrow['login_name']);

          # Load up the rest of the Friend record
          $friend_id = $this->selectFriendID("bugzilla_id", $this->getBugzillaID());
          if($friend_id > 0) {
            $this->selectFriend($friend_id);
          }

          # Override the friend record with (known good) Bugzilla info
          $this->setFirstName($myrow['first_name']);
          $this->setLastName($myrow['last_name']);

        }
      }
    }
    return $rValue;
  }

  /**
   * Verify if our Friend is a Foundation Staff.
   *
   * @return boolean
   */
  public function checkUserIsFoundationStaff() {
    return $this->_checkUserInGroup('www-auth');
  }

  /**
   * Verify if our Friend is a Webmaster.
   *
   * @return boolean
   */
  public function checkUserIsWebmaster() {
    return $this->_checkUserInGroup('admins');
  }

  /**
   * Verify if a user is in a group
   *
   * A group name might change in the future,
   * we will create a public function for each
   * group we need to verify instead of using this
   * function directly.
   *
   * For example,
   * checkUserIsFoundationStaff().
   *
   * @param string $group
   */
  private function _checkUserInGroup($group = '') {
    $group = filter_var($group, FILTER_SANITIZE_STRING);
    $ldap_uid = $this->getLDAPUID();
    if (empty($ldap_uid)) {
      return FALSE;
    }
    require_once("/home/data/httpd/eclipse-php-classes/system/ldapconnection.class.php");

    $Ldap = new LDAPConnection();
    if ($Ldap->checkUserInGroup($ldap_uid, $group)) {
      return TRUE;
    }
    return FALSE;
  }
}