<?php

namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'countries';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $allowedFields    = [];

    protected string $country = 'country c';
    protected string $state = 'state s';
    protected string $city = 'city cc';

    protected mixed $columnSearch = array('s.statename'); //set column field database for datatable searchable 
    protected mixed $order = array('s.statename' => 'ASC'); // default order
    protected mixed $columnOrder = array("", "s.statename");

    protected mixed $column_searchcity = array('cc.city'); //set column field database for datatable searchable 
    protected mixed $ordercity = array('cc.city' => 'ASC'); // default order
    protected mixed $city_column_order = array('', 'cc.city', '');

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Get State Datatables
     * 
     * 
     * @param mixed $country_id
     * @param mixed $input
     * @return mixed
     */
    public function getStateDatatables($country_id, $input)
    {
        $builder = $this->db->table($this->state);
        $builder->select('s.*');
        if ($country_id > 0) {
            $builder->where('s.countryid', $country_id);
        }




        $i = 0;

        foreach ($this->columnSearch as $item) // loop column 
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

                if (count($this->columnSearch) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($input['order'])) // here order processing
        {
            // $this->db->order_by('id', $input['order']['0']['dir']);

            $builder->orderBy($this->columnOrder[$input['order']['0']['column']], $input['order']['0']['dir']);
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
     * State Count Filtered
     * 
     * 
     * @param mixed $country_id
     * @param mixed $input
     * @return mixed
     */
    public function stateCountFiltered($country_id, $input)
    {
        $builder = $this->db->table($this->state);
        $builder->select('s.*');
        if ($country_id > 0) {
            $builder->where('s.countryid', $country_id);
        }



        $i = 0;

        foreach ($this->columnSearch as $item) // loop column 
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

                if (count($this->columnSearch) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($input['order'])) // here order processing
        {
            // $this->db->order_by('id', $input['order']['0']['dir']);

            $builder->orderBy($this->columnOrder[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $query = $builder->get();
        return count($query->getResultArray());
    }
    /**
     * State Count All
     * 
     * 
     * @param mixed $country_id
     * @return mixed
     */
    public function stateCountAll($country_id)
    {
        $builder = $this->db->table($this->state);
        if ($country_id > 0) {
            $builder->where('s.countryid', $country_id);
        }
        return $builder->countAllResults();
    }
    /**
     * Get City Datatables
     * 
     * @param mixed $input
     * @param mixed $country_id
     * @return mixed
     */
    public function getCityDatatables($country_id, $input)
    {
        $builder = $this->db->table($this->city);
        $builder->select('cc.*');
        if ($country_id > 0) {
            $builder->where('cc.stateid', $country_id);
        }
        $i = 0;

        foreach ($this->column_searchcity as $item) // loop column 
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

                if (count($this->column_searchcity) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }
        if (isset($input['order'])) // here order processing
        {
            $builder->orderBy($this->city_column_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->ordercity)) {
            $order = $this->ordercity;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($input['length'] != -1)
            $builder->limit($input['length'], $input['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * City Count Filtered
     * 
     * @param mixed $input
     * @param mixed $country_id
     * @return mixed
     */
    public function cityCountFiltered($country_id, $input)
    {
        $builder = $this->db->table($this->city);
        $builder->select('cc.*');
        if ($country_id > 0) {
            $builder->where('cc.stateid', $country_id);
        }
        $i = 0;

        foreach ($this->column_searchcity as $item) // loop column 
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

                if (count($this->column_searchcity) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }
        if (isset($input['order'])) // here order processing
        {
            $builder->orderBy($this->city_column_order[$input['order']['0']['column']], $input['order']['0']['dir']);
        } else if (isset($this->ordercity)) {
            $order = $this->ordercity;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        $query = $builder->get();
        return count($query->getResultArray());
    }
    /**
     * City Count All
     * 
     * @param mixed $country_id
     * @return mixed
     */
    public function cityCountAll($country_id)
    {
        $builder = $this->db->table($this->city);
        if ($country_id > 0) {
            $builder->where('cc.stateid', $country_id);
        }
        return $builder->countAllResults();
    }
}
