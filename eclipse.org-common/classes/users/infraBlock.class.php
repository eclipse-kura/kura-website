<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Matt Ward (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/

require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));
require_once(realpath(dirname(__FILE__) . "/../friends/friend.class.php"));
require_once('/home/data/httpd/eclipse-php-classes/system/dbconnection_infra_ro.class.php');


class InfraBlock {

  private $App = NULL;

  private $IsCommittter = NULL;

  private $Friend = NULL;

  private $Expiry = "";

  private $Subnet = "";

  /**
   * Constructor
   */
  function __construct() {

    global $App;
    $this->App = $App;
    $this->Session = $this->App->useSession();
    $this->Friend = $this->Session->getFriend();

    $this->IsCommitter = $this->Friend->getIsCommitter();

  }

  /**
   * isBlocked
   *
   * @ returns a bool indicating block status of the given IP.
   */
  public function isBlocked(){
    $rvalue = FALSE;
    $subnet = $_SERVER['REMOTE_ADDR'];
    //test if we have something that looks like an ip
    if ( preg_match('/[1-9]{1,3}\.(([0-9]{1,3}\.?){3})/', $subnet )) {
      if ($this->IsCommitter) {
        //strip last address chunk, and replace with 0
        $this->Subnet = substr_replace($subnet,'.0',strrpos($subnet,'.') );
        $sql = "SELECT /* USE MASTER */ COUNT(1) AS BlockCount,ExpiryDateTime FROM Attacks WHERE Subnet= " . $this->App->returnQuotedString($this->Subnet);
        $rs = $this->App->infra_sql($sql);
        $myrow = mysql_fetch_assoc($rs);
        if ($myrow['BlockCount'] > 0) {
          //stash the expiry time
          $this->Expiry = $myrow['ExpiryDateTime'];
          $rvalue = TRUE;
        }
      }
    }
    return $rvalue;
  }

  /**
   * getExpiry
   *
   * @ returns the value stashed in isBlocked.
   */
  public function getExpiry(){
    return $this->Expiry;
  }

  /**
   * whyBlocked
   *
   * @ returns a string which attempts to explain why you are blocked.
   */
  public function whyBlocked() {
    //by default someone else was doing something they shouldn't have
    $rvalue = " due to apparent abuse from your network. Contact webmaster@eclipse.org for asstisance, or wait for the block to expire.";
    //did you try to login?
    $sql = "SELECT /* USE MASTER */ COUNT(1) AS RecordCount, UserID FROM UserSSHTrustedSubnets WHERE Subnet= " . $this->App->returnQuotedString($this->Subnet) ." AND AuthReplyEmail IS NULL" ;
    $rs = $this->App->infra_sql($sql);
    $myrow = mysql_fetch_assoc($rs);
    if ($myrow['RecordCount'] > 0) {
      //caused by the same user?
      if (strcasecmp($myrow['UserID'], $this->Friend->getUID()) == 0) {
        $rvalue = " due to a login from a new network.  Please check your email for the unblock message.";
      } else {
        $rvalue = " due to another committers login from a new network.  Either wait for them to respond to the unlock mail or contact webmaster@eclipse.org .";
      }
    }
    return $rvalue;
  }

}
