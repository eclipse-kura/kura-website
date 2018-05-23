<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/

//if name of the file requested is the same as the current file, the script will exit directly.
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

class DownloadsProject {

  private $title = "";

  private $description = "";

  private $download_url_64_bit = "";

  private $download_url_32_bit = "";

  private $learn_more_url = "";

  private $project_type = "";

  private $logo = "";

  private $attributes = "";

  public function __construct() {
    // Set default classes for container
    $this->setProjectsAttributes('container','class','col-md-5th col-sm-8 col-xs-16 col-xs-offset-4 col-sm-offset-0 downloads-items');
  }

  /**
   * Return the Title of a project
   *
   * @return string
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * Set the Title of a project
   *
   * @param string $title
   */
  public function setTitle($title = "") {
    if (filter_var($title, FILTER_SANITIZE_STRING)) {
      $this->title = $title;
    }
  }

  /**
   * Return the Description of a project
   *
   * @return string
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Set the Description of a project
   *
   * @param string $description
   */
  public function setDescription($description = "") {
    if (filter_var($description, FILTER_SANITIZE_STRING)) {
      $this->description = $description;
    }
  }

  /**
   * Return the 64 bit Download URL of a project
   *
   * @return string
   */
  public function getDownloadUrl64Bit() {
    return $this->download_url_64_bit;
  }

  /**
   * Set the 64 bit Download URL of a project
   *
   * @param string $url
   */
  public function setDownloadUrl64Bit($url = "") {
    if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
      $this->download_url_64_bit = $url;
    }
  }

  /**
   * Return the 32 bit Download URL of a project
   *
   * @return string
   */
  public function getDownloadUrl32Bit() {
    return $this->download_url_32_bit;
  }

  /**
   * Set the 32 bit Download URL of a project
   *
   * @param string $url
   */
  public function setDownloadUrl32Bit($url = "") {
    if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
      $this->download_url_32_bit = $url;
    }
  }

  /**
   * Return the Learn More URL of a project
   *
   * @return string
   */
  public function getLearnMoreUrl() {
    return $this->learn_more_url;
  }

  /**
   * Set the Learn More URL of a project
   *
   * @param string $url
   */
  public function setLearnMoreUrl($url = "") {
    if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
      $this->learn_more_url = $url;
    }
  }

  /**
   * Return the Project Type of a project
   *
   * @return string
   */
  public function getProjectType() {
    return $this->project_type;
  }

  /**
   * Set the Project Type of a project
   *
   * @param string $type
   */
  public function setProjectType($type = "") {
    if (filter_var($type, FILTER_SANITIZE_STRING)) {
      $this->project_type = $type;
    }
  }

  /**
   * Return the Logo of a project
   *
   * @return string
   */
  public function getLogo() {
    return $this->logo;
  }

  /**
   * Set the Logo of a project
   *
   * @param string $logo
   */
  public function setLogo($logo = "") {
    // Check if the string is ending by either png, jpg, jpeg or gif
    if (preg_match("/(.+)\.(png|jpg|jpeg|gif)/i", $logo)) {
      $this->logo = $logo;
    }
  }

  /**
   * Set Attributes for specific elements
   *
   * @param string $element - HTML element
   *
   * @param string $type - HTML attributes (Ex: class)
   *
   * @param string $value
   */
  public function setProjectsAttributes($element = '', $type = 'class', $value = "") {
    $allowed_type = array(
      'class',
      'height',
      'style',
    );

    $type = strtolower($type);
    $value = explode(' ', $value);
    foreach ($value as $val) {
      if (in_array($type, $allowed_type) && is_string($element) && !empty($element) && !empty($val)) {
        switch ($type) {
          case 'class':
            // Append classes instead of overriting them.
            // This way we can set multiple classes for differents contexts.
            if (!isset($this->attributes[$type][$element]) || !in_array($val, $this->attributes[$type][$element])) {
              $this->attributes['class'][$element][] = $val;
            }
            break;

          // For everything else, we only keep the last value set.
          default:
            $this->attributes[$type][$element] = array(
              $val
            );
            break;
        }
      }
    }
  }

  /**
   * Return Attributes based on the Element and the Type
   *
   * @param string $element - html element
   *
   * @param string $type - html attribute - Example: class
   *
   * @return string
   */
  public function getProjectsAttributes($element = "", $type = "") {
    $allowed_type = array(
      'class',
      'height',
      'style',
    );

    // If type is null, we shall return the string with both class and id.
    if (is_null($type)) {
      $html = array();
      if (is_string($element) && !empty($element)) {
        foreach ($allowed_type as $type) {
          if (isset($this->attributes[$type][$element]) && is_array($this->attributes[$type][$element])) {
            $html[] = $type . '="' . implode(' ', $this->attributes[$type][$element]) . '"';
          }
        }
      }

      // Add a space if we have someting to return.
      $prefix = "";
      if (!empty($html)) {
        $prefix = " ";
      }
      return $prefix . implode(" ", $html);
    }

    // If type is set, return only class or id values.
    if (in_array($type, $allowed_type) && is_string($element) && !empty($element)) {
      if (isset($this->attributes[$type][$element]) && is_array($this->attributes[$type][$element])) {
        return implode(' ', $this->attributes[$type][$element]);
      }
    }
    return '';
  }


  /**
   * Determines if a project is valid or not
   *
   * @return bool
   */
  public function validProject($Project = NULL) {
    if ($Project == NULL) {
      return FALSE;
    }
    $logo = $Project->getLogo();
    $download_url = $Project->getDownloadUrl64Bit();
    $title = $Project->getTitle();
    $type = $Project->getProjectType();

    if ($title == "" || $logo ="" || $download_url = "" || $type == "") {
      return FALSE;
    }
    return TRUE;
  }
}