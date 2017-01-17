@extends('layouts.app')

@section('title', 'Lista de productos')

@section('header')
    Lista de productos
@endsection

@section('content')
    <div class="row">
      <div class="col-md-3">
        <!-- Profile Image -->
        <div class="box box-primary">
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
                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::select('condition', config('amazonAPI.condition'), Request::all()['condition'], ['class' => 'form-control', 'placeholder' => 'Condición..']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::select('sort', config('amazonAPI.sort'), Request::all()['sort'], ['class' => 'form-control', 'placeholder' => 'Ordenar por..']) !!}
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
            <h3 class="box-title"><i class="fa fa-th-list" style=""></i> {{ $result->Items->TotalResults }} Resultados</h3>
            <a href="{{ $result->Items->MoreSearchResultsUrl }}" target="_blank"class="btn btn-primary pull-right">Ver resultados en Amazon</a>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="active tab-pane">
                @foreach ($result->Items->Item as $item)
                    <div class="post clearfix">
                        <div class="row margin-bottom">
                            <div class="col-sm-2">
                                <img class="img-responsive" src="{{ $item->MediumImage->URL }}" alt="Photo">
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-10">
                                <h4>{{ $item->ItemAttributes->Title }}</h4>
                                <ul class="list-unstyled">
                                    <li>Por: {{ $item->ItemAttributes->Brand }}</li>
                                    <li>Nuevos: {{ $item->OfferSummary->TotalNew }}, usados: {{ $item->OfferSummary->TotalUsed }}, collecionables: {{ $item->OfferSummary->TotalCollectible }}, refraccionados/reparados: {{ $item->OfferSummary->TotalRefurbished }}</li>
                                    <li>Precio: {{ $item->OfferSummary->LowestNewPrice->FormattedPrice }}</li>
                                </ul>
                               <div class="panel box box-warning">
                                 <div class="box-header with-border">
                                   <h4 class="box-title">
                                     <a data-toggle="collapse" data-parent="#accordion" href="#{{ $itemAsin = $item->ASIN }}">
                                       <small>Ver descripción del producto</small>
                                     </a>
                                   </h4>
                                 </div>
                                 <div id="{{ $itemAsin }}" class="panel-collapse collapse">
                                   <div class="box-body">
                                       <ul>
                                       @foreach ($item->ItemAttributes->Feature as $feature)
                                           <li>
                                               {{ $feature }}
                                           </li>
                                       @endforeach
                                       </ul>
                                   </div>
                                 </div>
                               </div>
                                <div class="form-group margin-bottom-none row">
                                    <div class="col-sm-4">
                                        <a class="btn btn-primary pull-right btn-block btn-sm disabled">Enviar a Mercadolibre</a>
                                    </div>
                                    <div class="col-sm-4">
                                        <a class="btn btn-primary pull-right btn-block btn-sm disabled">Enviar a Linio</a>
                                    </div>
                                    <div class="col-sm-4">
                                        <a class="btn btn-warning pull-right btn-block btn-sm" target="_blank" href="{{ $item->DetailPageURL }}">Ver en amazon</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                    </div>
                    <!-- /.post -->
                @endforeach
              <!-- Post -->
              <div class="box-footer">
                  {!! Html::simplePagination(
                      $result->Items->Request->ItemSearchRequest->ItemPage,
                      $result->Items->TotalPages
                      ) !!}
              </div>

            </div>
            <!-- /.tab-pane -->

          </div>
          <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
      </div>
      <!-- /.col -->
    </div>

@endsection
