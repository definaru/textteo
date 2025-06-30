<?php

namespace App\Services;


class ResultPay
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function getArray($data)
    {
        return new ResultPay($data);
    }

    public function all()
    {
        $responce = $this->data;
        $data = [
            'title' => $responce["title"],
            'description' => $responce['description'],
            'external_id' => $responce['external_id'],
            'amount' => $responce['amount'],
            'email' => $responce['prefilled_customer']['email'],
            'first_name' => $responce['prefilled_customer']['first_name'],
            'last_name' => $responce['prefilled_customer']['last_name'],
            'payment_url' => $responce['payment_url'],
        ];
        return $data;
    }

}