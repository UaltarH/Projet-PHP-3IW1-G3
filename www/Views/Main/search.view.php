<?php
$hasNonEmptySubArray = false;

foreach ($articleWhere as $subArray) {
    if (!empty($subArray)) {
        $hasNonEmptySubArray = true;
        break;
    }
}

if ($hasNonEmptySubArray): ?>
    <h4>Articles</h4>
    <div class="container mt-2 px-2">
        <div class="table-responsive">
            <table class="table table-responsive table-borderless">
                <thead>
                <tr class="bg-light border-1">
                    <th scope="col" width="10%">Date</th>
                    <th scope="col" width="33%">Titre</th>
                    <th scope="col" width="33%">Contenu</th>
                </tr>
                </thead>
                <tbody>
                <?php $counter = 0; ?>
                <?php foreach ($articleWhere as $articles): ?>
                        <tr style="cursor: pointer" onclick="rowClicked('article/<?= $articles->getTitle() ?>')" class="border-1">
                            <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $articles->getCreatedDate() ?></td>
                            <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $articles->getTitle() ?></td>
                            <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $articles->getContent() ?></td>
                        </tr>
                        <?php $counter++; ?>
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
    <h4>Catégories Articles</h4>
    <div class="container mt-2 px-2">
        <div class="table-responsive">
            <table class="table table-responsive table-borderless">
                <thead>
                <tr class="bg-light border-1">
                    <th scope="col" width="33%">Nom de la catégorie</th>
                    <th scope="col" width="33%">Description</th>
                </tr>
                </thead>
                <tbody>
                <?php $counter = 0; ?>
                <?php foreach ($categorieArticlesWhere as $article_categories): ?>
                        <tr style="cursor: pointer" onclick="rowClicked('categorie_article/<?= $article_categories->getCategoryName() ?>')" class="border-1">
                            <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $article_categories->getCategoryName() ?></td>
                            <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $article_categories->getDescription() ?></td>
                        </tr>
                        <?php $counter++; ?>
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
    <h4>Jeux</h4>
    <div class="container mt-2 px-2">
        <div class="table-responsive">
            <table class="table table-responsive table-borderless">
                <thead>
                <tr class="bg-light border-1">
                    <th scope="col" width="33%">Titre</th>
                </tr>
                </thead>
                <tbody>
                <?php $counter = 0; ?>
                <?php foreach ($jeuxWhere as $jeux): ?>
                        <tr style="cursor: pointer" onclick="rowClicked('jeux/<?= $jeux->getTitle() ?>')" class="border-1">
                            <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $jeux->getTitle() ?></td>
                        </tr>
                        <?php $counter++; ?>
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
    <h4>Catégories Jeux</h4>
    <div class="container mt-2 px-2">
        <div class="table-responsive">
            <table class="table table-responsive table-borderless">
                <thead>
                <tr class="bg-light border-1">
                    <th scope="col" width="33%">Nom de la catégorie</th>
                    <th scope="col" width="33%">Description</th>
                </tr>
                </thead>
                <tbody>
                <?php $counter = 0; ?>
                <?php foreach ($categorieJeuxWhere as $categorie_jeux): ?>
                        <tr style="cursor: pointer" onclick="rowClicked('categorie_jeu/<?= $categorie_jeux->getCategoryName() ?>')" class="border-1">
                            <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $categorie_jeux->getCategoryName() ?></td>
                            <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $categorie_jeux->getDescription() ?></td>
                        </tr>
                        <?php $counter++; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<script>
    function rowClicked(row) {
        window.location.href = row;
    }
</script>
