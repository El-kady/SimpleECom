<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'merchant' => $this->merchant,
            'title'       => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
            'i18n' => $this->i18n,
            'created_at'  => $this->created_at->format('d-m-Y')
        ];
    }
}
