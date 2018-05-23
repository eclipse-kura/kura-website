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
 * PromoAds
 */
class PromoAds extends EclipseAds {

  public function __construct($source = "") {
    parent::__construct($source);

    // PAID

    // Froglogic
    $Ad = new Ad();
    $Ad->setTitle('FrogLogic');
    $Ad->setUrl('https://www.eclipse.org/go/PAID_FROGLOGIC');
    $Ad->setImage('/membership/promo/images/froglogic.gif');
    $Ad->setType('paid');
    $Ad->setWeight('9');
    $this->newAd($Ad);

    // JRebel
    $Ad = new Ad();
    $Ad->setTitle('JREBEL');
    $Ad->setUrl('https://www.eclipse.org/go/PAID_JREBEL_A');
    $Ad->setImage('/membership/promo/images/O4E-200x200-banner-1.jpg');
    $Ad->setType('paid');
    $Ad->setWeight('9');
    $this->newAd($Ad);

    // OTHER ADS

    // CA
    $Ad = new Ad();
    $Ad->setTitle('CA');
    $Ad->setUrl('/membership/showMember.php?member_id=655');
    $Ad->setImage('/membership/scripts/get_image.php?size=small&id=655');
    $Ad->setType('strategic');
    $Ad->setWeight('13');
    $this->newAd($Ad);

    // Oracle
    $Ad = new Ad();
    $Ad->setTitle('Oracle');
    $Ad->setUrl('https://www.eclipse.org/go/PROMO_ORACLE');
    $Ad->setImage('/membership/promo/images/oepe_ad_200x200.jpg');
    $Ad->setType('strat_ad');
    $Ad->setWeight('13');
    $this->newAd($Ad);

    // Actuate
    $Ad = new Ad();
    $Ad->setTitle('Actuate');
    $Ad->setUrl('https://www.eclipse.org/go/ACTUATEBP_B');
    $Ad->setImage('/membership/promo/images/actuate_puzzle_200x200.png');
    $Ad->setType('strat_ad');
    $Ad->setWeight('13');
    $this->newAd($Ad);

    // IBM
    $Ad = new Ad();
    $Ad->setTitle('IBM');
    $Ad->setUrl('https://www.eclipse.org/go/IBM_JAZZ');
    $Ad->setImage('/membership/promo/images/ibm200x200-eclipse_orion.png');
    $Ad->setType('strat_ad');
    $Ad->setWeight('13');
    $this->newAd($Ad);

    // SAP
    $Ad = new Ad();
    $Ad->setTitle('SAP');
    $Ad->setUrl('https://www.eclipse.org/go/PROMO_SAP');
    $Ad->setImage('/membership/promo/images/sap200x200.jpg');
    $Ad->setType('strat_ad');
    $Ad->setWeight('13');
    $this->newAd($Ad);

    // Itemis
    $Ad = new Ad();
    $Ad->setTitle('Itemis');
    $Ad->setUrl('https://www.eclipse.org/go/PROMO_ITEMIS');
    $Ad->setImage('/membership/promo/images/xtext_200x200.gif');
    $Ad->setType('strat_ad');
    $Ad->setWeight('5.5');
    $this->newAd($Ad);

    // Obeo
    $Ad = new Ad();
    $Ad->setTitle('Obeo');
    $Ad->setUrl('https://www.eclipse.org/go/PROMO_OBEO');
    $Ad->setImage('/membership/promo/images/obeo_sirius.png');
    $Ad->setType('strat_ad');
    $Ad->setWeight('5.5');
    $this->newAd($Ad);

    //Eclipsecon
    $Ad = new Ad();
    $Ad->setTitle('Eclipsecon');
    $Ad->setFormat('html');
    $Ad->setHtml('tpl/eclipseconAd.tpl.php');
    $Ad->setType('strat_ad');
    $Ad->setWeight('20');
    $this->newAd($Ad);

  }

/**
   * Custom implementation of _build()
   * @see EclipseAds::_build()
   *
   * @param $type - This variable determines help to determine which template file to use
   */
  protected function _build($layout = "", $type = "") {


    if ($this->ad->getFormat() == "html") {
      $this->output = $this->ad->getHtml();
    }
    $this->output = ob_get_clean();
  }
}