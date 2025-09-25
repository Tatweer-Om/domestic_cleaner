<script>
    $(document).ready(function() {
        $('#add_expense_modal').on('hidden.bs.modal', function() {
            $(".add_expense")[0].reset();
            $('.expense_id').val('');
            $('.account_id').selectpicker('val', '').selectpicker('refresh');

            // Reset file input and preview
            $('#fileUpload').val(''); // clear file input
            $('#filePreview').attr('src',
                "{{ asset('images/dummy_images/cover-image-icon.png') }}"); // reset preview image
            $('#fileName').text(''); // clear file name text
            $('#removeFile').hide(); // hide remove button
        });

   $('#all_expenses').DataTable({
    "sAjaxSource": "{{ url('show_expense') }}",
    "bFilter": true,
    'pagingType': 'numbers',
    "ordering": true,
    "language": {
        "search": "{{ trans('messages.search', [], session('locale')) }}",
        "lengthMenu": "{{ trans('messages.show_entries', [], session('locale')) }}",
        "zeroRecords": "{{ trans('messages.no_matching_records', [], session('locale')) }}",
        "info": "{{ trans('messages.showing_entries', [], session('locale')) }}",
        "infoEmpty": "{{ trans('messages.no_entries', [], session('locale')) }}",
        "infoFiltered": "{{ trans('messages.filtered_from_total', [], session('locale')) }}",
        "paginate": {
            "first": "{{ trans('messages.first', [], session('locale')) }}",
            "last": "{{ trans('messages.last', [], session('locale')) }}",
            "next": "{{ trans('messages.next', [], session('locale')) }}",
            "previous": "{{ trans('messages.previous', [], session('locale')) }}"
        }
    }
});


        $('.add_expense').submit(function(e) {

            e.preventDefault();

            var formdatas = new FormData($(this)[0]);
            formdatas.append('_token', '{{ csrf_token() }}');
            var title = $('.expense_name').val();
            var fileInput = $('#fileUpload')[0]; // Get the file input element

            var id = $('.expense_id').val();
            var type = $('#expense_type').val();
            var recurring = $('#recurring_frequency').val();

            if (!type) {
                show_notification('error',
                    '{{ trans('messages.select_expense_type_first_lang', [], session('locale')) }}');
                return false;
            }

            if (type === 'fixed' && !recurring) {
                show_notification('error',
                    '{{ trans('messages.select_recurring_lang', [], session('locale')) }}');
                return false;
            }

            if (title === "") {
                show_notification('error',
                    '{{ trans('messages.add_expense_name_lang', [], session('locale')) }}');
                return false;
            }

            // Show receipt error only when creating new (id is empty/null)
            if (!id && fileInput.files.length === 0) {
                show_notification('error',
                    '{{ trans('messages.add_expense_recipt_lang', [], session('locale')) }}');
                return false;
            }



            $.ajax({
                type: "POST",
                url: id ? "{{ url('update_expense') }}" : "{{ url('add_expense') }}",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    hidePreloader();
                    after_submit();
                    show_notification('success', id ?
                        '{{ trans('messages.data_update_success_lang', [], session('locale')) }}' :
                        '{{ trans('messages.data_add_success_lang', [], session('locale')) }}'
                    );
                    $('#add_expense_modal').modal('hide');
                    $('#all_expenses').DataTable().ajax.reload();
                    if (!id) $(".add_expense")[0].reset();
                },
                error: function(data) {
                    hidePreloader();
                    after_submit();
                    show_notification('error', id ?
                        '{{ trans('messages.data_update_failed_lang', [], session('locale')) }}' :
                        '{{ trans('messages.data_add_failed_lang', [], session('locale')) }}'
                    );
                    $('#all_expenses').DataTable().ajax.reload();
                    console.log(data);
                }
            });
        });


    });


    function del(id) {
        Swal.fire({
            title: '{{ trans('messages.sure_lang', [], session('locale')) }}',
            text: '{{ trans('messages.delete_lang', [], session('locale')) }}',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '{{ trans('messages.delete_it_lang', [], session('locale')) }}',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                showPreloader();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ url('delete_expense') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    error: function() {
                        hidePreloader();
                        after_submit();
                        show_notification('error',
                            '{{ trans('messages.delete_failed_lang', [], session('locale')) }}'
                        );
                    },
                    success: function(data) {
                        hidePreloader();
                        after_submit();
                        $('#all_expenses').DataTable().ajax.reload();
                        show_notification('success',
                            '{{ trans('messages.delete_success_lang', [], session('locale')) }}'
                        );
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success',
                    '{{ trans('messages.safe_lang', [], session('locale')) }}');
            }
        });
    }


  function edit(id) {
    showPreloader();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        dataType: 'JSON',
        url: "{{ url('edit_expense') }}",
        method: "POST",
        data: {
            id: id,
            _token: csrfToken
        },
        success: function(fetch) {
            hidePreloader();
            after_submit();

            if (fetch != "") {
                // Fill input fields
                $(".expense_name").val(fetch.expense_name);
                $(".expense_date").val(fetch.expense_date);
                $(".amount").val(fetch.amount);
                $(".expense_id").val(fetch.expense_id);
                $(".notes").val(fetch.notes);

                // Set category
$(".category_id").val(fetch.category_id); // Set the value first
$('.selectpicker').selectpicker('refresh');

                // Set expense type and toggle recurring
                $("#expense_type").selectpicker('val', fetch.expense_type);
                $("#expense_type").selectpicker('refresh');
                toggleRecurringFields(fetch.expense_type);

                            if (parseInt(fetch.expense_type) === 1) {
                    $("#recurring_frequency").selectpicker('val', fetch.recurring_frequency);
                    $("#recurring_frequency").selectpicker('refresh');
                } else {
                    $("#recurring_frequency").selectpicker('val', '');
                    $("#recurring_frequency").selectpicker('refresh'); // <-- You missed this refresh
                }

                // Check/uncheck the "is_recurring" checkbox
                if (fetch.recurring_frequency !== null && fetch.recurring_frequency !== '') {
                    $('input[name="is_recurring"]').prop('checked', true);
                    $(".recurring-section").removeClass("d-none"); // Optional: show section if hidden
                } else {
                    $('input[name="is_recurring"]').prop('checked', false);
                    $(".recurring-section").addClass("d-none"); // Optional: hide section
                }

                // Set file preview
                if (fetch.expense_image && fetch.file_url) {
                    $('#filePreview')
                        .attr('src', fetch.expense_image)
                        .attr('alt', 'File Preview')
                        .on('error', function () {
                            $(this).attr('src', '{{ asset('images/dummy_images/file.png') }}');
                        })
                        .show();

                    const fileName = fetch.file_url.split('/').pop();
                    $('#fileName').html(`<a href="${fetch.file_url}" target="_blank" class="text-decoration-underline">${fileName}</a>`);
                    $('#removeFile').show();
                } else {
                    $('#filePreview').hide();
                    $('#fileName').text('');
                    $('#removeFile').hide();
                }

                // Update modal title
                $(".modal-title").html('{{ trans('messages.update_lang', [], session('locale')) }}');
            }
        },
        error: function() {
            hidePreloader();
            after_submit();
            show_notification('error', '{{ trans('messages.edit_failed_lang', [], session('locale')) }}');
        }
    });
}




    // Trigger the file input when the user clicks the image preview
    document.getElementById('filePreview').addEventListener('click', function() {
        document.getElementById('fileUpload').click(); // Triggers the hidden file input
    });

    // Handle file input change event to preview the selected file
    document.getElementById('receiptFile').addEventListener('change', function(event) {
        let file = event.target.files[0];
        let preview = document.getElementById('filePreview');
        let fileNameDisplay = document.getElementById('fileName');
        let removeButton = document.getElementById('removeFile');

        if (file) {
            let fileType = file.type;
            let fileName = file.name.toLowerCase();

            if (fileType.startsWith('image')) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result; // Show image preview
                };
                reader.readAsDataURL(file);
            } else {
                // Handle document files
                if (fileName.endsWith('.pdf')) {
                    preview.src = "{{ asset('images/dummy_images/pdf.png') }}";
                } else if (fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                    preview.src = "{{ asset('images/dummy_images/word.jpeg') }}";
                } else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                    preview.src = "{{ asset('images/dummy_images/excel.jpeg') }}";
                } else {
                    preview.src = "{{ asset('images/dummy_images/file.png') }}";
                }
            }

            // Display file name
            fileNameDisplay.textContent = file.name;

            // Show remove button
            removeButton.style.display = 'block';
        }
    });
</script>
