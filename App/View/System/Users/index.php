<?php $this->captureStart("css")?>
<style>
.text-title{
    font-size   : 18px;
    font-weight : 500;
    color       : #060816;
    font-family : "Poppins", sans-serif;
}

#formUser input[type='text']{
    text-transform: uppercase;
}
.permissions-list{
    list-style: none;
}

.dataTable td {
    vertical-align: middle !important;
}

</style>
<?php $this->captureEnd("css")?>

<?php $this->captureStart("body") ?>

<?php
    include_once "modalFormUser.php";
    include_once "modaPermissionsUser.php";
?>

<!-- <section class="section"> -->
    <div class="col-12 ">
        <div class="card ">
            <div class="card-header d-flex flex-row justify-content-between align-items-center">
                <span class="text-title">Lista de usuários</span>
                <div>
                    <button id="btnFiltros" class="btn btn-outline-info btn-sm" >Filtros</button>
                    <?php 
                        if($this->CheckPermission(11)){
                    ?>
                        <button type="button" onClick="NewUser()" class="btn btn-primary btn-sm">Novo usuário</button>
                    <?php
                        }
                    ?>
                </div>
            </div>
            <div class="card-body mt-4">
                <div class="row table-responsive">
                    <table id="tableUsers" class="table table-sm table-striped table-bordered w-100 align-text-top"></table>
                </div>
            </div>
        </div>
    </div>

<!-- </section> -->

<?php $this->captureEnd("body")?>

<?php $this->captureStart("js")?>

<script>

$(function(){
    GeraTabela();
    ModalDraggable();
    
    $("#btnSalvar").click(function(){
        const password        = $("#user_pass");
        const confirmPassword = $("#confirm_pass");
        if(password.val() !== "" || confirmPassword.val() !== ""){
            if(password.val() !== confirmPassword.val()){
                alerta("Senhas não coincidem.", "Erro validação", "error");
                return;
            }
        }
        
        const frm = required_elements($("#formUser .required"))
        if(!frm.valid){
            alerta("Campos obrigatórios não preenchidos", "Erro validação", "error")
        }else{
            confirmaAcao(`Confirma salvar usuário "${$("#user_login").val().toUpperCase()}"?`, SaveFormUser, [])
        }
    })
    
    $("#btnSalvarPermission").click(function(){
        confirmaAcao("Confirma atualizar as permissões do usuário?", ConfirmSavePermissions, [])
    })
})

// let filtros   = new bootstrap.Modal("#modalFiltros", modalOption);
let formUsers       = new bootstrap.Modal("#modalFormUser", modalOption);
let permissionsUser = new bootstrap.Modal("#modalPermissionsUser", modalOption);

const GeraTabela = () => {
    // let form = $("#formFiltro").serializeObject();
    let form = [];

    $.ajax({url: '{{route()->link("users-list")}}',method:"POST", data: form})
    .done(resp => {

        $('#tableUsers').DataTable({
            columns: [
                { data: 'id',               title: "#",             className: "text-center "},
                { data: 'user_fullname',    title: "Nome Completo", className: "text-center "},
                { data: 'user_email',       title: "Email",         className: "text-center "},
                { data: 'user_lastlogin',   title: "Ult. Login",    className: "text-center ", render: renderFormataDataHora , orderable: false },
                { data: 'user_sts',         title: "Status",        className: "text-center ", render: renderStatus },
                { data: '',                 title: "Ações",         className: "d-flex justify-content-center gap-1", render: renderAcoes,orderable: false },
            ],
            data: resp,
            order: [["0", 'asc']],
            destroy: true,
            buttons: ['pageLength', exportMenu('pdf', 'excelNumber')],
        });
    })
}

const renderStatus = (data) => {
    let status = "";
    switch(data){
        case "1":
            status = `<span class="badge bg-success">Ativo</span>`;
        break;
        case "2":
            status = `<span class="badge bg-danger">Inativo</span>`;
        break;

        case "3":
            status = `<span class="badge bg-warning">Bloqueado</span>`;
        break;
    }

    return status;
}

