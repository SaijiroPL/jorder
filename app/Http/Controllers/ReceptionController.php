<?php

namespace App\Http\Controllers;

use App\Events\ChangeCountEvent;
use App\Events\KitchenEvent;
use App\Events\TableMoveEvent;
use App\Events\FinishAndPayEvent;
use Illuminate\Http\Request;

use App\Events\NotificationEvent;
use App\Events\PayEvent;
use App\Events\AttendEvent;

use App\Model\Category;
use App\Model\Order;
use App\Model\OrderPay;
use App\Model\OrderTable;
use App\Model\Payment;
use App\Model\Receipt;
use App\Model\Table;
use App\Model\OrderDish;
use App\Model\OrderOption;
use App\Model\Dish;
use App\Model\Option;
use App\Model\Item;
use App\Model\Room;
use App\Model\Holiday;
use App\Model\Timeslot;
use App\Model\Booked;
use App\Model\BookedTable;
use App\Model\Kitchen;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use App\Http\Controllers\print_table1;

use Illuminate\Support\Facades\Mail;
use App\Mail\SalesDayReportEmail;
use Illuminate\Support\Facades\DB;
use Excel;
use phpDocumentor\Reflection\Types\Null_;
class ReceptionController extends Controller
{

    //reception main screen ============================================================================================
    //seated   : order.status = seated;
    //waiting  : order.status = waiting; (calling_time is not null)
    //bookings : order.status = booking;

    public function seated()
    {
        $old_befores = Booked::where('timer_flag',0)->get();
        if(count($old_befores) > 0) {
            foreach($old_befores as $old_before) {
                $remain_time = strtotime($old_before->time) - time();
                if($remain_time < 0) {
                    Booked::where('id', $old_before->id)->update(['timer_flag' => 1]);
                }
            }
        }

        $current_date = date('Y-m-d');

        $booking_cnt = Booked::where('time', '<=',$current_date . " 23:59:59" )
            ->where('time', '>=',$current_date . " 00:00:00" )
            ->where('status', 'booking')
            // ->where('timer_flag',0)
            ->get()->count();

        $status = (request()->get('status'));
        switch($status){
            case 'seated'://seated
                $order_side_obj = Order::where('pay_flag', '<>', 2)->where('status', 'seated')->get();
                break;
            case 'waiting'://waiting
                $order_ids = OrderTable::where('calling_time', '<>', Null)->pluck('order_id');
                if($order_ids){
                    $order_side_obj = Order::where('pay_flag', '<>', 2)->whereIn('id', $order_ids)->get();}
                else
                    $order_side_obj = collect();
                break;
            case 'booking'://booking
                $order_side_obj = Booked::where('time', '<=',$current_date . " 23:59:59" )
                    ->where('time', '>=',$current_date . " 00:00:00" )
                    ->where('status', 'booking')
                    // ->where('timer_flag', 0)
                    ->orderby('time')->get();
                break;
            default:
                $order_side_obj = Order::where('pay_flag', '<>', 2)->where('status', 'seated')->get();
                break;
        }

        $order_obj = Order::where('pay_flag', '<>', 2)->get();
        $table_obj = Table::get();

        $order_tables = array();

        foreach($order_obj as $order)
        {
            if(count($order->ordertables) > 0){
                foreach ($order->ordertables as $ordertables) {
                    $order_tables[] = $ordertables['table_id'];
                }
            }
        }

        if($status == "booking")
            $order_side_obj = $this->get_book_obj($order_side_obj);
        else
            $order_side_obj = $this->get_order_obj($order_side_obj);

        foreach($table_obj as $table) {
            if(count($table->order) > 0) {
                $table->display_time = date_format(date_create($table->order[0]->time),"h:i A");
            }
            if(count($table->book) > 0) {
                    $table->display_time1 = date_format(date_create($table->book[0]->time),"h:i A");
                    $table->current_time1 = strtotime($table->book[0]->time) - strtotime($this->get_current_time());
            }
        }

        $room_size = Room::find(1);

        return view('reception.seated')->with(compact('order_tables', 'table_obj', 'order_obj', 'order_side_obj', 'room_size', 'status', 'booking_cnt'));
    }

    //Booking part
    public function booking()
    {

        $table_id = request()->table_id;//dd($table_id);
    }

    // create customer part ===========================================================================================
    public function addCustomer()
    {
        $order_obj = Order::get();
        $table_obj = Table::get();
        $order_tables = array();
        $order_get = array();
        $orders = array();
        $table_ids = array();
        $table_id = request()->get('table_id');
        $order_id = request()->get('order_id');
        $status = request()->get('status');
        $customer_name = "";
        foreach($order_obj as $order){
            if(count($order->ordertables) > 0){
                foreach ($order->ordertables as $ordertables) {
                    $order_tables[] = $ordertables['table_id'];
                }
            }
        }
        if($status == "booking")
        {
            if($order_id > 0){
                $order_get = Booked::find($order_id);
                $order_table_obj = BookedTable::where('book_id', $order_id)->get()->toArray();
                foreach ($order_table_obj as $order) {
                    $table_ids[] = $order['table_id'];
                }

                $default_duration_id = $order_get->duration;
            }
            else{
                $default_duration_id = $this->get_default_duration_id();
            }
        }
        else
        {
            if($order_id > 0){
                $order_get = Order::find($order_id);
                $order_table_obj = OrderTable::where('order_id', $order_id)->get()->toArray();
                foreach ($order_table_obj as $order) {
                    $table_ids[] = $order['table_id'];
                }

                $default_duration_id = $order_get->duration;
            }
            else{
                $default_duration_id = $this->get_default_duration_id();
            }
        }


        foreach($table_obj as $table) {
            if(count($table->order) > 0)
                $table->display_time = date_format(date_create($table->order[0]->time),"h:i A");
            if(count($table->book) > 0)
                $table->display_time_book = date_format(date_create($table->book[0]->time),"h:i A");
        }

        if($table_id != 0)
            $table_display_name = $this->get_table_name($table_id);
        else
            $table_display_name = '';

        if($order_id > 0)
            $customer_name = $order_get->customer_name;
        else {
            $today = date('Y-m-d');
            $today_order = Order::where('created_at','>=',$today.' 00:00:00')->orderby('created_at','desc')->get()->first();
            if(empty($today_order)) $customer_name = "Walked-in 00";
            else {
                $customer_name = $today_order->customer_name;
                $last_name = (int)substr($customer_name,-2);
                $last_name++;
                if($last_name<10) {
                    $last_name = '0' . $last_name;
                }
                $customer_name = substr($customer_name,0,-2) . $last_name;
            }

        }

//        dd($table_ids);

        $room_size = Room::find(1);

        return view('reception.addCustomer')->with(compact('order_tables', 'table_ids', 'table_obj', 'order_get', 'table_id', 'order_id', 'orders', 'table_display_name', 'default_duration_id', 'room_size', 'status', 'customer_name'));
    }

