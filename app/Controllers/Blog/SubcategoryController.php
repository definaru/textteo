<?php

namespace App\Controllers\Blog;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\CommonModel;
use App\Models\SubcategoryModel;

class SubcategoryController extends BaseController
{
    public $data;
    public $language;
    public $session;
    /**
     * @var \App\Models\CategoryModel
     */
    public $categoryModel;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    /**
     * @var \App\Models\SubcategoryModel
     */
    public $subcategoryModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        $lan = helper('default_language');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        // Declare page detail
        $this->data['theme'] = 'blog';
        $this->data['module'] = 'subcategories';
        $this->data['page'] = '';

        //Define Model
        $this->categoryModel = new CategoryModel();
        $this->subcategoryModel = new SubcategoryModel();
        $this->commonModel = new CommonModel();
    }
    /**
     * load subcategories page.
     * 
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        echo view('blog/subcategories/index', $this->data);
    }
    /**
     * Get Subcategories List.
     * 
     * 
     * @return mixed
     */
    public function subcategoriesList()
    {
        $input = $this->request->getPost();
        $list = $this->subcategoryModel->getDatatables($input);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $subcategoriess) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = libsodiumDecrypt($subcategoriess['category_name']);
            $row[] = libsodiumDecrypt($subcategoriess['subcategory_name']);
            $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_subcategories(' . $subcategoriess['id'] . ')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_subcategories(' . $subcategoriess['id'] . ')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';

            $data[] = $row;
        }



        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->subcategoryModel->countAll(),
            "recordsFiltered" => $this->subcategoryModel->countFiltered($input),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Create Subcategories.
     * 
     * 
     * @return mixed
     */
    public function createSubcategories()
    {
        $id = $this->request->getPost('id');
        $method = $this->request->getPost('method');
        $data['subcategory_name'] = libsodiumEncrypt($this->request->getPost('subcategory_name'));
        $data['category'] = $this->request->getPost('category');
        $data['slug'] = $this->request->getPost('slug');
        $data['description'] = libsodiumEncrypt($this->request->getPost('description'));
        $data['keywords'] = libsodiumEncrypt($this->request->getPost('keywords'));
        if (empty($data["slug"])) {
            $data["slug"] = str_slug($data["subcategory_name"]);
        }

        if ($method == 'update') {
            $where = array(
                'category' => $data['category'],
                'subcategory_name' => $data['subcategory_name'],
                'id !=' => $id,
                'status' => 1
            );
            $query = $this->commonModel->countTblResult('subcategories', $where);

            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Subcategory name already exits!';
            } else {
                $result = $this->commonModel->updateData('subcategories', ['id' => $id], $data);
                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Subcategories update successfully';
                } else {
                    $datas['result'] = 'false';
                    $datas['status'] = 'Subcategories update failed!';
                }
            }
        } else {
            $where = array(
                'category' => $data['category'],
                'subcategory_name' => $data['subcategory_name'],
                'status' => 1
            );
            $query = $this->commonModel->countTblResult('subcategories', $where);

            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Subcategory name already exits!';
            } else {
                $query = $this->commonModel->insertData('subcategories', $data);
                if ($query) {
                    $result = true;
                }

                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Subcategories added successfully';
                } else {
                    $datas['result'] = 'false';
                    $datas['status'] = 'Subcategories added failed!';
                }
            }
        }

        echo json_encode($datas);
    }
    /**
     * Edit Subcategories.
     * 
     * @param int $id
     * @return mixed
     */
    public function subcategoriesEdit($id)
    {
        $data = $this->commonModel->getTblRowOfData('subcategories', ['id' => $id], '*');
        $data = array(
            'description' => libsodiumDecrypt($data['description']),
            'keywords' => libsodiumDecrypt($data['keywords']),
            'slug' => $data['slug'],
            'id' => $data['id'],
            'category' => $data['category'],
            'subcategory_name' => libsodiumDecrypt($data['subcategory_name']),
        );
        echo json_encode($data);
    }
    /**
     * Delete Subcategories.
     * 
     * @param int $id
     * @return mixed
     */
    public function subcategoriesDelete($id)
    {
        $data = array(
            'status' => 0,
        );
        $this->commonModel->updateData('subcategories', ['id' => $id], $data);
        echo json_encode(array("status" => TRUE));
    }
}
