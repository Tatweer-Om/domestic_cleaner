<script>
$(document).ready(function() {
    const dtLanguage = {
        search: "{{ trans('messages.search', [], session('locale')) }}",
        lengthMenu: "{{ trans('messages.show_entries', [], session('locale')) }}",
        info: "{{ trans('messages.showing_entries', [], session('locale')) }}",
        infoEmpty: "{{ trans('messages.no_entries', [], session('locale')) }}",
        infoFiltered: "{{ trans('messages.filtered_from', [], session('locale')) }}",
        zeroRecords: "{{ trans('messages.no_matching_records', [], session('locale')) }}",
        paginate: {
            first: "{{ trans('messages.first', [], session('locale')) }}",
            last: "{{ trans('messages.last', [], session('locale')) }}",
            next: "{{ trans('messages.next', [], session('locale')) }}",
            previous: "{{ trans('messages.previous', [], session('locale')) }}"
        }
    };

    $('#all_booking').DataTable({
        "sAjaxSource": "{{ url('show_booking') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
        language: dtLanguage
    });

    $('#all_visits').DataTable({
        "sAjaxSource": "{{ url('show_visit') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
        language: dtLanguage
    });

    $('#all_booking_visits').DataTable({
        "ajax": {
            url: "{{ url('show_booking_visits') }}",
            type: "GET",
            data: function(d) {
                d.booking_id = $('#booking_id').val(); 
            }
        },
        language: dtLanguage
    });
});


    function cancel(id) {
        Swal.fire({
            title: "<?php echo trans('messages.sure_lang', [], session('locale')); ?>",
            text: "<?php echo trans('messages.cancel_lang', [], session('locale')); ?>",
            icon: "warning", // ✅ use icon instead of type (SweetAlert2)
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "<?php echo trans('messages.cancel_it_lang', [], session('locale')); ?>",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "<?php echo url('cancel_booking'); ?>",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    error: function() {
                        show_notification(
                            'error',
                            "<?php echo trans('messages.cancel_failed_lang', [], session('locale')); ?>"
                        );
                    },
                    success: function(data) {
                        $('#all_bookings').DataTable().ajax.reload();
                        show_notification(
                            'success',
                            "<?php echo trans('messages.cancel_success_lang', [], session('locale')); ?>"
                        );
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification(
                    'success',
                    "<?php echo trans('messages.safe_lang', [], session('locale')); ?>"
                );
            }
        });
    }




    $(document).ready(function() {
        $('#add_visit_modal').on('hidden.bs.modal', function() {
            $(".add_visit")[0].reset();
            $('.visit_id').val('');
        });

        $('.add_visit').submit(function(e) {

            e.preventDefault();
            var formdatas = new FormData($(this)[0]);
            formdatas.append('_token', '{{ csrf_token() }}');
            var title = $('#visit_date').val();
            var id = $('#visit_id').val();
            var duration = $('#duration').val();
            var hours = $('#hours').val();
            var worker = $('#worker_id').val();
            var shift = $('#shift').val();

            if (title === "") {
                show_notification(
                    'error',
                    "<?php echo trans('messages.add_visit_name_lang', [], session('locale')); ?>"
                );
                return false;
            }

            if (duration === "") {
                show_notification(
                    'error',
                    "<?php echo trans('messages.add_duration_lang', [], session('locale')); ?>"
                );
                return false;
            }

            if (hours === "") {
                show_notification(
                    'error',
                    "<?php echo trans('messages.add_hours_lang', [], session('locale')); ?>"
                );
                return false;
            }

            if (worker === "") {
                show_notification(
                    'error',
                    "<?php echo trans('messages.add_worker_lang', [], session('locale')); ?>"
                );
                return false;
            }

            if (shift === "") {
                show_notification(
                    'error',
                    "<?php echo trans('messages.add_shift_lang', [], session('locale')); ?>"
                );
                return false;
            }


            $.ajax({
                type: "POST",
                url: id ? "{{ url('update_visit2') }}" : "{{ url('add_visit2') }}",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status === 2) {
                        show_notification('error', 'Saving visit already exists. You cannot add another.');
                        return; // stop further execution
                    }


                    show_notification(
                        'success',
                        id ?
                        "<?php echo trans('messages.data_update_success_lang', [], session('locale')); ?>" :
                        "<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>"
                    );

                    $('#add_visit_modal').modal('hide');
                    $('#all_visits').DataTable().ajax.reload();
                    if (!id) $(".add_visit")[0].reset();
                },
                error: function(data) {

                    show_notification(
                        'error',
                        id ?
                        "<?php echo trans('messages.data_update_failed_lang', [], session('locale')); ?>" :
                        "<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>"
                    );

                    $('#all_visits').DataTable().ajax.reload();
                    console.log(data);
                }
            });
        });


        $('#apply-voucher-btn').on('click', function() {

            var btn = $(this);
            var formdatas = new FormData($('#voucher_form')[0]);
            formdatas.append('_token', "{{ csrf_token() }}");

            var voucher_code = $('#voucher-code').val();
            if (voucher_code === "") {
                show_notification(
                    'error',
                    "{{ trans('messages.provide_voucher_code_lang', [], session('locale')) }}"
                );
                return false;
            }

            formdatas.append('voucher_code', voucher_code);

            $.ajax({
                type: "POST",
                url: "{{ url('voucher_apply') }}",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log('Server response:', data);
                    console.log('Voucher amount:', data.voucher_amount, 'Type:', typeof data.voucher_amount);
                    if (data.status === 2) {
                        show_notification('error', 'Voucher Code is wrong.');
                        return;
                    }

                    show_notification(
                        'success',
                        "{{ trans('messages.data_voucher_success_lang', [], session('locale')) }}"
                    );
                    $('#voucher-code').attr('disabled', true);
                    btn.attr('disabled', true);

                    var voucher_amount = 0;
                    if (data.voucher_amount !== null && data.voucher_amount !== undefined) {
                        voucher_amount = parseFloat(String(data.voucher_amount).replace(/,/g, '')) || 0;
                    }
                    $('.total_voucher').val(voucher_amount.toFixed(2));
                    $('#voucher-discount-amount').text(voucher_amount.toFixed(2));

                    var total_amount = parseFloat($('.total_amount').val().replace(/,/g, '')) || 0;
                    var final_amount = (total_amount - voucher_amount).toFixed(2);

                    console.log('Total amount:', total_amount, 'Voucher amount:', voucher_amount, 'Final amount:', final_amount);

                    $('#total_amount').text(final_amount);
                    $('#total-amount').text(final_amount);
                    $('#voucher-discount-row').removeClass('d-none');
                },
                error: function(data) {
                    show_notification(
                        'error',
                        "{{ trans('messages.data_update_failed_lang', [], session('locale')) }}"
                    );
                    console.log(data);
                }
            });
            
            var booking_no = $('.booking_no').val();
            var total_amount = $('.total_amount').val();
            var total_voucher = $('.total_voucher').val();
            var total_discount = $('.total_discount').val();
            var voucher_code = $('#voucher-code').val();
            var payment_method = 1;
            if (!$('#accept-policy').is(':checked')) {
                show_notification('error', '<?php echo trans('messages.please_accept_privacy_policy', [], session('locale')); ?>');

                return; // Stop further execution
            }


            // Add voucher_code to FormData
            formdatas.append('booking_no', booking_no);
            formdatas.append('total_amount', total_amount);
            formdatas.append('total_voucher', total_voucher);
            formdatas.append('total_discount', total_discount);
            formdatas.append('payment_method', payment_method);
            formdatas.append('voucher_code', voucher_code);

            $.ajax({
                type: "POST",
                url: "{{ url('add_payment') }}",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response[0] == 1) {
                        var created_at = response[2];

                        if (created_at) {
                            let createdTime = new Date(created_at).getTime(); // Convert to milliseconds
                            let expiryTime = createdTime + 10 * 60 * 1000; // Add 10 minutes
                            // console.log(expiryTime);
                            // startCountdown(expiryTime);
                        } else {
                            document.getElementById("timer").innerHTML = "Invalid Time!";
                        }
                        var paymentConfig = response[1];
                        SmartBox.Checkout.configure = {
                            MID: paymentConfig.MID,
                            TID: paymentConfig.TID,
                            CurrencyId: paymentConfig.CurrencyId,
                            AmountTrxn: paymentConfig.AmountTrxn,
                            MerchantReference: paymentConfig.MerchantReference,
                            LanguageId: paymentConfig.LanguageId,
                            TrxDateTime: paymentConfig.RequestDateTime,
                            SessionToken: paymentConfig.SessionToken,
                            SecureHash: paymentConfig.SecureHash,
                            PaymentViewType: "1",
                            // Callback functions
                            completeCallback: function(data) {
                                // Extract your variables
                                const hostTransactionId = data.data.data.hostResponseData.TransactionId;
                                const transactionId = data.data.data.transactionId;
                                const responseCode = data.data.responseCode;
                                console.log(data);
                                // Create a query string with URL-encoded values
                                const queryString = `?hostTransactionId=${encodeURIComponent(hostTransactionId)}&transactionId=${encodeURIComponent(transactionId)}&responseCode=${encodeURIComponent(responseCode)}`;

                                // Redirect to the payment_success page with the query string
                                window.location = "{{ route('PaymentCompletew') }}" + queryString;

                            },
                            errorCallback: function(data) {
                                console.log("Payment error:", data);
                                window.location = "{{ route('PaymentError') }}";
                                // alert("Payment error occurred. Please try again.");
                            },
                            cancelCallback: function() {
                                console.log("Payment cancelled");
                                window.location = "{{ route('PaymentError') }}";
                                // alert("Payment has been cancelled.");
                            }
                        };
                        console.log("SmartBox configuration:", SmartBox.Checkout.configure);

                        SmartBox.Checkout.showSmartBox();

                    } else if (res[0] == 2) {
                        window.location = "{{ route('PaymentError') }}";
                    }
                },
                error: function(data) {
                    show_notification(
                        'error',
                        "<?php echo trans('messages.data_update_failed_lang', [], session('locale')); ?>"
                    );
                    console.log(data);
                }

            });
        });



    });

    function startCountdown(expiryTime) {
        let timerElement = document.getElementById("timer");
        $('#timer_div').show()

        localStorage.setItem("expiryTime", expiryTime); // Save expiry time in localStorage

        let interval = setInterval(() => {
            let now = new Date().getTime();
            let timeLeft = expiryTime - now;
            console.log(timeLeft)

            if (timeLeft <= 0) {
                clearInterval(interval);
                timerElement.innerHTML = "انتهى الوقت!";
                $('#expire-order-modal').modal('show');
                // setTimeout(function() {
                //     window.location = "{{ route('PaymentError') }}";
                // }, 2000);
                return;
            }

            let minutes = Math.floor(timeLeft / (1000 * 60));
            let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            timerElement.innerHTML = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
        }, 1000);
    }



    function edit_visit(id) {
        $('#global-loader').show();

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            dataType: 'JSON',
            url: "{{ url('edit_visit2') }}",
            method: "POST",
            data: {
                id: id,
                _token: csrfToken
            },
            success: function(fetch) {
                if (fetch != "") {
                    $("#booking_id").val(fetch.booking_id);
                    $('#booking_id').selectpicker('refresh');
                    $("#worker_id").val(fetch.worker_id);
                    $('#worker_id').selectpicker('refresh');
                    $("#shift").val(fetch.shift);
                    $('#shift').selectpicker('refresh');
                    $("#duration").val(fetch.duration);
                    $('#duration').selectpicker('refresh');
                    $(".visit_date").val(fetch.visit_date);
                    $(".visit_id").val(fetch.visit_id);
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

    function edit_condition(id) {
        $('#global-loader').show();

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            dataType: 'JSON',
            url: "{{ url('edit_condition') }}",
            method: "POST",
            data: {
                id: id,
                _token: csrfToken
            },
            success: function(fetch) {
                if (fetch != "") {

                    $("#condition_type").val(fetch.condition_type);
                    $('#condition_type').selectpicker('refresh');
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

    // Open modal and load existing condition (if id provided)


    // Submit handler: save (create/update)
    $(document).on('submit', '.add_condition', function(e) {
        e.preventDefault();
        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true);

        const fd = new FormData(this);
        fd.append('mode', 'save');

        $.ajax({
            url: "<?php echo url('condition'); ?>",
            method: "POST",
            data: fd,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.ok) {
                    show_notification(
                        'success',
                        res.message || "<?php echo trans('messages.saved_successfully', [], session('locale')); ?>"
                    );
                    $('#add_condition_modal').modal('hide');
                    // TODO: refresh your table/list if needed
                    // reloadConditionsTable();
                } else {
                    show_notification(
                        'error',
                        res.message || "<?php echo trans('messages.save_failed_lang', [], session('locale')); ?>"
                    );
                }
            },
            error: function(xhr) {
                // If using Laravel validation, show first error
                let msg = "<?php echo trans('messages.save_failed_lang', [], session('locale')); ?>";
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                show_notification('error', msg);
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });




    function del(id) {
        Swal.fire({
            title: '<?php echo trans('messages.sure_lang', [], session('locale')); ?>',
            text: '<?php echo trans('messages.delete_lang', [], session('locale')); ?>',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.delete_it_lang', [], session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ url('delete_visit') }}",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    error: function() {
                        $('#global-loader').hide();
                        show_notification('error', '<?php echo trans('messages.delete_failed_lang', [], session('locale')); ?>');
                    },
                    success: function(data) {
                        $('#global-loader').hide();
                        $('#all_visits').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang', [], session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.safe_lang', [], session('locale')); ?>');
            }
        });
    }
</script>