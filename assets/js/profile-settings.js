/*
Author       : Dreamguys
Template Name: Doccure - Bootstrap Template
Version      : 1.0
*/

(function($) {
    "use strict";

    var maxDate = $('#maxDate').val();
	
	// Pricing Options Show
	
	$('#pricing_select input[name="price_type"]').on('click', function() {
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
	
	// Education Add More
	
    $(".education-info").on('click','.trash', function () {
		$(this).closest('.education-cont').remove();
		return false;
    });

    $(".add-education").on('click', function () {
		
		var educationcontent = '<div class="row form-row education-cont">' +
			'<div class="col-12 col-md-10 col-lg-11">' +
				'<div class="row form-row">' +
					'<div class="col-12 col-md-6 col-lg-4">' +
						'<div class="form-group">' +
							'<label>'+lg_degree+'</label>' +
							'<input type="text" name="degree[]" class="form-control degree inputcls" maxlength="100">' +
						'</div>' +
					'</div>' +
					'<div class="col-12 col-md-6 col-lg-4">' +
						'<div class="form-group">' +
							'<label>'+lg_collegeinstitut+'</label>' +
							'<input type="text" name="institute[]" class="form-control institute inputcls" maxlength="100">' +
						'</div>' +
					'</div>' +
					'<div class="col-12 col-md-6 col-lg-4">' +
						'<div class="form-group">' +
							'<label>'+lg_year_of_complet+'</label>' +
							'<input type="text" name="year_of_completion[]" readonly class="form-control years year_of_completion inputcls">' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
			'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>' +
		'</div>';
		
        $(".education-info").append(educationcontent);
		  $('.years').datepicker({
		    startView: 2,
		    minViewMode: 2,
		    format: 'yyyy',
		    endDate:maxDate,
		    autoclose: true
		   });
        return false;
    });
	
	// Experience Add More
	
    $(".experience-info").on('click','.trash', function () {
		$(this).closest('.experience-cont').remove();
		return false;
    });

    $(".add-experience").on('click', function () {
    	var i=$('#experience_count').val();
    	 i =Number(i)+1;
		$('#experience_count').val(i);
		var experiencecontent = '<div class="row form-row experience-cont">' +
			'<div class="col-12 col-md-10 col-lg-11">' +
				'<div class="row form-row">' +
					'<div class="col-12 col-md-6 col-lg-3">' +
						'<div class="form-group">' +
							'<label>'+lg_hospital_name+'</label>' +
							'<input type="text" name="hospital_name[]" class="form-control" maxlength="150">' +
						'</div>' +
					'</div>' +
					'<div class="col-12 col-md-6 col-lg-3">' +
						'<div class="form-group">' +
							'<label>'+lg_from+'</label>' +
							'<input type="text" name="from[]" id="from" readonly class="form-control years">' +
						'</div>' +
					'</div>' +
					'<div class="col-12 col-md-6 col-lg-3">' +
						'<div class="form-group">' +
							'<label>'+lg_to3+'</label>' +
							'<input type="text" name="to[]" id="to" readonly class="form-control years">' +
						'</div>' +
					'</div>' +
					'<div class="col-12 col-md-6 col-lg-3">' +
						'<div class="form-group">' +
							'<label>'+lg_designation+'</label>' +
							'<input type="text" name="designation[]" class="form-control" maxlength="100">' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
			'<div class="col-12 col-md-2 col-lg-1"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>' +
		'</div>';
		
        $(".experience-info").append(experiencecontent);
      	$('.years').datepicker({
		    startView: 2,
		    minViewMode: 2,
		    format: 'yyyy',
		    endDate:maxDate,
		    autoclose: true
		   });
    
        return false;
    });
	
	// Awards Add More
	
    $(".awards-info").on('click','.trash', function () {
		$(this).closest('.awards-cont').remove();
		return false;
    });

    $(".add-award").on('click', function () {

        var regcontent = '<div class="row form-row awards-cont">' +
			'<div class="col-12 col-md-5">' +
				'<div class="form-group">' +
					'<label>'+lg_awards+'</label>' +
					'<input type="text" name="awards[]" class="form-control" maxlength="100">' +
				'</div>' +
			'</div>' +
			'<div class="col-12 col-md-5">' +
				'<div class="form-group">' +
					'<label>'+lg_year+'</label>' +
					'<input type="text" name="awards_year[]" readonly class="form-control years">' +
				'</div>' +
			'</div>' +
			'<div class="col-12 col-md-2">' +
				'<label class="d-md-block d-sm-none d-none">&nbsp;</label>' +
				'<a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a>' +
			'</div>' +
		'</div>';
		
        $(".awards-info").append(regcontent);
        $('.years').datepicker({
		    startView: 2,
		    minViewMode: 2,
		    format: 'yyyy',
		    endDate:maxDate,
		    autoclose: true
		   });
        return false;
    });
	
	// Membership Add More
	
    $(".membership-info").on('click','.trash', function () {
		$(this).closest('.membership-cont').remove();
		return false;
    });

    $(".add-membership").on('click', function () {

        var membershipcontent = '<div class="row form-row membership-cont">' +
			'<div class="col-12 col-md-10 col-lg-5">' +
				'<div class="form-group">' +
					'<label>'+lg_memberships+'</label>' +
					'<input type="text" name="memberships[]" class="form-control" maxlength="100">' +
				'</div>' +
			'</div>' +
			'<div class="col-12 col-md-2 col-lg-2">' +
				'<label class="d-md-block d-sm-none d-none">&nbsp;</label>' +
				'<a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a>' +
			'</div>' +
		'</div>';
		
        $(".membership-info").append(membershipcontent);
        return false;
    });
	
	// Registration Add More
	
    $(".registrations-info").on('click','.trash', function () {
		$(this).closest('.reg-cont').remove();
		return false;
    });

    $(".add-reg").on('click', function () {

        var regcontent = '<div class="row form-row reg-cont">' +
			'<div class="col-12 col-md-5">' +
				'<div class="form-group">' +
					'<label>'+lg_registrations+'</label>' +
					'<input type="text" name="registrations[]" class="form-control" maxlength="150">' +
				'</div>' +
			'</div>' +
			'<div class="col-12 col-md-5">' +
				'<div class="form-group">' +
					'<label>'+lg_year+'</label>' +
					'<input type="text" name="registrations_year[]" readonly class="form-control years">' +
				'</div>' +
			'</div>' +
			'<div class="col-12 col-md-2">' +
				'<label class="d-md-block d-sm-none d-none">&nbsp;</label>' +
				'<a href="#" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a>' +
			'</div>' +
		'</div>';
		
        $(".registrations-info").append(regcontent);
        $('.years').datepicker({
		    startView: 2,
		    minViewMode: 2,
		    format: 'yyyy',
		    endDate:maxDate,
		    autoclose: true
		   });
        return false;
    });

// function experience_counts()
// {
// 	var count=$('#experience_count').val();

//     for (var j = 0; j < count; j++) {

//     	$('#from'+j).datepicker({
// 		    startView: 2,
// 		    minViewMode: 2,
// 		    format: 'yyyy',
// 		    endDate:maxDate,
// 		    autoclose: true
// 		   });
//         $('#from'+j).change(function(){
//         	$('#to'+j).val('');
//             $('#to'+j).datepicker("destroy");
//             var year = $('#from'+j).val();
//             alert(year);
//             $('#to'+j).datepicker({
//             startDate: year,
//             startView: 2,
// 		    minViewMode: 2,
// 		    format: 'yyyy',
// 		    endDate:maxDate,
// 		    autoclose: true
//            }); 
//         });
    	
//     }

// }

// experience_counts();
     

    $('#dob').datepicker({
    startView: 2,
    format: 'dd/mm/yyyy',
    autoclose: true,
    todayHighlight: true,
    endDate:maxDate
   });

    $('.years').datepicker({
    startView: 2,
    minViewMode: 2,
    format: 'yyyy',
    endDate:maxDate,
    autoclose: true,
	startDate: '-60y', //2021 -1950
    endDate: maxDate //2021-2011
	
	
   });
	
})(jQuery);