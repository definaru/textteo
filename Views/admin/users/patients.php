<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?> <!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-7 col-auto">
					<h3 class="page-title">List of Patients</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>admin/dashboard">Dashboard</a></li>
						<li class="breadcrumb-item active">Patient</li>
					</ul>
				</div>
				<div class="col-sm-5 col">
					<!-- <a href="#user_modal" data-toggle="modal" class="btn btn-primary float-right mt-2">Add Patients</a> -->
					<a href="javascript:void(0);" onclick="add_patient()" class="btn btn-primary float-right mt-2">Add Patients</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->

		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="patients_table" class="table table-hover table-center mb-0 w-100">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Id</th>
										<th>Patient Name</th>
										<th>Age</th>
										<th>Blood Group</th>
										<th>Email</th>
										<th>Mobile No</th>
										<th>Member Since</th>
										<th>Account Status</th>
										<th>Last Visit</th>
										<th>Paid</th>

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

</div>
<!-- /Main Wrapper -->


<!-- Add Modal -->
<div class="modal fade" id="user_modal" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form action="#" enctype="multipart/form-data" autocomplete="off" id="register_form" method="post">
				<input type="hidden" id="role" name="role" value="2">
				<div class="modal-header">
					<h5 class="modal-title">Add Patients</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div class="row form-row">
						<div class="col-12 col-sm-6">
							<div class="form-group">
								<label>First Name <span class="text-danger">*</span></label>
								<input type="text" name="first_name" id="first_name" class="form-control">
							</div>
						</div>
						<div class="col-12 col-sm-6">
							<div class="form-group">
								<label>Last Name <span class="text-danger">*</span></label>
								<input type="text" name="last_name" id="last_name" class="form-control">
							</div>
						</div>
						<div class="col-12 col-sm-12">
							<div class="form-group">
								<label>Email <span class="text-danger">*</span></label>
								<input type="email" name="email" id="email" class="form-control">
							</div>
						</div>
						<div class="col-12 col-sm-6">
							<div class="form-group">
								<label>Country Code <span class="text-danger">*</span></label>
								<select name="country_code" class="form-control" id="country_code">
								</select>
							</div>
						</div>
						<div class="col-12 col-sm-6">
							<div class="form-group">
								<label>Mobile No <span class="text-danger">*</span></label>
								<input type="text" name="mobileno" id="mobileno" class="form-control">
							</div>
						</div>
						<div class="col-12 col-sm-6">
							<div class="form-group">
								<label>Password <span class="text-danger">*</span></label>
								<input type="password" name="password" id="password" class="form-control">
							</div>
						</div>
						<div class="col-12 col-sm-6">
							<div class="form-group">
								<label>Confirm Password <span class="text-danger">*</span></label>
								<input type="password" name="confirm_password" id="confirm_password" class="form-control">
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
					<button type="submit" id="register_btn" class="btn btn-outline btn-success ">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- /ADD Modal-->
<?php $this->endSection(); ?>