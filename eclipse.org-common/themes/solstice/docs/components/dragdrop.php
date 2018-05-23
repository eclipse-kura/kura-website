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
<div class="drag_installbutton">
  <a href="http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=252" class="drag">
    <img src="/eclipse.org-common/themes/solstice/public/images/components/drag-drop/installbutton.png">
     <div class="tooltip"><h3>Drag to Install!</h3>Drag to your running Eclipse workspace.</div>
  </a>
</div><?php $html = ob_get_clean();?>

<h3 id="section-dragdrop">Marketplace Drag & Drop install</h3>
<p>Please take a look at the External Install Button tab over on <a href="http://marketplace.eclipse.org/">Eclipse Marketplace</a> for the mpc_install id. </p>
<?php print $html; ?>

<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>