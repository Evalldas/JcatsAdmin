<div class="task-menu">
    <form method="POST" id="clientTaskForm" action="<?=base_url()?>ClientTasker/">
        <button class="btn btn-primary" name="task" value="reboot">Restart</button>
        <select name="serverIp" class="btn btn-outline-secondary">
            <?php foreach($servers as $server) {?>
            <option value="<?=$server['ip'];?>"><?=$server['name'];?></option>
            <?php }?>
        </select>
        <button class="btn btn-primary" name="task" value="installJcats">Install Jcats</button>
        <button class="btn btn-primary" name="task" value="removeJcats">Remove Jcats</button>
        <button class="btn btn-primary" name="task" value="changeServer">Change server</button>
    </form>
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
            <th>
                <label for="checkbox" class="checkbox">
                    <input type="checkbox" onClick="toggle(this)" style="margin-right: 5px;" />
                    Select all
                </label>
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
var base_url = "<?=base_url();?>";

function toggle(source) {
    checkboxes = document.getElementsByName('clientCheckbox[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}

/**
 * DataTables code
 */
$(document).ready(function() {
    /**
     * Define action URL's
     */
    var getClientUrl = base_url + "client/get/";
    var getServerUrl = base_url + "server/get/";

    /**
     * AJAX request to fill datatable
     */
    $.ajax({
        method: 'GET',
        url: getClientUrl,
        datatype: 'json',
        success: function(response) {
            // On successful connection do the DataTables magic
            $('#stationTableDashboard').DataTable({
                paging: false,
                processing: true,
                data: response.result,
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
                            });
                            return null;
                        }
                    },
                    {
                        // Column with checkboxes
                        orderable: false,
                        render: function() {
                            return '<label for="checkbox" class="checkbox"><input form="clientTaskForm" type="checkbox" name="clientCheckbox[]"></label>';
                        }
                    }
                ]
            });
        }
    });

});
</script>