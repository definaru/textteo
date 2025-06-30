<?php 
    $this->extend('user/layout/header'); 
    $current_timezone = session('time_zone');
    $user = user_detail(session('user_id'));
?>
<?php $this->section('content'); ?>
<main class="pt-4 pb-5">

</main>
<div class="page-dcotor-all-content">
    <style>
        #schedule_date,#timezone{
        background-color:#F7F7F7;
        }

        ul.nav.nav-tabs.nav-tabs-bottom.nav-justified {
            background-color: #F7F7F7 !important;
            border: none !important;
        }

        ul.nav-tabs.nav-tabs-bottom > li.nav-item {
            border: none !important;
        }

        ul.nav-tabs.nav-tabs-bottom > li.nav-item > a.nav-link {
            color: #333 !important;
            border: none !important;
            padding: 10px 15px !important;
            transition: border-bottom 0.2s, color 0.2s !important;
        }

        ul.nav-tabs.nav-tabs-bottom > li.nav-item > a.nav-link:hover,
        ul.nav-tabs.nav-tabs-bottom > li.nav-item > a.nav-link:focus {
            border-bottom: 2px solid #FD9720 !important;
            color: #FD9720 !important;
            text-decoration: none !important;
        }

        ul.nav-tabs.nav-tabs-bottom > li.nav-item > a.nav-link.active {
            border-bottom: 2px solid #FD9720 !important;
            color: #FD9720 !important;
            font-weight: bold !important;
        }


        /* overview */

        .verified-specialists .verified-specialists {
            margin-top: 30px;
            font-family: 'Poppins', sans-serif;
        }

        .verified-specialists .verified-specialists h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #000;
        }

        .verified-specialists .specialist-cards {
            display: flex;
            flex-wrap: nowrap; /* prevent wrapping */
            gap: 16px;
            overflow-x: auto; /* horizontal scroll if overflow */
            padding-bottom: 10px;
        }

        .verified-specialists .card {
            flex: 0 0 auto; /* prevent shrinking */
            width: 200px; /* fixed width for each card */
            background-color: #fff;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 14px;
            color: #000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            white-space: nowrap;
        }

        .verified-specialists .card i {
            font-size: 20px;
            color: #FD9720; /* gold color for icons */
        }


        .how-to-use {
            background-color: #F7F7F7;
            padding: 0px;
            border-radius: 20px;
            font-family: 'Poppins', sans-serif;
            max-width: 600px;
            margin: 0;
        }

        .how-to-use-h2 {
            font-weight: 600;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .step {
            display: flex;
            background-color: #FFFFFF;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 16px;
            align-items: flex-start;
        }

        .step-icon {
            background-color: #FFEFDA;
            color: #FD9720;
            font-weight: bold;
            border-radius: 8px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-right: 16px;
            padding: 5%;
        }

        .step-text strong {
            display: block;
            font-weight: 600;
            font-size: 16px;
            color: #000;
            margin-bottom: 4px;
        }

        .step-text p {
            margin: 0;
            font-size: 14px;
            color: #666;
            line-height: 1.4;
        }

        .slots-grid-mobile{
            display: flex !important;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px !important;
            justify-content: center;
            padding: 3%;
            overflow-x: auto !important;
        }
        .timeline-content {background-color:#F7F7F7}
        .doc-info-right{
            width: 100%;
            background-color:#FFFFFF;
        }
        .slot-container {
            width: 100%;
            max-width: 350px;
            margin: 0 auto;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            background-color:#FFFFFF;
            border-radius: 20px;
        }

        .slot-header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .slot-header input,
        .slot-header select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 12px;
        }

        .slots-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            justify-content: center;
            padding:3%;
        }

        .arrow-back-btn-and-summary-original-mobile{
            width:100% !important;
        }

        .slot {
            padding: 2px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 400;
            cursor: pointer;
            transition: all 0.3s ease;
            color:#252525
        }

        .slot:hover {
            border-color: #FD9720;
            color: #FD9720;
        }

        .slot.active {
            border: 2px solid #FD9720;
            color: #FD9720;
            font-weight: bold;
        }

        .see-more {
            margin-top: 10px;
            font-weight: 400;
            color: #252525;
            cursor: pointer;
            text-align: center;
        }

        /* Popup Modal */
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

        .doc-department{
            color:#252525;
        }

        .slot-booked{
            pointer-events: none; /* Disable clicks */
            opacity: 0.5;          /* Make it look faded */
            background-color: #ccc; /* Optional: Grey background */
            cursor: not-allowed; 
            padding: 8px;
            border: 1px dashed #999;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 400;
        }
        .no-token {
            width: 100%;
            text-align: center;
            font-size: 18px;
            color: #555;
            margin-top: 0px;
        }

        .specialist-cards-mobile{
            flex-direction: column;
        }

        .verified-specialists-mobile{
            align-items: flex-start !important;
            width: 100% !important;
        }

        .rating li {
        font-size: 14px;
        }

        .arrow-back-btn-original{
            padding: 1%;
            border: 1px solid #E1E1E1;
            color: #252525;
            height: 5%;
            border-radius: 10%;   
        }
        .arrow-back-btn-and-summary-original{padding-left: 2%}

        .arrow-btn-original{
            font-size:25px;
            font-weight: bold;
        }

        @media (min-width: 1080px) {
            .arrow-back-btn-original{padding: 0%}
        }

        @media (max-width: 576px) {
            .doc-info-right{margin-right: 0}
            .arrow-back-btn-original{
                width: 10%;
                padding: 1%;
            }
            .arrow-back-btn-and-summary-original{padding-left: 6%}
            .arrow-btn-original{padding: 0%}
        }

        @media (max-width: 768px) {
            .arrow-back-btn-and-summary-original {width: 100% !important}
        }

        @media (max-width: 500px){
            .doctor-info-main{margin-left: 12% !important}
        }

        @media (max-width: 430px){
            .doctor-info-main{margin-left: 30% !important}
        }

        @media (max-width: 320px){
            .doctor-info-main{margin-left: 38% !important}
        }

        #register .custom-popup {
            border-radius: 20px;
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        .doc-overview-web{text-align: start}
        #register .custom-popup .modal-body {padding: 30px}
        #register .custom-popup input::placeholder {color: #bbb}
        #register .custom-popup .btn-warning {
            background-color: #FD9720;
            border: none;
        }
        #register .custom-popup .btn-warning:hover {background-color: #FD9720}
        #register .custom-popup .btn i {margin-right: 5px}
        #change_password h2 {
            font-weight: 500;
            font-size: 24px;
            color: #252525;
            text-align: center;
            width:100%;
            margin-bottom: 10px;
        }

        #change_password p {
            font-weight: 400;
            font-size: 16px;
            color: #757575;
            text-align: center;
            margin-bottom: 0;
        }
        #change_password {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            max-width: 100%;
            margin: auto;
        }
        #change_password .form-group {
            position: relative;
            margin-bottom: 25px;
        }

        #change_password label {
            display: block;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }
        #change_password input.form-control {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: 0.3s ease;
            font-size: 15px;
        }

        #change_password input.form-control:focus {
            border-color: #FD9720;
            box-shadow: 0 0 0 2px rgba(248, 156, 28, 0.2);
        }
        #change_password span.far.fa-eye {
            position: absolute;
            right: 20px;
            top: 65%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 18px;
        }
        #change_password .submit-section {text-align: center}
        #change_password .submit-btn {
            background-color: #FD9720;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            border: none;
            width: 100%;
            transition: 0.3s ease;
        }
        #change_password .submit-btn:hover {background-color: #FD9720}
        .booking-section .consultation-type {
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 0;
        }
    </style>
    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">
                                    <?=$language['lg_home'] ?? ""; ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?php
                                    if ($doctors['role'] == 6) { // clinic
                                        echo $language['lg_clinic_profile'] ?? "";
                                    } elseif ($doctors['role'] == 1) { // doctor
                                        echo $language['lg_book_appointmen'] ?? "";
                                    } elseif ($doctors['role'] == 4) { // lab
                                        echo $language['lg_lab_profile'] ?? "";
                                    } elseif ($doctors['role'] == 5) { // pharmacy
                                        echo $language['lg_pharmacy_profile'] ?? "";
                                    }
                                ?>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?='/doctor-preview/'.$doctors['username']; ?>">
                                    <?=$language['lg_dr']. libsodiumDecrypt($doctors['first_name']).' '.libsodiumDecrypt($doctors['last_name']) ?? ""; ?>
                                </a>
                            </li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">
                        <?php
                            if ($doctors['role'] == 6) { // clinic
                                echo $language['lg_clinic_profile'] ?? "";
                            } elseif ($doctors['role'] == 1) { // doctor
                                echo $language['lg_dr'].libsodiumDecrypt($doctors['first_name']).' '.libsodiumDecrypt($doctors['last_name']) ?? "";
                            } elseif ($doctors['role'] == 4) { // lab
                                echo $language['lg_lab_profile'] ?? "";
                            } elseif ($doctors['role'] == 5) { // pharmacy
                                echo $language['lg_pharmacy_profile'] ?? "";
                            }
                        ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div id="loader-overlay" 
                    style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.7);
                    display: none;
                    align-items: center;
                    justify-content: center;
                    z-index: 9999;
                ">
                    <div class="spinner-border text-warning" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
    
                <?php if($user) { ?>
                    <div class="col-12 col-md-4 theiaStickySidebar">
                        <?=view('user/layout/sidebar');?>
                    </div>
                <?php } ?>
                <div class="col-12 <?=$user ? 'col-md-8' : 'col-md-12';?>">
                    <div class="card border-0">
                        <div class="card-body" style="background-color:#F7F7F7">
                            <div class="doctor-widget" style="width:100%">
                            <div class="row arrow-back-btn-and-summary-original d-flex justify-content-center" style="width:70%">
                                <div class="col-md-1 col-lg-1 col-xl-1 col-sm-1 arrow-back-btn-original">
                                <button class="btn btn-default arrow-btn-original" onclick="historyBackOriginal()" > < </button>
                                </div>

                                <?php
                                        /** @var array $doctors  */
                                        $profileimage = (file_exists($doctors['profileimage'])) ? base_url() . $doctors['profileimage'] : base_url() . 'assets/img/user.png';
                                        $doc_dept = (file_exists($doctors['specialization_img'])) ? base_url() . $doctors['specialization_img'] : 'https://via.placeholder.com/64x64.png?text=Specialization';
                                        ?>

                                <div class="card doctor-card mb-4 d-block d-md-none" id="doctor-${this.doctor_id}" style="border: none;">
                                                <div class="card-body p-4" style="background-color: #F7F7F7;">

                                                <div class="col-12 arrow-back-btn-original-mobile" style="width:50px; margin-bottom: 2%;">
                                                <button class="btn btn-default arrow-btn-original" style="border: 1px solid #E1E1E1;padding: 64%;width: 55px;height: 55px;" onclick="historyBackOriginal()" > < </button>
                                                </div>
                                                    <!-- Main Profile Section -->
                                                    <div class="row g-3 align-items-center">
                                                    <!-- Doctor Image -->
                                                    <div class="col-3 me-md-3 mb-3 mb-md-0" style="max-width: 24%;">
                                                    <img src="<?php echo $profileimage; ?>" 
                                                            class="doctor-img-mob" 
                                                            alt="profile picture" style="width: 100px;height: 100px;border-radius: 10%;">
                                                    </div>

                                                    <!-- Doctor Info -->
                                                    <div class="col-9 ps-md-3 div-mobile-info" style="margin-top: -5%;">
                                                        <div class="doctor-info-main" style="text-align: left;display: flex;flex-direction: column;align-items: flex-start;margin-left: 2%;">
                                                        <h4 class="doctor-name h5" style="margin-bottom: 0;font-size:16px;font-weight:500">
                                                            <a href="${base_url}doctor-preview/${this.username}" class="text-dark text-decoration-none">
                                                        <?php if ($doctors['role'] != 6) {
                                                                    echo $language['lg_dr'] ?? "";
                                                                } ?> <?php echo ucfirst(libsodiumDecrypt($doctors['first_name']) . ' ' . libsodiumDecrypt($doctors['last_name'])); ?> <i class="fas fa-chevron-right ms-1 small"></i>
                                                            </a>
                                                        </h4>

                                                        <!-- Speciality -->
                                                        <p class="doctor-specialty text-muted small" style="margin-bottom: 0;font-size:14px;font-weight:400">
                                                            <?php echo ucfirst(libsodiumDecrypt($doctors['speciality'])); ?>
                                                        </p>

                                                        <!-- Ratings -->
                                                            <div class="rating">
                                            <?php
                                            $rating_value = $doctors['rating_value'];
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating_value) {
                                                    echo '<i class="fas fa-star filled" style="font-size: 14px;"></i>';
                                                } else {
                                                    echo '<i class="fas fa-star" style="font-size: 14px;"></i>';
                                                }
                                            }
                                            ?>
                                            <span class="d-inline-block average-rating">(<?php echo $doctors['rating_count']; ?>)</span>
                                        </div>

                                                        <!-- Location -->
                                                        <p class="clinic-location small text-muted" style="margin-bottom: 0;font-size:12px;font-weight:400">
                                                            <i class="bi bi-geo-alt-fill me-1"></i>
                                                            <?php if (!empty($doctors['cityname']) && !empty($doctors['countryname'])) {
                                                        echo $clinicname.', '.$doctors['cityname'] . ', ' . $doctors['countryname'];
                                                        } elseif (!empty($doctors['cityname'])) {
                                                            echo $clinicname.', '.$doctors['cityname'];
                                                        } elseif (!empty($doctors['countryname'])) {
                                                            echo $clinicname.', '.$doctors['countryname'];
                                                        } else {
                                                            echo $clinicname;
                                                        }
                                                    
                                                        ?>
                                                        </p>
                                                        </div>
                                                    </div>
                                                    </div>

                                                    <!-- Today's Slots -->
                                                    <div class="today-slots-container border-top pt-3 mt-3">
                                                        <div class="slot-header">
                                        <div style="display:flex;justify-content:center;">
                                            <input type="date" style="background-color:#FFFFFF;margin-right: 2%;" name="schedule_date" id="schedule_date" value="<?php echo Date('Y-m-d') ?>" min="<?php echo date("Y-m-d"); ?>">
                                            <select class="form-control" style="background-color:#FFFFFF" name="timezone" id="timezone" class="timezone">
                                                <option value="">timezone</option>
                                                    <?php 
                                                    $currentTimezone = session('time_zone'); // Get the current session value
                                                    foreach ($timezones as $key => $value) { 
                                                        $selected = ($key === $currentTimezone) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?php echo $key; ?>" <?php echo $selected; ?>>
                                                            <?php echo $value; ?>
                                                        </option>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctors['userid'];?>">
                                        <input type="hidden" name="price_type" id="price_type" value="<?php echo $doctors['price_type'] ?>">
                                        <input type="hidden" name="hourly_rate" id="hourly_rate" value="<?php echo $doctors['amount'] ?>">
                                        <input type="hidden" name="role_id" id="role_id" value="<?php echo $doctors['role'] ?>">
                                        <input type="hidden" name="pet_id" id="pet_id" value="">
                                        
                                    </div>
                                                    <div class="today-slots-doctor-day row g-2" style="margin-left: 0%;">
                                                        
                                                    </div>
                                                    </div>

                                                    <!-- Booking Section -->
                                                    <div class="booking-section mt-3">
                                                        <div class="d-flex flex-column">
                                                            <div class="pricing-info">
                                                            <h5 class="consultation-type"> 
                                                                <span class="consultation-price" style="width: 100%;">
                                                            
                                                                <?php if (!empty($amount)) { ?>
                                                                    
                                                                <div style="color:#FD9720;font-size:20px;list-style: none;width: 100%;">
                                                                <p class="amount-total">
                                                                
                                                                </p>
                                                            </div>
                                                            
                                                                                                    
                                                            <?php } ?>
                                                            </span>
                                                            </h5>
                                                            </div>
                                                            <?php if ($login_role != '' & $login_role == 2) { ?>
                                                        
                                                            <a class="apt-btn-mobile" id="selectPetBtn"><?php  echo $language['lg_continue'] ?? ""; ?></a>
                                                            <?php }else{ ?>
                                                <a class="apt-btn-mobile" id="registerBtn"><?php  echo $language['lg_continue'] ?? ""; ?></a>
                                                            <?php } ?>
                                                        </div>
                                                        </div>

                                                
                                                </div>
                                                </div>
                            
                            
                                <div class="col-md-11 col-lg-11 col-xl-11 col-sm-11 doc-info-left d-none d-md-flex">
                                    <div class="doctor-img">
                                    
                                        <img src="<?php echo $profileimage; ?>" style="width:100px;height:100px;border-radius:12px" class="img-fluid" alt="User Image">
                                    </div>
                                    <div class="doc-info-cont">
                                        <h4 class="doc-name"><?php if ($doctors['role'] != 6) {
                                                                    echo $language['lg_dr'] ?? "";
                                                                } ?> <?php echo ucfirst(libsodiumDecrypt($doctors['first_name']) . ' ' . libsodiumDecrypt($doctors['last_name'])); ?></h4>
                                        <?php if ($doctors['role'] != 6) { ?>
                                            <p class="doc-department" style="font-weight:500;font-size:14px;color:#757575"><?php echo ucfirst(libsodiumDecrypt($doctors['speciality'])); ?></p>
                                        <?php } ?>
                                        <div class="rating">
                                            <?php
                                            $rating_value = $doctors['rating_value'];
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating_value) {
                                                    echo '<i class="fas fa-star filled" style="font-size: 14px;"></i>';
                                                } else {
                                                    echo '<i class="fas fa-star" style="font-size: 14px;"></i>';
                                                }
                                            }
                                            ?>
                                            <span class="d-inline-block average-rating">(<?php echo $doctors['rating_count']; ?>)</span>
                                        </div>
                                        <div class="clinic-details">
                                            <p class="doc-location" style="font-size:14px">
                                                <i class="fas fa-map-marker-alt"></i>
                                            
                                                <?php if (!empty($doctors['cityname']) && !empty($doctors['countryname'])) {
                                                    echo $clinicname.', '.$doctors['cityname'] . ', ' . $doctors['countryname'];
                                                } elseif (!empty($doctors['cityname'])) {
                                                    echo $clinicname.', '.$doctors['cityname'];
                                                } elseif (!empty($doctors['countryname'])) {
                                                    echo $clinicname.', '.$doctors['countryname'];
                                                } else {
                                                    echo $clinicname;
                                                }
                                            
                                                ?>
                                            </p> <span> </span>

                                            <ul class="clinic-gallery">
                                                <?php
                                                //if ($doctors['role'] != 6) {
                                                    //foreach ($clinic_images as $clinic_img) { ?>

                                                        <!-- <li>
                                                            <a href="<?php //echo base_url(); ?><?php //echo $clinic_img['clinic_image']; ?>" data-fancybox="gallery">
                                                                <img src="<?php //echo base_url(); ?><?php //echo $clinic_img['clinic_image']; ?>" alt="Feature">
                                                            </a>
                                                        </li> -->
                                                <?php //}
                                            // } ?>
                                            
                                            </ul>
                                        </div>

                                    </div>

                                    <div class="doc-info-pet pet-selected d-flex align-items-center rounded p-0">
                                    
                                    </div>

                                </div>

                                <!-- Sections Review and Overview -->
                            <div class="card" style="width:97%;border:none !important; background-color:#F7F7F7">
                                                <div class="card-body pt-0" style="width:100%">

                                                    <!-- Tab Menu -->
                                                    <nav class="user-tabs mb-4">
                                                        <ul class="nav nav-tabs nav-tabs-bottom nav-justified" style="background-color:#F7F7F7;">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" href="#doc_overview" data-toggle="tab"><?php echo $language['lg_overview'] ?? ""; ?></a>
                                                            </li>
                                                            <!-- <li class="nav-item">
                                                                <a class="nav-link" href="#doc_locations" data-toggle="tab"><?php echo $language['lg_locations'] ?? ""; ?></a>
                                                            </li> -->
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="#doc_reviews" data-toggle="tab"><?php echo $language['lg_reviews'] ?? ""; ?></a>
                                                            </li>
                                                            <!-- <li class="nav-item">
                                                                <a class="nav-link" href="#doc_business_hours" data-toggle="tab"><?php echo $language['lg_business_hours'] ?? ""; ?></a>
                                                            </li> -->
                                                        </ul>
                                                    </nav>
                                                    <!-- /Tab Menu -->

                                                    <!-- Tab Content -->
                                                    <div class="tab-content pt-0">

                                                        <!-- Overview Content -->
                                                        <div role="tabpanel" id="doc_overview" class="tab-pane fade show active">
                                                        <div class="doc-overview-web">
                                                            <p><strong>Experience:</strong><span> 6 years</span></p><br/>
                                                            <p><strong>Languages:</strong><span> English, Arabic, Russian</span></p><br/>
                                                            <p><strong>What it will help with:</strong><span> English, Arabic, Russian</span></p><br/>
                                                            <p><strong>Species treated:</strong><span> Cat, Dog, Rodents</span></p><br/>
                                                            <p><strong>Description:</strong><span> My name is Dr. Emma Smith and I specialize in animal behavior diagnostics</span></p><br/>

                                                        </div>
                                                        <h2 class="how-to-use-h2">How to use?</h2>
                                                        <div class="how-to-use">

            

                                                            <div class="step">
                                                                <div class="step-icon">1</div>
                                                                <div class="step-text">
                                                                    <strong>Book an online consultation</strong>
                                                                    <p>Select a clinic, veterinarian, date and time</p>
                                                                </div>
                                                            </div>

                                                            <div class="step">
                                                                <div class="step-icon">2</div>
                                                                <div class="step-text">
                                                                    <strong>Pay for online consultation</strong>
                                                                    <p>After payment, expect a consultation on the specified date and time</p>
                                                                </div>
                                                            </div>

                                                            <div class="step">
                                                                <div class="step-icon">3</div>
                                                                <div class="step-text">
                                                                    <strong>Get a veterinarian’s recommendation</strong>
                                                                    <p>After the consultation, you will receive recommendations for the treatment of your pet</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="verified-specialists">
                                                        <h2>Only verified specialists</h2>

                                                        <div class="specialist-cards">
                                                            <div class="card">
                                                                <span><i><img src="<?php echo base_url().'icons/experties.svg' ?>"></i> 5+ Expertise</span>
                                                                
                                                            </div>
                                                            <div class="card">
                                                                
                                                                <span><i><img src="<?php echo base_url().'icons/rating.svg' ?>"></i> 4.9 Avg. Rating</span>
                                                            </div>
                                                            <div class="card">
                                                                
                                                                <span><i><img src="<?php echo base_url().'icons/c_vets.svg' ?>"></i> 100% Certified Vets</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                        </div>
                                                        <!-- /Overview Content -->


                                                        <!-- Reviews Content -->
                                                        <div role="tabpanel" id="doc_reviews" class="tab-pane fade">

                                                            <!-- Review Listing -->
                                                            <div class="widget review-listing">
                                                                <ul class="comments-list">
                                                                    <?php if (!empty($reviews)) {
                                                                        foreach ($reviews as $rrows) {

                                                                            if ($rrows['profileimage'] == "" || ($rrows['profileimage'] != "" && !is_file($rrows['profileimage']))) {
                                                                                $rimg = base_url() . 'assets/img/user.png';
                                                                            } else {
                                                                                $rimg = (!empty($rrows['profileimage'] ?? "")) ? base_url() . $rrows['profileimage'] ?? "" : base_url() . 'assets/img/user.png';
                                                                            }

                                                                            if ($rrows['doctor_image'] == "" || ($rrows['doctor_image'] != "" && !is_file($rrows['doctor_image']))) {
                                                                                $drimg = base_url() . 'assets/img/user.png';
                                                                            } else {
                                                                                $drimg = (!empty($rrows['doctor_image'] ?? "")) ? base_url() . $rrows['doctor_image'] ?? "" : base_url() . 'assets/img/user.png';
                                                                            }

                                                                    ?>

                                                                            <!-- Comment List -->
                                                                            <li>
                                                                                <div class="comment" style="background-color:#FFFFFF; padding:2%;border-radius: 8px">
                                                                                    <img class="avatar avatar-sm rounded-circle" alt="User Image" src="<?php echo $rimg; ?>">
                                                                                    <div class="comment-body">
                                                                                        <div class="meta-data" style="display: flex; justify-content: space-between; align-items: center;">
                                                                                            <div class="author-info">
                                                                                                <span class="comment-author"><?php echo libsodiumDecrypt($rrows['first_name']) . ' ' . libsodiumDecrypt($rrows['last_name']); ?></span>
                                                                                                <span class="comment-date"><?php echo $language['lg_reviewed'] ?? "" ?? ""; ?> <?php echo time_elapsed_string($rrows['created_date']); ?></span>
                                                                                            </div>
                                                                                            
                                                            
                                                                                            <div class="review-count rating">
                                                                                                <?php for ($i = 1; $i <= 5; $i++) {
                                                                                                    if ($i <= $rrows['rating']) {
                                                                                                ?>
                                                                                                        <i class="fas fa-star filled"></i>
                                                                                                    <?php } else { ?>
                                                                                                        <i class="fas fa-star"></i>
                                                                                                <?php }
                                                                                                } ?>

                                                                                            </div>
                
                                                                                        </div>
                                                                                        <p class="comment-content">
                                                                                            <?php echo $rrows['review']; ?>
                                                                                        </p>
                                                                                        <div class="comment-reply">

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php if ($rrows['reply_id'] != '') { ?>
                                                                                    <ul class="comments-reply">

                                                                                        <!-- Comment Reply List -->
                                                                                        <li>
                                                                                            <div class="comment">
                                                                                                <img class="avatar rounded-circle" alt="User Image" src="<?php echo $drimg; ?>">
                                                                                                <div class="comment-body">
                                                                                                    <div class="meta-data">
                                                                                                        <span class="comment-author"><?php echo $language['lg_dr'] ?? ""; ?> <?php echo libsodiumDecrypt($rrows['doctor_firstname']) . ' ' . libsodiumDecrypt($rrows['doctor_lastname']); ?></span>
                                                                                                        <span class="comment-date"><?php echo $language['lg_replied'] ?? ""; ?> <?php echo time_elapsed_string($rrows['reply_date']); ?> </span>
                                                                                                    </div>
                                                                                                    <p class="comment-content">
                                                                                                        <?php echo $rrows['reply']; ?>
                                                                                                    </p>

                                                                                                </div>
                                                                                            </div>
                                                                                        </li>
                                                                                        <!-- /Comment Reply List -->

                                                                                    </ul>
                                                                                    <!-- /Comment Reply -->
                                                                                <?php } ?>

                                                                            </li>

                                                                    <?php }
                                                                    } else {
                                                                        $lg_no_reviews_foun = $language['lg_no_reviews_foun'] ?? "" ?? "";
                                                                        echo '<li>
                                                                            <div class="comment">
                                                                            <p>' . $lg_no_reviews_foun . '</p>
                                                                            </div>
                                                                            </li>';
                                                                    } ?>

                                                                </ul>

                                                                <!-- Show All -->

                                                                <!-- /Show All -->

                                                            </div>
                                                            <!-- /Review Listing -->

                                                            <!-- Write Review -->

                                                            <!-- /Write Review -->

                                                        </div>
                                                        <!-- /Reviews Content -->

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Sections Review and Overview -->

                                </div>
                                <div class="doc-info-right d-none d-md-block" >
                                    <div class="clini-infos">
                                    <div class="slot-container">
                                    <h2>Select a slot</h2>
                                    <div class="slot-header">
                                        <div style="display:flex;justify-content:center;">
                                            <input type="date" name="schedule_date" id="schedule_date" value="<?php echo Date('Y-m-d') ?>" min="<?php echo date("Y-m-d"); ?>">
                                            <select class="form-control" name="timezone" id="timezone" class="timezone">
                                                <option value="">timezone</option>
                                                    <?php 
                                                    $currentTimezone = session('time_zone'); // Get the current session value
                                                    foreach ($timezones as $key => $value) { 
                                                        $selected = ($key === $currentTimezone) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?php echo $key; ?>" <?php echo $selected; ?>>
                                                            <?php echo $value; ?>
                                                        </option>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctors['userid'];?>">
                                        <input type="hidden" name="price_type" id="price_type" value="<?php echo $doctors['price_type'] ?>">
                                        <input type="hidden" name="hourly_rate" id="hourly_rate" value="<?php echo $doctors['amount'] ?>">
                                        <input type="hidden" name="role_id" id="role_id" value="<?php echo $doctors['role'] ?>">
                                        <input type="hidden" name="pet_id" id="pet_id" value="">
                                        
                                    </div>
                                    <div class="slots-doctor-day">

                                    </div>

                                    </div>
                                    <div class="popup-overlay" id="popup" style="display:none;">
                                        <div class="popup-content" style="background:#fff; padding:20px; width:90%; max-width:500px; margin:50px auto; box-shadow:0 0 10px rgba(0,0,0,0.5);">
                                            <div class="remaining_slots">
                                            <h3>More Available Slots</h3>
                                            <p>Select a slot from below:</p>
                                            </div>
                                            <button class="close-btn" style="margin-top:15px; padding:8px 16px;">Close</button>
                                        </div>
                                    </div>

                                
                                        <ul>
                                            <!-- <li><i class="far fa-comment"></i> <?php //echo $doctors['rating_count']; ?> <?php //echo $language['lg_feedback'] ?? ""; ?></li> -->
                                            <!-- <li><i class="fas fa-map-marker-alt"></i> </i> <?php //if (!empty($doctors['cityname']) && !empty($doctors['countryname'])) {
                                                                                            // echo $doctors['cityname'] . ', ' . $doctors['countryname'];
                                                                                        // } elseif (!empty($doctors['cityname'])) {
                                                                                            //     echo $doctors['cityname'];
                                                                                            // } elseif (!empty($doctors['countryname'])) {
                                                                                            //     echo $doctors['countryname'];
                                                                                            // } else {
                                                                                            //     echo '';
                                                                                            // } ?> -->
                                                <?php if (!empty($amount)) { ?>
                                                    <li style="color:#FD9720;font-size:20px">
                                                    <p class="amount-total">
                                                    
                                                    </p>
                                                </li>
                                                                                        
                                        <?php } ?>
                                        </ul>
                                        
                                    </div>
                                    <?php
                                    //$where = array('patient_id' => session('user_id'), 'doctor_id' => $doctors['user_id']);
                                    //     $favourites='';
                                    //$is_favourite = getTblRowOfData('favourities', $where, '');
                                    // if ($is_favourite) {
                                    //     $favourites = 'fav-btns';
                                    // } else {
                                    //     $favourites = '';
                                    // }
                                    ?>

                                    <?php if ($login_role != '' & $login_role == 2) { ?>
                                        <!-- <div class="doctor-action">
                                            <?php
                                            if ($doctors['role'] == 1) { ?>
                                                <a href="javascript:void(0)" id="favourities_<?php //echo $doctors['user_id']; ?>" onclick="add_favourities('<?php //echo $doctors['user_id']; ?>')" class="btn btn-white fav-btn <?php //echo $favourites; ?>">
                                                    <i class="fas fa-heart"></i>
                                                </a>
                                                <a href="<?php //echo base_url() . 'patient/message'; ?>" class="btn btn-white msg-btn">
                                                    <i class="far fa-comment-alt"></i>
                                                </a>
                                            <?php } ?>
                                        </div> -->


                                        <div class="clinic-booking">
                                            <br />
                                            <br />
                                            <!-- <a class="apt-btn" href="<?php //echo base_url() . 'book-appoitnments/' . encryptor_decryptor('encrypt', libsodiumDecrypt($doctors['username'])); ?>">// echo $language['lg_continue'] ?? ""; </a> -->
                                            <a class="apt-btn" id="selectPetBtn"><?php  echo $language['lg_continue'] ?? ""; ?></a>
                                        </div>

                                    <?php }else{?>
                                        <div class="clinic-booking">
                                            <br />
                                            <br />
                                            <!-- <a class="apt-btn" href="<?php //echo base_url() . 'book-appoitnments/' . encryptor_decryptor('encrypt', libsodiumDecrypt($doctors['username'])); ?>">// echo $language['lg_continue'] ?? ""; </a> -->
                                            <a class="apt-btn" id="registerBtn"><?php  echo $language['lg_continue'] ?? ""; ?></a>
                                        </div>
                                    <?php } ?>

                                    

                                </div>
                            </div>

                            
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var country = '';
    var state = '';
    var city = '';
    var specialization = '';
</script>
<script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>

<?php $userId = session('user_id') ? session('user_id') : ''; ?>
<script type="text/javascript">
     const userId = <?php echo json_encode($userId); ?>;
    var doctor_id = $('#doctor_id').val();
        var session = doctor_id+'-session-appointment-'+userId;
        const sessionStorePet = doctor_id+"-session-pet-"+userId;
        const sessionAmountInfo = doctor_id+"-session-amount-"+userId;
        var sessionbeforeRegister = null;
        var sessionSlot = "selected-slot";
        var userIdSession = "userIdsession";
        

    $(document).ready(function(){
        // get ammount_info
        var hourly_rate = $('#hourly_rate').val();
        var price_type = $('#price_type').val();
        var role_id = $('#role_id').val();
        console.log("price_type", price_type);
        async function getAmountInformation(){
        
             return new Promise((resolve, reject) => {
                  $.post(base_url+'get-amount-info',{
            hourly_rate:hourly_rate,
            price_type:price_type,
            doctor_id:doctor_id,
            doctor_role_id:role_id
            
        },function(res){
            var obj = JSON.parse(res);
            console.log("obj", obj)
            if(obj.status == 500){
                toastr.warning(obj.message);
                reject(obj.message);
            }else if(obj.status == 202){
                localStorage.setItem(sessionAmountInfo, JSON.stringify({
                    'status': 'free'
                }));
                $('.amount-total').html(`
                        <strong style="float: left">Total</strong>
                        <strong style="float:right;" class="amount-total-value">FREE</strong>                  
                `);
                resolve()
            }
            else{
                localStorage.setItem(sessionAmountInfo, JSON.stringify({
                    'status': 'paid',
                    'amount' : obj.data.amount,
                    'transcation_charge' : obj.data.transcation_charge,
                    'transcation_charge_prec' : obj.data.transcation_charge_prec,
                    'tax_amount' : obj.data.tax_amount,
                    'tax_prec' : obj.data.tax_prec,
                    'total_amount' : obj.data.total_amount,
                    'hourly_rate' : obj.data.hourly_rate,
                    'currency_code' : obj.data.currency_code,
                    'currency_symbol' : obj.data.currency_symbol,
                    'discount' : 0,
                    'doctor_role_id' : obj.data.doctor_role_id
                }));

                $('.amount-total').html(`
                        <strong style="float: left">Total</strong>
                        <strong style="float:right;" class="amount-total-value">${obj.data.currency_symbol}${obj.data.total_amount}</strong>                 
                `);
                
                resolve();
            }
        });

             })
          
        }
        
        // End get ammount_info
        saveChangesAppointment();
        async function saveChangesAppointment(){
            const sessionAppointment = JSON.parse(localStorage.getItem(session));
            console.log('sessionAppointment', sessionAppointment);
            const petSelected = JSON.parse(localStorage.getItem(sessionStorePet));
            if (typeof petSelected !== 'undefined' && petSelected) {
                $('#pet_id').val(petSelected.pet_id);
                $('#reason_pet_visit').val(petSelected.reason_pet_visit);
            }
            
            await getAmountInformation();
            
            const amountInfo = JSON.parse(localStorage.getItem(sessionAmountInfo));
            console.log('amountInfo', amountInfo);
            if(petSelected != null && sessionAppointment != null && amountInfo != null){
                var appointment_date = '-';
                if(sessionAppointment != null){
                const dateObj = new Date(sessionAppointment['appointment_date']);
                const options = { year: 'numeric', month: 'short', day: '2-digit' };
                 appointment_date = dateObj.toLocaleDateString('en-US', options);
                }

                console.log(amountInfo.currency_symbol ,amountInfo.total_amount);

                
                var amountInformation = `
                 <ul class="amount-info-ul">
                                        <li>
                                            <strong style="float: left">Call Charge</strong>
                                            <strong style="float: right" class="price_value"> ${amountInfo['amount']}</strong>
                                        </li>
                                        <li>
                                            <strong style="float: left">Transaction charge (${amountInfo['transcation_charge_prec']}%)</strong>
                                            <strong style="float: right" class="price_value"> ${amountInfo['transcation_charge']}</strong>
                                        </li>
                                        <li>
                                            <strong style="float: left">VAT (${amountInfo['tax_prec']}%)</strong>
                                            <strong style="float: right" class="price_value"> ${amountInfo['tax_amount']}</strong>
                                        </li>
                                        <li>
                                            <strong style="float: left">Discount</strong>
                                            <strong style="float: right" class="price_value"> ${amountInfo['discount']}</strong>
                                        </li>
                                   </ul>
                                
                            </div>
                                <div class="col-md-12">
                                <ul>
                               <?php if (!empty($amount)) { ?>
                                            <li style="color:#FD9720;font-size:20px">
                                            <p class="amount-total">
                                            <strong style="float: left">Total</strong>
                                            <strong style="float:right;" class="amount-total-value">${amountInfo.currency_symbol}${amountInfo.total_amount}</strong>
                                            </p>
                                        </li>
                                                                                
                                <?php } ?>
                                </ul>
                                
                `;
                if(amountInfo['status'] == 'free'){
                    
                     amountInformation = `
                 <ul class="amount-info-ul">
                                        <li>
                                            <strong style="float: left">Call Charge</strong>
                                            <strong style="float: right" class="price_value"> 0.0</strong>
                                        </li>
                                        <li>
                                            <strong style="float: left">Transaction charge (0%)</strong>
                                            <strong style="float: right" class="price_value"> 0.0</strong>
                                        </li>
                                        <li>
                                            <strong style="float: left">VAT (5%)</strong>
                                            <strong style="float: right" class="price_value"> 0.0</strong>
                                        </li>
                                        <li>
                                            <strong style="float: left">Discount</strong>
                                            <strong style="float: right" class="price_value"> 0.0</strong>
                                        </li>
                                   </ul>
                                
                            </div>
                                <div class="col-md-12">
                                <ul>
                                <?php if (!empty($amount)) { ?>
                                            <li style="color:#FD9720;font-size:20px">
                                            <p class="amount-total">
                                             <strong style="float: left">Total</strong>
                        <strong style="float:right;" class="amount-total-value">FREE</strong>
                                            </p>
                                        </li>
                                                                                
                                <?php } ?>
                                </ul> `;
                 }

                $scheduleDateHtml = !sessionAppointment?  '<input type="date" name="schedule_date" id="schedule_date" value="<?=date('Y-m-d') ?>" min="<?php echo date("Y-m-d"); ?>">' : '<input type="hidden" name="schedule_date" id="schedule_date" value="<?php echo Date('Y-m-d') ?>" min="<?php echo date("Y-m-d"); ?>">';

              console.log("hereeeeeeeeeee");
            //swap page here

            $('.page-dcotor-all-content').html(`
            <div class="page-dcotor-all-content">
<style>

.summary-section-col{
    margin-top: 0%;
}

.summary-section{
    padding-top: 0%;
}
.booking-summary{
font-weight: 600;
font-size: 20px;
}

.booking-summary-p{
    font-weight: 400;
    font-size: 16px;
}

.reason-section-col{
    padding:0 5% 5% 5%;
}

.reason-section{
    margin-top: 2%;
    padding: 10px;
    background-color: #F7F7F7;
    border-radius: 15px;
}

.reason-section-title{
font-weight: 500;
font-size: 16px;
}

.reason-section-p{
    font-weight: 500;
    font-size: 14px;
    color:#757575;
}
    .doc-info-left {
    width: 65%; /* or 75% if you still want it smaller than full width */
    display: flex;
    align-items: center; /* vertical centering */
    justify-content: center; /* horizontal centering */
    gap: 20px; /* keep space between doctor and pet */
    padding: 0; /* no side padding */
    background-color: #FFFFFF;
    border-radius: 20px;
    padding: 1%;
}

.doctor-info {
    width: auto; /* instead of 50% */
    background: #f8f9fa;
    border-radius: 10px;
    padding: 3% 8%;
   align-content: center;
}


.doctor-img-custimize {
    width: 80px;
    height: 80px;
    margin: 0;
    padding-left: 0%;
    
}

.doctor-img-custimize img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px; /* small rounding (square look) */
    display: block;
}
.doc-info-cont {
    margin-left: 2%;
}
.doc-department,.rating,
.doc-info-cont .doc-name{
    font-size: 10px;
    font-weight: bold;
    margin-right: 2%;
}
.label-vet,
.pet_label{
    font-weight: 500;
    font-size: 16px;
}

.doc-info-pet {
    background: #f8f9fa; /* optional background */
    padding: 8px 22px;
    border-radius: 10px;
    width: 99%;
}

.doc-info-pet img{
    width: 80px; /* or your preferred size */
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    margin: 0; /* no margin */
    padding: 0; /* no padding */
    display: block; /* ensure no extra space */
}

.doc-info-right{
    margin-left: auto;
    flex: 0 279px;
    max-width: 281px;
    width:100%;
}

    .timeline-content {
        background-color:#F7F7F7
    };
    .doc-info-right{
        width: 100%;
        background-color:#FFFFFF;
        
    }
    .slot-container {
        width: 100%;
        max-width: 350px;
        margin: 0 auto;
        text-align: center;
        font-family: 'Poppins', sans-serif;
        background-color:#FFFFFF;
        border-radius: 20px;
    }

    .slot-header {
        /* display: flex; */
        /* justify-content: center; */
        align-items: start;
        /* gap: 10px; */
        margin-bottom: 10px;
    }

    .slot-header input,
    .slot-header select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 12px;
    }

    .slots-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        justify-content: center;
        padding:3%;
    }

    .slot {
        padding: 2px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 400;
        cursor: pointer;
        transition: all 0.3s ease;
        color:#252525
    }

    .slot:hover {
        border-color: #FD9720;
        color: #FD9720;
    }

    .slot.active {
        border: 2px solid #FD9720;
        color: #FD9720;
        font-weight: bold;
    }

    .see-more {
        margin-top: 10px;
        font-weight: bold;
        color: #252525;
        cursor: pointer;
        text-align: center;
    }

    /* Popup Modal */
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
    }

    .popup-content {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        max-width: 400px;
        text-align: center;
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

    .doc-department{
        color:#252525;
    }

    .slot-booked{
        pointer-events: none; /* Disable clicks */
        opacity: 0.5;          /* Make it look faded */
        background-color: #ccc; /* Optional: Grey background */
        cursor: not-allowed; 
        padding: 8px;
        border: 1px dashed #999;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 400;
    }
    .no-token {
        width: 100%;
        text-align: center;
        font-size: 18px;
        color: #555;
        margin-top: 0px;
    }

    .slots-doctor-day{
        padding:2%;
    }

    .slots-doctor-day ul li{
        font-weight: 500;
        font-size: 14px;
    }

    .slots-doctor-day .amount-info-ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.slots-doctor-day .amount-info-ul li {
   display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 500;
    font-size: 16px;
    margin-bottom: 5px;
    color: ##252525;
}

    .slots-doctor-day ul li .price_value{
        color:#757575;
        font-weight: 400;
        font-size: 16px;
    }

    @media (max-width: 1079px) {

        .label-vet,
        .pet_label{
            font-weight: 500;
            font-size: 16px;
            margin-left: 6%;
        }

    .doc-info-left,
    .doc-info-right {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
    }

    .summary-section-col {
        margin-top: 0;
    }

    .doctor-section {
        width: 100%;
        padding: 10px 0;
    }

    .slot-container {
        margin: 20px auto;
    }

    .slot-header {
        /* flex-direction: column;
        gap: 10px; */
    }

    .slots-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .doctor-widget {
        flex-direction: column;
    }

    .doc-info-left, .doc-info-right {
        width: 100% !important;
    }
    .doc-info-right{
        margin-right: 25%;
    }
}
.appointment-details {
    display: flex;
    justify-content: space-between;
    gap: 5%;
    padding: 5%;
    margin: 0;
    list-style: none;
    flex-wrap: wrap;
}

.appointment-details li {
    display: flex;           /* <-- Make h3 and p sit side-by-side */
    align-items: center;
    gap: 20%;               /* Optional spacing between h3 and p */
    flex: 1;
    min-width: 120px;
}

.appointment-details h3{
    margin: 0;
    font-weight: 500;
    font-size: 16px;
    color:#252525;
}
.appointment-details p {
    margin: 0;
    font-weight: 500;
    font-size: 16px;
    color: #FD9720;
}

/* Mobile (max-width: 767px) */
@media (max-width: 767px) {
    .doctor-widget {
        flex-direction: column;
    }
    .doc-info-left,
    .doc-info-right {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
    }

    .doc-info-right{
        margin-right: 25%;
    }

    .doctor-section {
        width: 100%;
        flex-direction: column;
        margin-bottom: 20px;
    }
    .row {
        flex-direction: column;
    }

    .doctor-section {
        width: 100%;
        margin-bottom: 20px;
    }

    .doc-info-pet, .doctor-info {
        width: 100%;
    }

    .doctor-info,
    .doc-info-pet {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 15px;
    }

    .doc-info-cont {
        margin-left: 0;
        margin-top: 10px;
    }

    .doctor-img-custimize,
    .doc-info-pet img {
        margin: 0 auto;
    }

    .slot-container {
        margin: 20px auto;
    }

    .slot-header {
        /* flex-direction: column; */
    }

    .slots-grid {
        grid-template-columns: repeat(1, 1fr);
    }

    .popup-content {
        width: 90%;
        margin: 30px auto;
    }
}


.arrow-back-btn{
    padding: 1%;
    border: 1px solid #E1E1E1;
    color: #252525;
    height: 20%;
    border-radius: 10%;
   
    }
.arrow-back-btn-and-summary{
padding-left: 2%;
    }

    .arrow-btn{
     font-size:20px;
    font-weight: bold;
    }

    @media (min-width: 1080px) {
        .arrow-back-btn{
        padding: 0%;
        }
    }

    @media (max-width: 576px) {
    .doc-info-right{
        margin-right: 0;
    }
    .arrow-back-btn{
      width: 10%;
      padding: 1%;
    }
      .arrow-back-btn-and-summary{
       padding-left: 6%;
    }
    .arrow-btn{
    padding: 0%;
            }
}
    
</style>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $language['lg_home'] ?? ""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php
                                                                                if ($doctors['role'] == 6) { // clinic
                                                                                    echo $language['lg_clinic_profile'] ?? "";
                                                                                } elseif ($doctors['role'] == 1) { // doctor
                                                                                    echo $language['lg_doctor_profile'] ?? "";
                                                                                } elseif ($doctors['role'] == 4) { // lab
                                                                                    echo $language['lg_lab_profile'] ?? "";
                                                                                } elseif ($doctors['role'] == 5) { // pharmacy
                                                                                    echo $language['lg_pharmacy_profile'] ?? "";
                                                                                }
                                                                                ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php
                                                if ($doctors['role'] == 6) { // clinic
                                                    echo $language['lg_clinic_profile'] ?? "";
                                                } elseif ($doctors['role'] == 1) { // doctor
                                                    echo $language['lg_doctor_profile'] ?? "";
                                                } elseif ($doctors['role'] == 4) { // lab
                                                    echo $language['lg_lab_profile'] ?? "";
                                                } elseif ($doctors['role'] == 5) { // pharmacy
                                                    echo $language['lg_pharmacy_profile'] ?? "";
                                                }
                                                ?></h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
    <div class="container-fluid">
    <div class="row">
 
        <div class="col-md-12 col-lg-4 col-xl-3 theiaStickySidebar">
                <?=
                user_detail(session('user_id')) ? view('user/layout/sidebar') : ''; 
                ?>
        </div>
       
        <div class="col-md-12 col-lg-8 col-xl-9">
        <!-- Doctor Widget -->
        <div class="card">
            <div class="card-body" style="background-color:#F7F7F7">
                <div class="doctor-widget">
                
                <div class="doc-info-left d-flex align-items-center ">
                    <div class="row arrow-back-btn-and-summary">
                    <div class="col-md-1 col-lg-1 col-xl-1 col-sm-1 arrow-back-btn">
                    <button class="btn btn-default arrow-btn" onclick="historyBack()" > < </button>
                    </div>
                        <div class="col-md-11 col-lg-11 col-xl-11 col-sm-11 summary-section-col">
                        <div class="summary-section">
                            <h3 class="booking-summary">Booking Summary</h3>
                           <p class="booking-summary-p">
                           Please note: the consultation may take between 15 to 20 minutes, depending on the case
                           </p>
                        </div>
                        </div>
                  
                
                    <div class="doctor-section col-md-6">
                    <!-- Group: Doctor image + Doctor info -->
                        <label class="label-vet">Veterinarians</label>
                        <div class="d-flex align-items-center doctor-info">
                            
                        <?php
                                /** @var array $doctors  */
                                $profileimage = (file_exists($doctors['profileimage'])) ? base_url() . $doctors['profileimage'] : base_url() . 'assets/img/user.png';
                                $doc_dept = (file_exists($doctors['specialization_img'])) ? base_url() . $doctors['specialization_img'] : 'https://via.placeholder.com/64x64.png?text=Specialization';
                                ?>
                            <div class="doctor-img-custimize">
                                <img src="<?php  echo $profileimage;?>" class="img-fluid" alt="User Image">
                            </div>
                            <div class="doc-info-cont">
                                <h4 class="doc-name">
                                    <?php if ($doctors['role'] != 6) { echo $language['lg_dr'] ?? ""; } ?>
                                    <?=ucfirst(libsodiumDecrypt($doctors['first_name']) . ' ' . libsodiumDecrypt($doctors['last_name'])); ?>
                                </h4>
                                <?php if ($doctors['role'] != 6) { ?>
                                    <p class="doc-department"><?=ucfirst(libsodiumDecrypt($doctors['speciality'])); ?></p>
                                <?php } ?>
                                <div class="rating">
                                    <?php
                                    $rating_value = $doctors['rating_value'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating_value) {
                                            echo '<i class="fas fa-star filled"></i>';
                                        } else {
                                            echo '<i class="fas fa-star"></i>';
                                        }
                                    }
                                    ?>
                                    <span class="d-inline-block average-rating">(<?=$doctors['rating_count']; ?>)</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="doctor-section col-md-6">
                    <label class="pet_label">Pet</label>
                    <!-- Pet info -->
                    <div class="doc-info-pet pet-selected d-flex align-items-center rounded ">
                        <img src="<?php base_url();?>${petSelected ? petSelected['pet_photo'] :''}" alt="" class="rounded-circle" width="48" height="48">
                        <div class="ml-3 flex-grow-1">
                            <div class="font-weight-bold">${petSelected ? petSelected['pet_name'] : '-'}</div>
                            <div class="text-muted small">${petSelected ? petSelected['pet_type'] : '-'}</div>
                        </div>
                    </div>

               
                    

                    </div>
     <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 reason-section-col">
                        <div class="reason-section">
                            <h3 class="reason-section-title">Reason for visit</h3>
                           <p class="reason-section-p">
                           Whiskers was brought in due to decreased appetite and lethargy over the past few days                           
                           </p>
                        </div>
                </div>
                <!-- appointment -->
                <div class="col-md-12 col-lg-12 col-xl-12">
                   <ul class="appointment-details">
                   <li>
                        <h3>Date</h3>
                        <p> ${appointment_date ?? '-'}</p>
                    </li>
                    <li>
                        <h3>Time</h3>
                        <p>${sessionAppointment ? sessionAppointment['appointment_start_time'] : '-'}</p>
                    </li>
                                        
                   </ul>
                </div>
    </div>
                        
                    </div>
                </div>
                
                                
                    <div class="doc-info-right" >
                        <div class="clini-infos">
                        <div class="slot-container">
                        
                        <div class="slot-header">
                                ${$scheduleDateHtml}
                            <input type="hidden" name="doctor_id" id="doctor_id" value="<?=$doctors['userid'];?>">
                            <input type="hidden" name="price_type" id="price_type" value="<?=$doctors['price_type'] ?>">
                            <input type="hidden" name="hourly_rate" id="hourly_rate" value="<?=$doctors['amount'] ?>">
                            <input type="hidden" name="role_id" id="role_id" value="<?=$doctors['role'] ?>">
                            <input type="hidden" name="pet_id" id="pet_id" value="${petSelected ? petSelected['pet_id'] : ''}">
                            <input type="hidden" name="reason_pet_visit" id="reason_pet_visit" value="${petSelected ? petSelected['reason_pet_visit'] : ''}">

                        </div>
                        <div class="slots-doctor-day">
                            <div class="row">
                                <div class="col-md-12"><strong><h2 style="float:left">Price</h2></br/></strong></div>
                                <div class="col-md-12">
                                ${amountInformation}
                                </div>
                                <div class="col-md-12">
                                   <ul>
                                    <li>
                                        <ul class="consent-list" style="
                                        display: flex;
                                        align-items: start;
                                        gap: 10px;
                                        list-style: none;
                                        padding: 0;
                                        margin: 0;
                                        ">
                                            <li style="margin: 0;width: 10%;">
                                                <input type="checkbox" class="form-control" style="width: 18px;height: 18px;cursor: pointer;">
                                            </li>
                                            <li style="margin: 0;padding-top:5%">
                                            <p style="margin: 0;font-size: 14px;line-height: 1;color:#757575">
                                                I give informed voluntary consent to medical intervention
                                                </p>
                                            </li>
                                        </ul>
                                       
                                    </li>
                                   </ul>
                                </div>
                            </div>
                            
                        </div>

                        </div>
                       
                            <ul>
                                <!-- <li><i class="far fa-comment"></i> <?php //echo $doctors['rating_count']; ?> <?php //echo $language['lg_feedback'] ?? ""; ?></li> -->
                                <!-- <li><i class="fas fa-map-marker-alt"></i> </i> <?php //if (!empty($doctors['cityname']) && !empty($doctors['countryname'])) {
                                                                                   // echo $doctors['cityname'] . ', ' . $doctors['countryname'];
                                                                               // } elseif (!empty($doctors['cityname'])) {
                                                                                //     echo $doctors['cityname'];
                                                                                // } elseif (!empty($doctors['countryname'])) {
                                                                                //     echo $doctors['countryname'];
                                                                                // } else {
                                                                                //     echo '';
                                                                                // } ?> -->
                                    
                            </ul>
                            
                        </div>
                        <?php
                        //$where = array('patient_id' => session('user_id'), 'doctor_id' => $doctors['user_id']);
                        //     $favourites='';
                        //$is_favourite = getTblRowOfData('favourities', $where, '');
                        // if ($is_favourite) {
                        //     $favourites = 'fav-btns';
                        // } else {
                        //     $favourites = '';
                        // }
                        ?>

                        <?php if ($login_role != '' & $login_role == 2) { ?>
                            <!-- <div class="doctor-action">
                                <?php
                                if ($doctors['role'] == 1) { ?>
                                    <a href="javascript:void(0)" id="favourities_<?php //echo $doctors['user_id']; ?>" onclick="add_favourities('<?php //echo $doctors['user_id']; ?>')" class="btn btn-white fav-btn <?php //echo $favourites; ?>">
                                        <i class="fas fa-heart"></i>
                                    </a>
                                    <a href="<?php //echo base_url() . 'patient/message'; ?>" class="btn btn-white msg-btn">
                                        <i class="far fa-comment-alt"></i>
                                    </a>
                                <?php } ?>
                            </div> -->


                            <div class="clinic-booking">
                                 <br />
                                 <br />
                                 
                                ${petSelected != null && sessionAppointment != null && amountInfo != null 
                                    ? `<a id="pay_btn" href="javascript:void(0);" onclick="checkoutV2();" class="btn btn-primary submit-btn">
                                            <?php echo $language['lg_proceed_to_book'] ?? ""; ?>
                                    </a>`
                                    : `<a class="apt-btn" id="selectPetBtn">
                                            <?php echo $language['lg_continue'] ?? ""; ?>
                                      </a>`
                                }
                            </div>

                        <?php } ?>

                    </div>
                </div>

                
            </div>
        </div>
        </div>
      </div>
    </div>
</div>`);

           
            }
        }
        
        
        $(document).on('click', '#savePetSelect', function(event) {
            event.preventDefault();
            var selectedPet = $('input[name="petSelectedId"]:checked');
            if (!selectedPet.length) {
                toastr.warning('Please select pet');
                return;
            }

            var selectedPetId = selectedPet.val(); // pet ID from value attribute

            var pet_data = {
                pet_id: selectedPetId,
                pet_name: selectedPet.data('pet-name'),
                pet_photo: selectedPet.data('pet-photo'),
                pet_type: selectedPet.data('pet-type'),
                reason_pet_visit: $('#reasonPetVisit').val() ?? '',
            };

            $('#pet_id').val(selectedPetId);
            $('#reason_pet_visit').val($('#reasonPetVisit').val());

            localStorage.setItem(sessionStorePet, JSON.stringify(pet_data));
            window.location.reload(true);
        });
        
        function initSlots() {
            var $allSlots = $(`.slots-grid .slot`);

            // Show only the first 2 slots initially
            if ($allSlots.length > 5) {
                $allSlots.slice(5).hide(); // Hide all slots except the first two
                $(`.see-more`).css('display', 'block');
            }

            // See more button click
            $(`.see-more`).on('click', function() {
                openPopup(); // Show the popup with hidden slots
            });

            // Close popup
            $('.close-btn').on('click', function() {
                $('#popup').fadeOut(); // Close the popup when the close button is clicked
            });
    }
    
   
        
        
    function getScheduleV2(date=null)
       {
        if(date){
            var schedule_date = date.split("/").reverse().join("-");  
            if(schedule_date == ''){
                $('#schedule_date_error').html('<small class="help-block" data-bv-validator="notEmpty" data-bv-for="schedule_date" data-bv-result="INVALID" style="color:red;">'+lg_date_is_require+'</small>');
                return false;
            }  
        }else{
            var date = $('#schedule_date').val(); 
            var schedule_date = date.split("/").reverse().join("-");  
            if(schedule_date == ''){
                $('#schedule_date_error').html('<small class="help-block" data-bv-validator="notEmpty" data-bv-for="schedule_date" data-bv-result="INVALID" style="color:red;">'+lg_date_is_require+'</small>');
                return false;
            }  
        }
        
    
        $('#schedule_date_error').html('');
        var doctor_id = $('#doctor_id').val();
        $.post(base_url+'get-schedule-from-date',{schedule_date:schedule_date,doctor_id:doctor_id},function(response){
        console.log(response);
        if(!isMobileView()){
            $('.slots-doctor-day').html(response);
            $('[data-toggle="tooltip"]').tooltip();
            $('#schedule_date').val(schedule_date);
            initSlots();
        }else{
            $('.today-slots-doctor-day').html(response);
            $('.arrow-back-btn-original').remove();
            $('.see-more').css('display', 'none');
            $('.slots-grid').addClass('slots-grid-mobile');
            $('.slots-grid').css({
                "width": "100%"
            });

            $('.slots-grid .slot').css({
                "background-color": "#FFFFFF"
            });
            $('.verified-specialists .specialist-cards').addClass('specialist-cards-mobile');
            $('.verified-specialists .card').addClass('verified-specialists-mobile');
            $('[data-toggle="tooltip"]').tooltip();
        }
        addActiveSlot();
        });
    }
    function isMobileView() {
    return window.matchMedia('(max-width: 767px)').matches;
    }

    
    const checkSlotChoosed = localStorage.getItem(session);
    const checkPetChoosed = localStorage.getItem(sessionStorePet);
    console.log("checkSlotChoosed", checkSlotChoosed);
    if((checkSlotChoosed == null || sessionbeforeRegister == null) && checkPetChoosed == null){
        getScheduleV2(null);
    }

    $('#schedule_date').on('change', async function() {
        getScheduleV2(null);
    });

    if((checkSlotChoosed !=null || sessionbeforeRegister != null) && checkPetChoosed == null){
        var appointmentDateValue = checkSlotChoosed !=null ?
         JSON.parse(checkSlotChoosed).appointment_date :
         JSON.parse(sessionbeforeRegister).appointment_date;

        getScheduleV2(appointmentDateValue);
    }

    $(document).on('click', '#registerBtn', function(){
         $('#register .modal-content').html(`
         <div class="modal-body">
            <h3 class="text-center mb-4">Sign Up</h3>
          <form id="signupForm">
          <input type="hidden" name="password" id="password" value="!Q1w2e3zaxscd">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>First Name*</label>
              <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter First Name" required>
            </div>
            <div class="form-group col-md-6">
              <label>Last Name*</label>
              <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter Last Name" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Email*</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
            </div>
            <div class="form-group col-md-6">
              <label>Mobile Number*</label>
              <div class="input-group">
                <input type="tel" name="mobileno" id="mobileno" class="form-control" placeholder="0 000-00-00" required>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" name="termsCheckbox" class="custom-control-input" id="termsCheckbox" required>
              <label class="custom-control-label" for="termsCheckbox">
                TextTeo <a href="javascript:void(0)">Terms and Conditions</a> & <a href="javascript:void(0)">Privacy Policy</a>
              </label>
            </div>
          </div>

          <button type="submit" id="registerAction" class="btn btn-warning btn-block text-white">CONTINUE</button>

          <div class="text-center my-3">or</div>

          <!--
          <div class="d-flex justify-content-around mb-3">
            <button type="button" class="btn btn-outline-primary"><i class="fab fa-facebook-f"></i> Facebook</button>
            <button type="button" class="btn btn-outline-danger"><i class="fab fa-google"></i> Google</button>
            <button type="button" class="btn btn-outline-dark"><i class="fab fa-apple"></i> Apple</button>
          </div>
          -->

          <p class="text-center">
            Have an account? <a id="registerFormLoginBtn" href="">Log in</a>
          </p>
        </form>
        </div>
         `);

         $('#register').modal('show');
    });

    function changePasswordModal(userIdValue){
       $('#changePassword .modal-content').html(`
        <h2 style="padding:2%;font-weight:500;font-size:24px;color:#252525">Think of a password</h2>
        <p style="padding:2%;font-weight:400;font-size:16px;color:#757575">Before you continue, you will need to create a password</p>
            <form method="post" action="#" autocomplete="off" id="change_password">
            <div class="row">
            <input type="hidden" name="user_id" id="userId" value="${userIdValue}">
                <div class="col-md-6 col-sm-6 col-xl-6 form-group" <?php if(!empty(session('redirect_activate'))){?>style="display: none;" <?php }?>>
                    <label><?php echo $language['lg_current_passwor']??" ";?> <span class="text-danger">*</span></label>
                    <input type="password" value="!Q1w2e3zaxscd" name="currentpassword" id="currentpasswordCase" class="form-control">
                    <span class="far fa-eye" id="togglecurrentpasswordCase"></span>
                </div>
                <div class="col-md-6 col-sm-6 col-xl-6 form-group">
                    <label><?php echo $language['lg_new_password']??" ";?> <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="passwordCase" class="form-control">
                    <span class="far fa-eye" id="togglenewpasswordCase"></span>
                </div>
                <div class="col-md-6 col-sm-6 col-xl-6 form-group">
                    <label><?php echo $language['lg_confirm_passwor']??" ";?> <span class="text-danger">*</span></label>
                    <input type="password" name="confirm_password" id="confirmPasswordCase" class="form-control">
                    <span class="far fa-eye" id="toggleconfirmpasswordCase"></span>
                </div>
                <div class="col-md-12 col-sm-12 col-xl-12 submit-section">
                    <button type="submit" id="change_password_btn_saved" class="btn btn-primary submit-btn"><?php echo $language['lg_save_changes']??" ";?></button>
                </div>
            </div>
                
            </form>
        `);

        $('#changePassword').modal('show');
    }

    function loginModal(){
       $('#loginModal .modal-content').html(`
        <h2 style="padding:3%;font-weight:500;font-size:24px;color:#252525">Log in</h2>
            <form method="post" action="#" style="padding: 3%;" autocomplete="off" id="signin_form">
            <div class="row">
            
                <div class="col-md-6 col-sm-6 col-xl-6 form-group" <?php if(!empty(session('redirect_activate'))){?>style="display: none;" <?php }?>>
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" value="" name="email" id="email" class="form-control">
                </div>
                <div class="col-md-6 col-sm-6 col-xl-6 form-group">
                    <label>Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="passwordCase" class="form-control">
                    <span class="far fa-eye" id="togglenewpasswordCase"
                    style="position: absolute; top: 66%; right: 21px; transform: translateY(-50%);
                    cursor: pointer; color: #666;"></span>
                </div>
               <div class="w-100" style="padding:3%">
               <button type="submit" id="loginBtn" class="btn btn-warning w-100 mb-3">
                            Login
                </button>
               </div>
               
            </div>
                
            </form>
        `);

        $('#loginModal').modal('show');
    }

    $(document).on('click', '#registerFormLoginBtn', function(event){
        event.preventDefault();
        $('#register').modal('hide');
        loginModal();
    });

    $(document).on('click', '#loginBtn', function(event){
        event.preventDefault();
           $("#signin_form").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 6
                },
            },
            messages: {
                email: {
                    required: lg_please_enter_em1
                },
                password: {
                    required: lg_please_enter_pa,
                    minlength: lg_your_password_m
                },

            },
            submitHandler: function (form) {
                console.log($("#signin_form").serialize());
                $.ajax({
                    url: base_url + 'user-login',
                    data: $("#signin_form").serialize(),
                    type: "POST",
                    beforeSend: function () {
                        $('#loginBtn').attr('disabled', true);
                        $('#loginBtn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#loginBtn').attr('disabled', false);
                        $('#loginBtn').html(lg_signin);

                        var obj = JSON.parse(res);

                        if (obj.status === 200)
                        {
                            if(localStorage.getItem(session)){                                
                                const tempSessionKey = `${doctor_id}-session-appointment-${obj.user_id}`;
                                const sessionValue = localStorage.getItem(session);
                                localStorage.setItem(tempSessionKey, sessionValue);
                                session = tempSessionKey;
                            }

                            const url = window.location.href;
                            const hasQuery = url.indexOf('?') !== -1;
                            if (hasQuery) {
                              const cleanUrl = url.split('?')[0];
                              window.location.href = cleanUrl;
                            }else{
                                window.location.reload(true);  
                            }                      
                        } else
                        {
                            toastr.error(obj.msg);
                        }
                    }
                });
                return false;
            }
        });
        if($("#signin_form").valid()){
            $("#signin_form").submit();
        }
    });

    $(document).on('click', '#change_password_btn_saved', function(event){
       event.preventDefault();
       var $form = $("#change_password");
       var userIdValue = localStorage.getItem(userIdSession);
    // Initialize validation
    $form.validate({
        rules: {
                currentpassword: {
                    required: true,
                    remote: {
                        url: base_url + "check-password-v2",
                        type: "post",
                        data: {
                            currentpassword: function () {
                                return $("#currentpasswordCase").val();
                            },'userId': userIdValue

                        }
                    }
                },

                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20,
                    password_req:true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#passwordCase",
                    maxlength: 20,
                },
            },
            messages: {
                currentpassword: {
                    required: lg_please_enter_cu,
                    remote: lg_your_current_pa
                },
                password: {
                    required: lg_please_enter_new_pa,
                    minlength: lg_your_password_m,
                    maxlength: lg_password_max_length_20
                },
                confirm_password: {
                    required: lg_please_enter_co,
                    equalTo: lg_your_password_d,
                    maxlength: lg_confirm_password_max_length_20
                },

        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {

            var formData = new FormData(form);

            $.ajax({
                url: base_url + 'change-password-v2',
                data: formData,
                type: "POST",
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#change_password_btn_saved').attr('disabled', true).html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (res) {
                    $('#change_password_btn_saved').attr('disabled', false);
                    var obj = JSON.parse(res);
                    if (obj.status === 200) {
                        toastr.success(obj.msg);
                        setTimeout(function () {
                            $('#changePassword').modal('hide');
                            const currentUrl = window.location.href;
                            const url = new URL(currentUrl);
                            url.search = '';
                            url.searchParams.set('login', 'true');
                            window.location.href = url.toString();
                        }, 2000);
                    } else {
                        toastr.error(obj.msg);
                    }
                },
                error: function (xhr, status, error) {
                    toastr.error('Something went wrong. Please try again.');
                    $('#registerAction').attr('disabled', false).html('CONTINUE');
                    console.error(error);
                }
            });

            return false; // prevent default form submit
        }
    });

    if($form.valid()){
        $form.submit();
    }

    });

    function registerSuccessModal(){
        $('#thankYouModal .modal-content').html(`
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" 
              style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 24px; color: #888;"></button>
      
      <!-- Icon -->
      <div style="margin-bottom: 30px;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
             stroke="orange" style="width: 100px; height: 100px; margin: auto;">
          <circle cx="12" cy="12" r="10" stroke-width="2"/>
          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                d="M9 12l2 2 4-4"/>
        </svg>
      </div>

      <!-- Title -->
      <h2 style="margin: 0; font-size: 24px; color: #222; font-weight: bold;">
        Thank you<br>for registration
      </h2>

      <!-- Subtitle -->
      <p style="margin-top: 20px; font-size: 18px; color: #888;">
        Email with instructions sent<br>to your mailbox
      </p>
        `);

        $('#thankYouModal').modal('show');
    }

   $(document).on('change', '#timezone', function(){
        const timezone = $(this).val();
        $.ajax({
            url: base_url + 'ajax/set-timezone',
            data: {timezone: timezone},
            type: "POST",
            success: function (res) {
                window.location.reload(true);
            }
        });
   });

    
    $(document).on('click', '#registerAction', function(event){
    event.preventDefault();  // Prevent the default form submission

    var $form = $("#signupForm");

    // Initialize validation
    $form.validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2
            },
            last_name: {
                required: true,
                minlength: 2
            },
            mobileno: {
                required: true,
                minlength: 12
            },
            email: {
                required: true,
                email: true
            },
            termsCheckbox: {
                required: true
            }
        },
        messages: {
            first_name: {
                required: "Please enter your first name",
                minlength: "At least 2 characters required"
            },
            last_name: {
                required: "Please enter your last name",
                minlength: "At least 2 characters required"
            },
            mobileno: {
                required: "Please enter your mobile number",
                minlength: "At least 12 characters required"
            },
            email: {
                required: "Please enter your email",
                email: "Enter a valid email"
            },
            termsCheckbox: {
                required: "You must agree to the terms"
            }
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        highlight: function (element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            console.log('Form is valid, submitting via AJAX');

            var formData = new FormData(form);
            formData.append('role', 2);
            formData.append('password', '!Q1w2e3zaxscd');
            formData.append('confirm_password', '!Q1w2e3zaxscd');
            formData.append('case', 'QLS28lY0bAu914UOBiXZZFZ5HH80AgiO');
             const path = window.location.pathname;
             const id = path.split('/').pop();
             formData.append('doctorEnc', id);
             console.log("doctor id", id);

            $.ajax({
                url: base_url + 'user-register',
                data: formData,
                type: "POST",
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#registerAction').attr('disabled', true).html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (res) {
                    $('#registerAction').attr('disabled', false);
                    var obj = JSON.parse(res);
                    if (obj.status === 200) {
                        console.log(obj.input_data);
                        
                        if(localStorage.getItem(session)){
                            sessionbeforeRegister = doctor_id+'-session-appointment-'+obj.input_data.id;
                            
                            console.log("sessionbeforeRegister", sessionbeforeRegister);
                            localStorage.setItem(sessionbeforeRegister, localStorage.getItem(session));
                            session = doctor_id+'-session-appointment-'+obj.input_data.id
                            //localStorage.removeItem(session);
                        }
                        localStorage.setItem(userIdSession, obj.input_data.id);
                        toastr.success(obj.msg);
                        setTimeout(function () {
                            $('#register').modal('hide');
                            registerSuccessModal();
                        }, 2000);
                        $('#registerBtn').prop('disabled', true); 
                    } else {
                        toastr.error(obj.msg);
                    }
                },
                error: function (xhr, status, error) {
                    toastr.error('Something went wrong. Please try again.');
                    $('#registerAction').attr('disabled', false).html('CONTINUE');
                    console.error(error);
                }
            });

            return false; // prevent default form submit
        }
    });

    if($form.valid()){
        $form.submit();
    }
});

