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
                        <a class="nav-link " href="#">Tous les jeux</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Articles
                        </a>
                        <ul class="dropdown-menu bg-secondary">
                            <li><a class="dropdown-item" href="#">Tutoriels</a></li>
                            <li><a class="dropdown-item" href="#">Rewiews</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <?php
                    $uri = $_SERVER["REQUEST_URI"];
                    $uriExploded = explode("?", $uri);
                    $uri = strtolower(trim($uriExploded[0], "/"));
                    ?>

                    <li class="nav-item">
                        <?php if (!isset($_SESSION["id"])) { ?>
                            <?php if ($uri != "se-connecter") { ?>
                                <a class='nav-link' href='/login'>
                                    Se connecter
                                <?php } ?>
                                </a>
                    </li>
                    <?php
                            if ($uri != "s-inscrire") { ?>
                        <li class="nav-item">
                            <a class='nav-link' href='/s-inscrire'>
                                S'inscrire
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>

                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

</header>