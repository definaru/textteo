<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\HomeModel;
use App\Models\AccountsModel;

class AccountsController extends BaseController
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
     * @var \App\Models\AccountsModel
     */
    public $accountModel;

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
        $user_detail = user_detail(session('user_id'));
        if ($user_detail['hospital_id'] != 0) {
            redirect('doctor');
        }
        $this->data['uri'] = service('uri');

        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        $this->homeModel = new HomeModel();
        $this->accountModel = new AccountsModel();
    }
    /**
     * Patient Dashboard.
     *
     * @return mixed
     */
    public function indexa()
    {
        $this->data['page'] = 'patientDashboard';
        $this->data['profile'] = $this->userModel->getUserDetails(session('user_id'));
        $this->data['user_detail'] = $this->userModel->getUserDetails(session('user_id'));
        return view('user/patient/patientDashboard', $this->data);
    }
    /**
     * Patient Accounts page.
     *
     * @return mixed
     */
    public function index()
    {
        $user_id = session('user_id');
        if (session('role') == '1') {
            $this->data['module']    = 'doctor';
            $this->data['page'] = 'accounts';

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);

            $this->data['currency_symbol'] = $rate_symbol;

            $this->data['balance'] = $this->accountModel->getBalance($user_id);
            $this->data['requested'] = $this->accountModel->getrequested($user_id);
            $this->data['earned'] = $this->accountModel->getEarned($user_id);
            $this->data['account_details'] = $this->accountModel->getAccountDetails($user_id);
            return view('user/layout/accounts', $this->data);
        } elseif (session('role') == '4') {
            $this->data['module']    = 'lab';
            $this->data['page'] = 'accounts';

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);

            $this->data['currency_symbol'] = $rate_symbol;

            $this->data['balance'] = $this->accountModel->getBalance($user_id);
            $this->data['requested'] = $this->accountModel->getrequested($user_id);
            $this->data['earned'] = $this->accountModel->getEarned($user_id);
            $this->data['account_details'] = $this->accountModel->getAccountDetails($user_id);
            return view('user/layout/accounts', $this->data);
        } elseif (session('role') == '5') {
            $this->data['module']    = 'pharmacy';
            $this->data['page'] = 'accounts';

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);

            $this->data['currency_symbol'] = $rate_symbol;

            $this->data['balance'] = $this->accountModel->getPharmacyBalance($user_id);
            // print_r($this->data['balance']);exit();
            $this->data['requested'] = $this->accountModel->getrequested($user_id);
            $this->data['earned'] = $this->accountModel->getEarned($user_id);
            $this->data['account_details'] = $this->accountModel->getAccountDetails($user_id);
            return view('user/layout/accounts', $this->data);
        } else if (session('role') == '6') {
            $this->data['module']    = 'doctor';
            $this->data['page'] = 'accounts';

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : settings('default_currency');
            $rate_symbol = currency_code_sign($currency_option);
            $this->data['currency_symbol'] = $rate_symbol;
            $this->data['balance'] =  round($this->accountModel->getBalance($user_id));
            $this->data['requested'] = $this->accountModel->getrequested($user_id);
            $this->data['earned'] = $this->accountModel->getEarned($user_id);
            $this->data['account_details'] = $this->accountModel->getAccountDetails($user_id);
            return view('user/layout/accounts', $this->data);
        } else {
            $this->data['module']    = 'patient';
            $this->data['page'] = 'accounts';

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
            // print_r($user_id);exit();
            $rate_symbol = currency_code_sign($currency_option);

            $this->data['currency_symbol'] = $rate_symbol;

            $payments_balance = $this->accountModel->getPatientBalance($user_id);
            $pharmacy_payments_balance = $this->accountModel->getPatientPharmacyBalance($user_id);
            $this->data['balance'] = $payments_balance + $pharmacy_payments_balance;

            $this->data['requested'] = $this->accountModel->getRequested($user_id);
            // print_r($this->db->last_query());exit();

            // print_r($this->data['requested']);exit();
            $this->data['earned'] = $this->accountModel->getEarned($user_id);
            $this->data['account_details'] = $this->accountModel->getAccountDetails($user_id);
            return view('user/patient/accounts', $this->data);
        }
    }
    /**
     * Get Account Detail for users
     * 
     * @return mixed
     */
    public function getAccountDetails()
    {
        $user_id = session('user_id');
        $data = $this->accountModel->getAccountDetails($user_id);
        echo json_encode($data);
    }
    /**
     * Add Account Details
     * 
     * 
     * @return mixed
     */
    public function addAccountDetails()
    {
        $inputdata = array();
        $status = '';
        $inputdata['user_id'] = session('user_id');
        $inputdata['bank_name'] = $this->request->getPost('bank_name');
        $inputdata['branch_name'] = $this->request->getPost('branch_name');
        $inputdata['account_no'] = $this->request->getPost('account_no');
        $inputdata['account_name'] = $this->request->getPost('account_name');

        $already_exits = $this->homeModel->checkTblDataExist('account_details', ['user_id' => $inputdata['user_id']], 'id', []);
        // print_r($already_exits);exit();        
        if (!$already_exits) {
            if ($this->homeModel->insertData('account_details', $inputdata)) {
                $result = 'true';
                $status = $this->language['lg_account_details'];
            } else {
                $result = 'false';
                $status = $this->language['lg_edit_req'];
            }
        } else {
            // check user is modified any data
            $check_edit_status = $this->homeModel->checkTblDataExist('account_details', $inputdata, '*', []);
            if ($check_edit_status) {
                $datas['result'] = 'false';
                $datas['status'] = $this->language['lg_accounts_update'];
                goto OUTPUT;
            }
            if ($this->homeModel->updateData('account_details', ['user_id' => $inputdata['user_id']], $inputdata)) {
                $result = 'true';
                $status = $this->language['lg_account_details_up'];
            }
        }

        if ($result == 'true') {
            $datas['result'] = $result;
            $datas['status'] = $status;
        } else {
            $datas['result'] = 'false';
            $datas['status'] = $this->language['lg_edit_req'];
        }
        OUTPUT:
        echo json_encode($datas);
    }

    /**
     * Doctor List In Account Page
     * 
     * 
     * @return mixed
     */
    public function doctorAccountsList()
    {
        $user_id = session('user_id');
        $list = $this->accountModel->get_datatables($user_id);

        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $account) {
            if ($account['patient_profileimage'] == "" || ($account['patient_profileimage'] != "" && !file_exists($account['patient_profileimage']))) {
                $patient_profileimage = base_url() . 'assets/img/user.png';
            } else {
                $patient_profileimage = (!empty($account['patient_profileimage'] ?? "")) ? base_url() . $account['patient_profileimage'] ?? "" : base_url() . 'assets/img/user.png';
            }

            $patient_currency = $account['currency_code'];

            $tax_amount = $account['tax_amount'] + $account['transcation_charge'];
            $amount = ($account['total_amount']) - ($tax_amount);
            $commission = !empty(settings("commission")) ? settings("commission") : "0";
            if (session('role') == '4')
                $commission = !empty(settings("lab_commission")) ? settings("lab_commission") : "0";
            if (session('role') == '5')
                $commission = !empty(settings("pharmacy_commission")) ? settings("pharmacy_commission") : "0";
            $commission_charge = ($amount * ($commission / 100));

            if ($account['request_status'] == '6') {
                $total_amount = ($amount);
            } else {
                $total_amount = ($amount - $commission_charge);
            }

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);

            $org_amount = get_doccure_currency($total_amount, $patient_currency, $user_currency_code);

            $cls = '';
            $appt = $this->homeModel->getTblRowOfData('appointments', array('payment_id' => $account['id']), '*');
            if ($appt && $appt['approved'] == 1 && $appt['appointment_status'] == 0 && $appt['call_end_status'] == 0 && $appt['review_status'] == 0) {
                $cls = 'd-none';
            }
            if ($appt && $appt['approved'] == 1 && $appt['appointment_status'] == 1 && $appt['call_end_status'] == 1) {
                $cls = 'd-none';
            }

            $fromdatetime = '';
            $can_send_request = 0;
            $can_add_wallet = 0;
            $appoint_status = '';

            if (session('role') == '1' || session('role') == '2' || session('role') == '6') {
                if ($appt && $appt['time_zone']) {
                    $current_timezone = $appt['time_zone'];
                    $old_timezone = session('time_zone');
                    $fromdatetime = converToTz($appt["from_date_time"], $old_timezone, $current_timezone);
                } else {
                    if ($appt && $appt["from_date_time"]) {
                        $fromdatetime = $appt["from_date_time"];
                    }
                }
                if ($appt && $appt['appointment_status'] == '1' && $appt['call_status'] == 1) {
                    $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
                    $can_add_wallet = 1;
                }
                if ($appt && $appt['appointment_status'] == '0' && $appt['call_status'] == 0 && ($fromdatetime > date('Y-m-d H:i:s'))) {
                    $appoint_status = '<span class="badge badge-warning">' . $this->language['lg_booked1'] . '</span>';
                }
                if ($appt && $appt['call_status'] == 0 && $appt['approved'] == 1 && ($fromdatetime < date('Y-m-d H:i:s'))) {
                    $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_missed'] . '</span>';
                    $can_send_request = 1;
                }
                if ($appt && $appt['appointment_status'] == '0' && $appt['approved'] == 1 && $appt['call_status'] == 0 && empty($fromdatetime)) {
                    $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_pending1'] . '</span>';
                }
                if ($appt && $appt['approved'] == 0) {
                    $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . ' </span>';
                    $can_send_request = 1;
                }
            }
            if (session('role') == '5') {
                $order_details = $this->homeModel->getTblRowOfData('orders', array('id' => $account['orders_id']), 'order_status');
                if ($order_details && $order_details['order_status'] == 'completed') {
                    $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
                    $can_add_wallet = 1;
                } else if ($order_details && $order_details['order_status'] == 'shipped') {
                    $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_shipped'] . '</span>';
                } else if ($order_details && $order_details['order_status'] == 'accepted') {
                    $appoint_status = '<span class="badge badge-success">' . $this->language['lg_accepted'] . '</span>';
                } else if ($order_details && $order_details['order_status'] == 'rejected') {
                    $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_rejected'] . ' </span>';
                } else if ($order_details && $order_details['order_status'] == 'pending') {
                    $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_pending1'] . ' </span>';
                }
            }
            if (session('role') == '4') {
                $lab_details = $this->homeModel->getTblRowOfData('lab_payments', array('order_id' => $account['order_id']), 'cancel_status');
                if ($lab_details && $lab_details['cancel_status'] == 'Approved') {
                    $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
                    $can_add_wallet = 1;
                } else if ($lab_details && $lab_details['cancel_status'] == 'New') {
                    $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
                } else if ($lab_details && $lab_details['cancel_status'] == 'Cancelled') {
                    $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . ' </span>';
                }
            }

            switch ($account['request_status']) {
                case '0':
                    // $status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
                    // if (session('role') == '1') {
                    //     $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'2\')" class="btn btn-sm bg-info-light">' . $this->language['lg_payment_receive'] . '</a>';
                    // } else {
                    //     $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'1\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
                    // }
                    $status = $appoint_status;
                    $action = '';
                    if (session('role') != '2') {
                        if ($can_add_wallet == 1) {
                            $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'2\',\'' . session('role') . '\')" class="btn btn-sm bg-info-light">' . $this->language['lg_add_to_balance'] . '</a>';
                        }
                    } else {
                        if ($can_send_request == 1) {
                            $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'1\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
                        }
                    }
                    break;
                case '1':
                    $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_pat'] . '</span>';
                    $action = '';
                    break;
                case '2':
                    // $status='<span class="badge badge-success">'.$this->language['lg_approved'].'</span>';
                    $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
                    $action = '';
                    break;
                case '3':
                    $status = '<span class="badge badge-warning">' . $this->language['lg_payment_request'] . '</span>';
                    $action = '';
                    break;
                case '4':
                    $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
                    $action = '';
                    break;
                case '5':
                    $status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . '</span>';
                    // $action='<a href="javascript:void(0)" onclick="send_request(\''.$account['id'].'\',\'2\')" class="btn btn-sm bg-info-light">'.$this->language['lg_send_request'].'</a>';
                    $action = '';
                    break;

                case '6':
                    $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_app'] . '</span>';
                    $action = '';
                    break;

                case '7':
                    $status = '<span class="badge badge-info">' . $this->language['lg_refund'] . '</span>';
                    $action = '';
                    break;

                case '8':
                    $status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . '</span>';
                    $action = '';
                    break;

                default:
                    // $status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
                    // $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'1\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
                    $status = $appoint_status;
                    $action = '';
                    if ($can_send_request == 1) {
                        $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'1\',\'' . session('role') . '\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
                    }

                    break;
            }


            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('d M Y', strtotime($account['payment_date']));
            $row[] = '<h2 class="table-avatar">
                    <a href="#" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
                    <a href="#">' . ucfirst(libsodiumDecrypt($account['patient_firstname'])) . ' ' . ucfirst(libsodiumDecrypt($account['patient_lastname'])) . ' </a>
                    </h2>';

            $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
            $row[] = $status;
            $row[] = $action;


            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->accountModel->countAll($user_id),
            "recordsFiltered" => $this->accountModel->countFiltered($user_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    /**
     * Accounts Page #Patient Accounts List
     * 
     * 
     * @return mixed
     */
    public function patientAccountsList()
    {
        $user_id = session('user_id');
        $type = $this->request->getPost('type');
        $list = $this->accountModel->getPatientAccountsDatatables($user_id, $type);
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $account) {

            if ($account['doctor_profileimage'] == "" || ($account['doctor_profileimage'] != "" && !file_exists($account['doctor_profileimage']))) {
                $doctor_profileimage = base_url() . 'assets/img/user.png';
            } else {
                $doctor_profileimage = (!empty($account['doctor_profileimage'] ?? "")) ? base_url() . $account['doctor_profileimage'] ?? "" : base_url() . 'assets/img/user.png';
            }

            $tax_amount = $account['tax_amount'] + $account['transcation_charge'];

            $amount = ($account['total_amount']) - ($tax_amount);
            $patient_currency = $account['currency_code'];
            $commission = !empty(settings("commission")) ? settings("commission") : "0";
            if ($account['role'] == '4')
                $commission = !empty(settings("lab_commission")) ? settings("lab_commission") : "0";
            if ($account['role'] == '5')
                $commission = !empty(settings("pharmacy_commission")) ? settings("pharmacy_commission") : "0";

            $commission_charge = ($amount * ($commission / 100));
            $total_amount = ($amount);

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);

            $org_amount = get_doccure_currency($total_amount, $patient_currency, $user_currency_code);

            $cls = '';
            $appt = $this->homeModel->getTblRowOfData('appointments', array('payment_id' => $account['id']), '*');
            if ($appt && $appt['approved'] == 1 && $appt['appointment_status'] == 0 && $appt['call_end_status'] == 0 && $appt['review_status'] == 0) {
                $cls = 'd-none';
            }
            if ($appt && $appt['approved'] == 1 && $appt['appointment_status'] == 1 && $appt['call_end_status'] == 1) {
                $cls = 'd-none';
            }

            $fromdatetime = '';
            $roleId = '';
            $appoint_status = '';
            $can_send_request = 0;
            if ($type == 'doctor') {
                if ($appt && $appt['time_zone']) {
                    $current_timezone = $appt['time_zone'];
                    $old_timezone = session('time_zone');
                    $fromdatetime = converToTz($appt["from_date_time"], $old_timezone, $current_timezone);
                } else {
                    if ($appt && $appt["from_date_time"]) {
                        $fromdatetime = $appt["from_date_time"];
                    }
                }
                if ($appt && $appt['appointment_status'] == '1' && $appt['call_status'] == 1) {
                    $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
                }
                if ($appt && $appt['appointment_status'] == '0' && $appt['call_status'] == 0 && ($fromdatetime > date('Y-m-d H:i:s'))) {
                    $appoint_status = '<span class="badge badge-warning">' . $this->language['lg_booked1'] . '</span>';
                }
                if ($appt && $appt['call_status'] == 0 && $appt['approved'] == 1 && ($fromdatetime < date('Y-m-d H:i:s'))) {
                    $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_missed'] . '</span>';
                    $can_send_request = 1;
                }
                if ($appt && $appt['appointment_status'] == '0' && $appt['approved'] == 1 && $appt['call_status'] == 0 && empty($fromdatetime)) {
                    $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_pending1'] . '</span>';
                }
                if ($appt && $appt['approved'] == 0) {
                    $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . ' </span>';
                    $can_send_request = 1;
                }
            }
            if ($type == 'pharmacy') {
                $order_details = $this->homeModel->getTblRowOfData(
                    'orders',
                    array('id' => $account['orders_id']),
                    'order_status'
                );
                if ($order_details && $order_details['order_status'] == 'completed') {
                    $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
                } else if ($order_details && $order_details['order_status'] == 'shipped') {
                    $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_shipped'] . '</span>';
                } else if ($order_details && $order_details['order_status'] == 'accepted') {
                    $appoint_status = '<span class="badge badge-success">' . $this->language['lg_accepted'] . '</span>';
                } else if ($order_details && $order_details['order_status'] == 'rejected') {
                    $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_rejected'] . ' </span>';
                    $can_send_request = 1;
                } else if ($order_details && $order_details['order_status'] == 'pending') {
                    $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_pending1'] . ' </span>';
                }
                $roleId = 5;
            }
            if ($type == 'lab') {
                $lab_details = $this->homeModel->getTblRowOfData('lab_payments', array('order_id' => $account['order_id']), 'cancel_status');
                if ($lab_details && $lab_details['cancel_status'] == 'Approved') {
                    $appoint_status = '<span class="badge badge-success">' . $this->language['lg_completed'] . '</span>';
                } else if ($lab_details && $lab_details['cancel_status'] == 'New') {
                    $appoint_status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
                } else if ($lab_details && $lab_details['cancel_status'] == 'Cancelled') {
                    $appoint_status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . ' </span>';
                    $can_send_request = 1;
                }
                $roleId = 4;
            }
            switch ($account['request_status']) {

                case '0':
                    // $status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
                    $status = $appoint_status;
                    $action = '';
                    if ($can_send_request == 1) {
                        $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'6\',\'' . $roleId . '\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
                    }
                    break;
                case '1':
                    $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_app'] . '</span>';
                    $action = '';
                    break;
                case '2':
                    if ($account['role'] == '4' || $account['role'] == '5')
                        $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
                    else
                        $status = '<span class="badge badge-success">' . $this->language['lg_appointment_com'] . '</span>';
                    $action = '';
                    break;
                case '3':
                    // $status='<span class="badge badge-warning">'.$this->language['lg_appointment_com'].'</span>';
                    if ($account['role'] == '4' || $account['role'] == '5')
                        $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
                    else
                        $status = '<span class="badge badge-warning">' . $this->language['lg_appointment_com'] . '</span>';
                    $action = '';
                    break;
                case '4':
                    // $status='<span class="badge badge-success">'.$this->language['lg_appointment_com'].'</span>';
                    if ($account['role'] == '4' || $account['role'] == '5')
                        $status = '<span class="badge badge-success">' . $this->language['lg_payment_receive'] . '</span>';
                    else
                        $status = '<span class="badge badge-success">' . $this->language['lg_appointment_com'] . '</span>';
                    $action = '';
                    break;
                case '5':
                    // $status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
                    $status = $appoint_status;
                    $action = '';
                    if ($can_send_request == 1) {
                        $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'6\',\'' . $roleId . '\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
                    }
                    break;

                case '6':
                    // $status='<span class="badge badge-warning">'.$this->language['lg_waiting_for_doc'].'</span>';
                    if ($account['role'] == '4')
                        $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_lab'] . '</span>';
                    elseif ($account['role'] == '5')
                        $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_pha'] . '</span>';
                    else
                        $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_doc'] . '</span>';
                    $action = '';
                    $action = '';
                    break;

                case '7':
                    if ($account['role'] == '4')
                        $status = '<span class="badge badge-success">' . $this->language['lg_ref_approve_lab'] . '</span>';
                    elseif ($account['role'] == '5')
                        $status = '<span class="badge badge-success">' . $this->language['lg_ref_approve_pha'] . '</span>';
                    else
                        $status = '<span class="badge badge-success">' . $this->language['lg_ref_approve_doc'] . '</span>';
                    $action = '';
                    break;

                case '8':
                    $status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . '</span>';
                    $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'6\',\'' . $roleId . '\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
                    break;


                default:
                    // $status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
                    $status = $appoint_status;
                    $action = '';
                    if ($can_send_request == 1) {
                        $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'1\',\'' . $roleId . '\')" class="btn btn-sm bg-info-light">' . $this->language['lg_send_request'] . '</a>';
                    }
                    break;
            }


            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('d M Y', strtotime($account['payment_date']));

            $user_role = '';
            $img = '';

            if ($account['role'] == '1') {
                $user_role = $this->language['lg_dr'];
                $img = '<a target="_blank" href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($account['doctor_username'])) . '" class="avatar avatar-sm mr-2">
                      <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                    </a>';
            }

            $detailed_preview_url = base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($account['doctor_username']));
            if ($account['role'] == '5') {
                $detailed_preview_url =  base_url() . 'pharmacy-preview/' . encryptor_decryptor('encrypt', $account['doctor_id']);
            }
            if ($account['role'] == '4') {
                $detailed_preview_url = base_url() . 'lab-tests/' . encryptor_decryptor('encrypt', libsodiumDecrypt($account['doctor_username']));
            }
            $row[] = '<h2 class="table-avatar">
                    ' . $img . '
                    <a target="_blank" href="' . $detailed_preview_url . '">' . $user_role . ' ' . ucfirst(libsodiumDecrypt($account['doctor_firstname'])) . " " . (libsodiumDecrypt($account['doctor_lastname'])) . '</a>
                  </h2>';




            $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
            // $row[]=$account['total_amount'];
            $row[] = $status;
            $row[] = $action;


            $data[] = $row;
        }



        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->accountModel->patientAccountsCountAll($user_id, $type),
            "recordsFiltered" => $this->accountModel->patientAccountsFiltered($user_id, $type),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Patient doctor request list
     * 
     * 
     * @return mixed
     */
    public function patientDoctorRequest()
    {
        $user_id = session('user_id');
        $list = $this->accountModel->getDoctorRequestDatatables($user_id);
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $account) {

            $doctor_profileimage = (!empty($account['doctor_profileimage'])) ? base_url() . $account['doctor_profileimage'] : base_url() . 'assets/img/user.png';

            $tax_amount = $account['tax_amount'] + $account['transcation_charge'];

            $amount = ($account['total_amount']) - ($tax_amount);
            $patient_currency = $account['currency_code'];
            $commission = !empty(settings("commission")) ? settings("commission") : "0";
            $commission_charge = ($amount * ($commission / 100));
            $total_amount = ($amount - $commission_charge);

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);

            $org_amount = get_doccure_currency($total_amount, $patient_currency, $user_currency_code);

            switch ($account['request_status']) {

                case '1':
                    $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_app'] . '</span>';
                    $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'2\')" class="btn btn-sm bg-info-light">' . $this->language['lg_approve1'] . '</a> <a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'5\')" class="btn btn-sm bg-info-light">' . $this->language['lg_cancel'] . '</a>';
                    break;
                case '5':
                    $status = '<span class="badge badge-danger">' . $this->language['lg_cancelled'] . '</span>';
                    $action = '';
                    break;

                default:
                    $status = '';
                    $action = '';
                    break;
            }


            $no++;
            $row = array();
            $row[] = date('d M Y', strtotime($account['payment_date']));
            $row[] = '<h2 class="table-avatar">
                  <a target="_blank" href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($account['doctor_username'])) . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $doctor_profileimage . '" alt="User Image">
                  </a>
                  <a target="_blank" href="' . base_url() . 'doctor-preview/' . encryptor_decryptor('encrypt', libsodiumDecrypt($account['doctor_username'])) . '">' . $this->language['lg_dr'] . ' ' . ucfirst(libsodiumDecrypt($account['doctor_name'])) . '</a>
                </h2>';

            $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
            $row[] = $status;
            $row[] = $action;


            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->accountModel->doctorRequestCountAll($user_id),
            "recordsFiltered" => $this->accountModel->doctorRequestFiltered($user_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    /**
     * Patient to  clinic / doctor / lab / pharmacy 
     * 
     * 
     * @return mixed
     */
    public function sendRequest()
    {
        $text = '';
        $payment_id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $role = $this->request->getPost('role');
        if ($role == 5) {
            $this->homeModel->updateData('pharmacy_payments', ['id' => $payment_id], ['request_status' => $status]);
        } else {
            $this->homeModel->updateData('payments', ['id' => $payment_id], ['request_status' => $status]);
        }

        if (session('role') == '1') {
            $user_id = $this->homeModel->getTblRowOfData('payments', array('id' => $payment_id), 'user_id');
            $touserid = $user_id ? $user_id['user_id'] : 0;
        } else {
            $doctor_id = $this->homeModel->getTblRowOfData('payments', array('id' => $payment_id), 'doctor_id');
            $touserid = $doctor_id ? $doctor_id['doctor_id'] : 0;
        }

        $appoitAt = $this->homeModel->getTblRowOfData('appointments', array('payment_id' => $payment_id), 'from_date_time');
        $appoitAt = $appoitAt ? $appoitAt['from_date_time'] : "";

        if ($status === 7) {
            $this->homeModel->updateData('appointments', ['payment_id' => $payment_id], ['appointment_status' => 1]);
        }

        if ($status === 2 || $status === 7) {
            $text = "has approved payment refund request of <i style='color:#6495ed'>'" . $appoitAt . "'</i> appointment of";
        } else if ($status === 5 || $status === 8) {
            $text = "has rejected payment refund request of <i style='color:#6495ed'>'" . $appoitAt . "'</i> appointment of";
        } else if ($status === 1 || $status === 6) {
            $text = "has sent payment refund request for <i style='color:#6495ed'>'" . $appoitAt . "'</i> appointment to";
        }
        $notification = array(
            'user_id' => session('user_id'),
            'to_user_id' => $touserid,
            'type' => "Payment Request",
            'text' => $text,
            'created_at' => date("Y-m-d H:i:s"),
            'time_zone' => $this->timezone
        );
        //print_r($notification);
        $this->homeModel->insertData('notification', $notification);
    }
    /**
     * Doctor Refund Request
     * 
     * 
     * @return mixed
     */
    public function patientRefundRequest()
    {
        $user_id = session('user_id');
        $list = $this->accountModel->get_refund_datatables($user_id);
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $account) {

            $patient_profileimage = (!empty($account['patient_profileimage'])) ? base_url() . $account['patient_profileimage'] : base_url() . 'assets/img/user.png';

            $tax_amount = $account['tax_amount'] + $account['transcation_charge'];

            $amount = ($account['total_amount']) - ($tax_amount);
            $patient_currency = $account['currency_code'];
            $commission = !empty(settings("commission")) ? settings("commission") : "0";
            $commission_charge = ($amount * ($commission / 100));
            $total_amount = ($amount);

            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $user_currency_rate = $user_currency['user_currency_rate'];

            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);

            $org_amount = get_doccure_currency($total_amount, $patient_currency, $user_currency_code);



            switch ($account['request_status']) {
                case '6':
                    $status = '<span class="badge badge-warning">' . $this->language['lg_waiting_for_app'] . '</span>';
                    $action = '<a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'7\',\'' . session('role') . '\')" class="btn btn-sm bg-info-light">' . $this->language['lg_approve1'] . '</a> <a href="javascript:void(0)" onclick="send_request(\'' . $account['id'] . '\',\'8\',\'' . session('role') . '\')" class="btn btn-sm bg-info-light">' . $this->language['lg_cancel'] . '</a>';
                    break;

                default:
                    $status = '<span class="badge badge-primary">' . $this->language['lg_new1'] . '</span>';
                    $action = '';
                    break;
            }


            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('d M Y', strtotime($account['payment_date']));
            $row[] = '<h2 class="table-avatar">
                  <a href="#" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="' . $patient_profileimage . '" alt="User Image"></a>
                  <a href="#">' . ucfirst(libsodiumDecrypt($account['patient_firstname'])) . ' ' . ucfirst(libsodiumDecrypt($account['patient_lastname'])) . ' </a>
                </h2>';




            $row[] = $rate_symbol . number_format($org_amount, 2, '.', ',');
            $row[] = $status;
            $row[] = $action;


            $data[] = $row;
        }


        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->accountModel->refund_count_all($user_id),
            "recordsFiltered" => $this->accountModel->refund_count_filtered($user_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    /**
     * Refund Payment Request
     * 
     * 
     * @return mixed
     */
    public function paymentRequest()
    {
        $inputdata = array();
        $balance = 0;
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];


        $inputdata['user_id'] = session('user_id');
        $inputdata['payment_type'] = $this->request->getPost('payment_type');
        $inputdata['request_amount'] = $this->request->getPost('request_amount');
        $inputdata['currency_code'] = $user_currency_code;
        $inputdata['description'] = $this->request->getPost('description');
        $inputdata['request_date'] = date('Y-m-d H:i:s');


        // $already_exits = $this->db->where('user_id', $inputdata['user_id'])->get('account_details')->num_rows();
        // $already_exits = $this->homeModel->checkTblDataExist('account_details', ['user_id' => $inputdata['user_id']], 'id', []);
        $already_exits = $this->homeModel->getTblRowOfData('account_details', array('user_id' => $inputdata['user_id']), '*');
        if (!$already_exits) {
            $datas['result'] = 'false';
            $datas['status'] = $this->language['lg_please_enter_ac'];
            echo json_encode($datas);
            return false;
        }

        if ($inputdata['payment_type'] == '1') {
            if (session('role') == '5') {
                $balance = $this->accountModel->getPharmacyBalance($inputdata['user_id']);
            } else {
                $balance = $this->accountModel->getBalance($inputdata['user_id']);
            }
        }

        if ($inputdata['payment_type'] == '2') {
            /** @var int $balance */
            $payments_balance = $this->accountModel->getPatientBalance($inputdata['user_id']);
            $pharmacy_payments_balance = $this->accountModel->getPatientPharmacyBalance($inputdata['user_id']);
            $balance = $payments_balance + $pharmacy_payments_balance;
        }

        $requested = $this->accountModel->getRequested($inputdata['user_id']);
        $earned = $this->accountModel->getEarned($inputdata['user_id']);

        $balances = $balance;
        $balance = intval($balance) - (intval($earned) + intval($requested));

        if (intval($balance) < (int)$inputdata['request_amount']) {
            $datas['result'] = 'false';
            $datas['status'] = $this->language['lg_request_less_than_balance'] ?? "Request amount should be less than balance";
            echo json_encode($datas);
            return false;
        }

        if ($this->homeModel->insertData('payment_request', $inputdata)) {
            $result = true;
        } else {
            $result = false;
        }

        if (@$result == true) {

            $notification = array(
                'user_id' => session('user_id'),
                'to_user_id' => 0,
                'type' =>  "Payment Request",
                'text' => "has raised payment request",
                'created_at' => date("Y-m-d H:i:s"),
                'time_zone' => $this->timezone
            );
            $this->homeModel->insertData('notification', $notification);
            $datas['result'] = 'true';
            $datas['status'] = $this->language['lg_payment_request1'];
        } else {
            $datas['result'] = 'false';
            $datas['status'] = $this->language['lg_payment_request2'];
        }

        echo json_encode($datas);
    }
}
