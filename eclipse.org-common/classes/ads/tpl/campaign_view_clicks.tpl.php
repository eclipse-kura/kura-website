<?php
/*******************************************************************************
 * Copyright (c) 2007-2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation)
 *******************************************************************************/

// VIEW CLICKS
?>
<div class="row">
  <div class="col-sm-12">
    <h2>Showing the <?php print $this->getCampaignViewClicks(); ?> most recent:</h2>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>ClickID</th>
          <th>Campaign Name</th>
          <th>Domain Name</th>
          <th>TimeStamp</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($this->getCurrentClicks() as $myrow):?>
            <tr>
              <td><?php print $myrow['ClickID']; ?></td>
              <td><?php print $myrow['CampaignKey']; ?></td>
              <td><?php print $myrow['HostName']; ?></td>
              <td><?php print $myrow['TimeClicked']; ?></td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="col-sm-12">
    <?php
    // CHANGE CAMPAIGN URL UI
  ?>
  <h2>Update campaign url and/or date</h2>
  <p>This will update the url and date for the campaign</p>

  <form action="?page=view-campaigns&<?php print $this->getCampaignByUserOrGroup($for_url = TRUE); ?>"
   method="POST">
    <table class="table table-striped table-bordered">
    <?php $currentCampaign = $this->selectCampaignFromCampaignKey(); ?>
      <tr>
        <th>OLD URL:</th>
        <td><?php print $currentCampaign['TargetUrl']; ?></td>
      </tr>
      <tr>
        <th>NEW URL: </th>
        <td>
          <input type="text" name="campaignNewURL" value="<?php print $currentCampaign['TargetUrl']; ?>">
        </td>
      </tr>

      <tr>
        <th>OLD DATE:</th>
        <td><?php print $currentCampaign['DateExpires']; ?></td>
      </tr>
      <tr>
        <th>NEW DATE: </th>
        <td>
          <input type="text" name="campaignNewDATE" value="<?php print $currentCampaign['NewExpiryDate']; ?>">
        </td>
      </tr>
      <tr>
        <th>OLD GROUP: </th>
        <td><?php print $currentCampaign['CampaignGroup']; ?></td>
      </tr>
      <tr>
        <th>NEW GROUP: </th>
        <td>
          <input type="text" name="campaignNewGROUP" value="<?php print $currentCampaign['CampaignGroup']; ?>">
        </td>
      </tr>
      <tr>
        <th></th>
        <td>
          <input type="hidden" name="action_state"  value="change"/>
          <input class="btn btn-primary" type="submit" value="Change" name="change">
        </td>
      </tr>
    </table>
    <input type="hidden" name="campaignNewClicks" value="<?php print $this->getCampaignViewClicks(); ?>">
    </form>
  </div>
</div>

<hr>

