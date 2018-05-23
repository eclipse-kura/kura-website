<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

//if name of the file requested is the same as the current file, the script will exit directly.
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

require_once(dirname(__FILE__) . "/DownloadsProject.class.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/membership/promo/promos.php");
require_once(dirname(__FILE__) . "/eclipseInstaller.php");
require_once(dirname(__FILE__) . "/../ads/promotedDownloads.class.php");
require_once(dirname(__FILE__) . "/../ads/downloadsBannerAd.class.php");

class Downloads extends DownloadsProject {

  protected $projects = array();

  protected $banner_ads = array();

  protected $promo_ad_type = "default";

  private $Installer = NULL;

  private $PromotedDownloads = NULL;

  private $hide_banner_ad = FALSE;

  private $Promos = NULL;

  public function __construct() {

    $this->Installer = new EclipseInstaller('oxygen/R2');
    $this->Installer->setInstallerLayout('layout_a');

    $this->PromotedDownloads = New PromotedDownloads();

    // Set the initial content of the Projects array
    $this->_setProjectsArray();

    // PROJECTS

    // Tool Platforms item
    $Project = new DownloadsProject();
    $Project->setTitle('Eclipse Che');
    $Project->setDescription('Eclipse Che is a developer workspace server and cloud IDE.');
    $Project->setLogo('assets/public/images/logo-che.png');
    $Project->setProjectType($this->projects['tool_platforms']['title']);
    $Project->setDownloadUrl64Bit('https://www.eclipse.org/che/getting-started/download/');
    $Project->setLearnMoreUrl('https://eclipse.org/che');
    $Project->setProjectsAttributes('image','height','50');
    $this->newProject($Project);

    // Tool Platforms item
    $Project = new DownloadsProject();
    $Project->setTitle('Orion');
    $Project->setDescription('A modern, open source software development environment that runs in the cloud.');
    $Project->setLogo('assets/public/images/logo-orion.png');
    $Project->setProjectType($this->projects['tool_platforms']['title']);
    $Project->setDownloadUrl64Bit('http://projects.eclipse.org/projects/ecd.orion/downloads');
    $Project->setLearnMoreUrl('https://orionhub.org');
    $Project->setProjectsAttributes('image','height','50');
    $this->newProject($Project);

    // Runtime Platforms item
    $Project = new DownloadsProject();
    $Project->setTitle('Jetty');
    $Project->setDescription('Eclipse Jetty provides a web server and javax.servlet container.');
    $Project->setLogo('assets/public/images/logo-jetty.png');
    $Project->setProjectType($this->projects['runtime_platforms']['title']);
    $Project->setDownloadUrl64Bit('https://www.eclipse.org/jetty/download.html');
    $Project->setLearnMoreUrl('http://www.eclipse.org/jetty/');
    $Project->setProjectsAttributes('container','class','jetty-spacing');
    $this->newProject($Project);

    // Runtime Platforms item
    $Project = new DownloadsProject();
    $Project->setTitle('Equinox');
    $Project->setDescription('Eclipse Equinox is an implementation of the OSGi core framework specification.');
    $Project->setLogo('assets/public/images/logo-equinox.png');
    $Project->setProjectType($this->projects['runtime_platforms']['title']);
    $Project->setDownloadUrl64Bit('http://projects.eclipse.org/projects/rt.equinox/downloads');
    $Project->setLearnMoreUrl('http://www.eclipse.org/equinox/');
    $Project->setProjectsAttributes('image','height','50');
    $this->newProject($Project);

    // Runtime Platforms item
    $Project = new DownloadsProject();
    $Project->setTitle('Concierge');
    $Project->setDescription('Eclipse Concierge is a small footprint implementation of the OSGi specification.');
    $Project->setLogo('assets/public/images/logo-concierge.png');
    $Project->setProjectType($this->projects['runtime_platforms']['title']);
    $Project->setDownloadUrl64Bit('https://projects.eclipse.org/projects/rt.concierge/downloads');
    $Project->setLearnMoreUrl('https://www.eclipse.org/concierge/');
    $this->newProject($Project);

    // Runtime Platforms item
    $Project = new DownloadsProject();
    $Project->setTitle('RAP');
    $Project->setDescription('Enabling modular business apps for desktop, browser and mobile.');
    $Project->setLogo('assets/public/images/logo-rap.png');
    $Project->setProjectType($this->projects['runtime_platforms']['title']);
    $Project->setDownloadUrl64Bit('https://www.eclipse.org/rap/downloads/');
    $Project->setLearnMoreUrl('http://www.eclipse.org/rap/');
    $this->newProject($Project);

    // Technology Communities item
    $Project = new DownloadsProject();
    $Project->setTitle('IoT');
    $Project->setDescription('Open source technology for IoT solution developers.');
    $Project->setLogo('assets/public/images/logo-iot.png');
    $Project->setProjectType($this->projects['technology_communities']['title']);
    $Project->setDownloadUrl64Bit('http://iot.eclipse.org/projects');
    $Project->setLearnMoreUrl('http://iot.eclipse.org/');
    $Project->setProjectsAttributes('container','class','col-xs-offset-4 col-md-offset-5 col-sm-offset-4');
    $Project->setProjectsAttributes('image','height','50');
    $this->newProject($Project);

    // Technology Communities item
    $Project = new DownloadsProject();
    $Project->setTitle('Systems Engineering');
    $Project->setDescription('Open source solutions for systems engineering and embedded systems.');
    $Project->setLogo('assets/public/images/logo-polarsys.png');
    $Project->setProjectType($this->projects['technology_communities']['title']);
    $Project->setDownloadUrl64Bit('https://www.polarsys.org/polarsys-downloads');
    $Project->setLearnMoreUrl('https://www.polarsys.org');
    $Project->setProjectsAttributes('image','height','50');
    $this->newProject($Project);

    // Technology Communities item
    $Project = new DownloadsProject();
    $Project->setTitle('LocationTech');
    $Project->setDescription('Open source solutions for geospatial applications.');
    $Project->setLogo('assets/public/images/logo-locationtech.png');
    $Project->setProjectType($this->projects['technology_communities']['title']);
    $Project->setDownloadUrl64Bit('https://www.locationtech.org/list-of-projects');
    $Project->setLearnMoreUrl('https://www.locationtech.org/');
    $Project->setProjectsAttributes('image','height','50');
    $this->newProject($Project);

  }

