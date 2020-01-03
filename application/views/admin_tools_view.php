<div class="container-fluid">
    <div class="row">
        <div class="col-3"><br>
            <h4>Create a new user</h4>
            <form class="form-small user-form" method="post" action="<?=base_url()?>User/create">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input class="form-control" type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select class="form-control" name="role">
                        <?php foreach ($roles as $role) {?>
                        <option value="<?=$role['id']?>"><?=$role['name']?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input class="form-control" name="password" type="password" placeholder="Password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-modal">Submit</button>
            </form>
        </div>
        <div class="col-2"><br>
            <h4>Edit user roles</h4>
            <form class="form-small user-form" method="post" action="<?=base_url()?>User/update">
                <div class="form-group">
                    <label for="username">User:</label>
                    <select name="id" class="form-control">
                        <?php foreach ($users as $user) {?>
                        <option value="<?=$user['id']?>"><?=$user['name']?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select class="form-control" name="role">
                        <?php foreach ($roles as $role) {?>
                        <option value="<?=$role['id']?>"><?=$role['name']?></option>
                        <?php }?>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form><br>
            <h4>Delete a user</h4>
            <form class="form-small" method="post" onsubmit="return confirm('Do you realy want to delete this user?')"
                action="<?=base_url()?>User/delete">
                <div class="form-group">
                    <label for="username">User:</label>
                    <select name="id" class="form-control">
                        <?php foreach ($users as $user) {?>
                        <option value="<?=$user['id']?>"><?=$user['name']?></option>
                        <?php }?>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-danger">Submit</button>
            </form><br>
            <h4>Reset user password</h4>
            <form class="form-small user-form" method="post" action="<?=base_url()?>User/update">
                <div class="form-group">
                    <label for="username">User:</label>
                    <select name="id" class="form-control">
                        <?php foreach ($users as $user) {?>
                        <option value="<?=$user['id']?>"><?=$user['name']?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input class="form-control" name="password" type="password" placeholder="Password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
var base_url = "<?=base_url();?>";
$('.user-form').submit(function(event) {
    event.preventDefault();
    var url = $(this).attr('action'); // Get action url from html form
    var post_data = $(this).serialize(); // Serialize data from input fields
    // Send the POST method
    $.post(url, post_data, function(o) {
        if (o.result == 1) {
            window.location.href = base_url + "dashboard/admin_tools";
        }
        alertify.error("Error");

    }, 'json');
});
</script>