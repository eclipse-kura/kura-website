<?php
/**
 * *****************************************************************************
 * Copyright (c) 2017 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * *****************************************************************************
 */
require_once ('lib/eclipseussblob.class.php');

/**
 * AeriApi class
 *
 * Usage example:
 *
 * include_once('aeriApi.class.php');
 * $GerritApi = new AeriApi();
 * $GerritApi->getRequest("chris.guindon@eclipse.org");
 *
 * @author chrisguindon
 */
class AeriApi extends EclipseUSSBlob {

  /**
   * Constuctor
   */
  function __construct(App $App = NULL) {
    parent::__construct($App);
    $this->setBaseUrl("https://dev.eclipse.org/recommenders/community/aeri/v2/api/v1/reporters");
  }

  public function getProblems($mail = "") {
    // Simple validation before making a request to gerrit
    if (empty($mail)) {
      return array();
    }

    $data = $this->get($mail . "/problems");
    return $data;
  }
}
