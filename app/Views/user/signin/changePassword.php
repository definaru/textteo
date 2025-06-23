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
									echo $language['lg_dashboard']??" ";?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_change_password']??" ";?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_change_password']??" ";?></h2>
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
									<div class="row">
										<div class="col-md-12 col-lg-6">
										
											<!-- Change Password Form -->
											<form method="post" action="#" autocomplete="off" id="change_password">
												<div class="form-group" <?php if(!empty(session('redirect_activate'))){?>style="display: none;" <?php }?>>
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
												<div class="submit-section">
													<button type="submit" id="change_password_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_save_changes']??" ";?></button>
												</div>
											</form>
											<!-- /Change Password Form -->
											
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