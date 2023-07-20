<div class="modal" id="editArticle-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
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
                        <input type="text" class="form-control" id="editArticle-form-title" name="editArticle-form-title" > 
                    </div>
                    <div class="form-group">
                        <label for="editArticle-form-content">Votre contenu d'article</label>
                        <button type="button" class="btn btn-primary btn-grapesjs" id="open-editor-edit">Ouvrir l'éditeur de contenu</button>
                        </br>
                        <div id="ul-article-version" style="display: inline-block;">

                        </div>
                        <button type="button" class="btn btn-primary btn-grapesjs" id="close-editor-edit" style="display: none;">Fermez l'éditeur</button>
                        <button type="button" class="btn btn-primary btn-grapesjs" id="save-button-edit" style="display: none;">Enregistrez votre contenu</button>                        

                        <div class="alert alert-success editArticle" role="alert" style="display: none;">
                            Le contenu de votre article a bien été enregistrer.
                        </div>

                        <div id="editorGrapesJsForEdit"></div>
                    </div>
                    <input type="submit" name="submitEditArticle" id="submitEditArticle" class="btn btn-primary" value="Modifiez votre article">
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
var editorEditArticle;
var contentArticle;

document.getElementById('open-editor-edit').addEventListener('click', function() {
    editorEditArticle = grapesjs.init({
        container: '#editorGrapesJsForEdit',
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
                    content: '<div>Contenu du template spotify</div>'
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

document.getElementById('close-editor-edit').addEventListener('click', function() {
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

document.getElementById('save-button-edit').addEventListener('click', function() {
    if (editorEditArticle) { // Vérifiez si editor est défini avant d'accéder à ses méthodes
        contentArticle = editorEditArticle.getHtml();
        let css = editorEditArticle.getCss();

        contentArticle = contentArticle.replace(/<body/g, '<div');
        contentArticle = contentArticle.replace(/<\/body>/g, '</div>');
        contentArticle += '<style>' + css + '</style>';

        let alertSuccess = document.getElementsByClassName('alert alert-success editArticle')[0];
        alertSuccess.style.display = 'block';
    }else {
        alert('Veuillez remplir le contenu de votre article avant de sauvegarder');
    }
});

//changer le contenu de lediteur de contenu quand on change de version d'article
function changeContentEditor(encodedContent){
    let content = decodeURIComponent(encodedContent);
    editorEditArticle.setComponents(content);
}

</script>