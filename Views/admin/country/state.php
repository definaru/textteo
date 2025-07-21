
<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>  
<div class="page-wrapper">
<div class="content container-fluid">
   <!-- Page Header -->
   <div class="page-header">
      <div class="row">
         <div class="col">
            <h3 class="page-title">State</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
               <li class="breadcrumb-item active">State Add</li>
            </ul>
         </div>
      </div>
   </div>
   <!-- /Page Header -->
   <div class="row">
      <div class="col-md-12">
         <button class="btn btn-info right" id="show_stateform">Add State</button>
         <div class="tab-content profile-tab-cont">
            <!-- Change Password Tab -->
            <div id="state_tab" class="tab-pane fade show active" style="display: none">
               <div class="card">
                  <div class="card-body">
                     <h5 class="card-title">Create State</h5>
                     <div class="row">
                        <div class="col-md-10 col-lg-6">
                           <div class="form-group">
                              <label>Country name</label>
                              <select class="form-control select" name="country" id="country">
                                 <option value="">Select country</option>
                              </select>
                           </div>
                           <div class="form-group">
                              <label>State Name</label>
                              <input type="text" class="form-control" id="state" name="state">
                           </div>
                           <button id="state_add" class="btn btn-primary" name="form_submit" value="true">Save</button>
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
         <!-- Recent Orders -->
         <div class="card">
            <div class="card-body">
               <div class="table-responsive">
                  <table id="state_table" class="table table-hover table-center w-100 mb-0">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>State Name</th>
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
         <form class="form-horizontal" autocomplete="off" id="state_edit" action="#" method="POST" enctype="multipart/form-data" >
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
                        <label>State name</label>
                        <input type="text" class="form-control" id="estate" name="estate">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
               <button type="submit" id="btnstateupdate"  class="btn btn-outline btn-success ">Submit</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div>
<!-- /Page Wrapper -->
<?php $this->endSection(); ?>