<?php
/*******************************************************************************
 * Copyright(c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier(Eclipse Foundation)
 *******************************************************************************/

require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));
require_once(realpath(dirname(__FILE__) . "/../../system/session.class.php"));
require_once(realpath(dirname(__FILE__) . "/../../system/file_export.class.php"));

class AdReports {
  private $App;

  private $FileExport = NULL;

  private $impressions = NULL;

  private $interval = NULL;

  private $interval_plus_one = NULL;

  private $Session = NULL;

  private $state = "";

  public function __construct() {

    $this->App = new App();

    $this->FileExport = new FileExport();

    // Require login for this page.
    // Anonymous users get redirected to login page
    $this->Session = $this->App->useSession('required');

    $this->Friend = $this->Session->getFriend();

    $this->state = $this->App->getHTTPParameter('state','POST');

    if ($this->state == 'exportCsv') {
      $this->FileExport->buildCsv('ad-reports', $this->getImpressions(), array('campaignKey','date','impressions','clicks','Ratio (%)'));
    }
  }

  public function outputPage($pageTitle = 'Eclipse Ads Report') {
    $html = "";

    // Print the output if the user is a commiter
    if($this->state != 'not-staff') {
      ob_start();
      include("tpl/ad_reports.tpl.php");
      $html = ob_get_clean();
    }
    return $html;
  }

  public function getImpressions() {
    if (is_null($this->impressions)) {
      $this->_setImpressions();
    }
    return $this->impressions;
  }

  /**
   * This function returns the interval value
   * @param string
   * @return string
   * */
  private function _getInterval($interval = "") {
    $this->interval = 10;
    if ($interval == 'default') {
      return $this->interval;
    }
    if ($interval == 'plus_one') {
      return $this->interval ++;
    }
  }

  /**
   * This function creates a new temporary impressions table
   * */
  private function _fetchImpressions() {
    $sql = "SELECT
            campaignKey,
            count(*) as impressions,
            str_to_date(concat(yearweek(TimeImpressed), ' Sunday'), '%X%V %W') as date
            FROM CampaignImpressions
            WHERE TimeImpressed > (NOW()-INTERVAL ". $this->_getInterval('plus_one'). " WEEK)
            GROUP BY campaignKey, date";
    $result = $this->App->eclipse_sql($sql);
    $array = array();
    while ($row = mysql_fetch_array($result)) {
      $array[] = array(
          'campaignKey' => $row['campaignKey'],
          'impressions' => $row['impressions'],
          'date' => $row['date'],
      );
    }
    return $array;
  }

  /**
   * This function creates a new temporary Clicks table
   * */
  private function _fetchClicks() {
    $sql = "SELECT
              campaignKey,
              count(*) as clicks,
              str_to_date(concat(yearweek(TimeClicked), ' Sunday'), '%X%V %W') as date
              FROM CampaignClicks
              WHERE TimeClicked > (NOW()-INTERVAL ". $this->_getInterval('plus_one'). " WEEK)
              GROUP BY campaignkey, date";
    $result = $this->App->eclipse_sql($sql);
    $array = array();
    while ($row = mysql_fetch_array($result)) {
      $array[] = array(
          'campaignKey' => $row['campaignKey'],
          'clicks' => $row['clicks'],
          'date' => $row['date'],
      );
    }
    return $array;
  }

  /**
   * This function merges the two new temporary tables
   * */
  private function _setImpressions() {
    $clicks = $this->_fetchClicks();
    $impressions = $this->_fetchImpressions();
    $combined_array = array();
    foreach ($impressions as $i) {
      $i['clicks'] = 0;
      foreach ($clicks as $c) {
        if (!empty($c['clicks']) && strcasecmp($c['campaignKey'], $i['campaignKey']) == 0  && $c['date'] == $i['date']) {
          $i['clicks'] = $c['clicks'];
        }
      }
      $i['ratio'] = $i['clicks'] / $i['impressions'] * 100;
      $combined_array[] = $i;
    }
    $this->impressions = $combined_array;
  }
}