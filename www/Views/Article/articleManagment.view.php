<h1>Article management page :</h1>

<!-- datatable -->

<table id="articleTable" class="display">
    <thead>
        <tr>
            <th>ID</th>
            <th>Article title</th>
            <th>Created date</th>
            <th>Update date</th>
            <th>Category</th>
            <th>Category game</th>
            <th>Game</th>
            <th>Content</th>
            <th>Action</th>
        </tr>
    </thead>
</table>



<!-- btn pour ouvrir la modal d'ajout d'article  -->
<button type="button" class="btn btn-primary"  id="open-modalCreateGameArticle-btn">Créer un article</button>

<!-- modal d'ajout d'article -->
<?php $this->partial("modalAddArticle", $optionsForms) ?>

<!-- modal modif -->
<?php $this->partial("modalEditArticle") ?>




<!-- script js pour la datatable -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script src="/Assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>

//gestions des boutons de modal
    // Récupérer la référence à la modal create article
    var modalCreateArticleElement = document.getElementById('multi-step-modal');
    var modalCreateArticle = new bootstrap.Modal(modalCreateArticleElement);
    $("#open-modalCreateGameArticle-btn").on('click', function(){
        openModalCreateArticle();
    }); 
    $("#close-modalCreateGameArticle-btn").on('click', function(){
        closeModalCreateArticle();
    }); 
    function openModalCreateArticle(){
        modalCreateArticle.show();
    }
    function closeModalCreateArticle(){
        modalCreateArticle.hide();
    }

    //modal edit article
    var modalEdiotArticleElement = document.getElementById('editArticle-modal');
    var modalEditArticle = new bootstrap.Modal(modalEdiotArticleElement);
    $("#close-modalEditArticle-btn").on('click', function(){
        closeModalEditArticle();
    }); 

    function openModalEditArticle(){
        modalEditArticle.show();
    }
    function closeModalEditArticle(){
        contentArticle = "";
        document.getElementById("editArticle-form-title").value = "";
        modalEditArticle.hide();
    }
//
    
// Formulaire à plusieurs étapes
    // Récupérer toutes les sections du formulaire
    const formSteps = document.querySelectorAll('.form-step');

    // Récupérer les boutons de navigation
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');

    // Fonction pour afficher une étape spécifique
    function showStep(stepIndex) {
    formSteps.forEach((step, index) => {
        if (index === stepIndex) {
        step.style.display = 'block';
        } else {
        step.style.display = 'none';
        }
    });
    }

    // Fonction pour passer à l'étape suivante
    function goToNextStep() {
    const currentStep = this.closest('.form-step');
    const nextStep = currentStep.nextElementSibling;
    if (nextStep) {
        currentStep.style.display = 'none';
        nextStep.style.display = 'block';
    }
    }

    // Fonction pour revenir à l'étape précédente
    function goToPrevStep() {
    const currentStep = this.closest('.form-step');
    const prevStep = currentStep.previousElementSibling;
    if (prevStep) {
        currentStep.style.display = 'none';
        prevStep.style.display = 'block';
    }
    }

    // Attacher les événements aux boutons de navigation
    nextButtons.forEach(button => {
        button.addEventListener('click', goToNextStep);
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', goToPrevStep);
    });

    // Afficher la première étape au chargement de la page
    showStep(0);

    // gestion de l'etape 2 dynamique du fomulaire
    // Récupérer les éléments liés à l'étape 1
    const selectField = document.getElementById('field1');

    // Récupérer les éléments liés à l'étape 2
    const step2Option1 = document.getElementById('step2-option1');
    const step2Option2 = document.getElementById('step2-option2');

    // Fonction pour afficher les champs correspondants à l'option choisie
    function showFieldsForOption(optionValue) {
        if (optionValue === 'Jeux') {
            step2Option1.style.display = 'block';
            step2Option2.style.display = 'none';
            //rendre les input required
            document.getElementById("createArticleGame-form-titleGame").required = true;
            document.getElementById("createArticleGame-form-categoryGame").required = true;
            document.getElementById("createArticleGame-form-imageJeu").required = true;
            document.getElementById("createArticleAboutGame-form-game").required = false;
            document.getElementById("createArticleAboutGame-form-titleArticle").required = false;

        } else if (optionValue === 'Trucs et astuces') {
            step2Option1.style.display = 'none';
            step2Option2.style.display = 'block';
            //rendre les input required
            document.getElementById("createArticleAboutGame-form-game").required = true;
            document.getElementById("createArticleAboutGame-form-titleArticle").required = true;
            document.getElementById("createArticleGame-form-titleGame").required = false;
            document.getElementById("createArticleGame-form-categoryGame").required = false;
            document.getElementById("createArticleGame-form-imageJeu").required = false;

        } else {
            step2Option1.style.display = 'none';
            step2Option2.style.display = 'none';
        }
    }

    // Événement de changement de sélection dans l'étape 1
    selectField.addEventListener('change', function() {
        const selectedOptionText = this.options[this.selectedIndex].text;
        showFieldsForOption(selectedOptionText);
    });

    // Afficher les champs correspondant à l'option sélectionnée initialement
    showFieldsForOption(selectField.value);
