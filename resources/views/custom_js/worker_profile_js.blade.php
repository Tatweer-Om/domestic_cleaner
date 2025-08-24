<script>
window.IS_AUTH = {{ auth()->check() ? 'true' : 'false' }};
// ------------ Helpers ------------
const IS_AUTH = !!(window.IS_AUTH);

// ------------ Helpers ------------
function addDays(isoDate, n) {
  const d = new Date(isoDate + 'T00:00:00');
  d.setDate(d.getDate() + n);
  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2,'0');
  const day = String(d.getDate()).padStart(2,'0');
  return `${y}-${m}-${day}`;
}

function buildTileHTML(index, date, shift, duration, visit, availability) {
  const shiftGroup = `shift-${index}`;
  const mId = `shift-m-${index}`;
  const eId = `shift-e-${index}`;

  const mChecked = shift === 'morning' ? 'checked' : '';
  const eChecked = shift === 'evening' ? 'checked' : '';

  const visitLabel = visit && (visit.visit_name || visit.name) ? ` - ${(visit.visit_name || visit.name)}` : '';
  const dayName = new Date(date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'short' });
  const durationBadge = duration === '5' ? '5h' : '4h';

  // Determine message based on availability
  let availabilityMessage = '';

  if (!availability.is_available) {
    availabilityMessage = `<div class="availability-message text-danger">Worker is occupied on ${date} for the ${shift} shift. `;
    if (availability.opposite_shift_available) {
      availabilityMessage += `${availability.opposite_shift} shift is available on this date.`;
    } else if (availability.next_available_date) {
      availabilityMessage += `Next available date for ${shift} shift: ${availability.next_available_date}.`;
    } else {
      availabilityMessage += `No immediate alternative available.`;
    }
    availabilityMessage += '</div>';
  }

  return `
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="mini-cal mini-cal--sm shadow-sm rounded-4">
        <div class="mini-cal-head d-flex justify-content-between align-items-center">
          <div class="label">Visit ${index}${visitLabel}</div>
          <span class="duration-badge">${durationBadge}</span>
        </div>

        <div class="mini-cal-body">
          <div class="date-wrap mb-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M7 3v4M17 3v4M3 9h18M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <input type="date" class="mini-date" value="${date}">
            <small class="day-name">${dayName}</small>
          </div>

          <div class="group">
            <div class="group-title">Shift</div>
            <div class="pills">
              <input type="radio" id="${mId}" name="${shiftGroup}" class="btn-check" ${mChecked}>
              <label for="${mId}" class="pill pill-success pill-xs">
                <span class="ico">☀️</span> Morning <small class="text-muted">(8:00 AM – 1:00 PM)</small>
              </label>

              <input type="radio" id="${eId}" name="${shiftGroup}" class="btn-check" ${eChecked}>
              <label for="${eId}" class="pill pill-success pill-xs">
                <span class="ico">🌙</span> Evening <small class="text-muted">(4:00 PM – 9:00 PM)</small>
              </label>
            </div>
          </div>
          ${availabilityMessage}
        </div>
      </div>
    </div>
  `;
}

// ------------ Validation (first-error only, except shifts) ------------
function scrollFocus($el) {
  $('html, body').animate({ scrollTop: $el.offset().top - 120 }, 250);
  $el.focus();
}

