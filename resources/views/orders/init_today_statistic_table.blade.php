<div class="row">
    <div class="col-12">
        <div class="card card-default">
            <div class="card-header h4 pl-4 pb-0 mb-0">{{Auth::user()->name}}, вы:</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <td>Собрали</td>
                            <td> {{$array['created']}} </td>
                            <td>{{\App\Http\Controllers\WorkerController::true_wordform($array['created'],'заказ','заказа','заказов')}}</td>
                        </tr>
                        <tr>
                            <td>На сумму</td>
                            <td> {{$array['created_price']}} </td>
                            <td>{{\App\Http\Controllers\WorkerController::true_wordform($array['created_price'],'рубль','рубля','рублей')}}</td>
                        </tr>
                        <tr>
                            <td>Проверили</td>
                            <td> {{$array['checked']}} </td>
                            <td>{{\App\Http\Controllers\WorkerController::true_wordform($array['checked'],'заказ','заказа','заказов')}}</td>
                        </tr>
                        <tr>
                            <td>На сумму</td>
                            <td> {{$array['checked_price']}} </td>
                            <td>{{\App\Http\Controllers\WorkerController::true_wordform($array['checked_price'],'рубль','рубля','рублей')}}</td>
                        </tr>
                        <tr>
                            <td>Брали перерыв</td>
                            <td> {{$array['break']}} </td>
                            <td>{{\App\Http\Controllers\WorkerController::true_wordform($array['break'],'минута','минуты','минут')}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
