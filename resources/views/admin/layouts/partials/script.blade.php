		<!-- jQuery -->
		<script src="admin_asset/plugins/jquery/jquery.min.js"></script>
		<!-- jQuery UI 1.11.4 -->
		<script src="admin_asset/plugins/jquery-ui/jquery-ui.min.js"></script>
		<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
		<script>
		    $.widget.bridge('uibutton', $.ui.button)
		</script>
		<!-- Bootstrap 4 -->
		<script src="admin_asset/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- ChartJS -->
		<script src="admin_asset/plugins/chart.js/Chart.min.js"></script>
		<!-- Sparkline -->
		<script src="admin_asset/plugins/sparklines/sparkline.js"></script>
		<!-- JQVMap -->
		<script src="admin_asset/plugins/jqvmap/jquery.vmap.min.js"></script>
		<script src="admin_asset/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
		<!-- jQuery Knob Chart -->
		<script src="admin_asset/plugins/jquery-knob/jquery.knob.min.js"></script>
		<!-- daterangepicker -->
		<script src="admin_asset/plugins/moment/moment.min.js"></script>
		<script src="admin_asset/plugins/daterangepicker/daterangepicker.js"></script>
		<!-- Tempusdominus Bootstrap 4 -->
		<script src="admin_asset/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
		<!-- Summernote -->
		<script src="admin_asset/plugins/summernote/summernote-bs4.min.js"></script>
		<!-- overlayScrollbars -->
		<script src="admin_asset/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
		<!-- AdminLTE App -->
		<script src="admin_asset/dist/js/adminlte.js"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="admin_asset/dist/js/demo.js"></script>
		<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		<script src="admin_asset/dist/js/pages/dashboard.js"></script>

		<!-- Function -->
		<script src="js/function.js"></script>

		<!-- Sweet Alert -->
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
		<!-- Optional: include a polyfill for ES6 Promises for IE11 -->
		<script src="//cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>

		@stack('scripts')

		<script type="text/javascript">

            /* 
            *	==============================================
            *			===	Method delete function ===
            *
            *				1) ajaxDeleteFunc()
            *				2) deleteSingleRecord()
            *				3) deleteMultiRecord()
            *	===============================================
            */

            // Datatables ajax reload form
		    function reloadDatatables(idTable){
		    	$(idTable).DataTable().ajax.reload()
		    }

		    // Checkbox all
            $('#checkAll').click(function() {
                if ($(this).is(':checked')) {
                    $('.checkItem').prop('checked', true);
                }else{
                    $('.checkItem').prop('checked', false);
                }
            });

            // Ajax delete
            function ajaxDeleteFunc(idTable, url, method, data = null) {
            	$.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.messages, 'success');  
                            reloadDatatables(idTable);
                        }else{
                        	Swal.fire({
							  	icon: 'error',
							  	title: 'Oops...',
							  	text: response.messages,
							})
                        }
                    }
                });  
            }

			// Delete single record
			function deleteSingleRecord(url, idTable) {
				Swal.fire({
		            title: 'Are you sure?',
		            text: "You won't be able to revert this!",
		            icon: 'warning',
		            showCancelButton: true,
		            confirmButtonColor: '#3085d6',
		            cancelButtonColor: '#d33',
		            confirmButtonText: 'Yes, delete it!'
		        }).then((result) => {
		            if (result.isConfirmed) {
		            	ajaxDeleteFunc(idTable, url, 'DELETE')
		            }
		        })
			}

			// Delete multi record
			function deleteMultiRecord(url, idTable) {
				const checked = [];
                $('input[name="checked[]"]').each(function() {
                    if ($(this).is(':checked')) {
                        checked.push($(this).val());
                    }
                });

                Swal.fire({
		            title: 'Are you sure?',
		            text: "You won't be able to revert this!",
		            icon: 'warning',
		            showCancelButton: true,
		            confirmButtonColor: '#3085d6',
		            cancelButtonColor: '#d33',
		            confirmButtonText: 'Yes, delete it!'
		        }).then((result) => {
		            if (result.isConfirmed) {
		            	ajaxDeleteFunc(idTable, url, 'POST', { checked: checked }); 
	                }else{
	                	$('input[type="checkbox"]').prop('checked', false);
	                }
		        })  
			}

		</script>
	</body>
</html>