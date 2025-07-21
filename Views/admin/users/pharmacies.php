<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?> 
<!-- Page Wrapper -->
<div class="page-wrapper">
   <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
         <div class="row">
            <div class="col-sm-7 col-auto">
               <h3 class="page-title">List of Pharmacies</h3>
               <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active">Pharmacies</li>
               </ul>
            </div>
            <div class="col-sm-5 col">
               <?php 
                  if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){
                  
                  ?>
               <a href="#user_edit_modal" data-toggle="modal" class="btn btn-primary float-right mt-2">Edit Admin Pharmacy</a>
               <?php }else{ ?>
               <a href="#user_modal" data-toggle="modal" class="btn btn-primary float-right mt-2">Add Admin Pharmacy</a>
               <?php } ?>
            </div>
         </div>
      </div>
      <!-- /Page Header -->
      <div class="row">
         <div class="col-sm-12">
            <div class="card">
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="pharmacy_table" class="table table-hover table-center mb-0 w-100">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Pharmacy Name</th>
                              <th>Email</th>
                              <th>Mobile No</th>
                              <th>Home Delivery</th>
                              <th>24 hrs open</th>
                              <th>Opens At</th>
                              <th>Member Since</th>
                              <th>Account Status</th>
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
<!-- Add Modal -->
<div class="modal fade" id="user_modal" aria-hidden="true" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document" >
      <div class="modal-content">
         <form action="#" enctype="multipart/form-data" autocomplete="off" id="register_form" method="post">
            <input type="hidden" id="role" name="role" value="5">
            <div class="modal-header">
               <h5 class="modal-title">Add Pharmacy</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="row form-row">
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Pharmacy Name <span class="text-danger">*</span></label>
                        <input type="text" value="<?php if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo $get_pharmacy_details[0]['first_name'];  } ?>" name="first_name" id="first_name" class="form-control">
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" value="<?php if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo $get_pharmacy_details[0]['last_name'];  } ?>" id="last_name" class="form-control">
                     </div>
                  </div>
                  <!--    <div class="col-12 col-sm-6">
                     <div class="form-group">
                     	<label>Pharmacy Name <span class="text-danger">*</span></label>
                     	<input type="text" name="pharmacy_name" value="<?php //if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo $get_pharmacy_details[0]['pharmacy_name'];  } ?>" id="pharmacy_name" class="form-control">
                     </div>
                     </div> -->
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="<?php if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo $get_pharmacy_details[0]['email'];  } ?>" id="email" class="form-control">
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Country Code <span class="text-danger">*</span></label>
                        <select name="country_code" class="form-control" id="country_code">
                        </select>
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Mobile No <span class="text-danger">*</span></label>
                        <input type="text" name="mobileno" value="<?php if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo $get_pharmacy_details[0]['mobileno'];  } ?>" id="mobileno" class="form-control">
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" value="" id="password" class="form-control">
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group"> 
                        <label>Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="confirm_password" value="" id="confirm_password" class="form-control">
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <input class="" type="checkbox" name="home_delivery" id="home_delivery" value="yes">
                        <label class="" for="home_delivery">
                        Home Delivery
                        </label>
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <input class="" type="checkbox" name="hrsopen" id="hrsopen" value="yes">
                        <label class="" for="hrsopen">
                        24 Hrs Open
                        </label>
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Pharmacy Opens At </label>
                        <input type="text" name="pharmacy_opens_at" id="pharmacy_opens_at" class="form-control">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
               <button type="submit" id="register_btn" class="btn btn-outline btn-success ">Submit</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- /ADD Modal-->
<!-- EDIT Modal -->
<div class="modal fade" id="user_edit_modal" aria-hidden="true" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document" >
      <!-- <form action="#" enctype="multipart/form-data" autocomplete="off" id="register_form_edit_pharam" method="post">  -->
      <div class="modal-content">
         <form action="<?php echo base_url()."admin/users/update_pharmacy"; ?>" enctype="multipart/form-data" autocomplete="off" id="register_form_edit_pharam" method="post">
            <input type="hidden" id="role_id" name="role" value="5">
            <div class="modal-header">
               <h5 class="modal-title">Edit Admin Pharmacy</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="row form-row">
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Pharmacy Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="edit_first_name" value="<?php if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo libsodiumDecrypt($get_pharmacy_details[0]['first_name']);  } ?>" class="form-control">
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="edit_last_name" value="<?php if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo libsodiumDecrypt($get_pharmacy_details[0]['last_name']);  } ?>" class="form-control">
                     </div>
                  </div>
                  <!--   <div class="col-12 col-sm-6">
                     <div class="form-group">
                     	<label>Pharmacy Name <span class="text-danger">*</span></label>
                     	<input type="text" name="pharmacy_name" id="edit_pharmacy_name" value="<?php //if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo $get_pharmacy_details[0]['pharmacy_name'];  } ?>" class="form-control">
                     </div>
                     </div> -->
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="<?php if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo libsodiumDecrypt($get_pharmacy_details[0]['email']);  } ?>" id="edit_email" class="form-control">
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Mobile No <span class="text-danger">*</span></label>
                        <input type="text" name="mobileno" value="<?php if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo libsodiumDecrypt($get_pharmacy_details[0]['mobileno']);  } ?>" id="edit_mobileno" class="form-control">
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <input type="hidden" value="<?php if(isset($get_pharmacy_details) && !empty($get_pharmacy_details)){ echo $get_pharmacy_details[0]['id'];  } ?>" name="pharmacy_id" id="edit_pharmacy_id" class="form-control">
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <input class="" type="checkbox" name="home_delivery" id="edit_home_delivery" value="yes" <?php 
                           if(!empty($specification['home_delivery'])){
                           
                           
                           	echo ($specification['home_delivery']=='yes')?'checked':''; }else{}?> >
                        <label class="" for="home_delivery">Home Delivery</label>
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <input class="" type="checkbox" name="hrsopen" id="edit_hrsopen" value="yes" <?php 
                           if(!empty($specification['24hrsopen'])){
                           	echo ($specification['24hrsopen']=='yes')?'checked':'';}else{} ?> >
                        <label class="" for="hrsopen">24 Hrs Open</label>
                     </div>
                  </div>
                  <div class="col-12 col-sm-6">
                     <div class="form-group">
                        <label>Pharmacy Opens At </label>
                        <input type="time" name="pharmacy_opens_at" id="edit_pharmacy_opens_at" value="<?php
                           if(!empty($specification['pharamcy_opens_at'])){
                           echo $specification['pharamcy_opens_at']; }else{} ?>" class="form-control">
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Close</button>
               <button type="submit" id="register_edit_btn" class="btn btn-outline btn-success ">Submit</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- /EDIT Modal-->
<!-- Delete Pharmacy -->
<!-- Modal -->
<div class="modal fade" id="pharmacy_delete_confirmation_box" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Pharmacy Delete Confirmation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            Are you sure! You want to delete this pharmacy?
            <input type="hidden" name="pharmacy_id" id="pharmacy_id">
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary phamracy_delete">Confirm</button>
         </div>
      </div>
   </div>
</div>
<?php $this->endSection(); ?>