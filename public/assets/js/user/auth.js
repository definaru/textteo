function change_role(role)
{
    if (role == 1) // Doctor
    {
        $('#role_type').html(lang_doctor);
        $("#doc_btn").addClass("active");
        $("#pat_btn").removeClass("active");
        $("#hos_btn").removeClass("active");
        $("#lab_btn").removeClass("active");
        $("#pha_btn").removeClass("active");
        $("#cli_btn").removeClass("active");
        $("#mci_div").show();
        $("#gst_div").hide();
        $("#upload_div").show();
        
        $(".doctor_code").show();
        //$('#register_form')[0].reset();
        $(".other_code").hide();
        $("#first_name").css('display', 'block');
        $("#first_name").parent().find('.focus-label').css('display', 'block');
        $("#last_name").css('display', 'block');
        $("#last_name").parent().find('.focus-label').css('display', 'block');
    }
    if (role == 2)  // Patient
    {
        $('#role_type').html(lang_patient);
        $("#pat_btn").addClass("active");
        $("#doc_btn").removeClass("active");
        $("#hos_btn").removeClass("active");
        $("#lab_btn").removeClass("active");
        $("#pha_btn").removeClass("active");
        $("#cli_btn").removeClass("active");
        $("#mci_div").hide();
        $("#gst_div").hide();
        $("#upload_div").hide();
        
        $(".doctor_code").hide();
        // $('#register_form')[0].reset();
        $(".other_code").show();
        $("#first_name").css('display', 'block');
        $("#first_name").parent().find('.focus-label').css('display', 'block');
        $("#last_name").css('display', 'block');
        $("#last_name").parent().find('.focus-label').css('display', 'block');
    }
    if (role == 3)  //  Hospital/Clinic
    {
        $('#role_type').html("Hospital/Clinic");
        $("#hos_btn").addClass("active");
        $("#doc_btn").removeClass("active");
        $("#pat_btn").removeClass("active");
        $("#lab_btn").removeClass("active");
        $("#pha_btn").removeClass("active");
        $("#cli_btn").removeClass("active");
        $("#mci_div").hide();
        $("#gst_div").show();
        $("#upload_div").show();
        
        $(".doctor_code").hide();
        //$('#register_form')[0].reset();
        $(".other_code").show();
        $("#first_name").css('display', 'none');
        $("#first_name").parent().find('.focus-label').css('display', 'none');
        $("#last_name").css('display', 'none');
        $("#last_name").parent().find('.focus-label').css('display', 'none');
    }
    if (role == 4)  // Labs
    {
        $('#role_type').html("Lab");
        $("#lab_btn").addClass("active");
        $("#doc_btn").removeClass("active");
        $("#pat_btn").removeClass("active");
        $("#hos_btn").removeClass("active");
        $("#pha_btn").removeClass("active");
        $("#cli_btn").removeClass("active");
        $("#mci_div").hide();
        $("#gst_div").hide();
        $("#upload_div").hide();
        
        $(".doctor_code").hide();
        //$('#register_form')[0].reset();
        $(".other_code").show();
        $("#first_name").css('display', 'none');
        $("#first_name").parent().find('.focus-label').css('display', 'none');
        $("#last_name").css('display', 'none');
        $("#last_name").parent().find('.focus-label').css('display', 'none');
    }
    if (role == 5) // pharamacy
    {
        $('#role_type').html('Pharmacy');
        $("#doc_btn").removeClass("active");
        $("#pat_btn").removeClass("active");
        $("#hos_btn").removeClass("active");
        $("#lab_btn").removeClass("active");
        $("#pha_btn").addClass("active");
        $("#cli_btn").removeClass("active");
        $("#mci_div").hide();
        $("#gst_div").hide();
        $("#upload_div").hide();
        
        $(".doctor_code").hide();
        //$('#register_form')[0].reset();
        $(".other_code").hide();
        $("#first_name").css('display', 'none');
        $("#first_name").parent().find('.focus-label').css('display', 'none');
        $("#last_name").css('display', 'none');
        $("#last_name").parent().find('.focus-label').css('display', 'none');
    }
        if (role == 6) // clinic
    {
        $('#role_type').html('Clinic');
        $("#doc_btn").removeClass("active");
        $("#pat_btn").removeClass("active");
        $("#hos_btn").removeClass("active");
        $("#lab_btn").removeClass("active");
        $("#pha_btn").removeClass("active");
        $("#cli_btn").addClass("active");
        $("#mci_div").hide();
        $("#gst_div").hide();
        $("#upload_div").hide();
        
        $(".doctor_code").hide();
        //$('#register_form')[0].reset();
        $(".other_code").hide();
        $("#first_name").css('display', 'none');
        $("#first_name").parent().find('.focus-label').css('display', 'none');
        $("#last_name").css('display', 'none');
        $("#last_name").parent().find('.focus-label').css('display', 'none');
    }
    $('#role').val(role);
    //$('#register_form')[0].reset();
}
function resend_otp() {
    // alert('dfd');
    /*var mobileno = $('#mobileno').val();
    var country_code = $('#country_code').val();

    $.ajax({
        url: base_url + 'Signin/sendotp',
        data: {
            mobileno: mobileno, country_code: country_code, otpcount: '2'
        },
        //contentType: "application/json; charset=utf-8",
        dataType: "text",
        method: "post",
        beforeSend: function () {
            $('.otp_load').html('<div class="spinner-border text-light" role="status"></div>');
        },
        success: function (res) {

            $('.otp_load').html('<a class="forgot-link" onclick="resend_otp()"  href="javascript:void(0);" id="resendotp">'+lg_resend_otp+'</a>');
            var obj = JSON.parse(res);

            if (obj.status === 200)
            {
                $('.OTP').show();
                toastr.success(obj.msg);

            } else if (obj.status === 500)
            {
                toastr.error(obj.msg);

            } else
            {
                toastr.error(obj.msg);
            }
        }
    });*/

}

