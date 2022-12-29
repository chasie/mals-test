<!-- Modal -->
<div class="modal " id="duties__modal"
     aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelTitleId">Обязанность</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="text-bold  col-md-4 col-4 col-form-label text-right" for="name">Название<span>*</span></label>
                        <div class=" col-md-8 col-8">
                            <input class="form-control" id="name" type="text" placeholder="" value="@if (isset($duty) && $duty->name!=null){{$duty->name}}@endif" autocomplete="off">
                        </div>
                    </div>
                    @if (isset($duty))
                        <input type="hidden" id="id" value="{{$duty->id}}">
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary " id="duties__save">Сохранить</button>
            </div>
        </div>
    </div>
    <script>
        $('#duties__save').on('click', function (e) {
            e.preventDefault();

            var data = {
                name: $('#name').val(),
                parent_id:'{{$parent_id}}',
                _token: $('input[name=_token]').val(),
            };
            let id = $('#id').val();
            if (id != undefined){
                data.id= id;
            }

            $.ajax({
                url: '{{URL::to('/')}}' + '/admin/duties/store',
                method: 'post',
                data: data,
                success: function (response, status, xhr, $form) {
                    if (response.error) {
                        toastr.error(response.error);
                    } else {
                        $('#duties__modal').modal('hide');
                        toastr.success('Данные сохранены!');
                        location.reload();
                    }
                }
            });
        });
</script>
</div>
