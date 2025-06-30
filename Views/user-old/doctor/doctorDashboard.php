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
							<?php 
							echo view('user/layout/sidebar');
							$user_detail=user_detail(session('user_id'));
							?>
							<!-- /Profile Sidebar -->

						</div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">

							<?php 

							$lg_give_it_a_click=$language['lg_give_it_a_click']??"";
							$lg_this_is_a_warni=$language['lg_this_is_a_warni']??"";
							$lg_click_here1=$language['lg_click_here1']??"";
							$lg_this_is_a_warni1=$language['lg_this_is_a_warni1']??"";
							$lg_give_it_a_click1=$language['lg_give_it_a_click1']??"";
							$lg_click_here1=$language['lg_click_here1']??"";

							if($user_detail['is_updated']=='0') {
								echo'<div class="alert alert-warning" role="alert">
								<i class="fa fa-exclamation-circle" aria-hidden="true"></i>'.$lg_this_is_a_warni.' <a href="'.base_url().session('module').'/profile" class="alert-link">'.$lg_click_here1.'</a>. '.$lg_give_it_a_click.'
								</div>';
						    }
						    if($user_detail['is_verified']=='0') {
								echo'<div class="alert alert-warning" role="alert">
								<i class="fa fa-exclamation-circle" aria-hidden="true"></i>
								'.$lg_this_is_a_warni1.' <a onclick="email_verification()" href="javascript:void(0);" class="alert-link">'.$lg_click_here1.'</a>. '.$lg_give_it_a_click1.'
								</div>';
						    }

						    ?>

							<div class="row">
								<div class="col-md-12">
									<div class="card dash-card">
										<div class="card-body">
											<div class="row">
												<div class="col-md-12 col-lg-4">
													<div class="dash-widget dct-border-rht">
														<div class="circle-bar circle-bar1">
															<div class="circle-graph1" data-percent="<?php echo $total_patient??"";?>">
																<img src="<?php echo base_url();?>assets/img/icon01.png" class="img-fluid" alt="patient">
															</div>
														</div>
														<div class="dash-widget-info">
															<h6><?php echo $language['lg_total_patient']??"";?></h6>
															<h3><?php echo $total_patient??"";?></h3>
															<p class="text-muted"><?php echo $language['lg_till_today']??""; ?></p>
														</div>
													</div>
												</div>												
												<div class="col-md-12 col-lg-4">
													<div class="dash-widget dct-border-rht">
														<div class="circle-bar circle-bar2">
															<div class="circle-graph2" data-percent="<?php echo $today_patient??"";?>">
																<img src="<?php echo base_url();?>assets/img/icon02.png" class="img-fluid" alt="Patient">
															</div>
														</div>
														<div class="dash-widget-info">
															<h6><?php echo $language['lg_today_patient']??"";?></h6>
															<h3><?php echo $today_patient;?></h3>
															<p class="text-muted"><?php echo date('d M Y',strtotime(date("y-m-d")));  ?></p>
														</div>
													</div>
												</div>												
												<div class="col-md-12 col-lg-4">
													<div class="dash-widget">
														<div class="circle-bar circle-bar3">
															<div class="circle-graph3" data-percent="<?php echo $recents??"";?>">
																<img src="<?php echo base_url();?>assets/img/icon03.png" class="img-fluid" alt="Patient">
															</div>
														</div>
														<div class="dash-widget-info">
															<h6><?php echo $language['lg_appointments']??"";?></h6>
															<h3><?php echo $recents??"";?></h3>
															<p class="text-muted"><?php echo $language['lg_till_today']??""; ?></p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12">
									<h4 class="mb-4"><?php echo $language['lg_patient_appoinm']??"";?></h4>
									<div class="appointment-tab">
									
										<!-- Appointment Tab -->
										<ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded">
											<li class="nav-item">
												<a class="nav-link active" href="#appointments" data-toggle="tab" onclick="appoinments_table(1)"><?php echo $language['lg_today']??"";?></a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="#appointments" data-toggle="tab" onclick="appoinments_table(2)"><?php echo $language['lg_upcoming']??"";?></a>
											</li> 
										</ul>
										<!-- /Appointment Tab -->
										
										<div class="tab-content">
										
											
											<div class="tab-pane show active" id="appointments">
												<div class="card card-table mb-0">
													<div class="card-body">
														<div class="table-responsive">
															<input type="hidden" id="type">
															<table id="appoinments_table" class="table table-hover table-center mb-0 w-100">
																<thead>
																	<tr>
																		<th><?php echo $language['lg_sno']??"";?></th>
																		<th><?php echo $language['lg_patient_name']??"";?></th>
																		<th><?php echo $language['lg_appoinment_date']??"";?></th>
																		<th><?php echo $language['lg_appoinment_type']??"";?></th>
																		<!--Pet update code
                                                                            //added new on 21st June 2024 by Muddasar-->
                                                                        <th>Pet</th>
																		<?php if(session('role')==6){ ?>
																		<th><?php echo $language['lg_assigned_to']??"";?></th>
																		<?php } ?>
																		<th><?php echo $language['lg_action']??"";?></th>
																		
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
			<!-- /Page Content -->
			<?php $this->endSection(); ?>
   
		