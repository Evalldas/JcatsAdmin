<div class="container-fluid">
    <div class="row">
        <div class="col-3"><br>
            <h4>Change password</h4>
            <form class="form-small" id="passwordForm" method="post" action="<?=base_url()?>User/update">
                <div class="form-group">
                    <label for="userPassword">New password</label>
                    <input id="newPassword1" class="form-control" name="password" type="password"
                        placeholder="New password" required>
                </div>
                <div class="form-group">
                    <label for="userPassword">Repeat a new password</label>
                    <input id="newPassword2" class="form-control" name="password_check" type="password"
                        placeholder="New password" required>
                </div>
                <input type="hidden" value="<?=$user_id?>" name="id">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
var base_url = "<?=base_url();?>";
$('#passwordForm').submit(function(event) {
    event.preventDefault();
    var url = $(this).attr('action'); // Get action url from html form
    var post_data = $(this).serialize(); // Serialize data from input fields
    // Check if new passwords match
    if ($('#newPassword1').val() != $('#newPassword2').val()) {
        alertify.alert("Passwords do not match");
    } else {
        // Send the POST method
        $.post(url, post_data, function(o) {
            if (o.result == 1) {
                alertify.alert('Password changed successfuly').set({
                    // If user pressed OK
                    'onok': function() {
                        window.location.href = base_url + "dashboard/profile_management";
                    },
                    'title': 'Success' // Prompt title
                });
            } else {
                alertify.error("Error");
            }

        }, 'json');
    }
});
</script>