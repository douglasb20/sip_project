<?php $this->captureStart("css") ?>
<?php $this->captureEnd("css") ?>

<?php $this->captureStart("body") ?>
<!-- <section class="section"> -->
    <div class="col-12 ">
        <div class="card ">
            <div class="card-body ">
                <h5 class="card-title">Relatório</h5>
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
$(function(){

    GeraTabela()
})

const GeraTabela = () => {
    $.ajax({url: '{{route()->link("lista-ligacoes")}}'}).done(resp => {

        tabelaPrincipal = $('#tableCallReports').DataTable({
                            columns: [
                                { data: 'calldate',         title: "Data",        className: "text-center", orderable: false },
                                { data: 'src',              title: "Origem",      className: "text-center", render: renderSrc, orderable: false },
                                { data: 'dst',              title: "Destino",     className: "text-center", render: renderDst , orderable: false },
                                { data: 'time_duration',    title: "Duração",     className: "text-center", orderable: false },
                                { data: 'status',           title: "Status",      className: "text-center", render: renderStatus },
                            ],
                            data: resp,
                            order: [["3", 'asc']],
                            pageLength: 20,
                            destroy: true,
                            dom: 'Bfrtip',
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
    
    return data;
}

</script>
<?php $this->captureEnd("js") ?>