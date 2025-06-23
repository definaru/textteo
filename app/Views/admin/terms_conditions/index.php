<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
	<div class="content container-fluid">

		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3 class="page-title">Terms and Conditions</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?= base_url(); ?>">Dashboard</a></li>
						<li class="breadcrumb-item active">Terms and Conditions</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->

		<div class="row">
			<div class="col-md-12">
				<div class="card-box">
					<form id="termsConditionForm" autocomplete="off" class="form-horizontal" action="<?php echo base_url('terms-update') ?>" method="POST" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-sm-3 control-label">Language</label>
							<div class="col-sm-3">
								<select name="terms_language" class="form-control " id="terms_language">
                                    <option value="">Select Language</option>
                                    <?php
                                    if (!empty($language)) {
                                        foreach ($language as $lang) {
                                            if(!empty($terms_language) && ($lang['language_value'] == $terms_language)) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                    ?>
                                        <option <?= $selected ?> value="<?= $lang['language_value'] ?>"><?= $lang['language'] ?></option>
                                    <?php } 
                                    } ?>
                                </select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Content</label>
                            <?php if(!empty($terms_conditions)) { ?>
                                <input type="hidden" id="terms_conditions_id" name="id" value="<?= $terms_conditions['id'] ?>" >
                            <?php } ?>
							<div class="col-sm-9">
								<?php
								/**
								 * @var string $ckeditor_editor1
								 */
								if (!empty($terms_conditions['content'])) {
									echo  "<textarea class='form-control' id='ck_editor_textarea_id' rows='6' name='content'>" . $terms_conditions['content'] . "</textarea>";
									echo display_ckeditor($ckeditor_editor1);
								} else {
									echo "<textarea class='form-control' id='ck_editor_textarea_id' rows='6' name='content'> </textarea>";
									echo display_ckeditor($ckeditor_editor1);
								}
								?>
							</div>
						</div>
						<div class="m-t-30 text-center">
							<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save Changes</button>
							<a href="<?php echo base_url() . 'termsandconditions' ?>" class="btn btn-default m-l-5">Cancel</a>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- /Page Wrapper -->
<?php $this->endSection(); ?>