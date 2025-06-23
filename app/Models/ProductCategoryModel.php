<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
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


    protected $db;
    protected $table = 'product_categories';
    protected mixed $columnSearch = array('category_name'); //set column field database for datatable searchable 
    protected mixed $order = array('id' => 'DESC'); // default order 
    protected mixed $categoriesColumnOrder = ['', 'category_name', ''];

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
        $this->where('status', 1);

        $i = 0;
        foreach ($this->columnSearch as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->groupStart();
                    $this->like($item, libsodiumEncrypt($_POST['search']['value']));
                } else {
                    $this->orLike($item, libsodiumEncrypt($_POST['search']['value']));
                }

                if (count($this->columnSearch) - 1 === $i) {
                    $this->groupEnd();
                }
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $columnOrder = $_POST['order']['0']['column'];
            $columnDir = $_POST['order']['0']['dir'];
            $orderBy = $this->categoriesColumnOrder[$columnOrder];
            $this->orderBy($orderBy, $columnDir);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->orderBy(key($order), $order[key($order)]);
        }
    }
    /**
     * Get Datatables 
     * 
     *
     * @return mixed
     */
    public function getDatatables()
    {
        $this->getDatatablesQuery();
        $this->where('status', 1);

        if ($_POST['length'] != -1) {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $this->limit($length, $start);
        }

        $query = $this->get();
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
        $this->getDatatablesQuery();
        $this->where('status', 1);

        $query = $this->get();
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
        $this->where('status', 1);
        return $this->countAllResults();
    }
}
