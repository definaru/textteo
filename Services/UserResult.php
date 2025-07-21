<?php

namespace App\Services;

class UserResult
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function getArray($data)
    {
        return new UserResult($data);
    }

    public function user()
    {
        helper('libsodium');
        $res = $this->data;
        $data = [
            'title' => $res["title"],
        ];
        return $data;
    }

}