<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommonModel;
use App\Libraries\SendEmail;
use App\Models\AdminModel;
use Config\Services;

class AjaxController extends BaseController
{
  public mixed $data;
  public mixed $session;
  public mixed $timezone;
  public mixed $input;
  protected mixed  $db;
  public mixed $uri;
  public mixed $language;
  /**
   * @var \App\Models\CommonModel
   */
  public $commonModel;
  /**
   * @var \App\Models\AdminModel
   */
  public $adminModel;

  public function __construct()
  {
    $this->data['base_url'] = base_url();

    $this->db = \Config\Database::connect();

    $this->timezone = session('time_zone');
    if (!empty($this->timezone)) {
      date_default_timezone_set($this->timezone);
    }
    $default_language = default_language();
    $lang = session('locale') ?? $default_language['language_value'];
    $this->data['language'] = lang('content_lang.language', [], $lang);
    $this->language = lang('content_lang.language', [], $lang);

    //Define Model
    $this->commonModel = new CommonModel();
    $this->adminModel = new AdminModel();
  }
  /**
   *  Check Email.
   * 
   * 
   * @return mixed
   */
  public function checkEmail()
  {
    $email = strtolower(trim($this->request->getPost('email') ?? ""));
    if (strlen($email) == 0) {
      echo 'false';
      die;
    }
    $user_id = $this->request->getPost('user_id') ? $this->request->getPost('user_id') : 0;
    if ($user_id === 0) {
      $user_id = session('user_id');
    }
    if (!empty(session('user_id'))) {
      $whereNotIn = [
        'column_name' => 'id<>',
        'value' => $user_id
      ];
    } else {
      $whereNotIn = [];
    }
    $result = $this->commonModel->checkTblDataExist('users', ['email' => libsodiumEncrypt($email)], 'id', $whereNotIn);
    if ($result > 0) {
      echo 'false';
    } else {
      echo 'true';
    }
  }
  /**
   *  Check Blog Slug.
   * 
   * 
   * @return mixed
   */
  public function checkBlogSlug()
  {
    $slug = strtolower(trim($this->request->getPost('slug') ?? ""));

    $post_id = $this->request->getPost('post_id') ? $this->request->getPost('post_id') : 0;
    if ($post_id === 0) {
      $result = $this->commonModel->checkTblDataExist('posts', ['slug' => libsodiumEncrypt($slug)], 'id', []);
    } else {
      $result = $this->commonModel->checkTblDataExist('posts', ['slug' => libsodiumEncrypt($slug), 'id!=' => $post_id], 'id');
    }

    if ($result > 0) {
      echo 'false';
    } else {
      echo 'true';
    }
  }
  /**
   *  Register Email.
   * 
   * 
   * @return mixed
   */
  public function registerEmail()
  {
    $email = strtolower(trim($this->request->getPost('email') ?? ""));
    if (strlen($email) == 0) {
      echo 'false';
      die;
    }
    $user_id = $this->request->getPost('user_id') ? $this->request->getPost('user_id') : 0;
    if ($user_id === 0) {
      $user_id = session('user_id');
    }
    if (!empty(session('user_id'))) {
      $whereNotIn = [
        'column_name' => 'id<>',
        'value' => $user_id
      ];
    } else {
      $whereNotIn = [];
    }
    $result = $this->commonModel->checkTblDataExist('users', ['email' => libsodiumEncrypt($email)], 'id', $whereNotIn);
    if ($result > 0) {
      echo 'true';
    } else {
      echo 'false';
    }
  }
  // public function checkMobNo()
  // {
  //     $mobileno = trim($this->request->getPost('mobileno'));
  //     if(strlen($mobileno)==0)
  //     {
  //       echo false;
  //       die;
  //     }
  //     $result = $this->commonModel->checkTblDataExist('users',['mobileno'=>libsodiumEncrypt($mobileno)], 'id');
  //     if ($result > 0) 
  //     {
  //       echo 'false';
  //     } 
  //     else 
  //     {
  //       echo 'true';
  //     }    
  // }
  /**
   *  Check Mobile No.
   * 
   * 
   * @return mixed
   */
  public function checkMobNo()
  {
    $checkall = !empty($this->request->getPost('checkall')) ? $this->request->getPost('checkall') : false;
    $mobileno = trim($this->request->getPost('mobileno') ?? "");
    $user_id = $this->request->getPost('id');
    if (strlen($mobileno) == 0) {
      echo false;
      die;
    }
    // if($checkall==false && !empty(session('user_id'))){
    // $whereNotIn = [
    //     'column_name' => 'id<>', 
    //     'value' => session('user_id')
    // ];
    // }
    // else{
    //   $whereNotIn=[];
    // }
    if (!empty($user_id)) {
      $whereNotIn = [
        'column_name' => 'id<>',
        'value' => $user_id
      ];
    } else {
      $whereNotIn = [];
    }
    $result = $this->commonModel->checkTblDataExist('users', ['mobileno' => libsodiumEncrypt($mobileno)], 'id', $whereNotIn);
    if ($result > 0) {
      echo 'false';
    } else {
      echo 'true';
    }
  }
  /**
   *  Set TimeZone.
   * 
   * 
   * @return mixed
   */
  public function setTimeZone()
  {
    if (isset($_REQUEST['timezone'])) {
      $array = array('time_zone' => $_REQUEST['timezone']);
      session()->set($array);
      echo json_encode($array);
    }
  }
  /**
   * Currency Rate.
   * 
   * 
   * @return mixed
   */
  public function currencyRate()
  {
    $count_row = $this->commonModel->countTblResult('currency_rate', []);
    if ($count_row == 0) {
      //    $this->currencyRateUpdate();
    } else {
      $row = $this->commonModel->getTblRowOfData('currency_rate', [], '*');
      $date = date('Y-m-d H:i:s');

      if ($row['updated_at'] < $date) {
        $this->currencyRateUpdate();
      }
    }
  }
  /**
   * Currency Rate Update.
   * 
   * 
   * @return mixed
   */
  public function currencyRateUpdate()
  {
    $req_url = 'https://v6.exchangerate-api.com/v6/cc726126438b5513e5a41f69/latest/USD';
    // $response_json = file_get_contents($req_url);
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $req_url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
    $response_json = curl_exec($curl_handle);
    curl_close($curl_handle);

    // Continuing if we got a result
    if (false !== $response_json) {
      // Try/catch for json_decode operation
      try {
        $response = json_decode($response_json);
        if ('success' === $response->result) {
          foreach ($response->conversion_rates as $key => $value) {
            $count = $this->commonModel->countTblResult('currency_rate', array('currency_code' => $key));
            if ($count == 0) {
              $data = array(
                'currency_code' => $key,
                'rate' => $value,
                'created_at' => date('Y-m-d H:i:s')
              );
              $this->commonModel->insertData('currency_rate', $data);
            } else {
              $data = array(
                'rate' => $value,
                'updated_at' => date('Y-m-d H:i:s')
              );
              $this->commonModel->updateData('currency_rate', array('currency_code' => $key), $data);
            }
          }
          echo "success";
        }
      } catch (\Exception $e) {
        echo 'Caught exception: ',  $e->getMessage();
      }
    }
  }

