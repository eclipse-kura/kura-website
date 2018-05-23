<?php
/**
 * Copyright (c) 2014-2017, 2018 Eclipse Foundation.
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

require_once ($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php");

$Nav  = new Nav();

// Set the theme for your project's web pages.
// See the Committer Tools "Phoenix" secion in the How Do I? for list of themes
// https://dev.eclipse.org/committers/
$theme = NULL;

// Define your project-wide Nav bars here.
// Format is Link text, link URL (can be http://www.someothersite.com/), target
// (_self, _blank).
$Nav->addNavSeparator("Solstice", "/eclipse.org-common/themes/solstice/docs/");
$Nav->addCustomNav("Documentation", "/eclipse.org-common/themes/solstice/docs/", "_self", NULL);
$Nav->addCustomNav("Source code", "http://git.eclipse.org/c/www.eclipse.org/eclipse.org-common.git/tree/themes/solstice/", "_self", NULL);
$Nav->addCustomNav("Using Phoenix", "http://wiki.eclipse.org/Using_Phoenix", "_self", NULL);
