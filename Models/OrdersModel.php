<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdersModel extends Model
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

  protected string $product = 'products p';
  protected string $category = 'product_categories c';
  protected string $subcategory = 'product_subcategories s';
  protected string $unit = 'unit u';
  protected string $users = 'users u';

  protected mixed $column_search = array('od.full_name', 'od.email', 'us.first_name', 'us.last_name', 'o.order_id', 'o.quantity', 'o.payment_type', 'o.subtotal', 'od.created_at', 'us.pharmacy_name');
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

  protected string $quotations = 'patient_request_quotation q';
  protected mixed $quotation_column_search = array('u.first_name', 'u.last_name');
  protected mixed $quotation_order = array('q.id' => 'ASC'); // default order 

  public function __construct()
  {
    parent::__construct();
    $this->db = \Config\Database::connect();
  }
  /**
   * Get Datatables Query
   * 
   * 
   *
   * @return mixed
   */
  private function getDatatablesQuery()
  {
    $builder = $this->db->table('orders as o');
    $builder->select('od.*,o.product_name,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty,o.payment_type,o.status,o.order_id,o.subtotal,o.order_status,o.user_notify,o.pharmacy_notify,o.id as id,ud.currency_code as product_currency, "CAST(o.subtotal AS INT)" as orderby_subtotoal, o.currency_code');
    $builder->join('order_user_details as od', 'od.order_user_details_id = o.user_order_id', 'left');
    $builder->join('users as us', 'us.id = o.pharmacy_id', 'left');
    $builder->join('users_details as ud', 'ud.user_id = o.pharmacy_id', 'left');
    if (session('role') == '5') {
      $builder->where('o.pharmacy_id', session('user_id'));
    } else {
      $builder->where('od.user_id', session('user_id'));
    }
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
        } else if ($item == 'od.full_name' || $item == 'us.full_name' || $item === 'us.pharmacy_name') {
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
  public function getDatatables()
  {
    $builder = $this->getDatatablesQuery();
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
  public function countFiltered()
  {
    $builder = $this->getDatatablesQuery();
    $query = $builder->get();
    return $query->getNumRows();
  }
  /**
   * Today Order
   * 
   * 
   *
   * @return mixed
   */
  public function todayOrder()
  {
    $builder = $this->getDatatablesQuery();
    $currentDate = date('Y-m-d');
    $query = $builder->where('DATE(ordered_at)', $currentDate)->get();
    return $query->getNumRows();
  }
  /**
   * Count All
   * 
   * 
   *
   * @return mixed
   */
  public function countAll()
  {
    $builder = $this->db->table($this->product);
    $builder->where("p.user_id", session('user_id'));
    $builder->where("(p.status = '1' OR p.status = '2')");
    return $builder->countAllResults();
  }
  /**
   * Get Products Datatables
   * 
   * 
   * @param mixed $orderId
   * @return mixed
   */
  public function getProductsDatatables($orderId)
  {
    $builder = $this->db->table('orders as o');
    $builder->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,o.quantity as qty,o.payment_type,o.status,o.product_name,o.order_id,o.subtotal  subtotal ,o.order_status,o.user_notify,o.pharmacy_notify,o.id as id,ud.currency_code as product_currency,o.currency_code as order_currency, date_format(od.created_at,"%d %b %Y") created_at_formatted');
    $builder->join('order_user_details as od', 'od.order_user_details_id = o.user_order_id', 'left');
    $builder->join('users as us', 'us.id = o.pharmacy_id', 'left');
    $builder->join('users_details as ud', 'ud.user_id = o.pharmacy_id', 'left');
    $builder->where('o.order_id', base64_decode($orderId));
    if (session('role') == '5') {
      $builder->where('o.pharmacy_id', session('user_id'));
    } else {
      $builder->where('o.user_id', session('user_id'));
    }
    $query = $builder->get();
    return $query->getResultArray();
  }
  /**
   * Count All
   * 
   * 
   * @param mixed $orderId
   * @return mixed
   */
  public function getPharmacyProductsDatatables($orderId)
  {
    $builder = $this->db->table('orders as o');
    $builder->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,o.quantity as qty,o.payment_type,o.status,o.product_name,o.order_id,o.subtotal  subtotal ,o.order_status,o.user_notify,o.pharmacy_notify,o.id as id,ud.currency_code as product_currency,dc.country as doctorcountryname,ds.statename as doctorstatename,dci.city as doctorcityname,pc.country as patientcountryname,ps.statename as patientstatename,pci.city as patientcityname,ud.address1 as doctoraddress1,ud.address2 as doctoraddress2,o.pharmacy_id,o.currency_code as order_currency,ud.postal_code as patient_postal_code,us.mobileno as mob_no, payments.transaction_charge_percentage');
    // $builder->from('orders as o');
    $builder->join('order_user_details as od', 'od.order_user_details_id = o.user_order_id', 'left');
    $builder->join('users as us', 'us.id = o.pharmacy_id', 'left');
    $builder->join('users_details as ud', 'ud.user_id = o.pharmacy_id', 'left');
    $builder->join('country dc', 'od.country = dc.countryid', 'left');
    $builder->join('state ds', 'od.state = ds.id', 'left');
    $builder->join('city dci', 'od.city = dci.id', 'left');
    $builder->join('country pc', 'ud.country = pc.countryid', 'left');
    $builder->join('state ps', 'ud.state = ps.id', 'left');
    $builder->join('city pci', 'ud.city = pci.id', 'left');
    $builder->join('payments', 'payments.id = o.payment_id', 'left');
    $builder->where('o.order_id', base64_decode($orderId));
    if (session('role') == '5') {
      $builder->where('o.pharmacy_id', session('user_id'));
    } else {
      $builder->where('o.user_id', session('user_id'));
    }
    $query = $builder->get();
    return $query->getResultArray();
  }
  public function getProductOrderDetails($id)
  {
    $builder = $this->db->table('orders as o');
    $builder->select('CONCAT(us.first_name," ", us.last_name) as patient_name,us.email as patient_email,ph.first_name as pharmacy_first_name,ph.last_name as pharmacy_last_name,o.product_name,o.order_id,o.order_status');
    $builder->join('users as ph', 'ph.id = o.pharmacy_id', 'left');
    $builder->join('users as us', 'us.id = o.user_id', 'left');
    $builder->where('o.id', $id);
    $query = $builder->get();
    return $query->getRowArray();
  }
}
