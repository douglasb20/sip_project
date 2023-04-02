<?php $this->captureStart('css'); ?>
<style>

</style>
<?php $this->captureEnd('css'); ?>

<?php $this->captureStart('body'); ?>
    <section class="dashboard">
        
        <div class="col-12">
            <div class="row">
                <!-- Revenue Card -->
                <div class="col-xxl-3 col-md-6">
                  <div class="card info-card sales-card">

                    <div class="card-body">
                      <h5 class="card-title">Realizadas</h5>
    
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fa-regular fa-phone-arrow-up-right"></i>
                        </div>
                        <div class="ps-3">
                          <h6>{{$realizadas}}</h6>
                          <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>
    
                        </div>
                      </div>
                    </div>
    
                  </div>
                </div><!-- End Revenue Card -->

                                <!-- Sales Card -->
                                <div class="col-xxl-3 col-md-6">
                  <div class="card info-card customers-card">

    
                    <div class="card-body">
                      <h5 class="card-title">Ocupadas</h5>
    
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="fa-regular fa-phone-xmark "></i>
                        </div>
                        <div class="ps-3">
                          <h6>{{$busy}}</h6>
                          <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>
    
                        </div>
                      </div>
                    </div>
    
                  </div>
                </div><!-- End Sales Card -->

                <!-- Revenue Card -->
                <div class="col-xxl-3 col-md-6">
                  <div class="card info-card revenue-card">

    
                    <div class="card-body">
                      <h5 class="card-title">Atendidas </h5>
    
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="fa-regular fa-phone-volume"></i>
                        </div>
                        <div class="ps-3">
                          <h6>{{$answered}}</h6>
                          <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>
    
                        </div>
                      </div>
                    </div>
    
                  </div>
                </div><!-- End Revenue Card -->
    
                <!-- Customers Card -->
                <div class="col-xxl-3 col-xl-12">
    
                  <div class="card info-card customers-card">

    
                    <div class="card-body">
                      <h5 class="card-title">Perdidas </h5>
    
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="fa-regular fa-phone-missed"></i>
                        </div>
                        <div class="ps-3">
                          <h6>{{$no_answer}}</h6>
                          <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>
    
                        </div>
                      </div>
    
                    </div>
                  </div>
    
                </div><!-- End Customers Card -->
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-6">
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
                <div class="col-6">
                    <div class="row">
                        <div class="col-12">
                        
                            <div class="card">
            
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
            </div>
        </div>
    </section>
<?php $this->captureEnd('body'); ?>


<?php $this->captureStart('js'); ?>

<script>
    let chart = JSON.parse('{{$chart}}');
    let horas = JSON.parse('{{$horas}}');

    let porDatas = JSON.parse('{{$porDatas}}');
    let datas = JSON.parse('{{$datas}}');

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
            colors: ['#4154f1', '#2eca6a', '#ff771d'],
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
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '90%',
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
    });

</script>

<?php $this->captureEnd('js'); ?>