function validateTopFormFirstError() {
  const $pkg    = $('#packageSelect');
  const $worker = $('#worker_id');
  const $date   = $('#startDate');
  const $dur4   = $('#dur4');
  const $dur5   = $('#dur5');

  // 1) Package
  if (!($pkg.val() || '').trim()) {
    show_notification('error', 'Please choose a package.');
    scrollFocus($pkg);
    return false;
  }

  // 2) Worker
  if (!($worker.val() || '').trim()) {
    show_notification('error', 'Please choose a worker.');
    scrollFocus($worker);
    return false;
  }

  // 3) Start date
  const dateVal = ($date.val() || '').trim();
  if (!dateVal) {
    show_notification('error', 'Please choose a start date.');
    scrollFocus($date);
    return false;
  }
  const d = new Date(dateVal + 'T00:00:00');
  if (isNaN(d.getTime())) {
    show_notification('error', 'Please choose a valid start date.');
    scrollFocus($date);
    return false;
  }

  // 4) Duration
  if (!$dur4.is(':checked') && !$dur5.is(':checked')) {
    show_notification('error', 'Please select a duration (4h or 5h).');
    scrollFocus($dur4);
    return false;
  }

  // 5) Shift
  if (!$('#shiftMorning').is(':checked') && !$('#shiftEvening').is(':checked')) {
    show_notification('error', 'Please select a shift (morning or evening).');
    scrollFocus($('#shiftMorning'));
    return false;
  }

  return true;
}

// ------------ Duration utilities ------------
function getSelectedDuration() {
  if ($('#dur4').is(':checked')) return '4';
  if ($('#dur5').is(':checked')) return '5';
  return null;
}
function getSelectedDuration2() {
  return getSelectedDuration(); // Alias for compatibility
}
function refreshDurationBadges() {
  const dur = getSelectedDuration();
  if (!dur) return;
  const text = dur === '5' ? '5h' : '4h';
  $('#visitTiles .duration-badge').text(text);
}
$(document).on('change', '#dur4, #dur5', refreshDurationBadges);

// ------------ CSRF ------------
const token = $('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({
  headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' }
});

// Function to extract worker_id from URL
function getWorkerIdFromUrl() {
  const path = window.location.pathname; // e.g., "/worker_profile/123"
  const match = path.match(/\/worker_profile\/(\d+)/); // Extract number after "/worker_profile/"
  return match ? match[1] : ''; // Return worker_id or empty string if not found
}

// ------------ Action row builder (Proceed vs Pay Now) ------------
function buildActionRowHtml() {
  const commonBtn = `
    style="background-color:#198754; border-color:#198754; color:#fff;"
    class="btn btn-success btn-sm w-100 mt-3"
  `;
  const worker_id = getWorkerIdFromUrl() || $('#worker_id').val() || ''; // Prefer URL, fallback to select input
  const paymentRoute = worker_id ? `/checkout/${worker_id}` : '#'; // Construct URL directly

  if (IS_AUTH) {
    if (!worker_id) {
      // Disable button if no worker_id is available
      return `
        <div class="col-12" id="paymentBtnRow">
          <a href="#" id="paymentBtn" ${commonBtn} disabled>Pay Now</a>
        </div>
      `;
    }
    return `
      <div class="col-12" id="paymentBtnRow">
        <a href="${paymentRoute}" id="paymentBtn" ${commonBtn}>Pay Now</a>
      </div>
    `;
  } else {
    return `
      <div class="col-12" id="proceedBtnRow">
        <button type="button" id="proceedBtn" ${commonBtn}>Proceed</button>
      </div>
    `;
  }
}

// ------------ Update Action Button State ------------
function updateActionButton() {
  const hasIssues = $('#visitTiles .availability-message').length > 0;
  $('#proceedBtn, #paymentBtn').prop('disabled', hasIssues);
}

// ------------ Collect Visits from Tiles ------------
function collectVisitsFromTiles() {
  const visits = [];
  $('#visitTiles .mini-cal').each(function (index) {
    const $tile = $(this);
    const date = $tile.find('.mini-date').val();
    const shift = $tile.find('.btn-check:checked').attr('id').includes('shift-m') ? 'morning' : 'evening';
    if (date && shift) {
      visits.push({
        visit_number: index + 1,
        date: date,
        shift: shift,
      });
    }
  });
  return visits;
}

