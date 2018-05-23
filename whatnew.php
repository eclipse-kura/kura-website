<?php include('includes/header.php') ?>
<div class="container" style="min-height: 800px;">
    <div class="row">
        <div class="col-md-12">
            <h2>Eclipse Kura&trade; 3.1.0</h2>
            <h4>Released on Oct 12th, 2017 - <a href="https://github.com/eclipse/kura/blob/KURA_3.1.0_RELEASE/kura/distrib/RELEASE_NOTES.txt">Release Notes</a></h4>
            <p><b>Added Features:</b></p>
            <ul>
                <li style="color: #4f5c6d; list-style-type: disc">H2 Databases: This release will see the switch from HSQLDB to H2. This migration will allow for a number of performance improvements as well the ability to maintain multiple database instances.</li>
                <li style="color: #4f5c6d; list-style-type: disc">REST API for Kura Drivers and Assets: To allow more flexibility, REST endpoints will be added to Kura for interacting with the Kura Drivers and Assets instances.</li>
                <li style="color: #4f5c6d; list-style-type: disc">Embedded Artemis Broker: The addition of the Apache Artemis Broker will extend Kura's messaging functionality.</li>
                <li style="color: #4f5c6d; list-style-type: disc">BLE via TinyB: To improve the reliability of BLE, Kura will switch to the TinyB library to support BLE via Bluez.</li>
                <li style="color: #4f5c6d; list-style-type: disc">Kura Wires Functional Logic: To extend the usability of Kura Wires, this release will include Functional Logic Wire Components. This will allow the use of plain JavaScript inside a Wire component.</li>
                <li style="color: #4f5c6d; list-style-type: disc">Kura Web UI Update: A new UI element will be added for managing and editing Drivers and Assets.</li>
                <li style="color: #4f5c6d; list-style-type: disc">S7 Industrial Protocol: A new driver will be added to the Eclipse Marketplace to support S7.</li>
                <li style="color: #4f5c6d; list-style-type: disc">BLE/SensorTag: A new driver will be added to the Eclipse Marketplace to support BLE on the TI SensorTag.</li>
                <li style="color: #4f5c6d; list-style-type: disc">MQTT publish rate improvement: A limit parameter will be added to the MQTT transport service to allow throttling of MQTT messages to prevent unnecessary congestion on the MQTT broker.</li>
                <li style="color: #4f5c6d; list-style-type: disc">Connection Monitor: A connection monitor will be introduced to the DataService to further improve connectivity reliability.</li>
                <li style="color: #4f5c6d; list-style-type: disc">RPMs: This release will provide RPM packaging for Fedora based distributions.</li>
            </ul>
            <p>&nbsp;</p>
            <p><b>Compatibility:</b></p>
            <ul>
                <li style="color: #4f5c6d">Eclipse Kura v3.1.0 does not introduce API breakage with previous releases.</li>
                <li style="color: #4f5c6d">This release does introduce a new set of APIs for TinyB for interacting with BLE devices. Support for previous BLE APIs will still be present and functional.</li>
                <li style="color: #4f5c6d">In Kura v3.1.0 it is possible to instantiate a Driver instance from the Drivers and Assets section or from Wire Composer only if the following requirements are met:</li>
                <li style="color: #4f5c6d">the Driver implementation class must implement the following interfaces:</li>
                <li style="color: #4f5c6d">Driver</li>
                <li style="color: #4f5c6d">ConfigurableComponent or SelfConfigurableComponent</li>
                <li style="color: #4f5c6d">the Driver must provide a component definition xml file that advertises that</li>
            </ul>
            <p style="color: #4f5c6d">the interfaces mentioned above are provided and that sets "required" as configuration policy.</p>
            <p style="color: #4f5c6d">It is advisable to update the deployment packages of non compliant drivers if needed.</p>
            <p style="color: #4f5c6d">It is still possible to instantiate non compliant drivers using the "+" button under "Services".</p>
            <p>&nbsp;</p>
            <p>Target Environments:</p>
            <p>Kura is released as pre-compiled binary installers for the following platforms:</p>
            <ul>
                <li style="text-indent: -18.0pt;">Raspberry Pi based on Raspbian: Raspberry Pi, Raspberry Pi B+, Raspberry Pi 2/3</li>
                <li style="text-indent: -18.0pt;">BeagleBone Black based on Debian</li>
                <li style="text-indent: -18.0pt;">Fedora on ARM (Experimental)</li>
                <li style="text-indent: -18.0pt;">Intel Edison (Experimental)</li>
                <li style="text-indent: -18.0pt;">APU Debian (Experimental)</li>
                <li style="text-indent: -18.0pt;">ARM_64 Debian (Experimental)</li>
            </ul>
        </div>
    </div>
</div>
<?php include('includes/footer.php') ?>
