$(document).ready(function () {

    // $('.textnumbers').on('keypress', function (event) {
    //     var x = event.which || event.keyCode;
    //     if((x >= 65 && x <= 90) || (x >=97 && x <= 122)  || (x >= 48 && x <= 57) ){
    //         return true;
    //     }
    //     else{
    //         return false;
    //     }
    // });
    $('[name="first_name"], [name="last_name"], .addressfield,.namefield,.numericOnly,.mobileNoOnly').on("cut copy paste", function (e) {
        e.preventDefault();
    });

    if (modules == "doctor" || pages == "profile") {
        if (modules != "clinic") {
            var maxDate = $('#maxDate').val();
            $('#dob').datepicker({
                startView: 2,
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: maxDate
            });

            $('.years').datepicker({
                startView: 2,
                minViewMode: 2,
                format: 'yyyy',
                endDate: maxDate,
                autoclose: true,
                startDate: '-60y', //2021 -1950
                endDate: '-0y' //2021-2011


            });
        }

        if (pages == "profile") {
            $.ajax({
                type: "GET",
                url: base_url + "ajax/get-specialization",
                data: { id: $(this).val() },
                beforeSend: function () {
                    $('#specialization').find("option:eq(0)").html(lg_please_wait);
                },
                success: function (data) {
                    /*get response as json */
                    $('#specialization').find("option:eq(0)").html(lg_select_speciali1);
                    var obj = jQuery.parseJSON(data);
                    $(obj).each(function () {
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);
                        $('#specialization').append(option);
                    });

                    $('#specialization').val(specialization).change();
                    // alert(specialization);
                    /*ends */

                    $('#specialization').on('change', function () {
                        if ($(this).val() === 'others') {
                            $('#other_specialization').show(); // Show text input
                        } else {
                            $('#other_specialization').hide(); // Hide if not 'others'
                        }
                    });

                }
            });
        }
    }

    if (pages == "profile") {

        // Dropzone.autoDiscover = false;
        // $(".dropzone").dropzone({
        // addRemoveLinks: true,
        // removedfile: function(file) {
        // var name = file.name; 

        // $.ajax({
        //     type: 'POST',
        //     url: 'upload.php',
        //     data: {name: name,request: 2},
        //     sucess: function(data){
        //         console.log('success: ' + data);
        //     }
        // });
        // var _ref;
        //     return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        // }
        // });


        $('input[name="price_type"]').on('click', function () {
            if ($(this).val() == 'Free') {
                $('#custom_price_cont').hide();
                $('#amount').val('');
            }
            if ($(this).val() == 'Custom Price') {
                $('#custom_price_cont').show();
                $('#amount').val('');
            }
            else {
            }
        });

        $.ajax({
            type: "GET",
            url: base_url + "ajax/get-country-code",
            data: { id: $(this).val() },
            beforeSend: function () {
                $('#country_code').find("option:eq(0)").html(lg_please_wait);
            },
            success: function (data) {
                $('#country_code').find("option:eq(0)").html(lg_select_country_);
                var obj = jQuery.parseJSON(data);
                $(obj).each(function () {
                    // var option = $('<option />');
                    // option.attr('value', this.value).text(this.label);
                    var option = "<option value=" + this.value + " data-id=" + this.countryid + " >" + this.label + "</option>";
                    $('#country_code').append(option);
                });
                // $('#country_code').val(country_code);
                $('#country_code option[data-id="' + $("#country_id").val() + '"]').prop('selected', true);
            }
        });

        $('#country_code').change(function () {
            $(this).valid();
            $("#country_id").val($("#country_code option:selected").attr('data-id'));
        });

        $.ajax({
            type: "GET",
            url: base_url + "ajax/get-country",
            beforeSend: function () {
                $('#country').find("option:eq(0)").html(lg_please_wait);
            },
            success: function (data) {
                $('#country').find("option:eq(0)").html(lg_select_country);
                var obj = jQuery.parseJSON(data);
                $(obj).each(function () {
                    var option = $('<option />');
                    option.attr('value', this.value).text(this.label);
                    $('#country').append(option);
                });
                $('#country').val(country);
            }
        });


        $.ajax({
            type: "POST",
            url: base_url + "ajax/get-state",
            data: { id: country },
            beforeSend: function () {
                $("#state option:gt(0)").remove();
                $("#city option:gt(0)").remove();
                $('#state').find("option:eq(0)").html(lg_please_wait);

            },
            success: function (data) {
                $('#state').find("option:eq(0)").html(lg_select_state);
                var obj = jQuery.parseJSON(data);
                $(obj).each(function () {
                    var option = $('<option />');
                    option.attr('value', this.value).text(this.label);
                    $('#state').append(option);
                });
                $('#state').val(state);
            }
        });

        $.ajax({
            type: "POST",
            url: base_url + "ajax/get-city",
            data: { id: state },
            beforeSend: function () {
                $("#city option:gt(0)").remove();
                $('#city').find("option:eq(0)").html(lg_please_wait);
            },
            success: function (data) {
                $('#city').find("option:eq(0)").html(lg_select_city);
                var obj = jQuery.parseJSON(data);
                $(obj).each(function () {
                    var option = $('<option />');
                    option.attr('value', this.value).text(this.label);
                    $('#city').append(option);
                });
                $('#city').val(city);
            }
        });



        $('#country').change(function () {
            $.ajax({
                type: "POST",
                url: base_url + "ajax/get-state",
                data: { id: $(this).val() },
                beforeSend: function () {
                    $("#state option:gt(0)").remove();
                    $("#city option:gt(0)").remove();
                    $('#state').find("option:eq(0)").html(lg_please_wait);

                },
                success: function (data) {
                    $('#state').find("option:eq(0)").html(lg_select_state);
                    var obj = jQuery.parseJSON(data);
                    $(obj).each(function () {
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);
                        $('#state').append(option);
                    });
                }
            });
        });

        $('#state').change(function () {
            $.ajax({
                type: "POST",
                url: base_url + "ajax/get-city",
                data: { id: $(this).val() },
                beforeSend: function () {
                    $("#city option:gt(0)").remove();
                    $('#city').find("option:eq(0)").html(lg_please_wait);
                },
                success: function (data) {
                    $('#city').find("option:eq(0)").html(lg_select_city);
                    var obj = jQuery.parseJSON(data);
                    $(obj).each(function () {
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);
                        $('#city').append(option);
                    });
                }
            });
        });


        $("#doctor_profile_form").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                mobileno: {
                    required: true,
                    minlength: 7,
                    maxlength: 12,
                    digits: true,
                    remote: {
                        url: base_url + "ajax/check-mobile-no",
                        type: "post",
                        data: {
                            mobileno: function () {
                                return $("#mobileno").val();
                            },
                            id: function () {
                                return $("#user_id").val();
                            },
                            checkall: "false"
                        }
                    }
                },
                gender: "required",
                dob: "required",
                // clinic_address: {required : true, maxlength:100 },
                // clinic_address2: {maxlength:100 },
                // clinic_city :  { required: true, SpecCharValidate: true, maxlength: 50 },
                // clinic_state :  { required: true, SpecCharValidate: true, maxlength: 50 },
                // clinic_country: { required:true , SpecCharValidate: true, maxlength: 50},
                // clinic_postal: {  digits: true },
                address1: { required: true, maxlength: 100 },
                address2: { maxlength: 100 },
                // address2: "required",
                country: "required",
                state: "required",
                city: "required",
                postal_code: {
                    required: true,
                    minlength: 4,
                    maxlength: 7,
                    digits: true,
                },
                price_type: "required",
                amount: {
                    required: function (element) {
                        if ($("input[name='price_type']:checked").val() === "Custom Price") {
                            return true;
                        } else {
                            return false;
                        }
                    },
                    number: true,
                    min: 1,
                    maxlength: 6
                },
                services: { required: true, SpecCharValidate: true },
                specialization: { required: true },
                // "hospital_name[]" : {SpecCharValidate: true, maxlength: 50} ,
                // "designation[]" : {SpecCharValidate: true, maxlength: 50} ,
                // "awards[]" : {SpecCharValidate: true, maxlength: 50} ,
                // "memberships[]" : {SpecCharValidate: true, maxlength: 50} ,
                // "registrations[]": {SpecCharValidate: true, maxlength: 50} ,
            },
            messages: {
                first_name: lg_please_enter_yo,
                last_name: lg_please_enter_yo1,
                mobileno: {
                    required: lg_please_enter_mo,
                    maxlength: lg_please_enter_va,
                    minlength: lg_please_enter_va,
                    digits: lg_please_enter_va,
                    remote: lg_your_mobile_no_
                },
                gender: lg_please_select_g,
                dob: lg_please_enter_yo2,
                address1: { required: lg_please_enter_yo3, maxlength: "Maximum length should be 100 characters" },
                address2: { maxlength: "Maximum length should be 100 characters" },
                clinic_address: { required: lg_please_enter_yo3, maxlength: "Maximum length should be 100 characters" },
                clinic_address2: { maxlength: "Maximum length should be 100 characters" },
                clinic_city: { required: "Please enter Clinic city", SpecCharValidate: "No Special Characters/Numbers Allowed", maxlength: "Maximum length should be 50 characters" },
                clinic_state: { required: "Please enter Clinic State", SpecCharValidate: "No Special Characters/Numbers Allowed", maxlength: "Maximum length should be 50 characters" },
                clinic_country: {
                    required: lg_please_select_c, SpecCharValidate: "No Special Characters/Numbers Allowed",
                    maxlength: "Maximum length should be 50 characters"
                },
                clinic_postal: { digits: lg_please_enter_va2 },
                // address2: lg_please_enter_yo4,
                country: lg_please_select_c,

                state: lg_please_select_s,
                city: lg_please_select_c1,
                postal_code: {
                    required: lg_please_enter_po,
                    maxlength: lg_please_enter_va2,
                    minlength: lg_please_enter_va2,
                    digits: lg_please_enter_va2
                },
                price_type: lg_please_select_p,
                amount: {
                    required: lg_please_enter_am,
                    digits: lg_please_enter_va3,
                    min: lg_please_enter_va3,
                    maxlength: "Max length should be 6 digits only"
                },
                services: { required: lg_please_enter_se, SpecCharValidate: "No Special Characters/Numbers Allowed" },
                // specialization: lg_please_select_s1,

                "hospital_name[]": { SpecCharValidate: "No Special Characters/Numbers Allowed", maxlength: "Maximum length should be 50 characters" },
                "designation[]": { SpecCharValidate: "No Special Characters/Numbers Allowed", maxlength: "Maximum length should be 50 characters" },
                "awards[]": { SpecCharValidate: "No Special Characters/Numbers Allowed", maxlength: "Maximum length should be 50 characters" },
                "memberships[]": { SpecCharValidate: "No Special Characters/Numbers Allowed", maxlength: "Maximum length should be 50 characters" },
                "registrations[]": { SpecCharValidate: "No Special Characters/Numbers Allowed", maxlength: "Maximum length should be 50 characters" },

            },
            submitHandler: function (form) {

                var inputerr = 0;
                var checkerr = 0;
                var checkerr1 = 0;
                if ($("#role_id").val() == 1 && $.trim($(".inputtagcls").val()) == "") {
                    $('html, body').animate({
                        scrollTop: ($(".bootstrap-tagsinput").offset().top - 100)
                    }, 1000);
                    toastr.error("Please Add Service Type");
                    $(".err_service").text('Enter Service');
                    inputerr++;
                    // return false;
                }
                else {
                    $(".err_service").text('');
                }
                $(".inputcls").map(function () {
                    if ($.trim($(this).val()) == '') {
                        $(this).attr("style", "border-color:red");
                        // $('.select2-selection').attr("style", "border-color:red");
                        inputerr++;
                    }
                    else {
                        $(this).removeAttr("style");
                        $('.select2-selection').removeAttr("style");
                    }
                });
                if (inputerr > 0) {
                    toastr.error('Please Fill Education Details');
                    return false;
                }
                else {
                    $("input[name='services[]']").each(function () {
                        var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
                        var services = $("input[name='services[]']").val();
                        if (!characterReg.test(services)) {
                            checkerr++;
                        }
                        if (services.length > 30) {
                            checkerr1++;
                        }
                    });
                    if (checkerr > 0) {
                        toastr.error("No Special Character or Numbers allowed In Service");
                        return false;
                    }
                    if (checkerr1 > 0) {
                        toastr.error("Service length should be within 60");
                        return false;
                    }

                    // For degree Name Special char and number validation start
                    $("input[name='degree[]']").each(function () {
                        var characterReg = /^\s*[a-zA-Z,.\s]+\s*$/;
                        var degree = $("input[name='degree[]']").val();
                        if (!characterReg.test(degree)) { checkerr++; }
                        if (degree.length > 30) { checkerr1++; }
                    });
                    if (checkerr > 0) {
                        toastr.error("No Special Character or Numbers allowed In Degree");
                        return false;
                    }
                    if (checkerr1 > 0) {
                        toastr.error("Degree length should be within 60");
                        return false;
                    }
                    // For Degree name Special char and number validation end

                    // For Institue name Special char and number validation
                    $("input[name='institute[]']").each(function () {
                        var characterReg1 = /^\s*[a-zA-Z,.\s]+\s*$/;
                        var institute = $("input[name='institute[]']").val();
                        if (!characterReg1.test(institute)) {
                            checkerr++;
                        }
                        if (institute.length > 100) {
                            checkerr1++;
                        }
                    });
                    if (checkerr > 0) {
                        toastr.error("No Special Character or Numbers allowed In Institute");
                        return false;
                    }
                    if (checkerr1 > 0) {
                        toastr.error("Institute character length should be within 100 ");
                        return false;
                    }
                }
                /*Fields from Ajax Validation*/

                $.ajax({
                    url: base_url + 'clinic/update-profile',
                    data: $("#doctor_profile_form").serialize(),
                    type: "POST",
                    beforeSend: function () {
                        $('#save_btn').attr('disabled', true);
                        $('#save_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#save_btn').attr('disabled', false);
                        $('#save_btn').html(lg_save_changes);

                        var obj = JSON.parse(res);

                        if (obj.status === 200) {
                            toastr.success(obj.msg);
                            setTimeout(function () {
                                window.location.href = base_url + modules;
                            }, 5000);

                        } else {
                            toastr.error(obj.msg);
                        }
                    }
                });
                return false;
            }
        });
    }


    if (pages == 'doctorList') {
        $.ajax({
            type: "GET",
            url: base_url + "ajax/get-country-code",
            data: { id: $(this).val() },
            beforeSend: function () {
                $('#country_code').find("option:eq(0)").html(lg_please_wait);
            },
            success: function (data) {
                $('#country_code').find("option:eq(0)").html(lg_select_country_);
                var obj = jQuery.parseJSON(data);
                $(obj).each(function () {
                    // var option = $('<option />');
                    // option.attr('value', this.value).text(this.label);
                    var option = "<option value=" + this.value + " data-id=" + this.countryid + " >" + this.label + "</option>";
                    $('#country_code').append(option);
                });
                // $('#country_code').val(country_code);
                $('#country_code option[data-id="' + $("#country_id").val() + '"]').prop('selected', true);
            }
        });

        $('#country_code').change(function () {
            $(this).valid();
            $("#country_id").val($("#country_code option:selected").attr('data-id'));
        });

        var appoinment_table;
        appoinment_table = $('#doctor_table').DataTable({
            'ordering': true,
            "processing": false,
            'bnDestroy': true,
            "serverSide": true,
            "order": [],
            "language": {
                "infoFiltered": ""
            },
            "ajax": {
                "url": base_url + 'clinic/list-doctor',
                "type": "POST",
                "data": function (data) {
                    data.type = $('#type').val();
                },
                error: function () {
                }
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                },
            ],

        });
        function appoinments_table(type) {
            $('#type').val(type);
            appoinment_table.ajax.reload(null, false);
        }
        appoinments_table(1);




        $("#register_form").validate({
            errorPlacement: function (error, element) {
                if (element.hasClass('select')) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element); 
                }
            },
            rules: {
                first_name: "required",
                last_name: "required",
                country_code: "required",
                mobileno: {
                    required: true,
                    minlength: 7,
                    maxlength: 12,
                    digits: true,
                    remote: {
                        url: base_url + "ajax/check-mobile-no",
                        type: "post",
                        data: {
                            mobileno: function () {
                                return $("#mobileno").val();
                            },
                            id: function () {
                                return $("#user_id").val();
                            },
                            checkall: "false"
                        }
                    }

                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: base_url + "ajax/check-email",
                        type: "post",
                        data: {
                            email: function () {
                                return $("#email").val();
                            },
                            user_id: function () {
                                return $("#user_id").val();
                            }
                        }
                    }

                },
                password: {
                    required: true,
                    minlength: 8,
                    password_req: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                }

            },
            messages: {
                first_name: "Please enter first name",
                last_name: "Please enter last name",
                country_code: "Please select country code",
                mobileno: {
                    required: "Please enter mobile number",
                    maxlength: "Please enter valid mobileno",
                    minlength: "Please enter valid mobileno",
                    digits: "Please enter valid mobileno",
                    remote: "Your mobile no already exits"
                },
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email address",
                    remote: "Your email address already exist"
                },
                password: {
                    required: "Please enter password",
                    minlength: "Your password must be 8 characters"
                },
                confirm_password: {
                    required: "Please enter confirm password",
                    equalTo: "Your password does not match"
                }

            },
            submitHandler: function (form) {
                $.ajax({
                    url: base_url + 'clinic/add-doctor',
                    data: $("#register_form").serialize(),
                    type: "POST",
                    beforeSend: function () {
                        $('#register_btn').attr('disabled', true);
                        $('#register_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#register_btn').attr('disabled', false);
                        $('#register_btn').html('Submit');
                        var obj = JSON.parse(res);

                        if (obj.status === 200) {
                            toastr.success(obj.msg);
                            $('#user_modal').modal('hide');
                            $('#register_form')[0].reset();
                            appoinments_table();
                        }
                        else {
                            toastr.error(obj.msg);
                        }
                    }
                });
                return false;
            }
        });

    }



});



