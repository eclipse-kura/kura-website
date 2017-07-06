<?php include('includes/header.php') ?>
<div id="intro">
  <div class="container">
    <div class="row">
          <div class="col-md-12" style="position: relative">
              <div class="row">
                  <div id="owl-example" class="owl-carousel col-md-8 col-md-offset-1">
                    <div class="item">
                      <img style="display: block; margin: auto;" src="images/kura_v3.1.png" alt="Kura v3.0">
                      <p classs="lead">
                          Kura 3.0 is out! The key new feature of this release is Kura Wires, 
                          a modular and visual data flow programming tool to define data collection
                          and processing pipelines by simply selecting components from a palette and 
                          wiring them together. Kura Wires support Industry 4.0 applications with drivers for
                          OPC-UA and other industrial automation protocols available from the <a href="https://marketplace.eclipse.org/category/categories/eclipse-kura" target="_blank">Eclipse Kura Marketplace</a>.
                          Read the full story <a href="https://www.eclipse.org/org/press-release/20170426_kura3.php" target="_blank">here</a>.
                      </p>
                    </div>
                    <div class="item">
                      <img style="display: block; margin: auto;" class="img-responsive" src="images/kura_logo_400.png" >
                      <p class="lead">
                          Kura is a Java/OSGi-based framework for IoT gateways. Kura APIs offer access to the underlying hardware (serial ports, GPS, watchdog, GPIOs, I2C, etc.),
                          management of network configurations, communication with M2M/IoT Integration Platforms, and gateway management.
                      </p>
                    </div>
                    <div class="item">
                      <img style="display: block; margin: auto;" class="img-responsive"  src="images/kura_camel.png" alt="Apache Software">
                      <p class="lead">
                        Apache Camel announces support for Kura in the upcoming 2.15 release! Read more information <a href="https://dentrassi.de/2016/11/24/providing-telemetry-data-with-opc-ua-on-eclipse-kura/" target="_blank">here</a>.
                        Watch a video introduction and tutorial <a href="https://www.youtube.com/watch?v=mli5c-oTN1U" target="_blank">here</a>.
                      </p>
                    </div>
                </div>
                  <div class="col-md-3">
                    <div class="icon-list"><a href="https://eclipse.github.io/kura/dev/kura-setup.html" target="_blank"><span class="glyphicon glyphicon-record"></span><p class="icon-list-p">Get Started</p></a><br/></div>
                    <div class="icon-list"><a href="downloads.php"><span class="glyphicon glyphicon-download"></span><p class="icon-list-p">Downloads</p></a><br/></div>
                    <div class="icon-list"><a href="community.php"><span class="glyphicon glyphicon-thumbs-up"></span><p class="icon-list-p">Community</p></a><br/></div>
                    <div class="icon-list"><a href="http://wiki.eclipse.org/Kura" target="_blank"><span class="glyphicon glyphicon-globe"></span><p class="icon-list-p">Wiki</p></a><br/></div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
<div id="features">
  <div class="container features">
    <div class="row">
          <div class="col-md-12" style="position: relative;">
			  <p class="feature-header">Solution Showcases</p>
              <div class="row" style="padding-right:10px">
				  <a style="color:white;" href="http://www.eurotech.com/en/products/software+services/everyware+cloud+m2m+platform" target="_blank">
				  <div class="col-md-2">
					<img class="img-responsive featuresimg" style="height:80%;padding-top:15px" src="images/ETH-Cloud.png">
				  </div>
                  <div class="col-md-2">
					<p class="feature-body">
					Cloud-based
					Kura device management</p>
                  </div>
				  </a>

				  <a style="color:white;" href="https://www.slideshare.net/eclipsekura/eclipse-kura-shoot-api" target="_blank">
				  <div class="col-md-2 feature-box">
					<img class="img-responsive featuresimg" style="height:50%;padding-top:10px" src="images/rpi256.png">
				  </div>
                  <div class="col-md-2"><br />
					<p class="feature-body">Shoot-a-Pi Tutorial</p>
                  </div>
				  </a>

                  <a style="color:white;" href="http://iot.eclipse.org/java/tutorial/" target="_blank">
				  <div class="col-md-2 feature-box">
     				<img class="img-responsive featuresimg" style="height:120px!important;width:125px!important; " src="images/ETH-Greenhouse_ws.png">
				  </div>
                  <div class="col-md-2"><br />
					<p class="feature-body">Greenhouse Tutorial</p>
                  </div>
				  </a>

              </div>
		  </div>
    </div>

  </div>
  <br />
  <div class="container features">
	<p class="feature-header">IoT Gateways</p>
    <div class="row" style="margin: 25px 40px 0px 0px; padding: 25px 10px 0px 0px; border-top: 1px solid white;">
      <div class="col-md-4 feature-box">
        <img class="img-responsive" src="images/rpi256.png">
      </div>
      <div class="col-md-7">
        <p class="feature-header">A Raspberry Pi Gateway</p>
        <p class="feature-body">Let Kura turn your Raspberry Pi into an IoT gateway. The Kura source
          code is complete with example projects to get you up and running quickly. Kura is compatible
          with the latest RPi B+ model.
          <a style="color: navy" href="downloads.php">Get the latest downloads</a>.</p>
      </div>
    </div>

    <div class="row" style="margin: 25px 40px 0px 0px; padding: 25px 10px 0px 0px; border-top: 1px solid white;">
      <div class="col-md-4 feature-box">
        <img class="img-responsive" src="images/BBB_Alt_View_Small.png">
      </div>
      <div class="col-md-7">
        <p class="feature-header">A BeagleBone Black Gateway</p>
        <p class="feature-body">Kura is compatible with the BeagleBone Black Rev C.
          <a style="color: navy" href="downloads.php">Get the latest Debian package</a>.</p>
      </div>

    </div>


    <div class="row" style="margin: 25px 40px 0px 0px; padding: 25px 10px 0px 0px; border-top: 1px solid white;">
      <div class="col-md-4 feature-box">
        <img class="img-responsive" src="images/eurotech.png">
      </div>
      <div class="col-md-6">
        <p class="feature-header">Industral IoT Gateways</p>
        <p class="feature-body">Take a look at vendors who are using Kura in their
          <a style="color: navy" href="vendors.php">commercial gateways</a>.</p>
      </div>
      </div>
    </div>


    </div>
  </div>
