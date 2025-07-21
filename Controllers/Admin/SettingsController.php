<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CommonModel;
use App\Models\SettingsModel;

class SettingsController extends BaseController
{
    public $data;
    public $session;
    /**
     * @var \App\Models\UserModel
     */
    public $userModel;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    public $language;
    /**
     * @var \App\Models\SettingsModel
     */
    public $settingsModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'settings';
        $this->data['page'] = '';

        $lan = helper('default_language');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->userModel = new UserModel();
        $this->commonModel = new CommonModel();
        $this->settingsModel = new SettingsModel();
    }
    /**
     * Settings Page.
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        $results = $this->getSettingList();
        // echo "<pre>";print_r($results);exit;
        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }
        echo view('admin/settings/index', $this->data);
    }
    /**
     * Get Settings List.
     * 
     * @return mixed
     */
    public function getSettingList()
    {
        $data = array();
        $data = $this->settingsModel->getSettingList();
        return $data;
    }
    /**
     * Settings Submit.
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function settingsSubmit()
    {
        $data = $this->request->getPost();
        $table_data['system']           = 1;
        $table_data['groups']           = 'config';
        $table_data['update_date']      = date('Y-m-d');
        $table_data['status']           = 1;
        if ($_FILES['site_logo']['name']) {
            if (!is_dir('./uploads/logo')) {
                mkdir('./uploads/logo', 0777, TRUE);
            }
            $file = $this->request->getFile('site_logo');
            $site_logo = $file->getRandomName();
            $file->move('uploads/logo', $site_logo);

            $img_uploadurl      = 'uploads/logo/' . $_FILES['site_logo']['name'];
            $key = 'logo_front';
            // $val = $this->image_resize(447,268,$image_url,$image_name);  
            $val =   'uploads/logo/' . $site_logo;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            if (count($select_logo) > 0) {
                $this->commonModel->updateData('system_settings', array('key' => $key), array('value' => $val));
            } else {
                $table_data['key']        = $key;
                $table_data['value']      = $val;
                $this->commonModel->insertData('system_settings', $table_data);
            }
        }
        if ($_FILES['footer_logo']['name']) {

            if (!is_dir('./uploads/logo')) {
                mkdir('./uploads/logo', 0777, TRUE);
            }
            $file = $this->request->getFile('footer_logo');
            $footer_logo = $file->getRandomName();
            $file->move('uploads/logo', $footer_logo);

            $img_uploadurl      = 'uploads/logo/' . $_FILES['site_logo']['name'];
            $key = 'logo_footer';
            $val =   'uploads/logo/' . $footer_logo;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            $img_uploadurl      = 'uploads/logo/' . $_FILES['footer_logo']['name'];
            // $val = $this->image_resize(447,268,$image_url,$image_name);  
            $val =   'uploads/logo/' . $footer_logo;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            if (count($select_logo) > 0) {
                $this->commonModel->updateData('system_settings', array('key' => $key), array('value' => $val));
            } else {
                $table_data['key']        = $key;
                $table_data['value']      = $val;
                $this->commonModel->insertData('system_settings', $table_data);
            }
        }
        // }
        if ($_FILES['apns_pem_file']['name']) {
            if (!is_dir('./uploads/apns_pem_file')) {
                mkdir('./uploads/apns_pem_file', 0777, TRUE);
            }
            $file = $this->request->getFile('apns_pem_file');
            $apns_pem_file = $file->getRandomName();
            $file->move('uploads/apns_pem_file', $apns_pem_file);

            $img_uploadurl      = 'uploads/apns_pem_file/' . $_FILES['site_logo']['name'];
            $key = 'apns_pem_file';
            $val =   'uploads/apns_pem_file/' . $apns_pem_file;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            $img_uploadurl      = 'uploads/apns_pem_file/' . $_FILES['apns_pem_file']['name'];
            // $val = $this->image_resize(447,268,$image_url,$image_name);  
            $val =   'uploads/apns_pem_file/' . $apns_pem_file;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            if (count($select_logo) > 0) {
                $this->commonModel->updateData('system_settings', array('key' => $key), array('value' => $val));
            } else {
                $table_data['key']        = $key;
                $table_data['value']      = $val;
                $this->commonModel->insertData('system_settings', $table_data);
            }
        }

        if ($_FILES['favicon']['name']) {
            if (!is_dir('./uploads/logo')) {
                mkdir('./uploads/logo', 0777, TRUE);
            }
            $file = $this->request->getFile('favicon');
            $favicon = $file->getRandomName();
            $file->move('uploads/logo', $favicon);

            $img_uploadurl      = 'uploads/logo/' . $_FILES['favicon']['name'];
            $key = 'favicon';
            $val =   'uploads/logo/' . $favicon;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            $img_uploadurl      = 'uploads/logo/' . $_FILES['favicon']['name'];
            // $val = $this->image_resize(447,268,$image_url,$image_name);  
            $val =   'uploads/logo/' . $favicon;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            if (count($select_logo) > 0) {
                $this->commonModel->updateData('system_settings', array('key' => $key), array('value' => $val));
            } else {
                $table_data['key']        = $key;
                $table_data['value']      = $val;
                $this->commonModel->insertData('system_settings', $table_data);
            }
        }
        if ($data) {
            $table_data = [];
            foreach ($data as $key => $val) {
                if ($key != 'form_submit' || $key != 'favicon' || $key != 'logo_front') {
                    $this->commonModel->deleteData('system_settings', array('key' => $key));

                    if ($key == 'apiKey' || $key == 'apiSecret' || $key == 'tiwilio_apiSecret' || $key == 'sendgrid_apikey' || $key == 'google_map_api' || $key =='email_address' || $key == 'smtp_user' || $key == 'smtp_pass')
                        $table_data['value']      = libsodiumEncrypt($val);
                    else
                        $table_data['value']      = $val;

                    $table_data['key']        = $key;
                    $table_data['system']      = 1;
                    $table_data['groups']      = 'config';
                    $table_data['update_date']  = date('Y-m-d');
                    $table_data['status']       = 1;
                    $this->commonModel->insertData('system_settings', $table_data);
                } else {
                }
            }
        } else {
        }
        $message = 'Settings are saved successfully.';
        // session()->setFlashdata('success_message', $message);

        return redirect('admin/' . $this->data['module']);
    }
}
