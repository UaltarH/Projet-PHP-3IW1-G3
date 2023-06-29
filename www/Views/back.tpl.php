<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Super site</title>
    <meta name="description" content="ceci est un super site">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="../style.css"/>
</head>
<body>
<?php $this->partial("header", []) ?>
<div id="mainContainer">
    <h1>Template Back</h1>
    
    <!-- inclure la vue -->
    <?php include $this->view; ?>
</div>
</body>
</html>