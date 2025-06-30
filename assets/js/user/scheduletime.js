const nth = function(d) 
        {
            if (d > 3 && d < 21) return 'th';
            switch (d % 10) {
                case 1:  return "st";
                case 2:  return "nd";
                case 3:  return "rd";
                default: return "th";
            }
        }     

function add_hours()
{

    var i=$('#slot_count').val();
    i =Number(i)+1;
    $('#slot_count').val(i);
    var j=Number(i)+1;
    var k=Number(i)-1;

    
    var hourscontent = '<div class="row form-row hours-cont" id="hours-cont_'+i+'">' +
    '<div class="col-12 col-md-11 ">' +
    '<h4 class="h4 text-center breadcrumb-bar px-2 py-1 mx-3 rounded text-white">'+i+'<sup>'+nth(i)+'</sup> '+lg_session+' </h4>' + 
    '<input type="hidden" name="sessions[]" value="'+i+'">'+
        '<div class="row form-row mx-3">' +
        '<div class="col-12 col-md-4">' +
            '<div class="form-group">' +
            '<label>'+lg_start_time+'</label>' +
            '<select class="form-control start_time" name="start_time['+i+']" onchange="get_end_time('+i+')" id="start_time_'+i+'">'+
                '<option value="">Select</option>'+
            '</select>' +
            '</div>' +
        '</div>' +
        '<div class="col-12 col-md-4">' +
            '<div class="form-group">' +
            '<label>'+lg_end_time+'</label>' +
            '<select class="form-control end_time" name="end_time['+i+']" onchange="get_time_slot('+j+'),get_tokens('+i+')" id="end_time_'+i+'">'+
                '<option value="">Select</option>'+
                '</select>'+
            '</div>' +
        '</div>' +
        '<div class="col-12 col-md-2">' + 
        '<div class="form-group">' + 
        '<label>'+lg_no_of_tokens+'</label>' + 
        '<input type="text" class="form-control" id="token_'+i+'" name="token['+i+']" readonly="">' + 
        '</div>' + 
        '</div>' +
        '<div class="col-12 col-md-2">' + 
        '<div class="form-group">' + 
        '<label>'+lg_type+'</label>' + 
        '<select class="form-control schedule_type" name="type['+i+']" id="slot_type_'+i+'">'+
            '<option value="">Select</option>'+
            '<option value="online">Online</option>'+
            '<option value="center">Center</option>'+
        '</select>' + 
        '</div>' + 
        '</div>' +
        '</div>' +
    '</div>' +
    '<div id="remove_btn_'+i+'" class="col-12 col-md-1 slot-drash-btn"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="javascript:void(0)" onclick="remove_session('+i+')" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>' +
    '</div>';
        
        $("#remove_btn_"+k).hide();
        $(".hours-info").append(hourscontent);
        get_time_slot(i)


        return false;
}

function remove_session(id)
{
    var k=Number(id)-1;
    $('#slot_count').val(k);
    $('#hours-cont_'+id).remove();
    toastr.error("Session deleted!");  
    $("#remove_btn_"+k).show();
}

function get_tokens(id)
{

    function addMinutes(time, minutes) {
        var date = new Date(new Date('01/01/2015 ' + time).getTime() + minutes * 60000);
        var tempTime = ((date.getHours().toString().length == 1) ? '0' + date.getHours() : date.getHours()) + ':' +
          ((date.getMinutes().toString().length == 1) ? '0' + date.getMinutes() : date.getMinutes()) + ':' +
          ((date.getSeconds().toString().length == 1) ? '0' + date.getSeconds() : date.getSeconds());
        return tempTime;
      }
      
    var slots  = $('#slots').val();
    var start_time=$('#start_time_'+id).val();
    var end_time=$('#end_time_'+id).val();    
    var countToken=0;
    slots=(slots==1)?60:slots;
    while (start_time != end_time) 
    {        
        start_time = addMinutes(start_time, slots);    
        countToken++;
    }
    $('#token_'+id).val(countToken);
}

