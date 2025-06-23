<!DOCTYPE html>
<html lang="en">

<!-- ======================== -->
<!-- HEAD -->
<!-- ======================== -->
<?php
if(!empty(session('redirect_activate'))){
	header("Location: https://textteo.com".session('redirect_activate'));
}
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TextTeo AI</title>
  <link href="/uploads/logo/1716626275_8357ec0a82c3394e6aa6.png" rel="icon">
  <!-- External Stylesheets -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link rel="stylesheet" href="styles.css" />

</head>

<body>

<!-- ======================== -->
<!-- HEADER -->
<!-- ======================== -->
<header class="header-wrapper">
  <div class="header-container">
    <!-- Mobile Menu Icon (Only visible on mobile/tablet) -->
    <button id="menu-icon" class="menu-icon d-lg-none" aria-label="Toggle Menu">
      <img src="assets/images/Icon svg/Interface icon/all/menu.svg" alt="Menu icon" />
    </button>

    <!-- Desktop Layout -->
    <div class="desktop-layout d-none d-lg-flex">
      <div class="header-left">
        <div class="logo">
          <a href="/"><img src="assets/images/logo.png" alt="TextTeo logo" /></a>
        </div>
      </div>
      <nav class="header-nav">
        <ul>
          <li><a href="#pet-problem-section">Help now</a></li>
          <li><a href="#review-carousel-section">Testimonials</a></li>
          <li><a href="#trusted-vets-section">Our vets</a></li>
          <li><a href="#how-it-works-section">How it works</a></li>
          <li><a href="#faq">FAQ</a></li>
        </ul>
      </nav>
    </div>

    <!-- Mobile/Tablet Layout -->
    <div class="mobile-layout d-lg-none">
      <div class="logo-center">
        <img src="assets/images/logo.png" alt="TextTeo logo" />
      </div>
    </div>

    <!-- Right Group (Desktop & Mobile) -->
    <div class="header-right">
      <a class="btn-login" href="/login">
        <i class="far fa-user"></i>
      </a>
 
      <a href="/search-veterinary?type=6"><button class="btn-book-now d-none d-lg-block" >Book Now</button></a>
    
    </div>

    <!-- Mobile Navigation -->
    <div id="mobile-nav" class="mobile-nav">
      <div class="mobile-nav-header">
        <button id="menu-icon2" class="menu-icon d-lg-none" aria-label="Toggle Menu">
          <img src="assets/images/Icon svg/Interface icon/all/menu.svg" alt="Menu icon" />
        </button>
        <div class="mobile-nav-logo">
          <img src="assets/images/logo.png" alt="TextTeo logo" />
        </div>
      </div>
      <ul>
        <li><a href="#pet-problem-section" class="close-menu">Help now</a></li>
        <li><a href="#review-carousel-section" class="close-menu">Testimonials</a></li>
        <li><a href="#trusted-vets-section" class="close-menu">Our vets</a></li>
        <li><a href="#how-it-works-section" class="close-menu">How it works</a></li>
        <li><a href="#faq" class="close-menu">FAQ</a></li>
      </ul>
      <div class="mobile-nav-footer">
        <button class="btn-mobile-book close-menu">BOOK NOW</button>
        <button class="btn-mobile-login close-menu">LOG IN</button>
      </div>
    </div>

    <!-- Mobile Nav Overlay -->
    <div id="mobile-nav-overlay" class="mobile-nav-overlay"></div>
  </div>
</header>

<!-- ======================== -->
<!-- HERO SECTION -->
<!-- ======================== -->
<section class="hero-section position-relative">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <h1 class="hero-title">
          Your pet's health is a click away – 24/7 vet care
        </h1>
        <p class="hero-subtitle">
          Chat with certified vets anytime, anywhere. Register now and enjoy
          <span class="text-warning fw-semibold">50% off</span> your first consultation!
        </p>
        <a href="<?php echo base_url(); ?>search-veterinary?type=1"><button class="btn btn-warning px-4 py-2 text-uppercase fw-bold" formaction="/register">
          Get Started Now
        </button></a>
      </div>
      <div class="col-md-6 text-center position-relative">
        <img src="assets/images/Desktop/Hero/Main image.png" alt="Phone mockup with woman and dog"
             class="img-fluid" />
      </div>
    </div>
  </div>