    public function store()
    {
        if(request()->get('status') != 'booking')
        {
            if(request()->get('order_id') > 0)  //edit
            {
                $order_obj = Order::find(request()->get('order_id'));
                $order_obj->time = request()->get('time');
                $order_obj->guest = request()->get('guest_number');
                $order_obj->duration = request()->get('duration');
                $order_obj->customer_name = request()->get('customer_name');
                $order_obj->contact_number = request()->get('contact_number');
                $order_obj->email = request()->get('email_address');
                $order_obj->note = request()->get('customer_notes');
                $status = request()->get('status');
                $order_obj->status = $status;
                $order_obj->update();
                OrderTable::where('order_id',request()->get('order_id'))->delete();
                $table_ids = request()->get('table_id');
                $table_id_arr = explode(',', $table_ids);
                $table_name = '';
                foreach ($table_id_arr as $id) {
                    $table_name .= $this->get_table_name($id).'+';
                    $order_table_obj = new OrderTable();
                    $order_table_obj->order_id = request()->get('order_id');
                    $order_table_obj->table_id = $id;
                    $order_table_obj->save();
                }
                $table_name = rtrim($table_name, '+');
                Order::where('id', $order_obj->id)->update(['table_name' => $table_name]);

                broadcast(new TableMoveEvent($table_name));
            }
            else  //add
            {
                $order_obj = new Order();
                $order_obj->time = request()->get('time');
                $order_obj->guest = request()->get('guest_number');
                $order_obj->duration = request()->get('duration');
                $order_obj->customer_name = request()->get('customer_name');
                $order_obj->contact_number = request()->get('contact_number');
                $order_obj->email = request()->get('email_address');
                $order_obj->note = request()->get('customer_notes');
                $status = request()->get('status');
                $order_obj->status = $status;
                $order_obj->calls = 0;
                $order_obj->save();
                $table_ids = request()->get('table_id');
                $table_id_arr = explode(',', $table_ids);
                $table_name = '';
                foreach ($table_id_arr as $id) {
                    $table_name .= $this->get_table_name($id).'+';
                    $order_table_obj = new OrderTable();
                    $order_table_obj->order_id = $order_obj->id;
                    $order_table_obj->table_id = $id;
                    $order_table_obj->save();
                }
                $table_name = rtrim($table_name, '+');
                Order::where('id', $order_obj->id)->update(['table_name' => $table_name]);
            }
        }
        else
        {
            if(request()->get('order_id') > 0)  //edit
            {
                $order_obj = Booked::find(request()->get('order_id'));
                $order_obj->time = request()->get('time');
                $order_obj->guest = request()->get('guest_number');
                $order_obj->duration = request()->get('duration');
                $order_obj->customer_name = request()->get('customer_name');
                $order_obj->contact_number = request()->get('contact_number');
                $order_obj->email = request()->get('email_address');
                $order_obj->note = request()->get('customer_notes');
                $status = request()->get('status');
                $order_obj->status = $status;
                $order_obj->update();
                BookedTable::where('book_id',request()->get('order_id'))->delete();
                $table_ids = request()->get('table_id');
                $table_id_arr = explode(',', $table_ids);
                $table_name = '';
                foreach ($table_id_arr as $id) {
                    $table_name .= $this->get_table_name($id).'+';
                    $order_table_obj = new BookedTable();
                    $order_table_obj->book_id = request()->get('order_id');
                    $order_table_obj->table_id = $id;
                    $order_table_obj->save();
                }
                $table_name = rtrim($table_name, '+');
                Booked::where('id', $order_obj->id)->update(['table_name' => $table_name]);
            }
            else  //add
            {
                $order_obj = new Booked();
                $order_obj->time = request()->get('time');
                $order_obj->guest = request()->get('guest_number');
                $order_obj->duration = request()->get('duration');
                $order_obj->customer_name = request()->get('customer_name');
                $order_obj->contact_number = request()->get('contact_number');
                $order_obj->email = request()->get('email_address');
                $order_obj->note = request()->get('customer_notes');
                $status = request()->get('status');
                $order_obj->status = $status;
                $order_obj->calls = 0;
                $order_obj->save();
                $table_ids = request()->get('table_id');
                $table_id_arr = explode(',', $table_ids);
                $table_name = '';
                foreach ($table_id_arr as $id) {
                    $table_name .= $this->get_table_name($id).'+';
                    $order_table_obj = new BookedTable();
                    $order_table_obj->book_id = $order_obj->id;
                    $order_table_obj->table_id = $id;
                    $order_table_obj->save();
                }
                $table_name = rtrim($table_name, '+');
                Booked::where('id', $order_obj->id)->update(['table_name' => $table_name]);
            }
        }

        return redirect()->route('reception.seated', ['status'=>$status]);
    }

    //ready to pay part =============================================================================
    public function ready_to_pay() {

        $order_obj = Order::where('pay_flag', 1)->get();
        $table_obj = Table::get();
        $order_obj = $this->get_order_obj($order_obj);
        foreach($order_obj as $orderObj) {
            $count_calling = 0;
            foreach($orderObj->ordertables as $orderTable) {
                if($orderTable->calling_time != Null)
                    $count_calling +=1;
            }
            $orderObj->calling_count = $count_calling;
        }
        return view('reception.ready_to_pay')->with(compact('order_obj'));
    }

    //view calling screen ===========================================================================
    public function view_calling() {

        if(request()->table_id != 0) {

//            $table_id = request()->table_id;
//            $now = $this->get_current_time();
//            $order_id = OrderTable::where('table_id', $table_id)->pluck('order_id');
//            OrderTable::where('order_id', $order_id)->update(['attend_time' => $now]);

            $table_id = request()->table_id;
            $order_id = OrderTable::where('table_id', $table_id)->pluck('order_id');
            OrderTable::where('order_id', $order_id)->update(['calling_time' => Null]);

            broadcast(new AttendEvent($order_id));
            $count_notification = $this->CountNotification();
            $count_notification['selected'] = 3;
            $count_notification['table_id'] = $table_id;
            broadcast(new NotificationEvent($count_notification));
        }

        $order_obj = collect();
        $order_ids = OrderTable::where('calling_time', '<>', null)->orderBy('calling_time', 'DESC')->pluck('order_id');
        if(count($order_ids) > 0) {
            $order_id_text = $this->get_order_ids($order_ids);
            $order_obj = Order::where('pay_flag', '<>', 2)->whereIn('id', $order_ids)->orderByRaw('FIELD (id, ' . implode(',', $order_id_text) . ') DESC')->get();
            $order_obj = $this->get_order_obj($order_obj);//dd($order_obj);
            foreach ($order_obj as $orderObj) {
                foreach ($orderObj->ordertables as $orderTable) {
                    if ($orderTable->calling_time != Null) {
                        $orderObj->calling_time = $orderTable->calling_time;
                        if ($orderTable->attend_time != Null) {
                            $orderObj->attend_time = $orderTable->attend_time;
                            $orderObj->attended_time = intval(strtotime($orderObj->attend_time) - strtotime($orderObj->calling_time));
                        } else {
                            $orderObj->attend_time = Null;
                        }
                        $orderObj->calling_table_id = $orderTable->table_id;
                    }
                }
            }
        }

//        dd($order_obj);
        if(count($order_obj) > 0)
            return view('reception.view_calling')->with(compact('order_obj'));
        else
            echo('<script>$("#myModal").modal("hide");</script>');

    }

    //view review screen==============================================================================
    public function view_review() {

        $order_obj = Order::where('pay_flag', '<>', 2)->where('review', '!=', Null)->get();
        $table_obj = Table::get();
        $order_obj = $this->get_order_obj($order_obj);
        foreach($order_obj as $orderObj) {
            $count_calling = 0;
            foreach($orderObj->ordertables as $orderTable) {
                if($orderTable->calling_time != Null)
                    $count_calling +=1;
            }
            $orderObj->calling_count = $count_calling;
        }
        return view('reception.view_review')->with(compact('order_obj'));
    }

    //view note screen=================================================================================
    public function view_note() {

        $order_obj = Order::where('pay_flag', '<>', 2)->where('note', '!=', Null)->get();
        $table_obj = Table::get();
        $order_obj = $this->get_order_obj($order_obj);
        foreach($order_obj as $orderObj) {
            $count_calling = 0;
            foreach($orderObj->ordertables as $orderTable) {
                if($orderTable->calling_time != Null)
                    $count_calling +=1;
            }
            $orderObj->calling_count = $count_calling;
        }
//        dd($order_obj[0]->ordertables);
        return view('reception.view_note')->with(compact('order_obj', 'count_calling'));
    }

    //accounting part =======================================================================================================
    public function accounting() {

        $order_id = request()->order_id;
        $order_data = Order::select('customer_name', 'time', 'duration', 'guest', 'status')->where('id', $order_id)->get()->first();
        $customer_name = $order_data->customer_name;
        $time = $order_data->time;
        $starting_time = date_format(date_create($time),"h:i A");
        $duration_time = $this->customers[$order_data->duration];
        $duration = $order_data->duration;
        $during_time = $this->get_during_minutes($time);
        $guest = $order_data->guest;
        $status = $order_data->status;

        $table_ids = OrderTable::where('order_id', $order_id)->pluck('table_id');
        $table_name = array();
        foreach($table_ids as $table_id) {
            $tb_name = $this->get_table_name($table_id);//dd($tb_name);
            $table_name[] = $tb_name;
        }

        $order_dishes = OrderDish::where('order_id', $order_id)->get();

        $total = 0;
        foreach($order_dishes as $order_dish) {

            $dish_list = Dish::select('name_en')->where('id', $order_dish->dish_id)->get()->first();
            $order_dish->dish_name_en = $dish_list->name_en;

            $order_dish->options = $order_dish->Order_Option()->get();
            $items_price = 0;
            foreach ($order_dish->options as $option) {
//                $option->option_name = Option::where('id', $option->option_id)->pluck('name')->first();
//                $option_items = Item::select('name', 'price')->where('id', $option->item_id)->get()->first();
//                $option->item_name = $option_items->name;
//                $items_price += $option_items->price;

                if($option->option_id)
                    $option->option_name = Option::where('id', $option->option_id)->pluck('name')->first();
                else
                    $option->option_name = '';

                if($option->item_id) {

                    $option_items = Item::select('name', 'price')->where('id', $option->item_id)->get()->first();
                    $option->item_name = $option_items['name'];
                    $items_price += $option_items['price'];
                }
                else {
                    $option->item_name = '';
                    $items_price = 0;
                }

            }
            $order_dish->each_price = $order_dish->dish_price + $items_price;
            $order_dish->sub_total = $order_dish->each_price * $order_dish->count;
            $total += $order_dish->sub_total;
        }

        $gst = Receipt::profile()->gst;
        $gst_price = $total * $gst / 100;
        $without_gst_price = $total - $gst_price;

        $payment_method = Payment::orderBy('sort')->get();

        return view('reception.accounting')->with(compact('order_id', 'customer_name', 'starting_time', 'duration_time', 'duration', 'time', 'during_time', 'guest', 'status', 'table_name', 'order_dishes', 'total', 'gst_price', 'without_gst_price', 'gst', 'payment_method'));
    }

