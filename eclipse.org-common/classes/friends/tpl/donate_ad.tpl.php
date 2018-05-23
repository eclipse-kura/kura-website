<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/classes/friends/payment.class.php");
$Paypal = new Paypal();
?>

<div class="donate-ad">
  <div class="container">
    <i class="fa fa-times btn-donate-close" aria-hidden="true"></i>
    <div class="row">
      <div class="col-sm-14 donate-text">
        <?php print $banner_content['title']; ?>
        <?php print $banner_content['text']; ?>
        <?php print $banner_content['thankyou']; ?>

        <ul class="list-inline">
          <li><a class="underlined-link" href="mailto:donate@eclipse.org?subject=Donation Problem">Problems donating?</a></li>
          <li><a class="underlined-link" href="/donate/faq.php" target="_blank">Donation FAQs</a></li>
        </ul>
      </div>
      <div class="col-sm-10 text-center donate-form">

        <form id="donation_default_eclipse_form" action="<?php print $Paypal->get_gateway_process_url();?>?utm_source=eclipse.org&utm_medium=donate_program&utm_content=donate-banner&utm_campaign=eclipse_org_<?php print $banner_content['campaign']; ?>" method="POST">
          <div class="row">
            <div class="col-sm-24">
              <ul class="list-inline list-amount">
                <li><button type="button" class="btn btn-focus btn-square" value="5">$5</button></li>
                <li><button type="button" class="btn btn-focus btn-square active" value="10">$10</button></li>
                <li><button type="button" class="btn btn-focus btn-square" value="35">$35</button></li>
                <li><button type="button" class="btn btn-focus btn-square" value="50">$50</button></li>
                <li><button type="button" class="btn btn-focus btn-square" value="100">$100</button></li>
              </ul>
            </div>
          </div>

          <div class="row margin-bottom-5">
            <div class="col-sm-24">
              <label class="radio-inline"><input type="radio" name="type" value="paypal" checked="checked"> Paypal</label>
              <label class="radio-inline"><input type="radio" name="type" value="bitpay"> Bitcoin</label>
            </div>
          </div>

          <div class="row margin-bottom-5">
            <div class="col-sm-24">
              <label class="radio-inline"><input type="radio" name="subscription" id="subscription_default" value="0" checked="checked"> One-time</label>
              <label class="radio-inline"><input type="radio" name="subscription" value="M"> Monthly</label>
              <label class="radio-inline"><input type="radio" name="subscription" value="Y"> Yearly</label>
              </ul>
            </div>
          </div>

          <div class="checkbox">
            <label>
              <input class="recognition-checkbox" type="checkbox" value="" name="recognition">
              I want to be listed on the recognition page
            </label>
          </div>

          <div class="recognition-fields form-inline margin-bottom-10">
            <div class="form-group">
              <input class="form-control" type="text" name="first_name" placeholder="First Name">
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="last_name" placeholder="Last Name">
            </div>
          </div>

          <div class="form-inline margin-bottom-10 donate-submit">
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">$</div>
                <input class="donate-amount form-control" type="number" name="amount" value="10">
              </div>
            </div>
            <div class="form-group">
              <input type="submit" value="Donate" class="btn btn-warning">
            </div>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>