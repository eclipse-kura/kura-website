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
<div class="toolbar-menu">
  <div class="container">
    <div class="row">
      <div class="col-md-24">
        <ol class="breadcrumb">
          <li><i class="fa fa-angle-double-right orange fa-fw"></i> <a class="active" href="/downloads/index.php">Packages</a></li>
          <li><a href="/downloads/java8/">Java&trade; 8 Support</a></li>
        </ol>
      </div>
    </div>
  </div>
</div>
<?php $html = ob_get_clean();?>
<h3 id="section-toolbarmenu">Toolbar Menu</h3>
</div>
<?php print $html; ?>
<div class="container">
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>