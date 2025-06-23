// H-3-4
// if(modules=='doctor' || modules=='patient')
// {
setInterval(function() {
 
 $.ajax({
      method:'get',
      dataType:'json',
      url:base_url+'get-appoinment-call',
      success:function(res){
        if(res.status===200){
           var html='<div class="call-inner">'+
                  '<div class="call-user">'+
                    '<img class="call-avatar" src="'+res.profileimage+'" alt="User Image">'+
                    '<h4>'+res.role+' '+res.name+'</h4>'+
                    '<span>'+lg_calling_+'</span>'+
                  '</div>'+              
                  '<div class="call-items">'+
                    '<a href="javascript:void(0);" onclick="end_call(\''+res.appointment_id+'\')" class="btn call-item call-end" data-dismiss="modal" aria-label="Close"><i class="material-icons">call_end</i></a>';
                    if(res.call_type=='Audio')
                    {
                      html +='<a target="_blank" onclick="incoming_call(\''+res.appointment_id+'\')" href="javascript:void(0);" class="btn call-item call-start"><i class="fas fa-phone"></i></a>';
                    }
                    else
                    {
                      html +='<a target="_blank" onclick="incoming_video_call(\''+res.appointment_id+'\')" href="javascript:void(0);" class="btn call-item call-start"><i class="fas fa-video"></i></a>';
                    }
                    
                  html +='</div>'+
                '</div>'; 
           $('.appoinments_users_details').html(html);     
           $('#appoinment_user').modal('show');
           playAudio();
        }
        else
        {
           $('.appoinments_users_details').html('');     
           $('#appoinment_user').modal('hide');
        }
        
      }
    });
}, 5000);
// }

if(modules=='patient')
{
    if(pages=='book_appoinments')
  {

function getSchedule()
{

    
    var date = $('#schedule_date').val(); 
    var schedule_date = date.split("/").reverse().join("-");  
    if(schedule_date == ''){
        $('#schedule_date_error').html('<small class="help-block" data-bv-validator="notEmpty" data-bv-for="schedule_date" data-bv-result="INVALID" style="color:red;">'+lg_date_is_require+'</small>');
        return false;
    }  
   
    $('#schedule_date_error').html('');
    var doctor_id = $('#doctor_id').val();
    $.post(base_url+'get-schedule-from-date',{schedule_date:schedule_date,doctor_id:doctor_id},function(response){
     $('.bookings-schedule').html(response);
     $('[data-toggle="tooltip"]').tooltip();
    });
}



getSchedule();

//getSchedule()

$(document).ready(function(){

        $('#schedule_date').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true,
          todayHighlight: true,
          startDate: "d"
        });

});


  function checkout(){
    var doctor_id = $('#doctor_id').val();
    var hourly_rate = $('#hourly_rate').val();
    var price_type = $('#price_type').val();
    var role_id = $('#role_id').val();
    //var type = $("input[name='type']:checked"). val();  
    var type;
  
  
    if(role_id==6)
    {
      type="Clinic";
    }
    else
    {
      type="Online";
    }
  
    var appoinment_token = $("input[name='token']:checked").val();
    var appoinment_date=$("input[name='slots']:checked").attr('data-date');
    var appoinment_timezone=$("input[name='slots']:checked").attr('data-timezone');
    var appoinment_time=$("input[name='slots']:checked").attr('data-time');
    var appoinment_session=$("input[name='slots']:checked").attr('data-session');
    var appoinment_pet_id=$("#booking_pet_id").val();    
        if(!appoinment_token){
          toastr.warning(lg_please_select_a1);
           return false;
      }
      
      if(typeof appoinment_pet_id=='undefined' || appoinment_pet_id=="" || appoinment_pet_id==null || appoinment_pet_id=="null"){
         toastr.warning('Please select pet');
           return false;
      }
      
      var appointment_data = [];
       appointment_data.push({'appoinment_token':$("input[name='token']:checked").val(),
                  'appoinment_date':$("input[name='token']:checked").attr('data-date'),
                  'appoinment_timezone':$("input[name='token']:checked").attr('data-timezone'),
                  'appoinment_start_time':$("input[name='token']:checked").attr('data-start-time'),
                  'appoinment_end_time':$("input[name='token']:checked").attr('data-end-time'),
                  'appoinment_session':$("input[name='token']:checked").attr('data-session'),
                  'appointment_type':$("input[name='token']:checked").attr('data-schedule-type'),
                  'type':type,
                  'appoinment_pet_id':appoinment_pet_id
              });
       var appointment_details = JSON.stringify(appointment_data);
  
          $('#pay_btn').attr('disabled',true);
          $('#pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
          
          $.post(base_url+'set-booked-session',{
              hourly_rate:hourly_rate,
              appointment_details:appointment_details,
              price_type:$('#price_type').val(),
              doctor_id:doctor_id,
              doctor_role_id:role_id
              
          },function(res){
  
            var obj = JSON.parse(res);
              if(obj.status===200)
              {
                  setTimeout(function(){ window.location=base_url+'checkout-appoinment'; },1000);
              }
              else if(obj.status===500)
              {
                toastr.error(obj.message);
                $('#pay_btn').attr('disabled',false);
                $('#pay_btn').html(lg_proceed_to_pay);
              }
              else
              {
                  toastr.success(lg_transaction_suc);
                  setTimeout(function() {
                    window.location.href=base_url+modules;
                }, 2000);
              }   
          });
  
    }




}

}

