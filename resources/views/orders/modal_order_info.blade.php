<div class="modal " id="orders__modal_info"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelTitleId">Заказ #{{$order->number}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list-group mb-0">
                    <a class="list-group-item list-group-item-action" href="javascript:;">
                        <em class="fa-fw fas fa-calendar-alt mr-2"></em>
                        Дата создания: <strong>{{\Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i')}}</strong>
                    </a>
                    <a class="list-group-item list-group-item-action" href="javascript:;">
                        <em class="fa-fw fa fa-user mr-2"></em>
                        Создал: <strong>{{($order->created_user!=null)?$order->created_user->name:' - '}}</strong>
                    </a>
                    <a class="list-group-item list-group-item-action" href="javascript:;">
                        <em class="fa-fw fas fa-calendar-alt mr-2"></em>
                        Собран: <strong>{{($order->getherstatistic!=null && $order->getherstatistic->finish != null)?\Carbon\Carbon::parse($order->getherstatistic->finish)->format('d.m.Y H:i'):' - '}}</strong>
                    </a>
                    <a class="list-group-item list-group-item-action" href="javascript:;">
                        <em class="fa-fw fa fa-user mr-2"></em>
                        Собрал: <strong>{{($order->getherstatistic!=null && $order->getherstatistic->user!=null)?$order->getherstatistic->user->name:' - '}}</strong>
                    </a>

                    <a class="list-group-item list-group-item-action" href="javascript:;">
                        <em class="fa-fw fas fa-calendar-alt mr-2"></em>
                        Проверен: <strong>{{($order->checkstatistic!=null && $order->checkstatistic->finish != null)?\Carbon\Carbon::parse($order->checkstatistic->finish)->format('d.m.Y H:i'):' - '}}</strong>
                    </a>

                    <a class="list-group-item list-group-item-action" href="javascript:;">
                        <em class="fa-fw fa fa-user mr-2"></em>
                        Проверил: <strong>{{($order->checkstatistic!=null && $order->checkstatistic->user!=null)?$order->checkstatistic->user->name:' - '}}</strong>
                    </a>
                    <a class="list-group-item list-group-item-action" href="javascript:;">
                        <em class="fa-fw fa fa-user mr-2"></em>
                        Помогал: <strong>{{($order->helpstatistic!=null && $order->helpstatistic->user!=null)?$order->helpstatistic->user->name:' - '}}</strong>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

