<?php
/**
 * Copyright (c) 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Christopher Guindon (Eclipse Foundation) - Initial implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once ('quicksilver.class.php');
class Eclipse_ide extends Quicksilver {

  /**
   * Constructor
   */
  public function __construct($App = NULL) {
    parent::__construct($App);

    $this->setTheme('eclipse_ide');

    // Set default images
    $image_path = $this->getThemeUrl('solstice') . 'public/images/logo/eclipse-ide/';

    $this->setAttributes('img_logo_default', $image_path . 'eclipse_logo_white.svg', 'src');
    $this->setAttributes('img_logo_eclipse_default', $image_path . 'eclipse_logo_white.svg', 'src');
    $this->setAttributes('img_logo_eclipse_white', $image_path . 'eclipse_logo_white.svg', 'src');
    $this->setAttributes('img_logo_mobile', $image_path . 'eclipse_logo_white.svg', 'src');


    $this->removeAttributes('featured-footer', 'background-secondary');
    $this->setAttributes('featured-footer', 'background-tertiary');

  }
}
