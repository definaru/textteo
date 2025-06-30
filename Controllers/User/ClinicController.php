<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\HomeModel;
use App\Models\UserModel;
use App\Models\AppointmentModel;

class ClinicController extends BaseController
{
    public mixed $data;
    public mixed $timezone;
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
     * @var \App\Models\UserModel
     */
    public $userModel;
    /**
     * @var \App\Models\AppointmentModel
     */
    public $appointmentModel;

    public function __construct()
    {
        $this->data['theme'] = 'user';
        $this->data['module'] = 'clinic';
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

        $this->homeModel = new HomeModel();
        $this->commonModel = new CommonModel();
        $this->userModel = new UserModel();
        $this->appointmentModel = new AppointmentModel();
    }
    /**
     *  Doctor Dashboard Page 
     * 
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'doctor_dashboard';
        $user_id = session('user_id');
        $this->data['total_patient'] = $this->appointmentModel->getTotalPatient($user_id);
        $this->data['today_patient'] = $this->appointmentModel->getTodayPatient($user_id);
        $this->data['recent'] = $this->commonModel->countTblResult('lab_payments', array('lab_id' => $user_id));
        $this->data['recents'] = $this->appointmentModel->get_recent_booking($user_id);
        return view('user/doctor/doctorDashboard', $this->data);
    }
    /**
     *  Clinic profile Page 
     * 
     *
     * @return mixed
     */
    public function profileSettings()
    {
        $this->data['page'] = 'profile';
        $this->data['profile'] = $this->userModel->getUserDetails(session('user_id'));
        $this->data['user_detail'] = $this->userModel->getUserDetails(session('user_id'));
        $where = ['user_id' => session('user_id'), 'status' => 1];
        $this->data['clinic_images'] = $this->commonModel->getTblResultOfData('clinic_images', $where, 'clinic_image,id,user_id');
        return view('user/clinic/clinicProfile', $this->data);
    }
    /**
     *  Social media Page 
     * 
     *
     * @return mixed
     */
    public function socialMedia()
    {
        $this->data['page'] = 'social-media';
        $this->data['profile'] = $this->userModel->getUserDetails(session('user_id'));
        $where = ['doctor_id' => session('user_id')];
        $this->data['social_media'] = $this->commonModel->getTblRowOfData('social_media', $where, '*');
        return view('user/layout/socialMedia', $this->data);
    }
    /**
     *  Clinic Doctor Social Media Link Update
     * 
     *
     * @return mixed
     */
    public function updateSocialMedia()
    {

        $id = session('user_id');

        $inputdata['facebook'] = $this->request->getPost('facebook');
        $inputdata['twitter'] = $this->request->getPost('twitter');
        $inputdata['instagram'] = $this->request->getPost('instagram');
        $inputdata['pinterest'] = $this->request->getPost('pinterest');
        $inputdata['linkedin'] = $this->request->getPost('linkedin');
        $inputdata['youtube'] = $this->request->getPost('youtube');
        $inputdata['doctor_id'] = $id;
        $inputdata['updated_at'] = date('Y-m-d H:i:s');

        //checking data exist or not
        $detailExist = $this->commonModel->checkTblDataExist('social_media', ['doctor_id' => $id], 'id');
        if ($detailExist) {
            // if yes data update
            $result = $this->commonModel->updateData('social_media', ['doctor_id' => $id], $inputdata);
        } else {
            // if no new row insert
            $result = $this->commonModel->insertData('social_media', $inputdata);
        }

        if ($result == true) {
            $response['msg'] = $this->language['lg_profile_success_up_sm'];
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_profile_success_up_sm_not'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }
    /**
     *  Clinic Profile Update
     * 
     *
     * @return mixed
     */
    public function updateProfile()
    {
        $id = session('user_id');
        $hospital_id = user_detail(session('user_id'))['hospital_id'];
        if (session('role') == '6') {
            $inputdata['first_name'] = libsodiumEncrypt($this->request->getPost('clinic_name'));
            $inputdata['last_name'] = '';
            $userdata['gender'] = '';
            $userdata['dob'] = '';
            $inputdata['username'] = libsodiumEncrypt(generate_username($this->request->getPost('clinic_name') . ' ' . $inputdata['last_name'] . ' '));
        } else {
            $inputdata['first_name'] = libsodiumEncrypt($this->request->getPost('first_name'));
            $inputdata['last_name'] = libsodiumEncrypt($this->request->getPost('last_name'));
            $userdata['gender'] = $this->request->getPost('gender');
            $userdata['dob'] = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->getPost('dob') ?? "")));
        }

