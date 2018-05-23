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
class Polarsys extends baseTheme {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    $this->setTheme('polarsys');
    parent::__construct($App);

    $this->setBaseUrl('https://www.polarsys.org');
    $image_path = $this->getThemeUrl('solstice') . 'public/images/polarsys/';

    // PolarSys Logo
    $this->setAttributes('img_logo_polarsys_sectors', $image_path . 'header-bg-icons.png', 'src');
    $this->setAttributes('img_logo_polarsys_sectors', 'Polarsys.org sectors logo', 'alt');
    $this->setAttributes('img_logo_polarsys_sectors', 'img-responsive', 'class');

    $this->setAttributes('img_logo_default', $image_path . 'logo.png', 'src');
    $this->setAttributes('img_logo_default', 'Polarsys.org logo', 'alt');

    $this->setAttributes('img_logo_mobile', $image_path . 'logo.png', 'src');
    $this->setAttributes('img_logo_mobile', 'Polarsys.org logo', 'alt');
    $this->setAttributes('img_logo_mobile', '161', 'width');

    // Default options
    $this->setDisplayMore(FALSE);
    $this->setDisplayGoogleSearch(FALSE);

    // Set toolbar attributes
    $this->setAttributes('toolbar-container-wrapper', 'toolbar-contrast');

    // Set header attributes
    $this->setAttributes('header-container', 'no-border');
    $this->setAttributes('header-left', 'hidden-xs col-sm-8');
    $this->setAttributes('header-right', 'hidden-xs col-md-6 col-sm-8 pull-right');

    // Set main-menu attributes
    $this->setAttributes('main-menu-wrapper', 'col-sm-24');
    $this->setAttributes('main-menu-ul-navbar', 'navbar-right');

    // Set Footer attributes
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
    $App->setGoogleAnalyticsTrackingCode('UA-910670-9');
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
    $Breadcrumb->insertCrumbAt('2', 'PolarSys', 'https://www.polarsys.org', NULL);
    $this->Breadcrumb = $Breadcrumb;
  }

  /**
   * Get default variables for CFA
   *
   * @return array
   */
  protected function _getCfaButtonDefault() {
    $default['class'] = 'btn btn-huge btn-warning';
    $default['href'] = 'https://www.polarsys.org/polarsys-downloads';

    $default['text'] = '<i class="fa fa-download"></i> Download';
    return $default;
  }

  /**
   * Get Default solstice Menu()
   *
   * @return Menu
   */
  protected function _getMenuDefault() {
    $base_url = $this->getBaseUrl();
    $App = $this->_getApp();
    require_once ($App->getBasePath() . '/system/menu.class.php');
    $Menu = new Menu();
    $Menu->setMenuItemList(array());
    $Menu->addMenuItem("About", $base_url . "/about-us", "_self");
    $Menu->addMenuItem("Solutions", $base_url . "/solutions", "_self");
    $Menu->addMenuItem("Community", $base_url . "/community", "_self");
    $Menu->addMenuItem("Contact Us", $base_url . "/contact-us", "_self");
    $Menu->addMenuItem("Members", $base_url . "/members-list", "_self");
    return $Menu;
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
      return '<a href="https://www.polarsys.org/user/login/"><i class="fa fa-sign-in fa-fw"></i> Log in</a>';
    }
    return parent::getSessionVariables($id);
  }

  /**
   * Get Html of Footer Region 1
   */
  public function getFooterRegion1() {
    return <<<EOHTML
      <h2 class="block-title">PolarSys</h2>
      <ul class="menu nav">
        <li class="first leaf"><a href="//polarsys.org/about-us" title="">About us</a></li>
        <li class="leaf"><a href="//polarsys.org/contact-us" title="">Contact us</a></li>
        <li class="leaf"><a href="//polarsys.org/governance" title="">Governance</a></li>
        <li class="leaf"><a href="//polarsys.org/members%20" title="">Members</a></li>
        <li class="last leaf"><a href="/polarsys-logo" title=""> Logo</a></li>
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
        <li class="first leaf"><a href="//polarsys.org/projects" title="">Projects</a></li>
        <li class="leaf"><a href="//polarsys.org//polarsys.org/og" title="">Blog</a></li>
        <li class="leaf"><a href="//polarsys.org/faq" title="Frequently Asked Questions">FAQ</a></li>
        <li class="leaf"><a href="//polarsys.org/news" title="">News and Events</a></li>
        <li class="last leaf"><a href="//polarsys.org/polarsys-newsletter" title="">Newsletter</a></li>
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
        {$this->getLogo('polarsys_sectors', TRUE)}
      </div>

      <div  id="footer-working-group-right" class="col-sm-10 col-xs-offset-1 col-sm-offset-3 col-md-11 col-md-offset-1 footer-working-group-col">
        {$this->getLogo('eclipse_default', $this->getEclipseUrl())}
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
            <p>PolarSys is a Working Group of The Eclipse Foundation.</p>
            <p id="copyright-text">{$this->getCopyrightNotice()}</p>
          </div>
          <div class="col-md-8 social-media">
            <ul class="list-inline">
              <li>
                <a class="social-media-link fa-stack fa-lg" href="https://twitter.com/EclipseFdn">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-twitter fa-stack-1x"></i>
                </a>
              </li>
              <li>
                <a class="social-media-link fa-stack fa-lg" href="https://plus.google.com/+Eclipse">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-google-plus fa-stack-1x"></i>
                </a>
              </li>
              <li>
                <a class="social-media-link fa-stack fa-lg" href="https://www.facebook.com/eclipse.org">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-facebook fa-stack-1x"></i>
                </a>
              </li>
              <li>
                <a class="social-media-link fa-stack fa-lg" href="https://www.youtube.com/user/EclipseFdn">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-youtube fa-stack-1x"></i>
                </a>
              </li>
              <li>
                <a class="social-media-link fa-stack fa-lg" href="https://www.linkedin.com/company/eclipse-foundation">
                  <i class="fa fa-circle-thin fa-stack-2x"></i>
                  <i class="fa fa-linkedin fa-stack-1x"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
EOHTML;
  }

}
