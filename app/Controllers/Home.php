<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HomeModel;
use App\Models\MypatientsModel;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use App\Libraries\PayPalLibrary;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use JasonN\Cart\Cart;
use App\Models\ApiModel;
use App\Models\ReviewModel;
use App\Models\UserModel;

class Home extends BaseController
{
    // public mixed $data;
    // public mixed $session;
    // public mixed $timezone;
    // public mixed $lang;
    // public mixed $language;
    /**
     * @var \App\Models\HomeModel
     */
    public $homeModel;
    /**
     * @var \App\Models\MypatientsModel
     */
    public $myPatientsModel;
    public mixed $cart;
    /**
     * @var \App\Models\ApiModel
     */
    public $apiModel;
    public mixed $payPal;
    protected mixed $paypal;
    /**
     * @var \App\Models\ReviewModel
     */
    public $reviewModel;
    /**
     * @var \App\Models\UserModel
     */
    public $userModel;

    public function __construct()
    {
        helper(['form', 'url', 'text', 'string', 'common', 'security', 'libsodium']);

        $this->data['theme'] = 'user';
        $this->data['module'] = 'home';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->timezone = session('time_zone');
        if (!empty($this->timezone)) {
            date_default_timezone_set($this->timezone);
        }

        $this->data['uri'] = service('uri');
        $lang = session('locale') ?? config('App')->defaultLocale;
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

        //Define Model
        $this->homeModel = new HomeModel();
        $this->myPatientsModel = new MypatientsModel();
        $this->apiModel = new ApiModel();
        $this->reviewModel = new ReviewModel();
        $this->payPal = new PayPalLibrary();
        $this->userModel = new UserModel();
        $paypalMode = !empty(settings("paypal_option")) ? settings("paypal_option") : "";
        if ($paypalMode === 'live') {
            $clientId = settings("live_client_id");
            $clientSecret = settings("live_secret_key");
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $clientId = settings("sandbox_client_id");
            $clientSecret = settings("sandbox_secret_key");
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }
        $this->paypal = new PayPalHttpClient($environment);
    }

    /**
     *  Home page.
     * 
     * 
     * @return mixed
     */
    public function index()
    {
        $this->data['page'] = 'index';
        $this->data['doctors'] = $this->homeModel->getDoctors();
        $this->data['specialization'] = $this->homeModel->getSpecialization();
        $this->data['blogs'] = $this->homeModel->getBlogs();
        return view('user/home/index', $this->data);
    }
    /**
     * Map
     * 
     * @param mixed  $id
     * @return mixed
     */
    public function maps($id)
    {
        $id = isset($id) ? base64_decode($id) : '';
        $user_id = session('user_id');
        if ($id > 0) {
            $doctor_details = user_detail($id);
            $this->data['to_address'] = libsodiumDecrypt($doctor_details['address1']) . "," . $doctor_details['cityname'] . ', ' . $doctor_details['statename'] . ', ' . $doctor_details['postal_code'] . ' ' . $doctor_details['countryname'];
        }
        $patient_details = user_detail(session('user_id'));
        $this->data['from_address'] = libsodiumDecrypt($patient_details['address1']) . "," . $patient_details['cityname'] . ', ' . $patient_details['statename'] . ', ' . $patient_details['postal_code'] . ' ' . $patient_details['countryname'];
        $this->data['module'] = 'patient';
        $this->data['page'] = 'maps';
        return view('user/home/maps', $this->data);
    }
    /**
     * Review
     * 
     * @return mixed
     */
    public function reviewPage()
    {
        $user_id = session('user_id');
        $this->data['page'] = 'review';
        $this->data['reviews'] = $this->homeModel->reviewListView($user_id);
        return view('user/review/review', $this->data);
    }

