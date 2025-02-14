<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcart extends Model
{
    use HasFactory;
    protected $table = 'tblcarts';
    //because tblcart have relation with tbluser and tblproduct
    public function user()
    {
        return $this->belongsTo(tbluser::class, 'tbluser_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'tblproduct_id', 'id');
    }
}
