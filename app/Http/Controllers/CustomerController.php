<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Events\SelfEvent;
use App\Events\FinishAndPayEvent;
use App\Events\PayEvent;

use App\Model\Category;
use App\Model\DishCategory;
use App\Model\Dish;
use App\Model\Discount;
use App\Model\OrderDish;
use App\Model\OrderOption;
use App\Model\Item;
use App\Model\Option;
use App\Model\Order;
use App\Model\Receipt;
use App\Model\Table;
use App\Model\OrderTable;
use App\Model\Holiday;
use App\Model\Timeslot;
use App\Model\Screentime;
use Illuminate\Http\Request;
use App\Events\KitchenEvent;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index($order_id)
    {
        $profile = Receipt::profile();
        $table_id = request()->table_id;//dd($table_id);
        $order_table = OrderTable::where('table_id', $table_id)->get()->first();//dd($order_table);
        $table_ids_arr = OrderTable::where('order_id', $order_id)->pluck('table_id');
        $tables = Table::whereIn('id', $table_ids_arr)->get();
        $table_name = '';
        $order = Order::where('id', $order_id)->where('pay_flag', '<>', 2)->first();//dd($order);
        foreach($tables as $table){
            $table_name .= $this->get_table_name($table->id).'+';
        }
        $table_name = rtrim($table_name, '+');
        //dd($table_name);
        $categories = Category::orderby('order')->get();
        $dishes = array();
        $temp_dishes = array();
        /*if(count($categories) > 0) {
            $category_record = Category::find($categories[0]['id']);
            $dishes = $category_record->dishes;
            foreach($dishes as $dish){
                $dish->discount = ($this->get_discount($dish->id))?($this->get_discount($dish->id)):'';
            }

        }*/

        $category_all = array();
        if(count($categories) > 0) {
            $i = 0;
            foreach ($categories as $category) {
                $dishes = [];
                if($category->has_subs != 1 && empty($category->parent_id)) {
                    $dishes = $this->get_dishes($category,$order->time,$order->menu_type);
                    if( !empty($dishes) && count($dishes) > 0 ) {
                        $category_all[$category->id] = $category;
                    }

                    if($i == 0) $temp_dishes = $dishes;
                    if(!empty($dishes)) $i++;
                }
                elseif($category->has_subs == 1) {
                    $main_sub_categories = array();
                    $sub_categories = Category::where('parent_id', $category->id)->orderby('order')->get();

                    if( !empty($sub_categories) && count($sub_categories) > 0 ) {
                        foreach ($sub_categories as $sub_category) {
                            $sub_dishes = $this->get_dishes($sub_category,$order->time,$order->menu_type);
                            if(!empty($sub_dishes) && count($sub_dishes) > 0) {
                                $dishes = $sub_dishes;
                                array_push($main_sub_categories ,$sub_category);
                            }

                            if($i == 0) $temp_dishes = $dishes;
                            if(!empty($dishes)) $i++;
                        }
                    }
                    else {
                        $dishes = $this->get_dishes($category,$order->time,$order->menu_type);
                        $main_sub_categories = [];

                        if($i == 0) $temp_dishes = $dishes;
                        if(!empty($dishes)) $i++;
                    }
                    if( !empty($dishes) && count($dishes) > 0 ) {
                        $category_all[$category->id] = $category;
                        $category_all[$category->id]['children'] = $main_sub_categories;
                    }
                }

            }
        }

        //dd($order_table);
        //dd($order->duration);

        $dishes = $temp_dishes;

        // $img_name = Storage::disk('screen')->files();
        $img_name = Receipt::profile()->advertise_img;
        $img_back = Receipt::profile()->background_img;

        $screentime = Screentime::orderby('id','desc')->get()->first();

        $pay_flag = Order::where('id', $order_id)->pluck('pay_flag')->first();
        if($pay_flag == 0) {
            if (request()->has('alert')) {
                $alert = request()->alert;
            } else {
                $alert = '';
            }
            return view('customer.index')
                ->with(compact('alert', 'profile', 'category_all', 'dishes', 'order', 'order_table', 'table_name', 'table_id', 'screentime'))
                ->with('img_name', $img_name)
                ->with('img_back', $img_back);
        } elseif($pay_flag == 1) {

            $table_name = $order->table_name;
            $starting_time = $order->time;

            $order_dishes = OrderDish::where('order_id', $order_id)->get();
            $total = 0;
            foreach($order_dishes as $order_dish) {
                $order_dish->options = $order_dish->Order_Option()->get();
                $items_price = 0;
                foreach ($order_dish->options as $option) {
                    if($option->item_id) {
                        $option_items = Item::select('price')->where('id', $option->item_id)->get()->first();
                        $items_price += $option_items->price;
                    }
                    else {
                        $items_price = 0;
                    }
                }
                $order_dish->each_price = $order_dish->dish_price + $items_price;
                $order_dish->sub_total = $order_dish->each_price * $order_dish->count;
                $total += $order_dish->sub_total;
            }

            $gst = Receipt::profile()->gst;
            $gst_price = $total * $gst/100;
            $without_gst_price = $total - $gst_price;
            return view('customer.view_pay')->with(compact('order_id', 'table_name', 'starting_time', 'total', 'without_gst_price', 'gst_price'));
        }

    }
