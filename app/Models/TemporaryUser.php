<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class TemporaryUser
{
    use Notifiable;

    public $name;
    public $email;

    // This method is required for email notifications to work
    public function routeNotificationForMail()
    {
        return $this->email;
    }
}
