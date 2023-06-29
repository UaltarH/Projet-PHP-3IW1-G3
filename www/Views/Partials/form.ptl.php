<?php if(!empty($errors)): ?>
<?php print_r($errors);?>
<?php endif;?>


<form method="<?= $config["config"]["method"] ?>"
      action="<?= $config["config"]["action"] ?>"
      enctype="<?= $config["config"]["enctype"] ?>"
      id="<?= $config["config"]["id"] ?>"
      class="<?= $config["config"]["class"] ?>">

    <?php foreach ($config["inputs"] as $name=>$configInput): ?>
        <div class="form-input-row">
        <?php if(isset($configInput["label"])): ?>
            <label for="<?=$name?>"><?=$configInput["label"]?> :</label>
        <?php endif; ?>
        <?php if($configInput["type"] != "select" && $configInput["type"] !== "file"): ?>
            <input
                name="<?= $name ?>"
                placeholder="<?= $configInput["placeholder"] ?>"
                class="<?= $configInput["class"] ?>"
                id="<?= $configInput["id"] ?>"
                type="<?= $configInput["type"] ?>"
                <?= $configInput["type"] == "tel" ? 'pattern="'.$configInput["pattern"].'"' : '' ?>
                <?= $configInput["type"] == "tel" ? 'minlength="'.$configInput["min"].'"' : '' ?>
                <?= $configInput["type"] == "tel" ? 'maxlength="'.$configInput["max"].'"' : '' ?>
                <?= isset($configInput["required"]) ? "required":"" ?>
             >
        <?php elseif ($configInput["type"] === "select"): ?>
            <select name="<?= $name ?>" id="<?= $configInput["id"] ?>">
                <?php foreach ($configInput["options"] as $value=>$name): ?>
                    <option value=<?= $value?>> <?= $name ?> </option>
                <?php endforeach;?>
            </select>
        <?php elseif ($configInput["type"] === "file"): ?>
            <label for="<?= $name ?>"><?= $configInput["label"] ?></label>
            <input
                    name="<?= $name ?>"
                    class="<?= $configInput["class"] ?>"
                    id="<?= $configInput["id"] ?>"
                    type="<?= $configInput["type"] ?>"
                    <?= isset($configInput["required"]) ? "required":"" ?>
                    <?= $configInput["multiple"]?"multiple":"" ?>
             >
        <?php endif;?>
        </div>

    <?php endforeach;?>
    <div class="form-input-row submit">
        <input type="submit" name="<?= $config["config"]["submitName"] ?>" value="<?= $config["config"]["submitLabel"] ?>">
        <input type="reset" value="<?= $config["config"]["reset"] ?>">
    </div>
</form>