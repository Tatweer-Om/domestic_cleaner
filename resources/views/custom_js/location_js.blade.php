
<script>
$(document).ready(function() {
    let omanMap, polygonLayer, polygonJson = [];

    // Define Oman cities array with Al Amarat
    const omanCities = [
        { label: "Muscat - مسقط", value: "Muscat", lat: 23.5880, lon: 58.3829 },
        { label: "Seeb - السيب", value: "Seeb", lat: 23.6703, lon: 58.1891 },
        { label: "Sohar - صحار", value: "Sohar", lat: 24.3643, lon: 56.7460 },
        { label: "Salalah - صلالة", value: "Salalah", lat: 17.0197, lon: 54.0897 },
        { label: "Nizwa - نزوى", value: "Nizwa", lat: 22.9333, lon: 57.5333 },
        { label: "Sur - صور", value: "Sur", lat: 22.5667, lon: 59.5289 },
        { label: "Ibri - عبري", value: "Ibri", lat: 23.2257, lon: 56.5157 },
        { label: "Buraimi - البريمي", value: "Buraimi", lat: 24.25, lon: 55.8 },
        { label: "Rustaq - الرستاق", value: "Rustaq", lat: 23.3908, lon: 57.4244 },
        { label: "Khasab - خصب", value: "Khasab", lat: 26.1900, lon: 56.2400 },
        { label: "Al-Mabaila - المعبيلة", value: "Al-Mabaila", lat: 23.6, lon: 58.2 },
        { label: "Al Amarat - العمارات", value: "Al Amarat", lat: 23.5127, lon: 58.5000 }
    ];

    // Function to initialize map
    function initializeMap() {
        if (!omanMap) {
            omanMap = L.map('map', {
                maxBounds: [
                    [16.5, 52.0], // Southwest coordinates (Oman)
                    [26.5, 60.0]  // Northeast coordinates (Oman)
                ],
                maxBoundsViscosity: 1.0,
                minZoom: 7
            }).setView([23.5880, 58.3829], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(omanMap);

            setTimeout(() => {
                omanMap.invalidateSize();
                console.log("Map initialized and resized");
            }, 100);
        } else {
            omanMap.invalidateSize();
            console.log("Map already initialized, resized");
        }
    }

    // Initialize map and autocomplete when modal is shown
    $('#add_location_modal').on('shown.bs.modal', function(e) {
        initializeMap();

        // Check if modal was opened via edit function
        let locationData = $(e.relatedTarget).data('location');
        if (locationData && locationData.location_polygon) {
            try {
                polygonJson = JSON.parse(locationData.location_polygon);
                if (polygonLayer) {
                    omanMap.removeLayer(polygonLayer);
                }
                polygonLayer = L.polygon(polygonJson, { color: '#3388ff', fillOpacity: 0.4 }).addTo(omanMap);
                omanMap.fitBounds(polygonLayer.getBounds());
                $('#map-status-text').text('Area Selected');
                $('#map-loading').hide();
                console.log("Polygon drawn from edit data:", polygonJson);
            } catch (err) {
                console.error('Error parsing polygon:', err);
                $('#map-status-text').text('No Area Selected');
                $('#map-loading').hide();
            }
        }

        // Initialize autocomplete
        $(".location_name").autocomplete({
            minLength: 2,
            appendTo: "#add_location_modal",
            source: function(request, response) {
                let term = request.term.toLowerCase();
                let results = omanCities.filter(city => 
                    city.label.toLowerCase().includes(term) || 
                    city.value.toLowerCase().includes(term)
                );
                console.log("Autocomplete search term:", term, "Results:", results);
                response(results);
            },
            select: function(event, ui) {
                console.log("Selected city:", ui.item);
                let selected = ui.item;
                let $input = $(this);
                $input.val(selected.value);
                console.log("Input value set to:", selected.value);
                searchLocation(selected.value);
                if (omanMap) {
                    omanMap.setView([selected.lat, selected.lon], 11);
                    console.log("Map moved to:", selected.lat, selected.lon);
                }
                return false;
            },
            focus: function(event, ui) {
                return false;
            }
        });
    });

    // Reset map and form when modal is hidden
    $('#add_location_modal').on('hidden.bs.modal', function() {
        $(".add_location")[0].reset();
        $('.location_id').val('');
        $('.location_polygon').val('');
        if (polygonLayer) {
            omanMap.removeLayer(polygonLayer);
            polygonLayer = null;
        }
        polygonJson = [];
        $(".location_name").autocomplete("destroy");
        $('#map-status-text').text('No Area Selected');
        $('#map-loading').hide();
    });

    // Handle manual input with debouncing
    let debounceTimer;
    $('.location_name').on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            let query = $(this).val();
            let exactMatch = omanCities.find(city => city.value.toLowerCase() === query.toLowerCase());
            if (!exactMatch) {
                searchLocation(query);
            }
        }, 500);
    });

    // Function to search location and fetch polygon
    function searchLocation(query) {
        if (!query) {
            $('.location_polygon').val('');
            if (polygonLayer) {
                omanMap.removeLayer(polygonLayer);
                polygonLayer = null;
            }
            polygonJson = [];
            $('#map-status-text').text('No Area Selected');
            $('#map-loading').hide();
            return;
        }

        $('#map-status-text').text('Searching...');
        $('#map-loading').show();

        fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&polygon_geojson=1&limit=1&countrycodes=OM`, {
            headers: { 'User-Agent': 'YourAppName/1.0' }
        })
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    $('#map-status-text').text('No Area Selected');
                    $('#map-loading').hide();
                    show_notification('success', '<?php echo trans('messages.location_found_lang', [], session('locale')); ?>');
                    return;
                }
                const result = data[0];
                const osmId = result.osm_id;
                const osmType = result.osm_type;

                const overpassQuery = `
                    [out:json];
                    ${osmType}(id:${osmId});
                    out geom;
                `;
                fetch('https://overpass-api.de/api/interpreter', {
                    method: 'POST',
                    body: overpassQuery
                })
                    .then(response => response.json())
                    .then(overpassData => {
                        const element = overpassData.elements[0];
                        let coords = [];

                        if (element.type === 'way') {
                            coords = element.geometry.map(pt => [pt.lat, pt.lon]);
                        } else if (element.type === 'relation') {
                            element.members.forEach(member => {
                                if (member.role === 'outer' && member.geometry) {
                                    coords.push(member.geometry.map(pt => [pt.lat, pt.lon]));
                                }
                            });
                        }

                        if (coords.length === 0) {
                            $('#map-status-text').text('No Area Selected');
                            $('#map-loading').hide();
                            show_notification('success', '<?php echo trans('messages.boundary_data_lang', [], session('locale')); ?>');
                            return;
                        }

                        polygonLayer = L.polygon(coords, { color: '#3388ff', fillOpacity: 0.4 }).addTo(omanMap);
                        omanMap.fitBounds(polygonLayer.getBounds());
                        $('#map-status-text').text('Area Selected');
                        $('#map-loading').hide();

                        polygonJson = coords;
                        $('.location_polygon').val(JSON.stringify(polygonJson));
                    })
                    .catch(err => {
                        $('#map-status-text').text('No Area Selected');
                        $('#map-loading').hide();
                        show_notification('error', '<?php echo trans('messages.boundary_fetch_failed_lang', [], session('locale')); ?>');
                        console.error('Overpass error:', err);
                    });
            })
            .catch(err => {
                $('#map-status-text').text('No Area Selected');
                $('#map-loading').hide();
                show_notification('error', '<?php echo trans('messages.geocode_failed_lang', [], session('locale')); ?>');
                console.error('Nominatim error:', err);
            });
    }

    // Edit function
    function edit(id) {
        showPreloader();
        before_submit();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            dataType: 'JSON',
            url: "{{ url('edit_location') }}",
            method: "POST",
            data: { id: id, _token: csrfToken },
            success: function(fetch) {
                hidePreloader();
                after_submit();

                if (fetch.error) {
                    show_notification('error', fetch.error);
                    return;
                }

                // Populate form fields
                $('.location_id').val(fetch.location_id);
                $('.location_name').val(fetch.location_name);
                $('.location_fare').val(fetch.location_fare);
                $('.notes').val(fetch.notes);
                $('.location_polygon').val(fetch.location_polygon);
                $("input[name='driver_status'][value='" + fetch.driver_status + "']").prop('checked', true);
                
                // Update modal title
                $('.modal-title').html('<?php echo trans('messages.update_lang', [], session('locale')); ?>');

                // Open modal and pass location data
                $('#add_location_modal').modal('show', { location: fetch });
            },
            error: function(xhr) {
                hidePreloader();
                after_submit();
                show_notification('error', '<?php echo trans('messages.edit_failed_lang', [], session('locale')); ?>');
                console.log('Edit error:', xhr);
            }
        });
    }

    // DataTable initialization
    $('#all_location').DataTable({
        "sAjaxSource": "{{ url('show_location') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
    });

    // Form submission
    $('.add_location').off('submit').on('submit', function(e) {
        e.preventDefault();

        var formdatas = new FormData(this);
        formdatas.append('_token', '{{ csrf_token() }}');
        var title = $('.location_name').val();
        var fare = $('.location_fare').val();
        var polygon = $('.location_polygon').val();

        // Validation
        if (title === "") {
            show_notification('error', '<?php echo trans('messages.add_location_name_lang', [], session('locale')); ?>');
            return false;
        }
        if (fare === "") {
            show_notification('error', '<?php echo trans('messages.add_location_fare_lang', [], session('locale')); ?>');
            return false;
        }
        if (polygon === "") {
            show_notification('error', '<?php echo trans('messages.add_location_polygon_lang', [], session('locale')); ?>');
            return false;
        }

        $.ajax({
            type: "POST",
            url: $('.location_id').val() ? "{{ url('update_location') }}" : "{{ url('add_location') }}",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(data) {
                show_notification('success', $('.location_id').val() ?
                    '<?php echo trans('messages.data_update_success_lang', [], session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_success_lang', [], session('locale')); ?>'
                );
                $('#add_location_modal').modal('hide');
                $('#all_location').DataTable().ajax.reload();
                $(".add_location")[0].reset();
            },
            error: function(data) {
                show_notification('error', $('.location_id').val() ?
                    '<?php echo trans('messages.data_update_failed_lang', [], session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang', [], session('locale')); ?>'
                );
                $('#all_location').DataTable().ajax.reload();
                console.log(data);
            }
        });
    });

    // Expose edit function globally
    window.edit = edit;
});

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
