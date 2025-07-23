<?php 
	$user = user_detail(session('user_id'));
	$this->extend('user/layout/header'); 
	$breadcrumb_title = $language['lg_dashboard'] ?? '';
?>
<?php $this->section('title');?>
<?=$breadcrumb_title;?>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="breadcrumb-bar">
	<div class="container mt-2 mt-md-5 pt-5">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="/" class="text-decoration-none">
								<?=$language['lg_home'] ?? ' '; ?>
							</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							<?=$breadcrumb_title;?>
						</li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?=$breadcrumb_title;?></h2>
			</div>
		</div>
	</div>
</div>

<div class="content">
	<div class="container">
		<div class="row">
			<?=$user ? view('user/layout/sidebar') : '';?>
			<div class="col-12 <?=$user ? 'col-md-9' : 'col-md-12';?>">
				<?php
				if ($user && $user['is_updated'] == '0') {
					$warn = $language['lg_this_is_a_warni'] ?? "";
					$click = $language['lg_click_here1'] ?? " ";
					$give = $language['lg_give_it_a_click'] ?? " ";
					echo '<div class="alert alert-warning" role="alert">
					<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' . $warn . ' <a href="/' . session('module') . '/profile" class="alert-link">' . $click . '</a>. ' . $give . '
					</div>';
				}
				if ($user && $user['is_verified'] == '0') {
					$warn = $language['lg_this_is_a_warni1'] ?? "";
					$click = $language['lg_click_here1'] ?? " ";
					$give = $language['lg_give_it_a_click'] ?? " ";
					echo '<div class="alert alert-warning" role="alert">
				    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
					  ' . $warn . ' <a onclick="email_verification()" href="javascript:void(0);" class="alert-link">' . $click . '</a>. ' . $give . '
					</div>';
				}
				?>

				<div class="profile-sidebar">
					<div class="card-body pt-0">
						<nav class="mb-4">
							<ul class="nav nav-tabs w-100 border-0 nav-fill">
								<li class="nav-item">
									<a class="nav-link active" onclick="appoinments_table()" href="#pat_appointments" data-toggle="tab">
										<?=$language['lg_appointments_Schedule'] ?? " "; ?>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="previous_appoinments_table()" href="#pres" data-toggle="tab">
										<span>
											<?=$language['lg_appointments_Previous'] ?? " "; ?>
										</span>
									</a>
								</li>
								<!--<li class="nav-item">
									<a class="nav-link" onclick="medical_records_table()" href="#medical" data-toggle="tab">
										<span class="med-records"><?php // $language['lg_medical_records'] ?? " "; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="billings_table()" href="#billing" data-toggle="tab">
										<span><?php // $language['lg_billing'] ?? " "; ?></span>
									</a>
								</li>-->
							</ul>
						</nav>

						<div class="tab-content pt-0">
							<input type="hidden" id="patient_id" value="<?=session('user_id'); ?>" />
							<div id="pat_appointments" class="tab-pane fade show active">
								<div class="card border-0 card-table">
									<div class="card-body appoinment-div-content">
										<div class="table-responsive"> <!-- d-none d-md-block -->
											<table id="appoinment_table" class="table table-hover table-center mb-0">
												<thead>
													<tr>
														<th><?=$language['lg_sno'] ?? " "; ?></th>
														<th><?=$language['lg_doctor2'] ?? " " . '/' . ($language['lg_clinic'] ?? " ") . ' ' . ($language['lg_name'] ?? " "); ?></th>
														<th><?=$language['lg_appt_date'] ?? " "; ?></th>
                                                        <th>Pet</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>

									<div class="no-appointment" style="display:none;">
										<div class="vstack gap-3 bg-white rounded-4 py-4">
											<h2 style="color:#252525; text-align: center;font-weight:400;font-size:16px;">
												No data available in table
											</h2>
											<a class="btn btn-lg btn-warning px-5 apt-btn-book" href="/search-veterinary?type=1">
												Book Appointment
											</a>
										</div>
									</div>
								</div>
							</div>

							<div class="tab-pane fade" id="pres">
								<div class="card card-table mb-0">
									<div class="card-body previous-appointment-content">
										<div class="table-responsive">
											<table id="previous_appointment_table" style="width:100%" class="table table-hover table-center mb-0">
												<thead>
													<tr>
														<th><?=$language['lg_sno'] ?? ' '; ?></th>
														<th>
															<?=$language['lg_doctor2'] ?? " " . '/' . 
																($language['lg_clinic'] ?? " ") . ' ' . 
																($language['lg_name'] ?? " ")
															;?>
														</th>
														<th><?=$language['lg_appt_date'] ?? " "; ?></th>
	                                                    <th>Advice</th>
                                                        <th>Pet</th>
													</tr>
												</thead>
												<tbody>

												</tbody>
											</table>
										</div>
									</div>
									<div class="card-body no-previous-appointment" style="display:none;padding: 3%;background-color: #F7F7F7;">
										<h2 style="color:#252525; text-align: center;padding: 3%;font-family:Poppins;font-weight:400;font-size:16px;background-color:#FFFFFF;border-radius:12px;">
											No data available in table
										</h2>
									</div>
								</div>
							</div>

							<div class="tab-pane fade" id="medical">
								<?php if (is_patient()) { ?>
									<?php // $language['lg_add_medical_rec'] ?? " "; ?>
								<?php } ?>
								<div class="card card-table mb-0">
									<div class="card-body">
										<div class="table-responsive">
											<table id="medical_records_table" class="table table-hover table-center mb-0 w-100">
												<thead>
													<tr>
														<th><?=$language['lg_sno'] ?? ''; ?></th>
														<th><?=$language['lg_date1'] ?? ''; ?> </th>
														<th><?=$language['lg_description'] ?? ''; ?></th>
														<th><?=$language['lg_attachment'] ?? ''; ?></th>
														<th><?=$language['lg_doctor2'] ?? ''; ?></th>
														<th data-orderable="false">
															<?=$language['lg_view1'] ?? ''; ?>
														</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="tab-pane" id="billing">
								<div class="card card-table mb-0">
									<div class="card-body">
										<div class="table-responsive">
											<table id="billing_table" class="table table-hover table-center mb-0 w-100">
												<thead>
													<tr>
														<th><?=$language['lg_sno'] ?? ''; ?></th>
														<th><?=$language['lg_date1'] ?? ''; ?></th>
														<th><?=$language['lg_description'] ?? ''; ?></th>
														<th><?=$language['lg_doctor2'] ?? ''; ?></th>
														<th data-orderable="false">
															<?=$language['lg_view1'] ?? ''; ?>
														</th>
													</tr>
												</thead>
												<tbody></tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php $this->endSection(); ?>

