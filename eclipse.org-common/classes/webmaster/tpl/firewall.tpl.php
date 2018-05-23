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
if(!is_a($this, 'Firewall') || !$this->Friend->checkUserIsWebmaster()){
  exit();
}
$recent_blocks = $this->getRecentBlocks();
$search_results = $this->getSearchResults();
?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active">
    <a href="#firewall-recent-block" aria-controls="firewall-recent-block" role="tab" data-toggle="tab">Recent Blocks</a>
  </li>
  <li role="presentation">
    <a href="#firewall-insert-block" aria-controls="firewall-insert-block" role="tab" data-toggle="tab">Insert a Block</a>
  </li>
  <li role="presentation">
    <a href="#firewall-search-block" aria-controls="firewall-search-block" role="tab" data-toggle="tab">Search</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="firewall-recent-block">
    <p><strong>Show last:</strong></p>
    <form action="<?php print $this->getFormActionUrl();?>#firewall-recent-block" method="POST">
      <input type="hidden" name="form_name" value="webmaster-firewall">

      <div class="form-group">
        <label class="radio-inline">
          <input type="radio" name="period" value="12"> 12 hours
        </label>
        <label class="radio-inline">
          <input type="radio" name="period" value="24"> 24 hours
        </label>
        <label class="radio-inline">
          <input type="radio" name="period" value="48"> 48 hours
        </label>
        <label class="radio-inline">
          <input type="radio" name="period" value="72"> 72 hours
        </label>
      </div>
      <input type="hidden" name="state" value="change_recent_blocks_period">
      <input type="submit" class="btn btn-primary" value="Change">
    </form>
    <?php if (!empty($recent_blocks)):?>
      <hr>
      <?php if (is_array($recent_blocks)): ?>
      <table class="table table-stripped">
        <thead>
          <tr>
            <th>Subnet</th>
            <th>Port</th>
            <th>UserID</th>
            <th>Reporting Node</th>
            <th>Inserted</th>
            <th>Expires</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($recent_blocks as $recent_block): ?>
          <tr>
            <td><?php print $recent_block['Subnet']; ?></td>
            <td><?php print $recent_block['Port']; ?></td>
            <td><?php print $recent_block['UserID']; ?></td>
            <td><?php print $recent_block['VictimNode']; ?></td>
            <td><?php print $recent_block['AttackDateTime']; ?></td>
            <td><?php print $recent_block['ExpiryDateTime']; ?></td>
            <td>
              <form action="<?php print $this->getFormActionUrl();?>" method="POST">
                <input type="hidden" name="form_name" value="webmaster-firewall">
                <input type="hidden" name="state" value="delete_block">
                <input type="hidden" name="subnet_to_delete" value="<?php print $recent_block['Subnet']; ?>">
                <input type="submit" class="btn btn-default btn-xs" value="DELETE">
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <tr>
          <td colspan="7"><?php print $recent_blocks; ?></td>
        </tr>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <div role="tabpanel" class="tab-pane" id="firewall-insert-block">
    <form class="form-horizontal" method="POST" action="<?php print $this->getFormActionUrl();?>#firewall-insert-block">
      <input type="hidden" name="form_name" value="webmaster-firewall">
      <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">IP:<span class="required">*</span></label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="insert_block_ip" placeholder="IP Address">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Port:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="insert_block_port" placeholder="Port">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <div class="radio">
            <label><input type="radio" name="insert_block_time" value="1_day"> Block for 24 hours</label><br>
            <label><input type="radio" name="insert_block_time" value="6_month"> Block for 6 months</label><br>
            <label><input type="radio" name="insert_block_time" value="1_year"> Block for 1 year</label>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <input type="hidden" name="state" value="insert_block">
          <button type="submit" class="btn btn-primary">Block</button>
        </div>
      </div>
    </form>
  </div>

  <div role="tabpanel" class="tab-pane" id="firewall-search-block">
    <form method="POST" action="<?php print $this->getFormActionUrl();?>#firewall-search-block">
      <input type="hidden" name="form_name" value="webmaster-firewall">
      <div class="form-group">
        <input type="search" placeholder="Search for IP/Subnet" name="search_block_ip" class="form-control">
      </div>
      <input type="hidden" name="state" value="search_block">
      <input type="submit" class="btn btn-primary">
    </form>
    <?php if (!empty($search_results)): ?>
      <hr>
      <h3>Search Results</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Subnet</th>
            <th>Port</th>
            <th>UserID</th>
            <th>VictimNode</th>
            <th>AttackDateTime</th>
            <th>ExpiryDateTime</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($search_results as $result): ?>
            <tr>
              <?php if (!empty($result['no_results'])): ?>
                <td colspan="5"><?php print $result['no_results']; ?></td>
              <?php else: ?>
                <td><?php print $result['Subnet']; ?></td>
                <td><?php print $result['Port']; ?></td>
                <td><?php print $result['UserID']; ?></td>
                <td><?php print $result['VictimNode']; ?></td>
                <td><?php print $result['AttackDateTime']; ?></td>
                <td><?php print $result['ExpiryDateTime']; ?></td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
