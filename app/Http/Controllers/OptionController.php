<?php

namespace App\Http\Controllers;

use App\Model\Dish;
use App\Model\DishOption;
use Illuminate\Http\Request;

use App\Model\Option;
use App\Model\Item;
use App\Model\Receipt;

class OptionController extends Controller
{
    //
    public function index()
    {
        $options = Option::get();
        $sort_type_name = "desc";
        $sort_type_display_name = "desc";
        foreach($options as $option) {
            $dish_ids = DishOption::where('option_id', $option->id)->pluck('dish_id');
            //dd($dish_ids);
            $related_dishes = '';
            foreach($dish_ids as $ds) {
                $dish_name = Dish::where('id', $ds)->pluck('name_en')->first();
                $related_dishes .= $dish_name.', ';
            }
            $option->related_dishes = substr($related_dishes, 0, -2);
        }
        return view('admin.option.list')->with(compact('options', 'sort_type_name', 'sort_type_display_name'));
    }
    public function edit($id)
    {
        $obj = Option::find($id);
        $profile = Receipt::find(1);
        $gst = $profile->gst;
        return view('admin.option.form')->with(compact('obj', 'gst'));
    }
    public function add()
    {
        $obj = new Option();
        $profile = Receipt::find(1);
        $gst = $profile->gst;
        return view('admin.option.form')->with(compact('obj', 'gst'));
    }
    public function delete($id)
    {
        $obj = Option::find($id);
        foreach($obj->items as $i){
            $i->delete();
        }
        $obj->delete();
        return redirect()->route('admin.option');
    }
    public function store()
    {
        // dd(request());
        if(is_null(request()->id)){ //new item
            $obj = new Option();
            $obj->name = request()->name;
            $obj->display_name_en = request()->display_name_en;
            $obj->display_name_cn = request()->display_name_cn;
            $obj->display_name_jp = request()->display_name_jp;
            $obj->multi_select = request()->multi_select == "on" ? '1' : '0';
            if(($obj->multi_select == 1) && (request()->number_selection == null))
                $obj->number_selection = 1;
            if(($obj->multi_select == 1) && (request()->number_selection != null))
                $obj->number_selection = request()->number_selection;
            if($obj->multi_select == 0)
                $obj->number_selection = 1;
            $obj->photo_visible = request()->photo_visible == "on" ? '1' : '0';
            $obj->save();

            $option_id = $obj->id;
            if(request()->has('new-option')){
                $new_options = request()->get('new-option');
                foreach($new_options as $i => $n){
                    $item = new Item();
                    $item->option_id = $option_id;
                    $item->name = $n['name'];
                    $item->price = $n['price'];
                    $item->stock = $n['stock'];
                    $item->save();
                    if(request()->photo_visible == "on" && request()->file('new-option')[$i]['image'] != ''){

                        $file = request()->file('new-option')[$i]['image'];
                        $destinationPath = 'options';
                        $destinationFile = "item_".$item->id.".png";
                        $file->move($destinationPath, $destinationFile);
                        $item->image = $destinationFile;
                    }
                    $item->save();
                }
            }
        } else {
            $obj = Option::find(request()->id);
            $obj->name = request()->name;
            $obj->display_name_en = request()->display_name_en;
            $obj->display_name_cn = request()->display_name_cn;
            $obj->display_name_jp = request()->display_name_jp;
            $obj->multi_select = request()->multi_select == "on" ? '1' : '0';
            if(($obj->multi_select == 1) && (request()->number_selection == null))
                $obj->number_selection = 1;
            if(($obj->multi_select == 1) && (request()->number_selection != null))
                $obj->number_selection = request()->number_selection;
            if($obj->multi_select == 0)
                $obj->number_selection = 1;
            $obj->photo_visible = request()->photo_visible == "on" ? '1' : '0';
            $obj->save();

            if(request()->has('prev-data')){
                $prev_data = request()->get('prev-data');
                foreach($prev_data as $id => $p){
                    $item = Item::find($id);
                    $item->name = $p['name'];
                    $item->price = $p['price'];
                    $item->stock = $p['stock'];

                    if(isset(request()->file('prev-data')[$id]['image']) != null){
                        $image = request()->file('prev-data')[$id]['image'];
                        $destinationPath = 'options';
                        $destinationFile = "item_".$item->id.".png";
                        $image->move($destinationPath, $destinationFile);
                        $item->image = $destinationFile;
                    }
                    $item->save();
                }
            }

            if(request()->has('deleted')){
                $deleted_ids = request()->get('deleted');
                Item::whereIn('id', $deleted_ids)->delete();
            }

            if(request()->has('new-option')){
                $new_options = request()->get('new-option');
                foreach($new_options as $i => $n){
                    $item = new Item();
                    $item->option_id = $obj->id;
                    $item->name = $n['name'];
                    $item->price = $n['price'];
                    $item->stock = $n['stock'];
                    $item->save();
                    if(request()->photo_visible == "on" && request()->file('new-option')[$i]['image'] != ''){
                        $file = request()->file('new-option')[$i]['image'];
                        $destinationPath = 'options';
                        $destinationFile = "item_".$item->id.".png";
                        $file->move($destinationPath, $destinationFile);
                        $item->image = $destinationFile;
                    }
                    $item->save();
                }
            }
        }
        return redirect()->route('admin.option');
    }

    public function sortOption()
    {
        $sort_type_name = request()->get('sort_type_name');
        $sort_type_display_name = request()->get('sort_type_display_name');
        $sortField = request()->get('sortField');
        if($sortField == "name"){
            $options = Option::orderBy('name',$sort_type_name)->get();
            $sort_type_name = ($sort_type_name == "asc") ? "desc" : "asc";
        }else{
            $options = Option::orderBy('display_name_en', $sort_type_display_name)->get();
            $sort_type_display_name = ($sort_type_display_name == "asc") ? "desc" : "asc";
        }
        $this->get_related_dish($options);
        return view('admin.option.list')->with(compact('options', 'sort_type_name', 'sort_type_display_name'));
    }

    public function get_related_dish($options) {

        foreach($options as $option) {
            $dish_ids = DishOption::where('option_id', $option->id)->pluck('dish_id');
            //dd($dish_ids);
            $related_dishes = '';
            foreach($dish_ids as $ds) {
                $dish_name = Dish::where('id', $ds)->pluck('name_en')->first();
                $related_dishes .= $dish_name.', ';
            }
            $option->related_dishes = substr($related_dishes, 0, -2);
        }
        return $options;
    }


}
