<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="Bootstrap Admin App" />
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

    <title>{{$title}}</title>

@include('includes.global_styles')
    @include('includes.global_app_styles')
</head>

<body>
{{csrf_field()}}
   <div class="wrapper">
      <!-- Main section-->
      <div class="block-center  wd-auto">
         <!-- Page content-->
         <div class="content-wrapper">
            <div class="content-heading text-center">
               <div class="text-center wd-wide">{{\Illuminate\Support\Facades\Auth::user()->name}}</div>
            </div>
             <div class="row mt-5">
                 <div class="col-12 text-center">
                     <button class="btn btn-success btn-xl mw-200 mr-3" type="button" style="flex-basis: 18%;" onclick="location.href='{{ route('main') }}'"><strong><i class="fas fa-home"></i></strong></button>
                     <button class="btn btn-success btn-xl mw-200 mr-3" type="button" onclick="changeStatus(2,1)"><strong>Перерыв</strong>
                     </button>
                     <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatus(3,1)"><strong>Пойти на обед</strong>
                     </button>
                 </div>
             </div>
         </div>
      </div><!-- Page footer-->
       @include('includes.footer')
   </div>
@include('includes.global_scripts')

@include('includes.global_app_scripts')
</body>

</html>
