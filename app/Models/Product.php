<?php

namespace App\Models;

use App\Traits\ProductHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, ProductHelpers;

    protected $fillable = ['merchant_id', 'title', 'description', 'price', 'currency'];

    public function isMine()
    {
        return $this->merchant_id === auth()->user()->load('merchant')->merchant->id;
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function i18n()
    {
        return $this->hasMany(Product_I18N::class);
    }
}
