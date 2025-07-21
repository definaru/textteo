<?php 
	$this->extend('user/layout/header'); 
	$login_image = !empty('/'.settings("login_image")) ? 
		'/'.settings("login_image") : 
		'/assets/img/login-banner.png';
?>
<?php $this->section('title'); ?>
Reset password
<?php $this->endSection(); ?>
<?php $this->section('content'); ?>
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<div class="account-content">
					<div class="row align-items-center justify-content-center">
						<div class="col-md-7 col-lg-6 login-left">
							<img 
								src="<?=$login_image;?>" 
								class="img-fluid" 
								alt="Doccure Login" 
							/> 
						</div>
						<div class="col-md-12 col-lg-6 login-right">
							<div class="login-header">
								<h3><?=$language['lg_change_password'] ?? '';?></h3>
							</div>
							<form action="#" autocomplete="off" id="change_password">
								<input type="hidden" name="id" id="id" value="<?=$id;?>">
								<div class="form-group form-focus">
									<input 
										type="password" 
										id="password" 
										name="password" 
										class="form-control floating" 
									/>
									<span class="far fa-eye" id="togglenewpassword"></span>
									<label class="focus-label"><?=$language['lg_new_password'] ?? '';?></label>
								</div>
								<div class="form-group form-focus">
									<input 
										type="password" 
										id="confirm_password" 
										name="confirm_password" 
										class="form-control floating" 
										required 
									/>
									<span class="far fa-eye" id="toggleconfirmpassword"></span>
									<label class="focus-label"><?=$language['lg_confirm_new_password'] ?? '';?></label>
								</div>
								<div id="update_pwd">
									<button type="submit" id="loading" class="btn btn-primary btn-block btn-lg login-btn">
										<?=$language['lg_confirm3'] ?? '';?>
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>		
<?php $this->endSection(); ?>

<?php $this->section('javascript'); ?>
<script>
	document.addEventListener('DOMContentLoaded', function() 
	{
		const button = document.getElementById('loading');

		button.addEventListener('click', function() {
			const originalText = button.innerHTML;
			button.innerHTML = 'Loading...';
			setTimeout(function() {
				button.innerHTML = originalText;
			}, 2000);
		});  
	});
</script>
<?php $this->endSection(); ?>