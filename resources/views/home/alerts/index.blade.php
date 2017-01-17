@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-10 col-lg-offset-1">
    <!-- Profile Image -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="form-group col-lg-8 control-label">
              <h3 class="box-title">Alertas</h3>
            </div>
            <div class="form-group col-lg-4 control-label">
              <a href="{{ route('alertas.create') }}" class="btn btn-info form-control">
                <i class="fa fa-plus fa-fw"></i>
                Agregar Nueva Alerta
              </a>
            </div>
        </div>
      <div class="box-body">
          <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="todo-list">
        @if ($alerts->count())
            @foreach ($alerts as $alert)
                <li>
                    <!-- todo text -->
                    <span class="text">{{ $alert->description }}</span>
                    <small class="label label-danger"><i class="fa fa-clock-o"></i>
                        {{ $alert->created_at->format('d/m/Y') }}
                    </small>
                    <!-- General tools such as edit or delete-->
                    <div class="tools" style="display:inline-block">
                        <a href="{{ route('alertas.show', $alert->id) }}"><i class="fa fa-eye fa-lg"></i></a>
                        <a href="{{ route('alertas.destroy', $alert->id) }}" onclick="return confirm('¿Seguro que deseas eliminar a este usuario?')"><i class="fa fa-trash-o fa-lg"></i></a>
                    </div>
                </li>
            @endforeach
        @else
            <li>
                <span class="text" style="margin-left:50%;transform:translateX(-50%);">No hay alertas</span>
            </li>
        @endif
        </ul>
        <div class="text-center">
            {{ $alerts->render() }}
        </div>
      </div>
      <!-- /.box-body -->
     </div>
    <!-- /.box -->
    </div>
</div>

@endsection