function get_time_slot(id)
{
    var slot  = $('#slots').val();
    var sessions=$('#sessions_'+id).val();
    if(id==1)
    {
        var end_time='';
    }
    else
    {
        var a=Number(id)-1;
        var end_time=$('#end_time_'+a).val();

    }
    if(slot != '' )
    {
        $.ajax({
        type: "POST",
        url: base_url+'schedule/get-available-time-slots',
        data:{slot:$('#slots').val(),day_id:$('#day_id').val(),end_time:end_time,sessions:sessions}, 
        beforeSend :function(){
            $("#start_time_"+id+" option:gt(0)").remove();                 
            $("#end_time_"+id+" option:gt(0)").remove();                 
            $("#token_"+id+"").val(0);                 
            $('#start_time_'+id+',#end_time_'+id+'').find("option:eq(0)").html(lg_please_wait);                
        },                         
        success: function (data) {
           
            $('#start_time_'+id+',#end_time_'+id+'').find("option:eq(0)").html(lg_select_time);                
            if(data)
            {                
                var obj=jQuery.parseJSON(data);
                $(obj).each(function(){
                var option = $('<option />');
                if(this.added == true){
                    option.attr('value', this.value).text(this.label);
                    option.attr('disabled',true);           
                    option.addClass('d-none');           
                }else{
                    option.attr('value', this.value).text(this.label);           
                }             
                $('#start_time_'+id+',#end_time_'+id+'').append(option);               
                });                 
            }
        }
        });
    }
    else
    {
        $("#start_time_"+id+" option:gt(0)").remove();
        $("#end_time_"+id+" option:gt(0)").remove();
    }
}

function get_end_time(id)
{
    var slot = $('#slots').val();
    var start_time = $('#start_time_'+id).val();
    var sessions=$('#sessions_'+id).val();

    if(slot!='' && start_time!='')
    {
        $.ajax({
        type: "POST",
        url: base_url+'schedule/get-available-time-slots',
        data:{slot:slot,day_id:$('#day_id').val(),start_time:start_time}, 
        beforeSend :function(){
            //$('.overlay').show();          
            $("#end_time_"+id+" option:gt(0)").remove();                 
            $('#end_time_'+id).find("option:eq(0)").html(lg_please_wait);                
        },                         
        success: function (data) {
        // $('.overlay').hide();              
            $('#end_time_'+id).find("option:eq(0)").html(lg_select_time);                
            var obj=jQuery.parseJSON(data);
            $(obj).each(function(){
            var option = $('<option />');
            if(this.added == true){
                option.attr('value', this.value).text(this.label);
                option.attr('disabled',true);    
                option.addClass('d-none');         
            }else{
                option.attr('value', this.value).text(this.label);           
            }               
            $('#end_time_'+id).append(option);               
            });                 
        }
        });

    }
}

