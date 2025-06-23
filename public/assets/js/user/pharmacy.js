function product_status(id) {
    var stat = $('#status_' + id).prop('checked');

    if (stat == true) {
        var status = 1;
    } else {
        var status = 2;
    }
    $.post(base_url + "products/change_status", { id: id, status: status }, function (data) {
        products_reload_table();
    });

}

function delete_products(id) {
    if (confirm(lg_are_you_sure_de3)) {
        // ajax delete data to database
        $.ajax({
            url: base_url + "admin/product/product_delete/" + id,
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                //if success reload ajax table
                products_reload_table();
                toastr.success(lg_product_deleted);
            },
            error: function () {
                //window.location.href=base_url+'admin/dashboard';
            }
        });

    }
}

function remove_images(image_url, preview_image_url, row_id) {


    $('#remove_image_div_' + row_id).remove();
    var total_array = $('#upload_image_url').val();
    var arr = total_array.split(",");
    var itemtoRemove = data.image_url;
    arr.splice($.inArray(itemtoRemove, arr), 1);
    $("#upload_image_url").val(arr);

    var total_array1 = $('#upload_preview_image_url').val();
    var arr1 = total_array1.split(",");
    var itemtoRemove1 = data.image_url;
    arr1.splice($.inArray(itemtoRemove1, arr1), 1);
    $("#upload_preview_image_url").val(arr1);

}

function remove_image(image_url, preview_image_url, row_id,is_current_upload) {

    var url = base_url + 'pharmacy/image-delete';
    if (confirm('Are you sure delete this image permanently?')) {
    $.ajax({
        type: 'post',
        url: url,
        dataType: 'json',

        data: {
            image_url: image_url, preview_image_url: preview_image_url,id:$('#productEditId').val(),is_current_upload:is_current_upload
        },

        success: function (data) {
            if (data.html == 1) {

                $('#remove_image_div_' + row_id).remove();
                var total_array = $('#upload_image_url').val();
                var arr = total_array.split(",");
                var itemtoRemove = data.image_url;
                arr.splice($.inArray(itemtoRemove, arr), 1);
                $("#upload_image_url").val(arr);

                var total_array1 = $('#upload_preview_image_url').val();
                var arr1 = total_array1.split(",");
                var itemtoRemove1 = data.image_url;
                arr1.splice($.inArray(itemtoRemove1, arr1), 1);
                $("#upload_preview_image_url").val(arr1);
            }
            if(data.html == 2) {
                toastr.error('Product should have atleast one image');
            }
        }
    });
}
}

