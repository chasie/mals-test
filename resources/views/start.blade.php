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
            <div class="content-heading text-center flex-column">
                @if(\Illuminate\Support\Facades\Auth::user()->status_work == 0)
                    <div class="text-center wd-wide">Добро пожаловать на работу, {{\Illuminate\Support\Facades\Auth::user()->name}}</div>
                    <div class="mt-3 h4">"Работа - это лучший способ насладиться жизнью"</div>
                @elseif(\Illuminate\Support\Facades\Auth::user()->status_work == 2)
                    <div class="text-center wd-wide">У Вас перерыв, {{\Illuminate\Support\Facades\Auth::user()->name}}</div>
                @elseif(\Illuminate\Support\Facades\Auth::user()->status_work == 3)
                    <div class="text-center wd-wide">Приятного аппетита!</div>
                @endif

            </div>
             <div class="row mt-5">
                 <div class="col-12 text-center">
                     @if(\Illuminate\Support\Facades\Auth::user()->status_work == 0)
                     <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatus(1);"><strong>Начать работу</strong></button>
                     @elseif(\Illuminate\Support\Facades\Auth::user()->status_work == 2)
                     <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatus(2,0);"><strong>Выйти с перерыва</strong></button>
                     @elseif(\Illuminate\Support\Facades\Auth::user()->status_work == 3)
                     <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatus(3,0);"><strong>Закончить обед</strong></button>
                     @endif
                 </div>
             </div>
         </div>
      </div><!-- Page footer-->
       @include('includes.footer')
   </div>
@include('includes.global_scripts')

@include('includes.global_app_scripts')


<script>
</script>
</body>

</html>
