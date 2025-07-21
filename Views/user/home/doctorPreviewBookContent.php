<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

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
    width: 80px;
    height: 80px;
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

    .slots-doctor-day ul li .price_value{
        color:#757575;
        font-weight: 400;
        font-size: 14px;
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
@media (max-width: 576px) {
    .doc-info-right{
        margin-right: 14%;
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
                    <div class="row">
                        <div class="col-md-12 summary-section-col">
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
                                    <?php echo ucfirst(libsodiumDecrypt($doctors['first_name']) . ' ' . libsodiumDecrypt($doctors['last_name'])); ?>
                                </h4>
                                <?php if ($doctors['role'] != 6) { ?>
                                    <p class="doc-department"><?php echo ucfirst(libsodiumDecrypt($doctors['speciality'])); ?></p>
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
                                    <span class="d-inline-block average-rating">(<?php echo $doctors['rating_count']; ?>)</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="doctor-section col-md-6">
                    <label class="pet_label">Pet</label>
                    <!-- Pet info -->
                    <div class="doc-info-pet pet-selected d-flex align-items-center rounded ">
                        <img src="" alt="" class="rounded-circle" width="48" height="48">
                        <div class="ml-3 flex-grow-1">
                            <div class="font-weight-bold">lucky</div>
                            <div class="text-muted small">jerman</div>
                        </div>
                    </div>

               
                    

                    </div>
                        
     <!-- reason -->
     <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 reason-section-col">
                        <div class="reason-section">
                            <h3 class="reason-section-title">Reason for visit</h3>
                           <p class="reason-section-p">
                           Whiskers was brought in due to decreased appetite and lethargy over the past few days                           </p>
                        </div>
                </div>
                <!-- appointment -->
                <div class="col-md-12 col-lg-12 col-xl-12">
                   <ul class="appointment-details">
                   <li>
                        <h3>Date</h3>
                        <p>29 Apr 2025</p>
                    </li>
                    <li>
                        <h3>Time</h3>
                        <p>08:30 AM</p>
                    </li>
                                        
                   </ul>
                </div>
         <!-- appointment -->
    </div>
                    <!-- reeson -->
                        
                    </div>
                </div>
                
                                
                    <div class="doc-info-right" >
                        <div class="clini-infos">
                        <div class="slot-container">
                        
                        <div class="slot-header">
                        
                            <input type="hidden" name="schedule_date" id="schedule_date" value="<?php echo Date('Y-m-d') ?>" min="<?php echo date("Y-m-d"); ?>">
                            <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctors['userid'];?>">
                            <input type="hidden" name="price_type" id="price_type" value="<?php echo $doctors['price_type'] ?>">
                            <input type="hidden" name="hourly_rate" id="hourly_rate" value="<?php echo $doctors['amount'] ?>">
                            <input type="hidden" name="role_id" id="role_id" value="<?php echo $doctors['role'] ?>">
                            <input type="hidden" name="pet_id" id="pet_id" value="">

                        </div>
                        <div class="slots-doctor-day">
                            <div class="row">
                                <div class="col-md-12"><strong><h2 style="float:left">Price</h2></br/></strong></div>
                                <div class="col-md-12">
                                    <ul>
                                        <li>
                                            <strong style="float: left">Call Charge</strong>
                                            <strong style="float: right" class="price_value"> 200$</strong>
                                        </li>
                                        <li>
                                            <strong style="float: left">Transaction charge (0%)</strong>
                                            <strong style="float: right" class="price_value"> 0.0$</strong>
                                        </li>
                                        <li>
                                            <strong style="float: left">VAT (5%)</strong>
                                            <strong style="float: right" class="price_value"> 0.0$</strong>
                                        </li>
                                        <li>
                                            <strong style="float: left">Discount</strong>
                                            <strong style="float: right" class="price_value"> 0.0$</strong>
                                        </li>
                                   </ul>
                                </div>
                            
                                <div class="col-md-12">
                                <ul>
                                <?php if (!empty($amount)) { ?>
                                            <li style="color:#FD9720;font-size:20px">
                                            <p>
                                            <strong style="float: left">Total</strong>
                                            <strong style="float:right;"><?php echo $amount; ?></strong>
                                            </p>
                                        </li>
                                                                                
                                <?php } ?>
                                </ul>
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
                                            <li style="margin: 0;">
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
                                <a class="apt-btn" href="<?php echo base_url() . 'book-appoinments/' . encryptor_decryptor('encrypt', libsodiumDecrypt($doctors['username'])); ?>"> <?php echo $language['lg_pay_appointment'] ?? ""; ?></a>
                            </div>

                        <?php } ?>

                    </div>
                </div>

                
            </div>
        </div>
        <!-- /Doctor Widget -->

        <!-- Doctor Details Tab -->
        
        <!-- /Doctor Details Tab -->
        </div>
      </div>
    </div>
</div>
<!-- /Page Content -->

<script type="text/javascript">
    var country = '';
    var state = '';
    var city = '';
    var specialization = '';
</script>
<script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>

<script type="text/javascript">

</script>
<?php $this->endSection(); ?>