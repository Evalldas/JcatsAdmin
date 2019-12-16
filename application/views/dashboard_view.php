<div class="task-menu row">
    <div class="col-lg-3 task-menu-group" style="min-width: 400px;">
        <label for="tableProperties">Table actions</label><br>
        <div class="btn-group" role="group">
            <button class="btn btn-primary" id="selectAllRowsButton">Select All</button>
            <button class="btn btn-primary" id="deselectAllRowsButton">Deselect All</button>
            <button action="<?=base_url()?>ClientTasker/statusUpdate" class="btn btn-primary"
                id="updateStatusButton">Update Status</button>
        </div>
    </div>
    <div class="col-lg-2 task-menu-group">
        <label for="quickCommands">Quick commands</label><br>
        <div class="btn-group" role="group">
            <button class="btn btn-primary btnAction" id="rebootButton"
                action="<?=base_url()?>ClientTasker/reboot">Reboot</button>
        </div>
    </div>
    <div class="col-lg-4 task-menu-group" style="min-width: 550px;">
        <label for="jcatsProperties">Jcats actions</label><br>
        <div class="btn-group" role="group">
            <select id="selectServer" name="serverIp" class="btn btn-secondary">
                <?php foreach($servers as $server) {?>
                <option value="<?=$server['ip'];?>"><?=$server['name'];?></option>
                <?php }?>
            </select>
            <button action="<?=base_url()?>ClientTasker/installJcats" class="btn btn-primary btnAction" name="task"
                value="installJcats">Install Jcats</button>
            <button action="<?=base_url()?>ClientTasker/removeJcats" class="btn btn-primary btnAction" name="task"
                value="removeJcats">Remove Jcats</button>
            <button action="<?=base_url()?>ClientTasker/switchServer" class="btn btn-primary btnAction" name="task"
                value="changeServer">Switch Server</button>
        </div>
    </div>
</div>
<table id="stationTableDashboard" class="table" class="display">
    <thead class="thead-dark">
        <tr>
            <th>Name</th>
            <th>IP address</th>
            <th>Server
                <span class="server-info-icon">
                    <span class="fas fa-info-circle"></span>
                    <span class="server-info-message">This column shows
                        what server the station is linked to</span>
                </span>
            </th>
            <th>Status</th>
        </tr>
    </thead>
</table>

<div class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Password</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input form="clientTaskForm" type="password" name="password" placeholder="Password">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script language="JavaScript">
// dectale base_url for cleaner url's
var base_url = "<?=base_url();?>";

/**
 * DataTables code
 */
$(document).ready(function() {
    /**
     * Define action URL's
     */
    var getClientUrl = base_url + "client/get/";
    var getServerUrl = base_url + "server/get/";

    var password = function() {
        var password = alertify.prompt('Password: ').set('type', 'password');
        return password;
    }

    /**
     * DataTable for displaying station info
     * Made using DataTables framework
     * Uses AJAX sourced data
     * 
     * TODO: Make it to display real time database data
     */
    var stationTable = $('#stationTableDashboard').DataTable({
        ajax: {
            method: 'GET',
            url: getClientUrl,
            datatype: 'JSON',
            dataSrc: function(response) {
                return response.result
            }
        },
        select: {
            style: 'multi'
        },
        paging: false,
        columnDefs: [{
            type: 'natural',
            targets: [0, 1, 2, 3]
        }],
        // Fill columns with the data
        columns: [{
                "data": "name"
            },
            {
                "data": "ip"
            },
            {
                // We want server name, not ID
                "data": "server_id",
                /** 
                 * Render new cell input
                 * 
                 * Does the job, but does not allow table to sort by this column
                 * TODO: make it to render actual data, not only display cell value
                 */
                render: function(data, type, full, meta) {
                    var currentCell = $("#stationTableDashboard")
                        .DataTable().cells({
                            "row": meta.row,
                            "column": meta.col
                        }).nodes(0);
                    $.ajax({
                        url: getServerUrl,
                        data: {
                            id: data
                        },
                        method: 'GET'
                    }).done(function(response) {
                        $(currentCell).html(response.result[0]
                            .name);
                        $(currentCell).attr('name="server_id"');
                        $(meta.row).attr('form="clientTaskForm"');
                    });
                    return null;
                }
            },
            {
                "data": "status",
                render: function(data, type, row) {
                    var status = data;
                    if (status == 0) {
                        return '<div class="status-red"></div>';
                    } else if (status == 1) {
                        return '<div class="status-green"></div>';
                    }
                }
            }
        ]
    });



    // Realoads data every 15 seconds, but deselects selected rows
    // workaround is to execute only if all rows are deselected
    setInterval(function() {
        $.ajax({
            methot: 'POST',
            url: base_url + "/ClientTasker/statusUpdate"
        });
        if (!stationTable.rows( '.selected' ).any()) {
            // Reload table data
            stationTable.ajax.reload();
        }
        // Update station status
    }, 15000);

    /**
     * Button functions
     */
    // Update status
    $('#updateStatusButton').click(function() {
        event.preventDefault(); // Prevent default action
        var url = $(this).attr('action'); // Get the URL from buttons attribute
        $.ajax({
            methot: 'POST',
            url: url
        });

    });

    // Select all the rows in the table
    $('#selectAllRowsButton').click(function() {
        stationTable.rows().select();
    });

    // Deselect all the rows in the table
    $('#deselectAllRowsButton').click(function() {
        stationTable.rows().deselect();
    });

    /**
     * Handle the Reboot button
     */
    $('.btnAction').click(function() {
        event.preventDefault(); // Prevent default action
        var select = document.getElementById("selectServer");
        var server = select.options[select.selectedIndex].value;
        var data = stationTable.rows('.selected')
            .data(); // Get data from the selected rows
        var url = $(this).attr('action'); // Get the URL from buttons attribute
        // Prompt password form to confirm the action and parse the password to POST request
        alertify.prompt('Please enter stations root password').set({
            // If user pressed OK
            'onok': function(evt, password) {
                // Declare post_data object with station array and a password
                var post_data = {
                    stations: [],
                    password: password,
                    server: server
                };
                // Original data is a mess of arrays and objects, just put the required data to the stations array
                $.each(data, function(i, item) {
                    post_data.stations.push(item);
                });
                // Post the data
                $.post(url, post_data, function(response) {
                    // Collect the response and alert about all fails
                    alertify.set('notifier', 'delay', 10);
                    alertify.set('notifier', 'position',
                        'top-center');
                    response.result.forEach(element => {
                        if (element.status ==
                            "failed") {
                            alertify.error(element
                                .station + ":\n" +
                                element.status +
                                ":\n" + element
                                .message);
                        }

                    });
                }, 'json');
            },
            'type': 'password', // Input type password
            'title': 'Password' // Prompt title
        });

    });
});
</script>