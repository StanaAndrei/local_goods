<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    @if (session('status'))
        <div style="color: green;">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ url('/login') }}">
        @csrf
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
            <label>
                <input type="checkbox" name="remember"> Remember Me?
            </label>
        </div>
        <button type="submit">Login</button>
    </form>
    <a href="{{ route('register') }}">Don't have an account? Register</a>
    <br>
    <a href="{{ route('password.request') }}">Forgot/reset password</a>
</body>
</html>