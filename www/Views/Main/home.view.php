<?php
if (isset($pseudo, $roleId)): ?>
    <h2>Bienvenue <span style="color: var(--secondary)"><?= $pseudo; ?></span> vous êtes connecté !</h2>

<?php endif; ?>
<div style="margin: auto; width: 50vw;" class="mt-5">
    <h3>Derniers articles</h3>
    <?php foreach ($newArticles as $article): ?>
        <div class="mt-3 border p-2" style="width: 50vw; display: flex; justify-content: space-between">
            <h5>
                <a href='/articles/article?id=<?= $article->getId() ?>'
                   style="text-decoration: none; color: black;">
                    <?= $article->getTitle() ?>
                </a>
            </h5>
            <span><?= $article->getCreatedDate() ?></span>
        </div>
    <?php endforeach; ?>
</div>
