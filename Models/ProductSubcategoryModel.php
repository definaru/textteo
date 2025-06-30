<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductSubcategoryModel extends Model
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

    protected  $table = 'product_subcategories s';
    protected string $category = 'product_categories c';
    protected mixed $column_search = array('c.category_name', 's.subcategory_name'); //set column field database for datatable searchable 
    protected mixed $order = array('id' => 'DESC'); // default order 
    protected mixed $subcategories_column_order = ['', 'c.category_name', 's.subcategory_name', ''];

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * Get Datatables Query
     * 
     * 
     * @return mixed
     */
    private function getDatatablesQuery()
    {
        $builder = $this->db->table($this->table);
        $builder->select('s.*,c.category_name');
        $builder->join($this->category, 'c.id = s.category', 'left');
        $builder->where('s.status', 1);

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($_POST['search']['value']));
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
            // $this->db->order_by('id', $_POST['order']['0']['dir']);

            $builder->orderBy($this->subcategories_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
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
    public function getDatatables()
    {
        $builder = $this->getDatatablesQuery();
        if ($_POST['length'] != -1) {
            $builder->limit($_POST['length'], $_POST['start']);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Datatables Count Filter
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
     * Datatables Count All
     * 
     * 
     * @return mixed
     */
    public function countAll()
    {
        $builder = $this->db->table($this->table);
        $builder->where('s.status', 1);
        return $this->countAllResults();
    }
}
