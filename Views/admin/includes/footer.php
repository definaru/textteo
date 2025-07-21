<?php
echo view('user/modules/language_scripts/scripts');
?>
   <script type="text/javascript">
           var base_url='<?php echo base_url();?>';
           var modules='<?php 
           /** @var string $module  */  
           echo $module;
           ?>';
           var pages='<?php
            /** @var string $page  */  
           echo $page;
           ?>';
           var theme='<?php 
            /** @var string $theme  */
           echo $theme;
           ?>';
       </script>

      

   <!-- jQuery -->

     <?php 
     
     if($page=='add_post' || $page=='edit_post' || $page=='products') { ?>
        <script src="<?php echo base_url();?>assets/js/jquery2.js"></script>
    <?php }  else { ?>
       <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
    <?php }  ?>
    <!-- Bootstrap Core JS -->
        <script src="<?php echo base_url();?>assets/js/popper.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/select2/js/select2.min.js"></script>
    <!-- Slimscroll JS -->
      <script src="<?php echo base_url();?>assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
     <?php if($module=='appointments' || $module=='users' || $module=='specialization' || $module=='reviews'||$module=='email_template' || $module=='categories' ||  $module=='subcategories'  ||  $module=='post' || $module=='language' ||  $module=='payment_requests' || $module=='country' || $module == 'unit' || $module == 'dashboard' || $module == 'pharmacy' || $module == 'product' || $module == 'subscription' || $module=='lab_tests') { ?>

    <script src="<?php echo base_url();?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/datatables/datatables.min.js"></script>

  <?php } 

    
  if($module=='profile' || $module=='users' || $module=='terms_conditions' || $module=='privacy_policy' || $page=='add_post' ||  $page=='edit_post' || $module=='language' || $module=='country' || $module=='categories') { ?>
  <script src="<?php echo base_url();?>assets/js/jquery.validate.js" type="text/javascript"></script>
  <script src="<?php echo base_url();?>assets/js/jquery.password-validation.js" type="text/javascript"></script>

    <script type="text/javascript" src="<?php echo base_url();?>assets/js/cropper_profile.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/cropper.min.js"></script>
  <?php } ?>
  
  <?php if($module=='pharmacy'){ ?>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_cropper_image.js"></script>
  <?php }
		 if( $module=='dashboard'){ ?>
		<script src="<?php echo base_url();?>assets/plugins/raphael/raphael.min.js"></script>    
		<script src="<?php echo base_url();?>assets/plugins/morris/morris.min.js"></script>  
		<script src="<?php echo base_url();?>assets/js/chart.morris.js"></script>
  <?php } ?>
		
		
    <!-- Custom JS -->
    <script  src="<?php echo base_url();?>assets/js/admin2.js"></script>
    
   
     <!-- <script src="<?php echo base_url();?>assets/js/toastr.js"></script> -->
     <script src="<?php echo base_url();?>assets/toastr_2.1.3/toastr.min.js"></script>
     <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jstz-1.0.7.min.js"></script>
     <script src="<?php echo base_url();?>assets/js/admin.js?v=0.00003"></script>

     <?php 
        //echo $theme; exit;
     if($theme=='blog' || $theme=='admin') {  if($module=='post' || $module=='pharmacy') { if($page=='add_post' || $page=='edit_post' || $page=='products') { ?>
       <script src="<?php echo base_url();?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js"></script>
       <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.cropit.js"></script>
     <?php } } ?>
     <?php 
     if($theme=='blog') {  if($module=='post' || $module=='pharmacy') { if($page=='add_post' || $page=='edit_post') { ?>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/cropper_image.js"></script> 
       <?php } } 


      ?>

       <script src="<?php echo base_url();?>assets/js/blog.js"></script>
     <?php } }?>
    <?php 
/**
 * @property object $session
 */
    if(session()->getFlashdata('error_message')) {  ?>
             <script>
               toastr.error('<?php 
           echo session()->getFlashdata('error_message');?>');
            </script>
        <?php session()->remove('error_message');
        } if(session()->getFlashdata('success_message')) {  ?>

            <script>
               toastr.success('<?php echo session()->getFlashdata('success_message');?>');
            </script>
            
      <?php session()->remove('success_message'); } ?>

</body>

</html>