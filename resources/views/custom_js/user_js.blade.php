<script>
    $(document).ready(function() {
        $('#add_user_modal').on('hidden.bs.modal', function() {
            $(".add_user")[0].reset();
            $('.user_id').val('');
            $('#user_type').selectpicker('val', '').selectpicker('refresh');
            $('.permission-checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);
            $('#imageUpload').val(''); // Clear the file input

            $('#imagePreview').attr('src',
            "{{ asset('images/dummy_images/cover-image-icon.png') }}"); // Reset to default preview

            $('#removeImage').hide();

        });

        $('#all_user').DataTable({
            "sAjaxSource": "{{ url('show_user') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
            "order": [
                [6, "dsc"]
            ]
        });

        $('.add_user').off('submit').on('submit', function(e) {
            e.preventDefault();

            var formdatas = new FormData($('.add_user')[0]);
            formdatas.append('_token', '{{ csrf_token() }}');
            var title = $('.user_name').val();
            var password = $('.password').val();
            var phone = $('.phone').val();
            var id = $('.user_id').val();

            if (title === "") {
                show_notification('error', '<?php echo trans('messages.add_user_name_lang', [], session('locale')); ?>');
                return false;
            }
            if (password === "") {
                show_notification('error', '<?php echo trans('messages.provide_password_lang', [], session('locale')); ?>');
                return false;
            }
            if (phone === "") {
                show_notification('error', '<?php echo trans('messages.add_doctor_phone_lang', [], session('locale')); ?>');
                return false;
            }


            showPreloader();
            before_submit();

            $.ajax({
                type: "POST",
                url: id ? "{{ url('update_user') }}" : "{{ url('add_user') }}",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    hidePreloader();
                    after_submit();
                    show_notification('success', id ?
                        '<?php echo trans('messages.data_update_success_lang', [], session('locale')); ?>' :
                        '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>'
                    );
                    $('#add_user_modal').modal('hide');
                    $('#all_user').DataTable().ajax.reload();
                    if (!id) $(".add_user")[0].reset();
                },
                error: function(data) {
                    hidePreloader();
                    after_submit();
                    show_notification('error', id ?
                        '<?php echo trans('messages.data_update_failed_lang', [], session('locale')); ?>' :
                        '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>'
                    );
                    $('#all_user').DataTable().ajax.reload();
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
            url: "{{ url('edit_user') }}",
            method: "POST",
            data: {
                id: id,
                _token: csrfToken
            },
            success: function(fetch) {
                $('#global-loader').hide();
                after_submit();
                if (fetch != "") {

                    $(".user_name").val(fetch.user_name);
                    console.log(fetch.user_id);

                    $(".user_id").val(fetch.user_id);
                    $(".password").val(fetch.password);
                    $(".email").val(fetch.user_email);
                    $(".phone").val(fetch.user_phone);
                    $(".notes").val(fetch.notes);
                    $(".user_image").attr("src", fetch.user_image);

                    $("#user_type").val(fetch.user_type);
                    $('.default-select').selectpicker('refresh');
                    $('#checked_html').html(fetch.checked_html);

                    $(".modal-title").html('<?php echo trans('messages.update_lang', [], session('locale')); ?>');
                }
            },
            error: function(html) {
                $('#global-loader').hide();
                after_submit();
                show_notification('error', '<?php echo trans('messages.edit_failed_lang', [], session('locale')); ?>');

                return false;
            }
        });
    }


    function del(id) {
        Swal.fire({
            title: '<?php echo trans('messages.sure_lang', [], session('locale')); ?>',
            text: '<?php echo trans('messages.delete_lang', [], session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.delete_it_lang', [], session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $('#global-loader').show();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ url('delete_user') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    error: function() {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error', '<?php echo trans('messages.delete_failed_lang', [], session('locale')); ?>');
                    },
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        $('#all_user').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang', [], session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.safe_lang', [], session('locale')); ?>');
            }
        });
    }


    document.getElementById('selectAll').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
