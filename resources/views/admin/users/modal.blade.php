<!-- Modal -->
<div class="modal " id="users__modal"
     aria-hidden="true">
    <style>
        .modal-dialog #email_block,
        .modal-dialog #password_block{
            display: none;
        }
        .modal-dialog.admin #email_block{
            display: flex;
        }
        .modal-dialog.admin #password_block,
        .modal-dialog.worker #password_block{
            display: flex;
        }
        .modal-dialog #pass_pin,
        .modal-dialog #pass_pass{
            display: none;
        }
        .modal-dialog.admin #pass_pass{
            display: inline-block;
        }
        .modal-dialog.worker #pass_pin{
            display: inline-block;
        }
    </style>
    <div class="modal-dialog @if (isset($user) && ($user->group_id==1 || $user->group_id==3 || $user->group_id==4)) admin @elseif(isset($user) && $user->group_id==2) worker @endif" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelTitleId">Пользователь</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="text-bold  col-md-4 col-4 col-form-label text-right" for="name">ФИО<span>*</span></label>
                        <div class=" col-md-8 col-8">
                            <input class="form-control" id="name" type="text" placeholder="" value="@if (isset($user) && $user->name!=null){{$user->name}}@endif" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="text-bold col-md-4 col-4 col-form-label text-right" for="birthday">День рождения</label>
                        <div class="col-md-8 col-8">
                            <input class="form-control datepicker" id="birthday" type="text" value="@if (isset($user) && $user->birthday!=null){{\Carbon\Carbon::parse($user->birthday)->format('d.m.Y')}}@endif" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="text-bold col-md-4 col-4 col-form-label text-right" for="group_id">Роль<span>*</span></label>
                        <div class="col-md-8 col-8">
                            <select class="form-control" id="group_id">
                                <option value=""></option>
                                <option value="2" @if (isset($user) && $user->group_id==2) selected @endif>Сотрудник</option>
                                @if(\Illuminate\Support\Facades\Auth::user()->isSAdmin())
                                <option value="1" @if (isset($user) && $user->group_id==1) selected @endif>Системный администратор</option>
                                @endif
                                <option value="3" @if (isset($user) && $user->group_id==3) selected @endif>Администратор</option>
                                <option value="4" @if (isset($user) && $user->group_id==4) selected @endif>Менеджер</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row" id="email_block">
                        <label class="text-bold col-md-4 col-4 col-form-label text-right" for="email">Email<span>*</span></label>
                        <div class="col-md-8 col-8">
                            <input class="form-control" id="email" type="text" value="@if (isset($user) && $user->email!=null){{$user->email}}@endif" autocomplete="off">
                        </div>
                    </div>
                    @if(!isset($user))
                    <div class="form-group row" id="password_block">
                        <label class="text-bold col-md-4 col-4 col-form-label text-right" for="password"><span id="pass_pin">Пин-код</span><span id="pass_pass">Пароль</span><span>*</span></label>
                        <div class="col-md-8 col-8">
                            <input class="form-control onlyDigits" id="password" type="text" value="" autocomplete="off">
                        </div>
                    </div>
                    @endif
                    @if (isset($user))
                        <input type="hidden" id="id" value="{{$user->id}}">
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary " id="users__save">Сохранить</button>
            </div>
        </div>
    </div>
    <script>
        $('.datepicker').datepicker({
            orientation: 'bottom',
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
            todayBtn: "linked",
            clearBtn: true,
            language: "ru"
        });

        $('#users__save').on('click', function (e) {
            e.preventDefault();

            var data = {
                name: $('#name').val(),
                birthday: $('#birthday').val(),
                group_id: $('#group_id').val(),
                email: $('#email').val(),
                _token: $('input[name=_token]').val(),
            };
let id = $('#id').val();
if (id != undefined){
    data.id= id;
}

let password = $('#password').val();
if (password != undefined){
    data.password= password;
}
            $.ajax({
                url: '{{URL::to('/')}}' + '/admin/users/store',
                method: 'post',
                data: data,
                success: function (response, status, xhr, $form) {
                    if (response.error) {
                        toastr.error(response.error);
                    } else {
                        $('#users__modal').modal('hide');
                        toastr.success('Данные сохранены!');

                        oTable.draw();
                    }
                }
            });
        });

        $('#group_id').on('change',function (){
            let val =$(this).val();
            let m = $('#users__modal .modal-dialog');
            m.removeClass('admin').removeClass('worker');
            $('#password').removeClass('onlyDigits');
            if(val == 1 || val == 3 || val == 4){
                m.addClass('admin');
                if($('#password').hasClass('onlyDigits')){
                    $('#password').removeClass('onlyDigits');
                }
            } else if (val == 2) {
                m.addClass('worker');
                if(!$('#password').hasClass('onlyDigits')){
                    $('#password').addClass('onlyDigits');
                }
            }
        });
</script>
</div>
