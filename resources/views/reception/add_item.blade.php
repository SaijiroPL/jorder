<input type="hidden" id="order_id" value="{{ $order_id }}" />
<div class="add_item_modal">
    <div class="row" style="margin: 0 0 10px 30px;">
        <div class="col-4 category">
            @php $i=0; @endphp
            @foreach($category_all as $key => $category)
                @if(isset($category['has_subs']) && $category['has_subs'] == 1 && !empty($category['children']))
                    @php  $first = $category['id']; @endphp
                    @foreach($category['children'] as $child)
                        @php
                            $first = $child['id'];
                            break;
                        @endphp
                    @endforeach
                    <div class="header category_parent common_category" id="category_{{$category['id']}}" onclick="onDishes({{$first}})">
                        <span class="fs-25">{{$category['name_en']}}</span>
                    </div>
                    @if($i == 0)
                    <div class="display-none" style="display: block;">
                        <ul class="category_child">
                            @foreach($category['children'] as $child)
                                @if($i == 0)
                                <li id="category_{{$child['id']}}" class="common_category selected_category_color" onclick="onDishes1({{ $child['id'] }})">
                                    -<span class="fs-25">{{$child['name_en']}}</span></li>
                                @else
                                <li id="category_{{$child['id']}}" class="common_category" onclick="onDishes1({{ $child['id'] }})">
                                    -<span class="fs-25">{{$child['name_en']}}</span></li>
                                @endif
                                @php $i++; @endphp
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="display-none">
                        <ul class="category_child">
                            @foreach($category['children'] as $child)
                                <li id="category_{{$child['id']}}" class="common_category" onclick="onDishes1({{ $child['id'] }})">
                                    -<span class="fs-25">{{$child['name_en']}}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                @else
                    @if($i == 0)
                        @if($category['parent_id'] == "")
                            <div class="category_parent common_category selected_category_color" id="category_{{$category['id']}}" onclick="onDishes({{$category['id'] }})">
                                {{--                            <img src="{{asset("img/collapse1.png")}}" style="width: 17px;" />--}}
                                <span class="fs-25">{{$category['name_en']}}</span>
                            </div>
                        @endif
                    @else
                        @if($category['parent_id'] == "")
                            <div class="category_parent common_category" id="category_{{$category['id']}}" onclick="onDishes({{$category['id'] }})">
                                {{--                            <img src="{{asset("img/collapse1.png")}}" style="width: 17px;" />--}}
                                <span class="fs-25">{{$category['name_en']}}</span>
                            </div>
                        @endif
                    @endif
                @endif
                @php $i++; @endphp
            @endforeach
        </div>
        <div class="space"></div>
        <div class="col-7 dish">
            <section id="dish-content">
                @foreach ($dishes as $ds)
                    @if($ds->sold_out == 0)
                        <ul id="myUL">
                            @if(count($ds->options) == 0)
                            <li>
                                <input type="checkbox" class="checked_items_{{ $ds->id }}0" value="{{$ds->id}}:0"
                                onclick="selectItem(1, '{{ $ds->id }}',0)" style="width:20px;height:20px;" />
                                <span class="fs-25">{{ $ds->name_en }}</span>
                            </li>
                            @else
                            <li><span class="caret fs-25">{{ $ds->name_en }}</span>
                                <ul class="nested">
                                    @foreach ($ds->options as $option)
                                        <li>
                                        <span class="caret fs-25">{{ $option->name }}:
                                            @if($option->photo_visible == 0 || $option->number_selection == 1)
                                                (You can select only 1 item.)
                                            @else
                                                (You can select only {{ $option->number_selection }} items.)
                                            @endif
                                        </span>
                                            <ul class="nested">
                                                @foreach ($option->item as $item)
                                                    <li>
                                                        @if($option->photo_visible == 0)
                                                            <input type="checkbox" class="checked_items_{{ $ds->id }}{{ $option->id }}" value="{{$ds->id}}:{{$item->id}}"
                                                                   onclick="selectItem(1, '{{ $ds->id }}', '{{ $option->id }}')" style="width:20px;height:20px;" />
                                                        @else
                                                            <input type="checkbox" class="checked_items_{{ $ds->id }}{{ $option->id }}" value="{{$ds->id}}:{{$item->id}}"
                                                                   onclick="selectItem('{{ $option->number_selection }}', '{{ $ds->id }}', '{{ $option->id }}')"
                                                                   style="width:20px;height:20px;" />
                                                        @endif
                                                        <span class="fs-25">{{ $item->name }}</span>
                                                        @if($item->price) <span class="fs-25">(¥{{ round($item->price, 0) }})</span> @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                        </ul>
                    @endif
                @endforeach
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-7 qty">
            <div class="row" style="height: 50px;">
                <div class="col-4"></div>
                <div class="col-4">
                    <div class="row">
                        <span style="color: white;margin: 10px 0 0 51px;font-size: 25px;">{{ __('reception.QTY') }}</span>
                    </div>
                </div>
                <div class="col-4"></div>
            </div>
            <div class="row" style="height: 50px;">
                <div class="col-5" style="text-align: right;">
                    <img src="{{asset('img/qty_down.png')}}" style="width: 60px;margin: 17px 10px 0 0px;" onclick="plusQty('minus')" />
                </div>
                <div class="col-2">
                    <div class="row qty_text">
                        <span id="qty" style="width: 70px;height: 60px;font-weight: 500;text-align: center;padding-top: 10px;">@if($order_dish_id != 0) {{ $count }} @else 0 @endif</span>
                    </div>
                </div>
                <div class="col-5">
                    <img src="{{asset('img/qty_up.png')}}" style="width: 60px;margin: 17px 0px 0 -15px;" onclick="plusQty('plus')" />
                </div>
            </div>
        </div>
        <div class="col-4" style="margin-left: -6px;">
            <div class="amend_btn" style="background: #1EC2C9;color: white;margin: 12px 0 0 43px; padding-left: 42px;" onclick="onApply()">
                @if($order_dish_id != 0)
                    <aa class="fs-25" style="margin-right: -6px;">{{ __('admin.Common.Update') }}</aa>
                @else
                    <aa class="fs-25" style="margin-right: 20px;">{{ __('admin.Common.Apply') }}</aa>
                @endif
                <img src="{{ asset('img/Group728white.png') }}" style="height:18px;margin: -8px 0 0 58px;">
            </div>
            <div class="amend_btn" style="background: white;color: black;margin: 12px 0 0 43px;padding-left: 15px;" onclick="$('#thirdModal').modal('hide');">
                <aa class="fs-25" style="margin-left: 25px;">{{ __('admin.Common.Cancel') }}</aa>
                <img src="{{ asset('img/Group728black.png') }}" style="height:18px;margin: -8px 0 0 5px;">
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
                <button type="button" class="btn btn-light waves-effect waves-light fs-20" onclick="$('#java-alert1').modal('hide');">
                    {{ __('admin.Common.Close') }}
                    <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                </button>
            </div>
        </div>
    </div>
