<?php
namespace App\Services;


class Profile
{

    public static function avatar($user)
    {
        $default = base_url('/assets/img/user.png');
        $img = $user['profileimage'] ?? '';

        if (empty($img)) {
            return $default;
        }
        return base_url($img);
    }

}