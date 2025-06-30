<?php

namespace App\Models;

use CodeIgniter\Model;

class LabPaymentModel extends Model
{
  protected $DBGroup          = 'default';
  protected $table            = 'lab_payments as lp';
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

  // Validation
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $validationRules      = [];
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $validationMessages   = [];
  protected $skipValidation       = false;
  protected $cleanValidationRules = true;

  // Callbacks
  protected $allowCallbacks = true;
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $beforeInsert   = [];
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $afterInsert    = [];
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $beforeUpdate   = [];
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $afterUpdate    = [];
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $beforeFind     = [];
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $afterFind      = [];
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $beforeDelete   = [];
  /**
   * @var string[] List of allowed fields in the model.
   */
  protected $afterDelete    = [];

  // protected $db;
  protected $builder;
  protected mixed $labappoinments_column_search = array('p.first_name', 'p.last_name', 'lp.lab_test_date', 'lp.total_amount', 'lp.payment_date', 'lp.cancel_status', 'lt.lab_test_name');

  public function __construct()
  {
    parent::__construct();
    $this->db = \Config\Database::connect();
    $this->builder = $this->db->table($this->table);
  }

  /**
   * Common Query For Lab Appointment
   * 
   * @param mixed $user_id
   * @return mixed
   */
  private function getLabAppointmentQuery($user_id)
  {
    $this->builder->select('lp.*, p.first_name as first_name,p.last_name as last_name,p.profileimage,lp.booking_ids as test_ids, GROUP_CONCAT(lt.lab_test_name) as lab_test_names');
    $this->builder->join('users p', 'p.id = lp.lab_id', 'left');
    $this->builder->join('lab_tests lt', 'FIND_IN_SET(lt.id, lp.booking_ids)', 'left');
    // $this->builder->join($this->table,'lt.lab_id = u.id','left');
    $this->builder->where('lp.patient_id', $user_id);

    $i = 0;
    foreach ($this->labappoinments_column_search as $item) {
      if ($_POST['search']['value']) {


        if ($i === 0) {
          $this->builder->groupStart();
          // $this->builder->like($item, $_POST['search']['value']);
        }

        if ($item == 'lp.lab_test_date' || $item == 'lp.payment_date') {
          $this->builder->orLike('date_format(' . $item . ',"%d %M %Y")', $_POST['search']['value']);
        } else if (count($this->labappoinments_column_search) - 1 == $i) {
          // booking status search
          if ($_POST['search']['value']) {
            $this->builder->orGroupStart();
            $this->builder->where('
                          CASE WHEN 
                            "Success" LIKE "%' . $_POST['search']['value'] . '%"
                          THEN 
                            lp.payment_status = 1
                          ELSE 
                            CASE WHEN 
                              "Failed" LIKE "%' . $_POST['search']['value'] . '%"
                            THEN 
                              lp.payment_status = 0
                            ELSE 
                              FALSE
                            END
                          END
                        ', NULL, FALSE);
            $this->builder->groupEnd();
          }

          $this->builder->groupEnd();
        } else {
          if ($item == "lt.lab_test_name" || $item == "p.first_name"  || $item == "p.last_name") {
            // $_POST['search']['value']=libsodiumEncrypt($_POST['search']['value']);
            $this->builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
          } else {
            $this->builder->orLike($item, $_POST['search']['value']);
          }
        }
      }
      $i++;
    }
    $this->builder->orderBy('id', "DESC");
    if (isset($_POST['order'])) {
    } else if (isset($this->labappoinments_order)) {
      $order = $this->labappoinments_order;
      $this->builder->orderBy(key($order), $order[key($order)]);
    }
    // group_by lab payments
    $this->builder->groupBy('lp.id');
  }
  /**
   * Get Lab Appointment Details
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function getLabAppointmentDetails($user_id)
  {
    $this->getLabAppointmentQuery($user_id);
    if ($_POST['length'] != -1)
      $this->builder->limit($_POST['length'], $_POST['start']);
    $query = $this->builder->get();
    // echo $this->db->getLastQuery();
    return $query->getResultArray();
  }
  /**
   * Lab Appointments Count Filtered
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function labAppointmentsCountFiltered($user_id)
  {
    $this->getLabAppointmentQuery($user_id);
    $query = $this->builder->get();
    return $query->getNumRows();
  }
  /**
   * Lab Appointments Count All
   * 
   * @param mixed $user_id
   * @return mixed
   */
  public function labAppointmentsCountAll($user_id)
  {
    $this->builder->where('lp.patient_id', $user_id);
    return $this->builder->countAllResults();
  }
}
