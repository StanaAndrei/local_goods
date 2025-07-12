<!-- Stored in resources/views/balance/withdraw.blade.php -->

@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Header -->
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: bold; color: #333;">Withdraw Funds</h1>
        <p style="color: #666;">Transfer funds from your account balance to your connected Stripe account.</p>
    </div>

    <!-- Main Content Box -->
    <div style="background: white; padding: 1.5rem 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        
        <!-- Session Messages -->
        @if (session('success'))
            <div style="padding: 1rem; margin-bottom: 1.5rem; background-color: #e6f4ea; color: #34a853; border-radius: 4px; border: 1px solid #cce8d4;">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div style="padding: 1rem; margin-bottom: 1.5rem; background-color: #fce8e6; color: #ea4335; border-radius: 4px; border: 1px solid #f9d6d2;">
                {{ session('error') }}
            </div>
        @endif

        <div style="margin-bottom: 1.5rem; font-size: 1.125rem;">
            <span style="color: #666;">Your current balance is:</span>
            <strong style="color: #333;">${{ number_format(Auth::user()->balance, 2) }}</strong>
        </div>

        @if (!Auth::user()->stripe_connect_id)
            <!-- Stripe Connect Call to Action -->
            <div style="padding: 1rem; margin-bottom: 1.5rem; background-color: #e8f0fe; color: #4285f4; border-radius: 4px; border: 1px solid #d2e3fc;">
                <h3 style="font-weight: bold; margin-bottom: 0.5rem;">Action Required</h3>
                <p>To withdraw funds, you first need to connect your Stripe account. This allows us to securely transfer funds to you. The process is fast and secure.</p>
            </div>
            <div style="display: flex; justify-content: center;">
                <a href="{{ route('balance.withdraw.connect') }}" style="display: inline-block; background-color: #4285f4; color: white; padding: 0.75rem 2rem; border-radius: 4px; text-decoration: none; font-weight: 500; transition: background-color 0.3s;">
                    Connect with Stripe
                </a>
            </div>
        @else
            <!-- Withdrawal Form -->
            <form action="{{ route('balance.withdraw.process') }}" method="POST">
                @csrf
                <div>
                    <label for="amount" style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.5rem;">Amount to Withdraw (USD)</label>
                    <input 
                        type="number" 
                        name="amount" 
                        id="amount" 
                        style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"
                        step="0.01" 
                        min="1.00" 
                        max="{{ Auth::user()->balance }}"
                        placeholder="e.g., 25.00"
                        required
                    >
                    @error('amount')
                        <p style="font-size: 0.875rem; color: #ea4335; margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                    <button 
                        type="submit" 
                        style="background-color: #003d7e; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; transition: background-color 0.3s;"
                    >
                        Request Withdrawal
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection