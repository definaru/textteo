<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<!-- Page Content -->
<div class="content">
				<div class="container-fluid">
					
					<div class="row">
						<div class="col-md-8 offset-md-2">
							
							<!-- Account Content -->
							<div class="account-content">
								<div class="row align-items-center justify-content-center">
									<div class="col-md-7 col-lg-6 login-left">
										
						                    <img src="<?php echo !empty(base_url().settings("login_image"))?base_url().settings("login_image"):base_url()."assets/img/login-banner.png";?>" class="img-fluid" alt="Doccure Login"> 
						                 
									</div>
									<div class="col-md-12 col-lg-6 login-right">
										<div class="login-header">
											<h3><?php 
											/** @var array $language */
											echo $language['lg_change_password']??"";?></h3>
											
										</div>
										
										<!-- Forgot Password Form -->
										<form action="#" autocomplete="off" id="change_password">
											<input type="hidden" name="id" id="id" value="<?php echo $id;?>">
											<div class="form-group form-focus">
												<input type="password" id="password" name="password" class="form-control floating"  >
												<span class="far fa-eye" id="togglenewpassword"></span>
												<label class="focus-label"><?php echo $language['lg_new_password']??"";?></label>
											</div>
											<div class="form-group form-focus">
												<input type="password" id="confirm_password" name="confirm_password" class="form-control floating" required >
												<span class="far fa-eye" id="toggleconfirmpassword"></span>
												<label class="focus-label"><?php echo $language['lg_confirm_new_password']??"";?></label>
											</div>
											
											<button id="update_pwd" class="btn btn-primary btn-block btn-lg login-btn" type="submit"><?php echo $language['lg_confirm3']??"";?></button>
										</form>
										<!-- /Forgot Password Form -->
										
									</div>
								</div>
							</div>
							<!-- /Account Content -->
							
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->

			
<?php $this->endSection(); ?>