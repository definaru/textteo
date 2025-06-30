<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\CommonModel;
use App\Models\HomeModel;
use App\Models\MypatientsModel;


class ReviewController extends BaseController
{
    public $data;
    public $session;
    /**
     * @var \App\Models\ReviewModel
     */
    public $reviewModel;
    public $language;
    public $db;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    /**
     * @var \App\Models\HomeModel
     */
    public $homeModel;
    /**
     * @var \App\Models\MypatientsModel
     */
    public $myPatientsModel;
    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium', 'ckeditor']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'reviews';
        $this->data['page'] = '';
        $this->data['uri'] = service('uri');
        $lan = helper('default_language');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        // //Define Model
        $this->reviewModel = new ReviewModel();
        $this->commonModel = new CommonModel();
        $this->homeModel = new HomeModel();
        $this->myPatientsModel = new MypatientsModel();
    }
    /**
     * Review Page.
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        return view('admin/reviews/index', $this->data);
    }
    /**
     * Review List.
     * 
     * @return mixed
     */
    public function reviews_list()
    {
        $list = $this->reviewModel->getReviewList();
        $data = array();
        $no = $_POST['start'];
        $a = 1;
        foreach ($list as $reviews) {
            $decryptFirstName = libsodiumDecrypt($reviews['first_name']);
            $decryptLastName = libsodiumDecrypt($reviews['last_name']);
            $doctorName = $decryptFirstName . ' ' . $decryptLastName;
            $decryptPatientFirstName = libsodiumDecrypt($reviews['patient_first_name']);
            $decryptPatientLastName = libsodiumDecrypt($reviews['patient_last_name']);
            $patientName = $decryptPatientFirstName . ' ' . $decryptPatientLastName;
            $rating = $reviews['rating'];
            $ratings = '';
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    $ratings .= '<i class="fe fe-star text-warning"></i>';
                } else {
                    $ratings .= '<i class="fe fe-star-o text-secondary"></i>';
                }
            }

            $doctor_profileimage = (!empty($reviews['doctor_profileimage'])) && file_exists($reviews['doctor_profileimage']) ? base_url() . $reviews['doctor_profileimage'] : base_url() . 'assets/img/user.png';
            $patient_profileimage = (!empty($reviews['patient_profileimage'])) && file_exists($reviews['patient_profileimage']) ? base_url() . $reviews['patient_profileimage'] : base_url() . 'assets/img/user.png';
            $no++;
            $row = array();
            $row[] = $no;

            $row[] = '<h2 class="table-avatar">
                  <a href="javascript:void(0)" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
                  <a href="javascript:void(0)">' . $patientName . ' </a>
                </h2>';
            $row[] = '<h2 class="table-avatar">
                  <a target="_blank" href="' . base_url() . 'doctor-preview/' . $reviews['doctor_username'] . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>
                  <a target="_blank" class="text" href="' . base_url() . 'doctor-preview/' . $reviews['doctor_username'] . '">Dr. ' . $doctorName . '</a>
                </h2>';
            $row[] = $ratings;
            $row[] = $reviews['review'];

            $row[] = date('d M Y', strtotime($reviews['created_date']));
            $row[] = '<div class="actions text-right">
                              <a class="btn btn-sm bg-danger-light" onclick="delete_reviews(' . $reviews['id'] . ')">
                                <i class="fe fe-trash"></i> Delete
                              </a>
                              
                  </div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->reviewModel->reviews_count_all(),
            "recordsFiltered" => $this->reviewModel->reviews_count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Review Delete.
     * 
     * @return mixed
     */
    public function reviews_delete()
    {
        $id = $this->request->getPost('reviews_id');
        $where = array('id' => $id);
        $this->commonModel->deleteData('rating_reviews', $where);
    }

    /**
     * Admin notifications.
     * 
     * @return mixed
     */
    public function adminNotificationPage()
    {
        $this->data['page'] = 'notification';
        return view('admin/dashboard/notification', $this->data);
    }

    /**
     * Admin email template.
     * 
     * @return mixed
     */
    public function adminEmailTemplate()
    {
        $this->data['page'] = 'index';
        return view('admin/email_template/index', $this->data);
    }

    /**
     * Email template List.
     * 
     * @return mixed
     */
    public function emailTemplateList()
    {
        $list = $this->reviewModel->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $email_template) {

            $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $email_template['template_title'];
            $edit_url = 'email-template-edit/' . base64_encode($email_template['template_id']);
            $row[] = '<div class="actions">
                      <a  class="btn btn-sm bg-success-light" href="' . $edit_url . '" >
                        <i class="fe fe-pencil"></i> Edit
                      </a>
                      
                    </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->reviewModel->count_all(),
            "recordsFiltered" => $this->reviewModel->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Email Template Edit.
     * 
     *  @param mixed $id
     *  @return mixed
     */
    public function emailTemplateEdit($id)
    {
        helper('ckeditor');
        // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor1'] = array(
            //id of the textarea being replaced by CKEditor
            'id'   => 'ck_editor_textarea_id',
            // CKEditor path from the folder on the root folder of CodeIgniter
            'path' => 'assets/js/ckeditor',
            // optional settings
            'config' => array(
                'toolbar' => "Full",
                'filebrowserBrowseUrl'      => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl'      => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );
        if (!empty($id)) {
            $this->data['page'] = 'edit';
            $this->data['edit_data'] = $this->reviewModel->edit_template(base64_decode($id));
            return view('admin/email_template/edit', $this->data);
        }
    }
    /**
     * Email Template Update.
     * 
     *  @param int $id
     *  @return mixed
     */
    public function edit($id)
    {
        if ($this->request->getPost('form_submit')) {
            $data['template_content'] = $this->request->getPost('template_content');
            $data['template_subject'] = $this->request->getPost('template_subject');
            if ($this->homeModel->updateData('email_templates', ['template_id' => $id], $data)) {
                session()->setFlashdata('success_message', 'Email Template Update Successfully');
                return redirect()->to('admin/email-template');
            }
        }
    }
    /**
     * Admin Search Notification.
     * 
     *  @return mixed
     */
    public function adminSearchNotification()
    {
        $response = array();
        $result = array();
        $page = $this->request->getPost('page');
        $limit = 5;
        $response['count'] = $this->reviewModel->getNotification($page, $limit, 1);
        $notification_list = $this->reviewModel->getNotification($page, $limit, 2);

        if (!empty($notification_list)) {
            foreach ($notification_list as $rows) {
                $decryptFirstName = libsodiumDecrypt($rows['first_name']);
                $decryptLastName = libsodiumDecrypt($rows['last_name']);
                $doctorName = $decryptFirstName . ' ' . $decryptLastName;
                $decryptPatientFirstName = libsodiumDecrypt($rows['patient_first_name']);
                $decryptPatientLastName = libsodiumDecrypt($rows['patient_last_name']);
                $patientName = $decryptPatientFirstName . ' ' . $decryptPatientLastName;
                $data['id'] = $rows['id'];
                $data['from_name'] = $patientName;
                $data['profile_image'] = (!empty($rows['profile_image'])) ? base_url() . $rows['profile_image'] : base_url() . 'assets/img/user.png';
                $data['to_name'] = ucfirst($doctorName);
                $data['text'] = ucfirst($rows['text']);
                $data['type'] = $rows['type'];
                $data['notification_date'] = time_elapsed_string($rows['notification_date']);
                $result[] = $data;
            }
        }
        $response['current_page_no'] = $page;
        $response['total_page'] = ceil($response['count'] / $limit);
        $response['data'] = $result;

        echo json_encode($response);
    }
    /**
     * Admin Delete Notification.
     * 
     *  @return mixed
     */
    public function deleteNotification()
    {
        $id = $this->request->getPost('id');
        if ($id === 0) {
            $this->homeModel->deleteData('notification', ['is_viewed' => 1]);
            $response['status'] = 200;
            $response['msg'] = "Deleted successfully";
        } else {
            $this->homeModel->deleteData('notification', ['id' => $id]);
            $response['status'] = 200;
            $response['msg'] = "Deleted successfully";
        }
        echo json_encode($response);
    }
    /**
     * Admin Update Notification.
     * 
     *  @return mixed
     */
    public function updateNotification()
    {
        $data = array('is_viewed' => 1);
        $this->homeModel->updateData('notification', ['is_viewed' => 0], $data);
        $response['status'] = 200;
        $response['msg'] = "Updated successfully";
        echo json_encode($response);
    }
    /**
     * Admin Pharmacy Preview.
     * 
     *  @param mixed $pharmacy_id
     *  @return mixed
     */
    public function pharmacyPreview($pharmacy_id = '')
    {
        $pharmacy_id = encryptor_decryptor('decrypt', $pharmacy_id);
        $this->data['pharmacy'] = $this->myPatientsModel->getSelectedPharmacyDetails($pharmacy_id);
        $this->data['page'] = 'pharmacy_preview';
        return view('user/home/pharmacyPreview', $this->data);
    }
}
