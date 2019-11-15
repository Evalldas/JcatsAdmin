<div class="row">
    <div class="col-lg-7">
        <form method="POST" id="clientTaskForm" action="<?=base_url()?>ClientTasker/">
            <h4>Užduotys:</h4>
            <input type="password" name="password" placeholder="Slaptažodis">
            <button class="btn btn-danger" name="task" value="reboot">Restart</button><br><br>
            <select name="serverIp" class="btn btn-dark">
                <?php foreach($servers as $server) {?>
                    <option value="<?=$server['ip'];?>"><?=$server['name'];?></option>
                <?php }?>
            </select>
            <button class="btn btn-danger" name="task" value="installJcats">Install Jcats</button>
            <button class="btn btn-danger" name="task" value="removeJcats">Remove Jcats</button>
            <button class="btn btn-danger" name="task" value="changeServer">Change server</button>
        </form>
        <h3 class="col-title">Klientai</h3>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Pavadinimas</th>
                    <th>IP adresas</th>
                    <th>Serveris</th>
                    <th><input type="checkbox" onClick="toggle(this)" /> Pasirinkti visus<br/></th>
                </tr>
            </thead>
            <?php foreach($clients as $client) {?>
                <tr>
                    <td><?=$client["name"]?></td>
                    <td><?=$client["ip"]?></td>
                    <td><?=$servers[$client["server_id"]]["name"]?></td>
                    <td><input form="clientTaskForm" type="checkbox" name="clientCheckbox[]" value="<?=$client['id']?>"><br/></td>
                </tr>
            <?php }?>
        </table>
    </div>
</div>

<script language="JavaScript">
    function toggle(source) {
        checkboxes = document.getElementsByName('clientCheckbox[]');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
    }

</script>