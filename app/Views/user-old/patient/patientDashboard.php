<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

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
									<a class="nav-link active" onclick="appoinments_table()" href="#pat_appointments" data-toggle="tab"><?php echo $language['lg_appointments'] ?? " "; ?></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" onclick="prescriptions_table()" href="#pres" data-toggle="tab"><span><?php echo $language['lg_prescription'] ?? " "; ?></span></a>
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
									<div class="card-body">
										<div class="table-responsive">
											<table id="appoinment_table" class="table table-hover table-center mb-0">
												<thead>
													<tr>
														<th><?php echo $language['lg_sno'] ?? " "; ?></th>
														<th><?php echo $language['lg_doctor2'] ?? " " . '/' . ($language['lg_clinic'] ?? " ") . ' ' . ($language['lg_name'] ?? " "); ?></th>
														<th><?php echo $language['lg_appt_date'] ?? " "; ?></th>
														<th><?php echo $language['lg_booking_date'] ?? " "; ?></th>
														<th><?php echo $language['lg_type'] ?? " "; ?></th>
<!--    // Pet update code
    //added new on 13rd June 2024 by Muddasar -->
                                                        <th>Pet</th>
														<!--<th>Archive</th>-->
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!-- /Appointment Tab -->

							<!-- Prescription Tab -->
							<div class="tab-pane fade" id="pres">
								<div class="card card-table mb-0">
									<div class="card-body">
										<div class="table-responsive">
											<table id="prescription_table" style="width:100%" class="table table-hover table-center mb-0">
												<thead>
													<tr>
														<th><?php echo $language['lg_sno'] ?? " "; ?></th>
														<th><?php echo $language['lg_date1'] ?? " "; ?></th>
														<th><?php echo $language['lg_prescription_number'] ?? " "; ?></th>
														<th><?php echo $language['lg_doctor2'] ?? " "; ?></th>
														<th data-orderable="false"><?php echo $language['lg_action'] ?? " "; ?></th>
													</tr>
												</thead>
												<tbody>

												</tbody>
											</table>
										</div>
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



<?php $this->endSection(); ?>