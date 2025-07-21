<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\HomeModel;
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use Stripe;
use Razorpay\Api\Api;
use App\Models\AppointmentModel;
use App\Models\PackageModel;
    // Pet update code
    //added new on 13rd June 2024 by Muddasar
use App\Models\UserModel;
use App\Models\PromoModel;

class BookAppointment extends BaseController
{

    // Appoinment type
    // -> Doctor Free Insert Appointment
    // -> Book Appointment 
    //     -> Pay On Arrive
    //     -> Card / Stripe payment
    //     -> Paypal payment
    //     -> Razorpay    

    public mixed $uri;
    public mixed $data;
    public mixed $timezone;
    public mixed $lang;
    public mixed $language;

    /**
     * @var \App\Models\HomeModel
     */
    public $homeModel;
    /**
     * @var \App\Models\UserModel
     */
    public $userModel;
    /**
     * @var \App\Models\PackageModel
     */
    public $packageModel;
    /**
     * @var \App\Models\AppointmentModel
     */
    public $apptModel;

    /**
     * @var \App\Models\AppointmentModel
     */
    public mixed $promoModel;
    public string $tokboxKey;
    public string $tokboxSecret;

    public function __construct()
    {
        $this->data['theme'] = 'user';
        $this->data['module'] = 'patient';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->data['url_segment1'] = service('uri')->getSegment(1);
        $this->data['url_segment2'] = service('uri')->getSegment(2);
        $this->timezone = session('time_zone');
        if (!empty($this->timezone)) {
            date_default_timezone_set($this->timezone);
        }
        $this->data['uri'] = service('uri');

        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        // OpenTok Key Used In Incoimng And Outgoing Call Tab
        $this->tokboxKey = !empty(settings("apiKey")) ? libsodiumDecrypt(settings("apiKey")) : "";
        $this->tokboxSecret = !empty(settings("apiSecret")) ? libsodiumDecrypt(settings("apiSecret")) : "";

        $this->homeModel = new HomeModel();
        $this->apptModel = new AppointmentModel();
        $this->userModel = new UserModel();
        $this->packageModel = new PackageModel();
        $this->promoModel = new PromoModel();

    }
    /**
     *  Doctor Appointment Preview Page 
     * 
     * @param string $username
     * @return mixed
     */
    public function bookDoctor($username)
    {
        $username = encryptor_decryptor('decrypt', $username);
        
        //echo $username;
        //exit;
        
        if (session('role') != 2) {
            return redirect()->to(session('module'));
        } else {
            $user_detail = user_detail(session('user_id'));
                // Pet update code
    //added new on 13rd June 2024 by Muddasar
            $patientId = session('user_id');
            //$patientId='9';
            $user_pets = $this->userModel->getPetsByPatientId($patientId);
            
            //var_dump($user_pets);
            
            if ($user_detail['is_updated'] == '0' || $user_detail['is_verified'] == '0') {
                session()->setFlashdata('error_message', $this->language['lg_please_update_p'] ?? "Please Verify and Update Your Account to Book Appointment");
                return redirect()->to('/patient');
            }     // Pet update code
    //added new on 13rd June 2024 by Muddasar
            else if(empty($user_pets)){
                session()->setFlashdata('error_message', "Please Save Pet Details");
                return redirect()->to('/patient/profile');
            }
            else {
                $this->data['page'] = 'book_appoinments';
                $this->data['doctors'] = $this->homeModel->getDoctorDetails(libsodiumEncrypt($username));
                session()->set('doctor_id', $this->data['doctors']['userid']);
                $this->data['schedule_date'] = date('d/m/Y');
                $this->data['selected_date'] = date('Y-m-d');
                    // Pet update code
    //added new on 13rd June 2024 by Muddasar
                $this->data['user_pets']=$user_pets;
                return view('user/home/bookAppointment', $this->data);
            }
        }
    }

