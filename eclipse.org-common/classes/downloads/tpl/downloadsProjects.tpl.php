<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
?>


<!-- Downloads-item -->
<div class="<?php print $project->getProjectsAttributes('container','class'); ?>" style="<?php print $project->getProjectsAttributes('container','style'); ?>">
  <span class="downloads-logo vertical-align"><img height="<?php print $project->getProjectsAttributes('image','height'); ?>" alt="<?php print $project->getTitle(); ?>" src="<?php print $project->getLogo(); ?>"></span>
  <p><?php print $project->getDescription(); ?></p>
  <p class="visible-xs visible-sm"><a href="<?php print $project->getDownloadUrl64Bit(); ?>" class="btn btn-warning btn-xs">Get it</a></p>
  <p class="visible-xs visible-sm downloads-items-hover-box-links"><a href="<?php print $project->getLearnMoreUrl(); ?>">Learn More</a></p>
  <div class="downloads-items-hover-box">
    <h4 class="downloads-items-header"><?php print $project->getTitle(); ?></h4>
    <p class="downloads-items-hover-box-text"><?php print $project->getDescription(); ?></p>
    <p><a href="<?php print $project->getDownloadUrl64Bit(); ?>" class="btn btn-warning btn-xs">Get it</a></p>
    <p class="downloads-items-hover-box-links"><a href="<?php print $project->getLearnMoreUrl(); ?>">Learn More</a></p>
  </div>
</div>
