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

class Campaign{

  protected $Friend = NULL;

  protected $App = NULL;

  /**
   * What state the user is in
   * (create, delete, change, not-committer)
   * @var string
   * */
  protected $state = '';

  /**
   * What page the user is viewing
   * @var string
   * */
  protected $page = '';

  /**
   * Name of campaign
   * @var string
   * */
  private $campaign_name = "";

  /**
   * Group of campaign
   * @var string
   * */
  private $campaign_group = "";
  /**
   * Target URL of campaign
   * @var string
   * */
  private $campaign_target = "";

  /**
   * End date of campaign
   * @var string
   * */
  private $campaign_end_date = "";

  /**
   * User ID
   * @var string
   * */
  private $campaign_portal_ID = "";

  /**
   * Confirmation of delete campaign
   * @var string
   * */
  private $campaign_delete = "";

  /**
   * Confirmation of delete campaign
   * @var bool
   * */
  private $campaign_confirm_delete = "";

  /**
   * View Campaign Clicks
   * @var string
   * */
  private $campaign_view_clicks = "";

  /**
   * Max number of clicks from a campaign
   * @var string
   * */
  private $campaign_max_clicks = "";

  /**
   * New URL for an existing Campaign
   * @var string
   * */
  private $campaign_new_url = "";

  /**
   * New DATE for an existing Campaign
   * @var string
   * */
  private $campaign_new_date = "";

  /**
   * New GROUP for an existing Campaign
   * @var string
   * */
  private $campaign_new_group = "";

  /**
   * The Campaign ID to which the new data will be assigned to
   * @var string
   * */
  private $campaign_new_clicks = "";


  public function __construct() {
    $this->App = new App();
    // Require login for this page.
    $Session = $this->App->useSession(true);
    $this->Friend = $Session->getFriend();

    // Get what state the user is in right now
    // Possible states: create, delete, change, view-campaigns, view-clicks

    // Define the state
    $this->state = filter_var($this->App->getHTTPParameter('action_state', 'POST'), FILTER_SANITIZE_STRING);

    // Define the page
    $this->page = filter_var($this->App->getHTTPParameter('page', 'POST'), FILTER_SANITIZE_STRING);

    // Determine if the user is part of the staff
    if(!$this->Friend->checkUserIsFoundationStaff()) {
      header("HTTP/1.1 403 Forbidden");
      exit;
    }

    // Create New Campaign
    $this->setCampaignName($this->App->getHTTPParameter("campaignName", "POST"));
    $this->setCampaignGroup($this->App->getHTTPParameter("campaignGroup", "POST"));
    $this->setCampaignTarget($this->App->getHTTPParameter("campaignTarget", "POST"));
    $this->setCampaignEndDate($this->App->getHTTPParameter("campaignEndDate", "POST"));
    $this->setCampaignPortalID($this->App->getHTTPParameter("campaignPortalID", "POST"));

    // View Campaigns
    if($this->App->getHTTPParameter("confirmCDelete", "POST")) {
      $this->setCampaignConfirmDelete($this->App->getHTTPParameter("campaignConfirmDelete", "POST"));
    }
    $this->setCampaignDelete($this->App->getHTTPParameter("campaignDelete", "POST"));
    $this->setCampaignViewClicks($this->App->getHTTPParameter("campaignViewClicks", "POST"));
    $this->setCampaignMaxClicks($this->App->getHTTPParameter("campaignMaxClicks", "POST"));

    // Update Campaign URL and/or Date
    $this->setCampaignNewUrl($this->App->getHTTPParameter("campaignNewURL", "POST"));
    $this->setCampaignNewDate($this->App->getHTTPParameter("campaignNewDATE", "POST"));
    $this->setCampaignNewGroup($this->App->getHTTPParameter("campaignNewGROUP", "POST"));
    $this->setCampaignNewClicks($this->App->getHTTPParameter("campaignNewClicks", "POST"));
  }

  /**
   * SETTERS
   *
   * Set the Campaign Name
   * @param string
   * */
  public function setCampaignName($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_name = $_val;
  }

