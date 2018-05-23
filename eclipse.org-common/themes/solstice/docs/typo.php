<?php
/**
 * Copyright (c) 2014, 2015, 2016, 2018 Eclipse Foundation.
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
$Theme = $App->getThemeClass();

// Begin: page-specific settings. Change these.
$pageTitle = "Solstice typography";
$Theme->setPageAuthor('Christopher Guindon');
$Theme->setPageKeywords('eclipse solstice');
$Theme->setPageTitle($pageTitle);

require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php");

$Nav = new Nav();
$Nav->addNavSeparator("Related Links", "");
$Nav->addCustomNav("Documentation", "/eclipse.org-common/themes/solstice/docs/", "_self", 1);
$Theme->setNav($Nav);

// Place your html content in a file called content/en_pagename.php
ob_start();
include ("content/en_" . $App->getScriptName());
$html = ob_get_clean();

$App->AddExtraHtmlHeader('<link rel="stylesheet" type="text/css" href="//eclipse.org/orion/editor/releases/5.0/built-editor.css"/>');
$App->AddExtraHtmlHeader('<script src="//eclipse.org/orion/editor/releases/5.0/built-editor.min.js"></script>');
$App->AddExtraHtmlHeader('<script>
	/*global require*/
	require(["orion/editor/edit"], function(edit) {
		edit({className: "editor"});
	});
</script>');

$Theme->setHtml($html);
$Theme->generatePage();