@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-10 col-lg-offset-1">
    <!-- Profile Image -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="form-group col-lg-8 control-label">
              <h3 class="box-title">Carritos de compra</h3>
            </div>
        </div>
      <div class="box-body">
          <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="todo-list">
        @if ($carts->count())
            @foreach ($carts as $cart)
                <li>
                    <!-- todo text -->
                    <span class="text">{{ $cart->cart_id }}</span>
                    <small class="label label-warning"><i class="fa fa-clock-o"></i>
                        {{ $cart->amount / 100 . " $" }}
                    </small>
                    <small class="label label-primary"><i class="fa fa-clock-o"></i>
                        {{ $cart->created_at->format('d/m/Y') }}
                    </small>
                    @if ($cart->processed)
                        <small class="label label-primary"><i class="fa fa fa-rocket"></i>
                            {{ 'Procesado' }}
                        </small>
                    @endif
                    <!-- General tools such as edit or delete-->
                    <div class="tools" style="display:inline-block">
                        <a href="{{ $cart->purchase_url }}" target="_blank"
                            onclick="window.location = '{{ route('carritos.processed', $cart->id) }}';"><i class="btn btn-warning btn-xs fa fa-rocket"> Procesar</i></a>
                            <a href="{{ route('carritos.show', $cart->id) }}"><i class="btn btn-info btn-xs fa fa-eye"> Ver contenido</i></a>
                            <a href="{{ route('carritos.destroy', $cart->id) }}" onclick="return confirm('¿Seguro que deseas eliminar a este usuario?')"><i class="btn btn-danger btn-xs fa fa-trash-o"> Eliminar</i></a>
                        </div>
                    </li>
                @endforeach
        @else
            <li>
                <span class="text" style="margin-left:50%;transform:translateX(-50%);">No existen Carritos de compra</span>
            </li>
        @endif
        </ul>
      </div>
      <!-- /.box-body -->
     </div>
    <!-- /.box -->
    </div>
</div>

@endsection
