<div class="tip_modal">
    <div class="row" style="margin: 0 0 10px 50px;">
        <span style="color: white;font-weight: 500;font-size: 25px;margin: 4px 10px 0 0;">{{ __('reception.AMOUNT_CHANGE') }}:</span>
        <span style="background: white;">
            <input id="discount" name="discount" value="" style="font-size: 30px;text-align: right;border-style: solid;border-width: 1px;width: 100px;" disabled />
        </span>
    </div>
    <div class="row">
        {{--<table style="border-spacing: 10px;border-collapse: separate;margin:0 0 0 36px;">--}}
        <table style="border-spacing: 10px;border-collapse: separate;margin:0 0 0 75px;">
            <tr>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis(7)"><span class="fs-35">7</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis(8)"><span class="fs-35">8</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis(9)"><span class="fs-35">9</span></td>
                {{--<td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis('¥')">$</td>--}}
            </tr>
            <tr>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis(4)"><span class="fs-35">4</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis(5)"><span class="fs-35">5</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis(6)"><span class="fs-35">6</span></td>
                {{--<td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis('%')">%</td>--}}
            </tr>
            <tr>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis(1)"><span class="fs-35">1</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis(2)"><span class="fs-35">2</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis(3)"><span class="fs-35">3</span></td>
            </tr>
            <tr>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis('c')"><span class="fs-35">C</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis('+')"><span class="fs-35">+</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_dis('-')"><span class="fs-35">-</span></td>
            </tr>
        </table>
    </div>
    <div class="row">
        <span class="amend_btn" style="background: white;color: black;margin: 12px 0 0 29px;" onclick="onCancel()">
            <aa class="fs-20" style="margin-right: 20px;">{{ __('admin.Common.Cancel') }}</aa>
            <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin-left: 20px;">
        </span>
        <span class="amend_btn" style="background: #1EC2C9;color: white;margin: 12px 0 0 27px;" onclick="onDiscountEnter($('#discount').val())">
            <aa class="fs-20" style="margin-right: 20px;">{{ __('admin.Common.Enter') }}</aa>
            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
        </span>
    </div>
</div>

<script>
    function onNumber_dis(number){
        var discount_obj = $("#discount");
        var origin_number = discount_obj.val();
//        if(parseInt(origin_number) > 0){
            if(number != "c"){
                origin_number = origin_number.toString() + number.toString();
            }
            if(number == "c"){
                origin_number = '';
            }
//        }else{
//            if(number == "back" || number == "c"){
//                origin_number = 0;
//            }else{
//                origin_number = number;
//            }
//        }
        discount_obj.val(origin_number);
    }
</script>


