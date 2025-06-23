<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * @property object $db
 */
class ApiModel extends Model
{
    // protected $db;
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
     * @param  string $tableName
     * @param  mixed $data
     * @param  boolean $caseCheck
     * @return mixed
     *
     */
    public function insertData($tableName, $data, $caseCheck = false)
    {
        if ($caseCheck == true) {
            $data = $this->arrayChangeValueCase($data, CASE_LOWER);
        }
        if ($this->db->table($tableName)->insert($data)) {
            $id = $this->db->insertID();
            return array('id' => $id);
        }
        return false;
    }

    /**
     * function to update table
     * $tableName - name of the table
     * $whereData - where data to be an single associative array
     * $updateData - update data to be an single associative array
     * $caseCheck - true for converting to lower case, false to normal insert
     * @param  string $tableName
     * @param  mixed $whereData
     * @param  mixed $updateData
     * @param  boolean $caseCheck
     * @return mixed
     */
    public function updateData($tableName, $whereData, $updateData, $caseCheck = false)
    {
        if ($caseCheck == true) {
            $updateData = $this->arrayChangeValueCase($updateData, CASE_LOWER);
        }

        $this->db->table($tableName)->where($whereData)->set($updateData)->update();
        return true;
    }

    /**
     * function to delete to db
     * $tableName - name of the table
     * $data - input data to be an single associative array
     * $caseCheck - true for converting to lower case, false to normal insert
     * @param  string $tableName
     * @param  mixed $data
     * @param  boolean $caseCheck
     * @return mixed
     */
    public function deleteData($tableName, $data, $caseCheck = false)
    {
        if ($caseCheck == true) {
            $data = $this->arrayChangeValueCase($data, CASE_LOWER);
        }
        $db = db_connect(); // Load the database connection
        $builder = $db->table($tableName); // Use the database builder
        $builder->where($data)->delete(); // Perform the delete operation
        return true;
    }
    /**
     * function to change cases in array values
     * @param  mixed $input
     * @param  mixed $ucase
     * @return mixed
     */
    public function arrayChangeValueCase($input, $ucase)
    {
        $case = $ucase;
        $narray = array();
        if (!is_array($input)) {
            return $narray;
        }
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $narray[$key] = $this->arrayChangeValueCase($value, $case);
                continue;
            }
            $narray[$key] = ($case == CASE_UPPER ? strtoupper($value) : strtolower($value));
        }
        return $narray;
    }

    /**
     * function to check user exist
     * @param  string $username
     * @return mixed
     */
    public function checkUserExist($username, $password)
    {
        $builder = $this->db->table('users');
        $builder->select('id, email, mobileno, first_name,last_name ,username,status, token,  country_code,  role, profileimage, pharmacy_name');
        $builder->where("(email = '" . $username . "' OR mobileno = '" . $username . "')");
        $builder->where('password', $password);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * function to check given value exist or not
     * @param  string $tblNme
     * @param  mixed $whereData
     * @param  mixed $colNme
     * @return mixed
     */
    public function checkTblDataExist($tblNme, $whereData, $colNme)
    {
        $builder = $this->db->table($tblNme);
        $builder->select($colNme);
        $builder->where($whereData);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * function to get single table data
     * $tableName - name of the table
     * $data - input data to be an single associative array
     * $case_check - true for converting to lower case, false to normal insert
     * @param  string $tableName
     * @param  mixed $whereData
     * @param  mixed $whereIn
     * @param  string $primaryId
     * @param  string $orderBy
     * @param  string $type
     * @param  mixed $fieldList
     * @return mixed
     */
    public function getSingleTableData($tableName, $whereData, $whereIn = array(), $primaryId = '', $orderBy = '', $type = '', $fieldList = '')
    {
        $builder = $this->db->table($tableName);
        if (isset($fieldList) && !empty($fieldList)) {
            $builder->select($fieldList);
        } else {
            $builder->select('*');
        }
        $builder->where($whereData);

        if (isset($whereIn) && !empty($whereIn)) {
            if ($whereIn['type'] == 'In') {
                $builder->whereIn($whereIn['column_name'], $whereIn['value']);
            } else {
                $builder->whereNotIn($whereIn['column_name'], $whereIn['value']);
            }
        }

        $builder->orderBy($primaryId, $orderBy);
        if ($type == 'single') {
            $query = $builder->get()->getRowArray();
        } else {

            $query = $builder->get()->getResultArray();
        }
        return $query;
    }

    /**
     * function to get User id by token
     * @param  string $token
     * @return mixed
     */
    public function getUserDetailsByToken($token)
    {
        $builder = $this->db->table('users');
        $where = [
            'token' => $token,
        ];
        $query = $builder->where($where)->where('token !=', '')->get()->getRowArray();
        if (!empty($query)) {
            return $query;
        } else {
            return [];
        }
    }

    /**
     * function to get language keywords
     * @param  mixed $languages
     * @return mixed
     */
    public function languageKeywords($languages)
    {

        $builder = $this->db->table('app_language_management');
        $builder->select('lang_key,lang_value,language,placeholder,validation1,validation2,validation3,type,page_key');
        $builder->where('language', 'en');
        $builder->where('type', 'App');
        $records = $builder->get()->getResultArray();
        $language = array();
        if (!empty($records)) {
            foreach ($records as $record) {
                $builder = $this->db->table('app_language_management');
                $builder->select('lang_key,lang_value,language,placeholder,validation1,validation2,validation3,type,page_key');
                $builder->where('language', $languages);
                $builder->where('type', 'App');
                $builder->where('page_key', $record['page_key']);
                $builder->where('lang_key', $record['lang_key']);
                $engRecords = $builder->get()->getResultArray();
                if (!empty($engRecords['lang_value'])) {
                    $language['language'][$record['page_key']][$record['lang_key']] = $engRecords['lang_value'];
                } else {
                    $language['language'][$record['page_key']][$record['lang_key']] = $record['lang_value'];
                }
            }
        }
        return $language;
    }

    /**
     * function to get patient details
     * @param  mixed $patientId
     * @return mixed
     */
    public function getPatientDetails($patientId)
    {
        $profileimage = 'assets/img/user.png';
        $builder = $this->db->table('users u');
        $builder->select('u.id as userid,u.role,u.country_code,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,
        (CASE WHEN LENGTH(u.first_name) > 0 THEN u.first_name ELSE " " END) as first_name,
        (CASE WHEN LENGTH(u.last_name) > 0 THEN u.last_name ELSE " " END) as last_name,
        (CASE WHEN LENGTH(u.email) > 0 THEN u.email ELSE " " END) as email,
        (CASE WHEN LENGTH(u.mobileno) > 0 THEN u.mobileno ELSE " " END) as mobileno,
        (CASE WHEN LENGTH(ud.address1) > 0 THEN ud.address1 ELSE " " END) as address1,
        (CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2,
        IF(ud.gender IS NULL,"",ud.gender) as gender,
        IF(ud.dob IS NULL,"",ud.dob) as dob,
        IF(ud.blood_group IS NULL,"",ud.blood_group) as blood_group,
        (CASE WHEN LENGTH(ud.postal_code) > 0 THEN ud.postal_code ELSE " " END) as postal_code,
        IF(ud.country IS NULL,"",ud.country) as country,
        IF(ud.state IS NULL,"",ud.state) as state,
        IF(ud.city IS NULL,"",ud.city) as city,
        IF(c.country IS NULL,"",c.country) as countryname,
        IF(s.statename IS NULL,"",s.statename) as statename,
        IF(ci.city IS NULL,"",ci.city) as cityname');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('u.id', $patientId);
        $builder->where('u.role', '2');
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * function to get clinic details
     * @param  mixed $patientId
     * @return mixed
     */
    public function getClinicDetails($patientId)
    {
        $profileimage = 'assets/img/user.png';
        $builder = $this->db->table('users u');
        $builder->select('u.id as userid,u.country_code,GROUP_CONCAT(DISTINCT cl.id) as clinic_image_id,GROUP_CONCAT(DISTINCT cl.clinic_image) as clinic_image,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,
        (CASE WHEN LENGTH(u.first_name) > 0 THEN u.first_name ELSE " " END) as first_name,
        (CASE WHEN LENGTH(u.last_name) > 0 THEN u.last_name ELSE " " END) as last_name,
        (CASE WHEN LENGTH(u.email) > 0 THEN u.email ELSE " " END) as email,
        (CASE WHEN LENGTH(u.username) > 0 THEN u.username ELSE " " END) as username,
        (CASE WHEN LENGTH(u.mobileno) > 0 THEN u.mobileno ELSE " " END) as mobileno,
        (CASE WHEN LENGTH(ud.address1) > 0 THEN ud.address1 ELSE " " END) as address1,
        (CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2,
        (CASE WHEN LENGTH(ud.postal_code) > 0 THEN ud.postal_code ELSE " " END) as postal_code,
        (CASE WHEN LENGTH(ud.clinic_postal) > 0 THEN ud.clinic_postal ELSE " " END) as clinic_postal,
        IF(ud.country IS NULL,"",ud.country) as country,
        IF(ud.state IS NULL,"",ud.state) as state,
        IF(ud.city IS NULL,"",ud.city) as city,
        (CASE WHEN LENGTH(ud.clinic_name) > 0 THEN ud.clinic_name ELSE " " END) as clinic_name,
        (CASE WHEN LENGTH(ud.biography) > 0 THEN ud.biography ELSE " " END) as biography,
        IF(ud.price_type IS NULL,"",ud.price_type) as price_type,
        IF(ud.amount IS NULL,"",ud.amount) as amount,
        IF(c.country IS NULL,"",c.country) as countryname,
        IF(s.statename IS NULL,"",s.statename) as statename,
        IF(ci.city IS NULL,"",ci.city) as cityname');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('clinic_images cl', 'cl.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('u.id', $patientId);
        $builder->where('u.role', '6');
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * function to get doctor details
     * @param  mixed $doctorId
     * @return mixed
     */
    public function doctorProfileDetails($doctorId)
    {

        $profileimage = 'assets/img/user.png';
        $builder = $this->db->table('users u');
        $builder->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.country_code,u.mobileno,GROUP_CONCAT(DISTINCT cl.id) as clinic_image_id,GROUP_CONCAT(DISTINCT cl.clinic_image) as clinic_image,u.subscription_status,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,
        (CASE WHEN LENGTH(u.first_name) > 0 THEN u.first_name ELSE " " END) as first_name,
        (CASE WHEN LENGTH(u.last_name) > 0 THEN u.last_name ELSE " " END) as last_name,
        (CASE WHEN LENGTH(u.email) > 0 THEN u.email ELSE " " END) as email,
        (CASE WHEN LENGTH(u.username) > 0 THEN u.username ELSE " " END) as username,
        (CASE WHEN LENGTH(u.mobileno) > 0 THEN u.mobileno ELSE " " END) as mobileno,
        (CASE WHEN LENGTH(ud.address1) > 0 THEN ud.address1 ELSE " " END) as address1,
        (CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2,
        (CASE WHEN LENGTH(sp.specialization) > 0 THEN sp.specialization ELSE " " END) as specialization,
        (CASE WHEN LENGTH(sp.id) > 0 THEN sp.id ELSE " " END) as specialization_id,
        IF(ud.gender IS NULL,"",ud.gender) as gender,
        IF(ud.register_no IS NULL,"",ud.register_no) as register_no,
        IF(ud.dob IS NULL,"",ud.dob) as dob,
        IF(ud.blood_group IS NULL,"",ud.blood_group) as blood_group,
        (CASE WHEN LENGTH(ud.postal_code) > 0 THEN ud.postal_code ELSE " " END) as postal_code,
        IF(ud.country IS NULL,"",ud.country) as country,
        IF(ud.state IS NULL,"",ud.state) as state,
        IF(ud.city IS NULL,"",ud.city) as city,
        (CASE WHEN LENGTH(ud.clinic_name) > 0 THEN ud.clinic_name ELSE " " END) as clinic_name,
        (CASE WHEN LENGTH(ud.clinic_postal) > 0 THEN ud.clinic_postal ELSE " " END) as clinic_postal,
        (CASE WHEN LENGTH(ud.clinic_address) > 0 THEN ud.clinic_address ELSE " " END) as clinic_address,
        (CASE WHEN LENGTH(ud.clinic_address2) > 0 THEN ud.clinic_address2 ELSE " " END) as clinic_address2,
        (CASE WHEN LENGTH(ud.clinic_city) > 0 THEN ud.clinic_city ELSE " " END) as clinic_city,
        (CASE WHEN LENGTH(ud.clinic_state) > 0 THEN ud.clinic_state ELSE " " END) as clinic_state,
        (CASE WHEN LENGTH(ud.clinic_country) > 0 THEN ud.clinic_country ELSE " " END) as clinic_country,
        (CASE WHEN LENGTH(ud.biography) > 0 THEN ud.biography ELSE " " END) as biography,
        IF(ud.price_type IS NULL,"",ud.price_type) as price_type,
        IF(ud.amount IS NULL,"",ud.amount) as amount,
        IF(ud.services IS NULL,"",ud.services) as services,
        IF(c.country IS NULL,"",c.country) as countryname,
        IF(s.statename IS NULL,"",s.statename) as statename,
        IF(ci.city IS NULL,"",ci.city) as cityname');
        $builder->join('clinic_images cl', 'cl.user_id = u.id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('specialization sp', 'sp.id = ud.specialization', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('u.role', '1');
        $builder->groupBy('cl.user_id');
        $builder->where('u.id', $doctorId);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * function to get pharmacy details
     * @param  mixed $patientId
     * @return mixed
     */
    public function getPharmacyDetails($patientId)
    {
        $profileimage = 'assets/img/user.png';
        $builder = $this->db->table('users u');
        $builder->select('u.id as userid,u.role,u.country_code,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,
        (CASE WHEN LENGTH(u.last_name) > 0 THEN u.last_name ELSE " " END) as last_name,
        (CASE WHEN LENGTH(u.mobileno) > 0 THEN u.mobileno ELSE " " END) as mobileno,
        (CASE WHEN LENGTH(u.email) > 0 THEN u.email ELSE " " END) as email,
        (CASE WHEN LENGTH(ud.address1) > 0 THEN ud.address1 ELSE " " END) as address1,
        (CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2,
        (CASE WHEN LENGTH(u.pharmacy_name) > 0 THEN u.pharmacy_name ELSE " " END) as pharmacy_name,
        (CASE WHEN LENGTH(ud.postal_code) > 0 THEN ud.postal_code ELSE " " END) as postal_code,
        IF(ud.country IS NULL,"",ud.country) as country,
        IF(ud.state IS NULL,"",ud.state) as state,
        IF(ud.city IS NULL,"",ud.city) as city,
        IF(ps.home_delivery IS NULL,"",ps.home_delivery) as home_delivery,
        IF(ps.24hrsopen IS NULL,"",ps.24hrsopen) as 24hrsopen,
        IF(ps.pharamcy_opens_at IS NULL,"",ps.pharamcy_opens_at) as pharamcy_opens_at,IF(c.country IS NULL,"",c.country) as countryname,
        IF(s.statename IS NULL,"",s.statename) as statename,
        IF(ci.city IS NULL,"",ci.city) as cityname');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('pharmacy_specifications ps', 'ps.pharmacy_id = u.id', 'left');
        $builder->where('u.id', $patientId);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * function to get lab details
     * @param  mixed $patientId
     * @return mixed
     */
    public function getLabDetails($patientId)
    {
        $profileimage = 'assets/img/user.png';
        $builder = $this->db->table('users u');
        $builder->select('u.id as userid,u.role,u.country_code,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,
        (CASE WHEN LENGTH(u.last_name) > 0 THEN u.last_name ELSE " " END) as last_name,
        (CASE WHEN LENGTH(u.mobileno) > 0 THEN u.mobileno ELSE " " END) as mobileno,
        (CASE WHEN LENGTH(ud.address1) > 0 THEN ud.address1 ELSE " " END) as address1,
        (CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2,
        (CASE WHEN LENGTH(u.first_name) > 0 THEN u.first_name ELSE " " END) as first_name,
        (CASE WHEN LENGTH(u.email) > 0 THEN u.email ELSE " " END) as email,
        (CASE WHEN LENGTH(ud.postal_code) > 0 THEN ud.postal_code ELSE " " END) as postal_code,
        IF(ud.country IS NULL,"",ud.country) as country,
        IF(ud.state IS NULL,"",ud.state) as state,
        IF(ud.city IS NULL,"",ud.city) as city,
        IF(c.country IS NULL,"",c.country) as countryname,
        IF(s.statename IS NULL,"",s.statename) as statename,
        IF(ci.city IS NULL,"",ci.city) as cityname');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('u.id', $patientId);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * function to patients lists
     * @param  mixed $pages
     * @param  mixed $limits
     * @param  mixed $type
     * @param  int $userId
     * @return mixed
     */
    public function patientsLists($pages, $limits, $userId, $type = '')
    {
        $builder = $this->db->table('appointments a');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,s.statename,(CASE WHEN LENGTH(ud.id) > 0 THEN ud.id ELSE "" END) as id,(CASE WHEN LENGTH(ud.user_id) > 0 THEN ud.user_id ELSE "" END) as user_id,(CASE WHEN LENGTH(ud.blood_group) > 0 THEN ud.blood_group ELSE "" END) as blood_group,(CASE WHEN LENGTH(ud.gender) > 0 THEN ud.gender ELSE "" END) as gender,(CASE WHEN LENGTH(c.country) > 0 THEN c.country ELSE " " END) as countryname,(CASE WHEN LENGTH(ci.city) > 0 THEN ci.city ELSE " " END) as cityname,(CASE WHEN LENGTH(ud.dob) > 0 THEN ud.dob ELSE " " END) as dob');
        $builder->join('users u', 'a.appointment_from = u.id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('u.role', '2');
        $builder->where('a.appointment_to', $userId);
        $builder->groupBy('a.appointment_from');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = $page - 1;
            }
            $page = (int)$page;
            $limit = isset($limit) ? (int) $limit : 0;
            $page = $page * $limit;
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }


    public function patientsListsNew($pages, $limits, $userId,$role_id,$type)
    {
        $builder = $this->db->table('appointments a');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,s.statename,(CASE WHEN LENGTH(ud.id) > 0 THEN ud.id ELSE "" END) as id,(CASE WHEN LENGTH(ud.user_id) > 0 THEN ud.user_id ELSE "" END) as user_id,(CASE WHEN LENGTH(ud.blood_group) > 0 THEN ud.blood_group ELSE "" END) as blood_group,(CASE WHEN LENGTH(ud.gender) > 0 THEN ud.gender ELSE "" END) as gender,(CASE WHEN LENGTH(c.country) > 0 THEN c.country ELSE " " END) as countryname,(CASE WHEN LENGTH(ci.city) > 0 THEN ci.city ELSE " " END) as cityname,(CASE WHEN LENGTH(ud.dob) > 0 THEN ud.dob ELSE " " END) as dob');
        $builder->join('users u', 'a.appointment_from = u.id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('u.role', '2');
        $builder->where('a.appointment_to', $userId);
        if($role_id==6){
            $builder->orWhere('a.hospital_id',$userId);
        }
        $builder->groupBy('a.appointment_from');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = $page - 1;
            }
            $page = (int)$page;
            $limit = isset($limit) ? (int) $limit : 0;
            $page = $page * $limit;
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * function to getrow data to db
     *
     * $whereData - input data to be an single associative array
     * @param  string $tblNme
     * @param  mixed $whereData
     * @return mixed
     */
    public function getRowData($tblNme, $whereData)
    {
        $builder = $this->db->table($tblNme);
        $builder->where($whereData);
        $query = $builder->countAllResults();
        return $query;
    }
    /**
     * function to doctor details
     * @param  mixed $doctorId
     * @return mixed
     */
    public function getDoctorDetails($doctorId)
    {
        $builder = $this->db->table('users u');
        $profileimage = 'assets/img/user.png';
        $builder->select('u.first_name,u.last_name,u.id as userid,u.username,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,ud.user_id,ud.gender,ud.dob,ud.blood_group,ud.biography,ud.clinic_name,ud.clinic_address,ud.address1,ud.address2,ud.postal_code,IF(ud.price_type = "Custom Price", "Paid", "Free") as price_type,IF(ud.amount IS NULL or ud.amount = "", "0", ud.amount) as amount,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value,(CASE WHEN LENGTH(u.first_name) > 0 THEN u.first_name ELSE " " END) as first_name,
        (CASE WHEN LENGTH(u.last_name) > 0 THEN u.last_name ELSE "" END) as last_name,
        (CASE WHEN LENGTH(u.email) > 0 THEN u.email ELSE "" END) as email,
        (CASE WHEN LENGTH(u.mobileno) > 0 THEN u.mobileno ELSE "" END) as mobileno,
        (CASE WHEN LENGTH(ud.address1) > 0 THEN ud.address1 ELSE "" END) as address1,
        (CASE WHEN LENGTH(ud.user_id) > 0 THEN ud.user_id ELSE "" END) as user_id,(CASE WHEN LENGTH(ud.gender) > 0 THEN ud.gender ELSE "" END) as gender,(CASE WHEN LENGTH(ud.dob) > 0 THEN ud.dob ELSE "" END) as dob,(CASE WHEN LENGTH(ud.blood_group) > 0 THEN ud.blood_group ELSE "" END) as blood_group,(CASE WHEN LENGTH(ud.biography) > 0 THEN ud.biography ELSE "" END) as biography,(CASE WHEN LENGTH(ud.clinic_name) > 0 THEN ud.clinic_name ELSE "" END) as clinic_name,(CASE WHEN LENGTH(ud.clinic_address) > 0 THEN ud.clinic_address ELSE "" END) as clinic_address,(CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE "" END) as address2,(CASE WHEN LENGTH(ud.postal_code) > 0 THEN ud.postal_code ELSE "" END) as postal_code,(CASE WHEN LENGTH(ud.services) > 0 THEN ud.services ELSE "" END) as services,(CASE WHEN LENGTH(s.statename) > 0 THEN s.statename ELSE "" END) as statename,(CASE WHEN LENGTH(c.country) > 0 THEN c.country ELSE "" END) as countryname,(CASE WHEN LENGTH(ci.city) > 0 THEN ci.city ELSE "" END) as cityname,(CASE WHEN LENGTH(sp.specialization) > 0 THEN sp.specialization ELSE "" END) as speciality,(CASE WHEN LENGTH(sp.specialization_img) > 0 THEN sp.specialization_img ELSE "" END) as specialization_img');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role', '1');
        $builder->where("(u.status = '1' OR u.status = '2')");
        $builder->where('u.is_verified', '1');
        $builder->where('u.is_updated', '1');
        $builder->where('u.id', $doctorId);
        return $builder->get()->getRowArray();
    }
    /**
     * function to is favourite
     * @param  mixed $doctorId
     * @param  mixed $patientId
     * @return mixed
     */
    public function isFavourite($doctorId, $patientId)
    {
        $builder = $this->db->table('favourities');
        $where = ['patient_id' => $patientId, 'doctor_id' => $doctorId];
        $isFavourite = $builder->getWhere($where)->getResultArray();
        $favourites = '0';
        if (count($isFavourite) > 0) {
            $favourites = '1';
        }
        return $favourites;
    }
    /**
     * function to get education details
     * @param  mixed $id
     * @return mixed
     */
    public function getEducationDetails($id)
    {
        $builder = $this->db->table('education_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * function to get experience details
     * @param  mixed $id
     * @return mixed
     */
    public function getExperienceDetails($id)
    {
        $builder = $this->db->table('experience_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * function to get awards details
     * @param  mixed $id
     * @return mixed
     */
    public function getAwardsDetails($id)
    {
        $builder = $this->db->table('awards_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * function to get memberships details
     * @param  mixed $id
     * @return mixed
     */
    public function getMembershipsDetails($id)
    {
        $builder = $this->db->table('memberships_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * function to get registrations details
     * @param  mixed $id
     * @return mixed
     */
    public function getRegistrationsDetails($id)
    {
        $builder = $this->db->table('registrations_details');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * function to get business hours
     * @param  mixed $id
     * @return mixed
     */
    public function getBusinessHours($id)
    {
        $builder = $this->db->table('business_hours');
        $builder->select('*');
        $builder->where('user_id', $id);
        $result = $builder->get()->getResultArray();
        return $result;
    }
    /**
     * function to review list view
     * @param  mixed $id
     * @return mixed
     */
    public function reviewListView($id)
    {
        $db = \Config\Database::connect(); // Connect to the database

        $builder = $this->db->table('rating_reviews r');
        $profileimage = 'assets/img/user.png';
        $where = ['r.doctor_id' => $id];
        $builder->select('IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage, u.first_name, u.last_name, r.*')
            ->join('users u', 'u.id = r.user_id')
            ->where($where);
        $results = $builder->get()->getResultArray();
        return $results;
    }
    /**
     * function to doctor search
     * @param  mixed $userData
     * @param  mixed $pages
     * @param  string $limits
     * @param  string $type
     * @param  mixed $roles
     * @return mixed
     */
    public function doctorSearch($userData, $pages, $limits, $type = '', $roles = '')
    {
        $builder = $this->db->table('users u');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.user_id, ud.services, ud.price_type,ud.amount, ud.currency_code, ud.gender, c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality, (CASE WHEN LENGTH(sp.specialization_img) > 0 THEN sp.specialization_img ELSE "" END) as specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value, u.role');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        if (!empty($userData['role'])) {
            $builder->whereIn('u.role', [$userData['role']]);
        } else {
            $builder->whereIn('u.role', [1]);
        }
        $builder->where('u.status', '1');
        $builder->where('u.is_verified', '1');
        $builder->where('u.is_updated', '1');
        if (!empty($userData['city'])) {
            $builder->where("(ci.city = '" . $userData['city'] . "')");
        }
        if (!empty($userData['state'])) {
            $builder->where("(s.statename = '" . $userData['state'] . "')");
        }
        if (!empty($userData['country'])) {
            $builder->where('c.country', $userData['country']);
        }
        if (isset($userData['specialization']) && !empty(libsodiumEncrypt($userData['specialization']))) {
            $builder->where('sp.specialization', libsodiumEncrypt($userData['specialization']));
        }
        if (!empty($userData['keywords'])) {
            $builder->groupStart();
            $builder->like('u.first_name', libsodiumEncrypt($userData['keywords']), 'after');
            $builder->orLike('u.last_name', libsodiumEncrypt($userData['keywords']), 'after');
            $builder->orLike('sp.specialization', libsodiumEncrypt($userData['keywords']));
            $builder->groupEnd();
        }
        if (!empty($userData['gender'])) {
            $builder->where('ud.gender', $userData['gender']);
        }
        if (isset($userData['username']) && !empty(libsodiumEncrypt($userData['username']))) {
            $builder->where('u.username', libsodiumEncrypt($userData['username']));
        }
        if (!empty($userData['order_by'])) {
            if ($userData['order_by'] == 'Free') {
                $builder->where('ud.price_type', 'Free');
            }
        }
        $builder->groupBy('ud.id');
        $builder->orderBy('u.id', 'DESC');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = $page - 1;
            }
            $page = ($page * $limit);

            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }
    /**
     * function to get clinic doctor details
     * @param  mixed $doctorId
     * @return mixed
     */
    public function getClinicDoctorDetails($doctorId)
    {
        $profileimage = 'assets/img/user.png';
        $builder = $this->db->table('users u');
        $builder->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,ud.user_id,ud.gender,ud.dob,ud.blood_group,ud.biography,ud.clinic_name,ud.clinic_address,ud.address1,(CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2,ud.postal_code,IF(ud.price_type = "Custom Price", "Paid", "Free") as price_type,IF(ud.amount IS NULL or ud.amount = "", "0", ud.amount) as amount,ud.services,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,(CASE WHEN LENGTH(sp.specialization_img) > 0 THEN sp.specialization_img ELSE "" END) as specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where("(u.status = '1' OR u.status = '2')");
        $builder->where('u.is_verified', '1');
        $builder->where('u.is_updated', '1');
        $builder->where('u.id', $doctorId);
        $query = $builder->get()->getRowArray();
        return $query;
    }
    /**
     * function to specialization lists
     * @param  mixed $pages
     * @param  string $limits
     * @param  string $type
     * @return mixed
     */
    public function specializationLists($pages, $limits, $type = '')
    {
        $builder = $this->db->table('specialization');
        $builder->select('id, specialization, specialization_img');
        $builder->where('status', 1);
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = $page - 1;
            }
            $page = ($page * $limit);
            $builder->orderBy('id', 'DESC');
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * function to prescription details
     * @param  mixed $prescriptionId
     * @return mixed
     */
    public function prescriptionDetails($prescriptionId)
    {
        $builder = $this->db->table('prescription_item_details pd');
        $builder->select('pd.id, pd.drug_name, pd.qty, pd.type, pd.days, pd.time, pd.created_at');
        $builder->where('pd.prescription_id', $prescriptionId);
        return $builder->get()->getResultArray();
    }
    /**
     * function to get prescription view
     * @param  mixed $prescription_id
     * @return mixed
     */
    public function getPrescriptionView($prescription_id)
    {
        $builder = $this->db->table('prescription p');
        $builder->select('p.id, p.doctor_id, p.patient_id, p.signature_id, p.created_at, p.status, si.img as signature_image');
        $builder->join('signature si', 'p.signature_id=si.id', 'left');
        $builder->where('p.id', $prescription_id);
        return $builder->get()->getResultArray();
    }
    /**
     * function to get doctor list.
     *  @return mixed
     */
    public function doctorList()
    {
        $builder = $this->db->table('users u');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.user_id,ud.price_type,ud.amount,ud.currency_code,s.statename,sp.specialization as speciality,(CASE WHEN LENGTH(sp.specialization_img) > 0 THEN sp.specialization_img ELSE " " END) as specialization_img,(CASE WHEN LENGTH(ci.city) > 0 THEN ci.city ELSE " " END) as cityname,(CASE WHEN LENGTH(c.country) > 0 THEN c.country ELSE " " END) as countryname,(CASE WHEN LENGTH(ud.services) > 0 THEN ud.services ELSE " " END) as services,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role', '1');
        $builder->where('u.status', '1');
        $builder->where('u.is_verified', '1');
        $builder->where('u.is_updated', '1');
        $builder->groupBy('ud.id');
        $builder->orderBy('rand()');
        $builder->limit(5);
        $query = $builder->get()->getResultArray();
        return $query;
    }
    /**
     * function to get specialization list.
     *  @return mixed
     */
    public function specializationList()
    {
        $builder = $this->db->table('specialization s');
        $builder->where('status', 1);
        $builder->orderBy('sequence', 'ASC');
        $builder->limit(5);
        $query = $builder->get()->getResultArray();
        return $query;
    }
    /**
     * function to get lab list.
     *  @return mixed
     */
    public function labList()
    {
        $builder = $this->db->table('users u');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.user_id,ud.services,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role', '4');
        $builder->where('u.status', '1');
        $builder->where('u.is_verified', '1');
        $builder->where('u.is_updated', '1');
        $builder->groupBy('ud.id');
        $builder->orderBy('rand()');
        $builder->limit(5);
        $query = $builder->get()->getResultArray();
        return $query;
    }
    /**
     * function to get lab list.
     *  @return mixed
     */
    public function readClinicList()
    {
        $builder = $this->db->table('users u');
        $builder->select('ud.user_id,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,(CASE WHEN LENGTH(s.statename) > 0 THEN s.statename ELSE "" END) as statename,(CASE WHEN LENGTH(ud.currency_code) > 0 THEN ud.currency_code ELSE "" END) as currency_code,(CASE WHEN LENGTH(c.country) > 0 THEN c.country ELSE "" END) as countryname,(CASE WHEN LENGTH(ci.city) > 0 THEN ci.city ELSE "" END) as cityname,(CASE WHEN LENGTH(ud.register_no) > 0 THEN ud.register_no ELSE "" END) as register_no,(CASE WHEN LENGTH(ud.subscription_end) > 0 THEN ud.subscription_end ELSE "" END) as subscription_end,(CASE WHEN LENGTH(sp.specialization_img) > 0 THEN sp.specialization_img ELSE "" END) as specialization_img,(CASE WHEN LENGTH(sp.specialization) > 0 THEN sp.specialization ELSE "" END) as speciality,(CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value, "" as currency');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role', '6');
        $builder->where('u.status', '1');
        $builder->where('u.is_verified', '1');
        $builder->where('u.is_updated', '1');
        $builder->groupBy('ud.id');
        $builder->orderBy('rand()');
        $builder->limit(5);
        $query = $builder->get()->getResultArray();
        return $query;
    }

    /**
     * function to Appointments count.
     * @param  int $type
     * @param  int $userId
     * @param  int $role
     * @return mixed
     */
    public function appointmentsCount($type, $userId, $role)
    {
        $builder = $this->db->table('appointments a');
        $currentDate = date('Y-m-d');
        $fromDateTime = date('Y-m-d H:i:s');
        $builder->select('a.appointment_from,a.appointment_to,a.appointment_date,a.from_date_time,u.first_name,u.last_name,u.username,u.profileimage,p.per_hour_charge,u.role');
        $builder->join('payments p', 'p.id = a.payment_id', 'left');
        if ($role == 1 || $role == 6) {
            $builder->join('users u', 'u.id = a.appointment_from', 'left');
            $builder->where('a.appointment_to', $userId);
        }
        if ($role == 2) {
            $builder->join('users u', 'u.id = a.appointment_to', 'left');
            $builder->where('a.appointment_from', $userId);
        }
        if ($type == 1 || $type == '1') {
            $builder->where('a.appointment_date', $currentDate);
        }
        if ($type == 2 || $type == '2') {
            $builder->where('a.from_date_time > ', $fromDateTime);
            $builder->where('a.appointment_status!=','2');
        }
        return $builder->countAllResults();
    }

    /**
     * function to prescription list.
     * @param  string $pages
     * @param  string $limits
     * @param  string $type
     * @param  int $patientId
     * @param  int $userId
     * @param  int $role
     * @return mixed
     */
    public function prescriptionList($pages, $limits, $patientId, $userId, $role, $type = '')
    {
        $builder = $this->db->table('prescription p');
        $builder->select('u.id,p.id as prescription_id, p.created_at, p.doctor_id,p.patient_id,p.signature_id,u.first_name,u.last_name,CONCAT(u.first_name," ", u.last_name) as doctor_name,us.first_name as patient_first_name,us.last_name as patient_last_name,CONCAT(us.first_name," ", us.last_name) as patient_name ,u.profileimage as doctor_image,us.profileimage as patient_image,s.specialization,(CASE WHEN LENGTH(si.img) > 0 THEN si.img ELSE "" END) as signature_image');
        $builder->join('prescription_item_details pd', 'pd.prescription_id=p.id', 'left');
        $builder->join('users u', 'u.id = p.doctor_id', 'left');
        $builder->join('users us', 'us.id = p.patient_id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('specialization s', 'ud.specialization = s.id', 'left');
        $builder->join('signature si', 'p.signature_id=si.id', 'left');
        $builder->where('p.patient_id', $patientId);
        $builder->where('p.status', 1);
        if ($role == 1) {
            $builder->where('p.doctor_id', $userId);
        }
        $builder->groupBy('p.id');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->orderBy('p.id', 'DESC');
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }

    /**
     * function to Medical Records List.
     * @param  string $pages
     * @param  string $limits
     * @param  string $type
     * @param  mixed $patientId
     * @param  int $userId
     * @param  int $role
     * @return mixed
     */
    public function medicalRecordsList($pages, $limits, $patientId, $userId, $role, $type = '')
    {
        $builder = $this->db->table('medical_records m');
        $builder->select('m.id,m.doctor_id,m.description,m.file_name,m.date,u.first_name,u.last_name,CONCAT(u.first_name," ", u.last_name) as doctor_name,us.first_name as patient_first_name,us.last_name as patient_last_name,CONCAT(us.first_name," ", us.last_name) as patient_name,u.profileimage as doctor_image,us.profileimage as patient_image,s.specialization');
        $builder->join('users u', 'u.id = m.doctor_id', 'left');
        $builder->join('users us', 'us.id = m.patient_id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('specialization s', 'ud.specialization = s.id', 'left');
        $builder->where('m.patient_id', $patientId);
        $builder->where('m.status', 1);
        if ($role == 1) {
            $builder->where('m.doctor_id', $userId);
            $builder->orWhere('m.doctor_id', 0);
        }
        $builder->groupBy('m.id');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->orderBy('m.id', 'DESC');
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }

    /**
     * function to Billing list.
     * @param  string $pages
     * @param  string $limits
     * @param  string $type
     * @param  mixed $patientId
     * @param  int $userId
     * @param  int $role
     * @return mixed
     */
    public function billingList($pages, $limits, $patientId, $userId, $role, $type = '')
    {
        $builder = $this->db->table('billing b');
        $builder->select('b.id, u.first_name, u.last_name, b.doctor_id,b.patient_id,b.signature_id, b.created_at, CONCAT(COALESCE(u.first_name, ""), " ", COALESCE(u.last_name, "")) as doctor_name, CONCAT(u1.first_name, " ", u1.last_name) as patient_name,u1.first_name as patient_first_name,u1.last_name as patient_last_name, u.profileimage as doctor_image, u1.profileimage as patient_image, s.specialization,(CASE WHEN LENGTH(si.img) > 0 THEN si.img ELSE "" END) as signature_image');
        $builder->join('billing_item_details bd', 'bd.billing_id=b.id', 'left');
        $builder->join('users u', 'u.id = b.doctor_id', 'left');
        $builder->join('users u1', 'u1.id = b.patient_id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('specialization s', 'ud.specialization = s.id', 'left');
        $builder->join('signature si', 'b.signature_id=si.id', 'left');
        $builder->where('b.patient_id', $patientId);
        $builder->where('b.status', 0);
        if ($role == 1) {
            $builder->where('b.doctor_id', $userId);
        }
        $builder->groupBy('b.id');

        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->orderBy('b.id', 'DESC');
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * function to Billing details.
     * @param  mixed $billingId
     * @return mixed
     */
    public function billingDetails($billingId)
    {
        $builder = $this->db->table('billing_item_details bd');
        $builder->select('bd.id, bd.billing_id, bd.name, bd.amount, bd.created_at');
        $builder->where('bd.billing_id', $billingId);
        return $builder->get()->getResultArray();
    }
    /**
     * function to Patient Details.
     * @param  int $userId
     * @return mixed
     */
    public function patientDetails($userId)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,s.statename,(CASE WHEN LENGTH(c.country) > 0 THEN c.country ELSE " " END) as countryname,(CASE WHEN LENGTH(ci.city) > 0 THEN ci.city ELSE " " END) as cityname,(CASE WHEN LENGTH(ud.gender) > 0 THEN ud.gender ELSE " " END) as gender,(CASE WHEN LENGTH(ud.blood_group) > 0 THEN ud.blood_group ELSE " " END) as blood_group,(CASE WHEN LENGTH(ud.dob) > 0 THEN ud.dob ELSE " " END) as dob,(CASE WHEN LENGTH(ud.id) > 0 THEN ud.id ELSE " " END) as id,(CASE WHEN LENGTH(ud.user_id) > 0 THEN ud.user_id ELSE " " END) as user_id');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('u.role', '2');
        $builder->where('u.id', $userId);
        $rows = $builder->get()->getRowArray();

        $data = array();
        if (!empty($rows)) {
            $decryptFirstName = libsodiumDecrypt($rows['first_name']);
            $decryptLastName = libsodiumDecrypt($rows['last_name']);
            $data['id'] = $rows['id'];
            $data['patient_id'] = $rows['user_id'];
            $data['username'] = libsodiumDecrypt($rows['username']);
            $data['profileimage'] = (!empty($rows['profileimage'])) ? $rows['profileimage'] : 'assets/img/user.png';
            $data['first_name'] = ucfirst($decryptFirstName);
            $data['last_name'] = ucfirst($decryptLastName);
            $data['mobileno'] = libsodiumDecrypt($rows['mobileno']);
            $data['dob'] = !empty($rows['dob']) ? $rows['dob'] : '';
            $data['age'] = age_calculate($rows['dob']);
            $data['blood_group'] = libsodiumDecrypt($rows['blood_group']);
            $data['gender'] = $rows['gender'];
            $data['cityname'] = $rows['cityname'];
            $data['countryname'] = !empty($rows['countryname']) ? $rows['countryname'] : '';
        }
        if (!empty($data)) {
            return $data;
        }
    }
    /**
     * function to Doctor Details.
     * @param  int $userId
     * @param  mixed $patientId
     * @return mixed
     */
    public function doctorDetails($userId, $patientId)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.price_type,ud.amount,(CASE WHEN LENGTH(ed.degree) > 0 THEN ed.degree ELSE " " END) as degree,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,(CASE WHEN LENGTH(sp.specialization_img) > 0 THEN sp.specialization_img ELSE " " END) as specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value,(CASE WHEN LENGTH(c.country) > 0 THEN c.country ELSE " " END) as countryname,(CASE WHEN LENGTH(ci.city) > 0 THEN ci.city ELSE " " END) as cityname,(CASE WHEN LENGTH(ud.services) > 0 THEN ud.services ELSE " " END) as services,(CASE WHEN LENGTH(ud.user_id) > 0 THEN ud.user_id ELSE " " END) as user_id');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('education_details ed', 'ed.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.id', $userId);
        $rows = $builder->get()->getRowArray();
        $data = array();
        if (!empty($rows)) {
            $decryptFirstName = libsodiumDecrypt($rows['first_name']);
            $decryptLastName = libsodiumDecrypt($rows['last_name']);
            $decryptSpeciality = libsodiumDecrypt($rows['speciality']);
            $data['id'] = $rows['user_id'];
            $data['username'] = libsodiumDecrypt($rows['username']);
            $data['profileimage'] = (!empty($rows['profileimage'])) ? $rows['profileimage'] : 'assets/img/user.png';
            $data['first_name'] = ucfirst($decryptFirstName);
            $data['last_name'] = ucfirst($decryptLastName);
            $data['specialization_img'] = $rows['specialization_img'];
            $data['speciality'] = ucfirst($decryptSpeciality);
            $data['degree'] = $rows['degree'];
            $data['cityname'] = $rows['cityname'];
            $data['countryname'] = $rows['countryname'];
            $data['services'] = $rows['services'];
            $data['rating_value'] = $rows['rating_value'];
            $data['rating_count'] = $rows['rating_count'];
            $data['currency'] = '$';
            $data['is_favourite'] = $this->isFavourite($rows['user_id'], $patientId);
            $data['price_type'] = ($rows['price_type'] == 'Custom Price') ? 'Paid' : 'Free';
            $data['slot_type'] = 'per slot';
            $data['amount'] = ($rows['price_type'] == 'Custom Price') ? $rows['amount'] : '0';
        }
        if (!empty($data)) {
            return $data;
        } else {
            return true;
        }
    }
    /**
     * function to User Order List.
     * @param  int $userId
     * @return mixed
     */
    public function userOrderList($userId)
    {
        $builder = $this->db->table('order_user_details od');
        $builder->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,(CASE WHEN LENGTH(us.pharmacy_name) > 0 THEN us.pharmacy_name ELSE "" END) as pharmacy_name,(CASE WHEN LENGTH(od.shipping) > 0 THEN od.shipping ELSE "" END) as shipping,SUM(o.quantity) as qty');
        $builder->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
        $builder->join('users as us', 'us.id = od.pharmacy_id', 'left');
        $builder->where('od.user_id', $userId);
        $builder->groupBy('od.order_user_details_id');
        $builder->orderBy('od.order_user_details_id', 'DESC');
        return $builder->get()->getResultArray();
    }
    /**
     * function to User Upcoming Order List.
     * @param  int $userId
     * @return mixed
     */
    public function userUpcomingOrderList($userId)
    {
        $date = date('Y-m-d H:i:s');
        $builder = $this->db->table('order_user_details od');
        $builder->select('us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');
        $builder->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
        $builder->join('users as us', 'us.id = od.pharmacy_id', 'left');
        $builder->where('od.user_id', $userId);
        $builder->where('od.created_at >', $date);
        $builder->groupBy('od.order_user_details_id');
        return $builder->get()->getResultArray();
    }
    /**
     * function to User Today Order List.
     * @param  int $userId
     * @return mixed
     */
    public function userTodayOrderList($userId)
    {
        $date = date('Y-m-d');
        $builder = $this->db->table('order_user_details od');
        $builder->select('us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');
        $builder->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
        $builder->join('users as us', 'us.id = od.pharmacy_id', 'left');
        $builder->where('od.user_id', $userId);
        $builder->where('date(od.created_at)', $date);
        $builder->groupBy('od.order_user_details_id');
        return $builder->get()->getResultArray();
    }
    /**
     * function to pharmacy Order List Upcoming.
     * @param  mixed $pharmacyId
     * @return mixed
     */
    public function pharmacyOrderListUpcoming($pharmacyId)
    {
        $date = date('Y-m-d H:i:s');
        $builder = $this->db->table('order_user_details od');
        $builder->select('us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');
        $builder->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
        $builder->join('users as us', 'us.id = od.pharmacy_id', 'left');
        $builder->where('od.pharmacy_id', $pharmacyId);
        $builder->where('od.created_at >', $date);
        $builder->groupBy('od.order_user_details_id');
        return $builder->get()->getResultArray();
    }
    /**
     * function to Pharmacy Order List Today.
     * @param  mixed $pharmacyId
     * @return mixed
     */
    public function pharmacyOrderListToday($pharmacyId)
    {
        $date = date('Y-m-d');
        $builder = $this->db->table('order_user_details od');
        $builder->select('us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,SUM(o.quantity) as qty');
        $builder->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
        $builder->join('users as us', 'us.id = od.pharmacy_id', 'left');
        $builder->where('od.pharmacy_id', $pharmacyId);
        $builder->where('date(od.created_at)', $date);
        $builder->groupBy('od.order_user_details_id');
        return $builder->get()->getResultArray();
    }
    /**
     * function to Pharmacy Order List.
     * @param  mixed $pharmacyId
     * @return mixed
     */
    public function pharmacyOrderList($pharmacyId)
    {
        $builder = $this->db->table('orders o');
        $builder->select('o.*,o.user_order_id as order_user_details_id,o.subtotal as total_amount,od.currency,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,(CASE WHEN LENGTH(o.quantity) > 0 THEN o.quantity ELSE "" END) as qty,(CASE WHEN LENGTH(od.shipping) > 0 THEN od.shipping ELSE "" END) as shipping');
        $builder->join('order_user_details as od', 'od.order_user_details_id = o.user_order_id', 'left');
        $builder->join('users as us', 'us.id = o.pharmacy_id', 'left');
        $builder->where('o.pharmacy_id', $pharmacyId);
        // $builder->groupBy('od.order_user_details_id');
        $builder->orderBy('o.id', 'DESC');
        return $builder->get()->getResultArray();
        // echo $this->db->getLastQuery();
        // exit;
    }
    /**
     * function to lab info.
     * @param  mixed $labId
     * @return mixed
     */
    public function labInfo($labId, $role)
    {
        $builder = $this->db->table('lab_payments lp');
        $builder->select('lp.lab_id,u.first_name,u.last_name,CONCAT(u.first_name," ", u.last_name) as lab_name,u.email,u.username,u.mobileno,u.profileimage,ud.services,ud.user_id,ud.price_type,ud.amount,ud.currency_code,(CASE WHEN LENGTH(ud.address1) > 0 THEN ud.address1 ELSE " " END) as address1, (CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2,c.country as countryname,s.statename,ci.city as cityname');
        if ($role == '4') {
            $builder->join('users u', 'lp.lab_id = u.id', 'left');
        } else {
            $builder->join('users u', 'lp.patient_id = u.id', 'left');
        }
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('u.id', $labId);
        $builder->groupBy('lp.lab_id');
        return $builder->get()->getResultArray();
    }
    /**
     * function to lab dashboard info.
     * @param  mixed $labId
     * @return mixed
     */
    public function labDashboardInfo($labId, $role)
    {
        $builder = $this->db->table('users u');
        $builder->select('lp.lab_id,u.first_name,u.last_name,CONCAT(u.first_name," ", u.last_name) as lab_name,u.email,u.username,u.mobileno,u.profileimage,ud.services,ud.user_id,ud.price_type,ud.amount,ud.currency_code,(CASE WHEN LENGTH(ud.address1) > 0 THEN ud.address1 ELSE " " END) as address1, (CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2,c.country as countryname,s.statename,ci.city as cityname');
        $builder->join('lab_payments lp', 'lp.lab_id = u.id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        if ($role == '2') {
            $builder->where('lp.patient_id', $labId);
        } else {
            $builder->where('u.id', $labId);
        }        
        $builder->groupBy('lp.lab_id');
        return $builder->get()->getResultArray();
    }
    /**
     * function to Get Total Test New.
     * @param  int $userId
     * @return mixed
     */
    public function getTotalTestNew($userId)
    {
        $where = ['lab_id' => $userId];
        return $this->db->table('lab_tests')->where($where)->countAllResults();
    }
    /**
     * function to Get Today Lab Patient.
     * @param  int $userId
     * @return mixed
     */
    public function getTodayLabPatient($userId)
    {
        $where = array('lab_id' => $userId, 'lab_test_date' => date('Y-m-d'));
        // return $this->db->table('lab_payments')->groupBy('lab_id')->where($where)->countAllResults();
        return $this->db->table('lab_payments')->where($where)->countAllResults();
    }
    /**
     * function to Get Recent Lab Booking.
     * @param  int $userId
     * @return mixed
     */
    public function getRecentLabBooking($userId)
    {
        $where = array('lab_id' => $userId);
        return $this->db->table('lab_payments')->where($where)->countAllResults();
    }
    /**
     * function to Get Upcoming Lab Booking New.
     * @param  int $userId
     * @return mixed
     */
    public function getUpcomingLabBookingNew($userId)
    {
        $where = array('lab_id' => $userId, 'lab_test_date >' => date('Y-m-d'));
        return $this->db->table('lab_payments')->where($where)->countAllResults();
    }
    /**
     * function to Get Today Patient Lab Patient.
     * @param  int $userId
     * @return mixed
     */
    public function getTodayPatientLabPatient($userId)
    {
        $where = array('patient_id' => $userId, 'lab_test_date' => date('Y-m-d'));
        return $this->db->table('lab_payments')->where($where)->countAllResults();
    }
    /**
     * function to Get Recent Patient Lab Booking.
     * @param  int $userId
     * @return mixed
     */
    public function getRecentPatientLabBooking($userId)
    {
        $where = array('patient_id' => $userId);
        return $this->db->table('lab_payments')->where($where)->countAllResults();
    }
    /**
     * function to Get Upcoming Patient Lab Booking.
     * @param  int $userId
     * @return mixed
     */
    public function getUpcomingPatientLabBooking($userId)
    {
        $where = array('patient_id' => $userId, 'lab_test_date >' => date('Y-m-d'));
        return $this->db->table('lab_payments')->where($where)->countAllResults();
    }
    /**
     * function to Get Favourites.
     * @param  string $pages
     * @param  string $limits
     * @param  string $type
     * @param  int $userId
     * @return mixed
     */
    public function getFavourites($pages, $limits, $userId, $type = '')
    {
        $builder = $this->db->table('favourities f');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,(CASE WHEN LENGTH(sp.specialization_img) > 0 THEN sp.specialization_img ELSE " " END) as specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $builder->join('users u', 'u.id = f.doctor_id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('f.patient_id', $userId);
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * function to Review List.
     * @param  string $pages
     * @param  string $limits
     * @param  string $type
     * @param  int $userId
     * @param  int $roleId
     * @return mixed
     */
    public function reviewList($pages, $limits, $userId, $roleId, $type = '')
    {
        $profileimage = 'assets/img/user.png';
        $builder = $this->db->table('rating_reviews r');
        $builder->select('u.first_name,u.last_name,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,r.id, r.doctor_id, r.user_id, r.title, r.review, r.rating, r.created_date, r.time_zone');
        if ($roleId == 1) {
            $builder->join('users u ', 'r.user_id = u.id');
            $builder->where('r.doctor_id', $userId);
        } else {
            $builder->join('users u ', 'r.doctor_id = u.id');
            $builder->where('r.user_id', $userId);
        }
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * function to Appointments Lists.
     * @param  string $pages
     * @param  string $limits
     * @param  mixed $type
     * @param  mixed $userData
     * @param  int $userId
     * @param  int $role
     * @return mixed
     */
    public function appointmentsLists($pages, $limits, $userData, $userId, $role, $type = '')
    {
        $builder = $this->db->table('appointments a');
        $currentDate = date('Y-m-d');
        $fromDateTime = date('Y-m-d H:i:s');
        $builder->select('a.appointment_time,a.appointment_end_time,a.appointment_from,a.appointment_to,a.payment_method,a.appointment_date,a.from_date_time,a.time_zone,a.to_date_time,a.from_date_time,a.created_date,a.id,a.approved,a.type,u.role,(CASE WHEN LENGTH(u.first_name) > 0 THEN u.first_name ELSE " " END) as first_name,
        (CASE WHEN LENGTH(u.last_name) > 0 THEN u.last_name ELSE " " END) as last_name,
        (CASE WHEN LENGTH(u.email) > 0 THEN u.email ELSE " " END) as email,
        (CASE WHEN LENGTH(u.username) > 0 THEN u.username ELSE " " END) as username,u.profileimage,p.per_hour_charge,u.role');
        $builder->join('payments p', 'p.id = a.payment_id', 'left');
        if ($role == 1 || $role == 6) {
            $builder->join('users u', 'u.id = a.appointment_from', 'left');
            $builder->where('a.appointment_to', $userId);
        }
        if ($role == 2) {
            $builder->join('users u', 'u.id = a.appointment_to', 'left');
            $builder->where('a.appointment_from', $userId);
        }
        /*if (!empty($userData['payment_method'])) {
            $builder->where('a.payment_method', $userData['payment_method']);
        } else {
            $builder->groupStart();
            $builder->where('a.payment_method', 1);
            $builder->orWhere('a.payment_method', 2);
            $builder->orWhere('a.payment_method', 3);
            $builder->orWhere('a.payment_method', 4);
            $builder->orWhere('a.payment_method', 'Paypal');
            $builder->orWhere('a.payment_method', 'Online');
            $builder->groupEnd();
        }*/
        if (isset($userData['payment_method'])) {
            if (!empty($userData['payment_method'] == 2)) // default send 2
            {
                $builder->groupStart();
                $builder->where('a.type', "clinic");  // offline payment
                $builder->orWhere('a.type', "Clinic");
                $builder->groupEnd();
            } else {
                $builder->groupStart();
                $builder->where('a.type', "online");
                $builder->orWhere('a.type', "Online");
                $builder->orWhere('a.type', "Free Booking");
                $builder->groupEnd();
            }
        }

        if (!empty($userData['patient_id'])) {
            $builder->where('a.appointment_from', $userData['patient_id']);
        }
        if (!empty($userData['type'])) {
            if ($userData['type'] == 1 || $userData['type'] == '1') {
                $builder->where('a.appointment_date', $currentDate);
            }
            if ($userData['type'] == 2 || $userData['type'] == '2') {
                // echo "test";
                $builder->where('a.from_date_time > ', $fromDateTime);
                $builder->where('a.appointment_status!=','2');
            }
        }
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->orderBy('a.id', 'DESC');
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            
            return $query;
        }
    }
    /**
     * function to Lab Test Lists.
     * @param  string $pages
     * @param  string $limits
     * @param  string $type
     * @param  mixed $labId
     * @return mixed
     */
    public function labTestLists($pages, $limits, $labId, $type = '')
    {
        $builder = $this->db->table('lab_tests lt');
        $builder->select('*');
        $builder->where('lab_id', $labId);
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->orderBy('id', 'DESC');
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }
    /**
     * function to Lab Appointments Lists.
     * @param  string $pages
     * @param  string $limits
     * @param  string $type
     * @param  mixed $userData
     * @param  int $userId
     * @param  int $role
     * @return mixed
     */
    public function labAppointmentsLists($pages, $limits, $userData, $userId, $role, $type = '')
    {
        $currentDate = date('Y-m-d');
        $fromDateTime = date('Y-m-d H:i:s');
        $builder = $this->db->table('lab_payments lp');
        $builder->select('lp.lab_id,lp.patient_id,lp.lab_test_date,lp.id,lp.status,lp.total_amount,lp.currency_code,lp.booking_ids,us.first_name,us.last_name,us.username,us.profileimage,us.role');
        $builder->join('users us', 'us.id = lp.patient_id', 'left');
        if ($role == 4) {
            $builder->join('users u', 'u.id = lp.patient_id', 'left');
            $builder->where('lp.lab_id', $userId);
        }
        if ($role == 2) {
            $builder->join('users u', 'u.id = lp.lab_id', 'left');
            $builder->where('lp.patient_id', $userId);
        }
        if (!empty($userData['type'])) {
            if ($userData['type'] == 1) {
                $builder->where('lp.lab_test_date', $currentDate);
            }
            if ($userData['type'] == 2) {
                $builder->where('lp.lab_test_date > ', $fromDateTime);
            }
        }
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->orderBy('lp.lab_test_date', 'DESC');
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }
    /**
     * function to Mylabs Lists.
     * @param  string $pages
     * @param  string $limits
     * @param  string $type
     * @param  int $userId
     * @return mixed
     */
    public function mylabsLists($pages, $limits, $userId, $type = '')
    {
        $builder = $this->db->table('lab_payments lp');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
        $builder->join('users u', 'lp.lab_id = u.id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('u.role', '4');
        $builder->where('lp.patient_id', $userId);
        $builder->groupBy('lp.lab_id');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * function to Mylabs Lists.
     * @param  mixed $appoinment_id
     * @return mixed
     */
    public function getAppoinmentCallDetails($appoinment_id)
    {
        $builder = $this->db->table('appointments a');
        $builder->select('a.*,d.first_name,d.last_name, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.device_id as doctor_device_id,d.device_type as doctor_device_type,p.first_name as patient_first_name,p.last_name as patient_last_name, CONCAT(p.first_name," ", p.last_name) as patient_name,p.profileimage as patient_profileimage,p.id as patient_id,p.device_id as patient_device_id,p.device_type as patient_device_type,d.id as doctor_id');
        $builder->join('users d', 'd.id = a.appointment_to', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->join('users p', 'p.id = a.appointment_from', 'left');
        $builder->join('users_details pd', 'pd.user_id = p.id', 'left');
        $builder->where('a.id', $appoinment_id);
        return $builder->get()->getRowArray();
    }

    /**
     * function to Invoice Lists.
     * @param  int $userId
     * @param  int $role
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @return mixed
     */
    public function readInvoiceList($userId, $role, $pages, $limits, $type = 1)
    {
        $builder = $this->db->table('payments p');
        $builder->select('p.total_amount,p.currency_code,p.id,p.invoice_no,p.payment_date,d.first_name,d.last_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,pi.first_name as patient_first_name,pi.last_name as patient_last_name,pi.profileimage as patient_profileimage,pi.id as patient_id,d.role');
        $builder->join('users d', 'd.id = p.doctor_id', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->join('users pi', 'pi.id = p.user_id', 'left');
        $builder->join('users_details pd', 'pd.user_id = pi.id', 'left');
        $builder->where('p.doctor_id > 0');

        if ($role == '1' || $role == '4' || $role == '6') {
            $builder->where('p.doctor_id', $userId);
        }
        if ($role == '5') {
            $builder->groupStart();
            $builder->where('p.doctor_id', $userId);
            $builder->orWhere("FIND_IN_SET(" . $userId . ", p.pharmacy_id)");
            $builder->groupEnd();
        }
        if ($role == '2') {
            $builder->where('p.user_id', $userId);
        }
        $builder->where('p.payment_status', 1);
        $builder->orderBy('p.id', 'desc');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            // echo $this->db->getLastQuery();
            return $query;
        }
    }
    /**
     * function to Doctor Lists.
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @return mixed
     */
    public function doctorLists($pages, $limits, $type = 1)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.user_id,ud.services,ud.amount,ud.currency_code,ud.price_type,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,(CASE WHEN LENGTH(sp.specialization_img) > 0 THEN sp.specialization_img ELSE " " END) as specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role', '1');
        $builder->where('u.status', '1');
        $builder->where('u.is_verified', '1');
        $builder->where('u.is_updated', '1');
        $builder->groupBy('ud.id');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->orderBy('u.id', 'DESC');
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }
    /**
     * function to signup.
     * @param  string $tableName
     * @param  mixed $inputdata
     * @return mixed
     */
    public function signup($tableName, $inputdata)
    {
        if ($this->db->table($tableName)->insert($inputdata)) {
            return ($this->db->affectedRows() != 1) ? false : true;
        }
    }
    /**
     * function to Assign Doctor.
     * @param  mixed $id
     * @param  mixed $appId
     * @param  int $userId
     * @return mixed
     */
    public function assignDoctor($id, $appId, $userId)
    {
        $inputdata = [];
        $inputdata['appointment_to'] = $id;
        $inputdata['hospital_id'] = $userId;
        $this->updateData('appointments', ['id' => $appId], $inputdata);
    }
    /**
     * function to Get Patients.
     * @param  int $userId
     * @return mixed
     */
    public function getPatients($userId)
    {
        $builder = $this->db->table('appointments a');
        $profileimage = 'assets/img/user.png';
        $builder->select('u.id as userid,u.role,u.first_name,u.last_name,u.username,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,(select chatdate from chat where sent_id = a.appointment_to GROUP BY a.appointment_to ORDER BY chatdate DESC LIMIT 1) as chatdate,(select msg from chat where sent_id = a.appointment_to GROUP BY a.appointment_to ORDER BY id DESC LIMIT 1) as lastchat');
        $builder->join('users u', 'u.id = a.appointment_from', 'left');
        $builder->where('a.appointment_to', $userId);
        $builder->groupBy('a.appointment_from');
        $builder->orderBy('chatdate', 'DESC');
        return $builder->get()->getResultArray();
    }
    public function getPatientsData($userId)
    {
        $profileimage = 'assets/img/user.png';
        $builder = $this->db->table('appointments a');
        $builder->select('u.id as userid,u.role,u.first_name,u.last_name,u.username, 
        IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,
        (select chatdate from chat where recieved_id = userid OR sent_id = userid ORDER BY chatdate DESC LIMIT 1) as chatdate,
        (select msg from chat where sent_id =userid OR recieved_id = userid ORDER BY id DESC LIMIT 1) as last_msg');
        $builder->join('users u', 'u.id = a.appointment_from', 'left');
        $builder->where('a.appointment_to', $userId);
        $builder->groupBy('a.appointment_from');
        $builder->orderBy('chatdate', 'DESC');
        return $builder->get()->getResultArray();   
    }
    /**
     * function to Get Doctors.
     * @param  int $userId
     * @return mixed
     * 
     */
    public function getDoctors($userId)
    {
        $builder = $this->db->table('appointments a');
        $profileimage = 'assets/img/user.png';
        $builder->select('u.id as userid,u.role,u.first_name,u.last_name,u.username,IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,(select chatdate from chat where recieved_id = "' . $userId . '" GROUP BY a.appointment_from ORDER BY chatdate DESC LIMIT 1) as chatdate,(select msg from chat where recieved_id = "' . $userId . '" GROUP BY a.appointment_from ORDER BY id DESC LIMIT 1) as lastchat');
        $builder->join('users u', 'u.id = a.appointment_to', 'left');
        $builder->where('a.appointment_from', $userId);
        $builder->groupBy('a.appointment_to');
        $builder->orderBy('chatdate', 'DESC');
        return $builder->get()->getResultArray();
    }
    public function getDoctorsData($userId)
    {
        $profileimage = 'assets/img/user.png';
        $builder = $this->db->table('appointments a');
        $builder->select('u.id as userid,u.role,u.first_name,u.last_name,u.username,
        IF(u.profileimage IS NULL or u.profileimage = "", "' . $profileimage . '", u.profileimage) as profileimage,
		(select chatdate from chat where (recieved_id = userid OR sent_id = userid) and (sent_id ="' . $userId . '" OR recieved_id = "' . $userId . '") ORDER BY chatdate DESC LIMIT 1) as chatdate,
		(select msg from chat where (sent_id=userid OR recieved_id = userid) and (sent_id ="' . $userId . '" OR recieved_id = "' . $userId . '") ORDER BY id DESC LIMIT 1) as last_msg');
        $builder->join('users u', 'u.id = a.appointment_to', 'left');
        $builder->where('a.appointment_from', $userId);        
        $builder->groupBy('a.appointment_to');
        $builder->orderBy('chatdate', 'DESC');
        return $builder->get()->getResultArray(); 
    }
    /**
     * function to  Get Latest Chat.
     * @param  mixed $selectedUser
     * @param  int $userId
     * @return mixed
     */
    public function getLatestChat($selectedUser, $userId)
    {
        //$this->updateChatCounts($selectedUser, $userId);
        $builder = $this->db->table('chat msg');
        $builder->select("DISTINCT CONCAT(sender.first_name,' ',sender.last_name) as senderName, sender.profileimage as senderImage, sender.id as sender_id, CONCAT(receiver.first_name,' ',receiver.last_name) as receiverName, receiver.profileimage as receiverImage, receiver.id as receiver_id, receiver.device_id as receiver_device_id, receiver.device_type as receiver_device_type, msg.msg, msg.chatdate, msg.id, msg.type, msg.file_name, msg.file_path, msg.time_zone, msg.id, sender.first_name as sender_from_firstusername, sender.last_name as sender_from_lastusername, receiver.first_name as reciever_first_username, receiver.last_name as reciever_last_username");
        $builder->join('users sender', 'msg.sent_id = sender.id', 'LEFT');
        $builder->join('users receiver', 'msg.recieved_id = receiver.id', 'LEFT');
        $builder->join('chat_deleted_details cd', 'cd.chat_id = msg.id', 'LEFT');
        //$builder->where('cd.can_view', $userId);
        $builder->where("((msg.recieved_id = $selectedUser AND msg.sent_id = $userId) OR (msg.recieved_id = $userId AND msg.sent_id = $selectedUser))");
        $builder->orderBy('msg.id', 'ASC');
        $result = $builder->get()->getResultArray();
        return $result;
    }

    public function updateChatCounts($selectedUser, $userId)
    {
        $builder = $this->db->table('chat msg');
        $builder->select("msg.id");        
        $builder->join('chat_deleted_details cd', 'cd.chat_id = msg.id', 'LEFT');
        $builder->where('cd.can_view', $userId);
        $builder->where("((msg.recieved_id = $selectedUser AND msg.sent_id = $userId) OR (msg.recieved_id = $userId AND msg.sent_id = $selectedUser))");
        $builder->orderBy('msg.id', 'DESC');
        $result = $builder->countAll();
        return $result;   
    }
    /**
     * function for  update  counts.
     * @param  mixed $selectedUser
     * @param  int $userId
     * @return mixed
     */
    public function update_counts($selectedUser, $userId)
    {
        $builder = $this->db->table('chat msg');
        $builder->select('msg.id');
        $builder->join('chat_deleted_details cd', 'cd.chat_id = msg.id', 'left');
        $builder->where('cd.can_view', $userId);
        $builder->where('((msg.recieved_id', $selectedUser);
        $builder->where('msg.sent_id', $userId, false);
        $builder->orWhere('msg.recieved_id', $userId);
        $builder->where('msg.sent_id', $selectedUser);
        $builder->orderBy('msg.id', 'DESC');
        $query = $builder->get()->getRowArray();;
        return $query;
    }
    /**
     * function to  Search Pharmacy New.
     * @param  string $page
     * @param  string $limit
     * @param  int $type
     * @param  mixed $userData
     * @return mixed
     */
    public function searchPharmacyNew($page, $limit, $type, $userData)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id as pharmacy_id, u.first_name,u.last_name,u.pharmacy_name,u.profileimage, u.mobileno');
        $builder->select('(CASE WHEN LENGTH(ud.address1) > 0 THEN ud.address1 ELSE " " END) as address1, (CASE WHEN LENGTH(ud.address2) > 0 THEN ud.address2 ELSE " " END) as address2, (CASE WHEN LENGTH(c.country) > 0 THEN c.country ELSE " " END) as country, (CASE WHEN LENGTH(c.phonecode) > 0 THEN c.phonecode ELSE " " END) as phonecode,(CASE WHEN LENGTH(s.statename) > 0 THEN s.statename ELSE " " END) as statename, (CASE WHEN LENGTH(ci.city) > 0 THEN ci.city ELSE " " END) as city, ud.postal_code');
        $builder->select('ps.home_delivery, ps.24hrsopen, ps.pharamcy_opens_at');
        $builder->join('users_details ud', 'u.id = ud.user_id', 'left');
        $builder->join('pharmacy_specifications ps', 'u.id = ps.pharmacy_id', 'left');
        $builder->join('state s', 's.id = ud.state', 'left');
        $builder->join('city ci', 'ci.id = ud.city', 'left');
        $builder->join('country c', 'c.countryid = ud.country', 'left');
        $builder->where('u.role', 5);
        $builder->where('u.status', 1);
        $builder->where('u.is_verified', 1);
        $builder->where('u.is_updated', 1);

        if (!empty($userData['city'])) {
            $builder->where("(ci.city = '" . $userData['city'] . "')");
        }
        if (!empty($userData['state'])) {
            $builder->where("(s.statename = '" . $userData['state'] . "')");
        }
        if (!empty($userData['country'])) {
            $builder->where('c.country', $userData['country']);
        }
        if (($userData['orderBy'] ?? '') == 'Latest') {
            $builder->orderBy('u.id', 'DESC');
        }
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($page) ? $page : '';
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * function to Read Pharmacy Productlist Search.
     * @param  mixed $pharmacy_id
     * @param  string $keywords
     * @param  string $category
     * @param  string $subcategory
     * @return mixed
     */
    public function readPharmacyProductlistSearch($pharmacy_id, $keywords, $category, $subcategory)
    {
        $builder = $this->db->table('products as p');
        $builder->select('p.id, p.user_id, p.name, p.slug, p.category, p.subcategory, p.unit_value, p.unit, p.price, p.sale_price, p.discount, p.description, p.short_description, p.manufactured_by, p.upload_image_url, p.upload_preview_image_url, p.created_date, p.status, c.category_name,s.subcategory_name,u.unit_name, "USD" as pharmacy_currency, "$" as pharmacy_currency_sign');
        $builder->join('product_categories as c', 'p.category = c.id', 'left');
        $builder->join('product_subcategories as s', 'p.subcategory = s.id', 'left');
        $builder->join('unit as u', 'p.unit = u.id', 'left');
        $builder->where("(p.status = '1' OR p.status = '2')");
        if (!empty($pharmacy_id)) {
            $builder->where("p.user_id", $pharmacy_id);
        }
        if (!empty($category)) {
            $builder->where('c.id', $category);
        }
        if (!empty($subcategory)) {
            $builder->where('s.id', $subcategory);
        }
        if (!empty($keywords)) {
            $builder->groupStart();
            $builder->like('c.category_name', $keywords);
            $builder->orLike('s.subcategory_name', $keywords);
            $builder->orLike('p.name', $keywords);
            $builder->groupEnd();
        }
        $builder->orderBy('p.id', 'DESC');
        return $builder->get()->getResultArray();
    }
    /**
     * function to All Pharmacy products.
     * @return mixed
     */
    public function allPharmacyProducts()
    {
        $builder = $this->db->table('products as p');
        $builder->select('p.id, p.user_id, p.name, p.slug, p.category, p.subcategory, p.unit_value, p.unit, p.price, p.sale_price, p.discount, p.description, p.short_description, p.manufactured_by, p.upload_image_url, p.upload_preview_image_url, p.created_date, p.status, c.category_name,s.subcategory_name,u.unit_name, "USD" as pharmacy_currency, "$" as pharmacy_currency_sign');
        $builder->join('product_categories as c', 'p.category = c.id', 'left');
        $builder->join('product_subcategories as s', 'p.subcategory = s.id', 'left');
        $builder->join('unit as u', 'p.unit = u.id', 'left');
        $builder->where("(p.status = '1' OR p.status = '2')");
        $builder->orderBy('p.id', 'DESC');
        return $builder->get()->getResultArray();
    }
    /**
     * function to Get Pharmacy Unit.
     * @return mixed
     */
    public function getPharmacyUnit()
    {
        $builder = $this->db->table('unit as u');
        $builder->select('u.id, u.unit_name, u.status');
        $builder->where('status', 1);
        return $builder->get()->getResultArray();
    }
    /**
     * function to  create product.
     * @param  mixed $tableName
     * @param  mixed $inputdata
     * @return mixed
     */
    public function createProduct($tableName, $inputdata)
    {
        if ($this->db->table($tableName)->insert($inputdata)) {
            return ($this->db->affectedRows() != 1) ? false : true;
        }
    }
    /**
     * function to  Get Pharmacy Category.
     * @return mixed
     */
    public function getPharmacyCategory()
    {
        $builder = $this->db->table('product_categories');
        $builder->select('id, category_name');
        $builder->where('status', 1);
        return $builder->get()->getResultArray();
    }
    /**
     * function to  Get Pharmacy Subcategory.
     * @param  int $catId
     * @return mixed
     */
    public function getPharmacySubcategory($catId)
    {
        $builder = $this->db->table('product_subcategories');
        $builder->select('id, subcategory_name');
        $builder->where('status', 1);
        $builder->where('category', $catId);
        return $builder->get()->getResultArray();
    }
    /**
     * function to  Get Pharmacy Product.
     * @param  mixed $subCatId
     * @param  mixed $pharmacyId
     * @return mixed
     * 
     */
    public function getPharmacyProduct($subCatId, $pharmacyId)
    {
        $builder = $this->db->table('products as p');
        $builder->select('p.id, p.user_id, p.name, p.slug, p.category, p.subcategory, p.unit_value, p.unit, p.price, p.sale_price, p.discount, p.description, p.short_description, p.manufactured_by, p.upload_image_url, p.upload_preview_image_url, p.created_date, p.status,(CASE WHEN LENGTH(p.discount) > 0 THEN p.discount ELSE "" END) as discount');
        $builder->where('status', 1);
        $builder->where('subcategory', $subCatId);
        $builder->where('user_id', $pharmacyId);
        return $builder->get()->getResultArray();
    }
    /**
     * function to  Get Selected Pharmacy Details.
     * @param  mixed $pharmacyId
     * @return mixed
     * 
     */
    public function getSelectedPharmacyDetails($pharmacyId = null)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id as pharmacy_id, u.first_name,u.last_name,u.pharmacy_name,u.profileimage, u.mobileno');
        $builder->select('ud.address1,ud.address2,c.country, c.phonecode,s.statename, ci.city, ud.postal_code');
        $builder->select('IF(ps.home_delivery IS NULL,"",ps.home_delivery) as home_delivery,
        IF(ps.24hrsopen IS NULL,"",ps.24hrsopen) as hrsopen,
        IF(ps.pharamcy_opens_at IS NULL,"",ps.pharamcy_opens_at) as pharamcy_opens_at,IF(c.country IS NULL,"",c.country) as countryname,
        IF(s.statename IS NULL,"",s.statename) as statename,
        IF(ci.city IS NULL,"",ci.city) as cityname');
        $builder->join('users_details ud', 'u.id = ud.user_id', 'left');
        $builder->join('pharmacy_specifications ps', 'u.id = ps.pharmacy_id', 'left');
        $builder->join('state s', 's.id = ud.state', 'left');
        $builder->join('city ci', 'ci.id = ud.city', 'left');
        $builder->join('country c', 'c.countryid = ud.country', 'left');
        $builder->where('u.id', $pharmacyId);
        $builder->where('u.role', 5);
        $builder->where('u.status', 1);
        return $builder->get()->getResultArray();
    }
    /**
     * function to Get Pharmacy Product List.
     * @param  mixed $pharmacyId
     * @return mixed
     */
    public function getPharmacyProductList($pharmacyId)
    {
        $builder = $this->db->table('products p');
        $builder->select('p.*,(CASE WHEN LENGTH(p.	discount) > 0 THEN p.discount ELSE " " END) as discount,c.category_name,s.subcategory_name,u.unit_name, "USD" as pharmacy_currency, "$" as pharmacy_currency_sign');
        $builder->join('product_categories as c', 'p.category = c.id', 'left');
        $builder->join('product_subcategories as s', 'p.subcategory = s.id', 'left');
        $builder->join('unit as u', 'p.unit = u.id', 'left');
        $builder->where("(p.status = '1' OR p.status = '2')");
        $builder->where("p.user_id", $pharmacyId);
        $builder->orderBy('p.id', 'DESC');
        $query = $builder->get()->getResultArray();
        return $query;
    }

    /**
     * function to Get Single Pharmacy Product.
     * @param  int $id
     * @return mixed
     */
    public function getSinglePharmacyProduct($id)
    {
        $builder = $this->db->table('products p');
        $builder->select('p.*,(CASE WHEN LENGTH(p.	discount) > 0 THEN p.discount ELSE " " END) as discount,c.category_name,s.subcategory_name,u.unit_name');
        $builder->join('product_categories as c', 'p.category = c.id', 'left');
        $builder->join('product_subcategories as s', 'p.subcategory = s.id', 'left');
        $builder->join('unit as u', 'p.unit = u.id', 'left');
        $builder->where("(p.status = '1' OR p.status = '2')");
        $builder->where("p.id", $id);
        $query = $builder->get()->getRowArray();
        return $query;
    }
    /**
     * function to User Order List Based Order ID.
     * @param  mixed $orderUserDetailsId
     * @return mixed
     */
    public function userOrderListBasedOrderID($orderUserDetailsId)
    {
        $builder = $this->db->table('order_user_details as od');
        $builder->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name, us.mobileno as user_mobileno, SUM(o.quantity) as qty, oc.country as phar_countryname, os.statename as phar_statename, oci.city as phar_cityname, ud.address1 as user_address1, ud.address2 as user_address2, c.country as user_countryname, s.statename as user_statename, ci.city as user_cityname, ud.postal_code as user_postal_code, payments.transcation_charge, payments.tax_amount, 0 as discount_amount, payments.invoice_no, payments.tax');
        $builder->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
        $builder->join('country oc', 'od.country = oc.countryid', 'left');
        $builder->join('state os', 'od.state = os.id', 'left');
        $builder->join('city oci', 'od.city = oci.id', 'left');
        $builder->join('users as us', 'us.id = od.pharmacy_id', 'left');
        $builder->join('users_details as ud', 'us.id = ud.user_id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('payments', 'payments.id = o.payment_id', 'left'); //New
        $builder->where('od.order_user_details_id', $orderUserDetailsId);
        $builder->groupBy('od.order_user_details_id');
        return $builder->get()->getResultArray();
    }
    /**
     * function to Order Details.
     * @param  mixed $orderId 
     * @return mixed
     */
    public function orderDetails($orderId)
    {
        $builder = $this->db->table('order_user_details as od');
        $builder->select('p.*,o.*');
        $builder->join('orders as o', 'o.user_order_id = od.order_user_details_id', 'left');
        $builder->join('products as p', 'p.id = o.product_id', 'left');
        $builder->where('od.order_user_details_id', $orderId);
        return $builder->get()->getResultArray();
    }
    /**
     * function to Pharmacy Invoice Details.
     * @param  int $id 
     * @return mixed
     */
    public function pharmacyInvoiceDetails($id)
    {
        $builder = $this->db->table('payments p');
        $builder->select('p.per_hour_charge,p.currency_code,p.transcation_charge,p.tax_amount,p.total_amount,p.id,p.invoice_no,p.payment_date,dd.user_id, d.first_name,d.last_name,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,us.first_name as patient_first_name,us.last_name as patient_last_name,CONCAT(us.first_name," ", us.last_name) as patient_name,us.profileimage as patient_profileimage,us.id as patient_id, dc.country as phar_countryname,ds.statename as phar_statename,dci.city as phar_cityname,dd.address1 as address1,(CASE WHEN LENGTH(pc.country) > 0 THEN pc.country ELSE "" END) as user_countryname,
        (CASE WHEN LENGTH(ps.statename) > 0 THEN ps.statename ELSE "" END) as user_statename,(CASE WHEN LENGTH(pci.city) > 0 THEN pci.city ELSE "" END) as user_cityname, (CASE WHEN LENGTH(dd.address2) > 0 THEN dd.address2 ELSE "" END) as address2, pd.address1 as user_address1, (CASE WHEN LENGTH(pd.address2) > 0 THEN pd.address2 ELSE "" END) as user_address2,d.role, p.invoice_no,  p.payment_date, (CASE WHEN LENGTH(dd.postal_code) > 0 THEN dd.postal_code ELSE " " END) as doctor_postal_code, (CASE WHEN LENGTH(pd.postal_code) > 0 THEN pd.postal_code ELSE " " END) as patient_postal_code, p.user_id as payment_patient_id, p.doctor_id as payment_doctor_id, d.role, p.order_id, p.payment_type');
        $builder->join('users d', 'd.id = p.doctor_id', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->join('users us', 'us.id = p.user_id', 'left');
        $builder->join('users_details pd', 'pd.user_id = us.id', 'left');
        $builder->join('country dc', 'dd.country = dc.countryid', 'left');
        $builder->join('state ds', 'dd.state = ds.id', 'left');
        $builder->join('city dci', 'dd.city = dci.id', 'left');
        $builder->join('country pc', 'pd.country = pc.countryid', 'left');
        $builder->join('state ps', 'pd.state = ps.id', 'left');
        $builder->join('city pci', 'pd.city = pci.id', 'left');
        $builder->where('p.id', $id);
        $builder->where('p.payment_status', 1);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * function to Get Patient Acclist.
     * @param  int $userId
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @return mixed
     */
    public function getPatientAcclist($userId, $pages, $limits, $role, $type = 1)
    {
        if ($role == '5') {
            $builder = $this->db->table('pharmacy_payments p');
        } else {
            $builder = $this->db->table('payments p');
        }
        $builder->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.first_name,d.last_name,d.profileimage as doctor_profileimage,d.id as doctor_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count,d.role');
        $builder->join('users d', 'd.id = p.doctor_id', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->where('p.user_id', $userId);
        $builder->where('p.payment_status', 1);
        $builder->orderBy('p.id', 'desc');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            $query = $builder->get();
            return $query->getResultArray();
        }
    }
    /**
     * function to Get Doctor Acclist.
     * @param  int $userId
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @return mixed
     */
    public function getDoctorAcclist($userId, $pages, $limits, $role, $type = 1)
    {
        if ($role == 5) {
            $builder = $this->db->table('pharmacy_payments p');
        } else {
            $builder = $this->db->table('payments p');
        }
        $builder->select('p.*,pi.first_name,pi.last_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
        $builder->join('users pi', 'pi.id = p.user_id', 'left');
        $builder->join('users_details pd', 'pd.user_id = pi.id', 'left');
        $builder->where('p.doctor_id', $userId);
        $builder->where('p.payment_status', 1);
        $builder->orderBy('p.id', 'desc');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            $query = $builder->get();
            return $query->getResultArray();
        }
    }
    /**
     * function to Get Product Details.
     * @param  int $id
     * @return mixed
     */
    public function productDetails($id)
    {
        $builder = $this->db->table('products p');
        $builder->select('p.status,p.id');
        $builder->where('p.status !=', 0);
        $builder->where('p.id', $id);
        $query = $builder->get()->getRowArray();
        return $query;
    }
    /**
     * function to Get Patient Reflist.
     * @param  int $userId
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @return mixed
     */
    public function getPatientReflist($userId, $pages, $limits, $role, $type = 1)
    {
        if ($role == 5) {
            $builder = $this->db->table('pharmacy_payments p');
        } else {
            $builder = $this->db->table('payments p');
        }
        $builder->select('p.*,pi.first_name,pi.last_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
        $builder->join('users pi', 'pi.id = p.user_id', 'left');
        $builder->join('users_details pd', 'pd.user_id = pi.id', 'left');
        $builder->where('p.doctor_id', $userId);
        $builder->where('p.payment_status', 1);
        $builder->where('p.request_status', 6);
        $builder->orderBy('p.id', 'desc');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            $query = $builder->get();
            return $query->getResultArray();
        }
    }
    /**
     * function to Get Doctor Reflist.
     * @param  int $userId
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @return mixed
     */
    public function getDoctorReflist($userId, $pages, $limits, $role, $type = 1)
    {
        if ($role == 5) {
            $builder = $this->db->table('pharmacy_payments p');
        } else {
            $builder = $this->db->table('payments p');
        }
        $builder = $this->db->table('payments p');
        $builder->select('p.*,CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,d.id as doctor_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count,d.role');
        $builder->join('users d', 'd.id = p.doctor_id', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->where('p.user_id', $userId);
        $builder->where('p.payment_status', 1);
        $builder->where('p.request_status', 1);
        $builder->orderBy('p.id', 'desc');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            $query = $builder->get();
            return $query->getResultArray();
        }
    }
    /**
     * function to Lab Lists.
     * @param  string $pages
     * @param  string $limits
     * @param  string $type
     * @param  mixed $userData
     * @return mixed
     */
    public function labLists($pages, $limits, $userData, $type = '')
    {
        $builder = $this->db->table('users u');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role', '4');
        $builder->where('u.status', '1');
        $builder->where('u.is_verified', '1');
        $builder->where('u.is_updated', '1');
        if (!empty($userData['city'])) {
            $builder->where("(ud.city = '" . $userData['city'] . "')");
        }
        if (!empty($userData['state'])) {
            $builder->where("(ud.state = '" . $userData['state'] . "')");
        }
        if (!empty($userData['country'])) {
            $builder->where('ud.country', $userData['country']);
        }
        if (isset($userData['username']) && !empty(libsodiumEncrypt($userData['username']))) {
            $builder->where('u.username', libsodiumEncrypt($userData['username']));
        }
        if (!empty($userData['keywords'])) {
            $builder->groupStart();
            $builder->like('u.first_name', libsodiumEncrypt($userData['keywords']));
            $builder->orLike('u.last_name', libsodiumEncrypt($userData['keywords']));
            $builder->orLike('sp.specialization', libsodiumEncrypt($userData['keywords']));
            $builder->groupEnd();
        }
        $builder->groupBy('ud.id');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->orderBy('u.id', 'DESC');
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }

    /**
     * function to Get Schedule Timings.
     * @param  string $id
     * @param  mixed $dayId
     * @return mixed
     */
    public function getScheduleTimings($id, $dayId)
    {
        $builder = $this->db->table('schedule_timings st');
        $builder->where('st.user_id', $id);
        $builder->where('st.day_id', $dayId);
        $query = $builder->get()->getResultArray();
        return $query;
    }

    /**
     * function to Appointments History.
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @param  mixed $patientId
     * @param  int $userId
     * @param  int $role
     * @param  mixed $userData
     * @return mixed
     */
    public function appointmentsHistory($pages, $limits, $patientId, $userId, $role, $userData, $type = 1)
    {
        $builder = $this->db->table('appointments a');
        $builder->select('a.appointment_to,a.payment_method,a.time_zone,a.appointment_date,a.from_date_time,a.to_date_time,a.created_date,a.id,a.appointment_from,a.type,a.approved,u.first_name,u.last_name,u.username,u.profileimage,p.per_hour_charge,u.role,s.specialization');
        $builder->join('payments p', 'p.id = a.payment_id', 'left');
        $builder->join('users u', 'u.id = a.appointment_to', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('specialization s', 'ud.specialization = s.id', 'left');
        $builder->where('a.appointment_from', $patientId);
        if ($role == 1) {
            $builder->where('a.appointment_to', $userId);
        }
        if (!empty($userData['payment_method'])) {
            $builder->where('a.payment_method', $userData['payment_method']);
        } {
            $builder->groupStart();
            $builder->where('a.payment_method', 1);
            $builder->orWhere('a.payment_method', 2);
            $builder->orWhere('a.payment_method', 3);
            $builder->orWhere('a.payment_method', 4);
            $builder->orWhere('a.payment_method', 'Paypal');
            $builder->orWhere('a.payment_method', 'Online');
            $builder->groupEnd();
        }
        $builder->groupBy('a.id');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->orderBy('a.from_date_time', 'DESC');
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }

    /**
     * function to My Doctor Lists.
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @param  int $userId
     * @return mixed
     */
    public function myDoctorLists($pages, $limits, $userId, $type = 1)
    {
        $builder = $this->db->table('appointments a');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,(CASE WHEN LENGTH(sp.specialization_img) > 0 THEN sp.specialization_img ELSE " " END) as specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select IFNULL(ROUND(AVG(rating)),0) from rating_reviews where doctor_id=u.id) as rating_value');
        $builder->join('users u', 'a.appointment_to = u.id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role', '1');
        $builder->where('a.appointment_from', $userId);
        $builder->groupBy('a.appointment_to');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }

    /**
     * function to Get Pharmacy Account List.
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @param  int $userId
     * @return mixed
     */
    public function getPharmacyAccountList($userId, $pages, $limits, $type = 1)
    {
        $builder = $this->db->table('orders p');
        $builder->select('p.id,p.status as status,p.ordered_at as payment_date,p.subtotal as price,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,(CASE WHEN LENGTH(pi.id) > 0 THEN pi.id ELSE " " END) as patient_id,pd.currency_code as currency_code');
        $builder->join('users pi', 'pi.id = p.user_id', 'left');
        $builder->join('users_details pd', 'pd.user_id = pi.id', 'left');
        $builder->where('p.pharmacy_id', $userId);
        $builder->where('p.status', 1);
        $builder->where('transaction_status !=', 'Pay on arrive');
        $builder->orderBy('p.id', 'desc');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }
    /**
     * function to Hospital Doctor List.
     * @param  string $pages
     * @param  string $limits
     * @param  int $type
     * @param  int $userId
     * @return mixed
     */
    public function hospitalDoctorList($userId, $pages, $limits, $type = 1)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id as id,CONCAT(u.first_name," ",u.last_name) as name,u.first_name,u.last_name ,u.email,u.country_code, u.mobileno as mobile,u.profileimage as profile,u.username as  username,u.is_verified,u.is_updated');
        $builder->join('users_details ud', 'u.id = ud.user_id', 'left');
        $builder->where('u.status', 1);
        $builder->where('u.hospital_id', $userId);
        $builder->orderBy('u.id', 'DESC');
        if ($type == 2) {
            return $builder->countAllResults();
        } else {
            $page = !empty($pages) ? $pages : '';
            $limit = $limits;
            if ($page >= 1) {
                $page = (int)$page - 1;
            }
            $page = (int)$page * (int)$limit;
            $builder->limit($limit, $page);
            $query = $builder->get()->getResultArray();
            return $query;
        }
    }

    /**
     * function to get Token.
     * 
     * @param  int $length
     * @param  int $userId
     * @return mixed
     */
    public function getToken($length, $userId)
    {
        $token = $userId;
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->cryptoRandSecure(0, $max - 1)];
        }
        return $token;
    }

    /**
     * function to get Token.
     * 
     * @param  int $min
     * @param  int $max
     * @return mixed
     */
    function cryptoRandSecure($min, $max)
    {

        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
    /**
     * function To Is Valid Loginwithotp.
     * @param  mixed $deviceId
     * @param  mixed $deviceType
     * @param  mixed $mobile
     * @return mixed
     */
    public function isValidLoginWithOtp($deviceId, $deviceType, $mobile)
    {
        $builder = $this->db->table('users');
        $profileimage = 'assets/img/user.png';
        $builder->select('id,email,first_name,last_name,username,mobileno,role,token,status,IF(profileimage IS NULL or profileimage = "", "' . $profileimage . '", profileimage) as profileimage');
        $builder->where("mobileno", $mobile);
        $result = $builder->get()->getRowArray();
        if (!empty($result)) {
            $userId = $result['id'];
            $token = $this->getToken(14, $userId);
            $result['token'] = $token;
            $this->updateData('users', ['id' => $userId], ['token' => $token, 'device_id' => $deviceId, 'device_type' => $deviceType], false);
        }
        return $result;
    }

    /**
     * function To Check Otp Details.
     * @param  mixed  $inputs
     * @return mixed
     */
    public function CheckOtpDetails($inputs = '')
    {
        $builder = $this->db->table('otp_history');
        $mobile = $inputs['mobileno'];
        $otp = $inputs['otp'];
        $builder->where('mobileno', $mobile);
        $builder->where('otpno', $otp);
        $builder->where('status=0');
        $builder->orderBy('otp_history.id', 'DESC');
        $builder->limit(1);
        return $builder->countAllResults();
    }
    /**
     * function to Get Chat Users
     * @param  int $userId
     * @return mixed
     */
    public function getChatUsers($userId, $chatId)
    {
        $builder = $this->db->table('chat');
        $builder->select('*');
        $builder->where("(recieved_id = '" . $chatId . "' AND sent_id = '" . $userId . "') OR (recieved_id = '" . $userId . "' AND sent_id = '" . $chatId . "')");
        $builder->orderBy('id', 'DESC');
        $builder->limit(1);
        return $builder->get()->getRowArray();
    }
    /**
     * function to Get Balance Pharmacy
     * @param  int $userId
     * @return mixed
     */
    public function getBalancePharmacy($userId)
    {
        $builder = $this->db->table('orders o');
        $builder->select('o.id,o.status as status,o.ordered_at as payment_date,Sum(o.subtotal) as price,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,pd.currency_code as currency_code');
        $builder->join('users pi', 'pi.id = o.user_id', 'left');
        $builder->join('users_details pd', 'pd.user_id = pi.id', 'left');
        $builder->where('o.pharmacy_id', $userId);
        $builder->where('o.status', 1);
        $builder->where('transaction_status !=', 'Pay on arrive');
        $result = $builder->get()->getResultArray();
        $balance = 0;
        if (!empty($result)) {
            foreach ($result as $rows) {
                $amount = $rows['price'];
                $commission = !empty(settings("commission")) ? settings("commission") : "0";
                $commissionCharge = ($amount * ($commission / 100));
                $balanceTemp = $amount - $commissionCharge;
                $userCurrency = get_user_currency();
                $userCurrencyCode = $userCurrency['user_currency_code'];
                $orgAmount = get_doccure_currency($balanceTemp, $rows['currency_code'], $userCurrencyCode);
                $balance += $orgAmount;
            }
        }
        return $balance;
    }
    /**
     * function to Get Patient Balance
     * @param  int $userId
     * @return mixed
     */
    public function getPatientBalance($userId)
    {
        $builder = $this->db->table('payments p');
        $builder->select('*');
        $builder->where('p.user_id', $userId);
        $builder->where('p.payment_status', 1);
        $builder->where('p.request_status', 7);
        $result = $builder->get()->getResultArray();
        $balance = 0;
        if (!empty($result)) {
            foreach ($result as $rows) {
                $taxAmount = $rows['tax_amount'] + $rows['transcation_charge'];
                $amount = ($rows['total_amount']) - ($taxAmount);
                $commission = !empty(settings("commission")) ? settings("commission") : "0";
                $commissionCharge = ($amount * ($commission / 100));
                $balanceTemp = $amount;
                $userCurrency = get_user_currency();
                $userCurrencyCode = $userCurrency['user_currency_code'];
                $userCurrencyRate = $userCurrency['user_currency_rate'];
                $currencyOption = (!empty($userCurrencyCode)) ? $userCurrencyCode : default_currency_code();
                $rateSymbol = currency_code_sign($currencyOption);
                $orgAmount = get_doccure_currency($balanceTemp, $rows['currency_code'], $userCurrencyCode);
                $balance += $orgAmount;
            }
        }
        return $balance;
    }
    /**
     * function to Get Balance
     * @param  int $userId
     * @return mixed
     */
    public function getBalance($userId)
    {
        $builder = $this->db->table('payments p');
        $builder->select('p.*,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
        $builder->where('p.doctor_id', $userId);
        $builder->where('p.payment_status', 1);
        $builder->where('p.request_status', 2);
        $result = $builder->get()->getResultArray();
        $balance = 0;
        if (!empty($result)) {
            foreach ($result as $rows) {
                $taxAmount = $rows['tax_amount'] + $rows['transcation_charge'];
                $amount = ($rows['total_amount']) - ($taxAmount);
                $commission = !empty(settings("commission")) ? settings("commission") : "0";
                $commissionCharge = ($amount * ($commission / 100));
                $balanceTemp = $amount - $commissionCharge;
                $userCurrency = get_user_currency();
                $userCurrencyCode = $userCurrency['user_currency_code'];
                $userCurrencyRate = $userCurrency['user_currency_rate'];
                $currencyOption = (!empty($userCurrencyCode)) ? $userCurrencyCode : default_currency_code();
                $rateSymbol = currency_code_sign($currencyOption);
                $orgAmount = get_doccure_currency($balanceTemp, $rows['currency_code'], $userCurrencyCode);
                $balance += $orgAmount;
            }
        }
        return $balance;
    }
    /**
     * function to Get Requested
     * @param  int $userId
     * @return mixed
     */
    public function getRequested($userId)
    {
        $builder = $this->db->table('payment_request');
        $builder->select('*');
        $builder->where('user_id', $userId);
        $builder->where('status', 1);
        $result = $builder->get()->getResultArray();
        $reuested = 0;
        if (!empty($result)) {
            foreach ($result as $rows) {
                $amount = $rows['request_amount'];
                $userCurrency = get_user_currency();
                $userCurrencyCode = $userCurrency['user_currency_code'];
                $userCurrencyRate = $userCurrency['user_currency_rate'];
                $currencyOption = (!empty($userCurrencyCode)) ? $userCurrencyCode : default_currency_code();
                $rateSymbol = currency_code_sign($currencyOption);
                $orgAmount = get_doccure_currency($amount, $rows['currency_code'], $userCurrencyCode);
                $reuested += $orgAmount;
            }
        }
        return $reuested;
    }
    /**
     * function to Get Earned
     * @param  int $userId
     * @return mixed
     */
    public function getEarned($userId)
    {
        $builder = $this->db->table('payment_request');
        $builder->select('*');
        $builder->where('user_id', $userId);
        $builder->where('status', 2);
        $result = $builder->get()->getResultArray();
        $reuested = 0;
        if (!empty($result)) {
            foreach ($result as $rows) {
                $amount = $rows['request_amount'];
                $userCurrency = get_user_currency();
                $userCurrencyCode = $userCurrency['user_currency_code'];
                $userCurrencyRate = $userCurrency['user_currency_rate'];
                $currencyOption = (!empty($userCurrencyCode)) ? $userCurrencyCode : default_currency_code();
                $rateSymbol = currency_code_sign($currencyOption);
                $orgAmount = get_doccure_currency($amount, $rows['currency_code'], $userCurrencyCode);
                $reuested += $orgAmount;
            }
        }
        return $reuested;
    }
    /**
     * function to Get Account Details
     * @param  int $userId
     * @return mixed
     */
    public function getAccountDetails($userId)
    {
        $builder = $this->db->table('account_details ad');
        $builder->where('ad.user_id', $userId);
        $query = $builder->get()->getResultArray();
        return $query;
    }
    /**
     * function to Get Balance Lab
     * @param  int $userId
     * @return mixed
     */
    public function getBalanceLab($userId)
    {
        $builder = $this->db->table('lab_payments p');
        $builder->select('p.*,CONCAT(pi.first_name," ", pi.last_name) as patient_name,pi.profileimage as patient_profileimage,pi.id as patient_id,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
        $builder->join('users pi', 'pi.id = p.patient_id', 'left');
        $builder->join('users_details pd', 'pd.user_id = pi.id', 'left');
        $builder->where('p.lab_id', $userId);
        $builder->where('p.status', 1);
        $builder->where('payment_type !=', 'Pay on arrive');
        $result = $builder->get()->getResultArray();
        $balance = 0;
        if (!empty($result)) {
            foreach ($result as $rows) {
                $taxAmount = $rows['tax_amount'] + $rows['transcation_charge'];
                $amount = ($rows['total_amount']) - ($taxAmount);
                $commission = !empty(settings("commission")) ? settings("commission") : "0";
                $commissionCharge = ($amount * ($commission / 100));
                $balanceTemp = $amount - $commissionCharge;
                $userCurrency = get_user_currency();
                $userCurrencyCode = $userCurrency['user_currency_code'];
                $userCurrencyRate = $userCurrency['user_currency_rate'];
                $currencyOption = (!empty($userCurrencyCode)) ? $userCurrencyCode : default_currency_code();
                $rateSymbol = currency_code_sign($currencyOption);
                $orgAmount = get_doccure_currency($balanceTemp, $rows['currency_code'], $userCurrencyCode);
                $balance += (float) $orgAmount;
            }
        }
        return $balance;
    }

     /**
     * function to Get Lab Payment Details
     * @param  int $orderId
     * @return mixed
     */
    public function getLabData($orderId)
    {
        $builder = $this->db->table('lab_payments lp');
        $builder->select('booking_ids');
        $builder->where('lp.order_id', $orderId);
        $query = $builder->get()->getRowArray();
        return $query;
    }

    /**
     * function to Get Lab Payment Details
     * @param  int $paymentId
     * @param  int $UserId
     * @return mixed
     */
    public function getPharmacyAppt($paymentId, $UserId) {
        $builder = $this->db->table('appointments a');
        $builder->select('a.*, d.role');
        $builder->join('users d', 'd.id = a.appointment_to', 'left');
        $builder->where('a.payment_id', $paymentId);
        $builder->where('a.appointment_from', $UserId);
        $query = $builder->get()->getResultArray();
        return $query;
    }

    /**
     * function to Get Lab Payment Details By Id
     * @param  int $id
     * @return mixed
     */
    public function getLabDataById($id) {
        $builder = $this->db->table('lab_tests');
        $builder->select('lab_test_name, amount, currency_code');
        $builder->where('id', $id);
        $query = $builder->get()->getRowArray();
        return $query;        
    }
}
