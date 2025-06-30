<?php

namespace App\Cells;

class Review
{

    public function card()
    {
        return [
            [
                'title' => 'Your trust, our pride',
                'subtitle' => 'The process was easy, and I got clear care instructions. Highly recommend!',
                'text' => 'Great service! My dog had an allergic reaction, and I got a vet consultation within minutes. The vet provided quick advice and a treatment plan. Will definitely use TextTeo again!',
                'person' => 'William Daniels',
                'pet' => 'Ray',
                'image' => '/assets/images/Desktop/your trust/Dog img.png',
                'instagram' => 'william',
            ],
            [
                'title' => 'Your trust, our pride',
                'subtitle' => 'The process was easy, and I got clear care instructions.',
                'text' => 'Great service! My dog had an allergic reaction, and I got a vet consultation within minutes. The vet provided quick advice and a treatment plan. Will definitely use TextTeo again!',
                'person' => 'William Daniels',
                'pet' => 'Ray',
                'image' => '/assets/images/Desktop/your trust/Dog img.png',
                'instagram' => 'william',
            ],
            [
                'title' => 'Your trust, our pride',
                'subtitle' => 'Such a smooth experience from start to finish!',
                'text' => 'The vet provided immediate guidance and solutions. TextTeo is a lifesaver—no more waiting rooms. Highly recommend for every pet parent!',
                'person' => 'Alex Johnson',
                'pet' => 'Bella',
                'image' => '/assets/images/Desktop/your trust/Dog img.png',
                'instagram' => 'alex',
            ],
        ];
    }

    public function section()
    {
        return view('cells/reviewSection', ['card' => self::card()]);
    }
}