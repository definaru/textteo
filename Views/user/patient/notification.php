<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><?php
																						/** @var array $language */
																						echo $language['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo 'Notification'; ?></li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?php echo 'Notification'; ?></h2>
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
				<div class="row">

					<div class="col-md-12">
						<input type="hidden" name="page" id="page_no_hidden" value="1">

						<ul class="noti-dropdown notification-view">
							<li>
								<div class="noti-wrapper" id="notification-list">
								</div>
							</li>
							<li>
								<div class="spinner-border text-success text-center" role="status" id="loading"></div>
								<div class="load-more text-center d-none" id="load_more_btn">
									<a class="btn btn-primary btn-sm" href="javascript:void(0);">Load More</a>
								</div>

							</li>
						</ul>
					</div>

				</div>

			</div>

		</div>
	</div>

</div>
<!-- /Page Content -->
<?php $this->endSection(); ?>