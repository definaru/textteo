<?php

namespace App\Models;

use CodeIgniter\Model;

class SubcategoryModel extends Model
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

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    protected $table = 'subcategories s';
    protected string $category = 'categories c';
    protected mixed $column_search = array('category_name', 'subcategory_name'); //set column field database for datatable searchable 
    protected mixed $order = array('id' => 'DESC'); // default order 

    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * Get Datatables
     * 
     * @param mixed $input
     * @return mixed
     */
    public function getDatatables($input)
    {
        $builder = $this->db->table($this->table);
        $builder->select('s.*,c.category_name');
        $builder->join($this->category, 'c.id = s.category', 'left');
        $builder->where('s.status', 1);

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
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

                if (count($this->column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($input['order'])) // here order processing
        {
            $builder->orderBy('id', $input['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Count Filtered
     * 
     * @param mixed $input
     * @return mixed
     */
    public function countFiltered($input)
    {
        $builder = $this->db->table($this->table);
        $builder->select('s.*,c.category_name');
        $builder->join($this->category, 'c.id = s.category', 'left');
        $builder->where('s.status', 1);
        $i = 0;
        foreach ($this->column_search as $item) // loop column 
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
                if (count($this->column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($input['order'])) // here order processing
        {
            $builder->orderBy('id', $input['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $query = $builder->get();
        return count($query->getResultArray());
    }
    /**
     * Count All
     * 
     * 
     * @return mixed
     */
    public function countAll()
    {
        $builder = $this->db->table('subcategories');
        $builder->where('status', 1);
        return $builder->countAllResults();
    }
}
