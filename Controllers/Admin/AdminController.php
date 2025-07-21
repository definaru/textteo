<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;


class AdminController extends BaseController
{
    public $data;
    public $session;
    //public mixed $renderer;
    public $adminModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();
        //$this->renderer = \Config\Services::renderer();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'login';
        $this->data['page'] = '';

        //Define Model
        $this->adminModel = new AdminModel();
    }
    /**
     * load admin login page.
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'login';

        // Render the view
        //echo $this->renderer->setData($this->data)->view($this->data['theme'] . '/login', $this->data);
        return view($this->data['theme'] . '/login', $this->data);
    }

    /**
     * is Valid Login.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function isValidLogin()
    {
        // print_r($this->request->getPost());exit;
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $result = $this->adminModel->isValidLogin(libsodiumEncrypt($email), $password);
        if (!empty($result) && $result != 1 && $result != 2) {
            $session_data = array('admin_id' => $result['id'], 'islogged' => 1);
            $this->session->set($session_data);

            // $this->session->set('admin_id',$result['id']);
            $this->session->getFlashdata('user_id');
            $this->session->getFlashdata('role');
            return redirect('admin/dashboard');
        } else if ($result == 1) {
            //$this->session->set_flashdata('error_message','Wrong login credentials.');
            $this->session->setFlashdata('error_message', 'Invalid Email');
        } else if ($result == 2) {
            $this->session->setFlashdata('error_message', 'Invalid Password');
        }
        return redirect($this->data['theme']);
    }

    /**
     * admin logout.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        $this->session->destroy();
        $this->session->setFlashdata('success_message', 'Logged out successfully');
        //return redirect()->to('admin');
        return redirect("admin");
    }
}
