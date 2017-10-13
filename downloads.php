<?php include('includes/header.php') ?>
<div class="container" style="min-height: 800px;">
  <div class="row">
      <div class="col-md-12">
          <h2>Eclipse Kura&trade; 3.1.0 Downloads</h2>
          <h4>Released on Oct 12th, 2017 - <a href="https://github.com/eclipse/kura/blob/KURA_3.1.0_RELEASE/kura/distrib/RELEASE_NOTES.txt">Release Notes</a></h4>
          <p>With the release of Eclipse Kura&trade; 2.0.0, there is no longer a need for multiple downloads (with and without web UI) per platform. Each installer now contains the updated web UI which is
              EPL compatible. If you still need an installation that does not include the web UI, please make a request to the Kura mailing list. The below downloads are distributed
              through the Eclipse Foundation and meet all requirements of the <a href="https://www.eclipse.org/legal/epl-v10.html" target="_blank">Eclipse Public License</a>.
          </p>
          <p>
              If you are looking for a previous version, check the <a href="archives.php">archives</a>.
          </p>
          <ul>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_raspberry-pi-2-3_installer.deb" target="_blank">Raspbian (Model 2 or 3) - Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_raspberry-pi-2-3-nn_installer.deb" target="_blank">Raspbian (Model 2 or 3, No Net)- Stable</a></li>

              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_raspberry-pi_installer.deb" target="_blank">Raspbian - Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_raspberry-pi-nn_installer.deb" target="_blank">Raspbian (No Net)- Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_raspberry-pi-bplus_installer.deb" target="_blank">Raspbian (Model B+)- Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_raspberry-pi-bplus-nn_installer.deb" target="_blank">Raspbian (Model B+, No Net)- Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_beaglebone_debian_installer.deb" target="_blank">BeagleBone - Stable</a></li></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_beaglebone-nn_debian_installer.deb" target="_blank">BeagleBone (No Net) - Stable</a></li></li>

              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura-fedora-3.1.0-1-armv7hl.rpm" target="_blank">Fedora ARM (Raspberry Model 2 or 3) - Experimental</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_intel-edison-nn_installer.sh" target="_blank">Intel Edison (No Net) - Experimental</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/kura_3.1.0_aarch64-nn_installer.sh" target="_blank">Aarch 64 (No Net) - Experimental</a></li>

              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.1.0/user_workspace_archive_3.1.0.zip" target="_blank">Developer's Workspace</a></li>
          </ul>
      </div>
  </div>
  <a name="debian_packages"></a>
  <h2>Raspbian/Debian Packages</h2>
  <p>
    Installation instructions for the Raspberry Pi and BeagleBone Black can be found at the below locations:</p>
    <ul>
        <li style="color: #4f5c6d"><a href="http://eclipse.github.io/kura/intro/raspberry-pi-quick-start.html" target="_blank">Raspberry Pi Quick Start</a></li>
        <li style="color: #4f5c6d"><a href="http://eclipse.github.io/kura/intro/beaglebone-quick-start.html" target="_blank">BeagleBone Black Quick Start</a></li>
    </ul>
  <h2>Application Developer Workspace</h2>
  <p>
    A workspace archive is available to help speed up the application development process. The archive contains all the necessary APIs and
    jar files to develop applications without building Kura from scratch. Simply import the archive into an Eclipse workspace and start coding.
  </p>
  <h2>Repositories</h2>
  <p>Eclipse hosts a Nexus for those who want to use Maven to manage their dependencies. The release and snapshot repositories can be found here:</p>
    <ul>
      <li style="color: #4f5c6d"><a href="https://repo.eclipse.org/content/repositories/kura-releases" target="_blank">Release</a></li>
      <li style="color: #4f5c6d"><a href="https://repo.eclipse.org/content/repositories/kura-snapshots/" target="_blank">SNAPSHOTS</a></li>
    </ul>

</div>
<?php include('includes/footer.php') ?>
