<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class PrivacyPolicy extends BaseController
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
        $this->data['module'] = 'privacy_policy';
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
        $this->data['privacy_language'] = $privacy_lang = !empty(settings("privacy_language")) ? settings("privacy_language") : "";
        $this->data['privacy_policy'] = $this->commonModel->getTblRowOfData('privacy_policy', ['language' => $privacy_lang], '*');
        $this->data['page'] = 'index';
        return view('admin/privacy_policy/index', $this->data);
    }

    public function change_language() {
        $privacypolicy_lang = $this->request->getPost('lang');
        $privacy_policy = $this->data['privacy_policy'] = $this->commonModel->getTblRowOfData('privacy_policy', ['language' => $privacypolicy_lang], '*');
        if (!empty($privacy_policy)) {
            $response['content'] = $privacy_policy['content'];
            $response['id'] = $privacy_policy['id'];
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
           
            $privacy_language = $data['privacy_language'];
            $check_exists = $this->commonModel->getTblRowOfData('system_settings', ['key' => 'privacy_language'], '*');
            if ($check_exists) {
                $this->commonModel->updateData('system_settings', array('key' => 'privacy_language'), array('value' => $privacy_language,'update_date' => date('Y-m-d')));
            } else {
                $table_data['key']        = 'privacy_language';
                $table_data['value']      = $privacy_language;
                $table_data['update_date'] = date('Y-m-d');
                $this->commonModel->insertData('system_settings', $table_data);
            }
            $policy_data['content'] = $data['content'];
            $policy_data['language'] = $privacy_language;
            if(!empty($data['id'])) {
                if ($this->commonModel->updateData('privacy_policy', ['id' => $data['id']], $policy_data)) {
                    session()->setFlashdata('success_message', 'Privacy policy updated Successfully');
                    return redirect()->to('privacypolicy');
                }
            } else {
                $this->commonModel->insertData('privacy_policy', $policy_data);
                session()->setFlashdata('success_message', 'Privacy policy updated Successfully');
                return redirect()->to('privacypolicy');
            }
        }
    }
}