(function($) {
    "use strict";
    
		
    if (pages == 'register') {

        /*$.ajax({
            type: "GET",
            url: base_url + "ajax/get-country-code",
            data: {id: $(this).val()},
            beforeSend: function () {
                $('#country_code').find("option:eq(0)").html(lg_please_wait);
            },
            success: function (data) {
                $('#country_code').find("option:eq(0)").html(lg_select_country_);
                var obj = jQuery.parseJSON(data);
                $(obj).each(function ()
                {
                    // var option = $('<option />');
                    // option.attr('value', this.value).text(this.label);
                    // option.attr('data-id', this.countryid).text(this.label);
                    var option = "<option value="+this.value+" data-id="+this.countryid+" >"+this.label+"</option>";
                    $('#country_code').append(option);
                });

                $('#country_code').val(country_code);

            }
        });

        $('#country_code').change(function () {
            $(this).valid();
            $("#country_id").val($("#country_code option:selected").attr('data-id'));
        });*/

        $('.OTP').hide();
        $('#resendotp').hide();
        $("#sendotp").on('click', function () {

            var mobileno = $('#mobileno').val();
            var country_code = $('#country_code').val();
            if (mobileno == "")
            {
                toastr.error(lg_please_enter_va4);
            } else {
                $.ajax({
                    url: base_url + 'Signin/sendotp',
                    data: {
                        mobileno: mobileno, country_code: country_code, otpcount: '1'
                    },

                    dataType: "text",
                    method: "post",
                    beforeSend: function () {
                        $('.otp_load').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {

                        $('.otp_load').html('<a class="forgot-link" onclick="resend_otp()"  href="javascript:void(0);" id="resendotp">'+lg_resend_otp+'</a>');

                        var obj = JSON.parse(res);

                        if (obj.status === 200)
                        {

                            $('.OTP').show();
                            $('#resendotp').show();
                            toastr.success(obj.msg);

                        } else if (obj.status === 500)
                        {
                            toastr.error(obj.msg);
                        } else
                        {
                            toastr.error(obj.msg);
                        }
                    }
                });
            }
        });



        $("#register_form").validate({
            errorPlacement: function (error, element) {
                if (element.hasClass('select') || element.is(":checkbox")) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element); 
                }
            },
            rules: {
                first_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 150,
                    text_spaces_only: true
                },
                last_name: {
                    required: true,
                    maxlength: 150,
                    text_spaces_only: true
                },
                country_code: {
                    required: true,
                },
                mobileno: {
                    required: true,
                    minlength: 7,
                    maxlength: 12,
                    digits: true,
                    // remote: {
                    //     url: base_url + "ajax/check-mobile-no",
                    //     type: "post",
                    //     data: {
                    //         mobileno: function () {
                    //             return $("#mobileno").val();
                    //         }
                    //     }
                    // }
                },
                email: {
                    required: true,
                    email: true,
                    // remote: {
                    //     url: base_url + "ajax/check-email",
                    //     type: "post",
                    //     data: {
                    //         email: function () {
                    //             return $("#email").val();
                    //         }
                    //     }
                    // }
                },
                password: {
                    required: true,
                    // minlength: 6,
                    // maxlength: 100,
                    password_req: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password",
                    maxlength: 100,
                },
                agree_statement: { required: true }

            },
            messages: {
                first_name: {
                    required: lg_please_enter_yo,
                    minlength: lg_first_name_shou,
                    maxlength: lg_first_name_shou_max,
                },
                last_name: {
                    required: lg_please_enter_yo1,
                    maxlength: lg_last_name_shoul_max,
                },
                country_code: {
                    required: lg_select_country_,
                },
                mobileno: {
                    required: lg_please_enter_mo,
                    maxlength: lg_number_should_b1,
                    minlength: lg_number_should_b,
                    digits: lg_digits_are_only,
                    remote: lg_your_mobile_no_
                },
                email: {
                    required: lg_please_enter_em,
                    email: lg_please_enter_va1,
                    remote: lg_your_email_addr1
                },
                password: {
                    required: lg_please_enter_pa,
                    // minlength: lg_your_password_m,
                    // maxlength: lg_password_max_length,
                    password_req: "Minimum eight and maximum 20 characters, at least one uppercase letter, one lowercase letter, one number and one special character"
                },
                confirm_password: {
                    required: lg_please_enter_co,
                    equalTo: lg_your_password_d,
                    maxlength: lg_confirm_password_max_length
                },
                agree_statement: {
                    required : lg_please_accept_t
                }

            },
            submitHandler: function (form) {

                $.ajax({
                    type: "POST",
                    url: base_url + 'user-register',
                    data: $("#register_form").serialize(),
                    dataType:"json",
                    beforeSend: function () {
                        $('#register_btn').attr('disabled', true);
                        $('#register_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#register_btn').attr('disabled', false);
                        $('#register_btn').html(lg_signup);                            

                        if (res.status === 200)
                        {
                            $('#register_form')[0].reset();
                            //window.location.href = base_url + 'login';
							
							toastr.success('Thank you for refistration! Email with instructions sent to your mailbox');

                        } else
                        {
                            toastr.error(res.msg);
                        }
                    }
                });
                return false;
            }
        });
        
        
    }

    if (pages == 'index')
    {

        $("#signin_form").validate({
            rules: {
                email: {
                    required: true,
                    // email: true,
                },
                password: {
                    required: true,
                    minlength: 6
                },
            },
            messages: {
                email: {
                    required: lg_please_enter_em1
                },
                password: {
                    required: lg_please_enter_pa,
                    minlength: lg_your_password_m
                },

            },
            submitHandler: function (form) {
                $.ajax({
                    url: base_url + 'user-login',
                    data: $("#signin_form").serialize(),
                    type: "POST",
                    beforeSend: function () {
                        $('#signin_btn').attr('disabled', true);
                        $('#signin_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#signin_btn').attr('disabled', false);
                        $('#signin_btn').html(lg_signin);

                        var obj = JSON.parse(res);

                        if (obj.status === 200)
                        {
                            if(typeof obj.redirectUrl!='undefined' && obj.redirectUrl!='' && obj.redirectUrl!=null && obj.redirectUrl!="null"){
                               window.location.href = obj.redirectUrl;
                            }
                            else{
                              window.location.href = base_url + obj.page_user; 
                            }
                            //window.location.href = base_url + obj.page_user;
                        } else
                        {
                            toastr.error(obj.msg);
                        }
                    }
                });
                return false;
            }
        });
    }
    
    if (pages == 'forgot-password')
    {
        $("#reset_password").validate({
            rules: {

                resetemail: {
                    required: true,
                    email: true,
                    remote: {
                        url: base_url + "ajax/register-email",
                        type: "post",
                        data: {
                            email: function () {
                                return $("#resetemail").val();
                            }
                        }
                    }
                }
            },
            messages: {
                resetemail: {
                    required: lg_please_enter_em,
                    email: lg_please_enter_va1,
                    remote: lg_your_email_addr
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: base_url + 'forgot-password',
                    data: $("#reset_password").serialize(),
                    type: "POST",
                    beforeSend: function () {
                        $('#reset_pwd').attr('disabled', true);
                        $('#reset_pwd').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#reset_pwd').attr('disabled', false);
                        $('#reset_pwd').html(lg_reset_password);

                        var obj = JSON.parse(res);

                        if (obj.status === 200)
                        {
                            $('#reset_password')[0].reset();
                            toastr.success(obj.msg);
                            setTimeout(function () {
                                window.location.href = base_url;
                            }, 2000);

                        } else
                        {
                            toastr.error(obj.msg);
                        }
                    }
                });
                return false;
            }
        });
    }

    if (pages == 'change_password')
    {
        $(document).ready(function () {

            const togglenewpassword = document.querySelector('#togglenewpassword');
            const password = document.querySelector('#password');
    
            togglenewpassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type2 = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type2);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
            });
    
            const toggleconfirmpassword = document.querySelector('#toggleconfirmpassword');
            const confirm_password = document.querySelector('#confirm_password');
    
            toggleconfirmpassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type3 = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
            confirm_password.setAttribute('type', type3);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
            });

            // password_req from widget-setting.js file
            $("#change_password").validate({
                rules: {

                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 100,
                        password_req:true
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#password",
                        maxlength: 100,
                    },
                },
                messages: {
                    password: {
                        required: lg_please_enter_pa,
                        minlength: lg_your_password_m,
                        maxlength: lg_password_max_length
                    },
                    confirm_password: {
                        required: lg_please_enter_co,
                        equalTo: lg_your_password_d,
                        maxlength: lg_confirm_password_max_length
                    },

                },
                submitHandler: function (form) {
                    $.ajax({
                        url: base_url + 'reset-password',
                        data: $("#change_password").serialize(),
                        type: "POST",
                        beforeSend: function () {
                            $('#update_pwd').attr('disabled', true);
                            $('#update_pwd').html('<div class="spinner-border text-light" role="status"></div>');

                        },
                        success: function (res) {
                            $('#update_pwd').attr('disabled', false);
                            $('#update_pwd').html(lg_confirm3);
                            var obj = JSON.parse(res);

                            if (obj.status === 200)
                            {
                                $('#change_password')[0].reset();
                                toastr.success(obj.msg);
                                setTimeout(function () {
                                    window.location.href = base_url;
                                }, 2000);
                            } else
                            {
                                toastr.error(obj.msg);
                            }
                        }
                    });
                    return false;
                }
            });

        });

    }

})(jQuery);