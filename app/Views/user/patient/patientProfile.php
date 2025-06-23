<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<style>
    /* Custom scroll styles */
    .scrollable-container {
        max-height: 200px; /* Adjust this value as needed */
        overflow-y: auto;
        padding-right: 15px; /* Prevent content shift from scrollbar */
    }

    /* Custom scrollbar styling */
    .scrollable-container::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .scrollable-container::-webkit-scrollbar-thumb {
        background: #FD9720;
        border-radius: 3px;
    }

    .scrollable-container::-webkit-scrollbar-thumb:hover {
        background: #ff8800;
    }

	    /* Tab Styles */
    .nav-tabs {
        /* border-bottom: 2px solid #dee2e6; */
        margin-bottom: 1.5rem;
    }

    .nav-tabs .nav-link {
        color: #252525;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        color: #FD9720;
        border-bottom: 3px solid #FD9720;
        /* background-color: transparent; */
    }

    .nav-tabs .nav-link:hover {
        color: #FD9720;
        /* border-color: transparent; */
    }
	
	@media(max-width: 768px){
		#addNewPetBtn{
			width:100%;
		}
	}

	#profile-tab,#password-tab{
		width: 100%;
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
                <?= view('user/layout/sidebar'); ?>
            </div>

            <div class="col-md-7 col-lg-8 col-xl-9">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="profileTabs" style="width:100%;background-color:#F7F7F7;border: none;" role="tablist">
                    <li class="nav-item" style="width:50%" role="presentation">
                        <button class="nav-link active" style="width:100%;background-color: #F7F7F7;" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                            Profile Settings
                        </button>
                    </li>
                    <li class="nav-item" style="width:50%" role="presentation">
                        <button class="nav-link" style="width:100%;background-color: #F7F7F7;" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab_id" type="button" role="tab" aria-controls="password-tab_id" aria-selected="false">
                            Change Password
                        </button>
                    </li>
                </ul>

                <!-- Tabs Content -->
                <div class="tab-content" id="profileTabsContent">
                    <!-- Tab 1: Profile Settings -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card">
                            <div class="card-body" style="background-color: #F7F7F7;">
                                <form method="post" action="#" id="patient_profile_form" autocomplete="off">
                                    <input type="hidden" value="<?php echo date('d/m/Y',strtotime('-1 day')); ?>" id="maxDate">
                                    <input type="hidden" value="<?php echo session('user_id'); ?>" id="user_id">
                                    <input type="hidden" id="country_id" name="country_id" value="<?php echo $profile['country_id'];?>">

                                    <?php
                                    if($profile['profileimage']=="" || ($profile['profileimage']!="" && !is_file($profile['profileimage']))) {
                                        $user_profile_image=base_url().'assets/img/user.png';
                                    } else {
                                        $user_profile_image=(!empty($profile['profileimage']??""))?base_url().$profile['profileimage']??"":base_url().'assets/img/user.png';
                                    }
                                    ?>

                                    <!-- Basic Information -->
                                    	<!-- Basic Information -->
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><?php echo $language['lg_basic_informati']??"";?></h4>
									<div class="row form-row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="change-avatar" style="background-color:#F7F7F7; padding:2%; border-radius:8px;">
													<div class="profile-img">
														<img src="<?php echo $user_profile_image;?>" style="width:50px;height:50px;" alt="User Image">
													</div>
													<div class="upload-img">
														<div class="avatar-view-btn" style="color: #FD9720;font-size: 16px;font-weight: 500;">
															<span><img src="<?php echo base_url().'/icons/gallery.svg' ?>"> <?php echo $language['lg_upload_photo']??"";?></span>
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
                                      

                                    <!-- Pets Section -->
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5>Pets</h5>
                                                     <?php if (!empty($user_pets)) : ?>
                    <div class="container scrollable-container">
                        <div class="row g-0"><!-- 'g-0' removes gutters between cols -->
						<?php foreach ($user_pets as $index => $pet) : ?>
							<div class="col-md-6" style="margin-bottom:2%">
								<div class="card h-100 border-0 rounded-0">
									<div class="card-body d-flex align-items-center p-2" style="background-color:#F7F7F7; border-radius:8px">
										<div class="row w-100">
											<div class="col-md-9 d-flex" style="width:80%">
												<div class="me-3">
													<?php if (!empty($pet['pet_photo'])) : ?>
														<img src="<?= base_url('uploads/pet_images/' . $pet['pet_photo']); ?>" class="img-fluid" width="50" height="50" alt="Pet Image">
													<?php else : ?>
														<span>No Image</span>
													<?php endif; ?>
												</div>
												<div class="pet-info" style="margin-left: 2%;">
													<div style="font-weight:500;font-size:16px;font-family:Poppins;color:#252525">
														<?= htmlspecialchars($pet['pet_name']); ?>, <?= !empty($pet['pet_age']) ? $pet['pet_age'] : ''; ?>
													</div>
													<div style="font-weight:500;font-size:11px;font-family:Poppins;color:#757575">
														<?= htmlspecialchars($pet['pet_type']); ?>, <?= htmlspecialchars($pet['breed_type']); ?>
													</div>
												</div>
											</div>
											<div class="col-md-3 d-flex align-items-center justify-content-end" style="width:20%">
												<div class="d-flex gap-2">
													<button type="button" class="btn btn-sm btn-default edit-pet" data-pet-id="<?= $pet['id']; ?>">
														<i class="fas fa-edit" style="color:#545454"></i>
													</button>
													<button type="button" class="btn btn-sm btn-default delete-pet" data-pet-id="<?= $pet['id']; ?>">
														<i class="fas fa-trash-alt" style="color:#FF4444"></i>
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
                    </div>
                <?php else : ?>
                    <p style="text-align: center;">No pets found.</p>
                <?php endif; ?>
                                                    <div class="row mt-3" style="text-align: center;">
                                                        <div class="col">
                                                            <button type="button" class="btn btn-primary" id="addNewPetBtn">
                                                                Add New Pet
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Details -->
                                    <div class="card contact-card">
                                        <div class="card-body">
                                            <h4 class="card-title"><?php echo $language['lg_contact_details']??"";?></h4>
                                            <div class="row form-row">
                                               
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
							
                                            </div>
                                        </div>
                                    </div>

                                    <div class="submit-section" style="text-align: center;width: 100%;">
                                        <button type="submit" style="width: 100%;" id="save_btn" class="btn btn-primary submit-btn himansu">
                                            <?php echo $language['lg_save_changes']??"";?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Change Password -->
                    <div class="tab-pane fade" id="password-tab_id" role="tabpanel" aria-labelledby="password-tab">
                           <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12 col-xl-12">
                                            <form method="post" action="#" autocomplete="off" id="change_password">
                                                <div class="form-group" <?php if(!empty(session('redirect_activate'))){?>style="display: none;"<?php }?>>
                                                    <label><?php echo $language['lg_current_passwor']??" ";?> <span class="text-danger">*</span></label>
                                                    <input type="password" value="!Q1w2e3zaxscd" name="currentpassword" id="currentpassword" class="form-control">
                                                    <span class="far fa-eye" id="togglecurrentpassword"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo $language['lg_new_password']??" ";?> <span class="text-danger">*</span></label>
                                                    <input type="password" name="password" id="password" class="form-control">
                                                    <span class="far fa-eye" id="togglenewpassword"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo $language['lg_confirm_passwor']??" ";?> <span class="text-danger">*</span></label>
                                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                                                    <span class="far fa-eye" id="toggleconfirmpassword"></span>
                                                </div>
                                                <div class="submit-section" style="width: 100%;">
                                                    <button type="submit" style="width: 100%;" id="change_password_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_save_changes']??" ";?></button>
                                                </div>
                                            </form>
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
			                

					<!-- end -->
				</div>

			</div>		
			<!-- /Page Content -->

			<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js"></script>


			<script type="text/javascript">
				var country='<?php echo $profile['country']??"";?>';
			    var state='<?php echo $profile['state']??"";?>';
			    var city='<?php echo $profile['city']??"";?>';
			    var country_code='<?php echo $profile['country_code']??"";?>';
			</script>


<?php $this->endSection(); ?>