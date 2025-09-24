<script>
    $(document).ready(function() {
    $('#add_driver_modal').on('hidden.bs.modal', function() {
        $(".add_driver")[0].reset();
        $('.driver_id').val('');


    // Optional: Reset image preview and hide "remove" button
    $('#imagePreview').attr('src', "{{ asset('images/dummy_images/cover-image-icon.png') }}");
    $('#removeImage').hide();
    });

$('#all_drivers').DataTable({
    "sAjaxSource": "{{ url('show_driver') }}",
    "bFilter": true,
    'pagingType': 'numbers',
    "ordering": true,
    "order": [[6, "desc"]],
    "language": {
        "sEmptyTable": "{{ trans('messages.no_data_available', [], session('locale')) }}",
        "sInfo": "{{ trans('messages.showing_entries', [], session('locale')) }}",
        "sInfoEmpty": "{{ trans('messages.no_entries', [], session('locale')) }}",
        "sInfoFiltered": "{{ trans('messages.filtered_from_total', [], session('locale')) }}",
        "sLengthMenu": "{{ trans('messages.show_menu_entries', [], session('locale')) }}",
        "sLoadingRecords": "{{ trans('messages.loading', [], session('locale')) }}",
        "sProcessing": "{{ trans('messages.processing', [], session('locale')) }}",
        "sSearch": "{{ trans('messages.search', [], session('locale')) }}",
        "sZeroRecords": "{{ trans('messages.no_matching_records', [], session('locale')) }}",
        "oPaginate": {
            "sFirst": "{{ trans('messages.first', [], session('locale')) }}",
            "sLast": "{{ trans('messages.last', [], session('locale')) }}",
            "sNext": "{{ trans('messages.next', [], session('locale')) }}",
            "sPrevious": "{{ trans('messages.previous', [], session('locale')) }}"
        }
    }
});


    $('.add_driver') .off('submit').on('submit', function(e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_driver')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var name = $('.driver_name').val();
        var phone = $('.phone').val();

        var driver_user_id = $('.driver_user_id').selectpicker('val');

        var id = $('.driver_id').val();

        if (name === "") {
            show_notification('error', '<?php echo trans('messages.add_driver_name_lang',[],session('locale')); ?>');
            return false;
        }

        if (phone === "") {
            show_notification('error', '<?php echo trans('messages.add_driver_phone_lang',[],session('locale')); ?>');
            return false;
        }


          if (driver_user_id === "") {
            show_notification('error', '<?php echo trans('messages.add_driver_user_id_lang',[],session('locale')); ?>');
            return false;
        }
        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_driver') }}" : "{{ url('add_driver') }}",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(data) {
                hidePreloader();
                after_submit();
                show_notification('success', id ?
                    '<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
                );
                $('#add_driver_modal').modal('hide');
                $('#all_drivers').DataTable().ajax.reload();
                if (!id) $(".add_driver")[0].reset();
            },
            error: function(data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_drivers').DataTable().ajax.reload();
            }
        });
    });
});

function edit(id) {
    $('#global-loader').show();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        dataType: 'JSON',
        url: "{{ url('edit_driver') }}",
        method: "POST",
        data: { id: id, _token: csrfToken },
        success: function(fetch) {
            $('#global-loader').hide();
            after_submit();
            if (fetch != "") {
                $(".driver_name").val(fetch.driver_name);
                $(".driver_id").val(fetch.driver_id);
                $(".phone").val(fetch.phone);
                $(".notes").val(fetch.notes);
                $(".driver_image").attr("src", fetch.driver_image);
                  $(".driver_user_id").val(fetch.driver_user_id).trigger('change');
                $('.driver_user_id').selectpicker('refresh');
                  $(".shift").val(fetch.shift).trigger('change');
                $('.shift').selectpicker('refresh');
                    if (Array.isArray(fetch.location_id)) {
                    $(".location_id").val(fetch.location_id).trigger('change');
                    $('.location_id').selectpicker('refresh');
                }
                if(fetch.whatsapp_notification == 1){
    $('#enable_whatsapp').prop('checked', true);
} else {
    $('#enable_whatsapp').prop('checked', false);
}
                $('#checked_html').html(fetch.checked_html);
                $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
            }
        },
        error: function(html) {
            $('#global-loader').hide();
            after_submit();
            show_notification('error', '<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');
            return false;
        }
    });
}

function del(id) {
    Swal.fire({
        title: '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
        text: '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: '<?php echo trans('messages.delete_it_lang',[],session('locale')); ?>',
        confirmButtonClass: "btn btn-primary",
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {
            $('#global-loader').show();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ url('delete_driver') }}",
                type: 'POST',
                data: { id: id, _token: csrfToken },
                error: function() {
                    $('#global-loader').hide();
                    after_submit();
                    show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                },
                success: function(data) {
                    $('#global-loader').hide();
                    after_submit();
                    $('#all_drivers').DataTable().ajax.reload();
                    show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
        }
    });
}


document.addEventListener("DOMContentLoaded", function () {
    let imagePreview = document.getElementById("imagePreview");
    let imageUpload = document.getElementById("imageUpload");
    let removeImage = document.getElementById("removeImage");

    // When clicking the image, trigger the file input
    imagePreview.addEventListener("click", function () {
        imageUpload.click();
    });

    // Handle image selection
    imageUpload.addEventListener("change", function (event) {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                removeImage.style.display = "block"; // Show remove button
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle remove image
    removeImage.addEventListener("click", function () {
        imagePreview.src = "{{ asset('images/dummy_images/cover-image-icon.png') }}"; // Reset to default image
        imageUpload.value = ""; // Clear file input
        removeImage.style.display = "none"; // Hide remove button
    });
});

$(document).ready(function() {
    $('.selectpicker').selectpicker({
        selectAllText: 'Select All', // Text for "select all" option
        deselectAllText: 'Deselect All', // Text for "deselect all" option
        multipleSeparator: ', ', // Separator for selected items in the display
        maxOptions: 5, // Optional: Limit the number of selections
        selectedTextFormat: 'count > 3' // Show count if more than 3 items are selected
    });
});
</script>
