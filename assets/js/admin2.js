/*
Author       : Textteo
Template Name: Textteo - Bootstrap Admin Template
Version      : 1.3
*/

(function($) {
    "use strict";
	
	// Variables declarations
	
	var $wrapper = $('.main-wrapper');
	var $pageWrapper = $('.page-wrapper');
	var $slimScrolls = $('.slimscroll');
	
	// Sidebar
	
	var Sidemenu = function() {
		this.$menuItem = $('#sidebar-menu a');
	};
	
	function init() {
		var $this = Sidemenu;
		$('#sidebar-menu a').on('click', function(e) {
			if($(this).parent().hasClass('submenu')) {
				e.preventDefault();
			}
			if(!$(this).hasClass('subdrop')) {
				$('ul', $(this).parents('ul:first')).slideUp(350);
				$('a', $(this).parents('ul:first')).removeClass('subdrop');
				$(this).next('ul').slideDown(350);
				$(this).addClass('subdrop');
			} else if($(this).hasClass('subdrop')) {
				$(this).removeClass('subdrop');
				$(this).next('ul').slideUp(350);
			}
		});
		$('#sidebar-menu ul li.submenu a.active').parents('li:last').children('a:first').addClass('active').trigger('click');
	}
	
	// Sidebar Initiate
	init();
	
	// Mobile menu sidebar overlay
	
	$('body').append('<div class="sidebar-overlay"></div>');
	$(document).on('click', '#mobile_btn', function() {
		$wrapper.toggleClass('slide-nav');
		$('.sidebar-overlay').toggleClass('opened');
		$('html').addClass('menu-opened');
		return false;
	});
	
	// Sidebar overlay
	
	$(".sidebar-overlay").on("click", function () {
		$wrapper.removeClass('slide-nav');
		$(".sidebar-overlay").removeClass("opened");
		$('html').removeClass('menu-opened');
	});
	
	// Page Content Height
	
	if($('.page-wrapper').length > 0 ){
		var height = $(window).height();	
		$(".page-wrapper").css("min-height", height);
	}
	
	// Page Content Height Resize
	
	$(window).resize(function(){
		if($('.page-wrapper').length > 0 ){
			var height = $(window).height();
			$(".page-wrapper").css("min-height", height);
		}
	});
	
	// Select 2
	
    if ($('.select').length > 0) {
        $('.select').select2({
            minimumResultsForSearch: -1,
            width: '100%'
        });
    }
	
	// Datetimepicker
	
	if($('.datetimepicker').length > 0 ){
		$('.datetimepicker').datetimepicker({
			format: 'DD/MM/YYYY',
			icons: {
				up: "fa fa-angle-up",
				down: "fa fa-angle-down",
				next: 'fa fa-angle-right',
				previous: 'fa fa-angle-left'
			}
		});
		$('.datetimepicker').on('dp.show',function() {
			$(this).closest('.table-responsive').removeClass('table-responsive').addClass('temp');
		}).on('dp.hide',function() {
			$(this).closest('.temp').addClass('table-responsive').removeClass('temp')
		});
	}

	// Tooltip
	
	if($('[data-toggle="tooltip"]').length > 0 ){
		$('[data-toggle="tooltip"]').tooltip();
	}
	
    // Datatable

    // if ($('.datatable').length > 0) {
    //     $('.datatable').DataTable({
    //         "bFilter": false,
    //     });
    // }
	
	// Email Inbox

	if($('.clickable-row').length > 0 ){
		$(document).on('click', '.clickable-row', function() {
			window.location = $(this).data("href");
		});
	}

	// Check all email
	
	$(document).on('click', '#check_all', function() {
		$('.checkmail').click();
		return false;
	});
	if($('.checkmail').length > 0) {
		$('.checkmail').each(function() {
			$(this).on('click', function() {
				if($(this).closest('tr').hasClass('checked')) {
					$(this).closest('tr').removeClass('checked');
				} else {
					$(this).closest('tr').addClass('checked');
				}
			});
		});
	}
	
	// Mail important
	
	$(document).on('click', '.mail-important', function() {
		$(this).find('i.fa').toggleClass('fa-star').toggleClass('fa-star-o');
	});
	
	// Summernote
	
	if($('.summernote').length > 0) {
		$('.summernote').summernote({
			height: 200,                 // set editor height
			minHeight: null,             // set minimum height of editor
			maxHeight: null,             // set maximum height of editor
			focus: false                 // set focus to editable area after initializing summernote
		});
	}
	
    // Product thumb images

    if ($('.proimage-thumb li a').length > 0) {
        var full_image = $(this).attr("href");
        $(".proimage-thumb li a").click(function() {
            full_image = $(this).attr("href");
            $(".pro-image img").attr("src", full_image);
            $(".pro-image img").parent().attr("href", full_image);
            return false;
        });
    }

    // Lightgallery

    if ($('#pro_popup').length > 0) {
        $('#pro_popup').lightGallery({
            thumbnail: true,
            selector: 'a'
        });
    }
	
	// Sidebar Slimscroll

	if($slimScrolls.length > 0) {
		$slimScrolls.slimScroll({
			height: 'auto',
			width: '100%',
			position: 'right',
			size: '7px',
			color: '#ccc',
			allowPageScroll: false,
			wheelStep: 10,
			touchScrollStep: 100
		});
		var wHeight = $(window).height() - 60;
		$slimScrolls.height(wHeight);
		$('.sidebar .slimScrollDiv').height(wHeight);
		$(window).resize(function() {
			var rHeight = $(window).height() - 60;
			$slimScrolls.height(rHeight);
			$('.sidebar .slimScrollDiv').height(rHeight);
		});
	}
	
	// Small Sidebar

	$(document).on('click', '#toggle_btn', function() {
		if($('body').hasClass('mini-sidebar')) {
			$('body').removeClass('mini-sidebar');
			$('.subdrop + ul').slideDown();
		} else {
			$('body').addClass('mini-sidebar');
			$('.subdrop + ul').slideUp();
		}
		setTimeout(function(){ 
			mA.redraw();
			mL.redraw();
		}, 300);
		return false;
	});
	$(document).on('mouseover', function(e) {
		e.stopPropagation();
		if($('body').hasClass('mini-sidebar') && $('#toggle_btn').is(':visible')) {
			var targ = $(e.target).closest('.sidebar').length;
			if(targ) {
				$('body').addClass('expand-menu');
				$('.subdrop + ul').slideDown();
			} else {
				$('body').removeClass('expand-menu');
				$('.subdrop + ul').slideUp();
			}
			return false;
		}
	});

	$("#promo_form").submit(function (event) {
		event.preventDefault();
		$.ajax({
			url: base_url + 'admin/add-promo',
			data: $("#promo_form").serialize(),
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
	});

})(jQuery);

function activatePromo(id){
	$.ajax({
		url: base_url + 'admin/edit-promo',
		data: {id: id},
		type: "POST",
		beforeSend: function () {
		},
		success: function (res) {

		}
	});
}
/////////////////specialization////////////////////////
$('input[name = "specialization"]').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32){
        return true;

    }
    else{
        return false;
    }
});



