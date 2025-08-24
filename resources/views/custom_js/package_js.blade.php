<script>

    $(document).ready(function() {
    $('#add_package_modal').on('hidden.bs.modal', function() {
                $(".add_package")[0].reset();
                $('.package_id').val('');
    });

            $('#all_package').DataTable({
                "sAjaxSource": "{{ url('show_package') }}",
                "bFilter": true,
                'pagingType': 'numbers',
                "ordering": true,
                "order": [[6, "dsc"]]
            });

        $('.add_package').off().on('submit', function (e) {
        e.preventDefault();

        var formdatas = new FormData($('.add_package')[0]);
        formdatas.append('_token', '{{ csrf_token() }}');
        var title = $('.package_name').val();
        var price_4_hours = $('.package_price_4').val();
        var price_5_hours = $('.package_price_5').val();


        var id = $('.package_id').val();


        // Validation
        if (title === "") {
            show_notification('error', '<?php echo trans('messages.add_package_name_lang',[],session('locale')); ?>');
            return false;
        }
          // Validation
        if (price_4_hours === "") {
            show_notification('error', '<?php echo trans('messages.add_package_price_4_lang',[],session('locale')); ?>');
            return false;
        }
          // Validation
        if (price_5_hours === "") {
            show_notification('error', '<?php echo trans('messages.add_package_price_5_lang',[],session('locale')); ?>');
            return false;
        }



        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_package') }}" : "{{ url('add_package') }}",
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
                $('#add_package_modal').modal('hide');
                $('#all_package').DataTable().ajax.reload();
                if (!id) $(".add_package")[0].reset();
            },
            error: function (data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_package').DataTable().ajax.reload();
                console.log(data);
            }
        });
    });

        });
        function edit(id){
            $('#global-loader').show();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax ({
                dataType:'JSON',
                url : "{{ url('edit_package') }}",
                method : "POST",
                data :   {id:id,_token: csrfToken},
                success: function(fetch) {
                    $('#global-loader').hide();
                    after_submit();
                    if(fetch!=""){

                        $(".package_name").val(fetch.package_name);
                        $(".sessions").val(fetch.sessions);
                        $(".package_price_4").val(fetch.package_price_4);
                         $(".package_price_5").val(fetch.package_price_5);
                        $(".package_type").val(fetch.package_type);
                        $('.default-select').selectpicker('refresh');
                        $(".notes").val(fetch.notes);
                        $(".package_id").val(fetch.package_id);
                        $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
                    }
                },
                error: function(html)
                {
                    $('#global-loader').hide();
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
                    $('#global-loader').show();
                    before_submit();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url('delete_package') }}",
                        type: 'POST',
                        data: {id: id,_token: csrfToken},
                        error: function () {
                            $('#global-loader').hide();
                            after_submit();
                            show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                        },
                        success: function (data) {
                            $('#global-loader').hide();
                            after_submit();
                            $('#all_package').DataTable().ajax.reload();
                            show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
                }
            });
        }



    </script>
