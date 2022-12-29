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
             <div class="row">
                 <div class="col-12">
                     <div class="card card-default">
                         <div class="card-body">
                             <!-- START table-responsive-->
                                 <table class="table " id="statistics" style="width:100%; max-width: 700px!important;">
                                     <tbody>
                                     </tbody>
                                 </table>
                         </div>
                     </div>
                     <div class="card card-default">
                         <div class="card-body">
                             <table class="table mt-5" id="statistics2" style="width:100%; max-width: 700px!important;">
                                 <thead>
                                 <tr>
                                     <th>Сотрудник</th>
                                     <th>Статус</th>
                                 </tr>
                                 </thead>
                                 {{--                                     <tbody>--}}
                                 {{--                                     </tbody>--}}
                             </table>
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
    var oTable2;
    $(function (){

        oTable = $('#statistics').DataTable({
            "dom": 'frt<"bottom">',
            paginate: false,
            "searching": false,
            "pagingType": "numbers",
            "serverSide": true,
            pageLength: -1,
            "language": {
                "emptyTable": "Нет данных",
                "zeroRecords": "Нет данных",
                'sSearch': 'Поиск '
            },


            ajax: function(data,callback, settings){
                data.filter__date_from = $('#filter__date_from').val();
                data.filter__date_to = $('#filter__date_to').val();
                data.filter__user = $('#filter__user').val();
                data._token = $('input[name=_token]').val();
                $.ajax({
                    url: '{{URL::to('/')}}/admin/realtimestatistics/getJsonRealTimeStatistics',
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
            columns:[
                ['','','',''],
                ['','','',''],
                ['','','',''],
                ['','','',''],
            ],
            columnDefs:[
                { orderable: false, targets: 'All' },
            ],
            "drawCallback": function( settings ) {
                $("#statistics thead").remove();
            }
        }).on('draw',function (){
        });

        oTable2 = $('#statistics2').DataTable({
            "dom": 'frt<"bottom">',
            paginate: false,
            "searching": false,
            "pagingType": "numbers",
            "serverSide": false,
            pageLength: -1,
            "language": {
                "emptyTable": "Нет данных",
                "zeroRecords": "Нет данных",
                'sSearch': 'Поиск '
            },

            // "ordering": false,
            ajax: function(data,callback, settings){
                data._token = $('input[name=_token]').val();
                $.ajax({
                    url: '{{URL::to('/')}}/admin/realtimestatistics/getJsonRealTimeStatistics2',
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
            columns:[
                {data: 'name',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'status',
                    render:function (data, type, full, meta){
                        return `${data.text}`;
                    }
                },
            ],
            columnDefs:[
                // { orderable: false, targets: [0] },
            ],
            "drawCallback": function( settings ) {
                $("#statistics thead th:before").remove();
                $("#statistics thead th:after").remove();
            }
        }).on('draw',function (){
        });
        setTimeout(function cycle() {
            oTable.draw();
            oTable2.draw();
            setTimeout(cycle, 30000)
        }, 30000);
    });
</script>
</body>

</html>
