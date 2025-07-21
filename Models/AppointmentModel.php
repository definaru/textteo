<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
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

    protected $table = 'appointments a';
    protected string $users = 'users u';
    // protected $column_search = array('u.first_name','u.last_name','u.profileimage','a.appointment_date','a.from_date_time'); //set column field database for datatable searchable 
    protected mixed $column_search = array('u.first_name', 'date_format(a.appointment_date,"%d %b %Y")', 'a.type');
    protected mixed $order = array('a.id' => 'ASC'); // default order
    protected mixed $column_order = array('', 'u.first_name', 'a.appointment_date', 'a.type');

    // admin
    protected string $appoinments = 'appointments a';
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
    public function getAppoinmentById($appointment_id)
    {
        $builder = $this->db->table('appointments a');
        $builder->select('a.*,u.id as userid,u.first_name,u.last_name,u.username,u.profileimage,u.email,u.mobileno,c.country as countryname,s.statename,ci.city as cityname,p.per_hour_charge');
        $builder->join('users u', 'u.id = a.appointment_to', 'left');
        $builder->join('users_details ud', 'u.id = ud.user_id', 'left');
        $builder->join('payments p', 'p.id = a.payment_id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('a.id', $appointment_id);
        return $builder->get()->getResultArray();
    }
    /**
     * Get Today Lab patient
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function getTodayLabpatient($user_id)
    {
        $where = array('lab_id' => $user_id, 'lab_test_date' => date('Y-m-d'));
        $builder = $this->db->table('lab_payments');
        return $builder->groupBy('lab_id')->where($where)->countAllResults();
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
    public function patientAppoinmentsList($page, $limit, $type, $user_id)
    {
        $builder = $this->db->table('appointments a');
        $builder->select('a.*,u.id as userid,u.first_name,u.last_name,u.username,u.profileimage,u.email,u.mobileno,c.country as countryname,s.statename,ci.city as cityname,p.per_hour_charge');
        $builder->join('users u', 'u.id = a.appointment_to', 'left');
        $builder->join('users_details ud', 'u.id = ud.user_id', 'left');
        $builder->join('payments p', 'p.id = a.payment_id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('a.appointment_from', $user_id);
        $builder->where('a.appointment_status', 0);

        // Updated Muddasar Ali  7-12-2024 Book appointment twice
        $builder->groupBy('a.id');

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) // if datatable send POST for search
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

        $builder->orderBy('a.from_date_time', 'ASC');
        $builder->groupBy('a.id');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {

            $page = !empty($page) ? $page : '';
            if ($page >= 1) {
                $page = $page - 1;
            }
            $page =  ($page * $limit);
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * Doctor Or Clinic Appointments List
     * 
     * @param mixed $user_id
     * @param mixed $page
     * @param mixed $limit
     * @param mixed $type
     * @return mixed
     */
    public function doctorAppoinmentList($page, $limit, $type, $user_id)
    {
        $from_date_time = date('Y-m-d H:i:s');
        $builder = $this->db->table('appointments a');
        $builder->select('a.*,u.id as userid,u.first_name,u.last_name,u.username,u.profileimage,u.email,u.mobileno,c.country as countryname,s.statename,ci.city as cityname,p.per_hour_charge');
        $builder->join('users u', 'u.id = a.appointment_from', 'left');
        $builder->join('users_details ud', 'u.id = ud.user_id', 'left');
        $builder->join('payments p', 'p.id = a.payment_id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('a.appointment_to', $user_id);
        $builder->where('a.to_date_time > ', $from_date_time);
        $builder->where('a.appointment_status', 0);
        $builder->orderBy('a.from_date_time', 'ASC');
        $builder->groupBy('a.id');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($page) ? $page : '';
            if ($page >= 1) {
                $page = $page - 1;
            }
            $page =  ($page * $limit);
            $builder->limit($limit, $page);
            $query = $builder->get();
            return $query->getResultArray();
        }
    }
    /**
     * Doctor List by Clinic
     * 
     * @param mixed $user_id
     * @param mixed $type
     * @return mixed
     */
    public function verfiedDoctorsInClinic($user_id, $type = "")
    {

        $builder = $this->db->table('users u');
        $builder->select('u.id as id,CONCAT(u.first_name,u.last_name) as name,u.first_name,u.last_name ,u.email,u.country_code, u.mobileno as mobile,u.profileimage as profile,u.username as  username');
        $builder->join('users_details ud', 'u.id = ud.user_id', 'left');
        if ($type == 2) {
            $builder->where('u.id', $user_id);
            $query = $builder->get();
            $result = $query->getResultArray();
            return $result;
        }
        $builder->where('u.status', 1);
        $builder->where('u.hospital_id', $user_id);
        $builder->where('u.is_verified', 1);
        $builder->where('u.is_updated', 1);

        $query = $builder->get();
        $result = $query->getResultArray();
        if ($type == 1) {
            $result = $query->getNumRows();
        }
        return $result;
    }
    /**
     * Ongoing Call Get Doctor Detail
     *
     * @param mixed $appoinment_id
     * @return mixed
     */
    public function getAppoinmentCallDetails($appoinment_id)
    {
        $builder = $this->db->table('appointments a');
        $builder->select('a.*, d.first_name as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage, p.first_name as patient_name,p.profileimage as patient_profileimage,p.id as patient_id,d.id as doctor_id,d.first_name as doctor_firstname,d.last_name as doctor_lastname,p.first_name as patient_firstname,p.last_name as patient_lastname,d.device_id as doctor_device_id,d.device_type as doctor_device_type,p.device_id as patient_device_id,p.device_type as patient_device_type');
        $builder->join('users d', 'd.id = a.appointment_to', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->join('users p', 'p.id = a.appointment_from', 'left');
        $builder->join('users_details pd', 'pd.user_id = p.id', 'left');
        $builder->where('md5(a.id)', $appoinment_id);
        return $builder->get()->getRowArray();
    }
    /**
     * Call 
     * 
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function getCall($user_id)
    {
        $builder = $this->db->table('appointments a');
        $builder->select('c.call_type,c.id,c.appointments_id,u.first_name, u.last_name,u.username as name,u.profileimage,u.role');
        $builder->from('call_details c');
        $builder->join('users u', 'u.id = c.call_from', 'left');
        $builder->where('c.call_to', $user_id);
        return $builder->get()->getRowArray();
    }
    /**
     * Get Today Patient
     * 
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function getTodayPatient($user_id)
    {
        $where = ['appointment_to' => $user_id, 'appointment_date' => date('Y-m-d')];
        return $this->db->table('appointments')->select('appointment_from')->groupBy('appointment_from')->where($where)->countAllResults();
    }
    /**
     * Get Appoinments DatatablesQuery
     * 
     * 
     * @param mixed $type
     * @return mixed
     */
    private function getAppoinmentsDatatablesQuery($type)
    {
        $builder = $this->db->table($this->appoinments);
        $builder->select('a.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.first_name as doc_first_name,d.last_name as doc_last_name,d.username as doctor_username,d.profileimage as doctor_profileimage, CONCAT(p.first_name," ", p.last_name) as patient_name,p.first_name as patient_first_name,p.last_name as patient_last_name,p.profileimage as patient_profileimage,s.specialization as doctor_specialization,pa.total_amount,pd.currency_code,cliu.first_name as clinic_first_name,cliu.last_name as clinic_last_name,d.role,cliu.username as clinic_username, TRUNCATE(pa.total_amount,2) as total_amount_decimal');
        $builder->join($this->doctor, 'd.id = a.appointment_to', 'left');
        $builder->join($this->doctor_details, 'dd.user_id = d.id', 'left');
        $builder->join($this->patient, 'p.id = a.appointment_from', 'left');
        $builder->join($this->patient_details, 'pd.user_id = p.id', 'left');
        $builder->join($this->specialization, 'dd.specialization = s.id', 'left');
        $builder->join($this->payment, 'a.payment_id = pa.id', 'left');
        $builder->join('users cliu', 'cliu.id = a.hospital_id', 'left');
        $builder->join('users_details clud', 'clud.user_id = cliu.id', 'left');

        if ($type == 1) {
            //Get completed appointmets
            $builder->where('a.appointment_status', 1);
            $builder->where('a.call_status', 1);
        } elseif ($type == 2) {
            //Upcoming appointments
            $from_date_time = date('Y-m-d H:i:s');
            $builder->where('a.from_date_time >', $from_date_time);
            $builder->where('a.appointment_status', 0);
            $builder->where('a.call_status', 0);
        } elseif ($type == 3) {
            //missed apppointments
            $from_date_time = date('Y-m-d H:i:s');
            $builder->where('a.from_date_time <', $from_date_time);
            $builder->where('a.appointment_status', 2);
            $builder->where('a.call_status', 0);
        }

        // $builder->order_by('a.from_date_time','ASC');
        $builder->groupBy('a.id');


        $i = 0;

        foreach ($this->appoinments_column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($_POST['search']['value']));
                    //$builder->group_end(); //close bracket
                } else {
                    $builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
                }

                if (count($this->appoinments_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            // $builder->order_by('id', $_POST['order']['0']['dir']);

            $builder->orderBy($this->appointments_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->appoinments_default_order)) {
            $order = $this->appoinments_default_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        return $builder;
    }
    /**
     * Get Appoinments Datatables
     * 
     * 
     * @return mixed
     */
    public function getAppoinmentsDatatables()
    {
        $builder = $this->getAppoinmentsDatatablesQuery(1);
        if ($_POST['length'] != -1) {
            $builder->limit($_POST['length'], $_POST['start']);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Get Upappoinments Datatables
     * 
     * 
     * @return mixed
     */
    public function getUpappoinmentsDatatables()
    {
        $builder = $this->getAppoinmentsDatatablesQuery(2);
        if ($_POST['length'] != -1) {
            $builder->limit($_POST['length'], $_POST['start']);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Get MissedAppoinments Datatables
     * 
     * 
     * @return mixed
     */
    public function getMissedAppoinmentsDatatables()
    {
        $builder = $this->getAppoinmentsDatatablesQuery(3);
        if ($_POST['length'] != -1) {
            $builder->limit($_POST['length'], $_POST['start']);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Appoinments Count Filtered
     * 
     * @param mixed $type
     * @return mixed
     */
    public function appoinmentsCountFiltered($type)
    {
        $builder = $this->getAppoinmentsDatatablesQuery($type);
        $query = $builder->get();
        return $query->getNumRows();
    }
    /**
     * Appoinments Count All
     * 
     * @param mixed $type
     * @return mixed
     */
    public function appoinmentsCountAll($type)
    {
        $builder = $this->db->table($this->appoinments);
        if ($type == 1) {
            // Get completed appointments
            $builder->where('a.appointment_status', 1);
            $builder->where('a.call_status', 1);
        } elseif ($type == 2) {
            // Upcoming appointments
            $from_date_time = date('Y-m-d H:i:s');
            $builder->where('a.from_date_time >', $from_date_time);
            $builder->where('a.appointment_status', 0);
            $builder->where('a.call_status', 0);
        } elseif ($type == 3) {
            // Missed appointments
            $from_date_time = date('Y-m-d H:i:s');
            $builder->where('a.from_date_time <', $from_date_time);
            $builder->where('a.appointment_status', 1);
            $builder->where('a.call_status', 0);
        }
        $this->orderBy('a.from_date_time', 'ASC');
        return $this->countAllResults();
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
     * Get Total Patient
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function getTotalPatient($user_id)
    {
        $where = array('appointment_to' => $user_id);
        $builder = $this->db->table('appointments');
        $builder->select('COUNT(DISTINCT appointment_from) AS total');
        $builder->where($where);
        $query = $builder->get();
        $row = $query->getRow();
        return $row ? $row->total : 0;
    }
    /**
     * Check Doctor AvalSlot
     * 
     * @param mixed $user_id
     * @param mixed $day
     * @param mixed $start
     * @param mixed $end
     * @return mixed
     */
    public function checkDoctorAvalSlot($user_id, $day, $start, $end)
    {
        $builder = $this->db->query('SELECT * FROM `schedule_timings` WHERE user_id=? AND day_id=? AND (start_time<=?) AND end_time>=(?)', array($user_id, $day, $start, $end))->getNumRows();
        return $builder;
    }
    /**
     * Get Recent Booking
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function get_recent_booking($user_id)
    {
        $query = $this->db->table('appointments')
            ->select('*')
            ->groupStart()
            ->where('hospital_id', $user_id)
            ->orGroupStart()
            ->where('appointment_to', $user_id)
            ->groupEnd()
            ->groupEnd()
            ->get();

        return $query->getNumRows();
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
