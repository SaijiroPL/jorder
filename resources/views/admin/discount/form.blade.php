@extends('layout.admin_layout')

@section('title', 'DISH')

@section('content')
    <style>
        .option-padding {
            padding-top : 0.6rem;
            padding-bottom : 0.6rem;
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
            font-size: 50px;
            font-weight:bold;
            border-radius:50px !important;
            /*border:2px solid #1ec2c9*/
        }

        .datetimepicker-days table.table-condensed {
            width: 360px;
        }

    </style>
    <div class="container-fluid pb-3 pt-3 blackgrey">
        <form method="POST" action="{{ route('admin.discount.store') }}" enctype='multipart/form-data' onSubmit="return validateform()">
            <input type="hidden" name="id" value="{{$obj->id}}">
            <input type="hidden" name="checked_start_date" id="checked-start-date" value="{{(isset($obj->start) ? $obj->start : date("Y-m-d H:i:s"))}}">
            <input type="hidden" name="checked_end_date" id="checked-end-date" value="{{(isset($obj->end) ? $obj->end : date("Y-m-d H:i:s"))}}">
            <input type="hidden" name="end_type" id="end-type" value="{{isset($obj->end_type) ? $obj->end_type : 0}}">
            <div style="padding-top:7%;"></div>
            <div class="widthh pt-3 pb-3 mb-3 white">
                <div class="row">
                    <div class="col-12">
                        <a>
                            <span class="">
                                <a href="{{route('admin.discount')}}">
                                    <img src="{{ asset('img/Group1100.png') }}" width="25" height="25" class="float-right" />
                                </a>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="row pl-4 pr-4">
                    <div class="col-6">
                        <div class="form-group">
                            <div>
                                <label class="text-blue txtdemibold fs-20">{{ __('admin.Discount.Choose_Dish') }}</label>
                            </div>
                            <select onchange="changeDish(this);" class="border-blue select-width-blue mr-1 option-padding option-select fs-20" style="width:100%" name="dish_id">
                                @foreach ($dishes as $ds)
                                    <option value="{{ $ds->id }}" data-price="{{$ds->price}}" @if(isset($dish) && $ds->id == $dish) selected @endif>{{ $ds->name_en }}</option>
                                @endforeach
                            </select>
                            <label class="text-blue float-right text-right fs-23" id="rrp_price">{{ __('admin.Dish.PRICE') }}: ¥ {{ round($dishes[0]->price) }}</label>
                        </div>
                        <div class="form-group">
                            <div>
                                <label class="text-blue txtdemibold fs-20">{{ __('admin.Discount.Discounted_Price') }}:</label>
                            </div>
                            <input type="number" class="outline-0 border-bottom-blue" style="font-size: 25px;" value="{{$obj->discount}}" id="discount-value" name="discount" step="0.01"/>
                            <input type="hidden" value="{{ $gst }}" id="gst" name="gst">
                            <label class="text-blue float-right  text-right fs-23" id="gst_value">
                                @if ($obj->discount > 0)
                                    (税: ¥ {{ round($obj->discount*$gst/100) }})
                                @else
                                    &nbsp;
                                @endif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 pl-4 pr-4">
                    <div class="col-6">
                        <div>
                            <label class="text-blue txtdemibold mr-3 fs-20">{{ __('admin.Discount.START') }}</label>
                            <div class="form-check d-inline">
                                <input type="radio" class="form-check-input rdobtn" id="materialUnchecked" name="start"
                                    @if($obj->end_type == 2 || $obj->end_type == 4) checked @endif>
                                <label class="form-check-label text-blue txtdemibold fs-20" for="materialUnchecked">{{ __('admin.Discount.NOW') }}</label>
                            </div>
                        </div>
                        <div>
                            <label class="text-blue txtdemibold invisible mr-3 fs-20">Start</label>
                            <div class="form-check d-inline">
                                <input type="radio" class="form-check-input" id="materialChecked" name="start"
                                    @if($obj->end_type != 2 && $obj->end_type != 4) checked @endif>
                                <label class="form-check-label text-blue txtdemibold fs-20" id="selected-start-date" for="materialChecked">
                                    @if($obj->id > 0 && $obj->end_type != 2)
                                        {{date("H:i d F Y", strtotime($obj->start))}}
                                    @else
                                        {{date("H:i d F Y")}}
                                    @endif
                                </label>
                            </div>
                            <button type="button" class="addOptionbtn pl-4 pr-4 float-right fs-20" onclick="onDateModal('start')">{{ __('admin.Common.Update') }}</button>
                        </div>
                    </div>
                    <div class="col-6">
                        <div>
                            <label class="text-blue txtdemibold mr-3  fs-20">{{ __('admin.Discount.END') }}</label>
                            <div class="form-check d-inline">
                                <input type="radio" class="form-check-input" id="materialUnchecked2" name="end"
                                    @if($obj->end_type == 3 || $obj->end_type == 4) checked @endif>
                                <label class="form-check-label text-blue txtdemibold fs-20" for="materialUnchecked2">{{ __('admin.Discount.Indefinite') }}</label>
                            </div>
                        </div>
                        <div>
                            <label class="text-blue txtdemibold invisible mr-3 fs-20">End</label>
                            <div class="form-check d-inline">
                                <input type="radio" class="form-check-input" id="materialChecked3" name="end"
                                    @if($obj->end_type != 3 && $obj->end_type != 4) checked @endif>
                                <label class="form-check-label text-blue txtdemibold fs-20" id="selected-end-date" for="materialChecked3">
                                    @if($obj->id > 0 && $obj->end_type != 3 )
                                        {{date("H:i d F Y", strtotime($obj->end))}}
                                    @else
                                        {{date("H:i d F Y")}}
                                    @endif</label>
                            </div>
                            <button type="button" class="addOptionbtn pl-4 pr-4 float-right fs-20" onclick="onDateModal('end')">{{ __('admin.Common.Update') }}</button>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 pl-4 pr-4">
                    <div class="col-7">
                        <div class="border-bottom-blue">
                            <div class="row">
                                <div class="col-8"><label class="text-blue txtdemibold mt-2 fs-20">{{ __('admin.Discount.TIME_SLOTS') }}</label></div>
                            </div>
                        </div>
                        <div class="border-bottom-blue" style="margin: 8px 0 0 0;">
                            <div class="row">
                                <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Breakfast') }}</label></div>
                                <div class="col-4">
                                    <div class="float-right mt-2">
                                        <label class="bs-switch">
                                            <input type="checkbox" name="timeslot_breakfast"
                                               @if($obj->timeslot_breakfast == 1) checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-bottom-blue" style="margin: 8px 0 0 0;">
                            <div class="row">
                                <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Lunch') }}</label></div>
                                <div class="col-4">
                                    <div class="float-right mt-2">
                                        <label class="bs-switch ">
                                            <input type="checkbox" name="timeslot_lunch"
                                               @if($obj->timeslot_lunch == 1) checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-bottom-blue" style="margin: 8px 0 0 0;">
                            <div class="row">
                                <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Tea') }}</label></div>
                                <div class="col-4">
                                    <div class="float-right mt-2">
                                        <label class="bs-switch ">
                                            <input type="checkbox" name="timeslot_tea"
                                                @if($obj->timeslot_tea == 1) checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-bottom-blue" style="margin: 8px 0 0 0;">
                            <div class="row">
                                <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Dinner') }}</label></div>
                                <div class="col-4">
                                    <div class="float-right mt-2">
                                        <label class="bs-switch ">
                                            <input type="checkbox" name="timeslot_dinner"
                                                @if($obj->timeslot_dinner == 1) checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 pl-4 pr-4">
                    <div class="col-7">
                        @if($obj->end_type == 0 && $obj->id > 0)
                            <button class="grey-button fs-20" onclick="onEndNow()" style="padding: 10px 15px 10px 15px;margin-top: -10px;">
                                END NOW
                                <img src="{{ asset('img/Group728.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
                            </button>
                        @endif
                    </div>
                    <div class="col-5">
                        <a class="grey-button fs-20" href="{{route('admin.discount')}}" style="color:black;padding: 14px 15px 14px 15px;margin-left: 50px;">
                            {{ __('admin.Common.Cancel') }}
                            <img src="{{ asset('img/Group728.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
                        </a>
                        <button class="green-button fs-20" style="padding: 10px 15px 10px 15px;margin-top:-10px;">
                            {{ __('admin.Common.Apply') }}
                            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
                        </button>
                    </div>
                </div>
            </div>
            @csrf
        </form>
    </div>

    <div class="modal fade" id="datetimemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="width: 800px;margin-left: -30%;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('img/Group1100.png') }}" width="30" height="30" class="float-right mt-3 mr-3" />
                    </button>
                </div>
                <div class="modal-body pr-4" style="width: 800px;">
                    <div class="row">
                        <div class="col-6">
                            <h3 class="font-weight-bold mb-3 text-left text-info fs-20" style="border-bottom:2px solid #1ec2c9;padding-bottom:11px">Date</h3>
                            <div id="now-datepicker"  style="font-size: 20px;width: 200px;"></div>
                        </div>
                        <div class="col-6 text-center">
                            <h3 class="font-weight-bold mb-3 text-left text-info fs-20" style="border-bottom:2px solid #1ec2c9;padding-bottom:11px">Time</h3>
                            <div id="now-time-picker" style="font-size: 25px;"></div>
                            <div class="output display-none">0</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                            {{ __('admin.Common.Cancel') }}
                            <img src="{{ asset('img/Group728.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
                        </button>
                        <button type="button" class="btn btn-primary waves-effect waves-light fs-20" id="apply-time-btn">
                            {{ __('admin.Common.Apply') }}
                            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="start-date-save">
    <input type="hidden" id="start-time-save">
    <input type="hidden" id="end-date-save">
    <input type="hidden" id="end-time-save">
    <script src="{{ asset('js/bootstrap-timepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.ios.picker.js') }}"></script>
    <script>
        var data_arr = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00'
            ,'12:00 ', '13:00 ', '14:00 ', '15:00 ', '16:00 ', '17:00 ', '18:00 ', '19:00 ', '20:00 ', '21:00 ', '22:00 ', '23:00 '];
        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        $('#now-datepicker').datetimepicker({
            inline: true,
            sideBySide: true,
            weekStart: 1,
            todayBtn: 1,
            minView: 2,
            forceParse: 0,
        });
        $('#now-time-picker').picker({
            data: data_arr,
            lineHeight: 40,
            selected: '2'
        },
        function(s){
            $(".output").html(s);
            $(".now-time-picker").data("value", s);
        });

        function onDateModal(arg){

            if(arg == 'start'){
                $("#apply-time-btn").attr('onclick', 'changeTime("start")');
            }else{
                $("#apply-time-btn").attr('onclick', 'changeTime("end")');
            }
            $('#datetimemodal').modal('show');
        }
        $('.close').click(function(){
            $('#datetimemodal').modal('hide');
        });

        function getCurrentDate()
        {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            today = yyyy + '-' + mm + '-' + dd;
            return today;
        }
        function changeTime(type)
        {
            var selected_date = $('#now-datepicker').data().date;
            if(selected_date == undefined){
                selected_date = getCurrentDate();
            }else{
                selected_date = $('#now-datepicker').data().date.slice(0, -6);
            }
            var selected_date_arr = selected_date.split("-");
            var day = selected_date_arr[2];
            var month = selected_date_arr[1];
            var month_name = monthNames[month-1];
            var year = selected_date_arr[0];
            var now_date = day + " " + month_name + " " + year;
            var time_index = $(".output").html();
            var compare_result = 1;

            if(type == "start"){
                compare_result = CompareDate("start", selected_date, time_index);
                if(compare_result == 0){
                    //alert("Start time must smaller than end time");
                    $("#alert-string")[0].innerText = "開始時刻は終了時刻よりも早くする必要があります";
                    $("#java-alert").modal('toggle');
                    return;
                }
                $("#start-date-save").val(selected_date);
                $("#start-time-save").val(time_index);
                $("#selected-start-date")[0].innerHTML = now_date+" "+data_arr[time_index];
                $("#checked-start-date").val(selected_date+" "+data_arr[time_index]+":00");
                $("#end-type").val(0);
            }else{
                compare_result = CompareDate("end", selected_date, time_index);
                if(compare_result == 0){
                    //alert("End time must smaller than start time");
                    $("#alert-string")[0].innerText = "終了時刻は開始時刻より遅くする必要があります";
                    $("#java-alert").modal('toggle');
                    return;
                }
                $("#end-date-save").val(selected_date);
                $("#end-time-save").val(time_index);
                $("#selected-end-date")[0].innerHTML = now_date+" "+data_arr[time_index];
                $("#checked-end-date").val(selected_date+" "+data_arr[time_index]+":00");
            }
            $("#datetimemodal").modal('hide');
        }

        function changeDish(obj)
        {
            var price = obj.selectedOptions[0].dataset.price;
            $("#rrp_price")[0].innerText = "価格: ¥ "+ price;
        }

        $("#discount-value").change(function(){
            var discount = $("#discount-value").val();
            var gst = $("#gst").val();
            var gst_include = 0;
            if(discount > 0){
                gst_include = discount*gst / 100;
            }
            $("#gst_value")[0].innerText = '(税額: ¥ '+gst_include+')';
        });

        $("#materialUnchecked").click(function() {
           // $("#checked-start-date").val(1);
            var end_type = $("#end-type").val();
            if(end_type == 3){
                $("#end-type").val(4);
            }else if(end_type == 0){
                $("#end-type").val(2);
            }
            //alert(end_type);
        });

        $("#materialUnchecked2").click(function() {
            // $("#checked-end-date").val(1);
            var end_type = $("#end-type").val();
            if(end_type == 2){
                $("#end-type").val(4);
            }else if(end_type == 0){
                $("#end-type").val(3);
            }
            //alert(end_type);
        });

        $("#materialChecked").click(function() {
            // $("#checked-start-date").val(1);
            var end_type = $("#end-type").val();
            if(end_type == 2){
                $("#end-type").val(0);
            }else if(end_type == 4){
                $("#end-type").val(3);
            }
            //alert(end_type);
        });

        $("#materialChecked3").click(function() {
            // $("#checked-end-date").val(1);
            var end_type = $("#end-type").val();
            if(end_type == 3){
                $("#end-type").val(0);
            }else if(end_type == 4){
                $("#end-type").val(2);
            }
            //alert(end_type);
        });

        function onEndNow()
        {
            $("#end-type").val(1);
        }

        function CompareDate(type, saved_date, time_index) {
            var dateOne = "";
            var dateTwo = "";
            var start_time_index = "";
            var end_time_index = "";
            if(type == "start"){
                dateOne = saved_date;
                dateTwo = $("#end-date-save").val();
                start_time_index = time_index;
                end_time_index = $("#end-time-save").val();
            }else{
                dateOne = $("#start-date-save").val();
                dateTwo = saved_date;
                start_time_index = $("#start-time-save").val();
                end_time_index = time_index;
            }
            if(dateOne != "" && dateTwo != ""){
                if (dateOne > dateTwo) {
                    return 0;
                }
                if(dateOne == dateTwo){
                    if(parseInt(start_time_index) >= parseInt(end_time_index)){
                        return 0;
                    }
                }
            }
            return 1;
        }

        function validateform() {

            var discount_value = $("#discount-value").val();

            if(!discount_value) {
                //alert('Plesae input Discounted Price!');
                $("#alert-string")[0].innerText = "割引価格を入力してください！";
                $("#java-alert").modal('toggle');
                return false;
            }
            return true;
        }
    </script>
@endsection
