<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log in</title>
    <?php /* /assets/css/font-awesome/awesome.min.css */ ?>
    <link href="/uploads/logo/1716626275_8357ec0a82c3394e6aa6.png" rel="icon">
    <link rel="stylesheet" href="/assets/css/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="/assets/toastr_2.1.3/toastr.css">
    <link rel="stylesheet" href="/assets/css/media.min.css" />
    <link rel="stylesheet" href="/styles.css" />
</head>
<body>
    <?=$this->include('layout/header');?>
    <main class="vh-100 vstack justify-content-center">
        <section class="create-account-section py-5">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-12 col-md-6 d-none d-md-block mb-4 mb-md-0 text-md-start">
                        <img 
                            src="assets/images/create-account.png" 
                            alt="Registration Illustration" 
                            class="img-fluid"
                            style="max-width: 480px;" 
                        />
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card p-4 shadow-sm border-0">
                            <h2 class="mb-3">Log in</h2>
                            <form action="#" action="#" id="signin_form" method="post" autocomplete="off">
                                <!-- Email -->
                                <div class="mb-3">
                                    <input name="email" type="email" class="form-control" id="emailInput" placeholder="Email" required />
                                </div>
                                <!-- Password -->
                                <div class="mb-3">
                                    <input type="password"  name="password" class="form-control" id="passwordInput" placeholder="Password"
                                        required />
                                </div>
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-warning w-100 mb-3">
                                    Login
                                </button>
                                </form>
                                <!-- Already have an account? Sign up -->
                                <div class="mb-3 text-center text-md-start">
                                    Reset Password
                                    <a href="/forgot-password" class="text-warning fw-semibold">Reset Password</a>
                                </div>

                            <!-- Already have an account? Sign up -->
                            <div class="mb-3 text-center text-md-start">
                                Don’t have an account?
                                <a href="/register" class="text-warning fw-semibold">Sign up</a>
                            </div>
                        </div><!-- end card -->
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?=$this->include('layout/footer');?>

    <script type="text/javascript">
        var base_url='<?=base_url();?>';
        var modules='<?=$module;?>';
        var pages='<?=$page;?>';
        var roles='<?=session()->get('role');?>';

        var lang_patient="<?=$language['lg_patient4'] ?? ' ';?>";
        var lang_doctor="<?=$language['lg_doctor2'] ?? ' ';?>";
        var lg_please_enter_yo="<?=$language['lg_please_enter_yo'] ?? ' ';?>";
        var lg_please_enter_yo1="<?=$language['lg_please_enter_yo1'] ?? ' ';?>";
        var lg_please_enter_mo="<?=$language['lg_please_enter_mo'] ?? ' ';?>";
        var lg_please_enter_va="<?=$language['lg_please_enter_va'] ?? ' ';?>";
        var lg_your_mobile_no_="<?=$language['lg_your_mobile_no_'] ?? ' ';?>";
        var lg_please_enter_em="<?=$language['lg_please_enter_em'] ?? ' ';?>";
        var lg_please_enter_va1="<?=$language['lg_please_enter_va1'] ?? ' ';?>";
        var lg_your_email_addr1="<?=$language['lg_your_email_addr1'] ?? ' ';?>";
        var lg_please_enter_pa="<?=$language['lg_please_enter_pa'] ?? ' ';?>";
        var lg_please_enter_new_pa="<?=$language['lg_please_enter_new_pa'] ?? ' ';?>";
        var lg_your_password_m="<?=$language['lg_your_password_m'] ?? ' ';?>";
        var lg_your_password_max="<?=$language['lg_your_password_max'] ?? ' ';?>";
        var lg_please_enter_co="<?=$language['lg_please_enter_co'] ?? ' ';?>";
        var lg_your_password_d="<?=$language['lg_your_password_d'] ?? ' ';?>";
        var lg_signup="<?=$language['lg_signup'] ?? ' ';?>";
        var lg_signin="<?=$language['lg_signin'] ?? ' ';?>";
        var lg_your_email_addr="<?=$language['lg_your_email_addr'] ?? ' ';?>";
        var lg_reset_password="<?=$language['lg_reset_password'] ?? ' ';?>";
        var lg_confirm3="<?=$language['lg_confirm3'] ?? ' ';?>";
        var lg_please_wait="<?=$language['lg_please_wait'] ?? ' ';?>";
        var lg_select_country="<?=$language['lg_select_country'] ?? ' ';?>";
        var lg_select_state="<?=$language['lg_select_state'] ?? ' ';?>";
        var lg_select_city="<?=$language['lg_select_city'] ?? ' ';?>";
        var lg_select_speciali1="<?=$language['lg_select_speciali1'] ?? ' ';?>";
        var lg_please_select_g="<?=$language['lg_please_select_g'] ?? ' ';?>";
        var lg_please_enter_yo2="<?=$language['lg_please_enter_yo2'] ?? ' ';?>";
        var lg_please_enter_yo3="<?=$language['lg_please_enter_yo3'] ?? ' ';?>";
        var lg_please_enter_yo4="<?=$language['lg_please_enter_yo4'] ?? ' ';?>";
        var lg_please_select_c="<?=$language['lg_please_select_c'] ?? ' ';?>";
        var lg_please_select_c_code="<?=$language['lg_please_select_c_code'] ?? ' ';?>";
        var lg_please_select_s="<?=$language['lg_please_select_s'] ?? ' ';?>";
        var lg_enter_address_max="<?=$language['lg_enter_address_max'] ?? ' ';?>";
        var lg_please_select_c1="<?=$language['lg_please_select_c1'] ?? ' ';?>";
        var lg_please_enter_po="<?=$language['lg_please_enter_po'] ?? ' ';?>";
        var lg_please_enter_va2="<?=$language['lg_please_enter_va2'] ?? ' ';?>";
        var lg_please_select_p="<?=$language['lg_please_select_p'] ?? ' ';?>";
        var lg_please_enter_am="<?=$language['lg_please_enter_am'] ?? ' ';?>";
        var lg_please_enter_va3="<?=$language['lg_please_enter_va3'] ?? ' ';?>";
        var lg_please_enter_se="<?=$language['lg_please_enter_se'] ?? ' ';?>";
        var lg_please_select_s1="<?=$language['lg_please_select_s1'] ?? ' ';?>";
        var lg_please_enter_de="<?=$language['lg_please_enter_de'] ?? ' ';?>";
        var lg_please_enter_in="<?=$language['lg_please_enter_in'] ?? ' ';?>";
        var lg_please_enter_ye="<?=$language['lg_please_enter_ye'] ?? ' ';?>";
        var lg_please_select_b="<?=$language['lg_please_select_b'] ?? ' ';?>";
        var lg_dr="<?=$language['lg_dr'] ?? ' ';?>";
        var lg_no_users_found="<?=$language['lg_no_users_found'] ?? ' ';?>";
        var lg_feedback="<?=$language['lg_feedback'] ?? ' ';?>";
        var lg_view_profile="<?=$language['lg_view_profile'] ?? ' ';?>";
        var lg_book_appointmen="<?=$language['lg_book_appointmen'] ?? ' ';?>";
        var lg_no_doctors_foun="<?=$language['lg_no_doctors_foun'] ?? ' ';?>";
        var lg_more="<?=$language['lg_more'] ?? ' ';?>";
        var lg_less="<?=$language['lg_less'] ?? ' ';?>";
        var lg_matches_for_you="<?=$language['lg_matches_for_you'] ?? ' ';?>";
        var lg_patient_id="<?=$language['lg_patient_id'] ?? ' ';?>";
        var lg_phone="<?=$language['lg_phone'] ?? ' ';?>";
        var lg_age="<?=$language['lg_age'] ?? ' ';?>";
        var lg_blood_group="<?=$language['lg_blood_group'] ?? ' ';?>";
        var lg_no_patients_fou="<?=$language['lg_no_patients_fou'] ?? ' ';?>";
        var lg_are_you_sure_to="<?=$language['lg_are_you_sure_to'] ?? ' ';?>";
        var lg_your_existing_s="<?=$language['lg_your_existing_s'] ?? ' ';?>";
        var lg_please_select_f="<?=$language['lg_please_select_f'] ?? ' ';?>";
        var lg_please_select_t="<?=$language['lg_please_select_t'] ?? ' ';?>";
        var lg_please_select_t1="<?=$language['lg_please_select_t1'] ?? ' ';?>";
        var lg_please_select_s2="<?=$language['lg_please_select_s2'] ?? ' ';?>";
        var lg_add10="<?=$language['lg_add10'] ?? ' ';?>";
        var lg_select_time="<?=$language['lg_select_time'] ?? ' ';?>";
        var lg_date_is_require="<?=$language['lg_date_is_require'] ?? ' ';?>";
        var lg_dob_is_require="<?=$language['lg_dob_is_require'] ?? ' '; ?>";
        var lg_please_select_a="<?=$language['lg_please_select_a'] ?? ' ';?>";
        var lg_appoinment_requ="<?=$language['lg_appoinment_requ'] ?? ' ';?>";
        var lg_please_accept_t="<?=$language['lg_please_accept_t'] ?? ' ';?>";
        var lg_transaction_suc="<?=$language['lg_transaction_suc'] ?? ' ';?>";
        var lg_transaction_fai1="<?=$language['lg_transaction_fai1'] ?? ' ';?>";
        var lg_sno="<?=$language['lg_sno'] ?? ' ';?>";
        var lg_drug_name="<?=$language['lg_drug_name'] ?? ' ';?>";
        var lg_quantity="<?=$language['lg_quantity'] ?? ' ';?>";
        var lg_type="<?=$language['lg_type'] ?? ' ';?>";
        var lg_days="<?=$language['lg_days'] ?? ' ';?>";
        var lg_time="<?=$language['lg_time'] ?? ' ';?>";
        var lg_doctor_signatur="<?=$language['lg_doctor_signatur'] ?? ' ';?>";
        var lg_prescription="<?=$language['lg_prescription'] ?? ' ';?>";
        var lg_name="<?=$language['lg_name'] ?? ' ';?>";
        var lg_description="<?=$language['lg_description'] ?? ' ';?>";
        var lg_amount="<?=$language['lg_amount'] ?? ' ';?>";
        var lg_doctor_billing="<?=$language['lg_doctor_billing'] ?? ' ';?>";
        var lg_bill4="<?=$language['lg_bill4'] ?? ' ';?>";
        var lg_please_upload_m="<?=$language['lg_please_upload_m'] ?? ' ';?>";
        var lg_file_size_must_="<?=$language['lg_file_size_must_'] ?? ' ';?>";
        var lg_submit="<?=$language['lg_submit'] ?? ' ';?>";
        var lg_medical_records="<?=$language['lg_medical_records'] ?? ' ';?>";
        var lg_yes="<?=$language['lg_yes'] ?? ' ';?>";
        var lg_save="<?=$language['lg_save'] ?? ' ';?>";
        var lg_select_type="<?=$language['lg_select_type'] ?? ' ';?>";
        var lg_before_food="<?=$language['lg_before_food'] ?? ' ';?>";
        var lg_after_food="<?=$language['lg_after_food'] ?? ' ';?>";
        var lg_morning="<?=$language['lg_morning'] ?? ' ';?>";
        var lg_afternoon="<?=$language['lg_afternoon'] ?? ' ';?>";
        var lg_evening="<?=$language['lg_evening'] ?? ' ';?>";
        var lg_night="<?=$language['lg_night'] ?? ' ';?>";
        var lg_please_enter_dr="<?=$language['lg_please_enter_dr'] ?? ' ';?>";
        var lg_please_enter_qt="<?=$language['lg_please_enter_qt'] ?? ' ';?>";
        var lg_please_enter_da="<?=$language['lg_please_enter_da'] ?? ' ';?>";
        var lg_please_select_t2="<?=$language['lg_please_select_t2'] ?? ' ';?>";
        var lg_please_sign_to_="<?=$language['lg_please_sign_to_'] ?? ' ';?>";
        var lg_update="<?=$language['lg_update'] ?? ' ';?>";
        var lg_please_enter_na="<?=$language['lg_please_enter_na'] ?? ' ';?>";
        var lg_please_enter_cu="<?=$language['lg_please_enter_cu'] ?? ' ';?>";
        var lg_your_current_pa="<?=$language['lg_your_current_pa'] ?? ' ';?>";
        var lg_change_password="<?=$language['lg_change_password'] ?? ' ';?>";
        var lg_accept="<?=$language['lg_accept'] ?? ' ';?>";
        var lg_cancel="<?=$language['lg_cancel'] ?? ' ';?>";
        var lg_view1="<?=$language['lg_view1'] ?? ' ';?>";
        var lg_no_appoinments_="<?=$language['lg_no_appoinments_'] ?? ' ';?>";
        var lg_cancelled="<?=$language['lg_cancelled'] ?? ' ';?>";
        var lg_day1="<?=$language['lg_day1'] ?? ' ';?>";
        var lg_days="<?=$language['lg_days'] ?? ' ';?>";
        var lg_remaining_time_="<?=$language['lg_remaining_time_'] ?? ' ';?>";
        var lg_conversation_wi="<?=$language['lg_conversation_wi'] ?? ' ';?>";
        var lg_add_review="<?=$language['lg_add_review'] ?? ' ';?>";
        var lg_thank_you_for_y="<?=$language['lg_thank_you_for_y'] ?? ' ';?>";
        var lg_please_wait__="<?=$language['lg_please_wait__'] ?? ' ';?>";
        var lg_thats_all="<?=$language['lg_thats_all'] ?? ' ';?>";
        var lg_download="<?=$language['lg_download'] ?? ' ';?>";
        var lg_load_more="<?=$language['lg_load_more'] ?? ' ';?>";
        var lg_degree="<?=$language['lg_degree'] ?? ' ';?>";
        var lg_collegeinstitut="<?=$language['lg_collegeinstitut'] ?? ' ';?>";
        var lg_year_of_complet="<?=$language['lg_year_of_complet'] ?? ' ';?>";
        var lg_hospital_name="<?=$language['lg_hospital_name'] ?? ' ';?>";
        var lg_from="<?=$language['lg_from'] ?? ' ';?>";
        var lg_to3="<?=$language['lg_to3'] ?? ' ';?>";
        var lg_designation="<?=$language['lg_designation'] ?? ' ';?>";
        var lg_awards="<?=$language['lg_awards'] ?? ' ';?>";
        var lg_year="<?=$language['lg_year'] ?? ' ';?>";
        var lg_memberships="<?=$language['lg_memberships'] ?? ' ';?>";
        var lg_registrations="<?=$language['lg_registrations'] ?? ' ';?>";
        var lg_save_changes="<?=$language['lg_save_changes'] ?? ' ';?>";
        var lg_first_name_shou="<?=$language['lg_first_name_shou'] ?? ' ';?>";
        var lg_last_name_shoul="<?=$language['lg_last_name_shoul'] ?? ' ';?>";
        var lg_first_name_shou_max15="<?=$language['lg_first_name_shou_max15'] ?? ' ';?>";
        var lg_last_name_shou1_max15="<?=$language['lg_last_name_shou1_max15'] ?? ' ';?>";

        var lg_number_should_b="<?=$language['lg_number_should_b'] ?? ' ';?>";
        var lg_number_should_b1="<?=$language['lg_number_should_b1'] ?? ' ';?>";
        var lg_digits_are_only="<?=$language['lg_digits_are_only'] ?? ' ';?>";

        var lg_calling_="<?=$language['lg_calling_'] ?? ' ';?>";
        var lg_error_posting_f="<?=$language['lg_error_posting_f'] ?? ' ';?>";
        var lg_add_category="<?=$language['lg_add_category'] ?? ' ';?>";
        var lg_edit_category="<?=$language['lg_edit_category'] ?? ' ';?>";
        var lg_delete_rw="<?=$language['lg_delete_rw'] ?? ' ';?>";
        var lg_are_you_sure_de="<?=$language['lg_are_you_sure_de'] ?? ' ';?>";
        var lg_category_delete="<?=$language['lg_category_delete'] ?? ' ';?>";
        var lg_please_enter_ca="<?=$language['lg_please_enter_ca'] ?? ' ';?>";
        var lg_select_category="<?=$language['lg_select_category'] ?? ' ';?>";
        var lg_add_subcategory="<?=$language['lg_add_subcategory'] ?? ' ';?>";
        var lg_edit_subcategor="<?=$language['lg_edit_subcategor'] ?? ' ';?>";
        var lg_are_you_sure_de1="<?=$language['lg_are_you_sure_de1'] ?? ' ';?>";
        var lg_subcategory_del="<?=$language['lg_subcategory_del'] ?? ' ';?>";
        var lg_please_select_c2="<?=$language['lg_please_select_c2'] ?? ' ';?>";
        var lg_please_enter_su="<?=$language['lg_please_enter_su'] ?? ' ';?>";
        var lg_are_you_sure_de2="<?=$language['lg_are_you_sure_de2'] ?? ' ';?>";
        var lg_post_deleted_su="<?=$language['lg_post_deleted_su'] ?? ' ';?>";
        var lg_select_subcateg="<?=$language['lg_select_subcateg'] ?? ' ';?>";
        var lg_no_blogs_found="<?=$language['lg_no_blogs_found'] ?? ' ';?>";
        var lg_post1="<?=$language['lg_post1'] ?? ' ';?>";
        var lg_please_enter_bl="<?=$language['lg_please_enter_bl'] ?? ' ';?>";
        var lg_please_select_b1="<?=$language['lg_please_select_b1'] ?? ' ';?>";
        var lg_please_select_b2="<?=$language['lg_please_select_b2'] ?? ' ';?>";
        var lg_please_upload_b="<?=$language['lg_please_upload_b'] ?? ' ';?>";
        var lg_please_enter_bl1="<?=$language['lg_please_enter_bl1'] ?? ' ';?>";
        var lg_warning="<?=$language['lg_warning'] ?? ' ';?>";
        var lg_are_you_sure_yo="<?=$language['lg_are_you_sure_yo'] ?? ' ';?>";
        var lg_comment3="<?=$language['lg_comment3'] ?? ' ';?>";
        var lg_reply="<?=$language['lg_reply'] ?? ' ';?>";
        var lg_please_upload_s="<?=$language['lg_please_upload_s'] ?? ' ';?>";
        var lg_invalid_extensi="<?=$language['lg_invalid_extensi'] ?? ' ';?>";
        var lg_done="<?=$language['lg_done'] ?? ' ';?>";
        var lg_please_upload_s1="<?=$language['lg_please_upload_s1'] ?? ' ';?>";
        var lg_resend_otp="<?=$language['lg_resend_otp'] ?? ' ';?>";
        var lg_please_enter_ke="<?=$language['lg_please_enter_ke'] ?? ' ';?>";
        var lg_location2="<?=$language['lg_location2'] ?? ' ';?>";
        var lg_no_city_found="<?=$language['lg_no_city_found'] ?? ' ';?>";
        var lg_speciality="<?=$language['lg_speciality'] ?? ' ';?>";
        var lg_specialist="<?=$language['lg_specialist'] ?? ' ';?>";
        var lg_no_doctors_foun1="<?=$language['lg_no_doctors_foun1'] ?? ' ';?>";
        var lg_select_country_="<?=$language['lg_select_country_'] ?? ' ';?>";
        var lg_please_enter_ph="<?=$language['lg_please_enter_ph'] ?? ' ';?>";
        var lg_pharmacy_detail="<?=$language['lg_pharmacy_detail'] ?? ' ';?>";
        var lg_something_went_1="<?=$language['lg_something_went_1'] ?? ' ';?>";
        var lg_view_more="<?=$language['lg_view_more'] ?? ' ';?>";
        var lg_session="<?=$language['lg_session'] ?? ' ';?>";
        var lg_start_time="<?=$language['lg_start_time'] ?? ' ';?>";
        var lg_end_time="<?=$language['lg_end_time'] ?? ' ';?>";
        var lg_no_of_tokens="<?=$language['lg_no_of_tokens'] ?? ' ';?>";
        var lg_start_time_is_r="<?=$language['lg_start_time_is_r'] ?? ' ';?>";
        var lg_end_time_is_req="<?=$language['lg_end_time_is_req'] ?? ' ';?>";
        var lg_please_choose_a="<?=$language['lg_please_choose_a'] ?? ' ';?>";
        var lg_please_select_a1="<?=$language['lg_please_select_a1'] ?? ' ';?>";
        var lg_proceed_to_pay="<?=$language['lg_proceed_to_pay'] ?? ' ';?>";
        var lg_please_enter_em1="<?=$language['lg_please_enter_em1'] ?? ' ';?>";
        var lg_please_enter_yo5="<?=$language['lg_please_enter_yo5'] ?? ' ';?>";
        var lg_please_enter_yo6="<?=$language['lg_please_enter_yo6'] ?? ' ';?>";
        var lg_name_is_require="<?=$language['lg_name_is_require'] ?? ' ';?>";
        var lg_email_is_requir="<?=$language['lg_email_is_requir'] ?? ' ';?>";
        var lg_mobile_no_is_re="<?=$language['lg_mobile_no_is_re'] ?? ' ';?>";
        var lg_address1_is_req="<?=$language['lg_address1_is_req'] ?? ' ';?>";
        var lg_country_is_requ="<?=$language['lg_country_is_requ'] ?? ' ';?>";
        var lg_state_is_requir="<?=$language['lg_state_is_requir'] ?? ' ';?>";
        var lg_city_is_require="<?=$language['lg_city_is_require'] ?? ' ';?>";
        var lg_postal_code_is_="<?=$language['lg_postal_code_is_'] ?? ' ';?>";
        var lg_your_order_has_="<?=$language['lg_your_order_has_'] ?? ' ';?>";
        var lg_confirm_and_pay="<?=$language['lg_confirm_and_pay'] ?? ' ';?>";
        var lg_confirm_and_pay2="<?=$language['lg_confirm_and_pay2'] ?? ' ';?>";
        var lg_activation_mail="<?=$language['lg_activation_mail'] ?? ' ';?>";
        var lg_please_enter_ba="<?=$language['lg_please_enter_ba'] ?? ' ';?>";
        var lg_please_enter_br="<?=$language['lg_please_enter_br'] ?? ' ';?>";
        var lg_please_enter_ac1="<?=$language['lg_please_enter_ac1'] ?? ' ';?>";
        var lg_please_enter_ac2="<?=$language['lg_please_enter_ac2'] ?? ' ';?>";
        var lg_request1="<?=$language['lg_request1'] ?? ' ';?>";
        var lg_no_products_fou="<?=$language['lg_no_products_fou'] ?? ' ';?>";
        var lg_select_services="<?=$language['lg_select_services'] ?? ' ';?>";
        var lg_select_gender="<?=$language['lg_select_gender'] ?? ' ';?>";
        var lg_search3="<?=$language['lg_search3'] ?? ' ';?>";
        var lg_view_details="<?=$language['lg_view_details'] ?? ' ';?>";
        var lg_browse_products="<?=$language['lg_browse_products'] ?? ' ';?>";
        var lg_no_pharmacy_fou="<?=$language['lg_no_pharmacy_fou'] ?? ' ';?>";
        var lg_status_updated_="<?=$language['lg_status_updated_'] ?? ' ';?>";
        var lg_are_you_sure_de3="<?=$language['lg_are_you_sure_de3'] ?? ' ';?>";
        var lg_product_deleted="<?=$language['lg_product_deleted'] ?? ' ';?>";
        var lg_select_unit="<?=$language['lg_select_unit'] ?? ' ';?>";
        var lg_please_enter_pr="<?=$language['lg_please_enter_pr'] ?? ' ';?>";
        var lg_please_upload_p="<?=$language['lg_please_upload_p'] ?? ' ';?>";
        var lg_please_enter_th1="<?=$language['lg_please_enter_th1'] ?? ' ';?>";
        var lg_please_enter_th="<?=$language['lg_please_enter_th'] ?? ' ';?>";
        var lg_please_enter_de1="<?=$language['lg_please_enter_de1'] ?? ' ';?>";
        var lg_please_enter_se1="<?=$language['lg_please_enter_se1'] ?? ' ';?>";
        var lg_please_enter_pr1="<?=$language['lg_please_enter_pr1'] ?? ' ';?>";
        var lg_please_select_u="<?=$language['lg_please_select_u'] ?? ' ';?>";
        var lg_please_enter_un="<?=$language['lg_please_enter_un'] ?? ' ';?>";
        var lg_please_select_p2="<?=$language['lg_please_select_p2'] ?? ' ';?>";
        var lg_please_select_p1="<?=$language['lg_please_select_p1'] ?? ' ';?>";
        var lg_order_failed="<?=$language['lg_order_failed'] ?? ' ';?>";
        var lg_slot="<?=$language['lg_slot'] ?? ' ';?>";
        var lg_complete="<?=$language['lg_complete'] ?? ' ';?>";
        var lg_please_enter_va5="<?=$language['lg_please_enter_va5'] ?? ' '; ?>";
        var lg_no_clinic_found="<?=$language['lg_no_clinic_found'] ?? ' ';?>";
        var lg_no_clinic_found="<?=$language['lg_no_clinic_found'] ?? ' '??'';?>";
        var lg_first_name_shou_max="<?=$language['lg_first_name_shou_max'] ?? ' ';?>";
        var lg_last_name_shoul_max="<?=$language['lg_last_name_shoul_max'] ?? ' ';?>";
        var lg_validate_text_spaces_only="<?=$language['lg_validate_text_spaces_only'] ?? ' ';?>";
        var lg_password_max_length="<?=$language['lg_password_max_length'] ?? ' ';?>";
        var lg_password_max_length_20="<?=$language['lg_password_max_length_20'] ?? ' ';?>";
        var lg_confirm_password_max_length="<?=$language['lg_confirm_password_max_length'] ?? ' ';?>";
        var lg_confirm_password_max_length_20="<?=$language['lg_confirm_password_max_length_20'] ?? ' ';?>";
        var lg_confirm_new_password="<?=$language['lg_confirm_new_password'] ?? ' ';?>";
        var lg_edit_details="<?=$language['lg_edit_details'] ?? ' ';?>";
        var lg_add_account_details="<?=$language['lg_add_account_details'] ?? ' ';?>";

        // patient personal information form validation
        var lg_pers_info_name_req="<?=$language['lg_pers_info_name_req'] ?? ' ';?>";
        var lg_pers_info_name_min="<?=$language['lg_pers_info_name_min'] ?? ' ';?>";
        var lg_pers_info_name_max="<?=$language['lg_pers_info_name_max'] ?? ' ';?>";
        var lg_pers_info_email_req="<?=$language['lg_pers_info_email_req'] ?? ' ';?>";
        var lg_pers_info_email_val="<?=$language['lg_pers_info_email_val'] ?? ' ';?>";
        var lg_pers_info_mobile_req="<?=$language['lg_pers_info_mobile_req'] ?? ' ';?>";
        var lg_pers_info_mobile_min="<?=$language['lg_pers_info_mobile_min'] ?? ' ';?>";
        var lg_pers_info_mobile_max="<?=$language['lg_pers_info_mobile_max'] ?? ' ';?>";
        var lg_pers_info_mobile_val="<?=$language['lg_pers_info_mobile_val'] ?? ' ';?>";
        var lg_pers_info_address_req="<?=$language['lg_pers_info_address_req'] ?? ' ';?>";
        var lg_pers_info_address_val="<?=$language['lg_pers_info_address_val'] ?? ' ';?>";
        var lg_pers_info_country_req="<?=$language['lg_pers_info_country_req'] ?? ' ';?>";
        var lg_pers_info_state_req="<?=$language['lg_pers_info_state_req'] ?? ' ';?>";
        var lg_pers_info_city_req="<?=$language['lg_pers_info_city_req'] ?? ' ';?>";
        var lg_pers_info_postalcode_req="<?=$language['lg_pers_info_postalcode_req'] ?? ' ';?>";
        var lg_pers_info_postalcode_min="<?=$language['lg_pers_info_postalcode_min'] ?? ' ';?>";
        var lg_pers_info_postalcode_max="<?=$language['lg_pers_info_postalcode_max'] ?? ' ';?>";
        var lg_pers_info_postalcode_val="<?=$language['lg_pers_info_postalcode_val'] ?? ' ';?>";
        // patient personal information form validation

        // appointments rating and review validation
        var lg_ratings_validation_title_max="<?=$language['lg_ratings_validation_title_max'] ?? ' ';?>";
        var lg_ratings_validation_review_max="<?=$language['lg_ratings_validation_review_max'] ?? ' ';?>";
        var lg_ratings_validation_title_val="<?=$language['lg_ratings_validation_title_val'] ?? ' ';?>";
        var lg_ratings_validation_review_val="<?=$language['lg_ratings_validation_review_val'] ?? ' ';?>";
        // appointments rating and review validation

        var lg_accept_chars_val="<?=$language['lg_accept_chars_val'] ?? ' ';?>";
        var lg_form_lab_test_testname_req="<?=$language['lg_form_lab_test_testname_req'] ?? ' ';?>";
        var lg_form_lab_test_testname_max="<?=$language['lg_form_lab_test_testname_max'] ?? ' ';?>";
        var lg_form_lab_test_amount_req="<?=$language['lg_form_lab_test_amount_req'] ?? ' ';?>";
        var lg_form_lab_test_amount_max="<?=$language['lg_form_lab_test_amount_max'] ?? ' ';?>";
        var lg_form_lab_test_duration_req="<?=$language['lg_form_lab_test_duration_req'] ?? ' ';?>";
        var lg_form_lab_test_duration_max="<?=$language['lg_form_lab_test_duration_max'] ?? ' ';?>";
        var lg_form_lab_test_description_req="<?=$language['lg_form_lab_test_description_req'] ?? ' ';?>";
        var lg_form_lab_test_description_max="<?=$language['lg_form_lab_test_description_max'] ?? ' ';?>";
        var lg_form_payment_request_requestamount_req="<?=$language['lg_form_payment_request_requestamount_req'] ?? ' ';?>";
        var lg_form_payment_request_requestamount_max="<?=$language['lg_form_payment_request_requestamount_max'] ?? ' ';?>";
        var lg_form_payment_request_description_req="<?=$language['lg_form_payment_request_description_req'] ?? ' ';?>";
        var lg_form_payment_request_description_max="<?=$language['lg_form_payment_request_description_max'] ?? ' ';?>";
    </script>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="/assets/js/jquery.password-validation.js" type="text/javascript"></script>
    <script src="/assets/css/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/user/auth.js"></script>
    <script src="/assets/js/toastr.js"></script>
    <script src="/script.js"></script>
</body>
</html>