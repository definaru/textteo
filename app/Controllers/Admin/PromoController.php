<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\CommonModel;
use App\Models\PromoModel;
class PromoController extends BaseController
{
    public  $data;
    public  $session;
    public  $commonModel;
    public  $promoModel;
    public $language;
    public $appointmentModel;
    protected string $table = 'coupons a';
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
        $this->data['module'] = 'promo';
        $this->data['page'] = '';

        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);
        //Define Model
        $this->commonModel = new CommonModel();
        $this->appointmentModel = new AppointmentModel();
        $this->promoModel = new PromoModel();
    }

    /**
     * load appointment page.
     *
     * @return mixed
     */
    public function index()
    {
        $list = $this->promoModel->getPromocodes();
        $this->data['promocodes'] = $list;
        $this->data['page'] = 'index';
        echo view('admin/promo/index', $this->data);
    }

    /**
     *  Add Promo
     *
     * @return mixed
     */
    public function promoAdd()
    {
        $inputdata = array();
        $response = array();
        $user_id = session('user_id');

        $inputdata['coupon'] = $this->request->getPost('title');
        $inputdata['discount_type'] = $this->request->getPost('type');
        $inputdata['discount'] = $this->request->getPost('value');
        $inputdata['active'] = 1;
        $result = $this->commonModel->insertData('coupons', $inputdata);

        if ($result == true) {
            $response['msg'] = 'Coupon add sucessfully!';
            $response['status'] = 200;
        } else {
            $response['msg'] = 'DB error';
            $response['status'] = 500;
            session()->setFlashdata('failed_message', 'DB error');
        }
        echo json_encode($response);
    }
    public function promoEdit()
    {
        $response = array();
        $system_coupons = $this->promoModel->getPromocodes();
        foreach($system_coupons as $coupon){
            if($coupon['id'] == $this->request->getPost('id')){
                if($coupon['active'] == 1){
                   $result = $this->commonModel->updateData('coupons',['id'=> $this->request->getPost('id')],['active'=> '0']);
                }else{
                   $result = $this->commonModel->updateData('coupons',['id'=> $this->request->getPost('id')],['active'=> '1']);
                }
            }
        }


        if ($result == true) {
            $response['msg'] = 'Coupon add sucessfully!';
            $response['status'] = 200;
        } else {
            $response['msg'] = 'DB error';
            $response['status'] = 500;
            session()->setFlashdata('failed_message', 'DB error');
        }
        echo json_encode($response);
    }
}
