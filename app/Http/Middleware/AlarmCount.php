<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Order;
use App\Model\OrderTable;
use App\Model\Booked;
use Illuminate\Support\Facades\View;

class AlarmCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $count_notification = collect();

        //ready_pay_count
        $count_notification->ready_pay_count = Order::where('pay_flag', 1)->get()->count();

        //calling_count
        $count_notification->calling_count = OrderTable::where('calling_time', '<>', null)->distinct()->pluck('order_id')->count();

        //review_count
        $count_notification->review_count = Order::where('pay_flag',  '<>', 2)->where('review', '!=', Null)->get()->count();

        //note_count
        $count_notification->note_count = Order::where('pay_flag',  '<>', 2)->where('note', '!=', Null)->get()->count();

        //display count of seated and booking status
        $count_notification->seated = Order::where('pay_flag',  '<>', 2)->where('status', 'seated')->get()->count();

        $current_date = date('Y-m-d');
        $count_notification->bookings = Booked::where('time', '<=',$current_date . " 23:59:59" )
            ->where('time', '>=',$current_date . " 00:00:00" )
            ->where('status', 'booking')
            ->where('timer_flag', 0)
            ->get()->count();

        View::share(compact('count_notification'));

        date_default_timezone_set("Asia/Tokyo");

//        date_default_timezone_set("Asia/Tokyo");
//        $current_date = strtoupper(date('d M Y'));
//        View::share(compact('current_date'));

        return $next($request);
    }
}
