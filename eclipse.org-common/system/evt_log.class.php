<?php
/**
 * *****************************************************************************
 * Copyright (c) 2004,2007,2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Denis Roy (Eclipse Foundation)- initial API and implementation
 * *****************************************************************************
 */

/**
 * Description: Functions and modules related to a modification log entry
 */

define('MAX_LOG_DAYS', 365);
$dblog = "/home/data/httpd/eclipse-php-classes/system/dbconnection_rw.class.php";

if (is_readable($dblog)) {
  require_once ("/home/data/httpd/eclipse-php-classes/system/dbconnection_rw.class.php");
  define('LOG_TO_DB', TRUE);
}
else {
  define('LOG_TO_DB', FALSE);
}

if (!class_exists("EvtLog")) {
  class EvtLog {

    var $LogID = 0;
    var $LogTable = "";
    var $PK1 = "";
    var $PK2 = "";
    var $LogAction = "";
    var $uid = "";
    var $EvtDateTime = "";

    /**
     * Fetch Log Records
     *
     *  $fields = array('LogAction' => 'mail_invalid', 'uid' => 'mmisingname0mv');
     *  print_r(EvtLog::fetchLogRecord($fields));
     *
     * @param array $fields
     */
    public static function fetchLogRecord($fields = array()) {
      $App = new App();
      $allowed_fields = array('LogID', 'LogTable', 'PK1', 'PK2', 'LogAction', 'uid', 'EvtDateTime');

      $sql = "SELECT LogID, LogTable, PK1, PK2, LogAction, uid, EvtDateTime FROM SYS_EvtLog";
      $conditions = array();
      foreach ($allowed_fields as $field) {
        if (isset($fields[$field]) && $fields[$field] != "") {
          $conditions[] = $field . " = " . $App->returnQuotedString($App->sqlSanitize($fields[$field]));
        }
      }

      if (!empty($conditions)) {
        $sql .= ' WHERE ';
        $sql .= implode(' and ', $conditions);
      }

      $sql .= " LIMIT 100";
      $result = $App->eclipse_sql($sql);
      $return = array();
      while ($row = mysql_fetch_assoc($result)) {
        $return[] = $row;
      }
      return $return;
    }

    function getLogID() {
      return $this->LogID;
    }

    function getLogTable() {
      return $this->LogTable;
    }

    function getPK1() {
      return $this->PK1;
    }

    function getPK2() {
      return $this->PK2;
    }

    function getLogAction() {
      return $this->LogAction;
    }

    function getuid() {
      return $this->PersonID;
    }

    function getEvtDateTime() {
      return $this->EvtDateTime;
    }

    function setLogID($_LogID) {
      $this->LogID = $_LogID;
    }

    function setLogTable($_LogTable) {
      $this->LogTable = $_LogTable;
    }

    function setPK1($_PK1) {
      $this->PK1 = $_PK1;
    }

    function setPK2($_PK2) {
      $this->PK2 = $_PK2;
    }

    function setLogAction($_LogAction) {
      $this->LogAction = $_LogAction;
    }

    function setuid($_uid) {
      $this->uid = $_uid;
    }

    function setEvtDateTime($_EvtDateTime) {
      $this->EvtDateTime = $_EvtDateTime;
    }

    function insertModLog($_uid) {
      $uid = $_uid;
      if (LOG_TO_DB) {
        // $var != "" is not a valid check here because we might want to log 0
        if ($this->getLogTable() !== "" && $this->getPK1() !== "" && $this->getLogAction() !== "" && $uid !== "") {
          $App = new App();
          $dbc = new DBConnectionRW();
          $dbh = $dbc->connect();

          $sql = "INSERT INTO SYS_EvtLog (
              LogID,
              LogTable,
              PK1,
              PK2,
              LogAction,
              uid,
              EvtDateTime)
            VALUES (
              NULL,
              " . $App->returnQuotedString($App->sqlSanitize($this->getLogTable(), $dbh)) . ",
              " . $App->returnQuotedString($App->sqlSanitize($this->getPK1(), $dbh)) . ",
              " . $App->returnQuotedString($App->sqlSanitize($this->getPK2(), $dbh)) . ",
              " . $App->returnQuotedString($App->sqlSanitize($this->getLogAction(), $dbh)) . ",
              " . $App->returnQuotedString($App->sqlSanitize($uid), $dbh) . ",
              NOW()
            )";

          mysql_query($sql, $dbh);
          if (mysql_error() != "") {
            echo "An unknown database error has occurred while logging information.  Please contact the System Administrator.";
            echo mysql_error();
            exit();
          }

          $dbc->disconnect();

          // 1% of each hits will perform clean up
          if (rand(0, 100) < 1) {
            $this->cleanup();
          }
        }
        else {
          echo "An unknown system error has occurred while logging information.  Please contact the System Administrator.";
          exit();
        }
      }
      else {
        // TODO: local logging
      }
    }

    function cleanup() {
      $sql = "DELETE FROM SYS_EvtLog WHERE EvtDateTime < " . MAX_LOG_DAYS;

      $dbc = new DBConnectionRW();
      $dbh = $dbc->connect();
      mysql_query($sql, $dbh);
      $dbc->disconnect();
    }

  }
}