// ------------ Validate Visits ------------
function validateVisits(visits, worker_id, callback) {
  const conflicts = [];
  let completedRequests = 0;

  if (!visits.length) {
    callback([]);
    return;
  }

  visits.forEach((visit, index) => {
    $.ajax({
      url: "{{ route('check.availability') }}",
      method: "POST",
      data: {
        worker_id: worker_id,
        date: visit.date,
        shift: visit.shift,
      },
      success: function (res) {
        if (!res.is_available) {
          conflicts.push({
            visit_number: visit.visit_number,
            date: visit.date,
            shift: visit.shift,
            message: `Worker is not available on ${visit.date} for the ${visit.shift} shift`,
            opposite_shift: res.opposite_shift,
            opposite_shift_available: res.opposite_shift_available,
            next_available_date: res.next_available_date,
          });
        }
        completedRequests++;
        if (completedRequests === visits.length) {
          callback(conflicts);
        }
      },
      error: function () {
        conflicts.push({
          visit_number: visit.visit_number,
          date: visit.date,
          shift: visit.shift,
          message: 'Failed to check availability for this visit.',
        });
        completedRequests++;
        if (completedRequests === visits.length) {
          callback(conflicts);
        }
      }
    });
  });
}

// ------------ Generate Handler ------------
$('#generateBtn').on('click', function (e) {
  e.preventDefault();

  if (!validateTopFormFirstError()) return;

  const payload = {
    package_id: $('#packageSelect').val(),
    worker_id: $('#worker_id').val(),
    start_date: $('#startDate').val(),
    shift_morning: $('#shiftMorning').is(':checked') ? 1 : 0,
    shift_evening: $('#shiftEvening').is(':checked') ? 1 : 0,
    duration_4: $('#dur4').is(':checked') ? 1 : 0,
    duration_5: $('#dur5').is(':checked') ? 1 : 0
  };

  const duration = payload.duration_4 ? '4' : (payload.duration_5 ? '5' : null);
  if (!duration) {
    show_notification('error', 'Please select a duration (4h or 5h) before generating.');
    return;
  }

  const shift = payload.shift_morning ? 'morning' : (payload.shift_evening ? 'evening' : null);
  if (!shift) {
    show_notification('error', 'Please select a shift (morning or evening) before generating.');
    return;
  }

  $.ajax({
    url: "{{ route('generate.booking') }}",
    method: "POST",
    data: payload,
    success: function (res) {
      const tiles = $('#visitTiles');
      tiles.empty();

      if (res.status === 'error') {
        tiles.html('<div class="col-12"><div class="alert alert-danger rounded-3">Unable to generate visits: ' + (res.message || 'Unknown error') + '</div></div>');
        return;
      }

      const visitCount = res.visit_count || (Array.isArray(res.visits) ? res.visits.length : 0);
      if (!visitCount) {
        tiles.html('<div class="col-12"><div class="alert alert-info rounded-3">No visits for this package.</div></div>');
        return;
      }

      // Generate tiles using worker_availability
      let html = '';
      res.worker_availability.forEach((availability, index) => {
        const visit = Array.isArray(res.visits) ? res.visits[index] : null;
        html += buildTileHTML(index + 1, availability.date, availability.shift, duration, visit, availability);
      });

      tiles.html(html);

      // Remove old action rows and append correct one based on auth
      $('#proceedBtnRow, #paymentBtnRow').remove();
      tiles.append(buildActionRowHtml());

      updateActionButton();

      if (typeof WOW !== 'undefined') { new WOW().init(); }
      refreshDurationBadges();

      // Attach date and shift change handlers
      (function () {
        const LOCALE = 'en-US';
        function dayNameFromISO(iso) {
          if (!iso) return '';
          const d = new Date(iso + 'T00:00:00');
          return d.toLocaleDateString(LOCALE, { weekday: 'short' });
        }

        // Store last valid value so we can revert
        $('#visitTiles')
          .off('focusin.miniDate pointerdown.miniDate')
          .on('focusin.miniDate pointerdown.miniDate', '.mini-date', function () {
            $(this).data('prev', this.value || '');
          });

        // Block Fridays and check availability on date change
        $('#visitTiles')
          .off('change.miniDate input.miniDate')
          .on('change.miniDate input.miniDate', '.mini-date', function () {
            const $inp = $(this);
            const $tile = $inp.closest('.mini-cal');
            const $message = $tile.find('.availability-message');
            let iso = $inp.val();

            if (!iso) {
              $inp.siblings('.day-name').text('');
              $inp.data('prev', '');
              return;
            }

            const d = new Date(iso + 'T00:00:00');
            const isFriday = d.getDay() === 5;

            if (isFriday) {
              show_notification('error', 'Friday is off. Please pick another day.');
              const prev = $inp.data('prev') || '';
              if (prev) {
                $inp.val(prev);
                $inp.siblings('.day-name').text(dayNameFromISO(prev));
              } else {
                $inp.val('');
                $inp.siblings('.day-name').text('');
              }
              return;
            }

            // Update day name
            $inp.siblings('.day-name').text(dayNameFromISO(iso));

            // Check availability for new date
            const shift = $tile.find('.btn-check:checked').attr('id').includes('shift-m') ? 'morning' : 'evening';
            const worker_id = $('#worker_id').val();

            $.ajax({
              url: "{{ route('check.availability') }}",
              method: "POST",
              data: { worker_id, date: iso, shift },
              success: function (res) {
                $message.remove();
                if (!res.is_available) {
                  let msgHtml = `<div class="availability-message text-danger">This date (${iso}) is not available for the ${shift} shift. `;
                  if (res.opposite_shift_available) {
                    msgHtml += `${res.opposite_shift} shift is available on this date.`;
                  } else if (res.next_available_date) {
                    msgHtml += `Next available date for ${shift} shift: ${res.next_available_date}.`;
                  } else {
                    msgHtml += `No immediate alternative available.`;
                  }
                  msgHtml += '</div>';
                  $tile.find('.mini-cal-body').append(msgHtml);
                  show_notification('error', `This date (${iso}) is not available for the ${shift} shift.`);
                }
                $inp.data('prev', iso);
                updateActionButton();
              },
              error: function () {
                show_notification('error', 'Failed to check availability.');
                updateActionButton();
              }
            });
          });

        // Check availability on shift change
        $('#visitTiles').on('change', '.btn-check', function () {
          const $tile = $(this).closest('.mini-cal');
          const $message = $tile.find('.availability-message');
          const date = $tile.find('.mini-date').val();
          const shift = $(this).attr('id').includes('shift-m') ? 'morning' : 'evening';
          const worker_id = $('#worker_id').val();

          $.ajax({
            url: "{{ route('check.availability') }}",
            method: "POST",
            data: { worker_id, date, shift },
            success: function (res) {
              $message.remove();
              if (!res.is_available) {
                let msgHtml = `<div class="availability-message text-danger">This date (${date}) is not available for the ${shift} shift. `;
                if (res.opposite_shift_available) {
                  msgHtml += `${res.opposite_shift} shift is available on this date.`;
                } else if (res.next_available_date) {
                  msgHtml += `Next available date for ${shift} shift: ${res.next_available_date}.`;
                } else {
                  msgHtml += `No immediate alternative available.`;
                }
                msgHtml += '</div>';
                $tile.find('.mini-cal-body').append(msgHtml);
                show_notification('error', `This date (${date}) is not available for the ${shift} shift.`);
              }
              updateActionButton();
            },
            error: function () {
              show_notification('error', 'Failed to check availability.');
              updateActionButton();
            }
          });
        });
      })();
    },
    error: function (xhr) {
      console.error("Error:", xhr.responseJSON || xhr.responseText);
      show_notification('error', xhr.responseJSON?.message || 'Failed to generate visits.');
    }
  });
});

