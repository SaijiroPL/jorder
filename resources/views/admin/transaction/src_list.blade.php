<div class="col-12">
    <table style="width: 96%;color: white;margin: 20px 0 0 15px;border-bottom: 1px solid white;">
        <thead>
        <tr>
            <td class="border-0" scope="col" width="15%" align="left">
                <a href="{{route("admin.transaction", ["sortType" => $sort, 'search_date' => $search_display_date])}}" class="text-white">
                    <b>TIME</b>
                    @if($sort == "asc")
                        <img src="{{ asset('img/Path444.png') }}" style="height:18px;margin-top:-5px;" />
                    @else
                        <img src="{{ asset('img/Path445.png') }}" style="height:18px;margin-top:-5px;" />
                    @endif
                </a>
            </td>
            <td class="border-0" scope="col" width="30%" align="left"><b>TABLE</b></td>
            <td class="border-0" scope="col" width="15%" align="left"><b>AMOUNT</b></td>
            <td class="border-0" scope="col" width="25%" align="left"><b>CUSTOMER</b></td>
            <td class="border-0" scope="col" width="15%" align="left"></td>
        </tr>
        </thead>
    </table>
    <div style="height: 55vh;overflow-y: auto;">
        <table class="table text-white txtdemibold" style="width: 96%;margin-left: 15px;">
            <tbody class="thh">
            @foreach($order_obj as $order)
                <tr>
                    <td width="15%" style="padding-left: 0;">{{ $order->display_time }}</td>
                    <td width="30%" style="padding-left: 4px;">{{ $order->table_display_name }}</td>
                    <td width="15%" style="padding-left: 4px;">{{ $order->amount }}</td>
                    <td width="25%" style="padding-left: 6px;">{{ $order->order->customer_name }}</td>
                    <td width="15%" style="text-align:center;padding-left: 9px;">

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<input type="hidden" id="daily_all_amount" value="{{ $daily_all_amount }}">
