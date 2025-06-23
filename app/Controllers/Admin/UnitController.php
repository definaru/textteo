<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Models\UnitModel;

class UnitController extends BaseController
{
    public $data;
    public $session;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    /**
     * @var \App\Models\UnitModel
     */
    public $unitModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'unit';
        $this->data['page'] = '';

        //Define Model
        $this->commonModel = new CommonModel();
        $this->unitModel = new UnitModel();
    }
    /**
     * Load Unit Page.
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        echo view('admin/unit/index', $this->data);
    }
    /**
     * Get Unit List.
     *
     * @return mixed
     */
    public function unitList()
    {
        $list = $this->unitModel->getDatatables();
        $data = array();
        $no = $_POST['start'];
        $a = 1;
        foreach ($list as $units) {

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = libsodiumDecrypt($units['unit_name']);

            $row[] = '<div class="actions">
                  <a class="btn btn-sm bg-success-light" onclick="edit_unit(' . $units['id'] . ')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>
                  <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_unit(' . $units['id'] . ')">
                    <i class="fe fe-trash"></i> Delete
                  </a>
                </div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->unitModel->countAll(),
            "recordsFiltered" => $this->unitModel->countFiltered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    /**
     * Create Unit.
     *
     * @return mixed
     */
    public function createUnit()
    {
        $id = $this->request->getPost('id');
        $method = $this->request->getPost('method');
        $data['unit_name'] = libsodiumEncrypt($this->request->getPost('unit_name'));
        $data['status'] = 1;

        if ($method == 'update') {
            $query = $this->commonModel->countTblResult('unit', ['unit_name' => $data['unit_name'], 'id !=' => $id, 'status' => 1]);
            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Unit already exits!';
            } else {
                $result = $this->commonModel->updateData('unit', ['id =' => $id], $data);
                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Unit update successfully';
                } else {
                    $datas['result'] = 'false';
                    $datas['status'] = 'Unit update failed!';
                }
            }
        } else {
            $query = $this->commonModel->countTblResult('unit', ['unit_name' => $data['unit_name'], 'status' => 1]);

            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Unit already exits!';
            } else {
                $query = $this->commonModel->insertData('unit', $data);
                if ($query) {
                    $result = true;
                }
                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Unit added successfully';
                } else {
                    $datas['result'] = 'false';
                    $datas['status'] = 'Unit added failed!';
                }
            }
        }

        echo json_encode($datas);
    }
    /**
     * Edit Unit.
     *
     * 
     * @param int $id
     * @return mixed
     */
    public function unitEdit($id)
    {
        $data = $this->commonModel->getTblRowOfData('unit', ['id' => $id], '*');
        $data = array(
            'id' => $data['id'],
            'status' => $data['status'],
            'unit_name' => libsodiumDecrypt($data['unit_name']),
        );
        echo json_encode($data);
    }
    /**
     * Delete Unit.
     *
     * @param int $id
     * @return mixed
     */
    public function unitDelete($id)
    {
        $data = array(
            'status' => 0,
        );
        $this->commonModel->updateData('unit', array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }
}
