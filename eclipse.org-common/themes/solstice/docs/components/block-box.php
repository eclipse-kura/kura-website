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
<div class="sideitem">
  <h6>Block Title</h6>
  <div class="content">
    <p>Content goes here...</p>
  </div>
</div><?php $html = ob_get_clean();?>

<h3 id="section-block-box">Block-box</h3>
<p>Content block mainly used in the right sidebar area. The <code>.block-box-classic</code> class is optional.</p>
<?php print $html;?>

<h4>Code</h4>

<pre><?php print htmlentities($html); ?></pre>