//    public function dish_list()
//    {
//        $category = Category::find(request()->category);
//        if($category->has_subs == 1) {
//
//        } else {
//            $dishes = $category->dishes;
//            foreach($dishes as $dish){
//                $dish->discount = ($this->get_discount($dish->id))?($this->get_discount($dish->id)):'';
//            }
//            return (string)view('part.category_dish_customer', compact('dishes'))->render();
//        }
//
//    }
    public function dish_list()
    {
        $order = Order::find(request()->order);
        $category = Category::find(request()->category);
        $menu_time = request()->time_slot;
        $menu_type = request()->menu_type;
        $dishes = $this->get_dishes($category,$menu_time,$menu_type);

        if ($order->duration != 5) {
            foreach($dishes as $ds) {
                if ($ds->isBuffet()) $ds->price = 0;
            }
        }
        /*$time = substr($menu_time,11,5);
        $date = date('d M Y', strtotime($menu_time));

        $timeslot = Timeslot::find(1);

        $breakfast_time = $timeslot->morning_starts;
        if( substr($breakfast_time,-2) == "AM" ) $breakfast_time = substr($breakfast_time,0,5);
        else{
            $first_time = (int)substr($breakfast_time,0,2);
            $first_time += 12;
            $last_time = substr($breakfast_time,2,3);
            $breakfast_time = $first_time . $last_time;
        }

        $lunch_time = $timeslot->lunch_starts;
        if( substr($lunch_time,-2) == "AM" ) $lunch_time = substr($lunch_time,0,5);
        else{
            $first_time = (int)substr($lunch_time,0,2);
            $first_time += 12;
            $last_time = substr($lunch_time,2,3);
            $lunch_time = $first_time . $last_time;
        }

        $tea_time = $timeslot->tea_starts;
        if( substr($tea_time,-2) == "AM" ) $tea_time = substr($tea_time,0,5);
        else{
            $first_time = (int)substr($tea_time,0,2);
            $first_time += 12;
            $last_time = substr($tea_time,2,3);
            $tea_time = $first_time . $last_time;
        }

        $dinner_time = $timeslot->dinner_starts;
        if( substr($dinner_time,-2) == "AM" ) $dinner_time = substr($dinner_time,0,5);
        else{
            $first_time = (int)substr($dinner_time,0,2);
            $first_time += 12;
            $last_time = substr($dinner_time,2,3);
            $dinner_time = $first_time . $last_time;
        }

        $latenight_time = $timeslot->latenight_starts;
        if( substr($latenight_time,-2) == "AM" ) $latenight_time = substr($latenight_time,0,5);
        else{
            $first_time = (int)substr($latenight_time,0,2);
            $first_time += 12;
            $last_time = substr($latenight_time,2,3);
            $latenight_time = $first_time . $last_time;
        }

        if( $time >= $breakfast_time && $time < $lunch_time )
        {
            $eat_in = "eatin_breakfast";
            $eat_in1 = "morning_on";
        }
        elseif( $time >= $lunch_time && $time < $tea_time  )
        {
            $eat_in = "eatin_lunch";
            $eat_in1 = "lunch_on";
        }
        elseif( $time >= $tea_time && $time < $dinner_time  )
        {
            $eat_in = "eatin_tea";
            $eat_in1 = "tea_on";
        }
        elseif( $time >= $dinner_time && $time < $latenight_time  )
        {
            $eat_in = "eatin_dinner";
            $eat_in1 = "dinner_on";
        }
        else
        {
            $eat_in = "";
            $eat_in1 = "";
        }

        $week = date('w', strtotime($menu_time));
        switch($week)
        {
            case 0:
                $time_week = Timeslot::find(8);
                break;
            case 1:
                $time_week = Timeslot::find(2);
                break;
            case 2:
                $time_week = Timeslot::find(3);
                break;
            case 3:
                $time_week = Timeslot::find(4);
                break;
            case 4:
                $time_week = Timeslot::find(5);
                break;
            case 5:
                $time_week = Timeslot::find(6);
                break;
            case 6:
                $time_week = Timeslot::find(7);
                break;

        }

        $eat_opt = $time_week->$eat_in1;
        $day_on = $time_week->day_on;

        $holiday = Timeslot::find(9);

        if($holiday->day_on == 1)   $chk_holiday = Holiday::where('holiday_date',$date)->get();
        else $chk_holiday = [];

        if(count($chk_holiday) > 0)
        {
            if( !empty($eat_in1) && $holiday->$eat_in1 == 1 )
            {
                $dishes = $category->eat_dishes($eat_in);
                foreach($dishes as $dish){
                    $dish->discount = ($this->get_discount($dish->id))?($this->get_discount($dish->id)):'';
                }
            }
            else
                $dishes = "";

        }
        else
        {
            $all_week = Timeslot::get();

            if( $all_week[1]->day_on == 0 && $all_week[2]->day_on  == 0 && $all_week[3]->day_on  == 0 && $all_week[4]->day_on  == 0 && $all_week[5]->day_on  == 0 && $all_week[6]->day_on  == 0 && $all_week[7]->day_on  == 0)
            {
                if(!empty($eat_in1) && $timeslot->$eat_in1 == 1)
                {
                    $dishes = $category->eat_dishes($eat_in);
                    foreach($dishes as $dish){
                        $dish->discount = ($this->get_discount($dish->id))?($this->get_discount($dish->id)):'';
                    }
                }
                else
                    $dishes = "";
            }
            else{
                if(!empty($eat_in1) && $eat_opt == 1 && $day_on == 1)
                {
                    $dishes = $category->eat_dishes($eat_in);
                    foreach($dishes as $dish){
                        $dish->discount = ($this->get_discount($dish->id))?($this->get_discount($dish->id)):'';
                    }
                }
                else
                    $dishes = "";
            }

        }*/

        return (string)view('part.category_dish_customer', compact('dishes', 'order'))->render();
    }
    public function dish_info()
    {
//        dd(request());
        $dish_id = request()->dish_id;
        $dish = Dish::find($dish_id);
        $dish->discount = $this->get_discount($dish_id);//dd($dish->discount);
        $items = request()->items;
        $options = $dish->options;
        $option_id_arr = '';
        foreach($options as $option){
            if($option->photo_visible > 0){
                $option_id_arr .= $option->id.',';
            }
        }
        $option_id_arr = rtrim($option_id_arr, ",");
        $option_count = count($options);
        if(request()->type == 'main'){
            $count = 1;
        }else{
            $count = 0;
            $type = 0;
            foreach ($options as $option) {
                if($option->photo_visible == 1){
                    $count = 1;
                }
                if($option->photo_visible == 0){
                    $type = 1;
                }
            }
            if($type == 1 && $count == 1){
                $count = 2;
            }
        }
//        dd($options);
//        $count = 0;
        return (string)view('part.dish_info', compact('dish', 'options', 'count', 'option_count', 'option_id_arr', 'items'))->render();
    }
    public function dish_option()
    {
//        dd(request());
        $dish_id = request()->dish_id;

        $dish = Dish::find($dish_id);
        $dish->discount = $this->get_discount($dish_id);
        $dish->price = ($dish->discount)?($dish->discount):$dish->price;

        $options = $dish->options;

        $count = request()->index;

        $option_ids = request()->option_id_arr;
        $option_id_arr = explode(',', $option_ids);
        $option = Option::find($option_id_arr[$count]);
        $items = Item::where('option_id', $option_id_arr[$count])->where('stock', 1)->get();

//        $chk_prev_flag = request()->chk_flag;
//        if($chk_prev_flag == 0)
            $count ++;
//        else
//            $count += 2;

        $items_id = request()->items_id;

        $selecteds = request()->selecteds;// for example: ['', '38', '46,50']
        $slt_items = array();
        $i = 0;
        foreach($selecteds as $selected) {

            $items_id_arr = explode(',', $selected);

            if($items_id_arr[0]) {
                $slt_items[$i]['items_id_arr'] = $items_id_arr;
                $j = 0;
                foreach($items_id_arr as $its_id) {
                    $it_pri = Item::where('id', $its_id)->get()->first();
                    $slt_items[$i][$j]['name'] = $it_pri->name;
                    $slt_items[$i][$j]['price'] = $it_pri->price;
                    $j++;
                }
                $i++;
            }

//            if($items_id_arr[0]) {
//                $op_id = Item::where('id', $items_id_arr[0])->pluck('option_id');
//                $slt_items[$i]['display_name_en'] = Option::where('id', $op_id)->pluck('display_name_en')->first();
//                $slt_items[$i]['display_name_cn'] = Option::where('id', $op_id)->pluck('display_name_cn')->first();
//                $slt_items[$i]['display_name_jp'] = Option::where('id', $op_id)->pluck('display_name_jp')->first();
//                $slt_items[$i]['items_id_arr'] = $items_id_arr;
//
//                $j = 0;
//                foreach($items_id_arr as $its_id) {
//                    $it_pri = Item::where('id', $its_id)->pluck('price')->first();
//                    $slt_items[$i][$j]['price'] = $it_pri;
//                    $j++;
//                }
//
//                $i++;
//            }

        }

        return (string)view('part.dish_option', compact('items', 'count', 'option', 'option_id_arr', 'option_ids', 'dish_id', 'items_id', 'dish', 'options', 'slt_items'))->render();

    }
    public function dish_option_previous()
    {
//        dd(request());
        $dish_id = request()->dish_id;

        $dish = Dish::find($dish_id);
        $dish->discount = $this->get_discount($dish_id);
        $dish->price = ($dish->discount)?($dish->discount):$dish->price;

        $options = $dish->options;

        $count = request()->index;

        $option_ids = request()->option_id_arr;
        $option_id_arr = explode(',', $option_ids);

        $chk_prev_flag = request()->chk_flag;
        if($chk_prev_flag == 1) {
            $count ++;
        }

        $option = Option::find($option_id_arr[$count]);
        $items = Item::where('option_id', $option_id_arr[$count])->where('stock', 1)->get();

        $count ++;

        $items_id = request()->items_id;

        $selecteds = request()->selecteds;// for example: ['', '38', '46,50']
        $slt_items = array();
        $i = 0;
        foreach($selecteds as $selected) {

            $items_id_arr = explode(',', $selected);

            if($items_id_arr[0]) {
                $slt_items[$i]['items_id_arr'] = $items_id_arr;
                $j = 0;
                foreach($items_id_arr as $its_id) {
                    $it_pri = Item::where('id', $its_id)->get()->first();
                    $slt_items[$i][$j]['name'] = $it_pri->name;
                    $slt_items[$i][$j]['price'] = $it_pri->price;
                    $j++;
                }
                $i++;
            }

//            if($items_id_arr[0]) {
//                $op_id = Item::where('id', $items_id_arr[0])->pluck('option_id');
//                $slt_items[$i]['display_name_en'] = Option::where('id', $op_id)->pluck('display_name_en')->first();
//                $slt_items[$i]['display_name_cn'] = Option::where('id', $op_id)->pluck('display_name_cn')->first();
//                $slt_items[$i]['display_name_jp'] = Option::where('id', $op_id)->pluck('display_name_jp')->first();
//                $slt_items[$i]['items_id_arr'] = $items_id_arr;
//
//                $j = 0;
//                foreach($items_id_arr as $its_id) {
//                    $it_pri = Item::where('id', $its_id)->pluck('price')->first();
//                    $slt_items[$i][$j]['price'] = $it_pri;
//                    $j++;
//                }
//
//                $i++;
//            }

        }

        return (string)view('part.dish_option', compact('items', 'count', 'option', 'option_id_arr', 'option_ids', 'dish_id', 'items_id', 'dish', 'options', 'slt_items'))->render();

    }


    public function dish_option_confirm()
    {
//        dd(request());
        $dish_id = request()->dish_id;
        $dish = Dish::find($dish_id);
        $dish->discount = $this->get_discount($dish_id);
        $dish->price = ($this->get_discount($dish_id))?($this->get_discount($dish_id)):$dish->price;
        $options = $dish->options;
        $count = request()->index;
        $option_ids = request()->option_id_arr;
        $option_id_arr = explode(',', $option_ids);//dd($option_id_arr);

        $items_id = request()->items_id;

        $selecteds = request()->selecteds;// for example: ['', '38', '46,50']
        $slt_items = array();
        $i = 0;
        foreach($selecteds as $selected) {

            $items_id_arr = explode(',', $selected);

            if($items_id_arr[0]) {
                $op_id = Item::where('id', $items_id_arr[0])->pluck('option_id');
                $slt_items[$i]['display_name_en'] = Option::where('id', $op_id)->pluck('display_name_en')->first();
                $slt_items[$i]['display_name_cn'] = Option::where('id', $op_id)->pluck('display_name_cn')->first();
                $slt_items[$i]['display_name_jp'] = Option::where('id', $op_id)->pluck('display_name_jp')->first();
                $slt_items[$i]['items_id_arr'] = $items_id_arr;

                $j = 0;
                foreach($items_id_arr as $its_id) {
                    $it = Item::select('name', 'price', 'image')->where('id', $its_id)->get()->first();
                    $slt_items[$i][$j]['name'] = $it->name;
                    $slt_items[$i][$j]['price'] = $it->price;
                    $slt_items[$i][$j]['image'] = $it->image;
                    $j++;
                }

                $i++;
            }

        }
//        $option = Option::find($option_id_arr[$count]);
//        $items = Item::where('option_id', $option_id_arr[$count])->get();
//        $count ++;
//        dd($next_option);
        return (string)view('part.dish_option_confirm', compact('count', 'option_id_arr', 'option_ids', 'dish_id', 'items_id', 'dish', 'options', 'slt_items'))->render();

    }

    //order dish save when photo_visible is 0
    public function order_dish()
    {
        $order_id = request()->order_id;
        $dish_id = request()->dish_id;
        $count = (int)(request()->count);
        $dish = Dish::find($dish_id);
        $items_id = request()->items_id;

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

        foreach($items_id as $item_id) {
            if($item_id != "") {
                $order_option = new OrderOption();
                $order_option->order_dish_id = $order_dish_id;
                $order_option->option_id = Item::where('id', $item_id)->pluck('option_id')->first();
                $order_option->item_id = $item_id;
                $order_option->item_price = Item::where('id', $item_id)->pluck('price')->first();
                $items_price += $order_option->item_price;
                $order_option->save();
            }
        }

        //save total price
        $order_dish = OrderDish::find($order_dish_id);
        $order_dish->total_price = ($order_dish->dish_price + $items_price) * $count;
        $order_dish->save();

        //broadcast to kitchen
        $added_dish = $this->get_added_dish($dish, $order_id, $order_dish_id);
        broadcast(new KitchenEvent($added_dish));

//        return (string)view('part.dish_info', compact('dish', 'options', 'count', 'option_count', 'option_id_arr', 'items'))->render();
    }

    //order dish save when photo_visible is 1
    public function orderNow_Photo()
    {
        $order_id = request()->order_id;
        $dish_id = request()->dish_id;
        $count = (int)(request()->count);
        $selected_items_id = request()->selected_items_id;
        $items_str = '';
        foreach($selected_items_id as $selected) {
            if($selected != "") {
                $items_str .= $selected.",";
            }
        }
        if($items_str != '') {
            $items_str = substr($items_str, 0, -1);
            $items_id = explode(",", $items_str);
        }

        $order = Order::find($order_id);
        $dish = Dish::find($dish_id);
        //save to order_dish_match table
        $order_dish = new OrderDish();
        $order_dish->order_id = $order_id;
        $order_dish->dish_id = $dish_id;
        $order_dish->count = $count;

        $order_dish->dish_price = ($this->get_discount($dish_id))?($this->get_discount($dish_id)):$dish->price;

        if ($order->duration != 5 && $dish->isBuffet()) {
            $order_dish->dish_price = 0;
        }

        $order_dish->ready_flag = 0;
        $order_dish->save();

        //save to order_option_match table
        $order_dish_id = $order_dish->id;
        $items_price = 0;
        if($items_str != '') {
            foreach($items_id as $item_id) {
                $order_option = new OrderOption();
                $order_option->order_dish_id = $order_dish_id;
                $order_option->option_id = Item::where('id',$item_id)->pluck('option_id')->first();
                $order_option->item_id = $item_id;
                $order_option->item_price = Item::where('id',$item_id)->pluck('price')->first();
                $items_price += $order_option->item_price;
                $order_option->save();
            }
        }
//        else {
//            $order_option = new OrderOption();
//            $order_option->order_dish_id = $order_dish_id;
//            $order_option->option_id = Null;
//            $order_option->item_id = Null;
//            $order_option->item_price = Null;
//            $items_price = 0;
//            $order_option->save();
//        }

        //save total price
        $order_dish = OrderDish::find($order_dish_id);
        $order_dish->total_price = ($order_dish->dish_price + $items_price) * $count;
        $order_dish->save();

        //broadcast to kitchen
        $added_dish = $this->get_added_dish($dish, $order_id, $order_dish_id);
        broadcast(new KitchenEvent($added_dish));

//        return $aaa;
    }

    //lang_select
    public function lang_select() {

        $profile = Receipt::find(1);
        $lang_data = array(0=>'English');
        if($profile->lang_cn == 1)  array_push($lang_data, '中国語');
        if($profile->lang_jp == 1)  array_push($lang_data, '日本語');


        $lang_id = request()->session()->get('language');
        return (string)view('customer.lang_select', compact('lang_data', 'lang_id'))->render();
    }

    public function put_lang() {

        $language = request()->checked_lang;
        request()->session()->put('language', $language);
        return ;
    }

    //feedback
    public function feedback() {

        $order_id = request()->order_id;
        $feedback = Order::where('id', $order_id)->get()->first();
//        dd($feedback);
        return (string)view('customer.feedback', compact('feedback'))->render();
    }

    public function add_review() {

        $order_id = request()->order_id;
        $review_type = request()->review_type;
        $review = request()->review;
        $order = Order::findOrFail($order_id);
        $order->review_type = $review_type;
        $order->review = $review;
        $order->save();

        //show count_notification on reception screen
        $count_notification = $this->CountNotification1(request()->table_id,4);
        broadcast(new NotificationEvent($count_notification));

        return $order;
    }

    //calling
    public function calling() {

        $table_id = request()->table_id;
        $orderTable = OrderTable::where('table_id', $table_id)->first();
        $order_id = OrderTable::where('table_id', $table_id)->pluck('order_id');//dd($order_id);
        $calls = Order::where('id', $order_id[0])->pluck('calls')->first();
        if($orderTable->calling_time == Null) {
            $now = $this->get_current_time();
            $orderTable->calling_time = $now;
            $orderTable->attend_time = Null;

            OrderTable::where('order_id', $order_id)->update(['calling_time' => $now]);
            OrderTable::where('order_id', $order_id)->update(['attend_time' => Null]);

            $calls += 1;
            $count_notification = $this->CountNotification1($table_id,2);

        } else {
            $orderTable->calling_time = Null;
            $orderTable->attend_time = Null;
            OrderTable::whereIn('order_id', $order_id)->update(['calling_time' => Null]);
            OrderTable::whereIn('order_id', $order_id)->update(['attend_time' => Null]);
            $count_notification = $this->CountNotification1($table_id,3);
        }
        $orderTable->save();
        Order::where('id', $order_id[0])->update(['calls' => $calls]);

        //show count_notification on reception screen

        broadcast(new NotificationEvent($count_notification));

        broadcast(new SelfEvent($order_id[0], $orderTable->calling_time));

        return $orderTable->calling_time;
    }

    public function remove_dish() {
        $order_dish = OrderDish::find(request()->id);
        $order_dish->delete();
        return $this->view_bill_pay(request());
    }

    //view bill pay
    public function view_bill_pay() {

        $order_id = request()->order_id;
        $table_ids = OrderTable::where('order_id', $order_id)->pluck('table_id');
        $table_name = '';
        foreach($table_ids as $table_id) {
            $tb_name = $this->get_table_name($table_id);//dd($tb_name);
            $table_name .= $tb_name . " + ";
        }
        $table_name = substr($table_name, 0, -3);
        $starting_time = Order::where('id', $order_id)->pluck('time')->first();

        $order_dishes = OrderDish::withTrashed()->where('order_id', $order_id)->get();

        $total = 0;
        $order = Order::find($order_id);
        foreach($order_dishes as $order_dish) {

            $dish_list = Dish::select('name_en')->where('id', $order_dish->dish_id)->get()->first();

            if(request()->session()->has('language')) {
                if(request()->session()->get('language') == 1)
                    $order_dish->dish_name = Dish::where('id', $order_dish->dish_id)->pluck('name_cn')->first();
                elseif(request()->session()->get('language') == 2)
                    $order_dish->dish_name = Dish::where('id', $order_dish->dish_id)->pluck('name_jp')->first();
                else
                    $order_dish->dish_name = Dish::where('id', $order_dish->dish_id)->pluck('name_en')->first();
            } else {
                $order_dish->dish_name = Dish::where('id', $order_dish->dish_id)->pluck('name_en')->first();
            }

            $order_dish->dish_name_cn = $dish_list->name_cn;
            $order_dish->dish_name_jp = $dish_list->name_jp;
            $order_dish->dish_name_en = $dish_list->name_en;

            $order_dish->options = $order_dish->Order_Option()->get();
            $items_price = 0;
            foreach ($order_dish->options as $option) {

                if($option->option_id) {
                    if(request()->session()->has('language')) {
                        if(request()->session()->get('language') == 1)
                            $option->option_name = Option::where('id', $option->option_id)->pluck('display_name_cn')->first();
                        elseif(request()->session()->get('language') == 2)
                            $option->option_name = Option::where('id', $option->option_id)->pluck('display_name_jp')->first();
                        else
                            $option->option_name = Option::where('id', $option->option_id)->pluck('display_name_en')->first();
                    } else {
                        $option->option_name = Option::where('id', $option->option_id)->pluck('display_name_en')->first();
                    }
                }
                else
                    $option->option_name = '';

                if($option->item_id) {

                    $option_items = Item::select('name', 'price')->where('id', $option->item_id)->get()->first();
                    $option->item_name = $option_items->name;
                    $items_price += $option_items->price;
                }
                else {
                    $option->item_name = '';
                    $items_price = 0;
                }

            }
            $order_dish->each_price = $order_dish->dish_price + $items_price;
            $order_dish->sub_total = $order_dish->each_price * $order_dish->count;
            if ($order->duration != 5 && $order_dish->dish->isBuffet())
                $order_dish->sub_total = 0;
            if ($order_dish->deleted_at == null)
                $total += $order_dish->sub_total;
        }

        $gst = Receipt::profile()->gst;
        $gst_price = round($total * $gst / 100);
        $without_gst_price = $total - $gst_price;

        // dd($order_dishes);

        return (string)view('customer.view_bill_pay', compact('order_id', 'table_name', 'starting_time', 'order_dishes', 'total', 'gst_price', 'without_gst_price'))->render();
    }

    public function check_finish_pay() {
        $order_id = request()->order_id;
        $table_name = request()->table_name;
        $chk_ready = OrderDish::where('order_id', $order_id)->where('ready_flag', 0)->get()->count();
        if ($chk_ready > 0) {
            $alert = "料理がすべて到着していません。スタッフをお呼びください。";
            return [
                'error' => true,
                'alert' => $alert,
            ];
        }
        return [
            'error' => false,
        ];
    }

    public function finish_pay() {

        $order_id = request()->order_id;
        $table_name = request()->table_name;
        $table_id = '';

        $order = Order::findOrFail($order_id);

        if($order->menu_type == 'Menu') {

            $chk_ready = OrderDish::where('order_id', $order_id)->where('ready_flag', 0)->get()->count();
            if($chk_ready > 0) {
                //alert
                $alert = "料理がすべて到着していません。スタッフをお呼びください。";
                $table_id = OrderTable::where('order_id', $order_id)->pluck('table_id')->first();
                return redirect()->route('customer.index', ['alert'=>$alert, 'order_id'=>$order_id, 'table_id'=>$table_id]);
            } else {
                $order->pay_flag = 1;
                $order->save();

                //show count_notification on reception screen
                $table_names = explode('+',$table_name);
                for($i=0;$i<count($table_names);$i++) {
                    $id = Table::where('name',trim($table_names[$i]))->get()->first();
                    $count_notification = $this->CountNotification1($id->id,1);
                    broadcast(new NotificationEvent($count_notification));
                }

                $table_id = OrderTable::where('order_id', $order_id)->pluck('table_id')->first();
                broadcast(new FinishAndPayEvent($order_id, $table_id));
            }
        } elseif($order->menu_type == 'TakeawayMenu') {

            $order->pay_flag = 1;
            $order->save();

            //show count_notification on reception screen
            $table_names = explode('+',$table_name);
            for($i=0;$i<count($table_names);$i++) {
                $id = Table::where('name',trim($table_names[$i]))->get()->first();
                $count_notification = $this->CountNotification1($id->id,1);
                broadcast(new NotificationEvent($count_notification));
            }

            $table_id = OrderTable::where('order_id', $order_id)->pluck('table_id')->first();
            broadcast(new FinishAndPayEvent($order_id, $table_id));
        }
        return redirect()->route('customer.index', ['order_id'=>$order_id, 'table_id'=>$table_id]);
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

        if( !empty($result) )
        {
            $dishes = $category->eat_dishes($result);
            foreach($dishes as $dish){
                $dish->discount = ($this->get_discount($dish->id))?($this->get_discount($dish->id)):'';
            }
        }
        else
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

        // dd(
        //     $time,
        //     $breakfast_time_starts,
        //     $breakfast_time_ends,
        //     $lunch_time_starts,
        //     $lunch_time_ends,
        //     $tea_time_starts,
        //     $tea_time_ends,
        //     $dinner_time_starts,
        //     $dinner_time_ends,
        //     $latenight_time_starts,
        //     $latenight_time_ends
        // );

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
        else
        {
            $time_chk[0] = "";
            $time_chk[1] = "";
        }

        return $time_chk[0];
    }

}
