<?php
/**
 * Copyright (c) 2018 Eurotech and/or its affiliates.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * SPDX-License-Identifier: EPL-2.0
 */
 
require_once ($_SERVER['DOCUMENT_ROOT'] .'/eclipse.org-common/classes/themes/quicksilver.class.php');

class KuraTheme extends Quicksilver {

  protected $MoreMenu = NULL;

  public function __construct($App = NULL) {
    parent::__construct($App);
  }

  public function setMoreMenu($MoreMenu) {
    $this->MoreMenu = $MoreMenu;
  }

  protected function _getMoreMenu() {
    return $this->MoreMenu;
  }

  public function getFooterPrexfix() {
  }
}
?>
