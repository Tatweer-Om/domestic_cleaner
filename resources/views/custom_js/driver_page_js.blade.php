<script>
    $(document).ready(function() {

        // 🔹 Reusable function to render both table rows and mobile cards
        function renderVisits(response, tbodySelector, cardsSelector, hasAction = false, emptyMessage = "{{ trans('messages.no_data', [], session('locale')) }}") {
            var tbody = $(tbodySelector);
            var cardsContainer = $(cardsSelector);
            tbody.empty();
            cardsContainer.empty();

            if (response.data && response.data.length > 0) {
                $.each(response.data, function(index, row) {
                    // ✅ Table row
                    tbody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.booking_no}</td>
                        <td>${row.visit_date}</td>
                        <td>${row.customer}</td>
                        <td>${row.location}</td>
                        <td>${row.shift_duration_status}</td>
                        ${hasAction ? `<td>${row.action ?? ''}</td>` : ''}
                    </tr>
                `);

                    // ✅ Mobile card
                    cardsContainer.append(`
                    <div class="card shadow-sm mb-3 border-0 rounded-3">
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-2 text-primary">
                                <i class="fas fa-receipt me-1 text-muted"></i> ${row.booking_no}
                            </h6>
                            <p class="mb-1"><i class="fas fa-calendar-check me-1 text-muted"></i> ${row.visit_date}</p>
                            <p class="mb-1"><i class="fas fa-user-tie me-1 text-muted"></i> ${row.customer}</p>
                            <p class="mb-2"><i class="fas fa-map-marker-alt me-1 text-muted"></i> ${row.location}</p>
                            <div class="d-flex flex-wrap gap-2 mb-2">${row.shift_duration_status}</div>
                            ${hasAction ? `<div class="mt-2">${row.action ?? ''}</div>` : ''}
                        </div>
                    </div>
                `);
                });
            } else {
                tbody.append(`<tr><td colspan="${hasAction ? 7 : 6}" class="text-center text-muted">${emptyMessage}</td></tr>`);
                cardsContainer.append(`<p class="text-center text-muted">${emptyMessage}</p>`);
            }
        }

        // 🔹 Load Today's Visits
        function loadTodayVisits() {
            $.ajax({
                url: '{{ route("driver.visits.today", $driver->id) }}',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    renderVisits(response, '#today_driver_body', '#today_driver_cards', true, "{{ trans('messages.no_visits_today', [], session('locale')) }}");
                },
                error: function(xhr) {
                    console.error('Error loading Today\'s Visits:', xhr.responseText);
                    $('#today_driver_body').html('<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>');
                    $('#today_driver_cards').html('<p class="text-center text-danger">Error loading data</p>');
                }
            });
        }

        function loadNext24HoursVisits() {
            $.ajax({
                url: '<?php echo route("driver.visits.next24hours", $driver->id); ?>', // 👈 new route
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    renderVisits(
                        response,
                        '#next24_driver_body', // 👈 tbody selector for next 24 hrs table
                        '#next24_driver_cards', // 👈 cards selector for next 24 hrs mobile view
                        true,
                        "<?php echo trans('messages.no_visits_next24', [], session('locale')); ?>"
                    );
                },
                error: function(xhr) {
                    console.error('Error loading Next 24 Hours Visits:', xhr.responseText);
                    $('#next24_driver_body').html('<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>');
                    $('#next24_driver_cards').html('<p class="text-center text-danger">Error loading data</p>');
                }
            });
        }


        // 🔹 Load This Week Visits
        function loadThisWeekVisits() {
            $.ajax({
                url: '{{ route("driver.visits.this_week", $driver->id) }}',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    renderVisits(response, '#this-week-drivers-body', '#this_week_driver_cards', false, "{{ trans('messages.no_visits_this_week', [], session('locale')) }}");
                },
                error: function(xhr) {
                    console.error('Error loading This Week Visits:', xhr.responseText);
                    $('#this-week-drivers-body').html('<tr><td colspan="6" class="text-center text-danger">Error loading data</td></tr>');
                    $('#this_week_driver_cards').html('<p class="text-center text-danger">Error loading data</p>');
                }
            });
        }

        // 🔹 Initialize DataTable for All Visits (Desktop only)
        var alldriversTable = $('#all_driver_visits').DataTable({
            processing: true,
            serverSide: false,
            responsive: false, // Disable responsive to prevent column hiding
            scrollX: false, // Disable horizontal scrolling
            autoWidth: false, // Disable automatic column width calculation
            ajax: {
                url: '{{ route("driver.visits.all", $driver->id) }}',
                dataSrc: 'aaData'
            },
            columns: [{
                    data: 0,
                    width: "8%",
                    className: "text-center"
                }, // S.No
                {
                    data: 1,
                    width: "15%",
                    className: "text-center"
                }, // Booking No
                {
                    data: 2,
                    width: "15%",
                    className: "text-center"
                }, // Visit Date
                {
                    data: 3,
                    width: "20%",
                    className: "text-left"
                }, // Customer
                {
                    data: 4,
                    width: "22%",
                    className: "text-left"
                }, // Location
                {
                    data: 5,
                    width: "20%",
                    className: "text-center"
                } // Shift / Duration / Status
            ],
            language: {
                url: '{{ asset("vendor/datatables/lang/" . $locale . ".json") }}'
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            order: [
                [2, 'desc']
            ], // Order by visit date descending
            drawCallback: function(settings) {
                // Ensure proper styling after draw
                $(this.api().table().node()).addClass('table-fixed');
            }
        });

        // 🔹 Initial load
        loadTodayVisits();
        loadThisWeekVisits();
        loadNext24HoursVisits();
        // 🔹 Refresh on tab switch
        $('#visitsTab a').on('shown.bs.tab', function(e) {
            var target = $(e.target).attr('data-bs-target');
            if (target === '#today-visits') {
                loadTodayVisits();
            } else if (target === '#this-week-visits') {
                loadThisWeekVisits();
            } else if (target === '#next24-visits') { // 👈 add this
                loadNext24HoursVisits();
            } else if (target === '#all-visits') {
                alldriversTable.ajax.reload(null, false);
                setTimeout(function() {
                    alldriversTable.columns.adjust().responsive.recalc();
                    alldriversTable.draw();
                }, 100);
            }
        });
    });

    function edit_driver_visit(visitId) {
        Swal.fire({
                title: '<?php echo trans('messages.mark_visit_completed', [], session('locale')); ?>',
                text: "<?php echo trans('messages.update_status_completed', [], session('locale')); ?>",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<?php echo trans('messages.confirm', [], session('locale')); ?>',
                cancelButtonText: '<?php echo trans('messages.cancel', [], session('locale')); ?>',

                reverseButtons: true,
                customClass: {
                    popup: 'swal-small-popup',
                    title: 'swal-small-title',
                    confirmButton: 'btn btn-success px-3 me-2',
                    cancelButton: 'btn btn-secondary px-3'
                },
                buttonsStyling: false
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("driver.visits.complete") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            visit_id: visitId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: '<?php echo trans('messages.visit_marked_completed', [], session('locale')); ?>',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    didClose: () => {
                                        // 🔹 reload the whole page
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire('<?php echo trans('messages.error_general', [], session('locale')); ?>', response.message ?? '<?php echo trans('messages.could_not_update_status', [], session('locale')); ?>', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Something went wrong. Try again.', 'error');
                        }
                    });
                }
            });
    }
</script>