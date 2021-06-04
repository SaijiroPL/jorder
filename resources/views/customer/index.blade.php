<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
    <meta name="viewport" content="width=device-width, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('customer_css/style.css')}}">
    <link rel="stylesheet" href="{{asset('customer_css/all.css')}}">
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
</head>
<style>
    .scrolling-content {
        overflow-y: scroll;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }
    /*:not(html) {
        -webkit-transform: translate3d(0, 0, 0);
    }*/
    #screensaver { position: absolute; width: 100%; height:100%; left:0px; top: 0px; display: none; z-index:9999; }
</style>

<body style="background: url('{{ $img_back }}');">
<div id="app">
    <input type="hidden" id="img_name" value="{{ $img_name }}">
    <nav>
        <input type="hidden" id="order_id" value="{{ $order->id }}" />
        <div class="brand">
            <p>
                <pay-finish-component order_id="{{ $order->id }}"></pay-finish-component>
            </p>

            <p>
                <finish-and-pay-component order_id="{{ $order->id }}"></finish-and-pay-component>
            </p>

            <img src="{{asset('receipt/'.$profile->logo_image)}}" alt="Logo" class="logo" height="110px" onclick="location.reload();">
        </div>
        <div class="category_container">
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
                        <span>
                            @if(session('language') == 1)
                                {{$category['name_cn']}}
                            @elseif(session('language') == 2)
                                {{$category['name_jp']}}
                            @else
                                {{$category['name_en']}}
                            @endif
                        </span>
                    </div>
                    @if($i == 0)
                        <div class="display-none" style="display: block;" id="cat-{{ $category['id'] }}">
                            <ul class="category_child">
                                @foreach($category['children'] as $child)
                                    @if($i == 0)
                                        <li id="category_{{$child['id']}}" class="common_category selected_category_color" onclick="onDishes1({{ $child['id'] }})">
                                            @if(session('language') == 1)
                                                - {{$child['name_cn']}}
                                            @elseif(session('language') == 2)
                                                - {{$child['name_jp']}}
                                            @else
                                                - {{$child['name_en']}}
                                            @endif
                                        </li>
                                    @else
                                        <li id="category_{{$child['id']}}" class="common_category" onclick="onDishes1({{ $child['id'] }})">
                                            @if(session('language') == 1)
                                                - {{$child['name_cn']}}
                                            @elseif(session('language') == 2)
                                                - {{$child['name_jp']}}
                                            @else
                                                - {{$child['name_en']}}
                                            @endif
                                        </li>
                                    @endif
                                    @php $i++; @endphp
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="display-none" id="cat-{{ $category['id'] }}">
                            <ul class="category_child">
                                @foreach($category['children'] as $child)
                                    <li id="category_{{$child['id']}}" class="common_category" onclick="onDishes1({{ $child['id'] }})">
                                        @if(session('language') == 1)
                                            - {{$child['name_cn']}}
                                        @elseif(session('language') == 2)
                                            - {{$child['name_jp']}}
                                        @else
                                            - {{$child['name_en']}}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @else
                    @if($i == 0)
                        @if($category['parent_id'] == "")
                            <div class="category_parent common_category selected_category_color" id="category_{{$category['id']}}" onclick="onDishes({{$category['id'] }})">
                                <span>
                                    @if(session('language') == 0)
                                        {{$category['name_en']}}
                                    @elseif(session('language') == 1)
                                        {{$category['name_cn']}}
                                    @else
                                        {{$category['name_jp']}}
                                    @endif
                                </span>
                            </div>
                        @endif
                    @else
                        @if($category['parent_id'] == "")
                            <div class="category_parent common_category" id="category_{{$category['id']}}" onclick="onDishes({{$category['id'] }})">
                                <span>
                                    @if(session('language') == 0)
                                        {{$category['name_en']}}
                                    @elseif(session('language') == 1)
                                        {{$category['name_cn']}}
                                    @else
                                        {{$category['name_jp']}}
                                    @endif
                                </span>
                            </div>
                        @endif
                    @endif
                @endif
                @php $i++; @endphp
            @endforeach
        </div>
    </nav>
    <main>
        <header>
        <div class="tInfo btn">
            <div class="tNumber">
                <h3>{{ __('customer.TABLE_NUMBER') }}</h3>
                {{--<h2>{{$table_name}}</h2>--}}
                <display-table-component order_id="{{ $order->id }}" table_name="{{ $table_name }}"></display-table-component>
            </div>
            <h6>&nbsp;</h6>
            <div class="tTime">
                <h3>{{ __('customer.START_TIME') }}</h3>
                <h3>{{date('Y年m月d日 H:i', strtotime($order->time))}}</h3>
            </div>
        </div>

        <customer-calling-component table_id="{{ $table_id }}" order_id="{{ $order->id }}" calling_time="{{ $order_table->calling_time }}"></customer-calling-component>

        <div style="width:230px;"  onclick="lang_select()">
            <img src="{{asset('img/language.png')}}" alt="language" srcset="" width="70px">
            <h3>言語</h3>
        </div>
        <div style="width:250px;" onclick="feedBack()">
            <img src="{{asset('img/feedback.png')}}" alt="feedback" srcset="" width="70px">
            <h3>{{ __('customer.FEEDBACK') }}</h3>
        </div>
        <div class="primaryBtn btn" id="myBtn" onclick="view_bill_pay()">
            <img src="{{asset('img/money.png')}}" alt="" srcset="" width="40px">
            <h3>{{ __('customer.BILL_PAY') }}</h3>
        </div>
        <div class="greyBtn btn">
            <h3>{{ __('customer.LEFT_TIME') }}</h3>
            <h3><font size="5px"><span id="time"></span></font> mins</h3>
        </div>
    </header>
    <section id="dish-content" class="scrolling-content">
    @foreach ($dishes as $ds)
        @if($ds->sold_out == 0)
            <div class="card" onclick="orderNow({{$ds->id}})">
                <div class="card-header">
                    <img class="cardImg" @if($ds->image) src="{{asset('dishes/'.$ds->image)}}" @endif>
                    <div class="headerSpan">
                        <div class="specialBadge">
                            @if($ds->badge_id > 0)
                                <img src="{{asset('badges/'.$ds->badge->filepath)}}" alt="" srcset="" height="38px">
                            @endif
                        </div>
                        <div class="fab">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <p class="text_limit_character dish_description">
                        @if(session('language') == 1)
                            {{$ds->name_cn}}
                        @elseif(session('language') == 2)
                            {{$ds->name_jp}}
                        @else
                            {{$ds->name_en}}
                        @endif
                    </p>
                    <footer>
                        @if($ds->discount != '')
                        <div class="discountedPrice">
                            ¥{{ round($ds->discount, 0) }}
                        </div>
                        @endif
                        <div @if($ds->discount != '') class="price striked" @else class="price unstriked" @endif>
                            @if($order->duration != 5 && $ds->isBuffet()) ¥0 @else ¥{{ $ds->price }} @endif
                        </div>
                    </footer>
                </div>
            </div>
        @else
            <div class="card outStock">
                <div class="card-header">
                    <img class="cardImg" @if($ds->image) src="{{asset('dishes/'.$ds->image)}}" @endif>
                    <div class="headerSpan">
                        <div class="specialBadge">
                            @if($ds->badge_id > 0)
                                <img src="{{asset('badges/'.$ds->badge->filepath)}}" alt="" srcset="" height="38px">
                            @endif
                        </div>
                        <div class="fab">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <p class="text_limit_character dish_description">
                        @if(session('language') == 1)
                            {{$ds->name_cn}}
                        @elseif(session('language') == 2)
                            {{$ds->name_jp}}
                        @else
                            {{$ds->name_en}}
                        @endif
                    </p>
                    <footer>
                        @if($ds->discount != '')
                            <div class="discountedPrice">
                                ¥{{ round($ds->discount, 0) }}
                            </div>
                        @endif
                        <div @if($ds->discount != '') class="price striked" @else class="price unstriked" @endif>¥{{ round($ds->price, 0) }}</div>
                    </footer>
                </div>
            </div>
        @endif
    @endforeach
