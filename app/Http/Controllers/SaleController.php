<?php

namespace App\Http\Controllers;

use App\Model\Dish;
use App\Model\OrderDish;
use App\Model\OrderPay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Model\Order;
use App\Model\Table;
use App\Model\Category;
use DateTime;

class SaleController extends Controller
{
    //
    public function index()
    {

        if(request()->has('search_day')) {//by date change
            $search_day = request()->search_day;
            if($search_day == 'previous') {
                $endDate = date('Y-m-d', strtotime(' -6 day', strtotime($this->get_current_time())));
                $startDate = date('Y-m-d', strtotime(' -14 day', strtotime($this->get_current_time())));
            } else if($search_day == 'next') {
                $endDate = date('Y-m-d', strtotime(' +8 day', strtotime($this->get_current_time())));
                $startDate = date('Y-m-d', strtotime(' 0 day', strtotime($this->get_current_time())));
            } else if($search_day == 'today') {
                $endDate = date('Y-m-d', strtotime(' +1 day', strtotime($this->get_current_time())));
                $startDate = date('Y-m-d', strtotime(' -7 day', strtotime($this->get_current_time())));
            }
//            $search_date = date('Y-m-d', strtotime($search_date));
        } else {//date nochange
            $search_day = 'today';
            $endDate = date('Y-m-d', strtotime(' +1 day', strtotime($this->get_current_time())));
            $startDate = date('Y-m-d', strtotime(' -7 day', strtotime($this->get_current_time())));
        }
//        dd($startDate. " => ". $endDate);

        $order_obj = Order::where('time', '>', $startDate)->where('time', '<', $endDate)->get();

        $daily_review = array();
        for($i=0;$i<7;$i++) {

            if(request()->has('search_day')) {//by date change
                $search_day = request()->search_day;
                if($search_day == 'previous') {
                    $date = date('Y-m-d', strtotime(' -'.($i+7).' day', strtotime($this->get_current_time())));
                } else if($search_day == 'next') {
                    $date = date('Y-m-d', strtotime(' -'.($i-7).' day', strtotime($this->get_current_time())));
                } else if($search_day == 'today') {
                    $date = date('Y-m-d', strtotime(' -'.($i).' day', strtotime($this->get_current_time())));
                }
            } else {//date nochange
                $search_day = 'today';
                $date = date('Y-m-d', strtotime(' -'.($i).' day', strtotime($this->get_current_time())));
            }
            $daily_review[$i]['date'] = $this->get_day_data($date);

            $sales = 0;
            $guest_count = 0;
            $order_count = 0;
            $dish_ids[$i] = array();
            $calls_count = 0;
            $feedback_count = 0;

            foreach($order_obj as $order) {
                if(substr($order->time, 0, 10) == $date){
                    $sales += OrderPay::where('order_id', $order->id)->sum('total');
                    $ds_ids = OrderDish::where('order_id', $order->id)->pluck('dish_id');
                    foreach($ds_ids as $ds_id) {
                        array_push($dish_ids[$i], $ds_id);
                    }
                    $guest_count += $order->guest;
                    $order_count ++;
                    $calls_count += $order->calls;
                    if($order->review != Null)
                        $feedback_count ++;
                }
            }

            //get sales
            if($sales > 0) {
                $daily_review[$i]['sales'] = '¥'.$sales;
            } else {
                $daily_review[$i]['sales'] = '¥0';
            }
            //get group
            $dish_ids[$i] = array_unique($dish_ids[$i]);
            $groups[$i] = array();
            $groups[$i] = Dish::whereIn('id', $dish_ids[$i])->pluck('group_id')->toArray();
            //$groups[$i] = array_unique($groups[$i]);
            if(count($groups[$i]) > 0) {
                $daily_review[$i]['group'] = count($groups[$i]);
            } else {
                $daily_review[$i]['group'] = '0';
            }
            //get guest
            $daily_review[$i]['guest'] = $guest_count;
            //get order_count
            $daily_review[$i]['orders'] = $order_count;
            //get calls
            $daily_review[$i]['calls'] = $calls_count;
            //get feedback
            $daily_review[$i]['feedback'] = $feedback_count;

        }

        $monthly_sales = array();
        for($i=0;$i<30;$i++) {
            $date = date('Y-m', strtotime(' -'.$i.' month', strtotime($this->get_current_time())));
            $monthly_sales[$i]['date'] = $this->get_monthly_data($date);

            $sales = 0;
            foreach($order_obj as $order) {
                if(substr($order->time, 0, 7) == $date){
                    $sales += OrderDish::where('order_id', $order->id)->sum('total_price');
                }

            }
            //get sales
            if($sales > 0) {
                $monthly_sales[$i]['sales'] = '¥'.$sales;
            } else {
                $monthly_sales[$i]['sales'] = '¥0';
            }

        }

        if(request()->has('date_seller')) {
            $date_seller = request()->date_seller;
            if($date_seller == 1) {//Daily
                $current_date = date('Y-m-d', strtotime($this->get_current_time()));
                $order_obj = Order::whereDate('time', $current_date)->get();
            }elseif($date_seller == 2) {//Weeekly
                $current_day = date("N");//dd($current_day);
                if($current_day == 7) {
                    $days_to_sunday = 7 - $current_day;
                    $days_from_monday = 14 - $current_day;
                }else {
                    $days_to_sunday =  - $current_day;
                    $days_from_monday = 7 - $current_day;
                }
                $start_date = date("Y-m-d", strtotime("$days_to_sunday Days"));
                $end_date = date("Y-m-d", strtotime("$days_from_monday Days"));
//                dd($start_date.':'.$end_date);
                $order_obj = Order::where('time', '>=', $start_date)->where('time', '<', $end_date)->get();
            }elseif($date_seller == 3) {//Monthly
                $current_date = date('Y-m', strtotime($this->get_current_time()));
                $order_obj = Order::whereDate('time', 'like', '%' . $current_date . '%')->get();
            }
            ////
            foreach($order_obj as $order) {
                $order->sale = OrderDish::where('order_id', $order->id)->sum('total_price');
            }
            $order_obj_array = $order_obj->toArray();
            $sale  = array_column($order_obj_array, 'sale');
            //get best sellers
            array_multisort($sale, SORT_DESC, $order_obj_array);
            $best_sellers = $order_obj_array;
            //get worst sellers
            array_multisort($sale, SORT_ASC, $order_obj_array);
            $worst_sellers = $order_obj_array;
        } else {
            $date_seller = 1;
            $current_date = date('Y-m-d', strtotime($this->get_current_time()));
            $order_obj = Order::whereDate('time', $current_date)->get();
            /////
            foreach($order_obj as $order) {
                $order->sale = OrderDish::where('order_id', $order->id)->sum('total_price');
            }
            $order_obj_array = $order_obj->toArray();
            $sale  = array_column($order_obj_array, 'sale');
            //get best sellers
            array_multisort($sale, SORT_DESC, $order_obj_array);
            $best_sellers = $order_obj_array;
            //get worst sellers
            array_multisort($sale, SORT_ASC, $order_obj_array);
            $worst_sellers = $order_obj_array;
        }
        $categories = Category::where('parent_id', null)->get();
        $subcategories = Category::whereNotNull('parent_id');

        return view('admin.saledata')->with(compact('daily_review', 'monthly_sales', 'best_sellers', 'worst_sellers', 'date_seller', 'search_day', 'categories'));
    }

