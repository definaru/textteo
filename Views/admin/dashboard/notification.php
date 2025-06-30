<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3 class="page-title">Notifications</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
						<li class="breadcrumb-item active">Notificationss</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->

		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="row">
					<div class="col-md-12">
						<input type="hidden" name="page" id="page_no_hidden" value="1">

						<ul class="noti-dropdown notification-view">
							<?php

							$notification_list = notification_list();
							$count = count($notification_list);
							?>
							<li>
								<?php
								/** @var int $count  */
								if ($count > 0) { ?>
									<div class="notification-header">
										<a href="javascript:void(0)" class="clear-notifications" onclick="delete_notification(0)"> Delete All </a>
										<div class="clearfix"></div>
									</div>
								<?php } ?>
							</li>
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
<!-- /Page Wrapper -->
<?php $this->endSection(); ?>