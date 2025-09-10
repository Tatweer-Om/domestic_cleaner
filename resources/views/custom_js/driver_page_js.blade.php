<script>
$(document).ready(function() {

    // 🔹 Reusable function to render both table rows and mobile cards
    function renderVisits(response, tbodySelector, cardsSelector, hasAction = false, emptyMessage = "No data") {
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
                renderVisits(response, '#today_driver_body', '#today_driver_cards', true, "No visits today");
            },
            error: function(xhr) {
                console.error('Error loading Today\'s Visits:', xhr.responseText);
                $('#today_driver_body').html('<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>');
                $('#today_driver_cards').html('<p class="text-center text-danger">Error loading data</p>');
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
                renderVisits(response, '#this-week-drivers-body', '#this_week_driver_cards', false, "No visits this week");
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
        responsive: true, // 📱 auto adjust for small screens
        ajax: {
            url: '{{ route("driver.visits.all", $driver->id) }}',
            dataSrc: 'aaData'
        },
        columns: [
            { data: 0 },  // S.No
            { data: 1 },  // Booking No
            { data: 2 },  // Visit Date
            { data: 3 },  // Customer
            { data: 4 },  // Location
            { data: 5 }   // Shift / Duration / Status
        ],
        language: {
            url: '{{ asset("vendor/datatables/lang/" . $locale . ".json") }}'
        }
    });

    // 🔹 Initial load
    loadTodayVisits();
    loadThisWeekVisits();

    // 🔹 Refresh on tab switch
    $('#visitsTab a').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr('data-bs-target');
        if (target === '#today-visits') {
            loadTodayVisits();
        } else if (target === '#this-week-visits') {
            loadThisWeekVisits();
        } else if (target === '#all-visits') {
            alldriversTable.ajax.reload(null, false);
        }
    });
});


</script>
