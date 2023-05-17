<?php $this->captureStart("css")?>
<style>
.text-title{
    font-size   : 18px;
    font-weight : 500;
    color       : #060816;
    font-family : "Poppins", sans-serif;
}

</style>
<?php $this->captureEnd("css")?>

<?php $this->captureStart("body")?>

<!-- <section class="section"> -->
    <div class="col-12 ">
        <div class="card ">
            <div class="card-header d-flex flex-row justify-content-between align-items-center">
                <span class="text-title">Lista de usuários</span>
                <button id="btnFiltros" class="btn btn-secondary " >Filtros</button>
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
    GeraTabela()
})

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
                { data: '',                 title: "Ações",         className: "d-flex justify-content-center gap-2", render: renderAcoes },
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
    let botoes = '';

    botoes += `<button type='button' class='btn btn-primary btn-sm' title='Editar usuário'><i class="fa-regular fa-edit"></i></button>`;
    botoes += `<button type='button' class='btn btn-info btn-sm' title='Permissões do usuário'><i class="fa-regular fa-key"></i></button>`;

    if(row.user_sts === "1"){
        botoes += `<button type='button' class='btn btn-danger btn-sm' title='Inativar usuário'><i class="fa-regular fa-xmark"></button>`;
    }else{
        botoes += `<button type='button' class='btn btn-success btn-sm' title='Ativar usuário'><i class="fa-regular fa-check"></button>`;
    }

    return botoes;
}

</script>
<?php $this->captureEnd("js")?>