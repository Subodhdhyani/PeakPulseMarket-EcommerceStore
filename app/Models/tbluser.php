<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SignupWelcomeNotification;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordChangedMail;
use Illuminate\Support\Facades\Mail;



class tbluser extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait, Notifiable;

    protected $table = "tblusers";
    protected $primaryKey = "id";
    protected $fillable = ['name', 'role', 'email', 'phone_number', 'profile_pic', 'address', 'password', 'remember_token', 'status', 'last_login'];

    public function carts()   //because tbluser relation used inside tblcart
    {
        return $this->hasMany(tblcart::class, 'tbluser_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        /* // Listen for the 'created' event
        static::created(function ($user) {
            try {
                // Send the welcome email
                $user->notify(new SignupWelcomeNotification());

                // Log success
                Log::info('Signup welcome email sent successfully to: ' . $user->email);
            } catch (\Exception $e) {
                // Log any errors
                Log::error('Error sending signup email: ' . $e->getMessage());
            }
        });*/


        static::updated(function ($user) {
            // Check if the password was changed
            if ($user->wasChanged('password')) {
                // Send an email notification
                Mail::to($user->email)->send(new PasswordChangedMail($user));
                //$user->notify(new PasswordChangedMail()); This not work because this is mail class not notification
                //if que apply then and here not required as it send in background
                //Mail::to($user->email)->queue(new PasswordChangedMail($user)); // que name and queue class given inside that mail class
            }
        });
    }
}
