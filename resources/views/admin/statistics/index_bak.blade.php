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
                             <div class="mr-3" style="width: 400px;">
                                 <div class="input-group input-group-sm input-daterange">
                                     <input type="text" class="form-control form-control-sm" id="filter__date_from" value="{{\Carbon\Carbon::now()->subMonth()->format('d.m.Y')}}">
                                     <div class="input-group-addon" style="margin-right: 5px;margin-left: 5px;padding-top: 5px;">до</div>
                                     <input type="text" class="form-control form-control-sm" id="filter__date_to" value="{{\Carbon\Carbon::now()->format('d.m.Y')}}">
                                 </div>
                             </div>

                             <div class="mr-3" style="width: 300px;">
                                 <select class="form-control form-control-sm" id="filter__user" multiple="multiple" data-placeholder="Сотрудники отобразить">
                                         <option></option>
                                     @foreach($users as $row)
                                         <option value="{{$row->id}}">{{$row->name}}</option>
                                     @endforeach
                                 </select>
                             </div>
                             <div style="width: 600px;">
                                 <select class="form-control form-control-sm" id="filter__columns" multiple="multiple" data-placeholder="Скрыть колонки">
                                         <option></option>
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
                                         <th class="align-top">Кол-во<br> отработанных<br> смен</th>
                                         <th class="align-top">Кол-во<br> собранных<br> заказов</th>
                                         <th class="align-top">Среднее<br> кол-во<br> собранных<br> заказов<br> в день</th>
                                         <th class="align-top">Сумма<br> собранных<br> заказов</th>
{{--6-10--}}
                                         <th class="align-top">Кол-во<br> проверенных<br> заказов</th>
                                         <th class="align-top">Среднее<br> кол-во<br> проверенных<br> заказов<br> в день</th>
                                         <th class="align-top">Сумма<br> проверенных<br> заказов</th>
                                         <th class="align-top">Кол-во<br> заказов,<br> кот.<br> помогал<br> собирать</th>
                                         <th class="align-top">Среднее<br> кол-во<br> заказов,<br> кот.<br> помогал<br> собирать<br> в день</th>
{{--11-15--}}
                                         <th class="align-top">Сумма<br> заказов,<br> кот.<br> помогал<br> собирать</th>
                                         <th class="align-top">ВВП на<br> сотрудника</th>
                                         <th class="align-top">Время на<br> сборку<br> заказов</th>
                                         <th class="align-top">Время на<br> сборку<br> 1 заказа</th>
                                         <th class="align-top">Время на<br> проверку<br> заказов</th>
{{--16-20--}}
                                         <th class="align-top">Время на<br> проверку<br> 1 заказа</th>
                                         <th class="align-top">Время на<br> помощь<br> в сборке<br> заказов</th>
                                         <th class="align-top">Время на<br> перерывы</th>
                                         <th class="align-top">Среднее<br> время<br> перерывов<br> в день</th>
                                         <th class="align-top">Время<br> простоя</th>
{{--21-25--}}
                                         <th class="align-top">Среднее<br> время<br> простоя<br> в день</th>
                                         <th class="align-top">Время<br> на доставку</th>
                                         <th class="align-top">Среднее<br> время<br> доставки<br> в день</th>
                                         <th class="align-top">Время<br> на поручения<br> руководителя</th>
                                         <th class="align-top">Среднее<br> время<br> на поручения<br> руководителя<br> в день</th>
{{--26-27--}}
                                         <th class="align-top">Время<br> на рабочие<br> обязанности</th>
                                         <th class="align-top">Среднее<br> время<br> на рабочие<br> обязанности<br> в день</th>
                                     </tr>
                                     </thead>
{{--                                     <tbody>--}}
{{--                                     </tbody>--}}
                                     <tfoot>
                                     <tr>
                                         <th id="name">Общее</th>
                                         <th id="shift"></th>
                                         <th id="order_complete_cnt"></th>
                                         <th id="order_complete_cnt_per_day"></th>
                                         <th id="order_complete_sum"></th>

                                         <th id="order_checked_cnt"></th>
                                         <th id="order_checked_cnt_per_day"></th>
                                         <th id="order_checked_sum"></th>
                                         <th id="order_helped_cnt"></th>
                                         <th id="order_helped_cnt_per_day"></th>

                                         <th id="order_helped_sum"></th>
                                         <th id="vvp"></th>
                                         <th id="order_complete_time"></th>
                                         <th id="order_complete_time_per_order"></th>
                                         <th id="order_checked_time"></th>

                                         <th id="order_checked_time_per_order"></th>
                                         <th id="order_helped_time"></th>
                                         <th id="break_time"></th>
                                         <th id="break_time_per_day"></th>
                                         <th id="free_time"></th>

                                         <th id="free_time_per_day"></th>
                                         <th id="delivery_time"></th>
                                         <th id="delivery_time_per_day"></th>
                                         <th id="manager_task_time"></th>
                                         <th id="manager_task_time_per_day"></th>

                                         <th id="duty_time"></th>
                                         <th id="duty_time_per_day"></th>
                                     </tr>
                                     </tfoot>
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
    var seclect_cols;
    var __total_data= {};
    var __lscols_arr=[];
    var first_load = 0;
    function updateBottomrow(data){
        let tfoot = $('.dataTables_scrollFoot tfoot');
        tfoot.find('th:not(#name)').text('0');
        if (Object.size(__total_data)>0){
            $.each(data,function (k,v){
                tfoot.find('th[id="'+ k +'"]').text(v);
            });
        }
    }
    $.fn.dataTable.Api.register( 'column().title()', function () {
        var colheader = this.header();
        return $(colheader).text().trim();
    } );
    $(function (){
        seclect_cols = $('#filter__columns').select2({
            theme: 'bootstrap4'
        }).on('select2:select',function (e){
            var data = e.params.data;
            var column = oTable.column( data.id );
            column.visible( false );
            // Get the column API object
            // var column = oTable.column( $(this).attr('data-column') );
            //
            // // Toggle the visibility
            // column.visible( ! column.visible() );
            __lscols_arr.push(data.id);
            localStorage.setItem('lscols', __lscols_arr.join());
        }).on('select2:unselect',function (e){
            var data = e.params.data;
            var column = oTable.column( data.id );
            column.visible( true );

            let i = __lscols_arr.indexOf(data.id);
            if(i >= 0) {
                __lscols_arr.splice(i,1);
            }
            localStorage.setItem('lscols', __lscols_arr.join());
        });

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
            scrollX:        true,
            scrollCollapse: true,
            fixedColumns:   true,

            ajax: function(data,callback, settings){
                data.filter__date_from = $('#filter__date_from').val();
                data.filter__date_to = $('#filter__date_to').val();
                data.filter__user = $('#filter__user').val();
                data._token = $('input[name=_token]').val();
                $.ajax({
                    url: '{{URL::to('/')}}/admin/statistics/getJson',
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
                        return `${data}`;
                    }
                },
                {data: 'shift',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_complete_cnt',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_complete_cnt_per_day',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_complete_sum',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
// 6-10
                {data: 'order_checked_cnt',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_checked_cnt_per_day',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_checked_sum',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_helped_cnt',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_helped_cnt_per_day',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
// 11-15
                {data: 'order_helped_sum',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'vvp',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_complete_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_complete_time_per_order',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_checked_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
// 16-20
                {data: 'order_checked_time_per_order',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_helped_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'break_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'break_time_per_day',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'free_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
// 21-25
                {data: 'free_time_per_day',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'delivery_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'delivery_time_per_day',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'manager_task_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'manager_task_time_per_day',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
// 26-27
                {data: 'duty_time',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'duty_time_per_day',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
            ],

        }).on('draw',function (){
            updateBottomrow(__total_data);

            if  (first_load == 0){
                setTimeout(function (){
                    let cnt_cols = Object.size(__total_data);
                    for (i=0; i<cnt_cols; i++){
                        // console.log(oTable.column( i ).title());
                        // Create a DOM Option and pre-select by default
                        if  (i>0){
                            var newOption = new Option(oTable.column( i ).title(), i, false, false);
                            // Append it to the select
                            seclect_cols.append(newOption);
                        }
                    }
                    __lscols_arr = [];
                    let lscols = localStorage.getItem('lscols');
                    if (lscols != undefined && lscols != ''){
                        __lscols_arr = lscols.split(',');
                    }
                    if  (__lscols_arr.length){
                        seclect_cols.val(__lscols_arr).trigger('change');
                        $.each(__lscols_arr,function (k,v){
                            // Get the column API object
                            var column = oTable.column( v );

                            // Toggle the visibility
                            column.visible( false );
                        });

                    }
                },300);
                first_load =1;
            }

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

        $('.input-daterange').datepicker({
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
            language: "ru"
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
</script>
</body>

</html>
