<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserBalance extends Model
{
    // use SoftDeletes;
    protected $table = 'user_balance';

    public function historyBalance()
    {
        return $this->hasOne('App\Models\UserBalanceHistory', 'user_balance_id');
    }
 
}
