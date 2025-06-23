<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use App\Models\HomeModel;
use OpenTok\OpenTok;
use OpenTok\MediaMode;

class PayPalController extends BaseController
{
    public mixed $timezone;
    protected mixed $paypal;
    public  mixed $language;
    /**
     * @var \App\Models\HomeModel
     */
    public $homeModel;
    public  mixed $data;
    public string $tokboxKey;
    public string $tokboxSecret;

    public function __construct()
    {
        $this->timezone = session('time_zone');
        if (!empty($this->timezone)) {
            date_default_timezone_set($this->timezone);
        }

        $this->homeModel = new HomeModel();
        $default_language = default_language();
        $lang = session('locale') ?? $default_language['language_value'];
        $this->data['language'] = lang('content_lang.language', [], $lang);
        $this->language = lang('content_lang.language', [], $lang);

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

        // OpenTok Key Used In Incoimng And Outgoing Call Tab
        $this->tokboxKey = !empty(settings("apiKey")) ? libsodiumDecrypt(settings("apiKey")) : "";
        $this->tokboxSecret = !empty(settings("apiSecret")) ? libsodiumDecrypt(settings("apiSecret")) : "";
    }

    /**
     * Get Invoice No for Appointments
     * 
     * @param mixed $table
     * @return mixed
     */
    private function getInvoiceNo($table)
    {
        $orderBy = ['id' => 'desc'];
        $invoice = $this->homeModel->getTblRowOfData($table, [], "id", $orderBy);
        if (empty($invoice)) {
            $invoice_id = 0;
        } else {
            $invoice_id = $invoice['id'];
        }
        $invoice_id = 'I' . sprintf("%05d", ++$invoice_id);
        return $invoice_id;
    }
    /**
     * Doctor Book Appt Success
     * 
     * 
     * @return mixed
     */
    public function successPayment()
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

            // Retrieve the payer details from the response
            $payerName = $execute_response->result->payer->name->given_name;
            $payerEmail = $execute_response->result->payer->email_address;

