@extends('layouts.app')

@section('title', 'Lista de productos')

@section('header')
    Lista de productos
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
      <div class="col-md-3">
        <!-- Profile Image -->
        <div class="box box-primary">
          <div class="box-header with-border">
              <h3 class="box-title">Filtrar</h3>
              <!-- /.box-tools -->
          </div>
          <div class="box-body">
          {!! Form::open(['route' => 'productos.index', 'method' => 'GET', 'class' =>'form-horizontal']) !!}
                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::select('published', ['meli' => 'En Mercadolibre', 'linio' => 'En Linio', 'both' => 'En Ambos', 'finished' => 'Finalizada', ], null, ['class' => 'form-control', 'placeholder' => 'Publicación...']) !!}
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit('Filtrar', ['class' => 'btn btn-info pull-right']) !!}
                    </div>
                    <!-- /.box-footer -->
              {!! Form::close() !!}
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-th-list" style=""></i>{{ $products->count() }} Resultados</h3>
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
                                    <img class="img-responsive" src="{{ $product->detail->img_url }}" alt="Photo">
                                </div>
                                <!-- /.col -->
                                <div class="col-md-9">
                                    <h4>{{ $product->detail->title }}</h4>
                                    <ul class="list-unstyled">
                                        <li>Por: {{ $product->detail->brand }}</li>
                                        <li>Precio: {{ $product->detail->price }}</li>
                                    </ul>
                                   <div class="panel box box-warning">
                                      <div class="box-body">
                                          {{ $product->detail->feature }}
                                       </div>
                                   </div>
                                    <div class="form-group margin-bottom-none row">
                                        @if (!$product->ml_p)
                                            <div class="col-sm-12">
                                                <a href="{{ route('products.reSendMeli', $product->id) }}" class="btn btn-info pull-right btn-block btn-sm">Republicar Mercadolibre</a>
                                            </div>
                                        @else
                                            <div class="col-sm-12">
                                                <a href="{{ route('products.destroy', $product->id) }}" class="btn btn-danger pull-right btn-block btn-sm">Eliminar</a>
                                            </div>
                                        @endif
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
          <div class="text-center">
              {{ $products->render() }}
          </div>
          <!-- /.box-body -->
        </div>
      </div>
      <!-- /.col -->
    </div>
@endsection
