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
<!-- Datatables-->
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css">
    <!-- SELECT2-->
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/select2/dist/css/select2.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css">
    @include('includes.global_app_styles')
</head>
<style>
    .duty__name{
        width: 100%;
        cursor: pointer;
    }
    .duties__open_close .fa-minus{
        display: none;
    }
    .duties__open_close.active .fa-minus{
        display: block;
    }
    .duties__open_close .fa-plus{
        display: block;
    }
    .duties__open_close.active .fa-plus{
        display: none;
    }
    .my_parent{
        display: none;
    }
    .my_parent.active{
        display: block;
    }
</style>
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
                     <div class="d-flex align-items-stretch justify-content-start wd-wide">
                         <button class="btn btn-success btn-xl mr-4" type="button" style="flex-basis: 18%;" onclick="location.href='{{ route('main') }}'"><strong><i class="fas fa-home"></i></strong></button>
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-12">
                     <div class="card card-default">
                         <div class="card-body">
                             @if(count($duties))
                                 @foreach ($duties as $duty)
                                     <div class="w-100">
                                         <div class="d-flex ml-1 p-2 border-bottom align-items-center " style="min-height: 50px;">
                                             <div  class="duty__name">{{$duty['name']}}</div>
                                             <div class="ml-auto">
                                                 @if(!array_key_exists('children', $duty))
                                                     <a title="Начать выполнение" href="javascript:;" class="btn btn-success btn__action " onclick="changeStatusDuty('{{$duty['id']}}',1);"><i class="fas fa-hand-point-up"></i></a>
                                                 @else
                                                     <a href="javascript:;" class="duties__open_close btn__action"><i class="fa fa-plus"></i><i class="fa fa-minus"></i></a>
                                                 @endif
                                             </div>
                                         </div>
                                         @include('duties.template_tree_group', $duty)
                                     </div>
                                 @endforeach
                             @endif
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
    var oTable;
    $(function (){
/*        oTable = $('#duties').DataTable({
            "dom": 'frt<"bottom"p>',
            paginate: true,
            "searching": false,
            "pagingType": "numbers",
            "serverSide": true,
            pageLength: 20,
            "language": {
                "emptyTable": "Нет данных",
                "zeroRecords": "Нет данных",
                'sSearch': 'Поиск '
            },
            "aaSorting": [[ 0, "asc" ]],


            ajax: function(data,callback, settings){
                data._token = $('input[name=_token]').val();
                $.ajax({
                    url: '{{URL::to('/')}}/duties/getJsonDuties',
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
                {data: 'id',
                    render: function (data, type, full, meta) {
                        return `${data}`;
                    }
                },
                {data: 'name',
                    render: function (data, type, full, meta) {
                        return `${data}`;
                    }
                },
                {data:"actions",
                    responsivePriority:-1
                }],
            columnDefs:[
                { orderable: false, targets: '_all' },
                {
                    targets:-1,
                    title:"",
                    orderable:!1,
                    render:function(data,a,e,l){

                        return `
                            <div style="white-space: nowrap;" class="text-right">
                                <a title="Начать выполнение" href="javascript:;" class="btn btn-success mr-2 " onclick="changeStatusDuty(${data},1);"><i class="fas fa-hand-point-up"></i></a>
                            </div>
                                `;
                    }
                }
            ],
            "createdRow": function( row, data, dataIndex ) {
                if ( data['row_class'] != "" ) {
                    $(row).addClass(data['row_class']);
                }
            }

        }).on('draw',function (){
            $('th.sorting_desc').removeClass('sorting_desc').addClass('sorting_disabled');
        });*/



        $(document).on('click','.duty__name',function (e){
            e.preventDefault();
           $(this).parent().find('.btn__action').trigger('click');
        });

        $(document).on('click','.duties__open_close',function (e){
            e.preventDefault();
           $(this).toggleClass('active');
           $(this).parent().parent().parent().find('.my_parent').eq(0).toggleClass('active');
        });

    });

</script>
</body>

</html>
