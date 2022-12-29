<div class="modal " id="statistics_modal__user_info"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelTitleId">Сотрудник {{$user->name}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover border-0">
                    <tbody>
                    @if(count($timing_arr))
                        @foreach($timing_arr as $row)
                            <tr class="border-0">
                                <td class="border-0">{{$row['time']}}</td>
                                <td class="border-0">{{$row['text']}}</td>
                            </tr>
                            @endforeach
                    @else
                        <tr>
                            <td colspan="2">Нет данных</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