if (pages == "doctorList") {
    var validator = $("#user_modal").validate();

    function add_doct() {
        validator.resetForm();
        $('#user_id').val('');
        $('#country_code').val('').trigger('change');
        $('#register_form')[0].reset(); // reset form on modals
        $('#user_modal').modal('show'); // show bootstrap modal
        $('#user_modal .modal-title').text('Add Doctor'); // Set Title to Bootstrap modal title
        $('.pass').show();
    }

    function edit_doctor(pre_id) {
        $('#register_form')[0].reset();
        validator.resetForm();
        $('input').css('color', '#000000');
        $('#user_modal').modal('show');
        $('.pass').hide();
        $('#user_modal .modal-title').text('Edit Doctor');
        $.post(base_url + 'clinic/get-doctor', { doc_id: pre_id }, function (res) {
            var obj = jQuery.parseJSON(res);
            $('#first_name').val(obj.first_name);
            $('#last_name').val(obj.last_name);
            $('#email').val(obj.email);
            $('#country_code').val(obj.country_code).select2();
            $('#mobileno').val(obj.mobile);
            $('#user_id').val(pre_id);
            // $('#email').attr("readonly",true);

        });

    }
    function delete_doctor(id) {
        $('#delete_id').val(id);
        $('#delete_table').val('users');
        $('#delete_title').text(lang_doctor);
        $('#delete_modal').modal('show');
    }


    function delete_details() {
        var id = $('#delete_id').val();
        var delete_table = $('#delete_table').val();
        $('#delete_btn').attr('disabled', true);
        $('#delete_btn').html('<div class="spinner-border text-light" role="status"></div>');
        $.post(base_url + 'ajax/delete-user', { id: id, delete_table, delete_table }, function (res) {

            if (delete_table == 'users') {
                window.location.reload();
            }
            $('#delete_btn').attr('disabled', false);
            $('#delete_btn').html(lg_yes);
            $('#delete_modal').modal('hide');


        });
    }
}

