@extends('layout.admin_layout')

@section('title', 'DISH')

@section('content')
<input type="hidden" name="check_discount_list" id="check_discount_list" value="{{ $check_discount_list }}">
<div style="padding-top:8%;">
</div>
<div class="widthh blackgrey pt-4 discount-content" style="height: 90vh;">
    <div class="row">
        <div class="col-6">
            <label class="text-white font-weight-bold fs-20">{{ __('admin.Bottom.DISCOUNT') }}</label>
        </div>
        <div class="col-6">
            <a onclick="window.history.back()">
                <span class="">
                    <img src="{{ asset('img/Group826.png') }}" width="25" height="25" class="float-right" />
                </span>
            </a>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-12">
            <table style="width: 99%;color: white;margin-top: 20px;border-bottom: 1px solid white;">
                <thead>
                    <tr>
                        <th class="border-0 fs-3 pd" scope="col" width="12%">
                            <a href="{{route("admin.discount.sort", ["sortField" => "start", 'start_sort' => $start_sort, "end_sort" => $end_sort])}}" class="text-white fs-20">
                                <b>{{ __('admin.Discount.START') }}</b>
                                <img
                                        @if($start_sort == "asc")
                                        src="{{ asset('img/Path445.png') }}"
                                        @else
                                        src="{{ asset('img/Path444.png') }}"
                                        @endif
                                        height="20" style="margin: -1px 0 0 5px;" />
                            </a>
                        </th>
                        <th class="border-0 fs-3 pd" scope="col" width="12%">
                            <a href="{{route("admin.discount.sort", ["sortField" => "end", "end_sort" => $end_sort, 'start_sort' => $start_sort])}}" class="text-white fs-20">
                                <b>{{ __('admin.Discount.END') }}</b>
                                <img
                                        @if($end_sort == "asc")
                                        src="{{ asset('img/Path445.png') }}"
                                        @else
                                        src="{{ asset('img/Path444.png') }}"
                                        @endif
                                        height="20" style="margin: -1px 0 0 5px;" />
                            </a>
                        </th>
                        <th class="border-0 text-left pd" scope="col" width="25%"><b class="fs-20">{{ __('admin.Bottom.DISH') }}</b></th>
                        <th class="border-0 text-left pd" scope="col" width="10%"><b class="fs-20">RRP</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="border-0 text-left pd" scope="col" width="12%"><b class="fs-20">{{ __('admin.Bottom.DISCOUNT') }}</b></th>
                        <th class="border-0 text-left pd" scope="col" colspan="4"><b class="fs-20">{{ __('admin.Discount.TIME_SLOTS') }}</b></th>
                    </tr>
                    <tr>
                        <td class="border-0 pd"></td>
                        <td class="border-0 pd"></td>
                        <td class="border-0 pd"></td>
                        <td class="border-0 pd"></td>
                        <td class="border-0 pd fs-15">{{ __('admin.Dish.PRICE') }}</td>
                        <td class="border-0 pd fs-15" width="8%">{{ __('admin.Dish.Breakfast') }}</td>
                        <td class="border-0 pd fs-15" width="7%">{{ __('admin.Dish.Lunch') }}</td>
                        <td class="border-0 pd fs-15" width="7%">{{ __('admin.Dish.Tea') }}</td>
                        <td class="border-0 pd fs-15" width="7%">{{ __('admin.Dish.Dinner') }}</td>
                    </tr>
                </thead>
            </table>
            <div style="height: 56.5vh;overflow-y: auto;">
            <table class="table text-white txtdemibold" style="width: 99%;">
                <tbody>
                @foreach($discounts as $discount)
                    @if($discount->dish)
                    <tr onclick="onrow(this)" @if($discount->end_type == 1) class="text-discount bg-lightgrey" @endif  data-url="{{route("admin.discount.edit", ["id" => $discount->id])}}">
                        <td width="12%" style="padding-left: 0;"><span class="fs-20">{{($discount->start != "") ? date("d F Y", strtotime($discount->start)) : ""}}</span></td>
                        <td width="12%" style="padding-left: 3px;"><span class="fs-20">{{($discount->end != "") ? date("d F Y", strtotime($discount->end)) : ""}}</span></td>
                        <td width="25%" style="padding-left: 7px;"><span class="fs-20">{{$discount->dish->name_en}}</span></td>
                        <td width="10%" style="padding-left: 8px;"><span class="fs-20">{{"¥ ".$discount->dish->price}}</span></td>
                        <td width="12%" style="padding-left: 8px;"><span class="fs-20">{{"¥ ".$discount->discount}}</span></td>
                        <td width="8%" style="padding-left: 10px;">@if($discount->timeslot_breakfast == 1) <img src="{{asset('img/Group904.png')}}" height="20" /> @endif</td>
                        <td width="7%" style="padding-left: 10px;">@if($discount->timeslot_lunch == 1) <img src="{{asset('img/Group904.png')}}" height="20" /> @endif</td>
                        <td width="7%" style="padding-left: 10px;">@if($discount->timeslot_tea == 1) <img src="{{asset('img/Group904.png')}}" height="20" /> @endif</td>
                        <td width="7%" style="padding-left: 10px;">@if($discount->timeslot_dinner == 1) <img src="{{asset('img/Group904.png')}}" height="20" /> @endif</td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{--<div class="row mt-4 mb-4" style="margin-left: 0;">--}}
        {{--<div class="col-12 mb-3">--}}
            {{--<div class="d-inline-block text-white font-bold border-blue ">--}}
                {{--<table>--}}
                    {{--<tr>--}}
                        {{--<td class="d-inline-block border-rightBlue p-3 w-60px" style="font-size: 15px;">--}}
                            {{--<a class="font-weight-bold text-white" href="{{ route('admin.dish') }}" >{{ __('admin.Bottom.DISH') }}</a>--}}
                        {{--</td>--}}
                        {{--<td class="p-3 d-inline-block border-rightBlue w-60px" style="font-size: 15px;">--}}
                            {{--<a class="font-weight-bold text-white" href="{{ route('admin.category') }}">{{ __('admin.Bottom.CATEGORY') }}</a>--}}
                        {{--</td>--}}
                        {{--<td class="p-3 d-inline-block border- w-60px border-rightBlue" style="font-size: 15px;">--}}
                            {{--<a class="font-weight-bold text-white" href="{{ route('admin.option') }}">{{ __('admin.Bottom.OPTION') }}</a>--}}
                        {{--</td>--}}
                        {{--<td class="bg-blue2 p-3 d-inline-block border-rightBlue w-60px" style="font-size: 15px;">--}}
                            {{--<a class="font-weight-bold text-white" href="#">{{ __('admin.Bottom.DISCOUNT') }}</a>--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--</table>--}}

            {{--</div>--}}
            {{--<a class="text-white  btnCreateNewDiscount" style="margin-left: 165px;" onclick="create_new_discount()">CREATE NEW DISCOUNT--}}
                {{--<img src="{{asset('img/Group728white.png')}}"  height="15" /> </a>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="row mt-4 mb-4" style="margin-left: 0;">
        <div class="col-12 mb-3">
            <div class="d-inline-block text-white font-bold border-blue ">
                <table>
                    <tr>
                        <td class="d-inline-block border-rightBlue p-3 w-60px" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.dish') }}" >{{ __('admin.Bottom.DISH') }}</a>
                        </td>
                        <td class="p-3 d-inline-block border-rightBlue w-60px" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.category') }}">{{ __('admin.Bottom.CATEGORY') }}</a>
                        </td>
                        <td class="p-3 d-inline-block border- w-60px border-rightBlue" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.option') }}">{{ __('admin.Bottom.OPTION') }}</a>
                        </td>
                        <td class="bg-blue2 p-3 d-inline-block border-rightBlue  w-60px" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.discount') }}">{{ __('admin.Bottom.DISCOUNT') }}</a>
                        </td>
                    </tr>
                </table>
            </div>
            @if($check_discount_list == 0)
            <a href="{{ route('admin.discount.add') }}" class="text-white btnCreateNewDiscount fs-20" style="margin-left: 50px;margin-top: 5px;">
                {{ __('admin.Discount.Create_New_Discount') }}
                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
            </a>
            @endif
        </div>
    </div>


    <!-- Default switch -->
    <!--<label class="bs-switch">
        <input type="checkbox">
        <span class="slider round"></span>
    </label>-->
</div>
</div>
<script>
    function onrow(obj)
    {
        var edit_url = $(obj).data('url');
        window.location = edit_url;
    }

    function create_new_discount() {

        var chk_discount = $('#check_discount_list').val();
        if(chk_discount != 1) {
            document.location.href = "{{ route('admin.discount.add') }}";
        } else {
            //alert('There is no dishes for discount.');
            $("#alert-string")[0].innerText = "割引の料理はありません。";
            $("#java-alert").modal('toggle');

        }
    }
</script>
@endsection
