<!-- Stored in resources/views/balance/deposit.blade.php -->

@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 2rem 1rem;">
    <!-- Header -->
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; font-weight: bold; color: #333;">Deposit Funds</h1>
        <p style="color: #666;">Add funds to your account balance.</p>
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

        <form action="{{ route('balance.deposit.process') }}" method="POST">
            @csrf
            <div>
                <label for="amount" style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.5rem;">Amount to Deposit (USD)</label>
                <input 
                    type="number" 
                    name="amount" 
                    id="amount" 
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"
                    step="0.01" 
                    min="5.00" 
                    placeholder="e.g., 50.00"
                    required
                >
                @error('amount')
                    <p style="font-size: 0.875rem; color: #ea4335; margin-top: 0.5rem;">{{ $message }}</p>
                @enderror
                <p style="font-size: 0.75rem; color: #666; margin-top: 0.25rem;">Minimum deposit is $5.00.</p>
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button 
                    type="submit" 
                    style="background-color: #003d7e; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; transition: background-color 0.3s;"
                >
                    Proceed to Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection