<div class="login-page">
    <div class="form">
        <form id="login-form" class="login-form" methot="post" action="<?=base_url()?>user/login">
            <input name="username" type="text" placeholder="username" />
            <input name="password" type="password" placeholder="password" />
            <button type="submit" name="submit">login</button>
        </form>
    </div>
</div>

<script>
    var base_url = "<?=base_url();?>";
    $('#login-form').submit(function (event) {
        event.preventDefault();
        var url = $(this).attr('action'); // Get action url from html form
        var post_data = $(this).serialize(); // Serialize data from input fields
        // Send the POST method
        $.post(url, post_data, function(o) {
            if(o.result == 1) {
                window.location.href = base_url + "dashboard/";
            }
                alertify.error("Login failed");
            
        }, 'json');
    });
</script>