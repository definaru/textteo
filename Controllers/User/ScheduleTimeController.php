<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\UserModel;

class ScheduleTimeController extends BaseController
{
    public  mixed $data;
    public mixed $timezone;
    public mixed $language;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    /**
     * @var \App\Models\UserModel
     */
    public $userModel;

    public function __construct()
    {
        $this->data['theme'] = 'user';
        $this->data['module'] = 'schedule';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->data['url_segment1'] = service('uri')->getSegment(1);
        $this->data['url_segment2'] = service('uri')->getSegment(2);
        $this->timezone = session('time_zone');
        if (!empty($this->timezone)) {
            date_default_timezone_set($this->timezone);
        }
        $this->data['uri'] = service('uri');

        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        $this->commonModel = new CommonModel();
        $this->userModel = new UserModel();
    }
    /**
     * Schedule Timing Page.
     * 
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'scheduleTime';
        $this->data['profile'] = $this->userModel->getUserDetails(session('user_id'));
        $where = array('user_id' => session('user_id'));
        $this->data['slots'] = $this->commonModel->getTblRowOfData('schedule_timings', $where, '*');
        return view('user/schedule/scheduleTiming', $this->data);
    }

    /**
     * Show Schedule List.
     * 
     * 
     * @return mixed
     */
    public function scheduleList()
    {
        $id = session('user_id');
        $day_id = $this->request->getPost('day_id');
        $day_name = $this->request->getPost('day_name');
        $append_html = $this->request->getPost('append_html');
        $where = array('user_id' => session('user_id'), 'day_id' => $day_id);
        $result = $this->commonModel->getTblResultOfData('schedule_timings', $where, '*');
        $data['available_time'] = $result;
        $data['day_id'] = $day_id;
        $data['day_name'] = $day_name;
        $data['append_html'] = $append_html;
        $data['language'] = $this->language;
        return view('user/schedule/scheduleTimingView', $data);
    }
    /**
     * Add Schedule Time.
     * 
     * 
     * @return mixed
     */
    public function getSlots()
    {
        $id = session('user_id');
        $data['day_id'] = $this->request->getPost('day_id');
        $data['append_html'] = $this->request->getPost('append_html');
        $data['day_name'] = $this->request->getPost('day_name');
        //$data['slot']=$this->request->getPost('slot');       
        $where = array('user_id' => session('user_id'),'day_id' => $data['day_id']);
        $slot = $this->commonModel->getTblRowOfData('schedule_timings', $where, '*');
        $data['slot'] = !empty($slot) ? $slot['slot'] : "";
        $already_day_id = $this->commonModel->getTblResultOfData('schedule_timings', ['user_id' => $id], 'day_id');
        $schedule_details = $this->commonModel->getTblResultOfData('schedule_timings', ['user_id' => $id,'day_id' => $data['day_id']], 'id');
        $data['slot_count'] = count($schedule_details);
        $data['already_day_id'] = array();
        if ($already_day_id) {
            $data['already_day_id'] = $this->multi_to_single($already_day_id);
        }
        $data['language'] = $this->language;
        return view('user/schedule/scheduleTimingAdd', $data,);
    }
    /**
     * Multi to Single.
     * 
     * @param mixed $array
     * @return mixed
     */
    private function multi_to_single($array)
    {
        $days_id = array();
        foreach ($array as $value) {
            $days_id[] = $value['day_id'];
        }
        return $days_id;
    }
    /**
     * Get Days Slots.
     * 
     * 
     * @return mixed
     */
    public function getDaySlots()
    {
        $response = array();
        $id = session('user_id');
        $day_id = $this->request->getPost('day_id');
        $result = $this->commonModel->getTblResultOfData('schedule_timings', ['user_id' => $id, 'day_id' => $day_id], '*');
        if (!empty($result)) {
            $data['edit'] = $result;
            $data['language'] = $this->language;
            $data['slot'] = $result[0]['slot'];
            $response['details'] = view('user/schedule/scheduleTimingEdit', $data);
            $response['count'] = count($result);
        }
        echo json_encode($response);
    }
    /**
     * Get Available Time Slots.
     * 
     * 
     * @return mixed
     */
    public function getAvailableTimeSlots_bkp()
    {
        if (!empty($_POST['slot'])) {
            $datas = [];
            $slot = $_POST['slot'];
            $start = strtotime('00:00');
            $end = strtotime('24:00');
            $user_id = session('user_id');
            if ($slot >= 5) {
                for ($i = $start; $i <= $end; $i = $i + $slot * 60) {
                    $res['label'] = date('g:i A', $i);
                    $res['value'] = date('H:i:s', $i);

                    /* Disabling already added timeslots */

                    $res['added'] = false;
                    $res['start_time'] = 0;
                    $res['end_time'] = 0;

                    if (!empty($_POST['start_time'])) {
                        if ($_POST['start_time'] == $res['value'] || strtotime($_POST['start_time']) > strtotime($res['value'])) {
                            $res['added'] = true;
                            $res['start_time'] = 0;
                            $res['end_time'] = 0;
                        }
                    }

                    if (!empty($_POST['end_time'])) {
                        $end_times = strtotime($_POST['end_time']);
                        $end_time = ($end_times - (($slot * 60)));
                        $end_time = date('H:i:s', $end_time);

                        if ($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])) {
                            $res['added'] = true;
                            $res['start_time'] = 0;
                            $res['end_time'] = 0;
                        }
                    }
                    $datas[] = $res;
                }
            } else {

                for ($i = $start; $i <= $end; $i = $i + 60 * 60) {
                    $res['label'] = date('g:i A', $i);
                    $res['value'] = date('H:i:s', $i);


                    /* Disabling already added timeslots */

                    $res['added'] = false;
                    $res['start_time'] = 0;
                    $res['end_time'] = 0;
                    @$day_id = $_POST['day_id'];


                    if (!empty($_POST['start_time'])) {
                        if ($_POST['start_time'] == $res['value'] || strtotime($_POST['start_time']) > strtotime($res['value'])) {
                            $res['added'] = true;
                            $res['start_time'] = 0;
                            $res['end_time'] = 0;
                        }
                    }

                    if (!empty($_POST['end_time'])) {
                        $end_times = strtotime($_POST['end_time']);
                        $end_time = ($end_times - ((60 * 60)));
                        $end_time = date('H:i:s', $end_time);

                        if ($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])) {
                            $res['added'] = true;
                            $res['start_time'] = 0;
                            $res['end_time'] = 0;
                        }
                    }

