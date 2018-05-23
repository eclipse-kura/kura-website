<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
if(!is_a($this, 'Jobs') || !$this->Friend->checkUserIsWebmaster()){
  exit();
}

$job_statuses = $this->getJobStatus();
$pending_jobs = $this->getPendingJobs();

?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">

  <li role="presentation" class="active">
    <a href="#job-add-a-job" aria-controls="job-add-a-job" role="tab" data-toggle="tab">Add a Job</a>
  </li>

  <li role="presentation">
    <a href="#job-job-status" aria-controls="job-job-status" role="tab" data-toggle="tab">Job Status</a>
  </li>

  <li role="presentation">
    <a href="#job-pending-jobs" aria-controls="job-pending-jobs" role="tab" data-toggle="tab">Pending jobs</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="job-add-a-job">
    <p><strong>Add rsync configs job</strong></p>
    <form action="<?php print $this->getFormActionUrl();?>" method="POST">
      <input type="hidden" name="form_name" value="webmaster-jobs">

      <div class="checkbox">
        <label>
          <input type="checkbox" name="rsync" value="on">
          Restart Rsync
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="postfix" value="on">
          Restart Postfix
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="nscd" value="on">
          Restart nscd
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="newaliases" value="on">
          Run newaliases
        </label>
      </div>
      <input type="hidden" name="state" value="add_job">
      <input class="btn btn-primary" type="submit" value="Add job">
    </form>
  </div>

  <div role="tabpanel" class="tab-pane" id="job-job-status">
    <table class="table table-stripped">
      <thead>
        <tr>
          <th>Job</th>
          <th>Options</th>
          <th>System</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($job_statuses as $job_status): ?>
        <tr>
          <td><?php print $job_status["job"]; ?></td>
          <td><?php print $job_status["options"]; ?></td>
          <td class="<?php print ($job_status['job_status'] == "OK" ? "green" : 'red'); ?>">
            <strong><?php print $job_status["node"]; ?></strong>
          </td>
          <td class="<?php print ($job_status['job_status'] == "OK" ? "green" : 'red'); ?>">
            <strong><?php print $job_status["job_status"]; ?></strong>
          </td>
          <td><?php print $job_status["run_when"]; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div role="tabpanel" class="tab-pane" id="job-pending-jobs">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Job</th>
          <th>Options</th>
          <th>Date Submitted</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($pending_jobs as $pending_job): ?>
        <tr>
          <td><?php print $pending_job['job_id'];?></td>
          <td><?php print $pending_job['job'];?></td>
          <td><?php print $pending_job['options'];?></td>
          <td><?php print $pending_job['date_code'];?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
