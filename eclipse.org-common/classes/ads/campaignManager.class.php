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
require_once("campaign.class.php");
class CampaignManager extends Campaign{

  /**
   * Message of success or error
   * @var array
   * */
  private $status_message = array();

  /**
   * Number of clicks for the current campaign
   * @var string
   */
  private $current_clicks = "";

  /**
   * Count the number of clicks for campaigns
   * @var string
   */
  private $count_clicks = "";

  /**
   * This is the header of the HTML page
   * Which includes the list of campaigns and the number of clicks
   * @var string
   */
  private $page_header_html = '';


  public function __construct() {
    parent::__construct();

    $Session = $this->App->useSession(true);

    // Verify what state is in right now
    switch($this->state) {

      // If a new Campaign is being created
      case 'create':
        $this->createNewCampaign();
        break;

      // If a Campaign is being deleted
      case 'delete':
        $this->deleteCampaign();

        break;

      // If a Campaign is being changed
      case 'change':
        $this->changeCampaign();
        break;
    }

    // Verify what page the user is viewing
    switch($this->page) {
      case 'view-campaigns':
        $this->viewCampaigns();
        $this->outputView('campaigns');
        break;
      case 'view-clicks':
        $this->viewCampaignClicks();
        $this->outputView('clicks');
        break;
    }
  }

  /**
   * SETTERS
   */

  /**
   * Set the success or error message
   * @param string
   * */
  public function setStatusMessage($_message = '', $_type = 'success') {
    $alert_type = array('success', 'warning', 'danger', 'info');
    if(!in_array($_type, $alert_type)) {
      $_type = 'warning';
    }
    $this->status_message[$_type][] = $_message;
  }

  /**
   * Set the number of clicks for the current campaign
   * @param SQL query
   */
  private function setCurrentClicks($_val) {
    $this->current_clicks = $_val;
  }

  /**
   * Set Count Clicks of a campaign
   * @param SQL query
   */
  private function setCountCampaignClicks($_val) {
    $this->count_clicks = $_val;
  }

  /**
   * GETTERS
   */

  /**
   * Get the Success or Error Mesage
   * @return string
   * */
  public function getStatusMessage() {
    $html = "";
    foreach($this->status_message as $type => $msgs) {
      foreach($msgs as $m) {
        $html .= '<div class="alert alert-' . $type . '" role="alert">' . $m . '</div>';
      }
    }
    return $html;
  }

  /**
   * Get the number of clicks of the current campaign
   * @return string
   */
  public function getCurrentClicks() {
    $clicks = array();
    if($this->getCountCampaignClicks() != 0){
      while ($myrow = mysql_fetch_array($this->current_clicks)){
        $clicks[] = $myrow;
      }
    }
    return $clicks;
  }

  /**
   * Get the Number of Clicks of a campaign
   * @return string
   */
  public function getCountCampaignClicks() {
    return $this->count_clicks;
  }

  /**
   * Content of the page on first load
   * And set the Page Title
   * @return string
   */
  public function outputPage($pageTitle = 'Eclipse Campaign Manager') {
    $html = "";

    // Print the output if the user is a commiter
    if($this->state != 'not-staff') {
      ob_start();
      print '<h1>' . $pageTitle . '</h1>';
      print $this->getStatusMessage();
      include("tpl/campaignManager.tpl.php");
      $html = ob_get_clean();
    }
    return $html;
  }

  /**
   * Counts the number of clicks
   */
  public function countCampaignClicks($_campaign_name) {
    $App = new App();

    // Get sanitized values
    $campaign_name = $App->returnQuotedString($App->sqlSanitize($_campaign_name));

    //Select total amount of clicks for each campaigns
    $sql = "SELECT COUNT(*) AS count FROM CampaignClicks WHERE CampaignKey=" . $campaign_name;
    $result = $App->eclipse_sql($sql);
    $count = array();
    while($row = mysql_fetch_array($result)) {
      $this->setCountCampaignClicks($row['count']);
      $count = $row['count'];
    }
    return $count;
  }

