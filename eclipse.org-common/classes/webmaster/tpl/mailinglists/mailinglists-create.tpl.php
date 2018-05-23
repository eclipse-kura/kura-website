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
?>

<form class="form-horizontal" method="POST" action="<?php print $this->getFormActionUrl();?>">
  <input type="hidden" name="form_name" value="webmaster-mailinglists">
  <input type="hidden" name="state" value="create">
  <div class="form-group">
    <div class="col-sm-18 col-md-offset-6">
      <div class="radio">
        <label><input type="radio" name="create_table" value="mailing_lists"> Create mailing lists</label>
      </div>
      <div class="radio">
        <label><input type="radio" name="create_table" value="newsgroups"> Create newsgroups</label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-6 control-label">Projects  <span class="required">*</span></label>
    <div class="col-sm-18">
      <select name="create_project" class="form-control">
        <option>Select a project</option>
        <?php foreach ($projects as $project): ?>
          <option value="<?php print $project['ProjectID']; ?>"><?php print $project['ProjectID']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-6 control-label">Name <span class="required">*</span></label>
    <div class="col-sm-18">
      <input class="form-control" type="text" name="create_name" placeholder="Choose a name">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-6 control-label">Description  <span class="required">*</span></label>
    <div class="col-sm-18">
      <input class="form-control" type="text" name="create_description" placeholder="Enter a description">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-18 col-sm-offset-6">
      <input type="submit" value="Create" class="btn btn-primary">
    </div>
  </div>
</form>