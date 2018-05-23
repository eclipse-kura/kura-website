<?php
/**
 * Copyright (c) 2016, 2018 Eclipse Foundation and others.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Eric Poirier (Eclipse Foundation) - initial API and implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once("eclipseAds.class.php");

class DownloadsBannerAd extends EclipseAds {

  public function __construct() {
    parent::__construct();

    $campaign = "";
    $content['body'] ="";
    $content['banner_styles'] = "";

    if ((time() >= strtotime("2018/02/26") && time() < strtotime("2018/04/05")) || (time() >= strtotime("2018/04/30") && time() < strtotime("2018/05/11"))) {
      $content['body'] ="Register now for FOSS4G NA 2018 ~ St. Louis, Missouri ~ May 14 - 17, 2018";
      $campaign = "PROMO_F4G2018_DOWNLOADS_PAGE";
    }

    if ((time() >= strtotime("2018/04/16") && time() < strtotime("2018/04/30")) || (time() >= strtotime("2018/05/14") && time() < strtotime("2018/06/13"))) {
      $content['body'] ="Register now for EclipseCon France 2018 ~ Toulouse, France ~ June 13 - 14, 2018";
      $campaign = "PROMO_ECF2018_DOWNLOADS_PAGE";
    }

    $content['button_text'] = "Register Today!";
    $content['button_url'] = $campaign;

    // Create the ad
    $Ad = new Ad();
    $Ad->setTitle('Downloads banner ad');
    $Ad->setCampaign($campaign);
    $Ad->setFormat("html");
    $Ad->setHtml('tpl/downloadsBannerAd.tpl.php', $content);
    $Ad->setType('paid');
    $Ad->setWeight('100');
    $this->newAd($Ad);

  }

  /**
   * Custom implementation of _build()
   * @see EclipseAds::_build()
   *
   * @param $type - This variable determines help to determine which template file to use
   */
  protected function _build($layout = "", $type = "") {

    // Check if the ad should be printed depending on the date
    if ((time() >= strtotime("2018/02/26") && time() < strtotime("2018/04/05")) || (time() >= strtotime("2018/04/30") && time() < strtotime("2018/05/11"))) {
      $this->output = $this->ad->getHtml();
    }

    if ((time() >= strtotime("2018/04/16") && time() < strtotime("2018/04/30")) || (time() >= strtotime("2018/05/14") && time() < strtotime("2018/06/13"))) {
      $this->output = $this->ad->getHtml();
    }
  }
}