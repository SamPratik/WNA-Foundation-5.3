<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="{{ route('home') }}">
    <img class="logo" src="{{ asset('images/logo.png') }}" alt="Logo">
    WNA Foundation
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse links" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a href="{{ route('home') }}" class="nav-link @yield('ActiveHome')">Home <span class="sr-only">(current)</span></a>
      </li>
      {{-- {{ route('home') }} --}}
      <li class="nav-item">
        <a href="{{ route('home') }}#aboutus" class="nav-link">About US</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('achievemetns.index') }}" class="nav-link @yield('ActiveAchievements')">Achievements</a>
      </li>
      {{-- {{ route('home') }} --}}
      <li class="nav-item">
        <a href="{{ route('home') }}#ourWork" class="nav-link">Our Work</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('news.index') }}" class="nav-link @yield('ActiveNews')">News</a>
      </li>
      <li class="nav-item">
        <a href="#contactNotice" class="nav-link">Notice</a>
      </li>
      <li class="nav-item">
        <a href="#contactNotice" class="nav-link">Contact</a>
      </li>
      @if(Auth::check())
      <li class="nav-item">
        <a href="{{ route('admin.logout') }}" class="nav-link">Logout</a>
      </li>
      @endif
    </ul>
  </div>
</nav>
