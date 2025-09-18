   <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="http://Tatweersoft.om/" target="_blank">TatweerSoft</a> 2025</p>
            </div>
        </div>



    </div>

    <!-- Required vendors -->
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/chart.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/owl-carousel/owl.carousel.js') }}"></script>
        <!-- <script src="{{ asset('vendor/datatables/js/jquery-ui.min.js') }}"></script> -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Bootstrap 5 JS -->

    <!-- Apex Chart -->
    <script src="{{ asset('vendor/apexchart/apexchart.js') }}"></script>

    <!-- Dashboard 1 -->
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <!-- clockpicker -->

    <script src="{{ asset('vendor/clockpicker/js/bootstrap-clockpicker.min.js') }}"></script>

    <!-- Clockpicker init -->
    <script src="{{ asset('js/plugins-init/clock-picker-init.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datetimepicker/js/moment.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- Material color picker -->
    <script src="{{ asset('vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <!-- Material color picker init -->
    <script src="{{ asset('js/plugins-init/material-date-picker-init.js') }}"></script>
    <script src="{{ asset('js/dashboard/dashboard-1.js') }}"></script>

    <script src="{{ asset('vendor/fullcalendar/js/main.min.js') }}"></script>

    <script src="{{ asset('js/plugins-init/fullcalendar-init.js') }}"></script>

    <script src="{{ asset('js/deznav-init.js') }}"></script>

    <script src="{{ asset('js/demo.js') }}"></script>
    <script src="{{ asset('js/styleSwitcher.js') }}"></script>
    <script src="{{ asset('vendor/chartist/js/chartist.min.js') }}"></script>
    <script src="{{ asset('vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('vendor/toastr/js/toastr.min.js')}}"></script>
    <script src="{{ asset('js/plugins-init/toastr-init.js')}}"></script>
    <script src="{{  asset('js/custom.min.js')}}"></script>

    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script src="{{  asset('js/plugins-init/sweetalert.init.js')}}"></script>

<!-- DataTables core -->

<!-- DataTables Buttons extension -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<!-- All init script -->

	<script>
		function assignedDoctor(){

			/*  testimonial one function by = owl.carousel.js */
			jQuery('.assigned-doctor').owlCarousel({
				loop:false,
				margin:30,
				nav:true,
				autoplaySpeed: 3000,
				navSpeed: 3000,
				paginationSpeed: 3000,
				slideSpeed: 3000,
				smartSpeed: 3000,
				autoplay: false,
				rtl: true,
				dots: false,
				navText: ['<i class="fa fa-caret-left"></i>', '<i class="fa fa-caret-right"></i>'],
				responsive:{
					0:{
						items:1
					},
					576:{
						items:2
					},
					767:{
						items:3
					},
					991:{
						items:2
					},
					1200:{
						items:3
					},
					1600:{
						items:4
					},
					1920:{
						items:5
					}
				}
			})
		}

		jQuery(window).on('load',function(){
			setTimeout(function(){
				assignedDoctor();
			}, 1000);
		});

		function pieChart(){
			var data = {
				labels: ['35%', '55%', '10%'],
				series: [30, 25, 15]
			};
			var options = {
				labelInterpolationFnc: function(value) {
				  return value[0]
				}
			};
			var responsiveOptions = [
				['screen and (min-width: 230px)', {
					chartPadding: 10,
					donut: true,
					labelOffset: 40,
					donutWidth: 50,
					labelDirection: 'explode',
					labelInterpolationFnc: function(value) {
						return value;
					}
				}],
				['screen and (min-width: 230px)', {
					labelOffset: 60,
					chartPadding: 20
				}]
			];
			new Chartist.Pie('#pie-chart', data, options, responsiveOptions);
		}
		jQuery(window).on('load',function(){
			setTimeout(function(){
				pieChart();
			}, 1000);
		});

        (function($){
			var table = $('#example5').DataTable({
				searching: true,
				paging:true,
				select: true,
				lengthChange:false
			});
			$('#example tbody').on('click', 'tr', function () {
				var data = table.row( this ).data();
			});
		})(jQuery);




	</script>

@include('custom_js.custom_js')
@php

    $routeName = Route::currentRouteName();
    $segments = explode('.', $routeName);
    $route_name = isset($segments[0]) ? $segments[0] : null;

@endphp

    @if ($route_name == 'location')
         @include('custom_js.location_js')
         @elseif ($route_name == 'driver')
         @include('custom_js.driver_js')
         @elseif ($route_name == 'worker')
         @include('custom_js.worker_js')
         @elseif ($route_name == 'user')
         @include('custom_js.user_js')
         @elseif ($route_name == 'expense_category')
         @include('custom_js.expensecat_js')
         @elseif ($route_name == 'expense')
         @include('custom_js.exepnse_js')
         @elseif ($route_name == 'voucher')
         @include('custom_js.voucher_js')
         @elseif ($route_name == 'package')
         @include('custom_js.package_js')
         @elseif ($route_name == 'service')
         @include('custom_js.service_js')
             @elseif ($route_name == 'all_bookings')
         @include('custom_js.booking_js')
         @elseif ($route_name == 'all_visits')
         @include('custom_js.booking_js')
               @elseif ($route_name == 'sms')
         @include('custom_js.add_sms_js')
		        @elseif ($route_name == 'worker_page')
         @include('custom_js.worker_page_js')
         	        @elseif ($route_name == 'driver_page')
         @include('custom_js.driver_page_js')

    @endif

</body>

</html>
