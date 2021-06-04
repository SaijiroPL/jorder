@extends('admin.setting')

@section('setting')
<div class="col-9 pl-0" style="margin-top: 100px;">
    <h5 class="black-text font-weight-bold pl-5 fs-30">{{__('setting.Multilingual')}}</h5>
    <form method="POST" action="{{ route('admin.setting.language.save') }}" id="saveForm">
    <div class="card pt-3 pb-2 ml-5 mt-4 col-lg-12 pr-0" style="width: 65vw;">
        <div class="row">
            <div class="col-6 pl-4">
                <h5 class="font-weight-normal fs-25">日本語</h5>
            </div>
            <div class="col-5 pr-0 text-right">
                <label class="switch-style">
                    <input type="checkbox" name="lang_jp"
                    @if($profile->lang_jp == 1)
                        checked
                    @endif
                    >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    {{--<div class="card pt-3 pb-2 ml-5 mt-2 col-lg-12 pr-0" style="width: 895px;">
        <div class="row">
            <div class="col-6 pl-4">
                <h5 class="font-weight-normal fs-25">Korean</h5>
            </div>
            <div class="col-5 pr-0 text-right">
                <label class="switch-style">
                    <input type="checkbox" name="lang_kr"
                    @if($profile->lang_kr == 1)
                        checked
                    @endif
                    >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>--}}
    <div class="card pt-3 pb-2 ml-5 mt-2 col-lg-12 pr-0" style="width: 65vw;">
        <div class="row">
            <div class="col-6 pl-4">
                <h5 class="font-weight-normal fs-25">中国語</h5>
            </div>
            <div class="col-5 pr-0 text-right">
                <label class="switch-style">
                    <input type="checkbox" name="lang_cn"
                    @if($profile->lang_cn == 1)
                        checked
                    @endif
                    >
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    @csrf
    </form>
    {{--<div class="col-11 mt-5 pr-2 text-right">--}}
        {{--<a href="#" class="btn bg-white black-text pt-2 pb-2 pr-2 pl-2"><h5 class="black-text mb-0">Cancel</h5></a>--}}
        {{--<a href="#" class="btn bg-info black-text pt-2 pb-2 pr-2 pl-2"><h5 class="white-text mb-0" onclick="onapply()">Apply</h5></a>--}}
    {{--</div>--}}
    <div class="col-lg-12 mt-5 pt-2 pr-4 text-right">
        <a href="{{ route('admin.setting.language') }}" class="btn bg-white black-text pt-2 pb-2 pr-3 pl-3">
            <h5 class="black-text mb-0 fs-25">
                <b>{{ __('admin.Common.Cancel') }}</b>
                <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
        <a href="#" class="btn bg-info black-text pt-2 pb-2 pr-3 pl-3" style="margin-right: -8px;">
            <h5 class="white-text mb-0 fs-25" onclick="onApply()">
                <b>{{ __('admin.Common.Apply') }}</b>
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
