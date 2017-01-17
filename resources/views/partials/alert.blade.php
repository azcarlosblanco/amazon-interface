@if (Session::has('alert'))
 <div class="alert alert-warning col-lg-10 col-lg-offset-1">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
     <span aria-hidden="true">&times;</span>
   </button>
     <p style="word-break: break-all;">
       {{ Session::get('alert') }}
     </p>
 </div>
@endif
