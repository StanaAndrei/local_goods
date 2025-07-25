@extends('layouts.app')

@section('title', 'Dashboard Page')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
        <h1 style="font-size: 1.5rem; font-weight: 600; color: #333; margin-bottom: 1rem;">
            Welcome, <span style="color: #ff6d00;">{{ Auth::user()->name }}</span>
        </h1>
        
        <p>
            {{ Auth::user()->balance }} {{ '€' }} 
            <a href="{{ url('/balance/deposit') }}" style="margin-left: 1rem; color: #007bff; text-decoration: none;">Deposit</a>
            <a href="{{ url('/balance/withdraw') }}" style="margin-left: 0.5rem; color: #007bff; text-decoration: none;">Withdraw</a>
        </p>

        <div style="margin: 1rem 0;">
            <a href="{{ route('user.profile', ['id' => auth()->id()]) }}" style="color: #007bff; text-decoration: none; margin-right: 1rem;">My profile</a>
            <a href="{{ route('profile.edit') }}" style="color: #007bff; text-decoration: none;">Edit Profile</a>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
            @csrf
            <button type="submit" 
                    style="background-color: #003d7e; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection