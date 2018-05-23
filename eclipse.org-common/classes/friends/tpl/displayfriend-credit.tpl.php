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
<div class="row friends-image">
  <?php foreach ($results as $r) :?>
  <div class="col-sm-12">
    <div class="row">
      <div class="col-xs-8">
        <img typeof="foaf:Image" src="<?php print $this->getGravatarURL($r['mail']);?>" alt="<?php print $r['name'];?>" title="<?php print $r['name'];?>" />
      </div>
      <div class="col-xs-16">
        <p><strong><?php print $r['name'];?></strong></p>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>