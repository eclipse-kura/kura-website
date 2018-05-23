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

$search_results = $this->getSearchResults();
$default_search_table_and_name = $this->getDefaultSearchTableAndName();
?>
<form action="<?php print $this->getFormActionUrl();?>#mailinglists-search" method="GET" class="form-horizontal">
  <input type="hidden" name="form_name" value="webmaster-mailinglists">
  <div class="form-group">
    <label class="col-sm-6 control-label">By Table <span class="required">*</span></label>
    <div class="col-sm-18">
      <select name="search_table" class="form-control">
        <option value="">Select a Table</option>
        <option <?php print ($default_search_table_and_name['table'] == 'mailing_lists' ? 'selected' : '');?> value="mailing_lists">Mailing Lists</option>
        <option <?php print ($default_search_table_and_name['table'] == 'newsgroups' ? 'selected' : '');?> value="newsgroups">Newsgroups</option>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-6 control-label">By Project</label>
    <div class="col-sm-18">
      <select name="search_1" class="form-control">
        <option value="">Select a project</option>
        <?php foreach ($projects as $project): ?>
          <option <?php print $this->checkSelectedOption('project_id', $project['ProjectID']); ?> value="<?php print $project['ProjectID']; ?>"><?php print $project['ProjectID']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-6 control-label">By Status</label>
    <div class="col-sm-18">
      <select name="search_2" class="form-control">
        <option value="">Choose a status</option>
        <?php foreach ($this->getStatusList() as $status): ?>
          <option <?php print $this->checkSelectedOption('provision_status',$status); ?> value="<?php print $status; ?>"><?php print $status; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-6 control-label">By Name</label>
    <div class="col-sm-18">
      <input type="text" name="search_3" value="<?php print $default_search_table_and_name['name']; ?>" placeholder="Enter a name" class="form-control">
    </div>
  </div>
  <input type="hidden" name="state" value="search">
  <div class="form-group">
    <div class="col-sm-18 col-sm-offset-6">
      <input type="submit" value="Search" class="btn btn-primary">
    </div>
  </div>
</form>
<?php if (!empty($search_results)) :?>
  <h3>Search Results</h3>
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Project ID</th>
        <th>Provision Status</th>
        <th>Created Date</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($search_results as $search_result): ?>
        <tr>
          <?php if (!empty($search_result['no_results'])): ?>
            <td colspan="5"><?php print $search_result['no_results']; ?></td>
          <?php else: ?>
            <td><?php print $search_result['name']; ?></td>
            <td><?php print $search_result['project_id']; ?></td>
            <td><?php print $search_result['provision_status']; ?></td>
            <td><?php print $search_result['create_date']; ?></td>
            <td class="text-right">
            <form action="<?php print $this->getFormActionUrl();?>" method="POST">
              <input type="hidden" name="form_name" value="webmaster-mailinglists">
              <input type="hidden" name="state" value="delete">
              <input type="hidden" name="item_type" value="<?php print $search_result['table']; ?>">
              <input type="hidden" name="item_to_delete" value="<?php print $search_result['name']; ?>">
              <input type="submit" class="btn btn-default btn-xs" value="DELETE">
            </form>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>