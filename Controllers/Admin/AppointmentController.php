<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\CommonModel;

class AppointmentController extends BaseController
{
    public  $data;
    public  $session;
    public  $commonModel;
    public $language;
    public $appointmentModel;
    protected string $table = 'appointments a';
    protected string $users = 'users u';
    // protected $column_search = array('u.first_name','u.last_name','u.profileimage','a.appointment_date','a.from_date_time'); //set column field database for datatable searchable 
    /**
     * @var string[] Array of search columns.
     */
    protected  $column_search = array('CONCAT(u.first_name," ", u.last_name)', 'date_format(a.appointment_date,"%d %b %Y")', 'a.type');
    /**
     * @var string[] Array of search columns.
     */
    protected  $order = array('a.id' => 'ASC'); // default order
    /**
     * @var string[] Array of search columns.
     */
    protected  $column_order = array('', 'CONCAT(u.first_name," ", u.last_name)', 'a.appointment_date', 'a.type');

    // admin
    protected string $appoinments = 'appointments a';
    protected string $doctor = 'users d';
    protected string $doctor_details = 'users_details dd';
    protected string $patient = 'users p';
    protected string $patient_details = 'users_details pd';
    protected string $specialization = 'specialization s';
    protected string $payment = 'payments pa';
    /**
     * @var string[] Array of search columns.
     */
    protected  $appoinments_column_search = array('CONCAT(d.first_name," ",d.last_name)', 'd.profileimage', 'CONCAT(p.first_name," ",p.last_name)', 'p.profileimage', 'date_format(a.appointment_date,"%d %b %Y")', 'date_format(a.created_date,"%d %b %Y")', 'a.type');
    /**
     * @var string[] Array of search columns.
     */
    protected  $appoinments_default_order = array('a.id' => 'DESC'); // upcoming appointments default order 
    /**
     * @var string[] Array of search columns.
     */
    protected  $appointments_column_order = array('', 'cliu.first_name, d.first_name', 'p.first_name', 'a.from_date_time', 'a.created_date', 'a.type', 'a.appointment_status', 'total_amount_decimal'); // upcoming appointments column order 
    /**
     * @var string[] Array of search columns.
     */
    protected  $appoinments_order = array('CONCAT(d.first_name," ",d.last_name)', 'd.profileimage', 'CONCAT(p.first_name," ",p.last_name)', 'p.profileimage', 'date_format(a.appointment_date,"%d %b %Y")', 'a.created_date', 'a.type');

