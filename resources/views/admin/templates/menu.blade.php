<nav class="top-nav">
    <div class="container">
        <div class="nav-wrapper">
            @section('breadcrumbs')
                <a href="{{ url('/admin') }}" class="page-title">Admin Panel</a>
            @show
        </div>
    </div>
</nav>
<ul id="nav-mobile" class="side-nav fixed">
    <li class="logo">
        <a id="logo-container" href="{{ url('/admin') }}" class="brand-logo">
            <img src="{{ asset('img/sb-logo-green.png') }}" />
        </a>
    </li>
    <li><a href="{{ url('/admin') }}">Grid Settings</a></li>
    <li><a href="{{ url('/admin/rewards') }}">Reward Levels</a></li>
    <li><a href="{{ url('/admin/purchases') }}">Purchases</a></li>
</ul>
<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>