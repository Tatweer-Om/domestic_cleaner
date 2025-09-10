<script>

    $(document).ready(function() {
    $('#add_location_modal').on('hidden.bs.modal', function() {
                $(".add_location")[0].reset();
                $('.location_id').val('');
    });

            $('#all_location').DataTable({
                "sAjaxSource": "{{ url('show_location') }}",
                "bFilter": true,
                'pagingType': 'numbers',
                "ordering": true,
            });

        $('.add_location').off('submit').on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_location')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var title = $('.location_name').val();
        var fare = $('.location_fare').val();

        var id = $('.location_id').val();

        // Validation
        if (title === "") {
            show_notification('error', '<?php echo trans('messages.add_location_name_lang',[],session('locale')); ?>');
            return false;
        }
         if (fare === "") {
            show_notification('error', '<?php echo trans('messages.add_location_fare_lang',[],session('locale')); ?>');
            return false;
        }



        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_location') }}" : "{{ url('add_location') }}",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function (data) {
                hidePreloader();
                after_submit();
                show_notification('success', id ?
                    '<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
                );
                $('#add_location_modal').modal('hide');
                $('#all_location').DataTable().ajax.reload();
                if (!id) $(".add_location")[0].reset();
            },
            error: function (data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_location').DataTable().ajax.reload();
                console.log(data);
            }
        });
    });

        });
        function edit(id){
            showPreloader();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax ({
                dataType:'JSON',
                url : "{{ url('edit_location') }}",
                method : "POST",
                data :   {id:id,_token: csrfToken},
                success: function(fetch) {

                    if(fetch!=""){

                        $(".location_name").val(fetch.location_name);
                        $(".location_fare").val(fetch.location_fare);
        $("input[name='driver_status'][value='" + fetch.location_available + "']").prop("checked", true);
                        $(".notes").val(fetch.notes);
                        $(".location_id").val(fetch.location_id);
                        $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
                    }
                },
                error: function(html)
                {
                 hidePreloader();

                    after_submit();
                    show_notification('error','<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');

                    return false;
                }
            });
        }


        function del(id) {
            Swal.fire({
                title:  '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
                text:  '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: '<?php echo trans('messages.delete_it_lang',[],session('locale')); ?>',
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1
            }).then(function (result) {
                if (result.value) {
                    showPreloader();
                    before_submit();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url('delete_location') }}",
                        type: 'POST',
                        data: {id: id,_token: csrfToken},
                        error: function () {
                                        hidePreloader();

                            after_submit();
                            show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                        },
                        success: function (data) {
                                        hidePreloader();

                            after_submit();
                            $('#all_location').DataTable().ajax.reload();
                            show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
                }
            });
        }



    </script>
