<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class UserModel extends Model
{
    protected $db;

    protected string $doctor = 'users d';
    protected string $doctor_details = 'users_details dd';
    protected $table = 'users';
    protected string $user_details = 'users_details';
    protected string $specialization = 'specialization s';
    protected string $labTests = 'lab_tests lt';
    protected string $users = 'users u';


    protected mixed $doctor_column_search = array('CONCAT(d.first_name," ",d.last_name)', 'd.profileimage', 'd.email', 'd.mobileno', 'd.created_date', 's.specialization');
    protected mixed $doctor_default_column_order = array('d.id' => 'DESC'); // default order 

    protected mixed $patient_column_search = array('CONCAT(p.first_name," ",p.last_name)', 'p.profileimage', 'p.email', 'p.mobileno', 'pd.dob', 'pd.blood_group', 'date_format(p.created_date,"%d %b %Y")');
    protected mixed $patient_default_order = array('p.id' => 'DESC'); // default order
    protected mixed $patient_column_order = array('', 'p.id', 'p.first_name', 'pd.dob', 'pd.blood_group', 'p.email', 'p.mobileno', 'p.created_date', 'p.status', 'last_vist', 'last_paid');

    protected string $patient = 'users p';
    protected string $patient_details = 'users_details pd';

    protected string $clinic = 'users c';
    protected string $clinic_details = 'users_details cd';
    protected string $clinic_doctors = 'clinic_details cdo';
    protected mixed $clinic_column_search = array('CONCAT(c.first_name," ",c.last_name)', 'cd.clinic_name', 'c.profileimage', 'c.email', 'c.mobileno', 'c.created_date', 's.specialization');
    protected mixed $clinic_order = array('c.profileimage' => 'ASC', 'c.id' => 'ASC', 'cd.clinic_name' => 'ASC', 's.specialization' => 'ASC', 'c.email' => 'ASC', 'c.mobileno' => 'ASC', 'c.created_date' => 'ASC', 'cd.amount' => 'ASC', 'c.status' => 'ASC'); // default order 

    protected mixed $clinic_doctor_search = array('cdo.clinic_name', 's.specialization');
    protected mixed $clinic_doctors_column_search = array('cd.clinic_name', 'CONCAT(c.first_name," ",c.last_name)', 'c.email', 'c.mobileno', 'date_format(c.created_date,"%d %b %Y")', 's.specialization');
    // var $clinic_doctor_order = array('cdo.clinic_details_id' => 'ASC', 's.specialization'=>'ASC','cdo.status'=>'ASC'); 
    protected mixed $clinic_doctors_order = array('cd.clinic_name', 'CONCAT(c.first_name," ",c.last_name)', 'c.email', 'c.mobileno', 'date_format(d.created_date,"%d %b %Y")', 's.specialization');
    protected string $lab = 'users l';
    protected string $lab_details = 'users_details ld';
    protected mixed $lab_column_search = array('l.id', 'l.first_name', 'l.last_name', 'l.profileimage', 'l.email', 'l.mobileno', 'l.created_date');
    protected mixed $lab_order = array('l.id' => 'DESC'); // default order
    protected mixed $lab_column_order = array('', 'l.id', 'l.first_name', 'l.email', 'l.mobileno', 'l.created_date', '');
    protected string $lab_payments = 'lab_payments lp';
    protected string $lab_tests = 'lab_tests lt';
    protected mixed $labtest_column_search = array('p.first_name', 'p.last_name', 'l.first_name', 'l.last_name', 'lt.lab_test_name', 'lp.lab_test_date', 'lp.order_id', 'lp.total_amount', 'lp.payment_type');
    protected mixed $labtest_order = array('lp.id' => 'DESC'); // default order
    protected mixed $labtest_column_order = array('', 'lp.order_id', 'p.first_name', 'l.first_name', 'lt.lab_test_name', 'lp.lab_test_date', '', 'lp.payment_type');
    protected mixed $labtestlist_column_search = array('u.first_name', 'u.last_name', 'u.username', 'lt.lab_test_name', 'lt.amount', 'lt.duration', 'lt.description', 'lt.created_date');
    protected mixed $labtestlist_order = array('', 'u.last_name', 'lt.lab_test_name', 'lt.amount', 'lt.duration', 'lt.description', 'lt.created_date'); // default order 
    protected mixed $labtestlist_column_order = array('', 'u.first_name', 'lt.lab_test_name', '', 'lt.duration', 'lt.description', 'lt.created_date');

    protected string $pharmacy = 'users ph';
    protected string $pharmacy_details = 'users_details phd';
    protected string $pharmacy_specifications = 'pharmacy_specifications phs';
    protected mixed $pharmacy_column_search = array('ph.pharmacy_name', 'CONCAT(ph.first_name," ",ph.last_name)', 'ph.profileimage', 'ph.email', 'ph.mobileno', 'phs.home_delivery', 'phs.24hrsopen', 'phs.pharamcy_opens_at', 'date_format(ph.created_date,"%d %b %Y")');
    protected mixed $pharmacy_default_order = array('ph.id' => 'DESC'); // default order
    protected mixed $pharmacy_order = array('', 'ph.pharmacy_name', 'ph.email', 'ph.mobileno', 'phs.home_delivery', 'phs.24hrsopen', 'phs.pharamcy_opens_at', 'ph.created_date', 'ph.status');

    protected mixed $column_search = array('od.full_name', 'p.first_name','p.last_name', 'us.first_name', 'us.last_name', 'o.order_id', 'o.quantity', 'o.payment_type', 'o.subtotal', 'od.created_at', 'o.order_status');
    protected mixed $column_order = array(
        'od.order_user_details_id', // default order
        'o.order_id',
        'us.pharmacy_name',
        'qty',
        'LENGTH(o.subtotal)',
        'o.payment_type',
        'o.order_status',
        'od.created_at'
    ); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * Get User Details.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getUserDetails($id)
    {
        $builder = $this->db->table('users');
        $builder->select('users.pharmacy_name,users.first_name,users.last_name,users.email,users.mobileno,users.country_code,users.country_id,users.profileimage,users.id_petcareclub,ud.*');
        $builder->join('users_details ud', 'ud.user_id = users.id', 'left');
        $builder->where('users.id', $id);
        $result = $builder->get()->getRowArray();
        return $result;
    }
    /**
     * Get DoctorDatatables.
     * 
     * @param mixed $input
     * @return mixed
     */
    public function getDoctorDatatables($input)
    {
        $builder = $this->db->table($this->doctor);
        $builder->select('d.*,s.specialization');
        $builder->join($this->doctor_details, 'dd.user_id = d.id', 'left');
        $builder->join($this->specialization, 'dd.specialization = s.id', 'left');
        $builder->where('d.role', '1');
        //Muddasar Ali updated 
        $builder->groupBy('d.id');

        $i = 0;

        foreach ($this->doctor_column_search as $item) // loop column 
        {
            if ($input['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else if ($item == 'd.created_date') {
                    $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->doctor_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($input['order'])) // here order processing
        {
            $builder->orderBy('id', $input['order']['0']['dir']);

            //$this->db->order_by($this->column_order[$inputdata['order']['0']['column']], $inputdata['order']['0']['dir']);
        } else if (isset($this->doctor_default_column_order)) {
            $order = $this->doctor_default_column_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Doctor Count All.
     * 
     * 
     * @return mixed
     */
    public function doctorCountAll()
    {
        $builder = $this->db->table($this->doctor);
        $builder->where('d.role', '1');

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
    /**
     * Get Doctor Details.
     * 
     * @param mixed $username
     * @return mixed
     */
    public function getDoctorDetails($username)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value,u.role');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role != 2');
        $builder->where("(u.status = '1' OR u.status = '2')");

        if (empty(session('admin_id'))) {
            $builder->where('u.is_verified', '1');
            $builder->where('u.is_updated', '1');
        }
        $builder->where('u.username', $username);
        return $result = $builder->get()->getRowArray();
        // print_r($result);exit;
        // echo $this->db->getLastQuery();exit;
    }
    /**
     * Clinic Images.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function clinicImages($id)
    {
        $builder = $this->db->table('clinic_images');
        $builder->select('clinic_image,user_id');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Favourites.
     * 
     * @param mixed $where
     * @return mixed
     */
    public function getFavourites($where)
    {
        $builder = $this->db->table('favourities');
        $result = $builder->getWhere($where)->getResultArray();
        return $result;
    }
    /**
     * Get Education Details.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getEducationDetails($id)
    {
        $builder = $this->db->table('education_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Experience Details.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getExperienceDetails($id)
    {
        $builder = $this->db->table('experience_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Awards Details.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getAwardsDetails($id)
    {
        $builder = $this->db->table('awards_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Memberships Details.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getMembershipsDetails($id)
    {
        $builder = $this->db->table('memberships_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Registrations Details.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getRegistrationsDetails($id)
    {
        $builder = $this->db->table('registrations_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Business Hours.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getBusinessHours($id)
    {
        $builder = $this->db->table('business_hours');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Monday Hours.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getMondayHours($id)
    {
        $builder = $this->db->table('schedule_timings');
        $builder->select('*');
        $builder->where('day_id', 2);
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Sunday Hours.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getSundayHours($id)
    {
        $builder = $this->db->table('schedule_timings');
        $builder->select('*');
        $builder->where('day_id', 1);
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Tuesday Hours.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getTueHours($id)
    {
        $builder = $this->db->table('schedule_timings');
        $builder->select('*');
        $builder->where('day_id', 3);
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Wednesday Hours.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getWedHours($id)
    {
        $builder = $this->db->table('schedule_timings');
        $builder->select('*');
        $builder->where('day_id', 4);
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Thursday Hours.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getThuHours($id)
    {
        $builder = $this->db->table('schedule_timings');
        $builder->select('*');
        $builder->where('day_id', 5);
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Friday Hours.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getFriHours($id)
    {
        $builder = $this->db->table('schedule_timings');
        $builder->select('*');
        $builder->where('day_id', 6);
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Saturday Hours.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getSatHours($id)
    {
        $builder = $this->db->table('schedule_timings');
        $builder->select('*');
        $builder->where('day_id', 7);
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Review List View.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function reviewListView($id)
    {
        $builder = $this->db->table('rating_reviews r');
        $where = array('r.doctor_id' => $id);
        return $builder
            ->select('u.profileimage,u.first_name,u.last_name,d.profileimage as doctor_image,d.first_name as doctor_firstname,d.last_name as doctor_lastname,r.*,rr.id as reply_id,rr.reply as reply,rr.created_date as reply_date')
            ->join('users u ', 'u.id = r.user_id')
            ->join('users d ', 'd.id = r.doctor_id', 'left')
            ->join('review_reply rr', 'r.id = rr.review_id', 'left')
            ->getWhere($where)
            ->getResultArray();
    }
    /**
     * Get Socialmedia Details.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getSocialmediaDetails($id)
    {
        $builder = $this->db->table('social_media');
        $builder->select('*');
        $builder->where('doctor_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * check Email.
     * 
     * @param mixed $email
     * @return mixed
     */
    public function checkEmail($email)
    {
        // $builder = $this->db->table('users');
        // $builder->select('id,email');
        // $builder->where('email', $email);
        // $get_result = $builder->get();
        // $result="";
        // if($builder->countAllResults() >0)
        // {
        //     return $result = $get_result->getRow();
        // }
        // return $result;       


        $builder = $this->db->table('users');
        $builder->select('id,email');
        $builder->where('email', $email);
        $result = $builder->get()->getRowArray();
        return $result;
    }
    /**
     * Insert Data.
     * 
     * @param mixed $tableName
     * @param mixed $data
     * @return mixed
     */
    public function insertData($tableName, $data)
    {
        $this->db->table($tableName)->insert($data);
        return ($this->db->affectedRows() != 1) ? false : true;
    }
    /**
     * Get Patient Datatables.
     * 
     * @param mixed $inputdata
     * @return mixed
     */
    public function getPatientDatatables($inputdata)
    {
        $builder = $this->db->table($this->patient);
        $builder->select('p.*,pd.dob,pd.blood_group,pd.currency_code,(select appointment_date from appointments where appointment_from=p.id order by id desc limit 1) as last_vist,(select TRUNCATE(total_amount,2) from payments where user_id=p.id order by id desc limit 1) as last_paid');
        $builder->join($this->patient_details, 'pd.user_id = p.id', 'left');
        $builder->where('p.role', '2');


        $i = 0;

        foreach ($this->patient_column_search as $item) // loop column 
        {
            if ($inputdata['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($inputdata['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($inputdata['search']['value']));
                }

                if (count($this->patient_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($inputdata['order'])) // here order processing
        {
            // $this->db->order_by('id', $input['order']['0']['dir']);

            $builder->orderBy($this->patient_column_order[$inputdata['order']['0']['column']], $inputdata['order']['0']['dir']);
        } else if (isset($this->patient_default_order)) {
            $order = $this->patient_default_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($inputdata['length'] != -1)
            $builder->limit($inputdata['length'], $inputdata['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Patient Count All.
     * 
     * @return mixed
     */
    public function patient_count_all()
    {
        $builder = $this->db->table($this->patient);
        $builder->where('p.role', '2');
        return $builder->countAllResults();
    }
    /**
     * Get Clinic Datatables.
     * 
     * @param mixed $inputdata
     * @return mixed
     */
    public function getClinicDatatables($inputdata)
    {
        $builder = $this->db->table($this->clinic);
        $builder->select('c.*,s.specialization,CONCAT(c.first_name," ", c.last_name) as doctor_name,c.first_name as doc_first_name,c.last_name as doc_last_name, cd.clinicname, cd.clinic_name');
        //$builder->select('c.*,s.specialization, cd.clinic_name');
        $builder->join($this->clinic_details, 'cd.user_id = c.id', 'left');
        $builder->join($this->specialization, 'cd.specialization = s.id', 'left');
        $builder->where('c.role', '6');
        $builder->where('c.status !=', 0);
        $builder->orderBy("c.id", "desc");
        // Updated Muddasar Ali 
        $builder->groupBy('c.id');

        $i = 0;

        foreach ($this->clinic_column_search as $item) // loop column 
        {
            if ($inputdata['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($inputdata['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($inputdata['search']['value']));
                }

                if (count($this->clinic_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($inputdata['order'])) // here order processing
        {
            $clinic_order = $this->clinic_order;
            $column = $inputdata['order']['0']['column'];
            $dir = $inputdata['order']['0']['dir'];
            $builder->orderBy(array_keys($clinic_order)[$column], $dir);
        } else if (isset($this->clinic_order)) {
            $builder->orderBy('id', 'ASC');
        }
        if ($inputdata['length'] != -1)
            $builder->limit($inputdata['length'], $inputdata['start']);
        $query = $builder->get();
        return $query->getResultArray();
        // echo $this->db->getLastQuery();
        // exit;
    }
    /**
     * Clinic Count ALL.
     * 
     * @return mixed
     */
    public function clinicCountAll()
    {
        $builder = $this->db->table($this->clinic);
        $builder->where('c.role', '6');
        $builder->where('c.status !=', 0);
        return $builder->countAllResults();
    }
    /**
     * Get ById.
     * 
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getById($id)
    {
        $builder = $this->db->table($this->table);
        $builder->where('id', $id);
        $query = $builder->get();

        return $query->getRow();
    }
    /**
     * Get Clinic Name.
     * 
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getClinicName($id)
    {
        $builder = $this->db->table($this->user_details);
        $builder->where('user_id', $id);
        $query = $builder->get();

        return $query->getRow()->clinic_name;
    }
    /**
     * Get Clinic Doctor Datatables.
     * 
     * 
     * @param mixed $id
     * @param mixed $inputdata
     * @return mixed
     */
    public function getClinicDoctorDatatables($id, $inputdata)
    {
        $builder = $this->db->table($this->clinic);
        $builder->select('c.*,s.specialization');
        $builder->join($this->clinic_details, 'cd.user_id = c.id', 'left');
        $builder->join($this->specialization, 'cd.specialization = s.id', 'left');
        $builder->where('c.status !=', 0);
        $builder->where('c.hospital_id', $id);


        $i = 0;


        foreach ($this->clinic_doctors_column_search as $item) // loop column 
        {
            if ($inputdata['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($inputdata['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($inputdata['search']['value']));
                }

                if (count($this->clinic_doctors_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($inputdata['order'])) // here order processing
        {
            $clinic_order = $this->clinic_doctors_order;
            $column = $inputdata['order']['0']['column'];
            $dir = $inputdata['order']['0']['dir'];
            $builder->orderBy(array_keys($clinic_order)[$column], $dir);
        } else if (isset($this->clinic_doctors_order)) {
            $builder->orderBy('c.id', 'ASC');
        }
        if ($inputdata['length'] != -1)
            $builder->limit($inputdata['length'], $inputdata['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Clinic Doctor Count ALL.
     * 
     * 
     * @param mixed $id
     * @return mixed
     */
    public function clinicDoctorCountAll($id)
    {
        $builder = $this->db->table($this->clinic_doctors);
        $builder->where('doctor_id', $id);
        return $builder->countAllResults();
    }
    /**
     * Clinic Doctor Count ALL.
     * 
     * 
     * @param mixed $username
     * @return mixed
     */
    public function getClinicDetails($username)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,clinicimage.clinic_image,sp.specialization as speciality,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value, sp.specialization_img');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('clinic_images clinicimage', 'u.id = clinicimage.user_id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role', '6');
        $builder->where('u.username', $username);
        return $result = $builder->get()->getRowArray();
    }

    // public function get_datatables($user_id)
    // {
    //     $builder = $this->db->table($this->labTests);
    //     $builder->select('lt.*, CONCAT(u.first_name," ", u.last_name) as lab_name, u.profileimage,u.profileimage as lab_profileimage');
    //     $builder->join($this->users, 'u.id = lt.lab_id', 'left');
    //     //$this->db->join($this->user_details,'ud.user_id = lt.lab_id','left');
    //     $this->db->where('lt.lab_id', $user_id);
    //     $i = 0;
    //     foreach ($this->column_search as $item) {
    //         if ($_POST['search']['value']) {

    //             if ($item == 'created_date') {
    //                 $_POST['search']['value'] = date('d M Y', $_POST['search']['value']);
    //                 // date('d M Y',strtotime($lab_tests['created_date']))
    //                 // $item = 
    //             }

    //             if ($item == 'lab_name') {
    //                 $_POST['search']['value'] = $_POST['search']['value'];
    //             }

    //             if ($i === 0) {
    //                 $this->db->group_start();
    //                 $this->db->like($item, $_POST['search']['value']);
    //             } else {
    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }

    //             if (count($this->column_search) - 1 == $i)
    //                 $this->db->group_end();
    //         }
    //         $i++;
    //     }
    //     if (isset($_POST['order'])) {
    //         $this->db->order_by('id', $_POST['order']['0']['dir']);
    //     } else if (isset($this->order)) {
    //         $order = $this->order;
    //         $this->db->order_by(key($order), $order[key($order)]);
    //     }
    //     if ($_POST['length'] != -1)
    //         $this->db->limit($_POST['length'], $_POST['start']);
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }

    // public function count_filtered($user_id)
    // {
    //     $this->_get_datatables_query($user_id);
    //     $query = $this->db->get();
    //     return $query->num_rows();
    // }

    // public function count_all($user_id)
    // {
    //     $this->db->where('lt.lab_id', $user_id);
    //     $this->db->from($this->table);
    //     return $this->db->count_all_results();
    // }
    /**
     * Get Lab Datatables
     * 
     * 
     * @param mixed $input
     * @return mixed
     */
    public function getLabDatatables($input)
    {
        $builder = $this->db->table($this->lab);
        $builder->select('l.*');
        $builder->join($this->lab_details, 'ld.user_id = l.id', 'left');
        $builder->where('l.role', '4');
        $i = 0;
        foreach ($this->lab_column_search as $item) // loop column 
        {
            if ($input['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->lab_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }
        if (isset($input['order'])) // here order processing
        {
            $builder->orderBy($this->lab_column_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->lab_order)) {
            $order = $this->lab_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Lab Count Filtered
     * 
     * 
     * @param mixed $input
     * @return mixed
     */
    public function labCountFiltered($input)
    {
        $builder = $this->db->table($this->lab);
        $builder->select('l.*');
        $builder->join($this->lab_details, 'ld.user_id = l.id', 'left');
        $builder->where('l.role', '4');
        $i = 0;
        foreach ($this->lab_column_search as $item) // loop column 
        {
            if ($input['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->lab_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }
        if (isset($input['order'])) // here order processing
        {
            $builder->orderBy($this->lab_column_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->lab_order)) {
            $order = $this->lab_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $query = $builder->get();
        return count($query->getResultArray());
    }
    /**
     * Lab Count All
     * 
     * 
     * @return mixed
     */
    public function labCountAll()
    {
        $builder = $this->db->table($this->lab);
        $builder->where('l.role', '4');
        return $builder->countAllResults();
    }
    /**
     * Get Booked Labtest Datatables
     * 
     * @param mixed $input
     * @return mixed
     */
    public function get_booked_labtest_datatables($input)
    {
        $builder = $this->db->table($this->lab_payments);
        $builder->select('lp.id as lp_id, lp.lab_id as lp_labid, lp.patient_id, lp.booking_ids, lp.order_id, lp.lab_test_date, lp.total_amount, lp.currency_code, lp.payment_type, lp.status as payment_status, p.first_name as patient_firstname, p.last_name as patient_lastname, l.first_name, l.last_name');
        $builder->join($this->lab_tests, 'lt.id = lp.booking_ids', 'left');
        $builder->join($this->patient, 'p.id = lp.patient_id', 'left');
        $builder->join($this->lab, 'l.id = lp.lab_id', 'left');

        $i = 0;

        foreach ($this->labtest_column_search as $item) // loop column 
        {
            if ($input['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->labtest_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($input['order'])) // here order processing
        {
            // $this->db->order_by('id', $input['order']['0']['dir']);

            $builder->orderBy($this->labtest_column_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->labtest_order)) {
            $order = $this->labtest_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        //echo $this->db->last_query();
        return $query->getResultArray();
    }

    /**
     * Booked Labtest Count Filtered
     * 
     * @param mixed $input
     * @return mixed
     */
    public function bookedLabtestCountFiltered($input)
    {
        $builder = $this->db->table($this->lab_payments);
        $builder->select('lp.id as lp_id, lp.lab_id as lp_labid, lp.patient_id, lp.booking_ids, lp.order_id, lp.lab_test_date, lp.total_amount, lp.currency_code, lp.payment_type, lp.status as payment_status, p.first_name as patient_firstname, p.last_name as patient_lastname, l.first_name, l.last_name');
        $builder->join($this->lab_tests, 'lt.id = lp.booking_ids', 'left');
        $builder->join($this->patient, 'p.id = lp.patient_id', 'left');
        $builder->join($this->lab, 'l.id = lp.lab_id', 'left');
        $i = 0;
        foreach ($this->labtest_column_search as $item) // loop column 
        {
            if ($input['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->labtest_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($input['order'])) // here order processing
        {
            $builder->orderBy($this->labtest_column_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->labtest_order)) {
            $order = $this->labtest_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $query = $builder->get();
        return count($query->getResultArray());
    }
    /**
     * Booked Labtest Count All
     * 
     * 
     * @return mixed
     */
    public function bookedLabtestCountAll()
    {
        $builder = $this->db->table($this->lab_payments);
        return $builder->countAllResults();
    }
    /*Booked Labtest*/
    /**
     * Get Lab Testname
     * 
     * @param mixed $ids
     * @return mixed
     */
    public function getLabTestname($ids)
    {
        $exp = explode(",", $ids);
        $builder = $this->db->table($this->lab_tests);
        $builder->select('GROUP_CONCAT(lab_test_name) as testname');
        $builder->whereIn('id', $exp);
        $res = $builder->get()->getRow()->testname;
        return $res;
    }
    /**
     * Get Lab Test Datatables
     * 
     * @param mixed $input
     * @return mixed
     */
    public function getLabtestDatatables($input)
    {
        $builder = $this->db->table($this->labTests);
        $builder->select('lt.*,u.first_name,u.last_name');
        $builder->join($this->users, 'u.id = lt.lab_id', 'left');

        $i = 0;

        foreach ($this->labtestlist_column_search as $item) // loop column 
        {
            if ($input['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($input['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
                }

                if (count($this->labtestlist_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($input['order'])) // here order processing
        {
            // $this->db->order_by('id', $input['order']['0']['dir']);

            $builder->orderBy($this->labtestlist_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->labtestlist_column_order)) {
            $order = $this->labtestlist_column_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * lab test Count Filtered
     * 
     * @param mixed $input
     * @return mixed
     */
    public function labtestCountFiltered($input)
    {
        $builder = $this->db->table($this->labTests);
        $builder->select('lt.*,u.first_name,u.last_name');
        $builder->join($this->users, 'u.id = lt.lab_id', 'left');

        $i = 0;
        foreach ($this->labtestlist_column_search as $item) // loop column 
        {
            if ($input['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, $input['search']['value']);
                } else {
                    $builder->orLike($item, $input['search']['value']);
                }

                if (count($this->labtestlist_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }
        if (isset($input['order'])) // here order processing
        {
            $builder->orderBy($this->labtestlist_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->labtestlist_column_order)) {
            $order = $this->labtestlist_column_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $query = $builder->get();
        return count($query->getResultArray());
    }
    /**
     * lab test Count All
     * 
     * 
     * @return mixed
     */
    public function labtestCountAll()
    {
        $builder = $this->db->table($this->labTests);
        return $builder->countAllResults();
    }
    /**
     * Get Pharmacy DatatablesQuery
     * 
     * 
     * @return mixed
     */
    private function getPharmacyDatatablesQuery()
    {
        $builder = $this->db->table($this->pharmacy);
        $builder->select('ph.*, phs.home_delivery, phs.24hrsopen as hrs_open, phs.pharamcy_opens_at');
        $builder->join($this->pharmacy_details, 'phd.user_id = ph.id', 'left');
        $builder->join($this->pharmacy_specifications, 'phs.pharmacy_id = ph.id', 'left');
        $builder->where('ph.role', '5');

        $i = 0;
        $searchValue = libsodiumEncrypt($_POST['search']['value']) ?? '';

        foreach ($this->pharmacy_column_search as $item) {
            if ($searchValue) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, $searchValue);
                } else {
                    $builder->orLike($item, $searchValue);
                }

                if (count($this->pharmacy_column_search) - 1 == $i) {
                    $builder->groupEnd();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            if ($_POST['order']['0']['column'] == 1) {
                $builder->orderBy('ph.first_name, ph.pharmacy_name', $_POST['order']['0']['dir']);
            } else {
                $columnIndex = $_POST['order']['0']['column'];
                $builder->orderBy($this->pharmacy_order[$columnIndex], $_POST['order']['0']['dir']);
            }
        } elseif (isset($this->pharmacy_default_order)) {
            $order = $this->pharmacy_default_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }

        return $builder;
    }
    /**
     * Get Pharmacy Datatables
     * 
     * 
     * @return mixed
     */
    public function getPharmacyDatatables()
    {
        $builder = $this->getPharmacyDatatablesQuery();
        $start = $_POST['start'] ?? 0;
        $length = $_POST['length'] ?? -1;

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Pharmacy Count Filters
     * 
     * 
     * @return mixed
     */
    public function pharmacyCountFiltered()
    {
        $builder = $this->getPharmacyDatatablesQuery();
        $query = $builder->get();
        return $query->getNumRows();
    }
    /**
     * Pharmacy Count All
     * 
     * @return mixed
     */
    public function pharmacyCountAll()
    {
        $builder = $this->db->table($this->pharmacy);
        $builder->where('ph.role', '5');
        return $builder->countAllResults();
    }
    /**
     * Invoice List Based On User
     * 
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function invoiceList($user_id)
    {
        $builder = $this->db->table('payments p');
        $builder->select('p.*,d.first_name as doctor_first_name,d.last_name as doctor_last_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,pi.first_name as patient_first_name, pi.last_name as patient_last_name,pi.profileimage as patient_profileimage,pi.id as patient_id,d.role');
        $builder->join('users d', 'd.id = p.doctor_id', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->join('users pi', 'pi.id = p.user_id', 'left');
        $builder->join('users_details pd', 'pd.user_id = pi.id', 'left');

        if (session('role') == '1' || session('role') == '4' || session('role') == '6') {
            $builder->where('p.doctor_id', $user_id);
        }
        if(session('role') == '5') {
            $builder->groupStart();
            $builder->where('p.doctor_id', $user_id);
            $builder->orWhere("FIND_IN_SET(".$user_id.", p.pharmacy_id)");
            $builder->groupEnd(); 
        }
        if (session('role') == '2') {
            $builder->where('p.user_id', $user_id);
        }

        $builder->where('p.payment_status', 1);


        $i = 0;

        $column_search = array('p.invoice_no', 'd.first_name', 'd.last_name', 'p.total_amount');
        foreach ($column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $builder->groupStart();
                }

                if ($item == 'p.payment_date') {
                    $this->builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
                } else if ($item == "d.first_name" || $item == "d.last_name") {
                    $builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
                } else {
                    $builder->orLike($item, $_POST['search']['value']);
                }


                if (count($column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        $column_order = array('', 'p.invoice_no', 'd.first_name', 'p.total_amount', 'p.payment_date', '');
        if (isset($_POST['order'])) // here order processing
        {
            // $builder->order_by('id', $_POST['order']['0']['dir']);
            $builder->orderBy($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        // $this->_get_datatables_query($user_id);
        if ($_POST['length'] != -1)
            $builder->limit($_POST['length'], $_POST['start']);

        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Total Invoice For User
     * 
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function countAllInvoice($user_id)
    {
        $builder = $this->db->table('payments p');

        $builder->where('p.payment_status', 1);

        if (session('role') == '1' || session('role') == '4' || session('role') == '6') {
            $builder->where('p.doctor_id', $user_id);
        }
        if(session('role') == '5') {
            $builder->groupStart();
            $builder->where('p.doctor_id', $user_id);
            $builder->orWhere("FIND_IN_SET(".$user_id.", p.pharmacy_id)");
            $builder->groupEnd(); 
        }
        if (session('role') == '2') {
            $builder->where('p.user_id', $user_id);
        }
        return $builder->countAllResults();
    }
/**
   * Get Datatables Query
   * 
   * 
   *
   * @return mixed
   */
    private function getOrdersDatatablesQuery()
  {
    $builder = $this->db->table('orders as o');
    $builder->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty,o.payment_type,o.status,o.order_id,o.subtotal,o.order_status,o.user_notify,o.pharmacy_notify,o.id as id,ud.currency_code as product_currency, "CAST(o.subtotal AS INT)" as orderby_subtotoal, o.currency_code,p.first_name as patient_firstname,p.last_name as patient_lastname');
    $builder->join('order_user_details as od', 'od.order_user_details_id = o.user_order_id', 'left');
    $builder->join('users as us', 'us.id = o.pharmacy_id', 'left');
    $builder->join('users_details as ud', 'ud.user_id = o.pharmacy_id', 'left');
    $builder->join('users as p', 'p.id = o.user_id', 'left');
    $builder->groupBy('o.id');
    $i = 0;
    foreach ($this->column_search as $item) // loop column 
    {
      if (isset($_POST['search']['value']) && $_POST['search']['value']) // if datatable send POST for search
      {
        if ($i === 0) // first loop
        {
          $builder->groupStart();
        }

        if ($item == 'od.created_at') {
          $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
        } else if ($item == 'p.first_name' || $item == 'p.last_name' || $item == 'us.first_name' || $item === 'us.last_name' || $item === 'us.pharmacy_name') {
          $builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
        } else {
          $builder->orLike($item, $_POST['search']['value']);
        }
        if (count($this->column_search) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }
    if (isset($_POST['order'])) // here order processing
    {
      $builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->column_order)) {
      $order = $this->column_order;
      $builder->orderBy($order[key($order)], 'DESC');
    }

    return $builder;
  }
  /**
   * Get Datatables 
   * 
   * 
   *
   * @return mixed
   */
  public function getOrderDatatables()
  {
    $builder = $this->getOrdersDatatablesQuery();
    if ($_POST['length'] != -1)
      $builder->limit($_POST['length'], $_POST['start']);
    $query = $builder->get();
    // echo $this->db->getLastQuery();
    return $query->getResultArray();
  }

  /**
   * Datatables Count Filter
   * 
   * 
   *
   * @return mixed
   */
  public function countFilteredOrders()
  {
    $builder = $this->getOrdersDatatablesQuery();
    $query = $builder->get();
    return $query->getNumRows();
  }

  public function getScheduleData($whereData)
    {
        $builder = $this->db->table('schedule_timings');
        $builder->select('*');
        $builder->where($whereData);
        $builder->orderBy('id','desc');
        $result = $builder->get()->getRowArray();
        return $result;
    }
    // Pet update code
    //added new on 13rd June 2024 by Muddasar
    public function getPetsByPatientId($patientId)
    {
        // Assuming 'pets' is the table storing pet data and 'patient_id' is the column
        // linking pets to their owners (patients).
        $query = $this->db->table('pets')
            ->where('patient_id', $patientId)
            ->get();

        return $query->getResultArray();
    }
    
    public function getPetById($pet_id)
    {
        return $this->db->table('pets')->where('id', $pet_id)->get()->getRowArray();
    }

    public function updatePetTable($where, $data)
    {
        $builder = $this->db->table('pets');
        $builder->update($data, $where);
        return ($this->db->affectedRows() != 1) ? false : true;
    }

}
