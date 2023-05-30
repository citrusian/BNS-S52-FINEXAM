<div class="card-footer"  style="border-radius: 0 0;">
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

    <script>
        google.charts.load('current', {'packages':['line', 'corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var chartDiv = document.getElementById('chart_div');

            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Month');
            data.addColumn('number', 'Income');
            data.addColumn('number', 'Expense');

            data.addRows([
                [new Date({{$chartYear[5]}}, {{$chartMonth[5]}}),  {{$chartJual[5]}},  {{$chartBeli[5]}}],
                [new Date({{$chartYear[4]}}, {{$chartMonth[4]}}),  {{$chartJual[4]}},  {{$chartBeli[4]}}],
                [new Date({{$chartYear[3]}}, {{$chartMonth[3]}}),  {{$chartJual[3]}},  {{$chartBeli[3]}}],
                [new Date({{$chartYear[2]}}, {{$chartMonth[2]}}),  {{$chartJual[2]}},  {{$chartBeli[2]}}],
                [new Date({{$chartYear[1]}}, {{$chartMonth[1]}}),  {{$chartJual[1]}},  {{$chartBeli[1]}}],
                [new Date({{$chartYear[0]}}, {{$chartMonth[0]}}),  {{$chartJual[0]}},  {{$chartBeli[0]}}],
            ]);

            var formatter = new google.visualization.NumberFormat({
                pattern: '#,##0.00'
            });
            formatter.format(data, 1);
            formatter.format(data, 2);

            var materialOptions = {
                chart: {
                    title: 'Average of Income and Expense Last 6 Month'
                },
                width: 850,
                height: 500,
                series: {
                    // 0: {axis: 'Income'},
                    // 1: {axis: 'Expense'}
                },
                axis: {
                    // Disable Series, it doesn't work on materialchart, only work on classic
                    y: {
                        // viewWindowMode: "explicit",
                        // viewWindow: {
                        //     min: 0,
                        //     max: 500000000
                        // }
                    },
                    x: {
                        // viewWindow: {
                        //     min: 0,
                        //     max: 500000000
                        // }
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
