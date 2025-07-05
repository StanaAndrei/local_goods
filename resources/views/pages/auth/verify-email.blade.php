<!DOCTYPE html>
<html>
<head>
    <title>Verify Email</title>
</head>
<body>
    <h1>Email Verification Required</h1>
    <p>
        Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you.
        If you didn't receive the email, we will gladly send you another.
    </p>
    @if (session('status'))
        <div style="color: green;">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Resend Verification Email</button>
    </form>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>