//


//gestion de la datatable 
    var selectedRow;
    $(document).ready(function() {

        $('input[required]').siblings("");
        let table = $('#articleTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'get',
            'ajax': {
                'url':'/sys/article/datatable'
            },
            columnDefs: [
                {
                    target: 0,
                    visible: false,
                    searchable: false,
                },
                {
                    target: 7,
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
                { data: 'category_game_name' },
                { data: 'title_game' },
                { data: 'content'},
                { data: 'action'}
            ],
            'drawCallback': function() {
                let allCrudButton = $('.crud-button');
                allCrudButton.on('click', function(e){
                    selectedRow = table.row($(e.target).parents('tr')).data();
                    if($(e.target).hasClass('row-edit-button')) {
                        //recuperer le contenu de l'article selectionner pour l'utilisateur dans l'editeur
                        contentArticle = selectedRow.content;
                        //afficher l'ancien contenu de l'article dans l'input text de la modal edit
                        document.getElementById("editArticle-form-title").value = selectedRow.title;

                        //recuperer tous les versions de l'article (memento) avec un call ajax
                        getAllArticleMemento(selectedRow.id)
                        .then(function(articleMemento) {
                            if (articleMemento.length > 0) {
                                //creer la liste avec les versions de l'article si le resultat est non vide
                                // Récupérer la référence de la div avec l'ID "memento"
                                let ulVersions = document.getElementById("ul-article-version");
                                let htmlContent = ` 
                                <li class="nav-item dropdown" style="list-style-type: none;">
                                    <a class="nav-link dropdown-toggle"role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Versions de l'article 
                                    </a>
                                    <ul class="dropdown-menu" style="width: 200px;"> `;

                                htmlContent += articleMemento.map(version => `
                                <li>
                                    <a style="display: flex; justify-content: space-between" class="dropdown-item" onclick="changeContentEditor('${encodeURIComponent(version.content)}')">
                                    ${version.title}
                                    <p>${version.created_date}</p>
                                    </a>
                                </li>
                                `).join("");

                                htmlContent += `<li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" onclick="changeContentEditor('${encodeURIComponent(selectedRow.content)}')">Version acctuel</a>
                                                </li>
                                            </ul>
                                        </li>`;

                                // Ajouter le contenu HTML à la div
                                ulVersions.innerHTML = htmlContent;
                            }
                        })
                        .catch(function(error) {
                            console.error(`Error: ${JSON.stringify(error)}`);
                        });
                        
                        //ouvrir la modal pour l'edition de l'article
                        openModalEditArticle();
                    }
                    else if ($(e.target).hasClass('row-delete-button')) {
                        //appel une fonction qui va faire un appel ajax pour supprimer l'article
                        deleteArticle(e, table);
                    }
                });

                //declanche les appels ajax quand on submit un des formulaires
                $('input[name="submitEditArticle"]').on('click', function(e) { editArticle(e, table); });
                $('input[name="submitCreateArticle"]').on('click', function(e) { createArticle(e, table); });
            }
        });            
    });
//


