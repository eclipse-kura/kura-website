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

require_once ('solstice.class.php');
class Quicksilver extends solstice {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    parent::__construct($App);
    $this->setTheme('quicksilver');

    $this->resetAttributes('header-left');
    $this->resetAttributes('main-menu-wrapper');
    $this->resetAttributes('header-right');
    $this->resetAttributes('main-sidebar');

    $this->setAttributes('header-left', 'col-sm-5 col-md-4');
    $this->setAttributes('main-menu-wrapper', 'col-sm-15 col-md-15 reset margin-top-10');
    $this->setAttributes('main-menu-wrapper-no-header-right', 'col-sm-24 col-md-19 reset margin-top-10');
    $this->setAttributes('header-right', 'col-sm-4 col-md-5 text-right hidden-print hidden-xs pull-right margin-top-10');

    $this->setAttributes('navbar-main-menu', 'float-right');
    $this->removeAttributes('main', 'no-promo');
    $this->setAttributes('breadcrumbs', 'breadcrumbs-default-margin');
    $this->setAlternateLayout();
    $this->removeAttributes('img_logo_default', 'img-responsive', 'class');
    $this->setAttributes('img_logo_default', '160', 'width');
    $this->setAttributes('img_logo_mobile', '160', 'width');

    // Featured footer
    $this->setAttributes('featured-footer', 'featured-footer featured-footer-newsletter background-secondary');

    // Set attributes on main sidebar
    $this->setAttributes('main-sidebar', 'main-sidebar-default-margin');
  }

  public function setAlternateLayout($enable = FALSE) {
   $image_path = '//www.eclipse.org' . $this->getThemeUrl('solstice') . 'public/images/logo/';
   $default_logo = 'eclipse-foundation-white-orange.svg';
    if ($enable) {
      $default_logo = 'eclipse-foundation-grey-orange.svg';
      $this->setAttributes('body', 'alternate-layout');
    }
    else {
      $this->removeAttributes('body', 'alternate-layout');
    }

    // Set default images
    $this->setAttributes('img_logo_default', $image_path . $default_logo, 'src');
    $this->setAttributes('img_logo_eclipse_default', $image_path . $default_logo, 'src');
    $this->setAttributes('img_logo_eclipse_white', $image_path . 'eclipse-foundation-white.svg', 'src');
    $this->setAttributes('img_logo_mobile', $image_path . $default_logo, 'src');
  }

  /**
   * Implement BaseTheme::_getHeaderRight();
   *
   * Hide headerRight div if empty
   *
   * {@inheritDoc}
   * @see BaseTheme::_getHeaderRight()
   */
  public function getHeaderRight(){
    $cfa_button = $this->getCfaButton();
    if (!$this->getDisplayHeaderRight() || empty($cfa_button)) {
      $this->setDisplayHeaderRight(FALSE);
      $this->resetAttributes('main-menu-wrapper');
      $this->setAttributes('main-menu-wrapper', 'col-sm-19 col-md-20 reset');

      if (strtolower($this->getLayout()) !== 'barebone') {
        $this->setAttributes('main-menu-wrapper', 'margin-top-10');
      }
      return "";
    }

   return <<<EOHTML
      <div{$this->getAttributes('header-right')}>
        {$this->getCfaButton()}
      </div>
EOHTML;
  }

  /**
   * Implement BaseTheme::getHeaderLeft();
   *
   * Reset header left classes for thin layout
   *
   * {@inheritDoc}
   * @see BaseTheme::getHeaderLeft()
   */
  public function getHeaderLeft(){
    $layout_types = array(
      'thin',
      'thin-header',
      'thin-with-footer-min'
    );
    $cfa_button = $this->getCfaButton();
    if (in_array($this->getLayout(), $layout_types) && (!$this->getDisplayHeaderRight() || empty($cfa_button))) {
      $this->resetAttributes('header-left', 'class');
      $this->setAttributes('header-left', 'col-sm-5 col-md-4');
    }
    return <<<EOHTML
      <div{$this->getAttributes('header-left')}>
        {$this->getLogo('default', TRUE)}
      </div>
EOHTML;
  }

  /**
   * Get Menu Suffix
   *
   * @return string
   */
  protected function _getMenuSuffix(){
    $suffix_items = array();

    $google_search = $this->getGoogleSearch();
    if (!empty($google_search)) {
      $suffix_items[] = <<<EOHTML
      <li class="dropdown eclipse-more main-menu-search">
        <a data-toggle="dropdown" class="dropdown-toggle" role="button"><i class="fa fa-search"></i> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li>
            <!-- Content container to add padding -->
            <div class="yamm-content">
              <div class="row">
                <div class="col-sm-24">
                  <p>Search</p>
                  {$this->getGoogleSearch()}
                </div>
              </div>
            </div>
          </li>
        </ul>
      </li>
EOHTML;
    }

   return implode('',$suffix_items);
  }

  /**
   * Implement BaseTheme::getFooterPrexfix()
   *
   * {@inheritDoc}
   * @see BaseTheme::getFooterPrexfix()
   */
  public function getFooterPrexfix() {
    return <<<EOHTML
    <!-- Sign Up to our Newsletter -->
    <div{$this->getAttributes('featured-footer')}>
      <div class="container">
        <p><i data-feather="mail" stroke-width="1"></i></p>
        <h2>Sign up to our Newsletter</h2>
        <p>A fresh new issue delivered monthly</p>
        <form action="https://www.eclipse.org/donate/process.php" method="post" target="_blank">
          <div class="form-group">
            <input type="hidden" name="type" value="newsletter">
            <input type="email" value="" name="email" class="textfield-underline form-control" id="mce-EMAIL" placeholder="Email">
          </div>
          <input type="submit" value="Subscribe" name="subscribe" class="button btn btn-warning">
        </form>
      </div>
    </div>
EOHTML;
  }
}
