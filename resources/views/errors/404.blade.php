@extends('layouts.app')

@section('title', '404')

@section('header')
    404 Error
@endsection

@section('content')
    <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Uups! Página no encontrada.</h3>

          <p>
            No pudimos encontrar la pagina que estas buscando.
            Por favor, <a href="{{ route('home') }}">Regresa al inicio</a> .
          </p>

        </div>
        <!-- /.error-content -->
      </div>
@endsection
