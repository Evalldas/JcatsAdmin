<!-- 
    Top task menu for storing task buttons
    Position sticky
-->
<div class="task-menu">
    <!-- Button to display addNewStationModal on the screen -->
    <button class="btn btn-primary btn-top-menu" data-toggle="modal" data-target="#addNewStationModal">Add new
        station</button>
    <!-- Button to display addNewServerModal on the screen -->
    <button class="btn btn-primary btn-top-menu" data-toggle="modal" data-target="#addNewServerModal">Add new
        server</button>
</div>
<!-- End of task menu -->

<div>
    <h1 id="success-message"></h1>
</div>

<!-- 
    Row to store station and server lists
    2 collumns. Wider for stations and narrower for servers
    Tables are made with php for loops and prints a lot of html modals with attribute hidden
    Need to find more efficient way for displaying right data from the database on the modal
-->
<div class="row">
    <div class="col-lg-7">
        <!-- Start of the station list -->
        <h3 class="col-title">Workstations</h3>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>IP address</th>
                    <th>Server <span class="server-info-icon"><span class="fas fa-info-circle"></span><span
                                class="server-info-message">This column shows
                                what server the station is linked to</span></span></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <?php foreach($clients as $client) {?>
            <tr>
                <td><?=$client["name"]?></td>
                <td><?=$client["ip"]?></td>
                <td><?=$servers[$client["server_id"]]["name"]?></td>
                <td><button class="btn btn-primary"
                        onclick="displayModal('editClientModal' + <?=$clientCount?>)">Edit</button>
                </td>
                <td>
                    <form method="POST" onsubmit="return confirm('Do you really want to delete station from the list?')"
                        action="<?=base_url()?>/client/delete/">
                        <button class="btn btn-danger" type="submit" name="id"
                            value="<?=$client['id'];?>">Delete</button>
                    </form>
                </td>
            </tr>
            <!-- Modal for station data editing -->
            <div id="editClientModal<?=$clientCount?>" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <h3>Edit station</h3>
                    <form action="<?=base_url()?>/client/update/" method="POST">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input name="name" type="text" value="<?=$client['name'];?>" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="text">IP:</label>
                            <input name="ip" type="text" value="<?=$client['ip'];?>" class="form-control" id="ip"
                                required pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="XXX.XXX.XXX.XXX">
                        </div>
                        <input type="hidden" name="id" value="<?=$client['id'];?>">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>
                    <button class="btn btn-dark btn-modal"
                        onclick="closeModal('editClientModal' + <?=$clientCount?>)">Cancel</button>
                </div>
            </div>
            <!-- End of station editing modal -->
            <?php $clientCount++;?>
            <?php }?>
        </table>
        <!-- End of station list -->
    </div>


    <div class="col-lg-5">
        <!-- Start of server list -->
        <h3 class="col-title">Servers</h3>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>IP address</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <?php foreach($servers as $server) {?>
            <?php if($server['id'] != 0) {?>
            <tr>
                <td><?=$server["id"]?></td>
                <td><?=$server["name"]?></td>
                <td><?=$server["ip"]?></td>
                <td><button class="btn btn-primary"
                        onclick="displayModal('editServerModal' + <?=$serverCount?>)">Edit</button></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Do you really want to delete server from the list?')"
                        action="<?=base_url()?>/server/delete/">
                        <button class="btn btn-danger" type="submit" name="id"
                            value="<?=$server['id']?>">Delete</button>
                    </form>
                </td>
            </tr>
            <!-- Modal for server data editing -->
            <div id="editServerModal<?=$serverCount?>" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <h3>Edit server</h3>
                    <form action="<?=base_url()?>/server/update/" method="POST">
                        <div class="form-group">
                            <label for="id">ID:</label>
                            <input name="new-id" type="text" value="<?=$server['id'];?>" class="form-control"
                                id="new-id">
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input name="name" type="text" value="<?=$server['name'];?>" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="text">IP:</label>
                            <input name="ip" type="text" value="<?=$server['ip'];?>" class="form-control" id="ip"
                                required pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="XXX.XXX.XXX.XXX">
                        </div>
                        <input type="hidden" name="id" type="text" value="<?=$server['id'];?>" id="id">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>
                    <button class="btn btn-dark btn-modal"
                        onclick="closeModal('editServerModal' + <?=$serverCount?>)">Cancel</button>
                </div>
            </div>
            <!-- End of server editing modal -->
            <?php $serverCount++;?>
            <?php }?>
            <?php }?>
        </table>
        <!-- End of server list -->
    </div>
</div>
<!-- End of the list row -->


<!--
    Modals with php forms
-->

<button id="openEditStationModal" class="btn btn-primary btn-top-menu" relid="3" data-toggle="modal" data-target="#editStationModal">Add new
    server</button>

