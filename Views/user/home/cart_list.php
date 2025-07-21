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
																						echo $language['lg_home'] ?? ""; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_cart3'] ?? ""; ?></li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?php echo $language['lg_cart3'] ?? ""; ?></h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container">



		<div class="card card-table">
			<div class="card-body">
				<div class="table-responsive">
					<div class="spinner-border text-success text-center" role="status" id="loading"></div>

					<table id="cart_list_table" class="table table-hover table-center mb-0">
						<thead>
							<tr>
								<th><?php echo $language['lg_product_image'] ?? ""; ?></th>
								<th><?php echo $language['lg_product_name'] ?? ""; ?></th>
								<th><?php echo $language['lg_value'] ?? ""; ?></th>
								<th><?php echo $language['lg_qty'] ?? ""; ?></th>
								<th><?php echo $language['lg_amount'] ?? ""; ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody class="cart_lists">
						</tbody>
						<tbody class="checkout_cart_lists">

						</tbody>
					</table>
				</div>
			</div>
		</div>

	</div>
</div>
<?php $this->endSection(); ?>
<!-- /Page Content -->