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
<header<?php print $this->getAttributes('header-wrapper');?>>
  <?php print $this->getToolbarHtml();?>
  <div<?php print $this->getAttributes('header-container');?>>
    <div<?php print $this->getAttributes('header-row');?>>
      <?php print $this->getHeaderLeft();?>
      <?php print $this->getHeaderRight();?>
      <?php print $this->getThemeFile('main_menu');?>
    </div>
  </div>
  <?php print $this->getExtraHeaderHtml(); ?>
</header>
