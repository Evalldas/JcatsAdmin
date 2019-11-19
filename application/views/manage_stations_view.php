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
                    <th>Server
                        <span class="server-info-icon">
                            <span class="fas fa-info-circle"></span>
                            <span class="server-info-message">This column shows
                                what server the station is linked to</span>
                        </span>
                    </th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <?php foreach($clients as $client) {?>
            <tr>
                <td><?=$client["name"]?></td>
                <td><?=$client["ip"]?></td>
                <td><?=$servers[$client["server_id"]]["name"]?></td>
                <td><button class="openEditStationModal btn btn-primary" relid="<?=$client['id'];?>" data-toggle="modal"
                        data-target="#editStationModal">Edit</button>
                </td>
                <td>
                    <form method="POST" onsubmit="return confirm('Do you really want to delete station from the list?')"
                        action="<?=base_url()?>/client/delete/">
                        <button class="btn btn-danger" type="submit" name="id"
                            value="<?=$client['id'];?>">Delete</button>
                    </form>
                </td>
            </tr>
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
                    <th>Domain</th>
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
                <td><?=$server["domain_name"]?></td>
                <td><button class="openEditServerModal btn btn-primary" relid="<?=$server['id']?>" data-toggle="modal"
                        data-target="#editServerModal">Edit</button>
                <td>
                    <form method="POST" onsubmit="return confirm('Do you really want to delete server from the list?')"
                        action="<?=base_url()?>/server/delete/">
                        <button class="btn btn-danger" type="submit" name="id"
                            value="<?=$server['id']?>">Delete</button>
                    </form>
                </td>
            </tr>
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
<!-- Modal with form to edit worksation data in the database -->
<div id="editStationModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Type in station details:</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="databaseErrorMessage" style="color: red;"></p>
                <form class="form-large" id="editStationForm" method="post" action="<?=base_url()?>client/update/">
                    <div class="form-group">
                        <label for="stationName">Station name:</label>
                        <input id="stationNameInput" class="form-control" type="text" name="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="stationIp">IP address:</label>
                        <input id="stationIpInput" class="form-control" name="ip" required
                            pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="XXX.XXX.XXX.XXX">
                    </div>
                    <input type="hidden" name="id" id="stationIdInput">
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

<!-- Modal with form to edit servers in the database -->
<div id="editServerModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Type in server details:</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="databaseErrorMessage" style="color: red;"></p>
                <form class="form-large" id="editServerForm" method="post" action="<?=base_url()?>server/update/">
                    <div class="form-group">
                        <label for="serverIdInput">Server ID:</label>
                        <input id="serverNewIdInput" class="form-control" type="text" name="new-id" placeholder="ID">
                        <input id="serverIdInput" type="hidden" name="id">
                    </div>
                    <div class="form-group">
                        <label for="serverNameInput">Server name:</label>
                        <input id="serverNameInput" class="form-control" type="text" name="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="serverIpInput">IP address:</label>
                        <input id="serverIpInput" class="form-control" name="ip" required
                            pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="XXX.XXX.XXX.XXX">
                    </div>
                    <div class="form-group">
                        <label for="serverDomainNameInput">Domain name:</label>
                        <input id="serverDomainNameInput" class="form-control" name="domain_name" placeholder="Domain name">
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
<!-- End of server editing modal -->


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
                    <div class="form-group">
                        <label for="stationDomainNameInput">Domain name:</label>
                        <input class="form-control" name="domain_name" placeholder="Domain name">
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
var base_url = "<?=base_url()?>";
</script>