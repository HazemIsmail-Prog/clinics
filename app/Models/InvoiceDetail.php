<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoice_details';
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class,'treatment_id','id');
    }


}
