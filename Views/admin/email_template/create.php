    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
          <div class="page-header">
            <div class="row">
              <div class="col-sm-7 col-auto">
                <h3 class="page-title">Email Template</h3>
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active">Email Template</li>
                </ul>
              </div>
                
            </div>
          </div>
          <!-- /Page Header -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">                        

                        <form class="form-horizontal" action="<?php echo base_url('admin/email_template/create'); ?>" method="POST" enctype="multipart/form-data" >
							<div class="form-group">
								<label class="col-sm-3 control-label">Title</label>
								<div class="col-sm-9">
									<input type="text" name="template_title" id="template_title" class="form-control" placeholder="Email template name" required="required">                             
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email Subject</label>
								<div class="col-sm-9">
									<input type="text" name="template_subject" id="template_subject" class="form-control" placeholder="Email template Subject" required="required">                            
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Template Content</label>
								<div class="col-sm-9">
									<?php
										echo "<textarea class='form-control' id='ck_editor_textarea_id' rows='6' name='template_content'> </textarea>";
										/**
 * @var string $ckeditor_editor1
 */
										echo display_ckeditor($ckeditor_editor1);
									
									?>
								</div>
							</div>
							<div class="m-t-30 text-center">
								<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
								<a href="<?php echo base_url().'admin/email_template' ?>" class="btn btn-default m-l-5">Cancel</a>
							</div>
						</form>                          
                    </div>
                </div>
			</div>
        </div>
    </div>