<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;

use App\Enums\Role;
use App\Enums\BuyerType;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'buyer_type'];
    protected $hidden = ['password', 'remember_token',];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'buyer_type' => BuyerType::class,
        ];
    }

    public function sendPasswordResetNotification($token) {
      $this->notify(new \App\Notifications\CustomResetPassword($token));
    }

    public function isBuyer() {
      return $this->role == Role::BUYER;
    }

    public function isSeller() {
      return $this->role == Role::SELLER;
    }

    public function isPrivateBuyer() {
      return $this->isBuyer() && $this->buyer_type == BuyerType::PRIVATE;
    }

    public function isCompanyBuyer() {
      return $this->isBuyer() && $this->buyer_type == BuyerType::COMPANY;
    }

    public function isTargetingPrivate() {
      return $this->isSeller() && $this->buyer_type == BuyerType::PRIVATE;
    }

    public function isTargetingCompany() {
      return $this->isSeller() && $this->buyer_type == BuyerType::COMPANY;
    }

    public function isTargetingBoth() {
      return $this->isSeller() && is_null($this->buyer_type);
    }

    public function products() {
      return $this->hasMany(\App\Models\Product::class, 'seller_id');
    }
}