// ------------ Payment Handler ------------
$(document).on('click', '#paymentBtn', function (e) {
  e.preventDefault();

  // Validate the top form
  if (!validateTopFormFirstError()) return;

  // Collect payload
  const package_id  = $('#packageSelect').val();
  const worker_id   = $('#worker_id').val();
  const location_id = $('#locationSelect').val();
  const start_date  = $('#startDate').val();
  const duration    = getSelectedDuration2();

  const visits = collectVisitsFromTiles();
  if (!visits.length) {
    show_notification('error', 'Please add at least one visit.');
    return;
  }

  // Validate visits for availability
  validateVisits(visits, worker_id, function (conflicts) {
    if (conflicts.length > 0) {
      let errorMsg = 'Cannot save booking due to the following conflicts:<ul>';
      conflicts.forEach(conflict => {
        errorMsg += `<li>Visit ${conflict.visit_number}: ${conflict.message}`;
        if (conflict.opposite_shift_available) {
          errorMsg += ` (${conflict.opposite_shift} shift is available on this date)`;
        } else if (conflict.next_available_date) {
          errorMsg += ` (Next available date for ${conflict.shift} shift: ${conflict.next_available_date})`;
        }
        errorMsg += '</li>';
      });
      errorMsg += '</ul>';
      show_notification('error', errorMsg);
      return;
    }

    // Proceed with booking if no conflicts
    const subtotal     = 0;
    const discount     = 0;
    const total_amount = 0;

    const payload = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      package_id,
      worker_id,
      location_id,
      start_date,
      duration,
      visits,
      subtotal,
      discount,
      total_amount,
    };

    // POST
    $.ajax({
      url: "{{ route('save_booking') }}",
      type: 'POST',
      data: JSON.stringify(payload),
      contentType: 'application/json',
      success: function (res) {
        if (res.ok) {
          show_notification('success', 'Booking saved. Redirecting to payment...');
          if (res.redirect_to_payment) {
            window.location.href = res.redirect_to_payment;
          } else {
            console.log('Booking ID:', res.booking_id);
          }
        } else {
          show_notification('error', res.message || 'Unable to create booking.');
        }
      },
      error: function (xhr) {
        let msg = 'Failed to save booking.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
          msg = xhr.responseJSON.message;
        }
        show_notification('error', msg);
        console.error(xhr.responseText);
      }
    });
  });
});

