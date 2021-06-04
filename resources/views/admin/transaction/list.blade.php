@extends('layout.admin_layout')

@section('title', 'Transactions')

@section('content')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            $( "#search_trans_date" ).datepicker({ dateFormat: 'yy年mm月dd日' });
        });
    </script>

    <div style="padding-top:8%;" class="pttbook"></div>
    <div class="widthh pt-4 blackgrey">
        <div class="row">
            <div class="col-4">
                <h4 class="text-white h4-responsive font-weight-bold ml-3 fs-30">取引履歴</h4>
            </div>
            <div class="col-4">
                <input type="text" id="all_amount" style="background:white;border:none;text-align:center;font-size: 20px;height: 35px" value="今日の合計金額: ¥{{ $daily_all_amount }}" readonly/>
            </div>
            <div class="col-3" style="text-align: right;top: 5px;">
                {{-- <a class="src_trans fs-25" onclick="now_sendmail()">
                    Finish the day now
                </a> --}}
            </div>
            <div class="col">
                <a onclick="window.history.back()">
                    <span class="">
                        <img src="{{ asset('img/Group826.png') }}" width="25" height="25" class="float-right" />
                    </span>
                </a>
            </div>
        </div>
        <div class="row mb-5 mt-5">
            <div class="col-4">
                <a href="{{ route('admin.transaction', ['search_date' => $search_date, 'd_s' => 'down']) }}">
                    <img src="{{ asset('img/Path501.png') }}" class="mb-3" height="30" />
                </a>
                <label class="text-white ml-3 mr-3 font-weight-light pt-2 fs-25" id="search_day">
                    {{ $search_display_date }}
                </label>
                <a href="{{ route('admin.transaction', ['search_date' => $search_date, 'd_s' => 'up']) }}">
                    <img src="{{ asset('img/Path502.png') }}" class="mb-3" height="30" />
                </a>
            </div>
            <div class="col-4" style="text-align: right;">
                <input type="text" id="search_trans_date" style="height: 35px"/>
            </div>
            <div class="col-1"></div>
            <div class="col-3" style="margin-top: 8px;">
                <a class="src_trans fs-25" onclick="search_transaction()">
                    検索
                    <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin-left: 5px;">
                </a> <br><br>
                <a class="src_trans fs-25" onclick="search_transaction()" data-toggle="modal" data-target="#setTransactionModal">
                    レジ内管理
                    <img src="{{ asset('img/Group728black.png') }}" style="height:18px; margin-left: 5px;">
                </a>
            </div>
        </div>
        @if($curTrans)
        <div class="row fs-20 text-white ml-2">
            <span class="ml-3">現金残高:￥{{ round($curTrans->cash_balance) }}</span>
            <span class="ml-3">釣銭準備金:￥{{ round($curTrans->charge_reserve) }}</span>
            <span class="ml-3">銀行入金:￥{{ round($curTrans->bank_deposit) }}</span>
        </div>
        @endif
        <div class="row" id="data_view">
            <div class="col-12">
                <table style="width: 96%;color: white;margin: 20px 0 0 15px;border-bottom: 1px solid white;">
                    <thead>
                    <tr>
                        <td class="border-0" scope="col" width="15%" align="left">
                            <a href="{{route("admin.transaction", ["sortType" => $sort, 'search_date' => $search_date])}}" class="text-white">
                                <b>日時</b>
                                @if($sort == "asc")
                                    <img src="{{ asset('img/Path444.png') }}" style="height:18px;margin-top:-5px;" />
                                @else
                                    <img src="{{ asset('img/Path445.png') }}" style="height:18px;margin-top:-5px;" />
                                @endif
                            </a>
                        </td>
                        <td class="border-0" scope="col" width="30%" align="left"><b>テーブル</b></td>
                        <td class="border-0" scope="col" width="15%" align="left"><b>金額</b></td>
                        <td class="border-0" scope="col" width="25%" align="left"><b>客様</b></td>
                        <td class="border-0" scope="col" width="15%" align="left"></td>
                    </tr>
                    </thead>
                </table>
                <div style="height: 55vh;overflow-y: auto;">
                    <table class="table text-white txtdemibold" style="width: 96%; margin-left:15px;">
                        <tbody class="thh">
                        @if($order_obj)
                            @foreach($order_obj as $order)
                                <tr>
                                    <td width="15%" style="padding-left: 0;">{{ $order->display_time }}</td>
                                    <td width="30%" style="padding-left: 4px;">{{ $order->table_display_name }}</td>
                                    <td width="15%" style="padding-left: 4px;">{{ $order->amount }}</td>
                                    <td width="25%" style="padding-left: 6px;">{{ $order->order->customer_name }}</td>
                                    <td width="15%" style="text-align:center;padding-left: 9px;"></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="setTransactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.transaction.cashinput') }}" method="post">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <img src="{{ asset('img/Group1101.png') }}"  style="width:25px;height:25px;" class="float-right" />
                        </button>
                    </div>
                    <div class="modal-body pr-4">
                        <h5 class="text-info font-weight-normal fs-20">日时: {{ $search_display_date }}</h5>
                        <h5 class="text-info font-weight-normal fs-20">現金残高</h5>
                        <input class="form-control pl-3" style="font-size: 25px;" type="number" name="cash_balance" id="cash_balance" value="{{ $prevRemain }}">
                        <h5 class="text-info font-weight-normal fs-20">釣銭準備金</h5>
                        <input class="form-control pl-3" style="font-size: 25px;" type="number" name="charge_reserve" id="charge_reserve" onchange="onChangechanged()">
                        <h5 class="text-info font-weight-normal fs-20">銀行入金</h5>
                        <input class="form-control pl-3" style="font-size: 25px;" type="number" name="bank_deposit" id="bank_deposit">
                        <input type="hidden" name="trans_date" value="{{ $search_date }}">
                        @csrf
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light waves-effect waves-light fs-20" data-dismiss="modal">
                            {{ __('admin.Common.Cancel') }}
                            <img src="{{ asset('img/Group728.png') }}" height="18" class="mb-1" />
                        </button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light fs-20" onclick="submit()">
                            {{ __('admin.Common.Apply') }}
                            <img src="{{ asset('img/Group728white.png') }}" height="18" class="mb-1" />
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<style>
    #search_trans_date {
        background: white;
        font-size: 25px;
        width: 300px;
        height: 45px;
        padding-left: 10px;
        padding-right: 10px;
        text-align: center;
        margin-top: 7px;
    }

    .src_trans {
        background: white;
        font-size: 16px;
        padding: 5px 20px 5px 20px;
        border-radius: 5px;
        font-weight: 500;
    }
</style>

<script>

    function search_transaction() {
        var src_date = $('#search_trans_date').val();
        if (src_date) {
            var src_dates = `${src_date.substring(0, 4)}-${src_date.substring(5, 7)}-${src_date.substring(8, 10)}`
            console.log(src_dates);
            window.location = `{{ route('admin.transaction') }}?search_date=${src_dates}`;
        }
    }

    function now_sendmail() {
        $.ajax({
            type:"GET",
            url:"{{ route('admin.now_sendmail') }}",
            data:{},
            success: function(result){
                //alert("asd");
            }
        });
    }

    function reprint(order_id) {

    }

    function onChangechanged() {
        var remain = $('#cash_balance').val();
        var change = $('#charge_reserve').val();
        var bank_placeholder = remain - change;
        $('#bank_deposit').val(bank_placeholder);
    }

</script>
