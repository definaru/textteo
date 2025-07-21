<?php
    use App\Libraries\SvgIcons;
    $user = session('user_id');
    $link = $user ? '/patient/profile' : '/login';
    $text = $user ? 'Log out' : 'Sign In';
?>
<header class="header-wrapper"> 
    <nav class="navbar navbar-expand-lg bg-white py-2 py-md-4"> 
        <div class="container"> 
            <button 
                class="navbar-toggler collapsed border-0 pt-0" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarCollapse" 
                aria-controls="navbarCollapse" 
                aria-expanded="false" 
                aria-label="Toggle navigation"
            > 
                <?=SvgIcons::menu(['fill' => '#252525', 'size' => 25]);?>
            </button>             
            <a class="navbar-brand" href="/">
                <?php // =SvgIcons::Logotype(['class' => 'logo']);?>
                <img src="/assets/images/logo.png" alt="TextTeo logo" />
            </a> 

            <?php if($user) { ?>
                <div class="d-md-none d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-dark border-0 position-relative p-1">
                        <?= SvgIcons::bell(['size' => 26, 'fill' => '#252525']);?> 
                        <span class="position-absolute start-75 translate-middle badge rounded-pill bg-warning" style="top: 25%;font-size: 10px;right: -23%">
                            1
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </button>
                    <div>
                        <a href="/login" class="btn btn-outline-dark p-1">
                            <?=SvgIcons::user();?>
                        </a>                          
                    </div>
                </div>
            <?php } else { ?>
                <a href="/patient/profile" class="btn btn-outline-dark p-2 d-md-none d-block">
                    <?=SvgIcons::logout();?>
                </a>
            <?php } ?>

            <div class="navbar-collapse collapse" id="navbarCollapse"> 
                <ul class="navbar-nav me-auto mb-2 mb-md-0 ms-md-5 ms-0 gap-2"> 
                    <li class="nav-item">
                        <a class="nav-link" href="/#pet-problem-section">Help now</a>
                    </li> 
                    <li class="nav-item"> 
                        <a class="nav-link" href="/#review-carousel-section">Testimonials</a> 
                    </li> 
                    <li class="nav-item"> 
                        <a class="nav-link" href="/#trusted-vets-section">Our vets</a> 
                    </li> 
                    <li class="nav-item"> 
                        <a class="nav-link" href="/#how-it-works-section">How it works</a> 
                    </li> 
                    <li class="nav-item"> 
                        <a class="nav-link" href="/#faq">FAQ</a> 
                    </li>
                </ul> 
                <div class="w-action">
                    <div class="d-flex flex-md-row flex-column align-self-stretch gap-2"> 
                        <?php if($user) { ?>
                            <button type="button" class="btn btn-outline-dark border-0 position-relative px-1 d-none d-md-block">
                                <?= SvgIcons::bell(['size' => 26, 'fill' => '#252525']);?> 
                                <span class="position-absolute start-75 translate-middle badge rounded-pill bg-warning" style="top: 25%;font-size: 10px;right: -23%">
                                    1
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </button>
                            <div class="d-none d-md-block">
                                <select class="form-select currency-btn" id="currency" aria-label="Choose currency">
                                    <option value="AED" selected>AED</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>                                
                            </div>
                        <?php } ?>
                        <a class="btn btn-outline-dark order-2 order-md-1" href="<?=$link;?>">
                            <?=SvgIcons::user(['class' => 'd-none d-md-block mt-1']);?>
                            <span class="d-md-none d-block text-uppercase fw-semibold"><?=$text;?></span>
                        </a>
                        <a class="btn btn-book-now text-decoration-none order-1 order-md-2" href="/search-veterinary?type=6">
                            Book Now
                        </a>
                    </div>                     
                </div>
            </div> 
        </div> 
    </nav>
</header>
    
