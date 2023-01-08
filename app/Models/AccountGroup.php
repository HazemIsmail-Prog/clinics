<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{

    public $table = 'account_groups';

    protected $guarded =[];

    public function clinics()
    {
        return $this->hasMany(Clinic::class,'account_group_id');
    }

}
