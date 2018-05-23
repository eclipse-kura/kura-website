<?php
/*******************************************************************************
 * Copyright (c) 2010, 2014, 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Nathan Gervais (Eclipse Foundation) - Initial API + Implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/

require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

/**
 * CampaignImpression
 *
 * @package eclipse.org-common
 * @subpackage ads
 * @author: Christopher Guindon <chris.guindon@eclipse.org>
 */
class CampaignImpression {

  /**
   * The Eclipse campaign key
   * @var unknown
   */
  private $campaign_key = 0;

  /**
   * The description of the page that will display the ad
   * @var string
   */
  private $source = '';

  /**
   * The remote address of the user that will view the ad
   * @var string
   */
  private $remote_addr = '';

  /**
   * The current UNIX timestamp
   * @var string
   */
  private $timestamp = '';

  /**
   * Constructor
   *
   * @param string $_campaign_key
   * @param string $_source
   * @param string $_remote_addr
   */
  function __construct($_campaign_key, $_source, $_remote_addr) {
    $this->campaign_key = $_campaign_key;
    $this->source = $_source;
    $this->remote_addr = $_remote_addr;
    $this->timestamp = date('Y-m-d H:i:s');
  }

  /**
   * Record an impression in the Eclipse database
   */
  function recordImpression() {
    $App = new App();

    // We dont register ad impressions in devmode
    if ($App->devmode == TRUE) return;

    if (rand(0, 1000) < 1) {
      // 1 of every 1,000 hits (0.1%) will clean up
      $deleteSql = "DELETE LOW_PRIORITY FROM CampaignImpressions WHERE TimeImpressed < DATE_SUB(NOW(), INTERVAL 1 YEAR)";
      $App->eclipse_sql($deleteSql);
    }

    $sql = "INSERT DELAYED INTO CampaignImpressions
        (CampaignKey, Source, HostName, TimeImpressed)
        VALUES (
      " . $App->returnQuotedString($App->sqlSanitize($this->campaign_key)) . ",
      " . $App->returnQuotedString($App->sqlSanitize($this->source)) . ",
      " . $App->returnQuotedString($App->sqlSanitize($this->remote_addr)) . ",
      " . $App->returnQuotedString($App->sqlSanitize($this->timestamp)) . ")";

    $result = $App->eclipse_sql($sql);
    return TRUE;
  }
}