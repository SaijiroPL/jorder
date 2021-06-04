@extends('admin.setting')

@section('setting')
<div class="col-9 pl-5" style="margin-top: 100px;">
    <h5 class="black-text font-weight-bold fs-25">{{__('setting.GST')}}</h5>
    <div class="card ml-2 col-lg-7 pt-4 mr-auto pb-4 ml-auto mt-4">
        <div class="col-lg-12 pr-0 pl-0 text-center">
            <input class="blueborder pt-2 pb-2 mb-5"  value="{{ round($profile->gst, 2) }} %" readonly id="result"
                style="padding-left:10px;padding-right:10px;text-align:right;font-size: 25px;width: 330px;" />
        </div>
        <div class="row mt-2">
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="7">
                    <h4 class="mb-0 fs-20">7</h4>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="8">
                    <h4 class="mb-0 fs-20">8</h4>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="9">
                    <h4 class="mb-0 fs-20">9</h4>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="4">
                    <h4 class="mb-0 fs-20">4</h4>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="5">
                    <h4 class="mb-0 fs-20">5</h4>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="6">
                    <h4 class="mb-0 fs-20">6</h4>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="1">
                    <h4 class="mb-0 fs-20">1</h4>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="2">
                    <h4 class="mb-0 fs-20">2</h4>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="3">
                    <h4 class="mb-0 fs-20">3</h4>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="C">
                    <h4 class="mb-0">C</h4>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="0">
                    <h4 class="mb-0">0</h4>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="grey text-center white-text radius-1" style="padding: 20px 0 20px 0;" data="B">
                    <h4 class="mb-0"><span class="fa fa-arrow-left"></span></h4>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="col-lg-11 mt-5 pr-2 text-right">--}}
        {{--<button class="btn bg-white black-text pt-2 pb-2 pr-2 pl-2"><h5 class="black-text mb-0">Cancel</h5></button>--}}
        {{--<button class="btn bg-info black-text pt-2 pb-2 pr-2 pl-2" onclick="onSave()"><h5 class="white-text mb-0">Apply</h5></button>--}}
    {{--</div>--}}

    <div class="col-lg-12 mt-5 pt-3 pr-4 text-right">
        <a href="{{ route('admin.setting.gst') }}" class="btn bg-white black-text pt-2 pb-2 pr-3 pl-3">
            <h5 class="black-text mb-0 fs-20">
                <b>{{__('admin.Common.Cancel')}}</b>
                <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
        <a href="#" class="btn bg-info black-text pt-2 pb-2 pr-3 pl-3" style="margin-right: -8px;">
            <h5 class="white-text mb-0 fs-20" onclick="onSave()">
                <b>{{__('admin.Common.Apply')}}</b>
                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
    </div>
</div>
<form method="POST" action="{{ route('admin.setting.gst.save') }}" id="saveForm">
    @csrf
    <input type="hidden" name="gst" id="form_result">
</form>
<script>
    var gst = 0;
    $(".grey").click(function(){
        var v = $(this).attr('data');
        if(v != 'C' && v != 'B'){
            if(gst / 10 > 1){
                return;
            }
            gst = gst * 10 + Number(v);
        } else if (v == 'B') {
            if(gst > 10) {
                gst = (gst - gst % 10) / 10;
            } else {
                gst = 0;
            }
        } else if (v == 'C') {
            gst = 0;
        }
        $('#result').val(gst + " %");
    });
    function onSave(){
        $('#form_result').val(gst);
        $('#saveForm').submit();
    }
</script>
@endsection
