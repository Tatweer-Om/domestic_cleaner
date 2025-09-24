<script type="text/javascript">

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $('.sms_status').on('change', function () {
        const smsStatus = $(this).val();
        $.ajax({
            url:  "{{ url('get_sms_status') }}",
            method: "POST",
            data: { sms_status: smsStatus,_token: csrfToken },
            success: function (data) {
                if (data.status === 1) {

                    $(".sms_area").val(data.sms);
                } else {
                    $(".sms_area").val('');
                }
            },
            error: function (data) {
                $('#global-loader').hide();
                show_notification('error',  '<?php echo trans('messages.get_data_failed_lang',[],session('locale')); ?>');
                console.log(data);
            }
        });
    });

  $(".worker_name").click(function () {
    $(".sms_area").val((index, value) => value + '{worker_name}');
});

$(".booking_no").click(function () {
    $(".sms_area").val((index, value) => value + '{booking_no}');
});

$(".booking_date").click(function () {
    $(".sms_area").val((index, value) => value + '{booking_date}');
});

$(".booking_time").click(function () {
    $(".sms_area").val((index, value) => value + '{booking_time}');
});

$(".visit_date").click(function () {
    $(".sms_area").val((index, value) => value + '{visit_date}');
});

$(".visit_time").click(function () {
    $(".sms_area").val((index, value) => value + '{visit_time}');
});

$(".package").click(function () {
    $(".sms_area").val((index, value) => value + '{package}');
});

$(".location").click(function () {
    $(".sms_area").val((index, value) => value + '{location}');
});

$(".total_visits").click(function () {
    $(".sms_area").val((index, value) => value + '{total_visits}');
});

$(".remianing_visits").click(function () {
    $(".sms_area").val((index, value) => value + '{remianing_visits}');
});

$(".next_visit_date").click(function () {
    $(".sms_area").val((index, value) => value + '{next_visit_date}');
});

$(".extention_time").click(function () {
    $(".sms_area").val((index, value) => value + '{extention_time}');
});

$(".extention_date").click(function () {
    $(".sms_area").val((index, value) => value + '{extention_date}');
});

$(".cancel_date").click(function () {
    $(".sms_area").val((index, value) => value + '{cancel_date}');
});

$(".driver_no").click(function () {
    $(".sms_area").val((index, value) => value + '{driver_no}');
});

$(".driver_name").click(function () {
    $(".sms_area").val((index, value) => value + '{driver_name}');
});

$(".customer_name").click(function () {
    $(".sms_area").val((index, value) => value + '{customer_name}');
});

$(".shift").click(function () {
    $(".sms_area").val((index, value) => value + '{shift}');
});

$(".duration").click(function () {
    $(".sms_area").val((index, value) => value + '{duration}');
});
$(".customer_number").click(function () {
    $(".sms_area").val((index, value) => value + '{customer_number}');
});





    setTimeout(function () {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = 0;
            setTimeout(() => alertBox.remove(), 500); // remove after fade
        }
    }, 5000); // 5 seconds
</script>
