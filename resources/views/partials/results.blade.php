@foreach ($results as $result)
    @foreach ($result->Items->Item as $item)
        @if ((int)$item->Offers->TotalOffers->__toString())
            @if (isset(Request::all()['prime']) ? (int)$item->Offers->Offer->OfferListing->IsEligibleForPrime : true)
                @if ($item->OfferSummary->LowestNewPrice->FormattedPrice != 'Too low to display')
                    @if (isset($item->MediumImage->URL))
                        <div class="panel box box-default">
                            <div class="box-header with-border">
                                <div class="flex-container">
                                    <p class="mercadolibre flex-item"><input type="checkbox" name="asin" value="{{ $itemAsin = $item->ASIN }}"> <img class="image-responsive" src="http://a3.mzstatic.com/us/r30/Purple62/v4/84/05/d2/8405d2ac-394d-c3ac-b038-5233e22e927c/icon175x175.jpeg" alt="" data-toggle="tooltip" data-placement="right" title="Mercadolibre" /></p>
                                    <p class="linio flex-item"><input type="checkbox"> <img class="image-responsive" src="http://a1.mzstatic.com/us/r30/Purple62/v4/9b/01/94/9b0194a9-6e1c-2619-8271-967891f96288/icon175x175.png" alt="" data-toggle="tooltip" data-placement="right" title="Linio" /></p>
                                    <p class="flex-item">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#{{ $itemAsin }}">
                                            Ver descripción
                                        </a>
                                    </p>
                                    <p class="flex-item"><strong>{{ str_limit($item->ItemAttributes->Title, 35) }}</strong></p>
                                    <p class="flex-item">Precio: {{ $item->OfferSummary->LowestNewPrice->FormattedPrice }}</p>
                                    <p class="flex-item">Cant: {{ $item->OfferSummary->TotalNew }}</p>
                                </div>
                            </div>
                            <div id="{{ $itemAsin }}" class="panel-collapse collapse">
                                <div class="box-body">
                                    <div class="row margin-bottom">
                                        <div class="col-sm-3">
                                            <img class="img-responsive" src="{{ $item->MediumImage->URL }}" alt="Photo">
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-9">
                                            <h4>{{ $item->ItemAttributes->Title }}</h4>
                                            <ul class="list-unstyled">
                                                <li>Por: {{ $item->ItemAttributes->Brand }}</li>
                                                <li>Nuevos: {{ $item->OfferSummary->TotalNew }}, usados: {{ $item->OfferSummary->TotalUsed }}, collecionables: {{ $item->OfferSummary->TotalCollectible }}, refraccionados/reparados: {{ $item->OfferSummary->TotalRefurbished }}</li>
                                                <li>Precio: {{ $item->OfferSummary->LowestNewPrice->FormattedPrice  }}</li>
                                            </ul>
                                            <div class="panel box box-warning">
                                                <div class="box-body">
                                                    <ul>
                                                        @foreach ($item->ItemAttributes->Feature as $feature)
                                                            <li>
                                                                {{ $feature }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>                                       </div>
                                                <div class="form-group margin-bottom-none row">
                                                    <div class="col-sm-4">
                                                        <a href="{{ route('products.sendMeli') }}"
                                                        onclick="event.preventDefault();
                                                        document.getElementById('sendProduct-form-{{$itemAsin}}').submit();" class="btn btn-primary pull-right btn-block btn-sm">Enviar a Mercadolibre</a>
                                                        <form id="sendProduct-form-{{$itemAsin}}" action="{{ route('products.sendMeli') }}" method="POST" style="display: none;">
                                                            {{ csrf_field() }}
                                                            <input type="text" name="amazonId" value="{{ $itemAsin }}">
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <a href="{{ route('products.sendLinio') }}"
                                                        onclick="event.preventDefault();
                                                        document.getElementById('sendProduct-form-{{$itemAsin}}-linio').submit();" class="btn btn-primary pull-right btn-block btn-sm">Enviar a Linio</a>
                                                        <form id="sendProduct-form-{{$itemAsin}}-linio" action="{{ route('products.sendLinio') }}" method="POST" style="display: none;">
                                                            {{ csrf_field() }}
                                                            <input type="text" name="amazonId" value="{{ $itemAsin }}">
                                                        </form>
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
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
        @endif
    @endforeach
@endforeach
