<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
	<div class="container-fluid">
		<div class="row align-items-center">
			<div class="col-md-12 col-12">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
																						/** @var array $language */
																						echo $language['lg_home'] ?? ''; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_terms_and_condi'] ?? ''; ?></li>
					</ol>
				</nav>
				<h2 class="breadcrumb-title"><?php echo $language['lg_terms_and_condi'] ?? ''; ?></h2>
			</div>
		</div>
	</div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
	<div class="container">

		<div class="row">
			<div class="col-12">
				<div class="terms-content">
					<?php
					if (!empty($terms_conditions['content'])) {
						echo $terms_conditions['content'];
					} else {
						echo $language['lg_no_record_found'];
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Page Content -->
<?php $this->endSection(); ?>