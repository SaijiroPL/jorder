@extends('layout.admin_layout')

@section('title', 'DISH')

@section('content')
<div style="padding-top:8%;">
</div>
<div class="widthh blackgrey pt-4" style="height: 90vh;">
    <div class="row">
        <div class="col-6">
            <label class="text-white font-weight-bold fs-30">{{ __('admin.Bottom.DISH') }}</label>
        </div>
        <div class="col-6">
            <a onclick="window.history.back()">
                <img src="{{ asset('img/Group826.png') }}" width="25" height="25" class="float-right" />
            </a>
        </div>
    </div>
    <div class="row mb-2" style="height: 65vh;">
        <div class="col-12">
            <table style="width: 99%;color: white;margin-top: 20px;border-bottom: 1px solid white; table-layout:fixed">
                <thead>
                    <tr>
                        <th class="border-0 fs-3 pd" scope="col" width="52%">
                            <a href="{{route('admin.dish.sort', ['sortType' => $sort])}}" class="text-white fs-20">
                                <b>{{ __('admin.Dish.ITEM') }}</b>
                                @if($sort == "asc")
                                    <img src="{{ asset('img/Path444.png') }}" height="20" style="margin: -1px 0 0 5px;" />
                                @else
                                    <img src="{{ asset('img/Path445.png') }}" height="20" style="margin: -5px 0 0 5px;" />
                                @endif
                            </a>
                        </th>
                        <th class="border-0 pd" scope="col" width="12%"><b class="fs-20">{{ __('admin.Dish.GROUP') }}</b></th>
                        <th class="border-0 pd" scope="col" width="12%"><b class="fs-20">{{ __('admin.Dish.PRICE') }}</b></th>
                        <th class="border-0 pd" scope="col" colspan="2" width="24%"><b class="fs-20">{{ __('admin.Dish.STATUS') }}</b></th>
                    </tr>
                    <tr>
                        <td class="border-0 pd"></td>
                        <td class="border-0 pd"></td>
                        <td class="border-0 pd"></td>
                        <td class="border-0 pd fs-20" width="12%"><b>{{ __('admin.Dish.Sold_Out') }}</b></td>
                        <td class="border-0 pd fs-20" width="12%"><b>{{ __('admin.Dish.Active') }}</b></td>
                    </tr>
                </thead>
            </table>
            <div style="height: 54vh;overflow-y: auto;">
                <table class="table text-white txtdemibold" style="width: 99%; table-layout:fixed">
                    <tbody>
                        @foreach($dishes as $d)
                        <tr onclick="onrow(this)" data-url="{{ route('admin.dish.preview', ['id' => $d->id]) }}">
                            <td width="52%" style="padding-left: 0;"><span class="fs-20">{{ $d->name_jp }}</span></td>
                            <td width="12%" style="padding-left: 9px;">
                                @if($d->group_id)
                                    <span class="fs-20">
                                        {{ $d->group_name }}
                                    </span>
                                @endif
                            </td>
                            <td width="12%" style="padding-left: 9px;"><span class="fs-20">¥ {{ $d->price }}</span></td>

                            <td width="12%" style="padding-left: 12px;">
                                @if($d->sold_out == 1)
                                <img src="{{ asset('img/Group904.png') }}" height="20" />
                                @endif
                            </td>
                            <td width="12%" style="padding-left: 13px;">
                                @if($d->active == 1)
                                <img src="{{ asset('img/Group904.png') }}" height="20" />
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-4">
        <div class="col-12 mb-3">
            <div class="d-inline-block text-white font-bold border-blue ">
                <table>
                    <tr>
                        <td class="bg-blue2 d-inline-block border-rightBlue p-3 w-60px" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.dish') }}" >{{ __('admin.Bottom.DISH') }}</a>
                        </td>
                        <td class="p-3 d-inline-block border-rightBlue w-60px" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.category') }}">{{ __('admin.Bottom.CATEGORY') }}</a>
                        </td>
                        <td class="p-3 d-inline-block border- w-60px border-rightBlue" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.option') }}">{{ __('admin.Bottom.OPTION') }}</a>
                        </td>
                        <td class="p-3 d-inline-block border-rightBlue  w-60px" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.discount') }}">{{ __('admin.Bottom.DISCOUNT') }}</a>
                        </td>
                    </tr>
                </table>
            </div>
            <a href="{{ route('admin.dish.add') }}" class="text-white btnCreateNewDiscount fs-20" style="margin-top: 5px;">
                {{ __('admin.Dish.CREATE_NEW_DISH') }}
                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
            </a>
        </div>
    </div>
</div>
<script>
    function onrow(obj)
    {
        var url = $(obj).data('url');
        window.location = url;
    }
</script>
@endsection
