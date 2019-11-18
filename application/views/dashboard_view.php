<div class="task-menu">
    <form method="POST" id="clientTaskForm" action="<?=base_url()?>ClientTasker/">
        <input type="password" name="password" placeholder="Slaptažodis">
        <button class="btn btn-primary" name="task" value="reboot">Restart</button><br><br>
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
            <th>
                <label for="checkbox" class="checkbox">
                    <input type="checkbox" onClick="toggle(this)" style="margin-right: 5px;" />
                    Check all
                </label>
            </th>
        </tr>
    </thead>
    <?php foreach($clients as $client) {?>
    <tr>
        <td><?=$client["name"]?></td>
        <td><?=$client["ip"]?></td>
        <td><?=$servers[$client["server_id"]]["name"]?></td>
        <td>
            <label for="checkbox" class="checkbox">
                <input form="clientTaskForm" type="checkbox" name="clientCheckbox[]" value="<?=$client['id']?>">
            </label>
        </td>
    </tr>
    <?php }?>
</table>

<script language="JavaScript">
function toggle(source) {
    checkboxes = document.getElementsByName('clientCheckbox[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}
</script>