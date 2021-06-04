<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    {{--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">--}}
    <meta name="viewport" content="width=device-width, user-scalable=0, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    {{--<meta http-equiv="refresh" content="60">--}}
    <title>
        Accounting
    </title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" media="screen">
    <link href="{{ asset('css/timepicki.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

</head>

<div class="modal fade" id="java-alert" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="margin-top: -150px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                </button>
            </div>
            <div class="modal-body pr-4">
                <p id="alert-string" class="text-center fs-20"></p>
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


<body>
<!-- <form method="POST" id="save-pay" action="{{ route('reception.pay', ['status' => $status]) }}"> -->
<form>
    @csrf
    <input type="hidden" id="order_time" value="{{ $time }}" />
    <input type="hidden" id="order_id" value="{{ $order_id }}" />
    <input type="hidden" id="duration" value="{{ $duration }}" />
    <div class="accounting" id="accounting">
        <div class="accounting_header">
                {{--<span class="close" style="margin: 0 35px 0 0;" onclick="window.history.back()">--}}
                <a class="close" style="margin: 0 35px 0 0;" href="{{ route('reception.seated', ['status' => 'seated']) }}">
                    <h2>
                        <img src="{{ asset('img/Group1101.png') }}" width="25" height="25" class="float-right mt-3 mr-3" />
                    </h2>
                </a>
            <div class="col-lg-11 pr-0 col-xl-11" style="padding: 20px 0 0 40px;">
                <div>
                    <h5><span class="fs-25" style="font-weight: 700;">{{ __('reception.NAME') }}: </span><span class="fs-25">{{ $customer_name }}</span></h5>
                </div>
                <div style="margin-bottom: 20px;">
                    <h5>
                        <span class="fs-25" style="font-weight: 700;">{{ __('reception.TIME') }}: </span><span class="fs-25">{{ $starting_time }}</span> &nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="fs-25" style="font-weight: 700;">{{ __('reception.DURATION') }}: </span><span class="fs-25">{{ $duration_time }}</span>
                        <span class="fs-25" id="during_time"></span>
                    </h5>
                </div>
                <div>
                    <table>
                        <tr>
                            <td width="50px"><img src="{{asset('img/head1.png')}}" width="45px" height="45px"></td>
                            <td width="80px" align="left"><span style="font-size: 30px;"><b>{{ $guest }}</b></span></td>
                            @foreach($table_name as $tb_nm)
                                @if($table_name[0] != $tb_nm)
                                    <td width="70px" align="center"><img src="{{asset('img/plus_red.png')}}"></td>
                                @endif
                                <td style="width: 100px;height: 100px;background: #000;color:white;text-align: center;-ms-word-break: break-all;word-break: break-all;">
                                    <b class="fs-25">
                                        @if(strlen($tb_nm) > 12)
                                            {{ substr($tb_nm, 0, 12)."..." }}
                                        @else
                                            {{ $tb_nm }}
                                        @endif
                                    </b>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-7">
                <div class="accounting_content">
                    <table class="account_tb_hd">
                        <tr>
                            <td class="td1"><h6><span class="fs-25" style="font-weight: 700;">{{ __('reception.ITEM') }}</span></h6></td>
                            <td class="td2"><h6><span class="fs-25" style="font-weight: 700;">{{ __('reception.QTY') }}</span></h6></td>
                            <td class="td3"><h6><span class="fs-25" style="font-weight: 700;">{{ __('reception.EACH') }}</span></h6></td>
                            <td class="td4"><h6><span class="fs-25" style="font-weight: 700;">{{ __('reception.SUB_TOTAL') }}</span></h6></td>
                        </tr>
                    </table>
                    <div style="width: 622px;height: 400px;overflow-x: hidden;overflow-y: auto;" id="item_list">
                        <table class="account_tb_ct" id="data">
                            @if(count($order_dishes) > 0)
                                @foreach($order_dishes as $order_dish)
                                    {{--<tr onclick="select_item({{ $order_dish->id }})">--}}
                                    <tr onclick="select_item({{ $order_dish->id }})" id="item_{{ $order_dish->id }}">
                                        <td class="td1" id="td_{{ $order_dish->id }}" style="color:black;"><h6><span class="fs-25">{{ $order_dish->dish_name_en }}
                                                    @foreach($order_dish->options as $option)
                                                        [{{ $option->option_name }}: {{ $option->item_name }}]
                                                    @endforeach
                                            </span></h6></td>
                                        <td class="td2"><h6><span class="fs-25">{{ $order_dish->count }}</span></h6></td>
                                        <td class="td3"><h6><span class="fs-25">
                                                @if($order_dish->each_price)
                                                ¥{{ $order_dish->each_price }}
                                                    @endif</span></h6></td>
                                        <td class="td4"><h6><span class="fs-25">
                                                @if($order_dish->sub_total)
                                                ¥{{ $order_dish->sub_total }}
                                                    @endif
                                            </span></h6></td>
                                    </tr>
                                @endforeach
                                @if(count($order_dishes) < 5)
                                    @for($i=0;$i<5-count($order_dishes);$i++)
                                        <tr onclick="select_item(0)">
                                            <td class="td1"></td>
                                            <td class="td2"></td>
                                            <td class="td3"></td>
                                            <td class="td4"></td>
                                        </tr>
                                    @endfor
                                @endif
                            @else
                                <tr>
                                    <td></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <table class="account_tb_ft">
                        <tr>
                            <td class="td1">
                                <span class="amend_btn" onclick="onAddItem()">
                                    <am class="fs-25" id="am">アイテムの追加</am>
                                    <img src="{{ asset('img/Group728white.png') }}" style="height: 22px;margin-top: -5px;">
                                </span>
                            </td>
                            <td onclick="select_item(0)"></td>
                            <td onclick="select_item(0)"></td>
                            <td onclick="select_item(0)"></td>
                        </tr>
                    </table>
                </div>
                <div class="accounting_footer">
                    <div class="row">
                        <div class="col-6" style="border-right: 2px solid black;">
                            <table>
                                <tr>
                                    <td width="40px" onclick="onTip()"><img src="{{asset('img/chat22.png')}}" width="50px" height="50px"></td>
                                    <td width="120px"><span style="font-size: 20px;float: left;">{{ __('reception.TIP') }}:</span></td>
                                    <td width="200px"><span style="font-size: 20px;float: right;" id="tip_value">¥0</span></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><span style="font-size: 20px;float: left;">{{ __('reception.SUB_TOTAL') }}:</span></td>
                                    <td><span style="font-size: 20px;float: right;" id="sub_total">¥{{ $total }}</span></td>
                                    <input type="hidden" id="tmp_sub_total" value="{{ $total }}">
                                    <input type="hidden" id="gst" value="{{ $gst }}">
                                </tr>
                                <tr>
                                    <td onclick="onDiscount()"><img src="{{asset('img/chat22.png')}}" width="50px" height="50px"></td>
                                    <td><span style="font-size: 20px;float: left;">{{ __('reception.DISCOUNT') }}:</span></td>
                                    <td><span style="font-size: 20px;float: right;" id="discount_value">¥0(0%)</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-6" style="">
                            <table>
                                <tr>
                                    <td width="140px"><span style="font-size: 20px;float: left;"><b>{{ __('reception.TOTAL') }}:</b></span></td>
                                    <td width="190px"><b><span style="font-size: 20px;float: right;" id="total">¥{{ $total }}</span></b></td>
                                </tr>
                                <tr>
                                    <td><span style="font-size: 20px;float: left;">{{ __('reception.GST') }}:</span></td>
                                    <td><span style="font-size: 20px;float: right;" id="gst_pr">¥{{ $gst_price }}</span></td>
                                </tr>
                                <tr>
                                    <td><span style="font-size: 20px;float: left;"><b>{{ __('reception.BALANCE_DUE') }}</b>:</span></td>
                                    <td><b><span style="font-size: 20px;float: right;" id="balance">¥{{ $total + $gst_price }}</span></b></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="row">
                    <div class="col-11" style="margin-bottom: 40px;">
                        <table>
                            <tr>
                                <td width="130px"><span style="font-size: 25px;float: left;">{{ __('reception.BALANCE_DUE') }}:</span></td>
                                <td width="150px"><span style="font-size: 25px;float: right;" id="balance1">¥{{ round($total + $gst_price, 0) }}</span></td>
                            </tr>
                            <tr>
                                <td><span class="mt-3" style="font-size: 25px;float: left;">{{ __('reception.AMOUNT_TENDERED') }}:</span></td>
                                <td>
                                    <input class="mt-3" type="text" id="amount_tender" name="amount_tender" value=""
                                           style="font-size: 25px;text-align: right;border-style: solid;
                                               border-width: 1px;padding-right: 5px;height: 40px;"
                                           disabled />
                                </td>
                            </tr>
                            <tr>
                                <td><span class="mt-3" style="font-size: 25px;float: left;">{{ __('reception.CHANGE') }}:</span></td>
                                <td><span class="mt-3" style="font-size: 25px;float: right;color: #F00;" id="change"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-7" style="margin:0 0 0 5px">
                        <table style="border-spacing: 10px;border-collapse: separate;">
                            <tr>
                                <td class="number_panel" onclick="onNumber(7)"><span class="fs-35">7</span></td>
                                <td class="number_panel" onclick="onNumber(8)"><span class="fs-35">8</span></td>
                                <td class="number_panel" onclick="onNumber(9)"><span class="fs-35">9</span></td>
                            </tr>
                            <tr>
                                <td class="number_panel" onclick="onNumber(4)"><span class="fs-35">4</span></td>
                                <td class="number_panel" onclick="onNumber(5)"><span class="fs-35">5</span></td>
                                <td class="number_panel" onclick="onNumber(6)"><span class="fs-35">6</span></td>
                            </tr>
                            <tr>
                                <td class="number_panel" onclick="onNumber(1)"><span class="fs-35">1</span></td>
                                <td class="number_panel" onclick="onNumber(2)"><span class="fs-35">2</span></td>
                                <td class="number_panel" onclick="onNumber(3)"><span class="fs-35">3</span></td>
                            </tr>
                            <tr>
                                <td class="number_panel" onclick="onNumber('c')"><span class="fs-35">C</span></td>
                                <td class="number_panel" onclick="onNumber(0)"><span class="fs-35">0</span></td>
                                <td class="number_panel" onclick="onNumber('.')"><span class="fs-35">.</span></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="key_panel" style="width: 120px;height: 67px;color:#777777;font-weight: 500;" onclick="Payment('CASH')">
                                    <span class="fs-25">{{ __('reception.CASH') }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-5" style="margin: 0 0 0 -35px;">
                        <table style="border-spacing: 10px;border-collapse: separate;">
                            <tr>
                                <td class="key_panel_1" style="background: #ADACAC;color:#FFFFFF;width: 180px;height: 70px;" onclick="Exact()">
                                    <span class="fs-25">{{ __('reception.EXACT') }}</span>
                                </td>
                            </tr>
                            @foreach($payment_method as $payment)
                                <tr>
                                    <td class="key_panel_1" onclick="Payment('{{$payment->name}}')" style="height: 70px;">
                                        <span class="fs-25">{{ $payment->name }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

<div class="modal fade" id="java-alert-confirm" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="margin-top: -150px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/Group1101.png') }}" style="width:25px;height:25px;" class="float-right" />
                </button>
            </div>
            <div class="modal-body pr-4">
                <p id="alert-string-confirm" class="text-center fs-20"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect waves-light fs-20" id="cancelbtn">
                    前の画面に戻る
                    <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                </button>
                <button type="button" class="btn btn-light waves-effect waves-light fs-20" id="confirmbtn">
                    {{ __('admin.Common.Apply') }}
                    <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                </button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="paymethod" value="" name="paymethod"/>

<script>
    var order_dish_id = 0;
    //amend button click
    function onAddItem() {
        var order_id = $('#order_id').val()
        $.ajax({
            type:"GET",
            url:"{{ route('reception.amend') }}",
            data:{order_id: order_id, order_dish_id: order_dish_id},
            success: function(result){
                $('#thirdModal').html(result);
            }
        });
        $('#thirdModal').modal("toggle");
    }

    function select_item(selected_dish_id) {

        var select_color = document.getElementsByClassName('td1');
        for(var i=0;i<select_color.length;i++) {
            select_color[i].style.color = 'black';
        }
        document.getElementById('td_'+selected_dish_id).style.color = 'blue';

        order_dish_id = selected_dish_id;

        if(order_dish_id == 0) {
            document.getElementById('am').innerHTML = 'アイテムを追加';
        } else {
            document.getElementById('am').innerHTML = '修正';
        }
    }

    function onNumber(number){
        var amount_tender_obj = $("#amount_tender");
        var origin_number = amount_tender_obj.val();
        if(number != "c"){
            origin_number = origin_number.toString() + number.toString();
        }
        if(number == "c"){
            origin_number = '¥';
        }
        amount_tender_obj.val(origin_number)
    }

    function onTip() {
        $.ajax({
            type:"GET",
            url:"{{ route('reception.tip') }}",
            data:{},
            success: function(result){
                $('#thirdModal').html(result);
            }
        });
        $('#thirdModal').modal("toggle");
    }

    function onDiscount() {

        $.ajax({
            type:"GET",
            url:"{{ route('reception.discount') }}",
            data:{},
            success: function(result){
                // console.log(result);
                $('#thirdModal').html(result);
            }
        });
        $('#thirdModal').modal("toggle");
    }
    function onCancel() {

        $('#thirdModal').html('');
        $('#thirdModal').modal("hide");
    }
    //onAddItemApply
    function onAddItemApply() {


    }
    //onTipEnter
    function onTipEnter(tip_obj) {

        $('#thirdModal').html('');
        $('#thirdModal').modal("hide");

        //display tip
        if(tip_obj == ''){
            document.getElementById("tip_value").textContent = '¥0';
        }else {
            document.getElementById("tip_value").textContent = `¥${tip_obj}`;
        }
        reCalculation();

        // //display sub total
        // // var tip = parseFloat(document.getElementById("tip_value").textContent.substring(1));
        // var tip = parseFloat($('#tip_value').html().substring(1));
        // var base_sub_total = parseFloat($("#tmp_sub_total").val().replace(',', ''));
        // document.getElementById("sub_total").textContent = '¥' + (tip + base_sub_total).toFixed(2).toString();

        // //display discount
        // var sub_total = parseFloat(document.getElementById("sub_total").textContent.substring(1)).toFixed(2);
        // var tmp_discount_obj = document.getElementById("discount_value").textContent.split('(');
        // var discount_obj = tmp_discount_obj[0].substring(1);
        // var percent = ((discount_obj/sub_total)*100).toFixed(2);
        // var percent_str = "(" + percent.toString() + "%" + ")";
        // document.getElementById("discount_value").textContent = tmp_discount_obj[0] + percent_str;

        // //display total
        // var total = (sub_total - parseFloat(tmp_discount_obj[0].substring(1)).toFixed(2)).toFixed(2);
        // document.getElementById("total").textContent = '¥' + total.toString();

        // //display gst
        // var gst_val = $('#gst').val();
        // var gst = ((total*gst_val)/100).toFixed(2);
        // document.getElementById("gst_pr").textContent = '¥' + (gst).toString();

        // //display without gst
        // // var without_gst = (total - gst).toFixed(2);
        // // document.getElementById("without_gst").textContent = '¥' + (without_gst).toString();

        // //display balance due
        // document.getElementById("balance").textContent = '¥' + total.toString();

        // //display balance1 due
        // document.getElementById("balance1").textContent = '¥' + total.toString();

    }
    //onDiscountEnter
    function onDiscountEnter(discount_obj) {

        $('#thirdModal').html('');
        $('#thirdModal').modal("hide");

        //display discount
        if(discount_obj == ''){
            document.getElementById("discount_value").textContent = '¥0(0%)';
        }else {
            document.getElementById("discount_value").textContent = '¥' + discount_obj;
        }

        reCalculation();
        // var sub_total = parseFloat(document.getElementById("sub_total").textContent.replace(',', '').substring(1)).toFixed(2);
        // var tmp_discount_obj = document.getElementById("discount_value").textContent.split('(');
        // var discount_obj = tmp_discount_obj[0].substring(1);
        // var percent = ((discount_obj/sub_total)*100).toFixed(2);
        // var percent_str = "(" + percent.toString() + "%" + ")";
        // document.getElementById("discount_value").textContent = document.getElementById("discount_value").textContent + percent_str;

        // //display total
        // var discount_val = parseFloat(tmp_discount_obj[0].substring(1)).toFixed(2);
        // var total = (sub_total - discount_val).toFixed(2);
        // document.getElementById("total").textContent = '¥' + total.toString();

        // //display gst
        // var gst_val = $('#gst').val();
        // var gst = ((total*gst_val)/100).toFixed(2);
        // document.getElementById("gst_pr").textContent = '¥' + (gst).toString();

        // //display without gst
        // // var without_gst = (total - gst).toFixed(2);
        // // document.getElementById("without_gst").textContent = '¥' + (without_gst).toString();

        // //display balance due
        // document.getElementById("balance").textContent = '¥' + total.toString();

        // //display balance1 due
        // document.getElementById("balance1").textContent = '¥' + total.toString();
    }

    function reCalculation() {
        var tip = parseFloat($('#tip_value').html().substring(1));
        var discount = $('#discount_value').html().split('(')[0].substring(1);
        var total = parseFloat($("#tmp_sub_total").val().replace(',', ''));
        var gst_val = $('#gst').val();
        var gst = ((total*gst_val)/100).toFixed(2);

        $('#sub_total').html(`¥${total + tip}`);
        $('#total').html(`¥${total + tip - discount}`);
        var tax = (total - discount) * gst_val / 100;
        $('#gst_pr').html(`¥${tax}`);
        $('#balance').html(`¥${total + tip - discount + tax}`);
        $('#balance1').html(`¥${total + tip - discount + tax}`);

        // document.getElementById("sub_total").textContent = '¥' + (tip + base_sub_total).toFixed(2).toString();

        // //display discount
        // var sub_total = parseFloat(document.getElementById("sub_total").textContent.substring(1)).toFixed(2);
        // var tmp_discount_obj = document.getElementById("discount_value").textContent.split('(');
        // var discount_obj = tmp_discount_obj[0].substring(1);
        // var percent = ((discount_obj/sub_total)*100).toFixed(2);
        // var percent_str = "(" + percent.toString() + "%" + ")";
        // document.getElementById("discount_value").textContent = tmp_discount_obj[0] + percent_str;

        // //display total
        // var total = (sub_total - parseFloat(tmp_discount_obj[0].substring(1)).toFixed(2)).toFixed(2);
        // document.getElementById("total").textContent = '¥' + total.toString();

        // //display gst
        // var gst_val = $('#gst').val();
        // var gst = ((total*gst_val)/100).toFixed(2);
        // document.getElementById("gst_pr").textContent = '¥' + (gst).toString();

        // //display without gst
        // // var without_gst = (total - gst).toFixed(2);
        // // document.getElementById("without_gst").textContent = '¥' + (without_gst).toString();

        // //display balance due
        // document.getElementById("balance").textContent = '¥' + total.toString();

        // //display balance1 due
        // document.getElementById("balance1").textContent = '¥' + total.toString();
    }

    function Exact() {
        var balance_due = document.getElementById("balance").textContent;
        $("#amount_tender").val(balance_due);
        var amount = parseFloat($('#amount_tender').val().replace(',', '').substring(1)).toFixed(2);
        var balance = parseFloat(balance_due.replace(',', '').substring(1)).toFixed(2);
        var change = amount - balance;
        document.getElementById("change").textContent = '¥' + change.toFixed(2).toString();
    }

    function Payment(pay_method) {

        document.getElementById('paymethod').value = pay_method;
        var order_dishes = <?php echo(json_encode($order_dishes))?>;
        var tip = parseFloat(document.getElementById("tip_value").textContent.substring(1)).toFixed(2);
        var sub_total = parseFloat(document.getElementById("sub_total").textContent.substring(1)).toFixed(2);
        var tmp_discount_obj = document.getElementById("discount_value").textContent.split('(');
        var discount = parseFloat(tmp_discount_obj[0].substring(1)).toFixed(2);
        var total = parseFloat(document.getElementById("total").textContent.substring(1)).toFixed(2);
        // var without_gst = parseFloat(document.getElementById("without_gst").textContent.substring(1)).toFixed(2);
        var gst = parseFloat(document.getElementById("gst_pr").textContent.substring(1)).toFixed(2);

        var am = $('#amount_tender').val();
        if(am.toString().indexOf('¥') < 0) {
            $('#amount_tender').val('¥' + am);
        }
        var amount = parseFloat($('#amount_tender').val().replace(',', '').substring(1)).toFixed(2);
        var balance = parseFloat(document.getElementById("balance").textContent.replace(',', '').substring(1)).toFixed(2);
        var change = amount - balance;
        if($('#amount_tender').val() == '¥') {
            //alert('There is no amount data.\nPlease input Amount data!');
            $("#alert-string")[0].innerText = "{{ __('reception.WRONG_AMOUNT') }}";
            $("#java-alert").modal('toggle');
        } else {
            if(change < 0) {
                //alert('Amount is smaller than Balance.\nPlease inpunt Amount correctly!');
                $("#alert-string")[0].innerText = "{{ __('reception.WRONG_AMOUNT') }}";
                $("#java-alert").modal('toggle');
                document.getElementById("change").textContent = '';
            } else {
                document.getElementById("change").textContent = '¥' + change.toFixed(2).toString();

                //regist to database
                var order_id = $('#order_id').val();
                var change = parseFloat(document.getElementById("change").textContent.replace(',', '').substring(1)).toFixed(2);

                $("#alert-string-confirm")[0].innerText = "{{ __('reception.CONFIRM_PAYMENT') }}";
                $("#cancelbtn").attr("onclick", "cancel_print()");
                $("#confirmbtn").attr("onclick", "account_print()");
                $("#java-alert-confirm").modal('toggle');

//                cancel_print();

            }
        }
    }

    function cancel_print() {
        $("#java-alert-confirm").modal('toggle');
        // var order_id = $('#order_id').val();
        // var pay_method = $('#paymethod').val();
        // var balance = parseFloat(document.getElementById("balance").textContent.replace(',', '').substring(1)).toFixed(2);
        // var amount = parseFloat($('#amount_tender').val().replace(',', '').substring(1)).toFixed(2);
        // var tip = parseFloat(document.getElementById("tip_value").textContent.substring(1)).toFixed(2);
        // var sub_total = parseFloat(document.getElementById("sub_total").textContent.substring(1)).toFixed(2);
        // var tmp_discount_obj = document.getElementById("discount_value").textContent.split('(');
        // var discount = parseFloat(tmp_discount_obj[0].substring(1)).toFixed(2);
        // var total = parseFloat(document.getElementById("total").textContent.substring(1)).toFixed(2);
        // var without_gst = parseFloat(document.getElementById("without_gst").textContent.substring(1)).toFixed(2);
        // var gst = parseFloat(document.getElementById("gst_pr").textContent.substring(1)).toFixed(2);
        // var change = parseFloat(document.getElementById("change").textContent.replace(',', '').substring(1)).toFixed(2);

        // $.ajax({
        //     type:"POST",
        //     url:"{{ route('reception.pay') }}",
        //     data:{ order_id: order_id, pay_method: pay_method, balance: balance, amount: amount, change: change, tip: tip, sub_total: sub_total, discount: discount, total: total, without_gst: without_gst, gst: gst, _token: "{{ csrf_token() }}" },
        //     success: function(result){
        //         $("#java-alert-confirm").modal('hide');
        //         window.location.href = "{{URL::to('reception/seated?status=seated')}}";
        //         //$("#save-pay").submit();
        //     }
        // });
    }

    function account_print() {

        var order_dishes = <?php echo(json_encode($order_dishes))?>;
        var order_id = $('#order_id').val();
        var pay_method = $('#paymethod').val();
        var balance = parseFloat(document.getElementById("balance").textContent.replace(',', '').substring(1)).toFixed(2);
        var amount = parseFloat($('#amount_tender').val().replace(',', '').substring(1)).toFixed(2);
        var tip = parseFloat(document.getElementById("tip_value").textContent.substring(1)).toFixed(2);
        var sub_total = parseFloat(document.getElementById("sub_total").textContent.substring(1)).toFixed(2);
        var tmp_discount_obj = document.getElementById("discount_value").textContent.split('(');
        var discount = parseFloat(tmp_discount_obj[0].substring(1)).toFixed(2);
        var total = parseFloat(document.getElementById("total").textContent.substring(1)).toFixed(2);
        var gst = parseFloat(document.getElementById("gst_pr").textContent.substring(1)).toFixed(2);
        var without_gst = (total - gst).toFixed(2);
        var change = parseFloat(document.getElementById("change").textContent.replace(',', '').substring(1)).toFixed(2);

        // $.ajax({
        //     type:"POST",
        //     url:"{{ route('reception.account_print') }}",
        //     data:{ order_id: order_id, order_dishes: order_dishes, pay_method: pay_method, balance: balance, amount: amount, change: change, tip: tip, sub_total: sub_total, discount: discount, total: total, without_gst: without_gst, gst: gst, _token: "{{ csrf_token() }}" },
        //     success: function(result){
        //         console.dir(result);
        //     }
        // });

        $.ajax({
            type:"POST",
            url:"{{ route('reception.pay') }}",
            data:{ order_id: order_id, pay_method: pay_method, balance: balance, amount: amount, change: change, tip: tip, sub_total: sub_total, discount: discount, total: total, without_gst: without_gst, gst: gst, _token: "{{ csrf_token() }}" },
            success: function(result){
                $("#java-alert-confirm").modal('hide');
                window.location.href = "{{URL::to('reception/seated?status=seated')}}";
                //$("#save-pay").submit();
            }
        });
    }

    //timer part
    var myVar = setInterval(myTimer, 1000);
    function myTimer() {

        var order_time = <?php echo json_encode($time) ?>;
        var dateParts = order_time.substr(0,10).split('-');
        var timePart = order_time.substr(11);
        order_time = dateParts[1] + '/' + dateParts[2] + '/' + dateParts[0] + ' ' + timePart;
        order_time = new Date(order_time);

        var duration = '<?php echo json_encode($duration) ?>';

        var current_time =  new Date();
        var elapsed_time = '';

        if(duration == 0) {
            document.getElementById("during_time").innerHTML = '(テイクアウト)';
        } else if(duration == 1) {
            order_time.setMinutes( order_time.getMinutes() + 30 );
            elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
            elapsed_time /= 60;
            elapsed_time = Math.round(elapsed_time);
            if(elapsed_time > 0) {
                document.getElementById("during_time").innerHTML = '(' + elapsed_time + '分)';
            }
            else {
                document.getElementById("during_time").innerHTML = '(0分)';
            }
        } else if(duration == 2) {
            order_time.setMinutes( order_time.getMinutes() + 60 );
            elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
            elapsed_time /= 60;
            elapsed_time = Math.round(elapsed_time);
            if(elapsed_time > 0) {
                document.getElementById("during_time").innerHTML = '(' + elapsed_time + '分)';
            }
            else {
                document.getElementById("during_time").innerHTML = '(0分)';
            }
        } else if(duration == 3) {
            order_time.setMinutes( order_time.getMinutes() + 90 );
            elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
            elapsed_time /= 60;
            elapsed_time = Math.round(elapsed_time);
            if(elapsed_time > 0) {
                document.getElementById("during_time").innerHTML = '(' + elapsed_time + '分)';
            }
            else {
                document.getElementById("during_time").innerHTML = '(0分)';
            }
        } else if(duration == 4) {
            order_time.setMinutes( order_time.getMinutes() + 120 );
            elapsed_time = (order_time.getTime() - current_time.getTime())/1000;
            elapsed_time /= 60;
            elapsed_time = Math.round(elapsed_time);
            if(elapsed_time > 0) {
                document.getElementById("during_time").innerHTML = '(' + elapsed_time + '分)';
            }
            else {
                document.getElementById("during_time").innerHTML = '(0分)';
            }
        } else if(duration == 5) {
            document.getElementById("during_time").innerHTML = '(無制限)';
        }

    }
</script>

<div id="thirdModal" class="modal"></div>
</body>
</html>

<style>
    .highlight { color: blue; }
</style>
