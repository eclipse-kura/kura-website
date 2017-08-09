<?php include('includes/header.php') ?>
<div class="container" style="min-height: 800px;">
  <div class="row">
      <div class="col-md-12">
          <h2>Eclipse Kura&trade; 3.0.0 Downloads</h2>
          <h4>Released on May 5th, 2017 - <a href="https://github.com/eclipse/kura/blob/KURA_3.0.0_RELEASE/kura/distrib/RELEASE_NOTES.txt">Release Notes</a></h4>
          <p>With the release of Eclipse Kura&trade; 2.0.0, there is no longer a need for multiple downloads (with and without web UI) per platform. Each installer now contains the updated web UI which is
              EPL compatible. If you still need an installation that does not include the web UI, please make a request to the Kura mailing list. The below downloads are distributed
              through the Eclipse Foundation and meet all requirements of the <a href="https://www.eclipse.org/legal/epl-v10.html" target="_blank">Eclipse Public License</a>.
          </p>
          <p>
              If you are looking for a previous version, check the <a href="archives.php">archives</a>.
          </p>
          <ul>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_fedora25_installer.sh" target="_blank">Fedora 25 (Model 2 or 3) - Experimental</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_raspberry-pi-2-3_installer.deb" target="_blank">Raspbian (Model 2 or 3) - Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_raspberry-pi-2-3-nn_installer.deb" target="_blank">Raspbian (Model 2 or 3, No Net)- Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_intel-edison-nn_installer.sh" target="_blank">Intel Edison (No Net)- Stable</a></li>

              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_raspberry-pi_installer.deb" target="_blank">Raspbian - Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_raspberry-pi-nn_installer.deb" target="_blank">Raspbian (No Net)- Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_raspberry-pi-bplus_installer.deb" target="_blank">Raspbian (Model B+)- Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_raspberry-pi-bplus-nn_installer.deb" target="_blank">Raspbian (Model B+, No Net)- Stable</a></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_beaglebone_debian_installer.deb" target="_blank">BeagleBone - Stable</a></li></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/kura_3.0.0_beaglebone-nn_debian_installer.deb" target="_blank">BeagleBone (No Net) - Stable</a></li></li>
              <li style="color: #4f5c6d"><a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.0.0/user_workspace_archive_3.0.0.zip" target="_blank">Developer's Workspace</a></li>
          </ul>
          <h2 id="3.1.0-M1-downloads">Eclipse Kura&trade; 3.1.0-M1 Downloads</h2>
          <h4>Released on Aug 9th, 2017</h4>
          <p>
              Artifacts for the Kura v3.1.0-M1 Milestone Release are available from the <a onclick="table_select('3.1.0-M1', '', '')">table</a> below by selecting the corresponding version from the dropdown list.
          </p>
      </div>
  </div>
  <h2 id="archives">Archives</h2>
  <section id="kura-table">
      <div class="container">
          <div class="row">
              <div class="col-sm-3 col-md-3 col-lg-3">
                  <label class="table-label">Version</label>
                  <select id="version-select" class="browser-default">
                      <option value="3.1.0-M1">v3.1.0-M1</option>
                      <option value="3.0.0">v3.0.0</option>
                      <option value="2.1.0">v2.1.0</option>
                      <option value="2.0.2">v2.0.2</option>
                      <option value="2.0.1">v2.0.1</option>
                      <option value="2.0.0">v2.0.0</option>
                      <option value="1.4.0">v1.4.0</option>
                      <option value="1.3.0">v1.3.0</option>
                      <option value="1.2.2">v1.2.2</option>
                      <option value="1.2.1">v1.2.1</option>
                      <option value="1.2.0">v1.2.0</option>
                      <option value="1.1.2">v1.1.2</option>
                      <option value="1.1.1">v1.1.1</option>
                      <option value="1.1.0">v1.1.0</option>
                      <option value="1.0.0">v1.0.0</option>
                      <option value="0.7.1">v0.7.1</option>
                      <option value="0.7.0">v0.7.0</option>
                      <option value="all" selected>All</option>
                  </select>
              </div>
              <div class="col-sm-3 col-md-3 col-lg-3">
                  <label class="table-label">Platform</label>
                  <select id="platform-select" class="browser-default">
                      <option value="Raspberry Pi 3 (Fedora)">Raspberry Pi 3 (Fedora)</option>
                      <option value="Raspberry Pi 2-3">Raspberry Pi 2-3</option>
                      <option value="Raspberry Pi B+">Raspberry Pi B+</option>
                      <option value="Raspberry Pi A+">Raspberry Pi A+</option>
                      <option value="Beaglebone Black">Beaglebone Black</option>
                      <option value="Intel Edison">Intel Edison</option>
                      <option value="all" selected>All</option>
                  </select>
              </div>
              <div class="col-sm-3 col-md-3 col-lg-3">
                  <label class="table-label">Web UI</label>
                  <select id="ui-select" class="browser-default">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                      <option value="all" selected>All</option>
                  </select>
              </div>
          </div>
          <div class="row">
              <table id="kura-downloads" class="table table-striped table-bordered" cellspacing="0" width="100%">

              </table>
          </div>
      </div>
  </section>
  <a name="source_code"></a>
  <h2>Source Code</h2>
  <p>
    The source code for Eclipse Kura is available at Github <a href="https://github.com/eclipse/kura" target="_blank">here</a>
  </p>
  <a name="debian_packages"></a>
  <h2>Raspbian/Debian Packages</h2>
  <p>
    Along with release and snapshot versions, Kura also provides "no networking" versions of the Debian install files. Files with "(No Net)" will provide frameworks in which Kura will not assist in configuring
    network interfaces or firewall. Installation instructions for the Raspberry Pi and BeagleBone Black can be found at the below locations:</p>
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

</div>
<script src="./js/table_select.js"></script>
<?php include('includes/footer.php') ?>
