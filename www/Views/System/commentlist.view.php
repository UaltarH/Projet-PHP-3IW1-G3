<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="pagetitle">
    <h1>Commentaires</h1>
</div>
<section class="section dashboard">
    <div class="row">

        <div class="col-lg-8">
            <div class="row">

                <div class="col-12">
                    <div class="card recent-sales overflow-auto">
                        <div class="card-body">

                            <h5 class="card-title">Commentaires</h5>

                            <div class="activity">
                                <?php foreach ($allComment as $comment): ?>
                                    <div class="activity-item d-flex">
                                        <div class="activite-label"><?= $comment->getCreationDate() ?></div>
                                        <i class='bi bi-circle-fill activity-badge <?= $comment->isModerated() ? $comment->isAccepted() ? "text-success" : "text-danger" : "text-warning" ?> align-self-start'></i>
                                        <?php if($comment->isModerated()): ?>
                                            <div class="activity-content">
                                                <?= $comment->isAccepted() ? "Accepté" : "Rejeté" ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="activity-content">
                                                En attente
                                            </div>
                                        <?php endif; ?>
                                        <a class="activity-content" href="edit/?id=<?= $comment->getId() ?>"
                                           style="color: black">
                                            <?= $comment->getContent() ?>
                                        </a>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card recent-sales overflow-auto">
                <div class="card-body">

                    <h5 class="card-title">Commentaires <span>| En attente de modération</span></h5>

                    <div class="activity">
                        <?php foreach ($unmoderatedComment as $comment): ?>
                            <div class="activity-item d-flex">
                                <div class="activite-label"><?= $comment->getCreationDate() ?></div>
                                <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                <a class="activity-content" href="edit/?id=<?= $comment->getId() ?>"
                                   style="color: black">
                                    <?= $comment->getContent() ?>
                                </a>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-8">
        <div class="row">

            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Commentaires <span>| Modérés</span></h5>

                        <div class="activity">
                            <?php foreach ($moderatedComment as $comment): ?>
                                <div class="activity-item d-flex">
                                    <div class="activite-label"><?= $comment->getCreationDate() ?></div>
                                    <i class='bi bi-circle-fill activity-badge <?= $comment->isModerated() ? $comment->isAccepted() ? "text-success" : "text-danger" : "text-warning" ?> align-self-start'></i>
                                    <div class="activity-content">
                                        <?= $comment->isAccepted() ? "Accepté" : "Rejeté" ?>
                                    </div>
                                    <div class="activity-content">
                                        <?= $comment->getContent() ?>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</section>