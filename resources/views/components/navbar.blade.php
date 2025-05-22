<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom shadow-sm">
    <div class="container-fluid">
        {{-- Logo and Brand --}}
        <a class="navbar-brand d-flex align-items-center fw-bold" href="/">
            <img src="{{ asset('shield.png') }}" alt="SHIELD Logo" class="me-2 img-fluid" style="max-width: 50px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.19);">
            SHIELD
        </a>

        {{-- Toggler Button --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar Links --}}
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                {{-- Common Links --}}
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/home">IDS</a>
                </li>

                {{-- Guest Links --}}
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('showProjectsPublicly') }}">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.login') }}">Login</a>
                    </li>
                @endguest

                {{-- Authenticated User Links --}}
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.logout') }}">Logout</a>
                    </li>
                @endauth

                {{-- Always Visible --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>



