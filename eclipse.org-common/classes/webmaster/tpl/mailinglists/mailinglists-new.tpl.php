<?php
/*******************************************************************************
 * Copyright (c) 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - initial API and implementation
 *******************************************************************************/
if(!is_a($this, 'MailingLists') || !$this->Friend->checkUserIsWebmaster()){
  exit();
}

$mailing_lists = $this->getMailingLists();
$newsgroups = $this->getNewsgroups();

if (isset($mailing_lists['completed'])) {
  unset($mailing_lists['completed']);
}

if (isset($newsgroups['completed'])) {
  unset($newsgroups['completed']);
}

?>
<p>Choose a date range:</p>
<form method="GET" action="<?php print $this->getFormActionUrl();?>#mailinglists-new">
  <div class="form-group">
    <label class="radio-inline">
      <input type="radio" name="date-range" value="5"> 5 days
    </label>
    <label class="radio-inline">
      <input type="radio" name="date-range" value="7"> 7 days
    </label>
    <label class="radio-inline">
      <input type="radio" name="date-range" value="14"> 14 days
    </label>
  </div>
  <div class="form-group">
    <input type="hidden" name="form_name" value="webmaster-mailinglists">
    <input type="hidden" name="state" value="date-range">
    <input type="submit" class="btn btn-primary">
  </div>
</form>
<?php
  if (empty($mailing_lists)) {
    print '0 mailinglists have been found.<br>';
  }
  else {
    print $this->getMailingListTable($mailing_lists, 'mailing_lists');
  }
  if (empty($newsgroups)) {
    print '0 newsgroups have been found.<br>';
  }
  else {
    print $this->getMailingListTable($newsgroups, 'newsgroups');
  }
