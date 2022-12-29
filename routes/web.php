<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Artisan::call('view:clear');

//Route::get('/', function () {
//    return view('welcome');
//})->middleware('auth');

Route::get('login', 'AuthController@getLogin')->name('login');
Route::post('login', 'AuthController@postLogin');
Route::get('logout', 'AuthController@getLogout');

Route::group(['prefix' => 'admin'], function () {
    Route::get('', ['as' => 'admin', function () {
        if (Auth::check()) {
            return to_route('admin.users'); // админка
        } else {
            return to_route('admin.loginform'); // форма логина с роутами для админки
        }
    }]);
    Route::get('login', 'AuthController@getAdminLogin')->name('admin.loginform');
    Route::post('login', 'AuthController@postAdminLogin')->name('admin.login');
    Route::get('logout', 'AuthController@getAdminLogout')->name('admin.logout');
});
Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
    //users
    Route::get('users', 'UserController@index')->middleware('auth')->name('admin.users');
    Route::post('users/getJson', 'UserController@getJson')->middleware('auth');
    Route::post('users/add', 'UserController@add')->middleware('auth')->name('admin.users.modal.add');
    Route::post('users/edit', 'UserController@edit')->middleware('auth')->name('admin.users.modal.edit');
    Route::post('users/editpin', 'UserController@editpin')->middleware('auth')->name('admin.users.modal.pin');
    Route::post('users/editpass', 'UserController@editpass')->middleware('auth')->name('admin.users.modal.pass');
    Route::post('users/store', 'UserController@store')->middleware('auth');
    Route::post('users/changepin', 'UserController@changepin')->middleware('auth');
    Route::post('users/changepass', 'UserController@changepass')->middleware('auth');
    Route::post('users/{id}/delete', 'UserController@delete')->middleware('auth');
    Route::post('users/shift/close', 'UserController@shiftclose')->middleware('auth')->name('user.shift.close');
    //duties
    Route::get('duties', 'DutyController@index')->middleware('auth')->name('admin.duties');
    Route::post('duties/add', 'DutyController@add')->middleware('auth')->name('admin.duties.modal.add');
    Route::post('duties/edit', 'DutyController@edit')->middleware('auth')->name('admin.duties.modal.edit');
    Route::post('duties/store', 'DutyController@store')->middleware('auth');
    Route::post('duties/{id}/delete', 'DutyController@delete')->middleware('auth');
    //statstics
    Route::get('statistics', 'StatisticController@index')->middleware('auth')->name('admin.statistics');
    Route::post('statistics/initTable', 'StatisticController@initTable')->middleware('auth');
//    Route::post('statistics/getJson', "StatisticController@getJson")->middleware('auth');
    Route::get('daystatistics', 'StatisticController@getDayStatistics')->middleware('auth')->name('admin.daystatistics');
    Route::post('daystatistics/getJsonDayStatistics', 'StatisticController@getJsonDayStatistics')->middleware('auth');
    Route::get('orderstatistics', 'StatisticController@getOrderStatistics')->middleware('auth')->name('admin.orderstatistics');
    Route::post('orderstatistics/getJsonOrderStatistics', 'StatisticController@getJsonOrderStatistics')->middleware('auth');
    Route::get('realtimestatistics', 'StatisticController@getRealTimeStatistics')->middleware('auth')->name('admin.realtimestatistics');
    Route::post('realtimestatistics/getJsonRealTimeStatistics', 'StatisticController@getJsonRealTimeStatistics')->middleware('auth');
    Route::post('realtimestatistics/getJsonRealTimeStatistics2', 'StatisticController@getJsonRealTimeStatistics2')->middleware('auth');

    //удаление проверка сборки из статистики
    Route::post('orderstatistics/order/delete/check', 'StatisticController@postDeleteCheckOrder')->middleware('auth')->name('order.forceclose.check');
    Route::post('orderstatistics/order/delete/complete', 'StatisticController@postDeleteCompleteOrder')->middleware('auth')->name('order.forceclose.complete');

    //user day statistics

    Route::post('statistics/user/modal/info', 'StatisticController@postModalStatisticsUserInfo')->middleware('auth')->name('statistics.user.modal.info');
});

Route::get('login', 'AuthController@getLogin')->name('login');
Route::post('login', 'AuthController@postLogin');
Route::get('logout', 'AuthController@getLogout')->name('logout');
Route::get('/', function () {
    return to_route('main');
})->middleware('auth');
Route::get('start', 'WorkerController@start')->middleware('auth')->name('start');
Route::get('break', 'WorkerController@break')->middleware('auth')->name('break');
Route::get('action', 'WorkerController@action')->middleware('auth')->name('action');
Route::get('main', 'WorkerController@main')->middleware('auth')->name('main');

Route::get('init_today_statistic_table', 'WorkerController@init_today_statistic_table')->name('init_today_statistic_table');

Route::post('workstart', 'WorkerController@workstart')->middleware('auth')->name('workstart');
Route::post('changestatus', 'WorkerController@changestatus')->middleware('auth')->name('changestatus');
Route::post('changestatusWorkPause', 'WorkerController@changestatusWorkPause')->middleware('auth')->name('changestatus.workpause');
Route::post('changestatusorder', 'WorkerController@changestatusorder')->middleware('auth')->name('changestatusorder');
//statusdelivery
Route::post('changestatusdelivery', 'WorkerController@changestatusdelivery')->middleware('auth')->name('changestatusdelivery');
//statusmanagertask
Route::post('changestatusmanagertask', 'WorkerController@changestatusmanagertask')->middleware('auth')->name('changestatusmanagertask');

//orders
Route::get('orders', 'WorkerController@getOrders')->middleware('auth')->name('orders');
Route::post('orders/getJson', 'WorkerController@getOrdersJson')->middleware('auth');
Route::post('orders/add', 'WorkerController@getOrderAdd')->middleware('auth')->name('orders.modal.add');
Route::post('orders/edit', 'WorkerController@getOrderEdit')->middleware('auth')->name('orders.modal.edit');
Route::post('orders/store', 'WorkerController@postOrderStore')->middleware('auth')->name('orders.modal.store');
Route::post('orders/delete', 'WorkerController@postOrderDelete')->middleware('auth')->name('orders.delete');
Route::post('orders/modal/info', 'WorkerController@postOrderModalInfo')->middleware('auth')->name('orders.modal.info');
Route::get('orders/{id}', 'WorkerController@getOrder')->middleware('auth')->name('order.view');

//duties
Route::get('duties', 'WorkerController@getDuties')->middleware('auth')->name('duties');
Route::post('duties/getJsonDuties', 'WorkerController@getJsonDuties')->middleware('auth');
Route::post('changestatusduty', 'WorkerController@changestatusduty')->middleware('auth')->name('changestatusduty');

//cron
Route::get('сloseendofday', 'CronController@сloseendofday');

Route::get('test', function () {
    return '0';
});
