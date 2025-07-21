<?php
namespace App\Cells;

class CardPets
{
    public function block($card)
    {
        return view('cells/card-pets', ['card' => $card]);
    }
}