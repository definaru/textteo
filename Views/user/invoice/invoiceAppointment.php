<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
 <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/style.css">
<!-- Breadcrumb -->
			<div class="breadcrumb-bar">
				<div class="container-fluid">
					<div class="row align-items-center">
						<div class="col-md-12 col-12">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php echo $language['lg_home']??""; ?></a></li>
									<li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_invoice_view']??""; ?></li>
								</ol>
							</nav>
							<h2 class="breadcrumb-title"><?php echo $language['lg_invoice_view']??""; ?></h2>
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->
			
			
			<!-- Page Content -->
			<div class="content">
				<div class="container-fluid">

					<div class="row">
						<div class="col-lg-8 offset-lg-2">
							<div class="invoice-content">
								<div class="invoice-item">
									<div class="row">
										<div class="col-md-6">
											<div class="invoice-logo">
												<img src="<?php echo (!empty(base_url() . settings("logo_front")) && file_exists(settings("logo_front"))) ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png"; ?>" alt="logo">
											</div>
										</div>
										<div class="col-md-6">
											<p class="invoice-details">
												<strong><?php echo $language['lg_invoice_no']??""; ?>:</strong> <?php echo $invoices['invoice_no'];?> <br>
												<strong><?php echo $language['lg_issued']??""; ?>:</strong> <?php echo date('d M Y',strtotime($invoices['payment_date']));?>
											</p>
										</div>
									</div>
								</div>
								
								<!-- Invoice Item -->
								<div class="invoice-item">
									<div class="row">
										<div class="col-md-6">
											<div class="invoice-info">
												<strong class="customer-text"><?php echo $language['lg_invoice_from']??""; ?></strong>
												<p class="invoice-details invoice-details-two">
													<?php if($role==1){ echo $language['lg_dr']??""; } ?> <?php echo "TextTeo AI Limited";?> <br>
													<?php //echo libsodiumDecrypt($invoices['doctoraddress1']).', '.libsodiumDecrypt($invoices['doctoraddress2']);?><br>
													<?php //echo $invoices['doctorcityname'].', '.$invoices['doctorcountryname'];?>
													<?php
													/*if (
														isset($invoices['doctorpostalcode']) && 
														!empty($invoices['doctorpostalcode'])
													) {
														echo ' - '.libsodiumDecrypt($invoices['doctorpostalcode']);
													}
													?> <br>
													<?php echo libsodiumDecrypt($invoices['doctormobile']);*/?>													
												</p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="invoice-info invoice-info2">
												<strong class="customer-text"><?php echo $language['lg_invoice_to']??""; ?></strong>
												<p class="invoice-details">
													<?php echo ucfirst(libsodiumDecrypt($invoices['pat_first_name']))." ".ucfirst(libsodiumDecrypt($invoices['pat_last_name']));?> <br>
													<?php //echo $invoices['patientaddress1'].', '.$invoices['patientaddress2'];?>,<br>
													<?php //echo $invoices['patientcityname'].', '.$invoices['patientcountryname'];?>
													<?php
													/*if (
														isset($invoices['patientpostalcode']) && 
														!empty($invoices['patientpostalcode'])
													) {
														echo ' - '.$invoices['patientpostalcode'];
													}
													?><br>
													<?php //echo libsodiumDecrypt($invoices['patientmobile']);*/?>

												</p>
											</div>
										</div>
									</div>
								</div>
								<!-- /Invoice Item -->
								
								<!-- Invoice Item -->
								<div class="invoice-item">
									<div class="row">
										<div class="col-md-12">
											<div class="invoice-info">
												<strong class="customer-text"><?php echo $language['lg_payment_method']??""; ?></strong>
												<p class="invoice-details invoice-details-two">
													<?php echo ucfirst($invoices['payment_type']);?><br>
													
												</p>
											</div>
										</div>
									</div>
								</div>
								<!-- /Invoice Item -->

								<?php


            $user_currency=get_user_currency();
            $user_currency_code=$user_currency['user_currency_code'];
            $user_currency_rate=$user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code))?$user_currency_code:$invoices['currency_code'];
            //$rate_symbol = currency_code_sign($currency_option);
            //Muddasar Ali, currency formate
			$fmt = new NumberFormatter( "en-US@currency=$currency_option", NumberFormatter::CURRENCY );
			$rate_symbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
			//currency formate end
            $rate=get_doccure_currency($invoices['per_hour_charge'],$invoices['currency_code'],$user_currency_code);
            $rate=number_format($rate,2,'.',',');
                     
            $amount=$rate_symbol.''.$rate;
              
            $transcation_charge=get_doccure_currency($invoices['transcation_charge'],$invoices['currency_code'],$user_currency_code);
            $transaction_charge_percentage = $invoices['transaction_charge_percentage'];

            $tax_amount=get_doccure_currency($invoices['tax_amount'],$invoices['currency_code'],$user_currency_code);

             $total_amount=get_doccure_currency($invoices['total_amount'],$invoices['currency_code'],$user_currency_code);
			 
			 // $transcation_charge_amt = !empty(settings("transaction_charge"))?settings("transaction_charge"):"0";



								?>
								
								<!-- Invoice Item -->
								<div class="invoice-item invoice-table-wrap">
									<div class="row">
										<div class="col-md-12">
											<div class="table-responsive">
												<table class="invoice-table table table-bordered">
													<thead>
														<tr><th><?php echo $language['lg_sno']??""; ?></th>
															<th><?php echo $language['lg_description']??""; ?></th>
															<th class="text-right"><?php echo $language['lg_total1']??""; ?></th>
														</tr>
													</thead>
													<tbody>
													<?php
													$mode='';
													if($role=='1' || $role=='6')
													{
                                                    	$payment_id=$invoices['id'];                                                       
                                                        
                                                        $appointments_res=getTblResultOfData('appointments',['payment_id'=>$payment_id],'*',false);

                                                       	$sno=1;
                                                       	foreach ($appointments_res as $key => $value)
                                                       	{
                                                       		if($value['type']=="online" || $value['type']=="Online"){
                                                       			$mode=$language['lg_video_call_book']??"";
                                                       		}else{
                                                       			if ($role=='1') { // doctor
                                                       				$mode=$language['lg_doctor_booking']??"";
                                                       			} elseif($role=='6') { // clinic
                                                       				$mode=$language['lg_clinic_booking']??"";
                                                       			}
                                                       		}
													?>
                                                       
														<tr>
															<td><?php echo $sno; ?></td>
															<td><?php echo $mode; ?></td>
															<td class="text-right"><?php echo $amount; ?></td>
														</tr>
													<?php  $sno++; } ?>
													<?php
													}
													else
													{
														$sno=1;														
                                                        $test_ids=getTblRowOfData('lab_payments',['order_id'=>$invoices['order_id']],'booking_ids');
														$test_ids = $test_ids['booking_ids'];
														$test_name="";
											            $array_ids=explode(',', $test_ids);
											            foreach ($array_ids as $value) {
															$result = getTblRowOfData('lab_tests',['id'=>$value],"lab_test_name,amount,,currency_code",false);
											                $mode=libsodiumDecrypt($result['lab_test_name']);
											            	$price=get_doccure_currency($result['amount'],$result['currency_code'],$user_currency_code);
											        ?>
											            <tr>
															<td><?php echo $sno; ?></td>
															<td><?php echo $mode; ?></td>
															<td class="text-right"><?php echo "aaa".$rate_symbol.$price; ?></td>
														</tr>
											        <?php
													$sno++;	}	}
													?>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-md-6 col-xl-6 ml-auto">
											<div class="table-responsive">
												<table class="invoice-table-two table">
													<tbody>
													<!--<tr>
														<th><?php //echo $language['lg_transaction_cha']??""; ?> (<?php echo $transaction_charge_percentage ?>%):</th>
														<td><span><?php //echo $rate_symbol.number_format($transcation_charge,2, '.', ''); ?></span></td>
													</tr>-->
													<tr>
														<th><?php echo $language['lg_tax']??""; ?> (<?php echo $invoices['tax'] ?>%):</th>
														<td><span><?php echo $rate_symbol.number_format($tax_amount,2, '.', ''); ?></span></td>
													</tr>
													<tr>
														<th><?php echo $language['lg_total_amount']??""; ?>:</th>
														<td><span><?php echo $rate_symbol.number_format($total_amount,2, '.', ''); ?></span></td>
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

<?php $this->endSection(); ?>