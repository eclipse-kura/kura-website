<?php
/**
 * Copyright (c) 2014, 2015, 2016, 2018 Eclipse Foundation.
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
<div class="featured-footer featured-footer-newsletter background-secondary">
  <div class="container">
    <p><i data-feather="mail" stroke-width="1"></i></p>
    <h2>Sign up to our Newsletter</h2>
    <p>A fresh new issue delivered monthly</p>
    <form action="https://www.eclipse.org/donate/process.php" method="post" target="_blank">
      <div class="form-group">
        <input type="hidden" name="type" value="newsletter">
        <input type="email" value="" name="email" class="textfield-underline form-control" id="mce-EMAIL" placeholder="Email">
      </div>
      <input type="submit" value="Subscribe" name="subscribe" class="button btn btn-warning">
    </form>
  </div>
</div>
<?php $html = ob_get_clean();?>

<h3 id="section-highlight">Highlight</h3>
</div>
<?php print $html; ?>
<div class="container">
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>