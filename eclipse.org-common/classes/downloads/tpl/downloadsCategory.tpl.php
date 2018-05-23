<?php
/*******************************************************************************
 * Copyright (c) 2014, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
?>

<!-- RUNTIMES PLATFORM -->
<div id="<?php print strtolower(str_replace(" ", "-", $category['title'])); ?>" class="downloads-section">
  <div class="container">
    <h2><span class="downloads-title"><?php print $category['title']; ?></span></h2>
    <div class="row downloads-content-padding">

      <?php if ($key == 'tool_platforms') :?>
        <!-- Installer -->
        <div class="col-md-10th col-sm-24">
          <?php print $this->Installer->output('64bit'); ?>
        </div>
      <?php endif;?>

      <?php print $this->getProjectsList($key); ?>

      <?php if ($key == 'tool_platforms') :?>
        <!-- PROMOTED DOWNLOAD -->
        <div class="col-md-5th col-sm-8 col-xs-16 col-xs-offset-4 col-sm-offset-0 downloads-items downloads-promoted">
          <?php print $this->PromotedDownloads->output('layout_a'); ?>
        </div>
      <?php endif;?>

    </div>
  </div>
</div>