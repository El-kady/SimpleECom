<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'merchant_id' => $this->merchant_id,
            'title'       => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'created_at'  => $this->created_at->format('d-m-Y')
        ];
    }
}
