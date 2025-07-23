<?php
    $id = session('user_id') && user_detail(session('user_id'))['first_name'] != null ? 'savePetSelect' : 'saveRequiredUserInfoBtn';
?>
<div class="modal fade" id="selectPetModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="petSelectForm">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title font-weight-bold">Select Pet</h4>
                    <button type="button" class="btn p-0 close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: #757575;font-size: 26px">&times;</span>
                    </button>
                </div>
                <div class="modal-body card-pets">
                    <div class="list-group list-group-scroll mb-3">
                        <?php foreach($pets as $pet) { ?>
                            <label class="list-group-item list-group-item-action d-flex rounded p-3 bg-light gap-3" role="button">
                                <img 
                                    src="<?=base_url('uploads/pet_images/' . $pet['pet_photo']); ?>" 
                                    alt="<?=$pet['pet_name']; ?>" 
                                    class="rounded-3" 
                                    style="width: 60px;height: 60px;object-fit: cover"
                                />
                                <div class="ml-3 flex-grow-1">
                                    <div class="title"><?=$pet['pet_name']; ?></div>
                                    <div class="text-muted small"><?=$pet['pet_type']; ?></div>
                                </div>
                                <input 
                                    class="form-check-input ml-auto d-none" 
                                    type="checkbox" 
                                    id="petSelectedId" 
                                    data-pet-name=<?=$pet['pet_name']; ?>
                                    data-pet-photo=<?=base_url('uploads/pet_images/' . $pet['pet_photo']); ?>
                                    data-pet-type=<?=$pet['pet_type']; ?>
                                    name="petSelectedId" 
                                    value="<?=$pet['id']; ?>"
                                />
                            </label>
                        <?php } ?>
                    </div>

                    <!-- https://getbootstrap.com/docs/5.3/components/modal/#toggle-between-modals -->
                    <div id="addNewPetBtn" class="list-group-item list-group-item-action d-flex align-items-center bg-light gap-3 rounded p-3 mb-4" role="button">
                        <div class="d-flex justify-content-center align-items-center bg-white rounded-4" style="width: 60px;height: 60px;color: #C3C3C3">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="title">Add Pet</div>
                    </div>

                    <div class="form-group m-0">
                        <p class="title">What is the reason for visit?</p>
                        <p style="color:#C3C3C3;font-size:14px;font-weight: 400;line-height: 140%;">
                            A detailed description of any symptoms or other relevant 
                            information will better prepare your veterinarian for 
                            this appointment  
                        </p>
                        <textarea 
                            class="form-control rounded" 
                            name="reason" 
                            id="reasonPetVisit" 
                            rows="4" 
                            placeholder="e.g. when did you first notice symptoms"
                            style="resize: none;"
                        ></textarea>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <div class="d-grid w-100">
                        <button type="submit" id="<?=$id;?>" class="btn btn-warning btn-lg px-4">
                            Next
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
