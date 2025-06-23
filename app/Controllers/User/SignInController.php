<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use Config\MyConfig; // Loading config class
use App\Models\SignInModel;
use App\Models\CommonModel;
use App\Libraries\SendEmail;

class SignInController extends BaseController
{
    public mixed $uri;
    public mixed $data;
    public mixed $session;
    public mixed $timezone;
    public mixed $lang;
    public mixed $language;
    /**
     * @var \App\Models\SignInModel
     */
    public $signInModel;
    /**
     * @var \App\Models\CommonModel
     */
    public $commonModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);

        // Declare page detail
        $this->data['theme'] = 'user';
        $this->data['module'] = 'signin';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->timezone = session('time_zone');
        if (!empty($this->timezone)) {
            date_default_timezone_set($this->timezone);
        }
        $this->data['uri'] = service('uri');
        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->commonModel = new CommonModel();
        $this->signInModel = new SignInModel();
    }
    /**
     *  Template Page.
     * 
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        return view('user/template', $this->data);
    }
    /**
     *  Register Page.
     * 
     * 
     * @return mixed
     */
    public function register()
    {
        $this->data['page'] = 'register';
        return view('user/signin/register', $this->data);
    }
    /**
     *  Change Password Page.
     * 
     * 
     * @return mixed
     */
    public function changePassword()
    {
        $this->data['page'] = 'change-password';
        $this->data['profile'] = user_detail(session('user_id'));
        return view('user/signin/changePassword', $this->data);
    }
    /**
     *  Verifyneed Parameters.
     * 
     * @param mixed $required_fields
     * @param mixed $available_fields
     * @return mixed
     */
    public function verifyneedparameters($required_fields, $available_fields)
    {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $available_fields;
        // Handling PUT request params
        foreach ($required_fields as $field) {
            if (!isset($request_params[$field])) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }
        if ($error) {
            // Required field(s) are missing or empty
            $response_message = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            $response = [
                'status' => 400,
                'error' => true,
                'message' => $response_message,
                'data' => ''
            ];
            echo json_encode($response, JSON_PRETTY_PRINT);
            die;
        }
    }
    /**
     *  Social Signin.
     * 
     * 
     * @return mixed
     */
    public function social_signin()
    {
        $data = $this->request->getPost();
        $email = $data['email'];
        $result = $this->signInModel->social_login(libsodiumEncrypt($email));

        if (!empty($result) && $result['status'] == 1) {
            $page_user = "";
            if ($result['role'] == '1') {
                $page_user = "doctor"; //veterinary
            } else if ($result['role'] == '4') {
                $page_user = "lab";
            } else if ($result['role'] == '5') {
                $page_user = "pharmacy";
            } else if ($result['role'] == '6') {
                $page_user = "clinic";
            } else if ($result['role'] == '2') {
                $page_user = "patient";
            }
            
            $redirect_page_user=$page_user;
            if ($result['role'] == '1') {
                $redirect_page_user="doctor";// veterinary
            }
            
            $session_data = array('user_id' => $result['id'], 'role' => $result['role'], 'module' => $page_user);
            session()->set($session_data);
            session()->remove('admin_id');
            $response['msg'] = '';
            $response['status'] = 200;
        } else if (!empty($result) && $result['status'] == 2) {
            $response['status'] = 500;
            $response['msg'] = $this->language['lg_your_account_ha1'];
        } else {
            $response['status'] = 500;
            $response['msg'] = $this->language['lg_wrong_login_cre'];
        }

        echo json_encode($response);
        return false;
    }
    /**
     *  Social Register.
     * 
     * 
     * @return mixed
     */
    public function social_register()
    {
        $inputdata = array();
        $response = array();

        $userdata = array();
        $userdata = $this->request->getPost();

        // $otpno=libsodiumEncrypt($userdata['otpno']);
        $inputdata['first_name'] = libsodiumEncrypt(ucfirst($userdata['first_name']));
        $inputdata['last_name'] = libsodiumEncrypt(ucfirst($userdata['last_name']));
        $inputdata['email'] = libsodiumEncrypt(strtolower(trim($userdata['email'])));
        $inputdata['mobileno'] = 0;
        $inputdata['country_code'] = 0;
        $inputdata['country_id'] = 0;
        $inputdata['username'] = libsodiumEncrypt(generate_username(ucfirst($userdata['first_name']) . ' ' . ucfirst($userdata['last_name'])));
        $inputdata['role'] = $userdata['user_role'];
        $inputdata['password'] = 0;
        $inputdata['confirm_password'] = 0;
        $inputdata['created_date'] = date('Y-m-d H:i:s');

        $already_exits = $this->commonModel->checkTblDataExist('users', ['email' => $inputdata['email']], 'id');

        if ($already_exits >= 1) {
            $response['msg'] = $this->language['lg_your_email_addr1'];
            $response['status'] = 500;
        } else {
            $results = $this->commonModel->insertData('users', $inputdata, false);
            if ($results !== false) {

                $page_user = "";
                if ($userdata['user_role'] == '1') {
                    $page_user = "doctor"; //veterinary
                } else if ($userdata['user_role'] == '4') {
                    $page_user = "lab";
                } else if ($userdata['user_role'] == '5') {
                    $page_user = "pharmacy";
                } else if ($userdata['user_role'] == '6') {
                    $page_user = "clinic";
                } else if ($userdata['user_role'] == '2') {
                    $page_user = "patient";
                }
                
                $redirect_page_user=$page_user;
                if ($userdata['role'] == '1') {
                    $redirect_page_user="doctor"; //veterinary
                }
                
                $result_login = $this->signInModel->social_login(libsodiumEncrypt($userdata['email']));
                $session_data = array('user_id' => $result_login['id'], 'role' => $result_login['role'], 'module' => $page_user);
                session()->set($session_data);
                session()->remove('admin_id');

                $response['status'] = 200;
                $response['msg'] = $this->language['lg_registration_su'];
                session()->setFlashdata('success_message', $this->language['lg_registration_su']);
            } else {
                $response['msg'] = $this->language['lg_registration_fa'];
                $response['status'] = 500;
            }
        }
        echo json_encode($response);
        return false;
    }
    /**
     *  Check Already Register.
     * 
     * 
     * @return mixed
     */
    public function check_already_register()
    {
        $data = $this->request->getPost();
        $already_exits = $this->commonModel->checkTblDataExist('users', ['email' => $data['email']], 'id');;
        if ($already_exits >= 1) {
            $response['msg'] = $this->language['lg_your_email_addr1'];
            $response['status'] = 500;
        } else {
            $response['msg'] = '';
            $response['status'] = 200;
        }

        echo json_encode($response);
        return false;
    }
    /**
     *  SignUp.
     * 
     * 
     * @return mixed
     */
    public function signup()
    {
        $inputdata = array();
        $response = array();

        $userdata = array();
        $userdata = $this->request->getPost();

        // $otpno=libsodiumEncrypt($userdata['otpno']);
        isset($userdata['first_name']) ? 
        $inputdata['first_name'] = libsodiumEncrypt(ucfirst($userdata['first_name'])):
        '';
        isset($userdata['last_name'])? 
        $inputdata['last_name'] = libsodiumEncrypt(ucfirst($userdata['last_name'])):
        '';
        $inputdata['email'] = libsodiumEncrypt(strtolower(trim($userdata['email'])));
        
        isset($userdata['mobileno']) ? 
        $inputdata['mobileno'] = libsodiumEncrypt($userdata['mobileno']):
        '';
        //$inputdata['country_code'] = $userdata['country_code'];
        //$inputdata['country_id'] = $userdata['country_id'];
        $inputdata['username'] = libsodiumEncrypt(generate_username(/*ucfirst($userdata['first_name']) . ' ' . ucfirst($userdata['last_name']) . ' ' .*/ $userdata['email']));
        $inputdata['role'] = $userdata['role'];
        $inputdata['password'] = md5($userdata['password']);
        $inputdata['confirm_password'] = md5($userdata['password']);
        $inputdata['created_date'] = date('Y-m-d H:i:s');


        $already_exits = $this->commonModel->checkTblDataExist('users', ['email' => $inputdata['email']], 'id');
        //$already_exits_mobile_no = $this->commonModel->checkTblDataExist('users', ['mobileno' => $inputdata['mobileno']], 'id');

        // H-3-4
        // if(settings('tiwilio_option')=='1')
        // {
        //     $otp_checking =$this->db->select('otpno,mobileno')->from('otp_history')->where('otpno', $otpno)->where('mobileno',$this->request->getPost('mobileno'))->get()->num_rows();
        //     if($otp_checking ==0)
        //     {
        //         $response['msg']=$this->language['lg_your_otp_is_inv'];
        //         $response['status']=500;
        //         echo json_encode($response);
        //         return false;
        //     }
        // }

        // Original code commneted ---------------------------------------------
       /* if ($already_exits >= 1) {
            $response['msg'] = $this->language['lg_your_email_addr1'];
            $response['status'] = 500;
        } else if ($already_exits_mobile_no >= 1) {
            $response['msg'] = $this->language['lg_your_mobileno_a'];
            $response['status'] = 500;
        } else {
            $results = $this->commonModel->insertData('users', $inputdata, false);
            if ($results !== false) {
                $inputdata['id'] = $results['id'];
                $sendmail = new \App\Libraries\SendEmail;
                $sendmail->send_email_verification($inputdata);

                $response['status'] = 200;
                $response['msg'] = $this->language['lg_registration_su'];
                session()->setFlashdata('success_message', $this->language['lg_registration_su']);
            } else {
                $response['msg'] = $this->language['lg_registration_fa'];
                $response['status'] = 500;
            }
        }
        echo json_encode($response);*/
        
        
        //------Original code finish -------------------------------------------
        
        //------Updated code ---------------------------------------------------
        
          if ($already_exits >= 1) {
            $response['msg'] = $this->language['lg_your_email_addr1'];
            $response['status'] = 500;
        } /*else if ($already_exits_mobile_no >= 1) {
            $response['msg'] = $this->language['lg_your_mobileno_a'];
            $response['status'] = 500;
        } */else {
            $results = $this->commonModel->insertData('users', $inputdata, false);
            if ($results !== false) {
                $inputdata['id'] = $results['id'];
                
                $response['status'] = 200;
                $response['msg'] = $this->language['lg_registration_su'];
                $response['input_data'] = ['id' => $inputdata['id'], 'password' => $userdata['password'], 'idEnc' => md5($inputdata['id'])];
                $sendmail = new \App\Libraries\SendEmail;
                isset($inputdata['case']) && $inputdata['case'] !=null && isset($inputdata['doctorEnc']) && $inputdata['doctorEnc'] != null ?
                 $sendmail->send_email_verification($inputdata, $inputdata['case'], $inputdata['doctorEnc']):
                 $sendmail->send_email_verification($inputdata);

                //session()->setFlashdata('success_message', $this->language['lg_registration_su']);
            } else {
                $response['msg'] = $this->language['lg_registration_fa'];
                $response['status'] = 500;
            }
        }
        echo json_encode($response);
        
    }
    /**
     *  Login.
     * 
     * 
     * @return mixed
     */
    public function login()
    {
        $response = array();
        $email = strtolower(trim($this->request->getPost('email') ?? ""));
        $password = $this->request->getPost('password');
        $result = $this->signInModel->isValidLogin(libsodiumEncrypt($email), $password);
        if (!empty($result) && $result['status'] == 1) {
            //by Muddasar on 21st June 2024
            $redirectUrl = session()->get('redirect_url');
            $return_session_url="";
            if ($redirectUrl && $redirectUrl!='' && $result['role'] == '2') {
                $return_session_url=$redirectUrl;
                // Clear the redirect URL from the session
                session()->remove('redirect_url');
            }
            
            //end
            $page_user = "";
            $redirect_page_user="";
            if ($result['role'] == '1') {
                $page_user = "doctor"; // veterinary
            } else if ($result['role'] == '4') {
                $page_user = "lab";
            } else if ($result['role'] == '5') {
                $page_user = "pharmacy";
            } else if ($result['role'] == '6') {
                $page_user = "clinic";
            } else if ($result['role'] == '2') {
                $page_user = "patient";
            } 
            
            $redirect_page_user=$page_user;
            if ($result['role'] == '1') {
                $redirect_page_user="doctor"; //veterinary
            }
            
            $session_data = array('user_id' => $result['id'], 'role' => $result['role'], 'module' => $page_user);
            session()->set($session_data);
            session()->remove('admin_id');
            $response['page_user'] = $redirect_page_user;
            $response['redirectUrl'] = $return_session_url;
            $response['user_id'] = $result['id'];
            $response['msg'] = 'Welcome back to your account';
            $response['status'] = 200;
        } elseif (!empty($result) && $result['status'] == 2) {
            // User account is inactive, do not allow login
            $response['status'] = 500;
            $response['msg'] = 'Your account is inactive. Please contact support.';
        } else {
            $checkEmail = $this->commonModel->checkTblDataExist('users', ['email' => libsodiumEncrypt($email)], 'id');
            $checkMob = $this->commonModel->checkTblDataExist('users', ['mobileno' => libsodiumEncrypt($email)], 'id');

            if ($checkEmail >= 1 || $checkMob >= 1) {
                $response['status'] = 500;
                $response['msg'] = $this->language['lg_wrong_login_cre'];
            } else {
                $response['status'] = 500;
                $response['msg'] = $this->language['lg_your_email_addr'];
            }
        }
        echo json_encode($response);
    }
    /**
     *  Password Check.
     * 
     * 
     * @return mixed
     */
    public function passwordCheck()
    {
        $inputdata = array();
        $response = array();
        // print_r($this->request->getPost());die;
        $currentpassword = md5($this->request->getPost('currentpassword') ?? "");
        $id = session('user_id');		
        $result = $this->commonModel->checkTblDataExist('users', ['password' => $currentpassword, 'id' => $id], 'id');;
        if ($result >= 1) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

      /**
     *  Password Check.
     * 
     * 
     * @return mixed
     */
    public function passwordCheckV2()
    {
        $inputdata = array();
        $response = array();
        // print_r($this->request->getPost());die;
        $currentpassword = md5($this->request->getPost('currentpassword') ?? "");
        $id = $this->request->getPost('userId');		
        $result = $this->commonModel->checkTblDataExist('users', ['password' => $currentpassword, 'id' => $id], 'id');;
        if ($result >= 1) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
    /**
     *  Password Update.
     * 
     * 
     * @return mixed
     */
    public function passwordUpdate()
    {
        $inputdata = array();
        $response = array();
        $currentpassword = md5($this->request->getPost('currentpassword') ?? "");
        $id = session('user_id');
        $result = $this->commonModel->checkTblDataExist('users', ['password' => $currentpassword, 'id' => $id], 'id');;
        if ($result >= 1) {
            $inputdata['password'] = md5($this->request->getPost('password') ?? "");
            $inputdata['confirm_password'] = md5($this->request->getPost('confirm_password') ?? "");
            $result = $this->commonModel->updateData('users', ['id' => $id], $inputdata);
            if ($result == true) {
                $response['msg'] = $this->language['lg_password_succes'];
                $response['status'] = 200;
            } else {
                $response['msg'] = $this->language['lg_password_change'];
                $response['status'] = 500;
            }
        } else {
            $response['msg'] = $this->language['lg_current_passwor1'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }

     /**
     *  Password Update.
     * 
     * 
     * @return mixed
     */
    public function passwordUpdateV2()
    {
        $inputdata = array();
        $response = array();
        $currentpassword = md5($this->request->getPost('currentpassword') ?? "");
        $id = $this->request->getPost('user_id');
        $result = $this->commonModel->checkTblDataExist('users', ['password' => $currentpassword, 'id' => $id], 'id');;
        if ($result >= 1) {
            $inputdata['password'] = md5($this->request->getPost('password') ?? "");
            $inputdata['confirm_password'] = md5($this->request->getPost('confirm_password') ?? "");
            $result = $this->commonModel->updateData('users', ['id' => $id], $inputdata);
            if ($result == true) {
                $response['msg'] = $this->language['lg_password_succes'];
                $response['status'] = 200;
            } else {
                $response['msg'] = $this->language['lg_password_change'];
                $response['status'] = 500;
            }
        } else {
            $response['msg'] = $this->language['lg_current_passwor1'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }

        /**
     *  Password Update.
     * 
     * 
     * @return mixed
     */
    public function newPasswordUpdate()
    {
        $inputdata = array();
        $response = array();
        $id = session('user_id');
        $inputdata['password'] = md5($this->request->getPost('password') ?? "");
        $inputdata['confirm_password'] = md5($this->request->getPost('confirm_password') ?? "");
        $result = $this->commonModel->updateData('users', ['id' => $id], $inputdata);
        if ($result == true) {
            $response['msg'] = $this->language['lg_password_succes'];
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_password_change'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }
    /**
     *  Log out.
     * 
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        session_destroy();
        return redirect()->route('/');
    }
    /**
     *  Render.
     * 
     * 
     * @return mixed
     */
    public function render()
    {
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        echo $lang . "   Lang <pre>" . " ->locale-" . session('locale') . " ->config" . config('App')->defaultLocale;
        print_r(lang('content_lang.language', [], $lang));
        echo "   <>Lang<>";
        print_r(lang('content_lang.language', [], 'english'));
    }
    /**
     *  Print Session.
     * 
     * 
     * @return mixed
     */
    public function printSession()
    {
        echo "<pre>";
        print_r(session());
    }
    /**
     *  Forgot Password Page.
     * 
     * 
     * @return mixed
     */
    public function forgotPassword()
    {
        $this->data['page'] = 'forgot-password';
        $this->data['profile'] = user_detail(session('user_id'));
        return view('user/signin/forgotPassword', $this->data);
    }
    /**
     *  Forgot Password Update.
     * 
     * 
     * @return mixed
     */
    public function forgotPasswordUpdate()
    {
        $inputdata = array();
        $response = array();
        $inputdata['email'] = libsodiumEncrypt(strtolower(trim($this->request->getPost('resetemail') ?? "")));
        $user_details = $this->commonModel->getTblRowOfData('users', ['email' => $inputdata['email']], '*');

        if ($user_details) {
            $inputdata['expired_reset'] = date('Y-m-d H:i:s', strtotime("+3 hours"));
            $inputdata['forget'] = urlencode($this->encryptor('encrypt', $inputdata['email'] . time()));
            $this->commonModel->updateData('users', ['id' => $user_details['id']], $inputdata);
            $user_details['url'] = $inputdata['forget'];

            // Sending Email
            $sendMail = new SendEmail(); // create an instance of Library
            $sendMail->sendResetPasswordEmail($user_details);

            $response['msg'] = $this->language['lg_your_reset_pass'];
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_your_email_addr'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }
    /**
     *  Reset.
     * 
     * @param mixed $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function reset($id)
    {

        $user_details = $this->commonModel->checkTblDataExist('users', ['forget' => urlencode($id)], 'id,expired_reset', []);
        // print_r(urlencode($id));
        if (!empty($user_details)) {
            $currenttime = date('Y-m-d H:i:s');

            if ($user_details['expired_reset'] >= $currenttime) {
                $inputdata['forget'] = '';

                $this->commonModel->updateData('users', ['id' => $user_details['id']], $inputdata);
                $this->data['id'] = md5($user_details['id']);

                $this->data['page'] = 'change_password';

                return view('user/signin/resetPassword', $this->data);
            } else {
                session()->setFlashdata('error_message', $this->language['lg_your_reset_link']);
                return redirect()->route('/');
            }
        } else {
            session()->setFlashdata('error_message', $this->language['lg_your_reset_link']);
            return redirect()->route('/');
        }
    }
    /**
     *  Reset Password.
     * 
     * 
     * @return mixed
     */
    public function resetPassword()
    {
        $inputdata = array();
        $response = array();
        $id = $this->request->getPost('id');

        $user_details = $this->commonModel->checkTblDataExist('users', ['md5(id)' => $id], 'id,expired_reset');
        $inputdata['password'] = md5($this->request->getPost('password') ?? "");
        $inputdata['confirm_password'] = md5($this->request->getPost('confirm_password') ?? "");
        $result = $this->commonModel->updateData('users', ['id' => $user_details['id']], $inputdata);
        if ($result == true) {
            $response['msg'] = $this->language['lg_password_change1'];
            $response['status'] = 200;
        } else {
            $response['msg'] = $this->language['lg_password_change'];
            $response['status'] = 500;
        }
        echo json_encode($response);
    }
    /**
     *  encryptor.
     * 
     * @param mixed $action
     * @param mixed $string
     * @return mixed
     */
    function encryptor($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'bookotv';
        $secret_iv = 'bookotv123';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {

            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);

            $output = base64_encode($output);
        } else if ($action == 'decrypt') {

            //decrypt the given text/string/number

            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
    /**
     * Email Activate For User
     * 
     * @param mixed $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function activate($id)
    {
        $user_details = $this->commonModel->getTblRowOfData('users', ['md5(id)' => $id], 'is_verified, id');
		/*if ($user_details['is_verified'] == '1') {
            session()->setFlashdata('error_message', $this->language['lg_your_account_ha2'] ?? "");
            return redirect()->route('/');
        }*/

        $inputdata['is_verified'] = 1;
        $result = [];
        $result['active'] = $this->commonModel->updateData('users', ['md5(id)' => $id], $inputdata);
        if ($result['active'] == true) {
            $response = array();
            if ($result == true) {
                $result['role'] = 2;
                //by himansu
                $redirectUrl = session()->get('redirect_url');
                $return_session_url="";
                if ($redirectUrl && $redirectUrl!='' && $result['role'] == '2') {
                    $return_session_url=$redirectUrl;
                    // Clear the redirect URL from the session
                    session()->remove('redirect_url');
                }

                //end
                $page_user = "";
                $redirect_page_user="";
                if ($result['role'] == '1') {
                    $page_user = "doctor"; //veterinary
                } else if ($result['role'] == '4') {
                    $page_user = "lab";
                } else if ($result['role'] == '5') {
                    $page_user = "pharmacy";
                } else if ($result['role'] == '6') {
                    $page_user = "clinic";
                } else if ($result['role'] == '2') {
                    $page_user = "patient";
                }

                $redirect_page_user=$page_user;
                if ($result['role'] == '1') {
                    $redirect_page_user="doctor"; //veterinary
                }

                $session_data = array('user_id' => $user_details['id'], 'role' => $result['role'], 'module' => $page_user, 'redirect_activate' => '/change-password');
                session()->set($session_data);
                $response['page_user'] = $redirect_page_user;
                $response['redirectUrl'] = $return_session_url;
                $response['msg'] = 'Welcome back to your account';
                $response['status'] = 200;
				return redirect()->route('/');
            }
            //session()->setFlashdata('success_message', $this->language['lg_your_account_ha'] ?? "");
            return redirect()->route('/change-password');
        } else {
            session()->setFlashdata('error_message', $this->language['lg_your_account_ve'] ?? "");
            return redirect()->route('/');
        }
    }
}
