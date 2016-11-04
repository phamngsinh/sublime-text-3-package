<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SubscriptionPackage extends Model
{
    protected $table = 'subscription_packages';
    protected $fillable = [
        'name',
        'price',
        'max_client',
        'is_active',
        'expiration',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public static function getAll($price = null)
    {
        $query = self::orderBy('price', 'asc');
        if($price){
            $query = $query->where('price','>',$price);
        }

        return $query->get();

    }

}
