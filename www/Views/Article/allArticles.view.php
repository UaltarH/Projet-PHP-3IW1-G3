<div class="game">
    <h4><?= $title ?></h4>
    <?php if (isset($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <div class="mt-5 border p-2" style="min-width: 50vw">
                <h5>
                    <a href='/articles/article?id=<?= $article->getId() ?>'
                       style="text-decoration: none; color: black;">
                        <?= $article->getTitle() ?>
                    </a>
                </h5>
                <div style="display: flex; flex-direction: row; justify-content: space-between">
                    <?php foreach ($games as $game): ?>
                        <?php if ($game["articleId"] == $article->getId()): ?>
                            <p><?= $game["game"]->getTitle() ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>