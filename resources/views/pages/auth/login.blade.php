<!DOCTYPE html>
<html>
<head>
    <title>Login - Marketplace</title>
    <style>
        :root {
            --primary: #ff6d00; /* eMAG-like orange */
            --primary-dark: #e65100;
            --secondary: #003d7e; /* Deep blue */
            --light: #f5f5f5;
            --dark: #333;
            --success: #4caf50;
            --danger: #f44336;
            --border: #e0e0e0;
        }
        
        body {
            font-family: 'Roboto', Arial, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .login-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            margin: 1rem;
        }
        
        h1 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }
        
        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .alert-success {
            background-color: #e8f5e9;
            color: var(--success);
            border: 1px solid #c8e6c9;
        }
        
        .alert-error {
            background-color: #ffebee;
            color: var(--danger);
            border: 1px solid #ffcdd2;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 1rem;
            transition: border 0.3s;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(255,109,0,0.2);
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
        }
        
        .remember-me input {
            margin-right: 0.5rem;
        }
        
        button[type="submit"] {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        button[type="submit"]:hover {
            background-color: var(--primary-dark);
        }
        
        .auth-links {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
        }
        
        .auth-links a {
            color: var(--secondary);
            text-decoration: none;
            transition: color 0.3s;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .auth-links a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        
        .error-message {
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        @if ($errors->has('email'))
            <div class="alert alert-error">
                {{ $errors->first('email') }}
            </div>
        @endif
        
        <form method="POST" action="{{ url('/login') }}">
            @csrf
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email') <div class="error-message">{{ $message }}</div> @enderror
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
                @error('password') <div class="error-message">{{ $message }}</div> @enderror
            </div>
            
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="auth-links">
            <a href="{{ route('register') }}">New? Register here</a>
            <a href="{{ route('password.request') }}">Forgot/Reset password </a>
        </div>
    </div>
</body>
</html>