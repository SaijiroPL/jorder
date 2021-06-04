<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;

use App\Model\Role;
use App\Model\Receipt;
use App\Model\Kitchen;
use App\Model\Timeslot;
use App\Model\Holiday;
use App\Model\Badge;
use App\Model\Payment;
use App\Model\Mail;
use App\Model\Screentime;
use Storage;

class SettingController extends Controller
{
    //
    public function kitchen()
    {
        $kitchens = Kitchen::get();
        return view('admin.setting.kitchen')->with(compact('kitchens'));
    }
    public function timeslots()
    {
        $search_data = "";
        $slots = Timeslot::get();
        return view('admin.setting.timeslots')->with(compact('slots'))->with(compact('search_data'));
    }
    public function htimeslots()
    {
        $search_data = "";
        $slot = Timeslot::find(9);
        $holidays = Holiday::get();
        return view('admin.setting.htimeslots')->with(compact('slot', 'holidays'))->with(compact('search_data'));
    }
    public function customer()
    {
        $profile = Receipt::profile();
        return view('admin.setting.customer')->with(compact('profile'));
    }
    public function screentime()
    {
        $screentime = Screentime::orderby('id','desc')->get()->first();
        $profile = Receipt::profile();
        $img_name = Storage::disk('screen')->files();
        $backs = Storage::disk('background')->files();
        return view('admin.setting.screentime')->with(compact('screentime', 'img_name'))->with(compact('profile', 'backs'));
    }
    public function gst()
    {
        $profile = Receipt::profile();
        return view('admin.setting.gst')->with(compact('profile'));
    }
    public function gstpost()
    {
        $profile = Receipt::profile();
        $profile->gst = request()->gst;
        $profile->save();
        return redirect()->route('admin.setting.gst');
    }
    public function payment()
    {
        $payments = Payment::orderBy('sort')->get();
        return view('admin.setting.payment')->with(compact('payments'));
    }
    //receipt
    public function receipt()
    {
        $profile = Receipt::profile();
        return view('admin.setting.receipt')->with(compact('profile'));
    }
    public function receiptpost()
    {
        // dd(request());
        $profile = Receipt::profile();
        $profile->shop_name = request()->shop_name;
        $profile->abn = request()->abn;
        $profile->address = request()->address;
        $profile->phone = request()->phone;
        $profile->printer_ip = request()->printer_ip;
        $profile->email_address = request()->email_address;
        $profile->save();
        return redirect()->route('admin.setting.receipt');
    }
    public function changelogo()
    {
        // change logo image
        $file = request()->file('image-file');
        $destinationPath = 'receipt';
        $destinationFile = 'logo.png';
        $file->move($destinationPath, $destinationFile);

        // update receipt logo_image
        $profile = Receipt::find(1);
        $profile->logo_image = $destinationFile;
        $profile->save();

        return redirect()->route('admin.setting.receipt');
    }
    //badge
    public function badge()
    {
        $badges = Badge::get();
        return view('admin.setting.badge')->with(compact('badges'));
    }
    public function language()
    {
        $profile = Receipt::profile();
        return view('admin.setting.language')->with(compact('profile'));
    }
    public function password()
    {
        $profile = Receipt::profile();
        return view('admin.setting.password')->with(compact('profile'));
    }

