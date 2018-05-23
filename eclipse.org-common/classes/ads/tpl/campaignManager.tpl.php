<?php
/*******************************************************************************
 * Copyright (c) 2007-2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Donald Smith (Eclipse Foundation) - initial API and implementation
 *    Eric Poirier (Eclipse Foundation)
 *******************************************************************************/

print $this->page_header_html; ?>

<p>This page is designed for Eclipse Foundation staff who need to manage click-tracking
campaigns and as an added bonus - it shortens most URLs.  If you need help with
this feature, see help at bottom of page, and feel free to email donald, who works at eclipse.org.</p>

<div class="row">
  <div class="col-sm-12">
    <h2>View Campaigns By Person</h2>
    <form class="form-horizontal" action="?page=view-campaigns" method="POST">
      <div class="form-group">
        <div class="col-sm-14">
          <select name="campaignPortalID" class="form-control">
            <option value="ALL">All</option>
            <?php
              foreach($this->selectCampaignByUser() as $email){
                print '<option SELECTED value="'.
                       $email['CreatorPortalID'] .'">' .
                       $email['CreatorPortalID'] . '</option>';
              }
            ?>
          </select>
        </div>
        <div class="col-sm-10">
          <input type="hidden" name="action_state"  value="view-campaigns"/>
          <input class="btn btn-primary" type="submit"
            value="View Campaigns" name="viewCampaigns">
        </div>
      </div>
    </form>
    <h2>View Campaigns By Group</h2>
    <form class="form-horizontal" action="?page=view-campaigns" method="POST">
      <div class="form-group">
        <div class="col-sm-14">
        <select name="campaignGroup" class="form-control">
          <option value="ALL">All</option>
          <?php
            foreach($this->selectCampaignByGroup() as $group){
              print '<option value="' . $group['CampaignGroup'] . '">' .
                     $group['CampaignGroup'] . "</option>";
            }
          ?>
        </select>
        </div>
        <div class="col-sm-10">
          <input type="hidden" name="action_state"  value="view-campaigns"/>
          <input class="btn btn-primary" type="submit" value="View Campaigns"
           name="viewCampaigns">
        </div>
      </div>
    </form>

    <h2>Create A New Campaign</h2>
    <form class="form-horizontal" action="?page=home" method="POST">
      <div class="form-group">
        <label class="col-sm-8 control-label" for="campaignName">Campaign Name:</label>
        <div class="col-sm-16">
          <input class="form-control" type="text" name="campaignName" value="">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-8 control-label" for="campaignTarget">Target URL:</label>
        <div class="col-sm-16">
          <input class="form-control"  type="text" name="campaignTarget" value="http://">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-8 control-label" for="campaignEndDate">Expiry Date:</label>
        <div class="col-sm-16">
          <input class="form-control"  type="text" name="campaignEndDate"
            value="<?php print date("Y-m-d", strtotime("+2 year")); ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-8 control-label" for="campaignGroup">Group:</label>
        <div class="col-sm-16">
          <input class="form-control"  type="text" name="campaignGroup" value="SOLO">
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-8 col-sm-16">
          <input type="hidden" name="action_state"  value="create"/>
          <input class="btn btn-primary" type="submit" value="Create Campaign"
           name="create">
        </div>
      </div>
      <p>
      </p>
    </form>
  </div>

  <div class="col-sm-12">
    <h2>How to Create a Campaign</h2>
    <p>A simple use case is:</p>
    <ol>
      <li>Enter a campaign name such as "FOO".  Keep it short and sweet, no spaces, just use Characters and numbers.</li>
      <li>Enter a target URL (MUST put http://) such as http://test.com/long/url/foo.php</li>
      <li>Enter an expiry date (after that date clicks to the campaign will go to an "expired campaign" error page).</li>
      <li>Enter Campaign Group (or leave at SOLO if it stands alone). This helps you manage related campaigns in a single newsletter for example.</li>
      <li>Click "Create Campaign" Button</li>
      <li>Point people to http://eclipse.org/go/FOO (where FOO is the campaign name), they will automatically be redirected to
      the target URL, and we will track who-clicked-what-when.</li>
    </ol>
    <h2>Notes:</h2>
    <ul>
      <li>Click the "View Campaigns" button to view campaigns that are related to the person or group you chose.</li>
      <li>Click the "view clicks" button to see the clicks to your campaign!</li>
      <li>It is possible to add a SubKey (lets call it a "channel key") to URLs if you want to distinguish between
      clicks coming from different channels. For example, eclipse.org/go/FOO@EM and eclipse.org/go/FOO@WB --
      both will record and redirect
      the FOO Campaign, but will tag the click with an "EM" (for Email)or "WB" (for Web)
      in the click results to distinguish
      clicks coming from channels.</li>
      <li>For Email Campaings we could get really fancy with a "Mail Merge" tool and encode a
      user ID after the @ in the URL to know *exactly* who is clicking on links.  For example,
      eclipse.org/go/FOO@1287 would record 1287 with the click and we could tell that
      member 1287 clicked the url...</li>
    </ul>
  </div>
</div>