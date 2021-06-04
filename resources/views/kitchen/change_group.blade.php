{{--change group--}}
<input type="hidden" id="group_id" value="{{ $group_id }}">
<div class="modal-dialog">
    <!-- Modal content-->
    <div class="ch_gr_modal_content">
        <div class="ch_gr_modal_header">
            <p class="ch_hd_tx">{{ __('kitchen.CHANGE_GROUP') }}</p>
            <p class="ch_gr_right_close" data-dismiss="modal"><img src="{{ asset('img/close.png') }}" style="width: 45px;height: 45px;"></p>
        </div>
        <div class="ch_gr_modal_body">
            <div class="ch_gr_modal_body_content">
                <label class="radio_container">すべて
                    @if($group_id == 0)
                        <input type="radio" checked="checked" name="radio" class="radio" value="0">
                    @else
                        <input type="radio" name="radio" class="radio" value="0">
                    @endif
                    <span class="checkmark"></span>
                </label>
                @foreach($group_data as $gr_data)
                    <label class="radio_container">{{ $gr_data->name }}
                    @if($gr_data->id == $group_id)
                        <input type="radio" checked="checked" name="radio" class="radio" value="{{ $gr_data->id }}">
                    @else
                        <input type="radio" name="radio" class="radio" value="{{ $gr_data->id }}">
                    @endif
                    <span class="checkmark"></span>
                    </label>
                @endforeach
            </div>
        </div>
        <div>
            <button class="ch_gr_return" onclick="return_previous_screen()">{{ __('admin.Common.Update') }}</button>
        </div>
    </div>

</div>

