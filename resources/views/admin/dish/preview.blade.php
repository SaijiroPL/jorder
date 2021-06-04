@extends('layout.admin_layout')

@section('title', 'DISH')

@section('content')
<div class="blackgrey pb-5" style="min-height: 900px;">
<div style="padding-top:115px;">
</div>
    <div class="widthh bg-lightgrey pl-5 pb pt-3">
        <div class="row">
            <div class="col-6 preview-title">
                <label class="fs-30">プレビュー</label>
            </div>
            <div class="col-6">
                <a href="{{route('admin.dish')}}">
                    <span class="">
                        <img src="{{ asset('img/Group1101.png') }}" width="25" height="25" class="float-right" width="20"/>
                    </span>
                </a>
            </div>
        </div>
        <div class="row" style="width: 100%;">
            <div class="col-10 bg-white mb-5 dish-detail-content">
                <div class="row">
                    <div class="col-11 dish-title">
                        <h4 class="font-weight-bold mb-0 fs-20">{{ $obj->name_en }}</h4>
                        <h5 class=" mb-0 fs-20">{{ $obj->desc_en }}</h5>
                        <div class="modalPriceOffer" style="display:inline-flex;">
                            @if($obj->discount)
                                <div class="discountedPrice fs-20">
                                    ¥{{ $obj->discount['discount'] }}
                                </div>
                            @endif
                            <div @if($obj->discount) class="price striked fs-20" @else class="price unstriked fs-20" @endif>¥{{ $obj->price }}</div>
                        </div>
                    </div>
                    <div class="col-1">
                        {{--<a href="{{route('admin.dish')}}" class="fa fa-s text-white float-right close_times mt-3">--}}
                        <a class="fa fa-s text-white float-right mt-3">
                            <img src="{{ asset('img/close1.png') }}" width="40" height="40" />
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div style="position:relative">
                        <img @if($obj->image) src="{{ asset('dishes/'.$obj->image) }}" @endif class="img-fluid w-100" style="height: 480px;margin: 0px 12px 0 12px;" />
                        @if($obj->badge)
                        <img src="{{ asset('badges/'.$obj->badge->filepath) }}" style="position: absolute;top:15px;left:29px;width:110px;height:40px;">
                        @endif
                        </div>
                        <p class="text-center text-movee font-weight-bold dish-adv fs-20" style="margin-left:15px;">{{ __('customer.DISH_READY') }}</p>
                    </div>
                    <div class="col-6" style="padding-right: 27px;padding-left: 20px;">
                        <p class="text-white d-block bg-movee pl-1 pt-1 pb-1 font-weight-bold fs-20">選んでください:</p>
                        <div style="height:34vh; overflow:auto">
                            @foreach ($obj->options as $op)
                                @if($op->photo_visible != "1")
                                    <h5 class="font-weight-bold d-block border-bottom fs-20"><b>{{ $op->display_name_en }}</b></h5>
                                <div class="ml-4 pl-2">
                                    @foreach ($op->items as $it)
                                        <div class="form-check mb-2">
                                            <input type="radio" class="fform-check-input rdobtn" id="materialUnchecked{{ $it->id }}" name="option[{{ $op->id }}]">
                                            <label class="form-check-label  txtdemibold  fs-20" for="materialUnchecked{{ $it->id }}" style="margin: -5px 0 0 10px;">
                                                {{ $it->name }}
                                                @if($it->price > 0)
                                                    <span style="color:#9A9828">(+{{ $it->price }})</span>
                                                @elseif($it->price < 0)
                                                    <span style="color:#C74E95">({{ $it->price }})</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <div style="text-align: center">
                            <i class="fa fa-minus-circle fa-3x text-movee mt"></i>
                            <label class="font-weight-normal fs-40">01</label>
                            <i class="fa fa-plus-circle fa-3x text-movee"></i>
                        </div>
                        <button class="border-0 bg-movee text-center text-white pt-2 pb-2 mt w-100 borderadious mb-3 fs-30">今すぐ注文 </button>
                        </div>
                </div>

            </div>
            <div class="col-2">
                <form action="{{ route('admin.dish.previewpost') }}" method="POST">
                <input type="hidden" name="id" id="obj_id" value="{{ $obj->id }}">
                <div style="position:absolute;bottom:6%;margin: 0px 0px -11px 15px;">
                    <div>
                        <p class="txtdemibold fs-20">完売</p>
                        <label class="bs-switch ml-100">
                            <input type="checkbox" name="sold_out" id="sold_out" onchange="change_sold_active()"
                            @if($obj->sold_out == 1)
                                checked
                            @endif
                            >
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div>
                        <p class="txtdemibold fs-20">アクティブ</p>
                        <label class="bs-switch ml-100">
                            <input type="checkbox" name="active" id="active" onchange="change_sold_active()"
                            @if($obj->active == 1)
                                checked
                            @endif
                            >
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <button class="editbttnn align-bottom fs-20">
                        {{ __('admin.Common.Edit') }}
                        <img src="{{ asset('img/Group728.png') }}" height="15" class="mb-1" />
                    </button>
                </div>
                @csrf
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

<script>
    function change_sold_active() {

        var id = $('#obj_id').val();

        if (document.getElementById("sold_out").checked == true) {
            var sold_out = 'true';
        }
        if (document.getElementById("active").checked == true) {
            var active = 'true';
        }
        // alert(id);
        $.ajax({
            type:"post",
            url:"{{ route('admin.change_sold_active') }}",
            data:{
                id: id, sold_out: sold_out, active: active,
                _token:"{{ csrf_token() }}"
            },
            success: function(result){
                //
            }
        });
    }
</script>


