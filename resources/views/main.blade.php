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

<body class="layout-h">
{{csrf_field()}}
   <div class="wrapper">
   @include('includes.header')
      <!-- Main section-->
      <section class="section-container">
         <!-- Page content-->
         <div class="content-wrapper">
             <div class="row mb-4">
                 <div class="col-12 ">
                     <div class="d-flex align-items-stretch justify-content-between flex-wrap" style=" margin-top: -15px;  margin-right: -12px; margin-left: -12px; ">
                         <button class="btn btn-success btn-xl" type="button"  style="flex-basis: 0; flex-grow: 1; flex-shrink: 0; margin-right: 12px; margin-left: 12px; margin-top: 15px;" onclick="location.href='{{ route('orders') }}'"><strong>Заказы</strong></button>
                         <button class="btn btn-success btn-xl" type="button"  style="flex-basis: 0; flex-grow: 1; flex-shrink: 0; margin-right: 12px; margin-left: 12px; margin-top: 15px;" onclick="changeStatusDelivery(1)"><strong>Доставка</strong></button>
                         <button class="btn btn-success btn-xl" type="button"  style="flex-basis: 0; flex-grow: 1; flex-shrink: 0; margin-right: 12px; margin-left: 12px; margin-top: 15px;" onclick="location.href='{{ route('duties') }}'"><strong>Рабочие обязанности</strong></button>
                         <button class="btn btn-success btn-xl" type="button"  style="flex-basis: 0; flex-grow: 1; flex-shrink: 0; margin-right: 12px; margin-left: 12px; margin-top: 15px;" onclick="changeStatusManagerTask(1)"><strong>Поручение руководителя</strong></button>
                         <button class="btn btn-success btn-xl" type="button"  style="flex-basis: 0; flex-grow: 1; flex-shrink: 0; margin-right: 12px; margin-left: 12px; margin-top: 15px;" onclick="location.href='{{ route('break') }}'"><strong>Перерывы/Обед</strong></button>
                     </div>
                 </div>
             </div>
             <div id="today_statistic_table">
                 <div class="row">
                     <div class="col-12">
                         <div class="card card-default">
                             <div class="card-header h4 pl-4 pb-0 mb-0">{{Auth::user()->name}}, вы:</div>
                             <div class="card-body">
                                 <div class="table-responsive">
                                     <table class="table table-hover">
                                         <tbody>
                                         <tr>
                                             <td>Собрали</td>
                                             <td> 0 </td>
                                             <td>заказов</td>
                                         </tr>
                                         <tr>
                                             <td>На сумму</td>
                                             <td> 0 </td>
                                             <td>рублей</td>
                                         </tr>
                                         <tr>
                                             <td>Проверили</td>
                                             <td> 0 </td>
                                             <td>заказов</td>
                                         </tr>
                                         <tr>
                                             <td>На сумму</td>
                                             <td> 0 </td>
                                             <td>рублей</td>
                                         </tr>
                                         <tr>
                                             <td>Брали перерыв</td>
                                             <td> 0 </td>
                                             <td>минут</td>
                                         </tr>
                                         </tbody>
                                     </table>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="row mb-4">
                 <div class="col-12 ">
                     <div class="d-flex align-items-stretch justify-content-center wd-wide">
                         <a href="{{ route('logout') }}" class="btn btn-danger btn-xl" type="button" ><strong>Завершить работу</strong></a>
                     </div>
                 </div>
             </div>
         </div>
      </section><!-- Page footer-->
       @include('includes.footer')
   </div>
@include('includes.global_scripts')

@include('includes.global_app_scripts')


<script>
    $(function (){
        init_today_statistic_table();
    });
    function init_today_statistic_table(){
        $.get('{{route('init_today_statistic_table')}}', function (result){
            $('#today_statistic_table').html('').html(result);
        });
    }
</script>
</body>

</html>
