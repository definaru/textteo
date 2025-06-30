<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CommonModel;
use App\Models\SettingsModel;

class CmsController extends BaseController
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
        $this->data['module'] = 'cms';
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
     * load CMS page.
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
        echo view('admin/cms/index', $this->data);
    }
    /**
     *  Get Setting List.
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
     * CMS Submit.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function cmsSubmit()
    {
        $data = $this->request->getPost();

        $table_data['system']           = 1;
        $table_data['groups']           = 'config';
        $table_data['update_date']      = date('Y-m-d');
        $table_data['status']           = 1;

        //banner image
        if ($_FILES['banner_image']['name']) {
            $file = $_FILES["banner_image"]['tmp_name'];
            list($width, $height) = getimagesize($file);

            if ($width < "1600" || $height < "210") {

                $message = 'Please upload above 1600x210 banner image size';
                session()->setFlashdata('error_message', $message);

                return redirect('admin/' . $this->data['module']);
            }

            if (!is_dir('./uploads/banner')) {
                mkdir('./uploads/banner', 0777, TRUE);
            }
            $file = $this->request->getFile('banner_image');
            $banner_image = $file->getRandomName();
            $file->move('uploads/banner', $banner_image);

            $img_uploadurl      = 'uploads/banner/' . $_FILES['banner_image']['name'];
            $key = 'banner_image';
            $val =   'uploads/banner/' . $banner_image;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            if (count($select_logo) > 0) {
                $this->commonModel->updateData('system_settings', array('key' => $key), array('value' => $val));
            } else {
                $table_data['key']        = $key;
                $table_data['value']      = $val;
                $this->commonModel->insertData('system_settings', $table_data);
            }
        }

        //feature image
        if ($_FILES['feature_image']['name']) {
            $file = $_FILES["feature_image"]['tmp_name'];
            list($width, $height) = getimagesize($file);

            if ($width < "421" || $height < "376") {
                $message = 'Please upload above 421x376 banner image size';
                session()->setFlashdata('error_message', $message);

                return redirect('admin/' . $this->data['module']);
            }

            if (!is_dir('./uploads/feature_image')) {
                mkdir('./uploads/feature_image', 0777, TRUE);
            }
            $file = $this->request->getFile('feature_image');
            $feature_image = $file->getRandomName();
            $file->move('uploads/feature_image', $feature_image);

            $img_uploadurl      = 'uploads/feature_image/' . $_FILES['feature_image']['name'];
            $key = 'feature_image';
            $val =   'uploads/feature_image/' . $feature_image;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            if (count($select_logo) > 0) {
                $this->commonModel->updateData('system_settings', array('key' => $key), array('value' => $val));
            } else {
                $table_data['key']        = $key;
                $table_data['value']      = $val;
                $this->commonModel->insertData('system_settings', $table_data);
            }
        }

        //login image
        if ($_FILES['login_image']['name']) {
            $file = $_FILES["login_image"]['tmp_name'];
            list($width, $height) = getimagesize($file);

            if ($width < "1000" || $height < "650") {
                $message = 'Please upload above 1000x650 banner image size';
                session()->setFlashdata('error_message', $message);
                return redirect('admin/' . $this->data['module']);
            }



            if (!is_dir('./uploads/login_image')) {
                mkdir('./uploads/login_image', 0777, TRUE);
            }
            $file = $this->request->getFile('login_image');
            $login_image = $file->getRandomName();
            $file->move('uploads/login_image', $login_image);

            $img_uploadurl      = 'uploads/login_image/' . $_FILES['login_image']['name'];
            $key = 'login_image';
            $val =   'uploads/login_image/' . $login_image;
            $select_logo = $this->settingsModel->getSettingByKey($key);
            if (count($select_logo) > 0) {
                $this->commonModel->updateData('system_settings', array('key' => $key), array('value' => $val));
            } else {
                $table_data['key']        = $key;
                $table_data['value']      = $val;
                $this->commonModel->insertData('system_settings', $table_data);
            }
        }

        // if($data){
        //     $table_data=[];
        //     foreach ($data AS $key => $val) {
        //     if($key!='form_submit' || $key!='favicon' || $key!='logo_front' ){
        //         $this->commonModel->deleteData('system_settings', array('key'=> $key));

        //         if($key=='apiKey' || $key=='apiSecret' || $key=='tiwilio_apiSecret' || $key=='sendgrid_apikey' || $key=='google_map_api')
        //             $table_data['value']      = libsodiumEncrypt($val);
        //         else
        //             $table_data['value']      = $val;

        //         $table_data['key']        = $key;
        //         $table_data['system']      = 1;
        //         $table_data['groups']      = 'config';
        //         $table_data['update_date']  = date('Y-m-d');
        //         $table_data['status']       = 1;
        //         $this->commonModel->insertData('system_settings', $table_data);

        //     }else{}
        //     }
        // } else{}                        


        $message = 'Settings are saved successfully.';
        session()->setFlashdata('success_message', $message);

        return redirect('admin/' . $this->data['module']);
    }
}
