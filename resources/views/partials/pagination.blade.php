<div class="form-group margin-bottom-none row">
    <div class="col-sm-4">
        <!-- Check all button -->
        <button type="button" class="btn btn-default btn-sm checkbox-toggle checkbox-mercadolibre"><i class="fa fa-square-o"> MercadoLibre</i>
        </button>

        <!-- Check all button -->
        <button type="button" class="btn btn-default btn-sm checkbox-toggle checkbox-linio"><i class="fa fa-square-o"> Lineo</i>
        </button>
    </div>
    <div class="col-sm-4">
        <a class="btn btn-warning btn-block btn-sm masive-sent"><i class="fa fa-paper-plane" aria-hidden="true"></i> Publicar</a>
    </div>
    <div class="col-sm-4">
        <!-- /.btn-group -->
        <div class="pull-right">
            <div class="btn-group">
                {!! Html::simplePagination(
                    (isset(Request::all()['page'])) ? Request::all()['page'] : 1,
                    $results[0]->Items->TotalPages
                    ) !!}
            </div>
            <!-- /.btn-group -->
        </div>
        <!-- /.pull-right -->
    </div>
</div>
