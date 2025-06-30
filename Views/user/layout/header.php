<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?=!empty(settings("meta_title")) ? settings("meta_title") : "Textteo";?></title>
    <meta content="<?=!empty(settings("meta_keywords")) ? settings("meta_keywords") : '';?>" name="keywords">
    <meta content="<?=!empty(settings("meta_description")) ? settings("meta_description") : '';?>" name="description">
    <!--<script src="//code.tidio.co/b2iousyjzvxaltkzcdebffhihh7cyp12.js" async></script>-->
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MKRXTVR8');</script>
    <!-- End Google Tag Manager -->


    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MKRXTVR8" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php 
        $timeout=!empty(settings("session_time_out"))?(int)settings("session_time_out"):150;
        $timeout_tot=$timeout*60;
    ?>
    <meta http-equiv="refresh" content="<?=$timeout_tot?>;URL=<?php echo base_url().'user-logout';?>?timeout">
    <!-- Favicons -->
    <link href="/uploads/logo/1716626275_8357ec0a82c3394e6aa6.png" rel="icon">

    <link rel="stylesheet" href="/assets/css/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="/assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">     

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
          <link rel="stylesheet" type="text/css" href="/assets/multiselect/dist/css/bootstrap-multiselect.css">
      <?php 
      } 
      ?>


    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="/assets/plugins/fancybox/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/assets/plugins/dropzone/dropzone.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="/assets/css/toastr.css">
    <link rel="stylesheet" href="/assets/css/media.min.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="/styles.css" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php //echo base_url();?>assets/js/html5shiv.min.js"></script>
    <script src="<?php //echo base_url();?>assets/js/respond.min.js"></script>
    <![endif]-->
    <style>
    .slot-container {
        width: 100%;
        max-width: 350px;
        text-align: center;
        font-family: 'Poppins', sans-serif;
        background-color: #FFFFFF;
        border-radius: 20px;
    }

    .slots-grid {
        display: flex;
        flex-wrap: nowrap; /* Keeps all slots in one line, add scroll if overflow */
        overflow-x: auto;   /* Allows scrolling if they overflow the container */
        gap: 8px;
        justify-content: flex-start;
    }

    .slot {
        padding: 2px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 400;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #252525;
        background-color: #FFFFFF;
        white-space: nowrap; /* Prevent slot text from wrapping */
    }

    .slot:hover {
        border-color: #FD9720;
        color: #FD9720;
    }

    .see-more {
        font-weight: 400;
        color: #252525;
        cursor: pointer;
        background-color: #FFFFFF;
        padding: 2px;
        border-radius: 8px;
    }

    .no-slots{
        font-weight: 400;
        color: #252525;
        cursor: pointer;
        background-color: #FFFFFF;
        padding: 2px;
        border-radius: 8px;
    }
    .popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
        z-index: 999;
        overflow: auto; /* enable scrolling if content overflows */
        padding: 20px;
    }
    .popup-content {
        background: #fff;
        max-height: 80vh; /* limits popup height */
        overflow-y: auto; /* makes only the popup scrollable */
        width: 100%;
        max-width: 600px; /* optional width limit */
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    .close-btn {
        margin-top: 20px;
        background: #FD9720;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        cursor: pointer;
    }

    .breadcrumb-title{
        font-weight: 500;
        color:#252525;
        font-size: 24px;
        font-family: 'Poppins';
    }
    .doc-info-left {
        display: flex;
        align-items: center; /* Optional: vertically center align the image and text */
        gap: 0px; /* Optional: spacing between image and text */
    }

    .doc-info-cont {
      margin-left: 0 !important; /* Remove the manual negative margin */
    }

    .doctor-img{
        margin-top: -6%;
    }
</style>
</head>
    <?=$this->include('layout/header');?>
    <?=$this->renderSection('content');?>    
    <?=$this->include('layout/footer');?>

    <!--modal Section---->
<script type="text/javascript">
    var country = '';
    var country_code = '';
    var state = '';
    var city = '';
    var citys = '<?=isset($_GET['location']) ? $_GET['location'] : '';?>';
    var specialization = '';
</script>
<script src="/assets/js/jquery.min.js"></script>
<?php
if ($module == 'doctor' || $module == 'clinic' || $module == 'patient' || $module == 'pharmacy' || $module == 'lab'  || $module == 'schedule') {
  if ($page == 'appoinments' || $page == 'doctor_dashboard' || $page == 'lab_appoinments') {
?>
    <div class="modal fade" id="view_docs" aria-hidden="true" role="dialog">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <!--  <div class="modal-header">
          <h5 class="modal-title">Delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>-->
          <div class="modal-body">
            <div class="form-content p-2">
              <input type="hidden" id="user_id">
              <h4 class="modal-title">Lab Results</h4>
              <div>
              </div>
              <div class="modal-body">
                <div class="form-content p-2">
                  <ul id="links" style="list-style-type: none;">

                  </ul>

                </div>
              </div>


              <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="appoinments_details">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo ((isset($language['lg_appointment_det'])) ? $language['lg_appointment_det'] : ""); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="info-details">
              <li>
                <div class="details-header">
                  <div class="row">
                    <div class="col-md-8">
                      <span class="title"><?php echo ((isset($language['lg_appointment_dat'])) ? $language['lg_appointment_dat'] : ""); ?></span>
                      <span class="text app_date"></span>
                    </div>
                    <div class="col-md-6">
                      <div class="text-right">
                        <!--  <button type="button" class="btn bg-success-light btn-sm" id="topup_status">Completed</button> -->
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li>
                <span class="title"><?php echo ((isset($language['lg_appoinment_type'])) ? $language['lg_appoinment_type'] : ""); ?></span>
                <span class="text type"></span>
              </li>
              <li>
                <span class="title"><?php echo ((isset($language['lg_confirm_date'])) ? $language['lg_confirm_date'] : ""); ?></span>
                <span class="text book_date"></span>
              </li>
              <li>
                <span class="title"><?php echo ((isset($language['lg_paid_amount'])) ? $language['lg_paid_amount'] : ""); ?></span>
                <span class="text amount"></span>
              </li>

            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="assign_doctor">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo ((isset($language['lg_assign_doctor'])) ? $language['lg_assign_doctor'] : ""); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="info-details">


              <?php if (session('role') == 6) { ?>
                <li>

                  <span class="text ">
                    <input type="hidden" id="app_id_assign" class="app_id" value="">
                    <input type="hidden" id="doctors_id_assign_date" value="">
                    <select name="assign_doc" id="assign_doc" onchange="assign_doc()" class="form-control">
                      <option>Select Veterinarian</option>
                    </select>
                    <p class="text-danger" id="assign_doc_err"></p>
                  </span>
                </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="appoinments_status_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="app-modal-title"><?php echo ((isset($language['lg_accept'])) ? $language['lg_accept'] : ""); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="<?php echo base_url('appt-change-status') ?>">
            <input type="hidden" id="appoinments_id" name="appoinments_id">
            <input type="hidden" id="appoinments_status" name="appoinments_status">

            <div class="modal-body">
              <p><?php echo ((isset($language['lg_are_you_sure_wa1'])) ? $language['lg_are_you_sure_wa1'] : ""); ?> <span id="app-modal-title"></span> <?php echo ((isset($language['lg_this_appoinment'])) ? $language['lg_this_appoinment'] : ""); ?></p>
            </div>
            <div class="modal-footer">
              <button type="submit" id="change_btn" class="btn btn-primary"><?php echo ((isset($language['lg_yes'])) ? $language['lg_yes'] : ""); ?></button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo ((isset($language['lg_no6'])) ? $language['lg_no6'] : ""); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade custom-modal" id="appoinments_status_complete_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="app-modal-title"><?php echo ((isset($language['lg_complete'])) ? $language['lg_complete'] : ""); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="<?php echo base_url('appoinments/change_complete_status') ?>">
            <input type="hidden" id="complete_appoinments_id" name="complete_appoinments_id">

            <div class="modal-body">
              <p><?php echo ((isset($language['lg_are_you_sure_wa1'])) ? $language['lg_are_you_sure_wa1'] : ""); ?> <span id="app-complete-modal-title"></span> <?php echo ((isset($language['lg_this_appoinment'])) ? $language['lg_this_appoinment'] : ""); ?></p>
            </div>
            <div class="modal-footer">
              <button type="submit" id="change_complete_btn" class="btn btn-primary"><?php echo ((isset($language['lg_yes'])) ? $language['lg_yes'] : ""); ?></button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo ((isset($language['lg_cancel'])) ? $language['lg_cancel'] : ""); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php }
  if ($page == 'checkout') {
  ?>

    <!-- Forgot Password Modal -->
    <div class="modal fade show" id="forgot_password_modal" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3><?php echo ((isset($language['lg_forgot_password'])) ? $language['lg_forgot_password'] : ""); ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form id="reset_password" method="post" autocomplete="off">
            <div class="modal-body">
              <p><?php echo ((isset($language['lg_enter_your_emai'])) ? $language['lg_enter_your_emai'] : ""); ?></p>
              <div class="form-group form-focus">
                <input type="email" name="resetemail" id="resetemail" class="form-control floating">
                <label class="focus-label"><?php echo ((isset($language['lg_email'])) ? $language['lg_email'] : ""); ?></label>
              </div>
              <div class="text-right">
                <a class="forgot-link" href="javascript:;" onclick="login()"><?php echo ((isset($language['lg_remember_your_p'])) ? $language['lg_remember_your_p'] : ""); ?></a>
              </div>
              <div class="modal-footer">
                <button id="reset_pwd" class="btn btn-primary btn-block btn-lg login-btn" type="submit"><?php echo ((isset($language['lg_reset_password'])) ? $language['lg_reset_password'] : ""); ?></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Forgot Password Modal -->

    <!-- Login Modal -->
    <div class="modal fade show" id="login_modal" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo ((isset($language['lg_login'])) ? $language['lg_login'] : ""); ?> <span><?php echo !empty(settings("meta_title")) ? settings("meta_title") : "Doccure"; ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form id="signin_form" method="post">
            <div class="modal-body">
              <div class="form-group form-focus">
                <input type="text" name="email" id="login_email" class="form-control floating">
                <label class="focus-label"><?php echo ((isset($language['lg_email_or_mobile'])) ? $language['lg_email_or_mobile'] : "") ?></label>
              </div>
              <div class="form-group form-focus">
                <input type="password" name="password" id="password" class="form-control floating">
                <label class="focus-label"><?php echo ((isset($language['lg_password'])) ? $language['lg_password'] : ""); ?></label>
              </div>
              <div class="text-right">
                <a class="forgot-link" href="javascript:;" onclick="forgot_password()"><?php echo ((isset($language['lg_forgot_password'])) ? $language['lg_forgot_password'] : ""); ?></a>
              </div>
              <div class="modal-footer d-block pl-0 pr-0">

                <button class="btn btn-primary btn-block btn-lg login-btn" id="signin_btn" type="submit"><?php echo ((isset($language['lg_signin'])) ? $language['lg_signin'] : ""); ?></button>
                <div class="row w-100" style="margin-top: 10px;margin-bottom: 10px;">
                  <div class="col-md-6">
                    <button class="btn btn-social btn-google" type="button" id="googlecheckoutsigninbtn" style="width: 100%;"><i class="fab fa-google float-left"></i><?php echo ((isset($language['lg_signin'])) ? $language['lg_signin'] : ""); ?></button>
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-social btn-facebook" type="button" onclick="fbcheckoutsignup()" style="width: 100%;"><i class="fab fa-facebook-f float-left"></i><?php echo ((isset($language['lg_signin'])) ? $language['lg_signin'] : ""); ?></button>
                  </div>
                </div>
                <div class="text-center dont-have"><?php echo ((isset($language['lg_dont_have_an_ac'])) ? $language['lg_dont_have_an_ac'] : ""); ?> <a href="javascript:;" onclick="register()"><?php echo ((isset($language['lg_register'])) ? $language['lg_register'] : ""); ?></a></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Login Modal -->

    <!-- Register Modal -->
    <div class="modal fade show" id="register_modal" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo ((isset($language['lg_patient4'])) ? $language['lg_patient4'] : ""); ?> <?php echo ((isset($language['lg_register'])) ? $language['lg_register'] : ""); ?></h3>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <form method="post" id="register_form" autocomplete="off">
            <div class="modal-body">
              <input type="hidden" id="role" name="role" value="2">
              <div class="form-group form-focus">
                <input type="text" name="first_name" id="first_name" class="form-control floating">
                <label class="focus-label"><?php echo ((isset($language['lg_first_name'])) ? $language['lg_first_name'] : ""); ?></label>
              </div>
              <div class="form-group form-focus">
                <input type="text" name="last_name" id="last_name" class="form-control floating">
                <label class="focus-label"><?php echo ((isset($language['lg_last_name'])) ? $language['lg_last_name'] : ""); ?></label>
              </div>
              <div class="form-group form-focus">
                <input type="email" name="email" id="register_email" class="form-control floating">
                <label class="focus-label"><?php echo ((isset($language['lg_email'])) ? $language['lg_email'] : ""); ?></label>
              </div>
              <!-- <input type="hidden" id="country_code" name="country_code" value="972"> -->
              <div class="row form-group form-focus">
                <div class="col-md-6">
                  <select name="country_code" class="form-control" id="country_code" style="padding-top:5px;">
                  </select>
                  <!-- <input type="email" name="email" id="register_email" class="form-control floating"> -->
                  <!-- <label class="focus-label" style="left:30px;"><?php echo ((isset($language['lg_email'])) ? $language['lg_email'] : ""); ?></label> -->
                </div>
                <div class="col-md-6">
                  <input type="text" name="mobileno" id="mobileno" class="form-control floating">
                  <label class="focus-label" style="left:30px;"><?php echo ((isset($language['lg_mobile_number'])) ? $language['lg_mobile_number'] : "") ?></label>
                </div>
              </div>
              <?php if (settings('tiwilio_option') == '1') { ?>
                <div class="text-right otp_load">
                  <a class="forgot-link" href="javascript:void(0);" id="sendotp"><?php echo ((isset($language['lg_send_otp'])) ? $language['lg_send_otp'] : "") ?></a>
                </div>
                <div class="form-group form-focus OTP">
                  <input type="text" name="otpno" id="otpno" class="form-control floating">
                  <label class="focus-label"><?php echo ((isset($language['lg_otp'])) ? $language['lg_otp'] : "") ?></label>
                </div>
              <?php } ?>
              <div class="row form-group form-focus">
                <div class="col-md-6">
                  <input type="password" name="password" id="register_password" class="form-control floating">
                  <label class="focus-label" style="left:30px;"><?php echo ((isset($language['lg_password'])) ? $language['lg_password'] : ""); ?></label>
                </div>
                <div class="col-md-6">
                  <input type="password" name="confirm_password" id="register_confirm_password" class="form-control floating">
                  <label class="focus-label" style="left:30px;"><?php echo ((isset($language['lg_confirm_passwor'])) ? $language['lg_confirm_passwor'] : ""); ?></label>
                </div>
              </div>
              <div class="text-left check_ctrl">
                <div class="text-right">
                  <a class="forgot-link" href="javascript:;" onclick="login()" style="color: #008FF8 "><?php echo ((isset($language['lg_already_have_an'])) ? $language['lg_already_have_an'] : ""); ?></a>
                </div>
              </div>
            </div>
            <div class="modal-footer d-block">
              <button class="btn btn-primary btn-block btn-lg login-btn" id="register_btn" type="submit"><?php echo ((isset($language['lg_signup'])) ? $language['lg_signup'] : ""); ?> </button>
              <div class="row w-100" style="margin-top: 10px;margin-bottom: 10px;">
                <div class="col-md-6">
                  <button class="btn btn-social btn-google" type="button" id="googlecheckoutsignupbtn" style="width: 100%;"><i class="fab fa-google float-left"></i><?php echo ((isset($language['lg_signup'])) ? $language['lg_signup'] : ""); ?></button>
                </div>
                <div class="col-md-6">
                  <button class="btn btn-social btn-facebook" type="button" onclick="fbcheckoutsignin()" style="width: 100%;"><i class="fab fa-facebook-f float-left"></i><?php echo ((isset($language['lg_signup'])) ? $language['lg_signup'] : ""); ?></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Register Modal -->

  <?php }

  if ($page == 'doctor_profile' || $page == 'profile' || $page == 'pharmacy_profile' || $page == 'lab_profile') {
  ?>

    <div class="modal fade custom-modal" id="avatar-modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close profile_image_popup_close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title"><i><?php echo ((isset($language['lg_profile_image'])) ? $language['lg_profile_image'] : ""); ?></i></h4>
          </div>
          <?php
          // $curprofileimage = (!empty($profile['profileimage']))?(($profile['profileimage'])):''; 
          if ($profile['profileimage'] == "" || ($profile['profileimage'] != "" && !file_exists($profile['profileimage']))) {
            $curprofileimage = base_url() . 'assets/img/user.png';
          } else {
            $curprofileimage = (!empty($profile['profileimage'] ?? "")) ? base_url() . $profile['profileimage'] ?? "" : base_url() . 'assets/img/user.png';
          }
          ?>
          <form class="avatar-form" action="<?php echo base_url('update-profile-image') ?>" enctype="multipart/form-data" method="post">
            <div class="modal-body">
              <div class="avatar-body">
                <!-- Upload image and data -->
                <div class="avatar-upload">
                  <input name="prev_img" type="hidden" value="<?php echo $curprofileimage; ?>">
                  <input class="avatar-src" name="avatar_src" type="hidden">
                  <input class="avatar-data" name="avatar_data" type="hidden">
                  <label for="avatarInput"><?php echo ((isset($language['lg_select_image'])) ? $language['lg_select_image'] : ""); ?></label>
                  <input class="avatar-input" id="avatarInput" name="avatar_file" type="file" required accept="image/png, image/gif, image/jpeg">
                  <span id="image_upload_error" class="error" style="display:none;"> <?php echo ((isset($language['lg_please_upload_i'])) ? $language['lg_please_upload_i'] : ""); ?> </span>
                  <span id="image_upload_size_error" class="error" style="display:none;"> </span>
                </div>
                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="row avatar-btns">
                <div class="col-md-12">
                  <button class="btn btn-success avatar-save" type="submit"><?php echo ((isset($language['lg_save'])) ? $language['lg_save'] : ""); ?></button>
                  <button type="button" class="btn btn-secondary submit-btn profile_image_popup_close" data-dismiss="modal"><?php echo ((isset($language['lg_cancel'])) ? $language['lg_cancel'] : ""); ?></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php }
  if ($page == 'scheduleTime') {
    echo "asdads"; ?>

    <!-- Add Time Slot Modal -->
    <div class="modal fade custom-modal" id="time_slot_modal">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <input type="hidden" id="day_id" value="1">
          <input type="hidden" name="slot" id="slot" >
          <input type="hidden" name="day_name" id="day_name" value="Sunday">
          <input type="hidden" name="id_value" id="id_value" value="">
          <input type="hidden" id="slot_count" value="1">
          <div class="slotdetails"></div>
        </div>
      </div>
    </div>
    <!-- /Add Time Slot Modal -->

  <?php } ?>

  <?php
  if ($page == "doctorList" || $page == 'mypatient_preview' || $page == 'patient_dashboard') {
  ?>
    <!-- Delete modal-->

    <div class="modal fade custom-modal" id="delete_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><?php echo ((isset($language['lg_delete'])) ? $language['lg_delete'] : ""); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <input type="hidden" id="delete_id">
          <input type="hidden" id="delete_table">
          <div class="modal-body">
            <p><?php echo ((isset($language['lg_are_you_sure_wa'])) ? $language['lg_are_you_sure_wa'] : ""); ?> <span id="delete_title"></span> ?</p>
          </div>
          <div class="modal-footer">
            <button type="button" id="delete_btn" onclick="delete_details()" class="btn btn-primary"><?php echo ((isset($language['lg_yes'])) ? $language['lg_yes'] : ""); ?></button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo ((isset($language['lg_no6'])) ? $language['lg_no6'] : ""); ?></button>
          </div>
        </div>
      </div>
    </div>
  <?php
  }
  ?>
  <?php if ($page == 'mypatient_preview' || $page == 'patient_dashboard' || $page == "add_doctor") { ?>



    <!-- View Prescription -->
    <div class="modal fade custom-modal" id="view_modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="width: 90%">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php //echo ((isset($language['lg_view1'])) ? $language['lg_view1'] : ""); ?> <span class="view_title"></span></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <label><?php //echo ((isset($language['lg_date1'])) ? $language['lg_date1'] : ""); ?> : <span id="view_date"></span></label><br>
            <label><?php //echo ((isset($language['lg_patient_name'])) ? $language['lg_patient_name'] : ""); ?> : <span id="patient_name"></span></label>

            <div class="view_details"></div>
          </div>
          <div class="clearfix"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo ((isset($language['lg_close1'])) ? $language['lg_close1'] : ""); ?></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Medical Records Modal -->
    <div class="modal fade custom-modal" id="add_medical_records">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Add <?php echo ((isset($language['lg_medical_records'])) ? $language['lg_medical_records'] : ""); ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="medical_records_form" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="medical_record_id" id="medical_record_id" value="">
              <input type="hidden" name="patient_id" id="patient_id" value="<?php
                                                                            /** @var int $patient_id */
                                                                            echo $patient_id; ?>">

              <div class="form-group">
                <label><?php echo ((isset($language['lg_description__op'])) ? $language['lg_description__op'] : ""); ?></label>
                <textarea class="form-control" name="description" id="description" rows="5"></textarea>
              </div>
              <div class="form-group">
                <label><?php echo ((isset($language['lg_upload_file'])) ? $language['lg_upload_file'] : ""); ?>[Allowed Types: jpeg/jpg/png/docx/xlsx/pdf Only]</label>
                <input class="form-control" type="file" name="user_file" id="user_files_mr">
                <a href="" id="show_med_rec_url" style="display:none;" target="_blank">Click to view previous medical record</a>
              </div>

              <div class="submit-section text-center">
                <button type="submit" id="medical_btn" class="btn btn-primary submit-btn"><?php echo ((isset($language['lg_submit'])) ? $language['lg_submit'] : ""); ?></button>
                <button type="button" class="btn btn-secondary submit-btn" data-dismiss="modal"><?php echo ((isset($language['lg_cancel'])) ? $language['lg_cancel'] : ""); ?></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Add Medical Records Modal -->

    <!-- Edit Medical Records Modal -->
    <!-- <div class="modal fade custom-modal" id="edit_medical_records" >
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Edit <?php echo ((isset($language['lg_medical_records'])) ? $language['lg_medical_records'] : ""); ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="medical_records_form"  enctype="multipart/form-data">          
            <div class="modal-body">
              <input type="hidden" name="patient_id" value="<?php /** @var int $patient_id */ echo $patient_id; ?>">
                              
              <div class="form-group">
                <label><?php echo ((isset($language['lg_description__op'])) ? $language['lg_description__op'] : ""); ?></label>
                <textarea class="form-control" name="description" id="description" rows="5"></textarea>
              </div>
              <div class="form-group">
                <label><?php echo ((isset($language['lg_upload_file'])) ? $language['lg_upload_file'] : ""); ?>[Allowed Types: jpeg/jpg/png/docx/xlsx/pdf Only]</label> 
                <input class="form-control" type="file" name="user_file" id="user_files_mr">
              </div>
              
              <div class="submit-section text-center">
                <button type="submit" id="medical_btn" class="btn btn-primary submit-btn"><?php echo ((isset($language['lg_submit'])) ? $language['lg_submit'] : ""); ?></button>
                <button type="button" class="btn btn-secondary submit-btn" data-dismiss="modal"><?php echo ((isset($language['lg_cancel'])) ? $language['lg_cancel'] : ""); ?></button>             
              </div>
            </div>
          </form>
        </div>
      </div>
    </div> -->
    <!-- /Edit Medical Records Modal -->

    <!-- Show Description Modal -->
    <div class="modal fade custom-modal" id="show_desc_medical_records">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo ((isset($language['lg_medical_records'])) ? $language['lg_medical_records'] : "") . ' - ' . ((isset($language['lg_description'])) ? $language['lg_description'] : ""); ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body" id="med_desc">
          </div>

        </div>
      </div>
    </div>
    <!-- Show Description Modal -->

  <?php }
  if ($page == 'patientDashboard') { ?>
    <!-- View Prescription -->
    <!-- <div class="modal fade custom-modal" id="view_modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="width: fit-content;">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo ((isset($language['lg_view1'])) ? $language['lg_view1'] : ""); ?> <span class="view_title"></span></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <label><?php echo ((isset($language['lg_date1'])) ? $language['lg_date1'] : ""); ?> : <span id="view_date"></span></label><br>
            <label><?php echo ((isset($language['lg_patient_name'])) ? $language['lg_patient_name'] : ""); ?> : <span id="patient_name"></span></label>

            <div class="view_details"></div>
          </div>
          <div class="clearfix"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo ((isset($language['lg_close1'])) ? $language['lg_close1'] : ""); ?></button>
          </div>
        </div>
      </div>
    </div> -->

    <!-- View Prescription v2 -->
    <div class="modal fade custom-modal" id="view_modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document" style="width: 90%">
        <div class="modal-content">
          <div class="modal-header">
           <h5 class="modal-title" style="font-weight: bold;">Veterinary Visit Summary</h5>
           <button type="button" class="btn btn-danger" data-dismiss="modal">X</button>
         </div>
          <div class="modal-body-prescription">
            
            
          </div>
          <div class="clearfix"></div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

    <!-- Show Description Modal -->
    <div class="modal fade custom-modal" id="show_desc_medical_records">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title"><?php echo ((isset($language['lg_medical_records'])) ? $language['lg_medical_records'] : "") . ' - ' . ((isset($language['lg_description'])) ? $language['lg_description'] : ""); ?></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body" id="med_desc">
          </div>

        </div>
      </div>
    </div>
    <!-- Show Description Modal -->
  <?php }

  if ($page == 'add_prescription' || $page == 'edit_prescription' || $page == 'add_billing' || $page == 'edit_billing') {  ?>

    <div class="modal fade" id="sign-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" id="signature-pad">
          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-pencil"></i> <?php echo ((isset($language['lg_add_signature'])) ? $language['lg_add_signature'] : ""); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <canvas width="460" height="318" id="sign"></canvas>
            <input type="hidden" id="rowno" name="rowno" value="<?php echo rand(); ?>">
            <input type="hidden" id="signname" value="">
            <input type="hidden" id="scount" value="">
          </div>
          <div class="modal-footer clearfix">
            <button type="submit" id="save2" class="btn btn-success" data-action="save"><i class="fa fa-check"></i> <?php echo ((isset($language['lg_save'])) ? $language['lg_save'] : ""); ?></button>
            <button type="button" data-action="clear" class="btn btn-default"><i class="fa fa-trash-o"></i> <?php echo ((isset($language['lg_clear'])) ? $language['lg_clear'] : ""); ?></button>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i><?php echo ((isset($language['lg_cancel'])) ? $language['lg_cancel'] : ""); ?></button>
          </div>
        </div>
      </div>
    </div>

  <?php }
}
if ($module == 'post' && $page == 'add_post' || $page == 'edit_post') { ?>

  <div class="modal fade" id="avatar-image-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header d-block">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo ((isset($language['lg_upload_image1'])) ? $language['lg_upload_image1'] : ""); ?></h4>
          <span id="image_size"><?php echo ((isset($language['lg_please_upload_a'])) ? $language['lg_please_upload_a'] : ""); ?></span>
        </div>

        <div class="modal-body">
          <div id="imageimg_loader" class="loader-wrap" style="display: none;">
            <div class="loader"><?php echo ((isset($language['lg_loading'])) ? $language['lg_loading'] : ""); ?></div>
          </div>

          <div class="image-editor">
            <input type="file" id="fileopen" name="file" class="cropit-image-input" required>
            <span class="error_msg help-block" id="error_msg_model"></span>
            <div class="cropit-preview"></div>
            <div class="row resize-bottom">
              <div class="col-md-4">
                <div class="image-size-label"><?php echo ((isset($language['lg_resize_image'])) ? $language['lg_resize_image'] : ""); ?></div>
              </div>
              <div class="col-md-4"><input type="range" class="custom cropit-image-zoom-input"></div>
              <div class="col-md-4 text-right"><button class="btn btn-primary export"><?php echo ((isset($language['lg_done'])) ? $language['lg_done'] : ""); ?></button></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php }

if ($module == 'pharmacy' && $page == 'add_product' || $page == 'edit_product') { ?>


  <div class="modal fade" id="avatar-image-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" data-backdrop="static" data-keyboard="false" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header d-block">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo ((isset($language['lg_upload_image1'])) ? $language['lg_upload_image1'] : ""); ?></h4>
          <span id="image_size"><?php echo ((isset($language['lg_please_upload_a'])) ? $language['lg_please_upload_a'] : ""); ?></span>
        </div>

        <div class="modal-body">
          <div id="imageimg_loader" class="loader-wrap" style="display: none;">
            <div class="loader"><?php echo ((isset($language['lg_loading'])) ? $language['lg_loading'] : ""); ?></div>
          </div>

          <div class="image-editor">
            <input type="file" id="fileopen" name="file" class="cropit-image-input" required>
            <label>Image types allowed: JPEG,JPG,PNG,GIF </label>
            <span class="error_msg help-block" id="error_msg_model"></span>
            <div class="cropit-preview"></div>
            <div class="row resize-bottom">
              <div class="col-md-4">
                <div class="image-size-label"><?php echo ((isset($language['lg_resize_image'])) ? $language['lg_resize_image'] : ""); ?></div>
              </div>
              <div class="col-md-4"><input type="range" class="custom cropit-image-zoom-input"></div>
              <div class="col-md-12 text-right">
                <button class="btn btn-primary export"><?php echo ((isset($language['lg_done'])) ? $language['lg_done'] : ""); ?></button>
                <button class="btn btn-secondary class" data-dismiss="modal"><?php echo ((isset($language['lg_cancel'])) ? $language['lg_cancel'] : ""); ?></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php }

if ($page == 'accounts') { ?>

  <div class="modal fade show" id="account_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="accounts_modal_title"><?php echo ((isset($language['lg_account_details1'])) ? $language['lg_account_details1'] : ""); ?></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form id="accounts_form" method="post">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"><?php echo ((isset($language['lg_bank_name'])) ? $language['lg_bank_name'] : ""); ?> <span class="text-danger">*</span></label>
                  <input type="text" name="bank_name" class="form-control bank_name" value="" id="bank_name">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"><?php echo ((isset($language['lg_branch_name'])) ? $language['lg_branch_name'] : ""); ?> <span class="text-danger">*</span></label>
                  <input type="text" name="branch_name" class="form-control branch_name" value="" id="branch_name">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"><?php echo ((isset($language['lg_account_number'])) ? $language['lg_account_number'] : ""); ?> <span class="text-danger">*</span></label>
                  <input type="text" name="account_no" class="form-control account_no numericOnly" value="" id="account_no" maxlength="20">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label"><?php echo ((isset($language['lg_account_holder_'])) ? $language['lg_account_holder_'] : ""); ?> <span class="text-danger">*</span></label>
                  <input type="text" name="account_name" class="form-control acc_name" value="" id="account_name">
                  <span class="help-block"></span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" id="acc_btn" class="btn btn-primary"><?php echo ((isset($language['lg_save'])) ? $language['lg_save'] : ""); ?></button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo ((isset($language['lg_cancel'])) ? $language['lg_cancel'] : ""); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Payment Modal -->
  <div class="modal fade show" id="payment_request_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title"><?php echo ((isset($language['lg_payment_request3'])) ? $language['lg_payment_request3'] : ""); ?></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <form id="payment_request_form" method="post">
            <input type="hidden" name="payment_type" id="payment_type">
            <div class="form-group">
              <label><?php echo ((isset($language['lg_request_amount'])) ? $language['lg_request_amount'] : ""); ?> <span class="text-danger">*</span></label>
              <input type="text" name="request_amount" id="request_amount" class="form-control numericOnly" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10">
              <span class="help-block"></span>
            </div>
            <div class="form-group">
              <label><?php echo ((isset($language['lg_description_opt'])) ? $language['lg_description_opt'] : ""); ?></label>
              <textarea class="form-control" name="description" id="description"></textarea>
              <span class="help-block"></span>
            </div>
            <div class="modal-footer">
              <button type="submit" id="request_btn" class="btn btn-primary"><?php echo ((isset($language['lg_request1'])) ? $language['lg_request1'] : ""); ?></button>
              <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo ((isset($language['lg_cancel'])) ? $language['lg_cancel'] : ""); ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php }
if (session('user_id') != '') { ?>


  <!-- Video Call Modal -->
  <div class="modal fade call-modal" id="appoinment_user">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">

          <!-- Incoming Call -->
          <div class="call-box incoming-box">
            <div class="call-wrapper appoinments_users_details">

            </div>
          </div>
          <!-- /Incoming Call -->

        </div>
      </div>
    </div>
  </div>
  <!-- Video Call Modal -->


  <div class="modal fade" id="ratings_review_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><?php echo ((isset($language['lg_ratings__review'])) ? $language['lg_ratings__review'] : ""); ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="write-review">
            <h4><?php echo ((isset($language['lg_write_a_review_'])) ? $language['lg_write_a_review_'] : ""); ?> <strong><?php echo ((isset($language['lg_dr'])) ? $language['lg_dr'] : ""); ?> <span id="doctor_name"></span></strong></h4>

            <!-- Write Review Form -->
            <form method="post" id="rating_reviews_form">
              <div class="form-group">
                <label><?php echo ((isset($language['lg_review2'])) ? $language['lg_review2'] : ""); ?></label>
                <div class="star-rating">
                  <input id="star-5" type="radio" name="rating" value="5">
                  <label for="star-5" title="5 stars">
                    <i class="active fa fa-star"></i>
                  </label>
                  <input id="star-4" type="radio" name="rating" value="4">
                  <label for="star-4" title="4 stars">
                    <i class="active fa fa-star"></i>
                  </label>
                  <input id="star-3" type="radio" name="rating" value="3">
                  <label for="star-3" title="3 stars">
                    <i class="active fa fa-star"></i>
                  </label>
                  <input id="star-2" type="radio" name="rating" value="2">
                  <label for="star-2" title="2 stars">
                    <i class="active fa fa-star"></i>
                  </label>
                  <input id="star-1" type="radio" name="rating" value="1">
                  <label for="star-1" title="1 star">
                    <i class="active fa fa-star"></i>
                  </label>
                </div>
              </div>
              <!-- hidden fileds -->


              <input type="hidden" name="doctor_id" id="doctor_id">
              <input type="hidden" name="appointment_id" id="rating_appointment_id">
              <div class="form-group">
                <label><?php echo ((isset($language['lg_title_of_your_r'])) ? $language['lg_title_of_your_r'] : ""); ?></label>
                <input class="form-control" name="title" type="text" placeholder="<?php echo ((isset($language['lg_if_you_could_sa'])) ? $language['lg_if_you_could_sa'] : "") ?>">
              </div>
              <div class="form-group">
                <label><?php echo ((isset($language['lg_your_review'])) ? $language['lg_your_review'] : ""); ?></label>
                <textarea id="review_desc" name="review" maxlength="100" class="form-control"></textarea>

                <div class="d-flex justify-content-between mt-3"><small class="text-muted"><span id="chars">100</span> <?php echo ((isset($language['lg_characters_rema'])) ? $language['lg_characters_rema'] : ""); ?></small></div>
              </div>
              <hr>

              <div class="submit-section">
                <button id="review_btn" type="submit" class="btn btn-primary submit-btn"><?php echo ((isset($language['lg_add_review'])) ? $language['lg_add_review'] : ""); ?></button>
              </div>
            </form>
            <!-- /Write Review Form -->

          </div>
        </div>

      </div>
    </div>
  </div>

<?php }
if ($module == 'signin' && $page == 'register') { ?>

  <div class="modal fade call-modal" id="user_role_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">

          <!-- Incoming Call -->
          <select class="form-control" id="user_role" onchange="social_register()">
            <option value=""><?php echo ((isset($language['lg_select_role'])) ? $language['lg_select_role'] : ""); ?></option>
            <option value="1"><?php echo ((isset($language['lg_doctor2'])) ? $language['lg_doctor2'] : ""); ?></option>
            <option value="2"><?php echo ((isset($language['lg_patient4'])) ? $language['lg_patient4'] : ""); ?></option>
            <option value="5"><?php echo ((isset($language['lg_pharmacy'])) ? $language['lg_pharmacy'] : ""); ?></option>
          </select>
          <!-- /Incoming Call -->

        </div>
      </div>
    </div>
  </div>



<?php } ?>

<?php if ($page == 'lab_tests') { ?>
  <div id="lab_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="#" enctype="multipart/form-data" autocomplete="off" id="lab_form" method="post">
          <div class="modal-header">
            <h5 class="modal-title">Add Lab Test</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" value="" name="id" />
            <input type="hidden" value="" name="method" />
            <div class="form-group">
              <label class="control-label mb-10"> Test Name <span class="text-danger">*</span></label>
              <input type="text" parsley-trigger="change" id="lab_test_name" name="lab_test_name" class="form-control" maxlength="60">
            </div>
            <div class="form-group">
              <label for="slug" class="control-label mb-10">Amount <span class="text-danger">*</span></label>
              <input type="text" parsley-trigger="change" id="amount" name="amount" class="form-control numericOnly" maxlength="10">
            </div>
            <div class="form-group">
              <label for="slug" class="control-label mb-10" onload='document.form1.text1.focus()'>Duration (In Minutes)<span class="text-danger">*</span></label>
              <input type="text" parsley-trigger="change" id="duration" onclick="duration(document.form1.text1)" name="duration" maxlength="3" class="form-control" maxlength="10">
            </div>
            <div class="form-group">
              <label for="slug" class="control-label mb-10">Description <span class="text-danger">*</span></label>
              <input type="text" parsley-trigger="change" id="description" name="description" class="form-control" maxlength="100">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline btn-default btn-sm btn-rounded" data-dismiss="modal">Cancel</button>
            <button type="submit" id="btnlabtestsave" class="btn btn-outline btn-success ">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Delete Modal -->
  <div class="modal fade" id="delete_lab_test" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="form-content p-2">
            <input type="hidden" id="lab_test_id">
            <input type="hidden" id="lab_test_status">
            <h4 class="modal-title">Change</h4>
            <p class="mb-4">Are you sure want to change the status?</p>
            <button type="button" id="change_btn" onclick="lab_test_delete()" class="btn btn-primary">Yes </button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /Delete Modal -->
<?php }

if (($page == 'appointments' ||  $page == 'lab_dashboard') && $module == 'lab') {    ?>

  <div class="modal fade custom-modal" id="upload_labdocs_modal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Upload Patient Reports</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <form id="upload_lab_form" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" name="appointment_id">

            <div class="form-group">
              <label><?php echo ((isset($language['lg_upload_file'])) ? $language['lg_upload_file'] : ""); ?></label>
              <input class="form-control" type="file" name="user_file" id="user_files_mr" multiple="multiple">
            </div>

            <!--  <div class="form-group">
                <label><?php echo ((isset($language['lg_description__op'])) ? $language['lg_description__op'] : ""); ?></label>
                <textarea class="form-control" name="description" id="description" rows="5"></textarea>
              </div> -->


            <div class="submit-section text-center">
              <button type="submit" id="medical_btn" class="btn btn-primary submit-btn"><?php echo ((isset($language['lg_submit'])) ? $language['lg_submit'] : ""); ?></button>
              <button type="button" class="btn btn-secondary submit-btn" data-dismiss="modal"><?php echo ((isset($language['lg_cancel'])) ? $language['lg_cancel'] : ""); ?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php }
if ($page == "doctorList") { ?>

  <!-- Add Modal -->
  <div class="modal fade" id="user_modal" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="#" autocomplete="off" id="register_form" method="post">
          <input type="hidden" id="role" name="role" value="1">
          <div class="modal-header">
            <h5 class="modal-title">Add Veterinarian</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="row form-row">
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>First Name <span class="text-danger">*</span></label>
                  <input type="text" name="first_name" id="first_name" class="form-control namefield" maxlength="100">
                  <input type="hidden" name="user_id" id="user_id" class="form-control">
                  <input type="hidden" name="role" id="role" value='1'>
                  <input type="hidden" id="country_id" name="country_id">
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Last Name <span class="text-danger">*</span></label>
                  <input type="text" name="last_name" id="last_name" class="form-control namefield" maxlength="100">
                </div>
              </div>
              <div class="col-12 col-sm-12">
                <div class="form-group">
                  <label>Email<span class="text-danger">*</span></label>
                  <input type="email" name="email" id="email" class="form-control" maxlength="200">
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Country Code <span class="text-danger">*</span></label>
                  <select name="country_code" class="form-control select" id="country_code">
                    <option value="">Select Country Code</option>
                  </select>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Mobile No <span class="text-danger">*</span></label>
                  <input type="text" name="mobileno" id="mobileno" pattern="[1-15]{1}[0-15]{15}" class="form-control mobileNoOnly" maxlength="15">
                </div>
              </div>
              <div class="col-12 col-sm-6 pass">
                <div class="form-group">
                  <label>Password <span class="text-danger">*</span></label>
                  <input type="password" name="password" id="password" class="form-control">
                </div>
              </div>
              <div class="col-12 col-sm-6 pass">
                <div class="form-group">
                  <label>Confirm Password <span class="text-danger">*</span></label>
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control">
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

<?php  }  ?>

    <!-- Pet update code
    added new on 13rd June 2024 by Muddasar-->
<!-- 
// if ($page == 'profile' || $page=="appoinments" ) {//|| $page="doctor_previe" -->
<!-- The Modal -->
<div class="modal fade" id="addPetModal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <?php /*?><form id="petForm">
          <div class="modal-header">
            <h4 class="modal-title">Add Pet</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">

              <div class="form-group">
                <label for="petName">Pet Name</label>
                <input type="text" class="form-control" id="petName" name="petName" placeholder="Enter pet name">
              </div>
              <div class="form-group">
                <label for="petBirthDate">Pet Birth Date</label>
                <input type="text" class="form-control petBirthDatepicker" id="petBirthDate" name="petBirthDate" placeholder="dd/mm/yyyy" readonly>
              </div>
              <div class="form-group">
                <label for="petType">Type</label>
                <select class="form-control" id="petType" name="petType">
                  <option value="">Select Type</option>
                  <option>Dog</option>
                  <option>Cat</option>

                </select>
              </div>
              <div class="form-group">
                <label for="breedType">Breed Type</label>
                <select class="form-control" id="breedType" name="breedType">
                  <option value="">Select Breed Type</option>
                  <option>Mixed Breed</option>
                  <option>Pure Breed</option>

                </select>
              </div>
              <div class="form-group">
                <label for="breedSize">Breed Size</label>
                <select class="form-control" id="breedSize" name="breedSize">
                  <option value="">Select Breed Size</option>
                  <option>Small</option>
                  <option>Medium</option>
                  <option>Large</option>

                </select>
              </div>
              <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender">
                  <option value="">Select Gender</option>
                  <option>Male</option>
                  <option>Female</option>

                </select>
              </div>
              <div class="form-group">
                <label for="weight">Weight</label>
                <select class="form-control" id="weight" name="weight">
                  <option value="">Select Weight</option>
                  <option>Underweight</option>
                  <option>Normal</option>
                  <option>Overweight</option>

                </select>
              </div>
              <div class="form-group">
                <label for="weightCondition">Weight Condition</label>
                <select class="form-control" id="weightCondition" name="weightCondition">
                  <option value="">Select Weight Condition</option>
                  <option>Good</option>
                  <option>Needs Attention</option>

                </select>
              </div>
              <div class="form-group">
                <label for="activityLevel">Activity Level</label>
                <select class="form-control" id="activityLevel" name="activityLevel">
                  <option value="">Select Activity Level</option>
                  <option>Low</option>
                  <option>Moderate</option>
                  <option>High</option>

                </select>
              </div>
              <div class="form-group">
                <label for="petPhoto">Choose Pet Photo</label>
                <input type="file" class="form-control" id="petPhoto" name="petPhoto" accept="image/*">
              </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" form="petForm" id="create_new_pet_btn">Save</button>
          </div>
          
      </form><?php */?>

    </div>
  </div>
</div>

 
<div class="modal fade" id="saveRequiredInfoModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">


    </div>
  </div>
</div>

<div class="modal fade" id="register" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content custom-popup">


    </div>
  </div>
</div>

<div class="modal fade" id="changePassword" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">


    </div>
  </div>
</div>

<div class="modal fade" id="loginModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">


    </div>
  </div>
</div>

<div class="modal fade" id="thankYouModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 650px;">
    <div class="modal-content" style="border-radius: 20px; padding: 40px; text-align: center; position: relative; font-family: Arial, sans-serif;">


    </div>
  </div>
</div>

<div class="modal fade" id="selectPetModal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">


    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="signoutBtnModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content" style="border-radius:24px;padding:30px;background-color:#fff;box-shadow:0 10px 40px rgba(0,0,0,0.1);position:relative;">
      
      <div class="modal-body text-center" style="padding:30px;">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
        style="position:absolute;top:20px;right:24px;font-size:28px;font-weight:bold;color:#aaa;background:none;border:none;">
         <span aria-hidden="true">&times;</span>
      </button>
        <!-- Title -->
        <h2 style="font-size:24px;font-weight:600;font-family:Poppins,sans-serif;margin-bottom:30px;color:#252525;">
          Are you sure you want<br>to sign out of your account?
        </h2>

        <!-- Buttons -->
        <div style="display:flex;justify-content:center;gap:20px;">
          <button class="btn" style="min-width:140px;height:48px;font-size:16px;font-weight:500;border-radius:12px;font-family:Poppins,sans-serif;border:2px solid #252525;color:#252525;">
           <a href="<?php echo base_url(); ?>user-logout" id="signout">
              <span><?php echo $language['lg_signout'] ?? "Signout"; ?></span>
					  </a>
          </button>
           <!-- Cancel Button -->
          <button type="button" data-dismiss="modal"
            style="min-width:140px;height:48px;font-size:16px;font-weight:500;border-radius:12px;font-family:Poppins,sans-serif;background-color:#FF9900;color:white;border:none;">
            CANCEL
          </button>
        </div>

      </div>
    </div>
  </div>
</div>
        
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this pet?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<?php //} ?>
     <!-- Pet update code end
    added new on 13rd June 2024 by Muddasar-->
<audio id="myAudio">
  <source src="<?php echo base_url(); ?>assets/ring/phone_ring.mp3" type="audio/mp3">
</audio>

<?php
echo view('user/modules/language_scripts/scripts');
?>

<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
  var modules = '<?php echo $module; ?>';
  var pages = '<?php echo $page; ?>';
  var roles = '<?php echo session('role'); ?>';
</script>

<!-- jQuery -->
<?php if ($page == 'add_post' || $page == 'edit_post' || $page == 'add_product' || $page == 'edit_product') { ?>
  <script src="<?php echo base_url(); ?>assets/js/jquery2.js"></script>
<?php } else { ?>
  <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<?php } ?>
<!-- Bootstrap Core JS -->
<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>


<?php
if ($module == 'doctor' || $module == 'patient' || $module == 'calendar' || $module == 'invoice' || $module == 'lab' || $module == 'clinic' || $theme == 'blog' || $page == 'doctors_search'  || $page == 'doctors_searchmap'  || $page == 'doctors_mapsearch' || $page == 'patients_search' || $module == 'pharmacy' || $page == 'products_list' || $page == 'pharmacy_search_bydoctor' || $page == 'products_list_by_pharmacy') {
?>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/multiselect/dist/js/bootstrap-multiselect.js"></script>
  <!-- Sticky Sidebar JS -->
  <script src="<?php echo base_url(); ?>assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
  <!-- Circle Progress JS -->
  <script src="<?php echo base_url(); ?>assets/js/circle-progress.min.js"></script>
<?php } ?>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
<?php
if (($module == 'doctor' || $module == 'subscription' || $module == 'clinic' || $module == 'patient' || $module == 'post' || $module == 'calendar' || $module == 'invoice' || $module == 'pharmacy' || $module == 'home' || $module == 'ecommerce' || $module == 'lab')) {
  if ($page == 'book_appoinments' || $page == 'doctor_profile' || $page == 'profile' || $page == 'hospital_profile' || $page == 'pharmacy_profile' || $page == 'lab_profile' || $page == 'lab_tests_preview' || $page == 'add_product' || $page == 'products_list_by_pharmacy' || $page == "doctor_dashboard") {
?>
  <?php
  }
  if ($page == 'doctor_profile' || $page == 'profile' || $page == 'lab_profile' || $page == 'hospital_profile' || $page == 'pharmacy_profile' || $page == 'add_product') {
  ?>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/cropper_profile.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/cropper.min.js"></script>

  <?php
  }   ?>
  <?php if ($page == 'products_list_by_pharmacy' || $page == 'index') {
  ?>
    <script src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
  <?php
  }
  if ($page == 'profile' || $page == 'add_product') {
  ?>
    <!-- Clinic Profile -->
    <script src="<?php echo base_url(); ?>assets/plugins/dropzone/dropzone.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/profile-settings.js"></script>
  <?php
  }
  if ($page == 'calendar' || $page == 'add_product') {
  ?>
    <script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/fullcalendar/fullcalendar.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/calendar.js"></script>

  <?php
  }
  if ($page == 'doctorList' || $page == 'doctor_dashboard' || $page == 'mypatient_preview' || $page == 'patientDashboard' || $page == 'index' || $page == 'pending_post' || $page == 'invoice' || $page == 'accounts' || $page == 'pharmacy_quotation' || $page == 'product_list' || $page == 'patient_quotation_list' || $page == 'orderlist' || $page == 'pharmacy_dashboard' || $page == 'lab_appoinments' || $page == 'appointments' || $page == "add_doctor" || $page == 'lab_appointment_list' || $page == 'lab_tests' || $page == 'lab_dashboard' || $module == 'pharmacy') {
  ?>

    <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/datatables.min.js"></script>

  <?php
  }
  if ($page == 'add_prescription' || $page == 'edit_prescription' || $page == 'add_billing' || $page == 'edit_billing') {
  ?>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/signature-pad.js"></script>

<?php
  }
}
?>
<!-- Slick JS -->
<script src="<?php echo base_url(); ?>assets/js/slick.js"></script>

<!-- Custom JS -->
<script src="<?php echo base_url(); ?>assets/js/script.js"></script>

<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.password-validation.js" type="text/javascript"></script>

<!-- Widget Setting JS -->
<script src="<?php echo base_url(); ?>assets/js/widget-settings.js"></script>

<?php
if ($page == "accounts") {
?>
  <script src="<?php echo base_url(); ?>assets/js/accounts.js"></script>
<?php
}
?>
<?php
if (($module == 'patient' || $module == 'ecommerce' || $module == 'subscription' || $module == 'lab' || $module == "home")  && ($page == 'checkout' || $page == "lab-checkout")) {
?>
  <script src="https://js.stripe.com/v3/"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php
}
?>
<script src="<?php echo base_url(); ?>assets/js/toastr.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jstz-1.0.7.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js"></script>

<script type="text/javascript">
  if ($('.select').length > 0) {
    $('.select').select2({
      //minimumResultsForSearch: -1,
      width: '100%'
    });
  }
</script>

<!-- Fancybox JS -->
<script src="<?php echo base_url(); ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>

<?php
if (($module != 'signin' &&  $module != 'patient' &&  $module != 'clinic') || $page == "checkout") {
?>
  <script src="<?php echo base_url(); ?>assets/js/web.js?v=0.0009"></script>
<?php
}
?>
<!--pharmacy-->
<?php
if ($module == 'home') {
?>
  <script src="<?php echo base_url(); ?>assets/js/user/product.js"></script>
<?php
}
?>
<?php
// Js For Register / Login / Forgot Password
if ($module == 'signin') {
?>
  <script src="<?php echo base_url(); ?>assets/js/user/auth.js"></script>
<?php
}
?>
<?php
if ($module == 'patient') {
?>
  <script src="<?php echo base_url(); ?>assets/js/user/patient.js"></script>
<?php
}
?>
<?php
if ($module == 'clinic' || $module == 'doctor') {
?>
  <script src="<?php echo base_url(); ?>assets/js/user/clinic.js"></script>
<?php
}
?>

<?php
if ($module == 'pharmacy') {
?>
  <script src="<?php echo base_url(); ?>assets/js/user/pharmacy.js"></script>
<?php
}
?>

<?php
if ($module == 'lab') {
?>
  <script src="<?php echo base_url(); ?>assets/js/user/lab.js"></script>
<?php
}
?>

<?php
if ($module == 'schedule') {
?>
  <script src="<?php echo base_url(); ?>assets/js/user/scheduletime.js"></script>
<?php
}
?>

<?php
if ($module == 'messages') {
?>
  <script src="<?php echo base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
  <?php
}
if (session('user_id') != '') {
  if ($module != 'pharmacy' && $page != 'checkout' && $page != 'schedule_timings') {
  ?>
    <script src="<?php echo base_url(); ?>assets/js/messages.js?v=0.0002"></script>
    <script src="<?php echo base_url(); ?>assets/js/appoinments.js"></script>
  <?php
  }
}

if ($theme == 'blog' || $module == 'pharmacy' || $page == 'blogList'  || $page == 'blog_details') {
  if ($module == 'home' && $page == 'blog_details') { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-confirm.min.js"></script>
    <?php
  }
  if ($module == 'post' || $module == 'pharmacy') {
    if ($page == 'add_post' || $page == 'edit_post' || $page == 'add_product' || $page == 'edit_product') {
    ?>
      <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.cropit.js"></script>
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/cropper_image.js"></script>
    <?php
    }

    if ($page == 'add_product' || $page == 'edit_product') {
    ?>
      <!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/product_cropper_image.js"></script> -->
  <?php
    }
  }
  ?>
  <script src="<?php echo base_url(); ?>assets/js/blog.js"></script>
<?php
}
?>

<?php if (($module == 'doctor' || $module == 'home' || $module == 'calendar') && ($page == 'doctor_profile' || $page == 'doctors_search' || $page == 'doctors_mapsearch' || $page == 'calendar')) { ?>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/multiselect/dist/js/bootstrap-multiselect.js"></script>
<?php } ?>
<?php if (session()->getFlashdata('error_message')) {  ?>
  <script>
    toastr.error('<?php echo session()->getFlashdata('error_message'); ?>');
  </script>
<?php session()->remove('error_message');
}
if (session()->getFlashdata('success_message')) {  ?>

  <script>
    toastr.success('<?php echo session()->getFlashdata('success_message'); ?>');
  </script>

<?php session()->remove('success_message');
} ?>
    <!--Pet update code
    added new on 13rd June 2024 by Muddasar-->
<?php if ($page == 'profile'  || $page=="appoinments" || $page="doctor_preview") {?>
<input type="hidden" id="maxDate_pet_case" value="<?php echo date('d/m/Y',strtotime('-1 day')); ?>">
<script src="<?php echo base_url(); ?>assets/js/addEditPet.js"></script>
<?php } ?>


    <!-- Pet update code end
    added new on 13rd June 2024 by Muddasar-->
<?php if ($module == 'signin' && ($page == 'index' || $page == 'register')) {  ?>


  <script type="text/javascript">
    var googleclientid = '<?php echo !empty(settings("googleclientid")) ? settings("googleclientid") : ""; ?>';
    var fbappid = '<?php echo !empty(settings("facebookclientid")) ? settings("facebookclientid") : ""; ?>';
  </script>
  <script src="https://apis.google.com/js/api:client.js"></script>
  <script src="https://accounts.google.com/gsi/client" async defer></script>



  <script type="text/javascript">
    var first_name = '';
    var last_name = '';
    var email = '';

   //google login ------------------------------------------
   function parseJwt (token) {
              var base64Url = token.split('.')[1];
              var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
              var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
                  return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
              }).join(''));

              return JSON.parse(jsonPayload);
          }
          function handleCredentialResponse(response) {
            const responsePayload = parseJwt(response.credential);
            first_name=responsePayload.given_name;
            last_name=responsePayload.family_name;
            email=responsePayload.email;
            console.log(first_name);
				<?php if($page=='register') { ?> 
                   //register
                       $.post(base_url + 'check-register',{'email':email},function(response){
                          var obj = JSON.parse(response);
                            if (obj.status===200) {
                                $('#user_role_modal').modal('show');
                            }  if (obj.status===500) {
                                toastr.error(obj.msg);
                            }
                      });
                <?php } else { ?> 
                  $.post(base_url + 'social-signin',{'email':email,'first_name':first_name,'last_name':last_name},function(response){
                          var obj = JSON.parse(response);
                            if (obj.status===200) {
                                window.location.href=base_url;
                            }  if (obj.status===500) {
                                toastr.error(obj.msg);
                            }
                      });
                  				  
				<?php   } ?>
		  
			}
		<?php if($page=='register') { ?> 
			var  googleText  =  'signup_with';
		<?php } else { ?> 
			var  googleText  =  'signin_with';
		<?php } ?>
        window.onload = function () {
          google.accounts.id.initialize({
            client_id: googleclientid,
            callback: handleCredentialResponse
          });
          google.accounts.id.renderButton(
            document.getElementById("googleloginbtn"),
            { type: "standard", theme: "outline", size: "large",  shape: "rectangular", logo_alignment: "left",  width: 195, text: googleText }  // customization attributes
          );
          
        }

    //fb ------------
    window.fbAsyncInit = function() {
      // FB JavaScript SDK configuration and setup
      FB.init({
        appId: fbappid, // FB App ID
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true, // parse social plugins on this page
        version: 'v2.8' // use graph api version 2.8
      });

      // Check whether the user already logged in
      // FB.getLoginStatus(function(response) {
      //     if (response.status === 'connected') {
      //         //display user data
      //         getFbUserData();
      //     }
      // });
    };

    // Load the JavaScript SDK asynchronously
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s);
      js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Facebook login with JavaScript SDK
    function fbLogin() {
      FB.login(function(response) {
        if (response.authResponse) {
          // Get and display the user profile data
          getFbUserData();
        } else {
          toastr.error('User cancelled login or did not fully authorize');

        }
      }, {
        scope: 'email'
      });
    }

    // Fetch the user profile data from facebook
    function getFbUserData() {
      FB.api('/me', {
          locale: 'en_US',
          fields: 'id,name,first_name,last_name,email,link,gender,locale,picture'
        },
        function(response) {

          first_name = response.first_name;
          last_name = response.last_name;
          email = response.email;

          if (typeof email === "undefined") {
            toastr.error('Email is not available in your Facebook account');
            return false;
          }

          <?php if ($page == 'register') { ?>
            //register
            $.post(base_url + 'check-register', {
              'email': email
            }, function(response) {
              var obj = JSON.parse(response);
              if (obj.status === 200) {
                $('#user_role_modal').modal('show');
              }
              if (obj.status === 500) {
                toastr.error(obj.msg);
              }
            });
          <?php } else { ?>
            // signin

            $.post(base_url + 'social-signin', {
              'email': email
            }, function(response) {
              var obj = JSON.parse(response);
              if (obj.status === 200) {
                window.location.href = base_url;
              }
              if (obj.status === 500) {
                toastr.error(obj.msg);
              }
            });

          <?php } ?>





        });
    }

    function social_register() {
      var user_role = $('#user_role').val();
      $.post(base_url + 'social-register', {
        'first_name': first_name,
        'last_name': last_name,
        'email': email,
        'user_role': user_role
      }, function(response) {
        var obj = JSON.parse(response);
        if (obj.status === 200) {
          window.location.href = base_url;
        }
        if (obj.status === 500) {
          toastr.error(obj.msg);
        }
      });
    }
  </script>

