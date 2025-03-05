<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpasswordreset extends Model
{
    use HasFactory;

    protected $table = "tblpasswordresets";
    protected $primarykey = "id";
    protected $fillable = ['email', 'token'];


    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Delete all previous records with the same email
            static::where('email', $model->email)->delete();
        });
    }
}