    public function sendmail()
    {
        $mails = Mail::get();
        return view('admin.setting.sendmail')->with(compact('mails'));
    }
    public function kitchen_post()
    {
        // dd(request());
        if(request()->has('removed')){
            $removeitems = request()->removed;
            foreach($removeitems as $item){
                $kitchen = Kitchen::find($item);
                $kitchen->delete();
            }
        }

        if((request()->has('new'))){
            $newitems = request()->new;
            foreach($newitems as $key => $item){
                $kitchen = new Kitchen();
                $kitchen->name = $item;
                $kitchen->save();
            }
        }

        if((!request()->has('new'))){
            if((request()->has('orgitem'))){
                $orgitem = request()->orgitem;
                $ids = Kitchen::where('id' ,'>' ,0)->pluck('id');//dd($ids);
                foreach($ids as $key => $id){
                    $kitchen = Kitchen::where('id', $id)->update(['name' => $orgitem[$key]]);
                }
            }
        }

        return redirect()->route('admin.setting.kitchen');
    }
    public function timeslot_post()
    {
        //dd(request());
        $search_data = "";
        $regular = Timeslot::find(1);
        if(request()->has('regular-morning-on')){
            $regular->morning_on = 1;
            if($this->get_eat_time(request()->input('regular-morning-start')) > $this->get_eat_time(request()->input('regular-morning-end')))
            {
                $search_data = "In Morning Start time is greater than End time";
            }
            else{
                $chk_data = $this->check_time(request()->input('regular-morning-start'),request(),0,'regular');
                if(!$chk_data) $search_data = "There is greater than the start time in Morning.";
                $chk_data = $this->check_time(request()->input('regular-morning-end'),request(),0,'regular');
                if(!$chk_data) $search_data = "There is greater than the end time in Morning.";
            }

            $regular->morning_starts = request()->input('regular-morning-start');
            $regular->morning_ends = request()->input('regular-morning-end');
        } else {
            $regular->morning_on = 0;
        }
        if(request()->has('regular-lunch-on')){
            $regular->lunch_on = 1;
            if($this->get_eat_time(request()->input('regular-lunch-start')) > $this->get_eat_time(request()->input('regular-lunch-end')))
            {
                $search_data = "In Lunch Start time is greater than End time";
            }
            else{
                $chk_data = $this->check_time(request()->input('regular-lunch-start'),request(),1,'regular');
                if(!$chk_data) $search_data = "There is greater than the start time in Lunch.";
                $chk_data = $this->check_time(request()->input('regular-lunch-end'),request(),1,'regular');
                if(!$chk_data) $search_data = "There is greater than the end time in Lunch.";
            }

            $regular->lunch_starts = request()->input('regular-lunch-start');
            $regular->lunch_ends = request()->input('regular-lunch-end');
        } else {
            $regular->lunch_on = 0;
        }
        if(request()->has('regular-tea-on')){
            $regular->tea_on = 1;
            if($this->get_eat_time(request()->input('regular-tea-start')) > $this->get_eat_time(request()->input('regular-tea-end')))
            {
                $search_data = "In Tea Start time is greater than End time";
            }
            else{
                $chk_data = $this->check_time(request()->input('regular-tea-start'),request(),2,'regular');
                if(!$chk_data) $search_data = "There is greater than the start time in Tea.";
                $chk_data = $this->check_time(request()->input('regular-tea-end'),request(),2,'regular');
                if(!$chk_data) $search_data = "There is greater than the end time in Tea.";
            }

            $regular->tea_starts = request()->input('regular-tea-start');
            $regular->tea_ends = request()->input('regular-tea-end');
        } else {
            $regular->tea_on = 0;
        }
        if(request()->has('regular-dinner-on')){
            $regular->dinner_on = 1;
            if($this->get_eat_time(request()->input('regular-dinner-start')) > $this->get_eat_time(request()->input('regular-dinner-end')))
            {
                $search_data = "In Dinner Start time is greater than End time";
            }
            else{
                $chk_data = $this->check_time(request()->input('regular-dinner-start'),request(),3,'regular');
                if(!$chk_data) $search_data = "There is greater than the start time in Dinner.";
                $chk_data = $this->check_time(request()->input('regular-dinner-end'),request(),3,'regular');
                if(!$chk_data) $search_data = "There is greater than the end time in Dinner.";
            }

            $regular->dinner_starts = request()->input('regular-dinner-start');
            $regular->dinner_ends = request()->input('regular-dinner-end');
        } else {
            $regular->dinner_on = 0;
        }
        if(request()->has('regular-latenight-on')){
            $regular->latenight_on = 1;
            if($this->get_eat_time1(request()->input('regular-latenight-start')) > $this->get_eat_time1(request()->input('regular-latenight-end')))
            {
                $search_data = "In Late Start time is greater than End time";
            }
            else{
                $chk_data = $this->check_time(request()->input('regular-latenight-start'),request(),4,'regular');
                if(!$chk_data) $search_data = "There is greater than the start time in Late.";
                $chk_data = $this->check_time(request()->input('regular-latenight-end'),request(),4,'regular');
                if(!$chk_data) $search_data = "There is greater than the end time in Late.";
            }

            $regular->latenight_starts = request()->input('regular-latenight-start');
            $regular->latenight_ends = request()->input('regular-latenight-end');
        } else {
            $regular->latenight_on = 0;
        }
        if(empty($search_data))  $regular->save();

        // dd(request());
        $monday = Timeslot::find(2);
        $result_data = self::saveTimeSlot(request(), $monday, 'monday', 2);
        if(!empty($result_data))  $search_data = $result_data;
        $tuesday = Timeslot::find(3);
        $result_data = self::saveTimeSlot(request(), $tuesday, 'tuesday', 3);
        if(!empty($result_data))  $search_data = $result_data;
        $wednesday = Timeslot::find(4);
        $result_data = self::saveTimeSlot(request(), $wednesday, 'wednesday', 4);
        if(!empty($result_data))  $search_data = $result_data;
        $thursday = Timeslot::find(5);
        $result_data = self::saveTimeSlot(request(), $thursday, 'thursday', 5);
        if(!empty($result_data))  $search_data = $result_data;
        $friday = Timeslot::find(6);
        $result_data = self::saveTimeSlot(request(), $friday, 'friday', 6);
        if(!empty($result_data))  $search_data = $result_data;
        $saturday = Timeslot::find(7);
        $result_data = self::saveTimeSlot(request(), $saturday, 'saturday', 7);
        if(!empty($result_data))  $search_data = $result_data;
        $sunday = Timeslot::find(8);
        $result_data = self::saveTimeSlot(request(), $sunday, 'sunday', 8);
        if(!empty($result_data))  $search_data = $result_data;

        //return redirect()->route('admin.setting.timeslots')->with(compact('search_data'));
        $slots = Timeslot::get();
        return view('admin.setting.timeslots')->with(compact('slots'))->with(compact('search_data'));
    }

