<?php include('includes/header.php') ?>
<div class="container" style="min-height: 800px;">
  <a name="source_code"></a>
  <h2>Source Code</h2>
  <p>
    The source code for Eclipse Kura is available at Github <a href="https://github.com/eclipse/kura">here</a>
  </p>
  <a name="debian_packages"></a>
  <h2>Debian Packages</h2>
  <p>
    A Debian package for the Raspbian operating system is avaialable from the Eclipse build system.<br>
    <ul>
      <li style="color: #4f5c6d">The latest stable version can be found <a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/0.7.0/kura-raspberry-pi_0.7.0.deb">here</a></li>
      <li style="color: #4f5c6d">The latest development version can be found <a href="http://www.eclipse.org/downloads/download.php?file=/kura/SNAPSHOT/kura-raspberry-pi_0.2.0-SNAPSHOT.deb">here</a></li>
    </ul>
    Install the package by issuing the command:<br>
    <PRE>dpkg -i &lt;deb_package_name&gt;.deb</PRE>
  </p>
  <h2>Other</h2>
  <p>
    Other artifacts of the Eclipse build system include:
    <ul>
        <li style="color: #4f5c6d">A Kura workspace. This is all the necessary jar files and APIs needed to build an application with Kura. Simply import the archive into an Eclipse workspace and start coding.
          Download the archive from <a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/0.7.0/user_workspace_archive_0.7.0.zip">here</a></li>
        <li style="color: #4f5c6d">The Raspberry jars. If you want to manually instally Kura on your Raspberry, grab the archive of Kura
           <a href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/0.7.0/kura-raspberry-pi-jars_0.7.0.zip">here</a></li>
    </ul>
  </p>
</div>
<?php include('includes/footer.php') ?>
