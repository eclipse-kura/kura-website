<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *    Chrisotpher Guindon (Eclipse Foundation)
 *******************************************************************************/
require_once("webmaster.class.php");

class Firewall extends Webmaster {

  private $search_results = NULL;

  private $all_blocks = NULL;

  private $recent_blocks = NULL;

  private $period = "12";

  public function __construct(App $App){
    parent::__construct($App);
    if ($this->getFormName() === 'webmaster-firewall') {
      // Checking the page state
      switch ($this->getState()) {
        case 'change_recent_blocks_period':
          $this->period = filter_var($this->App->getHTTPParameter('period', 'POST'), FILTER_SANITIZE_STRING);
          break;
        case 'insert_block':
          $this->_insertBlock();
          break;
        case 'delete_block':
          $this->_deleteBlock();
          break;
        case 'search_block':
          $this->getSearchResults();
          break;
      }
    }
  }

  /**
   * This function gets the recent blocks
   *
   * @return array
   * */
  public function getRecentBlocks() {
    if (is_null($this->recent_blocks)) {
      $this->recent_blocks = $this->_fetchBlocks($this->period);
    }
    return $this->recent_blocks;
  }

  /**
   * This function gets all the blocks from the Attacks table
   *
   * @return array
   * */
  public function getAllBlocks() {
    if (is_null($this->all_blocks)) {
      $this->all_blocks = $this->_fetchBlocks();
    }
    return $this->all_blocks;
  }

  /**
   * This function gets all the search results from the Attacks table
   *
   * @return array
   * */
  public function getSearchResults() {
    if (is_null($this->search_results) && $this->getState() === 'search_block') {
      $this->_searchBlocks();
    }
    return $this->search_results;
  }

  /**
   * This function deletes a blocked row from the Attacks table
   * */
  private function _deleteBlock() {
    $subnet = filter_var($this->App->getHTTPParameter('subnet_to_delete', 'POST'), FILTER_SANITIZE_STRING);
    if ($this->_isValidIp($subnet)) {
      $sql = "DELETE FROM Attacks
              WHERE Subnet = " . $this->App->returnQuotedString($this->App->sqlSanitize($subnet));
      $delete = $this->App->infra_sql($sql);
      $this->_updateStats($subnet,'0');
      $this->App->setSystemMessage('delete_block','You have successfully deleted the blocked subnet: ' . $subnet . '.', 'success');
      return TRUE;
    }
    $this->App->setSystemMessage('delete_block','There was a problem blocking the subnet: ' . $subnet . '.', 'danger');
    return FALSE;
  }

  /**
   * This function updates the Stats table
   *
   * @param $subnet contains a string
   * @param $blocking contains a string of either 1 or 0
   * */
  private function _updateStats($subnet, $blocking_option) {
    if ($this->_isValidIp($subnet)) {

      // By default we assume we're inserting a block
      $blocking_where = "0";
      $count = "";

      // But if we're deleting a block
      if ($blocking_option === "0") {
        $blocking_where = "1";
        $count = ", Count = (Count-1)";
      }
      $sql = "UPDATE Stats
              SET Blocking = ". $this->App->sqlSanitize($blocking_option) . $this->App->sqlSanitize($count) . "
              WHERE Blocking = ". $this->App->sqlSanitize($blocking_where) ."
              AND Subnet = " . $this->App->returnQuotedString($this->App->sqlSanitize($subnet));
      $update = $this->App->infra_sql($sql);
    }
  }

  /**
   * This function fetches recent blocks based on a number of hours
   *
   * @param $period
   *
   * @return array
   * */
  private function _fetchBlocks($period = "") {
    if (empty($period)) {
      $this->App->setSystemMessage('fetch_blocks','Please select a period of time.', 'danger');
      return;
    }
    $sql = "SELECT * FROM Attacks";
    if ($period != "") {
      $sql .= " WHERE AttackDateTime > DATE_SUB(NOW(), INTERVAL " . $this->App->sqlSanitize($period) . " HOUR)";
    }
    $sql .= " ORDER BY AttackDateTime DESC";
    $result = $this->App->infra_sql($sql);

    $recent_blocks = array();
    while ($row = mysql_fetch_array($result)) {
      $recent_blocks[] = $row;
    }
    if (empty($recent_blocks)) {
      return "There weren't any blocks whitin the past <strong>". $period ." hours</strong>.";
    }

    return $recent_blocks;
  }

