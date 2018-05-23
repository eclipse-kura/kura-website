<?php
/*******************************************************************************
 * Copyright (c) 2015, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/
//if name of the file requested is the same as the current file, the script will exit directly.
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
?>

<div class="downloads-installer">
  <span class="downloads-logo vertical-align"><img height="50" alt="Eclipse" src="assets/public/images/logo-eclipse.png"></span>
  <h3>Get Eclipse <span class="oxygen">Oxygen</span></h3>
  <p>Install your favorite Eclipse packages.</p>
  <p>
    <?php foreach ($installer_links['links'] as $link): ?>
      <a class="<?php print $link['link_classes']; ?>" href="<?php print $link['url']; ?>" title="<?php print $link['text']; ?> Download"><?php print $link['text_prefix'] . ' ' . $link['text']; ?></a>
    <?php endforeach; ?>
  </p>
  <p><a href="/downloads/eclipse-packages" class="grey-link">Download Packages</a> | <a class="grey-link" href="/downloads/eclipse-packages/?show_instructions=TRUE#page-download" title="Instructions">Need Help?</a></p>
</div>