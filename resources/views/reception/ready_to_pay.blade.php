<div class="rd_pay_modal">
    <div style="padding: 30px 0 0 30px;">
        <h5><span class="ml-3 fs-30" style="font-weight: 700;">{{ __('reception.modal_title_pay') }}</span></h5>
    </div>
    <div><span class="close" style="width:25px; height:25px;margin: -65px 15px 0 0;" onclick="$('#myModal').modal('hide');">
            <h2>
                <img src="{{ asset('img/Group1101.png') }}" width="25" height="25" class="float-right mt-3 mr-3" />
            </h2>
        </span></div>
    <div class="table_detail_rd col-10" style="margin: 90px 425px 0 0">
        <div class="row1" style="overflow-x:hidden;overflow-y:auto;height: 700px;">
            <input type="hidden" name="" id="order_side_pay" value="{{ $order_obj }}">
            @foreach($order_obj as $kk => $order)
                @foreach($order->table_order_names as $key => $ordertables)
                    <div class="border w-100 pt-2 pr-1 table_seated_list" style="margin-bottom: 10px;">
                        <div class="row w-100 p-0 m-0" style="height: 125px;">
                            <div class="col-lg-2 pr-0 col-xl-2">
                                <div class="row" style="margin-left: -12px;">
                                    <p class="red-text font-weight-bold ml-3 fs-23" id="time_pay_{{$kk}}">0分</p>
                                </div>
                                <div class="row table_name" style="text-align:center;margin-top: -12px;">
                                    <table>
                                        <tr>
                                            @if(count($order->table_display_names) > 1)
                                                <img src="{{asset('img/plus_red.png')}}" class="corner">
                                            @endif
                                            <td style="width: 100px;height: 80px;background: #000;color:white;text-align: center;-ms-word-break: break-all;word-break: break-all;">
                                                <b class="fs-25">
                                                    @if(strlen($ordertables) > 9)
                                                        {{ substr($ordertables, 0, 9)."..." }}
                                                    @else
                                                        {{ $ordertables }}
                                                    @endif
                                                </b>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-3 pr-0 col-xl-3" style="margin-left: -30px;">
                                <div class="row p-0 m-0 ml-3">
                                        <img class="alarm" style="margin-left:-7px;width:30px;height:27px;" src="{{ asset('img/calling.png') }}">
                                    @if($order->calling_count > 0)
                                        <img class="alarm" style="width:27px;height:29px;" src="{{ asset('img/alarm.png') }}">
                                    @else
                                        <e class="alarm" style="width: 27px;height: 29px;"></e>
                                    @endif
                                    @if($order->review != Null)
                                        <img class="alarm" style="width:30px;height:28px;" src="{{ asset('img/msg.png') }}">
                                    @else
                                        <e class="alarm" style="width: 30px;height:28px;"></e>
                                    @endif
                                    @if($order->note != Null)
                                        <img class="alarm" style="width:35px;height:25px;" src="{{ asset('img/note.png') }}">
                                    @else
                                        <e class="alarm" style="width: 35px;height: 25px;"></e>
                                    @endif
                                </div>
                                <div class="row pl-2 pt-3">
                                    <p class="pfont mb-0 black-text fs-23" style="word-break: break-all; line-height: 20px;">{{ $order->customer_name }}<br><br>{{ $order->display_time }}</p>
                                </div>
                            </div>
                            <div class="col-2 pr-0 text-right" style="margin-left: 25px;">
                                <div class="row pl-2">
                                    <img src="{{asset('img/head1.png')}}" width="30px" height="30px"><p class="font-weight-bold middle-ver fs-23">{{ $order->guest }}</p>
                                </div>
                                <div class="row mt-4 pl-3">
                                    <img src="{{asset('img/chat1.png')}}" style="width:45px;height:45px;margin: -13px 0 0 -7px;">
                                </div>
                            </div>
                            <div class="col-lg-2 pr-0 col-xl-2">
                                {{--<div class="process_bill" onclick="process_bill({{ $order->id }})"><b>PROCESS<br>BILL</b><br><h1>></h1></div>--}}
                                <div class="process_bill">
                                    <a style="color: white;" href="{{ route('reception.accounting', ['order_id' => $order->id]) }}">
                                        <b class="fs-20">{{ __('reception.PROCESS_BILL') }}</b>
                                        <br>
                                        <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 9px;">
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-2 pr-0 col-xl-2">
                                <div class="cancel_bill" onclick="cancel_bill({{ $order->id }})">
                                    <b class="fs-20">{{ __('reception.CANCEL_BILL') }}</b>
                                    <br>
                                    <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 9px;">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</div>

<div id="myModal" class="modal"></div>

<script>

    function cancel_bill(order_id) {

        $.ajax({
            type:"POST",
            url:"{{ route('reception.cancel_bill') }}",
            data:{ order_id: order_id,  _token:"{{ csrf_token() }}" },
            success: function(result){
                // console.log(result);
                $('#myModal').html(result);
            }
        });

        clearInterval(myVar);
    }

    //timer part
    var myVar = setInterval(myTimer, 1000);
    function myTimer() {

        var order_side =  $('#order_side_pay').val();
        order_side = JSON.parse(order_side);
           // console.log(order_side);
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

            if(duration == 0) {
                document.getElementById("time_pay_" + i).innerHTML = 'テイクアウト';
            } else if(duration == 1) {
                order_time.setMinutes( order_time.getMinutes() + 30 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time);
                if(elapsed_time > 0) {
                    document.getElementById("time_pay_" + i).innerHTML = elapsed_time + '分';
                }
                else {
                    document.getElementById("time_pay_" + i).innerHTML = '0分';
                }
            } else if(duration == 2) {
                order_time.setMinutes( order_time.getMinutes() + 60 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time);
                if(elapsed_time > 0) {
                    document.getElementById("time_pay_" + i).innerHTML = elapsed_time + '分';
                }
                else {
                    document.getElementById("time_pay_" + i).innerHTML = '0分';
                }
            } else if(duration == 3) {
                order_time.setMinutes( order_time.getMinutes() + 90 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time);
                if(elapsed_time > 0) {
                    document.getElementById("time_pay_" + i).innerHTML = elapsed_time + '分';
                }
                else {
                    document.getElementById("time_pay_" + i).innerHTML = '0分';
                }
            } else if(duration == 4) {
                order_time.setMinutes( order_time.getMinutes() + 120 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time);
                if(elapsed_time > 0) {
                    document.getElementById("time_pay_" + i).innerHTML = elapsed_time + '分';
                }
                else {
                    document.getElementById("time_pay_" + i).innerHTML = '0分';
                }
            } else if(duration == 5) {
                document.getElementById("time_pay_" + i).innerHTML = '無制限';
            }

        }
    }
</script>
