<script>
// Helper function to format date from ISO to readable format
function formatVisitDate(dateString) {
  if (!dateString) return '—';
  
  try {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-GB', {
      day: '2-digit',
      month: 'short',
      year: 'numeric'
    });
  } catch (e) {
    return dateString;
  }
}

// Helper function to format date for input field (YYYY-MM-DD)
function formatDateForInput(dateString) {
  if (!dateString) return '';
  
  try {
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
  } catch (e) {
    return '';
  }
}

// Helper function to calculate time range based on shift and duration
function calculateTimeRange(shift, duration) {
  if (!shift || !duration) return '—';
  
  const dur = parseInt(duration);
  if (![4, 5].includes(dur)) return `${dur}h`;
  
  let startTime, endTime;
  
  if (shift.toLowerCase() === 'morning') {
    startTime = '8:00 AM';
    if (dur === 4) {
      endTime = '12:00 PM';
    } else if (dur === 5) {
      endTime = '1:00 PM';
    }
  } else if (shift.toLowerCase() === 'evening') {
    if (dur === 4) {
      startTime = '4:00 PM';
      endTime = '8:00 PM';
    } else if (dur === 5) {
      startTime = '3:00 PM';
      endTime = '8:00 PM';
    }
  }
  
  return startTime && endTime ? `${startTime} - ${endTime}` : `${dur}h`;
}

