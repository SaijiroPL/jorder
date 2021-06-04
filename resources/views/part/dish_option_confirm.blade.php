<div class="modal-content-wide" style="position: relative;">
    <input type="hidden" id="dish-id" value="{{$dish_id}}">
    <input type="hidden" id="items-id" value="{{$items_id}}">
    <input type="hidden" id="option_price_str">

    {{--<span class="close" onclick="$('#thirdModal').modal('hide');Global_format();">&times;</span>--}}
    <img src="{{asset('img/close.png')}}" style="width:40px;height: 40px;margin-right: 12px;" class="close" onclick="base_page()" />
    <div class="modalHeader" style="padding-left: 12px;">
        <h3>
            @if(session('language') == 1)
                {{$dish->name_cn}}
            @elseif(session('language') == 2)
                {{$dish->name_jp}}
            @else
                {{$dish->name_en}}
            @endif
        </h3>
        <p>
            @if(session('language') == 1)
                {{$dish->desc_cn}}
            @elseif(session('language') == 2)
                {{$dish->desc_jp}}
            @else
                {{$dish->desc_en}}
            @endif
        </p>
        <div class="modalPriceOffer">
            <p>{{ __('customer.BASE') }}</p>

            @if($dish->discount != '')
                <span class="discountedPrice">¥{{ round($dish->price, 0) }}&nbsp;</span>
            @else
                <span class="price">¥{{ round($dish->price, 0) }}&nbsp;</span>
            @endif

            <span>
            @if($slt_items)
                @for($i=0;$i<count($slt_items);$i++)
                    @for($j=0;$j<count($slt_items[$i]['items_id_arr']);$j++)
                        @if($slt_items[$i][$j]['price'] < 0)
                            <span class="price">-</span>
                        @else
                            <span class="price">+</span>
                        @endif
                        <span>
                            {{--@if(session('language') == 1)--}}
                                {{--{{ $slt_items[$i]['display_name_cn'] }}--}}
                            {{--@elseif(session('language') == 2)--}}
                                {{--{{ $slt_items[$i]['display_name_jp'] }}--}}
                            {{--@else--}}
                                {{--{{ $slt_items[$i]['display_name_en'] }}--}}
                            {{--@endif--}}
                            {{ $slt_items[$i][$j]['name'] }}
                        </span>
                        <span class="price">
                            @if($slt_items[$i][$j]['price'] != 0)
                                @if($slt_items[$i][$j]['price'] < 0)
                                    {{ '¥'.round((-1)*$slt_items[$i][$j]['price'], 0) }}
                                @else
                                    {{ '¥'.round($slt_items[$i][$j]['price'], 0) }}
                                @endif
                            @endif
                        </span>
                    @endfor
                @endfor
            @endif

            <span id="option_price"></span>
            </span>

        </div>
    </div>
    <div class="contentHeader" style="margin: 13px 2px 0 12px;">
        次の項目を選択しました:
    </div>
    <div class="modalConfirmContent">
        <div class="dish-content">
            <div class="menuClassesHeader" style="width: 100%;">{{ __('customer.BASE') }}</div>
            <img @if($dish->image) src="{{asset('dishes/'.$dish->image)}}" @endif style="width:269px;height:269px;margin-left:5px;">
            <div class="confirmDishFooter">
                @if(session('language') == 1)
                    {{$dish->name_cn}}
                @elseif(session('language') == 2)
                    {{$dish->name_jp}}
                @else
                    {{$dish->name_en}}
                @endif
            </div>
        </div>
        @if($slt_items)
            @for($i=0;$i<count($slt_items);$i++)
            <div class="dish-content">
                <div class="menuClassesHeader" style="width: 100%;">
                    @if(session('language') == 1)
                        {{ $slt_items[$i]['display_name_cn'] }}
                    @elseif(session('language') == 2)
                        {{ $slt_items[$i]['display_name_jp'] }}
                    @else
                        {{ $slt_items[$i]['display_name_en'] }}
                    @endif
                </div>
                @for($j=0;$j<count($slt_items[$i]['items_id_arr']);$j++)
                    <div class="confirmGrid">
                        <div class="gridContent">
                            <img @if($slt_items[$i][$j]['image']) src="{{asset('options/'.$slt_items[$i][$j]['image'])}}" @endif style="width:90px;height:90px;margin-left:5px;">
                        </div>
                        <div class="gridContent-op">
                            <div class="confirmOption">
                                <p>{{ $slt_items[$i][$j]['name'] }}
                                    <br>
                                    <span class="price">@if($slt_items[$i][$j]['price'] != 0){{ '¥'.round($slt_items[$i][$j]['price'], 0) }}@endif</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            @endfor
        @endif
    </div>
    <div>
        <div class="modalContent noMargin">
            <div>
                <p class="prepareStatus" style="margin-left: 10px;width: 130%;padding-top: 17px;">{{ __('customer.DISH_READY') }}</p>
            </div>
            <div class="padding10">
                <div class="btnGroup" style="margin-left: 76px;">
                    <button id="minus" onclick="plusQty('minus')">
                        <i class="far fa-minus"></i>
                    </button>
                    <span id="numOfItems">
                        01
                    </span>
                    <button id="plus" onclick="plusQty('plus')">
                        <i class="far fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="btn-content">
        <button class="btnBottom-1" onclick="base_page()">&#9664; {{ __('admin.Common.Cancel') }}</button>
        <button class="btnBottom-3" onclick="orderNow_Photo()" >{{ __('customer.ORDER_NOW') }} &#9654;</button>
        <button class="btnBottom-2" onclick="review_previous_page('{{$option_ids}}','{{$count}}')">&#9664; {{ __('admin.Common.Back') }}</button>
    </div>
</div>
