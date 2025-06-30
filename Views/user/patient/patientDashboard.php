<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<style>

.appointment-card {
  width: 100%;
  max-width: 300px;
  background-color: #F7F7F7;
  border: 1px solid #eee;
  border-radius: 12px;
  padding: 16px;
  margin: 10px auto;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  font-family: 'Arial', sans-serif;
}
.card-body-appointment{
	padding:5%;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding:2%;
  border-radius: 5%;
}

.back-btn {
  background: transparent; 
  font-size: 20px;
  font-weight: 800;
  border: 2px solid #E1E1E1; /* You can change color and thickness */
  width: 20%;
  padding: 0;          /* Optional: adds spacing inside the border */
  border-radius: 0;    
}
.card-body {
  margin-top: 16px;
}

.pet-info {
  display: flex;
  align-items: center;
  gap: 10px;
  background-color: #FFFFFF;
  margin-bottom: 10px;
  padding: 5%;
  border-radius: 5%;
}

.pet-name {
  font-weight: bold;
  font-size: 16px;
}

.pet-type {
  font-size: 13px;
  color: #777;
}

.appointment-date {
	background-color: #FFFFFF;
	margin-bottom: 10px;
	padding: 5%;
	border-radius: 5%;
}

.appointment-date .label {
  font-size: 13px;
  color: #888;
  margin-bottom: 4px;
}

.appointment-date .date {
  font-size: 15px;
}

.highlight {
  color: orange;
  font-weight: bold;
}

.custom-success-modal {
  border-radius: 12px;
  padding: 30px;
  background: white;
  position: relative;
  max-width: 400px;
  margin: auto;
  box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
}

.success-icon {
  margin-bottom: 20px;
}

.modal-text {
  font-size: 18px;
  color: #333;
}

.close-btn {
  position: absolute;
  top: 15px;
  right: 20px;
  font-size: 22px;
  font-weight: bold;
  color: #888;
  cursor: pointer;
}

.close-btn:hover {
  color: #000;
}

#appoinment_table tbody td{
	font-weight: 500;
	font: 'Poppins';
}

.app_details {
  font-weight: 500;
  border: 1px solid;
  border-radius: 4px;
  width: 38px;
  height: 38px;
  background-color: #E1E1E1;
  display: none;  /* Use inline-flex to maintain the element as an inline block */
  justify-content: center;  /* Center horizontally */
  align-items: center;  /* Center vertically */
  text-align: center;  /* Ensure text inside is centered */
  text-decoration: none;  /* Remove underline from the link */
}

</style>
<style>
/* Responsive card layout for table rows */
@media (max-width: 768px) {
  #appoinment_table {
    display: block;
    width: 100%;
  }

  #appoinment_table thead {
    display: none; /* Hide table headers */
  }

  #appoinment_table tbody {
    display: block;
    width: 100%;
	padding:2%;
  }

#appoinment_table tbody tr td:nth-child(1),
#appoinment_table tbody tr td:nth-child(4) {
  display: none;
}

.app_details{
	display: inline-flex;
}

div.dropdown {
  display: none;
}

.appointment-link {
  display: inline-block;
  padding: 6px 12px;
  margin-top: 10px;
  border: 1px solid #FD9720;
  border-radius: 4px;
  background-color: transparent;
  color: #FD9720;
  font-size: 14px;
  text-decoration: none;
  transition: background-color 0.3s, color 0.3s;
}

.appointment-link:hover {
  background-color: #FD9720;
  color: #fff;
}

.dataTables_wrapper .dataTables_filter {
  background-color: #F7F7F7;
  border-radius: 8px;
  padding: 2%;
  display: flex;
  justify-content: center;     /* Center horizontally */
  align-items: center;         /* Center vertically */
  gap: 10px;                   /* Space between label and input */
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
  text-align: center;
}

.dataTables_wrapper .dataTables_filter input {
  width: 100%;
  max-width: 300px;           /* Optional: controls max input size */
  box-sizing: border-box;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

div.dataTables_wrapper div.dataTables_length{
	display:none;
}

#appoinment_table tbody tr {
  display: flex;
  align-items: center;  /* Vertically center content */
  justify-content: center;  /* Horizontally center content */
  flex-direction: row;  /* Default row direction */
  gap: 0;
  flex-wrap: wrap; /* Optional if you want responsiveness */
  background-color: #F7F7F7;
  border-radius: 8px;
  width: 100%;
}

