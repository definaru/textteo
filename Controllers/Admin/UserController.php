<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CommonModel;


class UserController extends BaseController
{
    public $data;
    public $session;
    public mixed $db;
    public mixed $signin;
    /**
     * @var \App\Models\UserModel
     */
    public $userModel;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    public $language;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'users';
        $this->data['page'] = '';

        $lan = helper('default_language');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->userModel = new UserModel();
        $this->commonModel = new CommonModel();
    }

    /**
     * load doctor list page.
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'doctors';
        echo view('admin/users/doctors', $this->data);
    }

    /**
     * Fetch doctor list data.
     *
     * @return mixed
     */
    public function doctorsList()
    {
        $list = $this->userModel->getDoctorDatatables($this->request->getPost());
        $data = array();
        $no = $this->request->getPost('start');
        $a = 1;

        foreach ($list as $doctor) {

            $val = '';

            if ($doctor['status'] == '1') {
                $val = 'checked';
            }
            $profileimage = base_url() . 'assets/img/user.png';

            if (!empty($doctor['profileimage']) && file_exists($doctor['profileimage'])) {
                $profileimage = base_url() . $doctor['profileimage'];
            }

            if ($doctor['hospital_id'] == 0) {
                $clinic_doctor = '';
            } else {
                $clinic_doctor = 'Clinic Doctor';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '#D00' . $doctor['id'];
            $row[] = '<h2 class="table-avatar">
                    <a target="_blank" href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($doctor['username'])) . '" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image"></a>
                    <a target="_blank" href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($doctor['username'])) . '">Dr. ' . ucfirst(libsodiumDecrypt($doctor['first_name']) . ' ' . libsodiumDecrypt($doctor['last_name'])) . ' <span>' . $clinic_doctor . '</span></a>
                    </h2>';
            $row[] = ucfirst(libsodiumDecrypt($doctor['specialization']));
            $row[] = libsodiumDecrypt($doctor['email']);
            $row[] = libsodiumDecrypt($doctor['mobileno']);
            $row[] = date('d M Y', strtotime($doctor['created_date'])) . '<br><small>' . date('h:i A', strtotime($doctor['created_date'])) . '</small>';
            $row[] = get_earned($doctor['id']);
            $row[] = '<div class="status-toggle">
                        <input type="checkbox" onchange="change_usersStatus(' . $doctor['id'] . ')" id="status_' . $doctor['id'] . '" class="check" ' . $val . '>
                        <label for="status_' . $doctor['id'] . '" class="checktoggle">checkbox</label>
                    </div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsFiltered" => $this->userModel->doctorCountAll(),
            "recordsTotal" => $this->userModel->doctorCountAll(),
            // "recordsTotal" => count($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    /**
     * Change Doctor status.
     *
     * @return mixed
     */
    public function changeUsersStatus()
    {

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $data = array(
            'status' => $status,
        );
        $this->userModel->updateTable(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }
    /**
     * Check email.
     *
     * @return mixed
     */
    public function checkEmail()
    {
        $email = $this->request->getPost('email');
        $result = $this->userModel->checkEmail($email);
        $res_id = $result['id'];
        if ($res_id > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    /**
     * Signup.
     *
     * @return mixed
     */
    public function signup()
    {
        $inputdata = array();
        $response = array();
        $result = "";

        //print_r($_POST);  exit;
        if ($this->request->getPost('role') == 6) {
            $inputdata['first_name'] = libsodiumEncrypt($this->request->getPost('clinic_name'));
            $inputdata['last_name'] = '';
        } else {
            $inputdata['first_name'] = libsodiumEncrypt($this->request->getPost('first_name'));
            $inputdata['last_name'] = libsodiumEncrypt($this->request->getPost('last_name'));
        }
        $inputdata['email'] = libsodiumEncrypt($this->request->getPost('email'));
        $inputdata['mobileno'] = libsodiumEncrypt($this->request->getPost('mobileno'));
        $inputdata['country_code'] = $this->request->getPost('country_code');
        $inputdata['username'] = libsodiumEncrypt(generate_username($inputdata['first_name'] . ' ' . $inputdata['last_name'] . ' ' . $inputdata['mobileno']));
        $inputdata['role'] = $this->request->getPost('role');
        $inputdata['password'] = md5($this->request->getPost('password') ?? "");
        $inputdata['confirm_password'] = md5($this->request->getPost('confirm_password') ?? "");
        $inputdata['created_date'] = date('Y-m-d H:i:s');
        // $inputdata['is_verified'] = 1;
        /*if($this->request->getPost('pharmacy_name'))
            $inputdata['pharmacy_name'] = $this->request->getPost('pharmacy_name');*/
        if ($this->request->getPost('id') == null) {

            $where_data_email = array('email' => $inputdata['email']);
            $already_exits = $this->commonModel->countTblResult('users', $where_data_email);

            $where_data_mobile = array('mobileno' => $inputdata['mobileno']);
            $already_exits_mobile_no = $this->commonModel->countTblResult('users', $where_data_mobile);
        } else {
            $where_data_email = array('email' => $inputdata['email'], 'id !=' => $this->request->getPost('id'));
            $already_exits = $this->commonModel->countTblResult('users', $where_data_email);

            $where_data_mobile = array('mobileno' => $inputdata['mobileno'], 'id !=' => $this->request->getPost('id'));
            $already_exits_mobile_no = $already_exits = $this->commonModel->countTblResult('users', $where_data_mobile);
        }
        if ($already_exits >= 1) {
            $response['msg'] = 'Email already exits';
            $response['status'] = 500;
        } else if ($already_exits_mobile_no >= 1) {
            $response['msg'] = 'Mobile no already exits';
            $response['status'] = 500;
        } else {

            if ($inputdata['role'] == 5) {
                $inputdata['pharmacy_user_type'] = 1;

                $get_pharmacy_details = $this->db->select('*')->from('users')->where('pharmacy_user_type', 1)->get()->result_array();

                if (isset($get_pharmacy_details) && !empty($get_pharmacy_details)) {
                    // print_r($get_pharmacy_details); exit;
                    $phar_id = $get_pharmacy_details[0]['id'];
                    $this->db->update('users', $inputdata, array('id' => $phar_id));
                } else {
                    $result = $this->signin->signup($inputdata);
                }
            } else {

                // Without Pharmacy
                //echo "New";
                if ($this->request->getPost('id') == null) {
                    $users = $this->userModel->insertData('users', $inputdata);
                    $lastInsertID = $this->userModel->insertID();
                    // $insert_id = $this->db->insertID();
                    $userdata['clinic_name'] = libsodiumEncrypt($this->request->getPost('clinic_name'));
                    $userdata['user_id'] = $lastInsertID;
                    $this->userModel->insertData('users_details', $userdata);
                    $result = true;
                } else {
                    $input_update_data['email'] = libsodiumEncrypt($this->request->getPost('email'));
                    $input_update_data['mobileno'] = libsodiumEncrypt($this->request->getPost('mobileno'));
                    $input_update_data['country_code'] = $this->request->getPost('country_code');
                    $input_update_data['first_name'] = libsodiumEncrypt($this->request->getPost('clinic_name'));
                    $result = $this->commonModel->updateData('users', array('id' => $this->request->getPost('id')), $input_update_data);
                }
            }




            if ($inputdata['role'] === 5) {
                $pharmacy_id = $this->db->insert_id();
                $home_delivery = $this->request->getPost('home_delivery');
                $pharmacy_opens_at = $this->request->getPost('pharmacy_opens_at');
                $hrsopen = $this->request->getPost('hrsopen');
                $pharmacydata = array(
                    'home_delivery' => (!empty($home_delivery)) ? $home_delivery : 'no',
                    'pharamcy_opens_at' => (!empty($pharmacy_opens_at)) ? $pharmacy_opens_at : '00:00:00',
                    '24hrsopen' => (!empty($hrsopen)) ? $hrsopen : 'no',
                    'pharmacy_id' => $pharmacy_id
                );
                // insert query




                $this->db->insert('pharmacy_specifications', $pharmacydata);
            }

            if ($result == true) {
                $response['msg'] = 'Registration success';
                $response['status'] = 200;
            } else {
                $response['msg'] = 'Registration failed';
                $response['status'] = 500;
            }
        }

        echo json_encode($response);
    }
    /**
     * Load Patients Page.
     *
     * @return mixed
     */
    public function patients()
    {
        $this->data['page'] = 'patients';
        echo view('admin/users/patients', $this->data);
    }
    /**
     * Get Patients List.
     *
     * @return mixed
     */
    public function patientsList()
    {
        $list = $this->userModel->getPatientDatatables($this->request->getPost());
        $data = array();
        $no = $this->request->getPost()['start'];
        $a = 1;

        foreach ($list as $patient) {

            $val = '';

            if ($patient['status'] == '1') {
                $val = 'checked';
            }
            $profileimage = base_url() . 'assets/img/user.png';

            if (!empty($patient['profileimage']) && file_exists($patient['profileimage'])) {
                $profileimage = base_url() . $patient['profileimage'];
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '#PT00' . $patient['id'];
            $row[] = '<h2 class="table-avatar">
                        <a target="_blank" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image"></a>
                        <a target="_blank" >' . (libsodiumDecrypt($patient['first_name']) . ' ' . libsodiumDecrypt($patient['last_name'])) . '</a>
                    </h2>';
            $row[] = (!empty($patient['dob'])) ? age_calculate($patient['dob']) : '';
            $row[] = libsodiumDecrypt($patient['blood_group']);
            $row[] = libsodiumDecrypt($patient['email']);
            $row[] = libsodiumDecrypt($patient['mobileno']);
            $row[] = date('d M Y', strtotime($patient['created_date'])) . '<br><small>' . date('h:i A', strtotime($patient['created_date'])) . '</small>';
            $row[] = '<div class="status-toggle">
                        <input type="checkbox" onchange="change_usersStatus(' . $patient['id'] . ')" id="status_' . $patient['id'] . '" class="check" ' . $val . '>
                        <label for="status_' . $patient['id'] . '" class="checktoggle">checkbox</label>
                        </div>';

            if (isset($patient['last_vist'])) {

                $row[] = date('d M Y', strtotime($patient['last_vist']));
            } else {
                $row[] = "";
            }

            $org_amount = 0;
            $currency_option = default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);
            if ($patient['last_paid']) {
                $org_amount = get_doccure_currency($patient['last_paid'], $patient['currency_code'], default_currency_code());
            }

            $row[] = $rate_symbol . $org_amount;


            $data[] = $row;
        }
        $output = array(
            "draw" => $this->request->getPost()['draw'],
            "recordsFiltered" => $this->userModel->patient_count_all(),
            "recordsTotal" => $this->userModel->patient_count_all(),
            // "recordsTotal" => count($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Load Clinic Page.
     *
     * @return mixed
     */
    public function clinic()
    {
        $this->data['page'] = 'clinic';
        echo view('admin/users/clinic', $this->data);
    }
    /**
     * Get Clinic List.
     *
     * @return mixed
     */
    public function clinicList()
    {
        $list = $this->userModel->getClinicDatatables($this->request->getPost());
        $data = array();
        $no = $this->request->getPost()['start'];
        $a = 1;

        foreach ($list as $clinic) {

            $val = '';

            if ($clinic['status'] == '1') {
                $val = 'checked';
            }
            $profileimage = base_url() . 'assets/img/user.png';

            if (!empty($clinic['profileimage']) && file_exists($clinic['profileimage'])) {
                $profileimage = base_url() . $clinic['profileimage'];
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '#C00' . $clinic['id'];
            $clinic_name = $clinic['clinic_name'] == "" ? libsodiumDecrypt($clinic['first_name']) . ' ' . libsodiumDecrypt($clinic['last_name']) : libsodiumDecrypt($clinic['clinic_name']);
            $row[] = '<h2 class="table-avatar">
                        <a href="javascript:void(0)" class="avatar avatar-sm mr-2" onclick="show_clinic_doctors(' . $clinic['id'] . ')" ><img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image"></a>
                        <a href="javascript:void(0)" onclick="show_clinic_doctors(' . $clinic['id'] . ')">' . ($clinic_name) . '</a>
                    </h2>';
            $row[] = ucfirst(libsodiumDecrypt($clinic['specialization']));
            $row[] = libsodiumDecrypt($clinic['email']);
            $row[] = libsodiumDecrypt($clinic['mobileno']);
            $row[] = date('d M Y', strtotime($clinic['created_date'])) . '<br><small>' . date('h:i A', strtotime($clinic['created_date'])) . '</small>';
            $row[] = get_earned($clinic['id']);
            $row[] = '<div class="status-toggle">
                        <input type="checkbox" onchange="change_usersStatus(' . $clinic['id'] . ')" id="status_' . $clinic['id'] . '" class="check" ' . $val . '>
                        <label for="status_' . $clinic['id'] . '" class="checktoggle">checkbox</label>
                        </div>';
            $docLink = encryptor_decryptor('encrypt', libsodiumDecrypt($clinic['username']));
            $row[] = '<h2 class="table-avatar">
                        <a target="_blank" href="' . base_url() . 'doctor-preview/' . $docLink . '" class="btn btn-primary">VIEW</a>
                    </h2>';
            $row[] = '<div class="actions">
                    <a class="btn btn-sm bg-success-light" onclick="edit_clinic(' . $clinic['id'] . ')" href="javascript:void(0)">
                        <i class="fe fe-pencil"></i> Edit
                    </a>
                    <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_clinic(' . $clinic['id'] . ')">
                        <i class="fe fe-trash"></i> Delete
                    </a>
                    </div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $this->request->getPost()['draw'],
            "recordsFiltered" => $this->userModel->clinicCountAll(),
            "recordsTotal" => count($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Clinic Delete.
     *
     * @param int $id
     * @return mixed
     */
    public function clinicDelete($id)
    {
        $data = array(
            'status' => 0,
        );
        $this->commonModel->updateData('users', array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }
    /**
     * Clinic Edit.
     *
     * @param int $id
     * @return mixed
     */
    public function clinicEdit($id)
    {
        $clinic_name = '';
        $data = $this->userModel->getById($id);
        $result = $this->commonModel->countTblResult('users_details', array('user_id' => $id));

        if ($result > 0)
            $clinic_name = libsodiumDecrypt($this->userModel->getClinicName($id));

        $result = array(
            'id' => $data->id,
            'email' => libsodiumDecrypt($data->email),
            'mobileno' => libsodiumDecrypt($data->mobileno),
            'country_code' => $data->country_code,
            'first_name' => libsodiumDecrypt($data->first_name),
            'last_name' => libsodiumDecrypt($data->last_name),
        );
        $result['clinic'] = $clinic_name;

        echo json_encode($result);
    }
    /**
     * Get Clinic Doctor.
     *
     * @param int $id
     * @return mixed
     */
    public function getClinicDoctors($id)
    {
        $list = $this->userModel->getClinicDoctorDatatables($id, $this->request->getPost());
        $data = array();
        $no = $this->request->getPost('start');
        $a = 1;
        foreach ($list as $doctor) {

            $val = '';

            if ($doctor['status'] == '1') {
                $val = 'checked';
            }

            $profileimage = (!empty($doctor['profileimage'])) ? base_url() . $doctor['profileimage'] : base_url() . 'assets/img/user.png';

            $no++;
            $row = array();
            $row[] = '<h2 class="table-avatar">
            <a href="#" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image"></a>
            <a href="#">Dr. ' . ucfirst(libsodiumDecrypt($doctor['first_name']) . ' ' . libsodiumDecrypt($doctor['last_name'])) . '</a>
        </h2>';
            // $row[] = '<h2 class="table-avatar">
            //             <img class="avatar-img avatar-sm rounded-circle mr-2" src="'.$profileimage.'" alt="User Image"> Dr. '.ucfirst($doctor['first_nam']).'
            //           </h2>';
            $row[] = ucfirst(libsodiumDecrypt($doctor['specialization']));
            // $row[] = '<div class="status-toggle">
            //               <input type="checkbox" onchange="change_clinic_doctor_status('.$doctor['clinic_doctor_id'].')" id="status_'.$doctor['clinic_doctor_id'].'" class="check" '.$val.'>
            //               <label for="status_'.$doctor['clinic_doctor_id'].'" class="checktoggle">checkbox</label>
            //             </div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->userModel->clinicDoctorCountAll($id),
            "recordsFiltered" => count($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Add Clinic Doctor.
     *
     * @return mixed
     */
    public function addClinicDoctor()
    {
        $inputdata['first_name'] = libsodiumEncrypt($this->request->getPost('first_name'));
        $inputdata['last_name'] = libsodiumEncrypt($this->request->getPost('last_name'));
        $inputdata['email'] = libsodiumEncrypt($this->request->getPost('email'));
        $inputdata['mobileno'] = libsodiumEncrypt($this->request->getPost('mobileno'));
        $inputdata['country_code'] = $this->request->getPost('country_code');
        $inputdata['username'] = libsodiumEncrypt(generate_username($inputdata['first_name'] . ' ' . $inputdata['last_name'] . ' ' . $inputdata['mobileno']));
        $inputdata['role'] = $this->request->getPost('role');
        $inputdata['password'] = md5($this->request->getPost('password') ?? "");
        $inputdata['confirm_password'] = md5($this->request->getPost('confirm_password') ?? "");
        $inputdata['hospital_id'] = $this->request->getPost('hospital_id') ?? "";
        $inputdata['created_date'] = date('Y-m-d H:i:s');
        $inputdata['is_verified'] = 1;

        $where_data_email = array('email' => $inputdata['email']);
        $already_exits = $this->commonModel->countTblResult('users', $where_data_email);

        $where_data_mobile = array('mobileno' => $inputdata['mobileno']);
        $already_exits_mobile_no = $this->commonModel->countTblResult('users', $where_data_mobile);

        if ($already_exits >= 1) {
            $response['msg'] = 'Email already exits';
            $response['status'] = 500;
        } else if ($already_exits_mobile_no >= 1) {
            $response['msg'] = 'Mobile no already exits';
            $response['status'] = 500;
        } else {
            $result = $this->userModel->insertData('users', $inputdata);
            if ($result == true) {
                $response['msg'] = 'Registration success';
                $response['status'] = 200;
            } else {
                $response['msg'] = 'Registration failed';
                $response['status'] = 500;
            }
        }
        echo json_encode($response);
    }
    /**
     * Clinic Preview.
     *
     * @param string $username
     * @return mixed
     */
    public function clinicPreview($username)
    {
        $this->data['page'] = 'clinic_preview';
        $this->data['doctors'] = $this->userModel->getClinicDetails(urldecode($username));
        if (!empty($this->data['doctors'])) {
            $doctor_details = $this->userModel->getClinicDetails(urldecode($username));

            if ($doctor_details['price_type'] == 'Custom Price') {

                $user_currency = get_user_currency();
                $user_currency_code = $user_currency['user_currency_code'];
                $user_currency_rate = $user_currency['user_currency_rate'];

                $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $doctor_details['currency_code'];
                $rate_symbol = currency_code_sign($currency_option);

                if (!empty(session('user_id'))) {
                    $rate = get_doccure_currency($doctor_details['amount'], $doctor_details['currency_code'], $user_currency_code);
                } else {
                    $rate = $doctor_details['amount'];
                }

                $this->data['amount'] = "Free";
            }
            $where = array('patient_id' => session('user_id'), 'doctor_id' => $this->data['doctors']['userid']);
            $this->data['is_favourite'] = $this->userModel->getFavourites($where);
            $this->data['clinic_images'] = $this->userModel->clinicImages($this->data['doctors']['userid']);
            $this->data['education'] = $this->userModel->getEducationDetails($this->data['doctors']['userid']);
            $this->data['experience'] = $this->userModel->getExperienceDetails($this->data['doctors']['userid']);
            $this->data['awards'] = $this->userModel->getAwardsDetails($this->data['doctors']['userid']);
            $this->data['memberships'] = $this->userModel->getMembershipsDetails($this->data['doctors']['userid']);
            $this->data['registrations'] = $this->userModel->getRegistrationsDetails($this->data['doctors']['userid']);
            $this->data['business_hours'] = $this->userModel->getBusinessHours($this->data['doctors']['userid']);
            $this->data['monday_hours'] = $this->userModel->getMondayHours($this->data['doctors']['userid']);
            $this->data['sunday_hours'] = $this->userModel->getSundayHours($this->data['doctors']['userid']);
            $this->data['tue_hours'] = $this->userModel->getTueHours($this->data['doctors']['userid']);
            $this->data['wed_hours'] = $this->userModel->getWedHours($this->data['doctors']['userid']);
            $this->data['thu_hours'] = $this->userModel->getThuHours($this->data['doctors']['userid']);
            $this->data['fri_hours'] = $this->userModel->getFriHours($this->data['doctors']['userid']);
            $this->data['sat_hours'] = $this->userModel->getSatHours($this->data['doctors']['userid']);
            $this->data['reviews'] = $this->userModel->reviewListView($this->data['doctors']['userid']);
            echo view('admin/users/clinic_preview', $this->data);
        } else {
            session()->setFlashdata('error_message', $this->language['lg_oops_something_']);
            redirect('admin/users/clinic');
        }
    }

    // public function patientPreview($patient_id)
    // {
    //     $this->data['page'] = 'mypatient_preview';
    //     $this->data['patient']=$this->my_patients->get_patient_details(base64_decode($patient_id));
    //     $this->data['last_booking']=$this->my_patients->get_last_booking(base64_decode($patient_id));
    //     $this->data['prescription_status']=$this->my_patients->get_booking_prescription_status(base64_decode($patient_id));

    //     $this->data['patient_id']=base64_decode($patient_id);
    //     $this->load->vars($this->data);
    //     $this->load->view($this->data['theme'].'/template');
    // }

    /**
     * lab list.
     *
     * @return mixed
     */
    public function labs()
    {
        $this->data['page'] = 'labs';
        echo view('admin/users/labs', $this->data);
    }
    /**
     * Lab List Data.
     *
     * @return mixed
     */
    public function labListData()
    {
        $input = $this->request->getPost();
        $list = $this->userModel->getLabDatatables($input);
        $data = array();
        $no = $input['start'];
        $a = 1;

        foreach ($list as $lab) {

            $val = '';

            if ($lab['status'] == '1') {
                $val = 'checked';
            }

            $profileimage = (!empty($lab['profileimage'])) && file_exists($lab['profileimage']) ? base_url() . $lab['profileimage'] : base_url() . 'assets/img/user.png';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '#L00' . $lab['id'];
            $row[] = '<h2 class="table-avatar">
                        <span class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image"></span>
                        ' . (libsodiumDecrypt($lab['first_name']) . ' ' . libsodiumDecrypt($lab['last_name'])) . '
                      </h2>';
            $row[] = libsodiumDecrypt($lab['email']);
            $row[] = libsodiumDecrypt($lab['mobileno']);
            $row[] = date('d M Y', strtotime($lab['created_date'])) . '<br><small>' . date('h:i A', strtotime($lab['created_date'])) . '</small>';
            $row[] = '<div class="status-toggle">
                          <input type="checkbox" onchange="change_usersStatus(' . $lab['id'] . ')" id="status_' . $lab['id'] . '" class="check" ' . $val . '>
                          <label for="status_' . $lab['id'] . '" class="checktoggle">checkbox</label>
                        </div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $input['draw'],
            "recordsTotal" => $this->userModel->labCountAll(),
            "recordsFiltered" => $this->userModel->labCountFiltered($input),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    /**
     * lab test booked list.
     *
     * @return mixed
     */
    public function labtestBooked()
    {
        $this->data['page'] = 'labtest_booked';
        echo view('admin/users/labtestBooked', $this->data);
    }
    /**
     * lab test booked list.
     *
     * @return mixed
     */
    public function bookedLabtestListData()
    {
        $input = $this->request->getPost();
        $list = $this->userModel->get_booked_labtest_datatables($input);
        $data = array();
        $no = $input['start'];
        $a = 1;

        foreach ($list as $lab) {


            $currency_option = default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);

            $pay_amount = get_doccure_currency($lab['total_amount'], $lab['currency_code'], default_currency_code());
            $testname = $this->userModel->getLabTestname($lab['booking_ids']);

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $lab['order_id'];
            $row[] = libsodiumDecrypt($lab['patient_firstname']) . " " . libsodiumDecrypt($lab['patient_lastname']);
            $row[] = libsodiumDecrypt($lab['first_name']) . " " . libsodiumDecrypt($lab['last_name']);
            $row[] = ($testname) ? libsodiumDecrypt($testname) : '---';
            $row[] = date('d M Y', strtotime($lab['lab_test_date']));
            $row[] = $rate_symbol . number_format($pay_amount, 2);
            $row[] = '<span class="badge badge-success">' . ($lab['payment_type']) . '</span>';

            $data[] = $row;
        }



        $output = array(
            "draw" => $input['draw'],
            // "recordsTotal" => $this->userModel->bookedLabtestCountAll(),
            "recordsTotal" => $this->userModel->bookedLabtestCountFiltered($input),
            "recordsFiltered" => $this->userModel->bookedLabtestCountFiltered($input),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    /**
     * Lab Tests Page.
     *
     * @return mixed
     */
    public function labTests()
    {
        $this->data['page'] = 'index';
        $this->data['module'] = 'lab_tests';
        echo view('admin/users/labTest', $this->data);
    }
    /**
     * Lab Tests List.
     *
     * @return mixed
     */
    public function labTestsList()
    {
        $input = $this->request->getPost();
        $list = $this->userModel->getLabtestDatatables($input);
        $data = array();
        $no = $input['start'];
        foreach ($list as $lab) {
            if ($lab['status'] == '1') {
                $val = 'checked';
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = libsodiumDecrypt($lab['first_name']) . ' ' . libsodiumDecrypt($lab['last_name']);
            $row[] = libsodiumDecrypt($lab['lab_test_name']);
            $amount = 0;
            if ($lab['amount']) {
                $amount = get_doccure_currency($lab['amount'], $lab['currency_code'], default_currency_code());
            }
            $row[] = currency_code_sign($lab['currency_code']) . '' . number_format($amount, 2);
            $row[] = libsodiumDecrypt($lab['duration']);
            $row[] = libsodiumDecrypt($lab['description']);
            $row[] = date('d M Y', strtotime($lab['created_date'])) . '<br><small>' . date('h:i A', strtotime($lab['created_date'])) . '</small>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $input['draw'],
            "recordsTotal" => $this->userModel->labtestCountAll(),
            "recordsFiltered" => $this->userModel->labtestCountFiltered($input),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Load Pharmacies Page.
     *
     * @return mixed
     */
    public function pharmacies()
    {
        $this->data['page'] = 'pharmacies';
        $this->data['get_pharmacy_details'] = $this->commonModel->getTblResultOfData('users', ['pharmacy_user_type' => 1], '*');
        $admin_pharmacy = $this->commonModel->getTblRowOfData('users', ['pharmacy_user_type' => 1], 'id');
        $this->data['specification'] = $this->commonModel->getTblRowOfData('pharmacy_specifications', ['pharmacy_id' => $admin_pharmacy['id']], '*');
        echo view('admin/users/pharmacies', $this->data);
    }
    /**
     * Pharmacies List.
     *
     * @return mixed
     */
    public function pharmaciesList()
    {
        $input = $this->request->getPost();
        $list = $this->userModel->getPharmacyDatatables();

        $data = array();
        $no = $input['start'];
        $a = 1;

        foreach ($list as $pharmacy) {

            $val = '';

            if ($pharmacy['status'] == '1') {
                $val = 'checked';
            }
            $pharmacy_id = encryptor_decryptor('encrypt', $pharmacy['id']);
            $pharmacy_name = ($pharmacy['pharmacy_name'] != '') ? ucfirst(libsodiumDecrypt($pharmacy['pharmacy_name'])) : ucfirst(libsodiumDecrypt($pharmacy['first_name'])) . ' ' . libsodiumDecrypt($pharmacy['last_name']);
            $profileimage = (!empty($pharmacy['profileimage'])) && file_exists($pharmacy['profileimage']) ? base_url() . $pharmacy['profileimage'] : base_url() . 'assets/img/user.png';
            //$pharmacy_id = $pharmacy['id'];
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<h2 class="table-avatar">
                    <a href="javascript:;" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image"></a>
                    <a target="_blank" href="' . base_url() . 'pharmacy-preview/' . $pharmacy_id . '">' . $pharmacy_name . '</a>
                  </h2>';
            $row[] = libsodiumDecrypt($pharmacy['email']);
            $row[] = libsodiumDecrypt($pharmacy['mobileno']);
            $row[] = ($pharmacy['home_delivery'] != '') ? ucfirst($pharmacy['home_delivery']) : 'N/A';
            $row[] = ($pharmacy['hrs_open'] != '') ? ucfirst($pharmacy['hrs_open']) : 'N/A';
            // $row[] = ($pharmacy['pharamcy_opens_at'] != '') ? date('h:i A', strtotime($pharmacy['pharamcy_opens_at'])) : 'N/A';
            $row[] = ($pharmacy['pharamcy_opens_at'] != '') ? ($pharmacy['pharamcy_opens_at']) : 'N/A';
            $row[] = date('d M Y', strtotime($pharmacy['created_date'])) . '<br><small>' . date('h:i A', strtotime($pharmacy['created_date'])) . '</small>';
            $row[] = '<div class="status-toggle">
                      <input type="checkbox" onchange="change_usersStatus(' . $pharmacy['id'] . ')" id="status_' . $pharmacy['id'] . '" class="check" ' . $val . '>
                      <label for="status_' . $pharmacy['id'] . '" class="checktoggle">checkbox</label>
                        
                    </div>';

            $data[] = $row;
        }



        $output = array(
            "draw" => $input['draw'],
            "recordsTotal" => $this->userModel->pharmacyCountAll(),
            "recordsFiltered" => $this->userModel->pharmacyCountFiltered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Pharmacies Update.
     *
     * @return mixed
     */
    public function updatePharmacy()
    {

        //print_r($this->request->getPost);  exit;
        $response['status'] = 500;

        // echo "<pre>"; print_r($this->request->getPost); exit;
        $pharmacy_id = $this->request->getPost('pharmacy_id');
        $updatedata = array(
            'first_name' => libsodiumEncrypt($this->request->getPost('first_name')),
            'last_name' => libsodiumEncrypt($this->request->getPost('last_name')),
            //'pharmacy_name' => $this->request->getPost('pharmacy_name'),
            'email' => libsodiumEncrypt($this->request->getPost('email')),
            'mobileno' => libsodiumEncrypt($this->request->getPost('mobileno')),
        );
        $result = $this->commonModel->updateData('users', ['id' => $pharmacy_id], $updatedata);
        if ($result == true) {
            session()->setFlashdata('success_message', 'Admin pharmacy updated successfully');
        } else {
            session()->setFlashdata('error_message', 'Edit Required!');
        }
        $pharmacydata = array();

        if ($this->request->getPost('home_delivery') != '') {
            $pharmacydata['home_delivery'] = $this->request->getPost('home_delivery');
            $pharmacydata['pharmacy_id'] = $pharmacy_id;
        } else {
            $pharmacydata['home_delivery'] = 'No';
            $pharmacydata['pharmacy_id'] = $pharmacy_id;
        }

        if ($this->request->getPost('hrsopen') != '') {
            $pharmacydata['24hrsopen'] = $this->request->getPost('hrsopen');
            $pharmacydata['pharmacy_id'] = $pharmacy_id;
        } else {
            $pharmacydata['24hrsopen'] = 'No';
            $pharmacydata['pharmacy_id'] = $pharmacy_id;
        }
        if ($this->request->getPost('pharmacy_opens_at') != '') {
            $pharmacydata['pharamcy_opens_at'] = $this->request->getPost('pharmacy_opens_at');
            $pharmacydata['pharmacy_id'] = $pharmacy_id;
        } else {
            $pharmacydata['pharamcy_opens_at'] = '00:00';
            $pharmacydata['pharmacy_id'] = $pharmacy_id;
        }

        // save or update the pharmacy specifications..
        $already_exits_pharmacy_specifications = $this->commonModel->countTblResult('pharmacy_specifications', ['pharmacy_id' => $pharmacy_id]);
        if ($already_exits_pharmacy_specifications >= 1) {
            // update query
            $select_qry = $this->commonModel->getTblRowOfData('pharmacy_specifications', ['pharmacy_id' => $pharmacy_id], '*');
            if (!empty($select_qry)) {
                $phar_spec_id = $select_qry['id'];
                $result = $this->commonModel->updateData('pharmacy_specifications', ['id' => $phar_spec_id], $pharmacydata);
                $response['status'] = 200;
            } else {
                // insert query
                $this->commonModel->insertData('pharmacy_specifications', $pharmacydata);
                $response['status'] = 200;
            }
        } else {
            // insert query
            $this->commonModel->insertData('pharmacy_specifications', $pharmacydata);
            $response['status'] = 200;
        }
        return redirect('admin/users/pharmacies');
        //echo json_encode($response);
    }

    public function pharmacyOrders() {
        $this->data['page'] = 'pharmacies';
        $this->data['get_pharmacy_details'] = $this->commonModel->getTblResultOfData('users', ['pharmacy_user_type' => 1], '*');
        $admin_pharmacy = $this->commonModel->getTblRowOfData('users', ['pharmacy_user_type' => 1], 'id');
        $this->data['specification'] = $this->commonModel->getTblRowOfData('pharmacy_specifications', ['pharmacy_id' => $admin_pharmacy['id']], '*');
        echo view('admin/users/orders', $this->data);
    }

    public function ordersList() {
        $data = [];
        $no = $_POST['start'];
        $a = $no + 1;
        $list = $this->userModel->getOrderDatatables();
        foreach ($list as $products) {
            $created_at = date("Y-m-d", strtotime($products['created_at']));
            $val = '';
            if ($products['status'] == '1') {
                $val = 'checked';
            }
            $no++;
            $row = [];
            $row[] = $a++;
            $row[] = $products['order_id'];
            $row[] = ucwords(libsodiumDecrypt($products['pharmacy_first_name']).' '.libsodiumDecrypt($products['pharmacy_last_name']));
            $row[] = ucwords(libsodiumDecrypt($products['patient_firstname']).' '.libsodiumDecrypt($products['patient_lastname']));
            $row[] = $products['full_name'];
            $row[] = $products['qty'];
            $row[] = convert_to_user_currency($products['subtotal'], $products['currency_code']);
            $row[] = $products['payment_type'];
            $status = "";
            if ($products['order_status'] == 'pending') {
                $status = '<span class="badge badge-primary">' . $this->language['lg_order_placed'] . '</span>';
            } elseif ($products['order_status'] == 'shipped') {
                $status = '<span class="badge badge-warning">' . $this->language['lg_shipped'] . '</span>';
            } elseif ($products['order_status'] == 'completed') {
                $status = '<span class="badge badge-success">' . $this->language['lg_delivered'] . '</span>';
            } elseif ($products['order_status'] == 'accepted') {
                $status = '<span class="badge badge-success">' . $this->language['lg_accepted'] . '</span>';
            } elseif ($products['order_status'] == 'rejected') {
                $status = '<span class="badge badge-danger">' . $this->language['lg_rejected'] . '</span>';
            }

            $row[] = $status;
            $row[] = date('d M Y', strtotime($created_at));
            $data[] = $row;
        }
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->userModel->countFilteredOrders(),
            "recordsFiltered" => $this->userModel->countFilteredOrders(),
            "data" => $data,
        ];
        //output to json format
        return $this->response->setJSON($output);
    }
}
