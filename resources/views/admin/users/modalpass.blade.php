<!-- Modal -->
<div class="modal " id="users__pass_modal"
     aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelTitleId">Изменение пароля</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="text-bold col-md-4 col-4 col-form-label text-right" for="new_password">Новый пароль<span>*</span></label>
                        <div class="col-md-8 col-8">
                            <input class="form-control " id="new_password" type="text" value="" autocomplete="off">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary " id="users__pass_save">Сохранить</button>
            </div>
        </div>
    </div>
    <script>
        $('#users__pass_save').on('click', function (e) {
            e.preventDefault();

            var data = {
                'id':'{{$user->id}}',
                _token: $('input[name=_token]').val(),
            };

let password = $('#new_password').val();
if (password != undefined){
    data.password= password;
}
            $.ajax({
                url: '{{URL::to('/')}}' + '/admin/users/changepass',
                method: 'post',
                data: data,
                success: function (response, status, xhr, $form) {
                    if (response.error) {
                        toastr.error(response.error);
                    } else {
                        $('#users__pin_modal').modal('hide');
                        toastr.success('Данные сохранены!');

                        oTable.draw();
                    }
                }
            });
        });
    </script>
</div>