  /**
   * Set the Campaign Group
   * @param string
   * */
  public function setCampaignGroup($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_group = $_val;
  }

  /**
   * Set the Campaign Target URL
   * @param string
   * */
  public function setCampaignTarget($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_target = $_val;
  }

  /**
   * Set the Campaign End Date
   * @param string
   * */
  public function setCampaignEndDate($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_end_date = $_val;
  }

  /**
   * Set the User ID
   * @param string
   * */
  public function setCampaignPortalID($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_portal_ID = $_val;
  }

  /**
   * Set the Confirmation of delete for a campaign
   * @param string
   * */
  public function setCampaignDelete($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_delete = $_val;
  }

/**
   * Set the Confirmation of delete for a campaign
   * @param bool
   * */
  public function setCampaignConfirmDelete($_val) {
    if(!empty($_val)) {
      $_val = filter_var($_val , FILTER_SANITIZE_STRING);
      $this->campaign_confirm_delete = $_val;
    }
  }

  /**
   * Set the View Campaign clicks
   * @param string
   * */
  public function setCampaignViewClicks($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_view_clicks = $_val;
  }

  /**
   * Set the Max number of clicks
   * @param string
   * */
  public function setCampaignMaxClicks($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_max_clicks = $_val;
  }

  /**
   * Set the new URL for a campaign
   * @param string
   * */
  public function setCampaignNewUrl($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_new_url = $_val;
  }

  /**
   * Set the new DATE for a campaign
   * @param string
   * */
  public function setCampaignNewDate($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_new_date = $_val;
  }

  /**
   * Set the new GROUP for a campaign
   * @param string
   * */
  public function setCampaignNewGroup($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_new_group = $_val;
  }

  /**
   * Set the Campaign ID for which the new data will be assigned to
   * @param string
   * */
  public function setCampaignNewClicks($_val) {
    $_val = filter_var($_val , FILTER_SANITIZE_STRING);
    $this->campaign_new_clicks = $_val;
  }


  /**
   * GETTERS
   *
   * Get the Campaign Name
   * @param string
   * */
  public function getCampaignName() {
    return $this->campaign_name;
  }

  /**
   * Get the Campaign Group
   * @param string
   * */
  public function getCampaignGroup() {
    return $this->campaign_group;
  }

  /**
   * Get the Campaign Targert URL
   * @param string
   * */
  public function getCampaignTarget() {
    return $this->campaign_target;
  }

  /**
   * Get the Campaign End Date
   * @param string
   * */
  public function getCampaignEndDate() {
    return $this->campaign_end_date;
  }

  /**
   * Get the user ID
   * @param string
   * */
  public function getCampaignPortalID() {
    return $this->campaign_portal_ID;
  }

  /**
   * Get the confirmation of delete for a campaign
   * @param string
   * */
  public function getCampaignDelete() {
    return $this->campaign_delete;
  }

/**
   * Get the confirmation of delete for a campaign
   * @param bool
   * */
  public function getCampaignConfirmDelete() {
    return $this->campaign_confirm_delete;
  }

  /**
   * Get the View Campaign Clicks
   * @param string
   * */
  public function getCampaignViewClicks() {
    return $this->campaign_view_clicks;
  }

  /**
   * Get the max number of clicks for a campaign
   * @param string
   * */
  public function getCampaignMaxClicks() {
    return $this->campaign_max_clicks;
  }

  /**
   * Get the new URL for a campaign
   * @param string
   * */
  public function getCampaignNewUrl() {
    return $this->campaign_new_url;
  }

  /**
   * Get the new DATE for a campaign
   * @param string
   * */
  public function getCampaignNewDate() {
    return $this->campaign_new_date;
  }

  /**
   * Get the new GROUP for a campaign
   * @param string
   * */
  public function getCampaignNewGroup() {
    return $this->campaign_new_group;
  }

  /**
   * Get the Campaign ID for which the new data will be assigned to
   * @param string
   * */
  public function getCampaignNewClicks() {
    return $this->campaign_new_clicks;
  }
}