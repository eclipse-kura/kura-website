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
<section class="default-breadcrumbs hidden-print breadcrumbs-default-margin" id="breadcrumb">
  <div class="container">
    <h3 class="sr-only">Breadcrumbs</h3>
    <div class="row">
      <div class="col-sm-24">
        <ol class="breadcrumb">
          <li><a href="https://www.eclipse.org/">Home</a></li>
          <li><a href="https://www.eclipse.org/projects/">Projects</a></li>
          <li><a href="https://www.eclipse.org/eclipse.org-common">eclipse.org-common</a></li>
          <li class="active">Solstice documentation</li>
        </ol>
      </div>
    </div>
  </div>
</section><?php $html = ob_get_clean();?>

<h3 id="section-breadcrumbs">Breadcrumbs</h3>
<p>The <code>$App Class</code> should generate a breadcrumb for you.</p>
</div>
<?php print $html;?>
<div class="container">
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>

