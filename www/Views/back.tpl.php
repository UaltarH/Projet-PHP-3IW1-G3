<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Super site</title>
    <meta name="description" content="ceci est un super site">
</head>
<body>
    <h1>Template Back</h1>

        <!-- create menu with variable come from View.php -->
        <ul>
            <?php foreach ($menuOpt as $label => $link): ?>
                <?php if (is_array($link)): ?>
                    <li>
                        <?php echo $label; ?>
                        <ul>
                            <?php foreach ($link["categories"] as $category => $categoryData): ?>
                                <li><?php echo $category; ?>
                                    <ul>
                                        <?php foreach ($categoryData["links"] as $articleTitle => $hrefArticle): ?>
                                            <li><a href="<?php echo $hrefArticle; ?>"><?php echo $articleTitle; ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo $link; ?>"><?php echo $label; ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    
    <!-- inclure la vue -->
    <?php include $this->view;?>

</body>
</html>