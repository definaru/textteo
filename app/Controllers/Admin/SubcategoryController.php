<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\ProductSubcategoryModel;

class SubcategoryController extends BaseController
{
    public $data;
    public $session;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    /**
     * @var \App\Models\ProductSubcategoryModel
     */
    public $productsubcategoryModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'subcategories';
        $this->data['page'] = '';

        //Define Model
        $this->productsubcategoryModel = new ProductSubcategoryModel();
        $this->commonModel = new CommonModel();
    }
    /**
     * Load Subcategories Page.
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        echo view('admin/subcategories/index', $this->data);
    }
    /**
     * Get Subcategories List.
     *
     * @return mixed
     */
    public function subcategoriesList()
    {
        $list = $this->productsubcategoryModel->getDatatables();
        $data = array();
        $no = $_POST['start'];
        $a = 1;

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
            "recordsTotal" => $this->productsubcategoryModel->countAll(),
            "recordsFiltered" => $this->productsubcategoryModel->countFiltered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Create Subcategories.
     *
     * @return mixed
     */
    public function createSubcategories()
    {
        $id = $this->request->getPost('id');
        $method = $this->request->getPost('method');
        $data['subcategory_name'] = libsodiumEncrypt($this->request->getPost('subcategory_name'));
        $data['category'] = $this->request->getPost('category');
        $data["slug"] = str_slug($data["subcategory_name"]);


        if ($method == 'update') {
            $query = $this->commonModel->countTblResult('product_subcategories', ['category' => $data['category'], 'subcategory_name' => $data['subcategory_name'], 'id !=' => $id, 'status' => 1]);

            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Subcategory name already exits!';
            } else {
                $result = $this->commonModel->updateData('product_subcategories', ['id' => $id], $data);
                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Subcategories update successfully';
                } else {
                    $datas['result'] = 'false';
                    $datas['status'] = 'Subcategories update failed!';
                }
            }
        } else {

            $query = $this->commonModel->countTblResult('product_subcategories', ['category' => $data['category'], 'subcategory_name' => $data['subcategory_name'], 'status' => 1]);


            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Subcategory name already exits!';
            } else {
                $result = $this->commonModel->insertData('product_subcategories', $data);

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
        $data = $this->commonModel->getTblRowOfData('product_subcategories', ['id' => $id], '*');
        $data = array(
            'category' => $data['category'],
            'id' => $data['id'],
            'status' => $data['status'],
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
        $this->commonModel->updateData('product_subcategories', ['id' => $id], $data);
        echo json_encode(array("status" => TRUE));
    }
}
