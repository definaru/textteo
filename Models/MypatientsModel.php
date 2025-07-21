<?php

namespace App\Models;

use CodeIgniter\Model;

class MypatientsModel extends Model
{
  protected $db;
  protected string $appointments = 'appointments a';
  protected string $users = 'users u';
  protected string $users_details = 'users_details ud';
  protected string $specialization = 'specialization s';
  protected string $prescription = 'prescription p';
  protected string $medical_records = 'medical_records m';
  protected string $billing = 'billing b';



  protected string $country = 'country c';
  protected string $state = 'state s';
  protected string $city = 'city ci';
  protected string $prescription_item_details = 'prescription_item_details pd';
  protected string $signature = 'signature s';
  protected array $appoinments_column_search = array('u.first_name', 'u.last_name', 'a.appointment_date', 'a.created_date', 'a.type');
  protected array $appoinments_order = array('a.id' => 'DESC'); // default order 
  protected array $appoinments_column_order = array('', 'u.first_name', 'a.appointment_date', 'a.created_date', 'a.type'); // datatable column order 

  protected array $prescription_column_search = array('u.first_name', 'u.last_name', 'CONCAT(u.first_name," ", u.last_name)', 'u.profileimage', 'p.created_at');
  protected array $prescription_order = array('p.id' => 'DESC'); // default order 
  protected array $prescription_column_order = array('u.first_name', 'p.created_at');


  protected array $medical_records_column_search = array('u.first_name', 'u.last_name', 'CONCAT(u.first_name," ", u.last_name)', 'u.profileimage', 'm.date');
  protected array $medical_records_order = array('m.id' => 'DESC'); // default order 

  protected array $billing_column_search = array('CONCAT(u.first_name," ", u.last_name)', 'u.profileimage', 'date_format(b.created_at,"%d %b %Y")');
  protected array $billing_records_order = array('b.id' => 'DESC'); // default order 

  protected string $quotations = 'patient_request_quotation q';
  protected array $quotation_column_search = array('u.first_name', 'u.last_name');
  protected array $quotation_order = array('q.id' => 'DESC'); // default order
  protected string $pharmacy = 'users p';

