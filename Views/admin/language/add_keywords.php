<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?> 
<!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-md-8">
								<h3 class="page-title">Add Keywords</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">Add Keywords</li>
								</ul>
							</div>
						
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-body">                       

                        <form class="form-horizontal" onsubmit="// return add_keyword_validation();" action="<?php echo base_url('admin/language/add_keywords'); ?>" method="POST" enctype="multipart/form-data" id="add_keywords_form">
							<div class="form-group">
								<label class="col-sm-3 control-label">Title <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<textarea class="form-control" placeholder="(ex):Hello world|Have a nice day" name="multiple_key" id="multiple_key"></textarea>                                    <small class="error_msg help-block keyword_error_empty error" style="display: none;">Please Enter Keyword</small>
									<small class="error_msg help-block keyword_error_spc error" style="display: none;">No Special Character/Numbers Allowed</small>
								</div>
							</div>
							
							<div class="m-t-30 text-center">
								<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
								<a href="<?php echo base_url().'admin/language/keywords' ?>" class="btn btn-default m-l-5">Cancel</a>
							</div>
						</form>                          
                    </div>
                </div>
                </div>
			</div>
					
				</div>			
			</div>
			<!-- /Page Wrapper -->
		
        </div>
		<!-- /Main Wrapper -->
<?php $this->endSection(); ?>