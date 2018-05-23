<?php
/*******************************************************************************
 * Copyright (c) 2015 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

$amount = $this->Donation->get_donation_amount();
if (empty($this->Donation->Donor->donor_email) && $amount >= 35) {
  $message = '<strong>Please note:</strong> You have not entered your email address in the previous form.
    Please provide your email address before proceeding to bitpay if you would like to receive your
    <a href="/donate/benefits.php" target="_blank" title="Friend of Eclipse Benefits">
    Friend of Eclipse Benefits</a>.<br/><br/>
    <div class="form-group">
      <label>Email address:</label><br/>
      <input type="text" name="buyerEmail" value="" />
    </div>';
  $this->set_client_message($message, 'warning');
}

?>
<form class="bitpay-donate" action="<?php print $this->_get_gateway_url();?>" method="post">
  <?php print $this->get_client_message();?>
  <h1>Please confirm your donation</h1>
  <?php if (!empty($this->Donation->Donor->donor_first_name) || !empty($this->Donation->Donor->donor_last_name)): ?>
    <div class="form-group col-xs-12">
      <label>Name:</label><br/>
      <?php print $this->Donation->Donor->donor_first_name;?> <?php print $this->Donation->Donor->donor_last_name;?>
    </div>
     <input type="hidden" name="buyerName" value="<?php print $this->Donation->Donor->get_donor_full_name();?>" />
  <?php endif;?>

  <?php if (!empty($this->Donation->Donor->donor_email)): ?>
    <div class="form-group  col-xs-12">
      <label>Email address:</label><br/>
      <?php print $this->Donation->Donor->donor_email;?>
      <input type="hidden" name="buyerEmail" value="<?php print $this->Donation->Donor->donor_email;?>" />
    </div>
  <?php endif;?>

  <div class="form-group  col-xs-12">
    <label>Donation Amount:</label><br/>
    $<?php print $this->Donation->donation_amount;?> USD
  </div>

  <div class="form-group col-xs-12">
    <label>Visibility:</label><br/>
    <?php print $this->Donation->get_donation_is_anonymous_string();?>
  </div>

  <?php if (!empty($this->Donation->message)): ?>
    <div class="form-group col-xs-24">
      <label>Comments:</label><br/>
      <p><?php print $this->Donation->message;?></p>
    </div>
  <?php endif;?>

  <div class="form-group col-xs-24 clearix">
    <input type="hidden" name="action"  value="checkout">
    <input type="hidden" name="notificationType" value="json" />
    <input type="hidden" name="price" value="<?php print $this->Donation->get_donation_amount();?>"/>
    <input type="hidden" name="currency" value="USD"/>
    <input type="hidden" name="posData" value="<?php print $this->Donation->get_donation_random_invoice_id();?>" />
    <input type="hidden" name="data" value="<?php print $this->_get_bitpay_data();?>">
    <button type="submit" name="submit" class="btn btn-warning">Donate with bitpay.com</button>
  </div>
</form>