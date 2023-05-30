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
                    confirmButtonText: 'Yes, update data!',
                    cancelButtonText: 'No, cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        popupButton.submit(); // Submit the corresponding form
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

</div>