<?php }
if ($module == 'patient' && $page == 'checkout') {  ?>


  <script type="text/javascript">
    var googleclientid = '<?php echo !empty(settings("googleclientid")) ? settings("googleclientid") : ""; ?>';
    var fbappid = '<?php echo !empty(settings("facebookclientid")) ? settings("facebookclientid") : ""; ?>';
  </script>
  <script src="https://apis.google.com/js/api:client.js"></script>



  <script type="text/javascript">
    //google login ------------------------------------------

    var googleUsersignin = {};

    var startAppsignin = function() {

      gapi.load('auth2', function() {
        // Retrieve the singleton for the GoogleAuth library and set up the client.
        auth2 = gapi.auth2.init({
          client_id: googleclientid,

          cookiepolicy: 'single_host_origin',
          // Request scopes in addition to 'profile' and 'email'
          //scope: 'additional_scope'
        });

        attachSignin(document.getElementById('googlecheckoutsigninbtn'));

      });
    };



    function attachSignin(element) {

      auth2.attachClickHandler(element, {},
        function(googleUser) {

          var first_name = googleUser.getBasicProfile().getGivenName();
          var last_name = googleUser.getBasicProfile().getFamilyName();
          var email = googleUser.getBasicProfile().getEmail();
          var role = 2;


          $.post(base_url + 'social-signin', {
            'email': email,
            'role': role
          }, function(response) {
            var obj = JSON.parse(response);
            if (obj.status === 200) {
              window.location.reload();
            }
            if (obj.status === 500) {
              toastr.error(obj.msg);
            }
          });


        },
        function(error) {
          toastr.error(JSON.stringify(error, undefined, 2));
        });
    }



    startAppsignin();

    //fb ------------
    window.fbAsyncInit = function() {
      // FB JavaScript SDK configuration and setup
      FB.init({
        appId: fbappid, // FB App ID
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true, // parse social plugins on this page
        version: 'v2.8' // use graph api version 2.8
      });

      // Check whether the user already logged in
      // FB.getLoginStatus(function(response) {
      //     if (response.status === 'connected') {
      //         //display user data
      //         getFbUserData();
      //     }
      // });
    };

    // Load the JavaScript SDK asynchronously
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s);
      js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Facebook login with JavaScript SDK
    function fbcheckoutsignin() {
      FB.login(function(response) {
        if (response.authResponse) {
          // Get and display the user profile data
          getFbUserData();
        } else {
          toastr.error('User cancelled login or did not fully authorize');

        }
      }, {
        scope: 'email'
      });
    }

    // Fetch the user profile data from facebook
    function getFbUserData() {
      FB.api('/me', {
          locale: 'en_US',
          fields: 'id,name,first_name,last_name,email,link,gender,locale,picture'
        },
        function(response) {

          var first_name = response.first_name;
          var last_name = response.last_name;
          var email = response.email;
          var role = 2;

          if (typeof email === "undefined") {
            toastr.error('Email is not available in your Facebook account');
            return false;
          }

          $.post(base_url + 'social-signin', {
            'email': email,
            'role': role
          }, function(response) {
            var obj = JSON.parse(response);
            if (obj.status === 200) {
              window.location.reload();
            }
            if (obj.status === 500) {
              toastr.error(obj.msg);
            }
          });




        });
    }




    //google signup ------------------------------------------

    var googleUsersignup = {};

    var startAppsignup = function() {

      gapi.load('auth2', function() {
        // Retrieve the singleton for the GoogleAuth library and set up the client.
        auth2 = gapi.auth2.init({
          client_id: googleclientid,
          plugin_name: "dgtdoccure_doctor_signup",
          cookiepolicy: 'single_host_origin',
          ux_mode: 'popup',
          // Request scopes in addition to 'profile' and 'email'
          scope: 'additional_scope'
        });

        attachSignup(document.getElementById('googlecheckoutsignupbtn'));

      });
    };



    function attachSignup(element) {

      auth2.attachClickHandler(element, {},
        function(googleUser) {

          var first_name = googleUser.getBasicProfile().getGivenName();
          var last_name = googleUser.getBasicProfile().getFamilyName();
          var email = googleUser.getBasicProfile().getEmail();
          var role = 2;


          $.post(base_url + 'social-register', {
            'first_name': first_name,
            'last_name': last_name,
            'email': email,
            'user_role': role
          }, function(response) {
            var obj = JSON.parse(response);
            if (obj.status === 200) {
              window.location.reload();
            }
            if (obj.status === 500) {
              toastr.error(obj.msg);
            }
          });


        },
        function(error) {
          toastr.error(JSON.stringify(error, undefined, 2));
        });
    }



    startAppsignup();



    // Load the JavaScript SDK asynchronously
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s);
      js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Facebook login with JavaScript SDK
    function fbcheckoutsignup() {
      FB.login(function(response) {
        if (response.authResponse) {
          // Get and display the user profile data
          getFbUserDatasignup();
        } else {
          //error_msg('User cancelled login or did not fully authorize');
          toastr.error('User cancelled login or did not fully authorize');

        }
      }, {
        scope: 'email'
      });
    }

    // Fetch the user profile data from facebook
    function getFbUserDatasignup() {
      FB.api('/me', {
          locale: 'en_US',
          fields: 'id,name,first_name,last_name,email,link,gender,locale,picture'
        },
        function(response) {

          var first_name = response.first_name;
          var last_name = response.last_name;
          var email = response.email;
          var role = 2;

          if (typeof email === "undefined") {
            toastr.error('Email is not available in your Facebook account');
            return false;
          }

          $.post(base_url + 'social-register', {
            'first_name': first_name,
            'last_name': last_name,
            'email': email,
            'user_role': role
          }, function(response) {
            var obj = JSON.parse(response);
            if (obj.status === 200) {
              window.location.reload();
            }
            if (obj.status === 500) {
              toastr.error(obj.msg);
            }
          });




        });
    }
    

  </script>

  

<?php } ?>
</body>
</html>