const params = new URLSearchParams(window.location.search);
const keyword = params.get('keyword') || null;
const keywordCase = params.get('keyword-case') || null;
const login = params.get('login') || null;


if(userId == ''){
    if(keywordCase != null &&  keywordCase == 'QLS28lY0bAu914UOBiXZZFZ5HH80AgiO'){
    if(keyword){
        var userIdValue = localStorage.getItem(userIdSession);
        console.log("userId", userIdValue);
        if(userIdValue){
         changePasswordModal(userIdValue);
        }
    }
}

 if(keywordCase == null && login){
        loginModal();
}
}


const togglecurrentpassword = document.querySelector('#togglecurrentpasswordCase');
const currentpassword = document.querySelector('#currentpasswordCase');
if(togglecurrentpassword && currentpassword){
togglecurrentpassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type1 = currentpassword.getAttribute('type') === 'password' ? 'text' : 'password';
    currentpassword.setAttribute('type', type1);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});
}


const togglenewpassword = document.querySelector('#togglenewpasswordCase');
const password = document.querySelector('#passwordCase');
if(togglenewpassword && password){
    togglenewpassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type2 = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type2);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });
}


const toggleconfirmpassword = document.querySelector('#toggleconfirmpasswordCase');
const confirm_password = document.querySelector('#confirmPasswordCase');
if(toggleconfirmpassword && confirm_password){
    toggleconfirmpassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type3 = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
        confirm_password.setAttribute('type', type3);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });
}

    // $('#selectPetBtn').prop('disabled', true).css({
    //     opacity: '0.6',
    //     pointerEvents: 'none',
    //     cursor: 'not-allowed',
    //     });

    // $(document).on('click', '.slot', function() {
    //     $('.slot').removeClass('active');
    //     $(this).addClass('active');

    //     var appointment = {
    //     appointment_token:       $(this).data('token'),
    //     appointment_date:        $(this).data('date'),
    //     appointment_timezone:    $(this).data('timezone'),
    //     appointment_start_time:        $(this).data('start-time'),
    //     appointment_end_time:     $(this).data('end-time'),
    //     appointment_session:     $(this).data('session'),
    //     appointment_type:     $(this).data('schedule-type'),
    //    };

    //     var doctor_id = $('#doctor_id').val();
    //     sessionStorage.setItem(session, JSON.stringify(appointment));
    //     console.log(sessionStorage.getItem(session));
    //     document.querySelector('#selectPetBtn').disabled = false;
    // });
    
    });

    document.addEventListener("DOMContentLoaded", function () {
    // Disable the button initially
    const selectPetBtn = document.querySelector('#selectPetBtn');
    const selectPetBtnMobile = document.querySelector('.apt-btn-mobile');
    
    if(selectPetBtn && (sessionbeforeRegister != null || session != null)){
        selectPetBtn.disabled = true;
        selectPetBtn.style.opacity = '0.6';
        selectPetBtn.style.pointerEvents = 'none';
        selectPetBtn.style.cursor = 'not-allowed';
    }

      if(selectPetBtnMobile && (sessionbeforeRegister != null || session != null)){
        selectPetBtnMobile.disabled = true;
        selectPetBtnMobile.style.opacity = '0.6';
        selectPetBtnMobile.style.pointerEvents = 'none';
        selectPetBtnMobile.style.cursor = 'not-allowed';
    }
    
    // When a slot is clicked, enable the button
    document.addEventListener('click', function (event) {
        if (event.target && event.target.classList.contains('slot')) {
            // Remove active class from other slots and add to the selected slot
            document.querySelectorAll('.slot').forEach(slot => slot.classList.remove('active'));
            event.target.classList.add('active');

            // Store appointment details in sessionStorage
            const appointment = {
                appointment_token: event.target.dataset.token,
                appointment_date: event.target.dataset.date,
                appointment_timezone: event.target.dataset.timezone,
                appointment_start_time: event.target.dataset.startTime,
                appointment_end_time: event.target.dataset.endTime,
                appointment_session: event.target.dataset.session,
                appointment_type: event.target.dataset.scheduleType,
            };

            localStorage.setItem(sessionSlot, appointment.appointment_token);

            const doctorId = document.querySelector('#doctor_id').value;
            localStorage.setItem(session, JSON.stringify(appointment));

            console.log(localStorage.getItem(session));
            console.log(session);
            // Enable the button
            if(selectPetBtn){
                selectPetBtn.disabled = false;
                selectPetBtn.style.opacity = '1';
                selectPetBtn.style.pointerEvents = 'auto';
                selectPetBtn.style.cursor = 'pointer';
            }

            
            if(selectPetBtnMobile){
                selectPetBtnMobile.disabled = false;
                selectPetBtn.style.opacity = '1';
                selectPetBtn.style.pointerEvents = 'auto';
                selectPetBtn.style.cursor = 'pointer';
            }
            
             if (event.target.classList.contains('popup-slot')) {
                const $visibleSlots = $('.slots-grid .slot:visible');
                const $popupSlot = $(event.target);
                const selectedToken = $popupSlot.data('token');

                const $swapOut = $visibleSlots.last(); // Last visible slot to be swapped out
                const swapOutToken = $swapOut.data('token');

                // Find real slot in grid to show (matched by token)
                const $realSlot = $(`.slots-grid .slot[data-token="${selectedToken}"]`);
                $realSlot.show();

                // Hide and clone the one we swap out
                $swapOut.hide();
                const $clone = $swapOut.clone().addClass('popup-slot').css('display', 'block');

                // Remove selected popup clone
                $popupSlot.remove();

                // Append new clone to popup
                $('.popup-slots').append($clone);
                 $('.slots-grid .slot').removeClass('active');
                 $realSlot.addClass('active');
                $('#popup').fadeOut();
            }
        }
    });
});

