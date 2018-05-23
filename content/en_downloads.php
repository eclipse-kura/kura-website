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
    Latest version is <strong>Eclipse Kura&trade; 3.2.0</strong>, released on April 17th, 2018 - <a href="https://github.com/eclipse/kura/blob/KURA_3.2.0_RELEASE/kura/distrib/RELEASE_NOTES.txt">Release Notes</a>
  </p>

  <section>
      <h3>Installers</h3>
      <p>
          Eclipse Kura installers can be downloaded from the table below. If you need an installation that does not include the web UI, please make a request to the Kura mailing list. The below downloads are distributed
          through the Eclipse Foundation and meet all requirements of the <a href="https://www.eclipse.org/legal/epl-v10.html" target="_blank">Eclipse Public License</a>.
      </p>
      <div class="alert alert-info">
          Note: some old Kura versions do not provide an EPL compatible Web UI. EPL compatibility is reported in the dedicated column in the downloads table.
      </div>
  </section>

  <section>
    <div id="downloads-filters" class="form-inline text-right"></div>

    <div style="max-height: 600px; overflow: scroll">
      <table id="downloads-table" class="table"></table>
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

  <script defer>
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
        default: '3.2.0'
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
