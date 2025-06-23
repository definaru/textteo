<!DOCTYPE html> 
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<title><?php echo !empty(settings("meta_title"))?settings("meta_title"):"Textteo";?></title>
<meta content="<?php echo !empty(settings("meta_keywords"))?settings("meta_keywords"):"";?>" name="keywords">
<meta content="<?php echo !empty(settings("meta_description"))?settings("meta_description"):"";?>" name="description">
<!--<script src="//code.tidio.co/b2iousyjzvxaltkzcdebffhihh7cyp12.js" async></script>-->
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MKRXTVR8');</script>
<!-- End Google Tag Manager -->


<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MKRXTVR8"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php 
      $timeout=!empty(settings("session_time_out"))?(int)settings("session_time_out"):150;
      $timeout_tot=$timeout*60;
?>
<META HTTP-EQUIV="refresh" CONTENT="<?=$timeout_tot?>;URL=<?php echo base_url().'user-logout';?>?timeout">
<!-- Favicons -->
<link href="<?php echo !empty(base_url().settings("favicon"))?base_url().settings("favicon"):base_url()."assets/img/favicon.png";?>" rel="icon">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
<!-- Fontawesome CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/fontawesome.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css">     

      <?php  
          // if(($module=='doctor' || $module=='clinic' || $module=='patient' || $module=='subscription' || $module=='home' || $module=='post' || $module=='calendar' || $module=='invoice' || $module=='pharmacy' || $module=='ecommerce' || $module=='lab')) 
          // {  
            if($page=='book_appoinments' || $page=='doctor_profile' || $page=='profile' || $page== 'add_product' || $page=='edit_product' || $page=='pharmacy_profile' || $page=='lab_profile' || $page=='lab_tests_preview')
            { ?> 
            <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-datepicker.min.css">
            <?php 
            } 
            if($page=='products_list_by_pharmacy' || $page=='index')
            { 
              ?>
              <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.min.css">
            <?php 
            }
            if($page=='doctor_profile' || $module=='clinic' || $page=='profile' || $page== 'lab_profile' ||$page=='pharmacy_profile' || $page=='add_product' || $page=='edit_product')
            { 
              ?>
                <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/cropper.min.css">
                <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/avatar.css">
            <?php 
            } 
            if($page=='profile' || $page=='add_post' || $page=='edit_post' || $page=='add_product' || $page=='edit_product')
            { 
              ?>
              <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css">
              <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/dropzone/dropzone.min.css">
              <?php 
            } 
          if($page=='calendar')
          { 
            ?>
            <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fullcalendar/fullcalendar.min.css">
          <?php 
          } 
          if($page=='doctor_dashboard' || $page=='patient_preview' || $page=='mypatient_preview' || $page=='patientDashboard' || $page=='index' || $page=='pending_post' || $page=='invoice' || $page=='accounts' || $page == 'pharmacy_dashboard' || $page == 'orderlist' || $page == 'product_list' || $page == 'lab_appointment_list' || $page=='lab_dashboard' || $page=='lab_tests' || $page=='lab_appoinments' || $page=='appointments' || $page=='lab_dashboard' || $module == 'pharmacy' || $module == 'doctor')
          { ?>
            <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datatables/datatables.min.css">
            <?php 
          } 
          if($page=='add_prescription' || $page=='edit_prescription' || $page=='add_billing' || $page=='edit_billing')
          { ?>
            <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/sign.css">

        <?php 
          }
      // } 
      if($module=='messages') 
      { 
      ?>

      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery.mCustomScrollbar.min.css">
      <?php 
      } 
      if($page=='blogList' || $page=='blog_details'  || ($theme=='blog' && $module=='home' && $page=='blog_details')) 
      { 
        ?>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery-confirm.min.css">
      <?php 
      } 
      ?>

      <?php
      if (($module == 'doctor' || $module == 'home' || $module == 'calendar') && ($page == 'doctor_profile' || $page == 'doctors_search' || $page == 'doctors_mapsearch' || $page == 'calendar')) 
      { 
      ?>
          <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/multiselect/dist/css/bootstrap-multiselect.css">
      <?php 
      } 
      ?>


<!-- Fancybox CSS -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fancybox/jquery.fancybox.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dropzone/dropzone.min.css">

<!-- Main CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/toastr.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="<?php //echo base_url();?>assets/js/html5shiv.min.js"></script>
<script src="<?php //echo base_url();?>assets/js/respond.min.js"></script>
<![endif]-->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<?= view('user/layout/navbar'); ?>
<?= $this->renderSection('content'); ?>
<?= view('user/layout/footer'); ?>

