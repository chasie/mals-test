<!-- Modal -->
<div class="modal " id="orders__modal"
     aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                @if(isset($order))
                    <h4 class="modal-title" id="modelTitleId">Заказ #{{$order->number}}</h4>
                @else
                    <h4 class="modal-title" id="modelTitleId">Новый заказ</h4>
                    @endif

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="text-bold  col-md-4 col-4 col-form-label text-right" for="number">Номер<span>*</span></label>
                        <div class=" col-md-8 col-8">
                            <input class="form-control" id="number" type="number" placeholder="" value="@if (isset($order) && $order->number!=null){{$order->number}}@endif" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="text-bold col-md-4 col-4 col-form-label text-right" for="price">Сумма заказа<span>*</span></label>
                        <div class="col-md-8 col-8">
                            <input class="form-control onlyDigits" id="price" type="text" value="@if (isset($order) && $order->price!=null){{$order->price}}@endif" autocomplete="off">
                        </div>
                    </div>
                    @if (isset($order))
                        <input type="hidden" id="id" value="{{$order->id}}">
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary " id="orders__save_confirm">Сохранить</button>
            </div>
        </div>
    </div>
    <script>
        $('#orders__save').on('click', function (e) {
            e.preventDefault();
            if ($('#price').val() == '' || $('#price').val()!=$('#price_confirm').val()){
                toastr.error('Сумма заказа не совпадает с введенной ранее');return;
            } else {
                $('#orders__modal_confirm').modal('hide');
            }
            var data = {
                number: $('#number').val(),
                price: $('#price').val(),
                _token: $('input[name=_token]').val(),
            };
let id = $('#id').val();
if (id != undefined){
    data.id= id;
}

            $.ajax({
                url: '{{URL::to('/')}}' + '/orders/store',
                method: 'post',
                data: data,
                success: function (response, status, xhr, $form) {
                    if (response.error) {
                        toastr.error(response.error);
                    } else {
                        $('#orders__modal').modal('hide');
                        toastr.success('Данные сохранены!');

                        oTable.draw();
                    }
                }
            });
        });

        $('#orders__save_confirm').on('click', function (e) {
            e.preventDefault();
            if ($('#price').val() == '' || $('#number').val()==''){
                toastr.error('Заполните необходимые поля');
                return;
            }
           $('#orders__modal_confirm').modal();
        });
</script>
</div>
<div class="modal " id="orders__modal_confirm"
     aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelTitleId">Подтвердите данные</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group row">
                        <label class="text-bold col-md-4 col-4 col-form-label text-right" for="price_confirm">Сумма заказа<span>*</span></label>
                        <div class="col-md-8 col-8">
                            <input class="form-control onlyDigits" id="price_confirm" type="text" autocomplete="off">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary " id="orders__save">Сохранить</button>
            </div>
        </div>
    </div>
</div>
