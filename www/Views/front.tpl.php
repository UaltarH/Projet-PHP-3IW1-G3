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

        <!-- inclure la vue -->
        <?php include $this->view; ?>
    </main>
    <?php $this->partial("footer", []) ?>
</body>

</html>