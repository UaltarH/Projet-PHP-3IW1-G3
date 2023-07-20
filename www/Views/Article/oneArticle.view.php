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
        <div class="mt-5 border p-2 col-8">
            <table class="table table-responsive table-borderless">
                <thead>
                <tr class="bg-light border-1">
                    <th scope="col" width="10%">Date</th>
                    <th scope="col" width="33%">Commentaire</th>
                    <th scope="col" width="33%">Auteur</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?= $comment["comment"]->getCreationDate(); ?></td>
                        <td><?= $comment["comment"]->getContent(); ?></td>
                        <td><?= $comment["user"]; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION["token"])): ?>
        <div class="col-8 card">
            <div class="p-3 ml-2">
                <h6>Ajouter un commentaire</h6>
                <form method="post" action="/post-comment">
                    <div class="comment-area">
                        <input type="text" class="form-control" name="comment" placeholder="Exprimez-vous!" required>
                        <input style="display: none" name="articleId" value="<?= $article->getId() ?>">
                    </div>
                    <div class="p-2">
                        <input type="submit" class="btn bg-secondary send btn-sm" value="Commenter"
                               style="color: white">
                    </div>
                    <div class="alert alert-info">
                        <?= $_GET["message"] ?? "" ?>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
