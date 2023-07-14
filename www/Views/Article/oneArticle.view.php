<div class="article">
    <h2><?= $article->getTitle() ?></h2>
    <br>
    Catégorie : <?= $category->getCategoryName() ?>
    <br>
    <br>
    <br>
    <h5><?= isset($game) ? "Jeu : " . $game->getTitle() : "" ?></h5>
    <p><?= $article->getContent() ?></p>
    <?php if (!empty($comments)): ?>
    <div class="mt-5 border p-2" style="min-width: 100%">
        <table class="table table-responsive table-borderless">
            <thead>
            <tr class="bg-light border-1">
                <th scope="col" width="10%">Date</th>
                <th scope="col" width="33%">Commentaire</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?= $comment->getCreationDate(); ?></td>
                    <td><?= $comment->getContent(); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
