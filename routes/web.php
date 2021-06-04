<?php

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

//Route::get('/rename_dish', 'LoginController@rename_dish');

Route::get('refresh-csrf', function(){
    return csrf_token();
});

Route::get('/', 'LoginController@getLogin');
//Route::post('/', 'LoginController@getLogin');
Route::get('/', 'LoginController@getLogin')->name('loginform');
Route::post('login', 'LoginController@postLogin')->name('login');
//Route::get('login', 'LoginController@appLogin')->name('applogin');
Route::post('change_ip', 'LoginController@change_ip')->name('change_ip');

Route::get('admin/login', 'LoginController@adminLogin')->name('admin.check');
Route::post('admin/login', 'LoginController@adminPostLogin')->name('admin.login');

Route::get('admin/home', 'HomeController@index')->name('admin.home');

Route::get('admin/bookings', 'BookingController@index')->name('admin.booking');
Route::get('admin/booking/edit', 'BookingController@edit')->name('admin.booking.edit');

Route::get('admin/transactions', 'TransactionController@index')->name('admin.transaction');
Route::get('admin/src_trans', 'TransactionController@src_trans')->name('admin.src_trans');
Route::post('admin/transactions/cashinput', 'TransactionController@cashinput')->name('admin.transaction.cashinput');

//Route::get('admin/review', 'SaleController@review')->name('admin.review');

//reception
Route::get('reception/seated', 'ReceptionController@seated')->name('reception.seated');
Route::get('reception/waiting', 'ReceptionController@waiting')->name('reception.waiting');
Route::post('reception/booking', 'ReceptionController@booking')->name('reception.booking');
Route::get('reception/addCustomer', 'ReceptionController@addCustomer')->name('reception.addCustomer');
Route::get('reception/editOrder', 'ReceptionController@editOrder')->name('reception.editOrder');
Route::post('reception/edit_note_review', 'ReceptionController@edit_note_review')->name('reception.edit_note_review');
Route::post('reception/attend_book', 'ReceptionController@attend_book')->name('reception.attend_book');
Route::get('reception/editOrder1', 'ReceptionController@editOrder1')->name('reception.editOrder1');
Route::post('reception/remove_order_item', 'ReceptionController@removeOrderItem')->name('reception.remove_orderitem');
Route::post('reception/order_info_edit', 'ReceptionController@order_info_edit')->name('reception.order_info_edit');
Route::post('reception/store', 'ReceptionController@store')->name('reception.store');
Route::get('reception/ready_to_pay', 'ReceptionController@ready_to_pay')->name('reception.ready_to_pay');
Route::post('reception/view_calling', 'ReceptionController@view_calling')->name('reception.view_calling');
Route::post('reception/attend', 'ReceptionController@attend')->name('reception.attend');
Route::get('reception/view_review', 'ReceptionController@view_review')->name('reception.view_review');
Route::get('reception/view_note', 'ReceptionController@view_note')->name('reception.view_note');
Route::get('reception/accounting', 'ReceptionController@accounting')->name('reception.accounting');
Route::post('reception/cancel_bill', 'ReceptionController@cancel_bill')->name('reception.cancel_bill');
Route::get('reception/tip', 'ReceptionController@tip')->name('reception.tip');
Route::get('reception/discount', 'ReceptionController@discount')->name('reception.discount');
Route::get('reception/amend', 'ReceptionController@amend')->name('reception.amend');
Route::post('reception/dish_list', 'ReceptionController@dish_list')->name('reception.dish_list');
Route::post('reception/add_item', 'ReceptionController@add_item')->name('reception.add_item');
Route::post('reception/change_count', 'ReceptionController@change_count')->name('reception.change_count');
Route::post('reception/pay', 'ReceptionController@pay')->name('reception.pay');
Route::post('reception/account_print', 'ReceptionController@account_print')->name('reception.account_print');
Route::post('reception/finish_pay', 'ReceptionController@finish_pay')->name('reception.finish_pay');
Route::post('reception/book_end', 'ReceptionController@book_end')->name('reception.book_end');
Route::get('reception/zoom_back', 'ReceptionController@zoom_back')->name('reception.zoom_back');
Route::get('reception/zoom_back1', 'ReceptionController@zoom_back1')->name('reception.zoom_back1');
Route::get('reception/removeBook', 'ReceptionController@removeBooking')->name('reception.booking.remove');
Route::post('reception/updateOrderRemain', 'ReceptionController@updateOrderRemain')->name('reception.update.or');

