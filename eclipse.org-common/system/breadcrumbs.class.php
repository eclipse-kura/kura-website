<?php
/**
 * *****************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Denis Roy (Eclipse Foundation) - initial API and implementation
 * Christopher Guindon (Eclipse Foundation) - Updated first level and added
 * removeCrumb()
 * Darrell Armstrong - Add 2nd and 3rd level crumbs
 * *****************************************************************************
 */
if (!class_exists('MenuItem')) {
  require_once ("menuitem.class.php");
}

if (!class_exists('Menu')) {
  require_once ("menu.class.php");
}

class Breadcrumb extends Menu {

  private $CrumbList = array();

  private $protocol = "http://";

  private $www_prefix = "";

  // static list of first-level URIs with corresponding display-friendly names
  // everything outside of this is considered to be in project space
  private $FirstLevel = array(
    "10years" => "10 years of Eclipse",
    "articles" => "Articles",
    "artwork" => "Artwork",
    "community" => "Community",
    "committers" => "Committers",
    "contribute" => "Contribute",
    "corporate_sponsors" => "Corporate Sponsors",
    "documentation" => "Documentation",
    "donate" => "Donate",
    "downloads" => "Downloads",
    "eclipse4" => "Eclipse SDK 4.x",
    "errors" => "Errors",
    "europa" => "Europa",
    "friends" => "Friends of Eclipse",
    "galileo" => "Galileo",
    "ganymede" => "Ganymede",
    "go" => "Go",
    "helios" => "Helios",
    "home" => "Home",
    "ide" => "IDE",
    "images" => "Images",
    "indigo" => "Indigo",
    "juno" => "Juno",
    "kepler" => "Kepler",
    "legal" => "Legal",
    "licenses" => "Licenses",
    "luna" => "Luna",
    "mail" => "Mailing Lists",
    "mars" => "Mars",
    "membership" => "Members",
    "mobile" => "Mobile",
    "neon" => "Neon",
    "newsgroups" => "Forums",
    "org" => "About Us",
    "oxygen" => "Oxygen",
    "phoenix-test" => "Test",
    "photon" => "Photon",
    "projects" => "Projects",
    "proposals" => "Proposals",
    "resources" => "Resources",
    "security" => "Security",
    "screenshots" => "Screenshots",
    "site_login" => "My Account",
    "getting_started" => "Getting started",
    'webmaster' => "Webmaster",
    "" => ""
  ) // Homepage
;

  // Second level items and friendly names (taken from page title which shows in current crumb)
  // newsletters set their own additional level of crumbs (in community)
  private $SecondLevel = array(
    "foundation" => "Eclipse Foundation",
    "workinggroups" => "Eclipse Working Groups",
    "elections" => "Elections",
    "documents" => "Governance Documents",
    "press-release" => "Press Releases"
  );

  // Third level items and friendly names
  // omit the date as the main landing page includes the current report + archived
  private $ThirdLevel = array(
    "reports" => "Annual Report"
  );

  function getCrumbList() {
    return $this->CrumbList;
  }

  function getProtocol() {
    return $this->protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
  }

  function getWWW_prefix() {
    global $App;

    if (!isset($App)) {
      $App = new App();
    }

    $this->www_prefix = $App->getWWWPrefix();
    $this->getProtocol();
    $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');

    // List of domains where we can't use the value of $App->getWWWPrefix().
    $allowed_domain_override = array(
      'dev.eclipse.org',
      'dev.eclipse.local'
    );

    if (in_array($http_host, $allowed_domain_override)) {
      $this->www_prefix = $this->protocol . $http_host;
    }

    return $this->www_prefix;
  }

  function setCrumbList($_List) {
    $this->CrumbList = $_List;
  }

