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
<div class="row header-row background-charcoal">
  <div class="col-md-16 header-row float-right right">
    <span id="descriptionText">Eclipse Luna (4.4) Release</span> for
    <select id="osSelect">
      <option value="win32">Windows</option>
      <option value="linux" selected="selected">Linux</option>
      <option value="macosx">Mac OS X (Cocoa)</option>
    </select>
  </div>
</div><?php $html = ob_get_clean();?>

<h3 id="section-headerrow">Header row</h3>
<p>@TODO</p>
<?php print $html; ?>

<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>