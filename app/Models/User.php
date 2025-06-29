<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;

use App\Enums\Role;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email','password','role'];
    protected $hidden = ['password','remember_token',];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function sendPasswordResetNotification($token) {
      $this->notify(new \App\Notifications\CustomResetPassword($token));
    }
}
