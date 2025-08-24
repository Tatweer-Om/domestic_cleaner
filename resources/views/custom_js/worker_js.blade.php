
<script>
$(function () {
  // === Constants ===
  const PLACEHOLDER_IMG = "{{ asset('images/dummy_images/cover-image-icon.png') }}";
  const ADD_URL    = "{{ url('add_worker') }}";
  const UPDATE_URL = "{{ url('update_worker') }}";
  const LIST_URL   = "{{ url('show_worker') }}";

  // === Selectpicker init ===
  if ($.fn.selectpicker) {
    $('select.worker_user_id, select.location_id').selectpicker(); // init once
  }

  // === DataTable ===
  const workersTable = $('#all_workers').DataTable({
    ajax: { url: LIST_URL, type: 'GET' },
    bFilter: true,
    pagingType: 'numbers',
    ordering: true,
    order: [[6, 'desc']]
  });

  // === Helpers ===
  function isTextInput(el) {
    if (!el) return false;
    const tag = el.tagName ? el.tagName.toLowerCase() : '';
    const type = (el.type || '').toLowerCase();
    return (
      tag === 'input' ||
      tag === 'textarea' ||
      tag === 'select' ||
      el.isContentEditable ||
      type === 'file'
    );
  }

  function resetAddWorkerModal($modal) {
    const $form = $modal.find('form.add_worker');
    if (!$form.length) return;

    // Reset form fields
    if ($form[0]) $form[0].reset();
    $form.find('.worker_id').val(''); // ensure add mode

    // Reset selectpickers
    $form.find('select.worker_user_id, select.location_id').each(function () {
      $(this).val('');
      if ($.fn.selectpicker) $(this).selectpicker('refresh');
    });

    // Reset image
    $form.find('#imageUpload').val('');
    $form.find('#imagePreview').attr('src', PLACEHOLDER_IMG);
    $form.find('#removeImage').hide();

    // Clear validation states
    $form.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
  }

  // === Modal lifecycle ===
  $('#add_worker_modal')
    .on('hidden.bs.modal', function () {
      resetAddWorkerModal($(this)); // always clean after close
    })
    .on('show.bs.modal', function () {
      const $form = $(this).find('form.add_worker');
      const id = $.trim($form.find('.worker_id').val());
      if (!id) resetAddWorkerModal($(this)); // only reset on "add" show
    });

  // === Image: click to pick, preview, remove ===
  $(document)
    .off('click', '#imagePreview')
    .on('click', '#imagePreview', function () {
      $('#imageUpload').trigger('click');
    });

  $(document)
    .off('change', '#imageUpload')
    .on('change', '#imageUpload', function (e) {
      const file = e.target.files && e.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function (ev) {
        $('#imagePreview').attr('src', ev.target.result);
        $('#removeImage').show();
      };
      reader.readAsDataURL(file);
    });

  $(document)
    .off('click', '#removeImage')
    .on('click', '#removeImage', function () {
      $('#imagePreview').attr('src', PLACEHOLDER_IMG);
      $('#imageUpload').val(''); // clear file input
      $(this).hide();
    });

  // === Enter key: open file chooser (don’t submit form) ===
  // Trigger only when the add/edit modal is visible and user isn't typing in a text field
  $(document).on('keydown', function (e) {
    if (e.key === 'Enter' && $('#add_worker_modal').is(':visible')) {
      const active = document.activeElement;
      if (!isTextInput(active)) {
        e.preventDefault(); // stop submit
        $('#imageUpload').trigger('click'); // open chooser
      }
    }
  });

  // === Submit (Add/Update) ===
  $(document)
    .off('submit', 'form.add_worker')
    .on('submit', 'form.add_worker', function (e) {
      e.preventDefault();

      const $form = $(this);
      const id    = $.trim($form.find('.worker_id').val());
      const name  = $.trim($form.find('.worker_name').val());
      const phone = $.trim($form.find('.phone').val());
      const location_id = $.trim($form.find('select.location_id').val());
      const worker_user_id = $form.find('select.worker_user_id').val(); // null/"" when empty

      // Basic validations
      if (!name) {
        show_notification('error', '<?php echo trans('messages.add_worker_name_lang',[],session('locale')); ?>');
        return;
      }
      if (!phone) {
        show_notification('error', '<?php echo trans('messages.add_worker_phone_lang',[],session('locale')); ?>');
        return;
      }
      if (!location_id) {
        show_notification('error', '<?php echo trans('messages.add_worker_location_lang',[],session('locale')); ?>');
        return;
      }
      if (!worker_user_id) {
        show_notification('error', '<?php echo trans('messages.add_worker_user_id_lang',[],session('locale')); ?>');
        return;
      }

      // Build payload (FormData picks up file + all inputs including name="location_id")
      const formData = new FormData($form[0]);
      // If your Blade form already has @csrf, _token is included automatically.

      // Optional: debug what’s being sent (remove in prod)
      // for (const [k, v] of formData.entries()) console.log(k, v);

      showPreloader();
      before_submit();

      $.ajax({
        type: 'POST',
        url: id ? UPDATE_URL : ADD_URL,
        data: formData,
        contentType: false,
        processData: false,
        success: function () {
          hidePreloader();
          after_submit();
          show_notification(
            'success',
            id
              ? '<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>'
              : '<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
          );

          // Close modal (hidden event will reset it)
          $('#add_worker_modal').modal('hide');

          // Reload table (stay on same page)
          workersTable.ajax.reload(null, false);
        },
        error: function (xhr) {
          hidePreloader();
          after_submit();

          let msg = id
            ? '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>'
            : '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>';

          if (xhr && xhr.responseJSON && xhr.responseJSON.errors) {
            const errs = xhr.responseJSON.errors;
            Object.keys(errs).forEach(function (field) {
              $form.find('[name="' + field + '"]').addClass('is-invalid');
            });
            const firstKey = Object.keys(errs)[0];
            if (firstKey) msg = errs[firstKey][0];
          }

          show_notification('error', msg);
          workersTable.ajax.reload(null, false);
        }
      });
    });

  // === If you set values programmatically in edit flow, refresh selectpicker ===
  // Example (call this after you fill the form with fetched data):
  // $form.find('select.location_id').val(locationId);
  // if ($.fn.selectpicker) $form.find('select.location_id').selectpicker('refresh');
  // $form.find('select.worker_user_id').val(userId);
  // if ($.fn.selectpicker) $form.find('select.worker_user_id').selectpicker('refresh');
});




