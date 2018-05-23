<?php
/*******************************************************************************
 * Copyright (c) 2015,2016 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Eric Poirier (Eclipse Foundation) - Initial implementation
 *    Christopher Guindon (Eclipse Foundation)
 *******************************************************************************/
?>
<p class="float-right padding-top-25">
  <a class="btn btn-warning btn-sm" data-target="#collapseEinstaller" class="solstice-collapse orange" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseEinstaller">
    <i class="fa fa-times"></i> Hide
  </a>
</p>
<h1>5 Steps to Install Eclipse</h1>

<p class="lead">
We've recently introduced the Eclipse Installer, a new and more efficient way
to install Eclipse. It is a proper installer (no zip files), with a
self-extracting download that leads you through the installation process. For
those who prefer not to use the Installer, the packages and zip files are still
available on our <a href="/downloads/eclipse-packages/">package download</a> page.</p>
<hr>
<h2>1. Download the Eclipse Installer</h2>
<?php if (!empty($platforms)) :?>
  <div class="row orange-download-link">
  <?php foreach ($platforms as $platform):?>
    <div class="col-sm-8 padding-top-10 text-center">
      <p><?php print $platform['label'];?></p>
      <ul class="list-inline">
        <li><i class="fa fa-download white"></i></li>
        <?php print implode('', $platform['links']);?>
      </ul>
    </div>
  <?php endforeach;?>
  </div>
<?php else:?>
  <p>Download Eclipse Installer from <a href="/downloads">http://www.eclipse.org/downloads</a></p>
<?php endif;?>
<!--
<img class="img-responsive" src="assets/public/images/installer-instructions-01.png" alt="Screenshot of Eclipse Installer's web page.">
 -->
<p>Eclipse is hosted on many mirrors around the world. Please select
the one closest to you and start to download the Installer</p>
<hr>
<h2>2. Start the Eclipse Installer executable</h2>
<p>For Windows users, after the Eclipse Installer executable has finished downloading it should be
available in your download directory.  Start the Eclipse Installer executable.
You may get a security warning to run this file. If the Eclipse Foundation is
the Publisher, you are good to select Run.</p>
<p>For Mac and Linux users, you will still need to unzip the download to create the Installer.
Start the Installer once it is available.</p>
<img class="img-responsive" src="/downloads/assets/public/images/installer-instructions-02-b.png" alt="Screenshot of the Eclipse Installer executable.">
<hr>
<h2>3. Select the package to install</h2>
<p>The new Eclipse Installer shows the packages available to Eclipse users.
You can search for the package you want to install or scroll through the list.</p>
<p>Select and click on the package you want to install.</p>
<img class="img-responsive" src="/downloads/assets/public/images/installer-instructions-03.png" alt="Screenshot of the Eclipse packages.">
<hr>
<h2>4. Select your installation folder</h2>
<p>Specify the folder where you want Eclipse to be installed. The default folder will be in your User directory.</p>
<p>Select the ‘Install’ button to begin the installation.</p>
<img class="img-responsive" src="/downloads/assets/public/images/installer-instructions-04.png" alt="Screenshot of the Install window.">
<hr>
<h2>5. Launch Eclipse</h2>
<p>Once the installation is complete you can now launch Eclipse.
The Eclipse Installer has done it's work. Happy coding.</p>
<img class="img-responsive" src="/downloads/assets/public/images/installer-instructions-05.png" alt="Screenshot of the Launch window.">
<p class="text-right padding-top-25">
  <a class="btn btn-warning btn-sm" data-target="#collapseEinstaller" class="solstice-collapse orange" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseEinstaller">
    <i class="fa fa-times"></i> Hide
  </a>
</p>