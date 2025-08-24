<script>
$(function () {
  // Optional: a tiny loader while fetching
  const loader = '<div class="text-center py-5"><div class="spinner-border" role="status"></div></div>';
  $('#servicesContainer').html(loader);

  $.ajax({
    url: "{{ route('worker_section') }}",   // or: "{{ url('service_section') }}"
    type: 'GET',
    dataType: 'html'
  })
  .done(function (html) {
    $('#servicesContainer').html(html);

    // If you’re using WOW animations
    if (typeof WOW !== 'undefined') { new WOW().init(); }



    // If you’re using Slick instead, comment Owl above and use this:
    if ($('.service-slider').length && typeof $.fn.slick !== 'undefined') {
      $('.service-slider').slick({
         arrows: true,
        slidesToShow: 3,

        slidesToScroll: 1,
        dots: true,
        arrows: true,
        responsive: [
          { breakpoint: 1200, settings: { slidesToShow: 3 } },
          { breakpoint: 992,  settings: { slidesToShow: 2 } },
          { breakpoint: 576,  settings: { slidesToShow: 1 } }
        ]
      });
    }

  })
  .fail(function () {
    $('#servicesContainer').html(
      '<div class="alert alert-danger m-0">Failed to load services. Please try again.</div>'
    );
  });
});

document.addEventListener('DOMContentLoaded', function () {
  fetch("{{ route('worker_list') }}")
    .then(res => res.json())
    .then(data => {
      if (data.worker_list) {
        document.getElementById('workerListContainer').innerHTML = data.worker_list;
        // If you rely on WOW.js animations, re-init after injection:
        if (typeof WOW !== 'undefined') { new WOW().init(); }
      }
    })
    .catch(err => console.error('workers.list error:', err));
});




