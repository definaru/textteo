<?php 
	use App\Services\Profile;
	$user = user_detail(session('user_id'));
	$this->extend('user/layout/header'); 
	$user_profile_image = Profile::avatar($user);
	$breadcrumb_title = $language['lg_profile_setting'] ?? '';
?>
<?php $this->section('title');?>
<?=$breadcrumb_title;?>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="breadcrumb-bar">
	<div class="container mt-2 mt-md-5 pt-5">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="<?=base_url().session('module');?>" class="text-decoration-none">
								<?=$language['lg_dashboard'] ?? '';?>
							</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							<?=$breadcrumb_title;?>
						</li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title">
					<?=$breadcrumb_title;?>
				</h2>
			</div>
		</div>
	</div>
</div>

<div class="content">
    <div class="container">
        <div class="row">
            <?=$user ? view('user/layout/sidebar') : '';?>
            <div class="col-12 <?=$user ? 'col-md-9' : 'col-md-12';?>">
				<div class="profile-sidebar">

					<div class="card border-0 bg-light m-0">
						<div class="card-body pb-0">
							<ul class="nav nav-tabs w-100 border-0 nav-fill" id="profileTabs" role="tablist">
								<li class="nav-item">
									<button 
										id="profile-tab" 
										class="nav-link fw-semibold active" 
										data-bs-toggle="tab" 
										data-bs-target="#profile" 
										type="button" 
										role="tab" 
										aria-controls="profile" 
										aria-selected="true"
									>
										Profile Settings
									</button>
								</li>
								<li class="nav-item">
									<button 
										id="password-tab" 
										class="nav-link fw-semibold" 
										data-bs-toggle="tab" 
										data-bs-target="#password-tab_id" 
										type="button" 
										role="tab" 
										aria-controls="password-tab_id" 
										aria-selected="false"
									>
										Change Password
									</button>
								</li>
							</ul>							
						</div>
					</div>


					<div class="tab-content pt-0" id="profileTabsContent">
						<div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							<div class="border-0 bg-light">
								<div class="card-body">
									<form method="post" action="#" id="patient_profile_form" autocomplete="off">
										<input type="hidden" value="<?=date('d/m/Y',strtotime('-1 day')); ?>" id="maxDate">
										<input type="hidden" value="<?=session('user_id'); ?>" id="user_id">
										<input type="hidden" id="country_id" name="country_id" value="<?=$profile['country_id'];?>">

										<div class="card border-0 pt-0">
											<div class="card-body p-3">
												<div class="row g-3 py-2">
													<div class="col-12">
														<h4 class="card-title">
															<?=$language['lg_basic_informati']??"";?>
														</h4>
													</div>
													<div class="col-12">
														<div class="change-avatar bg-light" style="padding:2%; border-radius:8px;">
															<div class="profile-img">
																<img src="<?=$user_profile_image;?>" style="width:50px;height:50px;" alt="User Image">
															</div>
															<div class="upload-img">
																<div class="avatar-view-btn fw-medium text-warning" style="font-size: 16px;">
																	<span>
																		<img src="/icons/gallery.svg" /> 
																		<?=$language['lg_upload_photo'] ?? '';?>
																	</span>
																	<input type="hidden" id="crop_prof_img" name="profile_image" />
																</div>
																<small class="form-text text-muted"><?=$language['lg_allowed_jpg_gif'] ?? '';?></small>
															</div>
														</div>
													</div>
													<div class="col-12 col-md-6">
														<label for="first_name" class="form-label form-required-label"><?=$language['lg_first_name']??"";?></label>
														<input 
															type="text" 
															name="first_name" 
															id="first_name" 
															value="<?=libsodiumDecrypt($profile['first_name'])??"";?>" 
															class="form-control"  
															maxlength="100" 
														/>
													</div>
													<div class="col-12 col-md-6">
														<label for="last_name" class="form-label form-required-label"><?=$language['lg_last_name']??"";?></label>
														<input 
															type="text" 
															name="last_name" 
															id="last_name" 
															value="<?=libsodiumDecrypt($profile['last_name'])??"";?>" 
															class="form-control"  
															maxlength="100"
														/>
													</div>
													<div class="col-12 col-md-6">
														<label for="country_code" class="form-label form-required-label"><?=$language['lg_country_code'] ?? ''; ?></label>
														<select name="country_code" class="form-control" id="country_code">
															<option value=""><?=$language['lg_select_country'] ?? '';?></option>
														</select>
													</div>
													<div class="col-12 col-md-6">
														<label for="mobileno" class="form-label form-required-label"><?=$language['lg_mobile_number']??"";?></label>
														<input 
															type="text" 
															id="mobileno" 
															name="mobileno" 
															value="<?=libsodiumDecrypt($profile['mobileno']) ?? '';?>" 
															class="form-control mobileNoOnly"  
															maxlength="15" 
														/>
													</div>
													<div class="col-12 col-md-6">
														<label for="gender" class="form-label form-required-label"><?=$language['lg_select_gender'] ?? '';?></label>
														<select class="form-select" name="gender" id="gender">
															<option value="" disabled><?=$language['lg_select'] ?? '';?></option>
															<option value="Male" <?=$profile['gender']=='Male' ? 'selected' : '';?>><?=$language['lg_male'] ?? '';?></option>
															<option value="Female" <?=$profile['gender']=='Female' ? 'selected' : '';?>><?=$language['lg_female'] ?? '';?></option>
														</select>
													</div>
												</div>
											</div>
										</div>

										<div class="card border-0 pt-0">
											<div class="card-body p-3">
												<div class="row g-3">
													<div class="col-12">
														<h4 class="card-title">Pets</h4>
													</div>
													<?=view_cell('CardPets::block', ['card' => $user_pets]);?>												
													<div class="col-12">
														<button type="button" class="btn btn-warning text-uppercase fw-semibold" id="addNewPetBtn">
															Add Pet
														</button>															
													</div>
												</div>
											</div>
										</div>
									
										<div class="card border-0 pt-0">
											<div class="card-body p-3">
												<div class="row g-3 py-2">
													<div class="col-12">
														<h4 class="card-title"><?=$language['lg_contact_details']??"";?></h4>
													</div>
													<div class="col-md-6">
														<label for="address1" class="form-label form-required-label">
															<?=$language['lg_address_line_1']??"";?>
														</label>
														<input 
															id="address1" 
															type="text" 
															name="address1" 
															value="<?=libsodiumDecrypt($profile['address1'] ?? '');?>" 
															class="form-control addressfield"  
															maxlength="150" 
															onpaste="return false;" 
															placeholder="Enter Address"
														/>
													</div>
													<div class="col-md-6">
														<label for="address2" class="form-label">
															<?=$language['lg_address_line_2'] ?? '';?>
														</label>
														<input 
															id="address2" 
															type="text" 
															name="address2" 
															value="<?=libsodiumDecrypt($profile['address2'] ?? '');?>" 
															class="form-control addressfield"  
															maxlength="150" 
															onpaste="return false;" 
															placeholder="Enter Address"
														/>
													</div>
													<div class="col-md-6">
														<label class="form-label form-required-label"><?=$language['lg_country']??"";?></label>
														<select class="form-select" name="country" id="country">
															<option value=""><?=$language['lg_select_country']??"";?></option>
														</select>
													</div>
													<div class="col-md-6">
														<label class="form-label form-required-label"><?=$language['lg_state__province']??"";?></label>
														<select class="form-select" name="state" id="state">
															<option value=""><?=$language['lg_select_state']??"";?></option>
														</select>
													</div>
													<div class="col-md-6">
														<label class="form-label form-required-label"><?=$language['lg_city']??"";?></label>
														<select class="form-select" name="city" id="city">
															<option value=""><?=$language['lg_select_city']??"";?></option>
														</select>
													</div>
												</div>
											</div>
										</div>

										<div class="vstack">
											<button type="submit" id="save_btn" class="btn btn-warning btn-lg text-uppercase fw-semibold mt-4">
												<?=$language['lg_save_changes'] ?? '';?>
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<!-- Tab 2: Change Password -->
						<div class="tab-pane fade" id="password-tab_id" role="tabpanel" aria-labelledby="password-tab">
							<div class="border-0 bg-light">
								<div class="card-body">
									<div class="card border-0 pt-0">
										<div class="card-body p-3">
											<form method="post" action="#" class="row g-3" autocomplete="off" id="change_password">
												<div class="col-12">
													<h4 class="card-title">Change password</h4>
												</div>
												<div class="col-12 col-md-6">
													<label for="currentpassword" class="form-label form-required-label">
														<?=$language['lg_current_passwor'] ?? ' ';?>
													</label>
													<div class="position-relative">
														<input 
															type="password" 
															value="!Q1w2e3zaxscd" 
															name="currentpassword" 
															id="currentpassword" 
															class="form-control"
															placeholder="Enter Old Password"
														/>
														<span class="far fa-eye" id="togglecurrentpassword" role="button"></span>														
													</div>
												</div>
												<div class="col-12 col-md-6">
													<label for="password" class="form-label form-required-label">
														<?=$language['lg_new_password'] ?? ' ';?> 
													</label>
													<div class="position-relative">
														<input 
															type="password" 
															name="password" 
															id="password" 
															class="form-control"
															placeholder="Enter New Password" 
														/>
														<span class="far fa-eye" id="togglenewpassword" role="button"></span>														
													</div>
												</div>
												<div class="col-12 col-md-6">
													<label for="confirm_password" class="form-label form-required-label">
														<?=$language['lg_confirm_passwor'] ?? ' ';?>
													</label>
													<div class="position-relative">
														<input 
															type="password" 
															name="confirm_password" 
															id="confirm_password" 
															class="form-control"
															placeholder="Enter New Password" 
														/>
														<span class="far fa-eye" id="toggleconfirmpassword" role="button"></span>														
													</div>
												</div>
												<div class="col-12">
													<div class="vstack mt-3">
														<button type="submit" id="change_password_btn" class="btn btn-warning btn-lg text-uppercase fw-semibold">
															<?=$language['lg_save_changes'] ?? ' ';?>
														</button>
													</div>
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
</div>
<?php $this->endSection(); ?>

<?php $this->section('javascript'); ?>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	var country = '<?=$profile['country'] ?? '';?>';
	var state = '<?=$profile['state'] ?? '';?>';
	var city = '<?=$profile['city'] ?? '';?>';
	var country_code = '<?=$profile['country_code'] ?? '';?>';
</script>
<?php $this->endSection(); ?>