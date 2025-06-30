<?php
$cookie_name="";
if (!empty($appoinments_list)) 
{
    foreach ($appoinments_list as $rows)
    {
        $cookie_name = 'notif' . $rows['id'];

        setcookie($cookie_name, 0, time() + (86400 * 30), "/");

        if ($rows['payment_method'] == 'Pay on Arrive') {
            $hourly_rate = 'Pay on Arrive';
        } else {
            $hourly_rate = !empty($rows['per_hour_charge']) ? convert_to_user_currency($rows['per_hour_charge']) : 'Free';
        }

        $current_timezone = $rows['time_zone'];
        $old_timezone = session('time_zone');
        $invite_date  = converToTz($rows['appointment_date'] . ' ' . $rows['appointment_time'], $old_timezone, $current_timezone);
        $invite_to  = converToTz($rows['appointment_date'] . ' ' . $rows['appointment_end_time'], $old_timezone, $current_timezone);
        $invite_to_end_time =  date('Y-m-d H:i:s', strtotime($invite_to));
        $invite_date = date('Y-m-d H:i:s', strtotime($invite_date));



        $appointment_date = date('d M Y', strtotime(converToTz($rows['appointment_date'], $old_timezone, $current_timezone)));
        $appointment_time = date('h:i A', strtotime(converToTz($rows['from_date_time'], $old_timezone, $current_timezone)));
        $appointment_end_time = date('h:i A', strtotime(converToTz($rows['to_date_time'], $old_timezone, $current_timezone)));
        $created_date = date('d M Y', strtotime(converToTz($rows['created_date'], $old_timezone, $current_timezone)));
        $hourly_rate = $hourly_rate;
        $type = $rows['type'];

        // Declare and define two dates 


        $start_date = new DateTime(date('Y-m-d H:i:s'));
        $start_diff = $start_date->diff(new DateTime($invite_date));

        $start_days = str_replace('+', '', $start_diff->format('%R%d'));
        $start_hours = str_replace('+', '', $start_diff->format('%R%h'));
        $start_minutes = str_replace('+', '', $start_diff->format('%R%i'));
        $start_seconds = str_replace('+', '', $start_diff->format('%R%s'));


        $end_date = new DateTime(date('Y-m-d H:i:s'));
        $end_diff = $end_date->diff(new DateTime($invite_to_end_time));

        $end_days = str_replace('+', '', $end_diff->format('%R%d'));
        $end_hours = str_replace('+', '', $end_diff->format('%R%h'));
        $end_minutes = str_replace('+', '', $end_diff->format('%R%i'));
        $end_seconds = str_replace('+', '', $end_diff->format('%R%s'));
        $sub = get_user_subscription_details(session('user_id'));


        if ($app_type == 'doctor' || $app_type == 'hospital')
        {
            $preview_url = base_url() . 'my_patients/mypatient-preview/' . base64_encode($rows['userid']);
            $messageId = $rows['appointment_from'];
        }

        if ($app_type == 'patient')
        {
            $preview_url = base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($rows['username']));
            $messageId = $rows['appointment_to'];
        }

        if ($end_days < 0 && $end_hours < 0 || $end_minutes < 0 || $end_seconds < 0)
        {
            expired_appoinments($rows['id']);
            remove_calls($rows['id']);
            if ($rows['approved'] == 0) {
                continue;
            }
        }
        $doc_id = "";
        if ($rows['hospital_id'] != "")
        {
            $doc_id = $rows['appointment_to'];
        }
        
        $redirect_page_user=session('module');
        if (session('role') == '1') {
            $redirect_page_user="veterinary";
        }

        $messageUrl = base_url().$redirect_page_user. '/message?id=' . $messageId;
?>

<div class="appointment-list">
    <div class="profile-info-widget">
        <div class="profile-det-info">
            <h3><a  href="<?php //echo $preview_url; ?>"><?php echo libsodiumDecrypt($rows['first_name']) . ' ' . libsodiumDecrypt($rows['last_name']); ?></a><?php if ($rows['change_date'] == 2) { ?>
                    <span class="highlighter" style="background: green;color: rgb(255, 255, 255);display: inline-block;margin: 2px 8px 0px 5px;padding: 3px 9px;font-size: 12px;line-height: 11px;vertical-align: middle;text-transform: uppercase;border-radius: 5px;border: 1px solid rgb(159, 170, 177);"><?php echo 'Appointment Date Changed'; ?></span><?php } ?>
            </h3>
            <div class="patient-details">
                <h5><i class="far fa-clock"></i> <?php echo $appointment_date . ', ' . $appointment_time . ' - ' . $appointment_end_time; ?> </h5>
                <h5><!--<i class="fas fa-map-marker-alt"></i>--> <?php //echo $rows['cityname'] . ', ' . $rows['countryname']; ?></h5>
                <?php if ($rows['type'] == 'clinic') { ?><h5><!--<i class="fas fa-map-marker-alt"></i>--> <a target="_blank" href="<?php //echo base_url() . "maps/" . base64_encode($rows['userid']); ?>"><?php //echo $language['lg_get_directions']??""; ?></a></h5><?php } ?>
                <h5><i class="fas fa-envelope"></i> <?php echo libsodiumDecrypt($rows['email']); ?></h5>
                <!--<h5 class="mb-0"><i class="fas fa-phone"></i> <?php //echo libsodiumDecrypt($rows['mobileno']); ?></h5>-->
            </div>
        </div>
    </div>


            <div class="appointment-action">
                <?php
                //  if ($rows['change_date'] == 0 && session('role') == 2) { 
                    ?>
                    <!-- <a href="#" class="btn btn-sm bg-info-light" onclick="show_appoinments_datechange('<?php echo $rows['id']; ?>')" ><i class="far fa-eye"></i><?php echo $language['lg_date_change_req']??""; ?> </a> -->
                <?php 
                    // } 
                ?>
                <?php 
                
                $hourly_rate = ($type == 'Clinic') ? $hourly_rate : $hourly_rate; 
                $lg_accept=$language['lg_accept']??"Accept";
                $lg_cancel=$language['lg_cancel']??"Cancel";
                $lg_cancelled=$language['lg_cancelled']??"Cancelled";
                ?>

                <?php 
                if (session('role') == 6)
                { 
                ?>
                    <a class="btn btn-sm bg-info-light" onclick="assign_doctor('<?php echo $rows['id']; ?>','<?php echo $rows['appointment_date']; ?>','<?php echo $appointment_time; ?>','<?php echo $appointment_end_time; ?>')"> <?php echo $language['lg_assign_doctor']??""; ?> </a>
                <?php } ?>

                <a class="btn btn-sm bg-info-light" onclick="show_appoinments_modal('<?php echo $appointment_date; ?>','<?php echo $appointment_time . ' - ' . $appointment_end_time; ?>','<?php echo $hourly_rate; ?>','<?php echo $type; ?>','<?php echo $rows['id']; ?>','<?= $doc_id ?>')">
                    <i class="far fa-eye"></i>
                    <?php echo $language['lg_view1']??""; ?> 
                </a>


                <?php
                if ($app_type == 'doctor'  || $app_type == 'hospital') {
                    if ($rows['approved'] == 0) {
                        echo '<a href="javascript:void(0);" onclick="conversation_status(\'' . $rows['id'] . '\',\'1\')" class="btn btn-sm bg-success-light"><i class="fas fa-check"></i> ' . $lg_accept . '</a>';
                    }
                }
                if ($app_type == 'patient') {
                    if ($rows['approved'] == 0) {
                        echo '<a href="javascript:void(0);" class="btn btn-sm bg-danger-light" style="cursor: no-drop;"><i class="fas fa-check"></i> ' . $lg_cancelled . '</a>';
                    }
                }

                if ($rows['approved'] == 0 && $rows['appointment_status'] == 0 && $rows['call_end_status'] == 0) {
                    if ($end_days < 0 && $end_hours < 0 || $end_minutes < 0 || $end_seconds < 0) {
                        expired_appoinments($rows['id']);
                        remove_calls($rows['id']);
                    }
                }

                if ($end_minutes == 0 && $end_seconds == 59 && $_COOKIE[$cookie_name] == 0) {
                    // if($_COOKIE[$cookie_name] == 0){ 
                ?>

                    <script>
                        toastr.success('You Have New appoinment with<?php echo libsodiumDecrypt($rows['first_name']) . ' ' . libsodiumDecrypt($rows['last_name']); ?>');
                        setTimeout(function() {
                            set_cookie()
                        }, 3000);
                    </script>

                <?php
                }

                if ($rows['approved'] == 0 && $rows['appointment_status'] == 0 && $rows['call_end_status'] == 0) {
                    if ($end_days < 0 && $end_hours < 0 || $end_minutes < 0 || $end_seconds < 0) {
                        appoinment_cancelled($rows['id']);
                        remove_calls($rows['id']);
                    }
                }

                if ($rows['approved'] == 1 && $rows['appointment_status'] == 0) {
                    //if($start_days < 1 && ( $start_hours > 0 || $start_minutes > 0 || $start_seconds > 0)){ 
                    // if($start_days >= 0 && ( $start_hours > 0 || $start_minutes > 0 || $start_seconds > 0)){ 
                    if ($end_days > 0 && $end_hours > 0 || $end_minutes > 0 || $end_seconds > 0) {
                        if ($app_type == 'doctor'  || $app_type == 'hospital') {
                            echo '<a href="javascript:void(0);" onclick="conversation_status(\'' . $rows['id'] . '\',\'0\')"  class="btn btn-sm bg-danger-light"><i class="fas fa-times"></i>' . $lg_cancel . '</a>';
                        }
                    }
                    // if($app_type=='doctor'  || $app_type=='hospital'){

                    // echo'<a href="javascript:void(0);" onclick="conversation_status(\''.$rows['id'].'\',\'1\')"  class="btn btn-sm bg-primary-light"><i class="fas fa-check"></i> '.$language['lg_accept']??"".'</a>';
                    //  }


                    // echo $rows['id']."<--->";
                    echo '<div class="conv-list conversation_right conversation_start">';

                    $lg_remaining_time_=$language['lg_remaining_time_']??"";
                        $lg_conversation_wi=$language['lg_conversation_wi']??"";
                        
                    if ($start_days > 0) { // More than Today 

                        if ($start_days == 1) {
                            $day = $language['lg_day1']??"";
                        } else {
                            $day = $language['lg_days']??"";
                        }
                        
                        // if($rows['payment_method']=='Pay on Arrive') {
                        if ($rows['type'] == 'Clinic' || $rows['type'] == 'clinic') {
                            echo '<ul>
                             <li><a href="' . $messageUrl . '" class="conv_messages"><i class="fas fa-comments"></i></li>
                             </ul> 
                             <div class="remainingtime">' . $lg_remaining_time_ . ' - ' . $start_days . $day . '</div>';
                        } else {

                            echo '<ul>';
                            echo '<li><a class="btn bg-danger border-0 rounded-circle"><i class="fas fa-phone"></i></a></li>';
                            //echo  '<li><a class="btn bg-danger border-0 rounded-circle"><i class="fas fa-video"></i></a></li>';
                            //echo  '<li><a href="' . $messageUrl . '" class="conv_messages"><i class="fas fa-comments"></i></li>';
                            '</ul> 
                             <div class="remainingtime">' . $lg_remaining_time_ . ' - ' . $start_days . $day . '</div>';
                        }
                    } else if ($start_days < 1 && ($start_hours > 0 || $start_minutes > 0 || $start_seconds > 0)) {

                        // if($rows['payment_method']=='Pay on Arrive') {
                        if ($rows['type'] == 'Clinic' || $rows['type'] == 'clinic') {
                            echo '<ul>';
                             echo '<li><a href="' . $messageUrl . '" class="conv_messages"><i class="fas fa-comments"></i></li>';
                            
                            //this is test code only
                            //echo '<li><a target="_blank" onclick="outgoing_call(\'' . md5($rows['id']) . '\')" href="javascript:void(0);" class="conv_videocall startVideo btn bg-success border-0 rounded-circle"><i class="fas fa-phone"></i></a></li>';
                            //echo '<li><a target="_blank" onclick="outgoing_video_call(\'' . md5($rows['id']) . '\')" href="javascript:void(0);" class="conv_videocall startVideo border-0 btn bg-success rounded-circle"><i class="fas fa-video"></i></a></li>';
                            //end
                            
                             echo '</ul> 
                             <div class="remainingtime">' . $lg_remaining_time_ . ' - ' . sprintf("%02d", $start_hours) . ":" . sprintf("%02d", $start_minutes) . ":" . sprintf("%02d", $start_seconds) . '</div>';
                        } else {

                            if (session('role') != 6) {
                                echo '<ul>';

                                echo '<li><a class="btn bg-danger border-0  rounded-circle"><i class="fas fa-phone"></i></a></li>';
                                //echo '<li><a class="btn bg-danger border-0 rounded-circle"><i class="fas fa-video"></i></a></li>';
                                //echo '<li><a href="' . $messageUrl . '" class="conv_messages"><i class="fas fa-comments"></i></li>';
                                echo  '</ul>';
                            }
                            echo  '<div class="remainingtime">' . $lg_remaining_time_ . ' - ' . sprintf("%02d", $start_hours) . ":" . sprintf("%02d", $start_minutes) . ":" . sprintf("%02d", $start_seconds) . '</div>';
                        }
                    }
                    else {

                        if ($end_days < 0 && $end_hours < 0 || $end_minutes < 0 || $end_seconds < 0) {

                            expired_appoinments($rows['id']);
                            remove_calls($rows['id']);
                        } else {


                            // if($rows['payment_method']=='Pay on Arrive') {
                                if ($rows['type'] == 'Clinic' || $rows['type'] == 'clinic') {

                                    echo '<ul>';
                                        echo '<li><a href="' . $messageUrl . '" class="conv_messages"><i class="fas fa-comments"></i></li>';
                                    echo '</ul>';
                                    echo '<div class="remainingtime">' . $lg_conversation_wi . ' - ' . sprintf("%02d", $end_hours) . ":" . sprintf("%02d", $end_minutes) . ":" . sprintf("%02d", $end_seconds) . '</div>';
                                } else {
    
    
                                    echo '<ul>';


                                    if($rows['appointment_to'] == 22){
                                        echo '<li><a target="_blank" href="/patient/appointment-medicalvet/'.$rows['id'].'" class="conv_videocall startVideo btn bg-success border-0 rounded-circle"><i class="fas fa-phone"></i></a></li>';
                                    }else{
                                        //echo '<li><a target="_blank" onclick="outgoing_call(\'' . md5($rows['id']) . '\')" href="javascript:void(0);" class="conv_videocall startVideo btn bg-success border-0 rounded-circle"><i class="fas fa-phone"></i></a></li>';
                                    }
                                    //echo '<li><a target="_blank" onclick="outgoing_video_call(\'' . md5($rows['id']) . '\')" href="javascript:void(0);" class="conv_videocall startVideo border-0 btn bg-success rounded-circle"><i class="fas fa-video"></i></a></li>';
    
    
                                    //echo '<li><a href="' . $messageUrl . '" class="conv_messages"><i class="fas fa-comments"></i></li>';
                                    echo ' </ul> 
                             <div class="remainingtime">' . $lg_conversation_wi . ' - ' . sprintf("%02d", $end_hours) . ":" . sprintf("%02d", $end_minutes) . ":" . sprintf("%02d", $end_seconds) . '</div>';
                                }
                        }
                    }
                    echo '</div>';
                }
                ?>

            </div>
        </div>



<?php }
} else {
    echo '<div class="appointment-list">
                        <div class="profile-info-widget">
                        <p>' . $language['lg_no_appoinments_']??"" . '</p>
                        </div>
                        </div>';
}
?>
<script>
    function set_cookie() {

        document.cookie = <?= $cookie_name ?> + "=1";

    }

    function show_appoinments_datechange(apt_id) {
        $('.appoinments_id').val(apt_id);
        $('#appoinments_datechange').modal('show');
    }
</script>