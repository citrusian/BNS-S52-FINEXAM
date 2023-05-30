<div class="card-footer"  style="border-radius: 0 0;">

    <script>
        const deleteButtons = document.querySelectorAll('.popupButton');

        deleteButtons.forEach((button) => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default button click behavior

                const popupButton = button.closest('form'); // Find the closest form element

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No, cancel',
                    reverseButtons: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // The user confirmed the deletion
                        // Proceed with the form submission
                        popupButton.submit(); // Submit the corresponding form
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // The user canceled the deletion
                        Swal.fire(
                            'Cancelled',
                            'Data unchanged :)',
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
        var userId = '{{ $user[0]->id }}';
    </script>

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

            // get user id from attributes
            var userId = document.querySelector('[data-userid]').getAttribute('data-userid');

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
                            formData.append('postid', userId);

                            // Perform the file upload action using AJAX
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url: '{{ route("updateuser_picture") }}',
                                method: 'POST',
                                // data: formData,
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
</div>
