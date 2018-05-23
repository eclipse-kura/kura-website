<?php
/**
 * Copyright (c) 2018 Eurotech and/or its affiliates.
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
  	<p class="text-center"><img src="content/images/kura_logo_400.png" alt="Eclipse Kura Logo" height="100px"></p>
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

  <style>

  </style>
</div>

<div class="jumbotron black" style="background-color: transparent">
	<p>Eclipse Kura&trade; is an extensible open source IoT Edge Framework based on Java/OSGi. Kura offers API access to the hardware interfaces of IoT Gateways (serial ports, GPS, watchdog, GPIOs, I2C, etc.). It features ready-to-use field protocols (including Modbus, OPC-UA, S7), an application container, and a web-based visual data flow programming to acquire data from the field, process it at the edge, and publish it to leading IoT Cloud Platforms through MQTT connectivity.</p>
</div>

<div class="news-list news-list-match-height">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 col-lg-offset-2 col-md-12 news-list-col padding-bottom-50">
        <div class="news-list-icon text-center">
          <i data-feather="activity" stroke-width="1"></i>
        </div>
        <h2 class="text-center">Project News</h2>
        <ul class="news-list-media list-unstyled">
          <li>
            <a href="https://github.com/eclipse/kura/blob/KURA_3.2.0_RELEASE/kura/distrib/RELEASE_NOTES.txt" target="_blank" class="media media-link">
            <h4 class="media-heading">Kura 3.2.0 Release</h4>
            <p class="media-text">Eclipse Kura 3.2.0 is now available for download!</p></a>
          </li>
           <li>
            <a href="https://www.slideshare.net/eclipsekura/building-iot-mashups-for-industry-40-with-eclipse-kura-and-kura-wires" target="_blank" class="media media-link">
            <h4 class="media-heading">IoT Meetup</h4>
            <p class="media-text">Building IoT Mashups for Industry 4.0 with Eclipse Kura and Kura Wires</p></a>
          </li>
          <li>
            <a href="https://iot.eclipse.org/open-iot-challenge/" target="_blank" class="media media-link">
            <h4 class="media-heading">Open IoT Challenge 4.0</h4>
            <p class="media-text">Latest news on the Eclipse Challenge</p></a>
          </li>
        </ul>
      </div>
      <div class="col-lg-10 col-md-12 news-list-col padding-bottom-50">
        <div class="news-list-icon text-center">
          <i data-feather="activity" stroke-width="1"></i>
        </div>
        <h2 class="text-center">Twitter Feed</h2>
        <ul class="news-list-media list-unstyled">
          <li>
          <?php
          	if ($Theme->hasCookieConsent()) {
            echo '<div class="twitter-feed"> <a class="twitter-timeline" data-dnt="true" href="https://twitter.com/search?q=%40eclipsekura%20-RT%20OR%20%23eclipsekapua%20-RT" data-widget-id="786273082092630024">Tweets about @eclipsekura -RT OR #eclipsekapua -RT</a>
            	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>';
			}
			?>
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
      <p>Download Kura for your Raspberry Pi 2 or 3 from here:
      </p>
      <p><a class="btn btn-warning" href="http://www.eclipse.org/downloads/download.php?file=/kura/releases/3.2.0/kura_3.2.0_raspberry-pi-2-3_installer.deb">Download</a></p>
      <p>To install, follow these instructions:
      </p>
      <p><a class="btn btn-info" href="http://eclipse.github.io/kura/intro/raspberry-pi-quick-start.html#eclipse-kuratrade-installation">Install Instructions</a></p>
      <!-- /downloads/packages/eclipse-standard-432/keplersr2 -->
      <hr>
      <p>For other platforms, please refer to the Documentation Page.</p>
      <p><a class="btn btn-warning" href="http://eclipse.github.io/kura/">Documentation</a></p>
    </div>
    <div class="col-md-8 two  gs-item">
      <div class="circle">2</div>
      <h1 class="fw-600">Connect</h1>
      <p>Use <b>Wires</b> to visually connect your sensors and PLCs using a friendly web UI for data capture, processing and publishing.
      </p>
      <p><a class="btn btn-info" href="//eclipse.github.io/kura/wires/kura-wires-intro.html">Learn More</a></p>
    </div>
    <div class="col-md-8 three gs-item">
      <div class="circle">3</div>
      <h1 class="fw-600">Extend</h1>
      <p>Develop new Components and Application, Drag-and-Drop new modules from the Eclipse IoT Marketplace.</p>
      <ul>
        <li>Get Started with the <a href="http://eclipse.github.io/kura/builtin/intro.html">Framework Functionalities</a></li>
        <li>Get Started with <a href="http://eclipse.github.io/kura/dev/kura-setup.html">Java development</a></li>
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
        <a class="step-icon" href="https://github.com/eclipse/kura/blob/develop/CONTRIBUTING.md"><i data-feather="git-pull-request" stroke-width="1"></i></a>
        <p><a href="https://github.com/eclipse/kura/blob/develop/CONTRIBUTING.md" class="btn btn-info">How to Contribute</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="https://github.com/eclipse/kura/labels/help%20wanted"><i data-feather="github" stroke-width="1"></i></a>
        <p><a href="https://github.com/eclipse/kura/labels/help%20wanted" class="btn btn-info">Help Wanted Issues</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="https://iot.eclipse.org/testbeds/"><i data-feather="book-open" stroke-width="1"></i></a>
        <p><a href="https://iot.eclipse.org/testbeds/" class="btn btn-info">Testbeds</a></p>
      </div>
      <div class="col-sm-6 step">
        <a class="step-icon" href="https://accounts.eclipse.org/mailing-list/kura-dev"><i data-feather="activity" stroke-width="1"></i></a>
        <p><a href="https://accounts.eclipse.org/mailing-list/kura-dev" class="btn btn-info">Mailing List</a></p>
      </div>
    </div>
  </div>
</div>
