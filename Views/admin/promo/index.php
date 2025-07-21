<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
   <style>
      table {
         width: 100%;
         border-collapse: collapse;
         margin: 20px 0;
         font-size: 18px;
         text-align: left;
      }

      table th, table td {
         padding: 12px 15px;
         border: 1px solid #ddd;
      }

      table th {
         background-color: #f4f4f4;
         color: #333;
      }

      table tr:nth-child(even) {
         background-color: #f9f9f9;
      }

      table tr:nth-child(odd) {
         background-color: #fff;
      }

      table tr:hover {
         background-color: #f1f1f1;
      }

      table td {
         vertical-align: middle;
      }

      .active-yes {
         color: green;
         font-weight: bold;
      }

      .active-no {
         color: red;
         font-weight: bold;
      }
   </style>
<div class="page-wrapper">
   <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
         <div class="row">
            <div class="col-sm-12">
               <h3 class="page-title">Promocodes</h3>
               <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active">Promocodes</li>
               </ul>
            </div>
            <div class="col-sm-5 col">
               <a href="javascript:void(0);" onclick="add_promo()" class="btn btn-primary float-right mt-2">Add Promocode</a>
            </div>
         </div>
      </div>
      <!-- /Page Header -->
      <div class="row">
         <div class="col-md-12">
            <!-- Recent Orders -->
            <div class="card">
               <div class="card-body">
                  <table>
                     <tr>
                        <td>Title</td>
                        <td>Type</td>
                        <td>Discuont</td>
                        <td>Active</td>
                     </tr>
                     <?php
                     foreach($promocodes as $code){
                        ?>
                        <tr>
                           <td><?=$code['coupon']?></td>
                           <td><?=$code['discount_type']?></td>
                           <td><?=$code['discount']?></td>
                           <td>
                              <input onclick="activatePromo(<?=$code['id']?>)" type="checkbox" <?php if($code['active'] == 1){?> checked="checked"<?php }?> name="active_<?=$code['id']?>" >
                           </td>
                        </tr>
                        <?php
                     }
                     ?>
                  </table>
               </div>
            </div>
            <!-- /Recent Orders -->
         </div>
      </div>
   </div>
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
   <!-- Add Modal -->
   <div class="modal fade" id="user_modal" aria-hidden="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <form action="#" enctype="multipart/form-data" autocomplete="off" id="promo_form" method="post">
               <input type="hidden" id="role" name="role" value="1">
               <div class="modal-header">
                  <h5 class="modal-title">Add Promocode</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">

                  <div class="row form-row">
                     <div class="col-12 col-sm-6">
                        <div class="form-group">
                           <label>Title <span class="text-danger">*</span></label>
                           <input type="text" name="title" id="first_name" class="form-control">
                        </div>
                     </div>
                     <div class="col-12 col-sm-6">
                        <div class="form-group">
                           <label>Type <span class="text-danger">*</span></label>
                           <select name="type" class="form-control" id="country_code">
                              <option value="%">%</option>
                              <option value="amount">Amount</option>
                              <option value="free_price">Free price</option>
                           </select>

                        </div>
                     </div>
                     <div class="col-12 col-sm-12">
                        <div class="form-group">
                           <label>Value <span class="text-danger">*</span></label>
                           <input type="text" name="value" id="value" class="form-control">
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
<script>
   function add_promo() {
      $('#user_id').val('');
      $('#country_code').val('').trigger('change');
      $('#promo_form')[0].reset(); // reset form on modals
      $('#user_modal').modal('show'); // show bootstrap modal
      $('#user_modal .modal-title').text('Add Promocode'); // Set Title to Bootstrap modal title
      $('.pass').show();
   }
</script>
<?php $this->endSection(); ?>