  // Main constructor
  function __construct($page_title = "") {

    $this->getWWW_prefix();

    // Default: Home
    $this->addCrumb("Home", "/", "_self");

    if (isset($_SERVER['REQUEST_URI'])) {
      // http://www.eclipse.org/newsgroups/test.php
      // Array ( [0] => [1] => newsgroups [2] => test.php )
      $items = explode("/", $_SERVER['REQUEST_URI']);

      // Examine Item 1 (first level URL)
      if (isset($this->FirstLevel[$items[1]])) {
        $this->addCrumb($this->FirstLevel[$items[1]], $this->www_prefix . "/" . $items[1], "_self");
      }
      else {
        // Not pre-defined Foundation page, must be a project page
        // /xtext/file.php => Home > Projects > xtext > $pageTitle
        $this->addCrumb("Projects", $this->www_prefix . "/projects/", "_self");
        $this->addCrumb($items[1], $this->www_prefix . "/" . $items[1], "_self");
      }

      // Add 2nd (and 3rd) level crumb, if needed and it is not the landing page (index)
      if (count($items) >= 4){
        if(isset($this->SecondLevel[$items[2]]) && !empty($items[count($items) - 1]) && $items[count($items) - 1] !== "index.php") {
          $this->addCrumb($this->SecondLevel[$items[2]], $this->www_prefix . "/" . $items[1] . "/" . $items[2], "_self");
        }

        // 3rd level - Annual Reports (annual_report is the landing page for current report and where to return if browsing archived report)
        if(isset($this->ThirdLevel[$items[3]]) && $items[count($items) - 1] !== "annual_report.php" && $items[count($items) -1] !== "index.php"){
          $this->addCrumb($this->ThirdLevel[$items[3]],
            $this->www_prefix . "/" . $items[1] . "/" . $items[2] . "/" .$items[3], "_self");
        }
      }

      // Add current page
      // AT this point, $pageTitle should be set as we are running in header()
      global $pageTitle;
      if (!empty($page_title)) {
        $pageTitle = $page_title;
      }
      $pageTitle = strip_tags($pageTitle);
      if (isset($pageTitle)) {
        $title = $pageTitle;

        // consider truncating $pageTitle if it's too long
        if (strlen($title) > 35) {
          $title = substr($title, 0, 35) . "...";
        }

        // Bug 442449 - Distinguish between page title and breadcrumbs menu
        // Remove project name from $title
        if ($this->getCrumbCount() > 1) {
          $pattern = '/^' . $this->getCrumbAt($this->getCrumbCount() - 1)->getText() . " /i";
          $title = preg_replace($pattern, '', $title);
        }
        $this->addCrumb($title, NULL, NULL);
      }
      else {
        // Add final generic crumb
        $this->addCrumb("Document", NULL, NULL);
      }

    }
  }

  function addCrumb($_Text, $_URL, $_Target) {
    $_Text = strip_tags($_Text);

    // We don't need to add a crumb if there is no text for it.
    if (!empty($_Text)) {
      // Menu Items must be added at position 1
      $Crumb = new Link($_Text, $_URL, $_Target, 0);

      // Add incoming menuitem
      $this->CrumbList[count($this->CrumbList)] = $Crumb;
    }
  }

  function getCrumbCount() {
    return count($this->CrumbList);
  }

  function getCrumbAt($_Pos) {
    if ($_Pos < $this->getCrumbCount()) {
      return $this->CrumbList[$_Pos];
    }
    // If link does not exist, return an empty link object
    else {
      $Crumb = new Link('', '', '', 0);
      return $Crumb;
    }
  }

  /**
   * Insert breadcrumb at a specific position
   *
   * @param unknown $_Pos
   *        Position to insert at
   * @param unknown $_Text
   *        Link text
   * @param unknown $_URL
   *        Link URL
   * @param unknown $_Target
   *        Link target
   *
   */
  function insertCrumbAt($_Pos, $_Text, $_URL, $_Target) {
    if ($_Pos < $this->getCrumbCount() && $_Pos > 0) { // Don't allow inserting
                                                       // before Home
      $Crumb = new Link($_Text, $_URL, $_Target, 0);
      $tempList = array(
        $Crumb
      );
      $result = array_merge(array_slice($this->CrumbList, 0, $_Pos, true), $tempList, array_slice($this->CrumbList, $_Pos, $this->getCrumbCount(), true));
      $this->CrumbList = $result;
    }
    else {
      $this->addCrumb($_Text, $_URL, $_Target);
    }
  }

  /**
   * Unset a link from CrumbList
   *
   * @param int|array $_Key
   */
  private function _removeCrumb($_Key) {
    if (isset($this->CrumbList[$_Key])) {
      unset($this->CrumbList[$_Key]);
    }
  }

  /**
   * Remove links from CrumbList
   *
   * Usage example:
   *
   * Remove more than one link with an array.
   * $Breadcrumb->removeCrumb(array(0,3));
   *
   * Remove only one link.
   * $Breadcrumb->removeCrumb(1);
   *
   * @param int|array $_Key
   */
  function removeCrumb($_Key = NULL) {

    if ($_Key === NULL) {
      return;
    }

    if (is_array($_Key)) {
      foreach ($_Key as $k) {
        $this->_removeCrumb($k);
      }
    }
    else {
      $this->_removeCrumb($_Key);
    }

    // 'reindex' CrumbList.
    $this->CrumbList = array_values($this->CrumbList);
  }

  function showBreadcrumbs() {
    // for debugging purposes only
    echo "Breadcrumbs: ";
    foreach ($this->CrumbList as $Crumb) {
      // $Crumb is a Link object
      echo "<a href='" . $Crumb->getURL() . "'>" . $Crumb->getText() . "</a>";
      echo " | ";
    }
  }

}
