<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Merchant extends Authenticatable
{
    use HasFactory;


    protected $fillable = [
        'store_name',
        'shipping_cost',
        'vat_included',
        'vat_percentage'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }


}
