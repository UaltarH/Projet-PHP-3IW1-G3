<div class="modal" id="editArticle-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-modal-label">Edition d'un article</h5>
                <button type="button" class="btn-close" id="close-modalEditArticle-btn" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-edit-article" action="" method="POST">
                    <div class="form-group">
                        <label for="editArticle-form-title">Votre titre d'article</label>
                        <input type="text" class="form-control" id="editArticle-form-title"
                               name="editArticle-form-title">
                    </div>
                    <div class="form-group">
                        <label for="editArticle-form-content">Votre contenu d'article</label>
                        <button type="button" class="btn btn-primary btn-grapesjs" id="open-editor-edit">Ouvrir
                            l'éditeur de contenu
                        </button>
                        </br>
                        <div id="ul-article-version" style="display: inline-block;">

                        </div>
                        <button type="button" class="btn btn-primary btn-grapesjs" id="close-editor-edit"
                                style="display: none;">Fermez l'éditeur
                        </button>
                        <button type="button" class="btn btn-primary btn-grapesjs" id="save-button-edit"
                                style="display: none;">Enregistrez votre contenu
                        </button>
                        <div id="editorGrapesJsForEdit"></div>
                    </div>
                    <input type="submit" name="submitEditArticle" id="submitEditArticle" class="btn btn-primary"
                           value="Modifiez votre article">
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/assets/vendor/grapes.js/grapesjs/css/grapes.min.css">
<script src="/assets/vendor/grapes.js/grapesjs/grapes.min.js"></script>
<script src="/assets/vendor/grapes.js/packages/tabs/grapesjs-tabs.min.js"></script>
<script src="/assets/vendor/grapes.js/packages/blocks-basic/index.js"></script>


<script type="text/javascript">

    var templateArticle = `<main>
        <section>
            <h1>Titre de l'article</h1>
            <p>Contenu de l'article</p>
        </section>
        <div>
            <h5>Nom de l'auteur</h5>
        </div>
        </main>
        <style>
            section {
                text-align: center;
            }
            p {
                text-align: justify;
            }
        </style>`;

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

    var editorEditArticle;
    var contentArticle;

    document.getElementById('open-editor-edit').addEventListener('click', function () {
        editorEditArticle = grapesjs.init({
            container: '#editorGrapesJsForEdit',
            pageManager: true,
            storageManager: {
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
                        label: 'Article',
                        content: templateArticle
                    }
                    // Ajoutez d'autres templates ici
                ]
            }
        });

        // Ajoutez le contenu de l'article dans l'éditeur
        editorEditArticle.setComponents(contentArticle);


        // afficher les bouttons de sauvegarde et de fermeture et cacher celui d'ouverture
        let btnCloseEditor = document.getElementById('close-editor-edit');
        let btnSave = document.getElementById('save-button-edit');
        let btnOpenEditor = document.getElementById('open-editor-edit');

        btnCloseEditor.style.display = 'block';
        btnSave.style.display = 'block';
        btnOpenEditor.style.display = 'none';
    });

    document.getElementById('close-editor-edit').addEventListener('click', function () {
        if (editorEditArticle) {
            editorEditArticle.destroy();
            editorEditArticle = null; // Réinitialise la variable editor après avoir détruit l'éditeur
            //remove style of the container
            var containerGrapeJs = document.getElementById('editorGrapesJsForEdit');
            containerGrapeJs.removeAttribute('style');
        }

        let btnOpenEditor = document.getElementById('open-editor-edit');
        let btnSave = document.getElementById('save-button-edit');
        let btnCloseEditor = document.getElementById('close-editor-edit');

        btnCloseEditor.style.display = 'none';
        btnOpenEditor.style.display = 'block';
        btnSave.style.display = 'none';
    });

    document.getElementById('save-button-edit').addEventListener('click', function () {
        if (editorEditArticle) { // Vérifiez si editor est défini avant d'accéder à ses méthodes
            contentArticle = editorEditArticle.getHtml();
            let css = editorEditArticle.getCss();

            contentArticle = contentArticle.replace(/<body/g, '<div');
            contentArticle = contentArticle.replace(/<\/body>/g, '</div>');
            contentArticle += '<style>' + css + '</style>';
        } else {
            alert('Veuillez remplir le contenu de votre article avant de sauvegarder');
        }
    });

    //changer le contenu de lediteur de contenu quand on change de version d'article
    function changeContentEditor(encodedContent) {
        let content = decodeURIComponent(encodedContent);
        editorEditArticle.setComponents(content);
    }

</script>