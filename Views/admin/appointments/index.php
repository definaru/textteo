<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- Page Wrapper -->
<div class="page-wrapper">
   <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
         <div class="row">
            <div class="col-sm-12">
               <h3 class="page-title">Appointments</h3>
               <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
                  <li class="breadcrumb-item active">Appointments</li>
               </ul>
            </div>
         </div>
      </div>
      <!-- /Page Header -->
      <div class="row">
         <div class="col-md-12">
            <!-- Recent Orders -->
            <div class="card">
               <div class="card-body">
                  <!-- Tab Menu -->
                  <nav class="user-tabs mb-4">
                     <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                        <li class="nav-item">
                           <a class="nav-link active" onclick="upappoinments_table()" href="#up_appointments" data-toggle="tab">Upcoming</a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" onclick="missedappoinments_table()" href="#missed" data-toggle="tab"><span>Missed</span></a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" onclick="appoinments_table()" href="#completed" data-toggle="tab">Completed</a>
                        </li>
                     </ul>
                  </nav>
                  <!-- /Tab Menu -->
                  <!-- Tab Content -->
                  <div class="tab-content pt-0">
                     <!-- Upcoming Tab -->
                     <div id="up_appointments" class="tab-pane fade show active">
                        <div class="table-responsive">
                           <table id="upappoinment_table" class="table table-hover table-center w-100 mb-0">
                              <thead>
                                 <tr>
                                    <th>S.No</th>
                                    <th>Doctor/Clinic Name</th>
                                    <th>Patient Name</th>
                                    <th>Appointment Date</th>
                                    <th>Booking Date</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                 </tr>
                              </thead>
                              <tbody>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <!-- /upcoming Tab -->
                     <!-- missed Tab -->
                     <div class="tab-pane fade" id="missed">
                        <div class="table-responsive">
                           <table id="missedappoinment_table" class="table table-hover table-center w-100 mb-0">
                              <thead>
                                 <tr>
                                    <th>S.No</th>
                                    <th>Doctor/Clinic Name</th>
                                    <th>Patient Name</th>
                                    <th>Appointment Date</th>
                                    <th>Booking Date</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                 </tr>
                              </thead>
                              <tbody>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <!-- /Missed Tab -->
                     <!-- Completed Records Tab -->
                     <div class="tab-pane fade" id="completed">
                        <div class="table-responsive">
                           <table id="appoinment_table" class="table table-hover table-center w-100 mb-0">
                              <thead>
                                 <tr>
                                    <th>S.No</th>
                                    <th>Doctor/Clinic Name</th>
                                    <th>Patient Name</th>
                                    <th>Appointment Date</th>
                                    <th>Booking Date</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                 </tr>
                              </thead>
                              <tbody>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <!-- /Completed Records Tab -->
                  </div>
                  <!-- Tab Content -->
               </div>
            </div>
            <!-- /Recent Orders -->
         </div>
      </div>
   </div>
</div>
<!-- /Page Wrapper -->
</div>
<!-- /Main Wrapper -->
<?php $this->endSection(); ?>