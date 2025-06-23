<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\HomeModel;
use App\Models\UserModel;
use App\Models\OrdersModel;

class InvoiceController extends BaseController
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
     * @var \App\Models\HomeModel
     */
    public $homeModel;
    /**
     * @var \App\Models\OrdersModel
     */
    public $ordersModel;

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

        $this->homeModel = new HomeModel();
        $this->userModel = new UserModel();
        $this->ordersModel = new OrdersModel();
    }
    /**
     * Invoice List Page.
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'invoice';
        return view('user/invoice/invoiceList',  $this->data);
    }
    /**
     * Get List Of Invoice
     * 
     * @return mixed
     */
    public function invoiceList()
    {
        $user_id = session('user_id');
        $list = $this->userModel->invoiceList($user_id);
        // print_r($this->db->last_query());exit();
        $data = array();
        $no = $_POST['start'];
        $a = 0;

        foreach ($list as $invoices) {
            $a++;
            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            // $user_currency_rate=$user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);
            if (session('role') == '5') {
                $order_details = $this->homeModel->getTblResultOfData('pharmacy_payments', ['doctor_id' => session('user_id'),'payment_id' => $invoices['id']], "sum(total_amount) as total_amount");
                $inv_amt = number_format($order_details[0]['total_amount'],2);
                $org_amount = get_doccure_currency($inv_amt, $invoices['currency_code'], $user_currency_code);
            } else {
                $org_amount = get_doccure_currency($invoices['total_amount'], $invoices['currency_code'], $user_currency_code);
            }
            $doctor_profileimage = (!empty($invoices['doctor_profileimage']) && file_exists(FCPATH . $invoices['doctor_profileimage'])) ? base_url() . $invoices['doctor_profileimage'] : base_url() . 'assets/img/user.png';
            $patient_profileimage = (!empty($invoices['patient_profileimage']) && file_exists(FCPATH . $invoices['patient_profileimage'])) ? base_url() . $invoices['patient_profileimage'] : base_url() . 'assets/img/user.png';

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url() . 'invoice-view/' . base64_encode($invoices['id']) . '">' . $invoices['invoice_no'] . '</a>';

            if (session('role') == '1' || session('role') == '4' || session('role') == '5' || session('role') == '6') {
                $href_link = 'javascript:;';
                $target = '';
                if (session('role') == '1') {
                    $href_link = base_url() . 'patient-preview/' . base64_encode($invoices['patient_id']);
                    $target = 'target="_blank"';
                }
                $row[] = '<h2 class="table-avatar">
                <a ' . $target . ' href="' . $href_link . '" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
                <a ' . $target . ' href="' . $href_link . '">' . ucfirst(libsodiumDecrypt($invoices['patient_first_name']) . ' ' . libsodiumDecrypt($invoices['patient_last_name'])) . ' </a>
                </h2>';
            }

            if (session('role') == '2') {
                $user_role = '';
                $img = '';
                $roleTitle = "";
                $roleLink = "";

                if ($invoices['role'] == 4) {
                    $roleTitle = "Lab";
                    $roleLink = 'lab-tests/' . encryptor_decryptor('encrypt', libsodiumDecrypt($invoices['doctor_username']));
                } else if ($invoices['role'] == 6) {
                    $roleTitle = "Clinic";
                    $roleLink = 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($invoices['doctor_username']));
                } else if ($invoices['role'] == 1) {
                    $roleTitle = "Doctor";
                    $roleLink = 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($invoices['doctor_username']));
                } else {
                    $roleTitle = "Pharmacy";
                    $roleLink = 'pharmacy-preview/' . encryptor_decryptor('encrypt', $invoices["doctor_id"]);
                    // $roleLink = "javascript:void(0);";
                }
                if ($invoices['role'] == '1') {
                    $user_role = $this->language['lg_dr'];
                    $img = '<a target="_blank" href="' . base_url() . $roleLink . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                    </a>';
                } else {
                    $img = '<a target="_blank" href="' . base_url() . $roleLink . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                    </a>';
                }
                $row[] = '<h2 class="table-avatar">
                ' . $img . '
                <a style="display:grid" target="_blank" href="' . base_url() . $roleLink . '">' . $user_role . ' ' . ucfirst(libsodiumDecrypt($invoices['doctor_first_name']) . "" . libsodiumDecrypt($invoices['doctor_last_name'])) . '
                <small>' . $roleTitle . '</small>
                </a>
                </h2>';
            }
            $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
            $row[] = $invoices['transaction_status'] == 'complete'? 
            '<span style="color:#00BD45;font-weight:500;font-size:14px;font:Poppins">Paid</span>' 
            : '<span style="color:#FD9720;font-weight:500;font-size:14px;font:Poppins">Awaiting payment</span>';//date('d M Y', strtotime($invoices['payment_date']));
           
            $action_menu = $invoices['transaction_status'] == 'complete' ? '
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
  z-index: 999999;
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
</style>  


 <div class="dropdown">
                    <button onclick="toggleDropdown(this)" class="btn p-0 border-0 bg-transparent">&#x22EE;</button>
                    <div class="dropdown-content">
                        <a id="download" href="' .base_url() . 'invoice-print/' . base64_encode($invoices['id']) . '" target="blank">download check</a>  
                        <a href="#">Cancel Appointment</a>
                    </div>
                </div>
