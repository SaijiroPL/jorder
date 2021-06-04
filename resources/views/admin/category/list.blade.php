@extends('layout.admin_layout')

@section('title', 'DISH')

@section('content')
<style>
    .category-div{
        height:48vh;
        overflow-y:auto;
        overflow-x:hidden;
        width:95%;
        margin:0 auto;
    }
    .align-center {
        text-align: center;
    }
</style>

<div class="">
    <div style="padding-top:8%;"></div>

    <div class="widthh blackgrey pt-4" style="height: 90vh;">
        <a onclick="window.history.back()">
            <h2><span class="">
                <img src="{{ asset('img/Group826.png') }}"  style="width:25px;height:25px;" class="float-right" />
            </span></h2>
        </a>
        <div class="pt-5">
            <div class="row" style="height: 66vh;">
                <div class="col-7">
                    <div class="row">
                        <div style="border-right:1px solid grey" class="col-6 pl-0 pr-3">
                            <h5 class="white-text font-weight-bold pl-2 fs-20" style="width:90%; margin:0 auto">{{ __('admin.Bottom.CATEGORY') }}</h5>
                            <h6 class="hspace-category" style="margin:0px;height: 41px;"></h6>
                            <div class="category-div" id="category-scroll">
                                @foreach($categories as $cat)
                                    @include('part.category_item')
                                @endforeach
                            </div>
                            <div class="col-lg-12 pl-0 pr-0 mt-4 pt-2 align-center">
                                <button class="btn bg-info radius pt-2 pb-2 pr-2 pl-2 waves-effect waves-light" data-toggle="modal" data-target="#addCategoryModal">
                                    <h6 class="mb-0 font-weight-bold fs-20">{{ __('admin.Common.Add') }}</h6>
                                </button>
                                <button class="btn bg-info radius pt-2 pb-2 pr-3 pl-3 waves-effect waves-light" onclick="onMainTitleEdit()">
                                    <h6 class="mb-0 font-weight-bold fs-20">{{ __('admin.Common.Edit') }}</h6>
                                </button>
                                <button class="btn black radius pt-2 pb-2 pr-3 pl-3 waves-effect waves-light" id="deleteMainCategory">
                                    <h6 class="mb-0 font-weight-bold fs-20">{{ __('admin.Common.Delete') }}</h6>
                                </button>
                            </div>
                            <input type="hidden" id="add_flag" name="add_flag">
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                                        </button>
                                    </div>
                                    <div class="modal-body pr-4">
                                        <h5 class="text-info font-weight-normal fs-20">English</h5>
		                                <input class="form-control pl-3" style="font-size: 25px;" type="text" name="name_en_cat" id="name_en_cat">
		                                <h5 class="text-info font-weight-normal fs-20">Mandarine</h5>
		                                <input class="form-control pl-3" style="font-size: 25px;" type="text" name="name_cn_cat" id="name_cn_cat">
                                        <h5 class="text-info font-weight-normal fs-20">Japanese</h5>
                                        <input class="form-control pl-3" style="font-size: 25px;" type="text" name="name_jp_cat" id="name_jp_cat">
                                        <h6 class="d-inline fs-20">
                                            無制限
                                            <label class="switch-style" style="margin-top:10px">
                                                <input type="checkbox" id="is_unlimited" name="is_unlimited">
                                                <span class="slider checkbox-round"></span>
                                            </label>
                                        </h6>
                                        <input type="hidden" id="parent_id" name="parent_id">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                                            {{ __('admin.Common.Cancel') }}
                                            <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                                        </button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light fs-20" onclick="addMainCategory()">
                                            {{ __('admin.Common.Apply') }}
                                            <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="editCategoryTitleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                                        </button>
                                    </div>
                                    <div class="modal-body pr-4">
                                        <h5 class="text-info font-weight-normal fs-20">English</h5>
		                                <input class="form-control pl-3" style="font-size: 25px;" type="text" name="current_en_cat" id="current_en_cat">
		                                <h5 class="text-info font-weight-normal fs-20">Mandarine</h5>
		                                <input class="form-control pl-3" style="font-size: 25px;" type="text" name="current_cn_cat" id="current_cn_cat">
                                        <h5 class="text-info font-weight-normal fs-20">Japanese</h5>
                                        <input class="form-control pl-3" style="font-size: 25px;" type="text" name="current_jp_cat" id="current_jp_cat">
                                        <h6 class="d-inline fs-20">
                                            無制限
                                            <label class="switch-style" style="margin-top:10px">
                                                <input type="checkbox" id="current_is_unlimited" name="is_unlimited">
                                                <span class="slider checkbox-round"></span>
                                            </label>
                                        </h6>
                                        <input type="hidden" id="current_parent_id" name="current_parent_id">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                                            {{ __('admin.Common.Cancel') }}
                                            <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                                        </button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light fs-20" onclick="onTitleCng()">
                                            {{ __('admin.Common.Apply') }}
                                            <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                                        </button>
                                    </div>
                                    <div class="modal-body pr-4">
                                        <h5 class="text-info font-weight-normal fs-20">English</h5>
                                        <input class="form-control pl-3" style="font-size: 25px;" type="text" name="name_en" id="name_en">
                                        <h5 class="text-info font-weight-normal fs-20">Mandarine</h5>
                                        <input class="form-control pl-3" style="font-size: 25px;" type="text" name="name_cn" id="name_cn">
                                        <h5 class="text-info font-weight-normal fs-20">Japanese</h5>
                                        <input class="form-control pl-3" style="font-size: 25px;" type="text" name="name_jp" id="name_jp">
                                        <input type="hidden" id="parent_id" name="parent_id">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                                            {{ __('admin.Common.Cancel') }}
                                            <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                                        </button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light fs-20" onclick="addCategory()">
                                            {{ __('admin.Common.Apply') }}
                                            <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="editSubTitleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                                        </button>
                                    </div>
                                    <div class="modal-body pr-4">
                                        <h5 class="text-info font-weight-normal fs-20">English</h5>
		                                <input class="form-control pl-3" style="font-size: 25px;" type="text" name="current_sub_en_cat" id="current_sub_en_cat">
		                                <h5 class="text-info font-weight-normal fs-20">Mandarine</h5>
		                                <input class="form-control pl-3" style="font-size: 25px;" type="text" name="current_sub_cn_cat" id="current_sub_cn_cat">
                                        <h5 class="text-info font-weight-normal fs-20">Japanese</h5>
                                        <input class="form-control pl-3" style="font-size: 25px;" type="text" name="current_sub_jp_cat" id="current_sub_jp_cat">
                                        <input type="hidden" id="current_sub_parent_id" name="current_sub_parent_id">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                                            {{ __('admin.Common.Cancel') }}
                                            <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                                        </button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light fs-20" onclick="onSubTitleCng()">
                                            {{ __('admin.Common.Apply') }}
                                            <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6" style="border-right:1px solid grey">
                            <h5 class="white-text font-weight-bold pl-2 fs-20" style="width:90%; margin:0 auto">{{ __('admin.Category.Sub_Category') }}</h5>
                            <h6 class="white-text d-inline pl-4 fs-20">{{ __('admin.Category.Use_Sub_Category') }}
                                <label class="switch-style" style="margin-top:3px">
                                    <input type="checkbox" id="chk_hassubs">
                                    <span class="slider checkbox-round"></span>
                                </label>
                            </h6>
                            <br>
                            <div class="category-div pr-2" id="subcategory-scroll" style="margin-top: 7px;">

                            </div>
                            <div class="col-lg-12 pl-0 pr-0 mt-4 pt-2 align-center">
                                <button class="btn bg-info radius pt-2 pb-2 pr-2 pl-2 waves-effect waves-light" onclick="onSubAdd()">
                                    <h6 class="mb-0 font-weight-bold fs-20">{{ __('admin.Common.Add') }}</h6>
                                </button>
                                <button class="btn bg-info radius pt-2 pb-2 pr-3 pl-3 waves-effect waves-light" onclick="onSubTitleEdit()">
                                    <h6 class="mb-0 font-weight-bold fs-20">{{ __('admin.Common.Edit') }}</h6>
                                </button>
                                <button class="btn black radius pt-2 pb-2 pr-3 pl-3 waves-effect waves-light" id="deleteSubCategory">
                                    <h6 class="mb-0 font-weight-bold fs-20">{{ __('admin.Common.Delete') }}</h6>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5 pl-2 pr-5" style="width:85%">
                    <h5 class="white-text font-weight-bold pl-2 fs-20" style="width:90%; margin:0 auto">{{ __('admin.Bottom.DISH') }}</h5>
                    <h6 class="hspace-category" style="margin:0px;height: 41px;"></h6>
                    <div class="category-div pt-div" id="scroll-dish">

                    </div>
                    <div class="col-lg-12 pl-0 pr-0 mt-4 pt-2 align-center">
                        <button class="btn bg-info radius pt-2 pb-2 pr-4 pl-4 waves-effect waves-light" onclick="onAddDish()">
                            <h6 class="mb-0 font-weight-bold fs-20">{{ __('admin.Common.Add') }}</h6>
                        </button>
                        <button class="btn black radius pt-2 pb-2 pr-4 pl-4 waves-effect waves-light" id="deleteDishModal">
                            <h6 class="mb-0 font-weight-bold fs-20">{{ __('admin.Common.Delete') }}</h6>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 mb-3">
                    <div class="d-inline-block text-white font-bold border-blue">
                        <table>
                            <tr>
                                <td class="d-inline-block border-rightBlue p-3 w-60px fs-20">
                                    <a class="font-weight-bold text-white" href="{{ route('admin.dish') }}" >{{ __('admin.Bottom.DISH') }}</a>
                                </td>
                                <td class="bg-blue2 p-3 d-inline-block border-rightBlue w-60px fs-20">
                                    <a class="font-weight-bold text-white" href="{{ route('admin.category') }}">{{ __('admin.Bottom.CATEGORY') }}</a>
                                </td>
                                <td class="p-3 d-inline-block border- w-60px border-rightBlue fs-20">
                                    <a class="font-weight-bold text-white" href="{{ route('admin.option') }}">{{ __('admin.Bottom.OPTION') }}</a>
                                </td>
                                <td class="p-3 d-inline-block border-rightBlue  w-60px fs-20">
                                    <a class="font-weight-bold text-white" href="{{ route('admin.discount') }}">{{ __('admin.Bottom.DISCOUNT') }}</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="modal fade" id="dish_add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:90% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                </button>
            </div>
            <input type="hidden" id="tmp_dish_ids">
            <input type="hidden" id="tmp_dish_count">
            <div class="modal-body pr-4" style="height: 500px;overflow:auto;">
                <div class="row w-100">
                    <div class="col-12">
                        @foreach($dishes as $dish)
                            <div class="border-bottom-blue">
                                <div class="row">
                                    <div class="col-8"><label class="txtdemibold mt-2 fs-20">{{$dish->name_en}}</label></div>
                                    <div class="col-4">
                                        <div class="float-right mt-2">
                                            <label class="bs-switch">
                                                <input type="checkbox" onclick="setDishId(this, '{{$dish->id}}');" class="common_check" id="dish_id_{{$dish->id}}">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                    CANCEL
                    <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                </button>
                <button type="button" class="btn btn-primary waves-effect waves-light fs-20" onclick="saveCheckedDishes();">
                    ADD
                    <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm_parent_category" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                </button>
            </div>
            <div class="modal-body pr-4">
                <p id="confirm_letter" class="text-center fs-20"></p>
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
<div class="modal fade" id="confirm_category_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                </button>
            </div>
            <div class="modal-body pr-4">
                <p id="confirm_remove" class="text-center fs-20"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                    {{ __('admin.Common.Cancel') }}
                    <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                </button>
                <button type="button" class="btn btn-primary waves-effect waves-light fs-20" id="confirmbtn">
                    {{ __('admin.Common.Delete') }}
                    <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="java-alert1" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="margin-top: -150px;">
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

