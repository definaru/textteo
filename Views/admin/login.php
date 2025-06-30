<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
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
    
    <!-- Main CSS -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/toastr.css">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/admin.css">
    
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.min.js"></script>
      <script src="assets/js/respond.min.js"></script>
    <![endif]-->
	
	
    </head>
    <body>
  
    <!-- Main Wrapper -->
        <div class="main-wrapper login-body">
            <div class="login-wrapper">
              <div class="container">
                  <div class="loginbox">
                      <div class="login-left">
              <img class="img-fluid" src="<?php echo !empty(base_url().settings("logo_front"))?base_url().settings("logo_front"):base_url()."assets/img/logo.png";?>" alt="Logo">
                        </div>
                        <div class="login-right">
              <div class="login-right-wrap">
                <h1>Login</h1>
                <p class="account-subtitle">Access to our dashboard</p>
                
                <!-- Form -->
                <form method="post" id="adm_login" action="<?php echo base_url();?>admin/login">
                  <div class="form-group">
                    <input class="form-control" name="email" type="email" placeholder="Email" maxlength="30" required autocomplete="off">
                  </div>
                  <div class="form-group">
                    <input class="form-control" name="password" id="password" type="password" placeholder="Password" maxlength="18" required>
					<span class="far fa-eye" id="togglePassword1"></span>

                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary btn-block" id="admin_signin" type="submit">Login</button>
                  </div>
                </form>
                <!-- /Form -->
                
                <!-- <div class="text-center forgotpass"><a href="forgot-password.html">Forgot Password?</a></div>
                <div class="login-or">
                  <span class="or-line"></span>
                  <span class="span-or">or</span>
                </div> -->
                  
            
              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- /Main Wrapper -->
    
    <!-- jQuery -->
        <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
    
    <!-- Bootstrap Core JS -->
        <script src="<?php echo base_url();?>assets/js/popper.min.js"></script>
        <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo base_url();?>assets/js/admin2.js"></script>

    <script src="<?php echo base_url();?>assets/js/toastr.js"></script>

    <?php
    
     if(session()->getFlashdata('error_message')) {   /** @phpstan-ignore-line */ ?>
             <script>
               toastr.error('<?php echo session()->getFlashdata('error_message');?>');<?php /** @phpstan-ignore-line */ ?>
            </script>
        <?php session()->remove('error_message');/** @phpstan-ignore-line */ 
        } if(session()->getFlashdata('success_message')) { /** @phpstan-ignore-line */   ?>

            <script>
               toastr.success('<?php echo session()->getFlashdata('success_message');?>');<?php /** @phpstan-ignore-line */ ?>
            </script>
            
      <?php session()->remove('success_message'); } /** @phpstan-ignore-line */ ?>
    
    </body>
</html>
<!-- newly added code on 13-10-2022 by Nandak------------------>
	<script>
	const togglePassword1 = document.querySelector('#togglePassword1');
  const password1 = document.querySelector('#password');

  togglePassword1.addEventListener('click', function (e) {
    // toggle the type attribute
    const type1 = password1.getAttribute('type') === 'password' ? 'text' : 'password';
    password1.setAttribute('type', type1);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});


  
	</script>
	<style>
	 .far {
  float: right;
  margin-left: -25px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}
	
	</style>
	<!-- newly added code End ------------------> 