/**
 * Clinic Or Doctor Appointment List
 */

if (pages == 'appoinments') {

    setInterval(function () {
        // my_appoinments(0);
        var loadval = $('#page_no_hidden').val();
        my_appoinments(loadval);

    }, 2000);

    function my_appoinments(load_more) {

        if (load_more == 0) {
            $('#page_no_hidden').val(1);
        }

        var page = $('#page_no_hidden').val();


        //$('#search-error').html('');

        $.ajax({
            url: base_url + 'doctor-appointment-list',
            type: 'POST',
            data: { page: page },
            beforeSend: function () {
                // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
            },
            success: function (response) {
                //$('#doctor-list').html('');
                if (response) {
                    var obj = $.parseJSON(response);


                    if (obj.current_page_no == 1) {
                        $("#appointment-list").html(obj.data);
                    } else {
                        // $("#appointment-list").append(obj.data);
                        $("#appointment-list").html(obj.data);
                    }


                    if (obj.count == 0) {
                        $('#load_more_btn').addClass('d-none');
                        $('#no_more').removeClass('d-none');
                        return false;
                    }


                    if (obj.current_page_no == 1 && obj.count < 8) {
                        $('page_no_hidden').val(1);
                        $('#load_more_btn').addClass('d-none');
                        $('#no_more').removeClass('d-none');
                        return false;
                    }



                    if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
                        $('#load_more_btn').removeClass('d-none');
                        $('#no_more').addClass('d-none');
                    } else {
                        $('#load_more_btn').addClass('d-none');
                        $('#no_more').removeClass('d-none');
                    }




                }
            }

        });
    }

    $('#load_more_btn').click(function () {
        var page_no = $('#page_no_hidden').val();
        var current_page_no = 0;

        if (page_no == 1) {
            current_page_no = 2;
        } else {
            current_page_no = Number(page_no) + 1;
        }
        $('#page_no_hidden').val(current_page_no);
        my_appoinments(1);
    });


    function show_appoinments_modal(app_date, book_date, amount, type, id) {

        $('.app_date').html(app_date);
        $('.book_date').html(book_date);
        $('.amount').html(amount);
        $('.type').html(type);
        $('#app_id').val(id);
        $('#assign_doc').html("");

        $.ajax({
            type: "GET",
            url: base_url + "ajax/get-clinic-doctors",

            success: function (data) {
                /*get response as json */
                var obj = jQuery.parseJSON(data);
                console.log(obj.length);
                if (obj.length == 0) {
                    $('#assign_doc').css('display', 'none');
                    $('#assign_doc_err').html('No doctors are found for this clinic...!');
                    $('.add_doc_btn1').css('display', 'block');
                }
                else {
                    $('#assign_doc').css('display', 'block');
                    $('.add_doc_btn1').css('display', 'none');
                    var option = $('<option />');
                    // option.attr('value', '0').text('Select Doctor');
                    $('#assign_doc').append(option);
                    $(obj).each(function () {
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);
                        $('#assign_doc').append(option);
                    });
                    $('#assign_doc').val(doc_id);
                    /*ends */
                }

            }
        });

        $('#appoinments_details').modal('show');

    }

    function assign_doctor(id, date, sdate, edate) {
        $('#app_id_assign').val(id);
        $('#assign_doc').html("");

        $('#doctors_id_assign_date').val(date);
        // alert(id+" -- "+date+" -- "+sdate+" -- "+edate);
        $.ajax({
            type: "GET",
            url: base_url + "ajax/get-clinic-doctors",
            success: function (data) {
                /*get response as json */
                var obj = jQuery.parseJSON(data);
                console.log(obj.length);
                if (obj.length == 0) {
                    $('#assign_doc').css('display', 'none');
                    $('#assign_doc_err').html('No doctors are found for this clinic...!');
                    $('.add_doc_btn1').css('display', 'block');
                }
                else {

                    var option = $('<option />');
                    option.attr('value', '0').text('Select Doctor');
                    $('#assign_doc').append(option);
                    var doctorAval = false;
                    if (doctorAval == false) {
                        $('#assign_doc').css('display', 'none');
                        $('#assign_doc_err').html('No doctors are available for this slot...!');
                        $('.add_doc_btn1').css('display', 'block');
                    }
                    $(obj).each(function () {
                        var values = this.value;
                        var labels = this.label;
                        $.post(base_url + 'ajax/get-clinic-schedule-from-date', { schedule_date: date, doctor_id: values, start_date: sdate, end_date: edate }, function (response) {
                            if (response != 0) {
                                // if (response.length > 1) {
                                option = $('<option />');
                                option.attr('value', values).text(labels);
                                $('#assign_doc').append(option);
                                doctorAval = true;
                                $('#assign_doc').css('display', 'block');
                                $('.add_doc_btn1').css('display', 'none'); $('#assign_doc_err').html('');
                            }
                            // console.log(values+' '+doctorAval);
                        });
                    });
                    // $('#assign_doc').val(doc_id);
                    /*ends */
                }

            }
        });
        $('#assign_doctor').modal('show');
    }

    function assign_doc() {

        $.ajax({
            type: "POST",
            data: { 'id': $("#assign_doc option:selected").val(), 'app_id': $('#app_id_assign').val() },
            url: base_url + "ajax/clinic-assign-doctor",
            success: function (data) {
                /*get response as json */
                var obj = jQuery.parseJSON(data);
                if (obj.status == 200) {
                    location.reload();
                }
                else {

                }
                /*ends */
            }
        });

    }

    function conversation_status(id, status) {
        if (status == 1) {
            $('.app-modal-title').html(lg_accept);
            $('#app-modal-title').html(lg_accept);
            $('#appoinments_status').val('1');
            $('#appoinments_id').val(id);
        }

        if (status == 0) {
            $('.app-modal-title').html(lg_cancel);
            $('#app-modal-title').html(lg_cancel);
            $('#appoinments_status').val('0');
            $('#appoinments_id').val(id);
        }

        $('#appoinments_status_modal').modal('show');

    }
    // function change_status() {
    //     var appoinments_id = $('#appoinments_id').val();
    //     var appoinments_status = $('#appoinments_status').val();
    //     $('#change_btn').attr('disabled', true);
    //     $('#change_btn').html('<div class="spinner-border text-light" role="status"></div>');
    //     $.post(base_url + 'appoinments/change_status', { appoinments_id: appoinments_id, appoinments_status, appoinments_status }, function (res) {

    //         my_appoinments(0);

    //         $('#change_btn').attr('disabled', false);
    //         $('#change_btn').html(lg_yes);
    //         $('#appoinments_status_modal').modal('hide');


    //     });
    // }

}



