<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Super site</title>
    <meta name="description" content="ceci est un super site">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="../style.css"/>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
          rel="stylesheet">

    
    <link href="/Assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/Assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/Assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="/Assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="/Assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/Assets/vendor/simple-datatables/style.css" rel="stylesheet">

    
    <link href="/Assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Assets/css/style.css"/>

    <!-- =======================================================
    * Template Name: NiceAdmin
    * Updated: May 30 2023 with Bootstrap v5.3.0
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>


<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="dashboard" class="logo d-flex align-items-center">
            <img src="assets/img/logo.png" alt="">
            <span class="d-none d-lg-block">La Carte Chance Backoffice</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>


    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-person"></i>
                    </div>
                    <span class="d-none d-md-block dropdown-toggle ps-2"><?= $userProfil["pseudo"] ?></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?= $userProfil["firstName"] ?> <?= $userProfil["lastName"] ?></h6>
                        <span><?= $userProfil["roleId"] == 2 ? "Admin" : "Modérateur" ?></span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-person"></i>
                            <span>Mon compte</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Se déconnecter</span>
                        </a>
                    </li>

                </ul>
            </li>

        </ul>
    </nav>

</header>


<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="dashboard">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-person"></i>
                <span>Utilisateurs</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-controller"></i>
                <span>Jeux</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-bag"></i>
                <span>Articles</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-chat-dots"></i>
                <span>Commentaires</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="/">
                <i class="bi bi-box-arrow-left"></i>
                <span>La Carte Chance</span>
            </a>
        </li>
    </ul>

</aside>

<main id="main" class="main">
    <?php include $this->view; ?>
</main>

<?php $this->partial("footer", []) ?>

<script src="/Assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="/Assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/Assets/vendor/chart.js/chart.umd.js"></script>
<script src="/Assets/vendor/echarts/echarts.min.js"></script>
<script src="/Assets/vendor/quill/quill.min.js"></script>
<script src="/Assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="/Assets/vendor/tinymce/tinymce.min.js"></script>
<script src="/Assets/vendor/php-email-form/validate.js"></script>


<script src="/Assets/js/main.js"></script>

</body>

</html>