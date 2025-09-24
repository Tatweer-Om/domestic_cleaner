
<script>

window.IS_AUTH = {{ auth()->check() ? 'true' : 'false' }};
const IS_AUTH = !!(window.IS_AUTH);

// ------------ Helpers ------------
function addDays(isoDate, n) {
  const d = new Date(isoDate + 'T00:00:00');
  d.setDate(d.getDate() + n);
  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
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
  const durationBadge = duration === '5'
    ? <?php echo json_encode(trans('messages.duration_5h', [], session('locale'))); ?>
    : <?php echo json_encode(trans('messages.duration_4h', [], session('locale'))); ?>;

  // Calculate time ranges based on duration
  const morningTimeRange = duration === '5'
    ? <?php echo json_encode(trans('messages.morning_5h', [], session('locale'))); ?>
    : <?php echo json_encode(trans('messages.morning_4h', [], session('locale'))); ?>;

  const eveningTimeRange = duration === '5'
    ? <?php echo json_encode(trans('messages.evening_5h', [], session('locale'))); ?>
    : <?php echo json_encode(trans('messages.evening_4h', [], session('locale'))); ?>;

  // Determine message based on availability
  let availabilityMessage = '';
  if (!availability.is_available) {
    const isWorkerStatusIssue = availability.worker_status && !availability.worker_available;
    const colorClass = isWorkerStatusIssue ? 'warning' : 'danger';
    const iconClass = isWorkerStatusIssue ? 'fa-exclamation-triangle' : 'fa-times-circle';

    availabilityMessage = `
      <div class="alert alert-${colorClass} d-flex align-items-center py-1 px-2 small mb-2 rounded-2">
        <i class="fa ${iconClass} me-2"></i>
        <div>
          <strong>${availability.message || "<?php echo trans('messages.date_not_available', [], session('locale')); ?>"}</strong>
          ${availability.opposite_shift_available && !isWorkerStatusIssue 
            ? `<div class="text-muted small">💡 ${availability.opposite_shift} <?php echo trans('messages.opposite_shift_available', [], session('locale')); ?></div>` 
            : ''}
          ${availability.next_available_date && !isWorkerStatusIssue 
            ? `<div class="text-muted small">📅 <?php echo trans('messages.next_available_date', [], session('locale')); ?>: ${availability.next_available_date}</div>` 
            : ''}
          ${isWorkerStatusIssue 
            ? `<div class="text-muted small"><?php echo trans('messages.try_different_date', [], session('locale')); ?></div>` 
            : ''}
        </div>
      </div>`;
  } else {
    availabilityMessage = `
      <div class="alert alert-success d-flex align-items-center py-1 px-2 small mb-2 rounded-2">
        <i class="fa fa-check-circle me-2"></i>
        <div>
          <strong><?php echo trans('messages.available', [], session('locale')); ?></strong>
          <div class="text-muted small">✅ <?php echo trans('messages.worker_ready_for_shift', [], session('locale')); ?> ${shift} - ${date}</div>
        </div>
      </div>`;
  }

  const availabilityContainerId = `availability-container-${index}`;
  return `
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="mini-cal mini-cal--sm shadow-sm rounded-4">
        <div class="mini-cal-head d-flex justify-content-between align-items-center">
          <div class="label">
            <?php echo json_encode(trans('messages.visit', [], session('locale'))); ?> ${index}${visitLabel}
          </div>
          <span class="duration-badge">${durationBadge}</span>
        </div>
        <div class="mini-cal-body">
          <div class="date-wrap mb-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M7 3v4M17 3v4M3 9h18M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <input type="text" class="mini-date" value="${date}" placeholder=<?php echo json_encode(trans('messages.select_date', [], session('locale'))); ?> readonly>
            <small class="day-name">${dayName}</small>
          </div>
          <div class="group">
            <div class="group-title"><?php echo json_encode(trans('messages.shift', [], session('locale'))); ?></div>
            <div class="pills">
              <input type="radio" id="${mId}" name="${shiftGroup}" class="btn-check" ${mChecked}>
              <label for="${mId}" class="pill pill-success pill-xs">
                <span class="ico">☀️</span> <?php echo json_encode(trans('messages.morning', [], session('locale'))); ?> 
                <small class="text-muted">(${morningTimeRange})</small>
              </label>
              <input type="radio" id="${eId}" name="${shiftGroup}" class="btn-check" ${eChecked}>
              <label for="${eId}" class="pill pill-success pill-xs">
                <span class="ico">🌙</span> <?php echo json_encode(trans('messages.evening', [], session('locale'))); ?> 
                <small class="text-muted">(${eveningTimeRange})</small>
              </label>
            </div>
          </div>
          <div class="availability-message-container" id="${availabilityContainerId}">${availabilityMessage}</div>
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

  if (!($pkg.val() || '').trim()) {
    show_notification(
      'error',
      <?php echo json_encode(trans('messages.please_choose_package', [], session('locale'))); ?>
    );
    scrollFocus($pkg);
    return false;
  }

  if (!($worker.val() || '').trim()) {
    show_notification(
      'error',
      <?php echo json_encode(trans('messages.please_choose_worker', [], session('locale'))); ?>
    );
    scrollFocus($worker);
    return false;
  }

  const dateVal = ($date.val() || '').trim();
  if (!dateVal) {
    show_notification(
      'error',
      <?php echo json_encode(trans('messages.please_choose_start_date', [], session('locale'))); ?>
    );
    scrollFocus($date);
    return false;
  }

  const d = new Date(dateVal + 'T00:00:00');
  if (isNaN(d.getTime())) {
    show_notification(
      'error',
      <?php echo json_encode(trans('messages.please_choose_valid_start_date', [], session('locale'))); ?>
    );
    scrollFocus($date);
    return false;
  }

  if (!$dur4.is(':checked') && !$dur5.is(':checked')) {
    show_notification(
      'error',
      <?php echo json_encode(trans('messages.please_select_duration_4h_5h', [], session('locale'))); ?>
    );
    scrollFocus($dur4);
    return false;
  }

  if (!$('input[name="shift"]:checked').length) {
    show_notification(
      'error',
      <?php echo json_encode(trans('messages.please_select_shift_morning_evening', [], session('locale'))); ?>
    );
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
  return getSelectedDuration();
}

function refreshDurationBadges() {
  const dur = getSelectedDuration();
  if (!dur) return;
  const text = dur === '5'
    ? <?php echo json_encode(trans('messages.duration_5h', [], session('locale'))); ?>
    : <?php echo json_encode(trans('messages.duration_4h', [], session('locale'))); ?>;

  $('#visitTiles .duration-badge').text(text);

  const morningTimeRange = dur === '5'
    ? <?php echo json_encode(trans('messages.morning_5h', [], session('locale'))); ?>
    : <?php echo json_encode(trans('messages.morning_4h', [], session('locale'))); ?>;

  const eveningTimeRange = dur === '5'
    ? <?php echo json_encode(trans('messages.evening_5h', [], session('locale'))); ?>
    : <?php echo json_encode(trans('messages.evening_4h', [], session('locale'))); ?>;

  $('#visitTiles .pill').each(function() {
    const $pill = $(this);
    const isMorning = $pill.text().includes('Morning');
    const isEvening = $pill.text().includes('Evening');

    if (isMorning) {
      $pill.html($pill.html().replace(/\([^)]+\)/, `(${morningTimeRange})`));
    } else if (isEvening) {
      $pill.html($pill.html().replace(/\([^)]+\)/, `(${eveningTimeRange})`));
    }
  });
}

$(document).on('change', '#dur4, #dur5', refreshDurationBadges);

// ------------ CSRF ------------
const token = $('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({
  headers: { 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' }
});

$(document).ready(function() {
  // Translation / route strings
  const T_FRIDAYS_ARE_OFF = <?php echo json_encode(trans('messages.fridays_are_off', [], session('locale'))); ?>;
  const T_DATE_NOT_AVAILABLE = <?php echo json_encode(trans('messages.date_not_available', [], session('locale'))); ?>;
  const T_OPPOSITE_SHIFT_AVAILABLE = <?php echo json_encode(trans('messages.opposite_shift_available', [], session('locale'))); ?>;
  const T_NEXT_AVAILABLE_DATE = <?php echo json_encode(trans('messages.next_available_date', [], session('locale'))); ?>;
  const T_TRY_DIFFERENT_DATE = <?php echo json_encode(trans('messages.try_different_date', [], session('locale'))); ?>;
  const T_AVAILABLE = <?php echo json_encode(trans('messages.available', [], session('locale'))); ?>;
  const T_WORKER_READY_FOR_SHIFT = <?php echo json_encode(trans('messages.worker_ready_for_shift', [], session('locale'))); ?>;
  const T_FAILED_TO_CHECK_AVAILABILITY = <?php echo json_encode(trans('messages.failed_to_check_availability', [], session('locale'))); ?>;

  const CHECK_AVAILABILITY_URL = <?php echo json_encode(route('check.availability')); ?>;

  // Date bounds: today -> last day of next month
  const today = new Date();
  const nextMonthEnd = new Date(today.getFullYear(), today.getMonth() + 2, 0);

  // Helper: mark Friday cells
  function markFridayCells(instance) {
    const dayCells = instance.calendarContainer.querySelectorAll('.flatpickr-day');
    dayCells.forEach(cell => {
      const dt = cell.dateObj;
      if (dt && typeof dt.getDay === 'function' && dt.getDay() === 5) {
        cell.classList.add('friday');
        cell.title = T_FRIDAYS_ARE_OFF;
      } else {
        cell.classList.remove('friday');
      }
    });
  }

  // Main start date picker
  const startDatePicker = flatpickr("#startDate", {
    dateFormat: "Y-m-d",
    minDate: new Date().fp_incr(1),
    maxDate: nextMonthEnd,
    showMonths: 2,
    disable: [
      function(date) { return date.getDay() === 5; }
    ],
    onChange: function(selectedDates, dateStr, instance) {
      $('#startDate').trigger('change');
    },
    onReady: function(selectedDates, dateStr, instance) {
      markFridayCells(instance);
    },
    onMonthChange: function(selectedDates, dateStr, instance) {
      markFridayCells(instance);
    }
  });

  // Mini datepicker initializer for tiles
  window.initMiniDatePicker = function(element) {
    return flatpickr(element, {
      dateFormat: "Y-m-d",
      minDate: new Date().fp_incr(1),
      maxDate: nextMonthEnd,
      showMonths: 2,
      disable: [
        function(date) { return date.getDay() === 5; }
      ],
      onChange: function(selectedDates, dateStr, instance) {
        const $input = $(element);
        const $tile = $input.closest('.mini-cal');
        const $availabilityContainer = $tile.find('.availability-message-container');

        if (!dateStr) {
          $input.siblings('.day-name').text('');
          $input.data('prev', '');
          $availabilityContainer.empty();
          return;
        }

        const dayName = new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'short' });
        $input.siblings('.day-name').text(dayName);

        const $checkedBtn = $tile.find('.btn-check:checked');
        let shift = null;
        if ($checkedBtn.length && $checkedBtn.attr('id')) {
          shift = $checkedBtn.attr('id').includes('shift-m') ? 'morning' : 'evening';
        }

        const worker_id = $('#worker_id').val();

        if (worker_id && shift) {
          $.ajax({
            url: CHECK_AVAILABILITY_URL,
            method: "POST",
            data: {
              worker_id: worker_id,
              date: dateStr,
              shift: shift,
              _token: '<?php echo csrf_token(); ?>'
            },
            success: function(res) {
              let availabilityMessage = '';
              if (!res.is_available) {
                const isWorkerStatusIssue = res.worker_status && !res.worker_available;
                const colorClass = isWorkerStatusIssue ? 'warning' : 'danger';
                const iconClass = isWorkerStatusIssue ? 'fa-exclamation-triangle' : 'fa-times-circle';

                availabilityMessage = `
                  <div class="alert alert-${colorClass} d-flex align-items-center py-1 px-2 small mb-2 rounded-2">
                    <i class="fa ${iconClass} me-2"></i>
                    <div>
                      <strong>${res.message || T_DATE_NOT_AVAILABLE}</strong>
                      ${res.opposite_shift_available && !isWorkerStatusIssue 
                        ? `<div class="text-muted small">💡 ${T_OPPOSITE_SHIFT_AVAILABLE}</div>` 
                        : ''}
                      ${res.next_available_date && !isWorkerStatusIssue 
                        ? `<div class="text-muted small">📅 ${T_NEXT_AVAILABLE_DATE}: ${res.next_available_date}</div>` 
                        : ''}
                      ${isWorkerStatusIssue 
                        ? `<div class="text-muted small">${T_TRY_DIFFERENT_DATE}</div>` 
                        : ''}
                    </div>
                  </div>`;
                $availabilityContainer.html(availabilityMessage);
                show_notification(
                  'error',
                  res.message || <?php echo json_encode(trans('messages.date_not_available', ['date' => '${dateStr}', 'shift' => '${shift}'], session('locale'))); ?>
                );
              } else {
                availabilityMessage = `
                  <div class="alert alert-success d-flex align-items-center py-1 px-2 small mb-2 rounded-2">
                    <i class="fa fa-check-circle me-2"></i>
                    <div>
                      <strong>${T_AVAILABLE}</strong>
                      <div class="text-muted small">✅ ${T_WORKER_READY_FOR_SHIFT.replace('%shift%', shift).replace('%date%', dateStr)}</div>
                    </div>
                  </div>`;
                $availabilityContainer.html(availabilityMessage);
                show_notification(
                  'success',
                  <?php echo json_encode(trans('messages.available', [], session('locale'))); ?>
                );
              }

              $input.data('prev', dateStr);
              updateActionButton && updateActionButton();
            },
            error: function() {
              $availabilityContainer.html(`
                <div class="alert alert-danger d-flex align-items-center py-1 px-2 small mb-2 rounded-2">
                  <i class="fa fa-times-circle me-2"></i>
                  <div><strong>${T_FAILED_TO_CHECK_AVAILABILITY}</strong></div>
                </div>
              `);
              show_notification(
                'error',
                <?php echo json_encode(trans('messages.failed_to_check_availability', [], session('locale'))); ?>
              );
              updateActionButton && updateActionButton();
            }
          });
        } else {
          $input.data('prev', '');
          $availabilityContainer.empty();
        }
      },
      onReady: function(selectedDates, dateStr, instance) {
        markFridayCells(instance);
      },
      onMonthChange: function(selectedDates, dateStr, instance) {
        markFridayCells(instance);
      }
    });
  };

  // Function to extract worker_id from URL
  function getWorkerIdFromUrl() {
    const path = window.location.pathname;
    const match = path.match(/\/worker_profile\/(\d+)/);
    return match ? match[1] : '';
  }

  // ------------ Action row builder ------------
  function buildActionRowHtml() {
    const commonBtn = `
      style="background-color:#198754; border-color:#198754; color:#fff;"
      class="btn btn-success btn-sm w-100 mt-3"
    `;
    console.log('Building action row. IS_AUTH:', IS_AUTH, 'window.IS_AUTH:', window.IS_AUTH);
    if (IS_AUTH) {
      return `
        <div class="col-12" id="paymentBtnRow">
          <button type="button" id="paymentBtn" ${commonBtn}>
            <?php echo json_encode(trans('messages.pay_now', [], session('locale'))); ?>
          </button>
        </div>
      `;
    } else {
      return `
        <div class="col-12" id="proceedBtnRow">
          <button type="button" id="proceedBtn" ${commonBtn}>
            <?php echo json_encode(trans('messages.proceed', [], session('locale'))); ?>
          </button>
        </div>
      `;
    }
  }

  // ------------ Update Action Button State ------------
  function updateActionButton() {
    const hasConflicts = $('#visitTiles .alert.alert-danger, #visitTiles .alert.alert-warning').length > 0;
    $('#proceedBtn, #paymentBtn').prop('disabled', hasConflicts);
  }

  // ------------ Collect Visits from Tiles ------------
  function collectVisitsFromTiles() {
    const visits = [];
    $('#visitTiles .mini-cal').each(function(index) {
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
        success: function(res) {
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
        error: function() {
          conflicts.push({
            visit_number: visit.visit_number,
            date: visit.date,
            shift: visit.shift,
            message: <?php echo json_encode(trans('messages.failed_to_check_availability', [], session('locale'))); ?>,
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
  $('#generateBtn').on('click', function(e) {
    e.preventDefault();

    if (!validateTopFormFirstError()) return;

    const selectedShift = $('input[name="shift"]:checked').val();
    const payload = {
      package_id: $('#packageSelect').val(),
      worker_id: $('#worker_id').val(),
      start_date: $('#startDate').val(),
      shift_morning: selectedShift === 'morning' ? 1 : 0,
      shift_evening: selectedShift === 'evening' ? 1 : 0,
      duration_4: $('#dur4').is(':checked') ? 1 : 0,
      duration_5: $('#dur5').is(':checked') ? 1 : 0
    };

    const duration = payload.duration_4 ? '4' : (payload.duration_5 ? '5' : null);
    if (!duration) {
      show_notification(
        'error',
        <?php echo json_encode(trans('messages.please_select_duration_before_generating', [], session('locale'))); ?>
      );
      return;
    }

    const shift = selectedShift;
    if (!shift) {
      show_notification(
        'error',
        <?php echo json_encode(trans('messages.please_select_shift_before_generating', [], session('locale'))); ?>
      );
      return;
    }

    $.ajax({
      url: "{{ route('generate.booking') }}",
      method: "POST",
      data: payload,
      success: function(res) {
        const tiles = $('#visitTiles');
        tiles.empty();

        if (res.status === 'error') {
          tiles.html(
            '<div class="col-12"><div class="alert alert-danger rounded-3">' +
            <?php echo json_encode(trans('messages.unable_generate_visits', [], session('locale'))); ?> +
            ': ' + (res.message || <?php echo json_encode(trans('messages.unknown_error', [], session('locale'))); ?>) +
            '</div></div>'
          );
          return;
        }

        const visitCount = res.visit_count || (Array.isArray(res.visits) ? res.visits.length : 0);
        if (!visitCount) {
          tiles.html(
            '<div class="col-12"><div class="alert alert-info rounded-3">' +
            <?php echo json_encode(trans('messages.no_visits_for_package', [], session('locale'))); ?> +
            '</div></div>'
          );
          return;
        }
if (res.package_price !== null) {
    $('#packagePriceBox').html(`
        <div class="my-3 d-flex justify-content-center">
            <span class="badge rounded-pill bg-success shadow-lg py-3 px-4" style="font-size: 1.4rem; min-width: 220px; text-align: center;">
                <i class="fa fa-tag me-2"></i>
                ${res.package_price} <small>OMR</small>
            </span>
        </div>
    `);
} else {
    $('#packagePriceBox').empty();
}




        let html = '';
        res.worker_availability.forEach((availability, index) => {
          const visit = Array.isArray(res.visits) ? res.visits[index] : null;
          html += buildTileHTML(index + 1, availability.date, availability.shift, duration, visit, availability);
        });

        tiles.html(html);
        $('#proceedBtnRow, #paymentBtnRow').remove();
        tiles.append(buildActionRowHtml());
        updateActionButton();

        if (typeof WOW !== 'undefined') { new WOW().init(); }
        refreshDurationBadges();

        $('#visitTiles .mini-date').each(function() {
          if (!$(this).data('flatpickr')) {
            window.initMiniDatePicker(this);
          }
        });

        // Check availability on shift change
        $('#visitTiles').on('change', '.btn-check', function() {
          const $tile = $(this).closest('.mini-cal');
          const $availabilityContainer = $tile.find('.availability-message-container');
          const date = $tile.find('.mini-date').val();
          const shift = $(this).attr('id').includes('shift-m') ? 'morning' : 'evening';
          const worker_id = $('#worker_id').val();

          if (!date) return;

          $.ajax({
            url: "{{ route('check.availability') }}",
            method: "POST",
            data: { worker_id, date, shift },
            success: function(res) {
              let availabilityMessage = '';
              if (!res.is_available) {
                const isWorkerStatusIssue = res.worker_status && !res.worker_available;
                const colorClass = isWorkerStatusIssue ? 'warning' : 'danger';
                const iconClass = isWorkerStatusIssue ? 'fa-exclamation-triangle' : 'fa-times-circle';

                availabilityMessage = `
                  <div class="alert alert-${colorClass} d-flex align-items-center py-1 px-2 small mb-2 rounded-2">
                    <i class="fa ${iconClass} me-2"></i>
                    <div>
                      <strong>${res.message || <?php echo json_encode(trans('messages.date_not_available', ['date' => '${date}', 'shift' => '${shift}'], session('locale'))); ?>}</strong>
                      ${res.opposite_shift_available && !isWorkerStatusIssue 
                        ? `<div class="text-muted small">💡 ${<?php echo json_encode(trans('messages.shift_available', ['shift' => '${res.opposite_shift}'], session('locale'))); ?>}</div>` 
                        : ''}
                      ${res.next_available_date && !isWorkerStatusIssue 
                        ? `<div class="text-muted small">📅 ${<?php echo json_encode(trans('messages.next_available_date', ['shift' => '${shift}', 'date' => '${res.next_available_date}'], session('locale'))); ?>}</div>` 
                        : ''}
                      ${isWorkerStatusIssue 
                        ? `<div class="text-muted small">${<?php echo json_encode(trans('messages.try_different_date', [], session('locale'))); ?>}</div>` 
                        : ''}
                    </div>
                  </div>`;
                $availabilityContainer.html(availabilityMessage);
                show_notification(
                  'error',
                  res.message || <?php echo json_encode(trans('messages.date_not_available', ['date' => '${date}', 'shift' => '${shift}'], session('locale'))); ?>
                );
              } else {
                availabilityMessage = `
                  <div class="alert alert-success d-flex align-items-center py-1 px-2 small mb-2 rounded-2">
                    <i class="fa fa-check-circle me-2"></i>
                    <div>
                      <strong>${T_AVAILABLE}</strong>
                      <div class="text-muted small">✅ ${T_WORKER_READY_FOR_SHIFT.replace('%shift%', shift).replace('%date%', date)}</div>
                    </div>
                  </div>`;
                $availabilityContainer.html(availabilityMessage);
                show_notification(
                  'success',
                  <?php echo json_encode(trans('messages.available', [], session('locale'))); ?>
                );
              }

              updateActionButton && updateActionButton();
            },
            error: function() {
              $availabilityContainer.html(`
                <div class="alert alert-danger d-flex align-items-center py-1 px-2 small mb-2 rounded-2">
                  <i class="fa fa-times-circle me-2"></i>
                  <div><strong>${T_FAILED_TO_CHECK_AVAILABILITY}</strong></div>
                </div>
              `);
              show_notification(
                'error',
                <?php echo json_encode(trans('messages.failed_to_check_availability', [], session('locale'))); ?>
              );
              updateActionButton && updateActionButton();
            }
          });
        });
      },
      error: function(xhr) {
        console.error("Error:", xhr.responseJSON || xhr.responseText);
        show_notification('error', xhr.responseJSON?.message || 'Failed to generate visits.');
      }
    });
  });

  // ------------ Payment Handler ------------
  $(document).on('click', '#paymentBtn', function(e) {
    e.preventDefault();
    console.log('Pay Now button clicked!');

    if (window.PENDING_BOOKING_NO) {
      console.log('Using pending booking number:', window.PENDING_BOOKING_NO);
      show_notification('success', 'Redirecting to checkout...');
      window.location.href = `/checkout/${window.PENDING_BOOKING_NO}`;
      return;
    }

    console.log('No pending booking, saving booking first...');
    saveBookingBeforeAuth(function(result) {
      console.log('Save booking result:', result);
      if (result && result.ok && result.booking_no) {
        show_notification('success', 'Redirecting to checkout...');
        window.location.href = `/checkout/${result.booking_no}`;
      } else {
        show_notification(
          'error',
          <?php echo json_encode(trans('messages.failed_create_booking_try_again', [], session('locale'))); ?>
        );
      }
    });
  });

  // Save booking before auth and stash booking_no
  function saveBookingBeforeAuth(callback) {
    if (!validateTopFormFirstError()) {
      if (callback) callback({ ok: false, error: 'invalid_form' });
      return;
    }

    const package_id = $('#packageSelect').val();
    const worker_id = $('#worker_id').val();
    const start_date = $('#startDate').val();
    const duration = getSelectedDuration2();
    const visits = collectVisitsFromTiles();

    if (!visits.length) {
      show_notification(
        'error',
        <?php echo json_encode(trans('messages.add_at_least_one_visit', [], session('locale'))); ?>
      );
      if (callback) callback({ ok: false, error: 'no_visits' });
      return;
    }

    validateVisits(visits, worker_id, function(conflicts) {
      if (conflicts.length) {
        let html = <?php echo json_encode(trans('messages.cannot_proceed_due_to', [], session('locale'))); ?> + '<ul>';
        conflicts.forEach(c => { html += `<li>Visit ${c.visit_number}: ${c.message}</li>`; });
        html += '</ul>';
        show_notification('error', html);
        if (callback) callback({ ok: false, error: 'conflicts' });
        return;
      }

      const payload = { _token: $('meta[name="csrf-token"]').attr('content'), package_id, worker_id, start_date, duration, visits, subtotal: 0, discount: 0, total_amount: 0 };
      $.ajax({
        url: "{{ route('save_booking') }}",
        type: 'POST',
        data: JSON.stringify(payload),
        contentType: 'application/json',
        success: function(res) {
          if (res.ok) {
            window.PENDING_BOOKING_NO = res.booking_no;
            if (callback) callback({ ok: true, booking_no: res.booking_no });
          } else {
            show_notification(
              'error',
              res.message || <?php echo json_encode(trans('messages.unable_create_booking', [], session('locale'))); ?>
            );
            if (callback) callback({ ok: false, error: 'save_failed' });
          }
        },
        error: function(xhr) {
          show_notification(
            'error',
            (xhr.responseJSON && (xhr.responseJSON.error || xhr.responseJSON.message)) || <?php echo json_encode(trans('messages.failed_save_booking', [], session('locale'))); ?>
          );
          if (callback) callback({ ok: false, error: 'save_error' });
        }
      });
    });
  }

  // ------------ Optional tiny style for duration badge ------------
  (function() {
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

  // Ensure only one duration checkbox is checked
  $(document).on('change', '.duration-check', function() {
    if (this.checked) {
      $('.duration-check').not(this).prop('checked', false);
    }
    refreshDurationBadges();
  });

  // ------------ Actions ------------
  $(document).on('click', '#proceedBtn', function() {
    saveBookingBeforeAuth(function(result) {
      if (!result || !result.ok) return;
      const booking_no = result.booking_no || window.PENDING_BOOKING_NO;
      if (IS_AUTH || window.IS_AUTH === true) {
        if (booking_no) { window.location.href = `/checkout/${booking_no}`; }
        return;
      }
      const $auth = $('#authShell');
      $auth.removeClass('d-none');
      $('#registerPane').removeClass('d-none');
      $('#loginPane').addClass('d-none');
      $('#tabRegister').removeClass('btn-outline-success').addClass('btn-success');
      $('#tabLogin').removeClass('btn-success').addClass('btn-outline-success');
    });
  });

  // Auth tabs
  $(document).on('click', '#tabRegister', function() {
    $('#registerPane').removeClass('d-none');
    $('#loginPane').addClass('d-none');
    $('#tabRegister').removeClass('btn-outline-success').addAddClass('btn-success');
    $('#tabLogin').removeClass('btn-success').addClass('btn-outline-success');
    $('#registerForm input[name="name"]').trigger('focus');
  });

  $(document).on('click', '#tabLogin', function() {
    $('#loginPane').removeClass('d-none');
    $('#registerPane').addClass('d-none');
    $('#tabLogin').removeClass('btn-outline-success').addClass('btn-success');
    $('#tabRegister').removeClass('btn-success').addClass('btn-outline-success');
    $('#loginForm input[name="email"]').trigger('focus');
  });

  $(document).on('submit', '#registerForm', function(e) {
    e.preventDefault();
    const $btn = $(this).find('button[type="submit"]');
    $btn.prop('disabled', true);
    const fd = new FormData(this);
    if (!fd.has('form_index')) { fd.append('form_index', '2'); }

    $.ajax({
      url: "{{ route('register.ajax') }}",
      method: "POST",
      data: fd,
      processData: false,
      contentType: false,
      success: function(res) {
        if (res.status === 'success') {
          show_notification(
            'success',
            res.message || <?php echo json_encode(trans('messages.registered', [], session('locale'))); ?>
          );
          const booking_no = window.PENDING_BOOKING_NO || null;
          if (res.logged_in && booking_no) { window.location.href = `/checkout/${booking_no}`; return; }
          if (res.redirect_url) { window.location.href = res.redirect_url; return; }
          $('#tabLogin').trigger('click');
        } else {
          show_notification('error', res.message || 'Registration failed.');
        }
      },
      error: function(xhr) {
        if (xhr.status === 422 && xhr.responseJSON?.errors) {
          const errors = xhr.responseJSON.errors;
          const firstKey = Object.keys(errors)[0];
          show_notification('error', errors[firstKey][0]);
        } else {
          show_notification(
            'error',
            <?php echo json_encode(trans('messages.server_error_try_again', [], session('locale'))); ?>
          );
        }
      },
      complete: function() {
        $btn.prop('disabled', false);
      }
    });
  });

  $(document).on('submit', '#loginForm', function(e) {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    e.preventDefault();

    const $form = $(this);
    const $btn = $form.find('button[type="submit"]');
    $btn.prop('disabled', true);

    const identifier = ($form.find('#phone_name').val() || '').trim();
    const password = ($form.find('input[name="password"]').val() || '').trim();
    const form_1 = $form.find('input[name="form_1"]').val() || '1';

    if (!identifier) {
      show_notification(
        'error',
        <?php echo json_encode(trans('messages.enter_username_or_phone', [], session('locale'))); ?>
      );
      $btn.prop('disabled', false);
      return;
    }

    if (!password) {
      show_notification(
        'error',
        <?php echo json_encode(trans('messages.enter_password', [], session('locale'))); ?>
      );
      $btn.prop('disabled', false);
      return;
    }

    const fd = new FormData();
    fd.append('identifier', identifier);
    fd.append('password', password);
    fd.append('form_1', form_1);

    $.ajax({
      url: "{{ route('login.ajax') }}",
      method: "POST",
      data: fd,
      processData: false,
      contentType: false,
      success: function(res) {
        if (res.status === 'success' || res.ok === true) {
          show_notification(
            'success',
            res.message || <?php echo json_encode(trans('messages.logged_in', [], session('locale'))); ?>
          );
          const booking_no = window.PENDING_BOOKING_NO || null;
          if (booking_no) { window.location.href = `/checkout/${booking_no}`; return; }
          if (res.redirect_url) { window.location.href = res.redirect_url; return; }
        } else {
          show_notification(
            'error',
            res.message || <?php echo json_encode(trans('messages.invalid_credentials', [], session('locale'))); ?>
          );
        }
      },
      complete: function() {
        $btn.prop('disabled', false);
      }
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('btnLogout');
    if (!logoutBtn) return;

    logoutBtn.addEventListener('click', function(e) {
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

  document.addEventListener('input', function(e) {
    if (e.target.classList.contains('mini-date')) {
      const value = e.target.value;
      const wrapper = e.target.closest('.d-flex.align-items-center.justify-content-between');
      if (value && wrapper) {
        const newDayName = new Date(value + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'short' });
        const dayEl = wrapper.querySelector('.day-name');
        if (dayEl) {
          dayEl.textContent = newDayName;
        }
      }
    }
  });
});
</script>