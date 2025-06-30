<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\HomeModel;
use App\Models\MessageModel;

class MessageController extends BaseController
{
    public mixed $uri;
    public mixed $data;
    public mixed $session;
    public mixed $timezone;
    public mixed $lang;
    public mixed $language;
    public mixed $profile;
    /**
     * @var \App\Models\CommonModel
     */
    public $userModel;
    /**
     * @var \App\Models\MessageModel
     */
    public $messageModel;
    /**
     * @var \App\Models\HomeModel
     */
    public $homeModel;

    public function __construct()
    {
        $this->data['theme'] = 'user';
        $this->data['module'] = 'patient';
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

        $this->messageModel = new MessageModel();
        $this->homeModel = new HomeModel();
    }
    /**
     * Message Page
     * 
     * @return mixed
     */
    public function index()
    {
        $user_id = session('user_id');
        if (session('role') == '1' || session('role') == '6') {
            $this->data['module']    = 'messages';
            $this->data['page'] = 'index';
            $this->data['users'] =  $this->messageModel->getPatients($user_id);
        } else {
            $this->data['module']    = 'messages';
            $this->data['page'] = 'index';
            $this->data['users'] =  $this->messageModel->getDoctors($user_id);
        }
        return view('user/layout/message', $this->data);
    }

    /**
     * Search users.
     * 
     * @return mixed
     */
    public function searchUsers()
    {
        $user_id = session('user_id');
        $keywords = $this->request->getPost('keywords');

        if (session('role') == '1' || session('role') == '6') {
            $users =  $this->messageModel->getPatients($user_id, $keywords);
        } else {
            $users =  $this->messageModel->getDoctors($user_id, $keywords);
        }

        //echo '<pre>';print_r($users);exit;

        $response = array();
        $result = array();

        if (!empty($users)) {
            foreach ($users as $rows) {
                $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
                $data['userid'] = $rows['userid'];
                $data['username'] = libsodiumDecrypt(ucfirst($rows['username']));
                $data['first_name'] = libsodiumDecrypt(ucfirst($rows['first_name']));
                $data['last_name'] = libsodiumDecrypt(ucfirst($rows['last_name']));
                $data['role'] = $rows['role'];
                $data['type'] = $rows['type'];
                $data['unread_count'] = $rows['unread_count'];
                $data['last_msg'] = strval($rows['last_msg']);
                if ($rows['chatdate'] != null) {
                    $data['chatdate'] = time_elapsed_string($rows['chatdate']);
                } else {
                    $data['chatdate'] = "";
                }
                $data['online_status'] = "avatar-offline";

                if ($rows['date_time']) {
                    $current_timezone = $rows['time_zone'];
                    $old_timezone = session('time_zone');

                    $appointment_date = date('Y-m-d H:i:s', strtotime(converToTz($rows['date_time'], $old_timezone, $current_timezone)));

                    $datetime1 = new \DateTime();
                    $datetime2 = new \DateTime($appointment_date);
                    $interval = $datetime1->diff($datetime2);

                    $interval_time = $interval->format('%i');
                    if ($interval_time < 5) {
                        $data['online_status'] = "avatar-online";
                    } elseif (($interval_time > 5 && $interval_time < 10)) {
                        $data['online_status'] = "avatar-away";
                    } elseif ($interval_time > 5) {
                        $data['online_status'] = "avatar-offline";
                    }
                }
                $result[] = $data;
            }
            $response['status'] = '200';
        } else {
            $response['status'] = '500';
        }
        $response['users_list'] = $result;
        echo json_encode($response);
    }
    /**
     * Get Chat List.
     * 
     * @return mixed
     */
    public function getMessages()
    {
        $user_id = session('user_id');
        $selected_user = $_POST['selected_user_id'];
        $latest_chat = $this->messageModel->get_latest_chat($selected_user, $user_id);
        $total_chat = $this->messageModel->get_total_chat_count($selected_user, $user_id);
        // $last_message_id = $this->messageModel->get_last_message_id($selected_user, $user_id);

        $page = 0;
        if ($total_chat > 5) {
            $total_chat = $total_chat - 5;
            $page = $total_chat / 5;
            $page = ceil($page);
            $page--;
        }

        if (count($latest_chat) > 4) {
            $html = '<div class="load-more-btn text-center" total="' . @$page . '">
            <button class="btn btn-sm" data-page="2"><i class="fa fa-refresh"></i> ' . $this->language['lg_load_more'] . '</button>
            </div><div id="ajax_old" class="ajax_old"></div>';
        } else {
            $html = '';
        }

        if (!empty($latest_chat)) {
            foreach ($latest_chat as $key => $currentuser) :

                $class_name = ($currentuser['sender_id'] != $user_id) ? 'received' : 'sent';

                $img = (!empty($currentuser['senderImage'])) ? base_url() . $currentuser['senderImage'] : base_url() . 'assets/img/user.png';

                $time_zone = session('time_zone');
                $from_timezone = $currentuser['time_zone'];
                $date_time = $currentuser['chatdate'];
                $date_time  = converToTz($date_time, $time_zone, $from_timezone);
                $time = date('d-m-Y h:i a', strtotime($date_time));


                if ($currentuser['type'] == 'image') {

                    $html .= '<li class="media ' . $class_name . '">';
                    if ($class_name == 'received') {
                        $html .= '<div class="avatar  avatar avatar-away">
                        <img src="' . $img . '" class="avatar-img rounded-circle">
                        </div>';
                    }
                    $html .= '<div class="media-body">
                        <div class="msg-box">
                        <div>
                        <p><img alt="" src="' . base_url() . $currentuser['file_path'] . '/' . $currentuser['file_name'] . '" class="img-fluid"></p>
                        <p>' . $currentuser['file_name'] . '</p>
                        <a href="' . base_url() . $currentuser['file_path'] . '/' . $currentuser['file_name'] . '" target="_blank" download>' . $this->language['lg_download'] . '</a>
                    <ul class="chat-msg-info">
                        <li>
                        <div class="chat-time">
                            <span>' . $time . '</span>
                        </div>
                        </li>
                    </ul>
                        </div>
                        </div>
                        </div>
                        </li>';
                } else if ($currentuser['type'] == 'others') {

                    $html .= '<li class="media ' . $class_name . '">';
                    if ($class_name == 'received') {
                        $html .= '<div class="avatar  avatar avatar-away">
                        <img src="' . $img . '" class="avatar-img rounded-circle">
                        </div>';
                    }
                    $html .= '<div class="media-body">
                        <div class="msg-box">
                        <div>
                        <p><img alt="" src="' . base_url() . 'assets/img/download.png" class="img-responsive"></p>
                        <p>' . $currentuser['file_name'] . '</p>
                        <a href="' . base_url() . $currentuser['file_path'] . '/' . $currentuser['file_name'] . '" target="_blank" download class="chat-time">' . $this->language['lg_download'] . '</a>
                        <ul class="chat-msg-info">
                        <li>
                            <div class="chat-time">
                            <span>' . $time . '</span>
                            </div>
                        </li>
                        </ul>
                        </div>
                        </div>
                        </div>
                        </li>';
                } else {
                    $html .= '<li class="media ' . $class_name . '">';
                    if ($class_name == 'received') {
                        $html .= '<div class="avatar  avatar avatar-away">
                        <img src="' . $img . '" class="avatar-img rounded-circle">
                        </div>';
                    }
                    $html .= '<div class="media-body">
                        <div class="msg-box">
                        <div>
                        <p>
                        ' . $currentuser['msg'] . '
                        </p>
                    <ul class="chat-msg-info">
                        <li>
                        <div class="chat-time">
                            <span>' . $time . '</span>
                        </div>
                        </li>
                    </ul>
                        </div>
                        </div>
                        </div>
                        </li>';
                }
            endforeach;
        }
        $html .= '<div id="ajax"></div><input type="hidden"  id="hidden_id" value="">';

        if ($total_chat == 0) {
            $html .= '<div class="no_message">' . $this->language['lg_no_record_found'] . '</div>';
        }
        echo $html;
    }
    /**
     * Get Chat Users.
     * 
     * @return mixed
     */
    public function getChatUser()
    {
        $user_id = session('user_id');
        $data = array();
        $data['status'] = 500;
        if (session('role') == '1' || session('role') == '6') {
            $users =  $this->messageModel->getPatients($user_id);
            if (!empty($users)) {
                $data['user_id'] = $users[0]['userid'];
                $data['username'] = libsodiumDecrypt($users[0]['username']);
                $data['name'] = libsodiumDecrypt($users[0]['first_name']) . ' ' . libsodiumDecrypt($users[0]['last_name']);
                $data['status'] = 200;
            }
        } else {
            $users =  $this->messageModel->getDoctors($user_id);
            if (!empty($users)) {
                $data['user_id'] = $users[0]['userid'];
                $data['username'] = libsodiumDecrypt($users[0]['username']);
                $data['name'] = libsodiumDecrypt($users[0]['first_name']) . ' ' . libsodiumDecrypt($users[0]['last_name']);
                $data['status'] = 200;
            }
        }
        echo json_encode($data);
    }
    /**
     * Get Chat Image.
     * 
     * @return mixed
     */
    public function getChatImg()
    {
        $user_id = $this->request->getPost('user_id');

        $result = getTblRowOfData('users', ['id' => $user_id], "profileimage");
        $status_result = getTblRowOfData('user_online_status', ['user_id' => $user_id], "date_time,time_zone");

        $response['online_status'] = "avatar-offline";
        $response['status'] = "Offline";

        if ($status_result['date_time']) {
            $current_timezone = $status_result['time_zone'];
            $old_timezone = session('time_zone');

            $appointment_date = date('Y-m-d H:i:s', strtotime(converToTz($status_result['date_time'], $old_timezone, $current_timezone)));

            $datetime1 = new \DateTime();
            $datetime2 = new \DateTime($appointment_date);
            $interval = $datetime1->diff($datetime2);

            $interval_time = $interval->format('%i');
            if ($interval_time < 5) {
                // echo "<pre>";
                // echo $appointment_date;
                // echo "<pre>";
                // echo $interval_time;
                $response['online_status'] = "avatar-online";
                $response['status'] = $this->language['lg_online'] ?? "";
            } else if ($interval_time > 5 && $interval_time < 10) {
                $response['online_status'] = "avatar-away";
                $response['status'] = $this->language['lg_idle'] ?? "";
            } else {
                $response['online_status'] = "avatar-offline";
                $response['status'] = $this->language['lg_offline'] ?? "";
            }
        }

        $profileimage = base_url() . 'assets/img/user.png';
        if (!empty($result['profileimage']) && file_exists($result['profileimage'])) {
            $profileimage = base_url() . $result['profileimage'];
        }
        $response['profileimage'] = $profileimage;
        echo json_encode($response);
    }
    /**
     * Get Message Count
     * 
     * @return mixed
     */
    public function getMessage()
    {
        $dat['message'] = $this->check_new_message();
        $dat['other_message'] = $this->get_message_counts();
        $dat['status'] = true;
        echo json_encode($dat);
    }

