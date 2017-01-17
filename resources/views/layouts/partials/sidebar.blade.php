<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- Sidebar user panel (optional) -->
    {{-- <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ asset('dist/img/avatar04.png') }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>Usuario</p>
        <!-- Status -->
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div> --}}

    <!-- search form (Optional) -->
    {{-- <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
      </div>
    </form> --}}
    <!-- /.search form -->

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
      <li class="header">Amazon</li>
      <li{{Html::classes(['active' => Route::is('amazon.index')])}}><a href="{{ route('amazon.index') }}"><i class="fa fa-amazon" style="color: #f39c12"></i> <span>Buscar Productos</span></a></li>
      <li{{Html::classes(['active' => Route::is('amazon.browse-node-lookup')])}}><a href="{{ route('amazon.browse-node-lookup') }}"><i class="fa fa-search" style="color: #f39c12"></i> <span>Navegar Categorías</span></a></li>

      <li class="header">Mercadolibre</li>
      <li{{Html::classes(['active' => Route::is('products.checkMeliOrders')])}}><a href="{{ route('products.checkMeliOrders') }}"><i class="fa fa-star" style="color: #dd4b39"></i> <span>Procesar Ordenes</span></a></li>
      <li{{Html::classes(['active' => Route::is('products.returnMeliOrders')])}}><a href="{{ route('products.returnMeliOrders') }}"><i class="fa fa-undo" style="color: #dd4b39"></i> <span>Procesar Devoluciones</span></a></li>
      {{-- <li{{Html::classes(['active' => Route::is('products.handle.searches')])}}><a href="{{ route('products.handle.searches') }}"><i class="fa fa-rocket" style="color: #dd4b39"></i> <span>Procesar Envios Masivos</span></a></li> --}}

      <li class="header">Panel de administración</li>
      <li{{Html::classes(['active' => Route::is('alertas.index')])}}><a href="{{ route('alertas.index') }}"><i class="fa fa-bell" style="color: #00a65a"></i> <span>Alertas</span></a></li>
      <li{{Html::classes(['active' => Route::is('productos.index')])}}><a href="{{ route('productos.index') }}"><i class="fa fa-cubes" style="color: #00a65a"></i> <span>Productos Publicados</span></a></li>
      <li{{Html::classes(['active' => Route::is('carritos.index')])}}><a href="{{ route('carritos.index') }}"><i class="fa fa-shopping-cart" style="color: #00a65a"></i> <span>Carritos de Compra</span></a></li>

      <li class="header">Super Administrador</li>
      <li{{Html::classes(['active' => Route::is('usuarios.index')])}}><a href="{{ route('usuarios.index') }}"><i class="fa fa-user" style="color: #00c0ef"></i> <span>Usuarios</span></a></li>
      <li{{Html::classes(['active' => Route::is('parametros.index')])}}><a href="{{ route('parametros.index') }}"><i class="fa fa-dollar" style="color: #00c0ef"></i> <span>Parametros de Costos</span></a></li>

    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>
