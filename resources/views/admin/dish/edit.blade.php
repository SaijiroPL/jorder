@extends('layout.admin_layout')

@section('title', 'DISH')

@section('content')
    <style>
        .option-padding {
            padding-top : 0.6rem;
            padding-bottom : 0.6rem;
        }
    </style>
    {{--<div class="container-fluid pb-3 blackgrey" style="height: auto;">--}}
    <div class="container-fluid pb-3 blackgrey">
        <form method="POST" action="{{ route('admin.dish.store') }}" enctype='multipart/form-data' name="edit_dish" onSubmit="return validateform()">
            <input type="hidden" value="{{ $obj->id }}" name="id">
            <input type="hidden" name="category_id" id="checked_ids" value="{{isset($dish_cats_ids) ? $dish_cats_ids : ''}}">
            <div style="padding-top:8%;">
            </div>
            {{--<div class="widthh pt-3 pb-3 mb-3 white" style="height: auto;">--}}
            <div class="widthh pt-3 pb-3 mb-3 white" style="height: 90vh;overflow: auto;">
                <div class="row">
                    <div class="col-11">
                    </div>
                    <div class="col-1">
                        <a href="
                        @if(isset($obj->id))
                            {{route('admin.dish.preview',['id'=>$obj->id])}}
                        @else
                            {{ route('admin.dish') }}
                        @endif">
                            <span class="">
                                <img src="{{ asset('img/Group1100.png') }}" width="25" height="25" class="float-right" width="20" />
                            </span>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="row pl-4 pr-4 pt-3" >
                        <div class="col-6">
                            <div class="form-group">
                                <div>
                                    <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.Name_of_dish') }}</label>
                                </div>
                                <textarea class="outline-0 border-blue h4rem pl-3" style="font-size: 20px;width: 40vw; height: 95px;" name="name_jp" id="name_jp">{{ $obj->name_jp }}</textarea>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.Name_of_dish_M') }}</label>
                                </div>
                                <textarea class="outline-0 border-blue h4rem pl-3" style="font-size: 20px;width: 40vw; height: 95px;" name="name_cn" id="name_cn">{{ $obj->name_cn }}</textarea>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.Name_of_dish_E') }}</label>
                                </div>
                                <textarea class="outline-0 border-blue h4rem pl-3" style="font-size: 20px;width: 40vw; height: 95px;" name="name_en" id="name_en">{{ $obj->name_en }}</textarea>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.Description') }}</label>
                                </div>
                                <textarea class="outline-0 border-blue h4rem pl-3" style="font-size: 20px;width: 40vw; height: 150px;" name="desc_en">{{ $obj->desc_en }}</textarea>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.Description_M') }}</label>
                                </div>
                                <textarea class="outline-0 border-blue h4rem pl-3" style="font-size: 20px;width: 40vw; height: 150px;" name="desc_cn">{{ $obj->desc_cn }}</textarea>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.Description_J') }}</label>
                                </div>
                                <textarea class="outline-0 border-blue h4rem pl-3" style="font-size: 20px;width: 40vw; height: 150px;" name="desc_jp">{{ $obj->desc_jp }}</textarea>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.PRICE') }}</label>
                                </div>
                                <input type="number" class="outline-0 border-blue pl-3" style="padding:10px 0 10px 0;font-size: 20px;" name="price" id="price" step="0.01" value="{{ $obj->price }}" />
                                <input type="hidden" value="{{ $gst }}" id="gst" name="gst">
                                <p class="text-right text-blue fs-20" id="gst_value">(税: ¥ {{ round($obj->price*$gst/100, 0) }})</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="addphoto mt-5">
                                <button class="create_addPhotobtn btn bg-info radius pt-2 pb-2 pr-4 pl-4 waves-effect waves-light fs-20" type="button" id="btn_add_image" onclick="setPhoto()"
                                        @if($obj->image != null)
                                        style="display:none"
                                        @endif
                                        >{{ __('admin.Dish.Photo').__('admin.Common.Add') }}</button>
                                <img id="main_img" width="100%" height="100%"
                                     @if($obj->image != null)
                                     src="{{ asset('dishes/'.$obj->image) }}"
                                        @endif
                                        >
                                <input type="file" id="image_file" name="image" style="display:none">
                            </div>
                            <button class="create_changePhotobtn fs-20" type="button" id="btn_change_image" onclick="setPhoto()"
                                    @if($obj->image == null)
                                    style="display:none"
                                    @endif
                                    >{{ __('admin.Dish.Photo').__('admin.Common.Update') }}</button>
                        </div>
                    </div>
                    <div class="row pl-4">
                        <div class="col-8" id="content">
                            <label class="text-blue txtdemibold fs-20">{{ __('admin.Bottom.OPTION') }}</label>
                            @foreach ($obj->options as $opt)
                                <div class="mt-2 option-element">
                                    <select class="border-blue select-width-blue mr-1 option-padding option-select pl-3 fs-20" name="opts[]" id="opts">
                                        @foreach ($options as $o)
                                            <option value="{{ $o->id }}"
                                                    @if($opt->id == $o->id)
                                                    selected
                                                    @endif
                                                    >{{ $o->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btndeletebehind mt-2 fs-20" type="button" onclick="onDeleteOption(this)">{{ __('admin.Common.Delete') }}</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row pl-5">
                        <button class="create_changePhotobtn fs-20" type="button" onclick="onAddOption()">
                            {{ __('admin.Bottom.OPTION').__('admin.Common.Add') }}
                        </button>
                    </div>
                    <div class="mt-2 option-element display-none" id="clone">
                        <select class="border-blue select-width-blue mr-1 option-padding option-select pl-3 fs-20" name="option">
                            @foreach ($options as $o)
                                <option value="{{ $o->id }}">{{ $o->name }}</option>
                            @endforeach
                        </select>
                        <button class="btndeletebehind mt-2 fs-20" type="button" onclick="onDeleteOption(this)">{{ __('admin.Common.Delete') }}</button>
                    </div>
                    <div class="row category_content pl-4">
                        <div class="col-8">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="text-blue txtdemibold fs-20">{{ __('admin.Bottom.CATEGORY') }}</label>
                                    </div>
                                    <div class="col-6">
                                        <label class="text-blue txtdemibold fs-20">{{ __('admin.Category.Sub_Category') }}</label>
                                    </div>
                                </div>
                                @foreach ($main_cats as $key => $cat)
                                    <div class="border-bottom-blue category_contents main_category_{{ $cat->id }}"
                                         @if(isset($dish_cats))
                                            @if(!in_array($cat->id, $dish_cats))
                                                style="display: none;"
                                            @endif
                                        @else
                                            style="display: none;"
                                        @endif>
                                        <div class="row">
                                            <div class="col-6" id="category_{{ $cat->id }}">
                                                <label class="txtdemibold mt-2 fs-20">{{ $cat->name_en }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    @if(count($main_cats[$key]->subs) > 0)
                                        @foreach ($main_cats[$key]->subs as $subcat)
                                            <div class="border-bottom-blue category_contents category_{{ $cat->id }}"
                                                 @if(isset($dish_cats))
                                                    @if(!in_array($subcat->id, $dish_cats))
                                                        style="display: none;"
                                                    @endif
                                                @else
                                                 style="display: none;"
                                                @endif
                                                    >
                                                <div class="row">
                                                    <div class="col-6" id="category_{{ $cat->id }}">
                                                        <label class="txtdemibold mt-2 fs-20">{{ $cat->name_en }}</label>
                                                    </div>
                                                    <div class="col-6" id="category_{{ $subcat->id }}">
                                                        <label class="txtdemibold mt-2 fs-20">{{ $subcat->name_en }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                            <div class="row pl-4">
                                <button class="create_changePhotobtn fs-20" type="button" id="edit-category-btn" data-target="#addModal">
                                    {{ __('admin.Bottom.CATEGORY').__('admin.Common.Edit') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row pl-4">
                        <div class="col-8" id="group-content">
                            <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.GROUP') }}</label>
                            @if($obj->groups)
                                @foreach ($obj->groups as $grp)
                                    <div class="mt-2 group-element">
                                        <select class="border-blue select-width-blue mr-1 option-padding group-select pl-3 fs-20" name="groups[]" id="groups" style="width: 585px;">
                                            @foreach ($groups as $g)
                                                <option value="{{ $g->id }}"
                                                        @if(substr($grp, 1, -1) == $g->id)
                                                        selected
                                                        @endif
                                                >{{ $g->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btndeletebehind mt-2 fs-20" type="button" onclick="onDeleteGroup(this)">{{ __('admin.Common.Delete') }}</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row pl-5">
                        <button class="create_changePhotobtn fs-20" type="button" onclick="onAddGroup()">
                            {{ __('admin.Common.Add').__('admin.Dish.GROUP') }}
                        </button>
                    </div>
                    <div class="mt-2 group-element display-none" id="group-clone">
                        <select class="border-blue select-width-blue mr-1 option-padding group-select pl-3 fs-20" name="group">
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                        <button class="btndeletebehind mt-2 fs-20" type="button" onclick="onDeleteGroup(this)">{{ __('admin.Common.Delete') }}</button>
                    </div>

                    <div class="row pl-4">
                        <div class="col-6">
                            <div class="form-group">
                                <div>
                                    <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.Badge') }}</label>
                                </div>
                                <select type="text" class="outline-0 border-blue w-100 option-padding pl-3 fs-20" name="badge_id">
                                    <option value="">{{ __('admin.Dish.Choose_Badge') }}</option>
                                    @foreach ($badges as $b)
                                        <option value="{{ $b->id }}"
                                                @if($b->id == $obj->badge_id)
                                                selected
                                                @endif
                                                > {{ $b->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5 pl-4 pr-4">
                        <div class="col-6">
                            <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.Eat_In') }}</label>
                            <div class="border-bottom-blue">
                                <div class="row">
                                    <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Breakfast') }}</label></div>
                                    <div class="col-4">
                                        <div class="float-right mt-2">
                                            <label class="bs-switch ">
                                                <input type="checkbox" name="eatin_breakfast"
                                                       @if($obj->eatin_breakfast == 1)
                                                       checked
                                                        @endif
                                                        >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom-blue">
                                <div class="row">
                                    <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Lunch') }}</label></div>
                                    <div class="col-4">
                                        <div class="float-right mt-2">
                                            <label class="bs-switch ">
                                                <input type="checkbox" name="eatin_lunch"
                                                       @if($obj->eatin_lunch == 1)
                                                       checked
                                                        @endif
                                                        >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom-blue">
                                <div class="row">
                                    <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Tea') }}</label></div>
                                    <div class="col-4">
                                        <div class="float-right mt-2">
                                            <label class="bs-switch ">
                                                <input type="checkbox" name="eatin_tea"
                                                       @if($obj->eatin_tea == 1)
                                                       checked
                                                        @endif
                                                        >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom-blue">
                                <div class="row">
                                    <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Dinner') }}</label></div>
                                    <div class="col-4">
                                        <div class="float-right mt-2">
                                            <label class="bs-switch ">
                                                <input type="checkbox" name="eatin_dinner"
                                                       @if($obj->eatin_dinner == 1)
                                                       checked
                                                        @endif
                                                        >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="text-blue txtdemibold fs-20">{{ __('admin.Dish.Takeaway') }}</label>
                            <div class="border-bottom-blue">
                                <div class="row">
                                    <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Breakfast') }}</label></div>
                                    <div class="col-4">
                                        <div class="float-right mt-2">
                                            <label class="bs-switch ">
                                                <input type="checkbox" name="takeaway_breakfast"
                                                       @if($obj->takeaway_breakfast == 1)
                                                       checked
                                                        @endif
                                                        >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom-blue">
                                <div class="row">
                                    <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Lunch') }}</label></div>
                                    <div class="col-4">
                                        <div class="float-right mt-2">
                                            <label class="bs-switch ">
                                                <input type="checkbox" name="takeaway_lunch"
                                                       @if($obj->takeaway_lunch == 1)
                                                       checked
                                                        @endif
                                                        >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom-blue">
                                <div class="row">
                                    <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Tea') }}</label></div>
                                    <div class="col-4">
                                        <div class="float-right mt-2">
                                            <label class="bs-switch ">
                                                <input type="checkbox" name="takeaway_tea"
                                                       @if($obj->takeaway_tea == 1)
                                                       checked
                                                        @endif
                                                        >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="border-bottom-blue">
                                <div class="row">
                                    <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{ __('admin.Dish.Dinner') }}</label></div>
                                    <div class="col-4">
                                        <div class="float-right mt-2">
                                            <label class="bs-switch ">
                                                <input type="checkbox" name="takeaway_dinner"
                                                       @if($obj->takeaway_dinner == 1)
                                                       checked
                                                        @endif
                                                        >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5 mb-5 pl-4 pr-4">
                        <div class="col-7 mt-4">
                            @if(isset($obj->id))
                                <a onclick="deleteConfirmModal();" class="grey-button fs-20" style="color:black;padding:10px 25px 10px 25px;">
                                    {{ __('admin.Common.Delete') }}
                                    <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                                </a>
                            @endif
                        </div>
                        <div class="col-5 mt-4">
                            <a @if(isset($obj->id))
                                href="{{route('admin.dish.preview', ['id' => $obj->id])}}"
                               @else
                                href="{{route('admin.dish')}}"
                               @endif
                               class="grey-button ml-5 fs-20" style="padding:15px 25px 15px 25px;color:black;">
                               {{ __('admin.Common.Cancel') }}
                               <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                            </a>
                            <button class="green-button fs-20" style="padding: 12px 23px 12px 25px;margin-top: -12px;">
                                {{ __('admin.Common.Apply') }}
                                <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @csrf
        </form>
    </div>
    <div class="modal fade" id="editCategoryModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="cancelCategory()" aria-label="Close">
                        <img style="width:20px;height:20px;" src="{{asset("img/Group1100.png")}}">
                    </button>
                </div>
                {{--<div class="row col-12 ml-1">--}}
                    {{--<div class="col-6" style="display: inline-block;">--}}
                        {{--<label class="fs-20 fn-bold pt-3 pb-2">SELECT CATEGORY</label>--}}
                        {{--<div class="modal-body pr-4" style="height: 500px;overflow-y:auto;">--}}
                            {{--@foreach ($main_cats as $key => $cat)--}}
                                {{--<div style="position: relative;">--}}
                                    {{--<label class="checkbox-container fs-20" id="checkbox-label">--}}
                                        {{--<input type="checkbox" id="select_all{{$cat->id}}" class="common_checked for_checked{{$cat->id}}" onclick="selectParent({{$cat->id}})"/>--}}
                                        {{--<span class="checkmark"></span>--}}
                                        {{--{{ $cat->name_en }}--}}
                                    {{--</label>--}}
                                {{--</div>--}}
                            {{--@endforeach--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-6" style="display: inline-block;">--}}
                        {{--<label class="fs-20 fn-bold pt-3 pb-2"><b>SELECT SUB CATEGORY</b></label>--}}
                        {{--<div class="modal-body pr-4" style="height: 500px;overflow-y:auto;">--}}
                            {{--@foreach ($main_cats as $key => $cat)--}}
                                {{--<div style="position: relative;">--}}
                                    {{--@if(count($main_cats[$key]->subs) > 0)--}}
                                        {{--<label class="checkbox-container fs-20" id="checkbox-label">--}}
                                            {{--{{ $cat->name_en }}--}}
                                        {{--</label>--}}
                                        {{--<img class="header{{$cat->id}}" style="width:24px;height:25px;position:absolute;left:1px;top:5px;" src="{{asset("img/expand.png")}}" onclick="showChild({{$cat->id}})">--}}
                                    {{--@endif--}}
                                    {{--@foreach ($main_cats[$key]->subs as $sub_cat)--}}
                                        {{--<div class="content{{$cat->id}}" style="padding:5px;margin-left: 20px;">--}}
                                            {{--<label class="checkbox-container fs-20">--}}
                                                {{--<input class="checkbox{{$cat->id}} common_checked for_checked{{$sub_cat->id}}" type="checkbox"  onclick="childCheck('{{$cat->id}}', '{{$sub_cat->id}}', this)" name="check[]" style="margin-left:50px;">--}}
                                                {{--{{$sub_cat->name_en}}--}}
                                                {{--<span class="checkmark"></span>--}}
                                            {{--</label>--}}
                                        {{--</div>--}}
                                    {{--@endforeach--}}
                                {{--</div>--}}
                            {{--@endforeach--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="col-12">
                    <div class="modal-body pr-4" style="height: 500px;overflow-y:auto;">
                        @foreach ($main_cats as $key => $cat)
                            <div style="position: relative;">
                                <label class="checkbox-container fs-20" id="checkbox-label">
                                    @if(count($main_cats[$key]->subs) == 0)
                                        <input type="checkbox" id="select_all{{$cat->id}}" class="common_checked for_checked{{$cat->id}}" onclick="selectParent({{$cat->id}})"/>
                                        <span class="checkmark"></span>
                                    @endif
                                    {{ $cat->name_en }}
                                </label>
                                @if(count($main_cats[$key]->subs) > 0)
                                    <img class="header{{$cat->id}}" style="width:24px;height:25px;position:absolute;left:1px;top:5px;" src="{{asset("img/expand.png")}}" onclick="showChild({{$cat->id}})">
                                @endif
                                @foreach ($main_cats[$key]->subs as $sub_cat)
                                    <div class="content{{$cat->id}}" style="padding:5px;margin-left: 20px;">
                                        <label class="checkbox-container fs-20">
                                            <input class="checkbox{{$cat->id}} common_checked for_checked{{$sub_cat->id}}" type="checkbox"  onclick="childCheck('{{$cat->id}}', '{{$sub_cat->id}}', this)" name="check[]" style="margin-left:50px;">
                                            {{$sub_cat->name_en}}
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect waves-light fs-20" onclick="cancelCategory()">
                        {{ __('admin.Common.Cancel') }}
                        <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                    </button>
                    <button type="button" class="btn btn-primary waves-effect waves-light fs-20" onclick="saveCategory()">
                        {{ __('admin.Common.Apply') }}
                        <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                    </button>
                </div>
            </div>
        </div>
    </div>
{{--Delete Dish Confirm Dialog--}}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img style="width:10px;height:10px;" src="{{asset("img/Group1100.png")}}">
                    </button>
                </div>
                <p style="text-align: center;padding: 20px;font-size: 25px;border-bottom:1px solid #e9ecef">Do you want to delete it?</p>
                <div style="text-align: center;margin-bottom:15px;">
                    <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                        {{ __('admin.Common.Cancel') }}
                        <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                    </button>
                    <button type="button" class="btn btn-primary waves-effect waves-light fs-20" style="padding: 15px;width: 25%;" onclick="deleteDish()">
                        {{ __('admin.Common.Apply') }}
                        <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var checkedIds = $("#checked_ids").val();
        var checkedIds_tmp = checkedIds;

        $("#price").change(function(){
            var price = $("#price").val();
            var gst = $("#gst").val();
            var gst_include = 0;
            if(price > 0){
                gst_include = price*gst / 100;
            }
            $("#gst_value")[0].innerText = '(税: ¥ '+gst_include.toFixed(0)+')';
        });

        $(document).ready(function(){
            // console.log($('#mcategory').val());
            @if($obj->id == null)
                $('#mcategory').trigger('change');
            @endif
        });
        $('#mcategory').change(function(){
            var main = $(this).val();
            $.ajax({
                type:"POST",
                url:"{{ route('admin.category.subs') }}",
                data:{
                    parent:main,
                    _token:"{{ csrf_token() }}"
                },
                success: function(result){
                    $('#scategory').html(result);
                }
            });
        });
        function setPhoto()
        {
            $('#image_file').trigger('click');
        }
        $('#image_file').change(function(ev){
            var f = ev.target.files[0];
            var fr = new FileReader();
            var img = $('#main_img');
            fr.onload = function(ev2) {
                $(img).attr('src', ev2.target.result);
                $(img).show();
                $('#btn_add_image').hide();
                $('#btn_change_image').show();
            };

            fr.readAsDataURL(f);
        });
        function onAddOption()
        {
            var div = $('#clone').clone();
            $('.option-select', div).attr('name', 'opts[]');
            $('.option-select', div).attr('id', 'opts');
            $(div).css("display", "block");
            $('#content').append(div);
        }
        function onDeleteOption(obj)
        {
            var parent = $(obj).closest('.option-element');
            $(parent).remove();
        }
        $("#edit-category-btn").click(function(){

            // var aa = '1,2';
            // var bb = aa.split(',');
            // console.log(bb.length);

            var checked_ids_arr = '';
            if(checkedIds != ''){
                if(checkedIds.length >= 2){
                    checked_ids_arr = checkedIds.split(',');
                }else{
                    checked_ids_arr = checkedIds;
                }
                // checked_ids_arr = checkedIds.split(',');
                for(var i = 0; i < checked_ids_arr.length; i ++){
                    $(".for_checked"+ checked_ids_arr[i])[0].checked = true;
                }
            }

            $("#editCategoryModal").modal("toggle");
        });

        function saveCategory() {
            checkedIds = checkedIds_tmp;
            $("#checked_ids").val(checkedIds);
            $("#editCategoryModal").modal("hide");
        }
        function cancelCategory() {
            checkedIds_tmp = checkedIds;
            var checked_ids_arr = '';
            if(checkedIds != ''){
                if(checkedIds.length >= 2){
                    checked_ids_arr = checkedIds.split(',');
                }else{
                    checked_ids_arr = checkedIds;
                }
            }
            $(".category_contents").css('display', 'none');
            var common_checked = $(".common_checked");
            for(var i = 0; i < common_checked.length; i ++){
                common_checked[i].checked = false;
            }
            for(var i = 0; i < checked_ids_arr.length; i ++){
                $(".main_category_"+checked_ids_arr[i]).css('display', 'block');
                $("#category_"+checked_ids_arr[i]).parent().parent().css('display', 'block');
            }
            $("#editCategoryModal").modal("hide");
        }
        function selectParent(index)
        {
            if($("#select_all"+index)[0].checked == true){//add checked id
                $(".main_category_"+index).css('display', 'block');
                if(checkedIds_tmp != ''){
                    var count = 0;
                    var common_checked_count = 0;
                    var common_checked_obj = $(".common_checked");
                    for(var i = 0; i < common_checked_obj.length; i ++){
                        if(common_checked_obj[i].checked == true){
                            common_checked_count ++;
                        }
                    }
                    var tmp_ids = '';
                    checkedIds_tmp += ',' + index;
                    if(common_checked_count >= 2){
                        tmp_ids = checkedIds_tmp.split(',');
                    }else{
                        tmp_ids = checkedIds_tmp;
                    }
                    for(var i = 0; i < tmp_ids.length; i ++){
                        if(tmp_ids[i] == index){
                            count ++;
                        }
                    }
                    // if(count == 0){
                    //     checkedIds_tmp += ',' + index;
                    // }
                }else{
                    checkedIds_tmp = index;
                }
            }else{//remove checked id
                $(".main_category_"+index).css('display', 'none');
                var ids_tmp = '';
                if(checkedIds_tmp != ''){
                    if(checkedIds_tmp.length >=2) {
                        ids_tmp = checkedIds_tmp.split(',');
                    }
                    else {
                        ids_tmp = checkedIds_tmp;
                    }
                }

                checkedIds_tmp = '';
                for(var i = 0; i < ids_tmp.length; i ++){
                    if(ids_tmp[i] != index){
                        if(checkedIds_tmp == ''){
                            checkedIds_tmp = ids_tmp[i];
                        }else{
                            checkedIds_tmp += ','+ids_tmp[i];
                        }
                    }
                }
            }

        }
        // function selectParent(index)
        // {
        //
        //     var checkObj = $(".checkbox"+index);
        //     var checkedCount = 0;
        //     if($(".content"+index).length == 0){
        //         for(var i = 0; i < checkObj.length; i ++){
        //             if(checkObj[i].checked == true){
        //                 checkedCount ++;
        //             }
        //         }
        //         if(checkedCount > 0){
        //             $("#select_all"+index)[0].checked = true;
        //         }
        //         if($("#select_all"+index)[0].checked == true){//add checked id
        //             $(".main_category_"+index).css('display', 'block');
        //             if(checkedIds_tmp != ''){
        //                 var count = 0;
        //                 var common_checked_count = 0;
        //                 var common_checked_obj = $(".common_checked");
        //                 for(var i = 0; i < common_checked_obj.length; i ++){
        //                     if(common_checked_obj[i].checked == true){
        //                         common_checked_count ++;
        //                     }
        //                 }
        //                 var tmp_ids = '';
        //                 if(common_checked_count >= 2){
        //                     tmp_ids = checkedIds_tmp.split(',');
        //                 }else{
        //                     tmp_ids = checkedIds_tmp;
        //                 }
        //                 for(var i = 0; i < tmp_ids.length; i ++){
        //                     if(tmp_ids[i] == index){
        //                         count ++;
        //                     }
        //                 }
        //                 if(count == 0){
        //                     checkedIds_tmp += ',' + index;
        //                 }
        //             }else{
        //                 checkedIds_tmp = index;
        //             }
        //
        //         }else{//remove checked id
        //             $(".main_category_"+index).css('display', 'none');
        //             var ids_tmp = '';
        //             if(checkedIds_tmp != ''){
        //                 if(checkedIds_tmp.length >=2) {
        //                     ids_tmp = checkedIds_tmp.split(',');
        //                 }
        //                 else {
        //                     ids_tmp = checkedIds_tmp;
        //                 }
        //             }
        //
        //             checkedIds_tmp = '';
        //             for(var i = 0; i < ids_tmp.length; i ++){
        //                 if(ids_tmp[i] != index){
        //                     if(checkedIds_tmp == ''){
        //                         checkedIds_tmp = ids_tmp[i];
        //                     }else{
        //                         checkedIds_tmp += ','+ids_tmp[i];
        //                     }
        //                 }
        //             }
        //         }
        //     }
        //
        // }
        function childCheck(category_index, sub_index, obj)
        {
            if(obj.checked == true){
                /*if($("#select_all"+category_index)[0].checked == false){
                    //$("#select_all"+category_index)[0].checked = true;
                    if(checkedIds_tmp == ''){
                        checkedIds_tmp = category_index;
                    }else{
                        checkedIds_tmp += ','+ category_index;
                    }
                }*/
                if(checkedIds_tmp == ''){
                    checkedIds_tmp = sub_index;
                }else{
                    checkedIds_tmp += ','+ sub_index;
                }
                //$(".main_category_"+category_index).css('display', 'block');
                $("#category_"+sub_index).parent().parent().css('display', 'block');
            }else{
                $("#category_"+sub_index).parent().parent().css('display', 'none');
                var ids_tmp = '';
                if(checkedIds_tmp != ''){
                    ids_tmp = checkedIds_tmp.split(',');
                }
                checkedIds_tmp = '';
                for(var i = 0; i < ids_tmp.length; i ++){
                    if(ids_tmp[i] != sub_index){
                        if(checkedIds_tmp == ''){
                            checkedIds_tmp = ids_tmp[i];
                        }else{
                            checkedIds_tmp += ','+ids_tmp[i];
                        }
                    }
                }
            }
        }
        function showChild(index)
        {
            var header_obj = $(".header"+index);
            var content_obj = $(".content"+index);
            content_obj.slideToggle(500, function () {
                header_obj.text(function () {
                    return content_obj.is(":visible") ? header_obj.attr("src", "{{asset('img/expand.png')}}") : header_obj.attr("src", "{{asset('img/collapse.png')}}");
                });
            });
        }

        //group
        function onAddGroup()
        {
            var div = $('#group-clone').clone();
            $('.group-select', div).attr('name', 'groups[]');
            $('.group-select', div).attr('id', 'groups');
            $(div).css("display", "block");
            $('#group-content').append(div);
        }
        function onDeleteGroup(obj)
        {
            var parent = $(obj).closest('.group-element');
            $(parent).remove();
        }

        function deleteConfirmModal()
        {
            $("#deleteModal").modal("toggle");
        }

        function deleteDish()
        {
            location.href ="{{route('admin.dish.delete', ['id' => $obj->id])}}";
        }

        function validateform() {

            var name_en = $("#name_en").val();
            var name_cn = $("#name_cn").val();
            var name_jp = $("#name_jp").val();
            var group = $("#group").val();
            var price = $("#price").val();
            // var opts = $("#opts").val();
            var category = $("#checked_ids").val();
            // var opts = e.options[e.selectedIndex].value;
            if(!name_jp) {
                //alert('Please input Name of dish!');
                $("#alert-string")[0].innerText = "料理名を入力してください！";
                $("#java-alert").modal('toggle');
                return false;
            }
            // else if(!name_cn) {
            //     //alert('Please input Name of dish(Mandarine)!');
            //     $("#alert-string")[0].innerText = "料理名（みかん）を入力してください！";
            //     $("#java-alert").modal('toggle');
            //     return false;
            // } else if(!name_en) {
            //     //alert('Please input Name of dish(Japanese)!');
            //     $("#alert-string")[0].innerText = "料理名（英語）を入力してください！";
            //     $("#java-alert").modal('toggle');
            //     return false;
            // }
            // else if(!group) {
            //     alert('Please select a Group!');
            //     return false;
            // }
            else if(!price) {
                //alert('Please select a Price!');
                $("#alert-string")[0].innerText = "価格を選択してください！";
                $("#java-alert").modal('toggle');
                return false;
            } else if(!category) {
                //alert('Please select a Category!');
                $("#alert-string")[0].innerText = "カテゴリを選択してください！";
                $("#java-alert").modal('toggle');
                return false;
            } //else if(!opts) {
            //     alert('Please select a Option!');
            //     return false;
            // }
            return true;
        }
    </script>
@endsection
