<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *    Eric Poirier (Eclipse Foundation)
 *******************************************************************************/

//if name of the file requested is the same as the current file, the script will exit directly.
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

require_once(dirname(__FILE__) . "/../../system/eclipseenv.class.php");

class EclipseInstaller extends EclipseEnv {

  private $platform = array();

  private $total_download_count = 0;

  private $json_data = array();

  private $layout = "layout_b";

  private $download_links = array();

  /**
   * Constructor
   */
  function __construct($release = NULL) {
    parent::__construct();
    $this->_addPlaform('Mac OS X');
    $this->_addPlaform('Windows');
    $this->_addPlaform('Linux');

    if (!is_null($release)) {
      // Let's load the json feed to get the links for this release.
      $this->_loadJson($release);

      // Build the array containing all the download links
      $this->setDownloadLinks();
    }
  }

  /**
   * Add a link to the Eclipse Installer
   *
   * @param string $platform
   * @param string $url
   * @param string $text
   * @return boolean
   */
  public function addlink($platform = '', $url = '', $text = '') {

    if(!isset($this->platform[$this->_removeSpaces($platform)])) {
      return FALSE;
    }
    $link_classes = "";
    $count = count($this->platform[$this->_removeSpaces($platform)]['links']);
    $platform_array = array(
      'platform' => $platform,
      'count' =>$count,
      'link_classes' => "btn btn-warning",
      'url' => $url,
      'text' => $text,
      'text_prefix' => 'Download',
    );

    $this->setPlatform($platform_array);
  }

  /**
   * Output of the Eclipse Installer HTML
   *
   * @return string
   */
  public function output($version = NULL, $os = NULL) {
    $html = "";
    $tpl = "";
    $layout = $this->getInstallerLayout();

    $os_client = $this->_getClientOS();
    if (!empty($os)) {
      $os_client = $os;
    }

    $installer_links = $this->getInstallerArray($version, $os_client);

    if (!empty($layout)) {
      switch ($layout) {
        case 'layout_a':
          $tpl = "view/eclipseInstallerLayoutA.php";
          break;
        case 'layout_b':
          $download_count = $this->total_download_count;
          $tpl = "view/eclipseInstallerLayoutB.php";
          break;
      }
      ob_start();
      include($tpl);
      $html = ob_get_clean();
    }
    return $html;
  }

  /**
   * Returns the layout for the Installer
   *
   * @return string
   */
  public function getInstallerLayout() {
    return $this->layout;
  }

  /**
   * Sets a specified layout for the Installer
   *
   * @param string $layout
   */
  public function setInstallerLayout($layout = "") {
    if (filter_var($layout, FILTER_SANITIZE_STRING)) {
      $this->layout = $layout;
    }
  }

  /**
   * Returns the download link
   *
   * @return array
   */
  public function getDownloadLinks() {
    return $this->download_links;
  }

  /**
   * Set the download link
   *
   * @param array $links
   */
  public function setDownloadLinks() {
    $this->download_links = $this->getPlatform();
  }

  /**
   * Return a platform
   *
   * @return array
   */
  public function getPlatform() {
    return $this->platform;
  }

  /**
   * Sets a specified platform
   *
   * @param array $platform
   */
  public function setPlatform($platform = array()) {
    $this->platform[$this->_removeSpaces($platform['platform'])]['links'][] = $platform;
  }

  /**
   * Returns an array of links
   *
   * @param string $os
   *
   * @param string $version
   *
   * @return array
   */
  public function getInstallerLinks($version = NULL, $os = NULL) {

    $os_client = $this->_getClientOS();
    $accepted_os = array('windows','macosx','linux');
    if (!empty($os) && in_array($os, $accepted_os)) {
      $os_client = $os;
    }

    $download_links = $this->getInstallerArray($version, $os_client);

    $links = array(
      'links' => array(),
    );
    if (!empty($download_links)) {
        foreach($download_links['links'] as $link) {
          $links['links'][] = $link['url'];
        }
    }
    return $links;
  }