    /**
     * Search Doctor List Design Page 
     * 
     * 
     * @return mixed
     */
    public function searchDoctor()
    {
        $this->data['page'] = 'searchDoctor';
        $this->data['keywords'] = '';
        $this->data['city'] = '';
        $this->data['state'] = '';
        if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
            $this->data['keywords'] = urldecode($_GET['keywords']);
        }
        if (isset($_GET['location']) && !empty($_GET['location'])) {
            $citys = $_GET['location'];
            $citys = str_replace(',', '', $citys);
            $explode = (explode(' ', $citys));
            if ($explode[0] != "") {
                $location = $this->homeModel->getTblRowOfData('city', ['city' => $explode[0]], 'id,stateid');
                $this->data['city'] = $location ? $location['id'] : '';
            }
        }
        $this->data['specialization'] = $this->homeModel->getTblResultOfData('specialization', ['status' => 1], '*');
        $this->data['role'] = !empty($_GET['type']) ? $_GET['type'] : 1;
        $this->data['login_role'] = !empty(session('role')) ? session('role') : 0;
        return view('user/home/doctorSearchList', $this->data);
    }

    /**
     * Search Doctor MapGrid List Design Page
     * 
     * 
     * @return mixed 
     */
    public function searchDoctorOnMap()
    {
        $search_data = $this->request->getPost();
        $this->data['page'] = 'doctors_mapsearch';
        $this->data['keywords'] = '';
        $this->data['city'] = '';
        $this->data['state'] = '';
        $this->data['search_data'] = isset($search_data) && !empty($search_data) ? $search_data : "";
        //$this->data['services'] = $this->profile->get_service_types_and_services();
        //$this->data['sub_services'] = $this->profile->get_all_sub_services();
        /* if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
          $this->data['keywords'] = urldecode($_GET['keywords']);
          }
          if (isset($_GET['location']) && !empty($_GET['location'])) {
          $location = $this->db->select('id,stateid')->where('city', urldecode($_GET['location']))->get('city')->row_array();
          $this->data['city'] = $location['id'];
          } */
        $this->data['role'] = !empty($_GET['type']) ? $_GET['type'] : 1;
        $this->data['login_role'] = !empty(session('role')) ? session('role') : 0;
        $this->data['specialization'] = $this->homeModel->getTblResultOfData('specialization', ['status' => 1], '*');
        return view('user/home/doctorSearchMapGridList', $this->data);
    }

    // AJAX DOCTOR LIST

    public function getScheduleFromDate($schedule_date, $doctor_id)
    {
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
        
        $schedule =  $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $doctor_id, 'day_id' => $day_id], '*');
        $slots = '<div class="slots-doctor-day">';
        $slots .= '<div class="slots-grid';
        $slots .= '-'.$doctor_id.' slots-grid bookings-schedule" style="width:100%">';
        if (!empty($schedule)) {
            $i = 1;
            $token = 1;
            foreach ($schedule as $rows) {
                $time_zone = $rows['time_zone'];
                $current_timezone = session('time_zone');
                $current_time = strtotime(date('Y-m-d H:i:s'));
                $converted_end_time = converToTz($schedule_date . ' ' . $rows['end_time'], $current_timezone, $time_zone);
                $endtime = strtotime($converted_end_time);

                if ($current_time <= $endtime) {
                    $start = strtotime(converToTz($rows['start_time'], $current_timezone, $time_zone));
                    $end = strtotime(converToTz($rows['end_time'], $current_timezone, $time_zone));
                    $datas = array();

                    if ($rows['slot'] >= 5) {
                        for ($j = $start; $j <= $end; $j = $j + $rows['slot'] * 60) {
                            $datas[] = date('H:i:s', $j);
                        }
                    } else {
                        for ($j = $start; $j <= $end; $j = $j + 60 * 60) {
                            $datas[] = date('H:i:s', $j);
                        }
                    }

                    for ($k = 0; $k <  $rows['token']; $k++) {
                        $start_time = converToTz($schedule_date . ' ' . $datas[$k], $current_timezone, $time_zone);

                        if (date('Y-m-d H:i:s') < $schedule_date . ' ' . $datas[$k]) {
                            $booked_session = get_booked_session($i, $token, $start_time, $rows['user_id']);
                            $time_display = date('h:i A', strtotime($datas[$k]));

                            if ($booked_session >= 1) {
                                // Slot booked, disable it visually
                                $slots.= '<div class="slot-booked" title="Booked">' . $time_display . '</div>';
                            } else {
                                if (!empty($datas[$k + 1])) {
                                    // if there is a next slot, use next start time
                                    $end_time_value = date('H:i:s', strtotime(converToTz($datas[$k + 1], $time_zone, $current_timezone)));
                                } else {
                                    // if this is the last slot, use schedule's original end_time
                                    $end_time_value = date('H:i:s', strtotime(converToTz($schedule_date . ' ' . $rows['end_time'], $time_zone, $current_timezone)));
                                }
                                $slots.= '
                                    <div class="slot" data-schedule-type="' . $rows['type'] . '" 
                                    data-date="' . date('Y-m-d', strtotime(str_replace('/', '-', $schedule_date))) . '" 
                                    data-timezone="' . $rows['time_zone'] . '" 
                                    data-start-time="' . date('H:i:s', strtotime(converToTz($datas[$k], $time_zone, $current_timezone))) . '" 
                                    data-end-time="' . $end_time_value . '" 
                                    data-session="' . $i . '" 
                                    data-token="' . $token . '">' . $time_display . '</div>';
                            }
                        }
                        $token++;
                    }
                }
                $i++;
            }
        } 
        $slots.= '<div class="see-more';
        $slots.= '-'.$doctor_id.' see-more" style="display:none">See more ></div>';
        
        $slots.= '</div>';

        $slots .= '</div>';
        
        return $slots;
    }

    /**
     * Search Doctor List.
     * 
     * 
     * @return mixed 
     */
    public function searchDoctorList()
    { 
        $response = $result = $lat = $long = $lat_long = array();

        $page = $this->request->getPost('page');
        $patient_lat = $this->request->getPost('s_lat');
        $patient_long = $this->request->getPost('s_long');
        $radius = $this->request->getPost('s_radius');
        $unit = $this->request->getPost('s_unit');
        $search_location = $this->request->getPost('s_location');
        $limit = ($unit != '' && $radius != '' && $search_location != '' && $patient_lat != '' && $patient_long != '') ? 100 : 5;

        $response['count'] = $this->homeModel->searchDoctor($page, $limit, 1);
        $response['limit'] = $limit;
        $doctor_list = $this->homeModel->searchDoctor($page, $limit, 2);

        if (!empty($doctor_list)) {
            foreach ($doctor_list as $rows) {
                $data['id'] = $rows['id'];
                $data['doctor_id'] = $rows['user_id'];
                $doctorName = encryptor_decryptor('encrypt', libsodiumDecrypt($rows['username']));
                $data['username'] = $doctorName;

                $hospitalInfo = user_hospital($rows['hospital_id']);
                // $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';


                if (!empty($rows['profileimage']) && file_exists($rows['profileimage'])) {
                    $data['profileimage'] = $rows['profileimage'];
                } else {
                    $data['profileimage'] = base_url() . 'assets/img/user.png';
                }

                $data['first_name'] = libsodiumDecrypt($rows['first_name']);
                $data['last_name'] = libsodiumDecrypt($rows['last_name']);
                //$data['clinicname'] = ucfirst($rows['clinicname']);

                $slotsToday = $this->getScheduleFromDate(date('Y-m-d'), $rows['user_id']);
                $data['slotsToday'] = $slotsToday;

                if (!empty($rows['specialization_img']) && file_exists($rows['specialization_img'])) {
                    $data['specialization_img'] = $rows['specialization_img'];
                } else {
                    $data['specialization_img'] = base_url() . 'assets/img/user.png';
                }


                $data['speciality'] = ($rows['speciality']) ? ucfirst(libsodiumDecrypt($rows['speciality'])) : '-';
                $data['cityname'] = ($rows['cityname']) ? $rows['cityname'] : '';
                $data['countryname'] = ($rows['countryname']) ? $rows['countryname'] : '';
                $data['services'] = $rows['services'];
                $data['rating_value'] = $rows['rating_value'];
                $data['rating_count'] = $rows['rating_count'];
                //$data['roleid'] = $rows['role'];

                // if ($rows['role'] == 1 || !$hospitalInfo) {
                //     $data['clinicname'] = '';
                // } else {
                //     $data['clinicname'] = ($rows['clinicname']) ? $rows['clinicname'] : $data['first_name'] . ' ' . $data['last_name'];
                // }

                if (!$hospitalInfo) {
                    $data['clinicname'] = ($rows['clinicname']) ? $rows['clinicname'] : $data['first_name'] . ' ' . $data['last_name'];;
                } else {
                    $data['clinicname'] = (libsodiumDecrypt($hospitalInfo['first_name']).' '.libsodiumDecrypt($hospitalInfo['last_name']));
                }
                //$data['clinicname'] = '';//($rows['clinicname']) ? $rows['clinicname'] : (libsodiumDecrypt($hospitalInfo['first_name']).' '.libsodiumDecrypt($hospitalInfo['last_name']));//$data['first_name'] . ' ' . $data['last_name'];
                
                $data['latitude'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
                $data['lat'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
                $data['latitude'] = $this->latitude($rows['cityname'] . ' ' . $rows['countryname']);
                $data['longitude'] = $this->longitude($rows['cityname'] . ' ' . $rows['countryname']);
                $images = $this->homeModel->getTblResultOfData('clinic_images', ['user_id' => $rows['user_id']], '*');
                $clinic_images = [];
                if ($images) {
                    foreach ($images as $value) {
                        if (!empty($value['clinic_image']) && file_exists($value['clinic_image'])) {
                            $clinic_images[] = array(
                                'user_id' => $value['user_id'],
                                'clinic_image' => base_url() . $value['clinic_image']
                            );
                        }
                    }
                }
                $data['clinic_images'] = json_encode($clinic_images);

                if ($rows['price_type'] == 'Custom Price') {
                    $user_currency = get_user_currency();
                    $user_currency_code = $user_currency['user_currency_code'];
                    $user_currency_rate = $user_currency['user_currency_rate'];
                    $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $rows['currency_code'];
                    $rate_symbol = currency_code_sign($currency_option);
                   // if (!empty(session('user_id'))) {
                     //  $rate = get_doccure_currency($rows['amount'], $rows['currency_code'], $user_currency_code);
                   // } else {
                        $rate = $rows['amount'];
                    //}
                    $data['amount'] = $rate_symbol . '' . $rate;
                } else {

                    $data['amount'] = "Free";
                }
                
                $count = 0; // vijay added 20-07-2021
                if ($unit != '' && $radius != '' && $search_location != '' && $patient_lat != '' && $patient_long != '') {
                    //$data['amount'] = ($rows['price_type'] == 'Custom Price') ? '$' . $rows['amount'] . ' per slot' : 'Free';
                    $data['distance'] = $this->getDistanceBetweenPointsNew($patient_lat, $patient_long, $data['latitude'], $data['longitude'], $unit);
                    if ($radius != '' && $radius > $data['distance']) {
                        $lat[] = $data['latitude'];
                        $long[] = $data['longitude'];
                        $result[] = $data;
                        $count = $count + 1;
                    }
                } else {
                    $lat[] = $data['latitude'];
                    $long[] = $data['longitude'];
                    $result[] = $data;
                }
            }
        }
        $response['current_page_no'] = $page;
        $response['total_page'] = ceil($response['count'] / $limit);
        $response['data'] = $result;
        echo json_encode($response);
    }
    /**
     * Get Latitude from Address.
     * 
     * @param mixed $address
     * @return mixed 
     */
    public function latitude($address)
    {
        $address = str_replace(" ", "+", $address);

        $url = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyA_QD2_rlwEFGhCK0oj2n6cixsvX0D3zgk&address=$address&sensor=false";

        $ch = curl_init(); //initiating curl
        curl_setopt($ch, CURLOPT_URL, $url); // CALLING THE URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);

        $response = json_decode($response);
        if (@$response->status == 'OK') {
            $lat = $response->results[0]->geometry->location->lat;
            $lng = $response->results[0]->geometry->location->lng;

            return $lat;
        }
    }
    /**
     * Get Longitude from Address.
     * 
     * @param mixed $address
     * @return mixed 
     */
    public function longitude($address)
    {
        $address = str_replace(" ", "+", $address);

        $url = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyA_QD2_rlwEFGhCK0oj2n6cixsvX0D3zgk&address=$address&sensor=false";

        $ch = curl_init(); //initiating curl
        curl_setopt($ch, CURLOPT_URL, $url); // CALLING THE URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);

        $response = json_decode($response);
        if (@$response->status == 'OK') {
            $lat = $response->results[0]->geometry->location->lat;
            $lng = $response->results[0]->geometry->location->lng;
            return $lng;
        }
    }
    /**
     * Get Distance Between Points New.
     * 
     * @param mixed $latitude1
     * @param mixed $longitude1
     * @param mixed $latitude2
     * @param mixed $longitude2
     * @param mixed $unit
     * @return mixed 
     */
    public function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Mi')
    {
        $theta = $longitude1 - $longitude2;
        $distance = sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta));

        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;

        switch ($unit) {
            case 'Mi':
                break;
            case 'Km':
                $distance = $distance * 1.609344;
        }

        return (round($distance, 2));
    }
    /**
     * Search Doctor By Keywords
     * 
     * 
     * @return mixed 
     */
    public function searchKeywords()
    {
        $data = array();
        $response = array();
        $sdata = array();
        $sresult = array();
        $result = array();
        $search_keywords = $this->request->getPost('search_keywords');

        if (!empty($search_keywords)) {
            $doctor_list = $this->homeModel->autoCompleteSearchDoctor(libsodiumEncrypt($search_keywords));
            $specialization_list = $this->homeModel->autoCompleteSearchSpecialization(libsodiumEncrypt($search_keywords));

            foreach ($specialization_list as $srows) {
                $sdata['specialization'] = $srows['specialization'];
                $sresult[] = $sdata;
            }


            if (!empty($doctor_list)) {
                foreach ($doctor_list as $rows) {
                    $data['username'] = libsodiumDecrypt($rows['username']);
                    $data['profileimage'] = (!empty($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
                    $data['first_name'] = ucfirst(libsodiumDecrypt($rows['first_name']));
                    $data['last_name'] = ucfirst(libsodiumDecrypt($rows['last_name']));
                    $data['speciality'] = ucfirst(libsodiumDecrypt($rows['speciality']));
                    $result[] = $data;
                }
            }
        }
        $response['specialist'] = $sresult;
        $response['doctor'] = $result;
        echo json_encode($response);
    }
    /**
     * doctor preview page
     * 
     * @param string $username
     * @return mixed  
     */
    public function doctorPreview($username)
    {
        //$doctorName = libsodiumDecrypt($username);
        $doctorName = encryptor_decryptor('decrypt', $username);
        $this->data['page'] = 'doctor_preview';
        $this->data['doctors'] = $this->homeModel->getDoctorDetails(libsodiumEncrypt($doctorName));
        $this->data['login_role'] = !empty(session('role')) ? session('role') : 0;
        
        if (!empty($this->data['doctors'])) {

            $timezones = [
                'UTC' => 'UTC',
                'Africa/Cairo' => 'Africa/Cairo',
                'Africa/Nairobi' => 'Africa/Nairobi',
                'America/New_York' => 'America/New_York',
                'America/Chicago' => 'America/Chicago',
                'America/Denver' => 'America/Denver',
                'America/Los_Angeles' => 'America/Los_Angeles',
                'America/Toronto' => 'America/Toronto',
                'Asia/Dubai' => 'Asia/Dubai',
                'Asia/Karachi' => 'Asia/Karachi',
                'Asia/Kolkata' => 'Asia/Kolkata',
                'Asia/Dhaka' => 'Asia/Dhaka',
                'Asia/Jakarta' => 'Asia/Jakarta',
                'Asia/Bangkok' => 'Asia/Bangkok',
                'Asia/Shanghai' => 'Asia/Shanghai',
                'Asia/Tokyo' => 'Asia/Tokyo',
                'Asia/Seoul' => 'Asia/Seoul',
                'Asia/Singapore' => 'Asia/Singapore',
                'Australia/Sydney' => 'Australia/Sydney',
                'Australia/Melbourne' => 'Australia/Melbourne',
                'Europe/London' => 'Europe/London',
                'Europe/Paris' => 'Europe/Paris',
                'Europe/Berlin' => 'Europe/Berlin',
                'Europe/Moscow' => 'Europe/Moscow',
                'Pacific/Auckland' => 'Pacific/Auckland',
                'Pacific/Honolulu' => 'Pacific/Honolulu',
                'Atlantic/Bermuda' => 'Atlantic/Bermuda',
            ];

            $this->data['timezones'] = $timezones;

            $doctor_details = $this->homeModel->getDoctorDetails(libsodiumEncrypt($doctorName));
            $user_id = $this->data['doctors']['userid'];
            session()->set('doctor_id', $user_id);
            if ($doctor_details['price_type'] == 'Custom Price') {
                $user_currency = get_user_currency();
                $user_currency_code = $user_currency['user_currency_code'];
                $user_currency_rate = $user_currency['user_currency_rate'];

                $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $doctor_details['currency_code'];
                $rate_symbol = currency_code_sign($currency_option);

                if (!empty(session('user_id'))) {
                    $rate = get_doccure_currency($doctor_details['amount'], $doctor_details['currency_code'], $user_currency_code);
                } else {
                    $rate = $doctor_details['amount'];
                }
                $this->data['amount'] = $rate_symbol . number_format($rate, 2, '.', '');
            } elseif ($doctor_details['role'] != 4) {
                $this->data['amount'] = "Free";
            }

            $hospitalInfo = user_hospital($doctor_details['hospital_id']);
            
            $this->data['clinicname'] = ($doctor_details['clinicname']) ? $doctor_details['clinicname'] : (libsodiumDecrypt($hospitalInfo['first_name']).' '.libsodiumDecrypt($hospitalInfo['last_name']));//$data['first_name'] . ' ' . $data['last_name'];

            // $this->homeModel->getTblResultOfData('specialization', ['status' => 1], '*')
            $where = array('patient_id' => session('user_id'), 'doctor_id' => $user_id);
            $this->data['is_favourite'] = $this->homeModel->getTblResultOfData('favourities', $where, '*');
            $this->data['clinic_images'] = $this->homeModel->getTblResultOfData('clinic_images', ['user_id' => $user_id], '*');
            $this->data['education'] = $this->homeModel->getTblResultOfData('education_details', ['user_id' => $user_id], '*');
            $this->data['experience'] = $this->homeModel->getTblResultOfData('experience_details', ['user_id' => $user_id], '*');
            $this->data['awards'] = $this->homeModel->getTblResultOfData('awards_details', ['user_id' => $user_id], '*');
            $this->data['memberships'] = $this->homeModel->getTblResultOfData('memberships_details', ['user_id' => $user_id], '*');
            $this->data['registrations'] = $this->homeModel->getTblResultOfData('registrations_details', ['user_id' => $user_id], '*');
            $this->data['business_hours'] = $this->homeModel->getTblResultOfData('business_hours', ['user_id' => $user_id], '*');
            $this->data['sunday_hours'] = $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $user_id, 'day_id' => 1], '*');
            $this->data['monday_hours'] = $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $user_id, 'day_id' => 2], '*');
            $this->data['tue_hours'] = $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $user_id, 'day_id' => 3], '*');
            $this->data['wed_hours'] = $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $user_id, 'day_id' => 4], '*');
            $this->data['thu_hours'] = $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $user_id, 'day_id' => 5], '*');
            $this->data['fri_hours'] = $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $user_id, 'day_id' => 6], '*');
            $this->data['sat_hours'] = $this->homeModel->getTblResultOfData('schedule_timings', ['user_id' => $user_id, 'day_id' => 7], '*');
            $this->data['reviews'] = $this->homeModel->reviewListView($user_id);
            $this->data['social_media'] = $this->homeModel->getTblResultOfData('social_media', ['doctor_id' => $user_id], '*');
            if (!empty($this->data['social_media'])) {
                $this->data['facebook'] = $this->data['social_media'][0]['facebook'];
                $this->data['twitter'] = $this->data['social_media'][0]['twitter'];
                $this->data['instagram'] = $this->data['social_media'][0]['instagram'];
                $this->data['pinterest'] = $this->data['social_media'][0]['pinterest'];
                $this->data['linkedin'] = $this->data['social_media'][0]['linkedin'];
                $this->data['youtube'] = $this->data['social_media'][0]['youtube'];
            }

            // print_r($this->data);
            return view('user/home/doctorPreview', $this->data);
        } else {
            session()->setFlashdata('error_message', $this->language['lg_oops_something_']);
            redirect('search-doctor');
        }
    }

    /**
     * Add Favorites of Doctor
     * 
     * 
     * @return mixed 
     */
    public function addFavourities()
    {
        $response = array();
        if (!empty(session('user_id'))) {
            $doctor_id = $this->request->getPost('doctor_id');
            $patient_id = session('user_id');
            if (is_patient()) {
                $where = array('doctor_id' => $doctor_id, 'patient_id' => $patient_id);
                $already_favourities = $this->homeModel->getTblRowOfData('favourities', $where, "*");
                if ($already_favourities) {
                    $this->homeModel->deleteData('favourities', $where);
                    $response['msg'] = $this->language['lg_favourities_rem'] ?? "";
                    $response['status'] = 201;
                } else {
                    $data = array('doctor_id' => $doctor_id, 'patient_id' => $patient_id);
                    $this->homeModel->insertData('favourities', $data, false);

                    $response['msg'] = $this->language['lg_favourities_add'];
                    $response['status'] = 200;
                }
            } else {
                $response['msg'] = $this->language['lg_invalid_login_p'] ?? "";
                $response['status'] = 204;
            }
        } else {
            $response['msg'] = $this->language['lg_please_login_to'] ?? "";
            $response['status'] = 204;
        }
        echo json_encode($response);
    }

    /**
     * search pharmacy
     * 
     * 
     * @return mixed 
     */
    public function search_pharmacy()
    {

        $response = array();
        $result = array();
        $page =  $this->request->getpost('page');
        $limit = 3;
        $response['count'] = $this->myPatientsModel->searchPharmacy($page, $limit, 1);
        $pharmacy_list = $this->myPatientsModel->searchPharmacy($page, $limit, 2);
        
        if (!empty($pharmacy_list)) {
            foreach ($pharmacy_list as $rows) {
                $data['link_id'] = encryptor_decryptor('encrypt', $rows['pharmacy_id']);
                //$data['id'] = $rows['pharmacy_id'];
                $data['pharmacy_name'] = (!empty($rows['pharmacy_name'])) ? ucfirst(libsodiumDecrypt($rows['pharmacy_name'])) : ucfirst(libsodiumDecrypt($rows['first_name'])) . ' ' . libsodiumDecrypt($rows['last_name']);
                $data['profileimage'] = (!empty($rows['profileimage']) && file_exists($rows['profileimage'])) ? base_url() . $rows['profileimage'] : base_url() . 'assets/img/user.png';
                $data['phonecode'] = $rows['phonecode'];
                $data['mobileno'] = libsodiumDecrypt($rows['mobileno']);
                $data['address1'] = libsodiumDecrypt($rows['address1']);
                $data['address2'] = libsodiumDecrypt($rows['address2']);
                $data['city'] = $rows['city'];
                $data['statename'] = $rows['statename'];
                $data['country'] = $rows['country'];
                $data['pharmacy_opens_at'] = date('g:iA', strtotime($rows['pharamcy_opens_at']));
                $result[] = $data;
            }
        }
        $response['current_page_no'] = $page;
        $response['total_page'] = ceil($response['count'] / $limit);
        $response['data'] = $result;
        echo json_encode($response);
    }

    /**
     * pharmacyPreview
     * 
     * @param mixed $pharmacy_id
     * @return mixed 
     */
    public function pharmacyPreview($pharmacy_id = '')
    {

        $pharmacy_id = encryptor_decryptor('decrypt', $pharmacy_id);
        // print_r($pharmacy_id);
        // exit;
        $this->data['pharmacy'] = $this->myPatientsModel->getSelectedPharmacyDetails($pharmacy_id);
        if ($this->data['pharmacy']) {
            $this->data['page'] = 'pharmacy_preview';
            return view('user/home/pharmacyPreview', $this->data);
        } else {
            return redirect(session('module'));
        }
    }
    /**
     * viewPharmacyProducts
     * 
     * @param mixed $pharmacy_id
     * @return mixed 
     */
    public function view_pharmacy_products($pharmacy_id = '')
    {
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_sign'];
        $data['pharmacy_id'] = $pharmacy_id;
        $phardecodeId = encryptor_decryptor('decrypt', $pharmacy_id);
        $this->myPatientsModel->set_userdata('pharmacy_id', $phardecodeId);
        $this->data['page'] = 'products_list_by_pharmacy';
        return view('user/home/productsListByPharmacy', $this->data);
    }
    /**
     * getProducts
     * 
     * 
     * @return mixed 
     */
    public function get_products()
    {
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_sign = $user_currency['user_currency_sign'];
        $response = array();
        $result = array();
        $page = $this->request->getpost('page');
        $pharmacy_id = $this->request->getpost('pharmacy_id');
        $limit = 3;
        $response['count'] = $this->myPatientsModel->get_products_by_pharmacy_filter($pharmacy_id, $page, $limit, 1);
        $product_list = $this->myPatientsModel->get_products_by_pharmacy_filter($pharmacy_id, $page, $limit, 2);
        // echo $this->db->last_query();die;
        if (!empty($product_list)) {

            $response['category_name'] = $product_list[0]['category_name'];
            foreach ($product_list as $rows) {
                $image_url = explode(',', $rows['upload_image_url']);

                $product_image = base_url() . 'assets/img/product.jpg';

                if (!empty($image_url[0]) && file_exists($image_url[0])) {
                    $product_image = base_url() . $image_url[0];
                }

                $data['id'] = $rows['id'];
                // $data['link_id'] = encryptor_decryptor('encrypt', libsodiumDecrypt($rows['id']));

                $data['user_currency_code'] = $user_currency_code;
                $data['user_currency_sign'] = $user_currency_sign;

                $data['productid'] = md5($rows['id']);
                $data['name'] = libsodiumDecrypt($rows['name']);
                $data['slug'] = $rows['slug'];
                //$data['product_image'] = base_url() . $image_url[0];
                $data['product_image'] = $product_image;
                $price = $rows['sale_price'];
                $sale_price = $rows['price'];
                $data['pharmacy_name'] = (!empty($rows['pharmacy_name'])) ? ucfirst(libsodiumDecrypt($rows['pharmacy_name'])) : ucfirst(libsodiumDecrypt($rows['first_name'])) . ' ' . libsodiumDecrypt($rows['last_name']);
                if (!empty(session('user_id'))) {
                    $priceNumeric = floatval($rows['price']);
                    $sale_priceNumeric = floatval($rows['sale_price']);
                    $sale_price = get_doccure_currency(round($priceNumeric), $rows['pharmacy_currency'], $user_currency_code);
                    $price = get_doccure_currency(round($sale_priceNumeric), $rows['pharmacy_currency'], $user_currency_code);
                }
                if (is_numeric($price)) {
                    $data['price'] = number_format($price, 2, '.', '');
                } else {
                    $data['price'] = '0.00'; // or some default value
                }

                if (is_numeric($sale_price)) {
                    $data['sale_price'] = number_format($sale_price, 2, '.', '');
                } else {
                    $data['sale_price'] = '0.00'; // or some default value
                }
                // $data['price'] = number_format($price, 2, '.', '');
                // $data['sale_price'] = number_format($sale_price, 2, '.', '');


                $data['unit'] = $rows['unit_value'] . $rows['unit_name'];
                $result[] = $data;
            }
        }

        $response['current_page_no'] = $page;
        $response['total_page'] = ceil($response['count'] / $limit);
        $response['data'] = $result;

        echo json_encode($response);
    }

    /**
     * GetSearchKeyProducts
     * 
     * 
     * 
     * @return mixed 
     */
    public function get_search_key_products()
    {
        $response = array();
        $products = [];
        $page = 1;
        $limit = 16;
        $pharmacy_id = $this->request->getPost('pharmacy_id');

        $product_list = $this->myPatientsModel->get_products_by_search($pharmacy_id, $page, $limit, 2);


        $products = array();

        if (!empty($product_list)) {
            foreach ($product_list as $product_li) {
                $products[] = trim($product_li['name']);
            }
        }
        $response['data'] = $products;
        echo json_encode($response);
    }
    /**
     * Cart Lists
     * 
     * 
     * 
     * @return mixed 
     */
    public function cart_lists()
    {
        $cart_data = \Config\Services::cart();
        $cart_list = $cart_data->contents();
        $cart_total_amount = $cart_data->total();
        $html = '';
        $checkout_html = '';
        $checkout_cart_html = '';
        $new_cart_total_amount = '';
        $datas['cart_count'] = 0;
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_sign = $user_currency['user_currency_sign'];

        if ($cart_data->totalItems() > 0) {

            foreach ($cart_list as $rows) {
                $image = (!empty($rows['image']) && file_exists($rows['image'])) ? base_url() . $rows['image'] : base_url() . 'assets/img/product.jpg';
                $sale_price = get_doccure_currency(round($rows['price']), $rows['pharmacy_currency'], $user_currency['user_currency_code']);
                $tot_sale_price = get_doccure_currency(round($rows['subtotal']), $rows['pharmacy_currency'], $user_currency['user_currency_code']);
                $new_cart_total_amount = get_doccure_currency(round($cart_total_amount), $rows['pharmacy_currency'], $user_currency['user_currency_code']);

                $html .= '<tr>
                  <td>
                    <h2 class="table-avatar">
                      <a href="javascript:void(0);" class="avatar avatar-sm mr-2"><img class="avatar-img rounded" src="' . $image . '" alt="User Image"></a>
                      
                    </h2>
                  </td>
                  <td><a href="javascript:void(0);">' . libsodiumDecrypt($rows['name']) . '</a></td>
                  <td>' . $user_currency_sign . '' . $sale_price . '</td>
                  
                  <td class="text-center">
                    <div class="quant-input">
                        <div class="cart-info quantity">
                              <div class="input-group mb-3">
                                <div class="input-group-prepend btn-increment-decrement" onClick="decrement_quantity(\'' . $rows['rowid'] . '\')">
                                  <span class="input-group-text">-</span>
                                </div>
                                <input type="text" class="form-control input-quantity" readonly id="input-quantity-' . $rows['rowid'] . '" value="' . $rows['qty'] . '">
                                <div class="input-group-append btn-increment-decrement" onClick="increment_quantity(\'' . $rows['rowid'] . '\')">
                                  <span class="input-group-text">+</span>
                                </div>
                              </div>
                         </div>
                    </div>
                  </td>
                  <td>' . $user_currency_sign . '' . number_format($tot_sale_price, 2, '.', '') . '</td>
                  <td class="text-right">
                    <div class="table-action">
                      <a onclick="remove_cart(\'' . $rows['rowid'] . '\')" href="javascript:void(0);" class="btn btn-sm bg-danger-light">
                        <i class="fas fa-times"></i>
                      </a>
                    </div>
                  </td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="5" style="text-align: center;">' . $this->language['lg_cart_empty'] . '</td></tr>';
        }

        if ($cart_data->totalItems() > 0) {
            $checkout_html = '<tr>
                      <td colspan="4" class="text-right">' . $this->language['lg_total_amount'] . '</td>
                      <td class="text-center"><b>' . $user_currency_sign . '' . number_format($new_cart_total_amount, 2, '.', '') . '</b></td>
                      <td class="text-right">
                        <div class="table-action">
                          <a href="' . base_url() . 'cart-checkout" class="btn btn-sm book-btn1">
                           <i class="fas fa-shopping-cart"></i> ' . $this->language['lg_checkout'] . '
                          </a>
                        
                        </div>
                      </td>
                    </tr>';

            $checkout_cart_html = '<tr>
                      <td colspan="4" class="text-right">' . $this->language['lg_total_amount'] . '</td>
                      <td class="text-center"><b>' . $user_currency_sign . '' . number_format($new_cart_total_amount, 2, '.', '') . '</b></td>
                      <td class="text-right">
                        
                      </td>
                    </tr>';

            $datas['cart_count'] = 1;
        }

        $datas['cart_list'] = $html;
        $datas['checkout_html'] = $checkout_html;
        $datas['checkout_cart_html'] = $checkout_cart_html;

        echo json_encode($datas);
    }

    /**
     * ProductsList
     * 
     * 
     * @return mixed 
     */
    public function products_list()
    {
        $this->data['page'] = 'products_list';
        return view('user/home/products_list', $this->data);
    }
    /**
     * Add Cart
     * 
     * 
     * @return mixed 
     */
    public function add_cart()
    {
        $cart_data = \Config\Services::cart();
        $product_id = $this->request->getpost('product_id');
        $cart_qty = !empty($this->request->getpost('cart_qty')) ? $this->request->getpost('cart_qty') : '1';
        $product_details = $this->myPatientsModel->get_product_details($product_id);
        $image_url = explode(',', $product_details['upload_image_url']);

        $result = $cart_data->insert(array(
            'id'    => $product_details['id'],
            'qty'    => $cart_qty,
            'pharmacy_currency'  =>  $product_details['pharmacy_currency'],
            'pharmacy_id'  =>  $product_details['pharmacy_id'],
            'price'    => $product_details['sale_price'],
            'name'    => $product_details['name'],
            'image' => $image_url[0],
        ));

        if (!empty($result)) {
            $datas['result'] = 'true';
            $datas['msg'] = $this->language['lg_cart_added_succ'];
            $datas['cart_count'] = $cart_data->totalItems();
        } else {
            $datas['result'] = 'false';
            $datas['msg'] = $this->language['lg_cart_added_fail'];
            $datas['cart_count'] = $cart_data->totalItems();
        }

        echo json_encode($datas);
    }

    /**
     * productSubCategory
     * 
     * 
     * 
     * @return mixed 
     */
    public function productSubCategory()
    {
        $subcategory = $this->request->getGet('subcategory');
        $category_data = $this->myPatientsModel->get_particular_categories($subcategory);
        $categoryId = $category_data[0]['category'];
        $this->data['pharmacy_id'] = session('pharmacy_id');
        $this->data['categoryId'] = $category_data[0]['category'];
        $this->data['subCategoryId'] = $category_data[0]['id'];
        $this->data['subcategory_list'] = $this->myPatientsModel->get_sub_categories($categoryId);
        $this->data['subcategory_name'] = $category_data[0]['subcategory_name'];

        $this->data['categories'] = $this->myPatientsModel->get_categories();

        $this->data['categorie_name'] = $this->data['categories'][0]['category_name'];

        $this->data['popular_products'] = $this->myPatientsModel->get_popular_products();
        $this->data['page'] = 'index';
        return view('user/ecommerce/index', $this->data);
    }
    /**
     * Checkout
     * 
     * 
     * @return mixed 
     */
    public function checkout()
    {
        $cart_data = \Config\Services::cart();
        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];
        $user_currency_rate = $user_currency['user_currency_rate'];
        $user_id = session('user_id');
        $user_details = $this->homeModel->getTblResultOfData('users_details', ['user_id' => $user_id], "*");
        $pharmacy_currency_code = $user_details[0]['currency_code'];
        $this->data['user_currency_sign'] = $user_currency['user_currency_sign'];
        $cart_list = $cart_data->contents();
        $cart_total_amountFin = $cart_data->total();
        $html = '';
        $checkout_html = '';
        $checkout_cart_html = '';
        $datas['cart_count'] = 0;
        $cart_total_amount = number_format($cart_total_amountFin, 2, '.', '');
        $cart_data->totalItems();

        $this->data['cart_list'] = $cart_list;
        $this->data['total_items'] = $cart_data->totalItems();
        $this->data['page'] = 'checkout';
        $user_id = session('user_id');
        $shipping_details = $this->homeModel->getTblRowOfData('shipping_details', ['user_id' => $user_id], "*");
        $this->data['shipping'] = $shipping_details;
        return view('user/ecommerce/checkout', $this->data);
    }
    /**
     * Cart Count.
     * 
     * 
     * @return mixed 
     */
    public function cart_count()
    {
        $cart_data = \Config\Services::cart();
        $datas['cart_count'] = $cart_data->totalItems();
        echo json_encode($datas);
    }
    /**
     * Remove Cart.
     * 
     * 
     * @return mixed 
     */
    public function remove_cart()
    {
        $cart_data = \Config\Services::cart();
        $id = $this->request->getpost('id');
        $remove = $cart_data->remove($id);
        echo $remove;
    }
    /**
     * Update Cart.
     * 
     * 
     * @return mixed 
     */
    public function update_cart()
    {
        $cart_data = \Config\Services::cart();
        $update = 10;
        // Get cart item info
        $rowid = $this->request->getpost('cart_id');
        $qty = $this->request->getpost('new_quantity');
        if (!empty($rowid) && !empty($qty)) {
            $update = $cart_data->update(array(
                'rowid' => $rowid,
                'qty'   => $qty
            ));
        }
    }
    /**
     * Cart Page.
     * 
     * 
     * @return mixed 
     */
    public function cart_page()
    {
        $this->data['page'] = 'cart';
        return view('user/home/cart_list', $this->data);
    }
    /**
     * Stripe Payment.
     * 
     * 
     * @return mixed 
     */
    public function stripePayment()
    {
        $stripe_secert_key = '';
        $stripe_option = !empty(settings("stripe_option")) ? settings("stripe_option") : "";
        if ($stripe_option == '1') {
            $stripe_secert_key = !empty(settings("sandbox_rest_key")) ? settings("sandbox_rest_key") : "";
        }
        if ($stripe_option == '2') {
            $stripe_secert_key = !empty(settings("live_rest_key")) ? settings("live_rest_key") : "";
        }

        $currency_code = $this->request->getpost('currency_code');

        $amount = get_doccure_currency($this->request->getpost('total_amount'), $currency_code, 'INR');

        $amount = number_format($amount, 2, '.', '');

        \Stripe\Stripe::setApiKey($stripe_secert_key);

        $intent = null;

        try {
            if (isset($_POST['payment_method_id'])) {
                # Create the PaymentIntent
                $intent = \Stripe\PaymentIntent::create([
                    'payment_method' => $_POST['payment_method_id'],
                    'amount' => ($amount * 100),
                    'currency' => 'INR',
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                ]);
            }

            if (isset($_POST['payment_intent_id'])) {
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
    /**
     * Generate Response.
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
            //$this->placeorder($intent);
            $this->stripePay($intent);
        } else {
            # Invalid status
            $results = array('status' => 500, 'message' => 'Transaction failure!.Please try again', 'error' => $intent);
            echo json_encode($results);
        }
    }
    /**
     * StripePay.
     * 
     * @param mixed $intent
     * @return mixed
     */
    public function stripePay($intent)
    {

        $cart_data = \Config\Services::cart();
        $item = '';
        $transaction_status = json_encode($intent);
        $txnid = time() . rand();
        $user_data = $_POST;

        $cartItems = $cart_data->contents();
        $PharmacyIds = array_unique(array_column($cartItems, 'pharmacy_id'));
        $cart_pharmacy_ids = implode(',', $PharmacyIds);

        $ordItemDetails['full_name']     = $user_data['ship_name'];
        $ordItemDetails['email']     = $user_data['ship_email'];

        $ordItemDetails['address1'] = $user_data['ship_address_1'];

        $ordItemDetails['address2']     = $user_data['ship_address_2'];
        $ordItemDetails['state']     = $user_data['state'];
        $ordItemDetails['postal_code']     = $user_data['postal_code'];
        $ordItemDetails['city']     = $user_data['city'];

        $ordItemDetails['country']     = $user_data['country'];
        $ordItemDetails['payment_method']     = '1';
        $ordItemDetails['phoneno']     = $user_data['ship_mobile'];
        $ordItemDetails['total_amount']     = $cart_data->total();

        $ordItemDetails['user_id']     = session('user_id');
        $ordItemDetails['pharmacy_id']     = session('pharmacy_id');
        $ordItemDetails['created_at']     = date('Y-m-d H:i:s');
        $ordItemDetails['currency']     = '$';
        $ordItemDetails['shipping']     = $user_data['shipping'];

        $ordItemDetails['status'] = 1;
        $this->userModel->insertData('order_user_details', $ordItemDetails);
        $lastInsertID = $this->userModel->insertID();
        // $insert = $this->apiModel->insertData('order_user_details', $ordItemDetails, false);
        $orderId = $lastInsertID;
        $oreder_id = 'OD' . time() . rand();

        $currency_code = $this->request->getpost('currency_code');
        $amount = $this->request->getpost('total_amount');

        $amount = number_format($amount, 2, '.', '');
        $tax = !empty(settings("tax")) ? settings("tax") : "0";

        $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
        if ($transcation_charge_amt > 0) {
            $transcation_charge = ($cart_data->total() * ($transcation_charge_amt / 100));
        } else {
            $transcation_charge = 0;
        }
        $totals_amount = $cart_data->total() + $transcation_charge;
        $tax_amount = (number_format($totals_amount, 2, '.', '') * $tax / 100);
        $invoice_no = $this->getInvoiceNo();

        $payments_data = array(
            'user_id' => session('user_id'),
            'doctor_id' => session('pharmacy_id'),
            'pharmacy_id' => $cart_pharmacy_ids,
            'invoice_no' => $invoice_no,
            'per_hour_charge' => $cart_data->total(),
            'total_amount' => $amount,
            'currency_code' => $currency_code,
            'txn_id' => $txnid,
            'order_id' => $oreder_id,
            'transaction_status' => $transaction_status,
            'payment_type' => 'Stripe',
            'tax' => !empty(settings("tax")) ? settings("tax") : "0",
            'tax_amount' => $tax_amount,
            'transcation_charge' => $transcation_charge,
            'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
            'payment_status' => 1,
            'payment_date' => date('Y-m-d H:i:s'),
        );
        $payment = $this->apiModel->insertData('payments', $payments_data, false);
        $payment_id = $payment['id'];
        $ordItemData = array();
        $i = 0;
        $pharmacy_id = 0;
        foreach ($cartItems as $item) {
            $ordItemData[$i]['user_id']     = session('user_id');
            $ordItemData[$i]['payment_id']     = $payment_id;
            $ordItemData[$i]['pharmacy_id']     = $item['pharmacy_id'];
            $ordItemData[$i]['order_id']     = $oreder_id;
            $ordItemData[$i]['product_id']     = $item['id'];
            $ordItemData[$i]['product_name']     = $item['name'];
            $ordItemData[$i]['quantity']     = $item['qty'];
            $ordItemData[$i]['price']     = $item["price"];
            $ordItemData[$i]['subtotal']     = $item["subtotal"];
            $ordItemData[$i]['transaction_status'] = $transaction_status;
            $ordItemData[$i]['payment_type']  = 'Stripe';
            $ordItemData[$i]['ordered_at']     = date('Y-m-d H:i:s');
            $ordItemData[$i]['user_order_id']     =   $orderId;
            $ordItemData[$i]['currency_code']     = $currency_code;
            $i++;
        }
        $paymentInsertId = $this->homeModel->updateData('payments', ['id' => $payment_id], ['doctor_id' => $item['pharmacy_id']]);
        $orderUserDetailsId = $this->homeModel->updateData('order_user_details', ['order_user_details_id' => $orderId], ['pharmacy_id' => $item['pharmacy_id']]);
        if (!empty($ordItemData)) {

            // Insert order items
            $insertOrderItems = $this->insertOrderItems($ordItemData);
            if ($insertOrderItems) {
                $payData = [];
                $order_details = $this->homeModel->getTblResultOfData('orders', ['order_id' => $oreder_id], "id,subtotal,pharmacy_id");
                foreach ($order_details as $prod_item) {

                    if ($transcation_charge_amt > 0) {
                        $pharm_transcation_charge = ($prod_item["subtotal"] * ($transcation_charge_amt / 100));
                    } else {
                        $pharm_transcation_charge = 0;
                    }
                    $pharm_totals_amount = $prod_item["subtotal"] + $pharm_transcation_charge;
                    $pharm_tax_amount = (number_format($pharm_totals_amount, 2, '.', '') * $tax / 100);
                    $pharm_tot_amt = number_format($pharm_tax_amount + $pharm_totals_amount, 2);

                    $payData['user_id']     = session('user_id');
                    $payData['orders_id']     = $prod_item['id'];
                    $payData['payment_id']     = $payment_id;
                    $payData['doctor_id']     = $prod_item['pharmacy_id'];
                    $payData['total_amount']     = $pharm_tot_amt;
                    $payData['currency_code']     = $currency_code;
                    $payData['order_id']     = $oreder_id;
                    $payData['tax'] = !empty(settings("tax")) ? settings("tax") : "0";
                    $payData['tax_amount'] = $pharm_tax_amount;
                    $payData['transcation_charge'] = $pharm_transcation_charge;
                    $payData['transaction_charge_percentage'] = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
                    $payData['payment_status'] = 1;
                    $payData['payment_date']     = date('Y-m-d H:i:s');
                    $this->homeModel->insertData('pharmacy_payments', $payData);
                }
                $cart_data->destroy();
                //  $this->add_shipping_details();
                session()->set('trans_id', $orderId);
                $results = array('status' => 200);

                foreach ($PharmacyIds as $pharm_id) {
                    $notification = array(
                        'user_id' => session('user_id'),
                        'to_user_id' => $pharm_id,
                        'type' => "Pharmacy",
                        'text' => "have ordered products to",
                        'created_at' => date("Y-m-d H:i:s"),
                        'time_zone' => session('time_zone')
                    );
                    $this->apiModel->insertData('notification', $notification, false);
                }
                echo json_encode($results);
            } else {
                $results = array('status' => 500);
                echo json_encode($results);
            }
        }
    }
    /**
     * Add Shipping Details.
     * 
     * 
     * @return mixed
     */
    public function add_shipping_details()
    {
        $inputdata = array();
        $inputdata['user_id'] = session('user_id');
        $inputdata['ship_name'] = $this->request->getpost('ship_name');
        $inputdata['ship_mobile'] = $this->request->getpost('ship_mobile');
        $inputdata['ship_email'] = $this->request->getpost('ship_email');
        $inputdata['ship_address_1'] = $this->request->getpost('ship_address_1');
        $inputdata['ship_address_2'] = $this->request->getpost('ship_address_2');
        $inputdata['ship_country'] = $this->request->getpost('country');
        $inputdata['ship_state'] = $this->request->getpost('state');
        $inputdata['ship_city'] = $this->request->getpost('city');
        $inputdata['postal_code'] = $this->request->getpost('postal_code');
        session('shipping_details', $inputdata);
        $already_exits = $this->apiModel->checkTblDataExist('shipping_details', ['user_id' => $inputdata['user_id']], 'id');
        if (count($already_exits) > 0) {
            $this->apiModel->updateData(
                'shipping_details',
                ['user_id', $inputdata['user_id']],
                $inputdata,
                false
            );
        } else {
            $this->apiModel->insertData('shipping_details', $inputdata, false);
        }
    }

    /**
     * Get Invoice No for Appointments
     * 
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
     * Payment Sucess.
     * 
     * @return mixed
     */
    public function payment_sucess()
    {
        $this->data['page'] = 'payment_sucess';
        return view('user/ecommerce/payment_sucess', $this->data);
    }
    /**
     * paypalPay.
     * 
     * 
     * @return mixed
     */
    public function paypalPay()
    {
        $cart_data = \Config\Services::cart();
        $paypal_email = '';
        $paypal_option = !empty(settings("paypal_option")) ? settings("paypal_option") : "";
        if ($paypal_option == '1') {
            $paypal_email = !empty(settings("sandbox_email")) ? settings("sandbox_email") : "";
        }
        if ($paypal_option == '2') {
            $paypal_email = !empty(settings("live_email")) ? settings("live_email") : "";
        }

        $amount = $this->request->getpost('total_amount');
        $name = $this->request->getpost('ship_name');
        $currency_code = $this->request->getpost('currency_code');
        $productinfo = "Orders";

        $amount = get_doccure_currency($amount, $currency_code, 'USD');
        $amount = number_format($amount, 2, '.', '');

        $ordItemDetails['full_name']     = $this->request->getpost('ship_name');
        $ordItemDetails['email']     = $this->request->getpost('ship_email');

        $ordItemDetails['address1'] = $this->request->getpost('ship_address_1');

        $ordItemDetails['address2']     = $this->request->getpost('ship_address_2');
        $ordItemDetails['state']     = $this->request->getpost('ship_state');
        $ordItemDetails['postal_code']     = $this->request->getpost('postal_code');
        $ordItemDetails['city']     = $this->request->getpost('ship_city');

        $ordItemDetails['country']     = $this->request->getpost('ship_country');
        $ordItemDetails['payment_method']     = '2';
        $ordItemDetails['phoneno']     = $this->request->getpost('ship_mobile');
        $ordItemDetails['total_amount']     = $amount;

        $ordItemDetails['user_id']     = session('user_id');
        $ordItemDetails['pharmacy_id']     = session('pharmacy_id');
        $ordItemDetails['created_at']     = date('Y-m-d H:i:s');
        $ordItemDetails['currency']     = '$';
        $ordItemDetails['shipping']     = $this->request->getpost('shipping');
        $ordItemDetails['status'] = 0;
        $user_order_id = $this->apiModel->insertData('order_user_details', $ordItemDetails, false);
        $oreder_id = 'OD' . time() . rand();
        $i = 0;

        $currency_code = $this->request->getpost('currency_code');
        $amount = (float) $amount;
        $amount = number_format($amount, 2, '.', '');
        $tax = !empty(settings("tax")) ? settings("tax") : "0";

        $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
        if ($transcation_charge_amt > 0) {
            $transcation_charge = ($cart_data->total() * ($transcation_charge_amt / 100));
        } else {
            $transcation_charge = 0;
        }
        $totals_amount = $cart_data->total() + $transcation_charge;
        $tax_amount = (number_format($totals_amount, 2, '.', '') * $tax / 100);
        $transaction_status = 'success';
        $txnid = time() . rand();
        $invoice_no = $this->getInvoiceNo();
        $payments_data = array(
            'user_id' => session('user_id'),
            'doctor_id' => session('pharmacy_id'),
            'invoice_no' => $invoice_no,
            'per_hour_charge' => $cart_data->total(),
            'total_amount' => $amount,
            'currency_code' => $currency_code,
            'txn_id' => $txnid,
            'order_id' => $oreder_id,
            'transaction_status' => $transaction_status,
            'payment_type' => 'Paypal',
            'tax' => !empty(settings("tax")) ? settings("tax") : "0",
            'tax_amount' => $tax_amount,
            'transcation_charge' => $transcation_charge,
            'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
            'payment_status' => 1,
            'payment_date' => date('Y-m-d H:i:s'),
        );
        $paymentsDetails = $this->apiModel->insertData('payments', $payments_data, false);
        $cartItems = $cart_data->contents();
        foreach ($cartItems as $item) {
            $ordItemData[$i]['user_id']     = session('user_id');
            $ordItemData[$i]['payment_id']     = $paymentsDetails['payment_id'];
            $ordItemData[$i]['pharmacy_id']     = $item['pharmacy_id'];
            $ordItemData[$i]['order_id']     = $oreder_id;
            $ordItemData[$i]['product_id']     = $item['id'];
            $ordItemData[$i]['product_name']     = $item['name'];
            $ordItemData[$i]['quantity']     = $item['qty'];
            $ordItemData[$i]['price']     = $item['price'];
            $ordItemData[$i]['subtotal']     = $item['subtotal'];
            $ordItemData[$i]['transaction_status'] = $transaction_status;
            $ordItemData[$i]['payment_type']  = 'Paypal';
            $ordItemData[$i]['ordered_at']     = date('Y-m-d H:i:s');
            $ordItemData[$i]['user_order_id']     = $user_order_id['id'];
            $i++;
        }
        $session_id = $this->apiModel->insertData('session_details', array('session_data' => json_encode(session('user_id'))), false);
    }

    /**
     * Paypal Success.
     * 
     * 
     * @return mixed
     */
    public function success()
    {
        if (isset($_POST["txn_id"]) && !empty($_POST["txn_id"])) {
            $paypalInfo =  $this->request->getpost();

            $txnid = $paypalInfo['txn_id'];
            $orderId = $paypalInfo['custom'];
            $sessID = $paypalInfo['item_number'];
            $amount = $paypalInfo['payment_gross'];
        } else {
            $paypalInfo =  $this->request->getpost();
            $txnid = $paypalInfo['txn_id'];
            $orderId = $paypalInfo['custom'];
            $sessID = $paypalInfo['item_number'];
            $amount = $paypalInfo['payment_gross'];
        }
        $transaction_status = json_encode($paypalInfo);
        $sessionDetails = $this->homeModel->getTblRowOfData('session_details', ['id' => $sessID], "*");
        $session        = (array) json_decode($sessionDetails['session_data']);
        session()->set_userdata($session);
        $status = 'success';

        if ($status == 'success') {

            $notification = array(
                'user_id' => session('user_id'),
                'to_user_id' => session('pharmacy_id'),
                'type' => "Pharmacy",
                'text' => "have ordered products to",
                'created_at' => date("Y-m-d H:i:s"),
                'time_zone' => session('time_zone')
            );
            $this->apiModel->insertData('notification', $notification, false);
            $this->cart->destroy();
            session()->setFlashdata('success_message', $this->language['lg_your_order_has_']);
            redirect(base_url() . 'pharmacy/orders_list');
        } else {

            $this->session->set_flashdata('error_message', $this->language['lg_transaction_fai']);
            return redirect(session('module'));
        }
    }
    /**
     * Paypal Failure.
     * 
     * 
     * @return mixed
     */
    public function failurePayment()
    {
        session()->setFlashdata('error_message', $this->language['lg_transaction_fai'] ?? "");
        return redirect(session('module'));
    }
    /**
     * Get Product Details.
     * 
     * @param mixed $slug
     * @return mixed
     */
    public function product_details($slug)
    {
        $user_currency = get_user_currency();
        $this->data['user_currency_code'] = $user_currency['user_currency_code'];
        $this->data['user_currency_sign'] = $user_currency['user_currency_sign'];
        $this->data['products'] = $this->homeModel->getProductDetails($slug);
        if (isset($this->data['products']['currency_code'])) {
            $pharmacy_currency_code = $this->data['products']['currency_code'];
            // $this->data['sale_price'] = get_doccure_currency(round($this->data['products']['price']), $pharmacy_currency_code, $user_currency['user_currency_code']);
            // Convert the value to a float before rounding
            $price = (float) $this->data['products']['price'];
            $this->data['sale_price'] = get_doccure_currency(round($price), $pharmacy_currency_code, $user_currency['user_currency_code']);
            $this->data['price'] = get_doccure_currency(round($this->data['products']['sale_price']), $pharmacy_currency_code, $user_currency['user_currency_code']);
            $this->data['page'] = 'product_details';
            return view('user/ecommerce/product_details', $this->data);
        } else {
            return redirect()->to('view_pharmacy_products');
        }
    }
    /**
     * Blog List.
     * 
     * 
     * @return mixed
     */
    public function blogList()
    {
        // print_r('test');exit;
        $this->data['page'] = 'blogList';
        return view('blog/blogList', $this->data);
    }
    /**
     * Blog Details.
     * 
     * @param string $title
     * @return mixed
     */
    public function blogDetails($title)
    {
        // echo $title;
        $this->data['page'] = 'blog_details';
        $this->data['posts'] = $this->homeModel->blogDetails(libsodiumEncrypt(urldecode($title)));
        if (!empty($this->data['posts'])) {
            return view('blog/blogDetails', $this->data);
        } else {
            session()->setFlashdata('error_message', 'Oops something went wrong try valid credentials!');
            return redirect()->to('blogs');
        }
    }
    /**
     * Get Blogs.
     * 
     * 
     * @return mixed
     */
    public function getBlogs()
    {
        $response = array();
        $result = array();
        $page = $this->request->getPost('page');
        $limit = 10;
        // $response['count'] = $this->homeModel->get_blogs($page, $limit, 1);
        // $blog_list = $this->homeModel->get_blogs($page, $limit, 2);
        $response['count'] = $this->homeModel->get_blogs();
        $blog_list = $this->homeModel->get_blogs();

        if (!empty($blog_list)) {
            foreach ($blog_list as $rows) {
                $image_url = explode(',', $rows['upload_image_url']);

                $avatar = base_url() . 'assets/img/user.png';
                $postimage = base_url() . 'assets/img/image-not-found.png';

                if (!empty($rows['profileimage']) && file_exists($rows['profileimage'])) {
                    $avatar = base_url() . $rows['profileimage'];
                }
                if (!empty($image_url[0]) && file_exists($image_url[0])) {
                    $postimage = base_url() . $image_url[0];
                }

                $docLink = encryptor_decryptor('encrypt', libsodiumDecrypt($rows['username']));
                $data['id'] = $rows['id'];
                $data['preview'] = ($rows['post_by'] == 'Admin') ? 'javascript:void(0);' : base_url() . 'doctor-preview/' . $docLink;
                $data['profileimage'] = $avatar;
                $data['name'] = ($rows['post_by'] == 'Admin') ? ucfirst($rows['name']) : 'Dr ' . ucfirst(libsodiumDecrypt($rows['name']));
                $data['post_image'] = $postimage;
                $data['title'] = libsodiumDecrypt($rows['title']);
                $data['slug'] = libsodiumDecrypt($rows['slug']);
                $data['description'] = character_limiter(libsodiumDecrypt($rows['description']), 70, '...');
                $data['created_date'] = date('d M Y', strtotime($rows['created_date']));
                $result[] = $data;
            }
        }
        $response['current_page_no'] = $page;
        // $response['total_page'] = ceil($response['count'] / $limit);
        $response['data'] = $result;

        echo json_encode($response);
    }

    /**
     * Add Comment to blog
     * 
     * 
     * @return mixed
     */
    public function addComments()
    {
        $inputdata = array();
        $response = array();
        if (session('admin_id') == '' && session('user_id') == '') {
            $response['msg'] = $this->language['lg_please_login_to1'];
            $response['status'] = 500;
        } else {

            $inputdata['post_id'] = $this->request->getPost('post_id');
            $inputdata['comments'] = $this->request->getPost('comments');
            $inputdata['role'] = session('role');
            if (!empty($inputdata['role'])) {
                $inputdata['user_id'] = session('user_id');
            } else {
                $inputdata['role'] = '3'; //Admin
                $inputdata['user_id'] = 0;
            }
            $inputdata['created_date'] = date('Y-m-d H:i:s');

            $result = $this->homeModel->insertData('comments', $inputdata);
            if ($result != false) {
                $response['msg'] = $this->language['lg_comment_added_s'];
                $response['status'] = 200;
            } else {
                $response['msg'] = $this->language['lg_comment_added_f'];
                $response['status'] = 500;
            }
        }
        echo json_encode($response);
    }
    /**
     * Blog User add replay
     * 
     * 
     * @return mixed
     */
    public function addReply()
    {
        $inputdata = array();
        $response = array();
        if (session('admin_id') == '' && session('user_id') == '') {
            $response['msg'] = $this->language['lg_please_login_to2'];
            $response['status'] = 500;
        } else {

            $inputdata['comment_id'] = $this->request->getPost('comment_id');
            $inputdata['replies'] = $this->request->getPost('reply');
            $inputdata['role'] = session('role');
            if (!empty($inputdata['role'])) {
                $inputdata['user_id'] = session('user_id');
            } else {
                $inputdata['role'] = '3'; //Admin
                $inputdata['user_id'] = 0;
            }
            $inputdata['created_date'] = date('Y-m-d H:i:s');

            $result = $this->homeModel->insertData('replies', $inputdata);
            if ($result != false) {
                $response['msg'] = $this->language['lg_reply_added_suc'];
                $response['status'] = 200;
            } else {
                $response['msg'] = $this->language['lg_reply_added_fai'];
                $response['status'] = 500;
            }
        }

        echo json_encode($response);
    }
    /**
     * Get Comments.
     * 
     * @return mixed
     */
    public function getComments()
    {
        $post_id = $this->request->getPost('post_id');
        $page = $this->request->getPost('page');
        $limit = 5;
        $response['count'] = $this->homeModel->getComments($post_id, $page, $limit, 1);
        $data['comments'] = $this->homeModel->getComments($post_id, $page, $limit, 2);
        $data['language'] = $this->language;
        $response['comments_list'] = view('blog/blogCommentList', $data);
        $response['comments_count'] = count($data['comments']);
        $response['current_page_no'] = $page;
        $response['total_page'] = ceil($response['count'] / $limit);
        echo json_encode($response);
    }
    /**
     * Get Replies.
     * 
     * 
     * @return mixed
     */
    public function getReplies()
    {
        $comment_id = $this->request->getPost('comment_id');
        $data['id'] = $comment_id;
        $data['language'] = $this->language;
        $response['replies_list'] = view('blog/blogReply', $data);
        echo json_encode($response);
    }
    /**
     * Delete Comment Reply.
     * 
     * 
     * @return mixed
     */
    public function deleteCommentReply()
    {
        $id = $this->request->getPost('id');
        $type = $this->request->getPost('type');

        if ($type === 1) {
            $this->homeModel->deleteData('comments', ['id' => $id]);
            $this->homeModel->deleteData('replies', ['comment_id' => $id]);
        }
        if ($type === 2) {
            $this->homeModel->deleteData('replies', ['id' => $id]);
        }
        echo true;
    }

    /**
     * Lab Book Appointment Payment with Paypal
     * 
     * 
     * @return mixed
     */
    public function pharmacyInitiatePayment()
    {
        $userdata = $this->request->getPost();

        $amount = $this->request->getPost('total_amount');

        $name = $this->request->getPost('ship_name');
        $currency_code = $this->request->getPost('currency_code');
        $productinfo = "Orders";
        $patient_id = session('user_id');

        $amount = get_doccure_currency($amount, $currency_code, 'USD');

        $amount = number_format($amount, 2, '.', '');

        // Create a new PayPal order request
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $amount // Specify the payment amount
                    ]
                ]
            ],
            'application_context' => [
                'return_url' => base_url('pharmacy-paypal-success'),
                'cancel_url' => base_url('paypal-failed')
            ]
        ];

        try {
            $id = $this->createEcommOrder();
            // Send the request to PayPal and get the response
            $response = $this->paypal->execute($request);
            $transDetail = array(
                "user_detail" => $userdata,
                "pharmacy_payment_id" => $id,
                "paypal_detail" => $response
            );
            $this->homeModel->insertData('session_details', ['session_data' => json_encode($transDetail)]);

            $approvalUrl = $response->result->links[1]->href;
            // echo "<pre>";print_r($response);die;
            // Redirect the user to PayPal for payment approval
            return redirect()->to($approvalUrl);
        } catch (\Exception $e) {
            // Handle any errors
            return $e->getMessage();
        }
    }
    /**
     * Create Ecomm Order.
     * 
     * 
     * @return mixed
     */
    public function createEcommOrder()
    {

        $orderBy = ['id' => 'desc'];
        $invoice = $this->homeModel->getTblRowOfData('payments', [], "id", $orderBy);
        if (empty($invoice)) {
            $invoice_id = 0;
        } else {
            $invoice_id = $invoice['id'];
        }
        $invoice_id = 'I' . sprintf("%05d", ++$invoice_id);
        $userdata = $this->request->getPost();
        $cart_data = \Config\Services::cart();
        $cartItems = $cart_data->contents();

        $PharmacyIds = array_unique(array_column($cartItems, 'pharmacy_id'));
        $cart_pharmacy_ids = implode(',', $PharmacyIds);

        $amount = $this->request->getPost('total_amount');

        $ordItemDetails['full_name']     = $this->request->getPost('ship_name');
        $ordItemDetails['email']     = $this->request->getPost('ship_email');

        $ordItemDetails['address1'] = $this->request->getPost('ship_address_1');

        $ordItemDetails['address2']     = $this->request->getPost('ship_address_2');
        $ordItemDetails['state']     = $this->request->getPost('ship_state');
        $ordItemDetails['postal_code']     = $this->request->getPost('postal_code');
        $ordItemDetails['city']     = $this->request->getPost('ship_city');

        $ordItemDetails['country']     = $this->request->getPost('ship_country');
        $ordItemDetails['payment_method']     = '2';
        $ordItemDetails['phoneno']     = $this->request->getPost('ship_mobile');
        $ordItemDetails['total_amount']     = $amount;
        $ordItemDetails['user_id']     = session('user_id');
        $ordItemDetails['pharmacy_id']     = session('pharmacy_id');
        $ordItemDetails['created_at']     = date('Y-m-d H:i:s');
        $ordItemDetails['currency']     = '$';
        $ordItemDetails['shipping']     = $this->request->getPost('shipping');
        $ordItemDetails['status'] = 0;
        $insert = $this->homeModel->insertData('order_user_details', $ordItemDetails);
        $user_order_id = $insert['id'];
        $order_id = 'OD' . time() . rand();
        $i = 0;
        $currency_code = $this->request->getPost('currency_code');
        $amount = number_format($amount, 2, '.', '');
        $tax = !empty(settings("tax")) ? settings("tax") : "0";
        $transcation_charge_amt = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
        if ($transcation_charge_amt > 0) {
            $transcation_charge = ($amount * ($transcation_charge_amt / 100));
        } else {
            $transcation_charge = 0;
        }
        $totals_amount = $amount + $transcation_charge;
        $tax_amount = (number_format($totals_amount, 2, '.', '') * $tax / 100);

        $invoice_no = $invoice_id;
        $transaction_status = 'success';
        $txnid = time() . rand();
        $payments_data = array(
            'user_id' => session('user_id'),
            'doctor_id' => session('pharmacy_id'),
            'pharmacy_id' => $cart_pharmacy_ids,
            'invoice_no' => $invoice_no,
            'per_hour_charge' => $amount,  //doubt
            'total_amount' => $amount,
            'currency_code' => $currency_code,
            'txn_id' => $txnid,
            'order_id' => $order_id,
            'transaction_status' => $transaction_status,
            'payment_type' => 'Paypal',
            'tax' => !empty(settings("tax")) ? settings("tax") : "0",
            'tax_amount' => $tax_amount,
            'transcation_charge' => $transcation_charge,
            'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
            'payment_status' => 1,
            'payment_date' => date('Y-m-d H:i:s'),
        );

        $insert = $this->homeModel->insertData('payments', $payments_data);
        $payment_id = $insert['id'];

        $ordItemData = [];

        foreach ($cartItems as $item) {
            $ordItemData[$i]['user_id']     = session('user_id');
            $ordItemData[$i]['payment_id']     = $payment_id;
            $ordItemData[$i]['pharmacy_id']     = $item['pharmacy_id'];
            $ordItemData[$i]['order_id']     = $order_id;
            $ordItemData[$i]['product_id']     = $item['id'];
            $ordItemData[$i]['product_name']     = $item['name'];
            $ordItemData[$i]['quantity']     = $item['qty'];
            $ordItemData[$i]['price']     = $item['price'];
            $ordItemData[$i]['subtotal']     = $item['subtotal'];
            $ordItemData[$i]['transaction_status'] = $transaction_status;
            $ordItemData[$i]['payment_type']  = 'Paypal';
            $ordItemData[$i]['ordered_at']     = date('Y-m-d H:i:s');
            $ordItemData[$i]['user_order_id']     = $user_order_id;
            $insert = $this->homeModel->insertData('orders', $ordItemData[$i]);
            $i++;
        }
        $payData = [];
        $order_details = $this->homeModel->getTblResultOfData('orders', ['order_id' => $order_id], "id,subtotal,pharmacy_id");
        foreach ($order_details as $prod_item) {
            if ($transcation_charge_amt > 0) {
                $pharm_transcation_charge = ($prod_item["subtotal"] * ($transcation_charge_amt / 100));
            } else {
                $pharm_transcation_charge = 0;
            }
            $pharm_totals_amount = $prod_item["subtotal"] + $pharm_transcation_charge;
            $pharm_tax_amount = (number_format($pharm_totals_amount, 2, '.', '') * $tax / 100);
            $pharm_tot_amt = number_format($pharm_tax_amount + $pharm_totals_amount, 2);

            $payData['user_id']     = session('user_id');
            $payData['orders_id']     = $prod_item['id'];
            $payData['payment_id']     = $payment_id;
            $payData['doctor_id']     = $prod_item['pharmacy_id'];
            $payData['total_amount']     = $pharm_tot_amt;
            $payData['currency_code']     = $currency_code;
            $payData['order_id']     = $order_id;
            $payData['tax'] = !empty(settings("tax")) ? settings("tax") : "0";
            $payData['tax_amount'] = $pharm_tax_amount;
            $payData['transcation_charge'] = $pharm_transcation_charge;
            $payData['transaction_charge_percentage'] = !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0";
            $payData['payment_status'] = 1;
            $payData['payment_date']     = date('Y-m-d H:i:s');
            $this->homeModel->insertData('pharmacy_payments', $payData);
        }
        return $payment_id;
    }

    /**
     * Pharmacy Success Payment.
     * 
     * 
     * @return mixed
     */
    public function pharmacySuccessPayment()
    {
        // Retrieve the payment details from the PayPal response
        $orderId = $_GET['token']; // Get the PayPal order ID from the callback URL
        // Create a request to retrieve the order details
        $request = new OrdersGetRequest($orderId);
        try {
            // Send the request to PayPal and get the response
            $execute_response = $this->paypal->execute($request);
            $transaction_status = json_encode($execute_response);
            $currency_code = $execute_response->result->purchase_units[0]->amount->currency_code;
            $amount = $execute_response->result->purchase_units[0]->amount->value;
            if ($execute_response->result->status == 'APPROVED' && $execute_response->result->payment_source->paypal->account_status == 'VERIFIED') {
                $transaction_status = json_encode($execute_response);
                $payment_id = session('pharmacy_payment_id');
                $this->homeModel->updateData('payments', ['id' => $payment_id], ['payment_status' => 2]);
                session()->remove('pharmacy_payment_id');
                session()->setFlashdata('success_message', "Your order booked successfully.");
                return redirect()->to(session('module'));
            } else {
                session()->setFlashdata('error_message', $this->language['lg_transaction_fai'] ?? "");
                return redirect(session('module'));
            }
        } catch (\Exception $ex) {
            // Handle any errors
            session()->setFlashdata('error_message', $this->language['lg_transaction_fai'] ?? "");
            return redirect(session('module'));
        }
    }

    /**
     * Notification Page.
     * 
     * 
     * 
     * @return mixed
     */
    public function notificationPage()
    {
        $this->data['page'] = 'notification';
        return view('user/patient/notification', $this->data);
    }
    /**
     * Search Notification.
     * 
     * 
     * @return mixed
     */
    public function searchNotification()
    {
        $response = array();
        $result = array();
        $page = $this->request->getPost('page');
        $limit = 5;
        $response['count'] = $this->reviewModel->getNotification($page, $limit, 1, session('user_id'));
        $notification_list = $this->reviewModel->getNotification($page, $limit, 2, session('user_id'));

        if (!empty($notification_list)) {
            foreach ($notification_list as $rows) {
                $decryptFirstName = libsodiumDecrypt($rows['first_name']);
                $decryptLastName = libsodiumDecrypt($rows['last_name']);
                $doctorName = $decryptFirstName . ' ' . $decryptLastName;
                $decryptPatientFirstName = libsodiumDecrypt($rows['patient_first_name']);
                $decryptPatientLastName = libsodiumDecrypt($rows['patient_last_name']);
                $patientName = $decryptPatientFirstName . ' ' . $decryptPatientLastName;
                $data['id'] = $rows['id'];
                if ($rows['user_id'] == 0) {
                    $data['from_name'] = 'Admin';
                } else {
                    $data['from_name'] = ucfirst((session('user_id') == $rows['user_id']) ? 'You' : $patientName);
                }
                if (session('user_id') == $rows['user_id'])
                    $data['profile_image'] = (!empty($rows['to_profile_image'])) ? base_url() . $rows['to_profile_image'] : base_url() . 'assets/img/user.png';
                else
                    $data['profile_image'] = (!empty($rows['profile_image'])) ? base_url() . $rows['profile_image'] : base_url() . 'assets/img/user.png';
                $data['to_name'] = ucfirst((session('user_id') == $rows['to_user_id']) ? 'You' : $doctorName);
                $data['text'] = ucfirst((session('user_id') == $rows['user_id']) ? str_replace('has', 'have', $rows['text']) : $rows['text']);
                $data['type'] = $rows['type'];
                $data['notification_date'] = time_elapsed_string($rows['notification_date']);

                $result[] = $data;
            }
        }
        $response['current_page_no'] = $page;
        $response['total_page'] = ceil($response['count'] / $limit);
        $response['data'] = $result;

        echo json_encode($response);
    }
    /**
     * Insert Order Items.
     * 
     * @param mixed $data
     * @return mixed
     */
    public function insertOrderItems($data = array())
    {
        $insert = "";
        for ($j = 0; $j < count($data); $j++) {
            $insert = $this->homeModel->insertData('orders', $data[$j]);
        }
        // Return the status

        return $insert ? true : false;
    }

    public function getTermsConditions()
    {
        $default_language = default_language();
        $user_lang = session('lang') ? session('lang') : $default_language['language_value'];
        $this->data['terms_conditions'] = $this->homeModel->getTblRowOfData('terms_conditions', ['language' => $user_lang], '*');
        $this->data['page'] = 'terms_and_conditions';
        return view('admin/terms_conditions/terms_and_conditions', $this->data);
    }

    public function getPrivacyPolicy()
    {
        $default_language = default_language();
        $user_lang = session('lang') ? session('lang') : $default_language['language_value'];
        $this->data['privacy_policy'] = $this->homeModel->getTblRowOfData('privacy_policy', ['language' => $user_lang], '*');
        $this->data['page'] = 'privacy_policy';
        return view('admin/privacy_policy/privacy_policy', $this->data);
    }
}
