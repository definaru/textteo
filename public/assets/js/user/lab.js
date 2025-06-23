(function($) {
    "use strict";
    
    $.ajax({
        type: "GET",
        url: base_url + "ajax/get-country",
        beforeSend: function () {
            $('#country').find("option:eq(0)").html(lg_please_wait);
        },
        success: function (data) {            
            $('#country').find("option:eq(0)").html(lg_select_country);
            var obj = jQuery.parseJSON(data);
            $(obj).each(function ()
            {
                var option = $('<option />');
                option.attr('value', this.value).text(this.label);
                $('#country').append(option);
            });
        }
    });

    if(pages=="profile")
    {
        
        $.ajax({
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
                    var option = "<option value="+this.value+" data-id="+this.countryid+" >"+this.label+"</option>";
                    $('#country_code').append(option);
                });
                $('#country_code option[data-id="'+$("#country_id").val()+'"]').prop('selected',true);
            }
        });

        $('#country_code').change(function () {
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
                $(obj).each(function ()
                {
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
            data: {id: country},
            beforeSend: function () {
                $("#state option:gt(0)").remove();
                $("#city option:gt(0)").remove();
                $('#state').find("option:eq(0)").html(lg_please_wait);

            },
            success: function (data) {
                $('#state').find("option:eq(0)").html(lg_select_state);
                var obj = jQuery.parseJSON(data);
                $(obj).each(function ()
                {
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
            data: {id: state},
            beforeSend: function () {
                $("#city option:gt(0)").remove();
                $('#city').find("option:eq(0)").html(lg_please_wait);
            },
            success: function (data) {
                $('#city').find("option:eq(0)").html(lg_select_city);
                var obj = jQuery.parseJSON(data);
                $(obj).each(function ()
                {
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
                data: {id: $(this).val()},
                beforeSend: function () {
                    $("#state option:gt(0)").remove();
                    $("#city option:gt(0)").remove();
                    $('#state').find("option:eq(0)").html(lg_please_wait);

                },
                success: function (data) {
                    $('#state').find("option:eq(0)").html(lg_select_state);
                    var obj = jQuery.parseJSON(data);
                    $(obj).each(function ()
                    {
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
                data: {id: $(this).val()},
                beforeSend: function () {
                    $("#city option:gt(0)").remove();
                    $('#city').find("option:eq(0)").html(lg_please_wait);
                },
                success: function (data) {
                    $('#city').find("option:eq(0)").html(lg_select_city);
                    var obj = jQuery.parseJSON(data);
                    $(obj).each(function ()
                    {
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);
                        $('#city').append(option);
                    });
                }
            });
        });


        $("#lab_profile_form").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                country_code: {
                    required: true,
                },
                gender: "required",
                dob: "required",
                mobileno: {
                    required: true,
                    minlength: 7,
                    maxlength: 12,
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
                            checkall:"false"
                        }
                    }
                },
                address1: {
                    required: true,
                    address_validation: true,
                    maxlength: 500,
                },
                address2: {
                    address_validation: true,
                    maxlength: 500,
                },
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
                first_name: "Please entet lab name",
                last_name: lg_please_enter_yo1,
                country_code: {
                    required: lg_please_select_c_code
                },
                gender: lg_please_select_g,
                dob: lg_please_enter_yo2,
                address1: {
                    required: lg_pers_info_address_req,
                    address_validation: lg_pers_info_address_val,
                    maxlength: lg_enter_address_max,
                },
                address2: {
                    address_validation: lg_pers_info_address_val,
                    maxlength: lg_enter_address_max,
                },
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
            submitHandler: function(form) {
                $.ajax({
                    url: base_url+'lab/update-profile',
                    data: $("#lab_profile_form").serialize(),
                    type: "POST",
                    beforeSend: function(){
                        $('#save_btn').attr('disabled',true);
                        $('#save_btn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                    success: function(res){
                        $('#save_btn').attr('disabled',false);
                        $('#save_btn').html(lg_save_changes);
                        var obj = JSON.parse(res);
                        if(obj.status===200)
                        { 
                            toastr.success(obj.msg);
                            setTimeout(function(){ window.location.href=base_url+'lab'; }, 2000);                              
                        }
                        else
                        {
                            toastr.error(obj.msg);
                        }   
                    }
                });
                return false;
            }
        });
       
    }
})(jQuery);