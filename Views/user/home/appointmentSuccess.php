<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">
                        <?php echo $language['lg_home']??""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_booking2']??""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php echo $language['lg_booking2']??""; ?></h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content success-page-cont">
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-lg-6">

                <!-- Success Card -->
                <div class="card success-card">
                    <div class="card-body">
                        <div class="success-cont">
                            <i class="fas fa-check"></i>
                            <h3><?php echo $language['lg_appointment_boo']??""; ?></h3>
                            <p>
                                <?php echo $language['lg_appointment_boo1']??""; ?> 
                                <strong id="doctor_name">
                                    <?php if ($appointment_details['role'] != 6) { echo $language['lg_dr']??""; } ?> 
                                    <?php echo libsodiumDecrypt($appointment_details['doctor_first_name']); ?>
                                </strong><br> 
                                <?php echo $language['lg_on1']??""; ?> 
                                <strong id="appt_time">
                                    <?php
                                    $current_timezone = $appointment_details['time_zone'];
                                    $old_timezone = session('time_zone');
                                    echo date('d M Y h:i a', strtotime(converToTz($appointment_details['from_date_time'], $old_timezone, $current_timezone)));
                                    // echo date("d M Y  h:i a", strtotime($appointment_details['from_date_time'])); ?>
                                </strong>
                            </p>
                            <div style="display:-webkit-inline-box">
                            <div style="margin-right:10px"><a href="<?php echo base_url(); ?>invoice-view/<?php echo base64_encode($appointment_details['payment_id']); ?>" class="btn btn-primary view-inv-btn"><?php echo $language['lg_view_invoice']??""; ?></a>
                            </div>
                            <div style="margin-left:10px"><a href="<?php echo base_url(); ?>patient/appointments/" class="btn btn-primary view-inv-btn"><?php echo "Appointment"  ; ?></a>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Success Card -->

            </div>
        </div>

    </div>
</div>
<!-- /Page Content -->

<?php $this->endSection(); ?>