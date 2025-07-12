<?php

namespace App\Models;

use App\Enums\BuyerType;
use App\Enums\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Billable;

    protected $fillable = ['name', 'email', 'password', 'role', 'buyer_type'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
            'buyer_type' => BuyerType::class,
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }

    public function isBuyer()
    {
        return $this->role == Role::BUYER;
    }

    public function isSeller()
    {
        return $this->role == Role::SELLER;
    }

    public function isPrivateBuyer()
    {
        return $this->isBuyer() && $this->buyer_type == BuyerType::PRIVATE;
    }

    public function isCompanyBuyer()
    {
        return $this->isBuyer() && $this->buyer_type == BuyerType::COMPANY;
    }

    public function isTargetingPrivate()
    {
        return $this->isSeller() && $this->buyer_type == BuyerType::PRIVATE;
    }

    public function isTargetingCompany()
    {
        return $this->isSeller() && $this->buyer_type == BuyerType::COMPANY;
    }

    public function isTargetingBoth()
    {
        return $this->isSeller() && is_null($this->buyer_type);
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'seller_id');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }

    public function getRoleNameAttribute()
    {
        return Role::tryFrom($this->role)?->name();
    }

    public function acquisitionsAsBuyer()
    {
        return $this->hasMany(Acquisition::class, 'buyer_id');
    }

    public function acquisitionsAsSeller()
    {
        return $this->hasManyThrough(
            Acquisition::class,
            Product::class,
            'seller_id',      // Foreign key on products table...
            'product_id',     // Foreign key on acquisitions table...
            'id',             // Local key on users table...
            'id'              // Local key on products table...
        );
    }

    public function hasEnoughBalance($amount)
    {
        return $this->balance >= $amount;
    }

    public function addBalance($amount)
    {
        $this->increment('balance', $amount);
    }

    public function deductBalance($amount)
    {
        if ($this->hasEnoughBalance($amount)) {
            $this->decrement('balance', $amount);
            return true;
        }
        return false;
    }
}
