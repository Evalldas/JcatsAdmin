<div class="task-menu row">
    <div class="col-lg-2 task-menu-group">
        <label for="tableProperties">Table actions</label><br>
        <div class="btn-group" role="group">
            <button class="btn btn-primary" id="selectAllRowsButton">Select All</button>
            <button class="btn btn-primary" id="deselectAllRowsButton">Deselect All</button>
        </div>
    </div>
    <div class="col-lg-2 task-menu-group">
        <label for="quickCommands">Quick commands</label><br>
        <div class="btn-group" role="group">
            <button class="btn btn-primary" id="rebootButton"
                action="<?=base_url()?>ClientTasker/reboot">Reboot</button>
        </div>
    </div>
    <div class="col-lg-8 task-menu-group">
        <label for="jcatsProperties">Jcats actions</label><br>
        <div class="btn-group" role="group">
            <select name="serverIp" class="btn btn-secondary">
                <?php foreach($servers as $server) {?>
                <option value="<?=$server['ip'];?>"><?=$server['name'];?></option>
                <?php }?>
            </select>
            <button class="btn btn-primary" name="task" value="installJcats">Install Jcats</button>
            <button class="btn btn-primary" name="task" value="removeJcats">Remove Jcats</button>
            <button class="btn btn-primary" name="task" value="changeServer">Change server</button>
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
     * AJAX request to fill datatable
     */
    $.ajax({
        method: 'GET',
        url: getClientUrl,
        datatype: 'json',
        success: function(response) {
            // On successful connection do the DataTables magic
            var stationTable = $('#stationTableDashboard').DataTable({
                select: {
                    style: 'multi'
                },
                paging: false,
                processing: true,
                data: response.result,
                columnDefs: [{
                    type: 'natural',
                    targets: [0, 1, 2]
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
                        // Render new cell input
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
                    }
                ]
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
            $('#rebootButton').click(function() {
                event.preventDefault(); // Prevent default action
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
                            password: password
                        };
                        // Original data is a mess of arrays and objects, just put the required data to the stations array
                        $.each(data, function(i, item) {
                            post_data.stations.push(item);
                        });
                        // Post the data
                        $.post(url, post_data, function(response) {
                            // Collect the response and alert about all fails
                            alertify.set('notifier','delay', 10);
                            alertify.set('notifier','position', 'top-center');
                            response.result.forEach(element => {
                                if(element.status == "failed") {
                                    alertify.error(element.station + ":\n" + element.status + ":\n" + element.message);
                                }
                            });
                        }, 'json');
                    },
                    'type': 'password', // Input type password
                    'title': 'Password' // Prompt title
                });

            });
        }
    });

});
</script>