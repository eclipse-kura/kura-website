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

<span class="downloads-logo vertical-align"><img height="50" alt="Promoted Downloads" src="<?php print $this->ad->getImage();?>"></span>
<!--<h3 class="downloads-items-header">Deploy IBM Bluemix</h3>-->
<p><?php print $this->ad->getBody();?></p>
<p class="orange small"><i class="fa fa-star" aria-hidden="true"></i> Promoted Download</p>
<p class="visible-xs visible-sm"><a href="<?php print $this->ad->getUrl();?>" class="btn btn-warning btn-xs">Get it</a></p>
<p class="visible-xs visible-sm downloads-items-hover-box-links"><a href="<?php print $this->ad->getUrl();?>">Learn More</a></p>
<div class="downloads-items-hover-box">
  <h4 class="downloads-items-header"><?php print $this->ad->getTitle();?></h4>
  <p class="downloads-items-hover-box-text"><?php print $this->ad->getBody();?></p>
  <p><a href="<?php print $this->ad->getUrl();?>" class="btn btn-warning btn-xs"><i class="fa fa-star" aria-hidden="true"></i> Promoted Download</a></p>
  <p class="downloads-items-hover-box-links"><a href="<?php print $this->ad->getUrl();?>">Learn More</a></p>
</div>