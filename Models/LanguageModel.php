<?php

namespace App\Models;

use CodeIgniter\Model;

class LanguageModel extends Model
{
	protected $DBGroup          = 'default';
	protected $table            = 'language';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $returnType       = 'array';
	protected $useSoftDeletes   = false;
	protected $protectFields    = true;
	/**
	 * @var string[] List of allowed fields in the model.
	 */
	protected $allowedFields    = [];

	protected mixed $column_order = array(null, 'L.lang_key', 'L.lang_value', 'L.language');
	protected mixed $column_search = array('L.lang_key', 'L.lang_value', 'L.language');
	protected mixed $app_column_search = array('L.lang_key', 'L.lang_value', 'L.language');
	protected mixed $order = array('L.sno' => 'DESC'); // default order
	protected mixed $request_details  = 'language_management L';
	protected mixed $language_management  = 'language_management';


	public function __construct()
	{
		parent::__construct();
		$this->db = \Config\Database::connect();
	}
	/**
	 * Language List
	 * 
	 * @return mixed
	 */
	public function languagesList()
	{
		$builder = $this->db->table('language');
		return $builder->get()->getResultArray();
	}

	/**
	 * Language List
	 * 
	 * @param mixed $inputdata
	 * @return mixed
	 */
	public function language_list($inputdata)
	{
		$builer = $this->db->table($this->request_details);
		$builer->select('L.*');
		$i = 0;

		foreach ($this->column_search as $item) // loop column
		{
			if ($inputdata['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$builer->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$builer->like($item, $inputdata['search']['value']);
				} else {

					if ($item == 'status') {
						if (strtolower($inputdata['search']['value']) == 'active') {
							$search_val = 1;
							$builer->orLike($item, $search_val);
						}
						if (strtolower($inputdata['search']['value']) == 'inactive') {
							$search_val = 0;
							$builer->orLike($item, $search_val);
						}
					} else {
						$search_val = $inputdata['search']['value'];
						$builer->orLike($item, $search_val);
					}
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$builer->groupEnd(); //close bracket
			}
			$i++;
		}

		if (isset($inputdata['order'])) // here order processing
		{
			$builer->orderBy($this->column_order[$inputdata['order']['0']['column']], $inputdata['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$builer->orderBy(key($order), $order[key($order)]);
		}
		if ($inputdata['length'] != -1)
			$builer->limit($inputdata['length'], $inputdata['start']);
		$builer->groupBy(array('L.lang_key'));
		$query = $builer->get();
		return $query->getResultArray();
	}
	/**
	 * Language List All
	 * 
	 *
	 * @return mixed
	 */
	public function languageListAll()
	{
		$builer = $this->db->table($this->request_details);
		$builer->groupBy(array('L.lang_key'));
		return $builer->countAllResults();
	}
	/**
	 * Currenct PageKey Value
	 * 
	 * @param mixed $inputs
	 * @return mixed
	 */
	public function currenctPageKeyValue($inputs)
	{

		$my_keys = array();
		if (!empty($inputs)) {
			foreach ($inputs as $input) {
				$my_keys[] = $input['lang_key'];
			}
		}


		$my_final_values = array();
		if (!empty($my_keys)) {

			$builder = $this->db->table($this->language_management);
			$builder->select('sno,lang_key,lang_value,language');
			$builder->whereIn('lang_key', $my_keys);
			$builder->orderBy('lang_key');
			$my_final = $builder->get()->getResultArray();
			if (!empty($my_final)) {
				foreach ($my_final as $keyvalue) {
					$my_final_values[$keyvalue['lang_key']][$keyvalue['language']] = $keyvalue['lang_value'];
				}
			}
		}
		return $my_final_values;
	}

	/**
	 * Add page
	 * 
	 * 
	 * @return mixed
	 */
	public function addPage()
	{
		if (!empty($_POST['page_name'])) {
			$data = array();
			$page_name = trim($_POST['page_name']);
			$page_key = str_replace(array(' ', '!', '&'), '_', strtolower($page_name));
			$data['page_title'] = $page_name;
			$data['page_key'] = $page_key;
			$data['status'] = 1;
			$builder = $this->db->table('pages');
			$builder->where($data);
			$record = $builder->countAllResults();
			if ($record >= 1) {
				return false;
			} else {
				$result = $builder->insert($data);
				return $result;
			}
		}
	}
	/**
	 * App Currenct Page Key Value
	 * 
	 * @param mixed $inputs
	 * @return mixed
	 */
	public function appCurrenctPageKeyValue($inputs)
	{
		$my_keys = array();
		$mypage_keys = array();
		if (!empty($inputs)) {
			foreach ($inputs as $input) {
				$my_keys[] = $input['lang_key'];
				$mypage_keys[] = $input['page_key'];
			}
		}
		$my_final_values = array();
		if (!empty($my_keys)) {
			$builder = $this->db->table('app_language_management');
			$builder->select('sno,lang_key,lang_value,language,type,page_key');
			$builder->whereIn('lang_key', $my_keys);
			$builder->whereIn('page_key', $mypage_keys);
			$builder->orderBy('lang_key');
			$my_final = $builder->get()->getResultArray();
			if (!empty($my_final)) {
				foreach ($my_final as $keyvalue) {
					$my_final_values[$keyvalue['lang_key']][$keyvalue['language']]['name'] = $keyvalue['lang_value'];
					$my_final_values[$keyvalue['lang_key']][$keyvalue['language']]['type'] = $keyvalue['type'];
					$my_final_values[$keyvalue['lang_key']][$keyvalue['language']]['lang_key'] = $keyvalue['lang_key'];
				}
			}
		}
		return $my_final_values;
	}
	/**
	 * App Language List All
	 * 
	 * @param mixed $page_key
	 * @return mixed
	 */
	public function appLanguageListAll($page_key)
	{
		$builder = $this->db->table('app_language_management L');
		$builder->where('L.page_key', $page_key);
		return $builder->countAllResults();
	}
	/**
	 * App Get Datatables Query
	 * 
	 * 
	 * @return mixed
	 */
	private function appGetDatatablesQuery()
	{
		$builder = $this->db->table('app_language_management L');
		$builder->select('L.*, P.page_title');
		$builder->join('pages P', 'P.page_key = L.page_key', 'left');
		$i = 0;
		foreach ($this->app_column_search as $item) // loop column
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{
				if ($i === 0) // first loop
				{
					$builder->groupStart(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$builder->like($item, $_POST['search']['value']);
				} else {
					if ($item == 'status') {
						if (strtolower($_POST['search']['value']) == 'active') {
							$search_val = 1;
							$builder->orLike($item, $search_val);
						}
						if (strtolower($_POST['search']['value']) == 'inactive') {
							$search_val = 0;
							$builder->orLike($item, $search_val);
						}
					} else {
						$search_val = $_POST['search']['value'];
						$builder->orLike($item, $search_val);
					}
				}

				if (count($this->app_column_search) - 1 == $i) //last loop
					$builder->groupEnd(); //close bracket
			}
			$i++;
		}
		if (isset($_POST['order'])) // here order processing
		{
			$builder->orderBy($this->app_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->app_order)) {
			$order = $this->app_order;
			$builder->orderBy(key($order), $order[key($order)]);
		}
		return $builder;
	}
	/**
	 * App Get Datatables Query
	 * 
	 * @param mixed $page_key
	 * @return mixed
	 */
	public function appLanguageList($page_key)
	{
		$builder = $this->appGetDatatablesQuery();
		if ($_POST['length'] != -1)
			$builder->where('L.page_key', $page_key);
		$builder->limit($_POST['length'], $_POST['start']);
		$builder->groupBy(array('L.page_key', 'L.lang_key'));
		$query = $builder->get();
		return $query->getResultArray();
	}
	/**
	 * App Language List Filtered
	 * 
	 * @param mixed $page_key
	 * @return mixed
	 */
	public function appLanguageListFiltered($page_key)
	{
		$builder = $this->appGetDatatablesQuery();
		$builder->where('L.page_key', $page_key);
		$builder->groupBy(array('L.page_key', 'L.lang_key'));
		$query = $builder->get();
		return $query->getNumRows();
	}
	/**
	 * Add App Keywords
	 * 
	 * 
	 * @return mixed
	 */
	public function addAppKeywords()
	{
		if (!empty($_POST['field_name'])) {
			$data = array();
			$datas = array();
			$field_name = trim($_POST['field_name']);
			$data['lang_key'] = $field_name;
			$data['page_key'] = $_POST['page_key'];
			$data['language'] = 'en';
			$builder = $this->db->table('app_language_management');
			$builder->where($data);
			$record = $builder->countAllResults();
			if ($record >= 1) {
				return false;
			} else {
				$datas['lang_key'] = $field_name;
				$datas['lang_value'] = trim($_POST['name']);
				$datas['page_key'] = $_POST['page_key'];
				$datas['type'] = $_POST['type'];
				$datas['language'] = 'en';
				$result = $builder->insert($datas);
				// echo $this->db->getLastQuery();exit;
				return $result;
			}
		}
	}
}
