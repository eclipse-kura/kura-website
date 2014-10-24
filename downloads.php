<?php include('includes/header.php') ?>
<div class="container" style="min-height: 800px;">
  <div class="row">
      <div class="col-md-6">
          <h2>Kura Downloads</h2>
          <p>The below downloads are distributed through the Eclipse Foundation and meet all requirements of the <a href="https://www.eclipse.org/legal/epl-v10.html" target="_blank">Eclipse Public License</a>.</p>
          <ul>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.0.0/kura_1.0.0_raspberry-pi_armv6.deb" target="_blank">Raspbian - Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/SNAPSHOT/kura-raspberry-pi_1.0.0-SNAPSHOT_armv6.deb" target="_blank">Raspbian - Development</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.0.0/kura_1.0.0_beaglebone_debian_armv7.deb" target="_blank">BeagleBone - Stable</a></li></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/SNAPSHOT/kura-beaglebone_debian_1.0.0-SNAPSHOT_armv7.deb" target="_blank">BeagleBone - Development</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.0.0/user_workspace_archive_1.0.0.zip" target="_blank">Developer's Workspace</a></li>
          </ul>
      </div>
      <div class="col-md-6">
          <h2>Kura Extended Downloads</h2>
          <p>The below downloads contain the web UI and CAN bundles. These features <b>do not</b> adhere to the requirements of the <a href="https://www.eclipse.org/legal/epl-v10.html" target="_blank">Eclipse Public License</a>
            and <b>are not</b> covered by EPL.
          </p>
          <ul>
              <li style="color: #4f5c6d"><a href="https://s3.amazonaws.com/kura_downloads/raspbian/release/1.0.0/kura_1.0.0_raspberry-pi_armv6.deb" target="_blank">Raspbian (with Web UI) - Stable</a></li>
              <li style="color: #4f5c6d"><a href="https://s3.amazonaws.com/kura_downloads/debian/release/1.0.0/kura_1.0.0_beaglebone_debian_armv7.deb" target="_blank">BeagleBone (with Web UI) - Stable</a></li>
              <li style="color: #4f5c6d"><a href="https://s3.amazonaws.com/kura_downloads/user_workspace/1.0.0/user_workspace_archive_1.0.0.zip" target="_blank">Developer's Workspace (with Web UI)</a></li>
              <li style="color: #4f5c6d"><a href="https://s3.amazonaws.com/kura_downloads/raspbian/snapshot/kura_1.1.0-SNAPSHOT_raspberry-pi_armv6.deb" target="_blank">Raspbian Snapshot Release</a></li>
          </ul>
      </div>
  </div>
  <a name="source_code"></a>
  <h2>Source Code</h2>
  <p>
    The source code for Eclipse Kura is available at Github <a href="https://github.com/eclipse/kura" target="_blank">here</a>
  </p>
  <a name="debian_packages"></a>
  <h2>Raspbian/Debian Packages</h2>
  <p>
    Installation instructions for the Raspberry Pi and BeagleBone Black can be found at the below locations:</p>
    <ul>
        <li style="color: #4f5c6d"><a href="http://eclipse.github.io/kura/doc/raspberry-pi-quick-start.html" target="_blank">Raspberry Pi Quick Start</a></li>
        <li style="color: #4f5c6d"><a href="http://eclipse.github.io/kura/doc/beaglebone-quick-start.html" target="_blank">BeagleBone Black Quick Start</a></li>
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
  <h2>Other</h2>
  <p>
    Other artifacts of the Eclipse build system include:
    <ul>
        <li style="color: #4f5c6d">The Raspberry jars. If you want to manually install Kura on your Raspberry, grab the archive of Kura
           <a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.0.0/kura_1.0.0_raspberry-pi.zip" target="_blank">here</a></li>
    </ul>
  </p>
</div>
<?php include('includes/footer.php') ?>
