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
             @if($user->order!=null)
            <div class="content-heading text-center">
               <div class="text-center wd-wide">Заказ № @if($user->order!=null){{$user->order->number}}@endif</div>
            </div>
             <div class="row mt-5">
                 @if($user->type_order == 0 )
                 <div class="col-12 text-center">
                     @if(!Auth::user()->isOrderPaused())
                     <button class="btn btn-success btn-xl mw-200 mr-3" type="button" onclick="changeStatusOrder('{{\Illuminate\Support\Facades\Auth::user()->order_id}}', '{{\Illuminate\Support\Facades\Auth::user()->type_order}}',0)"><strong>Закончить сборку</strong>
                     </button>
                     <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusWorkPause('{{\Illuminate\Support\Facades\Auth::user()->order_id}}',40,1)"><strong>Пауза сборки</strong>
                     </button>
                     @else
                         <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusWorkPause('{{\Illuminate\Support\Facades\Auth::user()->order_id}}',40,0)"><strong>Продолжить сборку</strong>
                         </button>
                         @endif
                 </div>
                 @elseif($user->type_order == 1)
                 <div class="col-12 text-center">
                     @if(!Auth::user()->isOrderPaused())
                     <button class="btn btn-success btn-xl mw-200 mr-3" type="button" onclick="changeStatusOrder('{{\Illuminate\Support\Facades\Auth::user()->order_id}}', '{{\Illuminate\Support\Facades\Auth::user()->type_order}}',0)"><strong>Закончить проверку</strong>
                     </button>
                     <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusWorkPause('{{\Illuminate\Support\Facades\Auth::user()->order_id}}',41,1)"><strong>Пауза проверки</strong>
                     </button>
                     @else
                         <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusWorkPause('{{\Illuminate\Support\Facades\Auth::user()->order_id}}',41,0)"><strong>Продолжить проверку</strong>
                         </button>
                     @endif
                 </div>
                 @elseif($user->type_order == 2)
                 <div class="col-12 text-center">
                     @if(!Auth::user()->isOrderPaused())
                     <button class="btn btn-success btn-xl mw-200 mr-3" type="button" onclick="changeStatusOrder('{{\Illuminate\Support\Facades\Auth::user()->order_id}}', '{{\Illuminate\Support\Facades\Auth::user()->type_order}}',0)"><strong>Закончить помощь</strong>
                     </button>
                     <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusWorkPause('{{\Illuminate\Support\Facades\Auth::user()->order_id}}',42,1)"><strong>Пауза помощи</strong>
                     </button>
                     @else
                         <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusWorkPause('{{\Illuminate\Support\Facades\Auth::user()->order_id}}',42,0)"><strong>Продолжить помощь</strong>
                         </button>
                     @endif
                 </div>
                 @elseif($user->type_order == 3)
                     <div class="col-12 text-center">
                         @if(!Auth::user()->isOrderPaused())
                             <button class="btn btn-success btn-xl mw-200 mr-3" type="button" onclick="changeStatusOrder('{{\Illuminate\Support\Facades\Auth::user()->order_id}}', '{{\Illuminate\Support\Facades\Auth::user()->type_order}}',0)"><strong>Закончить помощь в проверке</strong>
                             </button>
                             <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusWorkPause('{{\Illuminate\Support\Facades\Auth::user()->order_id}}',42,1)"><strong>Пауза помощи в проверке</strong>
                             </button>
                         @else
                             <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusWorkPause('{{\Illuminate\Support\Facades\Auth::user()->order_id}}',42,0)"><strong>Продолжить помощь в проверке</strong>
                             </button>
                         @endif
                     </div>
                 @endif
             </div>
                 @elseif($user->isdelivery==1)
                 <div class="content-heading text-center">
                     <div class="text-center wd-wide">Доставка</div>
                 </div>
                 <div class="row mt-5">
                         <div class="col-12 text-center">
                             <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusDelivery(0)"><strong>Закончить доставку</strong>
                             </button>
                         </div>
                 </div>
             @elseif($user->duty!=null)
                 <div class="content-heading text-center flex-column">
                     <div class="text-center wd-wide">Выполнение обязанности</div>
                     <div class="mt-3 h4">{{$user->duty->name}}</div>
                 </div>
                 <div class="row mt-5">
                         <div class="col-12 text-center">
                             <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusDuty({{$user->duty_id}},0)"><strong>Закончить рабочую обязанность</strong>
                             </button>
                         </div>
                 </div>
             @elseif($user->ismanagertask==1)
                 <div class="content-heading text-center">
                     <div class="text-center wd-wide">Поручение руководителя</div>
                 </div>
                 <div class="row mt-5">
                     <div class="col-12 text-center">
                         <button class="btn btn-success btn-xl mw-200" type="button" onclick="changeStatusManagerTask(0)"><strong>Закончить поручение</strong>
                         </button>
                     </div>
                 </div>
             @else
                 <div class="col-12 text-center">
                     <button class="btn btn-success btn-xl mw-200" type="button" onclick="location.href='{{route('main')}}'"><strong><i class="fas fa-home"></i></strong>
                     </button>
                 </div>
             @endif
         </div>
      </div><!-- Page footer-->
       @include('includes.footer')
   </div>
@include('includes.global_scripts')

@include('includes.global_app_scripts')


<script>
    $(function (){
    });

</script>
</body>

</html>
