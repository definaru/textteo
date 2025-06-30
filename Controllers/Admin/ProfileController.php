<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommonModel;

class ProfileController extends BaseController
{
    public $data;
    public $db;
    public $session;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;
    public $language;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);
        $this->session = \Config\Services::session();

        // Declare page detail
        $this->data['theme'] = 'admin';
        $this->data['module'] = 'profile';
        $this->data['page'] = '';

        $lan = helper('default_language');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->commonModel = new CommonModel();
    }
    /**
     * load Profile page.
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        $this->data['profile'] = $this->commonModel->getTblRowOfData('administrators', array('id' => session('admin_id')), '*');
        $result = $this->commonModel->getTblResultOfData('administrators', array('id' => session('admin_id')), '*');
        foreach ($result as $rows) {
            $country = $rows['country'];
            $countryid = get_countryid($country);
            $city_of_country = get_city_of_country($countryid);
            $this->data['city_of_country'] = $city_of_country;
        }
        echo view('admin/profile/index', $this->data);
    }
    /**
     * Check Current Password.
     *
     * @return mixed
     */
    public function checkCurrentpassword()
    {
        $id = session('admin_id');
        $password = $this->request->getPost('currentpassword');
        $result = $this->isValidPassword($id, $password);
        if ($result > 0) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
    /**
     * isValid Password.
     *
     * @param int $id
     * @param mixed $password
     * @return mixed
     */
    public function isValidPassword($id, $password)
    {
        $result = $this->commonModel->getTblRowOfData('administrators', array('id' => $id, 'password' => md5($password)), 'id,email');
        return $result;
    }
    /**
     * Check New Password.
     *
     * @return mixed
     */
    public function checkNewpassword()
    {
        $id = session('admin_id');
        $password = $this->request->getPost('password');
        $result = $this->isValidPassword($id, $password);
        if ($result > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    /**
     * Change Password.
     *
     * @return mixed
     */
    public function changePassword()
    {
        $inputdata = array();
        $response = array();

        $currentpassword = trim($this->request->getPost('currentpassword') ?? "");
        $inputdata['password'] = trim(md5($this->request->getPost('password') ?? ""));

        if ($currentpassword != '') {
            $result = $this->commonModel->updateData('administrators', array('id' => session('admin_id')), $inputdata);
            if ($result == true) {

                $response['msg'] = 'Password update successfully';
                $response['status'] = 200;
            } else {
                $response['msg'] = 'Password update failed';
                $response['status'] = 500;
            }
        } else {
            $response['msg'] = 'Please enter current password';
            $response['status'] = 500;
        }



        echo json_encode($response);
    }
    /**
     * Update Profile.
     *
     * @return mixed
     */
    public function updateProfile()
    {
        $inputdata = array();
        $response = array();

        $inputdata['name'] = libsodiumEncrypt($this->request->getPost('name'));
        $inputdata['email'] = libsodiumEncrypt($this->request->getPost('email'));
        $inputdata['country'] = $this->request->getPost('country');
        $inputdata['city'] = $this->request->getPost('city');
        $inputdata['biography'] = libsodiumEncrypt($this->request->getPost('biography'));

        $result = $this->commonModel->updateData('administrators', array('id' => session('admin_id')), $inputdata);
        if ($result == true) {

            $response['msg'] = 'Profile update successfully';
            $response['status'] = 200;
        } else {
            $response['msg'] = 'Profile update failed';
            $response['status'] = 500;
        }

        echo json_encode($response);
    }
    /**
     * Crop Profile Image.
     *
     * @param string $prev_img
     * @return mixed
     */
    public function cropProfileImg($prev_img = '')
    {
        $prev_img = $this->request->getPost('curprofileimage');
        $max_execution_time = 3000;
        /** @var string $max_execution_time */
        ini_set('max_execution_time', $max_execution_time);

        ini_set('memory_limit', '-1');

        if (!empty($prev_img)) {

            $file_path = FCPATH . $prev_img;

            if (file_exists($file_path)) {

                unlink(FCPATH . $prev_img);
            }
        }

        $error_msg       = '';

        $av_src          = $this->request->getPost('avatar_src');

        $av_data         = json_decode($this->request->getPost('avatar_data') ?? "", true);

        $av_file         = $_FILES['avatar_file'];

        $src             = 'uploads/profileimage/' . $av_file['name'];

        $imageFileType   = pathinfo($src, PATHINFO_EXTENSION);

        $image_name     = time() . '.' . $imageFileType;

        $src2            = 'uploads/profileimage/temp/' . $image_name;

        move_uploaded_file($av_file['tmp_name'], $src2);


        $ref_path = '/uploads/profileimage/temp/';

        $image1          = $this->crop_images($image_name, $av_data, 200, 200, "/uploads/profileimage/", $ref_path);

        $rand = rand(100, 999);

        $inputdata = array();
        $inputdata['profileimage'] = 'uploads/profileimage/' . $image_name;

        $this->commonModel->updateData('administrators', array('id' => session('admin_id')), $inputdata);

        $response = array(

            'state'  => 200,

            'message' => $error_msg,

            'result' => 'uploads/profileimage/' . $image_name,

            'img_name1' => $image_name

        );
        echo json_encode($response);
    }
    /**
     * Crop Profile Image.
     *
     * @param string $image_name
     * @param string $path
     * @param string $ref_path
     * @param mixed $av_data
     * @param int $t_width
     * @param int $t_height
     * @return mixed
     */
    public function crop_images($image_name, $av_data, $t_width, $t_height, $path, $ref_path)
    {

        $w                 = $av_data['width'];

        $h                 = $av_data['height'];

        $x1                = $av_data['x'];

        $y1                = $av_data['y'];
        $source = "";

        list($imagewidth, $imageheight, $imageType) = getimagesize(FCPATH . $ref_path . $image_name);

        $imageType                                  = image_type_to_mime_type($imageType);

        $ratio             = ($t_width / $w);

        $nw                = ceil($w * $ratio);

        $nh                = ceil($h * $ratio);

        $newImage          = imagecreatetruecolor($nw, $nh);
        $backgroundColor = imagecolorallocate($newImage, 0, 0, 0);
        imagefill($newImage, 0, 0, $backgroundColor);
        $black = imagecolorallocate($newImage, 0, 0, 0);
        // Make the background transparent
        imagecolortransparent($newImage, $black);
        switch ($imageType) {

            case "image/gif":
                $source = imagecreatefromgif(FCPATH . $ref_path . $image_name);

                break;

            case "image/pjpeg":

            case "image/jpeg":

            case "image/jpg":
                $source = imagecreatefromjpeg(FCPATH . $ref_path . $image_name);

                break;

            case "image/png":

            case "image/x-png":
                $source = imagecreatefrompng(FCPATH . $ref_path . $image_name);

                break;
        }

        imagecopyresampled($newImage, $source, 0, 0, $x1, $y1, $nw, $nh, $w, $h);

        switch ($imageType) {

            case "image/gif":
                imagegif($newImage, FCPATH . $path . $image_name);

                break;

            case "image/pjpeg":

            case "image/jpeg":

            case "image/jpg":
                imagejpeg($newImage, FCPATH . $path . $image_name, 100);

                break;

            case "image/png":

            case "image/x-png":
                imagepng($newImage, FCPATH . $path . $image_name);

                break;
        }
    }
}
