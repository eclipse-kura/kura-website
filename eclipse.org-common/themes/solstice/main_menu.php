<?php
/*******************************************************************************
 * Copyright (c) 2014, 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
?>
<div<?php print $this->getAttributes('main-menu-wrapper');?>>
  <div<?php print $this->getAttributes('main-menu');?>>
    <div<?php print $this->getAttributes('navbar-main-menu');?>>
      <ul<?php print $this->getAttributes('main-menu-ul-navbar');?>>
        <?php print $this->getMenu()?>
        <?php if ($this->getDisplayMore()) :?>
          <?php print $this->getMoreMenu('mobile')?>
          <!-- More -->
          <li class="dropdown eclipse-more hidden-xs">
            <a data-toggle="dropdown" class="dropdown-toggle" role="button">More<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li>
                <!-- Content container to add padding -->
                <div class="yamm-content">
                  <div class="row">
                    <?php print $this->getMoreMenu('desktop')?>
                  </div>
                </div>
              </li>
            </ul>
          </li>
        <?php endif;?>
        <?php if ($this->_getMenuSuffix()):?>
          <?php print $this->_getMenuSuffix(); ?>
        <?php endif; ?>

      </ul>
    </div>
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-main-menu">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <?php print $this->getLogo('mobile', TRUE);?>
    </div>
  </div>
</div>