function add_slot(day_id,day_name,append_html)
{
    
    // var slot = $('#slots').val();
    // if(slot=='')
    // {
    //     toastr.error(lg_please_select_s2);
    // }
    // else
    // {
        $.post(base_url+'schedule/get-slots',{day_id:day_id,append_html:append_html,day_name:day_name},function(response)
        {
            $('.slotdetails').html(response);
            get_time_slot(1);
            $('#time_slot_modal').modal('show');
            $('#id_value').val(append_html);
            $('#day_id').val(day_id);
            $('#day_name').val(day_name);
            $('#slots').change(function(){
                get_time_slot(1);
            });
            $('#schedule_form').on('submit', function(event) 
            {

                    //Add validation rule for dynamically generated name fields
                $('.start_time').each(function() {
                    $(this).rules("add", 
                        {
                            required: true,
                            messages: {
                                required: lg_start_time_is_r,
                                
                            }
                        });
                });
                //Add validation rule for dynamically generated email fields
                $('.end_time').each(function() {
                    $(this).rules("add", 
                        {
                            required: true,
                            messages: {
                                required: lg_end_time_is_req,
                                
                            }
                        });
                });

                $('.schedule_type').each(function() {
                    $(this).rules("add", 
                        {
                            required: true,
                            messages: {
                                required: 'Type is required',
                                
                            }
                        });
                });

                event.preventDefault();
                
                if($('#schedule_form').validate().form()) 
                {
                    var day_id=$('[name="dayid[]"]:checked').val();

                    if(!day_id)
                    {
                        toastr.error(lg_please_choose_a);
                        return false;

                    }

                
                    $.ajax({
                        url: base_url+'schedule/add-schedule',
                        data: $("#schedule_form").serialize(),
                        type: "POST",
                        beforeSend: function(){
                            $('#submit_btn').attr('disabled',true);
                            $('#submit_btn').html('<div class="spinner-border text-light" role="status"></div>');
                        },
                        success: function(res){
                            $('#submit_btn').attr('disabled',false);
                            $('#submit_btn').html(lg_add10);
                            
                                var obj = JSON.parse(res);
                                
                                if(obj.status===200)
                                {

                                    var append_html = $('#id_value').val();
                                    $('#'+append_html).click();
                                    $('#time_slot_modal').modal('hide');
                                    $('#schedule_form')[0].reset();
                                    toastr.success(obj.msg);
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

            $("#schedule_form").validate();

        });
    // }
}

function edit_slot(day_id)
{

    $.post(base_url+'schedule/get-day-slots',{day_id:day_id},function(response)
    {
    
        var obj=JSON.parse(response);
        $('.slotdetails').html(obj.details);

        $('#time_slot_modal').modal('show');
        
        $('#slots').change(function(){
            get_time_slot(1);
        });

        $('#edit_schedule_form').on('submit', function(event) 
        {

            //Add validation rule for dynamically generated name fields
            $('.start_time').each(function() {
                $(this).rules("add", 
                    {
                        required: true,
                        messages: {
                            required: lg_start_time_is_r,
                            
                        }
                    });
            });

            //Add validation rule for dynamically generated email fields
            $('.end_time').each(function() {
                $(this).rules("add", 
                    {
                        required: true,
                        messages: {
                            required: lg_end_time_is_req,
                            
                        }
                    });
            });

        

            event.preventDefault();

        
            if($('#edit_schedule_form').validate().form()) 
            {
                
                $.ajax({
                    url: base_url+'schedule/update-schedule',
                    data: $("#edit_schedule_form").serialize(),
                    type: "POST",
                    beforeSend: function(){
                        $('#submit_btn').attr('disabled',true);
                        $('#submit_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function(res){
                        $('#submit_btn').attr('disabled',false);
                        $('#submit_btn').html(lg_add10);
                        
                            var obj = JSON.parse(res);
                            
                            if(obj.status===200)
                            {

                                var append_html = $('#id_value').val();
                                $('#'+append_html).click();
                                $('#time_slot_modal').modal('hide');
                                toastr.success(obj.msg);

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

        $("#edit_schedule_form").validate();


    });

}

(function($) {
    "use strict";


    

  if(pages=='scheduleTime')
  {
        
        $(document).ready(function()
        {
                $(document).on('click','.timingsnav li a',function(){
                    var day_id = $(this).attr('data-day-value');
                    var append_html = $(this).attr('data-append-value');
                    var day_name = $(this).text();
                    $('#id_value').val(append_html);
                    $('#day_id').val(day_id);
                    $('#day_name').val(day_name);
                        
                        $('#slot_'+append_html).html('<div class="d-flex justify-content-center"><div class="spinner-grow text-success" style="width: 3rem; height: 3rem;" role="status"></div></div>');
                        get_time_slot(1);
                        setTimeout(function() {
                            $.post(base_url+'schedule/schedule-list',{day_id:day_id,day_name:day_name,append_html:append_html},function(result){
                                $('#slot_'+append_html).html(result);    
                                //$('.overlay').hide();
                            });   
                        }, 500);                    
                });

                $(document).on('click','.delete_schedule',function(){
                    var delete_value = $(this).attr('data-delete-val');
                    var append_html = $('#id_value').val();
                    var c = confirm('Are you sure to delete?');
                    if(c){
                        $.post(base_url+'delete-schedule-time',{delete_value:delete_value},function(res){
                        if(res == 1){
                            $('#'+append_html).click();
                        }
                        });
                    }
                });

                $('#slots').change(function(){
                    //toastr.warning(lg_your_existing_s);
                    $("#sunday").click();
                });

                $("#sunday").click();
        });


        

    }

})(jQuery);