    public function period() {
        $mode = 'daily';
        if (request()->has('mode') && isset(request()->mode)) {
            $mode = request()->mode;
        }
        $date = date('Y-m-d');
        $reviews = array();
        $review_cnt = 12;
        if (request()->has('date')) {
            $date = substr(request()->date, 0, 4).'-'.substr(request()->date, 7, 2).'-'.substr(request()->date, 12, 2);
        }
        $outputDate = new DateTime($date);
        $outputDate = $outputDate->format('Y年m月d日');

        $categories = Category::where('parent_id', null)->get();
        $subcategories = [];
        if (request()->has('category') && isset(request()->category)) {
            $subcategories = Category::where('parent_id', request()->category)->get();
        }

        $dishes = [];
        if (request()->has('subcategory') && isset(request()->subcategory)) {
            $dishes = Category::find(request()->subcategory)->dishes;
        }
        if (count($subcategories) == 0 && isset(request()->category)) {
            $dishes = Category::find(request()->category)->dishes;
        }

        $statItems = [];
        $type = '';
        if (request()->has('dish') && isset(request()->dish)) {
            $statItems = Dish::where('id', request()->dish)->get();
            $type = 'dish';
        }
        else if (request()->has('subcategory') && isset(request()->subcategory)) {
            $subCat = Category::find(request()->subcategory);
            $statItems = $subCat->dishes;
            $type = 'dish';
        } else {
            if (request()->has('category') && isset(request()->category)) {
                if (count($subcategories) > 0) {
                    $statItems = $subcategories;
                    $type = 'category';
                } else {
                    $cat = Category::find(request()->category);
                    $statItems = $cat->dishes;
                    $type = 'dish';
                }
            } else {
                $statItems = $categories;
                $type = 'category';
            }
        }
        if (count($statItems) > 0) {
            $statItems = $statItems->pluck('id')->toArray();
        }

        $catdishItems = [];

        for ($i = 0; $i < $review_cnt; $i++) {
            if ($mode == 'daily') {
                $prevDate = new DateTime($date);
                $dtRange = $this->determineDateRange($date, $mode);
                $reviews[$prevDate->format('Y年m月d日')] = $this->getStatistics($dtRange['startDate'], $dtRange['endDate'], $statItems, $type);
                $prevDate->modify('-1 day');
                $date = $prevDate->format('Y-m-d');
            } else if ($mode == 'weekly') {
                $prevDate = new DateTime($date);
                $dtRange = $this->determineDateRange($date, $mode);
                $reviews[$prevDate->format('Y年W週')] = $this->getStatistics($dtRange['startDate'], $dtRange['endDate'], $statItems, $type);
                $prevDate->modify('-1 week');
                $date = $prevDate->format('Y-m-d');
            } else if ($mode == 'monthly') {
                $prevDate = new DateTime($date);
                $dtRange = $this->determineDateRange($date, $mode);
                $reviews[$prevDate->format('Y年m月')] = $this->getStatistics($dtRange['startDate'], $dtRange['endDate'], $statItems, $type);
                $prevDate->modify('-1 month');
                $date = $prevDate->format('Y-m-d');
            }
        }

        if(request()->has('mode')) {
            $date_seller = request()->mode;
            if($date_seller == 'daily') {//Daily
                $current_date = date('Y-m-d', strtotime($this->get_current_time()));
                $order_obj = Order::whereDate('time', $current_date)->get();
            }elseif($date_seller == 'weekly') {//Weeekly
                $current_day = date("N");//dd($current_day);
                if($current_day == 7) {
                    $days_to_sunday = 7 - $current_day;
                    $days_from_monday = 14 - $current_day;
                }else {
                    $days_to_sunday =  - $current_day;
                    $days_from_monday = 7 - $current_day;
                }
                $start_date = date("Y-m-d", strtotime("$days_to_sunday Days"));
                $end_date = date("Y-m-d", strtotime("$days_from_monday Days"));
//                dd($start_date.':'.$end_date);
                $order_obj = Order::where('time', '>=', $start_date)->where('time', '<', $end_date)->get();
            }elseif($date_seller == 'monthly') {//Monthly
                $current_date = date('Y-m', strtotime($this->get_current_time()));
                $order_obj = Order::whereDate('time', 'like', '%' . $current_date . '%')->get();
            }
            ////
            foreach($order_obj as $order) {
                $order->sale = OrderDish::where('order_id', $order->id)->sum('total_price');
            }
            $order_obj_array = $order_obj->toArray();
            $sale  = array_column($order_obj_array, 'sale');
            //get best sellers
            array_multisort($sale, SORT_DESC, $order_obj_array);
            $best_sellers = $order_obj_array;
            //get worst sellers
            array_multisort($sale, SORT_ASC, $order_obj_array);
            $worst_sellers = $order_obj_array;
        } else {
            $date_seller = 1;
            $current_date = date('Y-m-d', strtotime($this->get_current_time()));
            $order_obj = Order::whereDate('time', $current_date)->get();
            /////
            foreach($order_obj as $order) {
                $order->sale = OrderDish::where('order_id', $order->id)->sum('total_price');
            }
            $order_obj_array = $order_obj->toArray();
            $sale  = array_column($order_obj_array, 'sale');
            //get best sellers
            array_multisort($sale, SORT_DESC, $order_obj_array);
            $best_sellers = $order_obj_array;
            //get worst sellers
            array_multisort($sale, SORT_ASC, $order_obj_array);
            $worst_sellers = $order_obj_array;
        }

        return view('admin.saleperiod')->with(compact('reviews', 'mode', 'outputDate', 'categories', 'subcategories', 'dishes', 'best_sellers', 'worst_sellers'));
    }

