<?php

use function App\Core\TokenJwt\getAllInformationsFromToken;

require_once '/var/www/html/Core/TokenJwt.php';

if (isset($_SESSION["token"])) {
    $informationsUser = getAllInformationsFromToken($_SESSION["token"]);
}

?>

<header class="header">

    <h1>La carte chance</h1>

    <nav class="navbar navbar-expand-lg bg-secondary p-2 my-3 ">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-3">
                    <li class="nav-item">
                        <a class="nav-link " href="/">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class='nav-link' href="/page/allgames">Jeux</a>
                    </li>
                    <li class="nav-item">
                        <a class='nav-link' href="/page/allaboutgames">Trucs et astuces</a>
                    </li>

                    <?php if (isset($_SESSION["token"])) : ?>
                        <!-- test si le token est set, donc cest un utilisateur -->
                        <li class="nav-item">
                            <a class='nav-link' href="/profil">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">Se déconnecter</a>
                        </li>
                        <!-- test si l'utilisateur est un admin -->
                    <?php if ($informationsUser['roleName'] == "admin"): ?>
                        <li class="nav-item">
                          <a class="nav-link" href="/sys/dashboard">Backoffice</a>
                        </li>
                    <?php endif; ?>
                    <?php else : ?>
                        <!-- le client n'est pas connecter -->
                        <li class="nav-item">
                            <a class='nav-link' href="/login">Se connecter</a>
                        </li>
                        <li class="nav-item">
                            <a class='nav-link' href="/s-inscrire">S' inscrire</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>
    </nav>

</header>