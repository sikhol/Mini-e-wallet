<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BalanceBankHistory extends Model
{
    // use SoftDeletes;
    protected $table = 'blance_bank_history';
 
    public function balanceBank()
    {
        return $this->belongsTo('App\Models\BlanceBank', 'balance_bank_id');
    }
}
