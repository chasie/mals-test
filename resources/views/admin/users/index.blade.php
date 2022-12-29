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

    <!-- DATETIMEPICKER-->
    <link rel="stylesheet" href="{{URL::to('/')}}/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.css">
    @include('admin.includes.global_app_styles')
</head>

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
                @if(\Illuminate\Support\Facades\Auth::user()->isSAdmin() || Auth::user()->isAdmin())
                <a href="javascript:;" onclick="init_modal();" class="btn btn-outline-primary ml-auto">Добавить</a>
                    @endif
            </div>
             <div class="row">
                 <div class="col-12">
                     <div class="card card-default">
                         <div class="card-body">
                             <!-- START table-responsive-->
                             <div class="table-responsive" >
                                 <table class="table table-hover" id="users" style="width: 100%; min-width: 500px;">
                                     <thead>
                                     <tr>
                                         <th>Имя</th>
                                         <th>Роль</th>
                                         <th>Дата рождения</th>
                                         <th></th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                     </tbody>
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
@include('admin.includes.global_app_scripts')
<!-- Modal -->
<div class="modal " id="alert"
     aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelTitleId">Уверены?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary " id="confirm_btn">Подтвердить</button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net/js/jquery.dataTables.js">
<link rel="stylesheet" href="{{URL::to('/')}}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js">
<script>
    var oTable;
    $(function (){
        $(document).on('click','.delete_btn',function (e){
            e.preventDefault();
            $('#confirm_btn').attr('data-id', $(this).attr('data-id'));
            $('#alert').modal();
        });

        $('#confirm_btn').on('click',function (e){
            e.preventDefault();

            var data = {
                _token: $('input[name=_token]').val(),
            };

            $.ajax({
                url: '{{URL::to('/')}}' + '/admin/users/'+$(this).attr('data-id')+'/delete',
                method: 'post',
                data: data,
                success: function (response, status, xhr, $form) {
                    if (response.error) {
                        toastr.error(response.error);
                    } else {
                        $('#alert').modal('hide');
                        oTable.draw();
                    }
                }
            });
        });
        let ausergroup_id = '{{\Illuminate\Support\Facades\Auth::user()->group_id}}';
        oTable = $('#users').DataTable({
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
            "aaSorting": [[ 0, "asc" ]],

            ajax: function(data,callback, settings){
                data.filter__search = $('input[type="search"]').val();
                data._token = $('input[name=_token]').val();
                $.ajax({
                    url: '{{URL::to('/')}}/admin/users/getJson',
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
                {data: 'name',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'group_id',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data: 'birthday',
                    render:function (data, type, full, meta){
                        return `${data}`;
                    }
                },
                {data:"actions",
                    responsivePriority:-1
                }],
                columnDefs:[
                    { orderable: false, targets: [2] },
                    {
                        targets:-1,
                        title:"",
                        orderable:!1,
                    render:function(data,a,e,l){
                        let text = ``;
                        let display_class = '';
                         if (ausergroup_id == 4 || (ausergroup_id == 3 && data.group_id == 1)){
                            display_class = 'd-none';
                        }

                        text = `
                            <div style="white-space: nowrap;" class="text-right ${display_class}">
                                <a title="Редактировать" href="javascript:;" class="btn btn-info btn-xs mr-2" onclick="init_edit_modal(${data.id});"><i class="fas fa-edit"></i></a>
                                <a title="Удалить"  class="btn btn-danger btn-xs delete_btn mr-2" data-id="${data.id}" ><i class="fas fa-trash"></i></a>`;
                        if(data.group_id == 1 || data.group_id == 3 || data.group_id == 4){
                            text += `<a title="Поменять пароль"  class="btn btn-info btn-xs changepin_btn" data-id="${data.id}" onclick="init_pass_modal(${data.id})">Поменять пароль</a>`;
                        } else {
                            text += `<a title="Поменять пин код"  class="btn btn-info btn-xs changepin_btn" data-id="${data.id}" onclick="init_pin_modal(${data.id})">Поменять пин код</a>`;
                        }
                        if(data.group_id == 2){
                            text += `<a title="Завершить смену"  class="btn btn-warning btn-xs ml-2" data-id="${data.id}" onclick="close_shift(${data.id})">Завершить смену</a>`;
                        }
                            text += `</div>`;
                        return text;
                            }
                    }
            ],

        }).on('draw',function (){});

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


    });

    function init_modal(){
        $.post('{{route('admin.users.modal.add')}}', { _token: $('input[name=_token]').val()}, function (result){
            $('#users__modal').remove();
            $('body').append(result);
            $('#users__modal').modal('show');
        });
    }
    function init_edit_modal(id){
        $.post('{{route('admin.users.modal.edit')}}', {id:id, _token: $('input[name=_token]').val()}, function (result){
            $('#users__modal').remove();
            $('body').append(result);
            $('#users__modal').modal('show');
        });
    }
    function init_pin_modal(id){
        $.post('{{route('admin.users.modal.pin')}}', {id:id, _token: $('input[name=_token]').val()}, function (result){
            $('#users__pin_modal').remove();
            $('#users__pass_modal').remove();
            $('body').append(result);
            $('#users__pin_modal').modal('show');
        });
    }
    function init_pass_modal(id){
        $.post('{{route('admin.users.modal.pass')}}', {id:id, _token: $('input[name=_token]').val()}, function (result){
            $('#users__pin_modal').remove();
            $('#users__pass_modal').remove();
            $('body').append(result);
            $('#users__pass_modal').modal('show');
        });
    }
    function close_shift(id){
        var data = {
            user_id:id,
            _token: $('input[name=_token]').val(),
        };

        $.ajax({
            url: '{{route('user.shift.close')}}',
            method: 'post',
            data: data,
            success: function (response, status, xhr, $form) {
                if (response.error) {
                    toastr.error(response.error);
                } else {
                    toastr.success('Смена закрыта!');
                }
            }
        });
    }
</script>
</body>

</html>
