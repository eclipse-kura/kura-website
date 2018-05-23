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

$mailing_list = $this->getMailingLists();
$newsgroups_list = $this->getNewsgroups();

$mailinglist['completed'] = array();
$newsgroups['completed'] = array();

if (!empty($mailing_list['completed'])) {
  $mailinglist['completed'] = $mailing_list['completed'];
}

if (!empty($newsgroups_list['completed'])) {
  $newsgroups['completed'] = $newsgroups_list['completed'];
}

print $this->getMailingListTable($mailinglist, 'mailing_lists');
print $this->getMailingListTable($newsgroups, 'newsgroups');