   <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="http://Tatweersoft.om/" target="_blank">TatweerSoft</a> 2025</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/chart.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/owl-carousel/owl.carousel.js') }}"></script>

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


    <script src="{{ asset('js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('js/deznav-init.js') }}"></script>
    <script src="{{ asset('js/demo.js') }}"></script>
    <script src="{{ asset('js/styleSwitcher.js') }}"></script>
    <script src="{{ asset('vendor/chartist/js/chartist.min.js') }}"></script>
<script src="{{ asset('vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js') }}"></script>




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

</body>

</html>
