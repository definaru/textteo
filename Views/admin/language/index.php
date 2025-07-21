<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>   
<!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
				
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-md-8">
								<h3 class="page-title">Add Language</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active">Add Language</li>
								</ul>
							</div>
						
						</div>
					</div>
					<!-- /Page Header -->
					
					<div class="row">
                <div class="col-md-12">
                	<div class="card">
                    <div class="card-body">                        

                        <form class="form-horizontal" autocomplete="off" id="add_language" action="<?=base_url('admin/language/addlanguage')?>" method="POST" enctype="multipart/form-data" >
							<div class="form-group">
								<label class="col-sm-3 control-label">Language <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input type="text" name="language" class="form-control" id="language">                             
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Language Value <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input type="text" name="language_value" class="form-control" id="language_value">                             
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">RTL or LTR  <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									 <select name="tag" id="tag" class="form-control">
                                            <option value="">--Select a Tag--</option> 
                                            <option value="rtl">RTL</option> 
                                            <option value="ltr">LTR</option> x
                                      </select>                             
								</div>
							</div>
							
							<div class="m-t-30 text-center">
								<button name="form_submit" id="language_btn" type="submit" class="btn btn-primary" value="true">Save</button>
								
							</div>
						</form>                          
                    </div>
                </div>
                </div>
			</div>




			<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table id="languages_table" class="table table-hover table-center mb-0 w-100">
											<thead>
												<tr>
													<th>#</th>
                                                    <th>Language</th>
                                                    <th>Language Value</th>
                                                    <th>Tag</th> 
                                                    <th>Action</th>
			                                        <th>Default Language</th>
													
												</tr>
											</thead>
											<tbody >
                                    <?php 
                                    if (!empty($list))

                                    {
                                    	$i=1;
									     foreach ($list as $row){      
                                         $new = '';      
                                         $status = 'Active';
                                         if($row['status']==2){
                                              $status = 'Inactive';
                                          }  

                                       ?>
							               <tr>

                                            <td> <?php echo $i++?></td>

                                            <td> <?php echo  $row['language'] ?></td>

                                            <td> <?php echo  $row['language_value'] ?></td>

                                             <td> <?php echo  $row['tag'] ?></td> 

                                             <td>
                                                <?php $status = ''; 
                                                if ($row['status'] == 1) 
                                                {  
                                                    $status = 'success'; 
                                                    $stst='Active'; 
                                                    $style='style="display:block;"';
                                                }
                                                else 
                                                {
                                                    $status = 'danger';
                                                    $stst='In Active';
                                                    $style='style="display:none;"';
                                                } 
                                               if($row['language_value']=='en'){
                                                         echo '';

                                                }else{?>

                                               <span id="lang_status<?php echo $row['id'];?>" data-status="<?php echo $row['status'];?>" style="cursor: pointer;" onclick="change_status(<?php echo $row['id'];?>)" class="label label-<?php echo $status;?>"><span id="texts<?php echo $row['id'];?>"><?php echo $stst;?></span></span>

                                               <?php  }?>
									        </td>
                                            <td><input type="radio" <?php echo $style;?> class="default_lang" value="1" <?php if($row['default_language']=='1') echo 'checked';?> name="default_language" data-id="<?php echo $row['id']; ?>" id="default_language<?php echo $row['id']; ?>"></td>

                                                </tr>

                                                <?php
                                               }
                                             } else {

                                        ?>

                                        <tr>

                                            <td colspan="6"><p class="text-danger text-center m-b-0">No Records Found</p></td>

                                        </tr>

                                    <?php } ?>

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
