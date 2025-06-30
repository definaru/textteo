<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3 class="page-title">Reviews</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
						<li class="breadcrumb-item active">Reviews</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->

		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="reviews_table" class="table table-hover table-center mb-0 w-100">
								<thead>
									<tr>
										<th>#</th>
										<th>Patient Name</th>
										<th>Doctor Name</th>
										<th>Ratings</th>
										<th>Description</th>
										<th>Date</th>
										<th class="text-right">Actions</th>
									</tr>
								</thead>
								<tbody>


								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- /Page Wrapper -->

<!-- Delete Modal -->
<div class="modal fade" id="delete_reviews" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<!--	<div class="modal-header">
							<h5 class="modal-title">Delete</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>-->
			<div class="modal-body">
				<div class="form-content p-2">
					<input type="hidden" id="reviews_id">
					<h4 class="modal-title">Delete</h4>
					<p class="mb-4">Are you sure want to delete?</p>
					<button type="button" id="change_btn" onclick="reviews_delete()" class="btn btn-primary">Yes </button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->endSection(); ?>
<!-- /Delete Modal -->