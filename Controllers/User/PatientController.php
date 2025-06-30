<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\HomeModel;
use App\Models\UserModel;
use App\Models\AppointmentModel;
use App\Models\MypatientsModel;
use OpenTok\OpenTok;
use OpenTok\Role;
use Aws\S3\S3Client;
class PatientController extends BaseController
{
    public mixed $uri;
    public mixed $data;
    public mixed $session;
    public mixed $timezone;
    public mixed $lang;
    public mixed $language;
    public mixed $profile;

    /**
     * @var \App\Models\UserModel
     */
    public $userModel;
    /**
     * @var \App\Models\AppointmentModel
     */
    public $appointmentModel;
    /**
     * @var \App\Models\HomeModel
     */
    public $homeModel;
    /**
     * @var \App\Models\MypatientsModel
     */
    public $myPatientModel;

    public function __construct()
    {
        $this->data['theme'] = 'user';
        $this->data['module'] = 'patient';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->data['url_segment1'] = service('uri')->getSegment(1);
        $this->data['url_segment2'] = service('uri')->getSegment(2);
        $this->timezone = session('time_zone');
        if (!empty($this->timezone) && session('time_zone') != '') {
            date_default_timezone_set($this->timezone);
        }
        // else{
        //     date_default_timezone_set('Asia/Kolkata');
        // }                
        $this->data['uri'] = service('uri');

        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        $this->homeModel = new HomeModel();
        $this->userModel = new UserModel();
        $this->appointmentModel = new AppointmentModel();
        $this->myPatientModel = new MypatientsModel();
    }
    /**
     * Patient Dashboard page.
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'patientDashboard';
        $this->data['profile'] = $this->userModel->getUserDetails(session('user_id'));
        $this->data['user_detail'] = $this->userModel->getUserDetails(session('user_id'));
        return view('user/patient/patientDashboard', $this->data);
    }
    /**
     * Profile Settings.
     * 
     * @return mixed
     */
    public function profileSettings()
    {
        $this->data['page'] = 'profile';
        $this->data['profile'] = $this->userModel->getUserDetails(session('user_id'));
        $this->data['user_detail'] = $this->userModel->getUserDetails(session('user_id'));
            // Pet update code
    //added new on 13rd June 2024 by Muddasar
        $patientId = session('user_id');
        $this->data['user_pets'] = $this->userModel->getPetsByPatientId($patientId);
        
        return view('user/patient/patientProfile', $this->data);
    }

    public function updateRequiredProfile(){
        $id = session('user_id');
        $userDetail = $this->request->getPost();
        $inputdata['first_name'] = libsodiumEncrypt($userDetail['first_name']);
        $inputdata['last_name'] = libsodiumEncrypt($userDetail['last_name']);
        $inputdata['mobileno'] = libsodiumEncrypt($userDetail['mobileno']);

        $result = $this->homeModel->updateData('users', ['id' => $id], $inputdata);

        if ($result == true) {
            $response['msg'] = $this->language['lg_profile_success'] ?? "";
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_profile_update_'] ?? "";
            $response['status'] = 500;
        }
        echo json_encode($response);
    }

    /**
     * Update Profile.
     * 
     * @return mixed
     */
    public function updateProfile()
    {
        $id = session('user_id');
        $userDetail = $this->request->getPost();

        if (isset($_POST['pharmacy_name']) && $_POST['pharmacy_name'] != '') {
            $inputdata['pharmacy_name'] = libsodiumEncrypt($userDetail['pharmacy_name']);
        }
        $inputdata['first_name'] = libsodiumEncrypt($userDetail['first_name']);
        $inputdata['last_name'] = libsodiumEncrypt($userDetail['last_name']);
        $inputdata['mobileno'] = libsodiumEncrypt($userDetail['mobileno']);
        $inputdata['country_code'] = $userDetail['country_code'];
        $inputdata['country_id'] = $userDetail['country_id'];
        $inputdata['is_updated'] = 1;

        /**
         * Test Hide
         */
        //$inputdata['is_verified'] = 1;

        $userdata['user_id'] = $id;
        $userdata['gender'] = $userDetail['gender'];
        //$userdata['dob'] = date('Y-m-d', strtotime(str_replace('/', '-', $userDetail['dob'])));
        //$userdata['blood_group'] = libsodiumEncrypt($userDetail['blood_group']);
        $userdata['address1'] = libsodiumEncrypt($userDetail['address1']);
        $userdata['address2'] = libsodiumEncrypt($userDetail['address2']);
        $userdata['country'] = $userDetail['country'];
        $userdata['state'] = $userDetail['state'];
        $userdata['city'] = $userDetail['city'];
        //$userdata['postal_code'] = $userDetail['postal_code'];
        $userdata['update_at'] = date('Y-m-d H:i:s');

        $this->homeModel->updateData('users', ['id' => $id], $inputdata);
        $userDetailExist = $this->homeModel->checkTblDataExist('users_details', ['user_id' => $id], 'id', []);

        if ($userDetailExist) {
            $result = $this->homeModel->updateData('users_details', ['user_id' => $id], $userdata);
        } else {
            $result = $this->homeModel->insertData('users_details', $userdata);
        }

        if ($result == true) {
            $response['msg'] = $this->language['lg_profile_success'] ?? "";
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_profile_update_'] ?? "";
            $response['status'] = 500;
        }
        echo json_encode($response);
    }
    /**
     * Crop Profile Image.
     * 
     * @return mixed
     */
    public function cropProfileImg()
    {
        helper('file');

        $max_execution_time = 3000;
        ini_set('max_execution_time', (string) $max_execution_time);
        ini_set('memory_limit', '-1');
        $prev_img = $this->request->getPost('prev_img');
        $av_data         = json_decode($this->request->getPost('avatar_data') ?? "", true);

        $file = $this->request->getFile('avatar_file');
        $fname = $file->getRandomName();
        $src2            = ROOTPATH . 'public/uploads/profileimage/temp/' . $fname;
        $file->move(ROOTPATH . 'public/uploads/profileimage/temp/', $fname);

        $ref_path = '/uploads/profileimage/temp/';
        $image1          = $this->resizeImage($fname, $av_data['width'], $av_data['height'], $av_data['x'], $av_data['y']);

        $inputdata = array();
        $inputdata['profileimage'] = 'uploads/profileimage/' . $fname;
        $id = session('user_id');
        if ($this->homeModel->updateData('users', ['id' => $id], $inputdata)) {
            if (!empty($prev_img)) {
                $file_path = FCPATH . $prev_img;
                if (is_file($file_path)) {
                    unlink(FCPATH . $prev_img);
                }
            }
            unlink($src2);
        }

        $response = array(
            'state'  => 200,
            'message' => '',
            'result' => 'uploads/profileimage/' . $fname,
            'img_name1' => $fname
        );
        echo json_encode($response);
    }
    /**
     * Resize Image.
     * 
     * 
     * @param string $fileName
     * @param int $width
     * @param int $height
     * @param mixed $x
     * @param mixed $y
     * @return mixed
     */
    private function resizeImage($fileName, $width, $height, $x, $y)
    {
        $image = \Config\Services::image()
            ->withFile(ROOTPATH . 'public/uploads/profileimage/temp/' . $fileName)
            ->crop($width, $height, $x, $y)
            ->save(ROOTPATH . 'public/uploads/profileimage/' . $fileName);
    }