</div>

<div id="sidebar">
  <div class="container">
    <h2>Latest News</h2>
    <div class="row">
      <div class="col-md-3 col-md-offset1 box">

        <p class="article-link">
          <a href="https://tobiddev.wordpress.com/" target="_blank">Industrial Monitoring Project using Kura</a><br /><br />
        </p>
      </div>
      <div class="col-md-3 col-md-offset3 box">
        <p class="article-link">
          <a href="http://diyapps.blogspot.com/" target="_blank">Vehicle Monitoring Project using Kura</a>
        </p>
      </div>
        <div class="col-md-4 col-md-offset4 box">

        <p class="article-link">
          <a href="http://openiotchallenge.tumblr.com/" target="_blank">Eclipse IoT Challenge Tumblr</a>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 col-md-offset1 box">

        <p class="article-link">
          <a href="http://www.youtube.com/watch?v=PXlDJMK7yqg" target="_blank">Live Demo at EclipseCon 2014</a><br /><br />
        </p>
      </div>
      <div class="col-md-3 col-md-offset3 box">
        <p class="article-link">
          <a href="http://www.parleys.com/play/543f8e75e4b06e1184ae4115" target="_blank">Video about end-to-end IoT solutions</a>
        </p>
      </div>
        <div class="col-md-4 col-md-offset4 box">

        <p class="article-link">
          <a href="http://www.bitreactive.com/kura-config/" target="_blank">BitReactive: Configure IoT Gateway Applications with Kura</a>
        </p>
      </div>
    </div>
  </div>

</div>


<div id="articles">
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset4 article-box">
        <p class="lead">
          <span class="quote lquote">&ldquo;</span>
          Deploying and configuring one device to act as a node in the Internet of Things is relatively easy. Doing the same for hundreds or thousands of devices is not so easy though. This is where the new Eclipse project Kura comes in.
          <span class="quote rquote">&rdquo;</span>
        </p>
        <p class="article-link">
          <a href="http://www.eclipse.org/community/eclipse_newsletter/2014/february/article3.php" target="_blank">Kura - A Gateway for the Internet of Things - <br/>D.J. Walker-Morgan</a>
        </p>
      </div>
      <div class="col-md-4 col-md-offset4 article-box">
        <p class="lead">
          <span class="quote lquote">&ldquo;</span>
          I just got the full Eclipse Kura demo and I was blown away. Very cool stuff. eclipse.org/proposals/<br/>technology.kura/ … #java #osgi #iot
          <span class="quote rquote">&rdquo;</span>
        </p>
        <p class="article-link">
          <a href="https://twitter.com/mmilinkov/status/382660433657597952" target="_blank">Tweet from Mike Milinkovich</a>
        </p>
      </div>
      <div class="col-md-4 col-md-offset4 article-box">
        <p class="lead">
          <span class="quote lquote">&ldquo;</span>
          Kura in action on YouTube.
          <span class="quote rquote">&rdquo;</span>
        </p>
        <p class="article-link">
          <a href="https://www.youtube.com/watch?v=PXlDJMK7yqg" target="_blank">Kura in action on YouTube.</a>
        </p>
      </div>
    </div>
  </div>
</div>
<?php include('includes/footer.php') ?>