  /**
   * Returns the appropriate array based on the Version and OS if specified
   *
   * @param string $version
   *
   * @param string $os
   *
   * @return array
   */
  public function getInstallerArray($version = NULL, $os = NULL) {

    $download_links = $this->getDownloadLinks();

    // Return default array if nothing is specified
    if (empty($os) && empty($version)) {
      return $download_links;
    }

   $accepted_version = array('64bit','32bit');
    if (!empty($version) && !in_array($version, $accepted_version)) {
      return array();
    }

    $accepted_os = array('windows','macosx','linux');
    if (!empty($os) && !in_array($os, $accepted_os)) {
      return array();
    }

    // Build new array if Version or OS has been specified
    if (!empty($os) || !empty($version)) {
      $links = array(
        'links' => array(),
      );
      foreach ($download_links as $platform) {
        foreach ($platform['links'] as $link) {
          $link_label = str_replace(" ", "", strtolower($platform['label']));
          $link_text = str_replace(" ", "", strtolower($link['text']));

          //If both are specified
          if (!empty($os) && !empty($version) && $link_label == $os && $version == $link_text) {
            $links['links'][] = $link;
          }

          // If only OS is specified
          if (!empty($os) && empty($version) && $link_label == $os) {
            $links['links'][] = $link;
          }

          // If only Version is specified
          if (!empty($version) && empty($os) && $version == $link_text) {
            $links['links'][] = $link;
          }
        }
      }
      return $links;
    }
    return array();
  }

  /**
   * Add links from json data feed.
   */
  private function _addLinksFromJson() {
    $data = $this->json_data;
    $eclipse_env = $this->getEclipseEnv();

    if (!empty($data['files']['mac64'])) {
      $this->addlink('Mac OS X', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['mac64']['url']), "64 bit");
    }

    if (!empty($data['files']['win32'])) {
      $this->addlink('Windows', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['win32']['url']), '32 bit');
    }

    if (!empty($data['files']['win64'])) {
      $this->addlink('Windows', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['win64']['url']), '64 bit');
    }

    if (!empty($data['files']['linux32'])) {
      $this->addlink('Linux', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['linux32']['url']), '32 bit');
    }

    if (!empty($data['files']['linux64'])) {
      $this->addlink('Linux', str_replace('www.eclipse.org', $eclipse_env['domain'], $data['files']['linux64']['url']), "64 bit");
    }
  }

  /**
   * Add a platform to the Eclipse Installer
   *
   * @param string $label
   */
  private function _addPlaform($label = '') {
   $safe_label = $this->_removeSpaces($label);
    $this->platform[$safe_label] = array(
      'label' => $label,
      //'icon' => '<img src="/downloads/assets/public/images/icon-' . $safe_label . '.png"/>',
      'icon' => '',
      'links' => array(),
    );
  }

  /**
   * Returns the user's OS or the specified OS
   *
   * @return $display
   */
  private function _getClientOS() {
    require_once(realpath(dirname(__FILE__) . "/../../system/app.class.php"));
    $App = new App();
    $client_os = $App->getClientOS();
    $os = "windows"; // setting windows as default display
    if ($client_os == "linux" || $client_os == "linux-x64") {
      $os = "linux";
    }
    if ($client_os == "macosx" || $client_os == "cocoa64" || $client_os == "carbon") {
      $os = "macosx";
    }

    // Check if the OS has been selected manually
    if (isset($_GET['osType'])) {
      $os = $_GET['osType'];
      if ($_GET['osType'] == 'win32') {
        $os = "windows";
      }
    }

    return $os;
  }

  /**
   * Remove all spaces from a string.
   *
   * @param string $str
   */
  private function _removeSpaces($str = '') {
   return str_replace(' ', '', strtolower($str));
  }

  /**
   * Load jSON data from file.
   * @param unknown $release
   */
  private function _loadJson($release) {
    $url = '/home/data/httpd/writable/community/eclipse_installer.json';
    $json_data =  json_decode(file_get_contents($url), TRUE);
    foreach ($json_data as $data) {
      if (strtolower($data['release_title']) == strtolower($release)) {
        $this->json_data = $data;
        $this->_addLinksFromJson();
        if (!empty($this->json_data['total_download_count'])) {
          $this->total_download_count = $this->json_data['total_download_count'];
        }
        break;
      }
    }
  }

}