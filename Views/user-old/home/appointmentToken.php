<?php
function Suffix($num)
{
    if (!in_array(($num % 100), array(11, 12, 13))) {
        switch ($num % 10) {
                // Handle 1st, 2nd, 3rd
            case 1:
                return 'st';
            case 2:
                return 'nd';
            case 3:
                return 'rd';
        }
    }
    return 'th';
}
/** @var array $language */
?>
<h3 class="card-title text-center text-primary mb-4"><?php
                                                        /** @var string $schedule_date */
                                                        echo date('l - d M Y', strtotime(str_replace('/', '-', $schedule_date))); ?>
</h3>
<div class="row">
    <?php if (!empty($schedule)) {
        $i = 1;
        $token = 1;
        foreach ($schedule as $rows) {
            $time_zone = $rows['time_zone'];
            $current_timezone = session('time_zone');
            $current_time = strtotime(date('Y-m-d H:i:s'));
            $converted_end_time = converToTz($schedule_date . ' ' . $rows['end_time'], $current_timezone, $time_zone);
            $endtime = strtotime($converted_end_time);

    ?>
            <div class="col-lg-12">
                <h3 class="h3 text-center book-btn2 mt-3 px-5 py-1 mx-3 rounded"><?php echo $i; ?><sup><?php echo Suffix($i); ?></sup> <?php
                                                                                                                                        /** @var array $language */
                                                                                                                                        echo $language['lg_session'] ?? ""; ?>
                    <?php if ($current_time > $endtime) { ?>
                        <h4 class="h4 mb-2"><?php echo $language['lg_no_tokens_found']; ?> </h4>
                    <?php } else { ?>
                </h3>
                <div class="text-center mt-3">
                    <h4 class="h4 mb-2"><?php echo $language['lg_start_time'] ?? ""; ?> </h4>
                    <span class="h4 btn btn-outline-primary"><b> <?php echo date('h:i A', strtotime(converToTz($rows['start_time'], $current_timezone, $time_zone))); ?></b></span>
                </div>
                <div class="token-slot mt-2 border">
                    <?php
                        $start = strtotime(converToTz($rows['start_time'], $current_timezone, $time_zone));
                        $end = strtotime(converToTz($rows['end_time'], $current_timezone, $time_zone));
                        $datas = array();
                        if ($rows['slot'] >= 5) {
                            for ($j = $start; $j <= $end; $j = $j + $rows['slot'] * 60) {
                                $datas[] = date('H:i:s', $j);
                            }
                        } else {
                            for ($j = $start; $j <= $end; $j = $j + 60 * 60) {
                                $datas[] = date('H:i:s', $j);
                            }
                        }

                        for ($k = 0; $k <  $rows['token']; $k++) {

                            $l = $k + 1;

                            $start_time =  converToTz($schedule_date . ' ' . $datas[$k],  $current_timezone, $time_zone);
                            // print_r($start_time);
                            // exit;
                            /*
                            Author: Sathishkumar
                            Status: OnHold
                            Descriptiop: This code is written to avoid creating expired time slots in different time zones.
                            */
                            /*$doctor_current_time = converToTz(date('Y-m-d H:i:s'), $time_zone, $current_timezone); // convert user time to doctor timezone time
                            if ($doctor_current_time < $schedule_date . ' ' . $datas[$k]) {*/
                            /*Preventing time slot generation code end*/
                            if (date('Y-m-d H:i:s') < $schedule_date . ' ' . $datas[$k]) {
                                // $token=$k+1;

                                $booked_session = get_booked_session($i, $token, $start_time, $rows['user_id']);
                                if ($booked_session >= 1) {
                                    // print_r($datas[$k]);
                                    // exit;
                                    echo '<div class="form-check-inline visits mr-0">
                                        <label class="visit-btns" style="background: #f6f5f5 !important;">
                                          <input disabled="" type="radio" class="form-check-input">

                                    	<span class="visit-rsn" data-toggle="tooltip" title="' . date('h:i A', strtotime($datas[$k])) . '">' . date('h:i A', strtotime($datas[$k])) . ' (' . $rows['type'] . ')' . '</span>

                                        </label>
                                      </div>';
                                } else {
                    ?>

                                <div class="form-check-inline visits mr-0">
                                    <label class="visit-btns" style="background: #f6f5f5;">
                                        <?php
                                        $appt_start_time = date('H:i:s', strtotime(converToTz($datas[$k], $time_zone, $current_timezone)));
                                        $appt_end_time = date('H:i:s', strtotime(converToTz($datas[$l], $time_zone, $current_timezone)));
                                        ?>
                                        <input type="radio" class="form-check-input" data-schedule-type="<?= $rows['type'] ?>" data-date="<?php echo date('Y-m-d', strtotime(str_replace('/', '-', $schedule_date))); ?>" data-timezone="<?php echo $rows['time_zone'] ?>" data-start-time="<?php echo $appt_start_time; ?>" data-end-time="<?php echo $appt_end_time; ?>" data-session="<?php echo $i; ?>" name="token" value="<?php echo $token; ?>">

                                        <!-- 
                                        Author: Sathishkumar
                                        Status: OnHold
                                        Descriptiop: This code is written to display the time interval based on the user's current time zone.
                                         -->
                                        <!-- <span class="visit-rsn" data-toggle="tooltip" title="<?php echo date('h:i A', strtotime($start_time)); ?>"><?php echo $token; ?></span> -->
                                        <!-- Show time interval code end -->
                                        <span class="visit-rsn" data-toggle="tooltip" title="<?php echo date('h:i A', strtotime($datas[$k])); ?>"><?php echo date('h:i A', strtotime($datas[$k])) . ' (' . $rows['type'] . ')'; ?></span>

                                    </label>
                                </div>

                    <?php }
                            }
                            $token++;
                        } ?>



                    <hr>
                </div>
            <?php  } ?>
            </div>

    <?php $i++;
        }
    } else {
        echo '<div class="col-md-12">
	                        <div class="text-center mt-4">
							 <h4 class="h4 mb-2">' . $language['lg_no_tokens_found'] ?? "" . ' </h4>
						    </div>
						    </div>';
    } ?>
</div>