function playAudio() { 
  var x = document.getElementById("myAudio");
  x.play(); 
} 

function pauseAudio() { 
  var x = document.getElementById("myAudio");
  x.pause(); 
}

var Windows; 
  
function incoming_call(appointment_id)
{

         //pauseAudio();
          Windows = window.open(base_url+'incoming-call/'+appointment_id, 
                "_blank", "width=1500, height=650"); 

          Windows.onbeforeunload = function(){ end_call(appointment_id); }
          
}

function incoming_video_call(appointment_id)
{

         //pauseAudio();
          Windows = window.open(base_url+'incoming-video-call/'+appointment_id, 
                "_blank", "width=1500, height=650"); 

          Windows.onbeforeunload = function(){ end_call(appointment_id); }
          
}

function outgoing_video_call(appointment_id)
{
      Windows = window.open(base_url+'outgoing-video-call/'+appointment_id, 
                "_blank", "width=1500, height=650");
      Windows.onbeforeunload = function(){ end_call(appointment_id); }
   
}

function outgoing_call(appointment_id)
{
      Windows = window.open(base_url+'outgoing-call/'+appointment_id, 
                "_blank", "width=1500, height=650");
      Windows.onbeforeunload = function(){ end_call(appointment_id); }
   
}

function remove_calldetails(appointment_id){
  $.post(base_url+'appoinments/remove_calldetails',{appointment_id:appointment_id},function(data){   
    //alert("Call Ended");
    // console.log("Window Closed") ;
  });
}

function end_call(appointment_id){

  $.post(base_url+'appoinment-end-call',{appointment_id:appointment_id},function(data){
    // console.log(data)
     // Windows.close(); 
     if(roles==2)
     {
        add_rating(data,appointment_id);
     }       
  });
}

function add_rating(doctor_id,appointment_id)
{
    $.post(base_url+'appt-doctor-details',{doctor_id:doctor_id,appointment_id:appointment_id},function(data){
      var obj = JSON.parse(data);
      if(obj.status.call_status==1 && obj.status.review_status==0){
      $('#doctor_name').html(obj.name);
      $('#doctor_id').val(doctor_id);
      $('#rating_appointment_id').val(appointment_id);
      $('#ratings_review_modal').modal('show');
      }
      
    });
  
} 


     $(document).ready(function(){

      /*$('#rating_reviews_form').submit(function(e) {

    var form = $(this);

    e.preventDefault();

    $.ajax({
        type: "POST",
        url: base_url+'appoinments/add_reviews_',
        data: form.serialize(), // <--- THIS IS THE CHANGE
        beforeSend: function(){
          $('#review_btn').attr('disabled',true);
          $('#review_btn').html('<div class="spinner-border text-light" role="status"></div>');
         },
       success: function(data){
        $('#review_btn').attr('disabled',false);
        $('#review_btn').html(lg_add_review);
        toastr.success(lg_thank_you_for_y);
          setTimeout(function() {
             window.location.href=base_url+'dashboard';
         }, 2000);
            
        },
        error: function() { alert(lg_error_posting_f); }
   });

});*/

      /*submit form ajax template*/
      $("#rating_reviews_form").submit(function(e) {
          e.preventDefault();
      }).validate({
        rules: {
          title: {
            reviews_validation: true,
            maxlength: 100,
          },
          review: {
            reviews_validation: true,
            maxlength: 100,
          },
        },
        messages: {
          title: {
            maxlength: lg_ratings_validation_title_max,
            reviews_validation: lg_ratings_validation_title_val,
          },
          review: {
            maxlength: lg_ratings_validation_review_max,
            reviews_validation: lg_ratings_validation_review_val,
          },
        },
        submitHandler: function (form) {
          // form data
          var formData = new FormData($('#rating_reviews_form')[0]);

          // ajax
          $.ajax({
            type: "POST",
            url: base_url+'appt-add-review',
            data: formData,
            beforeSend: function(){
              $('#review_btn').attr('disabled',true);
              $('#review_btn').html('<div class="spinner-border text-light" role="status"></div>');
             },
           success: function(data){
            $('#review_btn').attr('disabled',false);
            $('#review_btn').html(lg_add_review);
            toastr.success(lg_thank_you_for_y);
              setTimeout(function() {
                 window.location.href=base_url+modules;
             }, 2000);
                
            },
            error: function() { alert(lg_error_posting_f); },
            cache: false,
            contentType: false,
            processData: false
          });
          return false;
        }
      });
      /*submit form ajax template*/

    }); 