        $userdata['price_type'] = $this->request->getPost('price_type') ?? "";
        $userdata['amount'] = $this->request->getPost('amount') ?? "";
        $inputdata['mobileno'] = libsodiumEncrypt($this->request->getPost('mobileno'));
        $inputdata['country_code'] = $this->request->getPost('country_code');
        $inputdata['country_id'] = $this->request->getPost('country_id');
        $inputdata['is_updated'] = 1;

        /**
         * Test Hide
         */
        // $inputdata['is_verified'] = 1;

        $userdata['user_id'] = $id;
        $userdata['biography'] = libsodiumEncrypt($this->request->getPost('biography'));
        $userdata['clinic_name'] = libsodiumEncrypt($this->request->getPost('clinic_name'));

        if (session('role') == 1 && ($hospital_id == 0 || $hospital_id == "" || $hospital_id == null)) {
            $userdata['clinic_address'] = libsodiumEncrypt($this->request->getPost('clinic_address'));
            $userdata['clinic_address2'] = libsodiumEncrypt($this->request->getPost('clinic_address2'));
            $userdata['clinic_city'] = libsodiumEncrypt($this->request->getPost('clinic_city'));
            $userdata['clinic_state'] = libsodiumEncrypt($this->request->getPost('clinic_state'));
            $userdata['clinic_country'] = libsodiumEncrypt($this->request->getPost('clinic_country'));
            $userdata['clinic_postal'] = libsodiumEncrypt($this->request->getPost('clinic_postal'));
        }
        $userdata['address1'] = libsodiumEncrypt($this->request->getPost('address1'));
        $userdata['address2'] = libsodiumEncrypt($this->request->getPost('address2'));
        $userdata['country'] = $this->request->getPost('country');
        $userdata['state'] = $this->request->getPost('state');
        $userdata['city'] = $this->request->getPost('city');
        $userdata['postal_code'] = libsodiumEncrypt($this->request->getPost('postal_code'));
        $userdata['services'] = $this->request->getPost('services') ? $this->request->getPost('services') : "";
        if($this->request->getPost('specialization') && $this->request->getPost('specialization') != 'others'){
            $userdata['specialization'] =  $this->request->getPost('specialization');
        }elseif($this->request->getPost('other_specialization')){
            $result = $this->commonModel->insertData('specialization', [
                'specialization' => libsodiumEncrypt($this->request->getPost('other_specialization')),
                'status' => 1,
                'sequence' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            if($result){
                $userdata['specialization'] = $result['id'];
            }else{
                $userdata['specialization']= "";
            }
        }else{
            $userdata['specialization'] =  "";
        }
        $userdata['update_at'] = date('Y-m-d H:i:s');

        $this->commonModel->updateData('users', ['id' => $id], $inputdata);
        $userDetailExist = $this->commonModel->checkTblDataExist('users_details', ['user_id' => $id], 'id');
        if ($userDetailExist) {
            $result = $this->commonModel->updateData('users_details', ['user_id' => $id], $userdata);
        } else {
            $result = $this->commonModel->insertData('users_details', $userdata);
        }

        if ($result == true) {
            if (session('role') == 1) {
                if (!empty($this->request->getPost('degree'))) {
                    $where = array('user_id' => $id);
                    $this->commonModel->deleteData('education_details', $where);

                    $degree = array_filter($this->request->getPost('degree'));
                    $institute = array_filter($this->request->getPost('institute'));
                    $year_of_completion = array_filter($this->request->getPost('year_of_completion'));

                    for ($i = 0; $i < count($degree); $i++) {
                        $edudata = array(
                            'user_id' => $id,
                            'degree' => $degree[$i],
                            'institute' => $institute[$i],
                            'year_of_completion' => $year_of_completion[$i]
                        );
                        $this->commonModel->insertData('education_details', $edudata);
                    }
                }

                if (!empty($this->request->getPost('hospital_name'))) {
                    $where = array('user_id' => $id);
                    $this->commonModel->deleteData('experience_details', $where);

                    $hospital_name = array_filter($this->request->getPost('hospital_name'));
                    $from = array_filter($this->request->getPost('from'));
                    $to = array_filter($this->request->getPost('to'));
                    $designation = array_filter($this->request->getPost('designation'));

                    for ($j = 0; $j < count($hospital_name); $j++) {
                        $expdata = array(
                            'user_id' => $id,
                            'hospital_name' => $hospital_name[$j],
                            'from' => $from[$j],
                            'to' => $to[$j],
                            'designation' => $designation[$j]
                        );
                        $this->commonModel->insertData('experience_details', $expdata);
                    }
                }

                if (!empty($this->request->getPost('awards'))) {
                    $where = array('user_id' => $id);
                    $this->commonModel->deleteData('awards_details', $where);

                    $awards = array_filter($this->request->getPost('awards'));
                    $awards_year = array_filter($this->request->getPost('awards_year'));

                    for ($k = 0; $k < count($awards); $k++) {
                        $awadata = array(
                            'user_id' => $id,
                            'awards' => $awards[$k],
                            'awards_year' => $awards_year[$k]
                        );
                        $this->commonModel->insertData('awards_details', $awadata);
                    }
                }

                if (!empty($this->request->getPost('memberships'))) {
                    $where = array('user_id' => $id);
                    $this->commonModel->deleteData('memberships_details', $where);

                    $memberships = array_filter($this->request->getPost('memberships'));
                    for ($l = 0; $l < count($memberships); $l++) {
                        $memdata = array(
                            'user_id' => $id,
                            'memberships' => $memberships[$l]
                        );
                        $this->commonModel->insertData('memberships_details', $memdata);
                    }
                }

                if (!empty($this->request->getPost('registrations'))) {
                    $where = array('user_id' => $id);
                    $this->commonModel->deleteData('registrations_details', $where);

                    $registrations = array_filter($this->request->getPost('registrations'));
                    $registrations_year = array_filter($this->request->getPost('registrations_year'));

                    for ($m = 0; $m < count($registrations); $m++) {
                        $regdata = array(
                            'user_id' => $id,
                            'registrations' => $registrations[$m],
                            'registrations_year' => $registrations_year[$m]
                        );
                        $this->commonModel->insertData('registrations_details', $regdata);
                    }
                }

                if (!empty($this->request->getPost('availability'))) {
                    $where = array('user_id' => $id);
                    $this->commonModel->deleteData('business_hours', $where);

                    $business_hours_array = array();

                    if (!empty($_POST['availability'][0]['day'])) {
                        $from = $_POST['availability'][0]['from_time'];
                        $to = $_POST['availability'][0]['to_time'];
                        for ($i = 1; $i <= 7; $i++) {
                            $business_hours_array[$i] = array('day' => $i, 'from_time' => $from, 'to_time' => $to);
                        }
                    } else {
                        if (!empty($_POST['availability'][0])) {
                            unset($_POST['availability'][0]);
                        }
                        $business_hours_array = array_map('array_filter', $_POST['availability']);
                        $business_hours_array = array_filter($business_hours_array);
                    }
                    if (!empty($business_hours_array)) {
                        $business_hours_array = array_values($business_hours_array);
                    }

                    $business_hours_data = array(
                        'business_hours' => json_encode($business_hours_array),
                        'user_id' => $id
                    );
                    $this->commonModel->insertData('business_hours', $business_hours_data);
                }
            }

            $response['msg'] = $this->language['lg_profile_success'];
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_profile_update_'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }
    /**
     *  Crop Profile Image.
     * 
     *
     * @return mixed
     */
    public function cropProfileImg()
    {
        helper('file');

        $max_execution_time = 3000;
        ini_set('max_execution_time', $max_execution_time);
        ini_set('memory_limit', '-1');
        $prevImg = $this->request->getPost('prev_img');
        $av_data = json_decode($this->request->getPost('avatar_data') ?? "", true);

        $file  = $this->request->getFile('avatar_file');
        $imgName = $file->getRandomName();
        $src2  = WRITEPATH . 'uploads/profileimage/temp/' . $imgName;
        $file->move(WRITEPATH . 'uploads/profileimage/temp/', $imgName);


        $this->resizeImage($imgName, $av_data['width'], $av_data['height'], $av_data['x'], $av_data['y']);

        $inputdata = array();
        $inputdata['profileimage'] = 'uploads/profileimage/' . $imgName;
        $id = session('user_id');
        if ($this->commonModel->updateData('users', ['id' => $id], $inputdata)) {
            if (!empty($prevImg)) {
                $file_path = FCPATH . $prevImg;
                if (is_file($file_path)) {
                    unlink(FCPATH . $prevImg);
                }
            }
            unlink($src2);
        }

        $response = array(
            'state'  => 200,
            'message' => '',
            'result' => 'uploads/profileimage/' . $imgName,
            'img_name1' => $imgName
        );
        echo json_encode($response);
    }
    /**
     *  Resize Image.
     * 
     * @param string $fileName
     * @param int $width
     * @param int $height
     * @param int $x
     * @param int $y
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
     * Upload Clinic Image.
     * 
     *
     * @return mixed
     */
    public function uploadClinicImg()
    {
        try {
            $user_id = session('user_id');
            $path = ROOTPATH . "public/uploads/clinic_uploads/" . $user_id;
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $target_file = $path . basename($_FILES["file"]["name"]);
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            $file = $this->request->getFile('file');
            $fname = $file->getRandomName();
            $src2 = "uploads/clinic_uploads/" . $user_id . "/" . $fname;
            // $file->move($path, $fname);
            if ($file->move($path, $fname)) {
                $data = array(
                    'user_id' => $user_id,
                    'clinic_image' => $src2,
                    'token' => rand(),
                    'status' => '1'
                );
                $this->commonModel->insertData('clinic_images', $data);
            } else {
                echo  json_encode(array('error' => "error on file upload"));
            }
        } catch (\Exception $e) {
            echo '<script>toastr.error(' . $e->getMessage() . ');</script>';
        }
    }
    /**
     * Get Single Doctor.
     * 
     *
     * @return mixed
     */
    public function doctorSingle()
    {
        $doc_id = $this->request->getPost('doc_id');

        $list = $this->commonModel->getTblRowOfData('users', ['id' => $doc_id], 'id as id,first_name as name,first_name,last_name ,email,country_code, mobileno as mobile,profileimage as profile,username as  username,is_verified,is_updated');
        $result['first_name'] = libsodiumDecrypt($list['first_name']);
        $result['last_name'] = libsodiumDecrypt($list['last_name']);
        $result['email'] = libsodiumDecrypt($list['email']);
        $result['mobile'] = libsodiumDecrypt($list['mobile']);
        $result['country_code'] = $list['country_code'];
        echo json_encode($result);
    }

    /**
     * Clinic Doctor Design
     * 
     *
     * @return mixed
     */
    public function doctor()
    {
        $this->data['page'] = 'doctorList';
        $this->data['profile'] = $this->userModel->getUserDetails(session('user_id'));
        $this->data['user_detail'] = $this->userModel->getUserDetails(session('user_id'));
        $where = array('user_id' => session('user_id'), 'status' => 1);
        $this->data['clinic_images'] = $this->commonModel->getTblResultOfData('clinic_images', $where, 'clinic_image,id,user_id');
        return view('user/clinic/doctorList', $this->data);
    }

    /**
     * Clinic Doctor List
     * 
     *
     * @return mixed
     */
    public function doctorList()
    {
        $user_id = session('user_id');

        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];

        $list = $this->commonModel->getDoctorList($start, $length, libsodiumEncrypt($search));
        // $list = $this->commonModel->getTblResultOfData('users',['hospital_id'=>$user_id],'id as id,first_name as name,first_name,last_name ,email,country_code, mobileno as mobile,profileimage as profile,username as  username,is_verified,is_updated');
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $doctor) {
            // $profile_image=(!empty($doctor['profile']))?base_url().$doctor['profile']:base_url().'assets/img/user.png';

            if ($doctor['profile'] == "" || ($doctor['profile'] != "" && !is_file($doctor['profile']))) {
                $profile_image = base_url() . 'assets/img/user.png';
            } else {
                $profile_image = (!empty($doctor['profile'] ?? "")) ? base_url() . $doctor['profile'] ?? "" : base_url() . 'assets/img/user.png';
            }

            $row = array();
            $row[] = $a + $_POST['start'];
            $row[] = '<h2 class="table-avatar">
            <a href="#" class="avatar avatar-sm mr-2">
                <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
            </a>
            <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($doctor['username'])) . '">' . $this->language['lg_dr'] . ' ' . libsodiumDecrypt($doctor['name']) . ' </a>
            </h2>
            ';
            $html = '<div class="table-action">
            <a  href="javascript:void(0);" onclick="edit_doctor(' . $doctor['id'] . ')"  class="btn btn-sm bg-primary-light">
            <i class="fas fa-edit"></i> Edit
            </a>
            <a href="javascript:void(0);" onclick="delete_doctor(' . $doctor['id'] . ')" class="btn btn-sm bg-info-light">
            <i class="far fa-trash-alt"></i> Delete
            </a></div>';

            $row[] = libsodiumDecrypt($doctor['email']);

            $row[] = ($doctor['is_updated'] == 1) ? 'Yes' : 'No';

            $row[] = ($doctor['is_verified'] == 1) ? 'Yes' : 'No';

            $row[] = $html;

            $data[] = $row;
            $a++;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->commonModel->getRowCount(''),
            "recordsFiltered" => $this->commonModel->getRowCount($search),
            "data" => $data,
        );
        echo json_encode($output);
    }
    /**
     *  Add Doctor 
     *
     * @return mixed
     */
    public function doctorAdd()
    {
        $inputdata = array();
        $response = array();
        $user_id = session('user_id');
        $inputdata['first_name'] = libsodiumEncrypt(ucfirst($this->request->getPost('first_name') ?? ""));
        $inputdata['last_name'] = libsodiumEncrypt(ucfirst($this->request->getPost('last_name') ?? ""));
        $inputdata['email'] = libsodiumEncrypt(strtolower(trim($this->request->getPost('email') ?? "")));
        $inputdata['mobileno'] = libsodiumEncrypt($this->request->getPost('mobileno'));
        $inputdata['country_code'] = $this->request->getPost('country_code');
        $inputdata['country_id'] = $this->request->getPost('country_id');
        $inputdata['username'] = libsodiumEncrypt(generate_username($inputdata['first_name'] . ' ' . $inputdata['last_name'] . ' ' . $inputdata['mobileno']));
        $inputdata['role'] = $this->request->getPost('role');
        $inputdata['hospital_id'] = $user_id;
        $inputdata['role'] = $this->request->getPost('role');
        $inputdata['status'] = 1;
        $inputdata['password'] = md5($this->request->getPost('password') ?? "");
        $inputdata['confirm_password'] = md5($this->request->getPost('confirm_password') ?? "");
        $inputdata['created_date'] = date('Y-m-d H:i:s');

        if ($this->request->getPost('user_id') != "") {
            $result = $this->commonModel->updateData('users', ['id' => $this->request->getPost('user_id')], $inputdata);
            $response['msg'] = "Updated Successfully";
            $response['status'] = 200;
        } else {
            $result = $this->commonModel->insertData('users', $inputdata);
        }

        if ($result == true) {
            if ($this->request->getPost('user_id') == "") {
                $inputdata['id'] = $result['id'];
                $sendmail = new \App\Libraries\SendEmail;
                $sendmail->send_email_verification($inputdata);
            }
            $response['msg'] = $this->language['lg_registration_su'];
            $response['status'] = 200;
            session()->setFlashdata('success_message', $this->language['lg_registration_su']);
        } else {
            $response['msg'] = $this->language['lg_registration_fa'];
            $response['status'] = 500;
            session()->setFlashdata('failed_message', $this->language['lg_registration_fa']);
        }
        echo json_encode($response);
    }