            if ($execute_response->result->status == 'APPROVED' && $execute_response->result->payment_source->paypal->account_status == 'VERIFIED') {
                $transaction_status = json_encode($execute_response);

                // H-3-4
                // OpenTok Removed Here

                /* Get Invoice id */
                $invoice_no = $this->getInvoiceNo('payments');

                $amount = number_format($amount, 2, '.', '');


                $payments_data = array(
                    'user_id' => session('user_id'),
                    'doctor_id' => session('doctor_id'),
                    'invoice_no' => $invoice_no,
                    'per_hour_charge' => session('hourly_rate'),
                    'total_amount' => $amount,
                    'currency_code' => session('currency_code'),
                    'txn_id' => $execute_response->result->id,
                    'order_id' => 'OD' . time() . rand(),
                    'transaction_status' => $transaction_status,
                    'payment_type' => 'Paypal',
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
                $appointmentdata['type'] = $this->language['lg_online'];
                $appointmentdata['payment_method'] = 'Paypal';

                $opentok = new OpenTok($this->tokboxKey, $this->tokboxSecret);
                // An automatically archived session:
                $sessionOptions = array(
                    // 'archiveMode' => ArchiveMode::ALWAYS,
                    'mediaMode' => MediaMode::ROUTED
                );
                $new_session = $opentok->createSession($sessionOptions);
                // Store this sessionId in the database for later use
                $tokboxsessionId = $new_session->getSessionId();
                $tokboxtoken = $opentok->generateToken($tokboxsessionId);

                $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
                $appointmentdata['tokboxtoken'] = $tokboxtoken;
                // $appointmentdata['tokboxsessionId'] = $tokboxsessionId;
                // $appointmentdata['tokboxtoken'] = $tokboxtoken;
                $appointmentdata['paid'] = 1;
                $appointmentdata['approved'] = 1;
                $appointmentdata['time_zone'] = $appointment_details[0]->appoinment_timezone;
                $appointmentdata['created_date'] = date('Y-m-d H:i:s');
                $insert = $this->homeModel->insertData('appointments', $appointmentdata);
                $appointment_id = $insert['id'];

                $notification = array(
                    'user_id' => session('user_id'),
                    'to_user_id' => session('doctor_id'),
                    'type' => "Appointment",
                    'text' => "has booked appointment to",
                    'created_at' => date("Y-m-d H:i:s"),
                    'time_zone' => $appointment_details[0]->appoinment_timezone
                );
                $this->homeModel->insertData('notification', $notification);

                // $this->send_appoinment_mail($appointment_id);
                // if(settings('tiwilio_option')=='1') {
                // $this->send_appoinment_sms($appointment_id);
                // }



                if (session('doctor_role_id') == 6) {
                    session()->setFlashdata('success_message', $this->language['lg_clinic_transaction_suc'] ?? "");
                    // if (isset($this->language['lg_clinic_transaction_suc'])) {
                    //     session()->setFlashdata('success_message', $this->language['lg_clinic_transaction_suc']);
                    // } else {
                    //     session()->setFlashdata('success_message', "");
                    // }
                } else {
                    session()->setFlashdata('success_message', $this->language['lg_transaction_suc'] ?? "");
                }
                session()->remove('appointment_details');
                return redirect()->to('payment-success/' . base64_encode($appointment_id));
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
     * Doctor Book Appt Success
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
     * Doctor Or Clinic Book Appointment Payment with Paypal
     * 
     * 
     * @return mixed
     */
    public function initiatePayment()
    {
        $userdata = $this->request->getPost();

        $amount = $userdata['amount'];
        $currency_code = $userdata['currency_code'];
        $productinfo = $userdata['productinfo'];
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
                        'value' => $userdata['amount'] // Specify the payment amount
                    ]
                ]
            ],
            'application_context' => [
                'return_url' => base_url('paypal-success'),
                'cancel_url' => base_url('paypal-failed')
            ]
        ];

        try {
            // Send the request to PayPal and get the response

            $response = $this->paypal->execute($request);
            $transDetail = array(
                "user_detail" => $userdata,
                "paypal_detail" => $response
            );
            $this->homeModel->insertData('session_details', ['session_data' => json_encode($transDetail)]);

            $approvalUrl = $response->result->links[1]->href;

            // Redirect the user to PayPal for payment approval
            return redirect()->to($approvalUrl);
        } catch (\Exception $e) {
            // Handle any errors
            return $e->getMessage();
        }
    }
    /**
     * Complete Payments.
     * 
     * 
     * @return mixed
     */
    public function completePayment()
    {
        // Retrieve the payment details from PayPal
        // Process the payment and handle the transaction
        // Update the order status and save relevant details

        // Example implementation
        $orderId = $this->request->getGet('order_id');
        $payerId = $this->request->getGet('payer_id');

        // Process the payment and complete the transaction

        return "Payment Completed. Order ID: $orderId, Payer ID: $payerId";
    }



    /**
     * Lab Book Appointment Payment with Paypal
     * 
     * 
     * @return mixed
     */
    public function labInitiatePayment()
    {
        $userdata = $this->request->getPost();

        $amount = $userdata['amount'];
        $currency_code = $userdata['currency_code'];
        $productinfo = $userdata['productinfo'];
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
                        'value' => $userdata['amount'] // Specify the payment amount
                    ]
                ]
            ],
            'application_context' => [
                'return_url' => base_url('lab-paypal-success'),
                'cancel_url' => base_url('paypal-failed')
            ]
        ];

        try {
            // Send the request to PayPal and get the response

            $response = $this->paypal->execute($request);
            $transDetail = array(
                "user_detail" => $userdata,
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
     * Lab Appointment Book with paypal
     * 
     * 
     * @return mixed
     */
    public function labSuccessPayment()
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

                $booking_details_session = session('lab_test_book_details');
                $lab_id = $booking_details_session['lab_id'];
                $booking_ids = $booking_details_session['booking_ids'];
                $lab_test_date = $booking_details_session['lab_test_date'];
                $tax_amount = $booking_details_session['tax_amount'];
                $transcation_charge = $booking_details_session['transcation_charge'];
                $currency_code = $booking_details_session['currency_code'];
                /** @var float $amount */
                $amount = $booking_details_session['total_amount'];
                $amount = number_format($amount, 2, '.', '');
                // H-3-4
                // OpenTok Removed Here

                /* Get Invoice id */
                $invoice_no = $this->getInvoiceNo('lab_payments');

                $amount = number_format(floatval($amount), 2, '.', '');
                $txn_id = $execute_response->result->id;
                $order_id = 'OD' . time() . rand();

                // Store the Payment details
                $payments_data = array(
                    'lab_id' => $lab_id,
                    'patient_id' => session('user_id'),
                    'booking_ids' => $booking_ids,
                    'invoice_no' => $invoice_no,
                    'lab_test_date' => $lab_test_date,
                    'total_amount' => $amount,
                    'currency_code' => $currency_code,
                    'txn_id' => $txn_id,
                    'order_id' => $order_id,
                    'transaction_status' => $transaction_status,
                    'payment_type' => 'PayPal',
                    'tax' => !empty(settings("tax")) ? settings("tax") : "0",
                    'tax_amount' => $tax_amount,
                    'transcation_charge' => $transcation_charge,
                    'payment_status' => 1,
                    'payment_date' => date('Y-m-d H:i:s'),
                );
                $insert = $this->homeModel->insertData('lab_payments', $payments_data);

                $payments_data = array(
                    'user_id' => session('user_id'),
                    'doctor_id' => $lab_id,
                    'invoice_no' => $invoice_no,
                    'per_hour_charge' => $booking_details_session['amount'],
                    'total_amount' => $amount,
                    'currency_code' => $currency_code,
                    'txn_id' => $txn_id,
                    'order_id' => $order_id,
                    'transaction_status' => $transaction_status,
                    'payment_type' => 'PayPal',
                    'tax' => !empty(settings("tax")) ? settings("tax") : "0",
                    'tax_amount' => $tax_amount,
                    'transcation_charge' => $transcation_charge,
                    'transaction_charge_percentage' => !empty(settings("transaction_charge")) ? settings("transaction_charge") : "0",
                    'payment_status' => 1,
                    'payment_date' => date('Y-m-d H:i:s'),
                );
                $insert = $this->homeModel->insertData('payments', $payments_data);


                $notification = array(
                    'user_id' => session('user_id'),
                    'to_user_id' => $lab_id,
                    'type' => "Appointment",
                    'text' => "has booked appointment to",
                    'created_at' => date("Y-m-d H:i:s"),
                    'time_zone' => $this->timezone
                );
                $this->homeModel->insertData('notification', $notification);

                session()->remove('lab_test_book_details');
                session()->setFlashdata('success_message', "Your lab test booked successfully.");
                return redirect()->to(session('module') . '/lab-appointments');
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
}
