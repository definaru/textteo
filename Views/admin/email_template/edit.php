<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-7 col-auto">
					<h3 class="page-title">Email Template</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>admin/dashboard">Dashboard</a></li>
						<li class="breadcrumb-item active">Email Template</li>
					</ul>
				</div>

			</div>
		</div>
		<!-- /Page Header -->
		<div class="row">
			<div class="col-md-12">
				<div class="card-box">

					<form class="form-horizontal" action="<?php

															/** @var array $edit_data */
															echo base_url('admin/template-edit/' . $edit_data['template_id']) ?>" method="POST" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-sm-3 control-label">Email Title</label>
							<div class="col-sm-9">
								<?php if ($edit_data['template_title']) echo $edit_data['template_title']; ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Email Subject</label>
							<div class="col-sm-9">
								<input type="text" name="template_subject" id="template_subject" class="form-control" placeholder="Email template Subject" required="required" value="<?php echo $edit_data['template_subject']; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Template Content</label>
							<div class="col-sm-9">
								<?php
								/**
								 * @var string $ckeditor_editor1
								 */
								if (!empty($edit_data['template_content'])) {
									echo  "<textarea class='form-control' id='ck_editor_textarea_id' rows='6' name='template_content'>" . $edit_data['template_content'] . "</textarea>";
									echo display_ckeditor($ckeditor_editor1);
								} else {
									echo "<textarea class='form-control' id='ck_editor_textarea_id' rows='6' name='template_content'> </textarea>";
									echo display_ckeditor($ckeditor_editor1);
								}
								?>
							</div>
						</div>
						<div class="m-t-30 text-center">
							<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save Changes</button>
							<a href="<?php echo base_url() . 'admin/email-template' ?>" class="btn btn-default m-l-5">Cancel</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Page Wrapper -->






</div>
<!-- /Main Wrapper -->
<?php $this->endSection(); ?>