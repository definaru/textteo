<?php

namespace App\Services;

use GuzzleHttp\Client;


class ApiMamoPay
{

    public static function Request($method = 'GET', $url = '', $params = [])
    {
        try {
            $client = new Client();
            $response = $client->request($method, $url, $params);
            $data = json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            $data = [
                'status' => 'error', 
                'error' => $e->getMessage()
            ];
        }
        return $data;
    }

    public function createPayment($serviceId, $amount, $env)
    {
        $params = [
            'json' => [
                'title' => 'Dr. Amira Eissa Rady',
                'description' => 'Veterinarian services',
                'active' => true,
                'return_url' => env('app.baseURL').'/payment/success/'.$env,
                'failure_return_url' => env('app.baseURL').'/payment/failure/'.$env,
                'processing_fee_percentage' => 3,
                'amount' => $amount,
                'amount_currency' => 'AED',
                'link_type' => 'inline',
                'enable_tabby' => false,
                'enable_message' => false,
                'enable_tips' => false,
                'save_card' => 'off',
                'enable_customer_details' => false,
                'enable_quantity' => false,
                'enable_qr_code' => false,
                'send_customer_receipt' => false,
                'hold_and_charge_later' => false,
                'capacity' => 1,
                'external_id' => $serviceId,
                'first_name' => 'Test',
                'last_name' => 'Tester',
                'email' => 'email@test.com'
            ],
            'headers' => [
                'Authorization' => env('mamopay.apiKey'),
                'Content-Type'  => 'application/json',
                'accept'        => 'application/json',
            ],
        ];
        
        return self::Request('POST', 'https://business.mamopay.com/manage_api/v1/links', $params);
    }

}