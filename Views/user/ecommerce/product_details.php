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
																						echo $language['lg_home']??""; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_product_descrip']??""; ?></li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?php echo $language['lg_product_descrip']??""; ?></h2>
			</div>
		</div>
	</div>
</div>

<!-- Page Content -->
<div class="content">
	<div class="container-fluid">

		<div class="row">

			<div class="col-md-7 col-lg-9 col-xl-9">
				<!-- Doctor Widget -->
				<div class="card">
					<div class="card-body product-description">
						<div class="doctor-widget">
							<div class="doc-info-left">

								<?php
								/** @var array $products  */
								$img = $products['upload_image_url'];

								$image_url = explode(',', $products['upload_image_url']);

								?>

								<div class="doctor-img1">
									<img src="<?php echo base_url() . $image_url[0]; ?>" class="img-fluid" alt="User Image">
								</div>
								<div class="doc-info-cont">
									<h4 class="doc-name mb-2"><?php echo libsodiumDecrypt($products['name']); ?></h4>
									<p><span class="text-muted">Manufactured By </span> <?php echo libsodiumDecrypt($products['manufactured_by']); ?> </p>
									<p><?php echo libsodiumDecrypt($products['short_description']); ?></p>

								</div>
							</div>

						</div>

					</div>
				</div>
				<!-- /Doctor Widget -->

				<!-- Doctor Details Tab -->
				<div class="card">
					<div class="card-body pt-0">

						<!-- Tab Menu -->
						<h3 class="pt-4"><?php echo $language['lg_product_details']??""; ?></h3>
						<hr>
						<!-- /Tab Menu -->

						<!-- Tab Content -->
						<div class="tab-content pt-3">

							<!-- Overview Content -->
							<div role="tabpanel" id="doc_overview" class="tab-pane fade show active">
								<div class="row">
									<div class="col-md-9">

										<!-- About Details -->
										<div class="widget about-widget">
											<h4 class="widget-title"><?php echo $language['lg_description']??""; ?></h4>
											<p>

												<?php echo libsodiumDecrypt($products['description']); ?>

											</p>
										</div>
										<!-- /About Details -->
									</div>
								</div>
							</div>
							<!-- /Overview Content -->

						</div>
					</div>
				</div>
				<!-- /Doctor Details Tab -->

			</div>

			<div class="col-md-5 col-lg-3 col-xl-3 theiaStickySidebar">

				<!-- Right Details -->
				<div class="card search-filter">
					<div class="card-body">
						<div class="clini-infos mt-0">
							<?php
							/** @var float $price */
							/** @var float $sale_price */
							/** @var string $user_currency_sign */
							if ($sale_price != $price) { ?>

								<h4><strike><?php echo $user_currency_sign; ?> <?php echo number_format($sale_price, 2, '.', ''); ?></strike> <b class="text-lg strike"><?php echo $user_currency_sign; ?> <?php echo number_format($price, 2, '.', ''); ?></b></h4>

							<?php } else { ?>

								<h4><b class="text-lg strike"><?php echo $user_currency_sign; ?> <?php echo number_format($price, 2, '.', ''); ?></b></h4>

							<?php } ?>

							<!-- <h2>GFr <?php echo $products['sale_price']; ?> <strike class="text-small">GFr <?php echo $products['price']; ?></strike>  <span class="text-small text-success"><b><?php echo !empty($products['discount']) ? $products['discount'] . ' off' : ''; ?></b></span></h2>
													</div>  -->


						</div>
						<span class="badge badge-primary"><?php echo $language['lg_in_stock']??""; ?></span>
						<div class="custom-increment pt-4">
							<div class="input-group1">


								<span class="input-group-prepend float-left" style="cursor: pointer;" id="decrease" onclick="decreaseValue()" value="Decrease Value">
									<span class="input-group-text"><b>&#8212;</b></span>
								</span>
								<input type="number" id="cart_qty" value="1" class="form-control">
								<span class="input-group-append float-right" id="increase" onclick="increaseValue()" value="Increase Value" style="cursor: pointer;">
									<span class="input-group-text"><b>&#43;</b></span>
								</span>


								<div class="md-23 quantity-blk number">

								</div>
							</div>
							<div class="clinic-details mt-4">
								<div class="clinic-booking" style="clear: both;margin-top: 20px;display: inline-block;width: 100%;">

									<a class="apt-btn" onclick="add_cart('<?php echo md5($products['id']); ?>')" href="javascript:void(0);"><?php echo $language['lg_add_to_cart']??""; ?></a>

								</div>
							</div>
							<div class="card flex-fill mt-4 mb-0">
								<ul class="list-group list-group-flush">
									<li class="list-group-item">SKU<span class="float-right">201902-0057</span></li>
									<li class="list-group-item"><?php echo $language['lg_unit_count']??""; ?> <span class="float-right"><?php echo libsodiumDecrypt($products['unit_value']) . libsodiumDecrypt($products['unit_name']); ?> </span></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="card search-filter">
						<div class="card-body">
							<div class="card flex-fill mt-0 mb-0">
								<ul class="list-group list-group-flush benifits-col">
									<li class="list-group-item d-flex align-items-center">
										<div>
											<i class="fas fa-shipping-fast"></i>
										</div>
										<div>
											<?php echo $language['lg_free_shipping']??""; ?><br><span class="text-sm"><?php echo $language['lg_for_orders_from']??""; ?> <?php echo $user_currency_sign; ?>50</span>
										</div>
									</li>
									<li class="list-group-item d-flex align-items-center">
										<div>
											<i class="far fa-question-circle"></i>
										</div>
										<div>
											<?php echo $language['lg_support_247']??""; ?><br><span class="text-sm"><?php echo $language['lg_call_us_anytime']??""; ?></span>
										</div>
									</li>
									<li class="list-group-item d-flex align-items-center">
										<div>
											<i class="fas fa-hands"></i>
										</div>
										<div>
											<?php echo $language['lg_100_safety']??""; ?><br><span class="text-sm"><?php echo $language['lg_only_secure_pay']??""; ?></span>
										</div>
									</li>
									<li class="list-group-item d-flex align-items-center">
										<div>
											<i class="fas fa-tag"></i>
										</div>
										<div>
											<?php echo $language['lg_hot_offers']??""; ?><br><span class="text-sm"><?php echo $language['lg_discounts_up_to']??""; ?></span>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Right Details -->
				</div>
			</div>
		</div>
	</div>
	<!-- /Page Content -->
	<?php $this->endSection(); ?>
	<!-- /Page Content -->