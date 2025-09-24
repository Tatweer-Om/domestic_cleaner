<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    (function () {
  function toggleEntry(entry, checked) {
    if (!entry) {
      console.error('Voucher entry element not found');
      return;
    }
    if (checked) {
      entry.classList.remove('d-none');
      entry.style.display = 'block';
      const firstInput = entry.querySelector('input, textarea');
      if (firstInput) firstInput.focus();
    } else {
      entry.classList.add('d-none');
      entry.style.display = 'none';
      const firstInput = entry.querySelector('input, textarea');
      if (firstInput) firstInput.value = '';
    }
  }

  function initScope(scope) {
    const cb = scope.querySelector('#use-voucher, .js-use-voucher');
    const entry = scope.querySelector('#voucher-entry, .js-voucher-entry');
    console.log('initScope', cb, entry);
    if (!cb || !entry) return;

    toggleEntry(entry, cb.checked);

    cb.addEventListener('change', function () {
      console.log('Checkbox changed', cb.checked);
      toggleEntry(entry, cb.checked);
    });
  }

  function initAll() {
    document.querySelectorAll('.payment-card, .payment-container, body').forEach(initScope);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }

  document.addEventListener('change', function (e) {
    if (!e.target.matches('#use-voucher, .js-use-voucher')) return;
    const scope = e.target.closest('.payment-card, .payment-container') || document;
    const entry = scope.querySelector('#voucher-entry, .js-voucher-entry');
    toggleEntry(entry, e.target.checked);
  });

  // Fallback for show_notification
  window.show_notification = window.show_notification || function (type, message) {
    alert(type.toUpperCase() + ': ' + message);
  };

  $(document).on('click', '#apply-voucher-btn', function (e) {
    e.preventDefault();
    console.log('Apply voucher button clicked');

    var btn = $(this);
    var formdatas = new FormData($('#voucher_form')[0]);
    formdatas.append('_token', "{{ csrf_token() }}");
    console.log('CSRF token:', "{{ csrf_token() }}");

    var voucher_code = $('#voucher-code').val();
    console.log('Voucher code:', voucher_code);
    if (voucher_code === "") {
      show_notification('error', "{{ trans('messages.provide_voucher_code_lang', [], session('locale')) }}");
      return false;
    }

    formdatas.append('voucher_code', voucher_code);

    $.ajax({
      type: "POST",
      url: "{{ url('voucher_apply') }}",
      data: formdatas,
      contentType: false,
      processData: false,
      success: function (data) {
        console.log('AJAX success:', data);
        if (data.status === 2) {
          show_notification('error', 'Voucher Code is wrong.');
          return;
        }

        show_notification('success', "{{ trans('messages.data_voucher_success_lang', [], session('locale')) }}");
        $('#voucher-code').attr('disabled', true);
        btn.attr('disabled', true);

        $('.total_voucher').val(data.voucher_amount);
        $('#voucher-discount-amount').text(data.voucher_amount);

        var total_amount = parseFloat($('.total_amount').val()) || 0;
        var final_amount = total_amount - parseFloat(data.voucher_amount);

        $('.total_amount').val(final_amount);
        $('#total-amount').text(final_amount);
        $('#voucher-discount-row').removeClass('d-none');
      },
      error: function (data) {
        console.error('AJAX error:', data);
        show_notification('error', "{{ trans('messages.data_update_failed_lang', [], session('locale')) }}");
      }
    });
  });
})();
</script>