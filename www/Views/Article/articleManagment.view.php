<h1>Article management page :</h1>
<!-- modal modif -->
<?php include "Views/Partials/editArticleModal.ptl.php" ?>

<!-- datatable -->
<nav>
    <ul>
        <!-- /sys/user/list?action=faker -->
        <li><a href="#">Generate</a></li>
    </ul>
</nav>
<table id="articleTable" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>Article title</th>
            <th>Created date</th>
            <th>Update date</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

<!-- fomulaires pour la creation d'article -->
<?php if(isset($formCategoryArticle, $formCategoryArticleErrors)): ?>
<p>form select categorie article :</p>
<?php $this->partial("form", $formCategoryArticle, $formCategoryArticleErrors) ?>
<?php endif;?>

<?php if(isset($formCreateArticleGame, $formCreateArticleGameErrors)): ?>
<p>Form create article game:</p>
<?php $this->partial("form", $formCreateArticleGame, $formCreateArticleGameErrors) ?>
<?php endif;?>

<?php if(isset($formCreateArticleAboutGame, $formCreateArticleAboutGameErrors)): ?>
<p>form create  article about game:</p>
<?php $this->partial("form", $formCreateArticleAboutGame, $formCreateArticleAboutGameErrors) ?>
<?php endif;?>



<!-- btn pour ouvrir la modal d'ajout d'article  -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#multi-step-modal">Ouvrir le formulaire</button>

<!-- modal d'ajout d'article -->
<?php $this->partial("modalAddArticle", $optionsForms) ?>


<!-- script js pour la datatable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script>

    $(document).ready(function() {
        let table = $('#articleTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'get',
            'ajax': {
                'url':'/api/article/list'
            },
            columnDefs: [
                {
                    target: 0,
                    visible: false,
                    searchable: false,
                }
            ],
            order: [[1, 'asc']],
            'columns': [
                { data: 'id' },
                { data: 'title' },
                { data: 'created_date' },
                { data: 'updated_date' },
                { data: 'category_name' },
                { data: 'action'}
            ],
            'drawCallback': function() {
                let arr_edit = document.getElementsByClassName('row-edit-button');
                for(let elt of arr_edit) {
                    elt.addEventListener('click', function(e) {
                        e.preventDefault();
                        let uriParam = e.target.href.split('?')[1];
                        let params = uriParam.split('&').map(function(i) {
                            return i.split('=');
                        }).reduce(function(memo, i) {
                            memo[i[0]] = i[1] == +i[1] ? parseFloat(i[1],10) : decodeURIComponent(i[1]);
                            return memo;
                        }, {});
                        let data = table.row($(this).parents('tr')).data();
                        let modalContainer = $('#modal-container');
                        modalContainer.addClass("active");

                        let form = $('#edit-article-form');
                        let action = form.attr('action');
                        let searchParams = new URLSearchParams(action.split('?')[1]);
                        let baseURL = action.split('?')[0];
                        searchParams.delete('id');
                        let searchParamsStr = searchParams.toString().concat("&id="+data.id);
                        action = baseURL.concat("?"+searchParamsStr);
                        form.attr('action', action);

                        $('#closeModal').on('click', function () {
                            modalContainer.removeClass("active");
                        });
                    });
                }
            }
        });


        
        // gerer le formulaire d'ajout d'article:

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
</script>