</section>

<!-- ======================== -->
<!-- SERVICES SECTION -->
<!-- ======================== -->
<section class="services-section"  id="services-section">
  <div class="container">
    <!-- Title -->
    <div class="services-title row">
      <h2 class="col-md-6">Help for your pet is always at hand</h2>
    </div>
    <!-- Cards -->
    <div class="services-cards">
      <!-- Card 1 -->
      <div class="service-card">
        <div class="card-image">
          <img src="assets/images/Desktop/2 block/Certified.png" alt="Certified Veterinarian" />
        </div>
        <div class="card-content">
          <h3>Qualified Veterinarians</h3>
          <p>Expert care from trusted professionals with top reviews.</p>
        </div>
      </div>
      <!-- Card 2 -->
      <div class="service-card">
        <div class="card-image">
          <img src="assets/images/Desktop/2 block/Call.png" alt="Instant Help" />
        </div>
        <div class="card-content">
          <h3>Instant Help</h3>
          <p>24/7 support via chat or video, no waiting or travel required.</p>
        </div>
      </div>
      <!-- Card 3 -->
      <div class="service-card">
        <div class="card-image">
          <img src="assets/images/Desktop/2 block/Certified.png" alt="Treatment History" />
        </div>
        <div class="card-content">
          <h3>Treatment History at Hand</h3>
          <p>Convenient access to medical history and treatment details.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================== -->
<!-- DISCOUNT SECTION -->
<!-- ======================== -->
<section class="discount-section">
  <div class="container">
    <div class="discount-wrapper">
      <div class="discount-content">
        <h2>Get 50% discount</h2>
        <p>Register now and get a discount on your first visit</p>
        <a href="/register"><button class="btn-discount">Get a discount</button></a>
      </div>
      <div class="discount-image">
        <img src="assets/images/Desktop/Banners/vet and pets.png" alt="Vet and Pets" />
      </div>
    </div>
  </div>
</section>

<!-- ======================== -->
<!-- WHAT'S BOTHERING YOUR PET? SECTION -->
<!-- ======================== -->
<section class="pet-problem-section" id="pet-problem-section">
  <div class="container">

    <div class="pet-problem-header">
      <h2>What’s bothering your pet?</h2>
      <p>Choose a problem and our AI assistant will select the best doctor for you</p>
    </div>

    <div class="pet-problem-content">
      <!-- Desktop arrows (hidden on mobile/tablet) -->
      <div class="problem-arrows d-none d-lg-flex">
        <button class="arrow arrow-left">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
        <button class="arrow arrow-right">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>

      <!-- Problem cards wrapper -->
      <div class="problem-cards">
        <!-- Card 1 -->
        <div class="problem-card">
          <div class="card-icon">
            <!-- Use your icon/image here -->
            <img src="assets/images/Icon svg/pain icons/beetle 1.svg" alt="Skin icon"
                 class="icon-img" />
          </div>
          <div class="card-text">
            <h3>Skin Issues</h3>
            <button class="card-arrow">
              <a href="/search-veterinary"><i class="fa-solid fa-chevron-right"></i></a>
            </button>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="problem-card">
          <div class="card-icon">
            <img src="assets/images/Icon svg/pain icons/dental.svg" alt="Dental icon"
                 class="icon-img" />
          </div>
          <div class="card-text">
            <h3>Dental Issues</h3>
            <button class="card-arrow">
              <a href="/search-veterinary"><i class="fa-solid fa-chevron-right"></i></a>
            </button>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="problem-card">
          <div class="card-icon">
            <img src="assets/images/Icon svg/pain icons/stomach 1.svg" alt="Trauma icon"
                 class="icon-img" />
          </div>
          <div class="card-text">
            <h3>Trauma</h3>
            <button class="card-arrow">
              <a href="/search-veterinary"><i class="fa-solid fa-chevron-right"></i></a>
            </button>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="problem-card">
          <div class="card-icon">
            <img src="assets/images/Icon svg/pain icons/beetle 1.svg" alt="Parasites icon"
                 class="icon-img" />
          </div>
          <div class="card-text">
            <h3>Parasites</h3>
            <button class="card-arrow">
              <a href="/search-veterinary"><i class="fa-solid fa-chevron-right"></i></a>
            </button>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="problem-card">
          <div class="card-icon">
            <img src="assets/images/Icon svg/pain icons/infection 1.svg" alt="Ear Infections icon"
                 class="icon-img" />
          </div>
          <div class="card-text">
            <h3>Ear Infections</h3>
            <button class="card-arrow">
              <a href="/search-veterinary"><i class="fa-solid fa-chevron-right"></i></a>
            </button>
          </div>
        </div>

        <!-- Card 6 -->
        <div class="problem-card">
          <div class="card-icon">
            <img src="assets/images/Icon svg/pain icons/bone-fracture (1) 1.svg" alt="Vaccinations icon"
                 class="icon-img" />
          </div>
          <div class="card-text">
            <h3>Vaccinations</h3>
            <button class="card-arrow">
              <a href="/search-veterinary"><i class="fa-solid fa-chevron-right"></i></a>
            </button>
          </div>
        </div>

        <!-- Card 7 -->
        <div class="problem-card">
          <div class="card-icon">
            <img src="assets/images/Icon svg/pain icons/stomach 2.svg" alt="Stomach Issues icon"
                 class="icon-img" />
          </div>
          <div class="card-text">
            <h3>Stomach Issues</h3>
            <button class="card-arrow">
              <a href="/search-veterinary"><i class="fa-solid fa-chevron-right"></i></a>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ======================== -->