(function($) {
    "use strict";
    
    if(pages=="profile")
    {

        $('input[name="price_type"]').on('click', function() {
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

        $(document).off('click','.profile_image_popup_close').on('click','.profile_image_popup_close', function() {
            $(".avatar-form")[0].reset();
        });

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


        $("#pharmacy_profile_form").validate({
            rules: {
                pharmacy_name: "required",
                first_name: {
                    required: true,
                    maxlength: 50
                },
                last_name: {
                    required: true,
                    maxlength: 50
                },
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
                gender: "required",
                dob:"required",
                address1: { required: true, minlength:5, maxlength:100},                 
                country: "required",
                state: "required",
                city: "required",
                postal_code: {
                    required: true,
                    minlength: 4,
                    maxlength: 7,
                    digits: true,
                },
                home_delivery: "required",
                hrsopen		: "required",
                pharmacy_opens_at: {
                    required: function (element) {
                        if ($("#hrsopen").val() === "no") {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
            },
            messages: {
                pharmacy_name: lg_please_enter_ph,
                first_name: {
                    required: lg_please_enter_ph
                },
                last_name: {
                    required: lg_please_enter_yo1
                },
                mobileno: {
                    required: lg_please_enter_mo,
                    maxlength: lg_please_enter_va,
                    minlength: lg_please_enter_va,
                    digits: lg_please_enter_va,
                    remote: lg_your_mobile_no_
                },
                gender: lg_select_gender,
                dob: lg_dob_is_require,
                address1: { required: lg_please_enter_yo3,minlength:"Minimum length should be 5 characters",maxlength:"Maximum length should be 100 characters"}, 
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
                home_delivery: "Please select home delivery available yes/no",
                hrsopen		: "Select 24hrs Open yes/no",
            },
            submitHandler: function (form) {

                $.ajax({
                    url: base_url + 'pharmacy/update-profile',
                    data: $("#pharmacy_profile_form").serialize(),
                    type: "POST",
                    beforeSend: function () {
                        $('#save_btns').attr('disabled', true);
                        $('#save_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#save_btn').attr('disabled', false);
                        $('#save_btn').html(lg_save_changes);

                        var obj = JSON.parse(res);

                        if (obj.status === 200)
                        {

                            toastr.success(obj.msg);
                            setTimeout(function () {
                                window.location.href = base_url + 'pharmacy';
                            }, 5000);

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

    if (pages == 'add_product' || pages == 'edit_product') {

        function number(field) {
            var regex = /\d*\.?\d?/g;
            field.value = regex.exec(field.value);

        }

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        $(document).ready(function () {

            $("#upload_image_btn").click(function () {
                $("#avatar-image-modal").css('display', 'block');
                $("#avatar-image-modal").modal('show');
            });

            $.ajax({
                type: "GET",
                url: base_url + "ajax/get_product_category",
                // data: { id: $(this).val() },
                beforeSend: function () {
                    $('#category').find("option:eq(0)").html(lg_please_wait);
                },
                success: function (data) {
                    /*get response as json */
                    $('#category').find("option:eq(0)").html(lg_select_category);
                    var obj = jQuery.parseJSON(data);
                    $(obj).each(function () {
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);
                        $('#category').append(option);
                    });
                    $('#category').val(category);

                }
            });

            $.ajax({
                type: "GET",
                url: base_url + "ajax/get_product_unit",
                // data: { id: $(this).val() },
                beforeSend: function () {
                    $('#unit').find("option:eq(0)").html(lg_please_wait);
                },
                success: function (data) {
                    /*get response as json */
                    $('#unit').find("option:eq(0)").html(lg_select_unit);
                    var obj = jQuery.parseJSON(data);
                    $(obj).each(function () {
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);
                        $('#unit').append(option);
                    });
                    $('#unit').val(unit);

                }
            });

            if(category && category>0){                            
                $.ajax({
                    type: "GET",
                    url: base_url + "ajax/get_product_subcategory/"+category,
                    beforeSend: function () {
                        $("#subcategory option:gt(0)").remove();
                        $('#subcategory').find("option:eq(0)").html(lg_please_wait);

                    },
                    success: function (data) {
                        /*get response as json */
                        $('#subcategory').find("option:eq(0)").html(lg_select_subcateg);
                        var obj = jQuery.parseJSON(data);
                        $(obj).each(function () {
                            var option = $('<option />');
                            option.attr('value', this.value).text(this.label);
                            $('#subcategory').append(option);
                        });

                        $('#subcategory').val(subcategory);

                    }
                });
            }

            $('#category').change(function () {
                $.ajax({
                    type: "GET",
                    url: base_url + "ajax/get_product_subcategory/"+$(this).val(),
                    beforeSend: function () {
                        $("#subcategory option:gt(0)").remove();
                        $('#subcategory').find("option:eq(0)").html(lg_please_wait);

                    },
                    success: function (data) {

                        /*get response as json */
                        $('#subcategory').find("option:eq(0)").html(lg_select_subcateg);
                        var obj = jQuery.parseJSON(data);
                        $(obj).each(function () {
                            var option = $('<option />');
                            option.attr('value', this.value).text(this.label);
                            $('#subcategory').append(option);
                        });

                        /*ends */

                    }
                });
            });
            // $("#add_product").click(function(){ 
            //     $('#image-errors').show(); 
            // });

            $("#add_product").validate({

                rules: {

                    name: {
                        //required: true, SpecCharValidate: true,
                        required: true,
                        remote: {
                            url: base_url + "ajax/check-product-exists",
                            type: "post",
                            data: {
                                name: function () {
                                    return $("#name").val();
                                }
                            }
                        }

                    },
                    category: "required",
                    subcategory: "required",
                    unit_value: "required",
                    unit: "required",
                    price: "required",
                    sale_price: "required",
                    description: { required: true, maxlength: 1000 },
                    manufactured_by: { required: true, maxlength: 50 },
                    short_description: { required: true, maxlength: 500 },
                    upload_image_url: { required: true, accept: "image/jpg,image/jpeg,image/png,image/gif" }

                    // alert ("This is an alert dialog box"); 

                },
                messages: {
                    name: { required: lg_please_enter_pr, SpecCharValidate: "No Special Chars or Numbers Allowed", remote: "Product name already exists!" },
                    category: lg_please_select_p1,
                    subcategory: lg_please_select_p2,
                    unit_value: lg_please_enter_un,
                    unit: lg_please_select_u,
                    price: lg_please_enter_pr1,
                    sale_price: lg_please_enter_se1,
                    description: { required: lg_please_enter_de1, maxlength: "Maximum description length should be 1000" },
                    manufactured_by: { required: lg_please_enter_th, maxlength: "Maximum description length should be 50" },
                    short_description: { required: lg_please_enter_th1, maxlength: "Maximum short description length should be 500" }, upload_image_url: { required: 'Required!', accept: 'Invalid extension!' }

                },


                submitHandler: function (form) {


                    if ($('#upload_image_url').val() == '') {
                        $('#image-error').show();
                        $('#image-error').html(lg_please_upload_p);
                        return false;

                    }


                    $.ajax({
                        url: base_url + 'pharmacy/create-product',
                        data: $("#add_product").serialize(),
                        type: "POST",
                        beforeSend: function () {
                            $('#product_btn').attr('disabled', true);
                            $('#product_btn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                        success: function (res) {
                            $('#product_btn').attr('disabled', false);
                            $('#product_btn').html(lg_add10);
                            var obj = JSON.parse(res);

                            if (obj.status === 200) {
                                window.location.href = base_url + 'pharmacy/product-list';
                            } else {
                                toastr.error(obj.msg);
                            }
                        }
                    });
                    return false;
                }

            });


            $("#edit_product").validate({
                rules: {
                    name: "required",
                    category: "required",
                    subcategory: "required",
                    unit_value: "required",
                    unit: "required",
                    price: "required",
                    sale_price: "required",
                    description: "required",
                },
                messages: {
                    name: lg_please_enter_pr,
                    category: lg_please_select_p1,
                    subcategory: lg_please_select_p2,
                    unit_value: lg_please_enter_un,
                    unit: lg_please_select_u,
                    price: lg_please_enter_pr1,
                    sale_price: lg_please_enter_se1,
                    description: lg_please_enter_de1,
                    manufactured_by: lg_please_enter_th,
                    short_description: lg_please_enter_th1,
                    upload_image_url: lg_please_upload_p
                },
                submitHandler: function (form) {
                    if ($('#upload_image_url').val() == '') {
                        $('#image-error').show();
                        $('#image-error').html(lg_please_upload_p);
                        return false;
                    }

                    $.ajax({
                        url: base_url + 'pharmacy/update-product',
                        data: $("#edit_product").serialize(),
                        type: "POST",
                        beforeSend: function () {
                            $('#product_btn').attr('disabled', true);
                            $('#product_btn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                        success: function (res) {
                            $('#product_btn').attr('disabled', false);
                            $('#product_btn').html(lg_update);
                            var obj = JSON.parse(res);

                            if (obj.status === 200) {
                                window.location.href = base_url + 'pharmacy/product-list';
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

    if (pages == 'product_list') {

        var products_table;
        //datatables
        products_table = $('#products_table').DataTable({
            'ordering': true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + "pharmacy/product-list",
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

        $(document).on('change', '.product_status', function () {
            var id = $(this).attr('id');
            var stat = $('#' + id).prop('checked');
            if (stat == true) {
                var status = 1;
            } else {
                var status = 2;
            }
            $.post(base_url + "pharmacy/update-status", { id: id, status: status }, function (data) {
                toastr.success(lg_status_updated_);
                products_table.ajax.reload(null, false); //reload datatable ajax
            });
        });

        $(document).on('click', '.product_delete', function () {
            var id = $(this).attr('id');
            var avoid = "delete";
            id = id.replace(avoid, '');
            if (confirm(lg_are_you_sure_de3)) {
                $.get(base_url + "pharmacy/product-delete/"+id,function (data) {
                    toastr.success(lg_product_deleted);
                    products_table.ajax.reload(null, false); //reload datatable ajax
                });
            }
        });

        function products_reload_table() {
            products_table.ajax.reload(null, false); //reload datatable ajax
        }
        products_reload_table();
        
    }

})(jQuery);