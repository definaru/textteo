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
                    <div class="row">
                        <div class="col-lg-5 d-flex">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h3 class="card-title"><?php echo $language['lg_account5']??""; ?></h3>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-right">
                                                <a title="<?php echo $language['lg_edit_profile']??"" ?>" class="btn btn-primary btn-sm" onclick="add_account_details()">
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
                                    <span class="text-danger"><?= $language['lg_commission_char'] ?></span>
                                        <div class="col-lg-6">
                                            <div class="account-card bg-success-light">
                                                <span><?php
                                                        /** @var string $currency_symbol */
                                                        /** @var float $earned */
                                                        /** @var int $requested  */

                                                        echo $currency_symbol; ?><?php echo number_format($earned, 2); ?></span> <?php echo $language['lg_earned']??""; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="account-card bg-warning-light">
                                                <span><?php echo $currency_symbol; ?><?php echo number_format($requested, 2); ?></span> <?php echo $language['lg_requested']??""; ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="account-card bg-purple-light">
                                                <span><?php
                                                        /** @var int $balance  */


                                                        echo $currency_symbol; ?><?php echo number_format($balance - ($earned + $requested), 2); ?></span> <?php echo $language['lg_balance']??""; ?>
                                            </div>

                                        </div>
                                        
                                        <div class="col-md-12 text-center">
                                            <?php $net_balance = number_format($balance - ($earned + $requested), 2);
                                            $disabled = '';
                                            if ($net_balance <= 0) {
                                                $disabled = 'disabled';
                                            } ?>
                                            <a href="javascript:void(0);" onclick="payment_request(1)" title="<?= $language['lg_request_amount_'] ?>" class="btn btn-primary request_btn <?php echo $disabled;  ?>"><?php echo $language['lg_payment_request3']??""; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="user-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom nav-justified flex-wrap">
                            <li class="nav-item">
                                <a class="nav-link active" onclick="account_table()" href="#account_tab" data-toggle="tab"><?php echo $language['lg_accounts']??""; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" onclick="patient_refund_request()" href="#patient_request_tab" data-toggle="tab"><span><?php echo $language['lg_patients_refund']??""; ?></span></a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div id="account_tab" class="tab-pane fade show active">
                            <div class="card card-table">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="accounts_table" class="table table-hover table-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th><?php echo 'S.No'; ?></th>
                                                    <th><?php echo $language['lg_date1']??""; ?></th>
                                                    <th><?php echo $language['lg_patient_name']??""; ?></th>
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
                        <div id="patient_request_tab" class="tab-pane fade">
                            <div class="card card-table">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="patient_refund_request" style="width: 100%" class="table table-hover table-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th><?php echo 'S.No'; ?></th>
                                                    <th><?php echo $language['lg_date1']??""; ?></th>
                                                    <th><?php echo $language['lg_patient_name']??""; ?></th>
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
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
<!-- /Page Content -->
<?php $this->endSection(); ?>