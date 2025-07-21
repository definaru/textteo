<?php $this->extend('admin/includes/header'); ?>
<?php $this->section('content'); ?>
<!-- /Main Wrapper -->
<!-- Page Wrapper -->
<div class="page-wrapper">
  <div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <h3 class="page-title">Settings</h3>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Settings</li>
          </ul>
        </div>
      </div>
    </div>
    <!-- /Page Header -->

    <div class="row">

      <div class="col-12">

        <!-- General -->

        <div class="card">
          <div class="card-header">
            <h4 class="card-title">General</h4>
          </div>
          <div class="card-body">
            <form action="<?php echo route_to('admin/settings_submit') ?>" id="settings_form" method="POST" autocomplete="off" enctype="multipart/form-data">

              <div class="settings-tabs">
                <ul class="nav nav-tabs nav-tabs-solid">
                  <li class="nav-item">
                    <a class="nav-link active" href="#general_settings" data-toggle="tab">General Settings</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="#payment_gateway" data-toggle="tab"><span class="med-records">Payment Gateway</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#email_settings" data-toggle="tab"><span>Email</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#smtp_settings" data-toggle="tab"><span>SMTP</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#seo_settings" data-toggle="tab"><span>SEO</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#admob_settings" data-toggle="tab"><span>Admob</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#tokbox_settings" data-toggle="tab"><span>TokBox</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#sms_settings" data-toggle="tab"><span>SMS (TIWILIO)</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#social_links" data-toggle="tab"><span>Social API & Links</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#push_notification" data-toggle="tab"><span>Push Notification</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#timeout" data-toggle="tab"><span>Timeout</span></a>
                  </li>
                </ul>
              </div>

              <div class="row">
                <div class="col-lg-9">
                  <div class="tab-content">

                    <div class="tab-pane show active" id="general_settings">
                      <div class="form-group">
                        <label>Website Name</label>
                        <input type="text" class="form-control" id="website_name" name="website_name" placeholder="Website Name" value="<?php if (isset($website_name)) echo $website_name; ?>">
                      </div>
                      <div class="form-group">
                        <label>Website Logo</label>
                        <div class="uploader"><input type="file" id="site_logo" multiple="true" class="form-control" name="site_logo" placeholder="Select file"></div>
                        <p class="form-text text-muted small mb-0">Recommended image size is <b>200px x 50px</b></p>
                        <?php if (!empty($logo_front)) { ?><img src="<?php echo base_url() . $logo_front ?>" class="site-logo" style="width: 120px;"><?php } ?>
                        <div id="img_upload_error" class="text-danger" style="display:none"><b>Please upload valid image file.</b></div>
                      </div>
                      <div class="form-group">
                        <label>Footer Logo</label>
                        <div class="uploader"><input type="file" id="footer_logo" class="form-control" name="footer_logo" placeholder="Select file"></div>
                        <p class="form-text text-muted small mb-0">Recommended image size is <b>200px x 50px</b></p>
                        <?php if (!empty($logo_footer)) { ?><img src="<?php echo base_url() . $logo_footer ?>" class="site-logo" style="width: 120px;"><?php } ?>
                        <div id="img_upload_error" class="text-danger" style="display:none"><b>Please upload valid image file.</b></div>
                      </div>
                      <div class="form-group">
                        <label>Favicon</label>
                        <div class="uploader"><input type="file" multiple="true" class="form-control" id="favicon" name="favicon" placeholder="Select file"></div>
                        <p class="form-text text-muted small mb-0">Recommended image size is <b>16px x 16px</b> or <b>32px x 32px</b></p>
                        <p class="form-text text-muted small mb-1">Accepted formats: only png and ico</p>
                        <?php if (!empty($favicon)) { ?><img style="width: 37px;height: 37px;" src="<?php echo base_url() . $favicon ?>" class="fav-icon" /><?php } ?>
                        <div id="img_upload_errors" class="text-danger" style="display:none">Please upload valid image file.</div>
                      </div>
                      <div class="form-group">
                        <label>Contact No</label>
                        <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Contact No" value="<?php if (isset($contact_no)) echo $contact_no; ?>">
                      </div>
                      <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php if (isset($email)) echo $email; ?>">
                      </div>
                      <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Address"><?php if (isset($address)) echo $address; ?></textarea>
                      </div>
                      <div class="form-group">
                        <label>Zipcode</label>
                        <input onkeypress="return IsNumeric(event);" type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Zipcode" value="<?php if (isset($zipcode)) echo $zipcode; ?>">
                      </div>
                      <div class="form-group">
                        <label>Default Currency</label>
                        <?php
                        $get_currency = get_currency();
                        $user_currency_code = !(empty($default_currency)) ? $default_currency : 'USD';
                        ?>
                        <select class="form-control" name="default_currency">
                          <?php foreach ($get_currency as $row) { ?>
                            <option value="<?= $row['currency_code']; ?>" <?= ($row['currency_code'] == $user_currency_code) ? 'selected' : ''; ?>><?= $row['currency_code']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Commission %</label>
                        <input onkeypress="return IsNumeric(event);" type="text" class="form-control" id="commission" name="commission" placeholder="Commission" value="<?php if (isset($commission)) echo $commission; ?>">
                      </div>
                      <div class="form-group">
                        <label>Tax %</label>
                        <input onkeypress="return IsNumeric(event);" type="text" class="form-control" id="tax" name="tax" placeholder="Tax" value="<?php if (isset($tax)) echo $tax; ?>">
                      </div>
                      <div class="form-group">
                        <label>Transaction Charge %</label>
                        <input onkeypress="return IsNumeric(event);" type="text" class="form-control" id="transaction_charge" name="transaction_charge" placeholder="Transaction Charge" value="<?php if (isset($transaction_charge)) echo $transaction_charge; ?>">
                      </div>
                    </div>






                    <div class="tab-pane" id="payment_gateway">
                      <h4 class="card-title">PayPal</h4>
                      <div class="form-group row">
                        <div class="col-sm-9">
                          <?php
                          $ckd1 = 'checked="checked"';
                          $ckd2 = '';
                          if (isset($paypal_option)) {
                            if ($paypal_option == 1) {
                              $ckd1 = 'checked="checked"';
                              $ckd2 = '';
                            }
                            if ($paypal_option == 2) {
                              $ckd1 = '';
                              $ckd2 = 'checked="checked"';
                            }
                          } ?>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $ckd1; ?> name="paypal_option" value="1"> SandBox
                          </label>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $ckd2; ?> name="paypal_option" value="2"> Live
                          </label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-6">
                          <label>Sandbox Paypal Email</label>
                          <input class="form-control" type="text" name="sandbox_email" value="<?php if (isset($sandbox_email)) echo $sandbox_email; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Live Paypal Email</label>
                          <input class="form-control" type="text" name="live_email" value="<?php if (isset($live_email)) echo $live_email; ?>">
                        </div>
                        <!--Braintree Details-->
                        <div class="form-group col-lg-6">
                          <label>Paypal Sandbox Client ID</label>
                          <input class="form-control" type="text" name="sandbox_client_id" id="sandbox_client_id" value="<?php if (isset($sandbox_client_id)) echo $sandbox_client_id; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Paypal Live Client ID</label>
                          <input class="form-control" type="text" name="live_client_id" id="live_client_id" value="<?php if (isset($live_client_id)) echo $live_client_id; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Paypal Sandbox Secret Key</label>
                          <input class="form-control" type="text" name="sandbox_secret_key" id="sandbox_secret_key" value="<?php if (isset($sandbox_secret_key)) echo $sandbox_secret_key; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Paypal Live Secret Key</label>
                          <input class="form-control" type="text" name="live_secret_key" id="live_secret_key" value="<?php if (isset($live_secret_key)) echo $live_secret_key; ?>">
                        </div>



                        <div class="form-group col-lg-6">
                          <label>Sandbox Braintree Tokenization key</label>
                          <input class="form-control" type="text" name="braintree_key" id="braintree_key" value="<?php if (isset($braintree_key)) echo $braintree_key; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Live Braintree Tokenization key</label>
                          <input class="form-control" type="text" name="live_braintree_key" id="live_braintree_key" value="<?php if (isset($live_braintree_key)) echo $live_braintree_key; ?>">
                        </div>

                        <div class="form-group col-lg-6">
                          <label>Sandbox Braintree Merchant ID</label>
                          <input class="form-control" type="text" name="braintree_merchant" id="braintree_merchant" value="<?php if (isset($braintree_merchant)) echo $braintree_merchant; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Live Braintree Merchant ID</label>
                          <input class="form-control" type="text" name="live_braintree_merchant" id="live_braintree_merchant" value="<?php if (isset($live_braintree_merchant)) echo $live_braintree_merchant; ?>">
                        </div>

                        <div class="form-group col-lg-6">
                          <label>Sandbox Braintree Public key</label>
                          <input class="form-control" type="text" name="braintree_publickey" id="braintree_publickey" value="<?php if (isset($braintree_publickey)) echo $braintree_publickey; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Live Braintree Public key</label>
                          <input class="form-control" type="text" name="live_braintree_publickey" id="live_braintree_publickey" value="<?php if (isset($live_braintree_publickey)) echo $live_braintree_publickey; ?>">
                        </div>

                        <div class="form-group col-lg-6">
                          <label>Sandbox Braintree Private key</label>
                          <input class="form-control" type="text" name="braintree_privatekey" id="braintree_privatekey" value="<?php if (isset($braintree_privatekey)) echo $braintree_privatekey; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Live Braintree Private key</label>
                          <input class="form-control" type="text" name="live_braintree_privatekey" id="live_braintree_privatekey" value="<?php if (isset($live_braintree_privatekey)) echo $live_braintree_privatekey; ?>">
                        </div>

                        <div class="form-group col-lg-6">
                          <label>Sandbox Paypal APP ID</label>
                          <input class="form-control" type="text" name="paypal_appid" id="paypal_appid" value="<?php if (isset($paypal_appid)) echo $paypal_appid; ?>">
                        </div>
                        <div class="form-group col-lg-6">
                          <label>Live Paypal APP ID</label>
                          <input class="form-control" type="text" name="live_paypal_appid" id="live_paypal_appid" value="<?php if (isset($live_paypal_appid)) echo $live_paypal_appid; ?>">
                        </div>
                        <!--Braintree Details-->
                      </div>

                      <h4 class="card-title">Stripe</h4>

                      <div class="form-group row">
                        <div class="col-sm-9">
                          <?php
                          $ckd1 = 'checked="checked"';
                          $ckd2 = '';
                          if (isset($stripe_option)) {
                            if ($stripe_option == 1) {
                              $ckd1 = 'checked="checked"';
                              $ckd2 = '';
                            }
                            if ($stripe_option == 2) {
                              $ckd1 = '';
                              $ckd2 = 'checked="checked"';
                            }
                          } ?>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $ckd1; ?> name="stripe_option" value="1"> SandBox
                          </label>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $ckd2; ?> name="stripe_option" value="2"> Live
                          </label>
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Sandbox API Key</label>
                        <input type="text" id="sandbox_api_key" name="sandbox_api_key" value="<?php if (isset($sandbox_api_key)) echo $sandbox_api_key; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Sandbox Rest Key</label>
                        <input type="text" id="sandbox_rest_key" name="sandbox_rest_key" value="<?php if (isset($sandbox_rest_key)) echo $sandbox_rest_key; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Live API Key</label>
                        <input type="text" id="live_api_key" name="live_api_key" value="<?php if (isset($live_api_key)) echo $live_api_key; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Live Rest Key</label>
                        <input type="text" id="live_rest_key" name="live_rest_key" value="<?php if (isset($live_rest_key)) echo $live_rest_key; ?>" class="form-control">
                      </div>


                      <h4 class="card-title">Razorpay</h4>
                      <div class="form-group row">
                        <div class="col-sm-9">
                          <?php
                          $rkd1 = 'checked="checked"';
                          $rkd2 = '';
                          if (isset($razorpay_option)) {
                            if ($razorpay_option == 1) {
                              $rkd1 = 'checked="checked"';
                              $rkd2 = '';
                            }
                            if ($razorpay_option == 2) {
                              $rkd1 = '';
                              $rkd2 = 'checked="checked"';
                            }
                          } ?>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $rkd1; ?> name="razorpay_option" value="1"> SandBox
                          </label>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $rkd2; ?> name="razorpay_option" value="2"> Live
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Sandbox Key Id</label>
                        <input type="text" id="sandbox_key_id" name="sandbox_key_id" value="<?php if (isset($sandbox_key_id)) echo $sandbox_key_id; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Sandbox Key Secret</label>
                        <input type="text" id="sandbox_key_secret" name="sandbox_key_secret" value="<?php if (isset($sandbox_key_secret)) echo $sandbox_key_secret; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Live Key Id</label>
                        <input type="text" id="live_key_id" name="live_key_id" value="<?php if (isset($live_key_id)) echo $live_key_id; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Live Key Secret</label>
                        <input type="text" id="live_key_secret" name="live_key_secret" value="<?php if (isset($live_key_secret)) echo $live_key_secret; ?>" class="form-control">
                      </div>


                    </div>

                    <div class="tab-pane" id="email_settings">
                      <h4 class="card-title">Email</h4>
                      <div class="form-group">
                        <div class="col-sm-9">
                          <?php
                          $mail1 = '';
                          $mail2 = 'checked="checked"';
                          if (isset($mail_option)) {
                            if ($mail_option == 1) {
                              $mail1 = 'checked="checked"';
                              $mail2 = '';
                            }
                            if ($mail_option == 2) {
                              $mail1 = '';
                              $mail2 = 'checked="checked"';
                            }
                          } ?>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $mail1; ?> name="mail_option" value="1"> Enable
                          </label>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $mail2; ?> name="mail_option" value="2"> Disable
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-9">
                          <?php
                          $email1 = 'checked="checked"';
                          $email2 = '';
                          if (isset($email_option)) {
                            if ($email_option == 'smtp') {
                              $email1 = 'checked="checked"';
                              $email2 = '';
                            }
                            if ($email_option == 'sendgrid') {
                              $email1 = '';
                              $email2 = 'checked="checked"';
                            }
                          } ?>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $email1; ?> name="email_option" value="smtp"> SMTP
                          </label>
                          <label class="radio-inline">
                            <input type="radio" <?php echo $email2; ?> name="email_option" value="sendgrid"> Sendgrid
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Sendgrid API Key</label>
                        <input type="text" class="form-control" id="sendgrid_apikey" name="sendgrid_apikey" value="<?php if (isset($sendgrid_apikey)) echo libsodiumDecrypt($sendgrid_apikey); ?>">
                      </div>
                      <div class="form-group">
                        <label>Email From Address</label>
                        <input type="text" class="form-control" id="email_address" name="email_address" value="<?php if (isset($email_address)) echo libsodiumDecrypt($email_address); ?>">
                      </div>
                      <div class="form-group">
                        <label>Email Title</label>
                        <input type="text" class="form-control" id="email_tittle" name="email_tittle" value="<?php if (isset($email_tittle)) echo $email_tittle; ?>">
                      </div>
                    </div>

                    <div class="tab-pane" id="smtp_settings">
                      <h4 class="card-title">SMTP</h4>
                      <div class="form-group">
                        <label>SMTP Host</label>
                        <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="<?php if (isset($smtp_host)) echo $smtp_host; ?>">
                      </div>
                      <div class="form-group">
                        <label>SMTP Port</label>
                        <input type="text" class="form-control" id="smtp_port" name="smtp_port" value="<?php if (isset($smtp_port)) echo $smtp_port; ?>">
                      </div>
                      <div class="form-group">
                        <label>SMTP User</label>
                        <input type="text" class="form-control" id="smtp_user" name="smtp_user" value="<?php if (isset($smtp_user)) echo libsodiumDecrypt($smtp_user); ?>">
                      </div>
                      <div class="form-group">
                        <label>SMTP Password</label>
                        <input type="password" class="form-control" id="smtp_pass" name="smtp_pass" value="<?php if (isset($smtp_pass)) echo libsodiumDecrypt($smtp_pass); ?>">
                      </div>
                    </div>

                    <div class="tab-pane" id="seo_settings">
                      <h4 class="card-title">SEO</h4>
                      <div class="form-group">
                        <label>Meta title</label>
                        <input type="text" class="form-control" id="mete_title" name="meta_title" value="<?php if (isset($meta_title)) echo $meta_title; ?>">
                      </div>
                      <div class="form-group">
                        <label>Meta keywords</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="<?php if (isset($meta_keywords)) echo $meta_keywords; ?>">
                      </div>
                      <div class="form-group">
                        <label>Meta description</label>
                        <textarea class="form-control" rows="6" id="meta_description" name="meta_description"><?php if (isset($meta_description)) echo $meta_description; ?></textarea>
                      </div>
                    </div>

                    <div class="tab-pane" id="admob_settings">
                      <h4 class="card-title">Admob</h4>
                      <div class="form-group">
                        <label>Admob App ID</label>
                        <input type="text" class="form-control" id="admob" name="admob" placeholder="33BE2250B43518CCDA7DE426D04EE231" value="<?php if (isset($admob)) echo $admob; ?>">
                      </div>
                      <div class="form-group">
                        <label>Footer banner</label>
                        <input type="text" class="form-control" id="admob_footer_banner" name="admob_footer_banner" placeholder="ca-app-pub-3940256043534543544/656578111" value="<?php if (isset($admob_footer_banner)) echo $admob_footer_banner; ?>">
                      </div>
                    </div>

                    <div class="tab-pane" id="tokbox_settings">
                      <h4 class="card-title">Tokbox</h4>
                      <div class="form-group">
                        <label>API Key</label>
                        <input type="text" id="apiKey" name="apiKey" value="<?php if (isset($apiKey)) echo libsodiumDecrypt($apiKey); ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>API Secret</label>
                        <input type="text" id="apiSecret" name="apiSecret" value="<?php if (isset($apiSecret)) echo libsodiumDecrypt($apiSecret); ?>" class="form-control">
                      </div>
                    </div>

                    <div class="tab-pane" id="sms_settings">
                      <h4 class="card-title">SMS(TIWILIO)</h4>
                      <?php
                      $nd1 = 'checked="checked"';
                      $nd2 = '';
                      if (isset($tiwilio_option)) {
                        if ($tiwilio_option == 1) {
                          $nd1 = 'checked="checked"';
                          $nd2 = '';
                        }
                        if ($tiwilio_option == 2) {
                          $nd1 = '';
                          $nd2 = 'checked="checked"';
                        }
                      } ?>
                      <label class="radio-inline">
                        <input type="radio" <?php echo $nd1; ?> name="tiwilio_option" value="1"> Enable
                      </label>
                      <label class="radio-inline">
                        <input type="radio" <?php echo $nd2; ?> name="tiwilio_option" value="2"> Disable
                      </label>
                      <div class="form-group">
                        <label>Account SID</label>
                        <input type="text" id="tiwilio_apiKey" name="tiwilio_apiKey" value="<?php if (isset($tiwilio_apiKey)) echo $tiwilio_apiKey; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Auth Token</label>
                        <input type="text" id="tiwilio_apiSecret" name="tiwilio_apiSecret" value="<?php if (isset($tiwilio_apiSecret)) echo libsodiumDecrypt($tiwilio_apiSecret); ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>From Mobile No</label>
                        <input type="text" id="tiwilio_from_no" name="tiwilio_from_no" value="<?php if (isset($tiwilio_from_no)) echo $tiwilio_from_no; ?>" class="form-control">
                      </div>
                    </div>

                    <div class="tab-pane" id="social_links">

                      <h4 class="card-title">Google Map</h4>
                      <div class="form-group">
                        <label>Google Map API Key</label>
                        <input type="text" class="form-control" id="google_map_api" name="google_map_api" placeholder="Google Map API Key" value="<?php if (isset($google_map_api)) echo libsodiumDecrypt($google_map_api); ?>">
                      </div>

                      <h4 class="card-title">Social Login</h4>
                      <h6 class="card-title">Google Login</h6>
                      <div class="form-group">
                        <label>Client ID</label>
                        <input type="text" id="googleclientid" name="googleclientid" value="<?php if (isset($googleclientid)) echo $googleclientid; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Client Secret</label>
                        <input type="text" id="googlesecret" name="googlesecret" value="<?php if (isset($googlesecret)) echo $googlesecret; ?>" class="form-control">
                      </div>
                      <h6 class="card-title">Facebook Login</h6>
                      <div class="form-group">
                        <label>App ID</label>
                        <input type="text" id="facebookclientid" name="facebookclientid" value="<?php if (isset($facebookclientid)) echo $facebookclientid; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>App Secret</label>
                        <input type="text" id="facebooksecret" name="facebooksecret" value="<?php if (isset($facebooksecret)) echo $facebooksecret; ?>" class="form-control">
                      </div>
                      <h4 class="card-title">Social Links</h4>
                      <div class="form-group">
                        <label>FaceBook</label>
                        <input type="text" id="facebook" placeholder="https://www.facebook.com" name="facebook" value="<?php if (isset($facebook)) echo $facebook; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Twitter</label>
                        <input type="text" id="twitter" placeholder="https://www.twitter.com" name="twitter" value="<?php if (isset($twitter)) echo $twitter; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Google+</label>
                        <input type="text" id="google_plus" placeholder="https://plus.google.com" name="google_plus" value="<?php if (isset($google_plus)) echo $google_plus; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>LinkedIn</label>
                        <input type="text" id="linkedIn" placeholder="https://www.linkedin.com" name="linkedIn" value="<?php if (isset($linkedIn)) echo $linkedIn; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Instagram</label>
                        <input type="text" id="instagram" placeholder="https://www.instagram.com" name="instagram" value="<?php if (isset($instagram)) echo $instagram; ?>" class="form-control">
                      </div>

                    </div>

                    <div class="tab-pane" id="push_notification">
                      <h4 class="card-title">Push Notification</h4>
                      <div class="form-group">
                        <label>Firebase API Key</label>
                        <input type="text" id="fcm_api_access_key" name="fcm_api_access_key" value="<?php if (isset($fcm_api_access_key)) echo $fcm_api_access_key; ?>" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>APNS Pem File</label>
                        <input type="file" id="apns_pem_file" name="apns_pem_file" value="<?php if (isset($apns_pem_file)) echo $apns_pem_file; ?>" class="form-control">
                        <?php if (!empty($apns_pem_file)) { ?><a target="_blank" href="<?php echo base_url() . $apns_pem_file; ?>">Download</a><?php } ?>
                      </div>
                      <div class="form-group">
                        <label>APNS Password</label>
                        <input type="text" id="apns_password" name="apns_password" value="<?php if (isset($apns_password)) echo $apns_password; ?>" class="form-control">
                      </div>

                    </div>

                    <div class="tab-pane" id="timeout">
                      <h4 class="card-title">Timeout</h4>
                      <div class="form-group">
                        <label>Timeout (in minutes)</label>
                        <input type="text" id="session_time_out" name="session_time_out" value="<?php if (isset($session_time_out)) echo $session_time_out; ?>" class="form-control">
                      </div>
                    </div>
                  </div>

                  <button name="form_submit" id="form_submit" type="submit" class="btn btn-primary center-block" value="true">Save Changes</button>

                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- /General -->

      </div>
    </div>

  </div>
</div>
<!-- /Page Wrapper -->

</div>
<?php $this->endSection(); ?>