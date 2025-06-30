<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url() ?>"><?php
                                                                                        /** @var array $language */
                                                                                        echo $language['lg_home']??""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_accounts']??""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php echo $language['lg_accounts']??""; ?></h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">

                <!-- Profile Sidebar -->
                <?= view('user/layout/sidebar'); ?>
                <!-- /Profile Sidebar -->

            </div>

            <div class="col-md-7 col-lg-8 col-xl-9">
                <div id="account">
                    <!--<div class="row">
                        <div class="col-lg-5 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h3 class="card-title"><?php echo $language['lg_account5']??""; ?></h3>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-right">
                                                <a title="<?php echo $language['lg_edit_profile'??""] ?>" class="btn btn-primary btn-sm" id="btn-add-edit-title" onclick="add_account_details()">
                                                <i class="fas fa-pencil"></i> 
                                                <?php echo (isset($account_details) && !empty($account_details) && isset($account_details->bank_name) && ($account_details->bank_name!="" && $account_details->bank_name!=null) ) ? $language['lg_edit_details']??"" : $language['lg_add_account_details']??""; ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="profile-view-bottom">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="info-list">
                                                    <div class="title"><?php echo $language['lg_bank_name']??""; ?></div>
                                                    <div class="text" id="bank_name"><?php echo isset($account_details->bank_name) ? $account_details->bank_name : ''; ?></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="info-list">
                                                    <div class="title"><?php echo $language['lg_branch_name']??""; ?></div>
                                                    <div class="text" id="branch_name"><?php echo isset($account_details->branch_name) ? $account_details->branch_name : ''; ?></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="info-list">
                                                    <div class="title"><?php echo $language['lg_account_number']??""; ?></div>
                                                    <div class="text" id="account_no"><?php echo isset($account_details->account_no) ? $account_details->account_no : ''; ?></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="info-list">
                                                    <div class="title"><?php echo $language['lg_account_holder_']??""; ?></div>
                                                    <div class="text" id="account_name"><?php echo isset($account_details->account_name) ? $account_details->account_name : ''; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="account-card bg-success-light">
                                                <span><?php
                                                        /** @var string $currency_symbol */
                                                        /** @var float $earned */
                                                        /** @var integer $requested */
                                                        /** @var integer $balance */


                                                        echo $currency_symbol; ?><?php echo number_format($earned, 2); ?></span> <?php echo $language['lg_received']??""; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="account-card bg-warning-light">
                                                <span><?php echo $currency_symbol . "" . number_format($requested, 2); ?></span> <?php echo $language['lg_requested']??""; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="account-card bg-purple-light">
                                                <span><?php echo $currency_symbol; ?><?php echo number_format($balance - ($earned + $requested), 2); ?></span> <?php echo $language['lg_balance']??""; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ((number_format($balance - ($earned + $requested))) > 0) { ?>
                                            <div class="col-md-12 text-center">
                                                <a href="javascript:void(0);" title="<?= $language['lg_request_amount_'] ?>" onclick="payment_request(2)" class="btn btn-primary request_btn"><?php echo $language['lg_payment_request3']??""; ?></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->

                    <div class="user-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom nav-justified flex-wrap">
                            <li class="nav-item">
                                <a class="nav-link active" onclick="paccount_table()" href="#account_tab" data-toggle="tab"><?php echo $language['lg_doctor_appointm']??""; ?></a>
                            </li>
                            <!--<li class="nav-item">
                                <a class="nav-link" onclick="pharmacyaccount_table()" href="#pharmacy_account_tab" data-toggle="tab"><span><?php //echo $language['lg_pharmacy_appoin']??""; ?></span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" onclick="labaccount_table()" href="#lab_account_tab" data-toggle="tab"><span><?php //echo $language['lg_lab_appointment2']??""; ?></span></a>
                            </li>-->
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="account_tab" class="tab-pane fade show active">
                            <div class="card card-table">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="patient_accounts_table" class="table table-hover table-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $language['lg_sno']??""; ?></th>
                                                    <th><?php echo $language['lg_date1']??""; ?></th>
                                                    <th><?php echo $language['lg_doctor2']??"" . "/" . $language['lg_clinic']??""; ?></th>
                                                    <th><?php echo $language['lg_amount']??""; ?></th>
                                                    <th><?php echo $language['lg_status']??""; ?></th>
                                                    <th><?php echo $language['lg_action']??""; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                      <!--  <div id="pharmacy_account_tab" class="tab-pane fade">
                            <div class="card card-table">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="pharmacy_accounts_table" style="width: 100%" class="table table-hover table-center mb-0">
                                            <thead>
                                                <tr>
                                                   <th><?php //echo $language['lg_sno']??""; ?></th>
                                                    <th><?php //echo $language['lg_date1']??""; ?></th>
                                                    <th><?php //echo $language['lg_pharmacy_name']??""; ?></th>
                                                    <th><?php //echo $language['lg_amount']??""; ?></th>
                                                    <th><?php //echo $language['lg_status']??""; ?></th>
                                                    <th><?php //echo $language['lg_action']??""; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="lab_account_tab" class="tab-pane fade">
                            <div class="card card-table">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="lab_accounts_table" style="width: 100%" class="table table-hover table-center mb-0">
                                            <thead>
                                                <tr>
                                                   <th><?php //echo $language['lg_sno']??""; ?></th>
                                                    <th><?php //echo $language['lg_date1']??""; ?></th>
                                                    <th><?php //echo $language['lg_lab_name']??""; ?></th>
                                                    <th><?php //echo $language['lg_amount']??""; ?></th>
                                                    <th><?php //echo $language['lg_status']??""; ?></th>
                                                    <th><?php //echo $language['lg_action']??""; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>
<!-- /Page Content -->
<?php $this->endSection(); ?>