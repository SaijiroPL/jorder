<?php

namespace App\Http\Controllers;

use App\Model\Dish;
use App\Model\Item;
use App\Model\Option;
use App\Model\Order;
use App\Model\OrderDish;
use App\Model\OrderOption;
use App\Model\OrderTable;
use App\Model\Table;
use Illuminate\Http\Request;
use App\Model\Kitchen;
use React\HttpClient\RequestData;

use App\Events\KitchenEvent;
use App\Events\PayEvent;
use App\Events\NotificationEvent;
use App\Events\AttendEvent;

use App\Http\Controllers\print_table1;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;

class KitchenController extends Controller
{

    //main_screen
    public function main_screen(Request $request)
    {
        $kitchen_group = null;
        if($request->has('group_id')) {
            $group_id = $request->group_id;
            if ($group_id != 0) {
                $kitchen_group = Kitchen::find($group_id);
            }
        }
        else {
            $group_id = 0;
        }

        //get order dish data by group id
        $order_ids = Order::where('pay_flag', '<>', 2)->pluck('id');
        if(count($order_ids) > 0) {
            if ($group_id == 0) {
                $group_dish_ids = Dish::pluck('id');
            } else {
                $group_dish_ids = Dish::where('group_id', 'like', '%&' . $group_id . '&%')->pluck('id');
            }
            $group_order_dishes = OrderDish::whereIn('order_id', $order_ids)
                ->whereIn('dish_id', $group_dish_ids)
                // ->where('ready_flag', '0')
                ->orderBy('created_at', 'DESC')
                ->get();
            $group_order_dishes = $this->get_order_dish($group_order_dishes, $group_id);
        }
        else {
            $group_order_dishes = '';
        }

        $attend_status = OrderTable::where('calling_time', '<>', null)->where('attend_time', null)->distinct()->pluck('order_id')->count();

//        $count_notification = $this->CountNotification();
//        broadcast(new NotificationEvent($count_notification));

//        dd($group_order_dishes);
        return view('kitchen.main_screen')->with(compact('kitchen_group', 'group_order_dishes', 'group_id', 'attend_status'));
    }

    public function attend(Request $request)
    {
        if($request->table_id != 0) {
//            $table_id = $request->table_id;
//            $now = $this->get_current_time();
//            $order_id = OrderTable::where('table_id', $table_id)->pluck('order_id');
//            OrderTable::where('order_id', $order_id)->update(['attend_time' => $now]);
            $table_id = $request->table_id;
            $order_id = OrderTable::where('table_id', $table_id)->pluck('order_id');
            OrderTable::where('order_id', $order_id)->update(['calling_time' => Null]);

            broadcast(new AttendEvent($order_id));
            $count_notification = $this->CountNotification();
            broadcast(new NotificationEvent($count_notification));
        }

        $attend_info = collect();
        $order_ids = OrderTable::where('calling_time', '<>', Null)->orderBy('calling_time', 'DESC')->pluck('order_id');
        if(count($order_ids) > 0)
        {
            $order_id_text = $this->get_order_ids($order_ids);
            $attend_info = Order::where('pay_flag', '<>', 2)->whereIn('id', $order_ids)->orderByRaw('FIELD (id, '.implode(',', $order_id_text).') DESC')->get();

            foreach($attend_info as $attend) {

                $table_id = OrderTable::where('order_id', $attend->id)->pluck('table_id')->first();
                $table_name = $this->get_table_name($table_id);
                $attend->table_name = $table_name;

                foreach($attend->ordertables as $orderTable) {
                    if($orderTable->calling_time != Null) {
                        $attend->calling_time = $orderTable->calling_time;
                        if($orderTable->attend_time != Null) {
                            $attend->attend_time = $orderTable->attend_time;
                            $attend->attended_time = intval(strtotime($attend->attend_time)-strtotime($attend->calling_time));
                        } else {
                            $attend->attended_time = '';
                        }
                        $attend->calling_table_id = $orderTable->table_id;
                    }
                }

            }
        }

//        dd($attend_info);
        if(count($attend_info) > 0)
            return view('kitchen.calling_modal')->with(compact('attend_info'));
        else
            echo('<script>$("#CallingModal").modal("hide");</script>');
    }

