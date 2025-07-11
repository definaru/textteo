(function (factory) {
  if (typeof define === 'function' && define.amd) {
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    // Node / CommonJS
    factory(require('jquery'));
  } else {
    factory(jQuery);
  }
})(function ($) {
  'use strict';
  var console = window.console || { log: function () {} };

  function CropAvatar($element) {
    this.$container     = $element;
    this.$avatarView = $('.avatar-view-btn');
    this.$avatar        = $('.avatar-view-img');
    this.$avatarModal   = $('#avatar-modal');
    this.$loading       = $('.loading');
    this.$avatarForm    = this.$avatarModal.find('.avatar-form');
    this.$avatarUpload  = this.$avatarForm.find('.avatar-upload');
    this.$avatarSrc     = this.$avatarForm.find('.avatar-src');
    this.$avatarData    = this.$avatarForm.find('.avatar-data');
    this.$avatarInput   = this.$avatarForm.find('.avatar-input');
    this.$avatarSave    = this.$avatarForm.find('.avatar-save');
    this.$avatarBtns    = this.$avatarForm.find('.avatar-btns');
    this.$avatarWrapper = this.$avatarModal.find('.avatar-wrapper');
    this.$avatarPreview = this.$avatarModal.find('.avatar-preview');
    this.init();
  }

  CropAvatar.prototype = {
    constructor: CropAvatar,
    support: {
      fileList: !!$('<input type="file">').prop('files'),
      blobURLs: !!window.URL && URL.createObjectURL,
      formData: !!window.FormData
    },

    init: function () {
      this.support.datauri = this.support.fileList && this.support.blobURLs;

      if (!this.support.formData) {
        this.initIframe();
      }
      this.initModal();
      this.addListener();
    },

    addListener: function () {
      this.$avatarView.on('click', $.proxy(this.click, this));
      this.$avatarInput.on('change', $.proxy(this.change, this));
      this.$avatarForm.on('submit', $.proxy(this.submit, this));
      this.$avatarBtns.on('click', $.proxy(this.rotate, this));
    },
    initModal: function () {
      this.$avatarModal.modal({
        show: false
      });
    },

    initPreview: function () {
      var url = this.$avatar.attr('src');
      this.$avatarPreview.html('<img src="' + url + '">');
    },

    initIframe: function () {
      var target = 'upload-iframe-' + (new Date()).getTime(),
          $iframe = $('<iframe>').attr({
            name: target,
            src: ''
          }),
          _this = this;

      // Ready ifrmae
      $iframe.one('load', function () {

        // respond response
        $iframe.on('load', function () {
          var data;
          try {
            data = $(this).contents().find('body').text();
          } catch (e) {
            console.log(e.message);
          }

          if (data) {
            try {
              data = $.parseJSON(data);
            } catch (e) {
              console.log(e.message);
            }

            _this.submitDone(data);
          } else {
            _this.submitFail('Image upload failed!');
          }

          _this.submitEnd();

        });
      });

      this.$iframe = $iframe;
      this.$avatarForm.attr('target', target).after($iframe.hide());
    },

    click: function () {
      this.$avatarModal.modal('show');
      //this.initPreview();
    },

    change: function () {
      var files,file;

      if (this.support.datauri) {
        files = this.$avatarInput.prop('files');

        if (files.length > 0) {
          file = files[0];

          var file_size = file.size;
          // if(file_size>2097152 && this.isImageFile(file)) {
          //   $("#image_upload_size_error").css('display','block');
          //   $("#image_upload_size_error").html("File size is greater than 2MB");	
          //    $('#avatarInput').val('');
          //   return false;
          // } 
          // else{
          //   $("#image_upload_size_error").html("");	
          // }

          if(file_size < 1048576) 
					{
						 //$(".cropit-preview-image").show();
						  //$(".cropit-preview-background").show();
					         	
					}
					else
					{
						toastr.error("File size is greater than 1MB");
		    		$('#avatarInput').val('');
					}

          if (this.isImageFile(file)) {
              $('#image_upload_error').css('display','none');
            if (this.url) {
              URL.revokeObjectURL(this.url); // Revoke the old one
            }

            this.url = URL.createObjectURL(file);
            this.startCropper();
          }
          else
          {
              $('#image_upload_error').css('display','block');
              $('#avatarInput').val('');
              return false;
          }
		 
		  
		  
        }
      } else {
        file = this.$avatarInput.val();

        if (this.isImageFile(file)) {
          this.syncUpload();
        }
      }
    },

    submit: function () {
      if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
        return false;
      }

      if (this.support.formData) {
        this.ajaxUpload();
        return false;
      }
    },

    rotate: function (e) {
      var data;

      if (this.active) {
        data = $(e.target).data();

        if (data.method) {
          this.$img.cropper(data.method, data.option);
        }
      }
    },

    isImageFile: function (file) {
      if (file.type) {
        return /^image\/\w+$/.test(file.type);
      } else {
        return /\.(jpg|jpeg|png|gif)$/.test(file);
      }
    },

    startCropper: function () {
      var _this = this;

      if (this.active) {
        this.$img.cropper('replace', this.url);
      } else {
        this.$img = $('<img src="' + this.url + '">');
        this.$avatarWrapper.empty().html(this.$img);
        this.$img.cropper({
          aspectRatio: 1/1,
          preview    : this.$avatarPreview.selector,
          strict     : false,
          crop       : function (data) {
            var json = [
						  '{"x":' + data.x,
						  '"y":' + data.y,
						  '"height":' + data.height,
						  '"width":' + data.width,
						  '"rotate":' + data.rotate + '}'
                ].join();

            _this.$avatarData.val(json);
          }
        });

        this.active = true;
      }

      this.$avatarModal.one('hidden.bs.modal', function () {
      //  _this.$avatarPreview.empty();
        _this.stopCropper();
      });
    },

    stopCropper: function () {
      if (this.active) {
        this.$img.cropper('destroy');
        this.$img.remove();
        this.active = false;
      }
    },

    ajaxUpload: function () {
      var url = this.$avatarForm.attr('action'),
          data = new FormData(this.$avatarForm[0]),
          _this = this;

      $.ajax(url, {
					type       : 'post',
					data       : data,
					dataType   : 'json',
					processData: false,
					contentType: false,

					beforeSend: function () {
					  _this.submitStart();
					},

					success: function (data) {
					  _this.submitDone(data);
					  toastr.success("Profile Image Updated Successfully....");
					},

					error: function (XMLHttpRequest, textStatus, errorThrown) {
					  _this.submitFail(textStatus || errorThrown);
					},

					complete: function () {
					  _this.submitEnd();
					}
      });
    },

    syncUpload: function () {
      this.$avatarSave.click();
    },

    submitStart: function () {
      this.$loading.fadeIn();
    },

    submitDone: function (data) {
      console.log(data);

      if ($.isPlainObject(data) && data.state === 200) {

         if (data.result) {
			  this.url = data.result;
			  $('#crop_prof_img').val(data.img_name1);
			  if (this.support.datauri || this.uploaded) {
				this.uploaded = false;
				this.cropDone();
			  } else {
				this.uploaded = true;
				this.$avatarSrc.val(base_url+this.url);
 				this.startCropper();
			
			  }

              this.$avatarInput.val('');
        } else if (data.message) {
              this.alert(data.message);
        }

      } else {
        this.alert('Failed to response');
      }
    },

    submitFail: function (msg) {
      this.alert(msg);
    },

    submitEnd: function () {
      this.$loading.fadeOut();
    },

    cropDone: function () {
      this.$avatarForm.get(0).reset();
      this.$avatar.attr('src', base_url+this.url);
      this.stopCropper();
      this.$avatarModal.modal('hide');
    },

    alert: function (msg) {
      var $alert = [
            '<div class="alert alert-danger avatar-alert alert-dismissable">',
              '<button type="button" class="close" data-dismiss="alert">&times;</button>',
              msg,
            '</div>'
          ].join('');

      this.$avatarUpload.after($alert);
    }
  };

  $(function () {
    return new CropAvatar($('#crop-avatar'));
  });

});
