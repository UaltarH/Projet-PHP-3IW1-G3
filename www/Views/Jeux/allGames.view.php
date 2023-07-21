<div class="game">
    <h4>Jeux</h4>
    <div class="container mt-2 px-2">
        <div class="table-responsive">
            <table class="table table-responsive table-borderless">
                <thead>
                <tr class="bg-light border-1">
                    <th scope="col" width="33%">Titre</th>
                    <th scope="col" width="33%">Catégorie</th>
                </tr>
                </thead>
                <tbody>
                <?php $counter = 0; ?>
                <?php foreach ($jeux as $jeu): ?>
                    <tr style="cursor: pointer" onclick="rowClicked('jeux/jeu?id=<?= $jeu['id'] ?>')"
                        class="border-1">
                        <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $jeu['title'] ?></td>
                        <td class="<?php echo ($counter % 2 == 0) ? 'bg-light' : 'bs-gray'; ?>"><?= $jeu['categorie'] ?></td>
                    </tr>
                    <?php $counter++; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>