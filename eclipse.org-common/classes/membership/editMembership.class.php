<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));
require_once(realpath(dirname(__FILE__) . "/../../system/session.class.php"));

require_once("membership.class.php");

define('EDITMEMBERSHIP_LOGGED_IN_USER', 'EDITMEMBERSHIP_LOGGED_IN_USER');

class EditMembership extends Membership{

  /**
   * The value of the token that will be used in the URL
   * @var string
   * */
  public $token_url = "";

  private $Friend = NULL;

  /**
   * Member's current short description
   * @var string
   * */
  private $member_short_description = "";

  /**
   * Member's long description
   * @var string
   * */
  private $member_long_description = "";

  /**
   * Member's current url
   * @var string
   * */
  private $member_url = "";

  /**
   * The current small logo
   * @var string
   * */
  private $member_small_logo = "";

  /**
   * The current large logo
   * @var string
   * */
  private $member_large_logo = "";

  /**
   * The current member product
   * @var array
   * */
  private $member_product = array();

  private $Session;

  /**
   * What's the status right now
   * (changed information, changed logos)
   * @var string
   * */
  private $state = NULL;

  /**
   * Message of success or error
   * @var array
   * */
  private $status_message = array();

  /**
   * Token submitted by user
   * @var bool
   * */
  private $token = "";


  function __construct(){
    parent::__construct();

    $this->Session = $this->App->useSession();

    $this->setId($this->App->getHTTPParameter('member_id', 'POST'));

    if($this->getIsAvalidMemberId()){
      // IF the user is requesting a token,
      // we want to return right after
      switch($this->getState()){
        case 'token-request':
          $this->_submitTokenRequest();
          return FALSE;
      }

      $this->_setInitialFieldsAndLogos();

      // Check what have changed
      switch($this->getState()){
        case 'edit-info':
          $this->_editSelectedInformation();
          break;
        case 'edit-logo':
          $this->_editSmallOrLargeLogo();
          break;
        case 'edit-link':
          $this->_editMemberProduct();
          break;
        case 'add-link':
          $this->_createMemberProduct();
          break;
        case 'delete-link':
          $this->_deleteMemberProduct();
          break;
        case 'add-contact':
          $this->_submitNewMaintainer();
          break;
      }
    }
  }

  /**
   * Creating an Email in HTML format
   * */
  public function createEmail($_to, $_subject, $_body){

    if($this->App->is_staging()){
      $_to = 'webdev@eclipse.org';
    }

    $from = 'webmaster@eclipse.org';
    $headers = "MIME-Version: 1.0" . PHP_EOL;
    $headers .= 'Content-Type: text/plain; charset=UTF-8' . PHP_EOL;
    $headers .= 'From: ' . $from . PHP_EOL .
                'Reply-To: ' . $from . PHP_EOL;
    mail($_to, $_subject, $_body, $headers);
  }

  /**
   * GETTERS
   * */

  /**
   * Getting the member's current short description
   * @param string
   * */
  public function getMemberProduct(){
    return $this->member_product;
  }

  /**
   * Getting the member's current short description
   * @param string
   * */
  public function getMemberShortDescription(){
    return stripslashes($this->member_short_description);
  }

  /**
   * Getting the member's current long description
   * @param string
   * */
  public function getMemberLongDescription(){
    return stripslashes($this->member_long_description);
  }

  /**
   * Getting the member's current url
   * @param string
   * */
  public function getMemberUrl(){
    return $this->member_url;
  }

  public function getMemberLogo($_size){
    if($_size == 'small'){
      return $this->member_small_logo;
    }
    if($_size == 'large'){
      return $this->member_large_logo;
    }
  }

  /**
   * Get the Success or Error Mesage
   * @return string
   * */
  public function getStatusMessage() {
    // Make sure we have a session
    session_start();

    $html = "";
    $messages = $_SESSION['eclipse']['status_message'];
    if(!empty($messages)){
      foreach($messages as $type => $msgs) {
        foreach($msgs as $m) {
          $html .= '<div class="alert alert-' . $type . '" role="alert">' . $m . '</div>';
        }
      }
      unset($_SESSION['eclipse']['status_message']);
      session_destroy();
      return $html;
    }
  }

  public function getState() {
    if (is_null($this->state)) {
      $this->state = $this->App->getHTTPParameter('state', 'GET');
    }
    return $this->state;
  }

