<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {


       $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(".login_form").on("submit", function (e) {
        e.preventDefault(); // Prevent page reload

        let $form = $(this);
        let $button = $form.find('button[type="submit"]');

        // Disable button & show loading text
        $button.prop("disabled", true).text("Logging in...");

        $.ajax({
            url: "{{ url('login') }}",
            type: "POST",
            data: $form.serialize(),
            dataType: "json",
            timeout: 10000, // 10 seconds timeout
            success: function (response) {
                // Handle login success
                if (response.status == 1) {
                    show_notification('success', response.message);
                    window.location.href = response.redirect;

                } else if (response.status == 4) {
                    console.log('Driver login successful, ID:', response.id);
                    show_notification('success', response.message);
                    if (response.id) {
                        window.location.href = "{{ url('driver_page') }}/" + encodeURIComponent(response.id);
                    } else {
                        show_notification('error', 'Driver ID is missing');
                    }

                } else if (response.status == 5) {
                    console.log('Worker login successful, ID:', response.id);
                    show_notification('success', response.message);
                    if (response.id) {
                        window.location.href = "{{ url('worker_page') }}/" + encodeURIComponent(response.id);
                    } else {
                        show_notification('error', 'Worker ID is missing');
                    }

                } else {
                    show_notification('error', response.message);
                }
            },
        error: function (xhr, textStatus) {
    let errorMessage = "<?php echo trans('messages.error_unexpected', [], session('locale')); ?>";

    // Check for timeout or network issues
    if (textStatus === "timeout") {
        errorMessage = "<?php echo trans('messages.error_timeout', [], session('locale')); ?>";
    } else if (xhr.status === 0) {
        errorMessage = "<?php echo trans('messages.error_no_internet', [], session('locale')); ?>";
    } else if (xhr.responseJSON) {
        // Laravel validation errors
        if (xhr.responseJSON.errors) {
            errorMessage = Object.values(xhr.responseJSON.errors).flat().join("\n");
        }
        // Laravel custom message
        else if (xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        }
    }

    show_notification('error', errorMessage);
    alert(errorMessage);
},

            complete: function () {
                // Re-enable the button
                $button.prop("disabled", false).text("Login");
            }
        });
    });
});



</script>
