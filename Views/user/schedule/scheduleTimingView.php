<?php 
 
if(!empty($available_time)){ ?>
    <h4 class="card-title d-flex justify-content-between">
        <span>
            <?php         
                echo $language['lg_time_slots']??""; 
            ?>
            <?php echo "(".$available_time[0]['slot'].")"; ?>
        </span> 
        <a class="edit-link" data-toggle="modal" onclick="add_slot('<?php 
			/** @var string $append_html */
			/** @var int $day_id */
			/** @var string $day_name */
		echo $day_id;?>','<?php echo $day_name;?>','<?php echo $append_html;?>');" href="javascript:void(0);"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_slot']??""; ?></a>
        <!-- <a class="edit-link" data-toggle="modal" onclick="edit_slot('<?php echo $day_id;?>');" href="javascript:void(0);"><i class="fa fa-edit mr-1"></i><?php echo $language['lg_edit_slot']??""; ?></a> -->
    </h4>
    <div class="doc-times">
        <?php 
        foreach ($available_time as $key => $value)
        { ?>
                <div class="doc-slot-list">
                    <?php echo date('g:i a',strtotime($value['start_time'])); ?> - <?php echo date('g:i a',strtotime($value['end_time'])); ?>
                    (Session <?php echo $value['sessions']; ?> Token <?php echo $value['token']; ?>)<a href="javascript:void(0)" class="delete_schedule" data-delete-val="<?php echo $value['id']; ?>"> <i class="fa fa-times"></i></a>		
                </div>
        <?php 
        } ?>

    </div>
<?php 
} 
else 
{ ?>
	<h4 class="card-title d-flex justify-content-between">
		<span><?php echo $language['lg_time_slots']??""; ?></span> 
		<a class="edit-link" data-toggle="modal" onclick="add_slot('<?php 
			/** @var string $append_html */
			/** @var int $day_id */
			/** @var string $day_name */
		echo $day_id;?>','<?php echo $day_name;?>','<?php echo $append_html;?>');" href="javascript:void(0);"><i class="fa fa-plus-circle"></i><?php echo $language['lg_add_slot']??""; ?></a>
	</h4>
	<p class="text-muted mb-0"><?php echo $language['lg_not_available']??""; ?></p>

<?php }


