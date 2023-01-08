<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    public $table = 'accounts';

    protected $guarded =[];

    public function parentAccounts()
    {
        return $this->belongsTo(Account::class,'account_id');
    }

    public function childAccounts()
    {
        return $this->hasMany(Account::class,'account_id');
    }

    public function voucher_details()
    {
        return $this->hasMany(VoucherDetail::class,'account_id');
    }

    public function scopeLoggedAccountGroup($query)
    {
        return $query->where('account_group_id',auth()->user()->clinic->account_group_id);
    }
}
