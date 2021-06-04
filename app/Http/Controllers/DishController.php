<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\Category;
use App\Model\Kitchen;
use App\Model\Badge;
use App\Model\Option;
use App\Model\Dish;
use App\Model\DishOption;
use App\Model\DishCategory;
use App\Model\DishOrder;
use App\Model\Receipt;

class DishController extends Controller
{
    //
    public function index(){

        $dishes = Dish::orderBy('name_en', 'asc')->get();//dd($dishes);
        $sort = "desc";

        foreach($dishes as $ds)
        {
            if($ds->group_id){
                $group_ids = explode(",", $ds->group_id);
                $group_id = substr($group_ids[0], 1, -1);
                $group_name = Kitchen::where('id', $group_id)->pluck('name')->first();
                $ds->group_name = $group_name;
            }
        }

        return view('admin.dish.list')->with(compact('dishes', 'sort'));
    }

    public function edit($id) {
        $obj = Dish::find($id);

        $groups = Kitchen::get();
        $badges = Badge::where('active', '=', '1')->get();
        $options = Option::get();

        $main_cats = Category::get_mains();
        $main_cat = Category::find($obj->category_id);
        $sub_cats = [];
        if($main_cat != null)
            $sub_cats = $main_cat->subs;
        $dish_cats_tmp = DishCategory::getCategoryFromDish($id);
        $dish_cats = array();
        $dish_cats_ids = '';
        foreach ($dish_cats_tmp as $dish_cats_arr) {
            $dish_cats[] = $dish_cats_arr['categories_id'];
            $dish_cats_ids .= $dish_cats_arr['categories_id'].",";
        }
        $dish_cats_ids = rtrim($dish_cats_ids,", ");

        $gst = Receipt::find(1)->gst;

        $group_id = $obj->group_id;
        if($group_id) {
            $obj->groups = explode(',', $group_id);
        }

        return view('admin.dish.edit')->with(compact('main_cats', 'sub_cats', 'groups', 'badges', 'options', 'obj', 'dish_cats', 'dish_cats_ids', 'gst'));
    }

    public function add(){
        $obj = new Dish();
        $main_cats = Category::get_mains();
        $groups = Kitchen::get();
        $badges = Badge::where('active', '=', '1')->get();
        $options = Option::get();
        $obj->groups = [];
        $gst = Receipt::find(1)->gst;
        return view('admin.dish.edit')->with(compact('main_cats', 'groups', 'badges', 'options', 'obj', 'gst'));
    }

    public function preview($id){
        $obj = Dish::find($id);//dd($obj);
//        $option = $obj->options();dd($option);
        return view('admin.dish.preview')->with(compact('obj'));
    }

    public function previewpost(Request $request){
        $obj = Dish::find($request->id);
        if($request->get("sold_out") != null){
            $obj->sold_out = 1;
        } else {
            $obj->sold_out = 0;
        }
        if($request->get("active") != null){
            $obj->active = 1;
        } else {
            $obj->active = 0;
        }
        $obj->save();
        return redirect()->route('admin.dish.edit', ['id' => $request->id]);
    }

    public function change_sold_active(Request $request){
        $obj = Dish::find($request->id);
        if($request->get("sold_out") == true){
            $obj->sold_out = 1;
        } else {
            $obj->sold_out = 0;
        }
        if($request->get("active") == true){
            $obj->active = 1;
        } else {
            $obj->active = 0;
        }
        $obj->save();
    }

