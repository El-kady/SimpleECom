<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product_I18NResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'lang'       => $this->lang,
            'title'       => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
            'created_at'  => $this->created_at->format('d-m-Y')
        ];
    }
}
