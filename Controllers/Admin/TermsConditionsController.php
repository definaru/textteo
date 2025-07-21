<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class TermsConditionsController extends BaseController
{
    public $data;
    public $session;
    public $language;
    public $db;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'terms_conditions';
        $this->data['page'] = '';
        $this->data['uri'] = service('uri');

        $lan = helper('default_language');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->commonModel = new CommonModel();

        helper('ckeditor');
        // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor1'] = array(
            //id of the textarea being replaced by CKEditor
            'id'   => 'ck_editor_textarea_id',
            // CKEditor path from the folder on the root folder of CodeIgniter
            'path' => 'assets/js/ckeditor',
            // optional settings
            'config' => array(
                'toolbar' => "Full",
                'filebrowserBrowseUrl'      => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl'      => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );
    }

    public function index()
    {
        $this->data['language'] = $this->commonModel->getTblResultOfData('language', ['status' => 1], '*');
        $this->data['terms_language'] = $terms_lang = !empty(settings("terms_language")) ? settings("terms_language") : "";
        $this->data['terms_conditions'] = $this->commonModel->getTblRowOfData('terms_conditions', ['language' => $terms_lang], '*');
        $this->data['page'] = 'index';
        return view('admin/terms_conditions/index', $this->data);
    }

    public function change_language() {
        $terms_lang = $this->request->getPost('lang');
        $terms_conditions = $this->data['terms_conditions'] = $this->commonModel->getTblRowOfData('terms_conditions', ['language' => $terms_lang], '*');
        if (!empty($terms_conditions)) {
            $response['content'] = $terms_conditions['content'];
            $response['id'] = $terms_conditions['id'];
            $response['status'] = 200;
        } else {
            $response['content'] = '';
            $response['id'] = '';
            $response['status'] = 404;
        }
        echo json_encode($response);
    }

    public function update() {
        if ($this->request->getPost('form_submit')) {
            $data = $this->request->getPost();
           
            $terms_language = $data['terms_language'];
            $check_exists = $this->commonModel->getTblRowOfData('system_settings', ['key' => 'terms_language'], '*');
            if ($check_exists) {
                $this->commonModel->updateData('system_settings', array('key' => 'terms_language'), array('value' => $terms_language,'update_date' => date('Y-m-d')));
            } else {
                $table_data['key']        = 'terms_language';
                $table_data['value']      = $terms_language;
                $table_data['update_date'] = date('Y-m-d');
                $this->commonModel->insertData('system_settings', $table_data);
            }
            $terms_data['content'] = $data['content'];
            $terms_data['language'] = $terms_language;
            if(!empty($data['id'])) {
                if ($this->commonModel->updateData('terms_conditions', ['id' => $data['id']], $terms_data)) {
                    session()->setFlashdata('success_message', 'Terms and condition updated Successfully');
                    return redirect()->to('termsandconditions');
                }
            } else {
                $this->commonModel->insertData('terms_conditions', $terms_data);
                session()->setFlashdata('success_message', 'Terms and condition updated Successfully');
                return redirect()->to('termsandconditions');
            }
        }
    }
}
