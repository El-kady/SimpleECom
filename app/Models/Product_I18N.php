<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_I18N extends Model
{
    use HasFactory;

    protected $table = 'product_i18ns';

    protected $fillable = ['product_id', 'lang', 'title', 'description', 'price', 'currency'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
