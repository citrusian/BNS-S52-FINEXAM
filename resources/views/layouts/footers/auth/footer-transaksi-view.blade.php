<div class="card-footer"  style="border-radius: 0 0;">
    <script>
        // pagination 1.6, doesn't work for this type of pagination
        // $(function() {
        //     $('#table-id').pagination({
        //         items: 100,
        //         itemsOnPage: 10,
        //         cssStyle: 'light-theme'
        //     });
        // });
        $("#table-id").simplePagination({
            perPage: 10,
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
                color: 54545 ,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: 'success',
                title: "{{ session('sweetConfirm') }}"
            });
        </script>
    @endif
</div>