#appoinment_table tbody tr td > div {
  margin: 0;
  padding: 0;
}

#appoinment_table tbody tr td:not(:nth-child(1)):not(:nth-child(4)):not(:nth-child(5)) {
  margin-bottom: 0;
  padding-bottom: 0;
}

#appoinment_table tbody td {
  display: inline-block;
  width: auto;
  /* padding-left: 5%; */
  margin: 0;
  border: none;
}

  .doctor-img-appointment {
    display: none !important;
  }

  /* Optional: Style labels for each cell if needed */
 
}

.nav-link{
	font-family: Poppins !important;
	font-weight: 600;
	font-size: 18px;
	line-height: 120%;
}
</style>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
																						/** @var array $language */
																						echo $language['lg_home'] ?? " "; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_dashboard'] ?? " "; ?></li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?php echo $language['lg_dashboard'] ?? " "; ?></h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">

		<div class="row">
			<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
				<!-- Profile Sidebar -->

				<?php
				echo view('user/layout/sidebar');
				$user_detail = user_detail(session('user_id'));
				?>
				<!-- / Profile Sidebar -->
			</div>

			<div class="col-md-7 col-lg-8 col-xl-9">

				<?php
				if ($user_detail && $user_detail['is_updated'] == '0') {
					$warn = $language['lg_this_is_a_warni'] ?? "";
					$click = $language['lg_click_here1'] ?? " ";
					$give = $language['lg_give_it_a_click'] ?? " ";
					echo '<div class="alert alert-warning" role="alert">
					<i class="fa fa-exclamation-circle" aria-hidden="true"></i>' . $warn . ' <a href="' . base_url() . session('module') . '/profile" class="alert-link">' . $click . '</a>. ' . $give . '
					</div>';
				}
				if ($user_detail && $user_detail['is_verified'] == '0') {
					$warn = $language['lg_this_is_a_warni1'] ?? "";
					$click = $language['lg_click_here1'] ?? " ";
					$give = $language['lg_give_it_a_click'] ?? " ";
					echo '<div class="alert alert-warning" role="alert">
				    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
					  ' . $warn . ' <a onclick="email_verification()" href="javascript:void(0);" class="alert-link">' . $click . '</a>. ' . $give . '
					</div>';
				}
				?>

				<div class="card">
					<div class="card-body pt-0">

						<!-- Tab Menu -->
						<nav class="user-tabs mb-4">
							<ul class="nav nav-tabs nav-tabs-bottom nav-justified">
								<li class="nav-item">
									<a class="nav-link active" onclick="appoinments_table()" href="#pat_appointments" data-toggle="tab"><?php echo $language['lg_appointments_Schedule'] ?? " "; ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="previous_appoinments_table()" href="#pres" data-toggle="tab"><span><?php echo $language['lg_appointments_Previous'] ?? " "; ?></span></a>
								</li>
								<!--<li class="nav-item">
									<a class="nav-link" onclick="medical_records_table()" href="#medical" data-toggle="tab"><span class="med-records"><?php echo $language['lg_medical_records'] ?? " "; ?></span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="billings_table()" href="#billing" data-toggle="tab"><span><?php echo $language['lg_billing'] ?? " "; ?></span></a>
								</li>-->
							</ul>
						</nav>
						<!-- /Tab Menu -->

						<!-- Tab Content -->
						<div class="tab-content pt-0">

							<input type="hidden" id="patient_id" value="<?php echo session('user_id'); ?>">
							<!-- Appointment Tab -->
							<div id="pat_appointments" class="tab-pane fade show active">
								<div class="card card-table mb-0">
									<div class="card-body appoinment-div-content">
										<div class="table-responsive"> <!-- d-none d-md-block -->
											<table id="appoinment_table" class="table table-hover table-center mb-0">
												<thead>
													<tr>
														<th><?php echo $language['lg_sno'] ?? " "; ?></th>
														<th><?php echo $language['lg_doctor2'] ?? " " . '/' . ($language['lg_clinic'] ?? " ") . ' ' . ($language['lg_name'] ?? " "); ?></th>
														<th><?php echo $language['lg_appt_date'] ?? " "; ?></th>
														<!-- <th><?php //echo $language['lg_booking_date'] ?? " "; ?></th>
														<th><=//echo $language['lg_type'] ?? " "; ?></th> -->
