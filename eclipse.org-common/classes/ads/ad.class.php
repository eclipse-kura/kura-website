<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *    Eric Poirier (Eclipse Foundation)
 *******************************************************************************/

require_once("campaignImpression.class.php");

/**
 * Advertisement
 *
 * @package eclipse.org-common
 * @subpackage ads
 * @author: Christopher Guindon <chris.guindon@eclipse.org>
 */
class Ad {

  /**
   * The url of an ad
   * @var string
   */
  private $url = "";

 /**
   * The url of an ad
   *
   * Some ads might need two diffent urls.
   *
   * @var string
   */
  private $url2 = "";

  /**
   * The title for the ad
   * @var string
   */
  private $title = "";

  /**
   * The text content of the ad
   * @var unknown
   */
  private $body = "";

  /**
   * The path for the image in the ad
   * @var string
   */
  private $image = "";

  /**
   * The Eclipse campain key to track impressions
   * @var string
   */
  private $campaign = "";

  /**
   * Weight of an ad. If not set, the impressions will be split evenly.
   * @var int
   */
  private $weight = 0;

  /**
   * The Script tag URL
   * (This is mostly for IBM ads)
   * @var string
   */
  private $script_url = "";

  /**
   * The iFrame tag URL
   * (This is mostly for IBM ads)
   * @var string
   */
  private $iframe_url = "";

  /**
   * The image within the iframe
   * (This is mostly for IBM ads)
   * @var string
   */
  private $iframe_image = "";

  /**
   * The type of the ad.
   * @var string
   */
  private $type = "";

  /**
   * The Format of the Ad
   * Image is the default format
   * But can be changed to HTML
   * @var string
   */
  private $format = "image";

  /**
   * The HTML of an ad
   * @var string
   */
  private $html = "";

  /**
   * Get the HTML of an Ad
   *
   * @return string
   */
  public function getHtml() {
    return $this->html;
  }

  /**
   * Set the HTML of an Ad
   *
   * @param $template_path - string
   *
   * @param $variables - array - Define an array of strings to be printed in the html template
   */
  public function setHtml($template_path = "", $variables = array()) {

    // Make sure the template file exist before using it
    if (!file_exists(dirname(__FILE__) . "/" . $template_path)) {
      return FALSE;
    }

    ob_start();
    include $template_path;
    $this->html = ob_get_clean();
  }

  /**
   * Get the Script URL
   *
   * @return string
   */
  public function getScriptUrl() {
    return $this->script_url;
  }

  /**
   * Set the Script URL
   *
   * @param $url - string
   */
  public function setScriptUrl($url = "") {
    $this->script_url = $url;
  }

  /**
   * Get the IFrame URL
   *
   * @return string
   */
  public function getIframeUrl() {
    return $this->iframe_url;
  }

  /**
   * Set the Ifram URL
   *
   * @param $url - string
   */
  public function setIframeUrl($url = "") {
    $this->iframe_url = $url;
  }

  /**
   * Get the IFrame image
   *
   * @return string
   * */
  public function getIframeImage() {
    return $this->iframe_image;
  }

  /**
   * Set the IFrame image
   *
   * @param $url - string
   */
  public function setIframeImage($image = "") {
    $this->iframe_image = $image;
  }

  /**
   * Get the Ad's Format
   *
   * @return string
   * */
  public function getFormat() {
    return $this->iframe_image;
  }

  /**
   * Set the Ad's Format
   * For example, the format could be "image", "html"
   *
   * @param $format - string
   */
  public function setFormat($format = "image") {
    $this->format = $format;
  }

  /**
   * Get the ad's type
   *
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Set the Ad's type
   *
   * Image or html
   *
   * @param $type - string
   */
  public function setType($type = "") {
    $this->type = $type;
  }


  /**
   * Setter for $url
   * @param string $url
   */
  public function setUrl($url = '') {
    $this->url = $url;
  }

  /**
   * Getter for $url
   * @param string $url
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * Setter for $url2
   * @param string $url2
   */
  public function setUrl2($url = '') {
    $this->url2 = $url;
  }

  /**
   * Getter for $url2
   * @param string $url2
   */
  public function getUrl2() {
    return $this->url2;
  }

  /**
   * Setter for $title
   * @param string $title
   */
  public function setTitle($title = "") {
    $this->title = $title;
  }

  /**
   * Getter for $title
   * @param string $title
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * Setter for $body
   * @param string $body
   */
  public function setBody($body = "") {
    $this->body = $body;
  }

  /**
   * Getter for $body
   * @param string $body
   */
  public function getBody() {
    return $this->body;
  }

  /**
   * Setter for $image
   * @param string $image
   */
  public function setImage($image = "") {
    $this->image = $image;
  }

  /**
   * Getter for $image
   * @param string $image
   */
  public function getImage() {
    return $this->image;
  }

  /**
   * Setter for $campaign
   * @param string $campaign
   */
  public function setCampaign($campaign = "") {
    $this->campaign = $campaign;
  }

  /**
   * Getter for $campaign
   * @param string $campaign
   */
  public function getCampaign() {
    return $this->campaign;
  }

  /**
   * Setter for $weight
   * @param string $weight
   */
  public function setWeight($value = 0) {
    if (is_int($value)) {
      $this->weight = $value;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Getter for $weight
   * @param int $weight
   */
  public function getWeight($value = 0) {
    return $this->weight;
  }

  /**
   * Verify if this is a valid Ad
   * @return boolean
   */
  public function validAd() {

    // If we're dealing with an HTML Ad
    if ($this->format == "html" && $this->html == "") {
      return FALSE;
    }

    // If we're dealing with an Image Ad
    if ($this->format == "image" && ($this->url == "" || $this->title  == "" || $this->body  == "" || $this->image  == "")) {
      return FALSE;
    }
    return TRUE;
  }
}