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
                                    <h5 class="font-weight-bolder" style="color: #40a603">
                                        Rp. {{$monthIncome}}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder"></span>
                                        This Month
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
                                    <h5 class="font-weight-bolder" style="color: #e30000">
                                        Rp. {{$monthExpense}}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder"></span>
                                        This Month
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
                                    <h5 class="font-weight-bolder" style="color: #262626">
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
                                    <h5 class="font-weight-bolder" style="color: {{ $lossStatus === 0 ? '#40a603' : '#e30000' }}">
                                        {{ $monthProfit }}
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
            <div class="col-lg-7 mx-auto">
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
                                            title: 'Average of Income and Expense Throughout the Year'
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
                                                Temps: {label: 'Million (Rupiah)'},
                                                Daylight: {label: 'Million'}
                                            }
                                        }
                                    };

                                    function drawMaterialChart() {
                                        var materialChart = new google.charts.Line(chartDiv);
                                        materialChart.draw(data, materialOptions);
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
                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                                ['Vendor', 'Hours per Day'],
                                ['Acer',      {{$Acer}}],
                                ['Apple',     {{$Apple}}],
                                ['Asus',      {{$Asus}}],
                                ['Dell',      {{$Dell}}],
                                ['HP',        {{$HP}}],
                                ['Lenovo',    {{$Lenovo}}],
                                ['Other',     {{$Other}}]
                            ]);
                            var options = {
                                title: 'Available Stock'
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                            chart.draw(data, options);
                        }
                    </script>
{{--                    <div id="piechart" style="width: 900px; height: 600px;"></div>--}}
                    <div id="piechart" style="width: 900px; height: 600px;margin-right: 0; margin-left: 0;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
