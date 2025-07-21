<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
     <title><?php echo !empty(settings("meta_title"))?settings("meta_title"):"Doccure";?></title>
    <meta content="<?php echo !empty(settings("meta_keywords"))?settings("meta_keywords"):"";?>" name="keywords">
    <meta content="<?php echo !empty(settings("meta_description"))?settings("meta_description"):"";?>" name="description">
        <!-- Favicons -->
    <link href="<?php echo !empty(base_url().settings("favicon"))?base_url().settings("favicon"):base_url()."assets/img/favicon.png";?>" rel="icon">
    
    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/all.min.css">
    
    <!-- Feathericon CSS -->
     <link rel="stylesheet" href="<?php echo base_url();?>assets/css/feathericon.min.css">
		
		<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/morris/morris.css">
 <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">

     <?php 
     /** @var string $module  */  
     if($module=='appointments' || $module=='users' || $module=='specialization' || $module=='reviews'||$module=='email_template' ||  $module=='categories' ||  $module=='subcategories' ||  $module=='post' || $module=='language' ||  $module=='payment_requests' || $module=='country' || $module=='pharmacy' || $module =='unit' || $module == 'subscription' || $module=='lab_tests') { ?>

    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datatables/datatables.min.css">

  <?php } if($module=='profile' || $module=='pharmacy') { ?>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/cropper.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/avatar.css">

  <?php } if($module=='post' || $module=='pharmacy') { ?>
  
   <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css">
<?php } ?>
     <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/toastr.css"> -->
     <link rel="stylesheet" href="<?php echo base_url();?>assets/toastr_2.1.3/toastr.css">
     <link rel="stylesheet" href="<?php echo base_url();?>assets/css/admin.css">
    
    <!--[if lt IE 9]>
      <script src="<?php echo base_url();?>assets/js/html5shiv.min.js"></script>
      <script src="<?php echo base_url();?>assets/js/respond.min.js"></script>
    <![endif]-->
    </head>

    <?= view('admin/includes/navbar'); ?>
    <?= view('admin/includes/sidebar'); ?>
    <?= $this->renderSection('content'); ?>
    <?= view('admin/includes/footer'); ?>