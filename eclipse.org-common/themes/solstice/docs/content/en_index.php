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
?>

<style>
hr{
  margin-bottom:3em;
}
</style>
<h1><?php print $pageTitle;?></h1>

<p>The Solstice theme was built on top of Bootstrap which is a sleek, intuitive,
and powerful front-end framework for faster and easier web development.</p>
<p>We support most UI components from <a href="https://wiki.eclipse.org/Nova">Nova</a>. We’re hoping that the transition won’t be too hard for most pages.</p>
<h2>What's included with Solstice?</h2>
<ul>
  <li><a href="http://getbootstrap.com/">Bootstrap</a> v3.3.7</li>
  <li><a href="http://fortawesome.github.io/Font-Awesome/">Font Awesome</a> v4.7.0 </li>
  <li><a href="https://feathericons.com/">Feather</a> v4.7.0 </li>
  <li><a href="http://jquery.com/">jQuery</a> v2.1.1</li>
  <li><a href="https://github.com/chrisguindon/solstice-assets">Solstice Assets</a> (Less files &amp; images)</li>
  <li><a href="http://geedmo.github.io/yamm3/">Yamm3</a> (Yet another megamenu for Bootstrap 3)</li>
</ul>

<h2>Getting Started</h2>
<ul>
  <li><a href="https://www.youtube.com/watch?v=AbSC3506sz0&feature=youtu.be">Committer and Contributor Hangout Series -- Eclipse Website Refresh</a></li>
  <li>Read the documentation for <a href="http://getbootstrap.com/css/">Bootstrap</a> &amp; <a href="http://jquery.com/">jQuery</a></li>
  <li><a href="http://wiki.eclipse.org/Using_Phoenix">How to use Phoenix</a></li>
</ul>

<h2>Initiate a theme</h2>
<p>These are the possible parameter values you can pass into $App->getThemeClass():</p>
<ul>
  <li>NULL (default theme)</li>
  <li>solstice</li>
  <li>eclipse_ide</li>
  <li>quicksilver</li>
</ul>
<p>Example:</p>
<pre>
&lt;?php

$Theme = $App->getThemeClass("quicksilver");
</pre>

<h2>Using Solstice</h2>
<p>On a page using the eclipse.org-common $Theme Class, use this to make sure your page is always using the default theme:</p>
<pre>
&lt;?php
$Theme->generatePage();
</pre>

<div class="alert alert-warning">
  <h2 id="gdpr">General Data Protection Regulation (GDPR)</h2>

  <p>The General Data Protection Regulation (GDPR), a new regulation in EU law on data protection and privacy
  for all individuals within the European Union becomes enforceable on 25 May 2018.</p>

  <h3>Web Analytics Tools</h3>
  <p>We will not allow committers or project leads to collect user data or track user activity on
  Eclipse Foundation-owned domains, since that data may be shared with the third-party companies who employ them --
  an action for which our users have not given explicit consent.</p>

  <p>Using project-specific Web Analytics Tools will be prohibited as of May 24.
  The Eclipse Foundation has its own Google Analytics code, which is included with the unmodified Quicksilver theme.</p>

  <h3>Google Analytics</h3>
  <p class="fw-700">Projects who are not using our unmodified Quicksilver theme can still include the Eclipse Foundation Google Analytics code
  by inserting the following code snippet in the <head> of each page:</p>
  <br/>
  <pre>
  <?php print htmlentities($Theme->getGoogleTagManager());?>
  </pre>

  <h3>Cookie Consent Banner</h3>

  <p class="fw-700">If you are not using the Eclipse Foundation look and feel, you can still load our
  cookie consent banner, which include a link to the Eclipse Foundation Private Policy, by adding the following
  code snippet in the &lt;head&gt; of each page:</p>
  <br/>
  <pre>
  <?php print htmlentities('<link rel="stylesheet" type="text/css" href="//www.eclipse.org/eclipse.org-common/themes/solstice/public/stylesheets/vendor/cookieconsent/cookieconsent.min.css" />'). PHP_EOL; ?>
  <?php print htmlentities('<script src="//www.eclipse.org/eclipse.org-common/themes/solstice/public/javascript/vendor/cookieconsent/default.min.js"></script>');?>
  </pre>

  <h3>Validating Consent</h3>
  <p class="fw-700">If you include widgets from a 3rd party website, you might need to validate consent before you can include it:</p>
    <br/>
  <pre>
  &lt;?php
  if ($Theme->hasCookieConsent()) {
    //Insert widgets from a 3rd party
  }
  </pre>

</div>
<h2 id="starterkit">Starterkit</h2>
<p>The <a href="/eclipse.org-common/themes/solstice/docs/starterkit/">starterkit</a> includes all the files required to create a <strong>standard page</strong> and also a <strong>Press Release</strong> page with Solstice. The source code is available <a href="http://git.eclipse.org/c/www.eclipse.org/eclipse.org-common.git/tree/themes/solstice/docs/starterkit/">here</a>.</p>
<p> <p><a href="/eclipse.org-common/themes/solstice/docs/starterkit/solstice-starterkit.zip" class="btn btn-warning">Download Starterkit</a></p></p>
<br/>

<h2>Theme variables</h2>
<p>It's now possible to alter the Solstice theme using <code>$App->setThemeVariables($variables);</code>.</p>

