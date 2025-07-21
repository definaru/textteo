<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<!-- Page Content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-8 offset-md-2">

                <!-- Account Content -->
                <div class="account-content">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-7 col-lg-6 login-left">
                            <img src="<?php echo !empty(base_url() . settings("login_image")) && file_exists(settings("login_image")) ? base_url() . settings("login_image") : base_url() . "assets/img/login-banner.png"; ?>" class="img-fluid" alt="Doccure Login">
                        </div>
                        <div class="col-md-12 col-lg-6 login-right">
                            <div class="login-header">
                                <h3>
                                    <?php echo $language['lg_forgot_password'] ?? ""; ?>
                                </h3>
                                <p class="small text-muted">
                                    <?php echo $language['lg_enter_your_emai'] ?? ""; ?>
                                </p>
                            </div>

                            <!-- Forgot Password Form -->
                            <form action="#" id="reset_password" autocomplete="off">
                                <div class="form-group form-focus">
                                    <input type="email" type="email" name="resetemail" id="resetemail" class="form-control floating">
                                    <label class="focus-label"><?php echo $language['lg_email'] ?? ""; ?></label>
                                </div>
                                <div class="text-right">
                                    <a class="forgot-link" href="<?php echo base_url(); ?>login"><?php echo $language['lg_remember_your_p'] ?? ""; ?></a>
                                </div>
                                <button id="reset_pwd" class="btn btn-primary btn-block btn-lg login-btn" type="submit"><?php echo $language['lg_reset_password'] ?? ""; ?></button>
                            </form>
                            <!-- /Forgot Password Form -->

                        </div>
                    </div>
                </div>
                <!-- /Account Content -->

            </div>
        </div>

    </div>

</div>
<!-- /Page Content -->

<?php $this->endSection(); ?>