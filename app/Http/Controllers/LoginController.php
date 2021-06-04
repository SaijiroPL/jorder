<?php

namespace App\Http\Controllers;

use App\Model\OrderTable;
use App\Model\Receipt;
use Hash;

use Illuminate\Http\Request;
use App\Events\NotificationEvent;

use App\Model\Role;
use App\Model\Table;
use App\Model\Order;
use App\Model\Dish;
use App\Model\Item;

class LoginController extends Controller
{
    //
    public function getLogin(Request $request)
    {
        //get ip address from database
        $profile = Receipt::profile();
        //last get table number(ordered already)
        //$table_last = Table::where('index', 0)->orderBy('id', 'desc')->first();
        $table_last = request()->session()->get('login_table_name');
        return view('login')->with(compact('table_last', 'profile'));
    }

//    public function appLogin(Request $request){
//
//        $urls = $request->url;
//
//        $url_list = explode('#', $urls);
//        $role = $url_list[1];
//        $url = $url_list[0];
//
//        session(['role' => $role]);
//
//        if($role == 'reception' || $role == 'master') {
//            return redirect()->route('reception.seated', ['status' => 'seated']);
//        } elseif($role == 'menu') {
//            $list = explode('*', $url);
//            $or_tb_list = explode('?', $list[1]);
//            $or_list = explode('=', $or_tb_list[0]);
//            $order_id = $or_list[1];
//            $tb_list = explode('=', $or_tb_list[1]);
//            $table_id = $tb_list[1];
//            return redirect()->route('customer.index', ['order_id'=>$order_id, 'table_id'=>$table_id]);
//        } elseif($role == 'kitchen') {
//            return redirect(route('kitchen.main_screen'));
//        }
//
//    }

    public function postLogin(Request $request){

        $name = $request->role;
        $password = $request->password;
        $user = Role::where('name', '=', $name)->first();
        if(!isset($user)){
            return redirect(route('loginform'));
        }
        if(Hash::check($password, $user->password)){
            session(['role' => $name]);
            if($request->role == "Reception" || $request->role == "Master") {
                return redirect()->route('reception.seated', ['status' => 'seated']);
            }
            else if($request->role == "Menu" || $request->role == "TakeawayMenu"){
                $menu_type = $request->role;
                $table_name = $request->table;
                if($table_name) {
                    $table = Table::select('id')->where('name', $table_name)->get();
                    if(count($table) > 0){
                        $order = $table[0]->order;
                        if(count($order) > 0){
                            $order_id = $order[0]->id;
                            Order::where('id', $order_id)->update(['menu_type' => $menu_type]);
                            request()->session()->put('language', 2);
                            return redirect()->route('customer.index', ['order_id'=>$order_id, 'table_id'=>$table[0]->id]);
                        }
                        else{
                            $alert = "There is no order registered!";
                            return redirect(route('loginform'))->with(compact('alert'));
                        }
                    }
                    else {
                        $alert = "Please enter table name correctly!";
                        return redirect(route('loginform'))->with(compact('alert'));
                    }
                }
                else {
                    $alert = "Please enter table name!";
                    return redirect(route('loginform'))->with(compact('alert'));
                }
            }
            else if($request->role == "Kitchen") {
                return redirect(route('kitchen.main_screen'));
            }
        } else {
            if($password)
                $alert = "Please enter your password correctly!";
            else
                $alert = "Please enter password!";
            return redirect(route('loginform'))->with(compact('alert'));
        }
    }

    public function adminLogin(Request $request)
    {
        $profile = Receipt::profile();
        $slag = $request->admin_status;//dd($slag);
        $alert = '';
        if (session('role', '') == "Master") {
            switch($slag)
            {
                case 'edit_menu':
                    $route = 'admin.dish';
                    break;
                case 'saledata':
                    $route = 'admin.saledata.period';
                    break;
                case 'setting':
                    $route = 'admin.setting.receipt';
                    break;
                case 'table':
                    $route = 'admin.table';
                    break;
            }
            return redirect(route($route));
        }
        return view('admin.login')->with(compact('slag', 'alert', 'profile'));
    }

    public function adminPostLogin(Request $request)
    {
        $slag = $request->slag;
        $name = 'admin';
        $password = $request->password;
        $user = Role::where('name', $name)->first();
        $master = Role::where('name', 'master')->first();
        if(!isset($user) && !isset($master)){
            return redirect(route('loginform'));
        }
        if($password) {
            if(Hash::check($password, $user->password) || Hash::check($password, $master->password)){
                switch($slag)
                {
                    case 'edit_menu':
                        $route = 'admin.dish';
                        break;
                    case 'saledata':
                        $route = 'admin.saledata.period';
                        break;
                    case 'setting':
                        $route = 'admin.setting.receipt';
                        break;
                    case 'table':
                        $route = 'admin.table';
                        break;
                }
                return redirect(route($route));
            }
            else {
                $alert = "Please enter your password correctly!";
                return view('admin.login')->with(compact('slag', 'alert'));
            }
        }
        else {
            $alert = "Please enter password!";
            return view('admin.login')->with(compact('slag', 'alert'));
        }

    }

    public function change_ip(Request $request)
    {
        $changed_ip = $request->changed_ip;
        Receipt::where('id', 1)->update(['ip_address' => $changed_ip]);
    }

//    public function rename_dish() {
//        $dish_arr = Dish::select('id', 'image')->get()->toArray();
//        for($i=0;$i<count($dish_arr);$i++) {
//            if($dish_arr[$i]['image'] != null) {
//                $rename = "dish_".$dish_arr[$i]['id'].".png";
//                try {
//                    rename(public_path().'/dishes/'.$dish_arr[$i]['image'], public_path().'/dishes/'.$rename);
//                    Dish::where('id', $dish_arr[$i]['id'])->update(['image' => $rename]);
//                } catch (\Exception $ex) {
//                    echo($dish_arr[$i]['id'].'<br>');
//                }
//            }
//        }

//        $item_arr = Item::select('id', 'image')->get()->toArray();
//        for($i=0;$i<count($item_arr);$i++) {
//            if($item_arr[$i]['image'] != null) {
//                $rename = "item_".$item_arr[$i]['id'].".png";
//                try {
//                    rename(public_path().'/options/'.$item_arr[$i]['image'], public_path().'/options/'.$rename);
//                    Item::where('id', $item_arr[$i]['id'])->update(['image' => $rename]);
//                } catch (\Exception $ex) {
//                    echo($item_arr[$i]['id'].'<br>');
//                }
//            }
//        }
//    }
}