  /**
   * Get the token submitted by the user
   * @parem string
   * */
  public function getToken(){
    if (!$this->token) {
      $this->_setToken();
    }
    return $this->token;
  }

  /**
   * Fetch the user ID using the Friend's class
   * @param string
   * */
  public function fetchUserEmail(){
    if ($this->getToken() != "") {
      return $this->_fetchEmailBasedOnToken();
    }
    if($this->Session->isLoggedIn()){
      $Friend = $this->Session->getFriend();
      return $Friend->getEmail();
    }
  }

  /**
   * Query to fetch the Member's maintainers
   * @param array
   * */
  public function fetchMemberMaintainers($_users = ""){
    $_email = $this->App->returnQuotedString($this->App->sqlSanitize($this->fetchUserEmail()));
    $_member_id = $this->App->returnQuotedString($this->App->sqlSanitize($this->id));
    $sql = 'SELECT
            p.PersonID, p.FName, p.LName, p.EMail, p.Phone,
            group_concat("",
            CASE oc.Relation
               WHEN "MPE" THEN "Membership Page Editor"
               WHEN "DE"  THEN "Delegate"
               WHEN "MA"  THEN "Marketing"
               WHEN "CR"  THEN "Company Representative"
            END) as Type
            FROM People as p
            LEFT JOIN OrganizationContacts as oc
              ON p.PersonID = oc.PersonID ';
    if ($_users == EDITMEMBERSHIP_LOGGED_IN_USER) {
      $sql .= 'WHERE p.EMail = '. $_email;
    }
    else {
      $sql .= 'WHERE p.EMail IN
                (SELECT
                  p.Email
                  FROM OrganizationContacts as oc
                  LEFT JOIN People as p
                  ON oc.PersonID = p.PersonID
                  WHERE OrganizationID = ' . $_member_id . '
                )';
    }
    $sql .= 'AND (oc.Relation = "CR" OR oc.Relation = "MA" OR oc.Relation = "DE" OR oc.Relation = "MPE")
            AND OrganizationID = ' . $_member_id . '
            GROUP BY p.PersonID';
    $result = $this->App->foundation_sql($sql);

    // Build the array containing the Employees of this Member
    $_contacts = array();
    while ($row = mysql_fetch_assoc($result)) {
      $_contacts[$row['PersonID']]['PersonID'] = $row['PersonID'];
      $_contacts[$row['PersonID']]['FName'] = $row['FName'];
      $_contacts[$row['PersonID']]['LName'] = $row['LName'];
      $_contacts[$row['PersonID']]['EMail'] = $row['EMail'];
      $_contacts[$row['PersonID']]['Phone'] = ($row['Phone'] != NULL ? $row['Phone'] : 'N/A');
      $_contacts[$row['PersonID']]['Type'] = ($row['Type'] != NULL ? $row['Type'] : 'N/A');
    }
    return $_contacts;
  }

  /**
   * Content of the page on first load
   * Depending on if the user has the rights
   * to edit the page or not
   * @return string
   */
  public function outputPage() {
    $html = "";
    ob_start();
    if ($this->getIsAvalidMemberId() === FALSE) {
      $this->setMemberName("Invalid Member ID");
      include($_SERVER['DOCUMENT_ROOT'] . '/membership/content/en_showMemberInvalid.php');
      return ob_get_clean();
      exit;
    }
    switch($this->validateUser()) {
      case TRUE:
        $this->_editPage();
        break;
      case FALSE:
        print '<h1>Request access to edit the '. $this->getMemberName() .' Membership Page</h1>';
        print $this->getStatusMessage();
        include($_SERVER['DOCUMENT_ROOT'] . '/membership/content/en_token_request.php');
        break;
    }
    return ob_get_clean();
  }

  /**
   * Set the success or error message
   * @param string
   * */
  public function setStatusMessage($_message = '', $_type = 'success') {
    // Make sure we have a session
    session_start();

    $alert_type = array('success', 'warning', 'danger', 'info');
    if(!in_array($_type, $alert_type)) {
      $_type = 'warning';
    }
    $_SESSION['eclipse']['status_message'][$_type][] = $_message;
  }

