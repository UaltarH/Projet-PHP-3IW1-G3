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


<!-- btn pour ouvrir la modal d'ajout d'article  -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#multi-step-modal" id="open-modal-btn">Créer un article</button>

<!-- modal d'ajout d'article -->
<?php $this->partial("modalAddArticle", $optionsForms) ?>


<!-- grapes.js test -->
<link rel="stylesheet" href="/library/grapes.js/grapesjs/css/grapes.min.css">
<script src="/library/grapes.js/grapesjs/grapes.min.js"></script>
<script src="/library/grapes.js/packages/tabs/grapesjs-tabs.min.js"></script>
<script src="/library/grapes.js/packages/blocks-basic/index.js"></script>


<button id="save-button">Enregistrer</button>

<button id="open-editor">Ouvrir l'éditeur</button>
<button id="close-editor">Fermer l'éditeur</button>
<div id="gjs"></div>


<script type="text/javascript">
    //grapes.js 
    var templateSpotify = ` <main>
			<section id="section1">
				<div class="container">
					<div>
						<h1>Obtenez 3 mois pour 0,99 €</h1>
						<h2>Seulement 9,99 € /par mois ensuite. Annulez à tout moment.</h2>
						<a href="#" class="cta-button cta-button--blue">Démarrer Spotify Premium</a>
					</div>
					<footer>
						<p>
							Offre réservée aux utilisateurs n'ayant jamais essayé Spotify Premium. Offre valable jusqu'au 31 déc. 2021.<br>Offre soumise à conditions.
						</p>
					</footer>
				</div>
			</section>
			<section id="section2">
				<div class="container">
					<h1>Pourquoi passer à Spotify premium ?</h1>
					<div>
						<article class="benefit">
							<figure>
								
							</figure>
							<h1>Téléchargez votre musique.</h1>
							<h2>Profitez-en même sans connexion internet.</h2>
						</article>
						<article class="benefit">
							<figure>
								
							</figure>
							<h1>Écoutez sans pubs.</h1>
							<h2>Profitez de vos titres sans interruption.</h2>
						</article>
						<article class="benefit">
							<figure>
								
							</figure>
							<h1>Écoutez les titres de votre choix.</h1>
							<h2>Même sur votre mobile.</h2>
						</article>
						<article class="benefit">
							<figure>
								
							</figure>
							<h1>Zapping à l'infini.</h1>
							<h2>Cliquez simplement sur suivant.</h2>
						</article>
					</div>
				</div>
			</section>
			<section id="section3">
				<div class="container">
					<h1>Écoutez gratuitement ou abonnez-vous à Spotify Premium.</h1>
					<div class="offers-container">
						<article class="offer">
							<h1>Spotify Free</h1>
							<h2>0,00 € <small>/ mois</small></h2>
							<ul>
								<li>Lecture aléatoire</li>
								<li class="disabled">Sans interruptions</li>
								<li class="disabled">Zappez les titres sans limite</li>
								<li class="disabled">Écouter hors connexion</li>
								<li class="disabled">Écoutez les titres de votre choix</li>
								<li class="disabled">Son de qualité supérieure</li>
							</ul>
							<a href="#" class="cta-button cta-button--white">Démarrer</a>
						</article>
						<article class="offer">
							<h1>Spotify Premium</h1>
							<h2>3 mois pour 0,99 €</h2>
							<ul>
								<li>Lecture aléatoire</li>
								<li>Sans interruptions</li>
								<li>Zappez les titres sans limite</li>
								<li>Écouter hors connexion</li>
								<li>Écoutez les titres de votre choix</li>
								<li>Son de qualité supérieure</li>
							</ul>
							<a href="#" class="cta-button cta-button--green">Démarrer spotify premium</a>
						</article>
					</div>
					<footer>
						<p>
							Seulement 9,99 € /par mois ensuite. Offre réservée aux utilisateurs n'ayant jamais essayé Spotify Premium.<br>Offre valable jusqu'au 31 déc. 2018. Offre soumise à conditions.
						</p>
					</footer>
				</div>
			</section>
		</main> <style> 
            a{
                color: inherit;
            }

            button{
                border: none;
                background-color: transparent;
                cursor: pointer;
            }

            /*Positionnement des blocs*/

            .container{
                max-width: 1170px;
                margin: auto;
            }

            .cta-button{
                display: inline-block;
                background-color: grey;
                color: white;
                padding: 1em 3em;
                text-transform: uppercase;
                font-weight: 700;
                text-decoration: none;
                border-radius: 600px;
                letter-spacing: 0.1em;
                transition: all 0.3s;
                border: solid thin grey;
                text-align: center;
            }

            .cta-button:hover{
                background-color: lightgrey;
                border-color: lightgrey;
            }

            .cta-button--blue{
                background-color: var(--blue);
                border-color: var(--blue);
            }

            .cta-button--blue:hover{
                background-color: var(--blue-hover);
                border-color: var(--blue-hover);
            }

            .cta-button--white{
                background-color: white;
                border-color: var(--green);
                color: var(--green);
            }

            .cta-button--white:hover{
                background-color: var(--green-hover);
                border-color: var(--green);
                color: white;
            }

            .cta-button--green{
                background-color: var(--green);
                border-color: var(--green);
            }

            .cta-button--green:hover{
                background-color: var(--green-hover);
                border-color: var(--green-hover);
            }

            body{
                margin: 0;
                font-family: 'Arial', sans-serif;
            }

            header{
                background-color: rgba(0,0,0,0.5);
                padding: 1rem 0;
                position: fixed;
                width: 100%;
                z-index: 10;
            }

            header .container{
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            img{
                max-width: 100%;
                max-height: 100%;
            }

            #logo-link{
                width: 140px;
                line-height: 0;
                z-index: 10;
            }

            #menu-button{
                display: none;
                z-index: 10;
                width: 3rem;
                height: 3rem;
            }

            #menu-button::before{
                content: '';
                background-image: url('assets/images/menu.svg');
                background-repeat: no-repeat;
                background-position: center;
                background-size: contain;
                display: inline-block;
                width: 100%;
                height: 100%;
            }

            header nav ul{
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
            }


            header nav li a{
                text-decoration: none;
                padding: 0.5em;
                display: block;
                color: white;
            }


            /*SECTION1*/

            #section1{
                height: 640px;
                padding-bottom: 20px;
                color: white;
                background-image: url('assets/images/hero-image.jpg');
                background-size: cover;
                background-position: center right;
            }

            #section1 h1{
                font-size: 96px;
                margin: 0;
            }

            #section1 h2{
                font-size: 30px;
            }

            #section1 .container{
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            #section1 .cta-button{
                animation: fadeIn 1s;
            }

            #section1 footer{
                text-align: center;
                flex-grow: 0;
                flex-shrink: 0;
            }

            #section1 .container div{
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
            }



            #section2{
                background-color: white;
            }

            #section2 .container{
                display: flex;
                flex-direction: column;
            }

            #section2 .container > h1{
                text-align: center;
                font-size: 48px;
            }

            #section2 .container div{
                display: flex;
            }

            .benefit{
                padding: 30px;
                width: 25%;
                text-align: center;
            }

            .benefit h1{
                font-size: 20px;
            }

            .benefit h2{
                font-size: 14px;
                font-weight: 400;
            }

            #section3{
                background-color: #F8F8F8;
                padding: 40px 0;
            }

            #section3 .container{
                display: flex;
                flex-direction: column;
            }

            #section3 .container > h1{
                text-align: center;
                font-size: 30px;
            }

            .offers-container{
                width: 70%;
                margin: auto;
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
                margin-bottom: 30px;
            }

            .offer{
                width: 48%;
                background-color: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 8px rgba(0,0,0,0.2);
                display: flex;
                flex-direction: column;
                position: relative;
                transition: all 0.5s;
                top: 0;
                cursor: pointer;
            }

            .offer:hover{
                /*transform: translateY(-10px);*/
                top: -10px;
                box-shadow: 0 0 24px rgba(0,0,0,0.2);
            }

            .offer h1{
                font-size: 24px;
                font-weight: 400;
                margin: 0;
            }

            .offer h2{
                font-size: 32px;
                margin: 0;
            }

            .offer h2 small{
                font-weight: 400;
                font-size: 60%;
            }

            .offer ul{
                border-top: solid thin lightgrey;
                border-bottom: solid thin lightgrey;
                padding-top: 30px;
                padding-bottom: 30px;
                padding-left: 30px;
                list-style-image: url('assets/images/checklist.svg');
            }

            .offer ul li{
                margin-bottom: 1em;
            }

            .offer ul li.disabled{
                opacity: 0.5;
            }

            #section3 footer{
                text-align: center;
            }



            body > footer {
                padding: 1rem 0;
                background-color: black;
                color: white;
                font-size: 14px;
            }

            body > footer .container {
                display: flex;
                justify-content: space-between;
                align-items: center;

            }

            body > footer ul{
                list-style: none;
                margin: 0;
                padding: 0;
                display: flex;
            }

            body > footer li a{
                padding: 0.5em;
                display: block;
                text-decoration: none;
    } </style>`;

    
    var editor; // Déclaration de la variable editor en dehors des fonctions de clic

    document.getElementById('open-editor').addEventListener('click', function() {
        editor = grapesjs.init({
            container: '#gjs',
            pageManager: true, 
            storageManager:  {
                type: 'indexeddb',
            },
            plugins: ['grapesjs-tabs', 'gjs-blocks-basic'],
            pluginsOpts: {
                'grapesjs-tabs': {},
                "gjs-blocks-basic": {
                    blocks: ['column1', 'column2', 'column3', 'column3-7', 'text', 'link', 'image']
                }
            },
            blockManager: {
                blocks: [
                    {
                        id: 'template1',
                        label: 'Sportify Template',
                        content: templateSpotify
                    },
                    {
                        id: 'template2',
                        label: 'Template 2',
                        content: '<div>Contenu du template 2</div>'
                    }
                    // Ajoutez d'autres templates ici
                ]
            }
        });
    });

    document.getElementById('close-editor').addEventListener('click', function() {
        if (editor) {
            editor.destroy();
            editor = null; // Réinitialise la variable editor après avoir détruit l'éditeur
        }
    });

    var saveButton = document.getElementById('save-button');

    saveButton.addEventListener('click', function() {
        if (editor) { // Vérifiez si editor est défini avant d'accéder à ses méthodes
            var html = editor.getHtml();
            var css = editor.getCss();

            html = html.replace(/<body/g, '<div>');
            html = html.replace(/<\/body>/g, '</div>');
            html += '<style>' + css + '</style>';
            console.log(html);
        }
    });

        
        


     
