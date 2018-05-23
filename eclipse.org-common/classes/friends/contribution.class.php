<?php
/*******************************************************************************
 * Copyright (c) 2007, 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Nathan Gervais (Eclipse Foundation)- initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class Contribution {

  private $friend_id = "";

  private $contribution_id = "";

  private $date_expired = NULL;

  private $amount = "";

  private $message = "";

  private $transaction_id = "";

  private $currency = NULL;

  private $process_id = NULL;

  private $table_prefix = '';

  public function __construct($testmode = FALSE) {
    if ($testmode === TRUE){
      $this->table_prefix = 'testing_';
    }
  }

  function getFriendID() {
    return $this->friend_id;
  }

  function getContributionID() {
    return $this->contribution_id;
  }

  function getDateExpired() {
    return $this->date_expired;
  }

  function getAmount() {
    return $this->amount;
  }

  function getMessage() {
    return $this->message;
  }

  function getTransactionID() {
    return $this->transaction_id;
  }

  function getCurrency() {
    return $this->currency;
  }

  function getProcessId() {
    return $this->process_id;
  }

  function setFriendID($_friend_id){
    $this->friend_id = $_friend_id;
  }

  function setContributionID($_contribution_id){
    $this->contribution_id = $_contribution_id;
  }

  function setDateExpired($_date_expired){
    $this->date_expired = $_date_expired;
  }

  function setAmount($_amount){
    $this->amount = $_amount;
  }

  function setMessage($_message){
    $this->message = $_message;
  }

  function setTransactionID($_transaction_id){
    $this->transaction_id = $_transaction_id;
  }

  function setCurrency($currency = NULL) {
    $this->currency = $currency;
  }

  function setProcessId($id = NULL) {
    $this->process_id = $id;
  }

  function insertContribution(){
    $result = 0;
    $App = new App();

    if ($this->selectContributionExists($this->getTransactionID())){
      $result = -1;
    }
    else {
      if ($this->date_expired == NULL) {
        $default_date_expired = "DATE_ADD(NOW(), INTERVAL 1 YEAR)";
      }
      else {
        $default_date_expired = $App->returnQuotedString($App->sqlSanitize($this->date_expired));
      }
      # insert
      $sql = "INSERT INTO " . $this->table_prefix . "friends_contributions (
          friend_id,
          contribution_id,
          date_expired,
          amount,
          message,
          transaction_id,
          currency,
          process_id
        )
          VALUES (
          " . $App->returnQuotedString($App->sqlSanitize($this->getFriendID())) . ",
          " . $App->returnQuotedString($App->sqlSanitize($this->getContributionID())) . ",
          " . $default_date_expired . ",
          " . $App->returnQuotedString($App->sqlSanitize($this->getAmount())) . ",
          " . $App->returnQuotedString($App->sqlSanitize($this->getMessage())) . ",
          " . $App->returnQuotedString($App->sqlSanitize($this->getTransactionID())) . ",
          " . $App->returnQuotedString($App->sqlSanitize($this->getCurrency())) . ",
          " . $App->returnQuotedString($App->sqlSanitize($this->getProcessId())) .
          ")";
      return $App->eclipse_sql($sql);
    }
    return $result;
  }

    function updateContribution() {
    $result = 0;
    $App = new App();
    if ($this->selectContributionExists($this->getTransactionID())){
      $default_date_expired = $App->returnQuotedString($App->sqlSanitize($this->date_expired));
      $sql = "UPDATE " . $this->table_prefix . "friends_contributions SET
          friend_id = " . $App->returnQuotedString($App->sqlSanitize($this->getFriendID())) . ",
          date_expired = " . $default_date_expired . ",
          amount = " . $App->returnQuotedString($App->sqlSanitize($this->getAmount())) . ",
          message = " . $App->returnQuotedString($App->sqlSanitize($this->getMessage())) . ",
          transaction_id = " . $App->returnQuotedString($App->sqlSanitize($this->getTransactionID())) . ",
          currency = " . $App->returnQuotedString($App->sqlSanitize($this->getCurrency())) . ",
          process_id = " . $App->returnQuotedString($App->sqlSanitize($this->getProcessId())) . "
          WHERE contribution_id = " . $App->returnQuotedString($App->sqlSanitize($this->getContributionID()));
      return $App->eclipse_sql($sql);
    }
    else {
      $result = -1;
    }
    return $result;
  }

  function selectContributionExists($_transaction_id){
    $retVal = FALSE;
    if ($_transaction_id != "") {
      $App = new App();

      $sql = "SELECT /* USE MASTER */ transaction_id
          FROM " . $this->table_prefix . "friends_contributions
          WHERE transaction_id = " . $App->returnQuotedString($App->sqlSanitize($_transaction_id));

      $result = $App->eclipse_sql($sql);
      if ($result) {
        $myrow = mysql_fetch_array($result);
        if ($myrow['transaction_id'] == $_transaction_id) {
          $retVal = TRUE;
        }
      }
    }
    return $retVal;
  }

  function selectContribution($_contribution_id) {
    if ($_contribution_id != "")  {
      $App = new App();

      $sql = "SELECT /* USE MASTER */ friend_id,
              contribution_id,
              date_expired,
              amount,
              message,
              transaction_id,
              currency,
              process_id
          FROM " . $this->table_prefix . "friends_contributions
          WHERE contribution_id = " . $App->returnQuotedString($App->sqlSanitize($_contribution_id));

      $result = $App->eclipse_sql($sql);

      if ($myrow = mysql_fetch_array($result))  {
        $this->setFriendID($myrow["friend_id"]);
        $this->setContributionID($myrow["contribution_id"]);
        $this->setDateExpired($myrow["date_expired"]);
        $this->setAmount($myrow["amount"]);
        $this->setMessage($myrow["message"]);
        $this->setTransactionID($myrow["transaction_id"]);
        $this->setCurrency($myrow["currency"]);
        $this->setProcessId($myrow["process_id"]);
      }
    }
  }

  function selectContributionWithTransaction($_transaction_id)
  {
    if($_transaction_id != "")  {
      $App = new App();

      $sql = "SELECT /* USE MASTER */ friend_id,
              contribution_id,
              date_expired,
              amount,
              message,
              transaction_id,
              currency,
              process_id
          FROM " . $this->table_prefix . "friends_contributions
          WHERE transaction_id = " . $App->returnQuotedString($App->sqlSanitize($_transaction_id));

      $result = $App->eclipse_sql($sql);

      if ($myrow = mysql_fetch_array($result))  {
        $this->setFriendID($myrow["friend_id"]);
        $this->setContributionID($myrow["contribution_id"]);
        $this->setDateExpired($myrow["date_expired"]);
        $this->setAmount($myrow["amount"]);
        $this->setMessage($myrow["message"]);
        $this->setTransactionID($myrow["transaction_id"]);
        $this->setCurrency($myrow["currency"]);
        $this->setProcessId($myrow["process_id"]);
      }
    }
  }
}
