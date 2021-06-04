<div class="rd_pay_modal">
    <div style="padding: 30px 0 0 30px;">
        <h5><span class="ml-3 fs-30" style="font-weight: 700;">{{ __('reception.modal_title_note') }}</span></h5>
    </div>
    <div><span class="close" style="margin: -65px 15px 0 0;" onclick="note_modal_close()">
            <h2>
                <img src="{{ asset('img/Group1101.png') }}" width="25" height="25" class="float-right mt-3 mr-3" />
            </h2>
        </span></div>
    <div class="col-12">
        <div class="row1" style="overflow-x:hidden;overflow-y:auto;height: 700px;">
            @foreach($order_obj as $kk => $order)
                @foreach($order->table_order_names as $key => $tablename)
                    <div class="border w-100 pt-2 pr-1 table_seated_list" style="height: 280px;">
                        <div class="row w-100 p-0 m-0">
                            <div class="col-lg-2 pr-0 col-xl-2">
                                <div class="row p-0 m-0">
                                    <p class="red-text font-weight-bold ml-4 fs-23" id="time_note_{{$kk}}"></p>
                                </div>
                                <div class="row table_name" style="margin: -12px 0 0 10px;width: 80px;height: 80px;background: darkgrey;color: black;border: 2px solid #9B8D8D;">
                                    <table>
                                        <tr>
                                            @if(count($order->table_display_names) > 1)
                                                <img src="{{asset('img/plus_red.png')}}" class="corner">
                                            @endif
                                            <td style="width: 100px;height: 80px;text-align: center;-ms-word-break: break-all;word-break: break-all;">
                                                <b class="fs-25">
                                                    @if(strlen($tablename) > 9)
                                                        {{ substr($tablename, 0, 9)."..." }}
                                                    @else
                                                        {{ $tablename }}
                                                    @endif
                                                </b>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-2 pr-0 col-xl-2" style="margin-left: -41px;">
                                <div class="row p-0 m-0 ml-3">
                                    @if($order->pay_flag == '1')
                                        <img class="alarm" style="margin-left:-7px;width:2vw;height:2vw;" src="{{ asset('img/calling.png') }}">
                                    @else
                                        <e class="alarm" style="margin-left:-7px;width: 2vw;height: 2vw;"></e>
                                    @endif
                                    @if($order->calling_count > 0)
                                        <img class="alarm" style="width:2vw;height:2vw;" src="{{ asset('img/alarm.png') }}">
                                    @else
                                        <e class="alarm" style="width: 2vw;height: 2vw;"></e>
                                    @endif
                                    @if($order->review != Null)
                                        <img class="alarm" style="width:2vw;height:2vw;" src="{{ asset('img/msg.png') }}">
                                    @else
                                        <e class="alarm" style="width: 2vw;height: 2vw;"></e>
                                    @endif
                                        <img class="alarm" style="width: 2vw;height: 2vw;" src="{{ asset('img/note.png') }}">
                                </div>
                                <div class="row pl-2 pt-3 ml-1">
                                    <p class="pfont mb-0 black-text fs-20" style="word-break: break-all; line-height: 20px;">{{ $order->customer_name }}<br><br>{{ $order->display_time }}</p>
                                </div>
                            </div>
                            <div class="col-1 pr-0 text-right" style="margin-left: 25px;">
                                <div class="row pl-2">
                                    <img src="{{asset('img/head1.png')}}" width="30px" height="30px"><p class="font-weight-bold middle-ver fs-23">{{ $order->guest }}</p>
                                </div>
                            </div>
                            <div class="col-lg-2 pr-0 col-xl-2"></div>
                            <div class="col-lg-1 pr-0 col-xl-1" style="margin: 12px 0 0 25px;">
                                <img style="width: 40px;height: 40px;margin: 20px 0 0 50px;" src="{{ asset('img/chat11.png') }}">
                            </div>
                            <div class="col-lg-3 pr-0 col-xl-3" style="margin: -12px 0 0 25px;">
                                <img style="width: 80px;height: 70px;margin: 18px 0 0 77px;" src="{{ asset('img/note1.png') }}">
                                <br><span style="font-weight: 700;font-size: 20px;margin: 0 0 0 45px;">{{ __('reception.CUSTOMER_NOTE') }}</span>
                            </div>
                        </div>
                        <div style="width: 95%;">
                            <textarea style="border:1px solid grey;height: 130px;margin: 7px 0 0 24px;" class="white pl-2 w-100 pt-1 pb-1 fs-25" name="customer_notes" id="customer-notes">{{$order->note}}</textarea>
                        </div>
                    </div>
                @endforeach
            @endforeach

        </div>
    </div>
</div>

<script>

    var parentURL = window.parent.location.href;

    //modal_close
    function note_modal_close()
    {
        $("#myModal").modal("hide");
        clearInterval(myVar);
        // window.location.replace(parentURL);
    }

    //timer part
    var myVar = setInterval(myTimer, 1000);
    function myTimer() {

        var order_side = <?php echo json_encode($order_obj) ?>;
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
                document.getElementById("time_note_" + i).innerHTML = 'テイクアウト';
            } else if(duration == 1) {
                order_time.setMinutes( order_time.getMinutes() + 30 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time);
                if(elapsed_time > 0) {
                    document.getElementById("time_note_" + i).innerHTML = elapsed_time + '分';
                }
                else {
                    document.getElementById("time_note_" + i).innerHTML = '0分';
                }
            } else if(duration == 2) {
                order_time.setMinutes( order_time.getMinutes() + 60 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time);
                if(elapsed_time > 0) {
                    document.getElementById("time_note_" + i).innerHTML = elapsed_time + '分';
                }
                else {
                    document.getElementById("time_note_" + i).innerHTML = '0分';
                }
            } else if(duration == 3) {
                order_time.setMinutes( order_time.getMinutes() + 90 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time);
                if(elapsed_time > 0) {
                    document.getElementById("time_note_" + i).innerHTML = elapsed_time + '分';
                }
                else {
                    document.getElementById("time_note_" + i).innerHTML = '0分';
                }
            } else if(duration == 4) {
                order_time.setMinutes( order_time.getMinutes() + 120 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time);
                if(elapsed_time > 0) {
                    document.getElementById("time_note_" + i).innerHTML = elapsed_time + '分';
                }
                else {
                    document.getElementById("time_note_" + i).innerHTML = '0分';
                }
            } else if(duration == 5) {
                document.getElementById("time_note_" + i).innerHTML = '無制限';
            }

        }
    }
</script>

