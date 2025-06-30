
<style>
    /* Custom Upload Container */
    #petPhoto {
        display: none; /* Hide default input */
    }

    .custom-upload-container {
        border: none;
        border-radius: 8px;
        padding: 2%;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
        position: relative;
        overflow: hidden;
        margin-top: 5px;
        background-color: #F7F7F7;
    }

    .custom-upload-container.dragover {
        border-color: #ff8800;
        background: #fff0e0;
    }

    .upload-icon {
        font-size: 24px;
        color: #FD9720;
        margin-right: 12px;
        transition: all 0.3s ease;
        background-color: #FFE7C5;
        border-radius: 12px;
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        padding: 5%;
    }

    .custom-upload-container label {
        display: flex;
        justify-content: start;
        margin: 0;
    }

    .custom-upload-container h5 {
        color: #FD9720;
        margin: 0;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        font:'Poppins';
    }

    .preview-container {
        position: relative;
        margin-top: 1rem;
    }

    .image-preview {
        max-width: 50%;
        max-height: 150px;
        border-radius: 8px;
        display: none;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        display: none;
        padding: 3px 8px;
    }
  .upload-content {
    display: flex;
    align-items: center;  /* Vertical alignment */
    justify-content: center; /* Horizontal alignment */
    gap: 15px; /* Space between icon and text */
}

.text-container {
    text-align: left; /* Changed from center */
    /* Remove any previous margin/padding if needed */
}

/* Keep existing file-restrictions styles */
.file-restrictions {
    font-family: 'Poppins', sans-serif;
    font-weight: 400;
    font-size: 14px;
    color: #C3C3C3;
    margin-top: 4px;
    margin-bottom: 0;
}


/* Keep existing icon styles */

</style>

