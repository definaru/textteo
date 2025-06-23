<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>  
<div class="page-wrapper">
   <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
         <div class="row">
            <div class="col">
               <h3 class="page-title">City</h3>
               <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active">City Add</li>
               </ul>
            </div>
         </div>
      </div>
      <!-- /Page Header -->
      <div class="row">
         <div class="col-md-12">
            <button class="btn btn-info right" id="show_cityform">Add City</button>
            <div class="tab-content profile-tab-cont">
               <!-- Change Password Tab -->
               <div id="city_tab" class="tab-pane fade show active" style="display: none;">
                  <div class="card">
                     <div class="card-body">
                        <h5 class="card-title">Create City</h5>
                        <div class="row">
                           <div class="col-md-10 col-lg-6">
                              <div class="form-group">
                                 <label>Country name</label>
                                 <select class="form-control country select" name="country" id="country">
                                    <option value="">Select country</option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label>State Name</label>
                                 <select class="form-control select" name="state" id="state">
                                    <option value=""><?php  if(empty($language['lg_select_state'])){}else{
                                       echo $language['lg_select_state'];
                                       }?></option>
                                 </select>
                              </div>
                              <div class="form-group">
                                 <label>City Name</label>
                                 <input type="text" class="form-control" id="city" name="city">
                              </div>
                              <button id="city_add" class="btn btn-primary" name="form_submit" value="true">Save</button>
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
            <div class="form-group">
               <label>Select Country</label>
               <select class="form-control select" name="country" id="country1">
                  <option value="">Select country</option>
               </select>
            </div>
            <div class="form-group">
               <label>Select State</label>
               <select class="form-control select" name="state" id="state1">
                  <option value="">Select State</option>
               </select>
            </div>
            <!-- Recent Orders -->
            <div class="card">
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="city_list" class="table table-hover table-center w-100 mb-0">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>City Name</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
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
            <form class="form-horizontal" autocomplete="off" id="city_edit" action="#" method="POST" enctype="multipart/form-data" >
               <div class="modal-header">
                  <h5 class="modal-title">Edit City</h5>
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
                           <label>City name</label>
                           <input type="text" class="form-control" id="ecity" name="ecity">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
                  <button type="submit" id="btncityupdate"  class="btn btn-outline btn-success ">Submit</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- /Page Wrapper -->
<?php $this->endSection(); ?>