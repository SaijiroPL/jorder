@extends('admin.setting')

@section('setting')
<div class="col-9 pl-5" style="margin-top: 100px;">
    <h5 class="black-text font-weight-bold fs-30">{{__('setting.New_Customer')}}</h5>
    <h6 class="font-weight-bold text-info pt-3 mt-2 fs-20">{{__('setting.Default_Duration')}}</h6>
    <div class="card ml-5 col-lg-5 col-8 pt-4 mr-auto pb-4 ml-auto" style="height: 400px;">
        <div id="example-picker" style="font-size: 25px;">

        </div>
        <form method="POST" action="{{ route('admin.setting.customer.save') }}" id="saveForm">
            <input type="hidden" id="customer_time" name="customer_time">
            @csrf
        </form>
    </div>
    {{--<div class="col-11 mt-5 pr-2 text-right">--}}
        {{--<a href="#" class="btn bg-white black-text pt-2 pb-2 pr-2 pl-2"><h5 class="black-text mb-0">Cancel</h5></a>--}}
        {{--<a href="#" class="btn bg-info black-text pt-2 pb-2 pr-2 pl-2"><h5 class="white-text mb-0" onclick="onapply()">Apply</h5></a>--}}
    {{--</div>--}}

    <div class="col-lg-12 mt-5 pt-2 pr-4 text-right">
        <a href="{{ route('admin.setting.customer') }}" class="btn bg-white black-text pt-2 pb-2 pr-3 pl-3">
            <h5 class="black-text mb-0 fs-20">
                <b>{{ __('admin.Common.Cancel') }}</b>
                <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
        <a href="#" class="btn bg-info black-text pt-2 pb-2 pr-3 pl-3" style="margin-right: -8px;">
            <h5 class="white-text mb-0 fs-20" onclick="onApply()">
                <b>{{ __('admin.Common.Apply') }}</b>
                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.ios.picker.js') }}"></script>
<script>
    $('#example-picker').picker({
        data: ['テイクアウト','30分','60分','90分','120分','無制限'],
        selected: "{{ $profile->customer }}",
        lineHeight: 50,
    }, function(s){
    });
    function onApply()
    {
        $('#saveForm').submit();
    }
</script>
@endsection
