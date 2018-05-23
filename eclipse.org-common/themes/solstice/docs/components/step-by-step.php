<?php
/**
 * Copyright (c) 2014, 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Christopher Guindon (Eclipse Foundation) - Initial implementation
 *   Eric Poirier (Eclipse Foundation)
 *
 * SPDX-License-Identifier: EPL-2.0
 */
?>

<?php ob_start(); ?>
<div class="step-by-step">
  <div class="container">
    <div class="row intro">
      <div class="col-xs-24">
        <h2>Participate &amp; Contribute</h2>
        <p>Get involved in Eclipse projects to help contribute to their success.<br/>
        We welcome users and adopters as part of the community.</p>
      </div>
    </div>
    <div class="row step-by-step-timeline">
      <div class="col-sm-6 step">
        <a class="step-icon" href="/contribute"><i data-feather="help-circle" stroke-width="1"></i></a>
        <p><a href="/contribute" class="btn btn-info">How to contribute</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="/projects/handbook/#starting"><i data-feather="zap" stroke-width="1"></i></a>
        <p><a href="/projects/handbook/#starting" class="btn btn-info">Start a new project</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="/projects/handbook"><i data-feather="book-open" stroke-width="1"></i></a>
        <p><a href="/projects/handbook" class="btn btn-info">Running a project</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="/projects/project_activity.php"><i data-feather="activity" stroke-width="1"></i></a>
        <p><a href="/projects/project_activity.php" class="btn btn-info">Project Activity</a></p>
      </div>
    </div>
  </div>
</div>
<?php $html = ob_get_clean();?>
<h3 id="section-stepbystep">Step by Step</h3>
<?php print $html; ?>
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>