<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    var string $reviews = 'rating_reviews r';
    var string $doctor = 'users d';
    var string $doctor_details = 'users_details dd';
    var string $patient = 'users p';
    var string $patient_details = 'users_details pd';
    var string $specialization = 'specialization s';
    var $table = 'email_templates et';

    var mixed $column_search = array('et.template_title');
    var mixed $order = array('template_id' => 'Asc');
    var mixed $reviews_column_search = array('d.first_name', 'd.last_name', 'd.profileimage', 'p.first_name', 'p.last_name', 'p.profileimage', 'r.title', 'r.review', 'r.created_date');
    var mixed $reviews_order = array('r.id' => 'DESC'); // default order 
    var mixed $reviews_column_order = array('', 'p.first_name', 'd.username', 'r.rating', 'r.review', 'r.created_date');

    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Admin _get_reviews__datatables_query.
     * 
     * @return mixed
     */
    public function _get_reviews__datatables_query()
    {
        $builder = $this->db->table('rating_reviews r');
        $builder->select('r.*, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage, CONCAT(p.first_name," ", p.last_name) as patient_name,p.profileimage as patient_profileimage');
        $builder->join($this->doctor, 'd.id = r.doctor_id', 'left');
        $builder->join($this->patient, 'p.id = r.user_id', 'left');
        $i = 0;
        foreach ($this->reviews_column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, $_POST['search']['value']);
                } else {
                    $builder->orLike($item, $_POST['search']['value']);
                }

                if (count($this->reviews_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            // $builder->order_by('id', $_POST['order']['0']['dir']);

            $builder->orderBy($this->reviews_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->reviews_order)) {
            $order = $this->reviews_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($_POST['length'] != -1)
            $builder->limit($_POST['length'], $_POST['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Admin Reviews count Filtered.
     * 
     * @return mixed
     */
    public function reviews_count_filtered()
    {
        $builder = $this->db->table('rating_reviews r');
        $this->_get_reviews__datatables_query();
        return $builder->countAllResults();
        // $query = $builder->get();
        // return $query->getRow();
    }
    /**
     * Admin Reviews count All.
     * 
     * 
     * @return mixed
     */
    public function reviews_count_all()
    {
        $builder = $this->db->table('rating_reviews r');
        return $builder->countAllResults();
    }
    /**
     * Get Review List.
     * 
     * 
     * @return mixed
     */
    public function getReviewList()
    {
        $builder = $this->db->table('rating_reviews r');
        $builder->select('r.*,d.first_name,d.last_name, CONCAT(d.first_name," ", d.last_name) as doctor_name,d.username as doctor_username,d.profileimage as doctor_profileimage,p.first_name as patient_first_name,p.last_name as patient_last_name, CONCAT(p.first_name," ", p.last_name) as patient_name,p.profileimage as patient_profileimage');
        $builder->join($this->doctor, 'd.id = r.doctor_id', 'left');
        $builder->join($this->patient, 'p.id = r.user_id', 'left');
        $i = 0;
        foreach ($this->reviews_column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, libsodiumEncrypt($_POST['search']['value']));
                } else {
                    $builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
                }

                if (count($this->reviews_column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }
        if (isset($_POST['order'])) // here order processing
        {
            // $builder->order_by('id', $_POST['order']['0']['dir']);

            $builder->orderBy($this->reviews_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->reviews_order)) {
            $order = $this->reviews_order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        if ($_POST['length'] != -1)
            $builder->limit($_POST['length'], $_POST['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Get Review List.
     * 
     * @param mixed $page
     * @param mixed $limit
     * @param mixed $type
     * @param mixed $id
     * @return mixed
     */
    public function getNotification($page, $limit, $type, $id = '')
    {
        $builder = $this->db->table('notification n');
        $builder->select('n.*,from.first_name as patient_first_name,from.last_name as patient_last_name,IF(n.user_id>0,CONCAT(from.first_name," ", from.last_name),"Admin") as from_name,to.first_name,to.last_name,IF(n.to_user_id>0,CONCAT(to.first_name," ", to.last_name),"Admin") as to_name,from.profileimage as profile_image,to.profileimage as to_profile_image,n.created_at as notification_date');
        $builder->join('users from', 'n.user_id = from.id', 'left');
        $builder->join('users to', 'n.to_user_id = to.id', 'left');
        if ($id != '') {
            $builder->groupStart();
            $builder->where('n.user_id', $id);
            $builder->orWhere('n.to_user_id', $id);
            $builder->groupEnd();
        }
        $builder->orderBy('n.id', 'DESC');
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
     * Get Data Tables Query.
     * 
     * 
     * @return mixed
     */
    private function _get_datatables_query()
    {
        $builder = $this->db->table($this->table);
        $builder->where('template_status', 1);
        $i = 0;
        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
                    $builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $builder->like($item, $_POST['search']['value']);
                } else {
                    $builder->orLike($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $builder->groupEnd(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $builder->orderBy('id', $_POST['order']['0']['dir']);

            //$builder->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $builder->orderBy(key($order), $order[key($order)]);
        }
        return $builder;
    }
    /**
     * Get Data Tables.
     * 
     * 
     * @return mixed
     */
    public function get_datatables()
    {
        $builder = $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $builder->limit($_POST['length'], $_POST['start']);
        $query = $builder->get();
        return $query->getResultArray();
    }
    /**
     * Get Count Filtered.
     * 
     * 
     * @return mixed
     */
    public function count_filtered()
    {
        $builder = $this->db->table($this->table);
        $this->_get_datatables_query();
        $builder->where('template_status', 1);
        $query = $builder->get();
        return $query->getNumRows();
    }
    /**
     * Get Count All.
     * 
     * @return mixed
     */
    public function count_all()
    {
        $builder = $this->db->table($this->table);
        $builder->where('template_status', 1);
        return $builder->countAllResults();
    }
    /**
     * Edit Template.
     * 
     * @param mixed $id
     * @return mixed
     */
    public function edit_template($id)
    {
        $builder = $this->db->table($this->table);
        $builder->select('*');
        $builder->where('template_id', $id);
        return $builder->get()->getRowArray();
    }
}
