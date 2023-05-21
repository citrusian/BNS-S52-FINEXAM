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
                [new Date(2022, 11),  {{$datj11022}},  {{$datb11022}}],
                [new Date(2022, 12),   {{$datj12022}},  {{$datb12022}}],
                [new Date(2023, 1),   {{$datj1122}},  {{$datb1122}}],
                [new Date(2023, 2),   {{$datj2122}},  {{$datb2122}}],
                [new Date(2023, 3),   {{$datj3122}},  {{$datb3122}}],
                [new Date(2023, 4),   {{$datj4122}},  {{$datb4122}}],
                [new Date(2023, 5),  {{$datj5122}},  {{$datb5122}}],
            ]);

            var formatter = new google.visualization.NumberFormat({
                pattern: '#,##0.00'
            });
            formatter.format(data, 1);
            formatter.format(data, 2);

            var materialOptions = {
                chart: {
                    title: 'Average of Income and Expense Last 8 Month'
                },
                width: 850,
                height: 500,
                series: {
                    0: {axis: 'Income'},
                    1: {axis: 'Expense'}
                },
                axes: {
                    y: {
                        Income: {label: 'Million (Rupiah)'},
                        Expense: {label: 'Million'}
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
