<?php include('includes/header.php') ?>
<div class="container" style="min-height: 800px;">
  <a name="source_code"></a>
  <h2>Source Code</h2>
  <p>
    The source code for Eclipse Kura is available at Github <a href="https://github.com/eclipse/kura" target="_blank">here</a>
  </p>
  <a name="debian_packages"></a>
  <h2>Raspbian/Debian Packages</h2>
  <p>
    A Debian package for the Raspbian operating system is available from the Eclipse build system. Note: These packages <b>do not</b> contain the
    web UI or CAN bus support. If you are interested in these options, please read below the list.<br>
    <ul>
      <li style="color: #4f5c6d">The latest stable version for Raspbian can be found <a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.0.0/kura_1.0.0_raspberry-pi_armv6.deb" target="_blank">here</a></li>
      <li style="color: #4f5c6d">The latest development version for Raspbian can be found <a href="http://www.eclipse.org/downloads/download.php?file=/kura/SNAPSHOT/kura-raspberry-pi_1.0.0-SNAPSHOT_armv6.deb" target="_blank">here</a></li>
      <li style="color: #4f5c6d">The latest stable version for the BeagleBone Black rev C with Debian can be found <a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.0.0/kura_1.0.0_beaglebone_debian_armv7.deb" target="_blank">here</a></li></li>
      <li style="color: #4f5c6d">The latest development version for the BeagleBone Black rev C with Debian can be found <a href="http://www.eclipse.org/downloads/download.php?file=/kura/SNAPSHOT/kura-beaglebone_debian_1.0.0-SNAPSHOT_armv7.deb" target="_blank">here</a></li>
    </ul>
    The web UI and CAN bundle are not part of the Eclipse build system due to conflicts with the <a href="https://www.eclipse.org/legal/epl-v10.html" target="_blank">Eclipse Public License</a> We are working
    to resolve this and hope to have these options in the Eclipse build system in future releases. For now, these package can be downloaded at the below locations. Note: these packages
    <b>are not</b> covered by EPL!
    <ul>
      <li style="color: #4f5c6d">The latest stable version for Raspbian can be found <a href="https://s3.amazonaws.com/kura_downloads/raspbian/release/1.0.0/kura_1.0.0_raspberry-pi_armv6.deb" target="_blank">here</a></li>
      <li style="color: #4f5c6d">The latest stable version for the BeagleBone Black rev C with Debian can be found <a href="https://s3.amazonaws.com/kura_downloads/debian/release/1.0.0/kura_1.0.0_beaglebone_debian_armv7.deb" target="_blank">here</a></li>
    </ul>
    Install the package by issuing the command:<br>
    <PRE>dpkg -i &lt;deb_package_name&gt;.deb</PRE>
  </p>
  <h2>Application Developer Workspace</h2>
  <p>
    A workspace archive is available to help speed up the application development process. The archive contains all the necessary APIs and
    jar files to develop applications without building Kura from scratch. Simply import the archive into an Eclipse workspace and start coding.
    <ul>
      <li style="color: #4f5c6d">The latest stable version *with* the web UI can be found <a href="https://s3.amazonaws.com/kura_downloads/user_workspace/1.0.0/user_workspace_archive_1.0.0.zip" target="_blank">here</a></li>
      <li style="color: #4f5c6d">The latest stable version *without* the web UI can be found <a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.0.0/user_workspace_archive_1.0.0.zip" target="_blank">here</a></li>
    </ul>
  </p>
  <h2>Repositories</h2>
  <p>Eclipse hosts a Nexus for those who want to use Maven to manage their dependencies. The release and snapshot repositories can be found here:
    <ul>
      <li style="color: #4f5c6d"><a href="https://repo.eclipse.org/content/repositories/kura-releases" target="_blank">Release</a></li>
      <li style="color: #4f5c6d"><a href="https://repo.eclipse.org/content/repositories/kura-snapshots/" target="_blank">SNAPSHOTS</a></li>
    </ul>
  </p>
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
