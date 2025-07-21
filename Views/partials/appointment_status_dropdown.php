<?php
    $options = [
        [
            'value' => '-',
            'name' => 'Please choose status',
            'disabled' => true
        ],
        [
            'value' => 0,
            'name' => 'New',
            'disabled' => false
        ],
        [
            'value' => 1,
            'name' => 'Completed',
            'disabled' => false
        ],
        [
            'value' => 2,
            'name' => 'Expired',
            'disabled' => false
        ],
    ];
?>
<div class="actions">
    <select name="appointment_status" class="form-control appointment_status" id="<?=esc($id);?>">
        <?php foreach($options as $item) { ?>
        <option 
            value="0" 
            <?=$selected == $item['value'] ? 'selected' : '';?>
            <?=$item['disabled'] ? 'disabled' : '';?>
        >
            <?=$item['name'];?>
        </option>
        <?php } ?>
    </select>
</div>