    /**
     * Patient Lab-Appointment Page
     * 
     * @return mixed
     */
    public function labAppointmentPage()
    {
        $this->data['page'] = 'lab_appoinments';
        $this->data['profile'] = $this->userModel->getUserDetails(session('user_id'));
        $this->data['user_detail'] = $this->userModel->getUserDetails(session('user_id'));
        return view('user/patient/labAppointmentList', $this->data);
    }
    /**
     * Get Lab Appointment List
     * 
     * @return mixed
     */
    public function labAppointmentListOfPatient()
    {

        $appoinment = new \App\Models\LabPaymentModel;
        $user_id = session('user_id');
        $list = $appoinment->getLabAppointmentDetails($user_id);
        $data = array();
        $no = $_POST['start'];
        $a = $no + 1;
        foreach ($list as $lab_payments) {
            $val = 'Failed';
            $cls = '';
            if ($lab_payments['payment_status'] == '1') {
                $val = 'Success';
            }


            if ($lab_payments['profileimage'] == "" || ($lab_payments['profileimage'] != "" && !is_file($lab_payments['profileimage']))) {
                $profileimage = base_url() . 'assets/img/user.png';
            } else {
                $profileimage = base_url() . $lab_payments['profileimage'];
            }
            $no++;
            $row   = array();
            $row[] = $a++;
            $row[] = '<h2 class="table-avatar">
                        <a target="_blank" href="#" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image">
                        </a>
                        <a target="_blank" href="#">' . (libsodiumDecrypt($lab_payments['first_name']) . " " . libsodiumDecrypt($lab_payments['last_name'])) . '</a>
                    </h2>';
            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $lab_payments['currency_code'];

            $rows   = array();
            /**
             * @var array<array{currency_code:string}> $rows
             */
            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $rows['currency_code'];

            $rate_symbol = currency_code_sign($currency_option);

            $amount = get_doccure_currency($lab_payments['total_amount'], $lab_payments['currency_code'], $user_currency_code);

            // $row[] =$test_name;
            // $row[] =libsodiumDecrypt($lab_payments['lab_test_names']);
            $test_name = "";
            $array_ids = explode(',', $lab_payments['test_ids']);
            foreach ($array_ids as $key => $value) {
                $result = $this->homeModel->getTblRowOfData('lab_tests', array('id' => $value), '*');
                if ($key > 0) {
                    $test_name .= ",";
                }
                $test_name .= libsodiumDecrypt($result['lab_test_name']);
            }

            $row[] = $test_name;

            $row[] = date('d M Y', strtotime($lab_payments['lab_test_date']));
            $row[] = $rate_symbol . $amount;
            $row[] = date('d M Y', strtotime($lab_payments['payment_date']));
            $row[] = $val;
            $row[] = $lab_payments['cancel_status'];
            $row[] = '
                        <a class="btn btn-sm bg-success-light" onclick="view_docs(' . $lab_payments['id'] . ')" href="javascript:void(0)">
                            <i class="fe fe-eye"></i> View Document
                        </a>
                        
                        ';

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $appoinment->labAppointmentsCountAll($user_id),
            "recordsFiltered" => $appoinment->labAppointmentsCountFiltered($user_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Patient Appointment Page
     * 
     * @return mixed
     */
    public function appointmentPage()
    {
        $this->data['page'] = 'appoinments';
        return view('user/patient/appointmentList', $this->data);
    }
    /**
     * Patient Appointment List
     * 
     * 
     * @return mixed
     */
    public function appointmentListOfPatient()
    {
        $apptModal = new \App\Models\AppointmentModel;

        $response = array();
        $result = array();
        $page = $this->request->getPost('page');
        $limit = 8;
        $user_id = session('user_id');
        $this->myPatientModel->updateAppointmentLists($user_id);
        $response['count'] = $apptModal->patientAppoinmentsList($page, $limit, 1, $user_id);
        $data['appoinments_list'] = $apptModal->patientAppoinmentsList($page, $limit, 2, $user_id);
        $data['app_type'] = 'patient';
        $data['language'] = $this->language;
        $result = view('user/patient/appointmentCardView', $data);
        $response['current_page_no'] = $page;
        $response['total_page'] = ceil($response['count'] / $limit);
        $response['date_default_timezone_sets'] = session('time_zone');
        $response['data'] = $result;
        echo json_encode($response);
    }
    /**
     * Doctor Or Clinic Appointment List
     * 
     * @return mixed
     */
    public function appointmentListOfDoctor()
    {
        $apptModal = new \App\Models\AppointmentModel;

        $response = array();
        $result = array();
        $pageMul = $this->request->getPost('page');
        $page = 1;
        $limit = $pageMul * 8;
        $user_id = session('user_id');
        $response['count'] = $apptModal->doctorAppoinmentList($page, $limit, 1, $user_id);
        $data['appoinments_list'] = $apptModal->doctorAppoinmentList($page, $limit, 2, $user_id);
        $data['app_type'] = session('role') == 6 ? 'hospital' : 'doctor';
        $data['language'] = $this->language;
        $result = view('user/patient/appointmentCardView', $data);
        $response['current_page_no'] = $page;
        $response['total_page'] = ceil($response['count'] / $limit);
        $response['date_default_timezone_set'] = date_default_timezone_get();
        $response['data'] = $result;
        echo json_encode($response);
    }
    /**
     * Patient Favourite Doctors
     * 
     * 
     * @return mixed
     */
    public function favourites()
    {
        $user_id = session('user_id');
        $this->data['module'] = 'patient';
        $this->data['page'] = 'favourites';
        $this->data['favourites'] = $this->homeModel->getFavourites($user_id);
        return view('user/patient/favourites', $this->data);
    }


    public function myAppoinmentEdit(){
        
        $appointmentData = $this->request->getPost();
        $id = $appointmentData['id'];
        $date = $appointmentData['appointment_date'];
        $start_time_from = $appointmentData['appointment_start_time'];
        $start_time_to = $appointmentData['appointment_end_time'];
        $appointment_token = $appointmentData['appointment_token'];
        $appointment_session = $appointmentData['appointment_session'];
        $appointment_type = $appointmentData['appointment_type'];
        $result = $this->myPatientModel->updateAppointmentForPatient($id, $date, $start_time_from, $start_time_to, $appointment_token, $appointment_session, $appointment_type);
        if($result){
            $data = ['result' => $result, 'status' => 200];
        }else{
            $data = ['result' => $result, 'status' => 500];
        }

        echo json_encode($data);
    }

    public function myPrevoiusAppoinmentsList()
    {
        if (session('role') == 1) {
            $user_id = $this->request->getPost('patient_id');
        } else {
            $user_id = session('user_id');
        }
        $list = $this->myPatientModel->patientPreviousAppointments($user_id);
        $data = array();
        $no = $_POST['start'];
        $a = 0;
        $sno = $no + 1;

        foreach ($list as $appoinments) {
            $profile_image = base_url() . 'assets/img/user.png';
            if ($appoinments['profileimage'] != "" && is_file($appoinments['profileimage'])) {
                $profile_image = base_url() . $appoinments['profileimage'];
            }

          

            $no++;
            $a++;
            $row = array();
            $row[] = $sno++;

            if ($appoinments['hospital_id'] != "") {
                $clinicUsername = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['clinic_username']));
                
                $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                
                $dr = $this->language['lg_dr'];
                $doctor_section = '
                <div class="d-flex align-items-start gap-3">
                <div class="doctor-img-appointment">
                   <a href="' . base_url() . 'doctor-preview/' . $clinicUsername . '" class="avatar avatar-sm mr-2">
                    <img src="' . $profile_image . '" class="img-fluid" alt="User Image" width="50" height="50">
                  </a>
                </div>
                <div class="doc-info-cont-appointment" style="margin: 0; padding: 0; font-size:14px; font-weight:500;">
                    <h4 class="doc-name-appointment" style="margin: 0; padding: 0;color:#252525;">
                         <a href="' . base_url() . 'doctor-preview/' . $clinicUsername . '" style="margin: 0; padding: 0;">' .$dr.' '. libsodiumDecrypt($appoinments["clinic_first_name"]) . '</a>
                    </h4>
                    <span class="doc-department-appointment" style="margin: 0; padding: 0; color:#757575">' . $specialization . '</span>
                </div>
            </div>
            ';    
               
                $row[] = $doctor_section;
                //   ' '.libsodiumDecrypt($appoinments['clinic_last_name'])
            } else {
                $username = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['username']));
                if ($appoinments['role'] == 1) {
                    $value = $this->language['lg_dr'];
                    $img = '<a href="' . base_url() . 'doctor-preview/' . $username . '" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                } else {
                    $value = "";
                    $img = '<a href="' . base_url() . 'doctor-preview/' . $username . '" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                }

                $row[] = '<h2 class="table-avatar">
            ' . $img . '
            <a href="' . base_url() . 'doctor-preview/' . $username . '">' . $value . ' ' . (libsodiumDecrypt($appoinments['first_name'])) . ' <span>' . $specialization . '</span></a>
            </h2>';

                //.' '.libsodiumDecrypt($appoinments['last_name'])
            }
            $from_date_time = '';
            if (!empty($appoinments['time_zone'])) {
                $from_timezone = $appoinments['time_zone'];
                $to_timezone = date_default_timezone_get();
                $from_date_time = $appoinments['from_date_time'];
                //$from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
                $to_date_time = $appoinments['to_date_time'];
                $to_date_time = converToTz($to_date_time, $to_timezone, $from_timezone);
                $row[] = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info"> Starts at ' . date('h:i A', strtotime($from_date_time)) . '</span>';//. ' - ' . date('h:i A', strtotime($to_date_time)) .
            } else {
                $row[] = '-';
            }

            if(isset($appoinments['prescription_id']) && $appoinments['prescription_id'] != null){
                //$row['pres_item_drug_name'] = $appoinments['pres_item_drug_name'];
                // $row['pres_item_qty'] = $appoinments['pres_item_qty'];
                // $row['pres_item_type'] = $appoinments['pres_item_type'];
                // $row['pres_item_days'] = $appoinments['pres_item_days'];
                // $row['pres_item_time'] = $appoinments['pres_item_time'];
                // $items = explode("||", $appoinments['prescription_items']);
                // foreach ($items as $item) {
                //     list($prescription_id, $drug, $qty, $type, $days, $time) = explode("|", $item);
                //     // use values
                // }
                $prescription_id = $appoinments['prescription_id'];
               
                $row[] = '
                       <a href="javascript:void(0);" class="text-warning" onclick="view_prescriptionV2(' . $prescription_id . ')">
                            <img src="' . base_url('icons/c_vets.svg') . '" alt="icon" style="width:16px; height:16px; vertical-align:middle;"> 
                            Advice ' . date('d M Y', strtotime($from_date_time)) . '
                        </a>
                            ';
            }else{
                $row[] = '-';
            }

            if ($appoinments['pet_id'] != "") {
                $pet_data=$this->userModel->getPetById($appoinments['pet_id']);
                if(!empty($pet_data)){
                    $row[] = $pet_data['pet_name'];
                }
            }
            else{
                $row[] = '-';
            }
            $row[] = date('d M Y', strtotime($appoinments['created_date']));
            $row[] = ucfirst($appoinments['type']);
            
            // Pet update code
            //added new on 13rd June 2024 by Muddasar
         
            if ($appoinments['tokbox_archive_id'] != "") {
                $row[] = '<a href="/ajax.php?archiveId='.$appoinments['tokbox_archive_id'].'" target="_blank">archive link</a>';
            }
            else{
                $row[] = '-';
            }

            if ($appoinments['approved'] == 1 && $appoinments['call_status'] == 0 && $appoinments['type'] != 'Online') {
                $new_apt = '';
                $com_apt = '';
                $exp_apt = '';
                if ($appoinments['appointment_status'] == 0) {
                    $new_apt = 'selected';
                } else if ($appoinments['appointment_status'] == 1) {
                    $com_apt = 'selected';
                } else if ($appoinments['appointment_status'] == 2) {
                    $exp_apt = 'selected';
                }
                if ($appoinments['appointment_status'] != 2) {
                    $row[] = '<div class="actions">
                <select name="appointment_status" class="form-control appointment_status" id="' . $appoinments['id'] . '">
                <option value="0" ' . $new_apt . '>New</option>
                <option value="1" ' . $com_apt . '>Completed</option>
                <option value="2" ' . $exp_apt . '>Expired</option>
                </select>
                </div>';
                }
                if ($appoinments['appointment_status'] == 2) {
                    $app_status = "Expired";
                    $row[] = $app_status;
                }
            } else if ($appoinments['approved'] == 1 && $appoinments['call_status'] == 1 && $appoinments['type'] != 'Online') {
                if ($appoinments['appointment_status'] == 1) {
                    $app_status = "Completed";
                } else if ($appoinments['appointment_status'] == 2) {
                    $app_status = "Expired";
                } else {
                    $app_status = '-';
                }

                $row[] = $app_status;
            } else {
                $app_status = '-';
                $row[] = $app_status;
            }

            

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $a,
            "recordsFiltered" => $this->myPatientModel->countAllAppointmentsOfPatient($user_id),
            "data" => $data,
        );
        echo json_encode($output);
    }