  public function __construct()
  {
    parent::__construct();
    $this->db = \Config\Database::connect();
  }
  /**
   * Get patient List.
   * 
   * @param mixed $page
   * @param mixed $limit
   * @param mixed $type
   * @param mixed $user_id
   * @return mixed
   */
  public function patientList($page, $limit, $type, $user_id)
  {
    $builder = $this->db->table($this->appointments);
    $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
    $builder->join($this->users, 'a.appointment_from = u.id', 'left');
    $builder->join($this->users_details, 'ud.user_id = u.id', 'left');
    $builder->join($this->country, 'ud.country = c.countryid', 'left');
    $builder->join($this->state, 'ud.state = s.id', 'left');
    $builder->join($this->city, 'ud.city = ci.id', 'left');
    $builder->where('u.role', '2');
    $builder->where('a.appointment_to', $user_id);
    $builder->where('u.status', '1');

    if(session('role')==6){
      $builder->orwhere('a.hospital_id',$user_id);
    }

    $builder->groupBy('u.id, ud.id');//a.appointment_from

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
   * Get Patient Details.
   * 
   * @param mixed $userid
   * @return mixed
   */
  public function getPatientDetails($userid)
  {
    $builder = $this->db->table($this->users);
    $builder->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
    $builder->join($this->users_details, 'ud.user_id = u.id', 'left');
    $builder->join($this->country, 'ud.country = c.countryid', 'left');
    $builder->join($this->state, 'ud.state = s.id', 'left');
    $builder->join($this->city, 'ud.city = ci.id', 'left');
    $builder->where('u.id', $userid);
    return $result = $builder->get()->getRowArray();
  }
  /**
   * Get Last Booking.
   * 
   * @param mixed $userid
   * @return mixed
   */
  public function getLastBooking($userid)
  {
    $builder = $this->db->table($this->appointments);
    $builder->select('a.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
    $builder->join($this->users, 'u.id = a.appointment_to', 'left');
    $builder->join($this->users_details, 'ud.user_id = u.id', 'left');
    $builder->join($this->specialization, 'ud.specialization = s.id', 'left');
    $builder->where('a.appointment_from', $userid);
    if (is_doctor()) {
      $builder->where('a.appointment_to', session('user_id'));
    }
    if (isset($_GET['doctor_id'])) {
      $builder->where('a.appointment_to', $_GET['doctor_id']);
    }
    $builder->orderBy('id', 'DESC');
    $builder->limit('2');
    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Get Booking Prescription Status.
   * 
   * @param mixed $userid
   * @return mixed
   */
  public function getBookingPrescriptionStatus($userid)
  {
    $builder = $this->db->table($this->appointments);
    //$builder->select("count('a.*') as total, sum(case when a.appointment_status = '0' then 1 else 0 end) AS new, sum(case when a.appointment_status = '1' and a.call_status = 1 then 1 else 0 end) AS completed, sum(case when a.appointment_status = '2' then 1 else 0 end) AS expired");
    $builder->select("count('a.*') as total, sum(case when a.appointment_status = '0' then 1 else 0 end) AS new, sum(case when a.appointment_status = '1' then 1 else 0 end) AS completed, sum(case when a.appointment_status = '2' then 1 else 0 end) AS expired");
    $builder->where('a.appointment_from', $userid);
    if (is_doctor()) {
      $builder->where('a.appointment_to', session('user_id'));
    }
    $builder->orderBy('id', 'DESC');
    $query = $builder->get();
    $returnVal = $query->getRowArray();
    //echo $this->db->last_query();		
    if (!empty($returnVal)) {
      if ($returnVal['completed'] == 0) {
        $statusVal = 0;
      } else {
        $statusVal = 1;
      }
    } else {
      $statusVal = 0;
    }
    return $statusVal;
  }
  /**
   * Get Appoinments Datatables.
   * 
   * @param mixed $inputdata
   * @return mixed
   */
  public function getAppoinmentsDatatables($inputdata)
  {
    $builder = $this->db->table($this->appointments);
    $builder->select('a.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization,cliu.first_name as clinic_first_name,cliu.last_name as clinic_last_name,u.role,cliu.username as clinic_username');
    $builder->join($this->users, 'u.id = a.appointment_to', 'left');
    $builder->join($this->users_details, 'ud.user_id = u.id', 'left');
    $builder->join($this->specialization, 'ud.specialization = s.id', 'left');
    $builder->where('a.appointment_from', $inputdata['patient_id']);
    if (is_doctor()) {
      $builder->where('a.appointment_to', session('user_id'));
    }
    $builder->join('users cliu', 'cliu.id = a.hospital_id', 'left');
    $builder->join('users_details clud', 'clud.user_id = cliu.id', 'left');
    // $this->db->order_by('a.from_date_time','DESC');

    $i = 0;

    foreach ($this->appoinments_column_search as $item) // loop column 
    {
      if ($inputdata['search']['value']) // if datatable send POST for search
      {


        if ($item == 'type') {
          $inputdata['search']['value'] = $inputdata['search']['value'];
        }
        if ($item == 'first_name') {
          $inputdata['search']['value'] = libsodiumEncrypt($inputdata['search']['value']);
        }

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $builder->like($item, libsodiumEncrypt($inputdata['search']['value']));
        } else if ($item == 'a.created_date' || $item == 'a.appointment_date') {
          $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
        } else {
          $builder->orLike($item, libsodiumEncrypt($inputdata['search']['value']));
        }

        if (count($this->appoinments_column_search) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($inputdata['order'])) // here order processing
    {
      // $this->db->order_by('id', $inputdata['order']['0']['dir']);

      $builder->orderBy($this->appoinments_column_order[$inputdata['order']['0']['column']], $inputdata['order']['0']['dir']);
    } else if (isset($this->appoinments_order)) {
      $order = $this->appoinments_order;
      $builder->orderBy(key($order), $order[key($order)]);
    }
    if ($inputdata['length'] != -1)
      $builder->limit($inputdata['length'], $inputdata['start']);
    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Appoinments Count All.
   * 
   * @param mixed $inputdata
   * @return mixed
   */
  public function appoinments_count_all($inputdata)
  {
    $builder = $this->db->table($this->appointments);
    $builder->where('a.appointment_from', $inputdata['patient_id']);
    if (is_doctor()) {
      $builder->where('a.appointment_to', session('user_id'));
    }
    return $builder->countAllResults();
  }
  /**
   * Get Prescription Datatables.
   * 
   * @param mixed $inputdata
   * @return mixed
   */
  public function getPrescriptionDatatables($inputdata)
  {
    $builder = $this->db->table($this->prescription);
    $builder->select('p.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
    $builder->join($this->users, 'u.id = p.doctor_id', 'left');
    $builder->join($this->users_details, 'ud.user_id = u.id', 'left');
    $builder->join($this->specialization, 'ud.specialization = s.id', 'left');
    $builder->join($this->quotations, 'p.id = q.prescription_id', 'left');
    $builder->where('p.patient_id', $inputdata['patient_id']);
    $builder->where('p.status!=', 1);
    if (is_doctor()) {
      $builder->where('p.doctor_id', session('user_id'));
    }
    $i = 0;

    foreach ($this->prescription_column_search as $item) // loop column 
    {
      if ($inputdata['search']['value']) // if datatable send POST for search
      {
        // if ($item == 'created_date') {
        //   $inputdata['search']['value'] = date('d M Y', $inputdata['search']['value']);
        //   // date('d M Y',strtotime($lab_tests['created_date']))
        //   // $item = 
        // }

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $builder->like($item, libsodiumEncrypt($inputdata['search']['value']));
        } else if ($item == 'p.created_at') {
          $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $inputdata['search']['value']);
        } else {
          $builder->orLike($item, libsodiumEncrypt($inputdata['search']['value']));
        }

        if (count($this->prescription_column_search) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($inputdata['order'])) // here order processing
    {
      //  $this->db->order_by('id', $inputdata['order']['0']['dir']);

      $builder->orderBy($this->prescription_column_order[$inputdata['order']['0']['column']], $inputdata['order']['0']['dir']);
    } else if (isset($this->prescription_order)) {
      $order = $this->prescription_order;
      $builder->orderBy(key($order), $order[key($order)]);
    }

    if ($inputdata['length'] != -1)
      $builder->limit($inputdata['length'], $inputdata['start']);
    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Prescription Count All.
   * 
   * @param mixed $inputdata
   * @return mixed
   */
  public function prescriptionCountAll($inputdata)
  {
    $builder = $this->db->table($this->prescription);
    $builder->where('p.patient_id', $inputdata['patient_id']);
    $builder->where('p.status!=', 1);
    if (is_doctor()) {
      $builder->where('p.doctor_id', session('user_id'));
    }
    return $builder->countAllResults();
  }
  /**
   * Get Prescription Details.
   * 
   * @param mixed $prescription_id
   * @return mixed
   */
  public function getPrescriptionDetails($prescription_id)
  {
    $builder = $this->db->table($this->prescription_item_details);
    $builder->select('CONCAT(u.first_name," ", u.last_name) as doctor_name,u.first_name as doc_firstname,u.last_name as doc_last_name,CONCAT(u1.first_name," ", u1.last_name) as patient_name,u1.first_name as pat_first_name,u1.last_name as pat_last_name,pd.*,p.signature_id,s.img,s.rowno,DATE_FORMAT(pd.created_at, "%d-%m-%Y") as prescription_date');
    $builder->join($this->prescription, 'pd.prescription_id=p.id', 'left');
    $builder->join($this->signature, 'p.signature_id=s.id', 'left');
    $builder->join($this->users, 'u.id = p.doctor_id', 'left');
    $builder->join('users u1', 'u1.id = p.patient_id', 'left');
    $builder->where('p.id', $prescription_id);
    return $builder->get()->getResultArray();
  }


    public function getPrescriptionDetailsV2($prescription_id)
    {
      $builder = $this->db->table($this->prescription_item_details);
      $builder->select('u.id as doctor_id,CONCAT(u.first_name," ", u.last_name) as doctor_name,u.first_name as doc_firstname,u.last_name as doc_last_name,CONCAT(u1.first_name," ", u1.last_name) as patient_name,u1.first_name as pat_first_name,u1.last_name as pat_last_name,pd.*,a.reason,a.from_date_time,p.diagnosis,p.signature_id,s.img,s.rowno,DATE_FORMAT(pd.created_at, "%d-%m-%Y") as prescription_date,ud.clinicname as clinic_name');
      $builder->join($this->prescription, 'pd.prescription_id=p.id', 'left');
      $builder->join($this->appointments, 'p.appointment_id=a.id', 'left');
      $builder->join($this->signature, 'p.signature_id=s.id', 'left');
      $builder->join($this->users, 'u.id = p.doctor_id', 'left');
      $builder->join($this->users_details, 'u.id = ud.user_id', 'left');
      $builder->join('users u1', 'u1.id = p.patient_id', 'left');
      $builder->where('p.id', $prescription_id);
      return $builder->get()->getResultArray();
    }
  /**
   * Get Prescription
   * 
   * @param mixed $prescription_id
   * @return mixed
   */
  public function getPrescription($prescription_id)
  {
    $builder = $this->db->table($this->prescription_item_details);
    $builder->select('pd.*,p.signature_id,s.img,s.rowno');
    $builder->join($this->prescription, 'pd.prescription_id=p.id', 'left');
    $builder->join($this->signature, 'p.signature_id=s.id', 'left');
    $builder->where('p.id', $prescription_id);
    return $builder->get()->getResultArray();
  }
  /**
   * Update data
   * 
   * @param mixed $tableName
   * @param mixed $whereData
   * @param mixed $data
   * @return mixed
   */
  public function updateData($tableName, $whereData, $data)
  {
    $this->db->table($tableName)->where($whereData)->set($data)->update();
    if ($data && $this->db->table($tableName)->where($whereData)->set($data)->update()) {
      return $this->db->affectedRows();
    }
    return false;
  }
  /**
   * Get Medical Record Datatables
   * 
   *
   * @param mixed $inputdata
   * @return mixed
   */
  public function getMedicalRecordDatatables($inputdata)
  {
    $builder = $this->db->table($this->medical_records);
    $builder->select('m.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
    $builder->join($this->users, 'u.id = m.doctor_id', 'left');
    $builder->join($this->users_details, 'ud.user_id = u.id', 'left');
    $builder->join($this->specialization, 'ud.specialization = s.id', 'left');
    $builder->where('m.patient_id', $inputdata['patient_id']);
    $builder->where('m.status', 1);
    if (is_doctor()) {
      $builder->where('m.doctor_id', session('user_id'));
    }

    $i = 0;

    foreach ($this->medical_records_column_search as $item) // loop column 
    {
      if ($inputdata['search']['value']) // if datatable send POST for search
      {

        if ($item == 'u.first_name') {
          $inputdata['search']['value'] = libsodiumEncrypt($inputdata['search']['value']);
        }

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.

        }

        if ($item == 'm.date') {
          $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
        } else {
          $builder->orLike($item, libsodiumDecrypt($inputdata['search']['value']));
        }

        if (count($this->medical_records_column_search) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($inputdata['order'])) // here order processing
    {
      $builder->orderBy('id', $inputdata['order']['0']['dir']);

      //$this->db->order_by($this->column_order[$inputdata['order']['0']['column']], $inputdata['order']['0']['dir']);
    } else if (isset($this->medical_records_order)) {
      $order = $this->medical_records_order;
      $builder->orderBy(key($order), $order[key($order)]);
    }
    if ($inputdata['length'] != -1)
      $builder->limit($inputdata['length'], $inputdata['start']);
    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Medical Record Count All
   * 
   *
   * @param mixed $inputdata
   * @return mixed
   */
  public function medicalRecordCountAll($inputdata)
  {
    $builder = $this->db->table($this->medical_records);
    $builder->where('m.patient_id', $inputdata['patient_id']);
    $builder->where('m.status', 1);
    if (is_doctor()) {
      $builder->where('m.doctor_id', session('user_id'));
    }
    return $builder->countAllResults();
  }
  /**
   * Get Billing Datatables
   * 
   *
   * @param mixed $input
   * @return mixed
   */
  public function getBillingDatatables($input)
  {
    $builder = $this->db->table($this->billing);
    $builder->select('b.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
    $builder->join($this->users, 'u.id = b.doctor_id', 'left');
    $builder->join($this->users_details, 'ud.user_id = u.id', 'left');
    $builder->join($this->specialization, 'ud.specialization = s.id', 'left');
    $builder->where('b.patient_id', $input['patient_id']);
    $builder->where('b.status!=', 1);
    if (is_doctor()) {
      $builder->where('b.doctor_id', session('user_id'));
    }

    $i = 0;

    foreach ($this->billing_column_search as $item) // loop column 
    {
      if ($input['search']['value']) // if datatable send POST for search
      {

        if ($item == 'created_at') {
          $input['search']['value'] = date('d M Y', $input['search']['value']);
          // date('d M Y',strtotime($lab_tests['created_date']))
          // $item = 
        }



        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $builder->like($item, libsodiumEncrypt($input['search']['value']));
        } else {
          $builder->orLike($item, libsodiumEncrypt($input['search']['value']));
        }

        if (count($this->billing_column_search) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($input['order'])) // here order processing
    {
      $builder->orderBy('id', $input['order']['0']['dir']);

      //$this->db->order_by($this->column_order[$input['order']['0']['column']], $input['order']['0']['dir']);
    } else if (isset($this->billing_records_order)) {
      $order = $this->billing_records_order;
      $builder->orderBy(key($order), $order[key($order)]);
    }
    if ($input['length'] != -1)
      $builder->limit($input['length'], $input['start']);
    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Billing CountAll
   * 
   *
   * @param mixed $input
   * @return mixed
   */
  public function billingCountAll($input)
  {
    $builder = $this->db->table($this->billing);
    $builder->where('b.patient_id', $input['patient_id']);
    $builder->where('b.status!=', 1);
    if (is_doctor()) {
      $builder->where('b.doctor_id', session('user_id'));
    }
    return $builder->countAllResults();
  }
  /**
   * Get Billing Details
   * 
   *
   * @param mixed $billing_id
   * @return mixed
   */
  public function getBillingDetails($billing_id)
  {
    $builder = $this->db->table('billing_item_details bd');
    $builder->select('CONCAT(u.first_name," ", u.last_name) as doctor_name,u.first_name as doc_firstname,u.last_name as doc_last_name,CONCAT(u1.first_name," ", u1.last_name) as patient_name,u1.first_name as pat_first_name,u1.last_name as pat_last_name,bd.*,b.signature_id,s.img,s.rowno,DATE_FORMAT(bd.created_at, "%d-%m-%Y") as billing_date');
    $builder->join('billing b', 'bd.billing_id=b.id', 'left');
    $builder->join('signature s', 'b.signature_id=s.id', 'left');
    $builder->join('users u', 'u.id = b.doctor_id', 'left');
    $builder->join('users u1', 'u1.id = b.patient_id', 'left');
    $builder->where('b.id', $billing_id);
    return $builder->get()->getResultArray();
  }
  /**
   * Get Billing 
   * 
   *
   * @param mixed $billing_id
   * @return mixed
   */
  public function getBilling($billing_id)
  {
    $builder = $this->db->table('billing_item_details bd');
    $builder->select('bd.*,b.signature_id,s.img,s.rowno');
    $builder->join('billing b', 'bd.billing_id=b.id', 'left');
    $builder->join('signature s', 'b.signature_id=s.id', 'left');
    $builder->where('b.id', $billing_id);
    return $builder->get()->getResultArray();
  }



    /**
   * Patient Appointment List
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function patientPreviousAppointments($user_id)
  {
    $current_date = date('Y-m-d');
    $from_date_time = date('Y-m-d H:i:s');
    $builder = $this->db->table('appointments a');
$builder->select("
    a.*,
    u.first_name, u.last_name, u.username, u.profileimage, u.role,
    s.specialization,
    cliu.first_name AS clinic_first_name, cliu.last_name AS clinic_last_name, cliu.username AS clinic_username,
    pres.id AS prescription_id,
    GROUP_CONCAT(DISTINCT CONCAT_WS('|', pres_items.id, pres_items.drug_name, pres_items.qty, pres_items.type, pres_items.days, pres_items.time) SEPARATOR '||') AS prescription_items
");

$builder->join('users u', 'u.id = a.appointment_to', 'left');
$builder->join('users_details ud', 'ud.user_id = u.id', 'left');
$builder->join('specialization s', 'ud.specialization = s.id', 'left');
$builder->join('users cliu', 'cliu.id = a.hospital_id', 'left');
$builder->join('users_details clud', 'clud.user_id = cliu.id', 'left');

// Match prescriptions by same hospital and same appointment date
$builder->join('prescription pres', 'pres.appointment_id = a.id', 'left');
$builder->join('prescription_item_details pres_items', 'pres_items.prescription_id = pres.id', 'left');

// Filter
$builder->where('a.appointment_from', $user_id);
if (is_doctor()) {
    $builder->where('a.appointment_to', session('user_id'));
}
$builder->where('a.from_date_time <', $this->getNowInTimezone());

// ✅ Only group by a.id to satisfy ONLY_FULL_GROUP_BY
$builder->groupBy('a.id,s.specialization,pres.id');
    // $builder->order_by('a.from_date_time','DESC');

    $i = 0;
    foreach ($this->appoinments_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
        }

        if ($item == 'a.created_date' || $item == 'a.appointment_date') {
          $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
        }

        if ($item == 'type') {
          $builder->orLike($item, $_POST['search']['value']);
        } else if ($item == 'u.first_name') {
          $builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
        } else {
          $builder->orLike($item, $_POST['search']['value']);
        }


        if (count($this->appoinments_column_search) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      // $builder->order_by('id', $_POST['order']['0']['dir']);

      $builder->orderBy($this->appoinments_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->appoinments_order)) {
      $order = $this->appoinments_order;
      $builder->orderBy(key($order), $order[key($order)]);
    }

    if ($_POST['length'] != -1)
      $builder->limit($_POST['length'], $_POST['start']);
    $query = $builder->get();
    return $query->getResultArray();
  }

  private function getNowInTimezone() {
    $timezone = 'Africa/Cairo';//session('time_zone');
    $dt = new \DateTime('now', new \DateTimeZone($timezone));
    return $dt->format('Y-m-d H:i:s');
  }

  private function getNowInTimezoneMinusWeek() {
    $timezone = 'Africa/Cairo';//session('time_zone');
    $dt = new \DateTime(date('Y-m-d H:i:s', strtotime('-1 week')), new \DateTimeZone($timezone));
    return $dt->format('Y-m-d H:i:s');
  }

  private function getAllTimeZoneForThisDoctor(){
    $builder = $this->db->table('schedule_timings s');
    $builder->where('s.user_id', session('user_id'));//doctor_id
    $builder->select('s.time_zone');
    $query = $builder->get();
    return $query->getResult();
  }

  /**
   * Prescription Count All.
   * 
   * @param mixed $inputdata
   * @return mixed
   */
  public function getPatientAppointmentFromLastWeek($patient_id)
  {
    $oneWeekAgoUtc = (new \DateTime('-7 days', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');

    $builder = $this->db->table('appointments');
    $builder->groupStart()
        ->where('appointment_from', $patient_id)
        ->where('appointment_to', session('user_id'))
        ->orWhere('hospital_id', session('user_id'))
    ->groupEnd();
    $builder->where('from_date_time >=', $oneWeekAgoUtc);
    $builder->orderBy('from_date_time','DESC');
    $builder->select('id, from_date_time, time_zone');
   
    $results = $builder->get()->getResult();

    $finalAppointments = [];

    foreach ($results as $row) {
        try {
            $userTime = new \DateTime($row->from_date_time);
            $appointmentTime = clone $userTime;

            $now = new \DateTime('now', new \DateTimeZone($row->time_zone));

            $oneWeekAgo = clone $now;
            $oneWeekAgo->modify('-7 days');

            if ($appointmentTime >= $oneWeekAgo) {
                $finalAppointments[] = ['id' => $row->id, 'value' => $row->from_date_time.' - '.$row->time_zone];
            }
        } catch (\Exception $e) {
            // You can log error if needed
            continue;
        }
    }

    return $finalAppointments;
  }

  /**
   * Patient Appointment List
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function patientAppointments($user_id)
  {
    $builder = $this->db->table('appointments a');
    $builder->select('a.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization,cliu.first_name as clinic_first_name,cliu.last_name as clinic_last_name,u.role,cliu.username as clinic_username');
    $builder->join('users u', 'u.id = a.appointment_to', 'left');
    $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
    $builder->join('specialization s', 'ud.specialization = s.id', 'left');
    $builder->where('a.appointment_from', $user_id);
    if (is_doctor()) {
      $builder->where('a.appointment_to', session('user_id'));
    }
    $builder->where('a.from_date_time >=', $this->getNowInTimezone());
    $builder->join('users cliu', 'cliu.id = a.hospital_id', 'left');
    $builder->join('users_details clud', 'clud.user_id = cliu.id', 'left');
    
    $builder->groupBy('a.id,u.first_name,u.last_name,u.username,u.profileimage,s.specialization,cliu.first_name,cliu.last_name,u.role,cliu.username');
    // $builder->order_by('a.from_date_time','DESC');

    $i = 0;
    foreach ($this->appoinments_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
        }

        if ($item == 'a.created_date' || $item == 'a.appointment_date') {
          $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
        }

        if ($item == 'type') {
          $builder->orLike($item, $_POST['search']['value']);
        } else if ($item == 'u.first_name') {
          $builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
        } else {
          $builder->orLike($item, $_POST['search']['value']);
        }


        if (count($this->appoinments_column_search) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      // $builder->order_by('id', $_POST['order']['0']['dir']);

      $builder->orderBy($this->appoinments_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->appoinments_order)) {
      $order = $this->appoinments_order;
      $builder->orderBy(key($order), $order[key($order)]);
    }

    if ($_POST['length'] != -1)
      $builder->limit($_POST['length'], $_POST['start']);
    $query = $builder->get();
    return $query->getResultArray();
  }


  public function appointment($id)
  {
    $builder = $this->db->table('appointments a');
    $builder->select('a.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization,cliu.first_name as clinic_first_name,cliu.last_name as clinic_last_name,u.role,cliu.username as clinic_username');
    $builder->join('users u', 'u.id = a.appointment_to', 'left');
    $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
    $builder->join('specialization s', 'ud.specialization = s.id', 'left');
    $builder->where('a.id', $id);
    
    $builder->join('users cliu', 'cliu.id = a.hospital_id', 'left');
    $builder->join('users_details clud', 'clud.user_id = cliu.id', 'left');
    
    $builder->groupBy('a.id,u.first_name,u.last_name,u.username,u.profileimage,s.specialization,cliu.first_name,cliu.last_name,u.role,cliu.username');
    // $builder->order_by('a.from_date_time','DESC');

    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Count Patient Total Appointment
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function countAllAppointmentsOfPatient($user_id, $status=0)
  {
    $builder = $this->db->table('appointments a');
    $builder->where('a.appointment_from', $user_id);
    if($status == 1){
       $builder->where('a.from_date_time >=', $this->getNowInTimezone());
    }else{
      $builder->where('a.from_date_time <', $this->getNowInTimezone());
    }
    
    return $builder->countAllResults();
  }
  /**
   * Patient Prescription
   * 
   * @param mixed $user_id
   * @param mixed $type
   * @return mixed
   */
  public function patientPrescription($user_id, $type)
  {
    $builder = $this->db->table($this->prescription);
    $builder->select('p.*, u.first_name,u.last_name,u.username,u.profileimage,s.specialization');
    $builder->join($this->users, 'u.id = p.doctor_id', 'left');
    $builder->join($this->users_details, 'ud.user_id = u.id', 'left');
    $builder->join($this->specialization, 'ud.specialization = s.id', 'left');
    $builder->join($this->quotations, 'p.id = q.prescription_id', 'left');
    $builder->where('p.patient_id', $user_id);
    $builder->where('p.status', 0);
    if (is_doctor()) {
      $builder->where('p.doctor_id', session('user_id'));
    }

    $i = 0;

    foreach ($this->prescription_column_search as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {
        // if ($item == 'created_date') {
        //   $_POST['search']['value'] = date('d M Y', $_POST['search']['value']);
        // }

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $builder->like($item, libsodiumEncrypt($_POST['search']['value']));
        } else if ($item == 'p.created_at') {
          $builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
        } else {
          $builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
        }

        if (count($this->prescription_column_search) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $builder->orderBy($this->prescription_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->prescription_order)) {
      $order = $this->prescription_order;
      $builder->orderBy(key($order), $order[key($order)]);
    }


    if ($type == 'result') {
      if ($_POST['length'] != -1)
        $builder->limit($_POST['length'], $_POST['start']);

      $query = $builder->get();
      return $query->getResultArray();
    } else {
      return $builder->countAllResults();
    }
  }
  /**
   * Count All PrescriptionOf Patient
   * 
   * 
   * @return mixed
   */
  public function countAllPrescriptionOfPatient()
  {
    $builder = $this->db->table($this->prescription);
    $builder->where('p.patient_id', $_POST['patient_id']);
    $builder->where('p.status', 1);
    if (is_doctor()) {
      $builder->where('p.doctor_id', session('user_id'));
    }
    return $builder->countAllResults();
  }
  /**
   * Update Appointment Status
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function updateAppointmentLists($user_id)
  {
    $builder = $this->db->table('appointments');
    $builder->where('approved', 0);
    $builder->where('appointment_date <= CURDATE() AND appointment_end_time < CURTIME()');
    $builder->set(['appointment_status' => 1])->update();
  }

  public function updateAppointmentForPatient($id, $date, $start_time_from, $start_time_to, $appointment_token, $appointment_session, $appointment_type)
  {
    $typeUpdate = $appointment_type == 'online' ? ['type' => 'Online'] : [];
    $builder = $this->db->table('appointments');
    $appointment = $builder->where('id', $id)->get()->getRow();
    if (!$appointment) {
        return false;
    }
    // if ($appointment->approved != 0) {
    //     return false;
    // }
    // Proceed to update
    return $builder->where('id', $id)->update(array_merge([
        'appointment_date'     => $date,
        'appointment_time'     => $start_time_from,
        'appointment_end_time' => $start_time_to,
        'appoinment_token'     => $appointment_token,
        'appoinment_session'   => $appointment_session,
        'from_date_time'       => $date . ' ' . $start_time_from,
        'to_date_time'         => $date . ' ' . $start_time_to,
    ], $typeUpdate));
  }
  /**
   * Get Medical Records Details
   * 
   * @param mixed $mrid
   * @return mixed
   */
  public function getMedicalRecordsDetails($mrid)
  {
    $builder = $this->db->table('medical_records');
    $builder->select('file_name');
    $builder->where('id', $mrid);
    return $builder->get()->getResultArray();
  }
  /**
   * Search Pharmacy
   * 
   * @param mixed $page
   * @param mixed $limit
   * @param mixed $type
   * @return mixed
   */
  public function searchPharmacy($page, $limit, $type)
  {
    $builder = $this->db->table('users p');
    $builder->select('p.id as pharmacy_id, p.first_name,p.last_name,p.pharmacy_name,p.profileimage, p.mobileno');
    $builder->select('pd.address1,pd.address2,c.country, c.phonecode,s.statename, ci.city, pd.postal_code');
    $builder->select('ps.home_delivery, ps.24hrsopen, ps.pharamcy_opens_at');
    $builder->join('users_details pd', 'p.id = pd.user_id', 'left');
    $builder->join('pharmacy_specifications ps', 'p.id = ps.pharmacy_id', 'left');
    $builder->join('state s', 's.id = pd.state', 'left');
    $builder->join('city ci', 'ci.id = pd.city', 'left');
    $builder->join('country c', 'c.countryid = pd.country', 'left');
    $builder->where('p.role', 5);
    $builder->where('p.status', 1);
    $builder->where('p.is_verified', 1);
    $builder->where('p.is_updated', 1);

    if (!empty($_POST['city'])) {
      $builder->where('pd.city', $_POST['city']);
    }
    if (!empty($_POST['state'])) {
      $builder->where('pd.state', $_POST['state']);
    }
    if (!empty($_POST['country'])) {
      $builder->where('pd.country', $_POST['country']);
    }
    // services filter
    if (!empty($_POST['hrsopen'])) {
      $builder->where('ps.24hrsopen', $_POST['hrsopen']);
    }
    if (!empty($_POST['home_delivery'])) {
      $builder->where('ps.home_delivery', $_POST['home_delivery']);
    }
    // services filter end
    if ($_POST['order_by'] == 'Latest') {
      $builder->orderBy('p.id', 'DESC');
    }

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
   * Get Selected Pharmacy Details
   * 
   * @param mixed $pharmacy_id
   * @return mixed
   */
  public function getSelectedPharmacyDetails($pharmacy_id = NULL)
  {
    $builder = $this->db->table('users p');
    $builder->select('p.id as pharmacy_id, p.first_name,p.last_name,p.pharmacy_name,p.profileimage, p.mobileno');
    $builder->select('pd.address1,pd.address2,c.country, c.phonecode,s.statename, ci.city, pd.postal_code');
    $builder->select('ps.home_delivery, ps.24hrsopen,ps.24hrsopen as hrsopen, ps.pharamcy_opens_at');
    $builder->join('users_details pd', 'p.id = pd.user_id', 'left');
    $builder->join('pharmacy_specifications ps', 'p.id = ps.pharmacy_id', 'left');
    $builder->join('state s', 's.id = pd.state', 'left');
    $builder->join('city ci', 'ci.id = pd.city', 'left');
    $builder->join('country c', 'c.countryid = pd.country', 'left');
    $builder->where('p.id', $pharmacy_id);
    $builder->where('p.role', 5);
    $builder->where('p.status', 1);
    return $builder->get()->getRowArray();
  }
  /**
   * Get Products By Pharmacy Filter
   * 
   * @param mixed $pharmacy_id
   * @param mixed $page
   * @param mixed $limit
   * @param mixed $type
   * @return mixed
   */
  public function get_products_by_pharmacy_filter($pharmacy_id, $page, $limit, $type)
  {
    $builder = $this->db->table('products p');
    $builder->select('p.*,u.unit_name,c.category_name as category_name,ph.currency_code as pharmacy_currency');
    $builder->select('us.first_name,us.last_name,us.pharmacy_name');
    $builder->join('users us', 'us.id = p.user_id');
    $builder->join('unit u', 'u.id = p.unit', 'left');
    $builder->join('product_categories c', 'c.id = p.category', 'left');
    $builder->join('product_subcategories s', 's.id = p.subcategory', 'left');
    $builder->join('users_details ph', 'ph.user_id = p.user_id');
    $builder->where('p.status', '1');
    $builder->where('us.is_updated', '1');
    $builder->where('us.is_verified', '1');
    if (!empty($pharmacy_id)) {
      $builder->where('p.user_id', $pharmacy_id);
    }
    $builder->groupBy('p.id');
    $builder->orderBy('p.id', 'DESC');

    if (!empty($_POST['category'])) {
      $builder->where('c.id', $_POST['category']);
    }

    if (!empty($_POST['subcategory'])) {
      $builder->whereIn('s.id', $_POST['subcategory']);
    }

    if (!empty($_POST['keywords'])) {
      $builder->groupStart();
      $builder->orLike('p.name', $_POST['keywords']);
      $builder->groupEnd();
    }
    if ($type == 1) {
      return $builder->countAllResults();
    } else {
      $page = isset($page) && is_numeric($page) ? intval($page) : 0;

      if ($page >= 1) {
        $page = $page - 1;
      }

      $page = $page * $limit;

      $builder->limit($limit, $page);
      return $builder->get()->getResultArray();
    }
  }

  /**
   * Set userdata
   *
   * Legacy CI_Session compatibility method
   *
   * @param	mixed	$data	Session data key or an associative array
   * @param	mixed	$value	Value to store
   * @return	void
   */
  public function set_userdata($data, $value = NULL)
  {
    if (is_array($data)) {
      foreach ($data as $key => &$value) {
        $_SESSION[$key] = $value;
      }

      return;
    }

    $_SESSION[$data] = $value;
  }
  /**
   * Get Products By Search
   * 
   * @param mixed $pharmacy_id
   * @param mixed $page
   * @param mixed $limit
   * @param mixed $type
   * @return mixed
   */
  public function get_products_by_search($pharmacy_id, $page, $limit, $type = 2)
  {
    $builder = $this->db->table('products p');
    $builder->select('p.name');
    $builder->join('unit u', 'u.id = p.unit', 'left');
    $builder->join('product_categories c', 'c.id = p.category', 'left');
    $builder->join('product_subcategories s', 's.id = p.subcategory', 'left');
    $builder->where('p.status', '1');
    $builder->groupBy('p.id');
    $builder->orderBy('p.id', 'DESC');
    if (!empty($pharmacy_id)) {
      $builder->where('p.user_id', $pharmacy_id);
    }
    if (!empty($_POST['category'])) {
      $builder->where('c.id', libsodiumEncrypt($_POST['category']));
    }
    if (!empty($_POST['subcategory'])) {
      $builder->whereIn('s.id', libsodiumEncrypt($_POST['subcategory']));
    }
    if (!empty($_POST['keywords'])) {
      $builder->groupStart();
      $builder->Like('p.name', libsodiumEncrypt($_POST['keywords']));
      $builder->groupEnd();
    }
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
   * Get Products Details
   * 
   * @param mixed $product_id
   * @return mixed
   */
  public function get_product_details($product_id)
  {
    $builder = $this->db->table('products p');
    $builder->select("p.*,ph.currency_code as pharmacy_currency,ph.user_id as pharmacy_id");
    $builder->join('users_details ph', 'ph.user_id = p.user_id');
    $builder->where("p.status = 1 AND md5(p.id)='" . $product_id . "'");
    $result = $builder->get()->getRowArray();
    return $result;
  }
  /**
   * Get Products 
   * 
   * @param mixed $slug
   * @return mixed
   */
  public function getproduct($slug)
  {
    $builder = $this->db->table('products p');
    $builder->select('p.*,u.unit_name,ud.currency_code');
    $builder->join('unit u', 'u.id = p.unit', 'left');
    $builder->join('users_details ud', 'ud.user_id = p.user_id', 'left');
    $builder->where('p.slug', $slug);
    return $builder->get()->getRowArray();
  }
  /**
   * Get Particular Categories
   * 
   * @param mixed $slug
   * @return mixed
   */
  public function get_particular_categories($slug)
  {
    $builder = $this->db->table('product_subcategories ps');
    $builder->select('ps.*');
    $builder->join('product_categories as pc', 'pc.id = ps.id', 'left');
    $builder->groupStart();
    $builder->like('ps.slug', $slug);
    $builder->groupEnd();
    return $builder->get()->getResultArray();
  }
  /**
   * Get Sub Categories
   * 
   * @param mixed $category_id
   * @return mixed
   */
  public function get_sub_categories($category_id)
  {
    $builder = $this->db->table('product_subcategories ps');
    $builder->select('ps.*');
    $builder->where('ps.category', $category_id);
    return $builder->get()->getResultArray();
  }
  /**
   * Get Popular Products
   * 
   * @return mixed
   */
  public function get_popular_products()
  {
    $builder = $this->db->table('products');
    $builder->select('*');
    $builder->where('status', '1');
    $builder->orderBy('id', 'RANDOM');
    $builder->limit('25');
    return $builder->get()->getResultArray();
  }
  /**
   * Get Categories
   * 
   * @return mixed
   */
  public function get_categories()
  {
    $builder = $this->db->table('product_categories');
    $builder->select('*');
    $builder->where('status', '1');
    $builder->orderBy('id', 'RANDOM');
    $builder->limit('25');
    return $builder->get()->getResultArray();
  }
}
