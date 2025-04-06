<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <div class="logo-container" style="display: inline-block; padding: 5px">
            <img src="{{ asset('shield.png') }}" alt="Project Logo" class="img-fluid" style="max-width: 50px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.19); border-radius: 10px;">
        </div>
        <a style="font-weight: bolder;" class="navbar-brand" href="/">SHIELD</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/home">IDS</a>
                </li>
                @guest
                <li class="nav-item">
                    <a href="{{ route('showProjectsPublicly') }}" class="nav-link">Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.login') }}">Login</a>
                </li>
                @endguest
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.logout') }}">Logout</a>
                </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
