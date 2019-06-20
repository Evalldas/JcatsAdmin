<div class="row">
    <form class="form-inline" action="<?=base_url()?>/tasker/" method="POST">
        <button type="submit" name="task" value="restart">Restart</button>
    </form>
    <div class="col-lg-7">
        <h3 class="col-title">Klientai</h3>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Pavadinimas</th>
                    <th>IP adresas</th>
                    <th>Serveris</th>
                </tr>
            </thead>
            <?php foreach($clients as $client) {?>
                <tr>
                    <td><?=$client["id"]?></td>
                    <td><?=$client["name"]?></td>
                    <td><?=$client["ip"]?></td>
                    <td><?=$client["server_id"]?></td>
                </tr>
            <?php }?>
        </table>
    </div>
    <div class="col-lg-5">
        <h3 class="col-title">Serveriai</h3>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Pavadinimas</th>
                    <th>IP adresas</th>
                </tr>
            </thead>
            <?php foreach($servers as $server) {?>
                <tr>
                    <td><?=$server["id"]?></td>
                    <td><?=$server["name"]?></td>
                    <td><?=$server["ip"]?></td>
                </tr>
            <?php }?>
        </table>
    </div>
</div>