if (modules == 'doctor' || modules == 'clinic' || modules == 'patient') {

    if (pages == 'doctor_dashboard' || pages == 'patient_dashboard') {
        /*function email_verification()
        {
            $.get(base_url + 'dashboard/send_verification_email', function (data) {
                toastr.success(lg_activation_mail);
            });
        }*/
    }
    if (pages == 'doctor_dashboard') {

        function appoinments_table_func() {
            var appoinment_table;

            appoinment_table = $('#appoinments_table').DataTable({
                'ordering': true,
                "processing": false, //Feature control the processing indicator.
                'bnDestroy': true,
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                /* "language": {                
                 "infoFiltered": ""
             },*/
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": base_url + 'dashboard/appointment-list',
                    "type": "POST",
                    "data": function (data) {
                        data.type = $('#type').val();
                    },
                    error: function () {

                    }
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        "targets": [0], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],

            });
        }
        // appoinments_table_func();


        function appoinments_table(type) {
            $('#type').val(type);
            // appoinment_table.ajax.reload(null, false); //reload datatable ajax
            // appoinment_table.destroy();

            if ($.fn.DataTable.isDataTable('#appoinments_table')) {
                $('#appoinments_table').DataTable().destroy();
            }
            $('#appoinments_table tbody').empty();

            appoinments_table_func();
        }

        appoinments_table(1);

        function show_appoinments_modal(app_date, book_date, amount, type) {
            $('.app_date').html(app_date);
            $('.book_date').html(book_date);
            $('.amount').html(amount);
            $('.type').html(type);
            $('#appoinments_details').modal('show');

        }

        function conversation_status(id, status) {
            if (status == 1) {
                $('.app-modal-title').html(lg_accept);
                $('#app-modal-title').html(lg_accept);
                $('#appoinments_status').val('1');
                $('#appoinments_id').val(id);
            }

            if (status == 0) {
                $('.app-modal-title').html(lg_cancel);
                $('#app-modal-title').html(lg_cancel);
                $('#appoinments_status').val('0');
                $('#appoinments_id').val(id);
            }

            $('#appoinments_status_modal').modal('show');

        }

        // function change_status() {
        //     var appoinments_id = $('#appoinments_id').val();
        //     var appoinments_status = $('#appoinments_status').val();
        //     $('#change_btn').attr('disabled', true);
        //     $('#change_btn').html('<div class="spinner-border text-light" role="status"></div>');
        //     $.post(base_url + 'appoinments/change_status', { appoinments_id: appoinments_id, appoinments_status, appoinments_status }, function (res) {

        //         my_appoinments(0);

        //         $('#change_btn').attr('disabled', false);
        //         $('#change_btn').html(lg_yes);
        //         $('#appoinments_status_modal').modal('hide');


        //     });
        // }

    }

    if (pages == 'my_patients') {
        my_patient(0);
        function my_patient(load_more) {

            if (load_more == 0) {
                $('#page_no_hidden').val(1);
            }

            var page = $('#page_no_hidden').val();


            //$('#search-error').html('');
            var patient_blood_group = '',
                patient_gender = '',
                patient_age = '',
                patient_mobileno = '',
                patient_countryname = '',
                patient_cityname = '',
                patient_user_id = '';
            $.ajax({
                url: base_url + 'my_patients/patient_list',
                type: 'POST',
                data: { page: page },
                beforeSend: function () {
                    // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
                },
                success: function (response) {
                    //$('#doctor-list').html('');
                    if (response) {
                        var obj = $.parseJSON(response);
                        console.log(response);
                        if (obj.data.length >= 1) {
                            var html = '';
                            $(obj.data).each(function () {

                                if (this.blood_group != null) {
                                    patient_blood_group = this.blood_group;
                                } else {
                                    patient_blood_group = "";
                                }
                                if (this.gender != null) {
                                    patient_gender = this.gender;
                                } else {
                                    patient_gender = "";
                                }
                                if (this.age != null) {
                                    patient_age = this.age;
                                } else {
                                    patient_age = "";
                                }
                                if (this.mobileno != null) {
                                    patient_mobileno = this.mobileno;
                                } else {
                                    patient_mobileno = "";
                                }
                                if (this.countryname != null) {
                                    patient_countryname = this.countryname;
                                } else {
                                    patient_countryname = "";
                                }
                                if (this.cityname != null) {
                                    patient_cityname = this.cityname;
                                } else {
                                    patient_cityname = "";
                                }
                                if (this.user_id != null) {
                                    patient_user_id = this.user_id;
                                } else {
                                    patient_user_id = "";
                                }
                                html += '<div class="col-md-6 col-lg-4 col-xl-3">' +
                                    '<div class="card widget-profile pat-widget-profile">' +
                                    '<div class="card-body">' +
                                    '<div class="pro-widget-content">' +
                                    '<div class="profile-info-widget">' +
                                    '<a href="' + base_url + 'my_patients/mypatient-preview/' + this.userid + '" class="booking-doc-img">' +
                                    '<img src="' + this.profileimage + '" alt="User Image">' +
                                    '</a>' +
                                    '<div class="profile-det-info">' +
                                    '<h3><a href="' + base_url + 'my_patients/mypatient-preview/' + this.userid + '">' + this.first_name + ' ' + this.last_name + '</a></h3>' +
                                    '<div class="patient-details">' +
                                    '<h5><b>' + lg_patient_id + ' :</b> #PT00' + patient_user_id + '</h5>' +
                                    '<h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> ' + patient_cityname + ', ' + patient_countryname + '</h5>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="patient-info">' +
                                    '<ul>' +
                                    '<li>' + lg_phone + ' <span>' + patient_mobileno + '</span></li>' +
                                    '<li>' + lg_age + ' <span>' + patient_age + ', ' + patient_gender + '</span></li>' +
                                    '<li>' + lg_blood_group + ' <span>' + patient_blood_group + '</span></li>' +
                                    '</ul>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                            });

                            if (obj.current_page_no == 1) {
                                $("#patients-list").html(html);
                            } else {
                                $("#patients-list").append(html);
                            }



                            if (obj.count == 0) {
                                $('#load_more_btn').addClass('d-none');
                                $('#no_more').removeClass('d-none');
                                return false;
                            }


                            if (obj.current_page_no == 1 && obj.count < 8) {
                                $('page_no_hidden').val(1);
                                $('#load_more_btn').addClass('d-none');
                                $('#no_more').removeClass('d-none');
                                return false;
                            }



                            if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
                                $('#load_more_btn').removeClass('d-none');
                                $('#no_more').addClass('d-none');
                            } else {
                                $('#load_more_btn').addClass('d-none');
                                $('#no_more').removeClass('d-none');
                            }

                        } else {
                            var html = '<div class="appointment-list">' +
                                '<div class="profile-info-widget">' +
                                '<p>' + lg_no_patients_fou + '</p>' +
                                '</div>' +
                                '</div>';
                            $("#patients-list").html(html);
                        }

                    }
                }

            });
        }



        $('#load_more_btn').click(function () {
            var page_no = $('#page_no_hidden').val();
            var current_page_no = 0;

            if (page_no == 1) {
                current_page_no = 2;
            } else {
                current_page_no = Number(page_no) + 1;
            }
            $('#page_no_hidden').val(current_page_no);
            my_patient(1);
        });
    }

    if (pages == 'mypatient_preview' || pages == 'patient_Dashboard') {
        var appoinment_table;
        appoinment_table = $('#appoinment_table').DataTable({
            'ordering': true,
            "processing": false, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'ajax/appointment-list',
                "type": "POST",
                "data": function (data) {
                    data.patient_id = $('#patient_id').val();
                },
                error: function () {

                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });

        $(document).on('change', '.appointment_status', function () {
            var id = $(this).attr('id');
            var status = $(this).val();
            $.post(base_url + "my_patients/change_appointment_status", { id: id, status: status }, function (data) {
                toastr.success(lg_status_updated_);
                appoinment_table.ajax.reload(null, false);
            });
        });


        function appoinments_table() {
            appoinment_table.ajax.reload(null, false);
        }

        var prescription_table;
        prescription_table = $('#prescription_table').DataTable({
            'ordering': true,
            "processing": false, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'ajax/prescriptions_list',
                "type": "POST",
                "data": function (data) {
                    data.patient_id = $('#patient_id').val();
                },
                error: function () {

                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });


        function prescriptions_table() {
            prescription_table.ajax.reload(null, false);
        }

        function view_prescription(pre_id) {
            $('.overlay').show();
            $.post(base_url + 'my_patients/get_prescription_details', { pre_id: pre_id }, function (res) {
                var obj = jQuery.parseJSON(res);
                var table = '<table class="table table-bordered table-hover">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>' + lg_sno + '</th>' +
                    '<th>' + lg_drug_name + '</th>' +
                    '<th>' + lg_quantity + '</th>' +
                    '<th>' + lg_type + '</th>' +
                    '<th>' + lg_days + '</th>' +
                    '<th>' + lg_time + '</th>' +
                    '</tr>' +
                    '<tbody>';
                var i = 1;
                $(obj).each(function () {
                    var j = i++;

                    table += '<tr>' +
                        '<td>' + j + '</td>' +
                        '<td>' + this.drug_name + '</td>' +
                        '<td>' + this.qty + '</td>' +
                        '<td>' + this.type + '</td>' +
                        '<td>' + this.days + '</td>' +
                        '<td>' + this.time + '</td>' +
                        '</tr>';
                });
                table += '</tbody>' +
                    '</table>' +
                    '<div class="float-right">' +
                    '<img src="' + base_url + obj[0].img + '" style="width:150px"><br>' +
                    '( ' + lg_dr + ' ' + obj[0].doctor_name.charAt(0).toUpperCase() + obj[0].doctor_name.slice(1) + ' ) <br>' +
                    '<div>' + lg_doctor_signatur + '</div><br>' +
                    '</div>';
                $('#patient_name').text(obj[0].patient_name);
                $('#view_date').text(obj[0].prescription_date);
                $('.view_title').text(lg_prescription);
                $('.view_details').html(table);
                $('#view_modal').modal('show');
                $('.overlay').hide();
            });

        }

        function delete_prescription(id) {
            $('#delete_id').val(id);
            $('#delete_table').val('prescription');
            $('#delete_title').text(lg_prescription);
            $('#delete_modal').modal('show');
        }


        var billing_table;
        billing_table = $('#billing_table').DataTable({
            'ordering': true,
            "processing": false, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'ajax/billing_list',
                "type": "POST",
                "data": function (data) {
                    data.patient_id = $('#patient_id').val();
                },
                error: function () {

                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0, 2, 4], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });


        function billings_table() {
            billing_table.ajax.reload(null, false);
        }

        function view_billing(id) {
            $('.overlay').show();
            $.post(base_url + 'my_patients/get_billing_details', { id: id }, function (res) {
                var obj = jQuery.parseJSON(res);
                var table = '<table class="table table-bordered table-hover">' +
                    '<thead>' +
                    '<tr>' +
                    '<th>' + lg_sno + '</th>' +
                    '<th>' + lg_description + '</th>' +
                    '<th>' + lg_amount + '</th>' +
                    '</tr>' +
                    '<tbody>';
                var i = 1;
                $(obj).each(function () {
                    var j = i++;
                    table += '<tr>' +
                        '<td>' + j + '</td>' +
                        '<td>' + this.name + '</td>' +
                        '<td>' + this.amount + '</td>' +
                        '</tr>';
                });
                table += '</tbody>' +
                    '</table>' +
                    '<div class="float-right">' +
                    '<img src="' + base_url + obj[0].img + '" style="width:150px"><br>' +
                    '( ' + lg_dr + ' ' + obj[0].doctor_name.charAt(0).toUpperCase() + obj[0].doctor_name.slice(1) + ' ) <br>' +
                    '<div>' + lg_doctor_signatur + '</div><br>' +
                    '</div>';
                $('#patient_name').text(obj[0].patient_name);
                $('#view_date').text(obj[0].billing_date);
                $('.view_title').text(lg_doctor_billing);
                $('.view_details').html(table);
                $('#view_modal').modal('show');
            });

        }



        function delete_billing(id) {
            $('#delete_id').val(id);
            $('#delete_table').val('billing');
            $('#delete_title').text(lg_bill4);
            $('#delete_modal').modal('show');
        }


        function view_dec(id) {
            // alert(id);return false;
            $.ajax({
                type: "POST",
                url: base_url + 'my_patients/view_dec',
                data: { id: id },
                beforeSend: function () {

                },
                success: function (res) {
                    $('#med_desc').html(res);
                    $('#show_desc_medical_records').modal('show');
                }
            });


        }




        $('#medical_records_form').submit(function (e) {
            e.preventDefault();
            var oFile = document.getElementById("user_files_mr").files[0];
            var medical_record_id = $("#medical_record_id").val();
            //alert(medical_record_id);
            if (!document.getElementById("user_files_mr").files[0] && medical_record_id == '') {
                toastr.warning(lg_please_upload_m);
                return false;
            }
            if (oFile && medical_record_id == '') {

                if (oFile.size > 25097152) { // 25 mb for bytes.

                    toastr.warning(lg_file_size_must_);
                    return false;
                }
                const fileType = oFile['type'];
                //alert(fileType);
                const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/jpg', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf'];
                if (!validImageTypes.includes(fileType)) {
                    toastr.error('Please upload images/word/excel/pdf files only');
                    return false;
                }
            }

            var formData = new FormData($('#medical_records_form')[0]);
            $.ajax({
                url: base_url + 'my_patients/upload_medical_records',
                type: 'POST',
                data: formData,
                beforeSend: function () {
                    if (oFile) {
                        $('#medical_btn').attr('disabled', true);
                        $('#medical_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    }
                },
                success: function (res) {
                    $('#medical_btn').attr('disabled', false);
                    $('#medical_btn').html(lg_submit);
                    $('#add_medical_records').modal('hide');
                    var obj = jQuery.parseJSON(res);
                    if (obj.status === 500) {
                        toastr.warning(obj.msg);
                        $('#user_files_mr').val('');

                    } else {
                        $('#medical_records_form')[0].reset();
                        toastr.success(obj.msg);
                        medical_records_table();
                    }

                },
                error: function (data) {
                    //alert('error');

                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false
        });

        var medical_record_table;
        medical_record_table = $('#medical_records_table').DataTable({
            'ordering': true,
            "processing": false, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'ajax/medical_records_list',
                "type": "POST",
                "data": function (data) {
                    data.patient_id = $('#patient_id').val();
                },
                error: function () {

                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });


        function medical_records_table() {
            medical_record_table.ajax.reload(null, false);
        }

        function delete_medical_records(id) {
            $('#delete_id').val(id);
            $('#delete_table').val('medical_records');
            $('#delete_title').text(lg_medical_records);
            $('#delete_modal').modal('show');
        }



        function delete_details() {
            var id = $('#delete_id').val();
            var delete_table = $('#delete_table').val();
            $('#delete_btn').attr('disabled', true);
            $('#delete_btn').html('<div class="spinner-border text-light" role="status"></div>');
            $.post(base_url + 'ajax/delete-user', { id: id, delete_table, delete_table }, function (res) {

                // if (delete_table == 'prescription')
                //     ;
                // {

                // 	prescriptions_table();
                // }

                // if (delete_table == 'medical_records')
                //     ;
                // {
                //     medical_records_table();
                // }

                // if (delete_table == 'billing')
                //     ;
                // {
                //     billings_table();
                // }

                $('#delete_btn').attr('disabled', false);
                $('#delete_btn').html(lg_yes);
                $('#delete_modal').modal('hide');
                if (delete_table == 'prescription') {
                    prescriptions_table();
                    toastr.error("Prescription deleted successfully");
                }
                if (delete_table == 'medical_records') { toastr.error("medical_records deleted successfully"); }
                if (delete_table == 'billing') {
                    billings_table();
                    toastr.error("Billing detail deleted successfully");
                }
            });
        }

        function edit_medi_rec(id) {
            $.ajax({
                url: base_url + 'ajax/get_medical_records',
                type: 'POST',
                data: { 'medical_rec_id': id },
                success: function (res) {
                    var obj = $.parseJSON(res);
                    $(obj.data).each(function () {
                        $("#patient_id").val(this.patient_id);
                        $("#description").html(this.description);
                        $("#medical_record_id").val(this.id);
                        $("#show_med_rec_url").attr('href', base_url + this.file_name);
                        $("#show_med_rec_url").css('display', 'block');
                    });

                },
                error: function (data) {
                    alert('error');
                }

            });

            $('#add_medical_records').modal('show');


        }
        $(".add-new-btn").click(function () {
            $('#medical_records_form').trigger("reset");
        });

    }

    if (pages == 'add_prescription' || pages == 'edit_prescription' || pages == 'add_billing' || pages == 'edit_billing') {

        var wrapper = document.getElementById("signature-pad"),
            clearButton = wrapper.querySelector("[data-action=clear]"),
            saveButton = wrapper.querySelector("[data-action=save]"),
            canvas = wrapper.querySelector("canvas"),
            signaturePad;


        function resizeCanvas() {

            var ratio = window.devicePixelRatio || 1;
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        signaturePad = new SignaturePad(canvas);
        clearButton.addEventListener("click", function (event) {
            signaturePad.clear();
        });

        saveButton.addEventListener("click", function (event) {

            if (signaturePad.isEmpty()) {
                console.log('You should sign!')
            } else {
                $.ajax({
                    type: "POST",
                    url: base_url + 'my_patients/insert_signature',
                    data: { 'image': signaturePad.toDataURL(), 'rowno': $('#rowno').val() },
                    beforeSend: function () {
                        $('#save2').attr('disabled', true);
                        $('#save2').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#save2').attr('disabled', false);
                        $('#save2').html(lg_save);
                        signaturePad.clear();
                        $('#sign-modal').modal('hide');
                        $('.doctor_signature').html('');
                        $('.doctor_signature').html(res);
                        $('.doctor_signature').removeClass('doctor_signature');

                    }
                });
            }
        });

        $('.doctor_signature').click(function () {
            show_modal();
        });

        function show_modal() {
            $('#sign-modal').modal('show');
            const context = canvas.getContext('2d');
            context.clearRect(0, 0, 460, 318);
            $('#edit').addClass('doctor_signature');
        }
        $('.clear_sign').click(function () {
            $("#edit").find("img").remove();
            $("#signature_id").val(0);
            $('#edit').append('Click here to Sign');

            //alert('haii');
        });


        function delete_row(id) {

            $('#delete_' + id).remove();
            toastr.error(lg_delete_rw);
            //console.log(row_count);
        }

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }

    }

    if (pages == 'add_prescription' || pages == 'edit_prescription') {
        //     $('[name="drug_name[]"]').keyup(function() {
        //     this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        // });
        $(document).on('keypress', '.textnumbers', function (event) {
            var inputValue = event.key;
            var validInput = /^[0-9a-zA-Z - ]+$/i.test(inputValue);

            if (!validInput) {
                event.preventDefault();
            }
        });
        function add_more_row() {
            var hidden_count = $('#hidden_count').val();
            var total = Number(hidden_count) + 1;
            $('#hidden_count').val(total);

            var append_rows = '<tr id="delete_' + total + '">' +
                '<td><input type="text" name="drug_name[]" id="drug_name' + total + '" class="form-control filter-form inputcls textnumbers"></td>' +
                '<td style="min-width: 100px; max-width: 100px;"><input type="text" onkeypress="return isNumberKey(event)" name="qty[]" id="qty' + total + '" class="form-control filter-form text inputcls" maxlength="4"></td>' +
                '<td><select class="form-control filter-form inputcls" name="type[]" id="type' + total + '">' +
                '<option value="">' + lg_select_type + '</option>' +
                '<option value="Before Food">' + lg_before_food + '</option>' +
                '<option value="After Food">' + lg_after_food + '</option>' +
                '</select>' +
                '</td>' +
                '<td style="min-width: 100px; max-width: 100px;"><input onkeypress="return isNumberKey(event)" type="text" name="days[]" id="days' + total + '" class="form-control filter-form text inputcls" maxlength="4" autocomplete="off"></td>' +
                '<td class="checkbozcls">' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<input type="checkbox" name="time[' + total + '][]" value="Morning" id="morning' + total + '"><label for="morning' + total + '">&nbsp;&nbsp;' + lg_morning + '</label>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<input type="checkbox" name="time[' + total + '][]" value="Afternoon" id="afternoon' + total + '"><label for="afternoon' + total + '">&nbsp;&nbsp;' + lg_afternoon + '</label>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<input type="checkbox" name="time[' + total + '][]" value="Evening" id="evening' + total + '"><label for="evening' + total + '">&nbsp;&nbsp;' + lg_evening + '</label>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<input type="checkbox" name="time[' + total + '][]" value="Night" id="night' + total + '"><label for="night' + total + '">&nbsp;&nbsp;' + lg_night + '</label>' +
                '</div>' +
                '</div>' +
                '<input type="hidden" value="' + total + '" name="rowValue[]">' +
                '</td>' +
                '<td>' +
                '<a href="javascript:void(0)" class="btn bg-danger-light trash" onclick="delete_row(' + total + ')"><i class="far fa-trash-alt"></i></a>' +
                '</td>' +
                '</tr>';
            $('#add_more_rows').append(append_rows);
        }



        $(document).ready(function () {
            $("#add_prescription").validate({
                rules: {
                    // "drug_name[]": "required",
                    // "qty[]": "required",
                    // "type[]": "required",
                    // "days[]": "required",
                    // "time[]": "required"
                },
                messages: {
                    // "drug_name[]": lg_please_enter_dr,
                    // "qty[]": lg_please_enter_qt,
                    // "type[]": lg_please_select_t1,
                    // "days[]": lg_please_enter_da,
                    // "time[]": lg_please_select_t2
                },
                submitHandler: function (form) {

                    /*Fields from Ajax Validation*/
                    var inputerr = 0; var checkerr = 0;//alert(inputerr);
                    var checkerr1 = 0;
                    $(".inputcls").map(function () {
                        //alert($.trim($(this).attr('name')));						
                        if ($.trim($(this).val()) == '') {
                            $(this).attr("style", "border-color:red");
                            $('.select2-selection').attr("style", "border-color:red");
                            inputerr++;
                        }

                        else {
                            $(this).removeAttr("style");
                            $('.select2-selection').removeAttr("style");
                        }
                    });
                    if (inputerr > 0) {
                        toastr.error(lg_please_enter_va5);
                        return false;
                    } else {
                        $("input[name='rowValue[]']").each(function () {
                            var checkedNum = $("input[name='time[" + this.value + "][]']:checked").length;
                            if (checkedNum == 0) { checkerr++; }
                        });
                        if (checkerr > 0) {
                            toastr.error(lg_please_select_t2);
                            return false;
                        }
                        // For Drug name Special char and number validation start
                        $("input[name='drug_name[]']").each(function () {
                            var characterReg = /^\s*[a-zA-Z0-9,\s]+\s*$/;
                            var drug_name = $("input[name='drug_name[]']").val();
                            if (!characterReg.test(drug_name)) { checkerr++; }
                            if (drug_name.length > 30) { checkerr1++; }
                        });
                        if (checkerr > 0) {
                            toastr.error("No Special Character or Numbers allowed");
                            return false;
                        }
                        if (checkerr1 > 0) {
                            toastr.error("Drug name' Character length should be within 30");
                            return false;
                        }
                        // For Drug name Special char and number validation end

                        // For Drug name Special char and number validation
                        $("input[name='qty[]']").each(function () {
                            var characterReg1 = /^\s*[0-9,\s]+\s*$/;
                            var qty = $("input[name='qty[]']").val();
                            if (!characterReg1.test(qty)) { checkerr++; }
                            if (qty.length > 30) { checkerr1++; }
                        });
                        if (checkerr > 0) {
                            toastr.error("Numbers Only allowed");
                            return false;
                        }
                        if (checkerr1 > 0) {
                            toastr.error("Qty length should be within 5");
                            return false;
                        }

                    }

                    /*Fields from Ajax Validation*/

                    var signature_id = $('#signature_id').val();
                    if (signature_id == 0) { /* Signature validation */
                        toastr.error(lg_please_sign_to_);
                        return false;
                    }

                    var appointment_id = $('#appointment_id').val();

                    if (appointment_id == '' || appointment_id == null) { /* Signature validation */
                        toastr.error("please choose appointment");
                        return false;
                    }

                    var diagnosis = $('#diagnosis_id').val();

                    $.ajax({
                        url: base_url + 'my_patients/save_prescription',
                        data: $("#add_prescription").serialize() + `&appointment_id=${appointment_id}&diagnosis=${diagnosis}`,
                        type: "POST",
                        beforeSend: function () {
                            $('#prescription_save_btn').attr('disabled', true);
                            $('#prescription_save_btn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                        success: function (res) {
                            $('#prescription_save_btn').attr('disabled', false);
                            $('#prescription_save_btn').html(lg_save);

                            var obj = JSON.parse(res);

                            if (obj.status === 200) {
                                toastr.success(obj.msg);
                                setTimeout(function () {
                                    window.location.href = base_url + 'my_patients/mypatient-preview/' + obj.patient_id;
                                }, 3000);

                            }else if(obj.status === 300){
                                toastr.error(obj.msg);
                            } else {
                                toastr.error(obj.msg);
                            }
                        }
                    });
                    return false;
                }
            });

            $("#update_prescription").validate({
                rules: {
                    "drug_name[]": "required",
                    "qty[]": "required",
                    "type[]": "required",
                    "days[]": "required",
                    "time[]": "required"
                },
                messages: {
                    "drug_name[]": lg_please_enter_dr,
                    "qty[]": lg_please_enter_qt,
                    "type[]": lg_please_select_t1,
                    "days[]": lg_please_enter_da,
                    "time[]": lg_please_select_t2
                },
                submitHandler: function (form) {
                    var signature_id = $('#signature_id').val();
                    if (signature_id == 0) { /* Signature validation */
                        toastr.error(lg_please_sign_to_);
                        return false;
                    }

                    /*Fields from Ajax Validation*/
                    var inputerr = 0; var checkerr = 0;
                    $(".inputcls").map(function () {
                        if ($.trim($(this).val()) == '') {
                            $(this).attr("style", "border-color:red");
                            inputerr++;
                        } else {
                            $(this).removeAttr("style");
                        }
                    });
                    if (inputerr > 0) {
                        toastr.error(lg_please_enter_va5);
                        return false;
                    } else {
                        $("input[name='rowValue[]']").each(function () {
                            var checkedNum = $("input[name='time[" + this.value + "][]']:checked").length;
                            if (checkedNum == 0) { checkerr++; }
                        });
                        if (checkerr > 0) {
                            toastr.error(lg_please_select_t2);
                            return false;
                        }
                    }
                    /*Fields from Ajax Validation*/

                    $.ajax({
                        url: base_url + 'my_patients/update_prescription',
                        data: $("#update_prescription").serialize(),
                        type: "POST",
                        beforeSend: function () {
                            $('#prescription_update_btn').attr('disabled', true);
                            $('#prescription_update_btn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                        success: function (res) {
                            $('#prescription_update_btn').attr('disabled', false);
                            $('#prescription_update_btn').html(lg_save);

                            var obj = JSON.parse(res);

                            if (obj.status === 200) {
                                toastr.success(obj.msg);
                                setTimeout(function () {
                                    window.location.href = base_url + 'my_patients/mypatient-preview/' + obj.patient_id;
                                }, 2000);

                            } else {
                                toastr.error(obj.msg);
                            }
                        }
                    });
                    return false;
                }
            });



        });



    }

    if (pages == 'add_billing' || pages == 'edit_billing') {

        function add_more_row() {
            var hidden_count = $('#hidden_count').val();
            var total = Number(hidden_count) + 1;
            $('#hidden_count').val(total);
            var append_rows = '<tr id="delete_' + total + '">' +
                '<td>' +
                '<input type="text" name="name[]" id="name' + total + '" class="form-control filter-form inputcls" >' +
                '<td>' +
                '<input type="decimal" name="amount[]" onkeypress="return isNumberKey(event)" id="amount' + total + '" class="form-control filter-form inputcls" >' +
                '</td>' +
                '<td><a href="javascript:void(0)" class="btn bg-danger-light trash" onclick="delete_row(' + total + ')"><i class="far fa-trash-alt"></i></a></td>' +
                '</tr>';

            $('#add_more_rows').append(append_rows);

        }

        $(document).ready(function () {
            $("#add_billing").validate({
                rules: {
                    /*"name[]": { required: true, 
                                SpecCharValidate: true, 
                                maxlength:25 },
                    "amount[]": { required: true},*/
                },
                messages: {
                    /*"name[]": { required : lg_please_enter_na, 
                                SpecCharValidate: "No Special Chars/Numbers allowed ", 
                                 maxlength: "Maximum length should be within 25 characters"
                               }, 
                    "amount[]": {required: lg_please_enter_am } */
                },
                submitHandler: function (form) {

                    /*Fields from Ajax Validation*/
                    var inputerr = 0; var checkerr = 0;//alert(inputerr);
                    var checkerr1 = 0;
                    $(".inputcls").map(function () {
                        //alert($.trim($(this).attr('name')));						
                        if ($.trim($(this).val()) == '') {
                            $(this).attr("style", "border-color:red");
                            $('.select2-selection').attr("style", "border-color:red");
                            inputerr++;
                        }

                        else {
                            $(this).removeAttr("style");
                            $('.select2-selection').removeAttr("style");
                        }
                    });
                    if (inputerr > 0) {
                        toastr.error(lg_please_enter_va5);
                        return false;
                    } else {
                        // For Name Special char and number validation start
                        $("input[name='name[]']").each(function () {
                            var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
                            var name = $("input[name='name[]']").val();
                            if (!characterReg.test(name)) { checkerr++; }
                            if (name.length > 30) { checkerr1++; }
                        });
                        if (checkerr > 0) {
                            toastr.error("No Special Character or Numbers allowed");
                            return false;
                        }
                        if (checkerr1 > 0) {
                            toastr.error("Name Character length should be within 30");
                            return false;
                        }
                        // For Drug name Special char and number validation end

                        // For Drug name Special char and number validation
                        $("input[name='amount[]']").each(function () {
                            //var characterReg1 = /^\s*[0-9,\s]+\s*$/;								 
                            var amt = $("input[name='amount[]']").val();
                            //if (!characterReg1.test(amt)) {checkerr++;}
                            if (amt.length > 30) { checkerr1++; }
                        });
                        /*if (checkerr>0) {
                            toastr.error("Numbers Only allowed");	
                            return false;
                        }*/
                        if (checkerr1 > 0) {
                            toastr.error("Amount should be within 5 digits");
                            return false;
                        }

                    }
                    /*Fields from Ajax Validation*/

                    var signature_id = $('#signature_id').val();
                    if (signature_id == 0) { /* Signature validation */
                        toastr.error(lg_please_sign_to_);
                        return false;
                    }
                    $.ajax({
                        url: base_url + 'my_patients/save_billing',
                        data: $("#add_billing").serialize(),
                        type: "POST",
                        beforeSend: function () {
                            $('#bill_save_btn').attr('disabled', true);
                            $('#bill_save_btn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                        success: function (res) {
                            $('#bill_save_btn').attr('disabled', false);
                            $('#bill_save_btn').html(lg_save);

                            var obj = JSON.parse(res);

                            if (obj.status === 200) {
                                toastr.success(obj.msg);
                                setTimeout(function () {
                                    window.location.href = base_url + 'my_patients/mypatient-preview/' + obj.patient_id;
                                }, 3000);

                            } else {
                                toastr.error(obj.msg);
                            }
                        }
                    });
                    return false;
                }
            });
            $.validator.addMethod(
                "SpecCharValidate",
                function (value, element) {
                    var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
                    if (!characterReg.test(value)) {
                        return false;
                    } else {
                        return true;
                    }
                },
                "No Special Chars or Numbers Allowed in the City Name"
            );

            $("#update_billing").validate({
                rules: {
                    "name[]": "required",
                    "amount[]": "required"
                },
                messages: {
                    "name[]": lg_please_enter_na,
                    "amount[]": lg_please_enter_am
                },
                submitHandler: function (form) {
                    var signature_id = $('#signature_id').val();
                    if (signature_id == 0) { /* Signature validation */
                        toastr.error(lg_please_sign_to_);
                        return false;
                    }
                    $.ajax({
                        url: base_url + 'my_patients/update_billing',
                        data: $("#update_billing").serialize(),
                        type: "POST",
                        beforeSend: function () {
                            $('#billing_update_btn').attr('disabled', true);
                            $('#billing_update_btn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                        success: function (res) {
                            $('#billing_update_btn').attr('disabled', false);
                            $('#billing_update_btn').html(lg_update);

                            var obj = JSON.parse(res);

                            if (obj.status === 200) {
                                toastr.success(obj.msg);
                                setTimeout(function () {
                                    window.location.href = base_url + 'my_patients/mypatient-preview/' + obj.patient_id;
                                }, 3000);

                            } else {
                                toastr.error(obj.msg);
                            }
                        }
                    });
                    return false;
                }
            });


        });
    }



}
