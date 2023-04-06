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
        <button type="button" onClick="AtualizaDadosPeriodo('month', this)" class="btn btn-outline-primary btnPeriodo">Este mês</button>
    </div>
    
    <?php
        $this->buttons = ob_get_contents();
        ob_end_clean();
    ?>

    <section class="dashboard">
        
        <div class="col-12">
            <div class="row">
                <div class="col-7">
                    <div class="row">
                        <div class="col-12">
                        
                            <div class="card">
            
                                <div class="card-body">
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
                    <div class="row h-100">
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
                            <div class="card info-card customers-card">
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
                        <div class="col-xxl-6 col-md-6 align-self-end">
                            <div class="card info-card revenue-card">
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
                        <div class="col-xxl-6 col-md-6 align-self-end">
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
                                    <h5 class="card-title">Ligações nos últimos 7 dias </h5>
            
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

<script>
    let chart    = JSON.parse('{{$chart}}');
    let horas    = JSON.parse('{{$horas}}');

    let porDatas = JSON.parse('{{$porDatas}}');
    let datas    = JSON.parse('{{$datas}}');
    let pieChart = JSON.parse('{{$pie}}');

    document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#reportsChart"), {
            series: chart,
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: true,
                    tools: {
                        download: false,
                        selection: true,
                        zoom: true,
                        zoomin: false,
                        zoomout: false,
                        pan: true,
                        reset: true 
                    }
                },
            },
            markers: {
                size: 4
            },
            colors: ['#4154f1', '#2eca6a', '#ff771d','#f9c784'],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.4,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: {
                enabled: true
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                type: 'datetime',
                categories: horas
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yyyy HH:mm'
                },
            }
        }).render();

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
            colors: ['#4154f1', '#2eca6a', '#ff771d','#f9c784'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '70%',
                    endingShape: 'rounded',
                    dataLabels:{
                        position: "top"
                    }
                },
            },
            dataLabels: {
                enabled: true,
                style:{
                    colors: ["#000"]
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
            legend:{
                show: true
            },
            yaxis: {
                title: {
                    text: 'Ligações'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " ligações"
                    }
                }
            }
        }).render();
        // new ApexCharts(document.querySelector("#groupsChart"), {
        //     series: pie.series,
        //     chart: {
        //         height: 350,
        //         type: 'pie',
        //         toolbar: {
        //             show: true
        //         }
        //     },
        //     dataLabels:{
        //         style:{
        //             fontSize: "15.5px"
        //         },
        //     },
        //     labels: pie.label
        // })
        echarts.init(document.querySelector("#groupsChart")).setOption({
            // title: {
            //     text: 'Referer of a Website',
            //     subtext: 'Fake Data',
            //     left: 'center'
            // },
            tooltip: {
                trigger: 'item'
            },
            color: ['#4154f1', '#2eca6a', '#ff771d','#f9c784'],
            legend: {
                orient: 'horizontal',
                bottom: 'bottom'
            },
            series: [
                {
                    name: 'Ligações',
                    type: 'pie',
                    radius: '80%',
                    data: pieChart,
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
    });

    const AtualizaDadosPeriodo = (periodo, btn) => {
        
        if($(btn).hasClass("active")) return;

        $(".btnPeriodo").removeClass("active")
        $(btn).addClass("active")
        
        $.ajax({url: '{{route()->link("dados-dashboard")}}' + periodo})
        .done(resp => {
            let {no_answer, answered, busy, realizadas, pie} = resp;
            $("#nmroRealizadas").text(realizadas);
            $("#nmroOcupadas").text(busy);
            $("#nmroAtendidas").text(answered);
            $("#nmroPerdidas").text(no_answer);

            echarts.init(document.querySelector("#groupsChart")).setOption({
                // title: {
                //     text: 'Referer of a Website',
                //     subtext: 'Fake Data',
                //     left: 'center'
                // },
                tooltip: {
                    trigger: 'item'
                },
                color: ['#4154f1', '#2eca6a', '#ff771d','#f9c784'],
                legend: {
                    orient: 'horizontal',
                    bottom: 'bottom'
                },
                series: [
                    {
                        name: 'Ligações',
                        type: 'pie',
                        radius: '80%',
                        data: pie,
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

        })
    }

</script>

<?php $this->captureEnd('js'); ?>