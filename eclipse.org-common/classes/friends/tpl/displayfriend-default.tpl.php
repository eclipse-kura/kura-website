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
<div class="row friend-images">
  <?php foreach ($results as $r): ?>
  <div class="col-md-6 col-sm-8 col-xs-12">
    <div class="user-picture">
      <img class="col-xm-24" typeof="foaf:Image" src="<?php print $this->getGravatarURL($r['mail']);?>" alt="<?php print $r['name'];?>" title="<?php print $r['name'];?>" />
      <span class="col-xs-24 donor-name"><?php print $r['name'];?></span>
    </div>
  </div>
  <?php endforeach;?>
</div>