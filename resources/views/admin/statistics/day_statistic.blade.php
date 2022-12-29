<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta name="description" content="Bootstrap Admin App">
   <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
   <link rel="icon" type="image/x-icon" href="favicon.ico">
   <title>{{$title}}</title><!-- =============== VENDOR STYLES ===============-->
   <!-- FONT AWESOME-->
   @include('admin.includes.global_styles')
<!-- Datatables-->
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css">
    <link rel="stylesheet" href="//cdn.datatables.net/fixedcolumns/3.3.3/css/fixedColumns.bootstrap4.min.css">

    <!-- DATETIMEPICKER-->
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">

    <!-- SELECT2-->
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/select2/dist/css/select2.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css">
    @include('admin.includes.global_app_styles')
</head>
<style>
    /* Ensure that the demo table scrolls */
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }

    /* Lots of padding for the cells as SSP has limited data in the demo */
    th,
    td {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }
    .DTFC_LeftBodyLiner {
        overflow-x: hidden;
    }
</style>
<body>
{{csrf_field()}}
   <div class="wrapper">
      <!-- top navbar-->
      @include('admin.includes.header')
       <!-- sidebar-->
       @include('admin.includes.aside')
      <!-- offsidebar-->
      <!-- Main section-->
      <section class="section-container">
         <!-- Page content-->
         <div class="content-wrapper">
            <div class="content-heading">
               <div>{{$title}}</div>
            </div>
             <style>
                 .select2-selection.select2-selection--multiple{
                     min-height: 32px!important;

                 }
                 .select2-container .select2-search--inline .select2-search__field{
                     margin-top: 5px;
                 }
                 body .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered{
                     padding: 0 8px;
                 }
                 .card-header .form-control-sm{
                     height: 34px!important;
                 }
             </style>
             <div class="row">
                 <div class="col-12">
                     <div class="card card-default">
                         <div class="card-header d-flex">
                             <div class="mr-3" style="width: 200px;">
                                 <input type="text" class="form-control form-control-sm input-date" id="filter__date" value="{{\Carbon\Carbon::now()->format('d.m.Y')}}">
                             </div>

                             <div class="mr-3" style="width: 300px;">
                                 <select class="form-control form-control-sm" id="filter__user" multiple="multiple" data-placeholder="Сотрудники отобразить">
                                         <option></option>
                                     @foreach($users as $row)
                                         <option value="{{$row->id}}">{{$row->name}}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="card-body">
                             <!-- START table-responsive-->
{{--                             <div class="table-responsive" >--}}
                                 <table class="table table-striped" id="statistics" style="width: 100%;">
                                     <thead>
                                     <tr>
                                         <th class="align-top">Сотрудник</th>
                                         <th class="align-top">Время<br> начала<br> работы</th>
                                         <th class="align-top">Время<br> окончания<br> работы</th>
                                         <th class="align-top">Опоздание</th>
                                         <th class="align-top">Время<br> потраченное<br> перерывы</th>
{{--6-9--}}
                                         <th class="align-top">Время<br> начала<br> обеда</th>
                                         <th class="align-top">Время<br> окончания<br> обеда</th>
                                         <th class="align-top">Просрочен<br> обед</th>
                                         <th class="align-top">Время<br> простоя</th>
                                         <th class="align-top">ВВП</th>
                                     </tr>
                                     </thead>
{{--                                     <tbody>--}}
{{--                                     </tbody>--}}
                                 </table>
{{--                             </div><!-- END table-responsive-->--}}
                         </div>
                     </div>
                 </div>
             </div>
         </div>
      </section><!-- Page footer-->
@include('admin.includes.footer')
   </div>
@include('admin.includes.global_scripts')
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

<!-- DATETIMEPICKER-->
<script src="{{URL::to('/')}}/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>

<!-- SELECT2-->
<script src="{{URL::to('/')}}/vendor/select2/dist/js/select2.full.js"></script>
@include('admin.includes.global_app_scripts')

<script  href="{{URL::to('/')}}/vendor/datatables.net/js/jquery.dataTables.js"></script>
<script  href="{{URL::to('/')}}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="//cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js"></script>
<script>
    var oTable;
    $(function (){
        oTable = $('#statistics').DataTable({
            "dom": 'frt<"bottom">',
            paginate: true,
            "searching": false,
            "pagingType": "numbers",
            "serverSide": true,
            pageLength: -1,
            "language": {
                "emptyTable": "Нет данных",
                "zeroRecords": "Нет данных",
                'sSearch': 'Поиск '
            },
            "aaSorting": [[ 0, "asc" ]],
            // scrollX:        true,
            // scrollCollapse: true,
            // fixedColumns:   true,

            ajax: function(data,callback, settings){
                data.filter__date = $('#filter__date').val();
                data.filter__user = $('#filter__user').val();
                data._token = $('input[name=_token]').val();
                $.ajax({
                    url: '{{URL::to('/')}}/admin/daystatistics/getJsonDayStatistics',
                    type: 'POST',
                    data: data,
                    success:function (data){
                        if(data.error){
                            toastr.error(data.error);
                        } else {
                            __total_data = data.data_totals;
                            callback(data);
                        }
                    }
                });
            },
            columns: [
                // 1-5
                {data: 'name',
                    render:function (data, type, full, meta){
                        return `<a href="javascript:;" onclick="init_modal_user_info('${data.id}','${data.date}')">${data.name}</a>`;
                    }
                },
                {data: 'work_start',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'work_finish',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'islate',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'break_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
// 6-9
                {data: 'dinner_start',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'dinner_finish',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'islatedinner',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'free_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'vvp',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
            ],
            columnDefs:[
                { orderable: false, targets: [1,2,3,4,5,6,7,8,9] },
            ],

        }).on('draw',function (){
        });

        $.fn.datepicker.dates['ru'] = {
            days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
            daysShort: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
            daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
            months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
            today: "Сегодня",
            clear: "Очистить",
            format: "dd.mm.yyyy",
            titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
            weekStart: 1
        };

        $('.input-date').datepicker({
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-crosshairs',
                clear: 'fa fa-trash'
            },
            locale: 'ru',
            format: "dd.mm.yyyy",
            // todayBtn: "linked",
            clearBtn: false,
            language: "ru",
            endDate: "1d",
            autoclose: true,
        }).on('changeDate', function(e) {
            // `e` here contains the extra attributes
            oTable.draw();
        });

        $('#filter__user').select2({
            theme: 'bootstrap4'
        }).on('change',function (){
            oTable.draw();
        });
    });
    /* Start script for user info modal */
    function init_modal_user_info(user_id, date){
        $.post('{{route('statistics.user.modal.info')}}', {user_id:user_id, date:date, _token: $('input[name=_token]').val()}, function (result){
            $('#statistics_modal__user_info').remove();
            $('body').append(result);
            $('#statistics_modal__user_info').modal('show');
        });
    }
    /* End script for user info modal */
</script>
</body>

</html>
