<?php $this->captureStart('css'); ?>
<style>

</style>
<?php $this->captureEnd('css'); ?>

<?php $this->captureStart('body'); ?>

    <div class="col-8">
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
<?php $this->captureEnd('body'); ?>


<?php $this->captureStart('js'); ?>

<script>
    let chart = JSON.parse('{{$chart}}');
    let horas = JSON.parse('{{$horas}}');

    console.log(horas)
    console.log(chart)
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
    });

</script>

<?php $this->captureEnd('js'); ?>