$(document).ready(function () {
  // Get user_id from hidden div
  const userId = $('#user-profile').data('user-id');

  if (!userId) {
    console.error('No user_id found on #user-profile');
  }

  // --- Function: fetch user bookings ---
  function user_bookings() {
    $.ajax({
      url: "{{ route('user_bookings') }}",
      type: "GET",
      data: { user_id: userId },
      success: function (res) {
        if (res.ok) {
          let html = '';
          res.bookings.forEach(b => {
            const duration = Number(b.duration ?? 0);
            const durationLabel = duration ? `${duration} hour${duration === 1 ? '' : 's'}` : 'N/A';

            let statusText = 'Unknown', statusClass = 'chip-status';
            if (b.status == 1) { statusText = 'Pending';   statusClass = 'chip-status pending'; }
            else if (b.status == 2) { statusText = 'Completed'; statusClass = 'chip-status completed'; }
            else if (b.status == 3) { statusText = 'Cancelled'; statusClass = 'chip-status cancelled'; }

            const pkg = b.package ?? {};
            let price = null;
            if (duration === 4) price = pkg.package_price_4 ?? null;
            else if (duration === 5) price = pkg.package_price_5 ?? null;

            let locationName = b.location?.location_name ?? 'N/A';
            const words = locationName.split(/\s+/);
            if (words.length > 10) locationName = words.slice(0, 10).join(" ") + "…";

            html += `
              <div class="sleek-item mb-2 p-3 border rounded">
                <div class="sleek-line d-flex justify-content-between align-items-center">
                  <div class="sleek-main">
                    <div class="sleek-title">
                      <span class="chip chip-id">#${b.booking_no ?? b.id}</span>
                      <strong class="ms-2">${pkg.package_name ?? 'No Package'}</strong>
                    </div>
                    <div class="sleek-sub small text-muted mt-1">
                      <i class="far fa-clock me-1"></i>${b.start_date ?? ''} •
                      <i class="fas fa-map-marker-alt me-1 ms-2"></i>${locationName} •
                      <i class="fas fa-list-ol me-1 ms-2"></i>${b.visits_count ?? 0} visits •
                      <i class="fas fa-hourglass-half me-1 ms-2"></i>${durationLabel}
                    </div>
                  </div>
                  <div class="sleek-aside text-end">
                    <span class="chip ${statusClass}">${statusText}</span>
                    <div class="sleek-amount ms-3">${price !== null ? `OMR ${price}` : '—'}</div>
                  </div>
                </div>
              </div>
            `;
          });
          $('#bookings-pane').html(html);
        }
      }
    });
  }

  // --- Function: fetch user visits --- (NO PARAM)
  function user_visits() {
    $.ajax({
      url: "{{ url('user_visits') }}",
      type: "GET",
      data: { user_id: userId },
      success: function (res) {
        if (!res.ok || !res.visits) {
          $('#visits-pane').html('<p class="text-muted p-3">No visits found.</p>');
          return;
        }

        let html = `<div class="list sleek-list">`;

        res.visits.forEach(v => {
          let statusText = 'Unknown', statusClass = 'chip-status';
          if (v.status == 1) { statusText = 'Pending';   statusClass = 'chip-status upcoming'; }
          else if (v.status == 2) { statusText = 'Completed'; statusClass = 'chip-status completed'; }
          else if (v.status == 3) { statusText = 'Cancelled'; statusClass = 'chip-status cancelled'; }

          // Format date properly
          const formattedDate = formatVisitDate(v.visit_date);
          
          // Calculate time range based on shift and duration
          const timeRange = calculateTimeRange(v.shift, v.duration);
          
          const dur = v.duration ? `${v.duration}h` : '—';

          html += `
  <div class="sleek-item" data-visit-id="${v.id}" 
       data-original-date="${v.visit_date}" 
       data-original-duration="${v.duration}" 
       data-original-shift="${v.shift}">
    <div class="sleek-line">
      <div class="sleek-main">
        <div class="sleek-title">
          <span class="chip chip-id">#${v.booking?.booking_no ?? v.id}</span>
          <strong class="ms-2">${v.shift ?? '—'}</strong>
        </div>

        <!-- VIEW MODE -->
        <div class="sleek-sub view-mode">
          <i class="far fa-calendar me-1"></i>
          <span class="view-date">${formattedDate}</span> •

          <i class="far fa-clock me-1 ms-2"></i>
          <span class="view-time-range">${timeRange}</span> •

          <i class="fas fa-user-tie me-1 ms-2"></i>
          ${v.worker?.worker_name ?? '—'} •

          <i class="fas fa-sun me-1 ms-2"></i>
          <span class="view-shift">${v.shift ?? '—'}</span>
        </div>

        <!-- EDIT MODE (hidden by default) -->
        <div class="sleek-sub edit-mode d-none">
          <div class="row g-2 align-items-center">
            <div class="col-auto">
              <label class="small text-muted me-1">Date</label>
              <input type="date" class="form-control form-control-sm edit-date"
                     value="${formatDateForInput(v.visit_date)}">
            </div>
            <div class="col-auto">
              <label class="small text-muted me-1">Duration (h)</label>
              <input type="number" min="1" step="1"
                     class="form-control form-control-sm edit-duration"
                     value="${v.duration ?? ''}">
            </div>
            <div class="col-auto">
              <label class="small text-muted me-1">Shift</label>
              <select class="form-select form-select-sm edit-shift">
                <option value="Morning" ${v.shift === 'Morning' ? 'selected' : ''}>Morning</option>
                <option value="Evening" ${v.shift === 'Evening' ? 'selected' : ''}>Evening</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="sleek-aside d-flex align-items-center">
        <span class="chip ${statusClass} me-2">${statusText}</span>

        <!-- ACTIONS -->
        <div class="view-mode">
          <button class="btn btn-sm btn-outline-primary ms-2 btn-edit">
            <i class="fas fa-edit me-1"></i> Edit
          </button>
        </div>
        <div class="edit-mode d-none">
          <button class="btn btn-sm btn-success ms-2 btn-save">
            <i class="fas fa-check me-1"></i> Save
          </button>
          <button class="btn btn-sm btn-secondary ms-2 btn-cancel">
            <i class="fas fa-times me-1"></i> Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
`;

        });

        html += `</div>`;
        $('#visits-pane').html(html);
      },
      error: function (xhr) {
        console.error("Error loading visits:", xhr.responseText);
        $('#visits-pane').html('<p class="text-danger p-3">Failed to load visits.</p>');
      }
    });
  }

  // --- Function: fetch user feedback ---
function user_feedback() {
  $.ajax({
    url: "{{ url('user_feedback') }}",
    type: "GET",
    data: { user_id: userId },
    success: function (res) {
      if (!res.ok || !Array.isArray(res.bookings) || res.bookings.length === 0) {
        $('#workers-pane').html('<p class="text-muted p-3">No active bookings found.</p>');
        return;
      }

      let html = '<div class="list sleek-list">';
      res.bookings.forEach(b => {
        const bookingId = b.id;
        const workerId  = b.worker?.id ?? b.worker_id ?? '';
        const worker    = b.worker?.worker_name ?? 'Unknown Worker';
        const bookingNo = b.booking_no ?? bookingId;
        const dateStr   = b.start_date ?? '';

        // unique names/ids to prevent radio groups from colliding across cards
        const ratingName = `rating_${bookingId}`;

        html += `
          <div class="sleek-item p-3 border rounded mb-3">
            <div class="sleek-line d-flex justify-content-between align-items-center">
              <div class="sleek-main">
                <div class="sleek-title">
                  <span class="chip chip-id">#${bookingNo}</span>
                  <strong class="ms-2">${worker}</strong>
                </div>
                <div class="sleek-sub small text-muted mt-1">
                  <i class="far fa-calendar me-1"></i>${dateStr}
                </div>
              </div>
            </div>

            <hr class="my-3">

            <!-- Feedback Form -->
            <form class="worker-feedback-form" data-booking-id="${bookingId}">
              <input type="hidden" name="booking_id" value="${bookingId}">
              <input type="hidden" name="worker_id" value="${workerId}">
              <input type="hidden" name="user_id"   value="${userId}">
                <input type="hidden" name="customer_id"   value="${userId}">


              <!-- Stars -->
              <div class="mb-2">
                <div class="rating" aria-label="Rate this worker">
                  <input type="radio" id="r5_${bookingId}" name="${ratingName}" value="5">
                  <label for="r5_${bookingId}" title="5 stars">★</label>

                  <input type="radio" id="r4_${bookingId}" name="${ratingName}" value="4">
                  <label for="r4_${bookingId}" title="4 stars">★</label>

                  <input type="radio" id="r3_${bookingId}" name="${ratingName}" value="3">
                  <label for="r3_${bookingId}" title="3 stars">★</label>

                  <input type="radio" id="r2_${bookingId}" name="${ratingName}" value="2">
                  <label for="r2_${bookingId}" title="2 stars">★</label>

                  <input type="radio" id="r1_${bookingId}" name="${ratingName}" value="1">
                  <label for="r1_${bookingId}" title="1 star">★</label>
                </div>
                <small class="text-muted d-block">Tap a star to rate</small>
              </div>

              <!-- Text feedback -->
              <div class="mb-3">
                <textarea name="feedback" class="form-control" rows="3"
                  placeholder="Share your experience (punctuality, attitude, quality)…"></textarea>
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-sm btn-primary">
                  <i class="fas fa-paper-plane me-1"></i> Submit
                </button>
              </div>
            </form>
          </div>
        `;
      });
      html += '</div>';

      $('#workers-pane').html(html);
    },
    error: function (xhr) {
      console.error("Error loading feedback:", xhr.responseText);
      $('#workers-pane').html('<p class="text-danger p-3">Failed to load feedback.</p>');
    }
  });
}


  // 🚀 Call on page load
  user_bookings();
  user_visits();
  user_feedback();
});