    /**
     * Check New Messages.
     * 
     * @return mixed
     */
    public function check_new_message()
    {
        $user_selected_id = $this->request->getPost('user_selected_id');
        $recieved_id = session('user_id');;
        $where = array('recieved_id' => $recieved_id, 'read_status' => 0, 'sent_id' => $user_selected_id);
        $result = $this->messageModel->getChatHistory($where);

        $messages = array();
        if (!empty($result)) {
            foreach ($result as $r) {
                $data = getTblRowOfData('users', ['id' => $r['sent_id']], "id,profileimage as senderImage");

                $img = (!empty($data['senderImage'])) ? base_url() . $data['senderImage'] : base_url() . 'assets/img/user.png';

                $msg['image'] =  $img;
                $msg['type'] = $r['type'];
                $msg['file_path'] = $r['file_path'];
                $msg['file_name'] = $r['file_name'];
                $msg['read_status'] = $r['read_status'];
                $msg['message'] = $r['msg'];
                $msg['time'] = date('d-m-Y h:i A', strtotime($r['chatdate']));
                $messages[] = $msg;
            }
        }

        $where = array('recieved_id' => $recieved_id, 'read_status' => 0, 'sent_id' => $user_selected_id);
        //Send Data to Common-Helper Function
        updateData('chat', $where, ['read_status' => 1]);

        return  json_encode($messages);
    }

