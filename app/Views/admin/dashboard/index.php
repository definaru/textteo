<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>


<!-- Page Wrapper -->
<div class="page-wrapper">

  <div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <h3 class="page-title">Welcome Admin!</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
          </ul>
        </div>
      </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-body">
            <div class="dash-widget-header">
              <span class="dash-widget-icon text-primary border-primary">
                <i class="fe fe-users"></i>
              </span>
              <div class="dash-count">
                <h6 class="text-muted">Veterinarians</h6>
              </div>
            </div>
            <div class="dash-widget-info">
              <h3><?php
                  /** @var int $doctors_count  */
                  echo $doctors_count; ?></h3>
              <div class="progress progress-sm">
                <div style="width:<?php echo $doctors_count; ?>%;" class="progress-bar bg-primary"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-body">
            <div class="dash-widget-header">
              <span class="dash-widget-icon text-success">
                <i class="fe fe-credit-card"></i>
              </span>
              <div class="dash-count">
                <h6 class="text-muted">Patients</h6>
              </div>
            </div>
            <div class="dash-widget-info">

              <h3><?php
                  /** @var int $patients_count  */
                  echo $patients_count; ?></h3>
              <div class="progress progress-sm">
                <div style="width:<?php echo $patients_count; ?>%;" class="progress-bar bg-success"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-body">
            <div class="dash-widget-header">
              <span class="dash-widget-icon text-danger border-danger">
                <i class="fe fe-money"></i>
              </span>
              <div class="dash-count">
                <h6 class="text-muted">Appointment</h6>
              </div>
            </div>
            <div class="dash-widget-info">
              <h3><?php
                  /** @var int $appointments_count  */
                  echo $appointments_count; ?></h3>

              <div class="progress progress-sm">
                <div style="width:<?php echo $appointments_count; ?>%;" class="progress-bar bg-danger"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card">
          <div class="card-body">
            <div class="dash-widget-header">
              <span class="dash-widget-icon text-warning border-warning">
                <i class="fe fe-folder"></i>
              </span>
              <div class="dash-count">
                <h6 class="text-muted">Revenue</h6>

              </div>
            </div>
            <div class="dash-widget-info">
              <h3><?php
                  /** @var float $revenue  */

                  use App\Libraries\LibSodiumLibrary;

                  echo default_currency_symbol() . number_format($revenue, 2); ?></h3>

              <div class="progress progress-sm">
                <div style="width:<?php echo str_replace(',', '.', number_format($revenue)); ?>%;" class="progress-bar bg-warning"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-md-12 col-lg-6">

        <!-- Sales Chart -->
        <div class="card card-chart">
          <div class="card-header">
            <h4 class="card-title">Revenue</h4>
          </div>
          <div class="card-body">
            <div id="morrisArea"></div>
          </div>
        </div>
        <!-- /Sales Chart -->

      </div>
      <div class="col-md-12 col-lg-6">

        <!-- Invoice Chart -->
        <div class="card card-chart">
          <div class="card-header">
            <h4 class="card-title">Status</h4>
          </div>
          <div class="card-body">
            <div id="morrisLine"></div>
          </div>
        </div>
        <!-- /Invoice Chart -->

      </div>
    </div>

    <div class="row">
      <div class="col-md-6 d-flex">

        <!-- Recent Orders -->
        <div class="card card-table flex-fill">
          <div class="card-header">
            <h4 class="card-title">Veterinarians List</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-center mb-0 w-100">
                <thead>
                  <tr>
                    <th>Veterinarian Name</th>
                    <th>Specialization</th>
                    <th>Earned</th>
                    <th>Reviews</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($doctors)) {
                    foreach ($doctors as $drows) {

                      $doctor_profileimage = (!empty($drows['profileimage']) && file_exists((FCPATH . $drows['profileimage']))) ? base_url() . $drows['profileimage'] : base_url() . 'assets/img/user.png';


                  ?>
                      <tr>
                        <td>
                          <h2 class="table-avatar">
                            <a target="_blank" href="<?php echo base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($drows['username'])); ?>" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="<?php echo $doctor_profileimage; ?>" alt="User Image"></a>
                            <a target="_blank" href="<?php echo base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($drows['username'])); ?>">Dr. <?php echo ucfirst(libsodiumDecrypt($drows['first_name']) . ' ' . libsodiumDecrypt($drows['last_name'])); ?></a>
                          </h2>
                        </td>
                        <td><?php echo ucfirst(libsodiumDecrypt($drows['specialization'])); ?></td>
                        <td><?php echo (get_earned($drows['id'])); ?></td>
                        <td>
                          <?php
                          $rating_value = $drows['rating_value'];
                          for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating_value) {
                              echo '<i class="fe fe-star text-warning"></i>';
                            } else {
                              echo '<i class="fe fe-star-o text-secondary"></i>';
                            }
                          }
                          ?>

                        </td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /Recent Orders -->

      </div>
      <div class="col-md-6 d-flex">

        <!-- Feed Activity -->
        <div class="card  card-table flex-fill">
          <div class="card-header">
            <h4 class="card-title">Patients List</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-center mb-0 w-100">
                <thead>
                  <tr>
                    <th>Patient Name</th>
                    <th>Mobile No</th>
                    <th>Last Visit</th>
                    <th>Paid</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($patients)) {
                    foreach ($patients as $prows) {

                      $patient_profileimage = (!empty($prows['profileimage']) && file_exists((FCPATH . $prows['profileimage']))) ? base_url() . $prows['profileimage'] : base_url() . 'assets/img/user.png';

                  ?>
                      <tr>
                        <td>
                          <h2 class="table-avatar">
                            <a target="_blank" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="<?php echo $patient_profileimage; ?>" alt="User Image"></a>
                            <a target="_blank"><?php echo ucfirst(libsodiumDecrypt($prows['first_name']) . ' ' . libsodiumDecrypt($prows['last_name'])); ?> </a>
                          </h2>
                        </td>
                        <td><?php echo libsodiumDecrypt($prows['mobileno']); ?></td>
                        <td><?php if (isset($prows['last_vist'])) {
                              echo date('d M Y', strtotime($prows['last_vist']));
                            } ?></td>
                        <td><?php
                            $org_amount = 0;
                            if ($prows['last_paid']) {
                              $org_amount = get_doccure_currency($prows['last_paid'], $prows['currency_code'], default_currency_code());
                            }
                            echo default_currency_symbol() . " " . number_format($org_amount, 2, '.', ','); ?></td>

                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /Feed Activity -->

      </div>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Appoinment List</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="appoinment_table" class="table table-hover table-center w-100 mb-0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Veterinarian/veterinary clinic Name</th>
                    <th>Patient Name</th>
                    <th>Appointment Date</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Amount</th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (!empty($appointments)) {
                    $i = 1;
                    foreach ($appointments as $rows) {
                      if ($rows['from_date_time'] > 0) {
                        $doctor_profileimage = (!empty($rows['doctor_profileimage']) && file_exists((FCPATH . $rows['doctor_profileimage']))) ? base_url() . $rows['doctor_profileimage'] : base_url() . 'assets/img/user.png';
                        $patient_profileimage = (!empty($rows['patient_profileimage']) && file_exists((FCPATH . $rows['patient_profileimage']))) ? base_url() . $rows['patient_profileimage'] : base_url() . 'assets/img/user.png';

                        $from_timezone = $rows['time_zone'];
                        $to_timezone = date_default_timezone_get();
                        $from_date_time = $rows['from_date_time'];
                        $from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
                  ?>
                        <tr>
                          <td><?php echo $i++; ?></td>

                          <?php if ($rows['hospital_id'] != "") {
                            $clinicName = libsodiumDecrypt($rows['clinic_first_name']) . " " . libsodiumDecrypt($rows['clinic_last_name']); ?>
                            <td>
                              <h2 class="table-avatar">
                                <a target="_blank" href="<?php echo base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($rows['clinic_username'])); ?>">
                                  <?= strlen($clinicName) > 30 ? substr($clinicName, 0, 30) . "..." : $clinicName; ?>
                                </a>
                              </h2>
                            </td>
                          <?php } else {

                            if ($rows['role'] == 1) {
                              $value = $this->language['lg_dr'] ?? "";
                              $img = '<a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($rows['doctor_username'])) . '" class="avatar avatar-sm mr-2">
                                      <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="">
                                    </a>';
                              $specialization = ucfirst(libsodiumDecrypt($rows['doctor_specialization']));
                            } else {
                              $value = "";
                              $img = "";
                              $specialization = "";
                            }
                            if ($rows['role'] == 6) {
                              $img = '<a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($rows['doctor_username'])) . '" class="avatar avatar-sm mr-2">
                                      <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="">
                                    </a>';
                            }
                            // $img = '';
                            // $value = '';
                            // $specialization = '';
                            $doctorName = ucfirst(libsodiumDecrypt($rows['doc_first_name']) . ' ' . libsodiumDecrypt($rows['doc_last_name']));
                            echo '<td><h2 class="table-avatar">
                            ' . $img . '
                            <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($rows['doctor_username'])) . '">' . $value . ' <span class="longtext">' . $doctorName . '</span> <span>' . libsodiumDecrypt($specialization) . '</span></a>
                          </h2></td>
                            ';
                          } ?>



                          <td>
                            <h2 class="table-avatar">
                              <a target="_blank" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="<?php echo $patient_profileimage; ?>" alt="User Image"></a>
                              <a target="_blank"><?php echo ucfirst(libsodiumDecrypt($rows['patient_first_name'])) . ' ' . libsodiumDecrypt($rows['patient_last_name']); ?> </a>
                            </h2>
                          </td>
                          <td><?php echo date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info">' . date('h:i A', strtotime($from_date_time)) . '</span>'; ?></td>
                          <td><?php echo date('d M Y', strtotime($rows['created_date'])); ?></td>
                          <td><?php

                              $val = '';

                              // if ($rows['appointment_status'] == '1') {
                              //   $val = 'checked';
                              // }
                              if ($rows['appointment_status'] == 0) {
                                $val = 'New';
                              } elseif ($rows['appointment_status'] == 1) {
                                $val = 'Completed';
                              } elseif ($rows['appointment_status'] == 2) {
                                $val = 'Expired';
                              }

                              ?><?php echo $val; ?>
                          </td>
                          <td><?php echo ucfirst($rows['type']); ?></td>

                          <td><?php
                              $org_amount = 0;
                              if ($rows['total_amount']) {
                                $org_amount = get_doccure_currency($rows['total_amount'], $rows['currency_code'], default_currency_code());
                              }
                              echo default_currency_symbol() . " " . number_format($org_amount, 2, '.', ','); ?></td>
                        </tr>
                  <?php }
                    }
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- /Page Wrapper -->

</div>
<!-- /Main Wrapper -->


<?php $this->endSection(); ?>