<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
        </div>
        <div class="col-md-6 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5 bg-secondary">
                <span class="font-weight-bold"><?= $informationsUser["pseudo"] ?></span>
                <span class="text-black-50" style="text-transform: capitalize"><?= $informationsUser["roleName"] ?></span>
            </div>
            <div class="p-3 py-5 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Profil</h4>
                </div>
                <div class="row mt-2">
                    <form method="<?= $editUserForm["config"]["method"] ?>" action="<?= $editUserForm["config"]["action"] ?>" enctype="<?= $editUserForm["config"]["enctype"] ?>" id="<?= $editUserForm["config"]["id"] ?>" class="<?= $editUserForm["config"]["class"] ?>">
                        <?php foreach ($editUserForm["inputs"] as $inputName => $inputConfig) : ?>
                            <?php if (!in_array($inputConfig['label'], array("Role", "Email", "Mot de passe", "Confirmation"))) : ?>
                                <div class="col-md-12">
                                    <label class="labels"><?= $inputConfig["label"] ?></label>
                                    <input<?php
                                            $array = explode('-', $inputConfig['id']);
                                            $value = $informationsUser[end($array)];
                                            ?> id="<?= $inputConfig["id"] ?>" name="<?= $inputConfig["name"] ?>" type="<?= $inputConfig["type"] ?>" class="form-control" placeholder="<?= $value ?>" pattern="<?= $inputConfig["pattern"] ?>" <?= isset($configInput["required"]) ? "required" : "" ?>>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="mt-5 text-center">
                            <input class="btn bg-secondary profile-button" type="submit">
                        </div>
                    </form>
                    <div class="game mt-5">
                        <a href="/reset-password">Réinitialiser mon mot de passe</a>
                        <h4 style="color: red"><?= $_GET["message"] ?? "" ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>