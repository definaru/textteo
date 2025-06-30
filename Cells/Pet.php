<?php

namespace App\Cells;

class Pet
{

    public function card()
    {
        return [
            [
                'image' => '/assets/images/Icon svg/pain icons/skin.svg',
                'icon' => 'skin',
                'title' => 'Skin Issues',
                'href' => '/search-veterinary'
            ],
            [
                'image' => '/assets/images/Icon svg/pain icons/dental.svg',
                'icon' => 'dental',
                'title' => 'Dental Issues',
                'href' => '/search-veterinary'
            ],
            [
                'image' => '/assets/images/Icon svg/pain icons/bone-fracture (1) 1.svg',
                'icon' => 'trauma',
                'title' => 'Trauma',
                'href' => '/search-veterinary'
            ],
            [
                'image' => '/assets/images/Icon svg/pain icons/beetle 1.svg',
                'icon' => 'parasites',
                'title' => 'Parasites',
                'href' => '/search-veterinary'
            ],
            [
                'image' => '/assets/images/Icon svg/pain icons/infection 1.svg',
                'icon' => 'infection',
                'title' => 'Ear Infections',
                'href' => '/search-veterinary'
            ],
            [
                'image' => '/assets/images/Icon svg/pain icons/bone-fracture (1) 1.svg',
                'icon' => 'trauma',
                'title' => 'Vaccinations',
                'href' => '/search-veterinary'
            ],
            [
                'image' => '/assets/images/Icon svg/pain icons/stomach 2.svg',
                'icon' => 'stomach',
                'title' => 'Stomach Issues',
                'href' => '/search-veterinary'
            ]
        ];
    }

    public function section()
    {
        return view('cells/petSection', ['card' => self::card()]);
    }

}