    /**
     * Doctor Booking Token List Page to Patient Booking
     * 
     * @return mixed
     */
    public function getScheduleFromDate()
    {
        $schedule_date = $this->request->getPost('schedule_date');
        $doctor_id = $this->request->getPost('doctor_id');
        $day = date('D', strtotime(str_replace('/', '-', $schedule_date ?? "")));
        $day_id = 0;
        switch ($day) {
            case 'Sun':
                $day_id = 1;
                break;
            case 'Mon':
                $day_id = 2;
                break;
            case 'Tue':
                $day_id = 3;
                break;
            case 'Wed':
                $day_id = 4;
                break;
            case 'Thu':
                $day_id = 5;
                break;
            case 'Fri':
                $day_id = 6;
                break;
            case 'Sat':
                $day_id = 7;
                break;
            default:
                $day_id = 0;
                break;
        }
        $data['schedule'] =  $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $doctor_id, 'day_id' => $day_id], '*');
        $data['schedule_date'] = $schedule_date;
        $data['language'] = $this->language;
        return view('user/home/appointmentToken', $data);
    }

    /**
     * Function for date time format using in appointment token verify
     * 
     * 
     * @param mixed $fromTime
     * @param mixed $toTime
     * @param mixed $input
     * @return mixed
     */
    function checkIfExist($fromTime, $toTime, $input)
    {
        $fromDateTime = \DateTime::createFromFormat("H:i", $fromTime);
        // echo $fromDateTime;
        $toDateTime = \DateTime::createFromFormat('H:i', $toTime);
        $inputDateTime = \DateTime::createFromFormat('H:i', $input);
        if ($fromDateTime > $toDateTime) $toDateTime->modify('+1 day');
        return ($fromDateTime <= $inputDateTime && $inputDateTime < $toDateTime) || ($fromDateTime <= $inputDateTime->modify('+1 day') && $inputDateTime <= $toDateTime);
    }

    public function setPackageSession(){
        $new_data = array(
            'redirect_url' => '/'
        );
        session()->set($new_data);
        return json_encode(['status' => 200]);
    }

    public function getAppointmentAmountInfo(){
        try{
            if ($_POST['price_type'] == 'Free' || trim($_POST['hourly_rate']) == '') {
                /**
                 * Free fee doctor
                 */
               // $appointment_id = $this->bookFreeAppoinment();
                $response['status'] = 202;
                $response['data'] = ['appoinment_id' => $appointment_id ?? null];
                echo json_encode($response);
            } else {
                
                $tax = !empty(settings("tax")) ? settings("tax") : "0";
                // $hourly_rate = $this->request->getPost('hourly_rate');
                // $doctor_username = $this->request->getPost('doctor_username');
                $doctorID = $_POST['doctor_id'];
                if(!$doctorID){
                     $doctor_details = user_detail(session('doctor_id') ?? $doctorID);
                }else{
                     $doctor_details = user_detail(session('doctor_id'));
                }
               
                $user_currency = get_user_currency();
                $user_currency_code = $user_currency['user_currency_code'];
                // $user_currency_rate = $user_currency['user_currency_rate'];
                $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $doctor_details['currency_code'];
                $rate_symbol = currency_code_sign($currency_option);
                if (!empty(session('user_id'))) {
                    $rate = get_doccure_currency($doctor_details['amount'], $doctor_details['currency_code'], $user_currency_code);
                } else {
                    $rate = $doctor_details['amount'];
                }
                $rate = number_format($rate, 2, '.', '');
                $amount =  $rate;
    
                $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
    
                if ($transcation_charge_amt > 0) {
                    $transcation_charge = ($amount * ($transcation_charge_amt / 100));
                    $transcation_charge = number_format($transcation_charge, 2, '.', '');
                } else {
                    $transcation_charge = 0;
                }
                if($user_currency_code != 'AED'){
                    $api_url = "https://openexchangerates.org/api/latest.json?app_id=392ca79b7ad2497bb74616475aab7bff";
                    $curl = curl_init($api_url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response_rate = curl_exec($curl);
                    curl_close($curl);
                    $rates = json_decode($response_rate, true)['rates'];
                    $erate = (1/$rates['AED'])*$rates[$user_currency_code ?? 'USD'];
                    $amount = ($amount*$erate);
                    $transcation_charge = $transcation_charge*$erate;
                    $tax = $tax*$erate;
                }
                $total_amount = ($amount + $transcation_charge);
    
                $tax_amount = ($total_amount * $tax / 100);
                $tax_amount = number_format($tax_amount, 2, '.', '');
    
                $total_amount = $total_amount + $tax_amount;
                $total_amount = number_format($total_amount, 2, '.', '');

                $tax = number_format($tax, 2, '.', '');
    
                $amount = number_format($amount, 2, '.', '');

                $new_data = array(
                    'amount' => $amount,
                    'transcation_charge' => $transcation_charge,
                    'transcation_charge_prec' => $transcation_charge_amt,
                    'tax_amount' => $tax_amount,
                    'tax_prec' => $tax,
                    'total_amount' => $total_amount,
                    'hourly_rate' => $rate,
                    'currency_code' => $currency_option,
                    'currency_symbol' => $rate_symbol,
                    'discount' => 0,
                    'doctor_role_id' => $_POST['doctor_role_id']
                );
               
                $response['status'] = 200;
                $response['data'] = $new_data;
                echo json_encode($response);
            }
        }catch(\Exception $exc){
            $response['status'] = 500;
            $response['message'] = $this->language['lg_this_token_alre1'] ?? "";
            echo json_encode($response);
        }
       
    }

    /**
     * Booking Detail set in session if doctor fees not free
     * doctor fees is free it will create new appointment
     * 
     * @return mixed
     */
    public function setBookedSession()
    {
        $system_coupons = $this->promoModel->getPromocodes();
        $response = array();
        if (!empty($_REQUEST['coupon'])) {
            $coupons= $this->packageModel->getUserCoupons(session('user_id'));
            $used_coupons = [];
            foreach($coupons as $c){
                $used_coupons[] = $c['coupon'];
            }

            foreach($system_coupons as $coupon){
                if($_REQUEST['coupon'] == $coupon['coupon'] && $coupon['active'] == 1){
                    if($coupon['discount_type'] == 'free_price'){
                        $new_data = array(
                            'coupon' => true
                        );
                        session()->set($new_data);
                    }elseif($coupon['discount_type'] == '%'){
                        $new_data = array(
                            'coupon' => true,
                            'coupon_type' => 'special'.$coupon['discount']
                        );
                        $coupon_id = $this->insertCoupon('special'.$coupon['discount'], 'used');
                        session()->set($new_data);
                    }elseif($coupon['discount_type'] == 'amount'){
                        $new_data = array(
                            'coupon' => true,
                            'coupon_type' => 'amount'.$coupon['discount']
                        );
                        $coupon_id = $this->insertCoupon('amount'.$coupon['discount'], 'used');
                        session()->set($new_data);
                    }
                }
            }
            /*if($_REQUEST['coupon'] == 'firsttime'){
                $new_data = array(
                    'coupon' => true
                );
                session()->set($new_data);
            }elseif($_REQUEST['coupon'] == 'gift4pet' && !in_array($_REQUEST['coupon'], $used_coupons)){
                $new_data = array(
                    'coupon' => true,
                    'coupon_type' => 'gift4pet'
                );
                session()->set($new_data);
                $coupon_id = $this->insertCoupon('gift4pet', 'used');
            }elseif($_REQUEST['coupon'] == 'Mandarin' && !in_array($_REQUEST['coupon'], $used_coupons)){
                $new_data = array(
                    'coupon' => true,
                    'coupon_type' => 'Mandarin'
                );
                $coupon_id = $this->insertCoupon('special15', '');
                session()->set($new_data);
            }*/
            return true;
        }

        $appointment_details =  json_decode($this->request->getPost('appointment_details') ?? "");

        if ($_REQUEST['price_type'] == 'Free' || trim($_REQUEST['hourly_rate']) == '') {
            /**
             * Free fee doctor
             */
            $package = false;
            if(isset($_REQUEST['package']) && $_REQUEST['package'] == 'yes'){
                $package = true;
            }
            $appointment_id = $this->bookFreeAppoinment($package);
            // Notification Add
            //$this->insertNotification($appointment_details[0]->appoinment_timezone);
            $this->send_appoinment_mail($appointment_id);
            $this->data['appointment_details'] = $this->homeModel->getAppoinmentsDetails($appointment_id);
            $this->data['page'] = 'payment_success';
            $response['status'] = 202;
            $response['appoinment_id'] = $appointment_id;
            return view('user/home/appointmentSuccess', $this->data);
        }



        if (isset($appointment_details[0]->type) && $appointment_details[0]->type == 'package') {
            $tax = !empty(settings("tax")) ? settings("tax") : "0";
            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            // $user_currency_rate = $user_currency['user_currency_rate'];
            $currency_option = $user_currency_code;
            $rate_symbol = currency_code_sign($currency_option);
            $amount = 1*$_POST['amount'];
            $package_count = 1*$_POST['count'];
            $this->data['amount'] = $rate_symbol . '' . $amount;
            $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";

            if ($transcation_charge_amt > 0) {
                $transcation_charge = ($amount * ($transcation_charge_amt / 100));
                $transcation_charge = number_format($transcation_charge, 2, '.', '');
            } else {
                $transcation_charge = 0;
            }
            //currency rate change

            if($user_currency_code != 'AED'){
                $api_url = "https://openexchangerates.org/api/latest.json?app_id=392ca79b7ad2497bb74616475aab7bff";
                $curl = curl_init($api_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response_rate = curl_exec($curl);
                curl_close($curl);
                $rates = json_decode($response_rate, true)['rates'];
                $erate = (1/$rates['AED'])*$rates[$user_currency_code];
                $amount = $amount*$erate;
                $transcation_charge = $transcation_charge*$erate;
                $tax = $tax*$erate;
            }
            $total_amount = $amount + $transcation_charge;

            $tax_amount = ($total_amount * $tax / 100);
            $tax_amount = number_format($tax_amount, 2, '.', '');

            $total_amount = $total_amount + $tax_amount;
            $total_amount = number_format($total_amount, 2, '.', '');

            $new_data = array(
                'amount' => $amount,
                'transcation_charge' => $transcation_charge,
                'tax_amount' => $tax_amount,
                'total_amount' => $total_amount,
                'package_count' => $package_count,
                'hourly_rate' => 1,
                'currency_code' => $currency_option,
                'currency_symbol' => $rate_symbol,
                'discount' => 0
            );
            session()->set($new_data);
            session()->set('appointment_details', $appointment_details);
            $response['status'] = 200;
            return json_encode($response);
        }

        $starttime  = $appointment_details[0]->appoinment_start_time;
        $endtime    = $appointment_details[0]->appoinment_end_time;
        $selectdate = $appointment_details[0]->appoinment_date;
            // Pet update code
    //added new on 13rd June 2024 by Muddasar
        $appoinment_pet_id=$appointment_details[0]->appoinment_pet_id;

        $from_rh = date('H:i', strtotime($starttime));
        $to_rh   = date('H:i', strtotime($endtime));

        $user_id = session('user_id');
        $where = ['appointment_from' => $user_id, 'appointment_date' => $selectdate, 'approved' => 1, 'status' => 1, 'appointment_status' => 0, 'call_status' => 0];
        $patient_bookings = $this->homeModel->getTblResultOfData('appointments', $where, '*');
        
        $user_noslot = '';
        if (count($patient_bookings) > 0) {
            // $user_noslot = 1;
            foreach ($patient_bookings as  $b => $bookedtime) {
                $ufromTime = date('H:i', strtotime($bookedtime['appointment_time']));
                $utoTime   = date('H:i', strtotime($bookedtime['appointment_end_time']));
                $user_noslot   = $this->checkIfExist($ufromTime, $utoTime, $from_rh);
                if ($user_noslot != 1) {
                    $user_noslot = $this->checkIfExist($ufromTime, $utoTime, $to_rh);
                    if ($user_noslot == 1) break;
                } else {
                    break;
                }
            }
        }
        
        if ($user_noslot == 1) {
            $response['status'] = 500;
            $response['message'] = $this->language['lg_another_booking'] ?? "";
            echo json_encode($response);
        } else {
            $current_timezone = $appointment_details[0]->appoinment_timezone;
            $old_timezone = session('time_zone');
            $appointment_date = date('Y-m-d', strtotime(converToTz($appointment_details[0]->appoinment_date, $old_timezone, $current_timezone)));
            $appointment_time = date('H:i s', strtotime(converToTz($appointment_details[0]->appoinment_start_time, $old_timezone, $current_timezone)));
            
            if (date('Y-m-d H:i:s') < $appointment_date . ' ' . $appointment_time) {
                $fromStartTime = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time;
                $booked_session = get_booked_session($appointment_details[0]->appoinment_session, $appointment_details[0]->appoinment_token, $fromStartTime, session('doctor_id'));

                if ($booked_session >= 1) {
                    $response['status'] = 500;
                    $response['message'] = $this->language['lg_this_token_alre'];
                    echo json_encode($response);
                } else {
                    if ($_POST['price_type'] == 'Free' || trim($_POST['hourly_rate']) == '') {
                        /**
                         * Free fee doctor
                         */
                        $appointment_id = $this->bookFreeAppoinment();
                        $response['status'] = 202;
                        $response['appoinment_id'] = $appointment_id;
                        echo json_encode($response);
                    } else {
                        /**
                         * slot base fee doctor section 
                         */
                        $tax = !empty(settings("tax")) ? settings("tax") : "0";
                        $appointment_details =  json_decode($this->request->getPost('appointment_details') ?? "");
                        // $hourly_rate = $this->request->getPost('hourly_rate');
                        // $doctor_username = $this->request->getPost('doctor_username');

                        $doctor_details = user_detail(session('doctor_id'));

                        $user_currency = get_user_currency();
                        $user_currency_code = $user_currency['user_currency_code'];
                        // $user_currency_rate = $user_currency['user_currency_rate'];
                        $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $doctor_details['currency_code'];
                        $rate_symbol = currency_code_sign($currency_option);
                        if (!empty(session('user_id'))) {
                            $rate = get_doccure_currency($doctor_details['amount'], $doctor_details['currency_code'], $user_currency_code);
                        } else {
                            $rate = $doctor_details['amount'];
                        }
                        $rate = number_format($rate, 2, '.', '');
                        $this->data['amount'] = $rate_symbol . '' . $rate;


                        $amount = $rate;

                        $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";

                        if ($transcation_charge_amt > 0) {
                            $transcation_charge = ($amount * ($transcation_charge_amt / 100));
                            $transcation_charge = number_format($transcation_charge, 2, '.', '');
                        } else {
                            $transcation_charge = 0;
                        }
                        if($user_currency_code != 'AED'){
                            $api_url = "https://openexchangerates.org/api/latest.json?app_id=392ca79b7ad2497bb74616475aab7bff";
                            $curl = curl_init($api_url);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            $response_rate = curl_exec($curl);
                            curl_close($curl);
                            $rates = json_decode($response_rate, true)['rates'];
                            $erate = (1/$rates['AED'])*$rates[$user_currency_code];
                            $amount = $amount*$erate;
                            $transcation_charge = $transcation_charge*$erate;
                            $tax = $tax*$erate;
                        }
                        $total_amount = $amount + $transcation_charge;

                        $tax_amount = ($total_amount * $tax / 100);
                        $tax_amount = number_format($tax_amount, 2, '.', '');

                        $total_amount = $total_amount + $tax_amount;
                        $total_amount = number_format($total_amount, 2, '.', '');

                        $new_data = array(
                            'amount' => $amount,
                            'transcation_charge' => $transcation_charge,
                            'tax_amount' => $tax_amount,
                            'total_amount' => $total_amount,
                            'hourly_rate' => $rate,
                            'currency_code' => $currency_option,
                            'currency_symbol' => $rate_symbol,
                            'discount' => 0,
                            'doctor_role_id' => $_POST['doctor_role_id']
                        );
                        session()->set($new_data);
                        session()->set('appointment_details', $appointment_details);
                        $response['status'] = 200;
                        echo json_encode($response);
                    }
                }
            } else {
                $response['status'] = 500;
                $response['message'] = $this->language['lg_this_token_alre1'] ?? "";
                echo json_encode($response);
            }
        }
    }

    /**
     * creating free appointment
     * 
     * @return mixed
     */
    public function bookFreeAppoinment($package = false)
    {
        // Get Invoice No
        $invoice_no = $this->getInvoiceNo();
        $appointment_id = "";
        $packages = $this->packageModel->getUserPackages(session('user_id'));
        // Store the Payment details
        $doctor_id = session('doctor_id')?session('doctor_id'):1;
        $payments_data = array(
            'user_id' => session('user_id'),
            'doctor_id' => $doctor_id,
            'invoice_no' => $invoice_no,
            'per_hour_charge' => 0,
            'total_amount' => 0,
            'currency_code' => "USD",
            'txn_id' => "",
            'order_id' => 'OD' . time() . rand(),
            'transaction_status' => "success",
            'payment_type' => 'Free Booking',
            'tax' => 0,
            'tax_amount' => 0,
            'transcation_charge' => 0,
            'payment_status' => 0,
            'payment_date' => date('Y-m-d H:i:s'),
        );
        $insert = $this->homeModel->insertData('payments', $payments_data);
        $payment_id = $insert['id'];

        // Sending notification to mentor

        $appointment_details = session('appointment_details');

        // Insert Appointment
        $appointment_id = $this->insertAppointment('Free', $payment_id, $appointment_details);
        if($appointment_id){
            if($package){
                foreach ($packages as $p){
                    if($p['count'] < 1)
                        continue;
                    if($p['count_used'] < $p['count']){
                        $data = array(
                            'count_used' => ($p['count_used']+1),
                        );
                        $this->packageModel->updateTable(array('id' => $p['id']), $data);
                        break;
                    }
                }
            }
        }
        // Notification Insert
        $this->insertNotification($appointment_details[0]->appoinment_timezone);

        // H-3-4
        // if (settings('tiwilio_option') == '1') {
        //     $this->send_appoinment_sms($appointment_id);
        // }

        return $appointment_id;
    }

    /**
     * Appointment Checkout Page
     * 
     * 
     * @return mixed
     */
    public function checkout()
    {
        if (session('role') == '1') {
            redirect(base_url() . 'dashboard');
        } else if (session('doctor_id') == "" || session('doctor_id') == 0) {
            session()->setFlashdata('error_message', "Please Choose Doctor To Book Appointment");
            return redirect()->to(session('module'));
        } else if (!empty(session('appointment_details'))) {
            $packages = $this->packageModel->getUserPackages(session('user_id'));
            $doctor_id = session('doctor_id');
            $coupon = session('coupon')?session('coupon'): false;
            $coupons= $this->packageModel->getUserCoupons(session('user_id'));
            $doctor_details = user_detail(session('doctor_id'));
            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $doctor_details['currency_code'];
            $fmt = new \NumberFormatter( "en-US@currency=$currency_option", \NumberFormatter::CURRENCY );
            $rate_symbol = $fmt->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
            $this->data['packages'] = $packages;
            $this->data['coupons'] = $coupons;
            $this->data['page'] = 'checkout';
            $this->data['doctor_id'] = $doctor_id;
            $this->data['rate_symbol'] = $rate_symbol;
            $this->data['doctors'] = $this->homeModel->getVerifiedUserDetails($doctor_id);
            $this->data['patients'] = $this->homeModel->getVerifiedUserDetails(session('user_id'));
            $this->data['appointment_details'] = session('appointment_details');
            echo  view('user/home/appointmentCheckout', $this->data);
        } else {
            redirect('signin');
        }
    }
    /**
     * Package Checkout Page
     *
     *
     * @return mixed
     */
    public function checkoutPackage()
    {
        if (!empty(session('appointment_details'))) {
            $user_currency = get_user_currency();
            $user_currency_code = $user_currency['user_currency_code'];
            $currency_option = $user_currency_code;
            $fmt = new \NumberFormatter( "en-US@currency=$currency_option", \NumberFormatter::CURRENCY );
            $rate_symbol = $fmt->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
            $this->data['page'] = 'checkout-package';
            $this->data['rate_symbol'] = $rate_symbol;
            $this->data['patients'] = $this->homeModel->getVerifiedUserDetails(session('user_id'));
            $this->data['appointment_details'] = session('appointment_details');
            if(is_array($this->data['patients'])){
                echo  view('user/home/packageCheckout', $this->data);
            }
            else{
                echo  view('user/home/index');
            }
        } else {
            redirect('signin');
        }
    }

    /**
     * Get Invoice No for Appointments
     * 
     * @return mixed
     */
    private function getInvoiceNo()
    {
        $orderBy = ['id' => 'desc'];
        $invoice = $this->homeModel->getTblRowOfData('payments', [], "id", $orderBy);
        if (empty($invoice)) {
            $invoice_id = 0;
        } else {
            $invoice_id = $invoice['id'];
        }
        $invoice_id = 'I' . sprintf("%05d", ++$invoice_id);
        return $invoice_id;
    }

    /**
     * Inserting Notification from appointments
     * 
     * @param string $time_zone
     * @return mixed
     */
    private function insertNotification($time_zone)
    {
        $notification = array(
            'user_id' => session('user_id'),
            'to_user_id' => session('doctor_id'),
            'type' => "Appointment",
            'text' => "has booked appointment to",
            'created_at' => date("Y-m-d H:i:s"),
            'time_zone' => $time_zone
        );
        $this->homeModel->insertData('notification', $notification);

        session()->remove('appointment_details');
        session()->remove('doctor_id');
    }

    /**
     * Common Appointment insert for all type appointment booking
     * 
     * @param string $type
     * @param int $payment_id
     * @param mixed $appointment_details
     * @return mixed
     */
    private function insertAppointment($type, $payment_id, $appointment_details)
    {
        $appointmentdata['payment_id'] =  $payment_id;
        $appointmentdata['appointment_from'] = session('user_id');
        $appointmentdata['appointment_to'] = session('doctor_id');
        $appointmentdata['from_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time;
        $appointmentdata['to_date_time'] = $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_end_time;
        $appointmentdata['appointment_date'] = $appointment_details[0]->appoinment_date;
        $appointmentdata['appointment_time'] = $appointment_details[0]->appoinment_start_time;
        $appointmentdata['appointment_end_time'] = $appointment_details[0]->appoinment_end_time;
        $appointmentdata['appoinment_token'] = $appointment_details[0]->appoinment_token;
        $appointmentdata['appoinment_session'] = $appointment_details[0]->appoinment_session;

        $appointmentdata['tokboxsessionId'] = '';
        $appointmentdata['tokboxtoken'] = '';

        $appointmentdata['paid'] = 1;
        $appointmentdata['approved'] = 1;
        $appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
        $appointmentdata['created_date'] = date('Y-m-d H:i:s');
        $appointmentdata['reason'] = $appointment_details[0]->reason;
            // Pet update code
    //added new on 13rd June 2024 by Muddasar
        $appointmentdata['pet_id'] = $appointment_details[0]->appoinment_pet_id;

        if ($type == "Razorpay" || $type == "Paypal" || $type == "Stripe") {
            $appointmentdata['type'] = $this->language['lg_online'];
        } else if ($type == "Pay on Arrive") {
            $appointmentdata['type'] = "Clinic";
        } else {
            $appointmentdata['type'] = "Free Booking";
        }

        if ($type == 'Razorpay' || $type == "Pay on Arrive") {
            $appointmentdata['payment_method'] = $this->request->getPost('payment_method');
        } else if ($type == "Paypal") {
            $appointmentdata['payment_method'] = 'Paypal';
        } else if ($type == "Free Booking") {
            $appointmentdata['payment_method'] = 'Online';
        } else {
            $appointmentdata['payment_method'] = 'Stripe';
        }

        if ($type != 'Pay on Arrive' && $type != "Free") {
            $opentok = new OpenTok($this->tokboxKey, $this->tokboxSecret);
            // An automatically archived session:
            $sessionOptions = array(
                //'archiveMode' => ArchiveMode::ALWAYS,
                'mediaMode' => MediaMode::ROUTED
            );
            $new_session = $opentok->createSession($sessionOptions);
            // Store this sessionId in the database for later use
            $tokboxsessionId = $new_session->getSessionId();
            $tokboxtoken = $opentok->generateToken($tokboxsessionId);

            $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
            $appointmentdata['tokboxtoken'] = $tokboxtoken;

        }

        $insert = $this->homeModel->insertData('appointments', $appointmentdata);

        return $insert['id'];
    }

    /**
     * Common Appointment insert for all type appointment booking
     *
     * @param int $payment_id
     * @return mixed
     */
    private function insertPackage($payment_id)
    {
        $appointmentdata['payment_id'] =  $payment_id;
        $appointmentdata['user_id'] = session('user_id');
        $appointmentdata['paid'] = 1;
        $appointmentdata['count'] = session('package_count');
        $appointmentdata['count_used'] = 0;
        //$appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
        //$appointmentdata['created_date'] = date('Y-m-d H:i:s');
        $insert = $this->homeModel->insertData('packages', $appointmentdata);
        return $insert['id'];
    }
    /**
     * Common Coupon insert for all type appointment booking
     *
     * @param int $coupon_type
     * @return mixed
     */
    private function insertCoupon($coupon_type, $used)
    {
        $coupondata['coupon'] =  $coupon_type;
        $coupondata['user_id'] = session('user_id');
        $coupondata['coupon_reason'] = $used;
        $insert = $this->homeModel->insertData('users_coupons', $coupondata);
        return $insert['id'];
    }
    /**
     * creating appoinment in pay on arrive
     * 
     * 
     * @return mixed
     */
    public function addAppoinments()
    {
        $invoice_no = $this->getInvoiceNo();
        $appointment_id = "";

        $paymentdata = array(
            'user_id' => session('user_id'),
            'doctor_id' => session('doctor_id'),
            'invoice_no' => $invoice_no,
            'per_hour_charge' => session('hourly_rate'),
            'total_amount' => session('total_amount'),
            'currency_code' => session('currency_code'),
            'txn_id' => '',
            'order_id' => 'OD' . time() . rand(),
            'transaction_status' => '',
            'payment_type' => 'Pay on Arrive',
            'tax' => !empty(settings("tax")) ? settings("tax") : "0",
            'tax_amount' => session('tax_amount'),
            'transcation_charge' => session('transcation_charge'),
            'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
            'payment_status' => 0,
            'payment_date' => date('Y-m-d H:i:s'),
        );
        $insert = $this->homeModel->insertData('payments', $paymentdata);
        $payment_id = $insert['id'];

        // Sending notification to mentor
        $doctor_id = session('doctor_id');
        $appointment_details = session('appointment_details');

        // Insert Appointment
        $appointment_id = $this->insertAppointment('Pay on Arrive', $payment_id, $appointment_details);

        // Insert Notification
        $this->insertNotification($appointment_details[0]->appoinment_timezone);


        // H-3-4
        // if (settings('tiwilio_option') == '1') {
        //     $this->send_appoinment_sms($appointment_id);
        // }

        $results = array('status' => 200, 'appointment_id' => base64_encode($appointment_id));
        echo json_encode($results);
    }

    /**
     * creating appointment with card payment
     * 
     * 
     * @return mixed
     */
    public function makeStripePayment()
    {
        $appointment_details = session('appointment_details');

        $booked_session = get_booked_session($appointment_details[0]->appoinment_session, $appointment_details[0]->appoinment_token, $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time, session('doctor_id'));
        $stripe_secert_key = "";
        if ($booked_session >= 1) {
            $response['status'] = 500;
            $response['message'] = 'This token already booked';
            echo json_encode($response);
        } else {
            $stripe_option = !empty(settings("stripe_option")) ? settings("stripe_option") : "";
            if ($stripe_option == '1') {
                $stripe_secert_key = !empty(settings("sandbox_rest_key")) ? settings("sandbox_rest_key") : "";
            }
            if ($stripe_option == '2') {
                $stripe_secert_key = !empty(settings("live_rest_key")) ? settings("live_rest_key") : "";
            }

            $currency_code = session('currency_code');

            $amount = get_doccure_currency(session('total_amount'), $currency_code, 'USD');

            $amount = number_format($amount, 2, '.', '');

            \Stripe\Stripe::setApiKey($stripe_secert_key);

            $intent = null;

            try {
                if (isset($_POST['payment_method_id'])) {
                    # Create the PaymentIntent
                    $intent = \Stripe\PaymentIntent::create([
                        'payment_method' => $_POST['payment_method_id'],
                        'amount' => ($amount * 100),
                        'currency' => 'USD',
                        'confirmation_method' => 'manual',
                        'confirm' => true,
                    ]);
                }
                else if (isset($_POST['payment_intent_id'])) {
                    $intent = \Stripe\PaymentIntent::retrieve(
                        $_POST['payment_intent_id']
                    );
                    $intent->confirm();
                }

                $this->generateResponse($intent);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                # Display error on client
                $results = array('status' => 500, 'message' => $e->getMessage());
                echo json_encode($results);
            }
        }
    }

    /**
     * creating appointment with card payment
     *
     *
     * @return mixed
     */
    public function makeMamoPayPaymentInit()
    {
        $appointment_details = session('appointment_details');
        if($appointment_details[0]->type == 'package'){
            $currency_code = session('currency_code');
            $total_amount = session('total_amount');
            $coupon_type = session('coupon_type')?session('coupon_type'):false;
            if($coupon_type){
                if(stripos($coupon_type, 'special') !== false){
                    $discount_percent = str_replace('special', '', $coupon_type);
                    $total_amount_d = session('total_amount')*($discount_percent/100);
                    $total_amount = $total_amount - $total_amount_d;
                }
                if(stripos($coupon_type, 'amount') !== false){
                    $discount_percent = str_replace('amount', '', $coupon_type);
                    $total_amount = $total_amount - $discount_percent;
                    if($total_amount < 0)
                        $total_amount = 0;
                }
            }
            $this->homeModel->updateData('users_coupons',['user_id'=>session('user_id'), 'coupon' => 'mandarin'],['coupon_reason'=> 'used', 'date' => date('Y-m-d H:i:s')]);
           //$amount = get_doccure_currency($total_amount, $currency_code, 'USD');
            //$amount = number_format($amount, 2, '.', '');

            $user_detail = user_detail(session('user_id'));
            $email = libsodiumDecrypt($user_detail['email']);

            $ch = curl_init();

// Set the URL and other options for the cURL request
// Memopay key Muddasar ali
            curl_setopt($ch, CURLOPT_URL, 'https://business.mamopay.com/manage_api/v1/links');
            //sandbox link             curl_setopt($ch, CURLOPT_URL, 'https://sandbox.dev.business.mamopay.com/manage_api/v1/links');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);

// Prepare the request body
            $body = json_encode([
                'title' => 'Package',
                'description' => 'Pet booking appointment Package',
                'active' => true,
                'return_url' => base_url().'appointment-mamopay-payment-success',
                'failure_return_url' => base_url().'appointment-mamopay-payment-failed',
                'processing_fee_percentage' => 2,// to profit for Mamo pay
                'amount' => $total_amount,
                'amount_currency' => $currency_code,
                // 'payment_methods' => ['card'],
                'link_type' => 'inline',
                'enable_tabby' => false,
                'enable_message' => false,
                'enable_tips' => false,
                'save_card' => 'off',
                'enable_customer_details' => true,
                'enable_quantity' => false,
                'enable_qr_code' => false,
                'send_customer_receipt' => false,
                'hold_and_charge_later' => false,
                'email' => $email
            ]);

// Set the request body and headers
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer sk-8d8f778d-fe84-4ea2-a8d8-6b315a6ff305'
            ]);
            //Memo live key sk-f5f4713e-6b58-488f-a32a-14d565bd8240
            //Memo demo key 'Authorization: Bearer sk-8d8f778d-fe84-4ea2-a8d8-6b315a6ff305'
// Execute the request and capture the response
            $response = curl_exec($ch);

// Check for errors
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            } else {
                // Output the response
                echo $response;
            }
            curl_close($ch);
        }else{
            $booked_session = get_booked_session($appointment_details[0]->appoinment_session, $appointment_details[0]->appoinment_token, $appointment_details[0]->appoinment_date . ' ' . $appointment_details[0]->appoinment_start_time, session('doctor_id'));
            $stripe_secert_key = "";
            if ($booked_session >= 1) {
                $response['status'] = 500;
                $response['message'] = 'This token already booked';
                echo json_encode($response);
            } else {
                $currency_code = session('currency_code');

                //$amount = get_doccure_currency(session('total_amount'), $currency_code, 'USD');
                //$amount = 10;
                //$amount = number_format($amount, 2, '.', '');

                $user_detail = user_detail(session('user_id'));
                $email = libsodiumDecrypt($user_detail['email']);

                $ch = curl_init();

// Set the URL and other options for the cURL request
                curl_setopt($ch, CURLOPT_URL, 'https://business.mamopay.com/manage_api/v1/links');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);

// Prepare the request body
                $body = json_encode([
                    'title' => 'Booking appointment',
                    'description' => 'Pet booking appointment',
                    'active' => true,
                    'return_url' => base_url().'appointment-mamopay-payment-success',
                    'failure_return_url' => base_url().'appointment-mamopay-payment-failed',
                    'processing_fee_percentage' => 2, // to profit for Mamo pay
                    'amount' => session('total_amount'),
                    'amount_currency' => $currency_code,
                    //'payment_methods' => ['card'],
                    'link_type' => 'inline',
                    'enable_tabby' => false,
                    'enable_message' => false,
                    'enable_tips' => false,
                    'save_card' => 'off',
                    'enable_customer_details' => true,
                    'enable_quantity' => false,
                    'enable_qr_code' => false,
                    'send_customer_receipt' => false,
                    'hold_and_charge_later' => false,
                    'email' => $email
                ]);

// Set the request body and headers
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Authorization: Bearer sk-8d8f778d-fe84-4ea2-a8d8-6b315a6ff305'
                ]);
                //sk-f5f4713e-6b58-488f-a32a-14d565bd8240
