<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-md-8">
								<h3 class="page-title">Language Keywords</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">Language Keywords</li>
								</ul>
							</div>
							<div class="col-md-4 text-right m-b-30">
                        <a href="<?php echo base_url().'admin/language/add_keywords'?>" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> Add Keyword</a>
                    </div>
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table id="language_table" class="table table-hover table-center mb-0 w-100">
											<thead>
												<tr>
													<th>#</th>
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