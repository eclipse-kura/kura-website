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
 * CommitterPaperwork class
 *
 * Usage example:
 *
 * include_once('committerpaperwork.class.php');
 * $CommitterPaperwork = new CommitterPaperwork();
 * $CommitterPaperwork->loginSSO();
 *
 * @author chrisguindon
 */
class CommitterPaperwork extends EclipseUSSBlob {

  private $data = array();

  /**
   * Class constructor
   */
  function __construct(App $App = NULL) {
    parent::__construct($App);
  }

  /**
   * Create committer_paperwork record (POST)
   *
   * @param string $username
   * @param array $data
   */
  public function createCommitterPaperwork($username = NULL, $data = array()) {
    return $this->post('committer/paperwork/' . $username, json_encode($data));
  }

  /**
   * Delete CommitterPaperwork (DELETE)
   *
   * @param string $username
   * @param unknown $id
   */
  public function deleteCommitterPaperwork($username = "", $id = "", $etag = "") {
    $response = $this->delete('committer/paperwork/' . $username . '/' . $id);
    $this->unsetHeader('If-Match');
    return $response;
  }

  /**
   * Retrieve committer_paperwork (GET)
   *
   * @param string $username
   * @param string $id
   */
  public function retrieveCommitterPaperwork($username = "", $id = "", $etag = "") {

    if (!empty($etag)) {
      $this->setHeader(array(
        'If-Match' => $etag
      ));
    }

    $response = $this->get('committer/paperwork/' . $username . '/' . $id);
    if (isset($response->code) && $response->code == 200) {
      $data = json_decode($response->body);
      $this->data[$data->id] = $data;
    }

    $this->unsetHeader('If-Match');
    return $response;
  }

  /**
   * Update committer paperwork record
   * @param unknown $username
   * @param unknown $id
   * @param array $data
   * @param string $etag
   */
  public function updateCommitterPaperwork($username = NULL, $id = NULL, $data = array(), $etag = "") {

    $this->setHeader(array(
      'If-Match' => '"' . $etag . '"',
    ));

    $response = $this->put('committer/paperwork/' . $username . '/' . $id, json_encode($data));
    $this->unsetHeader('If-Match');
    return $response;
  }

  /**
   * Fetch commiter Paperwork index
   *
   * @param unknown $username
   * @param array $params
   *
   * @return array
   */
  public function indexCommitterPaperwork($username = NULL, $params = array()) {

    $url = 'committer/paperwork';
    if (!is_null($username) && is_string($username)) {
      $url .= '/' . $username;
    }

    $query = http_build_query($params);
    $response = $this->get($url . '?' . $query);
    return $response;
  }

  /**
   * Fetch all Committer Paperwork index
   *
   * @param unknown $username
   * @param array $params
   *
   * @return array
   */
  public function indexAllCommitterPaperwork($username = NULL, $params = array()) {

    $data = $this->indexCommitterPaperwork($username, $params);

    $return = array();
    $return[] = $data;
    if (!isset($data->error) && !empty($data->body) && $data) {
      while ($data = $this->_getNextPage($data)) {
        $return[] = $data;
      }
    }
    return $return;
  }

  /**
   * Start the committer provisioning process. (GET)
   *
   * @param string $username
   * @param int $id
   */
  public function targetedActionStartProvisioning($username = NULL, $id = "", $body = array()) {
    return $this->post('committer/paperwork/' . $username . '/provisioning/' . $id, json_encode($body));
  }

  /**
   * Retire a committer from a specific project. (GET)
   *
   * @param string $username
   * @param int $id
   */
  public function targetedActionRetireCommitter($username = NULL, $body = array()) {
    return $this->post('committer/paperwork/' . $username . '/retire', json_encode($body));
  }

  /**
   * Validate a username
   *
   * @param string $username
   *
   * @return bool
   */
  function validateCommitterPaperworkUsername($username) {
    if (empty($username) || !is_string($username)) {
      return FALSE;
    }

    // Validate that the username is in LDAP
    require_once("/home/data/httpd/eclipse-php-classes/system/ldapconnection.class.php");
    $LDAPConnection = new LDAPConnection();
    $dn = $LDAPConnection->getDNFromUID($username);

    if (empty($dn)) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Validate an id
   *
   * @param string $id
   *
   * @return bool
   */
  function validateCommitterPaperworkId($id) {
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (empty($id) && !is_int($id) && $id !== 0) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Validate an etag
   *
   * @param string $etag
   *
   * @return bool
   */
  function validateCommitterPaperworkEtag($etag) {
    if (empty($etag) || !is_string($etag)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Validate a page number
   *
   * @param int $page
   *
   * @return bool
   */
  function validateCommitterPaperworkPage($page) {
    if (!is_int($page)) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Validate a pagesize
   *
   * @param string $pagesize
   *
   * @return bool
   */
  function validateCommitterPaperworkPagesize($pagesize) {
    if (!is_int($pagesize) || $pagesize > 100) {
      return FALSE;
    }

    return $pagesize;
  }

  /**
   * Validate fields
   *
   * @param array $data
   *
   * @return bool
   */
  function validateCommitterPaperworkFields($data = array()) {

    // If there are no parameters passed this is still valid
    // but we can quit here
    if (empty($data) || !isset($data['parameters'])) {
      return TRUE;
    }

    $float_fields = array(
      'id',
      'election_nid',
      'election_status',
      'committer_paperwork_nid',
      'committer_paperwork_status'
    );

    $string_fields = array(
      'project_id',
      'forge',
    );

    foreach ($data['parameters'] as $data_field_name => $field_value) {
      // Verify the the field name exist in the float and string fields
      if (!in_array($data_field_name, $float_fields) && !in_array($data_field_name, $string_fields)) {
        return FALSE;
      }

      foreach ($float_fields as $float_field_name) {
        // If the default field name exist in the data array,
        // Make sure it validates with the appropriate filter
        if (isset($data[$float_field_name]) && filter_var($data[$float_field_name], FILTER_VALIDATE_FLOAT) === FALSE) {
          return FALSE;
        }
      }

      // Making sure we are dealing with strings
      foreach ($string_fields as $string_field_name) {
        if (isset($data[$string_field_name]) && !is_string($data[$string_field_name])) {
          return FALSE;
        }
      }
    }
    return TRUE;
  }
}