//customer
Route::get('customer/index/{order_id}', 'CustomerController@index')->name('customer.index');
Route::post('customer/dish_list', 'CustomerController@dish_list')->name('customer.dish_list');
Route::post('customer/dish_list1', 'CustomerController@dish_list1')->name('customer.dish_list1');
Route::post('customer/dish_info', 'CustomerController@dish_info')->name('customer.dish_info');
Route::post('customer/dish_option', 'CustomerController@dish_option')->name('customer.dish_option');
Route::post('customer/dish_option_previous', 'CustomerController@dish_option_previous')->name('customer.dish_option_previous');
Route::post('customer/dish_option_confirm', 'CustomerController@dish_option_confirm')->name('customer.dish_option_confirm');
Route::post('customer/order_dish', 'CustomerController@order_dish')->name('customer.order_dish');
Route::post('customer/orderNow_Photo', 'CustomerController@orderNow_Photo')->name('customer.orderNow_Photo');
Route::get('customer/lang_select', 'CustomerController@lang_select')->name('customer.lang_select');
Route::post('customer/put_lang', 'CustomerController@put_lang')->name('customer.put_lang');
Route::get('customer/feedback', 'CustomerController@feedback')->name('customer.feedback');
Route::post('customer/add_review', 'CustomerController@add_review')->name('customer.add_review');
Route::post('customer/calling', 'CustomerController@calling')->name('customer.calling');
Route::get('customer/view_bill_pay', 'CustomerController@view_bill_pay')->name('customer.view_bill_pay');
Route::get('customer/finish_pay', 'CustomerController@finish_pay')->name('customer.finish_pay');
Route::post('customer/check_finish_pay', 'CustomerController@check_finish_pay')->name('customer.check_finish_pay');
Route::post('customer/remove_dish', 'CustomerController@remove_dish')->name('customer.remove_dish');

//kitchen
Route::get('kitchen/CountNotification', 'KitchenController@CountNotification');
Route::post('kitchen/get_change_group_dish', 'KitchenController@get_change_group_dish')->name('kitchen.get_change_group_dish');
Route::get('kitchen/main_screen', 'KitchenController@main_screen')->name('kitchen.main_screen');
Route::post('kitchen/attend', 'KitchenController@attend')->name('kitchen.attend');
Route::get('kitchen/change_group', 'KitchenController@change_group')->name('kitchen.change_group');
Route::post('kitchen/ready', 'KitchenController@ready')->name('kitchen.ready');
Route::get('kitchen/extract_cooking_name', 'KitchenController@extract_cooking_name')->name('kitchen.extract_cooking_name');
Route::get('kitchen/extract_table_number', 'KitchenController@extract_table_number')->name('kitchen.extract_table_number');
Route::get('kitchen/docket', 'KitchenController@docket')->name('kitchen.docket');
Route::post('kitchen/reprint', 'KitchenController@reprint')->name('kitchen.reprint');
Route::get('kitchen/alert', 'KitchenController@java_alert')->name('kitchen.java_alert');

