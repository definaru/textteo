 $(function() {
        $('.image-editor').cropit({
          exportZoom: 1.25,
          imageBackground: true,
          imageBackgroundBorderWidth: 20,
         
        });

        $('.rotate-cw').click(function() {
          $('.image-editor').cropit('rotateCW');
        });
        $('.rotate-ccw').click(function() {
          $('.image-editor').cropit('rotateCCW');
        });
		$('#fileopen').on("change", function(){ 
			var files = $('#fileopen')[0].files[0];
			var u = URL.createObjectURL(this.files[0]);		 
			var ext = $('#fileopen').val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			toastr.error(lg_invalid_extensi);
			$("#fileopen").val('');
			
			} else
			{
					var img = new Image;   
				img.src = u;	 
				img.onload = function() {	
					if(img.width >= 680 && img.height >=454) 
					{
							//$(".cropit-preview-image").show();
							//$(".cropit-preview-background").show();
									
					}
					else
					{
						toastr.error(lg_please_upload_s);		 
						$('#fileopen').val('');
					}					
					
				}
			}
			
		 
		});
		 });
 	$(document).ready(function(){
        $('.export').click(function() {
			var files = $('#fileopen')[0].files[0];
			if(files.size > 2097152) {
				toastr.error('File size must be under 2MB!');
			} else {
				var imageData = $('.image-editor').cropit('export');
				
				var url=base_url+'pharmacy/image_upload';
				var row_id= $('#row_id').val();
				var dataString="img_data="+imageData+"&row_id="+row_id; 
				var file1 = $('#fileopen').val(); 
				$("#error_msg_model").html('');
				if(file1.length>1){
				$.ajax( {
							url:url,
							type       : 'post',
							data       : dataString,
							enctype    : 'multipart/form-data',
							dataType   : 'json',
							beforeSend: function () {
								$('.export').attr('disabled',true);
								$('.export').html('<div class="spinner-border text-light" role="status"></div>');
								
							},

							success: function (data) {
		
								$('.export').attr('disabled',false);
								$('.export').html(lg_done);
							
								if (data.result) {
									$('#image-errors').hide(); 		
								$(".uploaded-section").append(data.result);
								$('#error_msg_model').html('');
								$(".cropit-preview-image").attr('src','');
								$(".cropit-preview-background").attr('src','');
								$("#fileopen").val("");

								var v1 = $("#upload_image_url").val();
								var p1 = $("#upload_preview_image_url").val();

									if (v1.length > 0) {
											var v2 = [];
											v2.push(v1);
											v2.push(data.image_url);
											$("#upload_image_url").val(v2);
									} else {
											var array = [];
											array.push(data.image_url);
											$("#upload_image_url").val(array);
									}

									if (p1.length > 0) {
											var p2 = [];
											p2.push(p1);
											p2.push(data.preview_image_url);
											$("#upload_preview_image_url").val(p2);
									} else {
											var array = [];
											array.push(data.preview_image_url);
											$("#upload_preview_image_url").val(array);
									}
									$('#row_id').val(data.row_id);

								
								}
							$("#avatar-image-modal").modal('hide');
						},
						complete: function () {

								$("#imageimg_loader").hide();
								$(".export").html(lg_done);
							}
					});
				}
				else
					{
						$("#upload_image_url").val('');
						$("#upload_preview_image_url").val('');
						toastr.error(lg_please_upload_s); 		 
					}
			}
        });
    });
     
	  
	  