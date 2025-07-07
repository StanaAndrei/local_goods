<!DOCTYPE html>
<html>
<head>
    <title>Home - Local Goods</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
        }
        .hero {
            background: linear-gradient(rgba(0,61,126,0.8), rgba(0,61,126,0.8)), 
                        url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 0 1rem;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #ff6d00;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }
        .motto {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
            max-width: 800px;
        }
        .auth-links {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .auth-links a {
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        .login-btn {
            background-color: #ff6d00;
            color: white;
        }
        .login-btn:hover {
            background-color: #e65100;
        }
        .register-btn {
            background-color: white;
            color: #003d7e;
            border: 1px solid #003d7e;
        }
        .register-btn:hover {
            background-color: #003d7e;
            color: white;
        }
        .features {
            padding: 3rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .features h2 {
            color: #003d7e;
            margin-bottom: 2rem;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .feature-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>Welcome to LOCAL GOODS!</h1>
        <p class="motto">Buy and sell quality local products directly from Romanian producers</p>
        
        @auth
            <div style="margin-top: 2rem;">
            <a href="{{ route('dashboard') }}" 
              style="background-color: #ff6d00; color: white; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 500; transition: background-color 0.3s;">
                Go to Dashboard
            </a>
        @else
            <div class="auth-links">
                <a href="{{ route('login') }}" class="login-btn">Login</a>
                <a href="{{ route('register') }}" class="register-btn">Register</a>
            </div>
        @endauth
    </div>
</div>

    <div class="features">
        <h2>Why choose LOCAL GOODS?</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <h3>🏡 100% Romanian Products</h3>
                <p>Support local producers and the Romanian economy</p>
            </div>
            <div class="feature-card">
                <h3>💰 Fair Prices</h3>
                <p>Direct from producers means better prices for you</p>
            </div>
            <div class="feature-card">
                <h3>🚚 Fast Delivery</h3>
                <p>Get your local products quickly and fresh</p>
            </div>
        </div>
    </div>
</body>
</html>