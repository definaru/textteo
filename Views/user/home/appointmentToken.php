<div class="bookings-schedule">
<?php  if (!empty($schedule)) {
    $i = 1;
    $token = 1;
    foreach ($schedule as $rows) {
        $time_zone = $rows['time_zone'];
        $current_timezone = session('time_zone');
        $current_time = strtotime(date('Y-m-d H:i:s'));
        $converted_end_time = converToTz($schedule_date . ' ' . $rows['end_time'], $current_timezone, $time_zone);
        $endtime = strtotime($converted_end_time);

        if ($current_time <= $endtime) {
            $start = strtotime(converToTz($rows['start_time'], $current_timezone, $time_zone));
            $end = strtotime(converToTz($rows['end_time'], $current_timezone, $time_zone));
            $datas = array();

            if ($rows['slot'] >= 6) {
                    for ($j = $start; $j <= $end; $j = $j + $rows['slot'] * 60) {
                        $datas[] = date('H:i:s', $j);
                    }
            } else {
                    for ($j = $start; $j <= $end; $j = $j + 60 * 60) {
                        $datas[] = date('H:i:s', $j);
                    }
            } ?>
            <div class="d-flex flex-wrap justify-content-start w-100">
                <div style="gap: 8px;display: grid;grid-template-columns: auto auto auto;width: 100%;">
                    <?php for ($k = 0; $k <  $rows['token']; $k++) {
                        $l = $k + 1;
                        $start_time = converToTz($schedule_date . ' ' . $datas[$k],  $current_timezone, $time_zone);

                    if (date('Y-m-d H:i:s') < $schedule_date . ' ' . $datas[$k]) {
                            $booked_session = get_booked_session($i, $token, $start_time, $rows['user_id']);
                            $time_display = date('h:i A', strtotime(converToTz($datas[$k], $current_timezone, $time_zone)));
                            $appt_start_time = date('H:i:s', strtotime(converToTz($datas[$k], $time_zone, $current_timezone)));
                            $appt_end_time = date('H:i:s', strtotime(converToTz($datas[$l], $time_zone, $current_timezone)));                            

                            if ($booked_session >= 1) { ?>
                                <div class="slot-booked" title="Booked"><?=$time_display;?></div>
                            <?php } else { ?>
                                <div 
                                    role="button"
                                    class="slot" 
                                    data-schedule-type="<?=$rows['type'];?>" 
                                    data-date="<?=date('Y-m-d', strtotime(str_replace('/', '-', $schedule_date)))?>" 
                                    data-timezone="<?=$rows['time_zone'];?>" 
                                    data-start-time="<?=$appt_start_time;?>" 
                                    data-end-time="<?=$appt_end_time;?>" 
                                    data-session="<?=$i;?>" 
                                    data-token="<?=$token;?>"
                                    data-selected="<?=$time_display;?>"
                                >
                                    <?=$time_display;?>
                                </div>

                            <?php } 
                        }
                        $token++;
                    } ?>
                </div>                        
            </div>
            <div class="d-grid py-2">
                <div class="btn see-more" onclick="openPopup()">See more ></div>
            </div>
        <?php }
        $i++;
    }
} else { ?>
    <div class="w-100 mt-3 text-center">
        <p class="no-token"><?=$language['lg_no_tokens_found'] ?? 'No slots available';?></p>
    </div>
<?php } ?>
</div>