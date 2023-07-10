<?php
$hasNonEmptySubArray = false;

foreach ($articleWhere as $subArray) {
    if (!empty($subArray)) {
        $hasNonEmptySubArray = true;
        break;
    }
}

if ($hasNonEmptySubArray): ?>
<h2>Articles</h2>
<div class="container mt-5 px-2">
    <div class="table-responsive">
        <table class="table table-responsive table-borderless">
            <thead>
            <tr class="bg-light">
                <th scope="col" width="10%">Date</th>
                <th scope="col" width="33%">Titre</th>
                <th scope="col" width="33%">Contenu</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($articleWhere as $article): ?>
                <tr>
                    <td><?= $article[0]->getCreatedDate() ?></td>
                    <td><?= $article[0]->getTitle() ?></td>
                    <td><?= $article[0]->getContent() ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php
$hasNonEmptySubArray = false;

foreach ($categorieArticlesWhere as $subArray) {
    if (!empty($subArray)) {
        $hasNonEmptySubArray = true;
        break;
    }
}

if ($hasNonEmptySubArray): ?>
    <h2>Catégories Articles</h2>
    <div class="container mt-5 px-2">
        <div class="table-responsive">
            <table class="table table-responsive table-borderless">
                <thead>
                <tr class="bg-light">
                    <th scope="col" width="33%">Nom de la catégorie</th>
                    <th scope="col" width="33%">Description</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categorieArticlesWhere as $article_categorie):?>
                    <tr>
                        <td><?= $article_categorie[0]->getCategoryName() ?></td>
                        <td><?= $article_categorie[0]->getDescription() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php
$hasNonEmptySubArray = false;

foreach ($jeuxWhere as $subArray) {
    if (!empty($subArray)) {
        $hasNonEmptySubArray = true;
        break;
    }
}

if ($hasNonEmptySubArray): ?>
    <h2>Jeux</h2>
    <div class="container mt-5 px-2">
        <div class="table-responsive">
            <table class="table table-responsive table-borderless">
                <thead>
                <tr class="bg-light">
                    <th scope="col" width="33%">Titre</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($jeuxWhere as $jeu): ?>
                    <tr>
                        <td><?= $jeu[0]->getTitle() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php
$hasNonEmptySubArray = false;

foreach ($categorieJeuxWhere as $subArray) {
    if (!empty($subArray)) {
        $hasNonEmptySubArray = true;
        break;
    }
}

if ($hasNonEmptySubArray): ?>
    <h2>Catégories Jeux</h2>
    <div class="container mt-5 px-2">
        <div class="table-responsive">
            <table class="table table-responsive table-borderless">
                <thead>
                <tr class="bg-light">
                    <th scope="col" width="33%">Nom de la catégorie</th>
                    <th scope="col" width="33%">Description</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categorieJeuxWhere as $categorie_jeu): ?>
                    <tr>
                        <td><?= $categorie_jeu[0]->getCategoryName() ?></td>
                        <td><?= $categorie_jeu[0]->getDescription() ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