<!-- YOUR TRUST, OUR PRIDE SECTION -->
<!-- ======================== -->
<section class="review-carousel-section position-relative" id="review-carousel-section">
  <!-- Optional wavy background shape -->
  <div class="review-bg"></div>

  <div class="container position-relative">
    <!-- Slider wrapper (overflow hidden) -->
    <div class="review-slider-wrapper">
      <!-- Slider track (all slides side by side) -->
      <div class="review-slider-track">

        <!-- ========= SLIDE 1 ========= -->
        <div class="review-slide">
          <div class="row align-items-center">
            <!-- Left Column -->
            <div class="col-lg-6 col-12">
              <h2>Your trust, our pride</h2>
              <h3>“The process was so quick and convenient.”</h3>
              <p>
                Fantastic experience! I had a question about my cat’s behavior,
                and the vet provided helpful insights and practical tips right away.
                The process was so quick and convenient. Highly recommend!
              </p>
              <div class="mt-3">
                <p class="reviewer-name mb-0">Eric West</p>
                <p class="reviewer-dog mt-1">and his dog Finn</p>
                <div class="d-flex align-items-center gap-2 mt-1">
                  <i class="fa-brands fa-instagram" style="font-size:16px;"></i>
                  <span>@eric</span>
                </div>
              </div>
            </div>
            <!-- Right Column (Image) -->
            <div class="col-lg-6 col-12 text-center mt-4 mt-lg-0">
              <img src="assets/images/Desktop/your trust/Dog img.png" alt="Dog image"
                   class="img-fluid rounded-3" />
            </div>
          </div>
        </div>

        <!-- ========= SLIDE 2 ========= -->
        <div class="review-slide">
          <div class="row align-items-center">
            <div class="col-lg-6 col-12 position-relative">
              <h2>Your trust, our pride</h2>
              <h3>“The process was easy, and I got clear care instructions.”</h3>
              <p>
                Great service! My dog had an allergic reaction, and I got
                a vet consultation within minutes. The vet provided quick advice
                and a treatment plan. Will definitely use TextTeo again!
              </p>
              <div class="mt-3">
                <p class="reviewer-name mb-0">William Daniels</p>
                <p class="reviewer-dog mt-1">and his dog Ray</p>
                <div class="d-flex align-items-center gap-2 mt-1">
                  <i class="fa-brands fa-instagram" style="font-size:16px;"></i>
                  <span>@william</span>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-12 text-center mt-4 mt-lg-0">
              <img src="assets/images/Desktop/your trust/Dog img.png" alt="Dog 2"
                   class="img-fluid rounded-3" />
            </div>
          </div>
        </div>

        <!-- ========= SLIDE 3 ========= -->
        <div class="review-slide">
          <div class="row align-items-center">
            <div class="col-lg-6 col-12">
              <h2>Your trust, our pride</h2>
              <h3>“Such a smooth experience from start to finish!”</h3>
              <p>
                The vet provided immediate guidance and solutions. TextTeo
                is a lifesaver—no more waiting rooms. Highly recommend
                for every pet parent!
              </p>
              <div class="mt-3">
                <p class="reviewer-name mb-0">Alex Johnson</p>
                <p class="reviewer-dog mt-1">and her dog Bella</p>
                <div class="d-flex align-items-center gap-2 mt-1">
                  <i class="fa-brands fa-instagram" style="font-size:16px;"></i>
                  <span>@alex</span>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-12 text-center mt-4 mt-lg-0">
              <img src="assets/images/Desktop/your trust/Dog img.png" alt="Dog 3"
                   class="img-fluid rounded-3" />
            </div>
          </div>
        </div>

      </div> <!-- END review-slider-track -->
    </div> <!-- END review-slider-wrapper -->

    <!-- SINGLE NAV (for all slides) -->
    <div class="review-nav d-flex align-items-center justify-content-between">
      <!-- Dots (initially slide 0 is active) -->
      <div class="dots-wrapper d-flex gap-2">
        <div class="dot active" data-slide="0"></div>
        <div class="dot" data-slide="1"></div>
        <div class="dot" data-slide="2"></div>
      </div>
      <!-- Arrows -->
      <div class="arrows-wrapper d-flex gap-2">
        <button class="arrow arrow-left">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
        <button class="arrow arrow-right">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>
    </div>

  </div><!-- END container -->
