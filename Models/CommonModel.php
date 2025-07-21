<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class CommonModel extends Model
{
    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * function to insert to db
     * $tableName - name of the table
     * $data - input data to be an single associative array
     * $caseCheck - true for converting to lower case, false to normal insert
     * 
     * @param string  $tableName
     * @param mixed  $data
     * @param mixed $caseCheck 
     * @return mixed 
     */
    public function insertData($tableName, $data, $caseCheck = false)
    {
        if ($this->db->table($tableName)->insert($data)) {
            $id = $this->db->insertID();
            return array('id' => $id);
        }
        return false;
    }
    /**
     * Update Data
     * 
     * 
     * @param string $tableName
     * @param mixed $whereData
     * @param mixed $data
     * @return mixed
     */
    public function updateData($tableName, $whereData, $data)
    {
        // $this->db->table($tableName)->where($whereData)->set($data)->update();
        if ($data && $this->db->table($tableName)->where($whereData)->set($data)->update()) {
            return true;
        }
        return false;
    }
    /**
     * Delete Data
     * 
     * 
     * @param string $tableName
     * @param mixed $whereData
     * @return mixed
     */
    public function deleteData($tableName, $whereData)
    {
        if ($whereData && $this->db->table($tableName)->where($whereData)->delete()) {
            return true;
        }
        return false;
    }

    /**
     * function to check given value exist or not
     */
    // public function checkTblDataExist($tblNme,$whereData,$colNme)
    // {
    //     $builder = $this->db->table($tblNme);
    //     $builder->select($colNme);
    //     $builder->where($whereData);
    //     $query =  $builder->get()->getRowArray();
    //     return $query;
    // }
    /**
     * Check TblData Exist
     * 
     * 
     * @param string $tblNme
     * @param mixed $whereData
     * @param mixed $colNme
     * @param mixed $whereNotIn
     * @return mixed
     */
    public function checkTblDataExist($tblNme, $whereData, $colNme, $whereNotIn = '')
    {
        // print_r($whereNotIn);die;
        $builder = $this->db->table($tblNme);
        $builder->select($colNme);
        $builder->where($whereData);
        if ($whereNotIn && !empty($whereNotIn)) {
            $builder->where($whereNotIn['column_name'], $whereNotIn['value']);
        }
        $query =  $builder->get()->getRowArray();
        return $query;
    }

    /**
     * Get row count from table
     * 
     * 
     * @param string $tblNme
     * @param mixed $whereData
     * @return mixed
     */
    public function countTblResult($tblNme, $whereData)
    {
        $builder = $this->db->table($tblNme);
        $builder->where($whereData);
        $query =  $builder->countAllResults();
        return $query;
    }

    /**
     * Get Tbl RowOf Data
     * 
     * 
     * @param string $tblNme
     * @param mixed $whereData
     * @param mixed $colNme
     * @return mixed
     */
    public function getTblRowOfData($tblNme, $whereData, $colNme)
    {
        $builder = $this->db->table($tblNme);
        $builder->select($colNme);
        $builder->where($whereData);
        $query =  $builder->get()->getRowArray();
        return $query;
    }
    /**
     * Get TblResultOf Data
     * 
     * 
     * @param string $tblNme
     * @param mixed $whereData
     * @param mixed $colNme
     * @return mixed
     */
    public function getTblResultOfData($tblNme, $whereData = [], $colNme = '*')
    {
        $builder = $this->db->table($tblNme);
        $builder->select($colNme);
        if ($whereData)
            $builder->where($whereData);
        $query =  $builder->get()->getResultArray();
        return $query;
    }

    /**
     * Get Table Query
     * 
     *
     * @return mixed
     */
    public function getTblQuery()
    {
        $builder = $this->db->getLastQuery();
        return $builder;
    }

    /**
     * Get Doctor List
     * 
     * @param mixed $start
     * @param mixed $length
     * @param mixed $searchValue
     * @return mixed
     */
    public function getDoctorList($start, $length, $searchValue)
    {
        $builder = $this->db->table("users");
        $builder->select('id as id,first_name as name,first_name,last_name ,email,country_code, mobileno as mobile,profileimage as profile,username as  username,is_verified,is_updated');;
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('username', $searchValue)
                ->orLike('first_name', $searchValue)
                ->orLike('last_name', $searchValue)
                ->orLike('email', $searchValue)
                ->groupEnd();
        }
        $builder->where('status', 1);
        $builder->where('hospital_id', session('user_id'));
        $builder->limit($length, $start);
        $query = $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        return $query;
    }

    /**
     * DataTable Filter
     * 
     * 
     * @param mixed $searchValue
     * @return mixed
     */
    public function getRowCount($searchValue)
    {
        $builder = $this->db->table("users");
        $builder->select('id as id,first_name as name,first_name,last_name ,email,country_code, mobileno as mobile,profileimage as profile,username as  username,is_verified,is_updated');;
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('username', $searchValue)
                ->orLike('first_name', $searchValue)
                ->orLike('last_name', $searchValue)
                ->orLike('email', $searchValue)
                ->groupEnd();
        }
        $builder->where('status', 1);
        $builder->where('hospital_id', session('user_id'));
        return $builder->countAllResults();
    }
    
    //added new on 21st June 2024 by Muddasar
    public function getPetById($pet_id)
    {
        return $this->db->table('pets')->where('id', $pet_id)->get()->getRowArray();
    }
}
