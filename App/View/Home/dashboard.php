<?php $this->captureStart('css'); ?>
<style>


</style>
<?php $this->captureEnd('css'); ?>

<?php $this->captureStart('body'); ?>

    <?php
        ob_start()
    ?>

    <div class="btn-group" role="group" aria-label="Basic outlined example ">
        <button type="button" onClick="AtualizaDadosPeriodo('today', this)" class="btn btn-outline-primary btnPeriodo active">Hoje</button>
        <button type="button" onClick="AtualizaDadosPeriodo('lastday', this)" class="btn btn-outline-primary btnPeriodo">Ontem</button>
        <button type="button" onClick="AtualizaDadosPeriodo('week', this)" class="btn btn-outline-primary btnPeriodo">Esta semana</button>
        <button type="button" onClick="AtualizaDadosPeriodo('month', this)" class="btn btn-outline-primary btnPeriodo">30 dias</button>
    </div>
    
    <?php
        $this->buttons = ob_get_contents();
        ob_end_clean();
        include_once"modalCallback.php";
    ?>

    <section class="dashboard">
        
        <div class="col-12 user-select-none">
            <div class="row">
                <div class="col-7">
                    <div class="row ">
                        <div class="col-12">
                        
                            <div class="card h-100" >
            
                                <div class="card-body ">
                                    <h5 class="card-title">Status de ligação por hora </h5>
            
                                    <!-- Line Chart -->
                                    <div id="reportsChart"></div>
                                    <!-- End Line Chart -->
            
                                </div>
            
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div>
                
            
                <div class="col-5">
                    <div class="row ">
                        <!-- Realizadas Card -->
                        <div class="col-xxl-6 col-md-6 ">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Realizadas</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa-regular fa-phone-arrow-up-right"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6 id="nmroRealizadas">{{$realizadas}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Realizadas Card -->
        
                        <!-- Ocupadas Card -->
                        <div class="col-xxl-6 col-md-6 ">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Ocupadas</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa-regular fa-phone-xmark "></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6 id="nmroOcupadas">{{$busy}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Ocupadas Card -->
        
                        <!-- Atendidas Card -->
                        <div class="col-xxl-6 col-md-6 ">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Atendidas </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa-regular fa-phone-volume" style="transform:rotate(-45deg) !important"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6 id="nmroAtendidas">{{$answered}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Atendidas Card -->
            
                        <!-- Perdidas Card -->
                        <div class="col-xxl-6 col-md-6 ">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Perdidas </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa-regular fa-phone-missed"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6 id="nmroPerdidas">{{$no_answer}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Perdidas Card -->

                        <!-- Perdidas Card -->
                        <div class="col-xxl-12 col-md-12 cursor-pointer">
                            <div id="cardRetorno" class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Em espera de retorno </h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa-regular fa-rotate-exclamation"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6 id="nmroRetorno">{{$callback}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Perdidas Card -->
        
                    </div>
                </div>
            </div>  
        </div>
        
        <div class="col-12">
            <div class="row">
                <div class="col-7">
                    <div class="row">
                        <div class="col-12">
                        
                            <div class="card h-100">
            
                                <div class="card-body">
                                    <h5 class="card-title primary-text-color">Ligações nos últimos 7 dias </h5>
            
                                    <!-- Line Chart -->
                                    <div id="columnChart"></div>
                                    <!-- End Line Chart -->
            
                                </div>
            
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div>
                <div class="col-5">
                    <div class="row">
                        <div class="col-12">
                            
                            <div class="card">
            
                                <div class="card-body">
                                    <h5 class="card-title">Ligações por setor </h5>
            
                                    <!-- Line Chart -->
                                    <div id="groupsChart" style="min-height: 400px;" class="echart"></div>
                                    <!-- End Line Chart -->
            
                                </div>
            
                            </div>
                        </div><!-- End Reports -->
                    </div>
                </div>
                
            </div>
        </div>
    </section>
<?php $this->captureEnd('body'); ?>


<?php $this->captureStart('js'); ?>

