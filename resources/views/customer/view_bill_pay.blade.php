<div class="pay_modal-content">
    <div>
        <div style="vertical-align: top;width: 250px;">
            <span>{{ __('customer.TABLE_NUMBER') }}:<br>
                <b>
                    {{ $table_name }}
                </b>
            </span>
        </div>
    </div>
    <div style="margin: -50px 0 25px 245px;">
        <span>{{ __('customer.START_DATE') }}:<br><b>{{date('Y年m月d日 H:i', strtotime($starting_time))}}</b></span>
    </div>
    <div>
        {{--<span class="close" style="margin: -85px -13px 0 0;" onclick="$('#myModal').modal('hide');">&times;</span>--}}
        <img src="{{asset('img/close.png')}}" style="width:40px;height: 40px;margin: -80px -9px 0 2px;" class="close" onclick="$('#myModal').modal('toggle')" />
    </div>
    <div style="margin-bottom: 20px;">
        <div style="padding-right: 15px;">
            <table style="width: 100%;border-bottom: solid 2px black;">
                <tr style="height: 30px; font-size:18px">
                    <td class="head" width="35%">{{ __('reception.ITEM') }}</td>
                    <td class="head" width="10%" align="center">{{ __('reception.QTY') }}</td>
                    <td class="head" width="15%" align="center">{{ __('reception.EACH') }}</td>
                    <td class="head" width="15%" align="center">{{ __('reception.SUB_TOTAL') }}</td>
                    <td class="head" width="15%" align="center">{{ __('reception.STATUS') }}</td>
                    <td class="head" width="10%" align="center"></td>
                </tr>
            </table>
        </div>
        <div style="height: 300px;overflow: scroll;padding-right: 15px;">
            <table style="width: 100%; font-size:16px">
                @foreach($order_dishes as $order_dish)
                <tr>
                    <td width="35%">
                        {{ $order_dish->dish_name }}
                        @foreach($order_dish->options as $option)
                            @if($option->option_name)
                            [{{ $option->option_name }}: {{ $option->item_name }}]
                            @endif
                        @endforeach
                    </td>
                    <td width="10%" align="center">{{ $order_dish->count }}</td>
                    <td width="15%" align="center">
                        @if($order_dish->each_price)
                        ¥{{ $order_dish->each_price }}
                        @endif
                    </td>
                    <td width="15%" align="center">
                        @if($order_dish->sub_total)
                        ¥{{ $order_dish->sub_total }}
                        @endif
                    </td>
                    <td width="15%" align="center">
                        @if($order_dish->deleted_at != null)
                            <span class="span-alert fs-15">{{ __('reception.ITEM_CANCEL') }}</span>
                        @else
                            @if($order_dish->ready_flag == 1)
                                <span class="span-success">{{ __('reception.ITEM_PROVIDED') }}</span>
                            @else
                                <span class="span-warning">{{ __('reception.ITEM_PREPARING') }}</span>
                            @endif
                        @endif
                    </td>
                    <td width="10%" align="center">
                        @if($order_dish->ready_flag == 0 && $order_dish->deleted_at == null)
                            <a href="#" style="font-size:14px" onclick="remove_dish({{ $order_dish->id }})">{{ __('admin.Common.Cancel') }}</a>
                        @endif
                    </td>
                </tr>
                <tr><td height="10px"></td></tr>
                @endforeach
            </table>
        </div>
    </div>
    <div style="margin-left: 500px;">
        <table>
            <tr>
                <td align="left"><b>{{ __('reception.TOTAL') }}:</b></td>
                <td align="left">
                    <b>¥{{ $total }}</b>
                </td>
            </tr>
            <tr>
                <td align="left">{{ __('reception.WITHOUT GST') }}:</td>
                <td align="left">
                    ¥{{ $without_gst_price }}
                </td>
            </tr>
            <tr>
                <td align="left">{{ __('reception.GST') }}:</td>
                <td align="left">
                    ¥{{ $gst_price }}
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-top:20px; margin-bottom: 75px;">
        <span onclick="$('#myModal').modal('hide');" class="pay_return">メニューに戻ります</span>
        <span onclick="pay_finish({{ $order_id }}, {{ $table_name }})" class="pay_finish">完了してお支払</span>
    </div>
</div>



