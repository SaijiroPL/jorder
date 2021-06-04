@if(!empty($dishes))
@foreach ($dishes as $ds)
    @if($ds->sold_out == 0)
        <div class="card" onclick="orderNow({{$ds->id}})">
            <div class="card-header">
                <img class="cardImg" @if($ds->image) src="{{asset('dishes/'.$ds->image)}}" @endif>
                <div class="headerSpan">
                    <div class="specialBadge">
                        @if($ds->badge_id > 0)
                            <img src="{{asset('badges/'.$ds->badge->filepath)}}" alt="" srcset="" height="38px">
                        @endif
                    </div>
                    <div class="fab">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <p class="text_limit_character dish_description">
                    @if(session('language') == 1)
                        {{$ds->name_cn}}
                    @elseif(session('language') == 2)
                        {{$ds->name_jp}}
                    @else
                        {{$ds->name_en}}
                    @endif
                </p>
                <footer>
                    @if($ds->discount != '')
                        <div class="discountedPrice">
                            ¥{{ round($ds->discount, 0) }}
                        </div>
                    @endif
                    <div @if($ds->discount != '') class="price striked" @else class="price unstriked" @endif>
                        ¥{{ $ds->price }}
                    </div>
                </footer>
            </div>
        </div>
    @else
        <div class="card outStock">
            <div class="card-header">
                <img class="cardImg" @if($ds->image) src="{{asset('dishes/'.$ds->image)}}" @endif>
                <div class="headerSpan">
                    <div class="specialBadge">
                        @if($ds->badge_id > 0)
                            <img src="{{asset('badges/'.$ds->badge->filepath)}}" alt="" srcset="" height="38px">
                        @endif
                    </div>
                    <div class="fab">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <p class="text_limit_character dish_description">
                    @if(session('language') == 1)
                        {{$ds->name_cn}}
                    @elseif(session('language') == 2)
                        {{$ds->name_jp}}
                    @else
                        {{$ds->name_en}}
                    @endif
                </p>
                <footer>
                    @if($ds->discount != '')
                        <div class="discountedPrice">
                            ¥{{ round($ds->discount, 0) }}
                        </div>
                    @endif
                    <div @if($ds->discount != '') class="price striked" @else class="price unstriked" @endif>¥{{ round($ds->price, 0) }}</div>
                </footer>
            </div>
        </div>
    @endif
@endforeach
@endif