    /**
     * Patient Dashboard Appointment List
     * 
     * 
     * @return mixed
     */
    public function myAppoinmentsList()
    {
        if (session('role') == 1) {
            $user_id = $this->request->getPost('patient_id');
        } else {
            $user_id = session('user_id');
        }
        $list = $this->myPatientModel->patientAppointments($user_id);
        $data = array();
        $no = $_POST['start'];
        $a = 0;
        $sno = $no + 1;

        foreach ($list as $appoinments) {
            $profile_image = base_url() . 'assets/img/user.png';
            if ($appoinments['profileimage'] != "" && is_file($appoinments['profileimage'])) {
                $profile_image = base_url() . $appoinments['profileimage'];
            }

          

            $no++;
            $a++;
            $row = array();
            $row[] = $sno++;

            if ($appoinments['hospital_id'] != "") {
                $clinicUsername = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['clinic_username']));
                
                $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                
                $dr = $this->language['lg_dr'];
                $doctor_section = '
                <div class="d-flex align-items-start gap-3" style="
                background-color: #FFFFFF;
                margin-bottom: 10px;
                padding: 5%;
                border-radius: 5%;
                ">
                <div class="doctor-img-appointment">
                   <a href="' . base_url() . 'doctor-preview/' . $clinicUsername . '" class="avatar avatar-sm mr-2">
                    <img src="' . $profile_image . '" class="img-fluid" alt="User Image" width="50" height="50">
                  </a>
                </div>
                <div class="doc-info-cont-appointment" style="margin: 0; padding: 0; font-size:14px; font-weight:500;">
                    <h4 class="doc-name-appointment" style="margin: 0; padding: 0;color:#252525;">
                         <a href="' . base_url() . 'doctor-preview/' . $clinicUsername . '" style="margin: 0; padding: 0;">' .$dr.' '. libsodiumDecrypt($appoinments["clinic_first_name"]) . '</a>
                    </h4>
                    <span class="doc-department-appointment" style="margin: 0; padding: 0; color:#757575">' . $specialization . '</span>
                </div>
            </div>
            ';    
               
                $row[] = $doctor_section;
                //   ' '.libsodiumDecrypt($appoinments['clinic_last_name'])
            } else {
                $username = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['username']));
                if ($appoinments['role'] == 1) {
                    $value = $this->language['lg_dr'];
                    $img = '<a href="' . base_url() . 'doctor-preview/' . $username . '" class="avatar avatar-sm mr-2">   
                    <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                } else {
                    $value = "";
                    $img = '<a href="' . base_url() . 'doctor-preview/' . $username . '" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                }

                $row[] = '<h2 class="table-avatar">
            ' . $img . '
            <a href="' . base_url() . 'doctor-preview/' . $username . '">' . $value . ' ' . (libsodiumDecrypt($appoinments['first_name'])) . ' <span>' . $specialization . '</span></a>
            </h2>';

                //.' '.libsodiumDecrypt($appoinments['last_name'])
            }
            $dateSlot = null;
            $timeSlot = null;
            $from_date_time = '';
            if (!empty($appoinments['time_zone'])) {
                $from_timezone = $appoinments['time_zone'];
                $to_timezone = date_default_timezone_get();
                $from_date_time = $appoinments['from_date_time'];
                //$from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
                $to_date_time = $appoinments['to_date_time'];
                $to_date_time = converToTz($to_date_time, $to_timezone, $from_timezone);
                $row[] = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info"> Starts at ' . date('h:i A', strtotime($from_date_time)) . '</span>';//. ' - ' . date('h:i A', strtotime($to_date_time)) .
                $dateSlot = date('Y-m-d', strtotime($from_date_time));
                $timeSlot = date('h:i A', strtotime($from_date_time));
            } else {
                $row[] = '-';
                $dateSlot = date('Y-m-d');
                $timeSlot = '-';
            }

            $pet_data = null;
            if ($appoinments['pet_id'] != "") {
                $pet_data=$this->userModel->getPetById($appoinments['pet_id']);
                if(!empty($pet_data)){
                    $row[] = $pet_data['pet_name'];
                }
            }
            else{
                $row[] = '-';
            }
            $action_menu = '
     <style>
     table {
  overflow: visible !important;
}

