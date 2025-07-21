<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<?php $uri = new \CodeIgniter\HTTP\URI(current_url(true));

$name = $uri->getSegment(4); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-md-8">
								<h3 class="page-title">App Keywords</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">App Keywords</li>
								</ul>
							</div>
							<div class="col-md-4 text-right m-b-30">
                        <a href="<?php echo base_url().'admin/language/addAppKeywords/'.$name?>" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> Add Keyword</a><?php /** @phpstan-ignore-line */ ?>
                    </div>
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">
									 <form action="<?php echo base_url().'admin/language/updateAppLanguage';?>" onsubmit="update_multi_lang();" method="post" id="form_id">
									 <input type="hidden" id="page_key" name="page_key" value="<?php echo $name;?>"><?php /** @phpstan-ignore-line */ ?>
									<div class="table-responsive">
										<table id="app_language_table" class="table table-hover table-center mb-0 w-100">
											<thead>
												<tr>
													
													<?php
			                                        if (!empty($active_language))
			                                        {
			                                            foreach ($active_language as $row)
			                                            {  
			                                                ?>
			                                                <th><?php echo ucfirst($row['language'])?></th>
			                                                <?php
			                                            }
			                                        }
			                                        ?>
													
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
									 <div class="m-t-30 text-center">
                            <button name="form_submit"  type="submit" class="btn btn-primary center-block" value="true">Save</button>
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