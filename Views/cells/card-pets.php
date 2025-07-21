<?php 
    $image_size = 60;
?>
<?php if (!empty($user_pets)) : ?>
    <?php foreach ($user_pets as $index => $pet) { ?>
    <div class="col-md-6">
        <div class="d-flex p-3 bg-light card-pets">
            <div class="me-3">
                <?php if (!empty($pet['pet_photo'])) : ?>
                    <img 
                        src="<?= base_url('uploads/pet_images/' . $pet['pet_photo']); ?>" 
                        class="rounded-3"
                        alt="Pet Image" 
                        style="width: <?=$image_size;?>px;height: <?=$image_size;?>px;object-fit: cover"
                    />
                <?php else : ?>
                    <svg class="bd-placeholder-img rounded-3" role="img" width="<?=$image_size;?>" height="<?=$image_size;?>">
                        <rect width="100%" height="100%" fill="#868e96"></rect>
                        <text x="13%" y="50%" fill="#dee2e6" dy=".3em" style="font-size: 9px">No Image</text>
                    </svg>
                <?php endif; ?>
            </div>
            <div class="flex-grow-1">
                <div class="vstack">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <span class="title">
                                <?=htmlspecialchars($pet['pet_name']);?><?=!empty($pet['pet_age']) ? ', '.$pet['pet_age'] : '';?>
                            </span>
                        </div>
                        <div class="d-flex gap-3">
                            <button type="button" class="btn p-0 edit-pet" data-pet-id="<?=$pet['id'];?>">
                                <i class="fas fa-edit" style="color:#545454"></i>
                            </button>
                            <button type="button" class="btn p-0 delete-pet" data-pet-id="<?=$pet['id'];?>">
                                <i class="fas fa-trash-alt" style="color:#FF4444"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text">
                        <?=htmlspecialchars($pet['pet_type']);?>, <?=htmlspecialchars($pet['breed_type']);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
<?php else : ?>
    <div class="col-12 py-5">
        <p class="text-center">No pets found.</p>
    </div>
<?php endif; ?>	


