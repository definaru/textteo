<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'system_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $allowedFields    = [];

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * Get Setting List
     * 
     *
     * 
     * @return mixed
     */
    public function getSettingList()
    {
        $builder = $this->db->table('system_settings');
        $builder->select('*');
        $builder->orderBy('id');
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * Get Setting List
     * 
     *
     * @param mixed $key
     * @return mixed
     */
    public function getSettingByKey($key)
    {
        $builder = $this->db->table('system_settings');
        $builder->select('*');
        $builder->where('key', $key);
        $result = $builder->get()->getResultArray();
        return $result;
    }
}