td, th {
  overflow: visible !important;
  position: relative; /* Make sure dropdown can position itself relative to this */
}

.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  background-color: #FFFFFF;
  border: 1px;
  border-redius: 8px;
  min-width: 160px;
  box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
  z-index: 99999;
}

.dropdown-content.show {
  display: block;
}

.dropdown-content a {
  color: #545454;
  padding: 12px 16px;
  border-bottom: 1px solid #E1E1E1;
  text-decoration: none;
  display: block;
}
  
 .slot-container {
        width: 100%;
        margin: 0 auto;
        text-align: center;
        font-family: "Poppins", sans-serif;
        background-color:#FFFFFF;
        border-radius: 20px;
    }

    .slot-container h2{
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
        padding-left: 2%;
        margin-bottom: 2%;
        color:#252525;
    }
    
    .slot-header {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
        padding-left: 2%;
        margin-bottom: 0;
    }

    .slot-header input,
    .slot-header select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 12px;
        background-color:#F7F7F7;
    }

    .slots-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 8px;
        justify-content: center;
        padding:2%;
    }

    .slot {
        padding: 2px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 400;
        cursor: pointer;
        transition: all 0.3s ease;
        color:#252525
    }

    .slot:hover {
        border-color: #FD9720;
        color: #FD9720;
    }

    .slot.active {
        border: 2px solid #FD9720;
        color: #FD9720;
        font-weight: bold;
    }

    .slot-booked{
        border: 2px solid #FD9720;
        color: #FD9720;
        font-weight: bold;
    }

    .see-more{
    display:none;
    }

    .apt-btn{
        background-color: #fd9720;
        color: #fff;
        border-radius:4px;
    }

    .apt-btn:hover,
    .apt-btn:focus
    {
        background-color: #fd9720;
        color: #fff;
    }
    .apt-btn-div{
      padding: 2%;
    }
  


    </style>

                <div class="dropdown">
                    <button onclick="toggleDropdown(this)" class="btn p-0 border-0 bg-transparent">&#x22EE;</button>
                    <div class="dropdown-content">
                        <a id="appointment-edit-id" data-time='.$timeSlot.' data-date='.$dateSlot.' data-doctor-id='.$appoinments['hospital_id'].' data-appointment-id='.$appoinments['id'].' href="javascript:void(0);">Edit Appointment</a>  
                        <a href="#">Cancel Appointment</a>
                    </div>
                </div>

                <div class="modal fade" id="editAppointmentModal-'.$appoinments['id'].'">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="slot-container">
                <h2 class="title-slot">Select a slot</h2>
                    <div class="slot-header">
                        <input type="date" name="schedule_date" data-doctor-id='.$appoinments['hospital_id'].' data-appointment-id='.$appoinments['id'].' id="schedule_date" value="'.$dateSlot.'" min="'.Date('Y-m-d').'">
                        
                    </div>
                    <div class="slots-doctor-day-'.$appoinments['id'].'">

                    </div>

                     <div class="apt-btn-div">
                       <button style="width:100%"  data-appointment-id="'.$appoinments['id'].'" id="edit-appointment-btn" class="btn btn-default apt-btn">Save</button>
                    </div>
              </div>
             
             
      </div>
  </div>
