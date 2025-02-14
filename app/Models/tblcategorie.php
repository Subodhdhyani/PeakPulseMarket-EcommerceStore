<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcategorie extends Model
{
    use HasFactory;
    protected $table = "tblcategories";
    protected $primarykey = "id";
    protected $fillable = ['category_name', 'category_image'];
}