function enterEdit($item) {
  $item.find('.view-mode').addClass('d-none');
  $item.find('.edit-mode').removeClass('d-none');
}
function exitEdit($item) {
  $item.find('.edit-mode').addClass('d-none');
  $item.find('.view-mode').removeClass('d-none');
}

// Edit
$(document).on('click', '.btn-edit', function () {
  const $item = $(this).closest('.sleek-item');
  enterEdit($item);
});

// Cancel
$(document).on('click', '.btn-cancel', function () {
  const $item = $(this).closest('.sleek-item');
  // Reset inputs back to original values from data attributes
  const visitId = $item.data('visit-id');
  
  // Get original values from the visit data (we'll store them as data attributes)
  const originalDate = $item.data('original-date');
  const originalDuration = $item.data('original-duration');
  const originalShift = $item.data('original-shift');

  $item.find('.edit-date').val(formatDateForInput(originalDate));
  $item.find('.edit-duration').val(originalDuration);
  $item.find('.edit-shift').val(originalShift);

  exitEdit($item);
});

// Save (AJAX)
$(document).on('click', '.btn-save', function () {
  const $item = $(this).closest('.sleek-item');
  const visitId = $item.data('visit-id');

  const payload = {
    visit_date:  $item.find('.edit-date').val(),
    duration:    $item.find('.edit-duration').val(),
    shift:       $item.find('.edit-shift').val(),
    _token:      $('meta[name="csrf-token"]').attr('content')
  };

  // Basic validation
 if (!payload.visit_date || !payload.duration || !payload.shift) {
  show_notification('error', 'Please fill date, duration, and shift.');
  return;
}

// ✅ Restrict duration to only 4 or 5
if (!(payload.duration == 4 || payload.duration == 5)) {
  show_notification('error', 'Duration must be 4 or 5 hours only.');
  return;
}

  $.ajax({
    url: "{{ url('visits/update') }}/" + visitId,
    type: "POST",
    data: payload,
    success: function (res) {
      if (!res.ok) {
        show_notification('error', (res.message || 'Update failed'));
        return;
      }

      // Update view values with proper formatting
      const updatedDate = formatVisitDate(res.visit.visit_date);
      const updatedTimeRange = calculateTimeRange(res.visit.shift, res.visit.duration);
      
      $item.find('.view-date').text(updatedDate);
      $item.find('.view-time-range').text(updatedTimeRange);
      $item.find('.view-shift').text(res.visit.shift);
      
      // Update data attributes with new values
      $item.data('original-date', res.visit.visit_date);
      $item.data('original-duration', res.visit.duration);
      $item.data('original-shift', res.visit.shift);

      exitEdit($item);

      // ✅ Success notification
      show_notification('success', res.message || 'Visit updated successfully');
    },
    error: function (xhr) {
      console.error(xhr.responseText);

      // Try to extract server error message
      let msg = 'Server error while updating visit.';
      try {
        const json = JSON.parse(xhr.responseText);
        if (json.message) msg = json.message;
      } catch (e) {}

      // ❌ Error notification
      show_notification('error', msg);
    }
  });
});

