<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'dashboards';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $allowedFields    = [];

    protected $db;
    protected mixed $appoinments = 'appointments a';
    protected mixed $doctor = 'users d';
    protected mixed $doctor_details = 'users_details dd';
    protected mixed $patient = 'users p';
    protected mixed $patient_details = 'users_details pd';
    protected mixed $specialization = 'specialization s';
    protected mixed $payment = 'payments pa';

    var mixed $column_search = array('u.first_name', 'u.last_name', 'u.profileimage', 'p.request_date', 'p.description', 'p.payment_type');
    var mixed $order = array('p.request_date' => 'DESC'); // default order
    var mixed $payment_request_column_order = array('', 'p.request_date', 'request_amount_decimal', 'p.description', 'u.first_name', 'p.payment_type', '', 'p.status');

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * Get Doctors
     * 
     * 
     * 
     * @return mixed
     */
    public function getDoctors()
    {
        $builder = $this->db->table($this->doctor);
        $builder->select('d.*,s.specialization,(select COUNT(rating) from rating_reviews where doctor_id=d.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=d.id) as rating_value');
        $builder->join($this->patient_details, 'pd.user_id = d.id', 'left');
        $builder->join($this->specialization, 'pd.specialization = s.id', 'left');
        $builder->where('d.status', '1');
        $builder->where('d.role', '1');
        $builder->orderBy('d.id', 'DESC');
        $builder->limit('10');
        return $builder->get()->getResultArray();
    }
    /**
     * Get Patients
     * 
     * 
     * 
     * @return mixed
     */
    public function getPatients()
    {
        $builder = $this->db->table('users u');
        $builder->select('u.*,ud.dob,ud.blood_group,ud.currency_code,(select appointment_date from appointments where appointment_from=u.id order by id desc limit 1) as last_vist,(select total_amount from payments where user_id=u.id order by id desc limit 1) as last_paid');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->where('u.status', '1');
        $builder->where('u.role', '2');
        $builder->orderBy('u.id', 'DESC');
        $builder->limit('10');
        return $builder->get()->getResultArray();
    }
    /**
     * User Count
     * 
     * 
     * @param mixed $role
     * @return mixed
     */
    public function usersCount($role)
    {
        $builder = $this->db->table('users');
        $builder->where('role', $role);
        $builder->where('status', 1);
        return $builder->countAllResults();
    }
    /**
     * Appointments Count
     * 
     *
     * @return mixed
     */
    public function appointmentsCount()
    {
        $builder = $this->db->table('appointments');
        $builder->where('status', 1);
        return $builder->countAllResults();
    }
    /**
     * Get Appointments 
     * 
     *
     * @return mixed
     */
    public function getAppointments()
    {
        $builder = $this->db->table($this->appoinments);
        $builder->select('a.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.first_name as doc_first_name,d.last_name as doc_last_name,d.username as doctor_username,d.profileimage as doctor_profileimage, CONCAT(p.first_name," ", p.last_name) as patient_name,p.first_name as patient_first_name,p.last_name as patient_last_name,p.profileimage as patient_profileimage,s.specialization as doctor_specialization,pa.total_amount,pd.currency_code,cliu.first_name as clinic_first_name,cliu.last_name as clinic_last_name,d.role,cliu.username as clinic_username');
        $builder->join($this->doctor, 'd.id = a.appointment_to', 'left');
        $builder->join($this->doctor_details, 'dd.user_id = d.id', 'left');
        $builder->join($this->patient, 'p.id = a.appointment_from', 'left');
        $builder->join($this->patient_details, 'pd.user_id = p.id', 'left');
        $builder->join($this->specialization, 'dd.specialization = s.id', 'left');
        $builder->join($this->payment, 'a.payment_id = pa.id', 'left');
        $builder->join('users cliu', 'cliu.id = a.hospital_id', 'left');
        $builder->join('users_details clud', 'clud.user_id = cliu.id', 'left');
        $builder->orderBy('a.id', 'DESC');
        $builder->groupBy('a.id');
        $builder->limit('10');
        return $builder->get()->getResultArray();
    }
    /**
     * Get Revenue 
     * 
     *
     * @return mixed
     */
    public function getRevenue()
    {
        $builder = $this->db->table('payments p');
        $builder->select('p.*,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
        $builder->where('p.payment_status', 1);
        $builder->where('p.request_status !=', 7);
        $result = $builder->get()->getResultArray();

        $revenue = 0;
        if (!empty($result)) {
            foreach ($result as $rows) {

                $tax_amount = $rows['tax_amount'] + $rows['transcation_charge'];

                $amount = ($rows['total_amount']) - ($tax_amount);

                $commission = !empty(settings("commission")) ? settings("commission") : "0";
                $commission_charge = ($amount * ($commission / 100));
                $balance_temp = $commission_charge;


                $user_currency_code = default_currency_code();


                $currency_option = $user_currency_code;
                $rate_symbol = currency_code_sign($currency_option);

                $org_amount = get_doccure_currency($balance_temp, $rows['currency_code'], $user_currency_code);

                $revenue += $org_amount;
            }
        }

        if ($revenue <= 0) $revenue = 0;

        return $revenue;
    }
    /**
     * Get RevenueGraph Data
     * 
     * @param mixed $value
     * @return mixed
     */
    public function getRevenueGraphData($value)
    {
        $query = $this->db->query("SELECT currency_code,IFNULL((payments.total_amount),0) as total_amount,IFNULL((payments.tax_amount),0) as tax_amount,IFNULL((payments.transcation_charge),0) as transcation_charge,MONTHNAME(payments.payment_date) as rev_month FROM ( SELECT $value AS MONTH  ) AS rev_month LEFT JOIN payments ON rev_month.month = MONTH(payments.payment_date) where payments.status =1 and payments.request_status !=7 and YEAR(payments.payment_date) = YEAR(CURDATE())
        ");
        return $query->getResultArray();
    }
    /**
     * Status Graph
     * 
     * @param mixed $value
     * @return mixed
     */
    public function statusGraph($value)
    {
        $query = $this->db->query("SELECT id,role,MONTHNAME(users.created_date) as rev_month   FROM ( SELECT $value AS MONTH  ) AS rev_month LEFT JOIN users ON rev_month.month = MONTH(users.created_date)  where  YEAR(users.created_date) = YEAR(CURDATE())");

        return $query->getResultArray();
    }
    /**
     * _get_datatables_query
     * 
     * 
     * @return mixed
     */
    private function _get_datatables_query()
    {

        $builder = $this->db->table('payment_request p');
        $builder->select('p.*,u.first_name,u.role,u.last_name,u.username,u.profileimage,a.bank_name,a.branch_name,a.account_no,a.account_name, TRUNCATE(p.request_amount,2) as request_amount_decimal');
        $builder->join('users u', 'u.id = p.user_id', 'left');
        $builder->join('account_details a', 'a.user_id = p.user_id', 'left');

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.

                }

                if ($item == 'p.request_date') {
                    $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
                } else if ($item == 'u.first_name' || $item === 'u.last_name') {
                    $builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
                } else if ($item == 'p.payment_type') {
                    $statusArray = array('', 'Appoinments', 'Refund');
                    $searchKey = $_POST['search']['value'];
                    $status = "";
                    foreach ($statusArray as $key => $value) {
                        if (strpos(strtoupper($value), strtoupper($searchKey)) !== false) {
                            $status = $key;
                            break;
                        }
                    }
                    if ($status != "") {
                        $builder->orLike($item, $status);
                    }
                } else {
                    $builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {

            $builder->orderBy($this->payment_request_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        return $builder;
    }
    /**
     * Get Datatables
     * 
     * 
     * @return mixed
     */
    public function get_datatables()
    {
        $builder = $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $builder->limit($_POST['length'], $_POST['start']);
        $query = $builder->get();
        // echo $this->db->getLastQuery();
        return $query->getResultArray();
    }
    /**
     * Count Filter
     * 
     * 
     * @return mixed
     */
    public function count_filtered()
    {
        $builder = $this->_get_datatables_query();
        $query = $builder->get();
        return $query->getNumRows();
    }
    /**
     * Count All
     * 
     * 
     * @return mixed
     */
    public function count_all()
    {
        $builder = $this->db->table('payment_request p');
        return $builder->countAllResults();
    }
    /**
     * Update Pay Status
     * 
     * @param mixed $where
     * @param mixed $data
     * @return mixed
     */
    public function updatePayStatus($where, $data)
    {
        return $this->db->table('payment_request')->where($where)->set($data)->update();
    }
}