<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/jquery.ui.touch-punch.js') }}"></script>
<script>
    function touchHandler(event)
    {
        var touch = event.changedTouches[0];

        var simulatedEvent = document.createEvent("MouseEvent");
        simulatedEvent.initMouseEvent({
                touchstart: "mousedown",
                touchmove: "mousemove",
                touchend: "mouseup"
            }[event.type], true, true, window, 1,
            touch.screenX, touch.screenY,
            touch.clientX, touch.clientY, false,
            false, false, false, 0, null);

        touch.target.dispatchEvent(simulatedEvent);
        event.preventDefault();
    }

    function init()
    {
        document.addEventListener("touchstart", touchHandler, true);
        document.addEventListener("touchmove", touchHandler, true);
        document.addEventListener("touchend", touchHandler, true);
        document.addEventListener("touchcancel", touchHandler, true);
    }

    // $(function(){
    $(document).ready(function() {
        init();
    });
    $(function(){
        $("#scroll-dish").sortable({
            stop: function(){
                $.map($(this).find('li'), function(el) {
                    var itemID = el.id;
                    var itemIndex = $(el).index();
                    $.ajax({
                        url:'{{URL::to("order-dish")}}',
                        type:'GET',
                        dataType:'json',
                        data: {itemID:itemID, itemIndex: itemIndex},
                    })
                });
            }
        });
        $("#category-scroll").sortable({
            stop: function(){
                $.map($(this).find('li'), function(el) {
                    var itemID = el.id;
                    var itemIndex = $(el).index();
                    $.ajax({
                        url:'{{URL::to("order-category")}}',
                        type:'GET',
                        dataType:'json',
                        data: {itemID:itemID, itemIndex: itemIndex},
                    })
                });
            }
        });
        $("#subcategory-scroll").sortable({
            stop: function(){
                $.map($(this).find('li'), function(el) {
                    var itemID = el.id;
                    var itemIndex = $(el).index();
                    $.ajax({
                        url:'{{URL::to("order-category")}}',
                        type:'GET',
                        dataType:'json',
                        data: {itemID:itemID, itemIndex: itemIndex},
                    })
                });
            }
        });
    });

    //});