function addActiveSlot(){
    const savedToken = localStorage.getItem(sessionSlot);
    console.log('savedToken', savedToken);
    if (savedToken) {
        const savedSlot = document.querySelector(`.slot[data-token="${savedToken}"]`);
        console.log(savedSlot);
        if (savedSlot) {
            savedSlot.classList.add('active');
            const selectPetBtn = document.querySelector('#selectPetBtn');
            if(selectPetBtn){
                selectPetBtn.disabled = false;
                selectPetBtn.style.opacity = '1';
                selectPetBtn.style.pointerEvents = 'auto';
                selectPetBtn.style.cursor = 'pointer';
            }
            const selectPetBtnMobile = document.querySelector('.apt-btn-mobile');
            if(selectPetBtnMobile){
                selectPetBtnMobile.disabled = false;
                selectPetBtnMobile.style.opacity = '1';
                selectPetBtnMobile.style.pointerEvents = 'auto';
                selectPetBtnMobile.style.cursor = 'pointer';
            }

        }
    }
}


    function openPopup() {
        const $allSlots = $('.slots-grid .slot');
        const $popupContent = $('.popup-content .remaining_slots');

        $popupContent.find('.popup-slots').remove(); // Clear old clones

        const $popupSlots = $('<div class="popup-slots" style="margin-top: 15px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;"></div>');

        // Get hidden slots and clone
        $allSlots.filter(':hidden').each(function () {
            const $clone = $(this).clone();
            $clone.addClass('popup-slot').css('display', 'block');
            $popupSlots.append($clone);
        });

        $popupContent.append($popupSlots);
        $('#popup').fadeIn();
}

  function historyBack(){
    localStorage.clear();
    window.location.reload(true);
  }

  function historyBackOriginal(){
    localStorage.clear();
    history.back();
  }


