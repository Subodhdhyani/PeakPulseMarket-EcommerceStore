<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbltracking extends Model
{
    use HasFactory;
    protected $table = "tbltrackings";
    protected $primarykey = "id";
    protected $fillable = ['booking_id', 'courier_name', 'courier_tracking_number', 'order_status_0_to_1', 'order_status_1_to_2', 'order_status_2_to_3', 'order_status_3_to_6', 'order_status_6_to_4', 'order_status_0_to_4', 'order_status_1_to_4', 'order_status_4_to_5'];
}
