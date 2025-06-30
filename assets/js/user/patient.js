// Avoided Copy Paste In Form Fields
$('[name="first_name"], [name="last_name"], .addressfield,.namefield,.numericOnly,.mobileNoOnly').on("cut copy paste", function (e) {
    e.preventDefault();
});


if(pages=="lab_appoinments")
{
    var lab_appointment_table;        

    function lab_appoinments_table() {
        lab_appointment_table.ajax.reload(null, false);
    }

    function view_docs(id) {

        var base = $("#base").val();

        $.ajax({
            url: base_url + "patient/lab-appointment-doc/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                var li = '';
                $.each(data, function (index, value) {

                    var link = base_url + value;
                    var file_path = value.split("/");
                    file_name = file_path[file_path.length - 1];

                    var index = index + 1;
                    li += '<li><a target="_blank" href="' + link + '">' + index + ' . ' + file_name + '</a></li>';

                });

                if (data.length > 0) {
                    $('#links').html(li);
                } else {
                    $('#links').html("No records found");
                }

                $('#view_docs').modal('show');

            },
            error: function () {
                window.location.href = base_url + modules;
                return false;
            }
        });

    }
}

if(pages == 'appointmentDetials'){

}

if (pages == 'patientDashboard')
{   

    var appoinment_table;
    appoinment_table = $('#appoinment_table').DataTable({
        'ordering': true,
        "processing": false,
        'bnDestroy': true,
        "serverSide": true, 
        "order": [], 
        "language": {                
            "infoFiltered": ""
        },
        "ajax": {
            "url": base_url + 'patient/appointment-list',
            "type": "POST",
            "data": {type:$('#type').val()},
            error: function () {
            },
        },
        "columnDefs": [
            {
                "targets": [0], 
                "orderable": false, 
            },
        ],
    });


    function appoinments_Card_mobile(search,limit, start){
        $.ajax({
            url: base_url + 'patient/appointment-list',
            type: 'POST',
            data: {type:1, draw:null, search:{ value : search }, length: limit, start: start},
            error: function () {
            },
            success: function(res){
                res = JSON.parse(res);
                console.log(res);
                renderMobileCards(res.data, start, limit, res.recordsFiltered);
                return [];
            }
        });
    }

    function renderMobileCards(data, currentPage, limit, recordsFilterd) {
        console.log('currentPage', currentPage);
        console.log('limit', limit);
        const totalPages = Math.ceil(recordsFilterd / limit);
        console.log(totalPages);
        const container = document.getElementById("mobile-cards-container");
        container.innerHTML = ""; // Clear previous content
        
        data.forEach(data => {
            const card = document.createElement("div");
            card.className = "appointment-card";
            card.innerHTML = `
            <div class="card-header">
            <button class="back-btn"> <img src="${data[7]}" > </button>
            ${data[4]}
            </div>

        <div class="card-body-appointment">
             <div class="doc-info-appoint">
             ${data[1]}
             </div>
            <div class="pet-info">
            <img src="${data[5]}" alt="Pet" class="img-fluid" width="50" height="50"/>
            <div>
                <div class="pet-name">${data[3]}</div>
                <div class="pet-type">${data[6]}</div>
            </div>
            </div>
            <div class="appointment-date">
            <div class="label">Appointment Date</div>
            <div class="date">${data[2]}</div>
            </div>
        </div>
            `;
    
            container.appendChild(card);
            
        });

        const pagination = document.createElement("div");
        pagination.innerHTML = `
            <div id="pagination">
                <button class="btn btn-default" style="border:1px solid #F7F7F7" id="prev" onclick="changePage('prev', ${totalPages})">Previous</button>
                <span id="page-number">1</span>
                <button class="btn btn-default" style="border:1px solid #F7F7F7" id="next" onclick="changePage('next', ${totalPages})">Next</button>
             <input type="hidden" id="current-page" value="${currentPage}">
            </div>
        `;
        
        container.appendChild(pagination);
        const pageNumberDisplay = document.getElementById("page-number");
        if (pageNumberDisplay) {
            pageNumberDisplay.innerText = currentPage + 1;
        }

        if(totalPages == (currentPage + 1)){
            document.getElementById("next").disabled;
        }

        if((currentPage + 1) == 1){
            document.getElementById("prev").disabled;
        }

    }

    function changePage(direction, totalPages) {
        let currentPage = document.getElementById("current-page").value;
        currentPage = parseInt(currentPage);
        const limit = 5;
        if (direction === 'next' && totalPages != (currentPage + 1)) {
            currentPage = currentPage + 1;
            appoinments_Card_mobile(null, limit,(currentPage * limit))
        } else if (direction === 'prev' && currentPage > 0) {
            currentPage = currentPage - 1;
            appoinments_Card_mobile(null, limit,(currentPage * limit))
        }
    }
 
    
    function appoinments_table(type)
    {
        $('#type').val(type);
        appoinment_table.ajax.reload(null, false); 
        // alert(type);
    }

    //if(window.innerWidth > 768){
        appoinments_table(1);
        
    // }else{
    //     console.log("mobile");
    //     appoinments_Card_mobile(null, 5, 0);
        
    // }
    

   
    
    var previous_appoinment_table;
    previous_appoinment_table = $('#previous_appointment_table').DataTable({
        'ordering': true,
        "processing": false,
        'bnDestroy': true,
        "serverSide": true, 
        "order": [], 
        "language": {                
            "infoFiltered": ""
        },
        "ajax": {
            "url": base_url + 'patient/previous-appointment-list',
            "type": "POST",
            "data": {type:$('#type').val()},
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

    function previous_appoinments_table(type)
    {
        $('#type').val(type);
        previous_appoinment_table.ajax.reload(null, false); 
        // alert(type);
    }
    previous_appoinments_table(1);


    $('#previous_appointment_table').on('xhr.dt', function (e, settings, json, xhr) {
    if (!json || json.data.length === 0) {
        $('.previous-appointment-content').remove();

        $('.no-previous-appointment').css({
            'display': 'flex',
            'justify-content': 'center',
            'align-items': 'center',
            'width': '100%',
        }).show();

        $('.no-previous-appointment h2').css({
             'width': '100%',
        });

    }
   });

       $('#appoinment_table').on('xhr.dt', function (e, settings, json, xhr) {
    if (!json || json.data.length === 0) {
        $('.appoinment-div-content').remove();

        $('.no-appointment').css({
            'display': 'block',
            'justify-content': 'center',
            'align-items': 'center',
            'width': '100%',
        }).show();

        $('.no-appointment h2').css({
             'width': '100%',
        });


        $('.apt-btn-book').css({
             'width': '100%',
             'display': 'block',
             'margin': '0px auto',
            'text-align': 'center',
            'width': 'fit-content'
        });
    }
   });

    var prescription_table;
    prescription_table = $('#prescription_table').DataTable({
        'ordering': true,
        "processing": false, //Feature control the processing indicator.
        'bnDestroy': true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + 'patient/prescription-list',
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

    // Medical List Of Patient
    var medical_record_table;
    medical_record_table = $('#medical_records_table').DataTable({
        'ordering': true,
        "processing": false, //Feature control the processing indicator.
        'bnDestroy': true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + 'my_patients/medical_records_list',
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

    // Billing List Of Patient
    var billing_table;
    billing_table = $('#billing_table').DataTable({
        'ordering': true,
        "processing": false, //Feature control the processing indicator.
        'bnDestroy': true,
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url + 'my_patients/billing_list',
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

    function view_prescriptionV2(pre_id) {
        console.log(pre_id);
        $('.overlay').show();
        $.post(base_url + 'my_patients/get_prescription_details-v2', { pre_id: pre_id }, function (res) {
            var obj = jQuery.parseJSON(res);
            console.log(obj);
            var modalContent = `
            <!-- General Info -->
        <div class="mb-3" style="background-color: #f8f9fa; border-radius: 10px; padding: 15px;">
          <div class="row">
            <div class="col-md-3"><strong>Clinic Name</strong><br>${obj[0].clinic_name}</div>
            <div class="col-md-3"><strong>Veterinarian</strong><br>${obj[0].doctor_name}</div>
            <div class="col-md-3"><strong>Date of Visit</strong><br>${obj[0].from_date_time}</div>
            <div class="col-md-3"><strong>PetOwner</strong><br>${obj[0].patient_name}</div>
          </div>
          <div class="row mt-6">
            <div class="col-12">
              <strong>Reason for Visit</strong><br>
                  ${obj[0].reason}
              </div>
          </div>
        </div>
            `;

            
                var prescription =`
                 <!-- Prescription -->
        <div class="mb-3" style="background-color: #fff7eb; border-radius: 10px; padding: 15px;">
          <div><strong>Prescription </strong> ${pre_id}-EM</div>
          <div class="mt-2">
            <strong>Diagnosis</strong><br>
            ${obj[0].diagnosis}
            </div>
          <div class="row mt-3 text-center">
            <div class="col-md-4">
              <div style="background-color: #fff1d6; border-radius: 10px; padding: 10px; height: 100%;">
                <strong>Medication</strong><br>${obj.map(res => `<span>${res.drug_name}</span><br>`).join('')}
              </div>
            </div>
            <div class="col-md-4">
              <div style="background-color: #fff1d6; border-radius: 10px; padding: 10px; height: 100%;">
                <strong>Dosage</strong><br>${obj.map(res => `<span>${res.qty} ${res.time}</span><br>`).join('')} 
              </div>
            </div>
            <div class="col-md-4">
              <div style="background-color: #fff1d6; border-radius: 10px; padding: 10px; height: 100%;">
                <strong>Duration</strong><br>${obj.map(res => `<span>${res.days} days</span><br>`).join('')}  
              </div>
            </div>
            <
          </div>
        </div>
                `;
            

            var footer = `
        <!-- Recommendations -->
        <div style="background-color: #e8e7fc; border-radius: 10px; padding: 15px;">
          <h6><strong>Recommendations</strong></h6>
          <ul>
            <li>Ensure Whiskers has continuous access to fresh water to prevent dehydration</li>
            <li>Monitor appetite and activity</li>
            <li>Avoid giving table scraps or any non-prescribed food to prevent further gastrointestinal upset</li>
          </ul>
        </div>
            `;

            modalContent = modalContent + prescription + footer;

            
            // $('#patient_name').text(obj[0].patient_name);
            // $('#view_date').text(obj[0].prescription_date);
            // $('.view_title').text(lg_prescription);
            $('.modal-body-prescription').html(modalContent);
            $('#view_modal').modal('show');
            $('.overlay').hide();
        });

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

}

// $(document).ready(function () 
// {

    if(pages=="change-password" || pages=="profile")
    {
        const togglecurrentpassword = document.querySelector('#togglecurrentpassword');
        const currentpassword = document.querySelector('#currentpassword');

        togglecurrentpassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type1 = currentpassword.getAttribute('type') === 'password' ? 'text' : 'password';
        currentpassword.setAttribute('type', type1);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
        });


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


        $("#change_password").validate({
            rules: {
                currentpassword: {
                    required: true,
                    remote: {
                        url: base_url + "check-password",
                        type: "post",
                        data: {
                            currentpassword: function () {
                                return $("#currentpassword").val();
                            }
                        }
                    }
                },

                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20,
                    password_req:true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password",
                    maxlength: 20,
                },
            },
            messages: {
                currentpassword: {
                    required: lg_please_enter_cu,
                    remote: lg_your_current_pa
                },
                password: {
                    required: lg_please_enter_new_pa,
                    minlength: lg_your_password_m,
                    maxlength: lg_password_max_length_20
                },
                confirm_password: {
                    required: lg_please_enter_co,
                    equalTo: lg_your_password_d,
                    maxlength: lg_confirm_password_max_length_20
                },

            },
            submitHandler: function (form) {

                $.ajax({
                    url: base_url + 'change-password',
                    data: $("#change_password").serialize(),
                    type: "POST",
                    beforeSend: function () {
                        $('#change_password_btn').attr('disabled', true);
                        $('#change_password_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#change_password_btn').attr('disabled', false);
                        $('#change_password_btn').html(lg_change_password);
                        var obj = JSON.parse(res);

                        if (obj.status === 200)
                        {
                            $('#change_password')[0].reset();
                            toastr.success(obj.msg);
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

    if(pages=="profile")
    {
        if(modules!="clinic")
        {
            var maxDate = $('#maxDate').val();
            $('#dob').datepicker({
                startView: 2,
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: maxDate
            });
        }
    

        $(document).off('click','.profile_image_popup_close').on('click','.profile_image_popup_close', function() {
            $(".avatar-form")[0].reset();
        });


        $.ajax({
            type: "GET",
            url: base_url + "ajax/get-country-code",
            data: {id: $("#country_code").val()},
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
                dob: "required",
                blood_group: "required",
                address1: "required",
                country_code: "required",
                // address2: "required",
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
                country_code: lg_please_select_c_code,
                gender: lg_please_select_g,
                dob: lg_please_enter_yo2,
                blood_group: lg_please_select_b,
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
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: base_url + 'patient/update-profile',
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

                        if (obj.status === 200)
                        {
                            toastr.success(obj.msg);
                            setTimeout(function () {
                                window.location.href = base_url + 'patient';
                            }, 2000);
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


    // Lab Appointment List
    if (pages == 'lab_appoinments') 
    {
        
        lab_appointment_table = $('#lab_appointment_table').DataTable({
            'ordering': false,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
    
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'patient/lab-appointment-list',
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
    }

    // Booked Appointment List
    if (pages == 'appoinments')
    {
        setInterval(function () {
            // my_pappoinments(0);
            var loadvalue = $('#page_no_hidden').val();
            my_pappoinments(loadvalue);

        }, 1000);
        function my_pappoinments(load_more) {

            if (load_more == 0) {
                $('#page_no_hidden').val(1);
            }

            var page = $('#page_no_hidden').val();


            //$('#search-error').html('');

            $.ajax({
                url: base_url + 'patient/patient-appointment-list',
                type: 'POST',
                data: { page: page },
                beforeSend: function () {
                    
                    // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
                },
                success: function (response) {
                    console.log(888);
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
            my_pappoinments(1);
        });

        function show_appoinments_modal(app_date, book_date, amount, type) {
            $('.app_date').html(app_date);
            $('.book_date').html(book_date);
            $('.amount').html(amount);
            $('.type').html(type);
            $('#appoinments_details').modal('show');
        }
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
// });