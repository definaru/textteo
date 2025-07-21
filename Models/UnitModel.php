<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'unit';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $allowedFields    = [];
    protected mixed $column_search = array('unit_name'); //set column field database for datatable searchable 
    protected mixed $order = array('id' => 'DESC'); // default order 
    protected mixed $units_column_order = ['', 'unit.unit_name', ''];

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
    private function GetDatatablesQuery()
    {
        $builder = $this->db->table($this->table);
        $builder->where('status', 1);
        $i = 0;
        $searchValue = $_POST['search']['value'];
        foreach ($this->column_search as $item) {
            if ($searchValue) {
                if ($i === 0) {
                    $builder->groupStart();
                    $builder->like($item, libsodiumEncrypt($searchValue));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($searchValue));
                }

                if (count($this->column_search) - 1 === $i) {
                    $builder->groupEnd();
                }
            }
            $i++;
        }

        if ($_POST['order'] ?? "") {
            $builder->orderBy(
                $this->units_column_order[$_POST['order'][0]['column']],
                $_POST['order'][0]['dir']
            );
        } elseif (isset($this->order)) {
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
        $builder = $this->GetDatatablesQuery();
        $builder->where('status', 1);
        if ($_POST['length'] !== -1) {
            $builder->limit(
                $_POST['length'],
                $_POST['start']
            );
        }
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Count Filtered
     * 
     *
     * @return mixed
     */
    public function countFiltered()
    {
        $builder = $this->GetDatatablesQuery();
        $builder->where('status', 1);
        $query = $builder->get();
        return $query->getNumRows();
    }
    /**
     * Count ALL
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
