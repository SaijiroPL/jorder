<?php

namespace App\Http\Controllers;

use App\Model\DishCategory;
use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\Dish;
use Illuminate\Support\Facades\Input;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::where('parent_id' ,'=', null)->orderby('order')->get();
        $dishes = Dish::orderby('order')->get();
        return view('admin.category.list')->with(compact('categories', 'dishes'));
    }

    public function add()
    {
        $max = Category::max('id');
        $new = new Category();
        $new->name_en = request()->name_en;
        $new->name_cn = request()->name_cn;
        $new->name_jp = request()->name_jp;
        $new->parent_id = request()->parent_id;
        $new->is_unlimited = request()->is_unlimited;
        $new->order =  $max + 1;
        $new->save();

        $parent = Category::find(request()->parent_id);
        if(!is_null($parent)){
            $parent->has_subs = 1;
            $parent->save();
            return (string)view('part.subcategory_item', ['sub' => $new])->render();
        } else {
            return (string)view('part.category_item', ['cat' => $new])->render();
        }

        //return redirect()->route('admin.category');
    }

    public function edit_title()
    {
        $parent = Category::find(request()->parent_id);

        if(!is_null($parent)){
            $parent->name_en = request()->name_en;
            $parent->name_cn = request()->name_cn;
            $parent->name_jp = request()->name_jp;
            $parent->is_unlimited = request()->is_unlimited;
            $parent->save();
            return request()->name_en;
            //return (string)view('part.category_item', ['cat' => $parent])->render();
            //return (string)view('part.subcategory_item', ['sub' => $parent])->render();
        }
    }

    public function delete($id){
        $cat = Category::find($id);
        //remove from match table
        DishCategory::where('categories_id', $id)->delete();
        foreach ($cat->subs as $subs) {
            $dish_category = new DishCategory();
            $dish_category->where('categories_id', $subs->id)->delete();
        }
        $cat->delete();
        Category::where("parent_id", $id)->delete();
    }

    public function subs()
    {
        $main = request()->parent;
        $subs = Category::find($main)->subs;
        return (string)view('part.subcategory', compact('subs'))->render();
    }

    public function subs_list()
    {
        $main = request()->category;
        $subs = Category::find($main)->subs;
        $subs_count = count($subs);
        $shtml = (string)view('part.subcategory_list', compact('subs'))->render();
        $dish_html = self::dish_list();
        if(empty($shtml))   $add_flag = 1;
        else    $add_flag = '';
        return response()->json(['subcategory_list' => $shtml, 'dishes' => $dish_html, 'subs_count' => $subs_count, 'add_flag' => $add_flag]);
    }

    public function dish_list()
    {
//        $dishes = DishCategory::getDishFromCategory(request()->category);
        $dishes = Category::find(request()->category)->dishes;
        $dishes_arr = $dishes->toArray();
        $dish_ids = '';
        $dish_count = 0;
        foreach ($dishes_arr as $dish_arr) {
            $dish_ids .= $dish_arr['id'].',';
            $dish_count ++;
        }
        $dish_ids = rtrim($dish_ids, ",");
        return (string)view('part.category_dish', compact('dishes', 'dish_ids', 'dish_count'))->render();
    }

    public function dish_add()
    {
        $dish_ids = request()->dish_ids;
        $category_id = request()->category_id;
        if($dish_ids != ""){
            $dish_id_arr = explode(",", $dish_ids);
//            DishCategory::where('categories_id', $subcategory_id)->delete();
            foreach ($dish_id_arr as $dish_id) {
                DishCategory::where('dish_id', $dish_id)->where('categories_id', $category_id)->delete();
                $dish_category = new DishCategory();
                $dish_category->dish_id = $dish_id;
                $dish_category->categories_id = $category_id;
//                echo $dish_id;
//                if($subcategory_id != ""){
//                    $dish_category->categories_id = $subcategory_id;
//                }
                $dish_category->save();
            }
        }else{
            DishCategory::where('categories_id', $category_id)->delete();
        }
        return "success";
    }

    public function dish_delete($dish_id)
    {
        $category_id = request()->category_id;
        DishCategory::where("dish_id", $dish_id)->where('categories_id', $category_id)->delete();
        return "success";
    }
}