  /**
   * Validate the user
   * - Check if the logged in user is a maintainer of the selected Member
   * - Check if the token submitted is valid
   * - Returns the state of the user ($user_state)
   * @param string
   * */
  public function validateUser(){
    $email = $this->App->returnQuotedString($this->App->sqlSanitize($this->fetchUserEmail()));
    $member_id = $this->App->returnQuotedString($this->App->sqlSanitize($this->id));
    $token = $this->App->returnQuotedString($this->App->sqlSanitize($this->getToken()));
    $_has_eclipse_account = "";
    $_is_maintainer = array();
    $_valid_token = array();
    $user_verified = FALSE;

    if(!empty($member_id) && !empty($email)){
      $sql_maintainer = 'SELECT
                         p.EMail
                         FROM OrganizationContacts as oc
                         LEFT JOIN People as p
                         ON oc.PersonID = p.PersonID
                         WHERE oc.OrganizationID = '. $member_id .'
                         AND p.EMail = '. $email .'
                         AND (oc.Relation = "CR" OR oc.Relation = "MA" OR oc.Relation = "DE" OR oc.Relation = "MPE")';
      $result_maintainer = $this->App->foundation_sql($sql_maintainer);

      while ($row = mysql_fetch_assoc($result_maintainer)) {
        $_is_maintainer[] = $row['EMail'];
        break;
      }
    }

    if(!empty($member_id) && !empty($token)){
      // Check to see if the token is there and valid
      $sql_token = 'SELECT *
                    FROM OrganizationTokens
                    WHERE Token = ' . $token;
      $result_token = $this->App->eclipse_sql($sql_token);

      while ($row = mysql_fetch_assoc($result_token)) {
        // Check to see if the token has expired
        $current_time = date('Y-m-d H:i:s');
        if($row['ValidUntil'] > $current_time && $row['Subnet'] == $this->App->getSubnet()){
          $_valid_token[] = $row['Token'];
          break;
        }
      }
    }

    // Is a Maintainer AND has an Eclipse Account
    if(!empty($_is_maintainer) || !empty($_valid_token)){
      $user_verified = TRUE;
    }
    return $user_verified;
  }

