<?php
/**
 * *****************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * *****************************************************************************
 */

require_once ('baseTheme.class.php');

class Locationtech extends baseTheme {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    $this->setTheme('locationtech');
    parent::__construct($App);

    $this->setBaseUrl('https://www.locationtech.org');
    $image_path = $this->getThemeUrl('solstice') . 'public/images/locationtech/';

    // LocationTech logos
    $this->setAttributes('img_logo_default', $image_path . 'logo.png', 'src');
    $this->setAttributes('img_logo_default', 'Locationtech.org logo', 'alt');

    $this->setAttributes('img_logo_mobile', $image_path . 'logo.png', 'src');
    $this->setAttributes('img_logo_mobile', 'Locationtech.org logo', 'alt');

    // Set default options
    $this->setDisplayMore(FALSE);

    // Set toolbar attributes
    $this->setAttributes('toolbar-container-wrapper', 'toolbar-contrast');

    // Set header attributes
    $this->setAttributes('header-container', 'no-border');
    $this->setAttributes('header-wrapper', 'page-header-logo-bordered');
    $this->setAttributes('header-left', 'hidden-xs col-sm-8');
    $this->setAttributes('header-right', 'hidden-xs col-md-6 col-sm-8 pull-right');

    // Set attributes for main-menu
    $this->setAttributes('main-menu-wrapper', 'col-sm-24');
    $this->setAttributes('main-menu-ul-navbar', 'navbar-right');