<script type="text/javascript">

    let chartColors = ['#4154f1', '#2eca6a', '#ff771d','#FFDA6F'];
    let chart          = JSON.parse('{{$chart}}');
    let horas          = JSON.parse('{{$horas}}');

    let porDatas       = JSON.parse('{{$porDatas}}');
    let datas          = JSON.parse('{{$datas}}');
    let pieChart       = JSON.parse('{{$pie}}');

    let total          = pieChart.reduce((acc, cur) => acc + cur.value, 0)
    let pieGraf        = echarts.init(document.querySelector("#groupsChart"));
    let modalRetornado = new bootstrap.Modal("#modalRetornado",modalOption);
    let grafHoras;
    let tableCallback;
    

    grafHoras = new ApexCharts(document.querySelector("#reportsChart"), {
        series: chart,
        chart: {
            height: 400,
            type: "area",
            toolbar: {
                show: true,
                tools: {
                    download  : false,
                    selection : true,
                    zoom      : true,
                    zoomin    : false,
                    zoomout   : false,
                    pan       : true,
                    reset     : true
                }
            },
        },
        markers: {
            size: 4
        },
        colors: chartColors,
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.3,
                opacityTo: 0.5,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            type: 'category',
            categories: Object.values(horas),
            labels: {
                show: true,
                format: 'dd/MM',
                formatter: function (value, timestamp) {
                    return new Date(value).toLocaleTimeString("pt-BR", {hour:'2-digit', minute:'2-digit'}) // The formatter function overrides format property
                }, 
            },
            tooltip:{
                enabled: false
            }
        },
        yaxis:{
            max: max => max + Math.round(max / 2),
            labels:{
                formatter: val => val
            }
        },
        tooltip: {
            enabled: true,
            x: {
                format: 'dd MMM',
                formatter: (value, {w, series, seriesIndex, dataPointIndex}) => {

                    return w.globals.categoryLabels[dataPointIndex];
                }
            },
            y: {
                formatter: value => value,
                title: {
                    formatter: (seriesName) => seriesName,
                },
            }
        }
    })
    grafHoras.render();

    new ApexCharts(document.querySelector("#columnChart"), {
        series: porDatas,
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: true,
                tools: {
                    download: false,
                }
            },
        },
        colors: chartColors,
        plotOptions: {
            bar: {
                horizontal: false,
                hideOverflowingLabels: true,
                columnWidth: '50%',
                endingShape: 'rounded',
                dataLabels:{
                    position: "top",
                    hideOverflowingLabels: true,
                }
            },
        },
        dataLabels: {
            enabled: true,
            offsetY: -20,
            style:{
                colors: ["#373d3f"],
                fontWeight: '200'
            },
            formatter: function (val, opts) {
                return val !== 0.05 ? val : 0
            },
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: datas,
        },
        yaxis:{
            max: max => max + 3,
            labels:{
                formatter: val => val.toFixed(0)
            }
        },
        legend:{
            show: true,
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return (val !== 0.05 ? val : 0) + " ligações"
                }
            }
        }
    }).render();

    pieGraf.setOption({
        tooltip: {
            trigger: 'item'
        },
        color: chartColors,
        legend: {
            orient: 'horizontal',
            // right: '10px',
            bottom: 'bottom'
        },
        graphic: {
            type: 'text',
            left: 'center',
            top: 'center',
            style: {
                text: `Total\r\n${total}`,
                textAlign: "center",
                font: 'bold 24px Arial',
                fill: "#4154f1"
            }
        },
        series: [
            {
                name: 'Ligações',
                type: 'pie',
                stillShowZeroSum: true,
                showEmptyCircle: false,
                radius: ['80%', "45%"],
                data: pieChart,
                label: {
                    show: true,
                    formatter: ({percent, name}) => percent > 0  ? `${percent}%` : "",
                    position: 'inside',
                    color: "#373d3f",
                    fontSize: 14,
                    fontWeight: 'bold',
                    textBorderWidth: 1,
                    textBorderColor: "#fff"
                },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    });



    // ================================ EVENTS AND ACTIONS ================================

    pieGraf.on("click", function(params){
        console.log(params)
    })

    $("#cardRetorno").click(function(){
        tableCallback = $('#tableCallback').DataTable({
            ajax: {
                url: '{{route()->link("lista-callback")}}',
                type: "GET",
                dataSrc: parserDataTable,
                async: true
            },
            columns: [
                { data: 'id',               title: "#",                 className: "text-center", orderable: false },
                { data: 'cpf_callback',     title: "CPF Cliente",       className: "text-center" },
                { data: 'numero_callback',  title: "Telefone",          className: "text-center" },
                { data: 'data_callback',    title: "Data Cadastro",     className: "text-center", render: renderFormataDataHora },
                { data: 'nome_status',      title: "Status",            className: "text-center" },
                { data: null,               title: "Ações",             className: "text-center", render: renderAcoes },
            ],
            order: [["3", 'asc']],
            pageLength: 20,
            destroy: true,
            lengthMenu: [[20, 50, 100, 200], [20, 50, 100, 200]],
            buttons: ['pageLength', exportMenu('pdf', 'excelNumber')],
        });

        let reportCallback = new bootstrap.Modal("#callbackReport",modalOption);
        reportCallback.show()
    })

    const renderAcoes = (data, type, row) => {
        let buttons = ""
        if(row.id_status_callback === "1"){
            buttons += `<button class='btn btn-primary btn-sm' onClick='AbrirModalRetornado("${row.id}")' type='button' title="Definir ligação retornada para o cliente">Retornado</button>`
        }
        return buttons;
    }

    const AbrirModalRetornado = (id_callback) => {
        
        modalRetornado.show();
        $("#idCallback").val(id_callback);
        $("#selectStatusCallback").select2({
            dropdownParent: $('#modalRetornado'),
            width: "50%",
            closeOnSelect: true,
            data: JSON.parse('{{$selectCallback}}'),
            language: 'pt-BR',
            minimumResultsForSearch: -1
        });
    }

    const confirmaSalvarStatus = () => {
        let id_callback = $("#idCallback").val()
        confirmaAcao("Confirma alterar status do retorno?", SalvaStatusRetorno, id_callback)
    }

    const SalvaStatusRetorno = async (id_callback) => {
        $.ajax({
            url: '{{route()->link("atualiza-callback")}}' ,
            method: "PUT",
            data: {
                id_callback,
                id_status_callback: $("#selectStatusCallback").val()
            }
        
        }).done(resp => {
            
            modalRetornado.hide()
            tableCallback.ajax.reload()
            alerta("Retorno atualizado com sucesso!", "", "success");
            $("#nmroRetorno").text(resp.rows)
        })
    }

    const AtualizaDadosPeriodo = (periodo, btn) => {
        
        if($(btn).hasClass("active")) return;

        $(".btnPeriodo").removeClass("active")
        $(btn).addClass("active")
        
        $.ajax({url: '{{route()->link("dados-dashboard")}}' + periodo})
        .done(resp => {
            let {no_answer, answered, busy, realizadas, pie, horas} = resp;
            $("#nmroRealizadas").text(realizadas);
            $("#nmroOcupadas").text(busy);
            $("#nmroAtendidas").text(answered);
            $("#nmroPerdidas").text(no_answer);

            total = pie.reduce((acc, cur) => acc + cur.value, 0)

            if( ["lastday", "today"].includes(periodo)){
                grafHoras.updateOptions({
                    xaxis: {
                        categories: horas.categories,
                        labels: {
                            formatter: function (value, timestamp) {
                                return new Date(value).toLocaleTimeString("pt-BR", {hour:'2-digit', minute:'2-digit'}) 
                            }
                        }
                    },
                })
                grafHoras.updateSeries(horas.series)
            }

            pieGraf.setOption({
                graphic: {
                    style: {
                        text      : `Total\r\n${total}`,
                        textAlign : "center",
                        font      : 'bold 24px Arial'
                    }
                },
                series: [
                    {
                        data: pie,
                    }
                ]
            });

        })
    }

    const VerificaCallback = () => {
        NoLoading();

        $.ajax({url: '{{route()->link("verifica-callback")}}' })
        .done(resp => {
            $("#nmroRetorno").text(resp.nmro_retorno);
            setTimeout(VerificaCallback, 10000)
        })
    }
    
    setTimeout(VerificaCallback, 10000);

</script>

<?php $this->captureEnd('js'); ?>