// Execute the request and capture the response
                $response = curl_exec($ch);

// Check for errors
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                } else {
                    // Output the response
                    echo $response;
                }
                curl_close($ch);
            }
        }

    }
    /**
     * Payment Generate Response
     * 
     * @param mixed $intent
     * @return mixed
     */
    private function generateResponse($intent)
    {
        # Note that if your API version is before 2019-02-11, 'requires_action'
        # appears as 'requires_source_action'.
        if (($intent->status == 'requires_action' || $intent->status == 'requires_source_action') && $intent->next_action->type == 'use_stripe_sdk') {
            # Tell the client to handle the action
            $results = array('status' => 201, 'requires_action' => true, 'payment_intent_client_secret' => $intent->client_secret);
            echo json_encode($results);
        } else if ($intent->status == 'succeeded') {
            # The payment didn’t need any additional actions and completed!
            # Handle post-payment fulfillment
            $this->stripePayment($intent);
        } else {
            # Invalid status
            $results = array('status' => 500, 'message' => 'Transaction failure!.Please try again', 'error' => $intent);
            echo json_encode($results);
        }
    }
    /**
     * inserting payment and add appointment after stripe payment success
     * 
     * 
     * @param mixed $intent
     * @return mixed
     */
    public function stripePayment($intent)
    {
        $doctor_id = session('doctor_id');

        $amount = session('total_amount');
        $currency_code = session('currency_code');

        $transaction_status = json_encode($intent);
        $txnid = time() . rand();
        $appointment_id = "";

        /* Get Invoice id */
        $invoice_no = $this->getInvoiceNo();

        // Store the Payment details

        // $amount=get_doccure_currency($amount,'USD',$currency_code);

        $amount = number_format($amount, 2, '.', '');

        $payments_data = array(
            'user_id' => session('user_id'),
            'doctor_id' => $doctor_id,
            'invoice_no' => $invoice_no,
            'per_hour_charge' => session('hourly_rate'),
            'total_amount' => $amount,
            'currency_code' => $currency_code,
            'txn_id' => $txnid,
            'order_id' => 'OD' . time() . rand(),
            'transaction_status' => $transaction_status,
            'payment_type' => 'Stripe',
            'tax' => !empty(settings("tax")) ? settings("tax") : "0",
            'tax_amount' => session('tax_amount'),
            'transcation_charge' => session('transcation_charge'),
            'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
            'payment_status' => 1,
            'payment_date' => date('Y-m-d H:i:s'),
        );
        $insert = $this->homeModel->insertData('payments', $payments_data);
        $payment_id = $insert['id'];

        $appointment_details = session('appointment_details');

        // Insert Appointment 
        $appointment_id = $this->insertAppointment('Stripe', $payment_id, $appointment_details);

        // Notification Add
        $this->insertNotification($appointment_details[0]->appoinment_timezone);

        $this->send_appoinment_mail($appointment_id);
        // H-3-4
        // if (settings('tiwilio_option') == '1') {
        //  $this->send_appoinment_sms($appointment_id);
        // }

        $results = array('status' => 200, 'appointment_id' => base64_encode($appointment_id));
        echo json_encode($results);
    }

    /**
     * inserting payment and add appointment after stripe payment success
     *
     * @return mixed
     */
    public function makeMamoPayPaymentSuccess()
    {
        $appointment_details = session('appointment_details');
        if($appointment_details[0]->type == 'package'){
            $amount = session('total_amount');
            $currency_code = session('currency_code');
            $amount = number_format($amount, 2, '.', '');
            $invoice_no = $this->getInvoiceNo();
            $transaction_status = 'complete';
            $txnid = time() . rand();
            $payments_data = array(
                'user_id' => session('user_id'),
                'doctor_id' => 1,
                'invoice_no' => $invoice_no,
                'per_hour_charge' => session('hourly_rate'),
                'total_amount' => $amount,
                'currency_code' => 'AED', //$currency_code
                'txn_id' => $txnid,
                'order_id' => 'OD' . time() . rand(),
                'transaction_status' => $transaction_status,
                'payment_type' => 'MamoPay',
                'tax' => !empty(settings("tax")) ? settings("tax") : "0",
                'tax_amount' => session('tax_amount'),
                'transcation_charge' => session('transcation_charge'),
                'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
                'payment_status' => 1,
                'payment_date' => date('Y-m-d H:i:s'),
            );
            $insert = $this->homeModel->insertData('payments', $payments_data);
            $payment_id = $insert['id'];
            // Insert Package
            $package_id = $this->insertPackage($payment_id);

            $this->send_appoinment_mail($package_id);
            return view('user/home/packageSuccess', $this->data);
        }else{
            $doctor_id = session('doctor_id');

            $amount = session('total_amount');
            $currency_code = session('currency_code');

            $transaction_status = 'complete';
            $txnid = time() . rand();
            $appointment_id = "";

            /* Get Invoice id */
            $invoice_no = $this->getInvoiceNo();

            // Store the Payment details

            // $amount=get_doccure_currency($amount,'USD',$currency_code);

            $amount = number_format($amount, 2, '.', '');

            $payments_data = array(
                'user_id' => session('user_id'),
                'doctor_id' => $doctor_id,
                'invoice_no' => $invoice_no,
                'per_hour_charge' => session('hourly_rate'),
                'total_amount' => $amount,
                'currency_code' => $currency_code,
                'txn_id' => $txnid,
                'order_id' => 'OD' . time() . rand(),
                'transaction_status' => $transaction_status,
                'payment_type' => 'MamoPay',
                'tax' => !empty(settings("tax")) ? settings("tax") : "0",
                'tax_amount' => session('tax_amount'),
                'transcation_charge' => session('transcation_charge'),
                'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
                'payment_status' => 1,
                'payment_date' => date('Y-m-d H:i:s'),
            );
            $insert = $this->homeModel->insertData('payments', $payments_data);
            $payment_id = $insert['id'];
            // Insert Appointment
            $appointment_id = $this->insertAppointment('Stripe', $payment_id, $appointment_details);

            // Notification Add
            $this->insertNotification($appointment_details[0]->appoinment_timezone);

            $this->send_appoinment_mail($appointment_id);
            // H-3-4
            // if (settings('tiwilio_option') == '1') {
            //  $this->send_appoinment_sms($appointment_id);
            // }


            $this->data['appointment_details'] = $this->homeModel->getAppoinmentsDetails($appointment_id);
            $this->data['page'] = 'payment_success';
        }

        return view('user/home/appointmentSuccess', $this->data);
    }

    /**
     * Booking success page after Payment success
     * 
     * 
     * @param mixed $appointment_id
     *  @return mixed
     */
    public function paymentSuccess($appointment_id)
    {
        $this->data['appointment_details'] = $this->homeModel->getAppoinmentsDetails(base64_decode($appointment_id));
        $this->data['page'] = 'payment_success';
        return view('user/home/appointmentSuccess', $this->data);
    }
    /**
     * Create Razorpay Orders.
     * 
     *  @return mixed
     */
    public function createRazorpayOrders()
    {
        $amount = $this->request->getPost('amount');

        $currency_code = $this->request->getPost('currency_code');

        $amount = get_doccure_currency($amount, $currency_code, 'USD');

        $amount = number_format($amount, 2, '.', '');
        $api_key = "";
        $api_secret = "";

        $razorpay_option = !empty(settings("razorpay_option")) ? settings("razorpay_option") : "";
        if ($razorpay_option == '1') {
            $api_key = !empty(settings("sandbox_key_id")) ? settings("sandbox_key_id") : "";
            $api_secret = !empty(settings("sandbox_key_secret")) ? settings("sandbox_key_secret") : "";
        }
        if ($razorpay_option == '2') {
            $api_key = !empty(settings("live_key_id")) ? settings("live_key_id") : "";
            $api_secret = !empty(settings("live_key_secret")) ? settings("live_key_secret") : "";
        }
        /** @var Api $api */
        $api = new Api($api_key, $api_secret);
        // /** @var array $order */
        $order  = $api->order->create(array('receipt' => time(), 'amount' => ($amount * 100), 'currency' => 'USD'));

        $user_detail = user_detail(session('user_id'));

        $response['order_id'] = $order['id'];
        $response['key_id'] = $api_key;
        $response['amount'] = ($amount * 100);
        $response['currency'] = 'USD';
        $response['sitename'] = !empty(settings("meta_title")) ? settings("meta_title") : "Doccure";
        $response['siteimage'] = !empty(base_url() . settings("logo_front")) ? base_url() . settings("logo_front") : base_url() . "assets/img/logo.png";
        $response['patientname'] = ucfirst(libsodiumDecrypt($user_detail['first_name']) . ' ' . libsodiumDecrypt($user_detail['last_name']));
        $response['email'] = libsodiumDecrypt($user_detail['email']);
        $response['mobileno'] = libsodiumDecrypt($user_detail['mobileno']);

        echo json_encode($response);
    }
    /**
     * Doctor Appointment With Razorpay
     * 
     * @return mixed
     */
    public function razorpayAppoinments()
    {
        $doctor_id = session('doctor_id');

        $amount = $this->request->getPost('amount');
        $payment_id = $this->request->getPost('payment_id');
        $order_id = $this->request->getPost('order_id');
        $signature = $this->request->getPost('signature');
        $currency_code = $this->request->getPost('currency_code');
        $api_key = "";
        $api_secret = "";
        $appointment_id = "";

        $razorpay_option = !empty(settings("razorpay_option")) ? settings("razorpay_option") : "";
        if ($razorpay_option == '1') {
            $api_key = !empty(settings("sandbox_key_id")) ? settings("sandbox_key_id") : "";
            $api_secret = !empty(settings("sandbox_key_secret")) ? settings("sandbox_key_secret") : "";
        }
        if ($razorpay_option == '2') {
            $api_key = !empty(settings("live_key_id")) ? settings("live_key_id") : "";
            $api_secret = !empty(settings("live_key_secret")) ? settings("live_key_secret") : "";
        }

        $api = new Api($api_key, $api_secret);

        $attributes  = array('razorpay_signature'  => $signature,  'razorpay_payment_id'  => $payment_id,  'razorpay_order_id' => $order_id);
        $order  = $api->utility->verifyPaymentSignature($attributes);
        $response['payment_id'] = $payment_id;
        $response['order_id'] = $order_id;
        $response['signature'] = $signature;

        $transaction_status = json_encode($response);

        $txnid = $payment_id;

        /* Get Invoice id */
        $invoice_no = $this->getInvoiceNo();

        // Store the Payment details

        $payments_data = array(
            'user_id' => session('user_id'),
            'doctor_id' => $doctor_id,
            'invoice_no' => $invoice_no,
            'per_hour_charge' => session('hourly_rate'),
            'total_amount' => $amount,
            'currency_code' => $currency_code,
            'txn_id' => $txnid,
            'order_id' => 'OD' . time() . rand(),
            'transaction_status' => $transaction_status,
            'payment_type' => 'Razorpay',
            'tax' => !empty(settings("tax")) ? settings("tax") : "0",
            'tax_amount' => session('tax_amount'),
            'transcation_charge' => session('transcation_charge'),
            'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
            'payment_status' => 1,
            'payment_date' => date('Y-m-d H:i:s'),
        );
        $insert = $this->homeModel->insertData('payments', $payments_data);
        $payment_id = $insert['id'];

        $appointment_details = session('appointment_details');

        $appointment_id = $this->insertAppointment('Razorpay', $payment_id, $appointment_details);

        // Notification Insert
        $this->insertNotification($appointment_details[0]->appoinment_timezone);

        $this->send_appoinment_mail($appointment_id);
        // if(settings('tiwilio_option')=='1') {
        //     $this->send_appoinment_sms($appointment_id);
        // }


        $results = array('status' => 200, 'appointment_id' => base64_encode($appointment_id));

        echo json_encode($results);
    }
    /**
     * Doctor Schedule Timing Got By Clinic
     * 
     * @return mixed
     */
    public function getClinicScheduleFromDate()
    {
        $schedule_date = $this->request->getPost('schedule_date');
        $doctor_id = $this->request->getPost('doctor_id');
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $day = date('D', strtotime(str_replace('/', '-', $schedule_date ?? "")));
        $day_id = 0;
        switch ($day) {
            case 'Sun':
                $day_id = 1;
                break;
            case 'Mon':
                $day_id = 2;
                break;
            case 'Tue':
                $day_id = 3;
                break;
            case 'Wed':
                $day_id = 4;
                break;
            case 'Thu':
                $day_id = 5;
                break;
            case 'Fri':
                $day_id = 6;
                break;
            case 'Sat':
                $day_id = 7;
                break;
            default:
                $day_id = 0;
                break;
        }
        $data['schedule'] =  $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $doctor_id, 'day_id' => $day_id], '*');
        $data['schedule_date'] = $schedule_date;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $start_date = date('H:i', strtotime($start_date ?? ""));
        $end_date = date('H:i', strtotime($end_date ?? ""));
        echo  $this->apptModel->checkDoctorAvalSlot($doctor_id, $day_id, $start_date, $end_date);
        // $data['language'] = $this->language;
        // print_r($data['schedule']);
        // return view('user/clinic/clinicAppointmentView', $data);
    }
    /**
     * Checking Call Are Arraive
     * 
     * 
     * @return mixed
     */
    public function appointmentGetCall()
    {
        $user_id = session('user_id');
        $response['status'] = 500;
        $result = $this->apptModel->getCall($user_id);
        if (!empty($result)) {
            $appointmentData = $this->homeModel->getTblRowOfData('appointments', ['id' => $result['appointments_id']], '*', []);
            $app_time_zone = $appointmentData['time_zone'];
            $app_current_timezone = session('time_zone');

            $app_end_time =  converToTz($appointmentData['appointment_date'] . ' ' . $appointmentData['appointment_end_time'], $app_current_timezone, $app_time_zone);
            // echo $app_end_time;
            if (date('Y-m-d H:i:s') < $app_end_time) {
                $response['status'] = 200;
                $response['name'] = libsodiumDecrypt($result['first_name']) . " " . libsodiumDecrypt($result['last_name']);
                $response['profileimage'] = (!empty($result['profileimage'])) ? base_url() . $result['profileimage'] : base_url() . 'assets/img/user.png';
                $response['role'] = ($result['role'] == '1') ? 'Dr.' : '';
                $response['appointment_id'] = md5($result['appointments_id']);
                $response['call_type'] = $result['call_type'];
            }
        }
        echo json_encode($response);
    }
    /**
     * Video Call Appointment
     * 
     * 
     * @param int $appoinment_id
     * @return mixed
     */
    public function outGoingVideoCall($appoinment_id)
    {
        // $this->homeModel->updateData('appointments',['md5(id)'=>$appoinment_id],['call_end_status'=>1]);
        $appoinments_details = $this->apptModel->getAppoinmentCallDetails($appoinment_id);
        if (session('role') == '1') {
            $data['appoinments_details'] = $appoinments_details;
            $data['role'] = 'patient';
            // Notification
            $response['from_user_id'] = $appoinments_details['appointment_from'];
            $response['from_name'] = ($appoinments_details['doctor_firstname']);
            $response['to'] = $appoinments_details['appointment_to'];
            $notifydata['include_player_ids'] = $appoinments_details['patient_device_id'];
            $device_type = $appoinments_details['patient_device_type'];
            $notifydata['message'] = 'Incoming call from ' . ($appoinments_details['doctor_firstname']);
            $response['invite_id'] = $appoinments_details['id'];
            $response['type'] = 'video';
            $response['sessionId'] = $appoinments_details['tokboxsessionId'];
            $response['token'] = $appoinments_details['tokboxtoken'];
            $response['tokbox_apiKey'] = $this->tokboxKey;
            $response['tokbox_apiSecret'] = $this->tokboxSecret;
            $notifydata['notifications_title'] = 'Incoming call';
            $notifydata['additional_data'] = $response;

            //H-3-4
            // if(!empty($notifydata['include_player_ids']))
            // {                
            //     if($device_type=='Android')
            //     {
            //     sendFCMNotification($notifydata);
            //     }
            //     if($device_type=='IOS')
            //     {
            //     sendiosNotification($notifydata);
            //     }
            // }
            $data['type'] = 1;
            $this->call_details($data['appoinments_details'], 'patient', 'Video');
        } else {
            $data['appoinments_details'] = $appoinments_details;
            $data['role'] = 'doctor';
            // Notification
            $response['from_user_id'] = $appoinments_details['appointment_from'];
            $response['from_name'] = ($appoinments_details['patient_firstname']);
            $response['to'] = $appoinments_details['appointment_to'];
            $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
            $device_type = $appoinments_details['doctor_device_type'];
            $notifydata['message'] = 'Incoming call from ' . ($appoinments_details['patient_firstname']);
            $response['invite_id'] = $appoinments_details['id'];
            $response['type'] = 'video';
            $response['sessionId'] = $appoinments_details['tokboxsessionId'];
            $response['token'] = $appoinments_details['tokboxtoken'];
            $response['tokbox_apiKey'] = $this->tokboxKey;
            $response['tokbox_apiSecret'] = $this->tokboxSecret;
            $notifydata['notifications_title'] = 'Incoming call';
            $notifydata['additional_data'] = $response;

            //H-3-4
            // if(!empty($notifydata['include_player_ids']))
            // {
            //     if($device_type=='Android')
            //     {
            //     sendFCMNotification($notifydata);
            //     }
            //     if($device_type=='IOS')
            //     {
            //     sendiosNotification($notifydata);
            //     }
            // }
            $data['type'] = 1; //Outgoing Call
            $this->call_details($data['appoinments_details'], 'doctor', 'Video');
        }
        return view('user/call/videoCall', $data);
    }
    /**
     * Audio Call Outgoing
     * 
     * 
     * @param int $appoinment_id
     * @return mixed
     */
    public function outGoingAudioCall($appoinment_id)
    {
        // $this->homeModel->updateData('appointments',['md5(id)'=>$appoinment_id],['call_end_status'=>1]);
        $appoinments_details = $this->apptModel->getAppoinmentCallDetails($appoinment_id);
        if (session('role') == '1') {
            $data['appoinments_details'] = $appoinments_details;
            $data['role'] = 'patient';
            // Notification
            $response['from_user_id'] = $appoinments_details['appointment_from'];
            $response['from_name'] = $appoinments_details['doctor_firstname'];
            $response['to'] = $appoinments_details['appointment_to'];
            $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
            $device_type = $appoinments_details['doctor_device_type'];
            $notifydata['message'] = 'Incoming call from ' . $appoinments_details['doctor_firstname'];
            $response['invite_id'] = $appoinments_details['id'];
            $response['type'] = 'audio';
            $response['sessionId'] = $appoinments_details['tokboxsessionId'];
            $response['token'] = $appoinments_details['tokboxtoken'];
            $response['tokbox_apiKey'] = $this->tokboxKey;
            $response['tokbox_apiSecret'] = $this->tokboxSecret;
            $notifydata['notifications_title'] = 'Incoming call';
            $notifydata['additional_data'] = $response;

            // H-3-4
            // if(!empty($notifydata['include_player_ids']))
            // {
            //   if($device_type=='Android')
            //   {
            //     sendFCMNotification($notifydata);
            //   }
            //   if($device_type=='IOS')
            //   {
            //     sendiosNotification($notifydata);
            //   }
            // }

            $data['type'] = 1; //Outgoing Call
            $this->call_details($data['appoinments_details'], 'patient', 'Audio');
        } else {
            $data['appoinments_details'] = $appoinments_details;
            $data['role'] = 'doctor';

            // Notification
            $response['from_user_id'] = $appoinments_details['appointment_from'];
            $response['from_name'] = $appoinments_details['patient_firstname'];
            $response['to'] = $appoinments_details['appointment_to'];
            $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
            $device_type = $appoinments_details['doctor_device_type'];
            $notifydata['message'] = 'Incoming call from ' . $appoinments_details['patient_firstname'];
            $response['invite_id'] = $appoinments_details['id'];
            $response['type'] = 'audio';
            $response['sessionId'] = $appoinments_details['tokboxsessionId'];
            $response['token'] = $appoinments_details['tokboxtoken'];
            $response['tokbox_apiKey'] = $this->tokboxKey;
            $response['tokbox_apiSecret'] = $this->tokboxSecret;
            $notifydata['notifications_title'] = 'Incoming call';
            $notifydata['additional_data'] = $response;
            // H-3-4
            // if(!empty($notifydata['include_player_ids']))
            // {
            //   if($device_type=='Android')
            //   {
            //     sendFCMNotification($notifydata);
            //   }
            //   if($device_type=='IOS')
            //   {
            //     sendiosNotification($notifydata);
            //   }
            // }

            $data['type'] = 1; //Outgoing Call
            $this->call_details($data['appoinments_details'], 'doctor', 'Audio');
        }
        return view('user/call/audioCall', $data);
    }
    /**
     * Incoming Audio And Video Call
     * 
     * 
     * @param int $appoinment_id
     * @return mixed
     */
    public function inComingVideoCall($appoinment_id)
    {
        if (session('role') == '1') {
            $data['appoinments_details'] = $this->apptModel->getAppoinmentCallDetails($appoinment_id);
            $data['role'] = 'patient';
            $data['type'] = 2; //Incoming Call
            $this->remove_call_details($data['appoinments_details']['id']);
            $this->call_accept($data['appoinments_details']['id']);
        } else {

            $data['appoinments_details'] = $this->apptModel->getAppoinmentCallDetails($appoinment_id);
            $data['role'] = 'doctor';
            $data['type'] = 2; //Incoming Call
            $this->remove_call_details($data['appoinments_details']['id']);
            $this->call_accept($data['appoinments_details']['id']);
        }
        return view('user/call/videoCall', $data);
    }
    /**
     * Incoming Audio  Call
     * 
     * 
     * @param int $appoinment_id
     * @return mixed
     */
    public function inComingAudioCall($appoinment_id)
    {
        if (session('role') == '1') {
            $data['appoinments_details'] = $this->apptModel->getAppoinmentCallDetails($appoinment_id);
            $data['role'] = 'patient';
            $data['type'] = 2; //Incoming Call
            $this->remove_call_details($data['appoinments_details']['id']);
            $this->call_accept($data['appoinments_details']['id']);
        } else {

            $data['appoinments_details'] = $this->apptModel->getAppoinmentCallDetails($appoinment_id);
            $data['role'] = 'doctor';
            $data['type'] = 2; //Incoming Call
            $this->remove_call_details($data['appoinments_details']['id']);
            $this->call_accept($data['appoinments_details']['id']);
        }
        return view('user/call/audioCall', $data);
    }
    /**
     * End The Call
     * 
     * @return mixed
     */
    public function appointmentEndCall()
    {
        $appointment_id = $this->request->getPost('appointment_id');
        $this->homeModel->deleteData('call_details', ['md5(appointments_id)' => $appointment_id]);
        $callStatus = $this->homeModel->getTblRowOfData('appointments', ['md5(id)' => $appointment_id], "id,call_status,appointment_to", []);
        // $callStatus=$this->db->select('call_status')->where('md5(id)',$appointment_id)->get('appointments')->row()->call_status;
        if ($callStatus && $callStatus['call_status'] == 1) {
            $this->homeModel->updateData('appointments', ['md5(id)' => $appointment_id], ['call_end_status' => 1]);
        }
        $doctor_id = $callStatus['appointment_to'];
        echo $doctor_id;
    }
    /**
     * Call Detail Add From Call Triggered
     * 
     * 
     * @param mixed $appoinments_details
     * @param mixed $to
     * @param mixed $call_type
     * @return mixed
     */
    private function call_details($appoinments_details, $to, $call_type)
    {
        $call_from = "";
        $call_to = "";
        if ($to == 'doctor') {
            $call_from = session('user_id');
            $call_to = $appoinments_details['appointment_to'];
        }
        if ($to == 'patient') {
            $call_from = session('user_id');
            $call_to = $appoinments_details['appointment_from'];
        }
        // H-3-4
        // $this->send_appoinment_sms($appoinments_details,$to,$call_type);

        $data['appointments_id'] = $appoinments_details['id'];
        $data['call_from'] = $call_from;
        $data['call_to'] = $call_to;
        $data['call_type'] = $call_type;
        $this->homeModel->insertData('call_details', $data);
    }
    /**
     * Remove Call Detail
     * 
     * @param int $appointments_id
     * @return mixed
     */
    private function remove_call_details($appointments_id)
    {
        $this->homeModel->deleteData('call_details', ['appointments_id' => $appointments_id]);
    }
    /**
     * Update Call Detail
     * 
     * @param int $appointments_id
     * @return mixed
     */
    private function call_accept($appointments_id)
    {
        $this->homeModel->updateData('appointments', ['id' => $appointments_id], ['call_status' => 1]);
    }
    /**
     * Appointment Doctor Detail
     * 
     * 
     * @return mixed
     */
    public function appointmentDoctorDetail()
    {
        $doctor_id = $this->request->getPost('doctor_id');
        $appointment_id = $this->request->getPost('appointment_id');
        $user_detail = user_detail($doctor_id);
        $response['status'] = $this->homeModel->getTblRowOfData('appointments', ['md5(id)' => $appointment_id], "id,call_status,review_status", []);
        $response['name'] = ucfirst(libsodiumDecrypt($user_detail['first_name']) . ' ' . libsodiumDecrypt($user_detail['last_name']));
        echo json_encode($response);
    }
    /**
     * Add Review
     * 
     * @return mixed
     */
    public function addApptReviews()
    {
        $this->homeModel->updateData('appointments', ['md5(id)' => $this->request->getPost('appointment_id')], ['review_status' => 1, 'appointment_status' => 1]);
        $review_data['rating'] = $this->request->getPost('rating');
        $review_data['doctor_id'] = $this->request->getPost('doctor_id');
        $review_data['title'] = $this->request->getPost('title');
        $review_data['review'] = $this->request->getPost('review');
        $review_data['user_id'] = session('user_id');
        $review_data['created_date'] = date('Y-m-d H:i:s');
        $review_data['time_zone'] = date_default_timezone_get();
        $this->homeModel->insertData('rating_reviews', $review_data);
        echo 'success';
    }
    /**
     * Appointment Email Send
     * 
     * @param int $appointment_id
     * @return mixed
     */
    public function send_appoinment_mail($appointment_id)
    {
        $appoinments_details = $this->homeModel->getAppoinmentsDetails($appointment_id);

        $sendmail = new \App\Libraries\SendEmail;
        $sendmail->sendAppoinmentEmail($appoinments_details);
        // H-3-4
        // Notification Code (New Appoinments)
        // if(session('role') == '2')
        // {
        //     $notifydata['include_player_ids'] = $appoinments_details['doctor_device_id'];
        //     $device_type = $appoinments_details['doctor_device_type'];
        //     $nresponse['from_name'] = $appoinments_details['patient_first_name'];
        // }
        // $notifydata['message'] = $nresponse['from_name'].' has booked appointment on '.date('d M Y',strtotime($appoinments_details['created_date']));
        // $notifydata['notifications_title'] = '';
        // $nresponse['type'] = 'Booking';
        // $notifydata['additional_data'] = $nresponse;        
        // if($device_type=='Android')
        // {
        //     sendFCMNotification($notifydata);
        // }
        // if($device_type=='IOS')
        // {
        //     sendiosNotification($notifydata);
        // }
    }

    //   public function send_appoinment_sms($appointment_id)
    //   {

    //         $inputdata=$this->book->get_appoinments_details($appointment_id);

    //         $AccountSid = settings("tiwilio_apiKey");
    //         $AuthToken = settings("tiwilio_apiSecret");
    //         $from = settings("tiwilio_from_no");
    //         $twilio = new Client($AccountSid, $AuthToken);

    //         $msg = $this->language['lg_you_have_new_ap'].' '.$inputdata["patient_name"];

    //         $mobileno="+".$inputdata['doctor_mobile'];

    //         try 
    //         {
    //             $message = $twilio->messages
    //                 ->create($mobileno, // to
    //                         ["body" => $msg, "from" => $from]
    //                 );
    //             $response = array('status' => true);
    //             $status=0;
    //         }
    //         catch (\Exception $error)
    //         {
    //             //echo $error;
    //             $status=500;
    //         }
    //   }
    /**
     * Change Appointment Status
     * 
     * @return mixed
     */
    public function changeAppointmentStatus()
    {
        $appoinments_id = $this->request->getPost('appoinments_id');
        $appoinments_status = $this->request->getPost('appoinments_status');


        $this->homeModel->updateData('appointments', ['id' => $appoinments_id], ['approved' => $appoinments_status]);
        $appointment_details = $this->apptModel->getAppoinmentCallDetails(md5($appoinments_id ?? ""));

        if ($appoinments_status === 0) {
            $notification = array(
                'user_id' => session('user_id'),
                'to_user_id' => $appointment_details['patient_id'],
                'type' => "Appointment Cancel",
                'text' => "has cancelled the appointment of",
                'created_at' => date("Y-m-d H:i:s"),
                'time_zone' => $this->timezone
            );
            $this->homeModel->insertData('notification', $notification);
            //Cancel the appointment 
            // $this->send_appoinment_cancelmail($appoinments_id);
            // if(settings('tiwilio_option')=='1') {
            // $this->send_appoinment_cancelsms($appoinments_id);
            // }
            session()->setFlashdata('success_message', $this->language['lg_your_appointmen'] ?? "");
            return redirect()->to(session('module') . '/appointments');
        } elseif ($appoinments_status === 1) {
            $notification = array(
                'user_id' => session('user_id'),
                'to_user_id' => $appointment_details['patient_id'],
                'type' => "Appointment Accept",
                'text' => "has accepted the appointment of",
                'created_at' => date("Y-m-d H:i:s"),
                'time_zone' => $this->timezone
            );
            $this->homeModel->insertData('notification', $notification);
            //accept the appointment
            // $this->send_appoinment_acceptmail($appoinments_id);
            // if(settings('tiwilio_option')=='1')
            // {
            //     $this->send_appoinment_acceptsms($appoinments_id);
            // }
            session()->setFlashdata('success_message', $this->language['lg_your_appointmen1'] ?? "");
            return redirect()->to(session('module') . '/appointments');
        }
    }
}
