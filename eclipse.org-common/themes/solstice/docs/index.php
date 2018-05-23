<?php
/**
 * Copyright (c) 2014, 2015, 2016, 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Christopher Guindon (Eclipse Foundation) - Initial implementation
 *   Eric Poirier (Eclipse Foundation)
 *
 * SPDX-License-Identifier: EPL-2.0
 */

require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php");
require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php");

$App = new App();
$Nav = new Nav();
$Menu = new Menu();

// Begin: page-specific settings. Change these.
$pageTitle = " How to use the Eclipse Solstice theme";
$pageKeywords = "eclipse solstice";
$pageAuthor = "Christopher Guindon";

$theme = NULL;
$allowed_themes = array(
  'polarsys',
  'locationtech',
  'solstice',
  'eclipse_ide',
  'quicksilver'
);
$_theme = $App->getHTTPParameter('theme');
if (in_array($_theme, $allowed_themes)) {
  $theme = $_theme;
}
$Theme = $App->getThemeClass($theme);

ob_start();
?>
<div class="jumbotron featured-jumbotron">
  <div class="container">
    <div class="row">
      <div class="col-md-20 col-md-offset-2 col-sm-18 col-sm-offset-3">
        <h1><?php print $pageTitle;?></h1>
      </div>
    </div>
  </div>
</div>
<?php
$extra_header_html = ob_get_clean();
$Theme->setExtraHeaderHtml($extra_header_html);

// Place your html content in a file called content/en_pagename.php
ob_start();
include ("content/en_" . $App->getScriptName());
$html = ob_get_clean();

$variables['btn_cfa'] = array(
  'hide' => FALSE, // Optional - Hide the CFA button.
  'html' => '', // Optional - Replace CFA html and insert custom HTML.
  'class' => 'btn btn-huge btn-info', // Optional - Replace class on CFA link.
  'href' => '/eclipse.org-common/themes/solstice/docs/starterkit/solstice-starterkit.zip',
  'text' => '<i class="fa fa-download"></i> Download StarterKit'
);

// Set Solstice theme variables (Array)
$App->setThemeVariables($variables);

$Theme->setDisplayHeaderRight(FALSE);
$Theme->setAttributes('header-wrapper', 'header-default-bg-img');
$Theme->setHtml($html);
$Theme->generatePage();