<?php
/*******************************************************************************
 * Copyright (c) 2014 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Christopher Guindon (Eclipse Foundation) - Initial implementation
 *******************************************************************************/

  require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php");


  $App   = new App();
  $Nav  = new Nav();
  $Menu   = new Menu();

  # Begin: page-specific settings.  Change these.
  $pageTitle     = " eclipse.org-common Documentation";
  $pageKeywords  = "eclipse eclipse.org api, eclipse.org-common";
  $pageAuthor    = "Christopher Guindon";


  // Place your html content in a file called content/en_pagename.php
  ob_start();
  include("content/en_" . $App->getScriptName());
  $html = ob_get_clean();

  $App->generatePage(NULL, $Menu, NULL, $pageAuthor, $pageKeywords, $pageTitle, $html);

