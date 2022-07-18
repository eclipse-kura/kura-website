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

<div>

  <div class="page-header">
    <h1>Eclipse Kura&trade; Downloads</h1>
  </div>

  <p>
    Latest version is <strong>Eclipse Kura&trade; 5.1.2</strong>, released on July 18th, 2022 - <a href="https://github.com/eclipse/kura/blob/KURA_5.1.2_RELEASE/kura/distrib/RELEASE_NOTES.txt">Release Notes</a>
  </p>

  <section>
      <h3>Installers</h3>
      <p>
          Eclipse Kura installers can be downloaded from the table below. If you need an installation that does not include the web UI, please make a request to the Kura mailing list. The below downloads are distributed
          through the Eclipse Foundation and meet all requirements of the <a href="https://www.eclipse.org/legal/epl-v20.html" target="_blank">Eclipse Public License</a>.
      </p>
      <div class="alert alert-info">
          Note: some old Kura versions do not provide an EPL compatible Web UI. EPL compatibility is reported in the dedicated column in the downloads table.
      </div>

      <div class="alert alert-warning">
          Note: Eclipse Kura versions from 4.0.0 to 4.1.2, Eclipse Kura 5.0.0 use Log4J 2.8.2 that is affected by CVE-2021-44228, CVE-2021-45046 and CVE-2021-45105. 
          Please consider using Eclipse Kura 4.1.3+ and 5.0.1+ that leverage Log4J 2.17.0.
      </div>

      <div>
        <div id="downloads-filters" class="form-inline text-right"></div>

        <div style="max-height: 600px; overflow: scroll">
          <table id="downloads-table" class="table"></table>
        </div>
      </div>
  </section>
  <section>
      <h3>Docker</h3>
      <p>
          Eclipse Kura is available also in a <a href="https://hub.docker.com/r/eclipse/kura/" target="_blank">containerized form</a>.
      </p>
      <div class="alert alert-info">
          To run: <b>docker run -d -p 8443:443 -t eclipse/kura:latest</b>
      </div>
  </section>

  <section>
    <h3>Repositories</h3>
    <p>Eclipse hosts a Nexus for those who want to use Maven to manage their dependencies. The release and snapshot repositories can be found here:</p>
    <ul>
      <li><a href="https://repo.eclipse.org/content/repositories/kura-releases" target="_blank">Release</a></li>
      <li><a href="https://repo.eclipse.org/content/repositories/kura-snapshots/" target="_blank">Snapshots</a></li>
    </ul>
  </section>

  <script src="content/javascript/table.js"></script>

  <script>
    var renderLink = function (url) {
      var link = document.createElement('a')
      link.className = "fa fa-download"
      link.href = url
      link.target = '_blank'
      return link
    }
    var renderBool = function (bool) {
      var icon = document.createElement('i')
      if (bool === 'Yes') {
        icon.className = 'fa fa-check green'
      } else {
        icon.className = 'fa fa-times red'
      }
      return icon
    }
    var columnDescriptors = [
      {
        name: "Download URL",
        renderer: renderLink
      },
      {
        name: "Platform",
        filter: true
      },
      {
        name: "Version",
        filter: true,
        default: '5.1.2'
      },
      {
        name: "Web Ui",
        renderer: renderBool
      },
      {
        name: "Network Admin",
        filter: true,
        renderer: renderBool
      },
      {
        name: "EPL",
        renderer: renderBool
      }
    ]
    new DataTable('content/data/downloads.json',
                  'downloads-table',
                  'downloads-filters',
                  columnDescriptors)
  </script>
</div>
