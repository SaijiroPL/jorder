@extends('layout.admin_layout')

@section('title', 'OPTION')

@section('content')
<div class="container-fluid pb-5 blackgrey" style="min-height: 870px;">
    <div style="padding-top:8.5%;"></div>

    <div class="popupp" id="popupp"></div>

    <div class="popupchangePhoto white" id="popupchangePhoto">
        <div class="row">
            <div class="col-12">
                <span class="" id="btncloselang">
                    <img src="{{ asset('img/Group1100.png') }}" width="30" height="30" class="float-right mt-3 mr-3"  />
                </span>
            </div>
        </div>
        <img id="popupimg" class="popupimg" style="width: 500px;height: 500px;margin: 0 0 50px 93px;"/>
        <input type="file" style="display:none" id="option_image_modal">
        <input type="hidden" name="item_id">
        <button class="addOptionbtn mcenter fs-20" style="width: 200px;">Change Photo</button>
    </div>

    {{--<div class="widthh pb-2 pt-3 white" style="height: auto;min-height: 862px;">--}}
    <div class="widthh pb-2 pt-3 white" style="overflow:auto;">
        <div class="row">
            <div class="col-12">
                <a><span class="">
                    <img src="{{ asset('img/Group1100.png') }}" width="30" height="30" class="float-right mt-3 mr-3" onclick="onCancel()" />
                </span></a>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.option.store') }}" enctype='multipart/form-data' id="post_form" class="pl-4 pr-4">
        <input type="hidden" name="id" value="{{ $obj->id }}">
        <input type="hidden" value="{{ $gst }}" id="gst" name="gst">
        <input type="hidden" value="{{ count($obj->items) }}" id="count_item" name="count_item">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                   <div>
                       <label class="text-blue txtdemibold fs-20">{{ __('admin.Option.NAME') }}</label>
                   </div>
                    <input type="text" class="outline-0 border-bottom-blue" style="font-size: 25px;" name="name" id="name" value="{{ $obj->name }}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <div>
                        <label class="text-blue txtdemibold fs-20">{{ __('admin.Option.DISPLAY_NAME') }}</label>
                    </div>
                    <input type="text" class="outline-0 border-bottom-blue" style="font-size: 25px;" name="display_name_en" id="name_en" value="{{ $obj->display_name_en }}" />
                </div>
                <div class="form-group">
                    <div>
                        <label class="text-blue txtdemibold fs-20">{{ __('admin.Option.DISPLAY_NAME_M') }}</label>
                    </div>
                    <input type="text" class="outline-0 border-bottom-blue" style="font-size: 25px;" name="display_name_cn" value="{{ $obj->display_name_cn }}" />
                </div>
                <div class="form-group">
                    <div>
                        <label class="text-blue txtdemibold fs-20">{{ __('admin.Option.DISPLAY_NAME_J') }}</label>
                    </div>
                    <input type="text" class="outline-0 border-bottom-blue" style="font-size: 25px;" name="display_name_jp" value="{{ $obj->display_name_jp }}" />
                </div>
            </div>
        </div>
        <div class="border-bottom-blue3 mt-3 mb-3"></div>

        <div class="row">
            <div class="col-5" style="margin-top: 15px;">
                <div class="row">
                    <div class="col-6">
                        <label class="text-blue txtdemibold mr-2 fs-20">{{ __('admin.Option.Multi_Select') }}</label>
                    </div>
                    <div class="col-6">
                        <span>
                            <label class="bs-switch">
                                <input type="checkbox" name="multi_select" id="multi_select" onchange="multi_select_change()"
                                @if($obj->multi_select == 1)
                                    checked
                                @endif
                                >
                                <span class="slider round"></span>
                            </label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <label class="text-blue txtdemibold m5 fs-20">{{ __('admin.Option.Number_Of_Selection') }}</label>
                <img src="{{ asset('img/Path506.png') }}" class="" style="margin-left: 40px;" height="40" width="45" onclick="decNumber()" id="minus" />
                <input type="number" class="numInput outline-0" style="margin: 0 20px 0 20px;" name="number_selection" id="number_selection"
                   @if($obj->number_selection)
                        value="{{ $obj->number_selection }}"
                   @else
                        value="1"
                   @endif
                   >
                <img src="{{ asset('img/Path531.png') }}" class="" height="40" width="45" onclick="incNumber()" id="plus" />
            </div>

        </div>
        <div class="border-bottom-blue3 mt-3 mb-3"></div>

        <div class="row">
            <div class="col-5" style="margin-top: 15px;">
                <div class="row">
                    <div class="col-6">
                        <label class="text-blue txtdemibold mr-2 fs-20">{{ __('admin.Option.Show_Photo') }}</label>
                    </div>
                    <div class="col-6">
                        <span>
                            <label class="bs-switch ">
                                <input type="checkbox" name="photo_visible" id="show_photo"
                                @if($obj->photo_visible == 1)
                                    checked
                                @endif
                                >
                                <span class="slider round"></span>
                            </label>
                        </span>
                    </div>
                </div>
            </div>
            </div>

        <div class="border-bottom-blue3 mt-3 mb-3"></div>

        <div class="row">
            <div class="col-6">
                <label class="text-blue txtdemibold fs-20">
                    {{ __('admin.Option.Option') }}
                </label>
            </div>
            <div class="col-2">
                <label class="text-blue ma35 txtdemibold fs-20">
                    {{ __('admin.Option.Price') }}
                </label>
            </div>
            <div class="col-1">
                <label class="text-blue ma35 txtdemibold fs-20">
                    {{ __('admin.Option.Photo') }}
                </label>
            </div>
            <div class="col-1">
                <label class="text-blue ma35 txtdemibold fs-20">
                    {{ __('admin.Option.Stock') }}
                </label>
            </div>
        </div>

        <div id="content">
        @foreach ($obj->items as $item)
            <div class="row">
                <div class="col-6">
                    <input type="text" class="outline-0 border-bottom-blue mt-2 option-name" style="font-size: 25px;"
                        name="prev-data[{{ $item->id }}][name]" value="{{ $item->name }}" />
                </div>
                <div class="col-2">
                    <input type="number" class="outline-0 border-bottom-blue mt-2 option-price" style="font-size: 25px;"
                        name="prev-data[{{ $item->id }}][price]" value="{{ $item->price }}" id="item_price_{{ $item->id }}" onchange="item_price_change({{ $item->id }})" />
                    <input type="hidden" value="{{ $gst }}" id="gst" name="gst">
                    <label class="text-blue float-right text-right fs-20" id="gst_value_{{ $item->id }}">
                        @if ($item->price)
                            (税: ¥ {{ round($item->price*$gst/100) }})
                        @endif
                    </label>
                </div>
                <div class="col-1">
                    {{--<button class="btnaddphoto3  mt-2 pt-1 add-photo-button" onclick="onAddImage(this)" type="button" style="display:none">--}}
                        {{--Add Photo1--}}
                    {{--</button>--}}
                    <img class="add-photo-button" width=55 height=55 onclick="onAddImage(this)" style="display: none"
                         src="{{ asset('img/image-add-icon.png') }}"
                    >
                    <img class="option-image" width=55 height=55  onclick="onAddImage(this)"
                        @if($obj->photo_visible == '1')
                        src="{{ asset('options/'.$item->image) }}"
                        @endif
                    >
                </div>
                <div class="col-1">
                    <label class="bs-switch mt-3">
                        <input type="checkbox" class="stock-check-obj"
                        @if($item->stock == 1)
                            checked
                        @endif
                        >
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="col-1">
                    <button class="btnaddphoto3 mt-3 fs-23" type="button" onclick="onDelete(this)" data-id={{ $item->id }}>
                        {{ __('admin.Common.Delete') }}
                    </button>
                </div>
                <input type="file" class="file-image" id="file-image" name="prev-data[{{ $item->id }}][image]" style="display:none">
                <input type="hidden" class="stock-check-value"  name="prev-data[{{ $item->id }}][stock]">
                <input type="hidden" class="old-id" name="prev[]" value="{{ $item->id }}">
            </div>
        @endforeach
        </div>

        <div class="row clone" style="display:none">
            <div class="col-6">
                <input type="text" class="outline-0 border-bottom-blue mt-2 option-name" style="font-size: 25px;" />
            </div>
            <div class="col-2">
                <input type="number" class="outline-0 border-bottom-blue mt-2 option-price" style="font-size: 25px;"/>
            </div>
            <div class="col-1">
                {{--<button class="btnaddphoto3  mt-2 pt-1 add-photo-button" onclick="onAddImage(this)" type="button">--}}
                    {{--Add Photo--}}
                {{--</button>--}}
                <img class="add-photo-button" width=55 height=55 onclick="onAddImage(this)"
                     src="{{ asset('img/image-add-icon.png') }}"
                >
                <img class="option-image" width=55 height=55 style="display:none">
            </div>
            <div class="col-1">
                <label class="bs-switch mt-3">
                    <input type="checkbox" class="stock-check-obj">
                    <span class="slider round"></span>
                </label>
            </div>
            <div class="col-1">
                <button class="btnaddphoto3 mt-3 fs-23" type="button" onclick="onDelete(this)" data-id=0>
                    {{ __('admin.Common.Delete') }}
                </button>
            </div>
            <input type="file" class="file-image" id="file-image" name="image" style="display:none">
            <input type="hidden" class="stock-check-value option-stock">
        </div>

        <button class="btnaddphoto3 mt-2 pt-1 fs-20" style="width: 200px;" type="button" onclick="onAddOption()">
            {{ __('admin.Option.Option').__('admin.Common.Add') }}
        </button>
        <div class="row mt-5 mb-3">
            <div class="col-6">
                <button class="grey-button fs-20" type="button" onclick="onDeleteMain(this)" data-url="{{ route('admin.option.delete', ['id' => $obj->id]) }}"
                        style="padding: 10px 15px 10px 15px;margin-top: -10px;">
                    {{ __('admin.Common.Delete') }}
                    <img src="{{ asset('img/Group728.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
                </button>
            </div>
            <div class="col-6">
                <button class="grey-button fs-20" type="button" onclick="onCancel()" style="color:black;padding: 10px 15px 10px 15px;margin-top:-10px;margin-left: 100px;">
                    {{ __('admin.Common.Cancel') }}
                    <img src="{{ asset('img/Group728.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
                </button>
                <button class="green-button fs-20" type="button" onclick="onApply()" style="padding: 10px 15px 10px 15px;margin-top:-10px;">
                    {{ __('admin.Common.Apply') }}
                    <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin: -5px 0 0 20px;" />
                </button>
            </div>
        </div>
        @csrf
        </form>
    </div>
