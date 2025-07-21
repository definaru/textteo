<?php

namespace App\Models;

use CodeIgniter\Model;

class PharmacyModel extends Model
{
    var string $product = 'products p';
    var string $category = 'product_categories c';
    var string $subcategory = 'product_subcategories s';
    var string $unit = 'unit u';

    var string $users = 'users u';

    var mixed $column_search = array('p.name', 'c.category_name', 's.subcategory_name', 'u.unit_name');
    var mixed $column_order = array('', '', 'p.name', 'c.category_name', 's.subcategory_name', 'u.unit_name');
    // var $column_order = array('p.id' => 'DESC'); // default order 


    var string $quotations = 'patient_request_quotation q';
    var mixed $quotation_column_search = array('u.first_name', 'u.last_name');
    var mixed $quotation_order = array('q.id' => 'ASC'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
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
        $builder->whereIn('p.id', $pharmacy_id);
        $builder->where('p.role', 5);
        $builder->where('p.status', 1);
        return $builder->get()->getRowArray();
    }
    /**
     * _get_datatables_query
     * 
     * 
     * @return mixed
     */
    private function _get_datatables_query()
    {

        $builder = $this->db->table($this->product);
        $builder->select('p.*,c.category_name,s.subcategory_name,u.unit_name');
        $builder->join($this->category, 'p.category = c.id', 'left');
        $builder->join($this->subcategory, 'p.subcategory = s.id', 'left');
        $builder->join($this->unit, 'p.unit = u.id', 'left');
        $builder->where("(p.status = '1' OR p.status = '2')");
        $builder->where("p.user_id", session('user_id'));

        $i = 0;
        foreach ($this->column_search as $item) // loop column 
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
        return $query->getResultArray();
    }
    /**
     * Datatables Count Filter
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
        $builder = $this->db->table($this->product);
        $builder->where("p.user_id", session('user_id'));
        $builder->where("(p.status = '1' OR p.status = '2')");
        return $builder->countAllResults();
    }
}
