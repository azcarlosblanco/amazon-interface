@if (Session::has('success'))
 <div class="alert alert-info col-lg-10 col-lg-offset-1">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
     <span aria-hidden="true">&times;</span>
   </button>
     <p style="word-break: break-all;">
       {{ Session::get('success') }}
       <p>
           {!! Session::get('link') !!}
       </p>
     </p>
 </div>
@endif
