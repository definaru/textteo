
<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>  
<div class="page-wrapper">
   <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
         <div class="row">
            <div class="col">
               <h3 class="page-title">Country</h3>
               <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active">Country Add</li>
               </ul>
            </div>
         </div>
      </div>
      <!-- /Page Header -->
      <div class="row">
         <div class="col-md-12">
            <button class="btn btn-info right" id="show_countryform">Add Country</button>
            <div class="tab-content profile-tab-cont">
               <!-- Change Password Tab -->
               <div id="password_tab" class="tab-pane fade show active" style="display: none;">
                  <div class="card">
                     <div class="card-body">
                        <h5 class="card-title">Create Country</h5>
                        <div class="row">
                           <div class="col-md-10 col-lg-4">
                              <form class="form-horizontal" autocomplete="off" id="country_add" action="<?=base_url('admin/country-add')?>" method="POST" enctype="multipart/form-data" >
                                 <div class="form-group">
                                    <label>Sortname</label>
                                    <input type="text" class="form-control" id="sortname" name="sortname">
                                 </div>
                                 <div class="form-group">
                                    <label>Country name</label>
                                    <input type="text" class="form-control" id="country" name="country">
                                 </div>
                                 <div class="form-group">
                                    <label>Phone Code</label>
                                    <input type="text" class="form-control" id="phone_code" name="phone_code">
                                 </div>
                                 <button id="countryadd" class="btn btn-primary" name="form_submit" type="submit"value="true">Save</button>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- /Change Password Tab -->
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <!-- Recent Orders -->
            <div class="card">
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="country_table" class="table table-hover table-center w-100 mb-0">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Sort Name</th>
                              <th>Country</th>
                              <th>Phone code</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              if(empty($list)){}else{
                              
                              $cnt=0; foreach ($list as $row) { $cnt++;?>
                           <tr>
                              <td><?php echo $cnt; ?></td>
                              <td><?php echo $row['sortname']; ?></td>
                              <td><?php echo $row['country']; ?></td>
                              <td><?php echo $row['phonecode']; ?></td>
                              <td>
                                 <div class="actions">
                                    <?php
                                       $role_info=session('role_details');
                                       
                                       ?>
                                    <?php if(isset($role_info['countryconfig_edit'])){
                                       if($role_info['countryconfig_edit']== 1){ ?>					
                                    <a class="btn btn-sm bg-success-light" onclick="edit_country(<?php echo $row['countryid']; ?>)" href="javascript:void(0)">
                                    <i class="fe fe-pencil"></i> Edit
                                    </a> <?php }}?>
                                    <?php
                                       $role_info=session('role_details');
                                       
                                       ?>
                                    <?php if(isset($role_info['countryconfig_delete'])){
                                       if($role_info['countryconfig_delete']== 1){ ?>
                                    <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_country(<?php echo $row['countryid']; ?>)">
                                    <i class="fe fe-trash"></i> Delete
                                    </a>
                                    <?php }}?>
                                 </div>
                              </td>
                           </tr>
                           <?php } }?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <!-- /Recent Orders -->
         </div>
      </div>
   </div>
   <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
         <div class="modal-content">
            <form class="form-horizontal" autocomplete="off" id="country_edit" action="#" method="POST" enctype="multipart/form-data" >
               <div class="modal-header">
                  <h5 class="modal-title">Edit Country</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <input type="hidden" value="" name="id"/> 
                  <input type="hidden" value="" name="method"/>
                  <div class="row">
                     <div class="col-md-10">
                        <div class="form-group">
                           <label>Sortname</label>
                           <input type="text" class="form-control" id="esortname" name="esortname">
                        </div>
                        <div class="form-group">
                           <label>Country name</label>
                           <input type="text" class="form-control" id="ecountry" name="ecountry">
                        </div>
                        <div class="form-group">
                           <label>Phone Code</label>
                           <input type="text" class="form-control" id="ephonecode" name="ephonecode">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
                  <button type="submit" id="btncountryupdate"  class="btn btn-outline btn-success ">Submit</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- /Page Wrapper -->
<?php $this->endSection(); ?>