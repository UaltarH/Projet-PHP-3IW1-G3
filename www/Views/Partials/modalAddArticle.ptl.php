<div class="modal fade" id="multi-step-modal" tabindex="-1" role="dialog" aria-labelledby="multi-step-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="multi-step-modal-label">Formulaire à plusieurs étapes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="multi-step-form" action="#" method="POST">
                    <!-- Étape 1 -->
                    <section id="step1" class="form-step">
                        <h2>Étape 1 choix du type d'article:</h2>
                        <div class="form-group">
                            <select class="form-control" id="field1" name="categoriesArticle" required>
                            <?php foreach ($config["categoriesArticle"] as $value=>$name): ?>
                                <option value=<?= $value?>> <?= $name ?> </option>
                            <?php endforeach;?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary next-step">Suivant</button>
                    </section>

                    <!-- Étape 2 -->
                    <section id="step2" class="form-step">
                        <h2>Étape 2 créer l'article</h2>
                        <!-- Champs de la deuxième étape - quand on a selectionnez article jeu -->
                        <div id="step2-option1" style="display: none;">
                            <div class="form-group">
                                <label for="createArticleGame-form-titleGame">Votre titre de jeu et de l'article</label>
                                <input type="text" class="form-control" id="" name="createArticleGame-form-titleGame" required>

                                <label for="createArticleGame-form-categoryGame">Votre contenu de l'article</label>
                                <select class="form-control" id="" name="createArticleGame-form-categoryGame" required>
                                    <?php foreach ($config["categoriesGame"] as $value=>$name): ?>
                                        <option value=<?= $value?>> <?= $name ?> </option>
                                    <?php endforeach;?>
                                </select>

                                <label for="createArticleGame-form-imageJeu">Votre image du jeu</label>
                                <input name="createArticleGame-form-imageJeu" class="form-control" id="" type="file"required>

                                <label for="createArticleGame-form-contentGame">Votre contenu d'article</label>
                                <input type="text" class="form-control" id="" name="createArticleGame-form-contentGame" required>
                            </div>
                        </div>
                        <!-- Champs de la deuxième étape - quand on a selectionnez truc et astuce -->
                        <div id="step2-option2" style="display: none;">
                            <div class="form-group">
                                <label for="createArticleAboutGame-form-game">Votre jeu</label>
                                <select class="form-control" id="" name="createArticleGame-form-game" required>
                                    <?php foreach ($config["games"] as $value=>$name): ?>
                                        <option value=<?= $value?>> <?= $name ?> </option>
                                    <?php endforeach;?>
                                </select>

                                <label for="createArticleAboutGame-form-titleArticle">Votre titre d'article</label>
                                <input type="text" class="form-control" id="" name="createArticleAboutGame-form-titleArticle" required>                                

                                <label for="createArticleAboutGame-form-contentArticle">Votre contenu d'article</label>
                                <input type="text" class="form-control" id="" name="createArticleAboutGame-form-contentArticle" required>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary prev-step">Précédent</button>
                        <button type="submit" class="btn btn-primary">Créez l'article</button>
                    </section>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <script>
    $(document).ready(function() {
      // Afficher la première étape
      showStep(1);

      // Gestion des boutons Suivant/Précédent
      $(".next-step").click(function() {
        var currentStep = $(this).closest(".form-step");
        var nextStep = currentStep.next(".form-step");
        showStep(nextStep);
      });

      $(".prev-step").click(function() {
        var currentStep = $(this).closest(".form-step");
        var prevStep = currentStep.prev(".form-step");
        showStep(prevStep);
      });

      // Fonction pour afficher une étape spécifique
      function showStep(step) {
        $(".form-step").hide();
        $(step).show();
      }

      // Gestion de la logique conditionnelle
      $("#field1").change(function() {
        var selectedOption = $(this).val();
        if (selectedOption === "option1") {
          $("#step2-option1").show();
          $("#step2-option2").hide();
        } else if (selectedOption === "option2") {
          $("#step2-option1").hide();
          $("#step2-option2").show();
        } else {
          $("#step2-option1").hide();
          $("#step2-option2").hide();
        }
      });
    });
  </script> -->
