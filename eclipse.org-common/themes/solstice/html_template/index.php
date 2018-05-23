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

require_once (realpath(dirname(__FILE__) . '/../../../system/app.class.php'));
$App = new App();

$Theme = $App->getThemeClass($App->getHTTPParameter('theme'));
$Theme->setPageAuthor("Christopher Guindon");
$Theme->setPageKeywords("eclipse.org, Eclipse Foundation");
$Theme->setPageTitle('HTML Template');
$Theme->setHtml('<h1>HTML Template</h1>');
$Theme->setLayout($App->getHTTPParameter('layout'));
$Theme->generatePage();

