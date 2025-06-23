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
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_profile_setting']??"";?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_profile_setting']??"";?></h2>
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
                        <?= view('user/layout/sidebar'); ?>
						<!-- /Profile Sidebar -->
					    </div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">
							<div class="card">
								<div class="card-body">
									
									<!-- Profile Settings Form -->
									<form method="post" action="#" id="patient_profile_form" autocomplete="off">

								<input type="hidden" value="<?php echo date('d/m/Y',strtotime('-1 day')); ?>" id="maxDate">
								<input type="hidden" value="<?php echo session('user_id'); ?>" id="user_id">
								<input type="hidden"  id="country_id" name="country_id" value="<?php echo $profile['country_id'];?>">
								

							<?php							
							if($profile['profileimage']=="" || ($profile['profileimage']!="" && !is_file($profile['profileimage']))){
								$user_profile_image=base_url().'assets/img/user.png';
							}
							else {
								$user_profile_image=(!empty($profile['profileimage']??""))?base_url().$profile['profileimage']??"":base_url().'assets/img/user.png';
							}
							 ?>

							 
						
							<!-- Basic Information -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_basic_informati']??"";?></h4>
									<div class="row form-row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="change-avatar">
													<div class="profile-img">
														<img src="<?php echo $user_profile_image;?>" alt="User Image" class="avatar-view-img">
													</div>
													<div class="upload-img">
														<div class="change-photo-btn avatar-view-btn">
															<span><i class="fa fa-upload"></i> <?php echo $language['lg_upload_photo']??"";?></span>
															<input type="hidden" id="crop_prof_img" name="profile_image">
														</div>
														<small class="form-text text-muted"><?php echo $language['lg_allowed_jpg_gif']??"";?></small>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_first_name']??"";?> <span class="text-danger">*</span></label>
												<input type="text" name="first_name" id="first_name" value="<?php 
												/** @var array $profile */
												echo libsodiumDecrypt($profile['first_name'])??"";?>" class="form-control"  maxlength="100">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_last_name']??"";?> <span class="text-danger">*</span></label>
												<input type="text" name="last_name" id="last_name" value="<?php echo libsodiumDecrypt($profile['last_name'])??"";?>" class="form-control"  maxlength="100">
											</div>
										</div>
										<div class="col-md-6">
										 	<div class="form-group">
												<label><?php echo $language['lg_country_code']??""; ?><span class="text-danger">*</span></label>
                          						<select name="country_code" class="form-control" id="country_code">
                          							<option value=""><?php echo $language['lg_select_country']??"";?></option>
                          						</select>
                          					</div>
                        				</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_mobile_number']??"";?> <span class="text-danger">*</span></label>
												<input type="text"  id="mobileno" name="mobileno" value="<?php echo libsodiumDecrypt($profile['mobileno'])??"";?>" class="form-control mobileNoOnly"  maxlength="15" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_select_gender']??"";?> <span class="text-danger">*</span></label>
												<select class="form-control" name="gender" id="gender">
													<option value=""><?php echo $language['lg_select']??"";?></option>
													<option value="Male" <?php echo ($profile['gender']=='Male')?'selected':'';?>><?php echo $language['lg_male']??"";?></option>
													<option value="Female" <?php echo ($profile['gender']=='Female')?'selected':'';?>><?php echo $language['lg_female']??"";?></option>
												</select>
											</div>
										</div>
										<!--<div class="col-md-6">
											<div class="form-group mb-0">
												<label><?php //echo $language['lg_date_of_birth']??"";?> <span class="text-danger">*</span></label>												
												<input type="text" name="dob" id="dob" value="<?php  //echo !empty($profile['dob']??"")?date('d/m/Y',strtotime(str_replace('-', '/', $profile['dob']??""))):''; ?>" class="form-control">
											</div>
										</div>-->
										<!--<div class="col-md-6">
												<div class="form-group">
													<label><?php //echo $language['lg_blood_group']??"";?> <span class="text-danger">*</span></label>
													<select class="form-control" name="blood_group" id="blood_group">
														<option value=""><?php //echo $language['lg_select']??"";?></option>
														<option value="A-" <?php //echo (libsodiumDecrypt($profile['blood_group']??"")=='A-')?'selected':'';?>><?php //echo $language['lg_a2']??"";?></option>
														<option value="A+" <?php //echo (libsodiumDecrypt($profile['blood_group']??"")=='A+')?'selected':'';?>><?php //echo $language['lg_a3']??"";?></option>
														<option value="B-" <?php //echo (libsodiumDecrypt($profile['blood_group']??"")=='B-')?'selected':'';?>><?php //echo $language['lg_b6']??"";?></option>
														<option value="B+" <?php //echo (libsodiumDecrypt($profile['blood_group']??"")=='B+')?'selected':'';?>><?php //echo $language['lg_b7']??"";?></option>
														<option value="AB-" <?php //echo (libsodiumDecrypt($profile['blood_group']??"")=='AB-')?'selected':'';?>><?php //echo $language['lg_ab1']??"";?></option>
														<option value="AB+" <?php //echo (libsodiumDecrypt($profile['blood_group']??"")=='AB+')?'selected':'';?>><?php //echo $language['lg_ab2']??"";?></option>
														<option value="O-" <?php //echo (libsodiumDecrypt($profile['blood_group']??"")=='O-')?'selected':'';?>><?php //echo $language['lg_o4']??"";?></option>
														<option value="O+" <?php //echo (libsodiumDecrypt($profile['blood_group']??"")=='O+')?'selected':'';?>><?php //echo $language['lg_o5']??"";?></option>
													</select>
												</div>
											</div>-->
									</div>
								</div>
							</div>
							<!-- /Basic Information -->
                                        
                            <?php /*?><div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        
                                        <?php
                                        var_dump($user_pets);
                                        ?>
                                        
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPetModal">
                                            Add New Pet
                                        </button>
                                    </div>
                                </div>
                            </div><?php */?>
                                        
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Pets</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Image</th>
                                                            <th>Name</th>
                                                            <th>Birth Date</th>
                                                            <th>Type</th>
                                                            <th>Breed</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($user_pets)) : ?>
                                                            <?php foreach ($user_pets as $pet) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php if (!empty($pet['pet_photo'])) : ?>
                                                                            <img src="<?= base_url('uploads/pet_images/' . $pet['pet_photo']); ?>" class="img-thumbnail" width="100" height="100" alt="Pet Image">
                                                                        <?php else : ?>
                                                                            <span>No Image</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><?= htmlspecialchars($pet['pet_name']); ?></td>
                                                                    <td><?php echo !empty($pet['pet_birth_date']) ? date('d/m/Y', strtotime($pet['pet_birth_date'])) : ''; ?></td>
                                                                    <td><?= htmlspecialchars($pet['pet_type']); ?></td>
                                                                    <td><?= htmlspecialchars($pet['breed_type']); ?></td>
                                                                    <td>
                                                                        <!-- Add any actions buttons here, e.g., edit, delete -->
                                                                        <button type="button" class="btn btn-sm btn-info edit-pet" data-pet-id="<?= $pet['id']; ?>">Edit</button>
                                                                        <button type="button" class="btn btn-sm btn-danger delete-pet" data-pet-id="<?= $pet['id']; ?>">Delete</button>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <tr>
                                                                <td colspan="6">No pets found.</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <?php /*?><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPetModal">
                                                Add New Pet
                                            </button><?php */?>
                                            
                                            <button type="button" class="btn btn-primary" id="addNewPetBtn">
                                                Add New Pet
                                            </button>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>


							
							<!-- Contact Details -->
							<div class="card contact-card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_contact_details']??"";?></h4>
									<div class="row form-row">
										<div class="col-md-6">
											<div class="form-group">
												<label><?php echo $language['lg_address_line_1']??"";?> <span class="text-danger">*</span></label>
												<input type="text" name="address1" id="address1" value="<?php echo libsodiumDecrypt($profile['address1']??'');?>" class="form-control addressfield"  maxlength="150" onpaste="return false;">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_address_line_2']??"";?></label>
												<input type="text" name="address2" id="address2" value="<?php echo libsodiumDecrypt($profile['address2']??"");?>" class="form-control addressfield"  maxlength="150" onpaste="return false;" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_country']??"";?> <span class="text-danger">*</span></label>
												<select class="form-control" name="country" id="country">
													<option value=""><?php echo $language['lg_select_country']??"";?></option>
												</select>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_state__province']??"";?> <span class="text-danger">*</span></label>
												<select class="form-control" name="state" id="state">
													<option value=""><?php echo $language['lg_select_state']??"";?></option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php echo $language['lg_city']??"";?> <span class="text-danger">*</span></label>
												<select class="form-control" name="city" id="city">
													<option value=""><?php echo $language['lg_select_city']??"";?></option>
												</select>
											</div>
										</div>
										<!--<div class="col-md-6">
											<div class="form-group">
												<label class="control-label"><?php //echo $language['lg_postal_code']??"";?> <span class="text-danger">*</span></label>
												<input type="text" name="postal_code" id="postal_code" value="<?php //echo ($profile['postal_code']??"");?>" class="form-control numericOnly"  maxlength="10">
											</div>
										</div>-->
									</div>
								</div>
							</div>
							<!-- /Contact Details -->
							
							
							<div class="submit-section">
								<button type="submit" id="save_btn" class="btn btn-primary submit-btn himansu"><?php echo $language['lg_save_changes']??"";?></button>
							</div>
							</form>
									<!-- /Profile Settings Form -->
									
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>		
			<!-- /Page Content -->



			<script type="text/javascript">
				var country='<?php echo $profile['country']??"";?>';
			    var state='<?php echo $profile['state']??"";?>';
			    var city='<?php echo $profile['city']??"";?>';
			    var country_code='<?php echo $profile['country_code']??"";?>';
			</script>


<?php $this->endSection(); ?>