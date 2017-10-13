<?php include('includes/header.php') ?>
<div class="container" style="min-height: 800px;">
  <div class="row">
      <div class="col-md-12">
          <h2 id="archives">Archives</h2>
          <section id="kura-table">
              <div class="container">
                  <div class="row">
                      <div class="col-sm-3 col-md-3 col-lg-3">
                          <label class="table-label">Version</label>
                          <select id="version-select" class="browser-default">
                              <option value="3.1.0">v3.1.0</option>
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
                          <label class="table-label">Net Admin</label>
                          <select id="net-select" class="browser-default">
                              <option value="Yes">Yes</option>
                              <option value="No">No</option>
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
      </div>
  </div>
</div>
<script src="./js/table_select.js"></script>
<?php include('includes/footer.php') ?>
