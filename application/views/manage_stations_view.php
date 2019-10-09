<div class="top-menu">
    <button class="btn btn-primary btn-top-menu" onclick="displayModal('addNewStationModal')">Add new station</button>
    <div id="addNewStationModal" class="modal">
        <div class="modal-content">
            <h4>Type in station details:</h4>
            <form class="form-large" id="addClientForm" method="post" action="<?=base_url()?>/client/create/">
                <div class="form-group">
                    <label for="stationNameInput">Station name:</label>
                    <input class="form-control" type="text" name="name" placeholder="Pavadinimas">
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


    <button class="btn btn-primary btn-top-menu" onclick="displayModal('addNewServerModal')">Add new station</button>
    <div id="addNewServerModal" class="modal">
        <div class="modal-content">
            <h4>Type in server details:</h4>
            <form class="form-large" id="addServerForm" method="post" action="<?=base_url()?>/server/create/">
                <div class="form-group">
                    <label for="stationNameInput">Server name:</label>
                    <input class="form-control" type="text" name="name" placeholder="Pavadinimas">
                </div>
                <div class="form-group">
                    <label for="stationIpInput">IP address:</label>
                    <input class="form-control" name="ip" required pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$"
                        placeholder="XXX.XXX.XXX.XXX">
                </div>
                <button type="submit" class="btn btn-primary btn-modal">Submit</button>
            </form>
            <button class="btn btn-dark btn-modal" onclick="closeModal('addNewServerModal')">Cancel</button>
        </div>
    </div>

</div>




<div class="row">
    <div class="col-lg-7">
        <h3 class="col-title">Clients</h3>


        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Pavadinimas</th>
                    <th>IP adresas</th>
                    <th>Serveris</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <?php foreach($clients as $client) {?>


            <tr>
                <td><?=$client["id"]?></td>
                <td><?=$client["name"]?></td>
                <td><?=$client["ip"]?></td>
                <td><?=$servers[$client["server_id"] - 1]["name"]?></td>
                <td><button class="btn btn-primary" onclick="displayEditClientModal(<?=$clientCount?>)">Edit</button>
                </td>
                <td>
                    <form method="POST" action="<?=base_url()?>/client/delete/">
                        <button class="btn btn-danger" type="submit" name="id"
                            value="<?=$client['id'];?>">Delete</button>
                    </form>
                </td>
            </tr>


            <div id="editClientModal<?=$clientCount?>" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <h3>Redaguoti klientą</h3>
                    <form action="<?=base_url()?>/client/update/" method="POST">
                        <div class="form-group">
                            <label for="name">Kliento pavadinimas:</label>
                            <input name="name" type="text" value="<?=$client['name'];?>" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="text">IP:</label>
                            <input name="ip" type="text" value="<?=$client['ip'];?>" class="form-control" id="ip">
                        </div>
                        <div class="form-group">
                            <h3>Serveris:</h3>
                            <select name="server_id" class="btn btn-default">
                                <?php foreach($servers as $server) {?>
                                <option value="<?=$server['id'];?>"><?=$server['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <input type="hidden" name="id" value="<?=$client['id'];?>">
                        <button type="submit" name="submit" class="btn btn-primary">Atnaujinti</button>
                    </form>
                    <form style="float: right;" onsubmit="close()">
                        <button type="submit" class="btn btn-dark">Atšaukti</button>
                    </form>
                </div>
            </div>
            <?php $clientCount++;?>
            <?php }?>


        </table>
    </div>
    <div class="col-lg-5">
        <h3 class="col-title">Servers</h3>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Pavadinimas</th>
                    <th>IP adresas</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <?php foreach($servers as $server) {?>
            <tr>
                <td><?=$server["id"]?></td>
                <td><?=$server["name"]?></td>
                <td><?=$server["ip"]?></td>
                <td><button class="btn btn-primary" onclick="display2(<?=$serverCount?>)">Edit</button></td>
                <td>
                    <form method="POST" action="<?=base_url()?>/server/delete/">
                        <button class="btn btn-danger" type="submit" name="id"
                            value="<?=$server['id']?>">Delete</button>
                    </form>
                </td>
            </tr>
            <div id="myModal2<?=$serverCount?>" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <h3>Redaguoti serverį</h3>
                    <form action="<?=base_url()?>/server/update/" method="POST">
                        <div class="form-group">
                            <label for="name">Kliento pavadinimas:</label>
                            <input name="name" type="text" value="<?=$server['name'];?>" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="text">IP:</label>
                            <input name="ip" type="text" value="<?=$server['ip'];?>" class="form-control" id="ip">
                        </div>
                        <input type="hidden" name="id" value="<?=$server['id'];?>">
                        <button type="submit" name="submit" class="btn btn-primary">Atnaujinti</button>
                    </form>
                    <form style="float: right;" onsubmit="close()<?=$serverCount?>">
                        <button type="submit" class="btn btn-dark">Atšaukti</button>
                    </form>
                </div>
            </div>
            <?php $serverCount++;?>
            <?php }?>
        </table>
    </div>
</div>