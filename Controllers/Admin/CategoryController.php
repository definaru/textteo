<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\ProductCategoryModel;

class CategoryController extends BaseController
{
    public $data;
    public $session;
    public $categoryModel;
    public $commonModel;
    public $productCategoryModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'categories';
        $this->data['page'] = '';

        //Define Model
        $this->productCategoryModel = new ProductCategoryModel();
        $this->commonModel = new CommonModel();
    }
    /**
     * load appointment page.
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        echo view('admin/categories/index', $this->data);
    }
    /**
     * Create Categories.
     *
     * @return mixed.
     */
    public function createCategories()
    {
        $id = $this->request->getPost('id');
        $method = $this->request->getPost('method');
        $data['category_name'] = libsodiumEncrypt($this->request->getPost('category_name'));
        $data["slug"] = str_slug($data["category_name"]);
        $data["category_image"] = $this->request->getPost('category_img');
        if ($_FILES["category_image"]["name"] != '') {

            if (!is_dir('./uploads/categories')) {
                mkdir('./uploads/categories', 0777, TRUE);
            }
            $file = $this->request->getFile('category_image');
            $fname = $file->getRandomName();
            $file->move('uploads/categories', $fname);
            $category_image = 'uploads/categories/' . $fname;
            $data['category_image'] = $category_image;
        }
        if ($method == 'update') {
            $query = $this->commonModel->countTblResult('product_categories', ['category_name' => $data['category_name'], 'id !=' => $id, 'status' => 1]);


            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Category name already exits!';
            } else {
                $result = $this->commonModel->updateData('product_categories', ['id =' => $id], $data);
                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Category update successfully';
                } else {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Category update successfully';
                }
            }
        } else {
            $query = $this->commonModel->countTblResult('product_categories', ['category_name' => $data['category_name'], 'status' => 1]);
            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Category name already exits!';
            } else {
                $query = $this->commonModel->insertData('product_categories', $data);
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
        //return $this->response->setJSON($datas);
    }

    /**
     * Get Categories List.
     *
     * @return mixed.
     */
    public function categoriesList()
    {
        $list = $this->productCategoryModel->getDatatables();
        $data = array();
        $no = $_POST['start'];
        $a = 1;

        foreach ($list as $categories) {
            if (file_exists(FCPATH . $categories['category_image']) && !empty($categories['category_image'])) {
                $categoryImg = base_url() . $categories['category_image'];
            } else {
                $categoryImg = base_url() . "assets/img/logo-small.PNG";
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<h2 class="table-avatar">
                    <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                      <img class="avatar-img" src="' . $categoryImg . '" alt="">
                    </a>
                    <a href="javascript:void(0);">' . libsodiumDecrypt($categories['category_name']) . '</a>
                 </h2>';
            $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_categories(' . $categories['id'] . ')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_categories(' . $categories['id'] . ')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';

            $data[] = $row;
        }



        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->productCategoryModel->countAll(),
            "recordsFiltered" => $this->productCategoryModel->countFiltered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
        //return $this->response->setJSON($output);
    }

    /**
     * Categories Edit.
     *
     * @param  int $id
     * @return mixed.
     */
    public function categoriesEdit($id)
    {
        $data = $this->commonModel->getTblRowOfData('product_categories', ['id' => $id], '*');
        $data = array(
            'id' => $data['id'],
            'category_image' => $data['category_image'],
            'category_name' => libsodiumDecrypt($data['category_name']),
            'slug' => $data['slug'],
        );
        echo json_encode($data);
        //return $this->response->setJSON($data);
    }
    /**
     * Categories Delete.
     *
     * @param  int $id
     * @return mixed.
     */
    public function categoriesDelete($id)
    {
        $data = array(
            'status' => 0,
        );
        $this->commonModel->updateData('product_categories', array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        //return $this->response->setJSON(array("status" => TRUE));
    }
}