    /**
     * Calender View
     * 
     * 
     *  @return mixed
     */
    public function calendarView()
    {
        $this->data['module'] = 'calendar';
        $this->data['page'] = 'calendar';
        return view('user/layout/calender', $this->data);
    }
    /**
     * Calander List
     * 
     *  @return mixed
     */
    public function calendarList()
    {
        $id = session('user_id');
        $role = session('role');

        $result = $this->homeModel->calendarView($id, $role);

        foreach ($result as $record) {

            $from_date_time =  $record['appointment_date'] . ' ' . $record['appointment_time'];
            $to_date_time =  $record['appointment_date'] . ' ' . $record['appointment_end_time'];
            $from_timezone = $record['time_zone'];

            $to_timezone = session('time_zone');



            $from_date_time  = converToTz($from_date_time, $to_timezone, $from_timezone);
            $to_date_time  = converToTz($to_date_time, $to_timezone, $from_timezone);

            $from_time  = date('h:i a', strtotime($from_date_time));
            $to_time  = date('h:i a', strtotime($to_date_time));



            $start_time = date('g:i a', strtotime($from_time));
            $end_time = date('g:i a', strtotime($to_time));

            $title = libsodiumDecrypt($record['first_name']) . ' ' . libsodiumDecrypt($record['last_name']);

            $timestamp1 = strtotime($record['appointment_time']);
            $timestamp2 = strtotime($record['appointment_end_time']);
            $curdate = date('Y-m-d');
            $dateval = $record['appointment_date'];
            $cur_time = date('H:i');
            $curtime = strtotime($cur_time);


            // setting color here
            if ($record['approved'] == 0 && date('Y-m-d') > $record['appointment_date']) {
                $color = '#d9534f'; // Cancelled
            } else {
                $color = '#5bc0de'; // Pending
            }
            if ($record['approved'] == 1) {
                $color = '#5cb85c';  // Approved
            }

            if ($record['approved'] == 2 || $record['approved'] == 0) {
                $color = '#ff0100'; // Cancelled
            }

            if ($record['approved'] == 1 && $record['appointment_status'] == 1 && $record['call_status'] == 0) {
                if ((strtotime($curdate) == strtotime($dateval) || strtotime($dateval) <= strtotime($curdate)) && $timestamp2 < $curtime) {
                    $color = '#f4a460'; // Missed
                } else {
                    $color = '#5bc0de'; // Upcoming Booking
                }
            }
            if ($record['approved'] == 1 && $record['appointment_status'] == 0 && $record['call_status'] == 1) {
                $color = '#09e5ab'; // Booking not yet set as completed and no reviews by patient.

            }
            if ($record['approved'] == 1 && $record['appointment_status'] == 1 && $record['call_status'] == 1) {
                $color = '#008000'; // Completed
            }


            $event_array[] = array(
                'id' => $record['id'],
                'user_id' => $record['appointment_to'],
                'title' => $title,
                'start' =>  $from_date_time,
                'end' => $to_date_time,
                'color' => $color,
                'timezone' => $from_timezone
            );
        }


        if (!empty($event_array)) {
            // $this->session->set_userdata(array('search_id'=>''));
            echo json_encode($event_array);
        }
    }
    /**
     * Clinic Doctor Appointment Page
     * 
     *  @return mixed
     */
    public function appointmentPage()
    {
        $this->data['module'] = 'doctor';
        $this->data['page'] = 'appoinments';
        return view('user/patient/appointmentList', $this->data);
    }
}