//admin
//Route::group(['middleware' => 'checkadmin'], function(){
    Route::get('admin/category', 'CategoryController@index')->name('admin.category');
    Route::post('admin/category/add', 'CategoryController@add')->name('admin.category.add');
    Route::get('admin/category/delete/{id}', 'CategoryController@delete')->name('admin.category.delete');
    Route::post('admin/category/subs', 'CategoryController@subs')->name('admin.category.subs');
    Route::post('admin/category/subs_list', 'CategoryController@subs_list')->name('admin.category.subs_list');
    Route::post('admin/category/dish_list', 'CategoryController@dish_list')->name('admin.category.dish_list');
    Route::get('admin/category/dish_delete/{id}', 'CategoryController@dish_delete')->name('admin.category.dish_delete');
    Route::get('admin/category/dish_add', 'CategoryController@dish_add')->name('admin.category.dish_add');
    Route::post('admin/category/edit_title', 'CategoryController@edit_title')->name('admin.category.edit_title');

    Route::get('admin/dish', 'DishController@index')->name('admin.dish');
    Route::get('admin/dish/preview/{id}', 'DishController@preview')->name('admin.dish.preview');
    Route::get('admin/dish/edit/{id}', 'DishController@edit')->name('admin.dish.edit');
    Route::get('admin/dish/delete/{id}', 'DishController@deleteDish')->name('admin.dish.delete');
    Route::get('admin/dish/add', 'DishController@add')->name('admin.dish.add');
    Route::get('admin/dish/sort', 'DishController@sortDish')->name('admin.dish.sort');
    Route::post('admin/dish/store', 'DishController@store')->name('admin.dish.store');
    Route::post('admin/dish/previewpost', 'DishController@previewpost')->name('admin.dish.previewpost');
    Route::post('admin/dish/change_sold_active', 'DishController@change_sold_active')->name('admin.change_sold_active');

    Route::get('admin/option', 'OptionController@index')->name('admin.option');
    Route::get('admin/option/edit/{id}', 'OptionController@edit')->name('admin.option.edit');
    Route::get('admin/option/add', 'OptionController@add')->name('admin.option.add');
    Route::get('admin/option/sort', 'OptionController@sortOption')->name('admin.option.sort');
    Route::post('admin/option/store', 'OptionController@store')->name('admin.option.store');
    Route::get('admin/option/delete/{id}', 'OptionController@delete')->name('admin.option.delete');
    Route::post('admin/option/changephoto', 'OptionController@changephoto')->name('admin.option.changephoto');

    Route::get('admin/discount', 'DiscountController@index')->name('admin.discount');
    Route::get('admin/discount/add', 'DiscountController@add')->name('admin.discount.add');
    Route::get('admin/discount/edit/{id}', 'DiscountController@edit')->name('admin.discount.edit');
    Route::post('admin/discount/store', 'DiscountController@store')->name('admin.discount.store');
    Route::get('admin/discount/sort', 'DiscountController@sortOption')->name('admin.discount.sort');

    Route::get('admin/table', 'TableController@index')->name('admin.table');
    Route::post('admin/table/store', 'TableController@store')->name('admin.table.store');
    Route::post('admin/table/change_roomsize', 'TableController@change_roomsize')->name('admin.change_roomsize');

    Route::get('admin/saledata', 'SaleController@index')->name('admin.saledata');
    Route::get('admin/saledata/period', 'SaleController@period')->name('admin.saledata.period');

    Route::get('admin/setting/kitchen', 'SettingController@kitchen')->name('admin.setting.kitchen');
    Route::get('admin/setting/timeslots', 'SettingController@timeslots')->name('admin.setting.timeslots');
    Route::get('admin/setting/htimeslots', 'SettingController@htimeslots')->name('admin.setting.htimeslots');
    Route::get('admin/setting/customer', 'SettingController@customer')->name('admin.setting.customer');
    Route::get('admin/setting/screentime', 'SettingController@screentime')->name('admin.setting.screentime');

    Route::get('admin/setting/gst', 'SettingController@gst')->name('admin.setting.gst');
    Route::post('admin/setting/gst', 'SettingController@gstpost')->name('admin.setting.gst.save');
    Route::get('admin/setting/payment', 'SettingController@payment')->name('admin.setting.payment');
    Route::get('admin/setting/receipt', 'SettingController@receipt')->name('admin.setting.receipt');
    Route::post('admin/setting/receipt', 'SettingController@receiptpost')->name('admin.setting.receipt.save');
    Route::post('admin/setting/receipt/changelogo', 'SettingController@changelogo')->name('admin.setting.changelogo');

    Route::get('admin/setting/badge', 'SettingController@badge')->name('admin.setting.badge');
    Route::get('admin/setting/language', 'SettingController@language')->name('admin.setting.language');
    Route::get('admin/setting/password', 'SettingController@password')->name('admin.setting.password');
    Route::get('admin/setting/sendmail', 'SettingController@sendmail')->name('admin.setting.sendmail');

    Route::post('admin/setting/kitchenpost', 'SettingController@kitchen_post')->name('admin.setting.kitchen.post');
    Route::post('admin/setting/timeslotpost', 'SettingController@timeslot_post')->name('admin.setting.timeslots.post');
    Route::post('admin/setting/htimeslotpost', 'SettingController@htimeslot_post')->name('admin.setting.htimeslots.post');
    Route::post('admin/setting/addbadge', 'SettingController@addbadge')->name('admin.setting.addbadge');
    Route::post('admin/setting/customerpost', 'SettingController@customer_post')->name('admin.setting.customer.save');
    Route::post('admin/setting/languagepost', 'SettingController@language_post')->name('admin.setting.language.save');
    Route::post('admin/setting/passwordpost', 'SettingController@password_post')->name('admin.setting.password.save');
    Route::post('admin/setting/screentimepost', 'SettingController@screentime_post')->name('admin.setting.screentime.save');
    Route::post('admin/setting/addscreensaver', 'SettingController@add_screensaver')->name('admin.setting.screentime.img');
    Route::post('admin/setting/activebadge', 'SettingController@active_badge')->name('admin.setting.activebadge');
    Route::post('admin/setting/paymentpost', 'SettingController@payment_post')->name('admin.setting.payment.post');
    Route::post('admin/setting/sendmail_post', 'SettingController@sendmail_post')->name('admin.setting.sendmail.post');
//});

    Route::get('admin/now_sendmail', 'ReceptionController@now_sendmail')->name('admin.now_sendmail');

use Illuminate\Support\Facades\Input;
//Route::get('admin/category/order-dish', 'CategoryController@order_dish')->name('order-dish');
Route::get('order-dish',function(){
    $menu = DB::table('dishes')->orderBy('order','ASC')->get();
    $itemID = Input::get('itemID');
    $itemIndex = Input::get('itemIndex');

    foreach($menu as $value){
        return DB::table('dishes')->where('id','=',$itemID)->update(array('order'=> $itemIndex));
    }
});

Route::get('order-category',function(){
    $menu = DB::table('categories')->orderBy('order','ASC')->get();
    $itemID = Input::get('itemID');
    $itemIndex = Input::get('itemIndex');

    foreach($menu as $value){
        return DB::table('categories')->where('id','=',$itemID)->update(array('order'=> $itemIndex));
    }
});
