<?php
/**
 * Copyright (c) 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Eric Poirier (Eclipse Foundation) - Initial implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once ('quicksilver.class.php');
class Jakarta extends Quicksilver {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    parent::__construct($App);

    $this->setTheme('jakarta');

    // Set default images
    $image_path = $this->getThemeUrl('solstice') . 'public/images/logo/';
    $this->setAttributes('img_logo_default', $image_path . 'jakarta-ee-white.svg', 'src');
    $this->setAttributes('img_logo_default', 'Eclipse.org logo', 'alt');
    $this->setAttributes('img_logo_default', 'logo-eclipse-default img-responsive hidden-xs', 'class');

    $this->setAttributes('img_logo_eclipse_default', $image_path . 'jakarta-ee-white.svg', 'src');
    $this->setAttributes('img_logo_eclipse_default', 'Eclipse.org logo', 'alt');
    $this->setAttributes('img_logo_eclipse_default', 'img-responsive hidden-xs', 'class');

    $this->setAttributes('img_logo_eclipse_white', $image_path . 'jakarta-ee-white.svg', 'src');
    $this->setAttributes('img_logo_eclipse_white', 'Eclipse.org black and white logo', 'alt');
    $this->setAttributes('img_logo_eclipse_white', 'logo-eclipse-white img-responsive');

    $this->setAttributes('img_logo_mobile', $image_path . 'jakarta-ee-white.svg', 'src');
    $this->setAttributes('img_logo_mobile', 'Eclipse.org logo', 'alt');
    $this->setAttributes('img_logo_mobile', 'logo-eclipse-default-mobile img-responsive', 'class');

  }


  /**
   * Implement BaseTheme::getFooterPrexfix()
   *
   * {@inheritDoc}
   * @see BaseTheme::getFooterPrexfix()
   */
  public function getFooterPrexfix() {
    return "";
  }
}
