function delete_clinic_image(id) {    
    $.ajax({
        url: base_url + 'ajax/deleteClinicImg',
        data: {id: id},
        type: "POST",
        success: function (res) {    
            var obj = JSON.parse(res);    
            if (obj.status === 200)
            {    
                toastr.success(obj.msg);
                location.reload(true);
            } 
            else
            {
                toastr.error(lg_something_went_1);
            }
        }
    });
} 



// jquery validate method
$(document).ready(function () {
    // allow only char and spaces
    jQuery.validator.addMethod('text_spaces_only',function (value)
    {
        return /^[a-zA-Z\- ]*$/.test(value);
    },lg_validate_text_spaces_only);

    // email
    jQuery.validator.addMethod('email',function (value)
    { 
        // example@gmail.com
        // return /^([a-z0-9]{1,})([.\_])?(([a-z0-9]{1,}))(@)(([a-z1-9]{2,})(\.)[a-z]{2,3})$/.test(value);
        //example@gmail.co.in
        // return /^([a-z0-9]{1,})([.\_])?(([a-z0-9]{1,}))(@)(([a-z1-9]{2,})(\.)[a-z]{2,3})(\.)[a-z]{2,3})$/.test(value);
        return /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
    });

    // address
    jQuery.validator.addMethod('address_validation',function (value)
    { 
        return /^[A-Za-z0-9-,./ ]*$/.test(value); 
    });

    // address
    jQuery.validator.addMethod('reviews_validation',function (value)
    { 
        return /^[A-Za-z0-9-,./ ]*$/.test(value); 
    });

    jQuery.validator.addMethod('accept_chars',function (value) 
    { 
        return /^[A-Za-z0-9-,./\:\& ]*$/.test(value); 
    },lg_accept_chars_val);

    jQuery.validator.addMethod("password_req",function(value, element) 
    {            
        var passreq =  /^(?=.*[a-z])(?=.*[A-Z][a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/i;
        if(!passreq.test(value))
        {
            return false;
        }
        else {
            return true;
        }
    },"Minimum eight and maximum 20 characters, at least one uppercase letter, one lowercase letter, one number and one special character");

    $.validator.addMethod("SpecCharValidate",function(value, element) 
    {            
            var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
			if(!characterReg.test(value)) {				 
               	return false;
			}else{   
            	return true;
			}
    },"No Special Chars or Numbers Allowed in the City Name");

    if (pages == 'social-media') 
    {
        //url valiation start 
        $.validator.addMethod("validFBurl", function(value, element) 
        {   
            var FBurl = /^(http|https)\:\/\/facebook.com\/.*/;
            if(value.length>1 && !FBurl.test(value)) 
            {
                return false;
            }
            else 
            {
                return true;
            }
        },"Enter the valid FB!");

        $.validator.addMethod("validTwitterUrl", function(value, element) 
        {            
            var Twitterurl =  /https?:\/\/twitter\.com\/(#!\/)?[a-z0-9_]+$/i;
            if(value.length>1 && !Twitterurl.test(value)) 
            {
                return false;
            }
            else 
            {
                return true;
            }
        },"Enter the valid Twitter !");

        $.validator.addMethod("validInstagramUrl", function(value, element) 
        {            
            var Instaurl =  /^\s*(http\:\/\/)?instagram\.com\/[a-z\d-_]{1,255}\s*$/i;
            if(value.length>1 && !Instaurl.test(value)) 
            {
                return false;
            }
            else 
            {
                return true;
            }
        },"Enter the valid Instagram !");

        $.validator.addMethod("validPinterestUrl", function(value, element) 
        {            
            var Pinteresturl =  /^\s*(http\:\/\/)?pinterest\.com\/[a-z\d-_]{1,255}\s*$/i;
            if(value.length>1 && !Pinteresturl.test(value)) 
            {
                return false;
            }
            else 
            {
                return true;
            }
        },"Enter the valid Pinterest url !");

        $.validator.addMethod("validLinkedinUrl", function(value, element) 
        {            
            var Linkedinurl =  /^\s*(http\:\/\/)?linkedin\.com\/[a-z\d-_]{1,255}\s*$/i;
            if(value.length>1 && !Linkedinurl.test(value)) 
            {
                return false;
            }
            else 
            {
                return true;
            }
        },"Enter the valid Linkedin url !");

        $.validator.addMethod("validYoutubeUrl", function(value, element) 
        {            
            var Youtubeurl =  /^\s*(http\:\/\/)?youtube\.com\/[a-z\d-_]{1,255}\s*$/i;
            if(value.length>1 && !Youtubeurl.test(value)) 
            {
                return false;
            }
            else 
            {
                return true;
            }
        },"Enter the valid Linkedin url !");
        //url valiation end 
    }

});

$(document).on('keypress', '.mobileNoOnly', function (event) {		
    return (((event.which > 47) && (event.which < 58)) || (event.which == 13));
});

$(document).on('keypress', '.numericOnly', function (event) {		
    return (((event.which > 47) && (event.which < 58)) || (event.which == 13));
});

$('.namefield').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32){
        return true;
    }
    else{
        return false;
    }
});
$('.urlfield').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >= 48 && x <= 57) || (x >=97 && x <= 122) || x === 45){
        return true;
    }
    else{
        return false;
    }
});