  /**
   * This function validates an IP addresses or Subnets
   *
   * @return bool
   * */
  private function _isValidIp($ip) {
    if (!empty($ip) && $ip != '0.0.0.0'){
      if (preg_match("/^[1-9][0-9]{0,2}(\.[0-9]{1,3}){3}$/",$ip) == 1) {
        return TRUE;
      }
    }
    $msg = "The IP " . $ip." is not valid.";
    if (empty($ip)) {
      $msg = "You need to enter an IP address.";
    }
    $this->App->setSystemMessage('validate_ip',$msg,"danger");
    return FALSE;
  }

  /**
   * This function inserts or update attacking ip in the attacks table
   *
   */
  private function _insertBlock() {
    $ip = filter_var($this->App->getHTTPParameter('insert_block_ip', 'POST'), FILTER_SANITIZE_STRING);
    $time = strtoupper(str_replace('_', ' ', filter_var($this->App->getHTTPParameter('insert_block_time', 'POST'), FILTER_SANITIZE_STRING)));
    $port = filter_var($this->App->getHTTPParameter('insert_block_port', 'POST'), FILTER_SANITIZE_STRING);
    if ($port != "22") {
      $port = "0";
    }


    if (empty($time) && !filter_var($time, FILTER_SANITIZE_STRING)) {
      $msg_type = "danger";
      $msg = "Please select a valid amount of time for the ip to be blocked.";
    }

    // Making sure the ip is valid
    if ($this->_isValidIp($ip) && !(isset($msg_type) && $msg_type == 'danger')) {

      // getting the subnet
      $exploded_ip = explode('.',$ip);
      $subnet = $exploded_ip[0].".".$exploded_ip[1].".".$exploded_ip[2].".0";

      $sql = "INSERT INTO Attacks
              (AttackingIp,Subnet,Port,AttackDateTime,ExpiryDateTime,UserID,VictimNode)
              VALUES (
                ".$this->App->returnQuotedString($this->App->sqlSanitize($ip)).",
                ".$this->App->returnQuotedString($this->App->sqlSanitize($subnet)).",
                " . $this->App->returnQuotedString($this->App->sqlSanitize($port)) .",
                NOW(),
                DATE_ADD(NOW(), INTERVAL ". $this->App->sqlSanitize($time) ."),
                'Webmaster',
                'Portal'
              )
              ON DUPLICATE KEY
              UPDATE
                AttackDateTime = NOW(),
                ExpiryDateTime = DATE_ADD(NOW(), INTERVAL ". $this->App->sqlSanitize($time) .")";

      $insert = $this->App->infra_sql($sql);

      $this->_updateStats($subnet, '1');

      $msg_type = "success";
      $msg = "You have successfully blocked <strong>" . $ip . "</strong> for <strong>" . $time . "</strong>.";
    }
    $this->App->setSystemMessage('insert_block', $msg, $msg_type);
  }

  /**
   * This function returns an array of blocked IP addresses or Subnets
   *
   * @return array
   * */
  private function _searchBlocks() {
    $ip = filter_var($this->App->getHTTPParameter('search_block_ip', 'POST'), FILTER_SANITIZE_STRING);
    $search_results = array();

    if ($this->_isValidIp($ip)) {
      $sql = "SELECT DISTINCT
              Subnet,Port,UserID,VictimNode,AttackDateTime,ExpiryDateTime
              FROM Attacks
              WHERE (Subnet = " . $this->App->returnQuotedString($this->App->sqlSanitize($ip)) . "
              OR AttackingIp = " . $this->App->returnQuotedString($this->App->sqlSanitize($ip)) . ")
              ORDER BY AttackDateTime DESC";
      $result = $this->App->infra_sql($sql);

      while ($row = mysql_fetch_array($result)) {
        $search_results[] = $row;
      }
    }

    $this->search_results = $search_results;
    return $search_results;
  }

}