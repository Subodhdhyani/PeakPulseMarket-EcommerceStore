<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblfront_content extends Model
{
    use HasFactory;
    protected $table = 'tblfront_contents';
    protected $primarykey = 'id';
    protected $fillable = ['firstpic', 'secondpic', 'thirdpic', 'email', 'number'];
}
