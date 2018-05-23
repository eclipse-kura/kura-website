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

<div class="jumbotron featured-jumbotron padding-top-60">
  <div class="container">
    <div class="row">
      <div class="col-md-20 col-md-offset-2 col-sm-18 col-sm-offset-3">
        <h1>Page Title</h1>
      </div>
    </div>
  </div>
</div>
<?php $html = ob_get_clean();?>

<h3 id="section-landing-well">Landing-well</h3>
</div>
<?php print $html; ?>
<div class="container">
<h4>Code</h4>
<pre>
&lt;?php
ob_start();
?&rt;
<?php print htmlentities($html); ?>
&lt;?php
$extra_header_html = ob_get_clean();
$Theme->setExtraHeaderHtml($extra_header_html);
</pre>