function edit(id) {
    $('#global-loader').show();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        dataType: 'JSON',
        url: "{{ url('edit_worker') }}",
        method: "POST",
        data: { id: id, _token: csrfToken },
        success: function(fetch) {
            $('#global-loader').hide();
            after_submit();
            if (fetch != "") {
                $(".worker_name").val(fetch.worker_name);
                $(".worker_id").val(fetch.worker_id);
                $(".phone").val(fetch.phone);
                $(".notes").val(fetch.notes);
                $(".worker_image").attr("src", fetch.worker_image);
                  $(".worker_user_id").val(fetch.worker_user_id).trigger('change');
                $('.worker_user_id').selectpicker('refresh');
                 $(".location_id").val(fetch.location_id).trigger('change');
                $('.location_id').selectpicker('refresh');
                  $(".shift").val(fetch.shift).trigger('change');
                $('.shift').selectpicker('refresh');
                    $(".location_id").val(fetch.location_id).trigger('change');
                $('.location_id').selectpicker('refresh');
                $('#checked_html').html(fetch.checked_html);
                $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
            }
        },
        error: function(html) {
            $('#global-loader').hide();
            after_submit();
            show_notification('error', '<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');
            return false;
        }
    });
}

function del(id) {
    Swal.fire({
        title: '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
        text: '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: '<?php echo trans('messages.delete_it_lang',[],session('locale')); ?>',
        confirmButtonClass: "btn btn-primary",
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {
            $('#global-loader').show();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ url('delete_worker') }}",
                type: 'POST',
                data: { id: id, _token: csrfToken },
                error: function() {
                    $('#global-loader').hide();
                    after_submit();
                    show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                },
                success: function(data) {
                    $('#global-loader').hide();
                    after_submit();
                    $('#all_workers').DataTable().ajax.reload();
                    show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
        }
    });
}





</script>
