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
$title = 'Mailing';
if ($table == 'newsgroups') {
  $title = 'Newsgroup';
}
?>

<table class="table">
  <thead>
    <tr>
      <th class="col-sm-6"><?php print $title;?> Name</th>
      <th class="col-sm-5">Project ID</th>
      <th class="col-sm-5">Provision Status</th>
      <th class="col-sm-6">Created Date</th>
      <th class="col-sm-2"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($list as $key => $list_group): ?>
      <?php foreach ($list_group as $item): ?>
        <tr>
          <td><?php print $item['name']; ?></td>
          <td><?php print $item['project_id']; ?></td>
          <td><?php print $item['provision_status']; ?></td>
          <td><?php print $item['create_date']; ?></td>
          <td class="text-right">
            <form action="<?php print $this->getFormActionUrl();?>" method="POST">
              <input type="hidden" name="form_name" value="webmaster-mailinglists">
              <input type="hidden" name="state" value="delete">
              <input type="hidden" name="item_type" value="<?php print $table;?>">
              <input type="hidden" name="item_to_delete" value="<?php print $item['name']; ?>">
              <input type="submit" class="btn btn-default btn-xs" value="DELETE">
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </tbody>
</table>