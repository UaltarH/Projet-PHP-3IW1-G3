<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?=\App\Core\Config::getConfig()['website']['name']?></title>
    <meta name="description" content="<?=\App\Core\Config::getConfig()['website']['description']?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/style.css">
    <script src="/assets/js/main.js"></script>
    <script type="module" src="/js-install/index.js" defer></script>
</head>

<body>
    <div class="content-wrapper">
        <main>
            <div class="response-message"></div>
            <?php
            if(\App\Core\Config::getConfig()['installation']['done'])
                $this->partial("header", [])
            ?>

            <div class="container">
                <?php include $this->view; ?>
            </div>
        </main>
    </div>
    <?php
    if(\App\Core\Config::getConfig()['installation']['done'])
        $this->partial("footer", [])
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>