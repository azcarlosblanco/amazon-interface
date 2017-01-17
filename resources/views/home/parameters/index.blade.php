@extends('layouts.app')

@section('content')

  <div class="row">
      <div class="col-lg-10 col-lg-offset-1">
          <div class="box box-primary">
            <div class="box-header with-border">
                <div class="form-group col-lg-8 control-label">
                  <h3 class="box-title">Parámetros para cualculos de costos</h3>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                  <div class="row">
                      <div class="col-lg-12">
                        <div class="table-responsive">
                          <table class="table table-hover">
                            <thead>
                              <th>
                                Parámetro
                              </th>
                              <th>
                                Valor en la App
                              </th>
                              <th>
                                Valor Real
                              </th>
                              <th>
                                Unidad
                              </th>
                              <th>
                                Acción
                              </th>
                            </thead>
                            <tbody>
                                <tr>
                                  <td>{{ 'TRM' }}</td>
                                  <td>{{ $parameters['TRM'] }}</td>
                                  <td>{{ $parameters['TRM'] }}</td>
                                  <td>{{ 'COP' }}</td>
                                  <td>
                                      <a href="{{ route('parametros.edit', 'TRM') }}" onclick="return confirm('¿Seguro que desea editar este parámetro?')" class="btn btn-warning">
                                          <i class="fa fa-pencil-square-o fa-fw"></i>
                                          Editar
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>{{ 'Impuestos USA' }}</td>
                                  <td>{{ $parameters['tax_usa'] }}</td>
                                  <td>{{ $parameters['tax_usa'] * 100 }}</td>
                                  <td>{{ '%' }}</td>
                                  <td>
                                      <a href="{{ route('parametros.edit', 'tax_usa') }}" onclick="return confirm('¿Seguro que desea editar este parámetro?')" class="btn btn-warning">
                                          <i class="fa fa-pencil-square-o fa-fw"></i>
                                          Editar
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>{{ 'IVA Colombia' }}</td>
                                  <td>{{ $parameters['iva_co'] }}</td>
                                  <td>{{ $parameters['iva_co'] * 100 }}</td>
                                  <td>{{ '%' }}</td>
                                  <td>
                                      <a href="{{ route('parametros.edit', 'iva_co') }}" onclick="return confirm('¿Seguro que desea editar este parámetro?')" class="btn btn-warning">
                                          <i class="fa fa-pencil-square-o fa-fw"></i>
                                          Editar
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>{{ 'Envio x KG' }}</td>
                                  <td>{{ $parameters['costo_envio_kg'] }}</td>
                                  <td>{{ $parameters['costo_envio_kg'] }}</td>
                                  <td>{{ 'COP' }}</td>
                                  <td>
                                      <a href="{{ route('parametros.edit', 'costo_envio_kg') }}" onclick="return confirm('¿Seguro que desea editar este parámetro?')" class="btn btn-warning">
                                          <i class="fa fa-pencil-square-o fa-fw"></i>
                                          Editar
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>{{ 'Peso por defecto' }}</td>
                                  <td>{{ $parameters['default_weight'] }}</td>
                                  <td>{{ $parameters['default_weight'] }}</td>
                                  <td>{{ 'Kgs.' }}</td>
                                  <td>
                                      <a href="{{ route('parametros.edit', 'default_weight') }}" onclick="return confirm('¿Seguro que desea editar este parámetro?')" class="btn btn-warning">
                                          <i class="fa fa-pencil-square-o fa-fw"></i>
                                          Editar
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>{{ 'Utilidad' }}</td>
                                  <td>{{ $parameters['utilidad'] }}</td>
                                  <td>{{ $parameters['utilidad'] * 100}}</td>
                                  <td>{{ '%' }}</td>
                                  <td>
                                      <a href="{{ route('parametros.edit', 'utilidad') }}" onclick="return confirm('¿Seguro que desea editar este parámetro?')" class="btn btn-warning">
                                          <i class="fa fa-pencil-square-o fa-fw"></i>
                                          Editar
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>{{ 'Comisión Mercadolibre' }}</td>
                                  <td>{{ $parameters['comision_meli'] }}</td>
                                  <td>{{ $parameters['comision_meli'] * 100}}</td>
                                  <td>{{ '%' }}</td>
                                  <td>
                                      <a href="{{ route('parametros.edit', 'comision_meli') }}" onclick="return confirm('¿Seguro que desea editar este parámetro?')" class="btn btn-warning">
                                          <i class="fa fa-pencil-square-o fa-fw"></i>
                                          Editar
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <td>{{ 'Comisión Linio' }}</td>
                                  <td>{{ $parameters['comision_linio'] }}</td>
                                  <td>{{ $parameters['comision_linio'] * 100}}</td>
                                  <td>{{ '%' }}</td>
                                  <td>
                                      <a href="{{ route('parametros.edit', 'comision_linio') }}" onclick="return confirm('¿Seguro que desea editar este parámetro?')" class="btn btn-warning">
                                          <i class="fa fa-pencil-square-o fa-fw"></i>
                                          Editar
                                    </a>
                                  </td>
                                </tr>
                            </tbody>
                          </table>
                        </div>
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
