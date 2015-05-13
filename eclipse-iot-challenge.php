<?php include('includes/header.php') ?>
<div class="container" style="min-height: 800px;">
  <div class="row">
      <img src="images/eclipse_iot_challenge.png" />
  </div>
  <h2>Carracho</h2>
  <p>
    Carracho aims to build an advanced monitoring car system, integrating the huge amount of sensor data from the car ECU
    (read by standard OBD-II interface) with smartphone sensor data (like GPS). A gateway (a Raspberry Pi with Kura OSGi framework)
    can collect the data, wrap them in a MQTT packet and finally send them to a remote MQTT server exploiting the 3G connection
    provided by the smartphone.
    <br><br>
    Read more <a href="http://diyapps.blogspot.com/2015/03/eclipse-open-iot-challenge-final-post.html" target="_blank">here</a>.
  </p>
  <h2>Smart Helmet</h2>
  <p>
    According to a survey, in India, 139,091 persons were killed in road accidents during the year 2012, out of which 23% constituted
    for Two-wheeler related incidents. The objective of the solution is to detect impact of the accident or fall occurred using the
    accelerometer mounted on the helmet.
    <br><br>
    Read more <a href="http://byrebg.blogspot.in/2015/03/smart-helmet-using-eclipse-kura.html" target="_blank">here</a>.
  </p>
  <h2>DIY Home Automation</h2>
  <p>
    A DIY home automation solution based on MQTT-SN, Node-RED, Arduino, Raspberry Pi and nRF24L01+ RF transceivers.
    <br><br>
    Read more <a href="http://openiotchallenge.tumblr.com/post/114361695760/project-wrap-up" target="_blank">here</a>.
  </p>
  <h2>LwM2M over MQTT</h2>
  <p>
    The current stack for LWM2M relies on CoAP as the protocol. Along with CoAP, MQTT is another standard which is
    being very widely used in M2M scenarios. Our solution involves development of an LWM2M server prototype, as well
    as, a client prototype, which make use of MQTT as the underlying M2M protocol. Thus LWM2M can be used for both
    CoAP, as well as, MQTT.
    <br><br>
    Read more <a href="http://openiotchallenge.tumblr.com/post/112230693320/lwm2m-over-mqtt" target="_blank">here</a>.
  </p>
  <h2>Hot Desking Dilemma</h2>
  <p>
    More and more companies offer their employees the possibility to work remote from home instead of working in
    the office. Hot desking multiple employees share one desk over a week is a common practice for such
    companies in order to reduce costs. People who know this setting also know the downsides of this model: it is
    just pretty hard to get a desk when you need one.
    <br><br>
    Read more <a href="http://icanseedeadcats.com/2015/03/24/eclipse-iot-challenge-mqtt-communication-for-hot-desks-featuring-kura-mosquitto-and-paho-and-project-wrapup" target="_blank">here</a>.
  </p>
  <h2>Monitoring Industrial Automation</h2>
  <p>
    This project realizes a device that monitors a set of defined parameters (mapped to MODBUS registers on a
    device) and based on defined rule take action (store in internal memory, show as value or graph on local
    LCD or send using MQTT protocol to defined server). User is able to freely define mapping to MODBUS registers,
    poling interval, monitoring rules and actions).
    <br><br>
    Read more <a href="https://tobiddev.wordpress.com/2015/03/24/summary-of-open-iot-challenge-monitoring-industrial-automation-equipment/" target="_blank">here</a>.
  </p>
</div>
<?php include('includes/footer.php') ?>
