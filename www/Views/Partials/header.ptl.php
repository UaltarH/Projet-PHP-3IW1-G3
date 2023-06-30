<?php
use function App\Core\TokenJwt\getAllInformationsFromToken;
require_once '/var/www/html/Core/TokenJwt.php';

if(isset($_SESSION["token"]))
{
    $informationsUser = getAllInformationsFromToken($_SESSION["token"]);
}

?>

<header class="header">
    <h1>La carte chance</h1>
    <nav>
        <ul class='menu'>
            <li>
                <a href="/">Accueil</a>
            </li>
            <li>
                <a href="/page/allgames">Jeux</a>                
            </li>
            <li>
                <a href="/page/allaboutgames">Trucs et astuces</a>
            </li>
        </ul>

        <div class='search'>
            <input type="text" placeholder="Rechercher">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21l-4.3-4.3" />
                </g>
            </svg>

        </div>

        <ul class="menu">
        <?php if (isset($_SESSION["token"])): ?>
            <!-- test si le token est set, donc cest un utilisateur -->
            <li>
                <a href="/">Profile</a>
            </li>
            <li>
                <a href="/logout">Se déconnecter</a>
            </li>
            <!-- test si l'utilisateur est un admin -->
            <?php if ($informationsUser['roleName'] == "admin"): ?>
                <li><a href="/sys/dashboard">Backoffice</a></li>
            <?php endif; ?>
        <?php else: ?> 
            <!-- le client nest pas connecter -->
            <li>
                <a href="/login">Se connecter</a>
            </li>
            <li>
                <a href="/s-inscrire">S'inscrire</a>
            </li>
        <?php endif; ?>
    </nav>

</header>