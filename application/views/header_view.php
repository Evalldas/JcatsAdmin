<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>/public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?=base_url()?>/public/css/main.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?=base_url()?>/public/fontawesome/css/all.css" rel="stylesheet">

    <!-- Custom JCATS icon -->
    <link rel="icon" href="<?=base_url()?>/public/icons/favicon.ico">
    <!-- jQuery library -->
    <script language="JavaScript" type="text/javascript" src="<?=base_url()?>public/js/jquery.min.js"></script>
    <!-- custom JS code -->
    <script language="JavaScript" type="text/javascript" src="<?=base_url()?>public/js/custom.js"></script>
</head>

<body>
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
                <li class="nav-item ">
                    <a class="nav-link" href="<?=site_url('doc/index')?>">Help </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid">