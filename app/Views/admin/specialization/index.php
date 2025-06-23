<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>


<!-- /Main Wrapper -->
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-7 col-auto">
					<h3 class="page-title">Specialization</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>admin-dashboard">Dashboard</a></li>
						<li class="breadcrumb-item active">Specialization</li>
					</ul>
				</div>
				<div class="col-sm-5 col">
					<a href="javascript:void(0);" onclick="add_specialization()" class="btn btn-primary float-right mt-2">Add</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="specializationtable" class="table table-hover table-center mb-0 w-100">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Specialization</th>
										<th>Actions</th>
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



<div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<form action="#" enctype="multipart/form-data" autocomplete="off" id="specialization_form" method="post">
				<div class="modal-header">
					<h5 class="modal-title">Add Specialization</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">

					<input type="hidden" value="" name="id" />
					<input type="hidden" value="" name="method" />
					<input type="hidden" value="" id="specialization_img" />

					<div class="form-group">
						<label for="specialization" class="control-label mb-10">Specialization <span class="text-danger">*</span></label>
						<input type="text" parsley-trigger="change" id="specialization" name="specialization" maxlength="25" class="form-control">
					</div>
					<div class="form-group">
						<label for="specialization_image" class="control-label mb-10">Specialization Image <span class="text-danger">*</span></label>
						<input type="file" id="specialization_image" name="specialization_image" class="form-control" accept="image/png, image/gif, image/jpeg">
						<div class="specialization-img" id="specialization_images"></div>
						<label for="specialization_image_type" class="control-label mb-10">Please Upload JPEG, GIF, PNG Image Only.</label>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
					<button type="submit" id="btnspecializationsave" class="btn btn-primary ">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>




</div>
<!-- /Main Wrapper -->
<?php $this->endSection(); ?>