$('.addressfield').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32 || x === 38 || (x >= 44 && x <= 57) ){
        return true;
    }
    else{
        return false;
    }
});

// $(document).ready(function () {

    if (pages == "register") {
        const togglenewpassword = document.querySelector('#togglePassword1');
        const password = document.querySelector('#password');

        togglenewpassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type2 = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type2);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
        });

        const togglePassword2 = document.querySelector('#togglePassword2');
        const password2 = document.querySelector('#confirm_password');

        togglePassword2.addEventListener('click', function (e) {
        // toggle the type attribute
        const type2 = password2.getAttribute('type') === 'password' ? 'text' : 'password';
        password2.setAttribute('type', type2);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
        });
           
    }
    if (modules == "signin" && pages == "index") {
        const togglenewpassword = document.querySelector('#togglenewpassword');
        const password = document.querySelector('#password');

        togglenewpassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type2 = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type2);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
        });
           
    }

    if(pages=="change-password")
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


    if (pages == 'social-media') {

        /*Get the country list */
        $("#doctor_social_media").validate({
            rules: {
                facebook: { validFBurl: true, maxlength: 255},
                twitter: { validTwitterUrl: true, maxlength: 255},
                instagram: { validInstagramUrl: true, maxlength: 255},
                pinterest: { validPinterestUrl: true, maxlength: 255},
                linkedin: { validLinkedinUrl: true, maxlength: 255},
                youtube: { validYoutubeUrl: true, maxlength: 255},
            },
			   messages: {
				   facebook: { required: "Enter FB URL", validFBurl: "Enter the valid FB URL"  }, 
				   twitter: { required: "Enter Twitter URL", validTwitterUrl: "Enter the valid Twitter URL"  },
				   instagram: { required:"Enter the Instagram URL" , validInstagramUrl: "Enter the valid Instagram URL"},
 			   	   pinterest: { required: "Enter the Pinterest URL", validPinterestUrl: "Enter the valid Pinterest URL"},
			  	   linkedin: { required: "Enter the Pinterest URL", validLinkedinUrl: "Enter the valid Linkedin URL"},	
				   youtube: { required: "Enter the Youtube URL",  validYoutubeUrl: "Enter the valid Youtube URL"}, 
			  },
            submitHandler: function (form) {

                $.ajax({
                    url: base_url + 'social-media',
                    data: $("#doctor_social_media").serialize(),
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
                                location.reload(true);
                            }, 1000);

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

// });

function isMobileDevice() {
  return window.innerWidth <= 768;
}

if (pages == 'invoice') {

    $(document).ready(function () {
        if (!isMobileDevice()) {
        // ✅ Desktop: initialize DataTable
        $('#invoice_cards').hide(); // hide card container
        $('#invoice_table').show();
        //datatables
        var invoice_table;

        invoice_table = $('#invoice_table').DataTable({
            'ordering': true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'invoice-list',
                "type": "POST",
                "data": function (data) {
                },
                error: function () {

                },
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [0, 5], //first column / numbering column
                    "orderable": false, //set not orderable
                },
            ],

        });

    }else{
        // ✅ Mobile: Fetch data and build card UI
        $('#invoice_table').hide(); // hide the table
        $('#invoice_cards').show();

 var invoice_table;
       invoice_table = $('#invoice_table').DataTable({
            'ordering': true,
            "processing": true, //Feature control the processing indicator.
            'bnDestroy': true,
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": base_url + 'invoice-list',
                "type": "POST",
                dataSrc: function(response){
                const data = response.data || [];
                console.log(response);
                let html = '';

                data.forEach(row => {
                    html += `
                    <div class="invoice-card-wrapper">
                      <div class="invoice-header">
                        <span class="invoice-number">#${row[1]}</span>
                        <span class="invoice-amount">${row[3]}</span>
                        ${row[5]}
                      </div>
                      <div class="invoice-meta">
                        <span class="invoice-date">${row[6]}</span>
                        ${row[7] == 'Paid' ? `<span class="invoice-status" style="color:#00BD45;margin-right: 20%;font-weight:500;font-size:14px;font:Poppins">${row[7]}</span>` 
                        : `<span class="invoice-status" style="color:#FD9720;margin-right: 20%;font-weight:500;font-size:14px;font:Poppins">${row[7]}</span>`}
                         <div></div>
                      </div>
                     
                     
                    </div>
                    `;
                });

                $('#invoice_cards').html(html);
                return data;
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

       
    }
    });

    
        
        
    }

    $('#invoice_table').on('xhr.dt', function (e, settings, json, xhr) {
    if (!json || json.data.length === 0) {
        $('.invoice-table-list-div').remove();

        $('.no-invoice').css({
            'display': 'flex',
            'justify-content': 'center',
            'align-items': 'center',
            'width': '100%',
        }).show();

        $('.no-invoice h2').css({
             'width': '100%',
        });
      
    }
});


/**
     * Patient Dashboard Email Verify
     */
function email_verification() {
    $.get(base_url + 'ajax/user-email-verify', function (data) {
        toastr.success(lg_activation_mail);
        setTimeout(function () {                
        window.location.reload();
        }, 3000);
    });
}