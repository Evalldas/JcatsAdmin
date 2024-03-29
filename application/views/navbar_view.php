<nav class="navbar navbar-expand-lg navbar-dark bg-dark" role="navigation">

    <a class="navbar-brand" href="<?=base_url();?>"><img src="<?=base_url()?>/public/icons/lt.svg"
            style="width: 30px; height: 100%;"> CAX branch Lithuanian CTC</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="nav navbar-nav">
            <li class="nav-item ">
                <a class="nav-link" href="<?=base_url();?>">Main page </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="<?=site_url('dashboard/manage_stations')?>">Manage stations </a>
            </li>
            <?php if($user_role == 1){?>
            <li class="nav-item">
                <a class="nav-link" href="<?=site_url('dashboard/admin_tools')?>">Admin tools </a>
            </li>
            <?php }?>
        </ul>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item my-2 my-lg-0">
                <a class="nav-link" href="<?=site_url('dashboard/profile_management')?>">My profile </a>
            </li>
            <li class="nav-item">
                <a class="nav-link btn btn-secondary" href="<?=site_url('dashboard/logout')?>">Logout </a>
            </li>
        </ul>
    </div>
</nav>