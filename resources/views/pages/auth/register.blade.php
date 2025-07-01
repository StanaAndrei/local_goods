<!-- resources/views/auth/register.blade.php -->
<?php
use function Livewire\Volt\{state, layout};
use App\Enums\Role;
use App\Enums\BuyerType;

state([
    'role' => old('role', Role::SELLER->value),
    'buyer_type' => old('buyer_type', '')
]);

layout('layouts.app');
?>
<div>
    <h1>Register</h1>
    <form method="POST" action="{{ url('/register') }}">
        @csrf
        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <div>{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div>{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
            @error('password') <div>{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <div>
            <label>Role:</label>
            <select name="role" wire:model.live="role" required>
                <option value="{{ Role::SELLER->value }}">Seller</option>
                <option value="{{ Role::BUYER->value }}">Buyer</option>
            </select>
            @error('role') <div>{{ $message }}</div> @enderror
        </div>
        <div>
            <label>{{ $role == Role::SELLER->value ? 'Target buyer type:' : 'Buyer type:' }}</label>
            <select name="buyer_type" wire:model.live="buyer_type" {{ $role == Role::BUYER->value ? 'required' : '' }}>
                @if($role == Role::SELLER->value)
                    <option value="">None</option>
                @endif
                <option value="{{ BuyerType::PRIVATE->value }}">Private</option>
                <option value="{{ BuyerType::COMPANY->value }}">Company</option>
            </select>
            @error('buyer_type') <div>{{ $message }}</div> @enderror
        </div>
        <button type="submit">Register</button>
    </form>
    <a href="{{ route('login') }}">Already have an account? Login</a>
</div>