' :
'
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
</style>  


 <div class="dropdown">
                    <button onclick="toggleDropdown(this)" class="btn p-0 border-0 bg-transparent">&#x22EE;</button>
                    <div class="dropdown-content">
                        <a href="#">Pay</a>
                    </div>
                </div>
';


            $row[] = $action_menu;
            $row[] = $invoices['payment_date'];
            $row[] = $invoices['transaction_status'] == 'complete' ? 
            'Paid': 
            'Awaiting payment';
            $row[] = '<div class="table-action">
            <a href="' . base_url() . 'invoice-view/' . base64_encode($invoices['id']) . '" class="btn btn-sm bg-info-light">
            <i class="far fa-eye"></i> ' . $this->language['lg_view1'] . '
            </a>
            <a href="' . base_url() . 'invoice-print/' . base64_encode($invoices['id']) . '" class="btn btn-sm bg-primary-light" target="blank">
            <i class="fas fa-print"></i> ' . $this->language['lg_print'] . '
            </a>
            </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $a,
            "recordsFiltered" => $this->userModel->countAllInvoice($user_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Invoice Detail Page
     * 
     * 
     * @param mixed $invoice_id
     * @return mixed
     */
    public function invoiceDetail($invoice_id)
    {
        $this->data['invoices'] = $this->homeModel->getInvoiceDetails(base64_decode($invoice_id));
        $this->data['role'] = $this->data['invoices']['role'];

        // if ($this->data['invoices']['doctor_id'] == session('user_id') || $this->data['invoices']['user_id'] == session('user_id')) {
            if ($this->data['invoices']['role'] == '5') {
                $this->data['invoice_no'] = $this->data['invoices']['invoice_no'];
                $this->data['page'] = 'invoice_pharmacy_view';
                $this->data['invoices'] = $this->homeModel->getProductsDatatables(base64_decode($invoice_id));
                // echo (base64_decode($invoice_id));

                // echo (base64_encode($this->data['invoices']['order_id']));
                // print_r($this->data['invoices']);exit;
                $this->data['language'] = $this->language;
                return view('user/invoice/invoicePharmacy', $this->data);
            } else {
                $this->data['page'] = 'invoice_view';
                return view('user/invoice/invoiceAppointment', $this->data);
            }
        // } else {
        //     return redirect()->to(session('module'));
        // }
    }
    /**
     * Invoice Print
     * 
     * 
     * @param mixed $invoice_id
     * @return mixed
     */
    public function invoicePrint($invoice_id)
    {
        $this->data['invoices'] = $this->homeModel->getInvoiceDetails(base64_decode($invoice_id));
        $this->data['role'] = $this->data['invoices']['role'];
        // if ($this->data['invoices']['doctor_id'] == session('user_id') || $this->data['invoices']['user_id'] == session('user_id')) {
            $this->data['language'] = $this->language;
            if ($this->data['invoices']['role'] == '5') {
                $this->data['invoice_no'] = $this->data['invoices']['invoice_no'];
                $this->data['invoices'] = $this->homeModel->getProductsDatatables(base64_decode($invoice_id));
                // echo (base64_decode($invoice_id));exit;
                // print_r( $this->data['invoices']);exit;
                return view('user/invoice/printInvoicePharmacy', $this->data);
            } else {
                return view('user/invoice/printInvoiceAppointment', $this->data);
            }
        // } else {
        //     return redirect()->to(session('module'));
        // }
    }
    /**
     * Product View
     * 
     * @param mixed $orderId
     * @return mixed
     */
    public function productView($orderId)
    {
        $this->data['page'] = 'invoice_pharmacy_view';
        $this->data['invoices'] = $this->ordersModel->getPharmacyProductsDatatables($orderId);
        return view('user/invoice/invoicePharmacy', $this->data);
    }
    /**
     * Product Invoice Print.
     * 
     * @param mixed $orderId
     * @return mixed
     */
    public function productInvoicePrint($orderId)
    {
        $this->data['invoices'] = $this->ordersModel->getPharmacyProductsDatatables($orderId);
        $data['language'] = $this->language;
        return view('user/invoice/printInvoicePharmacy', $this->data);
    }
}