</section>

<!-- ======================== -->
<!-- TRUSTED VETS SECTION -->
<!-- ======================== -->
<section class="trusted-vets-section py-5" id="trusted-vets-section">
  <div class="container">
    <!--
.flex-column-reverse.flex-lg-row ensures that on mobile, the text appears first,
and stats appear second. On lg screens, it reverts to text on the right.
You can remove .flex-column-reverse if you prefer stats above text on mobile.
-->
    <div class="row align-items-center flex-column-reverse flex-lg-row">

      <!-- LEFT: Stats -->
      <!--
col-12 => full width on mobile
col-lg-5 => 5 columns on large screens
-->
      <div class="col-12 col-lg-5">
        <!-- We make each stat 6 columns wide on mobile, 4 columns on md:
   that shows up to 3 side by side on tablets and above.
   On phones, you get 2 stats per row. -->
        <div class="row g-4 text-center text-md-start justify-content-center justify-content-md-start">

          <!-- STAT #1 -->
          <div class="col-6 col-md-4">
            <div class="trusted-vet-stat">
              <div class="trusted-vet-icon mb-2">
                <img src="assets/images/Desktop/Icon about vets png/Icon.png" alt="Expertise icon"
                     class="img-fluid" />
              </div>
              <h3 class="trusted-vet-number">5+</h3>
              <p class="trusted-vet-label">Expertise</p>
            </div>
          </div>

          <!-- STAT #2 -->
          <div class="col-6 col-md-4">
            <div class="trusted-vet-stat">
              <div class="trusted-vet-icon mb-2">
                <img src="assets/images/Desktop/Icon about vets png/Icon-1.png" alt="Rating icon"
                     class="img-fluid" />
              </div>
              <h3 class="trusted-vet-number">4.9</h3>
              <p class="trusted-vet-label">Avg. Rating</p>
            </div>
          </div>

          <!-- STAT #3 -->
          <div class="col-6 col-md-4">
            <div class="trusted-vet-stat">
              <div class="trusted-vet-icon mb-2">
                <img src="assets/images/Desktop/Icon about vets png/Icon-2.png"
                     alt="Certified vets icon" class="img-fluid" />
              </div>
              <h3 class="trusted-vet-number">100%</h3>
              <p class="trusted-vet-label">Certified Vets</p>
            </div>
          </div>

        </div><!-- end row for stats -->
      </div><!-- end .col-lg-5 -->

      <!-- RIGHT: Text -->
      <!--
