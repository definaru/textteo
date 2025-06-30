
$(document).ready(function () {
    var tz = jstz.determine();
    var timezone = tz.name();
    $.post(base_url + 'ajax/set-timezone', { timezone: timezone }, function (res) {
        // console.log(res);
    })
    $.post(base_url + 'ajax/set-timezone', { timezone: timezone }, function (res) {
        // console.log(res);
    })

    $.post(base_url + 'ajax/currency-rate', function (res) {
        //console.log(res);
    })
    //Message module we need to show user status so updating timestam whem page reloading
    $.post(base_url + 'ajax/update-user-status', function (res) {
        //console.log(res);
    })
});

if (pages == 'searchDoctor'){

function openPopup(doctor_index) {
    var allSlots = $(`.slot-container-row .slots-grid-${doctor_index} .slot`);
    var popupContent = $('.popup-content .remaining_slots');

    // Create a grid for the remaining slots in the popup
    var popupSlots = $('<div class="popup-slots" style="margin-top: 15px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;"></div>');

    // Append remaining slots to the popup
    allSlots.slice(5).each(function() {
        var clone = $(this).clone().show();
        popupSlots.append(clone);
    });
    
    // Remove old slots if already exist
    popupContent.find('.popup-slots').remove();
    popupContent.append(popupSlots);

    // Show the popup
    $('#popup').fadeIn();
}

function isMobileView() {
    return window.matchMedia('(max-width: 767px)').matches;
}

function initSlots(doctor_indexes) {
    
    $(doctor_indexes).each(function(index, item){
        var $allSlots = $(`.slot-container-row .slots-grid-${item} .slot`);
        // Show only the first 2 slots initially
        if ($allSlots.length > 5) {
            $allSlots.slice(5).hide(); // Hide all slots except the first two
            $(`.see-more-${item}`).css('display', 'block');
        }

        // See more button click
        $(`.see-more-${item}`).on('click', function() {
            openPopup(item); // Show the popup with hidden slots
        });

        // Close popup
        $('.close-btn').on('click', function() {
            $('#popup').fadeOut(); // Close the popup when the close button is clicked
        });
    });
}


}


function clear_all(id = null) {
    $.ajax({
        type: "POST",
        data: { id: id },
        url: base_url + 'dashboard/notification_update',
        dataType: "json",
        success: function (response) {
            if (response.status == 200) {
                toastr.success("Notifications clear");
                setTimeout(function () { location.reload(true); }, 1000);
                location.reload(true);
            } else {
                toastr.error(response.msg);
            }
        }
    });
}


if (modules == 'home') {
    if (pages == 'index') {
        $("#search_button").click(function () {
            var link = base_url + 'search-veterinary?location=';
            var search_keywords = $.trim($('#search_keywords').val());
            var search_location = $.trim($('#search_location').val());
            if (search_keywords != '' && search_location != '') {
                window.location.href = link+ search_location + '&keywords=' + search_keywords;
            } else if (search_keywords != '') {
                window.location.href = link + search_keywords;
            } else if (search_location != '') {
                window.location.href = link+ search_location;
            } else {
                toastr.warning(lg_please_enter_ke);
            }
        });

        function search_locations() {

            $('.location_result').html('');
            var search_location = $.trim($('#search_location').val());
            if (search_location != '') {
                $.ajax({
                    type: "POST",
                    url: base_url + 'home/search_location',
                    data: 'search_location=' + search_location,
                    success: function (data) {
                        if (data.length) {
                            var obj = jQuery.parseJSON(data);
                            var html = '';
                            if (obj.location != null) {
                                $(obj.location).each(function () {
                                    html += `
                                    <div class="keyword-search">
                                        <a href="${base_url}search-veterinary?location=${this.location}">
                                            ${`${lg_location2} - ${this.location}`}
                                        </a>
                                    </div>`;
                                });
                            }
                            $('.location_result').html(html);
                        } else {
                            $('.location_result').html(`<b>${lg_no_city_found}</b>`);
                        }
                    }
                });
            } else {
                $('.location_result').html('');
            }
        }

        function search_keyword() {
            $('.keywords_result').html('');
            var search_keywords = $.trim($('#search_keywords').val());
            if (search_keywords != '') {
                $.ajax({
                    type: "POST",
                    url: base_url + 'search-keyword',
                    data: 'search_keywords=' + search_keywords,
                    success: function (data) {
                        if (data.length) {
                            var obj = jQuery.parseJSON(data);
                            var html = '';
                            if (obj.specialist != null) {
                                $(obj.specialist).each(function () {
                                    html += `
                                    <div class="keyword-search">
                                        <a href="${base_url}search-veterinary?keywords=${this.specialization}">
                                            ${`${lg_speciality} - ${this.specialization}`}
                                        </a>
                                    </div>`;
                                });
                            }
                            if (obj.doctor != null) {
                                $(obj.doctor).each(function () {
                                    html += `
                                    <div class="keyword-search">
                                        <a href="${base_url}search-veterinary?keywords=${this.first_name}">
                                            <div class="keyword-img">
                                                <img src="${this.profileimage}" class="img-responsive" />
                                            </div>
                                            ${`${lg_dr} ${this.first_name} ${this.last_name}`}
                                        </a>
                                        <small>${`${lg_specialist} - ${this.speciality}`}</small>
                                    </div>`;
                                });
                                $('.keywords_result').html(html);
                            } else {
                                var html = `<b>${lg_no_doctors_foun1}</b>`;
                            }
                            $('.keywords_result').html(html);
                        } else {
                            $('.keywords_result').html(`<b>${lg_no_doctors_foun1}</b>`);
                        }
                    }
                });
            } else {
                $('.keyword_result').html('');
            }
        }
    }
    if (pages == 'index' || pages == 'doctor_preview') {
        function add_favourities(doctor_id) {
            $.post(base_url + 'add-favourities', { doctor_id: doctor_id }, function (data) {
                var obj = JSON.parse(data);
                if (obj.status === 200) {
                    $('#favourities_' + doctor_id).addClass("fav-btns");
                    toastr.success(obj.msg);
                } else if (obj.status === 204) {
                    toastr.warning(obj.msg);
                } else if (obj.status === 201) {
                    $('#favourities_' + doctor_id).removeClass("fav-btns");
                    toastr.success(obj.msg);
                } else {
                    $('#favourities_' + doctor_id).removeClass("fav-btns");
                }
            });
        }
    }
}
$(document).ready(function () {
    if (
        (modules == 'home' || modules == 'patient' || modules == 'doctor' || modules == 'hospital' || modules == 'lab' || modules == 'pharmacy' || modules == 'ecommerce')
        &&
        (pages == 'searchDoctor' || pages == 'doctors_searchmap' || pages == 'patients_search' || pages == 'add_branch' || pages == 'labs_searchmap' || pages == 'pharmacy_profile' || pages == 'checkout' || pages == 'pharmacy_search_bydoctor' || pages == 'add_doctor')) {

        $(document).off('click', '.profile_image_popup_close').on('click', '.profile_image_popup_close', function () {
            $(".avatar-form")[0].reset();
        });
        if (!typeof (country_code)) {
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
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);
                        $('#country_code').append(option);
                    });
                    $('#country_code').val(country_code);
                }
            });
        }


        if (modules == 'ecommerce' || pages == 'checkout' || pages == 'searchDoctor') {
            $.ajax({
                type: "GET",
                url: base_url + "ajax/get-country",
                data: { id: $(this).val() },
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
                    //added new on 07 July 2024 by Muddasar
                    $('#country').val('229').trigger('change');
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
        }

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
    }

    if (modules == 'home' && pages == 'pharmacy_search_bydoctor') {
        $(document).on('click', '.pharmacy_profile_btn', function () {
            $("body").removeClass("modal-open");
            var pharmacy_id = $(this).data('pharmacy-id');
            $.ajax({
                //url:  base_url +'my_patients/get_phamacy_details',
                url: base_url + 'home/get_phamacy_details',
                type: 'POST',
                data: { pharmacy_id: pharmacy_id },
                success: function (response) {
                    // console.log(response);
                    var obj = JSON.parse(response);
                    //console.log(obj);
                    if (obj.status === 200) {
                        var html = '';
                        let view_pharmacy = $(".view_pharmacy_details").html(html);
                        let lg_pharmacy = `<p>${lg_pharmacy_detail}</p>`;                        
                        if (obj.data.length >= 1) {
                            $(obj.data).each(function () {
                                var pharmacy_name = (this.pharmacy_name != '' && this.pharmacy_name != null) ? this.pharmacy_name : '';
                                var first_name = (this.first_name != '' && this.first_name != null) ? this.first_name : '';
                                var last_name = (this.last_name != '' && this.last_name != null) ? this.last_name : '';
                                var profileimage = (this.profileimage != '' && this.profileimage != null) ? this.profileimage : '';
                                var phonecode = (this.phonecode != '' && this.phonecode != null) ? this.phonecode : '';
                                var mobileno = (this.mobileno != '' && this.mobileno != null) ? this.mobileno : '';
                                var address1 = (this.address1 != '' && this.address1 != null) ? this.address1 : '';
                                var address2 = (this.address2 != '' && this.address2 != null) ? this.address2 : '';
                                var city = (this.city != '' && this.city != null) ? this.city : '';
                                var statename = (this.statename != '' && this.statename != null) ? this.statename : '';
                                var country = (this.country != '' && this.country != null) ? this.country : '';
                                var pharmacy_opens_at = (this.pharamcy_opens_at != '' && this.pharamcy_opens_at != null) ? this.pharamcy_opens_at : '';
                                var home_delivery = (this.home_delivery != '' && this.home_delivery != null) ? this.home_delivery : '';
                                var hrsopen = (this.hrsopen != '' && this.hrsopen != null) ? this.hrsopen : '';
                                html += `
                                <div class="card-body">
                                    <center>
                                        <img 
                                            src="${base_url + profileimage}" 
                                            class="img-fluid" alt="${pharmacy_name}" 
                                            title="${pharmacy_name}" 
                                        />
                                    </center>
                                    <br />
                                    <table class="table table-bordered table-hover">
                                        <tr><td>Pharmacy name</td><td>${pharmacy_name}</td></tr>
                                        <tr><td>User name</td><td>${first_name} ${last_name}</td></tr>
                                        <tr><td>Mobile no</td><td>(${phonecode}) ${mobileno}</td></tr>
                                        <tr><td>Address 1</td><td>${address1}</td></tr>
                                        <tr><td>Address 2</td><td>${address2}</td></tr>
                                        <tr><td>City</td><td>${city}</td></tr>
                                        <tr><td>State name</td><td>${statename}</td></tr>
                                        <tr><td>Country</td><td>${country}</td></tr>
                                        <tr><td>Pharmacy opens at</td><td>${pharmacy_opens_at}</td></tr>
                                        <tr><td>Home delivery avalable</td><td>${home_delivery}</td></tr>
                                        <tr><td>24Hrs Open</td><td>${hrsopen}</td></tr>
                                    </table>
                                </div>`;
                            });
                            view_pharmacy;
                        } else {
                            var html = lg_pharmacy;view_pharmacy;
                        }
                    } else {
                        var html = lg_pharmacy;view_pharmacy;
                    }
                }
            });
        });
    }


    if ((modules == 'ecommerce' || pages == 'products_list_by_pharmacy')) {
        function search_subcategory(subcategory) {
            reset_products();
            $('#subcategory').val(subcategory);
            get_products(0);
        }

        function reset_products() {
            $('#category').val('');
            $('#keywords').val('');
            $('#subcategory').val('');
            get_products(0);
        }

        if (pages == 'index' || pages == 'products_list_by_pharmacy') {
            get_products(0);
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
            get_products(1);
        });

        function cart_lists() {
            $.get(base_url + 'cart-list', function (data) {
                $('#loading').hide();
                var obj = jQuery.parseJSON(data);
                $('.cart_lists').html(obj.cart_list);
                $('.checkout_cart_lists').html(obj.checkout_html);
                $('.checkout_cart_html').html(obj.checkout_cart_html);
                $('#cart_pay_btn').hide();
                if (obj.cart_count == 1) {
                    $('#cart_pay_btn').show();
                }
            });
        }
        cart_lists();
        function remove_cart(id) {
            $.ajax({
                url: base_url + 'home/remove_cart',
                type: "POST",
                data: { id: id },
                cache: false,
                success: function (data) {
                    cart_count();
                    cart_lists();
                }
            });
        }
        function increment_quantity(cart_id) {
            var inputQuantityElement = $("#input-quantity-" + cart_id);
            var newQuantity = parseInt($(inputQuantityElement).val()) + 1;
            save_to_db(cart_id, newQuantity);
        }
        function decrement_quantity(cart_id) {
            var inputQuantityElement = $("#input-quantity-" + cart_id);
            if ($(inputQuantityElement).val() > 1) {
                var newQuantity = parseInt($(inputQuantityElement).val()) - 1;
                save_to_db(cart_id, newQuantity);
            }
        }
        function save_to_db(cart_id, new_quantity) {
            var inputQuantityElement = $("#input-quantity-" + cart_id);
            $.ajax({
                url: base_url + 'home/update_cart',
                data: "cart_id=" + cart_id + "&new_quantity=" + new_quantity,
                type: 'post',
                success: function (response) {
                    cart_count();
                    cart_lists();
                }
            });
        }
        function cart_count() {
            $.get(base_url + 'home/cart_count', function (data) {
                var obj = jQuery.parseJSON(data);
                $('.cart_count').html(obj.cart_count);
            });
        }
    }




    if ((modules == 'doctor' || modules == 'patient') && (pages == 'doctor_profile' || pages == 'patient_profile') || pages == 'doctors_search' || pages == 'patients_search') {
        if (pages == 'doctor_profile') {
            /*Get the country list */
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
                            url: base_url + "profile/check_mobileno",
                            type: "post",
                            data: {
                                mobileno: function () {
                                    return $("#mobileno").val();
                                }
                            }
                        }
                    },
                    gender: "required",
                    dob: "required",
                    address1: "required",
                    // address2: "required",
                    country: "required",
                    state: "required",
                    city: "required",
                    postal_code: {
                        required: true,
                        minlength: 4,
                        maxlength: 7,
                        digits: true
                    },
                    price_type: "required",
                    amount: {
                        required: function (element) {
                            return $("input[name='price_type']:checked").val() === "Custom Price" ? true : false;
                        },
                        digits: true,
                        min: 1
                    },
                    services: "required",
                    specialization: "required",
                    "degree[]": "required",
                    "institute[]": "required",
                    "year_of_completion[]": "required"
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
                    address1: lg_please_enter_yo3,
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
                        min: lg_please_enter_va3
                    },
                    services: lg_please_enter_se,
                    specialization: lg_please_select_s1,
                    "degree[]": lg_please_enter_de,
                    "institute[]": lg_please_enter_in,
                    "year_of_completion[]": lg_please_enter_ye

                },
                submitHandler: function (form) {
                    $.ajax({
                        url: base_url + 'profile/update_doctor_profile',
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
                                    window.location.href = base_url + 'dashboard';
                                }, 5000);

                            } else {
                                toastr.error(obj.msg);
                            }
                        }
                    });
                    return false;
                }
            });

            $(document).on('click', '.days_check', function () {
                if ($(this).is(':checked') == true) {
                    $('.eachdays').attr('disabled', 'disabled');
                    $('.eachdayfromtime').attr('disabled', 'disabled');
                    $('.eachdaytotime').attr('disabled', 'disabled');
                    $('.eachdays').prop('checked', false);
                    $('.eachdays').removeAttr('style');
                    $('.eachdayfromtime').removeAttr('style');
                    $('.eachdaytotime').removeAttr('style');

                } else {
                    $('.eachdays').removeAttr('disabled');
                    $('.eachdayfromtime').removeAttr('disabled');
                    $('.eachdaytotime').removeAttr('disabled');
                    $('.daysfromtime_check').val('');
                    $('.daystotime_check').val('');
                    $('.daysfromtime_check').removeAttr('style');
                    $('.daystotime_check').removeAttr('style');
                }
            });
        }

        if (pages == 'patient_profile') {
            var maxDate = $('#maxDate').val();
            $('#dob').datepicker({
                startView: 2,
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: maxDate
            });

            $("#patient_profile_form").validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
                    mobileno: {
                        required: true,
                        minlength: 7,
                        maxlength: 12,
                        digits: true,
                        remote: {
                            url: base_url + "profile/check_mobileno",
                            type: "post",
                            data: {
                                mobileno: function () {
                                    return $("#mobileno").val();
                                }
                            }
                        }
                    },
                    gender: "required",
                    dob: "required",
                    blood_group: "required",
                    address1: "required",
                    address2: "required",
                    country: "required",
                    state: "required",
                    city: "required",
                    postal_code: {
                        required: true,
                        minlength: 4,
                        maxlength: 7,
                        digits: true,
                    }
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
                    blood_group: lg_please_select_b,
                    address1: lg_please_enter_yo3,
                    address2: lg_please_enter_yo4,
                    country: lg_please_select_c,
                    state: lg_please_select_s,
                    city: lg_please_select_c1,
                    postal_code: {
                        required: lg_please_enter_po,
                        maxlength: lg_please_enter_va2,
                        minlength: lg_please_enter_va2,
                        digits: lg_please_enter_va2
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: base_url + 'profile/update_patient_profile',
                        data: $("#patient_profile_form").serialize(),
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
                                    window.location.href = base_url + 'dashboard';
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
    }
});

