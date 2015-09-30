<nav class="top-nav">
    <div class="container">
        <div class="nav-wrapper">
            @section('breadcrumbs')
                <a href="{{ url('/admin') }}" class="page-title">Campaign Admin Panel</a>
            @show

            <ul class="right">
                 <li><a class="dropdown-button" href="#!" data-activates="dropdown_profile">{{Auth::user()->name}}<i class="material-icons right">arrow_drop_down</i></a></li>
            </ul>
            <ul id="dropdown_profile" class="dropdown-content">
              <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<ul id="nav-mobile" class="side-nav fixed">
    <li class="logo">
        <a id="logo-container" href="{{ url('/admin') }}" class="brand-logo">
            <img src="{{ asset('img/sb-logo-green.png') }}" />
        </a>
    </li>
    <li><a href="{{ url('/admin') }}">Overview</a></li>
    <li><a href="{{ url('/admin/set/settings') }}">Grid Settings</a></li>
    <li><a href="{{ url('/admin/set/available') }}">Grid Availability</a></li>
    <li><a href="{{ url('/admin/set/content') }}">Page Content</a></li>
    <li><a href="{{ url('/admin/rewards') }}">Reward Levels</a></li>
    <li><a href="{{ url('/admin/purchases') }}">Purchases</a></li>
    <li><a href="{{ url('/admin/set/image') }}">Static Image</a></li>
</ul>
<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="mdi-navigation-menu"></i></a>