offset-lg-2 => push text further right on large screens
text-center text-lg-start => center text on mobile, left-align on large
-->
      <div class="col-12 col-lg-5 offset-lg-2 text-center text-lg-start mb-4 mb-lg-0">
        <h2 class="trusted-vets-title">Your trusted vets</h2>
        <p class="trusted-vets-description mb-0">
          At TextTeo, your pet’s health is our priority. Our certified
          veterinarians are highly qualified professionals, thoroughly vetted
          to meet the highest standards. You can trust our expert team to
          provide the best care and advice for your pet.
        </p>
      </div><!-- end .col-lg-5.offset-lg-2 -->

    </div><!-- end .row -->
  </div><!-- end .container -->
</section>

<!-- ======================== -->
<!-- HOW IT WORKS SECTION -->
<!-- ======================== -->
<section class="how-it-works-section py-5" id="how-it-works-section">
  <div class="container">

    <!-- ======================== -->
    <!-- 1) MOBILE-ONLY PHONE SLIDER -->
    <!-- shown on < 992px screens, hidden on desktop -->
    <!-- ======================== -->
    <div class="mobile-slider-wrapper d-lg-none mb-4 text-center">
      <!-- Phone slider (3 images stacked) -->
      <div class="mobile-phone-slider position-relative d-inline-block">
        <img src="assets/images/Desktop/steps/iPhone 13 Pro Max.png" alt="Mobile step 1"
             class="mobile-slide active" data-slide="1" />
        <img src="assets/images/Desktop/steps/iPhone 13 Pro Max-1.png" alt="Mobile step 2"
             class="mobile-slide" data-slide="2" />
        <img src="assets/images/Desktop/steps/iPhone 13 Pro Max-2.png" alt="Mobile step 3"
             class="mobile-slide" data-slide="3" />
      </div>

      <!-- 3-dot nav (mobile only) -->
      <div class="mobile-dots mt-2">
        <span class="dot active" data-slide="1"></span>
        <span class="dot" data-slide="2"></span>
        <span class="dot" data-slide="3"></span>
      </div>
    </div>
    <!-- end mobile-slider-wrapper -->

    <!-- ======================== -->
    <!-- 2) DESKTOP LAYOUT (≥ 992px) -->
    <!-- hidden on mobile -->
    <!-- ======================== -->
    <div class="row align-items-center d-none d-lg-flex">

      <!-- LEFT: Title + Steps + CTA -->
      <div class="col-12 col-lg-6 mb-4 mb-lg-0">
        <h2 class="hiw-title mb-4">How it works</h2>

        <!-- Desktop Steps -->
        <ul class="hiw-steps list-unstyled">
          <li class="hiw-step active" data-step="1">
            <div class="step-number">01</div>
            <div class="step-text">
              <h3>Tell us about your pet</h3>
              <p>
                Provide a few details about your pet and their needs,
                so we can connect you with the right veterinarian.
              </p>
            </div>
          </li>
          <li class="hiw-step" data-step="2">
            <div class="step-number">02</div>
            <div class="step-text">
              <h3>Book a convenient time</h3>
              <p>
                Choose an available time slot that fits your schedule,
                and easily book your consultation in just a few clicks.
              </p>
            </div>
          </li>
          <li class="hiw-step" data-step="3">
            <div class="step-number">03</div>
            <div class="step-text">
              <h3>Connect to expert care</h3>
              <p>
                Join your vet’s video call or chat session at the scheduled time
                and receive personalized advice and treatment recommendations.
              </p>
            </div>
          </li>
        </ul>

        <!-- CTA Row -->
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center mt-4 gap-3">
          <a href="/register"><button class="btn btn-warning btn-lg text-uppercase fw-bold px-4 py-2">
            Start now
          </button></a>
          <div class="cta-text fs-5 text-warning fw-normal">
            Register now and get 50% discount:
          </div>
        </div>
      </div><!-- end col-lg-6 -->

      <!-- RIGHT: Desktop phone mockup -->
      <div class="col-12 col-lg-6 text-center text-lg-end">
        <div class="phone-mockup position-relative d-inline-block">
          <img src="assets/images/Desktop/steps/iPhone 13 Pro Max.png" alt="Step 1 mockup"
               class="step-image active" data-step="1" />
          <img src="assets/images/Desktop/steps/iPhone 13 Pro Max-1.png" alt="Step 2 mockup"
               class="step-image" data-step="2" />
          <img src="assets/images/Desktop/steps/iPhone 13 Pro Max-2.png" alt="Step 3 mockup"
               class="step-image" data-step="3" />
        </div>
      </div><!-- end col-lg-6 -->

    </div><!-- end row (desktop) -->


    <!-- ======================== -->
    <!-- 3) MOBILE STEPS + CTA -->
    <!-- also shown only on mobile (d-lg-none) -->
    <!-- ======================== -->
    <div class="mobile-steps d-lg-none">
      <h2 class="hiw-title mb-4">How it works</h2>
      <ul class="hiw-steps list-unstyled">
        <li class="hiw-step active" data-step="1">
          <div class="step-number">01</div>
          <div class="step-text">
            <h3>Tell us about your pet</h3>
            <p>
              Provide a few details about your pet and their needs,
              so we can connect you with the right veterinarian.
            </p>
          </div>
        </li>
        <li class="hiw-step" data-step="2">
          <div class="step-number">02</div>
          <div class="step-text">
            <h3>Book a convenient time</h3>
            <p>
              Choose an available time slot that fits your schedule,
              and easily book your consultation in just a few clicks.
            </p>
          </div>
        </li>
        <li class="hiw-step" data-step="3">
          <div class="step-number">03</div>
          <div class="step-text">
            <h3>Connect to expert care</h3>
            <p>
              Join your vet’s video call or chat session at the scheduled time
              and receive personalized advice and treatment recommendations.
            </p>
          </div>
        </li>
      </ul>
      <!-- CTA (mobile) -->
      <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center mt-4 gap-3">
        <a href="/register"><button class="btn btn-warning btn-lg text-uppercase fw-bold px-4 py-2">
          Start now
        </button></a>
        <div class="cta-text fs-5 text-warning fw-normal">
          Register now and get 50% discount:
        </div>
      </div>
    </div>
    <!-- end mobile-steps -->

  </div><!-- end container -->
