@extends('admin.setting')

@section('setting')
<style>
    .img-selected {
        border: solid 2px #01b4ff;
    }
</style>
<div class="col-9 pl-5" style="margin-top: 45px;">
    <form method="POST" action="{{ route('admin.setting.screentime.save') }}" id="saveForm" style="height: 63vh; overflow: auto">
    <div class="mt-5">
        <h5 class="black-text font-weight-bold fs-30">{{__('setting.Screen_Time')}}</h5>
    </div>
    <div class="mt-3">
        <h6 class="font-weight-bold text-info fs-20">{{ __('setting.Current_Screen_Time(s)')}}</h6>
        <input style="border:1px solid grey;border-radius:5px;width: 200px;height:50px;font-size: 25px;" class="white pl-2" type="text" name="current_time" id="current_time"
        @if(!empty($screentime)) value="{{ $screentime->screen_time }}" @endif disabled/>
    </div>
    <div class="mt-3">
        <h6 class="font-weight-bold text-info fs-20">{{ __('setting.New_Screen_time(s)')}}</h6>
        <input style="border:1px solid grey;border-radius:5px;width: 200px;height:50px;font-size: 25px;" class="white pl-2" type="text" name="new_time" id="new_time"/>
    </div>
    <div class="mt-1">
        <h6 class="font-weight-bold text-info fs-20">広告</h6>
        @foreach($img_name as $img)
            <img src="{{ asset('screen/'.$img) }}" style="width:100px; height:100px" class="img-screen @if($profile->advertise_img == asset('screen/'.$img)) img-selected @endif"/>
        @endforeach
        <input type="hidden" name="advertise_img" id="advertise_img">
    </div>
    <div class="text-right">
        <button type="button" class="btn bg-info text-right radius-1 pt-2 pb-2 pr-4 pl-4" style="margin-right:16px" onclick="onFileScreen()">
            <h6 class="mb-0 pt-1 pb-1 pr-5 pl-5 font-weight-bold fs-20" >{{__('admin.Common.Add')}}</h6>
        </button>
    </div>
    <div class="mt-1">
        <h6 class="font-weight-bold text-info fs-20">背景画面</h6>
        @foreach($backs as $img)
            <img src="{{ asset('background/'.$img) }}" style="width:100px; height:100px" class="img-back @if($profile->background_img == asset('background/'.$img)) img-selected @endif"/>
        @endforeach
        <input type="hidden" name="background_img" id="background_img">
    </div>
    <div class="text-right">
        <button type="button" class="btn bg-info text-right radius-1 pt-2 pb-2 pr-4 pl-4" style="margin-right:16px" onclick="onFileBack()">
            <h6 class="mb-0 pt-1 pb-1 pr-5 pl-5 font-weight-bold fs-20" >{{__('admin.Common.Add')}}</h6>
        </button>
    </div>
    @csrf
    </form>
    <form action="{{ route('admin.setting.screentime.img') }}" method="POST" id="image_form" enctype='multipart/form-data'>
        <input id="image-file" type="file" style="position:fixed; top:-100px" name="image-file" accept="image/x-png, image/gif, image/jpeg" onchange="submit()">
        <input type="hidden" name="upload_type" id="upload_type">
        @csrf
    </form>
    <div style="margin-bottom:5vh"></div>
    <div class="col-lg-12 mt-5 pt-2 pr-4 text-right">
        <a href="{{ route('admin.setting.screentime') }}" class="btn bg-white black-text pt-2 pb-2 pr-3 pl-3">
            <h5 class="black-text mb-0 fs-20">
                <b>{{__('admin.Common.Cancel')}}</b>
                <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
        <a href="#" class="btn bg-info black-text pt-2 pb-2 pr-3 pl-3" style="margin-right: -8px;">
            <h5 class="white-text mb-0 fs-20" onclick="onApply()">
                <b>{{__('admin.Common.Apply')}}</b>
                <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;">
            </h5>
        </a>
    </div>
</div>
<div class="modal fade" id="java-alert1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="margin-top: -50px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                </button>
            </div>
            <div class="modal-body pr-4">
                <p id="alert-string1" class="text-center fs-20"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                    {{ __('admin.Common.Close') }}
                    <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function onApply()
    {
        $('#saveForm').submit();
    }
    $('.img-screen').on('click', function(){
        $('.img-screen').removeClass('img-selected');
        $(this).addClass('img-selected');
        $('#advertise_img').val($(this).prop('src'));
    })
    $('.img-back').on('click', function(){
        $('.img-back').removeClass('img-selected');
        $(this).addClass('img-selected');
        $('#background_img').val($(this).prop('src'));
    })
    function onFileScreen(){
        $('#upload_type').val('screen');
        $('#image-file').trigger('click');
    }
    function onFileBack(){
        $('#upload_type').val('background');
        $('#image-file').trigger('click');
    }
</script>
@endsection
