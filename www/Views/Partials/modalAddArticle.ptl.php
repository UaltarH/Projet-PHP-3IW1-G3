<div class="modal" id="multi-step-modal" tabindex="-1" role="dialog" aria-labelledby="multi-step-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="multi-step-modal-label">Formulaire à plusieurs étapes</h5>
                <button type="button" class="btn-close" id="close-modalCreateGameArticle-btn" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="multi-step-form-create-article" action="" method="POST">
                    <!-- Étape 1 -->
                    <section id="step1" class="form-step">
                        <h2>Étape 1 choix du type d'article :</h2>
                        <div class="form-group">
                            <select class="form-control" id="field1" name="categoriesArticle" required>
                                <option value="" selected disabled hidden>Choisir le type d'article</option>
                                <?php foreach ($config["categoriesArticle"] as $value=>$name): ?>
                                    <option value=<?= $value?>> <?= $name ?> </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary next-step">Suivant</button>
                    </section>

                    <!-- Étape 2 -->
                    <section id="step2" class="form-step">
                        <h2>Étape 2 ajouter les informations relative a l'article :</h2>
                        <!-- Champs de la deuxième étape - quand on a selectionnez article jeu -->
                        <div id="step2-option1" style="display: none;">
                            <div class="form-group">
                                <label for="createArticleGame-form-titleGame">Votre titre de jeu et de l'article</label>
                                <input type="text" class="form-control" id="createArticleGame-form-titleGame" name="createArticleGame-form-titleGame" >

                                <label for="createArticleGame-form-categoryGame">Votre categorie de jeux</label>
                                <select class="form-control" id="createArticleGame-form-categoryGame" name="createArticleGame-form-categoryGame" >
                                    <?php foreach ($config["categoriesGame"] as $value=>$name): ?>
                                        <option value=<?= $value?>> <?= $name ?> </option>
                                    <?php endforeach;?>
                                </select>

                                <label for="createArticleGame-form-imageJeu">Votre image du jeu</label>
                                <input name="createArticleGame-form-imageJeu" class="form-control" id="createArticleGame-form-imageJeu" type="file">
                            </div>
                        </div>
                        <!-- Champs de la deuxième étape - quand on a selectionnez truc et astuce -->
                        <div id="step2-option2" style="display: none;">
                            <div class="form-group">
                                <label for="createArticleAboutGame-form-game">Votre jeu</label>
                                <select class="form-control" id="createArticleAboutGame-form-game" name="createArticleAboutGame-form-game" >
                                    <?php foreach ($config["games"] as $value=>$name): ?>
                                        <option value=<?= $value?>> <?= $name ?> </option>
                                    <?php endforeach;?>
                                </select>

                                <label for="createArticleAboutGame-form-titleArticle">Votre titre d'article</label>
                                <input type="text" class="form-control" id="createArticleAboutGame-form-titleArticle" name="createArticleAboutGame-form-titleArticle" >                                
                            </div>
                        </div>  
                        <button type="button" class="btn btn-secondary prev-step">Précédent</button>
                        <button type="button" class="btn btn-primary next-step">Suivant</button>                                          
                    </section>
                    <!-- Étape 3 -->
                    <section id="step3" class="form-step">
                        <h2>Étape 3 ajouter votre contenu a votre article :</h2>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary btn-grapesjs" id="open-editor">Ouvrir l'éditeur de contenu</button>
                            <button type="button" class="btn btn-primary btn-grapesjs" id="close-editor" style="display: none;">Fermez l'éditeur</button>
                            <button type="button" class="btn btn-primary btn-grapesjs" id="save-button" style="display: none;">Enregistrez votre contenu</button>      
                            <p id="addArticleContent-info">Votre article ne contient pas de contenu.</p>
                            
                            <div id="editorGrapesJs"></div>
                        </div>
                        <button type="button" class="btn btn-secondary prev-step">Précédent</button>
                        <input type="submit" name="submitCreateArticle" class="btn btn-primary" value="Créez l'article">
                    </section>
                </form>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="/library/grapes.js/grapesjs/css/grapes.min.css">
<script src="/library/grapes.js/grapesjs/grapes.min.js"></script>
<script src="/library/grapes.js/packages/tabs/grapesjs-tabs.min.js"></script>
<script src="/library/grapes.js/packages/blocks-basic/index.js"></script>

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

    var htmlContent = "";
    var editor; // Déclaration de la variable editor en dehors des fonctions de clic

    document.getElementById('open-editor').addEventListener('click', function() {
        editor = grapesjs.init({
            container: '#editorGrapesJs',
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

        // Ajoutez le contenu HTML à l'éditeur si il existe deja
        let infoContent = document.getElementById('addArticleContent-info');
        if(htmlContent != "") {
            infoContent.textContent = "Votre article contient du contenu.";
            editor.setComponents(htmlContent);
        }else {
            infoContent.textContent = "Votre article ne contient pas de contenu.";
        }

        // afficher les bouttons de sauvegarde et de fermeture et cacher celui d'ouverture
        let btnCloseEditor = document.getElementById('close-editor');
        let btnSave = document.getElementById('save-button');
        let btnOpenEditor = document.getElementById('open-editor');

        btnCloseEditor.style.display = 'block';
        btnSave.style.display = 'block';
        btnOpenEditor.style.display = 'none';
    });

    document.getElementById('close-editor').addEventListener('click', function() {
        if (editor) {
            editor.destroy();
            editor = null; // Réinitialise la variable editor après avoir détruit l'éditeur
            //remove style of the container
            var containerGrapeJs = document.getElementById('editorGrapesJs');
            containerGrapeJs.removeAttribute('style');
        }

        let btnOpenEditor = document.getElementById('open-editor');
        let btnSave = document.getElementById('save-button');
        let btnCloseEditor = document.getElementById('close-editor');
        
        btnCloseEditor.style.display = 'none';
        btnOpenEditor.style.display = 'block';
        btnSave.style.display = 'none';
    });

    var saveButton = document.getElementById('save-button');
    saveButton.addEventListener('click', function() {
        if (editor) { // Vérifiez si editor est défini avant d'accéder à ses méthodes
            htmlContent = editor.getHtml();
            let css = editor.getCss();

            htmlContent = htmlContent.replace(/<body/g, '<div');
            htmlContent = htmlContent.replace(/<\/body>/g, '</div>');
            htmlContent += '<style>' + css + '</style>';
            
            let infoContent = document.getElementById('addArticleContent-info');
            if(htmlContent != "") {
                infoContent.textContent = "Votre article contient du contenu.";
            } else {
                infoContent.textContent = "Votre article ne contient pas de contenu.";
            }
            console.log(htmlContent);
        }else {
            alert('Veuillez remplir le contenu de votre article avant de sauvegarder');
        }
    });
</script>

