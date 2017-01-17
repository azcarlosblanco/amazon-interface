@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-10 col-lg-offset-1">
    <!-- Profile Image -->
    <div class="box box-primary">
        <div class="box-header with-border">
              <h3 class="box-title">Descripción de producto</h3>
        </div>
      <div class="box-body">
          <div class="row margin-bottom">
              <div class="col-sm-3">
                  <img class="img-responsive" src="{{ $product->img_url }}" alt="Photo">
              </div>
              <!-- /.col -->
              <div class="col-sm-9">
                  <h4>{{ $product->title }}</h4>
                  <ul class="list-unstyled">
                      <li>Por: {{ $product->brand }}</li>
                      <li>Precio: {{ $product->price / 100 }}</li>
                  </ul>
                 <div class="panel box box-warning">
                    <div class="box-body">
                        {{ $product->feature }}
                     </div>
                 </div>
                 <div class="form-group margin-bottom-none row">
                     <div class="col-sm-4">
                         <a href="" class="btn btn-info pull-right btn-block btn-sm disable">Actualizar</a>

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
      <!-- /.box-body -->
     </div>
    <!-- /.box -->
    </div>
</div>

@endsection
