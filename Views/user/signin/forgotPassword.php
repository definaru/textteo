<?php 
    $this->extend('user/layout/header'); 
    $image = settings("login_image");
    $login_image = !empty('/'.$image) && file_exists($image) ? 
        '/'.$image : 
        '/assets/img/login-banner.png';
?>
<?php $this->section('content'); ?>
<main class="vh-100 vstack justify-content-center">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="account-content">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-md-7 col-lg-6 login-left">
                                <img 
                                    src="<?=$login_image;?>" 
                                    class="img-fluid" 
                                    alt="Doccure Login" 
                                />
                            </div>
                            <div class="col-md-12 col-lg-6 login-right">
                                <div class="login-header">
                                    <h3><?=$language['lg_forgot_password'] ?? ''; ?></h3>
                                    <p class="small text-muted"><?=$language['lg_enter_your_emai'] ?? ''; ?></p>
                                </div>
                                <form action="#" id="reset_password" autocomplete="off">
                                    <div class="form-group form-focus">
                                        <label class="focus-label">
                                            <?=$language['lg_email'] ?? ''; ?>
                                        </label>
                                        <input 
                                            type="email"
                                            name="resetemail" 
                                            id="resetemail" 
                                            class="form-control floating"
                                        />
                                    </div>
                                    <div class="text-right py-4">
                                        <a class="forgot-link" href="/login">
                                            <?=$language['lg_remember_your_p'] ?? ''; ?>
                                        </a>
                                    </div>
                                    <button id="reset_pwd" class="btn btn-warning btn-block btn-lg px-5" type="submit">
                                        <?=$language['lg_reset_password'] ?? ''; ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</main>
<?php $this->endSection(); ?>