</div>

              <a href="'.base_url().'patient/appointment/'.$appoinments['id'].'" class="app_details"> > </a>          
            ';
            $row[] = $action_menu;
            $row[] = base_url().'uploads/pet_images/'.$pet_data['pet_photo'];
            $row[] = $pet_data['pet_type'];
            $row[] = base_url().'/icons/Vector.svg';

            $row[] = date('d M Y', strtotime($appoinments['created_date']));
            $row[] = ucfirst($appoinments['type']);
            
            // Pet update code
            //added new on 13rd June 2024 by Muddasar
         
            if ($appoinments['tokbox_archive_id'] != "") {
                $row[] = '<a href="/ajax.php?archiveId='.$appoinments['tokbox_archive_id'].'" target="_blank">archive link</a>';
            }
            else{
                $row[] = '-';
            }

            if ($appoinments['approved'] == 1 && $appoinments['call_status'] == 0 && $appoinments['type'] != 'Online') {
                $new_apt = '';
                $com_apt = '';
                $exp_apt = '';
                if ($appoinments['appointment_status'] == 0) {
                    $new_apt = 'selected';
                } else if ($appoinments['appointment_status'] == 1) {
                    $com_apt = 'selected';
                } else if ($appoinments['appointment_status'] == 2) {
                    $exp_apt = 'selected';
                }
                if ($appoinments['appointment_status'] != 2) {
                    $row[] = '<div class="actions">
                <select name="appointment_status" class="form-control appointment_status" id="' . $appoinments['id'] . '">
                <option value="0" ' . $new_apt . '>New</option>
                <option value="1" ' . $com_apt . '>Completed</option>
                <option value="2" ' . $exp_apt . '>Expired</option>
                </select>
                </div>';
                }
                if ($appoinments['appointment_status'] == 2) {
                    $app_status = "Expired";
                    $row[] = $app_status;
                }
            } else if ($appoinments['approved'] == 1 && $appoinments['call_status'] == 1 && $appoinments['type'] != 'Online') {
                if ($appoinments['appointment_status'] == 1) {
                    $app_status = "Completed";
                } else if ($appoinments['appointment_status'] == 2) {
                    $app_status = "Expired";
                } else {
                    $app_status = '-';
                }

                $row[] = $app_status;
            } else {
                $app_status = '-';
                $row[] = $app_status;
            }

            

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $a,
            "recordsFiltered" => $this->myPatientModel->countAllAppointmentsOfPatient($user_id, 1),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function myAppoinment($appointment_id){
         $list = $this->myPatientModel->appointment($appointment_id);
        $data = array();
        $no = 0;
        $a = 0;
        $sno = $no + 1;

        foreach ($list as $appoinments) {
            $profile_image = base_url() . 'assets/img/user.png';
            if ($appoinments['profileimage'] != "" && is_file($appoinments['profileimage'])) {
                $profile_image = base_url() . $appoinments['profileimage'];
            }

          

            $no++;
            $a++;
            $row = array();
            $row[] = $sno++;

            if ($appoinments['hospital_id'] != "") {
                $clinicUsername = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['clinic_username']));
                
                $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                
                $dr = $this->language['lg_dr'];
                $doctor_section = '
                <div class="d-flex align-items-start gap-3" style="
                background-color: #FFFFFF;
                margin-bottom: 10px;
                padding: 5%;
                border-radius: 5%;
                ">
                <div class="doctor-img-appointment">
                   <a href="' . base_url() . 'doctor-preview/' . $clinicUsername . '" class="avatar avatar-sm mr-2">
                    <img src="' . $profile_image . '" class="img-fluid" alt="User Image" width="50" height="50">
                  </a>
                </div>
                <div class="doc-info-cont-appointment" style="margin: 0; padding: 0; font-size:14px; font-weight:500;">
                    <h4 class="doc-name-appointment" style="margin: 0; padding: 0;color:#252525;">
                         <a href="' . base_url() . 'doctor-preview/' . $clinicUsername . '" style="margin: 0; padding: 0;">' .$dr.''. libsodiumDecrypt($appoinments["clinic_first_name"]) . '</a>
                    </h4>
                    <span class="doc-department-appointment" style="margin: 0; padding: 0; color:#757575">' . $specialization . '</span>
                </div>
            </div>
            ';    
               
                $row[] = $doctor_section;
                //   ' '.libsodiumDecrypt($appoinments['clinic_last_name'])
            } else {
                $username = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['username']));
                if ($appoinments['role'] == 1) {
                    $value = $this->language['lg_dr'];
                    $img = '<a href="' . base_url() . 'doctor-preview/' . $username . '" class="avatar avatar-sm mr-2">   
                    <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                } else {
                    $value = "";
                    $img = '<a href="' . base_url() . 'doctor-preview/' . $username . '" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                }

                $row[] = '<h2 class="table-avatar">
            ' . $img . '
            <a href="' . base_url() . 'doctor-preview/' . $username . '">' . $value . ' ' . (libsodiumDecrypt($appoinments['first_name'])) . ' <span>' . $specialization . '</span></a>
            </h2>';

                //.' '.libsodiumDecrypt($appoinments['last_name'])
            }
            $dateSlot = null;
            $timeSlot = null;
            $from_date_time = '';
            if (!empty($appoinments['time_zone'])) {
                $from_timezone = $appoinments['time_zone'];
                $to_timezone = date_default_timezone_get();
                $from_date_time = $appoinments['from_date_time'];
                //$from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
                $to_date_time = $appoinments['to_date_time'];
                $to_date_time = converToTz($to_date_time, $to_timezone, $from_timezone);
                $row[] = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info"> Starts at ' . date('h:i A', strtotime($from_date_time)) . '</span>';//. ' - ' . date('h:i A', strtotime($to_date_time)) .
                $dateSlot = date('Y-m-d', strtotime($from_date_time));
                $timeSlot = date('h:i A', strtotime($from_date_time));
            } else {
                $row[] = '-';
                $dateSlot = date('Y-m-d');
                $timeSlot = '-';
            }

            $pet_data = null;
            if ($appoinments['pet_id'] != "") {
                $pet_data=$this->userModel->getPetById($appoinments['pet_id']);
                if(!empty($pet_data)){
                    $row[] = $pet_data['pet_name'];
                }
            }
            else{
                $row[] = '-';
            }
            $action_menu = '
     <style>
     table {
  overflow: visible !important;
}

td, th {
  overflow: visible !important;
  position: relative; /* Make sure dropdown can position itself relative to this */
}

.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  background-color: #FFFFFF;
  border: 1px;
  border-redius: 8px;
  min-width: 160px;
  box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
  z-index: 99999;
}

.dropdown-content.show {
  display: block;
}

