<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
if (!is_a($this, 'baseTheme')) {
  return "";
}
?>
<li class="dropdown">
  <a href="<?php print $this->getBaseUrl();?>/list-of-projects" title="List of Projects" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Technology <span class="caret"></span></a>
  <ul class="dropdown-menu">
    <li class="first leaf"><a href="<?php print $this->getBaseUrl();?>/list-of-projects" title="List of Projects">View Projects</a></li>
    <li class="leaf"><a href="<?php print $this->getBaseUrl();?>/proposals/propose-new-technology" title="Basic instructions for creating a technology project">Create a Proposal</a></li>
    <li class="last leaf"><a href="<?php print $this->getBaseUrl();?>/proposals" title="List of project proposals">Proposals</a></li>
  </ul>
</li>
<li class="dropdown">
  <a href="<?php print $this->getBaseUrl();?>/members" title="Members" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Members <span class="caret"></span></a>
  <ul class="dropdown-menu">
    <li class="first leaf"><a href="<?php print $this->getBaseUrl();?>/members-list" title="Members">View Members</a></li>
    <li class="last leaf"><a href="<?php print $this->getBaseUrl();?>/content/become-member" title="Instructions for Joining LocationTech as a Member">Become a Member</a></li>
  </ul>
</li>
<li class="leaf"><a href="http://tour.locationtech.org/" title="The 2015 LocationTech Tour">Tour 2016</a></li>
<li class="leaf"><a href="<?php print $this->getBaseUrl();?>/meetings" title="Meetings">Meetings</a></li>
<li class="leaf"><a href="<?php print $this->getBaseUrl();?>/events" title="LocationTech events">Events</a></li>
<li class="leaf"><a href="<?php print $this->getBaseUrl();?>/steeringcommittee">Steering Committee</a></li>
<li class="dropdown">
  <a href="<?php print $this->getBaseUrl();?>/about" title="About LocationTech" data-target="#" class="dropdown-toggle" data-toggle="dropdown">About Us <span class="caret"></span></a>
  <ul class="dropdown-menu">
    <li class="first leaf"><a href="<?php print $this->getBaseUrl();?>/charter" title="LocationTech Charter">Charter</a></li>
    <li class="leaf"><a href="<?php print $this->getBaseUrl();?>/news" title="News">News</a></li>
    <li class="leaf"><a href="<?php print $this->getBaseUrl();?>/community_news" title="Community News">Community News</a></li>
    <li class="leaf"><a href="<?php print $this->getBaseUrl();?>/about" title="Read a bit more about us.">About Us</a></li>
    <li class="leaf"><a href="http://www.eclipse.org/org/foundation/staff.php" title="See a list of the staff who provide services to support the community and ecosystem">Staff</a></li>
    <li class="leaf"><a href="<?php print $this->getBaseUrl();?>/conduct">Community Code of Conduct</a></li>
    <li class="leaf"><a href="<?php print $this->getBaseUrl();?>/faq" title="">FAQ</a></li>
    <li class="last leaf"><a href="<?php print $this->getBaseUrl();?>/jobs">Jobs</a></li>
  </ul>
</li>