// ------------ Optional tiny style for duration badge ------------
(function(){
  const css = `
    .duration-badge {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 999px;
      font-size: 12px;
      line-height: 1.2;
      border: 1px solid rgba(0,0,0,0.15);
    }`;
  const style = document.createElement('style');
  style.textContent = css;
  document.head.appendChild(style);
})();

// Ensure only one duration checkbox is checked (if you use checkboxes)
$(document).on('change', '.duration-check', function () {
  if (this.checked) {
    $('.duration-check').not(this).prop('checked', false);
  }
  refreshDurationBadges();
});

// ------------ Actions ------------
$(document).on('click', '#proceedBtn', function () {
  const $auth = $('#authShell');
  $auth.removeClass('d-none');

  // default to Register view when opening
  $('#registerPane').removeClass('d-none');
  $('#loginPane').addClass('d-none');
  $('#tabRegister').removeClass('btn-outline-success').addClass('btn-success');
  $('#tabLogin').removeClass('btn-success').addClass('btn-outline-success');
});

$(document).on('click', '#paymentBtn', function () {
  // Wire this to your payment flow:
  // window.location.href = "{{ url('/checkout') }}";
  // or $('#paymentModal').modal('show');
  show_notification('success', 'Proceeding to payment...');
});

// Auth tabs
$(document).on('click', '#tabRegister', function () {
  $('#registerPane').removeClass('d-none');
  $('#loginPane').addClass('d-none');
  $('#tabRegister').removeClass('btn-outline-success').addClass('btn-success');
  $('#tabLogin').removeClass('btn-success').addClass('btn-outline-success');
  $('#registerForm input[name="name"]').trigger('focus');
});

$(document).on('click', '#tabLogin', function () {
  $('#loginPane').removeClass('d-none');
  $('#registerPane').addClass('d-none');
  $('#tabLogin').removeClass('btn-outline-success').addClass('btn-success');
  $('#tabRegister').removeClass('btn-success').addClass('btn-outline-success');
  $('#loginForm input[name="email"]').trigger('focus');
});


