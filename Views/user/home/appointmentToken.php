
   

    <div class="slots-grid bookings-schedule style='width:100%'">
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

        if (!empty($schedule)) {
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
                        $start_time = converToTz($schedule_date . ' ' . $datas[$k],  $current_timezone, $time_zone);

                       if (date('Y-m-d H:i:s') < $schedule_date . ' ' . $datas[$k]) {
                            $booked_session = get_booked_session($i, $token, $start_time, $rows['user_id']);
                            $time_display = date('h:i A', strtotime(converToTz($datas[$k], $current_timezone, $time_zone)));
                            
                            if ($booked_session >= 1) {
                                // Slot booked, disable it visually
                                echo '<div class="slot-booked" title="Booked">' . $time_display . '</div>';
                            } else {
                                // if (!empty($datas[$k + 1])) {
                                //     // if there is a next slot, use next start time
                                //     $end_time_value = date('H:i:s', strtotime(converToTz($datas[$k + 1], $time_zone, $current_timezone)));
                                // } else {
                                //     // if this is the last slot, use schedule's original end_time
                                //     $end_time_value = date('H:i:s', strtotime(converToTz($schedule_date . ' ' . $rows['end_time'], $time_zone, $current_timezone)));
                                // }
                                $appt_start_time = date('H:i:s', strtotime(converToTz($datas[$k], $time_zone, $current_timezone)));
                                $appt_end_time = date('H:i:s', strtotime(converToTz($datas[$l], $time_zone, $current_timezone)));
                                echo '
                                    <div class="slot" data-schedule-type="' . $rows['type'] . '" 
                                    data-date="' . date('Y-m-d', strtotime(str_replace('/', '-', $schedule_date))) . '" 
                                    data-timezone="' . $rows['time_zone'] . '" 
                                    data-start-time="' . $appt_start_time . '" 
                                    data-end-time="' . $appt_end_time . '" 
                                    data-session="' . $i . '" 
                                    data-token="' . $token . '">' . $time_display . '</div>';
                            }
                        }
                        $token++;
                    }
                }
                $i++;
            }
        } else {
            echo '
                    <div class="text-center">
                        <p class="no-token">' . ($language['lg_no_tokens_found'] ?? "No slots available") . '</p>
                    </div>
                  ';
        }
        ?>
    </div>

    <div class="see-more" onclick="openPopup()">See more ></div>
