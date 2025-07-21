<?php $this->extend('user/layout/header'); ?>
<?php $this->section('content'); ?>
<?php

if(is_array($packages)){
    //echo "<pre>"; print_r($packages); echo "</pre>";
    $is_free_slot = false; $total_free = 0;
    foreach($packages as $p){
        if($p['count'] > $p['count_used']){
            $is_free_slot = true;
            $total_free += $p['count'] - $p['count_used'];
        }
    }
}
$coupon = session('coupon')?session('coupon'):false;
$coupon_type = session('coupon_type')?session('coupon_type'):false;
$mandarin = false;
foreach($coupons as $cp){
    if($cp['coupon'] == 'mandarin' && $cp['coupon_reason'] != 'used'){
        $mandarin = true;
    }
}
//echo "<pre>"; print_r($coupons); echo "</pre>";
?>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php
                                                                                        /** @var array $language */
                                                                                        echo $language['lg_home']??""; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $language['lg_checkout']??""; ?></li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title"><?php echo $language['lg_checkout']??""; ?></h2>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->
<!-- Page Content -->
<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-md-5 col-lg-4 theiaStickySidebar">

                <!-- Booking Summary -->
                <div class="card booking-card">
                    <div class="card-header">
                        <h4 class="card-title"><?php echo $language['lg_booking_summary']??""; ?></h4>
                    </div>
                    <div class="card-body">
                        <?php
                        /** @var array $doctors */
                        $profileimage = (!empty($doctors['profileimage'])) ? base_url() . $doctors['profileimage'] : base_url() . 'assets/img/user.png';
                        ?>

                        <!-- Booking Doctor Info -->
                        <div class="booking-doc-info">
                            <a href="<?php //echo base_url() . 'doctor-preview/' . $doctors['username']; ?>" class="booking-doc-img">
                                <img src="<?php echo $profileimage; ?>" alt="User Image">
                            </a>
                            <div class="booking-info">
                                <h4>
                                    <a href="<?php //echo base_url() . 'doctor-preview/' . $doctors['username']; ?>">
                                        <?php if ($doctors['role'] != 6) {
                                            echo $language['lg_dr']??"";
                                        } ?> 
                                        <?php 
                                        echo ucfirst(libsodiumDecrypt($doctors['first_name']) . ' ' . libsodiumDecrypt($doctors['last_name'])); 
                                        ?>
                                    </a>
                                </h4>
                                <!--<div class="rating">
                                    <?php
                                    /*$rating_value = $doctors['rating_value'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating_value) {
                                            echo '<i class="fas fa-star filled"></i>';
                                        } else {
                                            echo '<i class="fas fa-star"></i>';
                                        }
                                    }*/
                                    ?>
                                    <span class="d-inline-block average-rating">(<?php //echo $doctors['rating_count']; ?>)</span>
                                </div>-->
                                <!--<div class="clinic-details">
                                    <p class="doc-location"><i class="fas fa-map-marker-alt"></i> <?php //echo $doctors['cityname'] . ', ' . $doctors['countryname']; ?></p>
                                </div>-->
                            </div>
                        </div><br>
                        <!-- Booking Doctor Info -->

                        <div class="booking-summary">
                            <div class="booking-item-wrap">
                                <ul class="booking-date">
                                    <li><?php echo $language['lg_date1']??""; ?> <span> <?php
                                                                                    /** @var array $appointment_details */
                                                                                    echo date('d M Y', strtotime($appointment_details[0]->appoinment_date)) ?></span>
                                    </li><br>
                                    <li>Time <span><?php echo date('h:i A', strtotime(converToTz($appointment_details[0]->appoinment_start_time, session('time_zone'), $appointment_details[0]->appoinment_timezone))); ?></span></li>
                                </ul><br>
                                <?php
                                $tax = !empty(settings("tax")) ? settings("tax") : "0";;
                                $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
                                $amount = session('amount');
                                $transcation_charge = session('transcation_charge');
                                $tax_amount = session('tax_amount');
                                $total_amount = session('total_amount');
                                $discount = session('discount');
                                $discount_pc = '';
                                if($coupon_type){
                                    if(stripos($coupon_type, 'special') !== false){
                                        $discount_percent = str_replace('special', '', $coupon_type);
                                        $discount = session('total_amount')*($discount_percent/100);
                                        $total_amount = $total_amount - $discount;
                                        $discount_pc = '('.$discount_percent.'%)';
                                    }
                                    if(stripos($coupon_type, 'amount') !== false){
                                        $discount = str_replace('amount', '', $coupon_type);
                                        $total_amount = $total_amount - $discount;
                                        $discount_pc = '('.$discount.')';
                                        if($total_amount < 0)
                                            $total_amount = 0;
                                    }
                                }
                                ?>
                                <ul class="booking-fee">
                                    <li><?php echo $language['lg_call_charge']??""; ?> <span><?php echo $rate_symbol; ?>
                                            <?php echo number_format($amount, 2, '.', ''); ?></span></li>
                                    <li><?php echo $language['lg_transaction_cha']??""; ?> (<?php echo $transcation_charge_amt ?>%)<span><?php echo $rate_symbol; ?><?php echo number_format($transcation_charge, 2, '.', ''); ?></span></li>
                                    <li><?php echo $language['lg_tax']??""; ?> (<?php echo $tax ?>%)<span><?php echo $rate_symbol; ?><?php echo number_format($tax_amount, 2, '.', ''); ?></span></li>
                                    <li>Discount <?php echo $discount_pc;?><span><?php echo $rate_symbol; ?><?php echo number_format($discount, 2, '.', ''); ?></span></li>
                                </ul>
                                <div class="booking-total">
                                    <ul class="booking-total-list">
                                        <li>
                                            <span><?php echo $language['lg_total1']??""; ?></span>
                                            <span class="total-cost"><?php echo $rate_symbol; ?><?php echo number_format($total_amount, 2, '.', ''); ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Booking Summary -->

            </div>
            <div class="col-md-7 col-lg-8">

                <div class="card">
                    <div class="card-body">
                        <?php
                        if($total_free> 0)
                            echo "You have free consultations from packages: ".$total_free."<br />";
                        if($coupon_type)
                            echo "Successfully used coupon: ".$coupon_type;
                        ?>
                        <!-- Checkout Form -->
                        <?php if (session('user_id')) { ?>
                            <div class="payment-widget">
                                <h4 class="card-title"><?php echo $language['lg_payment_method']??""; ?></h4>
                                <!-- Credit Card Payment (MamoPay) -->
                                <div class="payment-list">
                                    <label class="payment-radio credit-card-option">
                                        <input type="radio" value="Card Payment (MamoPay)" name="payment_methods">
                                        <span class="checkmark"></span>
                                        <?php echo $language['lg_credit_card']??""; ?> MamoPay
                                    </label>
                                </div>

                                <!-- MamoPay Payment Form -->
                                <div class="mamopay_payment" style="display: none;">
                                    <form action="#" method="post" id="mamopay-payment-form">
                                        <div>
                                            <div id="mamopay-card-element" style="width: 100%; min-height: 400px; height: 400px;">
                                                <!-- MamoPay Element will be inserted here. -->
                                            </div>
                                            <div id="mamopay-card-errors" role="alert"></div>
                                        </div>
                                    </form>
                                </div>
                                <?php
                                if($is_free_slot){
                                    ?>
                                    <!-- free Payment -->
                                    <div class="payment-list">
                                        <label class="payment-radio credit-card-option">
                                            <input type="radio" value="Free Payment (From Package)" name="payment_methods">
                                            <span class="checkmark"></span>
                                            Free booking (from package)
                                        </label>
                                    </div>
                                    <?php
                                }
                                if($mandarin){
                                    ?>
                                    <!-- free Payment -->
                                    <div class="payment-list">
                                        <label class="payment-radio credit-card-option">
                                            <input type="radio" value="Free Payment" name="payment_methods">
                                            <span class="checkmark"></span>
                                            Free booking (coupon)
                                        </label>
                                    </div>
                                    <?php
                                }
                                ?>


                                <!-- Credit Card Payment -->
                                <!--<div class="payment-list">
                                    <label class="payment-radio credit-card-option">
                                        <input type="radio" value="Card Payment" name="payment_methods">
                                        <span class="checkmark"></span>
                                        <?php //echo $language['lg_credit_card']??""; ?>
                                    </label>
                                </div>-->

                            <?php } ?>
                            <!-- /Credit Card Payment -->
                            <!--<div class="stripe_payment" style="display: none;">
                                <form action="#" method="post" id="payment-form">
                                    <div>
                                        <label for="card-element">
                                            <?php //echo $language['lg_credit_or_debit']??""; ?>
                                        </label>
                                        <div id="card-element" style="width: 100%">
                                            <!-- A Stripe Element will be inserted here. -->
                                        <!--</div>

                                        <!-- Used to display form errors. -->
                                        <!--<div id="card-errors" role="alert"></div>
                                    </div>
                                    <div class="submit-section mt-4 mb-4">
                                        <button class="btn btn-primary submit-btn" id="stripe_pay_btn"><?php //echo $language['lg_confirm_and_pay1']??""; ?></button>
                                    </div>
                                </form>
                            </div>-->



                            <?php if (session('user_id')) { ?>

                                <!-- Paypal Payment -->
                                <!--<div class="payment-list">
                                    <label class="payment-radio paypal-option">
                                        <input type="radio" value="PayPal" name="payment_methods">
                                        <span class="checkmark"></span>
                                        <?php //echo $language['lg_paypal']??""; ?>
                                    </label>
                                </div>-->
                                <!-- /Paypal Payment -->

                                <!-- Paypal Payment -->
                                <!--<div class="payment-list">
                                    <label class="payment-radio paypal-option">
                                        <input type="radio" value="Razorpay" name="payment_methods">
                                        <span class="checkmark"></span>
                                        <?php// echo $language['lg_razorpay']??""; ?>
                                    </label>
                                </div>-->
                                <!-- /Paypal Payment -->

                                <?php if($appointment_details[0]->appointment_type == 'center' || (strtolower($appointment_details[0]->type) == 'clinic')) { ?>
                                <div class="payment-list">
                                    <label class="payment-radio credit-card-option">
                                        <input type="radio" value="Pay on Arrive" name="payment_methods">
                                        <span class="checkmark"></span>
                                        <?php echo $language['lg_pay_on_arrive']??""; ?>
                                    </label>
                                </div>
                                <?php } ?>
                                <div>
                                    <input type="text" value="" placeholder="Enter coupon" name="coupon" id="coupon">
                                    <button id="send_coupon">OK</button>
                                </div>

                                <!-- Terms Accept -->
                                <!-- <div class="terms-accept">
												<div class="custom-checkbox">
												    <input type="checkbox" name="terms_accept" id="terms_accept" value="1">
												   <label for="terms_accept">I have read and accept <a href="#">Terms &amp; Conditions</a></label>
												</div>
											</div> -->
                                <!-- /Terms Accept -->

                                <!-- Submit Section -->
                                <!--<div class="submit-section mt-4">
                                    <div class="paypal_payment" style="display: none;">
                                        <div class="submit-section mt-4">
                                            <button type="button" id="pay_buttons" onclick="appoinment_payment('paypal')" class="btn btn-primary submit-btn"><?php //echo $language['lg_confirm_and_pay']??""; ?></button>
                                        </div>
                                    </div>
                                    <div class="razorpay_payment" style="display: none;">
                                        <div class="submit-section mt-4">
                                            <button type="button" id="razor_pay_btn" onclick="appoinment_payment('razorpay')" class="btn btn-primary submit-btn"><?php //echo $language['lg_confirm_and_pay2']??""; ?></button>
                                        </div>
                                    </div>-->
                                    <div class="clinic_payment" style="display: none;">
                                        <div class="submit-section mt-4">
                                            <button type="button" id="pay_button" onclick="appoinment_payment('stripe')" class="btn btn-primary submit-btn"><?php echo $language['lg_book_appointmen']??""; ?></button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Submit Section -->

                            </div>

                        <?php } else { ?>

                            <div class="submit-section mt-4">
                                <button type="button" data-toggle="modal" data-target="#login_modal" class="btn btn-primary"><?php echo $language['lg_signin']??""; ?></button>
                                <button type="button" data-toggle="modal" data-target="#register_modal" class="btn btn-primary"><?php echo $language['lg_signup']??""; ?></button>
                            </div>

                        <?php } ?>

                        <form role="form" method="POST" id="payment_formid" action="<?php echo base_url() . 'paypal-initiate'; ?>">
                            <?php
                            /** @var array $patients */
                            $address = !empty($patients['address1']) ? libsodiumDecrypt($patients['address1']) : $language['lg_no_address_spec']??"";
                            $info = $language['lg_booking_appoinm']??"" . ' ' . libsodiumDecrypt($doctors['first_name']) . ' ' . libsodiumDecrypt($doctors['last_name']);
                            ?>
                            <input type="hidden" name="productinfo" id="productinfo" value="<?php echo $info ?>" />
                            <input type="hidden" name="name" id="name" value="<?php echo libsodiumDecrypt($patients['first_name']) . ' ' . libsodiumDecrypt($patients['last_name']); ?>" />
                            <input type="hidden" name="phone" id="phone" value="<?php echo libsodiumDecrypt($patients['mobileno']) ?>" />
                            <input type="hidden" name="email" id="email" value="<?php echo libsodiumDecrypt($patients['email']) ?>" />
                            <input type="hidden" name="address1" id="address1" value="<?php echo $address; ?>">
                            <input type="hidden" class="form-control" id="amount" name="amount" value="<?php echo session('total_amount'); ?>" readonly />
                            <input type="hidden" class="form-control" id="currency_code" name="currency_code" value="<?php echo session('currency_code'); ?>" readonly />
                            <input type="hidden" name="access_token" id="access_token">
                            <input type="hidden" name="payment_id" id="payment_id">
                            <input type="hidden" name="order_id" id="order_id">
                            <input type="hidden" name="signature" id="signature">



                            <input type="hidden" name="payment_method" id="payment_method" value="Card Payment">
                        </form>

                        <!-- /Checkout Form -->

                    </div>
                </div>

            </div>


        </div>

    </div>

</div>

<button id="my_book_appoinment" style="display: none;"><?php echo $language['lg_purchase']??""; ?></button>
<!-- /Page Content -->


<?php
/** @var string $stripe_api_key */
$stripe_option = !empty(settings("stripe_option")) ? settings("stripe_option") : "";
if ($stripe_option == '1') {
    $stripe_api_key = !empty(settings("sandbox_api_key")) ? settings("sandbox_api_key") : "";
}
if ($stripe_option == '2') {
    $stripe_api_key = !empty(settings("live_api_key")) ? settings("live_api_key") : "";
}

?>

        <script src='https://assets.mamopay.com/stable/checkout-inline-2.0.0.min.js'></script>



    <div id='mamo-checkout-element'></div>

    <script type="text/javascript">
        var stripe_api_key = '<?php echo $stripe_api_key; ?>';
        var country = '';
        var country_code = '';
        var state = '';
        var city = '';

        // Flag to track if the payment link has been fetched
        var mamopayPaymentLinkFetched = false;
        var mamopayPaymentLink = ''; // Variable to store the fetched payment link

        document.addEventListener('DOMContentLoaded', function () {
            // Get all radio buttons for payment methods
            const paymentMethods = document.querySelectorAll('input[name="payment_methods"]');

            // Get the Stripe and MamoPay payment form elements
            //const stripePaymentForm = document.querySelector('.stripe_payment');
            const mamopayPaymentForm = document.querySelector('.mamopay_payment');

            // Add event listener to each payment method radio button
            paymentMethods.forEach(function(paymentMethod) {
                paymentMethod.addEventListener('change', function() {
                    if (this.value === 'Card Payment (Stripe)') {
                        //stripePaymentForm.style.display = 'block';
                        mamopayPaymentForm.style.display = 'none';
                    } else if (this.value === 'Card Payment (MamoPay)') {
                        if (!mamopayPaymentLinkFetched) {  // Check if the payment link was fetched before
                            async function getPaymentLink() {
                                try {
                                    const response = await fetch('<?=base_url()?>/appointment-mamopay-payment-initiate', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            appointmentId: '',
                                        })
                                    });

                                    const data = await response.json();
                                    console.log(data); // Check the full response data in the console
                                    return data.payment_url;
                                } catch (error) {
                                    console.error('Error fetching payment link:', error);
                                }
                            }

                            // Fetch the payment link and store it
                            getPaymentLink().then((paymentLink) => {
                                if (paymentLink) {
                                    mamopayPaymentLink = paymentLink;
                                    mamopayPaymentLinkFetched = true;

                                    // Use the payment link in the iFrame
                                    const mamoPay = new MamoPay();
                                    const mamoPayBlock = document.getElementById('mamopay-card-element');
                                    console.log(mamoPayBlock);
                                    mamoPay.addIframeToWebsite('mamopay-card-element', mamopayPaymentLink);
                                } else {
                                    console.error('Failed to load payment link');
                                }
                            });

                        } else {
                            // Use the already fetched payment link in the iFrame
                            const mamoPay = new MamoPay();
                            const mamoPayBlock = document.getElementById('mamopay-card-element');
                            console.log(mamoPayBlock);
                            mamoPay.addIframeToWebsite('mamopay-card-element', mamopayPaymentLink);
                        }

                        mamopayPaymentForm.style.display = 'block';
                        //stripePaymentForm.style.display = 'none';
                    }else if (this.value === 'Free Payment (From Package)') {
                        setTimeout(function(){ window.location='<?=base_url()?>'+"/set-booked-session?price_type=Free&hourly_rate=''&package=yes"; },1000);
                    }
                    else if (this.value === 'Free Payment') {
                        setTimeout(function(){ window.location='<?=base_url()?>'+"/set-booked-session?price_type=Free&hourly_rate=''"; },1000);
                        /*async function MakeFreeAppointment() {
                            try {
                                const response = await fetch('<?=base_url()?>/set-booked-session', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    body: new URLSearchParams({
                                        hourly_rate: '',
                                        appointment_details: '',
                                        price_type: 'Free'
                                    })
                                });

                                const data = await response.json();
                                console.log(data);
                            } catch (error) {
                                console.error('Error to make free appointment', error);
                            }
                        }

                        // Fetch the payment link and store it
                        MakeFreeAppointment().then((paymentLink) => {
                            setTimeout(function(){ window.location='<?=base_url()?>'+'/set-booked-session'; },1000);
                        });*/
                    }
                });
            });

            document.getElementById('send_coupon').addEventListener('click', function() {
                var couponValue = document.getElementById('coupon').value;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/set-booked-session', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                // Handle the server response
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Success - handle the server response
                        location.reload(); // Reload the page on success
                    } else {
                        // Error handling
                        console.error('Error checking coupon');
                    }
                };
                xhr.send('coupon=' + encodeURIComponent(couponValue));
            });
        });
    </script>
<?php $this->endSection(); ?>