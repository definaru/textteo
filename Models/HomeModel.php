<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class HomeModel extends Model
{
    protected $db;
    protected array $blog_column_search = array('p.title', 'c.category_name', 's.subcategory_name', 'd.first_name', 'd.last_name');

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * Get Doctors
     * 
     * 
     * @return mixed
     */
    public function getDoctors()
    {
        $builder = $this->db->table('users');
        $builder->select('users.first_name,users.last_name,users.email,users.username,users.mobileno,users.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=users.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=users.id) as rating_value');
        $builder->join('users_details ud', 'ud.user_id = users.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('users.role', '1');
        $builder->Where('users.hospital_id', 0);
        $builder->where('users.status', '1');
        $builder->where('users.is_verified', '1');
        $builder->where('users.is_updated', '1');
        $builder->orderBy('users.id', 'DESC');
        $builder->groupBy('users.id, users.first_name, users.last_name, users.email, users.username, users.mobileno, users.profileimage, ud.id, c.country, s.statename, ci.city, sp.specialization, sp.specialization_img');
        return $result = $builder->get()->getResultArray();
    }
    /**
     * Get Blogs
     * 
     * 
     * @return mixed
     */
    public function getBlogs()
    {
        $builder = $this->db->table('posts');
        $builder->select('posts.*,IF(posts.post_by="Admin",a.profileimage, d.profileimage) as profileimage,IF(posts.post_by="Admin","Admin",d.first_name) as name,d.username,c.category_name,s.subcategory_name');
        $builder->join('users d', 'posts.user_id = d.id', 'left');
        $builder->join('categories c', 'posts.category = c.id', 'left');
        $builder->join('subcategories s', 'posts.subcategory = s.id', 'left');
        $builder->join('tags t', 'posts.id = t.post_id', 'left');
        $builder->join('administrators a', '1 = a.id', 'left');
        $builder->where('posts.status', '1');
        $builder->where('posts.is_verified', '1');
        $builder->where('posts.is_viewed', '1');
        $where = "posts.slug!=''";
        $builder->where($where);
        $builder->orderBy('rand()');
        $builder->groupBy('posts.id');
        $builder->limit(4);
        return $builder->get()->getResultArray();
    }
    /**
     * Get Specialization
     * 
     * 
     * @return mixed
     */
    public function getSpecialization()
    {
        $builder = $this->db->table('specialization');
        $builder->where('status', 1);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();
        return $query->getResultArray();
    }


    /**
     * COMMON FOR DATA INSERT
     * 
     * @param string $tableName
     * @param mixed $data
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
     * COMMON FOR DATA UPDATE
     * 
     * @param string $tableName
     * @param mixed $data
     * @param mixed $whereData
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
     * COMMON FOR DATA DELETE
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
     * CHECK TABLE VALUE EXIST OR NOT
     * 
     * @param string $tblNme
     * @param mixed $whereData
     * @param mixed $colNme
     * @param mixed $whereNotIn
     * @return mixed
     */
    public function checkTblDataExist($tblNme, $whereData, $colNme, $whereNotIn = [])
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
     * GET ROW COUNT
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
     * GET SINGLE ROW DATA FORM TABLE
     * 
     * @param string $tblNme
     * @param mixed $whereData
     * @param mixed $colNme
     * @param mixed $orderBy
     * @return mixed
     */
    public function getTblRowOfData($tblNme, $whereData, $colNme, $orderBy = [])
    {
        $builder = $this->db->table($tblNme);
        $builder->select($colNme);
        $builder->where($whereData);
        if ($orderBy) {
            $builder->OrderBy(array_keys($orderBy)[0], array_values($orderBy)[0]);
        }
        $query =  $builder->get()->getRowArray();
        return $query;
    }

    /**
     * GET ALL ROW FROM TABLE
     * 
     * @param string $tblNme
     * @param mixed $whereData
     * @param mixed $colNme
     * @return mixed
     */
    public function getTblResultOfData($tblNme, $whereData, $colNme)
    {
        $builder = $this->db->table($tblNme);
        $builder->select($colNme);
        $builder->where($whereData);
        $query =  $builder->get()->getResultArray();
        return $query;
    }

    /**
     * GET EXCUTED QUERY
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
     * DataTable Filter
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
            $builder->like('username', $searchValue)
                ->orLike('email', $searchValue);
        }
        return $builder->countAllResults();
    }


    /**
     * SEARCH DOCTOR LIST
     * 
     * 
     * @param mixed $page
     * @param mixed $limit
     * @param mixed $type
     * @return mixed
     */
    public function searchDoctor($page, $limit, $type)
    {
        $builder = $this->db->table("users");
        $builder->select('users.first_name,users.last_name,users.email,users.username,users.mobileno,users.profileimage,users.hospital_id,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=users.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=users.id) as rating_value,  users.role');
        // $builder->from('users u');
        $builder->join('users_details ud', 'ud.user_id = users.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        // Shenbagam
        // if (!empty($_POST['role'])) {
        //     $builder->Where('users.role', $_POST['role']);
        // } else {
        //     if (!empty($_POST['keywords'])) {
        //         $builder->WhereIn('users.role', [1, 6]);
        //     } else {
        //         $builder->Where('users.role', 1);
        //     }
        // }
        $builder->Where('users.role', 1);
        $builder->Where('users.status', '1');
        $builder->Where('users.is_verified', '1');
        $builder->Where('users.is_updated', '1');
        $builder->Where("ud.id is  NOT NULL"); //Shenbagam
        //$builder->Where('users.hospital_id !=', 0);



        // if ($_POST['role'] == 6) {
        //     if (!empty($_POST['get_id']) && empty($_POST['cities']) && empty($_POST['city']) && empty($_POST['state']) && empty($_POST['country']) && empty($_POST['postal_code']) && empty($_POST['s_city']) && empty($_POST['s_state']) && empty($_POST['s_country']) && empty($_POST['specialization']) && empty($_POST['keywords']) && empty($_POST['citys']) && empty($_POST['gender'])) {
        //         $builder->Where('users.id', $_POST['get_id']);
        //     }
        // }

        if (!empty($_POST['postal_code'])) {
            $builder->Where("(ud.postal_code = '" . $_POST['postal_code'] . "')");
        }

        if (isset($_POST['s_lat'])) {
            if (!empty($_POST['city'])) {
                $builder->Where('ci.city', $_POST['city']);
            }
            if (!empty($_POST['state'])) {
                $builder->Where('s.statename', $_POST['state']);
            }
            if (!empty($_POST['country'])) {
                $builder->Where('c.country', $_POST['country']);
            }
        } else {
            if (!empty($_POST['city'])) {
                $builder->Where('ud.city', $_POST['city']);
            }
            if (!empty($_POST['state'])) {
                $builder->Where('ud.state', $_POST['state']);
            }
            if (!empty($_POST['country'])) {
                $builder->Where('ud.country', $_POST['country']);
            }
        }
        // if (!empty($_POST['s_city'])) {
        //     $cityName = $_POST['s_city'];
        //     $builder->like('ci.city', $cityName);
        // }

        // if (!empty($_POST['s_state'])) {
        //     $builder->like("(s.statename = '" . $_POST['state'] . "' OR ci.city = '" . $_POST['state'] . "')");
        // }

        // if (!empty($_POST['s_country'])) {
        //     $builder->like('c.country', $_POST['country']);
        // }


        if (!empty($_POST['specialization'])) {
            $spec_array = explode(",", $_POST['specialization']);
            $spec_array = array_filter($spec_array);

            $builder->WhereIn('ud.specialization', $spec_array);
        }

        if (!empty($_POST['keywords'])) {
            $builder->GroupStart();
            // $builder->Like('CONCAT( users.first_name, " ", users.last_name)', $_POST['keywords'], 'after');
            $builder->Like('sp.specialization', libsodiumEncrypt($_POST['keywords']), 'after');
            $builder->orLike('users.first_name', libsodiumEncrypt($_POST['keywords']), 'after');
            $builder->orLike('users.last_name', libsodiumEncrypt($_POST['keywords']), 'after');
            $builder->orLike('ud.clinicname', libsodiumEncrypt($_POST['keywords']), 'after');
            $builder->GroupEnd();
        }



        if (!empty($_POST['s_location'])) {
            $builder->GroupStart();
            $citys = $_POST['s_location'];
            $citys = str_replace(',', '', $citys);
            $explode = (explode(' ', $citys));
            foreach ($explode as $val) {
                // $builder->orLike('c.country', $val, 'after');
                $builder->orLike('ci.city', $val, 'after');
                $builder->orLike('s.statename', $val, 'after');
            }
            $builder->GroupEnd();
        }

        if (!empty($_POST['citys'])) {
            $builder->GroupStart();
            $citys = $_POST['citys'];
            $citys = str_replace(',', '', $citys);
            $explode = (explode(' ', $citys));
            foreach ($explode as $val) {
                $builder->Like('c.country', $val, 'after');
                $builder->orLike('ci.city', $val, 'after');
                $builder->orLike('s.statename', $val, 'after');
            }
            $builder->GroupEnd();
        }

        if (!empty($_POST['gender'])) {
            $gender_array = explode(",", $_POST['gender']);
            $gender_array = array_filter($gender_array);
            $builder->WhereIn('ud.gender', $gender_array);
        }
        if (isset($_POST['order_by']) && $_POST['order_by'] == 'Free') {
            $builder->Where('ud.price_type', 'Free');
        }
        if (isset($_POST['order_by']) && $_POST['order_by'] == 'Clinic') {
            $builder->Where('sub.payment_plan!=', '3');
        }
        if (isset($_POST['order_by']) && $_POST['order_by'] == 'Online') {
            $builder->Where('sub.payment_plan', '3');
        }
        $builder->GroupBy('ud.id');

        if (isset($_POST['order_by']) && $_POST['order_by'] == 'Rating') {
            $builder->OrderBy('rating_value', 'DESC');
        }
        if (isset($_POST['order_by']) && $_POST['order_by'] == 'Price') {
            $builder->Where('ud.price_type !=', 'Free');
            $builder->OrderBy('ud.amount', 'DESC');
        }

        if (isset($_POST['order_by']) && $_POST['order_by'] == 'Latest') {
            $builder->OrderBy('users.id', 'DESC');
        }

        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($page) ? $page : '';
            if ($page >= 1) {
                $page = $page - 1;
            }
            $page = ($page * $limit);
            $builder->limit($limit, $page);
            $return = $builder->get()->getResultArray();
            // echo $this->db->getLastQuery();
            return $return;
        }
    }
    /**
     * Home Page Doctor Autocomplete
     * 
     * @param mixed $search_keywords
     * @return mixed
     */
    public function autoCompleteSearchDoctor($search_keywords)
    {
        $builder = $this->db->table("users u");
        $builder->select('u.first_name,u.last_name,u.username,u.profileimage,sp.specialization as speciality');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where("(u.role = '1' OR u.role = '6')");
        $builder->where('u.status', '1');
        $builder->where('u.is_verified', '1');
        $builder->where('u.hospital_id', '0');
        $builder->where('u.is_updated', '1');
        $builder->groupStart();
        $builder->like('u.first_name', $search_keywords, 'after');
        $builder->orLike('u.last_name', $search_keywords, 'after');
        $builder->orLike('CONCAT( u.first_name, " ", u.last_name)', $search_keywords, 'after');
        $builder->orLike('sp.specialization', $search_keywords, 'after');
        $builder->orLike('ud.services', $search_keywords, 'after');
        $builder->groupEnd();
        $builder->groupBy('ud.id');
        $builder->limit('5');
        return $builder->get()->getResultArray();
    }
    /**
     * Home Page Autocomplete For Speci
     * 
     * @param mixed $search_keywords
     * @return mixed
     */
    public function autoCompleteSearchSpecialization($search_keywords)
    {
        $builder = $this->db->table("specialization");
        $where = array('status' => 1);
        return $builder
            ->select('specialization')
            ->like('specialization', $search_keywords)
            ->limit(5)
            ->orderBy('specialization', 'asc')
            ->where('specialization', $where ?? "")
            ->get()
            ->getResultArray();
    }
    /**
     * DOCTOR DETAIL
     * 
     * @param mixed $username
     * @return mixed
     */
    public function getDoctorDetails($username)
    {
        $builder = $this->db->table('users u');
        $builder->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,u.hospital_id,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value,u.role');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('u.role != 2');
        $builder->where("(u.status = '1' OR u.status = '2')");

        if (empty(session('admin_id'))) {
            $builder->where('u.is_verified', '1');
            $builder->where('u.is_updated', '1');
        }
        $builder->where('u.username', $username);
        return $builder->get()->getRowArray();
    }

    /**
     * Review For Doctor
     * 
     * @param mixed $id
     * @return mixed
     */
    public function reviewListView($id)
    {
        $builder = $this->db->table('rating_reviews r');
        $where = array('r.doctor_id' => $id);
        return $builder
            ->select('u.profileimage,u.first_name,u.last_name,d.profileimage as doctor_image,d.first_name as doctor_firstname,d.last_name as doctor_lastname,r.*,rr.id as reply_id,rr.reply as reply,rr.created_date as reply_date')
            ->join('users u ', 'u.id = r.user_id')
            ->join('users d ', 'd.id = r.doctor_id', 'left')
            ->join('review_reply rr', 'r.id = rr.review_id', 'left')
            ->get()
            ->getResultArray();
    }
    /**
     * Get Verified User Details.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function getVerifiedUserDetails($id)
    {
        $builder = $this->db->table('users');
        $builder->select('users.id as userid,users.first_name,users.last_name,users.email,users.username,users.mobileno,users.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=users.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=users.id) as rating_value,users.role');
        $builder->join('users_details ud', 'ud.user_id = users.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('users.status', '1');
        $builder->where('users.is_verified', '1');
        $builder->where('users.is_updated', '1');
        $builder->where('users.id', $id);
        return $builder->get()->getRowArray();
    }
    /**
     * Get Appoinments Details.
     * 
     * @param mixed $appointment_id
     * @return mixed
     */
    public function getAppoinmentsDetails($appointment_id)
    {
        $builder = $this->db->table('appointments');
        $builder->select('appointments.*,d.first_name as doctor_first_name,d.last_name as doctor_last_name,d.email as doctor_email,p.email as patient_email,CONCAT(d.country_code,"", d.mobileno) as doctor_mobile,CONCAT(p.country_code,"", p.mobileno) as patient_mobile, p.first_name as patient_first_name, p.last_name as patient_last_name,d.role,d.device_type as doctor_device_type,d.device_id as doctor_device_id,p.first_name as patient_first_name');
        $builder->join('users d', 'd.id = appointments.appointment_to', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->join('users p', 'p.id = appointments.appointment_from', 'left');
        $builder->join('users_details pd', 'pd.user_id = p.id', 'left');
        $builder->where('appointments.id', $appointment_id);
        return $builder->get()->getRowArray();
    }

    /**
     * Appointment Invoice Details.
     * 
     * @param mixed $invoice_id
     * @return mixed
     */
    public function getInvoiceDetails($invoice_id)
    {
        $builder = $this->db->table('payments');
        $builder->select('payments.*,
         d.first_name as doc_first_name,
         d.last_name as doc_last_name,
        d.username as doctor_username,
        d.profileimage as doctor_profileimage,
        d.mobileno as doctormobile,
        pi.first_name as pat_first_name,
        pi.last_name as pat_last_name,
        pi.profileimage as patient_profileimage,
        pi.mobileno as patientmobile,
        dc.country as doctorcountryname,
        ds.statename as doctorstatename,
        dci.city as doctorcityname,
        pc.country as patientcountryname,
        ps.statename as patientstatename,
        pci.city as patientcityname,
        dd.address1 as doctoraddress1,
        dd.address2 as doctoraddress2,
        pd.address1 as patientaddress1,
        pd.address2 as patientaddress2,
        d.role,
         pd.postal_code as patientpostalcode,
         dd.postal_code as doctorpostalcode,
         payments.doctor_id,
         payments.user_id');
        $builder->join('users d', 'd.id = payments.doctor_id', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->join('users pi', 'pi.id = payments.user_id', 'left');
        $builder->join('users_details pd', 'pd.user_id = pi.id', 'left');
        $builder->join('country dc', 'dd.country = dc.countryid', 'left');
        $builder->join('state ds', 'dd.state = ds.id', 'left');
        $builder->join('city dci', 'dd.city = dci.id', 'left');
        $builder->join('country pc', 'pd.country = pc.countryid', 'left');
        $builder->join('state ps', 'pd.state = ps.id', 'left');
        $builder->join('city pci', 'pd.city = pci.id', 'left');
        $builder->where('payments.id', $invoice_id);;
        return $builder->get()->getRowArray();
    }

    /**
     * Appointment Invoice Item
     * 
     * @param mixed $orderId
     * @return mixed
     */
    public function getProductsDatatables($orderId)
    {
        $builder = $this->db->table('orders');
        $builder->select('od.*,us.first_name as pharmacy_first_name,us.last_name as pharmacy_last_name,us.pharmacy_name as pharmacy_name,orders.quantity as qty,orders.payment_type,orders.status,orders.product_name,orders.order_id,orders.subtotal  subtotal ,orders.order_status,orders.user_notify,orders.pharmacy_notify,orders.id as id,ud.currency_code as product_currency,dc.country as doctorcountryname,ds.statename as doctorstatename,dci.city as doctorcityname,pc.country as patientcountryname,ps.statename as patientstatename,pci.city as patientcityname,ud.address1 as doctoraddress1,ud.address2 as doctoraddress2,orders.pharmacy_id,orders.currency_code as order_currency,ud.postal_code as patient_postal_code,us.mobileno as mob_no, payments.transaction_charge_percentage');
        $builder->join('order_user_details as od', 'od.order_user_details_id = orders.user_order_id', 'left');
        $builder->join('users as us', 'us.id = orders.pharmacy_id', 'left');
        $builder->join('users_details as ud', 'ud.user_id = orders.pharmacy_id', 'left');
        $builder->join('country dc', 'od.country = dc.countryid', 'left');
        $builder->join('state ds', 'od.state = ds.id', 'left');
        $builder->join('city dci', 'od.city = dci.id', 'left');
        $builder->join('country pc', 'ud.country = pc.countryid', 'left');
        $builder->join('state ps', 'ud.state = ps.id', 'left');
        $builder->join('city pci', 'ud.city = pci.id', 'left');
        $builder->join('payments', 'payments.id = orders.payment_id', 'left');
        // $builder->where('orders.order_id', ($orderId));
        $builder->where('orders.payment_id', ($orderId));
        if (session('role') == '5') {
            $builder->where('orders.pharmacy_id', session('user_id'));
        } else {
            $builder->where('orders.user_id', session('user_id'));
        }
        $query = $builder->get();
        // echo $this->db->getLastQuery();exit;
        return $query->getResultArray();
    }

    /**
     * Lab Search List
     * 
     * @param mixed $username
     * @return mixed
     */
    public function getLabDetails($username)
    {
        $builder = $this->db->table('users');
        $builder->select('users.id as userid,users.first_name,users.last_name,users.email,users.username,users.mobileno,users.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
        $builder->join('users_details ud', 'ud.user_id = users.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('users.role', '4');
        $builder->where("(users.status = '1' OR users.status = '2')");
        $builder->where('users.is_verified', '1');
        $builder->where('users.is_updated', '1');
        $builder->where('users.username', $username);
        return $result = $builder->get()->getRowArray();
    }
    /**
     * Search Lab.
     * 
     * @param mixed $page
     * @param mixed $limit
     * @param mixed $type
     * @return mixed
     */
    public function searchLab($page, $limit, $type)
    {
        $builder = $this->db->table('users');
        $builder->select('users.first_name,users.last_name,users.email,users.username,users.mobileno,users.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname');
        $builder->join('users_details ud', 'ud.user_id = users.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->where('users.role', '4');
        $builder->where('users.status', '1');
        $builder->where('users.is_verified', '1');
        $builder->where('users.is_updated', '1');

        if (!empty($_POST['city'])) {
            $builder->where('ud.city', $_POST['city']);
        }

        if (!empty($_POST['state'])) {
            $builder->where('ud.state', $_POST['state']);
        }

        if (!empty($_POST['country'])) {
            $builder->where('ud.country', $_POST['country']);
        }

        if (!empty($_POST['keywords'])) {
            $builder->groupStart();
            $builder->like('users.first_name', libsodiumEncrypt($_POST['keywords']));
            $builder->orLike('users.last_name', libsodiumEncrypt($_POST['keywords']));
            // $builder->orLike('CONCAT(users.first_name," ", users.last_name)',$_POST['keywords']);
            $builder->groupEnd();
        }

        if ($type == 1) {
            return $builder->countAllResults();
        } else {
            $page = !empty($page) ? $page : '';
            if ($page >= 1) {
                $page = $page - 1;
            }
            $page =  ($page * $limit);
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * Lab User Detail
     * 
     * 
     * @param mixed $id
     * @return mixed
     */
    public function get_user_details($id)
    {
        $builder = $this->db->table('users');
        $builder->select('users.id as userid,users.first_name,users.last_name,users.email,users.username,users.mobileno,users.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=users.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=users.id) as rating_value,users.role');
        $builder->join('users_details ud', 'ud.user_id = users.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('users.status', '1');
        $builder->where('users.is_verified', '1');
        $builder->where('users.is_updated', '1');
        $builder->where('users.id', $id);
        return $result = $builder->get()->getRowArray();
    }
    /**
     * Total Amount
     * 
     * 
     * @param mixed $booking_ids
     * @return mixed
     */
    public function getLabTestAmount($booking_ids)
    {
        $builder = $this->db->table('lab_tests');
        $builder->select('SUM(lab_tests.amount) as total_amt');
        $builder->where('id IN(' . $booking_ids . ')');
        $builder->groupBy('lab_id');
        $query = $builder->get();
        return $query->getRowArray();
    }
    /**
     * Patient Favorites Doctor List
     * 
     * @param mixed $user_id
     * @return mixed
     */
    public function getFavourites($user_id)
    {
        $builder = $this->db->table('favourities f');
        $builder->select('u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,ud.*,c.country as countryname,s.statename,ci.city as cityname,sp.specialization as speciality,sp.specialization_img,(select COUNT(rating) from rating_reviews where doctor_id=u.id) as rating_count,(select ROUND(AVG(rating)) from rating_reviews where doctor_id=u.id) as rating_value');
        $builder->join('users u', 'u.id = f.doctor_id', 'left');
        $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
        $builder->where('f.patient_id', $user_id);
        return $builder->get()->getResultArray();
    }
    /**
     * Calendar List And Details 
     * 
     * 
     * @param mixed $user_id
     * @param mixed $role
     * @return mixed
     */
    public function calendarView($user_id, $role)
    {
        $builder = $this->db->table('appointments a');
        $builder->select('a.*,u.id as userid,u.first_name,u.last_name,u.username,u.profileimage,u.email,u.mobileno,c.country as countryname,s.statename,ci.city as cityname,p.per_hour_charge');
        if ($role == 1 || $role == 6) //doctor/clinic
        {
            $builder->join('users u', 'u.id = a.appointment_from', 'left');
        }

        if ($role == 2) //patient
        {
            $builder->join('users u', 'u.id = a.appointment_to', 'left');
        }

        $builder->join('users_details ud', 'u.id = ud.user_id', 'left');
        $builder->join('payments p', 'p.id = a.payment_id', 'left');
        $builder->join('country c', 'ud.country = c.countryid', 'left');
        $builder->join('state s', 'ud.state = s.id', 'left');
        $builder->join('city ci', 'ud.city = ci.id', 'left');
        if ($role == 1) //doctor
        {
            $builder->where('a.appointment_to', $user_id);
        }

        if ($role == 2) //patient
        {
            $builder->where('a.appointment_from', $user_id);
        }

        $query = $builder->get();
        $result = $query->getResultArray();
        return $result;
    }

    /**
     * Blogs
     * 
     * @return mixed
     */
    public function get_blogs()
    {
        $builder = $this->db->table('posts p');
        $builder->select('p.*,IF(p.post_by="Admin",a.profileimage, d.profileimage) as profileimage,IF(p.post_by="Admin","Admin", CONCAT(d.first_name)) as name,d.username,c.category_name,s.subcategory_name');
        $builder->join('users d', 'p.user_id = d.id', 'left');
        $builder->join('categories c', 'p.category = c.id', 'left');
        $builder->join('subcategories s', 'p.subcategory = s.id', 'left');
        $builder->join('tags t', 'p.id = t.post_id', 'left');
        $builder->join('administrators a', '1 = a.id', 'left');
        $builder->where('p.status', '1');
        $builder->where('p.is_verified', '1');
        $builder->where('p.is_viewed', '1');

        if (isset($_POST['category']) && $_POST['category'] != "") {
            $builder->where('c.category_name', libsodiumEncrypt($_POST['category']));
        }
        if (isset($_POST['tags'])  && $_POST['tags'] != "") {
            $builder->where('t.tag', $_POST['tags']);
        }

        $i = 0;
        foreach ($this->blog_column_search as $item) // loop column 
        {
            if ($_POST['keywords'] && ($_POST['keywords'] != 'admin' && $_POST['keywords'] != 'Admin')) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                }
                if ($item == "p.title") {
                    $builder->orLike($item, libsodiumEncrypt($_POST['keywords']));
                } else if ($item == 'c.category_name') {
                    $builder->orLike($item, libsodiumEncrypt($_POST['keywords']));
                } else if ($item == 's.subcategory_name') {
                    $builder->orLike($item, libsodiumEncrypt($_POST['keywords']));
                } else if ($item == 'd.first_name') {
                    $builder->orLike($item, libsodiumEncrypt($_POST['keywords']));
                } else if ($item != 'admin' && $item != 'Admin') {
                    $builder->orLike($item, $_POST['keywords']);
                }
                if (count($this->blog_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }
        $builder->orderBy('id', 'desc');
        $builder->groupBy('p.id');
        $builder->limit('8');
        return $builder->get()->getResultArray();
    }
    /**
     * Blog Detail
     * 
     * @param mixed $title
     * @return mixed
     */
    public function blogDetails($title)
    {
        $builder = $this->db->table('posts p');
        $builder->select('p.*,IF(p.post_by="Admin",a.profileimage, d.profileimage) as profileimage,IF(p.post_by="Admin","Admin", d.first_name) as name,IF(p.post_by="Admin",a.biography, dd.biography) as about_author,d.username,c.category_name,s.subcategory_name');
        $builder->join('users d', 'p.user_id = d.id', 'left');
        $builder->join('users_details dd', 'dd.user_id = d.id', 'left');
        $builder->join('categories c', 'p.category = c.id', 'left');
        $builder->join('subcategories s', 'p.subcategory = s.id', 'left');
        $builder->join('administrators a', '1 = 0', 'left');
        $builder->where('p.status', '1');
        $builder->where('p.is_verified', '1');
        $builder->where('p.is_viewed', '1');
        $builder->where('p.slug', $title);
        $data = $builder->get()->getRowArray();
        // echo $this->db->getLastQuery();
        return $data;
    }
    /**
     * Blog Comments
     * 
     * @param mixed $post_id
     * @param mixed $page
     * @param mixed $limit
     * @param mixed $type
     * @return mixed
     */
    public function getComments($post_id, $page, $limit, $type)
    {
        $builder = $this->db->table('comments c');
        $builder->select('c.*,IF(c.role="3",a.profileimage, d.profileimage) as profileimage,IF(c.role="3","Admin",d.first_name) as name,d.username');
        $builder->join('users d', 'c.user_id = d.id', 'left');
        $builder->join('administrators a', '1 = a.id', 'left');
        $builder->where('c.status', '1');
        $builder->where('c.post_id', $post_id);
        $builder->orderBy('c.id', 'DESC');
        if ($type == 1) {
            return $builder->countAllResults();
        } else {

            $page = !empty($page) ? $page : '';
            if ($page >= 1) {
                $page = $page - 1;
            }
            $page =  ($page * $limit);
            $builder->limit($limit, $page);
            return $builder->get()->getResultArray();
        }
    }
    /**
     * Get Replies.
     * 
     * 
     * @param mixed $comment_id
     * @return mixed
     */
    public function getReplies($comment_id)
    {
        $builder = $this->db->table('replies r');
        $builder->select('r.*,IF(r.role="3",a.profileimage, d.profileimage) as profileimage,IF(r.role="3","Admin", d.first_name) as name,d.username');
        $builder->join('users d', 'r.user_id = d.id', 'left');
        $builder->join('administrators a', '1 = a.id', 'left');
        $builder->where('r.status', '1');
        $builder->where('r.comment_id', $comment_id);
        $builder->orderBy('r.id', 'ASC');
        return $builder->get()->getResultArray();
    }
    /**
     * Get Categories.
     * 
     * 
     * @return mixed
     */
    public function getCategories()
    {
        $builder = $this->db->table('categories c');
        $builder->select('c.*,(SELECT COUNT(p.id) FROM posts AS p WHERE p.category=c.id AND p.status=1 AND p.is_verified=1 AND p.is_viewed=1) AS count');
        $builder->where('c.status', '1');
        $builder->orderBy('count', 'DESC');
        $builder->limit('10');
        return $builder->get()->getResultArray();
    }
    /**
     * Get Tags.
     * 
     * 
     * @return mixed
     */
    public function tags()
    {
        $builder = $this->db->table('tags t');
        $builder->select('t.*');
        $builder->join('posts p', 'p.id = t.post_id');
        $builder->where('p.status', '1');
        $builder->where('p.is_verified', '1');
        $builder->where('p.is_viewed', '1');
        $builder->groupBy('t.slug');
        $builder->orderBy('rand()');
        $builder->limit(10);
        return $builder->get()->getResultArray();
    }
    /**
     * Get Product Details.
     * 
     * @param mixed $slug
     * @return mixed
     */
    public function getProductDetails($slug)
    {
        $builder = $this->db->table('products p');
        $builder->select('p.*,u.unit_name,ud.currency_code');
        $builder->join('unit u', 'u.id = p.unit', 'left');
        $builder->join('users_details ud', 'ud.user_id = p.user_id', 'left');
        $builder->where('p.id', $slug);
        return $builder->get()->getRowArray();
    }
}
