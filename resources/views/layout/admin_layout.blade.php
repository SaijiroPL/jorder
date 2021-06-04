<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">--}}
    {{--<meta name="viewport" content="width=device-width, user-scalable=0">--}}
    <meta name="viewport" content="width=device-width, user-scalable=0, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title')
    </title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
    <link href="{{ asset('css/timepicki.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>

    <link href="{{ asset('css/datepicker.css') }}" rel="stylesheet">
    {{--<link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}

    <script type="text/javascript" src="{{ asset('js/timepicki.js') }}"></script>
</head>

<body>
    <div class=" col-4 pb-5 hh pt-3 menu_style" id="menu-content" style="z-index:1000;height:100vh;position:fixed;overflow: auto;top:0;margin-left:-1200px;background:#242424f0;">
        <a href="#" class="bg-transparent black-text float-right" id="menu-close-btn">
            <img src="{{ asset('img/Group826.png') }}" style="width:40px" />
        </a>
        <div class="text-center">
            <img style="width: 150px; height:150px" src="{{ asset('receipt/'.$profile->logo_image) }}" />
        </div>
        <div class="text-left pr-3 pl-3 mt-4 menu_bar_style">
            <a href="{{ route('reception.seated', ['status' => 'seated']) }}" class="text-left">
                <img style="width:50px" src="{{ asset('img/table.png') }}" />&nbsp;&nbsp;<h4 class="white-text d-inline v-align-middle fs-25" style="font-size:18px">{{ __('menu.Tables') }}</h4>
            </a>
            <br><br>
            <a href="{{ route('admin.transaction') }}" class="text-left">
                <img style="width:50px" src="{{ asset('img/tran.png') }}" />&nbsp;&nbsp;<h4 class="white-text d-inline v-align-middle fs-25" style="font-size:18px">{{ __('menu.Transactions') }}</h4>
            </a>
            <br><br>
            <a href="{{ route('admin.booking') }}" class="text-left">
                <img style="width:50px" src="{{ asset('img/book.png') }}" />&nbsp;&nbsp;<h4 class="white-text d-inline v-align-middle fs-25" style="font-size:18px">{{ __('menu.Bookings') }}</h4>
            </a>
            <br><br>
            <a href="{{ route('loginform') }}" class="text-left">
                <img style="margin-left: 7px;width:50px" src="{{ asset('img/logout.png') }}" />&nbsp;&nbsp;<h4 class="white-text d-inline v-align-middle fs-25" style="font-size:18px">{{ __('menu.Logout') }}</h4>
            </a>
            <br><br>
            <a href="{{ route('admin.check', ['admin_status' => 'edit_menu']) }}" class="text-left">
                <img style="width:50px" src="{{ asset('img/menu.png') }}" />&nbsp;&nbsp;
                <h4 class="white-text d-inline v-align-middle fs-20" style="font-size:18px">{{ __('menu.Edit_Menu') }}
                    @if(!$is_admin)
                    <img style="width:30px" src="{{ asset('img/lock.png') }}" class="float-right pt-2" />
                    @endif
                </h4>
            </a>
            <br><br>
            <a href="{{ route('admin.check', ['admin_status' => 'saledata']) }}" class="text-left mt-4">
                <img style="width:50px" src="{{ asset('img/sales.png') }}" />&nbsp;&nbsp;
                <h4 class="white-text d-inline v-align-middle fs-20" style="font-size:18px">{{ __('menu.Sales_Data') }} &nbsp;&nbsp;
                    @if(!$is_admin)
                    <img style="width:30px" src="{{ asset('img/lock.png') }}" class="float-right pt-1" />
                    @endif
                </h4>
            </a>
            <br><br>
            <a href="{{ route('admin.check', ['admin_status' => 'setting']) }}" class="text-left mt-4">
                &nbsp;&nbsp;<img style="width:50px" src="{{ asset('img/setting.png') }}" />&nbsp;&nbsp;
                <h4 class="white-text d-inline v-align-middle fs-20" style="font-size:18px">{{ __('menu.Setting') }} &nbsp;&nbsp;
                    @if(!$is_admin)
                    <img style="width:30px" src="{{ asset('img/lock.png') }}" class="float-right pt-2" />
                    @endif
                </h4>
            </a>
            <br><br>
            <a href="{{ route('admin.check', ['admin_status' => 'table']) }}" class="text-left mt-4">
                <img style="width:50px" src="{{ asset('img/table.png') }}" />&nbsp;&nbsp;
                <h4 class="white-text d-inline v-align-middle fs-20" style="font-size:18px">{{ __('menu.Table_Edit') }}
                    @if(!$is_admin)
                    <img style="width:30px" src="{{ asset('img/lock.png') }}" class="float-right pt-2" />
                    @endif
                </h4>
            </a>
        </div>
    </div>
    <header class="bg-black fixed-top container-fluid">
        <div class="row pt-0 pb-0" style="height: 75px;">
            <div class="col-1" style="margin-right:25px">
                <a href="#" class="white-text" id="menu-open-btn">
                    {{--<h1 class="mb-0">--}}
                        {{--<span class="fa fa-navicon" style="margin: 11px 0 0 5px;font-size: 52px;"></span>--}}
                    {{--</h1>--}}
                    <img src="{{ asset('img/top_menu.png') }}" style="margin-top: 13px;">
                </a>
            </div>
            <div class="col-7">
                <div class="row pl-0" id="app">
                    <notification-component></notification-component>
                    {{--<ul class="nav navbar pt-0 pb-0 mt-0 mb-0" style="box-shadow:none;">--}}
                        {{--<li class="menu bg-green" onclick="ready_to_pay('{{ $count_notification->ready_pay_count }}')">--}}
                            {{--<img src="{{ asset('img/dollar.png') }}" />--}}
                            {{--<readypaycount-component></readypaycount-component>--}}
                        {{--</li>--}}
                        {{--<li class="menu bg-pinq" onclick="view_calling('{{ $count_notification->calling_count }}')">--}}
                            {{--<img src="{{ asset('img/notify.png') }}" />--}}
                            {{--<callingcount-component></callingcount-component>--}}
                        {{--</li>--}}
                        {{--<li class="menu bg-yellow" onclick="view_review('{{ $count_notification->review_count }}')">--}}
                            {{--<img src="{{ asset('img/chat.png') }}" />--}}
                            {{--<reviewcount-component></reviewcount-component>--}}
                        {{--</li>--}}
                        {{--<li class="menu bg-info" onclick="view_note('{{ $count_notification->note_count }}')">--}}
                            {{--<img src="{{ asset('img/writechat.png') }}" />--}}
                            {{--<notecount-component></notecount-component>--}}
                        {{--</li>--}}
                        {{--<li class="menu bg-green" onclick="ready_to_pay('{{ $count_notification->ready_pay_count }}')"><img src="{{ asset('img/dollar.png') }}" />{{ $count_notification->ready_pay_count }}</li>--}}
                        {{--<li class="menu bg-pinq" onclick="view_calling('{{ $count_notification->calling_count }}')"><img src="{{ asset('img/notify.png') }}" />{{ $count_notification->calling_count }}</li>--}}
                        {{--<li class="menu bg-yellow" onclick="view_review('{{ $count_notification->review_count }}')"><img src="{{ asset('img/chat.png') }}" />{{ $count_notification->review_count }}</li>--}}
                        {{--<li class="menu bg-info" onclick="view_note('{{ $count_notification->note_count }}')"><img src="{{ asset('img/writechat.png') }}" />{{ $count_notification->note_count }}</li>--}}
                    {{--</ul>--}}
                </div>
            </div>
            <div class="col-3 pl-0 text-right" style="margin-top:10px;display:flex">
                <h5 class="mb-0 white-text font-weight-bold mt-3">{{ date('Y') . '年 ' . date('m') . '月 ' . date('d') . '日' }}</h5>
                <h5 class="mb-0 white-text font-weight-bold mt-3" style="margin-left: 10px"><span id="time"></span></h5>
            </div>
            {{-- <div class="col-2 pl-0 text-right" style="margin-top:10px;margin-left: -55px;">

            </div> --}}
        </div>
    </header>

    <div class="modal fade" id="java-alert" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="margin-top: -150px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                    </button>
                </div>
                <div class="modal-body pr-4">
                    <p id="alert-string" class="text-center fs-20"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                        {{ __('admin.Common.Close') }}
                        <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('content')

    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="{{ asset('js/mdb.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-timepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>

    <script type="text/javascript">

        // var admin_status = 0;
        function admin_view(admin_status) {

{{--            $('#edit_menu').location.href="{!! route('getDeleteRequest', $admin_status); !!}";--}}
            $.ajax({
                type:"GET",
                url:"{{ route('admin.check') }}",
                data:{admin_status: admin_status},
                success: function(result){
                    console.log(result);

                }
            });
        }

        function touchHandler(event) {
            var touch = event.changedTouches[0];

            var simulatedEvent = document.createEvent("MouseEvent");
                simulatedEvent.initMouseEvent({
                touchstart: "mousedown",
                touchmove: "mousemove",
                touchend: "mouseup"
            }[event.type], true, true, window, 1,
                touch.screenX, touch.screenY,
                touch.clientX, touch.clientY, false,
                false, false, false, 0, null);

            touch.target.dispatchEvent(simulatedEvent);
            event.preventDefault();
        }

        function init() {
            var sortable_div = document.getElementById('sortable_div');
            if(sortable_div){
                document.getElementById('sortable_div').addEventListener("touchstart", touchHandler, true);
                document.getElementById('sortable_div').addEventListener("touchmove", touchHandler, true);
                document.getElementById('sortable_div').addEventListener("touchend", touchHandler, true);
                document.getElementById('sortable_div').addEventListener("touchcancel", touchHandler, true);
            }
        }
        $(document).ready(function() {
            $('#datetimepicker12').datetimepicker({
                inline: true,
                sideBySide: true,
                language: 'fr',
                weekStart: 1,
                todayBtn: 1,
                minView: 2,
                forceParse: 0,
            });
            $('#datetimepicker13').datetimepicker({
                inline: true,
                sideBySide: true,
                language: 'fr',
                weekStart: 1,
                todayBtn: 1,
                minView: 2,
                forceParse: 0,
            });
            init();
            //var height = Math.max($(window).height() * 1.02, $(document.body).height());
            //$('#menu').height(height);
        });
        var rr = 0;
        $("#menu-open-btn").click(function(){
           if(rr == 0){
               $("#menu-content").animate({"margin-left": '+=1200'});
               rr = 1;
           }
        });
        $("#menu-close-btn").click(function(){
           if(rr == 1){
               $("#menu-content").animate({"margin-left": '-=1200'});
               rr = 0;
           }
        });
        $(document).mouseup(function(e)
        {
            var container = $("#menu-content");
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
                if(rr == 1){
                    $("#menu-content").animate({"margin-left": '-=1200'});
                    rr = 0;
                }
            }
        });

        // display current time ========================
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            var d = new Date();
            document.getElementById("time").innerHTML = d.toLocaleTimeString('ja', {hour: '2-digit', minute:'2-digit'});
        }

        //////////////////////////////////////////

        //ready to pay
        $('#ready_to_pay').click(function(){

            var ready_to_pay = document.getElementById("ready_to_pay").innerText;
            if(ready_to_pay == 0) {
//                alert('There is no pay data.');
                $("#alert-string")[0].innerText = "{{ __('reception.no_pay') }}";
                $("#java-alert").modal('toggle');
            }
            else {
                $('#myModal').html('');
                $.ajax({
                    type:"GET",
                    url:"{{ route('reception.ready_to_pay') }}",
                    data:{},
                    success: function(result){
                        // console.log(result);
                        $('#myModal').html(result);
                    }
                });
                $("#myModal").modal("toggle");
            }
        });
        {{--function ready_to_pay(op) {--}}
            {{--if(op == 0) {--}}
                {{--alert('There is no pay data.');--}}
            {{--}--}}
            {{--else {--}}
                {{--$('#myModal').html('');--}}
                {{--$.ajax({--}}
                    {{--type:"GET",--}}
                    {{--url:"{{ route('reception.ready_to_pay') }}",--}}
                    {{--data:{},--}}
                    {{--success: function(result){--}}
                        {{--// console.log(result);--}}
                        {{--$('#myModal').html(result);--}}
                    {{--}--}}
                {{--});--}}
                {{--$("#myModal").modal("toggle");--}}
            {{--}--}}
        {{--}--}}

        //view calling
        $('#calling_count').click(function(){
            var calling_count = document.getElementById("calling_count").innerText;
            if(calling_count == 0) {
                //alert('There is no calling data.');
                $("#alert-string")[0].innerText = "{{ __('reception.no_call') }}";
                $("#java-alert").modal('toggle');
            }
            else {
                $('#myModal').html('');
                var table_id = 0;
                $.ajax({
                    type:"POST",
                    url:"{{ route('reception.view_calling') }}",
                    data:{ table_id: table_id, _token:"{{ csrf_token() }}"},
                    success: function(result){
                        // console.log(result);
                        $('#myModal').html(result);
                    }
                });
                $("#myModal").modal("toggle");
                // clearInterval(myVar);
            }
        });

        //attend
        function attend(table_id)
        {
            $.ajax({
                type:"POST",
                url:"{{ route('reception.view_calling') }}",
                data:{ table_id: table_id, _token:"{{ csrf_token() }}" },
                success: function(result){
                    // console.log(result);
                    $('#myModal').html(result);
                }
            });
            clearInterval(myVar);
        }

        //view review
        $('#review_count').click(function(){

            var review_count = document.getElementById("review_count").innerText;
            if(review_count == 0) {
//                alert('There is no review data.');
                $("#alert-string")[0].innerText = "{{ __('reception.no_review') }}";
                $("#java-alert").modal('toggle');
            }
            else {
                $('#myModal').html('');
                $.ajax({
                    type:"GET",
                    url:"{{ route('reception.view_review') }}",
                    data:{},
                    success: function(result){
                        // console.log(result);
                        $('#myModal').html(result);
                    }
                });
                $("#myModal").modal("toggle");
            }
        });

        //view note
        $('#note_count').click(function(){

            var note_count = document.getElementById("note_count").innerText;
            if(note_count == 0) {
//                alert('There is no note data.');
                $("#alert-string")[0].innerText = "{{ __('reception.no_comment') }}";
                $("#java-alert").modal('toggle');
            }
            else {
                $('#myModal').html('');
                $.ajax({
                    type:"GET",
                    url:"{{ route('reception.view_note') }}",
                    data:{},
                    success: function(result){
                        // console.log(result);
                        $('#myModal').html(result);
                    }
                });
                $("#myModal").modal("toggle");
            }
        });

        //process bill
        function process_bill(order_id) {

//            $('#myModal').html('');
            $.ajax({
                type:"GET",
                url:"{{ route('reception.accounting') }}",
                data:{order_id: order_id},
                success: function(result){
                    // console.log(result);
                    $('#secondModal').html(result);
                }
            });
//            $('#myModal').modal("hide");
            $('#secondModal').modal("show");
        }

        function btnRemove(id) {
            if (confirm("予約をキャンセルしますか?")) {
                window.location = "{{ route('reception.booking.remove') }}" + `/?id=${id}`
            }
        }

    </script>

    <div id="myModal" class="modal"></div>
    <div id="secondModal" class="modal"></div>

</body>
</html>
