<?php
/**
 * Copyright (c) 2018 Eurotech and/or its affiliates.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/include/kura.class.php");

function renderPage($path) {

$App = new App();
$Theme = new KuraTheme($App);

// Shared variables/configs for all pages of your website.
require_once ('_projectCommon.php');

// Begin: page-specific settings. Change these.

$Theme->setPageKeywords('iot, m2m, kura, gateway, java, osgi, embedded');
$Theme->setPageTitle("Eclipse Kura");
$Theme->setBaseUrl('http://www.eclipse.org/kura/');

if (isset($Nav)) {
  $Theme->setNav($Nav);
}


// Initialize custom solstice $variables.
$variables = array();

// Add classes to <body>. (String)
$variables['body_classes'] = '';

// Hide the breadcrumbs. (Bool)
$variables['hide_breadcrumbs'] = TRUE;

// Insert HTML before the left nav. (String)
$variables['leftnav_html'] = '';

// Update the main container class (String)
$variables['main_container_classes'] = 'container';

// Insert HTML after opening the main content container, before the left
// sidebar. (String)
$variables['main_container_html'] = '';

// Insert header navigation for project websites.
// Bug 436108 - https://bugs.eclipse.org/bugs/show_bug.cgi?id=436108
$links = array();

// CFA Link - Big orange button in header
$variables['btn_cfa'] = array(
  'hide' => FALSE, // Optional - Hide the CFA button.
  'html' => '', // Optional - Replace CFA html and insert custom HTML.
  'class' => 'btn btn-huge btn-warning', // Optional - Replace class on CFA link.
  'href' => '/downloads.php', // Optional - Replace href on CFA link.
  'text' => '<i class="fa fa-download"></i> Download' // Optional - Replace text of CFA link.
);

// Set Solstice theme variables. (Array)
$App->setThemeVariables($variables);

$Theme->setAttributes('img_logo_default', 'content/images/kura_logo_small.png', 'src');
$Theme->setAttributes('img_logo_mobile', 'content/images/kura_logo_small.png', 'src');

$Menu = new Menu();
$Menu->setMenuItemList(array());
$Menu->addMenuItem("Documentation", "http://eclipse.github.io/kura/", "_self");
$Menu->addMenuItem("Marketplace", "https://marketplace.eclipse.org/taxonomy/term/4397%2C4396/title", "_self");

$Theme->setMenu($Menu);

$more_menu = array();
$more_menu['Community'][] = array(
  'url' => 'community.php#mailing-list',
  'caption' => 'Mailing List'
);

$more_menu['Community'][] = array(
  'url' => 'community.php#issue-tracker',
  'caption' => 'Issue Tracker'
);

$more_menu['Community'][] = array(
  'url' => 'community.php#discussion-forum',
  'caption' => 'Discussion Forum'
);

$more_menu['Community'][] = array(
  'url' => 'community.php#contributing',
  'caption' => 'Contributing'
);

$more_menu['Community'][] = array(
  'url' => 'community.php#benefactors',
  'caption' => 'Benefactors'
);

$Theme->setMoreMenu($more_menu);
$Theme->setDisplayGoogleSearch(false);
$Theme->setDisplayToolbar(false);

// Place your html content in a file called content/en_pagename.php
ob_start();
include ($path);
echo('<script defer>
document.querySelector(".eclipse-more .dropdown-toggle").innerHTML = "Community<b class=caret></b>"
</script>');
$html = ob_get_clean();
$Theme->setHtml($html);

$App->AddExtraHtmlHeader('<link rel="stylesheet" type="text/css"
href="content/css/style.css" media="screen" />');

// Insert script/html before closing </body> tag.
// $App->AddExtraJSFooter('<script type="text/javascript"
// src="script.min.js"></script>');

// Generate the web page
$Theme->generatePage();
}
