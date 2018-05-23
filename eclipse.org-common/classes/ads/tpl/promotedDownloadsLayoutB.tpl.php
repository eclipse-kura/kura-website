<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
?>

<div class="package-row clearfix zebra promo">
  <div class="row vertical-align-min-md">
    <div class="col-sm-3 icon"><img src="<?php print $this->ad->getImage();?>" width="42" height="42" alt="<?php print $this->ad->getTitle();?>"/></div>
    <div class="col-sm-13 text xs-sm-text-center">
      <h3 class="title">
        <a class="promo-title" href="//eclipse.org/go/<?php print $this->ad->getCampaign();?>" title="<?php print $this->ad->getTitle();?>"><?php print $this->ad->getTitle();?></a>
      </h3>
      <p><?php print $this->ad->getBody();?></p>
    </div>

    <div class="col-sm-8 download">
      <div class="col-sm-9 downloadLink-icon"><i class="fa fa-download"></i></div>
        <div class="col-sm-15 downloadLink-content">
          <div class="text-center">
            <p>
              <a class="orange" href="//eclipse.org/go/<?php print $this->ad->getCampaign();?>" title="<?php print $this->ad->getTitle();?>">
                <span class="text-center"><i class="fa fa-star"></i></span><br/>Promoted<br/>Download
              </a>
            </p>
          </div>
        </div>
    </div>
  </div>
</div>