    public function cancel_bill() {

        $order_id = request()->order_id;
        $order = Order::findOrFail($order_id);
        $order->pay_flag = 0;
        $order->save();

        $count_notification = $this->CountNotification();
        broadcast(new NotificationEvent($count_notification));

        $table_id = OrderTable::where('order_id', $order_id)->pluck('table_id')->first();
        broadcast(new FinishAndPayEvent($order_id, $table_id));

        return $this->ready_to_pay();
    }

    public function amend() {

        $order_id = request()->order_id;
        $order_dish_id = request()->order_dish_id;
        $count = OrderDish::where('id', $order_dish_id)->pluck('count')->first();

        $categories = Category::orderby('order')->get();
        $dishes = array();
        $temp_dishes = array();
        /*if(count($categories) > 0){
            $category_record = Category::find($categories[0]['id']);
            $dishes = $category_record->dishes;
            foreach($dishes as $dish){
                $dish->discount = ($this->get_discount($dish->id))?($this->get_discount($dish->id)):'';
                $dish->options = $dish->options()->get();
                foreach($dish->options as $option){
                    $option->item = Item::where('option_id', $option->id)->get();
                }
            }
        }*/

        $order = Order::find(request()->order_id);

        if(empty($order->menu_type))    $menu_type = 'Menu';
        else $menu_type = $order->menu_type;

        $category_all = array();
        if(count($categories) > 0){
            $i = 0;
            foreach ($categories as $category) {
                $dishes = [];
                if($category->has_subs != 1 && empty($category->parent_id)) {

                    $dishes = $this->get_dishes($category,$order->time,$menu_type);
                    if(!empty($dishes) &&  count($dishes) > 0 ) {
                        $category_all[$category->id] = $category;
                    }

                    if($i == 0) $temp_dishes = $dishes;
                    if(!empty($dishes)) $i++;
                }
                elseif($category->has_subs == 1) {
                    $main_sub_categories = array();
                    $sub_categories = Category::where('parent_id', $category->id)->orderby('order')->get();

                    if(!empty($sub_categories) &&  count($sub_categories) > 0 ) {

                        foreach ($sub_categories as $sub_category) {

                            $sub_dishes = $this->get_dishes($sub_category,$order->time,$menu_type);
                            //dd($menu_type);
                            if(!empty($sub_dishes) && count($sub_dishes) > 0) {
                                $dishes = $sub_dishes;
                                array_push($main_sub_categories ,$sub_category);
                            }

                            if($i == 0) $temp_dishes = $dishes;
                            if(!empty($dishes)) $i++;
                        }
                    }
                    else {

                        $dishes = $this->get_dishes($category,$order->time,$menu_type);

                        if($i == 0) $temp_dishes = $dishes;
                        if(!empty($dishes)) $i++;
                    }
                    if(!empty($dishes) && count($dishes) > 0 ) {
                        $category_all[$category->id] = $category;
                        $category_all[$category->id]['children'] = $main_sub_categories;
                    }
                }

            }
        }

        $dishes = $temp_dishes;
        return view('reception.add_item')->with(compact('order_id', 'category_all', 'dishes', 'order_dish_id', 'count'));

    }

    public function add_item() {

        $select_list = request()->select_list;
        $items_id = array();
        for($i=0;$i<count($select_list);$i++) {
            $selected_item = explode(":", $select_list[$i]);
            $dish_id = $selected_item[0];
            if($selected_item[1] != 0) {
                array_push($items_id, $selected_item[1]);
            }
        }
        //dd(1);
        $order_id = request()->order_id;
        $count = request()->qty;
        $dish = Dish::find($dish_id);
        //save to order_dish_match table
        $order_dish = new OrderDish();
        $order_dish->order_id = $order_id;
        $order_dish->dish_id = $dish_id;
        $order_dish->count = $count;

        $order = Order::find($order_id);
        $dish = Dish::find($dish_id);

        $order_dish->dish_price = ($this->get_discount($dish_id))?($this->get_discount($dish_id)):$dish->price;

        if ($order->duration != 5 && $dish->isBuffet()) {
            $order_dish->dish_price = 0;
        }

        $order_dish->ready_flag = 0;
        $order_dish->save();

        //save to order_option_match table
        $order_dish_id = $order_dish->id;
        $items_price = 0;

        //if(count($items_id) > 0) {
            foreach($items_id as $item_id) {
                $order_option = new OrderOption();
                $order_option->order_dish_id = $order_dish_id;
                $order_option->option_id = Item::where('id',$item_id)->pluck('option_id')->first();
                $order_option->item_id = $item_id;
                $order_option->item_price = Item::where('id',$item_id)->pluck('price')->first();
                $items_price += $order_option->item_price;
                $order_option->save();
            }
        //}

        //save total price
        $order_dish = OrderDish::find($order_dish_id);
        $order_dish->total_price = ($order_dish->dish_price + $items_price) * $count;
        $order_dish->save();

        //display changed data
        $order_dishes = OrderDish::where('order_id', $order_id)->get();

        foreach($order_dishes as $order_dish) {

            $dish_list = Dish::select('name_en')->where('id', $order_dish->dish_id)->get()->first();
            $order_dish->dish_name_en = $dish_list->name_en;

            $order_dish->options = $order_dish->Order_Option()->get();
            $items_price = 0;
            foreach ($order_dish->options as $option) {

                if($option->option_id)
                    $option->option_name = Option::where('id', $option->option_id)->pluck('name')->first();
                else
                    $option->option_name = '';

                if($option->item_id) {

                    $option_items = Item::select('name', 'price')->where('id', $option->item_id)->get()->first();
                    $option->item_name = $option_items['name'];
                    $items_price += $option_items['price'];
                }
                else {
                    $option->item_name = '';
                    $items_price = 0;
                }

            }
            $order_dish->each_price = $order_dish->dish_price + $items_price;
            $order_dish->sub_total = $order_dish->each_price * $order_dish->count;
        }

        //broadcast to kitchen
        $added_dish = $this->get_added_dish($dish, $order_id, $order_dish_id);
        broadcast(new KitchenEvent($added_dish));

//        return (string)view('reception.item_list', compact('order_dishes'))->render();
        return $order_id;
    }

    public function change_count()
    {
        $order_id = request()->order_id;
        $order_dish_id = request()->order_dish_id;
        $amend_count = request()->change_count;
        $count = OrderDish::where('id', $order_dish_id)->pluck('count')->first() + $amend_count;
        $amend_time = $this->get_current_time();

        OrderDish::where('id', $order_dish_id)->update(['amend_count' => $amend_count, 'count' => $count, 'amend_time'=>$amend_time]);

        $items_price = OrderOption::where('order_dish_id', $order_dish_id)->sum('item_price');

        //save total price
        $order_dish = OrderDish::find($order_dish_id);
        $order_dish->total_price = ($order_dish->dish_price + $items_price) * $count;
        $order_dish->save();

        //broadcast to kitchen
        $dish_id = OrderDish::where('id', $order_dish_id)->pluck('dish_id')->first();
        $group_id = Dish::where('id', $dish_id)->pluck('group_id');
        broadcast(new ChangeCountEvent($group_id));

//        return $added_dish;

    }

    public function dish_list()
    {
        $category = Category::find(request()->category);
        $order = Order::find(request()->order_id);
        $menu_time = $order->time;
        $menu_type = $order->menu_type;

        $dishes = $this->get_dishes($category,$menu_time,$menu_type);

        return (string)view('reception.dish_list', compact('dishes'))->render();
    }

