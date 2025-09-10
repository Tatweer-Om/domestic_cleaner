<script>


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


$('.datepicker').bootstrapMaterialDatePicker({
    weekStart: 0,
    time: false,
    format: 'YYYY/M/D',
    currentDate: moment()  // sets today's date
});


// three digit after decimal
function three_digit_after_decimal(number) {
    if (!isNaN(number)) {
        return Math.floor(number * 1000) / 1000;
    }
}
// two digit
function two_digit_after_decimal(number) {
    if (!isNaN(number)) {
        return Math.floor(number * 100) / 100;
    }
}
// only number allow
function isNumber(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if ((charCode != 45 || $(element).val().indexOf('-') != -1) && (charCode != 46 || $(element).val().indexOf(
            '.') != -1) && ((charCode < 48 && charCode != 8) || charCode > 57)) {
        return false;
    } else {
        return true;
    }
}

function isNumber_qty(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode;

    // Allow only digits
    if (charCode < 48 || charCode > 57) {
        return false;
    } else {
        return true;
    }
}

function convertToEnglishDigits(inputField) {
    // Replace Arabic digits with English digits
    inputField.value = inputField.value.replace(/[٠١٢٣٤٥٦٧٨٩]/g, function(match) {
        return String.fromCharCode(match.charCodeAt(0) - '٠'.charCodeAt(0) + '0'.charCodeAt(0));
    });

    // Remove any non-digit characters
    inputField.value = inputField.value.replace(/\D/g, '');
}

function isNumber1(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode;

    // Allow digits (0-9), backspace (8), and minus sign (45)
    if ((charCode >= 48 && charCode <= 57) || charCode == 8 || charCode == 45) {
        // Check if the minus sign is not the first character
        if (charCode == 45 && $(element).val().indexOf('-') !== -1) {
            return false;
        }
        return true;
    } else {
        return false;
    }
}

//Number with decimal only
$(document).on('keypress', '.isnumber', function(e) {
    return isNumber(e, this);
});
// only english digit
$(document).on('input', '.isnumber_qty', function() {
    convertToEnglishDigits(this);
});
//Number without decimal only
$(document).on('keypress', '.isnumber1', function(e) {
    return isNumber1(e, this);
});

function get_date_only(dateString) {
    // Convert the date string to a Date object
    const date = new Date(dateString);

    // Format the date as needed, for example: "YYYY-MM-DD"
    return date.toISOString().split('T')[0]; // Adjust the format as needed
}


function show_notification(type, msg) {
        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: 'toast-top-right', // Set position to top-right
            preventDuplicates: false,
            onclick: null,
            showDuration: '300',
            hideDuration: '1000',
            timeOut: '5000',
            extendedTimeOut: '1000',
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };
        if (type == "success") {
            toastr.success(msg, type);
        } else if (type == "error") {
            toastr.error(msg, type);
        } else if (type == "warning") {
            toastr.warning(msg, type);
        }
    }

    function before_submit() {
        $('.submit_form').attr('disabled', true);
        $('.submit_form').html(
            'Please wait <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>');
    }

    function after_submit() {
        $('.submit_form').attr('disabled', false);
        $('.submit_form').html('Submit');
    }

    function showPreloader() {
    if ($('#preloader').length === 0) {
        $('body').append(`
            <div id="preloader">
                <div class="sk-three-bounce">
                    <div class="sk-child sk-bounce1"></div>
                    <div class="sk-child sk-bounce2"></div>
                    <div class="sk-child sk-bounce3"></div>
                </div>
            </div>
        `);
    }
    $('#preloader').show();
}

function hidePreloader() {
    $('#preloader').remove(); // Completely removes it after hiding
}





</script>
