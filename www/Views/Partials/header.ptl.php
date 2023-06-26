<header class="header">
    <h1>La carte chance</h1>
    <nav>
        <ul class='menu'>
            <li><a href="/">Accueil</a>

            </li>
            <li><a href="/products">Tous les jeux</a>
                <ul class='sous-menu'>
                    <li>Test sous menu-1</li>
                    <li>Sous menu</li>
                </ul>
            </li>
            <li><a href="/posts">Articles</a></li>
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

        <?php
        $uri = $_SERVER["REQUEST_URI"];
        $uriExploded = explode("?", $uri);
        $uri = strtolower(trim($uriExploded[0], "/"));
        ?>

        <ul class="menu">
            <li>
                <?php if (!isset($_SESSION["id"])) { ?>
                    <?php if ($uri != "se-connecter") { ?>
                        <a onclick="window.location.href='/login';">
                            Se connecter
                        <?php } ?>
                        </a>
            </li>
            <?php
                    if ($uri != "s-inscrire") { ?>
                <li>
                    <a onclick="window.location.href='/s-inscrire'">
                        S'inscrire
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
        </ul>



    </nav>
</header>