    public function htimeslot_post(){
        if(request()->has('new')){
            $new_holidays = request()->input('new');
            foreach($new_holidays as $new){
                $holiday = new Holiday();
                $holiday->holiday_date = $new;
                $holiday->save();
            }
        }
        if(request()->has('removed')){
            $removeitems = request()->removed;
            foreach($removeitems as $item){
                $holiday = Holiday::find($item);
                $holiday->delete();
            }
        }
        $holiday = Timeslot::find(9);
        $search_data = self::saveTimeSlot(request(), $holiday, 'holiday',9);
        //return redirect()->route('admin.setting.htimeslots');
        $slot = Timeslot::find(9);
        $holidays = Holiday::get();
        return view('admin.setting.htimeslots')->with(compact('slot', 'holidays'))->with(compact('search_data'));
    }

    private function saveTimeSlot($req, $obj, $key, $id){
        // dd('aaa');
        $search_data = "";
        if($req->has($key.'-on')){
            $obj->day_on = 1;
            if($req->has($key.'-morning-on')){
                $obj->morning_on = 1;
                if($this->get_eat_time(request()->input($key.'-morning-start')) > $this->get_eat_time(request()->input($key.'-morning-end')))
                {
                    $search_data = "In Morning of " . $key . " Start time is greater than End time";
                }
                else{
                    $chk_data = $this->check_time(request()->input($key.'-morning-start'),$req,0, $key);
                    if(!$chk_data) $search_data = "There is greater than the start time in Morning of " . $key . ".";
                    $chk_data = $this->check_time(request()->input($key.'-morning-end'),$req,0, $key);
                    if(!$chk_data) $search_data = "There is greater than the end time in Morning of " . $key . ".";
                }

                $obj->morning_starts = $req->input($key.'-morning-start');
                $obj->morning_ends = $req->input($key.'-morning-end');
            } else {
                $obj->morning_on = 0;
            }
            if($req->has($key.'-lunch-on')){
                $obj->lunch_on = 1;
                if($this->get_eat_time(request()->input($key.'-lunch-start')) > $this->get_eat_time(request()->input($key.'-lunch-end')))
                {
                    $search_data = "In Lunch of " . $key . " Start time is greater than End time";
                }
                else{
                    $chk_data = $this->check_time(request()->input($key.'-lunch-start'),$req,1, $key);
                    if(!$chk_data) $search_data = "There is greater than the start time in Lunch of " . $key . ".";
                    $chk_data = $this->check_time(request()->input($key.'-lunch-end'),$req,1, $key);
                    if(!$chk_data) $search_data = "There is greater than the end time in Lunch of " . $key . ".";
                }

                $obj->lunch_starts = $req->input($key.'-lunch-start');
                $obj->lunch_ends = $req->input($key.'-lunch-end');
            } else {
                $obj->lunch_on = 0;
            }
            if($req->has($key.'-tea-on')){
                $obj->tea_on = 1;
                if($this->get_eat_time(request()->input($key.'-tea-start')) > $this->get_eat_time(request()->input($key.'-tea-end')))
                {
                    $search_data = "In Tea of " . $key . " Start time is greater than End time";
                }
                else{
                    $chk_data = $this->check_time(request()->input($key.'-tea-start'),$req,2, $key);
                    if(!$chk_data) $search_data = "There is greater than the start time in Tea of " . $key . ".";
                    $chk_data = $this->check_time(request()->input($key.'-tea-end'),$req,2, $key);
                    if(!$chk_data) $search_data = "There is greater than the end time in Tea of " . $key . ".";
                }

                $obj->tea_starts = $req->input($key.'-tea-start');
                $obj->tea_ends = $req->input($key.'-tea-end');
            } else {
                $obj->tea_on = 0;
            }
            if($req->has($key.'-dinner-on')){
                $obj->dinner_on = 1;
                if($this->get_eat_time(request()->input($key.'-dinner-start')) > $this->get_eat_time(request()->input($key.'-dinner-end')))
                {
                     $search_data = "In Dinner of " . $key . " Start time is greater than End time";
                }
                else{
                    $chk_data = $this->check_time(request()->input($key.'-dinner-start'),$req,3, $key);
                    if(!$chk_data) $search_data = "There is greater than the start time in Dinner of " . $key . ".";
                    $chk_data = $this->check_time(request()->input($key.'-dinner-end'),$req,3, $key);
                    if(!$chk_data) $search_data = "There is greater than the end time in Dinner of " . $key . ".";
                }

                $obj->dinner_starts = $req->input($key.'-dinner-start');
                $obj->dinner_ends = $req->input($key.'-dinner-end');
            } else {
                $obj->dinner_on = 0;
            }
            if($req->has($key.'-latenight-on')){
                $obj->latenight_on = 1;
                if($this->get_eat_time1(request()->input($key.'-latenight-start')) > $this->get_eat_time1(request()->input($key.'-latenight-end')))
                {
                    $search_data = "In Late of " . $key . " Start time is greater than End time";
                }
                else{
                    $chk_data = $this->check_time(request()->input($key.'-latenight-start'),$req,4, $key);
                    if(!$chk_data) $search_data = "There is greater than the start time in Late of " . $key . ".";
                    $chk_data = $this->check_time(request()->input($key.'-latenight-end'),$req,4, $key);
                    if(!$chk_data) $search_data = "There is greater than the end time in Late of " . $key . ".";
                }

                $obj->latenight_starts = $req->input($key.'-latenight-start');
                $obj->latenight_ends = $req->input($key.'-latenight-end');
            } else {
                $obj->latenight_on = 0;
            }
            if($req->has($key.'-business-on')){
                $obj->non_business = 1;
            } else {
                $obj->non_business = 0;
            }
        } else {
            $obj->day_on = 0;
            $obj->morning_on = 0;
            $obj->lunch_on = 0;
            $obj->tea_on = 0;
            $obj->dinner_on = 0;
            $obj->latenight_on = 0;
        }
        if(empty($search_data))  $obj->save();

        return $search_data;
    }
    public function addbadge()
    {
        $b_name = request()->get('b-name');
        if(!empty($b_name)) {
            $img_name = request()->get('image-name');
            if( $b_name != $img_name ) {
                if(Badge::where('name', $img_name)->exists()){
                    return redirect()->route('admin.setting.badge');
                }
            }

            $file = request()->file('image-file');
            $destinationPath = 'badges';
            $destinationFile = $file->getClientOriginalName();
            $file->move($destinationPath, $destinationFile);

            $badge = Badge::find($b_name);
            $badge->name = $img_name;
            $badge->filepath = $destinationFile;
            $badge->update();
        }
        else {
            $img_name = request()->get('image-name');
            if(Badge::where('name', $img_name)->exists()){
                return redirect()->route('admin.setting.badge');
            }

            $file = request()->file('image-file');
            $destinationPath = 'badges';
            $destinationFile = $file->getClientOriginalName();
            $file->move($destinationPath, $destinationFile);

            $badge = new Badge();
            $badge->name = $img_name;
            $badge->filepath = $destinationFile;
            $badge->save();
        }


        return redirect()->route('admin.setting.badge');
    }
    public function customer_post()
    {
        $profile = Receipt::profile();
        $profile->customer = request()->customer_time;
        $profile->save();
        return redirect()->route('admin.setting.customer');
    }
    public function language_post()
    {
        $profile = Receipt::profile();
        if(request()->has('lang_jp')){
            $profile->lang_jp = '1';
        } else {
            $profile->lang_jp = '0';
        }
        if(request()->has('lang_kr')){
            $profile->lang_kr = '1';
        } else {
            $profile->lang_kr = '0';
        }
        if(request()->has('lang_cn')){
            $profile->lang_cn = '1';
        } else {
            $profile->lang_cn = '0';
        }
        $profile->save();
        return redirect()->route('admin.setting.language');
    }
    public function password_post()
    {

        if(request()->has('password_menu')){
            if(substr(request()->password_menu, 0, 1) != "*") {
                $user_menu = Role::where('name', 'menu')->get()->first();
                $user_menu->password = Hash::make(request()->password_menu);
                $user_menu->save();
            }
        }
        if(request()->has('password_takeawaymenu')){
            if(substr(request()->password_takeawaymenu, 0, 1) != "*") {
                $user_takeawaymenu = Role::where('name', 'takeawaymenu')->get()->first();
                $user_takeawaymenu->password = Hash::make(request()->password_takeawaymenu);
                $user_takeawaymenu->save();
            }
        }
        if(request()->has('password_kitchen')){
            if(substr(request()->password_kitchen, 0, 1) != "*") {
                $user_kitchen = Role::where('name', 'kitchen')->get()->first();
                $user_kitchen->password = Hash::make(request()->password_kitchen);
                $user_kitchen->save();
            }
        }
        if(request()->has('password_reception')){
            if(substr(request()->password_reception, 0, 1) != "*") {
                $user_reception = Role::where('name', 'reception')->get()->first();
                $user_reception->password = Hash::make(request()->password_reception);
                $user_reception->save();
            }
        }
        if(request()->has('password_admin')){
            if(substr(request()->password_admin, 0, 1) != "*") {
                $user_admin = Role::where('name', 'admin')->get()->first();
                $user_admin->password = Hash::make(request()->password_admin);
                $user_admin->save();
            }
        }

        return redirect()->route('admin.setting.password');

    }
    public function screentime_post()
    {
        // dd(request());
        $profile = Receipt::profile();
        if(request()->new_time){
                $user_menu = new Screentime();
                $user_menu->screen_time = request()->new_time;
                $user_menu->save();
        }
        if(request()->advertise_img) {
            $profile->advertise_img = request()->advertise_img;
            $profile->save();
        }
        if(request()->background_img) {
            $profile->background_img = request()->background_img;
            $profile->save();
        }

        return redirect()->route('admin.setting.screentime');
    }
    public function add_screensaver()
    {
        $file = request()->file('image-file');
        $destinationPath = request()->upload_type;
        $destinationFile = date('Y-m-d-').$file->getClientOriginalName();
        $file->move($destinationPath, $destinationFile);
        return redirect()->route('admin.setting.screentime');
    }
    public function active_badge()
    {
        Badge::where('created_at' ,'!=', null)->update(['active' => '0']);
        if(request()->has('actives')){
            Badge::whereIn('id', request()->actives)->update(['active' => '1']);
        }
        return redirect()->route('admin.setting.badge');
    }
    public function payment_post()
    {
        // dd(request());
        if(request()->has('new')){
            $newitems = request()->new;
            foreach($newitems as $item){
                $payment = new Payment();
                $payment->name = $item;
                $payment->save();
            }
        }
        if(request()->has('removed')){
            $removeitems = request()->removed;
            foreach($removeitems as $item){
                $payment = Payment::find($item);
                $payment->delete();
            }
        }
        if(request()->has('sort')){
            $sorts = request()->sort;
            foreach($sorts as $s){
                $detail = explode('_', $s);
                $key = $detail[0];
                $item = Payment::get_item_for_sort($key);
                $item->sort = $detail[1];
                $item->save();
            }
        }
        return redirect()->route('admin.setting.payment');
    }
    public function sendmail_post()
    {
        if(request()->has('new')){
            $newitems = request()->new;
            foreach($newitems as $item){
                $mail = new Mail();
                $mail->name = $item;
                $mail->save();
            }
        }
        if(request()->has('removed')){
            $removeitems = request()->removed;
            foreach($removeitems as $item){
                $mail = Mail::find($item);
                $mail->delete();
            }
        }
        return redirect()->route('admin.setting.sendmail');
    }

