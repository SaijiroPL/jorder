@extends('layout.admin_layout')

@section('title', 'DISH')

@section('content')
<div style="padding-top:8%;">
</div>
<div class="widthh blackgrey  pt-4" style="height: 90vh;">
    <div class="row">
        <div class="col-6">
            <label class="text-white font-weight-bold fs-30">{{ __('admin.Bottom.OPTION') }}</label>
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
                        <th class="border-0 fs-3 fontbig" scope="col" width="25%">
                            <a href="{{route("admin.option.sort", ["sortField" => "name", "sort_type_name" => $sort_type_name, "sort_type_display_name" => $sort_type_display_name])}}" class="text-white fs-20">
                                <b>{{ __('admin.Option.NAME') }}</b>
                                <img
                                        @if($sort_type_name == "asc")
                                            src="{{ asset('img/Path445.png') }}"
                                        @else
                                            src="{{ asset('img/Path444.png') }}"
                                        @endif
                                        height="20" style="margin: -1px 0 0 0;"/>
                            </a>
                        </th>
                        <th class="border-0 fs-3 fontbig" scope="col" width="25%">
                            <a href="{{route("admin.option.sort", ["sortField" => "display_name", "sort_type_display_name" => $sort_type_display_name, "sort_type_name" => $sort_type_name])}}" class="text-white fs-20">
                                <b>{{ __('admin.Option.DISPLAY_NAME') }}</b>
                                <img
                                        @if($sort_type_display_name == "asc")
                                        src="{{ asset('img/Path445.png') }}"
                                        @else
                                        src="{{ asset('img/Path444.png') }}"
                                        @endif
                                        height="20" style="margin: -1px 0 0 0;"/>
                            </a>
                        </th>
                        <th class="border-0" scope="col" width="50%"><b class="fs-20">{{ __('admin.Option.RELATED_DISHES') }}</b></th>
                    </tr>
                </thead>
            </table>
            <div style="height:59vh;overflow-y:auto;">
                <table class="table text-white txtdemibold" style="width: 99%;">
                    <tbody>
                        @foreach ($options as $option)
                        <tr onclick="onrow({{ $option->id }})">
                            <td width="25%" style="padding-left: 0;"><span class="fs-20">{{ $option->name }}</span></td>
                            <td width="25%" style="padding-left: 12px;"><span class="fs-20">{{ $option->display_name_en }}</span></td>
                            <td width="50%" style="padding-left: 20px;"><span class="fs-20">{{ $option->related_dishes }}</span></td>
                            <td></td>
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
                        <td class="d-inline-block border-rightBlue p-3 w-60px" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.dish') }}" >{{ __('admin.Bottom.DISH') }}</a>
                        </td>
                        <td class="p-3 d-inline-block border-rightBlue w-60px" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.category') }}">{{ __('admin.Bottom.CATEGORY') }}</a>
                        </td>
                        <td class="bg-blue2 p-3 d-inline-block border- w-60px border-rightBlue" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.option') }}">{{ __('admin.Bottom.OPTION') }}</a>
                        </td>
                        <td class="p-3 d-inline-block border-rightBlue  w-60px" style="font-size: 15px;">
                            <a class="font-weight-bold text-white fs-20" href="{{ route('admin.discount') }}">{{ __('admin.Bottom.DISCOUNT') }}</a>
                        </td>
                    </tr>
                </table>
            </div>
            <a href="{{ route('admin.option.add') }}" class="text-white btnCreateNewDiscount fs-20" style="margin-top: 5px;">
                {{ __('admin.Option.Create_New_Option') }}
                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
            </a>
        </div>
    </div>
    <!-- Default switch -->
    <!--<label class="bs-switch">
        <input type="checkbox">
        <span class="slider round"></span>
    </label>-->
</div>
<script>
    function onrow(id){
        window.location = "{{ url('admin/option/edit') }}" + "/" + id;
    }
</script>
@endsection
