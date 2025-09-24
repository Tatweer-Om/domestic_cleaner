<script>
    $(function() {
        // Optional: a tiny loader while fetching
        const loader = '<div class="text-center py-5"><div class="spinner-border" role="status"></div></div>';
        $('#servicesContainer').html(loader);

        $.ajax({
                url: "{{ route('worker_section') }}", // or: "{{ url('service_section') }}"
                type: 'GET',
                dataType: 'html'
            })
            .done(function(html) {
                $('#servicesContainer').html(html);

                // If you’re using WOW animations
                if (typeof WOW !== 'undefined') {
                    new WOW().init();
                }



                // If you’re using Slick instead, comment Owl above and use this:
                if ($('.service-slider').length && typeof $.fn.slick !== 'undefined') {
                    $('.service-slider').slick({
                        arrows: true,
                        slidesToShow: 3,

                        slidesToScroll: 1,
                        dots: true,
                        arrows: true,
                        responsive: [{
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 3
                                }
                            },
                            {
                                breakpoint: 992,
                                settings: {
                                    slidesToShow: 2
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    slidesToShow: 1
                                }
                            }
                        ]
                    });
                }

            })
            .fail(function() {
                $('#servicesContainer').html(
                    '<div class="alert alert-danger m-0"><?php echo trans('messages.failed_to_load_services', [], session('locale')); ?></div>'
                );
            });
    });

    document.addEventListener('DOMContentLoaded', function() {
        fetch("{{ route('worker_list') }}")
            .then(res => res.json())
            .then(data => {
                if (data.worker_list) {
                    document.getElementById('workerListContainer').innerHTML = data.worker_list;
                    // If you rely on WOW.js animations, re-init after injection:
                    if (typeof WOW !== 'undefined') {
                        new WOW().init();
                    }
                }
            })
            .catch(err => console.error('workers.list error:', err));
    });




    $(function() {
        // Endpoints
        const REGISTER_URL = "{{ route('register.ajax') }}";
        const LOGIN_URL = "{{ route('login.ajax') }}"; // add this route in web.php

        // CSRF header for Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const $form = $('#authForm');
        const $header = $('#formHeader');
        const $toggleLink = $('#toggleLink');
        const $phoneBlk = $('#nameField'); // block with the phone input (signup only)
        const $user = $('#user_name'); // will act as identifier in login mode
        const $pass = $('#password');
        const $phone = $('#phone'); // ensure input has id="phone" name="phone"
        const $submit = $('#submitBtn');

        // Your helpers (safe fallbacks)
        window.show_notification = window.show_notification || function(t, m) {
            console.log(t.toUpperCase() + ': ' + m);
        };
        window.showPreloader = window.showPreloader || function() {};
        window.hidePreloader = window.hidePreloader || function() {};
        window.before_submit = window.before_submit || function() {};
        window.after_submit = window.after_submit || function() {};

        let mode = 'login'; // 'login' | 'signup'

        function setMode(next) {
            mode = next;
            if (mode === 'signup') {
                $header.text("<?php echo trans('messages.create_an_account', [], session('locale')); ?>");
                $submit.text("<?php echo trans('messages.create_account', [], session('locale')); ?>");
                $toggleLink.text("<?php echo trans('messages.have_account_sign_in', [], session('locale')); ?>");
                $phoneBlk.show();
                $user.attr('placeholder', "<?php echo trans('messages.enter_username', [], session('locale')); ?>");
            } else {
                $header.text("<?php echo trans('messages.sign_in_to_manage_bookings', [], session('locale')); ?>");
                $submit.text("<?php echo trans('messages.sign_in', [], session('locale')); ?>");
                $toggleLink.text("<?php echo trans('messages.create_account', [], session('locale')); ?>");
                $phoneBlk.hide();
                $user.attr('placeholder', "<?php echo trans('messages.enter_username_or_phone', [], session('locale')); ?>");
            }
            $user.trigger('focus');
        }
        setMode('login');

        $toggleLink.on('click', function(e) {
            e.preventDefault();
            setMode(mode === 'login' ? 'signup' : 'login');
        });

        function setSubmitting(busy) {
            $submit.prop('disabled', busy);
            $submit.data('orig', $submit.data('orig') || $submit.text());
            $submit.text(
                busy ?
                "<?php echo trans('messages.please_wait', [], session('locale')); ?>" :
                $submit.data('orig')
            );
        }

        // Main submit handler (adapted from your register AJAX)
        $(document).on('submit', '#authForm', function(e) {
            e.preventDefault();

            const identifierOrUsername = ($user.val() || '').trim();
            const password = ($pass.val() || '').trim();
            const phoneVal = (($phone?.val() || '') + '').trim();
            const form_1 = $form.find('input[name="form_1"]').val() || '1'; // fallback to 1


            setSubmitting(true);

            if (mode === 'signup') {
                // Client-side checks
                if (!identifierOrUsername) {
                    show_notification(
                        'error',
                        "<?php echo trans('messages.please_enter_username', [], session('locale')); ?>"
                    );
                    done();
                    return;
                }
                if (!phoneVal) {
                    show_notification(
                        'error',
                        "<?php echo trans('messages.please_enter_mobile_number', [], session('locale')); ?>"
                    );
                    done();
                    return;
                }
                if (!password) {
                    show_notification(
                        'error',
                        "<?php echo trans('messages.please_enter_password', [], session('locale')); ?>"
                    );
                    done();
                    return;
                }

                // Build FormData from this form (your original pattern)
                const fd = new FormData(this);
                // Ensure names match backend expectations
                fd.set('user_name', identifierOrUsername);
                fd.set('phone', phoneVal);
                fd.set('password', password);
                fd.append('form_1', form_1);

                $.ajax({
                    url: REGISTER_URL,
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status === 'success') {
                            show_notification('success', res.message || '<?php echo trans('messages.registered', [], session('locale')); ?>');
                            location.reload();
                            setMode('login');
                            // If you still have tabs, you can also trigger the login tab:
                            // $('#tabLogin').trigger('click');
                            $user.val(identifierOrUsername);
                            $pass.val('').trigger('focus');
                        } else {
                            show_notification('error', res.message || '<?php echo trans('messages.registeration_failed', [], session('locale')); ?>');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            const firstKey = Object.keys(errors)[0];
                            show_notification('error', errors[firstKey][0]);
                        } else {
                            show_notification('error', '<?php echo trans('messages.server_error', [], session('locale')); ?>');
                        }
                    },
                    complete: done
                });

                return;
            }

            // mode === 'login'
            if (!identifierOrUsername) {
                show_notification(
                    'error',
                    "<?php echo trans('messages.enter_username_or_phone', [], session('locale')); ?>"
                );
                done();
                return;
            }
            if (!password) {
                show_notification(
                    'error',
                    "<?php echo trans('messages.enter_password_field', [], session('locale')); ?>"
                );
                done();
                return;
            }

            // Send as simple form fields; backend reads identifier+password
            $.ajax({
                url: LOGIN_URL,
                method: 'POST',
                data: {
                    identifier: identifierOrUsername,
                    password: password,
                    form_1: form_1 // ✅ add this
                },
                success: function(res) {
                    if (res.status === 'success' || res.ok === true) {
                        show_notification('success', res.message || '<?php echo trans('messages.signed_in', [], session('locale')); ?>');
                        if (res.redirect_url) {
                            window.location.href = res.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    } else {
                        show_notification('error', res.message || '<?php echo trans('messages.login_failed', [], session('locale')); ?>');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        const errors = xhr.responseJSON.errors;
                        const firstKey = Object.keys(errors)[0];
                        show_notification('error', errors[firstKey][0]);
                    } else if (xhr.responseJSON?.message) {
                        show_notification('error', xhr.responseJSON.message);
                    } else {
                        show_notification('error', '<?php echo trans('messages.server_error', [], session('locale')); ?>');
                    }
                },
                complete: done
            });

            function done() {
                hidePreloader();
                after_submit();
                setSubmitting(false);
            }
        });
    });






    jQuery(function($) {
        // Initialize Bootstrap Select picker for location dropdown
        if ($.fn.selectpicker) {
            $("#filter_location").selectpicker();
        }

        // Initialize Slick Slider for workers slider



        // Initialize slider on page load
        initServiceSlider_Slick();

        // Initialize WOW.js for animations
        if (window.WOW) {
            new WOW().init();
        }

        // Function to update slider content via AJAX
        function waitForImages($scope, timeoutMs = 1000) {
            return new Promise(resolve => {
                const $imgs = $scope.find("img");
                if ($imgs.length === 0) return resolve();

                let done = 0,
                    settled = false;
                const finish = () => {
                    if (!settled) {
                        settled = true;
                        resolve();
                    }
                };
                const onOne = () => {
                    if (++done >= $imgs.length) finish();
                };

                const t = setTimeout(finish, timeoutMs); // fallback
                $imgs.each(function() {
                    if (this.complete) return onOne();
                    $(this).one("load error", onOne);
                });
            });
        }

        function updateSlider() {
            const locationId = $("#filter_location").val() || "";
            const $slider = $("#workers_slider");

            $.ajax({
                url: "{{ route('worker.slides') }}",
                type: "GET",
                data: {
                    location_id: locationId
                },
                cache: false,
                beforeSend: function() {
                    // Destroy slick if initialized
                    if ($slider.hasClass("slick-initialized")) {
                        $slider.slick("unslick");
                    }
                    // Reserve height to prevent jump
                    $slider.css("min-height", $slider.outerHeight() + "px")
                        .css("opacity", 0.3)
                        .html('<div class="py-4 text-center"><?php echo trans('messages.loading', [], session('locale')); ?></div>');
                },
                success: async function(res) {
                    // Replace inner content

                    $slider.html(res);

                    // Wait until images are ready
                    await waitForImages($slider, 1200);

                    // Init slick again
                    initServiceSlider_Slick();

                    // Force slick to recalc sizes
                    $slider.slick("setPosition");

                    // Re-init WOW (singleton)
                    if (window.WOW) {
                        if (!window._wow) window._wow = new WOW();
                        window._wow.init();
                    }

                    // Smooth fade-in + clear min-height
                    $slider.animate({
                        opacity: 1
                    }, 250, () => {
                        $slider.css("min-height", "");
                    });
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.responseText || xhr.statusText);
                    $slider.css({
                            opacity: 1,
                            "min-height": ""
                        })
                        .html('<div class="alert alert-danger"><?php echo trans('messages.failed_to_load_workers', [], session('locale')); ?></div>');
                }
            });
        }

        // Unbind previous handlers to prevent duplicates, then bind namespaced event
        $(document).off('change.filterLocation', '#filter_location');
        $(document).on('change.filterLocation', '#filter_location', updateSlider);
    });

    $(document).on('click', '.wpo-service-link', function(e) {
        const locationBox = $('#filter_location');
        const locationId = locationBox.val();

        // Remove old error states
        locationBox.removeClass('is-invalid');
        $('#location-error').remove();

        if (!locationId) {
            e.preventDefault();
            show_notification('error', "<?php echo trans('messages.please_select_location_first', [], session('locale')); ?>");
            // 🔴 Add Bootstrap-style red border
            locationBox.addClass('is-invalid');

            // 🔴 Append error message under the select
            locationBox.after('<small id="location-error" class="text-danger"><?php echo trans('messages.please_select_location_first', [], session('locale')); ?></small>');

            return false;
        }
    });



    function loadServices() {
        $.ajax({
            url: "{{ url('service_section') }}", // Your Laravel route
            type: "GET",
            success: function(response) {
                $("#service_show").html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error loading services:", error);
                $("#service_show").html(
                    '<div class="col-12 text-center text-danger"<?php echo trans('messages.server_error', [], session('locale')); ?></div>'
                );
            }
        });
    }

    // Load services when the page is ready
    $(document).ready(function() {
        loadServices();
    });


    // const map = L.map('map').setView([21.5, 55.9], 7);
    // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    // }).addTo(map);

    // let isEnglish = true;
    // let currentLang = 'en';
    // const suggestionsDiv = document.getElementById('suggestions');

    // function toggleLanguage() {
    //     isEnglish = !isEnglish;
    //     currentLang = isEnglish ? 'en' : 'ar';
    //     document.querySelector('.lang-toggle').textContent = isEnglish ? 'Switch to Arabic' : 'Switch to English';
    //     document.getElementById('search').placeholder = isEnglish ? 'Enter location in Oman' : 'أدخل الموقع في عمان';
    //     suggestionsDiv.style.display = 'none'; // Hide suggestions when language changes
    // }

    // // Debounce function to limit API calls
    // function debounce(func, wait) {
    //     let timeout;
    //     return function executedFunction(...args) {
    //         const later = () => {
    //             clearTimeout(timeout);
    //             func(...args);
    //         };
    //         clearTimeout(timeout);
    //         timeout = setTimeout(later, wait);
    //     };
    // }

    // // Search with dropdown suggestions
    // const fetchSuggestions = debounce(async function(query) {
    //     if (!query) {
    //         suggestionsDiv.style.display = 'none';
    //         return;
    //     }

    //     try {
    //         const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=OM&limit=5&accept-language=${currentLang}`);
    //         const data = await response.json();

    //         suggestionsDiv.innerHTML = '';
    //         if (data && data.length > 0) {
    //             data.forEach(item => {
    //                 console.log(item.lon)
    //                 console.log(item.lat)
    //                 const div = document.createElement('div');
    //                 div.className = 'suggestion-item';
    //                 div.textContent = item.display_name;
    //                 div.onclick = () => {
    //                     document.getElementById('search').value = item.display_name;
    //                     suggestionsDiv.style.display = 'none';
    //                     map.setView([parseFloat(item.lat), parseFloat(item.lon)], 12);

    //                     L.marker([parseFloat(item.lat), parseFloat(item.lon)]).addTo(map)
    //                         .bindPopup(item.display_name)
    //                         .openPopup();

    //                     document.getElementById('status').textContent = `Found: ${item.display_name}`;

    //                     // Round to 2 decimals
    //                     const lat = parseFloat(item.lat).toFixed(2);
    //                     const lon = parseFloat(item.lon).toFixed(2);

    //                     const googleLink = `https://www.google.com/maps?q=${lat},${lon}`;
    //                     const osmLink = `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lon}#map=12/${lat}/${lon}`;

    //                     sendLocationToBackend(item.display_name, lat, lon, googleLink, osmLink);
    //                 };
    //                 suggestionsDiv.appendChild(div);
    //             });
    //             suggestionsDiv.style.display = 'block';
    //         } else {
    //             suggestionsDiv.style.display = 'none';
    //         }
    //     } catch (error) {
    //         console.error('Error fetching suggestions:', error);
    //     }
    // }, 300);

    // async function searchLocation() {
    //     const query = document.getElementById('search').value.trim();
    //     if (!query) {
    //         document.getElementById('status').textContent = 'Please enter a location.';
    //         suggestionsDiv.style.display = 'none';
    //         return;
    //     }

    //     const statusDiv = document.getElementById('status');
    //     statusDiv.textContent = 'Searching...';

    //     try {
    //         const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=OM&limit=1&accept-language=${currentLang}`);
    //         const data = await response.json();

    //         if (data && data.length > 0) {
    //             const lat = parseFloat(data[0].lat);
    //             const lon = parseFloat(data[0].lon);
    //             map.setView([lat, lon], 12);
    //             L.marker([lat, lon]).addTo(map)
    //                 .bindPopup(data[0].display_name)
    //                 .openPopup();
    //             statusDiv.textContent = `Found: ${data[0].display_name}`;
    //         } else {
    //             statusDiv.textContent = 'Location not found in Oman.';
    //         }
    //         suggestionsDiv.style.display = 'none';
    //     } catch (error) {
    //         statusDiv.textContent = 'Search error. Please try again.';
    //         console.error(error);
    //     }
    // }


    // let locationData = null;

    // function getCurrentLocation() {
    //     if (!navigator.geolocation) {
    //         document.getElementById('status').textContent = 'Geolocation not supported.';
    //         return;
    //     }

    //     document.getElementById('status').textContent = 'Getting location...';

    //     navigator.geolocation.getCurrentPosition(
    //         async (position) => {
    //                 const lat = parseFloat(position.coords.latitude).toFixed(2);
    //                 const lon = parseFloat(position.coords.longitude).toFixed(2);
    //                 let address = 'Current Location';

    //                 // Reverse geocode to get address
    //                 try {
    //                     const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1&accept-language=${currentLang}&countrycodes=OM`);
    //                     const data = await response.json();
    //                     address = data.display_name || 'Current Location';
    //                 } catch (error) {
    //                     console.error('Error reverse geocoding:', error);
    //                     document.getElementById('status').textContent = 'Error getting address, showing coordinates.';
    //                 }

    //                 // Populate search input
    //                 document.getElementById('search').value = address;

    //                 // Generate links
    //                 const googleLink = `https://www.google.com/maps?q=${lat},${lon}`;
    //                 const osmLink = `https://www.openstreetmap.org/?mlat=${lat}&mlon=${lon}#map=12/${lat}/${lon}`;

    //                 // Store in locationData
    //                 locationData = {
    //                     address,
    //                     lat,
    //                     lon,
    //                     googleLink,
    //                     osmLink
    //                 };

    //                 // Update map
    //                 map.setView([lat, lon], 12);
    //                 L.marker([lat, lon]).addTo(map)
    //                     .bindPopup(address)
    //                     .openPopup();

    //                 // Update status
    //                 document.getElementById('status').textContent = `Current location: ${address} (Click Confirm to save)`;

    //                 // Enable confirm button
    //                 const confirmButton = document.getElementById('confirm_location');
    //                 if (confirmButton) confirmButton.disabled = false;

    //                 // Optional: Trigger suggestions
    //                 fetchSuggestions(address);
    //             },
    //             (error) => {
    //                 let msg = 'Error getting location: ';
    //                 switch (error.code) {
    //                     case error.PERMISSION_DENIED:
    //                         msg += 'Permission denied.';
    //                         break;
    //                     case error.POSITION_UNAVAILABLE:
    //                         msg += 'Position unavailable.';
    //                         break;
    //                     case error.TIMEOUT:
    //                         msg += 'Timeout.';
    //                         break;
    //                     default:
    //                         msg += 'Unknown error.';
    //                 }
    //                 document.getElementById('status').textContent = msg;
    //                 // Ensure locationData is not accessed here
    //             }, {
    //                 enableHighAccuracy: true,
    //                 timeout: 10000,
    //                 maximumAge: 60000
    //             }
    //     );
    // }

    // // Input event listener for suggestions
    // document.getElementById('search').addEventListener('input', function(e) {
    //     fetchSuggestions(e.target.value);
    // });

    // // Enter key for search
    // document.getElementById('search').addEventListener('keypress', function(e) {
    //     if (e.key === 'Enter') {
    //         searchLocation();
    //     }
    // });

    // // Hide suggestions when clicking outside
    // document.addEventListener('click', function(e) {
    //     if (!document.getElementById('search-container').contains(e.target)) {
    //         suggestionsDiv.style.display = 'none';
    //     }
    // });


    // // let locationData = null;


    // function sendLocationToBackend(address, lat, lon, googleLink = null, osmLink = null) {

    //     locationData = {
    //         address,
    //         lat,
    //         lon,
    //         googleLink,
    //         osmLink
    //     };
    //     fetch('/save_location', { // Changed from /save-location to /api/store
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'Accept': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    //             },
    //             body: JSON.stringify({
    //                 address: address,
    //                 latitude: lat,
    //                 longitude: lon,
    //                 google_link: googleLink,
    //                 osm_link: osmLink
    //             })
    //         })
    //         .then(res => {
    //             if (!res.ok) {
    //                 throw new Error(`HTTP error! Status: ${res.status}`);
    //             }
    //             return res.json();
    //         })
    //         .then(data => {
    //             if (data.status === 'inside') {
    //                 show_notification('success', `✅ Location found: ${data.location}`);

    //                 // Fetch worker slides for the location_id
    //                 fetch(`/worker-slides?location_id=${data.location_id}`)
    //                     .then(res => res.text())
    //                     .then(slides => {
    //                         const slider = document.getElementById('workers_slider');

    //                         // Destroy old slick if already initialized
    //                         if ($('.service-slider').hasClass('slick-initialized')) {
    //                             $('.service-slider').slick('unslick');
    //                         }

    //                         // Inject fresh HTML
    //                         slider.innerHTML = slides;

    //                         // Re-init slick after new content is in DOM
    //                         if ($('.service-slider').length && typeof $.fn.slick !== 'undefined') {
    //                             $('.service-slider').slick({
    //                                 arrows: true,
    //                                 slidesToShow: 3,
    //                                 slidesToScroll: 1,
    //                                 dots: true,
    //                                 responsive: [{
    //                                         breakpoint: 1200,
    //                                         settings: {
    //                                             slidesToShow: 3
    //                                         }
    //                                     },
    //                                     {
    //                                         breakpoint: 992,
    //                                         settings: {
    //                                             slidesToShow: 2
    //                                         }
    //                                     },
    //                                     {
    //                                         breakpoint: 576,
    //                                         settings: {
    //                                             slidesToShow: 1
    //                                         }
    //                                     }
    //                                 ]
    //                             });
    //                         }

    //                         document.getElementById('locCount').textContent = data.location;

    //                         let filterSelect = document.getElementById('filter_location');
    //                         if (filterSelect) {
    //                             filterSelect.value = data.location_id;
    //                             filterSelect.dispatchEvent(new Event('change'));
    //                             filterSelect.style.display = 'none';
    //                         }
    //                     })
    //                     .catch(err => console.error("Error fetching slides:", err));

    //                 console.log("Saved:", data);

    //             } else {
    //                 show_notification('error', '❌ Location not found in defined regions.');

    //                 // Fetch all worker slides
    //                 fetch('/worker-slides')
    //                     .then(res => res.text())
    //                     .then(slides => {

    //                         document.getElementById('workers_slider').innerHTML = slides;

    //                         document.getElementById('locCount').textContent = 'All Locations';

    //                         let filterSelect = document.getElementById('filter_location');
    //                         if (filterSelect) {
    //                             filterSelect.value = "";
    //                             filterSelect.style.display = 'block';
    //                         }
    //                     })
    //                     .catch(err => console.error("Error fetching slides:", err));
    //                 console.warn("Outside:", data);
    //             }
    //         })

    //         .catch(err => {
    //             show_notification('error', '⚠️ Error saving location. Please try again.');
    //             console.error("Error:", err);
    //         });
    // }

    // function handleConfirmLocation() {
    //     if (locationData) {
    //         // Call confirm_map with stored location data
    //         confirm_map(
    //             locationData.address,
    //             locationData.lat,
    //             locationData.lon,
    //             locationData.googleLink,
    //             locationData.osmLink
    //         );
    //     } else {
    //         show_notification('error', '⚠️ No location data available.');
    //         console.error("No location data available.");
    //     }
    // }

    // function confirm_map(address, lat, lon, googleLink, osmLink) {
    //     console.log("Confirming Location Data:", {
    //         address,
    //         lat,
    //         lon,
    //         googleLink,
    //         osmLink
    //     });

    //     fetch('/confirm_map', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'Accept': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    //             },
    //             body: JSON.stringify({
    //                 address: address,
    //                 latitude: lat,
    //                 longitude: lon,
    //                 google_link: googleLink,
    //                 osm_link: osmLink
    //             })
    //         })
    //         .then(res => res.json())
    //         .then(data => {
    //             console.log("Backend Response:", data);

    //             if (data.status === 'inside') {
    //                 show_notification('success', `✅ Location confirmed: ${address}`);
    //             } else if (data.status === 'outside') {
    //                 show_notification('error', `❌ Location not found in defined regions.`);
    //             } else {
    //                 show_notification('error', `⚠️ Unexpected response from server.`);
    //             }
    //         })
    //         .catch(err => {
    //             console.error("Error sending data to backend:", err);
    //             show_notification('error', '⚠️ Error saving location. Please try again.');
    //         });
    // }

    // // Add event listener for the confirm_location button
    // document.getElementById('confirm_location').addEventListener('click', handleConfirmLocation);


    function initServiceSlider_Slick() {
        const $slider = $("#workers_slider");
        const totalSlides = $slider.find(".wpo-service-slide-item").length;

        if ($slider.length && totalSlides > 0) {
            if ($slider.hasClass('slick-initialized')) {
                $slider.slick('unslick');
            }
            console.log("Initializing Slick Slider...");
            $slider.slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: totalSlides > 4, // 👈 loop only if enough slides
                arrows: true,
                dots: true,
                adaptiveHeight: false, // 👈 keeps the track height stable
                swipeToSlide: true,
                waitForAnimate: false,
                edgeFriction: 0.15,
                responsive: [{
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                            infinite: totalSlides > 3
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            infinite: totalSlides > 2
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            infinite: totalSlides > 1
                        }
                    }
                ]
            }).on('init', function(event, slick) {
                console.log("{{ trans('messages.slick_slider_initialized', [], session('locale')) }} " + slick.slideCount + " {{ trans('messages.slides', [], session('locale')) }}");
            });

            // After images load, force Slick to recalc widths so no empty gap
            $slider.find('img').on('load', function() {
                $slider.slick('setPosition');
            });
        } else {
            console.log("{{ trans('messages.no_slide_items_found', [], session('locale')) }}");
        }
    }
</script>