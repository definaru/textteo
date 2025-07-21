<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DashboardModel;
use App\Models\HomeModel;


class DashboardController extends BaseController
{
    public $data;
    public $session;
    /**
     * @var \App\Models\DashboardModel
     */
    public $dashboardModel;
    /**
     * @var \App\Models\HomeModel
     */
    public $homeModel;

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

        //Define Model
        $this->dashboardModel = new DashboardModel();
        $this->homeModel = new HomeModel();
    }

    /**
     * load dashboard page.
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        $this->data['doctors'] = $this->dashboardModel->getDoctors();
        $this->data['patients'] = $this->dashboardModel->getPatients();
        $this->data['doctors_count'] = $this->dashboardModel->usersCount(1);
        $this->data['patients_count'] = $this->dashboardModel->usersCount(2);
        $this->data['appointments_count'] = $this->dashboardModel->appointmentsCount();
        $this->data['appointments'] = $this->dashboardModel->getAppointments();
        $this->data['revenue'] = $this->dashboardModel->getRevenue();
        echo view('admin/dashboard/index', $this->data);
    }
    /**
     * Payment Request Page.
     *
     * @return mixed
     */
    public function paymentRequest()
    {
        $this->data['theme']     = 'admin';
        $this->data['module']    = 'payment_requests';
        $this->data['page'] = 'index';
        echo view('admin/paymentRequest', $this->data);
    }
    /**
     * Revenue Graph.
     *
     * @return mixed
     */
    public function revenueGraph()
    {

        $response = array();
        $result = array();
        $month_array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        foreach ($month_array as $value) {


            $result_array = $this->dashboardModel->getRevenueGraphData($value);
            //     $query = $this->db->query("SELECT currency_code,IFNULL((payments.total_amount),0) as total_amount,IFNULL((payments.tax_amount),0) as tax_amount,IFNULL((payments.transcation_charge),0) as transcation_charge,MONTHNAME(payments.payment_date) as rev_month FROM ( SELECT $value AS MONTH  ) AS rev_month LEFT JOIN payments ON rev_month.month = MONTH(payments.payment_date) where payments.status =1 and payments.request_status !=7 and YEAR(payments.payment_date) = YEAR(CURDATE())
            // ");
            //    $result_array= $query->result_array();
            $revenue = 0;

            $rev_month = "";
            $user_currency_code = "";

            foreach ($result_array as $rows) {

                if ($rows['total_amount'] > 0) {

                    $tax_amount = $rows['tax_amount'] + $rows['transcation_charge'];

                    $amount = intval(($rows['total_amount']) - ($tax_amount));

                    $commission = !empty(settings("commission")) ? settings("commission") : "0";
                    $commission_charge = ($amount * ($commission / 100));
                    $balance_temp = $commission_charge;

                    $currency_option = default_currency_code();
                    $rate_symbol = currency_code_sign($currency_option);

                    $org_amount = get_doccure_currency($balance_temp, $rows['currency_code'], $user_currency_code);


                    $revenue += $org_amount;
                    $rev_month = substr($rows['rev_month'], 0, 3);
                }
            }

            $data['revenue'] = number_format($revenue, 2);
            $data['month'] = (empty($rev_month)) ? "" : $rev_month;

            $result[] = $data;
        }

        $response['data'] = $result;

        echo json_encode($response);
    }

    /**
     * Status Graph.
     *
     * @return mixed
     */
    public function statusGraph()
    {


        $response = array();
        $result = array();

        $month_array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
        foreach ($month_array as $value) {


            $result_array = $this->dashboardModel->statusGraph($value);

            //     $query = $this->db->query("SELECT id,role,MONTHNAME(users.created_date) as rev_month   FROM ( SELECT $value AS MONTH  ) AS rev_month LEFT JOIN users ON rev_month.month = MONTH(users.created_date)  where  YEAR(users.created_date) = YEAR(CURDATE())
            // ");
            //        $result_array= $query->result_array();
            $patient = 0;
            $doctor = 0;
            $clinic = 0;
            $rev_month = "";
            foreach ($result_array as $rows) {


                if ($rows['role'] == 1) {
                    $doctor = $doctor + 1;
                } else if ($rows['role'] == 2) {
                    $patient = $patient + 1;
                } else if ($rows['role'] == 6) {
                    $clinic = $clinic + 1;
                }


                $doctor = $doctor;
                $patient = $patient;
                $clinic = $clinic;
                $rev_month = substr($rows['rev_month'], 0, 3);
            }

            $data['doctor'] = round($doctor);
            $data['patient'] = round($patient);
            $data['clinic'] = round($clinic);
            $data['month'] = strval($rev_month);

            $result[] = $data;
        }

        $response['data'] = $result;

        echo json_encode($response);
    }
    /**
     * Get Payment Request List.
     *
     * @return mixed
     */
    public function paymentRequestList()
    {
        $list = $this->dashboardModel->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $payments) {

            $profileimage = base_url() . 'assets/img/user.png';
            if ($payments['profileimage'] != "" && is_file($payments['profileimage'])) {
                $profileimage = base_url() . $payments['profileimage'];
            }
            if ($payments['role'] == 1) {
                $url = base_url() . 'doctor-preview/' . $payments['username'];
                $role = 'Doctor';
            } else {
                $url = '';
                $role = 'Patient';
            }

            switch ($payments['status']) {
                case '1':
                    $status = '<div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false"> Action </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" onclick="payment_status(\'' . $payments['id'] . '\',\'2\')" href="javascript:void(0);">Pay</a>
                      <a class="dropdown-item" onclick="payment_status(\'' . $payments['id'] . '\',\'3\')" href="javascript:void(0);">Reject</a>
                    </div>
                  </div>';
                    break;
                case '2':
                    $status = '<span class="badge badge-pill bg-success inv-badge">Paid</span>';
                    break;
                case '3':
                    $status = '<span class="badge badge-pill bg-danger inv-badge">Rejected</span>';
                    break;
                default:
                    $status = '';
                    break;
            }

            $currency_option = default_currency_code();
            $rate_symbol = currency_code_sign($currency_option);

            $org_amount = get_doccure_currency($payments['request_amount'], $payments['currency_code'], default_currency_code());

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date('d M Y', strtotime($payments['request_date']));
            $row[] = $rate_symbol . number_format($org_amount, 2);
            $row[] = $payments['description'];
            $row[] = '<h2 class="table-avatar">
                  <a target="_blank" href="' . $url . '" class="avatar avatar-sm mr-2">
                    <img class="avatar-img rounded-circle" src="' . $profileimage . '" alt="User Image">
                  </a>
                  <a target="_blank" href="' . $url . '">' . (libsodiumDecrypt($payments['first_name']) . ' ' . libsodiumDecrypt($payments['last_name'])) . ' <span>' . $role . '</span></a>
                </h2>';

            $row[] = ($payments['payment_type'] == 1) ? 'Appoinments' : 'Refund';
            $row[] = '<a href="javascript:void(0);" onclick="view_bankdetails(\'' . $payments['bank_name'] . '\',\'' . $payments['branch_name'] . '\',\'' . $payments['account_no'] . '\',\'' . $payments['account_name'] . '\')">View Bank Details</a>';

            $row[] = $status;

            $data[] = $row;
        }



        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->dashboardModel->count_all(),
            "recordsFiltered" => $this->dashboardModel->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    /**
     * Payment Request Status.
     *
     * @return mixed
     */
    public function paymentRequestStatus()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $data = array(
            'status' => $status,
        );
        $this->dashboardModel->updatePayStatus(array('id' => $id), $data);

        // $touserid = $this->dashboardModel->get_touserid($id);
        $touserid = getTblRowOfData('payment_request p', ['id' => $id], '*');
        $touserid = $touserid ? $touserid['user_id'] : 0;
        if ($status === 3) {
            $text = "has rejected payment request of";
        } else {
            $text = "has accepted payment request and paid";
        }
        $notification = array(
            'user_id' => 0,
            'to_user_id' => $touserid,
            'type' => "Payment Request",
            'text' => $text,
            'created_at' => date("Y-m-d H:i:s"),
            'time_zone' => session('time_zone')
        );
        $this->homeModel->insertData('notification', $notification);

        echo json_encode(array("status" => TRUE));
    }
}