  /**
   * Find out what the amount of last month's clicks
   */
  public function pastMonthClicks($_campaign_name) {
    $App = new App();


    // Get sanitized values
    $campaign_name = $App->returnQuotedString($App->sqlSanitize($_campaign_name));

    // Select dates and counts for each month for the past 6 month
    $sql = 'SELECT
                 count(TimeClicked) as count,
                 DATE_FORMAT(TimeClicked, "%Y-%m") as date
            FROM
                 CampaignClicks
            WHERE CampaignKey = '.$campaign_name.'
            GROUP BY
                 MONTH(TimeClicked)
            ORDER BY TimeClicked DESC
            LIMIT 6';
    $result = $App->eclipse_sql($sql);
    $results = array();
    while($row = mysql_fetch_array($result)) {
      $results[$row['date']] = $row['count'];
    }

    $clicks = array();
    $thisMonth = strtotime('now');

    while ($thisMonth >= strtotime("-5 month")) {
      $date = date('Y-m',$thisMonth);
      $clicks[] = array(
          'date' => $date,
          'count' => (!empty($results[$date])) ? $results[$date] : 0,
      );
      $thisMonth = strtotime(" -1 month", $thisMonth);
    }

    return $clicks;
  }

  /**
   * Create a new campaign
   */
  public function createNewCampaign() {
    $App = new App();

    // Get sanitized values
    $campaign_name = $App->returnQuotedString($App->sqlSanitize($this->getCampaignName()));
    $campaign_target = $App->returnQuotedString($App->sqlSanitize($this->getCampaignTarget()));
    $campaign_end_date = $App->returnQuotedString($App->sqlSanitize($this->getCampaignEndDate()));
    $campaign_portal_ID = $App->returnQuotedString($App->sqlSanitize($this->Friend->getEmail()));
    $campaign_group = $App->returnQuotedString($App->sqlSanitize($this->getCampaignGroup()));

    // Define default message
    $message = "ERROR, Campaign could not be created.";
    $message_type = "danger";

    // Form validation
    $error_type = "";

    // Check to see it the campaign name is valid
    if(empty($campaign_name)) {
      $error_type = "Campaign Name";
    }

    // Check to see if the URL is valid.
    if(strpos($campaign_target,"://") === FALSE ||
       strpos($campaign_target,".") === FALSE ||
       empty($campaign_target)) {
      $error_type = "URL";
      // Empty the url field
      $campaign_target = "";
    }

    // Check to see if the end date is valid
    if(empty($campaign_end_date) ||
       strpos($campaign_end_date,"-") === FALSE) {
      $error_type = "Date";
      $campaign_end_date = "";
    }

    // There is something wrong with a field
    $message = "ERROR, you need to enter a valid " . $error_type;

    // Check if the fields are not empty (Group field can be empty)
    if(!empty($campaign_name) &&
       !empty($campaign_target) &&
       !empty($campaign_end_date) &&
       !empty($campaign_portal_ID)) {

      // Check for Duplicate Campaign Names
      $duplicates;
      $verifyDuplicates = "SELECT COUNT(*) FROM Campaigns WHERE CampaignKey = '" . $campaign_name . "'";
      $resultVerifyDuplicates = $App->eclipse_sql($verifyDuplicates);

      // Define message if there's a duplicate
      $message = "ERROR, there's already a campaign with the same name.";

      while($row = mysql_fetch_array($resultVerifyDuplicates)) {
        $duplicates = $row[0];
      }

      // If it does not create a duplicate, create a new entry
      if ($duplicates == 0) {
        $sql = "INSERT INTO Campaigns
             (CampaignKey,
              TargetUrl,
              DateCreated,
              DateExpires,
              CreatorPortalID,
              CampaignGroup)
              values(" . $campaign_name . ",
                     ". $campaign_target . ",
                     " . "NOW(),
                     " . $campaign_end_date . ",
                     ". $campaign_portal_ID . ",
                     " . $campaign_group . ")";
        $result = $App->eclipse_sql($sql);

        // Define success message
        $message = 'SUCCESS, Campaign has been Created!';
        $message_type = 'success';
      }
    }

    // SET MESSAGE
    $this->setStatusMessage($message, $message_type);
  }

