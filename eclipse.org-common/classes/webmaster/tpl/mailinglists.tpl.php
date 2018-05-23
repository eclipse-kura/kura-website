<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
if(!is_a($this, 'MailingLists') || !$this->Friend->checkUserIsWebmaster()){
  exit();
}

$projects = $this->getProjects();
?>


<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active">
    <a href="#mailinglists-create" aria-controls="mailinglists-create" role="tab" data-toggle="tab">Create</a>
  </li>
  <li role="presentation">
    <a href="#mailinglists-search" aria-controls="mailinglists-search" role="tab" data-toggle="tab">Search</a>
  </li>
  <li role="presentation">
    <a href="#mailinglists-completed" aria-controls="mailinglists-completed" role="tab" data-toggle="tab">Recently Completed</a>
  </li>
  <li role="presentation">
    <a href="#mailinglists-new" aria-controls="mailinglists-new" role="tab" data-toggle="tab">New/Pending</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="mailinglists-create">
    <?php include('mailinglists/mailinglists-create.tpl.php');?>
  </div>
  <div role="tabpanel" class="tab-pane" id="mailinglists-search">
    <?php include('mailinglists/mailinglists-search.tpl.php');?>
  </div>
  <div role="tabpanel" class="tab-pane" id="mailinglists-completed">
    <?php include('mailinglists/mailinglists-completed.tpl.php');?>
  </div>
  <div role="tabpanel" class="tab-pane" id="mailinglists-new">
    <?php include('mailinglists/mailinglists-new.tpl.php');?>
  </div>
</div>