if (modules == 'doctor' && pages == 'doctor_profile') {
    function delete_clinic_image(id) {
        $.ajax({
            url: base_url + 'profile/delete_clinic_image',
            data: { id: id },
            type: "POST",
            beforeSend: function () {},
            success: function (res) {
                var obj = JSON.parse(res);
                if (obj.status === 200) {
                    toastr.success(obj.msg);
                    location.reload(true);
                } else {
                    toastr.error(lg_something_went_1);
                }
            }
        });
    }
}

if (modules == 'doctor' && pages == 'reviews') {
    function add_reply(id) {
        $('#review_div_' + id).show();
    }

    function create_reply(id) {
        var review_id = $('#review_id_' + id).val();
        var reply = $('#reply_text_' + id).val();
        if (reply != '') {
            $.ajax({
                type: "POST",
                url: base_url + 'dashboard/add_review_reply',
                data: { review_id: review_id, reply: reply }, // <--- THIS IS THE CHANGE
                beforeSend: function () {
                    $('#reply_btn_' + id).attr('disabled', true);
                    $('#reply_btn_' + id).html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (data) {

                    $('#reply_btn_' + id).attr('disabled', false);
                    $('#reply_btn_' + id).html(lg_submit);
                    var obj = JSON.parse(data);
                    if (obj.status === 200) {
                        toastr.success(obj.msg);
                        setTimeout(function () {
                            location.reload(true)
                        }, 1000);

                    } else {
                        toastr.error(obj.msg);
                    }
                },
                error: function () {
                    alert(lg_error_posting_f);
                }
            });
        }
        else {
            toastr.error("Reply should not be empty!");
        }
    }

    function delete_reply(id) {
        //alert(id);
        if (confirm("Are you sure want to delete reply?")) {
            $.ajax({
                type: "POST",
                url: base_url + 'dashboard/delete_reply',
                data: { id: id }, // <--- THIS IS THE CHANGE
                beforeSend: function () {},
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.status === 200) {
                        toastr.success(obj.msg);
                        setTimeout(function () {
                            location.reload(true)
                        }, 1000);

                    } else {
                        toastr.error(obj.msg);
                    }
                },
                error: function () {
                    alert(lg_error_posting_f);
                }
            });
        }
        else {
            return false;
        }
    }
}

if (modules == 'home') {
    if (pages == 'searchDoctor') {

        
        search_doctor(0);

        function reset_doctor() {
            // location.reload(true);
            window.location = base_url + 'search-veterinary';
        }

        function reset_clinic() {
            // location.reload(true);
            window.location = base_url + 'search-veterinary?type=6';
        }

        $(document).ready(function () {
            $('.specialization').prop('checked', false);
            $('.gender').prop('checked', false);
        })


        function search_doctor(load_more) {
            // alert('sddsf'); return false;
            if (load_more == 0) {
                $('#page_no_hidden').val(1);
            }

            var specialization = $('.specialization:checked').map(function () {
                return this.value;
            }).get().join(',');

            var gender = $('.gender:checked').map(function () {
                return this.value;
            }).get().join(',');

            // console.log(specialization);
            // alert();

            var order_by = $('#orderby').val();
            var page = $('#page_no_hidden').val();
            //var gender = $("#gender"). val();
            var role = $("#role").val();
            var login_role = $("#login_role").val();
            var appointment_type = $("#appointment_type").val();
            var city = $("#city").val();
            var state = $("#state").val();
            var country = $("#country").val();
            var keywords = $("#keywords").val();
            var queryString = window.location.search;
            var urlParams = new URLSearchParams(queryString);
            var clinic_id = urlParams.get('id')


            //$('#search-error').html('');

            $.ajax({
                url: base_url + 'search-veterinary',
                type: 'POST',
                data: {
                    appointment_type: appointment_type,
                    gender: gender,
                    specialization: specialization,
                    order_by: order_by,
                    page: page,
                    role: role,
                    login_role: login_role,
                    keywords: keywords,
                    get_id: clinic_id,
                    city: city,
                    citys: citys,
                    state: state,
                    country: country
                },
                beforeSend: function () {
                    // $('#doctor-list').attr('disabled', true);
                    // $('#doctor-list').html('<div class="spinner-border text-light" role="status"></div>');
                    $("#loading").show();
                },
                complete: function () {
                    $("#loading").hide();
                },
                success: function (response) {
                    // $('#doctor-list').html('');
                    if (response) {

                        var obj = $.parseJSON(response);
                        console.log(obj.data);
                        if (obj.data.length >= 1) {
                            var html = '';
                            var doctor_index = [];

                            $(obj.data).each(function () {


                                var services = '';

                                if (this.services != null && this.services.length != 0) {
                                    var service = this.services.split(',');
                                    for (var i = 0; i < service.length; i++) {
                                        services += '<span>' + service[i] + '</span>';
                                    }
                                }

                                var clinic_images = '';

                                var clinic_images_file = $.parseJSON(this.clinic_images);
                                $.each(clinic_images_file, function (key, item) {
                                    var userid = item.user_id;
                                    clinic_images += `
                                    <li> 
                                        <a href="${item.clinic_image}" data-fancybox="gallery"> 
                                            <img src="${item.clinic_image}" alt="Feature" /> 
                                        </a> 
                                    </li>`;

                                });
                                var fullname = lg_dr + ' ' + this.first_name + ' ' + this.last_name;
                                //if (role == 1) {
                                //    fullname = '';
                                //}
                                // else {
                                //     fullname = (this.clinicname != '') ? this.clinicname : this.first_name + ' ' + this.last_name;
                                // }


                                //mobile case//
                                 html += `
                                 <div class="card doctor-card mb-4 d-block d-md-none" id="doctor-${this.doctor_id}">
                                    <div class="card-body p-4">
                                        <div class="row g-3 align-items-center">
                                        <div class="col-3 me-md-3 mb-3 mb-md-0">
                                            <a href="${base_url}doctor-preview/${this.username}" class="doctor-profile-link">
                                            <img src="${this.profileimage}" 
                                                class="doctor-img-mob" 
                                                alt="${fullname} profile picture" style="width: 100px;height: 100px;border-radius: 10%;">
                                            </a>
                                        </div>
                                        <div class="col-9 ps-md-3" style="margin-top: -5%;">
                                            <div class="doctor-info-main">
                                            <h4 class="doctor-name h5" style="margin-bottom: 0;font-size:16px;font-weight:500">
                                                <a href="${base_url}doctor-preview/${this.username}" class="text-dark text-decoration-none">
                                                    ${fullname} 
                                                    <i class="fas fa-chevron-right ms-1 small"></i>
                                                </a>
                                            </h4>
                                            <p class="doctor-specialty text-muted small" style="margin-bottom: 0;font-size:14px;font-weight:400">
                                                ${this.speciality}
                                            </p>
                                            <div class="rating-section" style="margin-bottom: 0;display: flex;">
                                                <div class="star-rating">
                                                ${Array(5).fill().map((_, i) => `
                                                    <i class="fas fa-star ${i < this.rating_value ? 'text-warning' : 'text-secondary'} small"></i>
                                                `).join('')}
                                                </div>
                                                <span class="rating-count small text-muted">(${this.rating_count})</span>
                                            </div>
                                            <p class="clinic-location small text-muted mb-0 fw-normal" style="font-size:12px">
                                                <i class="bi bi-geo-alt-fill me-1"></i>
                                                ${this.clinicname}
                                            </p>
                                            </div>
                                        </div>
                                        </div>

                                        <div class="today-slots-container border-top pt-3 mt-3">
                                            <h6 class="slot-heading small fw-bold mb-2 today-slot">Today</h6>
                                            <div class="slot-grid row g-2" style="margin-left: 0%;">
                                                ${this.slotsToday}
                                            </div>
                                        </div>
                                        <div class="booking-section mt-3">
                                            <div class="d-flex flex-column">
                                                <div class="pricing-info">
                                                    <h5 class="consultation-type">1 video consultation 
                                                        <span class="consultation-price">
                                                            ${this.amount}
                                                        </span>
                                                    </h5>
                                                </div>
                                                <a href="${base_url}doctor-preview/${this.username}" class="apt-btn-mobile">
                                                    Book Appointment
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;


                                html += `
                                <div class="card d-none d-md-flex border-0">
                                    <div class="card-body doctor-list-search">
                                        <div class="doctor-widget">
                                            <div class="doc-info-left">
                                                <div class="doctor-img test">
                                                    <a href="${base_url}doctor-preview/${this.username}">
                                                        <img 
                                                            src="${this.profileimage}" 
                                                            class="img-fluid" style="width:100px;height:100px;border-radius:12px" 
                                                            alt="User Image"
                                                        />
                                                    </a>
                                                </div>
                                                <div class="doc-info-cont">
                                                    <h4 class="doc-name">
                                                        <a href="${base_url}doctor-preview/${this.username}" class="text-decoration-none">
                                                            ${fullname}
                                                        </a>
                                                    </h4>`;

                                if (role == 1) {
                                    //html += '<h5 class="doc-department">
                                    // <img src="' + this.specialization_img + '" class="img-fluid" alt="Speciality">
                                    // ' + this.speciality + '
                                    // </h5>';
                                }
                            html += `<div class="rating">
                                <p class="fw-medium" style="font-size:14px;color:#757575">
                                    ${this.speciality}
                                </p>`;
                                for (var j = 1; j <= 5; j++) {
                                    html += `<i class="fas fa-star${j <= this.rating_value ? ' filled' : ''}"></i>`;
                                }
                                // this.cityname
                                // <ul class="clinic-gallery">' + clinic_images + '</ul>
                                html +=
                                 `<span class="d-inline-block average-rating">(${this.rating_count})</span>
                                    </div>
                                    <div class="clinic-details">
                                        <p class="doc-location" style="font-size:14px">
                                            <i class="fas fa-map-marker-alt"></i> 
                                            ${this.clinicname}
                                        </p>
                                    </div>`;
                                if (role != 6) {
                                    // html += '<div class="clinic-services">' + services + '</div>';
                                }
                                html += '</div></div><div class="doc-info-right">' +
                                    // '<div class="clini-infos">' +
                                    // // '<ul>' 
                                    // // // '<li><i class="far fa-comment"></i>' + this.rating_count +  '</li>' +
                                    // // // '<li><i class="fas fa-map-marker-alt"></i> '+ this.clinicname+ ','+ this.cityname + ', ' + this.countryname + '</li>' +
                                    // // '<li><i class="far fa-money-bill-alt"></i> 1 hour video consultation ' + this.amount + ' </li>' +
                                    // // '</ul>' +
                                    // '</div>' +
                                    '<div class="clinic-booking">';
                                    //'<a class="view-pro-btn" href="' + base_url + 'doctor-preview/' + this.username + '">' + lg_view_profile + '</a>';

                                if (login_role != 5 & login_role != 4 & login_role != 1 & login_role != 6) {
                                    html += `
                                    <div class="clini-infos">
                                        <ul>
                                            <li> 
                                                1 video consultation 
                                                <span class="fw-semibold" style="color:#FD9720;font_size:24px">
                                                    ${this.amount}
                                                </span> 
                                            </li>
                                        </ul>
                                    </div>
                                    <a 
                                        class="apt-btn text-decoration-none" 
                                        href="${base_url}doctor-preview/${this.username}"
                                    >
                                        ${lg_book_appointmen}
                                    </a>`;
                                }
                                html+=`
                                    </div>
                                            </div>
                                        </div>
                                        <div class="row slot-container-row" style="border-top: 1px solid #E1E1E1;">
                                            <div class="fw-semibold" style="font-size:16px;color:#252525; padding-left:2%">
                                                <p>Today</p>
                                            </div>
                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                ${this.slotsToday}
                                            </div>
                                        </div>
                                    </div>
                                     <div class="popup-overlay" id="popup" style="display:none;">
                                        <div class="popup-content bg-white" style="padding:20px; width:90%; max-width:500px; margin:50px auto; box-shadow:0 0 10px rgba(0,0,0,0.5);">
                                            <div class="remaining_slots">
                                                <h3>More Available Slots</h3>
                                                <p>Select a slot from below:</p>
                                            </div>
                                            <button class="close-btn" style="margin-top:15px; padding:8px 16px;">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                                doctor_index.push(this.doctor_id);
                            });

                            if (obj.current_page_no == 1) {
                                $("#doctor-list").html(html);
                                console.log(doctor_index);
                                if(!isMobileView()){
                                   initSlots(doctor_index);
                                }else{
                                    $('.today-slots-container .slot-grid').css({
                                        'overflow-x': 'auto'
                                    });
                                }
                               
                            } else {
                                $("#doctor-list").append(html);
                                console.log(doctor_index);
                                if(!isMobileView()){
                                   initSlots(doctor_index);
                                }else{
                                     $('.today-slots-container .slot-grid').css({
                                        'overflow-x': 'auto'
                                    });
                                }
                            }
                        }
                        else {
                            var html = `
                            <div class="card">
                                '<div class="card-body">
                                    <div class="doctor-widget">
                                        <p>${role != 6 ? lg_no_doctors_foun : lg_no_clinic_found}</p>
                                    </div>
                                </div>
                            </div>
                            `;
                            $("#doctor-list").html(html);
                        }

                        var minimized_elements = $('h4.minimize');
                        minimized_elements.each(function () {
                            var t = $(this).text();
                            if (t.length < 100)
                                return;
                            $(this).html(
                                t.slice(0, 100) + `
                                <span>... </span>
                                <a href="#" class="more">${lg_more}</a>
                                <span style="display:none;">
                                    ${t.slice(100, t.length)}
                                    <a href="#" class="less">${lg_less}</a>
                                </span>`
                            );
                        });

                        $(".search-results").html(`<span>${ obj.count} ${lg_matches_for_you}</span>`);
                        // $(".widget-title").html(obj.count+' Matches for your search');
                        if (obj.count == 0) {
                            $('#load_more_btn_doctor').addClass('d-none');
                            $('#no_more').removeClass('d-none');
                            return false;
                        }

                        if (obj.current_page_no == 1 && obj.count < 3) {
                            $('page_no_hidden').val(1);
                            $('#load_more_btn_doctor').addClass('d-none');
                            $('#no_more').removeClass('d-none');
                            return false;
                        }

                        if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
                            $('#load_more_btn_doctor').removeClass('d-none');
                            $('#no_more').addClass('d-none');
                        } else {
                            $('#load_more_btn_doctor').addClass('d-none');
                            $('#no_more').removeClass('d-none');
                        }

                    }
                }

            });
        }
        $('#load_more_btn_doctor').click(function () {
            var page_no = $('#page_no_hidden').val();
            var current_page_no = 0;
            if (page_no == 1) {
                current_page_no = 2;
            } else {
                current_page_no = Number(page_no) + 1;
            }
            $('#page_no_hidden').val(current_page_no);
            search_doctor(1);
        });
    }
}