    public function tip() {

        return view('reception.tip');
    }

    public function discount() {

        return view('reception.discount');
    }

    public function pay() {

        $order_id = request()->order_id;
//        $count = OrderPay::where('order_id', $order_id)->get()->count();//dd($count);
//        if($count > 0) {
//            $orderPay = OrderPay::where('order_id', $order_id)->get()->first();
//            $orderPay->tip = request()->get('tip');
//            $orderPay->sub_total = request()->get('sub_total');
//            $orderPay->discount = request()->get('discount');
//            $orderPay->total = request()->get('total');
//            $orderPay->without_gst = request()->get('without_gst');
//            $orderPay->gst = request()->get('gst');
//            $orderPay->pay_method = request()->get('pay_method');
//            $orderPay->balance = request()->get('balance');
//            $orderPay->amount = request()->get('amount');
//            $orderPay->change = request()->get('change');
//            $orderPay->update();
//        } else {

        $orderPay = new OrderPay();
        $orderPay->order_id = request()->get('order_id');
        $orderPay->tip = request()->get('tip');
        $orderPay->sub_total = request()->get('sub_total');
        $orderPay->discount = request()->get('discount');
        $orderPay->total = request()->get('total');
        $orderPay->without_gst = request()->get('without_gst');
        $orderPay->gst = request()->get('gst');
        $orderPay->pay_method = request()->get('pay_method');
        $orderPay->balance = request()->get('balance');
        $orderPay->amount = request()->get('amount');
        $orderPay->change = request()->get('change');
        $orderPay->save();
//        OrderPay::where('order_id', Null)->delete();
//        }

        Order::where('id', $order_id)->update(['pay_flag' => 2]);

        $login_info = OrderTable::where('order_id', $order_id)->get()->first();;
        $login_table = Table::where('id',$login_info->table_id)->get()->first();;
        session(['login_table_name' => $login_table->name]);
        OrderTable::where('order_id', $order_id)->delete();

        //show pay finish status on customer
        $pay_status = 'pay_'.$order_id;

        broadcast(new PayEvent($pay_status));

//        /return 'success';
        return redirect()->route('reception.seated', ['status'=>'seated']);
    }

