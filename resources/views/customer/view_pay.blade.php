<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=0, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        ありがとうございました
    </title>

</head>

<body style="background-color: black;height: 100%;">
    <div id="app">
        <p>
            <pay-finish-component order_id="{{ $order_id }}"></pay-finish-component>
        </p>
        <p>
            <finish-and-pay-component order_id="{{ $order_id }}"></finish-and-pay-component>
        </p>
        <div class="white-square">
            <div style="text-align: center; margin-top: 55px; margin-bottom: 15px;">
                <div style="font-size: 50px;font-weight: 400;margin-bottom: 20px;">
                    <span>ありがとうございました</span>
                </div>
                <div style="font-size: 25px;">
                    <span>
                        請求書が作成されました。<br>タブレットをレジに持っていき、お支払いください。
                    </span>
                </div>
            </div>

            <div style="text-align: center;">
                <table style="background-image: url({{ asset('img/paper.png') }}); background-repeat: no-repeat;background-position:center;margin-left: 250px;
                        height: 360px; width: 320px; padding: 20px 30px 90px 30px;">
                    <tr>
                        <td align="left" colspan="2">
                            <span style="font-size: 18px;">{{ __('customer.TABLE_NUMBER') }}:</span><br>
                            <span style="font-size: 22px;"><b>{{ $table_name }}</b></span>
                        </td>
                    </tr>
                    <tr><td height="10px"></td></tr>
                    <tr>
                        <td align="left" colspan="2">
                            <span style="font-size: 18px;">{{ __('customer.START_DATE') }}:<br></span>
                            <span style="font-size: 22px;"><b>{{date('Y年m月d日 H:i', strtotime($starting_time))}}</b></span>
                        </td>
                    </tr>
                    <tr><td height="10px"></td></tr>
                    <tr>
                        <td align="left">
                            <span style="font-size: 22px;"><b>{{ __('reception.TOTAL') }}:</b></span>
                        </td>
                        <td align="left">
                            <span style="font-size: 22px;"><b>¥{{ $total }}</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="left">
                            <span style="font-size: 18px;">{{ __('reception.WITHOUT GST') }}:</span>
                        </td>
                        <td align="left">
                            <span style="font-size: 18px;">¥{{ $without_gst_price }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td align="left">
                            <span style="font-size: 18px;">{{ __('reception.GST') }}:</span>
                        </td>
                        <td align="left">
                            <span style="font-size: 18px;">¥{{ $gst_price }}</span>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</body>

<script src="{{ asset('js/app.js') }}"></script>

<style>
    .white-square {
        background-color: white;
        width: 80%;
        height: 80%;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>