    protected string $lab_payments = 'lab_payments lp';
    /**
     * @var string[] Array of search columns.
     */
    protected  $labappoinments_column_search = array('CONCAT(p.first_name," ", p.last_name)', 'date_format(lp.lab_test_date,"%d %b %Y")', 'lp.total_amount', 'date_format(lp.payment_date,"%d %b %Y")', 'lp.cancel_status', 'lt.lab_test_name');
    /**
     * @var string[] Array of search columns.
     */
    protected  $labappoinments_order = array('lp.id' => 'DESC'); // default order 

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'appointments';
        $this->data['page'] = '';

        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);
        //Define Model
        $this->commonModel = new CommonModel();
        $this->appointmentModel = new AppointmentModel();
    }

    /**
     * load appointment page.
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        echo view('admin/appointments/index', $this->data);
    }
    /**
     * Appoinments List.
     *
     * @return mixed.
     */
    public function appoinmentsList()
    {
        $list = $this->appointmentModel->getAppoinmentsDatatables();
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $appoinments) {

            $val = '';

            if ($appoinments['appointment_status'] == '1') {
                $val = 'checked';
            }

            $doctor_profileimage = (!empty($appoinments['doctor_profileimage']) && file_exists(FCPATH . $appoinments['doctor_profileimage'])) ? base_url() . $appoinments['doctor_profileimage'] : base_url() . 'assets/img/user.png';
            $patient_profileimage = (!empty($appoinments['patient_profileimage']) && file_exists(FCPATH . $appoinments['patient_profileimage'])) ? base_url() . $appoinments['patient_profileimage'] : base_url() . 'assets/img/user.png';
            $no++;
            $row = array();
            $row[] = $no;
            // $row[] = '<h2 class="table-avatar">
            //           <a target="_blank" href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'" class="avatar avatar-sm mr-2">
            //             <img class="avatar-img rounded-circle" src="'.$doctor_profileimage.'" alt="User Image">
            //           </a>
            //           <a target="_blank" href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'">Dr. '.ucfirst($appoinments['doctor_name']).' <span>'.ucfirst($appoinments['doctor_specialization']).'</span></a>
            //         </h2>';


            if ($appoinments['hospital_id'] != "") {
                $row[] = '<h2 class="table-avatar">
                  <a href="' . base_url() . 'doctor-preview/' . $appoinments['clinic_username'] . '"><img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">' . ucfirst(libsodiumDecrypt($appoinments['clinic_first_name']) . ' ' . libsodiumDecrypt($appoinments['clinic_last_name'])) . ' </a>
                 </h2>
                  ';
            } else {


                if ($appoinments['role'] == 1) {
                    $value = $this->language['lg_dr'];
                    $docLink = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['doctor_username']));
                    $img = '<a href="' . base_url() . 'doctor-preview/' . $docLink . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['doctor_specialization']));
                } else {
                    $value = "";
                    $img = '<a href="#" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>';
                    $specialization = "";
                }
                $docLink = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['doctor_username']));
                $row[] = '<h2 class="table-avatar">
                  ' . $img . '
                  <a href="' . base_url() . 'doctor-preview/' . $docLink . '">' . $value . ' ' . ucfirst(libsodiumDecrypt($appoinments['doc_first_name']) . ' ' . libsodiumDecrypt($appoinments['doc_last_name'])) . ' <span>' . libsodiumDecrypt($specialization) . '</span></a>
                </h2>
                  ';
            }
            $row[] = '<h2 class="table-avatar">
            <a target="_blank" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
            <a target="_blank">' . ucfirst(libsodiumDecrypt($appoinments['patient_first_name']) . ' ' . libsodiumDecrypt($appoinments['patient_last_name'])) . ' </a>
                </h2>';
            $from_date_time = '';
            if (!empty($appoinments['time_zone'])) {
                $from_timezone = $appoinments['time_zone'];
                $to_timezone = date_default_timezone_get();
                $from_date_time = $appoinments['from_date_time'];
                $from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
                $row[] = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info">' . date('h:i A', strtotime($from_date_time)) . '</span>';
            } else {
                $row[] = '-';
            }
            $row[] = date('d M Y', strtotime($appoinments['created_date']));
            $row[] = ucfirst($appoinments['type']);
            // $row[] = '<div class="status-toggle">
            //           <input type="checkbox" disabled  id="status_' . $appoinments['id'] . '" class="check" ' . $val . '>
            //           <label for="status_' . $appoinments['id'] . '" class="checktoggle">checkbox</label>
            //         </div>';
            if ($appoinments['appointment_status'] == 0) {
                $row[] = $appoinments['appointment_status'] = 'New';
            } elseif ($appoinments['appointment_status'] == 1) {
                $row[] = $appoinments['appointment_status'] = 'Completed';
            } elseif ($appoinments['appointment_status'] == 2) {
                $row[] = $appoinments['appointment_status'] = 'Expired';
            }
            $org_amount = 0;
            if ($appoinments['total_amount']) {
                $org_amount = get_doccure_currency($appoinments['total_amount'], $appoinments['currency_code'], default_currency_code());
            }

            $row[] = default_currency_symbol() . ' ' . number_format($org_amount, 2, '.', ',');

            $data[] = $row;
        }



        $output = array(
            "draw" => $_POST['draw'],
            //"recordsTotal" => $this->appointmentModel->appoinmentsCountAll(1),
            "recordsTotal" => $this->appointmentModel->appoinmentsCountFiltered(1),
            "recordsFiltered" => $this->appointmentModel->appoinmentsCountFiltered(1),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
        //return $this->response->setJSON($output);
    }
    /**
     * Upcomming Appoinments List.
     *
     * @return mixed.
     */
    public function upappoinmentsList()
    {
        $list = $this->appointmentModel->getUpappoinmentsDatatables();
        $data = array();
        $no = $_POST['start'];
        $a = 1;
        foreach ($list as $appoinments) {
            $val = '';

            if ($appoinments['appointment_status'] == '0') {
                $val = 'checked';
            }

            $doctor_profileimage = (!empty($appoinments['doctor_profileimage']) && file_exists(FCPATH . $appoinments['doctor_profileimage'])) ? base_url() . $appoinments['doctor_profileimage'] : base_url() . 'assets/img/user.png';
            $patient_profileimage = (!empty($appoinments['patient_profileimage']) && file_exists(FCPATH . $appoinments['patient_profileimage'])) ? base_url() . $appoinments['patient_profileimage'] : base_url() . 'assets/img/user.png';
            $no++;
            $row = array();
            $row[] = $a++;

            if ($appoinments['hospital_id'] != "") {
                $row[] = '<h2 class="table-avatar">
                  <a href="' . base_url() . 'doctor-preview/' . $appoinments['clinic_username'] . '">' . ucfirst(libsodiumDecrypt($appoinments['clinic_first_name']) . ' ' . libsodiumDecrypt($appoinments['clinic_last_name'])) . ' </a>
                 </h2>
                  ';
            } else {


                if ($appoinments['role'] == 1) {
                    $value = $this->language['lg_dr'];
                    $docLink = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['doctor_username']));
                    $img = '<a href="' . base_url() . 'doctor-preview/' . $docLink . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['doctor_specialization']));
                } else {
                    $value = "";
                    $img = '<a href="#" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>';
                    $specialization = "";
                }
                $docLink = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['doctor_username']));
                $row[] = '<h2 class="table-avatar">
                  ' . $img . '
                  <a href="' . base_url() . 'doctor-preview/' . $docLink . '">' . $value . ' ' . ucfirst(libsodiumDecrypt($appoinments['doc_first_name']) . ' ' . libsodiumDecrypt($appoinments['doc_first_name'])) . ' <span>' . libsodiumDecrypt($specialization) . '</span></a>
                </h2>
                  ';
            }

            $row[] = '<h2 class="table-avatar">
                  <a target="_blank" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
                  <a target="_blank">' . ucfirst(libsodiumDecrypt($appoinments['patient_first_name']) . ' ' . libsodiumDecrypt($appoinments['patient_last_name'])) . ' </a>
                </h2>';
            $from_date_time = '';
            if (!empty($appoinments['time_zone'])) {
                $from_timezone = $appoinments['time_zone'];
                $to_timezone = date_default_timezone_get();
                $from_date_time = $appoinments['from_date_time'];
                $from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
                $row[] = date('d M Y', strtotime($appoinments['from_date_time'])) . ' <span class="d-block text-info">' . date('h:i A', strtotime($appoinments['from_date_time'])) . '</span>';
            } else {
                $row[] = '-';
            }
            $row[] = date('d M Y', strtotime($appoinments['created_date']));
            $row[] = ucfirst($appoinments['type']);
            // $row[] = '<div class="status-toggle">
            //           <input type="checkbox" disabled  id="status_' . $appoinments['id'] . '" class="check" ' . $val . '>
            //           <label for="status_' . $appoinments['id'] . '" class="checktoggle">checkbox</label>
            //         </div>';
            if ($appoinments['appointment_status'] == 0) {
                $row[] = $appoinments['appointment_status'] = 'New';
            } elseif ($appoinments['appointment_status'] == 1) {
                $row[] = $appoinments['appointment_status'] = 'Completed';
            } elseif ($appoinments['appointment_status'] == 2) {
                $row[] = $appoinments['appointment_status'] = 'Expired';
            }
            $org_amount = 0;
            if ($appoinments['total_amount']) {
                $org_amount = get_doccure_currency($appoinments['total_amount'], $appoinments['currency_code'], default_currency_code());
            }

            $row[] = default_currency_symbol() . ' ' . number_format($org_amount, 2, '.', ',');

            $data[] = $row;
        }



        $output = array(
            "draw" => $_POST['draw'],
            //"recordsTotal" => $this->appointmentModel->appoinmentsCountAll(2),
            "recordsTotal" => $this->appointmentModel->appoinmentsCountFiltered(2),
            "recordsFiltered" => $this->appointmentModel->appoinmentsCountFiltered(2),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
        //return $this->response->setJSON($output);
    }
    /**
     * Missed Appoinments List.
     *
     * @return mixed.
     */
    public function missedappoinmentsList()
    {
        $list = $this->appointmentModel->getMissedAppoinmentsDatatables();
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $appoinments) {

            $val = '';

            if ($appoinments['appointment_status'] == '0') {
                $val = 'checked';
            }

            $doctor_profileimage = (!empty($appoinments['doctor_profileimage']) && file_exists(FCPATH . $appoinments['doctor_profileimage'])) ? base_url() . $appoinments['doctor_profileimage'] : base_url() . 'assets/img/user.png';
            $patient_profileimage = (!empty($appoinments['patient_profileimage']) && file_exists(FCPATH . $appoinments['patient_profileimage'])) ? base_url() . $appoinments['patient_profileimage'] : base_url() . 'assets/img/user.png';
            $no++;
            $row = array();
            $row[] = $no;
            // $row[] = '<h2 class="table-avatar">
            //           <a target="_blank" href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'" class="avatar avatar-sm mr-2">
            //             <img class="avatar-img rounded-circle" src="'.$doctor_profileimage.'" alt="User Image">
            //           </a>
            //           <a target="_blank" href="'.base_url().'doctor-preview/'.$appoinments['doctor_username'].'">Dr. '.ucfirst($appoinments['doctor_name']).' <span>'.ucfirst($appoinments['doctor_specialization']).'</span></a>
            //         </h2>';


            if ($appoinments['hospital_id'] != "") {
                $row[] = '<h2 class="table-avatar">
                  <a href="' . base_url() . 'doctor-preview/' . $appoinments['clinic_username'] . '">' . ucfirst(libsodiumDecrypt($appoinments['clinic_first_name']) . ' ' . libsodiumDecrypt($appoinments['clinic_last_name'])) . ' </a>
                 </h2>
                  ';
            } else {


                if ($appoinments['role'] == 1) {
                    $value = $this->language['lg_dr'];
                    $docLink = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['doctor_username']));
                    $img = '<a href="' . base_url() . 'doctor-preview/' . $docLink . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['doctor_specialization']));
                } else {
                    $value = "";
                    $img = '<a href="#" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>';
                    $specialization = "";
                }
                $docLink = encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['doctor_username']));
                $row[] = '<h2 class="table-avatar">
                  ' . $img . '
                  <a href="' . base_url() . 'doctor-preview/' . $docLink  . '">' . $value . ' ' . ucfirst(libsodiumDecrypt($appoinments['doc_first_name']) . ' ' . libsodiumDecrypt($appoinments['doc_last_name'])) . ' <span>' . $specialization . '</span></a>
                </h2>
                  ';
            }

            $row[] = '<h2 class="table-avatar">
                  <a target="_blank" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
                  <a target="_blank">' . ucfirst(libsodiumDecrypt($appoinments['patient_first_name']) . ' ' . libsodiumDecrypt($appoinments['patient_last_name'])) . ' </a>
                </h2>';
            $from_date_time = '';
            if (!empty($appoinments['time_zone'])) {
                $from_timezone = $appoinments['time_zone'];
                $to_timezone = date_default_timezone_get();
                $from_date_time = $appoinments['from_date_time'];
                $from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
                $row[] = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info">' . date('h:i A', strtotime($from_date_time)) . '</span>';
            } else {
                $row[] = '-';
            }
            $row[] = date('d M Y', strtotime($appoinments['created_date']));
            $row[] = ucfirst($appoinments['type']);
            // $row[] = '<div class="status-toggle">
            //           <input type="checkbox" disabled  id="status_' . $appoinments['id'] . '" class="check" ' . $val . '>
            //           <label for="status_' . $appoinments['id'] . '" class="checktoggle">checkbox</label>
            //         </div>';
            if ($appoinments['appointment_status'] == 0) {
                $row[] = $appoinments['appointment_status'] = 'New';
            } elseif ($appoinments['appointment_status'] == 1) {
                $row[] = $appoinments['appointment_status'] = 'Completed';
            } elseif ($appoinments['appointment_status'] == 2) {
                $row[] = $appoinments['appointment_status'] = 'Expired';
            }
            $org_amount = 0;
            if ($appoinments['total_amount']) {
                $org_amount = get_doccure_currency($appoinments['total_amount'], $appoinments['currency_code'], default_currency_code());
            }

            $row[] = default_currency_symbol() . ' ' . number_format($org_amount, 2, '.', ',');

            $data[] = $row;
        }



        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->appointmentModel->appoinmentsCountFiltered(3),
            //"recordsTotal" => $this->appointmentModel->appoinmentsCountAll(3),
            "recordsFiltered" => $this->appointmentModel->appoinmentsCountFiltered(3),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
        //return $this->response->setJSON($output);
    }
}
