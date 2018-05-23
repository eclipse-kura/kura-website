<?php
/*******************************************************************************
* Copyright (c) 2016 Eclipse Foundation and others.
* All rights reserved. This program and the accompanying materials
* are made available under the terms of the Eclipse Public License v1.0
* which accompanies this distribution, and is available at
* http://www.eclipse.org/legal/epl-v10.html
*
* Contributors:
*    Christopher Guindon (Eclipse Foundation) - Initial implementation
*******************************************************************************/
require_once('restclient.class.php');

/**
 * EclipseUSSBlob class
 *
 * @author chrisguindon
 */
class EclipseUSSBlob extends RestClient{

  /**
   * etag value
   *
   * @var string
   */
  private $etag = '';

  /**
   * Reponse body
   *
   * @var string
   */
  private $body = '';

  function __construct(App $App = NULL) {
    parent::__construct($App);
    $this->setBaseUrl('https://api.eclipse.org');

    switch ($this->getEnvShortName()) {
      case 'local':
        $this->setBaseUrl('https://api.php53.dev.docker');
        break;
      case 'staging':
        $this->setBaseUrl('https://api-staging.eclipse.org');
        break;
    }
  }

  /**
   * Set etag
   *
   * @param string $etag
   */
  public function setEtag($etag = ''){
    $this->etag = $etag;
  }

  /**
   * Get etag
   *
   * @return string
   */
  public function getEtag(){
    return $this->etag;
  }

  /**
   * Set body
   * @param string $body
   */
  public function setBody($body = ''){
    $this->body = $body;
  }

  /**
   * Get body
   * @return string
   */
  public function getBody(){
    return $this->body;
  }

  /**
   * Get Blob
   *
   * @param string $application_token
   * @param string $blob_key
   * @param string $etag
   *
   * @return Response $data
   */
  public function getBlob($application_token = "", $blob_key = "", $username = "") {
    $etag = $this->getEtag();
    if (!empty($etag)) {
      $this->setHeader(array(
        'If-None-Match' => $etag,
      ));
    }

    $url = 'uss/blob/';
    if (!empty($username)) {
      $url =  'account/profile/' . $username . '/blob/';
    }
    $url .= $application_token . '/' . $blob_key;
    $data = $this->get($url);
    $this->unsetHeader('If-None-Match');
    return $data;
  }

  /**
   * Get an index of blobs
   *
   * @param string $application_token
   * @param number $page
   * @param number $pagesize
   *
   * @return Response $data
   */
  public function indexBlob($application_token = "", $page = 1, $pagesize = 20, $username = "") {
    $url = 'uss/blob/' . $application_token;
    if (!empty($username)) {
      $url = 'account/profile/' . $username . '/blob/' . $application_token;
    }
    $data = $this->get($url . '?page=' . $page . '&pagesize=' . $pagesize);
    return $data;
  }

  /**
   * Fetch all blob from an $application_token
   *
   * @param string $application_token
   * @param number $page
   * @param number $pagesize
   *
   * @return Response $data
   */
  public function indexAllBlob($application_token = "", $page = 1, $pagesize = 20, $username = "") {
    $data = $this->indexBlob($application_token, $page, $pagesize, $username);

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
   * Create or update a blob
   *
   * @param string $application_token
   * @param string $blob_key
   * @param string $etag
   * @param unknown $data
   *
   * @return Response $data
   */
  public function putBlob($application_token = "", $blob_key = "", $data = NULL) {
    $fields['value'] = base64_encode($data);
    $etag = $this->getEtag();
    if (!empty($etag)) {
      $this->setHeader(array(
        'If-Match' => $etag,
      ));
    }

    $result = $this->put('uss/blob/' . $application_token . '/' . $blob_key, json_encode($fields));
    $this->unsetHeader('If-Match');
    return $result;
  }

  /**
   * Delete a blob
   *
   * @param string $application_token
   * @param string $blob_key
   * @param string $etag
   *
   * @return Response $data
   */
  public function deleteBlob($application_token = "", $blob_key = "") {
    $this->setHeader(array(
      'If-Match' => $this->getEtag(),
    ));
    $data = $this->delete('uss/blob/' . $application_token . '/' . $blob_key);
    $this->unsetHeader('If-Match');
    return $data;
  }
}