const renderAcoes = (data, type, row) => {
    let botoes = '&nbsp';

    <?php 
        if($this->CheckPermission(10)){
    ?>
            botoes += `<button type='button' class='btn btn-primary btn-sm' onclick="EditUser(${row.id})" title='Editar usuário'><i class="fa-regular fa-edit"></i></button>`;
    <?php
        }
    ?>

    <?php 
        if($this->CheckPermission(12) ){
    ?>
        if(row.id !== "1"){
            botoes += `<button type='button' class='btn btn-info btn-sm' onclick='ModalPermission("${row.id}")' title='Permissões do usuário'><i class="fa-regular fa-key"></i></button>`;
        }
    <?php
        }
    ?>

    <?php 
        if($this->CheckPermission(14) ){
    ?>
        if(row.id !== "1"){
            if(row.user_sts === "1"){
                botoes += `<button onclick="ConfirmToggleStatus(${row.id}, 2)" type='button' class='btn btn-danger btn-sm' title='Inativar usuário'><i class="fa-regular fa-xmark"></button>`;
            }else{
                botoes += `<button onclick="ConfirmToggleStatus(${row.id}, 1)" type='button' class='btn btn-success btn-sm' title='Ativar usuário'><i class="fa-regular fa-check"></button>`;
            }
        }
    <?php
        }
    ?>
    

    return botoes;
}

const EditUser = idUser => {
    document.forms["formUser"].reset();

    $.ajax({url: '{{route()->link("get-user")}}' + `${idUser}`,})
    .done(resp => {
        formUsers.show();
        let lastName = resp.user_fullname.replace(resp.user_nome, "").trim();
        $("#modalFormUser .modal-title").text("Alterar usuário");
        remove_required($("#formUser .required"))
        popula_dados("#formUser", resp);

        $("#user_lastname").val(lastName);
        $("#user_pass, #confirm_pass").removeClass("required");
        $("label[for='user_pass'], label[for='confirm_pass']").removeClass("required-label");
    })
}

const NewUser = () => {
    document.forms["formUser"].reset()
    formUsers.show();

    $("#user_pass, #confirm_pass").addClass("required");
    $("label[for='user_pass'], label[for='confirm_pass']").addClass("required-label");

    remove_required($("#formUser .required"))
    $("#modalFormUser .modal-title").text("Novo usuário");
}

const SaveFormUser = () => {
    let id = $("#id").val();
    let form = new FormData( $("#formUser")[0] );

    if( id === "" ){
        $.ajax({url: `{{route()->link("new-user")}}${id}`,type:"POST",dataType:"json", data: form, processData: false, contentType: false})
        .done(resp => {
            SaveUserDone()
        })
    }else{
        $.ajax({url: `{{route()->link("update-user")}}${id}`,type:"POST",dataType:"json", data: form,processData: false, contentType: false})
        .done(resp => {
            SaveUserDone()
        })
    }
}

const SaveUserDone = () => {
    GeraTabela();
    CloseModal();
    alerta("Usuário salvo com sucesso.","Sucesso", "success");
}

const CloseModal = () => {
    formUsers.hide();
    permissionsUser.hide();
}

const ModalPermission = id_user => {
    $.ajax({url: `{{route()->link("user-permissions")}}${id_user}`})
    .done(resp => {
        $("#formPermissionsUser input:checkbox").prop("checked", false)
        Array.from(resp).forEach((v) =>{
            $(`#formPermissionsUser input:checkbox[value='${v.id_permission}']`).prop("checked", true)
        })
        $("#id_user").val(id_user)
        permissionsUser.show();
    })
    
}

const ConfirmSavePermissions = () => {
    let {permissions,id_user} = $("#formPermissionsUser").serializeObject();

    $.ajax({url: `{{route()->link("save-permissions")}}${id_user}`,method: "POST", data : {permissions}})
    .done(resp => {
        alerta("Permissões atualizadas com sucesso.", "Sucesso", "success");
        CloseModal();
    });
}

const ConfirmToggleStatus = (id_user,id_status) => {
    let dados = {id_user, id_status};
    confirmaAcao("Confirma alterar status do usuário?", ToggleStatusUser, dados)
}

const ToggleStatusUser = dados => {
    let {id_user, id_status} = dados;

    $.ajax({url: `{{route()->link("change-user-status")}}${id_user}/${id_status}`})
    .done(resp => {
        alerta("Status atualizado com sucesso.", "Sucesso", "success");
        GeraTabela();
    });
}

</script>
<?php $this->captureEnd("js")?>