</div>
<script>

    var select_list = [];
    var order_dish_id = <?php echo(json_encode($order_dish_id))?>;
    var count = <?php echo(json_encode($count))?>;

    /*$(document).ready(function(){
        $(".category_parent").first().addClass('selected_category_color');
    });*/
    $(".header").click(function () {
        $header = $(this);
        $content = $header.next();
        $content.slideToggle(500);
    });

    function onDishes(category_id){
        var catContents = document.getElementsByClassName('display-none');
        for (var i = 0; i < catContents.length; i++) {
            catContents[i].style.display = 'none';
        }

        select_list = [];
        order_id = $('#order_id').val();
        var selected_obj = $("#category_"+category_id);
        $(".common_category").removeClass("selected_category_color");
        selected_obj.toggleClass('selected_category_color');
        $.ajax({
            type:"POST",
            url:"{{ route('reception.dish_list') }}",
            data:{
                order_id: order_id, category: category_id, _token:"{{ csrf_token() }}"
            },
            success: function(result){
                $('#dish-content').html(result);
            }
        });
    }

    function onDishes1(category_id){

        select_list = [];
        order_id = $('#order_id').val();
        var selected_obj = $("#category_"+category_id);
        $(".common_category").removeClass("selected_category_color");
        selected_obj.toggleClass('selected_category_color');
        $.ajax({
            type:"POST",
            url:"{{ route('reception.dish_list') }}",
            data:{
                order_id: order_id, category: category_id, _token:"{{ csrf_token() }}"
            },
            success: function(result){
                $('#dish-content').html(result);
            }
        });
    }

    function plusQty(arg){
        var qty_number_obj = $("#qty");
        var qty_number = qty_number_obj.text();

        if(arg == 'plus') {
            qty_number ++;
        } else {
            if(order_dish_id == 0) {
                if(qty_number > 0) {
                    qty_number --;
                }
            } else {
                if(parseInt(count) + parseInt(qty_number) < 1) {
                    $("#alert-string1")[0].innerText = 'The count for this item is ' + count + '.\n Please select qty by small than count!';
                    $("#java-alert1").modal('toggle');
                } else {

                    qty_number --;
                }
            }
        }
        // if(qty_number < 10) {
        //     if(qty_number == 1) {
        //         qty_number = '01';
        //     } else {
        //         qty_number = '0' + qty_number;
        //     }
        // }
        qty_number_obj.html(qty_number);

    }

    var toggler = document.getElementsByClassName("caret");
    var i;

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
    }

    //select Item
    function selectItem(number_selection, dish_id, option_id) {

        var ds_op = dish_id + option_id;
        var checked_cnt = $(".checked_items_" + ds_op + ":checked").length;
        if(checked_cnt > number_selection) {
            if(number_selection == 1) {
                $("#alert-string1")[0].innerText = "一つだけ選べます！";
                $("#java-alert1").modal('toggle');
            }
            else {
                $("#alert-string1")[0].innerText = 'You can select only ' + number_selection + ' items!';
                $("#java-alert1").modal('toggle');
            }
            var inputs = document.querySelectorAll('.checked_items_' + ds_op);
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].checked = false;
            }
        }

    }

    // function selectDishes(dish_id) {
    //
    //     // select_dish_id = '';
    //     // select_item_ids = [];
    //     var selected_dish_obj = $("#op_" + dish_id);
    //     $(".common_dish").removeClass("selected_category_color");
    //     selected_dish_obj.toggleClass('selected_category_color');
    //     // select_dish_id = dish_id;
    //     //console.dir(select_dish_id);
    // }

    //Apply
    function onApply(){
        var order_id =  <?php echo(json_encode($order_id)) ?>;

        if(order_dish_id == 0) {// add item
            //select get dish and option list for add
            select_list = [];
            var tmp = '';
            $("input:checkbox:checked").each(function(){
                var val = $(this).val();
                var val_arr = val.split(':');
                if(tmp == 0){
                    tmp = val_arr[0];
                    select_list.push(val);
                }
                else{
                    if(tmp != val_arr[0]) {
                        $("#alert-string1")[0].innerText = "選べる一品！";
                        $("#java-alert1").modal('toggle');
                    } else {
                        select_list.push(val);
                    }
                }
            });

            if(select_list.length == 0){
                $("#alert-string1")[0].innerText = "料理とオプションを選択してください！";
                $("#java-alert1").modal('toggle');
                return;
            } else {
                var qty_number_obj = $("#qty");
                var qty = qty_number_obj.text();

                if(qty == 0) {
                    $("#alert-string1")[0].innerText = "数量を設定してください！";
                    $("#java-alert1").modal('toggle');
                } else {
                    $('#thirdModal').html('');
                    $('#thirdModal').modal('hide');

                    $.ajax({
                        type:"POST",
                        url:"{{ route('reception.add_item') }}",
                        data:{
                            order_id: order_id, select_list: select_list, qty: qty, _token:"{{ csrf_token() }}"
                        },
                        success: function(result){
                            location.href = window.location.href;
                        }
                    });
                }
            }
        } else {// change count of item
            var count = <?php echo(json_encode($count))?>;
            var qty_number_obj = $("#qty");
            var qty = qty_number_obj.text();
            var change_count = parseInt(qty) - count;

            $('#thirdModal').html('');
            $('#thirdModal').modal('hide');

            $.ajax({
                type:"POST",
                url:"{{ route('reception.change_count') }}",
                data:{
                    order_dish_id: order_dish_id, change_count: change_count, _token:"{{ csrf_token() }}"
                },
                success: function(result){
                    // console.dir(result);
                    location.href = window.location.href;
                }
            });
        }

    }