  /**
   * Delete a campaign
   */
  public function deleteCampaign() {
    $App = new App();

    // Get sanitized values
    $campaign_delete = $App->returnQuotedString($App->sqlSanitize($this->getCampaignDelete()));
    $campaign_confirm_delete = $App->returnQuotedString($App->sqlSanitize($this->getCampaignConfirmDelete()));

    // Define default message
    $message = 'ERROR, Please click the confirm check box to delete.';
    $message_type = 'danger';

    if($campaign_confirm_delete) {

      // Define message when you don't know the CampaignKey
      $message = 'ERROR, Campaign could not be found.';
      if(!empty($campaign_delete)) {
        $sql = "DELETE FROM Campaigns WHERE CampaignKey=" . $campaign_delete;
        $result = $App->eclipse_sql($sql);

        // Define success message
        $message = 'SUCCESS, Campaign has been deleted.';
        $message_type = 'success';
      }
    }

    // SET MESSAGE
    $this->setStatusMessage($message, $message_type);
  }

  /**
   * Make changes to a Campaign
   */
  public function changeCampaign() {
    $App = new App();

    // Get sanitized values
    $campaign_new_url = $App->returnQuotedString($App->sqlSanitize($this->getCampaignNewUrl()));
    $campaign_new_date = $App->returnQuotedString($App->sqlSanitize($this->getCampaignNewDate()));
    $campaign_new_group = $App->returnQuotedString($App->sqlSanitize($this->getCampaignNewGroup()));
    $campaign_new_clicks = $App->returnQuotedString($App->sqlSanitize($this->getCampaignNewClicks()));

    // Define default message
    $message = 'ERROR, Campaign could not be changed.';
    $message_type = 'danger';

    if(!empty($campaign_new_url) && !empty($campaign_new_date) &&
       !empty($campaign_new_group) && !empty($campaign_new_clicks)) {
      $sql = "UPDATE Campaigns set
                  TargetUrl = " . $campaign_new_url . ",
                  DateExpires = " . $campaign_new_date . ",
                  CampaignGroup = " . $campaign_new_group . "
              WHERE
                  CampaignKey = " . $campaign_new_clicks;
      $result = $App->eclipse_sql($sql);

      // Define success message
      $message = 'SUCCESS, Campaign has been Changed!';
      $message_type = 'success';
    }

    // SET MESSAGE
    $this->setStatusMessage($message, $message_type);
  }

  /**
   * View Campaigns based on a Person or a Group
   */
  public function viewCampaigns() {
    $App = new App();
    $campaigns = array();

    // Get sanitized values
    $campaign_portal_ID = $App->sqlSanitize($this->getCampaignPortalID());
    $campaign_group = $App->sqlSanitize($this->getCampaignGroup());

    if(!empty($campaign_portal_ID) || !empty($campaign_group)) {
      $sql = "SELECT * FROM Campaigns";
      // View Campaigns by user ID
      if($campaign_portal_ID != "" && $campaign_portal_ID != "ALL" &&
         $campaign_group == "") {
        $sql .= " WHERE CreatorPortalID = '" . $campaign_portal_ID . "'";
      }
      // View Campaigns by Group
      else if($campaign_group != "" && $campaign_group != "ALL") {
        $sql .= " WHERE CampaignGroup = '" . $campaign_group . "'";
      }
      $sql .= " ORDER BY CampaignGroup, CampaignKey";
      $results = $App->eclipse_sql($sql);

      while ($result = mysql_fetch_array($results)){
        $campaigns[] = $result;
      }
      return $campaigns;
    }
  }

