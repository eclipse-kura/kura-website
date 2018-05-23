var table_select = (function() {
    /****************************
    // Download info for table
    ***************************/
    // URL Platform Version UI NetAdmin EPL
    var dataSet = [
        // v1.4.0
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/kura_1.4.0_raspberry-pi-2_installer.deb", "Raspberry Pi 2", "1.4.0", "No", "Yes", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/kura_1.4.0_raspberry-pi-2-nn_installer.deb", "Raspberry Pi 2", "1.4.0", "No", "No", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/kura_1.4.0_intel-edison-nn_installer.sh", "Intel Edison", "1.4.0", "No", "No", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/kura_1.4.0_raspberry-pi_installer.deb", "Raspberry Pi A+", "1.4.0", "No", "Yes", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/kura_1.4.0_raspberry-pi-nn_installer.deb", "Raspberry Pi A+", "1.4.0", "No", "No", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/kura_1.4.0_raspberry-pi-bplus_installer.deb", "Raspberry Pi B+", "1.4.0", "No", "Yes", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/kura_1.4.0_raspberry-pi-bplus-nn_installer.deb", "Raspberry Pi B+", "1.4.0", "No", "No", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/kura_1.4.0_beaglebone_debian_installer.deb", "Beaglebone Black", "1.4.0", "No", "Yes", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/kura_1.4.0_beaglebone-nn_debian_installer.deb", "Beaglebone Black", "1.4.0", "No", "No", "Yes"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.4.0/kura_1.4.0_raspberry-pi-2_installer.deb", "Raspberry Pi 2", "1.4.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.4.0/kura_1.4.0_raspberry-pi-2-nn_installer.deb", "Raspberry Pi 2", "1.4.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/intel/edison/release/1.4.0/kura_1.4.0_intel-edison_installer.sh", "Intel Edison", "1.4.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/intel/edison/release/1.4.0/kura_1.4.0_intel-edison-nn_installer.sh", "Intel Edison", "1.4.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.4.0/kura_1.4.0_raspberry-pi_installer.deb", "Raspberry Pi A+", "1.4.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.4.0/kura_1.4.0_raspberry-pi-nn_installer.deb", "Raspberry Pi A+", "1.4.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.4.0/kura_1.4.0_raspberry-pi-bplus_installer.deb", "Raspberry Pi B+", "1.4.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.4.0/kura_1.4.0_raspberry-pi-bplus-nn_installer.deb", "Raspberry Pi B+", "1.4.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/debian/release/1.4.0/kura_1.4.0_beaglebone_debian_installer.deb", "Beaglebone Black", "1.4.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/debian/release/1.4.0/kura_1.4.0_beaglebone-nn_debian_installer.deb", "Beaglebone Black", "1.4.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/user_workspace/release/1.4.0/user_workspace_archive_1.4.0.zip", "User Workspace", "1.4.0", "Yes", "No", "No"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.4.0/user_workspace_archive_1.4.0.zip", "User Workspace", "1.4.0", "No", "No", "Yes"],

        // v1.3.0
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/kura_1.3.0_raspberry-pi-2_installer.deb", "Raspberry Pi 2", "1.3.0", "No", "Yes", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/kura_1.3.0_raspberry-pi-2-nn_installer.deb", "Raspberry Pi 2", "1.3.0", "No", "No", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/kura_1.3.0_intel-edison-nn_installer.sh", "Intel Edison", "1.3.0", "No", "No", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/kura_1.3.0_raspberry-pi_installer.deb", "Raspberry Pi A+", "1.3.0", "No", "Yes", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/kura_1.3.0_raspberry-pi-nn_installer.deb", "Raspberry Pi A+", "1.3.0", "No", "No", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/kura_1.3.0_raspberry-pi-bplus_installer.deb", "Raspberry Pi B+", "1.3.0", "No", "Yes", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/kura_1.3.0_raspberry-pi-bplus-nn_installer.deb", "Raspberry Pi B+", "1.3.0", "No", "No", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/kura_1.3.0_beaglebone_debian_installer.deb", "Beaglebone Black", "1.3.0", "No", "Yes", "Yes"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/kura_1.3.0_beaglebone-nn_debian_installer.deb", "Beaglebone Black", "1.3.0", "No", "No", "Yes"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.3.0/kura_1.3.0_raspberry-pi-2_installer.deb", "Raspberry Pi 2", "1.3.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.3.0/kura_1.3.0_raspberry-pi-2-nn_installer.deb", "Raspberry Pi 2", "1.3.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/intel/edison/release/1.3.0/kura_1.3.0_intel-edison_installer.sh", "Intel Edison", "1.3.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/intel/edison/release/1.3.0/kura_1.3.0_intel-edison-nn_installer.sh", "Intel Edison", "1.3.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.3.0/kura_1.3.0_raspberry-pi_installer.deb", "Raspberry Pi A+", "1.3.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.3.0/kura_1.3.0_raspberry-pi-nn_installer.deb", "Raspberry Pi A+", "1.3.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.3.0/kura_1.3.0_raspberry-pi-bplus_installer.deb", "Raspberry Pi B+", "1.3.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/raspbian/release/1.3.0/kura_1.3.0_raspberry-pi-bplus-nn_installer.deb", "Raspberry Pi B+", "1.3.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/debian/release/1.3.0/kura_1.3.0_beaglebone_debian_installer.deb", "Beaglebone Black", "1.3.0", "Yes", "Yes", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/debian/release/1.3.0/kura_1.3.0_beaglebone-nn_debian_installer.deb", "Beaglebone Black", "1.3.0", "Yes", "No", "No"],
        [ "https://s3.amazonaws.com/kura_downloads/user_workspace/release/1.3.0/user_workspace_archive_1.3.0.zip", "User Workspace", "1.3.0", "Yes", "No", "No"],
        [ "http://www.eclipse.org/downloads/download.php?file=/kura/releases/1.3.0/user_workspace_archive_1.3.0.zip", "User Workspace", "1.3.0", "No", "No", "Yes"]
    ];
    var dTable;

    $(document).ready(function() {
        dTable = $('#kura-downloads').DataTable({
            "dom": "tilpfr",
            "data": dataSet,
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
                    return '<a href="' + data + '" class="btn-floating blue"><i class="material-icons">file_download</i></a>';
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
        $("select[name='kura-downloads_length']").addClass('browser-default');
        $('select').material_select();
    } );

    $('#version-select, #platform-select').change(function() {
        var name;
        var sTerm = $(this).val() != 'all' ? $(this).val() : '';
        switch ($(this).attr('id')) {
            case "version-select":
                name = "version";
                break;
            case "platform-select":
                name = "platform";
                break;
            case "ui-select":
                name = "ui";
                break;
            default:
                name = "";
        }
        dTable.column(name + ":name").search(sTerm, false, false).draw();
    })

}());
