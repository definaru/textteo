<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoModel extends Model
{
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

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $table = 'coupons a';
    protected string $users = 'users u';
    // protected $column_search = array('u.first_name','u.last_name','u.profileimage','a.appointment_date','a.from_date_time'); //set column field database for datatable searchable 
    protected mixed $column_search = array('u.first_name', 'date_format(a.appointment_date,"%d %b %Y")', 'a.type');
    protected mixed $order = array('a.id' => 'ASC'); // default order
    protected mixed $column_order = array('', 'u.first_name', 'a.appointment_date', 'a.type');

    // admin
    protected string $appoinments = 'packages a';
    protected string $doctor = 'users d';
    protected string $doctor_details = 'users_details dd';
    protected string $patient = 'users p';
    protected string $patient_details = 'users_details pd';
    protected string $specialization = 'specialization s';
    protected string $payment = 'payments pa';

    protected mixed $appoinments_column_search = array('CONCAT(d.first_name," ",d.last_name)', 'd.profileimage', 'CONCAT(p.first_name," ",p.last_name)', 'p.profileimage', 'date_format(a.appointment_date,"%d %b %Y")', 'date_format(a.created_date,"%d %b %Y")', 'a.type');
    protected mixed $appoinments_default_order = array('a.id' => 'DESC'); // upcoming appointments default order   
    protected mixed $appointments_column_order = array('', 'cliu.first_name, d.first_name', 'p.first_name', 'a.from_date_time', 'a.created_date', 'a.type', 'a.appointment_status', 'total_amount_decimal'); // upcoming appointments column order 
    protected mixed $appoinments_order = array('CONCAT(d.first_name," ",d.last_name)', 'd.profileimage', 'CONCAT(p.first_name," ",p.last_name)', 'p.profileimage', 'date_format(a.appointment_date,"%d %b %Y")', 'a.created_date', 'a.type');

    protected string $lab_payments = 'lab_payments lp';

    protected mixed $labappoinments_column_search = array('CONCAT(p.first_name," ", p.last_name)', 'date_format(lp.lab_test_date,"%d %b %Y")', 'lp.total_amount', 'date_format(lp.payment_date,"%d %b %Y")', 'lp.cancel_status', 'lt.lab_test_name');

    protected mixed $labappoinments_order = array('lp.id' => 'DESC'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * Patient Appointment List
     *
     * @param mixed $user_id
     * @param mixed $page
     * @param mixed $limit
     * @param mixed $type
     * @return mixed
     */
    public function getPromocodes()
    {
        $builder = $this->db->table('coupons p');
        $builder->select('p.*');
        return $builder->get()->getResultArray();
    }

    /**
     * Get DatatablesQuery
     * 
     * @param mixed $user_id
     * @return mixed
     */
    private function getDatatablesQuery($user_id)
    {
        $current_date = date('Y-m-d');
        $from_date_time = date('Y-m-d H:i:s');
        $builder = $this->db->table($this->table);
        $builder->select('a.*, u.first_name, u.last_name, u.username, u.profileimage, p.per_hour_charge, ud.first_name as doctor_name, ud.role');
        $builder->join($this->users, 'u.id = a.appointment_from', 'left');
        $builder->join('payments p', 'p.id = a.payment_id', 'left');
        $builder->join('users ud', 'ud.id = a.appointment_to', 'left');

        if ($_POST['type'] == 1) {
            $builder->where('a.appointment_date', $current_date);
        }

        if ($_POST['type'] == 2) {
            $builder->where('a.from_date_time > ', $from_date_time);
        }
        $builder->groupStart();
        $builder->where('a.appointment_to', $user_id);
        $builder->orWhere('a.hospital_id', $user_id);
        $builder->groupEnd();

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($_POST['search']['value']));
                } else if ($item == 'a.type') {
                    $builder->orLike($item, $_POST['search']['value']);
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
            $builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        return $builder;
    }
    /**
     * Get Datatables
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function getDatatables($user_id)
    {
        $builder = $this->getDatatablesQuery($user_id);
        if ($_POST['length'] != -1)
            $builder->limit($_POST['length'], $_POST['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Count Filtered
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function countFiltered($user_id)
    {
        $builder = $this->getDatatablesQuery($user_id);
        $query = $builder->get();
        return $query->getNumRows();
    }
    /**
     * Count All
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function countAll($user_id)
    {
        $builder = $this->db->table($this->table);
        $current_date = date('Y-m-d');
        $from_date_time = date('Y-m-d H:i:s');

        if ($_POST['type'] == 1) {
            $builder->where('a.appointment_date', $current_date);
        }

        if ($_POST['type'] == 2) {
            $builder->where('a.from_date_time > ', $from_date_time);
        }
        $builder->groupStart();
        $builder->where('a.appointment_to', $user_id);
        $builder->orWhere('a.hospital_id', $user_id);
        $builder->groupEnd();

        return $builder->countAllResults();
    }

    /**
     * Update Table.
     *
     * @param mixed $where
     * @param mixed $data
     * @return mixed
     */
    public function updateTable($where, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->update($data, $where);
        return ($this->db->affectedRows() != 1) ? false : true;
    }
}