  /**
   * This function insert a new product in the OrganizationProducts table
   * */
  private function _createMemberProduct(){
    $productFields = array(
      'org_id' => filter_var($this->App->getHTTPParameter("new_member_product_organization_id", "POST"), FILTER_SANITIZE_NUMBER_INT),
      'name' => filter_var($this->App->getHTTPParameter("new_member_product_name", "POST"), FILTER_SANITIZE_STRING),
      'description' => filter_var($this->App->getHTTPParameter("new_member_product_description", "POST"), FILTER_SANITIZE_STRING),
      'url' => filter_var($this->App->getHTTPParameter("new_member_product_url", "POST"), FILTER_SANITIZE_URL)
    );

    // Define default error message
    $message = '';
    $message_type = 'danger';

    // Check if id and url is not empty
    // Description can be empty
    if(empty($productFields['name'])){
      $message .= 'ERROR, The Name field is empty.<br>';
    }
    if(empty($productFields['url'])){
      $message .= 'ERROR, The URL field is empty.<br>';
    }
    if (!empty($productFields['url']) && !empty($productFields['name']) && !empty($productFields['org_id'])) {
      $sql = 'INSERT INTO OrganizationProducts (OrganizationID,name,description,product_url)
      VALUES ('.
        $this->App->returnQuotedString($this->App->sqlSanitize($productFields['org_id'])).','.
        $this->App->returnQuotedString($this->App->sqlSanitize($productFields['name'])).','.
        $this->App->returnQuotedString($this->App->sqlSanitize($productFields['description'])).','.
        $this->App->returnQuotedString($this->App->sqlSanitize($productFields['url'])).
      ')';
      $result = $this->App->eclipse_sql($sql);

      $message = 'SUCCESS, a new link has been created.';
      $message_type = 'success';
    }

    // SET MESSAGE
    $this->setStatusMessage($message, $message_type);
    // Get the most up to date product data
    $this->_setMemberProduct($this->fetchMemberProducts());

    $this->_redirectTo('#open_tab_edit-links');
  }

  /**
   * Edit the Member products (links)
   * */
  private function _editMemberProduct(){
    $product = array(
      'id' => filter_var($this->App->getHTTPParameter("member_product_id", "POST"), FILTER_SANITIZE_NUMBER_INT),
      'name' => filter_var($this->App->getHTTPParameter("member_product_name", "POST"), FILTER_SANITIZE_STRING),
      'description' => filter_var($this->App->getHTTPParameter("member_product_description", "POST"), FILTER_SANITIZE_STRING),
      'url' => filter_var($this->App->getHTTPParameter("member_product_url", "POST"), FILTER_SANITIZE_URL)
    );

    // Define default error message
    $message = 'ERROR, one of your fields is empty.';
    $message_type = 'danger';

    // Check if id and url is not empty
    // Description can be empty
    if(empty($product['name'])){
      $message .= 'ERROR, The Name field is empty.<br>';
    }
    if(empty($product['url'])){
      $message .= 'ERROR, The URL field is empty.<br>';
    }
    if (!empty($product['id']) && !empty($product['url']) && !empty($product['name'])) {
      $sql = 'UPDATE OrganizationProducts SET
          name = ' . $this->App->returnQuotedString($this->App->sqlSanitize($product['name'])) . ',
          description = ' . $this->App->returnQuotedString($this->App->sqlSanitize($product['description'])) . ',
          product_url = ' . $this->App->returnQuotedString($this->App->sqlSanitize($product['url'])) . '
      WHERE
          ProductID = ' . $this->App->returnQuotedString($this->App->sqlSanitize($product['id']));
      $result = $this->App->eclipse_sql($sql);

      $message = 'SUCCESS, your product has been changed.';
      $message_type = 'success';
    }
    // SET MESSAGE
    $this->setStatusMessage($message, $message_type);
    // Get the most up to date product data
    $this->_setMemberProduct($this->fetchMemberProducts());

    $this->_redirectTo('#open_tab_edit-links');
  }

  /**
   * Content of the Edit Page
   * @return string
   * */
  private function _editPage(){
    $token = $this->getToken();
    if(!empty($token)) {
      $this->token_url = '&token=' . $token;
    }
    print '<h1>Edit '. $this->getMemberName() .' Membership Page</h1>';
    print $this->getStatusMessage();
    include($_SERVER['DOCUMENT_ROOT'] . '/membership/content/en_editMember.php');
  }

  /**
   * This function deletes member products
   * */
  private function _deleteMemberProduct(){
    $product_id = filter_var($this->App->getHTTPParameter("member_product_id", "POST"), FILTER_SANITIZE_STRING);

    // Define default error message
    $message = 'ERROR, your link has not been deleted.';
    $message_type = 'danger';

    $is_part_of_product_list = FALSE;
    $products = $this->fetchMemberProducts();
    foreach($products as $product){
      if($product_id == $product['id']){
        $is_part_of_product_list = TRUE;
        break;
      }
    }

    if($is_part_of_product_list && !empty($product_id)) {
      $sql = 'DELETE FROM OrganizationProducts
              WHERE ProductID = ' . $product_id;
      $result = $this->App->eclipse_sql($sql);

      $message = 'SUCCESS, your link has been deleted.';
      $message_type = 'success';
    }

    // SET MESSAGE
    $this->setStatusMessage($message, $message_type);
    // Get the most up to date product data
    $this->_setMemberProduct($this->fetchMemberProducts());

    $this->_redirectTo('#open_tab_edit-links');
  }


  /**
   * Update/Edit the Member's information
   * */
  private function _editSelectedInformation(){

    // Set the member's information
    $short_desc = filter_var($this->App->getHTTPParameter("member_short_description", "POST"), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $long_desc = filter_var(strip_tags($this->App->getHTTPParameter("member_long_description", "POST"), '<p><strong><em><b><i><br><ul><li>'), FILTER_DEFAULT, FILTER_FLAG_STRIP_HIGH);
    $url = filter_var($this->App->getHTTPParameter("member_url", "POST") , FILTER_SANITIZE_URL);
    $id = $this->id;

    // Define default error message
    $message = 'ERROR, one of your fields is empty.';
    $message_type = 'danger';
    if(!empty($id) && !empty($short_desc) && !empty($long_desc) && !empty($url)){
      // Update the Members Descriptions and url in the database
      $sql = "INSERT INTO OrganizationInformation
              (OrganizationID,short_description,long_description,company_url,small_width,small_height,large_width,large_height)
              VALUES
              (". $this->App->returnQuotedString($this->App->sqlSanitize($id)) .",
               ". $this->App->returnQuotedString($this->App->sqlSanitize($short_desc)) .",
               ". $this->App->returnQuotedString($this->App->sqlSanitize($long_desc)) .",
               ". $this->App->returnQuotedString($this->App->sqlSanitize($url)) .",
                0,0,0,0)
               ON DUPLICATE KEY
               UPDATE
               OrganizationID = " . $this->App->returnQuotedString($this->App->sqlSanitize($id)) . ",
               short_description = ". $this->App->returnQuotedString($this->App->sqlSanitize($short_desc)) .",
               long_description = ". $this->App->returnQuotedString($this->App->sqlSanitize($long_desc)) .",
               company_url = ". $this->App->returnQuotedString($this->App->sqlSanitize($url));
      $result = $this->App->eclipse_sql($sql);

      // Get the most up to date text fields
      $this->_setMemberTextFields($short_desc,$long_desc,$url);

      // Define success message
      $message = 'SUCCESS, your information has changed.';
      $message_type = 'success';
    }

    // SET MESSAGE
    $this->setStatusMessage($message, $message_type);

    $this->_redirectTo('#open_tab_edit-member-info');
  }

  // EDIT IMAGES

  /**
   * Edit the current logo
   * This function is being used for the Small logo and Large logo
   * */
  private function _editSmallOrLargeLogo(){
    $_param = $this->App->getHTTPParameter('logo-size', 'POST');
    $_logo = $this->App->getHTTPParameter('member_'.$_param.'_logo', 'FILES');

    // Define default message
    $message = 'ERROR, no image has been submitted.';
    $message_type = 'danger';

    if(empty($_logo['tmp_name']) || empty($_logo['type'])){
      $this->setStatusMessage($message, $message_type);
      return;
    }

    $logo_mime = $_logo['type'];
    $logo_tmp_name = $_logo['tmp_name'];
    $logo_error = $_logo['error'];
    $_id = $this->App->sqlSanitize($this->id);



    // Error #0 = No errors.
    if($logo_error == 0){
      $logo_info = getimagesize($logo_tmp_name);
      $logo_width = $logo_info[0];
      $logo_height = $logo_info[1];
      $max_size = 0;

      // Check if the submitted logo is the small or large one
      if($_param == 'small'){
        $max_size = 120;
      }
      if($_param == 'large'){
        $max_size = 200;
      }

      // Resize the image if needed
      $logo_resize = $this->_resize_image($logo_tmp_name, $logo_width, $logo_height, $logo_info[2], $max_size);
      imagepng($logo_resize, $logo_tmp_name, 9, PNG_ALL_FILTERS);
      $logo_mime = $this->App->sqlSanitize('image/png');

      // Get the image ready for blob
      $file_open = fopen($logo_tmp_name, 'rb');
      $file_raw = fread($file_open, filesize($logo_tmp_name));
      fclose($file_open);
      $logo_blob = addslashes($file_raw);

      // Get the width and height of the new logo
      $image = imagecreatefromstring($file_raw);
      $width = $this->App->sqlSanitize(imagesx($image));
      $height = $this->App->sqlSanitize(imagesy($image));

      $other_param = ($_param == 'small' ? 'large' : 'small');

      $sql = "INSERT INTO OrganizationInformation
              (
                OrganizationID,
                company_url,
                ".$_param."_mime,
                ".$_param."_width,
                ".$other_param."_width,
                ".$_param."_height,
                ".$other_param."_height,
                ".$_param."_logo
              )
              VALUES
              (
                ". $this->App->returnQuotedString($this->App->sqlSanitize($_id)) .",
                'http://',
                '". $logo_mime ."',
                ". $this->App->returnQuotedString($this->App->sqlSanitize($width)) .",
                '0',
                ". $this->App->returnQuotedString($this->App->sqlSanitize($height)) .",
                '0',
                '".$logo_blob."'
              )
              ON DUPLICATE KEY
              UPDATE
                OrganizationID = '". $this->App->sqlSanitize($_id) ."',
                company_url = 'http://',
                ".$_param."_mime = '". $logo_mime ."',
                ".$_param."_width = ". $this->App->returnQuotedString($this->App->sqlSanitize($width)) .",
                ".$other_param."_width = '0',
                ".$_param."_height = ". $this->App->returnQuotedString($this->App->sqlSanitize($height)) .",
                ".$other_param."_height = 0,
                ".$_param."_logo = '". $logo_blob ."'";
      $result = $this->App->eclipse_sql($sql);

      $message = 'SUCCESS, you have submitted a new logo.';
      $message_type = 'success';

      $new_image = '<img src="data:'. $logo_mime . ';base64,' . base64_encode(file_get_contents($logo_tmp_name)).'">';
      $this->_setMemberLogo($new_image, $_param);
    }
    // SET MESSAGE
    $this->setStatusMessage($message, $message_type);

    $this->_redirectTo('#open_tab_edit-logos');
  }

  /**
   * Validate that the token
   * */
  private function _fetchEmailBasedOnToken(){
    $token = $this->App->returnQuotedString($this->App->sqlSanitize($this->getToken()));
    $sql = 'SELECT *
            FROM OrganizationTokens
            WHERE Token = ' . $token;
    $result = $this->App->eclipse_sql($sql);
    // Put the results in an array
    $email_array = array();
    while ($row = mysql_fetch_assoc($result)) {
      $email_array['EMail'] = $row['Email'];
    }
    $email = $this->App->returnQuotedString($this->App->sqlSanitize($email_array['EMail']));
    $sql_people = 'SELECT EMail
                   FROM People
                   WHERE EMail = ' . $email;
    $result_people = $this->App->foundation_sql($sql_people);

    $uid_array = array();
    while ($row = mysql_fetch_assoc($result_people)) {
      $uid_array['EMail'] = $row['EMail'];
    }
    return $uid_array['EMail'];
  }

  /**
   * Redirect the user to the same page after submit a form
   * */
  private function _redirectTo($_anchor){
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_anchor, 302);
    exit;
  }

  /**
   * Resize an image/logo
   * @param string
   * */
  private function _resize_image($_tmp_name,$_width,$_height,$_type,$_max){
    $logo_resize = imagecreatetruecolor($_width, $_height);
    $new_height = $_height;
    $new_width = $_width;
    if( $_width > $_max || $_height > $_max ) {
      if( $_width < $_height ){
        $new_height = $_max;
        $aspect = $_width/$_height;
        $new_width = $new_height * $aspect;
      }else{
        $new_width = $_max;
        $aspect = $_height/$_width;
        $new_height = $new_width * $aspect;
      }
    }
    $logo_resize = imagecreatetruecolor($new_width, $new_height);
    switch( $_type ) { //$_logo_info[2]
      case IMAGETYPE_GIF:  $image = imagecreatefromgif($_tmp_name); break;
      case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($_tmp_name); break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($_tmp_name);
        $white = imagecolorallocate($logo_resize, 255, 255, 255);
        imagefilledrectangle($logo_resize, 0, 0, $new_width, $new_height, $white);
        break;
    }
    imagecopyresampled($logo_resize, $image, 0, 0, 0, 0, $new_width, $new_height, $_width, $_height);
    return $logo_resize;
  }

  /**
   * Set the initial values of text fields and logos
   * on first page load
   * @param string
   * */
  private function _setInitialFieldsAndLogos(){

    // Fetch the member's information from database
    $memberInfo = $this->fetchProfile();

    // Set current information
    $this->_setMemberTextFields($memberInfo['body'],$memberInfo['full_text'],$memberInfo['website']);

    // Set the current small and large logo
    $this->_setMemberLogo($memberInfo['small_logo_link'], 'small');
    $this->_setMemberLogo($memberInfo['large_logo_link'], 'large');

    // Set the current products
    $this->_setMemberProduct($this->fetchMemberProducts());
  }


  /**
   * SETTERS
   * */

  /**
   * Setting all the current text fields
   * @param string
   * */
  private function _setMemberTextFields($_short_desc, $_long_desc, $_url){
    $this->_setMemberShortDescription($_short_desc);
    $this->_setMemberLongDescription($_long_desc);
    $this->_setMemberUrl($_url);
  }

  /**
   * Setting all the current text fields
   * @param string
   * */
  private function _setMemberProduct($_val){
    $this->member_product = $_val;
  }

  /**
   * Setting the member's current short description
   * @param string
   * */
  private function _setMemberShortDescription($_val){
    $this->member_short_description = $_val;
  }

  /**
   * Setting the member's current long description
   * @param string
   * */
  private function _setMemberLongDescription($_val){
    $this->member_long_description = $_val;
  }

  /**
   * Setting the member's current url
   * @param string
   * */
  private function _setMemberUrl($_val){
    $this->member_url = $_val;
  }

  /**
   * Setting the current logo
   * @param string
   * */
  private function _setMemberLogo($_val, $_size){
    if($_size == 'small'){
      $this->member_small_logo = $_val;
    }
    if($_size == 'large'){
      $this->member_large_logo = $_val;
    }
  }

  /**
   * Set the token submitted by the user
   * */
  private function _setToken(){
    $token = filter_var($this->App->getHTTPParameter('token', 'GET'), FILTER_SANITIZE_STRING);
    if(!empty($token)){
      $this->token = $token;
    }
  }

  /**
   * Submit a new maintainer for the specified member
   * */
  private function _submitNewMaintainer(){
    $newMaintainerFields = array(
        'first_name' => array('name' => 'first name', 'value' => filter_var($this->App->getHTTPParameter("new_maintainer_first_name", "POST"), FILTER_SANITIZE_STRING)),
        'last_name' => array('name' => 'last name','value' => filter_var($this->App->getHTTPParameter("new_maintainer_last_name", "POST"), FILTER_SANITIZE_STRING)),
        'email' => array('name' => 'email address','value' => filter_var($this->App->getHTTPParameter("new_maintainer_email", "POST"), FILTER_SANITIZE_EMAIL)),
        'phone' => array('name' => 'phone number','value' => filter_var($this->App->getHTTPParameter("new_maintainer_phone", "POST"), FILTER_SANITIZE_STRING)),
        'role' => array('name' => 'contact role','value' => filter_var_array($this->App->getHTTPParameter("new_maintainer_type", "POST"), FILTER_SANITIZE_STRING)),
    );

    // Fetch the current user's email and uid
    $user = $this->fetchMemberMaintainers(EDITMEMBERSHIP_LOGGED_IN_USER);
    foreach ($user as $u){
      $currentUserFullName = $u['FName'] . " " . $u['LName'];
      $currentUserEmail = $u['EMail'];
      break;
    }

    $status_message = "";
    $message_type = "danger";
    $good_to_send = FALSE;
    $is_company_rep = FALSE;

    foreach($newMaintainerFields as $field) {
      if($field['value'] == "") {
        $status_message .= "Please enter a valid " . $field['name'] . ".<br>";
      }
    }

    $maintainers = $this->fetchMemberMaintainers();
    // Check if email is not empty
    if ($newMaintainerFields['email']['value'] != "" && !empty($newMaintainerFields['role']['value'])) {
      // Check if the email submitted matches with one already on record
      // And already has the submitted role
      foreach ($maintainers as $maintainer) {
        if (($newMaintainerFields['email']['value'] == $maintainer['EMail'])) {
          $roles = explode(",", $maintainer['Type']);
          foreach ($newMaintainerFields['role']['value'] as $newRole) {
            if (in_array($newRole, $roles)) {
              $status_message .= $newMaintainerFields['email']['value'] . " is already a ". $newRole .".<br>";
            }
          }
          break;
        }
      }
    }

    if($status_message == ""){

      // All new maintainers are to be sent to Perri
      $to = "membership-admin@eclipse.org";

      $email_subject = "New Maintainer Request for " . $this->getMemberName(). "";
      $email_body =  $currentUserFullName . " has requested for a new maintainer to be added to " . $this->getMemberName() . '.';
      $email_body .= PHP_EOL.PHP_EOL."Here are the information of the new maintainer:";
      $email_body .= PHP_EOL."Member Name: " . $this->getMemberName();
      $email_body .= PHP_EOL."First Name: " . $newMaintainerFields['first_name']['value'];
      $email_body .= PHP_EOL."Last Name: " . $newMaintainerFields['last_name']['value'];
      $email_body .= PHP_EOL."Email Address: " . $newMaintainerFields['email']['value'];
      $email_body .= PHP_EOL."Phone Number: " . $newMaintainerFields['phone']['value'];
      $email_body .= PHP_EOL."Contact Type: ";
      foreach ($newMaintainerFields['role']['value'] as $role) {
        if ($role == 'Company Representative (CR)') {
          $is_company_rep = TRUE;
        }
        $email_body .= PHP_EOL . $role . ',';
      }
      $email_body .= PHP_EOL.PHP_EOL."Please verify this information and add the new maintainer if required.";
      $email_body .= PHP_EOL.PHP_EOL."For more information about this request please contact:";
      $email_body .= PHP_EOL."Name: " . $currentUserFullName;
      $email_body .= PHP_EOL."Email: " . $currentUserEmail;
      if ($is_company_rep === TRUE) {
        $email_body .= PHP_EOL."NOTE: This person has been designated as a Company Representative.
                                PLEASE MAKE SURE THE COMPANY HAS ONLY 1 COMPANY REPRESENTATIVE.
                                THIS IS NOT AUTOMATED AND MUST BE VERIFIED WITH THE COMPANY AND
                                UPDATED MANUALLY IN BOTH THE DATABASE AND THE CRM.";
      }
      $this->createEmail($to, $email_subject, $email_body);

      $status_message = "Thank you. <br>Your changes will be reviewed by the Eclipse Foundation's membership
                  management, and you may be contacted to provide further information.";
      $message_type = "success";
    }
    $this->setStatusMessage($status_message, $message_type);

    $this->_redirectTo('#open_tab_view-contacts');
  }

  /**
   * Submit a token by email to the user
   * The token will be valid for only 24 hours
   * */
  private function _submitTokenRequest(){
    // Check if it's ok to send the token
    // By verifying the email address submitted
    $email_to = filter_var($this->App->getHTTPParameter("token_request_email", "POST"), FILTER_SANITIZE_EMAIL);
    $emails = $this->fetchMemberMaintainers();
    $good_to_send = FALSE;
    foreach ($emails as $email){
      if($email['EMail'] == $email_to){
        $good_to_send = TRUE;
        break;
      }
    }

    // Default status message is the error message
    $message = "The email address <strong>". $email_to ." is not defined
                as a maintainer</strong> for the member <strong>". $this->getMemberName() ."</strong>.<br>
                Please contact <a href='mailto:membership-admin@eclipse.org?subject=Request access to Eclipse
membership page - " . $this->getMemberName() . "&body=In order to receive the token to enable you to edit the company information for " . $this->getMemberName() . ", please provide the details below.  We will need to verify this information prior to responding to you - please leave up to 1 business day for this to occur. %0D%0A%0D%0A Thanks %0D%0A Eclipse Membership Administration Team %0D%0A%0D%0A PLEASE PROVIDE THE FOLLOWING DETAILS: %0D%0A%0D%0A Company name: " . $this->getMemberName() . "%0D%0A First name:%0D%0A Last name:%0D%0ATitle: %0D%0ACompany Email:%0D%0ARole (if known):%0D%0A'>membership-admin@eclipse.org</a>
                if you think you should have access and be defined as a maintainer.";
    $message_type = 'danger';

    if($good_to_send){

      //Generate the token
      $token = $this->App->sqlSanitize(bin2hex(openssl_random_pseudo_bytes(32)));
      $token_quoted = $this->App->returnQuotedString($token);
      $token_is_valid_until = $this->App->returnQuotedString($this->App->sqlSanitize(date('Y-m-d H:i:s', strtotime('+1 day', time()))));

      $subnet = $this->App->returnQuotedString($this->App->sqlSanitize($this->App->getSubnet()));
      $memberId = $this->App->returnQuotedString($this->App->sqlSanitize($this->id));
      $email_to = $this->App->returnQuotedString($email_to);

      // Add a new entry in the OrganizationTokens table
      $sql = 'INSERT INTO OrganizationTokens
              (OrganizationID, Token, Email,
               Subnet, ValidUntil)
               values(' . $memberId . ', '. $token_quoted . ', ' . $email_to . ',
                      '. $subnet .', '. $token_is_valid_until .')';
      $result = $this->App->eclipse_sql($sql);

      // Send an email
      $domain = $_SERVER['HTTP_HOST'];
      $email_subject = 'Your token to modify the member page of ' . $this->getMemberName();
      $email_body = 'You have been granted 24 hours to edit the '. $this->getMemberName() .' member page.'.
      PHP_EOL. PHP_EOL .'Please use the following link to make the edits:'.
      PHP_EOL.'https://'. $domain .'/membership/editMember.php?member_id='. $this->id .'&token='. $token .
      PHP_EOL. PHP_EOL .'For more information, please contact membership-admin@eclipse.org.';
      $this->createEmail($email_to, $email_subject, $email_body);

      // Set the status message to success if the token has been sent
      $message = 'An email has been sent
                  to <strong>' . $email_to . '</strong>.
                  Please verify your inbox.';
      $message_type = 'success';

    }
    $this->setStatusMessage($message, $message_type);
  }
}