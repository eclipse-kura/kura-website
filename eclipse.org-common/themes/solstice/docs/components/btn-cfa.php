<?php
/**
 * Copyright (c) 2014, 2018 Eclipse Foundation.
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
?>

<h3 id="section-btncfa">Call For Action Button link</h3>
<p>Update or replace the CFA buttonin the header of solstice.</p>
<?php print $Theme->getCfaButton();;?>

<h4>PHP Code</h4>
<pre>
&lt;?php

  $variables = array();

  // CFA Link - Big orange button in header
  $variables['btn_cfa'] = array(
    'hide' => FALSE, // Optional - Hide the CFA button.
    'html' => '', // Optional - Replace CFA html and insert custom HTML.
    'class' => 'btn btn-huge btn-warning', // Optional - Replace class on CFA link.
    'href' => '//www.eclipse.org/downloads/', // Optional - Replace href on CFA link.
    'text' => '&lt;i class="fa fa-download"&gt;&lt;/i&gt; Download' // Optional - Replace text of CFA link.
  );

  // Set Solstice theme variables (Array)
  $App->setThemeVariables($variables);

</pre>
<h4>HTML Output</h4>

<pre><?php print htmlentities($Theme->getCfaButton()); ?></pre>