.dropdown-content a {
  color: #545454;
  padding: 12px 16px;
  border-bottom: 1px solid #E1E1E1;
  text-decoration: none;
  display: block;
}
  
 .slot-container {
        width: 100%;
        margin: 0 auto;
        text-align: center;
        font-family: "Poppins", sans-serif;
        background-color:#FFFFFF;
        border-radius: 20px;
    }

    .slot-container h2{
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
        padding-left: 2%;
        margin-bottom: 2%;
        color:#252525;
    }
    
    .slot-header {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
        padding-left: 2%;
        margin-bottom: 0;
    }

    .slot-header input,
    .slot-header select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 12px;
        background-color:#F7F7F7;
    }

    .slots-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 8px;
        justify-content: center;
        padding:2%;
    }

    .slot {
        padding: 2px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 400;
        cursor: pointer;
        transition: all 0.3s ease;
        color:#252525
    }

    .slot:hover {
        border-color: #FD9720;
        color: #FD9720;
    }

    .slot.active {
        border: 2px solid #FD9720;
        color: #FD9720;
        font-weight: bold;
    }

    .slot-booked{
        border: 2px solid #FD9720;
        color: #FD9720;
        font-weight: bold;
    }

    .see-more{
    display:none;
    }

    .apt-btn{
        background-color: #fd9720;
        color: #fff;
        border-radius:4px;
    }

    .apt-btn:hover,
    .apt-btn:focus
    {
        background-color: #fd9720;
        color: #fff;
    }
    .apt-btn-div{
      padding: 2%;
    }
  


    </style>

                <div class="dropdown">
                    <button onclick="toggleDropdown(this)" class="btn p-0 border-0 bg-transparent">&#x22EE;</button>
                    <div class="dropdown-content">
                        <a id="appointment-edit-id" data-time='.$timeSlot.' data-date='.$dateSlot.' data-doctor-id='.$appoinments['hospital_id'].' data-appointment-id='.$appoinments['id'].' href="javascript:void(0);">Edit Appointment</a>  
                        <a href="#">Cancel Appointment</a>
                    </div>
                </div>

                <div class="modal fade" id="editAppointmentModal-'.$appoinments['id'].'">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="slot-container">
                <h2 class="title-slot">Select a slot</h2>
                    <div class="slot-header">
                        <input type="date" name="schedule_date" data-doctor-id='.$appoinments['hospital_id'].' data-appointment-id='.$appoinments['id'].' id="schedule_date" value="'.$dateSlot.'" min="'.Date('Y-m-d').'">
                        
                    </div>
                    <div class="slots-doctor-day-'.$appoinments['id'].'">

                    </div>

                     <div class="apt-btn-div">
                       <button style="width:100%"  data-appointment-id="'.$appoinments['id'].'" id="edit-appointment-btn" class="btn btn-default apt-btn">Save</button>
                    </div>
              </div>
             
             
      </div>
  </div>
