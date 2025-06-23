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
                                     /** @var array $language */
									echo $language['lg_dashboard']??"";?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_edit_prescripti']??"";?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_edit_prescripti']??"";?></h2>
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
									<h4 class="card-title mb-0"><?php echo $language['lg_edit_prescripti']??"";?></h4>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="biller-info">
												<h4 class="d-block"><?php echo $language['lg_dr']??"";?> <?php echo ucfirst(libsodiumDecrypt($user_detail['first_name']).' '.libsodiumDecrypt($user_detail['last_name']));?></h4>
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
										<a href="javascript:void(0);" onclick="add_more_row()"><i class="fas fa-plus-circle"></i> <?php echo $language['lg_add_item']??"";?></a>
									</div>
									<!-- /Add Item -->
									<form id="update_prescription" method="post" autocomplete="off" >
							           <input type="hidden" name="patient_id" value="<?php 
                                       /** @var int $patient_id */
							           echo $patient_id??""; ?>">
							           <input type="hidden" name="prescription_id" value="<?php 
                                       /** @var array $prescription */
							           echo $prescription[0]['prescription_id']??""; ?>">
									<!-- Prescription Item -->
									<div class="card card-table">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-hover table-center">
													<thead>
														<tr>
															<th style="min-width: 200px"><?php echo $language['lg_drug_name']??"";?></th>
															<th style="min-width: 100px; max-width: 100px;"><?php echo $language['lg_quantity']??"";?></th>
															<th style="min-width: 100px"><?php echo $language['lg_type']??"";?></th>
															<th style="min-width: 100px; max-width: 100px;"><?php echo $language['lg_days']??"";?></th>
															<th style="min-width: 100px"><?php echo $language['lg_time']??"";?></th>
															<th style="min-width: 80px"><?php echo $language['lg_action']??"";?></th>
														</tr>
													</thead>
													<tbody>


														<?php 
														$i=0;
														foreach ($prescription as $p) { 
															$j=$i++;
															$time=array();
															$morning = '';
															$afternoon = '';
															$evening = '';
															$night = '';

														if(!empty($p['time'])){
															$time = explode(',',$p['time']);

															if(in_array('Morning',$time)){
																$morning = 'checked="checked"';
																}
															if(in_array('Afternoon',$time)){
																$afternoon = 'checked="checked"';
																}
															if(in_array('Evening',$time)){
																$evening = 'checked="checked"';
																}
															if(in_array('Night',$time)){
																$night = 'checked="checked"';
																}

														}


															?>
															
														<tr id="delete_<?php echo $i;?>">
													<td>
														<input type="text" class="form-control filter-form inputcls" name="drug_name[]" value="<?php echo $p['drug_name'];?>" id="drug_name<?php echo $i;?>" >
													</td>
													<td style="min-width: 100px; max-width: 100px;">
														<input onkeypress="return isNumberKey(event)" class="form-control filter-form text inputcls" value="<?php echo $p['qty'];?>" type="text" name="qty[]" id="qty<?php echo $i;?>" >
													</td>
													<td>
														<select class="form-control inputcls" name="type[]">
															<option value=""><?php echo $language['lg_select_type'];?></option>
															<option value="Before Food" <?php echo ($p['type']=='Before Food')?'selected':'';?>><?php echo $language['lg_before_food'];?></option>
															<option value="After Food" <?php echo ($p['type']=='After Food')?'selected':'';?>><?php echo $language['lg_after_food'];?></option>
														</select>
													</td>
													<td style="min-width: 100px; max-width: 100px;">
														<input onkeypress="return isNumberKey(event)" class="form-control filter-form text inputcls" value="<?php echo $p['days'];?>"  type="text" name="days[]" id="days<?php echo $i;?>" maxlength="4">
													</td>
													<td class="checkbozcls">
														<div class="row">
															<div class="col-md-6">
																<input type="checkbox" <?php echo $morning;?> name="time[<?php echo $j;?>][]" value="Morning" id="morning<?php echo $i;?>"><label for="morning<?php echo $i;?>">&nbsp;&nbsp;<?php echo $language['lg_morning'];?></label>
															</div>
															<div class="col-md-6">
																<input type="checkbox" <?php echo $afternoon;?> name="time[<?php echo $j;?>][]" value="Afternoon" id="afternoon<?php echo $i;?>"><label for="afternoon<?php echo $i;?>">&nbsp;&nbsp;<?php echo $language['lg_afternoon'];?></label>
															</div>
														</div>
														<div class="row">
															<div class="col-md-6">
																<input type="checkbox" <?php echo $evening;?> name="time[<?php echo $j;?>][]" value="Evening" id="evening<?php echo $i;?>"><label for="evening<?php echo $i;?>">&nbsp;&nbsp;<?php echo $language['lg_evening'];?></label>
															</div>
															<div class="col-md-6">
																<input type="checkbox" <?php echo $night;?> name="time[<?php echo $j;?>][]" value="Night" id="night<?php echo $i;?>"><label for="night<?php echo $i;?>">&nbsp;&nbsp;<?php echo $language['lg_night'];?></label>
															</div>
														</div>
														<input type="hidden" value="<?php echo $j;?>" name="rowValue[]">
													</td>
													<td>
														<?php if($i!='1') { ?>
														<a href="javascript:void(0)" class="btn bg-danger-light trash" onclick="delete_row(<?php echo $i;?>)">
															<i class="far fa-trash-alt"></i>
														</a>
													<?php } ?>
													</td>
												</tr>
												<?php  } ?>										
											</tbody>
											<tbody id="add_more_rows"></tbody>
										</table>
										<input type="hidden" value="<?php echo count($prescription);?>" id="hidden_count">
									</div>
										</div>
									</div>
									<!-- /Prescription Item -->
									
									<!-- Signature -->
									<div class="row">
										<div class="col-md-12 text-right">
											<div class="signature-wrap">
												<div class="signature doctor_signature" id="edit">
													<img src="<?php echo base_url().$prescription[0]['img']; ?>" style="width:200px; height:auto" alt="">
													<input type="hidden" name="signature_id" value="<?php echo $prescription[0]['signature_id']; ?>" id="signature_id">
													<input type="hidden" name="rowno" value="<?php echo $prescription[0]['rowno']; ?>" id="rownos">
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
											<div class="submit-section text-center">
												<button name="form_submit" value="true" type="submit" id="prescription_update_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_update'];?></button>
												<!-- <button type="reset" class="btn btn-secondary submit-btn">Clear</button> -->
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

			