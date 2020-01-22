<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlanceBank extends Model
{
    
    // use SoftDeletes;
    protected $table = 'blance_bank';
    public function balanceBankHistory()
    {
        return $this->hasOne('App\Models\BalanceBankHistory', 'balance_bank_id');
    }
   
}
