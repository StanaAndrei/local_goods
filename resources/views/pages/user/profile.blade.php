@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $user->name }}'s Profile</h3>
                </div>

                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=100" 
                             alt="Profile" class="rounded-circle mr-4">
                        
                        <div>
                            <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                            <p class="mb-1"><strong>Role:</strong> {{ $user->role->name }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $user->phone_number }}</p>
                            <p class="mb-1"><strong>Joined:</strong> {{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection