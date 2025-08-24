<script>
$(function () {
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  // Reset modal on close
  $('#add_service_modal').on('hidden.bs.modal', function () {
    const $form = $(".add_service")[0];
    if ($form) $form.reset();
    $('.service_id').val('');
    $('#serviceModalLabel').html(
      '<i class="fas fa-spray-can-sparkles me-1"></i> {{ trans('messages.service', [], session('locale')) }}'
    );
    $('#imagePreview').attr('src', "{{ asset('images/dummy_images/cover-image-icon.png') }}");
    $('#removeImage').hide();
  });

  // DataTable
  const servicesTable = $('#all_services').DataTable({
    ajax: {
      url: "{{ url('show_service') }}",
      type: 'GET'
    },
    bFilter: true,
    pagingType: 'numbers',
    ordering: true,
    // NOTE: 0-based column index. Change 5 -> whichever column you actually want to sort by.
    order: [[5, "desc"]],
    // Prevent reinit errors if you re-call
    destroy: true
  });

  // Create/Update submit
  $('.add_service').off('submit').on('submit', function (e) {
    e.preventDefault();

    const name = $('.service_name').val()?.trim();
    const fee  = $('.service_fee').val();

    if (!name) {
      show_notification('error', "{{ trans('messages.add_service_name_lang', [], session('locale')) }}");
      return;
    }
    if (fee === '' || isNaN(fee) || Number(fee) < 0) {
      show_notification('error', "{{ trans('messages.service_fee_placeholder', [], session('locale')) }}");
      return;
    }

    const id = $('.service_id').val();
    const formdatas = new FormData(this);

    showPreloader();
    before_submit();

    $.ajax({
      type: "POST",
      url: id ? "{{ url('update_service') }}" : "{{ url('add_service') }}",
      data: formdatas,
      contentType: false,
      processData: false
    }).done(function () {
      hidePreloader();
      after_submit();
      show_notification('success', id
        ? "{{ trans('messages.data_update_success_lang', [], session('locale')) }}"
        : "{{ trans('messages.data_add_success_lang', [], session('locale')) }}"
      );
      $('#add_service_modal').modal('hide');
      servicesTable.ajax.reload(null, false);
      if (!id) $(".add_service")[0].reset();
    }).fail(function (xhr) {
      hidePreloader();
      after_submit();
      const msg = (xhr?.responseJSON?.message) ? xhr.responseJSON.message : (id
        ? "{{ trans('messages.data_update_failed_lang', [], session('locale')) }}"
        : "{{ trans('messages.data_add_failed_lang', [], session('locale')) }}"
      );
      show_notification('error', msg);
      servicesTable.ajax.reload(null, false);
    });
  });
});

// Edit item
function edit(id) {
  $('#global-loader').show();
  before_submit();

  $.ajax({
    dataType: 'JSON',
    url: "{{ url('edit_service') }}",
    method: "POST",
    data: { id: id }
  }).done(function (fetch) {
    $('#global-loader').hide();
    after_submit();

    if (!fetch) return;

    // Fill fields
    $(".service_name").val(fetch.service_name || '');
    $(".service_fee").val(fetch.service_fee || '');
    $(".notes").val(fetch.notes || '');
    $(".service_id").val(fetch.service_id || '');

    // Image preview
    const img = fetch.service_image || "{{ asset('images/dummy_images/cover-image-icon.png') }}";
    $("#imagePreview").attr("src", img);
    if (fetch.service_image) {
      $('#removeImage').show();
    } else {
      $('#removeImage').hide();
    }

    // Optional dynamic HTML if you still use it
    if (fetch.checked_html) {
      $('#checked_html').html(fetch.checked_html);
    }

    // Update modal title for edit
    $("#serviceModalLabel").html(
      '<i class="fas fa-pen-to-square me-1"></i> {{ trans('messages.update_lang', [], session('locale')) }}'
    );

    // Show modal
    $('#add_service_modal').modal('show');

  }).fail(function () {
    $('#global-loader').hide();
    after_submit();
    show_notification('error', "{{ trans('messages.edit_failed_lang', [], session('locale')) }}");
  });
}

// Delete item
function del(id) {
  Swal.fire({
    title: "{{ trans('messages.sure_lang', [], session('locale')) }}",
    text: "{{ trans('messages.delete_lang', [], session('locale')) }}",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "{{ trans('messages.delete_it_lang', [], session('locale')) }}",
    customClass: {
      confirmButton: "btn btn-primary",
      cancelButton: "btn btn-danger ms-2"
    },
    buttonsStyling: false
  }).then(function(result) {
    if (result.isConfirmed) {
      $('#global-loader').show();
      before_submit();

      $.ajax({
        url: "{{ url('delete_service') }}",
        type: 'POST',
        data: { id: id }
      }).done(function () {
        $('#global-loader').hide();
        after_submit();
        $('#all_services').DataTable().ajax.reload(null, false);
        show_notification('success', "{{ trans('messages.delete_success_lang', [], session('locale')) }}");
      }).fail(function () {
        $('#global-loader').hide();
        after_submit();
        show_notification('error', "{{ trans('messages.delete_failed_lang', [], session('locale')) }}");
      });
    } else {
      show_notification('success', "{{ trans('messages.safe_lang', [], session('locale')) }}");
    }
  });
}

// Image preview handlers
document.addEventListener("DOMContentLoaded", function () {
  const imagePreview = document.getElementById("imagePreview");
  const imageUpload  = document.getElementById("imageUpload");
  const removeImage  = document.getElementById("removeImage");

  if (!imagePreview || !imageUpload || !removeImage) return;

  imagePreview.addEventListener("click", function () {
    imageUpload.click();
  });

  imageUpload.addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        imagePreview.src = e.target.result;
        removeImage.style.display = "block";
      };
      reader.readAsDataURL(file);
    }
  });

  removeImage.addEventListener("click", function () {
    imagePreview.src = "{{ asset('images/dummy_images/cover-image-icon.png') }}";
    imageUpload.value = "";
    removeImage.style.display = "none";
  });
});
</script>