<?php $this->section('javascript'); ?>
<script src="/assets/js/jquery.min.js"></script>
<script>
	function toggleDropdown(button) {
		const dropdown = button.nextElementSibling;
		dropdown.classList.toggle("show");
	}
	$(document).ready(function(){
		
		function getScheduleV2(appointment, doctor_id, date)
		{
			var schedule_date = date;

			$.post(base_url + 'get-schedule-from-date', { schedule_date: schedule_date, doctor_id: doctor_id }, function (response) {
				console.log(response);
				console.log(doctor_id);
				$('.slots-doctor-day-'+appointment).html(response);
				$('[data-toggle="tooltip"]').tooltip();
			});
		}

		$(document).on('click', '#appointment-edit-id', function(){
			var doctorId = $(this).data('doctor-id');
			var appointmentId = $(this).data('appointment-id');
			var dateStart = $(this).data('date');
			var currentTime = $(this).data('time');

			// Show the modal
			$('#editAppointmentModal-'+appointmentId).appendTo("body").modal("show");
			var dateDiv = $('#editAppointmentModal-'+appointmentId+' #schedule_date');
				// Set doctor ID in the input
			dateDiv.val(dateStart);
			// Pass the clicked element to getScheduleV2
			getScheduleV2(appointmentId, doctorId, dateStart);
		});

		$(document).on('change', '#schedule_date', async function() {
			var doctorId = $(this).data('doctor-id');
			var appointmentId = $(this).data('appointment-id');
			var date = $(this).val();
			getScheduleV2(appointmentId, doctorId, date);
		});

		// When a slot is clicked, enable the button
		document.addEventListener('click', function (event) {
			if (event.target && event.target.classList.contains('slot')) {
				// Remove active class from other slots and add to the selected slot
				document.querySelectorAll('.slot').forEach(slot => slot.classList.remove('active'));
				event.target.classList.add('active');

				const modal = event.target.closest('.modal');
				if (!modal) return;

				// Extract appointment and doctor IDs from the input in the modal
				const scheduleInput = modal.querySelector('input[name="schedule_date"]');
				const appointmentId = scheduleInput?.dataset.appointmentId;
				console.log("appointmentId", appointmentId);
				// // Store appointment details in sessionStorage
				const newAppointment = {
					appointment_token: event.target.dataset.token,
					appointment_date: event.target.dataset.date,
					appointment_timezone: event.target.dataset.timezone,
					appointment_start_time: event.target.dataset.startTime,
					appointment_end_time: event.target.dataset.endTime,
					appointment_session: event.target.dataset.session,
					appointment_type: event.target.dataset.scheduleType,
				};
				const sessionKey = 'slots-session-'+appointmentId;
				sessionStorage.setItem(sessionKey, JSON.stringify(newAppointment));
				console.log(sessionStorage.getItem(sessionKey));
			}
		});
		
		$(document).on('click', '#edit-appointment-btn', function(){
			const appointmentId = $(this).data('appointment-id');
			const sessionKey = 'slots-session-'+appointmentId;
			const storedData = sessionStorage.getItem(sessionKey);
			if (storedData) {
				const appointmentData = JSON.parse(storedData);
				$.post(base_url + 'patient/appointment-edit', { 
					id: appointmentId,
					appointment_date:appointmentData['appointment_date'],
					appointment_start_time:appointmentData['appointment_start_time'],
					appointment_end_time:appointmentData['appointment_end_time'],
					appointment_token:appointmentData['appointment_token'],
					appointment_session:appointmentData['appointment_session'],
					appointment_type:appointmentData['appointment_type'],
				}, function (response) {
					console.log(response);
					sessionStorage.removeItem(sessionKey);
					var response = JSON.parse(response);
					if(response.status == 200){
						window.location.reload(true);
					}else{
						$('body').append(`
						<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content custom-success-modal text-center">
							<div class="modal-body">
								<span class="close-btn" data-dismiss="modal">&times;</span>
								<div class="success-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#FFA500" viewBox="0 0 24 24">
									<path d="M12 0C5.371 0 0 5.371 0 12c0 6.628 5.371 12 12 12s12-5.372 12-12c0-6.629-5.371-12-12-12zm-1.2 17.143l-4.2-4.2 1.714-1.714 2.486 2.486 5.486-5.486L18 9.429l-7.2 7.714z"/>
								</svg>
								</div>
								<p class="modal-text">Your appointment could not be <br><strong>updated.</strong></p>
							</div>
							</div>
						</div>
						</div>
						`);
						$('body #successModal').modal("show");
					}
			});
			} else {
				console.warn('No appointment data found in sessionStorage for:', sessionKey);
				return ;
			}
		});
	});
</script>
<?php $this->endSection(); ?>