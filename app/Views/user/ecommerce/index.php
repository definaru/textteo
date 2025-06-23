<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<?php view('user/home/product_navbar'); ?>

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">

		<div class="row align-items-center pb-3">
			<div class="col-xl-8 col-lg-7 col-md-7 d-none d-md-block">
				<h3 class="title pharmacy-title"><?php echo $language['lg_products']; ?></h3>
			</div>
			<div class="col-xl-4 col-lg-5 col-md-5">
				<div class="pro-search ui-widget">
					<input class="form-control" type="text" id="keywords" onkeyup="getproduct_key()">
					<button class="btn" type="button" onclick="get_products(0)"><i class="fas fa-search"></i></button>
				</div>
			</div>
		</div>

		<div class="row">

			<div class="col-md-5 col-lg-3 col-xl-3 theiaStickySidebar">

				<!-- Search Filter -->
				<div class="card search-filter">
					<div class="card-header">
						<h4 class="card-title mb-0"><?php echo $language['lg_filter']; ?></h4>
					</div>
					<div class="card-body">

						<div class="filter-widget">
							<h4><?php echo $language['lg_categories']; ?></h4>

							<input type="hidden" id="category" value="<?php
																		/** @var int $categoryId */
																		echo $categoryId; ?>" name="category">
							<input type="hidden" id="subcategory" value="<?php
																			/** @var int $subCategoryId */
																			echo $subCategoryId; ?>" name="subcategory">
							<input type="hidden" id="pharmacy_id" value="<?php
																			/** @var int $pharmacy_id */
																			echo $pharmacy_id; ?>" name="pharmacy_id">

							<?php
							//print_r($subcategory_list);
							if (!empty($subcategory_list)) {
								$i = 1;
								foreach ($subcategory_list as $crows) { ?>
									<div>
										<label class="custom_check">

											<input type="checkbox" name="subcategotyCheckbox" class="subcategotyCheckbox" value="<?php echo $crows['id']; ?>" <?php if ($crows['id'] == $subCategoryId) {
																																									echo "checked";
																																								} ?>>

											<span class="checkmark"></span> <?php echo libsodiumDecrypt($crows['subcategory_name']); ?>
										</label>
									</div>
							<?php }
							} ?>

						</div>
						<div class="btn-search">

							<button type="button" onclick="get_products('0')" class="btn btn-block">Search</button>


						</div>
					</div>
				</div>
				<!-- /Search Filter -->

			</div>

			<div class="col-md-7 col-lg-8 col-xl-9">

				<input type="hidden" name="page" id="page_no_hidden" value="1">
				<div class="spinner-border text-success text-center" role="status" id="loading"></div>

				<div class="row" id="product-list">




				</div>


				<div class="load-more text-center d-none" id="load_more_btn">
					<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more']; ?></a>
				</div>


			</div>
		</div>
	</div>
</div>

<?php $this->endSection(); ?>
<input type="hidden" name="pharmacy_id" id="pharmacy_id" value="<?php echo base64_decode(service('uri')->getsegment(2)); ?>">