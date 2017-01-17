@extends('layouts.app')

@section('title', 'Lista de productos')

@section('header')
    Lista de productos
@endsection

@section('links')
    <meta name="csrf-token" content="{{ Session::token() }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/skin-yellow.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">

@endsection

@section('scripts')
    <!-- Notify -->
    <script src="{{ asset('plugins/notify/notify.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <!-- Page Script -->
    <script>
      $(function () {
        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        $('.mercadolibre input[type="checkbox"]').iCheck({
          checkboxClass: 'icheckbox_flat-green',
        });

        $('.linio input[type="checkbox"]').iCheck({
          checkboxClass: 'icheckbox_flat-purple',
        });

        $('.sideform input[type="checkbox"]').iCheck({
          checkboxClass: 'icheckbox_flat-orange',
        });


        //Enable check and uncheck all functionality
        $(".checkbox-mercadolibre").click(function () {
          var clicks = $(this).data('clicks');
          if (clicks) {
            //Uncheck all checkboxes
            $(".mercadolibre input[type='checkbox']").iCheck("uncheck");
            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
          } else {
            //Check all checkboxes
            $(".mercadolibre input[type='checkbox']").iCheck("check");
            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
          }
          $(this).data("clicks", !clicks);
        });

        $(".checkbox-linio").click(function () {
          var clicks = $(this).data('clicks');
          if (clicks) {
            //Uncheck all checkboxes
            $(".linio input[type='checkbox']").iCheck("uncheck");
            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
          } else {
            //Check all checkboxes
            $(".linio input[type='checkbox']").iCheck("check");
            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
          }
          $(this).data("clicks", !clicks);
        });

        //Handle starring for glyphicon and font awesome
        $(".mailbox-star").click(function (e) {
          e.preventDefault();
          //detect type
          var $this = $(this).find("a > i");
          var glyph = $this.hasClass("glyphicon");
          var fa = $this.hasClass("fa");

          //Switch states
          if (glyph) {
            $this.toggleClass("glyphicon-star");
            $this.toggleClass("glyphicon-star-empty");
          }

          if (fa) {
            $this.toggleClass("fa-star");
            $this.toggleClass("fa-star-o");
          }
        });
      });

      $(document).ready(function(){
         $('.sidebar-toggle').click();


         $('.masive-sent').click(function(event) {
             event.preventDefault();

            var list = [];

             $(".icheckbox_flat-green").map(function(){
                 if ($(this).attr("aria-checked") == 'true') {
                     list.push($(this).find("input[type='checkbox']").val());
                 }
             });

             $.ajax({
                    type: "POST",
                    url: "{{ route('products.sendMeliMasive') }}",
                    data: {
                        '_token': $('meta[name=csrf-token]').attr('content'),
                        '_list': list,
                    },
                    beforeSend: function() {
                        $('.masive-sent').addClass('disabled');
                        $.notify("Espere mientras se lleva a cabo la operación! Esto puede tardar segundos o minutos dependiendo de la cantidad de productos que envió.", {
                            clickToHide: true,
                            autoHide: true,
                            autoHideDelay: 500000,
                            elementPosition: 'left top',
                            globalPosition: 'left top',
                            className: 'danger',
                       });
                    },
                    success: function( msg ) {
                        for (var i = 0; i < msg.length; i++) {
                            $.notify(msg[i]['content'], {
                                // whether to hide the notification on click
                                clickToHide: true,
                                // whether to auto-hide the notification
                                autoHide: true,
                                // if autoHide, hide after milliseconds
                                autoHideDelay: 500000,
                                // show the arrow pointing at the element
                                arrowShow: true,
                                // arrow size in pixels
                                arrowSize: 5,
                                // default positions
                                elementPosition: 'left top',
                                globalPosition: 'left top',
                                // default style
                                style: 'bootstrap',
                                // default class (string or [string])
                                className: 'info',
                                // show animation
                                showAnimation: 'slideDown',
                                // show animation duration
                                showDuration: 400,
                                // hide animation
                                hideAnimation: 'slideUp',
                                // hide animation duration
                                hideDuration: 200,
                                // padding between element and notification
                                gap: 2
                           });
                        }
                    },
                    error: function(xhr) {
                        $.notify("Ocurrio un error al realizar la operación: " + xhr.statusText, {
                            clickToHide: true,
                            autoHide: true,
                            autoHideDelay: 500000,
                            elementPosition: 'left top',
                            globalPosition: 'left top',
                            className: 'error',
                       });
                    },
                    complete: function() {
                        $('.masive-sent').removeClass('disabled');
                    }
                });

         });
      });
    </script>
@endsection

@section('content')
    <div class="row">
      <div class="col-md-3">
        <!-- Profile Image -->
        <div id="ajaxResponse" class="box box-primary">
          <div class="box-body">
          {!! Form::open(['route' => 'amazon.search', 'method' => 'GET', 'class' =>'form-horizontal']) !!}
                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::text('keywords', Request::all()['keywords'], ['class' => 'form-control', 'placeholder' => 'Palabras Clave...']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::select('category', config('amazonAPI.category'), Request::all()['category'], ['class' => 'form-control', 'placeholder' => 'Categoría..']) !!}
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::select('condition', config('amazonAPI.condition'), Request::all()['condition'], ['class' => 'form-control', 'placeholder' => 'Condición..']) !!}
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::select('sort', config('amazonAPI.sort'), Request::all()['sort'], ['class' => 'form-control', 'placeholder' => 'Ordenar por..']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('prime', 'Prime', ['class' => 'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                           <p class="sideform" style="margin-top:2.5%;">
                               {!! Form::checkbox('prime', true, isset(Request::all()['prime']) ? Request::all()['prime'] : false) !!}
                           </p>
                        </div>
                    </div>

                    <div class="box-footer">
                        {!! Form::submit('Buscar', ['class' => 'btn btn-info pull-right']) !!}
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
              <h3 class="box-title"><i class="fa fa-th-list" style=""></i>{{ $results[0]->Items->TotalResults }} Resultados</h3>
              <a href="{{ route('amazon.masive', Request::all()) }}" class="btn bg-purple pull-right"><i class="fa fa-rocket" aria-hidden="true"></i> Enviar Todos</a>

            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body no-padding">
            <div class="mailbox-controls">
                @include('partials.pagination')
            </div>
            <div class="table-responsive">
                @include('partials.results')
              <!-- /.table -->
            </div>
            <!-- /.mail-box-messages -->
          </div>
          <!-- /.box-body -->
          <div class="box-footer no-padding">
            <div class="mailbox-controls">
                @include('partials.pagination')
            </div>
          </div>
        </div>
      </div>
      <!-- /.col -->
    </div>
@endsection
