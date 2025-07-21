<?php

namespace App\Cells;

class Services
{
    public function card()
    {
        return [
            [
                'image' => '/assets/images/Desktop/2 block/Certified.png',
                'alt' => 'Certified Veterinarian',
                'title' => 'Qualified Veterinarians',
                'text' => 'Expert care from trusted professionals with top reviews',
            ],
            [
                'image' => '/assets/images/Desktop/2 block/Call.png',
                'alt' => 'Instant Help',
                'title' => 'Instant Help',
                'text' => '24/7 support via chat or video, no waiting or travel required',
            ],
            [
                'image' => '/assets/images/Desktop/2 block/Chat.png',
                'alt' => 'Treatment History',
                'title' => 'Treatment History at Hand',
                'text' => 'Convenient access to medical history and treatment details.',
            ]
        ];
    }

    public function section()
    {
        return view('cells/servicesSection', ['card' => self::card()]);
    }
}