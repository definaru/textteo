<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class SignInModel extends Model
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * is Valid Login.
     * 
     * @param string  $email
     * @param string $password
     * @return mixed
     */
    public function isValidLogin($email, $password)
    {
        $password = md5($password);
        $builder = $this->db->table('users');
        $builder->select('*');
        $builder->where("(email = '" . $email . "' OR mobileno = '" . $email . "')");
        $builder->where('password', $password);
        // echo $builder->getCompiledSelect();
        $result = $builder->get()->getRowArray();
        return $result;
    }
    /**
     * Social Login.
     * 
     * @param string  $email
     * @return mixed
     */
    public function social_login($email)
    {
        $builder = $this->db->table('users');
        $builder->select('*');
        $builder->where("(email = '" . $email . "' OR mobileno = '" . $email . "')");
        $result = $builder->get()->getRowArray();
        return $result;
    }
}
