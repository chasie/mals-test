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
                                 <input type="text" class="form-control form-control-sm" id="filter__order_number" placeholder="Номер заказа...">
                             </div>
                             <div style="width: 600px;">
                                 <select class="form-control form-control-sm" id="filter__columns" multiple="multiple" data-placeholder="Скрыть колонки">
                                         <option></option>
                                 </select>
                             </div>
                         </div>
                         <div class="card-body">
                             <!-- START table-responsive-->
                             <div class="table-responsive" >
                                 <table class="table table-striped" id="statistics" style="width: 100%;">
                                     <thead>
                                     <tr>
                                         <th class="align-top">№ Заказа</th>
                                         <th class="align-top">Сумма<br> заказа</th>
                                         <th class="align-top">Дата</th>
                                         <th class="align-top">Время<br> начала<br> сборки</th>
                                         <th class="align-top">Время<br> окончания<br> сборки</th>
{{--6-10--}}
                                         <th class="align-top">Время<br> потраченное<br> на сборку</th>
                                         <th class="align-top">Сборщик</th>
                                         <th class="align-top">Проверяющий</th>
                                         <th class="align-top">Участвовали<br> в сборке</th>
                                         <th class="align-top">Время<br> начала<br> проверки</th>
{{--11-15--}}
                                         <th class="align-top">Время<br> окончания<br> проверки</th>
                                         <th class="align-top">Время<br> потраченное<br> на проверку</th>
                                         <th class="align-top">Проверенно</th>
                                         <th class="align-top">Действия</th>
                                     </tr>
                                     </thead>
{{--                                     <tbody>--}}
{{--                                     </tbody>--}}
                                 </table>
                             </div><!-- END table-responsive-->
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
    var __order_s_cols_arr=[];
    var first_load = 0;
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
            __order_s_cols_arr.push(data.id);
            localStorage.setItem('order_s_cols', __order_s_cols_arr.join());
        }).on('select2:unselect',function (e){
            var data = e.params.data;
            var column = oTable.column( data.id );
            column.visible( true );

            let i = __order_s_cols_arr.indexOf(data.id);
            if(i >= 0) {
                __order_s_cols_arr.splice(i,1);
            }
            localStorage.setItem('order_s_cols', __order_s_cols_arr.join());
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
            "aaSorting": [[ 2, "desc" ]],
            // scrollX:        true,
            scrollCollapse: true,
            // fixedColumns:   true,

            ajax: function(data,callback, settings){
                data.filter__date_from = $('#filter__date_from').val();
                data.filter__date_to = $('#filter__date_to').val();
                data.filter__order_number = $('#filter__order_number').val();
                data._token = $('input[name=_token]').val();
                $.ajax({
                    url: '{{URL::to('/')}}/admin/orderstatistics/getJsonOrderStatistics',
                    type: 'POST',
                    data: data,
                    success:function (data){
                        if(data.error){
                            toastr.error(data.error);
                        } else {
                            callback(data);
                        }
                    }
                });
            },
            columns: [
                // 1-5
                {data: 'number',
                    render:function (data, type, full, meta){
                        return `<a href="javascript:;" onclick="init_modal_info(${data.id})">${data.number}</a>`
                    }
                },
                {data: 'price',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'date',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_complete_time_start',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_complete_time_finish',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
// 6-10
                {data: 'order_complete_time_diff',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_complete_name',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_checked_name',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_helped_name',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_checked_time_start',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
// 11-15
                {data: 'order_checked_time_finish',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'order_checked_time_diff',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'ischecked',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data:"actions",
                    responsivePriority:-1
                }
             ],
            columnDefs:[
                { orderable: false, targets: [3,4,5,9,10,11,12] },
                {
                    targets:-1,
                    title:"",
                    orderable:!1,
                    render:function(data,a,e,l){
                        let text = ``;
                        @if(Auth::user()->isSAdmin() || Auth::user()->isAdmin())
                        text = `
                                <div style="white-space: nowrap;" class="text-right">
                                    <a title="Удалить сборку" href="javascript:;" class="btn btn-danger btn-xs mr-2" onclick="deleteOrderComplete(${data});"><i class="far fa-times-circle"></i> Сборку</a>
                                    <a title="Удалить проверку" href="javascript:;" class="btn btn-danger btn-xs" onclick="deleteOrderChecked(${data});"><i class="far fa-times-circle"></i> Проверку</a>`;
                        text += `</div>`;
                        @endif
                        return text;
                    }
                }
            ],
        }).on('draw',function (){

            if  (first_load == 0){
                setTimeout(function (){
                    let cnt_cols = 13;
                    for (i=0; i<cnt_cols; i++){
                        // console.log(oTable.column( i ).title());
                        // Create a DOM Option and pre-select by default
                        if  (i>0){
                            var newOption = new Option(oTable.column( i ).title(), i, false, false);
                            // Append it to the select
                            seclect_cols.append(newOption);
                        }
                    }
                    __order_s_cols_arr = [];
                    let order_s_cols = localStorage.getItem('order_s_cols');
                    if (order_s_cols != undefined && order_s_cols != ''){
                        __order_s_cols_arr = order_s_cols.split(',');
                    }
                    if  (__order_s_cols_arr.length){
                        seclect_cols.val(__order_s_cols_arr).trigger('change');
                        $.each(__order_s_cols_arr,function (k,v){
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

        $('#filter__order_number').on('change',function (){
            oTable.draw();
        });

    });
    /* Start script for удалить сборку проверку */
    function deleteOrderChecked(order_id){
        var data = {
            order_id:order_id,
            _token: $('input[name=_token]').val(),
        };

        $.ajax({
            url: '{{route('order.forceclose.check')}}',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    toastr.success('Проверка удалена!');
                    oTable.draw();
                }
            }
        });
    }
    function deleteOrderComplete(order_id){
        var data = {
            order_id:order_id,
            _token: $('input[name=_token]').val(),
        };

        $.ajax({
            url: '{{route('order.forceclose.complete')}}',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    toastr.success('Сборка отменена!');
                    oTable.draw();
                }
            }
        });
    }

    /* End script for удалить сборку проверку */
    /* Start script for order info modal */
    function init_modal_info(order_id){
        $.post('{{route('orders.modal.info')}}', {order_id:order_id, _token: $('input[name=_token]').val()}, function (result){
            $('#orders__modal_info').remove();
            $('body').append(result);
            $('#orders__modal_info').modal('show');
        });
    }
    /* End script for order info modal */
</script>
</body>

</html>
