<input type="hidden" name="review_type" id="review_type">
<div class="fd_modal-content">
    <div>
        <div style="vertical-align: top;width: 250px;"><span>今日の私たちのサービスをどのように評価しますか？</span></div>
    </div>
    <div style="margin: -50px 0 25px 280px;">
        {{--@if($feedback->review_type == null)--}}
            {{--<img src="{{asset('img/sad7.png')}}" style="margin-right: 20px;" onclick="review_type(1)">--}}
            {{--<img src="{{asset('img/normal4.png')}}" style="margin-right: 20px;" onclick="review_type(2)">--}}
            {{--<img src="{{asset('img/hap4.png')}}" onclick="review_type(3)">--}}
        {{--@elseif($feedback->review_type == 1)--}}
            {{--<img src="{{asset('img/sad4.png')}}" style="margin-right: 20px;" onclick="review_type(1)">--}}
            {{--<img src="{{asset('img/normal4.png')}}" style="margin-right: 20px;" onclick="review_type(2)">--}}
            {{--<img src="{{asset('img/hap4.png')}}" onclick="review_type(3)">--}}
        {{--@elseif($feedback->review_type == 2)--}}
            {{--<img src="{{asset('img/sad4.png')}}" style="margin-right: 20px;" onclick="review_type(1)">--}}
            {{--<img src="{{asset('img/normal4.png')}}" style="margin-right: 20px;" onclick="review_type(2)">--}}
            {{--<img src="{{asset('img/hap4.png')}}" onclick="review_type(3)">--}}
        {{--@elseif($feedback->review_type == 3)--}}
            {{--<img src="{{asset('img/sad4.png')}}" style="margin-right: 20px;" onclick="review_type(1)">--}}
            {{--<img src="{{asset('img/normal4.png')}}" style="margin-right: 20px;" onclick="review_type(2)">--}}
            {{--<img src="{{asset('img/hap4.png')}}" onclick="review_type(3)">--}}
        {{--@endif--}}
        <img src="{{asset('img/sad7.png')}}" id="review1" style="margin-right: 20px;" onclick="review_type(1)">
        <img src="{{asset('img/normal4.png')}}" id="review2" style="margin-right: 20px;" onclick="review_type(2)">
        <img src="{{asset('img/hap4.png')}}" id="review3" onclick="review_type(3)">
    </div>
    <div>
        {{--<span class="close" style="margin: -95px -10px 0 0;" onclick="$('#myModal').modal('hide');">&times;</span>--}}
        <img src="{{asset('img/close.png')}}" style="width:40px;height: 40px;margin: -95px -9px 0 2px;" class="close" onclick="$('#myModal').modal('toggle')" />
    </div>
    <div>
        <div style="margin-bottom: 10px;">コメントがあれば教えてください</div>
        <div>
            {{--<textarea name="review" id="review" rows="4" style="border:1px solid grey;font-size: 20px;width: 560px; padding: 10px">{{ $feedback->review }}</textarea>--}}
            <textarea name="review" id="review" rows="4" style="border:1px solid grey;font-size: 20px;width: 100%; padding: 10px"></textarea>
        </div>
    </div>
    <div style="margin-top:20px; margin-bottom: 70px;">
        <span onclick="$('#myModal').modal('hide');" class="fd_cancel">{{ __('admin.Common.Cancel') }}</span>
        <span onclick="add_review()" class="fd_manager">マネージャーに送信</span>
    </div>
</div>

<script>
    function review_type(id) {
        var review_type = 1;
        if(id != 1)
            review_type = id;

        document.getElementById('review_type').value = review_type;
        if(review_type == 1) {
            document.getElementById('review1').src = '{{ asset('img/sad7.png') }}';
            document.getElementById('review2').src = '{{ asset('img/normal4.png') }}';
            document.getElementById('review3').src = '{{ asset('img/hap4.png') }}';
        } else if(review_type == 2) {
            document.getElementById('review1').src = '{{ asset('img/sad4.png') }}';
            document.getElementById('review2').src = '{{ asset('img/normal7.png') }}';
            document.getElementById('review3').src = '{{ asset('img/hap4.png') }}';
        } else if(review_type == 3) {
            document.getElementById('review1').src = '{{ asset('img/sad4.png') }}';
            document.getElementById('review2').src = '{{ asset('img/normal4.png') }}';
            document.getElementById('review3').src = '{{ asset('img/hap7.png') }}';
        }
    }
</script>