//functions to call with ajax

    function createArticle(e, table){
        e.preventDefault();
        // checks validity of form
        if (! $('#multi-step-form-create-article')[0].checkValidity()) {
            alert("Veuillez renseigner tous les champs du formulaire");
            //$('#multi-step-form-create-article')[0].reportValidity();
            //TODO : gerer si le formulaire contiens bien tous les elements requis si non mettre un message pour informer l'utilisateur
            // de plus on a une erreur dans la console : An invalid form control with name='categoriesArticle' is not focusable.
            // ca veut dire quil ne peut pas ajouter "veuillez renseignez ce champ" car il n'est pas focusable (pas visible vue qu'on est dans un form a plusieurs etapes)
            return false;
        }

        if(htmlContent == ""){
            //todo : mettre un vrai message d'erreur
            alert("Veuillez renseigner le contenu de l'article");
            return false;
        }

        let data = new FormData();
        let createArticleForm = document.getElementById('multi-step-form-create-article');
        let elements = createArticleForm.elements;

        let selectElement = document.getElementById('field1');
        let selectedOption = selectElement.selectedOptions[0];
        let selectedOptionName = selectedOption.text;

        let urlRes;
        let partName;
        if(selectedOptionName == "Jeux"){
            urlRes = "/sys/article/create-article-game";
            partName = "createArticleGame";
        } 
        else{
            urlRes = "/sys/article/create-article-about-game";
            partName = "createArticleAboutGame";            
        }

        for(let elt of elements) {
            if(!!elt.value && elt.type !== "submit" && elt.type !== "reset") {
                if(elt.name.includes(partName)){
                    if (elt.type === "file") {
                        data.append(elt.name, elt.files[0]);
                    } else {
                        data.append(elt.name, elt.value);
                    }
                }
            }
        }
        data.append("content", htmlContent);

        $.ajax({
            type: "POST",
            url: urlRes,
            data: data,
            dataType: "json", // type de retour attendu
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // type de données envoyées
            context: $('.response-message'),
            processData: false, // Désactiver le traitement automatique des données
            contentType: false,
            success: function (data) {            
                closeModalCreateArticle();
                // Afficher la première étape du formaulaire d'ajout d'article
                showStep(0);
                // reset content of the editor and update message of the editor
                htmlContent = "";
                document.getElementById('addArticleContent-info').textContent = "Votre article ne contient pas de contenu.";

                table.ajax.reload();
            },
            error: function (xhr, resp, error) {
                //TODO : afficher un message d'erreur dans une alert bootstrap
                console.error(`Error : ${JSON.stringify(error)}`);
            },
            complete: function (xhr, status) {
                showResponseMessage(status, "Add");
                $('#multi-step-form-create-article').trigger('reset');
            }
        });
    }

    function editArticle(e, table){        
        e.preventDefault();

        // checks validity of form
        if (! $('#form-edit-article')[0].checkValidity()) {
            $('#form-edit-article')[0].reportValidity();
            return false;
        }

        let data = {};
        let editUserForm = document.getElementById('form-edit-article');
        let elements = editUserForm.elements;
        data["id"] = selectedRow.id;
        data["content"] = contentArticle;
        data["title_game"] = selectedRow.title_game;
        for(let elt of elements) {
            if(!!elt.value && elt.type !== "submit" && elt.type !== "reset") {
               data[elt.name] = elt.value;
            }
        }

        if(data["content"] == selectedRow.content && data["editArticle-form-title"] == selectedRow.title){
            alert("Vous n'avez pas modifié l'article");
            return false;
        }else {
            if(data["content"] == selectedRow.content){
                delete data["content"];
            }
            if(data["editArticle-form-title"] == selectedRow.title){
                delete data["editArticle-form-title"];
            }
        }
        
        $.ajax({
            type: "POST",
            url: '/sys/article/edit-article',
            data: data,
            dataType: "json", // type de retour attendu
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // type de données envoyées
            context: $('.response-message'),
            success: function (data) {
                closeModalEditArticle();
                table.ajax.reload();
            },
            error: function (xhr, resp, error) {
                console.error(`Error : ${JSON.stringify(error)}`);
            },
            complete: function (xhr, status) {
                showResponseMessage(status, "Edit");
            }
        });

    }

    function deleteArticle(e, table){
        console.log(selectedRow);
        let data = {id: selectedRow.id};
        $.ajax({
            type: "DELETE",
            url: '/sys/article/delete-article',
            data: data,
            dataType: "json", // type de retour attendu
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // type de données envoyées
            context: $('.response-message'),
            success: function (data) {
                table.ajax.reload();
            },
            error: function (xhr, resp, error) {
                console.error(`Error : ${JSON.stringify(error)}`);
            },
            complete: function (xhr, status) {
                showResponseMessage(status, "Delete");
            }
        });
    }

    function getAllArticleMemento(articleId) {
        return new Promise(function(resolve, reject) {
            $.ajax({
            type: "POST",
            url: '/sys/article/get-all-article-version',
            data: { article_id: articleId },
            dataType: "json",
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function(data) {
                resolve(data.articlesMemento);
            },
            error: function(xhr, resp, error) {
                reject(error);
            }
            });
        });
    }
//

    function showResponseMessage(status, action) {
        $('.response-message').addClass('active');
        if(status === "success") {
            $('.response-message').append(`<h2>${action} successful !</h2>`);
        }
        else {
            $('.response-message').append("<h2>Save fail !</h2>");
        }
        setTimeout(()=> {
            $('.response-message').children().remove();
        }, 2000);
    }

</script>
