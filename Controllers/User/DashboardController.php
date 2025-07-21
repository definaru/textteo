<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\CommonModel;
use App\Models\DashboardModel;
use App\Models\HomeModel;

class DashboardController extends BaseController
{

    public mixed $data;
    public mixed $session;
    public mixed $timezone;
    /**
     * @var \App\Models\DashboardModel
     */
    public $dashboardModel;
    public mixed $language;
    /**
     * @var \App\Models\HomeModel
     */
    public $homeModel;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    /**
     * @var \App\Models\AppointmentModel
     */
    public $appointmentModel;

    public function __construct()
    {

        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // if(session()->get('admin_id') ==''){
        //     return redirect('admin');
        // }
        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'dashboard';
        $this->data['page'] = '';

        $this->data['uri'] = service('uri');

        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        $this->timezone = session('time_zone');
        if (!empty($this->timezone)) {
            date_default_timezone_set($this->timezone);
        }
        // OpenTok Key

        //Define Model
        $this->dashboardModel = new DashboardModel();
        $this->homeModel = new HomeModel();
        $this->commonModel = new CommonModel();
        $this->appointmentModel = new AppointmentModel();
    }

    /**
     * review list
     * 
     * @return mixed
     */
    public function reviews()
    {
        $user_id = session('user_id');

        if (session('role') == '1') {
            $this->data['module'] = 'doctor';
            $this->data['page'] = 'reviews';
            $this->data['reviews'] = $this->homeModel->reviewListView($user_id);
            echo view('user/doctor/reviews', $this->data);
        } else if (session('role') == '6') {
            $this->data['module'] = 'doctor';
            $this->data['page'] = 'reviews';
            $this->data['reviews'] = $this->homeModel->reviewListView($user_id);
            echo view('user/doctor/reviews', $this->data);
        } else {
            return redirect(base_url() . 'dashboard');
        }
    }
    /**
     * Reply Delete.
     * 
     * @return mixed
     */
    public function deleteReply()
    {
        $user_id = session('user_id');
        if (session('role') == '1' || session('role') == '2') {
            $result = $this->commonModel->deleteData('review_reply', ['id' => $this->request->getPost('id')]);
            if ($result == true) {

                $response['msg'] = $this->language['lg_reply_deleted_s'];
                $response['status'] = 200;
            } else {
                $response['msg'] = $this->language['lg_something_went_'];
                $response['status'] = 500;
            }
            echo json_encode($response);
        } else {
            return redirect(base_url() . 'dashboard');
        }
    }
    /**
     * Add Review Reply.
     * 
     * @return mixed
     */
    public function addReviewReply()
    {

        $user_id = session('user_id');
        if (session('role') == '1') {

            $data['review_id'] = $this->request->getPost('review_id');
            $data['reply'] = $this->request->getPost('reply');
            $data['created_date'] = date('Y-m-d H:i:s');
            $data['time_zone'] = date_default_timezone_get();

            $query = $this->commonModel->insertData('review_reply', $data);
            if ($query) {
                $result = true;
            }
            if ($result == true) {

                $response['msg'] = $this->language['lg_reply_added_suc1'];
                $response['status'] = 200;
            } else {
                $response['msg'] = $this->language['lg_something_went_'];
                $response['status'] = 500;
            }
            echo json_encode($response);
        } else {
            return redirect(base_url() . 'dashboard');
        }
    }
    /**
     * Appointment List.
     * 
     * @return mixed
     */
    public function appoinmentsList()
    {
        $user_id = session('user_id');
        $list = $this->appointmentModel->getDatatables($user_id);
        $data = [];
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $appoinments) {
            if ($appoinments['payment_method'] == 'Pay on Arrive') {
                $hourly_rate = 'Pay on Arrive';
            } else {
                $hourly_rate = !empty($appoinments['per_hour_charge']) ? $appoinments['per_hour_charge'] : 'Free';
            }

            $current_timezone = $appoinments['time_zone'];
            $old_timezone = session('time_zone');

            $appointment_date = date('d M Y', strtotime(convertToTz($appoinments['appointment_date'], $old_timezone, $current_timezone)));
            $appointment_time = date('h:i A', strtotime(convertToTz($appoinments['from_date_time'], $old_timezone, $current_timezone)));
            $appointment_end_time = date('h:i A', strtotime(convertToTz($appoinments['to_date_time'], $old_timezone, $current_timezone)));
            $created_date = date('d M Y', strtotime(convertToTz($appoinments['created_date'], $old_timezone, $current_timezone)));
            $hourly_rate = $hourly_rate;
            $type = $appoinments['type'];

            if ($appoinments['approved'] == 1 && $appoinments['appointment_status'] == 0) {
                $status = '<a href="javascript:void(0);" onclick="conversation_status(\'' . $appoinments['id'] . '\',\'0\')" class="btn btn-sm bg-danger-light"><i class="fas fa-times"></i>' . $this->language['lg_cancel'] . '</a>';
            } elseif ($appoinments['approved'] == 0) {
                $status = '<a href="javascript:void(0);" onclick="conversation_status(\'' . $appoinments['id'] . '\',\'1\')" class="btn btn-sm bg-success-light"><i class="fas fa-check"></i>' . $this->language['lg_accept'] . '</a>';
            }

            $profile_image = base_url() . 'assets/img/user.png';
            if (isset($appoinments['profileimage']) && file_exists($appoinments['profileimage'])) {
                $profile_image = base_url() . $appoinments['profileimage'];
            }
            $no++;
            $row = [];
            $row[] = $no;
            // . ' ' . libsodiumDecrypt($appoinments['last_name'])
            $row[] = '<h2 class="table-avatar">
                  <a href="' . base_url() . 'my_patients/mypatient-preview/' . base64_encode($appoinments['appointment_from']) . '" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image"></a>
                  <a href="' . base_url() . 'my_patients/mypatient-preview/' . base64_encode($appoinments['appointment_from']) . '">' . (libsodiumDecrypt($appoinments['first_name'])) . '</a>
                </h2>';
            // <span>#PT00' . $appoinments['appointment_from'] . '</span>
            $from_date_time = '';
            if (!empty($appoinments['time_zone'])) {
                $from_timezone = $appoinments['time_zone'];
                $to_timezone = date_default_timezone_get();
                $from_date_time = $appoinments['from_date_time'];
                $from_date_time = convertToTz($from_date_time, $to_timezone, $from_timezone);
                $to_date_time = $appoinments['to_date_time'];
                $to_date_time = convertToTz($to_date_time, $to_timezone, $from_timezone);
                $row[] = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info">' . date('h:i A', strtotime($from_date_time)) . ' - ' . date('h:i A', strtotime($to_date_time)) . '</span>';
            } else {
                $row[] = '-';
            }

            // $hourly_rate = ($type=='Clinic')?$hourly_rate:default_currency_symbol().$hourly_rate;
            $hourly_rate = convert_to_user_currency($hourly_rate);
            $payment_method = $appoinments['payment_method'];
            if ($payment_method == 'Pay on Arrive') {
                $hourly_rate = 'Pay on Arrive';
            }

            $row[] = ucfirst($appoinments['type']);
            
            // Pet update code
            //added new on 21st June 2024 by Muddasar
            if ($appoinments['pet_id'] != "") {
                $pet_data=$this->commonModel->getPetById($appoinments['pet_id']);
                if(!empty($pet_data)){
                    $row[] = $pet_data['pet_name'];
                }
                else{
                    $row[] = "-";
                }
            }
            else{
                $row[] = "-";
            }
            //end

            if (session('role') == 6) {
                if ($appoinments['role'] != 6) {
                    $row[] = $appoinments['doctor_name'];
                } else {
                    $row[] = "-";
                }
            }

            $row[] = '<div class="table-action">
                  <a href="javascript:void(0);" onclick="show_appoinments_modal(\'' . $appointment_date . '\',\'' . $appointment_time . ' - ' . $appointment_end_time . '\',\'' . $hourly_rate . '\',\'' . $type . '\')" class="btn btn-sm bg-info-light">
                    <i class="far fa-eye"></i> View
                  </a>
                </div>';

            $data[] = $row;
        }

        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->appointmentModel->countAll($user_id),
            "recordsFiltered" => $this->appointmentModel->countFiltered($user_id),
            "data" => $data,
        ];

        // Output to JSON format
        echo json_encode($output);
    }
}
