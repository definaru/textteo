<?php

namespace App\Controllers\Admin;

use App\Models\CommonModel;
use App\Models\CountryModel;
use App\Controllers\BaseController;

class CountryController extends BaseController
{
    public $data;
    public $session;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    /**
     * @var \App\Models\CountryModel
     */
    public $countryModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'country';
        $this->data['page'] = '';

        //Define Model
        $this->commonModel = new CommonModel();
        $this->countryModel = new CountryModel();
    }
    /**
     * Get Country page.
     *
     * @return mixed
     */
    public function country()
    {

        $this->data['list'] = $this->commonModel->getTblResultOfData('country', [], '*');
        $this->data['page'] = 'country';
        echo view('admin/country/country', $this->data);
    }
    /**
     * Country Add.
     *
     * @return mixed
     */
    public function countryAdd()
    {
        $where = array();
        $where['country'] = $this->request->getPost('country');
        $record = $this->commonModel->countTblResult('country', $where);
        if ($record >= 1) {
            session()->setFlashdata('message', 'Already exists');
            redirect(base_url('admin/country/country'));
        } else {

            $data = array(
                'sortname' => trim($this->request->getPost('sortname') ?? ""),
                'country' => trim($this->request->getPost('country') ?? ""),
                'phonecode' => trim($this->request->getPost('phone_code') ?? ""),
            );
            $record = $this->commonModel->insertData('country', $data);
        }
        session()->setFlashdata('success_message', 'The country has been added successfully...');
        return redirect('admin/country');
    }
    /**
     * Check Sort Name.
     *
     * @return mixed
     */
    public function checkSortname()
    {
        $sortname = $this->request->getPost('sortname');
        $result = $this->commonModel->getTblRowOfData('country', array('sortname' => $sortname), 'countryid');

        if ($result > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    /**
     * Check Country.
     *
     * @return mixed
     */
    public function checkCountry()
    {
        $country = $this->request->getPost('country');
        $result = $this->commonModel->getTblRowOfData('country', array('country' => $country), 'countryid');

        if ($result > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    /**
     * Check Country.
     *
     * @return mixed
     */
    public function checkPhonecode()
    {
        $phonecode = $this->request->getPost('phonecode');
        $result = $this->commonModel->getTblRowOfData('country', array('phonecode' => $phonecode), 'countryid');

        if ($result > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    /**
     * Check Country.
     *
     * @return mixed
     */
    public function state()
    {
        $this->data['page'] = 'state';
        echo view('admin/country/state', $this->data);
    }
    /**
     * Get State List.
     *
     * @return mixed
     */
    public function stateList()
    {
        $input = $this->request->getPost();
        $role_info = session('role_details');
        $country_id = $this->request->getPost('country_id');

        $list = $this->countryModel->getStateDatatables($country_id, $input);
        $data = array();
        $no = $input['start'];
        $a = 1;

        foreach ($list as $state) {


            $no++;
            $row = array();
            $row[] = $no;


            $row[] = $state["statename"];
            $edit = "";
            $delete = "";


            $edit = '<a class="btn btn-sm bg-success-light" onclick="edit_state(' . $state['id'] . ')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>';

            $delete = '<a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_state(' . $state['id'] . ')">
                    <i class="fe fe-trash"></i> Delete
                  </a>';


            $row[] = '<div class="actions">' . $edit . ' ' . $delete . '</div>';



            $data[] = $row;
        }



        $output = array(
            "draw" => $input['draw'],
            "recordsTotal" => $this->countryModel->stateCountAll($country_id),
            "recordsFiltered" => $this->countryModel->stateCountFiltered($country_id, $input),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
        //return $this->response->setJSON($output);
    }
    /**
     * State Insert.
     *
     * @return mixed.
     */
    public function stateInsert()
    {
        $where = array();
        $where['countryid'] = $this->request->getPost('country');
        $where['statename'] = $this->request->getPost('state');

        $record = $this->commonModel->countTblResult('state', $where);
        if ($record >= 1) {

            $datas['result'] = 'exe';
            $datas['status'] = 'State already exits!';
        } else {
            $data = array(
                'statename' => trim($this->request->getPost('state') ?? ""),
                'countryid' => trim($this->request->getPost('country') ?? ""),
            );
            $result = $this->commonModel->insertData('state', $data);
            $datas['result'] = 'true';
            $datas['status'] = 'State added successfully';
        }
        echo json_encode($datas);
        //return $this->response->setJSON($datas);
    }
    /**
     * State Edit.
     *
     * @return mixed.
     */
    public function stateEdit()
    {
        $id = $this->request->getPost('id');
        $result = $this->commonModel->getTblRowOfData('state', array('id' => $id), '*');
        echo json_encode($result);
        //return $this->response->setJSON($result);
    }
    /**
     * State Update.
     *
     * @return mixed.
     */
    public function stateUpdate()
    {
        $id = $this->request->getPost('id');
        $record = $this->commonModel->countTblResult('state', array('statename' => $this->request->getPost('estate'), 'id !=' => $id));

        if ($record >= 1) {
            $datas['result'] = 'exe';
            $datas['status'] = 'State already exits!';
        } else {
            $data = array(
                'statename' => trim($this->request->getPost('estate') ?? ""),
            );
            $result = $this->commonModel->updateData('state', array('id' => $id), $data);
            if ($result == true) {
                $datas['result'] = 'true';
                $datas['status'] = 'State updated successfully';
            } else {
                $datas['result'] = 'false';
                $datas['status'] = 'State update failed!';
            }
        }
        echo json_encode($datas);
        //return $this->response->setJSON($datas);
    }
    /**
     * City Page.
     *
     * @return mixed.
     */
    public function city()
    {
        $this->data['page'] = 'city';
        echo view('admin/country/city', $this->data);
    }
    /**
     * City Insert.
     *
     * @return mixed.
     */
    public function cityInsert()
    {
        $where = array();
        $where['stateid'] = $this->request->getPost('state');
        $where['city'] = $this->request->getPost('city');
        $record = $this->commonModel->countTblResult('city', $where);
        if ($record >= 1) {

            // session()->setFlashdata('message', 'Already exists');
            $datas['result'] = 'false';
            $datas['status'] = 'Already exists';

            // return redirect('admin/city');
        } else {
            $data = array(
                'stateid' => trim($this->request->getPost('state') ?? ""),
                'city' => trim($this->request->getPost('city') ?? ""),
            );
            $result = $this->commonModel->insertData('city', $data);
            $datas['result'] = 'true';
            $datas['status'] = 'The city has been added successfully';
        }

        // session()->setFlashdata('success_message', 'The city has been added successfully...');
        echo json_encode($datas);
        //return $this->response->setJSON($datas);

        // return redirect('admin/city');
    }
    /**
     * Get City List.
     *
     * @return mixed.
     */
    public function cityList()
    {
        $input = $this->request->getPost();
        $state_id = $this->request->getPost('state_id');


        $list = $this->countryModel->getCityDatatables($state_id, $input);
        $data = array();
        $no = $input['start'];
        $a = 1;

        foreach ($list as $state) {


            $no++;
            $row = array();
            $row[] = $no;


            $row[] = $state["city"];
            $edit = "";
            $delete = "";


            $edit = '<a class="btn btn-sm bg-success-light" onclick="edit_city(' . $state['id'] . ')" href="javascript:void(0)">
                    <i class="fe fe-pencil"></i> Edit
                  </a>';

            $delete = '<a class="btn btn-sm bg-danger-light" href="javascript:void(0)" onclick="delete_city(' . $state['id'] . ')">
                    <i class="fe fe-trash"></i> Delete
                  </a>';


            $row[] = '<div class="actions">' . $edit . ' ' . $delete . '</div>';



            $data[] = $row;
        }



        $output = array(
            "draw" => $input['draw'],
            "recordsTotal" => $this->countryModel->cityCountAll($state_id),
            "recordsFiltered" => $this->countryModel->cityCountFiltered($state_id, $input),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
        //return $this->response->setJSON($output);
    }
    /**
     * City Edit.
     *
     * @return mixed.
     */
    public function cityEdit()
    {
        $id = $this->request->getPost('id');
        $result = $this->commonModel->getTblRowOfData('city', array('id' => $id), '*');

        echo json_encode($result);
        //return $this->response->setJSON($result);
    }
    /**
     * City Update.
     *
     * @return mixed.
     */
    public function cityUpdate()
    {

        $id = $this->request->getPost('id');

        $record = $this->commonModel->countTblResult('city', array('city' => $this->request->getPost('estate'), 'id !=' => $id));

        if ($record >= 1) {

            $datas['result'] = 'exe';
            $datas['status'] = 'City already exits!';
        } else {

            $data = array(

                'city' => trim($this->request->getPost('ecity') ?? ""),



            );
            $result = $this->commonModel->updateData('city', array('id' => $id), $data);

            if ($result == true) {
                $datas['result'] = 'true';
                $datas['status'] = 'City update successfully';
            } else {
                $datas['result'] = 'false';
                $datas['status'] = 'City update failed!';
            }
        }

        echo json_encode($datas);
        //return $this->response->setJSON($datas);
    }
    /**
     * State Delete.
     *
     * @param  int $id
     * @return mixed.
     */
    public function stateDelete($id)
    {
        $record = $this->commonModel->countTblResult('city', ['stateid' => $id]);
        if ($record >= 1) {
            $datas['result'] = 'exe';
            $datas['status'] = 'This State existed in city table';
        } else {
            $check = $this->commonModel->deleteData('state', ['id' => $id]);
            if ($check == true) {
                $datas['result'] = 'true';
                $datas['status'] = 'State deleted successfully';
            } else {
                $datas['result'] = 'false';
                $datas['status'] = 'State deleted failed!';
            }
        }

        echo json_encode($datas);
        //return $this->response->setJSON($datas);
    }
    /**
     * City Delete.
     *
     * @param  int $id
     * @return mixed.
     */
    public function cityDelete($id)
    {
        $check = $this->commonModel->deleteData('city', ['id' => $id]);

        if ($check == true) {
            $datas['result'] = 'true';
            $datas['status'] = 'City deleted successfully';
        } else {
            $datas['result'] = 'false';
            $datas['status'] = 'City deleted failed!';
        }

        echo json_encode($datas);
        //return $this->response->setJSON($datas);
    }
}
