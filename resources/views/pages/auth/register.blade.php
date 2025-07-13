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

<div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f9f9f9;">
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 500px; padding: 2rem; margin: 1rem;">
        <h1 style="color: #ff6d00; text-align: center; margin-bottom: 1.5rem;">Register</h1>
        
        <form method="POST" action="{{ url('/register') }}">
            @csrf
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Name:</label>
                <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;">
                @error('name') <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email:</label>
                <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;">
                @error('email') <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Address:</label>
                <input type="text" name="address" value="{{ old('address') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;">
                @error('address') <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password:</label>
                <input type="password" name="password" required style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;">
                @error('password') <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Confirm Password:</label>
                <input type="password" name="password_confirmation" required style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;">
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Role:</label>
                <select name="role" wire:model.live="role" required style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;">
                    <option value="{{ Role::SELLER->value }}">Seller</option>
                    <option value="{{ Role::BUYER->value }}">Buyer</option>
                </select>
                @error('role') <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">{{ $role == Role::SELLER->value ? 'Target buyer type:' : 'Buyer type:' }}</label>
                <select name="buyer_type" wire:model.live="buyer_type" {{ $role == Role::BUYER->value ? 'required' : '' }} style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;">
                    @if($role == Role::SELLER->value)
                        <option value="">None</option>
                    @endif
                    <option value="{{ BuyerType::PRIVATE->value }}">Private</option>
                    <option value="{{ BuyerType::COMPANY->value }}">Company</option>
                </select>
                @error('buyer_type') <div style="color: #f44336; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
            </div>
            
            <button type="submit" style="background-color: #ff6d00; color: white; border: none; padding: 0.75rem; font-size: 1rem; border-radius: 4px; cursor: pointer; width: 100%; font-weight: 500; margin-top: 0.5rem;">Register</button>
        </form>
        
        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="{{ route('login') }}" style="color: #003d7e; text-decoration: none;">Already have an account? Login</a>
        </div>
    </div>
</div>