    /**
     * Check New Messages.
     * 
     * @return mixed
     */
    public function get_message_counts()
    {
        $user_selected_id = $this->request->getPost('user_selected_id');
        $where = array();
        if (!empty($_POST['selected_user_id'])) {
            $where += array('c.sent_id !=' => $user_selected_id);
        }
        $recieved_id = session('user_id');
        $where += array('c.recieved_id' => $recieved_id, 'c.read_status' => 0);
        $data = $this->messageModel->getCountMsg($where);
        return json_encode($data);
    }
    /**
     * Get User Id.
     * 
     * @return mixed
     */
    public function get_user_id()
    {
        return getTblRowOfData('users', ['username' => libsodiumEncrypt($_POST['to_username'])], "id")['id'];
    }

    /**
     * Save Chat
     * 
     * @return mixed
     */

    public function insertChat()
    {
        $data['recieved_id'] = $this->get_user_id();
        $data['sent_id'] = session('user_id');
        $data['time_zone'] = session('time_zone');
        // print_r($data['time_zone']);
        // exit;
        $data['chatdate'] = date('Y-m-d H:i:s');
        $data['msg'] = $_POST['input_message'];
        //Send Data to Common-Helper Function
        $chat_id = insertData('chat', $data);
        $users = array($data['recieved_id'], $data['sent_id']);
        for ($i = 0; $i < 2; $i++) {
            $datas = array('chat_id' => $chat_id, 'can_view' => $users[$i]);
            insertData('chat_deleted_details', $datas);
        }

        if (session('user_id')) {
            $user_id = session('user_id');
            $data = array(
                'date_time' => date('Y-m-d H:i:s'),
                'time_zone' => $this->timezone,
                'user_id' => $user_id
            );

            $count = $this->homeModel->countTblResult('user_online_status', array('user_id' => $user_id));

            if ($count > 0) {
                $this->homeModel->updateData('user_online_status', array('user_id' => $user_id), $data);
            } else {
                $this->homeModel->insertData('user_online_status', $data);
            }
        }

        // Notification Code
        $latest_chat = $this->messageModel->getNewChat($this->get_user_id(), session('user_id'));
        if (!empty($latest_chat)) {
            foreach ($latest_chat as $key => $currentuser) {
                $from_timezone = $currentuser['time_zone'];
                $date_time = $currentuser['chatdate'];
                $time_zone = session('time_zone');
                $user_id = session('user_id');
                $date_time  = converToTz($date_time, $time_zone, $from_timezone);
                $msgdata['chat_time'] = date('Y-m-d H:i:s', strtotime($date_time));
                $type = $currentuser['type'];
                $attachment_file = ($currentuser['file_path']) ? ($currentuser['file_path'] . '/' . $currentuser['file_name']) : '';
                $message = $currentuser['msg'];
                $msgdata['type'] = 'Message';
                $msgdata['file_name'] = ($currentuser['file_path']) ? ($currentuser['file_path'] . '/' . $currentuser['file_name']) : '';
                $msgdata['msg_type'] = ($currentuser['sender_id'] != $user_id) ? 'received' : 'sent';
                $msgdata['chat_from'] = $currentuser['sender_id'];
                $msgdata['chat_to'] = $currentuser['receiver_id'];
                $msgdata['from_user_name'] = $currentuser['sender_from_firstusername'];
                $msgdata['to_user_name'] = $currentuser['reciever_first_username'];
                $msgdata['profile_from_image'] = (!empty($currentuser['senderImage'])) ? base_url() . $currentuser['senderImage'] : base_url() . 'assets/img/user.png';
                $msgdata['profile_to_image'] = (!empty($currentuser['receiverImage'])) ? base_url() . $currentuser['receiverImage'] : base_url() . 'assets/img/user.png';
                $msgdata['content'] = ($message) ? $message : '';
            }


            // $notifydata['include_player_ids'] = $currentuser['receiver_device_id'];
            // $device_type = $currentuser['receiver_device_type'];
            // $notifydata['message'] = $message;
            // $notifydata['notifications_title'] = $msgdata['from_user_name'];
            // $notifydata['additional_data'] = $msgdata;

            // H-3-4
            // if($device_type=='Android')
            // {
            // sendFCMNotification($notifydata);
            // }

            // if($device_type=='IOS')
            // {
            // sendiosNotification($notifydata);
            // }

        }
        //echo  $result;
    }