  /**
   * View the number of clicks from a specific campaign
   */
  public function viewCampaignClicks() {
    $App = new App();

    // Get sanitized values
    $campaign_view_clicks = $App->returnQuotedString($App->sqlSanitize($this->getCampaignViewClicks()));
    $campaign_max_clicks = $App->sqlSanitize($this->getCampaignMaxClicks());


    if(!empty($campaign_view_clicks) && !empty($campaign_max_clicks)) {
      $sql = "SELECT * FROM CampaignClicks
              WHERE CampaignKey=" . $campaign_view_clicks . "
              ORDER BY TimeClicked DESC LIMIT " . $campaign_max_clicks;
      $this->setCurrentClicks($App->eclipse_sql($sql));
      $this->countCampaignClicks($this->getCampaignViewClicks());
      $message = 'There are <strong>' . $this->getCountCampaignClicks() . '</strong> clicks for the <strong>'. $this->getCampaignViewClicks() . '</strong> campaign.';
      $message_type = 'info';
      $this->setStatusMessage($message, $message_type);
    }
  }

  /**
   * Select the Campaign that we want to change the URL
   */
  public function selectCampaignFromCampaignKey() {
    $App = new App();
    $campaign_view_clicks = $App->returnQuotedString($App->sqlSanitize($this->getCampaignViewClicks()));
    $campaign = array();
    if(!empty($campaign_view_clicks)) {
      $sql = "SELECT DISTINCT * FROM Campaigns WHERE CampaignKey=" . $campaign_view_clicks;
      $result = $App->eclipse_sql($sql);
    }
    while ($myrow = mysql_fetch_array($result)) {
        $campaign['TargetUrl'] = $myrow['TargetUrl'];
        $campaign['DateExpires'] = $myrow['DateExpires'];
        $campaign['CampaignGroup'] = $myrow['CampaignGroup'];
        $campaign['NewExpiryDate'] = date('Y-m-d',strtotime('+3 years'));
    }
    return $campaign;
  }

  /**
   * Select Query by Person or by group
   * */
  private function selectUserOrGroup($_user_or_group) {
    $App = new App();

    // Define accepted values
    $acceptedValues = array('CreatorPortalID','CampaignGroup');

    // Get sanitized values
    $user_or_group = $App->sqlSanitize($_user_or_group);

    if(in_array($user_or_group, $acceptedValues)) {
      $sql = "SELECT DISTINCT " . $user_or_group . " FROM Campaigns ORDER BY " . $user_or_group;
      return $App->eclipse_sql($sql);
    }else{
      return NULL;
    }
  }

  /**
   * Select Campaigns based on a Person
   */
  public function selectCampaignByUser() {
    $users = array();
    $results = $this->selectUserOrGroup("CreatorPortalID");
    while ($result = mysql_fetch_array($results)){
      $users[] = $result;
    }
    return $users;
  }

  /**
   * Select Campaigns based on a Group
   */
  public function selectCampaignByGroup() {
    $groups = array();
    $results = $this->selectUserOrGroup("CampaignGroup");
    while ($result = mysql_fetch_array($results)){
      $groups[] = $result;
    }
    return $groups;
  }

  /**
   * Determine if the view is generated based on a person's id or a group
   * */
  public function getCampaignByUserOrGroup($_for_url = FALSE) {
    $userOrGroup = "";
    $campaignPortalID = "";
    $campaignGroup = "";

    if($_for_url == TRUE) {
      $campaignPortalID = "campaignPortalID=";
      $campaignGroup = "campaignGroup=";
    }
    if($this->getCampaignPortalID() || $this->getCampaignGroup()) {
      if($this->getCampaignPortalID()) {
        $userOrGroup = $campaignPortalID . $this->getCampaignPortalID();
      }
      if($this->getCampaignGroup()) {
        $userOrGroup = $campaignGroup . $this->getCampaignGroup();
      }
      return $userOrGroup;
    }
    return FALSE;
  }

  /**
   * Shorten a string as you which
   * */
  public function shortenedString($_string, $_start, $_end) {
    $output = substr($_string, $_start, $_end);
    if (strlen($_string) > 80) {
      $output .= '...';
    }
    return $output;

  }

  /**
   * Ouput the campaigns or the number of clicks for one campaign
   * */
  private function outputView($_val) {
    ob_start();
    include("tpl/campaign_view_". $_val .".tpl.php");
    return $this->page_header_html = ob_get_clean();
  }
}