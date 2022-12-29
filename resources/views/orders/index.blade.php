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
                         @if(Auth::user()->isSAdmin() || Auth::user()->isAdmin() || Auth::user()->isManager())
                             <button class="btn btn-success btn-xl mr-4" type="button" style="flex-basis: 18%;" onclick="location.href='{{ route('admin.users') }}'"><strong><i class="fas fa-home"></i></strong></button>
                         @else
                             <button class="btn btn-success btn-xl mr-4" type="button" style="flex-basis: 18%;" onclick="location.href='{{ route('main') }}'"><strong><i class="fas fa-home"></i></strong></button>
                             @endif

                         <button class="btn btn-success btn-xl mr-4" type="button"  style="flex-basis: 18%;" onclick="init_modal();"><strong>Новый заказ</strong></button>
                     </div>
                 </div>
             </div>
             <div id="today_statistic_table">
                 <div class="row">
                     <div class="col-12">
                         <div class="card card-default">
                             <div class="card-body">
                                 <div class="table-responsive">
                                     <table class="table table-hover w-100" id="orders">
                                         <thead>
                                         <tr>
                                             <th width="70"> ID </th>
                                             <th> № </th>
                                             <th> Сумма </th>
                                             <th> Создал </th>
                                             <th> Статус<br>текущий </th>
                                             <th width="150"></th>
                                         </tr>
                                         </thead>
                                         <tbody>
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

<div class="modal " id="orders__modal_delete"
     aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">

                    <h4 class="modal-title" id="modelTitleId">Удалить?</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary " id="orders__delete_confirm">Подтвердить</button>
            </div>
        </div>
    </div>
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
        oTable = $('#orders').DataTable({
            "dom": 'frt<"bottom"p>',
            paginate: true,
            "searching": true,
            "pagingType": "numbers",
            "serverSide": true,
            pageLength: 20,
            "language": {
                "emptyTable": "Нет данных",
                "zeroRecords": "Нет данных",
                'sSearch': 'Поиск '
            },
            "aaSorting": [[ 0, "desc" ]],


            ajax: function(data,callback, settings){
                data._token = $('input[name=_token]').val();
                $.ajax({
                    url: '{{URL::to('/')}}/orders/getJson',
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
                {data: 'number',
                    render: function (data, type, full, meta) {
                        return `${data.number}`;
                    }
                },

                {data: 'price',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'created_user',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {
                    data: 'status_current',
                    render: function (data, type, full, meta) {
                        let mark = '';
                        let style = '';
                        if (data['0'] && data['0'].length>0){
                            mark += `<span class="badge badge-success mr-1">${data['0']}</span>`;
                        }
                        if (data['1'] && data['1'].length>0){
                            mark += `<span class="badge badge-success mr-1">${data['1']}</span>`;
                        }
                        if (data['2'] && data['2'].length>0){
                            mark += `<span class="badge badge-success mr-1">${data['2']}</span>`;
                        }
                        return mark;
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
                        let text = `
                            <div style="white-space: nowrap;" class="text-right">
                                <a title="Редактировать" href="javascript:;" class="btn btn-success mr-2 " onclick="init_edit_modal(${data});"><i class="fas fa-edit"></i></a>
                               `;
                        @if(\Illuminate\Support\Facades\Auth::user()->isWorker())
                            text += ` <a title="Перейти" href="javascript:;" class="btn btn-info" onclick="location.href='{{route('order.view','')}}/${data}'"><i class="fas fa-eye"></i></a>`;
                        @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->isSAdmin() || Illuminate\Support\Facades\Auth::user()->isAdmin())
                            text += `<a title="Удалить"  class="btn btn-danger delete_btn ml-2" data-id="${data}" ><i class="fas fa-trash"></i></a>`;
                        @endif

                            text += `</div>`;
                                return text;
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
        });



        let intervalUpdate = function (){
            setInterval(function (){
                oTable.draw();
            }, 30000);
        }
        intervalUpdate();
    });

    function init_modal_info(order_id){
        $.post('{{route('orders.modal.info')}}', {order_id:order_id, _token: $('input[name=_token]').val()}, function (result){
            $('#orders__modal_info').remove();
            $('body').append(result);
            $('#orders__modal_info').modal('show');
        });
    }
    function init_modal(){
        $.post('{{route('orders.modal.add')}}', { _token: $('input[name=_token]').val()}, function (result){
            $('#orders__modal').remove();
            $('#orders__modal_confirm').remove();
            $('body').append(result);
            $('#orders__modal').modal('show');
        });
    }
    function init_edit_modal(id){
        $.post('{{route('orders.modal.edit')}}', {id:id, _token: $('input[name=_token]').val()}, function (result){
            $('#orders__modal').remove();
            $('#orders__modal_confirm').remove();
            $('body').append(result);
            $('#orders__modal').modal('show');
        });
    }
    $(document).on('click','.delete_btn',function (){
        $('#orders__delete_confirm').attr('data-id',$(this).attr('data-id'));
        $('#orders__modal_delete').modal();
    });
    $('#orders__delete_confirm').on('click',function (){
        var data = {
            'id':$(this).attr('data-id'),
            _token: $('input[name=_token]').val(),
        };

        $.ajax({
            url: '{{ route('orders.delete') }}',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    $('#orders__modal_delete').modal('hide');
                    toastr.success('Данные удалены!');

                    oTable.draw();
                }
            }
        });
    })

</script>
</body>

</html>
