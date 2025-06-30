
function add_account_details() {
    $.ajax({
        url: base_url + "get-account-details",
        type: "GET",
        dataType: "JSON",
        success: function (data) {


            if (data) {
                $('[name="bank_name"]').val(data.bank_name);
                $('[name="branch_name"]').val(data.branch_name);
                $('[name="account_no"]').val(data.account_no);
                $('[name="account_name"]').val(data.account_name);
                $('#accounts_modal_title').text(lg_edit_details);
            } else {
                $('#accounts_form')[0].reset();
                $('#accounts_modal_title').text(lg_add_account_details);
            }

            // show bootstrap modal when complete loaded


        }


    });

    $('#account_modal').modal('show');
}

//datatables
var accounts_table;

accounts_table = $('#accounts_table').DataTable({
    'ordering': true,
    "processing": true, //Feature control the processing indicator.
    'bnDestroy': true,
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url + 'doctor-account-list',
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



function account_table() {
    accounts_table.ajax.reload(null, false);
}


var patient_refund_request_table;

patient_refund_request_table = $('#patient_refund_request').DataTable({
    'ordering': true,
    "processing": true, //Feature control the processing indicator.
    'bnDestroy': true,
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": base_url + 'patient-refund-request',
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
            "orderable": true, //set not orderable
        },
    ],

});


function patient_refund_request() {
    patient_refund_request_table.ajax.reload(null, false);
}

function send_request(id, status,role) {

    $.post(base_url + 'account-send-request', { id: id, status: status,role:role }, function (res) {
        account_table();
        patient_refund_request();
        window.location.reload();
    });
}





if (modules == 'patient') {



    //datatables
    var doctor_request_table;

    doctor_request_table = $('#doctor_request').DataTable({
        'ordering': true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy': true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + 'patient-doctor-request',
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
                "orderable": true, //set not orderable
            },
        ],

    });



    function doctor_request() {
        doctor_request_table.ajax.reload(null, false);
    }

    //datatables
    var paccounts_table;

    paccounts_table = $('#patient_accounts_table').DataTable({
        'ordering': true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy': true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + 'patient-account-list',
            "type": "POST",
            "data": {type: 'doctor'},
            error: function () {

            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [0, 5], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],

    });



    function paccount_table() {
        paccounts_table.ajax.reload(null, false);
    }

    // pahrmacy datatables
    var pharmacy_account_table;

    pharmacy_account_table = $('#pharmacy_accounts_table').DataTable({
        'ordering': true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy': true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + 'patient-account-list',
            "type": "POST",
            "data":  {
                type: 'pharmacy'
            },
            error: function () {

            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [0, 5], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],

    });



    function pharmacyaccount_table() {
        pharmacy_account_table.ajax.reload(null, false);
    }
    // lab datatables
    var lab_accounts_table;

    lab_accounts_table = $('#lab_accounts_table').DataTable({
        'ordering': true,
        "processing": true, //Feature control the processing indicator.
        'bnDestroy': true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + 'patient-account-list',
            "type": "POST",
            "data":  {
                type: 'lab'
            },
            error: function () {

            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [0, 5], //first column / numbering column
                "orderable": false, //set not orderable
            },
        ],

    });



    function labaccount_table() {
        lab_accounts_table.ajax.reload(null, false);
    }

    function send_request(id, status,role) {

        $.post(base_url + 'account-send-request', { id: id, status: status,role:role }, function (res) {

            paccount_table();
            pharmacyaccount_table();
            labaccount_table();
            window.location.reload();
        });
    }

}




function payment_request(type) {
    $('#payment_type').val(type);
    $('#payment_request_modal').modal('show');
}

function amount() {
    var a = $('#request_amount').val();
    if (a != '') {
        $('#request_amount').val(parseFloat(a));
    }

}

$('.numonly').keyup(function () {
    this.value = this.value.replace(/[^0-9\.]/g, '');
});

