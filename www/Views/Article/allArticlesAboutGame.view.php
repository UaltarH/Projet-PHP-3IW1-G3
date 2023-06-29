<?php if(isset($articles)): ?>
    <p>data: </p>
    <pre>
        <?php var_dump($articles); ?>
    </pre>
<?php endif; ?>

<?php if(isset($error)): ?>
    <p>erreur : <?= $error ?></p>
<?php endif; ?>