<form id="petForm">
    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title"><?= isset($pet) ? 'Edit Pet' : 'Add Pet' ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <!-- Modal Body -->
    <div class="modal-body">
        <input type="hidden" id="petId" name="petId" value="<?= isset($pet) ? $pet['id'] : '' ?>">
        <div class="col-md-12 col-lg-12 form-group">
            <div class="custom-upload-container">
                <input type="file" class="form-control" id="petPhoto" name="petPhoto" accept="image/*">
                <label for="petPhoto">
                      <div class="upload-content">
                        <?php if(isset($pet['pet_photo']) && !is_null($pet['pet_photo'])) {?>
                        <img src="<?php echo base_url().'uploads/pet_images/'.$pet['pet_photo']; ?>" width="60px" height="60px" class="upload-icon" alt="Pet Photo">
                        <?php } else{?>
                          <i class="fas fa-paw upload-icon"></i>
                        <?php } ?>
                        <div class="text-container">
                            <h5>Upload Pet Photo</h5>
                            <p class="file-restrictions">Allowed JPG or PNG</p>
                        </div>
                    </div>
                </label>

                <div class="preview-container">
                    <img src="#" class="image-preview" alt="Pet Photo Preview">
                    <button type="button" class="btn btn-danger btn-sm remove-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row">

        <div class="col-md-6 col-lg-6 form-group">
        <label for="petName">Pet Name</label>
        <input type="text" class="form-control" id="petName" name="petName"
                value="<?= isset($pet) ? $pet['pet_name'] : '' ?>"
                placeholder="Enter pet name">
        </div>

        <div class="col-md-6 form-group">
        <label for="petType">Type</label>
        <select class="form-control" id="petType" name="petType"
                onchange="updateBreedTypes('<?= ((isset($pet['breed_type'])) ? $pet['breed_type'] : '') ?>')">
            <option value="">Select Type</option>
            <option value="Dog" <?= isset($pet) && $pet['pet_type']=='Dog' ? 'selected' : '' ?>>Dog</option>
            <option value="Cat" <?= isset($pet) && $pet['pet_type']=='Cat' ? 'selected' : '' ?>>Cat</option>
        </select>
        </div>
    </div>

    <!-- Row 2: Type & Breed -->
    <div class="row">
        <div class="col-md-6 col-lg-6 form-group">
        <label for="petAge">Pet Age</label>
        <input type="text" class="form-control" id="petAge" name="petAge"
                value="<?= isset($pet) ? $pet['pet_age'] : '' ?>"
                placeholder="Enter pet Age">
        </div>

        <div class="col-md-6 col-lg-6 form-group">
        <label for="breedType">Breed Type</label>
        <select class="form-control" id="breedType" name="breedType">
            <option value="">Select Breed Type</option>
            <option value="Mixed Breed" <?= isset($pet) && $pet['breed_type']=='Mixed Breed' ? 'selected' : '' ?>>Mixed Breed</option>
            <option value="Pure Breed" <?= isset($pet) && $pet['breed_type']=='Pure Breed' ? 'selected' : '' ?>>Pure Breed</option>
        </select>
        </div>
    </div>

        <!-- <div class="form-group">
            <label for="petBirthDate">Pet Birth Date</label>
            <input type="text" class="form-control petBirthDatepicker" id="petBirthDate" name="petBirthDate" value="<?= isset($pet) ? date('d/m/Y', strtotime($pet['pet_birth_date'])) : '' ?>" placeholder="dd/mm/yyyy" >
        </div> -->
        <!-- <div class="form-group">
            <label for="breedSize">Breed Size</label>
            <select class="form-control" id="breedSize" name="breedSize">
                <option value="">Select Breed Size</option>
                
                 Add other sizes as needed -->
            <!-- </select> -->
        <!-- </div>  -->
        <!-- <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" id="gender" name="gender">
                <option value="">Select Gender</option>
               
                 Add other genders as needed -->
            <!-- </select>
        </div>  -->
        <!-- <div class="form-group">
            <label for="weight">Weight</label>
            <select class="form-control" id="weight" name="weight">
                <option value="">Select Weight</option>
               
                Add other weight conditions as needed -->
            <!-- </select>
        </div>  -->
        <!-- <div class="form-group">
            <label for="weightCondition">Weight Condition</label>
            <select class="form-control" id="weightCondition" name="weightCondition">
                <option value="">Select Weight Condition</option>
               
                Add other weight conditions as needed -->
            <!-- </select>
        </div> --> 
        <!-- <div class="form-group">
            <label for="activityLevel">Activity Level</label>
            <select class="form-control" id="activityLevel" name="activityLevel">
                <option value="">Select Activity Level</option>
                
                 Add other activity levels as needed -->
            <!-- </select>
        </div> --> 
       
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="savePetBtn"><?= isset($pet) ? 'Update' : 'Save' ?></button>
    </div>
</form>


<script>
$(document).ready(function() {
    const $container = $('.custom-upload-container');
    const $input = $('#petPhoto');
    const $preview = $('.image-preview');
    const $removeBtn = $('.remove-btn');

    // Handle file selection
    $input.on('change', function(e) {
        const file = e.target.files[0];
        handleFile(file);
    });

    // Drag & drop handling
    $container
        .on('dragover', function(e) {
            e.preventDefault();
            $container.addClass('dragover');
        })
        .on('dragleave', function(e) {
            e.preventDefault();
            $container.removeClass('dragover');
        })
        .on('drop', function(e) {
            e.preventDefault();
            $container.removeClass('dragover');
            const file = e.originalEvent.dataTransfer.files[0];
            $input[0].files = e.originalEvent.dataTransfer.files;
            handleFile(file);
        });

    // Handle file preview
    function handleFile(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                $preview.attr('src', e.target.result).show();
                $removeBtn.show();
            }
            
            reader.readAsDataURL(file);
        }
    }

    // Remove image
    $removeBtn.on('click', function(e) {
        e.stopPropagation();
        $input.val('');
        $preview.attr('src', '#').hide();
        $(this).hide();
    });
});
</script>