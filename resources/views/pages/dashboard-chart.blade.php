@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Sales</p>
                                    <h5 class="font-weight-bolder">
                                        Rp. {{$monthIncome}}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+55%</span>
                                        since yesterday
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Expense</p>
                                    <h5 class="font-weight-bolder">
                                        Rp. {{$monthExpense}}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+3%</span>
                                        since last week
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Staff</p>
                                    <h5 class="font-weight-bolder">
                                        {{$totalUser}}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-danger text-sm font-weight-bolder"></span>
                                        &nbsp
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Profit / Loss</p>
                                    <h5 class="font-weight-bolder">
                                        Rp. {{$monthProfit}}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder"></span> This month Profit / Loss
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>







        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
{{--                <div class="card z-index-2 h-100">--}}
                <div class="card z-index-2" style="height: 100% ">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Sales overview</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <div id="chart_div"></div>
                            <script>
                                google.charts.load('current', {'packages':['line', 'corechart']});
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() {

                                    var button = document.getElementById('change-chart');
                                    var chartDiv = document.getElementById('chart_div');

                                    var data = new google.visualization.DataTable();
                                    data.addColumn('date', 'Month');
                                    data.addColumn('number', "Income");
                                    data.addColumn('number', "Expense");

                                    data.addRows([
                                        [new Date(2022, 11),  {{$datj11022}},  {{$datb11022}}],
                                        [new Date(2022, 12),   {{$datj12022}},  {{$datb12022}}],
                                        [new Date(2023, 1),   {{$datj1122}},  {{$datb1122}}],
                                        [new Date(2023, 2),   {{$datj2122}},  {{$datb2122}}],
                                        [new Date(2023, 3),   {{$datj3122}},  {{$datb3122}}],
                                        [new Date(2023, 4),   {{$datj4122}},  {{$datb4122}}],
                                        [new Date(2023, 5),  {{$datj5122}},  {{$datb5122}}],
                                    ]);

                                    var materialOptions = {
                                        chart: {
                                            title: 'Average of Sales and Loss Throughout the Year'
                                        },
                                        width: 850,
                                        height: 500,
                                        series: {
                                            // Gives each series an axis name that matches the Y-axis below.
                                            0: {axis: 'Temps'},
                                            1: {axis: 'Daylight'}
                                        },
                                        axes: {
                                            // Adds labels to each axis; they don't have to match the axis names.
                                            y: {
                                                Temps: {label: 'Temps (Celsius)'},
                                                Daylight: {label: 'Daylight'}
                                            }
                                        }
                                    };

                                    var classicOptions = {
                                        title: 'Average Temperatures and Daylight in Iceland Throughout the Year',
                                        width: 900,
                                        height: 500,
                                        // Gives each series an axis that matches the vAxes number below.
                                        series: {
                                            0: {targetAxisIndex: 0},
                                            1: {targetAxisIndex: 1}
                                        },
                                        vAxes: {
                                            // Adds titles to each axis.
                                            0: {title: 'Temps (Celsius)'},
                                            1: {title: 'Daylight'}
                                        },
                                        hAxis: {
                                            ticks: [new Date(2014, 0), new Date(2014, 1), new Date(2014, 2), new Date(2014, 3),
                                                new Date(2014, 4),  new Date(2014, 5), new Date(2014, 6), new Date(2014, 7),
                                                new Date(2014, 8), new Date(2014, 9), new Date(2014, 10), new Date(2014, 11)
                                            ]
                                        },
                                        vAxis: {
                                            viewWindow: {
                                                max: 30
                                            }
                                        }
                                    };

                                    function drawMaterialChart() {
                                        var materialChart = new google.charts.Line(chartDiv);
                                        materialChart.draw(data, materialOptions);
                                        button.innerText = 'Change to Classic';
                                        button.onclick = drawClassicChart;
                                    }

                                    function drawClassicChart() {
                                        var classicChart = new google.visualization.LineChart(chartDiv);
                                        classicChart.draw(data, classicOptions);
                                        button.innerText = 'Change to Material';
                                        button.onclick = drawMaterialChart;
                                    }

                                    drawMaterialChart();

                                }

                            </script>
                        </div>







                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card card-carousel overflow-hidden h-100 p-0">

                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card ">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">Sales by Country</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center ">
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Categories</h6>
                    </div>
                    <div class="card-body p-3">


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
