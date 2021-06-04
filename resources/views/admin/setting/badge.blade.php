@extends('admin.setting')

@section('setting')
<div class="col-9 pl-5" style="margin-top: 100px;">
    <h5 class="black-text font-weight-bold fs-25">{{__('setting.Badges')}}</h5>
    <div class="row mt-4">
        <div class="col-6">
            <h6 class="text-info font-weight-bold pl-5 fs-20">{{__('setting.Name')}}</h6>
        </div>
        <div class="col-3">
            <h6 class="text-info font-weight-bold fs-20">{{__('setting.Image')}}</h6>
        </div>
        <div class="col-3">
            <h6 class="text-info font-weight-bold fs-20">{{__('setting.Active')}}</h6>
        </div>
    </div>
    <form action="{{ route('admin.setting.activebadge') }}" method="POST" id="active_form" class="bd_form">
    @foreach($badges as $badge)
    <div class="card pt-3 pb-2" style="margin-bottom:10px">
        <div class="row">
            <div class="col-6" onclick="editbadge('{{$badge->active}}','{{$badge->name}}','{{$badge->id}}')">
                <h6 class="font-weight-bold pl-5 fs-20">{{ $badge->name }}</h6>
            </div>
            <div class="col-1 text-right" onclick="editbadge('{{$badge->active}}','{{$badge->name}}','{{$badge->id}}')">
                <img class="" src="{{ asset('badges/'.$badge->filepath) }}" width="30px" />
            </div>
            <div class="col-2"></div>
            <div class="col-1 text-right" style="margin-left: 20px;">
                <label class="switch-style">
                    <input type="checkbox" name="actives[]" value="{{ $badge->id }}"
                    @if($badge->active == 1)
                    checked
                    @endif
                    >
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
    @endforeach
    @csrf
    </form>
    <form action="{{ route('admin.setting.addbadge') }}" method="POST" id="image_form" enctype='multipart/form-data'>
        <input id="image-file" type="file" style="position:fixed; top:-100px" name="image-file" accept="image/x-png, image/gif, image/jpeg">
        <input id="image-name" name="image-name" type="hidden">
        <input id="b-name" name="b-name" type="hidden">
        @csrf
    </form>
    <div class="text-right">
        <button class="btn bg-info text-right radius-1 pt-2 pb-2 pr-4 pl-4" style="margin-right:16px" onclick="onFile()">
            <h6 class="mb-0 pt-1 pb-1 pr-5 pl-5 font-weight-bold fs-20" >{{__('admin.Common.Add')}}</h6>
        </button>
    </div>
    <div class="col-lg-12 mt-3 pt-2 pr-4 text-right">
        <a href="{{ route('admin.setting.badge') }}" class="btn bg-white black-text pt-2 pb-2 pr-3 pl-3">
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

<div class="modal fade" id="java-alert2" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="margin-top: -50px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                </button>
            </div>
            <div class="modal-body pr-4">
                <h5 class="text-info font-weight-normal fs-20">バッジ名を入力してください</h5>
		        <input class="form-control pl-3" style="font-size: 25px;" type="text" name="badge-name" id="badge-name">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                    {{__('admin.Common.Cancel')}}
                    <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                </button>
                <button type="submit" class="btn btn-primary waves-effect waves-light fs-20" onclick="addbadge()">
                    {{__('admin.Common.Apply')}}
                    <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                </button>
            </div>
        </div>
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
    function onFile(){
        $('#image-file').trigger('click');
    }
    $('#image-file').change(function(){
        /*var image_name = prompt('Please enter badge name');
        if(image_name != null && image_name != ''){
            $('#image-name').val(image_name);
        }
        $('#image_form').submit();*/
        $("#java-alert2").modal('toggle');
    });
    function onApply()
    {
        $('#active_form').submit();
    }
    function addbadge()
    {
        var badge_name = $('#badge-name').val();
        if(badge_name == '') {
            $("#alert-string1")[0].innerText = "バッジ名を入力してください！";
            $("#java-alert1").modal('toggle');
        } else {

            $('#image-name').val(badge_name);
            $('#image_form').submit();
        }
    }
    function editbadge(flag,b_name,b_id)
    {
        if(flag==1)
        {
            $('#b-name').val(b_id);
            $('#badge-name').val(b_name);
            $('#image-file').trigger('click');
        }
    }
</script>
@endsection