</script>



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

        // Étapes du formulaire
        const formSteps = document.getElementsByClassName('form-step');
        // Bouton Suivant
        const nextBtn = document.getElementsByClassName('next-step')[0];
        // Bouton Précédent
        const prevBtn = document.getElementsByClassName('prev-step')[0];
        // Sélecteur de l'étape 1
        const step1Select = document.getElementById('field1');
        // Conteneur de l'option 1 de l'étape 2
        const step2Option1 = document.getElementById('step2-option1');
        // Conteneur de l'option 2 de l'étape 2
        const step2Option2 = document.getElementById('step2-option2');

        // Fonction pour afficher une étape spécifique
        function showStep(stepIndex) {
            for (let i = 0; i < formSteps.length; i++) {
                formSteps[i].style.display = 'none';
            }
            formSteps[stepIndex].style.display = 'block';
        }

        // Gestionnaire d'événement pour le bouton Suivant
        nextBtn.addEventListener('click', function() {
            const currentStep = Array.from(formSteps).findIndex(step => step.style.display === 'block');
            const nextStep = currentStep + 1;
            showStep(nextStep);
        });

        // Gestionnaire d'événement pour le bouton Précédent
        prevBtn.addEventListener('click', function() {
            const currentStep = Array.from(formSteps).findIndex(step => step.style.display === 'block');
            const prevStep = currentStep - 1;
            showStep(prevStep);
        });

        // Gestionnaire d'événement pour le changement de valeur dans le sélecteur de l'étape 1
        step1Select.addEventListener('change', function() {
            if (step1Select.options[step1Select.selectedIndex].text === 'Jeux') {
                step2Option1.style.display = 'block';
                step2Option2.style.display = 'none';
            } else if (step1Select.options[step1Select.selectedIndex].text === 'Trucs et astuces') {
                step2Option1.style.display = 'none';
                step2Option2.style.display = 'block';
            } else {
                step2Option1.style.display = 'none';
                step2Option2.style.display = 'none';
            }
        });

        // Afficher la première étape lors de l'ouverture de la modal
        showStep(0);
        
    });
</script>