$(function () {
  // Endpoints
  const REGISTER_URL = "{{ route('register.ajax') }}";
  const LOGIN_URL    = "{{ route('login.ajax') }}"; // add this route in web.php

  // CSRF header for Laravel
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

  const $form       = $('#authForm');
  const $header     = $('#formHeader');
  const $toggleLink = $('#toggleLink');
  const $phoneBlk   = $('#nameField');   // block with the phone input (signup only)
  const $user       = $('#user_name');   // will act as identifier in login mode
  const $pass       = $('#password');
  const $phone      = $('#phone');       // ensure input has id="phone" name="phone"
  const $submit     = $('#submitBtn');

  // Your helpers (safe fallbacks)
  window.show_notification = window.show_notification || function(t,m){ console.log(t.toUpperCase()+': '+m); };
  window.showPreloader = window.showPreloader || function(){};
  window.hidePreloader = window.hidePreloader || function(){};
  window.before_submit = window.before_submit || function(){};
  window.after_submit  = window.after_submit  || function(){};

  let mode = 'login'; // 'login' | 'signup'

  function setMode(next) {
    mode = next;
    if (mode === 'signup') {
      $header.text('CREATE AN ACCOUNT');
      $submit.text('Create Account');
      $toggleLink.text('Have an account? Sign in');
      $phoneBlk.show();
      $user.attr('placeholder', 'Enter username');
    } else {
      $header.text('SIGN IN TO MANAGE BOOKINGS');
      $submit.text('Sign In');
      $toggleLink.text('Create an account');
      $phoneBlk.hide();
      $user.attr('placeholder', 'Enter username or phone');
    }
    $user.trigger('focus');
  }
  setMode('login');

  $toggleLink.on('click', function (e) {
    e.preventDefault();
    setMode(mode === 'login' ? 'signup' : 'login');
  });

  function setSubmitting(busy) {
    $submit.prop('disabled', busy);
    $submit.data('orig', $submit.data('orig') || $submit.text());
    $submit.text(busy ? 'Please wait…' : $submit.data('orig'));
  }

  // Main submit handler (adapted from your register AJAX)
  $(document).on('submit', '#authForm', function (e) {
    e.preventDefault();

    const identifierOrUsername = ($user.val() || '').trim();
    const password             = ($pass.val() || '').trim();
    const phoneVal             = (($phone?.val() || '') + '').trim();

    showPreloader();
    before_submit();
    setSubmitting(true);

    if (mode === 'signup') {
      // Client-side checks
      if (!identifierOrUsername) {
        show_notification('error', 'Please enter a username');
        done(); return;
      }
      if (!phoneVal) {
        show_notification('error', 'Please enter mobile number');
        done(); return;
      }
      if (!password) {
        show_notification('error', 'Please enter password');
        done(); return;
      }

      // Build FormData from this form (your original pattern)
      const fd = new FormData(this);
      // Ensure names match backend expectations
      fd.set('user_name', identifierOrUsername);
      fd.set('phone', phoneVal);
      fd.set('password', password);

      $.ajax({
        url: REGISTER_URL,
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        success: function (res) {
          if (res.status === 'success') {
            show_notification('success', res.message || 'Registered!');
            // Switch to login mode, keep username for convenience
            setMode('login');
            // If you still have tabs, you can also trigger the login tab:
            // $('#tabLogin').trigger('click');
            $user.val(identifierOrUsername);
            $pass.val('').trigger('focus');
          } else {
            show_notification('error', res.message || 'Registration failed.');
          }
        },
        error: function (xhr) {
          if (xhr.status === 422 && xhr.responseJSON?.errors) {
            const errors = xhr.responseJSON.errors;
            const firstKey = Object.keys(errors)[0];
            show_notification('error', errors[firstKey][0]);
          } else {
            show_notification('error', 'Server error. Please try again.');
          }
        },
        complete: done
      });

      return;
    }

    // mode === 'login'
    if (!identifierOrUsername) {
      show_notification('error', 'Enter username or phone');
      done(); return;
    }
    if (!password) {
      show_notification('error', 'Enter password');
      done(); return;
    }

    // Send as simple form fields; backend reads identifier+password
    $.ajax({
      url: LOGIN_URL,
      method: 'POST',
      data: { identifier: identifierOrUsername, password: password },
      success: function (res) {
        if (res.status === 'success' || res.ok === true) {
          show_notification('success', res.message || 'Signed in!');
          if (res.redirect_url) {
            window.location.href = res.redirect_url;
          } else {
            window.location.reload();
          }
        } else {
          show_notification('error', res.message || 'Login failed.');
        }
      },
      error: function (xhr) {
        if (xhr.status === 422 && xhr.responseJSON?.errors) {
          const errors = xhr.responseJSON.errors;
          const firstKey = Object.keys(errors)[0];
          show_notification('error', errors[firstKey][0]);
        } else if (xhr.responseJSON?.message) {
          show_notification('error', xhr.responseJSON.message);
        } else {
          show_notification('error', 'Server error. Please try again.');
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

document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.getElementById('btnLogout');
    if (!logoutBtn) return;

    logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();

        fetch("{{ route('logout.ajax') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                window.location.href = data.redirect_url || "{{ url('/') }}";
            } else {
                alert(data.error || "Logout failed");
            }
        })
        .catch(() => alert("Network error while logging out"));
    });
});



function initServiceSlider_Slick() {
  var $el = $('#workers_slider');
  var slideCount = $el.find('.slide').length;
  var slidesToShow = Math.min(slideCount, 4); // Use the minimum of slide count or 4

  if ($el.hasClass('slick-initialized')) {
    $el.slick('unslick');
  }

  $el.slick({
    slidesToShow: slidesToShow || 1, // Fallback to 1 if no slides
    slidesToScroll: 1,
    infinite: slideCount > 1, // Only loop if more than 1 slide
    arrows: true,
    dots: true, // You have dots in the screenshot, so change to true if intended
    responsive: [
      { breakpoint: 1200, settings: { slidesToShow: Math.min(slideCount, 3) || 1 } },
      { breakpoint: 992, settings: { slidesToShow: Math.min(slideCount, 2) || 1 } },
      { breakpoint: 576, settings: { slidesToShow: 1 } }
    ]
  });
}
jQuery(function ($) {
    // Initialize Bootstrap Select picker for location dropdown
    if ($.fn.selectpicker) {
        $("#filter_location").selectpicker();
    }

    // Initialize Slick Slider for workers slider
  function initServiceSlider_Slick() {
  const $slider = $("#workers_slider");
  const totalSlides = $slider.find(".wpo-service-slide-item").length;

  if ($slider.length && totalSlides > 0) {
    console.log("Initializing Slick Slider...");
    $slider.slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      infinite: totalSlides > 4,          // 👈 loop only if enough slides
      arrows: true,
      dots: true,
      adaptiveHeight: false,               // 👈 keeps the track height stable
      swipeToSlide: true,
      waitForAnimate: false,
      edgeFriction: 0.15,
      responsive: [
        { breakpoint: 1200, settings: { slidesToShow: 3, infinite: totalSlides > 3 } },
        { breakpoint: 992,  settings: { slidesToShow: 2, infinite: totalSlides > 2 } },
        { breakpoint: 768,  settings: { slidesToShow: 1, infinite: totalSlides > 1 } }
      ]
    }).on('init', function(event, slick) {
      console.log("Slick Slider initialized with " + slick.slideCount + " slides");
    });

    // After images load, force Slick to recalc widths so no empty gap
    $slider.find('img').on('load', function () {
      $slider.slick('setPosition');
    });
  } else {
    console.log("No slide items found, skipping Slick initialization.");
  }
}


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

        let done = 0, settled = false;
        const finish = () => { if (!settled) { settled = true; resolve(); } };
        const onOne = () => { if (++done >= $imgs.length) finish(); };

        const t = setTimeout(finish, timeoutMs); // fallback
        $imgs.each(function () {
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
        data: { location_id: locationId },
        cache: false,
        beforeSend: function () {
            // Destroy slick if initialized
            if ($slider.hasClass("slick-initialized")) {
                $slider.slick("unslick");
            }
            // Reserve height to prevent jump
            $slider.css("min-height", $slider.outerHeight() + "px")
                   .css("opacity", 0.3)
                   .html('<div class="py-4 text-center">Loading…</div>');
        },
        success: async function (res) {
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
            $slider.animate({ opacity: 1 }, 250, () => {
                $slider.css("min-height", "");
            });
        },
        error: function (xhr) {
            console.error("AJAX Error:", xhr.responseText || xhr.statusText);
            $slider.css({ opacity: 1, "min-height": "" })
                   .html('<div class="alert alert-danger">Failed to load workers.</div>');
        }
    });
}

    // Unbind previous handlers to prevent duplicates, then bind namespaced event
    $(document).off('change.filterLocation', '#filter_location');
    $(document).on('change.filterLocation', '#filter_location', updateSlider);
});

$(document).on('click', '.wpo-service-link', function (e) {
    const locationId = $('#filter_location').val();
    if (!locationId) {
        e.preventDefault();
        show_notification('error', 'Please select a location first.');
        return false;
    }
});

</script>