</div>
        
            ';
            $row[] = $action_menu;
            $row[] = base_url().'uploads/pet_images/'.$pet_data['pet_photo'];
            $row[] = $pet_data['pet_type'];
            $row[] = base_url().'/icons/Vector.svg';

            $row[] = date('d M Y', strtotime($appoinments['created_date']));
            $row[] = ucfirst($appoinments['type']);
            
            // Pet update code
            //added new on 13rd June 2024 by Muddasar
         
            if ($appoinments['tokbox_archive_id'] != "") {
                $row[] = '<a href="/ajax.php?archiveId='.$appoinments['tokbox_archive_id'].'" target="_blank">archive link</a>';
            }
            else{
                $row[] = '-';
            }

            if ($appoinments['approved'] == 1 && $appoinments['call_status'] == 0 && $appoinments['type'] != 'Online') {
                $new_apt = '';
                $com_apt = '';
                $exp_apt = '';
                if ($appoinments['appointment_status'] == 0) {
                    $new_apt = 'selected';
                } else if ($appoinments['appointment_status'] == 1) {
                    $com_apt = 'selected';
                } else if ($appoinments['appointment_status'] == 2) {
                    $exp_apt = 'selected';
                }
                if ($appoinments['appointment_status'] != 2) {
                    $row[] = '<div class="actions">
                <select name="appointment_status" class="form-control appointment_status" id="' . $appoinments['id'] . '">
                <option value="0" ' . $new_apt . '>New</option>
                <option value="1" ' . $com_apt . '>Completed</option>
                <option value="2" ' . $exp_apt . '>Expired</option>
                </select>
                </div>';
                }
                if ($appoinments['appointment_status'] == 2) {
                    $app_status = "Expired";
                    $row[] = $app_status;
                }
            } else if ($appoinments['approved'] == 1 && $appoinments['call_status'] == 1 && $appoinments['type'] != 'Online') {
                if ($appoinments['appointment_status'] == 1) {
                    $app_status = "Completed";
                } else if ($appoinments['appointment_status'] == 2) {
                    $app_status = "Expired";
                } else {
                    $app_status = '-';
                }

                $row[] = $app_status;
            } else {
                $app_status = '-';
                $row[] = $app_status;
            }

            $data[] = $row;
        }


        return view('user/patient/appointmentDetails', ['data' => $data, 'page' => 'patientDashboard', 'module' => 'patient', 'theme' => 'user']);
    }

    
    /**
     * Patient Doctor Prescription List
     * 
     * 
     * @return mixed
     */
    public function myPrescriptionList()
    {
        $list = $this->myPatientModel->patientPrescription(session('user_id'), 'result');
        $data = array();
        $no = $_POST['start'];
        $a = 0;
        $b = $no + 1;
        foreach ($list as $prescriptions) {

            $profile_image = base_url() . 'assets/img/user.png';
            if ($prescriptions['profileimage'] != "" && is_file($prescriptions['profileimage'])) {
                $profile_image = base_url() . $prescriptions['profileimage'];
            }

            $no++;
            $row = array();
            $row[] = ++$a;
            $row[] = date('d M Y', strtotime($prescriptions['created_at']));
            $row[] = 'Prescription ' . $b++;
            $row[] = '<h2 class="table-avatar">
            <a href="' . base_url() . 'doctor-preview/' . $prescriptions['username'] . '" class="avatar avatar-sm mr-2">
            <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
            </a>
            <a href="' . base_url() . 'doctor-preview/' . $prescriptions['username'] . '">Dr. ' . ucfirst(libsodiumDecrypt($prescriptions['first_name']) . ' ' . libsodiumDecrypt($prescriptions['last_name'])) . ' <span>' . ucfirst(libsodiumDecrypt($prescriptions['specialization'])) . '</span></a>
            </h2>
            ';

            $html = '<div class="table-action">
            <a href="' . base_url() . 'my_patients/print-prescription/' . base64_encode($prescriptions['id']) . '" target="_blank" download class="btn btn-sm bg-success-light"><i class="fas fa-download"></i> ' . $this->language['lg_download'] . '</a>

            <a target="_blank" href="' . base_url() . 'my_patients/print-prescription/' . base64_encode($prescriptions['id']) . '" class="btn btn-sm bg-primary-light">
            <i class="fas fa-print"></i> ' . $this->language['lg_print'] . '
            </a>
            <a href="javascript:void(0);" onclick="view_prescription(' . $prescriptions['id'] . ')" class="btn btn-sm bg-info-light">
            <i class="far fa-eye"></i> ' . $this->language['lg_view1'] . '
            </a>';
            if (is_doctor()) {
                $html .= '<a href="' . base_url() . 'edit-prescription/' . base64_encode($prescriptions['id']) . '/' . base64_encode($prescriptions['patient_id']) . '" class="btn btn-sm bg-success-light">
                <i class="fas fa-edit"></i> ' . $this->language['lg_edit2'] . '
                </a>
                <a href="javascript:void(0);" onclick="delete_prescription(' . $prescriptions['id'] . ')" class="btn btn-sm bg-danger-light">
                <i class="far fa-trash-alt"></i> ' . $this->language['lg_delete'] . '
                </a>';
            }
            $html .= '</div>';

            $row[] = $html;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $a,
            "recordsFiltered" => $this->myPatientModel->patientPrescription(session('user_id'), 'count'),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Lab Appointment Test Doc
     * 
     * 
     * @param int $id
     * @return mixed
     */
    public function lbTestDocs($id)
    {
        $path = "uploads/lab_result/" . $id;
        $file_array = array();

        foreach (glob($path . '/*.*') as $file) {
            array_push($file_array, $file);
        }
        echo json_encode($file_array);
    }
        // Pet update code
    //added new on 13rd June 2024 by Muddasar
    public function createPet()
    {
        // Define upload directory path
        //$uploadPath = WRITEPATH . 'uploads/pet_images';
        
        $uploadPath = 'uploads/pet_images';

        // Ensure the upload directory exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true); // Create directory if it doesn't exist
        }
        
        $petBirthDate = $this->request->getPost('petBirthDate');

        // Check if petBirthDate is not empty before converting its format
        if (!empty($petBirthDate)) {
            // Convert format to 'Y-m-d'
            $petBirthDate = date_create_from_format('d/m/Y', $petBirthDate)->format('Y-m-d');
        }

        // Get form data
        $data = [
            'patient_id' => session('user_id'),
            'pet_name' => $this->request->getPost('petName'),
            'pet_age' => $this->request->getPost('petAge'),
            'pet_birth_date' => $petBirthDate,
            'pet_type' => $this->request->getPost('petType'),
            'breed_type' => $this->request->getPost('breedType'),
            'breed_size' => $this->request->getPost('breedSize'),
            'gender' => $this->request->getPost('gender'),
            'weight' => $this->request->getPost('weight'),
            'weight_condition' => $this->request->getPost('weightCondition'),
            'activity_level' => $this->request->getPost('activityLevel'),
            'created_date' => date('Y-m-d H:i:s'),
            'is_updated' => 0
        ];

        // Handle file upload if file exists
        $file = $this->request->getFile('petPhoto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['pet_photo'] = $newName;
        } else {
            $data['pet_photo'] = null; // Set pet_photo to null if no file uploaded or upload failed
        }

        // Insert data into the database
        $result = $this->homeModel->insertData('pets', $data);

        // Prepare JSON response
        $response = [];
        if ($result) {
            // Success message using language file
            $successMessage = 'sucess'; //$this->language['lg_pet_added_s']
            session()->setFlashdata('success_message', $successMessage);

            $response['success'] = true;
            $response['message'] = $successMessage;
            $response['status'] = 200; // HTTP status code indicating success
        } else {
            $response['success'] = false;
            $response['message'] = $this->language['lg_pet_added_f'];
            $response['status'] = 500; // HTTP status code indicating failure
        }

        // Output JSON response
        echo json_encode($response);
    }
    
    public function deletePet()
    {

        // Initialize response array
        $response = [
            'status' => 500,
            'msg' => 'Failed to delete pet.'
        ];

        // Get pet_id from POST request
        $pet_id = $this->request->getPost('pet_id');

        if ($pet_id) {
            // Attempt to delete the pet from the database
            $delete = $this->homeModel->deleteData('pets', ['id' => $pet_id]);

            if ($delete) {
                
                $successMessage = 'Pet deleted successfully.';
                session()->setFlashdata('success_message', $successMessage);
                
                $response['status'] = 200;
                $response['msg'] =$successMessage;
            }
        }

        // Return JSON response
        echo json_encode($response);
    }
    
    public function getPetModal()
    {
        $pet_id = $this->request->getPost('pet_id');
        $pet = $pet_id ? $this->userModel->getPetById($pet_id) : null;
       
        
        //$data['pet'] = $pet_id ? $this->userModel->getPetById($pet_id) : null;
        //return view('user/patient/petModal', $data);
        
        $data['pet'] = $pet;
        $html = view('user/patient/petModal', $data);
        return $this->response->setJSON(['status' => 'success', 'html' => $html, 'pet' => $pet]);
    }

    public function getPatientDoctorAdvice(){
        $pet_id = $this->request->getPost('pres_id');
    }
    
    public function editPet()
    {
        // Define upload directory path
        $uploadPath = 'uploads/pet_images';

        // Ensure the upload directory exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true); // Create directory if it doesn't exist
        }

        $petId = $this->request->getPost('petId');
        $petBirthDate = $this->request->getPost('petBirthDate');

        // Check if petBirthDate is not empty before converting its format
        if (!empty($petBirthDate)) {
            // Convert format to 'Y-m-d'
            $petBirthDate = date_create_from_format('d/m/Y', $petBirthDate)->format('Y-m-d');
        }

        // Get form data
        $data = [
            'patient_id' => session('user_id'),
            'pet_name' => $this->request->getPost('petName'),
            'pet_birth_date' => $petBirthDate,
            'pet_type' => $this->request->getPost('petType'),
            'pet_age' => $this->request->getPost('petAge'),
            'breed_type' => $this->request->getPost('breedType'),
            'breed_size' => $this->request->getPost('breedSize'),
            'gender' => $this->request->getPost('gender'),
            'weight' => $this->request->getPost('weight'),
            'weight_condition' => $this->request->getPost('weightCondition'),
            'activity_level' => $this->request->getPost('activityLevel'),
            'is_updated' => 1 // Set flag indicating this record has been updated
        ];

        // Handle file upload if a new file exists
        $file = $this->request->getFile('petPhoto');
        if ($file && $file->isValid() && !$file->hasMoved()) {

            // Remove old photo if a new one is uploaded
            /*$oldPhoto = $this->request->getPost('oldPetPhoto');
            if ($oldPhoto && file_exists($uploadPath . '/' . $oldPhoto)) {
                unlink($uploadPath . '/' . $oldPhoto);
            }*/

            // Move new photo to upload directory
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $data['pet_photo'] = $newName;
        }

        // Update data in the database
        $result = $this->homeModel->updateData('pets',['id' => $petId], $data);

        // Prepare JSON response
        $response = [];
        if ($result) {
            // Success message using language file
            $successMessage = $this->language['lg_pet_updated'];
            session()->setFlashdata('success_message', $successMessage);

            $response['success'] = true;
            $response['message'] = $successMessage;
            $response['status'] = 200; // HTTP status code indicating success
        } else {
            $response['success'] = false;
            $response['message'] = $this->language['lg_pet_updated1'];
            $response['status'] = 500; // HTTP status code indicating failure
        }
        
        //echo "<pre>";
        //print_r($data);
        //var_dump($response);
        //exit;

        // Output JSON response
        echo json_encode($response);
    }

    public function getPatientPets()
    {
        $pets = $this->userModel->getPetsByPatientId(session('user_id'));
        $data['pets'] = $pets;
        $html = view('user/patient/petModalSelect', $data);
        return $this->response->setJSON(['status' => 'success', 'html' => $html]);
    }

    public function appointmentCaptions($sessionId)
    {

        if(stripos($sessionId, 'stop_') !== false){
            $tokboxKey = !empty(settings("apiKey")) ? libsodiumDecrypt(settings("apiKey")) : "";
            $tokboxSecret = !empty(settings("apiSecret")) ? libsodiumDecrypt(settings("apiSecret")) : "";
            $opentok = new OpenTok($tokboxKey, $tokboxSecret);
            $token = $opentok->generateToken($sessionId, array(
                'role'       => Role::MODERATOR,
                'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
                'data'       => 'name=Johnny',
                'initialLayoutClassList' => array('focus')
            ));
            $sessionId = str_replace('stop_', '', $sessionId);
            $captions = $opentok->stopCaptions($sessionId);
            $results = array('status' => 200, 'captionsId' => $captions['captionsId']);
        }elseif(stripos($sessionId, 'archivestart_') !== false){
            $sessionId = str_replace('archivestart_', '', $sessionId);
            $tokboxKey = !empty(settings("apiKey")) ? libsodiumDecrypt(settings("apiKey")) : "";
            $tokboxSecret = !empty(settings("apiSecret")) ? libsodiumDecrypt(settings("apiSecret")) : "";
            $opentok = new OpenTok($tokboxKey, $tokboxSecret);
            $token = $opentok->generateToken($sessionId, array(
                'role'       => Role::MODERATOR,
                'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
                'data'       => 'name=Johnny',
                'initialLayoutClassList' => array('focus')
            ));
            $archive = $opentok->startArchive($sessionId);
            if(!empty($archive)){
                $archiveId = $archive->id;
                $data = array(
                    'tokbox_archive_id' => $archiveId,
                );
                $this->appointmentModel->updateTable(array('tokboxsessionId' => $sessionId), $data);
            }
            $results = array('status' => 200, 'archiveId' => $archiveId);
        }else{
            $tokboxKey = !empty(settings("apiKey")) ? libsodiumDecrypt(settings("apiKey")) : "";
            $tokboxSecret = !empty(settings("apiSecret")) ? libsodiumDecrypt(settings("apiSecret")) : "";
            $opentok = new OpenTok($tokboxKey, $tokboxSecret);
            $token = $opentok->generateToken($sessionId, array(
                'role'       => Role::MODERATOR,
                'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
                'data'       => 'name=Johnny',
                'initialLayoutClassList' => array('focus')
            ));
            $captions = $opentok->startCaptions($sessionId,$token, 'en-US');
            if(!empty($captions['captionsId'])){
                $data = array(
                    'appointment_captions_id' => $captions['captionsId'],
                );
                $this->appointmentModel->updateTable(array('tokboxsessionId' => $sessionId), $data);

            }
            $results = array('status' => 200, 'captionsId' => $captions['captionsId']);
        }

        echo json_encode($results);
    }
    /*
     * Integration with itmedicalvetsolutions.com
     * */
    public function MedicalVetRequest($method, $url, $data = [])
    {
        $username = 'virtue_production_access';
        $password = 'xcdWe4Ot1d9KA4wd';
        $credentials = base64_encode("$username:$password");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic ' . $credentials,
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        curl_close($curl);
        return json_decode($response, true);
    }
    public function appointmentMedicalvet($appointmentId)
    {
        $return_link_base = "https://user.petcareclub.vet/virtue/main/talk-to-vet?data=";
        $medicalvet_base_url = "https://itmedicalvetsolutions.com/client/user/virtue/v2";
        $return_link = "";
        $MedicalVetPetId = "";
        $apptModal = new \App\Models\AppointmentModel;
        $appointmentDetails = $apptModal->getAppoinmentById($appointmentId);
        if($appointmentDetails){
            $appointmentDetails = $appointmentDetails[0];
            if(!empty($appointmentDetails['petcareclub_link'])){
                $return_link = $appointmentDetails['petcareclub_link'];
            }else{
                $user = $this->userModel->getUserDetails(session('user_id'));
                echo "<pre>"; print_r($user); echo "</pre>";
                $pets = $this->userModel->getPetsByPatientId(session('user_id'));
                if(empty($user['id_petcareclub'])){
                    //register user to Medicalvet
                    $registerData = [
                        "first_name" => $user['first_name'],
                        "last_name" => $user['last_name'],
                        "email" => $user['email'].'@textteo.com',
                        "dev_type" => "web",
                        "app_version" => "1.0.1",
                    ];

                    $response = $this->MedicalVetRequest('POST', "$medicalvet_base_url/register-user", $registerData);
                    if(!empty($response['data']['stringify_response'])){
                        $resp_array = json_decode($response['data']['stringify_response'], true);
                        if(!empty($resp_array['user']['id'])){
                            $this->userModel->updateTable(array('id' => session('user_id')), ['id_petcareclub' => $resp_array['user']['id']]);
                        }
                    }
                }
                foreach ($pets as $pet){
                    if($pet['id'] == $appointmentDetails['pet_id']){
                        if(empty($pet['id_petcareclub'])){
                            //register user to Medicalvet
                            $registerData = [
                                "name" => $pet['pet_name'],
                                "species" => $pet['pet_type'],
                                "sex" => $pet['gender'],
                                "weight" => $pet['weight'],
                                "email" => $user['email'].'@textteo.com',
                            ];

                            $response = $this->MedicalVetRequest('POST', "$medicalvet_base_url/pet/add", $registerData);
                            if(!empty($response['data']['pet'])){
                                $this->userModel->updatePetTable(array('id' => $pet['id']), ['id_petcareclub' => $response['data']['pet']['id']]);
                                $MedicalVetPetId = $response['data']['pet']['id'];
                            }
                        }else{
                            $MedicalVetPetId = $pet['id_petcareclub'];
                        }
                        break;
                    }
                }
                //create appointment in Medicalvet
                $appointmentData = [
                    "email" => $user['email'].'@textteo.com',
                    "dev_type" => "web",
                    "pet_id" => $MedicalVetPetId
                ];

                $result = $this->MedicalVetRequest('POST', "$medicalvet_base_url/talk-to-vet", $appointmentData);
                if(!empty($result['data']['stringify_response'])){
                    $return_link = $return_link_base.'%7B'.trim($result['data']['stringify_response'], '{}').'%7D';
                    $this->appointmentModel->updateTable(array('id' => $appointmentId), ['petcareclub_link' => $return_link]);
                }
            }
        }
        //redirect user to appointment
        if($return_link){
            header("Location: ".$return_link);
            die();
        }
        $results = array('status' => 200, 'captionsId' => 'online');
        echo json_encode($results);
    }

}
