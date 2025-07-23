<?php 
    // Detail veterinar (Book Appointment)
    use App\Libraries\SvgIcons;
    use App\Models\userModel;
    $this->extend('user/layout/header'); 
    $current_timezone = session('time_zone');
    $user = user_detail(session('user_id'));
    $profileimage = file_exists($doctors['profileimage']) ? '/' . $doctors['profileimage'] : '/assets/img/user.png';
    $doc_dept = file_exists($doctors['specialization_img']) ? '/' . $doctors['specialization_img'] : 'https://via.placeholder.com/64x64.png?text=Specialization';
    
    $name = libsodiumDecrypt($doctors['first_name']).' '.libsodiumDecrypt($doctors['last_name']);
    $breadcrumb_title = $language['lg_dr'] . $name ?? '';
    $currentTimezone = session('time_zone'); 
    $rating_value = $doctors['rating_value'];
    $id = session('user_id') && user_detail(session('user_id'))['first_name'] != null ? 'savePetSelect' : 'saveRequiredUserInfoBtn';
    
    $pets = new userModel();
    $all_pets = $pets->getPetsByPatientId(session('user_id'));
?>
<?php $this->section('title');?>
<?=$breadcrumb_title;?>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="breadcrumb-bar">
    <div class="container mt-2 mt-md-5 pt-5">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/" class="text-decoration-none">
                                <?=$language['lg_home'] ?? ""; ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="/search-veterinary" class="text-decoration-none">
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
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <?=$breadcrumb_title;?>
                        </li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">
                    <?php
                        if ($doctors['role'] == 6) { // clinic
                            echo $language['lg_clinic_profile'] ?? "";
                        } elseif ($doctors['role'] == 1) { // doctor
                            echo $breadcrumb_title;
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
            <?=$user ? view('user/layout/sidebar') : '';?>
            <div class="profile-sidebar col-12 <?=$user ? 'col-md-9' : 'col-md-12';?>">
                <div class="row doctor-widget py-4">
                    <div class="col-12 col-md-8">
                        
                        <div class="d-flex gap-2">
                            <div>
                                <button 
                                    class="btn btn-outline-dark" 
                                    onclick="historyBackOriginal()" 
                                    style="background-color: transparent"
                                > 
                                    <?=SvgIcons::chevronLeft(['size' => 15, 'fill' => '#252525']);?>
                                </button>                                
                            </div>
                        
                            <div class="d-flex flex-grow-1">
                                <div class="doctor-img">
                                    <img 
                                        src="<?=$profileimage; ?>" 
                                        style="width:100px;height:100px;border-radius:12px" 
                                        class="img-fluid" 
                                        alt="User Image" 
                                    />
                                </div>
                                <div class="doc-info-cont flex-fill">
                                    <h4 class="d-flex justify-content-between doc-name">
                                        <span><?=$doctors['role'] != 6 ? $language['lg_dr']  : '';?> <?=ucfirst($name); ?></span>
                                        <div class="license">License verified</div>
                                    </h4>
                                    <?php if ($doctors['role'] != 6) { ?>
                                        <p class="doc-department" style="font-weight:500;font-size:14px;color:#757575">
                                            <?=ucfirst(libsodiumDecrypt($doctors['speciality'])); ?>
                                        </p>
                                    <?php } ?>
                                    <div class="rating gap-1">
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <i class="fas fa-star<?=$i <= $rating_value ? ' filled' : '';?>" style="font-size: 14px;"></i>
                                        <?php } ?>
                                        <span class="d-inline-block average-rating">(<?=$doctors['rating_count']; ?>)</span>
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
                                            } ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sections Review and Overview -->
                        
                        <div class="w-100 pt-0">
                            <nav class="user-tabs mb-4">
                                <ul class="nav nav-tabs w-100 border-0 nav-fill">
                                    <li class="nav-item">
                                        <a 
                                            class="nav-link active" 
                                            href="#doc_overview" 
                                            data-toggle="tab"
                                        >
                                            <?=$language['lg_overview'] ?? '';?>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a 
                                            class="nav-link" 
                                            href="#doc_reviews" 
                                            data-toggle="tab"
                                        >
                                            <?=$language['lg_reviews'] ?? ""; ?>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            
                            <div class="tab-content pt-0">

                                <!-- Overview Content -->
                                <div role="tabpanel" id="doc_overview" class="tab-pane fade show active">
                                    <div class="vstack gap-2">
                                        <p>
                                            <strong>Experience:</strong>
                                            <span> 6 years</span>
                                        </p>
                                        <p>
                                            <strong>Languages:</strong>
                                            <span> English, Arabic, Russian</span>
                                        </p>
                                        <p>
                                            <strong>What it will help with:</strong>
                                            <span> English, Arabic, Russian</span>
                                        </p>
                                        <p>
                                            <strong>Species treated:</strong>
                                            <span> Cat, Dog, Rodents</span>
                                        </p>
                                        <p style="display: flex;gap: 7px">
                                            <strong>Description:</strong>
                                            <span> My name is Dr. Emma Smith and I specialize in animal behavior diagnostics</span>
                                        </p>
                                    </div>

                                    <h2 class="card-title mt-4 mb-2">How to use?</h2>
                                    <div class="vstack gap-3 how-to-use">
                                        <div class="d-flex gap-2 rounded-4 bg-white p-3">
                                            <div class="square">1</div>
                                            <div class="step-text">
                                                <strong>Book an online consultation</strong>
                                                <p>Select a clinic, veterinarian, date and time</p>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 rounded-4 bg-white p-3">
                                            <div class="square">2</div>
                                            <div class="step-text">
                                                <strong>Pay for online consultation</strong>
                                                <p>After payment, expect a consultation on the specified date and time</p>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 rounded-4 bg-white p-3">
                                            <div class="square">3</div>
                                            <div class="step-text">
                                                <strong>Get a veterinarian’s recommendation</strong>
                                                <p>After the consultation, you will receive recommendations for the treatment of your pet</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-block">
                                        <h2 class="card-title mt-5 mb-2">Only verified specialists</h2>
                                        <div class="d-flex gap-3">
                                            <div class="col d-flex align-items-center gap-2 rounded-4 bg-white p-3">
                                                <div class="square">
                                                    <?=SvgIcons::career(['size' => 17]);?>
                                                </div>
                                                <span> 5+ Expertise</span>
                                            </div>
                                            <div class="col d-flex align-items-center gap-2 rounded-4 bg-white p-3">
                                                <div class="square">
                                                    <?=SvgIcons::rating(['size' => 17]);?>
                                                </div>
                                                <span>4.9 Avg. Rating</span>
                                            </div>
                                            <div class="col d-flex align-items-center gap-2 rounded-4 bg-white p-3">
                                                <div class="square">
                                                    <?=SvgIcons::certified(['size' => 17]);?>
                                                </div>
                                                <span>100% Certified Vets</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Overview Content -->


                                <!-- Reviews Content -->
                                <div role="tabpanel" id="doc_reviews" class="tab-pane fade">
                                    <div class="widget review-listing">
                                        <ul class="comments-list">
                                            <?php if (!empty($reviews)) {
                                                foreach ($reviews as $rrows) {

                                                    if ($rrows['profileimage'] == "" || ($rrows['profileimage'] != "" && !is_file($rrows['profileimage']))) {
                                                        $rimg = '/assets/img/user.png';
                                                    } else {
                                                        $rimg = (!empty($rrows['profileimage'] ?? "")) ? '/'.$rrows['profileimage'] ?? "" : '/assets/img/user.png';
                                                    }

                                                    if ($rrows['doctor_image'] == "" || ($rrows['doctor_image'] != "" && !is_file($rrows['doctor_image']))) {
                                                        $drimg = '/assets/img/user.png';
                                                    } else {
                                                        $drimg = (!empty($rrows['doctor_image'] ?? "")) ? '/'.$rrows['doctor_image'] ?? "" : '/assets/img/user.png';
                                                    }

                                            ?>

                                                    <!-- Comment List -->
                                                    <li>
                                                        <div class="comment bg-white" style="padding:2%;border-radius: 8px">
                                                            <img class="avatar avatar-sm rounded-circle" alt="User Image" src="<?=$rimg;?>">
                                                            <div class="comment-body">
                                                                <div class="meta-data d-flex justify-content-between align-items-center">
                                                                    <div class="author-info">
                                                                        <span class="comment-author">
                                                                            <?=libsodiumDecrypt($rrows['first_name']).' '.libsodiumDecrypt($rrows['last_name']);?>
                                                                        </span>
                                                                        <span class="comment-date">
                                                                            <?=$language['lg_reviewed'] ?? '';?> 
                                                                            <?=time_elapsed_string($rrows['created_date']);?>
                                                                        </span>
                                                                    </div>
                                                                    
                                    
                                                                    <div class="review-count rating">
                                                                        <?php for ($i = 1; $i <= $rrows['rating']; $i++) { ?>
                                                                            <i class="fas fa-star filled"></i>
                                                                        <?php } ?>
                                                                        <!-- <i class="fas fa-star"></i> -->

                                                                    </div>

                                                                </div>
                                                                <p class="comment-content">
                                                                    <?=$rrows['review']; ?>
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
                                    </div>
                                </div>
                                <!-- /Reviews Content -->

                            </div>
                        </div>


                    </div>
                    <div class="col-12 col-md-4">
                        <div class="rounded-4 bg-white p-3">
                            <div class="clini-infos">
                                <div class="slot-container">
                                    <h2 class="card-title">Select a slot</h2>
                                    <div class="slot-header py-2">
                                        <div class="d-flex align-items-center gap-2 justify-content-center">
                                            <input 
                                                type="date" 
                                                name="schedule_date" 
                                                id="schedule_date" 
                                                value="<?=Date('Y-m-d') ?>" 
                                                min="<?=date("Y-m-d"); ?>"
                                                class="calendar-input"
                                            />
                                            <select class="calendar-input form-select" name="timezone" id="timezone" class="timezone">
                                                <option value="" disabled>timezone</option>
                                                    <?php 
                                                    
                                                    foreach ($timezones as $key => $value) { 
                                                        $selected = ($key === $currentTimezone) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?=$key; ?>" <?=$selected; ?>>
                                                            <?=$value;?>
                                                        </option>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        
                                        <input type="hidden" name="doctor_id" id="doctor_id" value="<?=$doctors['userid'];?>" />
                                        <input type="hidden" name="price_type" id="price_type" value="<?=$doctors['price_type'] ?>" />
                                        <input type="hidden" name="hourly_rate" id="hourly_rate" value="<?=$doctors['amount'] ?>" />
                                        <input type="hidden" name="role_id" id="role_id" value="<?=$doctors['role'] ?>" />
                                        <input type="hidden" name="pet_id" id="pet_id" value="" />
                                    </div>
                                    <!-- Список временных слотов появляется здесь -->
                                    <div class="slots-grid slots-doctor-day slots-doctor-card"></div>
                                    <!-- Конец слотов -->
                                </div>

                                <div class="popup-overlay" id="popup" style="display:none;">
                                    <div class="popup-content bg-white">
                                            <div class="position-absolute top-0 end-0 p-2 close-btn" role="button">&#10006;</div>
                                        <div class="remaining_slots">
                                            <h3>More Available Slots</h3>
                                            <p>Select a slot from below:</p>
                                        </div>
                                        <div class="d-grid">
                                            <button 
                                                class="btn btn-warning close-btn text-uppercase" 
                                                style="margin-top:15px; padding:8px 16px;"
                                            >
                                                Save
                                            </button>                                                
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($amount)) { ?>
                                    <div style="font-size:20px" class="border-top">
                                        <div class="d-flex justify-content-between amount-total py-2 text-warning">
                                            <strong style="float: left">Total</strong>
                                            <strong style="float:right;" class="amount-total-value">
                                                <?=$amount;?> 
                                                <!-- AED -->
                                            </strong>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="d-grid clinic-booking">
                                <?php /*
                                id="<?=($login_role != '' & $login_role == 2) ? 'selectPetBtn' : 'registerBtn';?>"
                                */ ?>
                                <a 
                                    class="apt-btn btn btn-warning btn-lg" 
                                    onclick="selectPetModal.show()"
                                >
                                    <?=$language['lg_continue'] ?? ""; ?>
                                </a>
                            </div>                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>


<?php $this->section('modal'); ?>
<?=view('user/patient/petModalSelect', ['pets' => $all_pets]);?>
<?php $this->endSection(); ?>


<?php $this->section('javascript'); ?>
    <script type="text/javascript">
        var country = '';
        var state = '';
        var city = '';
        var specialization = '';
    </script>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/jquery.validate.js"></script>

    <?php $userId = session('user_id') ? session('user_id') : ''; ?>
    <script type="text/javascript">
        const userId = <?=json_encode($userId); ?>;
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
                    },
            function(res){
                var obj = JSON.parse(res);
                console.log("obj", obj)
                if(obj.status == 500){
                    toastr.warning(obj.message);
                    reject(obj.message);
                }else if(obj.status == 202){
                    localStorage.setItem(sessionAmountInfo, JSON.stringify({'status': 'free'}));
                    $('.amount-total').html(`
                        <strong style="float: left">Total</strong>
                        <strong style="float:right;" class="amount-total-value">Free</strong>                  
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
        
    
            
            
            function getScheduleV2(date=null) {
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
                $.post(base_url+'get-schedule-from-date',{schedule_date:schedule_date,doctor_id:doctor_id},
                    function(response){
                    console.log(response);
                    if(!isMobileView()){
                        $('.slots-doctor-day').html(response);
                        //$('[data-toggle="tooltip"]').tooltip();
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

                


                const params = new URLSearchParams(window.location.search);

                const keywordCase = params.get('keyword-case') || null;
                const login = params.get('login') || null;
            }



        
        );

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

        function addActiveSlot() {
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

            const $popupSlots = $('<div class="popup-slots" style="margin-top: 15px;display: grid;grid-template-columns: repeat(5, 1fr);gap: 8px;overflow-x: hidden;height: 230px;overflow-y: scroll"></div>');

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

        function checkoutV2(){
            var doctor_id = $('#doctor_id').val();
            var hourly_rate = $('#hourly_rate').val();
            var price_type = $('#price_type').val();
            var role_id = $('#role_id').val();
            
            //var type = $("input[name='type']:checked"). val();  
            var type;
        
            if(role_id==6) {
                type="Clinic";
            } else {
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

    <script type="text/javascript">
        const modalElement = document.getElementById('selectPetModal');
        const selectPetModal = new bootstrap.Modal(modalElement);

        function editMode()
        {
            console.log('editMode');
            alert('Pet is Select');
        }

    (function($) {
    "use strict";
        function showUserInfoModal() {
            $('#saveRequiredInfoModal .modal-content').html(`
                <h2 style="padding:2%;">Fill in your details</h2>
                <form class="form-grid" id="saveUserInfoForm">
                <div class="modal-header">
                    <h4 class="modal-title font-weight-bold"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >
                <div class="row">
                
                <div class="col-md-6 col-gl-6 col-sm-6">
                <div class="form-group">
                    <label for="first-name">First Name*</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" />
                </div>
                </div>

                <div class="col-md-6 col-gl-6 col-sm-6">
                <div class="form-group">
                    <label for="last-name">Last Name*</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" />
                </div>
                </div>

                <div class="col-md-6 col-gl-6 col-sm-6">
                <div class="form-group full-width">
                    <label for="mobile">Mobile Number*</label>
                    <div class="phone-input">
                    <input type="text" class="form-control" name="mobileno" id="mobileno" placeholder="0 000-00-00" />
                    </div>
                </div>
                </div>

                </div>
            
                <div class="col-md-12 col-gl-12 col-sm-12">
                <button id="saveRequiredInfoBtn" style="width:100%" class="btn btn-warning text-white rounded-pill px-4">SAVE</button>
                </div>
                </div>
                </form>
            `);
            $('#saveRequiredInfoModal').modal('show');
        }

        $('#saveRequiredUserInfoBtn').click(function(event) {
            event.preventDefault();
            var selectedPet = $('input[name="petSelectedId"]:checked');
                if (!selectedPet.length) {
                    toastr.warning('Please select pet');
                    return;
                }
            var reason = $('#reasonPetVisit').val();
            if (!reason) {
                    toastr.warning('Please enter Reason');
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

                sessionStorage.setItem(sessionStorePet, JSON.stringify(pet_data));
                console.log(sessionStorage.getItem(sessionStorePet));
                $('#selectPetModal').modal('hide');
                showUserInfoModal();

        });


        $(document).on('click', '#saveRequiredInfoBtn', function(event){
            event.preventDefault();
            $("#saveUserInfoForm").validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2
                    },
                    last_name: {
                        required: true,
                        minlength: 2
                    },
                    mobileno:{
                        required: true,
                        minlength: 12
                    }
                    
                },
                messages: {
                    first_name: {
                        required: "Please enter the first name",
                        minlength: "firstName name must be at least 2 characters long"
                    },
                    last_name: {
                        required: "Please enter the last name",
                        minlength: "lastName must be at least 2 characters long"
                    },
                    mobileno: {
                        required: "Please enter the mobile number",
                        minlength: "mobileNumber must be at least 12 characters long"
                    },
                },
                submitHandler: function(form) {
                    var formData = new FormData(form); // Create FormData object from form
                    let url= base_url + 'patient/update-required-proile';

                    $.ajax({
                        url: url,
                        data: formData,
                        type: "POST",
                        processData: false,  // Important: prevent jQuery from processing the FormData
                        contentType: false,  // Important: let the server handle the contentType
                        beforeSend: function () {
                            $('#saveRequiredInfoBtn').attr('disabled', true);
                            $('#saveRequiredInfoBtn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                        success: function (res) {
                            $('#saveRequiredInfoBtn').attr('disabled', false);
                            var obj = JSON.parse(res);
                            if (obj.status === 200)
                            {
                                toastr.success(obj.msg);
                                setTimeout(function () {
                                    window.location.reload(true);
                                }, 2000);
                            } 
                            else
                            {
                                toastr.error(obj.msg);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX error: ' + status, error);
                            toastr.error('An error occurred while processing your request. Please try again.');
                            $('#saveRequiredInfoBtn').attr('disabled', false);
                        }
                    });

                    return false;  // Prevent the form from submitting traditionally
                }

            });
            $("#saveUserInfoForm").submit();
        });
        
        function loadPetModal(petId = null) {
            var ditMode = (petId !== null);
            $.ajax({
                url: base_url + 'patient/getPetModal',
                type: 'POST',
                data: { pet_id: petId },
                success: function(res) {
                    let html=res.html;
                    let pet=res.pet;
                    $('#addPetModal .modal-content').html(html);
                    $('#addPetModal').modal('show');
                    initializeModal(pet);
                    
                    if (editMode) {
                        $('#petPhoto').rules('add', {
                            imageFileType: true
                        });
                    } else {
                        $('#petPhoto').rules('add', {
                            required: true,
                            imageFileType: true
                        });
                    }
                    
                },
                error: function() {
                    toastr.error('Failed to load pet data.');
                }
            });
        } 
        
        $('#addNewPetBtn').click(function() {
            $('#selectPetModal').modal('hide');
            loadPetModal();
        });

        $.validator.addMethod('imageFileType', function(value, element) {
            // Check if element has files (for file input)
            if (element.files && element.files.length > 0) {
                // Get file extension
                var extension = element.files[0].name.split('.').pop().toLowerCase();
                // Check if file extension is in the allowed list
                return ['jpg', 'jpeg', 'png'].indexOf(extension) !== -1;
            }
            return true;  // No file selected, so no validation needed
        }, 'Only JPG, JPEG, or PNG files are allowed.');
        
        function initializeModal(pet=null) {
            $("#petForm").validate({
                rules: {
                    petName: {
                        required: true,
                        minlength: 2
                    },
                    petType: {
                        required: true
                    },
                    petAge: {
                        required: true,
                    },
                    breedType: {
                        required: true
                    },
                    // breedSize: {
                    //     required: true
                    // },
                    // gender: {
                    //     required: true
                    // },
                    // weight: {
                    //     required: true
                    // },
                    // weightCondition: {
                    //     required: true
                    // },
                    // activityLevel: {
                    //     required: true
                    // },
                    petPhoto: {
                        //required: true,
                        //imageFileType: true  // Use custom method for file type validation
                    }
                },
                messages: {
                    petName: {
                        required: "Please enter the pet name",
                        minlength: "Pet name must be at least 2 characters long"
                    },
                    // petBirthDate: {
                    //     required: "Please enter the pet birth date",
                    //     //date: "Please enter a valid date"
                    // },
                    petType: {
                        required: "Please select the pet type"
                    },
                    petAge: {
                        required: "Please enter the pet age",
                    },
                    breedType: {
                        required: "Please select the breed type"
                    },
                    // breedSize: {
                    //     required: "Please select the breed size"
                    // },
                    // gender: {
                    //     required: "Please select the gender"
                    // },
                    // weight: {
                    //     required: "Please select the weight category"
                    // },
                    // weightCondition: {
                    //     required: "Please select the weight condition"
                    // },
                    // activityLevel: {
                    //     required: "Please select the activity level"
                    // },
                    petPhoto: {
                        required: "Please upload a photo of the pet",
                        imageFileType: "Only JPG, JPEG, or PNG files are allowed."
                    }
                },
                submitHandler: function(form) {
                    var formData = new FormData(form); // Create FormData object from form

                    // Append petPhoto file from input field to FormData (if present)
                    var petPhotoFile = $('#petPhoto')[0].files[0];
                    if (petPhotoFile) {
                        formData.append('petPhoto', petPhotoFile);
                    }
                    
                    let url= base_url + 'patient/' + ($('#petId').val() ? 'edit-pet' : 'create-pet');

                    $.ajax({
                        url: url,
                        data: formData,
                        type: "POST",
                        processData: false,  // Important: prevent jQuery from processing the FormData
                        contentType: false,  // Important: let the server handle the contentType
                        beforeSend: function () {
                            $('#create_new_pet_btn').attr('disabled', true);
                            $('#create_new_pet_btn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                        success: function (res) {
                            $('#create_new_pet_btn').attr('disabled', false);
                            $('#create_new_pet_btn').html(lg_save_changes);

                            var obj = JSON.parse(res);

                            if (obj.status === 200) {
                                toastr.success(obj.message);
                                setTimeout(function () {
                                    window.location.reload(true);
                                }, 2000);
                            } else {
                                toastr.error(obj.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX error: ' + status, error);
                            toastr.error('An error occurred while processing your request. Please try again.');
                            $('#create_new_pet_btn').attr('disabled', false);
                            $('#create_new_pet_btn').html(lg_save_changes);
                        }
                    });

                    return false;  // Prevent the form from submitting traditionally
                }

            });
            
            let already_saved_breed_type=(typeof pet!='undefined' && pet!=null)?pet.breed_type:'';
            updateBreedTypes(already_saved_breed_type);
            
        }
    
    })(jQuery);

    function updateBreedTypes(breed_type='') {
        const petType = document.getElementById("petType").value;
        const breedTypeSelect = document.getElementById("breedType");

        // Clear existing options
        breedTypeSelect.innerHTML = '<option value="">Select Breed Type</option>';

        // Populate breed type options based on selected pet type
        if (breedOptions[petType]) {
            breedOptions[petType].forEach(breed => {
                const option = document.createElement("option");
                option.value = breed.value;
                option.textContent = breed.text;
                breedTypeSelect.appendChild(option);
            });
        }
    
        if(breed_type!=''){
           breedTypeSelect.value=breed_type;
        }
    }
</script>
<?php $this->endSection(); ?>