</script>

<style>
    .category {
        width: 20px;
        height: 60vh;
        background: white;
        padding-top: 15px;
        overflow-x: hidden;
        overflow-y: auto;
        margin-left: 7px;
    }

    .space {
        width:27px;
    }

    .dish {
        width: 50px;
        height: 60vh;
        background: white;
        padding-top: 15px;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .qty {
        width: 250px;
        /*background: red;*/
        margin-left: 45px;
    }

    .qty_text {
        width: 80px;
        height: 75px;
        background: white;
        font-size: 30px;
        margin: 5px 0 0 -23px;
        padding: 7px 0 0 5px;
    }

    .selected_category_color{
        color: #039BFA;
    }

    ul {
        margin-bottom: 0;

    }


    /**********************************************/
    ul, #myUL {
        list-style-type: none;
    }

    #myUL {
        margin: 0;
        padding: 0;
    }

    .caret {
        cursor: pointer;
        -webkit-user-select: none; /* Safari 3.1+ */
        -moz-user-select: none; /* Firefox 2+ */
        -ms-user-select: none; /* IE 10+ */
        user-select: none;
    }

    .caret::before {
        content: "\25B6";
        color: black;
        display: inline-block;
        margin-right: 6px;
    }

    .caret-down::before {
        -ms-transform: rotate(90deg); /* IE 9 */
        -webkit-transform: rotate(90deg); /* Safari */'
    transform: rotate(90deg);
    }

    .nested {
        display: none;
    }

    .active {
        display: block;
    }

</style>


