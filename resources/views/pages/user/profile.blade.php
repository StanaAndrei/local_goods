@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem 1rem;">
    <div style="display: flex; justify-content: center;">
        <div style="width: 100%; max-width: 800px;">
            <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                <!-- Card Header -->
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #eee;">
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: #333; margin: 0;">{{ $user->name }}'s Profile</h3>
                </div>

                <!-- Card Body -->
                <div style="padding: 1.5rem;">
                    <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=100&background=003d7e&color=fff" 
                             alt="Profile" 
                             style="width: 100px; height: 100px; border-radius: 50%; margin-right: 1.5rem; border: 3px solid #ff6d00;">
                        
                        <div>
                            <p style="margin-bottom: 0.75rem; font-size: 0.875rem;">
                                <strong style="color: #003d7e; min-width: 80px; display: inline-block;">Email:</strong> 
                                <span style="color: #333;">{{ $user->email }}</span>
                            </p>
                            <p style="margin-bottom: 0.75rem; font-size: 0.875rem;">
                                <strong style="color: #003d7e; min-width: 80px; display: inline-block;">Role:</strong> 
                                <span style="color: #333;">{{ $user->role->name }}</span>
                            </p>
                            <p style="margin-bottom: 0.75rem; font-size: 0.875rem;">
                                <strong style="color: #003d7e; min-width: 80px; display: inline-block;">Phone:</strong> 
                                <span style="color: #333;">{{ $user->phone_number ?? 'Not provided' }}</span>
                            </p>
                            <p style="margin-bottom: 0.75rem; font-size: 0.875rem;">
                                <strong style="color: #003d7e; min-width: 80px; display: inline-block;">Joined:</strong> 
                                <span style="color: #333;">{{ $user->created_at->format('M Y') }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Additional sections can be added here -->
                    <div style="margin-top: 2rem;">
                        <h4 style="font-size: 1rem; font-weight: 600; color: #333; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #eee;">
                            User Statistics
                        </h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                            <div style="background-color: #f8f9fa; padding: 1rem; border-radius: 6px; text-align: center;">
                                <div style="font-size: 1.25rem; font-weight: 600; color: #ff6d00;">{{ $user->products->count() }}</div>
                                <div style="font-size: 0.75rem; color: #666;">Listed Products</div>
                            </div>
                            <!-- Add more stats as needed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection