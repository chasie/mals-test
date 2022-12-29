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
                                     @foreach($columns_select as $key=>$row)
                                         <option value="{{$key}}">{{$row}}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="card-body" id="card_body">
                             <!-- START table-responsive-->
                            <!-- END table-responsive-->
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
    var __lscols_arr=[];
    var first_load = 0;
    $.fn.dataTable.Api.register( 'column().title()', function () {
        var colheader = this.header();
        return $(colheader).text().trim();
    } );
    $(function (){

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
            // oTable.draw();
            init_table();
        });

        $('#filter__user').select2({
            theme: 'bootstrap4'
        }).on('change',function (){
            // oTable.draw();
            init_table();
        });

        seclect_cols = $('#filter__columns').select2({
            theme: 'bootstrap4'
        }).on('select2:select',function (e){
            var data = e.params.data;
            __lscols_arr.push(data.id);
            localStorage.setItem('lscols', __lscols_arr.join());
            init_table();
        }).on('select2:unselect',function (e){
            var data = e.params.data;
            let i = __lscols_arr.indexOf(data.id);
            if(i >= 0) {
                __lscols_arr.splice(i,1);
            }
            localStorage.setItem('lscols', __lscols_arr.join());
            init_table();
        });
        if  (first_load == 0){
                __lscols_arr = [];
                let lscols = localStorage.getItem('lscols');
                if (lscols != undefined && lscols != ''){
                    __lscols_arr = lscols.split(',');
                }
                if  (__lscols_arr.length){
                    seclect_cols.val(__lscols_arr).trigger('change');
                }
            first_load =1;
        }
        init_table();
    });
    function init_table(){
        let container = $('#card_body');
        container.html('');
        let data ={
            filter__date_from: $('#filter__date_from').val(),
            filter__date_to: $('#filter__date_to').val(),
            filter__user:  $('#filter__user').val(),
            filter__cols:  $('#filter__columns').val(),
            _token: $('input[name=_token]').val(),
        };
        $.ajax({
            type: 'POST',
            url: '{{URL::to('/')}}/admin/statistics/initTable',
            data: data,
            success: function(html) {
                container.append(html);
                    oTable = $('#statistics').DataTable({
                        dom: 'frt<"bottom">',
                        "searching": false,
                        "language": {
                            "emptyTable": "Нет данных",
                            "zeroRecords": "Нет данных",
                            'sSearch': 'Поиск '
                        },
                        // scrollX:        true,
                        scrollCollapse: true,
                        // fixedColumns: true,
                        "aaSorting": [[0, "asc"]],
                    });
                    // setTimeout(function (){
                    //    $('thead th:first-child').trigger('click');
                    // },300);
                }
        });
    }
</script>
</body>

</html>
