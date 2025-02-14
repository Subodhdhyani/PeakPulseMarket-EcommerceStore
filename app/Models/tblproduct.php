<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblproduct extends Model
{
    use HasFactory;

    protected $table = 'tblproducts';

    public function carts() //because tblproduct relation used inside tblcart
    {
        return $this->hasMany(tblcart::class, 'tblproduct_id', 'id');
    }
}
