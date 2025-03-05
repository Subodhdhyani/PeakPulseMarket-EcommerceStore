<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblreview extends Model
{
    use HasFactory;
    protected $table = "tblreviews";
    protected $primarykey = "id";
    protected $fillable = ['tbluser_id', 'tblproduct_id', 'booking_id', 'rating', 'review', 'status'];

    public function user()
    {
        return $this->belongsTo(tbluser::class, 'tbluser_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'tblproduct_id', 'id');
    }
}
