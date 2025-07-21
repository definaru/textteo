<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountsModel extends Model
{
  protected $db;
  public  $table = 'payments p';
  public string $doctor = 'users d';
  public string $doctor_details = 'users_details dd';
  public string $patient = 'users pi';
  public string $patient_details = 'users_details pd';
  public string $users = 'users u';
  public string $appoinments = 'appointments a';
  public array $column_search = array('p.invoice_no', 'u.first_name', 'u.last_name', 'u.profileimage', 'p.total_amount', 'p.payment_date'); //set column field database for datatable searchable
  public array $column_search1 = array('CONCAT(pi.first_name," ", pi.last_name)', 'p.total_amount', 'date_format(p.payment_date,"%d %b %Y")');
  public array $column_search2 = array('p.invoice_no', 'CONCAT(d.first_name," ", d.last_name)', 'd.profileimage', 'p.total_amount', 'p.payment_date');
  public array $order1 = array('p.request_status' => 'ASC'); // default order
  public array $order = array('p.id' => 'DESC'); // default order
  public array $column_order = [
    '',
    'p.payment_date',
    'd.first_name',
    'p.total_amount',
    'p.request_status',
    '',
  ]; // accounts column search

  public function __construct()
  {
    parent::__construct();
    $this->db = \Config\Database::connect();
  }
  /**
   * function to Get Balance Pharmacy
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function getBalancePharmacy($user_id)
  {
    $builder = $this->db->table('orders o');
    $builder->select('o.id,o.status as status,p.ordered_at as payment_date,Sum(p.subtotal) as price,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,pd.currency_code as currency_code');
    $builder->join($this->patient, 'pi.id = p.user_id', 'left');
    $builder->join($this->patient_details, 'pd.user_id = pi.id', 'left');
    $builder->where('p.pharmacy_id', $user_id);
    $builder->where('p.status', 1);
    $builder->where('transaction_status !=', 'Pay on arrive');

    $result = $builder->get()->getResultArray();
    $balance = 0;
    if (!empty($result)) {
      foreach ($result as $rows) {

        $amount = $rows['price'];
        $commission = !empty(settings("commission")) ? settings("commission") : "0";
        $commission_charge = ($amount * ($commission / 100));
        $balance_temp = $amount - $commission_charge;

        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];

        $org_amount = get_doccure_currency($balance_temp, $rows['currency_code'], $user_currency_code);

        $balance += $org_amount;
      }
    }
    return $balance;
  }
  /**
   * function to Get Patient Balance
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function getPatientBalance($user_id)
  {
    $builder = $this->db->table('payments p');
    $builder->select('*');
    $builder->where('p.user_id', $user_id);
    $builder->where('p.payment_status', 1);
    $builder->where('p.request_status', 7);
    $result = $builder->get()->getResultArray();
    $balance = 0;
    if (!empty($result)) {
      foreach ($result as $rows) {
        $tax_amount = $rows['tax_amount'] + $rows['transcation_charge'];
        $amount = ($rows['total_amount']) - ($tax_amount);
        $commission = !empty(settings("commission")) ? settings("commission") : "0";
        $commission_charge = ($amount * ($commission / 100));
        $balance_temp = $amount;
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];
        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);
        $org_amount = get_doccure_currency($balance_temp, $rows['currency_code'], $user_currency_code);
        $balance += $org_amount;
      }
    }
    return $balance;
  }

  public function getPatientPharmacyBalance($user_id)
  {
    $builder = $this->db->table('pharmacy_payments p');
    $builder->select('*');
    $builder->where('p.user_id', $user_id);
    $builder->where('p.payment_status', 1);
    $builder->where('p.request_status', 7);
    $result = $builder->get()->getResultArray();
    $balance = 0;
    if (!empty($result)) {
      foreach ($result as $rows) {
        $tax_amount = $rows['tax_amount'] + $rows['transcation_charge'];
        $amount = ($rows['total_amount']) - ($tax_amount);
        $commission = !empty(settings("commission")) ? settings("commission") : "0";
        $commission_charge = ($amount * ($commission / 100));
        $balance_temp = $amount;
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];
        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);
        $org_amount = get_doccure_currency($balance_temp, $rows['currency_code'], $user_currency_code);
        $balance += $org_amount;
      }
    }
    return $balance;
  }

  public function getPharmacyBalance($user_id) {
    $builder = $this->db->table('pharmacy_payments p');
    $builder->select('p.*,(select COUNT(id) from appointments where payment_id=p.payment_id) as appoinment_count');
    $builder->where('p.doctor_id', $user_id);
    $builder->where('p.payment_status', 1);
    $builder->where('p.request_status', 2);
    $result = $builder->get()->getResultArray();
    $balance = 0;
    if (!empty($result)) {
      foreach ($result as $rows) {
        $tax_amount = $rows['tax_amount'] + $rows['transcation_charge'];
        $amount = ($rows['total_amount']) - ($tax_amount);
        $commission = !empty(settings("commission")) ? settings("commission") : "0";
        $commission_charge = ($amount * ($commission / 100));
        $balance_temp = $amount - $commission_charge;
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];
        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);
        $org_amount = get_doccure_currency($balance_temp, $rows['currency_code'], $user_currency_code);
        $balance += $org_amount;
      }
    }
    return $balance;
  }
  /**
   * function to Get Balance
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function getBalance($user_id)
  {
    $builder = $this->db->table('payments p');
    $builder->select('p.*,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    $builder->where('p.doctor_id', $user_id);
    $builder->where('p.payment_status', 1);
    $builder->where('p.request_status', 2);
    $result = $builder->get()->getResultArray();
    $balance = 0;
    if (!empty($result)) {
      foreach ($result as $rows) {
        $tax_amount = $rows['tax_amount'] + $rows['transcation_charge'];
        $amount = ($rows['total_amount']) - ($tax_amount);
        $commission = !empty(settings("commission")) ? settings("commission") : "0";
        $commission_charge = ($amount * ($commission / 100));
        $balance_temp = $amount - $commission_charge;
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];
        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);
        $org_amount = get_doccure_currency($balance_temp, $rows['currency_code'], $user_currency_code);
        $balance += $org_amount;
      }
    }
    return $balance;
  }
  /**
   * function to Get Requested
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function getRequested($user_id)
  {
    $builder = $this->db->table('payment_request');
    $builder->select('*');
    $builder->where('user_id', $user_id);
    $builder->where('status', 1);
    $result = $builder->get()->getResultArray();
    $reuested = 0;
    if (!empty($result)) {
      foreach ($result as $rows) {
        $amount = $rows['request_amount'];
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];
        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);
        $org_amount = get_doccure_currency($amount, $rows['currency_code'], $user_currency_code);
        $reuested += $org_amount;
      }
    }
    return $reuested;
  }
  /**
   * function to Get Earned
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function getEarned($user_id)
  {
    $builder = $this->db->table('payment_request');
    $builder->select('*');
    $builder->where('user_id', $user_id);
    $builder->where('status', 2);
    $result = $builder->get()->getResultArray();
    $reuested = 0;
    if (!empty($result)) {
      foreach ($result as $rows) {
        $amount = $rows['request_amount'];
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];
        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);
        $org_amount = get_doccure_currency($amount, $rows['currency_code'], $user_currency_code);
        $reuested += $org_amount;
      }
    }
    return $reuested;
  }
  /**
   * function to Get Account Details
   * 
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function getAccountDetails($user_id)
  {
    $builder = $this->db->table('account_details ad');
    $builder->where('ad.user_id', $user_id);
    $query = $builder->get()->getRow();
    return $query;
  }
  /**
   * function to Get Balance Lab
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function getBalanceLab($user_id)
  {
    $builder = $this->db->table('lab_payments p');
    $builder->select('p.*,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    $builder->join($this->patient, 'pi.id = p.patient_id', 'left');
    $builder->join($this->patient_details, 'pd.user_id = pi.id', 'left');
    $builder->where('p.lab_id', $user_id);
    $builder->where('p.status', 1);
    $builder->where('payment_type !=', 'Pay on arrive');
    $result = $builder->get()->getResultArray();
    $balance = 0;
    if (!empty($result)) {
      foreach ($result as $rows) {
        $tax_amount = $rows['tax_amount'] + $rows['transcation_charge'];
        $amount = ($rows['total_amount']) - ($tax_amount);
        $commission = !empty(settings("commission")) ? settings("commission") : "0";
        $commission_charge = ($amount * ($commission / 100));
        $balance_temp = $amount - $commission_charge;
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];
        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
        $rate_symbol = currency_code_sign($currency_option);
        $org_amount = get_doccure_currency($balance_temp, $rows['currency_code'], $user_currency_code);
        $balance += (float)$org_amount;
      }
    }
    return $balance;
  }
  /**
   * _get_datatables_query
   * 
   * @param mixed $user_id
   * @return mixed
   */
  private function _get_datatables_query($user_id)
  {
    if(session('role') == 5) {
      $builder = $this->db->table('pharmacy_payments p');
    } else {
      $builder = $this->db->table($this->table);
    }
    $builder->distinct();
    $builder->select('p.*,pi.first_name as patient_firstname,pi.last_name as patient_lastname,pi.profileimage as patient_profileimage,pi.id as patient_ids,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count,pi.role');
    // $builder->from($this->table);
    $builder->join($this->patient, 'pi.id = p.user_id', 'left');
    $builder->join($this->patient_details, 'pd.user_id = pi.id', 'left');
    //Newly added left join by nandakumar 
    // $builder->join($this->appoinments,'a.appointment_to=p.doctor_id and a.appointment_from=p.user_id','left');
    // $builder->where('a.appointment_status',1);	 
    //End 
    $builder->where('p.doctor_id', $user_id);
    $builder->where('p.payment_status', 1);

    $i = 0;

    foreach ($this->column_search1 as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $builder->Like($item, $_POST['search']['value']);
        } else {
          $builder->orLike($item, $_POST['search']['value']);
        }

        if (count($this->column_search1) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $builder->orderBy('id', $_POST['order']['0']['dir']);

      //$builder->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
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
  public function get_datatables($user_id)
  {
    $builder = $this->_get_datatables_query($user_id);
    if ($_POST['length'] != -1)
      $builder->limit($_POST['length'], $_POST['start']);
    $query = $builder->get();
    // echo $this->db->getLastQuery();
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
   
    $builder = $this->_get_datatables_query($user_id);
    $query = $builder->countAllResults();
    return $query;
  }
  /**
   * Count All
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function countAll($user_id)
  {
    if(session('role') == 5) {
      $builder = $this->db->table('pharmacy_payments p');
    } else {
      $builder = $this->db->table($this->table);
    }
    $builder->where('p.payment_status', 1);
    $builder->where('p.doctor_id', $user_id);
    return $builder->countAllResults();
  }
  /**
   * _get_patient_accounts_datatables_query
   * 
   * @param mixed $user_id
   * @param mixed $type
   * @return mixed
   */
  private function _get_patient_accounts_datatables_query($user_id, $type)
  {

    if($type == 'pharmacy') {
      $builder = $this->db->table('pharmacy_payments p');
    } else {
      $builder = $this->db->table($this->table);
    }
    $builder->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.first_name as doctor_firstname,d.last_name as doctor_lastname,d.profileimage as doctor_profileimage,d.id as doctor_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count,d.role');
    $builder->join($this->doctor, 'd.id = p.doctor_id', 'left');
    $builder->join($this->doctor_details, 'dd.user_id = d.id', 'left');
    if ($type == 'pharmacy') {
      $builder->where('d.role', 5);
    } else if ($type == 'lab') {
      $builder->where('d.role', 4);
    } else if ($type == 'doctor') {
      $builder->groupStart();
      $builder->where('d.role', 1);
      $builder->orWhere('d.role', 6);
      $builder->groupEnd();
    }
    $builder->where('p.user_id', $user_id);
    $builder->where('p.payment_status', 1);

    $i = 0;

    foreach ($this->column_search2 as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $builder->like($item, $_POST['search']['value']);
        } else {
          $builder->orLike($item, $_POST['search']['value']);
        }

        if (count($this->column_search2) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      // $builder->order_by('id', $_POST['order']['0']['dir']);

      $builder->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $builder->orderBy(key($order), $order[key($order)]);
    }
    return $builder;
  }
  /**
   * Get Patient Accounts Datatables
   * 
   * @param mixed $user_id
   * @param mixed $type
   * @return mixed
   */
  public function getPatientAccountsDatatables($user_id, $type)
  {
    $builder = $this->_get_patient_accounts_datatables_query($user_id, $type);
    if ($_POST['length'] != -1)
      $builder->limit($_POST['length'], $_POST['start']);
    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Patient Accounts Filtered
   * 
   * @param mixed $user_id
   * @param mixed $type
   * @return mixed
   */
  public function patientAccountsFiltered($user_id, $type)
  {
    $builder = $this->_get_patient_accounts_datatables_query($user_id, $type);
    $query = $builder->get();
    return $query->getNumRows();
  }
  /**
   * Patient Accounts CountAll
   * 
   * @param mixed $user_id
   * @param mixed $type
   * @return mixed
   */
  public function patientAccountsCountAll($user_id, $type)
  {
    if($type == 'pharmacy') {
      $builder = $this->db->table('pharmacy_payments p');
    } else {
      $builder = $this->db->table($this->table);
    }
    $builder->join($this->doctor, 'd.id = p.doctor_id', 'left');
    if ($type == 'pharmacy') {
      $builder->where('d.role', 5);
    } else if ($type == 'lab') {
      $builder->where('d.role', 4);
    } else if ($type == 'doctor') {
      $builder->groupStart();
      $builder->where('d.role', 1);
      $builder->orWhere('d.role', 6);
      $builder->groupEnd();
    }
    $builder->where('p.payment_status', 1);
    $builder->where('p.user_id', $user_id);

    return $builder->countAllResults();
  }
  /**
   * _get_doctor_request_datatables_query
   * 
   * @param mixed $user_id
   * @return mixed
   */
  private function _get_doctor_request_datatables_query($user_id)
  {

    $builder = $this->db->table($this->table);
    $builder->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    $builder->join($this->doctor, 'd.id = p.doctor_id', 'left');
    $builder->join($this->doctor_details, 'dd.user_id = d.id', 'left');
    $builder->where('p.user_id', $user_id);
    $builder->where('p.payment_status', 1);
    $builder->where('p.request_status', 1);

    $i = 0;

    foreach ($this->column_search2 as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $builder->like($item, $_POST['search']['value']);
        } else {
          $builder->orLike($item, $_POST['search']['value']);
        }

        if (count($this->column_search2) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $builder->orderBy('id', $_POST['order']['0']['dir']);

      //$builder->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $builder->orderBy(key($order), $order[key($order)]);
    }
    return $builder;
  }
  /**
   * Get Doctor Request Datatables
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function getDoctorRequestDatatables($user_id)
  {
    $builder = $this->_get_doctor_request_datatables_query($user_id);
    if ($_POST['length'] != -1)
      $builder->limit($_POST['length'], $_POST['start']);
    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Doctor Request Filtered
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function doctorRequestFiltered($user_id)
  {
    $builder = $this->_get_doctor_request_datatables_query($user_id);
    $query = $builder->get();
    return $query->getNumRows();
  }
  /**
   * Doctor Request CountAll
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function doctorRequestCountAll($user_id)
  {
    $builder = $this->db->table($this->table);
    $builder->where('p.payment_status', 1);
    $builder->where('p.request_status', 1);
    $builder->where('p.user_id', $user_id);
    return $builder->countAllResults();
  }

  /**
   * Refund Request List for doctors
   * 
   * @param mixed $user_id
   * @return mixed
   */
  private function _get_refund_datatables_query($user_id)
  {

    if(session('role') == 5) {
      $builder = $this->db->table('pharmacy_payments p');
    } else {
      $builder = $this->db->table($this->table);
    }
    $builder->select('p.*,pi.first_name as patient_firstname,pi.last_name as patient_lastname,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    $builder->join($this->patient, 'pi.id = p.user_id', 'left');
    $builder->join($this->patient_details, 'pd.user_id = pi.id', 'left');
    $builder->where('p.doctor_id', $user_id);
    $builder->where('p.payment_status', 1);
    $builder->where('p.request_status', 6);

    $i = 0;

    foreach ($this->column_search1 as $item) // loop column 
    {
      if ($_POST['search']['value']) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $builder->like($item, $_POST['search']['value']);
        } else {
          $builder->orLike($item, $_POST['search']['value']);
        }

        if (count($this->column_search1) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($_POST['order'])) // here order processing
    {
      $builder->orderBy('id', $_POST['order']['0']['dir']);

      //$builder->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $builder->orderBy(key($order), $order[key($order)]);
    }
    return $builder;
  }
  /**
   * Get Refund Datatables
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function get_refund_datatables($user_id)
  {
    $builder = $this->_get_refund_datatables_query($user_id);
    if ($_POST['length'] != -1)
      $builder->limit($_POST['length'], $_POST['start']);
    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Refund Count Filtered
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function refund_count_filtered($user_id)
  {
    $builder = $this->_get_refund_datatables_query($user_id);
    $query = $builder->get();
    return $query->getNumRows();
  }
  /**
   * Refund Count All
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function refund_count_all($user_id)
  {
    if(session('role') == 5) {
      $builder = $this->db->table('pharmacy_payments p');
    } else {
      $builder = $this->db->table($this->table);
    }
    $builder->where('p.payment_status', 1);
    $builder->where('p.request_status', 6);
    $builder->where('p.doctor_id', $user_id);
    return $builder->countAllResults();
  }
}
