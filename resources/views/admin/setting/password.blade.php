@extends('admin.setting')

@section('setting')
<div class="col-9 pl-5" style="margin-top: 45px;">
    <form method="POST" action="{{ route('admin.setting.password.save') }}" id="saveForm">
    <div class="mt-5">
        <h6 class="font-weight-bold text-info fs-25">{{__('auth.Menu')}}</h6>
        <input style="border:1px solid grey;border-radius:5px;width: 60vw;" class="white pl-4" type="password" name="password_menu"
            @if($profile->password_menu != '')
                value="********"
            @endif
            />
    </div>
    <div class="mt-2">
        <h6 class="font-weight-bold text-info fs-25">{{__('auth.TakeawayMenu')}}</h6>
        <input style="border:1px solid grey;border-radius:5px;width: 60vw;" class="white pl-4" type="password" name="password_takeawaymenu"
            @if($profile->password_takeawaymenu != '')
                value="********"
            @endif
        />
    </div>
    <div class=" mt-2">
        <h6 class="font-weight-bold text-info fs-25">{{__('auth.Kitchen')}}</h6>
        <input style="border:1px solid grey;border-radius:5px;width: 60vw;" class="white pl-4" type="password" name="password_kitchen"
            @if($profile->password_kitchen != '')
                value="********"
            @endif
        />
    </div>
    <div class="mt-2">
        <h6 class="font-weight-bold text-info fs-25">{{__('auth.Reception')}}</h6>
        <input style="border:1px solid grey;border-radius:5px;width: 60vw;" class="white pl-4" type="password" name="password_reception"
            @if($profile->password_reception != '')
                value="********"
            @endif
        />
    </div>
    <div class="mt-2">
        <h6 class="font-weight-bold text-info fs-25">{{__('auth.Master')}}</h6>
        <input style="border:1px solid grey;border-radius:5px;width: 60vw;" class="white pl-4" type="password" name="password_admin"
            @if($profile->password_admin != '')
                value="********"
            @endif
        />
    </div>
    @csrf
    </form>

    <div class="col-lg-12 mt-5 pt-2 pr-4 text-right">
        <a href="{{ route('admin.setting.password') }}" class="btn bg-white black-text pt-2 pb-2 pr-3 pl-3">
            <h5 class="black-text mb-0 fs-25">
                <b>{{__('admin.Common.Cancel')}}</b>
                <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
        <a href="#" class="btn bg-info black-text pt-2 pb-2 pr-3 pl-3" style="margin-right: -8px;">
            <h5 class="white-text mb-0 fs-25" onclick="onApply()">
                <b>{{__('admin.Common.Apply')}}</b>
                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
    </div>
</div>
<script>
    function onApply()
    {
        $('#saveForm').submit();
    }
</script>
@endsection
