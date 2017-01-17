<!-- Main Header -->
<header class="main-header">

  <!-- Logo -->
  <a href="{{ route('home') }}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><small>LARS</small></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>LARS</b></span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        <!-- Notifications Menu -->
        <li class="dropdown notifications-menu">
          <!-- Menu toggle button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-danger">{{ $appAlerts = App\Entities\Alert::notRead()->count() }}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Tienes {{$appAlerts}} alertas</li>
            <li>
              <!-- Inner Menu: contains the notifications -->
              <ul class="menu">
                  @foreach (App\Entities\Alert::notRead() as $element)
                      <li><!-- start notification -->
                          <a href="{{ route('alertas.show', $element->id) }}">
                              {{ $element->description }}
                          </a>
                      </li>
                  @endforeach
                <!-- end notification -->
              </ul>
            </li>
            <li class="footer"><a href="{{ route('alertas.index') }}">Ver todo</a></li>
          </ul>
        </li>
        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs">{{ $name = Auth::user()->name }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
              <p>
                {{ $name }}
                <small>{{ Auth::user()->role }}</small>
              </p>
            </li>
            <!-- Menu Body -->
            {{-- <li class="user-body">
              <div class="row">
                <div class="col-xs-4 text-center">
                  <a href="#">Followers</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Sales</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Friends</a>
                </div>
              </div>
              <!-- /.row -->
            </li> --}}
            <!-- Menu Footer-->
            <li class="user-footer">
              {{-- <div class="pull-left">
                <a href="/cuenta" class="btn btn-default btn-flat">Cuenta</a>
              </div> --}}
              <div class="pull-right">
                <a href=""
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Salir</a>
              </div>
              <form id="logout-form" action="{{ url('/salir') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
              </form>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        {{-- <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li> --}}
      </ul>
    </div>
  </nav>
</header>