<pre>
&lt;?php

  // Initialize $variables.
  $variables = array();

  // Add classes to &lt;body&gt;. (String)
  $variables['body_classes'] = '';

  // Insert custom HTML in the breadcrumb region. (String)
  $variables['breadcrumbs_html'] = "";

  // Hide the breadcrumbs. (Bool)
  $variables['hide_breadcrumbs'] = TRUE;

  // Insert HTML before the left nav. (String)
  $variables['leftnav_html'] = '';

  // Update the main container class, this is usefull if you want to use the full width of the page. (String)
  // Eclipse.org homepage is a good example: https://www.eclipse.org/home/index.php
  $variables['main_container_classes'] = 'container-full';

  // Insert HTML after opening the main content container, before the left sidebar. (String)
  $variables['main_container_html'] = '';

  // Set Solstice theme variables (Array)
  $App->setThemeVariables($variables);
</pre>

<h2>Templates</h2>
<?php
  $themes = array('default','eclipse_ide', 'polarsys', 'locationtech');
?>
<?php foreach ($themes as $t) :?>
    <h3><?php print ucfirst($t);?></h3>
    <div class="well clearfix">
      <div class="col-md-8">
        <h4>Default layout</h4>
      <ul>
        <li><a href="/eclipse.org-common/themes/solstice/html_template/index.php?theme=<?php print $t;?>&layout=default-header">Header</a></li>
        <li><a href="/eclipse.org-common/themes/solstice/html_template/index.php?theme=<?php print $t;?>&layout=default-footer">Footer</a></li>
        <li><a href="/eclipse.org-common/themes/solstice/html_template/index.php?theme=<?php print $t;?>&layout=default">Full page</a></li>
        <li><a href="/eclipse.org-common/themes/solstice/html_template/index.php?theme=<?php print $t;?>&layout=default-fluid">Fluid (Full page)</a></li>
      </ul>
    </div>
    <div class="col-md-8">
      <h4>Thin layout <small>(<a href="https://bugs.eclipse.org/bugs/show_bug.cgi?id=437384">Bug #437384</a>)</small></h4>
      <ul>
        <li><a href="/eclipse.org-common/themes/solstice/html_template/index.php?theme=<?php print $t;?>&layout=thin-header">Thin header</a></li>
        <li><a href="/eclipse.org-common/themes/solstice/html_template/index.php?theme=<?php print $t;?>&layout=thin">Full page</a></li>
      </ul>
    </div>
    <div class="col-md-8">
      <h4 id="tpl-barebone">Barebone layout*</h4>
      <ul>
        <li><a href="/eclipse.org-common/themes/solstice/html_template/index.php?theme=<?php print $t;?>&layout=barebone">Barebone header</a></li>
      </ul>
    </div>
  </div>
<?php endforeach;?>
<p>*A barebone HTML header &amp; footer to adapt the look to subsites, such as Bugzilla, Forums, Mailing lists &amp; events.eclipse.org.</p>
<pre>
  // List of available layout to chose from
  $acceptable_layouts = array(
    'default',
    'default-header',
    'default-footer',
    'default-fluid',
    'barebone',
    'thin',
    'thin-header',
    'default-with-footer-min',
    'thin-with-footer-min',
  );
  $Theme->setLayout($acceptable_layouts[0]);
</pre>

<h2>Eclipse.org-common Classes</h2>
<p> Visit our <a href="classes.php">PHP classes</a> page for some documentation and example of how to use the API from eclipse.org-common.</p>

<h2>CSS</h2>
<p><a href="https://github.com/chrisguindon/solstice-assets/blob/master/stylesheets/classes.less">classes.less</a>
and <a href="https://github.com/chrisguindon/solstice-assets/blob/master/stylesheets/fonts.less">fonts.less</a> include usefull CSS classes for
colors, font-weight &amp; font size and offsets to remove the margin after the breadcrumbs or before the footer.</p>

<p><a href="typo.php">Typography</a> examples with solstice.</p>

<h2>Custom Components</h2>
<ol>
  <li><a href="#section-block-box">Block-box</a></li>
  <li><a href="#section-breadcrumbs">Breadcrumbs</a></li>
  <li><a href="#section-btncfa">Call For Action Button link</a></li>
  <li><a href="#section-dragdrop">Marketplace Drag and Drop install</a></li>
  <li><a href="#section-headernav">Header Nav</a></li>
  <li><a href="#section-headerrow">Header Row</a></li>
  <li><a href="#section-highlight">Block Highlight</a></li>
  <li><a href="#section-landing-well">Landing well</a></li>
  <li><a href="#section-news-list">News list</a></li>
  <li><a href="#section-stepbystep">Step by Step</a></li>
  <li><a href="#section-timeline">Timeline</a></li>
  <li><a href="#section-toolbarmenu">Toolbar Menu</a></li>
</ol>

<?php include('components/block-box.php');?><hr/>
<?php include('components/breadcrumbs.php');?><hr/>
<?php include('components/btn-cfa.php');?><hr/>
<?php include('components/dragdrop.php');?><hr/>
<?php include('components/header-nav.php');?><hr/>
<?php include('components/headerrow.php');?><hr/>
<?php include('components/highlight.php');?><hr/>
<?php include('components/landing-well.php');?><hr/>
<?php include('components/news-list.php');?><hr/>
<?php include('components/step-by-step.php');?><hr/>
<?php include('components/timeline.php');?><hr/>
<?php include('components/toolbar-menu.php');?><hr/>

<h2 id="bootstrap">Bootstrap example</h2>
<?php include('bootstrap/carousel.php');?><hr/>


