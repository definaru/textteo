<form id="petForm">
    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title"><?= isset($pet) ? 'Edit Pet' : 'Add Pet' ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <!-- Modal Body -->
    <div class="modal-body">
        <input type="hidden" id="petId" name="petId" value="<?= isset($pet) ? $pet['id'] : '' ?>">

        <div class="form-group">
            <label for="petName">Pet Name</label>
            <input type="text" class="form-control" id="petName" name="petName" value="<?= isset($pet) ? $pet['pet_name'] : '' ?>" placeholder="Enter pet name">
        </div>
        <div class="form-group">
            <label for="petBirthDate">Pet Birth Date</label>
            <input type="text" class="form-control petBirthDatepicker" id="petBirthDate" name="petBirthDate" value="<?= isset($pet) ? date('d/m/Y', strtotime($pet['pet_birth_date'])) : '' ?>" placeholder="dd/mm/yyyy" >
        </div>
        <div class="form-group">
            <label for="petType">Type</label>
            <select class="form-control" id="petType" name="petType" onchange="updateBreedTypes('<?=((isset($pet) && isset($pet['breed_type']))?$pet['breed_type']:'')?>')">
                <option value="">Select Type</option>
                <option value="Dog" <?= isset($pet) && $pet['pet_type'] == 'Dog' ? 'selected' : '' ?>>Dog</option>
                <option value="Cat" <?= isset($pet) && $pet['pet_type'] == 'Cat' ? 'selected' : '' ?>>Cat</option>
                <!-- Add other types as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="breedType">Breed Type</label>
            <select class="form-control" id="breedType" name="breedType">
                <option value="">Select Breed Type</option>
                <?php /*?><option value="Mixed Breed" <?= isset($pet) && $pet['breed_type'] == 'Mixed Breed' ? 'selected' : '' ?>>Mixed Breed</option>
                <option value="Pure Breed" <?= isset($pet) && $pet['breed_type'] == 'Pure Breed' ? 'selected' : '' ?>>Pure Breed</option><?php */?>
            </select>
        </div>
        <div class="form-group">
            <label for="breedSize">Breed Size</label>
            <select class="form-control" id="breedSize" name="breedSize">
                <option value="">Select Breed Size</option>
                <option value="Small" <?= isset($pet) && $pet['breed_size'] == 'Small' ? 'selected' : '' ?>>Small</option>
                <option value="Medium" <?= isset($pet) && $pet['breed_size'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                <option value="Large" <?= isset($pet) && $pet['breed_size'] == 'Large' ? 'selected' : '' ?>>Large</option>
                <!-- Add other sizes as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" id="gender" name="gender">
                <option value="">Select Gender</option>
                <option value="Male" <?= isset($pet) && $pet['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= isset($pet) && $pet['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                <!-- Add other genders as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="weight">Weight</label>
            <select class="form-control" id="weight" name="weight">
                <option value="">Select Weight</option>
                <option value="Underweight" <?= isset($pet) && $pet['weight'] == 'Underweight' ? 'selected' : '' ?>>Underweight</option>
                <option value="Normal" <?= isset($pet) && $pet['weight'] == 'Normal' ? 'selected' : '' ?>>Normal</option>
                <option value="Overweight" <?= isset($pet) && $pet['weight'] == 'Overweight' ? 'selected' : '' ?>>Overweight</option>
                <!-- Add other weight conditions as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="weightCondition">Weight Condition</label>
            <select class="form-control" id="weightCondition" name="weightCondition">
                <option value="">Select Weight Condition</option>
                <option value="Good" <?= isset($pet) && $pet['weight_condition'] == 'Good' ? 'selected' : '' ?>>Good</option>
                <option value="Needs Attention" <?= isset($pet) && $pet['weight_condition'] == 'Needs Attention' ? 'selected' : '' ?>>Needs Attention</option>
                <!-- Add other weight conditions as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="activityLevel">Activity Level</label>
            <select class="form-control" id="activityLevel" name="activityLevel">
                <option value="">Select Activity Level</option>
                <option value="Low" <?= isset($pet) && $pet['activity_level'] == 'Low' ? 'selected' : '' ?>>Low</option>
                <option value="Moderate" <?= isset($pet) && $pet['activity_level'] == 'Moderate' ? 'selected' : '' ?>>Moderate</option>
                <option value="High" <?= isset($pet) && $pet['activity_level'] == 'High' ? 'selected' : '' ?>>High</option>
                <!-- Add other activity levels as needed -->
            </select>
        </div>
        <div class="form-group">
            <label for="petPhoto">Choose Pet Photo</label>
            <input type="file" class="form-control" id="petPhoto" name="petPhoto" accept="image/*">
        </div>
    </div>

    <!-- Modal Footer -->
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="savePetBtn"><?= isset($pet) ? 'Update' : 'Save' ?></button>
    </div>
</form>