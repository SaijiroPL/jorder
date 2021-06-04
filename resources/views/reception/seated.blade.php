@extends('layout.admin_layout')
@section('title', session('role'))
@section('content')
@php
 $scren_scale = null !== session('scale_value') ? session('scale_value') : 100;
@endphp
<div style="padding-top:4%;"></div>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="text-center exit_fullscreen display-none" id="exit-fullscreen">
            <span class="white-text exit_fullscreen_letter">{{ __('admin.Table.EXIT_FULL_SCREEN') }}</span>
            <img src="{{ asset('img/exit-fullscreen.png') }}"/>
        </div>
    </div>
    <div class="row">
        <div class="col-1 pr-0 pl-0 text-center" style="margin-right: -45px;" id="display-method">
            <div class="text-center display_all_content" id="display-all">
                <img src="{{ asset('img/arrow.png') }}" class="display_all_btn"/>
                <p class="white-text font-weight-bold display_all">{{ __('admin.Table.SHOW_FULL_SCREEN') }}</p>
            </div>
            <div class="text-center display_scale">
                <img src="{{ asset('img/plus_full.png') }}" class="plus_btn" onclick="tableZoomIn()"/>
                <p class="pt5 scale_value" id="scale-value"></p>
                <img src="{{ asset('img/minus.png') }}" class="minus_btn" onclick="tableZoomOut();"/>
            </div>
        </div>
        <div class="room-content" id="room-content">
            <div class="room-div">
            <input type="hidden" id="book_table_obj" value="{{ json_encode($table_obj) }}">
            @if($status != "booking")
                @foreach($table_obj as $key => $table)
                    @if(count($table->order) > 0)
                        <div class="table-common" style="margin: {{$table['y']*20}}px 10px 10px {{$table['x']*20}}px;">
                            @if($table->type == 0)
                            <div style="margin: 0 0 0 0;">
                                <div style="display: inline-block;">
                                <div>
                                    <div class="white table-c-style text-center">
                                        <div style="width: 118px;height: 118px;"
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->order[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: -12px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            @elseif($table->type == 1)
                            <div style="margin: 0 0 0 0;">
                                <div style="display: inline-block;">
                                <div style="height: 110px;">
                                    <div class="white table-c-style text-center">
                                        <div style="width: 118px;height: 118px;"
                                            onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->order[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: -12px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-center"></span>
                            </div>
                            </div>
                            @elseif($table->type == 2)
                            <div style="margin: -4px 0 0 0;">
                                <div style="display: inline-block;">
                                <span class="ch-1 ch-ena ch-top ch-top-center"></span>
                                <div style="height: 110px;">
                                    <div class="white table-c-style text-center">
                                        <div style="width: 118px;height: 118px;"
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->order[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: 8px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-center"></span>
                            </div>
                            </div>
                            @elseif($table->type == 3)
                            <div style="margin: -4px 0 0 -38px;">
                                <div style="display: inline-block;">
                                <span class="ch-1 ch-ena ch-top ch-top-center" style="margin-left: 73px;"></span>
                                <div style="height: 118px;">
                                    <div class="white table-c-style text-center" style="margin-left: 38px;">
                                        <div style="width: 118px;height: 118px;"
                                            onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->order[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: 8px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                            </div>
                            </div>
                            @elseif($table->type == 4)
                            <div style="margin: -4px 0 0 -38px;">
                                <div style="display: inline-block;">
                                <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                <div style="height: 118px;">
                                    <div class="white table-c-style text-center" style="margin-left: 38px;">
                                        <div style="width: 118px;height: 118px;"
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->order[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: 8px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                            </div>
                            </div>
                            @elseif($table->type == 5)
                            <div style="margin: -4px 0 0 6px;">
                                <span class="ch-2 ch-ena ch-left ch-left-center"></span>
                                <div style="display: inline-block;">
                                <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                <div style="height: 118px;">
                                    <div class="white table-c-style text-center" style="margin-left: 38px;">
                                        <div style="width: 118px;height: 118px;"
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->order[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                            </div>
                            </div>
                            @elseif($table->type == 6)
                            <div style="margin: -4px 0 0 6px;">
                                <span class="ch-2 ch-ena ch-left ch-left-center"></span>
                                <div style="display: inline-block;">
                                    <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->order[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                    <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                                @endif
                                                <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                    <br>
                                                    <p class="ml-0"  id="time1_{{$key}}"></p>
                                                </h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                                @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                    <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                                @else
                                                    <img class="alarm" id="belled_icon_{{$table->id}}">
                                                @endif
                                                @if($table->orderTable[0]->calling_time != Null)
                                                    <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                                @else
                                                    <img class="alarm" id="call_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->review != Null)
                                                    <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                                @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->note != Null)
                                                    <img class="alarm" src="{{ asset('img/note.png') }}">
                                                @else
                                                    <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-ena ch-right ch-right-center"></span>
                            </div>
                            @elseif($table->type == 7)
                            <div style="margin: -4px 0 0 5px;">
                                <span class="ch-2 ch-ena ch-left ch-left-top"></span>
                                <span class="ch-2 ch-ena ch-left ch-left-bottom"></span>
                                <div style="display: inline-block;">
                                    <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->order[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                    <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                                @endif
                                                <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                    <br>
                                                    <p class="ml-0"  id="time1_{{$key}}"></p>
                                                </h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                                @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                    <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                                @else
                                                    <img class="alarm" id="belled_icon_{{$table->id}}">
                                                @endif
                                                @if($table->orderTable[0]->calling_time != Null)
                                                    <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                                @else
                                                    <img class="alarm" id="call_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->review != Null)
                                                    <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                                @else
                                                    <img class="alarm" id="review_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->note != Null)
                                                    <img class="alarm" src="{{ asset('img/note.png') }}">
                                                @else
                                                    <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-ena ch-right ch-right-center"></span>
                            </div>
                            @elseif($table->type == 8)
                            <div style="margin: -4px 0 0 6px;">
                                <span class="ch-2 ch-ena ch-left ch-left-top"></span>
                                <span class="ch-2 ch-ena ch-left ch-left-bottom"></span>
                                <div style="display: inline-block;">
                                    <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->order[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                    <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                                @endif
                                                <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                    <br>
                                                    <p class="ml-0"  id="time1_{{$key}}"></p>
                                                </h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                                @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                    <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                                @else
                                                    <img class="alarm" id="belled_icon_{{$table->id}}">
                                                @endif
                                                @if($table->orderTable[0]->calling_time != Null)
                                                    <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                                @else
                                                    <img class="alarm" id="call_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->review != Null)
                                                    <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                                @else
                                                    <img class="alarm" id="review_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->note != Null)
                                                    <img class="alarm" src="{{ asset('img/note.png') }}">
                                                @else
                                                    <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-ena ch-right ch-right-top"></span>
                                <span class="ch-2 ch-ena ch-right ch-right-bottom"></span>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="table-common" id="selected-{{$key}}"  style="margin: {{$table['y']*20}}px 10px 10px {{$table['x']*20}}px;">
                            @if($table->type == 0)
                            <div style="margin: 0 0 0 0;">
                                <div style="display: inline-block;">
                                    <div>
                                        <div class="white table-c-style-disable text-center">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($table->type == 1)
                            <div style="margin: 0 0 0 0">
                                <div style="display: inline-block;">
                                    <div style="height: 110px;">
                                        <div class="white table-c-style-disable text-center">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
                                </div>
                            </div>
                            @elseif($table->type == 2)
                            <div style="margin: 0 0 0 0">
                                <div style="display: inline-block; margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-center"></span>
                                    <div style="height: 110px;">
                                        <div class="white table-c-style-disable text-center">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
                                </div>
                            </div>
                            @elseif($table->type == 3)
                            <div style="margin: 0 0 0 -38px;">
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-center" style="margin-left: 73px;"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                            </div>
                            @elseif($table->type == 4)
                            <div style="margin: 0 0 0 -38px;">
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                            <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                            </div>
                            @elseif($table->type == 5)
                            <div style="margin: 0 0 0 6px;">
                                <span class="ch-2 ch-dis ch-left ch-left-center"></span>
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                            </div>
                            @elseif($table->type == 6)
                            <div style="margin: 0 0 0 6px;">
                                <span class="ch-2 ch-dis ch-left ch-left-center"></span>
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-dis ch-right ch-right-center"></span>
                            </div>
                            @elseif($table->type == 7)
                            <div style="margin: 0 0 0 5px;">
                                <span class="ch-2 ch-dis ch-left ch-left-top"></span>
                                <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-dis ch-right ch-right-center"></span>
                            </div>
                            @elseif($table->type == 8)
                            <div style="margin: 0 0 0 5px;">
                                <span class="ch-2 ch-dis ch-left ch-left-top"></span>
                                <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                 onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-dis ch-right ch-right-top"></span>
                                <span class="ch-2 ch-dis ch-right ch-right-bottom"></span>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($table->type == 9){{--Line--}}
                        <div class="table-common" style="margin: {{$table['y']*20}}px 10px 10px {{$table['x']*20}}px;">
                            <div class="text-center line-style"
                                 @if($table->index == "1"){{--right--}}
                                 style="padding-right: 200px;"
                                 @else
                                 style="padding-bottom: 200px;"
                                 @endif>
                            </div>
                        </div>
                    @endif
                @endforeach
            @elseif($status == "booking")
                @foreach($table_obj as $key => $table)
                    @if(count($table->order) > 0)
                        <div class="table-common" style="margin: {{$table['y']*20}}px 10px 10px {{$table['x']*20}}px;">
                            @if($table->type == 0)
                            <div style="margin: 0 0 0 0;">
                                <div style="display: inline-block;">
                                <div>
                                    <div class="white table-c-style text-center">
                                        <div style="width: 118px;height: 118px;"
                                            @if(count($table->book) > 0)
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @else
                                             onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @endif
                                            >
                                             @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: -12px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            @elseif($table->type == 1)
                            <div style="margin: 0 0 0 0;">
                                <div style="display: inline-block;">
                                <div style="height: 110px;">
                                    <div class="white table-c-style text-center">
                                        <div style="width: 118px;height: 118px;"
                                            @if(count($table->book) > 0)
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @else
                                             onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @endif
                                            >
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: -12px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-center"></span>
                            </div>
                            </div>
                            @elseif($table->type == 2)
                            <div style="margin: -4px 0 0 0;">
                                <div style="display: inline-block;">
                                <span class="ch-1 ch-ena ch-top ch-top-center"></span>
                                <div style="height: 110px;">
                                    <div class="white table-c-style text-center">
                                        <div style="width: 118px;height: 118px;"
                                            @if(count($table->book) > 0)
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @else
                                             onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @endif
                                            >
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: 8px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-center"></span>
                            </div>
                            </div>
                            @elseif($table->type == 3)
                            <div style="margin: -4px 0 0 -38px;">
                                <div style="display: inline-block;">
                                <span class="ch-1 ch-ena ch-top ch-top-center" style="margin-left: 73px;"></span>
                                <div style="height: 118px;">
                                    <div class="white table-c-style text-center" style="margin-left: 38px;">
                                        <div style="width: 118px;height: 118px;"
                                            @if(count($table->book) > 0)
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @else
                                             onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @endif
                                            >
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: 8px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                            </div>
                            </div>
                            @elseif($table->type == 4)
                            <div style="margin: -4px 0 0 -38px;">
                                <div style="display: inline-block;">
                                <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                <div style="height: 118px;">
                                    <div class="white table-c-style text-center" style="margin-left: 38px;">
                                        <div style="width: 118px;height: 118px;"
                                            @if(count($table->book) > 0)
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @else
                                             onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @endif
                                            >
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: 8px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                            </div>
                            </div>
                            @elseif($table->type == 5)
                            <div style="margin: -4px 0 0 6px;">
                                <span class="ch-2 ch-ena ch-left ch-left-center"></span>
                                <div style="display: inline-block;">
                                <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                <div style="height: 118px;">
                                    <div class="white table-c-style text-center" style="margin-left: 38px;">
                                        <div style="width: 118px;height: 118px;"
                                            @if(count($table->book) > 0)
                                             onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @else
                                             onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                            @endif
                                            >
                                            @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                            @endif
                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                <br>
                                                <p class="ml-0"  id="time1_{{$key}}"></p>
                                            </h6>
                                            <p class="red-text ml-01" id="time_book_{{$key}}">
                                            </p>
                                            @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_icon_{{$table->id}}">
                                            @endif
                                            @if($table->orderTable[0]->calling_time != Null)
                                                <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->review != Null)
                                                <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_icon_{{$table->id}}">
                                            @endif
                                            @if($table->order[0]->note != Null)
                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                            </div>
                            </div>
                            @elseif($table->type == 6)
                            <div style="margin: -4px 0 0 6px;">
                                <span class="ch-2 ch-ena ch-left ch-left-center"></span>
                                <div style="display: inline-block;">
                                    <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                    <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                                @endif
                                                <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                    <br>
                                                    <p class="ml-0"  id="time1_{{$key}}"></p>
                                                </h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                                @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                                @else
                                                    <img class="alarm" id="belled_icon_{{$table->id}}">
                                                @endif
                                                @if($table->orderTable[0]->calling_time != Null)
                                                    <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                                @else
                                                    <img class="alarm" id="call_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->review != Null)
                                                    <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                                @else
                                                    <img class="alarm" id="review_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->note != Null)
                                                    <img class="alarm" src="{{ asset('img/note.png') }}">
                                                @else
                                                    <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-ena ch-right ch-right-center"></span>
                            </div>
                            @elseif($table->type == 7)
                            <div style="margin: -4px 0 0 5px;">
                                <span class="ch-2 ch-ena ch-left ch-left-top"></span>
                                <span class="ch-2 ch-ena ch-left ch-left-bottom"></span>
                                <div style="display: inline-block;">
                                    <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                    <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                                @endif
                                                <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                    <br>
                                                    <p class="ml-0"  id="time1_{{$key}}"></p>
                                                </h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                                @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                                @else
                                                    <img class="alarm" id="belled_icon_{{$table->id}}">
                                                @endif
                                                @if($table->orderTable[0]->calling_time != Null)
                                                    <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                                @else
                                                    <img class="alarm" id="call_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->review != Null)
                                                    <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                                @else
                                                    <img class="alarm" id="review_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->note != Null)
                                                    <img class="alarm" src="{{ asset('img/note.png') }}">
                                                @else
                                                    <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-ena ch-right ch-right-center"></span>
                            </div>
                            @elseif($table->type == 8)
                            <div style="margin: -4px 0 0 6px;">
                                <span class="ch-2 ch-ena ch-left ch-left-top"></span>
                                <span class="ch-2 ch-ena ch-left ch-left-bottom"></span>
                                <div style="display: inline-block;">
                                    <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                    <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                                @endif
                                                <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                    <br>
                                                    <p class="ml-0"  id="time1_{{$key}}"></p>
                                                </h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                                @if($table->order[0]->pay_flag == '1' && $scren_scale > 20)
                                                <img class="alarm" id="belled_icon_{{$table->id}}" src="{{ asset('img/calling.png') }}">
                                                @else
                                                    <img class="alarm" id="belled_icon_{{$table->id}}">
                                                @endif
                                                @if($table->orderTable[0]->calling_time != Null)
                                                    <img class="alarm" id="call_icon_{{$table->id}}" src="{{ asset('img/alarm.png') }}">
                                                @else
                                                    <img class="alarm" id="call_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->review != Null)
                                                    <img class="alarm" id="review_icon_{{$table->id}}" src="{{ asset('img/msg.png') }}">
                                                @else
                                                    <img class="alarm" id="review_icon_{{$table->id}}">
                                                @endif
                                                @if($table->order[0]->note != Null)
                                                    <img class="alarm" src="{{ asset('img/note.png') }}">
                                                @else
                                                    <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-ena ch-right ch-right-top"></span>
                                <span class="ch-2 ch-ena ch-right ch-right-bottom"></span>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="table-common" id="selected-{{$key}}"  style="margin: {{$table['y']*20}}px 10px 10px {{$table['x']*20}}px;">
                            @if($table->type == 0)
                            <div style="margin: 0 0 0 0;">
                                <div style="display: inline-block;">
                                    <div>
                                        <div class="white table-c-style-disable text-center">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($table->type == 1)
                            <div style="margin: 0 0 0 0">
                                <div style="display: inline-block;">
                                    <div style="height: 110px;">
                                        <div class="white table-c-style-disable text-center">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
                                </div>
                            </div>
                            @elseif($table->type == 2)
                            <div style="margin: 0 0 0 0">
                                <div style="display: inline-block; margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-center"></span>
                                    <div style="height: 110px;">
                                        <div class="white table-c-style-disable text-center">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
                                </div>
                            </div>
                            @elseif($table->type == 3)
                            <div style="margin: 0 0 0 -38px;">
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-center" style="margin-left: 73px;"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                            </div>
                            @elseif($table->type == 4)
                            <div style="margin: 0 0 0 -38px;">
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                            </div>
                            @elseif($table->type == 5)
                            <div style="margin: 0 0 0 6px;">
                                <span class="ch-2 ch-dis ch-left ch-left-center"></span>
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                            </div>
                            @elseif($table->type == 6)
                            <div style="margin: 0 0 0 6px;">
                                <span class="ch-2 ch-dis ch-left ch-left-center"></span>
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-dis ch-right ch-right-center"></span>
                            </div>
                            @elseif($table->type == 7)
                            <div style="margin: 0 0 0 5px;">
                                <span class="ch-2 ch-dis ch-left ch-left-top"></span>
                                <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-dis ch-right ch-right-center"></span>
                            </div>
                            @elseif($table->type == 8)
                            <div style="margin: 0 0 0 5px;">
                                <span class="ch-2 ch-dis ch-left ch-left-top"></span>
                                <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
                                <div style="display: inline-block;margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <div style="width: 118px;height: 118px;"
                                                @if(count($table->book) > 0)
                                                onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $table->book[0]->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @else
                                                onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $table->id, "order_id" => 0, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'"
                                                @endif
                                                >
                                                <h6 class="black-text wb">{{ $table["name"] }}</h6>
                                                <p class="red-text ml-01" id="time_book_{{$key}}">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-dis ch-right ch-right-top"></span>
                                <span class="ch-2 ch-dis ch-right ch-right-bottom"></span>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($table->type == 9){{--Line--}}
                        <div class="table-common" style="margin: {{$table['y']*20}}px 10px 10px {{$table['x']*20}}px;">
                            <div class="text-center line-style"
                                 @if($table->index == "1"){{--right--}}
                                 style="padding-right: 200px;"
                                 @else
                                 style="padding-bottom: 200px;"
                                 @endif>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
            </div>
        </div>
        <div class="table_detail">
            <div class="row tab-header">
                <a href="{{route('reception.seated', ['status'=>'seated'])}}" class="black-text" style="width: 10vw; margin-left: 16px">
                    <span class="font-weight-bold fs-20">{{ __('reception.SEATED') }}</span>
                    <img src="{{ asset('img/seated.png') }}" style="width:65px;height:55px;"/>
                    <span class="font-weight-bold fs-25" style="margin-left: 2px">{{ $count_notification->seated }}</span>
                    @if($status == 'seated') <div class="tab_activate"></div> @endif
                </a>
                <a href="{{route('reception.seated', ['status'=>'waiting'])}}" class="black-text" style="width: 10vw">
                    <span class="font-weight-bold fs-20">{{ __('reception.WAITING') }}</span>
                    <img src="{{ asset('img/waiting.png') }}" style="width:55px;height:55px;"/>
                    <span class="font-weight-bold fs-25">{{ $count_notification->calling_count }}</span>
                    @if($status == 'waiting') <div class="tab_activate"></div> @endif
                </a>
                <a href="{{route('reception.seated', ['status'=>'booking'])}}" class="black-text" style="width: 10vw">
                    <span class="font-weight-bold fs-20">{{ __('reception.BOOKINGS') }}</span>
                    <img src="{{ asset('img/bookings.png') }}" style="width:55px;height:55px;"/>
                    <span class="font-weight-bold fs-25" id="book_cnt">{{ $booking_cnt /*$count_notification->bookings*/ }}</span>
                    @if($status == 'booking') <div class="tab_activate"></div> @endif
                </a>
            </div>
            <div style="height:62vh;overflow-x:hidden; overflow-y:auto;width: 385px;">
                <div class="row" style="width: 100%;">
                @if($status != "booking")
                    @foreach($order_side_obj as $kk => $order)
                        @foreach($order->table_order_names as $key => $ordertables)
                            <div class="border w-100 pt-2 pr-1 table_seated_list"
                                 onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $order->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                <div class="row w-100 p-0 m-0">
                                    <div class="col-lg-3 pr-0 col-xl-3">
                                        <div class="row" style="margin-left: -10px;">
                                            <p class="red-text font-weight-bold ml-0 fs-20" style="margin-left: 0px;" id="time_{{$kk}}">0分</p>
                                        </div>
                                        <div class="row table_name fs-15" style="text-align:center;">
                                            <table>
                                                <tr>
                                                    @if(count($order->table_display_names) > 1)
                                                        <img src="{{asset('img/plus_red.png')}}" class="corner">
                                                    @endif
                                                    <td style="width: 100px;height: 80px;background: #000;color:white;text-align: center;-ms-word-break: break-all;word-break: break-all;">
                                                        <b class="fs-20">
                                                            @if(strlen($ordertables) > 12)
                                                                {{ substr($ordertables, 0, 12)."..." }}
                                                            @else
                                                                {{ $ordertables }}
                                                            @endif
                                                        </b>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 pr-0 col-xl-4">
                                        <div class="row p-0 m-0">
                                            @if($order->pay_flag == '1')
                                                <img class="alarm" id="belled_list_{{$order->ordertables[0]['table_id']}}" style="margin-left:-7px;width: 25px;height: 23px;" src="{{ asset('img/calling.png') }}">
                                            @else
                                                <img class="alarm" id="belled_list_{{$order->ordertables[0]['table_id']}}">
                                            @endif
                                            @if($order->ordertables[0]->calling_time != Null)
                                                <img class="alarm" id="call_list_{{$order->ordertables[0]['table_id']}}" style="width: 21px;height: 23px;" src="{{ asset('img/alarm.png') }}">
                                            @else
                                                <img class="alarm" id="call_list_{{$order->ordertables[0]['table_id']}}">
                                            @endif
                                            @if($order->review != Null)
                                                <img class="alarm" id="review_list_{{$order->ordertables[0]['table_id']}}" style="width: 25px;height: 21px;" src="{{ asset('img/msg.png') }}">
                                            @else
                                                <img class="alarm" id="review_list_{{$order->ordertables[0]['table_id']}}">
                                            @endif
                                            @if($order->note != Null)
                                                <img class="alarm" style="width: 25px;height: 19px;" src="{{ asset('img/note.png') }}">
                                            @else
                                                <e class="alarm" style="width: 25px;height: 19px;"></e>
                                            @endif
                                        </div>
                                        <div class="row pl-2 pt-3">
                                            <p class=" pfont mb-0 black-text fs-15" style="word-break: break-all; line-height: 20px;">{{ $order->customer_name }}<br>{{ $order->display_time }}</p>
                                        </div>
                                    </div>
                                    <div class="offset-1 col-2 pr-0 text-right">
                                        <div class="row pl-2">
                                            <img src="{{asset('img/head1.png')}}" width="30" height="30">
                                            <p class="font-weight-bold middle-ver fs-20">{{ $order->guest }}</p>
                                        </div>
                                        <div class="row mt-4 pl-3">
                                            <img src="{{asset('img/chat1.png')}}" width="40" height="40" style="margin: -15px 0 0 -10px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @else
                    @foreach($order_side_obj as $kk => $order)
                        @foreach($order->table_order_names as $key => $ordertables)
                            <div class="border w-100 pt-2 pr-1 table_seated_list" id="side_book_{{$order->id}}" style="display: block;"
                                 onclick="window.location='{{ route("reception.editOrder", [ 'order_id' => $order->id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'">
                                <div class="row w-100 p-0 m-0">
                                    <div class="col-lg-3 pr-0 col-xl-3">
                                        <div class="row" style="margin-left: -10px;">
                                            <p class="red-text font-weight-bold ml-0 fs-20" style="margin-left: 0px;">&nbsp;</p>
                                        </div>
                                        <div class="row table_name fs-20" style="text-align:center;">
                                            <table>
                                                <tr>
                                                    @if(count($order->table_display_names) > 1)
                                                        <img src="{{asset('img/plus_red.png')}}" class="corner">
                                                    @endif
                                                    <td style="width: 100px;height: 80px;background: #000;color:white;text-align: center;-ms-word-break: break-all;word-break: break-all;">
                                                        <b class="fs-25">
                                                            @if(strlen($ordertables) > 12)
                                                                {{ substr($ordertables, 0, 12)."..." }}
                                                            @else
                                                                {{ $ordertables }}
                                                            @endif
                                                        </b>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 pr-0 col-xl-6">
                                        <div class="row p-0 m-0">
                                            &nbsp;
                                        </div>
                                        <div class="row pl-2 pt-3">
                                            <p class=" pfont mb-0 black-text fs-20" style="word-break: break-all; line-height: 20px;">{{ $order->customer_name }}<br>{{ $order->display_time }}</p>
                                        </div>
                                    </div>
                                    <div class="offset-1 col-2 pr-0 text-right">
                                        <div class="row pl-2">
                                            <img src="{{asset('img/head1.png')}}" width="30" height="30">
                                            <p class="font-weight-bold middle-ver fs-25">{{ $order->guest }}</p>
                                        </div>
                                        <div class="row mt-4 pl-3">
                                            <img src="{{asset('img/chat1.png')}}" width="40" height="40" style="margin: -15px 0 0 -10px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endif
                </div>
            </div>
            <div class="row">
                @if($status != 'waiting')
                    <a href="{{ route("reception.addCustomer", [ "table_id" => 0, "order_id" => 0, "status" => $status ]) }}"
                    class="new_customer_btn white-text text-center pt-3 pb-5">
                        <b>{{ __('reception.NEW_CUSTOMER') }}</b>
                        <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;margin-top:-5px;">
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>
<script>

        // $("#display-all").click(function(){
        //     $("#display-method").hide("slow");
        //     var room_content = $(".room-content");
        //     room_content.width('700px');
        //     document.getElementById('room-content').style.marginLeft = '15px';
        //     document.getElementById('room-content').style.marginTop = '12px';
        //     $("#exit-fullscreen").show('slow');
        // });

        var screen_scale = "{{null !== session('scale_value') ? session('scale_value') : 100}}";
        $(".room-div").animate({ 'zoom': screen_scale*0.01 }, 400);
        var table_info = document.getElementsByClassName('black-text wb');
        for(var i=0;i<table_info.length;i++) {
            table_info[i].style.fontSize = screen_scale*0.2 + 'px';
        }

        $("#scale-value").text(screen_scale + '%');

        $("#display-all").click(function(){

            var room_obj = $(".room-div");
            var width = <?php echo json_encode($room_size->width) ?>;
            var height = <?php echo json_encode($room_size->height) ?>;
            room_obj.width(width);
            room_obj.height(height);

            if(width >= height) {
                var scale_value = (865/width).toString();
                $(".room-div").animate({ 'zoom': scale_value }, 400);
            } else {
                var scale_value = (888/height).toString();
                $(".room-div").animate({ 'zoom': scale_value }, 400);
            }

            for(var i=0;i<table_info.length;i++) {
                table_info[i].style.fontSize = 10*0.2 + 'px';
            }

        });

        $("#exit-fullscreen").click(function() {
            $("#display-method").show("slow");
            var room_content = $(".room-content");
            room_content.width('655px');
            room_content.css('margin-top', '9px');
            room_content.css('margin-left', '20px');
            $("#exit-fullscreen").hide('slow');
        });

        function tableZoomIn(){
            $(".minus_btn").attr("src", "{{ asset('img/minus.png') }}");
            var scale_value_obj = $("#scale-value");
            var scale_value_all = scale_value_obj.text();
            var scale_value = scale_value_all.slice(0, -1);

            if(scale_value != 100){
                $(".plus_btn").attr("src", "{{ asset('img/plus.png') }}");
                scale_value = parseInt(scale_value) + 10;

                if(scale_value == 100){
                    $(".plus_btn").attr("src", "{{ asset('img/plus_full.png') }}");
                }
                $(".room-div").animate({ 'zoom': scale_value*0.01 }, 400);
                for(var i=0;i<table_info.length;i++) {
                    table_info[i].style.fontSize = scale_value*0.2 + 'px';
                }
            }else{
                $(".plus_btn").attr("src", "{{ asset('img/plus_full.png') }}");
            }
            $.ajax({
                type: "get",
                url: "{{ route('reception.zoom_back') }}",
                data: {scale_value: scale_value,status:"{{ $status }}",_token:"{{ csrf_token() }}"},
                success: function(data) {
                }
            });
            scale_value_obj.text(scale_value+"%");
            screen_scale = scale_value;
        }

        function tableZoomOut(){
            $(".plus_btn").attr("src", "{{ asset('img/plus.png') }}");
            var scale_value_obj = $("#scale-value");
            var scale_value_all = scale_value_obj.text();
            var scale_value = scale_value_all.slice(0, -1);

            if(scale_value > 10){
                scale_value = scale_value - 10;

                if(scale_value == 10){
                    $(".minus_btn").attr("src", "{{ asset('img/minus_full.png') }}")
                }
                $(".room-div").animate({ 'zoom': scale_value*0.01 }, 400);
                for(var i=0;i<table_info.length;i++) {
                    table_info[i].style.fontSize = scale_value*0.2 + 'px';
                }
            }else{
                $(".minus_btn").attr("src", "{{ asset('img/minus_full.png') }}")
            }
            $.ajax({
                type: "get",
                url: "{{ route('reception.zoom_back') }}",
                data: {scale_value: scale_value,status:"{{ $status }}",_token:"{{ csrf_token() }}"},
                success: function(data) {
                }
            });
            scale_value_obj.text(scale_value+"%");
            screen_scale = scale_value;
        }

        //timer part
        @if($status != 'booking')
            var myVar = setInterval(myTimer, 1000);
        @endif
        function myTimer() {

            var order_side = <?php echo json_encode($order_side_obj) ?>;
//            console.log(order_side);
            var current_time =  new Date();
            var duration = '';
            var elapsed_time = '';
            var order_time = '';
            for(var i=0;i<order_side.length;i++){

                duration = order_side[i].duration;

                order_time = order_side[i].time;
                var dateParts = order_time.substr(0,10).split('-');
                var timePart = order_time.substr(11);
                order_time = dateParts[1] + '/' + dateParts[2] + '/' + dateParts[0] + ' ' + timePart;
                order_time = new Date(order_time);
                var additional = order_side[i].additional;

                var element = document.getElementById("time_" + i);
                if(duration == 0) {
                    element.classList.remove('fs-20');
                    element.classList.add('fs-15');
                } else {
                    element.classList.remove('fs-15');
                    element.classList.add('fs-20');
                }

                if(duration == 0) {
                    document.getElementById("time_" + i).innerHTML = 'テイクアウト';
                } else if(duration == 1) {
                    order_time.setMinutes( order_time.getMinutes() + 30 );
                    elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                    elapsed_time /= 60;
                    elapsed_time = Math.round(elapsed_time) + additional;
                    if(elapsed_time > 0) {
                        document.getElementById("time_" + i).innerHTML = elapsed_time + '分';
                    }
                    else {
                        document.getElementById("time_" + i).innerHTML = '0分';
                    }
                } else if(duration == 2) {
                    order_time.setMinutes( order_time.getMinutes() + 60 );
                    elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                    elapsed_time /= 60;
                    elapsed_time = Math.round(elapsed_time) + additional;
                    if(elapsed_time > 0) {
                        document.getElementById("time_" + i).innerHTML = elapsed_time + '分';
                    }
                    else {
                        document.getElementById("time_" + i).innerHTML = '0分';
                    }
                } else if(duration == 3) {
                    order_time.setMinutes( order_time.getMinutes() + 90 );
                    elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                    elapsed_time /= 60;
                    elapsed_time = Math.round(elapsed_time) + additional;
                    if(elapsed_time > 0) {
                        document.getElementById("time_" + i).innerHTML = elapsed_time + '分';
                    }
                    else {
                        document.getElementById("time_" + i).innerHTML = '0分';
                    }
                } else if(duration == 4) {
                    order_time.setMinutes( order_time.getMinutes() + 120 );
                    elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                    elapsed_time /= 60;
                    elapsed_time = Math.round(elapsed_time) + additional;
                    if(elapsed_time > 0) {
                        document.getElementById("time_" + i).innerHTML = elapsed_time + '分';
                    }
                    else {
                        document.getElementById("time_" + i).innerHTML = '0分';
                    }
                } else if(duration == 5) {
                    document.getElementById("time_" + i).innerHTML = '無制限';
                }

            }
        }

        var myVar1 = setInterval(myTimer1, 1000);
        function myTimer1() {

            var table_obj = <?php echo json_encode($table_obj) ?>;
            // console.log(table_obj);
            var current_time =  new Date();
            var duration = '';
            var elapsed_time = '';
            var order_time = '';
            var status = '';
            for(var i=0;i<table_obj.length;i++){

                if(table_obj[i]['order'].length > 0) {

                    duration = table_obj[i]['order'][0]['duration'];
                    var additional = table_obj[i]['order'][0]['additional'];

                    order_time = table_obj[i]['order'][0]['time'];
                    var dateParts = order_time.substr(0,10).split('-');
                    var timePart = order_time.substr(11);
                    order_time = dateParts[1] + '/' + dateParts[2] + '/' + dateParts[0] + ' ' + timePart;
                    order_time = new Date(order_time);

                    status = table_obj[i]['order'][0]['status'];

                    if(duration == 0) {
                        if(status != 'booking') {
                            document.getElementById("time1_" + i).innerHTML = 'テイクアウト';
                        }
                    } else if(duration == 1) {
                        order_time.setMinutes( order_time.getMinutes() + 30 );
                        elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                        elapsed_time /= 60;
                        elapsed_time = Math.round(elapsed_time) + additional;
                        if((elapsed_time >= 0) && (status != 'booking')) {
                            document.getElementById("time1_" + i).innerHTML = elapsed_time;
                        }
                        if((elapsed_time < 0) && (status != 'booking')) {
                            document.getElementById("time1_" + i).innerHTML = '0';
                        }
                        if (elapsed_time < 15) {
                            document.getElementById("time1_" + i).style.color = 'red';
                        } else {
                            document.getElementById("time1_" + i).style.color = 'black';
                        }
                    } else if(duration == 2) {
                        order_time.setMinutes( order_time.getMinutes() + 60 );
                        elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                        elapsed_time /= 60;
                        elapsed_time = Math.round(elapsed_time) + additional;
                        if((elapsed_time >= 0) && (status != 'booking')) {
                            document.getElementById("time1_" + i).innerHTML = elapsed_time;
                        }
                        if((elapsed_time < 0) && (status != 'booking')) {
                            document.getElementById("time1_" + i).innerHTML = '0';
                        }
                        if (elapsed_time < 15) {
                            document.getElementById("time1_" + i).style.color = 'red';
                        } else {
                            document.getElementById("time1_" + i).style.color = 'black';
                        }
                    } else if(duration == 3) {
                        order_time.setMinutes( order_time.getMinutes() + 90 );
                        elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                        elapsed_time /= 60;
                        elapsed_time = Math.round(elapsed_time) + additional;
                        if((elapsed_time >= 0) && (status != 'booking')) {
                            document.getElementById("time1_" + i).innerHTML = elapsed_time;
                        }
                        if((elapsed_time < 0) && (status != 'booking')) {
                            document.getElementById("time1_" + i).innerHTML = '0';
                        }
                        if (elapsed_time < 15) {
                            document.getElementById("time1_" + i).style.color = 'red';
                        } else {
                            document.getElementById("time1_" + i).style.color = 'black';
                        }
                    } else if(duration == 4) {
                        order_time.setMinutes( order_time.getMinutes() + 120 );
                        elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                        elapsed_time /= 60;
                        elapsed_time = Math.round(elapsed_time) + additional;
                        if((elapsed_time >= 0) && (status != 'booking')) {
                            if(screen_scale > 20) {
                                document.getElementById("time1_" + i).innerHTML = elapsed_time;
                            }
                            else {
                                document.getElementById("time1_" + i).innerHTML = '';
                            }
                        }
                        if((elapsed_time < 0) && (status != 'booking')) {
                            if(screen_scale > 20) {
                                document.getElementById("time1_" + i).innerHTML = '0';
                            }
                            else {
                                document.getElementById("time1_" + i).innerHTML = '';
                            }
                        }
                        if (elapsed_time < 15) {
                            document.getElementById("time1_" + i).style.color = 'red';
                        } else {
                            document.getElementById("time1_" + i).style.color = 'black';
                        }
                    } else if(duration == 5) {
                        if(status != 'booking') {
                            //if(screen_scale > 30) {
                                document.getElementById("time1_" + i).innerHTML = '無制限';
                            /*}
                            else if(screen_scale == 30) {
                                document.getElementById("time1_" + i).innerHTML = 'Unlimit';
                            }
                            else {
                                document.getElementById("time1_" + i).innerHTML = '';
                            } */
                        }
                    }

                }

            }
        }

        var table_obj_book = <?php echo json_encode($table_obj) ?>;

        console.log(table_obj_book);
        var myVar_book = setInterval(myTimer_book, 1000);

        function myTimer_book() {
            var current_date =  new Date();
            var offset = 9;
            var utc = current_date.getTime() + (current_date.getTimezoneOffset() * 60000);
            var nd = new Date(utc + (3600000*offset));
            nd.setHours(0,0,0,0);
            var current_time =nd.getTime();
            var duration = '';
            var elapsed_time = '';
            var order_time = '';
            var status = '';

            for(var i=0;i<table_obj_book.length;i++){
                if(undefined !== table_obj_book[i]['book'] && table_obj_book[i]['book'].length > 0) {
                    var book_time = table_obj_book[i]['book'][0]['time'];
                    order_time = new Date(book_time.substr(0,4),(parseInt(book_time.substr(5,2))-1),parseInt(book_time.substr(8,2)),parseInt(book_time.substr(11,2)),parseInt(book_time.substr(14,2)));
                    elapsed_time = order_time.getTime() - current_time;

                    if( elapsed_time > 0 && elapsed_time < 24 * 3600 * 1000 ) {
                        var sub_time = get_book_data(order_time);
                        if(screen_scale > 20) {
                            document.getElementById("time_book_" + i).innerHTML = '予約' + '<br>' + sub_time + '<br>';
                        }
                        else {
                            document.getElementById("time_book_" + i).innerHTML = '';
                        }
                    }
                    else if (elapsed_time < 0 && elapsed_time > -3000) {
                        @if($status != 'booking')
                            document.getElementById('side_book_'+ table_obj_book[i]['book'][0]['id']).style.display = "none";
                        @endif
                        if(screen_scale > 20) {
                            document.getElementById("time_book_" + i).innerHTML = '&nbsp;' + '<br>' + '&nbsp;' + '<br>';
                        }
                        else {
                            document.getElementById("time_book_" + i).innerHTML = '';
                        }
                        $.ajax({
                            type:"POST",
                            url:"{{ route('reception.book_end') }}",
                            data:{
                                book_id: table_obj_book[i]['book'][0]['id'],
                                _token:"{{ csrf_token() }}"
                            },
                            success: function(data){
                                document.getElementById("book_cnt").innerHTML = data.status;
                                table_obj_book = data.table_obj;
                            }
                        });
                    }
                }
            }

        }

        function get_book_data(order_time)
        {
            var hours = order_time.getHours();
            var min = order_time.getMinutes();
            var sub_time = '';

            if (min < 10) {
                min = '0' + min;
            }

            if (hours == 0) {
                sub_time = '12:' + min + ' AM';
            }
            else if (hours > 0 && hours < 10) {
                sub_time = '0'+ hours + ':' + min + ' AM';
            }
            else if (hours > 9 && hours < 12) {
                sub_time = hours + ':' + min + ' AM';
            }
            else if (hours == 12) {
                sub_time = hours + ':' + min + ' PM';
            }
            else if (hours > 12 && hours < 22) {
                sub_time = '0'+ (hours - 12) + ':' + min + ' PM';
            }
            else if (hours > 21) {
                sub_time = hours + ':' + min + ' PM';
            }

            return sub_time;
        }

    </script>
@endsection
