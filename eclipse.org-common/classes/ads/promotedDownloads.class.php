<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
require_once("eclipseAds.class.php");

/**
 * PromotedDownloads
 */
class PromotedDownloads extends EclipseAds {

  public function __construct($source = "") {
    parent::__construct($source);

    // Note: 1 slot = 20
    //       Total = 100

    // IBM B // Keep this Ad for futur reference
    /*$Ad = new Ad();
    $Ad->setTitle('Use your Eclipse IDE to build in the cloud');
    $Ad->setUrl('//bs.serving-sys.com/BurstingPipe/adServer.bs?cn=tf&c=20&mc=click&pli=17277852&PluID=0&ord=[timestamp]');
    $Ad->setScriptUrl('//bs.serving-sys.com/BurstingPipe/adServer.bs?cn=rsb&c=28&pli=17255646&PluID=0&w=32&h=32&ord=[timestamp]');
    $Ad->setIframeUrl('//bs.serving-sys.com/BurstingPipe/adServer.bs?cn=brd&FlightID=17255646&Page=&PluID=0&Pos=1663007610');
    $Ad->setIframeImage('//bs.serving-sys.com/BurstingPipe/adServer.bs?cn=bsr&FlightID=17255646&Page=&PluID=0&Pos=1663007610');
    $Ad->setBody('Love Eclipse? Want to move to Cloud? Bluemix + Eclipse make it easy. Sign up to begin building today!');
    $Ad->setImage('/downloads/images/bluemix-logo-32x-promoted-download.png');
    $Ad->setCampaign('PROMO_DOWNLOAD_IBM_B');
    $Ad->setWeight(10);
    $Ad->setType('ibm');
    $this->newAd($Ad);*/

    // EMPTY
    $Ad = new Ad();
    $Ad->setTitle('EMPTY');
    $Ad->setBody("EMPTY");
    $Ad->setImage("EMPTY");
    $Ad->setCampaign('EMPTY');
    $Ad->setUrl("https://");
    $Ad->setWeight(60);
    $Ad->setType('empty');
    $this->newAd($Ad);

    // JREBEL
    $Ad = new Ad();
    $Ad->setTitle('JRebel for Eclipse IDE');
    $Ad->setBody('See Java Code Changes Instantly. Save Time. Reduce Stress. Finish Projects Faster!');
    $Ad->setImage('/downloads/images/JRebel-42x42-dark.png');
    $Ad->setCampaign('PROMO_DOWNLOAD_JREBEL');
    $Ad->setUrl("https://www.eclipse.org/go/" . $Ad->getCampaign());
    $Ad->setWeight(20);
    $Ad->setType('default');
    $this->newAd($Ad);

    // YATTA
    $Ad = new Ad();
    $Ad->setTitle('Yatta Launcher for Eclipse');
    $Ad->setBody('Install, launch, and share your Eclipse IDE. Stop configuring. Start Coding.');
    $Ad->setImage('/downloads/images/launcherIcon42.png');
    $Ad->setCampaign('PROMO_DOWNLOAD_YATTA');
    $Ad->setUrl("https://www.eclipse.org/go/" . $Ad->getCampaign());
    $Ad->setWeight(20);
    $Ad->setType('default');
    $this->newAd($Ad);
  }

  /**
   * Custom implementation of _build()
   * @see EclipseAds::_build()
   *
   * @param $type - This variable determines help to determine which template file to use
   */
  protected function _build($layout = "", $type = "") {

    ob_start();

    // Layout A is default
    $tpl = "tpl/promotedDownloadsLayoutA.tpl.php";
    if ($type == "ibm") {
      $tpl = "tpl/promotedDownloadsIBMLayoutA.tpl.php";
    }

    // if Layout B is specified
    if ($layout == 'layout_b'){
      $tpl = "tpl/promotedDownloadsLayoutB.tpl.php";
      if ($type == "ibm") {
        $tpl = "tpl/promotedDownloadsIBMLayoutB.tpl.php";
      }
    }
    include($tpl);
    $this->output = ob_get_clean();
  }
}

