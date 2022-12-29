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
                     <div class="d-flex align-items-stretch justify-content-between wd-wide" style="flex-wrap: wrap">
                         <button class="btn btn-success btn-xl " type="button" style="flex-basis: 18%;" onclick="location.href='{{ route('orders') }}'"><strong><i class="fas fa-arrow-left"></i></strong></button>
                         <button class="btn btn-success btn-xl " type="button"  style="flex-basis: 18%;" onclick="changeStatusOrder('{{$order->id}}', 0, 1)"><strong>Сборка заказа</strong></button>
                         <button class="btn btn-success btn-xl" type="button"  style="flex-basis: 18%;" onclick="changeStatusOrder('{{$order->id}}', 1, 1)"><strong>Проверка заказа</strong></button>
                         <button class="btn btn-success btn-xl" type="button"  style="flex-basis: 18%;" onclick="changeStatusOrder('{{$order->id}}', 2, 1)"><strong>Помощь в сборке заказа</strong></button>
                         <button class="btn btn-success btn-xl" type="button"  style="flex-basis: 18%;" onclick="changeStatusOrder('{{$order->id}}', 3, 1)"><strong>Помощь в проверке заказа</strong></button>
                         <button class="btn btn-success btn-xl" type="button"  style="flex-basis: 18%; visibility: hidden;" onclick="location.href='{{ route('break') }}'"><strong>Перерывы/Обед</strong></button>
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
         </div>
      </section><!-- Page footer-->
       @include('includes.footer')
   </div>
@include('includes.global_scripts')
<!-- Datatables-->
<script src="{{URL::to('/')}}/vendor/datatables.net/js/jquery.dataTables.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/dataTables.buttons.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/buttons.colVis.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/buttons.flash.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/buttons.html5.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-buttons/js/buttons.print.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-keytable/js/dataTables.keyTable.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-responsive/js/dataTables.responsive.js"></script>
<script src="{{URL::to('/')}}/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net/js/jquery.dataTables.js">
<link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js">
<!-- SELECT2-->
<script src="{{URL::to('/')}}/vendor/select2/dist/js/select2.full.js"></script>
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
