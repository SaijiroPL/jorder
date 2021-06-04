<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

use App\Mail\SalesDayReportEmail;
use Illuminate\Support\Facades\DB;
use Excel;

class NowMailController extends Controller
{
    public function now_sendmail()
    {

        date_default_timezone_set("Asia/Tokyo");

        Excel::create('sales_report', function($excel) {

            $excel->sheet('Sales Day Report', function($sheet) {

                // ===1. Sales Data ===
                $order_pay = DB::table('order_pay')->whereDate('created_at', date('Y-m-d',strtotime("-1 days")))->get();
                $receipt = DB::table('receipt')->find(1);
                $orders = DB::table('orders')->whereDate('created_at', date('Y-m-d',strtotime("-1 days")))->get();

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
                $order_cancel_dish = DB::table('order_dish_match')->whereDate('created_at', date('Y-m-d',strtotime("-1 days")))->where('amend_count', '<', 0)->get();

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

                $category_sale_view = DB::table('category_sales')->whereDate('created_at', date('Y-m-d',strtotime("-1 days")))->get()->toArray();
//            dd($category_sale_view[0]->name_en);
                $category_sales_data = array();
                for($i=0;$i<count($categories);$i++) {
                    $category_sales_data[$i]['id'] = $categories[$i]->id;
                    $category_sales_data[$i]['name'] = $categories[$i]->name_en;
                    $category_sales_data[$i]['qty'] = 0;
                    $category_sales_data[$i]['sales'] = 0;
                    for($j=0;$j<count($category_sale_view);$j++) {
                        if($category_sales_data[$i]['id'] == $category_sale_view[$j]->categories_id) {
                            $category_sales_data[$i]['qty'] += $category_sale_view[$j]->count;
                            $category_sales_data[$i]['sales'] += $category_sale_view[$j]->total;
                        }
                    }
                }

                // ===7. Item Sales Data ===
                $items = DB::table('items')->get()->toArray();

                $item_sale_view = DB::table('item_sales')->whereDate('created_at', date('Y-m-d',strtotime("-1 days")))->get();
//            dd($item_sale_view);
                $item_sales_data = array();
                for($i=0;$i<count($items);$i++) {
                    $item_sales_data[$i]['id'] = $items[$i]->id;
                    $item_sales_data[$i]['name'] = $items[$i]->name;
                    $item_sales_data[$i]['qty'] = 0;
                    $item_sales_data[$i]['sales'] = 0;
                    for($j=0;$j<count($item_sale_view);$j++) {
                        if($item_sales_data[$i]['id'] == $item_sale_view[$j]->item_id) {
                            $item_sales_data[$i]['qty'] += $item_sale_view[$j]->count;
                            $item_sales_data[$i]['sales'] += $item_sale_view[$j]->total;
                        }
                    }
                }

                // ===8. Hourly Item Ranking ===
                //get item_id list
                $item_id_all_list = array();
                $item_id_list = array();
                for($i=0;$i<count($item_sale_view);$i++) {
                    array_push($item_id_all_list, $item_sale_view[$i]->item_id);
                    $item_id_list = array_values(array_unique($item_id_all_list));
                }

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

                        if($item_id_list[$i] == $item_sale_view[$j]->item_id) {

                            $hourly_item_ranking[$i]['item_name'] = $item_sale_view[$j]->name;

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
                        if($item_id_list[$i] == $item_sale_view[$j]->item_id) {

                            $hourly_cooktime_ranking[$i]['item_name'] = $item_sale_view[$j]->name;

                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 10) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['10'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 11) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['11'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 12) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['12'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 13) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['13'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 14) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['14'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 15) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['15'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 16) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['16'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 17) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['17'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 18) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['18'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 19) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['19'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 20) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['20'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 21) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['21'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 22) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['22'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 23) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['23'] = round($elapsed / $count_temp);
                            }
                            if(substr($item_sale_view[$j]->start_time, 11, 3) == 0) {
                                $count_temp = 0;
                                $elapsed = 0;
                                $count_temp += $item_sale_view[$j]->count;
                                $elapsed += (int)((strtotime($item_sale_view[$j]->ready_time) - strtotime($item_sale_view[$j]->start_time)) / 60);
                                $hourly_cooktime_ranking[$i]['0'] = round($elapsed / $count_temp);
                            }

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

                // item_total sort desc(asc:a<=>b, desc:b<=>a)
                usort($hourly_item_ranking, function($a, $b) {
                    return $b['item_total'] <=> $a['item_total'];
                });

                // cook_avg_time sort desc(asc:a<=>b, desc:b<=>a)
                usort($hourly_cooktime_ranking, function($a, $b) {
                    return $b['cook_avg_time'] <=> $a['cook_avg_time'];
                });

                // ===10. Feedbacks ===
                $feedbacks = DB::table('orders')->whereDate('created_at', date('Y-m-d',strtotime("-1 days")))->where('review', '<>', Null)->get();

                $sheet->loadView('emails.sales_day')->with(compact('receipt', 'order_pay', 'sales_data', 'card_type', 'card_sales_data', 'cancel_items', 'hour_sales_data',
                    'category_sales_data', 'item_sales_data', 'hourly_item_ranking', 'hourly_cooktime_ranking', 'feedbacks'));

            });

        })->store('xlsx', public_path('excel/exports'));

        $filename = public_path().'/excel/exports/sales_report.xlsx';

        Mail::to('manager@kuromatsu.com.au')->send(new SalesDayReportEmail($filename));
    }

}
