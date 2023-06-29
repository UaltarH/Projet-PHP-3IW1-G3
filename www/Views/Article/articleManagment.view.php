<p>Article management page :</p>


<!-- <p>Information sur l'ajout d'un article:</p>
<?php if(!empty($infoCreationArticle)): ?>
<?php print_r($infoCreationArticle);?>
<?php endif;?> -->

<?php if(isset($formCategoryArticle, $formCategoryArticleErrors)): ?>
<p>form select categorie article :</p>
<?php $this->partial("form", $formCategoryArticle, $formCategoryArticleErrors) ?>
<?php endif;?>

<?php if(isset($FormCreateArticleGame, $FormCreateArticleGameErrors)): ?>
<p>form create  article game:</p>
<?php $this->partial("form", $FormCreateArticleGame, $FormCreateArticleGameErrors) ?>
<?php endif;?>

<?php if(isset($FormCreateArticleAboutGame, $FormCreateArticleAboutGameErrors)): ?>
<p>form create  article about game:</p>
<?php $this->partial("form", $FormCreateArticleAboutGame, $FormCreateArticleAboutGameErrors) ?>
<?php endif;?>

<?php if(isset($successMessage)): ?>
<?php var_dump($successMessage);?>
<?php endif;?>

<?php if(isset($errorMessage)): ?>
<?php var_dump($errorMessage);?>
<?php endif;?>