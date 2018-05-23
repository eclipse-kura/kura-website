<?php
/*******************************************************************************
* Copyright (c) 2006-2015 Eclipse Foundation and others.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the Eclipse Public License v1.0
* which accompanies this distribution, and is available at
* http://www.eclipse.org/legal/epl-v10.html
*
* Contributors:
*    Denis Roy (Eclipse Foundation) - initial API and implementation
*******************************************************************************/

require_once("/home/data/httpd/eclipse-php-classes/system/dbconnection.class.php");  # Read-only slave
require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class Mirror {

  # this class will eventually contain mirror information
  var $mirror_id = 0;  #PK
  var $organization = "";
  var $ccode = "";
  var $r30 = 0;
  var $update_ip_allow = "";
  var $email = "";
  var $is_internal = 0;
  var $is_advertise = 0;
  var $date_enabled = "";
  var $create_status = "";
  var $contact = "";
  var $internal_host_pattern = "";
  var $last_verified = "";

  public $exclude_string = "*.nfs* apitools/ apidocs/ archive/ archives/ /athena builds/N* */doc/* */documentation/* drops*/I* drops*/N* drops/M* *.jpg *.gif callisto/* compilelogs/ eclipse.org-common/ eclipse/testUpdates* eclipse/updates/3.2milestones /eclipse/updates/3.6-I-builds/ dev/TPTP* /tools/cdt/builds modeling/gmf/downloads/drops/B* *drops*/*/N* *drops*/*/I* *javadoc/ *javadocs/ linuxtools/N* *nightly* *Nightly* *staging* /webtools/downloads/drops/*/M* performance/ /releases/staging /releases/europa testresults/ /rt/eclipselink/nightly* /technology/cosmos /technology/ohf /technology/tigerstripe testcompilelogs/ testResults/ /tools/downloads /tools/orbit/committers */N201* */I201* */I.I201* */I-* */N-* *integration*/ xref/ */M20* /rt/eclipselink/maven.repo* */scripts* */logs* *drops4/X* *drops4/Y* */eclipse/updates/*-X* */eclipse/updates/*-Y* *.php*.* staging/* releases/staging/*";


  function getMirrorID() {
    return $this->mirror_id;
  }
  function getOrganization() {
    return $this->organization;
  }
  function getCCode() {
    return $this->ccode;
  }
  function getR30() {
    return $this->r30;
  }
  function getUpdateIPAllow() {
    return $this->update_ip_allow;
  }
  function getEMail() {
    return $this->email;
  }
  function getIsInternal() {
    return $this->is_internal;
  }
  function getIsAdvertise() {
    return $this->is_advertise;
  }
  function getDateEnabled() {
    return $this->date_enabled;
  }
  function getCreateStatus() {
    return $this->CreateStatus;
  }
  function getContact() {
    return $this->Contact;
  }
  function getInternalHostPattern() {
    return $this->internal_host_pattern;
  }
  function getLastVerified() {
    return $this->last_verified;
  }

  function setMirrorID($_mirror_id) {
    $this->mirror_id = $_mirror_id;
  }
  function setOrganization($_organization) {
    $this->organization = $_organization;
  }
  function setCCode($_ccode) {
    $this->ccode = $_ccode;
  }
  function setR30($_r30) {
    $this->r30 = $_r30;
  }
  function setUpdateIPAllow($_update_ip_allow) {
    $this->update_ip_allow = $_update_ip_allow;
  }
  function setEMail($_email) {
    $this->email = $_email;
  }
  function setIsInternal($_is_internal) {
    $this->is_internal = $_is_internal;
  }
  function setIsAdvertise($_is_advertise) {
    $this->is_advertise = $_is_advertise;
  }
  function setDateEnabled($_date_enabled) {
    $this->date_enabled = $_date_enabled;
  }
  function setCreateStatus($_create_status) {
    $this->CreateStatus = $_create_status;
  }
  function setContact($_contact) {
    $this->Contact = $_contact;
  }
  function setInternalHostPattern($_internal_host_pattern) {
    $this->internal_host_pattern = $_internal_host_pattern;
  }
  function setLastVerified($_last_verified) {
    $this->last_verified = $_last_verified;
  }

  function selectCountryCodeByIP($_IP) {

    $App = new App();

    $rValue = "xx";  # no ccode info for this IP
      if($_IP != "") {
        $ipnum = sprintf("%u", ip2long($_IP));

        $dbc = new DBConnection();  # Read-only, to slave!
        $dbh = $dbc->connect();

        $sql = "SELECT ccode FROM geoip WHERE $ipnum BETWEEN start AND end";

        $result = mysql_query($sql, $dbh);

      if($myrow = mysql_fetch_array($result)) {
        $rValue = $myrow['ccode'];
      }

      $dbc->disconnect();
      $dbh   = null;
      $dbc   = null;
      $result = null;
      $myrow  = null;

    }
    return $rValue;
  }

  /** @author droy
   * @since version - Apr 16,2007
   * @param String _IP IP address to examine
   * @return Boolean IP is a valid public IP
   *
   */
   function isValidPublicIP($_IP) {

     if(!preg_match('/^\d+\.\d+\.\d+\.\d+/', $_IP)) {
       return false;
     }

    if (substr_count($_IP, '.') > 3) {
      return false;
    }

    if (strpos($_IP, '.') == 0 || strrpos($_IP, '.') == (strlen($_IP)-1)) {
      return false;
    }

      $parts = explode('.', $_IP);

    foreach ($parts as $part) {
      if ($part > 255) {
        return false;
      }
    }

    switch($parts[0]) {
      case 0: return false; break;
      case 10: return false; break;
      case 127: return false;  break;
      case 172:
        if($parts[1] >= 16 && $parts[1] <= 32) {
          return false;
        }
        break;
      case 192:
        if($parts[1] == 168) {
          return false;
        }
        break;
    }

    if($parts[0] > 223) {
      return false;
    }

     return true;
   }


   /**
    * Returns true if user-agent appears to be a machine
    * @author Denis Roy
    * @see download.eclipse.org/errors/404.php
    * @since 2014-11-18
    * @return boolean
    *
    */
  function isMachineBrowser() {

    $browser = $_SERVER['HTTP_USER_AGENT'];
    $rValue = false;

    if(strpos($browser, "Jakarta") !== FALSE
      || strpos($browser, "Java/") !== FALSE
      || strpos($browser, "Slurp") !== FALSE
      || stripos($browser, "bot") !== FALSE
      || strpos($browser, "bing") !== FALSE
      || stripos($browser, "Googlebot/") !== FALSE
      || stripos($browser, "apacheHttpClient") !== FALSE
      || stripos($browser, "Apache-HttpClient/") !== FALSE
      || stripos($browser, "spider") !== FALSE
      || strpos($browser, "Apache-Maven/") !== FALSE
      || strpos($browser, "Apache Ivy/") !== FALSE
      || strpos($browser, "Apache Archiva/") !== FALSE
      || strpos($browser, "Artifactory/") !== FALSE
      || strpos($browser, "Aether") !== FALSE
      || strpos($browser, "m2e/") !== FALSE
      || strpos($browser, "Debian APT/") !== FALSE
      || strpos($browser, "developer fusion") !== FALSE
      || strpos($browser, "netBeans") !== FALSE     ) {
                $rValue = true;
        }
        return $rValue;
   }

  function isExcluded($filename) {
    $exclude_array   = explode(" ", $this->exclude_string);
    for($i = 0; $i < count($exclude_array); $i++) {
      # replace dot with \. since rsync considers it a dot
      $exclude_array[$i] = str_replace(".", "\.", $exclude_array[$i]);

      # replace leading and ending *
      $exclude_array[$i] = preg_replace('(^\*|\*$)', "", $exclude_array[$i]);
      $exclude_array[$i] = str_replace("*", ".*", $exclude_array[$i]);
      if(preg_match("#" . $exclude_array[$i] . "#", $filename)) {
        return true;
        break;
      }
    }
    return false;
  }

  /**
   * Run Stats query, return results as JSON
   * @param string $filename
   * @param string $view_date
   * @param string $view
   * @param string $group
   * @param string $datefrom
   * @param string $dateto
   * @return string JSON string
   * @since 2015-06-19
   * @author droy
   */
  function getStatsJSON($filename=null, $view_date=null, $view=null, $group=null, $datefrom=null, $dateto=null) {
    $App = new App();

    $dateQuery = false;
    if($view == "") {
    	$view = "sum";
    }

    $WHERE 		= "";
    $GROUPBY 	= "";
    $FILESPEC  	= "IDX.file_name";

    if($view_date == "today") {
      $WHERE = $App->addAndIfNotNull($WHERE);
      $WHERE .= " LEFT(DOW.download_date,10) = CURDATE()";
      $dateQuery = true;
    }

    if($view_date == "yesterday") {
      $WHERE = $App->addAndIfNotNull($WHERE);
      $WHERE .= " LEFT(DOW.download_date,10) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
      $dateQuery = true;
    }

    if($view_date == "L7") {
      $WHERE = $App->addAndIfNotNull($WHERE);
      $WHERE .= " DOW.download_date > DATE_SUB(CURDATE(), INTERVAL 8 day)";
      $dateQuery = true;
    }

    if($view_date == "L30") {
      $WHERE = $App->addAndIfNotNull($WHERE);
      $WHERE .= " DOW.download_date > DATE_SUB(CURDATE(), INTERVAL 31 day)";
      $dateQuery = true;
    }

    if($datefrom != "" && $dateto != "") {
      $WHERE = $App->addAndIfNotNull($WHERE);
      $WHERE .= " DOW.download_date >= '$datefrom' AND DOW.download_date < '$dateto 23:59:59'";
      $dateQuery = true;
    }

    if($datefrom != "" && $dateto == "") {
      $WHERE = $App->addAndIfNotNull($WHERE);
      $WHERE .= " DOW.download_date >= '$datefrom'";
      $dateQuery = true;
    }

    if($datefrom == "" && $dateto != "") {
      $WHERE = $App->addAndIfNotNull($WHERE);
      $WHERE .= "DOW.download_date <= '$dateto 23:59:59'";
      $dateQuery = true;
    }

    if($group != 1) {
      $GROUPBY = " IDX.file_name";
    }
    else {
      $FILESPEC =" \"File Group\" AS file_name";
    }

    if($view == "daily") {
      if($GROUPBY != "") {
        $GROUPBY .= ",";
      }
      $GROUPBY .= " LEFT(DOW.download_date,10)";
    }
    if($view == "ccode") {
      if($GROUPBY != "") {
        $GROUPBY .= ",";
      }
      $GROUPBY .= " DOW.ccode";
    }



    # Connect to database
    $dbc = new DBConnection();
    $now = $this->microtime_float();
    $dbh = $dbc->connect();

    # fetch oldest stat and total records
    $sql_info = "SELECT LEFT(MIN(download_date),10) as OldestDate, MAX(download_date) AS NewestDate, COUNT(*) AS RecordCount FROM downloads";
    $rs_info = mysql_query($sql_info, $dbh);
   	$myrow_info = mysql_fetch_assoc($rs_info);
   	$intTotalStats = number_format($myrow_info['RecordCount']);
    $sinceDate = $myrow_info['OldestDate'];
    $toDate = $myrow_info['NewestDate'];


    # Fetch the ID's if it's a date-based query
    $aFileID = array();
    $file_id_csv = "";

    if($filename != "") {
      $fileCount = 0;

      $sql = "SELECT IDX.file_id FROM download_file_index AS IDX WHERE IDX.file_name LIKE " . $App->returnQuotedString("%" . $filename . "%");
      $rs = mysql_query($sql, $dbh);
      while($myrow = mysql_fetch_assoc($rs)) {
        array_push($aFileID, $myrow['file_id']);
        $fileCount++;
      }
      $file_id_csv = implode(",", $aFileID);

      if(($fileCount > 5000 && $dateQuery) || ($fileCount > 10000 && !$dateQuery)) {
        die ("Your search includes too many files ($fileCount).  Please refine your search.");
      }
    }

    if($filename != "" && !$dateQuery) {
      $WHERE = $App->addAndIfNotNull($WHERE);
      $WHERE .= " IDX.file_name LIKE " . $App->returnQuotedString("%" . $filename . "%");
    }
    if($dateQuery) {
      $WHERE = $App->addAndIfNotNull($WHERE);
      $WHERE .= " IDX.file_id IN ($file_id_csv)";
    }

    if($WHERE != "") {
     $WHERE = " WHERE " . $WHERE;
    }
    if($GROUPBY != "") {
      $GROUPBY = " GROUP BY " . $GROUPBY;
    }

	/*
   	* End: Build query filter criteria
   	*/

  	/*
   	* Choose the appropriate query, based on the view
   	*
   	*/

    if($view == "sum") {
      if(!$dateQuery) {
        # simplified query based on the download_file_index only
        if($group == 1) {
          $sql = "SELECT $FILESPEC, SUM(download_count) AS file_count FROM download_file_index AS IDX " . $WHERE;
        }
        else {
          $sql = "SELECT $FILESPEC, download_count AS file_count FROM download_file_index AS IDX " . $WHERE . $GROUPBY . " ORDER BY file_count DESC";
        }
      }
      else {
        $sql = "SELECT $FILESPEC, COUNT(DOW.file_id) AS file_count FROM	download_file_index AS IDX LEFT JOIN downloads AS DOW ON DOW.file_id = IDX.file_id"
          . $WHERE . $GROUPBY . " ORDER BY file_count DESC";
      }
    }
    if($view == "daily" ) {
      $sql = "SELECT $FILESPEC, LEFT(DOW.download_date,10) AS download_date, COUNT(DOW.file_id) AS file_count	FROM download_file_index AS IDX
        LEFT JOIN downloads AS DOW ON DOW.file_id = IDX.file_id	" . $WHERE . $GROUPBY . "ORDER BY IDX.file_name, DOW.download_date DESC";
      # $inc_file = "inc/en_stats_daily.php";
	}


	if($view == "ccode") {
      $ORDERBY = " ORDER BY IDX.file_name, file_count DESC";
      if($group == 1) {
        $ORDERBY = " ORDER BY file_count DESC";
      }

      $sql = "SELECT $FILESPEC, DOW.ccode, COU.en_description AS CountryDescription, COUNT(DOW.file_id) AS file_count	FROM download_file_index AS IDX LEFT JOIN downloads AS DOW ON DOW.file_id = IDX.file_id
    	LEFT JOIN SYS_countries AS COU ON COU.ccode = DOW.ccode	" . $WHERE . $GROUPBY . $ORDERBY;

      # $inc_file = "inc/en_stats_ccode.php";
    }

    # Bypass query if no filename specified
    if($filename == "") {
      $sql = "SELECT 'Please specify a file' AS file_name, '' AS file_count";
   	}

    $rs = mysql_query($sql, $dbh);
    $querytime = $this->microtime_float() - $now;

    # build JSON payload
    $rValue = new stdClass();
    $rValue->totalStats = $intTotalStats;
    $rValue->sinceDate = $sinceDate;
    $rValue->toDate = $toDate;
    $rValue->queryTime = $querytime;

    $rows = array();
    while($r = mysql_fetch_assoc($rs)) {
      $rows[] = $r;
    }
    $rValue->rows = $rows;

    # Free up some memory from the hoggers
    unset($rows, $rs, $rs_info, $aFileID);
    $dbc->disconnect();

    return json_encode($rValue);
  }

  function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }

}
?>