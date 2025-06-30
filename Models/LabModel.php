<?php

namespace App\Models;

use CodeIgniter\Model;

class LabModel extends Model
{



    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    protected $DBGroup          = 'default';
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
    protected  $table = 'lab_tests lt';
    protected string $users = 'users u';
    protected string $userDetails = 'users_details ud';
    protected string $patient = 'users p';

    protected mixed $columnSearch = array('lt.lab_test_name', 'lt.duration', 'lt.amount', 'lt.description', 'lt.created_date');
    protected mixed $order = array('lt.id' => 'DESC');
    var string $lab_payments = 'lab_payments lp';
    var mixed $appoinments_column_search = array('p.first_name', 'date_format(lp.lab_test_date,"%d %b %Y")', 'lp.total_amount', 'date_format(lp.payment_date,"%d %b %Y")');
    var mixed $appoinments_order = array('lp.lab_test_date' => 'DESC'); // default order 
    /**
     * Get Datatables
     * 
     * 
     * @param mixed $user_id
     * @param mixed $input
     * @return mixed
     */
    public function getDatatables($user_id, $input)
    {
        $builder = $this->db->table($this->table);
        $builder->select('lt.*, CONCAT(u.first_name," ", u.last_name) as lab_name, u.profileimage,u.profileimage as lab_profileimage');
        $builder->join($this->users, 'u.id = lt.lab_id', 'left');
        //$this->db->join($this->user_details,'ud.user_id = lt.lab_id','left');
        $builder->where('lt.lab_id', $user_id);
        $i = 0;
        foreach ($this->columnSearch as $item) {
            if ($input['search']['value']) {

                if ($item == 'created_date') {
                    $input['search']['value'] = date('d M Y', $input['search']['value']);
                    // date('d M Y',strtotime($lab_tests['created_date']))
                    // $item = 
                }

                if ($item == 'lab_name') {
                    $input['search']['value'] = libsodiumEncrypt($input['search']['value']);
                }

                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else if ($item == 'lt.created_date') {
                    $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->columnSearch) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }
        // if(isset($input['order']))
        // {
        $builder->orderBy('id', 'desc');
        // } 
        // else if(isset($this->order))
        // {
        //     $order = $this->order;
        //     $builder->orderBy(key($order), $order[key($order)]);
        // }
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Count Filtered
     * 
     * 
     * @param mixed $user_id
     * @param mixed $input
     * @return mixed
     */
    public function countFiltered($user_id, $input)
    {
        $builder = $this->db->table($this->table);
        // $builder->select('lt.*, CONCAT(u.first_name," ", u.last_name) as lab_name, u.profileimage,u.profileimage as lab_profileimage');
        // $builder->join($this->users, 'u.id = lt.lab_id', 'left'); 
        //$this->db->join($this->user_details,'ud.user_id = lt.lab_id','left');
        $builder->where('lt.lab_id', $user_id);
        $i = 0;
        foreach ($this->columnSearch as $item) {
            if ($input['search']['value']) {

                if ($item == 'created_date') {
                    $input['search']['value'] = date('d M Y', $input['search']['value']);
                    // date('d M Y',strtotime($lab_tests['created_date']))
                    // $item = 
                }

                if ($item == 'lab_name') {
                    $input['search']['value'] = libsodiumEncrypt($input['search']['value']);
                }

                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->columnSearch) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }
        if (isset($input['order'])) {
            $builder->orderBy('id', $input['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $query = $builder->get();
        $result = $query->getResultArray();
        return count($result);
    }
    /**
     * Count All
     * 
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function countAll($user_id)
    {
        $builder = $this->db->table($this->table);
        $builder->where('lt.lab_id', $user_id);
        return $builder->countAllResults();
    }
    /**
     * Get Labappointment Details
     * 
     * 
     * @param mixed $input
     * @return mixed
     */
    public function getLabappointmentDetails($input)
    {
        $current_date = date('Y-m-d');
        $builder = $this->db->table($this->lab_payments);
        $builder->select('lp.*, CONCAT(p.first_name," ", p.last_name) as patient_name,p.first_name as patient_first_name,p.last_name as patient_last_name,lp.booking_ids as test_ids');
        $builder->join($this->patient, 'p.id = lp.patient_id', 'left');
        // $this->db->join($this->table,'lt.lab_id = u.id','left');
        // if($this->session->userdata('role')==4)
        $builder->where('lp.lab_id', session('user_id'));
        // if($this->session->userdata('role')==2)
        //     $this->db->where('lp.patient_id',$this->session->userdata('user_id'));
        if (isset($input['type']) && $input['type'] == 1) {
            $builder->where('lp.lab_test_date', $current_date);
        }

        if (isset($input['type']) && $input['type'] == 2) {
            $builder->where('lp.lab_test_date > ', $current_date);
        }

        $i = 0;

        foreach ($this->appoinments_column_search as $item) {
            if ($input['search']['value']) {

                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->appoinments_column_search) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }
        if (isset($input['order'])) {
            $builder->orderBy('id', $input['order']['0']['dir']);
        } else if (isset($this->appoinments_order)) {
            $order = $this->appoinments_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Lab Appointments Count Filtered
     * 
     * 
     * @param mixed $input
     * @return mixed
     */
    public function labappointmentsCountFiltered($input)
    {
        $current_date = date('Y-m-d');
        $builder = $this->db->table($this->lab_payments);
        $builder->select('lp.*, CONCAT(p.first_name," ", p.last_name) as patient_name,lp.booking_ids as test_ids');
        $builder->join($this->patient, 'p.id = lp.patient_id', 'left');
        // $this->db->join($this->table,'lt.lab_id = u.id','left');
        // if($this->session->userdata('role')==4)
        $builder->where('lp.lab_id', session('user_id'));
        // if($this->session->userdata('role')==2)
        //     $this->db->where('lp.patient_id',$this->session->userdata('user_id'));
        if (isset($input['type']) && $input['type'] == 1) {
            $builder->where('lp.lab_test_date', $current_date);
        }

        if (isset($input['type']) && $input['type'] == 2) {
            $builder->where('lp.lab_test_date > ', $current_date);
        }

        $i = 0;
        foreach ($this->appoinments_column_search as $item) {
            if ($input['search']['value']) {

                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->appoinments_column_search) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }
        if (isset($input['order'])) {
            $builder->orderBy('id', $input['order']['0']['dir']);
        } else if (isset($this->appoinments_order)) {
            $order = $this->appoinments_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $query = $builder->get();
        return count($query->getResultArray());
    }
    /**
     * Lab Appointments Count All
     * 
     * 
     * @return mixed
     */
    public function labappointmentsCountAll()
    {
        $builder = $this->db->table($this->lab_payments);
        $builder->where('lp.lab_id', session('user_id'));
        return $builder->countAllResults();
    }
    /**
     * Get Appointment Details
     * 
     * @param mixed $input
     * @return mixed
     */
    public function getAppointmentDetails($input)
    {
        $builder = $this->db->table($this->lab_payments);
        $builder->select('lp.*, CONCAT(p.first_name," ", p.last_name) as patient_name,p.first_name as patient_first_name,p.last_name as patient_last_name,lp.booking_ids as test_ids');
        $builder->join($this->patient, 'p.id = lp.patient_id', 'left');
        // $this->db->join($this->table,'lt.lab_id = u.id','left');
        if (session('role') == 4)
            $builder->where('lp.lab_id', session('user_id'));
        if (session('role') == 2)
            $builder->where('lp.patient_id', session('user_id'));

        $i = 0;
        foreach ($this->appoinments_column_search as $item) {
            if ($input['search']['value']) {

                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->appoinments_column_search) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }
        if (isset($input['order'])) {
            $builder->orderBy('id', $input['order']['0']['dir']);
        } else if (isset($this->appoinments_order)) {
            $order = $this->appoinments_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Appointments CountAll
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function appointmentsCountAll($user_id)
    {
        $builder = $this->db->table($this->lab_payments);
        $builder->where('lp.lab_id', $user_id);
        return $builder->countAllResults();
    }
    /**
     * Appointments Count Filtered
     * 
     * @param mixed $input
     * @return mixed
     */
    public function appointmentsCountFiltered($input)
    {
        $builder = $this->db->table($this->lab_payments);
        $builder->select('lp.*, CONCAT(p.first_name," ", p.last_name) as patient_name,lp.booking_ids as test_ids');
        $builder->join($this->patient, 'p.id = lp.patient_id', 'left');
        if (session('role') == 4)
            $builder->where('lp.lab_id', session('user_id'));
        if (session('role') == 2)
            $builder->where('lp.patient_id', session('user_id'));

        $i = 0;
        foreach ($this->appoinments_column_search as $item) {
            if ($input['search']['value']) {

                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->appoinments_column_search) - 1 == $i)
                    $builder->groupEnd();
            }
            $i++;
        }
        if (isset($input['order'])) {
            $builder->orderBy('id', $input['order']['0']['dir']);
        } else if (isset($this->appoinments_order)) {
            $order = $this->appoinments_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $query = $builder->get();
        return count($query->getResultArray());
    }
}