if (modules == 'home') {
    if (pages == 'doctors_searchmap') {

        search_doctor(0);

        $(document).ready(function () {
            $('#services').multiselect({
                nonSelectedText: lg_select_services,
                enableClickableOptGroups: true,
                enableCollapsibleOptGroups: true,
                enableFiltering: true,
                includeSelectAllOption: true,
                includeResetOption: true,
                onChange: function (option, checked, select) {
                    var selected_vals = $('#services').val();
                    var selectedValues = JSON.stringify($('#services').val());
                }
            });

        });



        var locations = [];
        function reset_doctor() {
            $('#orderby').val('');
            $('#keywords').val('');
            $('#appointment_type').val('');
            $('#gender').val('');
            $('#specialization').val('');
            $('#country').val('');
            $('#state').val('');
            $('#city').val('');
            // $('#search_doctor_form')[0].reset();
            search_doctor(0);
        }


        function search_doctor(load_more) {

            if (load_more == 0) {
                $('#page_no_hidden').val(1);
            }

            var specialization = $('#specialization').val();
            var order_by = $('#orderby').val();
            var page = $('#page_no_hidden').val();
            var gender = $("#gender").val();
            var role = $("#role").val();
            var appointment_type = $("#appointment_type").val();
            var city = $("#city").val();
            var state = $("#state").val();
            var country = $("#country").val();
            var keywords = $("#keywords").val();


            //$('#search-error').html('');

            $.ajax({
                url: base_url + 'home/search_doctor',
                type: 'POST',
                data: {
                    appointment_type: appointment_type,
                    gender: gender,
                    specialization: specialization,
                    order_by: order_by,
                    role: role,
                    page: page,
                    keywords: keywords,
                    city: city,
                    state: state,
                    country: country
                },
                beforeSend: function () {
                    $("#loading").show();
                },
                complete: function () {
                    $("#loading").hide();
                },
                success: function (response) {
                    //$('#doctor-list').html('');
                    if (response) {
                        var obj = $.parseJSON(response);
                        if (obj.data.length >= 1) {
                            var html = '';
                            //var locations = [];
                            $(obj.data).each(function () {
                                var services = '';
                                var view_more = '';

                                if (this.services != null && this.services.length != 0) {
                                    var service = this.services.split(',');
                                    for (var i = 0; i < service.length; i++) {
                                        services += `<span>${service[i]}</span>`;

                                        if (i == 2) {
                                            view_more = `<a href="${base_url}doctor-preview/${this.username}">${lg_view_more}</a>`;
                                            break;
                                        }
                                    }
                                }

                                var clinic_images = '';

                                var clinic_images_file = $.parseJSON(this.clinic_images);
                                $.each(clinic_images_file, function (key, item) {
                                    var userid = item.user_id;
                                    clinic_images += `
                                    <li> 
                                        <a href="/uploads/clinic_uploads/${userid}/${item.clinic_image}" data-fancybox="gallery"> 
                                            <img src="/uploads/clinic_uploads/${userid}/${item.clinic_image}" alt="Feature" /> 
                                        </a> 
                                    </li>`;
                                });
                                html += `
                                <div class="card">
                                    <div class="card-body">
                                        <div class="doctor-widget">
                                            <div class="doc-info-left">
                                                <div class="doctor-img">
                                                    <a href="${base_url}doctor-preview/${this.username}">
                                                        <img 
                                                            src="${this.profileimage}" 
                                                            class="img-fluid" 
                                                            alt="User Image" 
                                                        />
                                                    </a>
                                                </div>
                                                <div class="doc-info-cont">
                                                    <h4 class="doc-name">
                                                        <a href="${base_url}doctor-preview/${this.username}" class="text-decoration-none">
                                                            ${lg_dr} ${this.first_name}  ${this.last_name}
                                                        </a>
                                                    </h4>
                                                    <h5 class="doc-department">${this.speciality}</h5>
                                                <div class="rating">
                                `;
                                for (var j = 1; j <= 5; j++) {
                                    html += `<i class="fas fa-star ${j <= this.rating_value ? 'filled' : ''}"></i>`;
                                }
                                html += `
                                    <span class="d-inline-block average-rating">(${this.rating_count})</span>
                                        </div>
                                            <div class="clinic-details">
                                                <p class="doc-location" style="font-size:14px">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    ${this.cityname}, ${this.countryname}
                                                </p>
                                                <ul class="clinic-gallery">${clinic_images}</ul>
                                            </div>
                                            <div class="Tags">
                                               <div class="clinic-services">
                                                ${services}
                                               </div>
                                            </div>
                                            ${view_more}
                                        </div>
                                    </div>
                                    <div class="doc-info-right">
                                        <div class="clini-infos">
                                            <ul>
                                                <li>
                                                    <i class="far fa-comment"></i>
                                                    ${this.rating_count} ${lg_feedback}
                                                </li>
                                                <li>
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    ${this.cityname}, ${this.countryname}
                                                </li>
                                                <li>
                                                    <i class="far fa-money-bill-alt"></i>
                                                    ${this.amount}
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="clinic-booking">
                                            <a class="view-pro-btn" href="${base_url}doctor-preview/${this.username}">
                                                ${lg_view_profile}
                                            </a>`;
                                if (login_role != 5 & login_role != 4 & login_role != 1 & login_role != 6) {
                                    html += '<a class="apt-btn" href="' + base_url + 'book-appoinments/' + this.username + '">' + lg_book_appointmen + '</a>';
                                }
                                html += `</div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                        location_items = {};
                        location_items["id"] = this.id;
                        location_items["doc_name"] = lg_dr + ' ' + this.first_name + ' ' + this.last_name;
                        location_items["speciality"] = services;
                        location_items["address"] = this.cityname + ', ' + this.countryname;
                        location_items["next_available"] = "Available on Fri, 22 Mar";
                        location_items["amount"] = this.amount;
                        location_items["lat"] = this.latitude;
                        location_items["lng"] = this.longitude;
                        location_items["icons"] = "default";
                        location_items["profile_link"] = base_url + 'doctor-preview/' + this.username;
                        location_items["total_review"] = this.rating_count + ' ' + lg_feedback;
                        location_items["image"] = this.profileimage;
                        locations.push(location_items);
                    });
                    initialize();
                    if (obj.current_page_no == 1) {
                        $("#doctor-list").html(html);
                    } else {
                        $("#doctor-list").append(html);
                    }
                } else {

                            location_items = {};
                            locations.push(location_items);
                            initialize();
                            var html = '<div class="card">' +
                                '<div class="card-body">' +
                                '<div class="doctor-widget">' +
                                '<p>' + lg_no_doctors_foun + '</p>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            $("#doctor-list").html(html);
                        }




                        var minimized_elements = $('h4.minimize');
                        minimized_elements.each(function () {
                            var t = $(this).text();
                            if (t.length < 100)
                                return;
                            $(this).html(
                                t.slice(0, 100) + '<span>... </span><a href="#" class="more">' + lg_more + '</a>' +
                                '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">' + lg_less + '</a></span>'
                            );
                        });

                        $(".search-results").html('<span>' + obj.count + ' ' + lg_matches_for_you + '' + '</span>');
                        // $(".widget-title").html(obj.count+' Matches for your search');
                        if (obj.count == 0) {
                            $('#load_more_btn').addClass('d-none');
                            $('#no_more').removeClass('d-none');
                            return false;
                        }


                        if (obj.current_page_no == 1 && obj.count < 5) {
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
            search_doctor(1);
        });
    }
}


if (modules == 'home') {
    if (pages == 'patients_search') {


        function reset_patient() {
            $('#orderby').val('');
            $('#search_patient_form')[0].reset();
            search_patient(0);
        }

        search_patient(0);
        function search_patient(load_more) {

            if (load_more == 0) {
                $('#page_no_hidden').val(1);
            }

            var order_by = $('#orderby').val();
            var keyword = $('#search_user').val();
            var page = $('#page_no_hidden').val();
            var gender = $("#gender").val();
            var blood_group = $("#blood_group").val();
            var city = $("#city").val();
            var state = $("#state").val();
            var country = $("#country").val();


            //$('#search-error').html('');

            $.ajax({
                url: base_url + 'home/search_patient',
                type: 'POST',
                data: {
                    gender: gender,
                    blood_group: blood_group,
                    order_by: order_by,
                    page: page,
                    keyword: keyword,
                    city: city,
                    state: state,
                    country: country
                },
                beforeSend: function () {
                    // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
                },
                success: function (response) {
                    //$('#doctor-list').html('');
                    if (response) {
                        var obj = $.parseJSON(response);
                        if (obj.data.length >= 1) {
                            var html = '';
                            $(obj.data).each(function () {


                                html += '<div class="col-md-6 col-lg-4 col-xl-3">' +
                                    '<div class="card widget-profile pat-widget-profile">' +
                                    '<div class="card-body">' +
                                    '<div class="pro-widget-content">' +
                                    '<div class="profile-info-widget">' +
                                    '<a href="javascript:void(0)" class="booking-doc-img">' +
                                    '<img src="' + this.profileimage + '" alt="User Image">' +
                                    '</a>' +
                                    '<div class="profile-det-info">' +
                                    '<h3><a href="javascript:void(0)">' + this.first_name + ' ' + this.last_name + '</a></h3>' +
                                    '<div class="patient-details">' +
                                    '<h5><b>' + lg_patient_id + ' :</b> #PT00' + this.user_id + '</h5>' +
                                    '<h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> ' + this.cityname + ', ' + this.countryname + '</h5>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="patient-info">' +
                                    '<ul>' +
                                    '<li>' + lg_phone + ' <span>' + this.mobileno + '</span></li>' +
                                    '<li>' + lg_age + ' <span>' + this.age + ', ' + this.gender + '</span></li>' +
                                    '<li>' + lg_blood_group + ' <span>' + this.blood_group + '</span></li>' +
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



                        } else {

                            var html = '<div class="card" style="width:100%">' +
                                '<div class="card-body">' +
                                '<div class="doctor-widget">' +
                                '<p>' + lg_no_patients_fou + '</p>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            $("#patients-list").html(html);

                        }


                        var minimized_elements = $('h4.minimize');
                        minimized_elements.each(function () {
                            var t = $(this).text();
                            if (t.length < 100)
                                return;
                            $(this).html(
                                t.slice(0, 100) + '<span>... </span><a href="#" class="more">' + lg_more + '</a>' +
                                '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">' + lg_less + '</a></span>'
                            );
                        });


                        $(".search-results").html('<span>' + obj.count + ' ' + lg_matches_for_you + '' + '</span>');
                        // $(".widget-title").html(obj.count+' Matches for your search');
                        if (obj.count == 0) {
                            $('#load_more_btn').addClass('d-none');
                            $('#no_more').removeClass('d-none');
                            return false;
                        }


                        if (obj.current_page_no == 1 && obj.count < 2) {
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
            search_patient(1);
        });
    }
}


if (modules == 'patient') {
    if (pages == 'book_appoinments') {

        function getSchedule() {


            var date = $('#schedule_date').val();
            var schedule_date = date.split("/").reverse().join("-");
            if (schedule_date == '') {
                $('#schedule_date_error').html('<small class="help-block" data-bv-validator="notEmpty" data-bv-for="schedule_date" data-bv-result="INVALID" style="color:red;">' + lg_date_is_require + '</small>');
                return false;
            }

            $('#schedule_date_error').html('');
            var doctor_id = $('#doctor_id').val();
            $.post(base_url + 'book_appoinments/get_schedule_from_date', { schedule_date: schedule_date, doctor_id: doctor_id }, function (response) {
                alert("dfd")
                $('.bookings-schedule').html(response);
                $('[data-toggle="tooltip"]').tooltip();
            });
        }

        getSchedule()

        $(document).ready(function () {

            $('#schedule_date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                startDate: "d"
            });



        });


        function checkout() {
            var doctor_id = $('#doctor_id').val();
            var hourly_rate = $('#hourly_rate').val();
            var price_type = $('#price_type').val();
            var role_id = $('#role_id').val();
            //var type = $("input[name='type']:checked"). val();  
            var type;


            if (role_id == 6) {
                type = "Clinic";
            }
            else {
                type = "Online";
            }

            var appoinment_token = $("input[name='token']:checked").val();
            var appoinment_date = $("input[name='slots']:checked").attr('data-date');
            var appoinment_timezone = $("input[name='slots']:checked").attr('data-timezone');
            var appoinment_time = $("input[name='slots']:checked").attr('data-time');
            var appoinment_session = $("input[name='slots']:checked").attr('data-session');
            if (!appoinment_token) {
                toastr.warning(lg_please_select_a1);
                return false;
            }
            var appointment_data = [];
            appointment_data.push({
                'appoinment_token': $("input[name='token']:checked").val(),
                'appoinment_date': $("input[name='token']:checked").attr('data-date'),
                'appoinment_timezone': $("input[name='token']:checked").attr('data-timezone'),
                'appoinment_start_time': $("input[name='token']:checked").attr('data-start-time'),
                'appoinment_end_time': $("input[name='token']:checked").attr('data-end-time'),
                'appoinment_session': $("input[name='token']:checked").attr('data-session'),
                'type': type
            });
            var appointment_details = JSON.stringify(appointment_data);

            $('#pay_btn').attr('disabled', true);
            $('#pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
            $.post(base_url + 'book_appoinments/set_booked_session', {
                hourly_rate: hourly_rate,
                appointment_details: appointment_details,
                price_type: $('#price_type').val(),
                doctor_id: doctor_id,
                doctor_role_id: role_id

            }, function (res) {

                var obj = JSON.parse(res);
                if (obj.status === 200) {
                    setTimeout(function () { window.location = base_url + 'checkout-appoinment'; }, 1000);
                }
                else if (obj.status === 500) {
                    toastr.error(obj.message);
                    $('#pay_btn').attr('disabled', false);
                    $('#pay_btn').html(lg_proceed_to_pay);
                }
                else {
                    toastr.success(lg_transaction_suc);
                    setTimeout(function () {
                        window.location.href = base_url + 'dashboard';
                    }, 2000);

                }
            });

        }

    }

}


if (modules == 'home' && pages == 'checkout') {
    function login_cart() {
        var login_email = $('#login_email').val();
        var login_password = $('#login_password').val();
        if (login_email == '') {
            toastr.error(lg_please_enter_em1);
            return false;
        }
        if (login_password == '') {
            toastr.error(lg_please_enter_pa);
            return false;
        }

        $.ajax({
            url: base_url + 'signin/is_valid_login',
            data: {
                email: login_email,
                password: login_password
            },
            type: "POST",
            beforeSend: function () {
                $('#cart_login_btn').attr('disabled', true);
                $('#cart_login_btn').html('<div class="spinner-border text-light" role="status"></div>');
            },
            success: function (res) {
                $('#cart_login_btn').attr('disabled', false);
                $('#cart_login_btn').html(lg_signin);

                var obj = JSON.parse(res);

                if (obj.status === 200) {
                    window.location.href = base_url + 'cart-checkout';
                } else {
                    toastr.error(obj.msg);
                }
            }
        });
        return false;
    }

    $(document).ready(function () {

        $("#shipping_form").validate({
            rules: {
                ship_name: "required",
                ship_mobile: {
                    required: true,
                    minlength: 7,
                    maxlength: 12,
                    digits: true

                },
                ship_email: {
                    required: true,
                    email: true
                },
                ship_address_1: "required",
                ship_country: "required",
                ship_state: "required",
                ship_city: "required",
                postal_code: {
                    required: true,
                    minlength: 4,
                    maxlength: 7,
                    digits: true,
                },

            },
            messages: {
                ship_name: lg_please_enter_yo5,
                mobileno: {
                    required: lg_please_enter_mo,
                    maxlength: lg_please_enter_mo,
                    minlength: lg_please_enter_mo,
                    digits: lg_please_enter_mo,

                },
                email: {
                    required: lg_please_enter_em,
                    email: lg_please_enter_va1,

                },

                ship_address_1: lg_please_enter_yo6,
                ship_country: lg_please_select_c,
                ship_state: lg_please_select_s,
                ship_city: lg_please_select_c1,

                postal_code: {
                    required: lg_please_enter_po,
                    maxlength: lg_please_enter_va2,
                    minlength: lg_please_enter_va2,
                    digits: lg_please_enter_va2
                }
            },
            submitHandler: function (form) {

                $.ajax({
                    url: base_url + 'cart/add_shipping_details',
                    data: $("#shipping_form").serialize(),
                    type: "POST",
                    beforeSend: function () {

                    },
                    success: function (res) {

                    }
                });
                return false;
            }
        });

    });

    $(document).ready(function () {

        $('input[type=radio][name=payment_methods]').change(function () {
            payment_methods = $('input[type=radio][name=payment_methods]:checked').val();
            if (payment_methods == 'PayPal') {
                $('.stripe_payment').hide();
                $('.paypal_payment').show();
                $('#payment_method').val('Card Payment');
            } else {
                $('#payment-form').trigger('submit');
            }
        });
    });

    /*submit form ajax template*/
    $("#payment-form").submit(function (e) {

        e.preventDefault();
    }).validate({
        rules: {
            ship_name: {
                required: true,
                minlength: 3,
                maxlength: 150,
                text_spaces_only: true
            },
            ship_email: {
                required: true,
                email: true
            },
            ship_mobile: {
                required: true,
                minlength: 7,
                maxlength: 12,
                digits: true,
            },
            ship_country: {
                required: true
            },
            ship_address_1: {
                required: true,
                address_validation: true,
                maxlength: 500
            },
            ship_address_2: {
                address_validation: true,
                maxlength: 500
            },
            ship_state: {
                required: true
            },
            ship_city: {
                required: true
            },
            postal_code: {
                required: true,
                minlength: 4,
                maxlength: 7,
                digits: true,
            },

        },
        messages: {
            ship_name: {
                required: lg_pers_info_name_req,
                minlength: lg_pers_info_name_min,
                maxlength: lg_pers_info_name_max,
            },
            ship_email: {
                required: lg_pers_info_email_req,
                email: lg_pers_info_email_val,
            },
            ship_mobile: {
                required: lg_pers_info_mobile_req,
                minlength: lg_pers_info_mobile_min,
                maxlength: lg_pers_info_mobile_max,
                digits: lg_pers_info_mobile_val,
            },
            ship_country: {
                required: lg_pers_info_country_req,
            },
            ship_address_1: {
                required: lg_pers_info_address_req,
                address_validation: lg_pers_info_address_val,
                maxlength: lg_enter_address_max,
            },
            ship_address_2: {
                address_validation: lg_pers_info_address_val,
                maxlength: lg_enter_address_max,
            },
            ship_state: {
                required: lg_pers_info_state_req,
            },
            ship_city: {
                required: lg_pers_info_city_req,
            },
            postal_code: {
                required: lg_pers_info_postalcode_req,
                minlength: lg_pers_info_postalcode_min,
                maxlength: lg_pers_info_postalcode_max,
                digits: lg_pers_info_postalcode_val,
            },

        },
        invalidHandler: function (event, validator) {
            payment_methods = $('input[type=radio][name=payment_methods]:checked').val();
            if (payment_methods == 'PayPal') {
                $('#pay_buttons').attr('disabled', false);
                $('#pay_buttons').html(lg_confirm_and_pay);
            } else {
                $('input[type=radio][name=payment_methods]:checked').prop('checked', false);
            }
        },
        submitHandler: function (form) {
            payment_methods = $('input[type=radio][name=payment_methods]:checked').val();

            if (payment_methods == 'Card Payment') {
                
                $('.stripe_payment').show();
                $('.paypal_payment').hide();
                $('#payment_method').val('Card Payment');

            } else if (payment_methods == 'PayPal') {
                $('.stripe_payment').hide();
                $('.paypal_payment').show();
                $('#payment_method').val('Card Payment');
                form.submit();

            } else {
                $('.stripe_payment').hide();
                $('.paypal_payment').hide();
                $('#payment_method').val('');
            }
            return false;
        }
    });
    /*submit form ajax template*/


    function appoinment_payment(type) {

        // var terms_accept=$("input[name='terms_accept']:checked").val();
        var terms_accept = 1;
        if (terms_accept == '1') {
            if (type == 'paypal') {
                // $('#payment_formid').submit();
                $('#pay_buttons').attr('disabled', true);
                $('#pay_buttons').html('<div class="spinner-border text-light" role="status"></div>');
                $('#payment-form').attr('action', base_url + 'pharmacy-paypal-initiate');
                $('#payment-form').submit();

            }
            else {
                var payment_method = $("input[name='payment_methods']:checked").val();
                if (payment_method != 'Card Payment') {
                    $("#my_book_appoinment").click();
                }

                return false;
            }
        }
        else {
            toastr.warning(lg_please_accept_t);
        }
    }
    var stripe = Stripe(stripe_api_key);

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', { style: style });

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
            $('#stripe_pay_btn').attr('disabled', false);
            $('#stripe_pay_btn').html('Confirm and Pay');
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        $('#stripe_pay_btn').attr('disabled', true);
        $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(stripePaymentMethodHandler);
    });

    function stripePaymentMethodHandler(result) {
        if (result.error) {
            $('#card-errors').text(result.error.message);
            $('#stripe_pay_btn').attr('disabled', false);
            $('#stripe_pay_btn').html('Confirm and Pay');
            // Show error in payment form
        } else {
            // Otherwise send paymentMethod.id to your server 

            var payment_method = $('#myself:checked').val();

            ship_name = $('#ship_name').val();
            ship_email = $('#ship_email').val();
            ship_mobile = $('#ship_mobile').val();
            ship_address_1 = $('#ship_address_1').val();
            ship_address_2 = $('#ship_address_2').val();
            country = $('#country').val();
            state = $('#state').val();
            city = $('#city').val();
            postal_code = $('#postal_code').val();
            shipping = $('#shipping').val();
            total_amount = $('#total_amount').val();
            currency_code = $('#currency_code').val();

            if (ship_name == '') {
                toastr.error(lg_name_is_require);
                return false;
            }

            if (ship_email == '') {
                toastr.error(lg_email_is_requir);
                return false;
            }

            if (ship_mobile == '') {
                toastr.error(lg_mobile_no_is_re);
                return false;
            }

            if (ship_address_1 == '') {
                toastr.error(lg_address1_is_req);
                return false;
            }

            if (country == '') {
                toastr.error(lg_country_is_requ);
                return false;
            }

            if (state == '') {
                toastr.error(lg_state_is_requir);
                return false;
            }

            if (city == '') {
                toastr.error(lg_city_is_require);
                return false;
            }

            if (postal_code == '') {
                toastr.error(lg_postal_code_is_);
                return false;
            }

            $.ajax({
                url: base_url + 'home/stripePayment',
                data: { payment_method_id: result.paymentMethod.id, payment_method: payment_method, ship_name: ship_name, ship_email: ship_email, ship_mobile: ship_mobile, ship_address_1: ship_address_1, ship_address_2: ship_address_2, country: country, state: state, city: city, postal_code: postal_code, shipping: shipping, total_amount: total_amount, currency_code: currency_code },
                type: 'POST',
                dataType: 'JSON',
                beforeSend: function () {
                    $('#stripe_pay_btn').attr('disabled', true);
                    $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (response) {

                    handleServerResponse(response);

                },
                error: function (error) {
                    // console.log(error);
                }
            });

        }
    }

    function handleServerResponse(response) {
        if (response.status == '500') {
            toastr.error(response.message);
            $('#stripe_pay_btn').attr('disabled', false);
            $('#stripe_pay_btn').html('Confirm and Pay');
        } else if (response.status == '201') {
            // Use Stripe.js to handle required card action
            stripe.handleCardAction(
                response.payment_intent_client_secret
            ).then(handleStripeJsResult);
        } else {
            if (response.status == '200') {

                toastr.success(lg_your_order_has_);
                setTimeout(function () {
                    window.location.href = base_url + 'payment-sucess';
                }, 2000);

            } else {
                toastr.error(lg_order_failed);
                setTimeout(function () {
                    window.location.href = base_url + 'cart-list';
                }, 2000);
            }
        }
    }

    function handleStripeJsResult(result) {

        if (result.status == '500') {
            toastr.error(result.message);
        } else {
            // The card action has been handled
            // The PaymentIntent can be confirmed again on the server

            var payment_method = $('#myself:checked').val();

            ship_name = $('#ship_name').val();
            ship_email = $('#ship_email').val();
            ship_mobile = $('#ship_mobile').val();
            ship_address_1 = $('#ship_address_1').val();
            ship_address_2 = $('#ship_address_2').val();
            country = $('#country').val();
            state = $('#state').val();
            city = $('#city').val();
            postal_code = $('#postal_code').val();
            shipping = $('#shipping').val();
            total_amount = $('#total_amount').val();
            currency_code = $('#currency_code').val();

            if (ship_name == '') {
                toastr.error(lg_name_is_require);
                return false;
            }

            if (ship_email == '') {
                toastr.error(lg_email_is_requir);
                return false;
            }

            if (ship_mobile == '') {
                toastr.error(lg_mobile_no_is_re);
                return false;
            }

            if (ship_address_1 == '') {
                toastr.error(lg_address1_is_req);
                return false;
            }

            if (country == '') {
                toastr.error(lg_country_is_requ);
                return false;
            }

            if (state == '') {
                toastr.error(lg_state_is_requir);
                return false;
            }

            if (city == '') {
                toastr.error(lg_city_is_require);
                return false;
            }

            if (postal_code == '') {
                toastr.error(lg_postal_code_is_);
                return false;
            }

            $.ajax({
                url: base_url + 'cart/stripe_payment',
                data: { payment_intent_id: result.paymentIntent.id, payment_method: payment_method, ship_name: ship_name, ship_email: ship_email, ship_mobile: ship_mobile, ship_address_1: ship_address_1, ship_address_2: ship_address_2, country: country, state: state, city: city, postal_code: postal_code, shipping: shipping, total_amount: total_amount, currency_code: currency_code },
                type: 'POST',
                dataType: 'JSON',
                beforeSend: function () {
                    $('#stripe_pay_btn').attr('disabled', true);
                    $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (response) {

                    handleServerResponse(response);

                },
                error: function (error) {
                    // console.log(error);
                }
            });

        }
    }

}

if (modules == 'patient') {
    if (pages == 'checkout') {

        $('.OTP').hide();
        $('#resendotp').hide();

        function register() {

            $('#login_modal').modal('hide');
            $('#register_modal').modal('show');

        }

        function login() {

            $('#register_modal').modal('hide');
            $('#forgot_password_modal').modal('hide');
            $('#login_modal').modal('show');

        }

        function forgot_password() {

            $('#login_modal').modal('hide');
            $('#forgot_password_modal').modal('show');

        }

        $("#reset_password").validate({
            rules: {

                resetemail: {
                    required: true,
                    email: true,
                    remote: {
                        url: base_url + "signin/check_resetemail",
                        type: "post",
                        data: {
                            resetemail: function () { return $("#resetemail").val(); }
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
                    url: base_url + 'signin/reset_password',
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

                        if (obj.status === 200) {
                            $('#reset_password')[0].reset();
                            toastr.success(obj.msg);
                            $('#forgot_password_modal').modal('hide');
                            $('#login_modal').modal('show');
                        }
                        else {
                            toastr.error(obj.msg);
                        }
                    }
                });
                return false;
            }
        });

        $("#sendotp").on('click', function () {

            var mobileno = $('#mobileno').val();
            var country_code = $('#country_code').val();
            if (mobileno == "") {
                toastr.error(lg_please_enter_va4);
            }
            else {
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

                        $('.otp_load').html('<a class="forgot-link" onclick="resend_otp()"  href="javascript:void(0);" id="resendotp">' + lg_resend_otp + '</a>');

                        var obj = JSON.parse(res);

                        if (obj.status === 200) {

                            $('.OTP').show();
                            $('#resendotp').show();
                            toastr.success(obj.msg);

                        }
                        else if (obj.status === 500) {
                            toastr.error(obj.msg);
                        }
                        else {
                            toastr.error(obj.msg);
                        }
                    }
                });
            }
        });




        // $("#register_form").validate({
        //   rules: {
        //     first_name: "required",
        //     last_name: "required",
        //     mobileno: {
        //         required: true,
        //         minlength: 7,
        //         maxlength: 12,
        //         digits: true,
        //         remote: {
        //         url: base_url+"signin/check_mobileno",
        //         type: "post",
        //         data: {
        //             mobileno: function(){ return $("#mobileno").val(); }
        //         }
        //       }
        //     },
        //     email: {
        //       required: true,
        //       email: true,
        //       remote: {
        //         url: base_url+"signin/check_email",
        //         type: "post",
        //         data: {
        //             email: function(){ return $("#register_email").val(); }
        //         }
        //       }
        //     },
        //     password: {
        //         required: true,
        //         minlength: 6
        //     },
        //     confirm_password: {
        //          required: true,
        //          equalTo: "#register_password"
        //     }

        //   },
        //   messages: {
        //     first_name: lg_please_enter_yo,
        //     last_name: lg_please_enter_yo1,
        //     mobileno: {
        //         required: lg_please_enter_mo,
        //         maxlength: lg_please_enter_va,
        //         minlength: lg_please_enter_va,
        //         digits: lg_please_enter_va,
        //         remote: lg_your_mobile_no_
        //     },
        //     email: {
        //         required: lg_please_enter_em,
        //         email: lg_please_enter_va1,
        //         remote: lg_your_email_addr1
        //     },
        //     password: {
        //         required: lg_please_enter_pa,
        //         minlength: lg_your_password_m
        //     },
        //     confirm_password: {
        //         required: lg_please_enter_co,
        //         equalTo: lg_your_password_d
        //     }

        //   },

        //   submitHandler: function(form) {

        //     $.ajax({
        //       url: base_url+'signin/signup',
        //       data: $("#register_form").serialize(),
        //       type: "POST",
        //       beforeSend: function(){
        //         $('#register_btn').attr('disabled',true);
        //         $('#register_btn').html('<div class="spinner-border text-light" role="status"></div>');
        //       },
        //       success: function(res){
        //         $('#register_btn').attr('disabled',false);
        //         $('#register_btn').html(lg_signup);
        //         var obj = JSON.parse(res);

        //         if(obj.status===200)
        //         {
        //           $('#register_form')[0].reset();
        //           toastr.success(obj.msg);
        //           $('#register_modal').modal('hide');
        //           $('#login_modal').modal('show');            
        //         }
        //         else
        //         {
        //           toastr.error(obj.msg);
        //         }   
        //       }
        //     });
        //     return false;
        //   }
        // });


        $("#signin_form").validate({
            rules: {
                email: "required",
                password: {
                    required: true,
                    minlength: 8
                },
            },
            messages: {
                email: lg_please_enter_em,
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

                        if (obj.status === 200) {
                            $('#login_modal').modal('hide');
                            toastr.success("logged-in successfully");
                            window.location.reload();
                        }
                        else {
                            toastr.error(obj.msg);
                        }
                    }
                });
                return false;
            }
        });


        function appoinment_payment(type) {
            
            // var terms_accept=$("input[name='terms_accept']:checked").val();
            var terms_accept = 1;
            if (terms_accept == '1') {
                if (type == 'paypal') {
                    $('#payment_formid').submit();

                } else if (type == 'razorpay') {
                    razorpay();

                } else {
                    var payment_method = $("input[name='payment_methods']:checked").val();
                    if (payment_method != 'Card Payment') {
                        $("#my_book_appoinment").click();
                    }

                    return false;
                }
            } else {
                toastr.warning(lg_please_accept_t);
            }
        }

        function razorpay() {
            $('#razor_pay_btn').attr('disabled', true);
            $('#razor_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
            var amount = $('#amount').val();
            var currency_code = $('#currency_code').val();

            $.post(base_url + 'appointment-razorpay-create', { amount: amount, currency_code: currency_code }, function (data) {

                $('#razor_pay_btn').attr('disabled', false);
                $('#razor_pay_btn').html(lg_confirm_and_pay2);
                var obj = jQuery.parseJSON(data);
                var options = {
                    "key": obj.key_id,
                    "amount": obj.amount,
                    "currency": obj.currency,
                    "name": obj.sitename,
                    "description": "Booking Slot",
                    "image": obj.siteimage,
                    "order_id": obj.order_id,
                    "handler": function (response) {
                        razorpay_appoinments(response.razorpay_payment_id, response.razorpay_order_id, response.razorpay_signature);
                    },
                    "prefill": {
                        "name": obj.patientname,
                        "email": obj.email,
                        "contact": obj.mobileno
                    },
                    "notes": {
                        "address": "Razorpay Corporate Office"
                    },
                    "theme": {
                        "color": "#15558d"
                    }
                };

                var rzp1 = new Razorpay(options);
                rzp1.open();
            });
        }

        function razorpay_appoinments(payment_id, order_id, signature) {
            $('#payment_id').val(payment_id);
            $('#order_id').val(order_id);
            $('#signature').val(signature);

            $.ajax({
                url: base_url + 'appointment-razorpay-payment',
                data: $('#payment_formid').serialize(),
                type: 'POST',
                dataType: 'JSON',
                beforeSend: function () {
                    $('#razor_pay_btn').attr('disabled', true);
                    $('#razor_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (response) {
                    // $('.overlay').hide();
                    if (response.status == '200') {
                        toastr.success(lg_transaction_suc);
                        setTimeout(function () {
                            window.location.href = base_url + 'payment-success/' + response.appointment_id;
                        }, 2000);
                    } else {
                        toastr.error(lg_transaction_fai1);
                        setTimeout(function () {
                            window.location.href = base_url + 'patient';
                        }, 2000);
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        }

        var stripe = Stripe(stripe_api_key);

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', { style: style });

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                $('#stripe_pay_btn').attr('disabled', false);
                $('#stripe_pay_btn').html('Confirm and Pay');
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            $('#stripe_pay_btn').attr('disabled', true);
            $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
            stripe.createPaymentMethod({
                type: 'card',
                card: card,
            }).then(stripePaymentMethodHandler);
        });

        function stripePaymentMethodHandler(result) {
            if (result.error) {
                $('#card-errors').text(result.error.message);
                $('#stripe_pay_btn').attr('disabled', false);
                $('#stripe_pay_btn').html('Confirm and Pay');
                // Show error in payment form
            } else {
                // Otherwise send paymentMethod.id to your server 

                var payment_method = $('#myself:checked').val();

                $.ajax({
                    url: base_url + 'appointment-stripe-payment',
                    data: { payment_method_id: result.paymentMethod.id, payment_method: payment_method },
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function () {
                        $('#stripe_pay_btn').attr('disabled', true);
                        $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (response) {

                        handleServerResponse(response);

                    },
                    error: function (error) {
                        // console.log(error);
                    }
                });

            }
        }

        function handleServerResponse(response) {
            if (response.status == '500') {
                toastr.error(response.message);
                setTimeout(function () {
                    history.back()
                }, 2000);
                $('#stripe_pay_btn').attr('disabled', false);
                $('#stripe_pay_btn').html('Confirm and Pay');
            }
            else if (response.status == '201') {
                // Use Stripe.js to handle required card action
                stripe.handleCardAction(
                    response.payment_intent_client_secret
                ).then(handleStripeJsResult);
            }
            else {
                // print notification message here
                toastr.success(lg_transaction_suc);
                setTimeout(function () {
                    window.location.href = base_url + 'payment-success/' + response.appointment_id;
                }, 2000);
            }
        }

        function handleStripeJsResult(result) {

            if (result.status == '500') {
                toastr.error(result.message);
            } else {
                // The card action has been handled
                // The PaymentIntent can be confirmed again on the server

                var payment_method = $('#myself:checked').val();

                $.ajax({
                    url: base_url + 'book_appoinments/stripe_payment',
                    data: { payment_intent_id: result.paymentIntent.id, payment_method: payment_method },
                    type: 'POST',
                    dataType: 'JSON',
                    beforeSend: function () {
                        $('#stripe_pay_btn').attr('disabled', true);
                        $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (response) {
                        handleServerResponse(response);
                    },
                    error: function (error) {
                        // console.log(error);
                    }
                });

            }
        }

        // Submit the form with the token ID.
        $(document).ready(function () {

            $('#my_book_appoinment').click(function (e) {

                $.ajax({

                    url: base_url + 'add-appoinment',

                    data: $('#payment_formid').serialize(),

                    type: 'POST',

                    dataType: 'JSON',

                    beforeSend: function () {

                        $('#pay_button').attr('disabled', true);
                        $('#pay_button').html('<div class="spinner-border text-light" role="status"></div>');

                    }, success: function (response) {

                        if (response.status == '200') {

                            toastr.success(lg_transaction_suc);
                            setTimeout(function () {
                                window.location.href = base_url + 'payment-success/' + response.appointment_id;
                            }, 2000);

                        } else {
                            toastr.error(lg_transaction_fai1);
                            setTimeout(function () {
                                window.location.href = base_url + 'dashboard';
                            }, 2000);
                        }



                    },

                    error: function (error) {

                        // console.log(error);

                    }

                });

                e.preventDefault();

            });

        });
        function razorpay() {
            $('#razor_pay_btn').attr('disabled', true);
            $('#razor_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
            var amount = $('#amount').val();
            var currency_code = $('#currency_code').val();

            $.post(base_url + 'lab-razorpay-create', { amount: amount, currency_code: currency_code }, function (data) {

                $('#razor_pay_btn').attr('disabled', false);
                $('#razor_pay_btn').html(lg_confirm_and_pay2);
                var obj = jQuery.parseJSON(data);
                var options = {
                    "key": obj.key_id,
                    "amount": obj.amount,
                    "currency": obj.currency,
                    "name": obj.sitename,
                    "description": "Booking Slot",
                    "image": obj.siteimage,
                    "order_id": obj.order_id,
                    "handler": function (response) {
                        razorpay_appoinments(response.razorpay_payment_id, response.razorpay_order_id, response.razorpay_signature);
                    },
                    "prefill": {
                        "name": obj.patientname,
                        "email": obj.email,
                        "contact": obj.mobileno
                    },
                    "notes": {
                        "address": "Razorpay Corporate Office"
                    },
                    "theme": {
                        "color": "#15558d"
                    }
                };

                var rzp1 = new Razorpay(options);
                rzp1.open();
            });
        }

        function razorpay_appoinments(payment_id, order_id, signature) {
            $('#payment_id').val(payment_id);
            $('#order_id').val(order_id);
            $('#signature').val(signature);

            $.ajax({
                url: base_url + 'lab-razorpay-payment',
                data: $('#payment_formid').serialize(),
                type: 'POST',
                dataType: 'JSON',
                beforeSend: function () {
                    $('#razor_pay_btn').attr('disabled', true);
                    $('#razor_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (response) {
                    // $('.overlay').hide();
                    if (response.status == '200') {
                        toastr.success(lg_transaction_suc);
                        setTimeout(function () {
                            window.location.href = base_url + 'payment-success/' + response.appointment_id;
                        }, 2000);
                    } else {
                        toastr.error(lg_transaction_fai1);
                        setTimeout(function () {
                            window.location.href = base_url + 'patient';
                        }, 2000);
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        }
        
        $(document).ready(function () {
            $('input[type=radio][name=payment_methods]').change(function () {
                if (this.value == 'Card Payment') {
                    $('.stripe_payment').show();
                    $('.paypal_payment').hide();
                    $('.clinic_payment').hide();
                    $('.razorpay_payment').hide();
                    $('#payment_method').val('Card Payment');

                } else if (this.value == 'PayPal') {
                    $('.stripe_payment').hide();
                    $('.paypal_payment').show();
                    $('.clinic_payment').hide();
                    $('.razorpay_payment').hide();
                    $('#payment_method').val('Card Payment');

                } else if (this.value == 'Pay on Arrive') {
                    $('.stripe_payment').hide();
                    $('.paypal_payment').hide();
                    $('.clinic_payment').show();
                    $('.razorpay_payment').hide();
                    $('#payment_method').val('Pay on Arrive');

                } else if (this.value == 'Razorpay') {
                    $('.stripe_payment').hide();
                    $('.paypal_payment').hide();
                    $('.clinic_payment').hide();
                    $('.razorpay_payment').show();
                    $('#payment_method').val('Card Payment');

                } else {
                    $('.stripe_payment').hide();
                    $('.paypal_payment').hide();
                    $('.clinic_payment').hide();
                    $('.razorpay_payment').hide();
                    $('#payment_method').val('');
                }
            });
        });

    }
}



// if (modules == 'clinic' || modules == 'doctor' || modules == 'patient' || modules == 'lab' || modules == 'pharmacy') {

//     function email_verification() {
//         $.get(base_url + 'ajax/user-email-verify', function (data) {
//             toastr.success(lg_activation_mail);
//             setTimeout(function () {                
//             window.location.reload();
//             }, 3000);
//         });
//     }
// }










if (modules == 'home' || modules == 'ecommerce') {

    if (pages == 'products_list_by_pharmacy' || pages == 'index') {
        function getproduct_key() {

            var pr_key = $('#keywords').val();
            var subcategoryarray = $('input:checkbox:checked.subcategotyCheckbox').map(function () {
                return this.value;
            }).get();



            var category = $('#category').val();
            var subcategory = $('#subcategory').val();
            //var keywords = $('#keywords').val();
            var pharmacy_id = $('#pharmacy_id').val();
            $.ajax({
                type: 'POST',
                url: base_url + 'home/get_search_key_products',
                data: { keywords: pr_key, category: category, pharmacy_id: pharmacy_id, subcategory: subcategoryarray },
                //dataType: 'json',
                success: function (response) {
                    //console.log(response);
                    var obj = $.parseJSON(response);
                    //console.log(obj.data);
                    //arr = $.parseJSON($.trim(response));
                    // console.log(arr);
                    // $("#keywords").autocomplete({
                    //     source: $.trim(arr)
                    // });
                    $("#keywords").autocomplete({
                        source: obj.data
                    });
                }

            });



        }






    }






    if (pages == 'doctors_searchmap') {
        google.maps.visualRefresh = true;
        var slider, infowindow = null;
        var bounds = new google.maps.LatLngBounds();
        var map, current = 0;

        var icons = {
            'default': 'assets/img/marker.png'
        };

        function show() {
            infowindow.close();
            if (!map.slide) {
                return;
            }
            var next, marker;
            if (locations.length == 0) {
                return
            } else {
                next = 0;
            }

            current = next;
            marker = locations[next];
            setInfo(marker);
            // console.log(locations);
            infowindow.open(map, marker);
        }


        function initialize() {

            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                zoom: 14,
                //center: new google.maps.LatLng(53.470692, -2.220328),
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP,

            };

            map = new google.maps.Map(document.getElementById('map'), mapOptions);
            map.slide = true;

            setMarkers(map, locations);
            infowindow = new google.maps.InfoWindow({
                content: "loading..."
            });

            google.maps.event.addListener(infowindow, 'closeclick', function () {
                infowindow.close();
            });
            slider = window.setTimeout(show, 3000);
        }

        function setInfo(marker) {
            var content =
                '<div class="profile-widget" style="width: 100%; display: inline-block;">' +
                '<div class="doc-img">' +
                '<a href="' + marker.profile_link + '" tabindex="0" target="_blank">' +
                '<img class="img-fluid" alt="' + marker.doc_name + '" src="' + marker.image + '">' +
                '</a>' +
                '</div>' +
                '<div class="pro-content">' +
                '<h3 class="title">' +
                '<a href="' + marker.profile_link + '" tabindex="0">' + marker.doc_name + '</a>' +
                '<i class="fas fa-check-circle verified"></i>' +
                '</h3>' +
                '<p class="speciality">' + marker.speciality + '</p>' +
                '<div class="rating">' +
                '<i class="fas fa-star"></i>' +
                '<i class="fas fa-star"></i>' +
                '<i class="fas fa-star"></i>' +
                '<i class="fas fa-star"></i>' +
                '<i class="fas fa-star"></i>' +
                '<span class="d-inline-block average-rating"> (' + marker.total_review + ')</span>' +
                '</div>' +
                '<ul class="available-info">' +
                '<li><i class="fas fa-map-marker-alt"></i> ' + marker.address + ' </li>' +
                //'<li><i class="far fa-clock"></i> ' + marker.next_available + '</li>'+
                '<li><i class="far fa-money-bill-alt"></i> ' + marker.amount + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
            infowindow.setContent(content);
        }



        function setMarkers(map, markers) {
            for (var i = 0; i < markers.length; i++) {
                var item = markers[i];
                var latlng = new google.maps.LatLng(item.lat, item.lng);
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    doc_name: item.doc_name,
                    address: item.address,
                    speciality: item.speciality,
                    // next_available: item.next_available,
                    amount: item.amount,
                    profile_link: item.profile_link,
                    total_review: item.total_review,
                    animation: google.maps.Animation.DROP,
                    icon: icons[item.icons],
                    image: item.image
                });
                bounds.extend(marker.position);
                markers[i] = marker;
                google.maps.event.addListener(marker, "click", function () {
                    setInfo(this);
                    infowindow.open(map, this);
                    window.clearTimeout(slider);
                });
            }
            map.fitBounds(bounds);
            google.maps.event.addListener(map, 'zoom_changed', function () {
                if (map.zoom > 16)
                    map.slide = false;
            });
        }

        //google.maps.event.addDomListener(window, 'load', initialize);

    }
}
if (pages == 'products_list_by_pharmacy' || modules == 'ecommerce' || pages == 'index' || pages == 'cart' || pages == 'product_details') {
    function get_products(load_more) {


        var subcategoryarray = $('input:checkbox:checked.subcategotyCheckbox').map(function () {
            return this.value;
        }).get();


        //console.log(values)
        if (load_more == 0) {
            $('#page_no_hidden').val(1);
        }

        var page = $('#page_no_hidden').val();
        var category = $('#category').val();
        var subcategory = $('#subcategory').val();
        var keywords = $('#keywords').val();
        var pharmacy_id = $('#pharmacy_id').val();

        //$('#search-error').html('');

        $.ajax({
            url: base_url + 'home/get_products',
            type: 'POST',
            data: {
                page: page,
                category: category,
                subcategory: subcategoryarray,
                keywords: keywords,
                pharmacy_id: pharmacy_id,
                subcategoryarray: subcategoryarray,
            },
            beforeSend: function () {
                $("#loading").show();
            },
            complete: function () {
                $("#loading").hide();
            },
            success: function (response) {
                // alert('dfdsf'); return false;
                if (response) {
                    var obj = $.parseJSON(response);
                    if (obj.data.length >= 1) {
                        var html = '';
                        //alert(obj.category_name)

                        $(obj.data).each(function () {

                            $('.categoty_title_name').html(obj.category_name)
                            //alert(this.category_name)
                            html += '<div class="col-md-12 col-lg-3 col-xl-3 product-custom">';
                            html += '<div class="profile-widget">';
                            html += '<div class="doc-img">';
                            html += '<a href="' + base_url + 'product-details/' + this.id + '" tabindex="-1">';
                            html += '<img class="img-fluid" alt="Product image" src="' + this.product_image + '">';
                            html += '</a>';
                            // html += '<a href="javascript:void(0)" class="fav-btn" tabindex="-1">';
                            // html += '<i class="far fa-bookmark"></i>';
                            // html += '</a>';
                            html += '</div>';
                            html += '<div class="pro-content">';
                            html += '<h3 class="title pb-4">';
                            // alert(this.name)
                            html += '<a href="' + base_url + 'product-details/' + this.id + '" tabindex="-1">' + this.name + '</a>';
                            html += '<span class="">(' + this.pharmacy_name + ')</span>';
                            html += '</h3>';
                            html += '<div class="row align-items-center">';
                            html += '<div class="col-lg-6">';
                            if (this.price != this.sale_price) {
                                html += '<span class="price">' + this.user_currency_sign + ' ' + this.price + '</span>';
                                html += '<span class="price-strike">  ' + this.user_currency_sign + '  ' + this.sale_price + '</span>';
                            } else {
                                html += '<span class="price">' + this.user_currency_sign + ' ' + this.price + '</span>';
                            }
                            html += '</div>';
                            html += '<div class="col-lg-6 text-right">';
                            html += '<a href="javascript:void(0);" onclick="add_cart(\'' + this.productid + '\')" class="cart-icon"><i class="fas fa-shopping-cart"></i></a>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';

                        });

                        if (obj.current_page_no == 1) {
                            $("#product-list").html(html);
                        } else {
                            $("#product-list").append(html);
                        }

                    } else {
                        var html = '<div class="col-md-12">' +
                            '<div class="health-item-product1 text-center">' +
                            '<div class="health-img-popular1">' +
                            '<div class="card">' +
                            '<div class="card-body">' +
                            '<p class="mb-0">' + lg_no_products_fou + '</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                        $("#product-list").html(html);
                    }




                    var minimized_elements = $('h4.minimize');
                    minimized_elements.each(function () {
                        var t = $(this).text();
                        if (t.length < 100)
                            return;
                        $(this).html(
                            t.slice(0, 100) + '<span>... </span><a href="#" class="more">' + lg_more + '</a>' +
                            '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">' + lg_less + '</a></span>'
                        );
                    });

                    $(".search-results").html('<span>' + obj.count + ' ' + lg_matches_for_you + '' + '</span>');
                    // $(".widget-title").html(obj.count+' Matches for your search');
                    if (obj.count == 0) {
                        $('#load_more_btn').addClass('d-none');
                        $('#no_more').removeClass('d-none');
                        return false;
                    }


                    if (obj.current_page_no == 1 && obj.count < 3) {
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
    get_products(0);
    $('#load_more_btn').click(function () {
        var page_no = $('#page_no_hidden').val();
        var current_page_no = 0;

        if (page_no == 1) {
            current_page_no = 2;
        } else {
            current_page_no = Number(page_no) + 1;
        }
        $('#page_no_hidden').val(current_page_no);
        get_products(1);
    });



    function add_cart(product_id) {
        //alert("add_card")
        var cart_qty = $('#cart_qty').val();

        $.ajax({
            url: base_url + 'home/add_cart',
            type: "POST",
            data: { product_id: product_id, cart_qty: cart_qty },
            cache: false,
            beforeSend: function () {
            },
            success: function (data) {
                //console.log(data)
                var obj = jQuery.parseJSON(data);
                if (obj.result == 'true') {
                    toastr.success(obj.msg);
                    $('.cart_count').html(obj.cart_count);

                } else {
                    toastr.error(obj.msg);
                    $('.cart_count').html(obj.cart_count);

                }
            }
        });
    }

    function increaseValue() {
        var value = parseInt(document.getElementById('cart_qty').value, 10);
        value = isNaN(value) ? 1 : value;
        value++;
        document.getElementById('cart_qty').value = value;
    }

    function decreaseValue() {
        var value = parseInt(document.getElementById('cart_qty').value, 10);
        value = isNaN(value) ? 1 : value;
        value < 1 ? value = 1 : '';
        value--;
        document.getElementById('cart_qty').value = value;
    }


    function increment_quantity(cart_id) {
        var inputQuantityElement = $("#input-quantity-" + cart_id);
        var newQuantity = parseInt($(inputQuantityElement).val()) + 1;
        save_to_db(cart_id, newQuantity);
    }

    function decrement_quantity(cart_id) {
        var inputQuantityElement = $("#input-quantity-" + cart_id);
        if ($(inputQuantityElement).val() > 1) {
            var newQuantity = parseInt($(inputQuantityElement).val()) - 1;
            save_to_db(cart_id, newQuantity);
        }
    }


    function remove_cart(id) {
        $.ajax({
            url: base_url + 'home/remove_cart',
            type: "POST",
            data: { id: id },
            cache: false,
            beforeSend: function () {
                $("#loading").show();
            },
            success: function (data) {
                cart_count();
                cart_lists();

            }
        });
    }




    function save_to_db(cart_id, new_quantity) {
        var inputQuantityElement = $("#input-quantity-" + cart_id);
        $.ajax({
            url: base_url + 'home/update_cart',
            data: "cart_id=" + cart_id + "&new_quantity=" + new_quantity,
            type: 'post',
            beforeSend: function () {
                $("#loading").show();
            },

            success: function (response) {

                cart_count();
                cart_lists();

            }
        });
    }

    function cart_count() {
        $.get(base_url + 'home/cart_count', function (data) {
            var obj = jQuery.parseJSON(data);
            $('.cart_count').html(obj.cart_count);
        });
    }

    function cart_lists() {

        $.get(base_url + 'cart-list', function (data) {
            $('#loading').hide();
            var obj = jQuery.parseJSON(data);
            $('.cart_lists').html(obj.cart_list);
            $('.checkout_cart_lists').html(obj.checkout_html);
            $('.checkout_cart_html').html(obj.checkout_cart_html);
            $('#cart_pay_btn').hide();
            if (obj.cart_count == 1) {
                $('#cart_pay_btn').show();
            }
        });
    }

    cart_lists();

}



if (modules == 'home') {
    if (pages == 'doctors_mapsearch') {

        $(document).ready(function () {
            $('#services').multiselect({
                nonSelectedText: lg_select_services,
                enableClickableOptGroups: true,
                enableCollapsibleOptGroups: true,
                enableFiltering: true,
                includeSelectAllOption: true,
                includeResetOption: true,

            });

            $('#gender').multiselect({
                nonSelectedText: lg_select_gender,
                enableClickableOptGroups: true,
                enableCollapsibleOptGroups: true,
                enableFiltering: true,
                includeSelectAllOption: true,
                includeResetOption: true,

            });


        });
        var locations = [];
        google.maps.visualRefresh = true;
        var slider, infowindow = null;
        var bounds = new google.maps.LatLngBounds();
        var map, current = 0;

        var icons = {
            'default': 'assets/img/marker.png'
        };



        function show() {
            infowindow.close();
            if (!map.slide) {
                return;
            }
            var next, marker;
            if (locations.length == 0) {
                return
            } else {
                next = 0;
            }

            current = next;
            marker = locations[next];
            setInfo(marker);
            // console.log(locations);
            infowindow.open(map, marker);
        }

        function reset_doctor() {
            $('#orderby').val('');
            $('#keywords').val('');
            $('#appointment_type').val('');
            $('#gender').val('');
            $('#appointment_type').val('');
            $('#specialization').val('');
            $('#lang').val('');
            $('#ethnicity').val('');
            $('#country').val('');
            $('#administrative_area_level_1').val('');
            $('#postal_code').val('');
            $('#locality').val('');
            $('#search_location').val('');
            $('#search_keywords').val('');
            $('#search_radius').val('');
            $('#s_unit').val('');
            $('#s_lat').val('');
            $('#s_long').val('');
            // $('#search_doctor_form')[0].reset();
            search_doctor(0);
            location.reload(true);
        }

        search_doctor(0);

        function search_doctor(load_more) {
            if (load_more == 0) {
                $('#page_no_hidden').val(1);
                locations = [];
            }

            console.log('load_more', load_more);

            var specialization_list = $('#services').val();
            var specialization = "";
            var len1 = length.specialization_list;
            var last_index1 = len1 - 1;

            $.each(specialization_list, function (i, val) {
                specialization += val;

                if (i === last_index1) {
                    specialization += "";
                } else {
                    specialization += ",";
                }
            });



            var gender_list = $('#gender').val();

            var gender = "";
            var len2 = length.gender_list;
            var last_index = len2 - 1;
            $.each(gender_list, function (i, val) {
                gender += val;

                if (i === last_index) {
                    gender += "";
                } else {
                    gender += ",";
                }
            });



            var lang = $('#lang').val();
            var ethnicity = $('#ethnicity').val();
            var order_by = $('#orderby').val();
            var page = $('#page_no_hidden').val();

            var appointment_type = $("#appointment_type").val();
            //var city = $("#city").val();
            //var state = $("#state").val();
            //var country = $("#country").val();
            var keywords = $("#search_keywords").val();
            var s_country = $("#country").val();
            var s_state = $("#administrative_area_level_1").val();
            var s_locality = $("#locality").val();
            var s_postal_code = $("#postal_code").val();
            var s_lat = $("#s_lat").val();
            var s_long = $("#s_long").val();
            var role = $("#role").val();
            var search_radius = $("#search_radius").val();
            var s_unit = $("#s_unit").val();
            var services = $('#services').val();
            var sub_services = $('#sub_services').val();
            var s_location = $('#search_location').val();
            //$('#search-error').html('');
            
            $.ajax({
                url: base_url + 'search-veterinary',
                type: 'POST',
                data: {
                    appointment_type: appointment_type,
                    gender: gender,
                    specialization: specialization,
                    lang: lang,
                    ethnicity: ethnicity,
                    order_by: order_by,
                    page: page,
                    role: role,
                    keywords: keywords,
                    postal_code: s_postal_code,
                    s_lat: s_lat,
                    s_long: s_long,
                    s_radius: search_radius,
                    s_unit: s_unit,
                    s_location: s_location,
                    city: s_locality,
                    //citys: citys,
                    state: s_state,
                    country: s_country,
                    services: services,
                    sub_services: sub_services
                },
                beforeSend: function () {
                    $('#doctor-search').attr('disabled', true);
                    $('#doctor-search').html('<div class="spinner-border text-light" role="status"></div>');
                    // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
                },
                success: function (response) {
                    $('#doctor-search').attr('disabled', false);
                    $('#doctor-search').html(lg_search3);
                    if (response) {
                        
                        var obj = $.parseJSON(response);

                        if (obj.data.length >= 1) {
                            var html = '';
                            $(obj.data).each(function () {

                                var services = '';
                                var view_more = '';
                                //var service_latest = '';
                                if (this.services != null && this.services.length != 0) {
                                    var service = this.services.split(',');
                                    for (var i = 0; i < service.length; i++) {
                                        services += '<span>' + service[i] + '</span>';

                                        if (i == 2) {
                                            view_more = '<a href="' + base_url + 'doctor-preview/' + this.username + '">' + lg_view_more + '</a>';
                                            break;
                                        }
                                    }
                                }
                                var clinic_images = '';

                                var clinic_images_file = $.parseJSON(this.clinic_images);
                                $.each(clinic_images_file, function (key, item) {
                                    var userid = item.user_id;
                                    clinic_images += '<li> <a href="' + item.clinic_image + '" data-fancybox="gallery"> <img src="' + item.clinic_image + '" alt="Feature"> </a> </li>';

                                });
                                html += `<div class="card">
                                    <div class="card-body">
                                    <div class="doctor-widget">
                                    <div class="doc-info-left">
                                        <div class="doctor-img">
                                            <a href="${base_url}doctor-preview/${this.username}">
                                                <img src="${this.profileimage}" class="img-fluid" alt="User Image" />
                                            </a>
                                        </div>
                                    <div class="doc-info-cont">
                                        <h4 class="doc-name">
                                            <a href="${base_url}doctor-preview/${this.username}" class="text-decoration-none">
                                                ${lg_dr} ${this.first_name} ${this.last_name}
                                            </a>
                                        </h4>
                                        <h5 class="doc-department">${this.speciality}</h5>
                                        <div class="rating">`;
                                for (var j = 1; j <= 5; j++) {
                                    if (j <= this.rating_value) {
                                        html += '<i class="fas fa-star filled"></i>';
                                    } else {
                                        html += '<i class="fas fa-star"></i>';
                                    }
                                }
                                html += '<span class="d-inline-block average-rating">(' + this.rating_count + ')</span>' +
                                    '</div>' +
                                    '<div class="clinic-details">' +
                                    '<p class="doc-location" style="font-size:14px"><i class="fas fa-map-marker-alt"></i> ' + this.cityname + ', ' + this.countryname + '</p>' +
                                    ' <ul class="clinic-gallery">' + clinic_images + '</ul>' +
                                    '</div>' +
                                    '<div class="Tags">' +
                                    '<div class="clinic-services">' + services + '</div>' +
                                    '</div>' + view_more +
                                    '</div>' +
                                    '</div>' +
                                    '<div class="doc-info-right">' +
                                    '<div class="clini-infos">' +
                                    '<ul>' +
                                    '<li><i class="far fa-comment"></i>' + this.rating_count + ' ' + lg_feedback + '</li>' +
                                    '<li><i class="fas fa-map-marker-alt"></i> ' + this.cityname + ', ' + this.countryname + '</li>' +
                                    '<li><i class="far fa-money-bill-alt"></i> ' + this.amount + ' </li>';


                                html += '</ul>' +
                                    '</div>' +
                                    '<div class="clinic-booking">' +
                                    '<a class="view-pro-btn" href="' + base_url + 'doctor-preview/' + this.username + '">' + lg_view_profile + '</a>';

                                if (role != 5 & role != 4 & role != 6 & role != 1) {
                                    html += '<a class="apt-btn" href="' + base_url + 'book-appoinments/' + this.username + '">' + lg_book_appointmen + '</a>';
                                }

                                html += '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';



                                location_items = {};
                                location_items["id"] = this.id;
                                location_items["doc_name"] = lg_dr + ' ' + this.first_name + ' ' + this.last_name;
                                location_items["speciality"] = services;
                                location_items["address"] = this.cityname + ', ' + this.countryname;
                                location_items["next_available"] = "Available on Fri, 22 Mar";
                                location_items["amount"] = this.amount;
                                location_items["lat"] = this.latitude;
                                location_items["lng"] = this.longitude;
                                location_items["icons"] = "default";
                                location_items["profile_link"] = base_url + 'doctor-preview/' + this.username;
                                location_items["total_review"] = this.rating_count + ' ' + lg_feedback;
                                location_items["image"] = this.profileimage;

                                locations.push(location_items);


                            });

                           
                            if (obj.current_page_no == 1) {
                                $("#doctor-list").html(html);
                                initialize(obj.least_lat, obj.most_long);
                            } else {
                                $("#doctor-list").append(html);
                                setMarkers(map, locations);
                            }

                        } else {
                            location_items = {};
                            locations.push(location_items);
                            $("#map").hide();
                            var html = '<div class="card">' +
                                '<div class="card-body">' +
                                '<div class="doctor-widget">' +
                                '<p>' + lg_no_doctors_foun + '</p>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                            $("#doctor-list").html(html);
                        }




                        var minimized_elements = $('h4.minimize');
                        minimized_elements.each(function () {
                            var t = $(this).text();
                            if (t.length < 100)
                                return;
                            $(this).html(
                                t.slice(0, 100) + '<span>... </span><a href="#" class="more">' + lg_more + '</a>' +
                                '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">' + lg_less + '</a></span>'
                            );
                        });
                        // $('a.more', minimized_elements).click(function(event) {
                        //   event.preventDefault();
                        //   $(this).hide().prev().hide();
                        //   $(this).next().show();
                        // });

                        // $('a.less', minimized_elements).click(function(event) {
                        //   event.preventDefault();
                        //   $(this).parent().hide().prev().show().prev().show();
                        // });

                        $(".search-results").html('<span>' + obj.count + ' ' + lg_matches_for_you + '' + '</span>');
                        // $(".widget-title").html(obj.count+' Matches for your search');
                        if (obj.count == 0) {
                            $('#load_more_btn').addClass('d-none');
                            $('#no_more').removeClass('d-none');
                            return false;
                        }


                        if (obj.current_page_no == 1 && obj.count < 5) {
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
            search_doctor(1);
        });


        function initialize(lat, long) {
            $("#map").show();
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                zoom: 8,
                // center: new google.maps.LatLng(25.7616798, -80.1917902),
                center: new google.maps.LatLng(lat, long),
                //mapTypeControl: true,
                // mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                navigationControl: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP,

                styles: [{
                    elementType: 'geometry',
                    stylers: [{
                        color: '#232323'
                    }]
                },
                {
                    elementType: 'labels.text.stroke',
                    stylers: [{
                        color: '#232323'
                    }]
                },
                {
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#746855'
                    }]
                },
                {
                    featureType: 'administrative.locality',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#669933'
                    }]
                },
                {
                    featureType: 'poi',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#669933'
                    }]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#263c3f'
                    }]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#6b9a76'
                    }]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#38414e'
                    }]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry.stroke',
                    stylers: [{
                        color: '#212a37'
                    }]
                },
                {
                    featureType: 'road',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#9ca5b3'
                    }]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#746855'
                    }]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.stroke',
                    stylers: [{
                        color: '#1f2835'
                    }]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#f3d19c'
                    }]
                },
                {
                    featureType: 'transit',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#2f3948'
                    }]
                },
                {
                    featureType: 'transit.station',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#d59563'
                    }]
                },
                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{
                        color: '#151515'
                    }]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.fill',
                    stylers: [{
                        color: '#515c6d'
                    }]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.stroke',
                    stylers: [{
                        color: '#333333'
                    }]
                }
                ]

            };

            map = new google.maps.Map(document.getElementById('map'), mapOptions);
            map.slide = true;

            setMarkers(map, locations);
            infowindow = new google.maps.InfoWindow({
                content: "loading..."
            });

            google.maps.event.addListener(infowindow, 'closeclick', function () {
                infowindow.close();
            });


            slider = window.setTimeout(show, 3000);
        }

        function setInfo(marker) {
            var content =
                '<div class="profile-widget" style="width: 100%; display: inline-block;">' +
                '<div class="doc-img">' +
                '<a href="' + marker.profile_link + '" tabindex="0" target="_blank">' +
                '<img class="img-fluid" alt="' + marker.doc_name + '" src="' + marker.image + '">' +
                '</a>' +
                '</div>' +
                '<div class="pro-content">' +
                '<h3 class="title">' +
                '<a href="' + marker.profile_link + '" tabindex="0">' + marker.doc_name + '</a>' +
                '<i class="fas fa-check-circle verified"></i>' +
                '</h3>' +
                '<p class="speciality">' + marker.speciality + '</p>' +
                '<div class="rating">' +
                '<i class="fas fa-star"></i>' +
                '<i class="fas fa-star"></i>' +
                '<i class="fas fa-star"></i>' +
                '<i class="fas fa-star"></i>' +
                '<i class="fas fa-star"></i>' +
                '<span class="d-inline-block average-rating"> (' + marker.total_review + ')</span>' +
                '</div>' +
                '<ul class="available-info">' +
                '<li><i class="fas fa-map-marker-alt"></i> ' + marker.address + ' </li>' +
                //'<li><i class="far fa-clock"></i> ' + marker.next_available + '</li>'+
                '<li><i class="far fa-money-bill-alt"></i> ' + marker.amount + '</li>' +
                '</ul>' +
                '</div>' +
                '</div>';
            infowindow.setContent(content);
        }



        function setMarkers(map, markers) {
            for (var i = 0; i < markers.length; i++) {
                var item = markers[i];
                var latlng = new google.maps.LatLng(item.lat, item.lng);
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    doc_name: item.doc_name,
                    address: item.address,
                    speciality: item.speciality,
                    // next_available: item.next_available,
                    amount: item.amount,
                    profile_link: item.profile_link,
                    total_review: item.total_review,
                    animation: google.maps.Animation.DROP,
                    icon: icons[item.icons],
                    image: item.image
                });
                bounds.extend(marker.position);
                markers[i] = marker;
                google.maps.event.addListener(marker, "click", function () {
                    setInfo(this);
                    infowindow.open(map, this);
                    window.clearTimeout(slider);
                });
            }
            map.fitBounds(bounds);
            google.maps.event.addListener(map, 'zoom_changed', function () {
                if (map.zoom > 16)
                    map.slide = false;
            });
        }

    }



    if (modules == 'home' && pages == 'pharmacy_search_bydoctor') {

        //search_doctor(0);

        search_pharmacy(0);

        $('#load_more_btn_pharmacy').click(function () {
            var page_no = $('#page_no_hidden').val();
            var current_page_no = 0;

            if (page_no == 1) {
                current_page_no = 2;
            } else {
                current_page_no = Number(page_no) + 1;
            }
            $('#page_no_hidden').val(current_page_no);
            search_pharmacy(1);
        });







    }
}



function reset_pharmacy() {
    $('#orderby').val('');
    $('#search_pharmacy_form')[0].reset();
    search_pharmacy(0);
}



function search_pharmacy(load_more) {

    if (load_more == 0) {
        $('#page_no_hidden').val(1);
    }
    var order_by = $('#orderby').val();
    var page = $('#page_no_hidden').val();
    var city = $("#city").val();
    var state = $("#state").val();
    var country = $("#country").val();

    // Service filter
    var hrsopen = $('#24hrsopen').prop('checked') ? 'yes' : '';
    var home_delivery = $('#home_delivery').prop('checked') ? 'yes' : '';

    $.ajax({
        url: base_url + 'home/search_pharmacy',
        type: 'POST',
        data: {
            order_by: order_by,
            page: page,
            city: city,
            state: state,
            country: country,
            hrsopen: hrsopen,
            home_delivery: home_delivery
        },
        beforeSend: function () {
            $("#loading").show();
        },
        complete: function () {
            $("#loading").hide();
        },
        success: function (response) {

            //alert("Sucesss");

            if (response) {
                var obj = $.parseJSON(response);
                if (obj.data.length >= 1) {
                    var html = '';
                    var no = 1;
                    $(obj.data).each(function () {

                        var pharmacy_name = (this.pharmacy_name != '' && this.pharmacy_name != null) ? this.pharmacy_name : '';
                        var profileimage = (this.profileimage != '' && this.profileimage != null) ? this.profileimage : '';
                        var phonecode = (this.phonecode != '' && this.phonecode != null) ? this.phonecode : '';
                        var mobileno = (this.mobileno != '' && this.mobileno != null) ? this.mobileno : '';
                        var address1 = (this.address1 != '' && this.address1 != null) ? this.address1 : '';
                        var address2 = (this.address2 != '' && this.address2 != null) ? this.address2 : '';
                        var city = (this.city != '' && this.city != null) ? this.city : '';
                        var statename = (this.statename != '' && this.statename != null) ? this.statename : '';
                        var country = (this.country != '' && this.country != null) ? this.country : '';
                        var pharmacy_opens_at = (this.pharmacy_opens_at != '' && this.pharmacy_opens_at != null) ? this.pharmacy_opens_at : '';

                        html += '<div class="card">';
                        html += '<div class="card-body">';
                        html += '<div class="doctor-widget">';
                        html += '<div class="doc-info-left">';
                        html += '<div class="doctor-img1">';
                        html += '<a href="' + base_url + 'pharmacy-preview/' + (this.link_id) + '">';
                        html += '<img src="' + profileimage + '" class="img-fluid" alt="' + pharmacy_name + '">';
                        html += '</a>';
                        html += '</div>';
                        html += '<div class="doc-info-cont">';
                        html += '<h4 class="doc-name mb-2"><a  href="' + base_url + 'home/view_pharmacy_products/' + btoa(this.id) + '" view_pharmacy_products(' + btoa(this.id) + ')>' + pharmacy_name + '</a></h4>';
                        html += '<div class="clinic-details">';
                        html += '<div class="clini-infos pt-3">';

                        html += '<p class="doc-location mb-2" style="font-size:14px"><i class="fas fa-phone-volume mr-1"></i> (+' + phonecode + ') - ' + mobileno + '</p>';
                        if (address1 !== '') {
                            html += '<p class="doc-location mb-2 text-ellipse" style="font-size:14px"><i class="fas fa-map-marker-alt mr-1"></i> ' + address1 + ' </p>';
                        }
                        if (address2 !== '') {
                            html += '<p class="doc-location mb-2" style="font-size:14px"><i class="fas fa-chevron-right mr-1"></i> ' + address2 + '</p>';
                        }
                        html += '<p class="doc-location mb-2" style="font-size:14px"><i class="fas fa-chevron-right mr-1"></i> ' + city + ', ' + statename + ', ' + country + '</p>';

                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="doc-info-right">';
                        html += '<div class="clinic-booking">';
                        html += '<a class="view-pro-btn" href="' + base_url + 'pharmacy-preview/' + (this.link_id) + '">' + lg_view_details + '</a>';
                        html += '<a class="apt-btn" href="' + base_url + 'home/view_pharmacy_products/' + btoa(this.id) + '" view_pharmacy_products(' + this.id + ')>' + lg_browse_products + '</a>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';


                        no++;
                    });

                    if (obj.current_page_no == 1) {
                        $("#pharmacy-list").html(html);
                    } else {
                        $("#pharmacy-list").append(html);
                    }
                } else {
                    var html = '<div class="card" style="width:100%">' +
                        '<div class="card-body">' +
                        '<div class="doctor-widget">' +
                        '<p>' + lg_no_pharmacy_fou + '</p>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    $("#pharmacy-list").html(html);
                }
                var minimized_elements = $('h4.minimize');
                minimized_elements.each(function () {
                    var t = $(this).text();
                    if (t.length < 100)
                        return;
                    $(this).html(
                        t.slice(0, 100) + '<span>... </span><a href="#" class="more">' + lg_more + '</a>' +
                        '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">' + lg_less + '</a></span>'
                    );
                });

                $(".search-results").html('<span>' + obj.count + ' ' + lg_matches_for_you + '' + '</span>');
                // $(".widget-title").html(obj.count+' Matches for your search');
                if (obj.count == 0) {
                    $('#load_more_btn_pharmacy').addClass('d-none');
                    $('#no_more').removeClass('d-none');
                    return false;
                }


                if (obj.current_page_no == 1 && obj.count < 3) {
                    $('page_no_hidden').val(1);
                    $('#load_more_btn_pharmacy').addClass('d-none');
                    $('#no_more').removeClass('d-none');
                    return false;
                }



                if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
                    $('#load_more_btn_pharmacy').removeClass('d-none');
                    $('#no_more').addClass('d-none');
                } else {
                    $('#load_more_btn_pharmacy').addClass('d-none');
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
    search_doctor(1);
});



if (modules == 'pharmacy') {

    if (pages == 'orderlist' || pages == 'pharmacyDashboard') {
        var products_table;
        //datatables
        products_table = $('#orders_table').DataTable({
            'ordering': true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + "ajax/orders-list",
                "type": "POST",
                "data": function (data) {

                },
                error: function () {
                    //window.location.href=base_url+'admin/dashboard';
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

        $(document).on('change', '.order_status', function () {
            // alert('test');exit;
            var id = $(this).attr('id');
            var status = $(this).val();
            $.post(base_url + "pharmacy/changeOrderStatus", { id: id, status: status }, function (data) {
                toastr.success(lg_status_updated_);
                products_table.ajax.reload(null, false);
            });
        });

    }

}


if (modules == 'home' && pages == 'notification') {
    function delete_notification(id) {


        $.ajax({
            type: "POST",
            url: base_url + 'dashboard/delete_notification',
            data: { id: id },
            dataType: "json",
            success: function (response) {
                // return false;
                if (response.status == '200') {
                    toastr.success(response.msg);

                    setTimeout(function () { location.reload(true); }, 1000);
                } else {
                    toastr.error(response.msg);
                }



            }
        });


    }

    notification(0);


    function notification(load_more) {

        if (load_more == 0) {
            $('#page_no_hidden').val(1);
        }


        var page = $('#page_no_hidden').val();
        var order_by = 'DESC';
        var keywords = $("#keywords").val();



        //$('#search-error').html('');

        $.ajax({
            url: base_url + 'search-notification',
            type: 'POST',
            data: {
                page: page,
                order_by: order_by,
                keywords: keywords,
            },
            beforeSend: function () {
                $("#loading").show();
            },
            complete: function () {
                $("#loading").hide();
            },
            success: function (response) {
                //$('#doctor-list').html(''); 
                if (response) {

                    var obj = $.parseJSON(response);
                    if (obj.data.length >= 1) {
                        var html = '';

                        $(obj.data).each(function () {


                            html += '<div class="noti-list">' +
                                '<div class="noti-avatar">' +
                                '<img alt="avatar" src="' + this.profile_image + '">' +
                                '</div>' +
                                '<div class="noti-content">' +
                                '<span class="truncate head-notifications">' + this.from_name + '</span>' +
                                '<span class="notifications-time">' + this.notification_date + '</span>' +
                                '<div class="clearfix"></div>' +
                                '<p class="truncate">' + this.text + '</p>' +
                                '<p class="truncate">' + this.to_name + '</p>' +
                                '</div>' +
                                // '<div class="noti-delete">'+
                                //   '<button class="text-danger" type="button"><i class="fa fa-trash" onclick="delete_notification('+this.id+')"></i></button>'+
                                // '</div>'+
                                '</div>';


                        });

                        if (obj.current_page_no == 1) {
                            $("#notification-list").html(html);
                        } else {

                            $("#notification-list").append(html);
                        }

                    }
                    else {
                        var html = '<div class="col-md-12">' +
                            '<div class="card">' +
                            '<div class="card-body">' +
                            '<p class="mb-0">No Notifications found</p>' +
                            '</div>' +
                            '</div>';
                        '</div>';

                        $("#notification-list").html(html);
                    }

                    var minimized_elements = $('h4.minimize');
                    minimized_elements.each(function () {
                        var t = $(this).text();
                        if (t.length < 100) return;
                        $(this).html(
                            t.slice(0, 100) + '<span>... </span><a href="#" class="more">Load More</a>' +
                            '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">Load less</a></span>'
                        );
                    });


                    $(".search-results").html(obj.count);
                    if (obj.count == 0) {
                        $('#load_more_btn').addClass('d-none');
                        $('#no_more').removeClass('d-none');
                        return false;
                    }


                    if (obj.current_page_no == 1 && obj.count < 5) {
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
    $(document).on('click', '#load_more_btn', function () {
        var page_no = $('#page_no_hidden').val();
        var current_page_no = 0;

        if (page_no == 1) {
            current_page_no = 2;
        } else {
            current_page_no = Number(page_no) + 1;
        }
        $('#page_no_hidden').val(current_page_no);
        notification(page_no);
    });
}

if (modules == 'lab' && pages == 'labs_searchmap') {
    function reset_lab() {
        $('#orderby').val('');
        $('#keywords').val('');
        $('#country').val('');
        $('#state').val('');
        $('#city').val('');
        search_lab(0);
    }
    search_lab(0);
    function search_lab(load_more) {

        if (load_more == 0) {
            $('#page_no_hidden').val(1);
        }
        var page = $('#page_no_hidden').val();
        var city = $("#city").val();
        var state = $("#state").val();
        var country = $("#country").val();
        var keywords = $("#keywords").val();

        $.ajax({
            url: base_url + 'search-lab',
            type: 'POST',
            data: {
                page: page,
                keywords: keywords,
                city: city,
                state: state,
                country: country
            },
            // beforeSend: function () {
            //     $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status" style="text-align: center;margin: 60px;"></div>');
            //     $("#doctor-list").css('text-align', 'center');
            // },
            // beforeSend: function () {
            //     $('#doctor-list').attr('disabled', true);
            //     $('#doctor-list').html('<div class="spinner-border text-light" role="status"></div>');
            // },
            beforeSend: function () {
                // $('#doctor-list').attr('disabled', true);
                // $('#doctor-list').html('<div class="spinner-border text-light" role="status"></div>');
                $("#loading").show();
            },
            complete: function () {
                $("#loading").hide();
            },
            success: function (response) {
                if (response) {
                    var obj = $.parseJSON(response);
                    if (obj.data.length >= 1) {
                        var html = '';
                        $(obj.data).each(function () {
                            html += '<div class="card">' +
                                '<div class="card-body">' +
                                '<div class="doctor-widget">' +
                                '<div class="doc-info-left">' +
                                '<div class="doctor-img">' +
                                '<a href="' + base_url + 'lab-tests/' + this.username + '">' +
                                '<img src="' + this.profileimage + '" class="img-fluid" alt="User Image">' +
                                '</a>' +
                                '</div>' +
                                '<div class="doc-info-cont">' +
                                '<h4 class="doc-name"><a href="' + base_url + 'lab-tests/' + this.username + '">' + this.first_name + ' ' + this.last_name + '</a></h4>' +
                                '<span>Lab</span></div>' +
                                '</div>' +
                                '<div class="doc-info-right">' +
                                '<div class="clini-infos">' +
                                '<ul>' +
                                '<li><i class="fas fa-map-marker-alt"></i> ' + this.cityname + ', ' + this.countryname + '</li>' +
                                '</ul>' +
                                '</div>' +
                                '<div class="clinic-booking">' +
                                // '<a class="view-pro-btn" href="#">'+lg_view_profile+'</a>'+
                                '<a class="apt-btn" href="' + base_url + 'lab-tests/' + this.username + '">View Tests</a>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';

                        });

                        if (obj.current_page_no == 1) {
                            // alert('hi');
                            $("#doctor-list").html(html);
                        } else {
                            // alert('hii');
                            $("#doctor-list").append(html);
                        }
                    }
                    else {
                        // alert('hiii');
                        var html = '<div class="card">' +
                            '<div class="card-body">' +
                            '<div class="doctor-widget">' +
                            '<p>No labs found !!!</p>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                        $("#doctor-list").html(html);
                    }

                    var minimized_elements = $('h4.minimize');
                    minimized_elements.each(function () {
                        var t = $(this).text();
                        if (t.length < 100) return;
                        $(this).html(
                            t.slice(0, 100) + '<span>... </span><a href="#" class="more">' + lg_more + '</a>' +
                            '<span style="display:none;">' + t.slice(100, t.length) + ' <a href="#" class="less">' + lg_less + '</a></span>'
                        );
                    });

                    $(".search-results").html('<span>' + obj.count + ' ' + lg_matches_for_you + '' + '</span>');
                    // $(".widget-title").html(obj.count+' Matches for your search');
                    if (obj.count == 0) {
                        $('#load_more_btn_lab').addClass('d-none');
                        $('#no_more').removeClass('d-none');
                        return false;
                    }
                    if (obj.current_page_no == 1 && obj.count < 5) {
                        $('page_no_hidden').val(1);
                        $('#load_more_btn_lab').addClass('d-none');
                        $('#no_more').removeClass('d-none');
                        return false;
                    }

                    if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
                        $('#load_more_btn_lab').removeClass('d-none');
                        $('#no_more').addClass('d-none');
                    } else {
                        $('#load_more_btn_lab').addClass('d-none');
                        $('#no_more').removeClass('d-none');
                    }

                }
            }

        });
    }
    $('#load_more_btn_lab').click(function () {
        var page_no = $('#page_no_hidden').val();
        var current_page_no = 0;

        if (page_no == 1) {
            current_page_no = 2;
        } else {
            current_page_no = Number(page_no) + 1;
        }
        $('#page_no_hidden').val(current_page_no);
        search_lab(1);
    });

}

if (modules == 'lab' && pages == 'lab_tests_preview') {
    var maxDate = $('#maxDate').val();
    $('#lab_test_book_date').datepicker({
        format: 'yyyy-mm-dd',
        startDate: 'today',
        //endDate:maxDate,
        autoclose: true,
        todayHighlight: true
    });

    $(document).on('click', '.lab_test_book_btn', function () {
        booking_date = $('#lab_test_book_date').val();
        lab_id = $('#lab_id').val();
        if ($(".lab_test_chk:checked").length == 0) {
            toastr.error('Please select atleast one lab test.');
            return false;
        }
        if ($.trim(booking_date) == '') {
            toastr.error('Please select the booking date.');
            return false;
        }

        // get checked checkbox values..
        var booked_test_arr = [];
        $('.lab_test_chk:checked').each(function () {
            booked_test_arr.push($(this).val());
        });

        //console.log(booked_test_arr);
        if (booked_test_arr.length > 0) {
            $.ajax({
                url: base_url + 'set-booked-session-lab-test',
                type: 'POST',
                data: {
                    booking_ids: JSON.stringify(booked_test_arr),
                    lab_id: lab_id,
                    lab_username: $('#lab_username').val(),
                    lab_test_date: $('#lab_test_book_date').val()
                },
                beforeSend: function () {
                    $('#lab_test_book_btn').attr('disabled', true);
                    $('#lab_test_book_btn').html('<div class="spinner-border text-success text-center" role="status"></div>');
                },
                success: function (res) {
                    $('#lab_test_book_btn').attr('disabled', false);
                    $('#change_btn').html('Proceed to pay');
                    //console.log(response); return false;
                    var obj = JSON.parse(res);
                    if (obj.status === 200) {
                        setTimeout(function () { window.location = base_url + 'lab-checkout'; }, 1000);
                    }
                    else {
                        toastr.success('Error while booking the lab tests.');
                        setTimeout(function () {
                            window.location.href = base_url + "dashboard";
                        }, 2000);

                    }
                }
            });
        }




    });
}

if ((modules == 'lab' && pages == 'checkout') || pages == "lab-checkout") {

    function appoinment_payment(type) {
        // var terms_accept=$("input[name='terms_accept']:checked").val();
        var terms_accept = 1;
        if (terms_accept == '1') {
            if (type == 'paypal') {
                $('#payment_formid').submit();

            }else if (type == 'razorpay') {
                razorpay();
            }
            else {
                var payment_method = $("input[name='payment_methods']:checked").val();
                if (payment_method != 'Card Payment') {
                    $("#my_book_appoinment").click();
                }

                return false;
            }
        }
        else {
            toastr.warning(lg_please_accept_t);
        }
    }

    var stripe = Stripe(stripe_api_key);

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', { style: style });

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
            $('#stripe_pay_btn').attr('disabled', false);
            $('#stripe_pay_btn').html('Confirm and Pay');
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        $('#stripe_pay_btn').attr('disabled', true);
        $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(stripePaymentMethodHandler);
    });
    $(document).ready(function () {

        $('#my_book_appoinment').click(function (e) {

            $.ajax({

                url: base_url + 'add-lab-appoinment',

                data: $('#payment_formid').serialize(),

                type: 'POST',

                dataType: 'JSON',

                beforeSend: function () {

                    $('#pay_button').attr('disabled', true);
                    $('#pay_button').html('<div class="spinner-border text-light" role="status"></div>');

                }, success: function (response) {

                    if (response.status == '200') {

                        toastr.success(lg_transaction_suc);
                        setTimeout(function () {
                            window.location.href = base_url + 'payment-success';
                        }, 2000);

                    } else {
                        toastr.error(lg_transaction_fai1);
                        setTimeout(function () {
                            window.location.href = base_url + 'dashboard';
                        }, 2000);
                    }



                },

                error: function (error) {

                    // console.log(error);

                }

            });

            e.preventDefault();

        });
        

    });
    
    function stripePaymentMethodHandler(result) {

        if (result.error) {
            $('#card-errors').text(result.error.message);
            $('#stripe_pay_btn').attr('disabled', false);
            $('#stripe_pay_btn').html('Confirm and Pay');
            // Show error in payment form
        } else {
            //alert();
            $.ajax({
                url: base_url + 'lab/stripe-payment',
                data: { payment_method_id: result.paymentMethod.id },
                type: 'POST',
                dataType: 'JSON',
                beforeSend: function () {
                    $('#stripe_pay_btn').attr('disabled', true);
                    $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (response) {

                    handleServerResponse(response);

                },
                error: function (error) {
                    // console.log(error);
                }
            });

        }
    }
    function razorpay() {
        $('#razor_pay_btn').attr('disabled', true);
        $('#razor_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
        var amount = $('#amount').val();
        var currency_code = $('#currency_code').val();

        $.post(base_url + 'lab-razorpay-create', { amount: amount, currency_code: currency_code }, function (data) {

            $('#razor_pay_btn').attr('disabled', false);
            $('#razor_pay_btn').html(lg_confirm_and_pay2);
            var obj = jQuery.parseJSON(data);
            var options = {
                "key": obj.key_id,
                "amount": obj.amount,
                "currency": obj.currency,
                "name": obj.sitename,
                "description": "Booking Slot",
                "image": obj.siteimage,
                "order_id": obj.order_id,
                "handler": function (response) {
                    razorpay_appoinments(response.razorpay_payment_id, response.razorpay_order_id, response.razorpay_signature);
                },
                "prefill": {
                    "name": obj.patientname,
                    "email": obj.email,
                    "contact": obj.mobileno
                },
                "notes": {
                    "address": "Razorpay Corporate Office"
                },
                "theme": {
                    "color": "#15558d"
                }
            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
        });
    }

    function razorpay_appoinments(payment_id, order_id, signature) {
        $('#payment_id').val(payment_id);
        $('#order_id').val(order_id);
        $('#signature').val(signature);

        $.ajax({
            url: base_url + 'lab-razorpay-payment',
            data: $('#payment_formid').serialize(),
            type: 'POST',
            dataType: 'JSON',
            beforeSend: function () {
                $('#razor_pay_btn').attr('disabled', true);
                $('#razor_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
            },
            success: function (response) {
                // $('.overlay').hide();
                if (response.status == '200') {
                    toastr.success(lg_transaction_suc);
                    setTimeout(function () {
                        window.location.href = base_url + 'patient';
                    }, 2000);
                } else {
                    toastr.error(lg_transaction_fai1);
                    setTimeout(function () {
                        window.location.href = base_url + 'patient';
                    }, 2000);
                }
            },
            error: function (error) {
                // console.log(error);
            }
        });
    }
    

    /**
     * Lab Appointment Booking With Card Payment
     */
    function handleServerResponse(response) {
        if (response.status == '500') {
            toastr.error(response.message);
            $('#stripe_pay_btn').attr('disabled', false);
            $('#stripe_pay_btn').html('Confirm and Pay');
        } else if (response.status == '201') {
            // Use Stripe.js to handle required card action
            stripe.handleCardAction(
                response.payment_intent_client_secret
            ).then(handleStripeJsResult);
        } else {
            if (response.status == '200') {
                toastr.success("Lab Appointment booked successfully");
                setTimeout(function () {
                    window.location.href = base_url + 'patient/lab-appointments';
                }, 2000);

            } else {
                toastr.error(lg_something_went_1);
                setTimeout(function () {
                    window.location.href = base_url + 'patient';
                }, 2000);
            }
        }
    }

    function handleStripeJsResult(result) {

        if (result.status == '500') {
            toastr.error(result.message);
        } else {

            $.ajax({
                url: base_url + 'lab/stripe_payment',
                data: { payment_intent_id: result.paymentIntent.id },
                type: 'POST',
                dataType: 'JSON',
                beforeSend: function () {
                    $('#stripe_pay_btn').attr('disabled', true);
                    $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (response) {

                    handleServerResponse(response);

                },
                error: function (error) {
                    // console.log(error);
                }
            });

        }
    }

    // function stripeTokenHandler(token) {
    //     $('#access_token').val(token.id);
    //     $.ajax({
    //         url: base_url+'lab/stripe_pay',
    //         data: $('#payment_formid').serialize(),      
    //         type: 'POST',
    //         dataType: 'JSON',
    //         beforeSend: function(){
    //             $('#stripe_pay_btn').attr('disabled',true);
    //             $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
    //         },
    //         success: function(response){
    //             // $('.overlay').hide();
    //             if(response.status=='200')
    //             {
    //                 toastr.success('Your lab tst booked successfully.');
    //                 setTimeout(function() {
    //                     window.location.href=base_url+'dashboard';
    //                 }, 2000);
    //             }
    //             else
    //             {
    //                 toastr.error('Lab booking Failled');
    //                 setTimeout(function() {
    //                     window.location.href=base_url+'dashboard';
    //                 }, 2000);
    //             }
    //         },
    //         error: function(error){
    //             console.log(error);
    //         }

    //     });
    // }
    $(document).ready(function () {
        $('input[type=radio][name=payment_methods]').change(function () {
            if (this.value == 'Card Payment') {
                $('.stripe_payment').show();
                $('.paypal_payment').hide();
                $('.clinic_payment').hide();
                $('.razorpay_payment').hide();
                $('#payment_method').val('Card Payment');

            }
            else if (this.value == 'PayPal') {
                $('.stripe_payment').hide();
                $('.paypal_payment').show();
                $('.razorpay_payment').hide();
                $('.clinic_payment').hide();
                $('#payment_method').val('Card Payment');

            }
            else if (this.value == 'Pay on Arrive') {
                $('.stripe_payment').hide();
                $('.paypal_payment').hide();
                $('.clinic_payment').show();
                $('.razorpay_payment').hide();
                $('#payment_method').val('Pay on Arrive');

            }
            else if (this.value == 'Razorpay') {
                $('.stripe_payment').hide();
                $('.paypal_payment').hide();
                $('.clinic_payment').hide();
                $('.razorpay_payment').show();
                $('#payment_method').val('Razorpay');

            }
            else {
                $('.stripe_payment').hide();
                $('.paypal_payment').hide();
                $('#payment_method').val('');
            }
        });
    });


}

if (modules == 'lab' && (pages == 'lab_tests' || pages == 'lab_appointment_list')) {

    function add_lab_test() {

        $('[name="method"]').val('insert');
        $('#lab_form')[0].reset(); // reset form on modals
        $('#lab_modal').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add new lab test'); // Set Title to Bootstrap modal title
    }
    $(document).ready(function () {
        //datatables
        var lab_table;

        lab_table = $('#lab_table').DataTable({
            'ordering': true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'lab/lab-list',
                "type": "POST",
                "data": function (data) {
                },
                error: function () {

                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0, 4], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });


        // });

        function lab_table_list() {
            lab_table.ajax.reload(null, false);
        }



        // $(document).ready(function () {        

        /*submit form ajax template*/
        $("#lab_form").submit(function (e) {
            e.preventDefault();
        }).validate({
            rules: {
                lab_test_name: {
                    required: true,
                    maxlength: 100,
                    accept_chars: true,
                },
                amount: {
                    required: true,
                    maxlength: 100,
                    number: true,
                },
                duration: {
                    required: true,
                    maxlength: 100,
                    accept_chars: true,
                    number_req: true,
                },
                description: {
                    required: true,
                    maxlength: 500,
                    accept_chars: true,
                },

            },
            messages: {
                lab_test_name: {
                    required: lg_form_lab_test_testname_req,
                    maxlength: lg_form_lab_test_testname_max
                },
                amount: {
                    required: lg_form_lab_test_amount_req,
                    maxlength: lg_form_lab_test_amount_max
                },
                duration: {
                    required: lg_form_lab_test_duration_req,
                    maxlength: lg_form_lab_test_duration_max,
                    number_req: "Please enter numbers only"
                },
                description: {
                    required: lg_form_lab_test_description_req,
                    maxlength: lg_form_lab_test_description_max
                },

            },
            submitHandler: function (form) {

                // form data
                var formData = new FormData($('#lab_form')[0]);

                // ajax
                $.ajax({
                    url: base_url + 'lab/lab-test-save',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {

                        $('#btnlabtestsave').html('<div class="spinner-border text-light" role="status"></div>');
                        $('#btnlabtestsave').attr('disabled', true);

                    },
                    success: function (data) {
                        //console.log(data);
                        $('#btnlabtestsave').html('Save');
                        $('#btnlabtestsave').attr('disabled', false);
                        var obj = jQuery.parseJSON(data);
                        if (obj.status == 200) {
                            $('#lab_modal').modal('hide');
                            toastr.success(obj.msg);
                            lab_table_list();
                            // setTimeout(function(){ window.location.href=base_url+'lab-test'; }, 1000);
                            // lab_table.ajax.reload(null,false);
                        } else {
                            toastr.error(obj.msg);
                            // setTimeout(function(){ window.location.href=base_url+'lab-test'; }, 1000);
                            //lab_table.ajax.reload(null,false);
                        }
                    }

                });

                return false;
            }
        });
        /*submit form ajax template*/
    });

    function edit_lab_test(id) {
        $('[name="method"]').val('update');
        $('#lab_form')[0].reset(); // reset form on modals

        //Ajax Load data from ajax
        $.ajax({
            url: base_url + "lab/lab-test-edit/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {

                $('[name="id"]').val(data.id);
                $('[name="lab_test_name"]').val(data.lab_test_name);
                $('[name="amount"]').val(data.amount);
                $('[name="duration"]').val(data.duration);
                $('[name="description"]').val(data.description);
                $('#lab_modal').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit lab test'); // Set title to Bootstrap modal title

            },
            error: function () {
                // window.location.href=base_url+'dashboard';
            }
        });
    }

    function delete_lab_test(id, status) {
        $('#lab_test_id').val(id);
        $('#lab_test_status').val(status);
        $('#delete_lab_test').modal('show');
    }

    function lab_test_tables() {
        lab_table.ajax.reload(null, false);
    }

    function lab_test_delete() {
        var id = $('#lab_test_id').val();
        var status = $('#lab_test_status').val();
        $('#change_btn').attr('disabled', true);
        $('#change_btn').html('<div class="spinner-border text-light" role="status"></div>');
        $.post(base_url + 'lab/lab-test-delete', { id: id, status: status }, function (res) {
            $('#change_btn').attr('disabled', false);
            $('#change_btn').html('Yes');
            $('#delete_lab_test').modal('hide');
            var obj = jQuery.parseJSON(res);
            toastr.success(obj.msg);
            lab_table.ajax.reload(null, false);
            // setTimeout(function(){ window.location.href=base_url+'lab-test'; }, 1000);
        });
    }
}



if (pages == 'appointments') {

    var lab_appointment_table;
    lab_appointment_table = $('#lab_appointment_table').DataTable({
        'ordering': true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy': true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + 'lab/lab-appointment-list',
            "type": "POST",

            "data": function (data) {

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

    $(document).on('change', '.lab_appointment_status', function () {
        var id = $(this).attr('id');
        var status = $(this).val();
        $.post(base_url + "lab/change-appointment-status", { id: id, status: status }, function (data) {
            toastr.success(lg_status_updated_);
            lab_appointment_table.ajax.reload(null, false);
        });
    });

    function lab_appoinments_table() {
        lab_appointment_table.ajax.reload(null, false);
    }

    function view_docs(id) {

        var base = $("#base").val();

        $.ajax({
            url: base_url + "appoinments/get_docs/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {

                //console.log(data);
                //console.log("test");
                var li = '';
                $.each(data, function (index, value) {

                    var link = base + value;
                    //alert

                    var index = index + 1;

                    li += '<li><a target="_blank" href="' + link + '">' + index + ' . View Document</a></li>';

                });
                var len = data.length;

                if (len > 0) {
                    $('#links').html(li);
                } else {
                    $('#links').html("No records found");
                }

                $('#view_docs').modal('show');

            },
            error: function () {
                window.location.href = base_url + 'admin/dashboard';
                return false;
            }
        });

    }

}



if (modules == 'lab' && pages == 'appointments') {

    function upload_lab_docs(id) {
        // reset form values
        $('#upload_lab_form')[0].reset();

        $('[name="appointment_id"]').val(id);
        $('#upload_labdocs_modal').modal('show'); // show bootstrap modal
    }

    $('#upload_lab_form').submit(function (e) {
        e.preventDefault();
        var formData = new FormData($('#upload_lab_form')[0]);

        var oFile = document.getElementById("user_files_mr").files[0];
        if (!document.getElementById("user_files_mr").files[0]) {
            toastr.warning("Please select file");
            return false;
        }

        var fileInput = $('#user_files_mr')[0];
        $.each(fileInput.files, function (k, file) {
            formData.append('user_file[]', file);
        });

        $.ajax({
            url: base_url + 'lab/lab_upload_docs',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                if (oFile) {
                    $('#medical_btn').attr('disabled', true);
                    $('#medical_btn').html('<div class="spinner-border text-light" role="status"></div>');
                }
            },
            success: function (res) {
                $('#medical_btn').attr('disabled', false);
                $('#medical_btn').html(lg_submit);
                $('#upload_labdocs_modal').modal('hide');
                var obj = jQuery.parseJSON(res);
                if (obj.status === 500) {
                    toastr.warning(obj.msg);
                    $('#user_files_mr').val('');
                } else {
                    $('#upload_lab_form')[0].reset();
                    toastr.success(obj.msg);
                    window.location.reload();
                }

            }
        });
        return false;
    });
}
if (modules == 'doctor' && pages == 'appoinments') {
    function conversation_complete_status(id) {
        $('#app-complete-modal-title').html(lg_complete);
        $('#complete_appoinments_id').val(id);
        $('#appoinments_status_complete_modal').modal('show');
    }
    function change_complete_status() {
        var id = $('#complete_appoinments_id').val(id);
        $.post(base_url + 'appoinments/change_complete_status', { appoinments_id: id }, function (res) {

            $('#change_complete_btn').attr('disabled', true);
            $('#appoinments_status_modal').modal('hide');
            my_appoinments(0);
        });
    }
}


if (pages == 'lab_dashboard') {
    var lab_appoinments;

    lab_appoinments = $('#lab_appoinments_table').DataTable({
        'ordering': true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy': true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "language": {
            "infoFiltered": ""
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + 'lab/lab-appointment-list',
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
                "targets": [0, 4], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],

    });


    function lab_appoinments_table(type) {
        $('#type').val(type);
        lab_appoinments.ajax.reload(null, false); //reload datatable ajax
    }
    lab_appoinments_table(1);
}




/////////////////////duration//////////////////////
function duration(inputtxt) {
    var letters = /^[0-9a-zA-Z]+$/;
    if (inputtxt.value.match(letters)) {
        //alert('Your registration number have accepted : you can try another');
        document.form1.text1.focus();
        return true;
    }
    else {
        //alert('Please input alphanumeric characters only');
        return false;
    }
}
//////////////////     Sigin  firstname        //////////////////////////
$('input[name = "first_name"]').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if ((x >= 65 && x <= 90) || (x >= 97 && x <= 122) || x === 32) {
        return true;

    }
    else {
        return false;
    }
});

//////////////////     Sigin  Lastname        //////////////////////////
$('input[name = "last_name"]').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if ((x >= 65 && x <= 90) || (x >= 97 && x <= 122) || x === 32) {
        return true;

    }
    else {
        return false;
    }
});


function open_status() {
    var hrsopen = $('#hrsopen').val();
    if (hrsopen == 'yes') {
        $('#pharmacy_opens_at').prop('disabled', true);
        $('#pharmacy_opens_at').val('');
        $('.show-time-required').addClass('d-none');
    }
    if (hrsopen == 'no') {
        $('#pharmacy_opens_at').prop('disabled', false);
        $('.show-time-required').removeClass('d-none');
    }
}

// jquery validate method
$(document).ready(function () {
    // allow only char and spaces
    jQuery.validator.addMethod(
        'text_spaces_only',
        function (value) {
            return /^[a-zA-Z\- ]*$/.test(value);
        }
        ,
        lg_validate_text_spaces_only
    );

    // email
    jQuery.validator.addMethod(
        'email',
        function (value) {
            return /^([a-z0-9]{1,})([.\_])?(([a-z0-9]{1,}))(@)(([a-z1-9]{2,})(\.)[a-z]{2,3})$/.test(value);
        }
    );

    // address
    jQuery.validator.addMethod(
        'address_validation',
        function (value) {
            return /^[A-Za-z0-9-,./ ]*$/.test(value);
        }
    );

    // address
    jQuery.validator.addMethod(
        'reviews_validation',
        function (value) {
            return /^[A-Za-z0-9-,./ ]*$/.test(value);
        }
    );

    jQuery.validator.addMethod(
        'accept_chars',
        function (value) {
            return /^[A-Za-z0-9-,./\:\& ]*$/.test(value);
        },
        lg_accept_chars_val
    );
});
// jquery validate method

// toggle password
const togglePassword1 = document.querySelector('#togglePassword1');
const password1 = document.querySelector('#password');

if (togglePassword1) {
    togglePassword1.addEventListener('click', function (e) {
        // toggle the type attribute
        const type1 = password1.getAttribute('type') === 'password' ? 'text' : 'password';
        password1.setAttribute('type', type1);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });
    // toggle password
}

// toggle confirm password
const togglePassword2 = document.querySelector('#togglePassword2');
const password2 = document.querySelector('#confirm_password');

if (togglePassword2) {
    togglePassword2.addEventListener('click', function (e) {
        // toggle the type attribute
        const type2 = password2.getAttribute('type') === 'password' ? 'text' : 'password';
        password2.setAttribute('type', type2);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });
    // toggle confirm password
}

// disable-cut-copy-paste
$('[name="first_name"], [name="last_name"]').on("cut copy paste", function (e) {
    e.preventDefault();
});

// For special Character and Number validation
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
$.validator.addMethod(
    "validEmail",
    function (value, element) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(value)) {
            return false;
        } else {
            return true;
        }
    },
    "Enter the valid Email!"
);

$.validator.addMethod(
    "password_req",
    function (value, element) {
        var passreq = /^(?=.*[a-z])(?=.*[A-Z][a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/i;
        if (!passreq.test(value)) {
            return false;
        }
        else {
            return true;
        }
    },
    "Minimum eight and maximum 20 characters, at least one uppercase letter, one lowercase letter, one number and one special character"
);

$.validator.addMethod(
    "number_req",
    function (value, element) {
        var numreq = /^[0-9]+$/;
        if (!numreq.test(value)) {
            return false;
        }
        else {
            return true;
        }
    },
    "Please enter only number"
);