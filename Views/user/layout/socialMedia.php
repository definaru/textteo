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
										
									echo $language['lg_dashboard']??"";?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_social_media']??"";?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_social_media']??"";?></h2>
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
							<?php echo view('user/layout/sidebar');?>
							<!-- /Profile Sidebar -->
							
						</div>
						
						<div class="col-md-7 col-lg-8 col-xl-9">
							<div class="card">
								<div class="card-body">
								
									<!-- Social Form -->
									<form method="post" action="#" id="doctor_social_media" autocomplete="off">                                                                                           
										<div class="row">
											<div class="col-md-12 col-lg-8">
												<div class="form-group">
													<label><?php echo $language['lg_facebook_url']??""; ?></label>
													<input type="text" class="form-control" name="facebook" value="<?php echo $social_media['facebook']??"";?>">
													<label>Ex: http://facebook.com/anypage</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-lg-8">
												<div class="form-group">
													<label><?php echo $language['lg_twitter_url']??""; ?></label>
								<input type="text" class="form-control" name="twitter" value="<?php echo $social_media['twitter']??"";?>">									
								<label>Ex: http://twitter.com/anypage</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-lg-8">
												<div class="form-group">
													<label><?php echo $language['lg_instagram_url']??""; ?></label>
													<input type="text" class="form-control" name="instagram" value="<?php echo $social_media['instagram']??"";?>">
													<label>Ex: http://instagram.com/anypage</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-lg-8">
												<div class="form-group">
													<label><?php echo $language['lg_pinterest_url']??""; ?></label>
													<input type="text" class="form-control" name="pinterest" value="<?php echo $social_media['pinterest']??"";?>"> 
													<label>Ex: http://pinterest.com/anypage</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-lg-8">
												<div class="form-group">
													<label><?php echo $language['lg_linkedin_url']??""; ?></label>
													<input type="text" class="form-control" name="linkedin" value="<?php echo $social_media['linkedin']??"";?>">
													<label>Ex: http://linkedin.com/anypage</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 col-lg-8">
												<div class="form-group">
													<label><?php echo $language['lg_youtube_url']??""; ?></label>
													<input type="text" class="form-control" name="youtube" value="<?php echo $social_media['youtube']??"";?>"> <label>Ex: http://youtube.com/anypage</label>
												</div>
											</div>
										</div>
										<div class="submit-section">
											<button type="submit" class="btn btn-primary submit-btn" id="save_btn"><?php echo $language['lg_save_changes']??""; ?></button>
										</div>
									</form>
									<!-- /Social Form -->
									
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>		
			<!-- /Page Content -->
			<script type="text/javascript">
				var country='';
			    var state='';
			    var city='';
			    var specialization='';
			    var country_code='';
			</script>
<?php $this->endSection(); ?>            