<div class="game">
    <h4><?= $title ?></h4>
    <?php if (isset($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <div class="mt-5 border p-2" style="min-width: 50vw">
                <h5>
                    <a href='/articles/article?id=<?= $article->getTitle() ?>'
                       style="text-decoration: none; color: black;">
                        <?= $article->getTitle() ?>
                    </a>
                </h5>
                <div style="display: flex; flex-direction: row; justify-content: space-between">
                <p><?= $article->getContent() ?></p>
                <?php foreach ($games as $game): ?>
                    <?php if ($game["articleId"] == $article->getId()): ?>
                        <p><?= $game["game"]->getTitle() ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
                <div class="toggle-table-btn" onclick="toggleTable(this)" style="cursor: pointer">Afficher les
                    commentaires
                </div>
                <table class="table table-responsive table-borderless" style="display: none;">
                    <thead>
                    <tr class="bg-light border-1">
                        <th scope="col" width="10%">Date</th>
                        <th scope="col" width="33%">Commentaire</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($commentsByArticles as $commentsByArticle): ?>
                        <?php if ($commentsByArticle["articleId"] == $article->getId()): ?>
                            <?php foreach ($commentsByArticle["comment"] as $comment): ?>
                                <tr>
                                    <td><?= $comment->getCreationDate(); ?></td>
                                    <td><?= $comment->getContent(); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    function toggleTable(btn) {
        var table = btn.nextElementSibling;
        if (table.style.display === "none") {
            table.style.display = "table";
            btn.textContent = "Masquer les commentaires";
        } else {
            table.style.display = "none";
            btn.textContent = "Afficher les commentaires";
        }
    }
</script>