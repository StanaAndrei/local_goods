<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ request()->route('token') }}">
        <input type="email" name="email" value="{{ old('email', request('email')) }}" required placeholder="Your email">
        <input type="password" name="password" required placeholder="New password">
        <input type="password" name="password_confirmation" required placeholder="Confirm new password">
        <button type="submit">Reset Password</button>
    </form>
    @if (session('status'))
        <div>{{ session('status') }}</div>
    @endif
    @error('email')
        <div>{{ $message }}</div>
    @enderror
    @error('password')
        <div>{{ $message }}</div>
    @enderror
</body>
</html>