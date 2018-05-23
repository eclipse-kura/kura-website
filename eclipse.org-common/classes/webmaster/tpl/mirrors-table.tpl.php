<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
if(basename(__FILE__) == basename($_SERVER['PHP_SELF']) || !$this->Friend->checkUserIsWebmaster()){
  exit();
}
$statuses = $this->getMirrorStatuses();
?>

<?php if (!empty($mirrors)):?>
  <form method="POST" name="webmaster-mirror-update" action="<?php print $this->getFormActionUrl();?>">
    <table class="table table-stripped text-left">
      <thead>
        <tr>
          <th>Mirror Id</th>
          <th>Organization</th>
          <th>Internal?</th>
          <th>Protocol</th>
          <th style="width:120px;">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($mirrors as $mirror): ?>
          <tr class="<?php print $mirror['row_context']?>">
            <td><?php print $mirror['mirror_id']; ?></td>
            <td><?php print $mirror['organization']; ?><br/><?php print $mirror['base_path']; ?></td>
            <td><?php print $mirror['is_internal']; ?></td>
            <td><?php print $mirror['protocol']; ?></td>
            <td class="text-right">
              <div class="form-group form-group-sm">
                <select name="status_update_<?php print $mirror['mirror_id']; ?>" class="form-control">
                  <?php foreach ($statuses as $options):?>
                    <option <?php print ($options === $mirror['create_status'] ? 'selected' : ''); ?> value="<?php print $options; ?>"><?php print $options; ?></option>
                  <?php endforeach;?>
                </select>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <hr>
    <input type="hidden" name="form_name" value="webmaster-mirror-update">
    <input type="hidden" name="status" value="<?php print $status;?>">
    <input type="hidden" name="state" value="update_mirrors">
    <button class="btn btn-primary">update</button>
  </form>
<?php endif;?>