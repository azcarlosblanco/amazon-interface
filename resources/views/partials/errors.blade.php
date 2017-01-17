@if (count($errors) > 0)
  <div class="alert alert-danger col-lg-10 col-lg-offset-1">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
    <strong>Ups!</strong> Por favor revisa los siguientes errores:<br><br>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
