@extends('layouts.app')

@section('title', 'HOME')

@section('header')
@endsection

@section('content')
    <!-- Info boxes -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-aqua" style="font-size:0px;"><img class="image-responsive" src="http://a3.mzstatic.com/us/r30/Purple62/v4/84/05/d2/8405d2ac-394d-c3ac-b038-5233e22e927c/icon175x175.jpeg" alt="" data-toggle="tooltip" data-placement="right" title="Mercadolibre" /></span>

      <div class="info-box-content">
        <span class="info-box-text">Mercadolibre</span>
        <span class="info-box-number">{{ $productsMeli->count() }}<small>unds.</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red" style="font-size:0px;"><img class="image-responsive" src="http://a1.mzstatic.com/us/r30/Purple62/v4/9b/01/94/9b0194a9-6e1c-2619-8271-967891f96288/icon175x175.png" alt="" data-toggle="tooltip" data-placement="right" title="Linio" /></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Linio</span>
        <span class="info-box-number">{{ $productsLinio->count() }}<small>unds.</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix visible-sm-block"></div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fa fa-reply"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Ordenes</span>
        <span class="info-box-number">{{ $orders->count() }}<small> pendientes.</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><i class="fa fa-shopping-cart"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Carritos</span>
        <span class="info-box-number">{{ $carts->count() }}<small> Por procesar</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->

{{-- <div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Monthly Recap Report</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <div class="btn-group">
            <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-wrench"></i></button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li><a href="#">Separated link</a></li>
            </ul>
          </div>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-8">
            <p class="text-center">
              <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
            </p>

            <div class="chart">
              <!-- Sales Chart Canvas -->
              <canvas id="salesChart" style="height: 180px;"></canvas>
            </div>
            <!-- /.chart-responsive -->
          </div>
          <!-- /.col -->
          <div class="col-md-4">
            <p class="text-center">
              <strong>Goal Completion</strong>
            </p>

            <div class="progress-group">
              <span class="progress-text">Add Products to Cart</span>
              <span class="progress-number"><b>160</b>/200</span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
              </div>
            </div>
            <!-- /.progress-group -->
            <div class="progress-group">
              <span class="progress-text">Complete Purchase</span>
              <span class="progress-number"><b>310</b>/400</span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-red" style="width: 80%"></div>
              </div>
            </div>
            <!-- /.progress-group -->
            <div class="progress-group">
              <span class="progress-text">Visit Premium Page</span>
              <span class="progress-number"><b>480</b>/800</span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-green" style="width: 80%"></div>
              </div>
            </div>
            <!-- /.progress-group -->
            <div class="progress-group">
              <span class="progress-text">Send Inquiries</span>
              <span class="progress-number"><b>250</b>/500</span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-yellow" style="width: 80%"></div>
              </div>
            </div>
            <!-- /.progress-group -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- ./box-body -->
      <div class="box-footer">
        <div class="row">
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
              <h5 class="description-header">$35,210.43</h5>
              <span class="description-text">TOTAL REVENUE</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
              <h5 class="description-header">$10,390.90</h5>
              <span class="description-text">TOTAL COST</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block border-right">
              <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
              <h5 class="description-header">$24,813.53</h5>
              <span class="description-text">TOTAL PROFIT</span>
            </div>
            <!-- /.description-block -->
          </div>
          <!-- /.col -->
          <div class="col-sm-3 col-xs-6">
            <div class="description-block">
              <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
              <h5 class="description-header">1200</h5>
              <span class="description-text">GOAL COMPLETIONS</span>
            </div>
            <!-- /.description-block -->
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.box-footer -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row --> --}}

<!-- Main row -->
<div class="row">
  <!-- Left col -->
  <div class="col-md-8">

    <!-- TABLE: LATEST ORDERS -->
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Ultimas Ordenes</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class="table no-margin">
          @if ($orders->count())
              <thead>
                  <tr>
                      <th>Orden</th>
                      <th></th>
                      <th></th>
                      <th>Tipo</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($orders as $order)
                      <tr>
                          <td><a href="">{{ $order->resource }}</a></td>
                          <td>{{ ' '/*App\Entities\Product::isMeliId($order->product_id)->first()->detail->title*/ }}</td>
                          <td><span class="label label-success">{{ ' '/*$order->status*/ }}</span></td>
                          <td>{{ $order->topic }}</td>
                      </tr>
                  @endforeach
              </tbody>
          @else
              <div class="text-center">
                  Actualmente no hay ordenes que procesar

              </div>
          @endif
          </table>
        </div>
        <!-- /.table-responsive -->
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Devoluciones</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class="table no-margin">
          @if ($rejects->count())
              <thead>
                  <tr>
                      <th>Orden</th>
                      <th>Producto</th>
                      <th>Estatus</th>
                      <th>Tipo</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($rejects as $reject)
                      <tr>
                          <td><a href="">{{ $reject->resource }}</a></td>
                          <td>{{ App\Entities\Product::isMeliId($reject->product_id)->first()->detail->title }}</td>
                          <td><span class="label label-success">{{ $reject->status }}</span></td>
                          <td>{{ $reject->topic }}</td>
                      </tr>
                  @endforeach
              </tbody>
          @else
              <div class="text-center">
                  Actualmente no hay devoluciones que procesar
              </div>
          @endif
          </table>
        </div>
        <!-- /.table-responsive -->
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->

  <div class="col-md-4">
    <!-- Info Boxes Style 2 -->
    <div class="info-box bg-aqua">
      <span class="info-box-icon"><i class="fa fa-dollar"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">TRM</span>
        <span class="info-box-number">{{ config('productCostParameters.TRM') }} COP</span>

            <span class="progress-description">
              Precio por dolar
            </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
    {{-- <div class="info-box bg-green">
      <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Mentions</span>
        <span class="info-box-number">92,050</span>

        <div class="progress">
          <div class="progress-bar" style="width: 20%"></div>
        </div>
            <span class="progress-description">
              20% Increase in 30 Days
            </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
    <div class="info-box bg-red">
      <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Downloads</span>
        <span class="info-box-number">114,381</span>

        <div class="progress">
          <div class="progress-bar" style="width: 70%"></div>
        </div>
            <span class="progress-description">
              70% Increase in 30 Days
            </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
    <div class="info-box bg-yellow">
      <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>

      <div class="info-box-content">
        <span class="info-box-text">Direct Messages</span>
        <span class="info-box-number">163,921</span>

        <div class="progress">
          <div class="progress-bar" style="width: 40%"></div>
        </div>
            <span class="progress-description">
              40% Increase in 30 Days
            </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box --> --}}

    <!-- PRODUCT LIST -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Productos recientemente publicados</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <ul class="products-list product-list-in-box">
        @foreach ($productsMeli->take(5) as $product)
            @if (!is_null($product->detail))
                <li class="item">
                    <div class="product-img">
                        <img src="{{ $product->detail->img_url }}" alt="Product Image">
                    </div>
                    <div class="product-info">
                        <a href="javascript:void(0)" class="product-title">{{ $product->detail->title }}
                            <span class="label label-warning pull-right">${{ $product->detail->price }}</span></a>
                            <span class="product-description">
                                {{ $product->detail->feature }}
                            </span>
                        </div>
                    </li>
                    <!-- /.item -->
            @endif
        @endforeach
        </ul>
      </div>
      <!-- /.box-body -->
      <div class="box-footer text-center">
        <a href="{{ route('productos.index') }}" class="uppercase">Ver todos</a>
      </div>
      <!-- /.box-footer -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
@endsection