  /**
   * Change Currecny Rate
   * 
   * @return mixed
   */
  public function addUserCurrency()
  {
    $params = $this->request->getPost();
    if (!empty($params['code'])) {
      $user_id = session('user_id');
      $user_detail = user_detail(session('user_id'));
      if ($user_detail['is_updated'] == '0') {
        echo json_encode(['success' => false, 'msg' => 'Please update profile']);
        exit;
      }

      $where = array('user_id' => $user_id);
      $user_details = $this->commonModel->getTblRowOfData('users_details', $where, 'amount,currency_code');
      $current_amt = get_doccure_currency($user_details['amount'], $user_details['currency_code'], $params['code']);

      $data = array(
        'currency_code' => $params['code'],
        'amount' => $current_amt
      );
      $result = $this->commonModel->updateData('users_details', array('user_id' => $user_id), $data);
      if ($result == true) {
        echo json_encode(['success' => true, 'msg' => 'success']);
        exit;
      } else {
        echo json_encode(['success' => false, 'msg' => 'please try again']);
        exit;
      }
    }
  }

  /**
   * Update User Status.
   * 
   * @return mixed
   */
  public function updateUserStatus()
  {
    if (session('user_id')) {
      $user_id = session('user_id');
      $data = array(
        'date_time' => date('Y-m-d H:i:s'),
        'time_zone' => $this->timezone,
        'user_id' => $user_id
      );

      $count = $this->commonModel->countTblResult('user_online_status', array('user_id' => $user_id));

      if ($count > 0) {
        $this->commonModel->updateData('user_online_status', array('user_id' => $user_id), $data);
      } else {
        $this->commonModel->insertData('user_online_status', $data);
      }
    }
  }
  /**
   * User Email Verify
   * 
   * @return mixed
   */
  public function userEmailVerification()
  {
    $user_detail = user_detail(session('user_id'));
    $user_detail['id'] = session('user_id');
    $sendmail = new \App\Libraries\SendEmail;
    $sendmail->send_email_verification($user_detail);
  }
  /**
   * Get Country Code
   * 
   * @return mixed
   */
  public function getCountryCode()
  {
    $result = $this->commonModel->getTblResultOfData('country', [], 'countryid,phonecode,country');
    $json = array();
    foreach ($result as $rows) {
      $data['countryid'] = $rows['countryid'];
      $data['value'] = $rows['phonecode'];
      $data['label'] = $rows['country'] . '(+' . $rows['phonecode'] . ')';
      $json[] = $data;
    }
    echo json_encode($json);
  }
  /**
   * Get Country.
   * 
   * @return mixed
   */
  public function getCountry()
  {
    $result = $this->commonModel->getTblResultOfData('country', [], '*');
    $json = array();
    foreach ($result as $rows) {
      $data['value'] = $rows['countryid'];
      $data['label'] = $rows['country'];
      $json[] = $data;
    }
    echo json_encode($json);
  }
  /**
   * Get Sate.
   * 
   * @return mixed
   */
  public function getState()
  {
    $result = $this->commonModel->getTblResultOfData('state', ['countryid' => $_POST['id']], '*');
    $json = array();
    foreach ($result as $rows) {
      $data['value'] = $rows['id'];
      $data['label'] = $rows['statename'];
      $json[] = $data;
    }
    echo json_encode($json);
  }
  /**
   * Get City.
   * 
   * @return mixed
   */
  public function getCity()
  {
    $result = $this->commonModel->getTblResultOfData('city', ['stateid' => $_POST['id']], '*');
    $json = array();
    foreach ($result as $rows) {
      $data['value'] = $rows['id'];
      $data['label'] = $rows['city'];
      $json[] = $data;
    }
    echo json_encode($json);
  }
  /**
   * Get Specialization.
   * 
   * @return mixed
   */
  public function getSpecialization()
  {
    $result = $this->commonModel->getTblResultOfData('specialization', ['status' => 1], '*');
    $json = array();
    foreach ($result as $rows) {
      $data['value'] = $rows['id'];
      $data['label'] = libsodiumDecrypt($rows['specialization']);
      $json[] = $data;
    }
     // Add "Others" option at the end
     $json[] = [
      'value' => 'others',
      'label' => 'Others'
    ];
    echo json_encode($json);
  }
  /**
   * Set Language.
   * 
   * @return mixed
   */
  public function setLanguage()
  {
    if (isset($_REQUEST['lang'])) {
      $array = array(
        'locale' => $_REQUEST['lang'],
        'lang' => $_REQUEST['lang'],
        'language' => $_REQUEST['language'],
      );
      session()->set('locale', $_REQUEST['lang']);
      session()->set($array);
      echo json_encode($array);
    }
  }
  /**
   * Delete Clinic Image.
   * 
   * @return mixed
   */
  public function deleteClinicImg()
  {
    $id = $this->request->getPost('id');
    $where = array('id' => $id);
    $image = $this->commonModel->getTblRowOfData('clinic_images', $where, 'clinic_image');
    if ($this->commonModel->deleteData('clinic_images', $where)) {
      $file_path = FCPATH . ($image ? $image['clinic_image'] : "");
      if (is_file($file_path)) {
        unlink($file_path);
      }
    }
    $response['msg'] = $this->language['lg_image_deleted_s'];
    $response['status'] = 200;
    echo json_encode($response);
  }
  /**
   * Send Mail.
   * 
   * @return mixed
   */
  public function sendMail()
  {
    // $email_templates=email_template(2);
    // $body=$email_templates['template_content'];
    // $subject=$email_templates['template_subject'];

    // $body = str_replace('{{site_url}}', base_url(), $body);
    // $body = str_replace('{{site_logo}}',!empty(base_url().settings("logo_front"))?base_url().settings("logo_front"):base_url()."assets/img/logo.png", $body);
    // $body = str_replace('{{user_name}}', ucfirst($data['first_name'].' '.$data['last_name']), $body);
    // $body = str_replace('{{website_name}}', !empty(settings("website_name"))?settings("website_name"):"Doccure", $body);
    // $body = str_replace('{{email}}', $data['email'], $body);
    // $body = str_replace('{{reset_url}}', base_url().'reset/'.$data['url'], $body);
    // $body = str_replace('{{date}}', date('Y'), $body);

    // $message=$body;

    // $email = \Config\Services::email();

    // $email->setFrom('nithya.t@dreamguystech.com', 'Your Name');
    // $email->setTo('gowthamkumar.ramakrishnan@dreamguystech.com');
    // $email->setSubject('Test Email');
    // $email->setMessage('This is a test email.');

    // if ($email->send()) {
    //     echo 'Email sent successfully';
    // } else {
    //     echo $email->printDebugger(['headers']);
    // }

    $sendMail = new SendEmail(); // create an instance of Library
    $data['first_name'] = "Gowtham";
    $data['last_name'] = "Kumar";
    $data['email'] = "gowthamkumar.ramakrishnan@dreamguystech.com";
    $data['url'] = "gowthamkumar.ramakrishnan@dreamguystech.com";

    echo $sendMail->sendResetPasswordEmail($data); // calling method

  }

