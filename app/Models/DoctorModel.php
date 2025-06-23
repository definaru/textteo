<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'doctors';
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

	// Validation
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $validationRules      = [];
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks = true;
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $beforeInsert   = [];
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $afterInsert    = [];
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $beforeUpdate   = [];
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $afterUpdate    = [];
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $beforeFind     = [];
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $afterFind      = [];
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $beforeDelete   = [];
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $afterDelete    = [];
	protected mixed $column_search = array('users');
	protected mixed $blog_column_search = array('p.title', 'c.category_name', 's.subcategory_name', 'd.first_name', 'd.last_name');
	protected mixed $blog_default_column_order = array('p.id' => 'DESC'); // default order 
	/**
	 * Get Datatables
	 * 
	 * @param mixed $user_id
	 * @param mixed $input
	 * @param mixed $type
	 * @return mixed
	 */
	public function getDatatables($user_id, $input, $type = "")
	{
		$builder = $this->db->table('users u');
		$builder->select('u.id as id,CONCAT(u.first_name," ",u.last_name) as name,u.first_name,u.last_name ,u.email,u.country_code, u.mobileno as mobile,u.profileimage as profile,u.username as  username,u.is_verified,u.is_updated');
		$builder->join('users_details ud', 'u.id = ud.user_id', 'left');
		if ($type == 2) {
			$builder->where('u.id', $user_id);
		}
		$builder->where('u.status', 1);
		$builder->where('u.hospital_id', $user_id);
		$i = 0;
		foreach ($this->column_search as $item) // loop column 
		{
			if (isset($input['search']['value'])) // if datatable send POST for search
			{
				if ($i === 0) // first loop
				{
					$builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$builder->like($item, $input['search']['value']);
				} else {
					$builder->orLike($item, $input['search']['value']);
				}
				if (count($this->column_search) - 1 == $i) //last loop
					$builder->groupEnd(); //close bracket
			}
			$i++;
		}
		if (isset($_POST['order'])) // here order processing
		{
			$builder->orderBy('id', $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$builder->orderBy(key($order), $order[key($order)]);
		} else {
			$builder->orderBy('u.id', 'DESC');
		}
		$query = $builder->get();
		if ($type == 1) {
			$result = $query->getRowArray();
		} else {
			$result = $query->getResultArray();
		}
		return $result;
	}
	/**
	 * Blog List
	 * 
	 * @return mixed
	 */
	private function get_blog_datatables_query()
	{
		$builder = $this->db->table('posts p');
		$builder->select('p.*,d.first_name as d_first_name,d.last_name as d_last_name,d.username,c.category_name,s.subcategory_name');
		$builder->join('users d', 'p.user_id = d.id', 'left');
		$builder->join('categories c', 'p.category = c.id', 'left');
		$builder->join('subcategories s', 'p.subcategory = s.id', 'left');
		$builder->where('p.status', '1');
		if ($_POST['posts_type'] == 1) {
			$builder->where('p.is_verified', '1');
		}
		if ($_POST['posts_type'] == 2) {
			$builder->where('p.is_verified', '0');
		}

		if ($_POST['search']['value'] == 'admin' || $_POST['search']['value'] == 'Admin') {
			$builder->where('p.user_id', 0);
		} else {
			if ((session('user_id'))) {
				$builder->where('p.user_id', session('user_id'));
			}
		}
		$i = 0;

		foreach ($this->blog_column_search as $item) // loop column 
		{
			if ($_POST['search']['value'] && ($_POST['search']['value'] != 'admin' && $_POST['search']['value'] != 'Admin')) // if datatable send POST for search
			{
				if ($i === 0) // first loop
				{
					$builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
				}
				if ($item == "p.title") {
					$builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
				} else if ($item == 'c.category_name') {
					$builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
				} else if ($item == 's.subcategory_name') {
					$builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
				} else if ($item == 'd.first_name') {
					$builder->orLike($item, libsodiumEncrypt($_POST['search']['value']));
				} else if ($item != 'admin' && $item != 'Admin') {
					$builder->orLike($item, $_POST['search']['value']);
				}
				if (count($this->blog_column_search) - 1 == $i) //last loop
					$builder->groupEnd(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$builder->orderBy('id', $_POST['order']['0']['dir']);
		} else if (isset($this->blog_default_column_order)) {
			$order = $this->blog_default_column_order;
			$builder->orderBy(key($order), $order[key($order)]);
		}
		return $builder;
	}
	/**
	 * Get Blog Datatables
	 * 
	 * @return mixed
	 */
	public function getBlogDatatables()
	{
		$builder = $this->get_blog_datatables_query();
		if ($_POST['length'] != -1)
			$builder->limit($_POST['length'], $_POST['start']);
		$query = $builder->get();
		// echo $this->db->getLastQuery();
		return $query->getResultArray();
	}
	/**
	 * Get Blog Datatables
	 * 
	 * @return mixed
	 */
	public function blogCountFiltered()
	{
		$builder = $this->get_blog_datatables_query();
		$query = $builder->get();
		return $query->getNumRows();
	}
	/**
	 * Blog Count All
	 * 
	 * @return mixed
	 */
	public function blogCountAll()
	{
		$builder = $this->db->table('posts p');
		$builder->where('p.status', '1');
		if ($_POST['posts_type'] == 1) {
			$builder->where('p.is_verified', '1');
		}
		if ($_POST['posts_type'] == 2) {
			$builder->where('p.is_verified', '0');
		}
		if (!empty(session('user_id'))) {
			$builder->where('p.user_id', session('user_id'));
		}
		return $builder->countAllResults();
	}
}
