<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url().session('module');?>"><?php 
                                     /** @var array $language */
									echo $language['lg_dashboard'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_edit_billing'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_edit_billing'];?></h2>
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
						
							<!-- Profile Widget -->
							<?php 
							      echo view('user/doctor/patient_profile_widget');
							      $user_detail=user_detail(session('user_id'));
							?>
							<!-- /Profile Widget -->
							
						</div>

						<div class="col-md-7 col-lg-8 col-xl-9">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title mb-0"><?php echo $language['lg_edit_billing'];?></h4>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="biller-info">
												<h4 class="d-block"><?php echo $language['lg_dr'];?> <?php echo ucfirst(libsodiumDecrypt($user_detail['first_name']).' '.libsodiumDecrypt($user_detail['last_name']));?></h4>
												<span class="d-block text-sm text-muted"><?php echo ucfirst(libsodiumDecrypt($user_detail['speciality']));?></span>
												<span class="d-block text-sm text-muted"><?php echo $user_detail['cityname'].', '.$user_detail['countryname'];?></span>
											</div>
										</div>
										<div class="col-sm-6 text-sm-right">
											<div class="billing-info">
												<h4 class="d-block"><?php echo date('d M Y');?></h4>
											</div>
										</div>
									</div>
									
									<!-- Add Item -->
									<div class="add-more-item text-right">
										<a href="javascript:void(0);" onclick="add_more_row()"><i class="fas fa-plus-circle"></i> <?php echo $language['lg_add_item'];?></a>
									</div>
									<!-- /Add Item -->
									<form id="update_billing" method="post" autocomplete="off" >
							           <input type="hidden" name="patient_id" value="<?php 
                                       /** @var int $patient_id  */
							           echo $patient_id; ?>">
							           <input type="hidden" name="billing_id" value="<?php
                                        /** @var array $billing */
							            echo $billing[0]['billing_id']; ?>">
									
									<!-- Billing Item -->
									<div class="card card-table">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-hover table-center">
													<thead>
														<tr>													
															<th style="min-width:200px;"><?php echo $language['lg_name'];?></th>					
															<th style="min-width:200px;"><?php echo $language['lg_amount'];?></th>		
															<th style="width:80px;"><?php echo $language['lg_action'];?></th>
														</tr>
													</thead>
													<tbody>
														<?php 
														$i=1;
														foreach ($billing as $b) { ?>
													  <tr id="delete_<?php echo $i;?>">								
														 <td>
															<input type="text" name="name[]" id="name<?php echo $i;?>" value="<?php echo $b['name'];?>" class="form-control filter-form" >
														 </td>
														 <td>
															<input type="text" onkeypress="return isNumberKey(event)" value="<?php echo $b['amount'];?>" name="amount[]" id="amount<?php echo $i;?>" class="form-control filter-form" >
														</td>							
														<td>
															<?php if($i!='1') { ?>
														<a href="javascript:void(0)" class="btn bg-danger-light trash" onclick="delete_row(<?php echo $i;?>)">
															<i class="far fa-trash-alt"></i>
														</a>
													     <?php } ?>
														</td>
												      </tr>
												      <?php $i++; } ?>
											       </tbody>
											      <tbody id="add_more_rows"></tbody>
												</table>
											</div>
										</div>
									</div>
									<!-- /Billing Item -->
									<input type="hidden" value="<?php echo count($billing);?>" id="hidden_count">
									
									<!-- Signature -->
									<div class="row">
										<div class="col-md-12 text-right">
											<div class="signature-wrap">
												<div class="signature doctor_signature" id="edit">
													<img src="<?php echo base_url().$billing[0]['img']; ?>" style="width:200px; height:auto" alt="">
													<input type="hidden" name="signature_id" value="<?php echo $billing[0]['signature_id']; ?>" id="signature_id">
													<input type="hidden" name="rowno" value="<?php echo $billing[0]['rowno']; ?>" id="rownos">
												</div>
												<div class="sign-name">
													<p class="mb-0">( <?php echo $language['lg_dr'];?> <?php echo ucfirst(libsodiumDecrypt($user_detail['first_name']).' '.libsodiumDecrypt($user_detail['last_name']));?> )</p>
													<span class="text-muted"><?php echo $language['lg_signature'];?></span>
												</div>
											</div>
										</div>
									</div>
									<!-- /Signature -->
									
									<!-- Submit Section -->
									<div class="row">
										<div class="col-md-12">
											<div class="submit-section">
												<button type="submit" id="billing_update_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_update'];?></button>
<!-- 												<button type="reset" class="btn btn-secondary submit-btn">Clear</button> -->
											</div>
										</div>
									</div>
									<!-- /Submit Section -->
									</form>
									
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->
			<?php $this->endSection(); ?>