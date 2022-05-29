<?php


namespace App\Traits;


trait CartHelpers
{
    public function getPriceAfterVatAttribute($price, $vat_percentage = 0)
    {
        if ($vat_percentage > 0) {
            $price = $price + ($price * ($vat_percentage / 100));
        }
        return $price;
    }
}
