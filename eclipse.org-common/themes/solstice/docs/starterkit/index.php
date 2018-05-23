<?php
/**
 * Copyright (c) 2014-2017, 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 * Eric Poirier (Eclipse Foundation)
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");

$App = new App();

// To change the theme change the following NULL value
// To one of the following:
// "solstice", "quicksilver", "eclipse_ide", "jakarta", "locationtech", "polarsys"
$theme = NULL;
$Theme = $App->getThemeClass($theme);

// Shared variables/configs for all pages of your website.
require_once ('_projectCommon.php');

// Begin: page-specific settings. Change these.
$pageTitle = "Starterkit Template";
$Theme->setPageAuthor('Christopher Guindon');
$Theme->setPageKeywords('Add maximal 20 keywords and seperate them from each other by a comma en a space.');
$Theme->setPageTitle($pageTitle);

if (isset($Nav)) {
  $Theme->setNav($Nav);
}

// Insert header navigation for project websites.
// Bug 436108 - https://bugs.eclipse.org/bugs/show_bug.cgi?id=436108
$links = array();
$links[] = array(
  'icon' => 'fa-download', // Required
  'url' => '/downloads/', // Required
  'title' => 'Download', // Required
  // 'target' => '_blank', // Optional
  'text' => 'Eclipse Distribution, Update Site, Dropins' // Optional
);

$links[] = array(
  'icon' => 'fa-users', // Required
  'url' => '/users/', // Required
  'title' => 'Geting Involved', // Required
  // 'target' => '_blank', // Optional
  'text' => 'CVS, Workspace Setup, Wiki, Committers' // Optional
);

$links[] = array(
  'icon' => 'fa-book', // Required
  'url' => 'http://help.eclipse.org/luna/index.jsp', // Required
  'title' => 'Documentation', // Required
  // 'target' => '_blank', // Optional
  'text' => 'Tutorials, Examples, Videos, Online Reference' // Optional
);

$links[] = array(
  'icon' => 'fa-support', // Required
  'url' => '/forums/', // Required
  'title' => 'Support', // Required
  // 'target' => '_blank', // Optional
  'text' => 'Bug Tracker, Newsgroup Professional Support' // Optional
);

// Initialize custom solstice $variables.
$variables = array();

$variables['header_nav'] = array(
  'links' => $links, // Required
  'logo' => array( // Required
    'src' => '/eclipse.org-common/themes/solstice/public/images/logo/eclipse-800x188.png', // Required
    'alt' => 'The Eclipse Foundation', // Optional
    'url' => 'http://www.eclipse.org' // Optional
    // 'target' => '_blank' // Optional
  )
);

// CFA Link - Big orange button in header
$variables['btn_cfa'] = array(
  'hide' => FALSE, // Optional - Hide the CFA button.
  'html' => '', // Optional - Replace CFA html and insert custom HTML.
  'class' => 'btn btn-huge btn-warning', // Optional - Replace class on CFA link.
  'href' => '//www.eclipse.org/downloads/', // Optional - Replace href on CFA link.
  'text' => '<i class="fa fa-download"></i> Download' // Optional - Replace text of CFA link.
);

// Set Solstice theme variables. (Array)
$App->setThemeVariables($variables);

// Place your html content in a file called content/en_pagename.php
ob_start();
include ("content/en_" . $App->getScriptName());
$html = ob_get_clean();
$Theme->setHtml($html);

// Insert extra html before closing </head> tag.
// $App->AddExtraHtmlHeader('<link rel="stylesheet" type="text/css"
// href="style.css" media="screen" />');

// Insert script/html before closing </body> tag.
// $App->AddExtraJSFooter('<script type="text/javascript"
// src="script.min.js"></script>');

// Generate the web page
$Theme->generatePage();
