<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Model\Table;
use App\Model\Receipt;
use App\Model\Discount;
use App\Model\Order;
use App\Model\OrderTable;
use App\Model\Kitchen;
use App\Model\Dish;
use App\Model\OrderDish;
use App\Model\Option;
use App\Model\Item;
use App\Model\Booked;
use App\Model\BookedTable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $table_type = array(1=>'A', 'B', 'C', 'Line');
    public $customers = array(0=>'テイクアウト', '30分', '60分', '90分', '120分', '無制限');

    public function get_table_id($table_id)
    {
        $table_display_name = Table::select('name')->where('id', $table_id)->get();
//        $table_display_type = $table_display_gets[0]->type;
//        $table_display_index = $table_display_gets[0]->index;
//        $table_display_type = $this->table_type[$table_display_type];
//        $table_display_id = $table_display_type.'-'.$table_display_index;
        return $table_display_name;
    }

    public function get_time_data($time_data)
    {
        $hour = substr($time_data, 0,2);
        $min = substr($time_data, 2 );
        if($hour < 12) {
            $display_time = $hour.$min.' AM';
        }
        else {
            $display_time = $hour - 12;
            if($display_time < 10)
                $display_time = '0'.$display_time.$min.' PM';
            else
                $display_time = $display_time.$min.' PM';
        }
        return $display_time;
    }

    public function get_default_duration_id()
    {
        $duration_id = Receipt::profile()->customer;
//        $default_duration = $this->customers[$duration_id];

        return $duration_id;
    }

    public function get_discount($dish_id)
    {
//        date_default_timezone_set("Asia/Shanghai");
        date_default_timezone_set("Asia/Tokyo");
        $date = date('Y-m-d H:i:s');//dd($date);
        $check_discount = Discount::where('dish_id', $dish_id)->where('start', '<=', $date)->where('end', '>=', $date)->get()->first();//dd($check_discount);
        if($check_discount)
            $discount = $check_discount->discount;
        else
            $discount = '';
        return $discount;
    }

    public function get_during_minutes($time) // get duting minutes
    {
        $current_time = $this->get_current_time();
        $during_time = (int)((strtotime($current_time) - strtotime($time)) / 60);
        return $during_time;
    }

    public function get_current_time() {
//        date_default_timezone_set("Asia/Shanghai");
//        date_default_timezone_set("Australia/Melbourne");
        $current_datetime = date('Y-m-d H:i:s');
        return $current_datetime;
    }

    public function get_table_name($table_id) {
        $table_info = Table::where('id', $table_id)->get()->first();
        $table_name = $table_info->name;
        return $table_name;
    }

    public function get_day_data($date)
    {
        $date = date('D d M', strtotime($date));
        return $date;
    }

    public function get_monthly_data($date)
    {
        $date = date('M Y', strtotime($date));
        return $date;
    }

    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>===   Broadcast part  ===<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<//

    public function CountNotification()
    {

        $count_notification = array();

        //ready_pay_count
        $count_notification['ready_pay_count'] = Order::where('pay_flag', 1)->get()->count();

        //calling_count
        $count_notification['calling_count'] = OrderTable::where('calling_time', '<>', null)->distinct()->pluck('order_id')->count();

        //attend_count
        $count_notification['attend_count'] = OrderTable::where('calling_time', '<>', null)->where('attend_time', null)->distinct()->pluck('order_id')->count();

        //review_count
        $count_notification['review_count'] = Order::where('pay_flag',  '<>', 2)->where('review', '!=', Null)->get()->count();

        //note_count
        $count_notification['note_count'] = Order::where('pay_flag',  '<>', 2)->where('note', '!=', Null)->get()->count();

        //display count of seated and booking status
        $count_notification['seated'] = Order::where('pay_flag',  '<>', 2)->where('status', 'seated')->get()->count();

        $current_date = date('Y-m-d');
        $count_notification['bookings'] = Booked::where('timer_flag', 0)->where('time', '<=',$current_date . " 23:59:59" )
            ->where('time', '>=',$current_date . " 00:00:00" )->where('status', 'booking')->get()->count();

        return $count_notification;
    }

    public function CountNotification1($table_id,$selected)
    {

        $count_notification = array();

        //ready_pay_count
        $count_notification['ready_pay_count'] = Order::where('pay_flag', 1)->get()->count();

        //calling_count
        $count_notification['calling_count'] = OrderTable::where('calling_time', '<>', null)->distinct()->pluck('order_id')->count();

        //attend_count
        $count_notification['attend_count'] = OrderTable::where('calling_time', '<>', null)->where('attend_time', null)->distinct()->pluck('order_id')->count();

        //review_count
        $count_notification['review_count'] = Order::where('pay_flag',  '<>', 2)->where('review', '!=', Null)->get()->count();

        //note_count
        $count_notification['note_count'] = Order::where('pay_flag',  '<>', 2)->where('note', '!=', Null)->get()->count();

        //display count of seated and booking status
        $count_notification['seated'] = Order::where('pay_flag',  '<>', 2)->where('status', 'seated')->get()->count();

        $current_date = date('Y-m-d');
        $count_notification['bookings'] = Booked::where('timer_flag', 0)->where('time', '<=',$current_date . " 23:59:59" )
            ->where('time', '>=',$current_date . " 00:00:00" )->where('status', 'booking')->get()->count();

        $count_notification['table_id'] = $table_id;

        $count_notification['selected'] = $selected;

        return $count_notification;
    }

    //get_added_dish for broadcast in customer part
    public function get_added_dish($dish, $order_id, $order_dish_id)
    {
        //dish info
        $added_dish['dish_name_en'] = $dish->name_en;
        $added_dish['dish_image'] = $dish->image;
        $added_dish['group_id'] = $dish->group_id;

        //order info
        $added_dish['order_id'] = $order_id;
        $added_dish['starting_time'] = Order::where('id', $order_id)->pluck('time')->first();

        //order table info
        $display_table_list = OrderTable::where('order_id', $order_id)->get();
        $added_dish['display_table_id'] = $display_table_list[0]->table_id;
        $added_dish['display_table'] = $this->get_table_name($added_dish['display_table_id']);
        $added_dish['table_count'] = count($display_table_list);
        $added_dish['calling_time'] = OrderTable::where('table_id', $added_dish['display_table_id'])->pluck('calling_time');

        //order dish info
        $order_dish_list = OrderDish::where('id', $order_dish_id)->get()->first();
        $added_dish['id'] = $order_dish_list->id;
        $added_dish['dish_id'] = $order_dish_list->dish_id;
        $added_dish['count'] = $order_dish_list->count;
        $added_dish['dish_price'] = $order_dish_list->dish_price;
        $added_dish['total_price'] = $order_dish_list->total_price;
        $added_dish['ready_flag'] = $order_dish_list->ready_flag;

        //option info
        $added_dish['options'] = $order_dish_list->Order_Option()->get();
        if($added_dish['options']) {
            for($i=0;$i < count($added_dish['options']);$i++)
            {
                $added_dish['options'][$i]['option_name'] = Option::where('id', $added_dish['options'][$i]['option_id'])->pluck('name')->first();
                $added_dish['options'][$i]['item_name'] = Item::where('id', $added_dish['options'][$i]['item_id'])->pluck('name')->first();
            }
        } else {
            $added_dish['options'] = [];
        }

        return $added_dish;

    }

}
