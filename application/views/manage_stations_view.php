<!-- 
    Top task menu for storing task buttons
    Position sticky
-->
<div class="task-menu">
    <!-- Button to display addNewStationModal on the screen -->
    <button class="btn btn-primary btn-top-menu" onclick="displayModal('addNewStationModal')">Add new station</button>

    <!-- Button to display addNewServerModal on the screen -->
    <button class="btn btn-primary btn-top-menu" onclick="displayModal('addNewServerModal')">Add new station</button>
</div>
<!-- End of task menu -->


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
                    <th>ID</th>
                    <th>Name</th>
                    <th>IP address</th>
                    <th>Server <span class="server-info-icon">&#8505;<span class="server-info-message">This column shows
                                what server the station is linked to</span></span></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <?php foreach($clients as $client) {?>
            <tr>
                <td><?=$client["id"]?></td>
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
                            <label for="name">Name:</label>
                            <input name="name" type="text" value="<?=$server['name'];?>" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="text">IP:</label>
                            <input name="ip" type="text" value="<?=$server['ip'];?>" class="form-control" id="ip"
                                required pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="XXX.XXX.XXX.XXX">
                        </div>
                        <input type="hidden" name="id" value="<?=$server['id'];?>">
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
<!-- Modal with form to add new workstations to the database -->
<div id="addNewStationModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <h4>Type in station details:</h4>
        <form class="form-large" id="addClientForm" method="post" action="<?=base_url()?>/client/create/">
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
        <button class="btn btn-dark btn-modal" onclick="closeModal('addNewStationModal')">Cancel</button>
    </div>
</div>

<!-- Modal with form to add new servers to the database -->
<div id="addNewServerModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <h4>Type in server details:</h4>
        <form class="form-large" id="addServerForm" method="post" action="<?=base_url()?>/server/create/">
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
        <button class="btn btn-dark btn-modal" onclick="closeModal('addNewServerModal')">Cancel</button>
    </div>
</div>