</section>

<!-- ======================== -->
<!-- WHY CHOOSE US SECTION -->
<!-- ======================== -->
<section class="why-choose-us-section position-relative">
  <!-- Wavy background shape -->
  <div class="why-bg"></div>

  <!-- Content Container -->
  <div class="container position-relative" style="z-index: 1;">
    <div class="row">
      <div class="col-12">
        <h2 class="why-title">
          Why Choose Us?
        </h2>
        <p class="why-subtitle">
          Ask Teo for quick help in finding the right product or service, from veterinary care to
          insurance.
          Fast and always available!
        </p>
      </div>
    </div>

    <!-- Cards Row -->
    <div class="row g-4 mt-4">
      <!-- Card 1 -->
      <div class="col-lg-4 col-md-6">
        <div class="why-card">
          <div class="why-icon">
            <!-- Replace with your own icon -->
            <img src="assets/images/Desktop/icons/bot.png" alt="AI Assistant" />
          </div>
          <div class="why-card-text">
            <h3>Your AI assistant</h3>
            <p>
              Ask Teo for quick help in finding the right product or service, from veterinary care to
              insurance.
              Fast and always available!
            </p>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-lg-4 col-md-6">
        <div class="why-card">
          <div class="why-icon">
            <!-- Replace with your own icon -->
            <img src="assets/images/Desktop/icons/contract.png" alt="Ongoing Treatment" />
          </div>
          <div class="why-card-text">
            <h3>Ongoing treatment support</h3>
            <p>
              Receive personalized treatment plans and follow-up consultations to ensure your pet’s
              health
              is always monitored and well-managed.
            </p>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-lg-4 col-md-6">
        <div class="why-card">
          <div class="why-icon">
            <!-- Replace with your own icon -->
            <img src="assets/images/Desktop/icons/trust.png" alt="Comfort and Trust" />
          </div>
          <div class="why-card-text">
            <h3>Total comfort and trust</h3>
            <p>
              With round-the-clock expert advice from TextTeo, you can trust that your pet’s health
              is always in safe and capable hands.
            </p>
          </div>
        </div>
      </div>
    </div><!-- end row -->
  </div><!-- end container -->