<!-- Modal with form to edit worksations to the database -->
<div id="editStationModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Type in station details:</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-large" id="addClientForm" method="post" action="<?=base_url()?>client/create/">
                    <div class="form-group">
                        <label for="stationNameInput">Station name:</label>
                        <input id="stationName" class="form-control" type="text" name="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="stationIpInput">IP address:</label>
                        <input id="stationIp" class="form-control" name="ip" required
                            pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="XXX.XXX.XXX.XXX">
                    </div>
                    <button type="submit" class="btn btn-primary btn-modal">Submit</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- End of station adding modal -->

<!-- Modal with form to add new worksations to the database -->
<div id="addNewStationModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Type in station details:</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-large" id="addClientForm" method="post" action="<?=base_url()?>client/create/">
                    <div class="form-group">
                        <label for="stationNameInput">Station name:</label>
                        <input class="form-control" type="text" name="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="stationIpInput">IP address:</label>
                        <input class="form-control" name="ip" required pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$"
                            placeholder="XXX.XXX.XXX.XXX">
                    </div>
                    <button type="submit" class="btn btn-primary btn-modal">Submit</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- End of station adding modal -->

<!-- Modal with form to add new servers to the database -->
<div id="addNewServerModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Type in server details:</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-large" id="addServerForm" method="post" action="<?=base_url()?>server/create/">
                    <div class="form-group">
                        <label for="stationIdInput">Server ID:</label>
                        <input class="form-control" type="text" name="id" placeholder="ID">
                    </div>
                    <div class="form-group">
                        <label for="stationNameInput">Server name:</label>
                        <input class="form-control" type="text" name="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="stationIpInput">IP address:</label>
                        <input class="form-control" name="ip" required pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$"
                            placeholder="XXX.XXX.XXX.XXX">
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
<!-- End of server adding modal -->

<!-- Be aware, JavaScript code below -->
<script type="text/javascript">
/**
 * GLOBAL variables
 */
var base_url = "<?=base_url()?>";

/**
 * jQuery functions
 * make sure jQuery library is imported before jQuery code or it won't work
 */
$(function() {
    /**
     * Gobal JQuery variables
     */
    var msg_success = "Success!";
    var msg_ip_duplicate =
        "IP address you've entered is already in the database, please check the IP address before submiting";
    var msg_name_duplicate =
        "Name you've entered is already in the database, please check the name before submiting";
    var msg_undefined_err = "Unknown error has occured, please try again or contact your system administrator";
    var msg_id_duplicate = "ID you've entered is already in the database, please check the ID before submiting";

    /**
     * This function handles addNewStation form
     * 
     * @param {String}  event       Helps to control the event called by the HTML form
     * 
     * @return void
     */
    $("#addClientForm").submit(function(event) {
        event.preventDefault(); // Prevent default action of the form
        var url = $(this).attr('action'); // Get action url from html form
        var post_data = $(this).serialize(); // Serialize data from input fields

        // Send the POST method
        $.post(url, post_data, function(o) {

            /**
             * Client controller checks if there are no duplicates and returns either 
             * 1 for succsessful insertion, 2 for name duplicate, 3 for IP duplicate or 0 for undefined error
             * If station has been added, alert about success or error and redirect back to the previous page
             */
            if (o.result == 1) {
                alert(msg_success);
                window.location.href = "<?=site_url('dashboard/manage_stations')?>";
            } else if (o.result == 2) {
                alert(msg_name_duplicate);
            } else if (o.result == 3) {
                alert(msg_ip_duplicate);
            } else {
                alert(msg_undefined_err);
            }
        }, 'json');

    });

    /**
     * This function handles addNewServer form
     * 
     * @param {String}  event       Helps to control the event called by the HTML form
     * 
     * @return void
     */
    $("#addServerForm").submit(function(event) {
        event.preventDefault(); // Prevent default action of the form
        var url = $(this).attr('action'); // Get action url from html form
        var post_data = $(this).serialize(); // Serialize data from input fields

        // Send the POST method
        $.post(url, post_data, function(o) {

            /**
             * Server controller checks if there are no duplicates and returns either 
             * 1 for succsessful insertion, 2 for ID duplicate, 3 for name duplicate, 4 for IP duplicate or 0 for undefined error
             * If station has been added, alert about success or error and redirect back to the previous page
             */
            if (o.result == 1) {
                alert(msg_success);
                window.location.href = "<?=site_url('dashboard/manage_stations')?>";
            } else if (o.result == 2) {
                alert(msg_id_duplicate);
            } else if (o.result == 3) {
                alert(msg_name_duplicate);
            } else if (o.result == 4) {
                alert(msg_ip_duplicate);
            } else {
                alert(msg_undefined_err);
            }
        }, 'json');

    });
});

$(document).ready(function() {
    $('#openEditStationModal').click(function() {

        var id = $(this).attr('relid'); // get attribute value
        var url = base_url + "client/get/";
        $.ajax({
            url : url,
            data:{id : id},
            method:'GET',
            dataType:'json',
            success:function(response) {
                document.getElementById("stationName").value = response.result[0].name;
                document.getElementById("stationIp").value = response.result[0].ip;
            }
        });
    });
});
</script>