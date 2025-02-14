<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblorderhelp extends Model
{
    use HasFactory;
    protected $table = "tblorderhelps";
    protected $primarykey = "id";
    protected $fillable = ['tbluser_id', 'booking_id', 'subject', 'description', 'order_help_status'];

    // Define the relationship with tblbooking (belongs-to)
    public function tblbooking()
    {
        return $this->belongsTo(tblbooking::class, 'booking_id', 'booking_id');
    }
}
