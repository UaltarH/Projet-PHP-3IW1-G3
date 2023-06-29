<?php if(isset($titre, $content)): ?>
    <p>titre de la page : <?= $titre ?>, contenu de la page : <?= $content ?> </p>
<?php endif; ?>

<?php if(isset($error)): ?>
    <p>erreur : <?= $error ?></p>
<?php endif; ?>
