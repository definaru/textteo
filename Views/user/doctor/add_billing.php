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
									echo $language['lg_dashboard']??"";?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_add_billing']??"";?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_add_billing']??"";?></h2>
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
									<h4 class="card-title mb-0"><?php echo $language['lg_add_billing']??"";?></h4>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="biller-info">
												<h4 class="d-block"><?php echo $language['lg_dr']??"";?> <?php echo ucfirst(libsodiumDecrypt($user_detail['first_name']).' '.libsodiumDecrypt($user_detail['last_name']));?></h4>
												<span class="d-block text-sm text-muted"><?php echo ucfirst(libsodiumDecrypt($user_detail['speciality']));?></span>
												<span class="d-block text-sm text-muted"><?php echo ucfirst(libsodiumDecrypt($user_detail['address1']));?></span>
												<span class="d-block text-sm text-muted"><?php echo ucfirst(libsodiumDecrypt($user_detail['address2']));?></span>
												<span class="d-block text-sm text-muted"><?php echo $user_detail['cityname'].', '.$user_detail['countryname'];?></span>
												<span class="d-block text-sm text-muted"><?php echo ucfirst($user_detail['postal_code']);?></span>
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
										<a href="javascript:void(0);" onclick="add_more_row()"><i class="fas fa-plus-circle"></i><?php echo $language['lg_add_item']??"";?> </a>
									</div>
									<!-- /Add Item -->
									<form id="add_billing" method="post" autocomplete="off" >
							           <input type="hidden" name="patient_id" value="<?php
                                         /** @var int $patient_id */
							            echo $patient_id; ?>">
									
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
													<tbody class="more-rows">
													  <tr id="delete_1">								
														 <td>
															<input type="text" name="name[]" id="name1" class="form-control filter-form inputcls" >
														 </td>
														 <td>
															<input type="decimal" onkeypress="return isNumberKey(event)" name="amount[]" id="amount1" class="form-control filter-form inputcls" >
														</td>							
														<td>
														<!-- <a href="javascript:void(0)" class="btn bg-danger-light trash" onclick="delete_row(1)" style="pointer-events: none" > -->
															<!-- <i class="far fa-trash-alt"></i> -->
														<!-- </a> -->
														</td>
												      </tr>
											       </tbody>
											      <tbody id="add_more_rows"></tbody>
												</table>
											</div>
										</div>
									</div>
									<!-- /Billing Item -->
									<input type="hidden" value="1" id="hidden_count">
									
									<!-- Signature -->
									<div class="row">
										<div class="col-md-12 text-right">
											<div class="signature-wrap">
												<div class="signature doctor_signature">
													<input type="hidden" name="signature_id" value="0" id="signature_id">
													<?php echo $language['lg_click_here_to_s'];?>
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
												<button type="submit" id="bill_save_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_save'];?></button>
												<button type="reset" class="btn btn-secondary submit-btn clear_sign"><?php echo $language['lg_clear'];?></button>
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