//////////////////////Clinic name/////////////////////////////////


$('input[name = "clinic_name"]').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32){
        return true;

    }
    else{
        return false;
    }
});

/////////////////specialization//////////////////////// Added on 17-10-2022
// $('input[name = "first_name"]').on('keypress', function (event) {
//     var x = event.which || event.keyCode;
//     if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32){
//         return true;

//     }
//     else{
//         return false;
//     }
// });
/////////////////specialization//////////////////////// Added on 17-10-2022
$('input[name = "last_name"]').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32){
        return true;

    }
    else{
        return false;
    }
});
/////////////////specialization//////////////////////// Added on 17-10-2022
$('input[name = "name"]').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32){
        return true;

    }
    else{
        return false;
    }
});
/////////////////Add Country//////////////////////// Added on 28-10-2022////
$('input[name = "country"]').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32){
        return true;

    }
    else{
        return false;
    }
});
/////////////////Add Country//////////////////////// Added on 28-10-2022////
$('input[name = "sortname"]').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32){
        return true;

    }
    else{
        return false;
    }
});
/////////////////Add Country//////////////////////// Added on 28-10-2022////
$('input[name = "title"]').on('keypress', function (event) {
    var x = event.which || event.keyCode;
    if((x >= 65 && x <= 90) || (x >=97 && x <= 122) || x === 32){
        return true;

    }
    else{
        return false;
    }
});
