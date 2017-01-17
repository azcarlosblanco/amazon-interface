@extends('layouts.app')

@section('title', 'Navegar Categorías')

@section('header')
    Navegar Categorías
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

@section('breadcrumb')
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}"><i class="fa fa-home"></i> Início</a></li>
      <li><a href="{{ route('amazon.index') }}"> Amazon</a></li>
      <li {{ Html::classes(['active' => !isset(Request::all()['node'])]) }}>Categorías</li>
      @if (isset($actualCategory))
          <li {{ Html::classes(['active' => !isset(Request::all()['child'])]) }}>{{ array_search($actualCategory, config('amazonAPI.category_browse_node')) }}</li>
      @endif

    </ol>
@endsection

@section('content')
    <div class="row">
      <div class="col-md-3">
        <!-- Profile Image -->
        <div class="box box-primary">
          <div class="box-body">
              <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="todo-list">
                @if (!isset(Request::all()['node']))
                    @foreach (config('amazonAPI.category_browse_node') as $key => $value)
                        <li>
                            <a href="{{ route('amazon.browse-node-lookup', ['node' => $value]) }}">
                                <!-- drag handle -->
                                <span class="">
                                    <i class="fa fa-th-large"></i>
                                </span>
                                <!-- todo text -->
                                <span class="text">{{ $key }}</span>
                            </a>
                        </li>
                    @endforeach
                @else
                    @if (! isset($categoryChildren->Ancestors))
                        @foreach ($categoryChildren as $child)
                            <li>
                                <a href="{{ route('amazon.browse-node-lookup', ['node' => Request::all()['node'], 'child' => ($child->BrowseNodeId->__toString())]) }}">
                                    <!-- drag handle -->
                                    <span class="">
                                        <i class="fa fa-th-large"></i>
                                    </span>
                                    <!-- todo text -->
                                    <span class="text">{{ $child->Name }}</span>
                                    {{-- <span class="text">{{ $actualCategory }}</span>
                                    <span class="text">{{ ($child->BrowseNodeId->__toString()) }}</span> --}}
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a href="{{ route('amazon.browse-node-lookup', ['node' => Request::all()['node'], 'child' => $categoryChildren->BrowseNodeId->__toString()]) }}">
                                <!-- drag handle -->
                                <span>Ir Atrás</span>
                                <br>
                                <span class="">
                                    <i class="fa fa-th-large"></i>
                                </span>
                                <!-- todo text -->
                                <span class="text">{{ $categoryChildren->Name->__toString() }}</span>
                                {{-- <span class="text">{{ $actualCategory }}</span>
                                <span class="text">{{ ($child->BrowseNodeId->__toString()) }}</span> --}}
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

      </div>
      <!-- /.col -->
      @if (isset(Request::all()['node']))
          <div class="col-md-9">
            <div class="box box-primary">
              <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-th-list" style=""></i>{{ $results[0]->Items->TotalResults }} Resultados</h3>
                  <a href="{{ route('amazon.masive.categories', Request::all()) }}" class="btn bg-purple pull-right"><i class="fa fa-rocket" aria-hidden="true"></i> Enviar Todos</a>

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
      @else
          <div class="col-md-9">
            <div class="box box-primary">
              <div class="box-header with-border">

                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body no-padding text-center">
                  <h3 class="box-title"><i class="fa fa-arrow-left" style="margin:25vh 0;"></i> Clik en el menú de la izquierda para empezar a navegar las categorias</h3>
              </div>
              <!-- /.box-body -->

            </div>
          </div>
      @endif
      </div>
@endsection
