<?php $this->captureStart("css") ?>
<style>
.text-title{
    font-size   : 18px;
    font-weight : 500;
    color       : #060816;
    font-family : "Poppins", sans-serif;
}

</style>

<?php $this->captureEnd("css") ?>

<?php $this->captureStart("body");
include_once "modalFiltros.php";
?>
<!-- <section class="section"> -->
    <div class="col-12 ">
        <div class="card ">
            <div class="card-header d-flex flex-row justify-content-between align-items-center">
                <span class="text-title">Relatório de ligações</span>
                <button id="btnFiltros" class="btn btn-secondary " >Filtros</button>
            </div>
            <div class="card-body mt-4">
                <div class="row table-responsive">
                    <table id="tableCallReports" class="table table-sm table-striped table-bordered w-100"></table>
                </div>
            </div>
        </div>
    </div>

<!-- </section> -->

<?php $this->captureEnd("body") ?>

<?php $this->captureStart("js") ?>
<script>

let filtros = new bootstrap.Modal("#modalFiltros", modalOption);

$(function(){
    $('#data_de,#data_ate').datepicker({
        endDate: new Date(),
        todayBtn: "linked",
        todayHighlight: true
    });
    $("#status,#origem, #destino").select2({
        width: '100%',
        dropdownParent: $('#modalFiltros'),
        closeOnSelect: false,
    })
    GeraTabela()
})

$("#btnFiltros").click(function(){
    
    filtros.show()
})

$("#btnFiltrar").click(function(){
    GeraTabela();
    filtros.hide()
})

const GeraTabela = () => {
    let form = $("#formFiltro").serializeObject();
    $.ajax({url: '{{route()->link("lista-ligacoes")}}',method:"POST", data: form})
    .done(resp => {

        $('#tableCallReports').DataTable({
            columns: [
                { data: 'calldate',         title: "Data",        className: "text-center", render: renderFormataDataHora},
                { data: 'src',              title: "Origem",      className: "text-center", render: renderSrc, orderable: false },
                { data: 'dst',              title: "Destino",     className: "text-center", render: renderDst , orderable: false },
                { data: 'time_duration',    title: "Duração",     className: "text-center", orderable: false },
                { data: 'status',           title: "Status",      className: "text-center", render: renderStatus },
            ],
            data: resp,
            order: [["0", 'asc']],
            pageLength: 20,
            destroy: true,
            lengthMenu: [[20, 50, 100, 200], [20, 50, 100, 200]],
            buttons: ['pageLength', exportMenu('pdf', 'excelNumber')],
        });
    })
}



const renderSrc = (data, type, {src_name}) => {
    let src = src_name === null ? data : `${data} - (${src_name})`;

    return src;
}

const renderDst = (data, type, {dst_name}) => {
    let dst = dst_name === null ? data : `${data} - (${dst_name})`;

    return dst;
}

const renderStatus = (data, type, row) => {
    let status = "";
    switch(data){
        case "NO ANSWER":
            status = `<button type="button" class="btn btn-danger btn-sm">Não atendida</button>`;
        break;
        case "BUSY":
            status = `<button type="button" class="btn btn-warning btn-sm">Ocupada</button>`;
        break;

        case "ANSWERED":
            status = `<button type="button" class="btn btn-success btn-sm">Atendida</button>`;
        break;
    }

    return status;
}

</script>
<?php $this->captureEnd("js") ?>