</div>
<script>

    var count_item = $('#count_item').val();

    function onApply(){
        if($('#show_photo').is(':checked')){
            var invalidCt = 0;
            $('#file-image').each(function(i, obj){
                var parent = $(obj).closest('.row');
                var image_tag = $('.option-image', parent);
                if(($(obj).val() == "" && $(image_tag).attr('src') == "") && $(parent).is(':visible')) invalidCt++;
            });
            console.log(invalidCt);
            if(invalidCt > 1){
                //alert('Please upload photos');
                $("#alert-string")[0].innerText = "写真をアップロードしてください";
                $("#java-alert").modal('toggle');
                return;
            }
        }
        // else {
        //     var add_photo_button = document.getElementsByClassName("add-photo-button");
        //     add_photo_button.src = "";
        // }
        $('.stock-check-obj').each(function(i, obj){
            var parent = $(obj).closest('.row');
            var value = $('.stock-check-value', parent);
            if($(obj).is(':checked')){
                value.val(1);
            } else {
                value.val(0);
            }
        });

        var name = $('#name').val();
        var name_en = $('#name_en').val();
        var count_item = $("div[id^=aaa]").length;
        if(name == '') {
            //alert('Please input Name!');
            $("#alert-string")[0].innerText = "名前を入力してください！";
            $("#java-alert").modal('toggle');
        }
        else if(name_en == '') {
            //alert('Please input Display Name!');
            $("#alert-string")[0].innerText = "表示名を入力してください！";
            $("#java-alert").modal('toggle');
        }

        // else if(count_item == 0)
        //     alert('Please add Option(Include Option and Price)!');
        else
            $('#post_form').submit();

    }
    var current_image_obj;
    var current_file_obj;
    //event on each file input
    $(document).on('change', '#file-image', function(ev){
        var f = ev.target.files[0];
        var fr = new FileReader();

        var parent = $(this).closest('.row');
        var img = $('.option-image', parent);
        var button = $('.add-photo-button', parent);

        fr.onload = function(ev2) {
            $(img).attr('src', ev2.target.result);
            $(img).show();
            $(button).hide();
        };

        fr.readAsDataURL(f);
    });
    //click add image button image in row
    /*$(document).on('click', '.option-image', function(){

        if($('#show_photo').is(':checked')) {
            $('#popupimg').attr('src', $(this).attr('src'));
            $('#popupchangePhoto').show();
            $("#popupchangePhoto").animate({ "opacity": '1' }, "slow");
            $('#popupp').show();
            $("#popupp").animate({ "opacity": '1' }, "slow");

            current_file_obj = $('#file-image', $(this).closest('.row'));
            current_image_obj = $(this);
        }
        else {
            //alert('You can not change photo. Please check Show Photo option!');
            $("#alert-string")[0].innerText = "You can not change photo. Please check Show Photo option!";
            $("#java-alert").modal('toggle');
        }

        // $('#popupimg').attr('src', $(this).attr('src'));
        // $('#popupchangePhoto').show();
        // $("#popupchangePhoto").animate({ "opacity": '1' }, "slow");
        // $('#popupp').show();
        // $("#popupp").animate({ "opacity": '1' }, "slow");
        //
        // current_file_obj = $('#file-image', $(this).closest('.row'));
        // current_image_obj = $(this);
    });*/
    //close image modal
    /*$('#btncloselang').click(function(){
        $("#popupchangePhoto").animate({ "opacity": '0' }, "slow", function () {
            $("#popupchangePhoto").hide();
        });
        $("#popupp").animate({ "opacity": '0' }, "slow", function () {
            $("#popupp").hide();
        });
    });*/
    /*$('.addOptionbtn').click(function(){
        $("#popupchangePhoto").animate({ "opacity": '0' }, "slow", function () {
            $("#popupchangePhoto").hide();
        });
        $("#popupp").animate({ "opacity": '0' }, "slow", function () {
            $("#popupp").hide();
        });
        //current_file_obj.val($('#option_image_modal').val());
        //$(current_file_obj).remove();
        $("#alert-string")[0].innerText = $('#file-image').val();
        $("#java-alert").modal('toggle');
        var org_name = $(current_file_obj).attr('name');

        var parent = $(current_file_obj).closest('.row');
        var newFile = $('#option_image_modal').clone();
        newFile.attr('name', org_name);
        newFile.attr('id', '');
        parent.append(newFile);
        current_image_obj.attr('src', $('#popupimg').attr('src'));
    });*/
    //on add image button
    function onAddImage(obj)
    {
        //console.log($('#show_photo').is(':checked'));
        if($('#show_photo').is(':checked')) {
            var parent = $(obj).closest('.row');
            $('#file-image', parent).trigger('click');
        }
        else {
            $("#alert-string")[0].innerText = "You can not change photo. Please check Show Photo option!";
            $("#java-alert").modal('toggle');
        }

    }
    //add image button on modal
    /*$('#popupimg').click(function(){
        $('#option_image_modal').trigger('click');
    });*/
    //event on modal file input
    /*$('#option_image_modal').change(function(ev){
        var f = ev.target.files[0];
        var fr = new FileReader();
        var img = $('#popupimg');
        fr.onload = function(ev2) {
            $(img).attr('src', ev2.target.result);
            $(img).show();
        };

        fr.readAsDataURL(f);
    });*/
    var newIndex = 0;
    function onAddOption()
    {
        var div = $('.clone').clone();
        $(div).attr('id', 'aaa');
        $('.option-name', div).attr('name', 'new-option[' + newIndex + '][name]');
        $('.option-price', div).attr('name', 'new-option[' + newIndex + '][price]');
        $('.file-image', div).attr('name', 'new-option[' + newIndex + '][image]');
        $('.option-stock', div).attr('name', 'new-option[' + newIndex + '][stock]');
        $(div).show();
        $(div).removeClass('clone');
        $('#content').append(div);
        newIndex++;

    }

    function onDelete(obj)
    {
        var parent = $(obj).closest('.row');
        var id = $(obj).data('id');
        if(id == 0){
            parent.remove();
        }
        else {
            parent.hide();
            $('.old-id', parent).attr('name', 'deleted[]');
        }
    }

    function incNumber()
    {
        var cur_val = Number($('#number_selection').val());
        if($('#multi_select').is(':checked')) {
            $("#number_selection").prop('disabled', false);
            $('#number_selection').val(cur_val + 1);
        }

    }

    function decNumber()
    {
        var cur_val = Number($('#number_selection').val());
        if($('#multi_select').is(':checked')) {
            $("#number_selection").prop('disabled', false);
            if(cur_val > 1)
                $('#number_selection').val(cur_val - 1);
        }
    }

    function onDeleteMain(obj)
    {
        var url = $(obj).data('url');
        window.location = url;
    }

    function onCancel()
    {
        window.location = "{{ route('admin.option') }}";
    }

    function multi_select_change() {
        if($('#multi_select').is(':checked')) {
            $("#number_selection").prop('disabled', false);
        } else {
            $("#number_selection").prop('disabled', true);
        }
    }

    function item_price_change(id) {

        var item_price = $('#item_price_' + id).val();
        //alert(id + ' ' + item_price);
        var gst = $("#gst").val();
        var gst_include = 0;
        if(item_price != 0){
            gst_include = item_price*gst / 100;
        }

        $('#gst_value_' + id).html('(税: ¥ '+Math.round(gst_include)+')');

    }

</script>
@endsection
