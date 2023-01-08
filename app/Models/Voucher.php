<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';
    protected $guarded = [];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class,'clinic_id','id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function updator()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function voucher_details()
    {
        return $this->hasMany(VoucherDetail::class,'voucher_id','id');
    }

    public function scopeLoggedAccountGroup($query)
    {
        return $query->where('account_group_id',auth()->user()->clinic->account_group_id);
    }


}
