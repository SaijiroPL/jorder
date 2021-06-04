<div class="tip_modal">
    <div class="row" style="margin: 0 0 10px 50px;">
        <span style="color: white;font-weight: 500;font-size: 25px;margin: 4px 10px 0 0;">チップ:</span>
        <span style="background: white;">
            <input id="tip" name="tip" value="" style="font-size: 30px;text-align: right;border-style: solid;border-width: 1px;width: 200px;" disabled />
        </span>
    </div>
    <div class="row">
        <table style="border-spacing: 10px;border-collapse: separate;margin:0 0 0 75px;">
            <tr>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(7)"><span class="fs-35">7</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(8)"><span class="fs-35">8</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(9)"><span class="fs-35">9</span></td>
            </tr>
            <tr>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(4)"><span class="fs-35">4</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(5)"><span class="fs-35">5</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(6)"><span class="fs-35">6</span></td>
            </tr>
            <tr>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(1)"><span class="fs-35">1</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(2)"><span class="fs-35">2</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(3)"><span class="fs-35">3</span></td>
            </tr>
            <tr>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip('c')"><span class="fs-35">C</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip(0)"><span class="fs-35">0</span></td>
                <td class="number_panel" style="background: #D8D8D8;color:black" onclick="onNumber_tip('.')"><span class="fs-35">.</span></td>
            </tr>
        </table>
    </div>
    <div class="row">
        <span class="amend_btn" style="background: white;color: black;margin: 12px 0 0 29px;" onclick="onCancel()"><aa class="fs-20" style="margin-right: 20px;">{{ __('admin.Common.Cancel') }}</aa>
            <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin-left: 20px;">
        </span>
        <span class="amend_btn" style="background: #1EC2C9;color: white;margin: 12px 0 0 27px;" onclick="onTipEnter($('#tip').val())"><aa class="fs-20" style="margin-right: 20px;">{{ __('admin.Common.Enter') }}</aa>
            <img src="{{ asset('img/Group728white.png') }}" style="height:18px; margin-left: 20px;">
        </span>
    </div>
</div>

<script>
    function onNumber_tip(number){
        var tip_obj = $("#tip");
        var origin_number = tip_obj.val();
//        if(parseFloat(origin_number) > 0){
            if(number != "c"){
                origin_number = origin_number.toString() + number.toString();
            }
            if(number == "c"){
                origin_number = '';
            }
//        }else{
//            if(number == "c"){
//                origin_number = 0;
//            }else{
//                origin_number = number;
//            }
//        }
        tip_obj.val(origin_number);
    }
</script>


