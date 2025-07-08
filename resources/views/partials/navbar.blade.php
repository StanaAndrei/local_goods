<nav style="background-color: #003d7e; padding: 1rem 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <ul style="list-style-type: none; margin: 0; padding: 0; display: flex; justify-content: center; gap: 2rem;">
        <li><a href="{{ url('/') }}" style="color: white; text-decoration: none; font-weight: 500; padding: 0.5rem 1rem; border-radius: 4px; transition: background-color 0.3s;">Home</a></li>
        <li><a href="{{ url('/about') }}" style="color: white; text-decoration: none; font-weight: 500; padding: 0.5rem 1rem; border-radius: 4px; transition: background-color 0.3s;">About</a></li>
        <li><a href="{{ url('/products') }}" style="color: white; text-decoration: none; font-weight: 500; padding: 0.5rem 1rem; border-radius: 4px; transition: background-color 0.3s;">Store</a></li>
        <!-- Add more links as needed -->
    </ul>
    <style>
      nav li a:hover {
          background-color: rgba(255,255,255,0.2);
      }
    </style>
</nav>