  /**
   * Delete Specialization.
   * 
   * @return mixed
   */
  public function deleteUser()
  {
    $id = $this->request->getPost('id');
    $table_name = $this->request->getPost('delete_table');

    $data = array(
      'status' => 0,
    );
    $this->commonModel->updateData($table_name, array('id' => $id), $data);
    echo 1;
  }

  public function encrypt($type)
  {
    echo '--' . libsodiumDecrypt($type) . '--';
    // echo encryptor_decryptor('encrypt',$type);
  }
  /**
   * Get Category.
   * 
   * 
   * @return mixed
   */
  public function getCategory()
  {
    $where = array('status' => 1);
    $result = $this->commonModel->getTblResultOfData('categories', $where, '*');

    $json = array();
    foreach ($result as $rows) {
      $data['value'] = $rows['id'];
      $data['label'] = libsodiumDecrypt($rows['category_name']);
      $json[] = $data;
    }

    echo json_encode($json);
  }
  /**
   * Get Sub Category.
   * 
   * @param mixed $id
   * @return mixed
   */
  public function getSubCategory($id = '0')
  {
    if ($id > 0) {
      $where = array('status' => 1, 'category' => $id);
      $result = $this->commonModel->getTblResultOfData('subcategories', $where, '*');

      $json = array();
      foreach ($result as $rows) {
        $data['value'] = $rows['id'];
        $data['label'] = libsodiumDecrypt($rows['subcategory_name']);
        $json[] = $data;
      }
    } else {
      $json = array();
      $data['value'] = 0;
      $data['label'] = 'Select Subcategory';
      $json[] = $data;
    }

    echo json_encode($json);
  }
  /**
   * List Of product category
   * 
   * 
   * @return mixed
   */
  public function getProductCategory()
  {
    $where = array('status' => 1);
    $result = $this->commonModel->getTblResultOfData('product_categories', $where, '*');

    $json = array();
    foreach ($result as $rows) {
      $data['value'] = $rows['id'];
      $data['label'] = libsodiumDecrypt($rows['category_name']);
      $json[] = $data;
    }

    echo json_encode($json);
  }

