@extends('layout.admin_layout')
@section('title', session('role'))
@section('content')
    <style>
        thead tr{
            border-top:2px solid #1ec2c9;
        }
        .datetimepicker-inline{
            width:100% !important;
        }
        .datetimepicker td, .datetimepicker th{
            border-radius:50px;
            font-size: 25px;
        }
        .datetimepicker table tr td.active,
        .datetimepicker table tr td.active:hover,
        .datetimepicker table tr td.active.disabled,
        .datetimepicker table tr td.active.disabled:hover,
        .datetimepicker table tr td.active:hover,
        .datetimepicker table tr td.active:hover:hover,
        .datetimepicker table tr td.active.disabled:hover,
        .datetimepicker table tr td.active.disabled:hover:hover,
        .datetimepicker table tr td.active:active,
        .datetimepicker table tr td.active:hover:active,
        .datetimepicker table tr td.active.disabled:active,
        .datetimepicker table tr td.active.disabled:hover:active,
        .datetimepicker table tr td.active.active,
        .datetimepicker table tr td.active:hover.active,
        .datetimepicker table tr td.active.disabled.active,
        .datetimepicker table tr td.active.disabled:hover.active,
        .datetimepicker table tr td.active.disabled,
        .datetimepicker table tr td.active:hover.disabled,
        .datetimepicker table tr td.active.disabled.disabled,
        .datetimepicker table tr td.active.disabled:hover.disabled,
        .datetimepicker table tr td.active[disabled],
        .datetimepicker table tr td.active:hover[disabled],
        .datetimepicker table tr td.active.disabled[disabled],
        .datetimepicker table tr td.active.disabled:hover[disabled]
        {
            background-image:none !important;
            background-color:transparent;
            color:red;
            font-size: 25px;
            font-weight:bold;
            border-radius:50px !important;
            /*border:2px solid #1ec2c9*/
        }
        .datetimepicker table tr td.today.disabled:hover[disabled]
        {
            background-image:none !important;
            background-color:transparent;
            color:red;
            font-weight:bold;
            font-size: 30px;
            border-radius:50px !important;
            border:2px solid #1ec2c9
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        table,.datetimepicker > div,.datetimepicker-days{
            width:100% !important;
        }
        {{--.prev{--}}
            {{--background: url("{{asset('images/blue-arrow-prev.png')}}") no-repeat;--}}
        {{--}--}}
        {{--.next{--}}
            {{--background: url("{{asset('images/blue-arrow-next.png')}}") no-repeat;--}}
        {{--}--}}

        #delay-minutes-picker .clone-scroller .option{
            /*visibility: visible;*/
        }

        .now_btn
        {
            width: 90px;
            /*height: 33px;*/
            background: #1EC2C9;
            color: white;
            font-weight: 700;
            font-size: 25px;
            text-align: center;
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 10px;
            padding-bottom: 10px;
        }
    </style>

    <?php $customers = array(0=>'テイクアウト', '30分', '60分', '90分', '120分', '無制限'); ?>
    <form method="POST" id="save-customer" action="{{ route('reception.store', ['status' => $status]) }}">
        @csrf
        <div class="p-4 mt-5">
            <div class="container-fluid pb-3 position-relative">
                <div class="bg-grey pt-2 pl-2 mt-5">
                    <ul class="nav uldesign">
                        <li class="top-menu-btn font-weight-bold top-menu-active">
                            <div class="text-center time_menu">
                                <a class="black-text" id="now-tab" onclick="changeTab('home');">
                                    <div class="text-center time_menu fs-20">{{ __('reception.NOW') }}<br>{{ $customers[$default_duration_id] }}</div>
                                </a>
                            </div>
                        </li>
                        <li class="top-menu-btn font-weight-bold">
                            <div class="text-center-btn group_menu">
                                <a class="black-text fs-20" id="group-tab" onclick="changeTab('group');">
                                    <img src="{{asset('img/head1.png')}}" class="img-40" />
                                    @if($order_id > 0)
                                        {{$order_get->guest}}
                                    @else
                                        {{ __('reception.GROUP') }}
                                    @endif
                                </a>
                            </div>
                        </li>
                        <li class="top-menu-btn font-weight-bold">
                            <div class="text-center-btn table_menu">
                                <a id="table-tab" class="black-text fs-20" onclick="changeTab('table');">
                                    <img src="{{asset('img/table_sample.png')}}" class="mr-2 img-2" />@if($order_id > 0) {{ $table_display_name }}@else {{ __('reception.TABLE') }} @endif
                                </a>
                            </div>
                        </li>
                        <li class="top-menu-btn font-weight-bold">
                            <div class="text-center-btn name_menu">
                                <a id="name-tab" class="black-text fs-20" onclick="changeTab('name');">
                                    <div class="text-center name_menu" id="name-tab-div">{{$customer_name}}</div>
                                </a>
                            </div>
                        </li>
                        <li class="top-menu-btn font-weight-bold">
                            <div class="text-center-btn table_menu">
                                <a id="notes-tab" class="black-text fs-20" onclick="changeTab('notes');">
                                    <img src="{{asset('img/note.png')}}" class="mr-2 img-40" />{{ __('reception.NOTE') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="container">
                    <div class="tab-content">
                        <div id="home" class="container tab-detail"><br>
                            <div class="row" style="margin-top: 50px;">
                                <div class="col-4">
                                    <h5 class="date_content_title fs-25">{{ __('reception.DATE') }}</h5>
                                    <div id="calendar-picker" class="w-100 fs-20" @if(!empty($order_get)) data-date="{{substr($order_get->time,0,10)}}" @endif data-date-format="yyyy-mm-dd" >
                                    </div>
                                </div>
                                <div class="col-4" style="text-align:center">
                                    <h5 class="time_content_title fs-25">{{ __('reception.TIME') }}</h5>
                                    <input id="timepicker1" type="text" name="timepicker1" style="text-align: center;margin-bottom: 40px;font-size: 25px;"
                                    @if(empty($order_get)) value="時間の選択" @else value="{{date_format(date_create(substr($order_get->time,11,5)),"h:i A")}}" @endif />
                                    <span class="now_btn" onclick="current_time()">{{ __('reception.NOW') }}</span>
                                </div>
                                <div class="col-4">
                                    <h5 class="time_content_title fs-25">{{ __('reception.DURATION') }}</h5>
                                    <select id="duration-select" class="fs-25" style="width: 100%;height: 35px;padding-left: 15px;">
                                        <option value="0" @if($default_duration_id == 0) selected @endif>テイクアウト</option>
                                        <option value="1" @if($default_duration_id == 1) selected @endif>30分</option>
                                        <option value="2" @if($default_duration_id == 2) selected @endif>60分</option>
                                        <option value="3" @if($default_duration_id == 3) selected @endif>90分</option>
                                        <option value="4" @if($default_duration_id == 4) selected @endif>120分</option>
                                        <option value="5" @if($default_duration_id == 5) selected @endif>無制限</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="menu1" class="tab-detail display-none"><br>
                            <div class="offset-2 col-lg-8 col-xl-8 pl-0 " style="margin-top: 50px;">
                                <input value="@if($order_id > 0){{$order_get->guest}}@endif"
                                       id="guest-number" class="font-20 font-weight-bold form-control d-inline mr-4 border-guest-input text-center"
                                       style="height: 50px;width:280px;" name="guest_number"/>
                                <span class="h4 text-info" style="padding-top:10px;color: #1ec2c9 !important;">人</span><br>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text mr-lg-0 mr-xl-3" onclick="onNumber(7)">7</button>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text mr-lg-0 mr-xl-3" onclick="onNumber(8)">8</button>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text" onclick="onNumber(9)">9</button>
                                <br>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text mt-lg-2 mt-xl-4 mr-lg-0 mr-xl-3" onclick="onNumber(4)">4</button>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text mr-lg-0 mr-xl-3" onclick="onNumber(5)">5</button>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text" onclick="onNumber(6)">6</button>
                                <br>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text mt-lg-2 mt-xl-4 mr-lg-0 mr-xl-3" onclick="onNumber(1)">1</button>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text mr-lg-0 mr-xl-3" onclick="onNumber(2)">2</button>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text" onclick="onNumber(3)">3</button>
                                <br>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text mt-lg-2 mr-lg-0 mr-xl-3 mt-xl-4" onclick="onNumber('c')">C</button>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text mr-lg-0 mr-xl-3" onclick="onNumber(0)">0</button>
                                <button type="button" class="number-btn blackgrey border-0 pr-4 pl-4 fs-5 white-text" onclick="onNumber('back')"><-</button>
                            </div>
                        </div>
                        <div id="menu2" class="row container display-none tab-detail"><br>
                            <div class="row">
                                <div class="text-center exit_full_screen display-none" id="exit-fullscreen">
                                    <span class="white-text font-weight-bold exit_fullscreen_letter">{{ __('admin.Table.EXIT_FULL_SCREEN') }}</span>
                                    <img src="{{ asset('img/exit-fullscreen.png') }}"/>
                                </div>
                            </div>
                            <div class="row table-tab-content">
                                <div class="pr-0 pl-0 text-center" id="display-method">
                                    <div class="text-center display_all_content" id="display-all">
                                        <img src="{{ asset('img/arrow.png') }}" class="display_all_btn"/>
                                        <p class="white-text font-weight-bold display_all">{{ __('admin.Table.SHOW_FULL_SCREEN') }}</p>
                                    </div>
                                    <div class="text-center display_scale" style="margin-left: -10px;">
                                        <img src="{{ asset('img/plus_full.png') }}" class="plus_btn" onclick="tableZoomIn()"/>
                                        <p class="font-weight-bold pt5 scale_value" id="scale-value">100%</p>
                                        <img src="{{ asset('img/minus.png') }}" class="minus_btn" onclick="tableZoomOut();"/>
                                    </div>
                                </div>
                                <div class="room-content-table">
                                    <div class="room-div">
                                        <input type="hidden" name="" id="table_obj" value="{{ $table_obj }}">
                                        @foreach($table_obj as $key => $table)
                                            @if(count($table->order) > 0)
                                                <div class="table-common" id="selected-{{$table->id}}" style="margin: {{$table['y']*20}}px 10px 10px {{$table['x']*20}}px;">
                                                    @if($table->type == 0)
                                                        <div style="margin: 0 0 0 0;">
                                                            <div style="display: inline-block;">
                                                                <div>
                                                                    <div class="@if($table->order[0]->id == $order_id || $table->id == $table_id) bg-selected @endif table-area white table-c-style text-center" @if($status=='booking' || $table->order[0]->id == $order_id) onclick="selectObject('{{$table->id}}')" @endif id="{{$table->id}}">
                                                                        @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                                            <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: -12px;">
                                                                        @endif
                                                                        <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                                            <br>
                                                                            <p class="black-text ml-0" id="time1_{{$key}}"></p>
                                                                        </h6>
                                                                        @if($table->order[0]->status == 'booking')
                                                                            <a class="font-weight-bold red-text">
                                                                                予約済み
                                                                                <br>
                                                                                {{$table->display_time}}
                                                                            </a>
                                                                        @else
                                                                            @if($table->order[0]->pay_flag == '1')
                                                                                <img class="alarm" src="{{ asset('img/calling.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 18px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->orderTable[0]->calling_time != Null)
                                                                                <img class="alarm" src="{{ asset('img/alarm.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 15px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->review != Null)
                                                                                <img class="alarm" src="{{ asset('img/msg.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->note != Null)
                                                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($table->type == 1)
                                                        <div style="margin: 0 0 0 0;">
                                                            <div style="display: inline-block;">
                                                                <div style="height: 110px;">
                                                                    <div class="@if($table->order[0]->id == $order_id || $table->id == $table_id) bg-selected @endif table-area white table-c-style text-center" @if($status=='booking' || $table->order[0]->id == $order_id) onclick="selectObject('{{$table->id}}')" @endif id="{{$table->id}}">
                                                                        @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                                            <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: -12px;">
                                                                        @endif
                                                                        <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                                            <br>
                                                                            <p class="black-text ml-0" id="time1_{{$key}}"></p>
                                                                        </h6>
                                                                        @if($table->order[0]->status == 'booking')
                                                                            <a class="font-weight-bold red-text">
                                                                                予約済み
                                                                                <br>
                                                                                {{$table->display_time}}
                                                                            </a>
                                                                        @else
                                                                            @if($table->order[0]->pay_flag == '1')
                                                                                <img class="alarm" src="{{ asset('img/calling.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 18px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->orderTable[0]->calling_time != Null)
                                                                                <img class="alarm" src="{{ asset('img/alarm.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 15px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->review != Null)
                                                                                <img class="alarm" src="{{ asset('img/msg.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->note != Null)
                                                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                        @endif
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
                                                                    <div class="@if($table->order[0]->id == $order_id || $table->id == $table_id) bg-selected @endif table-area white table-c-style text-center" @if($status=='booking' || $table->order[0]->id == $order_id) onclick="selectObject('{{$table->id}}')" @endif id="{{$table->id}}">
                                                                        @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                                            <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: 8px;">
                                                                        @endif
                                                                        <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                                            <br>
                                                                            <p class="black-text ml-0" id="time1_{{$key}}"></p>
                                                                        </h6>
                                                                        @if($table->order[0]->status == 'booking')
                                                                            <a class="font-weight-bold red-text">
                                                                                予約済み
                                                                                <br>
                                                                                {{$table->display_time}}
                                                                            </a>
                                                                        @else
                                                                            @if($table->order[0]->pay_flag == '1')
                                                                                <img class="alarm" src="{{ asset('img/calling.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 18px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->orderTable[0]->calling_time != Null)
                                                                                <img class="alarm" src="{{ asset('img/alarm.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 15px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->review != Null)
                                                                                <img class="alarm" src="{{ asset('img/msg.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->note != Null)
                                                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                        @endif
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
                                                                    <div class="@if($table->order[0]->id == $order_id || $table->id == $table_id) bg-selected @endif table-area white table-c-style text-center" style="margin-left: 38px;" @if($status=='booking' || $table->order[0]->id == $order_id) onclick="selectObject('{{$table->id}}')" @endif id="{{$table->id}}">
                                                                        @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                                            <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: 8px;">
                                                                        @endif
                                                                        <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                                            <br>
                                                                            <p class="black-text ml-0" id="time1_{{$key}}"></p>
                                                                        </h6>
                                                                        @if($table->order[0]->status == 'booking')
                                                                            <a class="font-weight-bold red-text">
                                                                                予約済み
                                                                                <br>
                                                                                {{$table->display_time}}
                                                                            </a>
                                                                        @else
                                                                            @if($table->order[0]->pay_flag == '1')
                                                                                <img class="alarm" src="{{ asset('img/calling.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 18px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->orderTable[0]->calling_time != Null)
                                                                                <img class="alarm" src="{{ asset('img/alarm.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 15px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->review != Null)
                                                                                <img class="alarm" src="{{ asset('img/msg.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->note != Null)
                                                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                        @endif
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
                                                                    <div class="@if($table->order[0]->id == $order_id || $table->id == $table_id) bg-selected @endif table-area white table-c-style text-center" style="margin-left: 38px;" @if($status=='booking' || $table->order[0]->id == $order_id) onclick="selectObject('{{$table->id}}')" @endif id="{{$table->id}}">
                                                                        @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                                            <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="top: 8px;">
                                                                        @endif
                                                                        <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                                            <br>
                                                                            <p class="black-text ml-0" id="time1_{{$key}}"></p>
                                                                        </h6>
                                                                        @if($table->order[0]->status == 'booking')
                                                                            <a class="font-weight-bold red-text">
                                                                                予約済み
                                                                                <br>
                                                                                {{$table->display_time}}
                                                                            </a>
                                                                        @else
                                                                            @if($table->order[0]->pay_flag == '1')
                                                                                <img class="alarm" src="{{ asset('img/calling.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 18px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->orderTable[0]->calling_time != Null)
                                                                                <img class="alarm" src="{{ asset('img/alarm.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 15px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->review != Null)
                                                                                <img class="alarm" src="{{ asset('img/msg.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->note != Null)
                                                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                        @endif
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
                                                                    <div class="@if($table->order[0]->id == $order_id || $table->id == $table_id) bg-selected @endif table-area white table-c-style text-center" style="margin-left: 38px;" @if($status=='booking' || $table->order[0]->id == $order_id) onclick="selectObject('{{$table->id}}')" @endif id="{{$table->id}}">
                                                                        @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                                            <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                                                        @endif
                                                                        <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                                            <br>
                                                                            <p class="black-text ml-0" id="time1_{{$key}}"></p>
                                                                        </h6>
                                                                        @if($table->order[0]->status == 'booking')
                                                                            <a class="font-weight-bold red-text">
                                                                                予約済み
                                                                                <br>
                                                                                {{$table->display_time}}
                                                                            </a>
                                                                        @else
                                                                            @if($table->order[0]->pay_flag == '1')
                                                                                <img class="alarm" src="{{ asset('img/calling.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 18px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->orderTable[0]->calling_time != Null)
                                                                                <img class="alarm" src="{{ asset('img/alarm.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 15px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->review != Null)
                                                                                <img class="alarm" src="{{ asset('img/msg.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->note != Null)
                                                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                        @endif
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
                                                                    <div class="@if($table->order[0]->id == $order_id || $table->id == $table_id) bg-selected @endif table-area white table-c-style text-center" style="margin-left: 38px;" @if($status=='booking' || $table->order[0]->id == $order_id) onclick="selectObject('{{$table->id}}')" @endif id="{{$table->id}}">
                                                                        @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                                            <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 126px;top: 8px;">
                                                                        @endif
                                                                        <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                                            <br>
                                                                            <p class="black-text ml-0" id="time1_{{$key}}"></p>
                                                                        </h6>
                                                                        @if($table->order[0]->status == 'booking')
                                                                            <a class="font-weight-bold red-text">
                                                                                予約済み
                                                                                <br>
                                                                                {{$table->display_time}}
                                                                            </a>
                                                                        @else
                                                                            @if($table->order[0]->pay_flag == '1')
                                                                                <img class="alarm" src="{{ asset('img/calling.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 18px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->orderTable[0]->calling_time != Null)
                                                                                <img class="alarm" src="{{ asset('img/alarm.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 15px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->review != Null)
                                                                                <img class="alarm" src="{{ asset('img/msg.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->note != Null)
                                                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                        @endif
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
                                                                    <div class="@if($table->order[0]->id == $order_id || $table->id == $table_id) bg-selected @endif table-area white table-c-style text-center" style="margin-left: 38px;" @if($status=='booking' || $table->order[0]->id == $order_id) onclick="selectObject('{{$table->id}}')" @endif id="{{$table->id}}">
                                                                        @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                                            <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 125px;top: 8px;">
                                                                        @endif
                                                                        <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                                            <br>
                                                                            <p class="black-text ml-0" id="time1_{{$key}}"></p>
                                                                        </h6>
                                                                        @if($table->order[0]->status == 'booking')
                                                                            <a class="font-weight-bold red-text">
                                                                                予約済み
                                                                                <br>
                                                                                {{$table->display_time}}
                                                                            </a>
                                                                        @else
                                                                            @if($table->order[0]->pay_flag == '1')
                                                                                <img class="alarm" src="{{ asset('img/calling.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 18px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->orderTable[0]->calling_time != Null)
                                                                                <img class="alarm" src="{{ asset('img/alarm.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 15px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->review != Null)
                                                                                <img class="alarm" src="{{ asset('img/msg.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->note != Null)
                                                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <span class="ch-1 ch-ena ch-bottom ch-bottom-left"></span>
                                                                <span class="ch-1 ch-ena ch-bottom ch-bottom-right"></span>
                                                            </div>
                                                            <span class="ch-2 ch-ena ch-right ch-right-center"></span>
                                                        </div>
                                                    @elseif($table->type == 8)
                                                        <div style="margin: -4px 0 0 5px;">
                                                            <span class="ch-2 ch-ena ch-left ch-left-top"></span>
                                                            <span class="ch-2 ch-ena ch-left ch-left-bottom"></span>
                                                            <div style="display: inline-block;">
                                                                <span class="ch-1 ch-ena ch-top ch-top-left"></span>
                                                                <span class="ch-1 ch-ena ch-top ch-top-right"></span>
                                                                <div style="height: 118px;">
                                                                    <div class="@if($table->order[0]->id == $order_id || $table->id == $table_id) bg-selected @endif table-area white table-c-style text-center" style="margin-left: 38px;" @if($status=='booking' || $table->order[0]->id == $order_id) onclick="selectObject('{{$table->id}}')" @endif id="{{$table->id}}">
                                                                        @if(in_array($table->id, $order_tables) && (count($table->order[0]->ordertables) > 1))
                                                                            <img class="table_a_red_plus" src="{{asset('img/plus_red.png')}}" style="left: 125px;top: 8px;">
                                                                        @endif
                                                                        <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}
                                                                            <br>
                                                                            <p class="black-text ml-0" id="time1_{{$key}}"></p>
                                                                        </h6>
                                                                        @if($table->order[0]->status == 'booking')
                                                                            <a class="font-weight-bold red-text">
                                                                                予約済み
                                                                                <br>
                                                                                {{$table->display_time}}
                                                                            </a>
                                                                        @else
                                                                            @if($table->order[0]->pay_flag == '1')
                                                                                <img class="alarm" src="{{ asset('img/calling.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 18px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->orderTable[0]->calling_time != Null)
                                                                                <img class="alarm" src="{{ asset('img/alarm.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 15px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->review != Null)
                                                                                <img class="alarm" src="{{ asset('img/msg.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                            @if($table->order[0]->note != Null)
                                                                                <img class="alarm" src="{{ asset('img/note.png') }}">
                                                                            @else
                                                                                <e class="alarm" style="width: 19px;height: 16px;"></e>
                                                                            @endif
                                                                        @endif
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
                                                <input type="hidden" id="table_id" value="{{ $table->id }}">
                                                <div class="table-common" id="selected-{{$table->id}}" style="margin: {{$table['y']*20}}px 10px 10px {{$table['x']*20}}px;">
                                                    @if($table->type == 0)
                                                        <div style="margin: 0 0 0 0;">
                                                            <div style="display: inline-block;">
                                                                <div>
                                                                    <div class="@if($table->id == $table_id) bg-selected @endif table-area white table-c-style-disable text-center" id="{{$table->id}}">
                                                                        <div style="width: 118px;height: 118px;" onclick="selectObject('{{$table->id}}')">
                                                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($table->type == 1)
                                                        <div style="margin: 0 0 0 0;">
                                                            <div style="display: inline-block;">
                                                                <div style="height: 110px;">
                                                                    <div class="@if($table->id == $table_id) bg-selected @endif table-area white table-c-style-disable text-center" id="{{$table->id}}">
                                                                        <div style="width: 118px;height: 118px;" onclick="selectObject('{{$table->id}}')">
                                                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
                                                            </div>
                                                        </div>
                                                    @elseif($table->type == 2)
                                                        <div style="margin: -4px 0 0 0;">
                                                            <div style="display: inline-block;">
                                                                <span class="ch-1 ch-dis ch-top ch-top-center"></span>
                                                                <div style="height: 110px;">
                                                                    <div class="@if($table->id == $table_id) bg-selected @endif table-area white table-c-style-disable text-center" id="{{$table->id}}">
                                                                        <div style="width: 118px;height: 118px;" onclick="selectObject('{{$table->id}}')">
                                                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
                                                            </div>
                                                        </div>
                                                    @elseif($table->type == 3)
                                                        <div style="margin: -4px 0 0 -38px;">
                                                            <div style="display: inline-block;">
                                                                <span class="ch-1 ch-dis ch-top ch-top-center" style="margin-left: 73px;"></span>
                                                                <div style="height: 118px;">
                                                                    <div class="@if($table->id == $table_id) bg-selected @endif table-area white table-c-style-disable text-center" style="margin-left: 38px;" id="{{$table->id}}">
                                                                        <div style="width: 118px;height: 118px;" onclick="selectObject('{{$table->id}}')">
                                                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                                            </div>
                                                        </div>
                                                    @elseif($table->type == 4)
                                                        <div style="margin: -4px 0 0 -38px;">
                                                            <div style="display: inline-block;">
                                                                <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                                                <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                                                <div style="height: 118px;">
                                                                    <div class="@if($table->id == $table_id) bg-selected @endif table-area white table-c-style-disable text-center" style="margin-left: 38px;" id="{{$table->id}}">
                                                                        <div style="width: 118px;height: 118px;" onclick="selectObject('{{$table->id}}')">
                                                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                                            </div>
                                                        </div>
                                                    @elseif($table->type == 5)
                                                        <div style="margin: -4px 0 0 6px;">
                                                            <span class="ch-2 ch-dis ch-left ch-left-center"></span>
                                                            <div style="display: inline-block;">
                                                                <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                                                <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                                                <div style="height: 118px;">
                                                                    <div class="@if($table->id == $table_id) bg-selected @endif table-area white table-c-style-disable text-center" style="margin-left: 38px;" id="{{$table->id}}">
                                                                        <div style="width: 118px;height: 118px;" onclick="selectObject('{{$table->id}}')">
                                                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                                            </div>
                                                        </div>
                                                    @elseif($table->type == 6)
                                                        <div style="margin: -4px 0 0 6px;">
                                                            <span class="ch-2 ch-dis ch-left ch-left-center"></span>
                                                            <div style="display: inline-block;">
                                                                <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                                                <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                                                <div style="height: 118px;">
                                                                    <div class="@if($table->id == $table_id) bg-selected @endif table-area white table-c-style-disable text-center" style="margin-left: 38px;" id="{{$table->id}}">
                                                                        <div style="width: 118px;height: 118px;" onclick="selectObject('{{$table->id}}')">
                                                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                                            </div>
                                                            <span class="ch-2 ch-dis ch-right ch-right-center"></span>
                                                        </div>
                                                    @elseif($table->type == 7)
                                                        <div style="margin: -4px 0 0 6px;">
                                                            <span class="ch-2 ch-dis ch-left ch-left-top"></span>
                                                            <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
                                                            <div style="display: inline-block;">
                                                                <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                                                <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                                                <div style="height: 118px;">
                                                                    <div class="@if($table->id == $table_id) bg-selected @endif table-area white table-c-style-disable text-center" style="margin-left: 38px;" id="{{$table->id}}">
                                                                        <div style="width: 118px;height: 118px;" onclick="selectObject('{{$table->id}}')">
                                                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                                                <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                                            </div>
                                                            <span class="ch-2 ch-dis ch-right ch-right-center"></span>
                                                        </div>
                                                    @elseif($table->type == 8)
                                                        <div style="margin: -4px 0 0 6px;">
                                                            <span class="ch-2 ch-dis ch-left ch-left-top"></span>
                                                            <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
                                                            <div style="display: inline-block;">
                                                                <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                                                <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                                                <div style="height: 118px;">
                                                                    <div class="@if($table->id == $table_id) bg-selected @endif table-area white table-c-style-disable text-center" style="margin-left: 38px;" id="{{$table->id}}">
                                                                        <div style="width: 118px;height: 118px;" onclick="selectObject('{{$table->id}}')">
                                                                            <h6 id="table_info" class="black-text wb" style="margin-bottom: -15px;">{{ $table["name"] }}</h6>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="menu3" class="container display-none tab-detail"><br>
                            <div class=" mt-5">
                                <h6 class="font-weight-bold fs-20">{{ __('reception.CUSTOMER_NAME') }}</h6>
                                <input style="border:1px solid grey;border-radius:5px;" class="white pl-2 w-100 pt-1 pb-1 fs-20" value="{{$customer_name}}" name="customer_name" id="customer-name"/>
                            </div>
                            <div class=" mt-2">
                                <h6 class="font-weight-bold fs-20">{{ __('reception.CONTACT_NUMBER') }}</h6>
                                <input style="border:1px solid grey;border-radius:5px;" class="white pl-2 w-100 pt-1 pb-1 fs-20" value="@if($order_id > 0) {{$order_get->contact_number}}@endif" name="contact_number" id="contact-number"/>
                            </div>
                            <div class=" mt-2">
                                <h6 class="font-weight-bold fs-20">{{ __('reception.EMAIL_ADDRESS') }}</h6>
                                <input style="border:1px solid grey;border-radius:5px;" class="white pl-2 w-100 pt-1 pb-1 fs-20" value="@if($order_id > 0) {{$order_get->email}}@endif" name="email_address" id="email-address"/>
                            </div>
                        </div>
                        <div id="menu4" class="container display-none tab-detail"><br>
                            <div class=" mt-5">
                                <h6 class="font-weight-bold fs-20">{{ __('reception.CUSTOMER_NOTE') }}</h6>
                                <textarea style="border:1px solid grey;border-radius:5px;height: 250px;" class="white pl-2 w-100 pt-1 pb-1 fs-20" name="customer_notes" id="customer-notes">@if($order_id > 0) {{$order_get->note}}@endif</textarea>
                            </div>
                        </div>
                        <div class="row text-right mm" >
                            <div class="offset-2 col-10">
                                <button type="button" id="cancel-btn" onclick="window.history.back()" class="btn black">
                                    <h3 class="mb-0 font-weight-bold fs-20">{{ __('admin.Common.Cancel') }}
                                        <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -8px 0 0 20px;"></h3>
                                </button>
                                <button type="button" id="seat-btn" onclick="nextTab('group')" class="btn black" >
                                    <h3 class="mb-0 font-weight-bold fs-20">{{ __('reception.SEAT') }}
                                    <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -8px 0 0 20px;"></h3>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="time" id="selected-time" value="{{date('Y-m-d H:i:s')}}">
        <input type="hidden" name="duration" id="selected-duration">
        <input type="hidden" name="table_id" id="selected-table" value="{{$table_id}}">
        <input type="hidden" name="order_id" id="selected-table" value="{{$order_id}}">
    </form>
    <input id="saved-width" type="hidden">
    <script src="{{ asset('js/bootstrap-timepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.ios.picker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('js/timepicki.js') }}"></script>
    <script>
        var data_arr = [
            '12:00<span style="margin-left: 100px;">AM</span>','01:00<span style="margin-left: 100px;">AM</span>','02:00<span style="margin-left: 100px;">AM</span>',
            '03:00<span style="margin-left: 100px;">AM</span>','04:00<span style="margin-left: 100px;">AM</span>','05:00<span style="margin-left: 100px;">AM</span>',
            '06:00<span style="margin-left: 100px;">AM</span>','07:00<span style="margin-left: 100px;">AM</span>','08:00<span style="margin-left: 100px;">AM</span>',
            '09:00<span style="margin-left: 100px;">AM</span>','10:00<span style="margin-left: 100px;">AM</span>','11:00<span style="margin-left: 100px;">AM</span>',
            '12:00<span style="margin-left: 100px;">PM</span>','01:00<span style="margin-left: 100px;">PM</span>','02:00<span style="margin-left: 100px;">PM</span>',
            '03:00<span style="margin-left: 100px;">PM</span>','04:00<span style="margin-left: 100px;">PM</span>','05:00<span style="margin-left: 100px;">PM</span>',
            '06:00<span style="margin-left: 100px;">PM</span>','07:00<span style="margin-left: 100px;">PM</span>','08:00<span style="margin-left: 100px;">PM</span>',
            '09:00<span style="margin-left: 100px;">PM</span>','10:00<span style="margin-left: 100px;">PM</span>','11:00<span style="margin-left: 100px;">PM</span>'
        ];

        var date_minutes = ['テイクアウト','30分','60分','90分','120分','無制限'];

        $.fn.datetimepicker.dates['ja'] = {
            days: ["日", "月", "火", "水", "木", "金", "土", "日"],
            daysShort: ["日", "月", "火", "水", "木", "金", "土", "日"],
            daysMin: ["日", "月", "火", "水", "木", "金", "土", "日"],
            months: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
            monthsShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
            today: "今日",
            meridiem: '',
        };

        $('#calendar-picker').datetimepicker({
            Default: false,
            inline: true,
            sideBySide: true,
            language: 'ja',
            weekStart: 7,
            todayBtn: 1,
            minView: 2,
            forceParse: 0
        });

        var screen_scale = "{{null !== session('scale_value1') ? session('scale_value1') : 100}}";
        $(".room-div").animate({ 'zoom': screen_scale*0.01 }, 400);
        $("#scale-value").text(screen_scale + '%');
        var table_info = document.getElementsByClassName('black-text wb');
        for(var i=0;i<table_info.length;i++) {
            table_info[i].style.fontSize = screen_scale*0.15 + 'px';
        }

        //Now
        $('#now-time-picker').picker({
                data: data_arr,
                lineHeight: 45,
                selected: '{{date("H")}}'
            },
            function(s){
                $(".output").html(s);
            });

        function changeTab(arg){
            if(arg == "group" || arg == "table" || arg == "name" || arg == "notes"){

                if($("#timepicker1").val() == '時間の選択') {
                    current_time();
                }
            }
            if(arg == "table") {

                if($("#guest-number").val() == '') {
                    $("#alert-string")[0].innerText = "ゲストを入力してください";
                    $("#java-alert").modal('toggle');
                    return;
                }
            }
            if(arg == "name" || arg == "notes"){

                if($("#selected-table").val() == 0){
                    $("#alert-string")[0].innerText = "テーブルを選択してください";
                    $("#java-alert").modal('toggle');
                    return;
                }
                //compare booking time
                var table_obj_book = <?php echo json_encode($table_obj) ?>;

                var current_date =  new Date();
                var order_start_time = '';
                var order_end_time = '';
                var status = '';
                var order_time = '';

                var order_duration = $('#duration-select').val();
                order_duration = replace_time(parseInt(order_duration));

                var selected_date = $('#calendar-picker').data().date;
                if(selected_date == undefined){
                    var d = new Date();
                    var year = d.getFullYear();
                    var month = d.getMonth() + 1;
                    if (month < 10)
                        month = '0' + month;
                    var day = d.getDate();
                    if (day < 10)
                        day = '0' + day;
                    selected_date = year + '-' + month + '-' + day;
                }else{
                    //selected_date = $('#calendar-picker').data().date.slice(0, -6);
                    selected_date = $('#calendar-picker').data().date;
                }

                var time_selected = document.getElementById("timepicker1").value;
                var hour = parseFloat(time_selected.substring(0, 2));
                if(time_selected.substring(time_selected.length-2, time_selected.length) == 'PM' && time_selected.substring(0, 2) != '12') {
                    hour += 12;
                }
                time_selected = hour.toString() + time_selected.substring(time_selected.length-6,  time_selected.length-3);

                $("#selected-time").val(selected_date + " " + time_selected + ":00");

                order_time = $('#selected-time').val();

                order_start_time = new Date(order_time.substr(0,4),parseInt(order_time.substr(5,2)),parseInt(order_time.substr(8,2)),parseInt(order_time.substr(11,2)),parseInt(order_time.substr(14,2)));
                order_start_time = order_start_time.getTime();
                if(order_duration == 'Unlimited') order_duration = 24 - parseInt(order_time.substr(11,2));
                order_end_time = order_start_time + order_duration * 60 * 1000;
                //alert(parseInt(order_time.substr(8,2))+"-"+parseInt(order_time.substr(11,2)) + ":" + parseInt(order_time.substr(14,2)) + ";" +order_duration);
                var select_obj = document.getElementsByClassName('bg-selected');

                /*for(var i=0;i<select_obj.length;i++){ //selected tables
                    var book_data = '';
                    var book_duration = '';
                    var book_start_time = '';
                    var book_end_time = '';
                    var book_time = '';
                    for(var k = 0; k < table_obj_book.length;k++) { //all tables
                        if(undefined !== table_obj_book[k] && select_obj[i].id == table_obj_book[k]['id']) {
                            book_data = table_obj_book[k]['book'];

                            if(undefined !== book_data && book_data.length > 0) {
                                for(var j=0;j<book_data.length;j++) { //already booked tables
                                    book_time = book_data[j]['time'];
                                    book_duration = book_data[j]['duration'];
                                    book_duration = replace_time(parseInt(book_duration));
                                    if(book_duration == 'Unlimited') book_duration = 24 - parseInt(book_time.substr(11,2));
                                    book_start_time = new Date(book_time.substr(0,4),parseInt(book_time.substr(5,2)),parseInt(book_time.substr(8,2)),parseInt(book_time.substr(11,2)),parseInt(book_time.substr(14,2)));
                                    book_start_time = book_start_time.getTime();
                                    book_end_time = book_start_time + book_duration * 60 * 1000;
                                    //alert(parseInt(book_time.substr(11,2))+':'+parseInt(book_time.substr(14,2))+";"+book_data[j]['duration']);
                                    //alert(book_start_time + 'qq' + order_start_time + 'aa' + book_end_time + 'zz' + order_end_time);
                                    if( (order_start_time < book_start_time && book_start_time < order_end_time) || (book_start_time < order_start_time && order_start_time < book_end_time) ) {
                                        $("#alert-string")[0].innerText = "Booking be made from "+parseInt(book_time.substr(11,2))+":"+parseInt(book_time.substr(14,2))+" already";
                                        $("#java-alert").modal('toggle');
                                        return;
                                    }
                                }
                            }
                        }
                    }
                }*/
                //compare number of selected tables with guest
                var cnt = 0;
                var type = 0;
                var table_id = '';
                for(var i=0;i<select_obj.length;i++){
                    for(var j = 0; j < table_obj_book.length;j++) {
                        if(undefined !== table_obj_book[j] && select_obj[i].id == table_obj_book[j]['id']) {
                            type = table_obj_book[j]['type'];
                        }
                    }
                    if(cnt != 'unlimit' && type == 0 ) cnt = 'unlimit';
                    else if(cnt != 'unlimit')   cnt += type;

                    table_id += select_obj[i].id + ',';
                }

                if(cnt != 'unlimit' && cnt<$("#guest-number").val()) {
                    $("#alert-string")[0].innerText = "座席数はゲストより少ないです。";
                    $("#java-alert").modal('toggle');
                    return;
                }
                //selected tables save
                $("#selected-table").val(table_id.substr(0,table_id.length-1));

            }
            $(".top-menu-btn").removeClass('top-menu-active');
            $(".tab-detail").css('display', 'none');
            var seat_btn_obj = $("#seat-btn");
            switch (arg){
                case 'home':
                    $("#home").css('display', 'block');
                    $("#now-tab").parent().parent().addClass('top-menu-active');
                    seat_btn_obj.attr("onclick", "nextTab('group')");
                    break;
                case 'group':
                    $("#menu1").css('display', 'block');
                    $("#group-tab").parent().parent().addClass('top-menu-active');
                    seat_btn_obj.attr("onclick", "nextTab('table')");
                    break;
                case 'table':
                    $("#menu2").css('display', 'block');
                    $("#table-tab").parent().parent().addClass('top-menu-active');
                    seat_btn_obj.attr("onclick", "nextTab('name')");
                    break;
                case 'name':
                    $("#menu3").css('display', 'block');
                    $("#name-tab").parent().parent().addClass('top-menu-active');
                    seat_btn_obj.attr("onclick", "nextTab('notes')");
                    break;
                case 'notes':
                    $("#menu4").css('display', 'block');
                    $("#notes-tab").parent().parent().addClass('top-menu-active');
                    seat_btn_obj.attr("onclick", "nextTab('submit')");
                    break;
            }
        }

        function onNumber(number){
            var guest_number_obj = $("#guest-number");
            var origin_number = guest_number_obj.val();
            if(parseInt(origin_number) > 0){
                if(number != "c" && number != "back"){
                    origin_number = origin_number.toString() + number.toString();
                }
                if(number == "c"){
                    origin_number = 0;
                }
                if(number == "back"){
                    origin_number = origin_number.slice(0, -1);
                    if(origin_number == ""){
                        origin_number = 0;
                    }
                }
            }else{
                if(number == "back" || number == "c"){
                    origin_number = 0;
                }else{
                    origin_number = number;
                }
            }
            guest_number_obj.val(origin_number)
        }

        // $("#display-all").click(function(){
        //     $("#display-method").hide("slow");
        //     $("#display-name-container").hide("slow");
        //     var room_content = $(".room-content-table");
        //     room_content.removeClass('col-10');
        //     room_content.addClass('col-12');
        //     room_content.css('height', '425px');
        //     $("#exit-fullscreen").show('slow');
        // });

        $("#display-all").click(function(){

            var room_obj = $(".room-div");
            var width = <?php echo json_encode($room_size->width) ?>;
            var height = <?php echo json_encode($room_size->height) ?>;
            room_obj.width(width);
            room_obj.height(height);

            if(width >= height) {
                var scale_value = (460/width).toString();
                $(".room-div").animate({ 'zoom': scale_value }, 400);
            } else {
                var scale_value = (888/height).toString();
                $(".room-div").animate({ 'zoom': scale_value }, 400);
            }
        });

        $("#exit-fullscreen").click(function() {
            $("#display-method").show("slow");
            $("#display-name-container").show("slow");
            var room_content = $(".room-content-table");
            room_content.removeClass('col-12');
            room_content.addClass('col-10');
            room_content.css('height', '480px');
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
                    table_info[i].style.fontSize = scale_value*0.15 + 'px';
                }
            }else{
                $(".plus_btn").attr("src", "{{ asset('img/plus_full.png') }}");
            }
            $.ajax({
                type: "get",
                url: "{{ route('reception.zoom_back1') }}",
                data: {scale_value: scale_value,status:"{{ $status }}",_token:"{{ csrf_token() }}"},
                success: function(data) {
                }
            });
            scale_value_obj.text(scale_value+"%");
        }

        function tableZoomOut(){
            $(".plus_btn").attr("src", "{{ asset('img/plus.png') }}");
            var scale_value_obj = $("#scale-value");
            var scale_value_all = scale_value_obj.text();
            var scale_value = scale_value_all.slice(0, -1);

            if(scale_value > 20){
                scale_value = scale_value - 10;
                if(scale_value == 20){
                    $(".minus_btn").attr("src", "{{ asset('img/minus_full.png') }}")
                }
                $(".room-div").animate({ 'zoom': scale_value*0.01 }, 400);
                for(var i=0;i<table_info.length;i++) {
                    table_info[i].style.fontSize = scale_value*0.15 + 'px';
                }
            }else{
                $(".minus_btn").attr("src", "{{ asset('img/minus_full.png') }}")
            }
            $.ajax({
                type: "get",
                url: "{{ route('reception.zoom_back1') }}",
                data: {scale_value: scale_value,status:"{{ $status }}",_token:"{{ csrf_token() }}"},
                success: function(data) {
                }
            });
            scale_value_obj.text(scale_value+"%");

        }

        function selectObject(selected_id){
            var table_obj = $("#selected-table");
            // if(selected_id == $('#table_id').val())
            $("#selected-"+selected_id+" .table-area").toggleClass("bg-selected");
            var selected_ids = table_obj.val();
            var selected_table_id = "";
            if(selected_ids == 0){
                selected_table_id = selected_id;
            }else{
                var selected_ids_arr = selected_ids.split(",");
                if(selected_ids_arr.length == 0 || selected_ids_arr.length == 1){
                    if(selected_ids != selected_id)
                        selected_table_id = selected_ids + "," + selected_id;
                }
                else {
                    if(selected_ids_arr.includes(selected_id)) {
                        selected_ids_arr.splice( selected_ids_arr.indexOf(selected_id), 1);
                        for(var i = 0; i < selected_ids_arr.length; i ++){
                            if(selected_table_id == ""){
                                selected_table_id = selected_ids_arr[i];
                            }else{
                                selected_table_id += "," + selected_ids_arr[i];
                            }
                        }
                    }
                    else {
                        for(var i = 0; i < selected_ids_arr.length; i ++){
                            if(selected_ids_arr[i] != selected_id){
                                if(selected_table_id == ""){
                                    selected_table_id = selected_ids_arr[i];
                                }else{
                                    selected_table_id += "," + selected_ids_arr[i];
                                }
                            }
                        }
                        selected_table_id += "," + selected_id;
                    }
                }
            }
            // alert(selected_table_id);
            table_obj.val(selected_table_id);
        }

        function nextTab(arg){

            if(arg != 'submit'){
                changeTab(arg);
            }else{
                if($("#selected-table").val() == 0){
                    //alert("Please select the table");
                    $("#alert-string")[0].innerText = "テーブルを選択してください";
                    $("#java-alert").modal('toggle');
                    return;
                }
                var selected_date = $('#calendar-picker').data().date;
                if(selected_date == undefined){
                    var d = new Date();
                    var year = d.getFullYear();
                    var month = d.getMonth() + 1;
                    if (month < 10)
                        month = '0' + month;
                    var day = d.getDate();
                    if (day < 10)
                        day = '0' + day;
                    selected_date = year + '-' + month + '-' + day;
                }else{
                    //selected_date = $('#calendar-picker').data().date.slice(0, -6);
                    selected_date = $('#calendar-picker').data().date;
                }

                var time_selected = document.getElementById("timepicker1").value;
                var hour = parseFloat(time_selected.substring(0, 2));
                if(time_selected.substring(time_selected.length-2, time_selected.length) == 'PM' && time_selected.substring(0, 2) != '12') {
                    hour += 12;
                }
                time_selected = hour.toString() + time_selected.substring(time_selected.length-6,  time_selected.length-3);

                $("#selected-duration").val($("#duration-select").val());
                $("#selected-time").val(selected_date + " " + time_selected + ":00");
                $("#seat-btn").toggleClass('disabled');
                $("#save-customer").submit();

            }
        }

        $("#customer-name").keyup(function() {
            $("#name-tab-div").html($("#customer-name").val());
        });


        $('#timepicker1').timepicki({
            todayBtn:1
        });

        function current_time(){

            var timepicker1_obj = $('#timepicker1');

            var currentdate = new Date();

            var current_hour = currentdate.getHours();

            var current_mins = currentdate.getMinutes();
            if(current_mins < 10)
                current_mins = "0" + current_mins;

            var current_am = "";
            if(current_hour < 12) {
                if(current_hour < 10)
                    current_hour = '0' + current_hour;
                current_am = "AM";
            }
            else {
                current_hour = current_hour - 12;
                if(current_hour < 10)
                    current_hour = '0' + current_hour;
                current_am = "PM";
            }

            timepicker1_obj.val(current_hour + ":" + current_mins + " " + current_am);

        }

        var myVar = setInterval(myTimer, 1000);
        function myTimer() {

            var table_obj =  $('#table_obj').val();
            table_obj = JSON.parse(table_obj);
            // console.log(table_obj[0]['order'][0]['time']);
            var current_time =  new Date();
            var duration = '';
            var elapsed_time = '';
            var order_time = '';
            var status = '';
            for(var i=0;i<table_obj.length;i++){

                if(table_obj[i]['order'].length > 0) {
                    duration = table_obj[i]['order'][0]['duration'];

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
                        elapsed_time = Math.round(elapsed_time);
                        if((elapsed_time >= 0) && (status != 'booking')) {
                                document.getElementById("time1_" + i).innerHTML = elapsed_time;
                        }
                        if((elapsed_time < 0) && (status != 'booking')) {
                                document.getElementById("time1_" + i).innerHTML = '0';
                        }
                    } else if(duration == 2) {
                        order_time.setMinutes( order_time.getMinutes() + 60 );
                        elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                        elapsed_time /= 60;
                        elapsed_time = Math.round(elapsed_time);
                        if((elapsed_time >= 0) && (status != 'booking')) {
                                document.getElementById("time1_" + i).innerHTML = elapsed_time;
                        }
                        if((elapsed_time < 0) && (status != 'booking')) {
                                document.getElementById("time1_" + i).innerHTML = '0';
                        }
                    } else if(duration == 3) {
                        order_time.setMinutes( order_time.getMinutes() + 90 );
                        elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                        elapsed_time /= 60;
                        elapsed_time = Math.round(elapsed_time);
                        if((elapsed_time >= 0) && (status != 'booking')) {
                                document.getElementById("time1_" + i).innerHTML = elapsed_time;
                        }
                        if((elapsed_time < 0) && (status != 'booking')) {
                                document.getElementById("time1_" + i).innerHTML = '0';
                        }
                    } else if(duration == 4) {
                        order_time.setMinutes( order_time.getMinutes() + 120 );
                        elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                        elapsed_time /= 60;
                        elapsed_time = Math.round(elapsed_time);
                        if((elapsed_time >= 0) && (status != 'booking')) {
                                document.getElementById("time1_" + i).innerHTML = elapsed_time;
                        }
                        if((elapsed_time < 0) && (status != 'booking')) {
                                document.getElementById("time1_" + i).innerHTML = '0';
                        }
                    } else if(duration == 5) {
                        if(status != 'booking') {
                                document.getElementById("time1_" + i).innerHTML = '無制限';
                        }
                    }

                }

            }
        }

        function replace_time(order_duration)
        {
            var order_duration1 = '';
            switch(order_duration)
            {
                case 0:
                    order_duration1 = "テイクアウト";
                    break;
                case 1:
                    order_duration1 = 30;
                    break;
                case 2:
                    order_duration1 = 60;
                    break;
                case 3:
                    order_duration1 = 90;
                    break;
                case 4:
                    order_duration1 = 120;
                    break;
                case 5:
                    order_duration1 = "無制限";
                    break;
            }
            return order_duration1;
        }
    </script>

@endsection