$('.request_btn').click(function () {
    $("#request_amount").val('');
    $("#description").val('');
});
$(document).ready(function (e) {
    $("#account_modal").on('show.bs.modal', function () {
        var $alertas = $('#accounts_form');
        $alertas.validate().resetForm();
        $alertas.find('.error').removeClass('error');
    });

    // Account details form
    $("#accounts_form").submit(function (e) {
        e.preventDefault();
    }).validate({
        rules: {
            bank_name: {
                required: true,
                minlength: 5,
                maxlength: 150,
                text_spaces_only: true
            },
            branch_name: {
                required: true,
                minlength: 5,
                maxlength: 150,
                text_spaces_only: true
            },
            account_no: {
                required: true,
                minlength: 11,
                maxlength: 150,
                number: true
            },
            account_name: {
                required: true,
                minlength: 5,
                maxlength: 150,
                text_spaces_only: true
            }
        },
        messages: {
            bank_name: {
                required: lg_please_enter_ba
            },
            branch_name: {
                required: lg_please_enter_br
            },
            account_no: {
                required: lg_please_enter_ac1
            },
            account_name: {
                required: lg_please_enter_ac2
            },
        },
        submitHandler: function (form) {

            $.ajax({
                url: base_url + 'add-account-details',
                type: "POST",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {

                    $('#acc_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    $('#acc_btn').attr('disabled', true);

                },
                success: function (data) {

                    $('#acc_btn').html(lg_save);
                    $('#acc_btn').attr('disabled', false);

                    var obj = jQuery.parseJSON(data);
                    if (obj.result == 'true') {

                        toastr.success(obj.status);

                        $('#account_modal').modal('hide');
                        $('#bank_name').val(bank_name);
                        $('#branch_name').val(branch_name);
                        $('#account_no').val(account_no);
                        $('#account_name').val(account_name);
                        $('#btn-add-edit-title').html(lg_edit_details);

                        window.location.reload();

                    } else {
                        toastr.error(obj.status);
                    }

                }
            });
            return false;
        }
    });
    // Account details form

    /*$("#payment_request_form").on('submit', (function (e) {
        e.preventDefault();


        var request_amount = $('[name="request_amount"]').val();

        if (request_amount == '') {
            toastr.error(lg_please_enter_am);
            return false;
        }

        $.ajax({
            url: base_url + 'accounts/payment_request',
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

                $('#request_btn').html('<div class="spinner-border text-light" role="status"></div>');
                $('#request_btn').attr('disabled', true);

            },
            success: function (data) {

                $('#request_btn').html(lg_request1);
                $('#request_btn').attr('disabled', false);

                var obj = jQuery.parseJSON(data);
                if (obj.result == 'true')
                {

                    toastr.success(obj.status);

                    $('#payment_request_modal').modal('hide');
                    $('#payment_request_form')[0].reset();

                    window.location.reload();
                } else
                {
                    toastr.error(obj.status);
                }
            }

        });
    }));*/

    $("#payment_request_modal").on('show.bs.modal', function () {
        var $alertas = $('#payment_request_form');
        $alertas.validate().resetForm();
        $alertas.find('.error').removeClass('error');
    });

    /*submit form ajax template*/
    $("#payment_request_form").submit(function (e) {
        e.preventDefault();
    }).validate({
        rules: {
            request_amount: {
                required: true,
                maxlength: 100,
                min: 1,
                number: true,
            },
            description: {
                // required: true,
                maxlength: 500,
                accept_chars: true,
            },

        },
        messages: {
            request_amount: {
                required: lg_form_lab_test_amount_req,
                maxlength: lg_form_lab_test_amount_max
            },
            description: {
                // required: lg_form_lab_test_description_req,
                maxlength: lg_form_lab_test_description_max
            },

        },
        submitHandler: function (form) {

            // form data
            var formData = new FormData($('#payment_request_form')[0]);

            // ajax
            $.ajax({
                url: base_url + 'account-payment-request',
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {

                    $('#request_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    $('#request_btn').attr('disabled', true);

                },
                success: function (data) {

                    $('#request_btn').html(lg_request1);
                    $('#request_btn').attr('disabled', false);

                    var obj = jQuery.parseJSON(data);
                    if (obj.result == 'true') {

                        toastr.success(obj.status);

                        $('#payment_request_modal').modal('hide');
                        $('#payment_request_form')[0].reset();

                        window.location.reload();
                    } else {
                        toastr.error(obj.status);
                    }
                }

            });

            return false;
        }
    });
    /*submit form ajax template*/

});