</script>

<script>
    //$(document).ready(function(){
        // $('.hspace-category').height($('.switch-style').outerHeight(true));
    //});

    var changePosition = function(requestData){

        $.ajax({
            'url': '/sort',
            'type': 'POST',
            'data': requestData,
            'success': function(data) {
                if (data.success) {
                    App.notify.success('Saved!');
                } else {
                    App.notify.validationError(data.errors);
                }
            },
            'error': function(){
                App.notify.danger('Something wrong!');
            }
        });
    };

    $(document).ready(function(){
        var $sortableTable = $('.sortable');
        if ($sortableTable.length > 0) {
            $sortableTable.sortable({
                handle: '.sortable-handle',
                axis: 'y',
                update: function(a, b){

                    var entityName = $(this).data('entityname');
                    var $sorted = b.item;

                    var $previous = $sorted.prev();
                    var $next = $sorted.next();

                    if ($previous.length > 0) {
                        changePosition({
                            parentId: $sorted.data('parentid'),
                            type: 'moveAfter',
                            entityName: entityName,
                            id: $sorted.data('itemid'),
                            positionEntityId: $previous.data('itemid')
                        });
                    } else if ($next.length > 0) {
                        changePosition({
                            parentId: $sorted.data('parentid'),
                            type: 'moveBefore',
                            entityName: entityName,
                            id: $sorted.data('itemid'),
                            positionEntityId: $next.data('itemid')
                        });
                    } else {
                        App.notify.danger('Something wrong!');
                    }
                },
                cursor: "move"
            });
        }
        $('.sortable td').each(function(){
            $(this).css('width', $(this).width() +'px');
        });
    });

    var currentMain = '';
    var currentSub = '';
    var clickedSub = 0;
    var haveSub = 0;

    function onMainTitleEdit(){
        if(currentMain != ''){
            $('#current_parent_id').val(currentMain);
            $('#editCategoryTitleModal').modal('toggle');
            // currentMain = '';
            var selectedLi = $(`li[data-id=${currentMain}]`);
            $('#current_en_cat').val(selectedLi.data('english'));
            $('#current_cn_cat').val(selectedLi.data('chinese'));
            $('#current_jp_cat').val(selectedLi.data('japanese'));
            if (selectedLi.data('isunlimited') == 'on') {
                $('#current_is_unlimited').prop('checked', true);
            } else {
                $('#current_is_unlimited').prop('checked', false);
            }
        }
        else{
            $("#confirm_letter")[0].innerText = "カテゴリを選択してください。";
            $("#confirm_parent_category").modal('toggle');
        }
    }
    function onSubTitleEdit(){
        if(currentSub != ''){
            $('#current_sub_parent_id').val(currentSub);
            $('#editSubTitleModal').modal('toggle');
            // currentMain = '';
            var selectedLi = $(`li[data-id=${currentSub}]`);
            $('#current_sub_en_cat').val(selectedLi.data('english'));
            $('#current_sub_cn_cat').val(selectedLi.data('chinese'));
            $('#current_sub_jp_cat').val(selectedLi.data('japanese'));
        }
        else{
            $("#confirm_letter")[0].innerText = "サブカテゴリを選択してください。";
            $("#confirm_parent_category").modal('toggle');
        }
    }
    function onMain(obj){
        currentSub = '';
        haveSub = 0;
        activeCatButton('.cat-button', false);
        var id = $(obj).data('id');
        currentMain = id;
        if($(obj).hasClass('white')){
            activeCatButton(obj, true);
        }
        var hassubs = $(obj).data('hassubs');
        haveSub = hassubs;
        clickedSub = 0;
        //$('#chk_hassubs').prop('checked', hassubs == 1 ? true : false);
        $.ajax({
            type:"POST",
            url:"{{ route('admin.category.subs_list') }}",
            data:{
                category: id,
                _token:"{{ csrf_token() }}"
            },
            success: function(result){
                $('#subcategory-scroll').html(result.subcategory_list);
                $('#scroll-dish').html(result.dishes);
                $('#add_flag').val(result.add_flag);
                if(result.subs_count > 0){
                    $('#chk_hassubs').prop('checked', true);
                }
                else {
                    $('#chk_hassubs').prop('checked', false);
                }
                if(result.subcategory_list != '')
                {
                    clickedSub = 0;
                    haveSub = 1;
                }
                else
                    haveSub = 0;
            }
        });
    }
    function onSub(obj){
        clickedSub = 1;
        activeCatButton('.subcat', false);
        var id = $(obj).data('id');
        currentSub = id;
        if($(obj).hasClass('white')){
            activeCatButton(obj, true);
        }
        $.ajax({
            type:"POST",
            url:"{{ route('admin.category.dish_list') }}",
            data:{
                category: id,
                _token:"{{ csrf_token() }}"
            },
            success: function(result){
                $('#scroll-dish').html(result);
            }
        });
    }

    function addMainCategory()
    {
        var parent_id = '';
        var name_en = $('#name_en_cat').val();
        if(name_en == '') {
            //alert('Please input English name!');
            $("#alert-string1")[0].innerText = "英語名を入力してください！";
            $("#java-alert1").modal('toggle');
        } else {
            console.log($('#is_unlimited').val());
            $.ajax({
                type:"POST",
                url:"{{ route('admin.category.add') }}",
                data:{
                    name_en : $('#name_en_cat').val(),
                    name_cn : $('#name_cn_cat').val(),
                    name_jp : $('#name_jp_cat').val(),
                    is_unlimited: $('#is_unlimited').is(':checked') ? 'on' : 'off',
                    parent_id : parent_id,
                    _token : "{{ csrf_token() }}"
                },
                success: function(result){
                    if(parent_id != ''){
                        $('#subcategory-scroll').append(result);
                    } else {
                        $('#category-scroll').append(result);
                    }
                    $('#name_en_cat').val('');
                    $('#name_cn_cat').val('');
                    $('#name_jp_cat').val('');
                    $('#addCategoryModal').modal('hide');
                }
            });
        }
    }
    function onTitleCng()
    {
        var parent_id = $('#current_parent_id').val();
        var name_en = $('#current_en_cat').val();
        var selectedLi = $(`li[data-id=${currentMain}]`);
        if(name_en == '') {
            //alert('Please input English name!');
            $("#alert-string1")[0].innerText = "英語名を入力してください！";
            $("#java-alert1").modal('toggle');
        } else {
            var data = {
                name_en : $('#current_en_cat').val(),
                name_cn : $('#current_cn_cat').val(),
                name_jp : $('#current_jp_cat').val(),
                is_unlimited: $('#current_is_unlimited').is(':checked') ? 'on' : 'off',
                parent_id : parent_id,
                _token : "{{ csrf_token() }}"
            };
            $.ajax({
                type:"POST",
                url:"{{ route('admin.category.edit_title') }}",
                data: data,
                success: function(result){
                    if(parent_id != ''){

                        var html='';
                        if(result.length > 15)   html = result.substr(0,15) + "...";
                        else html = result;

                        document.getElementById("c_ti_" + currentMain).innerHTML = html;
                    }
                    selectedLi.data('english', data.name_en);
                    selectedLi.data('chinese', data.name_cn);
                    selectedLi.data('japanese', data.name_jp);
                    selectedLi.data('isunlimited', data.is_unlimited);

                    $('#current_en_cat').val('');
                    $('#current_cn_cat').val('');
                    $('#current_jp_cat').val('');
                    $('#editCategoryTitleModal').modal('hide');
                }
            });
        }
    }
    function onSubTitleCng()
    {
        var parent_id = $('#current_sub_parent_id').val();
        var name_en = $('#current_sub_en_cat').val();
        var selectedLi = $(`li[data-id=${currentSub}]`);
        if(name_en == '') {
            //alert('Please input English name!');
            $("#alert-string1")[0].innerText = "英語名を入力してください！";
            $("#java-alert1").modal('toggle');
        } else {
            var data = {
                name_en : $('#current_sub_en_cat').val(),
                name_cn : $('#current_sub_cn_cat').val(),
                name_jp : $('#current_sub_jp_cat').val(),
                parent_id : parent_id,
                _token : "{{ csrf_token() }}"
            };
            $.ajax({
                type:"POST",
                url:"{{ route('admin.category.edit_title') }}",
                data: data,
                success: function(result){
                    if(parent_id != ''){

                        var html = '';
                        if(result.length > 15)   html = result.substr(0,15) + "...";
                        else html = result;

                        document.getElementById("s_ti_" + currentSub).innerHTML = html;
                    }
                    selectedLi.data('english', data.name_en);
                    selectedLi.data('chinese', data.name_cn);
                    selectedLi.data('japanese', data.name_jp);

                    $('#current_en_cat').val('');
                    $('#current_cn_cat').val('');
                    $('#current_jp_cat').val('');
                    $('#editSubTitleModal').modal('hide');
                }
            });
        }
    }
    function addCategory()
    {
        var parent_id = $('#parent_id').val();
        var name_en = $('#name_en').val();
        if(name_en == '') {
            //alert('Please input English name!');
            $("#alert-string1")[0].innerText = "英語名を入力してください！";
            $("#java-alert1").modal('toggle');
        } else {

            $.ajax({
                type:"POST",
                url:"{{ route('admin.category.add') }}",
                data:{
                    name_en : $('#name_en').val(),
                    name_cn : $('#name_cn').val(),
                    name_jp : $('#name_jp').val(),
                    parent_id : parent_id,
                    _token : "{{ csrf_token() }}"
                },
                success: function(result){
                    if(parent_id != ''){
                        $('#subcategory-scroll').append(result);
                    } else {
                        $('#category-scroll').append(result);
                    }
                    $('#name_en').val('');
                    $('#name_cn').val('');
                    $('#name_jp').val('');
                    $('#addModal').modal('hide');
                    clickedSub = 0;
                    haveSub = 1;
                }
            });
        }
    }
    function onSubAdd(){
        if( $('#add_flag').val() == 1 && $('#dish_count').val() != 0 ){
            $("#confirm_letter")[0].innerText = "SubCategoryを追加するには、MainCategoryへの料理を削除する必要があります。";
            $("#confirm_parent_category").modal('toggle');
            return;
        }
        if($('#chk_hassubs').is(':checked') && currentMain != ''){
            $('#parent_id').val(currentMain);
            $('#addModal').modal('toggle');
            // currentMain = '';
        }
        if(!$('#chk_hassubs').is(':checked')){
            $("#confirm_letter")[0].innerText = "SubCategoryチェックボタンをアクティブに設定してください。";
            $("#confirm_parent_category").modal('toggle');
        }
        if(currentMain == ""){
            $("#confirm_letter")[0].innerText = "カテゴリを選択してください。";
            $("#confirm_parent_category").modal('toggle');
        }
    }
    $("#deleteMainCategory").click(function() {
        //alert(currentMain);
        if(currentMain != '') {
            $("#confirm_remove")[0].innerText = "カテゴリを削除しますか？";
            $("#confirmbtn").attr("onclick", "onDeleteMain()");
            $("#confirm_category_modal").modal('toggle');
        }else{
            $("#confirm_letter")[0].innerText = "カテゴリを選択してください。";
            $("#confirm_parent_category").modal('toggle');
        }
    });
    $("#deleteSubCategory").click(function() {
        if(currentSub != '') {
            $("#confirm_remove")[0].innerText = "サブカテゴリを削除しますか？";
            $("#confirmbtn").attr("onclick", "onDeleteSub()");
            $("#confirm_category_modal").modal('toggle');
        }else{
            $("#confirm_letter")[0].innerText = "サブカテゴリを選択してください。";
            $("#confirm_parent_category").modal('toggle');
        }
    });
    $("#deleteDishModal").click(function() {
        if(current_dish != '') {
            $("#confirm_remove")[0].innerText = "料理を削除しますか？";
            $("#confirmbtn").attr("onclick", "onDeleteDish()");
            $("#confirm_category_modal").modal('toggle');
        }else{
            $("#confirm_letter")[0].innerText = "お料理をお選びください。";
            $("#confirm_parent_category").modal('toggle');
        }
    });


    function onDeleteMain(){
        if(currentMain != ''){
            $.ajax({
                type:"GET",
                url:"{{ url('admin/category/delete') }}" + "/" + currentMain,
                success: function(){
                    $("[data-id='" + currentMain + "']").remove();
                    $("[data-parent='" + currentMain + "']").remove();
                    $('#scroll-dish').html('');
                    $("#confirm_category_modal").modal('hide');
                    currentMain = '';
                }
            });
        }
    }

    function onDeleteSub(){
        if(currentSub != ''){
            $.ajax({
                type:"GET",
                url:"{{ url('admin/category/delete') }}" + "/" + currentSub,
                success: function(){
                    $("[data-id='" + currentSub + "']").remove();
                    $('#scroll-dish').html('');
                    $("#confirm_category_modal").modal('hide');
                    currentSub = '';
                }
            });
        }
    }

    function activeCatButton(obj, selected){
        if(selected){
            $(obj).removeClass('white');
            $(obj).addClass('grey');
            $('.cat-caption', obj).removeClass('black-text');
        } else {
            $(obj).addClass('white');
            $(obj).removeClass('grey');
            $('.cat-caption', obj).addClass('black-text');
        }
    }

    var current_dish = '';
    function onDish(obj){
        current_dish = $(obj).data('dish');
        activeCatButton('.category-dish', false);
        if($(obj).hasClass('white')){
            activeCatButton(obj, true);
        }
    }
    function onAddDish()
    {
        if(currentMain == ""){
            $("#confirm_letter")[0].innerText = "カテゴリを選択してください。";
            $("#confirm_parent_category").modal('toggle');
        }else{
            // ('#chk_hassubs').is(':checked') == true   :  use sub category checked
            // clickedSub == 0  :  number of selected sub category = 0
            // clickedSub == 1  :  number of selected sub category > 0
            // haveSub == 1  : have subcategory   else  :  no subcategory

            if(haveSub == 1 && clickedSub == 0)
            {
                $("#confirm_letter")[0].innerText = "サブカテゴリを選択してください。";
                $("#confirm_parent_category").modal('toggle');
                return;
            }
            // if($('#chk_hassubs').is(':checked') == true && clickedSub == 0){
            //     $("#confirm_letter")[0].innerText = "Please checkoff USE SUB CATEGORY.";
            //     $("#confirm_parent_category").modal('toggle');
            //     return;
            // }
            var dish_ids = $("#dish_ids").val();
            var dish_count = $("#dish_count").val();
            var tmp_dish_ids = $("#tmp_dish_ids").val(dish_ids);
            var tmp_dish_count = $("#tmp_dish_count").val(dish_count);
            $(".common_check").each(function(e){
                $(".common_check")[e].checked = false;
            });
            if(dish_ids != '' && dish_count > 0){
                var dish_id_arr = dish_ids.split(',');
                for(var i = 0; i < dish_id_arr.length; i ++){
                    $("#dish_id_"+dish_id_arr[i])[0].checked = true;
                }
            }
            if(dish_count == 1){
                $("#dish_id_"+dish_ids)[0].checked = true;
            }
            $('#dish_add_modal').modal('toggle');
        }
    }


    function setDishId(obj, dish_id)
    {
        var tmp_dish_ids = $("#tmp_dish_ids").val();
        var tmp_dish_count = $("#tmp_dish_count").val();
        if(obj.checked == true){
            if(tmp_dish_ids != ''){
                tmp_dish_ids += ',' + dish_id;
            }else{
                tmp_dish_ids = dish_id;
            }
            $("#tmp_dish_ids").val(tmp_dish_ids);
            tmp_dish_count ++;
            $("#tmp_dish_count").val(tmp_dish_count);
        }else{
            var save_dish_ids = '';
            if(tmp_dish_count > 1){
                var dish_id_arr = tmp_dish_ids.split(',');
                for(var i = 0; i < dish_id_arr.length; i ++){
                    if(dish_id != dish_id_arr[i]){
                        if(save_dish_ids == ''){
                            save_dish_ids = dish_id_arr[i];
                        }else{
                            save_dish_ids += ',' + dish_id_arr[i];
                        }
                    }
                }
                $("#tmp_dish_ids").val(save_dish_ids);
            }else  if(tmp_dish_count == 1){
                $("#tmp_dish_ids").val('');
            }
            tmp_dish_count --;
            $("#tmp_dish_count").val(tmp_dish_count);
        }
        //$('#dish_add_modal').modal('hide');
    }

    function saveCheckedDishes()
    {
        var category = '';
        if(currentSub != '')
            category = currentSub;
        else
            category = currentMain;

        var tmp_dish_ids = $("#tmp_dish_ids").val();
        var tmp_dish_count = $("#tmp_dish_count").val();
        $("#dish_ids").val(tmp_dish_ids);
        $("#dish_count").val(tmp_dish_count);
        $.ajax({
            type:"GET",
            url:"{{ url('admin/category/dish_add') }}",
            data: {'dish_ids': tmp_dish_ids, 'category_id':category},
            success: function(result){
                if(result){
                    $.ajax({
                        type:"POST",
                        url:"{{ route('admin.category.dish_list') }}",
                        data:{
                            category: category,
                            _token:"{{ csrf_token() }}"
                        },
                        success: function(result){
                            $('#scroll-dish').html(result);
                            $('#add_flag').val('0');
                        }
                    });
                    if(currentSub != '')
                        currentSub = '';
                    else
                        currentMain = '';
                    $('#dish_add_modal').modal('hide');
                }
            }
        });

    }

    function onDeleteDish()
    {
        var category = '';
        if(currentSub != '')
            category = currentSub;
        else
            category = currentMain;

        if(current_dish != ''){
            $.ajax({
                type:"GET",
                url:"{{ url('admin/category/dish_delete') }}" + "/" + current_dish,
                data: {'category_id':category},
                success: function(result){
                    if(result){
                        $("[data-dish='" + current_dish + "']").remove();
                        $("#confirm_category_modal").modal('hide');
                        current_dish = '';
                        var cnt = $('#dish_count').val() - 1;
                        $('#dish_count').val(cnt);
                    }
                }
            });
        }
    }

</script>
@endsection
