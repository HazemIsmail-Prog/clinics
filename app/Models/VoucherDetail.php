<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherDetail extends Model
{
    protected $table = 'voucher_details';
    protected $guarded = [];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class,'voucher_id','id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }
}
