<?php

namespace App\Controllers\Payment;

use App\Services\ApiMamoPay;
use App\Services\ResultPay;
use App\Controllers\BaseController;


class PaymentController extends BaseController
{

    public function index()
    {
        helper('libsodium');
        $res = $this->request->getGet('data');
        $data = libsodiumDecrypt($res);
        return view('payment/index', ['data' => $data]);
    }
    
    public function success($paymentCode)
    {
        helper('libsodium');

        //$res = libsodiumEncrypt(json_encode($paymentCode));
        $data = libsodiumDecrypt($paymentCode);
        return view('payment/success', ['paymentCode' => $paymentCode, 'data' => $data]);
    }

    public function failure($paymentCode)
    {
        return view('payment/failure', ['paymentCode' => $paymentCode]);
    }

    public function pay()
    {
        $amount = $this->request->getPost('amount');
        $envData = [
            'amount' => $amount,
            'external_id' => 'FGJKGGU'.time()*100,
        ];
        $env = libsodiumEncrypt(json_encode($envData));

        $payment = new ApiMamoPay();
        $mamopay = $payment->createPayment($envData['external_id'], $amount, $env);
        $paymentLink = ResultPay::getArray($mamopay)->all();
        //$mamopay;
        if ($paymentLink) {
            return redirect()->to('/payment/card/' . libsodiumEncrypt(json_encode($paymentLink)));
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to create a payment link',
            ]);
        }

        // return $this->response->setJSON([
        //     'status' => 'success',
        //     'message' => 'Payment has been created',
        //     'payment_link' => $paymentLink,
        //     'env' => $env
        // ]);
    }


    public function card($paymentCode)
    {
        $data = libsodiumDecrypt($paymentCode);
        return view('payment/card', ['paymentCode' => $data]);
    }


    public function callback()
    {
        return $this->response->setJSON([
            'status' => 'ok',
        ]);
    }
}