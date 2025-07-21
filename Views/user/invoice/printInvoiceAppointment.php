<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title><?php echo !empty(settings("meta_title")) ? settings("meta_title") : "Doccure"; ?></title>
    <meta content="<?php echo !empty(settings("meta_keywords")) ? settings("meta_keywords") : ""; ?>" name="keywords">
    <meta content="<?php echo !empty(settings("meta_description")) ? settings("meta_description") : ""; ?>" name="description">
    <!-- Favicons -->
    <link href="<?php echo !empty(base_url() . settings("favicon")) ? base_url() . settings("favicon") : base_url() . "assets/img/favicon.png"; ?>" rel="icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/all.min.css">


    <!-- Main CSS -->

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/toastr.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php //echo base_url();
                    ?>assets/js/html5shiv.min.js"></script>
      <script src="<?php //echo base_url();
                    ?>assets/js/respond.min.js"></script>
    <![endif]-->

</head>
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="invoice-content">
                    <div class="invoice-item">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="invoice-logo">
                                    <img src="<?php echo (!empty(base_url() . settings("logo_front")) && file_exists(settings("logo_front")))  ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png"; ?>" alt="logo">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="invoice-details">
                                    <strong><?php echo $language['lg_invoice_no']; ?>:</strong> <?php echo $invoices['invoice_no']; ?> <br>
                                    <strong><?php echo $language['lg_issued']; ?>:</strong> <?php echo date('d M Y', strtotime($invoices['payment_date'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Item -->
                    <!-- <div class="invoice-item">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="invoice-info">
                                    <strong class="customer-text"><?php echo $language['lg_invoice_from']; ?></strong>
                                    <p class="invoice-details invoice-details-two">
                                        <?php if ($role == 1) {
                                            echo $language['lg_dr'];
                                        } ?> <?php echo ucfirst(libsodiumDecrypt($invoices['doc_first_name'])); ?> <br>
                                        <?php echo libsodiumDecrypt($invoices['doctoraddress1']) . ', ' . libsodiumDecrypt($invoices['doctoraddress2']); ?>,<br>
                                        <?php echo $invoices['doctorcityname'] . ', ' . $invoices['doctorcountryname']; ?>
                                        <?php
                                        if (
                                            isset($invoices['doctorpostalcode']) &&
                                            !empty($invoices['doctorpostalcode'])
                                        ) {
                                            echo ' - ' . $invoices['doctorpostalcode'];
                                        }
                                        ?> <br>
                                        <?php echo libsodiumDecrypt($invoices['doctormobile']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-info invoice-info2">
                                    <strong class="customer-text"><?php echo $language['lg_invoice_to']; ?></strong>
                                    <p class="invoice-details">
                                        <?php echo ucfirst(libsodiumDecrypt($invoices['pat_first_name'])." ".libsodiumDecrypt($invoices['pat_last_name'])); ?> <br>
                                        <?php echo libsodiumDecrypt($invoices['patientaddress1']) . ', ' . libsodiumDecrypt($invoices['patientaddress2']); ?>,<br>
                                        <?php echo $invoices['patientcityname'] . ', ' . $invoices['patientcountryname']; ?>
                                        <?php
                                        if (
                                            isset($invoices['patientpostalcode']) &&
                                            !empty($invoices['patientpostalcode'])
                                        ) {
                                            echo ' - ' . libsodiumDecrypt($invoices['patientpostalcode']);
                                        }
                                        ?><br>
                                        <?php echo libsodiumDecrypt($invoices['patientmobile']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <table style="width: 100%;">
                                            <tr>
                                            <td align="left">
                                                <h2 style="font-size:18px;font-weight: 700;color:#272b41;display:block;margin:0 0 8px; "><?php echo $language['lg_invoice_from'] ?? ""; ?></h2>
                                                <p style="font-size:0.9375rem;font-weight: 500;color:#757575;display:block;margin:0 0 8px; "><?php if ($role == 1) {
                                            echo $language['lg_dr'];
                                        } ?> <?php echo ucfirst(libsodiumDecrypt($invoices['doc_first_name'])); ?></p>
                                                <p style="font-size:0.9375rem;font-weight: 500;color:#757575;display:block;margin:0 0 8px; "><?php echo libsodiumDecrypt($invoices['doctoraddress1']) . ', ' . libsodiumDecrypt($invoices['doctoraddress2']); ?>,</p>
                                                <p style="font-size:0.9375rem;font-weight: 500;color:#757575;display:block;margin:0 0 8px; "><?php echo $invoices['doctorcityname'] . ', ' . $invoices['doctorcountryname']; ?>
                                        <?php
                                        if (
                                            isset($invoices['doctorpostalcode']) &&
                                            !empty($invoices['doctorpostalcode'])
                                        ) {
                                            echo ' - ' . libsodiumDecrypt($invoices['doctorpostalcode']);
                                        }
                                        ?></p>
                                                <p style="font-size:0.9375rem;font-weight: 500;color:#757575;display:block;margin:0 0 8px; "><?php echo libsodiumDecrypt($invoices['doctormobile']); ?></p>
                                                
                                            </td>
                                            <td align="right">
                                                <h2 style="font-size:18px;font-weight: 700;color:#272b41;display:block;margin:0 0 8px; "><?php echo $language['lg_invoice_to'] ?? ""; ?></h2>
                                                <p style="font-size:0.9375rem;font-weight: 500;color:#757575;display:block;margin:0 0 8px; "><?php echo ucfirst(libsodiumDecrypt($invoices['pat_first_name'])." ".libsodiumDecrypt($invoices['pat_last_name'])); ?></p>
                                                <p style="font-size:0.9375rem;font-weight: 500;color:#757575;display:block;margin:0 0 8px; "><?php echo libsodiumDecrypt($invoices['patientaddress1']) . ', ' . libsodiumDecrypt($invoices['patientaddress2']); ?>,</p>
                                                <p style="font-size:0.9375rem;font-weight: 500;color:#757575;display:block;margin:0 0 8px; "><?php echo $invoices['patientcityname'] . ', ' . $invoices['patientcountryname']; ?>
                                        <?php
                                        if (
                                            isset($invoices['patientpostalcode']) &&
                                            !empty($invoices['patientpostalcode'])
                                        ) {
                                            echo ' - ' . libsodiumDecrypt($invoices['patientpostalcode']);
                                        }
                                        ?></p>
                                                <p style="font-size:0.9375rem;font-weight: 500;color:#757575;display:block;margin:0 0 8px; "><?php echo libsodiumDecrypt($invoices['patientmobile']); ?></p>
                                                
                                            </td>                                              
                                            </tr>
                                        </table><br>

                    <!-- /Invoice Item -->

                    <!-- Invoice Item -->
                    <div class="invoice-item">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="invoice-info">
                                    <strong class="customer-text"><?php echo $language['lg_payment_method']; ?></strong>
                                    <p class="invoice-details invoice-details-two">
                                        <?php echo ucfirst($invoices['payment_type']); ?><br>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice Item -->
                    <?php


                    $user_currency = get_user_currency();
                    $user_currency_code = $user_currency['user_currency_code'];
                    $user_currency_rate = $user_currency['user_currency_rate'];

                    $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $invoices['currency_code'];
                    $rate_symbol = currency_code_sign($currency_option);


                    $rate = get_doccure_currency($invoices['per_hour_charge'], $invoices['currency_code'], $user_currency_code);
                    $rate = number_format($rate, 2, '.', ',');

                    $amount = $rate_symbol . '' . $rate;

                    $transcation_charge = get_doccure_currency($invoices['transcation_charge'], $invoices['currency_code'], $user_currency_code);
                    $transaction_charge_percentage = $invoices['transaction_charge_percentage'];

                    $tax_amount = get_doccure_currency($invoices['tax_amount'], $invoices['currency_code'], $user_currency_code);

                    $total_amount = get_doccure_currency($invoices['total_amount'], $invoices['currency_code'], $user_currency_code);

                    // $transcation_charge_amt = !empty(settings("transaction_charge"))?settings("transaction_charge"):"0";



                    ?>

                    <!-- Invoice Item -->
                    <div class="invoice-item invoice-table-wrap">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="invoice-table table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo $language['lg_sno']; ?></th>
                                                <th><?php echo $language['lg_description']; ?></th>
                                                <th class="text-right"><?php echo $language['lg_total1']; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $mode = '';
                                            if ($role == '1' || $role == '6') {
                                                $payment_id = $invoices['id'];

                                                $appointments_res=getTblResultOfData('appointments',['payment_id'=>$payment_id],'*',false);
                                                $sno = 1;
                                                foreach ($appointments_res as $key => $value) {
                                                    if ($value['type'] == "online" || $value['type'] == "Online") {
                                                        $mode = $language['lg_video_call_book'];
                                                    } else {
                                                        $mode = $language['lg_clinic_booking'];
                                                    }
                                            ?>

                                                    <tr>
                                                        <td><?php echo $sno; ?></td>
                                                        <td><?php echo $mode; ?></td>
                                                        <td class="text-right"><?php echo $amount; ?></td>
                                                    </tr>
                                                <?php $sno++;
                                                } ?>
                                                <?php
                                            } else {
                                                $sno = 1;
                                                $test_ids=getTblRowOfData('lab_payments',['order_id'=>$invoices['order_id']],'booking_ids')['booking_ids'];
                                                $test_name = "";
                                                $array_ids = explode(',', $test_ids);
                                                foreach ($array_ids as $value) {                                                    
                                                    $result = getTblRowOfData('lab_tests',['id'=>$value],"lab_test_name,amount,,currency_code",false);
                                                    $mode=libsodiumDecrypt($result['lab_test_name']);
                                                    $price = get_doccure_currency($result['amount'], $result['currency_code'], $user_currency_code);
                                                ?>
                                                    <tr>
                                                        <td><?php echo $sno; ?></td>
                                                        <td><?php echo $mode; ?></td>
                                                        <td class="text-right"><?php echo $rate_symbol . $price; ?></td>
                                                    </tr>
                                            <?php
                                                    $sno++;
                                                }
                                            }
                                            ?>
                                        </tbody>

                                        <tbody>
                                            <!--<tr>
                                                <th colspan="2" style="text-align: right;"><?php //echo $language['lg_transaction_cha']; ?> (<?php echo $transaction_charge_percentage ?>%):</th>
                                                <td style="text-align: right;"><span><?php //echo $rate_symbol . number_format($transcation_charge, 2, '.', ''); ?></span></td>
                                            </tr>-->
                                            <tr>
                                                <th colspan="2" style="text-align: right;"><?php echo $language['lg_tax']; ?> (<?php echo $invoices['tax'] ?>%):</th>
                                                <td style="text-align: right;"><span><?php echo $rate_symbol . number_format($tax_amount, 2, '.', ''); ?></span></td>
                                            </tr>
                                            <tr>
                                                <th colspan="2" style="text-align: right;"><?php echo $language['lg_total_amount']; ?>:</th>
                                                <td style="text-align: right;"><span><?php echo $rate_symbol . number_format($total_amount, 2, '.', ''); ?></span></td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- /Invoice Item -->

                </div>
            </div>
        </div>

    </div>

</div>
<!-- /Page Content-->

<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script type="text/javascript">
    window.print();
</script>