<?php
/**
 * Copyright (c) 2018, 2023 Eurotech and/or its affiliates.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * SPDX-License-Identifier: EPL-2.0
 */
?>

<script>
setTimeout(function () {
  var cookieButtons = document.querySelectorAll('.cc-compliance .cc-btn')
  for (var i=0; i<cookieButtons.length; i++) {
    cookieButtons[i].addEventListener("click", function () {
      location.reload()
    });
  }
}, 1000)
</script>


<div class="block-box block-box-classic">

<div class="jumbotron black" style="background-color: transparent">
  <p class="text-center"><img src="content/images/kura_logo_400.png" alt="Eclipse Kura Logo" height="100"></p>
  <h3 class="text-center">The extensible open source Java/OSGi IoT Edge Framework</h3>
</div>

<div id="main-carousel" class="carousel slide" data-ride="carousel">

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item image-item active">
      <img src="content/images/wires_example.png" alt="Kura Wires composer page">
    </div>
    <div class="item image-item">
      <img src="content/images/kura_status.png" alt="Kura status page">
    </div>
    <div class="item">
      <img src="content/images/kura_network.png" alt="Kura network page">
    </div>
    <div class="item">
      <img src="content/images/kura_firewall.png" alt="Kura firewall page">
    </div>
    <div class="item">
      <img src="content/images/kura_position.png" alt="Kura position page">
    </div>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#main-carousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#main-carousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>

</div>

<div class="jumbotron black" style="background-color: transparent">
	<p>Eclipse Kura&trade; is an extensible open source IoT Edge Framework based on Java/OSGi. Kura offers API access to the hardware interfaces of IoT Gateways (serial ports, GPS, watchdog, GPIOs, I2C, etc.). It features ready-to-use field protocols (including Modbus, OPC-UA, S7), an application container, and a web-based visual data flow programming to acquire data from the field, process it at the edge, and publish it to leading IoT Cloud Platforms through MQTT connectivity.</p>
</div>

<div class="news-list news-list-match-height">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 col-lg-offset-2 col-md-12 news-list-col padding-bottom-50">
        <div class="news-list-icon text-center">
          <i data-feather="activity"></i>
        </div>
        <h2 class="text-center">Project News</h2>
        <ul class="news-list-media list-unstyled">
          <li>
            <a href="https://github.com/eclipse/kura/tree/KURA_5.4.0_RELEASE/kura/distrib/RELEASE_NOTES.txt" target="_blank" class="media media-link">
            <h4 class="media-heading">Kura 5.4.0 Release</h4>
            <p class="media-text">Eclipse Kura 5.4.0 is now available for download!</p></a>
          </li>
          <li>
            <a href="https://github.com/eclipse/kura/blob/KURA_5.3.1_RELEASE/kura/distrib/RELEASE_NOTES.txt" target="_blank" class="media media-link">
            <h4 class="media-heading">Kura 5.3.1 Release</h4>
            <p class="media-text">Eclipse Kura 5.3.1 is now available for download!</p></a>
          </li>
          <li>
            <a href="https://github.com/eclipse/kura/blob/KURA_5.2.2_RELEASE/kura/distrib/RELEASE_NOTES.txt" target="_blank" class="media media-link">
            <h4 class="media-heading">Kura 5.2.2 Release</h4>
            <p class="media-text">Eclipse Kura 5.2.2 is now available for download!</p></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="timeline">
  <div class="row">
    <div class="col-md-8 one gs-item">
      <div class="circle">1</div>
      <h1 class="fw-600">Install</h1>
      <p>Download Kura for your Raspberry Pi 2/3/4 Raspberry Pi OS (32 bit) from here:
      </p>
      <p><a class="btn btn-warning" href="https://www.eclipse.org/downloads/download.php?file=/kura/releases/5.4.0/kura_5.4.0_raspberry-pi-armhf_installer.deb">Download</a></p>
      <p>To install, follow these instructions:
      </p>
      <p><a class="btn btn-info" href="https://eclipse.github.io/kura/docs-release-5.4/getting-started/raspberry-pi-raspberryos-quick-start/">Install Instructions</a></p>
      <hr>
      <p><b>Docker</b> run: <b>docker run -d -p 8443:443 -t eclipse/kura</b></p>
      <p><a class="btn btn-warning" href="https://github.com/eclipse/kura/tree/develop/kura/container">Documentation</a></p>
      <hr>
      <p>For other platforms, please refer to the Documentation Page.</p>
      <p><a class="btn btn-warning" href="https://eclipse.github.io/kura/">Documentation</a></p>
    </div>
    <div class="col-md-8 two  gs-item">
      <div class="circle">2</div>
      <h1 class="fw-600">Connect</h1>
      <p>Use <b>Wires</b> to visually connect your sensors and PLCs using a friendly web UI for data capture, processing and publishing.
      </p>
      <p><a class="btn btn-info" href="https://eclipse.github.io/kura/docs-release-5.3/kura-wires/introduction/">Learn More</a></p>
    </div>
    <div class="col-md-8 three gs-item">
      <div class="circle">3</div>
      <h1 class="fw-600">Extend</h1>
      <p>Develop new Components and Application, Drag-and-Drop new modules from the Eclipse IoT Marketplace.</p>
      <ul>
        <li>Get Started with the <a href="https://eclipse.github.io/kura/docs-release-5.4/">Framework Functionalities</a></li>
        <li>Get Started with <a href="https://eclipse.github.io/kura/docs-release-5.4/java-application-development/development-environment-setup/">Java development</a></li>
        <li>Access the <a href="//marketplace.eclipse.org/taxonomy/term/4397%2C4396/title">Marketplace</a></li>
      </ul>
    </div>
  </div>
</div>

<hr>

<div class="step-by-step">
  <div class="container">
    <div class="row intro">
      <div class="col-xs-24">
        <h1 style="font-size: 2em;" class="fw-600">Participate &amp; Contribute</h1>
        <p>We would love to hear from you!<br/>
        There are many ways to join the Kura Community:</p>
      </div>
    </div>
    <div class="row step-by-step-timeline">
      <div class="col-sm-6 step">
        <a class="step-icon" href="https://github.com/eclipse/kura/blob/develop/CONTRIBUTING.md"><i data-feather="git-pull-request"></i></a>
        <p><a href="https://github.com/eclipse/kura/blob/develop/CONTRIBUTING.md" class="btn btn-info">How to Contribute</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="https://github.com/eclipse/kura/labels/help%20wanted"><i data-feather="github"></i></a>
        <p><a href="https://github.com/eclipse/kura/labels/help%20wanted" class="btn btn-info">Help Wanted Issues</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="https://github.com/eclipse/kura/discussions"><i data-feather="message-circle"></i></a>
        <p><a href="https://github.com/eclipse/kura/discussions" class="btn btn-info">Discussions</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="https://iot.eclipse.org/testbeds/"><i data-feather="book-open"></i></a>
        <p><a href="https://iot.eclipse.org/testbeds/" class="btn btn-info">Testbeds</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="https://accounts.eclipse.org/mailing-list/kura-dev"><i data-feather="activity"></i></a>
        <p><a href="https://accounts.eclipse.org/mailing-list/kura-dev" class="btn btn-info">Mailing List</a></p>
      </div>
    </div>
  </div>
</div>

</div>
