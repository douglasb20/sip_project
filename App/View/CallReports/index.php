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
                <button id="btnFiltros" class="btn btn-outline-info " >Filtros</button>
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
    $('#data_de').datepicker({
        endDate: new Date(),
        todayBtn: "linked",
        todayHighlight: true
    });
    $('#data_de').datepicker().on("changeDate", function({date}){
        $('#data_ate').datepicker("setStartDate", date)
    })

    $('#data_ate').datepicker({
        endDate: new Date(),
        startDate: new Date(),
        todayBtn: "linked",
        todayHighlight: true
    });

    $("#status,#origem, #destino").select2({
        width: '100%',
        dropdownParent: $('#modalFiltros'),
        closeOnSelect: false,
    });

    $("#btnFiltros").click(() => filtros.show() );

    $("#btnFiltrar").click(function(){
        GeraTabela();
        filtros.hide()
    })
    ModalDraggable();
    GeraTabela();
})


const GeraTabela = () => {
    let form = $("#formFiltro").serializeObject();
    $.ajax({url: '{{route()->link("lista-ligacoes")}}',method:"POST", data: form})
    .done(resp => {

        $('#tableCallReports').DataTable({
            columns: [
                { data: 'calldate',         title: "Data",        className: "text-center", render: renderFormataDataHora},
                { data: 'src',              title: "Origem",      className: "text-center", render: renderSrc, orderable: false },
                { data: 'dstchannel',       title: "Destino",     className: "text-center", render: renderDst , orderable: false },
                { data: 'time_duration',    title: "Duração",     className: "text-center", orderable: false },
                { data: 'protocolo',        title: "Protocolo",   className: "text-center", orderable: false },
                { data: 'status',           title: "Status",      className: "text-center", render: renderStatus },
            ],
            data: resp,
            order: [["0", 'asc']],
            destroy: true,
            buttons: ['pageLength', exportMenu('pdf', 'excelNumber')],
        });
    })
}

const renderSrc = (data, type, {src_name}) => {
    let src = src_name === null ? data : `${data} - (${src_name})`;

    return src;
}

const renderDst = (data, type, {dst_name, dst}) => {
    let dstRender
    if(dst_name){
        dstRender = `${data} - ${dst_name}`
    }else{
        if(!data){
            dstRender = data
        }else{
            dstRender = dst
        }
    }

    return dstRender;
}

const renderStatus = (data, type, {dstchannel}) => {
    let status = "";
    if(dstchannel === ""){
        status = `<span class="badge bg-danger">Parou na URA</span>`;
    }else{
        switch(data){
            case "NO ANSWER":
                status = `<span class="badge bg-danger">Não atendida</span>`;
            break;
            case "BUSY":
                status = `<span class="badge bg-warning">Ocupada</span>`;
            break;
    
            case "ANSWERED":
                status = `<span class="badge bg-success">Atendida</span>`;
            break;
        }
    }

    return status;
}

</script>
<?php $this->captureEnd("js") ?>