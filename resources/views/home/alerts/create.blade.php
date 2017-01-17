@extends('layouts.app')

@section('content')

  <div class="row">
      <div class="col-lg-10 col-lg-offset-1">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Crear Alerta</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <div class="row">
                      <div class="col-lg-12">
                        {!! Form::open(['route' => 'alertas.store', 'method' => 'POST', 'class' =>'form-horizontal']) !!}
                          <div class="form-group">
                              {!! Form::label('description', 'Descripción', ['class' => 'col-sm-2 control-label']) !!}
                              <div class="col-sm-10">
                                  {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => '...', 'required']) !!}
                              </div>
                          </div>
                          <div class="form-group col-sm-3 control-label pull-right">
                              {!! Form::submit('Registrar', ['class' => 'btn btn-info form-control']) !!}
                          </div>
                        {!! Form::close() !!}
                      </div>
                      <!-- /.col-lg-12 (nested) -->
                  </div>
                  <!-- /.row (nested) -->
              </div>
              <!-- /.panel-body -->
          </div>
          <!-- /.panel -->
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->


@endsection
