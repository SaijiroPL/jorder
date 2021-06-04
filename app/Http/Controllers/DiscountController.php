<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\Dish;
use App\Model\Discount;
use App\Model\Receipt;

class DiscountController extends Controller
{
    public function index(){
        $discounts = Discount::get();
        $end_sort = "desc";
        $start_sort = "desc";
        $check_discount_list = $this->check_discount_list();
        return view('admin.discount.list')->with(compact('discounts', 'end_sort', 'start_sort', 'check_discount_list'));
    }

    public function add(){
        $discount_dish_id = Discount::pluck('dish_id');
        $dishes = Dish::whereNotIn('id', $discount_dish_id)->get();
        $obj = new Discount();
        $profile = Receipt::find(1);
        $gst = $profile->gst;
        return view('admin.discount.form')->with(compact('dishes', 'gst', 'obj'));
    }

    public function edit($id)
    {
        $obj = Discount::find($id);
        $dish = $obj->dish->id;
        $dishes = Dish::where('id', $dish)->get();
        $profile = Receipt::find(1);
        $gst = $profile->gst;
        return view('admin.discount.form')->with(compact('dishes', 'gst', 'obj', 'dish'));
    }

    public function store()
    {
        if(is_null(request()->id)){//add

            $discount_obj = new Discount();

            $end_type = request()->end_type;
            $start_date = "";
            $end_date = "";
            if($end_type == 0){//Normal add
                $start_date_arr = explode(" ", request()->checked_start_date);
                $end_date_arr = explode(" ", request()->checked_end_date);
                $start_date = $start_date_arr[0].' '.$start_date_arr[1];
                $end_date = $end_date_arr[0].' '.$end_date_arr[1];
            }
            if($end_type == 2){//Checked Now
                $start_date = $this->get_current_time();
                $end_date_arr = explode(" ", request()->checked_end_date);
                $end_date = $end_date_arr[0].' '.$end_date_arr[1];
            }
            if($end_type == 3){//indefined checked
                $start_date_arr = explode(" ", request()->checked_start_date);
                $start_date = $start_date_arr[0].' '.$start_date_arr[1];
                $end_date = '2030-01-01 00:00:00';
            }
            if($end_type == 4){//indefined and now checked
                $start_date = $this->get_current_time();
                $end_date = '2030-01-01 00:00:00';
            }
            $discount_obj->start = $start_date;
            $discount_obj->end = $end_date;
            $dish_id = request()->dish_id;
            $discount = request()->discount;
            $timeslot_breakfast = (request()->timeslot_breakfast == "on") ? 1 : 0;
            $timeslot_lunch = (request()->timeslot_lunch == "on") ? 1 : 0;
            $timeslot_tea = (request()->timeslot_tea == "on") ? 1 : 0;
            $timeslot_dinner = (request()->timeslot_dinner == "on") ? 1 : 0;

            $discount_obj->dish_id = $dish_id;
            $discount_obj->discount = $discount;
            $discount_obj->timeslot_breakfast = $timeslot_breakfast;
            $discount_obj->timeslot_lunch = $timeslot_lunch;
            $discount_obj->timeslot_tea = $timeslot_tea;
            $discount_obj->timeslot_dinner = $timeslot_dinner;
            $discount_obj->end_type = $end_type;
            $discount_obj->save();
        }else{
            $discount_obj = Discount::find(request()->id);
            $end_type = request()->end_type;
            $start_date = "";
            $end_date = "";
            if($end_type == 1){//End Now
                //delete
                Discount::where('id', request()->id)->delete();
            } else {//update
                if($end_type == 0){//Normal add
                    $start_date_arr = explode(" ", request()->checked_start_date);
                    $end_date_arr = explode(" ", request()->checked_end_date);
                    $start_date = $start_date_arr[0].' '.$start_date_arr[1];
                    $end_date = $end_date_arr[0].' '.$end_date_arr[1];
                }
                if($end_type == 2){//Checked Now
                    $start_date = $this->get_current_time();
                    $end_date_arr = explode(" ", request()->checked_end_date);
                    $end_date = $end_date_arr[0].' '.$end_date_arr[1];
                }
                if($end_type == 3){//indefined checked
                    $start_date_arr = explode(" ", request()->checked_start_date);
                    $start_date = $start_date_arr[0].' '.$start_date_arr[1];
                    $end_date = '2030-01-01 00:00:00';
                }
                if($end_type == 4){//indefined and now checked
                    $start_date = $this->get_current_time();
                    $end_date = '2030-01-01 00:00:00';
                }

                $discount_obj->start = $start_date;
                $discount_obj->end = $end_date;

                $dish_id = request()->dish_id;
                $discount = request()->discount;

                $timeslot_breakfast = (request()->timeslot_breakfast == "on") ? 1 : 0;
                $timeslot_lunch = (request()->timeslot_lunch == "on") ? 1 : 0;
                $timeslot_tea = (request()->timeslot_tea == "on") ? 1 : 0;
                $timeslot_dinner = (request()->timeslot_dinner == "on") ? 1 : 0;

                $discount_obj->dish_id = $dish_id;
                $discount_obj->discount = $discount;
                $discount_obj->timeslot_breakfast = $timeslot_breakfast;
                $discount_obj->timeslot_lunch = $timeslot_lunch;
                $discount_obj->timeslot_tea = $timeslot_tea;
                $discount_obj->timeslot_dinner = $timeslot_dinner;
                $discount_obj->end_type = $end_type;
                $discount_obj->update();
            }

        }
        return redirect()->route('admin.discount');
    }

    public function sortOption()
    {
        $start_sort = request()->get('start_sort');
        $end_sort = request()->get('end_sort');
        $sortField = request()->get('sortField');
        if($sortField == "start"){
            $discounts = Discount::orderBy($sortField, $start_sort)->get();
            $start_sort = ($start_sort == "asc") ? "desc" : "asc";
        }else{
            $discounts = Discount::orderBy($sortField, $end_sort)->get();
            $end_sort = ($end_sort == "asc") ? "desc" : "asc";
        }
        $check_discount_list = $this->check_discount_list();
        return view('admin.discount.list')->with(compact('discounts', 'start_sort', 'end_sort', 'check_discount_list'));
    }

    //check exist of discount list
    public function check_discount_list()
    {
        $discount_dish_id = Discount::pluck('dish_id');
        $dishes = Dish::whereNotIn('id', $discount_dish_id)->get();
        $check_discount_list = 0;
        if(count($dishes) == 0) {
            $check_discount_list = 1;
        }
        return $check_discount_list;
    }
}
