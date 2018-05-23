<?php
/**
 * *****************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * *****************************************************************************
 */

class FeedParser {

  /**
   * List of rss paths
   *
   * @var string
   */
  private $path = array();

  /**
   * Default display count
   *
   * @var integer
   */
  private $count = 4;

  /**
   * Flag to only display press_releases
   *
   * @var string
   */
  private $press_release = FALSE;

  /**
   * Array of news item
   *
   * @var array
   */
  private $items = array();

  /**
   * View more link values
   *
   * @var string
   */
  private $view_more = array();

  /**
   * Link to feedburner feed
   *
   * @var string
   */
  private $rss_link = "";

  /**
   * Default date_format
   *
   * @var string
   */
  private $date_format = "Y/m/d";

  /**
   * Default news item limit
   *
   * @var integer
   */
  private $limit = 200;

  /**
   * Set date format
   *
   * @param string $format
   */
  public function setDateFormat($format = "Y/m/d") {
    $this->date_format = $format;
    return TRUE;
  }

  /**
   * Get date format
   *
   * @return string
   */
  public function getDateFormat() {
    return $this->date_format;
  }

  /**
   * Set path for RSS feed
   *
   * @param string $url
   */
  public function addPath($path = "") {
    if (is_string($path)) {
      $this->path[] = $path;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get path for RSS feed
   *
   * @return string
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Set RSS link
   *
   * @param string $url
   */
  public function setRssLink($url = "") {
    if (is_string($url)) {
      $this->rss_link = $url;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get RSS link
   *
   * @param string $html
   *
   * @return string
   */
  public function getRssLink() {
    return $this->rss_link;
  }

  /**
   * Get RSS link
   *
   * @param string $html
   *
   * @return string
   */
  public function getRssLinkHTML() {
    $url = $this->getRssLink();
    if (empty($url)) {
      return "";
    }
    return '<a href="' . $url . '" class="link-rss-feed  orange" title="Subscribe to our RSS-feed"><i class="fa fa-rss"></i> <span>Subscribe to our RSS-feed</span></a>';
  }

  /**
   * Set Press Release Flag
   *
   * @param string $flag
   */
  public function setPressRelease($flag = FALSE) {
    if (is_bool($flag)) {
      $this->press_release = $flag;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get Press Release Flag
   *
   * @return string
   */
  public function getPressRelease() {
    return $this->press_release;
  }

  /**
   * Set item count
   *
   * @param number $count
   */
  public function setCount($count = 4) {
    if (is_numeric($count)) {
      $this->count = $count;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get item count
   *
   * @return number $count
   */
  public function getCount() {
    return $this->count;
  }

  /**
   * Set description limit
   *
   * @param number $limit
   */
  public function setLimit($limit = 200) {
    if (is_numeric($limit)) {
      $this->limit = $limit;
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get description limit
   *
   * @return number $limit
   */
  public function getLimit() {
    return $this->limit;
  }

  /**
   * Set view_more link
   *
   * @param string $url
   * @param string $caption
   * @param string $prefix
   */
  public function setViewMoreLink($url = "", $caption = 'View all', $prefix = '> ') {
    if (is_string($url) && is_string($caption) && is_string($prefix)) {
      $this->view_more = array(
        'url' => $url,
        'caption' => $caption,
        'prefix' => $prefix
      );
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Get ViewMore link
   *
   * @return string
   */
  public function getViewMoreLink() {
    $view_more = $this->view_more;

    if (empty($view_more['url']) || empty($view_more['caption'])) {
      return array();
    }

    if (!isset($view_more['prefix'])) {
      $view_more['prefix'] = "";
    }

    return $view_more;
  }

  /**
   * Get view_more link (HTML)
   *
   * @return string
   */
  public function getViewMoreLinkHTML() {
    $view_more = $this->getViewMoreLink();
    if (empty($view_more)) {
      return "";
    }
    return $view_more['prefix'] . '<a href="' . $view_more['url'] . '">' . $view_more['caption'] . '</a>';
  }

  /**
   * Html Output
   *
   * @return string
   */
  public function output() {
    if (!$this->_parseFeeds()) {
      return '<p>This news feed is currently empty. Please try again later.</p>';
    }

    $output = '';
    if (!empty($this->items)) {
      $output = '<ul class="news-list-media list-unstyled">';
      foreach ($this->items as $item) {
        $output .= '<li><a href="' . $item['link'] . '" class="media media-link">';
        $output .= '<p class="media-date">' . $item['date'] . '</p><h4 class="media-heading">' . $item['title'] . '</h4>';
        if ($this->getLimit() > 0) {
          $output .= '<p class="media-text">' . $item['description'] . '</p>';
        }
        $output .= ' </a></li>';
      }
      $output .= '</ul>';
    }

    return $output;
  }

  /**
   * Parse the Feed
   *
   * @return boolean
   */
  private function _parseFeeds() {
    $path = $this->getPath();
    if (empty($path)) {
      return FALSE;
    }

    $count = 0;
    foreach ($path as $p) {
      if (file_exists($p)) {
        $feed = simplexml_load_file($p);
      }

      if (isset($feed) && $feed != FALSE) {
        foreach ($feed->channel->item as $item) {
          $feed_array = array();
          if ($count >= $this->count) {
            break;
          }

          if ($this->getPressRelease() && $item->pressrelease != 1) {
            continue;
          }

          $date = strtotime((string) $item->pubDate);
          $date = date($this->getDateFormat(), $date);

          $description = (string) strip_tags($item->description);
          if (strlen($description) > $this->getLimit()) {
            $description = substr($description, 0, $this->limit);
            $description .= "...";
          }

          $item_array = array(
            'title' => (string) $item->title,
            'description' => $description,
            'link' => (string) $item->link,
            'date' => $date
          );

          $this->items[] = $item_array;
          $count++;
        }
      }
    }

    if (!empty($this->items)) {
      return TRUE;
    }
    return FALSE;
  }

}
