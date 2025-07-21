<div class="modal-header">
    <h5 class="modal-title">
        <?php echo $language['lg_add_time_slots']??""; ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="schedule_form" name="schedule_form" method="post" autocomplete="off">
        <input type="hidden" name="day_id" id="day_id" value="<?= $day_id; ?>">
        <div class="hours-info">
            <div class="row form-row hours-cont">
                <div class="col-6 col-md-6 mx-3">
                    <div class="form-group">               
                        <label><?php echo $language['lg_timing_slot_dur']??"";?></label>
                        <select class="select form-control" name="slot" id="slots">
                            <option value=""><?php echo $language['lg_select8']??"";?></option>                            
                            <!--<option value="5" <?php if(!empty($slot) && $slot=='5'){ echo "selected"; } ?>>5<?php echo $language['lg_mins']??""; ?></option>-->
                            <option value="10" <?php if(!empty($slot) && $slot=='10'){ echo "selected"; } ?>>10<?php echo $language['lg_mins']??""; ?></option>
                            <option value="15" <?php if(!empty($slot) && $slot=='15'){ echo "selected"; } ?>>15<?php echo $language['lg_mins']??""; ?></option>
                            <option value="20" <?php if(!empty($slot) && $slot=='20'){ echo "selected"; } ?>>20<?php echo $language['lg_mins']??""; ?></option>
                            <!--<option value="30" <?php if(!empty($slot) && $slot=='30'){ echo "selected"; } ?>>30<?php echo $language['lg_mins']??""; ?></option> 
                            <option value="45" <?php if(!empty($slot) && $slot=='45'){ echo "selected"; } ?>>45<?php echo $language['lg_mins']??""; ?></option>
                            <option value="1" <?php if(!empty($slot) && $slot=='1'){ echo "selected"; } ?>>1 &nbsp;<?php echo $language['lg_hour']??""; ?></option>-->                            
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-11">
                    <h4 class="h4 text-center breadcrumb-bar px-2 py-1 mx-3 rounded text-white">
                        1<sup>st</sup> 
                        <?php echo $language['lg_session']??""; ?> 
                    </h4> 
                    
                    <input type="hidden" name="sessions[]" id="sessions_1" value="1">
                    <div class="row form-row mx-3">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>
                                    <?php echo $language['lg_start_time']??""; ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control start_time" name="start_time[1]" onchange="get_end_time(1)" id="start_time_1">
                                    <option value=""><?php echo $language['lg_select']??""; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>
                                    <?php echo $language['lg_end_time']??""; ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control end_time" name="end_time[1]" onchange="get_time_slot(2),get_tokens(1)" id="end_time_1">
                                    <option value=""><?php echo $language['lg_select']??""; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-2"> 
                            <div class="form-group"> 
                                <label>
                                    <?php echo $language['lg_no_of_tokens']??""; ?>
                                </label> 
                                <input type="text" class="form-control" id="token_1" name="token[1]" readonly=""> 
                            </div> 
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label class="d-block"><?php echo $language['lg_type'];?></label>
                                <select class="form-control schedule_type" name="type[1]" id="slot_type_1">
                                    <option value=""><?php echo $language['lg_select']??""; ?></option>
                                    <option value="online">Online</option>
                                    <!--<option value="center">Center</option>-->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- set as default hidden  -->
        <div class="default-opti mb-3 d-none">
            <p><?php echo $language['lg_set_as_default']??""; ?></p>
            <div class="form-check-inline">
                <label class="form-check-label">
                <input type="checkbox" <?php 
                
                
                if (in_array("1", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='1') echo 'checked';?> name="dayid[]" value="1">Sun
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                <input type="checkbox" <?php if (in_array("2", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='2') echo 'checked';?> name="dayid[]" value="2">Mon
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                <input type="checkbox" <?php if (in_array("3", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='3') echo 'checked';?> name="dayid[]" value="3">Tue
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                <input type="checkbox" <?php if (in_array("4", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='4') echo 'checked';?> name="dayid[]" value="4">Wed
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                <input type="checkbox" <?php if (in_array("5", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='5') echo 'checked';?> name="dayid[]" value="5">Thu
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                <input type="checkbox" <?php if (in_array("6", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='6') echo 'checked';?> name="dayid[]" value="6">Fri
                </label>
            </div>
            <div class="form-check-inline">
                <label class="form-check-label">
                <input type="checkbox" <?php if (in_array("7", $already_day_id)) {echo "disabled"; } ?> class="form-check-input" <?php if($day_id=='7') echo 'checked';?> name="dayid[]" value="7">Sat
                </label>
            </div>
        </div>

        <div class="add-more mb-3 mx-3">
        <a href="javascript:void(0);" onclick="add_hours()" class="add-hours"><i class="fa fa-plus-circle"></i> <?php echo $language['lg_add_more']??""; ?></a>
        </div>
        <div class="submit-section text-center">
        <button type="submit" id="submit_btn" class="btn btn-primary submit-btn"><?php echo $language['lg_save']??""; ?></button>
        </div>
    </form>
</div>