    public function account_print() {

        $profile = Receipt::profile();

        $printerIp = $profile->printer_ip;
        $printerPort = 9100;

        $logo_image_name = $profile->logo_image;
        $title = "TAX INVOICE";
        $address = $profile->address;
        $tel = "TEL : ".$profile->phone;
        $abn = "ABN : ".$profile->abn;
        $shop_name = $profile->shop_name;

        $order_id = request()->get('order_id');
        $table_ids = OrderTable::where('order_id', $order_id)->pluck('table_id');
        $table_name = "";
        foreach($table_ids as $table_id) {
            $table_name .= $this->get_table_name($table_id).'+';
        }
        $table_name = rtrim($table_name, '+');
        $guest = Order::where('id', $order_id)->pluck('guest')->first();
        $table = "   Table  : ".$table_name." (".$guest." Guests)";
        $current_date = date('d F Y');
        $current_time = date('H:i:s');
        $day = date("D", strtotime($current_date));
        $date = "   Date   : ".$day.", ".$current_date.", ".$current_time;

        $order_dishes = request()->get('order_dishes');
        $tip = request()->get('tip');
        $sub_total = request()->get('sub_total');
        $discount = request()->get('discount');
        $total = request()->get('total');
        $without_gst = request()->get('without_gst');
        $gst = request()->get('gst');
        $pay_method = request()->get('pay_method');
        $balance = request()->get('balance');
        $amount = request()->get('amount');
        $change = request()->get('change');

        //print part
        $connector = new NetworkPrintConnector($printerIp, $printerPort);
        $printer = new Printer($connector);

        try {
            //Print top logo
            $printer->setJustification(Printer::JUSTIFY_CENTER);
//            $logo_image = EscposImage::load("receipt/$logo_image_name", false);
            $logo_image = EscposImage::load("receipt/logo.png");
            $printer->graphics($logo_image, 3 | 2);

            $printer->setFont(Printer::FONT_A);

            $printer->setTextSize(2,1);//1~8 of width and height, can change textsize
            $printer->setEmphasis(true);
            $printer->text("$title\n");

            $printer->setTextSize(1,1);

            $printer->setEmphasis(false);
            $printer->text("$address\n");
            $printer->text("$tel\n");
            $printer->text("$abn\n");

            $printer->setEmphasis(true);
            $printer->text("------------------------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(false);
            $printer->text("$table\n");
            $printer->text("$date\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("------------------------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $printer->setEmphasis(false);
            $printer->text("Description                 Price   Qty Total   \n");
            $printer->text("................................................\n");

            foreach($order_dishes as $order_dish) {

                $dish_len = strlen($order_dish['dish_name_en']);
                $mod = fmod($dish_len, 23);
                if($mod > 0)
                    $line_count = (int)($dish_len / 23) + 1;
                else
                    $line_count = (int)($dish_len / 23);

                for($i=1;$i<=$line_count;$i++) {
                    if($dish_len > 23) {
                        if($i==1) {
                            $printer->text(substr($order_dish['dish_name_en'], 0, 23).'     '.str_pad('¥'.sprintf('%0.2f', $order_dish['each_price']), 8, ' ', STR_PAD_RIGHT)
                                .str_pad($order_dish['count'], 4, ' ', STR_PAD_RIGHT).str_pad('¥'.sprintf('%0.2f', $order_dish['sub_total']), 8, ' ', STR_PAD_RIGHT));
                        } else {
                            $printer->text("\n");
                            $printer->text(substr($order_dish['dish_name_en'], ($i-1)*23, 23).'     ');
                        }
                    } else {
                        $printer->text(str_pad($order_dish['dish_name_en'], 28, ' ', STR_PAD_RIGHT).str_pad('¥'.sprintf('%0.2f', $order_dish['each_price']), 8, ' ', STR_PAD_RIGHT)
                            .str_pad($order_dish['count'], 4, ' ', STR_PAD_RIGHT).str_pad('¥'.sprintf('%0.2f', $order_dish['sub_total']), 8, ' ', STR_PAD_RIGHT));
                    }

                }

                $printer->text("\n");
                // loop
//                $line = sprintf('%-40.40s %7.2f %5.0f %7.2f', $order_dish['dish_name_en'], $order_dish['each_price'], $order_dish['count'], $order_dish['sub_total']);
//                $printer->text($line."\n");
                // end loop
            }

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("------------------------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(false);
            $printer -> text(new print_table1('Sub Total(Inc GST)', '¥'.$sub_total));
            $printer -> text(new print_table1('GST', '¥'.$gst));

            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text(new print_table1('Grand Total', '¥'.$total));
            $printer -> selectPrintMode();

            $printer -> text(new print_table1('Payment', '¥'.$amount));
            $printer -> text(new print_table1('('.$pay_method.')', '¥'.$amount));
            $printer -> text(new print_table1('Change Due', '¥'.$change));

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("------------------------------------------------\n");

            $printer->setEmphasis(false);
            $printer->text("Thank you for choosing\n");

            $printer->setTextSize(2,1);
            $printer->setEmphasis(true);
            $printer->text("$shop_name\n");

            $printer->setTextSize(1,1);
            $printer->setEmphasis(false);
            $printer->text("Operator Reception / No : JB10CB10\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("------------------------------------------------\n");

            $printer->cut();

        } finally {
            $printer -> close();
        }

        return $order_dishes;
    }

    //edit order part ==================================================================================================
    public function editOrder() {

        $order_id = request()->order_id;
        $status = request()->status;
        $order_side_obj = request()->order_side_obj;
        if($status == "booking")
            $booking_order = $this->get_booking_order1($order_id);
        else
            $booking_order = $this->get_booking_order($order_id);
        return (string)view('reception.editOrder', compact('booking_order','status','order_side_obj'))->render();
        //return view('reception.editOrder')->with(compact('booking_order','status','order_side_obj'));
    }

    public function edit_note_review() {

        $order_id = request()->order_id;
        $note = request()->note;
        $review_type = request()->review_type;
        $review = request()->review;
        Order::where('id', $order_id)->update(['note' => $note, 'review_type' => $review_type, 'review' => $review]);

        $count_notification = $this->CountNotification();
        broadcast(new NotificationEvent($count_notification));

    }

    //attend calling in booking order edit part
    public function attend_book() {

        $order_id = request()->order_id;
//        $now = $this->get_current_time();
//        OrderTable::where('order_id', $order_id)->update(['attend_time' => $now]);
        OrderTable::where('order_id', $order_id)->update(['calling_time' => Null]);

        $orderid[0] = $order_id;

        broadcast(new AttendEvent($orderid));
        $count_notification = $this->CountNotification();
        broadcast(new NotificationEvent($count_notification));

    }

    public function editOrder1() {

        $order_id = request()->order_id;

        $order_dishes = OrderDish::withTrashed()->where('order_id', $order_id)->get();

        $total = 0;
        foreach($order_dishes as $order_dish) {

            $dish_list = Dish::select('name_en')->where('id', $order_dish->dish_id)->get()->first();
            $order_dish->dish_name = Dish::where('id', $order_dish->dish_id)->pluck('name_en')->first();
            $order_dish->dish_name_en = $dish_list->name_en;

            $order_dish->options = $order_dish->Order_Option()->get();
            $items_price = 0;
            foreach ($order_dish->options as $option) {

                if($option->option_id) {
                    $option->option_name = Option::where('id', $option->option_id)->pluck('display_name_en')->first();
                }
                else
                    $option->option_name = '';

                if($option->item_id) {

                    $option_items = Item::select('name', 'price')->where('id', $option->item_id)->get()->first();
                    $option->item_name = $option_items['name'];
                    $items_price += $option_items['price'];
                }
                else {
                    $option->item_name = '';
                    $items_price = 0;
                }

            }
            $order_dish->each_price = $order_dish->dish_price + $items_price;
            $order_dish->sub_total = $order_dish->each_price * $order_dish->count;
            $total += $order_dish->sub_total;
        }

//        dd($order_dishes);
        return (string)view('reception.editOrder1', compact('order_dishes', 'order_id'))->render();
    }

    public function removeOrderItem() {
        $order_dish_id = request()->order_dish_id;
        OrderDish::find($order_dish_id)->delete();

        $order_id = request()->order_id;
        $order_dishes = OrderDish::withTrashed()->where('order_id', $order_id)->get();

        $total = 0;
        foreach($order_dishes as $order_dish) {

            $dish_list = Dish::select('name_en')->where('id', $order_dish->dish_id)->get()->first();
            $order_dish->dish_name = Dish::where('id', $order_dish->dish_id)->pluck('name_en')->first();
            $order_dish->dish_name_en = $dish_list->name_en;

            $order_dish->options = $order_dish->Order_Option()->get();
            $items_price = 0;
            foreach ($order_dish->options as $option) {

                if($option->option_id) {
                    $option->option_name = Option::where('id', $option->option_id)->pluck('display_name_en')->first();
                }
                else
                    $option->option_name = '';

                if($option->item_id) {

                    $option_items = Item::select('name', 'price')->where('id', $option->item_id)->get()->first();
                    $option->item_name = $option_items['name'];
                    $items_price += $option_items['price'];
                }
                else {
                    $option->item_name = '';
                    $items_price = 0;
                }

            }
            $order_dish->each_price = $order_dish->dish_price + $items_price;
            $order_dish->sub_total = $order_dish->each_price * $order_dish->count;
            $total += $order_dish->sub_total;
        }

        return (string)view('reception.editOrder1', compact('order_dishes', 'order_id'))->render();
    }

    public function order_info_edit() {

        $order_qty_info = request()->order_qty_info;
        $id = '';
        $qty = '';
        $sub_total = '';
        foreach($order_qty_info as $item)
        {
            $item_info = explode('-', $item);
            $id = $item_info[0];
            $qty = $item_info[1];
            $sub_total = substr($item_info[2], 1);

            $count = OrderDish::where('id', $id)->pluck('count')->first();
            $amend_time = $this->get_current_time();
            if($count != $qty) {
                $amend_count = $qty - $count;
                $count = $qty;
                OrderDish::where('id', $id)->update(['amend_count' => $amend_count, 'count' => $count, 'amend_time'=>$amend_time]);

                $items_price = OrderOption::where('order_dish_id', $id)->sum('item_price');

                //save total price
                $order_dish = OrderDish::find($id);
                $order_dish->total_price = ($order_dish->dish_price + $items_price) * $count;
                $order_dish->save();
            }

            //broadcast to kitchen
            $dish_id = OrderDish::where('id', $id)->pluck('dish_id');
            $group_id = Dish::where('id', $dish_id)->pluck('group_id');
            broadcast(new ChangeCountEvent($group_id));
        }

        return $order_qty_info;
    }

    //========================================================================
    //get order info
    public function get_order_obj($order_obj) {

        $order_tables = array();

        foreach($order_obj as $order)
        {
            $table_display_names = array();
            $table_order_names = array();
            if(count($order->ordertables) > 0){
                foreach ($order->ordertables as $ordertables) {
                    $order_tables[] = $ordertables['table_id'];
                    $table_display_names[] = $this->get_table_name($ordertables['table_id']);
                }
                $table_order_names[] = $this->get_table_name($order->ordertables[0]['table_id']);//get first table only in order
            }
            $order->display_time = date_format(date_create($order->time),"h:i A");
            $order->table_display_names = $table_display_names;
            $order->table_order_names = $table_order_names;
        }
//      dd($order_obj);
        return $order_obj;
    }

    public function get_book_obj($order_obj) {

        $order_tables = array();

        foreach($order_obj as $order)
        {
            $table_display_names = array();
            $table_order_names = array();
            if(count($order->bookedtables) > 0){
                foreach ($order->bookedtables as $ordertables) {
                    $table_display_names[] = $this->get_table_name($ordertables['table_id']);
                }
                $table_order_names[] = $this->get_table_name($order->bookedtables[0]['table_id']);//get first table only in order
            }
            $order->display_time = date_format(date_create($order->time),"h:i A");
            $order->table_display_names = $table_display_names;
            $order->table_order_names = $table_order_names;

        }

        return $order_obj;
    }

    public function get_order_ids($ids) {

        $order_ids = '';
        foreach($ids as $order_id) {
            $order_ids .= $order_id.',';
        }
        $order_id_text = [substr($order_ids, 0, -1)];
        return $order_id_text;
    }

    //get book order info ==========================================================================================
    public function get_booking_order($order_id) {

        $order_data = Order::where('id', $order_id)->get()->first();
        $customer_name = $order_data->customer_name;
        $starting_time = $order_data->time;
        $date = substr($starting_time, 0, 10);
        $time = date_format(date_create($starting_time),"h:i A");
        $duration = $order_data->duration;
        $duration_time = $this->customers[$duration];
        $guest = $order_data->guest;
        $note = $order_data->note;
        $review_type = $order_data->review_type;
        $review = $order_data->review;
        $pay_flag = $order_data->pay_flag;
        $status = $order_data->status;
        $additional = $order_data->additional;

        $order_table = OrderTable::where('order_id', $order_id)->get()->first();
        $calling_time = $order_table->calling_time;
        $attend_time = $order_table->attend_time;
        $table_id = $order_table->table_id;

        $table_ids = OrderTable::where('order_id', $order_id)->pluck('table_id');
        $table_name = array();
        foreach($table_ids as $table_id) {
            $tb_name = $this->get_table_name($table_id);//dd($tb_name);
            $table_name[] = $tb_name;
        }

        //============
        $booking_order = collect();
        $booking_order->order_id = $order_id;
        $booking_order->customer_name = $customer_name;
        $booking_order->starting_time = $starting_time;
        $booking_order->date = $date;
        $booking_order->time = $time;
        $booking_order->duration = $duration;
        $booking_order->duration_time = $duration_time;
        $booking_order->guest = $guest;
        $booking_order->note = $note;
        $booking_order->review_type = $review_type;
        $booking_order->review = $review;
        $booking_order->table_name = $table_name;
        $booking_order->pay_flag = $pay_flag;
        $booking_order->calling_time = $calling_time;
        $booking_order->attend_time = $attend_time;
        $booking_order->table_id = $table_id;
        if($attend_time != null)
            $booking_order->attended_time = intval(strtotime($booking_order->attend_time)-strtotime($booking_order->calling_time));
        $booking_order->status = $status;
        $booking_order->additional = $additional;

        return $booking_order;
    }

    public function get_booking_order1($order_id) {

        $order_data = Booked::where('id', $order_id)->get()->first();
        $customer_name = $order_data->customer_name;
        $starting_time = $order_data->time;
        $date = substr($starting_time, 0, 10);
        $time = date_format(date_create($starting_time),"h:i A");
        $duration = $order_data->duration;
        $duration_time = $this->customers[$duration];
        $guest = $order_data->guest;
        $note = $order_data->note;
        $review_type = $order_data->review_type;
        $review = $order_data->review;
        $pay_flag = $order_data->pay_flag;
        $status = $order_data->status;
        $additional = $order_data->additional;

        $order_table = BookedTable::where('book_id', $order_id)->get()->first();
        $calling_time = $order_table->calling_time;
        $attend_time = $order_table->attend_time;
        $table_id = $order_table->table_id;

        $table_ids = BookedTable::where('book_id', $order_id)->pluck('table_id');
        $table_name = array();
        foreach($table_ids as $table_id) {
            $tb_name = $this->get_table_name($table_id);//dd($tb_name);
            $table_name[] = $tb_name;
        }

        //============
        $booking_order = collect();
        $booking_order->order_id = $order_id;
        $booking_order->customer_name = $customer_name;
        $booking_order->starting_time = $starting_time;
        $booking_order->date = $date;
        $booking_order->time = $time;
        $booking_order->duration = $duration;
        $booking_order->duration_time = $duration_time;
        $booking_order->guest = $guest;
        $booking_order->note = $note;
        $booking_order->review_type = $review_type;
        $booking_order->review = $review;
        $booking_order->table_name = $table_name;
        $booking_order->pay_flag = $pay_flag;
        $booking_order->calling_time = $calling_time;
        $booking_order->attend_time = $attend_time;
        $booking_order->table_id = $table_id;
        $booking_order->additional = $additional;
        if($attend_time != null)
            $booking_order->attended_time = intval(strtotime($booking_order->attend_time)-strtotime($booking_order->calling_time));
        $booking_order->status = $status;

        return $booking_order;
    }

    public function finish_pay() {

        $order_id = request()->order_id;
        $order = Order::findOrFail($order_id);

        if($order->menu_type == 'Menu') {
            $chk_ready = OrderDish::where('order_id', $order_id)->where('ready_flag', 0)->get()->count();
            if($chk_ready > 0) {
                return [
                    "error" => true,
                    "message" => "未提供の料理があります。注文状況を確認してください。"
                ];
            } else {

                $order->pay_flag = 1;
                $order->save();

                //show count_notification on reception screen
                $count_notification = $this->CountNotification();
                broadcast(new NotificationEvent($count_notification));

                $table_id = OrderTable::where('order_id', $order_id)->pluck('table_id')->first();
                broadcast(new FinishAndPayEvent($order_id, $table_id));

                $booking_order = $this->get_booking_order($order_id);
                return (string)view('reception.editOrder_pay', compact('booking_order'))->render();
            }

        } elseif($order->menu_type == 'TakeawayMenu') {
            $order->pay_flag = 1;
            $order->save();

            //show count_notification on reception screen
            $count_notification = $this->CountNotification();
            broadcast(new NotificationEvent($count_notification));

            $table_id = OrderTable::where('order_id', $order_id)->pluck('table_id')->first();
            broadcast(new FinishAndPayEvent($order_id, $table_id));

            $booking_order = $this->get_booking_order($order_id);
            return (string)view('reception.editOrder_pay', compact('booking_order'))->render();
        }

    }

    public function get_dishes($category,$menu_time,$menu_type) {
        $time = substr($menu_time,11,5);
        $date = date('d M Y', strtotime($menu_time));

        $holiday = Timeslot::find(9);

        if($holiday->day_on == 1)   $chk_holiday = Holiday::where('holiday_date',$date)->get();
        else $chk_holiday = [];

        if(count($chk_holiday) > 0)
        {
            $result = $this->get_time_slot($holiday,$time,$menu_type);
        }
        else
        {
            $week = date('w', strtotime($menu_time));
            switch($week)
            {
                case 0:
                    $timeslot = Timeslot::find(8);
                    break;
                case 1:
                    $timeslot = Timeslot::find(2);
                    break;
                case 2:
                    $timeslot = Timeslot::find(3);
                    break;
                case 3:
                    $timeslot = Timeslot::find(4);
                    break;
                case 4:
                    $timeslot = Timeslot::find(5);
                    break;
                case 5:
                    $timeslot = Timeslot::find(6);
                    break;
                case 6:
                    $timeslot = Timeslot::find(7);
                    break;

            }

            $day_on = $timeslot->day_on;

            if( $day_on == 1 )
            {
                $result = $this->get_time_slot($timeslot,$time,$menu_type);

            }
            else{
                $timeslot1 = Timeslot::find(1);
                $result = $this->get_time_slot($timeslot1,$time,$menu_type);

            }

        }


        if( !empty($result))
        {
            $dishes = $category->eat_dishes($result);
            foreach($dishes as $dish){
                $dish->discount = ($this->get_discount($dish->id))?($this->get_discount($dish->id)):'';
                $dish->options = $dish->options()->get();
                foreach($dish->options as $option){
                    $option->item = Item::where('option_id', $option->id)->get();
                }
            }
        }
        else
//            $dishes = "";
            $dishes = [];

        return $dishes;
    }

    public function get_eat_time($eat_time)
    {
        if( substr($eat_time,-2) == "AM" ){
            if( substr($eat_time,0,2) == 12 ){
                $last_time = substr($eat_time,2,3);
                $eat_time = '00' . $last_time;
            }
            else{
                $eat_time = substr($eat_time,0,5);
            }

        }
        else{
            if( substr($eat_time,0,2) == 12 ){
                $last_time = substr($eat_time,2,3);
                $eat_time = '12' . $last_time;
            }
            else{
                $first_time = (int)substr($eat_time,0,2);
                $first_time += 12;
                $last_time = substr($eat_time,2,3);
                $eat_time = $first_time . $last_time;
            }

        }

        return $eat_time;
    }

    public function get_time_slot($timeslot,$time,$menu_type)
    {
        $breakfast_time_starts = $timeslot->morning_starts;
        $breakfast_time_starts = $this->get_eat_time($breakfast_time_starts);
        $breakfast_time_ends = $timeslot->morning_ends;
        $breakfast_time_ends = $this->get_eat_time($breakfast_time_ends);

        $lunch_time_starts = $timeslot->lunch_starts;
        $lunch_time_starts = $this->get_eat_time($lunch_time_starts);
        $lunch_time_ends = $timeslot->lunch_ends;
        $lunch_time_ends = $this->get_eat_time($lunch_time_ends);

        $tea_time_starts = $timeslot->tea_starts;
        $tea_time_starts = $this->get_eat_time($tea_time_starts);
        $tea_time_ends = $timeslot->tea_ends;
        $tea_time_ends = $this->get_eat_time($tea_time_ends);

        $dinner_time_starts = $timeslot->dinner_starts;
        $dinner_time_starts = $this->get_eat_time($dinner_time_starts);
        $dinner_time_ends = $timeslot->dinner_ends;
        $dinner_time_ends = $this->get_eat_time($dinner_time_ends);

        $latenight_time_starts = $timeslot->latenight_starts;
        $latenight_time_starts = $this->get_eat_time($latenight_time_starts);
        $latenight_time_ends = $timeslot->latenight_ends;
        $latenight_time_ends = $this->get_eat_time($latenight_time_ends);

        $time_chk[0] = "";
        $time_chk[1] = "";
        if( $time >= $breakfast_time_starts && $time < $breakfast_time_ends && $timeslot->morning_on == 1 )
        {
            if($menu_type == 'Menu') {
                $time_chk[0] = "eatin_breakfast";
            } else if($menu_type == 'TakeawayMenu') {
                $time_chk[0] = "takeaway_breakfast";
            }
            $time_chk[1] = "morning_on";
        }
        elseif( $time >= $lunch_time_starts && $time < $lunch_time_ends  && $timeslot->lunch_on == 1  )
        {
            if($menu_type == 'Menu') {
                $time_chk[0] = "eatin_lunch";
            } else if($menu_type == 'TakeawayMenu') {
                $time_chk[0] = "takeaway_lunch";
            }
            $time_chk[1] = "lunch_on";
        }
        elseif( $time >= $tea_time_starts && $time < $tea_time_ends  && $timeslot->tea_on == 1  )
        {
            if($menu_type == 'Menu') {
                $time_chk[0] = "eatin_tea";
            } else if($menu_type == 'TakeawayMenu') {
                $time_chk[0] = "takeaway_tea";
            }
            $time_chk[1] = "tea_on";
        }
        elseif( $time >= $dinner_time_starts && $time < $dinner_time_ends  && $timeslot->dinner_on == 1  )
        {
            if($menu_type == 'Menu') {
                $time_chk[0] = "eatin_dinner";
            } else if($menu_type == 'TakeawayMenu') {
                $time_chk[0] = "takeaway_dinner";
            }
            $time_chk[1] = "dinner_on";
        }

        return $time_chk[0];
    }

    public function now_sendmail()
    {
        date_default_timezone_set("Asia/Tokyo");

        Excel::create('sales_report', function($excel) {

            $excel->sheet('Sales Day Report', function($sheet) {
                $now_date = date('Y-m-d', strtotime($this->get_current_time()));
                // ===1. Sales Data ===
                $order_pay = DB::table('order_pay')->whereDate('created_at', $now_date)->get();
                $receipt = DB::table('receipt')->find(1);
                $orders = DB::table('orders')->whereDate('created_at', $now_date)->get();

                $sales_data['total_sales'] = 0;
                $sales_data['gross_total'] = 0;
                $sales_data['gst_pr'] = $receipt->gst;
                $sales_data['total_gst'] = 0;
                $sales_data['guest'] = 0;
                $sales_data['cash_income'] = 0;
                $sales_data['cash_count'] = 0;
                $sales_data['card_total'] = 0;
                $sales_data['card_count'] = 0;
                $sales_data['refund_total'] = 0;
                $sales_data['discount_total'] = 0;
                $sales_data['tip_total'] = 0;

                foreach($order_pay as $ord_pay) {
                    $sales_data['total_sales'] += $ord_pay->total;
                    $sales_data['gross_total'] += $ord_pay->without_gst;
                    $sales_data['total_gst'] += $ord_pay->gst;
                    if($ord_pay->pay_method == 'CASH') {
                        $sales_data['cash_income'] += $ord_pay->total;
                        $sales_data['cash_count'] += 1;
                    } else {
                        $sales_data['card_total'] += $ord_pay->total;
                        $sales_data['card_count'] += 1;
                    }
                    $sales_data['refund_total'] += $ord_pay->change;
                    $sales_data['discount_total'] += $ord_pay->discount;
                    $sales_data['tip_total'] += $ord_pay->tip;
                }
                foreach($orders as $order) {
                    $sales_data['guest'] += $order->guest;
                }

                // ===2. Card Sales Data ===
                $card_type = DB::table('payments')->pluck('name')->toArray();
                //dd($card_type[0] . '_Total');
                $card_sales_data = array();
                for($i=0;$i<count($card_type);$i++) {
                    $card_sales_data[$card_type[$i] . '_Total'] = 0;
                    $card_sales_data[$card_type[$i] . '_Tip'] = 0;
                    $card_sales_data[$card_type[$i] . '_Count'] = 0;
                    foreach($order_pay as $ord_pay) {
                        if($ord_pay->pay_method == $card_type[$i]) {
                            $card_sales_data[$card_type[$i] . '_Total'] += $ord_pay->total;
                            $card_sales_data[$card_type[$i] . '_Tip'] += $ord_pay->tip;
                            $card_sales_data[$card_type[$i] . '_Count'] += 1;
                        }
                    }
                }

                // ===3. Discounts ===

                // ===4. Canceled Items ====
                $order_cancel_dish = DB::table('order_dish_match')->whereDate('created_at', $now_date)->where('amend_count', '<', 0)->get();

                $cancel_items = array();
                for($i=0;$i<count($order_cancel_dish);$i++) {
                    $cancel_items[$i]['amend_time'] = $order_cancel_dish[$i]->amend_time;
                    $cancel_items[$i]['id'] = DB::table('order_pay')->where('order_id', $order_cancel_dish[$i]->order_id)->pluck('id')->first();

                    $option_price = DB::table('order_option_match')->where('order_dish_id', $order_cancel_dish[$i]->id)->sum('item_price');
                    $cancel_items[$i]['cancel_price'] = (-1) * ($order_cancel_dish[$i]->dish_price + $option_price) * $order_cancel_dish[$i]->amend_count;
                }

                // ===5. Hour Sales Data ===
                $hour_sales_data = array();
                for($i=0;$i<24;$i++) {
                    $hour_sales_data[$i]['people'] = 0;
                    $hour_sales_data[$i]['sales'] = 0;
                    foreach($order_pay as $ord_pay) {
                        $sale_time = substr($ord_pay->created_at, 11, 5);
                        if($sale_time == $i) {
                            $guest = DB::table('orders')->where('id', $ord_pay->order_id)->pluck('guest')->first();
                            $hour_sales_data[$i]['people'] += $guest;
                            $hour_sales_data[$i]['sales'] += $ord_pay->total;
                        }
                    }
                }

                // ===6. Category Sales Data ===
                $categories = DB::table('categories')->get()->toArray();
                $category_sale_view = DB::table('category_sales')->whereDate('created_at',$now_date)->get()->toArray();
                $category_sales_data = array();
                for($i=0;$i<count($categories);$i++) {
                    $category_sales_data[$i]['id'] = $categories[$i]->id;
                    $category_sales_data[$i]['name'] = $categories[$i]->name_en;
                    $category_sales_data[$i]['qty'] = 0;
                    $category_sales_data[$i]['sales'] = 0;
                    $category_sales_data[$i]['qty1'] = 0;
                    $category_sales_data[$i]['sales1'] = 0;
                    $category_sales_data[$i]['is_parent'] = 0;
                    for($j=0;$j<count($category_sale_view);$j++) {
                        if($category_sales_data[$i]['id'] == $category_sale_view[$j]->category_id) {
                            $category_sales_data[$i]['qty'] += $category_sale_view[$j]->count;
                            $category_sales_data[$i]['sales'] += $category_sale_view[$j]->total;
                        }

                        if($category_sales_data[$i]['id'] == $category_sale_view[$j]->parent_id) {
                            $category_sales_data[$i]['is_parent'] = 1;
                            $category_sales_data[$i]['qty1'] += $category_sale_view[$j]->count;
                            $category_sales_data[$i]['sales1'] += $category_sale_view[$j]->total;
                        }
                    }
                }

                // ===7. Item Sales Data ===
                $item_sale_view = DB::table('item_sales')->whereDate('created_at', $now_date)->get();
                //get item_id list
                $item_id_all_list = array();
                $item_id_list = array();
                for($i=0;$i<count($item_sale_view);$i++) {
                    array_push($item_id_all_list, $item_sale_view[$i]->dish_id);
                    $item_id_list = array_values(array_unique($item_id_all_list));
                }

                $item_sales_data = array();
                for($i=0;$i<count($item_id_list);$i++) {

                    $item_sales_data[$i]['qty'] = 0;
                    $item_sales_data[$i]['sales'] = 0;
                    for($j=0;$j<count($item_sale_view);$j++) {

                        if($item_id_list[$i] == $item_sale_view[$j]->dish_id) {

                            $item_sales_data[$i]['name'] = $item_sale_view[$j]->name_en;
                            $item_sales_data[$i]['qty'] += $item_sale_view[$j]->count;
                            $item_sales_data[$i]['sales'] += $item_sale_view[$j]->total;

                        }
                    }
                }

                // ===8. Hourly Item Ranking ===
                //get $hourly_item_ranking
                $hourly_item_ranking = array();
                for($i=0;$i<count($item_id_list);$i++) {
                    $hourly_item_ranking[$i]['10'] = 0;$hourly_item_ranking[$i]['11'] = 0;$hourly_item_ranking[$i]['12'] = 0;
                    $hourly_item_ranking[$i]['13'] = 0;$hourly_item_ranking[$i]['14'] = 0;$hourly_item_ranking[$i]['15'] = 0;
                    $hourly_item_ranking[$i]['16'] = 0;$hourly_item_ranking[$i]['17'] = 0;$hourly_item_ranking[$i]['18'] = 0;
                    $hourly_item_ranking[$i]['19'] = 0;$hourly_item_ranking[$i]['20'] = 0;$hourly_item_ranking[$i]['21'] = 0;
                    $hourly_item_ranking[$i]['22'] = 0;$hourly_item_ranking[$i]['23'] = 0;$hourly_item_ranking[$i]['0'] = 0;
                    $hourly_item_ranking[$i]['item_total'] = 0;
                    for($j=0;$j<count($item_sale_view);$j++) {

                        if($item_id_list[$i] == $item_sale_view[$j]->dish_id) {

                            $hourly_item_ranking[$i]['item_name'] = $item_sale_view[$j]->name_en;

                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 10)
                                $hourly_item_ranking[$i]['10'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 11)
                                $hourly_item_ranking[$i]['11'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 12)
                                $hourly_item_ranking[$i]['12'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 13)
                                $hourly_item_ranking[$i]['13'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 14)
                                $hourly_item_ranking[$i]['14'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 15)
                                $hourly_item_ranking[$i]['15'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 16)
                                $hourly_item_ranking[$i]['16'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 17)
                                $hourly_item_ranking[$i]['17'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 18)
                                $hourly_item_ranking[$i]['18'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 19)
                                $hourly_item_ranking[$i]['19'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 20)
                                $hourly_item_ranking[$i]['20'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 21)
                                $hourly_item_ranking[$i]['21'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 22)
                                $hourly_item_ranking[$i]['22'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 23)
                                $hourly_item_ranking[$i]['23'] += $item_sale_view[$j]->count;
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 0)
                                $hourly_item_ranking[$i]['0'] += $item_sale_view[$j]->count;
                        }

                    }
                    $hourly_item_ranking[$i]['item_total'] += $hourly_item_ranking[$i]['10'] + $hourly_item_ranking[$i]['11'] + $hourly_item_ranking[$i]['12'] +
                        $hourly_item_ranking[$i]['13'] + $hourly_item_ranking[$i]['14'] + $hourly_item_ranking[$i]['15'] +
                        $hourly_item_ranking[$i]['16'] + $hourly_item_ranking[$i]['17'] + $hourly_item_ranking[$i]['18'] +
                        $hourly_item_ranking[$i]['19'] + $hourly_item_ranking[$i]['20'] + $hourly_item_ranking[$i]['21'] +
                        $hourly_item_ranking[$i]['22'] + $hourly_item_ranking[$i]['23'] + $hourly_item_ranking[$i]['0'];
                }

                // ===9. Hourly Cooktime Ranking ===
                $hourly_cooktime_ranking = array();
                for($i=0;$i<count($item_id_list);$i++) {

                    $hourly_cooktime_ranking[$i]['10'] = 0;$hourly_cooktime_ranking[$i]['11'] = 0;$hourly_cooktime_ranking[$i]['12'] = 0;
                    $hourly_cooktime_ranking[$i]['13'] = 0;$hourly_cooktime_ranking[$i]['14'] = 0;$hourly_cooktime_ranking[$i]['15'] = 0;
                    $hourly_cooktime_ranking[$i]['16'] = 0;$hourly_cooktime_ranking[$i]['17'] = 0;$hourly_cooktime_ranking[$i]['18'] = 0;
                    $hourly_cooktime_ranking[$i]['19'] = 0;$hourly_cooktime_ranking[$i]['20'] = 0;$hourly_cooktime_ranking[$i]['21'] = 0;
                    $hourly_cooktime_ranking[$i]['22'] = 0;$hourly_cooktime_ranking[$i]['23'] = 0;$hourly_cooktime_ranking[$i]['0'] = 0;
                    $hourly_cooktime_ranking[$i]['cook_avg_time'] = 0;

                    for($j=0;$j<count($item_sale_view);$j++) {
                        if($item_id_list[$i] == $item_sale_view[$j]->dish_id) {

                            $hourly_cooktime_ranking[$i]['item_name'] = $item_sale_view[$j]->name_en;

                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 10) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['10'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 11) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['11'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 12) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['12'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 13) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['13'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 14) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['14'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 15) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['15'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 16) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['16'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 17) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['17'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 18) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['18'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 19) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['19'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 20) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['20'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 21) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['21'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 22) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['22'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 23) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['23'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 0) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                if($item_sale_view[$j]->ready_time != null)
                                    $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                if($count_temp != 0)
                                    $hourly_cooktime_ranking[$i]['0'] = round($elapsed / $count_temp);
                            }

                            if($hourly_item_ranking[$i]['item_total'] != 0) {
                                $hourly_cooktime_ranking[$i]['cook_avg_time'] = round(($hourly_cooktime_ranking[$i]['10'] * $hourly_item_ranking[$i]['10'] +
                                        $hourly_cooktime_ranking[$i]['11'] * $hourly_item_ranking[$i]['11'] +
                                        $hourly_cooktime_ranking[$i]['12'] * $hourly_item_ranking[$i]['12'] +
                                        $hourly_cooktime_ranking[$i]['13'] * $hourly_item_ranking[$i]['13'] +
                                        $hourly_cooktime_ranking[$i]['14'] * $hourly_item_ranking[$i]['14'] +
                                        $hourly_cooktime_ranking[$i]['15'] * $hourly_item_ranking[$i]['15'] +
                                        $hourly_cooktime_ranking[$i]['16'] * $hourly_item_ranking[$i]['16'] +
                                        $hourly_cooktime_ranking[$i]['17'] * $hourly_item_ranking[$i]['17'] +
                                        $hourly_cooktime_ranking[$i]['18'] * $hourly_item_ranking[$i]['18'] +
                                        $hourly_cooktime_ranking[$i]['19'] * $hourly_item_ranking[$i]['19'] +
                                        $hourly_cooktime_ranking[$i]['20'] * $hourly_item_ranking[$i]['20'] +
                                        $hourly_cooktime_ranking[$i]['21'] * $hourly_item_ranking[$i]['21'] +
                                        $hourly_cooktime_ranking[$i]['22'] * $hourly_item_ranking[$i]['22'] +
                                        $hourly_cooktime_ranking[$i]['23'] * $hourly_item_ranking[$i]['23'] +
                                        $hourly_cooktime_ranking[$i]['0'] * $hourly_item_ranking[$i]['0']) / $hourly_item_ranking[$i]['item_total']);
                            }

                        }

                    }
                }

                // item_total sort desc(asc:a<=>b, desc:b<=>a)
                usort($hourly_item_ranking, function($a, $b) {
                    return $b['item_total'] <=> $a['item_total'];
                });

                // cook_avg_time sort desc(asc:a<=>b, desc:b<=>a)
                usort($hourly_cooktime_ranking, function($a, $b) {
                    return $b['cook_avg_time'] <=> $a['cook_avg_time'];
                });

                // ===10. Feedbacks ===
                $feedbacks = DB::table('orders')->whereDate('created_at', $now_date)->where('review', '<>', Null)->get();

                $sheet->loadView('emails.sales_day')->with(compact('receipt', 'order_pay', 'sales_data', 'card_type', 'card_sales_data', 'cancel_items', 'hour_sales_data',
                    'category_sales_data', 'item_sales_data', 'hourly_item_ranking', 'hourly_cooktime_ranking', 'feedbacks'));

            });

        })->store('xlsx', public_path('excel/exports'));

        $filename = public_path().'/excel/exports/sales_report.xlsx';

        $email_address = DB::table('receipt')->where('id', 1)->pluck('email_address')->first();
        if($email_address != Null)
            Mail::to($email_address)->send(new SalesDayReportEmail($filename));

        return response()->json(['status' => 'success']);

    }

