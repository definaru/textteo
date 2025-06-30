<?php
use App\Libraries\SvgIcons;
    $content = isset($language['lg_footer_content_']) ? $language['lg_footer_content_'] : '';
    $under = isset($language['lg_footer_content_under']) ? $language['lg_footer_content_under'] : '';
    $email = isset($language['lg_footer_content_mail_info']) ? $language['lg_footer_content_mail_info'] : '';
?>
<footer class="footer-section bg-dark text-white py-5">
    <div class="container">
        <div class="row justify-content-between align-items-start mb-4">
            <div class="col-12 mb-4">
                <a href="/">
                    <img 
                        src="/assets/images/logo-white.png" 
                        style="margin-top: 40px;" 
                        alt="TextTeo Logo" 
                        class="img-fluid"
                        style="max-width: 180px;" 
                    />                    
                </a>
            </div>
            <div class="col-12 col-md-6">
                <div class="footer-about-content">
                    <p style="margin-bottom:0"><?=$content;?></p>
                    <br />
                    <p style="margin-top:0"><?=$under;?></p>
                    <a href="mailto:<?=$email;?>" class="text-white">
                        <?=$email;?>
                    </a>
                </div>
                <div class="mt-4 mb-5 mb-md-0">
                    <?php if(session('user_id')) {?>
                        <a href="/register" class="btn btn-outline-light fw-semibold text-uppercase px-4 py-2 sign-up-btn">
                            Sign Up
                        </a>
                    <?php } else{ ?>
                        <a href="/search-veterinary?type=1" class="btn btn-outline-light fw-semibold text-uppercase px-4 py-2 sign-up-btn">
                            For veterinarians
                        </a>
                    <?php } ?>                    
                </div>
            </div>
            <div class="col-12 col-md-6 link-footer">
                <div class="row justify-content-center align-items-start">
                    <div class="col-12 col-sm-6 col-md-6 mb-5">
                        <h5 class="fw-semibold text-white mb-4">For Clients</h5>
                        <ul class="vstack gap-md-4 gap-3 list-unstyled mb-0">
                            <li>
                                <a href="/search-veterinary" class="footer-link">Book Appointments</a>
                            </li>
                            <li>
                                <a href="/login" class="footer-link">My account</a>
                            </li>
                            <li>
                                <a href="/register" class="footer-link">New Customer</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 mb-5">
                        <h5 class="fw-semibold text-white mb-4">Contact</h5>
                        <?php /*
                        Address
                        <div class="d-flex mb-3">
                            <div class="me-2">
                                <i class="fa-solid fa-crosshairs" style="color: #d7d7d7;"></i>
                            </div>
                            <div>
                                Gate Avenu - South Zone, DIFC<br />
                                Innovation hub, Dubai, UAE
                            </div>
                        </div>                        
                        */ ?>
                        <div class="vstack gap-4">
                            <div class="d-flex align-items-start gap-3">
                                <?=SvgIcons::address(['size' => 35]);?>
                                <span>Gate Avenu - South Zone, DIFC Innovation hub, Dubai, UAE</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <?=SvgIcons::email(['size' => 20]);?>
                                <a href="mailto:info@textteo.com" class="text-decoration-none footer-link">info@textteo.com</a>
                            </div>
                            <div class="social d-flex gap-3">
                                <a href="https://www.instagram.com/textteo_ai" class="footer-social-icon">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                                <a href="https://chat.whatsapp.com/BYePyNVoPdbEM6ZF9I4Dzb" class="footer-social-icon">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                                <a href="https://twitter.com/textteo_ai" class="footer-social-icon">
                                    <i class="fa-brands fa-x-twitter"></i>
                                </a>
                                <a href="https://t.me/+cSzA1cz98TRhYTM0" class="footer-social-icon">
                                    <i class="fa-brands fa-telegram"></i>
                                </a>
                                <a href="https://www.linkedin.com/company/textteo-ai/" class="footer-social-icon">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="text-secondary opacity-50" />

        <div class="row justify-content-between align-items-center">
            <div class="col-auto d-flex gap-4 mb-2 mb-md-0 pb-1 order-1 order-md-2">
                <a href="/terms-conditions" class="link-offset-3 text-white">
                    Terms and conditions
                </a>
                <!--<a href="/privacy-policy" class="footer-link border-bottom border-secondary pb-1">
                Privacy Policy
                </a>-->
            </div>
            <div class="col-auto text-secondary text-center text-md-start order-2 order-md-1">
                <small>&copy; <?= date('Y') ?> TextTeo. All rights reserved.</small>
            </div>
        </div>
    </div>
</footer>