                    $datas[] = $res;
                }
            }

            echo json_encode($datas);
        }
    }
    /**
     * Schedule Add.
     * 
     * 
     * @return mixed
     */
    public function scheduleAdd_bkp()
    {
        $inputdata = array();
        $response = array();
        $id = session('user_id');
        $inputdata['slot'] = $this->request->getPost('slot');
        $day_id = $this->request->getPost('day_id');
        $inputdata['user_id'] = $id;
        $inputdata['time_zone'] = session('time_zone');
        $start_time = $this->request->getPost('start_time');
        $end_time = $this->request->getPost('end_time');
        $token = $this->request->getPost('token');
        $sessions = $this->request->getPost('sessions');

        $day_name = '';
        for ($j = 0; $j < count($day_id); $j++) {
            switch ($day_id[$j]) {
                case '1':
                    $day_name = 'Sunday';
                    break;
                case '2':
                    $day_name = 'Monday';
                    break;
                case '3':
                    $day_name = 'Tuesday';
                    break;
                case '4':
                    $day_name = 'Wednesday';
                    break;
                case '5':
                    $day_name = 'Thursday';
                    break;
                case '6':
                    $day_name = 'Friday';
                    break;
                case '7':
                    $day_name = 'Saturday';
                    break;
                default:
                    $day_name = '';
                    break;
            }

            $inputdata['day_id'] = $day_id[$j];
            $inputdata['day_name'] = $day_name;

            for ($i = 1; $i <= count($sessions); $i++) {
                $inputdata['start_time'] = date('H:i:s', strtotime($start_time[$i]));
                $inputdata['end_time'] = date('H:i:s', strtotime($end_time[$i]));
                $inputdata['sessions'] = $i;
                $inputdata['token'] = $token[$i];

                //checking token avaliable
                $detailExist = $this->commonModel->checkTblDataExist('schedule_timings', array('user_id' => $inputdata['user_id'], 'start_time' => $inputdata['start_time'], 'end_time' => $inputdata['end_time'], 'sessions' => $inputdata['sessions'], 'token' => $inputdata['token'], 'day_id' => $inputdata['day_id']), 'id');
                if (!$detailExist) {
                    $result = $this->commonModel->insertData('schedule_timings', $inputdata);
                }
            }
        }

        if ($result == true) {
            $response['msg'] = $this->language['lg_schedule_timing1'];
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_schedule_timing2'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }
    /**
     * Schedule Update.
     * 
     * 
     * @return mixed
     */
    public function scheduleUpdate()
    {
        $inputdata = array();
        $response = array();
        $id = session('user_id');
        $day_id = $this->request->getPost('day_id');

        $this->commonModel->deleteData('schedule_timings', ['user_id' => $id, 'day_id' => $day_id]);

        $inputdata['slot'] = $this->request->getPost('slots');
        $inputdata['user_id'] = $id;
        $inputdata['time_zone'] = session('time_zone');
        $start_time = $this->request->getPost('start_time');
        $end_time = $this->request->getPost('end_time');
        $token = $this->request->getPost('token');
        $sessions = $this->request->getPost('sessions');
        $day_name = '';

        switch ($day_id) {
            case '1':
                $day_name = 'Sunday';
                break;
            case '2':
                $day_name = 'Monday';
                break;
            case '3':
                $day_name = 'Tuesday';
                break;
            case '4':
                $day_name = 'Wednesday';
                break;
            case '5':
                $day_name = 'Thursday';
                break;
            case '6':
                $day_name = 'Friday';
                break;
            case '7':
                $day_name = 'Saturday';
                break;
            default:
                $day_name = '';
                break;
        }

        $inputdata['day_id'] = $day_id;
        $inputdata['day_name'] = $day_name;

        if (!empty($sessions)) {
            for ($i = 1; $i <= count($sessions); $i++) {
                $inputdata['start_time'] = date('H:i:s', strtotime($start_time[$i]));
                $inputdata['end_time'] = date('H:i:s', strtotime($end_time[$i]));
                $inputdata['sessions'] = $i;
                $inputdata['token'] = $token[$i];

                $detailExist = $this->commonModel->checkTblDataExist('schedule_timings', array('user_id' => $inputdata['user_id'], 'start_time' => $inputdata['start_time'], 'end_time' => $inputdata['end_time'], 'sessions' => $inputdata['sessions'], 'token' => $inputdata['token'], 'day_id' => $inputdata['day_id']), 'id');
                if (!$detailExist) {
                    $result = $this->commonModel->insertData('schedule_timings', $inputdata);
                }
            }
        }

        if ($result == true) {
            $response['msg'] = $this->language['lg_schedule_timing3'];
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_schedule_timing2'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }

    public function delete_schedule_time($value='')
    {
    $sts = 0;
    $id = $this->request->getPost('delete_value');


    $where = array('id'=>$id);
    $data = $this->commonModel->getTblRowOfData('schedule_timings', $where, '*');

    if($this->commonModel->deleteData('schedule_timings', ['user_id' => $data['user_id'],'start_time'=> $data['start_time'], 'day_id' => $data['day_id']])){
        $sts = 1;
    }
    echo $sts;
    }

    public function scheduleAdd()
    {
        $inputdata = array();
        $response = array();
        $id = session('user_id');
        $inputdata['slot'] = $this->request->getPost('slot');
        $day_id = $this->request->getPost('day_id');
        $inputdata['user_id'] = $id;
        $inputdata['time_zone'] = session('time_zone');
        $start_time = $this->request->getPost('start_time');
        $end_time = $this->request->getPost('end_time');
        $token = $this->request->getPost('token');
        $sessions = $this->request->getPost('sessions');
        $type = $this->request->getPost('type');

        $day_name = '';
        switch ($day_id) {
            case '1':
                $day_name = 'Sunday';
                break;
            case '2':
                $day_name = 'Monday';
                break;
            case '3':
                $day_name = 'Tuesday';
                break;
            case '4':
                $day_name = 'Wednesday';
                break;
            case '5':
                $day_name = 'Thursday';
                break;
            case '6':
                $day_name = 'Friday';
                break;
            case '7':
                $day_name = 'Saturday';
                break;
            default:
                $day_name = '';
                break;
        }

        $inputdata['day_id'] = $day_id;
        $inputdata['day_name'] = $day_name;

        $where = array('day_id'=>$day_id,'user_id'=>$id);
        $session_details = $this->commonModel->getTblResultOfData('schedule_timings', $where, '*');

        for ($i = 1; $i <= count($sessions); $i++) {
            $inputdata['start_time'] = date('H:i:s', strtotime($start_time[$i]));
            $inputdata['end_time'] = date('H:i:s', strtotime($end_time[$i]));
            $inputdata['sessions'] = $i + count($session_details);
            $inputdata['token'] = $token[$i];
            $inputdata['type'] = $type[$i];

            //checking token avaliable
            $detailExist = $this->commonModel->checkTblDataExist('schedule_timings', array('user_id' => $inputdata['user_id'], 'start_time' => $inputdata['start_time'], 'end_time' => $inputdata['end_time'], 'sessions' => $inputdata['sessions'], 'token' => $inputdata['token'], 'day_id' => $inputdata['day_id']), 'id');
            if (!$detailExist) {
                $result = $this->commonModel->insertData('schedule_timings', $inputdata);
            }
        }

        if ($result == true) {
            $response['msg'] = $this->language['lg_schedule_timing1'];
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_schedule_timing2'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }

    public function getAvailableTimeSlots()
    {
        if (!empty($_POST['slot'])) {
            $datas = [];
            $slot = $_POST['slot'];
            $start = strtotime('00:00');
            $end = strtotime('24:00');
            $user_id = session('user_id');
            $exist_date_arr = [];
            
            $sched_where = array('day_id'=>$_POST['day_id'],'user_id'=>$user_id);
            $schedule_res = $this->userModel->getScheduleData($sched_where);
            if (!empty($schedule_res['slot'])) {
                if($schedule_res['slot'] != $slot){
                    $wh = array('slot'=>$schedule_res['slot'],'user_id'=>$user_id);
                    $this->commonModel->deleteData('schedule_timings', $wh);
                }
            }
            $sched_res = $this->userModel->getScheduleData($sched_where);
            if(!empty($sched_res)) {
                $start = strtotime(date('H:i',strtotime($sched_res['end_time'])));
            }
            
            if ($slot >= 5) {
                for ($i = $start; $i <= $end; $i = $i + $slot * 60) {
                    $res['label'] = date('g:i A', $i);
                    $res['value'] = date('H:i:s', $i);

                    /* Disabling already added timeslots */

                    $res['added'] = false;
                    $res['start_time'] = 0;
                    $res['end_time'] = 0;

                    $where = array('start_time'=>$res['value'],'day_id'=>$_POST['day_id'],'user_id'=>$user_id);
                    $result = $this->commonModel->getTblRowOfData('schedule_timings', $where, '*');
                    
                    
                    if (!empty($result['start_time'])) {
                        for ($j = strtotime(date('H:i',strtotime($result['start_time']))); $j <= strtotime(date('H:i',strtotime($result['end_time']))); $j = $j + $result['slot'] * 60) {
                            $exist_date_arr[] = date('H:i', $j);
                        }
                    }
                   
                    if(!empty($exist_date_arr)) {
                        foreach($exist_date_arr as $val) {
                            if(strtotime(date('H:i', $i)) ==  strtotime($val)){
                                $res['added'] = true;
                                $res['start_time'] = 0;
                                $res['end_time'] = 0;
                            }
                        }
                    }

                    if (!empty($result['start_time'])) {
                        if($result['start_time'] == $res['value']){
                            $res['added'] = true;
                            $res['start_time'] = $result['start_time'];
                            $res['end_time'] = $result['end_time'];
                        }
                    }

                    $where = array('end_time'=>$res['value'],'day_id'=>$_POST['day_id'],'user_id'=>$user_id);
                    $result = $this->commonModel->getTblRowOfData('schedule_timings', $where, '*');
               
                    if (!empty($result['end_time'])) {
                        if($result['end_time'] == $res['value']){ // already added or not       
                            $res['added'] = true;
                            $res['start_time'] = $result['start_time'];
                            $res['end_time'] = $result['end_time'];
                        }
                    }

                    if(!empty($_POST['start_time']) ){
                        if($_POST['start_time'] == $res['value'] || strtotime($_POST['start_time']) > strtotime($res['value'])){
                          $res['added'] = true;
                          $res['start_time'] = 0;
                          $res['end_time'] = 0;
                        }
                    }
                    if (!empty($_POST['end_time'])) {
                        $end_times = strtotime($_POST['end_time']);
                        $end_time = ($end_times - (($slot * 60)));
                        $end_time = date('H:i:s', $end_time);

                        if ($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])) {
                            $res['added'] = true;
                            $res['start_time'] = 0;
                            $res['end_time'] = 0;
                        }
                    }
                    $datas[] = $res;
                }
            } else {

                for ($i = $start; $i <= $end; $i = $i + 60 * 60) {
                    $res['label'] = date('g:i A', $i);
                    $res['value'] = date('H:i:s', $i);


                    /* Disabling already added timeslots */

                    $res['added'] = false;
                    $res['start_time'] = 0;
                    $res['end_time'] = 0;
                    @$day_id = $_POST['day_id'];

                    $where = array('start_time'=>$res['value'],'day_id'=>$_POST['day_id'],'user_id'=>$user_id);
                    $result = $this->commonModel->getTblRowOfData('schedule_timings', $where, '*');

                    if (!empty($result['slot'])) {
                        if($result['slot'] != $slot){
    
                            $wh = array('slot'=>$result['slot'],'user_id'=>$user_id);
                            $this->commonModel->deleteData('schedule_timings', $wh);
                        }
                    }

                    if (!empty($result['start_time'])) {
                        for ($j = strtotime(date('H:i',strtotime($result['start_time']))); $j <= strtotime(date('H:i',strtotime($result['end_time']))); $j = $j + $result['slot'] * 60) {
                            $exist_date_arr[] = date('H:i', $j);
                        }
                    }

                    if(!empty($exist_date_arr)) {
                        foreach($exist_date_arr as $val) {
                            if(date('H:i', $i) ==  $val){
                                $res['added'] = true;
                                $res['start_time'] = 0;
                                $res['end_time'] = 0;
                            }
                        }
                    }

                    if (!empty($result['start_time'])) {
                        if($result['start_time'] == $res['value']){
                            $res['added'] = true;
                            $res['start_time'] = $result['start_time'];
                            $res['end_time'] = $result['end_time'];
                        }
                    }
                    $where = array('end_time'=>$res['value'],'day_id'=>$_POST['day_id'],'user_id'=>$user_id);
                    $result = $this->commonModel->getTblRowOfData('schedule_timings', $where, '*');
               
                    if (!empty($result['end_time'])) {
                        if($result['end_time'] == $res['value']){ // already added or not       
                            $res['added'] = true;
                            $res['start_time'] = $result['start_time'];
                            $res['end_time'] = $result['end_time'];
                        }
                    }

                    if(!empty($_POST['start_time']) ){
                        if($_POST['start_time'] == $res['value'] || strtotime($_POST['start_time']) > strtotime($res['value'])){
                          $res['added'] = true;
                          $res['start_time'] = 0;
                          $res['end_time'] = 0;
                        }
                    }
                    if (!empty($_POST['end_time'])) {
                        $end_times = strtotime($_POST['end_time']);
                        $end_time = ($end_times - ((60 * 60)));
                        $end_time = date('H:i:s', $end_time);

                        if ($end_time == $res['value'] || strtotime($end_time) > strtotime($res['value'])) {
                            $res['added'] = true;
                            $res['start_time'] = 0;
                            $res['end_time'] = 0;
                        }
                    }

                    $datas[] = $res;
                }
            }

            echo json_encode($datas);
        }
    }
}
