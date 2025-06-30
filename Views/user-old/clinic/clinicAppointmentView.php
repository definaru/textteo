<?php if(!empty($schedule) && $schedule){
$i = 0;
$req_start_date = strtotime($start_date);
$req_end_date = strtotime($end_date);
foreach ($schedule as $rows) {
    $time_zone = $rows['time_zone'];                         
    $current_timezone = session('time_zone');
    $start=strtotime($rows['start_time']);
    $end=strtotime($rows['end_time']);
    if($start <= $req_start_date && $end <= $req_end_date) {
        $i = 1;
    }
}
if($i == 1) {
    echo 'data is available';
}
} else { echo '0'; } ?>