    public function book_end()
    {
        $book_id = request()->get('book_id');
        Booked::where('id', $book_id)->update(['timer_flag' => 1]);
        $result = Booked::where('timer_flag',0)->get()->count();

        $table_obj = Table::get();

        foreach($table_obj as $table) {
            if(count($table->order) > 0) {
                $table->display_time = date_format(date_create($table->order[0]->time),"h:i A");
            }
            if(count($table->book) > 0) {
                    $table->display_time1 = date_format(date_create($table->book[0]->time),"h:i A");
                    $table->current_time1 = strtotime($table->book[0]->time) - strtotime($this->get_current_time());
            }
        }

        return response()->json(['status' => $result,'table_obj' => $table_obj]);
    }

    public function zoom_back()
    {
        session()->put('scale_value',request()->get('scale_value'));
        $status = request()->get('status');
        //return response()->json(['status' => session('scale_value')]);
        return redirect()->route('reception.seated', ['status' => $status]);
    }

    public function zoom_back1()
    {
        session()->put('scale_value1',request()->get('scale_value'));
        $status = request()->get('status');
        //return response()->json(['status' => session('scale_value')]);
        return redirect()->route('reception.seated', ['status' => $status]);
    }

    public function removeBooking()
    {
        $id = request()->id;
        $booked = Booked::find($id);
        $booked->bookedtables()->delete();
        $booked->delete();
        return redirect()->route('admin.booking');
    }
    public function updateOrderRemain() {
        $order_id = request()->order_id;
        $additional = request()->additional_min;
        $order = Order::find($order_id);
        $order->additional = $order->additional + $additional;
        $order->save();

        $table_ids = OrderTable::where('order_id', $order_id)->pluck('table_id')->toArray();
        $table_name = join('+', $table_ids);

        $object = new StdClass;
        $object->order_id = $order_id;

        broadcast(new TableMoveEvent($object));
    }
}
