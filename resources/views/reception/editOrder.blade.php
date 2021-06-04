@extends('layout.admin_layout')
@section('title', session('role'))
@section('content')

    <div class="p-4 mt-5">
        <div class="container-fluid pb-3 position-relative edit_order_pane">

            <div class="edit_order" style="height: 100%;margin-top: 25px;">
                <div class="accounting_header ml-3">
                    <span class="close" style="margin: 0 20px 0 0;" onclick="window.location='{{ route("reception.seated", [ 'status' => $booking_order->status ]) }}'">
                        <h2><span class="">
                             <img src="{{ asset('img/Group1100.png') }}" width="30" height="30" class="float-right mt-3 mr-3" />
                        </span></h2>

                    </span>
                    <div class="col-lg-11 pr-0 col-xl-11" style="padding: 20px 0 0 25px;">
                        <div>
                            <h5><span class="fs-20" style="font-weight: 700;">{{ __('reception.NAME') }}: </span><span class="fs-20">{{ $booking_order->customer_name }}</span></h5>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <h5>
                                <span class="fs-20" style="font-weight: 700;margin-right: 5px;">{{ __('reception.DATE') }}: </span>
                                <span class="fs-20" style="margin-right: 30px;">{{ date('Y年m月d日', strtotime($booking_order->date)) }}</span>
                                <span class="fs-20" style="font-weight: 700;margin-right: 5px;">{{ __('reception.TIME') }}: </span>
                                <span class="fs-20" style="margin-right: 30px;">{{ $booking_order->time }}</span>
                                <span class="fs-20" style="font-weight: 700;margin-right: 5px;">{{ __('reception.DURATION') }}: </span>
                                <span class="fs-20">{{ $booking_order->duration_time }}</span>
                                <span class="fs-20" id="during_time"></span>
                                @if($booking_order->duration > 0 && $booking_order->duration < 5)
                                    <span onclick="set_remain({{ $booking_order->order_id }})" style="color: white; background: black; padding: 10px 20px; font-size: 0.8em; margin-left: 1em">
                                        時間延長
                                    </span>
                                @endif
                            </h5>
                        </div>
                        <div>
                            <table>
                                <tr>
                                    <td width="50px"><img src="{{asset('img/head1.png')}}" width="50" height="55"></td>
                                    <td width="80px" align="left"><span style="font-size: 35px;"><b>{{ $booking_order->guest }}</b></span></td>
                                    @foreach($booking_order->table_name as $tb_nm)
                                        @if($booking_order->table_name[0] != $tb_nm)
                                            <td width="50px" align="center"><img src="{{asset('img/plus_red.png')}}"></td>
                                        @endif
                                        <td style="width: 80px;height: 80px;background: #000;color:white;text-align: center;font-size: 15px;-ms-word-break: break-all;word-break: break-all;">
                                            <b class="fs-20">
                                                @if(strlen($tb_nm) > 9)
                                                    {{ substr($tb_nm, 0, 9)."..." }}
                                                @else
                                                    {{ $tb_nm }}
                                                @endif
                                            </b>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="accounting_content ml-3">

                    <div style="margin-bottom: 10px;">
                        <img style="width: 70px;height: 60px;margin: 0 0 0 25px;" src="{{ asset('img/note1.png') }}">
                        <span class="fs-20" style="font-weight: 700;">{{ __('reception.CUSTOMER_NOTE') }}</span>
                        {{--<span onclick="edit({{ $booking_order->order_id }})" class="edit_order_edit_btn">--}}
                            <span onclick="window.location='{{ route("reception.addCustomer", [ "table_id" => $booking_order->table_id, "order_id" => $booking_order->order_id, "status" => $status, "order_side_obj" => $order_side_obj ]) }}'" class="edit_order_edit_btn">
                            <b class="fs-20">{{ __('admin.Common.Edit') }}</b>
                            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -8px 0 0 20px;">
                        </span>
                    </div>
                    <div style="width: 85vw;margin-bottom: 20px;">
                        <textarea style="border:1px solid grey;height: 10vh;margin: 0 0 0 25px;" class="white pl-2 w-100 pt-1 pb-1 fs-20"
                                  name="customer_notes" id="customer-notes">{{$booking_order->note}}</textarea>
                    </div>

                    <div class="col-lg-11 pr-0 col-xl-11">
                        <img style="width: 70px;height: 60px;margin: 0 0 0 7px;" src="{{ asset('img/review.png') }}">
                        <span class="fs-20" style="font-weight: 700;margin-right: 10px;">{{ __('reception.REVIEW') }}</span>
                        <span>
                            @if($booking_order->review_type == 1)
                                <img style="width: 70px;height: 70px;margin-right: 10px;" src="{{ asset('img/sad6.png') }}" id="review1" onclick="review_type(1)">
                            @else
                                <img style="width: 70px;height: 70px;margin-right: 10px;" src="{{ asset('img/sad5.png') }}" id="review1" onclick="review_type(1)">
                            @endif
                            @if($booking_order->review_type == 2)
                                <img style="width: 70px;height: 70px;margin-right: 10px;" src="{{ asset('img/normal6.png') }}" id="review2" onclick="review_type(2)">
                            @else
                                <img style="width: 70px;height: 70px;margin-right: 10px;" src="{{ asset('img/normal5.png') }}" id="review2" onclick="review_type(2)">
                            @endif
                            @if($booking_order->review_type == 3)
                                <img style="width: 70px;height: 70px;" src="{{ asset('img/hap6.png') }}" id="review3" onclick="review_type(3)">
                            @else
                                <img style="width: 70px;height: 70px;" src="{{ asset('img/hap5.png') }}" id="review3" onclick="review_type(3)">
                            @endif
                        </span>
                    </div>
                    <div style="width: 85vw;margin-bottom: 10px;">
                        <textarea style="border:1px solid grey;height: 10vh;margin: 7px 0 15px 25px;" class="white pl-2 w-100 pt-1 pb-1 fs-20"
                                  name="customer_notes" id="customer-review">{{$booking_order->review}}</textarea>
                    </div>

                    <div id="pay_state" style="margin-bottom: 40px; margin-left: 25px; marign-right: 25px;">
                        @if($booking_order->pay_flag == 0)
                            <div class="edit_order_edit_order" onclick="edit_order({{ $booking_order->order_id }})">
                                <span class="fs-20">{{ __('reception.EDIT_ORDER') }}</span>
                                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -8px 0 0 20px;">
                            </div>
                            {{--<div class="edit_order_edit_order" onclick="finish_pay('{{$tb_nm}}', '{{ $starting_time }}', '{{ $total }}', '{{ $without_gst_price }}', '{{ $gst_price }}')">--}}
                            <div class="edit_order_edit_order" onclick="finish_pay({{ $booking_order->order_id }})">
                                <span class="fs-20">{{ __('reception.ISSUE_A_BILL') }}</span>
                                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -8px 0 0 20px;">
                            </div>
                            <div class="edit_order_space" style="padding: 12px 221px 12px 0;">

                            </div>
                            {{-- @if($booking_order->status == 'booking')
                                <div class="edit_order_calling_img1">
                                    <img style="width: 50px;" src="{{ asset('img/alarm2.png') }}">
                                    <div class="edit_order_calling_time fs-20">0 sec</div>
                                </div>
                                <div class="edit_order_attended">
                                    <span class="fs-20">ATTENDED</span>
                                </div>
                            @else
                                @if($booking_order->attend_time != null)
                                    <div class="edit_order_calling_img1">
                                        <img style="width: 50px;" src="{{ asset('img/alarm2.png') }}">
                                    </div>
                                    <div class="edit_order_calling_time fs-20">{{ $booking_order->attended_time }} sec</div>
                                    <div class="edit_order_attended">
                                        <span class="fs-20">ATTENDED</span>
                                    </div>
                                @else
                                    @if($booking_order->calling_time != null)
                                        <div class="edit_order_calling_img1">
                                            <img style="width: 50px;" src="{{ asset('img/alarm1.png') }}">
                                        </div>
                                        <div class="edit_order_calling_time fs-20" id="calling_time"></div>
                                        <div class="edit_order_attending" onclick="attend_book({{ $booking_order->order_id }})">
                                            <span class="fs-20">ATTENDING</span>
                                            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -8px 0 0 20px;">
                                        </div>
                                    @endif
                                @endif
                            @endif --}}
                        @elseif($booking_order->pay_flag == 1)
                            @include('reception.editOrder_pay')
                            {{--<div class="edit_order_calling_img">
                                <img src="{{ asset('img/calling1.png') }}" style="width: 65px; margin-top: -10px;">
                            </div>
                            <div class="edit_order_process_bill">
                                <a style="color: white;" href="{{ route('reception.accounting', ['order_id' => $booking_order->order_id]) }}">
                                    <span class="fs-20">PROCESS BILL</span>
                                    <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
                                </a>
                            </div>

                            <div class="edit_order_cancel_bill">
                                <a onclick="cancel_bill({{ $booking_order->order_id }})">
                                    <span class="fs-20">CANCEL BILL</span>
                                    <img src="{{ asset('img/Group728white.png') }}" style="height:20px; margin: -8px 0 0 20px;">
                                </a>
                            </div>
                            <div class="edit_order_space">

                            </div>
                            @if($booking_order->status == 'booking')
                                <div class="edit_order_calling_img1">
                                    <img style="width: 50px;" src="{{ asset('img/alarm2.png') }}">
                                    <div class="edit_order_calling_time fs-20">0 sec</div>
                                </div>
                                <div class="edit_order_attended">
                                    <span class="fs-20">ATTENDED</span>
                                </div>
                            @else
                                @if($booking_order->attend_time != null)
                                    <div class="edit_order_calling_img1">
                                        <img style="width: 50px;" src="{{ asset('img/alarm2.png') }}">
                                    </div>
                                    <div class="edit_order_calling_time fs-20">{{ $booking_order->attended_time }} sec</div>
                                    <div class="edit_order_attended">
                                        <span class="fs-20">ATTENDED</span>
                                    </div>
                                @else
                                    @if($booking_order->calling_time != null)
                                    <div class="edit_order_calling_img1">
                                        <img style="width: 50px;" src="{{ asset('img/alarm1.png') }}">
                                    </div>
                                    <div class="edit_order_calling_time fs-20" id="calling_time"></div>
                                    <div class="edit_order_attending" onclick="attend_book({{ $booking_order->order_id }})">
                                        <span class="fs-20">ATTENDING</span>
                                        <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
                                    </div>
                                    @endif
                                @endif
                            @endif--}}
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    <input type="hidden" name="review_type" id="review_type"  value="{{ $booking_order->review_type }}">
    <script>

        function edit(order_id)
        {
            var note = $('#customer-notes').val();
            var review_type = $('#review_type').val();
            var review = $('#customer-review').val();
            if(review) {//there is review
                if(!review_type){
                    //alert('Please select review type!');
                    $("#alert-string")[0].innerText = "レビュータイプを選択してください！";
                    $("#java-alert").modal('toggle');
                }
                else {
                    $.ajax({
                        type:"POST",
                        url:"{{ route('reception.edit_note_review') }}",
                        data:{
                            order_id: order_id, note: note, review_type: review_type, review: review,  _token:"{{ csrf_token() }}"
                        },
                        success: function(result){
                            // console.log(result);
                        }
                    });
                }
            } else{//there is no review
                $.ajax({
                    type:"POST",
                    url:"{{ route('reception.edit_note_review') }}",
                    data:{
                        order_id: order_id, note: note, review_type: review_type, review: review,  _token:"{{ csrf_token() }}"
                    },
                    success: function(result){
                        // console.log(result);
                    }
                });
            }

            {{--if(!review_type){--}}
                {{--alert('Please select review type!');--}}
            {{--} else {--}}
                {{--$.ajax({--}}
                    {{--type:"POST",--}}
                    {{--url:"{{ route('reception.edit_note_review') }}",--}}
                    {{--data:{--}}
                        {{--order_id: order_id, note: note, review_type: review_type, review: review,  _token:"{{ csrf_token() }}"--}}
                    {{--},--}}
                    {{--success: function(result){--}}
                        {{--// console.log(result);--}}
                    {{--}--}}
                {{--});--}}
            {{--}--}}
        }

        function review_type(id)
        {
            // var review_type = 1;
            // if(id != 1)
            var review_type = id;

            document.getElementById('review_type').value = review_type;
            if(review_type == 1) {
                document.getElementById('review1').src = '{{ asset('img/sad6.png') }}';
                document.getElementById('review2').src = '{{ asset('img/normal5.png') }}';
                document.getElementById('review3').src = '{{ asset('img/hap5.png') }}';
            } else if(review_type == 2) {
                document.getElementById('review1').src = '{{ asset('img/sad5.png') }}';
                document.getElementById('review2').src = '{{ asset('img/normal6.png') }}';
                document.getElementById('review3').src = '{{ asset('img/hap5.png') }}';
            } else if(review_type == 3) {
                document.getElementById('review1').src = '{{ asset('img/sad5.png') }}';
                document.getElementById('review2').src = '{{ asset('img/normal5.png') }}';
                document.getElementById('review3').src = '{{ asset('img/hap6.png') }}';
            }
        }

        function edit_order(order_id)
        {
            // alert(order_id);
            $.ajax({
                type:"GET",
                url:"{{ route('reception.editOrder1') }}",
                data:{
                    order_id: order_id
                },
                success: function(result){
                    // console.log(result);
                    $('#myModal').html(result);
                }
            });
            $('#myModal').modal("toggle");
        }

        function attend_book(order_id)
        {
            $.ajax({
                type:"POST",
                url:"{{ route('reception.attend_book') }}",
                data:{ order_id: order_id,  _token:"{{ csrf_token() }}" },
                success: function(result){
                    // console.log(result);
                    location.href = window.location.href;
                }
            });
            clearInterval(myVar);
        }

        function cancel_bill(order_id) {

            $.ajax({
                type:"POST",
                url:"{{ route('reception.cancel_bill') }}",
                data:{ order_id: order_id,  _token:"{{ csrf_token() }}" },
                success: function(result){
                    // console.log(result);
                    location.href = window.location.href;
                }
            });
        }

        function set_remain(order_id) {
            const aNumber = Number(window.prompt("時間入力（分）", ""));
            $.ajax({
                type:"POST",
                url:"{{ route('reception.update.or') }}",
                data:{ order_id: order_id, additional_min: aNumber, _token:"{{ csrf_token() }}" },
                success: function(result){
                    location.href = window.location.href;
                }
            });
        }

        //timer part
        var myVar = setInterval(myTimer, 1000);
        function myTimer() {

            var duration = <?php echo json_encode($booking_order->duration) ?>;
            var order_time = <?php echo json_encode($booking_order->starting_time) ?>;
            var dateParts = order_time.substr(0,10).split('-');
            var timePart = order_time.substr(11);
            var additional = Number("<?php echo json_encode($booking_order->additional) ?>");
            order_time = dateParts[1] + '/' + dateParts[2] + '/' + dateParts[0] + ' ' + timePart;
            order_time = new Date(order_time);

            // console.log(order_time);
            var current_time =  new Date();
            var elapsed_time = '';

            if(duration == 0) {
                document.getElementById("during_time").innerHTML = '(テイクアウト)';
            } else if(duration == 1) {
                order_time.setMinutes( order_time.getMinutes() + 30 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time) + additional;
                if(elapsed_time > 0) {
                    document.getElementById("during_time").innerHTML = '(' + elapsed_time + '分)';
                }
                else {
                    document.getElementById("during_time").innerHTML = '(0分)';
                }
            } else if(duration == '2') {
                order_time.setMinutes( order_time.getMinutes() + 60 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time) + additional;
                if(elapsed_time > 0) {
                    document.getElementById("during_time").innerHTML = '(' + elapsed_time + '分)';
                }
                else {
                    document.getElementById("during_time").innerHTML = '(0分)';
                }
            } else if(duration == 3) {
                order_time.setMinutes( order_time.getMinutes() + 90 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time) + additional;
                if(elapsed_time > 0) {
                    document.getElementById("during_time").innerHTML = '(' + elapsed_time + '分)';
                }
                else {
                    document.getElementById("during_time").innerHTML = '(0分)';
                }
            } else if(duration == 4) {
                order_time.setMinutes( order_time.getMinutes() + 120 );
                elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
                elapsed_time /= 60;
                elapsed_time = Math.round(elapsed_time) + additional;
                if(elapsed_time > 0) {
                    document.getElementById("during_time").innerHTML = '(' + elapsed_time + '分)';
                }
                else {
                    document.getElementById("during_time").innerHTML = '(0分)';
                }
            } else if(duration == 5) {
                document.getElementById("during_time").innerHTML = '(無制限)';
            }

            var calling_time = <?php echo json_encode($booking_order->calling_time) ?>;
            var attend_time = <?php echo json_encode($booking_order->attend_time) ?>;
            if(calling_time != null && attend_time == null) {

                calling_time = new Date(calling_time);
                // console.log(calling_time);

                elapsed_time = (current_time.getTime() - calling_time.getTime())/1000;
                elapsed_time = Math.round(elapsed_time);
                if(elapsed_time > 0) {
                    document.getElementById("calling_time").innerHTML = elapsed_time + ' sec';
                }
            }

        }

        function finish_pay(order_id) {
            $.ajax({
                type:"POST",
                url:"{{ route('reception.finish_pay') }}",
                data:{
                    order_id: order_id,
                    _token:"{{ csrf_token() }}"
                },
                success: function(result){
                    if (result.error) {
                        $("#alert-string")[0].innerText = result.message;
                        $("#java-alert").modal('toggle');
                    } else {
                        document.getElementById("pay_state").innerHTML = result;
                    }
                }
            });
        }
    </script>

    <div id="myModal" class="modal"></div>

@endsection