  /**
   * Set the initial content of the Projects Array
   */
  private function _setProjectsArray() {
    $this->projects = array(
      'tool_platforms' => array(
        'title' => 'Tool Platforms',
        'items' => array(),
      ),
      'runtime_platforms' => array(
        'title' => 'Runtime Platforms',
        'items' => array(),
      ),
      'technology_communities' => array(
        'title' => 'Technology Communities',
        'items' => array(),
      ),
    );
  }

  /**
   * Adds a new Project to a specific array
   *
   * @param $Project object
   *
   * @return bool
   */
  public function newProject($Project = NULL) {

    // Prevent an invalid project to be entered
    if (!$valid = $this->validProject($Project)) {
      return FALSE;
    }

    // Add each projects to their own category
    foreach ($this->projects as $key => $category) {
      if ($Project->getProjectType() == $category['title']) {
        $this->projects[$key]['items'][] = $Project;
      }
    }
    return TRUE;
  }

  /**
   * Returns the HTML of the banner ad
   *
   * @return string
   */
  public function getBannerAd() {
    if ($this->hide_banner_ad) {
      return "";
    }
    $DownloadsBannerAd = new DownloadsBannerAd();
    return $DownloadsBannerAd->output();
  }

  /**
   * Makes the banner ad disapear
   *
   * @param $hide - bool
   */
  public function hideBannerAd($hide = TRUE) {
    if (filter_var($hide, FILTER_VALIDATE_BOOLEAN)) {
      $this->hide_banner_ad = $hide;
    }
  }

  /**
   * Returns a promo ad block depending on the ad type
   *
   * @return string
   */
  public function getPromoAd() {
    $adNo = (isset($_GET['adNo'])) ? $_GET['adNo'] : '';
    $promo = chooseDownloadAd($adNo);
    return $promo;
  }

  /**
   * Returns the HTML output of a list of projects
   *
   * @param $projects - array
   *
   * @return string
   */
  private function _projectsOutput($projects) {
    ob_start();
    foreach ($projects as $project) {
      include 'tpl/downloadsProjects.tpl.php';
    }
    return ob_get_clean();
  }

  /**
   * Get all the projects
   *
   * @return string
   */
  public function getAllDownloadsProjects() {
    ob_start();
    foreach ($this->projects as $key =>$category) {
      include 'tpl/downloadsCategory.tpl.php';
    }
    return ob_get_clean();
  }

  /**
   * Get the list of projects depending on the specified category
   *
   * @param $category - string
   *
   * @return string
   */
  public function getProjectsList($category) {
    if (filter_var($category, FILTER_SANITIZE_STRING)) {
      return $this->_projectsOutput($this->projects[$category]['items']);
    }
  }
}