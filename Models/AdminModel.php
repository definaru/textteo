<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'admins';
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

    /**
     * is Valid Login
     * 
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function isValidLogin($email, $password)
    {
        $password = md5($password);
        $builder = $this->db->table('administrators');
        $builder->select('id');
        $builder->where('email', $email);
        $result = $builder->get()->getRowArray();
        if (!empty($result)) {
            $builder->select('id');
            $builder->where('email', $email);
            $builder->where('password', $password);
            $result_set = $builder->get()->getRowArray();
            if (!empty($result_set)) {
                $result = $result_set;
                // If result value is assumed as zero, Username & Password is correct 
            } else {
                $result = 2;
                // If result value is assumed as 2, password is incorrect
            }
        } else {
            $result = 1;
            // If result value is assumed as 1, username is incorrect
        }

        return $result;
    }
    /**
     * Get City Of Country
     * 
     * @param mixed $countryid
     * @return mixed
     */
    function getCityOfCountry($countryid)
    {
        $builder = $this->db->table('city c');
        $builder->select('c.city');
        $builder->join('state s', 's.id = c.stateid', 'inner');
        $builder->where('s.countryid', $countryid);
        $builder->orderBy('c.city', 'ASC');
        $query = $builder->get();
        return $query->getResultArray();
    }
}
