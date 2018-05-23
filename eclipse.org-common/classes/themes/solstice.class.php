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
class Solstice extends baseTheme {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    $this->setTheme('solstice');
    parent::__construct($App);

    $this->setAttributes('header-left', 'col-sm-5 col-md-4');
    $this->setAttributes('main-menu-wrapper', 'col-sm-14 col-md-16 col-lg-19 reset');
    $this->setAttributes('header-right', 'col-sm-10 col-md-8 col-lg-5 hidden-print hidden-xs pull-right');

    $this->setAttributes('footer1', 'col-sm-6 hidden-print');
    $this->setAttributes('footer2', 'col-sm-6 hidden-print');
    $this->setAttributes('footer3', 'col-sm-6 hidden-print');
    $this->setAttributes('footer4', 'col-sm-6 hidden-print');
  }

  /**
   * Get Html of Footer Region 1
   */
  public function getFooterRegion1() {
    return <<<EOHTML
    <h2 class="section-title">Eclipse Foundation</h2>
    <ul class="nav">
    <li><a href="{$this->getBaseUrl()}org/">About Us</a></li>
    <li><a href="{$this->getBaseUrl()}org/foundation/contact.php">Contact Us</a></li>
    <li><a href="{$this->getBaseUrl()}donate">Donate</a></li>
      <li><a href="{$this->getBaseUrl()}org/documents/">Governance</a></li>
      <li><a href="{$this->getBaseUrl()}artwork/">Logo and Artwork</a></li>
      <li><a href="{$this->getBaseUrl()}org/foundation/directors.php">Board of Directors</a></li>
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
      <li><a href="{$this->getBaseUrl()}legal/privacy.php">Privacy Policy</a></li>
      <li><a href="{$this->getBaseUrl()}legal/termsofuse.php">Terms of Use</a></li>
      <li><a href="{$this->getBaseUrl()}legal/copyright.php">Copyright Agent</a></li>
      <li><a href="{$this->getBaseUrl()}legal/epl-2.0/">Eclipse Public License</a></li>
      <li><a href="{$this->getBaseUrl()}legal/">Legal Resources </a></li>
    </ul>
EOHTML;
  }

  /**
   * Get Html of Footer Region 3
   */
  public function getFooterRegion3() {
    return <<<EOHTML
    <h2 class="section-title">Useful Links</h2>
    <ul class="nav">
      <li><a href="https://bugs.eclipse.org/bugs/">Report a Bug</a></li>
      <li><a href="//help.eclipse.org/">Documentation</a></li>
      <li><a href="{$this->getBaseUrl()}contribute/">How to Contribute</a></li>
      <li><a href="{$this->getBaseUrl()}mail/">Mailing Lists</a></li>
      <li><a href="{$this->getBaseUrl()}forums/">Forums</a></li>
      <li><a href="//marketplace.eclipse.org">Marketplace</a></li>
    </ul>
EOHTML;
  }

  /**
   * Get Html of Footer Region 4
   */
  public function getFooterRegion4() {
    return <<<EOHTML
    <h2 class="section-title">Other</h2>
    <ul class="nav">
      <li><a href="{$this->getBaseUrl()}ide/">IDE and Tools</a></li>
      <li><a href="{$this->getBaseUrl()}projects">Community of Projects</a></li>
      <li><a href="{$this->getBaseUrl()}org/workinggroups/">Working Groups</a></li>
      <li><a href="{$this->getBaseUrl()}org/research/">Research@Eclipse</a></li>
      <li><a href="https://status.eclipse.org">Service Status</a></li>
    </ul>
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
