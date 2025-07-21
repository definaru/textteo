<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

			<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php 
                                     /** @var array $language */
									echo $language['lg_home']??"";?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_dashboard']??"";?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_dashboard']??"";?></h2>
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
                            <?= 
                            view('user/layout/sidebar'); 							
							$user_detail=user_detail(session('user_id'));
							?>
							<!-- /Profile Sidebar -->


							
						</div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">

						<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-7 col-auto">
								<h3 class="page-title">List of Veterinarians</h3>
								
							</div>
							<div class="col-sm-5 col">
								<a href="javascript:void(0);" onclick="add_doct()" class="btn btn-primary float-right mt-2">Add Veterinarian</a>
							</div>
						</div>
					</div>
					<!-- /Page Header -->

					
							<div class="row">
								<div class="col-md-12">
								
									
									<div class="appointment-tab">
										<!-- Appointment Tab -->
									
										<!-- /Appointment Tab -->										
										<div class="tab-content">
										
											<small style="color: red">Only profile updated & Email verified Veterinarians will be allowed for Appointments</small>
											<div class="tab-pane show active" id="appointments">
												<div class="card card-table mb-0">
													<div class="card-body">
														<div class="table-responsive">
															<input type="hidden" id="type">
															<table id="doctor_table" class="table table-hover table-center mb-0 w-100">
																<thead>
																	<tr>
																		<th>S.No</th>
																		<th>Name</th>
																		<th>Email</th>
																		<th>Profile Updated</th>
																		<th>Email Verified</th>
																		<th>Action</th>
																		
																	</tr>
																</thead>
																<tbody>
																</tbody>
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

			</div>		

            

            <?php $this->endSection(); ?>
			<!-- /Page Content -->
			<script type="text/javascript">
				var country='';
			    var state='';
			    var city='';
			    var country_code='';
			</script>

				
   
		