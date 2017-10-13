var table_select = (function() {
    var dTable;

    jQuery(document).ready(function() {
        dTable = jQuery('#kura-downloads').DataTable({
            "sDom": '<t><"row"<"col-sm-2"p><"col-sm-6"i>>',
            "ajax": 'data/downloads.txt',
            "lengthChange": true,
            "pageLength": 10,
            "pagingType": "simple",
            "language": {
                "paginate": {
                    'next': '<i class="material-icons">chevron_right</i>',
                    'previous': '<i class="material-icons">chevron_left</i>'
                }
            },
            "columns": [{
                "name": "url",
                "title": "URL",
                "render": function(data, type, full, meta) {
                    return '<a style="margin: auto; display: block;"href="' + data + '" class="btn-floating blue"><i class="material-icons">file_download</i></a>';
                }
            }, {
                "name": "platform", "title": "Platform"
            }, {
                "name": "version", "title": "Version"
            }, {
                "name": "ui", "title": "Web UI"
            }, {
                "name": "netAdmin", "title": "Network Admin"
            }, {
                "name": "epl", "title": "EPL"
            },
            ]
        });
        dTable.columns.adjust();
        jQuery("select[name='kura-downloads_length']").addClass('browser-default');
        jQuery('select').material_select();
    } );

    var select_version = function (versionTerm, platformTerm, uiTerm, netTerm) {
      var name;
      switch (jQuery(this).attr('id')) {
          case "version-select":
              name = "version";
              break;
          case "platform-select":
              name = "platform";
              break;
          case "ui-select":
              name = "ui";
              break;
          case "net-select":
              name = "netAdmin";
              break;
          default:
              name = "";
      }
      dTable
          .column('version:name').search(versionTerm)
          .column('platform:name').search(platformTerm)
          .column('ui:name').search(uiTerm)
          .column('netAdmin:name').search(netTerm)
          .draw();
    }

    jQuery('#version-select, #platform-select, #ui-select, #net-select').change(function() {
        var versionTerm = jQuery("#version-select").val() != 'all' ? jQuery("#version-select").val() : '';
        var platformTerm = jQuery("#platform-select").val() != 'all' ? jQuery("#platform-select").val() : '';
        var uiTerm = jQuery("#ui-select").val() != 'all' ? jQuery("#ui-select").val() : '';
        var netTerm = jQuery("#net-select").val() != 'all' ? jQuery("#net-select").val() : '';
        select_version(versionTerm, platformTerm, uiTerm, netTerm)
    })

    return function (versionTerm, platformTerm, uiTerm, netTerm) {
      select_version(versionTerm, platformTerm, uiTerm, netTerm)
      jQuery('html, body').animate({
        scrollTop: jQuery("#archives").offset().top - jQuery(".navbar").height()
    }, 250);
    }
}());