</section>

<!-- ======================== -->
<!-- FAQ SECTION -->
<!-- ======================== -->
<section class="faq-section" id="faq">
  <div class="faq-wrapper">
    <h2 class="faq-title">FAQ</h2>

    <!-- FAQ ITEM 1 (open by default) -->
    <div class="faq-item open">
      <div class="faq-question">
        <span>What steps do I need to follow to schedule an appointment?</span>
        <i class="faq-icon fa-solid fa-chevron-down"></i>
      </div>
      <div class="faq-answer">
        <p>
          To book an online consultation with a veterinarian, select
          the "Book an appointment" section. Select a veterinarian,
          then a convenient date and time. If you are not registered,
          create an account. You will receive a confirmation email
          with a link to the consultation.
        </p>
      </div>
      <hr class="faq-divider" />
    </div>

    <!-- FAQ ITEM 2 -->
    <div class="faq-item">
      <div class="faq-question">
        <span>Is the consultation via video or chat?</span>
        <i class="faq-icon fa-solid fa-chevron-down"></i>
      </div>
      <div class="faq-answer">
        <p>
          Your consultation can be via video or chat—whichever you prefer!
          You’ll be able to communicate directly with the vet.
        </p>
      </div>
      <hr class="faq-divider" />
    </div>

    <!-- FAQ ITEM 3 -->
    <div class="faq-item">
      <div class="faq-question">
        <span>How do I know if the vet is qualified to handle my pet’s issue?</span>
        <i class="faq-icon fa-solid fa-chevron-down"></i>
      </div>
      <div class="faq-answer">
        <p>
          All vets on TextTeo are certified professionals who have been vetted
          to meet the highest standards. You can check their credentials
          and user reviews on their profile before booking.
        </p>
      </div>
      <hr class="faq-divider" />
    </div>

    <!-- FAQ ITEM 4 -->
    <div class="faq-item">
      <div class="faq-question">
        <span>How quickly can I get in touch with a vet in case of an emergency?</span>
        <i class="faq-icon fa-solid fa-chevron-down"></i>
      </div>
      <div class="faq-answer">
        <p>
          We have 24/7 coverage. Once you book an emergency consultation, the
          system will connect you to the first available veterinarian as soon
          as possible.
        </p>
      </div>
      <hr class="faq-divider" />
    </div>

    <!-- FAQ ITEM 5 -->
    <div class="faq-item">
      <div class="faq-question">
        <span>Are there different pricing options or packages for consultations?</span>
        <i class="faq-icon fa-solid fa-chevron-down"></i>
      </div>
      <div class="faq-answer">
        <p>
          Yes. We offer both one-time consultations and subscription-based
          plans with discounted rates. You can select the option that suits
          your needs.
        </p>
      </div>
      <hr class="faq-divider" />
    </div>
  </div> <!-- end .faq-wrapper -->