    // public function upload_files()
    // {
    //     try {

    //         $user_id = session('user_id');

    //         $path = "uploads/msg_uploads/" . $user_id;
    //         if (!is_dir($path)) {
    //             mkdir($path, 0777, true);
    //         }

    //         $target_file = $path . basename($_FILES["userfile"]["name"]);
    //         $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

    //         if ($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif") {
    //             $type = 'others';
    //         } else {
    //             $type = 'image';
    //         }


    //         $config['upload_path']   = './' . $path;
    //         $config['allowed_types'] = '*';
    //         $this->load->library('upload', $config);
    //         if ($this->upload->do_upload('userfile')) {
    //             //$file_name = $this->request->getPost('file_name');
    //             $file_name = $this->upload->data('file_name');
    //             $data = array(
    //                 'recieved_id' => $_POST['to_user_id'],
    //                 'sent_id' => session('user_id'),
    //                 'msg' => 'file',
    //                 'file_name' => $file_name,
    //                 'chatdate' => date('Y-m-d H:i:s'),
    //                 'type' => $type,
    //                 'read_status' => 0,
    //                 'time_zone' => session('time_zone'),
    //                 'file_path' => $path
    //             );
    //             $chat_id = insertData('chat', $data);
    //             //$result = $this->db->insert('chat', $data);
    //             // $chat_id = $this->db->insert_id();
    //             $users = array($data['recieved_id'], $data['sent_id']);
    //             for ($i = 0; $i < 2; $i++) {
    //                 $datas = array('chat_id' => $chat_id, 'can_view' => $users[$i]);
    //                 insertData('chat_deleted_details', $datas);
    //             }

