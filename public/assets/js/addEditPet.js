/*
Author       : Textteo
Template Name: Textteo - Bootstrap Template
Version      : 1.0
*/

(function($) {
    "use strict";
    
    function loadPetModal(petId = null) {
        var editMode = (petId !== null);
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
            error: function(error) {
                toastr.error('Failed to load pet data.');
            }
        });
    }  

    $('#addNewPetBtn').click(function() {
        loadPetModal();
    });

    function loadSelectPetModal() {
        $.ajax({
            url: base_url + 'patient/getPetSelectModal',
            type: 'POST',
            success: function(res) {
                let html=res.html;
                $('#selectPetModal .modal-content').html(html);
                $('#selectPetModal').modal('show');    
            },
            error: function() {
                toastr.error('Failed to load pet data.');
            }
        });
    }

    $(document).on('click', '#selectPetBtn', function() {
        loadSelectPetModal();
    });
    // Handle Add Pet button click
    
    $('.edit-pet').click(function() {
        var petId = $(this).data('pet-id');
        loadPetModal(petId);
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
                // petPhoto: {
                //     required: "Please upload a photo of the pet",
                //     imageFileType: "Only JPG, JPEG, or PNG files are allowed."
                // }
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
                                window.location.href = base_url + 'patient/profile';
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
    
    $('.delete-pet').click(function() {
        var petId = $(this).data('pet-id');
        $('#confirmDeleteModal').modal('show');

        $('#confirmDeleteBtn').unbind().click(function() {
            // AJAX Delete Request
            $.ajax({
                url: base_url + 'patient/delete-pet',
                type: 'POST',
                data: { pet_id: petId },
                beforeSend: function () {
                    $('#confirmDeleteBtn').attr('disabled', true);
                    $('#confirmDeleteBtn').html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function(res) {
                    $('#confirmDeleteBtn').attr('disabled', false);
                    $('#confirmDeleteBtn').html(lg_save_changes);
                    var obj = JSON.parse(res);
                    if (obj.status === 200) {
                        toastr.success(obj.msg);
                        // Reload page or update table after successful deletion
                        location.reload(); // Example: Reload the page
                    } else {
                        toastr.error(obj.msg);
                    }
                },
                error: function() {
                    $('#confirmDeleteBtn').attr('disabled', false);
                    $('#confirmDeleteBtn').html(lg_save_changes);
                    toastr.error('Failed to delete pet.');
                }
            });
        });
    });
    
    
    // Define breed options for each pet type
    

    // Function to update breed types based on selected pet type
    

    // Initialize the form with existing pet data if available
    document.addEventListener("DOMContentLoaded", () => {
        //updateBreedTypes();
    });
    
	
})(jQuery);

const breedOptions = {
        Dog: [
            { value: "Dog Mixed Breed", text: "Dog Mixed Breed" },
            { value: "French Bulldog", text: "French Bulldog" },
            { value: "Labrador Retriever", text: "Labrador Retriever" },
            { value: "Golden Retriever", text: "Golden Retriever" },
            { value: "German Shepherd Dog", text: "German Shepherd Dog" },
            { value: "Poodle", text: "Poodle" },
            { value: "Dachshund", text: "Dachshund" },
            { value: "Bulldog", text: "Bulldog" },
            { value: "Beagle", text: "Beagle" },
            { value: "Rottweiler", text: "Rottweiler" },
            { value: "Pembroke Welsh Corgi", text: "Pembroke Welsh Corgi" },
            { value: "German Shorthaired Pointer", text: "German Shorthaired Pointer" },
            { value: "Australian Shepherd", text: "Australian Shepherd" },
            { value: "Yorkshire Terrier", text: "Yorkshire Terrier" },
                        { value: "Other", text: "Other" },
           // Add more dog breeds as needed
        ],
        Cat: [
            { value: "Maine Coon", text: "Maine Coon" },
            { value: "Scottish Fold", text: "Scottish Fold" },
            { value: "Siamese cat", text: "Siamese cat" },
            { value: "British Shorthair", text: "British Shorthair" },
            { value: "Ragdoll", text: "Ragdoll" },
            { value: "Sphynx", text: "Sphynx" },
            { value: "Persian", text: "Persian" },
            { value: "Bengal", text: "Bengal" },
            { value: "Burmese", text: "Burmese" },
            { value: "Russian Blue", text: "Russian Blue" },
            { value: "Korat", text: "Korat" },
            { value: "LaPerm", text: "LaPerm" },
            { value: "Other", text: "Other" },
           // Add more cat breeds as needed
        ]
    };

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