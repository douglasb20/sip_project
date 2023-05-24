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
<?php 

$this->captureEnd("css");
$this->captureStart("body");

include_once "modalFormSip.php";

?>

<!-- <section class="section"> -->
    <div class="col-12 ">
        <div class="card ">
            <div class="card-header d-flex flex-row justify-content-between align-items-center">
                <span class="text-title">Lista de operadores</span>
                <div>
                    <button id="btnFiltros" class="btn btn-outline-info btn-sm" >Filtros</button>

                    <?php 
                        if($this->CheckPermission(24)){
                    ?>
                        <button type="button" onClick="NewSip()" class="btn btn-primary btn-sm">Novo operador</button>
                    <?php
                        }
                    ?>

                    <?php 
                        if($this->CheckPermission(25)){
                    ?>
                        <button type="button" id="btnAtualizaSip" class="btn btn-success btn-sm" title="Atualizar lista de operadores pelo config">
                            <i class="fa-regular fa-arrows-rotate"></i>
                        </button>
                    <?php
                        }
                    ?>
                    
                </div>
            </div>
            <div class="card-body mt-4">
                <div class="row table-responsive">
                    <table id="tableSips" class="table table-sm table-striped table-bordered w-100 "></table>
                </div>
            </div>
        </div>
    </div>

<!-- </section> -->

<?php 

$this->captureEnd("body");
$this->captureStart("js")

?>

<script>

$(function(){
    GeraTabela();
    ModalDraggable();

    $("#btnSalvarSip").click(function(){
        
        const frm = required_elements($("#formSip .required"))
        if(!frm.valid){
            alerta("Campos obrigatórios não preenchidos", "Erro validação", "error")
        }else{
            confirmaAcao(`Confirma salvar operador "${$("#id_sip").val().toUpperCase()}"?`, SaveFormSip, [])
        }
    })
    
    $("#btnAtualizaSip").click(function(){
        confirmaAcao("Deseja atualizar a lista de operadores?", UpdateSipList, [])
    })
})

// let filtros   = new bootstrap.Modal("#modalFiltros", modalOption);
let formSip       = new bootstrap.Modal("#modalFormSip", modalOption);

const GeraTabela = () => {
    // let form = $("#formFiltro").serializeObject();
    let form = [];

    $.ajax({url: '{{route()->link("sip-list")}}',method:"POST", data: form})
    .done(resp => {

        $('#tableSips').DataTable({
            columns: [
                { data: 'sip_dial',     title: "Ramal",     className: "text-center "},
                { data: 'callerId',     title: "Nome",      className: "text-center "},
                { data: 'sip_status',   title: "Status",    className: "text-center ", render: renderStatus, orderable: false  },
                { data: '',             title: "Ações",     className: "d-flex justify-content-center gap-1", render: renderAcoes, orderable: false },
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
            botoes += `<button type='button' class='btn btn-primary btn-sm' onclick="EdiSip(this)" title='Editar operador'><i class="fa-regular fa-edit"></i></button>`;
    <?php
        }
    ?>

    <?php 
        if($this->CheckPermission(14) ){
    ?>
        
        if(row.sip_status === "1"){
            botoes += `<button onclick="ConfirmToggleStatus(${row.id_sip}, 2)" type='button' class='btn btn-danger btn-sm' title='Inativar operador'><i class="fa-regular fa-xmark"></button>`;
        }else{
            botoes += `<button onclick="ConfirmToggleStatus(${row.id_sip}, 1)" type='button' class='btn btn-success btn-sm' title='Ativar operador'><i class="fa-regular fa-check"></button>`;
        }
        
    <?php
        }
    ?>
    

    return botoes;
}

const EdiSip = botao => {
    let data = $('#tableSips').DataTable().row( $(botao).parents('tr')).data()
    formSip.show();

    $("#modalFormSip .modal-title").text("Alterar operador");
    remove_required($("#formSip .required"))
    popula_dados("#formSip", data);
}

const NewSip = () => {
    document.forms["formSip"].reset()
    formSip.show();

    remove_required($("#formSip .required"))
    $("#modalFormSip .modal-title").text("Novo operador");
}

const SaveFormSip = () => {

    let form = new FormData( $("#formSip")[0] );

    $.ajax({url: `{{route()->link("save-sip")}}`,type:"POST",dataType:"json", data: form, processData: false, contentType: false})
    .done(resp => {
        SaveSipDone()
    })
}

const SaveSipDone = () => {
    GeraTabela();
    CloseModal();
    alerta("Operador salvo com sucesso.","Sucesso", "success");
}

const CloseModal = () => {
    formSip.hide();
}

const ConfirmToggleStatus = (id_sip,sip_status) => {
    let dados = {id_sip, sip_status};
    confirmaAcao("Confirma alterar status do operador?", ToggleStatusUser, dados)
}

const ToggleStatusUser = dados => {
    let {id_sip, sip_status} = dados;

    $.ajax({url: `{{route()->link("change-sip-status")}}${id_sip}/${sip_status}`})
    .done(resp => {
        alerta("Status atualizado com sucesso.", "Sucesso", "success");
        GeraTabela();
    });
}

const UpdateSipList = () => {

    $.ajax({url: `{{route()->link("get-sip-config")}}`})
    .done(resp => {
        alerta("Operadores atualizado com sucesso.", "Sucesso", "success");
        GeraTabela();
    });
}

</script>
<?php $this->captureEnd("js")?>