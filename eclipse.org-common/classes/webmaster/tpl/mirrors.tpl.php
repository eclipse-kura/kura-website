<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
if(!is_a($this, 'Mirrors') || !$this->Friend->checkUserIsWebmaster()){
  exit();
}
$mirrors_array = $this->getMirrors();
?>

<?php if (!empty($mirrors_array)):?>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <?php foreach ($mirrors_array as $status => $mirrors) :?>
      <?php $active = (!isset($active)) ? TRUE : FALSE;?>
      <li role="presentation" <?php print ($active === TRUE ? 'class="active"' : ''); ?>><a href="#mirror_<?php print $status;?>" aria-controls="#mirror_<?php print $status;?>" role="tab" data-toggle="tab"><?php print strtoupper($status);?></a></li>
    <?php endforeach;?>
    <?php unset($active);?>
  </ul>
<?php endif; ?>

<?php if (!empty($mirrors_array)):?>
  <!-- Tab panes -->
  <div class="tab-content">
    <?php foreach ($mirrors_array as $status => $mirrors) :?>
      <?php $active = (!isset($active)) ? TRUE : FALSE;?>
      <div role="tabpanel" class="tab-pane <?php print ($active ? 'active' : ''); ?>" id="mirror_<?php print $status;?>">
        <?php include('mirrors-table.tpl.php');?>
      </div>
    <?php endforeach;?>
  </div>
<?php endif; ?>

<?php if (empty($mirrors_array)): ?>
  <p>There are no active mirrors and mirrors that needs approval.</p>
<?php endif; ?>
