<?php

namespace App\Models;

use CodeIgniter\Model;

class SpecializationModel extends Model
{



  protected $DBGroup          = 'default';
  protected $table            = 'specialization';
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

  var mixed $column_search = array('specialization', 'specialization_img'); //set column field database for datatable searchable 
  var mixed $order = array('id' => 'Asc'); // default order 

  // /**
  //  * Get Datatables Query
  //  * 
  //  * @param mixed $inputdata
  //  * @return mixed
  //  */
  // private function getDatatablesQuery($inputdata)
  // {
  //   $builder = $this->db->table($this->table);

  //   $builder->where('status', 1);
  //   $i = 0;

  //   if (isset($inputdata['order'])) // here order processing
  //   {
  //     $builder->orderBy('specialization', $inputdata['order']['0']['dir']);

  //     //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
  //   } else if (isset($this->order)) {
  //     $order = $this->order;
  //     $builder->orderBy(key($order), $order[key($order)]);
  //   }
  // }
  /**
   * Get Datatables
   * 
   * @param mixed $inputdata
   * @return mixed
   */
  public function getDatatables($inputdata)
  {
    $builder = $this->db->table($this->table);
    $i = 0;

    foreach ($this->column_search as $item) // loop column 
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

        if (count($this->column_search) - 1 == $i) //last loop
          $builder->groupEnd(); //close bracket
      }
      $i++;
    }

    if (isset($inputdata['order'])) // here order processing
    {
      $builder->orderBy('id', $inputdata['order']['0']['dir']);
      // $builder->orderBy('specialization', $inputdata['order']['0']['dir']);

      //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } else if (isset($this->order)) {
      $order = $this->order;
      $builder->orderBy(key($order), $order[key($order)]);
    }

    // $this->getDatatablesQuery($inputdata);
    $builder->where('status', 1);
    //$this->db->where('status',1);
    $builder->orderBy('id', 'DESC');
    if ($inputdata['length'] != -1)
      $builder->limit($inputdata['length'], $inputdata['start']);
    $query = $builder->get();
    return $query->getResultArray();
    // echo $this->db->getLastQuery();
    // exit;
  }
  /**
   * Count All
   * 
   * 
   * @return mixed
   */
  public function countAll()
  {
    $builder = $this->db->table($this->table);
    $builder->where('status', 1);
    return $builder->countAllResults();
  }
  /**
   * Update Specialization
   * 
   * @param mixed $where
   * @param mixed $data
   * @return mixed
   */
  public function updateSpecialization($where, $data)
  {
    $builder = $this->db->table($this->table);
    $builder->update($data, $where);
    return ($this->db->affectedRows() != 1) ? false : true;
  }
  /**
   * Check Specialization Exist
   * 
   * @param mixed $id
   * @param mixed $data
   * @return mixed
   */
  public function checkSpecializationExist($data, $id = NULL)
  {
    $builer = $this->db->table($this->table);
    $builer->where('specialization', $data);
    $builer->where('status', 1);
    if (!empty($id))
      $builer->where('id!=', $id);
    $query = $builer->countAllResults();
    return $query;
  }
  /**
   * Insert Specialization 
   * 
   *
   * @param mixed $data
   * @return mixed
   */
  public function insertSpecialization($data)
  {
    $specialization = array('specialization' => $data['specialization'], 'specialization_img' => $data['specialization_img']);
    $builer = $this->db->table($this->table);
    $builer->insert($specialization);
    $result = ($this->db->affectedRows() != 1) ? false : true;
    return $result;
  }
  /**
   * Get Specialization ById
   * 
   *
   * @param mixed $id
   * @return mixed
   */
  public function getSpecializationById($id)
  {
    $builder = $this->db->table($this->table);
    $builder->where('id', $id);
    $query = $builder->get();

    return $query->getRow();
  }
}
