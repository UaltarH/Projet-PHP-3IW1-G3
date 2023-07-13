<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="pagetitle">
    <h1>Commentaires</h1>
</div>
<section class="section dashboard">
    <div class="row">


        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-3 border-right">
                </div>
                <div class="col-md-6 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        Commentaire : <?= $commentInfos->getId() ?>
                    </div>
                    <div class="p-3 py-5">
                        <div class="row mt-2">
                            <form
                                    method="<?= $moderateCommentForm["config"]["method"] ?>"
                                    action="<?= $moderateCommentForm["config"]["action"] ?>"
                                    enctype="<?= $moderateCommentForm["config"]["enctype"] ?>"
                                    id="<?= $moderateCommentForm["config"]["id"] ?>"
                                    class="<?= $moderateCommentForm["config"]["class"] ?>"
                            >
                                <?php foreach ($moderateCommentForm["inputs"] as $inputName => $inputConfig): ?>
                                    <?php if (!in_array($inputConfig['label'], array("Role", "Email", "Mot de passe", "Confirmation"))): ?>
                                        <div>
                                            <label class="labels"><?= $inputConfig["label"] ?></label>
                                            <input
                                                    id="<?= $inputConfig["id"] ?>"
                                                    type="<?= $inputConfig["type"] ?>"
                                                    class="<?= $inputConfig["class"] ?>"
                                                <?= $inputConfig["readonly"] ? "readonly" : "" ?>
                                                    value="<?= $inputConfig["value"] ?>"
                                                    name="<?= $inputConfig["name"] ?>"
                                            >
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <div style="display: none">
                                    <label></label>
                                    <input name="id" value="<?= $commentInfos->getId() ?>">
                                </div>
                                <div class="mt-5 text-center">
                                    <input
                                            class="btn" style="background-color: var(--secondary); color: white"
                                            type="submit"
                                            value="<?= $moderateCommentForm["config"]["submitLabel"] ?>"
                                    >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</section>