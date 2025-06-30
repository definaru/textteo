<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SpecializationModel;


class SpecializationController extends BaseController
{

    public $data;
    /**
     * @var \App\Models\SpecializationModel
     */
    public $specializationModel;

    public $session;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'specialization';
        $this->data['page'] = '';

        //Define Model
        $this->specializationModel = new SpecializationModel();
    }
    /**
     * load specialization page.
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        echo view('admin/specialization/index', $this->data);
    }

    /**
     * Fetch Specialization List.
     * 
     * @return mixed
     */
    public function specialization_list()
    {
        $list = $this->specializationModel->getDatatables($this->request->getPost());
        $data = array();
        $no = $this->request->getPost('start');
        $a = 1;

        foreach ($list as $specializations) {

            $img = 'uploads/specialization/' . $specializations['specialization_img'];
            if (!empty($img) && file_exists($img)) {
                $img = base_url() . $img;
            } else {
                $img = base_url() . 'assets/img/product.jpg';
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<h2 class="table-avatar">
                        <a href="javascript:void(0);" class="avatar avatar-sm mr-2">
                        <img class="avatar-img" src="' . $img . '" alt="Speciality">
                        </a>
                        <a href="javascript:void(0);">' . libsodiumDecrypt($specializations['specialization']) . '</a>
                    </h2>';

            $row[] = '<div class="actions">
                    <a class="btn btn-sm bg-success-light" onclick="edit_specialization(' . $specializations['id'] . ')" href="javascript:void(0)">
                        <i class="fe fe-pencil"></i> Edit
                    </a>
                    <a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_specialization(' . $specializations['id'] . ')">
                        <i class="fe fe-trash"></i> Delete
                    </a>
                    </div>';

            $data[] = $row;
        }
        // $output = array(
        //     "draw" => $_POST['draw'],
        //     "recordsTotal" => $this->specializationModel->countAll(),
        //     "recordsFiltered" => count($list),
        //     "data" => $data,
        // );
        // Calculate the total number of records (recordsTotal)
        $totalRecords = $this->specializationModel->countAll();

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $totalRecords,  // Set to the total number of records
            "recordsFiltered" => $totalRecords,  // In this example, no filtering is applied
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    /**
     * Create / Update New Specialization.
     * 
     * @return mixed
     */
    public function createSpecialization()
    {
        // print_r($this->request->getPost());exit;
        $id = $this->request->getPost('id');
        $method = $this->request->getPost('method');
        $data['specialization'] = libsodiumEncrypt($this->request->getPost('specialization'));


        if ($_FILES["specialization_image"]["name"] != '') {
            if (!is_dir('./uploads/specialization')) {
                mkdir('./uploads/specialization', 0777, TRUE);
            }
            $file = $this->request->getFile('specialization_image');
            $fname = $file->getRandomName();
            $file->move('uploads/specialization', $fname);
            $data['specialization_img'] = $fname;
        }

        $data['status'] = 1;

        if ($method == 'update') {
            $query = $this->specializationModel->checkSpecializationExist($data['specialization'], $id);
            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Specialization already exits!';
            } else {
                // print_r($id);exit;
                $result = $this->specializationModel->updateSpecialization(array('id' => $id), $data);

                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Specialization update successfully';
                } else {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Specialization update successfully';
                }
            }
        } else {
            $query = $this->specializationModel->checkSpecializationExist($data['specialization']);

            if ($query > 0) {
                $datas['result'] = 'exe';
                $datas['status'] = 'Specialization already exits!';
            } else {
                $result = $this->specializationModel->insertSpecialization($data);

                if (@$result == true) {
                    $datas['result'] = 'true';
                    $datas['status'] = 'Specialization added successfully';
                } else {
                    $datas['result'] = 'false';
                    $datas['status'] = 'Specialization added failed!';
                }
            }
        }

        echo json_encode($datas);
    }
    /**
     * Delete Specialization.
     * 
     * @param int $id
     * @return mixed
     */
    public function specializationDelete($id)
    {
        $data = array(
            'status' => 0,
        );
        $this->specializationModel->updateSpecialization(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
    }

    /**
     * Edit Specialization.
     * 
     * @param int $id
     * @return mixed
     */
    public function specializationEdit($id)
    {
        $data = $this->specializationModel->getSpecializationById($id);
        $specialization = array(
            'id' => $data->id,
            'specialization' => libsodiumDecrypt($data->specialization),
            'specialization_img' => $data->specialization_img
        );
        echo json_encode($specialization);
    }
}
