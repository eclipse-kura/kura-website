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
 * Wayne Beaton (Eclipse Foundation)- initial API and implementation
 * Christopher Guindon (Eclipse Foundation) - minor changes
 * *****************************************************************************
 */

/**
 * This class represents a forge instance (e.g.
 * Eclipse, LocationTech,
 * or PolarSys). The intent is to try and centralize the notion of a
 * forge rather than have bits of code here and there to handle all of
 * the different forges (i.e. to reduce the long term maintenance burden).
 */
class Forge {

  /**
   * Forge data
   *
   * @var array
   */
  public $data = array();

  /**
   * List of all forges
   *
   * @var array
   */
  private static $forges = array();

  /**
   * Constructor
   *
   * @param unknown $data
   */
  function __construct($data = array()) {
    $this->data = $data;
  }

  /**
   * Get $forges
   *
   * @return Forge[]
   */
  static function getForges() {
    if (!empty(self::$forges)) {
      return self::$forges;
    }

    $forges = array(
      'eclipse' => array(
        'id' => 'eclipse',
        'name' => 'Eclipse',
        'url' => 'https://projects.eclipse.org',
        'hudson_domain' => array(
          'hudson.eclipse.org',
          'ci.eclipse.org'
        )
      ),
      'locationtech' => array(
        'id' => 'locationtech',
        'name' => 'LocationTech',
        'url' => 'https://www.locationtech.org',
        'hudson_domain' => array(
          'hudson.locationtech.org'
        )
      ),
      'polarsys' => array(
        'id' => 'polarsys',
        'name' => 'PolarSys',
        'url' => 'https://www.polarsys.org',
        'hudson_domain' => array(
          'hudson.polarsys.org'
        )
      )
    );

    foreach ($forges as &$forge) {
      $forge = new self($forge);
    }

    return self::$forges = $forges;
  }

  /**
   * Get specific forge
   *
   * @param string $id
   *
   * @return Forge
   */
  static function getForge($id) {
    $forges = self::getForges();
    if (isset($forges[$id])) {
      return $forges[$id];
    }
    return array();
  }

  /**
   * Get default forge
   *
   * @return Forge
   */
  static function getDefault() {
    return self::getForge('eclipse');
  }

  /**
   * Get forge from project id
   *
   * @param unknown $id
   *
   * @return NULL|Forge
   */
  static function getForgeForProjectId($id) {
    $segments = explode('.', $id);
    if ($segments[0] == 'foundation-internal') {
      return null;
    }

    foreach (self::getForges() as $id => $forge) {
      if ($id == $segments[0]) {
        return $forge;
      }
    }

    return self::getDefault();
  }

  /**
   * Get forge id
   *
   * @return string
   */
  function getId() {
    if (isset($this->data['id'])) {
      return $this->data['id'];
    }
    return "";
  }

  /**
   * Get forge name
   *
   * @return string
   */
  function getName() {
    if (isset($this->data['name'])) {
      return $this->data['name'];
    }
    return "";
  }

  /**
   * Get forge url
   *
   * @return string
   */
  function getUrl() {
    if (isset($this->data['url'])) {
      return $this->data['url'];
    }
    return "";
  }

  /**
   * Get hudson url
   *
   * @return array
   */
  function getHudsonDomain() {
    if (isset($this->data['hudson_domain'])) {
      return $this->data['hudson_domain'];
    }
    return array();
  }

  /**
   * Get local project id based off the forge
   *
   * @param unknown $id
   * @return unknown|NULL
   */
  function getLocalProjectId($id) {
    if ($this->isEclipseForge()) {
      return $id;
    }

    $forgeId = $this->getId();
    if (preg_match("/^$forgeId\.(.*)$/", $id, $matches)) {
      return $matches[1];
    }

    return null;
  }

  /**
   * Verify if current forge is Eclipse
   *
   * @return boolean
   */
  function isEclipseForge() {
    return $this->getId() == 'eclipse';
  }

  /**
   * Validate if url is a valid CI url
   *
   * @param string $url
   * @return boolean
   */
  public function isValidCIUrl($url = "") {
    // Verify the syntax of the given URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      return FALSE;
    }

    // get forge class from eclipse.org-common
    $parsed_url = parse_url(strtolower($url));
    if (!isset($parsed_url['path'])) {
      // no path specified
      return FALSE;
    }
    $host_parts = explode('.', $parsed_url['host']);

    // main domain name should be 2nd from last
    if (count($host_parts) < 2) {
      // the build link url is very unlikely to be valid
      return FALSE;
    }

    // check host is in the list of accepted domains
    $hudson_domain = $this->getHudsonDomain();
    if (!in_array($parsed_url['host'], $hudson_domain)) {
      return FALSE;
    }

    // break the path into parts
    $path_parts = explode('/', trim($parsed_url['path'], '/'));
    if (empty($path_parts[0])) {
      // first part is empty, parsed path had only contained '/'
      return FALSE;
    }

    // check path size, should be 1 or 3 parts
    if (count($path_parts) < 1 || count($path_parts) > 3) {
      // path longer than expected
      return FALSE;
    }

    if (count($path_parts) === 3) {
      // some projects have hudson for first path part - should be project name
      // 2nd part should be job if job specified (3rd part is build job name)
      if ($path_parts[0] === 'hudson' || $path_parts[1] !== 'job') {
        return FALSE;
      }
    }

    return TRUE;
  }

}