</section>
</main>
</div>
<div id="screensaver"></div>

<script src="{{ asset('js/app.js') }}"></script>

<script type="text/javascript">
    var csrfToken = $('[name="csrf_token"]').attr('content');

    setInterval(refreshToken, 120000); // 2 min

    function refreshToken(){
        $.ajax({
            type:"GET",
            url:"{{ url('refresh-csrf') }}",
            data:{},
            success: function(result){
                csrfToken = result;
            }
        });
    }

    setInterval(refreshToken, 120000); //2 min

</script>

<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script>

    // Glabal variables for order state registering( photo_visible == 1 )
     var selected_items_id = [];
     var order_option_count = 0;
     var chk_prev_flag = 0;

     //////////////////////////////////////////********************************************************************************************************************************

    $(document).ready(function(){
        var idletime = 120;
        @if ($screentime)
            idletime = {{ $screentime->screen_time }};
        @endif
        (function(poll, timeout){

            var _idle = false,
                _lastActive = 0,
                _activeNow = function() {
                    _lastActive = new Date();

                    if (_idle) {
                        $('#screensaver').hide();
                        _idle = false;
                    }
                },
                _poll = function() {

                    var elapsed = (new Date()) - _lastActive;
                    var img_path = img_name.value;

                    if ((elapsed > timeout) && !_idle) {
                        $('#screensaver').fadeIn();
                        _idle = true;

                        setInterval(function(){
                            document.getElementById("screensaver").innerHTML=`<img src="${img_path}" width="100%" height="100%">`;
                        }, 5000);

                    }
                }

            $(window).bind('click', _activeNow);

            window.setInterval(_poll, poll);

            _activeNow();
        })(1000, 1000 * idletime);
    });

    $(".header").click(function () {
        $header = $(this);
        $content = $header.next();
        $content.slideToggle(500);
    });

    function Global_format() {

        selected_items_id = [];
        order_option_count = 0;
        chk_prev_flag = 0;
    }

    function onDishes(category_id){

        var catContents = document.getElementsByClassName('display-none');
        for (var i = 0; i < catContents.length; i++) {
            catContents[i].style.display = 'none';
        }

//        var catContentIdToShow = 'cat' + category_id;
//        document.getElementById(catContentIdToShow).style.display = 'block';

        var selected_obj = $("#category_"+category_id);
        $(".common_category").removeClass("selected_category_color");
        selected_obj.toggleClass('selected_category_color');

        var order = <?php echo json_encode($order) ?>;
        $.ajax({
            type:"POST",
            url:"{{ route('customer.dish_list') }}",
            data:{
                order: "{{ $order->id }}", category: category_id, time_slot:"{{$order->time}}", menu_type: order['menu_type'], _token:"{{ csrf_token() }}"
            },
            success: function(result){
                $('#dish-content').html(result);
                $('#dish-content').scrollTop(0);
                $('#dish-content').animate({
                    scrollTop: 0
                }, 'slow');
            }
        });
    }

    function onDishes1(category_id){
        var selected_obj = $("#category_"+category_id);
        $(".common_category").removeClass("selected_category_color");
        selected_obj.toggleClass('selected_category_color');
        var order = <?php echo json_encode($order) ?>;
        $.ajax({
            type:"POST",
            url:"{{ route('customer.dish_list') }}",
            data:{
                order: "{{ $order->id }}", category: category_id, time_slot:"{{$order->time}}", menu_type: order['menu_type'], _token:"{{ csrf_token() }}"
            },
            success: function(result){
                $('#dish-content').html(result);
                $('#dish-content').scrollTop(0);
                $('#dish-content').animate({
                    scrollTop: 0
                }, 'slow');
            }
        });
    }

    function orderNow(dish_id){

        $('#myModal').html('');
        $.ajax({
            type:"POST",
            url:"{{ route('customer.dish_info') }}",
            data:{
                dish_id: dish_id, type: 'first', _token:"{{ csrf_token() }}"
            },
            success: function(result){
                $('#myModal').html(result);
            }
        });
        // $(".modal-content").css('width', '65%');
        $("#myModal").modal("toggle");
    }

    function plusQty(arg){
        var qty_number_obj = $("#numOfItems");
        var qty_number = qty_number_obj.text();
        if(arg == 'plus'){
            qty_number ++;
        }else{
            if(qty_number > 1){
                qty_number --;
            }
        }
        if(qty_number < 10){
            if(qty_number == 1){
                qty_number = '01';
            }else{
                qty_number = '0' + qty_number;
            }
        }
        qty_number_obj.html(qty_number);
    }

    function nextModal(arg){
        var dish_id = $("#dish-id").val();
        var order_id = $("#order_id").val();
        switch (arg){
            case 'thx':
                var res = confirm('ご注文はお決まりですか?');
                if (!res) return;
                var chked_list_str = '';
                $("input:radio").each(function(){
                    var name = $(this).attr("name");
                    if($("input:radio[name="+name+"]:checked").length > 0) {
                        if(chked_list_str != '')
                            chked_list_str += "," + $("input:radio[name="+name+"]:checked").val();
                        else
                            chked_list_str = $("input:radio[name="+name+"]:checked").val();
                    }
                });

                var chked_list = chked_list_str.split(",");
                const distinct = (value, index, self) => {
                    return self.indexOf(value) === index;
                }
                chked_list = chked_list.filter(distinct);
                // console.log(chked_list);
                var count = $("#numOfItems").text();
                // console.log(count);
                $.ajax({
                    type:"POST",
                    url:"{{ route('customer.order_dish') }}",
                    data:{
                        order_id: order_id, dish_id: dish_id, count: count, items_id: chked_list, _token: "{{ csrf_token() }}"
                    },
                    success: function(result){
						console.log('success');
						console.log('data');
                        var thx = $("#myModal").html($("#thx").html());
				    },
    				error: function (XMLHttpRequest, textStatus, errorThrown) {
      					console.log('error');
      					console.log(XMLHttpRequest.status);
      					console.log(textStatus);
      					console.log(errorThrown);
					}
                });
                break;
            case 'main':
                var checked_items_obj = $(".checked_items");
                var checked_option_ids = '';
                for(var i = 0; i < checked_items_obj.length; i ++){
                    if(checked_items_obj[i].checked == true){
                        if(checked_option_ids == ''){
                            checked_option_ids = checked_items_obj[i].value;
                        }else{
                            checked_option_ids += ',' + checked_items_obj[i].value;
                        }
                    }
                }
                $.ajax({
                    type:"POST",
                    url:"<?php echo e(route('customer.dish_info')); ?>",
                    data:{
                        dish_id: dish_id, type: 'main', items: checked_option_ids, _token:"<?php echo e(csrf_token()); ?>"
                    },
                    success: function(result){
                        $(".modal-content").css('width', '90%');
                        $('#myModal').html(result);
                    }
                });
                break;
        }
    }

    function next_page(option_id_arr, index){

        var dish_id = $("#dish-id").val();
        var items_id = $("#items-id").val();
     // console.log(items_id);
        selected_items_id.push(items_id);
        console.dir(selected_items_id);

        if(chk_prev_flag == 1)
            index++;

        $.ajax({
            type:"POST",
            url:"<?php echo e(route('customer.dish_option')); ?>",
            data:{
                option_id_arr: option_id_arr, dish_id: dish_id, items_id: selected_items_id[index], index: index, selecteds: selected_items_id, chk_prev_flag: chk_prev_flag, _token:"<?php echo e(csrf_token()); ?>"
            },
            success: function(result){
                $('#thirdModal').html(result);
                chk_prev_flag = 0;
                $("#items-id").val('');
            }
        });
        $("#myModal").modal("hide");
        $('#thirdModal').modal('show');
    }

    function option_previous_page(option_id_arr, index){
        var dish_id = $("#dish-id").val();
        $("#items-id").val('');
        var items_id = '';

        selected_items_id.pop();
        // console.log(selected_items_id);

        chk_prev_flag = 1;// back flag(from dish_option page)

        if(index == 1) {// back from first dish_option page to dish_info page
            $.ajax({
                type:"POST",
                url:"{{ route('customer.dish_info') }}",
                data:{
                    dish_id: dish_id, type: 'first', _token:"{{ csrf_token() }}"
                },
                success: function(result){
                    $('#myModal').html(result);
                }
            });
            // $(".modal-content").css('width', '65%');
            Global_format();
            index = 0;
            $("#myModal").modal('show');
            $('#thirdModal').modal('hide');
        }
        else {// back from dish_option page to dish_option page
            $.ajax({
                type:"POST",
                url:"<?php echo e(route('customer.dish_option_previous')); ?>",
                data:{
                    option_id_arr: option_id_arr, dish_id: dish_id, items_id: selected_items_id[index], index: index-2, selecteds: selected_items_id, _token:"<?php echo e(csrf_token()); ?>"
                },
                success: function(result){
                    $('#thirdModal').html(result);
                    chk_prev_flag = 0;
                }
            });
            $("#myModal").modal("hide");
            $('#thirdModal').modal('show');
        }
    }

    function review_previous_page(option_id_arr, index){
        var dish_id = $("#dish-id").val();
        $("#items-id").val('');
        var items_id = '';

        selected_items_id.pop();
        // console.log(selected_items_id);

        $.ajax({
            type:"POST",
            url:"<?php echo e(route('customer.dish_option_previous')); ?>",
            data:{
                option_id_arr: option_id_arr, dish_id: dish_id, items_id: selected_items_id[index], index: index-1, selecteds: selected_items_id, _token:"<?php echo e(csrf_token()); ?>"
            },
            success: function(result){
                $('#thirdModal').html(result);
                chk_prev_flag = 0;
            }
        });
        $("#myModal").modal("hide");
        $('#thirdModal').modal('show');
    }

    function base_page() {
        Global_format();
        $('#thirdModal').modal('hide');
    }

    // function selectItem(){
    //
    //     var display_name = $("#display_name").val();
    //     var option_price_obj = $("#option_price");
    //     var number_selection = $("#number_selection").val();
    //     var items_id_obj = $("#items-id");
    //
    //     var option_price = [];
    //     var items_id = [];
    //
    //     $("input:checkbox").each(function(){
    //         var name = $(this).attr("name");
    //         var it_chk_val = '';
    //         if($("input:checkbox[name="+name+"]:checked").length > 0) {
    //             if($("input:checkbox[name="+name+"]:checked").val()) {
    //                 it_chk_val = parseFloat($("input:checkbox[name="+name+"]:checked").val());
    //                 option_price.push([name, it_chk_val.toFixed(2)]);
    //             }
    //             else {
    //                 option_price.push([name, '']);
    //             }
    //             items_id.push([name]);
    //         }
    //     });
    //
    //     var itm_price = '';
    //     var display_item_price = '';
    //     var option_price_str = '';
    //     if(option_price.length <= number_selection) {
    //         for(var i=0;i<option_price.length;i++) {
    //             itm_price = option_price[i][1];
    //             if(itm_price != '')
    //                 display_item_price = "<span class='price'>" + "$" + itm_price + "</span>";
    //             else
    //                 display_item_price = "";
    //             option_price_str += "<span class='price'> + </span>" + display_name + display_item_price;
    //         }
    //     }
    //     else {
    //         alert('You can select ' + number_selection + ' only for this option.');
    //         //all check info format
    //         $("input:checkbox").each(function(){
    //             var id = $(this).attr("id");
    //             document.getElementById(id).checked = false;
    //             option_price = [];
    //             items_id = [];
    //         });
    //     }
    //
    //     option_price_obj.html(option_price_str);
    //     items_id_obj.val(items_id);
    // }

    function selectItem(){

        var display_name = $("#display_name").val();

        var option_price_obj = $("#option_price");
        var number_selection = $("#number_selection").val();
        var items_id_obj = $("#items-id");

        var option_price = [];
        var items_id = [];

        $("input:checkbox").each(function(){
            var name = $(this).attr("name");
            var it_chk_val = '';
            if($("input:checkbox[name="+name+"]:checked").length > 0) {
                var op_vals = [];
                if($("input:checkbox[name="+name+"]:checked").val()) {
                    var op_val = $("input:checkbox[name="+name+"]:checked").val();
                    op_vals = op_val.split(':');

                    // it_chk_val = parseFloat(op_vals[1]);
                    // option_price.push([name, op_vals[0] + ':' + parseFloat(op_vals[1]).toFixed(2)]);
                    option_price.push([name, op_val]);
                }
                else {
                    option_price.push([name, '']);
                }
                items_id.push([name]);
            }
        });

        var opt_itm = '';
        var itm_price = '';
        var display_item_price = '';
        var sign = '';
        var option_price_str = '';
        //alert(option_price.length);
        if(option_price.length <= number_selection) {
            for(var i=0;i<option_price.length;i++) {
                opt_itm = option_price[i][1].split(':');
                itm_price = opt_itm[1];

                if(itm_price != 0.00) {
                    if(itm_price < 0) {
                        display_item_price = "<span class='price'>" + "$" + (-1)*itm_price + "</span>";
                        sign = ' - ';
                    } else {
                        display_item_price = "<span class='price'>" + "$" + itm_price + "</span>";
                        sign = ' + ';
                    }
                }
                else {
                    display_item_price = "";
                    sign = ' + ';
                }
                option_price_str += "<span class='price'>" + sign + "</span>" + opt_itm[0] + display_item_price;
            }

        }
        else {
//            if(number_selection == '') {
//                alert('Number selection for this item is empty. Please ask to waiter!');
//            } else {
//                //alert('You can select ' + number_selection + ' only for this option.');
//                $('#java-alert2').modal('show');
//            }
            //all check info format
            $("input:checkbox").each(function(){
                var id = $(this).attr("id");
                document.getElementById(id).checked = false;
                option_price = [];
                items_id = [];
            });

        }

        option_price_obj.html(option_price_str);
        items_id_obj.val(items_id);
    }

    function reviewOrder(option_id_arr, index){
        var dish_id = $("#dish-id").val();
        var items_id = $("#items-id").val();
        // console.log(items_id);
        selected_items_id.push(items_id);
        // console.log(selected_items_id);
        $.ajax({
            type:"POST",
            url:"<?php echo e(route('customer.dish_option_confirm')); ?>",
            data:{
                option_id_arr: option_id_arr, dish_id: dish_id, items_id: selected_items_id[index], index: index, selecteds: selected_items_id, _token:"<?php echo e(csrf_token()); ?>"
            },
            success: function(result){
                $('#thirdModal').html(result);
                $("#items-id").val('');
            }
        });
        $("#myModal").modal("hide");
        $('#thirdModal').modal('show');
    }

    function orderNow_Photo() {
        var res = confirm('ご注文はお決まりですか?');
        if (!res) return;

        var dish_id = $("#dish-id").val();
        var order_id = $("#order_id").val();
        var count = $("#numOfItems").text();
        $('#thirdModal').html('');
        // console.log(count);
        $.ajax({
            type:"POST",
            url:"{{ route('customer.orderNow_Photo') }}",
            data:{
                order_id: order_id, dish_id: dish_id, count: count, selected_items_id: selected_items_id, _token: "{{ csrf_token() }}"
            },
            success: function(result){
                console.log(result);
                Global_format();

                var thx = $("#myModal").html($("#thx").html());
                $('#thirdModal').modal('hide');
                $("#myModal").modal("show");
            }
        });

    }

    //lang_select
    function lang_select() {
        $('#myModal').html('');
        $.ajax({
            type:"GET",
            url:"{{ route('customer.lang_select') }}",
            data:{},
            success: function(result){
                $('#myModal').html(result);
            }
        });
        $("#myModal").modal("toggle");
    }

    //feedback
    function feedBack() {
        var order_id = $("#order_id").val();
        $('#myModal').html('');
        $.ajax({
            type:"GET",
            url:"{{ route('customer.feedback') }}",
            data:{order_id: order_id},
            success: function(result){
                $('#myModal').html(result);
            }
        });
        $("#myModal").modal("toggle");
    }

    function add_review() {
        var order_id = $("#order_id").val();
        var review_type = $("#review_type").val();
        review_type = (review_type == '')? 1 : review_type;
        var review = $("#review").val();
        $.ajax({
            type:"POST",
            url:"{{ route('customer.add_review') }}",
            data:{
                order_id: order_id, review_type: review_type, review: review,table_id: {{ $table_id }},_token:"{{ csrf_token() }}"
            },
            success: function(result){
                // console.log(result);
            }
        });
        $('#myModal').modal('hide');
    }

    //call staff
    $('#calling_staff').click(function(){

        var table_id = <?php echo $table_id; ?>;
        $.ajax({
            type:"POST",
            url:"{{ route('customer.calling') }}",
            data:{
                table_id: table_id, _token:"{{ csrf_token() }}"
            },
            success: function(result){
                console.log('call result', result);
                // if(result != '') {
                //     document.getElementById("calling_staff").style.background="#C9B92E";
                //     document.getElementById("calling_staff").innerHTML="<img src=\"{{asset('img/calling_staff.png')}}\" width=\"60px\">\n" +
                //         "                    <h3 style=\"color: white;\">CALLING</h3>";
                // } else {
                //     document.getElementById("calling_staff").style.background="";
                //     document.getElementById("calling_staff").innerHTML="<img src=\"{{asset('img/call_staff.png')}}\" width=\"60px\" style=\"margin-top: 10px;\">\n" +
                //         "                    <h3>CALL STAFF</h3>";
                // }
            }
        });
    });

    //view bill pay
    function view_bill_pay() {
        $('#myModal').html('');
        var order_id = $("#order_id").val();
        $.ajax({
            type:"GET",
            url:"{{ route('customer.view_bill_pay') }}",
            data:{order_id: order_id},
            success: function(result){
                $('#myModal').html(result);
            }
        });
        $("#myModal").modal("toggle");
    }

    //remove a dish from order
    function remove_dish(id) {
        var order_id = $("#order_id").val();
        $.ajax({
            type: "POST",
            url: "{{ route('customer.remove_dish') }}",
            data: {
                id: id,
                order_id: order_id,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                $('#myModal').html(result);
            }
        })
    }

    function pay_finish(order_id, table_name) {
        $.ajax({
            type:"POST",
            url:"{{ route('customer.check_finish_pay') }}",
            data:{
                order_id: order_id,
                table_name: table_name,
                _token:"{{ csrf_token() }}"
            },
            success: function(result){
                if (result.error) {
                    alert(result.alert);
                } else {
                    window.location = '{{ route("customer.finish_pay") }}' + `?order_id=${order_id}&table_name=${table_name}`;
                }
            }
        });
    }

    var myVar = setInterval(myTimer, 1000);

    function myTimer() {

        var order = <?php echo json_encode($order) ?>;

        var current_time =  new Date();
        var duration = '';
        var elapsed_time = '';
        var order_time = '';
        var additional = order.additional;

        duration = order.duration;

        order_time = order.time;
        var dateParts = order_time.substr(0,10).split('-');
        var timePart = order_time.substr(11);
        order_time = dateParts[1] + '/' + dateParts[2] + '/' + dateParts[0] + ' ' + timePart;
        order_time = new Date(order_time);

        if(duration == 0) {
            document.getElementById("time").innerHTML = 'テイクアウト';
        } else if(duration == 1) {
            order_time.setMinutes( order_time.getMinutes() + 30 );
            elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
            elapsed_time /= 60;
            elapsed_time = Math.round(elapsed_time) + additional;
            if(elapsed_time > 0) {
                document.getElementById("time").innerHTML = elapsed_time;
            }
            else {
                document.getElementById("time").innerHTML = '0';
            }
            document.getElementById("time").style.color = elapsed_time > 15 ? 'white' : 'orange';
        } else if(duration == 2) {
            order_time.setMinutes( order_time.getMinutes() + 60 );
            elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
            elapsed_time /= 60;
            elapsed_time = Math.round(elapsed_time) + additional;
            if(elapsed_time > 0) {
                document.getElementById("time").innerHTML = elapsed_time;
            }
            else {
                document.getElementById("time").innerHTML = '0';
            }
            document.getElementById("time").style.color = elapsed_time > 15 ? 'white' : 'orange';
        } else if(duration == 3) {
            order_time.setMinutes( order_time.getMinutes() + 90 );
            elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
            elapsed_time /= 60;
            elapsed_time = Math.round(elapsed_time) + additional;
            if(elapsed_time > 0) {
                document.getElementById("time").innerHTML = elapsed_time;
            }
            else {
                document.getElementById("time").innerHTML = '0';
            }
            document.getElementById("time").style.color = elapsed_time > 15 ? 'white' : 'orange';
        } else if(duration == 4) {
            order_time.setMinutes( order_time.getMinutes() + 120 );
            elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
            elapsed_time /= 60;
            elapsed_time = Math.round(elapsed_time) + additional;
            if(elapsed_time > 0) {
                document.getElementById("time").innerHTML = elapsed_time;
            }
            else {
                document.getElementById("time").innerHTML = '0';
            }
            document.getElementById("time").style.color = elapsed_time > 15 ? 'white' : 'orange';
        } else if(duration == 5) {
            document.getElementById("time").innerHTML = '—';
        }

        //     document.getElementById("time").innerHTML = "—";
    }

</script>
<div id="myModal" class="modal"></div>
<div id='thx' class="display-none">
    <div class="modal-content text-center thx-modal-content">
        <div class="close-btn">
            {{--<span class="close" onclick="$('#myModal').modal('toggle')">&times;</span>--}}
            <img src="{{asset('img/close.png')}}" style="width:40px;height: 40px;" class="close" onclick="$('#myModal').modal('toggle')" />
        </div>
        <div class="greeting-letter">
            <h3>ありがとうございました!</h3>
        </div>
        <div class="order-complete">
            <h3>ご注文を受け付けました</h3>
        </div>
        <div class="padding10">
            <button class="complete-btn" onclick="$('#myModal').modal('toggle');$('#myModal').html('')">
                {{ __('admin.Common.Apply') }}
            </button>
        </div>
    </div>
</div>
<div id="thirdModal" class="modal"></div>
{{--<div id="feedbackModal" class>
    l"></div>--}}

</body>
</html>
