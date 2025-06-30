<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><?php 
                                      /** @var array $language  */
									echo $language['lg_dashboard'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_patient_profile'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_patient_profile'];?></h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">

					<div class="row">
						<div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar dct-dashbd-lft">

							
						
							<!-- Profile Widget -->
							<?php echo view('user/doctor/patient_profile_widget');?>
							<!-- /Profile Widget -->
							
							
							
						</div>

						<div class="col-md-7 col-lg-8 col-xl-9 dct-appoinment">
							<div class="card">
								<div class="card-body pt-0">
									<div class="user-tabs">
										<input type="hidden" id="patient_id" value="<?php 
                                        /** @var array $patient  */
										echo $patient['userid'];?>">
										<ul class="nav nav-tabs nav-tabs-bottom flex-wrap">
											<li class="nav-item">
												<a class="nav-link active" onclick="appoinments_table()" href="#pat_appointments" data-toggle="tab"><?php echo $language['lg_appointments'];?></a>
											</li>
											<li class="nav-item">
												<a class="nav-link" onclick="prescriptions_table()" href="#pres" data-toggle="tab"><span><?php echo $language['lg_prescription'];?></span></a>
											</li>
											<!--<li class="nav-item">
												<a class="nav-link" onclick="medical_records_table()" href="#medical" data-toggle="tab"><span class="med-records"><?php //echo $language['lg_medical_records'];?></span></a>
											</li>
											<li class="nav-item">
												<a class="nav-link" onclick="billings_table()"  href="#billing" data-toggle="tab"><span><?php// echo $language['lg_billing'];?></span></a>
											</li>-->
										</ul>
									</div>
									<div class="tab-content">
										
										<!-- Appointment Tab -->
										<div id="pat_appointments" class="tab-pane fade show active">
											<div class="card card-table mb-0">
												<div class="card-body">
													<div class="table-responsive">
														<table id="appoinment_table" class="table table-hover table-center mb-0">
															<thead>
																<tr>
																	<th><?php echo $language['lg_sno'];?></th>
																	<th><?php echo $language['lg_doctor2'];?></th>
																	<th><?php echo $language['lg_appt_date'];?></th>
																	<th><?php echo $language['lg_booking_date'];?></th>
																	<th><?php echo $language['lg_type'];?></th>
																	 <!--Pet update code
                                                                        //added new on 21st June 2024 by Muddasar-->
                                                                    <th>Pet</th>
																	<?php if(session('role')!=6) { ?>
																	<th data-orderable="false"><?php echo $language['lg_action'];?></th>
																	<?php } ?>
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
											<?php if(is_doctor()){ ?>
											<div class="text-right">
												<a href="<?php 
                                                 /** @var array $patient */
												echo base_url().'my_patients/add-prescription/'.base64_encode($patient['userid']);?>" class="add-new-btn <?php 
                                                /** @var int $prescription_status */
												echo ($prescription_status == 0)?'noaction':''; ?>"><?php echo $language['lg_add_prescriptio'];?></a>
											</div>
										<?php } ?>
											<div class="card card-table mb-0">
												<div class="card-body">
													<div class="table-responsive">
														<table id="prescription_table" style="width:100%" class="table table-hover table-center mb-0">
															<thead>
																<tr>
																	<th>S.No</th>
																	<th><?php echo $language['lg_date1'];?></th>
																	<th><?php echo $language['lg_name'];?></th>	
																	<th><?php echo $language['lg_doctor2'];?></th>
																	<th data-orderable="false"><?php echo $language['lg_action'];?></th>
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
										<?php if(is_doctor()){ ?>
											<div class="text-right">		
												<a href="#" class="add-new-btn <?php
                                                 /** @var int $prescription_status */
												 echo ($prescription_status == 0)?'noaction':''; ?>"  data-toggle="modal" data-target="#add_medical_records"><?php echo $language['lg_add_medical_rec'];?></a>
											</div>
										<?php } ?>
											<div class="card card-table mb-0">
												<div class="card-body">
													<div class="table-responsive">
														<table id="medical_records_table" class="table table-hover table-center mb-0" style="width: 100%">
															<thead>
																<tr>
																	<th>S.No</th>
																	<th><?php echo $language['lg_date1'];?> </th>
																	<th><?php echo $language['lg_description'];?></th>
																	<th><?php echo $language['lg_attachment'];?></th>
																	<th><?php echo $language['lg_doctor2'];?></th>
																	<?php if(is_doctor() || is_clinic()){ ?>
																	<th data-orderable="false"><?php echo $language['lg_action'];?></th>
																    <?php } ?>
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
											<?php if(is_doctor()){ ?>
											<div class="text-right">
												<a class="add-new-btn <?php 
                                                /** @var int $prescription_status */
												echo ($prescription_status == 0)?'noaction':''; ?>" href="<?php echo base_url().'my_patients/add-billing/'.base64_encode($patient['userid']);?>"><?php echo $language['lg_add_billing'];?></a>
											</div>
										<?php } ?>
											<div class="card card-table mb-0">
												<div class="card-body">
													<div class="table-responsive">
													
														<table id="billing_table" class="table table-hover table-center mb-0" style="width:100%">
															<thead>
																<tr>
																	<th>S.No</th>
																	<th><?php echo $language['lg_date1'];?></th>
																	<th><?php echo $language['lg_description'];?></th>
																	<th><?php echo $language['lg_doctor2'];?></th>
																	<th data-orderable="false"><?php echo $language['lg_action'];?></th>
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
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->
			<?php $this->endSection(); ?>