</section>
<section class="cta-section">
  <div class="container">
    <div class="cta-hero position-relative overflow-hidden rounded-4">

      <!-- Background image filling the container -->
      <img src="assets/images/Desktop/CTA banner/Image (7) (1).png" alt="Happy dog" class="cta-bg-img" />

      <!-- Left-to-right gradient to darken left side for text contrast -->
      <div class="cta-gradient"></div>

      <!-- Text + button on the left -->
      <div class="cta-content">
        <h2 class="cta-title">Better Care Starts Here</h2>
        <p class="cta-subtitle">
          We help you become better for your pets.
          Ready to join our large community?
        </p>
        <a href="/register"><button class="cta-button">JOIN NOW</button></a>
      </div>
    </div><!-- end .cta-hero -->
  </div><!-- end .container -->
</section>

<footer class="footer-section bg-dark text-white pt-5 pb-4">
  <div class="container">
    <div class="row justify-content-between align-items-start">
      <!-- Left Column: Logo, short text, and Sign Up button -->
      <div class="col-12 col-md-6 mb-4">
        <!-- Logo -->
        <div class="mb-3">
          <img src="assets/images/logo-white.png" alt="TextTeo Logo" class="img-fluid"
               style="max-width: 180px;" />
        </div>

        <!-- Short paragraph (added 'logo-paragraph' class) -->
        <p class="mb-4 logo-paragraph">
          We understand that your pet's health is a priority, and accessing veterinary care should be convenient and reliable.  Please reach out to us for collaborations 
          <a href= "mailto:marketing@textteo.com">marketing@textteo.com</a>
        </p>

        <!-- Sign Up button -->
        <a href="/register"><button class="btn btn-outline-light fw-semibold text-uppercase px-4 py-2 sign-up-btn">
          Sign Up
        </button></a>
      </div>

      <div class="col-12 col-md-6 mb-4">
        <div class="row justify-content-center align-items-start">
          <!-- Middle Column: For Clients section -->
          <div class="col-12 col-sm-6 col-md-6 mb-4">
            <h5 class="fw-semibold text-white mb-3">For Clients</h5>
            <ul class="list-unstyled mb-0">
              <li class="mb-2">
                <a href="/search-veterinary" class="footer-link">Search for Veterinarians</a>
              </li>
              <li class="mb-2">
                <a href="/login" class="footer-link">My account</a>
              </li>
              <li class="mb-2">
                <a href="/register" class="footer-link">New Customer</a>
              </li>
            </ul>
          </div>

          <!-- Right Column: Contact info -->
          <div class="col-12 col-sm-6 col-md-6 mb-4">
            <h5 class="fw-semibold text-white mb-3">Contact</h5>
            <!-- Address -->
            <div class="d-flex mb-3">
              <!--<div class="me-2">
                <i class="fa-solid fa-crosshairs" style="color: #d7d7d7;"></i>
              </div>-->
              <!--<div>
                Gate Avenu - South Zone, DIFC<br />
                Innovation hub, Dubai, UAE
              </div>-->
            </div>
            <!-- Email -->
            <div class="d-flex mb-3">
              <div class="me-2">
                <i class="fa-regular fa-envelope" style="color: #d7d7d7;"></i>
              </div>
              <div><a href= "mailto:info@textteo.com">info@textteo.com</a></div>
            </div>
            <!-- Social Icons row -->
            <div class="d-flex gap-3">
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
    </div><!-- end row -->

    <!-- Horizontal line -->
    <hr class="text-secondary opacity-50" />

    <!-- Bottom row: reorder columns on mobile so copyright is last -->
    <div class="row justify-content-between align-items-center">
      <div class="col-auto d-flex gap-4 order-1 order-md-2 mb-2 mb-md-0">
        <a href="/terms-conditions" class="footer-link border-bottom border-secondary pb-1">
          Terms and conditions
        </a>
        <!--<a href="/privacy-policy" class="footer-link border-bottom border-secondary pb-1">
          Privacy Policy
        </a>-->
      </div>
      <div class="col-auto order-2 order-md-1 text-secondary text-center text-md-start">
        <small>© 2025 TextTeo. All rights reserved.</small>
      </div>
    </div>
  </div><!-- end container -->
</footer>


<!-- ======================== -->
<!-- SCRIPTS -->
<!-- ======================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>

</html>