    //change_group
    public function change_group()
    {
        $group_id = request()->group_id;
        $group_data = Kitchen::all();
        //dd($group_data);
        return view('kitchen.change_group')->with(compact('group_data', 'group_id'));
    }

    public function ready(Request $request)
    {
        $orderdish = OrderDish::findOrFail($request->selected_id);
        $group_id = 0;
        if($orderdish->ready_flag == 1){
            $orderdish->ready_flag = 0;
            $orderdish->ready_time = Null;
        } else {
            $orderdish->ready_flag = 1;
            $orderdish->ready_time = $this->get_current_time();

            //print part
            //$printerIp = '192.168.192.151';
            $group_id = $request->group_id;
            $printerIp = Kitchen::where('id', $group_id)->pluck('printer_ip')->first();
            $printerPort = 9100;

            $ready_time = $orderdish->created_at;
            $time = substr($ready_time,11);
            $date = strtoupper(date("d M Y", strtotime(substr($ready_time,0,10))));

            $qty = $orderdish->count;

            $table_ids = OrderTable::where('order_id', $orderdish->order_id)->pluck('table_id');
            $table_name = "";
            foreach($table_ids as $table_id) {
                $table_name .= $this->get_table_name($table_id).'+';
            }
            $table_name = rtrim($table_name, '+');

            $dish_name = Dish::where('id',$orderdish->dish_id)->pluck('name_en')->first();

            $order_options = OrderOption::where('order_dish_id',$orderdish->id)->get();

            foreach($order_options as $order_option) {

                if($order_option->option_id)
                    $order_option->option_name = Option::where('id', $order_option->option_id)->pluck('name')->first();
                else
                    $order_option->option_name = '';

                if($order_option->item_id)
                    $order_option->item_name = Item::where('id', $order_option->item_id)->pluck('name')->first();
                else
                    $order_option->item_name = '';
            }

            $connector = new NetworkPrintConnector($printerIp, $printerPort);
            $printer = new Printer($connector);

            try {

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                /*$printer -> text(new print_table1('TIME:' . $time, 'DATE:' . $date));
                $printer -> text(new print_table1('TABLE:' . $table_name, 'QTY:' . $qty));*/
                $printer->setTextSize(1,2);
                $printer->setEmphasis(false);
                $printer -> text('TIME:' . $time);
                $printer -> text('                  DATE:' . $date);
                $printer->text("\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1,2);
                $printer->setEmphasis(false);
                $printer -> text('TABLE:');
                $printer->setEmphasis(true);
                $printer->setTextSize(2,2);
                $qty_len = strlen($qty);
                $table_len = 19 - $qty_len;
                $printer -> text(str_pad($table_name,$table_len,' ', STR_PAD_RIGHT));
                $printer->setEmphasis(false);
                $printer->setTextSize(1,2);
                $printer -> text('QTY:');
                $printer->setEmphasis(true);
                $printer->setTextSize(2,2);
                $printer -> text($qty);
                $printer->text("\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(2,2);
                $printer->setEmphasis(false);
                $printer->text($dish_name);

                foreach($order_options as $order_option) {
                    $printer->setTextSize(1,2);
                    $printer->text( "[" . $order_option->option_name . ":");
                    $printer->setEmphasis(true);
                    $printer->setTextSize(2,2);
                    $printer->text($order_option->item_name);
                    $printer->setEmphasis(false);
                    $printer->setTextSize(1,2);
                    $printer->text("]");
                }

                $printer->text("\n");

                $printer->cut();

            } finally {
                $printer -> close();
            }

        }
        $orderdish->save();

        $filter_flag = $request->filter_flag;
        $order_dishes = collect();
        if($filter_flag == 1)
        {
            $order_ids = Order::where('pay_flag', '<>', 2)->pluck('id');
            $dish_id = $request->id;
            if(count($order_ids) > 0) {

                $order_dishes = OrderDish::whereIn('order_id', $order_ids)->where('dish_id', $dish_id)->where('ready_flag', '0')->orderBy('created_at', 'ASC')->get();
                if(count($order_dishes) > 0)
                    $order_dishes = $this->get_order_dish($order_dishes);
            }

            return view('kitchen.extract_modal')->with(compact('order_dishes', 'filter_flag', 'dish_id', 'group_id'));
        }
        elseif($filter_flag == 2)
        {
            $table_id = $request->id;
            $order_id = OrderTable::where('table_id', $table_id)->pluck('order_id')->first();
            $order_dishes = OrderDish::where('order_id', $order_id)->where('ready_flag', '0')->orderBy('created_at', 'ASC')->get();
            $order_dishes = $this->get_order_dish($order_dishes);
            return view('kitchen.extract_modal')->with(compact('order_dishes', 'filter_flag', 'table_id', 'group_id'));
        }

//        return $orderdish->ready_flag;
    }

    //extract cooking name
    public function extract_cooking_name()
    {
        $order_ids = Order::where('pay_flag', '<>', 2)->pluck('id');
        if(count($order_ids) > 0) {
            $dish_id = request()->dish_id;
            $order_dishes = OrderDish::whereIn('order_id', $order_ids)->where('dish_id', $dish_id)->where('ready_flag', '0')->orderBy('created_at', 'ASC')->get();
            $order_dishes = $this->get_order_dish($order_dishes);
        }

        $filter_flag = 1;

        $group_id = request()->group_id;

        return view('kitchen.extract_modal')->with(compact('order_dishes', 'filter_flag', 'dish_id', 'group_id'));

    }

    //extract table number
    public function extract_table_number()
    {
        $table_id = request()->table_id;
        $order_id = OrderTable::where('table_id', $table_id)->pluck('order_id')->first();
        $order_dishes = OrderDish::where('order_id', $order_id)->where('ready_flag', '0')->orderBy('created_at', 'ASC')->get();
        $order_dishes = $this->get_order_dish($order_dishes);

        $filter_flag = 2;

        $group_id = request()->group_id;

        return view('kitchen.extract_modal')->with(compact('order_dishes', 'filter_flag', 'table_id', 'group_id'));
    }

    public function get_order_dish($group_order_dishes, $group_id = 0)
    {

        foreach($group_order_dishes as $group_order_dish)
        {
//            $group_order_dish->starting_time = $group_order_dish->created_at;
            //get table info
            $display_table_list = OrderTable::where('order_id', $group_order_dish->order_id)->get();
            $display_table_id = $display_table_list[0]->table_id;
            $group_order_dish->display_table_id = $display_table_id;
            $group_order_dish->display_table = $this->get_table_name($display_table_id);
            $group_order_dish->table_count = count($display_table_list);
            $group_order_dish->calling_time = OrderTable::where('table_id', $display_table_id)->pluck('calling_time')->first();

            //get order dish info
            $dish_list = Dish::select('image', 'name_en')->where('id', $group_order_dish->dish_id)->get()->first();
            $group_order_dish->dish_image = $dish_list->image;
            $group_order_dish->dish_name_en = $dish_list->name_en;

            //get order option info
            $group_order_dish->options = $group_order_dish->Order_Option()->get();
            foreach($group_order_dish->options as $option)
            {
                $option->option_name = Option::where('id', $option->option_id)->pluck('name')->first();
                $option->item_name = Item::where('id', $option->item_id)->pluck('name')->first();
            }

            $group_order_dish->group_id = $group_id;

        }

        return $group_order_dishes;
    }

    public function get_order_ids($ids) {

        $order_ids = '';
        foreach($ids as $order_id) {
            $order_ids .= $order_id.',';
        }
        $order_id_text = [substr($order_ids, 0, -1)];
        return $order_id_text;
    }

    public function docket() {

        $group_id = request()->group_id;
        $current_date = date('Y-m-d');
        $order_ids = Order::whereDate('created_at', $current_date)->where('pay_flag', '<>', 2)->pluck('id')->toArray();
//        $order_ids1 = Order::whereDate('created_at', $current_date)->where('menu_type', 'Menu')->where('pay_flag', '<>', 2)->pluck('id')->toArray();
//        $order_ids2 = Order::whereDate('created_at', $current_date)->where('menu_type', 'TakeawayMenu')->pluck('id')->toArray();
//        $order_ids = array_merge($order_ids1, $order_ids2);

        //dd($order_ids);
//        $order_ids = Order::where('pay_flag', '<>', 2)->pluck('id');

        if(count($order_ids) > 0) {
            $order_dishes = OrderDish::whereIn('order_id', $order_ids)
                ->join('dishes', 'dishes.id', '=', 'order_dish_match.dish_id')
                ->where('dishes.group_id', 'like', '%&' . $group_id . '&%')
                ->where('order_dish_match.ready_flag', '1')
                ->select('order_dish_match.*')
                ->orderBy('order_dish_match.ready_time', 'DESC')
                ->orderBy('order_dish_match.created_at', 'ASC')->get();
            $order_dishes = $this->get_order_dish($order_dishes);

            foreach($order_dishes as $order_dish) {
                //$order_dish->time = $this->get_time_data(substr($order_dish->created_at, 11, 5));
                $order_dish->time = Order::where('id', $order_dish->order_id)->pluck('time')->first();
            }

            if(count($order_dishes) > 0) {
                return view('kitchen.docket_modal')->with(compact('order_dishes', 'group_id'));
            } else {
                return '';
            }
        } else {
            return '';
        }

    }

    public function java_alert() {
        return view('kitchen.java_alert');
    }

//    public function get_change_group_dish(Request $request)
//    {
//
//        $group_id = $request->group_id;
//        $change_group_dish = array();
//        $dish_id_list = Dish::where('group_id', $group_id)->pluck('id');//dd($group_id);
//        $order_dish_list = OrderDish::whereIn('dish_id', $dish_id_list)->get();
//        foreach($order_dish_list as $key => $order_dish)
//        {
//            //order info
//            $order_id = $order_dish->order_id;
//            $change_group_dish[$key]['order_id'] = $order_id;
//            $change_group_dish[$key]['starting_time'] = Order::where('id', $order_id)->pluck('time')->first();
//
//            //order table info
//            $display_table_list = OrderTable::where('order_id', $order_id)->get();
//            $change_group_dish[$key]['display_table_id'] = $display_table_list[0]->table_id;
//            $change_group_dish[$key]['display_table'] = $this->get_table_name($change_group_dish[$key]['display_table_id']);
//            $change_group_dish[$key]['table_count'] = count($display_table_list);
//            $change_group_dish[$key]['calling_time'] = OrderTable::where('table_id', $change_group_dish[$key]['display_table_id'])->pluck('calling_time');
//
//            //order dish info
//            $change_group_dish[$key]['id'] = $order_dish->id;
//            $change_group_dish[$key]['dish_id'] = $order_dish->dish_id;
//            $change_group_dish[$key]['count'] = $order_dish->count;
//            $change_group_dish[$key]['dish_price'] = $order_dish->dish_price;
//            $change_group_dish[$key]['total_price'] = $order_dish->total_price;
//            $change_group_dish[$key]['ready_flag'] = $order_dish->ready_flag;
//
//            //dish info
//            $dish = Dish::where('id', $order_dish->dish_id)->get()->first();
//            $change_group_dish[$key]['dish_name_en'] = $dish->name_en;
//            $change_group_dish[$key]['dish_image'] = $dish->image;
//            $change_group_dish[$key]['group_id'] = $dish->group_id;
//
//            //option info
//            $change_group_dish[$key]['options'] = $order_dish->Order_Option()->get();
//            if($change_group_dish[$key]['options']) {
//                for($i=0;$i < count($change_group_dish[$key]['options']);$i++)
//                {
//                    $change_group_dish[$key]['options'][$i]['option_name'] = Option::where('id', $change_group_dish[$key]['options'][$i]['option_id'])->pluck('name')->first();
//                    $change_group_dish[$key]['options'][$i]['item_name'] = Item::where('id', $change_group_dish[$key]['options'][$i]['item_id'])->pluck('name')->first();
//                }
//            } else {
//                $change_group_dish[$key]['options'] = [];
//            }
//        }
//
//        return $change_group_dish;
//
//    }

    public function reprint(Request $request)
    {
        $order_dish_id = $request->order_dish_id;

        $orderdish = OrderDish::findOrFail($order_dish_id);
        if (!$orderdish) {
            return;
        }

        //$printerIp = '192.168.192.151';
        $group_id = $request->group_id;
        $printerIp = Kitchen::where('id', $group_id)->pluck('printer_ip')->first();
        $printerPort = 9100;

        $ready_time = $orderdish->ready_time;
        $time = substr($ready_time,11);
        $date = strtoupper(date("d M Y", strtotime(substr($ready_time,0,10))));

        $qty = $orderdish->count;

        $order_id = $orderdish->order_id;
        $order = Order::where('id', $order_id)->get()->first();
        $table_name = $order->table_name;

        $dish_name = Dish::where('id',$orderdish->dish_id)->pluck('name_en')->first();

        $order_options = OrderOption::where('order_dish_id',$orderdish->id)->get();

        foreach($order_options as $order_option) {

            if($order_option->option_id)
                $order_option->option_name = Option::where('id', $order_option->option_id)->pluck('name')->first();
            else
                $order_option->option_name = '';

            if($order_option->item_id)
                $order_option->item_name = Item::where('id', $order_option->item_id)->pluck('name')->first();
            else
                $order_option->item_name = '';
        }

        $connector = new NetworkPrintConnector($printerIp, $printerPort);
        $printer = new Printer($connector);

        try {
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            /*$printer -> text(new print_table1('TIME:' . $time, 'DATE:' . $date));
            $printer -> text(new print_table1('TABLE:' . $table_name, 'QTY:' . $qty));*/
            $printer->setTextSize(1,2);
            $printer->setEmphasis(false);
            $printer -> text('TIME:' . $time);
            $printer -> text('                  DATE:' . $date);
            $printer->text("\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1,2);
            $printer->setEmphasis(false);
            $printer -> text('TABLE:');
            $printer->setEmphasis(true);
            $printer->setTextSize(2,2);
            $qty_len = strlen($qty);
            $table_len = 19 - $qty_len;
            $printer -> text(str_pad($table_name,$table_len,' ', STR_PAD_RIGHT));
            $printer->setEmphasis(false);
            $printer->setTextSize(1,2);
            $printer -> text('QTY:');
            $printer->setEmphasis(true);
            $printer->setTextSize(2,2);
            $printer -> text($qty);
            $printer->text("\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(2,2);
            $printer->setEmphasis(false);
            $printer->text($dish_name);

            foreach($orderdish->options as $option) {
                $printer->text( "[" . $option->option_name . ":");
                $printer->setEmphasis(true);
                $printer->setTextSize(2,2);
                $printer->text($option->item_name);
                $printer->setEmphasis(false);
                $printer->setTextSize(1,2);
                $printer->text("]");
            }

            $printer->text("\n");

            $printer->cut();

        } finally {
            $printer -> close();
        }

        return $table_name;
    }
}
