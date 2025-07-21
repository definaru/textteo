<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-md-8">
								<h3 class="page-title">Pages</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">Pages</li>
								</ul>
							</div>
							<div class="col-md-4 text-right m-b-30">
                        <a href="<?php echo base_url().'admin/language/addPage'?>" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> Add Page</a>
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
                                        <th>Page Title</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $i = 0;
                                  if(!empty($list)){
                                  foreach ($list as $page) {
                                    $i++;
                                    ?>
                                  <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <div class="service-desc">
                                            <h2><a href="<?php echo base_url().'admin/language/pages/'.$page['page_key']; ?>"><?php echo $page['page_title']; ?></a></h2>
                                        </div>
                                    </td>
                                  </tr>
                                <?php }  	
                                  }else{}?>
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