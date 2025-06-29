<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="email" name="email" required placeholder="Your email">
        <button type="submit">Send Password Reset Link</button>
    </form>
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif
    @error('email')
        <div>{{ $message }}</div>
    @enderror
</body>
</html>