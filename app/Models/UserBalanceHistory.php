<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserBalanceHistory extends Model
{
    // use SoftDeletes;
    protected $table = 'user_balance_history';
    public function userBalance()
    {
        return $this->belongsTo('App\Models\UserBalance', 'user_balance_id');
    }
    public function blanceBank()
    {
        return $this->belongsTo('App\Models\BlanceBank', 'balance_bank_id');
    }
}