// checkout

function checkoutV2(){
    var doctor_id = $('#doctor_id').val();
    var hourly_rate = $('#hourly_rate').val();
    var price_type = $('#price_type').val();
    var role_id = $('#role_id').val();
    
    //var type = $("input[name='type']:checked"). val();  
    var type;
  
  
    if(role_id==6)
    {
      type="Clinic";
    }
    else
    {
      type="Online";
    }

    const appointments_data = JSON.parse(localStorage.getItem(session));
  
    var appoinment_token = appointments_data['appointment_token'];
    const petSelected = JSON.parse(localStorage.getItem(sessionStorePet));
    var appoinment_pet_id= petSelected.pet_id;
    var petVisitReason = petSelected.reason_pet_visit;
    
        if(!appoinment_token || appoinment_token == ''){
          toastr.warning(lg_please_select_a1);
           return false;
      }
      
      if(typeof appoinment_pet_id=='undefined' || appoinment_pet_id=="" || appoinment_pet_id==null || appoinment_pet_id=="null"){
         toastr.warning('Please select pet');
           return false;
      }

      
      var appointment_data = [];
       appointment_data.push({
                  'appoinment_token':appointments_data['appointment_token'],
                  'appoinment_date':appointments_data['appointment_date'],
                  'appoinment_timezone':appointments_data['appointment_timezone'],
                  'appoinment_start_time':appointments_data['appointment_start_time'],
                  'appoinment_end_time':appointments_data['appointment_end_time'],
                  'appoinment_session':appointments_data['appointment_session'],
                  'appointment_type':appointments_data['appointment_type'],
                  'type':type,
                  'appoinment_pet_id':appoinment_pet_id,
                  'reason' : petVisitReason
              });
              
              var appointment_details = JSON.stringify(appointment_data);
  
          $('#pay_btn').attr('disabled',true);
          $('#pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
          
          $.post(base_url+'set-booked-session',{
              hourly_rate:hourly_rate,
              appointment_details:appointment_details,
              price_type:$('#price_type').val(),
              doctor_id:doctor_id,
              doctor_role_id:role_id
              
          },function(res){
  
            var obj = JSON.parse(res);
              if(obj.status===200)
              {
                  localStorage.removeItem(session);
                  localStorage.removeItem(sessionStorePet);
                  localStorage.removeItem(sessionAmountInfo);
                  localStorage.removeItem(sessionbeforeRegister);
                   if(localStorage.getItem(userIdSession)){
                    localStorage.removeItem(userIdSession)
                  }
                  localStorage.removeItem(sessionSlot);
                  setTimeout(function(){ window.location=base_url+'checkout-appoinment'; },1000);
              }
              else if(obj.status===500)
              {
                toastr.error(obj.message);
                $('#pay_btn').attr('disabled',false);
                $('#pay_btn').html(lg_proceed_to_pay);
              }
              else
              {
                  localStorage.removeItem(session);
                  localStorage.removeItem(sessionStorePet);
                  localStorage.removeItem(sessionAmountInfo);
                  localStorage.removeItem(sessionbeforeRegister);
                  if(localStorage.getItem(userIdSession)){
                    localStorage.removeItem(userIdSession)
                  }
                  localStorage.removeItem(sessionSlot);
                  toastr.success(lg_transaction_suc);
                  setTimeout(function() {
                    window.location.href=base_url+modules;
                }, 2000);
              }   
          });
  
    }

</script>
<?php $this->endSection(); ?>