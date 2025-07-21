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
                                     /** @var array $language  */
									echo $language['lg_dashboard'];?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_my_patients'];?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_my_patients'];?></h2>
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
							<input type="hidden" name="page" id="page_no_hidden" value="1" >
							<div class="row row-grid" id="patients-list">
							</div>

							<div class="load-more text-center d-none" id="load_more_btn">
									<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more'];?></a>	
							</div>

						</div>
					</div>

				</div>

			</div>		
<?php $this->endSection(); ?>