    public function store()
    {
        if(is_null(request()->id)){
            $max = Dish::max('id');
            $obj = new Dish();
            $obj->name_en = request()->get('name_en');
            $obj->name_cn = request()->get('name_cn');
            $obj->name_jp = request()->get('name_jp');
            $obj->desc_en = request()->get('desc_en');
            $obj->desc_cn = request()->get('desc_cn');
            $obj->desc_jp = request()->get('desc_jp');
            $obj->price = request()->get('price');
            $obj->order =  $max + 1;
            //$obj->category_id = request()->get('category_id');
            //$obj->sub_category_id = request()->get('sub_category_id');
            if(request()->get('groups')) {
                $group_ids = request()->get('groups');
                $grs = '';
                foreach($group_ids as $gr){
                    $grs .= '&'.$gr.'&,';
                }
                $obj->group_id = rtrim($grs, ',');
            } else {
                $obj->group_id = '';
            }
            $obj->badge_id = request()->get('badge_id');
            $obj->eatin_breakfast = request()->get('eatin_breakfast') == "on" ? 1 : 0;
            $obj->eatin_lunch = request()->get('eatin_lunch') == "on" ? 1 : 0;
            $obj->eatin_tea = request()->get('eatin_tea') == "on" ? 1 : 0;
            $obj->eatin_dinner = request()->get('eatin_dinner') == "on" ? 1 : 0;
            $obj->takeaway_breakfast = request()->get('takeaway_breakfast') == "on" ? 1 : 0;
            $obj->takeaway_lunch = request()->get('takeaway_lunch') == "on" ? 1 : 0;
            $obj->takeaway_tea = request()->get('takeaway_tea') == "on" ? 1 : 0;
            $obj->takeaway_dinner = request()->get('takeaway_dinner') == "on" ? 1 : 0;
            $obj->save();

            $file = request()->file('image');
            if($file != null){
                $destinationPath = 'dishes';
                $destinationFile = "dish_".$obj->id.".png";
                $file->move($destinationPath, $destinationFile);
                $obj->image = $destinationFile;
            }
            $obj->save();

            if(request()->get('category_id') != ''){
                $category_ids = explode(',', rtrim(request()->get('category_id'), ","));
                foreach ($category_ids as $category_id) {
                    $category_match = new DishCategory();
                    $category_match->dish_id = $obj->id;
                    $category_match->categories_id = $category_id;
                    $category_match->save();
                }
            }

            if(request()->get('opts') != '') {
                $opts = request()->get('opts');
                foreach($opts as $op){
                    $dish_option = new DishOption();
                    $dish_option->dish_id = $obj->id;
                    $dish_option->option_id = $op;
                    $dish_option->save();
                }
            }
        } else {
            //dd(request());
            $obj = Dish::find(request()->id);
            $obj->name_en = request()->get('name_en');
            $obj->name_cn = request()->get('name_cn');
            $obj->name_jp = request()->get('name_jp');
            $obj->desc_en = request()->get('desc_en');
            $obj->desc_cn = request()->get('desc_cn');
            $obj->desc_jp = request()->get('desc_jp');
            $obj->price = request()->get('price');
            //$obj->category_id = request()->get('category_id');
            //$obj->sub_category_id = request()->get('sub_category_id');
            if(request()->get('groups')) {
                $group_ids = request()->get('groups');
                $grs = '';
                foreach($group_ids as $gr){
                    $grs .= '&'.$gr.'&,';
                }
                $obj->group_id = rtrim($grs, ',');
            } else {
                $obj->group_id = '';
            }
            $obj->badge_id = request()->get('badge_id');
            $obj->eatin_breakfast = request()->get('eatin_breakfast') == "on" ? 1 : 0;
            $obj->eatin_lunch = request()->get('eatin_lunch') == "on" ? 1 : 0;
            $obj->eatin_tea = request()->get('eatin_tea') == "on" ? 1 : 0;
            $obj->eatin_dinner = request()->get('eatin_dinner') == "on" ? 1 : 0;
            $obj->takeaway_breakfast = request()->get('takeaway_breakfast') == "on" ? 1 : 0;
            $obj->takeaway_lunch = request()->get('takeaway_lunch') == "on" ? 1 : 0;
            $obj->takeaway_tea = request()->get('takeaway_tea') == "on" ? 1 : 0;
            $obj->takeaway_dinner = request()->get('takeaway_dinner') == "on" ? 1 : 0;

            $file = request()->file('image');
            if($file != null){
                $destinationPath = 'dishes';
                $destinationFile = "dish_".$obj->id.".png";
                $file->move($destinationPath, $destinationFile);
                $obj->image = $destinationFile;
            }
            $obj->save();
            DishCategory::where('dish_id', request()->id)->delete();
            if(request()->get('category_id') != ''){
                $category_ids = explode(',', rtrim(request()->get('category_id'), ","));
                foreach ($category_ids as $category_id) {
                    $category_match = new DishCategory();
                    $category_match->dish_id = request()->id;
                    $category_match->categories_id = $category_id;
                    $category_match->save();
                }
            }
            $old_opts = $obj->dishoptions->pluck('id');
            DishOption::whereIn('id', $old_opts)->delete();

            if(request()->get('opts') != ''){
                $opts = request()->get('opts');
                foreach($opts as $op){
                    $dish_option = new DishOption();
                    $dish_option->dish_id = $obj->id;
                    $dish_option->option_id = $op;
                    $dish_option->save();
                }
            }
        }
        return redirect()->route('admin.dish');
    }

    public function deleteDish()
    {
        Dish::where('id', request()->id)->delete();
        DishCategory::where('dish_id', request()->id)->delete();
        DishOption::where('dish_id', request()->id)->delete();
//        DishOrder::where('dish_id', request()->id)->delete();
        return redirect()->route('admin.dish');
    }

    public function sortDish()
    {
        if(request()->get('sortType') == "asc"){
            $dishes = Dish::orderBy('name_en','asc')->get();
            $sort = "desc";
        }else{
            $dishes = Dish::orderBy('name_en','desc')->get();
            $sort = "asc";
        }

        foreach($dishes as $ds)
        {
            if($ds->group_id){
                $group_ids = explode(",", $ds->group_id);
                $group_id = substr($group_ids[0], 1, -1);
                $group_name = Kitchen::where('id', $group_id)->pluck('name')->first();
                $ds->group_name = $group_name;
            }
        }

        return view('admin.dish.list')->with(compact('dishes', 'sort'));
    }

}
