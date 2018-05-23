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
require_once ('lib/eclipseussblob.class.php');

/**
 * GerritApi class
 *
 * Usage example:
 *
 * include_once('gerritApi.class.php');
 * $GerritApi = new GerritApi();
 * $GerritApi->getRequest("changes/?q=reviewer:chris.guindon@eclipse.org+status:merged");
 *
 * @author chrisguindon
 */
class GerritApi extends EclipseUSSBlob {

  /**
   * Constuctor
   */
  function __construct(App $App = NULL) {
    parent::__construct($App);
    $this->setBaseUrl("https://git.eclipse.org/r");
  }

  /**
   * Fetch all merged reviews for a particular user
   *
   * @param string $endoint
   * @param number $start
   * @param number $pagesize
   * @param bool $get_all
   *
   * @return Response[]
   */
  public function getRequest($endoint = "", $start = 0, $pagesize = 100, $get_all = TRUE) {
    $arguments = array(
      'endpoint' => $endoint,
      'start' => $start,
      'pagesize' => $pagesize
    );

    // Simple validation before making a request to gerrit
    if (empty($arguments['endpoint']) || !is_numeric($arguments['start']) || !is_numeric($arguments['pagesize'])) {
      return array();
    }

    $return = array();
    while ($data = $this->_getAllPages($arguments)) {
      $return[] = $data;
      if (!$data->continue || $get_all === FALSE) {
        break;
      }
      $arguments['start'] += $arguments['pagesize'];
    }
    return $return;
  }

  /**
   * Fetch all pages from gerrit API.
   *
   * @param array $arguments
   *
   * @return Response
   */
  private function _getAllPages($arguments) {
    $data = $this->get($arguments['endpoint'] . "&start=" . $arguments['start'] . "&n=" . $arguments['pagesize']);
    $data->body = json_decode(preg_replace('/^.+\n/', '', $data->body, 1));
    $last_item = end($data->body);
    $data->continue = TRUE;
    if (!isset($last_item->_more_changes) || $last_item->_more_changes === FALSE) {
      $data->continue = FALSE;
    }
    return $data;
  }

}
