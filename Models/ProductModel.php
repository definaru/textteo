<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $allowedFields    = [];

    protected mixed $column_search = array('name');
    protected mixed $products_column_order = array(
        '',
        'products.name',
        ''
    );
    protected mixed $products_default_column_order = array('products.id' => 'DESC');

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
        $builder->where('status', 1);

        $i = 0;
        foreach ($this->column_search as $item) {
            $searchValue = $_POST['search']['value'];

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
            $orderColumn = $_POST['order'][0]['column'];
            $orderDir = $_POST['order'][0]['dir'];

            $builder->orderBy($this->products_column_order[$orderColumn], $orderDir);
        } elseif (isset($this->products_default_column_order)) {
            $order = $this->products_default_column_order;
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
        $builder->where('status', 1);
        $length = $_POST['length'];
        $start = $_POST['start'];
        if ($length !== -1) {
            $builder->limit($length, $start);
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
        $builder->where('status', 1);
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
        $builder->where('status', 1);
        return $builder->countAllResults();
    }
    /**
     * Datatables Count All
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getByIdWithPharmacy($id)
    {
        $builder = $this->db->table('products as pr')
            ->select('*, pr.id as product_id')
            ->join('users as u', 'u.id = pr.user_id', 'left')
            ->where('pr.id', $id)
            ->get();

        return $builder->getRowArray();
    }
}
