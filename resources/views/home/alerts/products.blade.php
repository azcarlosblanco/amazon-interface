@extends('layouts.app')

@section('title', 'Lista de productos')

@section('header')
    Lista de productos en el carrito
@endsection

@section('links')


@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.sidebar-toggle').click();
        });
    </script>
@endsection

@section('content')
    <div class="row">
      <!-- /.col -->
      <div class="col-md-10 col-md-offset-1">
        <div class="box box-primary">
          <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-th-list" style=""></i>{{ count($products) }} Resultados</h3>
            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body no-padding">
            {{-- <div class="mailbox-controls">
                @include('partials.pagination')
            </div> --}}
            <div class="table-responsive">
                @foreach ($products as $product)
                    <div class="panel box box-default">
                        <div class="box-body" style="overflow:hidden">
                            <div class="row margin-bottom">
                                <div class="col-md-3">
                                    <img class="img-responsive" src="{{ $product['item']->detail->img_url }}" alt="Photo">
                                </div>
                                <!-- /.col -->
                                <div class="col-md-9">
                                    <h4>{{ $product['item']->detail->title }}</h4>
                                    <ul class="list-unstyled">
                                        <li>Por: {{ $product['item']->detail->brand }}</li>
                                        <li>Precio: {{ $product['item']->detail->price / 100 }}</li>
                                    </ul>
                                   <div class="panel box box-warning">
                                      <div class="box-body">
                                          {{-- {{ $product['item']->detail->feature }} --}}
                                          <ul>
                                              <li>Estatus de compra: {{ $product['order']->status }}</li>
                                              <li>Usuario en Mercadolibre: {{ $product['order']->nickname }}</li>
                                              <li>Correo Electronico: {{ $product['order']->email }}</li>
                                              <li>Telefono de contacto: {{ $product['order']->phone }}</li>
                                              <li>Dinero Pagado: {{ $product['order']->paid_amount }}</li>
                                              <li>Dirección de envio: {{ $product['order']->shipping_details }}</li>
                                          </ul>
                                       </div>
                                   </div>
                                    <div class="form-group margin-bottom-none row">
                                        <div class="col-sm-4">
                                            <a class="btn btn-info pull-right btn-block btn-sm disable">Republicar</a>
                                        </div>
                                        <div class="col-sm-4">
                                            <a class="btn btn-danger pull-right btn-block btn-sm disabled">Eliminar</a>
                                        </div>
                                        <div class="col-sm-4">
                                            <a class="btn btn-warning pull-right btn-block btn-sm disabled" target="_blank" href="">Ver en amazon</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                @endforeach
              <!-- /.table -->
            </div>
            <!-- /.mail-box-messages -->
          </div>
          <!-- /.box-body -->
        </div>
      </div>
      <!-- /.col -->
    </div>
@endsection
