<?php

namespace App\Traits;

trait ProductHelpers
{
    public function getPriceAfterVatAttribute(){
        $price = $this->attributes['price'];
        if (!$this->merchant->vat_included && $this->merchant->vat_percentage > 0) {
            $price = $price + ($price * ($this->merchant->vat_percentage / 100) );
        }
        return $price;
    }
}
