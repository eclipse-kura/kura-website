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

<?php ob_start(); ?>
<div class="timeline">
  <div class="row">
    <div class="col-md-6 one gs-item">
      <div class="circle">1</div>
      <h1 class="fw-600">Getting Started</h1>
      <p>You can download the standard version of Eclipse that contains the basic bits
        for any Java developer to start coding in Java.
      </p>
      <p><a class="btn btn-warning" href="https://www.eclipse.org/downloads/packages/eclipse-standard-44/lunar">Download</a></p>
      <!-- /downloads/packages/eclipse-standard-432/keplersr2 -->
      <hr>
      <p> Eclipse also has pre-defined packages based on the type of development you want to do with Eclipse.</p>
      <p><a class="btn btn-warning" href="/downloads/">Download Packages </a></p>
    </div>
    <div class="col-md-6 two  gs-item">
      <div class="circle">2</div>
      <h1>Extend Eclipse</h1>
      <p>Eclipse Marketplace is a great source of plug-ins and product that you can add to Eclipse.
        Browse the online catalog or use the <a href="//marketplace.eclipse.org">Eclipse Marketplace</a>
        Client from within Eclipse. Look under the Eclipse Help Menu.
      </p>
      <p>Popular Plugins: </p>
      <ul>
        <li>
          <a href="http://marketplace.eclipse.org/content/subversive-svn-team-provider">Subversive - SVN Team Provider</a><br>
          <div class="drag_installbutton">
            <a href="http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=1139" class="drag">
              <img src="/eclipse.org-common/themes/solstice/public/images/components/drag-drop/installbutton.png">
              <div class="tooltip">
                <h3>Drag to Install!</h3>
                Drag to your running Eclipse workspace.
              </div>
            </a>
          </div>
        </li>
        <li>
          <a href="http://marketplace.eclipse.org/content/eclipse-color-theme">Eclipse Color Theme</a><br>
          <div class="drag_installbutton">
            <a href="http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=27025" class="drag">
              <img src="/eclipse.org-common/themes/solstice/public/images/components/drag-drop/installbutton.png">
              <div class="tooltip">
                <h3>Drag to Install!</h3>
                Drag to your running Eclipse workspace.
              </div>
            </a>
          </div>
        </li>
        <li>
          <a href="http://marketplace.eclipse.org/content/maven-integration-eclipse-juno-and-newer">Maven Integration for Eclipse</a><br>
          <div class="drag_installbutton">
            <a href="http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=252" class="drag">
              <img src="/eclipse.org-common/themes/solstice/public/images/components/drag-drop/installbutton.png">
              <div class="tooltip">
                <h3>Drag to Install!</h3>
                Drag to your running Eclipse workspace.
              </div>
            </a>
          </div>
        </li>
        <li>
          <a href="http://marketplace.eclipse.org/content/pydev-python-ide-eclipse">PyDev</a><br>
          <div class="drag_installbutton">
            <a href="http://marketplace.eclipse.org/marketplace-client-intro?mpc_install=114" class="drag">
              <img src="/eclipse.org-common/themes/solstice/public/images/components/drag-drop/installbutton.png">
              <div class="tooltip">
                <h3>Drag to Install!</h3>
                Drag to your running Eclipse workspace.
              </div>
            </a>
          </div>
        </li>
      </ul>
      <p><a class="btn btn-info" href="//marketplace.eclipse.org">Marketplace</a></p>
    </div>
    <div class="col-md-6 three gs-item">
      <div class="circle">3</div>
      <h1>Documentation</h1>
      <p>These are a few of the popular getting-started documents for someone new to Eclipse: </p>
      <ul>
        <li>Getting Started with the <a href="http://help.eclipse.org/kepler/nav/0">Eclipse Workbench</a></li>
        <li>Getting Started with <a href="http://help.eclipse.org/kepler/nav/1">Java development</a></li>
        <li>All online <a href="http://help.eclipse.org/kepler/index.jsp">Documentation</a></li>
      </ul>
    </div>
    <div class="col-md-6 four gs-item">
      <div class="circle">4</div>
      <h1>Getting Help</h1>
      <ul>
        <li>There are many online sources of help in the Eclipse community. First thing to do is <a href="https://dev.eclipse.org/site_login/createaccount.php">create an account</a> so you can use them.</li>
        <li>Our <a href="http://eclipse.org/forums/">forums</a> are great places to ask questions, especially the <a href="http://www.eclipse.org/forums/index.php/f/89/">newcomer forum</a>.</li>
        <li>Open bugs and feature requests at <a href="https://bugs.eclipse.org/bugs/">bugzilla</a>.</li>
        <li><a href="https://wiki.eclipse.org/IRC">IRC channels</a> are active for some projects.</li>
        <li>Project <a href="https://dev.eclipse.org/mailman/listinfo">mailing list</a> are good source of what is going on in the project.</li>
      </ul>
    </div>
  </div>
</div>
<?php $html = ob_get_clean();?>
<h3 id="section-timeline">Timeline</h3>
<?php print $html; ?>
<h4>Code</h4>
<pre><?php print htmlentities($html); ?></pre>