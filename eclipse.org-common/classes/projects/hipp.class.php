<?php
/*******************************************************************************
 * Copyright (c) 2014-2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Denis Roy (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class Hipp {

  #*****************************************************************************
  #
  # hipp.class.php
  #
  # Author:   Denis Roy
  # Date:    2013-11-06
  #
  # Description: Functions and modules related to Hudson HIPP
  #
  # HISTORY:
  #
  # mysql> select * from ProjectServices;
  # +-----+-------------------+-------------+----------------+------------------------+--------------------+------------+----------+-----------+---------+
  # | ID  | ProjectID         | ServiceType | ServiceVersion | ServicePreviousVersion | ServerHost         | ServerPort | XvncBase | OtherData | State   |
  # +-----+-------------------+-------------+----------------+------------------------+--------------------+------------+----------+-----------+---------+
  # |   1 | technology.babel  | hipp        | 3.2.2          | 3.0.1-b2               | hipp6.eclipse.org  |       8215 |      360 | NULL      | running |
  #
  #*****************************************************************************

  var $ID = 0;

  var $ProjectID = "";

  var $ServiceType  = "";

  var $ServiceVersion = "";

  var $ServicePreviousVersion = "";

  var $ServiceLatestVersion = "";

  var $ServerHost = "";

  var $ServerPort = 0;

  var $XvncBase = 0;

  var $OtherData = "";

  var $State = "";

  # Path to HIPP images
  private $HIPPImagePath = "/opt/public/hipp/homes/hudson-wars";

  private $VersionRegexp = '/[^\w\.-]/';

  /**
   * Create HTML for hipp control panel link
   *
   * @param string $id
   *   Project id (technology.cbi)
   *
   * @param string $shortname
   *   Project shortname (cbi)
   *
   * @return string
   */
  public function getControlLink($id, $shortname){
    $state = $this->getState();
    $str = "<span id='" . $shortname . "_state' class='" . $state . "'>" . ucfirst($state) . "</span> &#160; <span id='" . $shortname . "_instance'>";

    # Examine Service status to determine control knobs to place
    if ($state == "running") {
      # Add RESTART button
      $str .= '<a title="restart" href="#ct" data-action="restart" data-projectid="' . $id . '" data-shortname="' . $shortname . '" class="hipp-control-action-link" ><i class="fa fa-refresh"></i></a>';
    }

    if ($state == "stopped") {
      # Add START button
      $str .= '<a title="start"  href="#ct" data-action="start" data-projectid="' . $id . '" data-shortname="' . $shortname . '" class="hipp-control-action-link" ><i class="fa fa-power-off"></i></a>';
    }

    return $str . "</span>";
  }

  function getID() {
    return $this->ID;
  }

  function getProjectID() {
    return $this->ProjectID;
  }

  function getServiceType() {
    return $this->ServiceType;
  }

  function getServiceVersion() {
    return $this->ServiceVersion;
  }

  function getServicePreviousVersion() {
    return $this->ServicePreviousVersion;
  }

  function getServiceLatestVersion() {
    return $this->ServiceLatestVersion;
  }
  function getServerHost() {
    return $this->ServerHost;
  }

  function getServerPort() {
    return $this->ServerPort;
  }

  function getXvncBase() {
    return $this->XvncBase;
  }

  function getOtherData() {
    return $this->OtherData;
  }

  function getState() {
    return $this->State;
  }

  function setID($_ID) {
    $this->ID = $_ID;
  }

  function setProjectID($_ProjectID) {
    $this->ProjectID = $_ProjectID;
  }

  function setServiceType($_ServiceType) {
    $this->ServiceType = $_ServiceType;
  }

  function setServiceVersion($_ServiceVersion) {
    $this->ServiceVersion = $_ServiceVersion;
  }

  function setServicePreviousVersion($_ServicePreviousVersion) {
    $this->ServicePreviousVersion = $_ServicePreviousVersion;
  }

  function setServiceLatestVersion($_ServiceLatestVersion) {
    # this regexp also in admintools.git:create_hipp
    $this->ServiceLatestVersion = preg_replace($this->VersionRegexp, '', $_ServiceLatestVersion);
  }

  function setServerHost($_ServerHost) {
    $this->ServerHost = $_ServerHost;
  }

  function setServerPort($_ServerPort) {
    $this->ServerPort = $_ServerPort;
  }

  function setXvncBase($_XvncBase) {
    $this->XvncBase = $_XvncBase;
  }

  function setOtherData($_OtherData) {
    $this->OtherData = $_OtherData;
  }

  function setState($_State) {
    $this->State = $_State;
  }

  function selectHIPP($_ProjectID) {
    $App = new App();
    $WHERE = "";

    if ($_ProjectID != "") {
      $WHERE .= " WHERE SRV.ProjectID = " . $App->returnQuotedString($App->sqlSanitize($_ProjectID)) . "
                  AND SRV.ServiceType LIKE '_ipp'";
      $sql = "SELECT /*  hipp.class.php */
          SRV.ID,
          SRV.ProjectID,
          SRV.ServiceType,
          SRV.ServiceVersion,
          SRV.ServicePreviousVersion,
          SRV.ServerHost,
          SRV.ServerPort,
          SRV.XvncBase,
          SRV.OtherData,
          SRV.State
          FROM
          ProjectServices AS SRV "
          . $WHERE;

      $result = $App->foundation_sql($sql);

      if ($myrow = mysql_fetch_array($result)) {
        $this->setID($myrow["ID"]);
        $this->setProjectID($myrow["ProjectID"]);
        $this->setServiceType($myrow["ServiceType"]);
        $this->setServiceVersion($myrow["ServiceVersion"]);
        $this->setServicePreviousVersion($myrow["ServicePreviousVersion"]);
        $this->setServerHost($myrow["ServerHost"]);
        $this->setServerPort($myrow["ServerPort"]);
        $this->setXvncBase($myrow["XvncBase"]);
        $this->setOtherData($myrow["OtherData"]);
        $this->setState($myrow["State"]);
      }
      $result = NULL;
      $myrow = NULL;

      $this->getLatestVersionFromFile("hipp");
    }
  }

  /**
   * getLatestVersionFromFile - read latest version from file
   * @param string - Servicetype (HIPP)
   * @return none
   * @since 2015-02-26
   * @author droy
   */
  function getLatestVersionFromFile($_ServiceType="hipp") {
    if(is_readable($this->HIPPImagePath . "/latest")) {
      $this->setServiceLatestVersion(file_get_contents($this->HIPPImagePath. "/latest"));
    }
  }

}