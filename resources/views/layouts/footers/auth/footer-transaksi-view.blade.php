<div class="card-footer"  style="border-radius: 0 0;">
    <script>
        $("#table-id").simplePagination({
            perPage: 15,
            currentPage: 1,
            previousButtonClass: "btn btn-primary",
            nextButtonClass: "btn btn-primary",
            paginatorAlign: "center"
        });
    </script>

    <script>
        const deleteButtons = document.querySelectorAll('.deleteButton');

        deleteButtons.forEach((button) => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default button click behavior

                const deleteForm = button.closest('form'); // Find the closest form element

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // The user confirmed the deletion
                        // Proceed with the form submission
                        deleteForm.submit(); // Submit the corresponding form
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // The user canceled the deletion
                        Swal.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        );
                    }
                });
            });
        });
    </script>

    @if(session('sweetConfirm'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'center',
                showConfirmButton: true,
                timer: 10000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                },
                customClass: {
                    background: '#000000'
                }
            });

            Toast.fire({
                icon: 'success',
                title: "{{ session('sweetConfirm') }}"
            });
        </script>
    @endif

    <script>
        document.querySelector('.select-button').addEventListener('click', async function() {
            const { value: file } = await Swal.fire({
                title: 'Select Image',
                input: 'file',
                inputAttributes: {
                    'accept': 'image/*',
                    'aria-label': 'Upload your profile picture'
                }
            });

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    Swal.fire({
                        title: 'Your selected picture',
                        imageUrl: e.target.result,
                        imageAlt: 'The selected picture',
                        showCancelButton: true,
                        confirmButtonText: 'Upload',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append('image', file);

                            // Perform the file upload action using AJAX
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url: '{{ route("profile_ppicture") }}',
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function (response) {
                                    // Handle the success response from the server
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'File uploaded successfully!'
                                    }).then(() => {
                                        // Refresh the page to load PP
                                        location.reload();
                                    });
                                },
                                error: function (xhr, status, error) {
                                    // Handle the error response from the server
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'File upload failed!',
                                        html: '<b>Status Code:</b> ' + xhr.status + '<br>' +
                                            '<b>Status Text:</b> ' + xhr.statusText + '<br>' +
                                            '<b>Error Message:</b> ' + error
                                    });
                                }
                            });
                        }
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
    function loadPaginatedContent(page) {
        $.ajax({
            url: '/your-pagination-endpoint',
            type: 'GET',
            data: { page: page },
            success: function (response) {
                // Update the table body with the new content
                $('#table-id tbody').html(response.tableContent);

                // Update the pagination links
                $('#page-nav').html(response.paginationLinks);
            },
            error: function (xhr, status, error) {
                // Handle the error response from the server
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error
                });
            }
        });
    }
    </script>

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
