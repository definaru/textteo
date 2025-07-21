	<!-- Breadcrumb -->
	<?php $this->extend('user/layout/header'); ?>
	<?php $this->section('content'); ?>
	<div class="breadcrumb-bar">
		<div class="container-fluid">
			<div class="row align-items-center">
				<div class="col-md-12 col-12">
					<nav aria-label="breadcrumb" class="page-breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
																							/** @var array $language  */
																							echo $language['lg_home']; ?></a></li>
							<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_payment6']; ?></li>
						</ol>
					</nav>
					<h2 class="breadcrumb-title"><?php echo $language['lg_payment6']; ?></h2>
				</div>
			</div>
		</div>
	</div>
	<!-- /Breadcrumb -->

	<!-- Page Content -->
	<div class="content success-page-cont">
		<div class="container-fluid">

			<div class="row justify-content-center">
				<div class="col-lg-6">

					<!-- Success Card -->
					<div class="card success-card">
						<div class="card-body">
							<div class="success-cont">
								<i class="fas fa-check"></i>
								<h3><?php echo $language['lg_payment_success']; ?></h3>
								<h3><?php echo $language['lg_your_transactio']; ?> <?php echo (!empty(session('trans_id'))) ? session('trans_id') : '-'; ?> </h3>
							</div>
						</div>
					</div>
					<!-- /Success Card -->

				</div>
			</div>

		</div>
	</div>
	<?php $this->endSection(); ?>