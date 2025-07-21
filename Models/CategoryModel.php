<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
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

    protected $table = 'categories';
    protected mixed $column_search = array('category_name'); //set column field database for datatable searchable 
    protected mixed $order = array('id' => 'DESC'); // default order 
    protected mixed $categories_column_order = ['', 'category_name', ''];

    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * Get Datatables
     * 
     * 
     * @param mixed $input
     * @return mixed
     */
    public function getDatatables($input)
    {
        $builder = $this->db->table($this->table);
        $builder->where('status', 1);
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

            $builder->orderBy($this->categories_column_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $builder->where('status', 1);
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Count Filtered
     * 
     * 
     * @param mixed $input
     * @return mixed
     */
    public function countFiltered($input)
    {
        $builder = $this->db->table($this->table);
        $builder->where('status', 1);
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

            $builder->orderBy($this->categories_column_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $builder->where('status', 1);
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
        $builder = $this->db->table($this->table);
        $builder->where('status', 1);
        return $builder->countAllResults();
    }
}
