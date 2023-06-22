<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Super site</title>
    <meta name="description" content="ceci est un super site">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <main>

        <?php $this->partial("header", []) ?>

        <h1>Template Front</h1>
        <!--<?php
            $uri = $_SERVER["REQUEST_URI"];
            $uriExploded = explode("?", $uri);
            $uri = strtolower(trim($uriExploded[0], "/"));
            ?>
    <?php if (!isset($_SESSION["id"])) { ?>
        <?php if ($uri != "se-connecter") { ?>
            <button onclick="window.location.href='/se-connecter';">
                Se connecter
            </button>
        <?php } ?>
        <?php if ($uri != "s-inscrire") { ?>
            <button onclick="window.location.href='/s-inscrire';">
                S'inscrire
            </button>
        <?php } ?>
    <?php } ?> -->

        <!-- inclure la vue -->
        <?php include $this->view; ?>
    </main>
    <?php $this->partial("footer", []) ?>
</body>

</html>