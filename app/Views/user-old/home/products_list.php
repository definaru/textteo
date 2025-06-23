           <div class="breadcrumb-bar">
           	<div class="container-fluid">
           		<div class="row align-items-center">
           			<div class="col-md-12 col-12">
           				<nav aria-label="breadcrumb" class="page-breadcrumb">
           					<ol class="breadcrumb">
           						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
																									/** @var array $language  */
																									echo $language['lg_home']; ?></a></li>
           						<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_products']; ?></li>
           					</ol>
           				</nav>
           				<h2 class="breadcrumb-title search-results"></h2>
           			</div>
           		</div>
           	</div>
           </div>

           <?php view('user/home/product_navbar'); ?>

           <!-- Home Banner -->
           <section class="section">
           	<div class="container-fluid">
           		<div class="row justify-content-center">
           			<div class="col-md-8">
           				<div class="banner-wrapper-ecom">
           					<!-- Search -->
           					<div class="search-box1">
           						<h5 class="mt-3"><?php echo $language['lg_browse_medicine']; ?></h5>

           						<form>
           							<div class="form-group search-location1 postion-relative">


           								<input type="hidden" value="<?php if (isset($_GET['category']) && !empty($_GET['category'])) echo $_GET['category']; ?>" id="category" class="form-control">
           								<input type="hidden" value="<?php if (isset($_GET['subcategory']) && !empty($_GET['subcategory'])) echo $_GET['subcategory']; ?>" id="subcategory" class="form-control">

           								<input type="text" value="<?php if (isset($_POST['keywords']) && !empty($_POST['keywords'])) echo $_POST['keywords']; ?>" id="keywords" class="form-control" id="keywords" placeholder="Search..." style="padding-left:15px;">
           								<!-- <span class="form-text">Based on your Location</span> -->
           								<!-- <span class="search-detect">Detect</span> -->
           							</div>

           							<button type="button" onclick="get_products(0)" class="btn btn-primary search-btn"><i class="fas fa-search"></i> <span><?php echo $language['lg_go']; ?></span></button>

           							<button type="button" onclick="reset_products()" class="btn btn-primary search-btn"><i class="fas fa-refresh"></i> <span><?php echo $language['lg_reset']; ?></span></button>
           						</form>
           					</div>
           					<!-- /Search -->

           				</div>

           			</div>
           		</div>
           	</div>
           </section>
           <!-- /Home Banner -->
           <!-- Page Content -->
           <div class="content">
           	<div class="container-fluid">

           		<div class="row">
           			<div class="col-md-5 col-lg-3 col-xl-3 theiaStickySidebar">

           				<!-- Profile Sidebar -->
           				<div class="profile-sidebar">

           					<!-- Filter Area -->


           					<div id="accordion" class="accordion p-3">
           						<div class="card mb-0 border-0">
           							<h4 class="card-title px-3 pt-3"><?php echo $language['lg_categories']; ?></h4>

           							<?php if (!empty(product_categories())) {
											$i = 1;
											foreach (product_categories() as $crows) { ?>
           									<div class="card-header bg-white border-0 p-3 mb-0">
           										<a href="#collapse<?php echo $i; ?>" class="card-title text-dark d-inline-block mb-0 w-100 collapsed" data-toggle="collapse" aria-expanded="false">
           											<p class="d-inline font-bold text-uppercase"><?php echo $crows['category_name']; ?></p>
           										</a>
           									</div>

           									<div id="collapse<?php echo $i; ?>" class="card-body pt-0 pb-3 collapse" data-parent="#accordion" style="">
           										<?php if (!empty(product_subcategories($crows['id']))) {
														foreach (product_subcategories($crows['id']) as $srows) { ?>
           												<div class="mb-3 ml-3"><a onclick="search_subcategory('<?php echo $srows['slug']; ?>')" href="javascript:void(0);">- <?php echo $srows['subcategory_name']; ?></a></div>

           										<?php }
													} ?>
           									</div>

           							<?php $i++;
											}
										} ?>

           						</div>
           					</div>

           					<!-- /Filter Area -->

           				</div>
           				<!-- /Profile Sidebar -->

           			</div>

           			<div class="col-md-7 col-lg-8 col-xl-9">
           				<h4 class="mb-4"><?php echo $language['lg_best_sellers']; ?></h4>
           				<input type="hidden" name="page" id="page_no_hidden" value="1">
           				<div class="row" id="product-list">

           					<div class="load-more text-center d-none" id="load_more_btn">
           						<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more']; ?></a>
           					</div>

           				</div>

           			</div>
           		</div>
           	</div>
           </div>