<style>
    .list-group-scroll {
        max-height: 300px; /* adjust as needed */
        overflow-y: auto;
    }
    .list-group-item {
        transition: 0.3s;
        cursor: pointer;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .list-group-item.active {
        border-color: #f0ad4e;
        background-color: #fff8e1;
    }
    .rounded-pill {
        border-radius: 50rem !important;
    }
</style>

<form id="petSelectForm">
    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title font-weight-bold">Select Pet</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Modal Body -->
    <div class="modal-body">
        <!-- Scrollable Pet List -->
        <div class="list-group list-group-scroll mb-3">
            <?php foreach($pets as $pet) { ?>
                <label class="list-group-item list-group-item-action d-flex align-items-center border rounded p-3">
                    <img src="<?php echo base_url('uploads/pet_images/' . $pet['pet_photo']); ?>" alt="<?php echo $pet['pet_name']; ?>" class="rounded-circle" width="48" height="48">
                    <div class="ml-3 flex-grow-1">
                        <div class="font-weight-bold"><?php echo $pet['pet_name']; ?></div>
                        <div class="text-muted small"><?php echo $pet['pet_type']; ?></div>
                    </div>
                    <input class="form-check-input ml-auto" type="radio" id="petSelectedId" 
                    data-pet-name=<?php echo $pet['pet_name']; ?>
                    data-pet-photo=<?php echo base_url('uploads/pet_images/' . $pet['pet_photo']); ?>
                    data-pet-type=<?php echo $pet['pet_type']; ?>
                     name="petSelectedId" value="<?php echo $pet['id']; ?>">
                </label>
            <?php } ?>
        </div>

        <!-- Add Pet Card OUTSIDE the scroll -->
        <div id="addNewPetBtn" class="list-group-item list-group-item-action d-flex align-items-center border rounded p-3 mb-4" style="cursor:pointer;">
            <div class="d-flex justify-content-center align-items-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                <i class="fas fa-plus"></i> <!-- FontAwesome plus icon -->
            </div>
            <div class="ml-3 flex-grow-1">
                <div class="font-weight-bold text-muted">Add Pet</div>
            </div>
        </div>

        <!-- Reason for visit -->
        <div class="form-group">
            <label class="font-weight-bold">What is the reason for visit?</label>
            <textarea class="form-control rounded" name="reason" id="reasonPetVisit" rows="4" placeholder="A detailed description of any symptoms or other relevant information will better prepare your veterinarian for this appointment"></textarea>
        </div>

    </div>

    <!-- Modal Footer -->
    <?php if(session('user_id') && user_detail(session('user_id'))['first_name'] != null) {?>
        <div class="modal-footer">
          <button type="submit" id="savePetSelect" class="btn btn-warning text-white rounded-pill px-4">Next</button>
        </div>
    <?php } else{?>
        <div class="modal-footer">
          <button type="submit" id="saveRequiredUserInfoBtn" class="btn btn-warning text-white rounded-pill px-4">Next</button>
        </div>
    <?php } ?>
    
   
</form>

<script>
   
(function($) {
    "use strict";


    function showUserInfoModal() {
        
        $('#saveRequiredInfoModal .modal-content').html(`
            <h2 style="padding:2%;">Fill in your details</h2>
            <form class="form-grid" id="saveUserInfoForm">
            <div class="modal-header">
                <h4 class="modal-title font-weight-bold"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
             <div class="modal-body" >
             <div class="row">
             
             <div class="col-md-6 col-gl-6 col-sm-6">
             <div class="form-group">
                <label for="first-name">First Name*</label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" />
            </div>
             </div>

              <div class="col-md-6 col-gl-6 col-sm-6">
             <div class="form-group">
                <label for="last-name">Last Name*</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" />
            </div>
             </div>

              <div class="col-md-6 col-gl-6 col-sm-6">
              <div class="form-group full-width">
                <label for="mobile">Mobile Number*</label>
                <div class="phone-input">
                <input type="text" class="form-control" name="mobileno" id="mobileno" placeholder="0 000-00-00" />
                </div>
            </div>
             </div>

             </div>
           
            <div class="col-md-12 col-gl-12 col-sm-12">
            <button id="saveRequiredInfoBtn" style="width:100%" class="btn btn-warning text-white rounded-pill px-4">SAVE</button>
            </div>
            </div>
            </form>
        `);
        $('#saveRequiredInfoModal').modal('show');
    }

    $('#saveRequiredUserInfoBtn').click(function(event) {
        event.preventDefault();
        var selectedPet = $('input[name="petSelectedId"]:checked');
            if (!selectedPet.length) {
                toastr.warning('Please select pet');
                return;
            }
        var reason = $('#reasonPetVisit').val();
        if (!reason) {
                toastr.warning('Please enter Reason');
                return;
            }
            var selectedPetId = selectedPet.val(); // pet ID from value attribute

            var pet_data = {
                pet_id: selectedPetId,
                pet_name: selectedPet.data('pet-name'),
                pet_photo: selectedPet.data('pet-photo'),
                pet_type: selectedPet.data('pet-type'),
                reason_pet_visit: $('#reasonPetVisit').val() ?? '',
            };

            $('#pet_id').val(selectedPetId);
            $('#reason_pet_visit').val($('#reasonPetVisit').val());

            sessionStorage.setItem(sessionStorePet, JSON.stringify(pet_data));
            console.log(sessionStorage.getItem(sessionStorePet));
            $('#selectPetModal').modal('hide');
            showUserInfoModal();

    });

    // function loadUserInfoModal() {
        
    // }

    $(document).on('click', '#saveRequiredInfoBtn', function(event){
        event.preventDefault();
        $("#saveUserInfoForm").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 2
                },
                last_name: {
                    required: true,
                    minlength: 2
                },
                mobileno:{
                    required: true,
                    minlength: 12
                }
                
            },
            messages: {
                first_name: {
                    required: "Please enter the first name",
                    minlength: "firstName name must be at least 2 characters long"
                },
                last_name: {
                    required: "Please enter the last name",
                    minlength: "lastName must be at least 2 characters long"
                },
                mobileno: {
                    required: "Please enter the mobile number",
                    minlength: "mobileNumber must be at least 12 characters long"
                },
            },
            submitHandler: function(form) {
                var formData = new FormData(form); // Create FormData object from form
                let url= base_url + 'patient/update-required-proile';

                $.ajax({
                    url: url,
                    data: formData,
                    type: "POST",
                    processData: false,  // Important: prevent jQuery from processing the FormData
                    contentType: false,  // Important: let the server handle the contentType
                    beforeSend: function () {
                        $('#saveRequiredInfoBtn').attr('disabled', true);
                        $('#saveRequiredInfoBtn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#saveRequiredInfoBtn').attr('disabled', false);
                        var obj = JSON.parse(res);
                        if (obj.status === 200)
                        {
                            toastr.success(obj.msg);
                            setTimeout(function () {
                                window.location.reload(true);
                            }, 2000);
                        } 
                        else
                        {
                            toastr.error(obj.msg);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX error: ' + status, error);
                        toastr.error('An error occurred while processing your request. Please try again.');
                        $('#saveRequiredInfoBtn').attr('disabled', false);
                    }
                });

                return false;  // Prevent the form from submitting traditionally
            }

        });
        $("#saveUserInfoForm").submit();
    });
    
    function loadPetModal(petId = null) {
        var ditMode = (petId !== null);
        $.ajax({
            url: base_url + 'patient/getPetModal',
            type: 'POST',
            data: { pet_id: petId },
            success: function(res) {
                let html=res.html;
                let pet=res.pet;
                $('#addPetModal .modal-content').html(html);
                $('#addPetModal').modal('show');
                initializeModal(pet);
                
                if (editMode) {
                    $('#petPhoto').rules('add', {
                        imageFileType: true
                    });
                } else {
                    $('#petPhoto').rules('add', {
                        required: true,
                        imageFileType: true
                    });
                }
                
            },
            error: function() {
                toastr.error('Failed to load pet data.');
            }
        });
    } 
    
    $('#addNewPetBtn').click(function() {
            $('#selectPetModal').modal('hide');
            loadPetModal();
    });
    //var maxDate = $('#maxDate_pet_case').val();
    
    // Define custom method for file type validation
    $.validator.addMethod('imageFileType', function(value, element) {
        // Check if element has files (for file input)
        if (element.files && element.files.length > 0) {
            // Get file extension
            var extension = element.files[0].name.split('.').pop().toLowerCase();
            // Check if file extension is in the allowed list
            return ['jpg', 'jpeg', 'png'].indexOf(extension) !== -1;
        }
        return true;  // No file selected, so no validation needed
    }, 'Only JPG, JPEG, or PNG files are allowed.');
    
    function initializeModal(pet=null) {
        
        //var maxDate = $('#maxDate_pet_case').val();
        
        // Pricing Options Show
        // $('.petBirthDatepicker').datepicker({
        //     //startView: 2,
        //     format: 'dd/mm/yyyy',
        //     autoclose: true,
        //     todayHighlight: true,
        //     endDate:maxDate
        // });
        
        $("#petForm").validate({
            rules: {
                petName: {
                    required: true,
                    minlength: 2
                },
                // petBirthDate: {
                //     required: true,
                //     //date: true
                // },
                petType: {
                    required: true
                },
                petAge: {
                    required: true,
                },
                breedType: {
                    required: true
                },
                // breedSize: {
                //     required: true
                // },
                // gender: {
                //     required: true
                // },
                // weight: {
                //     required: true
                // },
                // weightCondition: {
                //     required: true
                // },
                // activityLevel: {
                //     required: true
                // },
                petPhoto: {
                    //required: true,
                    //imageFileType: true  // Use custom method for file type validation
                }
            },
            messages: {
                petName: {
                    required: "Please enter the pet name",
                    minlength: "Pet name must be at least 2 characters long"
                },
                // petBirthDate: {
                //     required: "Please enter the pet birth date",
                //     //date: "Please enter a valid date"
                // },
                petType: {
                    required: "Please select the pet type"
                },
                petAge: {
                    required: "Please enter the pet age",
                },
                breedType: {
                    required: "Please select the breed type"
                },
                // breedSize: {
                //     required: "Please select the breed size"
                // },
                // gender: {
                //     required: "Please select the gender"
                // },
                // weight: {
                //     required: "Please select the weight category"
                // },
                // weightCondition: {
                //     required: "Please select the weight condition"
                // },
                // activityLevel: {
                //     required: "Please select the activity level"
                // },
                petPhoto: {
                    required: "Please upload a photo of the pet",
                    imageFileType: "Only JPG, JPEG, or PNG files are allowed."
                }
            },
            submitHandler: function(form) {
                var formData = new FormData(form); // Create FormData object from form

                // Append petPhoto file from input field to FormData (if present)
                var petPhotoFile = $('#petPhoto')[0].files[0];
                if (petPhotoFile) {
                    formData.append('petPhoto', petPhotoFile);
                }
                
                let url= base_url + 'patient/' + ($('#petId').val() ? 'edit-pet' : 'create-pet');

                $.ajax({
                    url: url,
                    data: formData,
                    type: "POST",
                    processData: false,  // Important: prevent jQuery from processing the FormData
                    contentType: false,  // Important: let the server handle the contentType
                    beforeSend: function () {
                        $('#create_new_pet_btn').attr('disabled', true);
                        $('#create_new_pet_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    },
                    success: function (res) {
                        $('#create_new_pet_btn').attr('disabled', false);
                        $('#create_new_pet_btn').html(lg_save_changes);

                        var obj = JSON.parse(res);

                        if (obj.status === 200) {
                            toastr.success(obj.message);
                            setTimeout(function () {
                                window.location.reload(true);
                            }, 2000);
                        } else {
                            toastr.error(obj.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX error: ' + status, error);
                        toastr.error('An error occurred while processing your request. Please try again.');
                        $('#create_new_pet_btn').attr('disabled', false);
                        $('#create_new_pet_btn').html(lg_save_changes);
                    }
                });

                return false;  // Prevent the form from submitting traditionally
            }

        });
        
        let already_saved_breed_type=(typeof pet!='undefined' && pet!=null)?pet.breed_type:'';
        updateBreedTypes(already_saved_breed_type);
        
    }
    
})(jQuery);

function updateBreedTypes(breed_type='') {
        const petType = document.getElementById("petType").value;
        const breedTypeSelect = document.getElementById("breedType");

        // Clear existing options
        breedTypeSelect.innerHTML = '<option value="">Select Breed Type</option>';

        // Populate breed type options based on selected pet type
        if (breedOptions[petType]) {
            breedOptions[petType].forEach(breed => {
                const option = document.createElement("option");
                option.value = breed.value;
                option.textContent = breed.text;
                breedTypeSelect.appendChild(option);
            });
        }
    
        if(breed_type!=''){
           breedTypeSelect.value=breed_type;
        }
    }

    
</script>