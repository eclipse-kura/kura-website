<?php
/*******************************************************************************
 * Copyright (c) 2007-2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Nathan Gervais (Eclipse Foundation)- initial API and implementation
 *    Denis Roy (Eclipse Foundation)
 *******************************************************************************/
require_once(realpath(dirname(__FILE__) . "/../../system/smartconnection.class.php"));
require_once("friendsContributions.class.php");

class FriendsContributionsList {

  var $list = array();

  private $table_prefix = "";

  public function __construct($testmode = FALSE) {
    if ($testmode === TRUE){
      $this->table_prefix = 'testing_';
    }
  }

  function getList() {
    return $this->list;
  }

  function setList($_list) {
    $this->list = $_list;
  }

  function add($_contribution) {
    $this->list[count($this->list)] = $_contribution;
  }

  function getCount() {
    return count($this->list);
  }

  function getItemAt($_pos) {
    if($_pos < $this->getCount()) {
      return $this->list[$_pos];
    }
  }

  function selectFriendsContributionsList($_start = -1, $_numrows = -1, $_where=NULL) {
    $App = new App();
    # the IF() for date_expired is a bad hack to accommodate the donor list, should we decide to extend all our friends by one month
    $sql = "SELECT DISTINCT /* selectFriendsContributionsList */
          F.first_name,
          F.last_name,
          F.bugzilla_id,
          F.is_anonymous,
          F.is_benefit,
          F.uid,
          F.date_joined,
          FC.friend_id,
          IF(FC.date_expired > DATE_ADD(NOW(), INTERVAL 1 YEAR), DATE_SUB(FC.date_expired, INTERVAL 1 MONTH), FC.date_expired) AS date_expired,
          FC.contribution_id,
          FC.transaction_id,
          FC.currency,
          FC.process_id,
          FC.amount,
          FC.message,
          UP.user_mail
          FROM " . $this->table_prefix . "friends_contributions as FC
          LEFT JOIN " . $this->table_prefix . "friends as F on FC.friend_id = F.friend_id
          LEFT JOIN users_profiles as UP on (F.uid = UP.user_uid AND F.uid != '')";
    if ($_where != NULL) {
      $sql .= " " . $_where;
    }
        $sql .= " ORDER by FC.contribution_id DESC";
    if ($_start >= 0)
    {
    $sql .= " LIMIT $_start";
    if ($_numrows > 0)
      $sql .= ", $_numrows";
    }

    $App->sqlSanitize($sql);
    $result = $App->eclipse_sql($sql);

    while($myrow = mysql_fetch_array($result)) {
      $Friend = new Friend();
      $Friend->setFriendID($myrow['friend_id']);
      $Friend->setBugzillaID($myrow['bugzilla_id']);
      $Friend->setDateJoined($myrow['date_joined']);
      $Friend->setFirstName($myrow['first_name']);
      $Friend->setLastName($myrow['last_name']);
      $Friend->setIsAnonymous($myrow['is_anonymous']);
      $Friend->setIsBenefit($myrow['is_benefit']);
      $Friend->setLDAPUID($myrow['uid']);
      $Friend->setEmail($myrow['user_mail']);

      $Contribution = new Contribution();
      $Contribution->setFriendID($myrow['friend_id']);
      $Contribution->setContributionID($myrow['contribution_id']);
      $Contribution->setDateExpired($myrow['date_expired']);
      $Contribution->setMessage($myrow['message']);
      $Contribution->setAmount($myrow['amount']);
      $Contribution->setCurrency($myrow['currency']);
      $Contribution->setProcessId($myrow['process_id']);
      $Contribution->setTransactionID($myrow['transaction_id']);

      $FriendsContributions = new FriendsContributions();
      $FriendsContributions->setFriendID($myrow['friend_id']);
      $FriendsContributions->setContributionID($myrow['contribution_id']);
      $FriendsContributions->setFriendObject($Friend);
      $FriendsContributions->setContributionObject($Contribution);

      $this->add($FriendsContributions);
    }

    $result = null;
    $myrow  = null;
  }
}