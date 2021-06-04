@extends('layout.admin_layout')

@section('title', 'Settings')

@section('content')
<div class="pp">
    <div style="padding-top:5%;" class="pt">
    </div>
    <div class="pr-3 pl-3 pbb hh bg-light position-relative st-main">
        <a href="{{ route('reception.seated', ['status' => 'seated']) }}" class="bg-transparent" style="position:absolute;top:15px ;right:10px">
            <h2><span class="">
                <img src="{{ asset('img/Group1100.png') }}" width="30" height="30" class="float-right mt-3 mr-3" />
            </span></h2>
        </a>
        <div class="pt-4">
            <div class="row">
                <div class="col-3 pl-0">
                    <h5 class="blacditext font-weight-bold pl-5 mb-2 mt-3 fs-25">{{ __('menu.Setting') }}</h5>
                    <div class="setting_menu">
                        <ul class="col-lg-12 pl-0 w-100 pt-1" style="list-style-type:none">
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.kitchen') }}"><a style="color:white" class="anchor-white fs-20" href="#">{{__('setting.Kitchen_Groups')}}</a></li>
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.timeslots') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.Time_Slots')}}</a></li>
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.htimeslots') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.Holiday_Time_Slots')}}</a></li>
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.customer') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.New_Customer')}}</a></li>
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.screentime') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.Screen_Time')}}</a></li>
                        </ul>
                        <ul class="col-lg-12 pl-0 w-100 mt-3" style="list-style-type:none">
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.gst') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.GST')}}</a></li>
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.payment') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.Payment_Methods')}}</a></li>
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.receipt') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.Receipt')}}</a></li>
                        </ul>
                        <ul class="col-lg-12 pl-0 w-100 mt-3" style="list-style-type:none">
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.badge') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.Badges')}}</a></li>
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.language') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.Multilingual')}}</a></li>
                            <li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.password') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.Password')}}</a></li>
                            {{--<li class="menu1 fontbig" onclick="onmenu(this)" data-url="{{ route('admin.setting.sendmail') }}"><a class="anchor-white fs-20" style="color:white" href="#">{{__('setting.SendMail')}}</a></li>--}}
                        </ul>
                    </div>
                </div>
                @yield('setting')
            </div>
        </div>
    </div>
</div>
<script>
    $('.menu1').each(function(i, obj){
        var route = $(obj).data('url');
        var currentUrl = window.location.origin + window.location.pathname;
        if(route == window.location.href){
            $(obj).addClass('black');
        }
    });
    function onmenu(obj){
        window.location = $(obj).data('url');
    }
</script>
@endsection
