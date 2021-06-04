@extends('layout.admin_layout')

@section('title', 'Settings')

@section('content')
    <div style="padding-top:4%;"></div>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="text-center exit_fullscreen display-none" id="exit-fullscreen">
                <span class="white-text font-weight-bold exit_fullscreen_letter">{{ __('admin.Table.EXIT_FULL_SCREEN') }}</span>
                <img src="{{ asset('img/exit-fullscreen.png') }}"/>
            </div>
        </div>
        <div class="row">
            <div class="col-1 pr-0 pl-0 text-center" id="display-method" style="margin-right: -45px;">
                <div class="text-center display_all_content" id="display-all">
                    <img src="{{ asset('img/arrow.png') }}" class="display_all_btn"/>
                    <p class="white-text font-weight-bold display_all">{{ __('admin.Table.SHOW_FULL_SCREEN') }}</p>
                </div>
                <div class="text-center display_scale">
                    <img src="{{ asset('img/plus_full.png') }}" class="plus_btn" onclick="tableZoomIn()"/>
                    <p class="font-weight-bold pt5 scale_value" id="scale-value">100%</p>
                    <img src="{{ asset('img/minus.png') }}" class="minus_btn" onclick="tableZoomOut();"/>
                </div>
            </div>
            <div class="room-content" id="room-content">
                <div class="room-div">
                    @foreach($tables as $key => $table)
                        <div class="table-common" id="selected-{{$key}}" onclick="selectObject('{{$table["id"]}}', '{{$table["type"]}}')" style="margin: {{$table['y']*20}}px 10px 10px {{$table['x']*20}}px;">
                            @if($table["type"] == 0)
                            <div style="margin:0">
                                <div style="display: inline-block;">
                                    <div>
                                        <div class="white table-c-style-disable text-center">
                                            <h6 class="font-weight-bold grey-text wb">{{ $table["name"] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($table["type"] == 1)
                            <div style="margin:0">
                                <div style="display: inline-block;">
                                    <div style="height: 110px;">
                                        <div class="white table-c-style-disable text-center">
                                            <h6 class="font-weight-bold grey-text wb">{{ $table["name"] }}</h6>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
                                </div>
                            </div>
                            @elseif($table["type"] == 2)
                            <div style="margin:0">
                                <div style="display: inline-block; margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-center"></span>
                                    <div style="height: 110px;">
                                        <div class="white table-c-style-disable text-center">
                                            <h6 class="font-weight-bold grey-text wb">{{ $table["name"] }}</h6>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
                                </div>
                            </div>
                            @elseif($table["type"] == 3)
                            <div style="margin: 0 0 0 -38px;">
                                <div style="display: inline-block; margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-center" style="margin-left: 73px;"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <h6 class="font-weight-bold grey-text wb">{{ $table["name"] }}</h6>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                            </div>
                            @elseif($table["type"] == 4)
                            <div style="margin: 0 0 0 -38px;">
                                <div style="display: inline-block; margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <h6 class="font-weight-bold grey-text wb">{{ $table["name"] }}</h6>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                            </div>
                            @elseif($table["type"] == 5)
                            <div style="margin: 0 0 0 6px;">
                                <span class="ch-2 ch-dis ch-left ch-left-center"></span>
                                <div style="display: inline-block; margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <h6 class="font-weight-bold grey-text wb">{{ $table["name"] }}</h6>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                            </div>
                            @elseif($table["type"] == 6)
                            <div style="margin: 0 0 0 6px;">
                                <span class="ch-2 ch-dis ch-left ch-left-center"></span>
                                <div style="display: inline-block; margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <h6 class="font-weight-bold grey-text wb">{{ $table["name"] }}</h6>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-dis ch-right ch-right-center"></span>
                            </div>
                            @elseif($table["type"] == 7)
                            <div style="margin: 0 0 0 5px;">
                                <span class="ch-2 ch-dis ch-left ch-left-top"></span>
                                <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
                                <div style="display: inline-block; margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <h6 class="font-weight-bold grey-text wb">{{ $table["name"] }}</h6>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-dis ch-right ch-right-center"></span>
                            </div>
                            @elseif($table["type"] == 8)
                            <div style="margin: 0 0 0 5px;">
                                <span class="ch-2 ch-dis ch-left ch-left-top"></span>
                                <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
                                <div style="display: inline-block; margin-top: -4px;">
                                    <span class="ch-1 ch-dis ch-top ch-top-left"></span>
                                    <span class="ch-1 ch-dis ch-top ch-top-right"></span>
                                    <div style="height: 118px;">
                                        <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                                            <h6 class="font-weight-bold grey-text wb">{{ $table["name"] }}</h6>
                                        </div>
                                    </div>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
                                    <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
                                </div>
                                <span class="ch-2 ch-dis ch-right ch-right-top"></span>
                                <span class="ch-2 ch-dis ch-right ch-right-bottom"></span>
                            </div>
                            @elseif($table["type"] == 9){{--Line--}}
                            <div class="text-center line-style"
                                 @if($table['index'] == "1"){{--right--}}
                                    style="padding-right: 200px;"
                                 @else
                                    style="padding-bottom: 200px;"
                                 @endif
                            ></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-4 display_name" id="display-name-container" style="width:30vw;">
            <div class="p-2 display-content">
                <h3 class="text-info font-weight-bold h3-responsive mt-3 mb-3 fs-20">{{ __('admin.Table.Table_Name') }}</h3>
                <input type="" class="black-text mb-xl-4 font-weight-bold h4-responsive selected-name fs-25" name="selected-name"
                       id="selected-name" style="width: 24vw;padding-left: 15px;padding-right: 15px;margin-left: 10px;" value="" onchange="display_name()">
                <div class="display-value-coordinate">
                    <div class="text-center mt-1" id="selected-type-content">
                        <img src="{{ asset('img/arrowbottom.png') }}" width="40px" onclick="changeCoordinate('value', 'down')" />
                        <input id="selected-type" class="d-inline black-text font-weight-bold text-center mr-2 ml-2 fs-25"
                               style="width: 90px;height: 55px;border: 1px solid black;" placeholder="1" value="0" />
                        <img src="{{ asset('img/arrowtop.png') }}" width="40px" onclick="changeCoordinate('value', 'up')"  />
                    </div>
                    <div class="text-center mt-xl-4">
                        <h4 class="text-info font-weight-bold text-left h4-responsive fs-20">{{ __('admin.Table.X') }}</h4>
                        <img src="{{ asset('img/arrowleft.png') }}" width="40px" onclick="changeCoordinate('coordinate-x', 'down')"/>
                        <input id="selected-x" class="d-inline black-text font-weight-bold text-center mr-2 ml-2 fs-25"
                               style="width: 90px;height: 55px;border: 1px solid black;" placeholder="1" value="0" />
                        <img src="{{ asset('img/arrowright.png') }}" width="40px" onclick="changeCoordinate('coordinate-x', 'up')" />
                    </div>
                    <div class="text-center mt-xl-4">
                        <h4 class="text-info font-weight-bold text-left h4-responsive fs-20">{{ __('admin.Table.Y') }}</h4>
                        <img src="{{ asset('img/arrowbottom.png') }}" width="40px" onclick="changeCoordinate('coordinate-y', 'down')" />
                        <input id="selected-y" class="d-inline black-text font-weight-bold text-center mr-2 ml-2 fs-25"
                               style="width: 90px;height: 55px;border: 1px solid black;" placeholder="1" value="0"  />
                        <img src="{{ asset('img/arrowtop.png') }}" width="40px" onclick="changeCoordinate('coordinate-y', 'up')"/>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6 pl-xl-5">
                        <button class="btn grey pr-2 pl-0" style="width: 12vw;" onclick="location.reload();">
                            <h5 class="mb-0 font-weight-bold h5-responsive fs-20">
                                {{__('admin.Common.Cancel')}}
                                <br>
                                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 10px;">
                            </h5>
                        </button>
                    </div>
                    <div class="col-6 pl-xl-2">
                        <button class="btn bg-info pr-2 pl-2" style="width: 12vw;margin-left: -5px;" onclick="saveChangedTables()">
                            <h5 class="mb-0 font-weight-bold h5-responsive fs-20">
                                {{__('admin.Common.Apply')}}
                                <br>
                                <img src="{{ asset('img/Group728white.png') }}" style="height:18px;">
                            </h5>
                        </button>
                    </div>
                </div>
                <div class="row pr-3 pl-3 mt-2 mb-2">
                    <button class="btn bg-grey pr-2 pl-2 w-100">
                        <h5 class="mb-0 font-weight-bold h5-responsive fs-20" id="delete-selected-obj">
                            {{ __('admin.Table.DELETE_THIS_TABLE') }}
                            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
                        </h5>
                    </button>
                    <button class="btn bg-info pr-2 pl-2 w-100">
                        <h5 class="mb-0 font-weight-bold h5-responsive fs-15" id="add-new-table">
                            {{ __('admin.Table.ADD_NEW_TABLE') }}
                            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
                        </h5>
                    </button>
                    <button class="btn bg-info pr-2 pl-2 w-100">
                        <h5 class="mb-0 font-weight-bold h5-responsive fs-20" id="add-new-line">
                            {{ __('admin.Table.ADD_NEW_LINE') }}
                            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
                        </h5>
                    </button>
                    <button class="btn bg-info pr-2 pl-2 w-100">
                        <h5 class="mb-0 font-weight-bold h5-responsive fs-20" id="change-room-size">
                            {{ __('admin.Table.CHANGE_ROOM_SIZE') }}
                            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
                        </h5>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.table.store') }}" id="save-form" enctype='multipart/form-data'>
        <input type="hidden" name="saved_arr" id="saved-arr">
        <input type="hidden" name="id" id="selected-id" value="0">
        @csrf
    </form>
    <input type="hidden" id="saved-width">
    {{--Modal Add new LIne--}}
    <div class="modal fade" id="add-new-line-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img style="width:20px;height:20px;" src="{{asset("img/Group1100.png")}}">
                    </button>
                </div>
                <div class="row mt-4">
                    <div class="col-5">
                        <div class="form-check d-inline ml-5">
                            <input type="radio" class="form-check-input rdobtn" id="line-r-checked" name="line" checked onclick="showLine(1)">
                            <label class="form-check-label text-blue txtdemibold fs-25" for="line-r-checked">Line Right</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-check d-inline ml-2">
                            <input type="radio" class="form-check-input rdobtn" id="line-b-checked" name="line" onclick="showLine(2)">
                            <label class="form-check-label text-blue txtdemibold fs-25" for="line-b-checked">Line Bottom</label>
                        </div>
                    </div>
                </div>
                <div class="modal-line-content">
                    <a class="line-sample" id="line-sample-r"><img src="{{asset('img/line_right.png')}}"></a>
                    <a class="line-sample display-none" id="line-sample-b"><img src="{{asset('img/line_bottom.png')}}"></a>
                </div>
                <div style="text-align: center;margin-bottom:15px;">
                    <button type="button" class="btn btn-light waves-effect waves-light fs-25" data-dismiss="modal">
                        {{ __('admin.Common.Cancel') }}
                        <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin-left: 20px;">
                    </button>
                    <button type="button" id="add-line-btn" class="btn btn-primary waves-effect waves-light fs-25"
                            style="padding: 15px;width: 25%;" onclick="addLine(1)">
                        {{ __('admin.Common.Apply') }}
                        <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{--Change room size modal--}}
    <div class="modal fade" id="change-room-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img style="width:20px;height:20px;" src="{{asset("img/Group1100.png")}}">
                    </button>
                </div>
                <div class="text-center room-size" id="room-width-content">
                    <span class="text-info font-weight-bold h3-responsive fs-25">Room Width</span>
                    <input id="room-width" class="d-inline black-text font-weight-bold text-center w-25 mr-3 ml-3 fs-25" placeholder="1" value="{{ $room_size->width }}" />
                    <span class="text-info font-weight-bold h3-responsive fs-25">PX</span>
                </div>
                <div class="text-center room-size" id="room-height-content">
                    <span class="text-info font-weight-bold h3-responsive fs-25">Room Height</span>
                    <input id="room-height" class="d-inline black-text font-weight-bold text-center w-25 mr-3 ml-3 fs-25" placeholder="1" value="{{ $room_size->height }}" />
                    <span class="text-info font-weight-bold h3-responsive fs-25">PX</span>
                </div>
                <div style="text-align: center;margin-bottom:15px;">
                    <button type="button" class="btn btn-light waves-effect waves-light fs-25" data-dismiss="modal">
                        {{ __('admin.Common.Cancel') }}
                        <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin-left: 20px;">
                    </button>
                    <button type="button" id="save-room-btn" class="btn btn-primary waves-effect waves-light fs-25" style="padding: 15px;width: 25%;" >
                        {{ __('admin.Common.Apply') }}
                        <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{--seat:0--}}
    <div class="table-common display-none" id="clone-0">
        <div style="display: inline-block;">
            <div>
                <div class="white table-c-style-disable text-center">
                    <h6 class="font-weight-bold blue-text wb"></h6>
                </div>
            </div>
        </div>
    </div>
    {{--seat:1--}}
    <div class="table-common display-none" id="clone-1">
        <div style="display: inline-block;">
            <div style="height: 110px;">
                <div class="white table-c-style-disable text-center">
                    <h6 class="font-weight-bold blue-text wb"></h6>
                </div>
            </div>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
        </div>
    </div>
    {{--seat:2--}}
    <div class="table-common display-none" style="margin-top: -4px;" id="clone-2">
        <div style="display: inline-block;">
            <span class="ch-1 ch-dis ch-top ch-top-center"></span>
            <div style="height: 110px;">
                <div class="white table-c-style-disable text-center">
                    <h6 class="font-weight-bold blue-text wb"></h6>
                </div>
            </div>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-center"></span>
        </div>
    </div>
    {{--seat:3--}}
    <div class="table-common display-none" style="margin: -4px 0 0 -37px;" id="clone-3">
        <div style="display: inline-block;">
            <span class="ch-1 ch-dis ch-top ch-top-center" style="margin-left: 73px;"></span>
            <div style="height: 118px;">
                <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                    <h6 class="font-weight-bold blue-text wb"></h6>
                </div>
            </div>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
        </div>
    </div>
    {{--seat:4--}}
    <div class="table-common display-none" style="margin: -4px 0 0 -37px;" id="clone-4">
        <div style="display: inline-block;">
            <span class="ch-1 ch-dis ch-top ch-top-left"></span>
            <span class="ch-1 ch-dis ch-top ch-top-right"></span>
            <div style="height: 118px;">
                <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                    <h6 class="font-weight-bold blue-text wb"></h6>
                </div>
            </div>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
        </div>
    </div>
    {{--seat:5--}}
    <div class="table-common display-none" style="margin-top: -4px;" id="clone-5">
        <span class="ch-2 ch-dis ch-left ch-left-center"></span>
        <div style="display: inline-block;">
            <span class="ch-1 ch-dis ch-top ch-top-left"></span>
            <span class="ch-1 ch-dis ch-top ch-top-right"></span>
            <div style="height: 118px;">
                <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                    <h6 class="font-weight-bold blue-text wb"></h6>
                </div>
            </div>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
        </div>
    </div>
    {{--seat:6--}}
    <div class="table-common display-none" style="margin-top: -4px;" id="clone-6">
        <span class="ch-2 ch-dis ch-left ch-left-center"></span>
        <div style="display: inline-block;">
            <span class="ch-1 ch-dis ch-top ch-top-left"></span>
            <span class="ch-1 ch-dis ch-top ch-top-right"></span>
            <div style="height: 118px;">
                <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                    <h6 class="font-weight-bold blue-text wb"></h6>
                </div>
            </div>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
        </div>
        <span class="ch-2 ch-dis ch-right ch-right-center"></span>
    </div>
    {{--seat:7--}}
    <div class="table-common display-none" style="margin-top: -4px;" id="clone-7">
        <span class="ch-2 ch-dis ch-left ch-left-top"></span>
        <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
        <div style="display: inline-block;">
            <span class="ch-1 ch-dis ch-top ch-top-left"></span>
            <span class="ch-1 ch-dis ch-top ch-top-right"></span>
            <div style="height: 118px;">
                <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                    <h6 class="font-weight-bold blue-text wb"></h6>
                </div>
            </div>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
        </div>
        <span class="ch-2 ch-dis ch-right ch-right-center"></span>
    </div>
    {{--seat:8--}}
    <div class="table-common display-none" style="margin-top: -4px;" id="clone-8">
        <span class="ch-2 ch-dis ch-left ch-left-top"></span>
        <span class="ch-2 ch-dis ch-left ch-left-bottom"></span>
        <div style="display: inline-block;">
            <span class="ch-1 ch-dis ch-top ch-top-left"></span>
            <span class="ch-1 ch-dis ch-top ch-top-right"></span>
            <div style="height: 118px;">
                <div class="white table-c-style-disable text-center" style="margin-left: 38px;">
                    <h6 class="font-weight-bold blue-text wb"></h6>
                </div>
            </div>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-left"></span>
            <span class="ch-1 ch-dis ch-bottom ch-bottom-right"></span>
        </div>
        <span class="ch-2 ch-dis ch-right ch-right-top"></span>
        <span class="ch-2 ch-dis ch-right ch-right-bottom"></span>
    </div>
    {{--clone line right--}}
    <div class="table-common display-none" id="clone-line-1">
        <div class="text-center clone-line-style" style="padding-right: 200px;">
        </div>
    </div>
    {{--clone line bottom--}}
    <div class="table-common display-none" id="clone-line-2">
        <div class="text-center clone-line-style" style="padding-bottom: 200px;">
        </div>
    </div>
    <script>
        var tables_arr = <?php echo json_encode($tables); ?>;
        var new_id = <?php echo $new_id; ?>;
        var table_type_arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', 'Line'];
        var selected_arr = "";
        var tmp_selected_arr = "";
        var selected_type = "";
        var selected_index = "";

        $("document").ready(function () {
            $("#add-new-table").click(function() {
                // $("#add-new-table-modal").modal('toggle');
                addTable(0);
            });

            $("#add-new-line").click(function() {
                $("#add-new-line-modal").modal('toggle');
            });
        });

        function display_name() {

            if(selected_index == ""){
                alert("Please select the table or line!");
//                $("#alert-string")[0].innerText = "There is no calling data.";
//                $("#java-alert").modal('toggle');
                return;
            }
            var display_table_name = $('#selected-name').val();

            if(selected_type < 9) {
                $("#selected-"+selected_index+" h6")[0].innerText = display_table_name;
                tables_arr[selected_index]['name'] = display_table_name;
            } else {
                //alert("You can't named the line.");
                $("#alert-string")[0].innerText = "線に名前を付けることはできません。";
                $("#java-alert").modal('toggle');
                $("#selected-name").val('');
            }
//            var tbnm = document.getElementById("tb_nm").offsetWidth;
//            var tb = $(document.getElementsByClassName("table-c-style-disable")).width();

        }

        function addTable(type)
        {
            var cloneTable = $("#clone-"+type).clone();
            $(cloneTable).css("display", "block");
            var id = new_id;
            new_id ++;
            tables_arr[id] = {'id':id, 'x':0, 'y':0, 'type':type, 'index':0, 'name':''};
            $(cloneTable).attr("onclick", "selectObject('"+id+"', 'table')");
            $(cloneTable).attr("id", "selected-"+id);
            $(".room-div").append(cloneTable);
            selectObject(id, type);
            // $("#add-new-table-modal").modal('toggle');
        }

        function showLine(type)
        {
            $(".line-sample").css("display", "none");
            $("#add-line-btn").attr("onclick", "addLine('"+type+"')");
            switch(type){
                case 1://Right
                    $("#line-sample-r").css("display", "block");
                    break;
                case 2://Bottom
                    $("#line-sample-b").css("display", "block");
                    break;
            }
        }

        function addLine(type)
        {
            var cloneLine = $("#clone-line-"+type).clone();
            $(cloneLine).css("display", "block");
            var id = new_id;
            new_id ++;
            tables_arr[id] = {'id':id, 'x':0, 'y':0, 'type': 9, 'index':type, 'name':''};
            $(cloneLine).attr("onclick", "selectObject('"+id+"', 'line')");
            $(cloneLine).attr("id", "selected-"+id);
            $(".room-div").append(cloneLine);
            selectObject(id, type);
            $("#add-new-line-modal").modal('toggle');
        }

        function selectObject(index, type)
        {
            // console.log(tables_arr);
            selected_arr = new Object(tables_arr[index]);
            // console.log(selected_arr);
            var selected_name = "";
            var selected_value_obj = $("#selected-type-content");
            tmp_selected_arr = selected_arr;
            selected_type = tables_arr[index]['type'];
            selected_value_obj.css("display", "block");
            selected_index = index;
            $(".table-common").css("z-index",0);
            $(".table-common h6").removeClass("blue-text");
            $(".table-common h6").addClass("grey-text");
            $("#selected-"+selected_index).css("z-index", 1);
            $("#selected-"+selected_index+" h6").removeClass("grey-text");
            $("#selected-"+selected_index+" h6").addClass("blue-text");

            if(selected_type < 9){
                // selected_name = $("#selected-name").val();
                selected_name = $("#selected-"+index+" h6")[0].innerText;
                $("#selected-value").val(selected_arr.index);
                $("#delete-selected-obj")[0].innerHTML = "このテーブルを削除 &gt;";
            }else{//"line"
                selected_name = "Line";
                selected_value_obj.css("display", "none");
                $("#delete-selected-obj")[0].innerHTML = "このテーブルを削除 &gt;";
            }
            $("#selected-name").val(selected_name);
            tables_arr[index]['name'] = $("#selected-name").val();
            $("#selected-type").val(selected_arr.type);
            $("#selected-x").val(selected_arr.x);
            $("#selected-y").val(selected_arr.y);
        }

        function changeCoordinate(type, aroma)
        {
            if(tmp_selected_arr == ""){
                //alert("Please select the table or line!");
                $("#alert-string")[0].innerText = "テーブルまたはラインを選択してください！";
                $("#java-alert").modal('toggle');
                return;
            }
            if(selected_type <  9){//"line"
                var selected_value = tmp_selected_arr.index;
                var selected_table_type = table_type_arr[tmp_selected_arr.type];
                var selected_name = $('#selected-name').val();
            }
            var coordinate_x = tmp_selected_arr.x;
            var coordinate_y = tmp_selected_arr.y;
            var selectedObj = $("#selected-"+selected_index);
            var room_obj = $(".room-content");

            switch (type){
                case "value":
                    if(aroma == "up"){
                        if(selected_type <= 7){
                            selected_type ++;
                            if(selected_type == 1) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <div style=\"height: 110px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\">\n" +
                                    "              <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "            </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-center\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 2) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin-top: -4px;\">\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-center\"></span>\n" +
                                    "       <div style=\"height: 110px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-center\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 3) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 -38px;\">\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-center\" style=\"margin-left: 73px;\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 4) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 -38px;\">\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-right\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 5) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 6px;\">\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-center\"></span>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-right\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 6) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 6px;\">\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-center\"></span>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-right\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "   <span class=\"ch-2 ch-dis ch-right ch-right-center\"></span>\n" +
                                    "</div>";
                            } else if (selected_type == 7) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 5px;\">\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-top\"></span>\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-bottom\"></span>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-right\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "   <span class=\"ch-2 ch-dis ch-right ch-right-center\"></span>\n" +
                                    "</div>";
                            } else if (selected_type == 8) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 5px;\">\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-top\"></span>\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-bottom\"></span>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-right\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "   <span class=\"ch-2 ch-dis ch-right ch-right-top\"></span>\n" +
                                    "   <span class=\"ch-2 ch-dis ch-right ch-right-bottom\"></span>\n" +
                                    "</div>";
                            }
                        } else {
                            //alert("You can't create seat for over 8.");
                            $("#alert-string")[0].innerText = "8人以上の座席を作成することはできません。";
                            $("#java-alert").modal('toggle');
                            break;
                        }
                    }else{
                        if(selected_type > 0){
                            selected_type --;
                            if(selected_type == 0) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <div>\n" +
                                    "           <div class=\"white table-c-style-disable text-center\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if(selected_type == 1) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <div style=\"height: 110px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\">\n" +
                                    "              <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "            </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-center\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 2) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin-top: -4px;\">\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-center\"></span>\n" +
                                    "       <div style=\"height: 110px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-center\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 3) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 -38px;\">\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-center\" style=\"margin-left: 73px;\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 4) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 -38px;\">\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-right\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 5) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 6px;\">\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-center\"></span>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-right\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "</div>";
                            } else if (selected_type == 6) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 6px;\">\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-center\"></span>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-right\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "   <span class=\"ch-2 ch-dis ch-right ch-right-center\"></span>\n" +
                                    "</div>";
                            } else if (selected_type == 7) {
                                document.getElementById("selected-"+selected_index).innerHTML =
                                    "<div style=\"margin: -4px 0 0 5px;\">\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-top\"></span>\n" +
                                    "   <span class=\"ch-2 ch-dis ch-left ch-left-bottom\"></span>\n" +
                                    "   <div style=\"display: inline-block;\">\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-top ch-top-right\"></span>\n" +
                                    "       <div style=\"height: 118px;\">\n" +
                                    "           <div class=\"white table-c-style-disable text-center\" style=\"margin-left: 38px;\">\n" +
                                    "               <h6 class=\"font-weight-bold blue-text wb\"></h6>\n" +
                                    "           </div>\n" +
                                    "       </div>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-left\"></span>\n" +
                                    "       <span class=\"ch-1 ch-dis ch-bottom ch-bottom-right\"></span>\n" +
                                    "   </div>\n" +
                                    "   <span class=\"ch-2 ch-dis ch-right ch-right-center\"></span>\n" +
                                    "</div>";
                            }
                        }
                    }
                    tmp_selected_arr.index = selected_value;
                    $("#selected-name")[0].innerText = selected_name;
                    $("#selected-"+selected_index+" h6")[0].innerText = selected_name;
                    $("#selected-type").val(selected_type);

                    tables_arr[selected_index]['type'] = selected_type;
                    // tables_arr[selected_index]['name'] = $('#selected-name').val();
                    break;
                case "coordinate-x":
                    var room_pos_left = room_obj.width() + room_obj[0].scrollLeft;
                    if(aroma == "up"){
                        if($(".room-div").width() > room_pos_left) {
                            coordinate_x++;
                        }

                    }else{
                        if(coordinate_x > 0){
                            coordinate_x --;
                        }
                    }
                    var margin_left = coordinate_x * 20;
                    selectedObj.css("margin-left", margin_left+"px");
                    tmp_selected_arr.x = coordinate_x;
                    $("#selected-x").val(coordinate_x);
                    var selected_pos_left = selectedObj[0].offsetLeft + selectedObj.width();
                    if(aroma == "down" && coordinate_y > 0){
                        if(room_obj[0].scrollLeft > selectedObj[0].offsetLeft){
                            room_obj[0].scrollLeft = room_obj[0].scrollLeft - 20;
                        }
                    }else{
                        if(room_pos_left < selected_pos_left){
                            room_obj[0].scrollLeft = room_obj[0].scrollLeft + 20;
                        }
                    }
                    break;
                case "coordinate-y":
                    var room_pos_top = room_obj.height() + room_obj[0].scrollTop;

                    if(aroma == "up"){
                        if(coordinate_y > 0){
                            coordinate_y --;
                        }
                    }else{
                        if($(".room-div").height() > room_pos_top) {
                            coordinate_y++;
                        }
                    }
                    var margin_top = coordinate_y * 20;
                    selectedObj.css("margin-top", margin_top+"px");
                    tmp_selected_arr.y = coordinate_y;
                    $("#selected-y").val(coordinate_y);

                    var selected_pos_top = selectedObj[0].offsetTop + selectedObj.height();
                    if(aroma == "up" && coordinate_y > 0){
                        if(room_obj[0].scrollTop > selectedObj[0].offsetTop){
                            room_obj[0].scrollTop = room_obj[0].scrollTop - 20;
                        }
                    }else{
                        if(room_pos_top < selected_pos_top){
                            room_obj[0].scrollTop = room_obj[0].scrollTop + 20;
                        }
                    }
                    break;
            }
        }

        function saveChangedTables()
        {
            var form = $("#save-form");
            var saved_arr = JSON.stringify(tables_arr);
            $("#saved-arr").val(saved_arr);
            if($('#selected-' + selected_index + ' h6')[0].innerHTML == '') {
                $("#alert-string")[0].innerText = "テーブル名を入力してください！";
                $("#java-alert").modal('toggle');
            } else {
                form.submit();
            }
            //form.submit();
        }

        $("#selected-value").keyup(function() {
            if(selected_index == ""){
                //alert("Please select the table or line!");
                $("#alert-string")[0].innerText = "テーブルまたはラインを選択してください！";
                $("#java-alert").modal('toggle');
                return;
            }
            var selected_value = $("#selected-value").val();
            if(selected_type != 9){//"line"
                var selected_table_type = table_type_arr[tmp_selected_arr.type];
            }
            tmp_selected_arr.index = selected_value;
            $("#selected-name")[0].innerText = selected_table_type+"-"+selected_value;
            $("#selected-"+selected_index+" h6")[0].innerText = selected_table_type+"-"+selected_value;
        });

        $("#selected-x").keyup(function(){
            if(selected_index == ""){
                //alert("Please select the table or line!!");
                $("#alert-string")[0].innerText = "テーブルまたはラインを選択してください！";
                $("#java-alert").modal('toggle');
                return;
            }
            var coordinate_x = $("#selected-x").val();
            var selectedObj = $("#selected-"+selected_index);
            var room_obj = $(".room-content");
            var margin_left = coordinate_x * 20;
            var room_div_obj = $(".room-div");
            var move_value = margin_left + selectedObj.width();
            if(move_value > room_div_obj.width()){
                margin_left = room_div_obj.width()- selectedObj.width();
                selectedObj.css("margin-left", margin_left+"px");
                room_obj[0].scrollLeft = room_div_obj.width() - room_obj.width();
            }else{
                selectedObj.css("margin-left", margin_left+"px");
                room_obj[0].scrollLeft = selectedObj.width() + margin_left - room_obj.width();
            }
            tmp_selected_arr.x = coordinate_x;
        });

        $("#selected-y").keyup(function(){
            if(selected_index == ""){
                //alert("Please select the table or line!");
                $("#alert-string")[0].innerText = "テーブルまたはラインを選択してください！";
                $("#java-alert").modal('toggle');
                return;
            }
            var coordinate_y = $("#selected-y").val();
            var selectedObj = $("#selected-"+selected_index);
            var margin_top = coordinate_y * 20;
            var room_obj = $(".room-content");
            var room_div_obj = $(".room-div");
            var move_value = margin_top + selectedObj.height();
            if(move_value > room_div_obj.height()){
                margin_top = room_div_obj.height()- selectedObj.height();
                selectedObj.css("margin-top", margin_top+"px");
                room_obj[0].scrollTop = room_div_obj.height() - room_obj.height();
            }else{
                selectedObj.css("margin-top", margin_top+"px");
                room_obj[0].scrollTop = selectedObj.height() + margin_top - room_obj.height();
            }
            tmp_selected_arr.y = coordinate_y;
        });

        $("#delete-selected-obj").click(function(){
            if(selected_index == ""){
                //alert("Please select the table or line!");
                $("#alert-string")[0].innerText = "テーブルまたはラインを選択してください！";
                $("#java-alert").modal('toggle');
                return;
            }
            var saved_arr = JSON.stringify(tables_arr);
            $("#saved-arr").val(saved_arr);
            $("#selected-id").val(selected_index);
            $("#save-form").submit();
        });

        $("#change-room-size").click(function() {
            var room_obj = $(".room-div");
            // $("#room-width").val(room_obj.width());
            // $("#room-height").val(room_obj.height());
            $("#change-room-modal").modal("toggle");
        });

        $("#save-room-btn").click(function(){
            var room_obj = $(".room-div");
            var width = $("#room-width").val();
            var height = $("#room-height").val();

            $.ajax({
                type:"POST",
                url:"{{ route('admin.change_roomsize') }}",
                data:{
                    room_width: width, room_height: height, _token:"{{ csrf_token() }}"
                },
                success: function(result){
                    room_obj.width(width);
                    room_obj.height(height);
                    $("#change-room-modal").modal("toggle");
                }
            });

        });

        $("#display-all").click(function(){
            // $("#display-method").hide("slow");
            // $("#display-name-container").hide("slow");
            // var room_content = $(".room-content");
            // $("#saved-width").val(room_content.width());
            // room_content.width('1000px');
            // room_content.height('645px');
            // room_content.css('margin-top', '15px');
            // room_content.css('margin-left', '15px');
            // $("#exit-fullscreen").show('slow');

            var room_obj = $(".room-div");
            var width = $("#room-width").val();
            var height = $("#room-height").val();
            room_obj.width(width);
            room_obj.height(height);

            if(width >= height) {
                var scale_value = (865/width).toString();
                $(".room-div").animate({ 'zoom': scale_value }, 400);
            } else {
                var scale_value = (888/height).toString();
                $(".room-div").animate({ 'zoom': scale_value }, 400);
            }

        });

        $("#exit-fullscreen").click(function() {
            $("#display-method").show("slow");
            $("#display-name-container").show("slow");
            $(".room-content").width('865px');
            $(".room-content").height('888px');
            // $(".room-content").css('margin-top', '-5px');
            $(".room-content").css('margin-left', '20px');
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
            }else{
                $(".plus_btn").attr("src", "{{ asset('img/plus_full.png') }}");
            }
            scale_value_obj.text(scale_value+"%");
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
            }else{
                $(".minus_btn").attr("src", "{{ asset('img/minus_full.png') }}")
            }
            scale_value_obj.text(scale_value+"%");
        }

    </script>
@endsection