    //             echo  json_encode(array('img' => $path . '/' . $file_name, 'type' => $type, 'file_name' => $file_name));
    //         } else {
    //             echo  json_encode(array('error' => $this->upload->display_errors()));
    //         }
    //     } catch (Exception $e) {
    //         echo '<pre>';
    //         print_r($e->getMessages());
    //         die();
    //     }
    // }
    /**
     * Upload Files
     * 
     * @return mixed
     */
    public function uploadFiles()
    {
        // print_r('sdfsdf');
        // exit;
        try {
            $user_id = session()->get('user_id'); // Use session() instead of session()

            $path = "uploads/msg_uploads/" . $user_id;
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $file = $this->request->getFile('userfile'); // Use getFile() to access uploaded files

            if ($file->isValid() && $file->getExtension() !== 'jpg' && $file->getExtension() !== 'png' && $file->getExtension() !== 'jpeg' && $file->getExtension() !== 'gif') {
                $type = 'others';
            } else {
                $type = 'image';
            }

            $file->move($path); // Move the uploaded file to the destination directory

            $file_name = $file->getName(); // Get the file name after moving

            $data = [
                'recieved_id' => $this->request->getPost('to_user_id'),
                'sent_id' => session()->get('user_id'),
                'msg' => 'file',
                'file_name' => $file_name,
                'chatdate' => date('Y-m-d H:i:s'),
                'type' => $type,
                'read_status' => 0,
                'time_zone' => session()->get('time_zone'),
                'file_path' => $path,
            ];

            $chat_id = insertData('chat', $data); // Replace with your model and method

            $users = [$data['recieved_id'], $data['sent_id']];
            for ($i = 0; $i < 2; $i++) {
                $datas = ['chat_id' => $chat_id, 'can_view' => $users[$i]];
                insertData('chat_deleted_details', $datas); // Replace with your model and method
            }

            echo json_encode(['img' => $path . '/' . $file_name, 'type' => $type, 'file_name' => $file_name]);
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e->getMessage());
            die();
        }
    }
}
