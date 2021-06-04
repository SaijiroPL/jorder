<div class="lg_modal-content">
    <div class="lang_sel_header">
        <p class="lang">Please choose your language:</p>
        <img src="{{asset('img/close.png')}}" style="width:40px;height: 40px;margin: -77px -81px 0 0;" class="close" onclick="$('#myModal').modal('toggle')" />
    </div>
    <div class="lang_sel_content">
        @for($i=0;$i< count($lang_data);$i++)
            <label class="container lg_con_align">{{ $lang_data[$i] }}
                @if($i == $lang_id)
                    <input type="radio" checked="checked" name="radio" value="{{ $lang_data[$i] }}" id="{{$i}}">
                @else
                    <input type="radio" name="radio" value="{{ $lang_data[$i] }}" id="{{$i}}">
                @endif
                <span class="checkmark lg_chk"></span>
            </label>
        @endfor
    </div>
    {{--<div>--}}
        {{--<span class="close lg_close" style="margin: -260px -10px 0 0;" onclick="$('#myModal').modal('hide');">&times;</span>--}}
        {{--<img src="{{asset('img/close.png')}}" style="width:40px;height: 40px;margin-right: 12px;" class="close" onclick="$('#myModal').modal('toggle')" />--}}
    {{--</div>--}}
    <div class="lang_sel_footer">
        <div onclick="lang_sel()"><span>
            {{ __('admin.Common.Apply') }}
        </span></div>
    </div>
</div>

<script>
    function lang_sel() {
        var checked_lang = '';
        $("input:radio").each(function(){
            var name = $(this).attr("name");
            if($("input:radio[name=radio]:checked").length > 0) {
                checked_lang = $("input:radio[name=radio]:checked").attr('id');
            }
        });

        $.ajax({
            type:"POST",
            url:"{{ route('customer.put_lang') }}",
            data:{ checked_lang: checked_lang, _token:"{{ csrf_token() }}" },
            success: function(result){
                console.log(result);
                $('#myModal').modal('hide');
                location.href = window.location.href;
            }
        });


    }
</script>

