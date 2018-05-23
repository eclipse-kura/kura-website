<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/

require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));

class Webmaster {

  protected $App = NULL;

  protected $Friend = NULL;

  protected $projects = array();

  private $state = '';

  public function __construct(App $App) {
    $this->App = $App;

    // Determine if the user is a webmaster
    $Session = $this->App->useSession(true);
    $this->Friend = $Session->getFriend();
    if(!$this->Friend->checkUserIsWebmaster()) {
      header("HTTP/1.1 403 Forbidden");
      exit;
    }
    $this->state = filter_var($this->App->getHTTPParameter('state', 'POST'), FILTER_SANITIZE_STRING);
  }

  public function getFormActionUrl(){
    return '#';
  }

  /**
   * This function get all the active projects
   *
   * @return array
   * */
  public function getProjects() {
    if (empty($this->projects)) {
      $this->projects = $this->_fetchProjects();
    }
    return $this->projects;
  }

  /**
   * This function fetches the project ID for all Active projects
   *
   * @return array
   * */
  protected function _fetchProjects() {
    $sql = "SELECT ProjectID FROM Projects WHERE IsActive = 1";
    $result = $this->App->foundation_sql($sql);
    $projects = array();
    while ($row = mysql_fetch_array($result)) {
      $projects[] = array(
        'ProjectID' => $row['ProjectID']
      );
    }
    // Add Foundation as first item of the array
    array_unshift($projects, array('ProjectID' => 'Foundation'));

    $this->projects = $projects;
    return $projects;
  }

  /**
   * Get State
   */
  public function getState() {
    return $this->state;
  }

  /**
   * Get form_name
   */
  public function getFormName() {
    return filter_var($this->App->getHTTPParameter('form_name', 'POST'), FILTER_SANITIZE_STRING);
  }

  /**
   * Fetch HTML template for specific page
   *
   * @param $pageTitle - Title of the page
   * @param $page - The page we'd like to retrieve
   */
  public function outputPage($page = "") {
    $page = filter_var($page, FILTER_SANITIZE_STRING);
    $path = $this->App->getBasePath() . '/classes/webmaster/tpl/' . $page . '.tpl.php';
    if (!file_exists($path)) {
      $this->App->setSystemMessage('webmaster', 'An error has occurred. (#webmaster-001)', 'danger');
      return '';
    }
    ob_start();
    include($path);
    return ob_get_clean();
  }
}