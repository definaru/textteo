<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MypatientsModel;
use App\Models\CommonModel;
use Mpdf\Mpdf;

class MypatientsController extends BaseController
{

    public mixed $data;
    public mixed $session;
    public mixed $timezone;
    public mixed $lang;
    public mixed $language;
    /**
     * @var \App\Models\HomeModel
     */
    public $homeModel;
    /**
     * @var \App\Models\MypatientsModel
     */
    public $mypatientsModel;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;

    public function __construct()
    {

        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);

        $this->data['theme'] = 'user';
        $this->data['module'] = 'doctor';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->timezone = session('time_zone');
        if (!empty($this->timezone)) {
            date_default_timezone_set($this->timezone);
        }

        $this->data['uri'] = service('uri');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->mypatientsModel = new MypatientsModel();
        $this->commonModel = new CommonModel();
    }
    /**
     *  My patients page.
     * 
     * 
     * @return mixed
     */
    public function index()
    {
        if (session('role') == '1' || session('role') == '6') {
            $this->data['page'] = 'my_patients';
            return view('user/doctor/my_patients', $this->data);
        } else {
            // return redirect(base_url().'dashboard');
        }
    }
    /**
     *  Patient lists.
     * 
     * 
     * @return mixed
     */
    public function patientList()
    {
        $response = array();
        $result = array();
        $page = $this->request->getPost('page');
        $limit = 8;
        $user_id = session('user_id');
        $response['count'] = $this->mypatientsModel->patientList($page, $limit, 1, $user_id);
        $patient_list = $this->mypatientsModel->patientList($page, $limit, 2, $user_id);

        if (!empty($patient_list)) {
            foreach ($patient_list as $rows) {


                $data['id'] = $rows['id'];
                $data['user_id'] = $rows['user_id'];
                $data['userid'] = base64_encode($rows['user_id']);
                $data['username'] = $rows['username'];
                if (!empty($rows['profileimage']) && file_exists($rows['profileimage'])) {
                    $data['profileimage'] = base_url() . $rows['profileimage'];
                } else {
                    $data['profileimage'] = base_url() . 'assets/img/user.png';
                }
                $data['first_name'] = ucfirst(libsodiumDecrypt($rows['first_name']));
                $data['last_name'] = ucfirst(libsodiumDecrypt($rows['last_name']));
                $data['mobileno'] = libsodiumDecrypt($rows['mobileno']);
                $data['dob'] = $rows['dob'];
                $data['age'] = age_calculate($rows['dob']);
                $data['blood_group'] = libsodiumDecrypt($rows['blood_group']);
                $data['gender'] = libsodiumDecrypt($rows['gender']);
                $data['cityname'] = $rows['cityname'];
                $data['countryname'] = $rows['countryname'];
                $result[] = $data;
            }
        }
        $response['current_page_no'] = $page;
        $response['total_page'] = ceil($response['count'] / $limit);
        $response['data'] = $result;

        echo json_encode($response);
    }
    /**
     *  Mypatient Preview.
     * 
     * @param mixed $patient_id
     * @return mixed
     */
    public function mypatientPreview($patient_id)
    {
        $this->data['page'] = 'mypatient_preview';
        $this->data['patient'] = $this->mypatientsModel->getPatientDetails(base64_decode($patient_id));
        $this->data['last_booking'] = $this->mypatientsModel->getLastBooking(base64_decode($patient_id));
        $this->data['prescription_status'] = $this->mypatientsModel->getBookingPrescriptionStatus(base64_decode($patient_id));
        $this->data['patient_id'] = base64_decode($patient_id);
        return view('user/doctor/mypatient_preview', $this->data);
    }
    /**
     *  Appointment Lists.
     * 
     * 
     * @return mixed
     */
    public function appoinmentsList()
    {
        $list = $this->mypatientsModel->getAppoinmentsDatatables($this->request->getPost());
        $data = array();
        $no = $this->request->getPost('start');
        $a = 1;
        $sno = $no + 1;

        foreach ($list as $appoinments) {
            if ($appoinments['profileimage'] == "" || ($appoinments['profileimage'] != "" && !is_file($appoinments['profileimage']))) {
                $profile_image = base_url() . 'assets/img/user.png';
            } else {
                $profile_image = (!empty($appoinments['profileimage'] ?? "")) ? base_url() . $appoinments['profileimage'] ?? "" : base_url() . 'assets/img/user.png';
            }
            $no++;
            $row = array();
            $row[] = $sno++;

            if ($appoinments['hospital_id'] != "") {
                $row[] = '<h2 class="table-avatar">
                        ' . $profile_image . '
                        <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['clinic_username'])) . '">' . ucfirst($appoinments['clinic_first_name'] . ' ' . $appoinments['clinic_last_name']) . ' </a>
                        </h2>
                        ';
            } else {

                if ($appoinments['role'] == 1) {
                    $value = $this->language['lg_dr'];
                    $img = '<a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['username'])) . '" class="avatar avatar-sm mr-2">
                            <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                } else {
                    /*$value="";
                $img="";
                $specialization="";*/
                    // $value=$this->language['lg_dr'];
                    $value = "";
                    $img = '<a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['username'])) . '" class="avatar avatar-sm mr-2">
                            <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>';
                    $specialization = ucfirst(libsodiumDecrypt($appoinments['specialization']));
                }

                $row[] = '<h2 class="table-avatar">
                        ' . $img . '
                        <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($appoinments['username'])) . '">' . $value . ' ' . ucfirst(libsodiumDecrypt($appoinments['first_name']) . ' ' . libsodiumDecrypt($appoinments['last_name'])) . ' <span>' . $specialization . '</span></a>
                        </h2>
                        ';
            }
            $from_date_time = '';
            if (!empty($appoinments['time_zone'])) {
                $from_timezone = $appoinments['time_zone'];
                $to_timezone = date_default_timezone_get();
                $from_date_time = $appoinments['from_date_time'];
                $from_date_time = converToTz($from_date_time, $to_timezone, $from_timezone);
                $to_date_time = $appoinments['to_date_time'];
                $to_date_time = converToTz($to_date_time, $to_timezone, $from_timezone);
                $row[] = date('d M Y', strtotime($from_date_time)) . ' <span class="d-block text-info">' . date('h:i A', strtotime($from_date_time)) . ' - ' . date('h:i A', strtotime($to_date_time)) . '</span>';
            } else {
                $row[] = '-';
            }
            $row[] = date('d M Y', strtotime($appoinments['created_date']));
            $row[] = ucfirst($appoinments['type']);

            if (session('role') != 6) {

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
            }

            $data[] = $row;
        }



        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->mypatientsModel->appoinments_count_all($this->request->getPost()),
            "recordsFiltered" => count($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     *  Change Appointment Status.
     * 
     * 
     * @return mixed
     */
    public function changeAppointmentStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        if ($status === 1) {
            $this->commonModel->updateData('appointments', array('id' => $id), array('call_status' => 1, 'appointment_status' => $status));
        } else {
            $this->commonModel->updateData('appointments', array('id' => $id), array('appointment_status' => $status));
        }
        echo 'success';
    }
    /**
     *  Prescriptions List.
     * 
     * 
     * @return mixed
     */
    public function prescriptionsList()
    {
        $list = $this->mypatientsModel->getPrescriptionDatatables($this->request->getPost());
        $data = array();
        $no = $this->request->getPost('start');
        $a = $no + 1;
        $b = $no + 1;
        foreach ($list as $prescriptions) {
            $profile_image = base_url() . 'assets/img/user.png';
            if (!empty($prescriptions['profileimage']) && file_exists($prescriptions['profileimage'])) {
                $profile_image = base_url() . $prescriptions['profileimage'];
            }

            $no++;
            $row = array();
            $row[] = $a++;
            $row[] = date('d M Y', strtotime($prescriptions['created_at']));
            $row[] = 'Prescription ' . $b++;
            $row[] = '<h2 class="table-avatar">
                    <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($prescriptions['username'])) . '" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                    </a>
                    <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($prescriptions['username'])) . '">Dr. ' . ucfirst(libsodiumDecrypt($prescriptions['first_name']) . ' ' . libsodiumDecrypt($prescriptions['last_name'])) . ' <span>' . ucfirst(libsodiumDecrypt($prescriptions['specialization'])) . '</span></a>
                    </h2>
                    ';

            $html = '<div class="table-action">
                    <a href="' . base_url() . 'my_patients/print-prescription/' . base64_encode($prescriptions['id']) . '" target="_blank" download class="btn btn-sm bg-success-light mb-2 mr-2"><i class="fas fa-download"></i> ' . $this->language['lg_download'] . '</a>

                    <a target="_blank" href="' . base_url() . 'my_patients/print-prescription/' . base64_encode($prescriptions['id']) . '" class="btn btn-sm bg-primary-light mb-2 mr-2">
                        <i class="fas fa-print"></i> ' . $this->language['lg_print'] . '
                    </a>
                    <a href="javascript:void(0);" onclick="view_prescription(' . $prescriptions['id'] . ')" class="btn btn-sm bg-info-light mb-2 mr-2">
                        <i class="far fa-eye"></i> ' . $this->language['lg_view1'] . '
                    </a>';
            if (is_doctor()) {
                $html .= '<a href="' . base_url() . 'my_patients/edit-prescription/' . base64_encode($prescriptions['id']) . '/' . base64_encode($prescriptions['patient_id']) . '" class="btn btn-sm bg-success-light mb-2 mr-2">
                        <i class="fas fa-edit"></i> ' . $this->language['lg_edit2'] . '
                    </a>
                    <a href="javascript:void(0);" onclick="delete_prescription(' . $prescriptions['id'] . ')" class="btn btn-sm bg-danger-light mb-2 mr-2">
                        <i class="far fa-trash-alt"></i> ' . $this->language['lg_delete'] . '
                    </a>';
            }
            $html .= '</div>';

            $row[] = $html;

            $data[] = $row;
        }



        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => $this->mypatientsModel->prescriptionCountAll($this->request->getPost()),
            "recordsFiltered" => $this->mypatientsModel->prescriptionCountAll($this->request->getPost()),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     *  Print Prescriptions.
     * 
     * @param mixed $prescription_id
     * @return mixed
     */
    public function printPrescription($prescription_id)
    {
        $data['prescription'] = $this->mypatientsModel->getPrescriptionDetails(base64_decode($prescription_id));
        $data['language'] = $this->language;
        // echo view('user/doctor/print_billing',$data);
        $mpdf = new \Mpdf\Mpdf();
        $html = view('user/doctor/print_prescription', $data);
        $mpdf->WriteHTML($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $fileName = 'prescription - ' . date('Y-m-d H:i:s') . '.pdf';
        $mpdf->Output($fileName, 'I');
    }
    /**
     *  Get Prescription Details.
     * 
     * 
     * @return mixed
     */
    public function get_prescription_details()
    {
        $prescription_id = $this->request->Getpost('pre_id');
        $result = $this->mypatientsModel->getPrescriptionDetails($prescription_id);
        $result[0]['doctor_name'] = libsodiumDecrypt($result[0]['doc_firstname']) . ' ' . libsodiumDecrypt($result[0]['doc_last_name']);
        $result[0]['patient_name'] = libsodiumDecrypt($result[0]['pat_first_name']) . ' ' . libsodiumDecrypt($result[0]['pat_last_name']);

        echo json_encode($result);
    }

    public function getPrescriptionDetailsV2(){
        $prescription_id = $this->request->Getpost('pre_id');
        $result = $this->mypatientsModel->getPrescriptionDetailsV2($prescription_id);
        $hospitalInfo = user_hospital($result[0]['doctor_id']);
        $result[0]['doctor_name'] = libsodiumDecrypt($result[0]['doc_firstname']) . ' ' . libsodiumDecrypt($result[0]['doc_last_name']);
        $result[0]['patient_name'] = libsodiumDecrypt($result[0]['pat_first_name']) . ' ' . libsodiumDecrypt($result[0]['pat_last_name']);
        $result[0]['clinic_name'] = ($result[0]['clinic_name']) ? libsodiumDecrypt($result[0]['clinic_name']): (libsodiumDecrypt($hospitalInfo['first_name']).' '.libsodiumDecrypt($hospitalInfo['last_name']));
        $result[0]['from_date_time'] = $result[0]['from_date_time'];
        $result[0]['reason'] = $result[0]['reason'];
        $result[0]['diagnosis'] = $result[0]['diagnosis'];
        echo json_encode($result);
    }
    /**
     * Edit Prescription.
     * 
     * @param mixed $prescription_id
     * @param mixed $patient_id
     * @return mixed
     */
    public function editPrescription($prescription_id, $patient_id)
    {
        $this->data['page'] = 'edit_prescription';
        $this->data['patient_id'] = base64_decode($patient_id);
        $this->data['prescription'] = $this->mypatientsModel->getPrescription(base64_decode($prescription_id));
        echo view('user/doctor/edit_prescription', $this->data);
    }
    /**
     * Insert Signature.
     * 
     * 
     * @return mixed
     */
    public function insertSignature()
    {
        // echo "<pre>";print_r($this->request->getPost());exit;
        $img = $this->request->getPost('image');
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img ?? "");
        if (!is_dir('./uploads/signature-image')) {
            mkdir('./uploads/signature-image', 0777, TRUE);
        }
        $file = './uploads/signature-image/' . uniqid() . '.png';
        $success = file_put_contents($file, $data);
        $image = str_replace('./', '', $file);
        $check = $this->getSingleSigns($this->request->getPost());
        if ($check == 0) {
            $data = array('img' => $image, 'rowno' => $this->request->getPost('rowno'));
            $result = $this->commonModel->insertData('signature', $data);
            $id = $result['id'];
        } else {
            $data = array('img' => $image);
            $this->commonModel->updateData('signature', array('rowno' => $this->request->getPost('rowno')), $data);
            $result = $this->commonModel->getTblRowOfData('signature', array('rowno' => $this->request->getPost('rowno')), '*');
            $id = $result['id'];
        }
        echo '<div id="edit">
                <img src="' . base_url() . $image . '" style="width:180px;height:85px;">
            <div class="edit" style="position: absolute;">
                <i class="fa fa-edit" onclick="show_modal()" title="' . $this->language['lg_click_here_to_e'] . '"></i>
            </div>
            <input type="hidden" name="signature_id" value="' . $id . '" id="signature_id">
            </div>';
    }
    /**
     * Get Single Signs.
     * 
     * @param mixed $input
     * @return mixed
     */
    public function getSingleSigns($input)
    {
        $datas = array('rowno' => $input['rowno']);
        return $this->commonModel->countTblResult('signature', $datas);
    }
    /**
     * Update Prescription.
     * 
     * 
     * @return mixed
     */
    public function updatePrescription()
    {

        $prescription_id = $this->request->getPost('prescription_id');

        $data = array(
            'signature_id' => $this->request->getPost('signature_id'),
        );
        $this->commonModel->updateData('prescription', array('id' => $prescription_id), $data);
        $where = array('prescription_id' => $prescription_id);
        $this->commonModel->deleteData('prescription_item_details', $where);

        $drug_name = $this->request->getPost('drug_name');
        $qty = $this->request->getPost('qty');
        $type = $this->request->getPost('type');
        $days = $this->request->getPost('days');
        $time = $this->request->getPost('time');
        $rowval = $this->request->getPost('rowValue');


        for ($i = 0; $i < count($drug_name); $i++) {
            $time = '';
            $j = $i + 1;
            /*if(!empty($_POST['time'.$j])){
                $time = implode(',',$_POST['time'.$j]);
            } */

            $arrval = $rowval[$i];
            if (!empty($_POST['time'][$arrval])) {
                $time = implode(',', $_POST['time'][$arrval]);
            }

            $datas = array(
                'prescription_id' => $prescription_id,
                'drug_name' => $drug_name[$i],
                'qty' => $qty[$i],
                'type' => $type[$i],
                'days' => $days[$i],
                'time' => $time,
                'created_at'  => date('Y-m-d H:i:s')
            );
            $data = $this->commonModel->insertData('prescription_item_details', $datas);
        }
        if ($data) {
            $result = true;
        }
        if ($result == true) {

            $response['msg'] = $this->language['lg_prescription_up1'];
            $response['status'] = 200;
            $response['patient_id'] = base64_encode($this->request->getPost('patient_id') ?? "");
        } else {
            $response['msg'] = $this->language['lg_prescription_up'];
            $response['status'] = 500;
        }


        $notification = array(
            'user_id' => session('user_id'),
            'to_user_id' => $this->request->getPost('patient_id'),
            'type' => "Prescription",
            'text' => "has prescription updated to",
            'created_at' => date("Y-m-d H:i:s"),
            'time_zone' => session('time_zone')
        );
        $data = $this->commonModel->insertData('notification', $notification);
        echo json_encode($response);
    }
    /**
     * Add Prescription.
     * 
     * @param mixed $patient_id
     * @return mixed
     */
    public function addPrescription($patient_id)
    {
        $this->data['page'] = 'add_prescription';
        $this->data['patient_id'] = base64_decode($patient_id);
        $this->data['appointments'] = $this->mypatientsModel->getPatientAppointmentFromLastWeek($patient_id);
        echo view('user/doctor/add_prescription', $this->data);
    }
    /**
     * Save Prescription.
     * 
     * 
     * @return mixed
     */
    public function savePrescription()
    {
        $data = array(
            'doctor_id' => session('user_id'),
            'patient_id' => $this->request->getPost('patient_id'),
            'signature_id' => $this->request->getPost('signature_id'),
            'appointment_id' => $this->request->getPost('appointment_id'),
            'diagnosis' => $this->request->getPost('diagnosis'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $prescritpionExist = $this->commonModel->checkTblDataExist('prescription',
         ['appointment_id' => $data['appointment_id']], 'id');
         if($prescritpionExist){
            $response['msg'] = 'Appointment has prescription';
            $response['status'] = 300;
            echo json_encode($response);
            return;
         }
        $result = $this->commonModel->insertData('prescription', $data);

        $prescription_id = $result['id'];

        $drug_name = $this->request->getPost('drug_name');
        $qty = $this->request->getPost('qty');
        $type = $this->request->getPost('type');
        $days = $this->request->getPost('days');
        $time = $this->request->getPost('time');
        $rowval = $this->request->getPost('rowValue');

        for ($i = 0; $i < count($drug_name); $i++) {
            $time = '';
            $j = $i + 1;
            /*if(!empty($_POST['time'.$j])){
            $time = implode(',',$_POST['time'.$j]);
            }*/

            $arrval = $rowval[$i];
            if (!empty($_POST['time'][$arrval])) {
                $time = implode(',', $_POST['time'][$arrval]);
            }

            $datas = array(
                'prescription_id' => $prescription_id,
                'drug_name' => $drug_name[$i],
                'qty' => $qty[$i],
                'type' => $type[$i],
                'days' => $days[$i],
                'time' => $time,
                'created_at'  => date('Y-m-d H:i:s')
            );

            $result = $this->commonModel->insertData('prescription_item_details', $datas);
        }



        // Notification

        $notification = array(
            'user_id' => session('user_id'),
            'to_user_id' => $this->request->getPost('patient_id'),
            'type' => "Prescription",
            'text' => "has prescription to",
            'created_at' => date("Y-m-d H:i:s"),
            'time_zone' => session('time_zone')
        );
        $output = $this->commonModel->insertData('notification', $notification);
        if ($output) {
            $result = true;
        }
        if ($result == true) {

            $response['msg'] = $this->language['lg_prescription_ad1'];
            $response['status'] = 200;
            $response['patient_id'] = base64_encode($this->request->getPost('patient_id') ?? "");
        } else {
            $response['msg'] = $this->language['lg_prescription_ad'];
            $response['status'] = 500;
        }



        echo json_encode($response);
    }
    /**
     * Upload Medical Records.
     * 
     * 
     * @return mixed
     */
    public function uploadMedicalRecords()
    {
        $data = array();
        //ob_flush();
        $med_rec_updated = '';
        $doctor_id = session('user_id');
        $patient_id = $this->request->getPost('patient_id');
        $description = $this->request->getPost('description');
        $medical_record_id = $this->request->getPost('medical_record_id');

        if ($_FILES["user_file"]["name"] != '') {
            if (!is_dir('./uploads/medical_records')) {
                mkdir('./uploads/medical_records', 0777, TRUE);
            }
            $file = $this->request->getFile('user_file');
            $description_filename = $file->getRandomName();
            $file->move('uploads/medical_records', $description_filename);
            $uploaded_file = 'uploads/medical_records/' . $description_filename;
            $data += array('file_name' => $uploaded_file);
        }

        $data += array(
            'date' =>  date('Y-m-d H:i:s'),
            'description' => libsodiumEncrypt($description),
            'doctor_id' => $doctor_id,
            'patient_id' => $patient_id
        );

        if ($medical_record_id == '') {
            $output = $this->commonModel->insertData('medical_records', $data);
            $med_rec_updated = 0;
        } else if ($medical_record_id > 0) {
            $med_rec_updated = $this->mypatientsModel->updateData('medical_records', array('id' => $medical_record_id), $data);
        }

        $notification = array(
            'user_id' => $doctor_id,
            'to_user_id' => $patient_id,
            'type' => "Medical-records",
            'text' => "has medical records to",
            'created_at' => date("Y-m-d H:i:s"),
            'time_zone' => session('time_zone')
        );
        $output = $this->commonModel->insertData('notification', $notification);
        if ($output) {
            $result = true;
        }
        if ($result == true && $med_rec_updated == 0) {

            $response['msg'] = $this->language['lg_medical_records2'];
            $response['status'] = 200;
        } else if ($result == true && $med_rec_updated > 0) {

            $response['msg'] = $this->language['lg_medical_records3'];
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_medical_records1'];
            $response['status'] = 500;
        }

        echo json_encode($response);
    }
    /**
     * Medical Records List.
     * 
     * 
     * @return mixed
     */
    public function medicalRecordsList()
    {
        $input = $this->request->getPost();
        $list = $this->mypatientsModel->getMedicalRecordDatatables($input);
        $data = array();
        $no = $input['start'];
        $a = $no + 1;
        $b = 1;
        if (!empty($list)) {
            foreach ($list as $medical_records) {
                if ($medical_records['profileimage'] == "" || ($medical_records['profileimage'] != "" && !is_file($medical_records['profileimage']))) {
                    $profile_image = base_url() . 'assets/img/user.png';
                } else {
                    $profile_image = (!empty($medical_records['profileimage'] ?? "")) ? base_url() . $medical_records['profileimage'] ?? "" : base_url() . 'assets/img/user.png';
                }
                $no++;
                $row = array();
                $row[] = $a++;
                $row[] = date('d M Y', strtotime($medical_records['date']));
                // $row[] =$medical_records['description'];
                $row[] = '<a onclick="view_dec(' . $medical_records['id'] . ');"  class="btn btn-primary btn-sm">Description</a>';
                $row[] = '<a href="' . base_url() . $medical_records['file_name'] . '" target="_blank" title="' . $this->language['lg_download_attach'] . '" class="btn btn-primary btn-sm" download>' . $this->language['lg_download'] . '<i class="fa fa-download"></i></a>';

                if (is_doctor() || is_clinic()) {

                    $row[] = '<h2 class="table-avatar">
                        <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($medical_records['username'])) . '" class="avatar avatar-sm mr-2">
                            <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>
                        <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($medical_records['username'])) . '">' . $this->language['lg_dr'] . ' ' . ucfirst(libsodiumDecrypt($medical_records['first_name']) . ' ' . libsodiumDecrypt($medical_records['last_name'])) . ' <span>' . ucfirst(libsodiumDecrypt($medical_records['specialization'])) . '</span></a>
                        </h2>
                        ';

                    $row[] = '<div class="table-action">
                        <a href="' . base_url() . $medical_records['file_name'] . '" target="_blank"  class="btn btn-sm bg-info-light">
                            <i class="fas fa-eye"></i> ' . $this->language['lg_view1'] . '
                        </a>';


                    if (is_doctor()) {
                        $row[] = '<a href="#" class="btn btn-sm bg-success-light" onclick="edit_medi_rec(' . $medical_records['id'] . ')"  ><i class="fas fa-edit"></i> ' . $this->language['lg_edit2'] . '</a>
                        <a href="javascript:void(0);" onclick="delete_medical_records(' . $medical_records['id'] . ')" class="btn btn-sm bg-danger-light">
                            <i class="far fa-trash-alt"></i> ' . $this->language['lg_delete'] . '
                        </a>';
                    }

                    $row[] = '</div>';
                }

                if (is_patient()) {

                    $row[] = '<h2 class="table-avatar">
                        <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($medical_records['username'])) . '" class="avatar avatar-sm mr-2">
                            <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                        </a>
                        <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($medical_records['username'])) . '">' . ucfirst(libsodiumDecrypt($medical_records['first_name']) . ' ' . libsodiumDecrypt($medical_records['last_name'])) . ' <span>' . ucfirst(libsodiumDecrypt($medical_records['specialization'])) . '</span></a>
                        </h2>
                        ';

                    $row[] = '<div class="table-action">
                        <a href="' . base_url() . $medical_records['file_name'] . '" target="_blank"  class="btn btn-sm bg-info-light">
                            <i class="fas fa-eye"></i> ' . $this->language['lg_view1'] . '
                        </a>
                        
                        
                        </div>';
                }

                $data[] = $row;
            }
        }



        $output = array(
            "draw" => $input['draw'],
            "recordsTotal" => $this->mypatientsModel->medicalRecordCountAll($input),
            "recordsFiltered" => count($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * View Doccuments.
     * 
     * 
     * @return mixed
     */
    public function viewDec()
    {
        $id = $this->request->getPost('id');
        $description = $this->commonModel->getTblRowOfData('medical_records', array('id' => $id), 'description');
        echo ($description['description']) ? libsodiumDecrypt($description['description']) : $this->language['lg_no_record_found'];
        exit;
    }
    /**
     * Billing List.
     * 
     * 
     * @return mixed
     */
    public function billingList()
    {
        $input = $this->request->getPost();
        $list = $this->mypatientsModel->getBillingDatatables($input);
        $data = array();
        $no = $input['start'];
        $a = $no + 1;
        $b = $no + 1;
        foreach ($list as $billings) {
            if ($billings['profileimage'] == "" || ($billings['profileimage'] != "" && !is_file($billings['profileimage']))) {
                $profile_image = base_url() . 'assets/img/user.png';
            } else {
                $profile_image = (!empty($billings['profileimage'] ?? "")) ? base_url() . $billings['profileimage'] ?? "" : base_url() . 'assets/img/user.png';
            }
            $no++;
            $row = array();
            $row[] = $a++;
            $row[] = date('d M Y', strtotime($billings['created_at']));
            $row[] = 'Billno ' . $b++;
            $row[] = '<h2 class="table-avatar">
                    <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($billings['username'])) . '" class="avatar avatar-sm mr-2">
                        <img class="avatar-img rounded-circle" src="' . $profile_image . '" alt="User Image">
                    </a>
                    <a href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($billings['username'])) . '">' . $this->language['lg_dr'] . ' ' . ucfirst(libsodiumDecrypt($billings['first_name']) . ' ' . libsodiumDecrypt($billings['last_name'])) . ' <span>' . ucfirst(libsodiumDecrypt($billings['specialization'])) . '</span></a>
                    </h2>';

            $html = '<div class="table-action">
                    <a target="_blank" href="' . base_url() . 'my_patients/print-billing/' . base64_encode($billings['id']) . '" class="btn btn-sm bg-primary-light">
                        <i class="fas fa-print"></i> ' . $this->language['lg_print'] . '
                    </a>
                    <a href="javascript:void(0);" onclick="view_billing(' . $billings['id'] . ')" class="btn btn-sm bg-info-light">
                        <i class="far fa-eye"></i> ' . $this->language['lg_view1'] . '
                    </a>';
            if (is_doctor()) {
                $html .= '<a href="' . base_url() . 'my_patients/edit-billing/' . base64_encode($billings['id']) . '/' . base64_encode($billings['patient_id']) . '" class="btn btn-sm bg-success-light">
                        <i class="fas fa-edit"></i> ' . $this->language['lg_edit2'] . '
                    </a>
                    <a href="javascript:void(0);" onclick="delete_billing(' . $billings['id'] . ')" class="btn btn-sm bg-danger-light">
                        <i class="far fa-trash-alt"></i> ' . $this->language['lg_delete'] . '
                    </a>';
            }
            $html .= '</div>';

            $row[] = $html;


            $data[] = $row;
        }



        $output = array(
            "draw" => $input['draw'],
            "recordsTotal" => $this->mypatientsModel->billingCountAll($input),
            "recordsFiltered" => count($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Billing Add.
     * 
     * @param mixed $patient_id
     * @return mixed
     */
    public function addBilling($patient_id)
    {
        $this->data['page'] = 'add_billing';
        $this->data['patient_id'] = base64_decode($patient_id);
        echo view('user/doctor/add_billing', $this->data);
    }
    /**
     * Save Billing
     * 
     * 
     * @return mixed
     */
    public function saveBilling()
    {
        $data = array(
            'doctor_id' => session('user_id'),
            'patient_id' => $this->request->getPost('patient_id'),
            'signature_id' => $this->request->getPost('signature_id'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $output = $this->commonModel->insertData('billing', $data);

        $billing_id = $output['id'];

        $name = $this->request->getPost('name');
        $amount = $this->request->getPost('amount');

        for ($i = 0; $i < count($name); $i++) {
            $datas = array(
                'billing_id' => $billing_id,
                'name' => $name[$i],
                'amount' => $amount[$i],
                'created_at'  => date('Y-m-d H:i:s')
            );
            $output = $this->commonModel->insertData('billing_item_details', $datas);
            if ($output) {
                $result = true;
            }
        }

        if ($result == true) {

            $response['msg'] = $this->language['lg_billing_added_s'];
            $response['status'] = 200;
            $response['patient_id'] = base64_encode($this->request->getPost('patient_id') ?? "");
        } else {
            $response['msg'] = $this->language['lg_billing_added_f'];
            $response['status'] = 500;
        }



        echo json_encode($response);
    }
    /**
     * Get Billing Details.
     * 
     * 
     * @return mixed
     */
    public function getBillingDetails()
    {
        $billing_id = $this->request->getPost('id');
        $result = $this->mypatientsModel->getBillingDetails($billing_id);
        if (!empty($result)) {

            // convert amount to user currency
            foreach ($result as $key => $value) {
                $result[$key]['doctor_name'] = libsodiumDecrypt($value['doc_firstname']) . ' ' . libsodiumDecrypt($value['doc_last_name']);
                $result[$key]['patient_name'] = libsodiumDecrypt($value['pat_first_name']) . ' ' . libsodiumDecrypt($value['pat_last_name']);
                $result[$key]['amount'] = convert_to_user_currency($value['amount']);
            }
            // convert amount to user currency
        }
        echo json_encode($result);
    }
    /**
     * Edit Billing Details.
     * 
     * @param mixed $billing_id
     * @param mixed $patient_id
     * @return mixed
     */
    public function editBilling($billing_id, $patient_id)
    {
        $this->data['page'] = 'edit_billing';
        $this->data['patient_id'] = base64_decode($patient_id);
        $this->data['billing'] = $this->mypatientsModel->getBilling(base64_decode($billing_id));
        echo view('user/doctor/edit_billing', $this->data);
    }
    /**
     * Update Billing Details.
     * 
     * 
     * @return mixed
     */
    public function updateBilling()
    {
        $output = '';
        $billing_id = $this->request->getPost('billing_id');
        $data = array(
            'signature_id' => $this->request->getPost('signature_id'),
        );
        $this->commonModel->updateData('billing', array('id' => $billing_id), $data);


        $where = array('billing_id' => $billing_id);
        $this->commonModel->deleteData('billing_item_details', $where);

        $name = $this->request->getPost('name');
        $amount = $this->request->getPost('amount');

        for ($i = 0; $i < count($name); $i++) {
            $datas = array(
                'billing_id' => $billing_id,
                'name' => $name[$i],
                'amount' => $amount[$i],
                'created_at'  => date('Y-m-d H:i:s')
            );
            $output = $this->commonModel->insertData('billing_item_details', $datas);
        }
        if ($output) {
            $result = true;
        }
        if ($result == true) {
            $response['msg'] = $this->language['lg_billing_update_'];
            $response['status'] = 200;
            $response['patient_id'] = base64_encode($this->request->getPost('patient_id') ?? "");
        } else {
            $response['msg'] = $this->language['lg_billing_update_not'];
            $response['status'] = 500;
        }

        echo json_encode($response);
    }
    /**
     * Print Billing Details.
     * 
     * @param mixed $billing_id
     * @return mixed
     */
    public function printBilling($billing_id)
    {
        $data['billing'] = $this->mypatientsModel->getBillingDetails(base64_decode($billing_id));
        $data['language'] = $this->language;
        // echo view('user/doctor/print_billing',$data);
        $mpdf = new \Mpdf\Mpdf();
        $html = view('user/doctor/print_billing', $data);
        $mpdf->WriteHTML($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $fileName = 'bill - ' . date('Y-m-d H:i:s');
        $mpdf->Output($fileName, 'I');
    }
    /**
     * Print Medical Records.
     * 
     * @param mixed $medical_records_id
     * @return mixed
     */
    public function printMedicalRecords($medical_records_id)
    {
        $medical_records = $this->mypatientsModel->getMedicalRecordsDetails(base64_decode($medical_records_id));
        return redirect(base_url() . $medical_records[0]['file_name']);
    }
}
