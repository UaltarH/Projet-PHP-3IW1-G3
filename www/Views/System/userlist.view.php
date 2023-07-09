<h1>User List</h1>

<?php include "Views/Partials/editUserModal.ptl.php" ?>
<nav>
    <ul>
        <li><a href="/sys/user/list?action=faker">Generate</a></li>
    </ul>
</nav>
<table id="userTable" class="display">
    <thead>
    <tr>
        <th>ID</th>
        <th>User Name</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>E-mail</th>
        <th>Register Date</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    </thead>
</table>

<?php $this->partial("form", $createUserForm, $createUserFormErrors); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script>

    let selectedRow;
    let responseMessage = $('.response-message');
    $(document).ready(function() {
        $('input[required]').siblings("")
        let table = $('#userTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'get',
            'ajax': {
                'url':'/api/user/list'
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
                { data: 'pseudo' },
                { data: 'first_name' },
                { data: 'last_name' },
                { data: 'email' },
                { data: 'date_inscription' },
                { data: 'role_name' },
                { data: 'action'}
            ],
            'drawCallback': function() {
                let allCrudButton = $('.crud-button');
                allCrudButton.on('click', function(e){
                    selectedRow = table.row($(e.target).parents('tr')).data();
                    if($(e.target).hasClass('row-edit-button')) {
                        handleEditModal(e, table);
                    }
                    else if ($(e.target).hasClass('row-delete-button')) {
                        handleDelete(e, table);
                    }
                });

                $('#closeModal').on('click', function(e){ handleCloseEditModal(); });
                $('input[name="submitEditUser"]').on('click', function(e) { handleEditSubmit(e, table); });
                $('input[name="submitCreateUser"]').on('click', function(e) { handleCreateSubmit(e, table); });
            }
        });
    });
    function handleCreateSubmit(e, table) {
        e.preventDefault();
        // checks validity of form
        if (! $('#create-user-form')[0].checkValidity()) {
            $('#create-user-form')[0].reportValidity();
            return false;
        }
        let data = {};
        let createUserForm = document.getElementById('create-user-form');
        let elements = createUserForm.elements;
        for(let elt of elements) {
            if(!!elt.value && elt.type !== "submit" && elt.type !== "reset") {
                data[elt.name] = elt.value;
            }
        }
        $.ajax({
            type: "POST",
            url: '/api/user/add/',
            data: data,
            dataType: "json", // type de retour attendu
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // type de données envoyées
            context: responseMessage,
            success: function (data) {
                table.ajax.reload();
            },
            error: function (xhr, resp, error) {
                console.error(`Error : ${JSON.stringify(error)}`);
            },
            complete: function (xhr, status) {
                showResponseMessage(status, "Add");
                $('#create-user-form').trigger('reset');
            }
        });
    }
    function handleEditModal(e, table) {
        e.preventDefault();
        //on récupère la data de la row
        let modalContainer = $('#modal-container');
        modalContainer.addClass("active");
    }
    function handleCloseEditModal() {
        $('#modal-container').removeClass("active");
        // on clear les champs
        $('#edit-user-form').trigger('reset');
    }
    function handleEditSubmit(e, table) {
        e.preventDefault();

        // checks validity of form
        if (! $('#edit-user-form')[0].checkValidity()) {
            $('#edit-user-form')[0].reportValidity();
            return false;
        }
        let data = {};
        let editUserForm = document.getElementById('edit-user-form');
        let elements = editUserForm.elements;
        data["id"] = selectedRow.id;
        for(let elt of elements) {
            if(!!elt.value && elt.type !== "submit" && elt.type !== "reset") {
               data[elt.name] = elt.value;
            }
        }
        $.ajax({
            type: "POST",
            url: '/api/user/edit/',
            data: data,
            dataType: "json", // type de retour attendu
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // type de données envoyées
            context: responseMessage,
            success: function (data) {
                handleCloseEditModal();
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
    function handleDelete(e, table) {
        let data = {id: selectedRow.id};
        $.ajax({
            type: "DELETE",
            url: '/api/user/delete/',
            data: data,
            dataType: "json", // type de retour attendu
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // type de données envoyées
            context: responseMessage,
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
    function showResponseMessage(status, action) {
        responseMessage.addClass('active');
        if(status === "success") {
            responseMessage.append(`<h2>${action} successful !</h2>`);
        }
        else {
            responseMessage.append("<h2>Save fail !</h2>");
        }
        setTimeout(()=> {
            responseMessage.children().remove();
        }, 2000);
    }
</script>
