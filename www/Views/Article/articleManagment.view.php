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
    });
</script>
