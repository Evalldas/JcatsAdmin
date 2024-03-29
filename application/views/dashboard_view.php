<div class="task-menu row">
    <div class="col-lg-3 task-menu-group" style="min-width: 400px;">
        <label for="tableProperties">Table actions</label><br>
        <div class="btn-group" role="group">
            <button class="btn btn-primary" id="selectAllRowsButton">Select All</button>
            <button class="btn btn-primary" id="deselectAllRowsButton">Deselect All</button>
            <button action="<?=base_url()?>ClientTasker/statusUpdate" class="btn btn-primary btn-no-response">Update
                Status</button>
        </div>
    </div>
    <div class="col-lg-2 task-menu-group">
        <label for="quickCommands">Quick actions</label><br>
        <div class="btn-group" role="group">
            <button class="btn btn-primary btnAction" id="rebootButton"
                action="<?=base_url()?>ClientTasker/reboot">Reboot</button>
            <button class="btn btn-primary" data-toggle="modal" data-target="#changeMtuModal">Change
                MTU</button>
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
    <div class="col-lg-2 task-menu-group">
        <label for="quickCommands">Station actions</label><br>
        <div class="btn-group" role="group">
            <button class="btn btn-primary btn-post-password"
                action="<?=base_url()?>ClientTasker/updateStationInfo">Update station info</button>
        </div>

    </div>
</div>
<table id="stationTableDashboard" class="table" class="display">
    <thead class="thead-dark">
        <tr>
            <th>Name</th>
            <th>IP address</th>
            <th>Server
                <span class="info-icon">
                    <span class="fas fa-info-circle"></span>
                    <span class="info-message">This column shows
                        what server the station is linked to</span>
                </span>
            </th>
            <th>Status
                <span class="info-icon">
                    <span class="fas fa-info-circle"></span>
                    <span class="info-message">This column shows status of the station. Green circle means station
                        is online and the red one means it's offline.</span>
                </span>
            </th>
            <th>MTU
                <span class="info-icon">
                    <span class="fas fa-info-circle"></span>
                    <span class="info-message">MTU value of the main stations network interface.</span>
                </span>
            </th>
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

<!-- Modal with form to change workstations MTU size -->
<div id="changeMtuModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change MTU size</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-large" id="changeMtuForm" method="post"
                    action="<?=base_url()?>ClientTasker/changeMtu/">
                    <div class="form-group">
                        <label for="stationMtu">MTU:</label>
                        <input id="stationMtuInput" class="form-control" type="text" name="mtu" placeholder="MTU">
                    </div>
                    <div class="form-group">
                        <label for="stationPassword">Root password</label>
                        <input id="stationPasswordInput" class="form-control" name="password" type="password"
                            placeholder="Password">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-modal">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- End of station editing modal -->

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
            targets: [0, 1, 2, 3, 4]
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
            },
            {
                "data": "mtu"
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
        if (!stationTable.rows('.selected').any()) {
            // Reload table data
            stationTable.ajax.reload();
        }
        // Update station status
    }, 15000);

    /**
     * Button functions
     */
    // Update status
    $('.btn-no-response').click(function() {
        event.preventDefault(); // Prevent default action
        var url = $(this).attr('action'); // Get the URL from buttons attribute
        $.ajax({
            methot: 'POST',
            url: url
        });

    });
    $('.btn-post-password').click(function() {
        event.preventDefault(); // Prevent default action
        var url = $(this).attr('action'); // Get the URL from buttons attribute
        alertify.prompt('Please enter stations root password').set({
            // If user pressed OK
            'onok': function(evt, password) {
                // Declare post_data object with station array and a password
                var post_data = {
                    password: password
                };
                // Post the data
                $.post(url, post_data, 'json');
            },
            'type': 'password', // Input type password
            'title': 'Password' // Prompt title
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
    $("#changeMtuForm").submit(function(event) {
        event.preventDefault(); // Prevent default action of the form
        var url = $(this).attr('action'); // Get action url from html form
        var post_data = {
            stations: [],
            mtu: $('#stationMtuInput').val(),
            password: $('#stationPasswordInput').val()
        };
        var data = stationTable.rows('.selected')
            .data(); // Get data from the selected rows
        // Original data is a mess of arrays and objects, just put the required data to the stations array
        $.each(data, function(i, item) {
            post_data.stations.push(item);
        });
        // Hide the modal
        $('#changeMtuModal').modal('hide');
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

    });
    /**
     * Handle Action buttons
     */
    $('.btnAction').click(async function() {
        if (stationTable.rows('.selected').any()) {
            event.preventDefault(); // Prevent default action
            var select = document.getElementById("selectServer");
            var server = select.options[select.selectedIndex].value;
            var data = stationTable.rows('.selected')
                .data(); // Get data from the selected rows
            var url = $(this).attr('action'); // Get the URL from buttons attribute
            var post_data = {
                stations: [],
                password: null,
                server: null
            };
            // Prompt password form to confirm the action and parse the password to POST request
            alertify.prompt('Please enter stations root password').set({
                // If user pressed OK
                'onok': function(evt, password) {
                    // Declare post_data object with station array and a password
                    post_data.password = password;
                    post_data.server = server;
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

        } else {
            alertify.alert('Alert message', 'No stations are selected!');
        }
    });
});
</script>