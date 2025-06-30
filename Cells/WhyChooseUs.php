<?php

namespace App\Cells;

class WhyChooseUs
{

    public function card()
    {
        return [
            [
                'image' => '/assets/images/Desktop/icons/bot.png',
                'alt' => 'AI Assistant',
                'title' => 'Your AI assistant',
                'text' => 'Ask Teo for quick help in finding the right product or service, from veterinary care to insurance. Fast and always available!'
            ],
            [
                'image' => '/assets/images/Desktop/icons/contract.png',
                'alt' => 'Ongoing Treatment',
                'title' => 'Ongoing treatment support',
                'text' => 'Receive personalized treatment plans and follow-up consultations to ensure your pet’s health is always monitored and well-managed.'
            ],
            [
                'image' => '/assets/images/Desktop/icons/trust.png',
                'alt' => 'Comfort and Trust',
                'title' => 'Total comfort and trust',
                'text' => 'With round-the-clock expert advice from TextTeo, you can trust that your pet’s health is always in safe and capable hands.'
            ]
        ];
    }

    public function section()
    {
        return view('cells/whyChooseUsSection', ['card' => self::card()]);
    }

}