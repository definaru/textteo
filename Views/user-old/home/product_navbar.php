<!--product navbar-->
<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<section class="section shadow-sm" style="background: #fff;margin-top:15px">
	<div class="breadcrumb-bar">
		<div class="container-fluid">
			<div class="row align-items-center">
				<div class="col-md-12 col-12">
					<nav aria-label="breadcrumb" class="page-breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.html">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Products</li>
						</ol>
					</nav>
					<h2 class="breadcrumb-title search-results"></h2>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-12">
				<ul class="navbar-nav main-nav1" style="flex-direction: row;">
					<?php
					$category = getTblResultOfData('product_categories', ['status' => 1], '*', false);
					if (!empty($category)) {

						foreach ($category as $crows) {
					?>
							<li class="nav-item dropdown mr-4">
								<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><?php echo libsodiumDecrypt($crows['category_name']); ?> </a>
								<div class="dropdown-menu">
									<?php
									$subcategory = getTblResultOfData('product_subcategories', ['status' => 1, 'category' => $crows['id']], '*', false);
									if (!empty($subcategory)) {
										foreach ($subcategory as $srows) { ?>

											<a class="dropdown-item" href="<?php echo base_url() . 'sub-category/?subcategory=' . $srows['slug'] ?? ""; ?>"><?php echo libsodiumDecrypt($srows['subcategory_name']) ?? ""; ?></a>

									<?php }
									} ?>
								</div>
							</li>
					<?php }
					} ?>
				</ul>
			</div>

		</div>
	</div>
</section>
<?php $this->endSection(); ?>
<!--/product navbar-->