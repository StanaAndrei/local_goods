@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 2rem auto; padding: 0 1rem;">
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="background-color: #003d7e; color: white; padding: 1rem 1.5rem; font-size: 1.125rem; font-weight: 500;">
            {{ __('Edit Profile') }}
        </div>

        <div style="padding: 1.5rem;">
            @if (session('success'))
                <div style="background-color: #e8f5e9; color: #4caf50; padding: 0.75rem 1rem; border-radius: 4px; border: 1px solid #c8e6c9; margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1.25rem;">
                    <label for="name" style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">{{ __('Name') }}</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus
                           style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;">
                    @error('name')
                        <span style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="email" style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">{{ __('Email Address') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email"
                           style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;">
                    @error('email')
                        <span style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="phone_number" style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">{{ __('Phone Number') }}</label>
                    <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" autocomplete="tel"
                           style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem;">
                    @error('phone_number')
                        <span style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="address" style="display: block; font-size: 0.875rem; font-weight: 500; color: #444; margin-bottom: 0.25rem;">{{ __('Address') }}</label>
                    <textarea id="address" name="address" rows="3" autocomplete="address-line1"
                              style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.875rem; min-height: 100px;">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <span style="color: #f44336; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-top: 1.5rem; display: flex; gap: 0.75rem;">
                    <button type="submit" style="background-color: #ff6d00; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; font-weight: 500; cursor: pointer;">
                        {{ __('Update Profile') }}
                    </button>
                    <a href="{{ url()->previous() }}" style="background-color: #eee; color: #333; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-weight: 500;">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection