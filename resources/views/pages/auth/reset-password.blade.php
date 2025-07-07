<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body style="font-family: 'Roboto', Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh;">
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; padding: 2rem; margin: 1rem;">
        <h1 style="color: #ff6d00; text-align: center; margin-bottom: 1.5rem;">Reset Password</h1>
        
        <form method="POST" action="{{ route('password.update') }}" style="margin-bottom: 1.5rem;">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">
            
            <div style="margin-bottom: 1.25rem;">
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', request('email')) }}" 
                    required 
                    placeholder="Your email" 
                    style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;"
                >
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <input 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="New password" 
                    style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;"
                >
            </div>
            
            <div style="margin-bottom: 1.25rem;">
                <input 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    placeholder="Confirm new password" 
                    style="width: 100%; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 1rem;"
                >
            </div>
            
            <button 
                type="submit" 
                style="background-color: #ff6d00; color: white; border: none; padding: 0.75rem; font-size: 1rem; border-radius: 4px; cursor: pointer; width: 100%; font-weight: 500;"
            >
                Reset Password
            </button>
        </form>

        @if (session('status'))
            <div style="background-color: #e8f5e9; color: #4caf50; padding: 0.75rem 1rem; border-radius: 4px; border: 1px solid #c8e6c9; margin-bottom: 1rem; font-size: 0.9rem;">
                {{ session('status') }}
            </div>
        @endif
        
        @error('email')
            <div style="background-color: #ffebee; color: #f44336; padding: 0.75rem 1rem; border-radius: 4px; border: 1px solid #ffcdd2; margin-bottom: 1rem; font-size: 0.9rem;">
                {{ $message }}
            </div>
        @enderror
        
        @error('password')
            <div style="background-color: #ffebee; color: #f44336; padding: 0.75rem 1rem; border-radius: 4px; border: 1px solid #ffcdd2; margin-bottom: 1rem; font-size: 0.9rem;">
                {{ $message }}
            </div>
        @enderror
        
        <div style="text-align: center;">
            <a href="{{ route('login') }}" style="color: #003d7e; text-decoration: none;">Back to Login</a>
        </div>
    </div>
</body>
</html>