<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Super site</title>
    <meta name="description" content="ceci est un super site">
</head>
<body>
    <h1>Template Front</h1>
    <?php 
        $uri = $_SERVER["REQUEST_URI"];
        $uriExploded = explode("?", $uri);
        $uri = strtolower(trim( $uriExploded[0], "/"));
    ?>
    <?php if (!isset($_SESSION["id"])) { ?>
        <?php if ($uri != "se-connecter"){ ?>
            <button onclick="window.location.href='/se-connecter';">
                Se connecter
            </button>
        <?php } ?>
        <?php if ($uri != "s-inscrire"){ ?>
            <button onclick="window.location.href='/s-inscrire';">
                S'inscrire
            </button>
        <?php } ?>
    <?php } ?>

    <!-- inclure la vue -->
    <?php include $this->view;?>

</body>
</html>