// Submit handler for any feedback form in the pane
$(document).on('submit', '#workers-pane .worker-feedback-form', function (e) {
  e.preventDefault();

  const $form = $(this);
  const $btn  = $form.find('button[type="submit"]');
  const csrf  = $('meta[name="csrf-token"]').attr('content') || '';

  const bookingId = $form.find('input[name="booking_id"]').val();
  const workerId  = $form.find('input[name="worker_id"]').val();
  const userID = $form.find('input[name="user_id"]').val();

  // find the selected rating (name is rating_<bookingId>)
  const ratingInput = $form.find(`input[name="rating_${bookingId}"]:checked`);
  const rating = ratingInput.val();
  const feedback = ($form.find('textarea[name="feedback"]').val() || '').trim();

  // Validate
  if (!workerId || !bookingId) {
    show_notification('error', 'Missing worker or booking id.');
    return;
  }
  if (!rating || !['1','2','3','4','5'].includes(String(rating))) {
    show_notification('error', 'Please select a rating (1–5 stars).');
    return;
  }

  const payload = {
    user_id: userID,            // if your backend needs it
    worker_id: workerId,
    booking_id: bookingId,
    rating: Number(rating),
    feedback: feedback
  };

  // Loading state
  $btn.prop('disabled', true).data('orig', $btn.html());
  $btn.html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting…');

  $.ajax({
    url: "{{ url('feedback/worker') }}",
    method: "POST",
    headers: { 'X-CSRF-TOKEN': csrf },
    data: payload,
    success: function (res) {
      if (res?.ok) {
        show_notification('success', res.message || 'Thanks! Your feedback was submitted.');
        // Optional: clear form
        $form.find(`input[name="rating_${bookingId}"]`).prop('checked', false);
        $form.find('textarea[name="feedback"]').val('');
      } else {
        show_notification('error', res?.message || 'Could not submit feedback.');
      }
    },
    error: function (xhr) {
      let msg = 'Server error while submitting feedback.';
      try { const j = JSON.parse(xhr.responseText); if (j?.message) msg = j.message; } catch(e){}
      show_notification('error', msg);
      console.error(xhr.responseText);
    },
    complete: function () {
      $btn.prop('disabled', false).html($btn.data('orig'));
    }
  });
});

</script>
