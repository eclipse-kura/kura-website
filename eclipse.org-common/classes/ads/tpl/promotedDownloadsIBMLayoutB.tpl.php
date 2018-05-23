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

<div class="package-row clearfix promo">
  <div class="row vertical-align-min-md">
    <div class="col-xs-3 icon">
      <!-- iframe/script/href/image tag -->
      <script src="<?php print $this->ad->getScriptUrl();?>"></script>
      <noscript>
        <a href="<?php print $this->ad->getIframeUrl;?>" target="_blank">
          <img src="<?php print $thi->ad->getIframeImage;?>" width=32 height=32 alt="Click Here" border=0>
        </a>
      </noscript>
    </div>
    <div class="col-sm-13 text xs-sm-text-center">
      <h3>
        <a href="<?php print $this->ad->getUrl();?>" target="_blank"><?php print $this->ad->getTitle();?>
          <img src="<?php print $this->ad->getImage();?>" width=1 height=1 alt=" " border=0>
        </a>
      </h3>
      <p><?php print $this->ad->getBody();?></p>
    </div>
    <div class="col-sm-8 download">
      <div class="col-sm-9 downloadLink-icon"><i class="fa fa-download"></i></div>
      <div class="col-sm-15 downloadLink-content">
        <div class="text-center">
          <p>
            <a class="orange" href="<?php print $this->ad->getUrl();?>" target="_blank">
              <i class="fa fa-star"></i><br/>Promoted<br/>Download</a><br/></p>
        </div>
      </div>
    </div>
  </div>
</div>