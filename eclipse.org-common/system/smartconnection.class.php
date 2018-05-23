<?php
/*******************************************************************************
 * Copyright (c) 2006, 2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Wayne Beaton + Nathan Gervais (Eclipse Foundation)- initial API and implementation
 *    Christopher Guindon (Eclipse Foundation) - Bug 496509 - Remove old database files from eclipse.org-common
 *******************************************************************************/

  if (file_exists("/home/data/httpd/eclipse-php-classes/system/dbconnection.class.php")) {
    require_once("/home/data/httpd/eclipse-php-classes/system/dbconnection.class.php");
  }

  // Possibly deprecated: Epic does not exist, will this connect to marketplace?
  if (file_exists("/home/data/httpd/eclipse-php-classes/system/dbconnection_epic_ro.class.php")) {
    require_once("/home/data/httpd/eclipse-php-classes/system/dbconnection_epic_ro.class.php");
  }

  // Possibly deprecated: live.eclipse.org does not exist, is this still needed?
  if (file_exists("/home/data/httpd/eclipse-php-classes/system/dbconnection_live_rw.class.php")) {
    require_once ("/home/data/httpd/eclipse-php-classes/system/dbconnection_live_rw.class.php");
  }