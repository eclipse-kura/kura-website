<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
?>
<div class="promoted-plugin">
  <a href="<?php print $this->ad->getUrl2();?>">
    <span class="header clearfix">
      <h3><i class="fa fa-star">&nbsp;</i> Promoted Plugin</h3>
    </span>
    <span class="content clearfix">
      <span class="col-xs-8 pp-image">
        <img src="<?php print $this->ad->getImage();?>" class="img-responsive"/>
      </span>
      <span class="col-xs-16 pp-content">
        <span class="pp-title"><?php print $this->ad->getTitle();?></span>
        <span class="pp-text"><?php print $this->ad->getBody();?></span>
      </span>
    </span>
  </a>
  <a href="<?php print $this->ad->getUrl();?>" class="drag">
    <span class="download clearfix drag_installbutton">
      <i class="fa fa-download pull-left"></i>
      <span class="padding-top-10" style="display:block">INSTALL NOW</span>
      <span class="tooltip show-right"><span class="h3">Drag to Install!</span><br/>Drag to your running Eclipse workspace.</span>
    </span>
  </a>
</div>
