<?php

namespace App\Cells;

class Faq
{

    public function card()
    {
        return [
            [
                'is_open' => ' open',
                'issue' => 'What steps do I need to follow to schedule an appointment?',
                'answer' => 'To book an online consultation with a veterinarian, select the "Book an appointment" section. Select a veterinarian, then a convenient date and time. If you are not registered, create an account. You will receive a confirmation email with a link to the consultation.'
            ],
            [
                'is_open' => '',
                'issue' => 'Is the consultation via video or chat?',
                'answer' => 'Your consultation can be via video or chat—whichever you prefer! You’ll be able to communicate directly with the vet.'
            ],
            [
                'is_open' => '',
                'issue' => 'How do I know if the vet is qualified to handle my pet’s issue?',
                'answer' => 'All vets on TextTeo are certified professionals who have been vetted to meet the highest standards. You can check their credentials and user reviews on their profile before booking.'
            ],
            [
                'is_open' => '',
                'issue' => 'How quickly can I get in touch with a vet in case of an emergency?',
                'answer' => 'We have 24/7 coverage. Once you book an emergency consultation, the system will connect you to the first available veterinarian as soon as possible.'
            ],
            [
                'is_open' => '',
                'issue' => 'Are there different pricing options or packages for consultations?',
                'answer' => 'Yes. We offer both one-time consultations and subscription-based plans with discounted rates. You can select the option that suits your needs.'
            ]
        ];
    }



    public function section()
    {
        return view('cells/faqSection', ['card' => self::card()]);
    }

}