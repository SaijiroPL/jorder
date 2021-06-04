<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Table;
use App\Model\Room;

class TableController extends Controller
{
    public function index()
    {
        $tables_arr = Table::get()->toArray();
        $tables = array();
        if(count($tables_arr) > 0){
            foreach ($tables_arr as $table_arr) {
                $tables[$table_arr["id"]] = $table_arr;
            }
            $last_id_arr = Table::select('id')->orderBy('id', 'desc')->first()->toArray();
            $new_id = $last_id_arr['id'] + 1;
        }else{
            $new_id = 1;
        }
        $table_type = array(0=>'0', '1', '2', '3', '4', '5', '6', '7', '8', 'Line');

        $room_size = Room::find(1);

        return view('admin.table')->with(compact('tables', 'table_type', 'new_id', 'room_size'));
    }

    public function store()
    {
        $tables_arr_tmp = json_decode(request()->get('saved_arr'));//dd($tables_arr_tmp);
        $tables_arr = array();
        foreach ($tables_arr_tmp as $key => $table_arr_tmp){
            if($table_arr_tmp != NULL){
                $tables_arr[$table_arr_tmp->id] = (array)$table_arr_tmp;
            }
        }
        $table_obj = Table::select('id')->get()->toArray();
        if(count($table_obj) > 0){
            $last_id_arr = Table::select('id')->orderBy('id', 'desc')->first()->toArray();
        }
        // dd($tables_arr);
        foreach ($tables_arr as $table_arr) {
            if(isset($last_id_arr['id']) && $last_id_arr['id'] >= $table_arr['id']){
                $table = Table::find($table_arr['id']);
                $table->x = $table_arr['x'];
                $table->y = $table_arr['y'];
                $table->type = $table_arr['type'];
                $table->index = $table_arr['index'];
                $table->name = $table_arr['name'];
                $table->update();
            }else{
                $table = new Table();
                $table->x = $table_arr['x'];
                $table->y = $table_arr['y'];
                $table->type = $table_arr['type'];
                $table->index = $table_arr['index'];
                $table->name = $table_arr['name'];
                $table->save();
            }
        }
        if(request()->get('id') > 0){
            $table = Table::find(request()->get('id'));
            $table->delete();
        }
        return redirect()->route('admin.table');
    }

    public function change_roomsize(Request $request)
    {
        $room_width = $request->room_width;
        $room_height = $request->room_height;
        $room = Room::find(1);
        $room->width = $room_width;
        $room->height = $room_height;
        $room->save();

    }

}
