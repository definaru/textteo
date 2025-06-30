<?php

namespace App\Controllers\Blog;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\CommonModel;

class CategoryController extends BaseController
{
    public $data;
    public $session;
    /**
     * @var \App\Models\CategoryModel
     */
    public $categoryModel;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    public $language;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'blog';
        $this->data['module'] = 'categories';
        $this->data['page'] = '';

        $lan = helper('default_language');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->categoryModel = new CategoryModel();
        $this->commonModel = new CommonModel();
    }
    /**
     * load categories page.
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        echo view('blog/categories/index', $this->data);
    }
    /**
     * Get Categories List .
     * 
     * @return mixed
     */
    public function categoriesList()
    {
        $input = $this->request->getPost();
        $list = $this->categoryModel->getDatatables($input);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $categoriess) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = libsodiumDecrypt($categoriess['category_name']);
            $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_categories(' . $categoriess['id'] . ')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_categories(' . $categoriess['id'] . ')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->categoryModel->countAll(),
            "recordsFiltered" => $this->categoryModel->countFiltered($input),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Categories Create.
     * 
     * @return mixed
     */
    public function createCategories()
    {
        $id = $this->request->getPost('id');
        $method = $this->request->getPost('method');
        $data['category_name'] = libsodiumEncrypt($this->request->getPost('category_name'));
        $data['slug'] = $this->request->getPost('slug');
        $data['description'] = libsodiumEncrypt($this->request->getPost('description'));
        $data['keywords'] = libsodiumEncrypt($this->request->getPost('keywords'));
        if (empty($data["slug"])) {
            $data["slug"] = str_slug($data["category_name"]);
        }

        if ($method == 'update') {
            $query = $this->commonModel->countTblResult('categories', array('category_name' => $data['category_name'], 'id !=' => $id, 'status' => 1));

            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Category name already exits!';
            } else {
                $result = $this->commonModel->updateData('categories', array('id' => $id, 'status' => 1), $data);

                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Category update successfully';
                } else {
                    $datas['result'] = 'false';
                    $datas['status'] = 'Edit Required';
                }
            }
        } else {
            $query = $this->commonModel->countTblResult('categories', array('category_name' => $data['category_name'], 'status' => 1));
            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Category name already exists!';
            } else {
                $query = $this->commonModel->insertData('categories', $data);

                if ($query) {
                    $result = true;
                }
                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Categories added successfully';
                } else {
                    $datas['result'] = 'false';
                    $datas['status'] = 'Categories added failed!';
                }
            }
        }

        echo json_encode($datas);
    }
    /**
     * Categories Edit.
     * 
     * @param int $id
     * @return mixed
     */
    public function categoriesEdit($id)
    {
        $data = $this->commonModel->getTblRowOfData('categories', array('id' => $id), '*');
        $data = array(
            'category_name' => libsodiumDecrypt($data['category_name']),
            'description' => libsodiumDecrypt($data['description']),
            'keywords' => libsodiumDecrypt($data['keywords']),
            'slug' => $data['slug'],
            'status' => $data['status'],
            'id' => $data['id'],
        );
        echo json_encode($data);
    }
    /**
     * Categories Delete.
     * 
     * @param int $id
     * @return mixed
     */
    public function categoriesDelete($id)
    {
        $data = array(
            'status' => 0,
        );
        $this->commonModel->updateData('categories', ['id' => $id], $data);

        echo json_encode(array("status" => TRUE));
    }
}