  /**
   * List Of product unit
   * 
   * 
   * @return mixed
   */
  public function getProductUnit()
  {
    $where = array('status' => 1);
    $result = $this->commonModel->getTblResultOfData('unit', $where, '*');

    $json = array();
    foreach ($result as $rows) {
      $data['value'] = $rows['id'];
      $data['label'] = libsodiumDecrypt($rows['unit_name']);
      $json[] = $data;
    }

    echo json_encode($json);
  }

  /**
   * List Of product subcategory
   * 
   * @param mixed $id
   * @return mixed
   */
  public function getProductSubategory($id = '0')
  {
    if ($id > 0) {
      $where = array('status' => 1, 'category' => $id);
      $result = $this->commonModel->getTblResultOfData('product_subcategories', $where, '*');

      $json = array();
      foreach ($result as $rows) {
        $data['value'] = $rows['id'];
        $data['label'] = libsodiumDecrypt($rows['subcategory_name']);
        $json[] = $data;
      }
    } else {
      $json = array();
      $data['value'] = 0;
      $data['label'] = 'Select Subcategory';
      $json[] = $data;
    }

    echo json_encode($json);
  }
  /**
   * Pharmacy Product Check
   * 
   * @return mixed
   */
  public function checkProductExists()
  {
    $product = libsodiumEncrypt(trim($this->request->getPost('name') ?? ""));
    $user_id = session('user_id');

    $detailExist = $this->commonModel->checkTblDataExist('products', ['user_id' => $user_id, 'name' => $product], '*');
    if ($detailExist) {
      echo 'false';
    } else {
      echo 'true';
    }
  }
  /**
   * List Of Doctor In Clinic
   * 
   * @return mixed
   */
  public function getHospitalDoctor()
  {
    $apptModal = new \App\Models\AppointmentModel;
    $user_id = session('user_id');
    $list = $apptModal->verfiedDoctorsInClinic($user_id);

    $json = array();
    if (!empty($list)) {
      foreach ($list as $rows) {
        $data['value'] = $rows['id'];
        $data['label'] = libsodiumDecrypt($rows['first_name']) . ' ' . libsodiumDecrypt($rows['last_name']);
        $json[] = $data;
      }
    }
    echo json_encode($json);
  }
  /**
   * Clinic Appt assign to Doctor
   * 
   * 
   * @return mixed
   */
  public function clinicAssignDoctor()
  {
    $id = $this->request->getPost('id');
    $app_id = $this->request->getPost('app_id');

    $data = array(
      'appointment_to' => $id,
      'hospital_id' => session('user_id')
    );
    $this->commonModel->updateData('appointments', array('id' => $app_id), $data);
    session()->setFlashdata('success_message', 'Doctor assigned successfully');
    $response['msg'] = "Doctor added";
    $response['status'] = 200;
    echo json_encode($response);
  }
  /**
   * Get City Of Office.
   * 
   * 
   * @return mixed
   */
  public function getCityOfCountry()
  {
    $countryid = '';
    $where = array('country' => $_POST['country']);
    $result_country = $this->commonModel->getTblResultOfData('country', $where, '*');
    foreach ($result_country as $row) {
      $countryid = $row['countryid'];
    }
    $result = $this->adminModel->getCityOfCountry($countryid);
    //$where=array('countryid' =>101);
    //$result=$this->db->get_where('state',$where)->result_array();


    $json = array();
    foreach ($result as $rows) {
      $data['value'] = $rows['city'];
      $data['label'] = $rows['city'];
      $json[] = $data;
    }

    echo json_encode($json);
  }
  /**
   * Cart.
   * 
   * 
   * @return mixed
   */
  public function cart()
  {
    // Call the cart service
    $cart = \Config\Services::Cart();
    // $cart = new Cart();
    // Insert an array of values
    $cart->insert(array(
      'id'      => 'sku_1234ABCDasdasd',
      'qty'     => 1,
      'price'   => '19.56',
      'name'    => 'T-Shirt',
      'options' => array('Size' => 'L', 'Color' => 'Red')
    ));

    print_r($cart->totalItems());
    print_r($cart->contents());
  }
}
