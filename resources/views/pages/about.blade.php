<!DOCTYPE html>
<html>
<head>
    <title>About Us - Local Goods</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        nav {
            background-color: #003d7e;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 2rem;
        }
        nav li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        nav li a:hover {
            background-color: rgba(255,255,255,0.2);
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .about-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        h1 {
            color: #ff6d00;
            margin-bottom: 1.5rem;
        }
        h2 {
            color: #003d7e;
            margin: 1.5rem 0 1rem;
        }
        p {
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .team-member {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1.5rem;
            text-align: center;
        }
        .team-member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            border: 3px solid #ff6d00;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ url('/about') }}">About</a></li>
            <li><a href="{{ url('/contact') }}">Contact</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="about-card">
            <h1>About Local Goods</h1>
            
            <h2>Our Story</h2>
            <p>
              Founded in 2023, Local Goods is Romania's premier marketplace for authentic local products. 
              We connect buyers with trusted sellers across the country, promoting Romanian craftsmanship and quality goods.
            </p>
            
            <h2>Our Mission</h2>
            <p>
              Romania has always been a big producer of agricultural and handcrafted goods.
              People have been harvesting their own vegetables for many years but lately they
              have had a problem selling their products.
              This changes the game
            </p>
            
            <h2>Why Choose Us?</h2>
            <ul>
                <li>100% Romanian products</li>
                <li>Direct from producers</li>
                <li>Quality assurance</li>
                <li>Secure transactions</li>
                <li>Fast delivery nationwide</li>
            </ul>

            {{-- <h2>Our Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Team Member">
                    <h3>Ana Popescu</h3>
                    <p>Founder & CEO</p>
                </div>
                <div class="team-member">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Team Member">
                    <h3>Mihai Ionescu</h3>
                    <p>Head of Operations</p>
                </div>
                <div class="team-member">
                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Team Member">
                    <h3>Elena Dumitrescu</h3>
                    <p>Customer Support</p>
                </div>
            </div> --}}
        </div>
    </div>
</body>
</html>