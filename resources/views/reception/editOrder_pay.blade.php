
<div class="edit_order_calling_img">
    <img src="{{ asset('img/calling1.png') }}" style="width: 65px; margin-top: -10px;">
</div>
<div class="edit_order_process_bill">
    <a style="color: white;" href="{{ route('reception.accounting', ['order_id' => $booking_order->order_id]) }}">
        <span class="fs-25">{{ __('reception.PROCESS_BILL') }}</span>
        <img src="{{ asset('img/Group728white.png') }}" style="height:20px; margin: -8px 0 0 20px;">
    </a>
</div>

<div class="edit_order_cancel_bill">
    <a onclick="cancel_bill({{ $booking_order->order_id }})">
        <span class="fs-25">{{ __('reception.CANCEL_BILL') }}</span>
        <img src="{{ asset('img/Group728white.png') }}" style="height:20px; margin: -8px 0 0 20px;">
    </a>
</div>
<div class="edit_order_space">

</div>
@if($booking_order->status == 'booking')
    <div class="edit_order_calling_img1">
        <img style="width: 50px;" src="{{ asset('img/alarm2.png') }}">
        <div class="edit_order_calling_time fs-25">0 sec</div>
    </div>
    <div class="edit_order_attended">
        <span class="fs-25">ATTENDED</span>
    </div>
@else
    @if($booking_order->attend_time != null)
        <div class="edit_order_calling_img1">
            <img style="width: 50px;" src="{{ asset('img/alarm2.png') }}">
        </div>
        <div class="edit_order_calling_time fs-25">{{ $booking_order->attended_time }} sec</div>
        <div class="edit_order_attended">
            <span class="fs-25">ATTENDED</span>
        </div>
    @else
        @if($booking_order->calling_time != null)
        <div class="edit_order_calling_img1">
            <img style="width: 50px;" src="{{ asset('img/alarm1.png') }}">
        </div>
        <div class="edit_order_calling_time fs-25" id="calling_time"></div>
        <div class="edit_order_attending" onclick="attend_book({{ $booking_order->order_id }})">
            <span class="fs-25">ATTENDING</span>
            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
        </div>
        @endif
    @endif
@endif