    public function check_time($time1,$req,$chk,$sup)
    {
        if(empty($time1)) return true;

        $time = $this->get_eat_time($time1);

        $breakfast_time_starts = $req->input($sup.'-morning-start');
        $breakfast_time_starts = $this->get_eat_time($breakfast_time_starts);
        $breakfast_time_ends = $req->input($sup.'-morning-end');
        $breakfast_time_ends = $this->get_eat_time($breakfast_time_ends);

        $lunch_time_starts = $req->input($sup.'-lunch-start');
        $lunch_time_starts = $this->get_eat_time($lunch_time_starts);
        $lunch_time_ends = $req->input($sup.'-lunch-end');
        $lunch_time_ends = $this->get_eat_time($lunch_time_ends);

        $tea_time_starts = $req->input($sup.'-tea-start');
        $tea_time_starts = $this->get_eat_time($tea_time_starts);
        $tea_time_ends = $req->input($sup.'-tea-end');
        $tea_time_ends = $this->get_eat_time($tea_time_ends);

        $dinner_time_starts = $req->input($sup.'-dinner-start');
        $dinner_time_starts = $this->get_eat_time($dinner_time_starts);
        $dinner_time_ends = $req->input($sup.'-dinner-end');
        $dinner_time_ends = $this->get_eat_time($dinner_time_ends);

        $latenight_time_starts = $req->input($sup.'-latenight-start');
        $latenight_time_starts = $this->get_eat_time1($latenight_time_starts);
        $latenight_time_ends = $req->input($sup.'-latenight-end');
        $latenight_time_ends = $this->get_eat_time1($latenight_time_ends);

        switch($chk)
        {
            case 0:
                if( !empty($lunch_time_starts) && $time > $lunch_time_starts && $req->has($sup.'-lunch-on'))
                {
                    $data = false;
                }
                elseif(!empty($tea_time_starts) && $time > $tea_time_starts && $req->has($sup.'-tea-on'))
                {
                    $data = false;
                }
                elseif(!empty($dinner_time_starts) && $time > $dinner_time_starts && $req->has($sup.'-dinner-on'))
                {
                    $data = false;
                }
                elseif(!empty($latenight_time_starts) && $time > $latenight_time_starts && $req->has($sup.'-latenight-on'))
                {
                    $data = false;
                }
                else
                {
                    $data = true;
                }
                break;
            case 1:
                if( !empty($breakfast_time_ends) && $time < $breakfast_time_ends && $req->has($sup.'-morning-on'))
                {
                    $data = false;
                }
                elseif(!empty($tea_time_starts) && $time > $tea_time_starts && $req->has($sup.'-tea-on'))
                {
                    $data = false;
                }
                elseif(!empty($dinner_time_starts) && $time > $dinner_time_starts && $req->has($sup.'-dinner-on'))
                {
                    $data = false;
                }
                elseif(!empty($latenight_time_starts) && $time > $latenight_time_starts && $req->has($sup.'-latenight-on'))
                {
                    $data = false;
                }
                else
                {
                    $data = true;
                }
                break;
            case 2:
                if( !empty($lunch_time_ends) && $time < $lunch_time_ends && $req->has($sup.'-lunch-on'))
                {
                    $data = false;
                }
                elseif(!empty($breakfast_time_ends) && $time < $breakfast_time_ends && $req->has($sup.'-morning-on'))
                {
                    $data = false;
                }
                elseif(!empty($dinner_time_starts) && $time > $dinner_time_starts && $req->has($sup.'-dinner-on'))
                {
                    $data = false;
                }
                elseif(!empty($latenight_time_starts) && $time > $latenight_time_starts && $req->has($sup.'-latenight-on'))
                {
                    $data = false;
                }
                else
                {
                    $data = true;
                }
                break;
            case 3:
                if( !empty($lunch_time_ends) && $time < $lunch_time_ends && $req->has($sup.'-lunch-on') )
                {
                    $data = false;
                }
                elseif(!empty($tea_time_ends) && $time < $tea_time_ends && $req->has($sup.'-tea-on'))
                {
                    $data = false;
                }
                elseif(!empty($breakfast_time_ends) && $time < $breakfast_time_ends && $req->has($sup.'-morning-on'))
                {
                    $data = false;
                }
                elseif(!empty($latenight_time_starts) && $time > $latenight_time_starts && $req->has($sup.'-latenight-on'))
                {
                    $data = false;
                }
                else
                {
                    $data = true;
                }
                break;
            case 4:
                if( substr($time1,-2) == "PM" ){
                    if( !empty($lunch_time_ends) && $time < $lunch_time_ends && $req->has($sup.'-lunch-on') )
                    {
                        $data = false;
                    }
                    elseif(!empty($tea_time_ends) && $time < $tea_time_ends && $req->has($sup.'-tea-on'))
                    {
                        $data = false;
                    }
                    elseif(!empty($dinner_time_ends) && $time < $dinner_time_ends && $req->has($sup.'-dinner-on'))
                    {
                        $data = false;
                    }
                    elseif(!empty($breakfast_time_ends) && $time < $breakfast_time_ends && $req->has($sup.'-morning-on'))
                    {
                        $data = false;
                    }
                    else
                    {
                        $data = true;
                    }
                }
                else{
                    if(!empty($breakfast_time_starts) && $time > $breakfast_time_starts && $req->has($sup.'-morning-on'))
                    {
                        $data = false;
                    }
                    else
                    {
                        $data = true;
                    }
                }
                break;
            default:
                $data = true;
        }

        return $data;
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

    public function get_eat_time1($eat_time)
    {
        if( substr($eat_time,-2) == "AM" ){
            if( substr($eat_time,0,2) == 12 ){
                $last_time = substr($eat_time,2,3);
                $eat_time = '24' . $last_time;
            }
            else{
                $first_time = (int)substr($eat_time,0,2);
                $first_time += 24;
                $last_time = substr($eat_time,2,3);
                $eat_time = $first_time . $last_time;
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
}