    // Set attributes for footer
    $this->setAttributes('footer1', 'col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print');
    $this->setAttributes('footer2', 'col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print');
    $this->setAttributes('footer3', 'col-xs-offset-1 col-xs-11 col-sm-7 col-md-4 col-md-offset-0 hidden-print');
    $this->setAttributes('footer4','col-xs-24 col-md-11 footer-other-working-groups col-md-offset-1 hidden-print');
  }

  /**
   * Hook for making changes to $App when using setApp()
   *
   * @param App $App
   */
  public function _hookSetApp($App) {
    $App->setGoogleAnalyticsTrackingCode('UA-910670-10');
  }

  /**
   * Set $Breadcrumb
   *
   * @param Breadcrumb $Breadcrumb
   */
  public function setBreadcrumb($Breadcrumb = NULL) {
    if (!$Breadcrumb instanceof Breadcrumb) {
      $App = $this->_getApp();
      require_once ($App->getBasePath() . '/system/breadcrumbs.class.php');
      $Breadcrumb = new Breadcrumb();
    }
    $Breadcrumb->insertCrumbAt('1', 'Eclipse Working Groups', 'https://www.eclipse.org/org/workinggroups', NULL);
    $Breadcrumb->insertCrumbAt('2', 'Locationtech', 'https://www.locationtech.org', NULL);
    $this->Breadcrumb = $Breadcrumb;
  }

  /**
   * Get default variables for CFA
   *
   * @return array
   */
  protected function _getCfaButtonDefault() {
    $default['class'] = 'btn btn-huge btn-warning';
    $default['href'] = 'https://locationtech.org/mailman/listinfo/location-iwg';
    $default['text'] = '<i class="fa fa-users"></i> Getting Started';
    return $default;
  }
  /**
   * Get main-menu html output
   *
   * @return string
   */
  public function getMenu() {
    $Menu = $this->_getMenu();
    $main_menu = $Menu->getMenuArray();
    $variables = array();
    $DefaultMenu = new Menu();
    $default_menu_flag = FALSE;
    if ($DefaultMenu->getMenuArray() == $main_menu) {
      $App = $this->_getApp();
      ob_start();
      include($App->getBasePath() . '/themes/' . $this->getTheme() . '/_menu_links.php');
      return ob_get_clean();
    }

    // Main-menu
    foreach ($main_menu as $item) {
      $menu_li_classes = "";
      $caption = $item->getText();
      $items[] = '<li' . $menu_li_classes . '><a href="' . $item->getURL() . '" target="' . $item->getTarget() . '">' . $caption . '</a></li>';
    }

    return implode($items, '');
  }

  /**
   * Get $ession_variables
   *
   * @param string $id
   *
   * @return string
   */
  public function getSessionVariables($id = "") {
    $Session = $this->_getSession();
    if ($id == "my_account_link" && !$Session->isLoggedIn()) {
      return '<a href="https://www.locationtech.org/user/login/"><i class="fa fa-sign-in fa-fw"></i> Log in</a>';
    }
    return parent::getSessionVariables($id);
  }

  /**
   * Get Html of Footer Region 1
   */
  public function getFooterRegion1() {
    return <<<EOHTML
      <h2 class="block-title">LocationTech</h2>
        <ul class="menu nav">
          <li class="first leaf"><a href="{$this->getBaseUrl()}/about" title="">About Us</a></li>
          <li class="leaf"><a href="{$this->getBaseUrl()}/contact" title="">Contact us</a></li>
          <li class="leaf"><a href="{$this->getBaseUrl()}/charter" title="">Governance</a></li>
          <li class="leaf"><a href="{$this->getBaseUrl()}>/steeringcommittee" title="">Steering Committee</a></li>
          <li class="last leaf"><a href="{$this->getBaseUrl()}>/jobs" title="">Jobs</a></li>
        </ul>
EOHTML;
  }

  /**
   * Get Html of Footer Region 2
   */
  public function getFooterRegion2() {
    return <<<EOHTML
      <h2 class="section-title">Legal</h2>
      <ul class="nav">
       <li><a href="//www.eclipse.org/legal/privacy.php">Privacy Policy</a></li>
       <li><a href="//www.eclipse.org/legal/termsofuse.php">Terms of Use</a></li>
       <li><a href="//www.eclipse.org/legal/copyright.php">Copyright Agent</a></li>
       <li><a href="//www.eclipse.org/legal/epl-2.0/">Eclipse Public License</a></li>
       <li><a href="//www.eclipse.org/legal/">Legal Resources</a></li>
     </ul>
EOHTML;
  }

  /**
   * Get Html of Footer Region 3
   */
  public function getFooterRegion3() {
    return <<<EOHTML
      <h2 class="block-title">Useful Links</h2>
      <ul class="menu nav">
        <li class="first leaf"><a href="https://locationtech.org/mailman/listinfo" title="">Discussion lists</a></li>
        <li class="leaf"><a href="https://github.com/LocationTech" title="">Github</a></li>
        <li class="leaf"><a href="https://locationtech.org/wiki" title="">Wiki</a></li>
        <li class="leaf"><a href="http://foss4g-na.org" title="">FOSS4G NA</a></li>
        <li class="leaf"><a href="http://tour.locationtech.org" title="">Tour</a></li>
        <li class="leaf"><a href="http://fedgeoday.org" title="">FedGeoDay</a></li>
        <li class="last leaf"><a href="https://status.eclipse.org">Service Status</a></li>
      </ul>
EOHTML;
  }

  /**
   * Get Html of Footer Region 4
   */
  public function getFooterRegion4() {
    return <<<EOHTML
      <div id="footer-working-group-left" class="col-sm-10 col-xs-offset-1 col-md-11 col-md-offset-1 footer-working-group-col">
        {$this->getLogo('default', TRUE)}<br/>
      </div>
      <div  id="footer-working-group-right" class="col-sm-10 col-xs-offset-1 col-sm-offset-3 col-md-11 col-md-offset-1 footer-working-group-col">
       <span class="hidden-print">{$this->getLogo('eclipse_white', $this->getEclipseUrl())}</span>
      </div>
EOHTML;
  }

  /**
   * Get Html of Footer Region 5
   */
  public function getFooterRegion5() {
    return <<<EOHTML
      <div class="col-sm-24 margin-top-20">
        <div class="row">
          <div id="copyright" class="col-md-16">
            <p>LocationTech is a Working Group of The Eclipse Foundation.</p>
            <p id="copyright-text">{$this->getCopyrightNotice()}</p>
          </div>
          <div class="col-md-8 social-media">
            <ul class="list-inline">
              <li>
                <a class="social-media-link fa-stack fa-lg" href="//twitter.com/locationtech">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-twitter fa-stack-1x"></i>
                </a>
              </li>
              <li>
                <a class="social-media-link fa-stack fa-lg" href="//www.facebook.com/groups/401867609865450/">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-facebook fa-stack-1x"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
EOHTML;
  }

}
