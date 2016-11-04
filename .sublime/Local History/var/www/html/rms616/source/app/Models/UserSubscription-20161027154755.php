<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSubscription
 * @package App\Models
 */
class UserSubscription extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'payment_id',
        'token',
        'payer_id',
        'payer_id',
        'payment_information',
        'user_id',
        'status',
        'expiration_time',
        'subscription_package_id',
        'payment_information',
    ];

    //Model users_subscriptions table
    /**
     * @var string
     */
    protected $table = 'users_subscriptions';

    //Parse expiration_time to Carbon date object
    /**
     * @var array
     */
    protected $dates = ['expiration_time'];
    /**
     *
     */
    const STATUS_ACTIVE = 1;
    /**
     *
     */
    const STATUS_INACTIVE = 0;
    /**
     * Relation with subscription_package table. Get Subscription package with `subscription_package_id` and `id`
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     */
    public function packages()
    {
        return $this->hasOne('App\Models\SubscriptionPackage', 'id', 'subscription_package_id');
    }
}
