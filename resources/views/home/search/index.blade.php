@extends('layouts.app')

@section('title', 'Busqueda de productos')

@section('header')
    Busqueda de productos
@endsection

@section('links')
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
@endsection

@section('scripts')
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <!-- Page Script -->
    <script>
      $(function () {

        $('.sideform input[type="checkbox"]').iCheck({
          checkboxClass: 'icheckbox_flat-orange',
        });

      });
    </script>
@endsection

@section('content')
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-amazon" style="color: #ff9900"></i> Amazon API</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            {!! Form::open(['route' => 'amazon.search', 'method' => 'GET', 'class' =>'form-horizontal']) !!}
                      <div class="form-group">
                          {!! Form::label('keywords', 'Palabras Clave', ['class' => 'col-sm-2 control-label']) !!}
                          <div class="col-sm-10">
                              {!! Form::text('keywords', null, ['class' => 'form-control', 'placeholder' => 'camisa, telefono, etc']) !!}
                          </div>
                      </div>
                      <div class="form-group">
                          {!! Form::label('category', 'Categoría', ['class' => 'col-sm-2 control-label']) !!}
                          <div class="col-sm-10">
                              {!! Form::select('category', config('amazonAPI.category'), null, ['class' => 'form-control', 'placeholder' => 'Categoría..']) !!}
                          </div>
                      </div>
                      {{-- <div class="form-group">
                          {!! Form::label('condition', 'Condición', ['class' => 'col-sm-2 control-label']) !!}
                          <div class="col-sm-10">
                              {!! Form::select('condition', config('amazonAPI.condition'), null, ['class' => 'form-control', 'placeholder' => 'Condición..']) !!}
                          </div>
                      </div> --}}
                      <div class="form-group">
                          {!! Form::label('sort', 'Ordenar por', ['class' => 'col-sm-2 control-label']) !!}
                          <div class="col-sm-10">
                              {!! Form::select('sort', config('amazonAPI.sort'), null, ['class' => 'form-control', 'placeholder' => 'Ordenar por..']) !!}
                          </div>
                      </div>

                      <div class="form-group">
                          {!! Form::label('prime', 'Prime', ['class' => 'col-sm-2 control-label']) !!}
                          <div class="col-sm-10">
                             <p class="sideform" style="margin-top:.5%;">
                                 {!! Form::checkbox('prime', true, false) !!}
                             </p>
                          </div>
                      </div>

                      <div class="box-footer">
                          <a href="{{ route('home') }}" class="btn btn-default">Volver</a>
                          {!! Form::submit('Buscar', ['class' => 'btn btn-info pull-right']) !!}
                      </div>
                      <!-- /.box-footer -->
                {!! Form::close() !!}

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
@endsection