<!--    // Pet update code
    //added new on 13rd June 2024 by Muddasar -->
                                                        <th>Pet</th>
														<th></th>
														<!--<th>Archive</th>-->
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>


										<!-- Mobile Cards -->
										<!-- <div id="mobile-cards-container" class="d-block d-md-none">

										
										</div> -->
									</div>

									<div class="card-body no-appointment" style="display:none;padding: 3%;background-color: #F7F7F7;">
										<div style="background-color:#FFFFFF;padding: 4%;">
											<h2 style="color:#252525; text-align: center;padding: 3%;font-family:Poppins;font-weight:400;font-size:16px;border-radius:12px;margin: 0;">No data available in table</h2>
										<a class="apt-btn-book" href="<?php echo base_url().'search-veterinary?type=1' ?>" style="background-color:#FD9720;color:#FFFFFF;font-family:Poppins;padding:12px 20px 12px 20px;border-radius:4px;font-weight:600;font-size:16px">Book Appointment</a>
										</div>
									</div>
								</div>

									

							
							</div>
							<!-- /Appointment Tab -->

							<!-- Prescription Tab -->
							<div class="tab-pane fade" id="pres">
								<div class="card card-table mb-0">
									<div class="card-body previous-appointment-content">
										<div class="table-responsive">
											<table id="previous_appointment_table" style="width:100%" class="table table-hover table-center mb-0">
												<thead>
													<tr>
													<th><?php echo $language['lg_sno'] ?? " "; ?></th>
														<th><?php echo $language['lg_doctor2'] ?? " " . '/' . ($language['lg_clinic'] ?? " ") . ' ' . ($language['lg_name'] ?? " "); ?></th>
														<th><?php echo $language['lg_appt_date'] ?? " "; ?></th>
														<!-- <th><?php //echo $language['lg_booking_date'] ?? " "; ?></th>
														<th><=//echo $language['lg_type'] ?? " "; ?></th> -->
<!--    // Pet update code
    //added new on 13rd June 2024 by Muddasar -->
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
										<h2 style="color:#252525; text-align: center;padding: 3%;font-family:Poppins;font-weight:400;font-size:16px;background-color:#FFFFFF;border-radius:12px;">No data available in table</h2>
									</div>
								</div>
							</div>
							<!-- /Prescription Tab -->

							<!-- Medical Records Tab -->
							<div class="tab-pane fade" id="medical">
								<?php if (is_patient()) { ?>
									<!-- <div class="text-right">		
												<a href="#" class="add-new-btn"  data-toggle="modal" data-target="#add_medical_records"><?php echo $language['lg_add_medical_rec'] ?? " "; ?></a>
											</div> -->
								<?php } ?>
								<div class="card card-table mb-0">
									<div class="card-body">
										<div class="table-responsive">
											<table id="medical_records_table" class="table table-hover table-center mb-0" style="width: 100%">
												<thead>
													<tr>
														<th><?php echo $language['lg_sno'] ?? " "; ?></th>
														<th><?php echo $language['lg_date1'] ?? " "; ?> </th>
														<th><?php echo $language['lg_description'] ?? " "; ?></th>
														<th><?php echo $language['lg_attachment'] ?? " "; ?></th>
														<th><?php echo $language['lg_doctor2'] ?? " "; ?></th>
														<th data-orderable="false"><?php echo $language['lg_view1'] ?? " "; ?></th>

													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!-- /Medical Records Tab -->

							<!-- Billing Tab -->
							<div class="tab-pane" id="billing">
								<div class="card card-table mb-0">
									<div class="card-body">
										<div class="table-responsive">

											<table id="billing_table" class="table table-hover table-center mb-0" style="width:100%">
												<thead>
													<tr>
														<th><?php echo $language['lg_sno'] ?? " "; ?></th>
														<th><?php echo $language['lg_date1'] ?? " "; ?></th>
														<th><?php echo $language['lg_description'] ?? " "; ?></th>
														<th><?php echo $language['lg_doctor2'] ?? " "; ?></th>
														<th data-orderable="false"><?php echo $language['lg_view1'] ?? " "; ?></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!-- Billing Tab -->
							 
								

						</div>
						<!-- Tab Content -->

					</div>
				</div>
			</div>
		</div>

	</div>

</div>
<!-- /Page Content -->
<script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>

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