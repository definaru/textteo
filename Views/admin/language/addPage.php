<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-md-8">
								<h3 class="page-title">Add Page</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">Add Page</li>
								</ul>
							</div>
						
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-body">                       

                        <form class="form-horizontal" onsubmit="return keyword_validation();"  method="POST" enctype="multipart/form-data" >
							<div class="form-group">
								<label class="col-sm-3 control-label">Page Name <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input  type="text" id="page_name" name="page_name" value="" class="form-control" maxlength="100">
									<small class="error_msg help-block keyword_error error" style="display: none;">Please enter a page name</small>
									<small class="error_msg help-block keyword_error_spc error" style="display: none;">No Special Character/Numbers Allowed</small>                             
								</div>
							</div>
							
							<div class="m-t-30 text-center">
								<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
								<a href="<?php echo base_url().'admin/language/pages' ?>" class="btn btn-default m-l-5">Cancel</a>
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