<?= $this->extend('user/layout/header'); ?>
<?= $this->section('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
																						/** @var array $language */
																						echo $language['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_blog2']; ?></li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?php echo $language['lg_blog2']; ?></h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container">

		<div class="row">
			<div class="col-lg-8 col-md-12">
				<input type="hidden" name="page" id="page_no_hidden" value="1">
				<input type="hidden" id="keywords" value="<?php if (isset($_GET['keywords']) && !empty($_GET['keywords'])) echo $_GET['keywords']; ?>">
				<input type="hidden" id="category" value="<?php if (isset($_GET['category']) && !empty($_GET['category'])) echo $_GET['category']; ?>">
				<input type="hidden" id="tags" value="<?php if (isset($_GET['tags']) && !empty($_GET['tags'])) echo $_GET['tags']; ?>">
				<div class="row blog-grid-row" id="blog-list">


				</div>

				<!-- Blog Pagination -->
				<div class="row">
					<div class="load-more text-center d-none" id="load_more_btn">
						<a class="btn btn-primary btn-sm" href="javascript:void(0);"><?php echo $language['lg_load_more']; ?></a>
					</div>
				</div>
				<!-- /Blog Pagination -->

			</div>

			<!-- Blog Sidebar -->
			<div class="col-lg-4 col-md-12 sidebar-right theiaStickySidebar">

				<?= view('user/layout/blogSidebar'); ?>

			</div>
			<!-- /Blog Sidebar -->

		</div>
	</div>
</div>
<!-- /Page Content -->
<?= $this->endSection(); ?>