$(document).on('submit', '#registerForm', function (e) {

  e.preventDefault();

  const $btn = $(this).find('button[type="submit"]');
  $btn.prop('disabled', true);

  const fd = new FormData(this);

  $.ajax({
    url: "{{ route('register.ajax') }}",
    method: "POST",
    data: fd,
    processData: false,
    contentType: false,
    success: function (res) {
      if (res.status === 'success') {
        show_notification('success', res.message || 'Registered!');
        $('#tabLogin').trigger('click');
      } else {
        show_notification('error', res.message || 'Registration failed.');
      }
    },
    error: function (xhr) {
      if (xhr.status === 422 && xhr.responseJSON?.errors) {
        // Laravel validation errors
        const errors = xhr.responseJSON.errors;
        const firstKey = Object.keys(errors)[0];
        show_notification('error', errors[firstKey][0]);
      } else {
        show_notification('error', 'Server error. Please try again.');
      }
    },
    complete: function () {
      $btn.prop('disabled', false);
    }
  });
});

$(document).on('submit', '#loginForm', function (e) {
     $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
  e.preventDefault();

  const $form = $(this);
  const $btn  = $form.find('button[type="submit"]');
  $btn.prop('disabled', true);

  // Read fields
  const identifier = ($form.find('#phone_name').val() || '').trim(); // username OR phone
  const password   = ($form.find('input[name="password"]').val() || '').trim();

  if (!identifier) {
    show_notification('error', 'Please enter username or phone');
    $btn.prop('disabled', false);
    return;
  }
  if (!password) {
    show_notification('error', 'Please enter password');
    $btn.prop('disabled', false);
    return;
  }

  // Build payload (keep FormData style like your register code)
  const fd = new FormData();
  fd.append('identifier', identifier); // backend should accept "identifier"
  fd.append('password', password);

  $.ajax({
    url: "{{ route('login.ajax') }}",
    method: "POST",
    data: fd,
    processData: false,
    contentType: false,
  success: function (res) {
    if (res.status === 'success' || res.ok === true) {
      show_notification('success', res.message || 'Logged in!');

      // Get worker_id from URL or select input
      const worker_id = getWorkerIdFromUrl() || $('#worker_id').val() || '';
      if (worker_id) {
        // Redirect to checkout page with worker_id
        const checkoutRoute = `/checkout/${worker_id}`;
        window.location.href = checkoutRoute;
      } else {
        // Fallback: redirect to a default page or show error
        show_notification('error', 'Worker ID not found. Please select a worker.');
        // Optionally redirect to a default page
        // window.location.href = '/booking';
      }
    } else {
      show_notification('error', res.message || 'Invalid credentials.');
    }
  },
    complete: function () {
      $btn.prop('disabled', false);
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





// Helper: read current duration (top-level choice)
function getSelectedDuration2() {
  if ($('#dur4').is(':checked')) return 4;
  if ($('#dur5').is(':checked')) return 5;
  return null;
}

// Build visits array from tiles
function collectVisitsFromTiles() {
  const visits = [];
  // tiles structure from your buildTileHTML()
  $('#visitTiles .mini-cal').each(function(idx) {
    const $tile = $(this);
    const index = idx + 1;

    const date = ($tile.find('input.mini-date').val() || '').trim();

    // determine checked shift from the two radios in the group
    // We can sniff by looking for adjacent label text:
    let shift = null;
    const $checked = $tile.find('input.btn-check:checked');
    if ($checked.length) {
      const forId = $checked.attr('id') || '';
      // our ids are "shift-m-{index}" and "shift-e-{index}"
      shift = forId.includes('-m-') ? 'morning' : 'evening';
    }

    // per-visit duration mirrors the top-level duration
    const duration = getSelectedDuration2();

    visits.push({ index, date, shift, duration });
  });

  // filter out incomplete rows if any slipped in
  return visits.filter(v => v.date && v.shift && (v.duration === 4 || v.duration === 5));
}

// Click handler



</script>