    public function determineDateRange($date, $mode) {
        if ($mode == 'daily') {
            $startDate = new DateTime($date);
            $endDate = new DateTime($date);
            $endDate->modify('Next Day');
            return [
                'startDate' => $startDate,
                'endDate' => $endDate,
            ];
        } else if ($mode == 'weekly') {
            $mon = new DateTime($date);
            $mon->modify('monday this week');
            $sun = new DateTime($date);
            $sun->modify('Next Monday');
            return [
                'startDate' => $mon,
                'endDate' => $sun,
            ];
        } else if ($mode == 'monthly') {
            $first = new DateTime($date);
            $first->modify('first day of this month');
            $last = new DateTime($date);
            $last->modify('Next Month');
            $last->modify('first day of this month');
            return [
                'startDate' => $first,
                'endDate' => $last,
            ];
        }
    }

    public function getStatistics($startDate, $endDate, $selectedIds, $type) {
        $startDate = $startDate->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        $order_obj = Order::where('time', '>=', $startDate)->where('time', '<', $endDate)->get();
        $sales = 0;
        $guest_count = 0;
        $order_count = 0;
        $dish_ids = array();
        $calls_count = 0;
        $feedback_count = 0;

        foreach($order_obj as $order) {
            // $sales += OrderPay::where('order_id', $order->id)->sum('total');
            $orderdishes = $order->orderdishes;
            foreach($orderdishes as $od) {
                if ($type == 'dish') {
                    if (in_array($od->dish->id, $selectedIds)) {
                        $sales += $od->total_price;
                        array_push($dish_ids, $od->dish->id);
                        $guest_count += $order->guest;
                        $order_count ++;
                        $calls_count += $order->calls;
                        if($order->review != Null)
                            $feedback_count++;
                    }
                } else if ($type == 'category') {
                    $allCats = [];
                    $allCats = array_merge($selectedIds, $allCats);
                    foreach($selectedIds as $sid) {
                        $subs = Category::find($sid)->subs->pluck('id')->toArray();
                        $allCats = array_merge($allCats, $subs);
                    }
                    $belongs = $od->dish->categories->pluck('id')->toArray();
                    if (count(array_intersect($allCats, $belongs)) > 0) {
                        $sales += $od->total_price;
                        array_push($dish_ids, $od->dish->id);
                        $guest_count += $order->guest;
                        $order_count ++;
                        $calls_count += $order->calls;
                        if($order->review != Null)
                            $feedback_count++;
                    }
                }
            }
        }

        //get sales
        if($sales > 0) {
            $daily_review['sales'] = '¥'.$sales;
        } else {
            $daily_review['sales'] = '¥0';
        }
        //get group
        $dish_ids = array_unique($dish_ids);
        $groups = array();
        $groups = Dish::whereIn('id', $dish_ids)->pluck('group_id')->toArray();
        if(count($groups) > 0) {
            $daily_review['group'] = count($groups);
        } else {
            $daily_review['group'] = '0';
        }
        //get guest
        $daily_review['guest'] = $guest_count;
        //get order_count
        $daily_review['orders'] = $order_count;
        //get calls
        $daily_review['calls'] = $calls_count;
        //get feedback
        $daily_review['feedback'] = $feedback_count;

        return $daily_review;
    }

//    public function date_seller(Request $request) {
//
//        $option = $request->option;
//
//        return $option;
//    }

//    public function review()
//    {
//        if(request()->has('search_date')) {//by date change
//            $search_date = request()->search_date;
//            if(request()->d_s == 'up') {
//                $search_date = date('Y-m-d', strtotime(' +1 day', strtotime($search_date)));
//            } else if(request()->d_s == 'down') {
//                $search_date = date('Y-m-d', strtotime(' -1 day', strtotime($search_date)));
//            }
//            $search_date = date('Y-m-d', strtotime($search_date));
//        } else {//date nochange
//            $search_date = date('Y-m-d', strtotime($this->get_current_time()));
//        }
//        $search_display_date = date("d M Y", strtotime($search_date));
//
//        $order_obj = Order::whereDate('time', $search_date)->where('review', '!=', Null)->orderBy('time')->orderBy('table_name')->get();
//        $table_obj = Table::get();
//        $order_obj = $this->get_order_obj($order_obj);
//        foreach($order_obj as $orderObj) {
//            $count_calling = 0;
//            foreach($orderObj->ordertables as $orderTable) {
//                if($orderTable->calling_time != Null)
//                    $count_calling +=1;
//            }
//            $orderObj->calling_count = $count_calling;
//        }
//
//        return view('admin.review')->with(compact('order_obj', 'search_display_date'));
//    }
//
//    public function get_order_obj($order_obj) {
//
//        $order_tables = array();
//
//        foreach($order_obj as $order)
//        {
//            $table_display_names = array();
//            $table_order_names = array();
//            if(count($order->ordertables) > 0){
//                foreach ($order->ordertables as $ordertables) {
//                    $order_tables[] = $ordertables['table_id'];
//                    $table_display_names[] = $this->get_table_name($ordertables['table_id']);
//                }
//                $table_order_names[] = $this->get_table_name($order->ordertables[0]['table_id']);//get first table only in order
//            }
//            $order->display_time = $this->get_time_data(substr($order->time, 11, 5));
//            $order->table_display_names = $table_display_names;
//            $order->table_order_names = $table_order_names;
//        }
//
//        return $order_obj;
//    }

//    public function src_review() {
//
//        $src_date = request()->src_date;
//        $order_obj = Order::whereDate('time', $src_date)->where('review', '!=', Null)->orderBy('time')->orderBy('table_name')->get();
//
//        $table_obj = Table::get();
//        $order_obj = $this->get_order_obj($order_obj);
//        foreach($order_obj as $orderObj) {
//            $count_calling = 0;
//            foreach($orderObj->ordertables as $orderTable) {
//                if($orderTable->calling_time != Null)
//                    $count_calling +=1;
//            }
//            $orderObj->calling_count = $count_calling;
//        }
//        $search_display_date = $src_date;
//        return view('admin.review_src')->with(compact('order_obj', 'search_display_date'));
//    }
}
